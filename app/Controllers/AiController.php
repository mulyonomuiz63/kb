<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;
use Smalot\PdfParser\Parser;

class AiController extends Controller
{
    protected $apiKey;
    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }
    public function index()
    {
        $data = [
            'title' => 'NDTAXANDLAW AI',
            'hasil' => null,
            'prompt' => null
        ];
        return view('list', $data);
    }

    public function processResume()
    {
        $file = $this->request->getFile('pdf_file');
        $userPrompt = $this->request->getPost('prompt');

        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid.');
        }

        // Tambahkan instruksi JSON ke prompt user
        $fullPrompt = $userPrompt . "\n\nWAJIB: Berikan output dalam format JSON murni dengan key kecil (snake_case) sesuai 21 poin tersebut.";

        $pdfData = base64_encode(file_get_contents($file->getTempName()));
        $hasilRaw = $this->askGeminiWithPDF($pdfData, $fullPrompt);

        // 1. Bersihkan output dari tag ```json ... ``` jika ada
        $hasilClean = str_replace(['```json', '```'], '', $hasilRaw);
        $hasilClean = trim($hasilClean);

        // 2. Coba decode untuk memastikan ini JSON valid
        $hasilArray = json_decode($hasilClean, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // Jika AI gagal memberikan JSON, simpan sebagai teks biasa (fallback)
            $isJson = false;
            $contentToSave = $hasilRaw;
        } else {
            $isJson = true;
            $contentToSave = $hasilClean; // Simpan string JSON-nya
        }

        $db = \Config\Database::connect();
        $db->table('resumes')->insert([
            'title'      => ($isJson && isset($hasilArray['nama_wp'])) ? 'Analisis: ' . $hasilArray['nama_wp'] : 'Analisis Pajak - ' . date('Y-m-d H:i'),
            'content'    => $contentToSave, // Ini yang akan dipanggil di menu laporan
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return view('list', [
            'title'  => 'NDTAXANDLAW AI',
            'hasil'  => $isJson ? $hasilArray : $hasilRaw, // Jika JSON, kirim array agar mudah dipanggil di view
            'isJson' => $isJson,
            'prompt' => $userPrompt
        ]);
    }

    private function askGeminiWithPDF($base64Pdf, $prompt)
    {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent";

        $client = \Config\Services::curlrequest();

        // Struktur JSON untuk mengirim Dokumen + Teks
        $payload = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt],
                        [
                            "inline_data" => [
                                "mime_type" => "application/pdf",
                                "data" => $base64Pdf
                            ]
                        ]
                    ]
                ]
            ],
        ];


        try {
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-goog-api-key' => $this->apiKey, // API Key dikirim lewat Header sesuai dokumentasi
                ],
                'body' => json_encode($payload),
                'http_errors' => false
            ]);

            $result = json_decode($response->getBody(), true);

            if ($response->getStatusCode() !== 200) {
                return "Error (" . $response->getStatusCode() . "): " . ($result['error']['message'] ?? 'Gagal memproses dokumen');
            }
            $rawText = $result['candidates'][0]['content']['parts'][0]['text'] ?? "";

            // MEMBERSIHKAN TANDA BINTANG DAN MARKDOWN SISA
            $cleanText = str_replace(['*', '#', '`'], '', $rawText);
            $cleanText = trim($cleanText);

            return $cleanText;
        } catch (\Exception $e) {
            return "Koneksi Gagal: " . $e->getMessage();
        }
    }

    // public function processResume()
    // {
    //     $file = $this->request->getFile('pdf_file');
    //     $userPrompt = $this->request->getPost('prompt');

    //     if (!$file->isValid()) {
    //         return redirect()->back()->with('error', 'File tidak valid.');
    //     }

    //     // 1. Persiapkan data PDF
    //     $pdfData = base64_encode(file_get_contents($file->getTempName()));

    //     // 2. Mulai Research Task (Metode Interactions)
    //     $interactionId = $this->startDeepResearch($pdfData, $userPrompt);

    //     if (strpos($interactionId, 'Error') !== false) {
    //         return redirect()->back()->with('error', $interactionId);
    //     }

    //     // 3. Polling Hasil (Karena ini demo, kita coba tunggu sebentar atau arahkan ke halaman polling)
    //     // Untuk hasil terbaik, arahkan user ke halaman loading yang mengecek ID ini
    //     return view('loading', [
    //         'interaction_id' => $interactionId,
    //         'title' => 'Deep Researching...'
    //     ]);
    // }

    // private function startDeepResearch($base64Pdf, $prompt)
    // {
    //     $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent";
    //     $client = Services::curlrequest();

    //     $payload = [
    //         "input" => $prompt,
    //         "agent" => "deep-research-pro-preview-12-2025",
    //         "background" => true,
    //         "contents" => [
    //             [
    //                 "parts" => [
    //                     ["text" => "Gunakan data PDF ini sebagai sumber utama research."],
    //                     [
    //                         "inline_data" => [
    //                             "mime_type" => "application/pdf",
    //                             "data" => $base64Pdf
    //                         ]
    //                     ]
    //                 ]
    //             ]
    //         ]
    //     ];

    //     try {
    //         $response = $client->request('POST', $url, [
    //             'headers' => [
    //                 'Content-Type'   => 'application/json',
    //                 'x-goog-api-key' => $this->apiKey,
    //             ],
    //             'body' => json_encode($payload),
    //             'http_errors' => false
    //         ]);

    //         $result = json_decode($response->getBody(), true);

    //         // Interaction ID biasanya ada di field 'name' atau 'interactionId'
    //         return $result['name'] ?? "Error: Gagal mendapatkan Interaction ID";
    //     } catch (\Exception $e) {
    //         return "Error: " . $e->getMessage();
    //     }
    // }

    // // Fungsi untuk dicek via AJAX oleh View
    // // Fungsi untuk menyimpan hasil ke database
    // public function save_result()
    // {
    //     // Ambil hasil teks yang sudah dibersihkan dari AJAX
    //     $hasilAkhir = $this->request->getPost('hasil_akhir');
    //     $promptAsli = $this->request->getPost('prompt');

    //     if (empty($hasilAkhir)) {
    //         return redirect()->to('/gemini')->with('error', 'Hasil riset kosong.');
    //     }

    //     // 1. Logika Simpan ke Database (Contoh menggunakan Query Builder)
    //     $db = \Config\Database::connect();
    //     $db->table('resumes')->insert([
    //         'title'      => 'Analisis Pajak - ' . date('Y-m-d H:i'),
    //         'content'    => $hasilAkhir,
    //         'created_at' => date('Y-m-d H:i:s')
    //     ]);

    //     // 2. Tampilkan hasil akhir ke view 'list'
    //     return view('list', [
    //         'title'  => 'Hasil Deep Research Selesai',
    //         'hasil'  => $hasilAkhir,
    //         'prompt' => $promptAsli
    //     ]);
    // }

    // // Fungsi pendukung untuk mengecek status (Interaction ID)
    // public function checkStatus($id)
    // {
    //     // Interaction ID biasanya dikirim dengan format 'interactions/XXXXX'
    //     // Jika hanya ID-nya saja, kita tambahkan prefixnya
    //     $interactionName = (strpos($id, 'interactions/') === false) ? "interactions/" . $id : $id;

    //     $url = "https://generativelanguage.googleapis.com/v1beta/" . $interactionName;

    //     $client = \Config\Services::curlrequest();

    //     try {
    //         $response = $client->request('GET', $url, [
    //             'headers' => [
    //                 'x-goog-api-key' => $this->apiKey,
    //                 'Content-Type'   => 'application/json'
    //             ],
    //             'http_errors' => false
    //         ]);

    //         return $this->response->setJSON(json_decode($response->getBody(), true));
    //     } catch (\Exception $e) {
    //         return $this->response->setJSON(['error' => $e->getMessage()]);
    //     }
    // }
}

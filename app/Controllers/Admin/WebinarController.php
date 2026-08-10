<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DetailTransaksiModel;
use App\Models\SiswaModel;
use App\Models\TransaksiModel;
use App\Models\WebinarSesiModel;

class WebinarController extends BaseController
{
    protected $webinarSesiModel;
    protected $detailPaketModel;
    protected $paketModel;
    protected $transaksiModel;
    protected $siswaModel;
    protected $detailTransaksiModel;

    public function __construct()
    {
        $this->webinarSesiModel = new WebinarSesiModel();
        $this->detailPaketModel = new \App\Models\DetailPaketModel();
        $this->siswaModel = new SiswaModel();
        $this->transaksiModel = new TransaksiModel();
        $this->detailTransaksiModel = new DetailTransaksiModel();
        $this->paketModel = new \App\Models\PaketModel();
    }

    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'List Webinar Sesi', 'url' => '#'],
        ];

        // Mengirimkan seluruh data sesi untuk pilihan multi-select sesi bonus/gratis di form
        $data['allSesi'] = $this->webinarSesiModel->findAll();

        return view('admin/webinar/list', $data);
    }

    public function datatables()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('auth');
        }

        $request = $this->request;
        $postData = $request->getPost();

        $draw = isset($postData['draw']) ? (int)$postData['draw'] : 1;
        $start = isset($postData['start']) ? (int)$postData['start'] : 0;
        $rowperpage = isset($postData['length']) ? (int)$postData['length'] : 10;
        $searchValue = $postData['search']['value'] ?? '';

        $builder = $this->webinarSesiModel;

        $totalRecords = $builder->countAllResults(false);

        if ($searchValue != '') {
            $builder->like('nama_sesi', $searchValue);
        }

        $totalRecordwithFilter = $builder->countAllResults(false);

        $records = $builder->orderBy('id_sesi', 'DESC')
            ->limit($rowperpage, $start)
            ->get()
            ->getResult();

        $currentDateTime = date('Y-m-d H:i:s');
        $data = [];

        // Inisialisasi DB di luar loop untuk menghitung jumlah peserta nanti
        $db = \Config\Database::connect();

        foreach ($records as $record) {
            $id_encrypt = encrypt_url($record->id_sesi);

            // Penentuan Status Sesi Berdasarkan Waktu
            if ($record->waktu_selesai < $currentDateTime) {
                $statusHtml = '<span class="badge badge-light-secondary fw-bold px-3 py-2"><i class="ki-outline ki-check fs-7 me-1"></i> Selesai</span>';
            } elseif ($record->waktu_mulai <= $currentDateTime && $record->waktu_selesai >= $currentDateTime) {
                $statusHtml = '<span class="badge badge-light-success fw-bold px-3 py-2"><span class="bullet bullet-dot bg-success me-1"></span> Berjalan</span>';
            } else {
                $statusHtml = '<span class="badge badge-light-primary fw-bold px-3 py-2"><span class="bullet bullet-dot bg-primary me-1"></span> Akan Datang</span>';
            }

            // Parsing JSON link_zoom
            $zoomLinks = json_decode($record->link_zoom, true) ?? [];
            $zoomHtml = '';
            foreach ($zoomLinks as $zl) {
                $zoomHtml .= '<a href="' . esc($zl) . '" target="_blank" class="badge badge-light-primary mb-1 me-1 text-truncate" style="max-width:150px;"><i class="ki-duotone ki-video fs-6 me-1"></i> Zoom Link</a>';
            }

            // Parsing JSON link_youtube
            $ytLinks = json_decode($record->link_youtube, true) ?? [];
            $ytHtml = '';
            foreach ($ytLinks as $yl) {
                $ytHtml .= '<a href="' . esc($yl) . '" target="_blank" class="badge badge-light-danger mb-1 me-1 text-truncate" style="max-width:150px;"><i class="ki-duotone ki-youtube fs-6 me-1"></i> YT Link</a>';
            }

            // Parsing Sesi Bonus / Gratis Terkait
            $bonusIds = json_decode($record->sesi_gratis, true) ?? [];
            $bonusHtml = '<div class="d-flex flex-wrap gap-1" style="max-width: 250px; white-space: normal;">';
            if (!empty($bonusIds)) {
                $bonusSessions = $this->webinarSesiModel->whereIn('id_sesi', $bonusIds)->findAll();
                foreach ($bonusSessions as $bs) {
                    $bonusHtml .= '<span class="badge badge-light-info text-break text-start" style="white-space: normal;">' . esc($bs['nama_sesi']) . '</span>';
                }
            } else {
                $bonusHtml .= '<span class="text-muted fs-7">Tidak ada</span>';
            }
            $bonusHtml .= '</div>';

            // =========================================================================================
            // TAMBAHAN: Menghitung jumlah peserta yang sudah beli / daftar pada sesi ini
            // (Hanya menghitung yang status transaksinya 'S' (Selesai) atau 'M' (Menunggu Pembayaran))
            // =========================================================================================
            $jumlah_peserta = $db->table('detail_transaksi')
                ->join('transaksi', 'transaksi.idtransaksi = detail_transaksi.idtransaksi')
                ->where('detail_transaksi.idsesi', $record->id_sesi)
                ->whereIn('transaksi.status', ['S']) 
                ->countAllResults();

            $pesertaHtml = '<span class="badge badge-light-success fw-bold px-3 py-2"><i class="ki-duotone ki-profile-user fs-6 me-1"></i> ' . $jumlah_peserta . ' Peserta</span>';
            // =========================================================================================

            $opsi = '
            <div class="d-flex justify-content-end">
                <a href="javascript:void(0)" 
                    data-id="' . $id_encrypt . '" 
                    class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 edit-webinar"
                    data-bs-toggle="tooltip" 
                    data-bs-placement="top" 
                    title="Edit Webinar Sesi">
                    <i class="ki-duotone ki-pencil fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </a>
            </div>';

            $data[] = [
                "nama_sesi"    => '<div class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6 text-break" style="white-space: normal; word-break: break-word;">' . esc($record->nama_sesi) . '</div>',
                "waktu"        => '<div class="fs-7 text-muted text-break" style="white-space: normal;"><b>Mulai:</b> ' . esc($record->waktu_mulai) . '<br><b>Selesai:</b> ' . esc($record->waktu_selesai) . '</div>',
                "harga_sesi"   => '<span class="fw-bold text-success text-break">Rp ' . number_format($record->harga_sesi, 0, ',', '.') . '</span>',
                "status"       => $statusHtml,
                "jumlah_peserta" => $pesertaHtml, 
                "sesi_gratis"  => $bonusHtml,
                "link_zoom"    => '<div class="d-flex flex-wrap text-break">' . ($zoomHtml ?: '<span class="text-muted fs-7">Tidak ada</span>') . '</div>',
                "link_youtube" => '<div class="d-flex flex-wrap text-break">' . ($ytHtml ?: '<span class="text-muted fs-7">Tidak ada</span>') . '</div>',
                "opsi"         => $opsi
            ];
        }

        $response = [
            "draw" => $draw,
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "data" => $data,
            csrf_token() => csrf_hash()
        ];

        return $this->response->setJSON($response);
    }

    public function store()
    {
        try {
            $zoomInput = $this->request->getVar('link_zoom');
            $ytInput = $this->request->getVar('link_youtube');
            $sesiGratisInput = $this->request->getVar('sesi_gratis') ?? [];

            $zoomArray = array_filter(array_map('trim', preg_split('/[\r\n,]+/', $zoomInput)));
            $ytArray = array_filter(array_map('trim', preg_split('/[\r\n,]+/', $ytInput)));

            // =========================================================================
            // TAMBAHAN: Upload File Materi (PDF) - Mendukung Banyak File
            // =========================================================================
            $path = FCPATH . 'uploads/webinar/materi';
            if (!is_dir($path)) {
                mkdir($path, 0777, true); // Buat folder otomatis jika belum ada
            }

            $uploadedFiles = [];
            if ($files = $this->request->getFiles()) {
                if (isset($files['file_materi'])) {
                    // Pastikan format menjadi array meski yang diunggah hanya 1 file
                    $materiFiles = is_array($files['file_materi']) ? $files['file_materi'] : [$files['file_materi']];
                    foreach ($materiFiles as $file) {
                        if ($file->isValid() && !$file->hasMoved()) {
                            $newName = $file->getRandomName();
                            $file->move($path, $newName);
                            // Simpan link URL lengkap ke dalam array
                            $uploadedFiles[] = base_url('uploads/webinar/materi/' . $newName);
                        }
                    }
                }
            }
            // =========================================================================

            $data = [
                'nama_sesi'      => $this->request->getVar('nama_sesi'),
                'deskripsi_sesi' => $this->request->getVar('deskripsi_sesi'),
                'waktu_mulai'    => $this->request->getVar('waktu_mulai'),
                'waktu_selesai'  => $this->request->getVar('waktu_selesai'),
                'harga_sesi'     => $this->request->getVar('harga_sesi'),
                'link_zoom'      => json_encode(array_values($zoomArray)),
                'link_youtube'   => json_encode(array_values($ytArray)),
                'sesi_gratis'    => json_encode(array_values($sesiGratisInput)),
                'status'         => $this->request->getVar('status'),
                'file_materi'    => !empty($uploadedFiles) ? json_encode(array_values($uploadedFiles)) : null
            ];

            if ($this->webinarSesiModel->insert($data)) {
                return redirect()->to('sw-admin/webinar')->with('success', 'Webinar sesi berhasil disimpan');
            } else {
                return redirect()->to('sw-admin/webinar')->with('error', 'Gagal menyimpan data');
            }
        } catch (\Exception $e) {
            return redirect()->to('sw-admin/webinar')->with('error', $e->getMessage());
        }
    }

    public function edit()
    {
        if ($this->request->isAJAX()) {
            try {
                $id_encrypted = $this->request->getVar('id_sesi');
                $id_decrypted = decrypt_url($id_encrypted);

                $record = $this->webinarSesiModel->find($id_decrypted);

                if ($record) {
                    $zoomArray = json_decode($record['link_zoom'], true) ?? [];
                    $ytArray = json_decode($record['link_youtube'], true) ?? [];
                    $sesiGratisArray = json_decode($record['sesi_gratis'], true) ?? [];

                    $record['link_zoom_text'] = implode("\n", $zoomArray);
                    $record['link_youtube_text'] = implode("\n", $ytArray);
                    $record['sesi_gratis_array'] = $sesiGratisArray;
                    $record['token'] = csrf_hash();

                    return $this->response->setJSON($record);
                }
            } catch (\Exception $e) {
                return $this->response->setStatusCode(500)->setJSON(['error' => $e->getMessage()]);
            }
        }
    }

    public function update()
    {
        try {
            $id = $this->request->getVar('id_sesi');

            $zoomInput = $this->request->getVar('link_zoom');
            $ytInput = $this->request->getVar('link_youtube');
            $sesiGratisInput = $this->request->getVar('sesi_gratis') ?? [];

            $zoomArray = array_filter(array_map('trim', preg_split('/[\r\n,]+/', $zoomInput)));
            $ytArray = array_filter(array_map('trim', preg_split('/[\r\n,]+/', $ytInput)));

            $updateData = [
                'nama_sesi'      => $this->request->getVar('nama_sesi'),
                'deskripsi_sesi' => $this->request->getVar('deskripsi_sesi'),
                'waktu_mulai'    => $this->request->getVar('waktu_mulai'),
                'waktu_selesai'  => $this->request->getVar('waktu_selesai'),
                'harga_sesi'     => $this->request->getVar('harga_sesi'),
                'link_zoom'      => json_encode(array_values($zoomArray)),
                'link_youtube'   => json_encode(array_values($ytArray)),
                'sesi_gratis'    => json_encode(array_values($sesiGratisInput)),
                'status'         => $this->request->getVar('status')
            ];

            // =========================================================================
            // TAMBAHAN: Logika Upload File Materi di Proses Update
            // =========================================================================
            $path = FCPATH . 'uploads/webinar/materi';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $uploadedFiles = [];
            $hasNewFiles = false;

            if ($files = $this->request->getFiles()) {
                if (isset($files['file_materi'])) {
                    $materiFiles = is_array($files['file_materi']) ? $files['file_materi'] : [$files['file_materi']];
                    foreach ($materiFiles as $file) {
                        if ($file->isValid() && !$file->hasMoved()) {
                            $newName = $file->getRandomName();
                            $file->move($path, $newName);
                            $uploadedFiles[] = base_url('uploads/webinar/materi/' . $newName);
                            $hasNewFiles = true;
                        }
                    }
                }
            }

            // Jika ada file PDF baru yang diunggah, timpa array file_materi lama
            // Jika kosong, data lama tetap dipertahankan
            if ($hasNewFiles) {
                $updateData['file_materi'] = json_encode(array_values($uploadedFiles));
            }
            // =========================================================================

            $this->webinarSesiModel->update($id, $updateData);
            

            // =========================================================================
            // TAMBAHAN LOGIKA UPDATE OTOMATIS HARGA PAKET
            // =========================================================================
            $db = \Config\Database::connect();

            // 1. Cari semua paket yang terkait dengan id_sesi yang baru saja diubah
            $affectedPackages = $db->table('detail_paket')
                                   ->select('idpaket')
                                   ->where('id_sesi', $id)
                                   ->get()
                                   ->getResult();

            // 2. Looping semua paket yang terdampak
            foreach ($affectedPackages as $paket) {
                
                // Hitung total harga_sesi untuk idpaket ini (Relasi: detail_paket -> webinar_sesi)
                $totalHargaRow = $db->table('detail_paket')
                                    ->selectSum('webinar_sesi.harga_sesi', 'total_harga')
                                    ->join('webinar_sesi', 'webinar_sesi.id_sesi = detail_paket.id_sesi')
                                    ->where('detail_paket.idpaket', $paket->idpaket)
                                    ->get()
                                    ->getRow();

                $totalHargaBaru = $totalHargaRow->total_harga ?? 0;

                // 3. Update field nominal_paket di tabel paket dengan total harga yang baru
                $db->table('paket')
                   ->where('idpaket', $paket->idpaket)
                   ->update(['nominal_paket' => $totalHargaBaru]);
            }
            // =========================================================================
            // AKHIR TAMBAHAN LOGIKA
            // =========================================================================


            return redirect()->to('sw-admin/webinar')->with('success', 'Webinar sesi berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->to('sw-admin/webinar')->with('error', 'Gagal mengubah data');
        }
    }

    public function sertifikat($id_siswaen, $id_sesien)
    {
        // 1. Data Retrieval
        $id_siswa = decrypt_url($id_siswaen); // Ambil ID siswa dari session
        $id_sesi   = decrypt_url($id_sesien);
        $id_target = (int) $id_sesi;       // ID Sesi yang ingin dilihat sertifikatnya

        $dataSesi = $this->transaksiModel
            ->select('ws_target.*, paket.nama_paket')
            ->join('detail_transaksi', 'transaksi.idtransaksi = detail_transaksi.idtransaksi')
            ->join('paket', 'detail_transaksi.idpaket = paket.idpaket')
            ->join('siswa', 'transaksi.idsiswa = siswa.id_siswa')
            // Ambil data detail dari webinar_sesi target yang ingin dicetak sertifikatnya
            ->join('webinar_sesi ws_target', 'ws_target.id_sesi = ' . $id_target)
            ->where('transaksi.status', 'S')
            ->where('transaksi.idsiswa', $id_siswa)
            ->groupStart()
                // KONDISI 1: Sesi yang diminta dibeli secara langsung sebagai sesi utama
                ->where('detail_transaksi.idsesi', $id_target)
                // KONDISI 2: Sesi yang diminta ada di dalam daftar JSON `sesi_gratis` dari sesi utama yang dibeli
                ->orWhere("EXISTS (
                    SELECT 1 FROM webinar_sesi ws_parent 
                    WHERE ws_parent.id_sesi = detail_transaksi.idsesi 
                    AND (
                        ws_parent.sesi_gratis LIKE '%\"{$id_target}\"%' 
                        OR ws_parent.sesi_gratis LIKE '%[{$id_target},%' 
                        OR ws_parent.sesi_gratis LIKE '%,{$id_target},%' 
                        OR ws_parent.sesi_gratis LIKE '%,{$id_target}]%'
                    )
                )", null, false)
            ->groupEnd()
            ->get()
            ->getRow();
        $dataSiswa     = $this->siswaModel->where('id_siswa', $id_siswa)->get()->getRow();


        if (!$dataSesi || !$dataSiswa) {
            return "Data tidak ditemukan";
        }

        // 2. Inisialisasi PDF (Landscape - A4: 297 x 210 mm)
        $pdf = new \setasign\Fpdi\Fpdi();
        $pdf->SetAutoPageBreak(false, 5);
        $pdf->AddPage('L', 'A4');

        // Metadata
        $pdf->SetCreator("kelasbrevet.com");
        $pdf->SetAuthor(strtoupper($dataSiswa->nama_siswa));
        $pdf->SetTitle(strtoupper($dataSesi->nama_sesi) . ' - SERTIFIKAT');
        $pdf->SetSubject('SERTIFIKAT ' . strtoupper($dataSesi->nama_sesi) . ' - SERTIFIKAT');
        $pdf->SetKeywords('KelasBrevet, Pajak, Webinar');

        // 3. Background Image (Sesuai Permintaan)
        $bgImg = 'uploads/webinar/sertifikat/background.jpeg';
        $pdf->Image($bgImg, 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());

        // 4. Helper Format Tanggal & Nomor
        $arrBulan        = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $arrBulanRomawi  = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

        $timeStart   = strtotime($dataSesi->waktu_mulai);
        $idSesi   = str_pad($dataSesi->id_sesi, 3, '0', STR_PAD_LEFT);
        // Bisa disesuaikan jika ingin statis 8 Agustus 2026 atau dinamis berdasarkan start_ujian
        $tglSertif   = date('d', $timeStart) . ' ' . $arrBulan[(int)date('m', $timeStart)] . ' ' . date('Y', $timeStart);
        $nomorSertif = $idSesi .' - ' . $dataSiswa->id_siswa . '/WEBINAR-BREVET/' . $arrBulanRomawi[(int)date('m', $timeStart)] . '/' . date('Y', $timeStart);

        // =========================================================
        // 5. PENULISAN KONTEN DINAMIS (Rata Tengah)
        // =========================================================

        // A. NOMOR SERTIFIKAT (Di atas teks "diberikan kepada:")
        $pdf->SetTextColor(51, 49, 49); // Warna Abu-abu gelap / Hitam
        $pdf->SetFont('Arial', 'BU', 16);
        // Posisi Y: 75 (Silakan naik/turunkan angka 75 jika kurang pas dengan background)
        $pdf->SetXY(10, 55);
        // Lebar 0 agar membentang penuh dari kiri ke kanan, 'C' untuk Center
        $pdf->Cell(0, 5, "Nomor: " . $nomorSertif, 0, 1, 'C');

        // B. NAMA LENGKAP PESERTA
        // Warna Biru (Menyesuaikan desain draft: RGB ~ 23, 107, 195)
        $pdf->SetTextColor(23, 98, 185);
        $pdf->SetFont('Arial', 'B', 24);

        $nama_siswa = ucwords(strtoupper($dataSiswa->nama_siswa));

        // Jika panjang karakter nama lebih dari 20 huruf, otomatis turunkan ukuran font-nya
        if (strlen($nama_siswa) > 20) {
            $pdf->SetFont('Arial', 'B', 20); // Font diperkecil jadi 28
        } elseif (strlen($nama_siswa) > 30) {
            $pdf->SetFont('Arial', 'B', 18); // Font diperkecil jadi 20 jika sangat panjang
        }

        $pdf->SetXY(8, 88);
        $pdf->Cell(0, 10, $nama_siswa, 0, 1, 'C');

        // C. DESKRIPSI KEGIATAN
        $pdf->SetTextColor(51, 49, 49); // Kembali ke warna Hitam
        $pdf->SetFont('Arial', '', 12);

        $namaWebinar = $dataSesi->nama_paket ?? 'Webinar Perpajakan';
        $teksTema = !empty($dataSesi->nama_sesi) ? "dengan tema \"" . $dataSesi->nama_sesi . "\"\n" : "";

        $deskripsi = "Atas partisipasinya sebagai Peserta " . $namaWebinar . "\n"
                   . "Yang diselenggarakan oleh Kelas Brevet\n"
                   . $teksTema
                   . "Pada " . $tglSertif;

        // Karena FPDF MultiCell mengukur dari margin kiri, kita harus hitung posisi X 
        // agar kotaknya persis berada di tengah kertas.
        $lebar_teks = 200; // Lebar area teks
        $posisi_x = ($pdf->getPageWidth() - $lebar_teks) / 2;

        $pdf->SetXY($posisi_x, 110); // Posisi Y: 135
        $pdf->MultiCell($lebar_teks, 6, $deskripsi, 0, 'C');

        // =========================================================

        // 6. Output
        $isDownload = $this->request->getGet('download');
        $outputMode = $isDownload ? 'D' : 'I'; // 'D' = Download File, 'I' = Preview di Iframe

        $this->response->setContentType('application/pdf');
        $pdf->Output(strtoupper($dataSiswa->nama_siswa) . '-SERTIFIKAT.pdf', $outputMode);
        exit;
    }
}
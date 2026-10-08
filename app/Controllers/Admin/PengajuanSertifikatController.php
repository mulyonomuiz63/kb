<?php

namespace App\Controllers\Admin; // Sesuaikan dengan namespace folder Anda

use App\Controllers\BaseController;
use CodeIgniter\Database\Config;

class PengajuanSertifikatController extends BaseController
{
    protected $db;
    protected $siswaModel;
    protected $serviceEmail;
    protected $pengajuanSertifikatModel;

    public function __construct()
    {
        $this->db = Config::connect();
        $this->siswaModel = new \App\Models\SiswaModel();
        $this->pengajuanSertifikatModel = new \App\Models\PengajuanSertifikatModel();
        $this->serviceEmail = new \App\Libraries\Emailer();
    }

    // 1. Tampilkan Halaman View Admin
    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'List Pengajuan Sertifikat', 'url' => '#'],
        ];
        
        // Sesuaikan dengan path file view admin yang Anda buat sebelumnya
        return view('admin/sertifikat/pengajuan-sertifikat', $data); 
    }

    // 2. Fetch Data untuk DataTables (Server-Side)
    public function getDataPengajuan()
    {
        // Cegah akses langsung melalui URL browser
        if (!$this->request->isAJAX()) {
            return redirect()->to('/');
        }

        try {
            $builder = $this->db->table('pengajuan_sertifikat a')
                ->select('a.*, b.status as status_bayar')
                ->join('transaksi b', 'a.idtransaksi = b.idtransaksi', 'left');

            // Fitur Pencarian (Search)
            $searchValue = $this->request->getPost('search')['value'] ?? '';
            if (!empty($searchValue)) {
                $builder->groupStart()
                    ->like('a.nama_penerima', $searchValue)
                    ->orLike('a.idtransaksi', $searchValue)
                    ->orLike('a.no_hp', $searchValue)
                    ->orLike('a.no_resi', $searchValue)
                    ->groupEnd();
            }

            // Fitur Pagination (Limit & Offset)
            $start  = $this->request->getPost('start') ?? 0;
            $length = $this->request->getPost('length') ?? 10;

            // Menghitung total data
            $totalRecords = $this->db->table('pengajuan_sertifikat')->countAllResults();
            
            // Menghitung total data setelah difilter pencarian
            $recordsFiltered = $builder->countAllResults(false); 

            // Eksekusi pengambilan data
            $builder->orderBy('a.created_at', 'DESC');
            if ($length != -1) {
                $builder->limit($length, $start);
            }
            $records = $builder->get()->getResult();

            $data = [];
            $no = $start + 1;

            foreach ($records as $row) {
                $data[] = [
                    "no"                => $no++,
                    "id_pengajuan"      => $row->id_pengajuan, // Primary Key
                    "idtransaksi"       => $row->idtransaksi,
                    "tanggal_pengajuan" => date('d M Y H:i', strtotime($row->created_at)),
                    "nama_penerima"     => esc($row->nama_penerima),
                    "no_hp"             => esc($row->no_hp),
                    "alamat_lengkap"    => esc($row->alamat_lengkap),
                    "kode_pos"          => esc($row->kode_pos),
                    "status_bayar"      => $row->status_bayar,
                    "status_pengiriman" => $row->status_pengiriman,
                    "no_resi"           => esc($row->no_resi)
                ];
            }

            // Format pengembalian JSON standar untuk DataTables Server-Side
            return $this->response->setJSON([
                "draw"            => intval($this->request->getPost('draw')),
                "recordsTotal"    => $totalRecords,
                "recordsFiltered" => $recordsFiltered,
                "data"            => $data,
                csrf_token()      => csrf_hash()
            ]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'error'        => $e->getMessage(),
                csrf_token()   => csrf_hash()
            ]);
        }
    }

    // 3. Proses Update Status & Nomor Resi
    public function updatePengiriman()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/');
        }

        try {
            $id_pengajuan      = $this->request->getPost('id_pengajuan');
            $status_pengiriman = $this->request->getPost('status_pengiriman');
            $no_resi           = $this->request->getPost('no_resi');

            // Validasi Input Dasar
            if (empty($id_pengajuan) || empty($status_pengiriman)) {
                throw new \Exception("Data tidak lengkap, gagal memproses.");
            }

            // 1. PROTEKSI ERROR NULL: Pastikan data pengajuan benar-benar ada di database
            $dataPengajuan = $this->pengajuanSertifikatModel->find($id_pengajuan);
            if (!$dataPengajuan) {
                throw new \Exception("Data pengajuan tidak ditemukan.");
            }

            // Validasi: Jika status diubah jadi 'dikirim', resi wajib ada
            if ($status_pengiriman === 'dikirim' && empty(trim($no_resi))) {
                throw new \Exception("Nomor resi wajib diisi jika status pengiriman adalah 'Dikirim'.");
            }

            // Proses Update ke Database
            $updateData = [
                'status_pengiriman' => $status_pengiriman,
                'no_resi'           => ($status_pengiriman === 'menunggu') ? null : $no_resi, // Hapus resi jika dikembalikan ke menunggu
                'updated_at'        => date('Y-m-d H:i:s')
            ];

            $this->db->table('pengajuan_sertifikat')
                ->where('id_pengajuan', $id_pengajuan)
                ->update($updateData);

            // 2. LOGIKA AMAN: Hanya kirim email & notif jika statusnya BENAR-BENAR "dikirim"
            // (Mencegah email "Sertifikat Dikirim" terkirim saat admin mengubah status ke "menunggu" atau "diproses")
            if ($status_pengiriman === 'dikirim') {
                
                // 3. PROTEKSI XSS (KEAMANAN): Gunakan esc() agar input resi & nama aman dari injeksi script
                $resi_aman = esc($no_resi);
                
                send_notif($dataPengajuan['id_siswa'], "Sertifikat Telah Dikirim", "Sertifikat Anda telah dikirim. Nomor resi: " . $resi_aman);

                $dataSiswa = $this->siswaModel->find($dataPengajuan['id_siswa']);
                if ($dataSiswa) {
                    $nama_aman = esc($dataSiswa['nama_siswa']);
                    $this->serviceEmail->send(
                        $dataSiswa['email'],
                        "Sertifikat Telah Dikirim - KelasBrevet",
                        "Halo <b>{$nama_aman}</b>,<br>Sertifikat Anda telah dikirim. Nomor resi: " . $resi_aman . " untuk melacak pengiriman, silakan gunakan nomor resi tersebut di https://jne.co.id/tracking-package.<br><br>Terima kasih telah menggunakan layanan kami."
                    );
                }
            }

            return $this->response->setJSON([
                'status'       => 'success',
                'message'      => 'Status pengiriman berhasil diperbarui!',
                csrf_token()   => csrf_hash()
            ]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'       => 'error',
                'message'      => $e->getMessage(),
                csrf_token()   => csrf_hash()
            ]);
        }
    }
    // ==========================================
    // FUNGSI BARU: KIRIM WA KONFIRMASI SERTIFIKAT
    // ==========================================
    public function kirimWaKonfirmasi()
    {
        // Cegah akses selain AJAX
        if (!$this->request->isAJAX()) {
            return redirect()->to('/');
        }

        try {
            $destinationWa = $this->request->getPost('wa_no_hp');
            $paramNama     = $this->request->getPost('wa_nama');

            if (empty($destinationWa)) {
                throw new \Exception("Nomor WhatsApp tidak tersedia.");
            }

            // 1. Deteksi Environment
            $is_production = false;
            if (isset($_SERVER['CI_ENVIRONMENT']) && $_SERVER['CI_ENVIRONMENT'] === 'production') {
                $is_production = true;
            } elseif (defined('ENVIRONMENT') && ENVIRONMENT === 'production') {
                $is_production = true;
            }

            // 2. Siapkan data berdasarkan Environment
            if ($is_production) {
                // MODE WABA OFFICIAL: Menggunakan Template
                $data_template = [
                    "template_name"     => "konfirmasi_pengajuan_sertifikat", // Sesuai perintah
                    "template_language" => "id",
                    "parameter"         => [
                        [
                            "name" => $paramNama // Hanya mengirimkan parameter name
                        ]
                    ],
                    "apps_source"       => "kelasbrevet"
                ];

                $kirim = kirim_wa($destinationWa, '', $data_template);
            } else {
                // MODE UNOFFICIAL (DEVELOPMENT): Menggunakan Teks Manual
                $pesan = "Halo Kak *$paramNama*,\n\nKami ingin mengonfirmasi terkait pengajuan sertifikat fisik Brevet A&B *(Cap Basah)*. Untuk melanjutkan proses ini, silakan menyelesaikan pembayaran terlebih dahulu. Setelah pembayaran terkonfirmasi, sertifikat akan segera kami proses dan kirimkan. Terima kasih.";
                $kirim = kirim_wa($destinationWa, $pesan, []);
            }

            // 3. Evaluasi Response API WA
            if (isset($kirim['status']) && ($kirim['status'] == '200' || $kirim['status'] === true)) {
                return $this->response->setJSON([
                    'status'       => 'success',
                    'message'      => 'Pesan WhatsApp berhasil dikirim!',
                    csrf_token()   => csrf_hash()
                ]);
            } else {
                throw new \Exception("Gagal mengirim WhatsApp: " . ($kirim['message'] ?? 'Kesalahan API Watzap'));
            }

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'       => 'error',
                'message'      => $e->getMessage(),
                csrf_token()   => csrf_hash()
            ]);
        }
    }
}
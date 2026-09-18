<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class HomeController extends BaseController
{
    protected $paketModel;
    protected $affiliateModel;
    public function __construct()
    {
        $this->paketModel = new \App\Models\PaketModel();
        $this->affiliateModel = new \App\Models\AffiliateModel();
    }
    public function index()
    {
        $this->data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-siswa')],
        ];

        $this->data['show_banner'] = true;

        $this->data['affiliate'] =  $this->affiliateModel->where('user_id', session()->get('id'))->first();

        $this->data['paket'] = $this->paketModel->getAll();
        $this->data['paketWebinars'] = $this->paketModel->getPaketWebinar();

        // Pastikan DB instance tersedia di view jika Anda melakukan query di dalam loop
        $this->data['db'] = \Config\Database::connect();

        // =================================================================================
        // AWAL LOGIKA ALERT WEBINAR (Dipindah dari View ke Controller)
        // =================================================================================
        $db = \Config\Database::connect();
        $id_siswa = session()->get('id');
        $currentDateTime = time();

        // 1. Ambil SEMUA data sesi (Tanpa harus ada transaksi)
        // Kita gunakan 'left join' ke paket agar jika ada sesi yg tidak terikat paket, tetap muncul
        $dataWebinar = $db->table('webinar_sesi')
            ->select('webinar_sesi.*')
            ->get()
            ->getResult();

        $activeAlerts = [];

        foreach ($dataWebinar as $w) {
            // 2. Decode sesi_gratis untuk mengecek isinya
            $childIds = json_decode($w->sesi_gratis, true) ?? [];

            // 3. KONDISI 1: Hanya proses jika sesi_gratis KOSONG (berarti nilainya [] atau null)
            if (empty($childIds)) {

                $waktuMulai   = strtotime($w->waktu_mulai);
                $waktuSelesai = strtotime($w->waktu_selesai);
                $waktuBukaZoom = $waktuMulai - (3 * 3600); // 3 Jam sebelum mulai

                // 4. KONDISI 2: Jika waktu sekarang berada di antara 3 jam sebelum mulai dan waktu selesai
                if ($currentDateTime >= $waktuBukaZoom && $currentDateTime <= $waktuSelesai) {

                    // Ekstrak Link Gmeet/Zoom
                    $zoomLinks = json_decode($w->link_zoom, true) ?? [];
                    // Jika formatnya array ambil index 0, jika text biasa langsung ambil datanya
                    $w->mainZoomLink = !empty($zoomLinks[0]) ? $zoomLinks[0] : $w->link_zoom;

                    // Penanda apakah benar-benar sedang live atau sekadar persiapan (menunggu mulai)
                    $w->is_live = ($currentDateTime >= $waktuMulai);
                    $w->waktu_mulai_format = $waktuMulai;
                    $w->waktu_selesai_format = $waktuSelesai;

                    // Set nama paket parent untuk ditampilkan di <h2> View
                    // Jika sesi ini tidak punya paket, beri nama default
                    $w->nama_paket_parent = $w->nama_paket ?? 'Webinar Terbuka / Gratis';

                    // Masukkan ke array alert yang akan di tampilkan
                    $activeAlerts[] = $w;
                }
            }
        }

        // 5. Masukkan variabel $activeAlerts ke $this->data agar bisa dibaca di View
        $this->data['activeAlerts'] = $activeAlerts;
        // =================================================================================
        // AKHIR LOGIKA ALERT WEBINAR
        // =================================================================================

        $cekTransaksi = $db->table('transaksi')
            ->where('idsiswa', $id_siswa)
            ->where('status', 'S')
            ->groupStart() // Membuka kurung query agar kondisi OR tidak merusak WHERE idsiswa
            ->like('jenis_paket', '"brevet"') // Mencari string "brevet" di dalam array
            ->orLike('jenis_paket', '"ikh"')  // ATAU mencari string "ikh" di dalam array
            ->groupEnd()
            ->countAllResults();

        // Kirim variabel ini ke View
        $this->data['showWaAlert'] = ($cekTransaksi > 0);

        // untuk top 10
        $sql = "
        SELECT 
            t.id_siswa, 
            s.nama_siswa, 
            COUNT(t.mapel) as total_mapel, 
            AVG(t.max_nilai) as rata_rata_siswa,
            MAX(t.end_ujian) as end_ujian
        FROM (
            SELECT 
                id_siswa, 
                mapel, 
                MAX(nilai) as max_nilai, 
                -- Mengambil waktu saat mapel tersebut diselesaikan
                MIN(end_ujian) as end_ujian 
            FROM ujian
            WHERE status = 'S' AND nilai >= 60
            GROUP BY id_siswa, mapel
        ) t
        JOIN siswa s ON s.id_siswa = t.id_siswa
        GROUP BY t.id_siswa
        HAVING COUNT(t.mapel) >= 8
        -- URUTKAN: Nilai tertinggi dulu (DESC). Jika nilai sama, waktu tercepat (ASC)
        ORDER BY rata_rata_siswa DESC, end_ujian ASC";

        $siswaStats = $this->db->query($sql)->getResult();
        $listSiswa = [];
        foreach ($siswaStats as $row) {
            $rataRataSiswa = (float) $row->rata_rata_siswa;
            // Simpan data siswa ke array untuk di-ranking
            $listSiswa[] = [
                'id_siswa'  => $row->id_siswa,
                'nama'      => $row->nama_siswa,
                'nilai'     => round($rataRataSiswa)
            ];
        }
        usort($listSiswa, function ($a, $b) {
            return $b['nilai'] <=> $a['nilai'];
        });

        // Potong array agar hanya mengambil 5 urutan pertama (Di code Anda tertulis 10)
        $this->data['top_siswa'] = array_slice($listSiswa, 0, 10);

        return view('siswa/dashboard', $this->data);
    }
}

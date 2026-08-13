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
        $currentDateTime = strtotime(date('Y-m-d H:i:s'));

        // Ambil data paket yang dimiliki siswa 
        // (Ditambahkan paket.nama_paket agar judul H2 di alert tidak error)
        $dataWebinar = $db->table('transaksi')
            ->select('webinar_sesi.*, paket.nama_paket')
            ->join('detail_transaksi', 'transaksi.idtransaksi = detail_transaksi.idtransaksi')
            ->join('paket', 'detail_transaksi.idpaket = paket.idpaket')
            ->join('webinar_sesi', 'detail_transaksi.idsesi = webinar_sesi.id_sesi')
            ->where('transaksi.status', 'S')
            ->where('transaksi.idsiswa', $id_siswa)
            ->groupBy('detail_transaksi.idsesi')
            ->get()
            ->getResult();

        $activeAlerts = [];

        foreach ($dataWebinar as $w) {
            $childIds = json_decode($w->sesi_gratis, true) ?? [];

            if (!empty($childIds)) {
                $childSessions = $db->table('webinar_sesi')
                    ->whereIn('id_sesi', $childIds)
                    ->orderBy('waktu_mulai', 'ASC')
                    ->get()
                    ->getResult();
            } else {
                $childSessions = [$w]; // Fallback jika tidak ada anak
            }

            foreach ($childSessions as $child) {
                $waktuMulai = strtotime($child->waktu_mulai);
                $waktuSelesai = strtotime($child->waktu_selesai);
                $waktuBukaZoom = $waktuMulai - (3 * 3600); // 3 Jam sebelum

                // KONDISI UTAMA: Jika waktu sekarang berada di antara waktu buka zoom dan waktu selesai
                if ($currentDateTime >= $waktuBukaZoom && $currentDateTime <= $waktuSelesai) {

                    // Ekstrak Link Gmeet/Zoom
                    $zoomLinks = json_decode($child->link_zoom, true) ?? [];
                    $child->mainZoomLink = $zoomLinks[0] ?? $child->link_zoom;

                    // Penanda apakah benar-benar sedang live atau sekadar persiapan (2 jam sblm)
                    $child->is_live = ($currentDateTime >= $waktuMulai);
                    $child->waktu_mulai_format = $waktuMulai;
                    $child->waktu_selesai_format = $waktuSelesai;

                    // Set nama paket parent untuk ditampilkan di <h2> View
                    $child->nama_paket_parent = $w->nama_paket ?? 'Paket Pelatihan';

                    $activeAlerts[] = $child;
                }
            }
        }

        // Masukkan variabel $activeAlerts ke $this->data agar bisa dibaca di View
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

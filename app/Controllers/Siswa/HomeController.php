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
        // Pastikan DB instance tersedia di view jika Anda melakukan query di dalam loop
        $this->data['db'] = \Config\Database::connect();

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

        // Potong array agar hanya mengambil 5 urutan pertama
        $this->data['top_siswa'] = array_slice($listSiswa, 0, 10);

        return view('siswa/dashboard', $this->data);
    }
}

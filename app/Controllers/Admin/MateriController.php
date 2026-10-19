<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class MateriController extends BaseController
{
    protected $transaksiModel;
    protected $mapelSiswaModel;
    protected $mapelModel;

    public function __construct()
    {
        $this->transaksiModel = new \App\Models\TransaksiModel();
        $this->mapelSiswaModel = new \App\Models\MapelSiswaModel();
        $this->mapelModel     = new \App\Models\MapelModel();
    }
    public function index($id)
    {
        $userId = decrypt_url($id);
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'Data Peserta', 'url' => base_url('sw-admin/siswa')],
            ['title' => 'List Materi', 'url' => '#'],
        ];

        // 1. Ambil semua idmapel milik siswa dalam satu query
        $siswa = $this->mapelSiswaModel
            ->select('idmapel')
            ->where('idsiswa', $userId)
            ->get()
            ->getResultObject();

        $data['modul'] = array();
        foreach ($siswa as  $r) {
            $modul = $this->mapelModel->getAllIdSiswa($r->idmapel);

            foreach ($modul as $m) {
                $data['modul'][] = $m;
            }
        }

        return view('admin/materi/list', $data);
    }

    public function list()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'Data Peserta', 'url' => base_url('sw-admin/siswa')],
            ['title' => 'Migrasi Materi', 'url' => '#'],
        ];

        // Query untuk mengambil data transaksi tervalidasi ('S') beserta status migrasinya
        // Sesuaikan nama tabel 'siswa' jika berbeda
        $db = \Config\Database::connect();$builder = $db->table('transaksi');$builder->select('transaksi.idsiswa, detail_transaksi.idmapel, siswa.nama_siswa, mapel.nama_mapel, mapel_siswa.idmapelsiswa');
        $builder->join('siswa', 'transaksi.idsiswa = siswa.id_siswa');
        // Anggap Anda punya tabel siswa untuk menampilkan nama:
        // $builder->select('siswa.nama_siswa'); 
        // $builder->join('siswa', 'transaksi.idsiswa = siswa.idsiswa');

        $builder->join('detail_transaksi', 'transaksi.idtransaksi = detail_transaksi.idtransaksi');$builder->join('mapel', 'detail_transaksi.idmapel = mapel.id_mapel');
        // Left join untuk cek status migrasi
        $builder->join('mapel_siswa', 'mapel_siswa.idsiswa = transaksi.idsiswa AND mapel_siswa.idmapel = detail_transaksi.idmapel', 'left');
        $builder->where('transaksi.status', 'S');$builder->groupBy('transaksi.idsiswa, detail_transaksi.idmapel'); // Group by siswa dan mapel

        $data['dataMateri'] =$builder->get()->getResultObject();

        return view('admin/materi/materi-migration', $data);
    }

    // Method untuk Migrasi Per Siswa
    public function migrasiSingle()
    {
        if ($this->request->isAJAX()) {
            $idsiswa =$this->request->getPost('idsiswa');
            $idmapel =$this->request->getPost('idmapel');

            // Cek apakah sudah ada
            $exists =$this->mapelSiswaModel->where('idsiswa', $idsiswa)->where('idmapel',$idmapel)->first();
            
            if (!$exists) {$this->mapelSiswaModel->insert([
                    'idsiswa' => $idsiswa,
                    'idmapel' => $idmapel
                ]);
                return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil dimigrasi', 'csrf' => csrf_hash()]);
            }
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data sudah ada', 'csrf' => csrf_hash()]);
        }
    }

    // Method untuk Migrasi Menyeluruh (Batch / Chunking untuk hindari overload)
    public function migrasiBatch()
    {
        if ($this->request->isAJAX()) {
            $limit = 100; // Proses 100 data per request agar server tidak hank$db = \Config\Database::connect();
            
            // Ambil data yang BELUM ada di mapel_siswa
            $sql = "SELECT t.idsiswa, dt.idmapel 
                    FROM transaksi t
                    JOIN detail_transaksi dt ON t.idtransaksi = dt.idtransaksi
                    JOIN mapel m ON dt.idmapel = m.id_mapel
                    LEFT JOIN mapel_siswa ms ON ms.idsiswa = t.idsiswa AND ms.idmapel = dt.idmapel
                    WHERE t.status = 'S' 
                    AND ms.idmapelsiswa IS NULL
                    AND dt.idmapel IS NOT NULL 
                    AND dt.idmapel != 0 
                    AND dt.idmapel != ''
                    GROUP BY t.idsiswa, dt.idmapel
                    LIMIT $limit";
                    
            $pendingData = $this->db->query($sql)->getResultArray();

            if (empty($pendingData)) {
                return $this->response->setJSON([
                    'status' => 'done', 
                    'message' => 'Semua data telah termigrasi!',
                    'csrf' => csrf_hash()
                ]);
            }

            // Insert data secara batch
            $this->mapelSiswaModel->insertBatch($pendingData);

            // Hitung sisa yang belum termigrasi
            $sqlCount = "SELECT COUNT(DISTINCT t.idsiswa, dt.idmapel) as sisa 
                        FROM transaksi t
                        JOIN detail_transaksi dt ON t.idtransaksi = dt.idtransaksi
                        JOIN mapel m ON dt.idmapel = m.id_mapel
                        LEFT JOIN mapel_siswa ms ON ms.idsiswa = t.idsiswa AND ms.idmapel = dt.idmapel
                        WHERE t.status = 'S' 
                        AND ms.idmapelsiswa IS NULL
                        AND dt.idmapel IS NOT NULL 
                        AND dt.idmapel != 0 
                        AND dt.idmapel != ''";
            $sisa = $this->db->query($sqlCount)->getRow()->sisa;

            return $this->response->setJSON([
                'status' => 'processing',
                'sisa' => $sisa,
                'total_awal'   => $sisa + count($pendingData),
                'migrated_now' => count($pendingData),
                'csrf' => csrf_hash()
            ]);
        }
    }
}

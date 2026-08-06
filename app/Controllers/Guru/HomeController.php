<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;




class HomeController extends BaseController
{

    protected $kelasModel;
    protected $guruModel;
    protected $guruMapelModel;
    protected $guruKelasModel;
    protected $mapelModel;

    public function __construct()
    {
        $this->mapelModel = new \App\Models\MapelModel();
        $this->kelasModel = new \App\Models\KelasModel();
        $this->guruModel = new \App\Models\GuruModel();
        $this->guruMapelModel = new \App\Models\GuruMapelModel();
        $this->guruKelasModel = new \App\Models\GuruKelasModel();
    }

    public function index()
    {
        $id_guru = session()->get('id');

        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-guru')],
        ];

        // Mengambil data master
        $data['mapel'] = $this->mapelModel->asObject()->findAll();
        $data['kelas'] = $this->kelasModel->asObject()->findAll();
        $data['guru']  = $this->guruModel->asObject()->find($id_guru);

        // Mengambil data kelas dan mapel yang diajar oleh guru terkait
        $guru_kelas = $this->guruKelasModel->getALLByGuru($id_guru);
        $guru_mapel = $this->guruMapelModel->getALLByGuru($id_guru);

        // --- PROSES PENGORGANISASIAN DATA ARRAY ---
        $kelas_mapel_array = [];

        if (!empty($guru_kelas)) {
            foreach ($guru_kelas as $gk) {
                $mapel_terkait = []; // Siapkan array kosong untuk menampung mapel per kelas

                if (!empty($guru_mapel)) {
                    foreach ($guru_mapel as $gm) {
                        // Pengecekan: Jika ID Kelas di tabel guru_mapel SAMA DENGAN ID Kelas di tabel guru_kelas
                        if (isset($gm->kelas) && $gm->kelas == $gk->kelas) {
                            $mapel_terkait[] = $gm; // Masukkan mapel ini ke kelas tersebut
                        }
                    }
                }

                // Simpan daftar mapel yang sudah dicocokkan ke dalam kelas
                $gk->daftar_mapel = $mapel_terkait;

                $kelas_mapel_array[] = $gk;
            }
        }

        // Timpa data guru_kelas dengan data yang sudah terstruktur
        $data['guru_kelas'] = $kelas_mapel_array;

        return view('guru/dashboard', $data);
    }
}

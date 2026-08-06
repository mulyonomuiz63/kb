<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;


class RelasiController extends BaseController
{

    protected $guruModel;
    protected $kelasModel;
    protected $mapelModel;
    protected $guruKelasModel;
    protected $guruMapelModel;
    public function __construct()
    {
        $this->guruModel = new \App\Models\GuruModel();
        $this->kelasModel = new \App\Models\KelasModel();
        $this->mapelModel = new \App\Models\MapelModel();
        $this->guruKelasModel = new \App\Models\GuruKelasModel();
        $this->guruMapelModel = new \App\Models\GuruMapelModel();
    }

    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'Data Instruktur', 'url' => base_url('sw-admin/guru')],
            ['title' => 'Atur Relasi', 'url' => '#'],
        ];
        $data['guru'] = $this->guruModel->asObject()->findAll();
        return view('admin/guru/list-relasi', $data);
    }
    public function aturRelasi($id = '')
    {
        try {
            $id_guru = decrypt_url($id);
            $guru = $this->guruModel->asObject()->find($id_guru);

            if (!$guru) return redirect()->back()->with('error', 'Guru tidak ditemukan.');

            $data['breadcrumbs'] = [
                ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
                ['title' => 'Data Relasi', 'url' => base_url('sw-admin/relasi')],
                ['title' => 'Atur Relasi', 'url' => '#'],
            ];
            $data['kelas'] = $this->kelasModel->asObject()->findAll();
            $data['mapel'] = $this->mapelModel->asObject()->findAll();
            $data['guru']  = $guru;
            return view('admin/guru/relasi', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan.');
        }
    }

    public function guruKelas()
    {
        if (!$this->request->isAJAX() || session()->get('role') != 1) return exit('No direct script access allowed');

        $id_guru  = decrypt_url($this->request->getPost('id_guru'));
        $id_kelas = $this->request->getPost('id_kelas');

        $kelasData = $this->kelasModel->asObject()->find($id_kelas);
        if (!$kelasData) return $this->response->setJSON(['status' => false]);

        $where = ['guru' => $id_guru, 'kelas' => $id_kelas];
        $exists = $this->guruKelasModel->where($where)->first();

        if (!$exists) {
            $this->guruKelasModel->save(array_merge($where, ['nama_kelas' => $kelasData->nama_kelas]));
            $msg = "Akses Kelas Berhasil Diberikan";
        } else {
            // Cabut akses kelas
            $this->guruKelasModel->where($where)->delete();
            
            // [BARU] Hapus secara masal semua relasi mapel yang terikat dengan guru dan kelas ini
            $this->guruMapelModel->where(['guru' => $id_guru, 'kelas' => $id_kelas])->delete();
            
            $msg = "Akses Kelas & Mapel Berhasil Dicabut";
        }

        return $this->response->setJSON(['status' => true, 'message' => $msg, 'token' => csrf_hash()]);
    }

    public function guruMapel()
    {
        if (!$this->request->isAJAX() || session()->get('role') != 1) return exit('No direct script access allowed');

        $id_guru  = decrypt_url($this->request->getPost('id_guru'));
        $id_mapel = $this->request->getPost('id_mapel');
        
        // [BARU] Menangkap id_kelas yang dikirim dari AJAX View
        $id_kelas = $this->request->getPost('id_kelas');

        $mapelData = $this->mapelModel->asObject()->find($id_mapel);
        if (!$mapelData) return $this->response->setJSON(['status' => false]);

        // [BARU] Menambahkan 'kelas' ke dalam kondisi pengecekan database
        $where = ['guru' => $id_guru, 'mapel' => $id_mapel, 'kelas' => $id_kelas];
        $exists = $this->guruMapelModel->where($where)->first();

        if (!$exists) {
            $this->guruMapelModel->save(array_merge($where, ['nama_mapel' => $mapelData->nama_mapel]));
            $msg = "Akses Mapel Berhasil Diberikan";
        } else {
            $this->guruMapelModel->where($where)->delete();
            $msg = "Akses Mapel Berhasil Dicabut";
        }

        return $this->response->setJSON(['status' => true, 'message' => $msg, 'token' => csrf_hash()]);
    }
}

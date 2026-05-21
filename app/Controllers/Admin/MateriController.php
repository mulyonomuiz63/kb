<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class MateriController extends BaseController
{
    protected $transaksiModel;
    protected $mapelModel;

    public function __construct()
    {
        $this->transaksiModel = new \App\Models\TransaksiModel();
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
        $siswa = $this->transaksiModel
            ->select('detail_transaksi.idmapel')
            ->join('detail_transaksi', 'transaksi.idtransaksi = detail_transaksi.idtransaksi')
            ->where('transaksi.status', 'S')
            ->where('idsiswa', $userId)
            ->groupBy('detail_transaksi.idmapel')
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
}

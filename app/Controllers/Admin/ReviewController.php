<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;


class ReviewController extends BaseController
{
    protected $paketModel;
    protected $ujianMasterModel;
    protected $kelasModel;
    protected $mapelModel;
    protected $detailPaketModel;
    protected $reviewModel;

    public function __construct()
    {
        $this->paketModel = new \App\Models\PaketModel();
        $this->ujianMasterModel = new \App\Models\UjianMasterModel();
        $this->kelasModel = new \App\Models\KelasModel();
        $this->mapelModel = new \App\Models\MapelModel();
        $this->detailPaketModel = new \App\Models\DetailPaketModel();
        $this->reviewModel = new \App\Models\ReviewModel();
    }

    public function index()
    {

        try {
            $data['breadcrumbs'] = [
                ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
                ['title' => 'Data Review Paket', 'url' => '#'],
            ];
            // MASTER DATA
            $data['paket'] = $this->paketModel
                ->join('diskon b', 'b.iddiskon = paket.iddiskon', 'left')
                // Penulisan yang benar: null tanpa tanda kutip
                ->where([
                    'paket.deleted_at'  => null,
                    'paket.v_ujian'  => '1',
                    'paket.v_materi' => '0',
                    'paket.status'        => '1'
                ])
                ->orderBy('paket.sort_order', 'ASC')
                ->get()->getResultObject();

            return view('admin/review/list', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }
}

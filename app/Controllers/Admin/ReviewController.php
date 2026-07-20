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
            $data['paket'] = $this->db->table('review_ujian d')
                ->select('p.idpaket, p.nama_paket, p.slug, p.v_ujian, p.v_materi, p.status, p.sort_order, COUNT(d.rating) as jumlah_reviewer, ROUND(AVG(d.rating), 1) as rata_rating')
                ->join('ujian_master c', 'c.kode_ujian = d.kode_ujian')
                ->join('detail_paket b', 'b.id_ujian = c.id_ujian')
                ->join('paket p', 'p.idpaket = b.idpaket')
                ->where([
                    'p.deleted_at'  => null,
                    'p.v_ujian'     => '1',
                    'p.v_materi'    => '0',
                    'p.status'      => '1'
                ])
                ->orderBy('p.sort_order', 'ASC')
                ->groupBy('p.idpaket') // Mengelompokkan berdasarkan idpaket untuk menghindari duplikasi
                ->get()->getResultObject();

            return view('admin/review/list', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }
}

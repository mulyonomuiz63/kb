<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Libraries\Emailer;
use App\Models\WebinarSesiModel;
use App\Models\SiswaModel; // Sesuaikan dengan model siswa Anda
use App\Libraries\SeoHelper;
use App\Models\DetailTransaksiModel;
use App\Models\PaketModel;
use App\Models\TransaksiModel;

class WebinarController extends BaseController
{
    protected  $seo;
    protected  $sesiModel;
    protected  $siswaModel;
    protected  $paketModel;
    protected  $transaksiModel;
    protected  $detailTransaksiModel;
    protected  $emailer;

    public function __construct()
    {
        $this->seo = new SeoHelper();
        $this->sesiModel = new WebinarSesiModel();
        $this->siswaModel = new SiswaModel();
        $this->paketModel = new PaketModel();
        $this->transaksiModel = new TransaksiModel();
        $this->detailTransaksiModel = new DetailTransaksiModel();
        $this->emailer = new Emailer();
    }

    public function index()
    {
        // Pastikan user sudah login
        $id_siswa = session()->get('id');
        // Query untuk mengambil sesi webinar yang sudah dibeli dan lunas
        $dataWebinar = $this->transaksiModel
            ->select('webinar_sesi.*, paket.nama_paket, paket.file')
            ->join('detail_transaksi', 'transaksi.idtransaksi = detail_transaksi.idtransaksi')
            ->join('paket','detail_transaksi.idpaket=paket.idpaket')
            ->join('webinar_sesi', 'detail_transaksi.idsesi=webinar_sesi.id_sesi')
            ->where('transaksi.status', 'S')
            ->where('idsiswa', $id_siswa)
            ->groupBy('detail_transaksi.idsesi')
            ->get()
            ->getResult();

        // var_dump($data['webinar']);

        $data = [
            'webinar' => $dataWebinar
        ];
        $data['breadcrumbs'] = [
            ['title' => 'Webinar', 'url' => base_url('sw-siswa')],
            ['title' => 'List Webinar', 'url' => '#'],
        ];

        return view('siswa/webinar/list', $data);
    }

    
}

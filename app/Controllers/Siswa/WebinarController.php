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

        $db = \Config\Database::connect();

        // Query untuk mengambil sesi webinar yang sudah dibeli dan lunas
        $builder = $db->table('webinar_sesi ws');
        $builder->select('ws.*, p.nama_paket');
        $builder->join('detail_transaksi dt', 'dt.idsesi = ws.id_sesi');
        $builder->join('transaksi t', 't.idtransaksi = dt.idtransaksi');
        $builder->join('paket p', 'p.idpaket = ws.idpaket', 'left'); // Mengambil info paket terkait

        // Asumsi: id pengguna di tabel transaksi adalah 'id_siswa' dan status 'L' berarti Lunas
        $builder->where('t.idsiswa', $id_siswa);
        $builder->where('t.status', 'L');

        // Urutkan berdasarkan waktu mulai terdekat
        $builder->orderBy('ws.waktu_mulai', 'ASC');

        $webinar = $builder->get()->getResultObject();

        $data = [
            'title'   => 'Webinar Saya',
            'webinar' => $webinar
        ];

        return view('siswa/webinar/list', $data);
    }

    
}

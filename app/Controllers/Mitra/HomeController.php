<?php

namespace App\Controllers\Mitra;

use App\Controllers\BaseController;
use App\Models\MitraModel;
use App\Models\SiswaModel;
use App\Models\VoucherModel;
use App\Models\TransaksiModel;



class HomeController extends BaseController
{
    protected $mitraModel;
    protected $siswaModel;
    protected $voucherModel;
    protected $transaksiModel;
    

    public function __construct()
    {
        $this->mitraModel = new MitraModel();
        $this->siswaModel = new SiswaModel();

        $this->voucherModel = new VoucherModel();
        $this->transaksiModel = new TransaksiModel();
        

    }
    
     //voucher
    public function index()
    {
        // MASTER DATA
        $data['voucher'] = $this->voucherModel->join('mitra','voucher.idmitra=mitra.idmitra')->where('voucher.idmitra', session('id'))->groupBy('voucher.kode_voucher')->get()->getResultObject();
        $data['mitra'] = $this->mitraModel->where('idmitra', session('id'))->get()->getRowObject();

        return view('mitra/voucher/list', $data);
    }

    public function detailVoucher($id)
    {
        $kode_voucher = decrypt_url($id);
        $data['mitra'] = $this->mitraModel->where('mitra.idmitra', session('id'))->get()->getRowObject();
        $data['transaksi'] = $this->transaksiModel
            ->select('transaksi.*, b.nama_siswa, b.email, c.nama_paket')
            ->join('detail_transaksi d', 'd.idtransaksi=transaksi.idtransaksi')
            ->join('siswa b', 'b.id_siswa = transaksi.idsiswa')
            ->join('paket c', 'c.idpaket = d.idpaket')
            ->where('transaksi.kode_voucher', $kode_voucher)
            ->where('transaksi.status', 'S')
            ->groupBY('transaksi.idtransaksi')
            ->orderBy('transaksi.status', 'esc')
            ->get()->getResultObject();
        
        $data['voucher'] = $this->voucherModel->join('mitra','voucher.idmitra=mitra.idmitra')->where('voucher.kode_voucher', $kode_voucher)->groupBy('voucher.kode_voucher')->get()->getRowObject();


        return view('mitra/voucher/detail', $data);
    }
}

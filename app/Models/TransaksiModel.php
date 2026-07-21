<?php

namespace App\Models;

use CodeIgniter\Model;


class TransaksiModel extends Model
{
    protected $table            = 'transaksi';
    protected $primaryKey       = 'idtransaksi';
    protected $allowedFields    = ['idsiswa', 'va', 'nominal', 'diskon', 'voucher', 'kode_unik', 'status', 'tgl_exp', 'tgl_drop', 'tgl_pembayaran', 'bukti_pembayaran', 'keterangan', 'token', 'jenis_bayar', 'kode_voucher', 'referensi_bank', 'jenis_paket', 'kode_affiliate'];

    public function getAll()
    {
        return $this
            ->select('transaksi.*, b.nama_siswa, b.email, c.nama_paket, c.v_ujian, c.v_materi, c.jenis_paket')
            ->join('detail_transaksi d', 'd.idtransaksi=transaksi.idtransaksi')
            ->join('siswa b', 'b.id_siswa = transaksi.idsiswa')
            ->join('paket c', 'c.idpaket = d.idpaket')
            ->whereIn('transaksi.status', ['V', 'S'])
            ->groupBY('transaksi.idtransaksi')
            ->orderBy('transaksi.status', 'esc')
            ->get()->getResultObject();
    }
    public function getBaseQuery()
    {
        // UPGRADE: Menambahkan b.idafiliasi pada select untuk mempermudah eksekusi filter di controller
        return $this->select('transaksi.*, b.nama_siswa, b.email, b.id_siswa, b.kantor, b.idafiliasi, c.nama_paket, a.nama_afiliasi')
            ->join('detail_transaksi d', 'd.idtransaksi=transaksi.idtransaksi')
            ->join('siswa b', 'b.id_siswa = transaksi.idsiswa')
            ->join('paket c', 'c.idpaket = d.idpaket')
            ->join('afiliasi a', 'a.idafiliasi = b.idafiliasi', 'left')
            ->groupBy('transaksi.idtransaksi');
    }

    public function countAllData()
    {
        return $this->db->table('transaksi')->countAllResults();
    }

    public function getAllKodeVoucher()
    {
        return $this
            ->select('kode_voucher, tgl_pembayaran')
            ->groupBY('transaksi.kode_voucher')
            ->orderBy('transaksi.tgl_pembayaran', 'desc')
            ->get()->getResultObject();
    }
    //di panggil di pic
    public function getAllStatusPeserta()
    {
        return $this
            ->select('transaksi.*, b.nama_siswa, b.email, b.hp, b.id_siswa, c.nama_paket')
            ->join('detail_transaksi d', 'd.idtransaksi=transaksi.idtransaksi')
            ->join('siswa b', 'b.id_siswa = transaksi.idsiswa')
            ->join('paket c', 'c.idpaket = d.idpaket')
            ->where('transaksi.status', 'S')
            ->groupBY('transaksi.idtransaksi')
            ->orderBy('transaksi.status', 'asc')
            ->orderBy('transaksi.tgl_pembayaran', 'desc')
            ->get()->getResultObject();
    }
    public function getById($id)
    {
        return $this
            ->select('transaksi.*, b.nama_siswa, b.email, c.nama_paket, c.jumlah_bulan')
            ->join('detail_transaksi d', 'd.idtransaksi=transaksi.idtransaksi')
            ->join('siswa b', 'b.id_siswa = transaksi.idsiswa')
            ->join('paket c', 'c.idpaket = d.idpaket')
            ->where('transaksi.idtransaksi', $id)
            ->groupBY('transaksi.idtransaksi')
            ->get()->getRowObject();
    }

    public function getByIdSiswa($id)
    {
        return $this
            ->select('transaksi.*, c.nama_paket, c.tagline, c.jumlah_bulan, c.nominal_paket')
            ->join('detail_transaksi d', 'd.idtransaksi=transaksi.idtransaksi')
            ->join('siswa b', 'b.id_siswa = transaksi.idsiswa')
            ->join('paket c', 'c.idpaket = d.idpaket')
            ->where('transaksi.idsiswa', $id)
            ->whereIn('transaksi.status', ['P', 'V'])
            ->groupBY('transaksi.idtransaksi')
            ->get()->getRowObject();
    }
    public function getByIdDropSiswa($id)
    {
        return $this
            ->select('transaksi.*, c.nama_paket, c.tagline, c.jumlah_bulan, c.nominal_paket')
            ->join('detail_transaksi d', 'd.idtransaksi=transaksi.idtransaksi')
            ->join('siswa b', 'b.id_siswa = transaksi.idsiswa')
            ->join('paket c', 'c.idpaket = d.idpaket')
            ->where('transaksi.idsiswa', $id)
            ->whereIn('transaksi.status', ['D'])
            ->groupBY('transaksi.idtransaksi')
            ->get()->getRowObject();
    }


    public function getByIdSiswaAll($id)
    {
        return $this
            ->select('transaksi.*, b.nama_siswa, b.email, c.nama_paket, c.tagline, c.jumlah_bulan')
            ->join('detail_transaksi d', 'd.idtransaksi=transaksi.idtransaksi')
            ->join('siswa b', 'b.id_siswa = transaksi.idsiswa')
            ->join('paket c', 'c.idpaket = d.idpaket')
            ->where('transaksi.idsiswa', $id)
            ->groupBY('transaksi.idtransaksi')
            ->orderBy('transaksi.idtransaksi', 'desc')
            ->get()->getResultObject();
    }
}

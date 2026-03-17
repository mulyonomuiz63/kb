<?php

namespace App\Models;

use CodeIgniter\Model;

class UjianDetailModel extends Model
{
    protected $table            = 'ujian_detail';
    protected $primaryKey       = 'id_detail_ujian';
    protected $allowedFields    = ['kode_ujian', 'nama_soal', 'pg_1', 'pg_2', 'pg_3', 'pg_4', 'pg_5', 'jawaban', 'penjelasan'];

    public function getAllBykodeUjian($kode_ujian)
    {
        return $this
            ->where('kode_ujian', $kode_ujian)->orderBy('id_detail_ujian', 'RANDOM')
            ->get()->getResultObject();
    }
    
    public function getAllByKodeUjianJumlah($kode_ujian)
    {
        return $this
            ->where('kode_ujian', $kode_ujian)->get()->getResultObject();
    }

    public function getBySoalKodeUjian($nama_soal, $kode_ujian)
    {
        return $this
            ->where('nama_soal', $nama_soal)
            ->where('kode_ujian', $kode_ujian)
            ->get()->getRowObject();
    }


    public function getAllByiddetailujian($id_detail_ujian)
    {
        return $this
            ->where('id_detail_ujian', $id_detail_ujian)
            ->get()->getRowObject();
    }
}

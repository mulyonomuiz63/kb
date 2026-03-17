<?php

namespace App\Models;

use CodeIgniter\Model;

class UjianSiswaModel extends Model
{
    protected $table            = 'ujian_siswa';
    protected $primaryKey       = 'id_ujian_siswa';
    protected $allowedFields    = ['ujian_id', 'ujian', 'siswa', 'jawaban', 'benar', 'jam', 'status', 'date_send'];

    public function getAll($ujian, $siswa)
    {
        return $this
            ->where('ujian', $ujian)
            ->where('siswa', $siswa)
            ->get()->getRowObject();
    }

    public function getByKodeUjian($kode_ujian)
    {
        return $this
            ->where('ujian', $kode_ujian)
            ->where('jawaban', null)
            ->get()->getResultObject();
    }

    public function getByUjianSiswa($id_detail_ujian, $id_siswa)
    {
        return $this
            ->where('ujian_id', $id_detail_ujian)
            ->where('siswa', $id_siswa)
            ->where('jawaban', null)
            ->get()->getRowObject();
    }

    public function date_kirim_ujian($ujian, $siswa)
    {
        return $this
            ->where('ujian', $ujian)
            ->where('siswa', $siswa)
            ->where('status', 'selesai')
            ->limit('1')
            ->orderBy('date_send', 'asc')
            ->get()->getRowObject();
    }
    public function belum_terjawab($ujian, $siswa, $jawaban = null)
    {
        return $this
            ->where('ujian', $ujian)
            ->where('siswa', $siswa)
            ->where('benar', $jawaban)
            ->get()->getResultObject();
    }

    public function benar($ujian, $siswa, $benar)
    {
        return $this
            ->where('ujian', $ujian)
            ->where('siswa', $siswa)
            ->where('benar', $benar)
            ->get()->getResultObject();
    }

    public function salah($ujian, $siswa, $salah)
    {
        return $this
            ->where('ujian', $ujian)
            ->where('siswa', $siswa)
            ->where('benar', $salah)
            ->get()->getResultObject();
    }
}

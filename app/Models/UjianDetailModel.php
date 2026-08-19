<?php

namespace App\Models;

use CodeIgniter\Model;

class UjianDetailModel extends Model
{
    protected $table            = 'ujian_detail';
    protected $primaryKey       = 'id_detail_ujian';
    protected $allowedFields    = ['kode_ujian', 'nama_soal', 'pg_1', 'pg_2', 'pg_3', 'pg_4', 'pg_5', 'jawaban', 'penjelasan', 'jenis_soal', 'deleted_at'];

    // --- SETTING SOFT DELETE BAWAAN CODEIGNITER 4 ---
    // protected $useSoftDeletes = true;
    // protected $deletedField   = 'deleted_at';

    public function getAllBykodeUjianAdmin($kode_ujian)
    {
        return $this
            ->where('kode_ujian', $kode_ujian)->orderBy('id_detail_ujian', 'ASC')
            ->get()->getResultObject();
    }
    public function getAllBykodeUjian($kode_ujian)
    {
        return $this
            ->where('kode_ujian', $kode_ujian)->orderBy('id_detail_ujian', 'RANDOM')
            ->where('deleted_at', null)
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


    public function getSoalAcakBerdasarkanLevel($kode_ujian, $jml_mudah, $jml_sedang, $jml_susah)
    {
        // Fungsi bantuan untuk mengambil ID acak agar server database tidak jebol
        $getAcakIds = function ($jenis_soal, $limit) use ($kode_ujian) {
            if ($limit <= 0) return [];

            // Hanya ambil ID-nya saja (sangat ringan bagi database)
            $ids = $this->select('id_detail_ujian')
                ->where('kode_ujian', $kode_ujian)
                ->where('jenis_soal', $jenis_soal)
                ->where('deleted_at', null)
                ->findAll(); // Bisa juga pakai get()->getResultArray() jika array

            if (empty($ids)) return [];

            // Ekstrak hanya menjadi array angka, lalu acak menggunakan PHP
            $id_array = array_column($ids, 'id_detail_ujian');
            shuffle($id_array);

            // Potong array sesuai dengan jumlah yang dibutuhkan (limit)
            return array_slice($id_array, 0, $limit);
        };

        // 1. Ambil Kumpulan ID Acak
        $id_mudah  = $getAcakIds('E', $jml_mudah);
        $id_sedang = $getAcakIds('M', $jml_sedang);
        $id_susah  = $getAcakIds('H', $jml_susah);

        // Gabungkan semua ID yang terpilih
        $semua_id_terpilih = array_merge($id_mudah, $id_sedang, $id_susah);

        // Jika tidak ada soal sama sekali, kembalikan array kosong
        if (empty($semua_id_terpilih)) {
            return [];
        }

        // 2. Query data soal secara penuh berdasarkan ID yang terpilih saja
        $semua_soal = $this->whereIn('id_detail_ujian', $semua_id_terpilih)
            ->get()->getResultObject();

        // 3. Acak ulang agar posisi E, M, dan H tidak terurut
        shuffle($semua_soal);

        return $semua_soal;
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class UjianDetailModel extends Model
{
    protected $table            = 'ujian_detail';
    protected $primaryKey       = 'id_detail_ujian';
    protected $allowedFields    = ['kode_ujian', 'nama_soal', 'pg_1', 'pg_2', 'pg_3', 'pg_4', 'pg_5', 'jawaban', 'penjelasan', 'jenis_soal', 'sub_materi', 'deleted_at'];

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


    public function getSoalAcakBerdasarkanLevel($kode_ujian)
    {
        $db = \Config\Database::connect();

        // Ambil komposisi sub-materi berdasarkan ujian ini
        $komposisi = $db->table('ujian_komposisi')
            ->where('kode_ujian', $kode_ujian)
            ->get()
            ->getResultObject();

        $semua_id_terpilih = [];

        // Fungsi bantuan untuk mengambil ID acak per sub-materi & level
        // Menambahkan $excluded_ids agar soal tidak terambil ganda
        $getAcakIds = function ($jenis_soal, $limit, $sub_materi = null, $excluded_ids = []) use ($kode_ujian) {
            if ($limit <= 0) return [];

            $builder = $this->select('id_detail_ujian')
                ->where('kode_ujian', $kode_ujian)
                ->where('jenis_soal', $jenis_soal)
                ->where('deleted_at', null);

            // Filter sub-materi
            if (!empty($sub_materi)) {
                $builder->where('sub_materi', $sub_materi);
            }

            // PENTING: Jangan ambil soal yang sudah terpilih di baris sebelumnya
            if (!empty($excluded_ids)) {
                $builder->whereNotIn('id_detail_ujian', $excluded_ids);
            }

            $ids = $builder->findAll();

            if (empty($ids)) return [];

            $id_array = array_column($ids, 'id_detail_ujian');
            shuffle($id_array);

            return array_slice($id_array, 0, $limit);
        };

        // Eksekusi pengambilan ID berdasarkan tabel ujian_komposisi
        if (!empty($komposisi)) {
            foreach ($komposisi as $comp) {
                // Ambil Mudah, Sedang, Susah dengan mengecualikan ID yang sudah terpilih
                $id_m_mudah  = $getAcakIds('E', (int) $comp->jml_mudah, $comp->nama_sub_materi, $semua_id_terpilih);
                $semua_id_terpilih = array_merge($semua_id_terpilih, $id_m_mudah);

                $id_m_sedang = $getAcakIds('M', (int) $comp->jml_sedang, $comp->nama_sub_materi, $semua_id_terpilih);
                $semua_id_terpilih = array_merge($semua_id_terpilih, $id_m_sedang);

                $id_m_susah  = $getAcakIds('H', (int) $comp->jml_susah, $comp->nama_sub_materi, $semua_id_terpilih);
                $semua_id_terpilih = array_merge($semua_id_terpilih, $id_m_susah);
            }

            if (empty($semua_id_terpilih)) {
                return [];
            }

            $semua_soal = $this->whereIn('id_detail_ujian', $semua_id_terpilih)
                ->get()->getResultObject();
        } else {
            // Fallback: Jika ujian_komposisi kosong (Ujian Brevet Biasa)
            $semua_soal = $this->where('kode_ujian', $kode_ujian)
                ->where('deleted_at', null)
                ->get()->getResultObject();

            if (empty($semua_soal)) {
                return [];
            }
        }

        // Acak ulang susunan akhir soal agar urutan E, M, H tercampur
        shuffle($semua_soal);

        return $semua_soal;
    }
}

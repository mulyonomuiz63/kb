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

        // 1. Ambil komposisi sub-materi berdasarkan ujian ini
        $komposisi = $db->table('ujian_komposisi')
            ->where('kode_ujian', $kode_ujian)
            ->get()
            ->getResultObject();

        $semua_id_terpilih = [];

        // 2. Fungsi bantuan independen untuk mengambil ID acak per sub-materi & level
        $getAcakIds = function ($jenis_soal, $limit, $sub_materi = null, &$excluded_ids = []) use ($db, $kode_ujian) {
            if ($limit <= 0) return [];

            // Gunakan $db->table() langsung agar query builder selalu fresh/bersih
            $builder = $db->table($this->table)
                ->select('id_detail_ujian')
                ->where('kode_ujian', $kode_ujian)
                ->where('jenis_soal', $jenis_soal)
                ->where('deleted_at', null);

            // Filter sub-materi jika diisi (gunakan trim untuk mengantisipasi spasi tersembunyi)
            if (!empty($sub_materi)) {
                $builder->where('TRIM(sub_materi)', trim($sub_materi));
            }

            // Jangan ambil soal yang sudah terpilih sebelumnya
            if (!empty($excluded_ids)) {
                $builder->whereNotIn('id_detail_ujian', $excluded_ids);
            }

            $ids = $builder->get()->getResultArray();

            // Jika soal spesifik sub-materi kurang dari limit, lakukan Fallback/Auto Fill-Up
            // Ambil sisa kekurangan dari soal level sama di sub-materi lain
            if (count($ids) < $limit && !empty($sub_materi)) {
                $sisa_butuh = $limit - count($ids);
                $id_terambil_sekarang = array_column($ids, 'id_detail_ujian');
                $excl = array_merge($excluded_ids, $id_terambil_sekarang);

                $builderFallback = $db->table($this->table)
                    ->select('id_detail_ujian')
                    ->where('kode_ujian', $kode_ujian)
                    ->where('jenis_soal', $jenis_soal)
                    ->where('deleted_at', null);

                if (!empty($excl)) {
                    $builderFallback->whereNotIn('id_detail_ujian', $excl);
                }

                $idsFallback = $builderFallback->get()->getResultArray();
                $ids = array_merge($ids, $idsFallback);
            }

            if (empty($ids)) return [];

            $id_array = array_column($ids, 'id_detail_ujian');
            shuffle($id_array);

            // Ambil sejumlah limit yang diminta
            $result_ids = array_slice($id_array, 0, $limit);

            // Tambahkan ID yang baru terpilih ke array penampung utama
            $excluded_ids = array_merge($excluded_ids, $result_ids);

            return $result_ids;
        };

        // 3. Hitung total target soal dari komposisi
        $total_komposisi_soal = 0;
        if (!empty($komposisi)) {
            foreach ($komposisi as $comp) {
                $total_komposisi_soal += ((int)$comp->jml_mudah + (int)$comp->jml_sedang + (int)$comp->jml_susah);
            }
        }

        // 4. Eksekusi pengambilan ID berdasarkan tabel ujian_komposisi
        if (!empty($komposisi) && $total_komposisi_soal > 0) {
            foreach ($komposisi as $comp) {
                $subMateriNama = !empty($comp->nama_sub_materi) ? $comp->nama_sub_materi : null;

                // Ambil Mudah, Sedang, Susah
                $getAcakIds('E', (int) $comp->jml_mudah, $subMateriNama, $semua_id_terpilih);
                $getAcakIds('M', (int) $comp->jml_sedang, $subMateriNama, $semua_id_terpilih);
                $getAcakIds('H', (int) $comp->jml_susah, $subMateriNama, $semua_id_terpilih);
            }

            if (empty($semua_id_terpilih)) {
                // Fallback: Jika tidak ada soal yang cocok sama sekali
                $semua_soal = $this->where('kode_ujian', $kode_ujian)
                    ->where('deleted_at', null)
                    ->limit(30)
                    ->get()->getResultObject();
            } else {
                $semua_soal = $this->whereIn('id_detail_ujian', $semua_id_terpilih)
                    ->get()->getResultObject();
            }
        } else {
            // Fallback: Ujian tanpa komposisi
            $semua_soal = $this->where('kode_ujian', $kode_ujian)
                ->where('deleted_at', null)
                ->limit(30)
                ->get()->getResultObject();

            if (empty($semua_soal)) {
                return [];
            }
        }

        // 5. Acak ulang susunan akhir soal agar urutan level bercampur
        if (!empty($semua_soal)) {
            shuffle($semua_soal);
        }

        return $semua_soal;
    }
}

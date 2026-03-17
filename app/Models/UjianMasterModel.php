<?php

namespace App\Models;

use CodeIgniter\Model;

class UjianMasterModel extends Model
{
    protected $table            = 'ujian_master';
    protected $primaryKey       = 'id_ujian';
    protected $allowedFields    = ['kode_ujian', 'nama_ujian', 'guru', 'kelas', 'mapel', 'date_created', 'waktu_mulai', 'waktu_berakhir', 'jenis_ujian', 'status'];

    public function getAll()
    {
        return $this
            ->get()->getResultObject();
    }

    public function getAllBykodeGuru($guru)
    {
        return $this
            ->select('ujian_master.*, kelas.nama_kelas, mapel.nama_mapel') // Tambahkan select agar aman
            ->join('kelas', 'kelas.id_kelas=ujian_master.kelas')
            ->join('mapel', 'mapel.id_mapel=ujian_master.mapel')
            ->join('guru', 'guru.id_guru=ujian_master.guru')
            ->where('ujian_master.guru', $guru);
    }

    public function getAllBykelas($kelas, $id_siswa)
    {
        if (session()->get('status') == 'Subscribe') {
            return $this
                ->join('kelas', 'kelas.id_kelas=ujian_master.kelas')
                ->join('mapel', 'mapel.id_mapel=ujian_master.mapel')
                ->join('guru', 'guru.id_guru=ujian_master.guru')
                ->join('ujian_siswa', 'ujian_siswa.ujian=ujian_master.kode_ujian')
                ->where('ujian_master.kelas', $kelas)
                ->where('ujian_siswa.siswa', $id_siswa)

                ->groupBy('ujian_master.id_ujian')
                ->get()->getResultObject();
        } else {
            return $this
                ->join('kelas', 'kelas.id_kelas=ujian_master.kelas')
                ->join('mapel', 'mapel.id_mapel=ujian_master.mapel')
                ->join('guru', 'guru.id_guru=ujian_master.guru')
                ->join('ujian_siswa', 'ujian_siswa.ujian_id=ujian_master.id_ujian')
                ->where('ujian_master.kelas', $kelas)
                ->where('ujian_siswa.siswa', $id_siswa)
                ->groupBy('ujian_master.id_ujian')
                ->get()->getResultObject();
        }
    }

    public function getAllBykelas2($kelas, $id_siswa)
    {

        return $this
            ->select('ujian_master.kode_ujian, ujian_master.nama_ujian, ujian_master.waktu_mulai, kelas.nama_kelas, ujian_siswa.siswa, ujian_siswa.ujian, COUNT(ujian_siswa.benar) AS benar')
            ->join('ujian_siswa', 'ujian_master.kode_ujian=ujian_siswa.ujian')
            ->join('kelas', 'ujian_master.kelas=kelas.id_kelas')
            ->where('ujian_siswa.siswa', $id_siswa)
            ->where('kelas.id_kelas', $kelas)
            ->where('ujian_siswa.benar', 1)
            ->groupBy('ujian_siswa.ujian')
            ->orderBy('ujian_master.id_ujian', 'desc')
            ->get()
            ->getResultObject();
    }

    public function getAllUntukNilaiUjian($kelas, $id_siswa, $kode_ujian)
    {

        return $this
            ->select('ujian_master.kode_ujian, ujian_master.nama_ujian, ujian_master.waktu_mulai, kelas.nama_kelas, ujian_siswa.siswa, ujian_siswa.ujian, COUNT(ujian_siswa.benar) AS benar')
            ->join('ujian_siswa', 'ujian_master.kode_ujian=ujian_siswa.ujian')
            ->join('kelas', 'ujian_master.kelas=kelas.id_kelas')
            ->where('ujian_siswa.siswa', $id_siswa)
            ->where('kelas.id_kelas', $kelas)
            ->where('ujian_siswa.benar', 1)
            ->where('ujian_master.kode_ujian', $kode_ujian)
            ->groupBy('ujian_siswa.ujian')
            ->orderBy('ujian_master.id_ujian', 'desc')
            ->get()
            ->getResultObject();
    }

    public function getHasilUjian($kode_ujian, $id_siswa)
    {
        return $this
            ->select('siswa.nama_siswa, ujian_master.kode_ujian, ujian_master.nama_ujian, ujian_master.waktu_mulai, kelas.nama_kelas, ujian_siswa.ujian, COUNT(ujian_siswa.benar) AS benar')
            ->join('ujian_siswa', 'ujian_master.kode_ujian=ujian_siswa.ujian')
            ->join('kelas', 'ujian_master.kelas=kelas.id_kelas')
            ->join('siswa', 'ujian_siswa.siswa=siswa.id_siswa')
            ->where('ujian_master.kode_ujian', $kode_ujian)
            ->where('ujian_siswa.siswa', $id_siswa)
            ->where('ujian_siswa.benar', 1)
            ->groupBy('ujian_siswa.ujian')
            ->get()
            ->getRowObject();
    }

    public function getBykode($kode_ujian)
    {
        return $this
            ->join('kelas', 'kelas.id_kelas=ujian_master.kelas')
            ->join('mapel', 'mapel.id_mapel=ujian_master.mapel')
            ->join('guru', 'guru.id_guru=ujian_master.guru')
            ->where('ujian_master.kode_ujian', $kode_ujian)
            ->get()->getRowObject();
    }


    // Fungsi untuk Datatables: Mengambil data dengan limit dan search
    public function get_datatables($guru, $request)
    {
        $builder = $this->_get_datatables_query($guru, $request);

        // Konversi string ke integer menggunakan (int)
        $limit = $request->getPost('length');
        $start = $request->getPost('start');

        // Pastikan nilai dikonversi ke integer agar tidak TypeError
        if ($limit !== null && $limit != -1) {
            $builder->limit((int)$limit, (int)$start);
        }

        return $builder->get()->getResult();
    }

    // Fungsi untuk Datatables: Menghitung total data setelah difilter search
    public function count_filtered($guru, $request)
    {
        $builder = $this->_get_datatables_query($guru, $request);
        return $builder->countAllResults();
    }

    // Fungsi untuk Datatables: Menghitung total asli tanpa filter
    public function count_all($guru)
    {
        return $this->where('guru', $guru)->countAllResults();
    }

    // Helper untuk filter pencarian
    private function _get_datatables_query($guru, $request)
    {
        // Ambil base query dari fungsi asli Anda
        $builder = $this->getAllBykodeGuru($guru);

        $search = $request->getPost('search')['value'];
        if ($search) {
            $builder->groupStart()
                ->like('ujian_master.nama_ujian', $search)
                ->orLike('kelas.nama_kelas', $search)
                ->orLike('mapel.nama_mapel', $search)
                ->groupEnd();
        }

        // Default sorting
        $builder->orderBy('ujian_master.id_ujian', 'DESC');

        return $builder;
    }
}

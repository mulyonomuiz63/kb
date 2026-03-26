<?php

namespace App\Models;

use CodeIgniter\Model;

class UjianModel extends Model
{
    protected $table            = 'ujian';
    protected $primaryKey       = 'id_ujian';
    protected $allowedFields    = ['id_siswa','kode_ujian', 'nama_ujian', 'guru', 'kelas', 'mapel', 'date_created', 'waktu_mulai', 'waktu_berakhir','start_ujian', 'end_ujian', 'jenis_ujian','status','nilai','kuota','verifikasi'];

    public function getAll()
    {
        return $this
            ->get()->getResultObject();
    }

    public function getAllBykodeGuru($guru)
    {
        return $this
            ->join('kelas', 'kelas.id_kelas=ujian.kelas')
            ->join('mapel', 'mapel.id_mapel=ujian.mapel')
            ->join('guru', 'guru.id_guru=ujian.guru')
            ->where('ujian.guru', $guru)
            ->get()->getResultObject();
    }

    public function getAllBykelas($kelas, $id_siswa)
    {
  
        return $this
            ->join('kelas', 'kelas.id_kelas=ujian.kelas')
            ->join('mapel', 'mapel.id_mapel=ujian.mapel')
            ->join('guru', 'guru.id_guru=ujian.guru')
            ->where('ujian.kelas', $kelas)
            ->where('ujian.id_siswa', $id_siswa)
            ->groupBy('ujian.id_ujian')
            ->get()->getResultObject();
    }

    public function getAllByKelasSertifikat($kelas, $id_siswa)
    {

        return $this
            ->join('mapel', 'mapel.id_mapel=ujian.mapel')
            ->join('guru', 'guru.id_guru=ujian.guru')
            ->where('ujian.kelas', $kelas)
            ->where('ujian.id_siswa', $id_siswa)
            ->where('ujian.nilai >=', 60)
            ->orderBy('ujian.nilai', 'desc')
            ->groupBy('ujian.kode_ujian')
            ->groupBy('ujian.nilai')
            ->get()->getResultObject();
    }

    public function getAllBykelas2($kelas, $id_siswa)
    {
        return $this
            ->select('ujian.kode_ujian, ujian.nama_ujian, ujian.waktu_mulai, kelas.nama_kelas, ujian_siswa.siswa, ujian_siswa.ujian, COUNT(ujian_siswa.benar) AS benar')
            ->join('ujian_siswa', 'ujian.kode_ujian=ujian_siswa.ujian')
            ->join('kelas', 'ujian.kelas=kelas.id_kelas')
            ->where('ujian_siswa.siswa', $id_siswa)
            ->where('ujian_siswa.benar', 1)
            ->groupBy('ujian_siswa.ujian')
            ->orderBy('ujian.waktu_mulai', 'desc')
            ->get()
            ->getResultObject();
    }

    public function getHasilUjian($kode_ujian, $id_siswa)
    {
        return $this
            ->select('siswa.nama_siswa, ujian.kode_ujian, ujian.nama_ujian, ujian.waktu_mulai, kelas.nama_kelas, ujian_siswa.ujian, COUNT(ujian_siswa.benar) AS benar')
            ->join('ujian_siswa', 'ujian.kode_ujian=ujian_siswa.ujian')
            ->join('kelas', 'ujian.kelas=kelas.id_kelas')
            ->join('siswa', 'ujian_siswa.siswa=siswa.id_siswa')
            ->where('ujian.kode_ujian', $kode_ujian)
            ->where('ujian_siswa.siswa', $id_siswa)
            ->where('ujian_siswa.benar', 1)
            ->groupBy('ujian_siswa.ujian')
            ->get()
            ->getRowObject();
    }

    public function getBykode($kode_ujian, $id_ujian)
    {
        return $this
            ->join('kelas', 'kelas.id_kelas=ujian.kelas')
            ->join('mapel', 'mapel.id_mapel=ujian.mapel')
            ->join('guru', 'guru.id_guru=ujian.guru')
            ->join('siswa', 'siswa.id_siswa=ujian.id_siswa')
            ->where('ujian.kode_ujian', $kode_ujian)
            ->where('ujian.id_ujian', $id_ujian)
            ->get()->getRowObject();
    }
    
    public function getByIdsiswa($id_siswa)
    {
        return $this
        ->select("siswa.*, mapel.*, ujian.id_ujian, ujian.id_siswa, ujian.kode_ujian, max(ujian.nilai) as nilai, ujian.end_ujian")
            ->join('siswa', 'siswa.id_siswa=ujian.id_siswa')
            ->join('mapel', 'mapel.id_mapel=ujian.mapel')
            ->where('ujian.id_siswa', $id_siswa)
            ->where('ujian.status', 'S')
            ->where('ujian.nilai >=', 60)
            ->groupBy('ujian.kode_ujian')
            ->orderBy('max(ujian.nilai)', 'desc')
            ->get()->getResultObject();
    }
    
    public function getByIdsiswaAsc($id_siswa)
    {
        return $this
            ->join('siswa', 'siswa.id_siswa=ujian.id_siswa')
            ->join('mapel', 'mapel.id_mapel=ujian.mapel')
            ->where('ujian.id_siswa', $id_siswa)
            ->where('ujian.status', 'S')
            ->where('ujian.nilai >=', 60)
            ->orderBy('ujian.start_ujian', 'asc')
            ->get()->getRowObject();
    }
    
     public function getByIdsiswaDesc($id_siswa)
    {
        return $this
            ->join('siswa', 'siswa.id_siswa=ujian.id_siswa')
            ->join('mapel', 'mapel.id_mapel=ujian.mapel')
            ->where('ujian.id_siswa', $id_siswa)
            ->where('ujian.status', 'S')
            ->where('ujian.nilai >=', 60)
            ->orderBy('ujian.end_ujian', 'desc')
            ->get()->getRowObject();
    }
    
    public function getByIdsiswaSertifikat($id_siswa)
    {
        return $this
            ->select('max(ujian.nilai) as nilai_ujian, ujian.*, siswa.nama_siswa, mapel.nama_mapel')
            ->join('siswa', 'siswa.id_siswa=ujian.id_siswa')
            ->join('mapel', 'mapel.id_mapel=ujian.mapel')
            ->where('ujian.id_siswa', $id_siswa)
            ->where('ujian.status', 'S')
            ->where('ujian.nilai >=', 60)
            ->groupBy('ujian.kode_ujian')
            ->orderBy('ujian.mapel', 'asc')
            ->get()->getResultObject();
    }
    
    
}

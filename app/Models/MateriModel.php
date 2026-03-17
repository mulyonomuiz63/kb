<?php

namespace App\Models;

use CodeIgniter\Model;

class MateriModel extends Model
{
    protected $table            = 'materi';
    protected $primaryKey       = 'id_materi';
    protected $allowedFields    = ['kode_materi', 'nama_materi', 'guru', 'mapel', 'kelas', 'text_materi', 'status', 'date_created'];

    public function getAllbyMapelKelas($mapel, $kelas)
    {
        return $this
            ->join('kelas', 'kelas.id_kelas=materi.kelas')
            ->join('mapel', 'mapel.id_mapel=materi.mapel')
            ->where('mapel.id_mapel', $mapel)
            ->where('materi.kelas', $kelas)
            ->orderBy('materi.id_materi', 'asc')
            ->get()->getResultObject();
    }

    public function getAllbyKelas($kelas)
    {
        return $this
            ->join('kelas', 'kelas.id_kelas=materi.kelas')
            ->join('mapel', 'mapel.id_mapel=materi.mapel')
            ->where('materi.kelas', $kelas)
            ->get()->getResultObject();
    }

    public function getBykodeMateri($kode_materi)
    {
        return $this
            ->join('kelas', 'kelas.id_kelas=materi.kelas')
            ->join('mapel', 'mapel.id_mapel=materi.mapel')
            ->join('guru', 'guru.id_guru=materi.guru')
            ->where('materi.kode_materi', $kode_materi)
            ->get()->getRowObject();
    }

    public function getById($id_materi)
    {
        return $this
            ->join('kelas', 'kelas.id_kelas=materi.kelas')
            ->join('mapel', 'mapel.id_mapel=materi.mapel')
            ->where('materi.id_materi', $id_materi)
            ->get()->getRowObject();
    }

    public function getAllByGuru($guru, $id)
    {
        
        return $this
            ->join('kelas', 'kelas.id_kelas=materi.kelas')
            ->join('mapel', 'mapel.id_mapel=materi.mapel')
            ->where('materi.guru', $guru)
            ->where('materi.mapel', $id)
            ->orderBy('id_materi', 'asc')
            ->get()->getResultObject();
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class FileModel extends Model
{
    protected $table            = 'file';
    protected $primaryKey       = 'id_file';
    protected $allowedFields    = ['kode_file', 'nama_file'];

    public function getAllByKode($kode)
    {
        return $this
            ->where('kode_file', $kode)
            ->get()->getResultObject();
    }
    
    public function getMateriWithFile($mapel, $kelas)
    {
        return $this
            ->select('file.nama_file, file.id_file, materi.nama_materi')
            ->join('materi', 'materi.kode_materi = file.kode_file', 'left')
            ->join('kelas', 'kelas.id_kelas = materi.kelas')
            ->join('mapel', 'mapel.id_mapel = materi.mapel')
            ->where('materi.mapel', $mapel)
            ->where('materi.kelas', $kelas)
            ->orderBy('materi.id_materi', 'ASC')
            ->get()
            ->getResultObject();
    }
}

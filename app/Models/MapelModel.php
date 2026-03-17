<?php



namespace App\Models;



use CodeIgniter\Model;



class MapelModel extends Model

{

    protected $table            = 'mapel';

    protected $primaryKey       = 'id_mapel';

    protected $allowedFields    = ['nama_mapel', 'file'];



    public function getAll($kelas)
    {

        return $this

            ->select('mapel.id_mapel, mapel.file, mapel.nama_mapel, COUNT(*) AS jlh_materi, kelas.id_kelas, kelas.nama_kelas, materi.status, guru.nama_guru, guru.avatar')

            ->join('materi', 'materi.mapel=mapel.id_mapel')

            ->join('kelas', 'materi.kelas=kelas.id_kelas')
            
            ->join('guru_kelas', 'kelas.id_kelas=guru_kelas.kelas')
            
            ->join('guru', 'guru_kelas.guru = guru.id_guru')

            ->where('materi.kelas', $kelas)

            ->orderBy('kelas.id_kelas')

            ->groupBy('mapel.id_mapel, mapel.nama_mapel')

            ->get()->getResultObject();
    }
    
    public function getAllIdSiswa($id)
    {

        return $this

            ->select('mapel.id_mapel, mapel.file, mapel.nama_mapel, kelas.id_kelas, kelas.nama_kelas, materi.status, materi.kode_materi, guru.nama_guru, guru.avatar')

            ->join('materi', 'materi.mapel=mapel.id_mapel')

            ->join('kelas', 'materi.kelas=kelas.id_kelas')
            
            ->join('guru_kelas', 'kelas.id_kelas=guru_kelas.kelas')
            
            ->join('guru', 'guru_kelas.guru = guru.id_guru')

            ->where('materi.mapel', $id)

            ->orderBy('kelas.id_kelas')

            ->groupBy('mapel.id_mapel, mapel.nama_mapel')

            ->get()->getResultObject();
    }
    
    public function getAllIdMapel($id){
        return $this

            ->select('mapel.id_mapel, mapel.file, mapel.nama_mapel, materi.status, materi.kode_materi, guru.nama_guru, guru.avatar')
            ->join('guru_mapel', 'mapel.id_mapel=guru_mapel.mapel')
            ->join('materi', 'mapel.id_mapel=materi.mapel')
            ->join('guru', 'guru_mapel.guru = guru.id_guru')
            ->where('mapel.id_mapel', $id)
            ->groupBy('mapel.id_mapel')

            ->get()->getResultObject();
    }
    
   
}

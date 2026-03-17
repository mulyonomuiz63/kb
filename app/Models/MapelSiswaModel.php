<?php



namespace App\Models;



use CodeIgniter\Model;



class MapelSiswaModel extends Model

{

    protected $table            = 'mapel_siswa';

    protected $primaryKey       = 'idmapelsiswa';

    protected $allowedFields    = ['idmapel', 'idsiswa'];



    public function getAll($id)
    {

        return $this
            ->join('materi', 'materi.mapel=mapel_siswa.idmapel')
            ->where('mapel_siswa.idsiswa', $id)
            ->groupBy('mapel_siswa.idmapel')
            ->get()->getResultObject();
    }
}

<?php



namespace App\Models;



use CodeIgniter\Model;



class MapelSiswaModel extends Model

{
    protected $table            = 'mapel_siswa';
    protected $primaryKey       = 'idmapelsiswa';
    protected $allowedFields    = ['idmapel', 'idsiswa'];
}

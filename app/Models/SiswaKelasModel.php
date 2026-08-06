<?php
namespace App\Models;

use CodeIgniter\Model;

class SiswaKelasModel extends Model
{
    protected $table            = 'siswa_kelas';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['id_siswa', 'id_kelas'];
}
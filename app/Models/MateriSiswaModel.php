<?php

namespace App\Models;

use CodeIgniter\Model;

class MateriSiswaModel extends Model
{
    protected $table            = 'materi_siswa';
    protected $primaryKey       = 'id_materi_siswa';
    protected $allowedFields    = ['materi', 'kelas', 'mapel', 'siswa', 'dilihat'];
}

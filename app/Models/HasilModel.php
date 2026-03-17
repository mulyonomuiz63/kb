<?php
namespace App\Models;

use CodeIgniter\Model;

class HasilModel extends Model
{
    protected $table = 'hasil';
    protected $primaryKey = 'id';
    protected $allowedFields = ['idsiswa', 'idquiztem', 'skor_dasar', 'bonus', 'total', 'jawaban'];
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class EssaysiswaModel extends Model
{
    protected $table            = 'essay_siswa';
    protected $primaryKey       = 'id_essay_siswa';
    protected $allowedFields    = ['essay_id', 'ujian', 'siswa', 'jawaban', 'score', 'sudah_dikerjakan'];
}

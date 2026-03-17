<?php

namespace App\Models;

use CodeIgniter\Model;


class ReviewModel extends Model
{
    protected $table            = 'review_ujian';
    protected $primaryKey       = 'id_review';
    protected $allowedFields    = ['kode_ujian', 'id_siswa', 'rating', 'komentar', 'status'];
}

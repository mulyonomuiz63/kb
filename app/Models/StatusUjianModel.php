<?php

namespace App\Models;

use CodeIgniter\Model;

class StatusUjianModel extends Model
{
    protected $table            = 'status_ujian';
    protected $primaryKey       = 'idstatusujian';
    protected $allowedFields    = ['kode_ujian', 'status'];
}

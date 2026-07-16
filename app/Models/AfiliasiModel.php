<?php

namespace App\Models;

use CodeIgniter\Model;


class AfiliasiModel extends Model
{
    protected $table            = 'afiliasi';
    protected $primaryKey       = 'idafiliasi';
    protected $allowedFields    = ['nama_afiliasi', 'logo', 'status', 'created_at', 'updated_at', 'deleted_at'];

    // 1. AKTIFKAN SOFT DELETES DI SINI
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';
}

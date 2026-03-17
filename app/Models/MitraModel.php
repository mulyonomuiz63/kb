<?php

namespace App\Models;

use CodeIgniter\Model;

class MitraModel extends Model
{
    protected $table            = 'mitra';
    protected $primaryKey       = 'idmitra';
    protected $allowedFields    = ['nama_mitra', 'email', 'password', 'role', 'is_active', 'date_created', 'avatar','komisi'];

    public function getById($id)
    {
        return $this
            ->where('idmitra', $id)
            ->get()->getRowObject();
    }
    public function getByEmail($email)
    {
        return $this
            ->where('email', $email)
            ->get()->getRowObject();
    }
}

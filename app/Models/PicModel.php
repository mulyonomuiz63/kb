<?php

namespace App\Models;

use CodeIgniter\Model;

class PicModel extends Model
{
    protected $table            = 'pic';
    protected $primaryKey       = 'idpic';
    protected $allowedFields    = ['nama_pic', 'email', 'password', 'role', 'is_active', 'date_created', 'avatar'];

    public function getById($id)
    {
        return $this
            ->where('idpic', $id)
            ->get()->getRowObject();
    }
    public function getByEmail($email)
    {
        return $this
            ->where('email', $email)
            ->get()->getRowObject();
    }
}

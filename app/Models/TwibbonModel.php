<?php

namespace App\Models;

use CodeIgniter\Model;


class TwibbonModel extends Model
{
    protected $table            = 'twibbon';
    protected $primaryKey       = 'idtwibbon';
    protected $allowedFields    = ['url', 'judul','file', 'caption', 'pengguna'];

    public function getAll()
    {
        return $this->get()->getResultObject();
    }
    public function getById($id)
    {
        return $this->where('idtwibbon', $id)
            ->get()->getRowObject();
    }
}

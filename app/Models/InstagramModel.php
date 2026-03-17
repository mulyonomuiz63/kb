<?php

namespace App\Models;

use CodeIgniter\Model;


class InstagramModel extends Model
{
    protected $table            = 'instagram';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['kode'];

    public function getAll()
    {
        return $this->orderBy('id', 'desc')->get()->getResultObject();
    }
    public function getById($id)
    {
        return $this->where('id', $id)
            ->get()->getRowObject();
    }
}

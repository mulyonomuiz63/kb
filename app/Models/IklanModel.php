<?php

namespace App\Models;

use CodeIgniter\Model;


class IklanModel extends Model
{
    protected $table            = 'iklan';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nama', 'file','url', 'text', 'status', 'status_iklan'];

    public function getAll()
    {
        return $this->where('status_iklan', 'modal')->orderBy('status', 'desc')->get()->getResultObject();
    }
    public function getById($id)
    {
        return $this->where('id', $id)
            ->get()->getRowObject();
    }
}

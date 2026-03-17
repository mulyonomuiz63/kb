<?php

namespace App\Models;

use CodeIgniter\Model;


class DiskonModel extends Model
{
    protected $table            = 'diskon';
    protected $primaryKey       = 'iddiskon';
    protected $allowedFields    = ['iddiskon', 'nama', 'diskon'];

    public function getAll()
    {
        return $this->get()->getResultObject();
    }
    public function getById($id)
    {
        return $this->where('iddiskon', $id)
            ->get()->getRowObject();
    }
}

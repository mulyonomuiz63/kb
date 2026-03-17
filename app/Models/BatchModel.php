<?php

namespace App\Models;

use CodeIgniter\Model;


class BatchModel extends Model
{
    protected $table            = 'batch';
    protected $primaryKey       = 'idbatch';
    protected $allowedFields    = ['batch', 'status_batch'];

    public function getAll()
    {
        return $this->get()->getResultObject();
    }
    public function getById($id)
    {
        return $this->where('idbatch', $id)
            ->get()->getRowObject();
    }
}

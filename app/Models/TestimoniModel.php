<?php

namespace App\Models;

use CodeIgniter\Model;


class TestimoniModel extends Model
{
    protected $table            = 'testimoni';
    protected $primaryKey       = 'idtestimoni';
    protected $allowedFields    = ['idsiswa', 'keterangan'];

    public function getAll()
    {
        return $this->get()->getResultObject();
    }
    public function getById($id)
    {
        return $this->where('idtestimoni', $id)
            ->get()->getRowObject();
    }
}

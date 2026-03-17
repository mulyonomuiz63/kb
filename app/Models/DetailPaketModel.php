<?php

namespace App\Models;

use CodeIgniter\Model;


class DetailPaketModel extends Model
{
    protected $table            = 'detail_paket';
    protected $primaryKey       = 'iddetailpaket';
    protected $allowedFields    = ['iddetailpaket', 'idpaket', 'id_ujian', 'id_mapel'];

    public function getAll()
    {
        return $this->get()->getResultObject();
    }
    public function getById($id)
    {
        return $this->where('iddetailpaket', $id)
            ->get()->getRowObject();
    }
}

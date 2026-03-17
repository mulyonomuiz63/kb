<?php

namespace App\Models;

use CodeIgniter\Model;


class DetailTransaksiModel extends Model
{
    protected $table            = 'detail_transaksi';
    protected $primaryKey       = 'iddetailtransaksi';
    protected $allowedFields    = ['idtransaksi', 'idpaket', 'idmapel', 'prince', 'quantity', 'name'];

    public function getAll()
    {
        return $this->get()->getResultObject();
    }
    public function getById($id)
    {
        return $this->where('iddetailtransaksi', $id)
            ->get()->getRowObject();
    }
}

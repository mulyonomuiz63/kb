<?php

namespace App\Models;

use CodeIgniter\Model;


class GaleriModel extends Model
{
    protected $table            = 'galeri';
    protected $primaryKey       = 'idgaleri';
    protected $allowedFields    = ['judul','tgl_pelatihan', 'file'];

    public function getAll()
    {
        return $this->orderBy('idgaleri', 'desc')->get()->getResultObject();
    }
    public function getById($id)
    {
        return $this->where('id', $id)
            ->get()->getRowObject();
    }
}

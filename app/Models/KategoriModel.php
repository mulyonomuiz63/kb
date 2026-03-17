<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table            = 'kategori';
    protected $primaryKey       = 'id_kategori';
    protected $allowedFields    = ['nama_kategori', 'nama_kategori_slug'];

    public function getAll()
    {
        return $this->get()->getResultObject();
    }
    public function getById($id)
    {
        return $this
            ->where('id_kategori', $id)
            ->get()->getRowObject();
    }
}

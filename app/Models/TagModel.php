<?php

namespace App\Models;

use CodeIgniter\Model;

class TagModel extends Model
{
    protected $table            = 'tags';
    protected $primaryKey       = 'id_tag';
    protected $allowedFields    = ['id_bank_soal', 'nama_tag', 'nama_tag_slug'];

    public function getAll()
    {
        return $this->get()->getResultObject();
    }
    public function getById($id)
    {
        return $this
            ->where('id_tag', $id)
            ->get()->getRowObject();
    }
}

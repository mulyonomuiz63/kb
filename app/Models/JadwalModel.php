<?php

namespace App\Models;

use CodeIgniter\Model;


class JadwalModel extends Model
{
    protected $table            = 'jadwal_pelatihan';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['idbatch','materi', 'jenis','kelas', 'kapasitas', 'pendaftar','tgl_pelatihan','mulai','berakhir','status','link'];

    public function getAll()
    {
        return $this->get()->getResultObject();
    }
    public function getById($id)
    {
        return $this->where('id', $id)
            ->get()->getRowObject();
    }
}

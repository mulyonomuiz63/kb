<?php

namespace App\Models;

use CodeIgniter\Model;


class VoucherModel extends Model
{
    protected $table            = 'voucher';
    protected $primaryKey       = 'idvoucher';
    protected $allowedFields    = ['idvoucher', 'idmitra','diskon_voucher','kode_voucher', 'tgl_aktif', 'tgl_exp', 'status'];

    public function getAll()
    {
        return $this->get()->getResultObject();
    }
    public function getById($id)
    {
        return $this->where('idvoucher', $id)
            ->get()->getRowObject();
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;


class DetailVoucherModel extends Model
{
    protected $table            = 'detail_voucher';
    protected $primaryKey       = 'iddetailvoucher';
    protected $allowedFields    = ['idvoucher', 'idpaket'];
}

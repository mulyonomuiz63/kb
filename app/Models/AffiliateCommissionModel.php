<?php

namespace App\Models;

use CodeIgniter\Model;

class AffiliateCommissionModel extends Model
{
    protected $table = 'affiliate_commissions';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'kode_affiliate',
        'id_transaksi',
        'id_paket',
        'komisi',
        'harga',
        'tgl_approved',
        'status',
        'status_penarikan',
        'tgl_pembayaran',
        'created_at'
    ];
}

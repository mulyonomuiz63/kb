<?php

namespace App\Models;
use CodeIgniter\Model;


class AffiliateKlikHarianModel extends Model
{
protected $table = 'affiliate_klik_harian';
protected $allowedFields = ['affiliate_link_id','tanggal','jumlah_klik'];
}
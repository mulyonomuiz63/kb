<?php

namespace App\Models;
use CodeIgniter\Model;


class AffiliateLinkModel extends Model
{
protected $table = 'affiliate_links';
protected $allowedFields = ['kode_affiliate','paket_id','short_code','expired_at'];
}
<?php

namespace App\Models;
use CodeIgniter\Model;


class AffiliateModel extends Model
{
    protected $table = 'affiliates';
    protected $primaryKey = 'id_affiliate';
    
    protected $allowedFields = [
        'user_id',
        'kode_affiliate',
        'bank',
        'norek',
        'nama_akun_bank',
        'cabang_bank',
        'status',
        'syarat_ketentuan',
        'total_edit',
        'created_at'
    ];

    protected $useTimestamps = false;

    protected $beforeInsert = ['generateKodeAffiliate'];

    protected function generateKodeAffiliate(array $data)
    {
        if (! empty($data['data']['kode_affiliate'])) {
            return $data;
        }

        $chars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $length = 8;

        do {
            $kode = '';
            for ($i = 0; $i < $length; $i++) {
                $kode .= $chars[random_int(0, strlen($chars) - 1)];
            }
        } while ($this->where('kode_affiliate', $kode)->countAllResults() > 0);

        $data['data']['kode_affiliate'] = $kode;

        return $data;
    }

    
}

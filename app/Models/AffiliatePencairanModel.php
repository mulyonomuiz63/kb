<?php

namespace App\Models;

use CodeIgniter\Model;

class AffiliatePencairanModel extends Model
{
    protected $table            = 'affiliate_pencairan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;

    // Daftar kolom yang diizinkan untuk di-insert/update (Mass Assignment)
    protected $allowedFields    = [
        'kode_affiliate',
        'kode_penarikan',
        'list_id_komisi',
        'nominal_kotor',
        'potongan_pph21',
        'biaya_admin',
        'nominal_bersih',
        'bank_tujuan',
        'no_rekening',
        'atas_nama',
        'bukti_transfer',
        'status',
        'catatan_admin'
    ];

    // Konfigurasi Timestamps otomatis
    // CodeIgniter akan otomatis mengisi created_at dan updated_at saat insert/update
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

   
}
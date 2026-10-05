<?php

namespace App\Models;

use CodeIgniter\Model;

class PengajuanSertifikatModel extends Model
{
    protected $table            = 'pengajuan_sertifikat';
    protected $primaryKey       = 'id_pengajuan';
    
    protected $allowedFields    = [
        'id_siswa', 
        'idtransaksi', 
        'nama_penerima', 
        'no_hp', 
        'alamat_lengkap', 
        'kode_pos', 
        'status_pengiriman', 
        'no_resi'
    ];
}
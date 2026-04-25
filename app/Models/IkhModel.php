<?php

namespace App\Models;

use CodeIgniter\Model;

class IkhModel extends Model
{
    protected $table            = 'pendaftaran_ikh';
    protected $primaryKey       = 'id_ikh';

    // Izinkan semua field yang ada di tabel untuk diisi
    protected $allowedFields    = [
        'id_siswa', 'nik', 'npwp', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 
        'pendidikan_terakhir','jurusan','tahun_masuk', 'tahun_lulus', 'no_wa', 'email', 'kategori_kantor', 'nama_kantor', 
        'alamat_ktp', 'alamat_korespondensi', 'is_riwayat_hidup', 'is_bukan_pns', 'is_pakta_integritas', 
        'is_pernyataan_ikh', 'file_riwayat_hidup', 'file_bukan_pns', 'file_pakta_integritas', 
        'file_pernyataan_ikh', 'file_ktp', 'file_npwp', 'file_kk', 'file_foto', 
        'file_skck', 'file_ijazah', 'file_spt', 'file_sertifikat', 'file_ttd',
        'status_validasi_admin', 'status_proses', 'status_final', 'status_sertifikat', 'catatan_admin', 'tgl_aktif', 'tgl_exp', 'file_kartu_ikh','kuota'
    ];
}
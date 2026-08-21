<?php

use App\Models\UjianDetailModel;

if (!function_exists('soal_ujian')) {
    /**
     * Mengambil daftar soal ujian secara acak berdasarkan level dan komposisi
     *
     * @param string $kode_ujian Kode ujian yang masih terenkripsi
     * @return array
     */
    function soal_ujian(string $kode_ujian)
    {
        // 1. Dekripsi kode ujian
        $kode_ujian_asli = decrypt_url($kode_ujian);
        
        // 2. PROTEKSI: Jika gagal dekripsi atau kode kosong, langsung hentikan dan kembalikan array kosong
        if (empty($kode_ujian_asli)) {
            return [];
        }

        // 3. Lanjutkan eksekusi jika kode valid
        $ujianDetailModel = new UjianDetailModel();
        
        return $ujianDetailModel->getSoalAcakBerdasarkanLevel($kode_ujian_asli);
    }
}
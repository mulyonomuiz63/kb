<?php
if (!function_exists('is_valid_domain')) {
    /**
     * Mengecek apakah domain email benar-benar ada/aktif
     */
    function is_valid_domain($email)
    {
        // Ambil bagian setelah tanda @
        $domain = substr(strrchr($email, "@"), 1);
        
        // Cek MX Record (Mail Server) atau A Record (IP Host)
        // checkdnsrr mengembalikan TRUE jika domain valid
        return checkdnsrr($domain, "MX") || checkdnsrr($domain, "A");
    }
}
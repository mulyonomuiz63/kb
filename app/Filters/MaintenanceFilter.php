<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class MaintenanceFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cek apakah mode maintenance aktif di .env
        if (env('app.maintenanceMode', false)) {

            // =======================================================
            // 1. PENGATURAN IP YANG DIIZINKAN (WHITELIST)
            // =======================================================
            $allowed_ips = [
                '127.0.0.1',
                '::1',
                '193.186.4.147',
                '103.130.18.252',
                '46.250.226.163' // <-- Masukkan IP dari gambar ke sini
            ];

            // Dapatkan IP pengunjung saat ini
            $user_ip = $request->getIPAddress();

            // Jika IP pengunjung ada di dalam daftar $allowed_ips, 
            // hentikan filter di sini dan biarkan mereka masuk!
            if (in_array($user_ip, $allowed_ips)) {
                return;
            }
            // =======================================================

            // Jika IP TIDAK diizinkan, jalankan logika redirect maintenance
            helper('url');

            $current_url = current_url();
            $target_url  = site_url('maintenance');

            if ($current_url !== $target_url) {
                return redirect()->to($target_url);
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada aksi
    }
}

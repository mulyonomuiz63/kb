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

            helper('url'); // Pastikan helper URL di-load

            $current_url = current_url(); // Dapatkan URL yang sedang diakses user saat ini (Full URL)
            $target_url  = site_url('maintenance'); // Dapatkan target URL maintenance (Full URL)

            // Jika URL saat ini BUKAN URL maintenance, maka alihkan!
            // Ini dijamin mencegah perulangan (loop)
            if ($current_url !== $target_url) {
                return redirect()->to($target_url);
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu aksi setelah request
    }
}

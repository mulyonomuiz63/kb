<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class MaintenanceFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Tentukan kapan maintenance aktif. 
        // Anda bisa menggunakan variabel di .env atau database.
        $isMaintenance = env('app.maintenanceMode', false);

        if ($isMaintenance) {
            // Pastikan halaman maintenance itu sendiri tidak ikut ter-redirect (looping)
            if ($request->getUri()->getPath() !== 'maintenance') {
                return redirect()->to(site_url('maintenance'));
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu aksi setelah request
    }
}
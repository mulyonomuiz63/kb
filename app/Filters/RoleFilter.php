<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    /**
     * @param array|null $arguments Role yang diizinkan (misal: ['1', '2'])
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // 1. Cek apakah user sudah login
        if (!$session->has('role')) {
            return redirect()->to('logout')
                             ->with('pesan', "Silahkan login terlebih dahulu");
        }

        // 2. Cek apakah role user ada di dalam daftar argumen yang diizinkan
        // $arguments dikirim dari Routes, misal: ['admin', 'guru']
        if ($arguments && !in_array($session->get('role'), $arguments)) {
            return redirect()->to('logout')
                             ->with('pesan', "Anda tidak punya otoritas di area ini");
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Kosongkan
    }
}
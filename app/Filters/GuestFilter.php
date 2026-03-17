<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class GuestFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Jika session 'role' ditemukan, artinya user sudah login
        if (session()->has('role')) {
            $role = session()->get('role');

            // Arahkan ke dashboard sesuai role masing-masing
            switch ($role) {
                case 1: return redirect()->to(base_url('sw-admin'));
                case 2: return redirect()->to(base_url('sw-siswa'));
                case 3: return redirect()->to(base_url('sw-guru'));
                case 4: return redirect()->to(base_url('sw-mitra'));
                default: return redirect()->to(base_url('/'));
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Kosong
    }
}
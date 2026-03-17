<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CheckDataSiswa implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id');

        // Cek apakah siswa statusnya masih 'B'
        $siswa = $db->table('siswa')
            ->where('id_siswa', $userId)
            ->where('status', 'B')
            ->get()
            ->getRow();

        if (!empty($siswa)) {
            // Redirect dengan membawa pesan flashdata 'info'
            return redirect()->to('sw-siswa/profile')->with('pesan', 'Mohon segera lengkapi data diri anda untuk dapat mengakses seluruh layanan Kelasbrevet.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu diisi
    }
}

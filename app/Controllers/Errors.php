<?php
namespace App\Controllers;

class Errors extends BaseController
{
    public function show404()
    {
        session()->setFlashdata('pesan', "
            swal({
                title: 'Halaman Tidak Ditemukan',
                text: 'Halaman yang Anda cari tidak tersedia, Gunakan menu navigasi untuk menemukan halaman yang Anda cari.',
                type: 'warning',
                padding: '2em'
            })
        ");
        // Redirect ke dashboard utama (sw-admin atau base_url)
        return redirect()->to(base_url('/'));
    }
}
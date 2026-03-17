<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class ProfileController extends BaseController
{

    protected $adminModel;
    public function __construct()
    {
        $this->adminModel = new \App\Models\AdminModel();
    }
    public function index()
    {
        $data = [
            'title' => 'Profile',
            'admin' => $this->adminModel->asObject()->find(session()->get('id'))
        ];
        return view('admin/profile/list', $data);
    }

    public function updateProfile()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }

        try {

            $fileGambar = $this->request->getFile('avatar');

            // Jika tidak upload gambar baru
            if ($fileGambar->getError() == 4) {

                $nama_gambar = $this->request->getVar('gambar_lama');
            } else {

                // Generate nama random
                $nama_gambar = $fileGambar->getRandomName();

                // Upload file
                $fileGambar->move('assets/app-assets/user', $nama_gambar);

                // Hapus file lama jika bukan default
                $gambar_lama = $this->request->getVar('gambar_lama');

                if ($gambar_lama != 'default.jpg' && file_exists('assets/app-assets/user/' . $gambar_lama)) {
                    unlink('assets/app-assets/user/' . $gambar_lama);
                }
            }

            $this->adminModel->save([
                'id_admin'   => session()->get('id'),
                'nama_admin' => $this->request->getVar('nama_admin'),
                'avatar'     => $nama_gambar
            ]);

            $sessionData = [
                    'avatar'=> $nama_gambar,
                ];
            session()->set($sessionData);

            return redirect()->to('sw-admin/profile')
                ->with('success', 'Profile telah diubah');
        } catch (\Throwable $e) {

            return redirect()->to('sw-admin/profile')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updatePassword()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }

        try {

            $admin = $this->adminModel->asObject()->find(session()->get('id'));

            if (!$admin) {
                return redirect()->to('sw-admin/profile')
                    ->with('error', 'Data admin tidak ditemukan');
            }

            if (password_verify($this->request->getVar('current_password'), $admin->password)) {

                $this->adminModel->save([
                    'id_admin' => $admin->id_admin,
                    'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
                ]);

                return redirect()->to('sw-admin/profile')
                    ->with('success', 'Password telah diubah');
            } else {

                return redirect()->to('sw-admin/profile')
                    ->with('error', 'Current Password Salah');
            }
        } catch (\Throwable $e) {

            return redirect()->to('sw-admin/profile')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

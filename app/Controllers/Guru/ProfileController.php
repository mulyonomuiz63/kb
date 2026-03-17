<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;




class ProfileController extends BaseController
{
    protected $guruModel;
    public function __construct()
    {
        $this->guruModel = new \App\Models\GuruModel();
    }
    // START::PROFILE
    public function index()
    {
        $data['guru'] = $this->guruModel->asObject()->find(session()->get('id'));

        return view('guru/profile', $data);
    }
    public function update()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        $fileGambar = $this->request->getFile('avatar');

        // Cek Gambar, Apakah Tetap Gambar lama
        if ($fileGambar->getError() == 4) {
            $nama_gambar = $this->request->getVar('gambar_lama');
        } else {
            // Generate nama file Random
            $nama_gambar = $fileGambar->getRandomName();
            // Upload Gambar
            $fileGambar->move('assets/app-assets/user', $nama_gambar);
        }

        $this->guruModel->save([
            'id_guru' => session()->get('id'),
            'nama_guru' => $this->request->getVar('nama_guru'),
            'avatar' => $nama_gambar
        ]);
        return redirect()->to('sw-guru/profile')->with('success', 'Profile telah diubah');
    }
    public function editPassword()
    {
        // 1. Validasi Input
        $rules = [
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Password minimal 6 karakter.');
        }

        $id_guru = session()->get('id');

        // 2. Update Data
        // Menggunakan update() lebih spesifik daripada save() jika kita sudah tahu ID-nya
        $dataUpdate = [
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
        ];

        $update = $this->guruModel->update($id_guru, $dataUpdate);

        if ($update) {
            return redirect()->to('sw-guru/profile')->with('success', 'Password berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui password.');
        }
    }
    // END::PROFILE
}

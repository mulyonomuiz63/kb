<?php

namespace App\Controllers\Mitra;

use App\Controllers\BaseController;
use App\Models\MitraModel;
use App\Models\SiswaModel;
use App\Models\VoucherModel;
use App\Models\TransaksiModel;



class ProfileController extends BaseController
{
    protected $mitraModel;
    protected $siswaModel;
    protected $voucherModel;
    protected $transaksiModel;
    

    public function __construct()
    {
        $this->mitraModel = new MitraModel();
        $this->siswaModel = new SiswaModel();

        $this->voucherModel = new VoucherModel();
        $this->transaksiModel = new TransaksiModel();
        

    }
    
    // START::PROFILE & SETTING
    public function index()
    {
       $data['mitra'] = $this->mitraModel->where('idmitra',session('id'))->get()->getRowObject();
        return view('mitra/profile-setting', $data);
    }
    public function updateProfile()
    {
        $fileGambar = $this->request->getFile('avatar');

        // Cek Gambar, Apakah Tetap Gambar lama
        if ($fileGambar->getError() == 4) {
            $nama_gambar = $this->request->getVar('gambar_lama');
        } else {
            // Generate nama file Random
            $nama_gambar = $fileGambar->getRandomName();
            // Upload Gambar
            $fileGambar->move('assets/Mitra-assets/user/', $nama_gambar);
            // hapus File Yang Lama
            if ($this->request->getVar('gambar_lama') != 'default.jpg') {
                unlink('assets/Mitra-assets/user/' . $this->request->getVar('gambar_lama'));
            }
        }

        $this->mitraModel->save([
            'idmitra' => session()->get('id'),
            'nama_mitra' => $this->request->getVar('nama_mitra'),
            'avatar' => $nama_gambar
        ]);
        return redirect()->to('sw-mitra/profile')->with('success', 'Profile berhasil diubah');
    }
    public function updatePassword()
    {
        $admin = $this->mitraModel->asObject()->find(session()->get('id'));

        if (password_verify($this->request->getVar('current_password'), $admin->password)) {
            $this->mitraModel->save([
                'idmitra' => $admin->idmitra,
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
            ]);
            return redirect()->to('sw-mitra/profile')->with('success', 'Kata sandi berhasil diubah');
        } else {
            return redirect()->to('sw-mitra/profile')->with('error', 'Kata sandi saat ini salah');
        }
    }
    

}

<?php

namespace App\Controllers;

use App\Models\MitraModel;
use App\Models\SiswaModel;
use App\Models\VoucherModel;
use App\Models\TransaksiModel;



class Mitra extends BaseController
{
    protected $MitraModel;
    protected $SiswaModel;
    protected $VoucherModel;
    protected $TransaksiModel;
    

    public function __construct()
    {
        $validation = \Config\Services::validation();
        $this->MitraModel = new MitraModel();
        $this->SiswaModel = new SiswaModel();

        $this->VoucherModel = new VoucherModel();
        $this->TransaksiModel = new TransaksiModel();
        

    }
    
     //voucher
    public function index()
    {
        if (session()->get('role') != 4) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => 'active',
            'expanded' => 'true'
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
         $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        // END MENU DATA
        // ================================================

        // MASTER DATA
        $data['voucher'] = $this->VoucherModel->join('mitra','voucher.idmitra=mitra.idmitra')->where('voucher.idmitra', session('id'))->groupBy('voucher.kode_voucher')->get()->getResultObject();
        $data['mitra'] = $this->MitraModel->where('idmitra', session('id'))->get()->getRowObject();

        return view('mitra/voucher/list', $data);
    }

    public function detail_voucher($id)
    {
        if (session()->get('role') != 4) {
            return redirect()->to('auth');
        }
        $kode_voucher = decrypt_url($id);
        // MENU DATA
         $data['dashboard'] = [
            'menu' => 'active',
            'expanded' => 'true'
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['mitra'] = $this->MitraModel->where('mitra.idmitra', session('id'))->get()->getRowObject();
        $data['transaksi'] = $this->TransaksiModel
            ->select('transaksi.*, b.nama_siswa, b.email, c.nama_paket')
            ->join('detail_transaksi d', 'd.idtransaksi=transaksi.idtransaksi')
            ->join('siswa b', 'b.id_siswa = transaksi.idsiswa')
            ->join('paket c', 'c.idpaket = d.idpaket')
            ->where('transaksi.kode_voucher', $kode_voucher)
            ->where('transaksi.status', 'S')
            ->groupBY('transaksi.idtransaksi')
            ->orderBy('transaksi.status', 'esc')
            ->get()->getResultObject();
        
        $data['voucher'] = $this->VoucherModel->join('mitra','voucher.idmitra=mitra.idmitra')->where('voucher.kode_voucher', $kode_voucher)->groupBy('voucher.kode_voucher')->get()->getRowObject();


        return view('mitra/voucher/detail', $data);
    }

    // START::PROFILE & SETTING
    public function profile()
    {
        if (session()->get('role') != 4) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        
        $data['menu_profile'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
       $data['mitra'] = $this->MitraModel->where('idmitra',session('id'))->get()->getRowObject();
        return view('mitra/profile-setting', $data);
    }
    public function edit_profile()
    {
        if (session()->get('role') != 4) {
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
            $fileGambar->move('assets/Mitra-assets/user/', $nama_gambar);
            // hapus File Yang Lama
            if ($this->request->getVar('gambar_lama') != 'default.jpg') {
                unlink('assets/Mitra-assets/user/' . $this->request->getVar('gambar_lama'));
            }
        }

        $this->MitraModel->save([
            'idmitra' => session()->get('id'),
            'nama_mitra' => $this->request->getVar('nama_mitra'),
            'avatar' => $nama_gambar
        ]);

        session()->setFlashdata('pesan', "
            swal({
                title: 'Berhasil!',
                text: 'Profile telah diubah',
                type: 'success',
                padding: '2em'
            }); 
        ");
        return redirect()->to('Mitra/profile');
    }
    public function edit_password()
    {
        if (session()->get('role') != 4) {
            return redirect()->to('auth');
        }
        $admin = $this->MitraModel->asObject()->find(session()->get('id'));

        if (password_verify($this->request->getVar('current_password'), $admin->password)) {
            $this->MitraModel->save([
                'idmitra' => $admin->idmitra,
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
            ]);
            session()->setFlashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Password telah diubah',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
            return redirect()->to('Mitra/profile');
        } else {
            session()->setFlashdata('pesan', "
                        swal({
                            title: 'Oops..',
                            text: 'Current Password Salah',
                            type: 'error',
                            padding: '2em'
                            });
                        ");
            return redirect()->to('Mitra/profile');
        }
    }
    

}

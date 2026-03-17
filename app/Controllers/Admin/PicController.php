<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;


class PicController extends BaseController
{

    protected $picModel;
    public function __construct()
    {
        $this->picModel = new \App\Models\PicModel();
    }

    public function index()
    {
        // MASTER DATA
        $data['pic'] = $this->picModel->asObject()->findAll();
        return view('admin/pic/list', $data);
    }
    public function store()
    {
        // Cek Role (Opsional jika belum ada di Filter)
        if (session()->get('role') != 1) return redirect()->to('auth');

        try {
            $namapic = $this->request->getVar('nama_pic');
            $emails  = $this->request->getVar('email');
            $sandis  = $this->request->getVar('sandi');
            $data_pic = [];

            // Validasi input agar tidak error saat foreach jika input kosong
            if (empty($namapic)) {
                return redirect()->back()->with('error', 'Ops! Tidak ada data yang dikirim.');
            }

            foreach ($namapic as $index => $nama) {
                // Cek email duplikat
                $cek = $this->picModel->where("email", $emails[$index])->first();

                if (!$cek) {
                    $data_pic[] = [
                        'nama_pic'     => $nama,
                        'email'        => $emails[$index],
                        'password'     => password_hash($sandis[$index], PASSWORD_DEFAULT),
                        'role'         => 5,
                        'is_active'    => 1,
                        'date_created' => time(),
                        'avatar'       => 'default.jpg',
                    ];
                }
            }

            if (!empty($data_pic)) {
                // Eksekusi insert
                $this->picModel->insertBatch($data_pic);
                return redirect()->to('sw-admin/pic')->with('success', 'Mantap! ' . count($data_pic) . ' data PIC baru berhasil disimpan.');
            } else {
                // Jika semua email yang diinput sudah ada di DB
                return redirect()->to('sw-admin/pic')->with('error', 'Waduh, semua email yang kamu masukkan sudah terdaftar.');
            }
        } catch (\Exception $e) {
            // Menangkap error jika terjadi kegagalan sistem/database
            return redirect()->to('sw-admin/pic')->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function edit()
    {
        if ($this->request->isAJAX()) {
            $id_input = $this->request->getVar('idpic');
            $idpic = decrypt_url($id_input);

            $data_pic = $this->picModel->asObject()->find($idpic);

            if ($data_pic) {
                // Sertakan token baru untuk keamanan AJAX selanjutnya
                return $this->response->setJSON([
                    'token_baru' => csrf_hash(),
                    'pic'        => $data_pic
                ]);
            }
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Not Found']);
        }
    }

    public function update()
    {
        if (session()->get('role') != 1) return redirect()->to('auth');

        try {
            $idpic  = $this->request->getVar('idpic');
            $id_dec = decrypt_url($idpic);

            // Pastikan ID berhasil didekripsi sebelum lanjut
            if (!$id_dec) {
                return redirect()->back()->with('error', 'ID tidak valid atau sudah kedaluwarsa.');
            }

            $data = [
                'idpic'     => $id_dec,
                'nama_pic'  => $this->request->getVar('nama_pic'),
                'email'     => $this->request->getVar('email'),
                'is_active' => $this->request->getVar('active'),
            ];

            $sandi = $this->request->getVar('sandi');
            if (!empty($sandi)) {
                $data['password'] = password_hash($sandi, PASSWORD_DEFAULT);
            }

            // Eksekusi Update
            $this->picModel->save($data);

            return redirect()->to('sw-admin/pic')->with('success', 'Profil PIC berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function delete($id_input)
    {
        if (session()->get('role') != 1) return redirect()->to('auth');

        try {
            $id_dec = decrypt_url($id_input);

            if (!$id_dec) {
                return redirect()->back()->with('error', 'ID tidak valid atau sudah kedaluwarsa.');
            }

            $this->picModel->delete($id_dec);
            return redirect()->to('sw-admin/pic')->with('success', 'PIC berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}

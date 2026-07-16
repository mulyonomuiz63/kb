<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AfiliasiController extends BaseController
{
    protected $afiliasiModel;

    public function __construct()
    {
        $this->afiliasiModel = new \App\Models\AfiliasiModel();
    }

    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'List Afiliasi', 'url' => '#'],
        ];
        $data['afiliasi'] = $this->afiliasiModel->asObject()->findAll();

        return view('admin/afiliasi/list', $data);
    }

    public function store()
    {
        // Validasi input
        $rules = [
            'nama_afiliasi.*' => 'required',
            'logo.*' => 'max_size[logo,2048]|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Pastikan nama terisi dan file gambar valid (Maks 2MB).');
        }

        try {
            $nama_afiliasi = $this->request->getVar('nama_afiliasi');
            $files         = $this->request->getFileMultiple('logo');

            $data_insert = [];

            if (empty($nama_afiliasi)) {
                return redirect()->back()->with('error', 'Ops! Tidak ada data yang dikirim.');
            }

            // 1. Tentukan path folder tujuan
            // (Gunakan FCPATH agar merujuk ke folder public/ secara absolut)
            $uploadPath = FCPATH . 'uploads/afiliasi/';

            // 2. Cek apakah folder sudah ada. Jika belum, buat foldernya!
            if (!is_dir($uploadPath)) {
                // Parameter 0777 memberikan hak akses baca/tulis/eksekusi
                // Parameter true mengizinkan pembuatan folder secara rekursif (bersarang)
                mkdir($uploadPath, 0777, true);
            }

            foreach ($nama_afiliasi as $index => $nama) {
                $namaFile = 'default.jpg';

                // Proses file upload per baris jika ada
                if (isset($files[$index]) && $files[$index]->isValid() && !$files[$index]->hasMoved()) {
                    $namaFile = $files[$index]->getRandomName();

                    // Pindahkan file ke path yang sudah dipastikan ketersediaannya
                    $files[$index]->move($uploadPath, $namaFile);
                }

                $data_insert[] = [
                    'nama_afiliasi' => $nama,
                    'logo'          => $namaFile,
                    'status'        => 'A', // Default Aktif (A)
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                ];
            }

            if (!empty($data_insert)) {
                $this->afiliasiModel->insertBatch($data_insert);
                return redirect()->to('sw-admin/afiliasi')->with('success', count($data_insert) . ' data Afiliasi berhasil disimpan.');
            }
        } catch (\Exception $e) {
            return redirect()->to('sw-admin/afiliasi')->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function edit()
    {
        if ($this->request->isAJAX()) {
            $id_input = $this->request->getVar('idafiliasi');
            $idafiliasi = decrypt_url($id_input);

            $data_afiliasi = $this->afiliasiModel->asObject()->find($idafiliasi);

            if ($data_afiliasi) {
                return $this->response->setJSON([
                    'token_baru' => csrf_hash(),
                    'afiliasi'   => $data_afiliasi
                ]);
            }
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Not Found']);
        }
    }

    public function update()
    {
        $id_input = $this->request->getVar('idafiliasi');
        $id_dec   = decrypt_url($id_input);

        if (!$id_dec) {
            return redirect()->back()->with('error', 'ID tidak valid atau sudah kedaluwarsa.');
        }

        // Validasi input
        $rules = [
            'nama_afiliasi' => 'required',
            'status'        => 'required|in_list[A,T]',
            'logo'          => 'max_size[logo,2048]|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Pastikan form diisi dengan benar dan gambar maks 2MB.');
        }

        try {
            $logoLama = $this->request->getVar('logo_lama');
            $fileLogo = $this->request->getFile('logo');
            $namaFile = $logoLama;

            // Jika ada file baru yang diupload
            if ($fileLogo->isValid() && !$fileLogo->hasMoved()) {
                $namaFile = $fileLogo->getRandomName();
                $fileLogo->move('uploads/afiliasi/', $namaFile);

                // Hapus file lama jika bukan default
                $pathFile = FCPATH . 'uploads/afiliasi/' . $logoLama;
                if ($logoLama != 'default.jpg' && file_exists($pathFile)) {
                    unlink($pathFile);
                }
            }

            $data = [
                'idafiliasi'    => $id_dec,
                'nama_afiliasi' => $this->request->getVar('nama_afiliasi'),
                'logo'          => $namaFile,
                'status'        => $this->request->getVar('status'),
            ];

            $this->afiliasiModel->save($data);

            return redirect()->to('sw-admin/afiliasi')->with('success', 'Data Afiliasi berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function delete($id_input)
    {
        try {
            $id_dec = decrypt_url($id_input);

            if (!$id_dec) {
                return redirect()->back()->with('error', 'ID tidak valid atau sudah kedaluwarsa.');
            }

            // Pastikan datanya ada sebelum di-update
            $afiliasi = $this->afiliasiModel->find($id_dec);
            if (!$afiliasi) {
                return redirect()->back()->with('error', 'Data afiliasi tidak ditemukan.');
            }

            // Lakukan Soft Delete (Hanya update kolom deleted_at)
            $this->afiliasiModel->update($id_dec, [
                'deleted_at' => date('Y-m-d H:i:s')
            ]);

            return redirect()->to('sw-admin/afiliasi')->with('success', 'Afiliasi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}

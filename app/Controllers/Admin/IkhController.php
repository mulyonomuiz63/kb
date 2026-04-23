<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;


class IkhController extends BaseController
{
    protected $ikhModel;
    public function __construct()
    {
        $this->ikhModel = new \App\Models\IkhModel();
    }

    // 1. Tampilkan Tabel Daftar IKH
    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'List IKH', 'url' => '#'],
        ];

        // Ambil semua data pendaftaran (kecuali yang masih draft awal)
        $data['list_ikh'] = $this->ikhModel->where('status_validasi_admin !=', 'draft')
                                           ->orderBy('created_at', 'DESC')->findAll();
        
        return view('admin/ikh/list', $data);
    }

    // 2. Halaman Detail & Review Berkas
    public function review($id)
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'List IKH', 'url' => base_url('sw-admin/ikh')],
            ['title' => 'Data IKH', 'url' => '#'],
        ];
        $id_ikh = decrypt_url($id);
        $ikh = $this->ikhModel->find($id_ikh);
        if (!$ikh) {
            return redirect()->to('admin/ikh')->with('error', 'Data tidak ditemukan.');
        }

        $data['ikh'] = $ikh;
        $data['title'] = 'Review Berkas IKH - ' . $ikh['nama_lengkap'];
        
        return view('admin/ikh/review', $data);
    }

    // 3. Proses AJAX Update Status & Catatan
    public function updateStatus()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $id_ikh = $this->request->getPost('id_ikh');
        $jenis_update = $this->request->getPost('jenis_update'); // 'validasi', 'proses', 'final'
        $ikh = $this->ikhModel->find($id_ikh);
        $updateData = [];

        if ($jenis_update == 'validasi') {
            $updateData['status_validasi_admin'] = $this->request->getPost('status'); // 'valid' / 'ditolak'
            $updateData['catatan_admin'] = $this->request->getPost('catatan_admin');
            
            // Jika valid, otomatis set status_proses ke pending agar masuk antrean
            if($updateData['status_validasi_admin'] == 'valid') {
                send_notif(
                    $ikh['id_siswa'],
                    'Validasi Berkas IKH',
                    'Pengajuan IKH divalidasi, tunggu proses selanjutnya dari admin',
                    base_url('sw-siswa/perijinan-ikh')
                );
                $updateData['status_proses'] = 'selesai'; 
                $updateData['status_final'] = 'selesai';
            }else{
                send_notif(
                    $ikh['id_siswa'], 
                    'Pengajuan IKH Ditolak',
                    'Pengajuan IKH ditolak, Silahkan cek catatan admin',
                     base_url('sw-siswa/perijinan-ikh')
                );
            }
        }

        try {
            $this->ikhModel->update($id_ikh, $updateData);
            return $this->response->setJSON(['success' => true, 'message' => 'Status berhasil diperbarui!', 'csrf_hash' => csrf_hash()]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal memperbarui status.', 'csrf_hash' => csrf_hash()]);
        }
    }

    // 4. Proses AJAX Upload Kartu IKH & Set Tanggal
    public function uploadKartu()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $id_ikh = $this->request->getPost('id_ikh');
        $tgl_aktif = $this->request->getPost('tgl_aktif');
        $tgl_exp = $this->request->getPost('tgl_exp');
        $file = $this->request->getFile('file_kartu_ikh');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            
            // Validasi file
            if ($file->getSize() > 2097152) {
                return $this->response->setJSON(['success' => false, 'message' => 'File harus PDF dan maksimal 2MB.', 'csrf_hash' => csrf_hash()]);
            }

            // Hapus kartu lama jika ada (Replace)
            $dataLama = $this->ikhModel->find($id_ikh);
            $namaFileLama = is_array($dataLama) ? ($dataLama['file_kartu_ikh'] ?? null) : ($dataLama->file_kartu_ikh ?? null);
            
            if (!empty($namaFileLama)) {
                // PERBAIKAN: Cukup arahkan ke 'uploads/ikh/' karena 'kartu/' sudah menempel di $namaFileLama
                $pathFileLama = FCPATH . 'uploads/ikh/' . $namaFileLama;
                
                if (file_exists($pathFileLama) && is_file($pathFileLama)) {
                    unlink($pathFileLama); // Hapus file fisik dari server
                }
            }

            // Simpan file baru
            $newName = 'KARTU_IKH_' . $id_ikh . '_' . $file->getRandomName();
            $basePath = FCPATH . 'uploads/ikh/kartu/';
            if (!is_dir($basePath)) mkdir($basePath, 0755, true);

            try {
                $file->move($basePath, $newName);
                
                // Update ke database
                $this->ikhModel->update($id_ikh, [
                    'file_kartu_ikh' => 'kartu/' . $newName,
                    'tgl_aktif' => $tgl_aktif,
                    'tgl_exp' => $tgl_exp,
                    'status_sertifikat' => 'terbit' // Otomatis terbit!
                ]);

                return $this->response->setJSON(['success' => true, 'message' => 'Kartu IKH berhasil diterbitkan!', 'csrf_hash' => csrf_hash()]);

            } catch (\Exception $e) {
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengunggah file.', 'csrf_hash' => csrf_hash()]);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Pilih file PDF yang valid.', 'csrf_hash' => csrf_hash()]);
    }
}

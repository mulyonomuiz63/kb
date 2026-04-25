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
            if ($updateData['status_validasi_admin'] == 'valid') {
                send_notif(
                    $ikh['id_siswa'],
                    'Validasi Berkas IKH',
                    'Pengajuan IKH divalidasi, tunggu proses selanjutnya dari admin',
                    base_url('sw-siswa/ikh')
                );
                $updateData['status_proses'] = 'selesai';
                $updateData['status_final'] = 'selesai';
            } else {
                send_notif(
                    $ikh['id_siswa'],
                    'Pengajuan IKH Ditolak',
                    'Pengajuan IKH ditolak, Silahkan cek catatan admin',
                    base_url('sw-siswa/ikh')
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

    public function uploadBerkas()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $id_ikh = $this->request->getPost('id_ikh');
        $existing = $this->ikhModel->find($id_ikh);

        if (!$existing) {
            return $this->response->setJSON([
                'status' => 'error',
                'msg' => 'Data IKH tidak ditemukan',
                csrf_token() => csrf_hash()
            ]);
        }

        $fields = ['file_riwayat_hidup', 'file_bukan_pns', 'file_pakta_integritas', 'file_pernyataan_ikh', 'file_skck'];
        $updateData = [];
        $uploadedCount = 0;

        foreach ($fields as $field) {
            $file = $this->request->getFile($field);

            // Cek apakah ada file yang diunggah
            if ($file && $file->isValid() && !$file->hasMoved()) {

                $subFolder = str_replace('file_', '', $field);
                $uploadPath = FCPATH . 'uploads/ikh/' . $subFolder;

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                // Hapus file lama jika ada
                if (!empty($existing[$field]) && file_exists($uploadPath . '/' . $existing[$field])) {
                    @unlink($uploadPath . '/' . $existing[$field]);
                }

                $newName = $file->getRandomName();
                if ($file->move($uploadPath, $newName)) {
                    $updateData[$field] = $subFolder . '/' . $newName;
                    $uploadedCount++;
                }
            }
        }

        if (!empty($updateData)) {
            $this->ikhModel->update($id_ikh, $updateData);
            return $this->response->setJSON([
                'status' => 'success',
                'msg' => $uploadedCount . ' Berkas berhasil diperbarui',
                csrf_token() => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'msg' => 'Tidak ada file yang dipilih atau file tidak valid',
            csrf_token() => csrf_hash()
        ]);
    }

    // 5. Proses AJAX Upload Kartu IKH & Set Tanggal
    public function uploadKartu()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $id_ikh = $this->request->getPost('id_ikh');
        $tgl_aktif = $this->request->getPost('tgl_aktif');
        $tgl_exp = $this->request->getPost('tgl_exp');

        // 1. Ambil data lama untuk keperluan hapus file fisik & cek kuota
        $dataLama = $this->ikhModel->find($id_ikh);
        if (!$dataLama) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak ditemukan.', 'csrf_hash' => csrf_hash()]);
        }

        // 2. Tangkap file multiple
        $files = $this->request->getFileMultiple('file_kartu_ikh');
        $uploadedFiles = [];
        $basePath = FCPATH . 'uploads/ikh/kartu/';

        if (!is_dir($basePath)) mkdir($basePath, 0755, true);

        foreach ($files as $file) {
            if ($file->isValid() && !$file->hasMoved()) {

                // Validasi ukuran per file (2MB)
                if ($file->getSize() > 2097152) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Salah satu file terlalu besar (Max 2MB).', 'csrf_hash' => csrf_hash()]);
                }

                // Simpan file
                $newName = 'KARTU_IKH_' . $id_ikh . '_' . $file->getRandomName();
                try {
                    $file->move($basePath, $newName);
                    // Simpan path relatifnya saja agar konsisten dengan database
                    $uploadedFiles[] = 'kartu/' . $newName;
                } catch (\Exception $e) {
                    continue; // Skip jika gagal satu
                }
            }
        }

        // 3. Jika ada file yang berhasil diunggah
        if (!empty($uploadedFiles)) {

            // --- LOGIKA HAPUS FILE FISIK LAMA (REPLACE TOTAL) ---
            $fileLamaJson = is_array($dataLama) ? ($dataLama['file_kartu_ikh'] ?? '') : ($dataLama->file_kartu_ikh ?? '');
            $arrFileLama = json_decode($fileLamaJson, true) ?? [];

            // Jika data lama bukan JSON (hanya string tunggal dari sistem lama), bungkus jadi array
            if (empty($arrFileLama) && !empty($fileLamaJson)) {
                $arrFileLama = [$fileLamaJson];
            }

            foreach ($arrFileLama as $oldFile) {
                $pathOld = FCPATH . 'uploads/ikh/' . $oldFile;
                if (file_exists($pathOld) && is_file($pathOld)) {
                    @unlink($pathOld);
                }
            }

            // --- UPDATE DATABASE ---
            $currentStatus = is_array($dataLama) ? $dataLama['status_sertifikat'] : $dataLama->status_sertifikat;
            $newKuota = (int)$dataLama['kuota'];

            // Kuota hanya dikurangi jika status belum 'terbit'
            if ($currentStatus !== 'terbit') {
                // Logika: Kurangi 1, tapi hasil minimal adalah 0
                $newKuota = max(0, $newKuota - 1);
            }

            try {
                $this->ikhModel->update($id_ikh, [
                    'file_kartu_ikh'    => json_encode($uploadedFiles), // Simpan dalam format JSON
                    'tgl_aktif'         => $tgl_aktif,
                    'tgl_exp'           => $tgl_exp,
                    'status_sertifikat' => 'terbit',
                    'kuota'             => $newKuota
                ]);

                // Kirim Notifikasi
                send_notif(
                    $dataLama['id_siswa'],
                    'Kartu IKH Diterbitkan',
                    'Kartu IKH Anda sudah berhasil diterbitkan (Multiple Files).',
                    base_url('sw-siswa/ikh')
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Kartu IKH berhasil diterbitkan!',
                    'csrf_hash' => csrf_hash()
                ]);
            } catch (\Exception $e) {
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal memperbarui database.', 'csrf_hash' => csrf_hash()]);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Tidak ada file valid yang terpilih.', 'csrf_hash' => csrf_hash()]);
    }
}

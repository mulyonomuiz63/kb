<?php

namespace App\Controllers\Pic;

use App\Controllers\BaseController;
use Google\Client;
use Google\Service\Drive;

class IkhController extends BaseController
{
    protected $ikhModel;
    protected $siswaModel;
    public function __construct()
    {
        $this->ikhModel = new \App\Models\IkhModel();
        $this->siswaModel = new \App\Models\SiswaModel();
    }

    // 1. Tampilkan Tabel Daftar IKH
    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-pic')],
            ['title' => 'List IKH', 'url' => '#'],
        ];

        // Ambil semua data pendaftaran (kecuali yang masih draft awal)
        $data['list_ikh'] = $this->ikhModel->where('status_validasi_admin !=', 'draft')
            ->orderBy('created_at', 'DESC')->findAll();

        return view('pic/ikh/list', $data);
    }

    public function updatePemohon()
    {
        // 1. Validasi Input (disesuaikan dengan kebutuhan)
        $rules = [
            'id_ikh' => 'required',
            'nik'    => 'required|numeric|min_length[16]',
            'npwp'   => 'required',
            'email'  => 'required|valid_email',
            'no_wa'  => 'required|numeric',
        ];

        // Jika validasi gagal, kembalikan pesan error dalam format JSON
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success'   => false,
                'message'   => 'Gagal menyimpan. Pastikan NIK, NPWP, Email, dan No WhatsApp valid.',
                'csrf_hash' => csrf_hash() // Penting: Kirim balik token CSRF terbaru
            ]);
        }

        // 2. Ambil ID Data yang akan diedit
        $idIkh = $this->request->getPost('id_ikh');
        $riwayat = $this->request->getVar('riwayat_pekerjaan'); // Ini akan menjadi array
        if (!is_array($riwayat)) {
            $riwayat = [];
        }
        $riwayat_bersih = array_values(array_filter($riwayat, function($value) {
            return !empty(trim($value));
        }));

        // 4. Encode menjadi format JSON
        $json_riwayat = json_encode($riwayat_bersih);

        // 3. Kumpulkan data dari form view admin
        $dataUpdate = [
            'nama_lengkap'         => $this->request->getPost('nama_lengkap'),
            'nik'                  => $this->request->getPost('nik'),
            'npwp'                 => $this->request->getPost('npwp'),
            'tempat_lahir'         => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'        => $this->request->getPost('tanggal_lahir'),
            'pendidikan_terakhir'  => $this->request->getPost('pendidikan_terakhir'),
            'jurusan'              => $this->request->getPost('jurusan'),
            'tahun_masuk'          => $this->request->getPost('tahun_masuk'),
            'tahun_lulus'          => $this->request->getPost('tahun_lulus'),
            'no_wa'                => $this->request->getPost('no_wa'),
            'email'                => $this->request->getPost('email'),
            'kategori_kantor'      => $this->request->getPost('kategori_kantor'),
            'nama_kantor'          => $this->request->getPost('nama_kantor'),
            'alamat_ktp'           => $this->request->getPost('alamat_ktp'),
            'alamat_korespondensi' => $this->request->getPost('alamat_korespondensi'),
            'riwayat_pekerjaan'    => $json_riwayat,
        ];

        // 4. Proses Update ke Database
        $updated = $this->ikhModel->update($idIkh, $dataUpdate);

        // 5. Kembalikan Response Sukses ke Frontend
        if ($updated) {
            return $this->response->setJSON([
                'success'   => true,
                'message'   => 'Seluruh data pemohon berhasil diperbarui.',
                'csrf_hash' => csrf_hash()
            ]);
        } else {
            return $this->response->setJSON([
                'success'   => false,
                'message'   => 'Terjadi kesalahan sistem saat menyimpan ke database.',
                'csrf_hash' => csrf_hash()
            ]);
        }
    }

    // 2. Halaman Detail & Review Berkas
    public function review($id)
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-pic')],
            ['title' => 'List IKH', 'url' => base_url('sw-pic/ikh')],
            ['title' => 'Data IKH', 'url' => '#'],
        ];
        $id_ikh = decrypt_url($id);
        $ikh = $this->ikhModel->find($id_ikh);
        if (!$ikh) {
            return redirect()->to('pic/ikh')->with('error', 'Data tidak ditemukan.');
        }

        $data['ikh'] = $ikh;
        $data['idIkh'] = $id_ikh;
        $data['title'] = 'Review Berkas IKH - ' . $ikh['nama_lengkap'];

        return view('pic/ikh/review', $data);
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

        // Ambil data siswa untuk penamaan folder server
        $idSiswa = is_array($existing) ? $existing['id_siswa'] : $existing->id_siswa;
        $dataSiswa = $this->siswaModel->find($idSiswa);

        if (!$dataSiswa) {
            return $this->response->setJSON([
                'status' => 'error',
                'msg' => 'Data Siswa tidak ditemukan',
                csrf_token() => csrf_hash()
            ]);
        }

        $fields = ['file_riwayat_hidup', 'file_bukan_pns', 'file_pakta_integritas', 'file_pernyataan_ikh', 'file_skck'];
        $updateData = [];
        $uploadedCount = 0;

        // Persiapan Koneksi & Folder server
        try {
            $service = $this->getDriveService();
            $namaSiswa = $dataSiswa['nama_siswa'];
            $noInduk   = $dataSiswa['no_induk_siswa'];
            $namaArray = explode(' ', $namaSiswa);
            $duaKataPertama = array_slice($namaArray, 0, 2);
            $namaDepan = implode('_', $duaKataPertama);
            $folderSiswaName = strtoupper($namaDepan) . "_" . $noInduk;
            $folderSiswaId = $this->getOrCreateFolder($service, $folderSiswaName, setting('folder_id_drive'));
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'msg' => 'Gagal terhubung ke server: ' . $e->getMessage(),
                csrf_token() => csrf_hash()
            ]);
        }

        foreach ($fields as $field) {
            $file = $this->request->getFile($field);

            // Cek apakah ada file yang diunggah
            if ($file && $file->isValid() && !$file->hasMoved()) {

                // 1. Hapus file lama di Drive (jika ada)
                $fileIdLama = is_array($existing) ? ($existing[$field] ?? null) : ($existing->$field ?? null);
                if (!empty($fileIdLama)) {
                    // Cek jika ini ID Drive (tidak memiliki titik / ekstensi lokal)
                    if (strpos($fileIdLama, '.') === false) {
                        try {
                            $service->files->delete($fileIdLama);
                        } catch (\Exception $e) {
                            // Abaikan jika file lama tidak ada di Drive
                        }
                    } else {
                        // Jika ternyata file lama masih di folder lokal, kita hapus juga sebagai pembersihan
                        $pathOld = FCPATH . 'uploads/ikh/' . $fileIdLama;
                        if (file_exists($pathOld) && is_file($pathOld)) @unlink($pathOld);
                    }
                }

                // 2. Upload file baru ke Drive
                $namaInputBersih = strtoupper(str_replace('file_', '', $field));
                $newName = $namaInputBersih . "_" . $dataSiswa['no_induk_siswa'] . "_" . time();

                $fileMetadata = new \Google\Service\Drive\DriveFile([
                    'name' => $newName,
                    'parents' => [$folderSiswaId]
                ]);

                try {
                    $uploadedFile = $service->files->create($fileMetadata, [
                        'data' => file_get_contents($file->getTempName()),
                        'mimeType' => $file->getClientMimeType(),
                        'uploadType' => 'multipart',
                        'fields' => 'id'
                    ]);

                    $updateData[$field] = $uploadedFile->id; // Simpan ID server
                    $uploadedCount++;
                } catch (\Exception $e) {
                    continue; // Skip jika 1 file gagal, lanjut file berikutnya
                }
            }
        }

        if (!empty($updateData)) {
            $this->ikhModel->update($id_ikh, $updateData);
            return $this->response->setJSON([
                'status' => 'success',
                'msg' => $uploadedCount . ' Berkas berhasil diperbarui ke server',
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

        $idSiswa = is_array($dataLama) ? $dataLama['id_siswa'] : $dataLama->id_siswa;
        $dataSiswa = $this->siswaModel->find($idSiswa);

        if (!$dataSiswa) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data Siswa tidak ditemukan.', 'csrf_hash' => csrf_hash()]);
        }

        // 2. Tangkap file multiple
        $files = $this->request->getFileMultiple('file_kartu_ikh');
        $uploadedFiles = [];

        // Persiapan Koneksi & Folder server
        try {
            $service = $this->getDriveService();
            $folderSiswaName = strtoupper($dataSiswa['nama_siswa']) . "_" . $dataSiswa['no_induk_siswa'];
            $folderSiswaId = $this->getOrCreateFolder($service, $folderSiswaName, setting('folder_id_drive'));
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal terhubung ke server.', 'csrf_hash' => csrf_hash()]);
        }

        foreach ($files as $index => $file) {
            if ($file->isValid() && !$file->hasMoved()) {

                // Validasi ukuran per file (2MB)
                if ($file->getSize() > 2097152) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Salah satu file terlalu besar (Max 2MB).', 'csrf_hash' => csrf_hash()]);
                }

                // Upload file ke Drive
                $newName = 'KARTU_IKH_' . $dataSiswa['no_induk_siswa'] . '_' . time() . '_' . $index;

                $fileMetadata = new \Google\Service\Drive\DriveFile([
                    'name' => $newName,
                    'parents' => [$folderSiswaId]
                ]);

                try {
                    $uploadedFileDrive = $service->files->create($fileMetadata, [
                        'data' => file_get_contents($file->getTempName()),
                        'mimeType' => $file->getClientMimeType(),
                        'uploadType' => 'multipart',
                        'fields' => 'id'
                    ]);
                    // Simpan ID Drive ke dalam array
                    $uploadedFiles[] = $uploadedFileDrive->id;
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
                // Deteksi apakah ID Drive atau path lokal
                if (strpos($oldFile, '.') === false) {
                    try {
                        $service->files->delete($oldFile);
                    } catch (\Exception $e) {
                    }
                } else {
                    // Hapus file lokal lama sebagai bentuk pembersihan
                    $pathOld = FCPATH . 'uploads/ikh/' . $oldFile;
                    if (file_exists($pathOld) && is_file($pathOld)) {
                        @unlink($pathOld);
                    }
                }
            }

            // --- UPDATE DATABASE ---
            $currentStatus = is_array($dataLama) ? $dataLama['status_sertifikat'] : $dataLama->status_sertifikat;
            $newKuota = (int)(is_array($dataLama) ? $dataLama['kuota'] : $dataLama->kuota);

            // Kuota hanya dikurangi jika status belum 'terbit'
            if ($currentStatus !== 'terbit') {
                $newKuota = max(0, $newKuota - 1);
            }

            try {
                $this->ikhModel->update($id_ikh, [
                    'file_kartu_ikh'    => json_encode($uploadedFiles), // Simpan dalam format JSON ID Drive
                    'tgl_aktif'         => $tgl_aktif,
                    'tgl_exp'           => $tgl_exp,
                    'status_sertifikat' => 'terbit',
                    'kuota'             => $newKuota
                ]);

                // Kirim Notifikasi
                send_notif(
                    $idSiswa,
                    'Kartu IKH Diterbitkan',
                    'Kartu IKH Anda sudah berhasil diterbitkan dan tersedia.',
                    base_url('sw-siswa/ikh')
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Kartu IKH berhasil diunggah ke Drive dan diterbitkan!',
                    'csrf_hash' => csrf_hash()
                ]);
            } catch (\Exception $e) {
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal memperbarui database.', 'csrf_hash' => csrf_hash()]);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Tidak ada file valid yang terpilih.', 'csrf_hash' => csrf_hash()]);
    }

    public function uploadFileAjax()
    {

        $namaInput = $this->request->getPost('input_name');
        $idIkh     = $this->request->getPost('id_ikh');

        // Ambil data siswa untuk nama folder
        $dataSiswa = $this->siswaModel
        ->join('pendaftaran_ikh', 'pendaftaran_ikh.id_siswa = siswa.id_siswa')
        ->where('pendaftaran_ikh.id_ikh', $idIkh)
        ->first();
        if (!$dataSiswa) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data siswa tidak ditemukan.']);
        }

        $namaSiswa = $dataSiswa['nama_siswa'];
        $noInduk   = $dataSiswa['no_induk_siswa'];
        $namaArray = explode(' ', $namaSiswa);
        $duaKataPertama = array_slice($namaArray, 0, 2);
        $namaDepan = implode('_', $duaKataPertama);
        $folderSiswaName = strtoupper($namaDepan) . "_" . $noInduk;

        $file = $this->request->getFile('file_dokumen');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            try {
                $service = $this->getDriveService();

                // --- LOGIKA FOLDER KHUSUS SISWA ---
                // 1. Cari atau Buat Folder Siswa
                $folderSiswaId = $this->getOrCreateFolder($service, $folderSiswaName, setting('folder_id_drive'));

                // 2. Hapus File Lama (jika ada di DB)
                $dataLama = $this->ikhModel->find($idIkh);
                $fileIdLama = is_array($dataLama) ? ($dataLama[$namaInput] ?? null) : ($dataLama->$namaInput ?? null);
                if ($fileIdLama) {
                    try {
                        $service->files->delete($fileIdLama);
                    } catch (\Exception $e) {
                        // Abaikan jika file lama tidak ditemukan di drive
                    }
                }

                // 3. Upload Baru ke dalam folderSiswaId
                $fileName = strtoupper(str_replace('file_', '', $namaInput)) . "_" . $noInduk . "_" . time();

                $fileMetadata = new \Google\Service\Drive\DriveFile([
                    'name' => $fileName,
                    'parents' => [$folderSiswaId] // Masuk ke folder siswa
                ]);

                $uploadedFile = $service->files->create($fileMetadata, [
                    'data' => file_get_contents($file->getTempName()),
                    'mimeType' => $file->getClientMimeType(),
                    'uploadType' => 'multipart',
                    'fields' => 'id'
                ]);

                // 4. Update Database
                $this->ikhModel->update($idIkh, [$namaInput => $uploadedFile->id]);

                return $this->response->setJSON([
                    'success'     => true,
                    'message'     => "Berhasil upload ke folder $folderSiswaName",
                    'csrf_hash'   => csrf_hash()
                ]);

            } catch (\Exception $e) {
                return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        
        return $this->response->setJSON(['success' => false, 'message' => 'File tidak valid.']);
    }

    private function getDriveService()
    {
        $client = new Client();
        $client->setAuthConfig(APPPATH . 'ThirdParty/oauth-credentials.json');
        $client->addScope(Drive::DRIVE);

        $tokenPath = WRITEPATH . 'google-token-admin.json';

        if (!file_exists($tokenPath)) {
            throw new \Exception("Admin belum melakukan verifikasi Drive. Akses /auth/admin-drive");
        }

        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);

        // Jika Token Expired, Refresh otomatis
        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $newToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $fullToken = array_merge($accessToken, $newToken);
                file_put_contents($tokenPath, json_encode($fullToken));
            }
        }

        return new Drive($client);
    }
    private function getOrCreateFolder($service, $folderName, $parentFolderId)
    {
        // Cari folder berdasarkan nama dan parent-nya
        $query = "name = '$folderName' and mimeType = 'application/vnd.google-apps.folder' and '$parentFolderId' in parents and trashed = false";
        $results = $service->files->listFiles([
            'q' => $query,
            'fields' => 'files(id, name)'
        ]);

        // Jika ketemu, kembalikan ID-nya
        if (count($results->getFiles()) > 0) {
            return $results->getFiles()[0]->id;
        }

        // Jika tidak ketemu, buat folder baru
        $folderMetadata = new \Google\Service\Drive\DriveFile([
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => [$parentFolderId]
        ]);

        $folder = $service->files->create($folderMetadata, ['fields' => 'id']);
        return $folder->id;
    }
}

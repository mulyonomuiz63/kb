<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use Google\Client;
use Google\Service\Drive;

class IKHController extends BaseController
{
    protected $ikhModel;
    protected $siswaModel;
    protected $data;
    public function __construct()
    {
        $this->ikhModel = new \App\Models\IkhModel();
        $this->siswaModel = new \App\Models\SiswaModel();
        $this->data = [];
    }



    public function index()
    {
        $ikh = $this->ikhModel->where([
            'id_siswa' => session('id')
        ])->first();
        $siswa = $this->siswaModel->where('id_siswa', session('id'))->first();

        $this->data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-siswa')],
            ['title' => 'Sertifikasi IKH', 'url' => base_url('sw-siswa/ikh')],
        ];

        if (empty($ikh)) {
            session()->setFlashdata('pesan', "
                        swal({
                            title: 'Informasi!',
                            text: 'Anda belum memiliki paket untuk pengajuan IKH',
                            type: 'info',
                            padding: '2em'
                        }); 
                    ");
            return redirect()->to('list-bimbel');
        }
        $this->data['ikh'] = $ikh;
        $this->data['siswa'] = $siswa;
        $this->data['title'] = 'Sertifikasi Izin Kuasa Hukum (IKH)';

        return view('siswa/ikh/list', $this->data);
    }

    // FUNGSI 1: Simpan Data Teks Saja
    public function store()
    {
        $rules = [
            'nik'   => 'required|numeric|min_length[16]',
            'npwp'  => 'required',
            'email' => 'required|valid_email',
            'no_wa' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan. Pastikan NIK, NPWP, Email, dan No WhatsApp valid.');
        }

        $idIkh = $this->request->getPost('id_ikh');
        $isEdit = !empty($idIkh);

        $dataText = [
            'id_siswa'              => session('id'),
            'nik'                   => $this->request->getPost('nik'),
            'npwp'                  => $this->request->getPost('npwp'),
            'nama_lengkap'          => $this->request->getPost('nama_lengkap'),
            'tempat_lahir'          => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'         => $this->request->getPost('tanggal_lahir'),
            'pendidikan_terakhir'   => $this->request->getPost('pendidikan_terakhir'),
            'jurusan'               => $this->request->getPost('jurusan'),
            'tahun_masuk'           => $this->request->getPost('tahun_masuk'),
            'tahun_lulus'           => $this->request->getPost('tahun_lulus'),
            'no_wa'                 => $this->request->getPost('no_wa'),
            'email'                 => $this->request->getPost('email'),
            'kategori_kantor'       => $this->request->getPost('kategori_kantor'),
            'nama_kantor'           => $this->request->getPost('nama_kantor'),
            'alamat_ktp'            => $this->request->getPost('alamat_ktp'),
            'alamat_korespondensi'  => $this->request->getPost('alamat_korespondensi'),
            'is_riwayat_hidup'      => $this->request->getPost('check_riwayat') ? 1 : 0,
            'is_bukan_pns'          => $this->request->getPost('check_pns') ? 1 : 0,
            'is_pakta_integritas'   => $this->request->getPost('check_pakta') ? 1 : 0,
            'is_pernyataan_ikh'     => $this->request->getPost('check_pengajuan') ? 1 : 0,
        ];

        if ($isEdit) {
            $this->ikhModel->update($idIkh, $dataText);
            $pesan = "Data diri berhasil diperbarui.";
        } else {
            // Beri status awal khusus agar tahu ini masih draft (baru isi data diri, file belum lengkap)
            $dataText['status_validasi_admin'] = 'draft';
            $this->ikhModel->insert($dataText);
            $pesan = "Data diri berhasil disimpan. Silakan lanjutkan mengunggah dokumen.";
        }

        return redirect()->to('sw-siswa/ikh?tab=lampiran')->with('success', $pesan);
    }

    public function uploadFileAjax()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $idSiswa   = session('id');
        $namaInput = $this->request->getPost('input_name');
        $idIkh     = $this->request->getPost('id_ikh');

        // Ambil data siswa untuk nama folder
        $dataSiswa = $this->siswaModel->find($idSiswa);
        if (!$dataSiswa) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data siswa tidak ditemukan.']);
        }

        $namaSiswa = $dataSiswa['nama_siswa'];
        $noInduk   = $dataSiswa['no_induk_siswa'];
        $folderSiswaName = strtoupper($namaSiswa) . "_" . $noInduk;

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

                // =========================================================================
                // KEMBALIKAN FUNGSI PENGECEKAN KELENGKAPAN FILE DI SINI
                // =========================================================================
                $isComplete = $this->check_all_files_uploaded($idIkh);

                return $this->response->setJSON([
                    'success'     => true,
                    'message'     => "Berhasil upload ke folder $folderSiswaName",
                    'is_complete' => $isComplete, // Parameter penting agar JS memicu auto-reload
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

    private function check_all_files_uploaded($idIkh)
    {
        $data = $this->ikhModel->find($idIkh);
        
        if ($data) {
            $requiredFiles = ['file_ktp', 'file_npwp', 'file_kk', 'file_foto', 'file_ijazah', 'file_spt', 'file_sertifikat', 'file_ttd'];
            $isComplete = true;

            foreach ($requiredFiles as $file) {
                // PERBAIKAN: Deteksi otomatis format Array atau Object
                $nilaiFile = is_array($data) ? ($data[$file] ?? null) : ($data->$file ?? null);
                
                if (empty($nilaiFile)) {
                    $isComplete = false;
                    break;
                }
            }

            // PERBAIKAN: Ambil status validasi saat ini dengan aman
            $statusValidasi = is_array($data) ? ($data['status_validasi_admin'] ?? null) : ($data->status_validasi_admin ?? null);

            // Jika ke-10 file terisi dan status masih draft, otomatis ubah ke pending (siap diperiksa admin)
            if ($isComplete && $statusValidasi === 'draft') {
                $this->ikhModel->update($idIkh, ['status_validasi_admin' => 'pending']);
                send_notif(
                    '1',
                    'Pesan baru: ' . session()->get('nama'),
                    'Pengajuan IKH siap diperiksa',
                    base_url('sw-admin/ikh')
                );
            }else{
                $isComplete = false;
            }
            
            return $isComplete; // Kembalikan true jika lengkap
        }
        
        return false;
    }

    public function perbaikan($id)
    {
        $idIkh = decrypt_url($id);
        $ikh = $this->ikhModel->where('id_siswa', session('id'))->first();

        if (!$ikh || $ikh['id_ikh'] != $idIkh) {
            return redirect()->to('sw-siswa/ikh')->with('error', 'Data IKH tidak ditemukan.');
        }

        $this->ikhModel->update(
            $idIkh,
            [
                'status_validasi_admin' => 'pending',
                'status_sertifikat'     => 'belum',
                'tgl_aktif'             => null,
                'tgl_exp'               => null,
                'file_kartu_ikh'        => ''
            ],
        );
        send_notif(
            '1',
            'Pesan baru: ' . session()->get('nama'),
            'Perbaikan berkas IKH siap diperiksa',
            base_url('sw-admin/ikh')
        );
        return redirect()->to('sw-siswa/ikh')->with('success', 'Perbaikan berhasil dikirim. Pengajuan Anda akan segera diperiksa kembali.');
    }

    public function perpanjang($id)
    {
        $idIkh = decrypt_url($id);
        $ikh = $this->ikhModel->where('id_siswa', session('id'))->first();

        if (!$ikh || $ikh['id_ikh'] != $idIkh) {
            return redirect()->to('sw-siswa/ikh')->with('error', 'Data IKH tidak ditemukan.');
        }

        $this->ikhModel->update($idIkh, ['status_validasi_admin' => 'revisi']);
        send_notif(
            '1',
            'Pesan baru: ' . session()->get('nama'),
            'Perpanjang Izik Kuasa Hukum',
            base_url('sw-admin/ikh')
        );
        return redirect()->to('sw-siswa/ikh')->with('success', 'Silahkan untuk melengkapi data anda, untuk memperpanjang Izin Kuasa Hukum anda.');
    }
}

<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use Google\Client;
use Google\Service\Drive;
use App\Libraries\Pdf;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class IKHController extends BaseController
{
    protected $ikhModel;
    protected $siswaModel;
    protected $data;
    protected $ujianModel;
    protected $ujianMasterModel;
    public function __construct()
    {
        $this->ikhModel = new \App\Models\IkhModel();
        $this->siswaModel = new \App\Models\SiswaModel();
        $this->ujianModel = new \App\Models\UjianModel();
        $this->ujianMasterModel = new \App\Models\UjianMasterModel();
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
        $riwayat = $this->request->getVar('riwayat_pekerjaan'); // Ini akan menjadi array
        if (!is_array($riwayat)) {
            $riwayat = [];
        }
        $riwayat_bersih = array_values(array_filter($riwayat, function($value) {
            return !empty(trim($value));
        }));

        // 4. Encode menjadi format JSON
        $json_riwayat = json_encode($riwayat_bersih);


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
            'riwayat_pekerjaan'     => $json_riwayat,
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

    // =====================================================================
    // FUNGSI BARU: GENERATE SERTIFIKAT & UPLOAD KE GOOGLE DRIVE (VIA AJAX)
    // =====================================================================
    public function generateSertifikatDrive()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $idSiswa = session('id');
        $idIkh   = $this->request->getPost('id_ikh');

        // 1. DATA PREPARATION
        $hasilUjian = $this->ujianModel->getByIdsiswaSertifikat($idSiswa);
        $siswa = $this->siswaModel->where('id_siswa', $idSiswa)->get()->getRowObject();

        if (!$hasilUjian || !$siswa) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Anda belum lulus atau belum memiliki sertifikat brevet AB di sistem.',
                'csrf_hash' => csrf_hash()
            ]);
        }

        // Kalkulasi Nilai & Tanggal
        $totalNilaiUjian = 0;
        $countMateri = count($hasilUjian);
        $tgl_awal = null;
        $tgl_akhir = null;

        foreach ($hasilUjian as $row) {
            $totalNilaiUjian += $row->nilai_ujian;
            $currentStart = strtotime($row->start_ujian);
            $currentEnd = strtotime($row->end_ujian);
            if (!$tgl_awal || $currentStart < $tgl_awal) $tgl_awal = $currentStart;
            if (!$tgl_akhir || $currentEnd > $tgl_akhir) $tgl_akhir = $currentEnd;
        }

        $hasilTotal = ($countMateri > 0) ? round($totalNilaiUjian / $countMateri) : 0;
        $predikat = $this->_getPredikat($hasilTotal); 

        // 2. PDF INITIALIZATION
        new Pdf();
        $pdf = new \setasign\Fpdi\Fpdi();
        $pdf->SetCompression(true); // Kompresi Aktif
        $pdf->SetAutoPageBreak(false, 0);
        $bulanNomor = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $bulanIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $strTglAkhir = date('d', $tgl_akhir) . ' ' . $bulanIndo[(int)date('m', $tgl_akhir)] . ' ' . date('Y', $tgl_akhir);
        $noSertifikat = "{$hasilUjian[0]->id_ujian}/ALC-BREVET-AB/{$bulanNomor[(int)date('m',$tgl_akhir)]}/" . date('Y', $tgl_akhir);

        // 3. GENERATE QR CODE
        $writer = new PngWriter();
        $qrCode = QrCode::create(base_url('detail/data_ab/' . encrypt_url($idSiswa)))->setSize(300)->setMargin(0);
        $logoQr = Logo::create(FCPATH . 'assets/img/logo-brevet.png')->setResizeToWidth(60);
        $qrResult = $writer->write($qrCode, $logoQr);
        $qrUri = $qrResult->getDataUri();

        // PAGE 1: SERTIFIKAT UTAMA
        $pdf->AddPage('L');
        $pdf->Image(FCPATH . 'uploads/sertifikat/brevet-ab.jpg', 0, 0, 297, 210);
        $pdf->SetTextColor(51, 49, 49);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY(28, 12);
        $pdf->Cell(100, 5, "Izin Operasional LKP: 500.16.7.2/0003/SPNF-LKP/IV.7/I/2025", 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->SetXY(28, 70);
        $pdf->Cell(0, 5, "Nomor : " . $noSertifikat, 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 24);
        $pdf->SetXY(28, 118);
        $pdf->Cell(0, 15, strtoupper($siswa->nama_siswa), 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(28, 134);
        $pdf->Cell(0, 10, "NIP : " . $siswa->no_induk_siswa, 0, 1, 'L');
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetXY(28, 150);
        $pdf->Cell(0, 8, "Dinyatakan LULUS dengan nilai " . $hasilTotal, 0, 1, 'L');
        $pdf->SetX(28);
        $pdf->Cell(0, 8, "Predikat kelulusan " . $predikat['huruf'] . " ({$predikat['teks']})", 0, 1, 'L');
        $pdf->SetX(28);
        $pdf->Cell(0, 8, "Pada tanggal " . $strTglAkhir, 0, 1, 'L');
        $pdf->Image($qrUri, 30, 175, 28, 28, 'png');

        // PAGE 2: TRANSKRIP NILAI
        $pdf->AddPage('L');
        $pdf->Image(FCPATH . 'uploads/sertifikat/brevet-ab-2.jpg', 0, 0, 297, 210);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(55, 57);
        $pdf->Cell(0, 5, strtoupper($siswa->nama_siswa), 0, 1, 'L');
        $pdf->SetXY(25, 65);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(15, 6, 'No', 1, 0, 'C');
        $pdf->Cell(140, 6, 'Materi Pelatihan', 1, 0, 'C');
        $pdf->Cell(75, 6, 'Nilai', 1, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $no = 1;
        foreach ($hasilUjian as $row) {
            $pdf->SetX(25);
            $pdf->Cell(15, 6, $no++, 1, 0, 'C');
            $pdf->Cell(140, 6, $row->nama_mapel, 1, 0, 'L');
            $pdf->Cell(75, 6, $row->nilai_ujian, 1, 1, 'C');
        }
        $pdf->SetX(25);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(155, 6, 'NILAI RATA-RATA', 1, 0, 'C');
        $pdf->Cell(75, 6, $hasilTotal, 1, 1, 'C');
        $pdf->Image($qrUri, 240, 145, 25, 25, 'png');
        $pdf->SetXY(138, 174);
        $pdf->Cell(0, 5, $strTglAkhir, 0, 1, 'L');

        // =====================================================================
        // 4. PROSES UPLOAD KE GOOGLE DRIVE DARI STRING PDF
        // =====================================================================
        try {
            // "S" merender PDF menjadi string di dalam RAM (tidak didownload ke browser)
            $pdfStringData = $pdf->Output('', 'S'); 

            // Panggil Service Google Drive (Pastikan fungsi getDriveService() ada di controller ini)
            $service = $this->getDriveService(); 
            
            // Format folder siswa
            $namaArray = explode(' ', $siswa->nama_siswa);
            $duaKataPertama = array_slice($namaArray, 0, 2);
            $namaDepan = implode('_', $duaKataPertama);
            $folderSiswaName = strtoupper($namaDepan) . "_" . $siswa->no_induk_siswa;
            
            // Dapatkan ID Folder Siswa (Pastikan fungsi getOrCreateFolder() ada di controller ini)
            $folderSiswaId = $this->getOrCreateFolder($service, $folderSiswaName, setting('folder_id_drive'));

            // Hapus file lama di Drive jika sudah ada
            $dataLama = $this->ikhModel->find($idIkh);
            $fileIdLama = is_array($dataLama) ? ($dataLama['file_sertifikat'] ?? null) : ($dataLama->file_sertifikat ?? null);
            if ($fileIdLama && strpos($fileIdLama, '.') === false) {
                try {
                    $service->files->delete($fileIdLama);
                } catch (\Exception $e) {}
            }

            // Upload PDF ke Drive
            $fileName = "SERTIFIKAT_SISTEM_" . $siswa->no_induk_siswa . "_" . time() . ".pdf";
            $fileMetadata = new \Google\Service\Drive\DriveFile([
                'name' => $fileName,
                'parents' => [$folderSiswaId]
            ]);

            $uploadedFile = $service->files->create($fileMetadata, [
                'data'       => $pdfStringData, // Data diambil dari string PDF
                'mimeType'   => 'application/pdf',
                'uploadType' => 'multipart',
                'fields'     => 'id'
            ]);

            // Update Database IKH dengan ID Drive yang baru
            $this->ikhModel->update($idIkh, ['file_sertifikat' => $uploadedFile->id]);

            return $this->response->setJSON([
                'success'   => true,
                'message'   => "Sertifikat berhasil dibuat & diunggah ke sistem.",
                'file_url'  => 'https://drive.google.com/file/d/' . $uploadedFile->id . '/preview',
                'csrf_hash' => csrf_hash()
            ]);

        } catch (\Exception $e) {
            log_message('error', '[Upload Drive Error] ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Gagal mengunggah ke Google Drive. Coba lagi nanti.',
                'csrf_hash' => csrf_hash()
            ]);
        }
    }
    private function _getPredikat($nilai)
    {
        if ($nilai < 60) return ['huruf' => 'D', 'teks' => 'Kurang'];
        if ($nilai < 70) return ['huruf' => 'C', 'teks' => 'Cukup'];
        if ($nilai < 80) return ['huruf' => 'B', 'teks' => 'Cukup Baik'];
        if ($nilai < 90) return ['huruf' => 'A', 'teks' => 'Baik'];
        return ['huruf' => 'A+', 'teks' => 'Sangat Baik'];
    }
}

<?php
namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

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
        $ikh = $this->ikhModel->where('id_siswa', session('id'))->first();
        $siswa = $this->siswaModel->where('id_siswa', session('id'))->first();

        $this->data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-siswa')],
            ['title' => 'Sertifikasi IKH', 'url' => base_url('sw-siswa/ikh')],
        ];

        if(empty($ikh)){
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


    // FUNGSI 2: Upload File secara AJAX (Satu per satu)
    public function uploadFileAjax()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Akses ditolak.']);
        }

        $idSiswa   = session('id');
        $namaInput = $this->request->getPost('input_name'); // e.g., 'file_ktp'
        $idIkh     = $this->request->getPost('id_ikh');     // ID dari tabel pendaftaran_ikh

        if (empty($idIkh)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Simpan data diri terlebih dahulu.']);
        }

        $file = $this->request->getFile('file_dokumen');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            
            // 1. Validasi Ukuran (Maks 2 MB = 2097152 bytes)
            if ($file->getSize() > 2097152) {
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal: Ukuran file melebihi 2 MB.', 'csrf_hash' => csrf_hash()]);
            }

            // 2. Validasi Ekstensi PDF
            $ext = strtolower($file->getClientExtension());
            $allowPdfOnly = ['file_ktp', 'file_npwp', 'file_kk', '','file_skck', 'file_ijazah', 'file_spt', 'file_sertifikat'];
            
            if (in_array($namaInput, $allowPdfOnly) && $ext !== 'pdf') {
                return $this->response->setJSON(['success' => false, 'message' => 'File ini HANYA BOLEH berformat PDF.', 'csrf_hash' => csrf_hash()]);
            }

            $folderName = str_replace('file_', '', $namaInput);
            $basePath   = FCPATH . 'uploads/ikh/' . $folderName . '/';

            if (!is_dir($basePath)) {
                mkdir($basePath, 0755, true);
            }

            // =========================================================================
            // PERBAIKAN: HAPUS FILE LAMA JIKA ADA (Replace)
            // =========================================================================
            $dataLama = $this->ikhModel->find($idIkh);
            
            // Deteksi otomatis apakah CI4 mengembalikan Array atau Object
            $namaFileLama = is_array($dataLama) ? ($dataLama[$namaInput] ?? null) : ($dataLama->$namaInput ?? null);

            if (!empty($namaFileLama)) {
                $pathFileLama = FCPATH . 'uploads/ikh/' . $namaFileLama;
                
                // Pastikan file tersebut benar-benar ada di folder sebelum dihapus
                if (file_exists($pathFileLama) && is_file($pathFileLama)) {
                    unlink($pathFileLama); // Hapus file fisik dari server
                }
            }
            // =========================================================================
            $newName = strtoupper($folderName) . '_' . $idSiswa . '_' . $file->getRandomName();
            
            try {
                $file->move($basePath, $newName);
                $dbPath = $folderName . '/' . $newName;
                
                // Update nama file baru ke database
                $this->ikhModel->update($idIkh, [$namaInput => $dbPath]);

                // =========================================================================
                // PERBAIKAN: CEK KELENGKAPAN SEMUA FILE UNTUK AUTO-RELOAD
                // =========================================================================
                $isComplete = $this->check_all_files_uploaded($idIkh);

                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Upload berhasil!',
                    'is_complete' => $isComplete, // Beritahu JS apakah sudah lengkap
                    'csrf_hash' => csrf_hash()
                ]);

            } catch (\Exception $e) {
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal memindahkan file.', 'csrf_hash' => csrf_hash()]);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'File rusak atau tidak valid.', 'csrf_hash' => csrf_hash()]);
    }

    // Fungsi internal (Diubah sedikit agar mengembalikan nilai true/false)
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

        $this->ikhModel->update($idIkh, 
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
<?php
namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class PerijinanIKHController extends BaseController
{
    protected $ikhModel;
    protected $data;
    public function __construct()
    {
        $this->ikhModel = new \App\Models\IkhModel();
        $this->data = [];
    }

    public function index()
    {
        $ikh = $this->ikhModel->where('id_siswa', session('id'))->first();

        $this->data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-siswa')],
            ['title' => 'Sertifikasi IKH', 'url' => base_url('sw-siswa/perijinan-ikh')],
        ];

        $this->data['ikh'] = $ikh;
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
            'no_wa'                 => $this->request->getPost('no_wa'),
            'email'                 => $this->request->getPost('email'),
            'kategori_kantor'       => $this->request->getPost('kategori_kantor'),
            'nama_kantor'           => $this->request->getPost('nama_kantor'),
            'alamat_ktp'            => $this->request->getPost('alamat_ktp'),
            'alamat_korespondensi'  => $this->request->getPost('alamat_korespondensi'),
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

        return redirect()->to('sw-siswa/perijinan-ikh?tab=lampiran')->with('success', $pesan);
    }


    // FUNGSI 2: Upload File secara AJAX (Satu per satu)
    public function uploadFileAjax()
    {
        // Pastikan hanya request AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Akses ditolak.']);
        }

        $idSiswa   = session('id');
        $namaInput = $this->request->getPost('input_name'); // e.g., 'file_ktp'
        $idIkh     = $this->request->getPost('id_ikh');     // ID dari tabel pendaftaran_ikh

        if (empty($idIkh)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Simpan data diri terlebih dahulu sebelum mengunggah file.']);
        }

        $file = $this->request->getFile('file_dokumen');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            
            // Validasi Ekstensi berdasarkan nama input
            $ext = strtolower($file->getClientExtension());
            $allowPdfOnly = ['file_skck', 'file_ijazah', 'file_spt', 'file_cv', 'file_sertifikat', 'file_ttd'];
            
            if (in_array($namaInput, $allowPdfOnly) && $ext !== 'pdf') {
                return $this->response->setJSON(['success' => false, 'message' => 'File ini HANYA BOLEH berformat PDF.']);
            }

            // Ambil nama folder ('file_ktp' -> 'ktp')
            $folderName = str_replace('file_', '', $namaInput);
            $basePath   = FCPATH . 'uploads/ikh/' . $folderName . '/';

            if (!is_dir($basePath)) {
                mkdir($basePath, 0755, true);
            }

            $newName = strtoupper($folderName) . '_' . $idSiswa . '_' . $file->getRandomName();
            
            // Simpan file
            try {
                $file->move($basePath, $newName);
                
                // Update ke database
                $dbPath = $folderName . '/' . $newName;
                $this->ikhModel->update($idIkh, [$namaInput => $dbPath]);

                // Cek apakah semua file sudah terisi (opsional: untuk mengubah status dari draft ke pending validasi)
                $this->check_all_files_uploaded($idIkh);

                return $this->response->setJSON(['success' => true, 'message' => 'Upload berhasil!']);

            } catch (\Exception $e) {
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal memindahkan file ke server.']);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'File rusak atau terlalu besar.']);
    }

    // Fungsi internal untuk merubah status jika ke-10 file sudah lengkap
    private function check_all_files_uploaded($idIkh)
    {
        $data = $this->ikhModel->find($idIkh);
        if ($data) {
            $requiredFiles = ['file_ktp', 'file_npwp', 'file_kk', 'file_foto', 'file_skck', 'file_ijazah', 'file_spt', 'file_cv', 'file_sertifikat', 'file_ttd'];
            $isComplete = true;

            foreach ($requiredFiles as $file) {
                if (empty($data->$file)) {
                    $isComplete = false;
                    break;
                }
            }

            // Jika semua 10 field file tidak kosong dan statusnya masih draft, ubah jadi siap divalidasi admin
            if ($isComplete && $data->status_validasi_admin == 'draft') {
                $this->ikhModel->update($idIkh, ['status_validasi_admin' => 'pending']);
            }
        }
    }
}
<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class UjianController extends BaseController
{
    protected $siswaModel;
    protected $ujianModel;
    protected $ujianMasterModel;
    protected $ujianDetailModel;
    protected $ujianSiswaModel;
    public function __construct()
    {
        $this->siswaModel = new \App\Models\SiswaModel();
        $this->ujianModel = new \App\Models\UjianModel();
        $this->ujianMasterModel = new \App\Models\UjianMasterModel();
        $this->ujianDetailModel = new \App\Models\UjianDetailModel();
        $this->ujianSiswaModel = new \App\Models\UjianSiswaModel();
    }
    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-siswa')],
            ['title' => 'Ujian', 'url' => '#'],
        ];
        $data['siswa'] = $this->siswaModel->asObject()->find(session()->get('id'));

        $siswa = $this->siswaModel
            ->select('siswa.*, kelas.id_kelas, kelas.nama_kelas')
            ->join('siswa_kelas', 'siswa_kelas.id_siswa = siswa.id_siswa', 'left')
            ->join('kelas', 'kelas.id_kelas = siswa.kelas OR kelas.id_kelas = siswa_kelas.id_kelas', 'left')
            ->where('siswa.email', session()->get('email'))
            ->groupBy('kelas.id_kelas')
            ->asObject()
            ->get()
            ->getResultObject();

        $data['ujian'] = array();
        foreach ($siswa as  $r) {
            $tugas = $this->ujianModel->getAllByKelas($r->id_kelas, $r->id_siswa);

            foreach ($tugas as $t) {
                // PERUBAHAN DI SINI: Kelompokkan ujian berdasarkan nama kelas
                $data['ujian'][$r->nama_kelas][] = $t;
            }

            $dataUjian = $this->ujianMasterModel->where('kelas', $r->id_kelas)->groupBy('mapel')->get()->getResultObject();
            $total = 0;
            foreach ($dataUjian as $rr) {
                $total++;
            }

            $totalUjian = $this->ujianModel->where('kelas', $r->id_kelas)->where('id_siswa', $r->id_siswa)
                ->where('ujian.nilai >=', 60)
                ->groupBy('ujian.mapel')->get()->getResultObject();
            $totalSertifikat = 0;
            foreach ($totalUjian as $r) {
                $totalSertifikat++;
            }

            $data['totalSertifikat'] = $totalSertifikat;
            $data['total'] = $total;
        }

        return view('siswa/ujian/list', $data);
    }

    // Contoh logic di Controller
    public function prosesVerifikasi()
    {
        if ($this->request->isAJAX()) {
            $idujian_post = $this->request->getPost('idujian');
            $id_ujian = decrypt_url($idujian_post);
            $img = $this->request->getPost('face_image');
            $deviceTime = $this->request->getPost('device_time');
            $url = $this->request->getPost('url');

            // 1. Ambil data lama untuk cek file verifikasi sebelumnya
            $ujianLama = $this->db->table('ujian')
                ->select('verifikasi')
                ->where('id_ujian', $id_ujian)
                ->get()->getRow();

            // Persiapan folder
            $directory = FCPATH . 'uploads/verifikasi/';
            if (!is_dir($directory)) mkdir($directory, 0777, true);

            // Persiapan file baru
            $img = str_replace(['data:image/jpeg;base64,', ' '], ['', '+'], $img);
            $data = base64_decode($img);
            $fileName = 'verif_' . session()->get('id') . '_' . time() . '.jpg';

            // 2. Mulai proses simpan gambar baru
            if (file_put_contents($directory . $fileName, $data)) {

                // 3. HAPUS GAMBAR LAMA (Jika ada file lama di folder)
                if (!empty($ujianLama->verifikasi)) {
                    $oldFilePath = $directory . $ujianLama->verifikasi;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath); // Menghapus file lama dari server
                    }
                }

                // 4. Update tabel ujian dengan nama file baru
                $update = $this->db->table('ujian')
                    ->where('id_ujian', $id_ujian)
                    ->update(['verifikasi' => $fileName]);

                if ($update) {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'csrf_hash' => csrf_hash(),
                        'redirect' => $url . '?device_time=' . $deviceTime
                    ]);
                } else {
                    // Jika DB gagal, hapus file baru yang baru saja diupload agar tidak sampah
                    unlink($directory . $fileName);
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Gagal memperbarui data di database.',
                        'csrf_hash' => csrf_hash()
                    ]);
                }
            }

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal mengunggah gambar verifikasi.',
                'csrf_hash' => csrf_hash()
            ]);
        }
    }

    public function lihatPg($kode_ujian, $id_siswa, $id_ujian)
    {
        $data['breadcrumbs'] = [
            ['title' => 'Ujian', 'url' => base_url('sw-siswa/ujian')],
            ['title' => 'List Ujian', 'url' => '#'],
        ];
        $idsiswa = decrypt_url($id_siswa);
        // Ambil waktu dari device (jika dikirim via GET), jika tidak pakai waktu server
        $deviceTimeRequest = $this->request->getGet('device_time');
        $now = (!empty($deviceTimeRequest)) ? date('Y-m-d H:i', strtotime($deviceTimeRequest)) : date('Y-m-d H:i');


        $dataUjian = $this->ujianModel->where('id_ujian', decrypt_url($id_ujian))->where('id_siswa', $idsiswa)->where('status', 'B')->get()->getRowObject();

        if (!empty($dataUjian)) {
            $this->ujianSiswaModel->where('ujian', decrypt_url($kode_ujian))->where('siswa', $idsiswa)->delete();
            // Ambil soal acak berdasarkan level jika ada pengaturan jumlah soal
            $ujian_detail = $this->ujianDetailModel->getSoalAcakBerdasarkanLevel(
                decrypt_url($kode_ujian),
                (int) $dataUjian->jml_mudah,
                (int) $dataUjian->jml_sedang,
                (int) $dataUjian->jml_susah
            );
            $total = count($ujian_detail);
            $totalMenit = $total * $dataUjian->waktu_per_soal; // Menggunakan waktu per soal dari database
            // Logic End Ujian berdasarkan waktu device/lokal yang sudah ditentukan di atas
            $endUjian = date('Y-m-d H:i', strtotime($now . " + $totalMenit minutes"));
            $kuota = $dataUjian->kuota - 1;

            $data_ujian_siswa = [];
            $id_siswa_decrypted = decrypt_url($id_siswa);

            foreach ($ujian_detail as $uj) {
                $data_ujian_siswa[] = [
                    'ujian_id' => $uj->id_detail_ujian,
                    'ujian'    => $uj->kode_ujian,
                    'siswa'    => $id_siswa_decrypted,
                ];
            }

            // 4. Pastikan data array tidak kosong sebelum insert
            if (!empty($data_ujian_siswa)) {
                $this->ujianSiswaModel->insertBatch($data_ujian_siswa, 100);
            }

            $this->ujianModel
                ->set('start_ujian', $now) // Menggunakan waktu lokal device
                ->set('end_ujian', $endUjian) // Menggunakan waktu lokal device + durasi
                ->set('status', 'U')
                ->set('kuota', $kuota)
                ->where('id_ujian', decrypt_url($id_ujian))
                ->update();
        }

        // ... Sisa kode (data view) tetap sama ...
        $data['siswa'] = $this->siswaModel->asObject()->find($idsiswa);
        $data['ujian'] = $this->ujianModel->getBykode(decrypt_url($kode_ujian), decrypt_url($id_ujian));
        $data['detail_ujian'] = $this->ujianSiswaModel->getAllBykodeUjianSiswa(decrypt_url($kode_ujian), $idsiswa);
        $data['ujian_siswa'] = $this->ujianSiswaModel
            ->where('ujian', decrypt_url($kode_ujian))
            ->where('siswa', decrypt_url($id_siswa))
            ->get()->getResultObject();

        $data['jawaban_benar'] = $this->ujianSiswaModel->benar(decrypt_url($kode_ujian), decrypt_url($id_siswa), 1);
        $data['jawaban_salah'] = $this->ujianSiswaModel->salah(decrypt_url($kode_ujian), decrypt_url($id_siswa), 0);
        $data['tidak_dijawab'] = $this->ujianSiswaModel->belum_terjawab(decrypt_url($kode_ujian), decrypt_url($id_siswa), null);
        $data['sedang'] = true;

        return view('siswa/ujian/pg-lihat', $data);
    }

    public function kirimUjian()
    {
        if ($this->request->isAJAX()) {

            $db = \Config\Database::connect();
            $db->transBegin();

            try {
                $id_siswa        = $this->request->getVar('id_siswa');
                $id_detail_ujian = $this->request->getVar('id_detail_ujian');
                $jawaban         = $this->request->getVar('jawaban');
                $jam             = $this->request->getVar('jam');

                // 1. CEGAH FATAL ERROR: Pastikan soal ditemukan
                $du = $this->ujianDetailModel->getAllByiddetailujian($id_detail_ujian);
                if (!$du) {
                    throw new \Exception('Data soal tidak ditemukan atau tidak valid.');
                }

                // 2. TENTUKAN STATUS JAWABAN (0=Salah, 1=Benar, 2=Kosong/Ragu)
                $status_benar = 0; // Default: Salah

                if ($jawaban == NULL || $jawaban == '') {
                    $status_benar = 2; // Kosong
                } elseif ($jawaban == $du->jawaban) {
                    $status_benar = 1; // Benar
                }

                // 3. EKSEKUSI DATABASE (Cukup 1 kali penulisan, tidak perlu diulang-ulang)
                // Asumsi: Baris data ujian siswa sudah di-generate (insertBatch) di awal ujian
                $this->ujianSiswaModel
                    ->set('jawaban', $jawaban)
                    ->set('benar', $status_benar)
                    ->set('jam', $jam)
                    ->where('ujian_id', $id_detail_ujian)
                    ->where('siswa', $id_siswa)
                    ->update();

                // 4. COMMIT & RESPONSE
                if ($db->transStatus() === false) {
                    $db->transRollback();
                    return $this->response->setJSON([
                        'status' => false,
                        'error'  => 'Jawaban gagal disimpan ke database.'
                    ]);
                } else {
                    $db->transCommit();
                    return $this->response->setJSON([
                        'status' => true,
                        'pesan'  => 'Jawaban berhasil disimpan.'
                    ]);
                }
            } catch (\Throwable $e) {
                $db->transRollback();
                return $this->response->setJSON([
                    'status' => false,
                    'error'  => $e->getMessage() // Akan menangkap error dari throw Exception di atas
                ]);
            }
        }
    }

    public function kirimUjianSelesai()
    {
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $kode_ujian = $this->request->getVar('kode_ujian');
            $id_ujian   = $this->request->getVar('id_ujian');
            $waktu_selesai_client = $this->request->getVar('waktu_selesai_client');
            $id_siswa   = session('id');

            // 1. Update status di tabel ujianSiswaModel (detail jawaban siswa)
            $this->ujianSiswaModel
                ->set('status', 'selesai')
                ->set('date_send', time())
                ->where('ujian', $kode_ujian)
                ->where('siswa', $id_siswa)
                ->update();

            // 2. Ambil data siswa (Gunakan getRowObject karena 1 email = 1 siswa)
            $siswa = $this->siswaModel
                ->where('email', session()->get('email'))
                ->get()
                ->getRowObject();

            if ($siswa) { // Pastikan data siswa ada
                // 3. Ambil data ujian untuk kalkulasi nilai
                $ujian = $this->ujianMasterModel
                    ->getAllUntukNilaiUjian($siswa->id_siswa, $kode_ujian);

                foreach ($ujian as $u) {
                    $kode_ujian_loop = $u->kode_ujian;
                    $jumlah_benar    = $u->benar ?? 0;

                    // Cek jumlah soal yang ada di tabel siswa
                    $ujian_detail = $this->ujianSiswaModel
                        ->where('ujian', $kode_ujian_loop)
                        ->where('siswa', $id_siswa)
                        ->get()
                        ->getResultObject();

                    $total_soal = count($ujian_detail);

                    // CEGAH ERROR "DIVISION BY ZERO"
                    if ($total_soal > 0) {
                        $nilai = round(($jumlah_benar / $total_soal) * 100);
                    } else {
                        $nilai = 0;
                    }

                    // 4. UPDATE KE TABEL ujianModel DENGAN BENAR DAN AMAN
                    $this->ujianModel
                        ->set('status', 'S')
                        ->set('nilai', $nilai)
                        ->set('updated_at', $waktu_selesai_client)
                        ->where('kode_ujian', $kode_ujian_loop) // MENGGUNAKAN VARIABEL LOOP!
                        ->where('id_ujian', $id_ujian)
                        ->where('id_siswa', $id_siswa) // SANGAT PENTING: Agar tidak menimpa nilai siswa lain!
                        ->update();
                }
            }

            // 5. Finalisasi Transaksi
            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->to('sw-siswa/ujian')->with('pesan', 'Gagal menyimpan ujian. Pastikan koneksi internet stabil.');
            } else {
                $db->transCommit();
                return redirect()->to('sw-siswa/ujian')->with('success', 'Ujian telah dikerjakan');
            }
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->to('sw-siswa/ujian')->with('pesan', $e->getMessage());
        }
    }

    public function remedial($id, $kode, $status)
    {
        $db = \Config\Database::connect();

        try {
            $db->transBegin();

            $id_ujian = decrypt_url($id);
            $kode_ujian = decrypt_url($kode);
            $idsiswa = session('id');

            $dataUjian = $this->ujianModel->where('id_ujian', $id_ujian)->where('id_siswa', $idsiswa)->get()->getRowObject();
            if (!empty($dataUjian)) {
                $this->ujianSiswaModel->where('ujian', $kode_ujian)->where('siswa', $idsiswa)->delete();
                // Ambil soal acak berdasarkan level jika ada pengaturan jumlah soal
                $ujian_detail = $this->ujianDetailModel->getSoalAcakBerdasarkanLevel(
                    decrypt_url($kode_ujian),
                    (int) $dataUjian->jml_mudah,
                    (int) $dataUjian->jml_sedang,
                    (int) $dataUjian->jml_susah
                );

                $data_ujian_siswa = [];

                foreach ($ujian_detail as $uj) {
                    $data_ujian_siswa[] = [
                        'ujian_id' => $uj->id_detail_ujian,
                        'ujian'    => $kode_ujian,
                        'siswa'    => $idsiswa,
                    ];
                }

                // 4. Pastikan data array tidak kosong sebelum insert
                if (!empty($data_ujian_siswa)) {
                    $this->ujianSiswaModel->insertBatch($data_ujian_siswa, 100);
                }

                // AMBIL WAKTU DARI PERANGKAT USER (Jika dikirim via POST)
                // Jika tidak ada, fallback ke waktu server (sebagai pengaman)
                $userTimestamp = $this->request->getPost('device_time') ?: time();
                $total = count($ujian_detail);
                $kuota = $dataUjian->kuota - 1;
                $totalMenit = $total * $dataUjian->waktu_per_soal; // Menggunakan waktu per soal dari database

                // FORMULASI WAKTU BERDASARKAN DEVICE USER
                $startTime = date('Y-m-d H:i:s', $userTimestamp);
                $endTime   = date('Y-m-d H:i:s', $userTimestamp + ($totalMenit * 60));

                $this->ujianModel
                    ->set('date_created', $userTimestamp) // Menggunakan timestamp user
                    ->set('start_ujian', $startTime)
                    ->set('end_ujian', $endTime)
                    ->set('status', 'U')
                    ->set('nilai', null)
                    ->set('kuota', $kuota)
                    ->where('id_ujian', $id_ujian)
                    ->update();
            }

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->to('sw-siswa/ujian')->with('error', 'Gagal memproses remedial.');
            }

            $db->transCommit();

            return redirect()->to('sw-siswa/ujian/lihat-pg/' . encrypt_url($kode_ujian) . '/' . encrypt_url($idsiswa) . '/' . encrypt_url($id_ujian) . '/' . $status)
                ->with('pesan', 'Ujian ulang telah diaktifkan menggunakan waktu perangkat Anda.');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->to('sw-siswa/ujian')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function otomatisKirimUjian()
    {
        $data = $this->ujianModel->where('status', 'U')->where('end_ujian <', date('Y-m-d H:i'))->get()->getResultObject();
        foreach ($data as $rows) {
            $this->ujianSiswaModel
                ->set('status', 'selesai')
                ->set('date_send', time())
                ->where('ujian', $rows->kode_ujian)
                ->where('siswa', $rows->id_siswa)
                ->update();

            $siswa = $this->siswaModel->where('id_siswa', $rows->id_siswa)->get()->getResultObject();

            $data['ujian'] = array();
            foreach ($siswa as  $r) {
                $ujian = $this->ujianMasterModel->getAllUntukNilaiUjian($r->id_siswa, $rows->kode_ujian);

                foreach ($ujian as $u) {
                    $data['ujian'][] = $u;
                }
            }

            for ($i = 0; $i < count($data['ujian']); $i++) {
                $ujian_detail = $this->ujianDetailModel
                    ->getAllByKodeUjianJumlah($data['ujian'][$i]->kode_ujian);
                $nilai = round($data['ujian'][$i]->benar / count($ujian_detail) * 100);
                $this->ujianModel
                    ->set('status', 'S')
                    ->set('nilai', $nilai)
                    ->set('updated_at', $rows->end_ujian)
                    ->where('kode_ujian', $rows->kode_ujian)
                    ->where('id_ujian', $rows->id_ujian)
                    ->update();
            }
        }
    }
}

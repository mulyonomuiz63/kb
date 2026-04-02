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

        $siswa = $this->siswaModel->where('email', session()->get('email'))->get()->getResultObject();
        $data['ujian'] = array();
        foreach ($siswa as  $r) {
            $tugas = $this->ujianModel->getAllByKelas($r->kelas, $r->id_siswa);

            foreach ($tugas as $t) {
                $data['ujian'][] = $t;
            }


            $dataUjian = $this->ujianMasterModel->where('kelas', $r->kelas)->groupBy('mapel')->get()->getResultObject();
            $total = 0;
            foreach ($dataUjian as $rr) {
                $total++;
            }


            $totalUjian = $this->ujianModel->where('kelas', $r->kelas)->where('id_siswa', $r->id_siswa)
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
        // Ambil waktu dari device (jika dikirim via GET), jika tidak pakai waktu server
        $deviceTimeRequest = $this->request->getGet('device_time');
        $now = (!empty($deviceTimeRequest)) ? date('Y-m-d H:i', strtotime($deviceTimeRequest)) : date('Y-m-d H:i');

        // Ujian berjalan
        $ujianDetail = $this->ujianDetailModel
            ->where('kode_ujian', decrypt_url($kode_ujian))
            ->get()->getResultObject();

        $total = 0;
        foreach ($ujianDetail as $dataRows) {
            $total++;
        }
        $totalMenit = $total * 3;

        // Logic End Ujian berdasarkan waktu device/lokal yang sudah ditentukan di atas
        $endUjian = date('Y-m-d H:i', strtotime($now . " + $totalMenit minutes"));

        $dat_u = $this->ujianModel->where('id_ujian', decrypt_url($id_ujian))->where('id_siswa', decrypt_url($id_siswa))->where('status', 'B')->get()->getResultObject();

        if (!empty($dat_u)) {
            $dataUjian = $this->ujianModel->where('id_ujian', decrypt_url($id_ujian))->get()->getRowObject();
            $kuota = $dataUjian->kuota - 1;

            $this->ujianModel
                ->set('start_ujian', $now) // Menggunakan waktu lokal device
                ->set('end_ujian', $endUjian) // Menggunakan waktu lokal device + durasi
                ->set('status', 'U')
                ->set('kuota', $kuota)
                ->where('id_ujian', decrypt_url($id_ujian))
                ->update();

            $data_ujian_model = $this->ujianSiswaModel->where('ujian', decrypt_url($kode_ujian))->where('siswa', decrypt_url($id_siswa))->get()->getResultObject();
            if (!empty($data_ujian_model)) {
                $data_ujian_siswa = $this->ujianSiswaModel->where('ujian', decrypt_url($kode_ujian))->where('siswa', decrypt_url($id_siswa))->get()->getResultObject();
                foreach ($data_ujian_siswa as $rows) {
                    $data_detail_siswa = [
                        'jawaban'       => null,
                        'benar'         => null,
                        'jam'           => null,
                        'status'        => null,
                    ];
                    $this->ujianSiswaModel->set($data_detail_siswa)->where('id_ujian_siswa', $rows->id_ujian_siswa)->update();
                }
            } else {
                $ujian_detail = $this->ujianDetailModel->getAllBykodeUjian(decrypt_url($kode_ujian));
                $data_ujian_siswa = [];
                foreach ($ujian_detail as $uj) {
                    array_push($data_ujian_siswa, [
                        'ujian_id' => $uj->id_detail_ujian,
                        'ujian' => $uj->kode_ujian,
                        'siswa' => decrypt_url($id_siswa),
                    ]);
                }
                $this->ujianSiswaModel->insertBatch($data_ujian_siswa);
            }
        }

        // ... Sisa kode (data view) tetap sama ...
        $data['siswa'] = $this->siswaModel->asObject()->find(session()->get('id'));
        $data['ujian'] = $this->ujianModel->getBykode(decrypt_url($kode_ujian), decrypt_url($id_ujian));
        $data['detail_ujian'] = $this->ujianDetailModel->getAllBykodeUjian(decrypt_url($kode_ujian));
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

                $id_siswa = $this->request->getVar('id_siswa');
                $id_detail_ujian = $this->request->getVar('id_detail_ujian');
                $jawaban = $this->request->getVar('jawaban');
                $jam = $this->request->getVar('jam');

                $du = $this->ujianDetailModel->getAllByiddetailujian($id_detail_ujian);

                $dataJawaban = $this->ujianSiswaModel->getByUjianSiswa($id_detail_ujian, $id_siswa);

                if (!empty($dataJawaban)) {

                    if ($jawaban == $du->jawaban) {

                        $this->ujianSiswaModel
                            ->set('jawaban', $jawaban)
                            ->set('benar', 1)
                            ->set('jam', $jam)
                            ->where('ujian_id', $id_detail_ujian)
                            ->where('siswa', $id_siswa)
                            ->update();
                    } else {

                        if ($jawaban == NULL) {

                            $this->ujianSiswaModel
                                ->set('jawaban', $jawaban)
                                ->set('benar', 2)
                                ->set('jam', $jam)
                                ->where('ujian_id', $id_detail_ujian)
                                ->where('siswa', $id_siswa)
                                ->update();
                        } else {

                            $this->ujianSiswaModel
                                ->set('jawaban', $jawaban)
                                ->set('benar', 0)
                                ->set('jam', $jam)
                                ->where('ujian_id', $id_detail_ujian)
                                ->where('siswa', $id_siswa)
                                ->update();
                        }
                    }
                } else {

                    if ($jawaban == $du->jawaban) {

                        $this->ujianSiswaModel
                            ->set('jawaban', $jawaban)
                            ->set('benar', 1)
                            ->where('ujian_id', $id_detail_ujian)
                            ->where('siswa', $id_siswa)
                            ->update();
                    } else {

                        if ($jawaban == NULL) {

                            $this->ujianSiswaModel
                                ->set('jawaban', $jawaban)
                                ->set('benar', 2)
                                ->where('ujian_id', $id_detail_ujian)
                                ->where('siswa', $id_siswa)
                                ->update();
                        } else {

                            $this->ujianSiswaModel
                                ->set('jawaban', $jawaban)
                                ->set('benar', 0)
                                ->where('ujian_id', $id_detail_ujian)
                                ->where('siswa', $id_siswa)
                                ->update();
                        }
                    }
                }

                if ($db->transStatus() === false) {
                    $db->transRollback();
                    return $this->response->setJSON([
                        'status' => false,
                        'error' => 'Gagal menyimpan data'
                    ]);
                } else {
                    $db->transCommit();
                    return $this->response->setJSON([
                        'status' => true,
                        'error' => 'Berhasil di simpan'
                    ]);
                }
            } catch (\Throwable $e) {

                $db->transRollback();


                return $this->response->setJSON([
                    'status' => false,
                    'error' => $e->getMessage()
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
            $id_ujian = $this->request->getVar('id_ujian');
            $id_siswa   = session('id');

            $this->ujianSiswaModel
                ->set('status', 'selesai')
                ->set('date_send', time())
                ->where('ujian', $kode_ujian)
                ->where('siswa', $id_siswa)
                ->update();

            $this->ujianModel
                ->set('status', 'S')
                ->set('nilai', 0)
                ->where('kode_ujian', $kode_ujian)
                ->where('id_ujian', $id_ujian)
                ->update();


            $siswa = $this->siswaModel
                ->where('email', session()->get('email'))
                ->get()
                ->getResultObject();

            $data['ujian'] = array();

            foreach ($siswa as $r) {

                $ujian = $this->ujianMasterModel
                    ->getAllUntukNilaiUjian($r->kelas, $r->id_siswa, $kode_ujian);

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
                    ->where('kode_ujian', $kode_ujian)
                    ->where('id_ujian', $id_ujian)
                    ->update();
            }


            if ($db->transStatus() === false) {

                $db->transRollback();
                return redirect()->to('sw-siswa/ujian')->with('pesan', 'Gagal menyimpan ujian, Pastikan koneksi internet stabil.');
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

            // AMBIL WAKTU DARI PERANGKAT USER (Jika dikirim via POST)
            // Jika tidak ada, fallback ke waktu server (sebagai pengaman)
            $userTimestamp = $this->request->getPost('device_time') ?: time();

            $dataUjian = $this->ujianModel->where('id_ujian', $id_ujian)->get()->getRowObject();
            $kuota = $dataUjian->kuota - 1;

            $data['ujian_siswa'] = $this->ujianSiswaModel
                ->where('ujian_siswa.ujian', $kode_ujian)
                ->where('ujian_siswa.siswa', $idsiswa)
                ->get()->getResultObject();

            $total = count($data['ujian_siswa']); // Lebih ringkas dibanding foreach $total++
            $totalMenit = $total * 3;

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

            // Update detail jawaban siswa
            $data_ujian_siswa = $this->ujianSiswaModel->where('ujian', $kode_ujian)->where('siswa', $idsiswa)->get()->getResultObject();

            foreach ($data_ujian_siswa as $rows) {
                $data_detail_siswa = [
                    'jawaban' => null,
                    'benar'   => null,
                    'jam'     => null,
                    'status'  => null,
                ];
                $this->ujianSiswaModel->update($rows->id_ujian_siswa, $data_detail_siswa);
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

    public function otomatisKirimUjian(){
         $data = $this->ujianModel->where('status', 'U')->where('end_ujian <', date('Y-m-d H:i'))->get()->getResultObject();
         foreach($data as $rows){
             $this->ujianSiswaModel
                ->set('status', 'selesai')
                ->set('date_send', time())
                ->where('ujian', $rows->kode_ujian)
                ->where('siswa', $rows->id_siswa)
                ->update();
                
            $siswa = $this->siswaModel->where('id_siswa', $rows->id_siswa)->get()->getResultObject();

            $data['ujian'] = array();
            foreach ($siswa as  $r) {
                $ujian = $this->ujianMasterModel->getAllUntukNilaiUjian($r->kelas, $r->id_siswa, $rows->kode_ujian);
    
                foreach ($ujian as $u) {
                    $data['ujian'][] = $u;
                }
            }

            if(!empty($data['ujian'])){
                for ($i = 0; $i < count($data['ujian']); $i++) {
                    $ujian_detail = $this->ujianDetailModel->getAllByKodeUjianJumlah($data['ujian'][$i]->kode_ujian);
                    $nilai = round($data['ujian'][$i]->benar / count($ujian_detail) * 100);
                     $this->ujianModel
                    ->set('status', 'S')
                    ->set('nilai', $nilai)
                    ->where('id_ujian', $rows->id_ujian)
                    ->update();
                    
                }
            }else{
                 $this->ujianModel
                    ->set('status', 'S')
                    ->set('nilai', 0)
                    ->where('id_ujian', $rows->id_ujian)
                    ->update();
            }
         }
    }
}

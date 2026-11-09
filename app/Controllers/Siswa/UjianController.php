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
    protected $transaksiModel;
    protected $detailTransaksiModel;
    protected $emailer;

    public function __construct()
    {
        $this->siswaModel = new \App\Models\SiswaModel();
        $this->ujianModel = new \App\Models\UjianModel();
        $this->ujianMasterModel = new \App\Models\UjianMasterModel();
        $this->ujianDetailModel = new \App\Models\UjianDetailModel();
        $this->ujianSiswaModel = new \App\Models\UjianSiswaModel();
        $this->transaksiModel = new \App\Models\TransaksiModel();
        $this->detailTransaksiModel = new \App\Models\DetailTransaksiModel();
        $this->emailer = new \App\Libraries\Emailer();
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

        // Inisialisasi Database dan ID Siswa untuk keperluan pengecekan Midtrans Pending
        $db = \Config\Database::connect();
        $id_siswa = session()->get('id');

        foreach ($siswa as  $r) {
            $tugas = $this->ujianModel->getAllByKelas($r->id_kelas, $r->id_siswa);

            foreach ($tugas as $t) {
                // Ambil detail soal
                $ujian_detail = $this->ujianDetailModel->getSoalAcakBerdasarkanLevel($t->kode_ujian);

                // Gunakan pengecekan is_array agar super aman
                $t->total_soal = is_array($ujian_detail) ? count($ujian_detail) : 0;

                // =========================================================================
                // PENGECEKAN STATUS TRANSAKSI PENDING MIDTRANS
                // =========================================================================
                $cekPending = $db->table('detail_transaksi')
                    ->select('transaksi.token')
                    ->join('transaksi', 'transaksi.idtransaksi = detail_transaksi.idtransaksi')
                    ->where('transaksi.idsiswa', $id_siswa)
                    ->where('detail_transaksi.idujian', $t->id_ujian)
                    ->like('transaksi.jenis_paket', '"tryout_ukp"') // Sesuai jenis paket di fungsi Upgrade
                    ->whereIn('transaksi.status', ['P', 'PM','M']) // P = Pending
                    ->orderBy('transaksi.idtransaksi', 'DESC')
                    ->get()
                    ->getRow();

                // Masukkan token pending ke dalam object $t
                if ($cekPending && !empty($cekPending->token)) {
                    $t->token_pending = $cekPending->token;
                } else {
                    $t->token_pending = null;
                }
                // =========================================================================

                // Kelompokkan ujian berdasarkan nama kelas
                $data['ujian'][$r->nama_kelas][] = $t;
            }
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
        $cek_ujian = $this->ujianModel->where('id_ujian', decrypt_url($id_ujian))
            ->where('id_siswa', $idsiswa)
            ->get()->getRowObject();

        if (!empty($cek_ujian) && $cek_ujian->status == 'S') {
            // Jika status sudah 'S' (Selesai), langsung redirect ke list ujian
            return redirect()->to(base_url('sw-siswa/ujian'))->with('pesan', 'Ujian telah selesai dan tidak dapat diakses kembali.');
        }
        // Ambil waktu dari device (jika dikirim via GET), jika tidak pakai waktu server
        $deviceTimeRequest = $this->request->getGet('device_time');
        $now = (!empty($deviceTimeRequest)) ? date('Y-m-d H:i', strtotime($deviceTimeRequest)) : date('Y-m-d H:i');


        $dataUjian = $this->ujianModel->where('id_ujian', decrypt_url($id_ujian))->where('id_siswa', $idsiswa)->where('status', 'B')->get()->getRowObject();

        if (!empty($dataUjian)) {
            $this->ujianSiswaModel->where('ujian', decrypt_url($kode_ujian))->where('siswa', $idsiswa)->delete();
            // Ambil soal acak berdasarkan level jika ada pengaturan jumlah soal
            $ujian_detail = $this->ujianDetailModel->getSoalAcakBerdasarkanLevel(decrypt_url($kode_ujian));
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
        $data['lampiran_ujian'] = null;
        if (\Config\Database::connect()->fieldExists('lampiran', 'ujian_master')) {
            $masterUjian = $this->ujianMasterModel->select('lampiran')->where('kode_ujian', decrypt_url($kode_ujian))->get()->getRowObject();
            $data['lampiran_ujian'] = $masterUjian->lampiran ?? null;
        }
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
                $ujian_detail = $this->ujianDetailModel->getSoalAcakBerdasarkanLevel($kode_ujian);

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

    public function reviewUjian($kode_ujian)
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-siswa')],
            ['title' => 'Data Ujian', 'url' => base_url('sw-siswa/ujian')],
            ['title' => 'List Soal Ujian', 'url' => '#'],
        ];
        $idsiswa = session()->get('id');
        $data['ujian'] = $this->ujianMasterModel->getBykode(decrypt_url($kode_ujian));
        $data['detail_ujian'] = $this->ujianDetailModel->getAllBykodeUjian(decrypt_url($kode_ujian));
        $data['siswa'] = $this->siswaModel->asObject()->find($idsiswa);

        $data['ujian_siswa'] = $this->ujianSiswaModel
            ->where('ujian', decrypt_url($kode_ujian))
            ->where('siswa', $idsiswa)
            ->get()->getResultObject();

        $data['jawaban_benar'] = $this->ujianSiswaModel->benar(decrypt_url($kode_ujian), $idsiswa, 1);
        $data['jawaban_salah'] = $this->ujianSiswaModel->salah(decrypt_url($kode_ujian), $idsiswa, 0);
        $data['tidak_dijawab'] = $this->ujianSiswaModel->belum_terjawab(decrypt_url($kode_ujian), $idsiswa);

        return view('siswa/ujian/reviewUjian', $data);
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

    // Untuk upgrade supaya kunci jawaban dan pembahasan bisa di akses
    public function upgradeUjian()
    {
        // Pastikan request datang dari AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid Request']);
        }

        $id = $this->request->getPost('id_ujian');
        $kode = $this->request->getPost('kode_ujian');

        $idujian = decrypt_url($id);
        $kodeujian = decrypt_url($kode);

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Ambil data harga upgrade dari ujian_master
            $dataUjianMaster = $this->ujianMasterModel->where('kode_ujian', $kodeujian)->get()->getRowObject();

            if (!$dataUjianMaster) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Data ujian tidak ditemukan.']);
            }

            $biaya_upgrade = (int) ($dataUjianMaster->biaya_upgrade ?? 0);

            if ($biaya_upgrade <= 0) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Biaya upgrade belum diatur oleh Admin.']);
            }

            $tgl_mulai = date('Y-m-d H:i:s');
            $tgl_exp   = date('Y-m-d H:i:s', strtotime('+ 1 day', strtotime($tgl_mulai)));
            $status    = 'M'; // M = Menunggu Pembayaran

            // 2. Insert ke tabel Transaksi
            $dataInsert = [
                'idsiswa'        => session()->get('id'),
                'nominal'        => $biaya_upgrade,
                'diskon'         => '0',
                'status'         => $status,
                'tgl_exp'        => $tgl_exp,
                'tgl_drop'       => $tgl_exp,
                'tgl_pembayaran' => null,
                'keterangan'     => 'Upgrade Pembahasan: ' . ($dataUjianMaster->nama_ujian ?? 'Ujian'),
                'jenis_bayar'    => 'online',
                'jenis_paket'    => '["tryout_ukp"]', // Penanda bahwa ini transaksi khusus Upgrade
                'created_at'     => $tgl_mulai
            ];

            $this->transaksiModel->insert($dataInsert);
            $idtransaksi = $this->transaksiModel->insertID();

            // 3. Insert ke tabel Detail Transaksi
            $detailTransaksi = [
                'idtransaksi' => $idtransaksi,
                'idpaket'     => '0',
                'idmapel'     => '0',
                'idsesi'      => '0',
                'idujian'     => $idujian,
                'prince'      => $biaya_upgrade,
                'quantity'    => 1,
                'name'        => "Upgrade: " . $dataUjianMaster->nama_ujian
            ];
            $this->detailTransaksiModel->insert($detailTransaksi);

            // 4. Ambil data Siswa untuk dikirim ke Midtrans
            $siswa = $db->table('siswa')->where('id_siswa', session()->get('id'))->get()->getRowObject();

            // 5. Integrasi Midtrans
            $snapToken = null;
            if ($biaya_upgrade > 0) {
                \Midtrans\Config::$serverKey    = setting('midtrans_server_key');
                \Midtrans\Config::$isProduction = filter_var(setting('midtrans_is_production'), FILTER_VALIDATE_BOOLEAN);
                \Midtrans\Config::$isSanitized  = true;
                \Midtrans\Config::$is3ds        = true;

                $dataItem = [
                    [
                        'id'       => 'UPG-' . $idujian,
                        'price'    => $biaya_upgrade,
                        'quantity' => 1,
                        'name'     => substr("Upgrade Pembahasan: " . $dataUjianMaster->nama_ujian, 0, 50)
                    ]
                ];

                $params = [
                    'transaction_details' => [
                        'order_id'     => $idtransaksi . '-' . time(),
                        'gross_amount' => $biaya_upgrade,
                    ],
                    'item_details'     => $dataItem,
                    'customer_details' => [
                        'first_name' => $siswa->nama_siswa ?? 'Siswa',
                        'email'      => $siswa->email ?? 'no-email@domain.com',
                        'phone'      => $siswa->hp ?? '080000000000',
                    ],
                ];

                $snapToken = \Midtrans\Snap::getSnapToken($params);

                // PERBAIKAN DI SINI:
                // Gunakan Query Builder langsung ($db->table) agar tidak di-block oleh rules allowedFields di Model
                $db->table('transaksi')->where('idtransaksi', $idtransaksi)->update(['token' => $snapToken]);
            }

            $db->transComplete();

            // 6. Kembalikan Response ke AJAX
            if ($db->transStatus() === FALSE) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan transaksi ke database.']);
            }

            if ($snapToken) {
                return $this->response->setJSON([
                    'status'     => 'success',
                    'snap_token' => $snapToken,
                    csrf_token() => csrf_hash() // Mengirim kembali CSRF token baru agar form tetap aman
                ]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mendapatkan token pembayaran dari Midtrans.']);
            }
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
        }
    }

    public function unduhLampiran($kode_ujian)
    {
        $kode = decrypt_url($kode_ujian);
        $idSiswa = session()->get('id');
        if ($kode === '' || empty($idSiswa)) {
            return redirect()->to('sw-siswa/ujian')->with('pesan', 'Lampiran tidak tersedia.');
        }

        $db = \Config\Database::connect();
        if (!$db->fieldExists('lampiran', 'ujian_master')) {
            return redirect()->to('sw-siswa/ujian')->with('pesan', 'Lampiran tidak tersedia.');
        }

        $ujian = $this->ujianModel
            ->select('ujian.review, ujian_master.lampiran')
            ->join('ujian_master', 'ujian_master.kode_ujian = ujian.kode_ujian', 'left')
            ->where('ujian.kode_ujian', $kode)
            ->where('ujian.id_siswa', $idSiswa)
            ->where('ujian.review', '1')
            ->where('ujian_master.lampiran IS NOT NULL', null, false)
            ->where('ujian_master.lampiran !=', '')
            ->get()
            ->getRowObject();

        if (!$ujian || empty($ujian->lampiran)) {
            return redirect()->to('sw-siswa/ujian')->with('pesan', 'Lampiran hanya bisa diunduh saat review aktif.');
        }

        $namaFile = basename($ujian->lampiran);
        $path = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'lampiranUjian' . DIRECTORY_SEPARATOR . $namaFile;
        if (!is_file($path)) {
            return redirect()->to('sw-siswa/ujian')->with('error', 'File lampiran tidak ditemukan.');
        }

        $siswa = $this->siswaModel->asObject()->find($idSiswa);
        $nama = trim(strip_tags((string) ($siswa->nama_siswa ?? session()->get('nama') ?? 'Siswa')));
        $nama = preg_replace('/\s+/', ' ', $nama) ?: 'Siswa';
        if (function_exists('mb_substr')) {
            $nama = mb_substr($nama, 0, 80);
        } else {
            $nama = substr($nama, 0, 80);
        }

        $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
        if ($ext === 'pdf') {
            return $this->unduhPdfWatermark($path, $nama);
        }

        return $this->unduhGambarWatermark($path, $nama, $ext);
    }

    private function unduhPdfWatermark(string $path, string $nama)
    {
        $sementara = null;
        try {
            if (!class_exists(\TCPDF::class)) {
                require_once APPPATH . 'ThirdParty/TCPDF-6.3.5/tcpdf.php';
            }
            require_once APPPATH . 'ThirdParty/fpdi/src/autoload.php';

            $siapkanPdf = function () {
                $dokumen = new \setasign\Fpdi\Tcpdf\Fpdi();
                $dokumen->setPrintHeader(false);
                $dokumen->setPrintFooter(false);
                $dokumen->SetAutoPageBreak(false, 0);
                $dokumen->SetMargins(0, 0, 0);
                return $dokumen;
            };

            $pdf = $siapkanPdf();
            $sumber = $path;
            try {
                $pageCount = $pdf->setSourceFile($sumber);
            } catch (\Throwable $e) {
                $terbuka = (new \App\Libraries\PdfLampiran())->bukaEnkripsiPemilik($path);
                $sementara = tempnam(sys_get_temp_dir(), 'lampiran');
                file_put_contents($sementara, $terbuka);
                unset($terbuka);
                $sumber = $sementara;
                $pdf = $siapkanPdf();
                $pageCount = $pdf->setSourceFile($sumber);
            }

            for ($i = 1; $i <= $pageCount; $i++) {
                $template = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($template);
                $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';
                $pdf->AddPage($orientation, [$size['width'], $size['height']]);
                $pdf->useTemplate($template, 0, 0, $size['width'], $size['height']);
                $this->tulisWatermarkPdf($pdf, $nama, (float) $size['width'], (float) $size['height']);
            }

            $binary = $pdf->Output('lampiran-ujian.pdf', 'S');
        } catch (\Throwable $e) {
            log_message('error', '[Lampiran PDF] ' . $e->getMessage());
            return redirect()->to('sw-siswa/ujian')->with('pesan', 'Lampiran PDF tidak dapat diberi watermark.');
        } finally {
            if (is_string($sementara) && is_file($sementara)) {
                unlink($sementara);
            }
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="lampiran-ujian.pdf"')
            ->setHeader('Content-Length', (string) strlen($binary))
            ->setBody($binary);
    }

    private function tulisWatermarkPdf($pdf, string $nama, float $lebar, float $tinggi): void
    {
        $ukuran = 12;
        $pdf->SetFont('helvetica', '', $ukuran);
        while ($pdf->GetStringWidth($nama) > $lebar * 0.42 && $ukuran > 8) {
            $ukuran--;
            $pdf->SetFont('helvetica', '', $ukuran);
        }

        $sudut = 30;
        $rad = deg2rad($sudut);
        $lebarTeks = $pdf->GetStringWidth($nama);
        $tinggiHuruf = $ukuran * 0.36;
        $maju = $lebarTeks * cos($rad);
        $naik = $lebarTeks * sin($rad);
        $jarakX = $maju + 16;
        $jarakY = $naik + $tinggiHuruf + 14;
        $margin = 10;

        $pdf->SetTextColor(145, 145, 145);
        $pdf->SetAlpha(0.22);

        for ($y = $margin + $naik; $y <= $tinggi - $margin; $y += $jarakY) {
            for ($x = $margin; $x + $maju <= $lebar - $margin; $x += $jarakX) {
                $pdf->StartTransform();
                $pdf->Rotate($sudut, $x, $y);
                $pdf->Text($x, $y, $nama);
                $pdf->StopTransform();
            }
        }

        $pdf->SetAlpha(1);
    }

    private function unduhGambarWatermark(string $path, string $nama, string $ext)
    {
        if (!function_exists('imagecreatetruecolor')) {
            return redirect()->to('sw-siswa/ujian')->with('pesan', 'Server belum mendukung watermark gambar.');
        }

        $sumber = $this->bukaGambarLampiran($path, $ext);
        if ($sumber === null) {
            return redirect()->to('sw-siswa/ujian')->with('pesan', 'Format lampiran tidak didukung.');
        }

        $lebar = imagesx($sumber);
        $tinggi = imagesy($sumber);
        $gambar = imagecreatetruecolor($lebar, $tinggi);
        $putih = imagecolorallocate($gambar, 255, 255, 255);
        imagefilledrectangle($gambar, 0, 0, $lebar, $tinggi, $putih);
        imagecopy($gambar, $sumber, 0, 0, 0, 0, $lebar, $tinggi);
        imagedestroy($sumber);

        $font = FCPATH . 'fonts/bookosb.ttf';
        if (is_file($font) && function_exists('imagettftext')) {
            $this->tulisWatermarkGambar($gambar, $nama, $lebar, $tinggi, $font);
        } else {
            $warnaSolid = imagecolorallocate($gambar, 140, 140, 140);
            $langkahX = max(160, strlen($nama) * 12);
            for ($y = 28; $y < $tinggi - 10; $y += 52) {
                for ($x = 16; $x + $langkahX <= $lebar; $x += $langkahX) {
                    imagestring($gambar, 5, $x, $y, $nama, $warnaSolid);
                }
            }
        }

        ob_start();
        $mime = 'image/jpeg';
        $namaUnduh = 'lampiran-ujian.jpg';
        if ($ext === 'png' && function_exists('imagepng')) {
            imagepng($gambar);
            $mime = 'image/png';
            $namaUnduh = 'lampiran-ujian.png';
        } elseif ($ext === 'webp' && function_exists('imagewebp')) {
            imagewebp($gambar, null, 85);
            $mime = 'image/webp';
            $namaUnduh = 'lampiran-ujian.webp';
        } else {
            imagejpeg($gambar, null, 90);
        }
        $binary = ob_get_clean();
        imagedestroy($gambar);

        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Disposition', 'attachment; filename="' . $namaUnduh . '"')
            ->setHeader('Content-Length', (string) strlen($binary))
            ->setBody($binary);
    }

    private function tulisWatermarkGambar($gambar, string $nama, int $lebar, int $tinggi, string $font): void
    {
        $ukuran = max(18, (int) round(min($lebar, $tinggi) / 34));
        $sudut = 30;

        $ukur = function (int $ukuranFont) use ($font, $nama, $sudut): array {
            $kotak = imagettfbbox($ukuranFont, $sudut, $font, $nama);
            $xs = [$kotak[0], $kotak[2], $kotak[4], $kotak[6]];
            $ys = [$kotak[1], $kotak[3], $kotak[5], $kotak[7]];
            return [min($xs), min($ys), max($xs) - min($xs), max($ys) - min($ys)];
        };

        [$minX, $minY, $teksW, $teksH] = $ukur($ukuran);
        while ($teksW > $lebar * 0.42 && $ukuran > 14) {
            $ukuran -= 2;
            [$minX, $minY, $teksW, $teksH] = $ukur($ukuran);
        }

        $maju = $teksW;
        $naik = $teksH;
        $jarakX = (int) ($maju + max(28, $ukuran * 1.4));
        $jarakY = (int) ($naik + max(24, $ukuran * 1.5));
        $margin = (int) max(12, $ukuran);
        $warna = imagecolorallocatealpha($gambar, 145, 145, 145, 72);

        for ($y = $margin; $y + $naik <= $tinggi - $margin; $y += $jarakY) {
            for ($x = $margin; $x + $maju <= $lebar - $margin; $x += $jarakX) {
                imagettftext($gambar, $ukuran, $sudut, $x - (int) $minX, $y - (int) $minY, $warna, $font, $nama);
            }
        }
    }

    private function bukaGambarLampiran(string $path, string $ext)
    {
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                return function_exists('imagecreatefromjpeg') ? @imagecreatefromjpeg($path) : null;
            case 'png':
                return function_exists('imagecreatefrompng') ? @imagecreatefrompng($path) : null;
            case 'gif':
                return function_exists('imagecreatefromgif') ? @imagecreatefromgif($path) : null;
            case 'webp':
                return function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : null;
            default:
                return null;
        }
    }
}

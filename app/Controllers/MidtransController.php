<?php

namespace App\Controllers;

use App\Libraries\Emailer;

class MidtransController extends BaseController
{
    protected $db;
    protected $transaksiModel;
    protected $mapelSiswaModel;
    protected $detailPaketModel;
    protected $ujianModel;
    protected $ujianSiswaModel;
    protected $paketModel;
    protected $affiliateCommissionModel;
    protected $ikhModel;
    protected $emailer;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->transaksiModel = new \App\Models\TransaksiModel();
        $this->mapelSiswaModel = new \App\Models\MapelSiswaModel();
        $this->detailPaketModel = new \App\Models\DetailPaketModel();
        $this->ujianModel = new \App\Models\UjianModel();
        $this->ujianSiswaModel = new \App\Models\UjianSiswaModel();
        $this->paketModel = new \App\Models\PaketModel();
        $this->affiliateCommissionModel = new \App\Models\AffiliateCommissionModel();
        $this->ikhModel = new \App\Models\IkhModel();
        $this->emailer = new Emailer();
    }

    public function notification()
    {
        try {
            $payload = $this->request->getJSON();
            $serverKey = setting('midtrans_server_key');

            // 1. Validasi Signature (Keamanan)
            $signature = hash("sha512", $payload->order_id . $payload->status_code . $payload->gross_amount . $serverKey);

            if ($payload->signature_key !== $signature) {
                return $this->response->setStatusCode(403)->setJSON(['message' => 'Invalid signature']);
            }

            $status = $payload->transaction_status;
            $orderIdRaw = explode('-', $payload->order_id)[0];

            // Ambil data transaksi beserta data siswa
            $trx = $this->transaksiModel->join('siswa', 'transaksi.idsiswa=siswa.id_siswa')
                ->where('idtransaksi', $orderIdRaw)
                ->first();

            if (!$trx) {
                return $this->response->setStatusCode(404)->setJSON(['message' => 'Transaction not found']);
            }

            // 2. Mapping Status
            $internalStatus = match ($status) {
                'settlement', 'capture' => 'S', // Success
                'pending'               => 'PM', // Waiting
                'deny', 'cancel'        => 'DM', // Failed
                'expire'                => 'E',  // Expired
                default                 => 'M'
            };

            // 3. Mulai Database Transaction
            $this->db->transBegin();

            $updateData = [
                'status'      => $internalStatus,
                'jenis_bayar' => 'online'
            ];

            if ($internalStatus === 'S') {
                $updateData['tgl_pembayaran'] = date('Y-m-d H:i:s');
                $this->transaksiModel->update($trx['idtransaksi'], $updateData);
                $this->approveOtomatis($trx['idtransaksi']);

                // Kirim Notifikasi Email
                $this->kirimNotifikasiEmail($trx, $payload->gross_amount);
            }



            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return $this->response->setStatusCode(500)->setJSON(['message' => 'Failed to process transaction']);
            } else {
                $this->db->transCommit();
                return $this->response->setJSON(['message' => 'OK']);
            }
        } catch (\Exception $e) {
            if ($this->db->transStatus() === false) $this->db->transRollback();
            log_message('error', '[Midtrans Notification Error] ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['message' => $e->getMessage()]);
        }
    }

    public function notificationManual()
    {
        $idtransaksi = $this->request->getGet('order_id');

        // Cari transaksi dengan ID tersebut yang statusnya SUDAH SUKSES ('S')
        $cekTransaksi = $this->transaksiModel->where('idtransaksi', $idtransaksi)->where('status', 'S')->get()->getRowObject();

        // LOGIKA DIPERBAIKI: Jika datanya TIDAK KOSONG (!empty), artinya transaksi ini sudah pernah diproses sukses.
        // Cegah proses berulang dan langsung kembalikan ke halaman.
        if (!empty($cekTransaksi)) {
            return redirect()->to('sw-siswa/transaksi')->with('success', 'Paket sudah dibayar silahkan untuk memulai ujian');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // ---------------------------------------------------------
            // 1. KONFIGURASI MIDTRANS
            // ---------------------------------------------------------
            \Midtrans\Config::$serverKey = setting('midtrans_server_key');
            \Midtrans\Config::$isProduction = filter_var(setting('midtrans_is_production'), FILTER_VALIDATE_BOOLEAN);
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            // Tarik status terbaru langsung dari server Midtrans
            $status = (object) \Midtrans\Transaction::status($idtransaksi);

            // ---------------------------------------------------------
            // 2. UPDATE STATUS TRANSAKSI (SELAIN SUKSES)
            // ---------------------------------------------------------
            if ($status->transaction_status == 'pending') {
                $this->transaksiModel->where('idtransaksi', $idtransaksi)
                    ->set('status', 'PM')
                    ->set('tgl_exp', $status->transaction_time)
                    ->set('tgl_drop', $status->expiry_time ?? null)
                    ->update();

                // LOGIKA DIPERBAIKI: Menambahkan 'deny' dan 'cancel' sesuai dokumentasi resmi Midtrans API
            } elseif ($status->transaction_status == 'deny' || $status->transaction_status == 'cancel') {
                $this->transaksiModel->where('idtransaksi', $idtransaksi)
                    ->set('status', 'DM')
                    ->set('tgl_exp', $status->transaction_time)
                    ->set('tgl_drop', $status->expiry_time ?? null)
                    ->update();
            } elseif ($status->transaction_status == 'expire') {
                $this->transaksiModel->where('idtransaksi', $idtransaksi)
                    ->set('status', 'E')
                    ->set('tgl_exp', $status->transaction_time)
                    ->set('tgl_drop', $status->expiry_time ?? null)
                    ->update();
            }

            // ---------------------------------------------------------
            // 3. JIKA TRANSAKSI BERHASIL
            // ---------------------------------------------------------
            // LOGIKA DIPERBAIKI: Mengakomodasi 'capture' (Credit Card) dan 'settlement' (Transfer/E-Wallet)
            if ($status->transaction_status == 'settlement' || $status->transaction_status == 'capture') {
                // Update Status Transaksi menjadi Sukses (S)
                $this->transaksiModel->where('idtransaksi', $idtransaksi)
                    ->set('status', 'S')
                    ->set('tgl_pembayaran', date("Y-m-d H:i:s"))
                    ->update();
                $this->approveOtomatis($idtransaksi);

                // Kirim Email Konfirmasi
                $subject = 'PEMBAYARAN BERHASIL';
                $namaSiswa = $this->request->getVar('nama_siswa') ?? 'Siswa';
                $message = '
                <div style="color: #000; padding: 10px;">
                    <div style="font-family: `Segoe UI`, Tahoma, sans-serif; font-size: 20px; color: #1C3FAA; font-weight: bold;">
                        PEMBAYARAN TERVERIFIKASI
                    </div><br>
                    <p style="font-family: `Segoe UI`, Tahoma, sans-serif; color: #000;">
                        Hallo ' . esc($namaSiswa) . '<br>
                        Pembayaran anda telah berhasil kami verifikasi. Silahkan kembali ke aplikasi <a href="https://kelasbrevet.com/">KelasBrevet.com</a>
                    </p>
                </div>';

                if (session('email')) {
                    $this->emailer->send(session('email'), $subject, $message);
                }
            }

            // ---------------------------------------------------------
            // 4. COMMIT ATAU ROLLBACK TRANSAKSI
            // ---------------------------------------------------------
            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->to('sw-siswa/transaksi')->with('pesan', 'Pembayaran tidak dapat di proses');
            } else {
                $db->transCommit();
                return redirect()->to('sw-siswa/transaksi')->with('success', 'Status pembayaran berhasil diperbarui');
            }
        } catch (\Exception $e) {
            // ---------------------------------------------------------
            // 5. TANGKAP ERROR (MIDTRANS API DOWN / KONEKSI GAGAL)
            // ---------------------------------------------------------
            $db->transRollback();

            // Mengembalikan ke halaman transaksi dengan pesan error yang aman tanpa merusak tampilan
            return redirect()->to('sw-siswa/transaksi')->with('pesan', 'Terjadi kesalahan koneksi ke server pembayaran. Silakan coba lagi.');
        }
    }

    private function approveOtomatis($idtransaksi)
    {
        $datatransaksi = $this->transaksiModel
            ->join('detail_transaksi', 'detail_transaksi.idtransaksi=transaksi.idtransaksi')
            ->where('transaksi.idtransaksi', $idtransaksi)
            ->groupBy('detail_transaksi.idpaket')
            ->get()->getResultObject();

        foreach ($datatransaksi as $rowst) {
            // Logika pengecekan status sebelum diproses
            if ($rowst->status == 'S') {
                $idsiswa = $rowst->idsiswa;

                // Tambah Mapel Siswa
                if ($rowst->idmapel != '0') {
                    $data_mapel_siswa = [
                        'idmapel' => $rowst->idmapel,
                        'idsiswa' => $rowst->idsiswa
                    ];
                    $this->mapelSiswaModel->save($data_mapel_siswa);
                }

                // Ambil Data Paket & Master Ujian
                $datapaket = $this->detailPaketModel
                    ->join('ujian_master', 'detail_paket.id_ujian=ujian_master.id_ujian')
                    ->where('idpaket', $rowst->idpaket)
                    ->groupBy('detail_paket.id_ujian')
                    ->get()->getResultObject();

                foreach ($datapaket as $rowsp) {
                    // Menambah ujian ke siswa
                    $data_ujian = [
                        'id_siswa'     => $rowst->idsiswa,
                        'kode_ujian'   => $rowsp->kode_ujian,
                        'nama_ujian'   => $rowsp->nama_ujian,
                        'guru'         => $rowsp->guru,
                        'kelas'        => $rowsp->kelas,
                        'mapel'        => $rowsp->mapel,
                        'jml_mudah'    => $rowsp->jml_mudah,
                        'jml_sedang'   => $rowsp->jml_sedang,
                        'jml_susah'    => $rowsp->jml_susah,
                        'waktu_per_soal' => $rowsp->waktu_per_soal,
                        'date_created' => time(),
                    ];
                    $this->ujianModel->save($data_ujian);
                    $this->ujianSiswaModel->where('ujian', $rowsp->kode_ujian)->where('siswa', $idsiswa)->delete();
                }
            }
        }

        $transaksiMaster = $this->transaksiModel
            ->where('idtransaksi', $idtransaksi)
            ->where('status', 'S')
            ->first();

        if ($transaksiMaster) {
            $jenisPaketArray = json_decode($transaksiMaster['jenis_paket'], true) ?? [];

            if (in_array('ikh', $jenisPaketArray)) {

                $idsiswa = $transaksiMaster['idsiswa'];

                // 2. Cek apakah siswa ini sudah punya data di ikhModel
                $existingIkh = $this->ikhModel->where('id_siswa', $idsiswa)->first();

                if ($existingIkh) {
                    // --- LOGIKA UPDATE ---
                    $newKuota = (int)$existingIkh['kuota'] + 1;

                    $dataUpdate = [
                        'kuota'  => $newKuota
                    ];

                    $this->ikhModel->update($existingIkh['id_ikh'], $dataUpdate);
                } else {
                    // --- LOGIKA INSERT BARU ---

                    // 1. Data utama yang kita butuhkan
                    $dataInsertBaru = [
                        'id_siswa'            => $idsiswa,
                        'is_riwayat_hidup'    => '1',
                        'is_bukan_pns'        => '1',
                        'is_pakta_integritas' => '1',
                        'is_pernyataan_ikh'   => '1',
                        'kuota'               => '1'
                    ];

                    // 2. MELENGKAPI DATA (Perbaikan Logika ENUM & DATE)
                    $dataInsertLengkap = [];

                    // Daftar field yang SUDAH PUNYA default di database atau BOLEH NULL.
                    // Field ini tidak akan kita kirim, biarkan MySQL yang isi otomatis.
                    $skipFields = [
                        'tanggal_lahir',
                        'status_validasi_admin',
                        'status_proses',
                        'status_final',
                        'status_sertifikat',
                        'catatan_admin',
                        'tgl_aktif',
                        'tgl_exp'
                    ];

                    foreach ($this->ikhModel->allowedFields as $field) {
                        if (array_key_exists($field, $dataInsertBaru)) {
                            // Jika field ada di data utama kita, masukkan.
                            $dataInsertLengkap[$field] = $dataInsertBaru[$field];
                        } else {
                            // Jika masuk dalam daftar skip, lewati (jangan masukan ke array)
                            if (in_array($field, $skipFields)) {
                                continue;
                            }

                            // Handle ENUM yang dari gambar pertama TIDAK PUNYA default
                            if ($field === 'pendidikan_terakhir') {
                                $dataInsertLengkap[$field] = 'S1';
                            } elseif ($field === 'kategori_kantor') {
                                $dataInsertLengkap[$field] = 'Lain...';
                            } else {
                                // Sisa field varchar/text/int diisi string kosong agar tidak error NOT NULL
                                $dataInsertLengkap[$field] = '';
                            }
                        }
                    }

                    // 3. Jalankan perintah insert
                    $this->ikhModel->insert($dataInsertLengkap);
                }
            }
        }

        // Handle Affiliate Commission
        $commission = $this->affiliateCommissionModel
            ->where('id_transaksi', $idtransaksi)
            ->first();

        if ($commission) {
            $this->affiliateCommissionModel
                ->where('id_transaksi', $idtransaksi)
                ->update(null, [
                    'status'            => 'approved',
                    'tgl_approved'      => date('Y-m-d H:i:s'),
                    'status_penarikan'  => 'pending'
                ]);
        }
    }

    private function kirimNotifikasiEmail($trx, $amount)
    {
        try {
            $subject = "Pembayaran Berhasil - " . $trx['idtransaksi'];

            $message = "
                <div style='font-family: Arial, sans-serif; line-height: 1.6;'>
                    <h2 style='color: #2e7d32;'>Halo, {$trx['nama_siswa']}!</h2>
                    <p>Pembayaran Anda untuk transaksi <strong>#{$trx['idtransaksi']}</strong> telah kami terima.</p>
                    <hr>
                    <table border='0' cellpadding='5'>
                        <tr><td><strong>Total Bayar</strong></td><td>: Rp " . number_format($amount, 0, ',', '.') . "</td></tr>
                        <tr><td><strong>Metode</strong></td><td>: Pembayaran Online (Midtrans)</td></tr>
                        <tr><td><strong>Status</strong></td><td>: <span style='color:green; font-weight:bold;'>BERHASIL / AKTIF</span></td></tr>
                    </table>
                    <hr>
                    <p>Silakan login ke panel siswa untuk mengakses materi atau memulai ujian Anda.</p>
                    <br>
                    <p>Terima Kasih,<br><strong>Admin Kelas Brevet</strong></p>
                </div>
            ";

            $this->emailer->send($trx['email'], $subject, $message);
            // Cek apakah data ditemukan
            send_notif(
                $trx['idsiswa'], // Pastikan kolom di DB namanya idsiswa
                "Pembayaran Berhasil",
                "Pembelian paket berhasil.",
                base_url('sw-siswa/transaksi')
            );
        } catch (\Exception $e) {
        }
    }
}

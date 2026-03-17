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
                $this->approveOtomatis($trx['idtransaksi']);
                
                // Kirim Notifikasi Email
                $this->kirimNotifikasiEmail($trx, $payload->gross_amount);
            }

            $this->transaksiModel->update($trx['idtransaksi'], $updateData);


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

    private function approveOtomatis($idtransaksi)
    {
        $datatransaksi = $this->transaksiModel
            ->join('detail_transaksi', 'detail_transaksi.idtransaksi=transaksi.idtransaksi')
            ->where('transaksi.idtransaksi', $idtransaksi)
            ->groupBy('detail_transaksi.idpaket')
            ->get()->getResultObject();

        foreach ($datatransaksi as $rowst) {
            // Logika pengecekan status sebelum diproses
            if ($rowst->status != 'S') {
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
                        'date_created' => time(),
                    ];
                    $this->ujianModel->save($data_ujian);

                    // Menambah/Reset status di tabel ujian siswa
                    $data_ujian_siswa = $this->ujianSiswaModel
                        ->where('ujian', $rowsp->kode_ujian)
                        ->where('siswa', $idsiswa)
                        ->get()->getResultObject();

                    foreach ($data_ujian_siswa as $rows) {
                        $data_detail_siswa = [
                            'jawaban' => null,
                            'benar'   => null,
                            'jam'     => null,
                            'status'  => null,
                        ];
                        $this->ujianSiswaModel->set($data_detail_siswa)
                            ->where('id_ujian_siswa', $rows->id_ujian_siswa)
                            ->update();
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
        }
    }

    private function kirimNotifikasiEmail($trx, $amount)
    {
        try {
            $emailer = new Emailer();
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

            $emailer->send($trx['email'], $subject, $message);
            // Cek apakah data ditemukan
            send_notif(
                $trx['idsiswa'], // Pastikan kolom di DB namanya idsiswa
                "Verifikasi Pembayaran",
                "Pembelian paket berhasil.",
                base_url('sw-siswa/transaksi')
            );
        } catch (\Exception $e) {
        }
    }
}
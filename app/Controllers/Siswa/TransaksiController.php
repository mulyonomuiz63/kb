<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class TransaksiController extends BaseController
{
    protected $transaksiModel;
    protected $detailTransaksiModel;
    protected $detailPaketModel;
    protected $paketModel;
    protected $affiliateLinkModel;
    protected $affiliateModel;
    protected $affiliateCommissionModel;
    protected $voucherModel;
    protected $emailer;

    public function __construct()
    {
        $this->transaksiModel = new \App\Models\TransaksiModel();
        $this->detailTransaksiModel = new \App\Models\DetailTransaksiModel();
        $this->detailPaketModel = new \App\Models\DetailPaketModel();
        $this->paketModel = new \App\Models\PaketModel();
        $this->affiliateModel = new \App\Models\AffiliateModel();
        $this->affiliateLinkModel = new \App\Models\AffiliateLinkModel();
        $this->affiliateCommissionModel = new \App\Models\AffiliateCommissionModel();
        $this->voucherModel = new \App\Models\VoucherModel();
        $this->emailer = new \App\Libraries\Emailer();
    }
    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-siswa')],
            ['title' => 'Histori Transaksi', 'url' => '#'],
        ];
        $data['transaksi'] = $this->transaksiModel->getByIdSiswaAll(session('id'));

        return view('siswa/transaksi/list', $data);
    }
    public function pesan($id = '', $kodevoucher = '')
    {
        try {
            
            // 1. Inisialisasi & Setup Session URL
            $uri = new \CodeIgniter\HTTP\URI($this->request->getUri());
            session()->set(['url' => $uri->getPath()]);

            // 2. Dekripsi ID Paket
            $idpaket = decrypt_url($id);
            if (!$idpaket) {
                throw new \Exception("ID Paket tidak valid.");
            }

            // 3. Ambil Data Detail Paket
            $datadetail = $this->detailPaketModel
                ->join('ujian_master', 'ujian_master.id_ujian=detail_paket.id_ujian', 'left')
                ->where('detail_paket.idpaket', $idpaket)
                ->get()
                ->getRowObject();

            if (empty($datadetail)) {
                return redirect()->to('bimbel');
            }


            // 5. Cek Ketersediaan Paket & Tampilkan View
            $cekpaket = $this->paketModel->getById($idpaket);
            if (!empty($cekpaket)) {
                $breadcrumbs = [
                    ['title' => 'Dashboard', 'url' => base_url('sw-siswa')],
                    ['title' => 'Detail Paket', 'url' => '#'], // Status saat ini
                ];
                $data = [
                    'db'           => \Config\Database::connect(),
                    'paket'        => $cekpaket,
                    'kodevoucher'  => $kodevoucher,
                    'idpaketenc'   => $id,
                    'url'          => $uri->getPath(),
                    'breadcrumbs'  => $breadcrumbs
                ];
                return view('siswa/paket/index', $data);
            } else {
                return redirect()->to('bimbel')->with('pesan', 'Paket yang anda pilih sudah tidak ada.');
            }
        } catch (\Exception $e) {
            // Log error untuk kebutuhan debugging developer
            return redirect()->to('bimbel')->with('pesan', 'Terjadi kesalahan sistem. Silakan coba beberapa saat lagi.');
        }
    }

    public function cekKodeVoucher()
    {
        if ($this->request->isAJAX()) {
            $kode_voucher = $this->request->getVar('kode_voucher');
            $idpaket = $this->request->getVar('idpaket');
            $data = $this->voucherModel->join('detail_voucher', 'voucher.idvoucher=detail_voucher.idvoucher')->where('detail_voucher.idpaket', $idpaket)->where('voucher.kode_voucher', $kode_voucher)->where('voucher.tgl_exp >', date('Y-m-d H:i:s'))->where('voucher.status', 'A')->groupBy('voucher.idvoucher')->get()->getResultObject();
            echo json_encode($data);
        }
    }

    public function checkout()
    {
        $db = \Config\Database::connect();

        // 1. Cek apakah ada transaksi pending
        $existingTransaksi = $this->transaksiModel
            ->select('transaksi.status')
            ->join('detail_transaksi d', 'd.idtransaksi=transaksi.idtransaksi')
            ->where('transaksi.idsiswa', session('id'))
            ->where('transaksi.status', 'P')
            ->get()->getRowObject();

        if (!empty($existingTransaksi)) {
            return redirect()->to('sw-siswa/transaksi')->with('pesan', 'Anda tidak dapat melakukan pembelian paket, karena masih ada paket yang sedang dalam pembayaran');
        }

        // 2. Persiapan Data
        $tgl_mulai = date('Y-m-d H:i:s');
        $tgl_exp   = date('Y-m-d H:i:s', strtotime('+ 1 day', strtotime($tgl_mulai)));

        $diskonVoucherInput = $this->request->getVar('diskon_voucher');
        $kode_voucher = ($diskonVoucherInput == '' || $diskonVoucherInput == '0') ? '' : $this->request->getVar('kode_voucher');

        // Mulai Transaksi Database
        $db->transBegin();

        try {
            // 3. Simpan Transaksi Utama
            $dataInsert = [
                'idsiswa'      => session('id'),
                'nominal'      => $this->request->getVar('total'),
                'diskon'       => $this->request->getVar('diskon'),
                'voucher'      => $diskonVoucherInput,
                'tgl_exp'      => $tgl_exp,
                'tgl_drop'     => $tgl_exp, // Menggunakan tgl_exp sesuai logika awal
                'kode_voucher' => $kode_voucher
            ];

            $this->transaksiModel->insert($dataInsert);
            $idtransaksi = $this->transaksiModel->insertID();

            // 4. Kalkulasi Harga Satuan (Logic Asli)
            $totalRaw      = $this->request->getVar('total');
            $diskonPersen  = $this->request->getVar('diskon');
            $voucherPersen = ($diskonVoucherInput != '') ? $diskonVoucherInput : 0;

            $diskon        = $totalRaw - ($totalRaw - ($totalRaw * $diskonPersen / 100));
            $totalDiskon   = $totalRaw - $diskon;
            $diskon_voucher = $totalDiskon - ($totalDiskon - ($totalDiskon * $voucherPersen / 100));
            $totalVoucher  = $totalDiskon - $diskon_voucher;

            $detailPaket = $this->detailPaketModel
                ->select('detail_paket.*, ujian_master.nama_ujian, mapel.nama_mapel')
                ->join('ujian_master', 'detail_paket.id_ujian=ujian_master.id_ujian', 'left')
                ->join('mapel', 'detail_paket.id_mapel=mapel.id_mapel', 'left')
                ->where('detail_paket.idpaket', $this->request->getVar('idpaket'))
                ->get()->getResultObject();

            $totalDataCount = $this->detailPaketModel->where('idpaket', $this->request->getVar('idpaket'))->countAllResults();
            $hasil          = ($totalRaw - $diskon - $diskon_voucher) / (int)($totalDataCount ?: 1);

            // 5. Simpan Detail Transaksi
            foreach ($detailPaket as $rows) {
                $this->detailTransaksiModel->insert([
                    'idtransaksi' => $idtransaksi,
                    'idpaket'     => $rows->idpaket,
                    'idmapel'     => $rows->id_mapel,
                    'prince'      => $hasil,
                    'quantity'    => 1,
                    'name'        => $rows->nama_ujian . ' ' . $rows->nama_mapel
                ]);
            }

            // 6. Logic Affiliate
            $shortCode = session()->get('short_code');
            if ($shortCode) {
                $affiliateLink = $this->affiliateLinkModel->where('short_code', $shortCode)->first();
                if ($affiliateLink && $affiliateLink['expired_at'] > date('Y-m-d H:i:s')) {
                    $affiliateCek = $this->affiliateModel->where('kode_affiliate', $affiliateLink['kode_affiliate'])->first();
                    if (session()->get('id') != $affiliateCek['user_id']) {
                        $this->affiliateCommissionModel->insert([
                            'kode_affiliate' => $affiliateLink['kode_affiliate'],
                            'id_transaksi'   => $idtransaksi,
                            'id_paket'       => $this->request->getVar('idpaket'),
                            'komisi'         => $this->request->getVar('komisi'),
                            'harga'          => $totalVoucher,
                        ]);
                    }
                }
            }

            // 7. Commit & Kirim Email
            if ($db->transStatus() === false) {
                throw new \Exception("Gagal memproses data transaksi.");
            }

            $db->transCommit();

            // Menggunakan Library Emailer baru Anda
            $this->kirimEmailInvoice($this->emailer, $diskon, $diskon_voucher, encrypt_url($idtransaksi));
            return redirect()->to('sw-siswa/transaksi/pesan-bayar/' . encrypt_url($idtransaksi))->with('pesan', 'Silahkan pilih metode pembayaran!');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('sw-siswa')->with('pesan', $e->getMessage());
        }
    }

    /**
     * Helper Private Function untuk Konten Email
     */
    private function kirimEmailInvoice($emailer, $diskon, $diskon_voucher, $idtransaksi)
    {
        $totalBayar = $this->request->getVar('total') - $diskon - $diskon_voucher;
        $diskonVoucherHtml = ($this->request->getVar('diskon_voucher') != '')
            ? '<li>Diskon Voucher : ' . $this->request->getVar('diskon_voucher') . '%  (' . number_format($diskon_voucher, 0, '.', '.') . ')</li>'
            : '';

        $subject = 'PAKET BERHASIL DIPESAN';
        $message = '
        <div style="color: #000; padding: 10px; font-family: sans-serif;">
            <div style="font-size: 20px; color: #1C3FAA; font-weight: bold;">PAKET BERHASIL DIPESAN</div>
            <br>
            <p>' . session('nama') . ',<br> Anda telah berhasil memesan paket KelasBrevet dengan detail di bawah ini:</p>
            <ul>
                <li>Nama Paket: ' . $this->request->getVar('nama_paket') . '</li>
                <li>Jenis Paket: ' . $this->request->getVar('jenis_paket') . '</li>
                <li>Harga: ' . number_format($this->request->getVar('nominal'), 0, '.', '.') . '</li>
                <li>Diskon: ' . $this->request->getVar('diskon') . '% (' . number_format($diskon, 0, '.', '.') . ')</li>
                ' . $diskonVoucherHtml . '
                <li><strong>Total Bayar: ' . number_format($totalBayar, 0, '.', '.') . '</strong></li>
            </ul>
            <p>Silahkan lakukan pembayaran:<br><br>
                <a href="' . base_url('sw-siswa/transaksi/pesan-bayar/' . $idtransaksi) . '" style="display: inline-block; padding: 10px 20px; background: #1C3FAA; color: #fff; text-decoration: none; border-radius: 5px;">Pilih metode pembayaran</a>
            </p>
            <p>Salam,<br>Team KelasBrevet</p>
        </div>';

        return $emailer->send(session('email'), $subject, $message);
    }

    public function pesanBayar($id)
    {
        $idtransaksi = decrypt_url($id);
        $data['transaksi']  = $this->transaksiModel
            ->join('detail_transaksi d', 'd.idtransaksi=transaksi.idtransaksi')
            ->join('siswa b', 'b.id_siswa = transaksi.idsiswa')
            ->join('paket c', 'c.idpaket = d.idpaket')
            ->where('transaksi.idsiswa', session('id'))
            ->where('transaksi.idtransaksi', $idtransaksi)
            ->where('transaksi.status', 'P')->get()->getRowObject();
        if (!empty($data['transaksi'])) {
            return view('siswa/paket/checkout', $data);
        } else {
            return redirect()->to('sw-siswa');
        }
    }

    public function manualBayar($idt)
    {
        $db = \Config\Database::connect();

        try {
            // 1. Dekripsi ID Transaksi
            $idtransaksi = decrypt_url($idt);

            // Jika hasil dekripsi kosong atau tidak valid, lempar exception
            if (!$idtransaksi) {
                throw new \Exception("Parameter transaksi tidak valid.");
            }

            // 2. Cek status transaksi (Hanya proses jika belum sukses/settlement)
            $cekTransaksi = $this->transaksiModel
                ->where('idtransaksi', $idtransaksi)
                ->where('status', 'M')
                ->get()->getRowObject();

            if (empty($cekTransaksi)) {
                // Gunakan transaksi database untuk memastikan data konsisten
                $db->transBegin();
                $tgl_mulai = date('Y-m-d H:i:s');
                $tgl_exp   = date('Y-m-d H:i:s', strtotime('+ 1 day', strtotime($tgl_mulai)));

                $this->transaksiModel
                    ->where('idtransaksi', $idtransaksi)
                    ->set('tgl_mulai', $tgl_mulai)
                    ->set('tgl_exp', $tgl_exp)
                    ->set('jenis_bayar', 'manual')
                    ->update();

                if ($db->transStatus() === false) {
                    $db->transRollback();
                    throw new \Exception("Gagal memperbarui metode pembayaran.");
                }

                $db->transCommit();
            }

            return redirect()->to(base_url("sw-siswa/transaksi/pesan-bayar/" . encrypt_url($idtransaksi)));
        } catch (\Exception $e) {
            // Rollback jika terjadi kesalahan di tengah jalan
            if ($db->transStatus() === false) {
                $db->transRollback();
            }

            return redirect()->back()->with('pesan', $e->getMessage());
        }
    }

    public function UploadBuktibayar()
    {
        $db = \Config\Database::connect();
        // Inisialisasi library Emailer
        try {
            $db->transBegin();

            $file = $this->request->getFile('bukti_bayar');
            if (!$file || !$file->isValid()) {
                throw new \Exception("File bukti pembayaran tidak valid atau tidak ditemukan.");
            }

            // 1. Proses Upload & Resizing
            $newName = $file->getRandomName();
            $path = FCPATH . 'uploads/transaksi';
            $thumbnail_path = $path . '/thumbnails';

            if ($file->move($path, $newName)) {
                // Manipulasi Gambar (Thumbnail & Resize)
                $this->image->withFile($path . '/' . $newName)
                    ->resize(1012, 1012, true, 'auto')
                    ->save($thumbnail_path . '/' . $newName, 80);

                // Hapus file asli untuk menghemat storage (sesuai logika lama Anda)
                if (file_exists($path . '/' . $newName)) {
                    unlink($path . '/' . $newName);
                }
            }

            // 2. Update Database (Transaksi & Affiliate)
            $idTransaksi = $this->request->getVar('idtransaksi');

            $this->transaksiModel->update($idTransaksi, [
                'status'           => 'V',
                'tgl_pembayaran'   => date("Y-m-d H:i:s"),
                'bukti_pembayaran' => $newName
            ]);

            $this->affiliateCommissionModel
                ->where('id_transaksi', $idTransaksi)
                ->set('status', 'paid')
                ->update();

            // 3. Notifikasi Admin (Internal)
            send_notif(
                '1',
                "Menunggu verifikasi pembayaran",
                "Silahkan admin untuk verifikasi pembayaran.",
                base_url('sw-admin/transaksi')
            );

            // Commit Transaksi Database
            if ($db->transStatus() === false) {
                $db->transRollback();
                throw new \Exception("Gagal menyimpan data transaksi.");
            }

            $db->transCommit();

            // 4. Kirim Email ke Siswa menggunakan Library Emailer
            $subject = 'BUKTI PEMBAYARAN BERHASIL DIUPLOAD';
            $message = '
            <div style="color: #000; padding: 20px; font-family: sans-serif; line-height: 1.6;">
                <div style="font-size: 20px; color: #1C3FAA; font-weight: bold; margin-bottom: 20px;">
                    BUKTI PEMBAYARAN BERHASIL DIUPLOAD
                </div>
                <p>Halo, <strong>' . session('nama') . '</strong></p>
                <p>Anda telah berhasil melakukan upload bukti pembayaran. Mohon tunggu, admin kami akan segera memverifikasi transaksi tersebut.</p>
                
                <div style="margin: 30px 0;">
                    <a href="' . base_url('sw-siswa/transaksi') . '" style="background: #1C3FAA; color: #fff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                        Buka Dashboard
                    </a>
                </div>

                <p style="font-size: 13px; color: #666;">
                    Salam,<br>
                    <strong>Team KelasBrevet</strong><br>
                    KBIG Office - Jl. Sawo Raya, Lampung<br>
                    Whatsapp: 0821-8074-4966
                </p>
            </div>';

            // Panggil library global
            $this->emailer->send(session('email'), $subject, $message);
            return redirect()->to('sw-siswa/transaksi')->with('success', 'Upload bukti pembayaran berhasil, mohon tunggu verifikasi admin.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function midtransBayar($idt)
    {
        $idtransaksi = decrypt_url($idt);

        // Cek apakah sudah pernah diproses ke Midtrans (status 'M')
        $cekTransaksi = $this->transaksiModel->where('idtransaksi', $idtransaksi)->where('status', 'M')->get()->getRowObject();

        if (empty($cekTransaksi)) {

            // --- POWERFUL MIDTRANS CONFIGURATION ---
            // Mengambil status dari helper setting. 
            // Jika value di database "False" atau "0", $isProduction akan bernilai false.
            \Midtrans\Config::$serverKey    = setting('midtrans_server_key');
            \Midtrans\Config::$isProduction = filter_var(setting('midtrans_is_production'), FILTER_VALIDATE_BOOLEAN);
            \Midtrans\Config::$isSanitized  = true;
            \Midtrans\Config::$is3ds          = true;

            $data = $this->transaksiModel
                ->join('siswa', 'transaksi.idsiswa=siswa.id_siswa')
                ->where('transaksi.idtransaksi', $idtransaksi)
                ->where('transaksi.status', 'P')->get()->getRowObject();

            if (!$data) {
                return redirect()->back()->with('error', 'Transaksi tidak ditemukan atau sudah diproses.');
            }

            // Kalkulasi Harga (Tetap sesuai logika asli)
            $diskon         = ($data->nominal * $data->diskon) / 100;
            $totalDiskon    = $data->nominal - $diskon;
            $diskon_voucher = ($totalDiskon * $data->voucher) / 100;
            $gross_amount   = round($totalDiskon - $diskon_voucher);

            $detailTransaksi = $this->detailTransaksiModel->where('idtransaksi', $idtransaksi)->get()->getResultObject();
            $dataItem = array();
            $total_item_price = 0;

            foreach ($detailTransaksi as $rows) {
                $price = (int)$rows->prince;
                $dataItem[] = array(
                    'id'       => $rows->iddetailtransaksi,
                    'price'    => $price,
                    'quantity' => (int)$rows->quantity,
                    'name'     => substr($rows->name, 0, 50)
                );
                $total_item_price += ($price * (int)$rows->quantity);
            }

            // Penyesuaian diskon agar item_details sinkron dengan gross_amount (Syarat Mutlak Midtrans)
            $selisih = $gross_amount - $total_item_price;
            if ($selisih != 0) {
                $dataItem[] = array(
                    'id'       => 'DISC-VOUCHER',
                    'price'    => $selisih,
                    'quantity' => 1,
                    'name'     => 'Potongan Harga / Voucher'
                );
            }

            $params = array(
                'transaction_details' => array(
                    'order_id'     => $data->idtransaksi, // Unik ID agar tidak ditolak Midtrans jika re-payment
                    'gross_amount' => $gross_amount,
                ),
                'item_details'     => $dataItem,
                'customer_details' => array(
                    'first_name' => $data->nama_siswa,
                    'email'      => $data->email,
                    'phone'      => $data->hp,
                    'billing_address' => array(
                        "first_name" => $data->nama_siswa,
                        "email"      => $data->email,
                        "phone"      => $data->hp,
                        "country_code" => "IDN"
                    ),
                ),
            );

            try {
                // Dapatkan Snap Token
                $snapToken = \Midtrans\Snap::getSnapToken($params);

                // Update database
                $this->transaksiModel
                    ->where('idtransaksi', $idtransaksi)
                    ->set('status', 'M')
                    ->set('token', $snapToken)
                    ->set('jenis_bayar', 'online')
                    ->update();

                return $this->response->setJSON([
                    'status'     => true,
                    'snap_token' => $snapToken,
                    'csrf_hash'  => csrf_hash()
                ]);

            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'status'    => false,
                    'message'   => $e->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
            }
        } else {
            return $this->response->setJSON([
                'status'     => true,
                'snap_token' => $cekTransaksi->token,
                'csrf_hash'  => csrf_hash()
            ]);
        }
    }
}

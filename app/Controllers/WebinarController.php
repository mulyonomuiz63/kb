<?php

namespace App\Controllers;

use App\Libraries\Emailer;
use App\Models\WebinarSesiModel;
use App\Models\SiswaModel; // Sesuaikan dengan model siswa Anda
use App\Libraries\SeoHelper;
use App\Models\DetailTransaksiModel;
use App\Models\PaketModel;
use App\Models\TransaksiModel;

class WebinarController extends BaseController
{
    protected  $seo;
    protected  $sesiModel;
    protected  $siswaModel;
    protected  $paketModel;
    protected  $transaksiModel;
    protected  $detailTransaksiModel;
    protected  $emailer;

    public function __construct()
    {
        $this->seo = new SeoHelper();
        $this->sesiModel = new WebinarSesiModel();
        $this->siswaModel = new SiswaModel();
        $this->paketModel = new PaketModel();
        $this->transaksiModel = new TransaksiModel();
        $this->detailTransaksiModel = new DetailTransaksiModel();
        $this->emailer = new Emailer();
    }

    public function index($slug = 'marathon-update-perpajakan-2026')
    {
        // untuk breadcrumb 
        $breadcrumbItems = [
            "Home" => base_url(),
        ];

        // 1. Ambil data dari model
        $katalog_webinar = $this->sesiModel->getPaketWebinarLengkap($slug);

        // Ambil nilai diskon keseluruhan dari database (Asumsi nama fieldnya 'diskon' di tabel paket)
        // Sesuaikan '$katalog_webinar->diskon' dengan nama kolom diskon di database Anda
        $diskonKeseluruhan = isset($katalog_webinar->diskon) ? $katalog_webinar->diskon : 10;

        // 2. Manipulasi data untuk menambahkan harga_coret dan menghitung diskon pada setiap sesi
        if ($katalog_webinar && !empty($katalog_webinar->sesi)) {
            foreach ($katalog_webinar->sesi as &$sesi) {

                if (isset($sesi['harga_sesi']) && $sesi['harga_sesi'] > 0) {

                    // Cek jika ada diskon khusus per sesi, jika tidak gunakan diskon keseluruhan
                    $diskonAktif = isset($sesi['diskon']) ? $sesi['diskon'] : $diskonKeseluruhan;

                    if ($diskonAktif > 0 && $diskonAktif <= 100) {
                        // 1. Simpan harga asli ke harga_coret (Misal: 200.000)
                        $sesi['harga_coret'] = $sesi['harga_sesi'];

                        // 2. Hitung harga bayar setelah diskon (Misal: 200.000 - 10% = 180.000)
                        $potonganDiskon = $sesi['harga_sesi'] * ($diskonAktif / 100);
                        $sesi['harga_sesi'] = $sesi['harga_sesi'] - $potonganDiskon;
                    } else {
                        // Jika tidak ada diskon
                        $sesi['harga_coret'] = $sesi['harga_sesi'];
                    }
                } else {
                    $sesi['harga_coret'] = 0; // Jika sesi gratis
                }
            }
        }

        // 3. Masukkan ke array $data
        $data['katalog_webinar'] = $katalog_webinar;
        $data['siswa'] = $this->siswaModel->where('id_siswa', session()->get('id'))->first();

        $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
        $schema = $schemaBreadcrumb;
        $data['schema'] = $schema;

        return view('webinar/index', $data);
    }

    // Memproses Pendaftaran
    public function daftar()
    {
        // 1. Sanitasi dan Casting Input untuk mencegah manipulasi data
        $idpaket      = (int)$this->request->getPost('idpaket');
        $email        = $this->request->getPost('email', FILTER_SANITIZE_EMAIL);
        $nama_siswa    = esc($this->request->getPost('nama')); // Diubah jadi 'nama' menyesuaikan form HTML
        $hp           = esc($this->request->getPost('hp'));
        $sesi_terpilih = $this->request->getPost('id_sesi'); // Bentuknya Array

        // Jika user iseng bypass HTML Required dan tidak memilih sesi satupun
        if (empty($sesi_terpilih) || !is_array($sesi_terpilih)) {
            return redirect()->back()->withInput()->with('error', 'Pilih minimal satu sesi webinar.');
        }

        $rules = [
            'email' => [
                // is_unique dihapus karena sistem Anda memperbolehkan user lama beli paket baru
                'rules'  => 'required|valid_email',
                'errors' => [
                    'required'    => 'Email tidak boleh kosong.',
                    'valid_email' => 'Format email menyimpang (Gunakan standar: contoh@mail.com).'
                ]
            ],
            'hp' => [
                // is_unique dihapus dengan alasan yang sama
                'rules'  => 'required|numeric|min_length[9]|max_length[15]',
                'errors' => [
                    'required'   => 'Nomor HP harus diisi.',
                    'numeric'    => 'Nomor HP hanya boleh berisi angka.',
                    'min_length' => 'Nomor HP minimal terdiri dari 9 digit.',
                    'max_length' => 'Nomor HP maksimal terdiri dari 15 digit.'
                ]
            ],
            'nama' => [
                'rules'  => 'required|alpha_numeric_space|min_length[3]|max_length[60]',
                'errors' => [
                    'required'            => 'Nama harus diisi.',
                    'alpha_numeric_space' => 'Nama hanya boleh huruf, angka, dan spasi.',
                    'min_length'          => 'Nama terlalu pendek.',
                    'max_length'          => 'Nama maksimal 60 karakter.'
                ]
            ],
        ];

        // Jalankan Validasi Sisi Server
        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $errorMsg = implode(' ', $errors);
            return redirect()->to('marathon-perpajakan')->withInput()->with('error', "'" . str_replace(["\r", "\n"], '', $errorMsg) . "'");
        }

        if (!is_valid_domain($email)) {
            return redirect()->to('marathon-perpajakan')->withInput()->with('error', 'Domain email tidak valid.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 2. Cek apakah siswa sudah ada, jika belum otomatis daftar
        $cekSiswa = $this->siswaModel->where('email', $email)->first();
        if ($cekSiswa) {
            $id_siswa = $cekSiswa['id_siswa'];
        } else {
            $randomPassword = random_string('alnum', 8);
            $hashedPassword = password_hash($randomPassword, PASSWORD_DEFAULT);
            $data_siswa = array(
                'no_induk_siswa' => rand(1000000, 9000000),
                'nama_siswa'     => $nama_siswa,
                'email'          => $email,
                'password'       => $hashedPassword,
                'kelas'          => 1,
                'role'           => 2,
                'is_active'      => 1,
                'date_created'   => time(),
                'avatar'         => 'default.jpg',
                'hp'             => $hp,
            );

            $this->siswaModel->insert($data_siswa);
            $id_siswa = $this->siswaModel->insertID();
            $subject = 'SELAMAT ANDA BERHASIL TERDAFTAR DI KELASBREVET';
            $message = '
            <div style="color: #000; padding: 10px;">
                <div style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; font-size: 20px; color: #1C3FAA; font-weight: bold;">
                    INFORMASI PENDAFTARAN</div>
                <br>
                <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Hallo ' . substr($nama_siswa, 0, 10) . ' <br>
                    <span style="color: #000;">Kami menambahkan anda ke dalam kelasBrevet 
                    <br>Silahkan login ke website kelasbrevet untuk mengikuti webinar:</span></p>
                <table style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">
                    <tr><td>Nama</td><td> : ' . substr($nama_siswa, 0, 10) . '</td></tr>
                    <tr><td>Email</td><td> : ' . $email . '</td></tr>
                    <!-- NOTE: Password yang dikirim HARUS randomPassword asli, bukan Hash-nya -->
                    <tr><td>Password</td><td> : ' . $randomPassword . '</td></tr> 
                </table>
                <br>
                    <a href="' . base_url("auth/") . '"  style="display: inline-block; background: #1C3FAA; color: #fff;margin:10px; text-decoration: none; border-radius: 5px; text-align: center; line-height: 30px; font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; padding: 5px 20px;">Login</a>
            </div>';

            // Kirim Email
            $this->emailer->send($email, $subject, $message);
        }

        // ==============================================================================
        // UPGRADE TAMBAHAN: Cek Jika Peserta Sudah Punya Paket/Sesi Ini (Gratis/Berbayar)
        // ==============================================================================
        $cekPaketAktif = $db->table('transaksi')
            ->join('detail_transaksi', 'transaksi.idtransaksi = detail_transaksi.idtransaksi')
            ->where('transaksi.idsiswa', $id_siswa)
            ->whereIn('transaksi.status', ['M', 'S']) // 'S' = Lunas/Gratis (Selesai), 'M' = Menunggu Pembayaran
            ->whereIn('detail_transaksi.idsesi', $sesi_terpilih)
            ->get()
            ->getRow();

        if ($cekPaketAktif) {
            $db->transRollback(); // Batalkan seluruh query sebelumnya (termasuk insert siswa jika baru)
            return redirect()->to('marathon-perpajakan')->withInput()->with('error', 'Anda sudah memiliki paket webinar yang aktif atau sedang menunggu pembayaran.');
        }
        // ==============================================================================


        // 3. Proses Pembayaran
        $tgl_mulai = date('Y-m-d H:i:s');
        $tgl_exp   = date('Y-m-d H:i:s', strtotime('+ 1 day', strtotime($tgl_mulai)));

        // Keamanan: Pastikan paket yang dibeli ada di database
        $dataPaket = $this->paketModel->select('paket.*, diskon.diskon, sum(webinar_sesi.harga_sesi) as harga_sesi')
            ->join('diskon', 'paket.iddiskon = diskon.iddiskon', 'left')
            ->join('detail_paket', 'paket.idpaket=detail_paket.idpaket', 'left')
            ->join('webinar_sesi', 'detail_paket.id_sesi=webinar_sesi.id_sesi', 'left')
            ->where('paket.idpaket', $idpaket)
            ->whereIn('webinar_sesi.id_sesi', $sesi_terpilih)
            ->get()->getRow();

        if (empty($dataPaket)) {
            return redirect()->to('marathon-perpajakan')->with('error', 'Data Paket Webinar tidak ditemukan.');
        }

        if ((float) $dataPaket->harga_sesi <= 0) {
            $status = 'S';
            $tgl_pembayaran = $tgl_mulai;
        } else {
            $status = 'M';
            $tgl_pembayaran = null;
        }

        $dataInsert = [
            'idsiswa'      => $id_siswa,
            'nominal'      => $dataPaket->harga_sesi,
            'diskon'       => $dataPaket->diskon,
            'status'       => $status, // Menunggu atau Selesai
            'tgl_exp'      => $tgl_exp,
            'tgl_drop'     => $tgl_exp,
            'tgl_pembayaran' => $tgl_pembayaran,
            'jenis_bayar'  => 'online',
            'jenis_paket'  => $dataPaket->jenis_paket
        ];

        $this->transaksiModel->insert($dataInsert);
        $idtransaksi = $this->transaksiModel->insertID();

        // PERBAIKAN KRUSIAL: Filter sesi HANYA mengambil yang dicentang oleh user
        $detailPaket = $db->table('detail_paket')
            ->select('
                detail_paket.*, 
                COALESCE(webinar_sesi.harga_sesi, 0) as harga_sesi,
                webinar_sesi.nama_sesi, 
                ujian_master.nama_ujian, 
                mapel.nama_mapel,
                COALESCE(NULLIF(webinar_sesi.nama_sesi, ""), NULLIF(ujian_master.nama_ujian, ""), NULLIF(mapel.nama_mapel, ""), "Item Paket") as nama_final
            ')
            ->join('webinar_sesi', 'detail_paket.id_sesi = webinar_sesi.id_sesi', 'left')
            ->join('ujian_master', 'detail_paket.id_ujian = ujian_master.id_ujian', 'left')
            ->join('mapel', 'detail_paket.id_mapel = mapel.id_mapel', 'left')
            ->where('detail_paket.idpaket', $idpaket)

            // PERBAIKAN: Mengelompokkan kondisi OR
            ->groupStart()
            ->whereIn('detail_paket.id_sesi', $sesi_terpilih) // Ambil sesi yang dicentang
            ->orWhere('detail_paket.id_sesi', 0) // ATAU ambil yang id_sesi-nya 0 (Ujian / Materi)
            ->orWhere('detail_paket.id_sesi IS NULL') // Jaga-jaga jika di database tersimpan sebagai NULL
            ->groupEnd()

            ->get()
            ->getResultObject();

        if (!empty($detailPaket) && is_array($detailPaket)) {
            // 1. Hitung Total Item dan Total Harga Keseluruhan
            $jmlDetail = count($detailPaket);
            $totalHargaKeseluruhan = 0;

            foreach ($detailPaket as $row) {
                $totalHargaKeseluruhan += (float) $row->harga_sesi;
            }

            // 2. Hitung Harga Rata-Rata per Item dan Sisa Pembagian
            // Menggunakan floor agar hasilnya selalu bilangan bulat (integer)
            $hargaRataRata = floor($totalHargaKeseluruhan / $jmlDetail);
            $sisaHarga = $totalHargaKeseluruhan - ($hargaRataRata * $jmlDetail);

            $detailTransaksi = [];
            $total_item_price = 0;

            foreach ($detailPaket as $index => $rows) {
                $itemName = !empty($rows->nama_sesi) ? $rows->nama_sesi : (!empty($rows->nama_ujian) ? $rows->nama_ujian : (!empty($rows->nama_mapel) ? $rows->nama_mapel : 'Item Paket'));

                // 3. Set Harga Item
                $hargaFinalItem = $hargaRataRata;

                // Tambahkan sisa bagi (selisih) HANYA ke item pertama agar total keseluruhan tetap sama persis (mencegah error Midtrans)
                if ($index === 0) {
                    $hargaFinalItem += $sisaHarga;
                }

                $detailTransaksi[] = [
                    'idtransaksi' => $idtransaksi,
                    'idpaket'     => $idpaket,
                    'idmapel'     => $rows->id_mapel ?? '0',
                    'idsesi'      => $rows->id_sesi ?? '0',
                    'prince'      => $hargaFinalItem,
                    'quantity'    => 1,
                    'name'        => $itemName
                ];
            }

            $this->detailTransaksiModel->insertBatch($detailTransaksi);

            // 4. Proses Transaksi (Midtrans jika berbayar / Langsung selesai jika gratis)
            $data = $this->transaksiModel
                ->join('siswa', 'transaksi.idsiswa=siswa.id_siswa')
                ->where('transaksi.idtransaksi', $idtransaksi)
                ->get()->getRowObject();

            $diskon         = ($data->nominal * $data->diskon) / 100;
            $totalDiskon    = $data->nominal - $diskon;
            // Asumsi kolom voucher ada di tabel, jika tidak ada, ubah $data->voucher menjadi 0
            $voucher        = isset($data->voucher) ? $data->voucher : 0;
            $diskon_voucher = ($totalDiskon * $voucher) / 100;
            $gross_amount   = round($totalDiskon - $diskon_voucher);

            $snapToken = null;

            if ($gross_amount > 0) {
                \Midtrans\Config::$serverKey    = setting('midtrans_server_key');
                \Midtrans\Config::$isProduction = filter_var(setting('midtrans_is_production'), FILTER_VALIDATE_BOOLEAN);
                \Midtrans\Config::$isSanitized  = true;
                \Midtrans\Config::$is3ds        = true;

                $detailTransaksi = $this->detailTransaksiModel->where('idtransaksi', $idtransaksi)->get()->getResultObject();
                $dataItem = array();

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

                // Penyesuaian diskon agar item_details sinkron dengan gross_amount
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
                        // Tambahkan time() agar ID pesanan Unik jika user mencoba transaksi ulang
                        'order_id'     => $data->idtransaksi . '-' . time(),
                        'gross_amount' => $gross_amount,
                    ),
                    'item_details'     => $dataItem,
                    'customer_details' => array(
                        'first_name' => $data->nama_siswa,
                        'email'      => $data->email,
                        'phone'      => $data->hp,
                        'billing_address' => array(
                            "first_name"   => $data->nama_siswa,
                            "email"        => $data->email,
                            "phone"        => $data->hp,
                            "country_code" => "IDN"
                        ),
                    ),
                );
                $snapToken = \Midtrans\Snap::getSnapToken($params);

                // Update database dengan Token
                $this->transaksiModel
                    ->where('idtransaksi', $idtransaksi)
                    ->set('token', $snapToken)
                    ->update();
            }
        }

        $db->transComplete(); // Selesai query

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan pada server saat mendaftar.');
        }

        // 5. Redirect Berdasarkan Jenis Paket (Berbayar via Midtrans, Gratis kembali ke halaman semula)
        if ($gross_amount > 0) {
            return redirect()->to('webinar/invoice')->with('success', 'Pendaftaran berhasil, silakan selesaikan pembayaran!')->with('snapToken', $snapToken);
        } else {
            return redirect()->to('marathon-perpajakan')->with('success', 'Pendaftaran berhasil, Anda telah terdaftar sebagai peserta webinar.');
        }
    }
    public function invoice()
    {
        // Jika tidak ada token (misal user asal buka URL /invoice), kembalikan ke home
        if (!session()->getFlashdata('snapToken') && !session()->getFlashdata('success')) {
            return redirect()->to('/webinar');
        }

        return view('webinar/invoice');
    }
}

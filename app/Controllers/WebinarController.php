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

    public function index($slug = 'marathon-update-perpajakan-session-2-2026')
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
        $nama_siswa    = esc($this->request->getPost('nama'));
        $hp           = esc($this->request->getPost('hp'));
        $sesi_terpilih = $this->request->getPost('id_sesi'); // Bentuknya Array

        // Jika user iseng bypass HTML Required dan tidak memilih sesi satupun
        if (empty($sesi_terpilih) || !is_array($sesi_terpilih)) {
            return redirect()->back()->withInput()->with('error', 'Pilih minimal satu sesi webinar.');
        }

        // ==============================================================================
        // UPGRADE 1: VALIDASI DINAMIS (Berdasarkan Session Login / User Baru)
        // ==============================================================================
        $id_session = session()->get('id'); 
        
        $rules = [
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

        if ($id_session) {
            // JIKA USER SUDAH LOGIN (SESSION ADA)
            $rules['email'] = [
                'rules'  => 'required|valid_email', // Tidak perlu is_unique karena memakai emailnya sendiri
                'errors' => [
                    'required'    => 'Email tidak boleh kosong.',
                    'valid_email' => 'Format email menyimpang (Gunakan standar: contoh@mail.com).'
                ]
            ];
            $rules['hp'] = [
                'rules'  => "required|numeric|min_length[9]|max_length[15]|is_unique[siswa.hp,id_siswa,{$id_session}]",
                'errors' => [
                    'required'   => 'Nomor HP harus diisi.',
                    'numeric'    => 'Nomor HP hanya boleh berisi angka.',
                    'min_length' => 'Nomor HP minimal 9 digit.',
                    'max_length' => 'Nomor HP maksimal 15 digit.',
                    'is_unique'  => 'Nomor HP ini sudah digunakan oleh akun lain.'
                ]
            ];
        } else {
            // JIKA USER BARU (TIDAK ADA SESSION)
            $rules['email'] = [
                'rules'  => 'required|valid_email|is_unique[siswa.email]',
                'errors' => [
                    'required'    => 'Email tidak boleh kosong.',
                    'valid_email' => 'Format email menyimpang.',
                    'is_unique'   => 'Email ini sudah terdaftar,'
                ]
            ];
            $rules['hp'] = [
                'rules'  => 'required|numeric|min_length[9]|max_length[15]|is_unique[siswa.hp]',
                'errors' => [
                    'required'   => 'Nomor HP harus diisi.',
                    'numeric'    => 'Nomor HP hanya boleh berisi angka.',
                    'min_length' => 'Nomor HP minimal 9 digit.',
                    'max_length' => 'Nomor HP maksimal 15 digit.',
                    'is_unique'  => 'Nomor HP ini sudah terdaftar'
                ]
            ];
        }

        // Jalankan Validasi Sisi Server
        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $errorMsg = implode(' ', $errors);
            return redirect()->to('marathon-perpajakan')->withInput()->with('error', str_replace(["\r", "\n"], '', $errorMsg));
        }

        if (!is_valid_domain($email)) {
            return redirect()->to('marathon-perpajakan')->withInput()->with('error', 'Domain email tidak valid.');
        }

        $db = \Config\Database::connect();

        // 2. Ambil Data Paket Lebih Awal (Untuk Cek Harga Gratis / Berbayar)
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

        $isCurrentGratis = ((float) $dataPaket->harga_sesi <= 0);

        // ==============================================================================
        // UPGRADE 2 & 3: CEK DOBEL TRANSAKSI & VALIDASI GRATIS VS BERBAYAR
        // Pengecekan dilakukan SEBELUM Database Transaction / Insert Siswa
        // ==============================================================================
        $cekSiswa = $this->siswaModel->where('email', $email)->first();
        $id_siswa = $cekSiswa ? $cekSiswa['id_siswa'] : null;

        if ($id_siswa) {
            // [CEK ANTI SPAM / KLIK DOBEL] - Cek apakah user ini membuat transaksi di 10 detik terakhir
            $recentTx = $db->table('transaksi')
                ->where('idsiswa', $id_siswa)
                ->where('created_at >=', date('Y-m-d H:i:s', time() - 10))
                ->countAllResults();

            if ($recentTx > 0) {
                return redirect()->to('marathon-perpajakan')->withInput()->with('error', 'Sistem sedang memproses pendaftaran Anda. Mohon jangan klik tombol daftar berulang kali.');
            }

            // [CEK DUPLIKASI PAKET] - Boleh beli lagi JIKA beda versi (Gratis vs Berbayar)
            $cekPaketAktif = $db->table('transaksi')
                ->join('detail_transaksi', 'transaksi.idtransaksi = detail_transaksi.idtransaksi')
                ->where('transaksi.idsiswa', $id_siswa)
                ->whereIn('transaksi.status', ['M', 'S']) // M = Menunggu, S = Selesai/Lunas/Gratis
                ->whereIn('detail_transaksi.idsesi', $sesi_terpilih);
            
            // Logika pembeda:
            if ($isCurrentGratis) {
                $cekPaketAktif->where('transaksi.nominal <=', 0); // Cari riwayat yang Gratis juga
            } else {
                $cekPaketAktif->where('transaksi.nominal >', 0); // Cari riwayat yang Berbayar juga
            }

            $cekPaketAktif = $cekPaketAktif->get()->getRow();

            if ($cekPaketAktif) {
                $tipePaket = $isCurrentGratis ? 'Gratis' : 'Premium/Berbayar';
                return redirect()->to('marathon-perpajakan')->withInput()->with('error', "Anda sudah memiliki paket {$tipePaket} untuk sesi ini. Silahkan login ke akun Anda untuk melihat paket webinar.");
            }
        }
        // ==============================================================================

        $db->transStart();

        // 3. Proses Insert/Update Siswa & Kirim Email (Logika Asli Anda)
        if ($cekSiswa) {
            $data_siswa = array('hp' => $hp);
            $this->siswaModel->update($id_siswa, $data_siswa);

            $subject = 'SELAMAT ANDA BERHASIL TERDAFTAR DI DIWEBINAR KELASBREVET';
            $message = '
            <div style="color: #000; padding: 10px;">
                <div style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; font-size: 20px; color: #1C3FAA; font-weight: bold;">
                    INFORMASI PENDAFTARAN WEBINAR</div> 
                <br>
                <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Hallo ' . substr($nama_siswa, 0, 10) . ' <br>
                    <span style="color: #000;">Kami menambahkan anda ke dalam webinar kelasBrevet. 
                    <br>Silahkan login ke website kelasbrevet untuk mengikuti webinar:</span></p>
                <br>
                    <a href="' . base_url("auth/") . '"  style="display: inline-block; background: #1C3FAA; color: #fff;margin:10px; text-decoration: none; border-radius: 5px; text-align: center; line-height: 30px; font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; padding: 5px 20px;">Login</a>
            </div>';

            $this->emailer->send($email, $subject, $message);
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
            $subject = 'SELAMAT ANDA BERHASIL TERDAFTAR DI DIWEBINAR KELASBREVET';
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
                    <tr><td>Password</td><td> : ' . $randomPassword . '</td></tr> 
                </table>
                <br>
                    <a href="' . base_url("auth/") . '"  style="display: inline-block; background: #1C3FAA; color: #fff;margin:10px; text-decoration: none; border-radius: 5px; text-align: center; line-height: 30px; font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; padding: 5px 20px;">Login</a>
            </div>';

            $this->emailer->send($email, $subject, $message);
        }

        // 4. Proses Pembayaran
        $tgl_mulai = date('Y-m-d H:i:s');
        $tgl_exp   = date('Y-m-d H:i:s', strtotime('+ 1 day', strtotime($tgl_mulai)));

        if ($isCurrentGratis) {
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
            'jenis_paket'  => $dataPaket->jenis_paket,
            'created_at'   => $tgl_mulai
        ];

        $this->transaksiModel->insert($dataInsert);
        $idtransaksi = $this->transaksiModel->insertID();

        // 5. Query Builder Detail Paket (Sesuai milik Anda)
        $builder = $db->table('detail_paket')
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
            ->where('detail_paket.idpaket', $idpaket);

        $builder->groupStart()
            ->whereIn('detail_paket.id_sesi', $sesi_terpilih);

        if (!$isCurrentGratis) {
            $builder->orWhere('detail_paket.id_sesi', 0)
                ->orWhere('detail_paket.id_sesi IS NULL');
        }

        $builder->groupEnd();

        $detailPaket = $builder->get()->getResultObject();
        $gross_amount = 0; 

        if (!empty($detailPaket) && is_array($detailPaket)) {
            $jmlDetail = count($detailPaket);
            $totalHargaKeseluruhan = 0;

            foreach ($detailPaket as $row) {
                $totalHargaKeseluruhan += (float) $row->harga_sesi;
            }

            $hargaRataRata = floor($totalHargaKeseluruhan / $jmlDetail);
            $sisaHarga = $totalHargaKeseluruhan - ($hargaRataRata * $jmlDetail);

            $detailTransaksi = [];
            $total_item_price = 0;

            foreach ($detailPaket as $index => $rows) {
                $itemName = !empty($rows->nama_sesi) ? $rows->nama_sesi : (!empty($rows->nama_ujian) ? $rows->nama_ujian : (!empty($rows->nama_mapel) ? $rows->nama_mapel : 'Item Paket'));

                $hargaFinalItem = $hargaRataRata;
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

            // 6. Integrasi Midtrans
            $data = $this->transaksiModel
                ->join('siswa', 'transaksi.idsiswa=siswa.id_siswa')
                ->where('transaksi.idtransaksi', $idtransaksi)
                ->get()->getRowObject();

            $diskon         = ($data->nominal * $data->diskon) / 100;
            $totalDiskon    = $data->nominal - $diskon;
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

                $this->transaksiModel
                    ->where('idtransaksi', $idtransaksi)
                    ->set('token', $snapToken)
                    ->update();
            }
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan pada server saat mendaftar.');
        }

        // 7. Selesai
        if ($gross_amount > 0) {
            return redirect()->to('webinar/invoice')->with('success', 'Pendaftaran berhasil, silakan selesaikan pembayaran!')->with('snapToken', $snapToken);
        } else {
            return redirect()->to('marathon-perpajakan')->with('success', 'Pendaftaran berhasil, Anda telah terdaftar sebagai peserta webinar, informasi lengkapnya akan dikirim ke email Anda.');
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

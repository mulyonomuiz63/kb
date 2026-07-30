<?php

namespace App\Controllers;

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

    public function __construct()
    {
        $this->seo = new SeoHelper();
        $this->sesiModel = new WebinarSesiModel();
        $this->siswaModel = new SiswaModel();
        $this->paketModel = new PaketModel();
        $this->transaksiModel = new TransaksiModel();
        $this->detailTransaksiModel = new DetailTransaksiModel();
    }

    public function index()
    {
        //untuk breadcrumb 
        $breadcrumbItems = [
            "Home" => base_url(),
        ];

        $data['katalog_webinar'] = $this->sesiModel->getPaketWebinarLengkap(72);
        // var_dump($data['katalog_webinar']);
        $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
        $schema = $schemaBreadcrumb;
        $data['schema'] = $schema;
        return view('webinar/index', $data);
    }

    // Memproses Pendaftaran
    public function daftar()
    {
        // 1. Sanitasi dan Casting Input untuk mencegah manipulasi data
        $idpaket       = (int)$this->request->getPost('idpaket');
        $email         = $this->request->getPost('email', FILTER_SANITIZE_EMAIL);
        $nama_siswa    = esc($this->request->getPost('nama')); // Diubah jadi 'nama' menyesuaikan form HTML
        $hp            = esc($this->request->getPost('hp'));
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
            return redirect()->back()->withInput()->with('error', "'" . str_replace(["\r", "\n"], '', $errorMsg) . "'");
        }

        if (!is_valid_domain($email)) {
            return redirect()->back()->withInput()->with('error', 'Domain email tidak valid.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 2. Cek apakah siswa sudah ada, jika belum otomatis daftar
        $cekSiswa = $this->siswaModel->where('email', $email)->first();
        if ($cekSiswa) {
            $id_siswa = $cekSiswa['id_siswa'];
            $datasession = [
                'id'       => $cekSiswa['id_siswa'],
                'email'    => $cekSiswa['email'],
                'nama'     => $cekSiswa['nama_siswa'],
                'role'     => $cekSiswa['role'],
                'avatar'   => $cekSiswa['avatar'],
            ];
            session()->set($datasession);
        } else {
            $data_siswa = array(
                'no_induk_siswa' => rand(1000000, 9000000),
                'nama_siswa'     => $nama_siswa,
                'email'          => $email,
                'kelas'          => 1,
                'role'           => 2,
                'is_active'      => 1,
                'date_created'   => time(),
                'avatar'         => 'default.jpg',
                'hp'             => $hp,
            );

            $this->siswaModel->insert($data_siswa);
            $id_siswa = $this->siswaModel->insertID();

            $userBaru = $this->siswaModel->find($id_siswa);

            $datasession = [
                'id'       => $userBaru['id_siswa'],
                'email'    => $userBaru['email'],
                'nama'     => $userBaru['nama_siswa'],
                'role'     => $userBaru['role'],
                'avatar'   => $userBaru['avatar'],
            ];
            session()->set($datasession);
        }


        // 3. Proses Pembayaran
        $tgl_mulai = date('Y-m-d H:i:s');
        $tgl_exp   = date('Y-m-d H:i:s', strtotime('+ 1 day', strtotime($tgl_mulai)));

        // Keamanan: Pastikan paket yang dibeli ada di database
        $dataPaket = $this->paketModel->where('paket.idpaket', $idpaket)->get()->getRow();

        if (empty($dataPaket)) {
            return redirect()->back()->with('error', 'Data Paket Webinar tidak ditemukan.');
        }

        $dataInsert = [
            'idsiswa'      => $cekSiswa['id_siswa'],
            'nominal'      => $dataPaket->nominal_paket,
            'diskon'       => '0',
            'status'       => 'M', // Menunggu
            'v_ujian'      => '0', // Menunggu
            'v_materi'     => '0', // Menunggu
            'tgl_exp'      => $tgl_exp,
            'tgl_drop'     => $tgl_exp,
            'jenis_bayar'  => 'online',
            'jenis_paket'  => $dataPaket->jenis_paket
        ];

        $this->transaksiModel->insert($dataInsert);
        $idtransaksi = $this->transaksiModel->insertID();

        // PERBAIKAN KRUSIAL: Filter sesi HANYA mengambil yang dicentang oleh user
        $detailPaket = $this->sesiModel
            ->where('idpaket', $idpaket)
            ->whereIn('id_sesi', $sesi_terpilih)
            ->get()->getResultObject();

        if (!empty($detailPaket) && is_array($detailPaket)) {
            $detailTransaksi = [];
            $total_item_price = 0;

            foreach ($detailPaket as $rows) {
                $detailTransaksi[] = [
                    'idtransaksi' => $idtransaksi,
                    'idpaket'     => '0',
                    'idmapel'     => '0',
                    'id_sesi'     => $rows->id_sesi,
                    'prince'      => $rows->harga_sesi,
                    'quantity'    => 1,
                    'name'        => $rows->nama_sesi
                ];
            }
            $this->detailTransaksiModel->insertBatch($detailTransaksi);


            // 4. Pembayaran Midtrans
            \Midtrans\Config::$serverKey    = setting('midtrans_server_key');
            \Midtrans\Config::$isProduction = filter_var(setting('midtrans_is_production'), FILTER_VALIDATE_BOOLEAN);
            \Midtrans\Config::$isSanitized  = true;
            \Midtrans\Config::$is3ds        = true;

            // PERBAIKAN LOGIKA: Hanya ambil data yang baru saja diinsert (tanpa filter status P karena kita simpan dengan status M)
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

        $db->transComplete(); // Selesai query

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan pada server saat mendaftar.');
        }

        // 5. Redirect ke Invoice sambil membawa Token
        // Parameter with() menyimpan variabel secara sementara untuk dipanggil di halaman Invoice
        return redirect()->to('webinar/invoice')->with('success', 'Pendaftaran berhasil, silakan selesaikan pembayaran!')->with('snapToken' , $snapToken);
    }
}

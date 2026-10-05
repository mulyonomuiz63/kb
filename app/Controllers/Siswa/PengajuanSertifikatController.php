<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use CodeIgniter\Database\Config;

class PengajuanSertifikatController extends BaseController
{
    protected $db;

    public function __construct()
    {
        helper('setting');
        $this->db = Config::connect();

        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey    = setting('midtrans_server_key');
        \Midtrans\Config::$isProduction = filter_var(setting('midtrans_is_production'), FILTER_VALIDATE_BOOLEAN);
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;
    }

    public function index()
    {
        $id_siswa = session()->get('id');

        // Ambil data langsung untuk dilempar ke View
        $records = $this->db->table('pengajuan_sertifikat a')
            ->select('a.*, b.status, b.token')
            ->join('transaksi b', 'a.idtransaksi = b.idtransaksi', 'left')
            ->where('a.id_siswa', $id_siswa)
            ->orderBy('a.created_at', 'DESC')
            ->get()
            ->getResult();

        $data = [
            'title'     => 'Pengajuan Sertifikat Fisik',
            'pengajuan' => $records // Kirim data ke view
        ];

        return view('siswa/sertifikat/pengajuan-sertifikat', $data);
    }

    // Fungsi datatables() SUDAH DIHAPUS karena tidak dipakai lagi

    public function store()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('auth');
        }

        try {
            $id_siswa       = session()->get('id');
            $nama_penerima  = $this->request->getPost('nama_penerima');
            $no_hp          = $this->request->getPost('no_hp');
            $alamat_lengkap = $this->request->getPost('alamat_lengkap');
            $kode_pos       = $this->request->getPost('kode_pos');
            $total_bayar    = 100000;

            if (empty($nama_penerima) || empty($no_hp) || empty($alamat_lengkap)) {
                throw new \Exception("Pastikan semua field wajib diisi.");
            }

            $this->db->transStart();

            $tgl_mulai = date('Y-m-d H:i:s');
            $tgl_exp   = date('Y-m-d H:i:s', strtotime('+ 1 day', strtotime($tgl_mulai)));

            // 1. Insert ke tabel transaksi
            $this->db->table('transaksi')->insert([
                'idsiswa'        => $id_siswa,
                'nominal'        => $total_bayar,
                'status'         => 'M',
                'tgl_exp'        => $tgl_exp,
                'tgl_drop'       => $tgl_exp,
                'tgl_pembayaran' => null,
                'jenis_bayar'    => 'online',
                'jenis_paket'    => '["sertifikat"]',
                'created_at'     => $tgl_mulai
            ]);
            $id_transaksi = $this->db->insertID();
            $order_id_midtrans = $id_transaksi;

            // 2. Insert ke pengajuan_sertifikat
            $this->db->table('pengajuan_sertifikat')->insert([
                'id_siswa'          => $id_siswa,
                'idtransaksi'       => $id_transaksi,
                'nama_penerima'     => $nama_penerima,
                'no_hp'             => $no_hp,
                'alamat_lengkap'    => $alamat_lengkap,
                'kode_pos'          => $kode_pos,
                'status_pengiriman' => 'menunggu',
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s')
            ]);

            // 3. Insert ke detail_transaksi
            $this->db->table('detail_transaksi')->insert([
                'idtransaksi' => $id_transaksi,
                'idpaket'     => 0,
                'idmapel'     => 0,
                'idsesi'      => 0,
                'prince'      => $total_bayar, // pastikan nama kolom ini memang 'prince' di DB
                'quantity'    => 1,
                'name'        => 'Pengajuan Cetak Sertifikat Fisik'
            ]);

            // 4. Request Token Midtrans
            $transaction = [
                'transaction_details' => [
                    'order_id'     => $order_id_midtrans,
                    'gross_amount' => $total_bayar,
                ],
                'customer_details' => [
                    'first_name' => $nama_penerima,
                    'phone'      => $no_hp,
                ],
            ];
            $snapToken = \Midtrans\Snap::getSnapToken($transaction);

            // 5. Update tabel transaksi
            $this->db->table('transaksi')
                ->where('idtransaksi', $id_transaksi)
                ->update(['token' => $snapToken]);

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                throw new \Exception("Gagal menyimpan ke database.");
            }

            return $this->response->setJSON([
                'status'  => 'success',
                'token'   => $snapToken,
                'message' => 'Pengajuan berhasil dibuat!',
                csrf_token() => csrf_hash()
            ]);

        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => $e->getMessage(),
                csrf_token() => csrf_hash()
            ]);
        }
    }
}
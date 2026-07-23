<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\Emailer;
use App\Models\AffiliateModel;
use App\Models\AffiliateLinkModel;
use App\Models\AffiliateCommissionModel;
use App\Models\AffiliateKlikHarianModel;

class AffiliateController extends BaseController
{
    protected $affiliate;
    protected $affiliateLinkModel;
    protected $komisi;
    protected $affiliateKlikHarianModel;
    protected $emailer;

    public function __construct()
    {
        $this->affiliate = new AffiliateModel();
        $this->affiliateLinkModel = new AffiliateLinkModel();
        $this->komisi    = new AffiliateCommissionModel();
        $this->affiliateKlikHarianModel = new AffiliateKlikHarianModel();
        $this->emailer = new Emailer();
    }

    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'List Affiliate', 'url' => '#'],
        ];
        return view('admin/affiliate/index', $data);
    }

    public function datatables()
    {
        $request = service('request');

        $draw   = (int) $request->getPost('draw');
        $start  = (int) $request->getPost('start');
        $length = (int) $request->getPost('length');
        $search = $request->getPost('search')['value'] ?? null;

        $builder = $this->affiliate
            ->select("
            siswa.nama_siswa,
            affiliates.id_affiliate,
            affiliates.status,
            affiliates.kode_affiliate,
            affiliates.created_at,

            -- 1. Menghitung Total Komisi yang Menunggu Pencairan (Pending / Proses)
            IFNULL(
                SUM(
                    CASE 
                        WHEN c.status = 'approved' AND c.status_penarikan IN ('pending', 'proses')
                        THEN (c.harga * c.komisi / 100)
                        ELSE 0
                    END
                ),
            0) AS total_komisi_pending,

            -- 2. Menghitung Total Komisi yang Sudah Berhasil Dicairkan (Success / Selesai)
            IFNULL(
                SUM(
                    CASE 
                        WHEN c.status = 'approved' AND c.status_penarikan = 'success'
                        THEN (c.harga * c.komisi / 100)
                        ELSE 0
                    END
                ),
            0) AS total_komisi_dicairkan
        ")
            ->join('siswa', 'siswa.id_siswa = affiliates.user_id')
            // Hapus filter 'pending' di sini agar kita bisa mengambil data yang sudah cair juga
            ->join(
                'affiliate_commissions c',
                'c.kode_affiliate = affiliates.kode_affiliate AND c.status = "approved"',
                'left'
            )
            ->where('affiliates.status !=', '2')
            ->groupBy('affiliates.kode_affiliate');

        // 🔎 SEARCH
        if ($search) {
            $builder->groupStart()
                ->like('siswa.nama_siswa', $search)
                ->groupEnd();
        }

        $totalData = $builder->countAllResults(false);

        // 🔥 LOGIKA SORTING (PENGURUTAN)
        // 1. Prioritaskan Nominal Pending/Pencairan paling besar di atas.
        // 2. Yang nominal pendingnya 0 (termasuk yang sudah cair semua) otomatis turun ke bawah.
        $builder->orderBy('total_komisi_pending', 'desc')
            ->orderBy('affiliates.status', 'asc')
            ->orderBy('affiliates.id_affiliate', 'desc')
            ->limit($length, $start);

        $query = $builder->get()->getResult();

        $data = [];

        foreach ($query as $row) {

            switch ($row->status) {
                case '1':
                    $badge = 'badge-success';
                    $text  = 'Approved';
                    $btn = '
                        <a href="' . base_url('sw-admin/affiliate/komisi/' . encrypt_url($row->kode_affiliate)) . '"
                            class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>';
                    break;
                case '0':
                    $badge = 'badge-warning';
                    $text  = 'Pending';
                    $btn = '
                        <a href="' . base_url('sw-admin/affiliate/edit/' . encrypt_url($row->id_affiliate)) . '"
                            class="btn btn-sm btn-outline-warning mr-2">
                            <i class="bi bi-eye"></i>
                        </a>';
                    break;
                default:
                    $badge = 'badge-secondary';
                    $text  = '-';
                    $btn = '';
            }

            // 💡 KATA-KATA PROFESIONAL UNTUK TAMPILAN KOMISI
            $tampilan_komisi = '';

            // Tampilkan Jika ada komisi yang sedang menunggu
            if ($row->total_komisi_pending > 0) {
                $tampilan_komisi .= '
                <div class="text-warning font-weight-bold" title="Menunggu Pencairan">
                    Rp ' . number_format($row->total_komisi_pending, 0, ',', '.') . ' 
                    <small>(Menunggu Pencairan)</small>
                </div>';
            }

            // Tampilkan Jika ada komisi yang sudah berhasil dicairkan
            if ($row->total_komisi_dicairkan > 0) {
                $tampilan_komisi .= '
                <div class="text-success mt-1" style="font-size: 0.85rem;" title="Sudah Dicairkan">
                    <i class="bi bi-check-circle-fill"></i> Rp ' . number_format($row->total_komisi_dicairkan, 0, ',', '.') . ' (Telah Dicairkan)
                </div>';
            }

            // Jika belum ada komisi sama sekali
            if ($row->total_komisi_pending == 0 && $row->total_komisi_dicairkan == 0) {
                $tampilan_komisi = '<span class="text-muted">Rp 0</span>';
            }

            $data[] = [
                '<div class="font-weight-bold">' . $row->nama_siswa . '</div>
             <small class="text-muted">Affiliate User</small>',

                date('d M Y H:i', strtotime($row->created_at)),

                $tampilan_komisi, // <-- Menggunakan variabel UI komisi yang baru

                '<span class="badge ' . $badge . ' px-3 py-2">' . $text . '</span>',

                '<div class="btn-group">
                ' . $btn . '
            </div>'
            ];
        }

        return $this->response->setJSON([
            'draw' => intval($draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalData,
            'data' => $data,
            csrf_token() => csrf_hash() // 🔥 AUTO REFRESH CSRF
        ]);
    }


    public function edit($id)
    {
        try {

            // Pastikan parameter tidak kosong
            if (empty($id)) {
                throw new \Exception('Parameter tidak valid.');
            }

            // Decrypt ID
            $id = decrypt_url($id);

            // Pastikan hasil decrypt berupa angka
            if (!is_numeric($id)) {
                throw new \Exception('ID tidak valid.');
            }

            // Cari data affiliate
            $affiliate = $this->affiliate->find((int) $id);

            if (!$affiliate) {
                throw new \Exception('Data affiliate tidak ditemukan.');
            }

            $data['breadcrumbs'] = [
                ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
                ['title' => 'List Affiliate', 'url' => '#'],
            ];
            $data['affiliate'] = $affiliate;
            return view('admin/affiliate/form', $data);
        } catch (\Throwable $e) {

            log_message('error', 'Affiliate Edit Error: ' . $e->getMessage());

            return redirect()
                ->to(base_url('sw-admin/affiliate'))
                ->with('error', 'Terjadi kesalahan atau data tidak ditemukan.');
        }
    }

    public function store()
    {
        try {

            $id = $this->request->getPost('id_affiliate');

            // Ambil & sanitasi input
            $data = [
                'bank'           => strtoupper(trim($this->request->getPost('bank'))),
                'norek'          => trim($this->request->getPost('norek')),
                'nama_akun_bank' => trim($this->request->getPost('nama_akun_bank')),
                'cabang_bank'    => trim($this->request->getPost('cabang_bank')),
                'status'         => $this->request->getPost('status'),
            ];

            // ===============================
            // UPDATE
            // ===============================
            if ($id) {

                if (!$this->affiliate->update($id, $data)) {
                    throw new \Exception('Gagal memperbarui data affiliate.');
                }

                $cekDataAffiliate = $this->affiliate
                    ->where('id_affiliate', $id)
                    ->get()
                    ->getRowObject();

                if ($cekDataAffiliate) {

                    $statusInput = $data['status'];

                    switch ($statusInput) {
                        case '1':
                            $judul = "Affiliate Disetujui!";
                            $pesan = "Selamat! Pendaftaran affiliate Anda telah diterima. Sekarang Anda bisa mulai menggunakan fitur affiliate.";
                            break;

                        case '2':
                            $judul = "Affiliate Ditolak";
                            $pesan = "Mohon maaf, pendaftaran affiliate Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.";
                            break;

                        case '0':
                            $judul = "Affiliate Pending";
                            $pesan = "Pendaftaran affiliate Anda sedang dalam antrean verifikasi. Mohon tunggu kabar selanjutnya.";
                            break;

                        default:
                            $judul = "Update Affiliate";
                            $pesan = "Ada perubahan status pada pendaftaran affiliate Anda.";
                            break;
                    }

                    // Kirim notifikasi (jika gagal jangan hentikan sistem)
                    try {
                        send_notif(
                            $cekDataAffiliate->user_id,
                            $judul,
                            $pesan,
                            base_url('sw-siswa/affiliatee')
                        );
                    } catch (\Throwable $notifError) {
                        log_message('error', 'Notif gagal dikirim: ' . $notifError->getMessage());
                    }
                }
            }
            // ===============================
            // INSERT
            // ===============================
            else {

                $data['created_at'] = date('Y-m-d H:i:s');

                if (!$this->affiliate->insert($data)) {
                    throw new \Exception('Gagal menyimpan data affiliate.');
                }
            }

            return redirect()
                ->to(base_url('sw-admin/affiliate'))
                ->with('success', 'Data affiliate berhasil disimpan.');
        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function copy()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $paket_id = $this->request->getPost('paket_id');
        $short_code = $this->request->getPost('short_code');
        $kode_affiliate    = $this->request->getPost('kode_affiliate');

        $expiredAt = date('Y-m-d H:i:s', strtotime('+1 months'));

        $linkModel = new AffiliateLinkModel();
        $linkModel->insert([
            'kode_affiliate' => $kode_affiliate,
            'paket_id'      => $paket_id,
            'short_code'    => $short_code,
            'expired_at'    => $expiredAt,
        ]);

        return $this->response->setJSON([
            'status' => 'success'
        ]);
    }

    public function redirect($id, $voucher = null)
    {
        // 1. Ambil data affiliate link berdasarkan short_code
        $affiliateLink = $this->affiliateLinkModel->where('short_code', $id)->first();

        // JIKA affiliate link TIDAK ditemukan (Null)
        if (!$affiliateLink) {
            // Jangan paksa akses array, arahkan ke halaman error atau default
            return redirect()->to('/')->with('pesan', 'Link tidak valid atau telah dihapus.');
        }

        // 2. Jika link ada, ambil data affiliate-nya
        $affiliate = $this->affiliate->where('kode_affiliate', $affiliateLink['kode_affiliate'])->first();

        $now = date('Y-m-d H:i:s');
        $paketIdEncrypted = encrypt_url($affiliateLink['paket_id']);
        $targetUrl = 'sw-siswa/transaksi/pesan/' . $paketIdEncrypted . '/' . $voucher;

        // 3. Mulai pengecekan logika
        // Pastikan $affiliate ditemukan sebelum akses $affiliate['user_id']
        if ($affiliate && $affiliate['user_id'] != session()->get('id')) {

            // Cek apakah link belum expired
            // if ($affiliateLink['expired_at'] > $now) {
            // Simpan session affiliate
            $data = [
                'short_code' => $affiliateLink['short_code'],
            ];
            session()->set($data);

            // --- A. UPDATE GRAND TOTAL DI TABEL UTAMA ---
            $this->affiliateLinkModel->where('id', $affiliateLink['id'])
                ->set('klik_link', 'klik_link + 1', false)
                ->update();

            // --- B. CATAT REKAP KLIK HARIAN ---
            $tanggalHariIni = date('Y-m-d'); // Ambil tanggal hari ini (Format: 2026-07-10)

            // Cek apakah hari ini link tersebut sudah pernah di-klik
            $cekHarian = $this->affiliateKlikHarianModel->where([
                'affiliate_link_id' => $affiliateLink['id'],
                'tanggal'      => $tanggalHariIni
            ])->first();

            if ($cekHarian) {
                // Jika HARI INI sudah ada data, tambahkan jumlah kliknya (+1)
                $this->affiliateKlikHarianModel->where('id', $cekHarian['id'])
                    ->set('jumlah_klik', 'jumlah_klik + 1', false)
                    ->update();
            } else {
                // Jika HARI INI belum ada data sama sekali, insert data baru dengan klik = 1
                $this->affiliateKlikHarianModel->insert([
                    'affiliate_link_id' => $affiliateLink['id'],
                    'tanggal'      => $tanggalHariIni,
                    'jumlah_klik'  => 1
                ]);
            }
            // }
        }

        // Apapun kondisinya (expired atau punya sendiri), tetap redirect ke halaman pesan
        return redirect()->to($targetUrl);
    }


    public function listKomisi($id)
    {
        try {

            // Validasi parameter kosong
            if (empty($id)) {
                throw new \Exception('Parameter tidak valid.');
            }

            // Decrypt ID
            $id = decrypt_url($id);

            if (empty($id)) {
                throw new \Exception('Kode affiliate tidak valid.');
            }

            // Ambil data affiliate
            $affiliate = $this->affiliate
                ->select('affiliates.*, siswa.nama_siswa, siswa.email')
                ->join('siswa', 'siswa.id_siswa=affiliates.user_id')
                ->where('affiliates.kode_affiliate', $id)
                ->first();

            if (!$affiliate) {
                throw new \Exception('Data affiliate tidak ditemukan.');
            }

            // Ambil data komisi dengan pagination
            $komisi = $this->komisi
                ->select('affiliate_commissions.*, siswa.email, siswa.nama_siswa, paket.nama_paket') // Tambahkan kolom paket di sini
                ->join('transaksi', 'transaksi.idtransaksi = affiliate_commissions.id_transaksi')
                ->join('detail_transaksi', 'detail_transaksi.idtransaksi = transaksi.idtransaksi')
                ->join('paket', 'paket.idpaket = detail_transaksi.idpaket')
                ->join('siswa', 'siswa.id_siswa = transaksi.idsiswa')
                ->where('affiliate_commissions.kode_affiliate', $id)
                ->groupBy('affiliate_commissions.id') // Group by berdasarkan id unik detail transaksi jika ingin setiap item dalam keranjang punya row sendiri
                ->orderBy('affiliate_commissions.tgl_approved', 'DESC')
                ->paginate(15, 'komisi');

            $data['breadcrumbs'] = [
                ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
                ['title' => 'Data Affiliate', 'url' => base_url('sw-admin/affiliate')],
                ['title' => 'Komisi Affiliate', 'url' => '#'],
            ];
            $data['affiliate'] = $affiliate;
            $data['komisi'] = $komisi;
            $data['pager'] = $this->komisi->pager;
            return view('admin/affiliate/komisi', $data);
        } catch (\Throwable $e) {
            return redirect()
                ->to(base_url('sw-admin/affiliate'))
                ->with('error', 'Terjadi kesalahan atau data tidak ditemukan.');
        }
    }

    public function processKomisi()
    {
        // 1. Tangkap Data dari Request
        $ids = $this->request->getPost('ids');
        $email = $this->request->getPost('email');
        $nama = $this->request->getPost('nama') ?? 'Affiliate'; // Fallback jika nama tidak ter-passing

        // Data tambahan untuk tabel pencairan
        $bank_tujuan = $this->request->getPost('bank_tujuan');
        $no_rekening = $this->request->getPost('no_rekening');
        $atas_nama   = $this->request->getPost('atas_nama');
        $persentase_pph21 = (float) $this->request->getPost('potongan_pph21');
        $biaya_admin = (float) $this->request->getPost('biaya_admin');
        $fileBukti   = $this->request->getFile('bukti_transfer');

        // 2. Validasi Input Dasar
        if (empty($ids) || empty($email) || !is_array($ids)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data ID atau Email tidak boleh kosong/tidak valid.'
            ]);
        }

        // 3. Validasi Keamanan File (Anti-Hacker)
        if (!$fileBukti || !$fileBukti->isValid()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'File bukti transfer tidak valid atau belum diunggah.'
            ]);
        }

        // Pengecekan tipe MIME asli file untuk mencegah hacker mengubah ekstensi file berbahaya menjadi .jpg
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($fileBukti->getMimeType(), $allowedMimeTypes)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Keamanan: Format file ditolak! Hanya boleh JPG, JPEG, atau PNG.'
            ]);
        }

        $db = \Config\Database::connect();

        // 4. Hitung nominal kotor langsung dari Database agar aman dari manipulasi inspect element (Hacker)
        $komisiTerpilih = $db->table('affiliate_commissions')
            ->whereIn('id', $ids)
            ->get()->getResultArray();

        if (empty($komisiTerpilih)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data komisi tidak ditemukan di database.']);
        }

        $nominal_kotor = 0;
        $kode_affiliate = $komisiTerpilih[0]['kode_affiliate']; // Ambil kode affiliate dari data komisi

        foreach ($komisiTerpilih as $k) {
            $nominal_kotor += ($k['harga'] * $k['komisi'] / 100);
        }


        $pph21 = $nominal_kotor * ($persentase_pph21 / 100);
        $biaya_admin = (float) $biaya_admin; // Pastikan biaya admin adalah float

        $nominal_bersih = $nominal_kotor - $pph21 - $biaya_admin;
        // 5. Proses Manajemen Folder & Upload File
        $uploadPath = FCPATH . 'uploads/bukti_pencairan/';

        // Jika folder belum ada, buat foldernya secara otomatis beserta permission-nya (0755)
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Gunakan fungsi getRandomName() agar nama file terenkripsi (mencegah overwrite & serangan path traversal)
        $fileName = $fileBukti->getRandomName();

        // Pindahkan file ke folder tujuan
        if (!$fileBukti->move($uploadPath, $fileName)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mengunggah bukti transfer ke server.']);
        }

        // ================= MULAI TRANSAKSI DATABASE =================
        $db->transStart();

        try {
            // A. Insert data ke tabel affiliate_pencairan (Yang baru)
            $dataPencairan = [
                'kode_affiliate' => $kode_affiliate,
                'kode_penarikan' => 'WDW-' . date('YmdHis') . rand(10, 99), // Generate Resi
                'list_id_komisi' => json_encode($ids), // Tampung id komisi dalam format JSON
                'nominal_kotor'  => $nominal_kotor,
                'potongan_pph21' => $persentase_pph21, // Simpan persentase PPh21, bukan nominal
                'biaya_admin'    => $biaya_admin,
                'nominal_bersih' => $nominal_bersih,
                'bank_tujuan'    => esc($bank_tujuan),
                'no_rekening'    => esc($no_rekening),
                'atas_nama'      => esc($atas_nama),
                'bukti_transfer' => $fileName, // Nama file random yang tersimpan
                'status'         => 'selesai' // Langsung selesai karena bukti transfer sudah dilampirkan admin
            ];
            $db->table('affiliate_pencairan')->insert($dataPencairan);

            // B. Proses Update Database (Kode Lama Anda Dipertahankan 100%)
            $this->komisi
                ->whereIn('id', $ids)
                ->set([
                    'status_penarikan' => 'paid',
                    'tgl_pembayaran'   => date('Y-m-d H:i:s')
                ])
                ->update();

            // C. Proses Kirim Email (Kode Lama Anda Dipertahankan 100%)
            // C. Proses Kirim Email
            $subject = 'Pencairan Komisi Affiliate Berhasil Diproses';
            $message = '
                <div style="color: #333; padding: 20px; font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; max-width: 600px; margin: auto; border: 1px solid #eaedf1; border-radius: 10px; background-color: #ffffff;">
                    
                    <!-- Header -->
                    <div style="text-align: center; border-bottom: 2px solid #f8f9fa; padding-bottom: 15px; margin-bottom: 20px;">
                        <div style="font-size: 22px; color: #1C3FAA; font-weight: bold;">
                            PENCAIRAN KOMISI BERHASIL
                        </div>
                        <p style="font-size: 14px; color: #6c757d; margin-top: 5px;">Bukti pembayaran telah dilampirkan ke akun Anda</p>
                    </div>
                    
                    <!-- Body / Sambutan -->
                    <p style="font-size: 15px; color: #333; line-height: 1.6;">Hallo <strong>' . esc($nama) . '</strong>,</p>
                    <p style="font-size: 15px; color: #555; line-height: 1.6;">
                        Kabar gembira! Permintaan pencairan komisi affiliate Anda telah berhasil kami proses dan dana telah ditransfer ke rekening Anda. Berikut adalah rincian pencairannya:
                    </p>
                    
                    <!-- Rincian Pencairan (Tabel) -->
                    <div style="background-color: #f8f9fa; padding: 15px 20px; border-radius: 8px; margin: 25px 0;">
                        <table style="width: 100%; font-size: 14px; color: #333; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 10px 0; border-bottom: 1px dashed #ccc;"><strong>Jumlah Komisi Dicairkan</strong></td>
                                <td style="padding: 10px 0; border-bottom: 1px dashed #ccc; text-align: right;"><strong>' . count($komisiTerpilih) . ' Item</strong></td>
                            </tr>
                            <tr>
                                <td style="padding: 10px 0; border-bottom: 1px dashed #ccc;"><strong>Total Komisi Kotor</strong></td>
                                <td style="padding: 10px 0; border-bottom: 1px dashed #ccc; text-align: right;">Rp ' . number_format($nominal_kotor, 0, ',', '.') . '</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px 0; border-bottom: 1px dashed #ccc;"><strong>Potongan PPh21 (' . $persentase_pph21 . '%)</strong></td>
                                <td style="padding: 10px 0; border-bottom: 1px dashed #ccc; text-align: right; color: #dc3545;">- Rp ' . number_format($pph21, 0, ',', '.') . '</td>
                            </tr>
                            <tr>
                                <td style="padding: 15px 0 5px 0; font-size: 14px; color: #1C3FAA;"><strong>Biaya Admin / Transfer</strong></td>
                                <td style="padding: 15px 0 5px 0; font-size: 14px; color: #1C3FAA; text-align: right;"><strong>- Rp ' . number_format($biaya_admin, 0, ',', '.') . '</strong></td>
                            </tr>
                            <tr>
                                <td style="padding: 15px 0 5px 0; font-size: 16px; color: #1C3FAA;"><strong>TOTAL DITERIMA</strong></td>
                                <td style="padding: 15px 0 5px 0; font-size: 16px; color: #1C3FAA; text-align: right;"><strong>Rp ' . number_format($nominal_bersih, 0, ',', '.') . '</strong></td>
                            </tr>
                        </table>
                    </div>

                    <!-- Detail Rekening Tujuan -->
                    <div style="margin-bottom: 25px; font-size: 14px; color: #555; line-height: 1.6; border-left: 4px solid #1C3FAA; padding-left: 15px; background-color: #f0f4f8; padding: 10px 15px; border-radius: 0 5px 5px 0;">
                        <strong style="color: #333;">Informasi Rekening Tujuan:</strong><br>
                        Bank Tujuan : <strong>' . esc($bank_tujuan) . '</strong><br>
                        No. Rekening : <strong>' . esc($no_rekening) . '</strong><br>
                        Atas Nama : <strong>' . esc($atas_nama) . '</strong>
                    </div>
                    
                    <p style="font-size: 15px; color: #555; line-height: 1.6;">
                        Silakan cek mutasi rekening Anda. Untuk melihat riwayat pencairan dan mengunduh bukti transfer resmi, silakan klik tombol di bawah ini:
                    </p>
                    
                    <!-- Tombol Call to Action -->
                    <div style="text-align: center; margin-top: 30px; margin-bottom: 20px;">
                        <a href="' . base_url('sw-siswa/affiliate') . '" style="display: inline-block; padding: 12px 25px; background-color: #1C3FAA; color: #fff; text-decoration: none; border-radius: 50px; font-weight: bold; font-size: 14px; box-shadow: 0 4px 6px rgba(28, 63, 170, 0.2);">
                            Lihat Dashboard Komisi
                        </a>
                    </div>
                    
                    <!-- Footer -->
                    <p style="font-size: 12px; color: #999; text-align: center; margin-top: 35px; border-top: 1px solid #eaedf1; padding-top: 15px;">
                        Email ini dibuat otomatis oleh sistem. Mohon tidak membalas email ini.
                    </p>
                </div>
            ';
            $emailSent = $this->emailer->send($email, $subject, $message);

            // Selesaikan transaksi database
            $db->transComplete();

            // Jika transaksi database gagal
            if ($db->transStatus() === false) {
                // ROLLBACK MANUAL: Hapus file gambar yang sudah terlanjur terupload jika DB error
                if (file_exists($uploadPath . $fileName)) {
                    unlink($uploadPath . $fileName);
                }
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal memproses data.'
                ]);
            }

            if (!$emailSent) {
                // Opsional: Database sukses di-update tapi email gagal terkirim
                return $this->response->setJSON([
                    'status' => 'warning',
                    'message' => 'Komisi berhasil dibayar, tetapi email pemberitahuan gagal dikirim.'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Komisi berhasil diproses dan email terkirim.'
            ]);
        } catch (\Exception $e) {
            // Tangkap error tak terduga (exception)
            $db->transRollback();

            // ROLLBACK MANUAL: Hapus file gambar jika ada sistem error/syntax error
            if (isset($fileName) && file_exists($uploadPath . $fileName)) {
                unlink($uploadPath . $fileName);
            }

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

    public function getDetailPencairan($id_komisi)
    {
        // Pastikan request melalui AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Akses tidak sah.'
            ]);
        }

        try {
            $db = \Config\Database::connect();

            // ==============================================================
            // PERBAIKAN DI SINI
            // Karena di database tersimpan sebagai string ("35"), 
            // kita harus menambahkan kutip ganda di dalam pencariannya.
            // ==============================================================
            $searchId = '"' . (int)$id_komisi . '"';

            $pencairan = $db->table('affiliate_pencairan')
                ->where("JSON_CONTAINS(list_id_komisi, '$searchId')", null, false)
                ->get()
                ->getRowArray();

            if (empty($pencairan)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data detail pencairan tidak ditemukan.'
                ]);
            }

            // Ambil list ID komisi dari kolom JSON dan ubah kembali menjadi array PHP
            $listIdKomisi = json_decode($pencairan['list_id_komisi'], true);

            // Ambil rincian data komisi terkait dari tabel affiliate_commissions
            $detailKomisi = [];
            if (!empty($listIdKomisi) && is_array($listIdKomisi)) {
                $detailKomisi = $db->table('affiliate_commissions')
                    ->whereIn('id', $listIdKomisi)
                    ->get()
                    ->getResultArray();
            }

            // Masukkan data rincian komisi ke dalam array hasil response
            $pencairan['detail_komisi'] = $detailKomisi;

            return $this->response->setJSON([
                'status' => 'success',
                'data' => $pencairan
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }
}

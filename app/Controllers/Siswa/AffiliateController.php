<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\AffiliateModel;
use App\Models\AffiliateCommissionModel;
use App\Models\SiswaModel;
use App\Models\AffiliateLinkModel;
use App\Models\AffiliateKlikHarianModel;

class AffiliateController extends BaseController
{
    protected $affiliate;
    protected $komisi;
    protected $SiswaModel;
    protected $affiliateLinkModel;
    protected $affiliateKlikHarianModel;
    protected $paketModel;

    public function __construct()
    {
        $this->affiliate = new AffiliateModel();
        $this->komisi    = new AffiliateCommissionModel();
        $this->SiswaModel    = new SiswaModel();
        $this->affiliateLinkModel = new AffiliateLinkModel();
        $this->affiliateKlikHarianModel = new AffiliateKlikHarianModel();
        $this->paketModel = new \App\Models\PaketModel();
    }

    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-siswa')],
            ['title' => 'Affiliate', 'url' => '#'],
        ];
        $data['db'] = \Config\Database::connect();
        // Ambil affiliate
        $affiliate = $this->affiliate
            ->where('user_id', session()->get('id'))
            ->get()->getRowObject();
    
        $data['affiliates'] = $affiliate;
        $data['siswa'] = $this->SiswaModel->asObject()->find(session()->get('id'));
        $data['paket'] = $this->paketModel->getAll();
    
        // Kalau belum punya affiliate
        if (!$affiliate) {
            $data['komisi'] = [];
            $data['pager']  = null;
            $data['totalKlik'] = 0;
            $data['totalApprovedTransaksi'] = 0;
            $data['conversionRate'] = 0;
            $data['totalKomisiPending'] = 0;
            $data['totalKomisiCair'] = 0;
            $data['totalKomisiAkumulasi'] = 0;
            $data['linksByPaket'] = [];
            $data['salesByPaket'] = [];
            $data['chartDates'] = [];
            $data['chartClicks'] = [];
            $data['referralUrl'] = '';
            $data['qrDataUri']   = '';
            $data['avatarUrl']   = '';
            $data['memberId']    = '';
            return view('siswa/affiliate/index', $data);
        }

        // 1. Ambil data links untuk affiliate ini
        $links = $this->affiliateLinkModel
            ->where('kode_affiliate', $affiliate->kode_affiliate)
            ->findAll();

        $totalKlik = 0;
        $linksByPaket = [];
        $linkIds = [];

        foreach ($links as $l) {
            $totalKlik += (int)($l['klik_link'] ?? 0);
            $linksByPaket[$l['paket_id']] = $l;
            $linkIds[] = $l['id'];
        }

        // 2. Ringkasan Komisi & Transaksi menyeluruh
        $summaryKomisi = $data['db']->table('affiliate_commissions')
            ->select("
                COUNT(id) as total_transaksi,
                COUNT(CASE WHEN status IN ('approved', 'paid') THEN 1 ELSE NULL END) as total_approved_transaksi,
                SUM(CASE WHEN status IN ('approved', 'paid') AND status_penarikan = 'pending' THEN (harga * komisi / 100) ELSE 0 END) as komisi_pending,
                SUM(CASE WHEN status_penarikan = 'paid' THEN (harga * komisi / 100) ELSE 0 END) as komisi_cair,
                SUM(CASE WHEN status IN ('approved', 'paid') THEN (harga * komisi / 100) ELSE 0 END) as total_komisi
            ")
            ->where('kode_affiliate', $affiliate->kode_affiliate)
            ->get()
            ->getRowObject();

        $totalApprovedTransaksi = (int)($summaryKomisi->total_approved_transaksi ?? 0);
        $totalKomisiPending    = (float)($summaryKomisi->komisi_pending ?? 0);
        $totalKomisiCair       = (float)($summaryKomisi->komisi_cair ?? 0);
        $totalKomisiAkumulasi  = (float)($summaryKomisi->total_komisi ?? 0);

        // Conversion Rate: (Approved Transaksi / Total Klik) * 100%
        $conversionRate = $totalKlik > 0 ? round(($totalApprovedTransaksi / $totalKlik) * 100, 1) : 0;

        // 3. Rekap Transaksi & Komisi per Paket
        $salesPerPaket = $data['db']->table('affiliate_commissions')
            ->select('id_paket, COUNT(id) as total_terjual, SUM(harga * komisi / 100) as total_komisi_paket')
            ->where('kode_affiliate', $affiliate->kode_affiliate)
            ->whereIn('status', ['approved', 'paid'])
            ->groupBy('id_paket')
            ->get()
            ->getResultArray();

        $salesByPaket = [];
        foreach ($salesPerPaket as $sp) {
            $salesByPaket[$sp['id_paket']] = $sp;
        }

        // 4. Analisis Tren Klik Harian (7 Hari Terakhir)
        $chartDates = [];
        $grafikKlikMap = [];

        for ($i = 6; $i >= 0; $i--) {
            $tgl = date('Y-m-d', strtotime("-$i days"));
            $chartDates[] = date('d M', strtotime($tgl));
            $grafikKlikMap[$tgl] = 0;
        }

        if (!empty($linkIds)) {
            $harian = $data['db']->table('affiliate_klik_harian')
                ->select('tanggal, SUM(jumlah_klik) as total_harian')
                ->whereIn('affiliate_link_id', $linkIds)
                ->where('tanggal >=', date('Y-m-d', strtotime('-6 days')))
                ->groupBy('tanggal')
                ->get()
                ->getResultArray();

            foreach ($harian as $h) {
                if (isset($grafikKlikMap[$h['tanggal']])) {
                    $grafikKlikMap[$h['tanggal']] = (int)$h['total_harian'];
                }
            }
        }
        $chartClicks = array_values($grafikKlikMap);

        // Data statistik & analitik untuk view
        $data['totalKlik']              = $totalKlik;
        $data['totalApprovedTransaksi'] = $totalApprovedTransaksi;
        $data['conversionRate']         = $conversionRate;
        $data['totalKomisiPending']     = $totalKomisiPending;
        $data['totalKomisiCair']        = $totalKomisiCair;
        $data['totalKomisiAkumulasi']   = $totalKomisiAkumulasi;
        $data['linksByPaket']           = $linksByPaket;
        $data['salesByPaket']           = $salesByPaket;
        $data['chartDates']             = $chartDates;
        $data['chartClicks']            = $chartClicks;

        // 5. Data Kartu Anggota Affiliate (untuk ditampilkan langsung di dashboard)
        $primaryShortCode = $links[0]['short_code'] ?? $affiliate->kode_affiliate;
        $referralUrl = base_url('ref/' . $primaryShortCode . '/8173AF4239');
        $memberId = 'KB-AFF-' . strtoupper($affiliate->kode_affiliate);

        $qrDataUri = '';
        try {
            if (class_exists(\Endroid\QrCode\QrCode::class) && class_exists(\Endroid\QrCode\Writer\PngWriter::class)) {
                $qrCode = new \Endroid\QrCode\QrCode($referralUrl);
                $qrCode->setSize(200);
                $qrCode->setMargin(4);
                $writer = new \Endroid\QrCode\Writer\PngWriter();
                $result = $writer->write($qrCode);
                $qrDataUri = $result->getDataUri();
            }
        } catch (\Throwable $e) {
            $qrDataUri = '';
        }
        if (empty($qrDataUri)) {
            $qrDataUri = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($referralUrl);
        }

        $avatarPath = 'assets/app-assets/user/' . ($data['siswa']->avatar ?? '');
        if (!empty($data['siswa']->avatar) && file_exists(FCPATH . $avatarPath)) {
            $avatarUrl = base_url($avatarPath);
        } else {
            $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($data['siswa']->nama_siswa ?? 'Affiliate') . '&background=0b132b&color=f8fafc&bold=true&size=256';
        }

        $data['referralUrl'] = $referralUrl;
        $data['qrDataUri']   = $qrDataUri;
        $data['avatarUrl']   = $avatarUrl;
        $data['memberId']    = $memberId;
    
        // Pagination komisi (WAJIB pakai group)
        $data['komisi'] = $this->komisi
            ->select('affiliate_commissions.*, siswa.nama_siswa, paket.nama_paket') // Tambahkan kolom paket di sini
            ->join('transaksi', 'transaksi.idtransaksi = affiliate_commissions.id_transaksi')
            ->join('detail_transaksi', 'detail_transaksi.idtransaksi = transaksi.idtransaksi')
            ->join('paket', 'paket.idpaket = detail_transaksi.idpaket')
            ->join('siswa', 'siswa.id_siswa = transaksi.idsiswa')
            ->where('affiliate_commissions.kode_affiliate', $affiliate->kode_affiliate)
            ->groupBy('affiliate_commissions.id') // Group by berdasarkan id unik detail transaksi jika ingin setiap item dalam keranjang punya row sendiri
            ->orderBy('affiliate_commissions.id', 'DESC')
            ->paginate(10, 'komisi');
    
        $data['pager'] = $this->komisi->pager;
    
        return view('siswa/affiliate/index', $data);
    }


    public function create()
    {
        $data['siswa'] = $this->SiswaModel->asObject()->find(session()->get('id'));
        return view('siswa/affiliate/form', $data);
    }

    public function edit($idenc)
    {
        $id = decrypt_url($idenc);
        $data['breadcrumbs'] = [
            ['title' => 'Affiliate', 'url' => base_url('sw-siswa/affiliate')],
            ['title' => 'Edit Affiliate', 'url' => '#'],
        ];
        $data['siswa'] = $this->SiswaModel->asObject()->find(session()->get('id'));
        $data['affiliate'] = $this->affiliate->find($id);
        
    
        return view('siswa/affiliate/form', $data);
    }

    public function save()
    {
        // 1. Tentukan Rules Validasi
        $rules = [
            'bank' => [
                'rules'  => 'required|alpha_numeric_space|max_length[50]',
                'errors' => [
                    'required' => 'Nama Bank wajib diisi.',
                    'alpha_numeric_space' => 'Nama Bank tidak boleh mengandung karakter spesial.',
                ]
            ],
            'norek' => [
                'rules'  => 'required|numeric|min_length[5]|max_length[25]',
                'errors' => [
                    'required' => 'Nomor rekening wajib diisi.',
                    'numeric'  => 'Nomor rekening hanya boleh berisi angka.',
                    'min_length' => 'Nomor rekening terlalu pendek.',
                    'max_length' => 'Nomor rekening maksimal 25 angka.',
                ]
            ],
            'nama_akun_bank' => [
                'rules'  => 'required|regex_match[/^[a-zA-Z0-9 \.]+$/]|max_length[50]',
                'errors' => [
                    'required' => 'Nama pemilik rekening wajib diisi.',
                    'regex_match' => 'Nama pemilik rekening hanya boleh huruf, angka, spasi, dan titik.',
                ]
            ],
            'cabang_bank' => [
                'rules'  => 'required|alpha_numeric_space|max_length[50]',
                'errors' => [
                    'required' => 'Cabang bank wajib diisi.',
                    'alpha_numeric_space' => 'Cabang tidak boleh mengandung karakter spesial.',
                ]
            ]
        ];
    
        // 2. Jalankan Validasi
        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $pesanError = implode('\n', $errors); 
    
            return redirect()->back()->withInput('pesan', $pesanError);
        }
    
        // 3. Inisialisasi Data & Keamanan
        $id = $this->request->getPost('id_affiliate');
        $currentUserId = session()->get('id');
    
        // Data dasar yang akan disimpan
        $data = [
            'user_id'        => $currentUserId,
            'bank'           => esc($this->request->getPost('bank')),
            'norek'          => esc($this->request->getPost('norek')),
            'nama_akun_bank' => esc($this->request->getPost('nama_akun_bank')),
            'cabang_bank'    => esc($this->request->getPost('cabang_bank')),
        ];
    
        // 4. Logika Update atau Insert
        if ($id) {
            // --- PROTEKSI UPDATE (IDOR PROTECTION) ---
            $affiliate = $this->affiliate->where([
                'id_affiliate' => $id,
                'user_id'      => $currentUserId
            ])->first();
    
            // Jika data tidak ditemukan atau bukan milik user tersebut
            if (!$affiliate) {
                return redirect()->back()->with('pesan', 'Gagal memproses data,Coba lagi!');
            }
    
            // --- PROTEKSI LIMIT EDIT (Maksimal 1 Kali) ---
            if (($affiliate['total_edit'] ?? 0) >= 1) {
                return redirect()->back()->with('pesan', 'Anda sudah menggunakan batas maksimal pengubahan data.');
            }
    
            // Gunakan status yang sudah ada (mencegah manipulasi status via Inspect Element)
            $data['status'] = $affiliate['status'];
            $data['total_edit'] = ($affiliate['total_edit'] ?? 0) + 1;
    
            $this->affiliate->update($id, $data);
            $pesanUser = 'Data rekening berhasil diperbarui.';
            
        } else {
            // --- PROTEKSI INSERT ---
            // Pastikan status selalu Pending (0) saat pendaftaran baru
            $data['status'] = '1';
            $data['total_edit'] = 0;
            
            send_notif(
                '1', // Pastikan kolom di DB namanya idsiswa
                "Pendaftar affiliate baru", 
                "Silahkan verifikasi pendaftar affiliate baru.", 
                base_url('sw-admin/affiliate')
            );
    
            $this->affiliate->insert($data);
            $pesanUser = 'Pendaftaran program affiliate berhasil disimpan.';
        }
    
        return redirect()->to(base_url('sw-siswa/affiliate'))->with('success', $pesanUser);
    }
    public function delete()
    {
        $id = $this->request->getPost('id');
    
        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID tidak ditemukan'
            ]);
        }
    
        $this->affiliate->delete($id);
    
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Pengajuan affiliate berhasil dibatalkan'
        ]);
    }

    
    public function copy()
    {
        if (!$this->request->isAJAX()) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Forbidden'
                ]);
        }
        // cek affiliate terlebih dahulu
        $affiliate = $this->affiliate->where('user_id', session()->get('id'))->first();
        
        
        $paket_id       = $this->request->getPost('paket_id');
        $kode_affiliate = $affiliate['kode_affiliate'];
        $now       = date('Y-m-d H:i:s');
        $expiredAt = date('Y-m-d H:i:s', strtotime('+1 months'));
    
        // 🔍 CEK DATA EXISTING
        $existing = $this->affiliateLinkModel
            ->where('kode_affiliate', $kode_affiliate)
            ->where('paket_id', $paket_id)
            ->first();
    
        // ===============================
        // JIKA DATA SUDAH ADA
        // ===============================
        if ($existing) {
    
            // ⏳ CEK EXPIRED
            if ($existing['expired_at'] > $now) {
    
                // MASIH AKTIF → UPDATE EXPIRED SAJA
                $this->affiliateLinkModel->update($existing['id'], [
                    'expired_at' => $expiredAt
                ]);
    
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Link masih aktif, expired diperpanjang',
                    'link'    => base_url('ref/'.$existing['short_code'].'/8173AF4239'),
                ]);
            }else{
                $chars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                $length = 8;
        
                do {
                    $kode = '';
                    for ($i = 0; $i < $length; $i++) {
                        $kode .= $chars[random_int(0, strlen($chars) - 1)];
                    }
                } while ($this->affiliateLinkModel->where('short_code', $kode)->countAllResults() > 0);
        
                $short_code = $kode;
                
                // ❌ SUDAH EXPIRED → UPDATE DATA
                $this->affiliateLinkModel->update($existing['id'], [
                    'short_code' => $short_code,
                    'expired_at' => $expiredAt
                ]);
        
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Link diperbarui karena sudah expired',
                    'link'    => base_url('ref/'.$short_code.'/8173AF4239')
                ]);
            }
    
        }else{
            // ===============================
            // JIKA DATA BELUM ADA → INSERT
            // ===============================
            $chars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $length = 8;
    
            do {
                $kode = '';
                for ($i = 0; $i < $length; $i++) {
                    $kode .= $chars[random_int(0, strlen($chars) - 1)];
                }
            } while ($this->affiliateLinkModel->where('short_code', $kode)->countAllResults() > 0);
    
            $short_code = $kode;
            
            $insert = $this->affiliateLinkModel->insert([
                'kode_affiliate' => $kode_affiliate,
                'paket_id'       => $paket_id,
                'short_code'     => $short_code,
                'expired_at'     => $expiredAt,
            ]);
        
            if ($insert === false) {
                return $this->response
                    ->setStatusCode(500)
                    ->setJSON([
                        'status'  => 'error',
                        'message' => 'Gagal menyimpan data'
                    ]);
            }else{
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Link berhasil dibuat',
                    'link'    => base_url('ref/'.$short_code.'/8173AF4239')
                ]);
            }
        
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

    public function kartu()
    {
        $userId = session()->get('id');
        $affiliate = $this->affiliate
            ->where('user_id', $userId)
            ->get()->getRowObject();

        if (!$affiliate || $affiliate->status != '1') {
            return redirect()->to(base_url('sw-siswa/affiliate'))
                ->with('pesan', 'Kartu anggota affiliate hanya dapat diakses setelah akun disetujui (Approved).');
        }

        $siswa = $this->SiswaModel->asObject()->find($userId);

        // Ambil link referral utama (short code pertama atau fallback ke kode_affiliate)
        $primaryLink = $this->affiliateLinkModel
            ->where('kode_affiliate', $affiliate->kode_affiliate)
            ->first();

        $shortCode = $primaryLink['short_code'] ?? $affiliate->kode_affiliate;
        $referralUrl = base_url('ref/' . $shortCode . '/8173AF4239');

        // Buat QR Code
        $qrDataUri = '';
        try {
            if (class_exists(\Endroid\QrCode\QrCode::class) && class_exists(\Endroid\QrCode\Writer\PngWriter::class)) {
                $qrCode = new \Endroid\QrCode\QrCode($referralUrl);
                $qrCode->setSize(220);
                $qrCode->setMargin(4);
                $writer = new \Endroid\QrCode\Writer\PngWriter();
                $result = $writer->write($qrCode);
                $qrDataUri = $result->getDataUri();
            }
        } catch (\Throwable $e) {
            $qrDataUri = '';
        }

        if (empty($qrDataUri)) {
            $qrDataUri = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' . urlencode($referralUrl);
        }

        // Avatar check
        $avatarPath = 'assets/app-assets/user/' . ($siswa->avatar ?? '');
        if (!empty($siswa->avatar) && file_exists(FCPATH . $avatarPath)) {
            $avatarUrl = base_url($avatarPath);
        } else {
            $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($siswa->nama_siswa ?? 'Affiliate') . '&background=0b132b&color=f8fafc&bold=true&size=256';
        }

        $data = [
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => base_url('sw-siswa')],
                ['title' => 'Affiliate', 'url' => base_url('sw-siswa/affiliate')],
                ['title' => 'Kartu Anggota', 'url' => '#'],
            ],
            'affiliate'    => $affiliate,
            'siswa'        => $siswa,
            'referralUrl'  => $referralUrl,
            'qrDataUri'    => $qrDataUri,
            'avatarUrl'    => $avatarUrl,
            'memberId'     => 'KB-AFF-' . strtoupper($affiliate->kode_affiliate),
        ];

        return view('siswa/affiliate/kartu', $data);
    }
}

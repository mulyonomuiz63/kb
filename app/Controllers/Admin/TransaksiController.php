<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;


class TransaksiController extends BaseController
{

    protected $transaksiModel;
    protected $detailPaketModel;
    protected $ujianModel;
    protected $ujianSiswaModel;
    protected $affiliateCommissionModel;
    protected $mapelSiswaModel;
    protected $paketModel;
    protected $siswaModel;
    protected $serviceEmail;
    protected $detailTransaksiModel;
    protected $ikhModel;
    public function __construct()
    {
        $this->transaksiModel = new \App\Models\TransaksiModel();
        $this->detailPaketModel = new \App\Models\DetailPaketModel();
        $this->ujianModel = new \App\Models\UjianModel();
        $this->ujianSiswaModel = new \App\Models\UjianSiswaModel();
        $this->affiliateCommissionModel = new \App\Models\AffiliateCommissionModel();
        $this->mapelSiswaModel = new \App\Models\MapelSiswaModel();
        $this->paketModel = new \App\Models\PaketModel();
        $this->siswaModel = new \App\Models\SiswaModel();
        $this->serviceEmail = new \App\Libraries\Emailer();
        $this->detailTransaksiModel = new \App\Models\DetailTransaksiModel();
        $this->ikhModel  = new \App\Models\IkhModel();
    }
    public function index()
    {
        $db = \Config\Database::connect();

        // 1. Dapatkan Data Top 5 Paket Terlaris (Status Lunas 'S')
        // 1. Dapatkan Data Top 5 Paket Terlaris (Perbaikan Duplikasi Data)
        $topPaket = $db->table('detail_transaksi dt')
            // TAMBAHKAN DISTINCT DI SINI ↓
            ->select('p.nama_paket as label, COUNT(DISTINCT dt.idtransaksi) as total')
            ->join('paket p', 'p.idpaket = dt.idpaket')
            ->join('transaksi t', 't.idtransaksi = dt.idtransaksi')
            ->where('t.status', 'S')
            ->where('t.jenis_bayar !=', null)
            ->groupBy('dt.idpaket, p.nama_paket')
            ->orderBy('total', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // 2. Dapatkan Data Metode Pembayaran Terpopuler (Online vs Manual)
        $metodeBayar = $db->table('transaksi as t')
            ->select('t.jenis_bayar as label, COUNT(DISTINCT t.idtransaksi) as total')
            ->join('detail_transaksi dt', 'dt.idtransaksi=t.idtransaksi')
            ->join('paket p', 'p.idpaket = dt.idpaket')
            ->where('t.status', 'S')
            ->where('t.jenis_bayar !=', null)
            ->groupBy('t.jenis_bayar')
            ->get()->getResultArray();

        $analisisVoucher = $this->transaksiModel
            ->select("
                COUNT(DISTINCT CASE WHEN (transaksi.kode_voucher = '8173AF4239' OR (transaksi.kode_affiliate IS NOT NULL AND transaksi.kode_affiliate != '')) THEN transaksi.idtransaksi ELSE NULL END) as affiliate,
                COUNT(DISTINCT CASE WHEN (transaksi.kode_voucher IS NOT NULL AND transaksi.kode_voucher != '' AND transaksi.kode_voucher != '8173AF4239') AND (transaksi.kode_affiliate IS NULL OR transaksi.kode_affiliate = '') THEN transaksi.idtransaksi ELSE NULL END) as mitra,
                COUNT(DISTINCT CASE WHEN (transaksi.kode_voucher IS NULL OR transaksi.kode_voucher = '') AND (transaksi.kode_affiliate IS NULL OR transaksi.kode_affiliate = '') THEN transaksi.idtransaksi ELSE NULL END) as tanpa_voucher
            ")
            ->join('detail_transaksi d', 'd.idtransaksi = transaksi.idtransaksi')
            ->join('paket c', 'c.idpaket = d.idpaket')
            ->where('transaksi.status', 'S')
            ->get()->getRowArray();

        $listTahun = $db->table('transaksi')
            ->select('YEAR(created_at) AS tahun', false)
            ->where('status', 'S')
            ->groupBy('tahun')
            ->orderBy('tahun', 'DESC') // Urutkan dari tahun terbaru
            ->get()
            ->getResultArray();

        // Jika tabel kosong, set default ke tahun sekarang agar tidak error
        if (empty($listTahun)) {
            $listTahun = [['tahun' => date('Y')]];
        }

        //  ke dalam array data yang akan dikirim ke View
        $data = [
            'listTahun' => $listTahun
        ];

        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'List Transaksi', 'url' => '#'],
        ];

        // Parsing data ke View
        $data['topPaket']        = json_encode($topPaket);
        $data['metodeBayar'] = json_encode($metodeBayar);
        $data['analisisVoucher'] = json_encode([
            ['label' => 'Voucher Mitra', 'total' => (int)($analisisVoucher['mitra'] ?? 0)],
            ['label' => 'Voucher Affiliate', 'total' => (int)($analisisVoucher['affiliate'] ?? 0)],
            ['label' => 'Tanpa Voucher', 'total' => (int)($analisisVoucher['tanpa_voucher'] ?? 0)]
        ]);

        return view('admin/transaksi/list', $data);
    }
    public function datatables()
    {
        if ($this->request->isAJAX()) {
            try {
                $request = $this->request;
                $start  = (int) $request->getPost('start');
                $length = (int) $request->getPost('length');
                $search = $request->getPost('search')['value'];

                // Tangkap Parameter Filter
                $filter_bulan = $request->getPost('filter_bulan_range');
                $status_afiliasi = $request->getPost('filter_status_afiliasi');
                $filter_paket = $request->getPost('paket_pelatihan'); // UPGRADE: Tangkap filter paket

                // ==========================================
                // 1. BUILDER UNTUK MENAMPILKAN DATA TABEL
                // ==========================================
                $query = $this->transaksiModel->getBaseQuery();

                if (!empty($search)) {
                    $query->groupStart()
                        ->like('b.nama_siswa', $search)
                        ->orLike('c.nama_paket', $search)
                        ->orLike('transaksi.idtransaksi', $search)
                        ->groupEnd();
                }

                // ==========================================
                // IMPLEMENTASI FILTER RANGE BULAN (QUERY 1)
                // ==========================================
                if (!empty($filter_bulan)) {
                    $dates = explode(' - ', $filter_bulan);
                    if (count($dates) == 2) {
                        $start_date = trim($dates[0]) . '-01 00:00:00';
                        $end_date   = date('Y-m-t 23:59:59', strtotime(trim($dates[1]) . '-01'));

                        $query->where('transaksi.created_at >=', $start_date);
                        $query->where('transaksi.created_at <=', $end_date);
                    }
                }

                // ==========================================
                // IMPLEMENTASI FILTER PAKET (QUERY 1)
                // ==========================================
                if ($filter_paket == '1') {
                    $query->whereIn('c.v_ujian', ['all', '1']);
                } elseif ($filter_paket == '2') {
                    $query->whereIn('c.v_materi', ['all', '1']);
                } elseif ($filter_paket == '3') {
                    $query->like('c.jenis_paket', '"ikh"');
                }

                if ($status_afiliasi === '0') {
                    $query->where('b.idafiliasi IS NULL');
                } else {
                    if ($status_afiliasi != '2') {
                        $query->where('b.idafiliasi', $status_afiliasi);
                    }
                }

                // Menghitung total data terfilter TANPA mereset builder (bawaan kode aslimu)
                $totalFiltered = $query->countAllResults(false);

                // Ambil data untuk baris tabel (Ini akan otomatis mereset builder $query)
                $data = $query->orderBy("transaksi.status = 'S'", "ASC", FALSE)
                    ->orderBy("transaksi.tgl_pembayaran IS NULL", "DESC", FALSE)
                    ->orderBy('transaksi.tgl_pembayaran', 'DESC')
                    ->limit($length, $start)
                    ->get()
                    ->getResultObject();


                // ==========================================
                // 2. BUILDER TERPISAH UNTUK TOTAL PENDAPATAN
                // ==========================================
                // Panggil ulang dari model agar tidak merusak $query di atas
                $queryTotal = $this->transaksiModel->getBaseQuery();

                if (!empty($search)) {
                    $queryTotal->groupStart()
                        ->like('b.nama_siswa', $search)
                        ->orLike('c.nama_paket', $search)
                        ->orLike('transaksi.idtransaksi', $search)
                        ->groupEnd();
                }

                // ==========================================
                // IMPLEMENTASI FILTER RANGE BULAN (QUERY TOTAL)
                // ==========================================
                if (!empty($filter_bulan)) {
                    $dates = explode(' - ', $filter_bulan);
                    if (count($dates) == 2) {
                        $start_date = trim($dates[0]) . '-01 00:00:00';
                        $end_date   = date('Y-m-t 23:59:59', strtotime(trim($dates[1]) . '-01'));

                        $queryTotal->where('transaksi.created_at >=', $start_date);
                        $queryTotal->where('transaksi.created_at <=', $end_date);
                    }
                }

                // ==========================================
                // IMPLEMENTASI FILTER PAKET (QUERY TOTAL)
                // ==========================================
                if ($filter_paket == '1') {
                    $queryTotal->whereIn('c.v_ujian', ['all', '1']);
                } elseif ($filter_paket == '2') {
                    $queryTotal->whereIn('c.v_materi', ['all', '1']);
                } elseif ($filter_paket == '3') {
                    $queryTotal->like('c.jenis_paket', '"ikh"');
                }

                if ($status_afiliasi === '0') {
                    $queryTotal->where('b.idafiliasi IS NULL');
                } else {
                    if ($status_afiliasi != '2') {
                        $queryTotal->where('b.idafiliasi', $status_afiliasi);
                    }
                }

                // Ambil semua data hanya yang berstatus Lunas (S)
                $dataTotalLunas = $queryTotal->where('transaksi.status', 'S')->get()->getResultObject();

                // Kalkulasi omset murni (Keseluruhan, Manual, dan Midtrans)
                $totalPendapatan = 0;
                $totalManual = 0;
                $totalMidtrans = 0;

                foreach ($dataTotalLunas as $dt) {
                    $diskon         = ($dt->nominal * $dt->diskon) / 100;
                    $totalDiskon    = $dt->nominal - $diskon;
                    $diskon_voucher = ($totalDiskon * $dt->voucher) / 100;
                    $nominal_bersih = $dt->nominal - $diskon - $diskon_voucher;

                    $totalPendapatan += $nominal_bersih;

                    // Pisahkan berdasarkan jenis pembayaran
                    if ($dt->jenis_bayar === 'manual') {
                        $totalManual += $nominal_bersih;
                    } elseif ($dt->jenis_bayar === 'online') {
                        $totalMidtrans += $nominal_bersih;
                    }
                }

                // ==========================================
                // 3. PEMBENTUKAN BARIS TABEL (HTML)
                // ==========================================
                $results = [];
                foreach ($data as $s) {
                    $id_enc = encrypt_url($s->idtransaksi);
                    $row = [];

                    // Kolom Peserta
                    $htmlPeserta = '<div class="text-gray-800 fw-bold fs-6">' . esc($s->nama_siswa) . '</div>';
                    $htmlPeserta .= '<div class="text-muted fw-semibold fs-7 mb-1">' . esc($s->email) . '</div>';

                    // Pengecekan jika nomor HP ada dan tidak kosong
                    if (!empty($s->hp)) {
                        // Bersihkan karakter selain angka (misal ada spasi atau strip)
                        $no_wa = preg_replace('/[^0-9]/', '', $s->hp);

                        // Ubah awalan '0' menjadi '62' agar formatnya sesuai dengan standar API WhatsApp
                        if (substr($no_wa, 0, 1) === '0') {
                            $no_wa = '62' . substr($no_wa, 1);
                        }

                        // Tambahkan tombol WhatsApp
                        $htmlPeserta .= '<a href="https://wa.me/' . esc($no_wa) . '" target="_blank" class="badge badge-light-success text-decoration-none mt-1">
                        <i class="ki-outline ki-whatsapp text-success me-1"></i> ' . esc($s->hp) . '
                     </a>';
                    }

                    $row['peserta'] = $htmlPeserta;

                    // Kolom Paket
                    $row['paket'] = '<div class="text-gray-800 fw-bold fs-6">' . esc($s->nama_paket) . '</div>
                     <div class="text-muted fw-semibold fs-7">' . esc($s->kantor ?? '') . '</div>';

                    // LOGIKA AFFILIATE & KOLOM VOUCHER
                    $is_affiliate = false;
                    $kode_affiliate = $s->kode_affiliate ?? null;
                    $nama_afiliasi = $s->nama_afiliasi ?? null;

                    if ($s->kode_voucher === '8173AF4239' || !empty($kode_affiliate) || !empty($s->idafiliasi)) {
                        $is_affiliate = true;
                    }

                    $html_voucher = $s->kode_voucher
                        ? '<span class="badge badge-light-info fw-bold px-3 py-2">' . esc($s->kode_voucher) . '</span>'
                        : '<span class="text-muted fs-7 fw-semibold">-</span>';

                    if ($is_affiliate) {
                        // Menampilkan nama afiliasi jika ada, jika tidak ada fallback ke teks statis
                        $display_nama = $nama_afiliasi ? esc($nama_afiliasi) : 'Link Affiliate';

                        $html_voucher .= '<div class="mt-2">
                            <span class="badge badge-light-success fs-8 fw-bold px-2 py-1">
                                <i class="ki-duotone ki-shop fs-7 me-1 text-success">
                                    <span class="path1"></span><span class="path2"></span>
                                </i> ' . $display_nama . '
                            </span>
                        </div>';
                    }

                    $row['voucher'] = $html_voucher;

                    // KOLOM PEMBAYARAN
                    $label_jenis_bayar = '';
                    if ($s->jenis_bayar === 'online') {
                        $label_jenis_bayar = '<div class="text-info fw-semibold fs-8"><i class="ki-duotone ki-credit-cart fs-7 me-1 text-info"><span class="path1"></span><span class="path2"></span></i> Midtrans</div>';
                    } elseif ($s->jenis_bayar === 'manual') {
                        $label_jenis_bayar = '<div class="text-primary fw-semibold fs-8"><i class="ki-duotone ki-wallet fs-7 me-1 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i> Manual Transfer</div>';
                    } else {
                        $label_jenis_bayar = '<div class="text-muted fw-semibold fs-8">Belum memilih</div>';
                    }

                    $html_tgl_pesan = '';
                    if (!empty($s->created_at)) {
                        $datePesan = new \DateTime($s->created_at);
                        $html_tgl_pesan = '<div class="text-gray-800 fw-bold fs-7" title="Waktu Pesanan Dibuat">
                                            <i class="ki-duotone ki-time fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>'
                            . $datePesan->format('d M Y, H:i') .
                            '</div>';
                    } else {
                        $html_tgl_pesan = '<div class="text-muted fw-semibold fs-7"><i class="ki-duotone ki-time fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>-</div>';
                    }

                    $html_tgl_bayar = '';
                    if (!empty($s->tgl_pembayaran)) {
                        $dateBayar = new \DateTime($s->tgl_pembayaran);
                        $html_tgl_bayar = '<div class="text-success fw-semibold fs-8 mt-1" title="Waktu Pembayaran Berhasil">
                                            Lunas: ' . $dateBayar->format('d M Y, H:i') .
                            '</div>';
                    } else {
                        $html_tgl_bayar = '<div class="text-danger fw-semibold fs-8 mt-1">Belum bayar</div>';
                    }

                    $row['pembayaran'] = $html_tgl_pesan . $html_tgl_bayar . '<div class="mt-1">' . $label_jenis_bayar . '</div>';

                    // PERHITUNGAN DISKON & NOMINAL
                    $diskon         = ($s->nominal * $s->diskon) / 100;
                    $totalDiskon    = $s->nominal - $diskon;
                    $diskon_voucher = ($totalDiskon * $s->voucher) / 100;
                    $nominal        = $s->nominal - $diskon - $diskon_voucher;

                    $row['nominal'] = '<span class="text-primary fw-bold fs-6">Rp ' . number_format($nominal, 0, ',', '.') . '</span>';

                    // STATUS
                    if ($s->status === 'S') {
                        $row['status'] = '<div class="text-center"><span class="badge badge-light-success fw-bold px-3 py-2">Lunas</span></div>';
                    } elseif ($s->status === 'P') {
                        $row['status'] = '<div class="text-center"><span class="badge badge-light-primary fw-bold px-3 py-2">Menunggu Pembayaran</span></div>';
                    } elseif ($s->status === 'V') {
                        $row['status'] = '<div class="text-center"><span class="badge badge-light-info fw-bold px-3 py-2">Menunggu Approval</span></div>';
                    } elseif ($s->status === 'E') {
                        $row['status'] = '<div class="text-center"><span class="badge badge-light-danger fw-bold px-3 py-2">Expired</span></div>';
                    } elseif ($s->status === 'M') {
                        $row['status'] = '<div class="text-center"><span class="badge badge-light-warning fw-bold px-3 py-2">Proses Pembayaran</span></div>';
                    } elseif ($s->status === 'DM') {
                        $row['status'] = '<div class="text-center"><span class="badge badge-light-danger fw-bold px-3 py-2">Denied</span></div>';
                    } elseif ($s->status === 'PM') {
                        $row['status'] = '<div class="text-center"><span class="badge badge-light-warning fw-bold px-3 py-2">Pending</span></div>';
                    } else {
                        $row['status'] = '<div class="text-center"><span class="badge badge-light-danger fw-bold px-3 py-2">Expired</span></div>';
                    }

                    // AKSI
                    $row['aksi'] = '
                <div class="text-center">
                    <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        Aksi <i class="ki-duotone ki-down fs-5 ms-1"></i>
                    </a>
                    
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4 text-start" data-kt-menu="true">
                        
                        <div class="menu-item px-3">
                            <a href="javascript:void(0)" class="menu-link px-3 validasi-transaksi" data-bs-toggle="modal" data-bs-target="#validasi_transaksi" data-transaksi="' . $id_enc . '">
                                <i class="ki-duotone ki-setting-2 fs-4 me-2 text-gray-500"><span class="path1"></span><span class="path2"></span></i> Detail Transaksi
                            </a>
                        </div>';

                    if ($s->status == 'S') {
                        $row['aksi'] .= '
                        <div class="menu-item px-3">
                            <a href="javascript:void(0)" class="menu-link px-3 text-success invoice_cetak" data-bs-toggle="modal" data-bs-target="#invoice_cetak_modal" data-invoice="' . base_url('sw-admin/transaksi/invoice/' . $id_enc) . '">
                                <i class="ki-duotone ki-file-down fs-4 me-2 text-success"><span class="path1"></span><span class="path2"></span></i> Unduh Invoice
                            </a>
                        </div>';
                    } else {
                        $row['aksi'] .= '
                        <div class="menu-item px-3">
                            <a href="' . base_url('sw-admin/transaksi/approve-manual/' . $id_enc) . '" class="menu-link px-3 text-primary" id="approve">
                                <i class="ki-duotone ki-check-square fs-4 me-2 text-primary"><span class="path1"></span><span class="path2"></span></i> Approve Transaksi
                            </a>
                        </div>
                        
                        <div class="separator mt-3 opacity-75"></div>
                        
                        <div class="menu-item px-3 mt-3">
                            <a href="' . base_url('sw-admin/transaksi/hapus-transaksi-siswa/' . $id_enc) . '" class="menu-link px-3 text-danger btn-delete" id="hapus">
                                <i class="ki-duotone ki-trash fs-4 me-2 text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Hapus Transaksi
                            </a>
                        </div>';
                    }

                    $row['aksi'] .= '
                    </div>
                </div>';

                    $results[] = $row;
                }

                return $this->response->setJSON([
                    'draw'             => (int) $request->getPost('draw'),
                    'recordsTotal'     => $this->transaksiModel->countAllData(),
                    'recordsFiltered'  => $totalFiltered,
                    'data'             => $results,
                    'total_pendapatan' => number_format($totalPendapatan, 0, ',', '.'),
                    'total_manual'     => number_format($totalManual, 0, ',', '.'),
                    'total_midtrans'   => number_format($totalMidtrans, 0, ',', '.'),
                    'csrf_hash'        => csrf_hash()
                ]);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'draw'             => 0,
                    'recordsTotal'     => 0,
                    'recordsFiltered'  => 0,
                    'data'             => [],
                    'total_pendapatan' => '0',
                    'total_manual'     => '0',
                    'total_midtrans'   => '0',
                    'error'            => $e->getMessage()
                ]);
            }
        }
    }
    public function validasiTransaksi()
    {
        if ($this->request->isAJAX()) {
            try {
                $id = decrypt_url($this->request->getVar('idtransaksi'));
                $data = $this->transaksiModel->getById($id);

                if (!$data) throw new \Exception("Data transaksi tidak ditemukan.");

                // Pastikan data dikonversi ke array agar mudah dimanipulasi
                $result = (array) $data;

                // Masukkan CSRF dengan KEY yang benar agar terbaca oleh data[csrfName] di JS
                $result[csrf_token()] = csrf_hash();

                return $this->response->setJSON($result);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    csrf_token() => csrf_hash() // Tetap kirim token baru meski error
                ]);
            }
        }
    }

    public function approveManual($id)
    {
        $db = $this->db;
        $idtransaksi = decrypt_url($id);
        $now = date("Y-m-d H:i:s");

        $db->transBegin();

        try {
            // 1. Update Status Transaksi Utama
            $this->transaksiModel->update($idtransaksi, [
                'status' => 'S',
                'tgl_pembayaran' => $now
            ]);

            // 2. Ambil data transaksi
            $datatransaksi = $this->transaksiModel
                ->join('detail_transaksi', 'detail_transaksi.idtransaksi=transaksi.idtransaksi')
                ->where('transaksi.idtransaksi', $idtransaksi)
                ->where('transaksi.status', 'S') // Pastikan statusnya memang sudah S
                ->groupBy('detail_transaksi.idpaket')
                ->get()->getResultObject();

            if (empty($datatransaksi)) {
                throw new \Exception("Data transaksi tidak ditemukan atau gagal diupdate.");
            }

            // Simpan ID Siswa untuk keperluan notifikasi di luar loop
            $idSiswaTujuan = $datatransaksi[0]->idsiswa;

            foreach ($datatransaksi as $rowst) {
                // Mapping Mapel Siswa
                if ($rowst->idmapel != '0') {
                    $this->mapelSiswaModel->save([
                        'idmapel' => $rowst->idmapel,
                        'idsiswa' => $rowst->idsiswa
                    ]);
                }

                // Ambil Paket
                $datapaket = $this->detailPaketModel
                    ->join('ujian_master', 'detail_paket.id_ujian=ujian_master.id_ujian')
                    ->where('idpaket', $rowst->idpaket)
                    ->groupBy('detail_paket.id_ujian')
                    ->get()->getResultObject();

                foreach ($datapaket as $rowsp) {
                    // Menambah ujian ke siswa
                    $this->ujianModel->save([
                        'id_siswa'   => $rowst->idsiswa,
                        'kode_ujian' => $rowsp->kode_ujian,
                        'nama_ujian' => $rowsp->nama_ujian,
                        'guru'       => $rowsp->guru,
                        'kelas'      => $rowsp->kelas,
                        'mapel'      => $rowsp->mapel,
                        'date_created' => time(),
                    ]);

                    // Reset status ujian siswa jika sebelumnya sudah ada
                    $this->ujianSiswaModel->where('ujian', $rowsp->kode_ujian)
                        ->where('siswa', $rowst->idsiswa)
                        ->set([
                            'jawaban' => null,
                            'benar'   => null,
                            'jam'     => null,
                            'status'  => null,
                        ])->update();
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
                        $insertResult = $this->ikhModel->insert($dataInsertLengkap);

                        // 4. Debugging (Bisa kamu hapus/komentari kalau sudah berhasil masuk DB)
                        if ($insertResult === false) {
                            dd([
                                'Pesan Gagal Model' => $this->ikhModel->errors(),
                                'Pesan Gagal DB Server' => $this->ikhModel->db->error()
                            ]);
                        }
                    }
                }
            }

            // 3. Update Afiliasi
            $this->affiliateCommissionModel->where('id_transaksi', $idtransaksi)
                ->set([
                    'status'           => 'approved',
                    'tgl_approved'     => $now,
                    'status_penarikan' => 'pending'
                ])->update();

            $db->transCommit();

            // 4. Kirim Notifikasi (Gunakan variabel $idSiswaTujuan yang sudah aman)
            send_notif($idSiswaTujuan, "Verifikasi pembayaran", "Pembelian paket berhasil.", base_url('sw-siswa/transaksi'));

            $dataSiswa = $this->siswaModel->find($idSiswaTujuan);
            if ($dataSiswa) {
                $this->serviceEmail->send(
                    $dataSiswa['email'],
                    "Verifikasi Pembayaran - KelasBrevet",
                    "Halo <b>{$dataSiswa['nama_siswa']}</b>,<br>Pembayaran Anda telah kami verifikasi. Selamat belajar!"
                );
            }

            return redirect()->to('sw-admin/transaksi')->with('success', 'Pembayaran berhasil diverifikasi.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function approveTransaksi()
    {
        if (session()->get('role') != 1) return redirect()->to('auth');

        $db = $this->db;
        $db->transBegin(); // Menggunakan Database Transaction agar aman

        try {
            $idTransaksi = $this->request->getVar('idtransaksi');
            $status = $this->request->getVar('status');
            $idsiswa = $this->request->getVar('idsiswa');

            if ($status != "P") {
                // Update Status Transaksi
                $this->transaksiModel->update($idTransaksi, ['status' => $status]);

                // Logic Penambahan Ujian ke Siswa
                $datatransaksi = $this->transaksiModel->where('idtransaksi', $idTransaksi)->where('status', 'S')->first();

                if ($datatransaksi) {
                    $datapaket = $this->detailPaketModel->join('ujian_master', 'detail_paket.id_ujian=ujian_master.id_ujian')
                        ->where('idpaket', $datatransaksi->idpaket)
                        ->groupBy('detail_paket.id_ujian')
                        ->findAll();

                    foreach ($datapaket as $row) {
                        $this->ujianModel->save([
                            'id_siswa'   => $idsiswa,
                            'kode_ujian' => $row->kode_ujian,
                            'nama_ujian' => $row->nama_ujian,
                            'guru'       => $row->guru,
                            'kelas'      => $row->kelas,
                            'mapel'      => $row->mapel,
                            'date_created' => time(),
                        ]);
                    }
                }

                // Kirim Email (Disederhanakan)
                $this->serviceEmail->send(
                    $this->request->getVar('email'),
                    "Verifikasi Pembayaran - KelasBrevet",
                    "Pembayaran Anda telah berhasil diverifikasi."
                );
            } else {
                // Logic Penolakan / Expired
                $tgl_exp = date('Y-m-d H:i:s', strtotime('+1 day'));
                $this->transaksiModel->update($idTransaksi, [
                    'status' => $status,
                    'tgl_exp' => $tgl_exp,
                    'tgl_pembayaran' => null,
                    'bukti_pembayaran' => null,
                    'keterangan' => $this->request->getVar('keterangan'),
                ]);

                // Hapus Thumbnail jika ada
                $path = './uploads/transaksi/thumbnails/' . $this->request->getVar('bukti_pembayaran');
                if (file_exists($path)) unlink($path);
            }

            $db->transCommit();
            return redirect()->to('sw-admin/transaksi')->with('success', 'Transaksi berhasil diproses.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function hapusTransaksiSiswa($id)
    {
        $idtransaksi = decrypt_url($id);
        $dataDrop  =   $this->db->query("select * from transaksi where idtransaksi='$idtransaksi'")->getRow();
        if ($dataDrop != null) {
            //untuk menghapus file bukti transaksi
            if ($dataDrop->bukti_pembayaran != '') {
                if (file_exists('./uploads/transaksi/thumbnails/' . $dataDrop->bukti_pembayaran)) {
                    unlink('./uploads/transaksi/thumbnails/' . $dataDrop->bukti_pembayaran);
                };
            }
            $data = $this->detailTransaksiModel->where('idtransaksi', $dataDrop->idtransaksi)->get()->getResultObject();
            foreach ($data as $rows) {
                $this->detailTransaksiModel->delete($rows->iddetailtransaksi);
            }
            $this->transaksiModel->delete($dataDrop->idtransaksi);
        }
        return redirect()->to('sw-admin/transaksi')->with('success', 'Transaksi berhasil dibatalkan');
    }

    public function hapusTransaksi()
    {
        // Ambil SEMUA transaksi yang memenuhi kriteria kedaluwarsa
        $dataDrop = $this->db->query("SELECT * FROM transaksi WHERE status IN ('P', 'M','PM', 'DM', 'E') AND tgl_drop <= NOW()")->getResultObject();

        // Cek apakah ada data yang perlu dihapus
        if (!empty($dataDrop)) {

            $berhasil = 0;
            $gagal = 0;

            // Looping setiap transaksi yang kedaluwarsa
            foreach ($dataDrop as $transaksi) {

                // 1. Mulai transaksi database untuk SATU idtransaksi ini saja
                $this->db->transBegin();

                try {
                    // Ambil data detail transaksi (child)
                    $details = $this->detailTransaksiModel->where('idtransaksi', $transaksi->idtransaksi)->get()->getResultObject();

                    // Hapus semua child
                    foreach ($details as $detail) {
                        $this->detailTransaksiModel->delete($detail->iddetailtransaksi);
                    }

                    // Hapus parent (transaksi utama)
                    $this->transaksiModel->delete($transaksi->idtransaksi);

                    // Cek status query di background
                    if ($this->db->transStatus() === false) {
                        throw new \Exception('Query gagal dieksekusi oleh database.');
                    }

                    // Jika sukses, simpan perubahan untuk transaksi ini
                    $this->db->transCommit();
                    $berhasil++;
                } catch (\Exception $e) {
                    // Jika error, kembalikan data HANYA untuk transaksi ini
                    $this->db->transRollback();
                    $gagal++;
                }
            }
            echo "Proses selesai. Berhasil: $berhasil, Gagal: $gagal.";
        }
    }

    public function exportExcel()
    {
        // 1. Tangkap parameter filter yang dikirim dari frontend (sama persis dengan datatables)
        $request         = $this->request;
        $filter_bulan    = $request->getGet('filter_bulan_range');
        $filter_paket    = $request->getGet('filter_paket');
        $status_afiliasi = $request->getGet('filter_status_afiliasi');
        $search          = $request->getGet('search'); // Jika ingin menghormati kata kunci pencarian aktif

        // 2. Ambil builder dari model
        $query = $this->transaksiModel->getBaseQuery();

        // 3. Terapkan Filter Pencarian (Search)
        if (!empty($search)) {
            $query->groupStart()
                ->like('b.nama_siswa', $search)
                ->orLike('c.nama_paket', $search)
                ->orLike('transaksi.idtransaksi', $search)
                ->groupEnd();
        }

        // 4. Terapkan Filter Range Bulan (created_at)
        if (!empty($filter_bulan)) {
            $dates = explode(' - ', $filter_bulan);
            if (count($dates) == 2) {
                $start_date = trim($dates[0]) . '-01 00:00:00';
                $end_date   = date('Y-m-t 23:59:59', strtotime(trim($dates[1]) . '-01'));

                $query->where('transaksi.created_at >=', $start_date);
                $query->where('transaksi.created_at <=', $end_date);
            }
        }

        // 5. Terapkan Filter Paket
        if ($filter_paket == '1') {
            $query->whereIn('c.v_ujian', ['all', '1']);
        } elseif ($filter_paket == '2') {
            $query->whereIn('c.v_materi', ['all', '1']);
        } elseif ($filter_paket == '3') {
            $query->like('c.jenis_paket', '"ikh"');
        }

        // 6. Terapkan Filter Status Afiliasi
        if ($status_afiliasi === '0') {
            $query->where('b.idafiliasi IS NULL');
        } elseif ($status_afiliasi === '1') {
            $query->where('b.idafiliasi !=', null);
        }

        // Ambil data (urutkan berdasarkan data terbaru)
        $dataTransaksi = $query->orderBy('transaksi.created_at', 'DESC')->get()->getResultObject();

        // 7. Buat Header File CSV untuk Download Excel
        $filename = 'Laporan_Pembayaran_' . date('Y-m-d_H-i-s') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Tambahkan BOM agar karakter UTF-8 terbaca sempurna di Microsoft Excel
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($output, ['No', 'Nama Peserta', 'Nama Paket', 'Tanggal Pelatihan', 'Profesi'], ',');

        // Masukkan Data Baris ke CSV: Ubah pemisah akhir menjadi ','
        $no = 1;
        foreach ($dataTransaksi as $row) {
            $tglPelatihan = !empty($row->created_at) ? date('d-m-Y', strtotime($row->created_at)) : '-';

            fputcsv($output, [
                $no++,
                $row->nama_siswa ?? '-',
                $row->nama_paket ?? '-',
                $tglPelatihan,
                $row->profesi ?? '-'
            ], ',');
        }

        fclose($output);
        exit;
    }
}

<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\SiswaModel;
use App\Models\GuruModel;
use App\Models\MitraModel;
use App\Models\PicModel;
use App\Models\MapelModel;
use App\Models\KelasModel;
use App\Models\TransaksiModel;
use App\Models\UjianMasterModel;
use App\Models\UjianModel;

class HomeController extends BaseController
{
    protected $adminModel;
    protected $guruModel;
    protected $siswaModel;
    protected $kelasModel;
    protected $mapelModel;
    protected $mitraModel;
    protected $picModel;
    protected $ujianModel;
    protected $ujianMasterModel;
    protected $transaksiModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
        $this->guruModel = new GuruModel();
        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();
        $this->mapelModel = new MapelModel();
        $this->mitraModel = new MitraModel();
        $this->picModel = new PicModel();
        $this->ujianModel = new UjianModel();
        $this->ujianMasterModel = new UjianMasterModel();
        $this->transaksiModel = new TransaksiModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')]
        ];

        // 1. Data Pengajar
        $data['guru'] = $this->guruModel->asObject()->findAll();
        $data['guru_aktif'] = $this->guruModel->where('is_active', 1)->get()->getResultObject();
        $data['guru_tidak_aktif'] = $this->guruModel->where('is_active', 0)->get()->getResultObject();

        // 2. Data Siswa
        $data['siswa'] = $this->siswaModel->asObject()->findAll();
        $data['siswa_aktif'] = $this->siswaModel->where('is_active', 1)->get()->getResultObject();
        $data['siswa_tidak_aktif'] = $this->siswaModel->where('is_active', 0)->get()->getResultObject();

        // 3. Mitra & PIC
        $data['mitra'] = $this->mitraModel->where('is_active', 1)->get()->getResultObject();
        $data['pic'] = $this->picModel->where('is_active', 1)->get()->getResultObject();

        // 4. Mapel & Kelas
        $data['mapel'] = $this->mapelModel->asObject()->findAll();
        $data['kelas'] = $this->kelasModel->asObject()->findAll();

        // 5. Peserta Lulus (Sertifikat AB)
        $sertifikatAB = $this->siswaModel->getSertifikatAB();
        $lulus = 0;
        foreach ($sertifikatAB as $s) {
            $dataUjian = $this->ujianMasterModel->where('kelas', $s->kelas)->groupBy('mapel')->get()->getResultObject();
            $total = count($dataUjian);

            $totalUjian = $this->ujianModel->where('kelas', $s->kelas)->where('id_siswa', $s->id_siswa)
                ->where('ujian.nilai >=', 60)
                ->groupBy('ujian.mapel')->get()->getResultObject();
            $totalSertifikat = count($totalUjian);

            if ($total != 0 && $totalSertifikat >= $total) {
                $lulus++;
            }
        }
        $data['peserta_lulus'] = $lulus;

        // 6. Transaksi Berhasil Keseluruhan
        $transaksiSuccess = $this->transaksiModel->join('detail_transaksi d', 'd.idtransaksi=transaksi.idtransaksi')
            ->join('paket c', 'c.idpaket = d.idpaket')
            ->where('transaksi.status', 'S')
            ->groupBy('transaksi.idtransaksi')
            ->get()->getResultObject();

        $totalPendapatan = 0;
        foreach ($transaksiSuccess as $row) {
            $diskon = ($row->nominal * $row->diskon) / 100;
            $totalDiskon = $row->nominal - $diskon;
            $diskon_voucher = ($totalDiskon * $row->voucher) / 100;
            $totalPendapatan += ($row->nominal - $diskon - $diskon_voucher);
        }
        $data['total_pendapatan'] = $totalPendapatan;
        $data['total_transaksi'] = count($transaksiSuccess);

        // 7. Data Analitik Tambahan untuk Metronic 8 Widget
        // Transaksi Pending & Menunggu Konfirmasi
        $data['transaksi_pending'] = $this->transaksiModel->whereIn('status', ['P', 'V'])->countAllResults();

        // Total Affiliate Terdaftar
        $data['total_affiliate'] = $db->table('affiliates')->countAllResults();

        // Total Pengajuan Sertifikat Fisik yang Pending
        $data['sertifikat_pending'] = $db->table('pengajuan_sertifikat')->where('status_pengiriman', '0')->countAllResults();

        // 8. Daftar Tahun untuk Filter Transaksi
        $listTahun = $db->table('transaksi')
            ->select('YEAR(created_at) AS tahun', false)
            ->where('status', 'S')
            ->groupBy('tahun')
            ->orderBy('tahun', 'DESC')
            ->get()->getResultArray();

        if (empty($listTahun)) {
            $listTahun = [['tahun' => date('Y')]];
        }
        $data['listTahun'] = $listTahun;

        // 9. Transaksi Terbaru (5 data terakhir)
        $data['transaksi_terbaru'] = $this->transaksiModel
            ->select('transaksi.*, siswa.nama_siswa, siswa.email, paket.nama_paket')
            ->join('detail_transaksi dt', 'dt.idtransaksi = transaksi.idtransaksi', 'left')
            ->join('paket', 'paket.idpaket = dt.idpaket', 'left')
            ->join('siswa', 'siswa.id_siswa = transaksi.idsiswa', 'left')
            ->orderBy('transaksi.created_at', 'DESC')
            ->groupBy('transaksi.idtransaksi')
            ->limit(6)
            ->get()->getResultObject();

        return view('admin/dashboard', $data);
    }

    /**
     * Endpoint AJAX untuk filter report grafik Metronic 8
     */
    public function getDashboardData()
    {
        $db = \Config\Database::connect();
        $periode = $this->request->getGet('periode') ?? 'this_year'; // 'this_year', 'last_6_months', 'this_month', 'all'
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        // A. Grafik Tren Pendapatan & Registrasi Siswa per Bulan
        $months = [];
        $pendapatanSeries = [];
        $siswaSeries = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthNum = str_pad($m, 2, '0', STR_PAD_LEFT);
            $monthName = date('M', mktime(0, 0, 0, $m, 10));
            $months[] = $monthName;

            // Pendapatan bulan ini
            $trxBulan = $db->table('transaksi')
                ->select('nominal, diskon, voucher')
                ->where('status', 'S')
                ->where("YEAR(created_at)", $tahun)
                ->where("MONTH(created_at)", $m)
                ->get()->getResultObject();

            $sumBulan = 0;
            foreach ($trxBulan as $trx) {
                $potongan = ($trx->nominal * $trx->diskon) / 100;
                $setelahDiskon = $trx->nominal - $potongan;
                $potonganVoucher = ($setelahDiskon * $trx->voucher) / 100;
                $sumBulan += ($trx->nominal - $potongan - $potonganVoucher);
            }
            $pendapatanSeries[] = (int) $sumBulan;

            // Siswa terdaftar bulan ini
            $countSiswa = $db->table('siswa')
                ->where("YEAR(date_created)", $tahun)
                ->where("MONTH(date_created)", $m)
                ->countAllResults();
            $siswaSeries[] = (int) $countSiswa;
        }

        // B. Distribusi Paket Terlaris (Donut/Pie Chart)
        $paketTerlaris = $db->table('detail_transaksi dt')
            ->select('p.nama_paket as label, COUNT(DISTINCT dt.idtransaksi) as total')
            ->join('paket p', 'p.idpaket = dt.idpaket')
            ->join('transaksi t', 't.idtransaksi = dt.idtransaksi')
            ->where('t.status', 'S')
            ->groupBy('dt.idpaket, p.nama_paket')
            ->orderBy('total', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        $paketLabels = [];
        $paketSeries = [];
        foreach ($paketTerlaris as $pt) {
            $paketLabels[] = strlen($pt['label']) > 20 ? substr($pt['label'], 0, 18) . '...' : $pt['label'];
            $paketSeries[] = (int) $pt['total'];
        }

        // C. Status Kelulusan Ujian Peserta (Donut)
        $ujianLulus = $db->table('ujian')->where('nilai >=', 60)->countAllResults();
        $ujianTidakLulus = $db->table('ujian')->where('nilai <', 60)->where('status', 'E')->countAllResults();
        $ujianBerjalan = $db->table('ujian')->where('status', 'U')->countAllResults();

        // D. Rasio Metode Pembayaran
        $metodeBayar = $db->table('transaksi')
            ->select('jenis_bayar, COUNT(idtransaksi) as total')
            ->where('status', 'S')
            ->where('jenis_bayar IS NOT NULL')
            ->groupBy('jenis_bayar')
            ->get()->getResultArray();

        $bayarLabels = [];
        $bayarSeries = [];
        foreach ($metodeBayar as $mb) {
            $bayarLabels[] = strtoupper($mb['jenis_bayar'] ?? 'LAINNYA');
            $bayarSeries[] = (int) $mb['total'];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'tahun' => $tahun,
            'months' => $months,
            'pendapatan' => $pendapatanSeries,
            'siswa_baru' => $siswaSeries,
            'paket_terlaris' => [
                'labels' => $paketLabels,
                'series' => $paketSeries
            ],
            'status_ujian' => [
                'labels' => ['Lulus (Nilai >= 60)', 'Belum Lulus', 'Sedang Mengerjakan'],
                'series' => [(int) $ujianLulus, (int) $ujianTidakLulus, (int) $ujianBerjalan]
            ],
            'metode_bayar' => [
                'labels' => $bayarLabels,
                'series' => $bayarSeries
            ]
        ]);
    }
}

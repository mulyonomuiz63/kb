<?= $this->extend('template/app'); ?>

<?= $this->section('styles'); ?>
<style>
    .kb-metric-card {
        border-radius: 14px;
        background: #ffffff;
        border: 1px solid #eef2f7;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }
    .kb-metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08) !important;
        border-color: rgba(0, 158, 247, 0.2);
    }
    .kb-icon-box {
        width: 46px;
        height: 46px;
        min-width: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .kb-metric-card .card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .kb-footer-pill {
        padding: 8px 12px;
        border-radius: 8px;
        min-height: 40px;
        display: flex;
        align-items: center;
    }
    .kb-chart-container {
        min-height: 330px;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<?php $db = Config\Database::connect(); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <!-- ============================================================== -->
            <!-- 1. TOOLBAR & FILTER HEADER                                    -->
            <!-- ============================================================== -->
            <div class="card card-flush shadow-sm mb-6 border-0">
                <div class="card-body py-4 px-6">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-4">
                        <!-- Judul & Quick Context -->
                        <div class="d-flex flex-column">
                            <h2 class="fw-bolder text-gray-900 mb-1 d-flex align-items-center gap-2">
                                <span class="svg-icon svg-icon-primary svg-icon-1">
                                    <i class="ki-duotone ki-chart-line-star fs-1 text-primary">
                                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                    </i>
                                </span>
                                Executive Dashboard & Analytics
                            </h2>
                            <span class="text-muted fs-7 fw-semibold">Pantau performa penjualan, aktivitas peserta, dan kelulusan secara real-time</span>
                        </div>

                        <!-- Header Action -->
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <!-- Tombol Refresh -->
                            <button id="btn_refresh_dashboard" class="btn btn-sm btn-light-primary fw-bold d-flex align-items-center gap-1">
                                <i class="ki-duotone ki-arrows-circle fs-3"><span class="path1"></span><span class="path2"></span></i>
                                <span>Segarkan Data</span>
                            </button>

                            <!-- Date Badge -->
                            <div class="badge badge-light-primary px-3 py-2 fw-bold fs-7 d-none d-md-flex align-items-center gap-1">
                                <i class="ki-duotone ki-calendar-8 fs-5 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                                <?= date('d M Y') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- 2. SUMMARY KPI STATISTIC CARDS (METRONIC 8 WIDGETS)          -->
            <!-- ============================================================== -->
            <div class="row g-5 g-xl-6 mb-6">

                <!-- KPI 1: Total Pendapatan -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card kb-metric-card h-100 p-6">
                        <!-- Top Header: Title & Icon -->
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <span class="text-gray-500 fw-bold fs-8 text-uppercase ls-1">TOTAL PENDAPATAN</span>
                            <div class="kb-icon-box bg-light-danger text-danger">
                                <i class="ki-duotone ki-dollar fs-2tx text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </div>
                        </div>

                        <!-- Main Metric Number -->
                        <div class="mb-4">
                            <div class="fs-2hx fw-bolder text-gray-900 lh-1" id="kpi_pendapatan">
                                Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?>
                            </div>
                        </div>

                        <!-- Footer Pill Status -->
                        <div class="mt-auto">
                            <div class="kb-footer-pill bg-light-danger justify-content-between">
                                <span class="text-danger fw-bold fs-7">Volume Transaksi</span>
                                <span class="badge badge-danger fs-8 fw-bolder"><?= $total_transaksi ?> Lunas</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI 2: Total Peserta & Rasio Aktif -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card kb-metric-card h-100 p-6">
                        <!-- Top Header: Title & Icon -->
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <span class="text-gray-500 fw-bold fs-8 text-uppercase ls-1">TOTAL SISWA</span>
                            <div class="kb-icon-box bg-light-primary text-primary">
                                <i class="ki-duotone ki-profile-user fs-2tx text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                            </div>
                        </div>

                        <!-- Main Metric Number -->
                        <div class="mb-4">
                            <div class="fs-2hx fw-bolder text-gray-900 lh-1">
                                <?= count($siswa); ?>
                            </div>
                        </div>

                        <!-- Footer Progress & Status -->
                        <div class="mt-auto">
                            <div class="kb-footer-pill bg-light-primary flex-column justify-content-center">
                                <div class="d-flex justify-content-between w-100 fs-8 fw-semibold text-gray-700 mb-1">
                                    <span>Aktif: <strong class="text-primary"><?= count($siswa_aktif); ?></strong></span>
                                    <span class="fw-bold text-primary"><?= count($siswa) > 0 ? round((count($siswa_aktif) / count($siswa)) * 100) : 0 ?>%</span>
                                </div>
                                <div class="h-6px w-100 bg-white rounded">
                                    <div class="bg-primary rounded h-6px" role="progressbar" style="width: <?= count($siswa) > 0 ? (count($siswa_aktif) / count($siswa)) * 100 : 0 ?>%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI 3: Peserta Lulus Sertifikasi -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card kb-metric-card h-100 p-6">
                        <!-- Top Header: Title & Icon -->
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <span class="text-gray-500 fw-bold fs-8 text-uppercase ls-1">SISWA LULUS</span>
                            <div class="kb-icon-box bg-light-success text-success">
                                <i class="ki-duotone ki-award fs-2tx text-success"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </div>
                        </div>

                        <!-- Main Metric Number -->
                        <div class="mb-4">
                            <div class="fs-2hx fw-bolder text-success lh-1">
                                <?= $peserta_lulus; ?>
                            </div>
                        </div>

                        <!-- Footer Pill Status -->
                        <div class="mt-auto">
                            <div class="kb-footer-pill bg-light-success justify-content-between">
                                <span class="text-success fw-bold fs-7">Status Akademik</span>
                                <span class="badge badge-success fs-8 fw-bolder">Sertifikat Resmi</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI 4: Partner & Affiliate Network -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card kb-metric-card h-100 p-6">
                        <!-- Top Header: Title & Icon -->
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <span class="text-gray-500 fw-bold fs-8 text-uppercase ls-1">JARINGAN MITRA & AFFILIATE</span>
                            <div class="kb-icon-box bg-light-warning text-warning">
                                <i class="ki-duotone ki-people fs-2tx text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </div>
                        </div>

                        <!-- Main Metric Number -->
                        <div class="mb-4">
                            <div class="fs-2hx fw-bolder text-warning lh-1">
                                <?= count($mitra) + (int)$total_affiliate; ?>
                            </div>
                        </div>

                        <!-- Footer Pill Status -->
                        <div class="mt-auto">
                            <div class="kb-footer-pill bg-light-warning justify-content-between gap-1">
                                <span class="badge badge-warning text-white fw-bold fs-8">Mitra: <?= count($mitra); ?></span>
                                <span class="badge badge-primary fw-bold fs-8">Affiliate: <?= (int)$total_affiliate; ?></span>
                                <span class="badge badge-info fw-bold fs-8">Guru: <?= count($guru_aktif); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ============================================================== -->
            <!-- 3. MAIN CHARTS REPORT (TREN & DISTRIBUSI)                      -->
            <!-- ============================================================== -->
            <div class="row g-5 g-xl-6 mb-6">

                <!-- Chart 1: Tren Pendapatan & Registrasi Siswa Bulanan -->
                <div class="col-xl-8">
                    <div class="card card-flush shadow-sm border-0 h-100">
                        <div class="card-header pt-5 border-0">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-gray-900 fs-4">Tren Pendapatan & Pertumbuhan Siswa</span>
                                <span class="text-gray-500 mt-1 fw-semibold fs-7">Grafik perkembangan finansial dan registrasi per bulan</span>
                            </h3>
                            <div class="card-toolbar">
                                <span class="badge badge-light-primary fw-bold me-2"><i class="fa fa-circle text-primary fs-8 me-1"></i> Pendapatan (Rp)</span>
                                <span class="badge badge-light-success fw-bold"><i class="fa fa-circle text-success fs-8 me-1"></i> Registrasi Siswa</span>
                            </div>
                        </div>
                        <div class="card-body pt-2">
                            <div id="chart_tren_pendapatan" class="w-100 kb-chart-container"></div>
                        </div>
                    </div>
                </div>

                <!-- Chart 2: Distribusi 5 Paket Terlaris -->
                <div class="col-xl-4">
                    <div class="card card-flush shadow-sm border-0 h-100">
                        <div class="card-header pt-5 border-0">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-gray-900 fs-4">Paket Terpopuler</span>
                                <span class="text-gray-500 mt-1 fw-semibold fs-7">Rasio volume penjualan paket pelatihan</span>
                            </h3>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-center pt-0">
                            <div id="chart_paket_donut" class="w-100 d-flex justify-content-center" style="min-height: 280px;"></div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ============================================================== -->
            <!-- 4. SECOND ROW: STATUS UJIAN, METODE BAYAR & TRANSAKSI TERBARU -->
            <!-- ============================================================== -->
            <div class="row g-5 g-xl-6 mb-6">

                <!-- Chart 3: Status Kelulusan Ujian -->
                <div class="col-md-6 col-xl-4">
                    <div class="card card-flush shadow-sm border-0 h-100">
                        <div class="card-header pt-5 border-0">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-gray-900 fs-5">Analisis Kelulusan Ujian</span>
                                <span class="text-gray-500 mt-1 fw-semibold fs-7">Hasil evaluasi kelulusan materi</span>
                            </h3>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-center pt-0">
                            <div id="chart_ujian_donut" class="w-100 d-flex justify-content-center" style="min-height: 250px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Chart 4: Metode Pembayaran (Online vs Manual) -->
                <div class="col-md-6 col-xl-4">
                    <div class="card card-flush shadow-sm border-0 h-100">
                        <div class="card-header pt-5 border-0">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-gray-900 fs-5">Kanal Pembayaran</span>
                                <span class="text-gray-500 mt-1 fw-semibold fs-7">Perbandingan jalur pembayaran masuk</span>
                            </h3>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-center pt-0">
                            <div id="chart_metode_donut" class="w-100 d-flex justify-content-center" style="min-height: 250px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Quick Action & Operasional Box -->
                <div class="col-xl-4">
                    <div class="card card-flush shadow-sm border-0 h-100">
                        <div class="card-header pt-5 border-0">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-gray-900 fs-5">Perlu Tindakan Admin</span>
                                <span class="text-gray-500 mt-1 fw-semibold fs-7">Antrean operasional dan pengawasan</span>
                            </h3>
                        </div>
                        <div class="card-body pt-2">
                            <div class="d-flex flex-column gap-3">
                                <!-- Item 1: Transaksi Pending -->
                                <a href="<?= base_url('sw-admin/transaksi') ?>" class="d-flex align-items-center justify-content-between p-3 rounded bg-light-warning text-decoration-none">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="symbol symbol-35px bg-warning bg-opacity-20 d-flex align-items-center justify-content-center rounded">
                                            <i class="ki-duotone ki-bill fs-2 text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-900 fw-bold fs-7">Konfirmasi Pembayaran</span>
                                            <span class="text-muted fs-8">Invoice status pending / menunggu verifikasi</span>
                                        </div>
                                    </div>
                                    <span class="badge badge-warning fw-bolder"><?= $transaksi_pending; ?></span>
                                </a>

                                <!-- Item 2: Pengiriman Sertifikat Fisik -->
                                <a href="<?= base_url('sw-admin/pengajuan-sertifikat') ?>" class="d-flex align-items-center justify-content-between p-3 rounded bg-light-primary text-decoration-none">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="symbol symbol-35px bg-primary bg-opacity-20 d-flex align-items-center justify-content-center rounded">
                                            <i class="ki-duotone ki-delivery-fast fs-2 text-primary"><span class="path1"></span><span class="path2"></span></i>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-900 fw-bold fs-7">Cetak & Resi Sertifikat</span>
                                            <span class="text-muted fs-8">Pengajuan cetak sertifikat fisik</span>
                                        </div>
                                    </div>
                                    <span class="badge badge-primary fw-bolder"><?= $sertifikat_pending; ?></span>
                                </a>

                                <!-- Item 3: Quick Link Siswa -->
                                <a href="<?= base_url('sw-admin/siswa') ?>" class="d-flex align-items-center justify-content-between p-3 rounded bg-light-info text-decoration-none">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="symbol symbol-35px bg-info bg-opacity-20 d-flex align-items-center justify-content-center rounded">
                                            <i class="ki-duotone ki-user fs-2 text-info"><span class="path1"></span><span class="path2"></span></i>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-900 fw-bold fs-7">Manajemen Siswa</span>
                                            <span class="text-muted fs-8">Kelola kuota, aktivasi & progres ujian</span>
                                        </div>
                                    </div>
                                    <span class="badge badge-info fw-bolder"><?= count($siswa); ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ============================================================== -->
            <!-- 5. RECENT TRANSACTIONS TABLE                                   -->
            <!-- ============================================================== -->
            <div class="card card-flush shadow-sm border-0 mb-6">
                <div class="card-header pt-5 border-0">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder text-gray-900 fs-4">Transaksi Terkini</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Daftar transaksi terbaru yang masuk ke sistem</span>
                    </h3>
                    <div class="card-toolbar">
                        <a href="<?= base_url('sw-admin/transaksi') ?>" class="btn btn-sm btn-light-primary fw-bold">
                            Lihat Semua Transaksi
                            <i class="ki-duotone ki-arrow-right fs-4 ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="ps-4 min-w-150px rounded-start">Siswa</th>
                                    <th class="min-w-150px">Paket Pelatihan</th>
                                    <th class="min-w-120px">Total Bayar</th>
                                    <th class="min-w-100px">Metode</th>
                                    <th class="min-w-100px">Status</th>
                                    <th class="min-w-120px text-end pe-4 rounded-end">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($transaksi_terbaru)): ?>
                                    <?php foreach ($transaksi_terbaru as $trx): ?>
                                        <?php
                                        $pot = ($trx->nominal * $trx->diskon) / 100;
                                        $totalTagihan = $trx->nominal - $pot - (($trx->nominal - $pot) * $trx->voucher / 100);
                                        ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-35px me-3">
                                                        <span class="symbol-label bg-light-primary text-primary fw-bold">
                                                            <?= strtoupper(substr($trx->nama_siswa ?? 'U', 0, 1)) ?>
                                                        </span>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span class="text-gray-900 fw-bold fs-7"><?= esc($trx->nama_siswa ?? 'Peserta') ?></span>
                                                        <span class="text-muted fs-8"><?= esc($trx->email ?? '-') ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-gray-800 fw-semibold fs-7"><?= esc($trx->nama_paket ?? 'Paket Brevet') ?></span>
                                            </td>
                                            <td>
                                                <span class="text-gray-900 fw-bold fs-7">Rp <?= number_format($totalTagihan, 0, ',', '.') ?></span>
                                            </td>
                                            <td>
                                                <span class="badge badge-light-dark fs-8 fw-semibold text-uppercase">
                                                    <?= esc($trx->jenis_bayar ?? 'Online') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($trx->status == 'S'): ?>
                                                    <span class="badge badge-light-success fs-8 fw-bold">Lunas</span>
                                                <?php elseif ($trx->status == 'P'): ?>
                                                    <span class="badge badge-light-warning fs-8 fw-bold">Pending</span>
                                                <?php elseif ($trx->status == 'V'): ?>
                                                    <span class="badge badge-light-info fs-8 fw-bold">Verifikasi</span>
                                                <?php else: ?>
                                                    <span class="badge badge-light-danger fs-8 fw-bold">Batal/Exp</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end pe-4 text-muted fs-8">
                                                <?= !empty($trx->created_at) ? date('d M Y H:i', strtotime($trx->created_at)) : '-' ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">Belum ada transaksi terbaru.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    // Variabel global chart instance untuk kemudahan re-render saat filter diubah
    var chartTren = null;
    var chartPaket = null;
    var chartUjian = null;
    var chartMetode = null;

    // Fungsi Format Rupiah
    function formatRupiah(num) {
        return 'Rp ' + Number(num).toLocaleString('id-ID');
    }

    // Fungsi Fetch dan Render Data Dashboard
    function loadDashboardData() {
        // Tampilkan indikator loading halus
        KTApp.showPageLoading();

        $.ajax({
            url: "<?= base_url('sw-admin/dashboard-filter') ?>",
            type: "GET",
            dataType: "json",
            success: function(res) {
                KTApp.hidePageLoading();
                if (res.status === 'success') {
                    renderChartTren(res.months, res.pendapatan, res.siswa_baru);
                    renderChartPaket(res.paket_terlaris.labels, res.paket_terlaris.series);
                    renderChartUjian(res.status_ujian.labels, res.status_ujian.series);
                    renderChartMetode(res.metode_bayar.labels, res.metode_bayar.series);
                }
            },
            error: function() {
                KTApp.hidePageLoading();
                console.error("Gagal memuat data analitik dashboard.");
            }
        });
    }

    // 1. Render Chart Tren Pendapatan & Siswa (Area + Bar Combo)
    function renderChartTren(months, pendapatan, siswaBaru) {
        var element = document.getElementById("chart_tren_pendapatan");
        if (!element) return;

        var options = {
            series: [{
                name: 'Pendapatan (Rp)',
                type: 'area',
                data: pendapatan
            }, {
                name: 'Registrasi Siswa',
                type: 'column',
                data: siswaBaru
            }],
            chart: {
                height: 330,
                type: 'line',
                stacked: false,
                toolbar: { show: false }
            },
            stroke: {
                width: [3, 0],
                curve: 'smooth'
            },
            colors: ['#009EF7', '#50CD89'],
            fill: {
                type: ['gradient', 'solid'],
                gradient: {
                    shade: 'light',
                    type: "vertical",
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            labels: months,
            xaxis: {
                categories: months,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: { colors: '#A1A5B7', fontSize: '12px' }
                }
            },
            yaxis: [{
                title: { text: 'Pendapatan (Rp)', style: { color: '#009EF7', fontSize: '11px' } },
                labels: {
                    style: { colors: '#A1A5B7' },
                    formatter: function(val) {
                        if (val >= 1000000) return (val / 1000000).toFixed(1) + ' Jt';
                        if (val >= 1000) return (val / 1000).toFixed(0) + ' Rb';
                        return val;
                    }
                }
            }, {
                opposite: true,
                title: { text: 'Siswa Baru', style: { color: '#50CD89', fontSize: '11px' } },
                labels: {
                    style: { colors: '#A1A5B7' },
                    formatter: function(val) { return val.toFixed(0); }
                }
            }],
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (y, opts) {
                        if (opts.seriesIndex === 0) return formatRupiah(y);
                        return y + ' Siswa';
                    }
                }
            },
            legend: { show: false }
        };

        if (chartTren) {
            chartTren.updateOptions(options);
        } else {
            chartTren = new ApexCharts(element, options);
            chartTren.render();
        }
    }

    // 2. Render Chart Paket Donut
    function renderChartPaket(labels, series) {
        var element = document.getElementById("chart_paket_donut");
        if (!element) return;

        // Fallback jika belum ada data
        if (!series || series.length === 0 || series.every(v => v === 0)) {
            labels = ['Belum ada data'];
            series = [1];
        }

        var options = {
            series: series,
            labels: labels,
            chart: {
                type: 'donut',
                height: 270
            },
            colors: ['#009EF7', '#50CD89', '#F1416C', '#FFC700', '#7239EA'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Order',
                                fontSize: '12px',
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                }
                            }
                        }
                    }
                }
            },
            legend: {
                position: 'bottom',
                fontSize: '11px'
            },
            dataLabels: { enabled: false }
        };

        if (chartPaket) {
            chartPaket.updateOptions(options);
        } else {
            chartPaket = new ApexCharts(element, options);
            chartPaket.render();
        }
    }

    // 3. Render Chart Ujian Donut
    function renderChartUjian(labels, series) {
        var element = document.getElementById("chart_ujian_donut");
        if (!element) return;

        var options = {
            series: series,
            labels: labels,
            chart: {
                type: 'donut',
                height: 240
            },
            colors: ['#50CD89', '#F1416C', '#FFC700'],
            legend: {
                position: 'bottom',
                fontSize: '11px'
            },
            plotOptions: {
                pie: { donut: { size: '60%' } }
            },
            dataLabels: { enabled: false }
        };

        if (chartUjian) {
            chartUjian.updateOptions(options);
        } else {
            chartUjian = new ApexCharts(element, options);
            chartUjian.render();
        }
    }

    // 4. Render Chart Metode Pembayaran Donut
    function renderChartMetode(labels, series) {
        var element = document.getElementById("chart_metode_donut");
        if (!element) return;

        if (!series || series.length === 0) {
            labels = ['ONLINE', 'MANUAL'];
            series = [0, 0];
        }

        var options = {
            series: series,
            labels: labels,
            chart: {
                type: 'pie',
                height: 240
            },
            colors: ['#009EF7', '#7239EA', '#50CD89'],
            legend: {
                position: 'bottom',
                fontSize: '11px'
            },
            dataLabels: { enabled: true }
        };

        if (chartMetode) {
            chartMetode.updateOptions(options);
        } else {
            chartMetode = new ApexCharts(element, options);
            chartMetode.render();
        }
    }

    // Event Listener Tombol Refresh
    $('#btn_refresh_dashboard').on('click', function(e) {
        e.preventDefault();
        loadDashboardData();
    });

    // Inisialisasi Pertama Kali setelah DOM Ready
    $(document).ready(function() {
        loadDashboardData();
    });
</script>
<?= $this->endSection(); ?>

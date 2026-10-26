<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <!-- 📊 STATISTIK & GRAFIK ANALITIK -->
            <div class="row g-5 g-xl-8 mb-8">
                <!-- Statistik Cards -->
                <div class="col-xl-4">
                    <div class="row g-5 g-xl-8">
                        <div class="col-12">
                            <div class="card bg-primary hoverable card-xl-stretch shadow-sm">
                                <div class="card-body">
                                    <i class="ki-duotone ki-people fs-3x text-white mb-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                    <div class="text-white fw-bold fs-2 mb-1 mt-3"><?= number_format($totalAffiliate, 0, ',', '.') ?> Peserta</div>
                                    <div class="fw-semibold text-white fs-6">Total Affiliate Aktif</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card bg-warning hoverable card-xl-stretch shadow-sm">
                                <div class="card-body">
                                    <i class="ki-duotone ki-wallet fs-3x text-white mb-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                    <div class="text-white fw-bold fs-2 mb-1 mt-3">Rp <?= number_format($totalKomisiPending, 0, ',', '.') ?></div>
                                    <div class="fw-semibold text-white fs-6">Komisi Menunggu Pending</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grafik Analitik -->
                <div class="col-xl-8">
                    <div class="card card-flush shadow-sm h-xl-100 border-0">
                        <div class="card-header pt-5 border-0">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">Kinerja Komisi Affiliate (<?= $tahunSekarang ?>)</span>
                                <span class="text-gray-400 mt-1 fw-semibold fs-7">Perbandingan Komisi Menunggu Cair vs Sudah Cair per Bulan</span>
                            </h3>
                        </div>
                        <div class="card-body pt-0 pb-5">
                            <div id="kt_affiliate_chart" style="height: 300px"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 📋 TABEL DAFTAR PESERTA -->
            <div class="card card-flush shadow-sm border-0">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title flex-column align-items-start">
                        <span class="card-label fw-bold text-gray-800">Daftar Peserta</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Peserta yang terdaftar sebagai affiliate. Prioritas komisi pending berada di atas.</span>
                    </div>
                    
                    <div class="card-toolbar">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-affiliate-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Affiliate..." />
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatables-list" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Peserta</th>
                                    <th class="min-w-150px">Tgl Daftar</th>
                                    <th class="text-end min-w-200px">Detail Komisi</th>
                                    <th class="text-center min-w-100px">Status</th>
                                    <th class="text-center min-w-100px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function () {
    let table = $('#datatables-list').DataTable({
        processing: true,
        serverSide: true,
        ordering: false,
        ajax: {
            url: "<?= base_url('sw-admin/affiliate/datatables') ?>",
            type: "POST",
            data: function (d) {
                d[csrfName] = csrfHash;
            },
            dataSrc: function (json) {
                if(json[csrfName]) {
                    csrfHash = json[csrfName];
                }
                return json.data;
            }
        },
        columnDefs: [
            { targets: 2, className: "text-end align-top pt-4" },    // Komisi
            { targets: 3, className: "text-center align-top pt-4" }, // Status
            { targets: 4, className: "text-center align-top pt-4" }  // Aksi
        ],
        drawCallback: function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
            if (typeof KTMenu !== 'undefined') {
                KTMenu.createInstances();
            }
        }
    });

    // Fitur Pencarian Custom
    $('[data-kt-affiliate-table-filter="search"]').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Inisialisasi Grafik ApexCharts Dinamis
    var grafikData = <?= $chartData ?>;

    var initChart = function () {
        var element = document.getElementById("kt_affiliate_chart");
        if (!element) return;

        var options = {
            series: [{
                name: 'Komisi Dicairkan',
                data: grafikData.cair
            }, {
                name: 'Komisi Pending',
                data: grafikData.pending
            }],
            chart: {
                fontFamily: 'inherit',
                type: 'area',
                height: 300,
                toolbar: { show: false }
            },
            colors: ['#50CD89', '#FFC700'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.1,
                    stops: [0, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#A1A5B7', fontSize: '12px' } }
            },
            yaxis: {
                labels: {
                    style: { colors: '#A1A5B7', fontSize: '12px' },
                    formatter: function (val) {
                        if (val >= 1000000) {
                            return "Rp " + (val / 1000000).toFixed(1) + " Jt";
                        } else if (val >= 1000) {
                            return "Rp " + (val / 1000).toFixed(0) + " Rb";
                        }
                        return "Rp " + val;
                    }
                }
            },
            legend: { position: 'top', horizontalAlign: 'right' },
            grid: {
                borderColor: '#E4E6EF',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "Rp " + val.toLocaleString("id-ID");
                    }
                }
            }
        };

        var chart = new ApexCharts(element, options);
        chart.render();
    };

    initChart();
});
</script>
<?= $this->endSection(); ?>
<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?php $db = Config\Database::connect(); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">

        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="row g-5 g-xl-8 mb-8">

                <div class="col-xl-4">
                    <div class="card card-flush shadow-sm border-0 h-100">
                        <div class="card-header pt-5 border-0">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-800 fs-4">Paket Terlaris</span>
                                <span class="text-gray-500 mt-1 fw-semibold fs-7">Top 5 Paket Terpopuler</span>
                            </h3>
                        </div>
                        <div class="card-body d-flex align-items-end pt-0">
                            <div id="chart_paket_terlaris" class="w-100 min-h-250px"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card card-flush shadow-sm border-0 h-100">
                        <div class="card-header pt-5 border-0">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-800 fs-4">Metode Bayar</span>
                                <span class="text-gray-500 mt-1 fw-semibold fs-7">Rasio Online vs Manual</span>
                            </h3>
                        </div>
                        <div class="card-body d-flex flex-center pt-0">
                            <div id="chart_metode_bayar" class="w-100 min-h-250px"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card card-flush shadow-sm border-0 h-100">
                        <div class="card-header pt-5 border-0">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-800 fs-4">Performa Voucher</span>
                                <span class="text-gray-500 mt-1 fw-semibold fs-7">Mitra vs Voucher Affiliate</span>
                            </h3>
                        </div>
                        <div class="card-body d-flex flex-center pt-0">
                            <div id="chart_performa_voucher" class="w-100 min-h-250px"></div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card card-flush shadow-sm border-0">

                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title flex-column align-items-start">
                        <span class="card-label fw-bold text-gray-800">Daftar Pembayaran</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Kelola dan validasi pembayaran peserta ujian secara real-time.</span>
                        <div class="mt-3">
                            <span class="badge badge-light-success fs-6 fw-bold px-4 py-2 border border-success border-dashed">
                                Total Lunas: Rp <span id="ui_total_pendapatan">0</span>
                            </span>
                        </div>
                    </div>

                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <div class="w-150px">
                            <select id="filter_bulan" class="form-select form-select-solid" data-control="select2" data-hide-search="true">
                                <option value="">Semua Bulan</option>
                                <?php
                                for ($i = 0; $i < 12; $i++) {
                                    $val = date('Y-m', strtotime("-$i months"));
                                    $label = date('F Y', strtotime("-$i months"));
                                    $bulanEng = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                    $bulanInd = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                    $label = str_replace($bulanEng, $bulanInd, $label);
                                    echo "<option value=\"$val\">$label</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="w-100 mw-150px">
                            <select id="filter-status-afiliasi" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Status">
                                <option value="0">Tidak Afiliasi</option>
                                <option value="1">Afiliasi</option>
                                <option value="2">Semua Data</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-transaksi-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Transaksi..." />
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatables-list" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-150px">Peserta</th>
                                    <th class="min-w-200px">Paket & Lembaga</th>
                                    <th class="min-w-100px">Voucher</th>
                                    <th class="min-w-125px">Pembayaran</th>
                                    <th class="text-end min-w-100px">Nominal</th>
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
<div class="modal fade" id="validasi_transaksi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content rounded border-0 shadow-lg">
            <form action="<?= base_url('sw-admin/transaksi/approve-transaksi'); ?>" method="POST">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf-token" />
                <div id="isiKonten">
                    <div class="p-10 text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="text-muted mt-3 fw-semibold">Memuat data transaksi...</div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="invoice_cetak_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded border-0 shadow-lg">
            <div class="isiKontenInvoice">
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // 1. Inisialisasi DataTable Server Side (Metronic UI)
        var table = $('#datatables-list').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= base_url('sw-admin/transaksi/datatables') ?>",
                "type": "POST",
                "data": function(d) {
                    d["<?= csrf_token() ?>"] = $('.csrf-token').val();
                    d.filter_bulan = $('#filter_bulan').val();
                    d.filter_status_afiliasi = $('#filter-status-afiliasi').val();
                },
                "dataSrc": function(json) {
                    $('.csrf-token').val(json.csrf_hash);
                    $('#ui_total_pendapatan').text(json.total_pendapatan);
                    return json.data;
                },
            },
            "columns": [{
                    "data": "peserta",
                    "className": "text-gray-800 fw-bold"
                },
                {
                    "data": "paket"
                },
                {
                    "data": "voucher"
                },
                {
                    "data": "pembayaran"
                },
                {
                    "data": "nominal",
                    "className": "text-end fw-bold text-gray-900"
                },
                {
                    "data": "status",
                    "className": "text-center"
                },
                {
                    "data": "aksi",
                    "className": "text-center"
                }
            ],
            "columnDefs": [{
                "targets": [5, 6],
                "orderable": false
            }],
            drawCallback: function(settings) {
                // Beri tahu Metronic untuk membaca ulang DOM dan mengaktifkan dropdown baru
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            }
        });

        // Trigger reload saat filter bulan diubah
        $('#filter_bulan').on('change', function() {
            table.ajax.reload();
        });

        // Trigger reload saat filter status afiliasi diubah
        $('#filter-status-afiliasi').on('change', function() {
            table.ajax.reload();
        });

        // Search Datatable Custom
        $('[data-kt-transaksi-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        // 2. Fungsi Validasi Transaksi (AJAX)
        $(document).on('click', '.validasi-transaksi', function() {
            const idtransaksi = $(this).data('transaksi');
            const csrfName = "<?= csrf_token() ?>";
            const csrfHash = $('.csrf-token').val();

            // Set loading state in modal
            $("#isiKonten").html('<div class="p-10 text-center"><div class="spinner-border text-primary" role="status"></div></div>');

            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-admin/transaksi/validasi-transaksi') ?>",
                data: {
                    idtransaksi: idtransaksi,
                    [csrfName]: csrfHash
                },
                dataType: 'JSON',
                success: function(data) {
                    // Update CSRF token
                    $('.csrf-token').val(data[csrfName]);

                    // Kalkulasi Logika Anda
                    let diskon = (data.nominal * data.diskon) / 100;
                    let totalDiskon = data.nominal - diskon;
                    let diskon_voucher = (totalDiskon * data.voucher) / 100;
                    let totalFix = data.nominal - diskon - diskon_voucher;

                    let statusBadge = getStatusBadge(data.status);
                    let jenisBayar = (data.jenis_bayar == 'online') ? 'Midtrans' : (data.status == 'P' ? 'N/A' : 'Manual');
                    let imgPath = '<?= base_url('uploads/transaksi/thumbnails'); ?>/' + data.bukti_pembayaran;
                    let lampiran = '';

                    if ((data.status === 'V' && data.jenis_bayar === 'manual') || (data.status === 'S' && data.jenis_bayar === 'manual')) {
                        lampiran = `
                            <div class="fv-row mt-5 text-center bg-light p-5 rounded border border-dashed border-gray-300">
                                <label class="fs-7 fw-bold text-gray-700 d-block text-start mb-3 text-uppercase">Bukti Pembayaran:</label>
                                <a href="${imgPath}" target="_blank">
                                    <img class="img-fluid rounded shadow-sm w-100" src="${imgPath}" alt="Bukti" style="transition: transform .3s ease; cursor: zoom-in;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                                </a>
                            </div>`;
                    }

                    // Injeksi HTML dengan UI Metronic 8
                    $("#isiKonten").html(`
                        <div class="modal-header pb-0 border-0 justify-content-between">
                            <h2 class="fw-bold">Validasi Transaksi</h2>
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                            </div>
                        </div>
                        
                        <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">
                            <div class="row g-5 fs-6 mb-7">
                                <div class="col-6 text-gray-600 fw-semibold">Paket:</div>
                                <div class="col-6 text-end text-gray-900 fw-bold">${data.nama_paket}</div>
                                
                                <div class="col-6 text-gray-600 fw-semibold">Peserta:</div>
                                <div class="col-6 text-end text-gray-900 fw-bold">${data.nama_siswa}</div>
                                
                                <div class="col-12"><div class="separator separator-dashed my-1"></div></div>
                                
                                <div class="col-6 text-gray-600 fw-semibold">ID Transaksi:</div>
                                <div class="col-6 text-end text-gray-800 fw-semibold">${data.idtransaksi}</div>
                                
                                <div class="col-6 text-gray-600 fw-semibold">Metode:</div>
                                <div class="col-6 text-end fw-bold text-success">${jenisBayar}</div>
                                
                                <div class="col-6 text-gray-600 fw-semibold">Nominal:</div>
                                <div class="col-6 text-end fw-bold text-primary fs-4">Rp ${numberFormat(totalFix, 0, ',', '.')}</div>
                            </div>
                            
                            <div class="mb-5 text-center">${statusBadge}</div>
                            
                            ${lampiran}
                            
                            <input type="hidden" name="idtransaksi" value="${data.idtransaksi}">
                        </div>
                        
                        <div class="modal-footer border-0 p-5 p-lg-10 pt-0 justify-content-end">
                            <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    `);

                    // Gunakan native bootstrap modal show (karena ID modal tetap sama)
                }
            });
        });

        // 3. Helper Status Badge (Metronic 8 Colors)
        function getStatusBadge(status) {
            const map = {
                'S': '<span class="badge badge-light-success px-4 py-2 fs-6">Lunas</span>',
                'P': '<span class="badge badge-light-primary px-4 py-2 fs-6">Menunggu Pembayaran</span>',
                'V': '<span class="badge badge-light-info px-4 py-2 fs-6">Menunggu Approval</span>',
                'E': '<span class="badge badge-light-danger px-4 py-2 fs-6">Expired</span>',
                'M': '<span class="badge badge-light-warning px-4 py-2 fs-6">Proses Pembayaran</span>',
                'DM': '<span class="badge badge-light-danger px-4 py-2 fs-6">Denied</span>',
                'PM': '<span class="badge badge-light-warning px-4 py-2 fs-6">Pending</span>'
            };
            return map[status] || '<span class="badge badge-light-danger px-4 py-2 fs-6">Denied</span>';
        }

        // 4. Invoice Cetak
        $(document).on('click', '.invoice_cetak', function() {
            const url = $(this).data('invoice');
            $(".isiKontenInvoice").html(`
                <div class="modal-header pb-0 border-0 justify-content-between pt-5 px-7">
                    <h2 class="fw-bold">Preview Invoice</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body p-0 mt-5 rounded-bottom overflow-hidden">
                    <iframe src="${url}" width="100%" height="700px" style="border:none; display:block;"></iframe>
                </div>
            `);
        });

        // 5. Konfirmasi Aksi (SweetAlert)
        $(document).on("click", "#hapus, #approve", function(e) {
            e.preventDefault();

            const url = $(this).attr("href");
            const isHapus = $(this).attr('id') == 'hapus';

            const title = isHapus ? "Batalkan Transaksi?" : "Approve Transaksi?";
            const text = isHapus ? "Data yang dibatalkan mungkin tidak dapat dikembalikan." : "Pastikan data transaksi sudah sesuai dan valid.";
            const icon = isHapus ? "warning" : "question";
            const confirmButtonColor = isHapus ? "#d33" : "#3085d6";
            const confirmButtonText = isHapus ? "Ya, Batalkan!" : "Ya, Approve!";

            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmButtonColor,
                cancelButtonColor: "#aaa",
                confirmButtonText: confirmButtonText,
                cancelButtonText: "Kembali",
                customClass: {
                    confirmButton: "btn fw-bold " + (isHapus ? "btn-danger" : "btn-primary"),
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    window.location.href = url;
                }
            });
        });
    });

    // Helper Number Format
    function numberFormat(n, c, d, t) {
        var c = isNaN(c = Math.abs(c)) ? 2 : c,
            d = d == undefined ? "." : d,
            t = t == undefined ? "," : t,
            s = n < 0 ? "-" : "",
            i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
            j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    }
</script>
<script>
    // Ambil data JSON dari PHP
    const dataTopPaket = <?= $topPaket ?? '[]' ?>;
    const dataMetodeBayar = <?= $metodeBayar ?? '[]' ?>;
    const dataVoucher = <?= $analisisVoucher ?? '[]' ?>;

    // --- 1. Chart Paket Terlaris (Horizontal Bar) ---
    function initChartPaket() {
        if(dataTopPaket.length === 0) return;
        let categories = dataTopPaket.map(item => item.label);
        let seriesData = dataTopPaket.map(item => parseInt(item.total));

        var options = {
            series: [{ name: 'Transaksi', data: seriesData }],
            chart: { fontFamily: 'inherit', type: 'bar', height: 250, toolbar: { show: false } },
            plotOptions: { bar: { borderRadius: 4, horizontal: true, distributed: true } },
            colors: ['#009EF7', '#50CD89', '#F1416C', '#FFC700', '#7239EA'],
            dataLabels: { enabled: true, style: { fontSize: '11px' } },
            xaxis: { categories: categories, labels: { style: { colors: '#A1A5B7' } } },
            yaxis: { labels: { style: { colors: '#3F4254', fontWeight: 500 } } },
            legend: { show: false }
        };
        new ApexCharts(document.querySelector("#chart_paket_terlaris"), options).render();
    }

    // --- 2. Chart Metode Bayar (Donut) ---
    function initChartMetodeBayar() {
        if(dataMetodeBayar.length === 0) return;
        let labels = dataMetodeBayar.map(item => item.label.toUpperCase());
        let series = dataMetodeBayar.map(item => parseInt(item.total));

        var options = {
            series: series,
            labels: labels,
            chart: { fontFamily: 'inherit', type: 'donut', height: 250 },
            colors: ['#009EF7', '#50CD89'], 
            plotOptions: { pie: { donut: { size: '65%' } } },
            dataLabels: { enabled: false },
            legend: { position: 'bottom' }
        };
        new ApexCharts(document.querySelector("#chart_metode_bayar"), options).render();
    }

    // --- 3. Chart Performa Voucher (Donut - BARU) ---
    function initChartVoucher() {
        if(dataVoucher.length === 0) return;
        let labels = dataVoucher.map(item => item.label);
        let series = dataVoucher.map(item => parseInt(item.total));

        var options = {
            series: series,
            labels: labels,
            chart: { fontFamily: 'inherit', type: 'donut', height: 250 },
            colors: ['#7239EA', '#50CD89', '#E4E6EF'], // Purple untuk Mitra, Green untuk Affiliate, Light Gray untuk tanpa voucher
            plotOptions: { 
                pie: { 
                    donut: { 
                        size: '65%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Sukses',
                                fontSize: '12px',
                                fontWeight: 'bold',
                                color: '#A1A5B7',
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                }
                            }
                        }
                    } 
                } 
            },
            dataLabels: { enabled: false },
            legend: { position: 'bottom' },
            stroke: { show: true, width: 2, colors: ['#ffffff'] }
        };
        new ApexCharts(document.querySelector("#chart_performa_voucher"), options).render();
    }

    // Render semua chart setelah DOM siap
    document.addEventListener("DOMContentLoaded", function () {
        initChartPaket();
        initChartMetodeBayar();
        initChartVoucher();
    });
</script>
<?= $this->endSection(); ?>
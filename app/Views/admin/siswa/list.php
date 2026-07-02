<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="row g-5 g-xl-10 mb-5 mb-xl-10">

                <!-- Statistik + Chart -->
                <div class="col-xl-8">
                    <div class="card card-flush h-xl-100 shadow-sm">

                        <!-- Header -->
                        <div class="card-header align-items-center border-0 pt-6">
                            <div class="card-title flex-column align-items-start">
                                <h3 class="fw-bold text-gray-800 mb-1">
                                    Statistik Ujian Peserta
                                </h3>
                                <span class="text-muted fw-semibold fs-7">
                                    Visualisasi distribusi nilai peserta
                                </span>
                            </div>
                        </div>

                        <!-- Statistik -->
                        <div class="card-body pt-3">
                            <div class="row g-5 mb-8">
                                <div class="col-md-4">
                                    <div class="bg-primary rounded-3 p-6 h-100">
                                        <div class="fs-2hx fw-bold text-white" id="stat_total_mengerjakan">0</div>
                                        <div class="text-white opacity-75 fw-semibold">
                                            Peserta Selesai
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="bg-success rounded-3 p-6 h-100">
                                        <div class="fs-2hx fw-bold text-white" id="stat_nilai_tertinggi">0</div>
                                        <div class="text-white opacity-75 fw-semibold">
                                            Nilai Tertinggi
                                        </div>

                                        <div class="text-white fw-bold fs-7 mt-2" id="stat_nama_tertinggi">
                                            -
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="bg-info rounded-3 p-6 h-100">
                                        <div class="fs-2hx fw-bold text-white" id="stat_rata_rata">0</div>
                                        <div class="text-white opacity-75 fw-semibold">
                                            Rata-rata Nilai
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Chart -->
                            <div id="kt_charts_ujian" style="height:350px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Top 5 -->
                <div class="col-xl-4">
                    <div class="card card-flush h-xl-100 shadow-sm">
                        <div class="card-header">
                            <div class="card-title flex-column align-items-start">
                                <h3 class="fw-bold text-gray-800 mb-1">
                                    Top 7 Peserta
                                </h3>
                                <span class="text-muted fs-7">
                                    Berdasarkan nilai tertinggi
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($top_siswa)): ?>
                                <?php foreach ($top_siswa as $index => $siswa): ?>
                                    <div class="d-flex align-items-center <?= $index != count($top_siswa) - 1 ? 'mb-6' : '' ?>">
                                        <div class="symbol symbol-50px me-4">
                                            <?php
                                            $bg = 'bg-light-secondary';
                                            if ($index == 0) $bg = 'bg-warning';
                                            if ($index == 1) $bg = 'bg-light-primary';
                                            if ($index == 2) $bg = 'bg-light-danger';
                                            ?>
                                            <span class="symbol-label <?= $bg ?> fw-bold fs-5">
                                                <?= $index + 1 ?>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-gray-800 fs-6">
                                                <?= esc($siswa['nama']) ?>
                                            </div>
                                            <div class="text-muted fs-7">
                                                Nilai Akhir
                                            </div>
                                        </div>
                                        <span class="badge badge-light-success fs-6 fw-bold">
                                            <?= number_format($siswa['nilai']) ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="d-flex flex-column align-items-center py-15">
                                    <i class="ki-duotone ki-chart-simple fs-4x text-gray-300 mb-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <span class="text-muted">
                                        Belum ada data peserta
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-siswa-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari peserta..." />
                        </div>
                    </div>

                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <div class="w-100 mw-150px">
                            <select id="filter-status" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Status">
                                <option value="">Semua Status</option>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                                <option value="2">Data Tidak Lengkap</option>
                            </select>
                        </div>

                        <a href="<?= base_url('sw-admin/siswa/sertifikat-ab') ?>" class="btn btn-light-success" data-bs-toggle="tooltip" data-bs-placement="top" title="List sertifikat AB seluruh siswa">
                            <i class="ki-duotone ki-badge fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            Sertifikat AB
                        </a>

                        <a href="<?= base_url('sw-admin/siswa/create') ?>" class="btn btn-primary">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah Peserta
                        </a>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatable-list" class="table align-middle table-row-dashed fs-6 gy-5 text-wrap">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-100px">NIP</th>
                                    <th class="min-w-150px">Nama</th>
                                    <th class="min-w-150px">Email</th>
                                    <th class="min-w-125px">Handphone</th>
                                    <th class="text-center min-w-100px">Registrasi</th>
                                    <th class="text-center min-w-100px">Status</th>
                                    <th class="text-center min-w-100px">Ujian</th>
                                    <th class="text-end min-w-100px">Opsi</th>
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

<div class="modal fade" id="detail_siswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body scroll-y pt-0 pb-15 px-5 px-xl-20">
                <div class="mb-13 text-center">
                    <h1 class="mb-3">Detail Informasi Siswa</h1>
                    <div class="text-muted fw-semibold fs-5">Informasi lengkap terkait biodata, kontak, dan instansi siswa</div>
                </div>

                <div class="row g-9">
                    <div class="col-md-4 text-center mb-4">
                        <div class="symbol symbol-150px symbol-circle mb-5" id="file_profile">
                            <img src="<?= base_url('assets/admin/media/avatars/blank.png') ?>" alt="image" id="avatar_detail" />
                        </div>
                        <h4 class="fw-bold text-gray-900 mb-1" id="nama_siswa_top">Loading...</h4>
                        <span class="badge badge-light-primary fw-bold" id="no_induk_top">Loading...</span>
                    </div>

                    <div class="col-md-8">
                        <div class="fs-5 fw-bold text-gray-900 mb-4 border-bottom pb-2">Identitas Pribadi</div>
                        <div class="row mb-7">
                            <?= renderDetailRow('No. Induk Siswa', 'no_induk_siswa', 6) ?>
                            <?= renderDetailRow('NIK', 'nik', 6) ?>
                            <?= renderDetailRow('Nama Lengkap', 'nama_siswa', 12) ?>
                            <?= renderDetailRow('Tempat Lahir', 'tempat_lahir', 6) ?>
                            <?= renderDetailRow('Tanggal Lahir', 'tgl_lahir', 6) ?>
                            <?= renderDetailRow('Jenis Kelamin', 'jenis_kelamin', 6) ?>
                        </div>

                        <div class="fs-5 fw-bold text-gray-900 mb-4 border-bottom pb-2 mt-8">Kontak & Alamat</div>
                        <div class="row mb-7">
                            <?= renderDetailRow('Email', 'email', 6) ?>
                            <?= renderDetailRow('No. HP', 'hp', 6) ?>
                            <?= renderDetailRow('Provinsi', 'provinsi', 6) ?>
                            <?= renderDetailRow('Kota/Kabupaten', 'kota', 6) ?>
                            <?= renderDetailRow('Kecamatan', 'kecamatan', 6) ?>
                            <?= renderDetailRow('Kelurahan', 'kelurahan', 6) ?>
                            <?= renderDetailRow('Alamat KTP', 'alamat_ktp', 12) ?>
                            <?= renderDetailRow('Alamat Domisili', 'alamat_domisili', 12) ?>
                        </div>

                        <div class="fs-5 fw-bold text-gray-900 mb-4 border-bottom pb-2 mt-8">Data Akademik & Akun</div>
                        <div class="row mb-7">
                            <?= renderDetailRow('Kelas', 'kelas', 6) ?>
                            <?= renderDetailRow('Status Akun', 'is_active', 6) ?>
                            <?= renderDetailRow('Tgl. Terdaftar', 'date_created', 6) ?>
                            <?= renderDetailRow('Status Siswa', 'status', 6) ?>
                        </div>

                        <div class="fs-5 fw-bold text-gray-900 mb-4 border-bottom pb-2 mt-8">Pekerjaan / Instansi</div>
                        <div class="row">
                            <?= renderDetailRow('Profesi', 'profesi', 6) ?>
                            <?= renderDetailRow('Bidang Usaha', 'bidang_usaha', 6) ?>
                            <?= renderDetailRow('Tipe Kantor', 'kantor', 6) ?>
                            <?= renderDetailRow('Nama Kantor', 'nama_kantor', 6) ?>
                            <?= renderDetailRow('Alamat Kantor', 'alamat_kantor', 12) ?>
                            <?= renderDetailRow('Riwayat Pekerjaan', 'riwayat_pekerjaan', 12) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
function renderDetailRow($label, $id, $col = 6)
{
    return "
    <div class='col-sm-$col mb-4'>
        <div class='fw-semibold text-muted fs-7 text-uppercase'>$label</div>
        <div class='fw-bold text-gray-800 fs-6' id='$id'>-</div>
    </div>";
}
?>
<?= $this->endSection(); ?>


<?= $this->section('scripts') ?>
<script>
    var chartInstance = null; // Menyimpan instance chart agar bisa di-update

    $(document).ready(function() {

        // 1. Muat Data Statistik untuk Grafik & Widget
        loadStatistik();

        // 2. Inisialisasi DataTable
        var table = $('#datatable-list').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= base_url('sw-admin/siswa/datatable') ?>",
                type: "POST",
                data: function(d) {
                    d[csrfName] = csrfHash;
                    d.status_filter = $('#filter-status').val();
                },
                dataSrc: function(json) {
                    if (json.csrf_hash) {
                        csrfHash = json.csrf_hash;
                        $('input[name="' + csrfName + '"]').val(csrfHash);
                    }
                    return json.data;
                },
            },
            columns: [{
                    data: 'no_induk_siswa',
                    className: 'text-gray-800 fw-bold'
                },
                {
                    data: 'nama_siswa',
                    render: d => `<span class="text-gray-800 fw-bold">${d}</span>`
                },
                {
                    data: 'email'
                },
                {
                    data: 'hp',
                    render: function(data) {
                        if (!data || data === '0' || data === '') return '<span class="text-muted">-</span>';
                        let cleanNumber = data.replace(/[^0-9]/g, '');
                        if (cleanNumber.startsWith('0')) cleanNumber = '62' + cleanNumber.slice(1);
                        return `<a href="https://wa.me/${cleanNumber}" target="_blank" style="text-decoration: none;"><i class="fab fa-whatsapp" style="color: #25D366;"></i> ${data}</a>`;
                    }
                },
                {
                    data: 'date_created',
                    className: 'text-center'
                },
                {
                    data: 'is_active',
                    className: 'text-center',
                    render: d => d != 0 ? '<span class="badge badge-light-success fw-bold px-4 py-2">Aktif</span>' : '<span class="badge badge-light-danger fw-bold px-4 py-2">Tidak Aktif</span>'
                },
                {
                    data: 'stats',
                    className: 'text-center'
                },
                {
                    data: null,
                    orderable: false,
                    className: 'text-end',
                    render: function(row) {
                        return `
                        <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            Aksi <i class="ki-duotone ki-down fs-5 ms-1"></i>
                        </a>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
                            <div class="menu-item px-3"><a href="javascript:void(0);" class="menu-link px-3 detail-siswa" data-siswa="${row.id_siswa_enc}" data-bs-toggle="modal" data-bs-target="#detail_siswa">Detail</a></div>
                            <div class="menu-item px-3"><a href="<?= base_url('sw-admin/siswa/edit') ?>/${row.id_siswa_enc}" class="menu-link px-3">Edit</a></div>
                            <div class="menu-item px-3"><a href="<?= base_url('sw-admin/siswa/sertifikat') ?>/${row.id_siswa_enc}" class="menu-link px-3">Sertifikat</a></div>
                            <div class="menu-item px-3"><a href="<?= base_url('sw-admin/siswa/ujian') ?>/${row.id_siswa_enc}" class="menu-link px-3">List Ujian</a></div>
                            <div class="menu-item px-3"><a href="<?= base_url('sw-admin/siswa/materi') ?>/${row.id_siswa_enc}" class="menu-link px-3">List Materi</a></div>
                            ${row.totalUjian <= 0 ? `
                                <div class="separator mt-3 opacity-75"></div>
                                <div class="menu-item px-3"><a href="<?= base_url('sw-admin/siswa/delete') ?>/${row.id_siswa_enc}" class="menu-link px-3 text-danger btn-hapus">Hapus</a></div>
                            ` : ''}
                        </div>`;
                    }
                }
            ],
            drawCallback: function(settings) {
                if (typeof KTMenu !== 'undefined') KTMenu.createInstances();
            }
        });

        // 3. Trigger Search & Filter Table
        $('[data-kt-siswa-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });
        $('#filter-status').on('change', function() {
            table.ajax.reload();
        });

        // 4. Modal Detail Ajax
        $(document).on('click', '.detail-siswa', function() {
            const id_siswa = $(this).data('siswa');
            $("#nama_siswa_top").html('Loading...');

            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-admin/siswa/detail') ?>",
                data: {
                    [csrfName]: csrfHash,
                    id_siswa: id_siswa
                },
                dataType: 'JSON',
                success: function(data) {
                    if (data.csrf_hash) {
                        csrfHash = data.csrf_hash;
                        $('input[name="' + csrfName + '"]').val(csrfHash);
                    }
                    let imgSource = data.avatar ? 'https://kelasbrevet.com/assets/app-assets/user/' + data.avatar : '<?= base_url('assets/admin/media/avatars/blank.png') ?>';
                    $("#file_profile").html('<img src="' + imgSource + '" alt="avatar" id="avatar_detail" />');

                    const fields = ['no_induk_siswa', 'nik', 'nama_siswa', 'tempat_lahir', 'tgl_lahir', 'jenis_kelamin', 'email', 'hp', 'provinsi', 'kota', 'kecamatan', 'kelurahan', 'alamat_ktp', 'alamat_domisili', 'kelas', 'date_created', 'profesi', 'bidang_usaha', 'kantor', 'nama_kantor', 'alamat_kantor', 'riwayat_pekerjaan'];

                    fields.forEach(f => $(`#${f}`).html(data[f] || '-'));
                    $("#nama_siswa_top").html(data.nama_siswa || '-');
                    $("#no_induk_top").html(data.no_induk_siswa || '-');
                    $("#is_active").html(data.is_active == 1 ? '<span class="badge badge-light-success">Aktif</span>' : '<span class="badge badge-light-danger">Non-Aktif</span>');
                    $("#status").html(data.status === 'B' ? 'Baru' : (data.status === 'S' ? 'Selesai' : data.status || '-'));
                }
            });
        });

        // 5. SweetAlert Delete
        $(document).on("click", ".btn-hapus", function(e) {
            e.preventDefault();
            var link = $(this).attr("href");
            Swal.fire({
                text: "Anda yakin ingin menghapus data ini?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function(result) {
                if (result.value) document.location.href = link;
            });
        });
    });

    // ==========================================
    // FUNGSI UNTUK MENGAMBIL DATA STATISTIK
    // ==========================================
    function loadStatistik() {
        // Parsing data JSON dari Controller
        var stats = <?= $statistik ?>;

        // Render Text Widget
        $('#stat_total_mengerjakan').text(stats.total_mengerjakan);
        $('#stat_nilai_tertinggi').text(stats.nilai_tertinggi);
        $('#stat_nama_tertinggi').text(stats.nama_tertinggi);
        $('#stat_rata_rata').text(stats.rata_rata);

        // Render Chart
        renderChart(stats.chart_categories, stats.chart_data);
    }

    // ==========================================
    // FUNGSI RENDER APEXCHART
    // ==========================================
    function renderChart(categories, dataSeries) {
        var element = document.getElementById('kt_charts_ujian');
        if (!element) return;

        var height = parseInt(KTUtil.css(element, 'height'));
        var labelColor = KTUtil.getCssVariableValue('--bs-gray-500');
        var borderColor = KTUtil.getCssVariableValue('--bs-gray-200');
        var baseColor = KTUtil.getCssVariableValue('--bs-primary');

        var options = {
            series: [{
                name: 'Jumlah Peserta',
                data: dataSeries
            }],
            chart: {
                fontFamily: 'inherit',
                type: 'bar',
                height: height,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: false,
                    columnWidth: '50%'
                }
            },
            legend: {
                show: false
            },
            dataLabels: {
                show: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: categories,
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    style: {
                        colors: labelColor,
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: labelColor,
                        fontSize: '12px'
                    }
                }
            },
            fill: {
                type: 'solid'
            },
            tooltip: {
                style: {
                    fontSize: '12px'
                },
                y: {
                    formatter: function(val) {
                        return val + ' Orang';
                    }
                }
            },
            colors: [baseColor],
            grid: {
                borderColor: borderColor,
                strokeDashArray: 4,
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            }
        };

        // Jika chart sudah ada, update datanya. Jika belum, buat baru.
        if (chartInstance) {
            chartInstance.updateOptions(options);
        } else {
            chartInstance = new ApexCharts(element, options);
            chartInstance.render();
        }
    }
</script>
<?= $this->endSection(); ?>
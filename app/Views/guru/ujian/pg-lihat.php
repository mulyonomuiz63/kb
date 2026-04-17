<?= $this->extend('template/app'); ?>

<?= $this->section('styles'); ?>
<style>
    /* Styling khusus tetap dipertahankan namun disesuaikan dengan variabel Metronic jika perlu */
    .nav-number {
        width: 35px;
        height: 35px;
        margin: 2px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.475rem;
        transition: all 0.3s;
    }

    .nav-number.active {
        background: #009ef7 !important;
        color: #fff !important;
    }

    .nav-number.answered {
        background: #50cd89 !important;
        color: #fff !important;
    }

    /* Menyesuaikan hover premium ala Metronic */
    .table-hover tbody tr:hover {
        background-color: #f1faff !important;
        transition: background-color 0.2s ease;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content'); ?>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <div class="card card-flush shadow-sm">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-user-tick fs-1 text-primary me-3">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                        </i>
                        <h3 class="card-label fw-bold text-dark">Daftar Peserta Selesai</h3>
                    </div>
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-light-primary" onclick="window.history.back()">
                        <i class="ki-duotone ki-black-left fs-4 me-1"></i> Kembali
                    </button>
                </div>
            </div>

            <div class="card-body py-4">
                <div class="table-responsive">
                    <table id="tableSiswa" class="table align-middle table-row-dashed fs-6 gy-5 table-hover">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-150px">Peserta</th>
                                <th class="text-center min-w-100px">Statistik</th>
                                <th class="text-center min-w-100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable dengan styling Metronic 8
        $('#tableSiswa').DataTable({
            "processing": true,
            "serverSide": true,
            "ordering": false,
            "ajax": {
                "url": "<?= base_url('sw-guru/ujian/ajaxSiswaUjian/' . $kode_ujian_encrypt) ?>",
                "type": "POST",
                "data": function(d) {
                    d[csrfName] = csrfHash;
                },
                "dataSrc": function(json) {
                    // Update input CSRF dengan token baru (Security CI4)
                    csrfHash = json[csrfName];
                    return json.data;
                }
            },
            // TAMBAHKAN BAGIAN INI
            "drawCallback": function(settings) {
                // Inisialisasi ulang dropdown Metronic setelah data tampil
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            },
            "language": {
                "search": "",
                "searchPlaceholder": "Cari Peserta...",
                "lengthMenu": "Tampilkan _MENU_",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "paginate": {
                    "previous": "<i class='ki-duotone ki-left fs-2'></i>",
                    "next": "<i class='ki-duotone ki-right fs-2'></i>"
                }
            },
            "dom": `
                <'row'
                    <'col-sm-6 d-flex align-items-center justify-content-start'l>
                    <'col-sm-6 d-flex align-items-center justify-content-end'f>
                >
                <'table-responsive'tr>
                <'row'
                    <'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>
                    <'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>
                >`
        });
    });
</script>
<?= $this->endSection(); ?>
<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="card card-flush shadow-sm border-0">
                
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title flex-column align-items-start">
                        <span class="card-label fw-bold text-gray-800">Daftar Peserta</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Peserta yang terdaftar sebagai affiliate.</span>
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
                                    <th class="text-end min-w-100px">Komisi</th>
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
                // 🔥 Update CSRF setiap response
                if(json[csrfName]) {
                    csrfHash = json[csrfName];
                }
                return json.data;
            }
        },
        columnDefs: [
            { targets: 2, className: "text-end" },     // Komisi (Rata kanan - Bootstrap 5 menggunakan text-end)
            { targets: 3, className: "text-center" },  // Status
            { targets: 4, className: "text-center" }   // Aksi
        ],
        // Inisialisasi ulang komponen menu & tooltip bawaan Metronic saat tabel me-render ulang
        drawCallback: function(settings) {
            if (typeof KTMenu !== 'undefined') {
                KTMenu.createInstances();
            }
        }
    });

    // Fitur Pencarian Custom yang menyatu dengan UI Card Header Metronic
    $('[data-kt-affiliate-table-filter="search"]').on('keyup', function() {
        table.search(this.value).draw();
    });
});
</script>
<?= $this->endSection(); ?>
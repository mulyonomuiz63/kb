<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card card-flush shadow-sm border-0">

                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title flex-column align-items-start">
                        <div class="d-flex align-items-center gap-2 gap-lg-3">
                            <a href="<?= base_url('sw-admin/artikel/create'); ?>" class="btn btn-sm btn-primary fw-bold">
                                <i class="ki-duotone ki-plus fs-2"></i> Buat Artikel
                            </a>
                        </div>
                    </div>

                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-artikel-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Artikel..." />
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatables-list" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 border-bottom border-gray-200">
                                    <th class="min-w-250px">Informasi Artikel</th>
                                    <th class="text-center min-w-100px">Thumbnail</th>
                                    <th class="text-center min-w-100px">Visitor</th>
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
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <div class="modal-content bg-transparent shadow-none">
            <div class="modal-body p-0 position-relative text-center">
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2 position-absolute" data-bs-dismiss="modal" aria-label="Close" style="top: 10px; right: 10px; z-index: 10; background: rgba(255,255,255,0.8);">
                    <i class="ki-duotone ki-cross fs-1 text-dark"><span class="path1"></span><span class="path2"></span></i>
                </div>

                <img src="" id="imgFull" class="img-fluid rounded shadow-lg" alt="Preview Image">
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // DataTable Server Side dg Custom DOM Metronic
        var table = $('#datatables-list').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= base_url('sw-admin/artikel/datatables') ?>",
                "type": "POST",
                "data": function(d) {
                    // CSRF Token Global CI4
                    d.<?= csrf_token() ?> = "<?= csrf_hash() ?>";
                },
                "dataSrc": function(json) {
                    // Jika server mengirimkan token baru (Regenerate CSRF)
                    if (json.token) {
                        // Update hash untuk request berikutnya jika diperlukan
                        // console.log("CSRF Updated");
                    }
                    return json.data;
                },
            },
            "columnDefs": [{
                    "targets": [0],
                    "className": "text-gray-800 fw-bold"
                },
                {
                    "targets": [1, 4],
                    "orderable": false
                },
                {
                    "targets": [1, 2, 3, 4],
                    "className": "text-center align-middle"
                }
            ],
            drawCallback: function(settings) {
                // Beri tahu Metronic untuk membaca ulang DOM dan mengaktifkan dropdown baru
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            }
        });

        // Fitur Pencarian Custom yang menyatu dengan UI Card Header Metronic
        $('[data-kt-artikel-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Preview Image Logic (Menggunakan data-bs-target atau langsung via JS standard BS5)
        $(document).on('click', '.preview-img', function() {
            const src = $(this).attr('data-src');
            $('#imgFull').attr('src', src);

            // Membuka modal dengan API Bootstrap 5
            var myModal = new bootstrap.Modal(document.getElementById('previewModal'));
            myModal.show();
        });
    });
</script>
<?= $this->endSection(); ?>
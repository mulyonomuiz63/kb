<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <div class="widget shadow-sm border-0 p-4 bg-white" style="border-radius: 15px;">
                
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                    <div>
                        <h4 class="font-weight-bold text-dark mb-1">Manajemen Artikel</h4>
                        <p class="text-muted small mb-0">Kelola konten berita dan publikasi Anda</p>
                    </div>
                    <div class="d-flex">
                        <a href="<?= base_url('sw-admin/artikel/kategori'); ?>" class="btn btn-outline-warning shadow-sm mr-2">
                            <i class="bi bi-tag mr-2"></i> Kategori
                        </a>
                        <a href="<?= base_url('sw-admin/artikel/create'); ?>" class="btn btn-primary shadow-sm">
                            <i class="bi bi-plus-lg mr-1"></i> Buat Artikel
                        </a>
                    </div>
                </div>

                <hr class="mb-4" style="opacity: 0.1;">

                <div class="table-responsive">
                    <table id="datatables-list" class="table table-hover border-0 w-100">
                        <thead>
                            <tr class="bg-light">
                                <th class="border-0 py-3">Informasi Artikel</th>
                                <th class="border-0 py-3">Thumbnail</th>
                                <th class="border-0 py-3">Visitor</th>
                                <th class="border-0 py-3">Status</th>
                                <th class="border-0 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="align-middle">
                            </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 bg-transparent">
            <div class="modal-body p-0 text-center position-relative">
                <button type="button" class="close position-absolute text-white" data-dismiss="modal" aria-label="Close" style="top: 10px; right: 20px; z-index: 10; font-size: 2rem; outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <img src="" id="imgFull" class="img-fluid rounded shadow-lg">
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    // Mematikan alert default DataTables agar tidak muncul popup browser yang mengganggu
    $.fn.dataTable.ext.errMode = 'none';

    // DataTable Server Side
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
                if(json.token) {
                    // Update hash untuk request berikutnya jika diperlukan
                    // console.log("CSRF Updated");
                }
                return json.data;
            },
        },
        "columnDefs": [
            { "targets": [1, 4], "orderable": false },
            { "className": "text-center align-middle", "targets": [1, 2, 3, 4] }
        ],
    
    });

    // Preview Image Logic (Bootstrap 4 .modal('show'))
    $(document).on('click', '.preview-img', function() {
        const src = $(this).attr('data-src');
        $('#imgFull').attr('src', src);
        $('#previewModal').modal('show');
    });
});
</script>
<?= $this->endSection(); ?>
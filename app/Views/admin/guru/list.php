<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="card card-flush shadow-sm border-0">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-guru-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Instruktur..." />
                        </div>
                    </div>

                    <div class="card-toolbar">
                        <a href="<?= base_url('sw-admin/guru/create') ?>" class="btn btn-primary fw-bold">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah Instruktur
                        </a>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatable-list" class="table align-middle table-row-dashed fs-6 gy-5 w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Nama</th>
                                    <th class="min-w-150px">Email</th>
                                    <th class="min-w-5">Mapel</th>
                                    <th class="min-w-5px text-center">Soal</th>
                                    <th class="min-w-10px text-center">Opsi</th>
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
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        
        var table = $('#datatable-list').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= base_url('sw-admin/guru/datatable') ?>", 
                "type": "POST",
                "data": function(d) {
                    // Kirim CSRF Token
                    d.<?= csrf_token() ?> = "<?= csrf_hash() ?>";
                },
                "dataSrc": function(json) {
                    // Update hash CSRF setiap kali table reload agar tidak expired
                    $('input[name="<?= csrf_token() ?>"]').val(json.csrf_hash);
                    return json.data;
                }
            },
            "columns": [
                { 
                    "data": "nama",
                    "className": "text-gray-800 fw-bold"
                },
                { 
                    "data": "email" 
                },
                { 
                    "data": "mapel" 
                },
                { 
                    "data": "soal",
                    "className": "text-center"
                },
                { 
                    "data": "opsi",
                    "className": "text-center text-nowrap",
                    "orderable": false
                }
            ],
            // Inisialisasi ulang komponen menu bawaan Metronic jika terdapat di tombol Opsi
            "drawCallback": function(settings) {
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            }
        });

        // Fitur Pencarian Custom yang menyatu dengan UI Metronic
        $('[data-kt-guru-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

    });
</script>
<?= $this->endSection(); ?>
<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="card card-flush shadow-sm">
                
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-ujian-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Ujian..." />
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatable-list" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Nama Ujian</th>
                                    <th class="min-w-150px">Kelas</th>
                                    <th class="min-w-150px text-center">Status</th>
                                    <th class="text-center min-w-100px">Opsi</th>
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
            processing: true,
            serverSide: true,
            order: [[0, 'desc']],
            ajax: {
                url: "<?= base_url('sw-admin/guru/ajaxUjianGuru'); ?>",
                type: "POST",
                data: function(d) {
                    d.id_guru = "<?= $id_guru_enc ?>";
                    d['<?= csrf_token() ?>'] = "<?= csrf_hash() ?>";
                },
                dataSrc: function(json) {
                    if (json.token) {
                        $('input[name="<?= csrf_token() ?>"]').val(json.token);
                    }
                    return json.data;
                }
            },
            
            columns: [
                { 
                    data: "nama_ujian",
                    className: "text-gray-800 fw-bold" 
                },
                { 
                    data: "nama_kelas" 
                },
                { 
                    data: "status",
                    className: "text-center"
                },
                { 
                    data: "opsi",
                    className: "text-end" 
                }
            ],
            
            columnDefs: [{
                targets: [2, 3],
                orderable: false,
                searchable: false
            }],

            // Inisialisasi ulang komponen menu bawaan Metronic jika terdapat di tombol Opsi
            drawCallback: function(settings) {
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            }
        });

        // Fitur Pencarian Custom yang menyatu dengan UI Card Header Metronic
        $('[data-kt-ujian-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

    });
</script>
<?= $this->endSection(); ?>
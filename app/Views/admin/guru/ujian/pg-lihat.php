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
                            <input type="text" data-kt-peserta-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Peserta..." />
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="tableSiswa" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Peserta</th>
                                    <th class="text-center min-w-150px">Statistik</th>
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
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        
        var table = $('#tableSiswa').DataTable({
            "processing": true,
            "serverSide": true,
            "ordering": false,
            "ajax": {
                "url": "<?= base_url('sw-admin/guru/ajaxSiswaUjian/' . $kode_ujian_encrypt) ?>",
                "type": "POST",
                "data": function(d) {
                    d[csrfName] = csrfHash;
                },
                "dataSrc": function(json) {
                    // Update input CSRF dengan token baru yang dikirim oleh Controller
                    if(json[csrfName]) {
                        csrfHash = json[csrfName];
                    }
                    return json.data;
                }
            },
            // Inisialisasi ulang komponen menu bawaan Metronic jika terdapat di tombol Aksi
            "drawCallback": function(settings) {
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            }
        });

        // Fitur Pencarian Custom yang menyatu dengan UI Card Header Metronic
        $('[data-kt-peserta-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

    });
</script>
<?= $this->endSection(); ?>
<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card card-flush">
                <div class="card-header pt-6">
                    <div class="card-title">
                        <!-- Input Pencarian DataTables -->
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                            <input type="text" data-kt-ujian-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Cari Ujian..." />
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatable-list" class="table align-middle table-row-dashed fs-6 gy-5 w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2 text-center">#</th>
                                    <th class="min-w-175px">Nama Ujian & Kode</th>
                                    <th class="min-w-100px text-center">Total Peserta</th>
                                    <th class="min-w-150px">Status Pengerjaan</th>
                                    <th class="min-w-150px">Analisis Nilai</th>
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

<!-- Input Hidden untuk CSRF Token jika dibutuhkan oleh JS -->
<input type="hidden" class="csrf-token" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Inisialisasi DataTables
        var table = $('#datatable-list').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= base_url('sw-admin/ujian/datatable') ?>",
                type: "POST",
                data: function(d) {
                    d.<?= csrf_token() ?> = $('.csrf-token').val();
                },
                dataSrc: function(json) {
                    $('.csrf-token').val(json.csrfHash);
                    return json.data;
                },
            },
            columnDefs: [
                {
                    "targets": [0],
                    "orderable": false
                },
                {
                    "className": "text-center",
                    "targets": [0, 2]
                }
            ]
        });

        // Search DataTables Custom Input
        $('[data-kt-ujian-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });
    });
</script>
<?= $this->endSection(); ?>
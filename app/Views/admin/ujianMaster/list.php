<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card card-flush">
                <div class="card-header pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                            <input type="text" data-kt-ujian-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Cari Ujian..." />
                        </div>
                    </div>
                    
                    <!-- PERUBAHAN: Tombol diubah menjadi pemicu Modal -->
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-light-success fw-bold" data-bs-toggle="modal" data-bs-target="#modalFilterExcel">
                            <i class="ki-outline ki-file-down fs-3"></i> Download Excel
                        </button>
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

<!-- TAMBAHAN BARU: Modal Filter Download Excel -->
<div class="modal fade" id="modalFilterExcel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-400px">
        <div class="modal-content">
            <!-- Form mengarah ke URL Controller Export Excel dengan metode GET -->
            <form action="<?= base_url('sw-admin/ujian/export-excel') ?>" method="GET" target="_blank">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <div class="mb-13 text-center">
                        <h1 class="mb-3">Filter Download</h1>
                        <div class="text-muted fw-semibold fs-5">Pilih periode data peserta</div>
                    </div>
                    
                    <div class="d-flex flex-column mb-8 fv-row">
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                            <span class="required">Tahun</span>
                        </label>
                        <select name="tahun" class="form-select form-select-solid" required>
                            <option value="">-- Pilih Tahun --</option>
                            <?php 
                                // Looping dinamis tahun, dari tahun saat ini mundur 3 tahun
                                $tahunSekarang = date('Y');
                                for($i = $tahunSekarang; $i >= ($tahunSekarang - 3); $i--): 
                            ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="d-flex flex-column mb-8 fv-row">
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                            <span>Bulan (Opsional)</span>
                        </label>
                        <select name="bulan" class="form-select form-select-solid">
                            <option value="">Semua Bulan (Satu Tahun)</option>
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>

                    <div class="text-center">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-light me-3">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="ki-outline ki-file-down fs-4"></i> Download
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

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
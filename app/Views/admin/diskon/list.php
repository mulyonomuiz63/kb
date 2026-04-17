<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="card card-flush shadow-sm border-0">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title flex-column align-items-start">
                        <span class="card-label fw-bold text-gray-800">Daftar Diskon</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Kelola persentase diskon untuk paket kursus/layanan.</span>
                    </div>
                    
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-diskon-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Diskon..." />
                        </div>

                        <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#tambah_diskon">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah Diskon
                        </button>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatables-list" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Nama Diskon</th>
                                    <th class="min-w-150px">Besar Diskon (%)</th>
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
<div class="modal fade" id="tambah_diskon" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content rounded border-0">
            <form action="<?= base_url('sw-admin/diskon/store'); ?>" method="POST" class="needs-validation">
                <input type="hidden" name="<?= csrf_token() ?>" class="csrf-token" value="<?= csrf_hash() ?>" />
                
                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h2 class="fw-bold">Tambah Diskon Baru</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                
                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Nama Diskon</label>
                        <input type="text" name="nama" class="form-control form-control-solid" placeholder="Contoh: Promo Ramadhan" required>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Persentase Diskon (%)</label>
                        <input type="text" name="diskon" id="diskon" class="form-control form-control-solid" placeholder="0.00" required>
                        <div class="text-muted fs-7 mt-2"><i class="ki-duotone ki-information-5 text-gray-500 fs-6 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> Gunakan titik untuk desimal.</div>
                    </div>
                </div>
                
                <div class="modal-footer border-0 p-5 p-lg-10 pt-0">
                    <button type="button" class="btn btn-light me-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">
                        <span class="indicator-label"><i class="ki-duotone ki-save-2 fs-3 me-1"><span class="path1"></span><span class="path2"></span></i> Simpan Data</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_diskon" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content rounded border-0">
            <form action="<?= base_url('sw-admin/diskon/update'); ?>" method="POST">
                <input type="hidden" name="<?= csrf_token() ?>" class="csrf-token" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="iddiskon" id="iddiskon">
                
                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h2 class="fw-bold">Ubah Data Diskon</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                
                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Nama Diskon</label>
                        <input type="text" name="nama" id="nama" class="form-control form-control-solid" required>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Persentase Diskon (%)</label>
                        <input type="text" name="diskon" id="e_diskon" class="form-control form-control-solid" required>
                    </div>
                </div>
                
                <div class="modal-footer border-0 p-5 p-lg-10 pt-0">
                    <button type="button" class="btn btn-light me-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info fw-bold text-white">
                        <span class="indicator-label"><i class="ki-duotone ki-check fs-3 me-1"></i> Update Diskon</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        
        // 1. Inisialisasi DataTables Server Side
        var table = $('#datatables-list').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= base_url('sw-admin/diskon/datatables') ?>",
                "type": "POST",
                "data": function(d) {
                    d.<?= csrf_token() ?> = $('.csrf-token').val();
                },
                "dataSrc": function(json) {
                    // Selalu update token CSRF setiap kali tabel reload
                    $('.csrf-token').val(json.<?= csrf_token() ?>);
                    return json.data;
                }
            },
            "columns": [
                { "data": "nama", "className": "text-gray-800 fw-bold" },
                { "data": "diskon" },
                { "data": "opsi" }
            ],
            "columnDefs": [{
                "targets": [2],
                "orderable": false,
                "className": "text-end" // Bootstrap 5 Metronic: text-right diganti text-end
            }],
            "drawCallback": function(settings) {
                // Inisialisasi ulang tooltip & menu metronic tiap kali tabel refresh
                if (typeof KTMenu !== 'undefined') { KTMenu.createInstances(); }
            }
        });

        // Fitur Pencarian Custom yang menyatu dengan UI Card Header Metronic
        $('[data-kt-diskon-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        // 2. Fungsi Load Data ke Modal Edit (AJAX)
        $(document).on('click', '.edit-diskon', function() {
            const iddiskon = $(this).data('diskon');
            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-admin/diskon/edit') ?>",
                data: {
                    iddiskon: iddiskon,
                    <?= csrf_token() ?>: $('.csrf-token').val()
                },
                dataType: 'JSON',
                success: function(data) {
                    // Update CSRF token di semua form
                    $('.csrf-token').val(data.token);

                    $("#iddiskon").val(data.iddiskon);
                    $("#nama").val(data.nama);
                    $("#e_diskon").val(data.diskon);
                    $('#edit_diskon').modal('show');
                }
            });
        });

        // 3. Validasi Input Koma/Titik (LOGIKA ASLI TIDAK DIUBAH)
        function commaOnly(input) {
            var value = input.val();
            var update = value.replace(/,/g, '.').replace(/[^0-9.]/g, '');
            // Memastikan hanya satu titik desimal
            var parts = update.split('.');
            if (parts.length > 2) update = parts[0] + '.' + parts.slice(1).join('');
            input.val(update);
        }

        $('#diskon, #e_diskon').on('input', function() {
            commaOnly($(this));
        });
    });
</script>
<?= $this->endSection(); ?>
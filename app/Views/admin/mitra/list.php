<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-mitra-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Mitra..." />
                        </div>
                    </div>
                    <div class="card-toolbar">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambah_mitra">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah Mitra
                        </button>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatable-list" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-50px text-center">No</th>
                                    <th class="min-w-200px">Nama Mitra</th>
                                    <th class="min-w-200px">Kontak Email</th>
                                    <th class="min-w-100px text-center">Komisi</th>
                                    <th class="min-w-100px text-center">Aksi</th>
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
<div class="modal fade" id="edit_mitra" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded">
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h2 class="fw-bold">Pengaturan Mitra</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <form action="<?= base_url('sw-admin/mitra/update'); ?>" method="POST" class="form">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf-token" />
                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-15">

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Nama Mitra</label>
                        <input type="hidden" name="idmitra" id="idmitra">
                        <input type="text" name="nama_mitra" id="nama_mitra" class="form-control form-control-solid" required>
                    </div>

                    <div class="row g-9 mb-7">
                        <div class="col-md-7 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Email</label>
                            <input type="email" name="email" id="email" class="form-control form-control-solid" required>
                        </div>
                        <div class="col-md-5 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Komisi (%)</label>
                            <div class="input-group input-group-solid">
                                <input type="text" id="e_komisi" name="komisi" class="form-control form-control-solid" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Status Akun</label>
                        <select name="active" id="active" class="form-select form-select-solid" data-control="select2" data-hide-search="true">
                            <option value="1">Aktif</option>
                            <option value="0">Non-Aktif</option>
                        </select>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Ubah Kata Sandi</label>
                        <input type="text" name="sandi" class="form-control form-control-solid" placeholder="Minimal 6 karakter">
                        <div class="text-muted fs-7 mt-2">Kosongkan jika tidak diubah.</div>
                    </div>

                    <div class="text-center pt-5">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Simpan Perubahan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="tambah_mitra" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded">
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h2 class="fw-bold">Tambah Mitra Baru</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <form action="<?= base_url('sw-admin/mitra/store'); ?>" method="POST" class="form">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf-token">
                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-15">

                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="table-tambah-mitra">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Nama Mitra</th>
                                    <th class="min-w-200px">Email</th>
                                    <th class="min-w-100px text-center">Komisi (%)</th>
                                    <th class="min-w-150px">Sandi Default</th>
                                    <th class="min-w-50px text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-mitra">
                                <tr>
                                    <td><input type="text" name="nama_mitra[]" required class="form-control form-control-solid" placeholder="Nama Lengkap"></td>
                                    <td><input type="email" name="email[]" required class="form-control form-control-solid" placeholder="email@contoh.com"></td>
                                    <td><input type="number" name="komisi[]" step="0.01" required class="form-control form-control-solid text-center" value="0"></td>
                                    <td><input type="text" name="sandi[]" required class="form-control form-control-solid" value="Sandi@#123"></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-icon btn-light-danger btn-sm remove-row disabled" disabled>
                                            <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-light-primary btn-sm" id="add-more-row">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah Baris Lagi
                        </button>
                    </div>

                    <div class="text-center pt-10">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Simpan Semua Data</span>
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    <?= session()->getFlashdata('pesan'); ?>

    $(document).ready(function() {

        // Init Select2 di Modal Edit (jika menggunakan Metronic)
        $('#active').select2();

        // Inisialisasi DataTables
        var table = $('#datatable-list').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= base_url('sw-admin/mitra/datatable') ?>",
                type: "POST",
                data: function(d) {
                    d.<?= csrf_token() ?> = $('.csrf-token').val();
                },
                dataSrc: function(json) {
                    $('.csrf-token').val(json.csrfHash);
                    return json.data;
                },
            },
            columnDefs: [{
                    "targets": [0, 4],
                    "orderable": false
                },
                {
                    "className": "text-center",
                    "targets": [0, 3, 4]
                }
            ],
            drawCallback: function(settings) {
                // Inisialisasi ulang menu dropdown Metronic setelah data tabel dimuat
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            }
        });

        // Search DataTables Custom Input
        $('[data-kt-mitra-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Fungsi Click Edit
        $('#datatable-list').on('click', '.edit-mitra', function() {
            const idmitra = $(this).data('mitra');
            const csrfHash = $('.csrf-token').val();

            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-admin/mitra/mitra-by-id') ?>",
                data: {
                    <?= csrf_token() ?>: csrfHash,
                    idmitra: idmitra
                },
                dataType: 'JSON',
                success: function(data) {
                    $('.csrf-token').val(data.token); // Update CSRF
                    $("#idmitra").val(data.idmitra);
                    $("#nama_mitra").val(data.nama_mitra);
                    $("#email").val(data.email);
                    $("#e_komisi").val(data.komisi);

                    // Update Select2 secara programatik jika menggunakan Metronic
                    $("#active").val(data.is_active).trigger('change');

                    $('#edit_mitra').modal('show');
                },
            });
        });

        // Fungsi Tambah Baris Dinamis
        $('#add-more-row').click(function() {
            var newRow = `
            <tr>
                <td><input type="text" name="nama_mitra[]" required class="form-control form-control-solid" placeholder="Nama Lengkap"></td>
                <td><input type="email" name="email[]" required class="form-control form-control-solid" placeholder="email@contoh.com"></td>
                <td><input type="number" name="komisi[]" step="0.01" required class="form-control form-control-solid text-center" value="0"></td>
                <td><input type="text" name="sandi[]" required class="form-control form-control-solid" value="Sandi@#123"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-icon btn-light-danger btn-sm remove-row">
                        <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    </button>
                </td>
            </tr>`;
            $('#tbody-mitra').append(newRow);
        });

        // Fungsi Hapus Baris
        $(document).on('click', '.remove-row', function() {
            if ($('#tbody-mitra tr').length > 1) {
                $(this).closest('tr').remove();
            }
        });

        // Validasi input komisi agar hanya angka & titik
        function commaOnly(input) {
            let value = input.val().replace(/[^0-9.]/g, '');
            input.val(value);
        }

        $(document).on('input', '#komisi, #e_komisi', function() {
            commaOnly($(this));
        });

    });
</script>
<?= $this->endSection(); ?>
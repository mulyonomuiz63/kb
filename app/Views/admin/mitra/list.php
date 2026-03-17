<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">

            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold text-primary">Manajemen Mitra</h5>
                    <button class="btn btn-primary rounded-pill shadow-sm" data-toggle="modal" data-target="#tambah_mitra">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Mitra
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable-list" class="table table-hover text-nowrap w-100">
                            <thead class="bg-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Mitra</th>
                                    <th>Kontak Email</th>
                                    <th>Komisi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit_mitra" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title font-weight-bold">Pengaturan Mitra</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                </div>
                <form action="<?= base_url('sw-admin/mitra/update'); ?>" method="POST">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf-token" />
                    <div class="modal-body p-4">
                        <div class="form-group mb-3">
                            <label class="text-muted small fw-bold">NAMA MITRA</label>
                            <input type="hidden" name="idmitra" id="idmitra">
                            <input type="text" name="nama_mitra" id="nama_mitra" class="form-control rounded-3" required shadow-none>
                        </div>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group mb-3">
                                    <label class="text-muted small fw-bold">EMAIL</label>
                                    <input type="email" name="email" id="email" class="form-control rounded-3" required shadow-none>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group mb-3">
                                    <label class="text-muted small fw-bold">KOMISI (%)</label>
                                    <input type="text" id="e_komisi" name="komisi" class="form-control rounded-3" required shadow-none>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-muted small fw-bold">STATUS AKUN</label>
                            <select name="active" id="active" class="form-control custom-select rounded-3 shadow-none">
                                <option value="1">Aktif</option>
                                <option value="0">Non-Aktif</option>
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label class="text-muted small fw-bold">UBAH KATA SANDI</label>
                            <input type="text" name="sandi" class="form-control rounded-3 shadow-none" placeholder="Minimal 6 karakter">
                            <small class="text-info mt-1 d-block"><i class="bi bi-info-circle me-1"></i> Kosongkan jika tidak diubah.</small>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="tambah_mitra" tabindex="-1" role="dialog" aria-labelledby="tambah_mitraLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title font-weight-bold" id="tambah_mitraLabel">
                        <i class="bi bi-person-plus me-2"></i>Tambah Mitra Baru
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?= base_url('sw-admin/mitra/store'); ?>" method="POST">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf-token">
                    <div class="modal-body p-4">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="table-tambah-mitra">
                                <thead class="bg-light text-center">
                                    <tr>
                                        <th width="25%">Nama Mitra</th>
                                        <th width="25%">Email</th>
                                        <th width="15%">Komisi (%)</th>
                                        <th width="25%">Sandi Default</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-mitra">
                                    <tr>
                                        <td><input type="text" name="nama_mitra[]" required class="form-control shadow-none" placeholder="Nama Lengkap"></td>
                                        <td><input type="email" name="email[]" required class="form-control shadow-none" placeholder="email@contoh.com"></td>
                                        <td><input type="number" name="komisi[]" step="0.01" required class="form-control shadow-none text-center" value="0"></td>
                                        <td><input type="text" name="sandi[]" required class="form-control shadow-none" value="Sandi@#123"></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-light-danger text-danger btn-sm rounded-circle remove-row disabled">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-more-row">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Baris Lagi
                        </button>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow">Simpan Semua Data</button>
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
            // Inisialisasi DataTables
            var table = $('#datatable-list').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "<?= base_url('sw-admin/mitra/datatable') ?>",
                    type: "POST",
                    data: function(d) {
                        // Kirim CSRF Token terbaru di setiap request
                        d.<?= csrf_token() ?> = $('.csrf-token').val();
                    },
                    dataSrc: function(json) {
                        // Update CSRF token di input hidden setelah server merespon
                        $('.csrf-token').val(json.csrfHash);
                        return json.data;
                    },
                },
                columnDefs: [{
                        "targets": [0, 4],
                        "orderable": false
                    }, // No dan Aksi tidak bisa disortir
                    {
                        "className": "text-center",
                        "targets": [0, 3, 4]
                    }
                ],
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"></div>'
                }
            });

            // Fungsi Click Edit (Delegation untuk elemen yang baru muncul via AJAX)
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
                        $("#active").val(data.is_active);
                        $('#edit_mitra').modal('show');
                    },
                });
            });
        });

        // Fungsi Tambah Baris Dinamis
        $('#add-more-row').click(function() {
            var newRow = `
        <tr>
            <td><input type="text" name="nama_mitra[]" required class="form-control shadow-none"></td>
            <td><input type="email" name="email[]" required class="form-control shadow-none"></td>
            <td><input type="number" name="komisi[]" step="0.01" required class="form-control shadow-none text-center" value="0"></td>
            <td><input type="text" name="sandi[]" required class="form-control shadow-none" value="Sandi@#123"></td>
            <td class="text-center">
                <button type="button" class="btn btn-light-danger text-danger btn-sm rounded-circle remove-row">
                    <i class="bi bi-trash"></i>
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

        $('#komisi, #e_komisi').on('input', function() {
            commaOnly($(this));
        });
    </script>


    <?= $this->endSection(); ?>
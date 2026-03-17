<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">
            <div class="widget shadow-sm p-4 bg-white rounded">
                <div class="widget-heading d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="font-weight-bold text-dark">Manajemen Diskon</h5>
                        <p class="text-muted small">Kelola persentase diskon untuk paket kursus/layanan.</p>
                    </div>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah_diskon">
                        <i class="flaticon-plus-1"></i> Tambah Diskon
                    </button>
                </div>

                <div class="table-responsive">
                    <table id="datatables-list" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Diskon</th>
                                <th>Besar Diskon (%)</th>
                                <th class="text-right">Opsi</th>
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

<div class="modal fade" id="tambah_diskon" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0">
            <form action="<?= base_url('sw-admin/diskon/store'); ?>" method="POST" class="needs-validation">
                <input type="hidden" name="<?= csrf_token() ?>" class="csrf-token" value="<?= csrf_hash() ?>" />
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white">Tambah Diskon Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Nama Diskon</label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Promo Ramadhan" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Persentase Diskon (%)</label>
                        <input type="text" name="diskon" id="diskon" class="form-control" placeholder="0.00" required>
                        <small class="text-muted text-italic">*Gunakan titik untuk desimal</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_diskon" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0">
            <form action="<?= base_url('sw-admin/diskon/update'); ?>" method="POST">
                <input type="hidden" name="<?= csrf_token() ?>" class="csrf-token" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="iddiskon" id="iddiskon">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title text-white">Ubah Data Diskon</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Nama Diskon</label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Persentase Diskon (%)</label>
                        <input type="text" name="diskon" id="e_diskon" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">Update Diskon</button>
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
                "url": "<?= base_url('sw-admin/diskon/datatables') ?>", // Buat method ini di Controller
                "type": "POST",
                "data": function(d) {
                    d.<?= csrf_token() ?> = $('.csrf-token').val();
                },
                "dataSrc": function(json) {
                    // Selalu update token CSRF setiap kali tabel reload
                    $('.csrf-token').val(json.<?= csrf_token() ?>);
                    return json.data;
                },
                error: function(xhr, error, thrown) {
                    console.log('Error loading data: ', error);
                    console.log('Response: ', xhr.responseText);
                    console.log('Thrown: ', thrown);
                    console.log('Status: ', xhr.status);
                }
            },
            "columns": [{
                    "data": "nama"
                }, // Sesuai dengan key "nama" di Controller
                {
                    "data": "diskon"
                }, // Sesuai dengan key "diskon" di Controller
                {
                    "data": "opsi"
                } // Sesuai dengan key "opsi" di Controller
            ],
            "columnDefs": [{
                "targets": [2], // Kolom Opsi
                "orderable": false,
                "className": "text-right"
            }],
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

        // 3. Validasi Input Koma/Titik
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

<style>
    .widget {
        border-radius: 10px;
        border: none;
    }

    .table thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 1px;
    }

    .badge-primary {
        cursor: pointer;
        padding: 8px 12px;
    }
</style>
<?= $this->endSection(); ?>
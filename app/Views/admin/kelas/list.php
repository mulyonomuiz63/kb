<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">
            <div class="widget shadow p-3 bg-white rounded">
                <div class="widget-heading d-flex justify-content-between align-items-center">
                    <h5 class="font-weight-bold">Daftar Kelas</h5>
                    <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal" data-target="#tambah_kelas">
                        <i class="bi bi-plus-circle mr-1"></i> Tambah Kelas
                    </a>
                </div>
                <hr>
                <div class="table-responsive">
                    <table id="datatables-list" class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Kelas</th>
                                <th class="text-center" width="150">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($kelas as $k) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><span class="badge outline-badge-info"><?= $k->nama_kelas; ?></span></td>
                                    <td class="text-center">
                                        <div class="dropdown custom-dropdown">
                                            <a class="dropdown-toggle badge badge-primary border-0" href="#" role="button" id="dropdownMenu<?= $k->id_kelas; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right shadow border-0" aria-labelledby="dropdownMenu<?= $k->id_kelas; ?>">

                                                <a class="dropdown-item py-2 edit-kelas"
                                                    href="javascript:void(0)"
                                                    data-id="<?= encrypt_url($k->id_kelas); ?>">
                                                    <i class="bi bi-pencil-square me-2 text-primary"></i> Edit Kelas
                                                </a>

                                                <div class="dropdown-divider"></div>

                                                <a class="dropdown-item py-2 btn-delete"
                                                    href="javascript:void(0)"
                                                    data-url="<?= base_url('sw-admin/kelas/delete/' . encrypt_url($k->id_kelas)); ?>">
                                                    <i class="bi bi-trash me-2 text-danger"></i> Hapus Kelas
                                                </a>

                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tambah_kelas" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('sw-admin/kelas/store'); ?>" method="POST">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Tambah Kelas Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-outline-success btn-sm mb-3 tambah-baris-kelas">
                        <i class="bi bi-plus-lg"></i> Tambah Baris
                    </button>
                    <table class="table table-sm">
                        <tbody id="tbody-kelas">
                            <tr>
                                <td><input type="text" name="nama_kelas[]" placeholder="Contoh: X RPL 1" required class="form-control"></td>
                                <td width="50"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="edit_kelas" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('sw-admin/kelas/update'); ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id_kelas" id="id_kelas_edit">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white">Edit Nama Kelas</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kelas</label>
                        <input type="text" name="nama_kelas" id="nama_kelas_edit" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info text-white">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>


<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#datatables-list').DataTable({
            "ordering": false,
            "lengthChange": false,
            "pageLength": 10
        });

        // --- 2. MULTIPLE INSERT (Tambah Baris) ---
        $('.tambah-baris-kelas').click(function() {
            let html = `<tr>
            <td><input type="text" name="nama_kelas[]" required class="form-control"></td>
            <td><button type="button" class="btn btn-danger btn-sm hapus-baris"><i class="bi bi-x"></i></button></td>
        </tr>`;
            $('#tbody-kelas').append(html);
        });

        $(document).on('click', '.hapus-baris', function() {
            $(this).closest('tr').remove();
        });

        // --- 3. AJAX EDIT ---
        $('.edit-kelas').click(function() {
            const id_enc = $(this).data('id');
            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-admin/kelas/edit') ?>",
                data: {
                    id_kelas: id_enc,
                    "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
                },
                dataType: 'JSON',
                success: function(response) {
                    // Set data ke modal
                    $('#id_kelas_edit').val(response.kelas.id_kelas);
                    $('#nama_kelas_edit').val(response.kelas.nama_kelas);

                    // Update CSRF Hash di form (agar tidak error saat submit)
                    $('input[name="<?= csrf_token() ?>"]').val(response.token_baru);

                    $('#edit_kelas').modal('show');
                },
            });
        });
    });
</script>

<?= $this->endSection(); ?>
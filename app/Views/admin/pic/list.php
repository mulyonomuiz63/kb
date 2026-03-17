<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">
            <div class="widget shadow p-3 bg-white rounded">
                <div class="widget-heading">
                    <h5 class="">Daftar PIC</h5>
                    <a href="javascript:void(0)" class="btn btn-primary mt-3" data-toggle="modal" data-target="#tambah_pic">Tambah PIC</a>
                </div>
                <div class="table-responsive mt-3">
                    <table id="datatable-list" class="table text-left text-nowrap">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pic as $g) : ?>
                                <tr>
                                    <td><?= $g->nama_pic; ?></td>
                                    <td><?= $g->email; ?></td>
                                    <td>
                                        <span class="badge <?= $g->is_active == 1 ? 'bg-success' : 'bg-danger' ?>">
                                            <?= $g->is_active == 1 ? 'Aktif' : 'Non-Aktif' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#edit_pic" data-pic="<?= encrypt_url($g->idpic); ?>" class="badge bg-primary edit-pic">
                                            <i class="bi bi-gear"></i>
                                        </a>
                                        <a href="javascript:void(0)" data-url="<?= base_url('sw-admin/pic/delete/' . encrypt_url($g->idpic)); ?>" class="badge bg-danger btn-delete">
                                            <i class="bi bi-trash"></i>
                                        </a>
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

<div class="modal fade" id="tambah_pic" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-lg" role="document">
        <form action="<?= base_url('sw-admin/pic/store'); ?>" method="POST">
            <?= csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah PIC Baru</h5>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-success btn-sm mb-3 tambah-baris-pic">Tambah Baris</button>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Sandi</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                        <tbody id="tbody-pic">
                            <tr>
                                <td><input type="text" name="nama_pic[]" required class="form-control"></td>
                                <td><input type="email" name="email[]" required class="form-control"></td>
                                <td><input type="text" name="sandi[]" value="Sandi@#" required class="form-control"></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="edit_pic" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('sw-admin/pic/update'); ?>" method="POST">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf-token" />
            <input type="hidden" name="idpic" id="idpic">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit PIC</h5>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama PIC</label>
                        <input type="text" name="nama_pic" id="nama_pic" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Status Aktif</label>
                        <select name="active" id="active" class="form-control">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ganti Sandi <small class="text-muted">(Kosongkan jika tidak diubah)</small></label>
                        <input type="text" name="sandi" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#datatable-list').DataTable({
            "ordering": false
        });
        // Tambah Baris Dinamis
        $('.tambah-baris-pic').click(function() {
            const row = `
            <tr>
                <td><input type="text" name="nama_pic[]" required class="form-control"></td>
                <td><input type="email" name="email[]" required class="form-control"></td>
                <td><input type="text" name="sandi[]" required value="Sandi@#" class="form-control"></td>
                <td><button type="button" class="btn btn-danger btn-sm hapus-baris">X</button></td>
            </tr>`;
            $('#tbody-pic').append(row);
        });

        $(document).on('click', '.hapus-baris', function() {
            $(this).closest('tr').remove();
        });

        // AJAX Edit PIC
        $('.edit-pic').click(function() {
            const idpic = $(this).data('pic');
            const csrfName = "<?= csrf_token() ?>";
            const csrfToken = $('.csrf-token').val();

            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-admin/pic/edit') ?>",
                data: { idpic: idpic, [csrfName]: csrfToken },
                dataType: 'JSON',
                success: function(data) {
                    // Update CSRF Token agar klik selanjutnya tidak 403
                    if (data.token_baru) { $('.csrf-token').val(data.token_baru); }

                    // Isi Modal (Gunakan data.voucher sesuai struktur Controller mitra semalam)
                    const p = data.pic;
                    $("#idpic").val(idpic); // Tetap gunakan yang terenkripsi untuk keamanan
                    $("#nama_pic").val(p.nama_pic);
                    $("#email").val(p.email);
                    $("#active").val(p.is_active);
                    
                    $('#edit_pic').modal('show').removeAttr('aria-hidden');
                }
            });
        });
    });
</script>

<?= $this->endSection(); ?>
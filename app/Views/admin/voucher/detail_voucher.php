<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">
            <div class="widget shadow p-3 bg-white rounded">
                <div class="widget-heading">
                    <h5>Detail Paket Dalam Voucher</h5>
                    <a href="<?= base_url('sw-admin/mitra') ?>" class="btn btn-info mt-3">Kembali</a>
                    <button class="btn btn-primary mt-3" data-toggle="modal" data-target="#tambah_paket">Tambah</button>
                </div>
                <div class="table-responsive mt-3">
                    <table id="datatable-list" class="table text-left">
                        <thead>
                            <tr>
                                <th>Kode Voucher</th>
                                <th>Nama Paket</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detailvoucher as $s) : ?>
                                <tr>
                                    <td><?= $s->kode_voucher; ?></td>
                                    <td><?= $s->nama_paket; ?></td>
                                    <td>
                                        <a href="javascript:void(0)"
                                            data-url="<?= base_url('sw-admin/mitra/delete-voucher-paket/' . encrypt_url($s->iddetailvoucher) . '/' . encrypt_url($s->idvoucher)); ?>"
                                            class="badge bg-danger btn-delete">
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

<div class="modal fade" id="tambah_paket" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-md" role="document">
        <form action="<?= base_url('sw-admin/mitra/store-voucher-paket'); ?>" method="POST">
            <?= csrf_field(); ?>
            <input type="hidden" name="idvoucher" value="<?= $idvoucher ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Paket ke Voucher</h5>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-success btn-sm mb-3 tambah-baris-paket">Tambah Baris</button>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Paket</th>
                                <th width="10%">Opsi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-paket">
                            <tr>
                                <td>
                                    <select name="idpaket[]" class="form-control" required>
                                        <option value="">-- Pilih Paket --</option>
                                        <?php foreach ($paket as $p) : ?>
                                            <option value="<?= $p->idpaket; ?>"><?= $p->nama_paket; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
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

<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#datatable-list').DataTable({
            "ordering": false
        });
        

        // Tambah Baris Paket
        $('.tambah-baris-paket').click(function() {
            const row = `
        <tr>
            <td>
                <select name="idpaket[]" class="form-control" required>
                    <option value="">-- Pilih Paket --</option>
                    <?php foreach ($paket as $p) : ?>
                        <option value="<?= $p->idpaket; ?>"><?= $p->nama_paket; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm hapus-baris">X</button>
            </td>
        </tr>`;
            $('#tbody-paket').append(row);
        });

        // Hapus Baris
        $(document).on('click', '.hapus-baris', function() {
            $(this).closest('tr').remove();
        });
    });
</script>

<?= $this->endSection(); ?>
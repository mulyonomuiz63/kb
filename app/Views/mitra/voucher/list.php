<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<!--  BEGIN CONTENT AREA  -->
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <div class="widget shadow p-3 bg-white">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="widget-heading">
                                <h5 class="">Mitra KelasBrevet</h5>
                            </div>
                            <div class="table-responsive">
                                <table id="datatables-list" class="table text-left text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Mitra</th>
                                            <th>Kode Voucher</th>
                                            <th>Diskon Voucher</th>
                                            <th>Tgl Pembuatan</th>
                                            <th>Tgl Expire</th>
                                            <th>Status</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($voucher as $s) : ?>
                                            <tr>
                                                <td><?= $s->nama_mitra; ?></td>
                                                <td><?= $s->kode_voucher; ?></td>
                                                <td class="text-center"><?= $s->diskon_voucher; ?> %</td>
                                                <td><?= $s->tgl_aktif; ?></td>
                                                <td><?= $s->tgl_exp; ?></td>
                                                <td><?= $s->status == 'A'? '<span class="badge  bg-success">Aktif</span>':'<span class="badge  bg-danger">Tidak Aktif</span>'; ?></td>
                                                <td>
                                                    <a href="<?= base_url('sw-mitra/detail-voucher/') . encrypt_url($s->kode_voucher); ?>" class="badge  bg-info">
                                                        <i class="bi bi-eye-fill"></i>
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
        </div>
    </div>
<!--  END CONTENT AREA  -->


<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>

<script>
    $(document).ready(function() {
        $('#datatables-list').DataTable({
            "ordering": false,
            "lengthChange": false,
            "pageLength": 10
        });
    })
    
</script>

<?= $this->endSection(); ?>
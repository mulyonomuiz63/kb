<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?= $this->include('template/sidebar/mitra'); ?>
<?php $db = Config\Database::connect(); ?>

<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <div class="widget shadow p-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="widget-heading">
                                <h5 class="">Mitra KelasBrevet</h5>
                            </div>
                            <div class="table-responsive">
                                <table id="datatable-table" class="table text-left text-nowrap">
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
                                                    <a href="<?= base_url('Mitra/detail_voucher/') . '/' . encrypt_url($s->kode_voucher); ?>" class="badge  bg-info">
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
    <div class="footer-wrapper">
        <div class="footer-section f-section-1">
            <p class="terms-conditions"><?= copyright() ?></p>
        </div>
        <div class="footer-section f-section-2">
           
        </div>
    </div>
</div>
<!--  END CONTENT AREA  -->



<script>
    <?= session()->getFlashdata('pesan'); ?>

    $(document).ready(function() {

        $('.edit-voucher').click(function() {
            const idvoucher = $(this).data('voucher');
            $.ajax({
                type: 'POST',
                data: {
                    idvoucher: idvoucher
                },
                dataType: 'JSON',
                async: true,
                url: "<?= base_url('App/edit_voucher') ?>",
                success: function(data) {
                    $.each(data, function(idvoucher, diskon_voucher, status) {
                        $("#idvoucher").val(data.idvoucher);
                        $("#idmitra").val(data.idmitra);
                        $("#diskon_voucher").val(data.diskon_voucher);
                        $("#status").val(data.status);
                        $("#tgl_exp").html("<span class='text-danger'>Tgl Exp "+data.tgl_exp+"</span>")
                    });
                }
            });
        });
        // END voucher
    })
    
</script>

<?= $this->endSection(); ?>
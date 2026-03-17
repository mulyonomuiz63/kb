<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?= $this->include('template/sidebar/admin'); ?>
<?php $db = Config\Database::connect(); ?>
<style>
    .zoom {
          transition: transform .2s; /* Animation */
        }
        
    .zoom:hover {
      transform: scale(1.1); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
    }
</style>

<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <div class="widget shadow p-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="widget-heading">
                                <h5 class="">Transaksi Menggunakan Kode Voucher</h5>
                                <a href="javascript:window.history.go(-1);" class="btn btn-info mt-3">Kembali</a>
                            </div>
                            <div class="table-responsive">
                                <table id="datatable-table" class="table text-left text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="text-wrap">Voucher</th>
                                            <th class="text-wrap">Tgl Transaksi</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($transaksi as $s) : ?>
                                        <?php 
                                            $bulan = date('n', strtotime($s->tgl_pembayaran));
                                            $tahun  = date('Y', strtotime($s->tgl_pembayaran));
                                            $bulanini = date('n');
                                            $tahunini = date('Y');
                                            
                                            $bulantahun = $bulan.'-'.$tahun;
                                            $bulantahunini =  $bulanini.'-'.$tahunini;
                                            
                                        ?>
                                            <tr class="<?= $bulantahun == $bulantahunini? 'bg-light':'' ?>">
                                                <td class="text-wrap"><?= $s->kode_voucher; ?></td>
                                                <td class="text-wrap"><?= $s->tgl_pembayaran; ?></td>
                                                <td>
                                                   <a href="<?= base_url('App/detail_voucher/'.encrypt_url($s->kode_voucher)); ?>" class="badge badge-success"  data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Lihat Komisi"><i class="bi bi-eye"></i></a>
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
</script>


<?= $this->endSection(); ?>
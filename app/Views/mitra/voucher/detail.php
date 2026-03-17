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
                                <?php 
                                    $nama = $voucher->nama_mitra;
                                    $kode_voucher = $voucher->kode_voucher;
                                    $total_pengguna = 0;
                                    $total_harga = 0;
                                    $idmitra =  $voucher->idmitra;
                                    foreach ($transaksi as $s) : 
                                        $diskon         = ($s->nominal * $s->diskon) / 100;
                                        $totalDiskon    = $s->nominal - $diskon ;
                                        $diskon_voucher = ($totalDiskon * $s->voucher) / 100;
                                        $total_diskon_voucher = $totalDiskon - $diskon_voucher;
                                        $jumlah = ($s->nominal - $diskon - $diskon_voucher);
                                        $total_pengguna += 1;
                                        $total_harga += $jumlah;
                                    endforeach;

                                     if(!empty($voucher)){
                                         $komisi = $voucher->komisi;
                                     }else{
                                         $komisi = 0;
                                     }
                                     
                                ?>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Mitra</th>
                                            <th>:</th>
                                            <th><?= $nama ?></th>
                                        </tr>
                                        <tr>
                                            <th>Kode Voucher</th>
                                            <th>:</th>
                                            <th><?= $kode_voucher ?></th>
                                        </tr>
                                        <tr>
                                            <th>Total Pengguna</th>
                                            <th>:</th>
                                            <th><?= $total_pengguna ?> Peserta</th>
                                        </tr>
                                        <tr>
                                            <th>Total Harga</th>
                                            <th>:</th>
                                            <th>Rp. <?= number_format($total_harga, 0, '.', '.') ?></th>
                                        </tr>
                                        <tr>
                                            <th>% Komisi</th>
                                            <th>:</th>
                                            <th><?= $komisi ?> %</th>
                                        </tr>
                                        <tr>
                                            <th>Nilai Komisi</th>
                                            <th>:</th>
                                            <th>Rp. <?= number_format(($total_harga * $komisi)/100 , 0, '.', '.') ?></th>
                                        </tr>
                                    </thead>
                                </table>
                                <a href="javascript:window.history.go(-1);" class="btn btn-info mt-3">Kembali</a>
                            </div>
                            <div class="table-responsive">
                                <table id="datatable-table" class="table text-left text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Siswa</th>
                                            <th>Paket</th>
                                            <th class="text-wrap">Tgl Pembayaran</th>
                                            <th>Nominal</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($transaksi as $s) : ?>
                                            <tr>
                                                <td class="text-wrap"><?= $s->nama_siswa; ?></td>
                                                <td class="text-wrap"><?= $s->nama_paket; ?></td>
                                                <td class="text-wrap"><?= $s->tgl_pembayaran; ?></td>
                                                  <?php 
                                                        $diskon         = ($s->nominal * $s->diskon) / 100;
                                                        $totalDiskon    = $s->nominal - $diskon ;
                                                        $diskon_voucher = ($totalDiskon * $s->voucher) / 100;
                                                        $total_diskon_voucher = $totalDiskon - $diskon_voucher;
                                                    ?>
                                                <td class="text-center"><?= number_format($s->nominal - $diskon - $diskon_voucher, 0, '.', '.'); ?></td>
                                                <td>
                                                    <?php if ($s->status == 'V') : ?>
                                                        <span class="badge  bg-info">Menunggu Approved</span>
                                                    <?php else : ?>
                                                        <span class="badge  bg-success">Approved</span>
                                                    <?php endif; ?>
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


<?= $this->endSection(); ?>
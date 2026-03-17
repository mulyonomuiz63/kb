<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?= $this->include('template/sidebar/pic'); ?>

<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <div class="widget shadow p-3">
                    <div class="row">
                        <div class="col-lg-12"> 
                            <div class="widget-heading">
                                <a href="javascript:history.back()" class="btn btn-info">Kembali</a>
                            </div>
                            <div class="table-responsive">
                                <table id="datatable-table" class="table text-left text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Peserta</th>
                                            <th>email</th>
                                            <th>lembaga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       <?php foreach($presensi as $rows): ?>
                                            <tr>
                                                <td class="text-wrap"><?= $rows->nama_siswa ?></td>
                                                <td><?= $rows->email ?></td>
                                                <td><?= $rows->kota_intansi ?></td>
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
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
                            <div class="widget-heading d-flex justify-content-between">
                                <div>
                                     <a href="javascript:window.history.go(-1);" class="btn btn-primary">Kembali</a>
                                </div>
                                <?php
                                    $idsiswa=''; 
                                    foreach ($ujian as $u) : 
                                    $idsiswa= $u->id_siswa;
                                ?>
                                <?php endforeach; ?>
                                <?php if($total != 0 ){ ?>
                                    <?php if($totalSertifikat >= $total){ ?>
                                        <div>
                                            <a href="javascript:void(0)" data-toggle="modal" data-target="#sertifikat_all_cetak_modal" data-sertifikat_all="<?= base_url("siswa/lihat_sertifikat_brevet/".encrypt_url($idsiswa)) ?>" class="badge badge-success sertifikat_all_cetak"  data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Unduh Sertifikat Brevet AB"><i class="bi bi-download"></i></a>
                                            <a href="javascript:void(0)" data-toggle="modal" data-target="#sertifikat_all_cap_cetak_modal" data-sertifikat_all_cap="<?= base_url("siswa/lihat_sertifikat_brevet/".encrypt_url($idsiswa))."/cap" ?>" class="badge badge-primary sertifikat_all_cap_cetak" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Unduh Sertifikat Cap Basah"><i class="bi bi-download"></i></a>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                            <div class="table-responsive" style="overflow-x: scroll;">
                                <table id="datatable-table" class="table text-left text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Nama Ujian</th>
                                            <th>Mulai/Selesai</th>
                                            <th>Nilai</th>
                                            <th>Status</th>
                                            <th>Sertifikat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ujian as $u) : ?>
                                            <tr>
                                                <td><?= $u->nama_ujian; ?></td>
                                                <td><?= $u->start_ujian.' / '. $u->end_ujian; ?></td>

                                                <td><?= $u->nilai ?></td>
                                                <td><?= $u->nilai >= 60 ? '<span class="badge badge-success ml-2">Lulus</span>' : '<span class="badge badge-danger ml-2">Tidak Lulus</span>' ?></td>
                                                <td>
                                                    <?= $u->nilai >= 60 ? '<a href="javascript:void(0)" data-toggle="modal" data-target="#sertifikat_cetak_modal" data-sertifikat="' . base_url("siswa/lihat_sertifikat/") . '/' . encrypt_url($u->kode_ujian) . "/" . encrypt_url($u->id_ujian) . '" class="badge badge-success mr-2 sertifikat_cetak" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Unduh"><i class="bi bi-download"></i></a><a href="javascript:void(0)" class="badge badge-primary sertifikat_cap_cetak" data-toggle="modal" data-target="#sertifikat_cap_cetak_modal" data-sertifikat_cap="' . base_url("siswa/lihat_sertifikat/") . '/' . encrypt_url($u->kode_ujian) . "/" . encrypt_url($u->id_ujian) .'/cap" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Unduh Cap Basah"><i class="bi bi-download"></i></a>' : '<span class="badge badge-secundary"><i class="bi bi-download"></i></span>' ?>
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
<!--modal sertifikat cap-->
<div class="modal fade" id="sertifikat_cap_cetak_modal" tabindex="-1" role="dialog" aria-labelledby="sertifikat_capLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="isiKontenSetifikatCap"></div>
        </div>
    </div>
</div>
<!--modal sertifikat cap-->
<!--modal sertifikat-->
<div class="modal fade" id="sertifikat_cetak_modal" tabindex="-1" role="dialog" aria-labelledby="sertifikat_Label" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="isiKontenSertifikat"></div>
        </div>
    </div>
</div>
<!--modal sertifikat-->

<!--modal sertifikat all cap-->
<div class="modal fade" id="sertifikat_all_cap_cetak_modal" tabindex="-1" role="dialog" aria-labelledby="sertifikat_all_capLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="isiKontenSetifikatAllCap"></div>
        </div>
    </div>
</div>
<!--modal sertifikat all cap-->
<!--modal sertifikat all-->
<div class="modal fade" id="sertifikat_all_cetak_modal" tabindex="-1" role="dialog" aria-labelledby="sertifikat_all_Label" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="isiKontenSertifikatAll"></div>
        </div>
    </div>
</div>
<!--modal sertifikat all-->
<!--  END CONTENT AREA  -->

<script>
    <?= session()->getFlashdata('pesan'); ?>
    $('.sertifikat_cap_cetak').click(function() {
        const url = $(this).data('sertifikat_cap');
            $(".isiKontenSetifikatCap").html(`
            <div class="modal-header">
                <h5 class="modal-title">Sertifikat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    x
                </button>
            </div>
                <iframe src="${url}" width="100%" height="500vh"></iframe>
            `);
    });
    $('.sertifikat_cetak').click(function() {
        const url = $(this).data('sertifikat');
            $(".isiKontenSertifikat").html(`
            <div class="modal-header">
                <h5 class="modal-title">Sertifikat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    x
                </button>
            </div>
                <iframe src="${url}" width="100%" height="500vh"></iframe>
            `);
    });
    
    $('.sertifikat_all_cap_cetak').click(function() {
        const url = $(this).data('sertifikat_all_cap');
            $(".isiKontenSetifikatAllCap").html(`
            <div class="modal-header">
                <h5 class="modal-title">Sertifikat Brevet AB</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    x
                </button>
            </div>
                <iframe src="${url}" width="100%" height="500vh"></iframe>
            `);
    });
    $('.sertifikat_all_cetak').click(function() {
        const url = $(this).data('sertifikat_all');
            $(".isiKontenSertifikatAll").html(`
            <div class="modal-header">
                <h5 class="modal-title">Sertifikat Brevet AB</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    x
                </button>
            </div>
                <iframe src="${url}" width="100%" height="500vh"></iframe>
            `);
    });
</script>

<?= $this->endSection(); ?>
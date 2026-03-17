<?= $this->extend('landing/template'); ?>
<?= $this->section('content'); ?>
<div class="section mb-4 " id="">
    <div class="container">
        <div class="photo-gallery">
            <div class="row siap-kerja">
                <?php  foreach($data["galeri"] as $rows):  ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mt-4">
                        <div class="photo-thumb position-relative">
                            <?= img_lazy('uploads/galeri/thumbnails/'.$rows['file'],$rows['judul'], ['class' => '']) ?>
                            <div class="bg"></div>
                            <div class="icon position-absolute top-50 start-50 translate-middle">
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modal_view_galeri" data-url_galleri="<?= base_url('uploads/galeri/thumbnails/'.$rows['file']) ?>" class="badge badge-success mr-2 view_galeri"><i class="fas fa-plus"></i></a></a>
                            </div>
                        </div>
                        <div class="photo-caption text-wrap">
                            <a href="javascript:void;"><?= $rows['judul'] ?></a>
                        </div>
                        <div class="photo-date">
                            <i class="fas fa-calendar-alt"></i> <?= tanggal_indo($rows['tgl_pelatihan']) ?>
                        </div>
                    </div>
                <?php endforeach ?>
                <div class="col-12 float-sm-end mt-4"> 
                    <?php echo $data["pager"]->links('default', 'custom_pager') ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!--modal sertifikat cap-->
<div class="modal fade" id="modal_view_galeri" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered d-flex justify-content-center">
        <div class="isiKonten"></div>
    </div>
</div>
<!--modal sertifikat cap-->

<script>
    
</script>
<?= $this->endSection(); ?>
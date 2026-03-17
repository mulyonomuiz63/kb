<?= $this->extend('landing/template'); ?>
<?= $this->section('css') ?>
<style>
    .frame {
      width: 200px;   /* ukuran bingkai tetap */
      height: 200px;  /* ukuran bingkai tetap */
      overflow: hidden;       /* potong gambar kalau keluar */
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .inside-frame {
      width: 100%;
      height: 100%;
      object-fit: contain; /* biar gambar muat rapi tanpa distorsi */
    }

</style>
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>
<div class="section mb-4 " id="">
    <div class="container">
        <div class="photo-gallery">
            <div class="row siap-kerja">
                <?php  foreach($data["twibbon"] as $rows):  ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mt-4">
                        <div class="photo-thumb position-relative frame">
                            <?= img_lazy('uploads/twibbon/thumbnails/'.$rows['file'],"loading", ['class' => 'inside-frame', 'width' => "200px", 'height' => "200px"]) ?>
                        </div>
                        <div class="photo-caption text-wrap">
                            <a href="<?= base_url("twibbon/".$rows['url']) ?>"><?= $rows['judul'] ?></a>
                        </div>
                        <div class="photo-date">
                            <i class="fas fa-calendar-alt"></i> <?= date("d M Y", strtotime($rows['created_at'])); ?>
                            <i class="fas fa-user ms-2 me-1"></i> <?= $rows['pengguna']; ?> dukungan
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
<?= $this->endSection(); ?>
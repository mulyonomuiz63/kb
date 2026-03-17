<?= $this->extend('landing/template'); ?>
<?= $this->section('content'); ?>


<div class="section mb-4" id="">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8 mb-8">
                <div class="overflow-hidden position-relative mt-8">
                    <?= img_lazy('uploads/artikel/thumbnails/'.$detail->image_default,"loading", ['class' => 'zoom', 'width'=>'100%']) ?>
                    <div class="position-absolute bottom-0 start-0 kategori-artikel p-1 text-white"><?= $detail->kategori ?></div>
                </div>
                <div class="">
                    <div><h3  class="judul-artikel mt-2"><?= $detail->judul ?></h3></div>
                    <div class="post-meta-wrapper d-flex justify-content-start mt-2">
						<span class="posted-on-bottom"> 
						    <i class="bi bi-calendar me-1"></i><?= date('d/m/Y', strtotime($detail->created_at)) ?>
						</span>
						<span class="posted-on-bottom ms-4"> 
						    <i class="bi bi-person me-2"></i><?= $detail->nama_admin ?>
						</span>
						<span class="posted-on-bottom ms-4"> 
						    <i class="bi bi-eye me-2"></i><?= $detail->hit ?>
						</span>
					</div>
                    <div class="mt-2"><?= $detail->konten ?></div>
                </div>
                <div class="block-line">Tag Terkait</div>
                <?php foreach($tags as $rowsTag): ?>
                    <a href="<?= base_url('tag/'.$rowsTag->slug_tag) ?>">
                        <span class="badge rounded-pill text-bg-secondary me-1"><?= $rowsTag->tag ?></span>
                    </a>
                <?php endforeach; ?>
                <div class="block-line"></div>
                <div class="share-section">
                    <h4>Bagikan tulisan</h4>
                    <div class="social-links d-flex justify-content-start">
                        <a href="javascript:void(0)" class="whatsapp me-1" onclick="myFunctionWA()"><i class="bi bi-whatsapp"></i></a>
                        <a href="javascript:void(0)" onclick="myFunctionFacebook()" class="facebook me-1"><i class="bi bi-facebook"></i></a>
                        <a href="javascript:void(0)"  onclick="myFunctionLinkedin()" class="linkedin me-1"><i class="bi bi-linkedin"></i></a>
                        <a href="javascript:void(0)" class="copy-link me-1" title="Copy Link" onclick="myFunction()"><i class="bi bi-link-45deg"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="galeri-artikel">
                    <div class="block-header">
                        <h3 class="block-title">
                            <a href="">Promo Kegiatan</a>
                        </h3>
                    </div>
                    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                      <div class="carousel-indicators">
                        <?php
                            $no=0;
                            foreach($iklan as $rowsIklan): 
                        ?>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?= $no ?>" class="<?= $no == '0'? 'active':'' ?>"></button>
                        <?php $no++; endforeach;  ?>
                      </div>
                      <div class="carousel-inner">
                        <?php
                            $no=0;
                            foreach($iklan as $rowsIklan): 
                            $no++;
                        ?>
                            <div class="carousel-item <?= $no == 1? 'active':'' ?>">
                                <a href="<?= $rowsIklan->url ?>">
                                      <?= img_lazy('uploads/iklan/thumbnails/'. $rowsIklan->file,"loading", ['class' => 'd-block w-100 zoom', 'width'=>'100%']) ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                      </div>
                      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                      </button>
                      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                      </button>
                    </div>
                </div>
                <div class="kategori-list-artikel">
                    <div class="block-header">
                        <h3 class="block-title">
                            <a href="">Kategori</a>
                        </h3>
                    </div>
                    <div class="list-kategori">
                        <ul>
                            <?php foreach($kategori as $rowsKategori): ?>
                                <a href="<?= base_url('kategori/'.$rowsKategori->slug_kategori) ?>">
                                    <li><?= $rowsKategori->kategori ?> (<?= $rowsKategori->total ?>)</li>
                                </a>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="rekomendasi-artikel">
                    <div class="block-header">
                        <h3 class="block-title">
                            <a href="">Rekomendasi</a>
                        </h3>
                    </div>
                    <?php foreach($artikelRekomendasi as $rowsRekomendasi): ?>
                        <div class="card-artikel">
                            <div class="img-artikel position-relative">
                                <?= img_lazy('uploads/artikel/thumbnails/'.$rowsRekomendasi->image_default,"loading", ['class' => 'zoom', 'height'=>'240px']) ?>
                                <div class="position-absolute bottom-0 start-0 kategori-artikel p-1 text-white"><?= $rowsRekomendasi->kategori ?></div>
                            </div>
                            <div class="">
                                <div>
                                    <a href="<?= base_url('artikel/'.$rowsRekomendasi->slug_judul) ?>">
                                        <h3  class="judul-artikel-bottom mt-2"><?= $rowsRekomendasi->judul?></h3>
                                    </a>
                                </div>
                                <div class="post-meta-wrapper d-flex justify-content-start mt-2">
            						<span class="posted-on-bottom">
            						    <i class="bi bi-calendar me-1"></i><?= date('d/m/Y', strtotime($rowsRekomendasi->created_at)) ?>
            						</span>
            						<span class="posted-on-bottom ms-4"> 
            						    <i class="bi bi-person me-2"></i><?= $rowsRekomendasi->nama_admin ?>
            						</span>
            						<span class="posted-on-bottom ms-4"> 
            						    <i class="bi bi-eye me-2"></i><?= $rowsRekomendasi->hit ?>
            						</span>
            					</div>
                                <div class="konten-artikel mt-1">
                                    <?= $rowsRekomendasi->konten ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    
    function myFunctionWA(){
         var slugWa = '<?= !empty($detail)? $detail->judul.' '. base_url('artikel/'.$detail->slug_judul) :'' ?>';
        window.location = "https://api.whatsapp.com/send/?text="+slugWa;
    }
    
    function myFunctionFacebook(){
         var slugFb = '<?= !empty($detail)? base_url('artikel/'.$detail->slug_judul) :'' ?>';
        window.location = "https://www.facebook.com/sharer/sharer.php?u="+slugFb;
    }
    
    function myFunctionLinkedin(){
         var slugLink = '<?= !empty($detail)? base_url('artikel/'.$detail->slug_judul): '' ?>';
        window.location = "https://www.linkedin.com/sharing/share-offsite/?url="+slugLink;
    }
    
    
    function myFunction() {
      // Get the text field
      var copyText = '<?= !empty($detail)? base_url('artikel/'.$detail->slug_judul) : '' ?>';

      navigator.clipboard.writeText(copyText);
    
      // Alert the copied text
      alert("Berhasil disalin: " + copyText);
    }
</script>
<?= $this->endSection(); ?>
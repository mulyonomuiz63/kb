<?= $this->extend('landing/template'); ?>
<?= $this->section('content'); ?>
<div class="section mb-4" id="">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8 mb-8">
                <div class="block-header">
                    <h3 class="block-title">
                        <a href="">Artikel Utama</a>
                    </h3>
                </div>
                
                <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                    <div class="carousel-indicators">
                        <?php
                            $no=0;
                            foreach($artikelUtamaUp as $rows): 
                        ?>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?= $no ?>" class="<?= $no == '0'? 'active':'' ?>"></button>
                        <?php $no++; endforeach;  ?>
                      </div>
                      <div class="carousel-inner">
                        <?php
                            $no=0;
                            foreach($artikelUtamaUp as $rows): 
                            $no++;
                        ?>
                            <div class="carousel-item <?= $no == 1? 'active':'' ?>">
                                <div class="img-artikel-utama position-relative">
                                   <?= img_lazy('uploads/artikel/thumbnails/'.$rows->image_default,"loading", ['class' => 'zoom', 'width'=>'100%']) ?>
                                   <div class="position-absolute bottom-0 start-0 kategori-artikel p-1 text-white"><?= $rows->kategori ?></div>
                                </div>
                                <div class="carousel-caption d-none d-md-block">
                                    <a  href="<?= base_url('artikel/'.$rows->slug_judul) ?>">
                                       <h3 class="judul-artikel-up mt-2"><?= $rows->judul ?></h3>
                                    </a>
                                    <!--<h5>First slide label</h5>-->
                                    <!--<p>Some representative placeholder content for the first slide.</p>-->
                                </div>
                            </div>
                        <?php endforeach; ?>
                      </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
      
                
                <div class="col-12">
                    <div class="row mt-2">
                        <?php foreach($artikelUtamaDown as $rows ): ?>
                            <div class="col-12 col-md-6 card-artikel">
                                <div class="img-artikel position-relative">
                                    <?= img_lazy('uploads/artikel/thumbnails/'.$rows->image_default,"loading", ['class' => 'zoom', 'height'=>'240']) ?>
                                    <div class="position-absolute bottom-0 start-0 kategori-artikel p-1 text-white"><?= $rows->kategori ?></div>
                                </div>
                                <div class="">
                                    <div>
                                        <a href="<?= base_url('artikel/'.$rows->slug_judul) ?>">
                                            <h3  class="judul-artikel-bottom mt-2"><?= $rows->judul ?></h3>
                                        </a>
                                    </div>
                                    <div class="post-meta-wrapper d-flex justify-content-start mt-2">
            							<span class="posted-on-bottom">
            							    <i class="bi bi-calendar me-1"></i><?= date('d/m/Y', strtotime($rows->created_at)) ?>
            							</span>
            							<span class="posted-on-bottom ms-4"> 
            							    <i class="bi bi-person me-2"></i><?= $rows->nama_admin ?>
            							</span>	
            							<span class="posted-on-bottom ms-4"> 
            							    <i class="bi bi-eye me-2"></i><?= $rows->hit ?>
            							</span>	
            						</div>
                                    <div class="konten-artikel mt-1">
                                        <?= $rows->konten ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="block-header">
                    <h3 class="block-title">
                        <a href="">Semua Artikel</a>
                    </h3>
                </div>
                <div class="col-12">
                    <div class="row">
                        <?php foreach($artikel as $rowsAll): ?>
                            <div class="col-12 col-md-6 mt-2 card-artikel">
                                <div class="img-artikel position-relative"> 
                                    <?= img_lazy('uploads/artikel/thumbnails/'.$rowsAll['image_default'],"loading", ['class' => 'zoom', 'height'=>'240']) ?>
                                    <div class="position-absolute bottom-0 start-0 kategori-artikel p-1 text-white"><?= $rowsAll['kategori'] ?></div>
                                </div>
                                <div class="">
                                    <div>
                                        <a href="<?= base_url('artikel/'.$rowsAll['slug_judul']) ?>">
                                            <h3  class="judul-artikel-bottom mt-2"><?= $rowsAll['judul'] ?></h3>
                                        </a>
                                    </div>
                                    <div class="post-meta-wrapper d-flex justify-content-start mt-2">
            							<span class="posted-on-bottom">
            							    <i class="bi bi-calendar me-1"></i><?= date('d/m/Y', strtotime($rowsAll['created_at'])) ?>
            							</span>
            							<span class="posted-on-bottom ms-4"> 
            							    <i class="bi bi-person me-2"></i><?= $rowsAll['nama_admin'] ?>
            							</span>	
            							<span class="posted-on-bottom ms-4"> 
            							    <i class="bi bi-eye me-2"></i><?= $rowsAll['hit'] ?>
            							</span>
            						</div>
                                    <div class="konten-artikel mt-1" style="font-size:10px">
                                        <?= $rowsAll['konten'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-12 float-sm-end mt-4"> 
                    <?php echo $pager->links('default', 'custom_pager') ?>
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
                                  <?= img_lazy('uploads/iklan/thumbnails/'.$rowsIklan->file,"loading", ['class' => 'd-block w-100 zoom', 'width'=>'100%']) ?>
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
                            <div class="img-artikel position-relative mt-4">
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
</script>
<?= $this->endSection(); ?>
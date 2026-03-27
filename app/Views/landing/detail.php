<?= $this->extend('landing/template'); ?>
<?= $this->section('css') ?>
<style>
/* =====================
   GLOBAL SMOOTH UI
===================== */
* {
  -webkit-tap-highlight-color: transparent;
}
/* ===== AFFILIATE ===== */
.affiliate-box{
    background:linear-gradient(135deg,#f8fbff,#eef4ff);
    border:1px dashed #c9d9ff;
    border-radius:10px;
    font-size:12px;
    animation:fadeUp .5s ease;
}
.product-card {
  background: linear-gradient(180deg,#ffffff,#f9fbff);
  border-radius: 16px;
  box-shadow: 0 10px 35px rgba(30,60,114,.08);
  transition: all .35s ease;
  position: relative;
  overflow: hidden;
  padding: 24px;
}

.product-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 18px 45px rgba(30,60,114,.15);
}

/* =====================
   IMAGE EFFECT
===================== */
.product-card img {
  border-radius: 14px;
  transition: transform .5s ease;
}

.product-card:hover img {
  transform: scale(1.04);
}

/* =====================
   TITLE
===================== */
.product-card h5 {
  font-size: 1.2rem;
  line-height: 1.4;
}

/* =====================
   PRICE
===================== */
.price-detail {
  font-size: 1.35rem;
  font-weight: 800;
  color: #1A4FF0;
}

.old-price {
  font-size: .85rem;
  color: #999;
  text-decoration: line-through;
}

.discount {
  background: linear-gradient(135deg,#ff4d4d,#d0011b);
  color: #fff;
  font-size: .75rem;
  padding: 4px 10px;
  border-radius: 999px;
  font-weight: 600;
}

/* =====================
   BUTTON
===================== */
.btn-buy {
  background: linear-gradient(135deg,#1A4FF0,#2d6bff);
  color: #fff;
  border-radius: 12px;
  border: none;
  font-weight: 600;
  transition: all .35s ease;
  padding: 12px 16px;
  letter-spacing: .3px;
  font-size: 14px;
}

.btn-buy:hover {
  background: linear-gradient(135deg,#d0011b,#ff4d4d);
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(208,1,27,.35);
}

/* =====================
   REVIEW SUMMARY
===================== */
.review-avatar {
  width: 42px;
  height: 42px;
  object-fit: cover;
  border: 2px solid #e6ebff;
}

.review-item {
  background: #ffffff;
  transition: all .3s ease;
  border-radius: 14px;
}

.review-item:hover {
  transform: translateY(-6px);
  box-shadow: 0 15px 30px rgba(0,0,0,.08);
}

/* =====================
   PROGRESS BAR
===================== */
.progress {
  height: 8px;
  border-radius: 999px;
  background: #edf1ff;
}

.progress-bar {
  border-radius: 999px;
  transition: width .6s ease;
}

/* =====================
   SCROLL REVIEW
===================== */
.review-scroll {
  overflow-x: auto;
  scrollbar-width: thin;
  scrollbar-color: #cfd8ff transparent;
}

.review-scroll::-webkit-scrollbar {
  height: 6px;
}

.review-scroll::-webkit-scrollbar-thumb {
  background: #cfd8ff;
  border-radius: 999px;
}

/* =====================
   DESKRIPSI
===================== */
.product-card h2 {
  font-size: 1.4rem;
  margin-bottom: 15px;
}

.product-card p {
  line-height: 1.7;
}

/* =====================
   ANIMATION ON LOAD
===================== */
.product-card {
  animation: fadeUp .7s ease both;
}

@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* =====================
   RESPONSIVE
===================== */
@media (max-width: 768px) {
  .product-card {
    padding: 18px;
  }

  .price-detail {
    font-size: 1.2rem;
  }

  .btn-buy {
    font-size: .9rem;
    padding: 10px 14px;
  }
}

.btn-buy-copy{
    background:#DCDCDC;
    color: #212121;
    border-radius:10px;
    border:none;
    padding:0px 15px;
    font-weight:600;
    transition:.3s ease;
}
.btn-buy-copy:hover{
    background:#d0011b;
    color: #fff;
    transform:scale(1.05);
}

.btn-buy-wa{
    background:#90EE90;
    color: #fff;
    border-radius:10px;
    border:none;
    padding:0px 15px;
    font-weight:600;
    transition:.3s ease;
}
.btn-buy-wa:hover{
    background:#d0011b;
    color: #fff;
    transform:scale(1.05);
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content'); ?>
<div class="container">
  <div class="product-card row g-4 my-4">
    <!-- Gambar Produk -->
    <div class="col-md-5 text-center">
       <?= img_lazy('assets-landing/images/paket/thumbnails/'.$paket->file, $paket->nama_paket, ['class' => 'card-img-top']) ?>
    </div>

    <!-- Detail Produk -->
    <div class="col-md-7">
      <h5 class="fw-bold"><?= $paket->nama_paket ?></h5>
      <div class="text-warning mb-2" style="font-size:12px">
            <?php if($rataRating > 0): ?>
                <span class="text-dark"><?= $rataRating ?><span> <?= showStars($rataRating) ?> <span class="text-dark">(<?= $totalRating + 325 ?>)</span>
            <?php endif; ?>
      </div>
      <?php
          $soal = $db->query("SELECT a.id_ujian, b.kode_ujian FROM detail_paket a join ujian_master b on a.id_ujian=b.id_ujian where a.idpaket = '$paket->idpaket' group by a.id_ujian")->getResult();
          $durasi = 0;
          foreach($soal as $r):
              $total = 0;
              $ujianDetail = $db->query("select * from ujian_detail where kode_ujian = '$r->kode_ujian'")->getResult();
              foreach($ujianDetail as $dataRows){
                  $total++;
              }
              $jml = $db->query("select count(kode_ujian) as total_soal from ujian_detail where kode_ujian = '$r->kode_ujian'")->getRow();
              
              $totalMenit = $total * 3;
              $start =  (date('Y-m-d H:i'));
              $end_ = (date('Y-m-d H:i', strtotime("+ $totalMenit minutes")));

              
              $start_ujian = date_create($start);
              $end_ujian = date_create($end_);
              $durasi = date_diff($start_ujian, $end_ujian);
          endforeach;
          
      ?>
      <div class="d-flex align-items-center gap-2 mb-2">
        <span class="price-detail">Rp. <?= number_format($paket->nominal_paket - (($paket->nominal_paket*$paket->diskon)/100))  ?></span>
        <span class="old-price">Rp. <?= number_format($paket->nominal_paket) ?></span>
        <span class="discount"><?= $paket->diskon ?> %</span>
      </div>

      <p class="small text-muted mb-2">Semua transaksi pembelian paket brevet dilakukan melalui kelasbrevet.com.</p>

      <div class="mb-3">
        <label class="fw-semibold">Paket Tersedia</label>
      </div>
        <?php if(session()->get('id') && !empty($affiliate)): ?>
            <div class="affiliate-box p-2 mb-3">
                💰 <strong class="text-warning">Komisi <?= $paket->komisi ?>%</strong>
                <div class="text-muted small">
                    Dari setiap pembelian via link kamu
                </div>
            </div>
        <?php endif; ?>
        <div class="d-flex gap-2 mt-3">
            <a href="<?= base_url('transaksi/pesan/'.encrypt_url($paket->idpaket).'/'.$kdvoucher) ?>" class="btn-buy badge text-center flex-fill">Pesan Sekarang</a>
            <!--badge text-bg-primary d-flex justify-content-center btn-hover-dark p-2-->
            <?php if(session()->get('id')): ?>
                <?php if(!empty($affiliate)): ?>
                     <button class="btn-buy-copy btn-sm mx-1 btn-copy-link" data-paket_id="<?= $paket->idpaket ?>">
                        <i class="fa fa-copy"></i>
                    </button>
                    <button class="btn-buy-wa btn-sm  share-link" data-paket_id="<?= $paket->idpaket ?>">
                        <i class="fab fa-whatsapp"></i>
                    </button>
                    
                    <!-- iOS clipboard helper -->
                    <input type="text" id="clipboard-temp" style="position:fixed;top:-1000px;opacity:0;">

                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
  </div>
</div>

<div class="container">
    <div class="product-card my-4">
        <h2 class="text-left fz-6">Deskripsi Produk</h2>
        <?= $paket->deskripsi ?>
            
    </div>
</div>

<!-- ======= REVIEW & RATING SECTION ======= -->
<?php if($rataRating > 0): ?>
    <div class="container">
        <div class="product-card my-4 row g-4">
            <!-- Kolom Rating Ringkasan -->
            <div class="col-md-4 text-center border-end">
              <h5 class="fw-bold mb-1">Penilaian Paket Pelatihan</h5>
              <h1 class="display-4 fw-bold text-warning mb-0"><?= $rataRating ?: 0 ?></h1>
              <div class="text-warning mb-2">
                <?= showStars($rataRating) ?>
              </div>
              <p class="text-muted"><?= $totalRating + 325 ?> Ulasan</p>
            </div>
        
            <!-- Kolom Distribusi Bintang -->
            <div class="col-md-4">
              <h6 class="fw-semibold mb-3">Distribusi Rating</h6>
              <?php
                // Hitung jumlah rating per bintang (5→1)
                $ratings = $db->query("
                    SELECT rating, COUNT(*) AS total
                    FROM review_ujian d
                    JOIN ujian_master c ON c.kode_ujian=d.kode_ujian
                    JOIN detail_paket b ON b.id_ujian=c.id_ujian
                    JOIN paket p ON p.idpaket=b.idpaket
                    WHERE p.slug = '$paket->slug'
                    GROUP BY rating
                    ORDER BY rating DESC
                ")->getResult();
        
                // Siapkan array total per rating
                $ratingCount = [5=>0,4=>0,3=>0,2=>0,1=>0];
                $totalAll = 0;
                foreach($ratings as $r){
                    $ratingCount[$r->rating] = $r->total;
                    $totalAll += $r->total;
                }
              ?>
        
              <?php foreach(range(5,1) as $star): 
                $percent = $totalAll > 0 ? round(($ratingCount[$star]/$totalAll)*100) : 0;
              ?>
              <div class="d-flex align-items-center mb-2">
                <div class="me-2" style="width:30px;"><?= $star ?>★</div>
                <div class="progress flex-fill" style="height:8px;">
                  <div class="progress-bar bg-warning" style="width: <?= $percent ?>%;"></div>
                </div>
                <div class="ms-2 small text-muted"><?= $percent ?>%</div>
              </div>
              <?php endforeach; ?>
            </div>
        
            <!-- Kolom Review Terbaru -->
            <div class="col-md-4">
              <h6 class="fw-semibold mb-3">Ulasan Terbaru</h6>
              <?php
                $reviews = $db->query("
                  SELECT
                    q.nama_siswa,
                    q.avatar,
                    d.komentar,
                    d.rating,
                    d.created_at
                FROM review_ujian d
                JOIN ujian_master c ON c.kode_ujian = d.kode_ujian
                JOIN detail_paket b ON b.id_ujian = c.id_ujian
                JOIN paket p ON p.idpaket = b.idpaket
                JOIN siswa q ON q.id_siswa = d.id_siswa
                WHERE p.slug = '$paket->slug'
                  AND d.status = 'A'
                  AND d.created_at = (
                      SELECT MAX(d2.created_at)
                      FROM review_ujian d2
                      JOIN ujian_master c2 ON c2.kode_ujian = d2.kode_ujian
                      JOIN detail_paket b2 ON b2.id_ujian = c2.id_ujian
                      JOIN paket p2 ON p2.idpaket = b2.idpaket
                      WHERE p2.slug = '$paket->slug'
                        AND d2.status = 'A'
                        AND d2.id_siswa = d.id_siswa
                  )
                ORDER BY d.created_at DESC limit 10;
                ")->getResult();
              ?>
            
              <?php if($reviews): ?>
                <div class="review-scroll d-flex gap-3 pb-2">
                  <?php foreach($reviews as $rv): ?>
                    <div class="review-item flex-shrink-0 p-3 shadow-sm rounded-3" style="min-width: 230px; max-width: 230px;">
                      <div class="d-flex align-items-center mb-2">
                        <?php 
                          $foto = !empty($rv->avatar) 
                                  ? base_url('assets/app-assets/user/'.$rv->avatar) 
                                  : base_url('assets/app-assets/user/default.png');
                        ?>
                        <img src="<?= $foto ?>" alt="foto <?= esc($rv->nama_siswa ?? 'Pengguna') ?>"
                             class="rounded-circle me-2 review-avatar">
                        <div>
                          <div class="fw-semibold small"><?= esc($rv->nama_siswa ?? 'Pengguna') ?></div>
                          <div class="text-warning small"><?= showStars($rv->rating) ?></div>
                        </div>
                      </div>
                      <p class="small text-muted mb-2">“<?= esc($rv->komentar) ?>”</p>
                      <small class="font-weight-bold text-secondary"><?= date('d M Y', strtotime($rv->created_at)) ?></small>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php else: ?>
                <p class="text-muted">Belum ada ulasan untuk paket pelatihan ini.</p>
              <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif ?>
<?= $this->endSection(); ?>
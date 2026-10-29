<?= $this->extend('landing/template'); ?>

<?= $this->section('css') ?>
<style>
  /* =====================
   GLOBAL & CARD STYLING
  ===================== */
  * { -webkit-tap-highlight-color: transparent; }

  .product-card {
    background: linear-gradient(180deg, #ffffff, #f9fbff);
    border-radius: 16px;
    box-shadow: 0 10px 35px rgba(30, 60, 114, .08);
    transition: all .35s ease;
    position: relative;
    overflow: hidden;
    padding: 30px;
  }

  .product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 40px rgba(30, 60, 114, .12);
  }

  .img-wrapper {
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
  }
  
  .img-wrapper img {
    transition: transform .5s ease;
    width: 100%;
    object-fit: cover;
  }

  .product-card:hover img { transform: scale(1.03); }

  /* =====================
   TYPOGRAPHY & PRICE
  ===================== */
  .tryout-subtitle {
    font-size: 0.9rem;
    color: #29459A;
    font-weight: 600;
    letter-spacing: 0.5px;
  }

  .price-detail {
    font-size: 1.6rem;
    font-weight: 800;
    color: #29459A;
  }
  
  .price-free {
    color: #198754; 
    font-size: 1.8rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  .old-price {
    font-size: 0.9rem;
    color: #999;
    text-decoration: line-through;
  }

  .discount-badge {
    background: linear-gradient(135deg, #ff4d4d, #d0011b);
    color: #fff;
    font-size: .75rem;
    padding: 4px 10px;
    border-radius: 8px;
    font-weight: 600;
  }

  /* =====================
   BUTTONS
  ===================== */
  .btn-buy {
    background: linear-gradient(135deg, #29459A, #1e3376);
    color: #fff;
    border-radius: 10px;
    border: none;
    font-weight: 600;
    transition: all .3s ease;
    padding: 12px 20px;
    text-decoration: none;
    display: inline-block;
  }

  .btn-buy:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(41, 69, 154, .3);
    color: #fff;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .product-card { padding: 20px; }
    .price-detail { font-size: 1.4rem; }
    .price-free { font-size: 1.5rem; }
  }
</style>
<?= $this->endSection() ?>


<?= $this->section('content'); ?>

<?php 
  // Cek apakah paket ini Gratis (Rp 0)
  $is_free = (isset($paket->nominal_paket) && ($paket->nominal_paket == 0 || $paket->nominal_paket == '0')); 
?>

<div class="container my-5">
  
  <!-- ========================================== -->
  <!-- 1. BAGIAN ATAS: CARD PRODUK UTAMA          -->
  <!-- ========================================== -->
  <div class="product-card row g-4 align-items-center">
    <!-- Gambar Produk -->
    <div class="col-md-5 text-center">
      <div class="img-wrapper position-relative">
        <?php if($is_free): ?>
            <span class="position-absolute top-0 start-0 m-3 badge bg-success fs-6 shadow-sm"><i class="fa fa-gift me-1"></i> GRATIS</span>
        <?php endif; ?>
        <!-- Ganti src default ini dengan img_lazy milik Anda -->
        <img src="<?= base_url('assets-landing/images/paket/thumbnails/' . ($paket->file ?? 'default.png')) ?>" alt="<?= $paket->nama_paket ?? 'Try Out UKP' ?>" class="img-fluid">
      </div>
    </div>

    <!-- Detail Produk -->
    <div class="col-md-7 ps-md-4">
      <span class="tryout-subtitle text-uppercase mb-2 d-block"><i class="fa fa-graduation-cap me-1"></i> Simulasi Ujian Kompetensi Perpajakan</span>
      
      <h2 class="fw-bolder text-dark mb-3"><?= $paket->nama_paket ?? 'Try Out Ujian Kompetensi Perpajakan (UKP)' ?></h2>
      
      <!-- Area Harga -->
      <div class="d-flex flex-wrap align-items-center gap-3 mb-3 bg-light rounded-3 p-3 border">
        <?php if($is_free): ?>
            <span class="price-free mb-0">GRATIS</span>
        <?php else: ?>
            <?php 
              $harga_diskon = $paket->nominal_paket - (($paket->nominal_paket * $paket->diskon) / 100); 
            ?>
            <span class="price-detail mb-0">Rp <?= number_format($harga_diskon, 0, ',', '.') ?></span>
            <div class="d-flex flex-column justify-content-center">
              <?php if($paket->diskon > 0): ?>
                <span class="discount-badge mb-1 text-center">Hemat <?= $paket->diskon ?>%</span>
                <span class="old-price">Rp <?= number_format($paket->nominal_paket, 0, ',', '.') ?></span>
              <?php endif; ?>
            </div>
        <?php endif; ?>
      </div>

      <p class="text-muted mb-4 fs-7" style="line-height: 1.6;">
        Persiapkan diri Anda menghadapi Ujian Kompetensi Perpajakan dengan simulasi yang dirancang khusus menyerupai ujian aslinya. Dapatkan evaluasi langsung dan pembahasan mendalam.
      </p>

      <!-- Tombol Aksi -->
      <div class="d-flex gap-2 mt-2">
        <a href="<?= base_url('sw-siswa/transaksi/pesan/' . encrypt_url($paket->idpaket ?? '')) ?>" class="btn-buy text-center flex-fill">
          <?= $is_free ? '<i class="fa fa-pencil-alt me-2"></i> Mulai Try Out Sekarang' : '<i class="fa fa-shopping-cart me-2"></i> Pesan Sekarang' ?>
        </a>
      </div>
    </div>
  </div>

  <!-- ========================================== -->
  <!-- 2. BAGIAN BAWAH: DESKRIPSI & KEUNGGULAN    -->
  <!-- ========================================== -->
  <div class="row g-4 g-lg-5 align-items-stretch mt-3">
    <!-- Kiri: Deskripsi -->
    <div class="col-lg-7 col-md-12">
      <div class="card border-0 shadow-sm rounded-4 h-100 p-4 p-md-5 bg-white">
        <div class="card-body p-0">
          <h3 class="fw-bolder text-dark mb-4 pb-3 border-bottom fs-4">
            Deskripsi Try Out
          </h3>
          <div class="text-gray-700 fs-6 lh-lg" style="text-align: justify;">
             <?= !empty($paket->deskripsi) ? $paket->deskripsi : '<p>Try Out Ujian Kompetensi Perpajakan (UKP) ini dirancang khusus bagi Anda yang ingin mengukur kemampuan dan kesiapan sebelum menghadapi ujian sertifikasi konsultan pajak. Simulasi ini disusun oleh para ahli praktisi pajak dengan mengacu pada standar soal UKP terbaru.</p><p>Dengan mengikuti simulasi ini, Anda tidak hanya mengetahui seberapa jauh pemahaman materi Anda, tetapi juga melatih manajemen waktu saat mengerjakan soal-soal tingkat tinggi.</p>' ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Kanan: Keunggulan (Diadaptasi dari lafat.id) -->
    <div class="col-lg-5 col-md-12">
      <div class="position-sticky" style="top: 2rem;">
        <div class="card border-0 p-4 p-md-5 rounded-4 shadow-sm" style="background-color: #f4f7fc; border: 2px dashed #29459A !important;">
          <div class="card-body p-0">
            <h4 class="fw-bolder mb-4 d-flex align-items-center" style="color: #29459A;">
              <i class="fa fa-star fs-4 me-3"></i> Keunggulan Try Out
            </h4>

            <!-- Poin Keunggulan sesuai referensi Lafat.id -->
            <ul class="list-unstyled d-flex flex-column gap-3 m-0">
              <li class="d-flex align-items-center p-3 rounded-3 bg-white shadow-sm border border-light">
                <i class="fa fa-check-circle text-success me-3 fs-4"></i>
                <span class="text-dark fw-semibold fs-6">100 Soal per Paket Ujian</span>
              </li>
              <li class="d-flex align-items-center p-3 rounded-3 bg-white shadow-sm border border-light">
                <i class="fa fa-check-circle text-success me-3 fs-4"></i>
                <span class="text-dark fw-semibold fs-6">Nilai Langsung Keluar (Real-time)</span>
              </li>
              <li class="d-flex align-items-center p-3 rounded-3 bg-white shadow-sm border border-light">
                <i class="fa fa-check-circle text-success me-3 fs-4"></i>
                <span class="text-dark fw-semibold fs-6">Tersedia Pembahasan & Dasar Hukum</span>
              </li>
              <li class="d-flex align-items-center p-3 rounded-3 bg-white shadow-sm border border-light">
                <i class="fa fa-check-circle text-success me-3 fs-4"></i>
                <span class="text-dark fw-semibold fs-6">Mencakup UKP Tingkat A, B, & C</span>
              </li>
              <li class="d-flex align-items-center p-3 rounded-3 bg-white shadow-sm border border-light">
                <i class="fa fa-check-circle text-success me-3 fs-4"></i>
                <span class="text-dark fw-semibold fs-6">Simulasi Waktu Mirip Ujian Asli</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<?= $this->endSection(); ?>
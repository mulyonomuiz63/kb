<?= $this->extend('landing/template'); ?>
<?= $this->section('css') ?>
<style>
    .text {
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 2; /* tampil 4 baris dulu */
      -webkit-box-orient: vertical;
    }

    .text.expanded {
      -webkit-line-clamp: unset;
    }
    .toggle-btn {
      color: #007bff;
      cursor: pointer;
      font-size: 14px;
      margin-top: 5px;
      display: inline-block;
    }
    .circle-img {
      width: 150px;          /* ukuran lingkaran */
      height: 150px;         /* harus sama dengan width agar bulat */
      border: 3px solid #CCCCCC;/* border putih */
      border-radius: 100%;    /* biar jadi lingkaran */
      object-fit: cover;     /* gambar terpotong tapi proporsional */
      object-position: center;/* posisi gambar di tengah */
    }
    
    .user-info img {
      width: 90px !important;
      height: 90px !important;
      border-radius: 50% !important;
      object-fit: cover !important;
      /* ↓ Atur posisi wajah agar kepala tidak terpotong */
      object-position: center top !important;
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('content'); ?>
<!-- Testimonial End -->
        <div class="section section-padding-02 mt-n1 mb-2" id="testimoni">
            <div class="container">

                <!-- Section Title Start -->
                <div class="section-title text-center">
                    <h5 class="sub-title">Testimoni Alumni</h5>
                </div>
                <!-- Section Title End -->

                <!-- Testimonial Wrapper End -->
                <div class="testimonial-active">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <!-- Single Testimonial Start -->
                            <?php
                            $data_testi = $db->query("SELECT testimoni.*, siswa.nama_siswa, siswa.kota_intansi, siswa.avatar FROM testimoni join siswa on testimoni.idsiswa=siswa.id_siswa order by idtestimoni desc")->getResult();
                            foreach ($data_testi as $rows) {
                            ?>
                                <div class="single-testimonial swiper-slide">
                                    <div class="testimonial-author">
                                        <div class="user-info position-relative mb-4">
                                            <?= img_lazy('assets/app-assets/user/' . $rows->avatar,"loading", ['class' => 'circle-img']) ?>

                                            <i class="icofont-quote-left position-absolute top-100 start-50 translate-middle text-primary fs-1"></i>
                                        </div>
                                        <h4 class="name"><?= $rows->nama_siswa; ?></h4>
                                        <span class="designation"><?= $rows->kota_intansi; ?></span>
                                    </div>
                                    <div class="testimonial-content text">

                                        <p><?= $rows->keterangan; ?></p>
                                    </div>
                                    <span class="toggle-btn">Add more</span>
                                </div>
                            <?php } ?>
                            <!-- Single Testimonial End -->


                        </div>

                        <!-- Add Pagination -->
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
                <!-- Testimonial Wrapper End -->

            </div>
        </div>
        <!-- Testimonial End -->
<script>
    document.querySelectorAll(".single-testimonial").forEach(card => {
      const text = card.querySelector(".text");
      const btn = card.querySelector(".toggle-btn");

      const lineHeight = parseInt(window.getComputedStyle(text).lineHeight, 10);
      const maxHeight = lineHeight * 2; // clamp 4 baris

      // Cek apakah teks lebih tinggi dari batas
      if (text.scrollHeight <= maxHeight) {
        btn.style.display = "none"; // sembunyikan tombol kalau teks pendek
      }

      btn.addEventListener("click", () => {
        text.classList.toggle("expanded");
        btn.textContent = text.classList.contains("expanded") ? "Show less" : "Add more";
      });
    });
</script>
<?= $this->endSection(); ?>
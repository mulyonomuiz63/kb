<?= $this->extend('landing/template'); ?>
<?= $this->section('css'); ?>
<style>
    .insta-card {
      margin-bottom: 20px;
    }
    .instagram-media {
        border: none !important;
        box-shadow: none !important;
        width: 100% !important;
        min-width: auto !important;
        max-width: 100% !important;
    }
    /* Loading skeleton */
    .ig-placeholder {
        width: 100%;
        padding-top: 120%; /* aspect ratio kotak (ubah sesuai kebutuhan) */
        background: linear-gradient(90deg, #f0f0f0 25%, #e6e6e6 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 8px;
    }
    
    /* Animasi shimmer */
    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
    }
    
    /* Saat embed sudah load, sembunyikan placeholder */
    .ig-wrapper.loaded .ig-placeholder {
        display: none;
    }
</style>
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>
<div class="section mb-4 " id="">
    <div class="container">
        <div class="photo-gallery">
            <div class="row siap-kerja ig-wrapper">
                <?php foreach ($data["galeri"] as $post): ?>
                    <div class="col-md-4 insta-card">
                        <blockquote class="instagram-media" data-instgrm-permalink="<?= $post["kode"] ?>" data-instgrm-version="14"></blockquote>
                        <div class="ig-placeholder"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<script async src="//www.instagram.com/embed.js"></script>
<script>
  // Tunggu embed Instagram selesai dirender
  window.addEventListener("load", function () {
    // Cari semua wrapper
    document.querySelectorAll(".ig-wrapper").forEach(wrapper => {
      // Tambahkan kelas 'loaded'
      wrapper.classList.add("loaded");
    });
  });
</script>
<?= $this->endSection(); ?>
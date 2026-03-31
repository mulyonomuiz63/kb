<?= $this->extend('siswa/template/app'); ?>
<?= $this->section('styles'); ?>
<style>
    /* Animasi Masuk (Fade Up) */
    .animate-card {
        animation: cardFadeIn 0.6s ease backwards;
    }

    @keyframes cardFadeIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Hover Effect pada Card */
    .hover-elevate-up {
        transition: all 0.3s ease-in-out;
    }

    .hover-elevate-up:hover {
        transform: translateY(-10px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.1) !important;
    }

    /* Zoom Effect pada Image */
    .img-zoom-container {
        overflow: hidden;
        border-radius: 0.75rem 0.75rem 0 0;
    }

    .img-zoom-container img {
        transition: transform 0.5s ease;
    }

    .hover-elevate-up:hover .img-zoom-container img {
        transform: scale(1.1);
    }

    /* Glassmorphism Badge */
    .badge-glass {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    /* Button Glow Effect */
    .btn-glow-primary:hover {
        box-shadow: 0 0 15px rgba(26, 79, 240, 0.4);
    }
</style>
<style>
    /* ... kode CSS Anda yang sudah ada ... */

    /* Animasi denyut halus untuk gambar agar tidak kaku */
    .pulse-animation {
        animation: pulseCustom 3s infinite ease-in-out;
    }

    @keyframes pulseCustom {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.03);
        }

        100% {
            transform: scale(1);
        }
    }

    /* Batasan lebar teks agar tidak terlalu panjang di layar lebar */
    .max-w-600px {
        max-width: 600px;
    }

    /* Transisi halus untuk tombol */
    .btn {
        transition: all 0.2s ease;
    }
</style>
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>

<?php $db = Config\Database::connect(); ?>


<div class="d-flex flex-column flex-column-fluid py-4 py-lg-6 mt-8">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="row g-7">
                <?php if (!empty($modul)): ?>
                    <?php $delay = 0; ?>
                    <?php foreach ($modul as $m) : ?>
                        <?php
                        $jml_materi = $db->query("select count(*) as total_materi from materi where mapel = '$m->id_mapel'")->getRow();
                        $delay += 0.1; // Memberikan efek loading berurutan
                        ?>

                        <div class="col-md-6 col-xl-4 animate-card" style="animation-delay: <?= $delay ?>s">
                            <div class="card border-0 shadow-sm h-100 hover-elevate-up">

                                <div class="position-relative img-zoom-container" style="aspect-ratio: 16/9;">
                                    <?= img_lazy('uploads/mapel/' . $m->file, "loading", ['class' => 'w-100 h-100 object-fit-cover']) ?>

                                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-to-t from-dark opacity-25"></div>

                                    <?php if ($m->status == 0): ?>
                                        <div class="position-absolute top-0 end-0 p-4">
                                            <span class="badge badge-glass fs-8 fw-bold px-4 py-2">
                                                <i class="ki-outline ki-calendar-tick fs-8 me-1 text-white"></i> COMING SOON
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="card-body p-7">
                                    <div class="d-flex align-items-center mb-5">
                                        <div class="symbol symbol-40px symbol-circle me-3 shadow-sm border border-2 border-white">
                                            <img src="<?= base_url('assets-landing/images/logo-blue.png') ?>" alt="Akuntanmu">
                                        </div>
                                        <div class="d-flex flex-column align-items-start">
                                            <span class="text-dark fw-bolder fs-6">Akuntanmu Center</span>
                                            <span class="badge badge-light-primary fw-bold fs-9 mt-1">Verified Mentor</span>
                                        </div>
                                    </div>

                                    <h3 class="text-dark fw-boldest fs-4 mb-3 lh-base min-h-50px text-hover-primary transition-3ms">
                                        <?= $m->nama_mapel ?>
                                    </h3>

                                    <div class="separator separator-dashed my-4"></div>

                                    <div class="d-flex flex-stack">
                                        <div class="d-flex align-items-center me-3">
                                            <div class="bg-light-info rounded p-2 me-2">
                                                <i class="ki-outline ki-book-open fs-4 text-info"></i>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="text-dark fw-bold fs-7"><?= $jml_materi->total_materi ?> Sesi</span>
                                                <span class="text-muted fw-semibold fs-9">Video Materi</span>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center">
                                            <div class="bg-light-warning rounded p-2 me-2">
                                                <i class="ki-outline ki-time fs-4 text-warning"></i>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="text-dark fw-bold fs-7">180 Menit</span>
                                                <span class="text-muted fw-semibold fs-9">Durasi Total</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-8">
                                        <?php if ($m->status == 0): ?>
                                            <button class="btn btn-light-danger w-100 fs-7 fw-bold py-3 disabled">
                                                <i class="ki-outline ki-lock fs-5 me-2"></i> Belum Tersedia
                                            </button>
                                        <?php else: ?>
                                            <a href="<?= base_url('sw-siswa/materi/lihat-materi/' . encrypt_url($m->kode_materi) . '/' . encrypt_url($m->id_mapel) . '/' . encrypt_url($m->id_kelas)) ?>"
                                                class="btn btn-primary btn-glow-primary w-100 fs-7 fw-bold py-3 shadow-sm">
                                                Mulai Belajar <i class="ki-outline ki-arrow-right fs-5 ms-2"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 animate-card">
                        <div class="card border-0 shadow-none bg-transparent">
                            <div class="card-body d-flex flex-column flex-center py-20">
                                <div class="mb-10 text-center">
                                    <img src="<?= base_url('assets/peserta/media/illustrations/sigma-1/5.png') ?>"
                                        class="mw-350px mw-lg-450px mb-10 pulse-animation"
                                        alt="Empty State">
                                </div>

                                <div class="text-center">
                                    <h2 class="fw-boldest text-dark mb-4 fs-1">Mulai Perjalanan Belajarmu!</h2>
                                    <p class="text-gray-500 fs-5 fw-semibold mb-10 max-w-600px mx-auto">
                                        Anda belum memiliki materi aktif untuk dipelajari.<br>
                                        Tingkatkan kompetensi Anda dengan pilihan paket Brevet terbaik kami dan jadilah ahli pajak profesional sekarang juga.
                                    </p>
                                    <div class="d-flex flex-center gap-4">
                                        <a href="<?= base_url('list-bimbel') ?>" class="btn btn-primary px-8 fw-bold">
                                            <i class="ki-outline ki-shopping-cart fs-4 me-2"></i> Jelajahi Paket Materi
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<script>
    // Inisialisasi tooltip Metronic jika diperlukan
    $(document).ready(function() {
        if (typeof KTComponents !== 'undefined') {
            KTComponents.init();
        }
    });
</script>

<?= $this->endSection(); ?>
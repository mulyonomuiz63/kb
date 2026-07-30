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

    .pulse-animation {
        animation: pulseCustom 3s infinite ease-in-out;
    }

    @keyframes pulseCustom {
        0% { transform: scale(1); }
        50% { transform: scale(1.03); }
        100% { transform: scale(1); }
    }

    .max-w-600px { max-width: 600px; }
    .btn { transition: all 0.2s ease; }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid py-4 py-lg-6 mt-8">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="row g-7">
                <?php if (!empty($webinar)): ?>
                    <?php 
                    $delay = 0; 
                    $currentDateTime = strtotime(date('Y-m-d H:i:s'));
                    ?>
                    <?php foreach ($webinar as $w) : ?>
                        <?php
                        $delay += 0.1;
                        $waktuMulai = strtotime($w->waktu_mulai);
                        $waktuSelesai = strtotime($w->waktu_selesai);
                        
                        // 1. Menentukan Status Sesi Zoom
                        if ($currentDateTime < $waktuMulai) {
                            $status = 'upcoming';
                            $badgeColor = 'badge-light-warning';
                            $badgeText = 'Akan Datang';
                            $icon = 'ki-calendar-tick';
                        } elseif ($currentDateTime >= $waktuMulai && $currentDateTime <= $waktuSelesai) {
                            $status = 'live';
                            $badgeColor = 'bg-danger text-white pulse-animation';
                            $badgeText = 'Sedang Live';
                            $icon = 'ki-bi-camera-video-fill';
                        } else {
                            $status = 'finished';
                            $badgeColor = 'badge-light-success';
                            $badgeText = 'Selesai';
                            $icon = 'ki-check-circle';
                        }

                        // 2. Mengolah Data JSON YouTube
                        $youtubeLinks = [];
                        if (!empty($w->link_youtube)) {
                            $decoded = json_decode($w->link_youtube, true);
                            if (is_array($decoded)) {
                                $youtubeLinks = $decoded;
                            }
                        }
                        
                        // 3. Mengambil ID Video YouTube PERTAMA untuk dijadikan Cover Thumbnail
                        $firstVideoId = null;
                        if (!empty($youtubeLinks[0])) {
                            // Regex pintar untuk mengekstrak ID Video dari berbagai macam format URL YouTube
                            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $youtubeLinks[0], $match);
                            $firstVideoId = $match[1] ?? null;
                        }
                        ?>

                        <div class="col-md-6 col-xl-4 animate-card" style="animation-delay: <?= $delay ?>s">
                            <div class="card border-0 shadow-sm h-100 hover-elevate-up">
                                
                                <!-- Card Header / Image Thumbnail -->
                                <div class="position-relative img-zoom-container bg-light" style="aspect-ratio: 16/9;">
                                    <?php if ($firstVideoId): ?>
                                        <!-- Jika ada link YT, gunakan thumbnail otomatis dari YouTube HD -->
                                        <img src="https://img.youtube.com/vi/<?= $firstVideoId ?>/maxresdefault.jpg" onerror="this.onerror=null; this.src='https://img.youtube.com/vi/<?= $firstVideoId ?>/hqdefault.jpg';" loading="lazy" class="w-100 h-100 object-fit-cover" alt="<?= esc($w->nama_sesi) ?>">
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <i class="ki-solid ki-youtube fs-3x text-danger bg-white rounded-circle shadow-sm"></i>
                                        </div>
                                    <?php else: ?>
                                        <!-- Fallback jika tidak ada link YouTube -->
                                        <img src="<?= base_url('assets-landing/images/default-webinar.jpg') ?>" loading="lazy" class="w-100 h-100 object-fit-cover" alt="<?= esc($w->nama_sesi) ?>">
                                    <?php endif; ?>

                                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-to-t from-dark opacity-25"></div>

                                    <!-- Label Paket -->
                                    <div class="position-absolute top-0 start-0 p-4">
                                        <span class="badge badge-glass fs-8 fw-bold px-3 py-2">
                                            <?= esc($w->nama_paket ?? 'Webinar Sesi') ?>
                                        </span>
                                    </div>

                                    <!-- Status Live/Upcoming -->
                                    <div class="position-absolute top-0 end-0 p-4">
                                        <span class="badge <?= $badgeColor ?> fs-8 fw-bold px-4 py-2 border-0 shadow-sm">
                                            <i class="ki-outline <?= $icon ?> fs-8 me-1 <?= $status == 'live' ? 'text-white' : '' ?>"></i> <?= $badgeText ?>
                                        </span>
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="card-body p-7 d-flex flex-column">
                                    <div class="d-flex align-items-center mb-5">
                                        <div class="symbol symbol-40px symbol-circle me-3 shadow-sm border border-2 border-white">
                                            <img src="<?= base_url('assets-landing/images/logo-blue.png') ?>" alt="Akuntanmu">
                                        </div>
                                        <div class="d-flex flex-column align-items-start">
                                            <span class="text-dark fw-bolder fs-6">Akuntanmu Center</span>
                                            <span class="badge badge-light-primary fw-bold fs-9 mt-1">Verified Mentor</span>
                                        </div>
                                    </div>

                                    <!-- Judul Sesi -->
                                    <h3 class="text-dark fw-boldest fs-4 mb-3 lh-base min-h-50px">
                                        <?= esc($w->nama_sesi) ?>
                                    </h3>

                                    <div class="separator separator-dashed my-4"></div>

                                    <!-- Info Waktu Zoom -->
                                    <div class="d-flex flex-column gap-3 mb-6">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light-info rounded p-2 me-3">
                                                <i class="ki-outline ki-calendar fs-4 text-info"></i>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-500 fw-semibold fs-8">Tanggal Pelaksanaan</span>
                                                <span class="text-dark fw-bold fs-6"><?= date('d M Y', $waktuMulai) ?></span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light-warning rounded p-2 me-3">
                                                <i class="ki-outline ki-time fs-4 text-warning"></i>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-500 fw-semibold fs-8">Waktu Sesi (WIB)</span>
                                                <span class="text-dark fw-bold fs-6"><?= date('H:i', $waktuMulai) ?> - <?= date('H:i', $waktuSelesai) ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- List YouTube Video (Jika Ada) -->
                                    <?php if (!empty($youtubeLinks)): ?>
                                        <div class="mt-2 mb-6">
                                            <span class="text-gray-600 fw-semibold fs-8 d-block mb-3">Materi / Rekaman Video:</span>
                                            <div class="d-flex flex-column gap-2">
                                                <?php foreach ($youtubeLinks as $idx => $ytLink): ?>
                                                    <a href="<?= esc($ytLink) ?>" target="_blank" class="btn btn-sm btn-light-danger fw-bold d-flex justify-content-start align-items-center">
                                                        <i class="ki-solid ki-youtube fs-4 me-3"></i> Tonton Video Bagian <?= $idx + 1 ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Action Button Zoom -->
                                    <div class="mt-auto">
                                        <?php if ($status == 'upcoming'): ?>
                                            <button class="btn btn-light w-100 fs-7 fw-bold py-3 disabled" disabled>
                                                <i class="ki-outline ki-lock fs-5 me-2"></i> Akses Zoom Dibuka <?= date('d M', $waktuMulai) ?>
                                            </button>
                                        <?php elseif ($status == 'live'): ?>
                                            <a href="<?= esc($w->link_zoom) ?>" target="_blank" class="btn btn-primary btn-glow-primary w-100 fs-7 fw-bold py-3 shadow-sm">
                                                Gabung Zoom <i class="ki-outline ki-entrance-left fs-5 ms-2"></i>
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-light-success w-100 fs-7 fw-bold py-3 disabled" disabled>
                                                <i class="ki-outline ki-check-circle fs-5 me-2"></i> Zoom Selesai
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Empty State -->
                    <div class="col-12 animate-card">
                        <div class="card border-0 shadow-none bg-transparent">
                            <div class="card-body d-flex flex-column flex-center py-20">
                                <div class="mb-10 text-center">
                                    <img src="<?= base_url('assets/peserta/media/illustrations/sigma-1/5.png') ?>"
                                        class="mw-350px mw-lg-450px mb-10 pulse-animation"
                                        alt="Empty State">
                                </div>

                                <div class="text-center">
                                    <h2 class="fw-boldest text-dark mb-4 fs-1">Belum Ada Sesi Webinar</h2>
                                    <p class="text-gray-500 fs-5 fw-semibold mb-10 max-w-600px mx-auto">
                                        Anda belum memiliki sesi webinar atau pelatihan yang aktif saat ini.<br>
                                        Silakan lihat daftar paket pelatihan kami untuk mulai belajar dan berinteraksi dengan mentor.
                                    </p>
                                    <div class="d-flex flex-center gap-4">
                                        <a href="<?= base_url('list-bimbel') ?>" class="btn btn-primary px-8 fw-bold">
                                            <i class="ki-outline ki-shop fs-4 me-2"></i> Jelajahi Paket Webinar
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
    $(document).ready(function() {
        if (typeof KTComponents !== 'undefined') {
            KTComponents.init();
        }
    });
</script>

<?= $this->endSection(); ?>
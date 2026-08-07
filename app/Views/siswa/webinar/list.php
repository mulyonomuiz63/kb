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

    .max-w-600px {
        max-width: 600px;
    }

    .btn {
        transition: all 0.2s ease;
    }

    .badge-title-clamp {
        max-width: 55%;
        /* Batasi lebar agar tidak menabrak badge status di sebelah kanan */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: middle;
        cursor: pointer;
        /* Memberikan indikator bahwa elemen ini bisa di-hover */
    }

    .youtube-recording-box {
        background-color: #f8f9fa;
        border: 1px dashed #dbdfde;
        border-radius: 0.75rem;
        padding: 1rem;
    }

    .youtube-item-btn {
        transition: all 0.2s ease-in-out;
        border: 1px solid #eee;
        cursor: pointer;
    }

    .youtube-item-btn:hover {
        background-color: #fff1f2 !important;
        border-color: #f64e60 !important;
        color: #f64e60 !important;
        transform: translateX(4px);
    }

    /* --- WRAPPER UTAMA CUSTOM PLAYER --- */
    .secure-video-wrapper {
        position: relative;
        width: 100%;
        user-select: none;
        -webkit-user-select: none;
        background-color: #000;
        overflow: hidden;
    }

    /* Blokir total interaksi mouse asli ke youtube iframe */
    #youtubeIframe {
        pointer-events: none !important;
    }

    /* --- OVERLAY & IKON --- */
    .video-click-overlay {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: calc(100% - 50px) !important;
        z-index: 10;
        cursor: pointer;
    }

    .center-icon-overlay {
        position: absolute !important;
        top: 0 !important;
        bottom: 50px !important;
        left: 0 !important;
        right: 0 !important;
        margin: auto !important;
        width: 80px !important;
        height: 80px !important;
        background: rgba(0, 0, 0, 0.6) !important;
        border-radius: 50% !important;
        align-items: center !important;
        justify-content: center !important;
        z-index: 100 !important;
        pointer-events: none !important;
        opacity: 0;
        transition: all 0.3s ease !important;
        display: none;
    }

    .center-icon-overlay.show-icon {
        display: flex !important;
    }

    /* Mask untuk menutupi sisa logo YT (opsional) */
    .yt-logo-mask {
        position: absolute;
        bottom: 50px;
        right: 0;
        width: 120px;
        height: 50px;
        z-index: 15;
        background: transparent;
    }

    /* --- CONTROL BAR BAWAH --- */
    .custom-controls {
        position: absolute !important;
        top: auto !important;
        bottom: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 50px !important;
        background: rgba(0, 0, 0, 0.85);
        z-index: 20;
        display: flex;
        align-items: center;
        padding: 0 15px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .secure-video-wrapper:hover .custom-controls {
        opacity: 1;
    }

    /* --- AUTO-HIDE (IDLE MODE) SAAT MOUSE DIAM --- */
    .secure-video-wrapper.video-idle .custom-controls,
    .secure-video-wrapper.video-idle .center-icon-overlay.show-icon {
        opacity: 0 !important;
        transition: opacity 0.5s ease !important;
    }

    .secure-video-wrapper.video-idle,
    .secure-video-wrapper.video-idle * {
        cursor: none !important;
    }

    /* --- KUSTOMISASI RANGE SLIDER --- */
    .custom-range {
        -webkit-appearance: none;
        appearance: none;
        width: 100%;
        height: 4px !important;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 2px;
        outline: none;
        margin: 0;
        padding: 0;
    }

    .custom-range::-webkit-slider-runnable-track {
        width: 100%;
        height: 4px;
        border-radius: 2px;
    }

    .custom-range::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #ffffff;
        cursor: pointer;
        margin-top: -5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
        transition: transform 0.1s;
    }

    .custom-range::-webkit-slider-thumb:hover {
        transform: scale(1.3);
    }
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
                        // Cek apakah paket ini Gratis atau Berbayar
                        $isPaketGratis = ($w->status == 'F');

                        // Kumpulkan list sesi detail yang akan dijadikan card terpisah
                        $childSessions = [];
                        $childIds = json_decode($w->sesi_gratis, true) ?? [];

                        if (!empty($childIds)) {
                            $db = \Config\Database::connect();
                            $childSessions = $db->table('webinar_sesi')
                                ->whereIn('id_sesi', $childIds)
                                ->orderBy('waktu_mulai', 'ASC')
                                ->get()
                                ->getResult();
                        } else {
                            // Fallback jika tidak ada anak (hanya 1 sesi utama)
                            $childSessions = [$w];
                        }
                        ?>

                        <!-- Render setiap anak sesi menjadi Card Terpisah -->
                        <?php foreach ($childSessions as $child): ?>
                            <?php
                            $delay += 0.1;
                            $waktuMulai = strtotime($child->waktu_mulai);
                            $waktuSelesai = strtotime($child->waktu_selesai);
                            $waktuBukaZoom = $waktuMulai - (2 * 3600); // 2 jam sebelum acara mulai

                            // 1. Menentukan Status Sesi Zoom (Dibuka 2 jam sebelum mulai)
                            if ($currentDateTime < $waktuBukaZoom) {
                                $status = 'upcoming';
                                $badgeColor = 'badge-light-warning';
                                $badgeText = 'Akan Datang';
                                $icon = 'ki-calendar-tick';
                            } elseif ($currentDateTime >= $waktuBukaZoom && $currentDateTime <= $waktuSelesai) {
                                $status = 'live';
                                $badgeColor = 'bg-danger text-white pulse-animation';
                                $badgeText = ($currentDateTime < $waktuMulai) ? 'Segera Dimulai' : 'Sedang Live';
                                $icon = 'ki-bi-camera-video-fill';
                            } else {
                                $status = 'finished';
                                $badgeColor = 'badge-light-success';
                                $badgeText = 'Selesai';
                                $icon = 'ki-check-circle';
                            }

                            // 2. Mengolah Data JSON YouTube
                            $youtubeLinks = [];
                            if (!empty($child->link_youtube)) {
                                $decoded = json_decode($child->link_youtube, true);
                                if (is_array($decoded)) {
                                    $youtubeLinks = $decoded;
                                }
                            }

                            // 3. Mengambil ID Video YouTube PERTAMA untuk dijadikan Cover Thumbnail
                            $firstVideoId = null;
                            if (!empty($youtubeLinks[0])) {
                                preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $youtubeLinks[0], $match);
                                $firstVideoId = $match[1] ?? null;
                            }

                            // 4. Zoom Link Utama
                            $zoomLinks = json_decode($child->link_zoom, true) ?? [];
                            $mainZoomLink = $zoomLinks[0] ?? $child->link_zoom;
                            ?>

                            <div class="col-md-6 col-xl-4 animate-card" style="animation-delay: <?= $delay ?>s">
                                <div class="card border-0 shadow-sm h-100 hover-elevate-up d-flex flex-column">

                                    <!-- Card Header / Image Thumbnail -->
                                    <div class="position-relative img-zoom-container bg-light" style="aspect-ratio: 16/9; flex-shrink: 0;">
                                        <?php if ($firstVideoId && !$isPaketGratis): ?>
                                            <img src="https://img.youtube.com/vi/<?= $firstVideoId ?>/maxresdefault.jpg" onerror="this.onerror=null; this.src='https://img.youtube.com/vi/<?= $firstVideoId ?>/hqdefault.jpg';" loading="lazy" class="w-100 h-100 object-fit-cover" alt="<?= esc($child->nama_sesi) ?>">
                                            <div class="position-absolute top-50 start-50 translate-middle">
                                                <i class="ki-solid ki-youtube fs-3x text-danger bg-white rounded-circle shadow-sm"></i>
                                            </div>
                                        <?php else: ?>
                                            <?= img_lazy('assets-landing/images/paket/thumbnails/' . ($child->file ?? $w->file), esc($child->nama_sesi), ['class' => 'w-100 h-100 object-fit-cover']) ?>
                                        <?php endif; ?>

                                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-to-t from-dark opacity-25"></div>

                                        <!-- Label Paket (Gratis / Premium) -->
                                        <div class="position-absolute top-0 start-0 p-4 w-100">
                                            <?php if ($isPaketGratis): ?>
                                                <span class="badge bg-success text-white fs-8 fw-bold px-3 py-2 shadow-sm d-inline-block badge-title-clamp" data-bs-toggle="tooltip" data-bs-placement="top" title="Paket Gratis">
                                                    <i class="ki-outline ki-abstract-26 fs-6 text-white me-1"></i> Gratis
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark fs-8 fw-bold px-3 py-2 shadow-sm d-inline-block badge-title-clamp" data-bs-toggle="tooltip" data-bs-placement="top" title="Paket Berbayar">
                                                    <i class="ki-outline ki-star fs-6 text-dark me-1"></i> Premium
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Status Live/Upcoming -->
                                        <div class="position-absolute top-0 end-0 p-4">
                                            <span class="badge <?= $badgeColor ?> fs-8 fw-bold px-4 py-2 border-0 shadow-sm">
                                                <i class="ki-outline <?= $icon ?> fs-8 me-1 <?= $status == 'live' ? 'text-white' : '' ?>"></i> <?= $badgeText ?>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Card Body -->
                                    <div class="card-body p-7 d-flex flex-column flex-grow-1">
                                        <div class="d-flex align-items-center mb-5">
                                            <div class="symbol symbol-40px symbol-circle me-3 shadow-sm border border-2 border-white">
                                                <img src="<?= base_url('assets-landing/images/logo-blue.png') ?>" alt="Akuntanmu">
                                            </div>
                                            <div class="d-flex flex-column align-items-start">
                                                <span class="text-dark fw-bolder fs-6">Akuntanmu Learning Center</span>
                                                <span class="badge badge-light-primary fw-bold fs-9 mt-1">Verified Mentor</span>
                                            </div>
                                        </div>

                                        <!-- Judul Sesi Detail -->
                                        <h3 class="text-dark fw-boldest fs-4 mb-3 lh-base min-h-50px">
                                            <?= esc($child->nama_sesi) ?>
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

                                        <!-- List YouTube Video -->
                                        <?php if (!empty($youtubeLinks)): ?>
                                            <div class="youtube-recording-box mb-6">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <span class="text-gray-800 fw-bold fs-7 d-flex align-items-center">
                                                        <i class="ki-solid ki-youtube text-danger fs-3 me-2"></i> Rekaman Materi (<?= count($youtubeLinks) ?> Bagian)
                                                    </span>
                                                </div>
                                                <div class="d-flex flex-column gap-2" style="max-height: 140px; overflow-y: auto;">

                                                    <?php if ($isPaketGratis): ?>
                                                        <div class="alert alert-light-danger border border-danger border-dashed p-3 m-0 d-flex align-items-center">
                                                            <i class="ki-outline ki-lock-3 fs-1 text-danger me-3"></i>
                                                            <div class="d-flex flex-column">
                                                                <span class="fw-bold text-danger fs-8">Akses Terkunci</span>
                                                                <span class="text-muted fs-9">Rekaman hanya untuk peserta Premium.</span>
                                                            </div>
                                                        </div>
                                                    <?php else: ?>
                                                        <?php foreach ($youtubeLinks as $idx => $ytLink): ?>
                                                            <button type="button"
                                                                class="btn btn-sm btn-white youtube-item-btn fw-bold d-flex align-items-center justify-content-between py-2.5 px-3 rounded-2 shadow-xs w-100 text-start"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#youtubeModal"
                                                                data-youtubelink="<?= esc($ytLink) ?>">
                                                                <div class="d-flex align-items-center">
                                                                    <span class="badge badge-light-danger fw-bolder fs-9 me-3 px-2 py-1">Part <?= $idx + 1 ?></span>
                                                                    <span class="text-gray-700 fs-7 text-truncate" style="max-width: 170px;">Tonton Rekaman Sesi <?= $idx + 1 ?></span>
                                                                </div>
                                                                <i class="ki-outline ki-play fs-5 text-danger"></i>
                                                            </button>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>

                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Action Button Gmeet & Sertifikat -->
                                        <div class="mt-auto pt-4">
                                            <?php if ($status == 'upcoming'): ?>
                                                <button class="btn btn-light w-100 fs-7 fw-bold py-3 disabled" disabled>
                                                    <i class="ki-outline ki-lock fs-5 me-2"></i> Akses Gmeet Dibuka <?= date('d M H:i', $waktuBukaZoom) ?>
                                                </button>
                                            <?php elseif ($status == 'live'): ?>
                                                <a href="<?= esc($mainZoomLink) ?>" target="_blank" class="btn btn-primary btn-glow-primary w-100 fs-7 fw-bold py-3 shadow-sm hover-elevate-up">
                                                    Gabung Gmeet <i class="ki-outline ki-entrance-left fs-5 ms-2"></i>
                                                </a>
                                            <?php else: ?>
                                                <!-- PERUBAHAN DI SINI: Tombol Download Sertifikat -->
                                                <button type="button" class="btn btn-success w-100 fs-7 fw-bold py-3 shadow-sm hover-elevate-up" data-bs-toggle="modal" data-bs-target="#modalSertifikat" data-nama="<?= esc($child->nama_sesi) ?>" data-idsesi="<?= encrypt_url($child->id_sesi) ?>">
                                                    <i class="ki-outline ki-diploma fs-4 me-2"></i> Lihat Sertifikat
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <!-- End of Child Cards Loop -->

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

<!-- Modal Popup Pemutar Video YouTube -->
<div class="modal fade" id="youtubeModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content rounded-4 shadow border-0 overflow-hidden">
            <div class="modal-header border-0 pb-0 px-6 pt-6">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center">
                    <i class="ki-solid ki-youtube text-danger fs-2x me-2"></i> Pemutar Rekaman Materi
                </h5>
                <!-- Modifikasi tombol close agar juga menyetop video -->
                <button type="button" class="btn-close" id="btnCloseModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6 bg-dark">
                <!-- Wrapper Khusus Custom Player -->
                <div class="ratio ratio-16x9 secure-video-wrapper rounded-3 shadow-sm" id="videoContainer" oncontextmenu="return false;">

                    <div class="video-click-overlay" id="playPauseOverlay"></div>

                    <!-- Indikator Play/Pause di Tengah -->
                    <div class="center-icon-overlay" id="centerIconContainer">
                        <svg id="centerIconPlay" xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="#ffffff" viewBox="0 0 16 16" style="margin-left: 6px;">
                            <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z" />
                        </svg>
                        <svg id="centerIconPause" class="d-none" xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="#ffffff" viewBox="0 0 16 16">
                            <path d="M5.5 3.5A1.5 1.5 0 0 1 7 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5zm5 0A1.5 1.5 0 0 1 12 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5z" />
                        </svg>
                    </div>

                    <div class="yt-logo-mask"></div>

                    <!-- Div kosong yang akan digantikan oleh YouTube iframe API -->
                    <div id="youtubeIframe"></div>

                    <!-- Custom Controls Bar Bawah -->
                    <div class="custom-controls" id="customControls">
                        <button id="btnPlayPause" class="btn btn-icon btn-sm btn-color-white btn-active-color-primary me-2" style="background: transparent; border: none;">
                            <svg id="iconPlay" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#ffffff" viewBox="0 0 16 16">
                                <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z" />
                            </svg>
                            <svg id="iconPause" class="d-none" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#ffffff" viewBox="0 0 16 16">
                                <path d="M5.5 3.5A1.5 1.5 0 0 1 7 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5zm5 0A1.5 1.5 0 0 1 12 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5z" />
                            </svg>
                        </button>

                        <span id="timeDisplay" class="text-white fw-semibold fs-8 me-4" style="white-space: nowrap; font-family: monospace;">
                            00:00 / 00:00
                        </span>

                        <div class="flex-grow-1 d-flex align-items-center me-4">
                            <input type="range" id="progressBar" class="custom-range w-100" value="0" step="0.1" min="0" max="100">
                        </div>

                        <div class="me-2 d-flex align-items-center">
                            <svg id="iconVolumeUp" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#ffffff" viewBox="0 0 16 16">
                                <path d="M11.536 14.01A8.473 8.473 0 0 0 14.026 8a8.473 8.473 0 0 0-2.49-6.01l-.708.707A7.476 7.476 0 0 1 13.025 8c0 2.071-.84 3.946-2.197 5.303l.708.707z" />
                                <path d="M10.121 12.596A6.48 6.48 0 0 0 12.025 8a6.48 6.48 0 0 0-1.904-4.596l-.707.707A5.483 5.483 0 0 1 11.025 8a5.483 5.483 0 0 1-1.61 3.89l.706.706z" />
                                <path d="M8.707 11.182A4.486 4.486 0 0 0 10.025 8a4.486 4.486 0 0 0-1.318-3.182L8 5.525A3.489 3.489 0 0 1 9.025 8 3.49 3.49 0 0 1 8 10.475l.707.707zM6.717 3.55A.5.5 0 0 1 7 4v8a.5.5 0 0 1-.812.39L3.825 10.5H1.5A.5.5 0 0 1 1 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06z" />
                            </svg>
                            <svg id="iconVolumeMute" class="d-none" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#ffffff" viewBox="0 0 16 16">
                                <path d="M6.717 3.55A.5.5 0 0 1 7 4v8a.5.5 0 0 1-.812.39L3.825 10.5H1.5A.5.5 0 0 1 1 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06zm7.137 2.096a.5.5 0 0 1 0 .708L12.207 8l1.647 1.646a.5.5 0 0 1-.708.708L11.5 8.707l-1.646 1.647a.5.5 0 0 1-.708-.708L10.793 8 9.146 6.354a.5.5 0 1 1 .708-.708L11.5 7.293l1.646-1.647a.5.5 0 0 1 .708 0z" />
                            </svg>
                        </div>
                        <input type="range" id="volumeBar" class="custom-range me-4" style="width: 80px;" value="100" min="0" max="100">

                        <button id="btnFullscreen" class="btn btn-icon btn-sm btn-color-white btn-active-color-primary" style="background: transparent; border: none;">
                            <i class="ki-outline ki-maximize fs-2 text-white"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Popup Sertifikat -->
<div class="modal fade" id="modalSertifikat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl"> <!-- Diubah jadi modal-xl agar preview PDF lebih luas dan lega -->
        <div class="modal-content shadow-lg border-0 rounded-4">

            <div class="modal-header border-0 py-5 bg-light-success">
                <h3 class="fw-bolder mb-0 text-success d-flex align-items-center">
                    <i class="ki-outline ki-diploma fs-1 me-2 text-success"></i> Sertifikat Pelatihan
                </h3>
                <div class="btn btn-icon btn-sm btn-active-light-success ms-2" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>

            <div class="modal-body py-5 px-lg-10 text-center">
                <!-- Judul Dinamis -->
                <h4 class="fw-bold text-gray-800 mb-5" id="sertifikatTitleName">Nama Sesi</h4>

                <!-- PREVIEW SERTIFIKAT MENGGUNAKAN IFRAME -->
                <div class="border border-2 border-dashed border-success rounded-3 p-2 mb-5 mx-auto bg-light overflow-hidden" style="position: relative;">
                    <iframe id="iframeSertifikat" src="" width="100%" height="500px" style="border:none; display:block; border-radius: 8px;"></iframe>
                </div>

                <p class="text-muted fs-6 mb-0">Selamat! Anda telah menyelesaikan sesi pelatihan ini. Sertifikat penghargaan Anda sudah diterbitkan dan siap untuk diunduh.</p>
            </div>

            <div class="modal-footer border-0 pt-0 pb-8 justify-content-center">
                <button type="button" class="btn btn-light fw-bold me-3 px-6" data-bs-dismiss="modal">Tutup</button>

                <!-- Tombol Action Download PDF -->
                <a href="#" id="btnDownloadSertifikat" class="btn btn-success fw-bold px-8 shadow-sm hover-elevate-up" target="_blank">
                    <i class="ki-outline ki-file-down fs-3 me-2"></i> Download PDF
                </a>
            </div>

        </div>
    </div>
</div>
<script src="https://www.youtube.com/iframe_api"></script>

<script>
    // --- 1. BLOKIR TINDAKAN KLIK KANAN & SHORTCUT (Keamanan) ---
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });
    document.onkeydown = function(e) {
        if (e.keyCode === 123) return false; // F12
        if (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74 || e.keyCode === 67)) return false; // Inspect Element dll
        if (e.ctrlKey && e.keyCode === 85) return false; // View Source
        if (e.ctrlKey && e.keyCode === 83) return false; // Save Page
    };

    let ytPlayer;
    let idleTimer;
    let pendingVideoId = null;

    // --- 2. Inisialisasi Player (Terpanggil otomatis oleh script YouTube) ---
    window.onYouTubeIframeAPIReady = function() {
        ytPlayer = new YT.Player('youtubeIframe', {
            height: '100%',
            width: '100%',
            videoId: '', // Dikosongkan dulu, akan diisi saat modal diklik
            playerVars: {
                autoplay: 1,
                controls: 0,
                disablekb: 1,
                rel: 0,
                modestbranding: 1,
                iv_load_policy: 3,
                enablejsapi: 1,
                origin: window.location.origin
            },
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
    };

    // --- 3. Format Waktu & Update CSS Range Slider ---
    function formatTime(seconds) {
        if (isNaN(seconds)) return "00:00";
        const h = Math.floor(seconds / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = Math.floor(seconds % 60);
        const fM = m.toString().padStart(2, '0');
        const fS = s.toString().padStart(2, '0');
        return h > 0 ? `${h}:${fM}:${fS}` : `${fM}:${fS}`;
    }

    function updateRangeFill(el) {
        const val = el.value;
        const percentage = ((val - (el.min || 0)) / ((el.max || 100) - (el.min || 0))) * 100;
        el.style.background = `linear-gradient(to right, #ffffff 0%, #ffffff ${percentage}%, rgba(255, 255, 255, 0.2) ${percentage}%, rgba(255, 255, 255, 0.2) 100%)`;
    }

    function extractVideoId(url) {
        let match = url.match(/(?:youtube(?:-nocookie)?\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
        return (match && match[1]) ? match[1] : null;
    }

    // --- 4. Logic UI Player Custom ---
    function trackProgress() {
        if (ytPlayer && ytPlayer.getPlayerState) {
            if (ytPlayer.getPlayerState() === YT.PlayerState.PLAYING) {
                const duration = ytPlayer.getDuration();
                const current = ytPlayer.getCurrentTime();
                if (duration > 0) {
                    const percentage = (current / duration) * 100;
                    const pb = document.getElementById('progressBar');
                    pb.value = percentage;
                    updateRangeFill(pb);
                    document.getElementById('timeDisplay').innerText = `${formatTime(current)} / ${formatTime(duration)}`;
                }
                requestAnimationFrame(trackProgress);
            }
        }
    }

    function resetIdleTimer() {
        const videoContainer = document.getElementById('videoContainer');
        videoContainer.classList.remove('video-idle');
        clearTimeout(idleTimer);
        if (ytPlayer && ytPlayer.getPlayerState && ytPlayer.getPlayerState() === YT.PlayerState.PLAYING) {
            idleTimer = setTimeout(() => {
                videoContainer.classList.add('video-idle');
            }, 2500);
        }
    }

    function onPlayerReady(event) {
        // Jika ada video yang menunggu di-load saat modal diklik sebelumnya
        if (pendingVideoId) {
            ytPlayer.loadVideoById(pendingVideoId);
            pendingVideoId = null;
        }
    }

    function onPlayerStateChange(event) {
        const centerContainer = document.getElementById('centerIconContainer');

        if (event.data === YT.PlayerState.UNSTARTED || event.data === YT.PlayerState.CUED) {
            centerContainer.classList.remove('show-icon');
        } else {
            centerContainer.classList.add('show-icon');
        }

        if (event.data === YT.PlayerState.PLAYING) {
            document.getElementById('iconPlay').classList.add('d-none');
            document.getElementById('centerIconPlay').classList.add('d-none');
            document.getElementById('iconPause').classList.remove('d-none');
            document.getElementById('centerIconPause').classList.remove('d-none');
            resetIdleTimer();
            requestAnimationFrame(trackProgress);
        } else if (event.data === YT.PlayerState.PAUSED || event.data === YT.PlayerState.ENDED) {
            document.getElementById('iconPause').classList.add('d-none');
            document.getElementById('centerIconPause').classList.add('d-none');
            document.getElementById('iconPlay').classList.remove('d-none');
            document.getElementById('centerIconPlay').classList.remove('d-none');
            clearTimeout(idleTimer);
            document.getElementById('videoContainer').classList.remove('video-idle');
        }
    }

    // --- 5. DOM EVENT LISTENER ---
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof KTComponents !== 'undefined') KTComponents.init();

        updateRangeFill(document.getElementById('progressBar'));
        updateRangeFill(document.getElementById('volumeBar'));

        // Handle Klik List Rekaman
        document.querySelectorAll('.youtube-item-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                let rawUrl = this.getAttribute('data-youtubelink');
                let vid = extractVideoId(rawUrl);

                if (!vid) {
                    e.preventDefault();
                    e.stopPropagation();
                    alert('Maaf, URL rekaman video tidak valid atau belum tersedia.');
                    return false;
                }

                if (ytPlayer && ytPlayer.loadVideoById) {
                    ytPlayer.loadVideoById(vid);
                } else {
                    pendingVideoId = vid; // Jika YT API belum 100% siap, simpan di pending
                }
            });
        });

        // Event saat Modal Ditutup (Stop Video)
        const youtubeModal = document.getElementById('youtubeModal');
        if (youtubeModal) {
            youtubeModal.addEventListener('hidden.bs.modal', function() {
                if (ytPlayer && ytPlayer.stopVideo) {
                    ytPlayer.stopVideo();
                }
            });
        }

        // Custom Control: Play/Pause Overlay Klik
        document.getElementById('playPauseOverlay').addEventListener('click', function() {
            togglePlayPause();
        });
        document.getElementById('btnPlayPause').addEventListener('click', function() {
            togglePlayPause();
        });

        function togglePlayPause() {
            if (ytPlayer && ytPlayer.getPlayerState) {
                if (ytPlayer.getPlayerState() === YT.PlayerState.PLAYING) ytPlayer.pauseVideo();
                else ytPlayer.playVideo();
            }
        }

        // Custom Control: Progress bar
        document.getElementById('progressBar').addEventListener('input', function() {
            updateRangeFill(this);
            if (ytPlayer && ytPlayer.getDuration) {
                const duration = ytPlayer.getDuration();
                const seekToTime = (this.value / 100) * duration;
                ytPlayer.seekTo(seekToTime, true);
                document.getElementById('timeDisplay').innerText = `${formatTime(seekToTime)} / ${formatTime(duration)}`;
            }
        });

        // Custom Control: Volume
        document.getElementById('volumeBar').addEventListener('input', function() {
            updateRangeFill(this);
            const volVal = this.value;
            if (volVal == 0) {
                document.getElementById('iconVolumeUp').classList.add('d-none');
                document.getElementById('iconVolumeMute').classList.remove('d-none');
            } else {
                document.getElementById('iconVolumeMute').classList.add('d-none');
                document.getElementById('iconVolumeUp').classList.remove('d-none');
            }
            if (ytPlayer && ytPlayer.setVolume) ytPlayer.setVolume(volVal);
        });

        // Custom Control: Idle Mode
        const vContainer = document.getElementById('videoContainer');
        ['mousemove', 'mousedown', 'touchstart', 'keydown'].forEach(evt =>
            vContainer.addEventListener(evt, resetIdleTimer)
        );
        vContainer.addEventListener('mouseleave', function() {
            if (ytPlayer && ytPlayer.getPlayerState && ytPlayer.getPlayerState() === YT.PlayerState.PLAYING) {
                clearTimeout(idleTimer);
            }
        });

        // Custom Control: Fullscreen
        document.getElementById('btnFullscreen').addEventListener('click', function() {
            const vc = document.getElementById('videoContainer');
            if (!document.fullscreenElement) {
                if (vc.requestFullscreen) vc.requestFullscreen();
                else if (vc.webkitRequestFullscreen) vc.webkitRequestFullscreen();
                else if (vc.msRequestFullscreen) vc.msRequestFullscreen();
            } else {
                if (document.exitFullscreen) document.exitFullscreen();
                else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
                else if (document.msExitFullscreen) document.msExitFullscreen();
            }
        });
    });
</script>
<!-- Script Dinamis Sertifikat -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modalSertifikat = document.getElementById('modalSertifikat');

        if (modalSertifikat) {
            modalSertifikat.addEventListener('show.bs.modal', function(event) {
                // Tangkap tombol yang diklik
                const button = event.relatedTarget;

                // Ambil data atribut dari tombol
                const namaSesi = button.getAttribute('data-nama');
                const idSesi = button.getAttribute('data-idsesi');

                // Update Teks Judul di Modal
                modalSertifikat.querySelector('#sertifikatTitleName').textContent = namaSesi;

                // Buat URL endpoint sertifikat
                const urlSertifikat = "<?= base_url('sw-siswa/webinar/sertifikat') ?>/" + idSesi;

                // Masukkan URL ke dalam Iframe agar langsung merender PDF di dalam modal
                modalSertifikat.querySelector('#iframeSertifikat').src = urlSertifikat;

                // Update Link Tombol Download
                const btnDownload = modalSertifikat.querySelector('#btnDownloadSertifikat');
                btnDownload.href = urlSertifikat + "?download=true";
            });

            // Kosongkan iframe saat modal ditutup agar tidak membebani memori browser
            modalSertifikat.addEventListener('hidden.bs.modal', function() {
                modalSertifikat.querySelector('#iframeSertifikat').src = '';
            });
        }
    });
</script>

<?= $this->endSection(); ?>
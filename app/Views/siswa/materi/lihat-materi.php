<?= $this->extend('siswa/template/app'); ?>

<?= $this->section('styles'); ?>
<style>
  /* ==========================================================================
     1. TOP HEADER STYLING (DI ATAS BACKGROUND BIRU GELAP METRONIC)
     ========================================================================== */
  .badge-glass {
    background: rgba(255, 255, 255, 0.15) !important;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.25) !important;
    color: #ffffff !important;
    font-weight: 600;
  }

  .btn-white-glass {
    background: #ffffff !important;
    color: #004b87 !important;
    border: none !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    transition: all 0.25s ease;
  }
  .btn-white-glass:hover {
    background: #f1f5f9 !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    color: #003366 !important;
  }

  /* ==========================================================================
     2. VIDEO PLAYER & CINEMA STYLING
     ========================================================================== */
  .secure-video-wrapper {
    position: relative;
    width: 100%;
    user-select: none;
    -webkit-user-select: none;
    background-color: #0a0e17;
    border-radius: 0.85rem 0.85rem 0 0;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
  }

  /* Blokir interaksi mouse bawaan ke youtube */
  #mainVideo {
    pointer-events: none !important;
    width: 100%;
    height: 100%;
  }

  /* Overlay klik untuk Play/Pause */
  .video-click-overlay {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: calc(100% - 56px) !important;
    z-index: 10;
    cursor: pointer;
  }

  /* Center Play/Pause Ripple Overlay */
  .center-icon-overlay {
    position: absolute !important;
    top: 0 !important;
    bottom: 56px !important;
    left: 0 !important;
    right: 0 !important;
    margin: auto !important;
    width: 72px !important;
    height: 72px !important;
    background: rgba(15, 23, 42, 0.75) !important;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 2px solid rgba(255, 255, 255, 0.3) !important;
    border-radius: 50% !important;
    align-items: center !important;
    justify-content: center !important;
    z-index: 100 !important;
    pointer-events: none !important;
    opacity: 0;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5) !important;
    transition: all 0.3s ease !important;
    display: none;
  }

  .center-icon-overlay.show-icon {
    display: flex !important;
  }

  /* Control bar bawah dengan glassmorphism */
  .custom-controls {
    position: absolute !important;
    top: auto !important;
    bottom: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 56px !important;
    background: linear-gradient(180deg, rgba(15, 23, 42, 0) 0%, rgba(10, 14, 23, 0.95) 100%);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    z-index: 20;
    display: flex;
    align-items: center;
    padding: 0 16px;
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .secure-video-wrapper:hover .custom-controls {
    opacity: 1;
  }

  /* Auto-hide saat idle */
  .secure-video-wrapper.video-idle .custom-controls,
  .secure-video-wrapper.video-idle .center-icon-overlay.show-icon {
    opacity: 0 !important;
    transition: opacity 0.5s ease !important;
  }

  .secure-video-wrapper.video-idle,
  .secure-video-wrapper.video-idle * {
    cursor: none !important;
  }

  /* Custom Range Sliders */
  .custom-range {
    -webkit-appearance: none;
    appearance: none;
    width: 100%;
    height: 5px !important;
    background: rgba(255, 255, 255, 0.25);
    border-radius: 3px;
    outline: none;
    margin: 0;
    padding: 0;
    cursor: pointer;
  }

  .custom-range::-webkit-slider-runnable-track {
    width: 100%;
    height: 5px;
    border-radius: 3px;
  }

  .custom-range::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background: #0086a7;
    border: 2px solid #ffffff;
    cursor: pointer;
    margin-top: -5px;
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.6);
    transition: transform 0.15s ease;
  }

  .custom-range::-webkit-slider-thumb:hover {
    transform: scale(1.3);
    background: #00a8cc;
  }

  .custom-range::-moz-range-thumb {
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background: #0086a7;
    border: 2px solid #ffffff;
    cursor: pointer;
    transition: transform 0.15s ease;
  }

  /* ==========================================================================
     3. PLAYLIST CAROUSEL STYLING
     ========================================================================== */
  .scroll-track::-webkit-scrollbar {
    height: 6px;
  }
  .scroll-track::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
  }
  .scroll-track::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
  }
  .scroll-track::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
  }

  .video-thumb {
    position: relative;
    border-radius: 0.75rem;
    transition: all 0.25s ease;
    background: #ffffff;
    border: 2px solid #e2e8f0;
  }

  .video-thumb:hover {
    transform: translateY(-3px);
    border-color: #0086a7 !important;
    box-shadow: 0 8px 20px rgba(0, 134, 167, 0.15);
  }

  .video-thumb.border-primary {
    border-color: #0086a7 !important;
    background: #f0fdfa;
    box-shadow: 0 8px 20px rgba(0, 134, 167, 0.18);
  }

  .video-thumb .thumb-overlay-play {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(15, 23, 42, 0.35);
    border-radius: 0.5rem;
    opacity: 0;
    transition: opacity 0.2s ease;
  }

  .video-thumb:hover .thumb-overlay-play {
    opacity: 1;
  }

  .video-title-clamp {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    min-height: 38px;
    line-height: 1.35;
  }

  /* ==========================================================================
     4. CHAT DISKUSI & MENTION STYLING (ROBUST FLEXBOX CONTAINER)
     ========================================================================== */
  .chat-card-container {
    height: 100%;
    min-height: 560px;
    display: flex;
    flex-direction: column;
    border-radius: 0.85rem;
    overflow: hidden;
    background: #ffffff;
  }

  .chat-header-gradient {
    background: linear-gradient(135deg, #0086a7 0%, #00566b 100%);
  }

  .inner-chat-materi {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
  }
  .inner-chat-materi::-webkit-scrollbar {
    width: 5px;
  }
  .inner-chat-materi::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 8px;
  }

  .chat-bubble-me {
    background: #e0f2fe;
    border: 1px solid #bae6fd;
    border-radius: 1rem 1rem 0.25rem 1rem;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
  }

  .chat-bubble-other {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 1rem 1rem 1rem 0.25rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
  }

  .chat-message-text {
    word-wrap: break-word;
    white-space: pre-line;
  }

  .mention-dropdown {
    position: absolute;
    bottom: 100%;
    left: 15px;
    width: calc(100% - 30px);
    max-height: 220px;
    overflow-y: auto;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 0.65rem;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    display: none;
    z-index: 1050;
  }

  .mention-item {
    padding: 10px 14px;
    cursor: pointer;
    transition: background-color 0.15s ease;
    border-bottom: 1px solid #f8fafc;
  }

  .mention-item:last-child {
    border-bottom: none;
  }

  .mention-item:hover {
    background-color: #e0f2fe;
    color: #0086a7;
  }

  /* Responsif styling */
  @media (max-width: 575.98px) {
    .custom-controls {
      padding: 0 10px;
    }
    #volumeBar {
      width: 50px !important;
    }
    .video-thumb {
      width: 175px !important;
    }
  }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid py-4 py-lg-4 mt-8">
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

      <!-- ================= HEADER NAVIGATION BAR (DIRANCANG KHUSUS BACKGROUND BIRU) ================= -->
      <div class="mb-6 animate__animated animate__fadeIn">
        
        <!-- Baris Tombol & Badges Filter -->
        <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
          <a href="<?= base_url('sw-siswa/materi') ?>" class="btn btn-sm btn-white-glass fw-bolder px-4 py-2 d-inline-flex align-items-center rounded-pill">
            <i class="ki-outline ki-arrow-left fs-4 me-1 text-primary"></i> Kembali ke Daftar Materi
          </a>
          <span class="badge badge-glass px-3 py-2 fs-7 rounded-pill">
            <i class="ki-outline ki-book-open fs-5 me-1 text-white"></i> <?= esc($materi->nama_mapel ?? 'Materi Brevet') ?>
          </span>
          <span class="badge badge-glass px-3 py-2 fs-7 rounded-pill">
            <i class="ki-outline ki-abstract-26 fs-5 me-1 text-white"></i> <?= esc($materi->nama_kelas ?? 'Kelas Brevet') ?>
          </span>
          <?php if (!empty($materi->nama_guru)): ?>
            <span class="badge badge-glass px-3 py-2 fs-7 rounded-pill">
              <i class="ki-outline ki-teacher fs-5 me-1 text-white"></i> Instruktur: <?= esc($materi->nama_guru) ?>
            </span>
          <?php endif; ?>
        </div>
      </div>

      <div class="row g-5 g-xl-8 align-items-stretch">
        
        <!-- ================= KOLOM KIRI: VIDEO PLAYER & PLAYLIST ================= -->
        <div class="col-xl-8 d-flex flex-column">
          
          <!-- VIDEO PLAYER CARD -->
          <div class="card card-flush shadow-sm mb-5 mb-xl-8 border-0 overflow-hidden animate__animated animate__fadeIn rounded-4">
            <?php
            $thumbs = json_decode($materi->text_materi, true) ?? [];
            $first = $thumbs[0] ?? $materi->text_materi;
            if (preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $first, $match)) {
              $firstVideoId = $match[1];
            } else {
              $firstVideoId = trim($first);
            }
            ?>
            <div class="card-body p-0">
              <div class="ratio ratio-16x9 secure-video-wrapper" id="videoContainer" oncontextmenu="return false;">

                <!-- Overlay Klik Layar (Play/Pause) -->
                <div class="video-click-overlay" id="playPauseOverlay"></div>

                <!-- Ikon Tengah Play/Pause Ripple -->
                <div class="center-icon-overlay" id="centerIconContainer">
                  <svg id="centerIconPlay" xmlns="http://www.w3.org/2000/svg" width="56" height="56" fill="#ffffff" viewBox="0 0 16 16" style="margin-left: 4px;">
                    <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z" />
                  </svg>
                  <svg id="centerIconPause" class="d-none" xmlns="http://www.w3.org/2000/svg" width="56" height="56" fill="#ffffff" viewBox="0 0 16 16">
                    <path d="M5.5 3.5A1.5 1.5 0 0 1 7 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5zm5 0A1.5 1.5 0 0 1 12 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5z" />
                  </svg>
                </div>

                <div class="yt-logo-mask"></div>

                <!-- YouTube Iframe API Target -->
                <iframe id="mainVideo"
                  src="https://www.youtube.com/embed/<?= $firstVideoId ?>?enablejsapi=1&rel=0&controls=0&disablekb=1&modestbranding=1&autoplay=1&iv_load_policy=3"
                  allow="autoplay; encrypted-media"
                  allowfullscreen>
                </iframe>

                <!-- Custom Video Controls Bar -->
                <div class="custom-controls" id="customControls">
                  <button id="btnPlayPause" class="btn btn-icon btn-sm btn-color-white btn-active-color-primary me-2" style="background: transparent; border: none;" title="Play/Pause">
                    <svg id="iconPlay" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#ffffff" viewBox="0 0 16 16">
                      <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z" />
                    </svg>
                    <svg id="iconPause" class="d-none" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#ffffff" viewBox="0 0 16 16">
                      <path d="M5.5 3.5A1.5 1.5 0 0 1 7 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5zm5 0A1.5 1.5 0 0 1 12 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5z" />
                    </svg>
                  </button>

                  <span id="timeDisplay" class="text-white fw-semibold fs-8 me-3" style="white-space: nowrap; font-family: monospace;">
                    00:00 / 00:00
                  </span>

                  <!-- Seek bar -->
                  <div class="flex-grow-1 d-flex align-items-center me-3">
                    <input type="range" id="progressBar" class="custom-range w-100" value="0" step="0.1" min="0" max="100">
                  </div>

                  <!-- Volume Controls -->
                  <div class="me-2 d-flex align-items-center cursor-pointer">
                    <svg id="iconVolumeUp" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#ffffff" viewBox="0 0 16 16">
                      <path d="M11.536 14.01A8.473 8.473 0 0 0 14.026 8a8.473 8.473 0 0 0-2.49-6.01l-.708.707A7.476 7.476 0 0 1 13.025 8c0 2.071-.84 3.946-2.197 5.303l.708.707z" />
                      <path d="M10.121 12.596A6.48 6.48 0 0 0 12.025 8a6.48 6.48 0 0 0-1.904-4.596l-.707.707A5.483 5.483 0 0 1 11.025 8a5.483 5.483 0 0 1-1.61 3.89l.706.706z" />
                      <path d="M8.707 11.182A4.486 4.486 0 0 0 10.025 8a4.486 4.486 0 0 0-1.318-3.182L8 5.525A3.489 3.489 0 0 1 9.025 8 3.49 3.49 0 0 1 8 10.475l.707.707zM6.717 3.55A.5.5 0 0 1 7 4v8a.5.5 0 0 1-.812.39L3.825 10.5H1.5A.5.5 0 0 1 1 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06z" />
                    </svg>
                    <svg id="iconVolumeMute" class="d-none" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#ffffff" viewBox="0 0 16 16">
                      <path d="M6.717 3.55A.5.5 0 0 1 7 4v8a.5.5 0 0 1-.812.39L3.825 10.5H1.5A.5.5 0 0 1 1 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06zm7.137 2.096a.5.5 0 0 1 0 .708L12.207 8l1.647 1.646a.5.5 0 0 1-.708.708L11.5 8.707l-1.646 1.647a.5.5 0 0 1-.708-.708L10.793 8 9.146 6.354a.5.5 0 1 1 .708-.708L11.5 7.293l1.646-1.647a.5.5 0 0 1 .708 0z" />
                    </svg>
                  </div>
                  <input type="range" id="volumeBar" class="custom-range me-3" style="width: 75px;" value="100" min="0" max="100">

                  <!-- Fullscreen Button -->
                  <button id="btnFullscreen" class="btn btn-icon btn-sm btn-color-white btn-active-color-primary" style="background: transparent; border: none;" title="Fullscreen">
                    <i class="ki-outline ki-maximize fs-2 text-white"></i>
                  </button>
                </div>

              </div>
            </div>

            <!-- Detail Video Aktif (Bagian Bawah Player) -->
            <div class="card-footer p-4 p-md-5 bg-white border-top">
              <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="pe-md-4">
                  <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <span class="badge badge-light-primary fw-bold px-2 py-1 fs-9">
                      <i class="ki-outline ki-youtube fs-8 text-primary me-1"></i> Sedang Diputar
                    </span>
                  </div>
                  <h3 class="fs-5 fw-bold text-gray-900 mb-1" id="active_video_title_sub">
                    <?= esc($materi->nama_materi) ?>
                  </h3>
                  <div class="d-flex align-items-center gap-3 text-muted fs-8">
                    <span><i class="ki-outline ki-teacher fs-6 text-gray-500 me-1"></i><?= esc($materi->nama_guru ?? 'Instruktur Kelas Brevet') ?></span>
                    <span>•</span>
                    <span><i class="ki-outline ki-shield-tick fs-6 text-success me-1"></i>Modul Resmi Brevet Perpajakan</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- PLAYLIST PEMBELAJARAN CARD -->
          <div class="card card-flush shadow-sm mb-5 mb-xl-8 animate__animated animate__fadeInUp border-0 rounded-4">
            <div class="card-header border-0 pt-5 pb-2">
              <div class="card-title align-items-start flex-column">
                <div class="d-flex align-items-center gap-2">
                  <span class="card-label fw-bold text-gray-900 fs-4">Playlist Pembelajaran</span>
                  <?php
                  $allPlaylist = [];
                  foreach ($thumbs as $thumb) {
                    $allPlaylist[] = ['vid' => $thumb, 'title' => $materi->nama_materi, 'kode' => $materi->kode_materi, 'active' => true];
                  }
                  foreach ($materiAll as $ma) {
                    if ($materi->id_materi != $ma->id_materi) {
                      $tMa = json_decode($ma->text_materi, true) ?? [];
                      foreach ($tMa as $tm) {
                        $allPlaylist[] = ['vid' => $tm, 'title' => $ma->nama_materi, 'kode' => $ma->kode_materi, 'active' => false];
                      }
                    }
                  }
                  ?>
                  <span class="badge badge-light-primary fw-bold fs-8"><?= count($allPlaylist) ?> Video</span>
                </div>
                <span class="text-muted mt-1 fw-semibold fs-7">Klik video di bawah untuk mempelajari setiap sesi pembelajaran</span>
              </div>
              <div class="card-toolbar">
                <button class="btn btn-icon btn-sm btn-light-primary me-2" id="prevBtn" title="Geser ke kiri"><i class="ki-outline ki-left fs-2"></i></button>
                <button class="btn btn-icon btn-sm btn-light-primary" id="nextBtn" title="Geser ke kanan"><i class="ki-outline ki-right fs-2"></i></button>
              </div>
            </div>
            
            <div class="card-body pt-2 pb-5">
              <div class="d-flex flex-nowrap overflow-auto pb-3 scroll-track" id="carouselTrack" style="scrollbar-width: thin;">
                <?php
                foreach ($allPlaylist as $index => $item):
                  if (preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $item['vid'], $m)) {
                    $vId = $m[1];
                  } else {
                    $vId = trim($item['vid']);
                  }

                  // Cek apakah video sudah dilihat
                  $isViewed = in_array($item['kode'], $viewedList) ? true : false;
                ?>
                  <div class="video-thumb me-4 p-2 flex-shrink-0 cursor-pointer <?= $index == 0 ? 'border border-primary border-3' : '' ?>"
                    style="width: 215px;"
                    data-videoid="<?= $vId ?>"
                    data-kode_materi="<?= $item['kode'] ?>"
                    data-title="<?= esc($item['title']) ?>">
                    
                    <div class="position-relative mb-2 rounded-2 overflow-hidden ratio ratio-16x9 bg-dark">
                      <img src="https://img.youtube.com/vi/<?= $vId ?>/hqdefault.jpg" class="w-100 h-100 object-fit-cover rounded-2" alt="Thumbnail">
                      
                      <!-- Sesi badge -->
                      <span class="badge bg-dark bg-opacity-75 text-white fs-9 position-absolute top-0 start-0 m-2 px-2 py-1 rounded">
                        Sesi <?= $index + 1 ?>
                      </span>

                      <!-- Checkmark badge viewed -->
                      <div class="position-absolute top-0 end-0 p-2 checkmark-icon <?= $isViewed ? '' : 'd-none' ?>" id="check-<?= $item['kode'] ?>">
                        <span class="badge badge-circle badge-success shadow-sm" style="width: 24px; height: 24px; border: 2px solid #ffffff;">
                          <i class="ki-outline ki-check text-white fs-4 fw-bold"></i>
                        </span>
                      </div>

                      <!-- Hover play indicator -->
                      <div class="thumb-overlay-play">
                        <span class="badge badge-circle badge-primary" style="width: 36px; height: 36px;">
                          <i class="ki-outline ki-to-right text-white fs-2"></i>
                        </span>
                      </div>
                    </div>

                    <div class="px-1">
                      <span class="text-gray-900 fw-bold text-hover-primary fs-7 d-block video-title-clamp" title="<?= esc($item['title']) ?>">
                        <?= esc($item['title']) ?>
                      </span>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <!-- NOTICE HAK CIPTA -->
          <div class="alert alert-dismissible bg-light-warning d-flex flex-column flex-sm-row p-5 mb-10 border border-warning border-dashed rounded-3">
            <i class="ki-outline ki-shield-cross fs-2hx text-warning me-4 mb-5 mb-sm-0"></i>
            <div class="d-flex flex-column pe-0 pe-sm-10">
              <h5 class="mb-1 text-gray-900 fw-bold">Perlindungan Hak Cipta & Etika Belajar</h5>
              <span class="fs-7 text-gray-700">Seluruh materi video, modul soal, dan bahan ajar dilindungi hak cipta resmi Kelas Brevet. Dilarang keras merekam, menyebarluaskan, atau membagikan akses akun tanpa izin tertulis dari manajemen.</span>
            </div>
          </div>

        </div>

        <!-- ================= KOLOM KANAN: DISKUSI MATERI & TANYA JAWAB ================= -->
        <div class="col-xl-4 d-flex flex-column mb-10">
          <div class="chat-card-container card card-flush shadow-sm animate__animated animate__fadeInRight border-0">
            
            <!-- HEADER CHAT -->
            <div class="card-header pt-4 pb-4 px-4 chat-header-gradient flex-shrink-0">
              <div class="card-title d-flex align-items-center justify-content-between w-100 m-0">
                <div class="d-flex flex-column pe-2" style="max-width: 65%;">
                  <div class="d-flex align-items-center gap-2">
                    <i class="ki-outline ki-messages text-white fs-3"></i>
                    <span class="card-label fw-bold text-white fs-5 lh-1">Diskusi Materi</span>
                  </div>
                  <span class="text-white opacity-75 fw-semibold fs-8 text-truncate mt-1" id="judul_materi_chat" style="max-width: 100%;">
                    Tanyakan hal yang belum dimengerti
                  </span>
                </div>
                <div class="card-toolbar">
                  <button type="button" class="btn btn-sm btn-light fw-bold text-primary d-flex align-items-center px-3 py-2 shadow-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#staticBackdrop" title="Download File Materi">
                    <i class="ki-outline ki-file-down fs-4 me-1 text-primary"></i>
                    <span class="fs-8">File Modul</span>
                    <span id="fileCountBadge" class="badge badge-circle badge-danger ms-2 fs-9" style="display: none;">0</span>
                  </button>
                </div>
              </div>
            </div>
            
            <!-- BODY CHAT (FLEX GROW & SCROLL OTOMATIS) -->
            <div class="card-body p-3 p-md-4 d-flex flex-column flex-grow-1 overflow-hidden bg-light-subtle" style="min-height: 0;">
              <div class="scroll-y flex-grow-1 pe-2 inner-chat-materi" style="min-height: 320px; max-height: 480px; overflow-y: auto;">
                <div class="d-flex justify-content-center py-10">
                  <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Memuat...</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- FOOTER CHAT / INPUT PESAN (SELALU KELIHATAN DI BAWAH) -->
            <div class="card-footer p-3 p-md-4 flex-shrink-0 position-relative bg-white border-top" id="kt_chat_messenger_footer">
              <input type="hidden" name="kode_materi" id="kode_materi" value="">

              <!-- Mention autocomplete dropdown -->
              <div id="mention_dropdown" class="mention-dropdown shadow-lg"></div>

              <textarea id="msg_input" name="text" class="form-control mb-2 border border-gray-300 fs-7" rows="2" data-kt-element="input" placeholder="Tulis pertanyaan Anda... (Gunakan @ untuk mention)" style="resize: none;"></textarea>
              
              <div class="d-flex align-items-center justify-content-between">
                <div>
                  <small id="informasi" class="text-danger fw-semibold fs-8"></small>
                  <span class="text-muted fs-9 d-block">Enter kirim, Shift+Enter baris baru</span>
                </div>
                <div class="d-flex">
                  <button id="btn_cancel_edit" class="btn btn-light-danger btn-sm me-2 d-none fw-bold" type="button" title="Batal Edit">
                    Batal
                  </button>
                  <button id="chat_materi" class="btn btn-primary btn-sm fw-bold px-4" type="button" data-kt-element="send">
                    <i class="ki-outline ki-send fs-4 me-1"></i> Kirim
                  </button>
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- ================= MODAL DOWNLOAD FILE MATERI ================= -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header border-0 bg-light pb-4">
        <div class="d-flex align-items-center">
          <div class="symbol symbol-40px symbol-circle bg-light-primary me-3">
            <i class="ki-outline ki-folder-down fs-2 text-primary"></i>
          </div>
          <div>
            <h3 class="modal-title fw-bold text-gray-900 mb-0">Download Berkas Softcopy & Modul</h3>
            <span class="text-muted fs-7">Unduh lembar kerja, materi presentasi, dan latihan soal resmi</span>
          </div>
        </div>
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
          <i class="ki-outline ki-cross fs-1"></i>
        </div>
      </div>
      <div class="modal-body p-6">
        <div id="file-list-container">
          <div class="text-center py-5">
            <span class="spinner-border text-primary"></span>
          </div>
        </div>
        <div class="mt-6 pt-5 border-top">
          <div class="d-flex flex-column align-items-center bg-light-primary rounded-3 p-5 text-center">
            <div class="symbol symbol-50px symbol-circle bg-white shadow-sm mb-3">
              <i class="ki-outline ki-cloud-download fs-2tx text-primary"></i>
            </div>
            <h5 class="fw-bold text-gray-900 mb-2">Akses Seluruh Modul Lengkap</h5>
            <span class="text-muted fs-7 mb-4 mw-450px">Untuk melihat atau mengunduh seluruh arsip materi dan file latihan secara lengkap, silakan kunjungi Google Drive materi berikut.</span>
            <a href="https://drive.google.com/drive/folders/1Rqr_3mgwLJx-8Zx2NLUPNuLT0xrqvUe9?usp=sharing" target="_blank" class="btn btn-primary btn-sm fw-bold px-5 rounded-pill">
              <i class="ki-outline ki-exit-up-right fs-4 me-2"></i> Buka Folder Google Drive
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ================= MODAL FULLSCREEN PREVIEW PDF ================= -->
<div class="modal fade" id="pdfPreviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content shadow-none">
      <div class="modal-header bg-light border-bottom shadow-sm z-index-1 py-3">
        <div class="d-flex align-items-center">
          <i class="ki-outline ki-document fs-1 text-primary me-3"></i>
          <div>
            <h5 class="modal-title fw-bold text-dark mb-0" id="pdfModalTitle">Preview Dokumen</h5>
            <span class="text-muted fs-8">Pratinjau modul langsung di peramban</span>
          </div>
        </div>
        <div class="d-flex align-items-center">
          <a href="#" id="pdfDownloadBtn" download class="btn btn-sm btn-primary me-3 fw-bold rounded-pill">
            <i class="ki-outline ki-save-2 fs-4 me-1"></i> Download PDF
          </a>
          <div class="btn btn-icon btn-sm btn-active-light-danger" data-bs-dismiss="modal">
            <i class="ki-outline ki-cross fs-1"></i>
          </div>
        </div>
      </div>
      <div class="modal-body p-0 bg-secondary">
        <div id="pdfLoadingContainer" class="d-flex flex-column justify-content-center align-items-center h-100">
          <span class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;"></span>
          <span class="text-muted fw-bold">Memuat dokumen PDF...</span>
        </div>
        <iframe id="pdfViewer" class="d-none" src="" style="width: 100%; height: 100%; border: none;"></iframe>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://www.youtube.com/iframe_api"></script>
<script>
  /* ==========================================================================
     1. VARIABEL GLOBAL & PENGATURAN AWAL
     ========================================================================== */
  document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
  });

  document.onkeydown = function(e) {
    if (e.keyCode === 123) return false;
    if (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74 || e.keyCode === 67)) return false;
    if (e.ctrlKey && e.keyCode === 85) return false;
    if (e.ctrlKey && e.keyCode === 83) return false;
  };

  let player;
  let idleTimer;
  const track = document.getElementById('carouselTrack');

  let currentCsrfHash = '<?= csrf_hash() ?>';
  const csrfName = '<?= csrf_token() ?>';

  // Variabel untuk Chat & Tagging
  let lastDisplayedId = 0;
  let editingMessageId = null;
  let activeParticipants = [];

  /* ==========================================================================
     2. FUNGSI UTILITIES (AJAX, WAKTU, & TAMPILAN)
     ========================================================================== */
     
  // Menyamakan tinggi chat panel dengan video container di layar desktop
  function syncChatHeight() {
    if (window.innerWidth >= 1200) {
      const videoCard = $('#videoContainer').closest('.card');
      const chatCard = $('.chat-card-container');
      const videoHeight = videoCard.outerHeight();

      if (videoHeight > 300) {
        chatCard.css('height', videoHeight + 'px');
        const chatHeaderHeight = chatCard.find('.card-header').outerHeight(true) || 60;
        const chatFooterHeight = chatCard.find('.card-footer').outerHeight(true) || 120;
        const chatBody = chatCard.find('.card-body');
        const pt = parseFloat(chatBody.css('padding-top')) || 16;
        const pb = parseFloat(chatBody.css('padding-bottom')) || 16;

        const availableInner = videoHeight - chatHeaderHeight - chatFooterHeight - pt - pb;

        if (availableInner > 180) {
          $('.inner-chat-materi').css({
            'height': availableInner + 'px',
            'max-height': availableInner + 'px'
          });
        }
      }
    } else {
      const chatCard = $('.chat-card-container');
      chatCard.css('height', 'auto');
      $('.inner-chat-materi').css({
        'height': '380px',
        'max-height': '380px'
      });
    }
  }

  window.addEventListener('resize', syncChatHeight);

  function updateCsrfToken(newToken) {
    if (newToken && newToken !== currentCsrfHash) {
      currentCsrfHash = newToken;
      $('meta[name="csrf-token"]').attr('content', newToken);
    }
  }

  $.ajaxSetup({
    beforeSend: function(xhr, settings) {
      if (settings.type === 'POST') {
        xhr.setRequestHeader('X-CSRF-TOKEN', currentCsrfHash);
        if (typeof settings.data === 'object' && settings.data !== null) {
          settings.data[csrfName] = currentCsrfHash;
        } else if (typeof settings.data === 'string') {
          if (!settings.data.includes(csrfName)) {
            settings.data += (settings.data ? '&' : '') + csrfName + '=' + currentCsrfHash;
          }
        }
      }
    }
  });

  function formatTime(seconds) {
    if (isNaN(seconds)) return "00:00";
    const h = Math.floor(seconds / 3600);
    const m = Math.floor((seconds % 3600) / 60);
    const s = Math.floor(seconds % 60);
    const formattedM = m.toString().padStart(2, '0');
    const formattedS = s.toString().padStart(2, '0');
    return h > 0 ? `${h}:${formattedM}:${formattedS}` : `${formattedM}:${formattedS}`;
  }

  function updateRangeFill(el) {
    if (!el) return;
    const val = el.value;
    const min = el.min || 0;
    const max = el.max || 100;
    const percentage = ((val - min) / (max - min)) * 100;
    el.style.background = `linear-gradient(to right, #0086a7 0%, #0086a7 ${percentage}%, rgba(255, 255, 255, 0.25) ${percentage}%, rgba(255, 255, 255, 0.25) 100%)`;
  }

  updateRangeFill(document.getElementById('progressBar'));
  updateRangeFill(document.getElementById('volumeBar'));

  /* ==========================================================================
     3. FUNGSI AUTO-HIDE KONTROL & KURSOR SAAT IDLE
     ========================================================================== */
  function resetIdleTimer() {
    const videoContainer = $('#videoContainer');
    videoContainer.removeClass('video-idle');
    clearTimeout(idleTimer);

    if (player && player.getPlayerState && player.getPlayerState() === YT.PlayerState.PLAYING) {
      idleTimer = setTimeout(() => {
        videoContainer.addClass('video-idle');
      }, 2500);
    }
  }

  /* ==========================================================================
     4. LOGIKA YOUTUBE PLAYER & KONTROL VIDEO
     ========================================================================== */
  function trackProgress() {
    if (player && player.getPlayerState) {
      if (player.getPlayerState() === YT.PlayerState.PLAYING) {
        const duration = player.getDuration();
        const current = player.getCurrentTime();

        if (duration > 0) {
          const percentage = (current / duration) * 100;
          const pb = document.getElementById('progressBar');
          if (pb) {
            pb.value = percentage;
            updateRangeFill(pb);
          }
          const td = document.getElementById('timeDisplay');
          if (td) {
            td.innerText = `${formatTime(current)} / ${formatTime(duration)}`;
          }
        }
        requestAnimationFrame(trackProgress);
      }
    }
  }

  function onYouTubeIframeAPIReady() {
    player = new YT.Player('mainVideo', {
      events: {
        'onReady': onPlayerReady,
        'onStateChange': onPlayerStateChange
      }
    });
  }

  function onPlayerReady(event) {
    const firstThumb = $('.video-thumb').first();
    if (firstThumb.length) {
      if (!$('.video-thumb.border-primary').length) {
        firstThumb.addClass('border border-primary border-3');
      }
      const initialTitle = firstThumb.data('title');
      const initialKode = firstThumb.data('kode_materi');
      $('#judul_materi_chat').text(initialTitle);
      $('#kode_materi').val(initialKode);
      $('#active_video_title').text(initialTitle);
      $('#active_video_title_sub').text(initialTitle);
      $('#active_video_code_badge').html('<i class="ki-outline ki-code fs-8 me-1"></i> ' + initialKode);

      lastDisplayedId = 0;
      activeParticipants = [];
      editingMessageId = null;

      get_chat_materi(true);
      loadFileMateri(initialKode);

      setTimeout(syncChatHeight, 400);
    }
  }

  function onPlayerStateChange(event) {
    const containerCenter = $('#centerIconContainer');

    if (event.data === YT.PlayerState.UNSTARTED || event.data === YT.PlayerState.CUED) {
      containerCenter.removeClass('show-icon');
    } else {
      containerCenter.addClass('show-icon');
    }

    if (event.data === YT.PlayerState.PLAYING) {
      $('#iconPlay, #centerIconPlay').addClass('d-none');
      $('#iconPause, #centerIconPause').removeClass('d-none');

      resetIdleTimer();
      requestAnimationFrame(trackProgress);
    } else if (event.data === YT.PlayerState.PAUSED) {
      $('#iconPause, #centerIconPause').addClass('d-none');
      $('#iconPlay, #centerIconPlay').removeClass('d-none');

      clearTimeout(idleTimer);
      $('#videoContainer').removeClass('video-idle');
    }

    if (event.data === YT.PlayerState.ENDED) {
      const current = $('.video-thumb.border-primary');
      const next = current.next('.video-thumb');
      
      if (next.length > 0) {
        next.click();
        
        // Gulir playlist secara halus
        const trackElement = document.getElementById('carouselTrack');
        const scrollPos = next[0].offsetLeft - (trackElement.offsetWidth / 2) + (next[0].offsetWidth / 2);
        trackElement.scrollTo({
          left: scrollPos,
          behavior: 'smooth'
        });

        // Kembalikan fokus pandangan ke video utama
        document.getElementById('videoContainer').scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
        
      } else {
        const firstThumb = $('.video-thumb').first();
        $('.video-thumb').removeClass('border border-primary border-3');
        firstThumb.addClass('border border-primary border-3');
        
        const firstTitle = firstThumb.data('title');
        const firstKode = firstThumb.data('kode_materi');
        $('#judul_materi_chat').text(firstTitle);
        $('#kode_materi').val(firstKode);
        $('#active_video_title').text(firstTitle);
        $('#active_video_title_sub').text(firstTitle);
        $('#active_video_code_badge').html('<i class="ki-outline ki-code fs-8 me-1"></i> ' + firstKode);
        
        track.scrollTo({ left: 0, behavior: 'smooth' });

        document.getElementById('videoContainer').scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });

        if (player && player.cueVideoById) {
          player.cueVideoById({
            videoId: firstThumb.data('videoid')
          });
        }

        lastDisplayedId = 0;
        activeParticipants = [];
        editingMessageId = null;
        get_chat_materi(true);
        loadFileMateri(firstKode);
      }
    }
  }

  /* ==========================================================================
     5. EVENT LISTENERS UTAMA
     ========================================================================== */
  $(document).ready(function() {
    
    setTimeout(syncChatHeight, 500);

    $('#videoContainer').on('mousemove mousedown touchstart keydown', function() {
      resetIdleTimer();
    });

    $('#videoContainer').on('mouseleave', function() {
      if (player && player.getPlayerState && player.getPlayerState() === YT.PlayerState.PLAYING) {
        clearTimeout(idleTimer);
      }
    });

    $('#playPauseOverlay, #btnPlayPause').on('click', function() {
      if (player && player.getPlayerState) {
        if (player.getPlayerState() === YT.PlayerState.PLAYING) {
          player.pauseVideo();
        } else {
          player.playVideo();
        }
      }
    });

    $('#progressBar').on('input', function() {
      updateRangeFill(this);
      if (player && player.getDuration) {
        const duration = player.getDuration();
        const seekToTime = (this.value / 100) * duration;
        player.seekTo(seekToTime, true);
        document.getElementById('timeDisplay').innerText = `${formatTime(seekToTime)} / ${formatTime(duration)}`;
      }
    });

    $('#volumeBar').on('input', function() {
      updateRangeFill(this);
      const volVal = this.value;
      if (volVal == 0) {
        $('#iconVolumeUp').addClass('d-none');
        $('#iconVolumeMute').removeClass('d-none');
      } else {
        $('#iconVolumeMute').addClass('d-none');
        $('#iconVolumeUp').removeClass('d-none');
      }
      if (player && player.setVolume) player.setVolume(volVal);
    });

    $('#btnFullscreen').on('click', function() {
      const videoContainer = document.getElementById('videoContainer');
      if (!document.fullscreenElement) {
        if (videoContainer.requestFullscreen) videoContainer.requestFullscreen();
        else if (videoContainer.webkitRequestFullscreen) videoContainer.webkitRequestFullscreen();
        else if (videoContainer.msRequestFullscreen) videoContainer.msRequestFullscreen();
      } else {
        if (document.exitFullscreen) document.exitFullscreen();
        else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
        else if (document.msExitFullscreen) document.msExitFullscreen();
      }
    });

    $(document).on('click', '.video-thumb', function() {
      const videoId = $(this).data('videoid');
      const kode_materi = $(this).data('kode_materi');
      const title = $(this).data('title');

      $('.video-thumb').removeClass('border border-primary border-3');
      $(this).addClass('border border-primary border-3');
      $('#kode_materi').val(kode_materi);
      $('#judul_materi_chat').text(title);
      $('#active_video_title').text(title);
      $('#active_video_title_sub').text(title);
      $('#active_video_code_badge').html('<i class="ki-outline ki-code fs-8 me-1"></i> ' + kode_materi);

      // Tandai video telah dilihat
      $.ajax({
          url: "<?= base_url('sw-siswa/materi/mark-viewed') ?>",
          type: "POST",
          data: { 
            kode_materi: kode_materi,
            idmapel: "<?= $idmapel ?>",
            idkelas: "<?= $idkelas ?>"
          },
          dataType: "JSON",
          success: function(res) {
              if (res.csrf) updateCsrfToken(res.csrf);
              $('#check-' + kode_materi).removeClass('d-none').hide().fadeIn(300);
          }
      });

      // Reset Chat Panel saat pindah video
      lastDisplayedId = 0;
      activeParticipants = [];
      editingMessageId = null;
      $('#btn_cancel_edit').addClass('d-none');
      $('#msg_input').val('').attr('placeholder', 'Tulis pertanyaan Anda... (Gunakan @ untuk mention)');
      $('.inner-chat-materi').html('<div class="d-flex justify-content-center py-10"><span class="spinner-border text-primary"></span></div>');

      get_chat_materi(true);
      loadFileMateri(kode_materi);

      if (player && player.loadVideoById) {
        player.loadVideoById({
          videoId: videoId
        });
      }
    });

    $('#nextBtn').on('click', () => track.scrollBy({
      left: 250,
      behavior: 'smooth'
    }));
    $('#prevBtn').on('click', () => track.scrollBy({
      left: -250,
      behavior: 'smooth'
    }));

    // Batal Edit Pesan
    $('#btn_cancel_edit').on('click', function() {
      editingMessageId = null;
      $('#msg_input').val('').prop('disabled', false).focus();
      $('#msg_input').attr('placeholder', 'Tulis pertanyaan Anda... (Gunakan @ untuk mention)');
      $(this).addClass('d-none');
    });

    // Enter Key untuk Kirim (Shift+Enter untuk baris baru)
    $('#msg_input').on('keydown', function(e) {
      if (e.which == 13 && !e.shiftKey) {
        e.preventDefault();
        if ($('#mention_dropdown').is(':visible')) {
          const firstItem = $('#mention_dropdown .mention-item').first();
          if (firstItem.length > 0) {
            firstItem.click();
            return false;
          }
        }
        $('#chat_materi').click();
      }
    });

    // Mention Dropdown Autocomplete
    $('#msg_input').on('keyup input', handleMentionInput);

    $(document).on('click', function(e) {
      if (!$(e.target).closest('#kt_chat_messenger_footer').length) {
        $('#mention_dropdown').hide();
      }
    });

    // Kirim Pesan / Update Edit Pesan
    $('#chat_materi').off('click').on('click', function() {
      const btn = $(this);
      const textarea = $('#msg_input');
      const chat_text = textarea.val().trim();
      const kode_materi = $('#kode_materi').val();
      const link = '<?= $link ?? "" ?>';
      const linkadmin = '<?= $linkadmin ?? "" ?>';

      if (chat_text !== '') {
        $('#mention_dropdown').hide();
        btn.prop('disabled', true);
        textarea.prop('disabled', true);

        if (editingMessageId) {
          // PROSES UPDATE CHAT
          $.ajax({
            url: "<?= base_url('sw-siswa/materi/update-chat-materi') ?>",
            method: "POST",
            dataType: "json",
            data: {
              id_chat: editingMessageId,
              text: chat_text
            },
            success: function(res) {
              if (res && res.token) updateCsrfToken(res.token);

              $('#msg-text-' + editingMessageId).html(formatMessage(chat_text));
              $('#btn-edit-' + editingMessageId).attr('onclick', `editMessage('${editingMessageId}', '${encodeURIComponent(chat_text)}')`);

              editingMessageId = null;
              $('#btn_cancel_edit').addClass('d-none');
              textarea.attr('placeholder', 'Tulis pertanyaan Anda... (Gunakan @ untuk mention)');
              textarea.val('').prop('disabled', false).focus();
            },
            error: function(xhr) {
              let errMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Gagal mengedit pesan.";
              Swals.alert("Gagal", errMsg, "error");
              textarea.prop('disabled', false);
            },
            complete: function() {
              btn.prop('disabled', false);
            }
          });
        } else {
          // PROSES INSERT CHAT BARU
          $.ajax({
            url: "<?= base_url('sw-siswa/materi/chat-materi') ?>",
            method: "POST",
            dataType: "json",
            data: {
              chat_materi: chat_text,
              kode_materi: kode_materi,
              link: link,
              linkadmin: linkadmin,
            },
            success: function(res) {
              if (res && res.token) updateCsrfToken(res.token);
              textarea.val('').prop('disabled', false).focus();
              get_chat_materi(true);
            },
            error: function(xhr) {
              Swals.alert("Gagal", "Gagal mengirim pesan, silakan coba lagi.", "error");
              textarea.prop('disabled', false);
            },
            complete: function() {
              btn.prop('disabled', false);
            }
          });
        }
      }
    });

    setInterval(() => get_chat_materi(false), 5000);

  });

  /* ==========================================================================
     6. FUNGSI AJAX BANTUAN MATERI & CHAT
     ========================================================================== */
  function loadFileMateri(kode) {
    const container = $('#file-list-container');
    container.html('<div class="text-center py-10"><span class="spinner-border text-primary"></span></div>');
    $.post("<?= base_url('sw-siswa/materi/get-file-materi') ?>", {
      kode_materi: kode
    }, function(res) {
      container.html(res);
      const count = container.find('.btn-view-pdf').length;
      if (count > 0) {
        $('#fileCountBadge').text(count).show();
        $('#fileCountBadgeSub').text(count).show();
      } else {
        $('#fileCountBadge').hide();
        $('#fileCountBadgeSub').hide();
      }
    }).fail(function() {
      container.html('<div class="text-center text-danger">Gagal memuat file materi.</div>');
      $('#fileCountBadge').hide();
      $('#fileCountBadgeSub').hide();
    });
  }

  function get_chat_materi(shouldScroll = false) {
    const kode_materi = $('#kode_materi').val();
    if (!kode_materi) return;

    $.ajax({
      url: "<?= base_url('sw-siswa/materi/get-chat-materi') ?>",
      method: "POST",
      dataType: "json",
      data: {
        kode_materi: kode_materi,
        last_id: lastDisplayedId
      },
      success: function(res) {
        if (res && res.token) updateCsrfToken(res.token);

        if (lastDisplayedId === 0) {
          $('.inner-chat-materi').html('');
        }

        if (res.participants && Array.isArray(res.participants)) {
          res.participants.forEach(p => {
            if (p && !activeParticipants.includes(p)) activeParticipants.push(p);
          });
        }

        const data = res.messages;
        if (data && data.length > 0) {
          let html = '';
          let hasNewMessage = false;
          const myEmail = '<?= session()->get('email') ?>';
          const currentTime = Math.floor(Date.now() / 1000);

          data.forEach(m => {
            const msgId = parseInt(m.id_chat_materi);
            const senderName = m.nama && m.nama.trim() !== '' ? m.nama : 'Peserta';

            if (m.nama && !activeParticipants.includes(m.nama)) activeParticipants.push(m.nama);

            if (msgId > lastDisplayedId) {
              lastDisplayedId = msgId;
              hasNewMessage = true;

              const isMe = (m.email && myEmail && m.email.toLowerCase() === myEmail.toLowerCase());
              const date = new Date(m.date_created * 1000);
              const timeStr = date.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
              });
              
              const defaultAvatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(senderName)}&background=0086a7&color=fff&bold=true`;
              const avatarUrl = m.gambar && m.gambar !== 'default.png' 
                ? '<?= base_url('assets/app-assets/user/') ?>' + m.gambar 
                : defaultAvatar;

              if (isMe) {
                const timeDiff = currentTime - m.date_created;
                let actionButtons = '';

                // Batas waktu edit/hapus 5 Menit
                if (timeDiff <= 300) {
                  actionButtons = `
                    <div class="mt-1 me-2">
                      <span id="btn-edit-${msgId}" class="badge badge-light-primary badge-sm cursor-pointer text-hover-primary me-1" onclick="editMessage('${msgId}', '${encodeURIComponent(m.text)}')">
                        <i class="ki-outline ki-pencil fs-9 me-1"></i>Edit
                      </span>
                      <span class="badge badge-light-danger badge-sm cursor-pointer text-hover-danger" onclick="deleteMessage('${msgId}', ${m.date_created})">
                        <i class="ki-outline ki-trash fs-9 me-1"></i>Hapus
                      </span>
                    </div>`;
                }

                html += `
                  <div class="d-flex justify-content-end mb-3" id="chat-item-${msgId}">
                    <div class="d-flex flex-column align-items-end" style="max-width: 88%;">
                      <div class="d-flex align-items-center mb-1">
                        <span class="text-muted fs-9 me-2">${timeStr}</span>
                        <span class="fs-8 fw-bold text-gray-900 me-2">Anda</span>
                        <div class="symbol symbol-25px symbol-circle">
                          <img alt="${senderName}" src="${avatarUrl}" onerror="this.src='${defaultAvatar}'" />
                        </div>
                      </div>
                      <div class="p-3 chat-bubble-me text-gray-800 fw-semibold text-start chat-message-text fs-7" id="msg-text-${msgId}">
                        ${formatMessage(m.text)}
                      </div>
                      ${actionButtons}
                    </div>
                  </div>`;
              } else {
                html += `
                  <div class="d-flex justify-content-start mb-3">
                    <div class="d-flex flex-column align-items-start" style="max-width: 88%;">
                      <div class="d-flex align-items-center mb-1">
                        <div class="symbol symbol-25px symbol-circle me-2">
                          <img alt="${senderName}" src="${avatarUrl}" onerror="this.src='${defaultAvatar}'" />
                        </div>
                        <span class="fs-8 fw-bold text-gray-900 me-2">${senderName}</span>
                        <span class="text-muted fs-9">${timeStr}</span>
                      </div>
                      <div class="p-3 chat-bubble-other text-gray-800 fw-semibold text-start chat-message-text fs-7">
                        ${formatMessage(m.text)}
                      </div>
                      <div class="mt-1 ms-2">
                        <span class="badge badge-light badge-sm cursor-pointer text-hover-primary" onclick="replyTo('${senderName}')">
                          <i class="ki-outline ki-left fs-9 me-1"></i>Balas
                        </span>
                      </div>
                    </div>
                  </div>`;
              }
            }
          });

          if (hasNewMessage) {
            if ($('.inner-chat-materi').find('.text-muted.py-12').length > 0) {
              $('.inner-chat-materi').html('');
            }
            $('.inner-chat-materi').append(html);
            if (shouldScroll) {
              const chatContainer = $('.inner-chat-materi');
              chatContainer.scrollTop(chatContainer[0].scrollHeight);
            }
          }
        } else if (lastDisplayedId === 0) {
          $('.inner-chat-materi').html(`
            <div class="text-center text-muted py-12">
              <div class="symbol symbol-50px symbol-circle bg-light-primary mb-3">
                <i class="ki-outline ki-messages fs-2tx text-primary"></i>
              </div>
              <p class="fs-7 fw-semibold text-gray-700 mb-1">Belum ada diskusi untuk video ini</p>
              <span class="fs-8 text-muted">Mulai ajukan pertanyaan atau diskusikan materi bersama instruktur!</span>
            </div>
          `);
        }
      }
    });
  }

  // --- Formatting, Mentions, Balas, Edit, & Delete ---

  function formatMessage(text) {
    if (!text) return "";
    text = text.replace(/(?:\r\n|\r|\n)/g, '<br>');
    var urlRegex = /(https?:\/\/[^\s]+)/g;
    text = text.replace(urlRegex, url => `<a href="${url}" target="_blank" class="fw-bold text-primary text-hover-dark">${url}</a>`);
    var mentionRegex = /@\[(.*?)\]/g;
    text = text.replace(mentionRegex, `<span class="badge badge-light-primary text-primary fw-bold px-2 py-1 me-1"><i class="ki-outline ki-profile-circle text-primary me-1"></i>@$1</span>`);
    return text;
  }

  function replyTo(nama) {
    const input = $('#msg_input');
    const currentText = input.val();
    input.val(`${currentText} @[${nama}] `);
    input.focus();
  }

  function handleMentionInput(e) {
    const input = $(this);
    const val = input.val();
    const cursorPosition = input[0].selectionStart;
    const textBeforeCursor = val.substring(0, cursorPosition);
    const lastAtIndex = textBeforeCursor.lastIndexOf('@');

    if (lastAtIndex !== -1) {
      const query = textBeforeCursor.substring(lastAtIndex + 1);
      if (!query.includes(']') && !query.includes('\n')) {
        const matches = activeParticipants.filter(name =>
          name.toLowerCase().includes(query.toLowerCase())
        );
        if (matches.length > 0) {
          showMentionDropdown(matches, lastAtIndex, cursorPosition);
          return;
        }
      }
    }
    $('#mention_dropdown').hide();
  }

  function showMentionDropdown(matches, atIndex, cursorPosition) {
    const dropdown = $('#mention_dropdown');
    let html = '';
    matches.forEach(name => {
      html += `
        <div class="mention-item d-flex align-items-center" onclick="insertMention('${name.replace(/'/g, "\\'")}', ${atIndex}, ${cursorPosition})">
          <div class="symbol symbol-25px symbol-circle me-2">
            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=0086a7&color=fff" alt="Avatar">
          </div>
          <span class="fw-bold fs-7 text-gray-800">${name}</span>
        </div>`;
    });
    dropdown.html(html).show();
  }

  function insertMention(name, atIndex, cursorPosition) {
    const input = $('#msg_input');
    const val = input.val();
    const beforeAt = val.substring(0, atIndex);
    const afterCursor = val.substring(cursorPosition);
    const newVal = `${beforeAt}@[${name}] ${afterCursor}`;
    input.val(newVal);
    $('#mention_dropdown').hide();
    input.focus();
  }

  function editMessage(id, encodedText) {
    editingMessageId = id;
    const decText = decodeURIComponent(encodedText).replace(/<br\s*[\/]?>/gi, '\n');
    $('#msg_input').val(decText).focus();
    $('#msg_input').attr('placeholder', 'Mengedit pesan...');
    $('#btn_cancel_edit').removeClass('d-none');
  }

  function deleteMessage(id, timeStamp) {
    const currentTime = Math.floor(Date.now() / 1000);
    const timeDiff = currentTime - timeStamp;

    if (timeDiff > 300) {
      Swal.fire({
        icon: 'warning',
        title: 'Ditolak!',
        text: 'Pesan yang dikirim lebih dari 5 menit yang lalu tidak dapat dihapus.',
        confirmButtonText: 'OK'
      });
      return;
    }

    Swal.fire({
      title: 'Hapus Pesan?',
      text: 'Pesan ini akan dihapus secara permanen dari diskusi.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Menghapus...',
          text: 'Memproses penghapusan pesan',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        let postData = {
          id_chat: id
        };
        postData[csrfName] = currentCsrfHash;

        $.post(`<?= base_url('sw-siswa/materi/delete-chat-materi') ?>`, postData, function(res) {
          if (res && res.token) updateCsrfToken(res.token);

          Swal.close();

          $('#chat-item-' + id).fadeOut(300, function() {
            $(this).remove();
          });
        }).fail(function(xhr) {
          Swal.close();
          let errMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Gagal menghapus pesan.";
          Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: errMsg,
            confirmButtonText: 'OK'
          });
        });
      }
    });
  }
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var pdfPreviewModal = document.getElementById('pdfPreviewModal');
    var pdfViewer = document.getElementById('pdfViewer');
    var pdfLoading = document.getElementById('pdfLoadingContainer');
    var pdfTitle = document.getElementById('pdfModalTitle');
    var pdfDownloadBtn = document.getElementById('pdfDownloadBtn');

    if (pdfPreviewModal) {
      pdfPreviewModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var fileUrl = button.getAttribute('data-file-url');
        var fileName = button.getAttribute('data-file-name');

        pdfTitle.textContent = fileName;
        pdfDownloadBtn.href = fileUrl;

        pdfViewer.classList.remove('d-block');
        pdfViewer.classList.add('d-none');
        pdfLoading.classList.remove('d-none');
        pdfLoading.classList.add('d-flex');
        pdfViewer.src = fileUrl;

        pdfViewer.onload = function() {
          pdfLoading.classList.remove('d-flex');
          pdfLoading.classList.add('d-none');
          pdfViewer.classList.remove('d-none');
          pdfViewer.classList.add('d-block');
        };
      });

      pdfPreviewModal.addEventListener('hidden.bs.modal', function() {
        pdfViewer.src = '';
        pdfTitle.textContent = 'Preview Dokumen';
      });
    }
  });
</script>
<?= $this->endSection(); ?>

<?= $this->extend('siswa/template/app'); ?>

<?= $this->section('styles'); ?>
<style>
  /* --- WRAPPER UTAMA --- */
  .secure-video-wrapper {
    position: relative;
    width: 100%;
    user-select: none;
    -webkit-user-select: none;
    background-color: #000;
    overflow: hidden;
  }

  /* Blokir total interaksi mouse asli ke youtube */
  #mainVideo {
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

  .custom-range::-moz-range-thumb {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #ffffff;
    cursor: pointer;
    border: none;
    transition: transform 0.1s;
  }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid py-4 py-lg-6 mt-8">
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

      <div class="row g-5 g-xl-10">
        <div class="col-xl-8">
          <div class="card card-flush shadow-sm mb-5 mb-xl-10 overflow-hidden animate__animated animate__fadeIn">
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

                <div class="video-click-overlay" id="playPauseOverlay"></div>

                <div class="center-icon-overlay" id="centerIconContainer">
                  <svg id="centerIconPlay" xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="#ffffff" viewBox="0 0 16 16" style="margin-left: 6px;">
                    <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z" />
                  </svg>
                  <svg id="centerIconPause" class="d-none" xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="#ffffff" viewBox="0 0 16 16">
                    <path d="M5.5 3.5A1.5 1.5 0 0 1 7 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5zm5 0A1.5 1.5 0 0 1 12 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5z" />
                  </svg>
                </div>

                <div class="yt-logo-mask"></div>

                <iframe id="mainVideo"
                  src="https://www.youtube.com/embed/<?= $firstVideoId ?>?enablejsapi=1&rel=0&controls=0&disablekb=1&modestbranding=1&autoplay=1&iv_load_policy=3"
                  allow="autoplay; encrypted-media"
                  allowfullscreen>
                </iframe>

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

          <div class="alert alert-dismissible bg-light-danger d-flex flex-column flex-sm-row p-5 mb-10 border border-danger border-dashed">
            <i class="ki-outline ki-information-5 fs-2hx text-danger me-4 mb-5 mb-sm-0"></i>
            <div class="d-flex flex-column pe-0 pe-sm-10">
              <h5 class="mb-1 text-danger fw-bold">Peringatan Hak Cipta!</h5>
              <span class="fs-7 text-gray-800">Dilarang keras melakukan penyebaran atau penggandaan video pembelajaran ini tanpa seizin tertulis dari pemilik konten. Pelanggaran akan dikenakan sanksi hukum.</span>
            </div>
          </div>
        </div>

        <div class="col-xl-4">
          <div class="card card-flush shadow-sm h-xl-100 animate__animated animate__fadeInRight">
            <div class="card-header pt-7 bg-primary">
              <div class="card-title d-flex align-items-center justify-content-between w-100 m-0">
                <div class="d-flex flex-column pe-3" style="max-width: 85%;">
                  <span class="card-label fw-bold text-white fs-4 lh-1 mb-1">Diskusi Materi</span>
                  <span class="text-white opacity-75 fw-semibold fs-7 text-truncate" id="judul_materi_chat" style="max-width: 100%;">
                    Tanyakan hal yang belum dimengerti
                  </span>
                </div>
                <div class="card-toolbar">
                  <button type="button" class="btn btn-sm btn-icon btn-color-white btn-active-color-primary btn-outline btn-outline-white border-dashed btn-active-white" data-bs-toggle="modal" data-bs-target="#staticBackdrop" title="Download File Materi">
                    <i class="ki-outline ki-file-up fs-2 text-white"></i>
                  </button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="scroll-y me-n5 pe-5 h-300px h-lg-400px inner-chat-materi">
                <div class="d-flex justify-content-center py-10">
                  <span class="spinner-border text-primary"></span>
                </div>
              </div>
            </div>
            <div class="card-footer pt-4" id="kt_chat_messenger_footer">
              <input type="hidden" name="kode_materi" id="kode_materi" value="">
              <textarea name="text" class="form-control form-control-flush mb-3" rows="1" data-kt-element="input" placeholder="Tulis pesan..."></textarea>
              <div class="d-flex flex-stack">
                <div class="d-flex align-items-center me-2">
                  <small id="informasi" class="text-danger"></small>
                </div>
                <button id="chat_materi" class="btn btn-primary" type="button" data-kt-element="send">Kirim</button>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 mt-5">
          <div class="card card-flush shadow-sm animate__animated animate__fadeInUp">
            <div class="card-header border-0 pt-5">
              <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold text-dark">Playlist Pembelajaran</span>
                <span class="text-muted mt-1 fw-semibold fs-7">Klik untuk berpindah materi video</span>
              </h3>
              <div class="card-toolbar">
                <button class="btn btn-icon btn-sm btn-light-primary me-2" id="prevBtn"><i class="ki-outline ki-left fs-2"></i></button>
                <button class="btn btn-icon btn-sm btn-light-primary" id="nextBtn"><i class="ki-outline ki-right fs-2"></i></button>
              </div>
            </div>
            <div class="card-body">
              <div class="d-flex flex-nowrap overflow-auto pb-5 scroll-track" id="carouselTrack" style="scrollbar-width: none; -ms-overflow-style: none;">
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

                foreach ($allPlaylist as $index => $item):
                  if (preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $item['vid'], $m)) {
                    $vId = $m[1];
                  } else {
                    $vId = trim($item['vid']);
                  }
                ?>
                  <div class="video-thumb me-5 flex-shrink-0 cursor-pointer <?= $index == 0 ? 'border border-primary border-3' : '' ?>"
                    style="width: 200px; transition: all 0.3s;"
                    data-videoid="<?= $vId ?>"
                    data-kode_materi="<?= $item['kode'] ?>"
                    data-title="<?= esc($item['title']) ?>">
                    <div class="overlay-wrapper mb-3">
                      <div class="overlay-layer bg-dark bg-opacity-10 rounded-3">
                        <img src="https://img.youtube.com/vi/<?= $vId ?>/hqdefault.jpg" class="w-100 rounded-3 shadow-sm hover-elevate-up" alt="">
                      </div>
                    </div>
                    <div class="m-0">
                      <span class="text-gray-800 fw-bold text-hover-primary fs-7 d-block"><?= esc($item['title']) ?></span>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-0">
        <h3 class="modal-title fw-bold">Download Materi Softcopy</h3>
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
          <i class="ki-outline ki-cross fs-1"></i>
        </div>
      </div>
      <div class="modal-body">
        <div id="file-list-container">
          <div class="text-center py-5">
            <span class="spinner-border text-primary"></span>
          </div>
        </div>
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

  // 2. Blokir Shortcut Keyboard Developer Tools
  document.onkeydown = function(e) {
    // Blokir F12
    if (e.keyCode === 123) {
      return false;
    }
    // Blokir Ctrl+Shift+I (Inspect Element) & Ctrl+Shift+J (Console) & Ctrl+Shift+C (Element Selector)
    if (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74 || e.keyCode === 67)) {
      return false;
    }
    // Blokir Ctrl+U (View Page Source)
    if (e.ctrlKey && e.keyCode === 85) {
      return false;
    }
    // Blokir Ctrl+S (Save Page)
    if (e.ctrlKey && e.keyCode === 83) {
      return false;
    }
  };
  let player;
  let idleTimer; // Timer untuk auto-hide controls
  const track = document.getElementById('carouselTrack');
  let currentCsrfHash = '<?= csrf_hash() ?>';
  const csrfName = '<?= csrf_token() ?>';

  /* ==========================================================================
     2. FUNGSI UTILITIES (AJAX, WAKTU, & TAMPILAN)
     ========================================================================== */
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
    const val = el.value;
    const min = el.min || 0;
    const max = el.max || 100;
    const percentage = ((val - min) / (max - min)) * 100;
    el.style.background = `linear-gradient(to right, #ffffff 0%, #ffffff ${percentage}%, rgba(255, 255, 255, 0.2) ${percentage}%, rgba(255, 255, 255, 0.2) 100%)`;
  }

  // Set initial colors
  updateRangeFill(document.getElementById('progressBar'));
  updateRangeFill(document.getElementById('volumeBar'));


  /* ==========================================================================
     3. FUNGSI AUTO-HIDE KONTROL & KURSOR
     ========================================================================== */
  function resetIdleTimer() {
    const videoContainer = $('#videoContainer');
    
    // Tampilkan kembali kontrol & kursor dengan menghapus class video-idle
    videoContainer.removeClass('video-idle');
    clearTimeout(idleTimer);

    // Set ulang timer hanya jika video sedang PLAYING
    if (player && player.getPlayerState && player.getPlayerState() === YT.PlayerState.PLAYING) {
      idleTimer = setTimeout(() => {
        videoContainer.addClass('video-idle');
      }, 2500); // Kursor/kontrol hilang setelah 2.5 detik diam
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
          pb.value = percentage; 
          updateRangeFill(pb); 
          document.getElementById('timeDisplay').innerText = `${formatTime(current)} / ${formatTime(duration)}`;
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

      get_chat_materi(true);
      loadFileMateri(initialKode);
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
      
      resetIdleTimer(); // Mulai timer sembunyi kontrol
      requestAnimationFrame(trackProgress);
    } 
    else if (event.data === YT.PlayerState.PAUSED) {
      $('#iconPause, #centerIconPause').addClass('d-none');
      $('#iconPlay, #centerIconPlay').removeClass('d-none');
      
      // Saat Pause, kontrol harus tetap muncul
      clearTimeout(idleTimer);
      $('#videoContainer').removeClass('video-idle');
    }

    if (event.data === YT.PlayerState.ENDED) {
      const current = $('.video-thumb.border-primary');
      const next = current.next('.video-thumb');
      if (next.length > 0) {
        next.click();
        next[0].scrollIntoView({ behavior: 'smooth', inline: 'center' });
      } else {
        const firstThumb = $('.video-thumb').first();
        $('.video-thumb').removeClass('border border-primary border-3');
        firstThumb.addClass('border border-primary border-3');
        $('#judul_materi_chat').text(firstThumb.data('title'));
        $('#kode_materi').val(firstThumb.data('kode_materi'));
        track.scrollTo({ left: 0, behavior: 'smooth' });
        
        if (player && player.cueVideoById) {
          player.cueVideoById({ videoId: firstThumb.data('videoid') });
        }
        get_chat_materi(true);
        loadFileMateri(firstThumb.data('kode_materi'));
      }
    }
  }


  /* ==========================================================================
     5. EVENT LISTENERS UTAMA
     ========================================================================== */
  $(document).ready(function() {
    
    // -- Deteksi Pergerakan Mouse untuk Fitur Auto-Hide --
    $('#videoContainer').on('mousemove mousedown touchstart keydown', function() {
      resetIdleTimer();
    });

    $('#videoContainer').on('mouseleave', function() {
      if (player && player.getPlayerState && player.getPlayerState() === YT.PlayerState.PLAYING) {
        clearTimeout(idleTimer);
        // Kontrol akan otomatis tersembunyi karena mouse tidak lagi berada di elemen (:hover css native hilang)
      }
    });

    // -- Tombol Play/Pause Overlay --
    $('#playPauseOverlay, #btnPlayPause').on('click', function() {
      if (player && player.getPlayerState) {
        if (player.getPlayerState() === YT.PlayerState.PLAYING) {
          player.pauseVideo();
        } else {
          player.playVideo();
        }
      }
    });

    // -- Progress Bar (Timeline) --
    $('#progressBar').on('input', function() {
      updateRangeFill(this); 
      if (player && player.getDuration) {
        const duration = player.getDuration();
        const seekToTime = (this.value / 100) * duration;
        player.seekTo(seekToTime, true);
        document.getElementById('timeDisplay').innerText = `${formatTime(seekToTime)} / ${formatTime(duration)}`;
      }
    });

    // -- Volume Control --
    $('#volumeBar').on('input', function() {
      updateRangeFill(this); 
      const volVal = this.value;

      if(volVal == 0) {
        $('#iconVolumeUp').addClass('d-none');
        $('#iconVolumeMute').removeClass('d-none');
      } else {
        $('#iconVolumeMute').addClass('d-none');
        $('#iconVolumeUp').removeClass('d-none');
      }

      if (player && player.setVolume) {
        player.setVolume(volVal);
      }
    });

    // -- Fullscreen --
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

    // -- Playlist Carousel / Klik Video Baru --
    $(document).on('click', '.video-thumb', function() {
      const videoId = $(this).data('videoid');
      const kode_materi = $(this).data('kode_materi');
      const title = $(this).data('title');

      $('.video-thumb').removeClass('border border-primary border-3');
      $(this).addClass('border border-primary border-3');
      $('#kode_materi').val(kode_materi);
      $('#judul_materi_chat').text(title);
      $('.inner-chat-materi').html('<div class="d-flex justify-content-center py-10"><span class="spinner-border text-primary"></span></div>');

      get_chat_materi(true);
      loadFileMateri(kode_materi);

      if (player && player.loadVideoById) {
        player.loadVideoById({ videoId: videoId });
      }
    });

    $('#nextBtn').on('click', () => track.scrollBy({ left: 250, behavior: 'smooth' }));
    $('#prevBtn').on('click', () => track.scrollBy({ left: -250, behavior: 'smooth' }));

    // -- Fitur Chat (Send & Reload) --
    $('#chat_materi').off('click').on('click', function() {
      const btn = $(this);
      const textarea = $('textarea[name=text]');
      const chat_text = textarea.val().trim();
      const kode_materi = $('#kode_materi').val();
      const link = '<?= $link ?? "" ?>';
      const linkadmin = '<?= $linkadmin ?? "" ?>';

      if (chat_text !== '') {
        btn.prop('disabled', true);
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
            if (res.status === 'success') {
              updateCsrfToken(res.token);
              textarea.val('');
              get_chat_materi(true);
            }
          },
          complete: function() {
            btn.prop('disabled', false);
          }
        });
      }
    });

    setInterval(() => get_chat_materi(false), 5000);

  }); // Akhir document.ready

  /* ==========================================================================
     6. FUNGSI AJAX BANTUAN MATERI
     ========================================================================== */
  function loadFileMateri(kode) {
    const container = $('#file-list-container');
    container.html('<div class="text-center py-10"><span class="spinner-border text-primary"></span></div>');
    $.post("<?= base_url('sw-siswa/materi/get-file-materi') ?>", { kode_materi: kode }, function(res) {
      container.html(res);
    }).fail(function() {
      container.html('<div class="text-center text-danger">Gagal memuat file.</div>');
    });
  }

  function get_chat_materi(shouldScroll = false) {
    const kode_materi = $('#kode_materi').val();
    if (!kode_materi) return;
    $.ajax({
      url: "<?= base_url('sw-siswa/materi/get-chat-materi') ?>",
      method: "POST",
      dataType: "json",
      data: { kode_materi: kode_materi },
      success: function(res) {
        if (res.html) {
          $('.inner-chat-materi').html(res.html);
        }
        updateCsrfToken(res.token);
        if (shouldScroll) {
          const chatContainer = $('.inner-chat-materi');
          chatContainer.scrollTop(chatContainer[0].scrollHeight);
        }
      }
    });
  }
</script>
<?= $this->endSection(); ?>
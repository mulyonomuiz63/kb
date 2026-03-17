<?= $this->extend('siswa/template/app'); ?>

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
              <div class="ratio ratio-16x9 bg-dark">
                <iframe id="mainVideo"
                  src="https://www.youtube.com/embed/<?= $firstVideoId ?>?enablejsapi=1&rel=0&modestbranding=1&autoplay=1"
                  allow="autoplay; encrypted-media" allowfullscreen></iframe>
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
  let player;
  const track = document.getElementById('carouselTrack');

  let currentCsrfHash = '<?= csrf_hash() ?>';
  const csrfName = '<?= csrf_token() ?>';

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

  // Fungsi AJAX File
  function loadFileMateri(kode) {
    const container = $('#file-list-container');
    container.html('<div class="text-center py-10"><span class="spinner-border text-primary"></span></div>');
    $.post("<?= base_url('sw-siswa/materi/get-file-materi') ?>", {
      kode_materi: kode
    }, function(res) {
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
      data: {
        kode_materi: kode_materi
      },
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

  $('#chat_materi').off('click').on('click', function() {
    const btn = $(this);
    const textarea = $('textarea[name=text]');
    const chat_text = textarea.val().trim();
    const kode_materi = $('#kode_materi').val();
    const link = '<?= $link ?>';

    if (chat_text !== '') {
      btn.prop('disabled', true);
      $.ajax({
        url: "<?= base_url('sw-siswa/materi/chat-materi') ?>",
        method: "POST",
        dataType: "json",
        data: {
          chat_materi: chat_text,
          kode_materi: kode_materi,
          link: link
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

      // Load chat dan file untuk video pertama
      get_chat_materi(true);
      loadFileMateri(initialKode);
    }
  }

  function onPlayerStateChange(event) {
    if (event.data === YT.PlayerState.ENDED) {
      const current = $('.video-thumb.border-primary');
      const next = current.next('.video-thumb');
      if (next.length > 0) {
        next.click();
        next[0].scrollIntoView({
          behavior: 'smooth',
          inline: 'center'
        });
      } else {
        const firstThumb = $('.video-thumb').first();
        $('.video-thumb').removeClass('border border-primary border-3');
        firstThumb.addClass('border border-primary border-3');
        $('#judul_materi_chat').text(firstThumb.data('title'));
        $('#kode_materi').val(firstThumb.data('kode_materi'));
        track.scrollTo({
          left: 0,
          behavior: 'smooth'
        });
        if (player && player.cueVideoById) {
          player.cueVideoById({
            videoId: firstThumb.data('videoid')
          });
        }
        get_chat_materi(true);
        loadFileMateri(firstThumb.data('kode_materi'));
      }
    }
  }

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
    loadFileMateri(kode_materi); // Update file saat ganti video

    if (player && player.loadVideoById) {
      player.loadVideoById({
        videoId: videoId
      });
    }
  });

  setInterval(() => get_chat_materi(false), 5000);

  $('#nextBtn').on('click', () => track.scrollBy({
    left: 250,
    behavior: 'smooth'
  }));
  $('#prevBtn').on('click', () => track.scrollBy({
    left: -250,
    behavior: 'smooth'
  }));
</script>
<?= $this->endSection(); ?>
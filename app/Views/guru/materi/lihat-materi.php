<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?= $this->include('template/sidebar/guru'); ?>

<div id="content" class="main-content">
  <div class="layout-px-spacing">
    <div class="row layout-top-spacing">
      <div class="col-lg-12 layout-spacing">
        <div class="widget shadow p-3">
          <div class="widget-heading">

            <!-- Judul dinamis -->
            <div class="d-flex justify-content-between">
                <h5 id="judulVideo"><?= $materi->nama_materi; ?></h5>
                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#staticBackdrop">
                  Materi softcopy
                </button>
            </div>
            
            <!--<table class="mt-3">-->
            <!--  <tr><th>Tutor</th><th> : <?= $guru->nama_guru; ?></th></tr>-->
            <!--  <tr><th>Kelas</th><th> : <?= $materi->nama_kelas; ?></th></tr>-->
            <!--  <tr><th>Dibuat pada</th><th> : <?= date('d-M-Y H:i', $materi->date_created); ?></th></tr>-->
            <!--</table>-->

            <?php 
              $thumbs = json_decode($materi->text_materi, true) ?? [];
              $first = $thumbs[0] ?? $materi->text_materi;

               // Ambil ID dari bentuk URL atau kode langsung
              if (preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $first, $match)) {
                  $firstVideoId = $match[1];
              } else {
                  // Jika langsung kode video tanpa URL
                  $firstVideoId = trim($first);
              }
            ?>

            <?php if($thumbs): ?>
            <hr>

            <!-- Video utama -->
            <div class="embed-responsive embed-responsive-16by9">
              <iframe id="mainVideo" class="embed-responsive-item"
                src="https://www.youtube.com/embed/<?= $firstVideoId ?>?enablejsapi=1&rel=0&modestbranding=1&controls=1&autoplay=1"
                allowfullscreen></iframe>
            </div>

            <!-- Carousel -->
            <div class="video-carousel mt-4 position-relative">
              <button class="carousel-btn left" id="prevBtn">❮</button>
              <div class="carousel-track">

                <!-- Video utama list -->
                <?php foreach($thumbs as $i => $thumb): 
                  
                   // Ambil ID dari bentuk URL atau kode langsung
                  if (preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $thumb, $match)) {
                      $videoId = $match[1];
                  } else {
                      // Jika langsung kode video tanpa URL
                      $videoId = trim($first);
                  }
                  $title = $materi->nama_materi;
                ?>
                <div class="video-thumb <?= $i==0 ? 'active':'' ?>"
                  data-videoid="<?= $videoId ?>"
                  data-kode_materi="<?= $materi->kode_materi; ?>"
                  data-title="<?= esc($title) ?>">

                  <img src="https://img.youtube.com/vi/<?= $videoId ?>/hqdefault.jpg">
                  <div class="overlay"><div class="play-icon">▶</div></div>
                  <div class="video-title"><?= esc($title) ?></div>
                </div>
                <?php endforeach; ?>

                <!-- Menampilkan semua materi lainnya -->
                <?php foreach($materiAll as $ma): ?>
                  <?php if($materi->id_materi != $ma->id_materi): ?>
                    <?php $thumbsMa = json_decode($ma->text_materi, true) ?? []; ?>
                    <?php foreach($thumbsMa as $i => $thumbMa): 
                      // Ambil ID dari bentuk URL atau kode langsung
                      if (preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $thumbMa, $match)) {
                          $videoId = $match[1];
                      } else {
                          // Jika langsung kode video tanpa URL
                          $videoId = trim($first);
                      }
                    ?>
                    <div class="video-thumb"
                      data-videoid="<?= $videoId ?>"
                      data-kode_materi="<?= $ma->kode_materi; ?>"
                      data-title="<?= esc($ma->nama_materi) ?>">

                      <img src="https://img.youtube.com/vi/<?= $videoId ?>/hqdefault.jpg">
                      <div class="overlay"><div class="play-icon">▶</div></div>
                      <div class="video-title"><?= esc($ma->nama_materi) ?></div>
                    </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
                <?php endforeach; ?>

              </div>
              <button class="carousel-btn right" id="nextBtn">❯</button>
            </div>
            <?php endif; ?>

            <hr>

            <div>
              <span class="btn btn-danger mb-3 text-left">
                “PERINGATAN! Dilarang keras melakukan penyebaran atau penggandaan video pembelajaran ini tanpa seizin tertulis dari pemilik konten. Setiap bentuk pelanggaran terhadap ketentuan ini akan dikenakan sanksi sesuai hukum yang berlaku.”
              </span>
            </div>

            <!-- CHAT -->
            <div id="toggleAccordion">
              <div class="card">
                <div class="card-header">
                  <section class="mb-0 mt-0">
                    <div role="menu" class="collapsed" data-toggle="collapse" data-target="#chatBox">
                      Lihat Histori Chat
                    </div>
                  </section>
                </div>

                <div id="chatBox" class="collapse" data-parent="#toggleAccordion">
                  <div class="card-body" style="height: 250px; overflow-y: scroll;">
                    <div class="inner-chat-materi">
                      <button class="btn btn-primary btn-block" disabled>
                        <span class="spinner-border spinner-border-sm"></span> Loading...
                      </button>
                    </div>
                  </div>
                </div>

                <div class="card-footer bg-white">
                  <input type="hidden" name="kode_materi" id="kode_materi" value="">
                  <textarea class="form-control" name="text" placeholder="Tulis komentar / chat" rows="1"></textarea>
                  <label id="informasi" class="text-danger"></label>
                  <button id="chat_materi" class="btn btn-primary d-flex ml-auto mt-2" type="button">Kirim Chat</button>
                </div>
              </div>
            </div>

            <a href="javascript:void(0)" class="btn btn-primary mt-3" onclick="history.back()">Kembali</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <div class="footer-wrapper">
    <div class="footer-section f-section-1">
      <p class="terms-conditions"><?= copyright() ?></p>
    </div>
  </div>
</div>

<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">List Materi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
            <ol class="list-group list-group-numbered">
                <?php if(!empty(($file))): ?>
                    <?php foreach ($file as $m): ?>
                        <?php if ($m->nama_file): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center file-item"
                                data-id="<?= $m->id_file ?>">
            
                                <span>
                                    Materi Softcopy 
                                <?php
                                    $namaTanpaExt = pathinfo($m->nama_file, PATHINFO_FILENAME);

                                    // ganti underscore (_) menjadi spasi
                                    echo str_replace('_', ' ', $namaTanpaExt);
                                ?>
                                </span>
            
                                <button
                                    type="button"
                                    class="btn btn-sm btn-danger btn-delete-file"
                                    data-id="<?= $m->id_file ?>"
                                    data-nama="<?= esc($m->nama_materi) ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
            
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span>Tidak ada materi</span>
                <?php endif ?>
            </ol>
        </div>





      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>

<!-- STYLE -->
<style>
.video-carousel {
  position: relative;
  overflow: hidden;
  width: 100%;
}
.carousel-track {
  display: flex;
  overflow-x: auto;
  scroll-behavior: smooth;
  gap: 10px;
  padding: 10px;
}
.carousel-track::-webkit-scrollbar { display: none; }

.carousel-btn {
  position: absolute;
  top: 40%;
  transform: translateY(-50%);
  background: rgba(0,0,0,0.2);
  color: white;
  border: none;
  font-size: 28px;
  width: 35px;
  height: 55px;
  cursor: pointer;
  z-index: 10;
  border-radius: 5px;
  transition: all 0.3s ease;
}
.carousel-btn:hover { background: rgba(0,0,0,0.6); }
.carousel-btn.left { left: 0; }
.carousel-btn.right { right: 0; }

.video-thumb {
  position: relative;
  flex: 0 0 auto;
  width: 160px;
  height: auto;
  border-radius: 8px;
  overflow: hidden;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
  border: 3px solid transparent;
  text-align: center;
}
.video-thumb img {
  width: 100%;
  height: 90px;
  object-fit: cover;
  border-radius: 6px;
}
.video-thumb:hover { transform: scale(1.05); }
.video-thumb.active {
  border: 3px solid #007bff;
  box-shadow: 0 0 15px rgba(0,123,255,0.6);
}
.video-thumb .overlay {
  position: absolute;
  top: 0; left: 0;
  width: 100%; height: 90px;
  background: rgba(0,0,0,0.3);
  opacity: 0;
  transition: opacity 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
}
.video-thumb:hover .overlay { opacity: 1; }
.play-icon {
  color: white;
  font-size: 32px;
  text-shadow: 0 0 8px rgba(0,0,0,0.5);
}
.video-title {
  margin-top: 6px;
  font-size: 13px;
  color: #333;
  font-weight: 500;
  line-height: 1.2;
  text-align: center;
  display: -webkit-box;
  -webkit-line-clamp: 2;        /* maksimal 2 baris */
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: normal;
  height: 2.4em;                /* sesuaikan tinggi agar pas 2 baris */
  padding: 0 4px;
  word-break: break-word;       /* biar kata panjang tetap patah */
}

</style>

<!-- SCRIPT -->
<script src="https://www.youtube.com/iframe_api"></script>
<script>
let player;

function onYouTubeIframeAPIReady() {
  player = new YT.Player('mainVideo', {
    playerVars: { autoplay: 1 },
    events: {
      'onReady': onPlayerReady,
      'onStateChange': onPlayerStateChange
    }
  });
}

// Autoplay pertama
function onPlayerReady() {
  const firstThumb = $('.video-thumb').first();
  firstThumb.addClass('active');
  $('#kode_materi').val(firstThumb.data('kode_materi'));
  $('#judulVideo').text(firstThumb.data('title'));
}

// Auto next
function onPlayerStateChange(event) {
  if (event.data === YT.PlayerState.ENDED) {
    const current = $('.video-thumb.active');
    const next = current.next('.video-thumb');
    if (next.length > 0) next.click();
  }
}

// Ganti video
$(document).on('click', '.video-thumb', function () {
  const videoId = $(this).data('videoid');
  const kode_materi = $(this).data('kode_materi');
  const title = $(this).data('title');

  $('.video-thumb').removeClass('active');
  $(this).addClass('active');

  $('#judulVideo').text(title);
  $('#kode_materi').val(kode_materi);

  if (player && player.loadVideoById) {
    player.loadVideoById(videoId);
  }

  const container = document.querySelector('.carousel-track');
  const scrollLeft = this.offsetLeft - container.offsetWidth / 2 + this.offsetWidth / 2;
  container.scrollTo({ left: scrollLeft, behavior: 'smooth' });
});

// Scroll buttons
$('#nextBtn').click(() => document.querySelector('.carousel-track').scrollBy({ left: 300, behavior: 'smooth' }));
$('#prevBtn').click(() => document.querySelector('.carousel-track').scrollBy({ left: -300, behavior: 'smooth' }));

// === CHAT HANDLER ===
$('#chat_materi').click(function() {
  const chat_materi = $('textarea[name=text]').val();
  const kode_materi = $('input[name=kode_materi]').val();
  if (chat_materi.trim() !== '') {
    $.post("<?= base_url('guru/chat_materi') ?>", { chat_materi, kode_materi }, function() {
      $('textarea[name=text]').val('');
      $('#informasi').html('');
    });
  } else {
    $('#informasi').text('Komentar Harus Diisi');
  }
});

// === AUTO REFRESH CHAT ===
setInterval(() => {
  const kode_materi = $('input[name=kode_materi]').val();
  $.post("<?= base_url('guru/get_chat_materi') ?>", { kode_materi }, function(html) {
    $('.inner-chat-materi').html(html);
  });
}, 5000);

// === DELETE FILE ===
$(document).on('click', '.hapus-file', function() {
  if (!confirm('Anda yakin mau menghapus data ini ?')) return;
  const id_file = $(this).data('id');
  const nama_file = $(this).data('nama_file');
  $.post("<?= base_url('guru/delete_file') ?>", { id_file, nama_file }, function() {
    location.reload();
  });
});
$('#kode_materi').val('<?= $materi->kode_materi ?>');
</script>


<!--untuk menghapus file-->
<script>
$(document).on('click', '.btn-delete-file', function () {

    const btn  = $(this);
    const id   = btn.data('id');
    const nama = btn.data('nama');

    if (!confirm(`Yakin ingin menghapus file "${nama}" ?`)) return;

    $.ajax({
        url: "<?= base_url('guru/delete_file') ?>",
        type: "POST",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            id_file: id,
            <?= csrf_token() ?>: "<?= csrf_hash() ?>"
        },
        dataType: "json",
        success: function (res) {
            if (res.status === 'success') {

                // 🔥 Hilangkan item tanpa reload
                btn.closest('.file-item').fadeOut(300, function () {
                    $(this).remove();
                });

            } else {
                alert(res.message || 'Gagal menghapus file');
            }
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            alert('Gagal memproses permintaan');
        }
    });
});
</script>




<?= $this->endSection(); ?>

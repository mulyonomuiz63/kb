<?= $this->extend('template/app'); ?>

<?= $this->section('styles'); ?>
<style>
    /* Styling Dasar Layout */
    .bg-black { background: #000; }
    .widget { border-radius: 15px; overflow: hidden; }
    
    /* Peringatan Hak Cipta (Dashed Red) */
    .alert-copyright {
        border: 1.5px dashed #f85359;
        background: #fff5f5;
        color: #f85359;
        border-radius: 10px;
        padding: 15px;
    }

    /* Video Carousel Styling (Sesuai Contoh Kode Siswa) */
    .video-carousel { position: relative; overflow: hidden; width: 100%; background: #fff; border-radius: 15px; }
    .carousel-track { display: flex; overflow-x: auto; scroll-behavior: smooth; gap: 10px; padding: 15px; }
    .carousel-track::-webkit-scrollbar { display: none; }
    
    .carousel-btn {
        position: absolute; top: 40%; transform: translateY(-50%);
        background: rgba(0,0,0,0.2); color: white; border: none;
        font-size: 24px; width: 35px; height: 50px; cursor: pointer; z-index: 10;
        border-radius: 5px; transition: all 0.3s ease;
    }
    .carousel-btn:hover { background: rgba(0,0,0,0.6); }
    .carousel-btn.left { left: 5px; }
    .carousel-btn.right { right: 5px; }

    .video-thumb {
        position: relative; flex: 0 0 auto; width: 180px;
        cursor: pointer; transition: transform 0.2s; border: 3px solid transparent;
        background: #fff; border-radius: 10px; padding: 5px;
    }
    .video-thumb img { width: 100%; height: 100px; object-fit: cover; border-radius: 6px; }
    .video-thumb.active { border: 3px solid #4361ee; box-shadow: 0 0 10px rgba(67, 97, 238, 0.3); }
    
    .video-title-carousel {
        margin-top: 8px; font-size: 12px; font-weight: 600; color: #333;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
        overflow: hidden; line-height: 1.3; height: 2.6em;
    }

    /* Chat Styling */
    .chat-section { background: #fff; border-radius: 15px; border: 1px solid #e0e6ed; }
    .inner-chat-materi { height: 300px; overflow-y: auto; padding: 15px; background: #f8f9fa; }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        
        <div class="col-xl-8 col-lg-7 col-md-12 layout-spacing">
            <div class="widget shadow-sm bg-white mb-4">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0 text-primary" id="judulVideo"><?= esc($materi->nama_materi); ?></h5>
                        <small class="text-muted"><i class="bi bi-person"></i> Instruktur: <?= esc($guru->nama_guru); ?></small>
                    </div>
                    <a href="javascript:window.history.go(-1);" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

                <?php 
                    $thumbs = json_decode($materi->text_materi, true) ?? [];
                    $firstUrl = is_array($thumbs) ? ($thumbs[0] ?? '') : $materi->text_materi;
                    preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $firstUrl, $match);
                    $firstVideoId = $match[1] ?? '';
                ?>

                <div class="embed-responsive embed-responsive-16by9 bg-black">
                    <iframe id="mainVideo" class="embed-responsive-item"
                        src="https://www.youtube.com/embed/<?= esc($firstVideoId) ?>?enablejsapi=1&rel=0&modestbranding=1"
                        allowfullscreen></iframe>
                </div>

                <div class="video-carousel position-relative">
                    <button class="carousel-btn left" id="prevBtn">❮</button>
                    <div class="carousel-track" id="carouselTrack">
                        
                        <?php foreach($thumbs as $i => $vUrl): 
                            preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $vUrl, $vMatch);
                            $vId = $vMatch[1] ?? '';
                            if(!$vId) continue;
                        ?>
                            <div class="video-thumb <?= $vId == $firstVideoId ? 'active' : '' ?>"
                                data-videoid="<?= $vId ?>"
                                data-kode="<?= esc($materi->kode_materi) ?>"
                                data-title="<?= esc($materi->nama_materi) ?>">
                                <img src="https://img.youtube.com/vi/<?= $vId ?>/mqdefault.jpg">
                                <div class="video-title-carousel"><?= esc($materi->nama_materi) ?></div>
                            </div>
                        <?php endforeach; ?>

                        <?php foreach($materiAll as $ma): if($ma->id_materi != $materi->id_materi): 
                            $maThumbs = json_decode($ma->text_materi, true) ?? [];
                            foreach($maThumbs as $vUrlMa):
                                preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $vUrlMa, $vMatchMa);
                                $vIdMa = $vMatchMa[1] ?? '';
                                if(!$vIdMa) continue;
                        ?>
                            <div class="video-thumb"
                                data-videoid="<?= $vIdMa ?>"
                                data-kode="<?= esc($ma->kode_materi) ?>"
                                data-title="<?= esc($ma->nama_materi) ?>">
                                <img src="https://img.youtube.com/vi/<?= $vIdMa ?>/mqdefault.jpg">
                                <div class="video-title-carousel"><?= esc($ma->nama_materi) ?></div>
                            </div>
                        <?php endforeach; endif; endforeach; ?>
                    </div>
                    <button class="carousel-btn right" id="nextBtn">❯</button>
                </div>
            </div>

            <div class="alert-copyright d-flex align-items-start shadow-sm">
                <i class="bi bi-exclamation-triangle-fill mr-3" style="font-size: 24px;"></i>
                <small><b>PERINGATAN!</b> Dilarang keras melakukan penyebaran atau penggandaan video pembelajaran ini tanpa seizin tertulis dari pemilik konten. Pelanggaran akan dikenakan sanksi sesuai hukum yang berlaku.</small>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5 col-md-12 layout-spacing">
            <div class="chat-section shadow-sm">
                <div class="p-3 border-bottom bg-primary text-white rounded-top">
                    <h6 class="mb-0 fw-bold text-white"><i class="bi bi-chat-dots-fill mr-2 "></i> Diskusi & File</h6>
                </div>
                
                <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active py-2" data-toggle="tab" href="#tab-chat">Chat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-2" data-toggle="tab" href="#tab-file">File</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-chat">
                        <div class="inner-chat-materi" id="chatContainer">
                            </div>
                        <div class="p-3 border-top">
                            <textarea class="form-control" name="text" placeholder="Tulis pesan..." rows="1" style="resize:none;"></textarea>
                            <button id="chat_materi" class="btn btn-primary btn-block mt-2">Kirim Pesan</button>
                            <small id="informasi" class="text-danger"></small>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab-file">
                        <div class="p-3" id="file_container" style="min-height: 200px;">
                            </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<input type="hidden" id="kode_materi" value="<?= esc($materi->kode_materi) ?>">
<input type="hidden" id="idguru" value="<?= encrypt_url($guru->id_guru) ?>">

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script src="https://www.youtube.com/iframe_api"></script>
<script>
    let player;
    const baseUrl = "<?= rtrim(base_url(), '/') . '/' ?>";

    function onYouTubeIframeAPIReady() {
        player = new YT.Player('mainVideo', {
            events: {
                'onStateChange': (e) => {
                    if (e.data === YT.PlayerState.ENDED) {
                        $('.video-thumb.active').next('.video-thumb').click();
                    }
                }
            }
        });
    }

    // Carousel Scroll (Sesuai kode contoh)
    $('#nextBtn').click(() => document.getElementById('carouselTrack').scrollBy({ left: 300, behavior: 'smooth' }));
    $('#prevBtn').click(() => document.getElementById('carouselTrack').scrollBy({ left: -300, behavior: 'smooth' }));

    // Ganti Video
    $(document).on('click', '.video-thumb', function() {
        const vId = $(this).data('videoid');
        const kode = $(this).data('kode');
        const title = $(this).data('title');
        const idguru = $('#idguru').val();

        $('.video-thumb').removeClass('active');
        $(this).addClass('active');
        
        $('#judulVideo').text(title);
        $('#kode_materi').val(kode);

        if (player && player.loadVideoById) player.loadVideoById(vId);

        loadChatMateri(kode, idguru);
        loadFileMateri(kode);

        // Auto center carousel scroll
        const container = document.getElementById('carouselTrack');
        const scrollLeft = this.offsetLeft - container.offsetWidth / 2 + this.offsetWidth / 2;
        container.scrollTo({ left: scrollLeft, behavior: 'smooth' });
    });

    function loadChatMateri(kode, idguru) {
        if (!kode) return;
        $.post(baseUrl + "sw-admin/guru/get-chat-materi", { kode_materi: kode, idguru: idguru }, function(html) {
            $('#chatContainer').html(html);
            let chatBox = document.getElementById('chatContainer');
            chatBox.scrollTop = chatBox.scrollHeight;
        });
    }

    function loadFileMateri(kode) {
        if (!kode) return;
        $.post(baseUrl + "sw-admin/guru/get-file-materi", { kode_materi: kode }, function(res) {
            $('#file_container').html(res);
        });
    }

    $('#chat_materi').click(function() {
        const chat = $('textarea[name=text]').val();
        const kode = $('#kode_materi').val();
        const idguru = $('#idguru').val();

        if (chat.trim() === "") return;

        $.post(baseUrl + "sw-admin/guru/chat-materi", {
            chat_materi: chat,
            kode_materi: kode,
            idguru: idguru,
        }, function() {
            $('textarea[name=text]').val('');
            loadChatMateri(kode, idguru);
        });
    });

    $(document).ready(function() {
        const initKode = $('#kode_materi').val();
        const idguru = $('#idguru').val();
        loadChatMateri(initKode, idguru);
        loadFileMateri(initKode);
        
        setInterval(() => loadChatMateri($('#kode_materi').val(), $('#idguru').val()), 5000);
    });
</script>
<?= $this->endSection(); ?>
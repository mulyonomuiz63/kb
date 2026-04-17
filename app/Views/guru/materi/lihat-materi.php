<?= $this->extend('template/app'); ?>

<?= $this->section('styles') ?>
<style>
    /* Styling Dasar Layout */
    .bg-black {
        background: #000;
    }

    /* Peringatan Hak Cipta (Dashed Red) - Metronic Style */
    .alert-copyright {
        border: 1px dashed #f1416c;
        background: #fff5f8;
        color: #f1416c;
        border-radius: 0.625rem;
        padding: 1.25rem;
    }

    /* Video Carousel Styling */
    .video-carousel {
        position: relative;
        overflow: hidden;
        width: 100%;
        background: #fff;
        border-radius: 0.625rem;
    }

    .carousel-track {
        display: flex;
        overflow-x: auto;
        scroll-behavior: smooth;
        gap: 15px;
        padding: 1.25rem;
    }

    .carousel-track::-webkit-scrollbar {
        display: none;
    }

    .carousel-btn {
        position: absolute;
        top: 40%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.1);
        color: #333;
        border: none;
        font-size: 1.5rem;
        width: 40px;
        height: 60px;
        cursor: pointer;
        z-index: 10;
        border-radius: 0.475rem;
        transition: all 0.3s ease;
    }

    .carousel-btn:hover {
        background: rgba(0, 0, 0, 0.8);
        color: #fff;
    }

    .carousel-btn.left {
        left: 10px;
    }

    .carousel-btn.right {
        right: 10px;
    }

    .video-thumb {
        position: relative;
        flex: 0 0 auto;
        width: 200px;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 2px solid transparent;
        background: #fff;
        border-radius: 0.625rem;
        padding: 6px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .video-thumb img {
        width: 100%;
        height: 110px;
        object-fit: cover;
        border-radius: 0.475rem;
    }

    .video-thumb.active {
        border-color: #009ef7;
        background-color: #f1faff;
    }

    .video-title-carousel {
        margin-top: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #3f4254;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
        height: 2.8em;
    }

    /* Chat Styling */
    .inner-chat-materi {
        height: 350px;
        overflow-y: auto;
        padding: 1.25rem;
        background-color: #f9f9f9;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="row g-5 g-xl-10">

            <div class="col-xl-8 col-lg-7 col-md-12">
                <div class="card card-flush shadow-sm mb-5">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title flex-column">
                            <h3 class="fw-bolder text-dark mb-1" id="judulVideo"><?= esc($materi->nama_materi); ?></h3>
                            <div class="d-flex align-items-center text-gray-400 fw-bold fs-7">
                                <i class="ki-duotone ki-user fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>
                                Instruktur: <?= esc($guru->nama_guru); ?>
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <a href="javascript:window.history.go(-1);" class="btn btn-sm btn-light">
                                <i class="ki-duotone ki-black-left fs-4 me-1"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <?php
                    $thumbs = json_decode($materi->text_materi, true) ?? [];
                    $firstUrl = is_array($thumbs) ? ($thumbs[0] ?? '') : $materi->text_materi;
                    preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $firstUrl, $match);
                    $firstVideoId = $match[1] ?? '';
                    ?>
                    <div class="card-body p-0">
                        <div class="ratio ratio-16x9 bg-black">
                            <iframe id="mainVideo"
                                src="https://www.youtube.com/embed/<?= esc($firstVideoId) ?>?enablejsapi=1&rel=0&modestbranding=1"
                                title="Main Video Player" allowfullscreen></iframe>
                        </div>
                    </div>

                    <div class="video-carousel border-top">
                        <button class="carousel-btn left" id="prevBtn">❮</button>
                        <div class="carousel-track" id="carouselTrack">

                            <?php foreach ($thumbs as $i => $vUrl):
                                preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $vUrl, $vMatch);
                                $vId = $vMatch[1] ?? '';
                                if (!$vId) continue;
                            ?>
                                <div class="video-thumb <?= $vId == $firstVideoId ? 'active' : '' ?>"
                                    data-videoid="<?= $vId ?>"
                                    data-kode="<?= esc($materi->kode_materi) ?>"
                                    data-title="<?= esc($materi->nama_materi) ?>">
                                    <img src="https://img.youtube.com/vi/<?= $vId ?>/mqdefault.jpg" alt="Thumbnail">
                                    <div class="video-title-carousel"><?= esc($materi->nama_materi) ?></div>
                                </div>
                            <?php endforeach; ?>

                            <?php foreach ($materiAll as $ma): if ($ma->id_materi != $materi->id_materi):
                                    $maThumbs = json_decode($ma->text_materi, true) ?? [];
                                    foreach ($maThumbs as $vUrlMa):
                                        preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $vUrlMa, $vMatchMa);
                                        $vIdMa = $vMatchMa[1] ?? '';
                                        if (!$vIdMa) continue;
                            ?>
                                        <div class="video-thumb"
                                            data-videoid="<?= $vIdMa ?>"
                                            data-kode="<?= esc($ma->kode_materi) ?>"
                                            data-title="<?= esc($ma->nama_materi) ?>">
                                            <img src="https://img.youtube.com/vi/<?= $vIdMa ?>/mqdefault.jpg" alt="Thumbnail">
                                            <div class="video-title-carousel"><?= esc($ma->nama_materi) ?></div>
                                        </div>
                            <?php endforeach;
                                endif;
                            endforeach; ?>
                        </div>
                        <button class="carousel-btn right" id="nextBtn">❯</button>
                    </div>
                </div>

                <div class="alert-copyright d-flex align-items-center shadow-sm">
                    <i class="ki-duotone ki-information-2 fs-2x me-4 text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <div class="fs-7">
                        <b class="text-uppercase">Peringatan!</b> Dilarang keras melakukan penyebaran atau penggandaan video pembelajaran ini tanpa seizin tertulis dari pemilik konten. Pelanggaran akan dikenakan sanksi sesuai hukum yang berlaku.
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-5 col-md-12">
                <div class="card card-flush shadow-sm">
                    <div class="card-header bg-primary py-4 min-h-auto">
                        <h3 class="card-title fw-bolder text-white fs-6">
                            <i class="ki-duotone ki-messages fs-2 me-2 text-white"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            Diskusi & File
                        </h3>
                    </div>

                    <div class="card-body p-0">
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x nav-stretch fs-6 fw-bold border-bottom px-5">
                            <li class="nav-item flex-equal text-center">
                                <a class="nav-link active text-active-primary py-4" data-bs-toggle="tab" href="#tab-chat">Chat</a>
                            </li>
                            <li class="nav-item flex-equal text-center">
                                <a class="nav-link text-active-primary py-4" data-bs-toggle="tab" href="#tab-file">File</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tab-chat" role="tabpanel">
                                <div class="inner-chat-materi" id="chatContainer">
                                </div>
                                <div class="p-5 border-top bg-light">
                                    <textarea class="form-control form-control-solid mb-3 border border-gray-300 border-active-primary px-5 py-4 mb-3" name="text" placeholder="Tulis pesan..." rows="2" style="resize:none;"></textarea>
                                    <button id="chat_materi" class="btn btn-primary w-100">
                                        <i class="ki-duotone ki-send fs-3 me-1"><span class="path1"></span><span class="path2"></span></i> Kirim Pesan
                                    </button>
                                    <div id="informasi" class="text-danger fs-8 mt-2"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab-file" role="tabpanel">
                                <div class="p-5" id="file_container" style="min-height: 250px;">
                                </div>
                            </div>
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
                        const nextItem = $('.video-thumb.active').next('.video-thumb');
                        if (nextItem.length > 0) {
                            nextItem.click();
                        }
                    }
                }
            }
        });
    }

    // Carousel Scroll (Metronic Friendly)
    $('#nextBtn').click(() => document.getElementById('carouselTrack').scrollBy({
        left: 300,
        behavior: 'smooth'
    }));
    $('#prevBtn').click(() => document.getElementById('carouselTrack').scrollBy({
        left: -300,
        behavior: 'smooth'
    }));

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

        if (player && player.loadVideoById) {
            player.loadVideoById(vId);
        } else {
            $('#mainVideo').attr('src', 'https://www.youtube.com/embed/' + vId + '?enablejsapi=1&rel=0&modestbranding=1');
        }

        loadChatMateri(kode, idguru);
        loadFileMateri(kode);

        // Auto center carousel scroll
        const container = document.getElementById('carouselTrack');
        const scrollLeft = this.offsetLeft - container.offsetWidth / 2 + this.offsetWidth / 2;
        container.scrollTo({
            left: scrollLeft,
            behavior: 'smooth'
        });
    });

    function loadChatMateri(kode, idguru) {
        if (!kode) return;
        $.post(baseUrl + "sw-guru/materi/get-chat-materi", {
            kode_materi: kode,
            idguru: idguru
        }, function(html) {
            $('#chatContainer').html(html);
            let chatBox = document.getElementById('chatContainer');
            chatBox.scrollTop = chatBox.scrollHeight;
        });
    }

    function loadFileMateri(kode) {
        if (!kode) return;
        $.post(baseUrl + "sw-guru/materi/get-file-materi", {
            kode_materi: kode
        }, function(res) {
            $('#file_container').html(res);
        });
    }

    $('#chat_materi').click(function() {
        const btn = $(this);
        const chat = $('textarea[name=text]').val();
        const kode = $('#kode_materi').val();
        const idguru = $('#idguru').val();

        if (chat.trim() === "") return;

        btn.attr('disabled', true);
        $.post(baseUrl + "sw-guru/materi/chat-materi", {
            chat_materi: chat,
            kode_materi: kode,
            idguru: idguru,
        }, function() {
            btn.attr('disabled', false);
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
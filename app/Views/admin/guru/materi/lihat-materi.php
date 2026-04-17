<?= $this->extend('template/app'); ?>

<?= $this->section('styles'); ?>
<style>
    /* Menyempurnakan Custom CSS agar selaras dengan Metronic 8 */
    .bg-black {
        background-color: #000;
    }

    /* Peringatan Hak Cipta Metronic Style */
    .alert-copyright {
        border: 1px dashed var(--bs-danger);
        background-color: var(--bs-danger-light);
        color: var(--bs-danger);
        border-radius: 0.75rem;
        padding: 1.25rem;
    }

    /* Video Carousel Styling */
    .video-carousel {
        position: relative;
        overflow: hidden;
        width: 100%;
        background: #fff;
        border-radius: 0.75rem;
    }

    .carousel-track {
        display: flex;
        overflow-x: auto;
        scroll-behavior: smooth;
        gap: 12px;
        padding: 15px 5px;
    }

    .carousel-track::-webkit-scrollbar {
        display: none;
    }

    .carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.4);
        color: white;
        border: none;
        font-size: 1.2rem;
        width: 35px;
        height: 50px;
        cursor: pointer;
        z-index: 10;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .carousel-btn:hover {
        background: rgba(0, 0, 0, 0.8);
    }

    .carousel-btn.left {
        left: 5px;
    }

    .carousel-btn.right {
        right: 5px;
    }

    .video-thumb {
        position: relative;
        flex: 0 0 auto;
        width: 180px;
        cursor: pointer;
        transition: transform 0.2s;
        border: 2px solid transparent;
        background: #fff;
        border-radius: 0.75rem;
        padding: 4px;
    }

    .video-thumb img {
        width: 100%;
        height: 100px;
        object-fit: cover;
        border-radius: 0.5rem;
    }

    .video-thumb.active {
        border-color: var(--bs-primary);
        box-shadow: 0px 0px 15px 0px rgba(var(--bs-primary-rgb), 0.2);
    }

    .video-title-carousel {
        margin-top: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--bs-gray-800);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
        height: 2.8em;
    }

    /* Chat Styling */
    .inner-chat-materi {
        height: 400px;
        overflow-y: auto;
        padding: 15px;
        background: var(--bs-body-bg);
        scroll-behavior: smooth;
    }

    .inner-chat-materi::-webkit-scrollbar {
        width: 6px;
    }

    .inner-chat-materi::-webkit-scrollbar-thumb {
        background-color: var(--bs-gray-300);
        border-radius: 10px;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="d-flex flex-column flex-xl-row gap-7 gap-xl-10">

                <div class="flex-lg-row-fluid">
                    <div class="card card-flush shadow-sm mb-7">

                        <div class="card-header pt-7">
                            <div class="card-title flex-column">
                                <h2 class="fw-bold mb-2 text-gray-900" id="judulVideo"><?= esc($materi->nama_materi); ?></h2>
                                <div class="d-flex align-items-center text-muted fw-semibold">
                                    <i class="ki-duotone ki-user fs-4 me-2"><span class="path1"></span><span class="path2"></span></i>
                                    Instruktur: <?= esc($guru->nama_guru); ?>
                                </div>
                            </div>
                        </div>

                        <div class="card-body pt-5">
                            <?php
                            $thumbs = json_decode($materi->text_materi, true) ?? [];
                            $firstUrl = is_array($thumbs) ? ($thumbs[0] ?? '') : $materi->text_materi;
                            preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $firstUrl, $match);
                            $firstVideoId = $match[1] ?? '';
                            ?>

                            <div class="ratio ratio-16x9 bg-black rounded overflow-hidden mb-5">
                                <iframe id="mainVideo"
                                    src="https://www.youtube.com/embed/<?= esc($firstVideoId) ?>?enablejsapi=1&rel=0&modestbranding=1"
                                    allowfullscreen>
                                </iframe>
                            </div>

                            <div class="video-carousel mt-5">
                                <button class="carousel-btn left" id="prevBtn"><i class="ki-duotone ki-left fs-2"><span class="path1"></span><span class="path2"></span></i></button>
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
                                            <img src="https://img.youtube.com/vi/<?= $vId ?>/mqdefault.jpg">
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
                                                    <img src="https://img.youtube.com/vi/<?= $vIdMa ?>/mqdefault.jpg">
                                                    <div class="video-title-carousel"><?= esc($ma->nama_materi) ?></div>
                                                </div>
                                    <?php endforeach;
                                        endif;
                                    endforeach; ?>

                                </div>
                                <button class="carousel-btn right" id="nextBtn"><i class="ki-duotone ki-right fs-2"><span class="path1"></span><span class="path2"></span></i></button>
                            </div>

                        </div>
                    </div>

                    <div class="alert-copyright d-flex align-items-center">
                        <i class="ki-duotone ki-shield-cross fs-2tx text-danger me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        <div class="d-flex flex-column">
                            <h4 class="mb-1 text-danger fw-bold">PERINGATAN!</h4>
                            <span class="text-danger fw-semibold">Dilarang keras melakukan penyebaran atau penggandaan video pembelajaran ini tanpa seizin tertulis dari pemilik konten. Pelanggaran akan dikenakan sanksi sesuai hukum yang berlaku.</span>
                        </div>
                    </div>

                </div>
                <div class="flex-column flex-lg-row-auto w-100 w-xl-400px mb-7">
                    <div class="card card-flush shadow-sm">

                        <div class="card-header pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-900"><i class="ki-duotone ki-messages fs-2 me-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Interaksi</span>
                            </h3>
                            <div class="card-toolbar">
                                <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bold" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a id="kt_chat_tab" class="nav-link justify-content-center text-active-gray-800 active" data-bs-toggle="tab" role="tab" href="#tab-chat">Chat</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a id="kt_file_tab" class="nav-link justify-content-center text-active-gray-800" data-bs-toggle="tab" role="tab" href="#tab-file">File Modul</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="tab-content">

                                <div class="tab-pane fade show active" id="tab-chat" role="tabpanel">
                                    <div class="inner-chat-materi" id="chatContainer">
                                    </div>

                                    <div class="card-footer pt-4" id="kt_chat_messenger_footer">
                                        <small id="informasi" class="text-danger fw-bold d-block mb-2"></small>

                                        <div class="d-flex align-items-end gap-3">

                                            <textarea class="form-control form-control-solid flex-grow-1" name="text" rows="1" placeholder="Tuliskan pertanyaan Anda..." style="resize:none;"></textarea>

                                            <button class="btn btn-primary flex-shrink-0 d-flex align-items-center justify-content-center" type="button" id="chat_materi" style="height: 44px;">
                                                Kirim <i class="ki-duotone ki-send fs-3 ms-2"><span class="path1"></span><span class="path2"></span></i>
                                            </button>

                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="tab-file" role="tabpanel">
                                    <div class="p-7" id="file_container" style="min-height: 300px;">
                                    </div>
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

    // Initialize YouTube Player
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

    // Carousel Scroll (Sesuai kode contoh aslinya)
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

        if (player && player.loadVideoById) player.loadVideoById(vId);

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

    // Load Data Chat
    // Tambahkan parameter 'forceScroll' (default: false)
    function loadChatMateri(kode, idguru, forceScroll = false) {
        if (!kode) return;

        let chatBox = document.getElementById('chatContainer');
        let isAtBottom = false;
        let currentScroll = 0;

        // Cek posisi scroll SEBELUM request AJAX
        if (chatBox) {
            currentScroll = chatBox.scrollTop;
            // Toleransi 10px untuk perhitungan pixel browser
            isAtBottom = Math.abs((chatBox.scrollHeight - chatBox.scrollTop) - chatBox.clientHeight) <= 10;
        }

        $.post(baseUrl + "sw-admin/guru/get-chat-materi", {
            kode_materi: kode,
            idguru: idguru
        }, function(html) {
            let $chatContainer = $('#chatContainer');

            // HANYA update layar jika ada perubahan isi (pesan baru)
            if ($chatContainer.html() !== html) {
                $chatContainer.html(html);

                if (chatBox) {
                    // Jika posisi sebelumnya di paling bawah ATAU dipaksa turun (forceScroll)
                    if (isAtBottom || forceScroll) {
                        chatBox.scrollTop = chatBox.scrollHeight;
                    } else {
                        // Pertahankan posisi scroll saat membaca pesan lama
                        chatBox.scrollTop = currentScroll;
                    }
                }
            } else if (forceScroll && chatBox) {
                // Jika tidak ada chat baru tapi dipaksa turun (misal saat pertama kali buka)
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });
    }

    // Load Data File Modul
    function loadFileMateri(kode) {
        if (!kode) return;
        $.post(baseUrl + "sw-admin/guru/get-file-materi", {
            kode_materi: kode
        }, function(res) {
            $('#file_container').html(res);
        });
    }

    // Aksi Submit Chat
    $('#chat_materi').click(function() {
        const chatInput = $('textarea[name=text]');
        const chat = chatInput.val();
        const kode = $('#kode_materi').val();
        const idguru = $('#idguru').val();
        const btn = $(this);

        if (chat.trim() === "") {
            $('#informasi').text('Pesan tidak boleh kosong!');
            setTimeout(() => {
                $('#informasi').text('');
            }, 3000);
            return;
        }

        btn.attr("data-kt-indicator", "on").prop('disabled', true); // Loading state on button

        $.post(baseUrl + "sw-admin/guru/chat-materi", {
            chat_materi: chat,
            kode_materi: kode,
            idguru: idguru,
        }, function() {
            chatInput.val('');
            loadChatMateri(kode, idguru, true);
        }).always(function() {
            btn.removeAttr("data-kt-indicator").prop('disabled', false); // Remove loading state
        });
    });

    $(document).ready(function() {
        const initKode = $('#kode_materi').val();
        const idguru = $('#idguru').val();

        loadChatMateri(initKode, idguru, true);
        loadFileMateri(initKode);

        // Interval auto-refresh chat setiap 5 detik
        setInterval(() => loadChatMateri($('#kode_materi').val(), $('#idguru').val()), 5000);
    });
</script>
<?= $this->endSection(); ?>
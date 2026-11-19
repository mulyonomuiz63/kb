<?= $this->extend('template/app'); ?>

<?= $this->section('styles') ?>
<style>
    .bg-black {
        background: #000;
    }

    .alert-copyright {
        border: 1px dashed #f1416c;
        background: #fff5f8;
        color: #f1416c;
        border-radius: 0.625rem;
        padding: 1.25rem;
    }

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

    .inner-chat-materi {
        height: 390px;
        overflow-y: auto;
        padding: 1rem;
        background-color: #f8fafc;
    }

    .chat-bubble-me {
        background: #e0f2fe;
        border: 1px solid #bae6fd;
        border-radius: 1rem 1rem 0.25rem 1rem;
    }

    .chat-bubble-other {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem 1rem 1rem 0.25rem;
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
        max-height: 200px;
        overflow-y: auto;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 0.65rem;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        display: none;
        z-index: 1050;
    }

    .mention-item {
        padding: 8px 12px;
        cursor: pointer;
        border-bottom: 1px solid #f1f5f9;
    }

    .mention-item:hover {
        background-color: #e0f2fe;
        color: #009ef7;
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
                            <div class="d-flex align-items-center text-gray-500 fw-bold fs-7">
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
                    <div class="card-header bg-primary py-4 min-h-auto d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bolder text-white fs-6 m-0">
                            <i class="ki-duotone ki-messages fs-2 me-2 text-white"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            Diskusi & File Materi
                        </h3>
                        <a href="<?= base_url('sw-guru/diskusi?materi=' . urlencode($materi->kode_materi)) ?>" id="btnFullDiskusiGuru" class="btn btn-sm btn-light-primary fw-bold px-3 py-1 rounded-pill" title="Buka di Halaman Diskusi Penuh">
                            Layar Penuh
                        </a>
                    </div>

                    <div class="card-body p-0">
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x nav-stretch fs-6 fw-bold border-bottom px-5">
                            <li class="nav-item flex-equal text-center">
                                <a class="nav-link active text-active-primary py-4" data-bs-toggle="tab" href="#tab-chat">Diskusi</a>
                            </li>
                            <li class="nav-item flex-equal text-center">
                                <a class="nav-link text-active-primary py-4" data-bs-toggle="tab" href="#tab-file">File Modul</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tab-chat" role="tabpanel">
                                <div class="inner-chat-materi" id="chatContainer">
                                    <div class="text-center py-10"><span class="spinner-border text-primary"></span></div>
                                </div>
                                <div class="p-4 border-top bg-white position-relative" id="guru_chat_footer">
                                    <div id="mention_dropdown" class="mention-dropdown shadow-lg"></div>
                                    <textarea id="msg_input" class="form-control form-control-solid mb-2 border border-gray-300 fs-7" name="text" placeholder="Tulis jawaban atau tanggapan... (Gunakan @ untuk mention)" rows="2" style="resize:none;"></textarea>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted fs-9">Enter kirim, Shift+Enter baris baru</span>
                                        <div class="d-flex gap-2">
                                            <button id="btn_cancel_edit" class="btn btn-sm btn-light-danger d-none fw-bold" type="button">Batal</button>
                                            <button id="chat_materi" class="btn btn-sm btn-primary fw-bold px-4" type="button">
                                                <i class="ki-duotone ki-send fs-4 me-1"><span class="path1"></span><span class="path2"></span></i> Kirim
                                            </button>
                                        </div>
                                    </div>
                                    <div id="informasi" class="text-danger fs-8 mt-1"></div>
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
    let lastDisplayedId = 0;
    let editingMessageId = null;
    let activeParticipants = [];
    let guruCsrfName = '<?= csrf_token() ?>';
    let guruCsrfHash = '<?= csrf_hash() ?>';

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

    $('#nextBtn').click(() => document.getElementById('carouselTrack').scrollBy({
        left: 300,
        behavior: 'smooth'
    }));
    $('#prevBtn').click(() => document.getElementById('carouselTrack').scrollBy({
        left: -300,
        behavior: 'smooth'
    }));

    $(document).on('click', '.video-thumb', function() {
        const vId = $(this).data('videoid');
        const kode = $(this).data('kode');
        const title = $(this).data('title');
        const idguru = $('#idguru').val();

        $('.video-thumb').removeClass('active');
        $(this).addClass('active');

        $('#judulVideo').text(title);
        $('#kode_materi').val(kode);
        $('#btnFullDiskusiGuru').attr('href', baseUrl + 'sw-guru/diskusi?materi=' + encodeURIComponent(kode));

        if (player && player.loadVideoById) {
            player.loadVideoById(vId);
        } else {
            $('#mainVideo').attr('src', 'https://www.youtube.com/embed/' + vId + '?enablejsapi=1&rel=0&modestbranding=1');
        }

        lastDisplayedId = 0;
        activeParticipants = [];
        editingMessageId = null;
        $('#btn_cancel_edit').addClass('d-none');
        $('#msg_input').val('');
        $('#chatContainer').html('<div class="text-center py-10"><span class="spinner-border text-primary"></span></div>');

        loadChatMateri(kode, idguru, true);
        loadFileMateri(kode);

        const container = document.getElementById('carouselTrack');
        const scrollLeft = this.offsetLeft - container.offsetWidth / 2 + this.offsetWidth / 2;
        container.scrollTo({
            left: scrollLeft,
            behavior: 'smooth'
        });
    });

    function loadChatMateri(kode, idguru, shouldScroll = false) {
        if (!kode) return;
        let postData = {
            kode_materi: kode,
            idguru: idguru,
            last_id: lastDisplayedId
        };
        postData[guruCsrfName] = guruCsrfHash;

        $.post(baseUrl + "sw-guru/materi/get-chat-materi", postData, function(res) {
            if (res && res.token) guruCsrfHash = res.token;

            if (lastDisplayedId === 0) {
                $('#chatContainer').html('');
            }

            if (res.participants && Array.isArray(res.participants)) {
                res.participants.forEach(p => {
                    if (p && !activeParticipants.includes(p)) activeParticipants.push(p);
                });
            }

            const data = res.messages;
            const myEmail = (res.my_email || '').toLowerCase();

            if (data && data.length > 0) {
                let html = '';
                let hasNewMessage = false;
                const currentTime = Math.floor(Date.now() / 1000);

                data.forEach(m => {
                    const msgId = parseInt(m.id_chat_materi);
                    const senderName = m.nama && m.nama.trim() !== '' ? m.nama : 'Peserta';

                    if (m.nama && !activeParticipants.includes(m.nama)) activeParticipants.push(m.nama);

                    if (msgId > lastDisplayedId) {
                        lastDisplayedId = msgId;
                        hasNewMessage = true;

                        const isMe = (m.email || '').toLowerCase() === myEmail;
                        const timeStr = new Date(m.date_created * 1000).toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        });

                        const defaultAvatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(senderName)}&background=0086a7&color=fff&bold=true`;
                        const avatarUrl = (m.gambar && m.gambar !== 'default.png' && m.gambar !== 'default.jpg')
                            ? baseUrl + 'assets/app-assets/user/' + m.gambar
                            : defaultAvatar;

                        if (isMe) {
                            const timeDiff = currentTime - m.date_created;
                            let actionButtons = '';
                            if (timeDiff <= 300) {
                                actionButtons = `
                                    <div class="mt-1 me-2">
                                        <span id="btn-edit-${msgId}" class="badge badge-light-primary badge-sm cursor-pointer text-hover-primary me-1" onclick="editMessage('${msgId}', '${encodeURIComponent(m.text)}')">
                                            <i class="ki-duotone ki-pencil fs-9 me-1"><span class="path1"></span><span class="path2"></span></i>Edit
                                        </span>
                                        <span class="badge badge-light-danger badge-sm cursor-pointer text-hover-danger" onclick="deleteMessage('${msgId}', ${m.date_created})">
                                            <i class="ki-duotone ki-trash fs-9 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>Hapus
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
                                                <img src="${avatarUrl}" onerror="this.onerror=null;this.src='${defaultAvatar}'" />
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
                                <div class="d-flex justify-content-start mb-3" id="chat-item-${msgId}">
                                    <div class="d-flex flex-column align-items-start" style="max-width: 88%;">
                                        <div class="d-flex align-items-center mb-1">
                                            <div class="symbol symbol-25px symbol-circle me-2">
                                                <img src="${avatarUrl}" onerror="this.onerror=null;this.src='${defaultAvatar}'" />
                                            </div>
                                            <span class="fs-8 fw-bold text-gray-900 me-2">${senderName}</span>
                                            <span class="text-muted fs-9">${timeStr}</span>
                                        </div>
                                        <div class="p-3 chat-bubble-other text-gray-800 fw-semibold text-start chat-message-text fs-7">
                                            ${formatMessage(m.text)}
                                        </div>
                                        <div class="mt-1 ms-2">
                                            <span class="badge badge-light badge-sm cursor-pointer text-hover-primary" onclick="replyTo('${senderName.replace(/'/g, "\\'")}')">
                                                <i class="ki-duotone ki-left fs-9 me-1"><span class="path1"></span><span class="path2"></span></i>Balas
                                            </span>
                                        </div>
                                    </div>
                                </div>`;
                        }
                    }
                });

                if (hasNewMessage) {
                    if ($('#chatContainer').find('.empty-chat-placeholder').length > 0) {
                        $('#chatContainer').html('');
                    }
                    $('#chatContainer').append(html);
                    if (shouldScroll) {
                        const chatBox = document.getElementById('chatContainer');
                        chatBox.scrollTop = chatBox.scrollHeight;
                    }
                }
            } else if (lastDisplayedId === 0) {
                $('#chatContainer').html('<div class="empty-chat-placeholder text-center py-10 text-muted fs-7">Belum ada diskusi di materi ini.</div>');
            }
        }, 'json');
    }

    function loadFileMateri(kode) {
        if (!kode) return;
        let postData = { kode_materi: kode };
        postData[guruCsrfName] = guruCsrfHash;
        $.post(baseUrl + "sw-guru/materi/get-file-materi", postData, function(res) {
            $('#file_container').html(res);
        });
    }

    function formatMessage(text) {
        if (!text) return "";
        text = text.replace(/(?:\r\n|\r|\n)/g, '<br>');
        var urlRegex = /(https?:\/\/[^\s]+)/g;
        text = text.replace(urlRegex, url => `<a href="${url}" target="_blank" class="fw-bold text-primary text-decoration-underline">${url}</a>`);
        var mentionRegex = /@\[(.*?)\]/g;
        text = text.replace(mentionRegex, `<span class="badge badge-light-primary text-primary fw-bold px-2 py-1 me-1"><i class="ki-duotone ki-profile-circle text-primary me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>@$1</span>`);
        return text;
    }

    function replyTo(nama) {
        const input = $('#msg_input');
        const currentText = input.val();
        input.val(`${currentText ? currentText + ' ' : ''}@[${nama}] `);
        input.focus();
    }

    function handleMentionInput() {
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
                    const dropdown = $('#mention_dropdown');
                    let html = '';
                    matches.forEach(name => {
                        html += `
                        <div class="mention-item d-flex align-items-center" onclick="insertMention('${name.replace(/'/g, "\\'")}', ${lastAtIndex}, ${cursorPosition})">
                            <div class="symbol symbol-25px symbol-circle me-2">
                                <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=0086a7&color=fff" alt="Avatar">
                            </div>
                            <span class="fw-bold fs-7 text-gray-800">${name}</span>
                        </div>`;
                    });
                    dropdown.html(html).show();
                    return;
                }
            }
        }
        $('#mention_dropdown').hide();
    }

    function insertMention(name, atIndex, cursorPosition) {
        const input = $('#msg_input');
        const val = input.val();
        const beforeAt = val.substring(0, atIndex);
        const afterCursor = val.substring(cursorPosition);
        input.val(`${beforeAt}@[${name}] ${afterCursor}`);
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
        if ((currentTime - timeStamp) > 300) {
            Swal.fire('Ditolak!', 'Pesan yang dikirim lebih dari 5 menit yang lalu tidak dapat dihapus.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Hapus Pesan?',
            text: 'Pesan ini akan dihapus secara permanen dari diskusi.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                let postData = { id_chat: id, idguru: $('#idguru').val() };
                postData[guruCsrfName] = guruCsrfHash;

                $.post(baseUrl + "sw-guru/materi/delete-chat-materi", postData, function(res) {
                    if (res && res.token) guruCsrfHash = res.token;
                    $('#chat-item-' + id).fadeOut(250, function() {
                        $(this).remove();
                    });
                }, 'json');
            }
        });
    }

    $('#chat_materi').click(function() {
        const btn = $(this);
        const textarea = $('#msg_input');
        const chat = textarea.val().trim();
        const kode = $('#kode_materi').val();
        const idguru = $('#idguru').val();

        if (chat === "") return;

        $('#mention_dropdown').hide();
        btn.prop('disabled', true);
        textarea.prop('disabled', true);

        if (editingMessageId) {
            let postData = {
                id_chat: editingMessageId,
                text: chat,
                idguru: idguru
            };
            postData[guruCsrfName] = guruCsrfHash;

            $.post(baseUrl + "sw-guru/materi/update-chat-materi", postData, function(res) {
                if (res && res.token) guruCsrfHash = res.token;
                $('#msg-text-' + editingMessageId).html(formatMessage(chat));
                $('#btn-edit-' + editingMessageId).attr('onclick', `editMessage('${editingMessageId}', '${encodeURIComponent(chat)}')`);
                editingMessageId = null;
                $('#btn_cancel_edit').addClass('d-none');
                textarea.attr('placeholder', 'Tulis jawaban atau tanggapan... (Gunakan @ untuk mention)');
                textarea.val('').prop('disabled', false).focus();
                btn.prop('disabled', false);
            }, 'json').fail(function() {
                textarea.prop('disabled', false);
                btn.prop('disabled', false);
            });
        } else {
            let postData = {
                chat_materi: chat,
                kode_materi: kode,
                idguru: idguru,
            };
            postData[guruCsrfName] = guruCsrfHash;

            $.post(baseUrl + "sw-guru/materi/chat-materi", postData, function(res) {
                if (res && res.token) guruCsrfHash = res.token;
                btn.prop('disabled', false);
                textarea.val('').prop('disabled', false).focus();
                loadChatMateri(kode, idguru, true);
            }, 'json').fail(function() {
                textarea.prop('disabled', false);
                btn.prop('disabled', false);
            });
        }
    });

    $(document).ready(function() {
        const initKode = $('#kode_materi').val();
        const idguru = $('#idguru').val();
        loadChatMateri(initKode, idguru, true);
        loadFileMateri(initKode);

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

        $('#msg_input').on('keyup input', handleMentionInput);

        $('#btn_cancel_edit').on('click', function() {
            editingMessageId = null;
            $('#msg_input').val('').prop('disabled', false).focus();
            $('#msg_input').attr('placeholder', 'Tulis jawaban atau tanggapan... (Gunakan @ untuk mention)');
            $(this).addClass('d-none');
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('#guru_chat_footer').length) {
                $('#mention_dropdown').hide();
            }
        });

        setInterval(() => loadChatMateri($('#kode_materi').val(), $('#idguru').val(), false), 4000);
    });
</script>
<?= $this->endSection(); ?>

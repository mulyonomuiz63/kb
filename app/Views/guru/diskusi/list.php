<?= $this->extend('template/app'); ?>

<?= $this->section('styles'); ?>
<style>
    .diskusi-wrapper {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        overflow: hidden;
        display: flex;
        height: calc(100vh - 200px);
        min-height: 560px;
        max-height: 760px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .diskusi-sidebar {
        width: 370px;
        border-right: 1px solid #e2e8f0;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
    }

    .diskusi-list-scroll {
        flex-grow: 1;
        overflow-y: auto;
    }

    .chat-item {
        cursor: pointer;
        padding: 14px 18px;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }

    .chat-item:hover {
        background-color: #f8fafc;
    }

    .chat-item.active {
        background-color: #f0f9ff;
        border-left: 4px solid #009ef7;
    }

    .diskusi-main {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        background: #f8fafc;
        position: relative;
        min-width: 0;
    }

    .chat-history {
        flex-grow: 1;
        padding: 20px;
        overflow-y: auto;
        background-color: #f8fafc;
    }

    .bubble-me {
        background: #e0f2fe;
        border: 1px solid #bae6fd;
        color: #1e293b;
        border-radius: 14px 14px 2px 14px;
        padding: 10px 14px;
        position: relative;
        min-width: 90px;
        max-width: 100%;
        word-break: break-word;
    }

    .bubble-them {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #1e293b;
        border-radius: 14px 14px 14px 2px;
        padding: 10px 14px;
        position: relative;
        min-width: 90px;
        max-width: 100%;
        word-break: break-word;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    }

    .chat-message-text {
        font-size: 0.92rem;
        line-height: 1.45;
        white-space: pre-line;
        word-break: break-word;
    }

    .mention-dropdown {
        position: absolute;
        bottom: 100%;
        left: 20px;
        z-index: 1050;
        width: 300px;
        max-height: 220px;
        overflow-y: auto;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 0.75rem;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        display: none;
        margin-bottom: 8px;
    }

    .mention-item {
        padding: 10px 14px;
        cursor: pointer;
        transition: background-color 0.15s ease;
        border-bottom: 1px solid #f1f5f9;
    }

    .mention-item:last-child {
        border-bottom: none;
    }

    .mention-item:hover {
        background-color: #e0f2fe;
        color: #009ef7;
    }

    @media (max-width: 991.98px) {
        .diskusi-sidebar {
            width: 100%;
        }
        .diskusi-wrapper.mobile-chat-open .diskusi-sidebar {
            display: none !important;
        }
        .diskusi-wrapper:not(.mobile-chat-open) .diskusi-main {
            display: none !important;
        }
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="diskusi-wrapper" id="diskusiWrapper">
            <!-- SIDEBAR DAFTAR DISKUSI MATERI -->
            <div class="diskusi-sidebar">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white">
                    <div>
                        <h4 class="fw-bolder text-gray-900 m-0 d-flex align-items-center">
                            <i class="ki-duotone ki-messages fs-2 text-primary me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            Diskusi Materi
                        </h4>
                        <span class="text-muted fs-8">Pertanyaan & diskusi peserta di materi Anda</span>
                    </div>
                    <button class="btn btn-sm btn-primary fw-bold rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modal_materi" title="Pilih Materi">
                        <i class="ki-duotone ki-plus fs-4 me-1"></i> Materi
                    </button>
                </div>

                <div class="p-3 border-bottom bg-light-subtle">
                    <div class="position-relative">
                        <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle-y ms-3"><span class="path1"></span><span class="path2"></span></i>
                        <input type="text" class="form-control form-control-sm form-control-solid ps-10" placeholder="Cari judul materi atau kelas..." id="searchChat">
                    </div>
                </div>

                <div class="diskusi-list-scroll" id="sidebarChatList">
                    <?php if (!empty($diskusi)): ?>
                        <?php foreach ($diskusi as $d): ?>
                            <div class="chat-item d-flex align-items-start"
                                 data-materi="<?= esc($d['materi']) ?>"
                                 data-namamateri="<?= esc($d['nama_materi']) ?>"
                                 data-mapel="<?= esc($d['nama_mapel'] ?? '') ?>"
                                 data-kelas="<?= esc($d['nama_kelas'] ?? '') ?>"
                                 data-videourl="<?= esc($d['video_url'] ?? '') ?>">
                                <div class="symbol symbol-45px symbol-circle me-3 flex-shrink-0 mt-1">
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($d['nama_materi']) ?>&background=0086a7&color=fff&bold=true" alt="">
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="fw-bold fs-6 text-gray-900 text-truncate pe-2"><?= esc($d['nama_materi']) ?></div>
                                        <?php if (!empty($d['last_time'])): ?>
                                            <span class="text-muted fs-9 flex-shrink-0"><?= date('d/m H:i', $d['last_time']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($d['nama_mapel']) || !empty($d['nama_kelas'])): ?>
                                        <div class="mb-1">
                                            <span class="badge badge-light-primary fs-9 px-2 py-0 text-truncate" style="max-width: 100%;">
                                                <?= esc($d['nama_mapel'] ?? '') ?><?= !empty($d['nama_kelas']) ? ' • ' . esc($d['nama_kelas']) : '' ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-gray-500 fs-8 text-truncate pe-2">
                                            <?php if (!empty($d['last_message'])): ?>
                                                <span class="fw-semibold text-gray-700"><?= esc($d['last_sender']) ?>:</span> <?= esc($d['last_message']) ?>
                                            <?php else: ?>
                                                <span class="fst-italic">Klik untuk melihat diskusi</span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (($d['unread_count'] ?? 0) > 0): ?>
                                            <span class="badge badge-circle badge-danger unread-badge flex-shrink-0" data-materi="<?= esc($d['materi']) ?>">
                                                <?= ($d['unread_count'] > 99) ? '99+' : $d['unread_count'] ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-12 px-4" id="emptySidebarPlaceholder">
                            <i class="ki-duotone ki-messages fs-3x text-gray-300 mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            <p class="text-gray-600 fw-semibold fs-7 mb-2">Belum ada diskusi materi dari peserta</p>
                            <button class="btn btn-sm btn-light-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modal_materi">
                                <i class="ki-duotone ki-plus fs-5 me-1"></i> Mulai Diskusi Materi
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- AREA UTAMA RUANG DISKUSI -->
            <div class="diskusi-main">
                <div id="empty_state" class="d-flex flex-column align-items-center justify-content-center h-100 p-6 text-center">
                    <div class="symbol symbol-75px symbol-circle bg-light-primary mb-4">
                        <i class="ki-duotone ki-messages fs-2tx text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    </div>
                    <h4 class="fw-bold text-gray-800 mb-1">Panel Diskusi Materi Pengajar</h4>
                    <p class="text-muted fs-7 mw-400px mb-4">Pilih salah satu topik materi di sebelah kiri untuk menjawab pertanyaan peserta atau memulai diskusi baru pada modul video Anda.</p>
                    <button class="btn btn-sm btn-primary fw-bold rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modal_materi">
                        <i class="ki-duotone ki-plus fs-4 me-1"></i> Pilih Materi Anda
                    </button>
                </div>

                <div id="chat_area" style="display:none;" class="h-100 flex-column position-relative">
                    <!-- HEADER RUANG DISKUSI -->
                    <div class="px-4 py-3 border-bottom bg-white d-flex align-items-center justify-content-between shadow-xs">
                        <div class="d-flex align-items-center overflow-hidden pe-2">
                            <button type="button" class="btn btn-icon btn-sm btn-light me-2 d-lg-none flex-shrink-0" id="btnBackMobile">
                                <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </button>
                            <div class="overflow-hidden">
                                <div class="fw-bolder fs-5 text-gray-900 text-truncate" id="active_title">Nama Materi</div>
                                <div class="text-muted fs-8 text-truncate" id="active_subtitle">Diskusi Materi</div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="#" id="btnWatchVideo" class="btn btn-sm btn-light-primary fw-bold rounded-pill px-3 d-none">
                                <i class="ki-duotone ki-to-right fs-4 me-1"></i> Buka Video & Modul
                            </a>
                        </div>
                    </div>

                    <!-- RIWAYAT PESAN -->
                    <div class="chat-history" id="chat_history"></div>

                    <!-- FOOTER INPUT PESAN -->
                    <div class="p-3 p-md-4 border-top bg-white position-relative">
                        <div id="mention_dropdown" class="mention-dropdown shadow-lg"></div>

                        <div class="d-flex gap-2 align-items-end">
                            <textarea id="msg_input" class="form-control form-control-solid border border-gray-300 fs-7" rows="2" placeholder="Tulis jawaban atau tanggapan Anda... (Gunakan @ untuk mention peserta)" style="resize: none;"></textarea>
                            <button class="btn btn-light-danger btn-sm fw-bold d-none px-3 py-3" id="btn_cancel_edit">Batal</button>
                            <button class="btn btn-primary fw-bold px-4 py-3 flex-shrink-0" id="btn_send">
                                <i class="ki-duotone ki-send fs-4 me-1"><span class="path1"></span><span class="path2"></span></i> Kirim
                            </button>
                        </div>
                        <div class="text-muted fs-9 mt-1">Tekan <b>Enter</b> untuk kirim, <b>Shift + Enter</b> untuk baris baru</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PILIH MATERI PENGAJAR -->
<div class="modal fade" id="modal_materi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-500px">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-2">
                <div>
                    <h3 class="fw-bolder text-gray-900 mb-0">Pilih Materi yang Diampu</h3>
                    <span class="text-muted fs-8">Pilih materi untuk membuka ruang diskusi bersama peserta</span>
                </div>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div class="modal-body pt-3">
                <div class="position-relative mb-4">
                    <i class="ki-duotone ki-magnifier fs-2 text-gray-500 position-absolute top-50 translate-middle-y ms-4"><span class="path1"></span><span class="path2"></span></i>
                    <input type="text" id="search_materi" class="form-control form-control-solid ps-12" placeholder="Cari nama materi, mapel, atau kelas..." />
                </div>

                <div class="scroll-y mh-350px pe-2" id="list_materi">
                    <?php if (!empty($materi)): ?>
                        <?php foreach ($materi as $m): ?>
                            <div class="materi-item card card-bordered border-gray-200 p-3 mb-2 cursor-pointer bg-hover-light-primary"
                                onclick="startNewChat('<?= esc($m['kode_materi']) ?>', '<?= esc(addslashes($m['nama_materi'])) ?>', '<?= esc(addslashes($m['nama_mapel'] ?? '')) ?>', '<?= esc(addslashes($m['nama_kelas'] ?? '')) ?>', '<?= esc($m['video_url'] ?? '') ?>')"
                                data-search="<?= strtolower(esc(($m['nama_materi'] ?? '') . ' ' . ($m['nama_mapel'] ?? '') . ' ' . ($m['nama_kelas'] ?? ''))) ?>">

                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-40px me-3 flex-shrink-0">
                                        <span class="symbol-label bg-light-primary text-primary fw-bolder fs-5">
                                            <?= strtoupper(substr($m['nama_materi'] ?? 'M', 0, 1)) ?>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column overflow-hidden">
                                        <div class="fw-bold fs-6 text-gray-900 text-truncate"><?= esc($m['nama_materi']) ?></div>
                                        <div class="text-muted fs-8 text-truncate">
                                            <?= esc($m['nama_mapel'] ?? '') ?><?= !empty($m['nama_kelas']) ? ' • ' . esc($m['nama_kelas']) : '' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-8 text-muted fs-7">Belum ada materi yang diampu.</div>
                    <?php endif; ?>

                    <div id="no_results" class="text-center py-6 d-none">
                        <div class="text-muted fs-7">Materi tidak ditemukan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    let currentMateri = '';
    let lastDisplayedId = 0;
    let chatInterval = null;
    let editingMessageId = null;
    let activeParticipants = [];

    let chatCsrfName = '<?= csrf_token() ?>';
    let chatCsrfHash = '<?= csrf_hash() ?>';
    const myEmail = ('<?= esc($guruEmail ?? session()->get('email')) ?>').toLowerCase();

    $(document).ready(function() {
        $(document).on('click', '.chat-item', function() {
            const materi = $(this).data('materi');
            const namaMateri = $(this).data('namamateri');
            const mapel = $(this).data('mapel') || '';
            const kelas = $(this).data('kelas') || '';
            const videoUrl = $(this).data('videourl') || '';
            loadChat(materi, namaMateri, mapel, kelas, videoUrl);
        });

        $('#btnBackMobile').on('click', function() {
            $('#diskusiWrapper').removeClass('mobile-chat-open');
        });

        $('#searchChat').on('keyup input', function() {
            const q = $(this).val().toLowerCase().trim();
            $('#sidebarChatList .chat-item').each(function() {
                const text = ($(this).data('namamateri') + ' ' + $(this).data('mapel') + ' ' + $(this).data('kelas')).toLowerCase();
                $(this).toggleClass('d-none', !text.includes(q));
            });
        });

        $('#btn_send').on('click', function() {
            sendMessage();
        });

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
                sendMessage();
            }
        });

        $('#msg_input').on('keyup input', handleMentionInput);

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.diskusi-main').length) {
                $('#mention_dropdown').hide();
            }
        });

        $('#btn_cancel_edit').on('click', function() {
            editingMessageId = null;
            $('#msg_input').val('').prop('disabled', false).focus();
            $('#msg_input').attr('placeholder', 'Tulis jawaban atau tanggapan Anda... (Gunakan @ untuk mention peserta)');
            $(this).addClass('d-none');
        });

        $('#search_materi').on('keyup input', function() {
            const value = $(this).val().toLowerCase();
            let found = false;
            $('.materi-item').each(function() {
                if ($(this).attr('data-search').indexOf(value) > -1) {
                    $(this).removeClass('d-none');
                    found = true;
                } else {
                    $(this).addClass('d-none');
                }
            });
            $('#no_results').toggleClass('d-none', found);
        });

        <?php if (!empty($targetMateriInfo)): ?>
            startNewChat(
                '<?= esc($targetMateriInfo['kode_materi']) ?>',
                '<?= esc(addslashes($targetMateriInfo['nama_materi'])) ?>',
                '<?= esc(addslashes($targetMateriInfo['nama_mapel'] ?? '')) ?>',
                '<?= esc(addslashes($targetMateriInfo['nama_kelas'] ?? '')) ?>',
                '<?= esc($targetMateriInfo['video_url'] ?? '') ?>'
            );
        <?php endif; ?>
    });

    function startNewChat(kodeMateri, namaMateri, mapel = '', kelas = '', videoUrl = '') {
        $('#modal_materi').modal('hide');

        if ($(`.chat-item[data-materi="${kodeMateri}"]`).length === 0) {
            $('#emptySidebarPlaceholder').remove();
            const itemHtml = `
                <div class="chat-item d-flex align-items-start active"
                     data-materi="${kodeMateri}"
                     data-namamateri="${namaMateri}"
                     data-mapel="${mapel}"
                     data-kelas="${kelas}"
                     data-videourl="${videoUrl}">
                    <div class="symbol symbol-45px symbol-circle me-3 flex-shrink-0 mt-1">
                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(namaMateri)}&background=0086a7&color=fff&bold=true" alt="">
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="fw-bold fs-6 text-gray-900 text-truncate mb-1">${namaMateri}</div>
                        ${(mapel || kelas) ? `<div class="mb-1"><span class="badge badge-light-primary fs-9 px-2 py-0">${mapel}${kelas ? ' • ' + kelas : ''}</span></div>` : ''}
                        <div class="text-gray-500 fs-8 text-truncate">Diskusi aktif</div>
                    </div>
                </div>`;
            $('#sidebarChatList').prepend(itemHtml);
        }

        currentMateri = '';
        loadChat(kodeMateri, namaMateri, mapel, kelas, videoUrl);
    }

    function loadChat(materi, namaMateri, mapel = '', kelas = '', videoUrl = '') {
        $('#diskusiWrapper').addClass('mobile-chat-open');

        if (currentMateri === materi && $('#chat_area').is(':visible')) return;

        currentMateri = materi;
        lastDisplayedId = 0;
        editingMessageId = null;
        activeParticipants = [];

        $('#btn_cancel_edit').addClass('d-none');
        $('#msg_input').val('').attr('placeholder', 'Tulis jawaban atau tanggapan Anda... (Gunakan @ untuk mention peserta)');

        $('.chat-item').removeClass('active');
        const activeItem = $(`.chat-item[data-materi="${materi}"]`);
        activeItem.addClass('active');
        activeItem.find('.unread-badge').remove();

        $('#empty_state').attr('style', 'display: none !important;');
        $('#chat_area').attr('style', 'display: flex !important;');
        $('#active_title').text(namaMateri);

        const parts = [];
        if (mapel) parts.push(mapel);
        if (kelas) parts.push(kelas);
        $('#active_subtitle').text(parts.length ? parts.join(' • ') : 'Diskusi Materi');

        if (videoUrl) {
            $('#btnWatchVideo').attr('href', videoUrl).removeClass('d-none');
        } else {
            $('#btnWatchVideo').addClass('d-none');
        }

        $('#chat_history').html('<div class="text-center py-15"><span class="spinner-border text-primary"></span></div>');

        fetchMessages(materi);

        if (chatInterval) clearInterval(chatInterval);
        chatInterval = setInterval(function() {
            if (currentMateri) fetchMessages(currentMateri);
        }, 3500);
    }

    function fetchMessages(materi) {
        $.get(`<?= base_url('sw-guru/diskusi/get-messages') ?>/${encodeURIComponent(materi)}`, {
            last_id: lastDisplayedId
        }, function(response) {
            if (response.token) {
                chatCsrfHash = response.token;
            }
            if (response.video_url) {
                $('#btnWatchVideo').attr('href', response.video_url).removeClass('d-none');
            }
            if (response.nama_mapel || response.nama_kelas) {
                const parts = [];
                if (response.nama_mapel) parts.push(response.nama_mapel);
                if (response.nama_kelas) parts.push(response.nama_kelas);
                $('#active_subtitle').text(parts.join(' • '));
            }

            if (lastDisplayedId === 0) {
                $('#chat_history').html('');
            }

            if (response.participants && Array.isArray(response.participants)) {
                response.participants.forEach(p => {
                    if (p && !activeParticipants.includes(p)) activeParticipants.push(p);
                });
            }

            const data = response.messages;
            if (data && data.length > 0) {
                let html = '';
                data.forEach(m => {
                    const msgId = parseInt(m.id_chat_materi);
                    const senderName = m.nama && m.nama.trim() !== '' ? m.nama : 'Peserta';

                    if (m.nama && !activeParticipants.includes(m.nama)) {
                        activeParticipants.push(m.nama);
                    }

                    if (msgId > lastDisplayedId) {
                        lastDisplayedId = msgId;

                        const isMe = (m.email || '').toLowerCase() === myEmail;
                        const time = new Date(m.date_created * 1000).toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false
                        });

                        const defaultAvatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(senderName)}&background=0086a7&color=fff&bold=true`;
                        const avatarUrl = (m.gambar && m.gambar !== 'default.png' && m.gambar !== 'default.jpg')
                            ? `<?= base_url('assets/app-assets/user/') ?>/${m.gambar}`
                            : defaultAvatar;

                        if (isMe) {
                            const currentTime = Math.floor(Date.now() / 1000);
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
                            <div class="d-flex justify-content-end mb-4" id="chat-item-${msgId}">
                                <div class="d-flex flex-column align-items-end" style="max-width: 82%;">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="text-muted fs-9 me-2">${time}</span>
                                        <span class="badge badge-light-primary fs-9 me-2">Anda (Pengajar)</span>
                                        <div class="symbol symbol-30px symbol-circle">
                                            <img src="${avatarUrl}" onerror="this.onerror=null;this.src='${defaultAvatar}'">
                                        </div>
                                    </div>
                                    <div class="bubble-me">
                                        <div class="chat-message-text" id="msg-text-${msgId}">${formatMessage(m.text)}</div>
                                    </div>
                                    ${actionButtons}
                                </div>
                            </div>`;
                        } else {
                            html += `
                            <div class="d-flex justify-content-start mb-4" id="chat-item-${msgId}">
                                <div class="d-flex flex-column align-items-start" style="max-width: 82%;">
                                    <div class="d-flex align-items-center mb-1">
                                        <div class="symbol symbol-30px symbol-circle me-2">
                                            <img src="${avatarUrl}" onerror="this.onerror=null;this.src='${defaultAvatar}'">
                                        </div>
                                        <span class="fs-8 fw-bold text-gray-900 me-2">${senderName}</span>
                                        <span class="badge badge-light fs-9 me-2">Peserta</span>
                                        <span class="text-muted fs-9">${time}</span>
                                    </div>
                                    <div class="bubble-them">
                                        <div class="chat-message-text">${formatMessage(m.text)}</div>
                                    </div>
                                    <div class="mt-1 ms-1">
                                        <span class="badge badge-light badge-sm cursor-pointer text-hover-primary" onclick="replyTo('${senderName.replace(/'/g, "\\'")}')">
                                            <i class="ki-duotone ki-left fs-9 me-1"><span class="path1"></span><span class="path2"></span></i>Balas
                                        </span>
                                    </div>
                                </div>
                            </div>`;
                        }
                    }
                });

                if (html !== '') {
                    if ($('#chat_history').find('.empty-chat-placeholder').length > 0) {
                        $('#chat_history').html('');
                    }
                    $('#chat_history').append(html);
                    $('#chat_history').animate({
                        scrollTop: $('#chat_history')[0].scrollHeight
                    }, 250);
                }
            } else if (lastDisplayedId === 0) {
                $('#chat_history').html(`
                    <div class="empty-chat-placeholder text-center py-15 text-muted">
                        <i class="ki-duotone ki-messages fs-3x text-gray-300 mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                        <div class="fw-bold text-gray-700 fs-6 mb-1">Belum ada diskusi pada materi ini</div>
                        <div class="fs-8">Anda dapat memulai diskusi atau menyapa peserta pada materi ini.</div>
                    </div>
                `);
            }
        });
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
        input.val(`${beforeAt}@[${name}] ${afterCursor}`);
        $('#mention_dropdown').hide();
        input.focus();
    }

    function formatMessage(text) {
        if (!text) return "";
        var urlRegex = /(https?:\/\/[^\s]+)/g;
        text = text.replace(urlRegex, url => `<a href="${url}" target="_blank" class="fw-bold text-primary text-decoration-underline">${url}</a>`);
        var mentionRegex = /@\[(.*?)\]/g;
        text = text.replace(mentionRegex, `<span class="badge badge-light-primary text-primary fw-bold px-2 py-1 me-1"><i class="ki-duotone ki-profile-circle text-primary me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>@$1</span>`);
        return text;
    }

    function sendMessage() {
        const text = $('#msg_input').val().trim();
        if (!text || !currentMateri) return;

        $('#mention_dropdown').hide();
        $('#msg_input').prop('disabled', true);
        $('#btn_cancel_edit').prop('disabled', true);
        $('#btn_send').prop('disabled', true);

        if (editingMessageId) {
            let postData = { id_chat: editingMessageId, text: text };
            postData[chatCsrfName] = chatCsrfHash;

            $.post(`<?= base_url('sw-guru/diskusi/update-message') ?>`, postData, function(res) {
                if (res && res.token) chatCsrfHash = res.token;
                $('#msg-text-' + editingMessageId).html(formatMessage(text));
                $('#btn-edit-' + editingMessageId).attr('onclick', `editMessage('${editingMessageId}', '${encodeURIComponent(text)}')`);
                editingMessageId = null;
                $('#btn_cancel_edit').addClass('d-none').prop('disabled', false);
                $('#btn_send').prop('disabled', false);
                $('#msg_input').attr('placeholder', 'Tulis jawaban atau tanggapan Anda... (Gunakan @ untuk mention peserta)');
                $('#msg_input').prop('disabled', false).val('').focus();
            }).fail(function(xhr) {
                $('#msg_input').prop('disabled', false);
                $('#btn_cancel_edit').prop('disabled', false);
                $('#btn_send').prop('disabled', false);
                let errMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Gagal mengedit pesan.";
                Swal.fire("Gagal", errMsg, "error");
            });
        } else {
            let postData = {
                materi: currentMateri,
                text: text
            };
            postData[chatCsrfName] = chatCsrfHash;

            $.post(`<?= base_url('sw-guru/diskusi/send') ?>`, postData, function(res) {
                if (res && res.token) chatCsrfHash = res.token;
                $('#msg_input').prop('disabled', false).val('').focus();
                $('#btn_send').prop('disabled', false);
                fetchMessages(currentMateri);
            }).fail(function() {
                $('#msg_input').prop('disabled', false);
                $('#btn_send').prop('disabled', false);
                Swal.fire("Gagal", "Gagal mengirim pesan, silakan coba lagi.", "error");
            });
        }
    }

    function editMessage(id, encodedText) {
        editingMessageId = id;
        $('#msg_input').val(decodeURIComponent(encodedText)).focus();
        $('#msg_input').attr('placeholder', 'Mengedit pesan... (Tekan Enter untuk simpan)');
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
                let postData = { id_chat: id };
                postData[chatCsrfName] = chatCsrfHash;

                $.post(`<?= base_url('sw-guru/diskusi/delete-message') ?>`, postData, function(res) {
                    if (res && res.token) chatCsrfHash = res.token;
                    $('#chat-item-' + id).fadeOut(250, function() {
                        $(this).remove();
                    });
                }).fail(function(xhr) {
                    let errMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Gagal menghapus pesan.";
                    Swal.fire("Gagal", errMsg, "error");
                });
            }
        });
    }
</script>
<?= $this->endSection(); ?>

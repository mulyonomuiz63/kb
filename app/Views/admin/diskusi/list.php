<?= $this->extend('template/app'); ?>

<?= $this->section('styles'); ?>
<style>
    .chat-history {
        scroll-behavior: smooth;
    }

    .chat-history::-webkit-scrollbar {
        width: 6px;
    }

    .chat-history::-webkit-scrollbar-thumb {
        background-color: var(--bs-gray-300);
        border-radius: 10px;
    }

    .chat-item {
        cursor: pointer;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }

    .chat-item:hover {
        background-color: var(--bs-gray-100);
    }

    .chat-item.active {
        background-color: #f0f9ff;
        border-left-color: #009ef7;
    }

    .chat-message-text {
        word-wrap: break-word;
        white-space: pre-line;
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
        border: 1px solid var(--bs-gray-300);
        border-radius: 0.65rem;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.15);
        display: none;
        margin-bottom: 8px;
    }

    .mention-item {
        padding: 9px 14px;
        cursor: pointer;
        transition: background-color 0.15s ease;
        border-bottom: 1px solid #f1f5f9;
    }

    .mention-item:last-child {
        border-bottom: none;
    }

    .mention-item:hover {
        background-color: var(--bs-light-primary);
        color: var(--bs-primary);
    }

    .sticky-lg-top {
        transition: top 0.3s ease;
    }

    #kt_chat_contacts_body::-webkit-scrollbar {
        width: 4px;
    }
    #kt_chat_contacts_body::-webkit-scrollbar-thumb {
        background-color: var(--bs-gray-300);
        border-radius: 10px;
    }

    #kt_app_content_container {
        overflow: visible !important;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="d-flex flex-column flex-lg-row align-items-stretch">

                <!-- SIDEBAR KIRI: DAFTAR GURU & DISKUSI MATERI -->
                <div class="flex-column flex-lg-row-auto w-100 w-lg-350px w-xl-400px mb-8 mb-lg-0">
                    <div class="sticky-lg-top" style="top: 90px; z-index: 9;">
                        <div class="card card-flush shadow-sm">

                            <div class="card-header pt-6 pb-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="fw-bolder text-gray-900 m-0 fs-5">Diskusi Materi Brevet</h3>
                                    <span class="text-muted fs-8">Dikelompokkan per Pengajar</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-primary fw-bold rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modal_materi">
                                    <i class="ki-duotone ki-plus fs-4 me-1"></i> Materi
                                </button>
                            </div>

                            <div class="px-6 pb-3">
                                <div class="position-relative">
                                    <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 ms-4 translate-middle-y"><span class="path1"></span><span class="path2"></span></i>
                                    <input type="text" class="form-control form-control-sm form-control-solid ps-11" id="searchChat" placeholder="Cari judul materi atau pengajar..." />
                                </div>
                            </div>

                            <div class="card-body pt-2" id="kt_chat_contacts_body">
                                <div class="scroll-y me-n5 pe-5" style="max-height: 62vh;" id="chat_list">

                                    <div class="accordion accordion-icon-toggle" id="accordionGuru">
                                        <?php foreach ($diskusi as $index => $group): ?>
                                            <?php
                                            if (empty($group['materi'])) continue;

                                            $totalUnreadGuru = 0;
                                            foreach ($group['materi'] as $m) {
                                                $totalUnreadGuru += (int) $m['unread_count'];
                                            }
                                            ?>

                                            <div class="mb-4 guru-group-item" data-guruname="<?= strtolower(esc($group['nama_guru'])) ?>">
                                                <div class="accordion-header py-3 px-3 rounded bg-light-subtle d-flex align-items-center cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapse<?= $group['id_guru'] ?>" aria-expanded="<?= $totalUnreadGuru > 0 ? 'true' : 'false' ?>">
                                                    <span class="accordion-icon me-2"><i class="ki-duotone ki-arrow-right fs-4"><span class="path1"></span><span class="path2"></span></i></span>
                                                    <div class="d-flex align-items-center justify-content-between w-100 overflow-hidden">
                                                        <div class="d-flex align-items-center overflow-hidden">
                                                            <div class="symbol symbol-35px symbol-circle me-3 position-relative flex-shrink-0">
                                                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($group['nama_guru']) ?>&background=0086a7&color=fff&bold=true" alt="Pic">
                                                            </div>
                                                            <div class="overflow-hidden">
                                                                <div class="fs-6 fw-bold text-gray-900 text-truncate"><?= esc($group['nama_guru']) ?></div>
                                                                <div class="fs-8 text-muted"><?= count($group['materi']) ?> Topik Materi</div>
                                                            </div>
                                                        </div>
                                                        <?php if ($totalUnreadGuru > 0): ?>
                                                            <span class="badge badge-circle badge-danger fs-9 ms-2 flex-shrink-0">
                                                                <?= ($totalUnreadGuru > 99) ? '99+' : $totalUnreadGuru ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div id="collapse<?= $group['id_guru'] ?>" class="collapse <?= $totalUnreadGuru > 0 ? 'show' : '' ?> fs-6 ps-4 pt-2" data-bs-parent="#accordionGuru">
                                                    <div class="d-flex flex-column gap-1">
                                                        <?php foreach ($group['materi'] as $m): ?>
                                                            <div class="chat-item d-flex justify-content-between align-items-start p-3 rounded"
                                                                data-materi="<?= esc($m['materi']) ?>"
                                                                data-namamateri="<?= esc($m['nama_materi']) ?>"
                                                                data-emailguru="<?= esc($group['email_guru']) ?>"
                                                                data-namaguru="<?= esc($group['nama_guru']) ?>"
                                                                data-mapel="<?= esc($m['nama_mapel'] ?? '') ?>"
                                                                data-kelas="<?= esc($m['nama_kelas'] ?? '') ?>"
                                                                data-videourl="<?= esc($m['video_url'] ?? '') ?>">

                                                                <div class="d-flex flex-column overflow-hidden pe-2">
                                                                    <span class="text-gray-900 fw-bold fs-6 text-truncate"><?= esc($m['nama_materi']) ?></span>
                                                                    <?php if (!empty($m['nama_mapel']) || !empty($m['nama_kelas'])): ?>
                                                                        <span class="badge badge-light-primary fs-9 px-2 py-0 my-1 align-self-start text-truncate" style="max-width: 100%;">
                                                                            <?= esc($m['nama_mapel'] ?? '') ?><?= !empty($m['nama_kelas']) ? ' • ' . esc($m['nama_kelas']) : '' ?>
                                                                        </span>
                                                                    <?php endif; ?>
                                                                    <?php if (!empty($m['last_message'])): ?>
                                                                        <span class="text-muted fs-8 text-truncate">
                                                                            <b><?= esc($m['last_sender']) ?>:</b> <?= esc($m['last_message']) ?>
                                                                        </span>
                                                                    <?php else: ?>
                                                                        <span class="text-muted fw-semibold fs-8">Klik untuk diskusi</span>
                                                                    <?php endif; ?>
                                                                </div>

                                                                <div class="d-flex flex-column align-items-end flex-shrink-0">
                                                                    <?php if (!empty($m['last_time'])): ?>
                                                                        <span class="text-muted fs-9 mb-1"><?= date('d/m H:i', $m['last_time']) ?></span>
                                                                    <?php endif; ?>
                                                                    <?php if ($m['unread_count'] > 0): ?>
                                                                        <span class="badge badge-sm badge-circle badge-primary unread-badge" data-materi="<?= esc($m['materi']) ?>">
                                                                            <?= ($m['unread_count'] > 99) ? '99+' : $m['unread_count'] ?>
                                                                        </span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PANEL KANAN: RUANG DISKUSI MATERI -->
                <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                    <div class="card shadow-sm h-100">

                        <div id="empty_state" class="card-body d-flex flex-column justify-content-center align-items-center text-center h-100" style="min-height: 55vh;">
                            <div class="symbol symbol-75px symbol-circle bg-light-primary mb-4">
                                <i class="ki-duotone ki-messages fs-2tx text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </div>
                            <h3 class="text-gray-800 fw-bold mb-1">Monitoring & Bantuan Diskusi Materi</h3>
                            <div class="text-gray-500 fw-semibold fs-7 mw-400px mb-4">Pilih salah satu materi di sebelah kiri atau klik tombol <b>+ Materi</b> untuk melihat dan membalas diskusi peserta.</div>
                            <button type="button" class="btn btn-sm btn-primary fw-bold rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modal_materi">
                                <i class="ki-duotone ki-plus fs-4 me-1"></i> Pilih Materi Diskusi
                            </button>
                        </div>

                        <div id="chat_area" class="d-none flex-column h-100">

                            <div class="card-header border-bottom py-4 d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column overflow-hidden pe-3">
                                    <h3 class="fs-5 fw-bolder text-gray-900 mb-0 text-truncate" id="active_title">Nama Materi</h3>
                                    <span class="text-muted fs-8 text-truncate mt-1" id="active_subtitle">Diskusi Materi</span>
                                </div>
                                <div class="card-toolbar flex-shrink-0">
                                    <a href="#" id="btnWatchVideo" class="btn btn-sm btn-light-primary fw-bold rounded-pill px-3 d-none">
                                        <i class="ki-duotone ki-to-right fs-4 me-1"></i> Buka Video Materi
                                    </a>
                                </div>
                            </div>

                            <div class="card-body p-5 bg-light-subtle">
                                <div class="scroll-y me-n3 pe-3 chat-history"
                                    id="chat_history"
                                    style="min-height: 420px; height: 500px; overflow-y: auto;">
                                </div>
                            </div>

                            <div class="card-footer p-4 bg-white position-relative" id="kt_chat_messenger_footer">
                                <div id="mention_dropdown" class="mention-dropdown shadow-lg"></div>

                                <div class="d-flex align-items-end gap-2">
                                    <textarea id="msg_input" class="form-control form-control-solid border border-gray-300 fs-7" rows="2" placeholder="Tuliskan balasan Anda... (Gunakan @ untuk mention peserta)" style="resize: none;"></textarea>
                                    <button class="btn btn-light-danger btn-sm fw-bold d-none px-3 py-3" id="btn_cancel_edit" title="Batal Edit">
                                        Batal
                                    </button>
                                    <button class="btn btn-primary fw-bold px-4 py-3 flex-shrink-0" id="btn_send">
                                        <i class="ki-duotone ki-send fs-3 me-1"><span class="path1"></span><span class="path2"></span></i> Kirim
                                    </button>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <span class="text-muted fs-9">Tekan <b>Enter</b> untuk kirim, <b>Shift + Enter</b> untuk baris baru</span>
                                    <span class="text-muted fs-9" id="sender_identity_badge"></span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Materi Baru -->
<div class="modal fade" id="modal_materi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content rounded-4 border-0 shadow-lg">

            <div class="modal-header pb-2 border-0 justify-content-between">
                <div>
                    <h3 class="fw-bolder text-gray-900 mb-0">Pilih Materi Diskusi</h3>
                    <span class="text-muted fs-8">Pilih materi di bawah ini untuk memulai atau membuka ruang diskusi</span>
                </div>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div class="modal-body px-6 pt-3 pb-6">
                <div class="position-relative mb-4">
                    <i class="ki-duotone ki-magnifier fs-2 text-gray-500 position-absolute top-50 translate-middle-y ms-4"><span class="path1"></span><span class="path2"></span></i>
                    <input type="text" id="search_materi_modal" class="form-control form-control-solid ps-12" placeholder="Cari nama materi, mapel, kelas, atau pengajar..." />
                </div>

                <div class="mh-350px scroll-y pe-2">
                    <?php foreach ($materi as $m): ?>
                        <div class="materi-modal-item d-flex flex-stack p-3 mb-2 rounded border border-gray-200 bg-hover-light-primary cursor-pointer"
                            data-search="<?= strtolower(esc(($m['nama_materi'] ?? '') . ' ' . ($m['nama_mapel'] ?? '') . ' ' . ($m['nama_kelas'] ?? '') . ' ' . ($m['nama_guru'] ?? ''))) ?>"
                            onclick="startNewChat('<?= esc($m['kode_materi']) ?>', '<?= esc(addslashes($m['nama_materi'])) ?>', '<?= esc(addslashes($m['email_guru'] ?? '')) ?>', '<?= esc(addslashes($m['nama_guru'] ?? '')) ?>', '<?= esc(addslashes($m['nama_mapel'] ?? '')) ?>', '<?= esc(addslashes($m['nama_kelas'] ?? '')) ?>', '<?= esc($m['video_url'] ?? '') ?>')">

                            <div class="d-flex align-items-center overflow-hidden">
                                <div class="symbol symbol-40px symbol-circle me-3 flex-shrink-0">
                                    <span class="symbol-label bg-light-primary">
                                        <i class="ki-duotone ki-book-open text-primary fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                    </span>
                                </div>
                                <div class="d-flex flex-column overflow-hidden">
                                    <span class="text-gray-900 fw-bold fs-6 text-truncate"><?= esc($m['nama_materi']) ?></span>
                                    <span class="text-muted fw-semibold fs-8 text-truncate">
                                        <?= esc($m['nama_mapel'] ?? '') ?><?= !empty($m['nama_kelas']) ? ' • ' . esc($m['nama_kelas']) : '' ?> • Pengajar: <?= esc($m['nama_guru'] ?? 'Pengajar') ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts') ?>
<script>
    let currentMateri = '';
    let currentEmailGuru = '';
    let currentNamaGuru = '';
    let lastDisplayedId = 0;
    let chatInterval = null;
    let editingMessageId = null;

    let chatCsrfName = '<?= csrf_token() ?>';
    let chatCsrfHash = '<?= csrf_hash() ?>';

    let activeParticipants = [];

    $(document).ready(function() {
        // Klik Sidebar Materi
        $(document).on('click', '.chat-item', function() {
            const materi = $(this).data('materi');
            const namaMateri = $(this).data('namamateri');
            currentEmailGuru = $(this).data('emailguru') || '';
            currentNamaGuru = $(this).data('namaguru') || '';
            const mapel = $(this).data('mapel') || '';
            const kelas = $(this).data('kelas') || '';
            const videoUrl = $(this).data('videourl') || '';

            $('.chat-item').removeClass('active');
            $(this).addClass('active');
            $(this).find('.unread-badge').remove();

            loadChat(materi, namaMateri, mapel, kelas, videoUrl);
        });

        // Pencarian di sidebar kiri
        $('#searchChat').on('keyup input', function() {
            const q = $(this).val().toLowerCase().trim();
            if (!q) {
                $('.guru-group-item').removeClass('d-none');
                $('.chat-item').removeClass('d-none');
                return;
            }
            $('.guru-group-item').each(function() {
                const group = $(this);
                const guruName = group.data('guruname') || '';
                let hasVisibleItem = false;
                group.find('.chat-item').each(function() {
                    const itemText = ($(this).data('namamateri') + ' ' + $(this).data('mapel') + ' ' + $(this).data('kelas') + ' ' + guruName).toLowerCase();
                    const match = itemText.includes(q);
                    $(this).toggleClass('d-none', !match);
                    if (match) hasVisibleItem = true;
                });
                group.toggleClass('d-none', !hasVisibleItem);
                if (hasVisibleItem) {
                    group.find('.collapse').addClass('show');
                }
            });
        });

        // Pencarian di modal pilih materi
        $('#search_materi_modal').on('keyup input', function() {
            const q = $(this).val().toLowerCase().trim();
            $('.materi-modal-item').each(function() {
                const s = $(this).data('search') || '';
                $(this).toggleClass('d-none', !s.includes(q));
            });
        });

        $('#btn_send').on('click', sendMessage);
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
            if (!$(e.target).closest('#kt_chat_messenger_footer').length) {
                $('#mention_dropdown').hide();
            }
        });

        $('#btn_cancel_edit').on('click', function() {
            editingMessageId = null;
            $('#msg_input').val('').prop('disabled', false).focus();
            $('#msg_input').attr('placeholder', 'Tuliskan balasan Anda... (Gunakan @ untuk mention peserta)');
            $(this).addClass('d-none');
        });

        <?php if (!empty($targetMateriInfo)): ?>
            startNewChat(
                '<?= esc($targetMateriInfo['kode_materi']) ?>',
                '<?= esc(addslashes($targetMateriInfo['nama_materi'])) ?>',
                '<?= esc(addslashes($targetMateriInfo['email_guru'] ?? '')) ?>',
                '<?= esc(addslashes($targetMateriInfo['nama_guru'] ?? '')) ?>',
                '<?= esc(addslashes($targetMateriInfo['nama_mapel'] ?? '')) ?>',
                '<?= esc(addslashes($targetMateriInfo['nama_kelas'] ?? '')) ?>',
                '<?= esc($targetMateriInfo['video_url'] ?? '') ?>'
            );
        <?php endif; ?>
    });

    function startNewChat(kodeMateri, namaMateri, emailGuru, namaGuru, mapel = '', kelas = '', videoUrl = '') {
        $('#modal_materi').modal('hide');
        currentEmailGuru = emailGuru || '';
        currentNamaGuru = namaGuru || '';
        currentMateri = '';
        loadChat(kodeMateri, namaMateri, mapel, kelas, videoUrl);
    }

    function loadChat(materi, namaMateri, mapel = '', kelas = '', videoUrl = '') {
        if (currentMateri === materi && !$('#chat_area').hasClass('d-none')) return;

        currentMateri = materi;
        lastDisplayedId = 0;
        activeParticipants = [];
        editingMessageId = null;

        $('#btn_cancel_edit').addClass('d-none');
        $('#msg_input').val('').attr('placeholder', 'Tuliskan balasan Anda... (Gunakan @ untuk mention peserta)');

        if (currentNamaGuru && !activeParticipants.includes(currentNamaGuru)) {
            activeParticipants.push(currentNamaGuru);
        }

        $('#empty_state').removeClass('d-flex').addClass('d-none');
        $('#chat_area').removeClass('d-none').addClass('d-flex');

        $('#active_title').text(namaMateri);
        const parts = [];
        if (mapel) parts.push(mapel);
        if (kelas) parts.push(kelas);
        if (currentNamaGuru) parts.push('Pengajar: ' + currentNamaGuru);
        $('#active_subtitle').text(parts.length ? parts.join(' • ') : 'Diskusi Materi');
        $('#sender_identity_badge').text(currentNamaGuru ? 'Membalas sebagai: ' + currentNamaGuru : '');

        if (videoUrl) {
            $('#btnWatchVideo').attr('href', videoUrl).removeClass('d-none');
        } else {
            $('#btnWatchVideo').addClass('d-none');
        }

        $('#chat_history').html('<div class="text-center py-20"><span class="spinner-border text-primary"></span></div>');

        fetchMessages(materi);

        if (chatInterval) clearInterval(chatInterval);
        chatInterval = setInterval(() => {
            if (currentMateri) fetchMessages(currentMateri);
        }, 3500);
    }

    function fetchMessages(materi) {
        $.get(`<?= base_url('sw-admin/diskusi/get-messages') ?>/${encodeURIComponent(materi)}`, {
                last_id: lastDisplayedId
            },
            function(response) {
                if (response.token) chatCsrfHash = response.token;

                if (response.guru_email && !currentEmailGuru) {
                    currentEmailGuru = response.guru_email;
                }
                if (response.guru_nama && !currentNamaGuru) {
                    currentNamaGuru = response.guru_nama;
                    $('#sender_identity_badge').text('Membalas sebagai: ' + currentNamaGuru);
                }
                if (response.video_url) {
                    $('#btnWatchVideo').attr('href', response.video_url).removeClass('d-none');
                }
                if (response.nama_mapel || response.nama_kelas || response.guru_nama) {
                    const parts = [];
                    if (response.nama_mapel) parts.push(response.nama_mapel);
                    if (response.nama_kelas) parts.push(response.nama_kelas);
                    if (response.guru_nama) parts.push('Pengajar: ' + response.guru_nama);
                    $('#active_subtitle').text(parts.join(' • '));
                }

                if (lastDisplayedId === 0) {
                    $('#chat_history').html('');
                }

                if (response.participants && Array.isArray(response.participants)) {
                    response.participants.forEach(p => {
                        if (p && !activeParticipants.includes(p)) {
                            activeParticipants.push(p);
                        }
                    });
                }

                const data = response.messages;
                if (data && data.length > 0) {
                    let html = '';
                    let hasNewMessage = false;

                    data.forEach(m => {
                        const msgId = parseInt(m.id_chat_materi);
                        const senderName = m.nama && m.nama.trim() !== '' ? m.nama : 'Peserta';

                        if (m.nama && !activeParticipants.includes(m.nama)) {
                            activeParticipants.push(m.nama);
                        }

                        if (msgId > lastDisplayedId) {
                            lastDisplayedId = msgId;
                            hasNewMessage = true;

                            const isMe = (m.email && currentEmailGuru && m.email.toLowerCase() === currentEmailGuru.toLowerCase());
                            const time = new Date(m.date_created * 1000).toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit'
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
                                        <div class="mt-1 me-12">
                                            <span id="btn-edit-${msgId}" class="badge badge-light-primary badge-sm cursor-pointer text-hover-primary me-2" onclick="editMessage('${msgId}', '${encodeURIComponent(m.text)}')">
                                                <i class="ki-duotone ki-pencil fs-8 me-1"><span class="path1"></span><span class="path2"></span></i>Edit
                                            </span>
                                            <span class="badge badge-light-danger badge-sm cursor-pointer text-hover-danger" onclick="deleteMessage('${msgId}', ${m.date_created})">
                                                <i class="ki-duotone ki-trash fs-8 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>Hapus
                                            </span>
                                        </div>
                                    `;
                                }

                                html += `
                                <div class="d-flex justify-content-end mb-6" id="chat-item-${msgId}">
                                    <div class="d-flex flex-column align-items-end" style="max-width: 82%;">
                                        <div class="d-flex align-items-center mb-1">
                                            <div class="me-3">
                                                <span class="text-muted fs-8 me-2">${time}</span>
                                                <span class="badge badge-light-primary fs-9 me-1">Pengajar</span>
                                                <span class="fs-7 fw-bold text-gray-900">${senderName}</span>
                                            </div>
                                            <div class="symbol symbol-35px symbol-circle">
                                                <img alt="Pic" src="${avatarUrl}" onerror="this.onerror=null;this.src='${defaultAvatar}'">
                                            </div>
                                        </div>
                                        <div class="p-4 rounded-3 bg-light-primary border border-primary border-opacity-25 text-gray-900 fw-semibold text-start chat-message-text" id="msg-text-${msgId}">
                                            ${formatMessage(m.text)}
                                        </div>
                                        ${actionButtons}
                                    </div>
                                </div>`;
                            } else {
                                html += `
                                <div class="d-flex justify-content-start mb-6" id="chat-item-${msgId}">
                                    <div class="d-flex flex-column align-items-start" style="max-width: 82%;">
                                        <div class="d-flex align-items-center mb-1">
                                            <div class="symbol symbol-35px symbol-circle">
                                                <img alt="Pic" src="${avatarUrl}" onerror="this.onerror=null;this.src='${defaultAvatar}'">
                                            </div>
                                            <div class="ms-3">
                                                <span class="fs-7 fw-bold text-gray-900 me-1">${senderName}</span>
                                                <span class="badge badge-light fs-9 me-2">Peserta</span>
                                                <span class="text-muted fs-8">${time}</span>
                                            </div>
                                        </div>
                                        <div class="p-4 rounded-3 bg-white border border-gray-200 shadow-xs text-gray-900 fw-semibold text-start chat-message-text">
                                            ${formatMessage(m.text)}
                                        </div>
                                        <div class="mt-1 ms-2">
                                            <span class="badge badge-light badge-sm cursor-pointer text-hover-primary" onclick="replyTo('${senderName.replace(/'/g, "\\'")}')">
                                                <i class="ki-duotone ki-left fs-8 me-1"><span class="path1"></span><span class="path2"></span></i>Balas
                                            </span>
                                        </div>
                                    </div>
                                </div>`;
                            }
                        }
                    });

                    if (hasNewMessage) {
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
                            <div class="fs-8">Mulai kirim pesan di bawah untuk membuka diskusi pada materi ini.</div>
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
        text = text.replace(urlRegex, url => `<a href="${url}" target="_blank" class="fw-bold text-primary text-hover-dark text-decoration-underline">${url}</a>`);
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

            $.post(`<?= base_url('sw-admin/diskusi/update-message') ?>`, postData, function(res) {
                if (res && res.token) chatCsrfHash = res.token;

                $('#msg-text-' + editingMessageId).html(formatMessage(text));
                $('#btn-edit-' + editingMessageId).attr('onclick', `editMessage('${editingMessageId}', '${encodeURIComponent(text)}')`);

                editingMessageId = null;
                $('#btn_cancel_edit').addClass('d-none').prop('disabled', false);
                $('#btn_send').prop('disabled', false);
                $('#msg_input').attr('placeholder', 'Tuliskan balasan Anda... (Gunakan @ untuk mention peserta)');
                $('#msg_input').prop('disabled', false).val('').focus();
            }).fail(function(xhr) {
                $('#msg_input').prop('disabled', false);
                $('#btn_cancel_edit').prop('disabled', false);
                $('#btn_send').prop('disabled', false);
                let errMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Gagal mengedit pesan.";
                Swals.alert("Gagal", errMsg, "error");
            });

        } else {
            let postData = {
                materi: currentMateri,
                text: text,
                email_guru: currentEmailGuru,
                nama_guru: currentNamaGuru
            };
            postData[chatCsrfName] = chatCsrfHash;

            $.post(`<?= base_url('sw-admin/diskusi/send') ?>`, postData, function(res) {
                if (res && res.token) chatCsrfHash = res.token;
                $('#msg_input').prop('disabled', false).val('').focus();
                $('#btn_send').prop('disabled', false);
                fetchMessages(currentMateri);
            }).fail(function() {
                $('#msg_input').prop('disabled', false);
                $('#btn_send').prop('disabled', false);
                Swals.alert("Gagal", "Gagal mengirim pesan, silakan coba lagi.", "error");
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
        const timeDiff = currentTime - timeStamp;

        if (timeDiff > 300) {
            Swals.alert('Ditolak!', 'Pesan yang dikirim lebih dari 5 menit yang lalu tidak dapat dihapus.', 'warning');
            return;
        }

        Swals.confirm(
            'Hapus Pesan?',
            'Pesan ini akan dihapus secara permanen dari diskusi.',
            function() {
                Swals.loading('Menghapus...', 'Memproses penghapusan pesan');

                let postData = { id_chat: id };
                postData[chatCsrfName] = chatCsrfHash;

                $.post(`<?= base_url('sw-admin/diskusi/delete-message') ?>`, postData, function(res) {
                    if (res && res.token) chatCsrfHash = res.token;
                    Swals.close();

                    $('#chat-item-' + id).fadeOut(250, function() {
                        $(this).remove();
                    });
                }).fail(function(xhr) {
                    Swals.close();
                    let errMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Gagal menghapus pesan.";
                    Swals.alert("Gagal", errMsg, "error");
                });
            }
        );
    }
</script>
<?= $this->endSection(); ?>

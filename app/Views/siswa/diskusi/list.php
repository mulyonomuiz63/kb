<?= $this->extend('siswa/template/app'); ?>

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
        width: 360px;
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
        border-left: 4px solid #0086a7;
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
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
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

    .bubble-them.bubble-guru {
        background: #f0fdf4;
        border-color: #86efac;
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
        color: #0086a7;
    }

    @media (max-width: 991.98px) {
        .diskusi-wrapper {
            height: calc(100vh - 175px);
        }
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
<div class="container-xxl mt-4 mb-10">
    <div class="diskusi-wrapper" id="diskusiWrapper">
        <!-- SIDEBAR DAFTAR DISKUSI MATERI -->
        <div class="diskusi-sidebar">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white">
                <div>
                    <h4 class="fw-bolder text-gray-900 m-0 d-flex align-items-center">
                        <i class="ki-outline ki-messages fs-2 text-primary me-2"></i> Diskusi Materi
                    </h4>
                    <span class="text-muted fs-8">Tanya jawab modul & video pembelajaran</span>
                </div>
                <button class="btn btn-sm btn-primary fw-bold rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modal_materi" title="Mulai Diskusi Materi Baru">
                    <i class="ki-outline ki-plus fs-4 me-1"></i> Materi
                </button>
            </div>

            <div class="p-3 border-bottom bg-light-subtle">
                <div class="position-relative">
                    <i class="ki-outline ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle-y ms-3"></i>
                    <input type="text" class="form-control form-control-sm form-control-solid ps-10" placeholder="Cari judul materi atau mapel..." id="searchChat">
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
                             data-guru="<?= esc($d['nama_guru'] ?? '') ?>"
                             data-videourl="<?= esc($d['video_url'] ?? '') ?>">
                            <div class="symbol symbol-45px symbol-circle me-3 flex-shrink-0 mt-1">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($d['nama_materi']) ?>&background=0086a7&color=fff&bold=true" alt="<?= esc($d['nama_materi']) ?>">
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="fw-bold fs-6 text-gray-900 text-truncate pe-2 chat-sidebar-name"><?= esc($d['nama_materi']) ?></div>
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
                                        <span class="badge badge-circle badge-primary unread-badge flex-shrink-0" data-materi="<?= esc($d['materi']) ?>">
                                            <?= ($d['unread_count'] > 99) ? '99+' : $d['unread_count'] ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-12 px-4" id="emptySidebarPlaceholder">
                        <i class="ki-outline ki-messages fs-3x text-gray-300 mb-3"></i>
                        <p class="text-gray-600 fw-semibold fs-7 mb-2">Belum ada riwayat diskusi</p>
                        <button class="btn btn-sm btn-light-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modal_materi">
                            <i class="ki-outline ki-plus fs-5 me-1"></i> Pilih Materi untuk Diskusi
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- AREA UTAMA RUANG DISKUSI -->
        <div class="diskusi-main">
            <div id="empty_state" class="d-flex flex-column align-items-center justify-content-center h-100 p-6 text-center">
                <div class="symbol symbol-75px symbol-circle bg-light-primary mb-4">
                    <i class="ki-outline ki-messages fs-2tx text-primary"></i>
                </div>
                <h4 class="fw-bold text-gray-800 mb-1">Ruang Diskusi Materi Brevet</h4>
                <p class="text-muted fs-7 mw-350px mb-4">Pilih salah satu topik materi di sebelah kiri atau klik tombol <b>+ Materi</b> untuk bertanya langsung kepada Pengajar.</p>
                <button class="btn btn-sm btn-primary fw-bold rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modal_materi">
                    <i class="ki-outline ki-plus fs-4 me-1"></i> Mulai Diskusi Materi
                </button>
            </div>

            <div id="chat_area" style="display:none;" class="h-100 flex-column position-relative">
                <!-- HEADER RUANG DISKUSI -->
                <div class="px-4 py-3 border-bottom bg-white d-flex align-items-center justify-content-between shadow-xs">
                    <div class="d-flex align-items-center overflow-hidden pe-2">
                        <button type="button" class="btn btn-icon btn-sm btn-light me-2 d-lg-none flex-shrink-0" id="btnBackMobile" title="Kembali ke Daftar">
                            <i class="ki-outline ki-arrow-left fs-2"></i>
                        </button>
                        <div class="overflow-hidden">
                            <div class="fw-bolder fs-5 text-gray-900 text-truncate" id="active_title">Nama Materi</div>
                            <div class="text-muted fs-8 text-truncate" id="active_subtitle">Diskusi Materi Brevet</div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="#" id="btnWatchVideo" class="btn btn-sm btn-light-primary fw-bold rounded-pill px-3 d-none">
                            <i class="ki-outline ki-to-right fs-4 me-1"></i> Tonton Video
                        </a>
                    </div>
                </div>

                <!-- RIWAYAT PESAN -->
                <div class="chat-history" id="chat_history"></div>

                <!-- FOOTER INPUT PESAN -->
                <div class="p-3 p-md-4 border-top bg-white position-relative">
                    <div id="mention_dropdown" class="mention-dropdown shadow-lg"></div>

                    <div class="d-flex gap-2 align-items-end">
                        <textarea id="msg_input" class="form-control form-control-solid border border-gray-300 fs-7" rows="2" placeholder="Tulis pertanyaan atau tanggapan... (Gunakan @ untuk mention)" style="resize: none;"></textarea>
                        <button class="btn btn-light-danger btn-sm fw-bold d-none px-3 py-3" id="btn_cancel_edit" title="Batal Edit">Batal</button>
                        <button class="btn btn-primary fw-bold px-4 py-3 flex-shrink-0" id="btn_send">
                            <i class="ki-outline ki-send fs-4 me-1"></i> Kirim
                        </button>
                    </div>
                    <div class="text-muted fs-9 mt-1">Tekan <b>Enter</b> untuk kirim, <b>Shift + Enter</b> untuk baris baru</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PILIH MATERI -->
<div class="modal fade" id="modal_materi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-500px">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-2">
                <div>
                    <h3 class="fw-bolder text-gray-900 mb-0">Pilih Materi Diskusi</h3>
                    <span class="text-muted fs-8">Pilih modul video untuk mulai bertanya atau berdiskusi</span>
                </div>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>

            <div class="modal-body pt-3">
                <div class="position-relative mb-4">
                    <i class="ki-outline ki-magnifier fs-2 text-gray-500 position-absolute top-50 translate-middle-y ms-4"></i>
                    <input type="text" id="search_materi" class="form-control form-control-solid ps-12" placeholder="Cari nama materi, mapel, atau kelas..." />
                </div>

                <div class="scroll-y mh-350px pe-2" id="list_materi">
                    <?php if (!empty($materi)): ?>
                        <?php foreach ($materi as $m): ?>
                            <div class="materi-item card card-bordered border-gray-200 p-3 mb-2 cursor-pointer bg-hover-light-primary"
                                onclick="startNewChat('<?= esc($m['kode_materi']) ?>', '<?= esc(addslashes($m['nama_materi'])) ?>', '<?= esc(addslashes($m['nama_mapel'] ?? '')) ?>', '<?= esc(addslashes($m['nama_kelas'] ?? '')) ?>', '<?= esc(addslashes($m['nama_guru'] ?? '')) ?>', '<?= esc($m['video_url'] ?? '') ?>')"
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
                                            <?= esc($m['nama_mapel'] ?? 'Materi Brevet') ?>
                                            <?= !empty($m['nama_kelas']) ? ' • ' . esc($m['nama_kelas']) : '' ?>
                                            <?= !empty($m['nama_guru']) ? ' • ' . esc($m['nama_guru']) : '' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-8 text-muted fs-7">Belum ada materi aktif pada kelas Anda.</div>
                    <?php endif; ?>

                    <div id="no_results" class="text-center py-6 d-none">
                        <i class="ki-outline ki-search-list fs-3x text-gray-400 mb-2"></i>
                        <div class="text-muted fs-7">Materi tidak ditemukan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts') ?>
<script>
    let currentMateri = '';
    let lastDisplayedId = 0;
    let chatInterval = null;
    let editingMessageId = null;

    let chatCsrfName = '<?= csrf_token() ?>';
    let chatCsrfHash = '<?= csrf_hash() ?>';

    let activeParticipants = [];
    let currentGuruEmail = '';

    $(document).ready(function() {
        $.ajaxSetup({
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', chatCsrfHash);
            }
        });

        // Klik item di sidebar
        $(document).on('click', '.chat-item', function() {
            const materi = $(this).data('materi');
            const namaMateri = $(this).data('namamateri');
            const mapel = $(this).data('mapel') || '';
            const kelas = $(this).data('kelas') || '';
            const guru = $(this).data('guru') || '';
            const videoUrl = $(this).data('videourl') || '';
            loadChat(materi, namaMateri, mapel, kelas, guru, videoUrl);
        });

        // Tombol Kembali di Mobile
        $('#btnBackMobile').on('click', function() {
            $('#diskusiWrapper').removeClass('mobile-chat-open');
        });

        // Pencarian diskusi di sidebar kiri
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

        // Enter untuk kirim, Shift+Enter untuk baris baru
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
            $('#msg_input').attr('placeholder', 'Tulis pertanyaan atau tanggapan... (Gunakan @ untuk mention)');
            $(this).addClass('d-none');
        });

        // Pencarian di modal pilih materi
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

        $('#modal_materi').on('hidden.bs.modal', function() {
            $('#search_materi').val('');
            $('.materi-item').removeClass('d-none');
            $('#no_results').addClass('d-none');
        });

        // Auto-open jika ada ?materi=KODE_MATERI dari notifikasi atau link langsung
        <?php if (!empty($targetMateriInfo)): ?>
            startNewChat(
                '<?= esc($targetMateriInfo['kode_materi']) ?>',
                '<?= esc(addslashes($targetMateriInfo['nama_materi'])) ?>',
                '<?= esc(addslashes($targetMateriInfo['nama_mapel'] ?? '')) ?>',
                '<?= esc(addslashes($targetMateriInfo['nama_kelas'] ?? '')) ?>',
                '<?= esc(addslashes($targetMateriInfo['nama_guru'] ?? '')) ?>',
                '<?= esc($targetMateriInfo['video_url'] ?? '') ?>'
            );
        <?php endif; ?>
    });

    function startNewChat(kodeMateri, namaMateri, mapel = '', kelas = '', guru = '', videoUrl = '') {
        $('#modal_materi').modal('hide');

        // Jika belum ada di sidebar, tambahkan di paling atas
        if ($(`.chat-item[data-materi="${kodeMateri}"]`).length === 0) {
            $('#emptySidebarPlaceholder').remove();
            const itemHtml = `
                <div class="chat-item d-flex align-items-start active"
                     data-materi="${kodeMateri}"
                     data-namamateri="${namaMateri}"
                     data-mapel="${mapel}"
                     data-kelas="${kelas}"
                     data-guru="${guru}"
                     data-videourl="${videoUrl}">
                    <div class="symbol symbol-45px symbol-circle me-3 flex-shrink-0 mt-1">
                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(namaMateri)}&background=0086a7&color=fff&bold=true" alt="">
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="fw-bold fs-6 text-gray-900 text-truncate pe-2 chat-sidebar-name">${namaMateri}</div>
                        </div>
                        ${(mapel || kelas) ? `<div class="mb-1"><span class="badge badge-light-primary fs-9 px-2 py-0">${mapel}${kelas ? ' • ' + kelas : ''}</span></div>` : ''}
                        <div class="text-gray-500 fs-8 text-truncate">Diskusi aktif</div>
                    </div>
                </div>`;
            $('#sidebarChatList').prepend(itemHtml);
        }

        currentMateri = ''; // Paksa load ulang
        loadChat(kodeMateri, namaMateri, mapel, kelas, guru, videoUrl);
    }

    function updateHeaderMeta(namaMateri, mapel, kelas, guru, videoUrl) {
        $('#active_title').text(namaMateri);
        const parts = [];
        if (mapel) parts.push(mapel);
        if (kelas) parts.push(kelas);
        if (guru) parts.push('Pengajar: ' + guru);
        $('#active_subtitle').text(parts.length ? parts.join(' • ') : 'Diskusi Materi Brevet');

        if (videoUrl) {
            $('#btnWatchVideo').attr('href', videoUrl).removeClass('d-none');
        } else {
            $('#btnWatchVideo').addClass('d-none');
        }
    }

    function loadChat(materi, namaMateri, mapel = '', kelas = '', guru = '', videoUrl = '') {
        $('#diskusiWrapper').addClass('mobile-chat-open');

        if (currentMateri === materi && $('#chat_area').is(':visible')) return;

        currentMateri = materi;
        lastDisplayedId = 0;
        editingMessageId = null;
        activeParticipants = [];
        if (guru) activeParticipants.push(guru);

        $('#btn_cancel_edit').addClass('d-none');
        $('#msg_input').val('').attr('placeholder', 'Tulis pertanyaan atau tanggapan... (Gunakan @ untuk mention)');

        $('.chat-item').removeClass('active');
        const activeItem = $(`.chat-item[data-materi="${materi}"]`);
        activeItem.addClass('active');
        activeItem.find('.unread-badge').remove();

        $('#empty_state').attr('style', 'display: none !important;');
        $('#chat_area').attr('style', 'display: flex !important;');
        updateHeaderMeta(namaMateri, mapel, kelas, guru, videoUrl);

        $('#chat_history').html('<div class="text-center py-15"><span class="spinner-border text-primary"></span></div>');

        fetchMessages(materi);

        if (chatInterval) clearInterval(chatInterval);
        chatInterval = setInterval(function() {
            if (currentMateri) fetchMessages(currentMateri);
        }, 3500);
    }

    function fetchMessages(materi) {
        $.ajax({
            url: `<?= base_url('sw-siswa/diskusi/get-messages') ?>/${encodeURIComponent(materi)}`,
            type: 'GET',
            data: {
                last_id: lastDisplayedId
            },
            success: function(response) {
                if (response.token) {
                    chatCsrfHash = response.token;
                    $('meta[name="csrf-token"]').attr('content', response.token);
                }

                if (response.guru_email) {
                    currentGuruEmail = response.guru_email.toLowerCase();
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

                const data = response.messages;
                const unreadId = response.first_unread_id;

                if (response.participants && Array.isArray(response.participants)) {
                    response.participants.forEach(p => {
                        if (p && !activeParticipants.includes(p)) activeParticipants.push(p);
                    });
                }

                if (data && data.length > 0) {
                    let html = '';
                    const myEmail = ('<?= session()->get('email') ?>').toLowerCase();

                    data.forEach(m => {
                        const msgIdRaw = parseInt(m.id_chat_materi);
                        const senderName = m.nama && m.nama.trim() !== '' ? m.nama : 'Peserta';

                        if (m.nama && !activeParticipants.includes(m.nama)) {
                            activeParticipants.push(m.nama);
                        }

                        if (msgIdRaw > lastDisplayedId) {
                            lastDisplayedId = msgIdRaw;

                            const msgEmail = (m.email || '').toLowerCase();
                            const isMe = (msgEmail && myEmail && msgEmail === myEmail);
                            const isGuru = (msgEmail && currentGuruEmail && msgEmail === currentGuruEmail);
                            const processedText = formatMessage(m.text);
                            const msgId = `msg-${m.id_chat_materi}`;

                            const time = new Date(m.date_created * 1000).toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false
                            });

                            const defaultAvatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(senderName)}&background=${isGuru ? '16a34a' : '0086a7'}&color=fff&bold=true`;
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
                                            <span id="btn-edit-${msgIdRaw}" class="badge badge-light-primary badge-sm cursor-pointer text-hover-primary me-1" onclick="editMessage('${msgIdRaw}', '${encodeURIComponent(m.text)}')">
                                                <i class="ki-outline ki-pencil fs-9 me-1"></i>Edit
                                            </span>
                                            <span id="btn-del-${msgIdRaw}" class="badge badge-light-danger badge-sm cursor-pointer text-hover-danger" onclick="deleteMessage('${msgIdRaw}', ${m.date_created})">
                                                <i class="ki-outline ki-trash fs-9 me-1"></i>Hapus
                                            </span>
                                        </div>`;
                                }

                                html += `
                                <div id="${msgId}" class="d-flex justify-content-end mb-4">
                                    <div class="d-flex flex-column align-items-end" style="max-width: 82%;">
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="text-muted fs-9 me-2">${time}</span>
                                            <span class="fs-8 fw-bold text-gray-900 me-2">Anda</span>
                                            <div class="symbol symbol-30px symbol-circle">
                                                <img src="${avatarUrl}" onerror="this.onerror=null;this.src='${defaultAvatar}'">
                                            </div>
                                        </div>
                                        <div class="bubble-me">
                                            <div class="chat-message-text" id="msg-text-${msgIdRaw}">${processedText}</div>
                                        </div>
                                        ${actionButtons}
                                    </div>
                                </div>`;
                            } else {
                                const roleBadge = isGuru
                                    ? `<span class="badge badge-success fs-9 px-2 py-1 ms-1"><i class="ki-outline ki-verify text-white fs-9 me-1"></i>Pengajar</span>`
                                    : '';

                                html += `
                                <div id="${msgId}" class="d-flex justify-content-start mb-4">
                                    <div class="d-flex flex-column align-items-start" style="max-width: 82%;">
                                        <div class="d-flex align-items-center mb-1 flex-wrap">
                                            <div class="symbol symbol-30px symbol-circle me-2">
                                                <img src="${avatarUrl}" onerror="this.onerror=null;this.src='${defaultAvatar}'">
                                            </div>
                                            <span class="fs-8 fw-bold text-gray-900">${senderName}</span>
                                            ${roleBadge}
                                            <span class="text-muted fs-9 ms-2">${time}</span>
                                        </div>
                                        <div class="bubble-them ${isGuru ? 'bubble-guru' : ''}">
                                            <div class="chat-message-text">${processedText}</div>
                                        </div>
                                        <div class="mt-1 ms-1">
                                            <span class="badge badge-light badge-sm cursor-pointer text-hover-primary" onclick="replyTo('${senderName.replace(/'/g, "\\'")}')">
                                                <i class="ki-outline ki-left fs-9 me-1"></i>Balas
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

                        if (unreadId) {
                            const targetElement = document.getElementById(`msg-${unreadId}`);
                            if (targetElement) {
                                targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            }
                        } else {
                            $('#chat_history').animate({
                                scrollTop: $('#chat_history')[0].scrollHeight
                            }, 250);
                        }
                    }
                } else if (lastDisplayedId === 0) {
                    $('#chat_history').html(`
                        <div class="empty-chat-placeholder text-center py-15 text-muted">
                            <i class="ki-outline ki-messages fs-3x text-gray-300 mb-3"></i>
                            <div class="fw-bold text-gray-700 fs-6 mb-1">Belum ada diskusi pada materi ini</div>
                            <div class="fs-8">Tulis pertanyaan Anda di bawah untuk memulai diskusi bersama Pengajar.</div>
                        </div>
                    `);
                }
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
        text = text.replace(urlRegex, function(url) {
            return '<a href="' + url + '" target="_blank" rel="noopener noreferrer" class="fw-bold text-primary text-decoration-underline">' + url + '</a>';
        });
        var mentionRegex = /@\[(.*?)\]/g;
        text = text.replace(mentionRegex, `<span class="badge badge-light-primary text-primary fw-bold px-2 py-1 me-1"><i class="ki-outline ki-profile-circle text-primary me-1"></i>@$1</span>`);
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

            $.post(`<?= base_url('sw-siswa/diskusi/update-chat') ?>`, postData, function(res) {
                if (res && res.token) chatCsrfHash = res.token;

                $('#msg-text-' + editingMessageId).html(formatMessage(text));
                $('#btn-edit-' + editingMessageId).attr('onclick', `editMessage('${editingMessageId}', '${encodeURIComponent(text)}')`);

                editingMessageId = null;
                $('#btn_cancel_edit').addClass('d-none').prop('disabled', false);
                $('#btn_send').prop('disabled', false);
                $('#msg_input').attr('placeholder', 'Tulis pertanyaan atau tanggapan... (Gunakan @ untuk mention)');
                $('#msg_input').prop('disabled', false).val('').focus();
            }).fail(function(xhr) {
                $('#msg_input').prop('disabled', false);
                $('#btn_cancel_edit').prop('disabled', false);
                $('#btn_send').prop('disabled', false);
                let errMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Gagal mengedit pesan.";
                Swal.fire('Gagal', errMsg, 'error');
            });
        } else {
            let postData = {
                materi: currentMateri,
                text: text
            };
            postData[chatCsrfName] = chatCsrfHash;

            $.post(`<?= base_url('sw-siswa/diskusi/send') ?>`, postData, function(res) {
                if (res && res.token) chatCsrfHash = res.token;
                $('#msg_input').prop('disabled', false).val('').focus();
                $('#btn_send').prop('disabled', false);
                fetchMessages(currentMateri);
            }).fail(function(xhr) {
                $('#msg_input').prop('disabled', false);
                $('#btn_send').prop('disabled', false);
                let errMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Gagal mengirim pesan.";
                Swal.fire('Gagal', errMsg, 'error');
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
                let postData = { id_chat: id };
                postData[chatCsrfName] = chatCsrfHash;

                $.post(`<?= base_url('sw-siswa/diskusi/delete-chat') ?>`, postData, function(res) {
                    if (res && res.token) chatCsrfHash = res.token;
                    $(`#msg-${id}`).fadeOut(250, function() {
                        $(this).remove();
                    });
                    if (editingMessageId == id) {
                        editingMessageId = null;
                        $('#msg_input').val('').prop('disabled', false);
                        $('#btn_cancel_edit').addClass('d-none');
                    }
                }).fail(function(xhr) {
                    let errMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Gagal menghapus pesan.";
                    Swal.fire('Gagal', errMsg, 'error');
                });
            }
        });
    }
</script>
<?= $this->endSection(); ?>

<?= $this->extend($currentUser['role'] === 'siswa' ? 'siswa/template/app' : 'template/app'); ?>

<?= $this->section('styles'); ?>
<style>
    /* Full-screen lock agar halaman chat tidak scroll di laptop/tablet/desktop */
    html, body {
        height: 100vh !important;
        overflow: hidden !important;
    }
    #kt_app_toolbar, #kt_toolbar,
    #kt_app_footer, .footer {
        display: none !important;
    }
    #kt_app_wrapper, .app-wrapper,
    #kt_app_main, .app-main,
    #kt_app_content, #kt_content, #kt_content_container, .app-content {
        margin-top: 0 !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    .kb-chat-app {
        position: fixed !important;
        top: 78px;
        bottom: 12px;
        left: 16px;
        right: 16px;
        max-width: 1400px;
        margin: 0 auto;
        height: auto !important;
        min-height: 0 !important;
        background: #ffffff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.08);
        z-index: 95;
    }
    .kb-chat-sidebar {
        width: 350px;
        min-width: 300px;
        border-right: 1px solid #f1f1f4;
        background: #fafbfe;
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 0;
        overflow: hidden;
    }
    .kb-chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #fdfdfd;
        position: relative;
        height: 100%;
        min-width: 0;
        min-height: 0;
        overflow: hidden;
    }
    @media (max-width: 991.98px) {
        .kb-chat-app {
            left: 10px;
            right: 10px;
            bottom: 73px;
        }
    }
    @media (max-width: 767.98px) {
        .kb-chat-app {
            left: 8px;
            right: 8px;
            bottom: 73px;
        }
        .kb-chat-sidebar {
            width: 100%;
            min-width: 100%;
        }
        .kb-chat-app.has-active-room .kb-chat-sidebar {
            display: none !important;
        }
        .kb-chat-app:not(.has-active-room) .kb-chat-main {
            display: none !important;
        }
    }
    .kb-room-item {
        cursor: pointer;
        transition: all 0.2s ease;
        border-radius: 10px;
        padding: 10px 12px;
        margin-bottom: 4px;
    }
    .kb-room-item:hover {
        background: #edf3ff;
    }
    .kb-room-item.active {
        background: #e1ecff;
        border-left: 3px solid #29459A;
    }
    .kb-messages-scroll {
        flex: 1;
        min-height: 0;
        overflow-y: auto;
        padding: 18px 22px;
        background: #efeae2;
        background-image: radial-gradient(rgba(41, 69, 154, 0.05) 1px, transparent 0);
        background-size: 20px 20px;
        scroll-behavior: smooth;
    }
    .kb-msg-row {
        position: relative;
        transition: background 0.15s ease;
        padding: 2px 4px;
        border-radius: 10px;
    }
    .kb-msg-row.is-selected {
        background: rgba(41, 69, 154, 0.08);
    }
    .kb-bubble {
        max-width: 68%;
        padding: 9px 14px;
        border-radius: 14px;
        position: relative;
        font-size: 0.92rem;
        line-height: 1.45;
        word-break: break-word;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        cursor: pointer;
        transition: transform 0.1s ease;
    }
    .kb-bubble-me {
        background: #29459A;
        color: #ffffff;
        border-bottom-right-radius: 3px;
    }
    .kb-bubble-other {
        background: #ffffff;
        color: #1e293b;
        border-bottom-left-radius: 3px;
        border: 1px solid #eef0f5;
    }
    .kb-bubble-deleted {
        background: #f1f5f9 !important;
        color: #64748b !important;
        border: 1px dashed #cbd5e1 !important;
        font-style: italic;
        cursor: default;
    }
    .kb-msg-text {
        white-space: pre-wrap;
    }
    /* Chevron ala WhatsApp di pojok kanan atas bubble */
    .kb-wa-chevron {
        position: absolute;
        top: 4px;
        right: 6px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        opacity: 0;
        transition: opacity 0.15s ease, background 0.15s ease;
        z-index: 2;
    }
    .kb-bubble-me .kb-wa-chevron {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
    }
    .kb-bubble-other .kb-wa-chevron {
        background: rgba(0, 0, 0, 0.06);
        color: #475569;
    }
    .kb-msg-row:hover .kb-wa-chevron,
    .kb-msg-row.is-selected .kb-wa-chevron {
        opacity: 1;
    }
    /* Action Bar melayang ala WhatsApp saat hover atau klik pesan */
    .kb-msg-actions-bar {
        display: flex;
        align-items: center;
        gap: 3px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 50rem;
        padding: 3px 6px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.1);
        opacity: 0;
        pointer-events: none;
        transform: translateY(3px) scale(0.96);
        transition: all 0.16s ease;
        z-index: 10;
        flex-shrink: 0;
    }
    .kb-msg-row:hover .kb-msg-actions-bar,
    .kb-msg-row.is-selected .kb-msg-actions-bar {
        opacity: 1;
        pointer-events: auto;
        transform: translateY(0) scale(1);
    }
    .kb-act-btn {
        border: none;
        background: transparent;
        color: #334155;
        font-size: 0.74rem;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 50rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
        transition: background 0.15s ease, color 0.15s ease;
        white-space: nowrap;
    }
    .kb-act-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
    .kb-act-btn.kb-act-danger:hover {
        background: #fef2f2;
        color: #dc2626;
    }
    .kb-react-emoji-btn {
        border: none;
        background: transparent;
        font-size: 1rem;
        line-height: 1;
        padding: 3px 5px;
        border-radius: 50%;
        cursor: pointer;
        transition: transform 0.15s ease, background 0.15s ease;
    }
    .kb-react-emoji-btn:hover {
        transform: scale(1.28);
        background: #f1f5f9;
    }
    .kb-react-emoji-btn.active {
        background: #e0e7ff;
        box-shadow: 0 0 0 1px #6366f1;
    }
    .kb-reactions-pill {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        background: #ffffff;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        border-radius: 50rem;
        padding: 1px 7px;
        font-size: 0.75rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.08);
        margin-top: 4px;
        cursor: pointer;
        user-select: none;
    }
    .kb-reactions-pill .kb-react-count {
        font-size: 0.68rem;
        font-weight: 700;
        color: #475569;
        margin-left: 2px;
    }
    @media (max-width: 576px) {
        .kb-bubble {
            max-width: 82%;
        }
        .kb-act-btn span {
            display: none;
        }
        .kb-act-btn {
            padding: 5px 7px;
        }
    }
    .kb-bubble-time {
        font-size: 0.68rem;
        opacity: 0.78;
        margin-top: 3px;
        display: flex;
        align-items: center;
        gap: 4px;
        justify-content: flex-end;
    }
    .kb-bubble-me .kb-bubble-time {
        color: rgba(255,255,255,0.88);
    }
    .kb-reply-preview-box {
        border-left: 3px solid #009EF7;
        background: rgba(0, 158, 247, 0.08);
        padding: 5px 10px;
        border-radius: 6px;
        margin-bottom: 6px;
        font-size: 0.78rem;
        cursor: pointer;
    }
    .kb-bubble-me .kb-reply-preview-box {
        background: rgba(255, 255, 255, 0.18);
        border-left-color: #ffffff;
        color: #ffffff;
    }
    .kb-link-card {
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.08);
        background: #ffffff;
        margin-top: 6px;
        color: #333;
        text-decoration: none;
        display: block;
        transition: transform 0.2s;
    }
    .kb-link-card:hover {
        transform: scale(1.01);
    }
    .kb-link-img {
        width: 100%;
        max-height: 140px;
        object-fit: cover;
    }
    .kb-active-reply-bar {
        background: #eef3ff;
        border-left: 4px solid #29459A;
        padding: 8px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .kb-emoji-panel {
        position: absolute;
        bottom: 70px;
        left: 16px;
        background: #fff;
        border: 1px solid #e1e3ea;
        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        border-radius: 12px;
        padding: 10px;
        width: 310px;
        max-height: 220px;
        overflow-y: auto;
        display: none;
        z-index: 1050;
    }
    .kb-emoji-item {
        font-size: 1.4rem;
        cursor: pointer;
        padding: 4px;
        border-radius: 6px;
        text-align: center;
        transition: background 0.15s;
    }
    .kb-emoji-item:hover {
        background: #f1f3f8;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <div class="app-container container-xxl">

            <!-- Chat Container Box -->
            <div class="kb-chat-app d-flex">

                <!-- SIDEBAR: Rooms & Search -->
                <div class="kb-chat-sidebar">
                    <!-- Sidebar Header -->
                    <div class="p-4 border-bottom bg-white d-flex align-items-center justify-content-between flex-shrink-0">
                        <div class="d-flex align-items-center gap-2">
                            <div class="symbol symbol-35px bg-light-primary rounded-circle d-flex align-items-center justify-content-center">
                                <i class="ki-duotone ki-messages fs-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </div>
                            <div>
                                <h5 class="fw-bolder mb-0 text-gray-900">Diskusi & Chat</h5>
                                <span class="fs-8 text-muted"><?= esc($currentUser['name']) ?> (<?= ucfirst($currentUser['role']) ?>)</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-1">
                            <!-- Tombol Suara Notifikasi Ringtone -->
                            <button type="button" id="btn_toggle_sound" class="btn btn-icon btn-sm btn-light-info rounded-circle" title="Suara Ringtone Pesan Masuk: Aktif (Klik untuk Matikan/Hidupkan)">
                                <i class="fas fa-volume-up fs-5 text-info" id="icon_sound_status"></i>
                            </button>

                            <!-- Tombol Buat Grup -->
                            <button type="button" class="btn btn-icon btn-sm btn-light-primary rounded-circle" data-bs-toggle="modal" data-bs-target="#modal_create_group" title="Buat Grup Baru">
                                <i class="fas fa-users fs-4 text-primary"></i>
                            </button>

                            <!-- Tombol Kontak Baru -->
                            <button type="button" class="btn btn-icon btn-sm btn-light-success rounded-circle" data-bs-toggle="modal" data-bs-target="#modal_new_chat" title="Mulai Chat Baru">
                                <i class="fas fa-plus fs-4 text-success"></i>
                            </button>

                            <?php if ($isAdmin): ?>
                                <!-- Admin Toggle Chat Status -->
                                <button type="button" id="btn_toggle_chat_status" class="btn btn-icon btn-sm <?= $chatStatus ? 'btn-light-danger' : 'btn-light-success' ?> rounded-circle" title="<?= $chatStatus ? 'Nonaktifkan Chat untuk Peserta' : 'Aktifkan Chat untuk Peserta' ?>">
                                    <i class="fas fa-power-off fs-5 <?= $chatStatus ? 'text-danger' : 'text-success' ?>"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($isAdmin): ?>
                        <!-- Admin Filter Mode (Semua vs Room Saya) & Toggle Pengajar -->
                        <div class="px-4 pt-3 pb-2 bg-white border-bottom flex-shrink-0">
                            <div class="btn-group w-100 mb-2" role="group">
                                <button type="button" class="btn btn-xs btn-primary fw-bold" id="btn_mode_my">Percakapan Saya</button>
                                <button type="button" class="btn btn-xs btn-light fw-bold" id="btn_mode_all" title="Monitor seluruh chat peserta & grup">Monitoring Semua</button>
                            </div>
                            <div class="d-flex align-items-center justify-content-between bg-light rounded px-3 py-1">
                                <span class="fs-9 fw-semibold text-gray-700" title="Atur apakah Peserta bisa melihat & chat dengan Pengajar/Guru"><i class="fas fa-chalkboard-teacher me-1 text-primary"></i> Chat Pengajar utk Peserta:</span>
                                <button type="button" id="btn_toggle_guru_status" class="btn btn-xs py-0 px-2 fw-bold <?= !empty($guruChatStatus) ? 'btn-success' : 'btn-secondary' ?>" title="Klik untuk Mengaktifkan / Menonaktifkan Pengajar bagi Peserta (Admin tetap selalu bisa chat Pengajar & PIC)">
                                    <?= !empty($guruChatStatus) ? 'Aktif' : 'Nonaktif' ?>
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Search Input -->
                    <div class="p-3 bg-white border-bottom flex-shrink-0">
                        <div class="position-relative">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute top-50 translate-middle-y ms-3 text-gray-500">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" id="search_room_input" class="form-control form-control-sm form-control-solid ps-10 rounded-pill" placeholder="Cari nama atau grup..." />
                        </div>
                    </div>

                    <!-- Room List -->
                    <div class="flex-grow-1 overflow-auto p-3" id="room_list_container" style="min-height: 0;">
                        <div class="text-center py-10 text-muted">
                            <div class="spinner-border spinner-border-sm text-primary mb-2"></div>
                            <div class="fs-8">Memuat daftar chat...</div>
                        </div>
                    </div>
                </div>

                <!-- MAIN CHAT PANE -->
                <div class="kb-chat-main" id="chat_main_panel">

                    <!-- Default Blank State -->
                    <div id="blank_chat_state" class="d-flex flex-column align-items-center justify-content-center h-100 text-center p-6">
                        <div class="symbol symbol-80px bg-light-primary rounded-circle mb-4 d-flex align-items-center justify-content-center">
                            <i class="ki-duotone ki-message-text-2 fs-3tx text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        </div>
                        <h4 class="fw-bolder text-gray-900 mb-2">Selamat Datang di Fitur Chat & Diskusi</h4>
                        <p class="text-muted fs-7 mw-400px mb-4">
                            Pilih salah satu percakapan di bilah kiri, atau klik tombol tambah untuk memulai obrolan langsung dengan peserta maupun admin.
                        </p>
                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-5 fw-bold" data-bs-toggle="modal" data-bs-target="#modal_new_chat">
                            <i class="ki-duotone ki-plus fs-3 me-1"></i> Mulai Obrolan Baru
                        </button>
                        <div class="text-muted fs-8 mt-5">
                            <kbd class="bg-light-dark text-gray-700 px-2 py-1 rounded">ESC</kbd> untuk menutup chat saat ini
                        </div>
                    </div>

                    <!-- Active Room Interface (Hidden by default) -->
                    <div id="active_chat_interface" class="d-none d-flex flex-column h-100 overflow-hidden" style="min-height: 0;">

                        <!-- Header Active Room -->
                        <div class="p-3 px-4 border-bottom bg-white d-flex align-items-center justify-content-between flex-shrink-0">
                            <div class="d-flex align-items-center gap-3 cursor-pointer" onclick="document.getElementById('btn_room_info')?.click()" title="Klik untuk melihat detail partisipan & informasi percakapan">
                                <button type="button" id="btn_mobile_back" class="btn btn-icon btn-sm btn-light rounded-circle d-md-none" onclick="event.stopPropagation();" title="Kembali ke Daftar Chat">
                                    <i class="fas fa-arrow-left fs-5"></i>
                                </button>
                                <div class="position-relative d-flex align-items-center">
                                    <img id="active_room_avatar" src="<?= base_url('assets/app-assets/user/default.jpg') ?>" onerror="this.onerror=null;this.src='<?= base_url('assets/app-assets/user/default.jpg') ?>'" alt="Avatar" class="w-40px h-40px rounded-circle object-fit-cover shadow-sm border border-2 border-white" />
                                    <img id="active_room_second_avatar" src="<?= base_url('assets/app-assets/user/default.jpg') ?>" onerror="this.onerror=null;this.src='<?= base_url('assets/app-assets/user/default.jpg') ?>'" alt="Avatar 2" class="w-40px h-40px rounded-circle object-fit-cover shadow-sm border border-2 border-white d-none" style="margin-left: -12px;" />
                                    <span id="active_room_online_dot" class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-white" style="width: 10px; height: 10px;"></span>
                                </div>
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="fw-bolder text-gray-900 mb-0" id="active_room_name">-</h6>
                                        <span id="active_room_type_badge" class="badge badge-light-primary fs-9 px-2 py-0">Personal</span>
                                    </div>
                                    <span class="text-muted fs-8" id="active_room_meta">Memuat...</span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <!-- Tombol Detail Partisipan / Info Grup -->
                                <button type="button" id="btn_room_info" class="btn btn-icon btn-sm btn-light-info rounded-circle" title="Detail Partisipan & Informasi Percakapan">
                                    <i class="fas fa-info-circle fs-4 text-info"></i>
                                </button>

                                <!-- Tombol Tutup Chat (ESC) -->
                                <button type="button" id="btn_close_chat" class="btn btn-icon btn-sm btn-light-dark rounded-circle" title="Tutup Chat (Tekan tombol ESC)">
                                    <i class="fas fa-times fs-4"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Messages Scroll Container -->
                        <div class="kb-messages-scroll" id="messages_scroll_box">
                            <div class="text-center py-10 text-muted" id="messages_loading">
                                <div class="spinner-border spinner-border-sm text-primary mb-2"></div>
                                <div class="fs-8">Memuat riwayat obrolan...</div>
                            </div>
                            <div id="messages_container" class="d-flex flex-column gap-3"></div>
                        </div>

                        <!-- Active Reply Box -->
                        <div id="active_reply_box" class="kb-active-reply-bar d-none">
                            <div class="d-flex align-items-center gap-2 overflow-hidden">
                                <i class="fas fa-reply fs-5 text-primary"></i>
                                <div class="text-truncate">
                                    <strong class="fs-8 text-primary" id="reply_author_name">-</strong>
                                    <div class="fs-8 text-muted text-truncate" id="reply_text_preview">-</div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-icon btn-light-danger rounded-circle" id="btn_cancel_reply" title="Batal Balas">
                                <i class="fas fa-times fs-6"></i>
                            </button>
                        </div>

                        <!-- Banner Mode Monitoring Read-Only (Tampil khusus saat Admin Monitoring Semua) -->
                        <div id="monitoring_readonly_bar" class="d-none p-3 px-4 bg-light-warning border-top d-flex align-items-center justify-content-between flex-shrink-0">
                            <div class="d-flex align-items-center gap-2 text-gray-800 fs-8 fw-semibold">
                                <span class="badge badge-warning text-dark fw-bold px-2 py-1"><i class="fas fa-eye me-1 text-dark"></i> MODE MONITORING</span>
                                <span>Anda sedang memantau komunikasi ini (Hanya melihat isi percakapan & tidak dapat membalas pesan).</span>
                            </div>
                            <button type="button" class="btn btn-xs btn-light-primary fw-bold flex-shrink-0" onclick="document.getElementById('btn_room_info')?.click()">
                                <i class="fas fa-users me-1"></i> Detail Partisipan
                            </button>
                        </div>

                        <!-- Input & Attachments Bar -->
                        <div id="chat_input_bar_wrapper" class="p-3 bg-white border-top position-relative flex-shrink-0">

                            <!-- Emoji Picker Panel -->
                            <div class="kb-emoji-panel" id="emoji_panel">
                                <div class="row g-1 text-center" id="emoji_list_grid"></div>
                            </div>

                            <form id="form_send_message" class="d-flex align-items-center gap-2" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <input type="hidden" name="room_id" id="input_room_id" value="" />
                                <input type="hidden" name="reply_to_id" id="input_reply_to_id" value="" />

                                <!-- Tombol Emoji -->
                                <button type="button" id="btn_toggle_emoji" class="btn btn-icon btn-sm btn-light-primary rounded-circle" title="Pilih Emoji / Icon">
                                    😊
                                </button>

                                <!-- Tombol Lampiran Gambar/File -->
                                <label class="btn btn-icon btn-sm btn-light-primary rounded-circle mb-0 cursor-pointer" title="Kirim Gambar / File (Otomatis Di-kompres)">
                                    <i class="fas fa-paperclip fs-4 text-primary"></i>
                                    <input type="file" name="file" id="input_chat_file" class="d-none" accept="image/*,.pdf,.doc,.docx" />
                                </label>

                                <!-- Preview File Terpilih -->
                                <div id="file_preview_badge" class="badge badge-light-primary d-none align-items-center gap-1 py-2 px-3">
                                    <span id="file_preview_name" class="fs-8 text-truncate" style="max-width: 120px;">file.jpg</span>
                                    <i class="fas fa-times fs-6 cursor-pointer text-danger ms-1" id="btn_remove_file"></i>
                                </div>

                                <!-- Input Teks Pesan -->
                                <textarea name="message" id="input_chat_message" rows="1" class="form-control form-control-solid resize-none rounded-pill py-2 px-4 fs-7" placeholder="Ketik pesan Anda... (Kirim link untuk meta otomatis)" style="max-height: 90px;"></textarea>

                                <!-- Tombol Kirim -->
                                <button type="submit" id="btn_send_submit" class="btn btn-icon btn-primary rounded-circle shadow-sm" title="Kirim Pesan (Enter)">
                                    <i class="fas fa-paper-plane fs-4 text-white"></i>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL: Mulai Chat Baru / Cari Peserta                           -->
<!-- ============================================================== -->
<div class="modal fade" id="modal_new_chat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-450px">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header py-4 px-6 border-0">
                <h4 class="fw-bolder mb-0 text-gray-900">Mulai Percakapan Baru</h4>
                <div class="btn btn-sm btn-icon btn-light" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body px-6 py-2">
                <div class="position-relative mb-3">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute top-50 translate-middle-y ms-3 text-gray-500">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <input type="text" id="search_contact_input" class="form-control form-control-solid ps-10 rounded-pill" placeholder="<?= $isAdmin ? 'Cari nama Peserta, Pengajar, PIC, Mitra...' : 'Cari nama Peserta, Admin, atau Pengajar...' ?>" />
                </div>

                <!-- Filter Kategori Role Kontak -->
                <div class="d-flex flex-wrap gap-1 mb-3" id="contact_role_filter_group">
                    <button type="button" class="btn btn-xs btn-primary rounded-pill px-3 fw-bold contact-role-tab" data-role="all">Semua</button>
                    <button type="button" class="btn btn-xs btn-light rounded-pill px-3 fw-bold contact-role-tab" data-role="siswa">Peserta</button>
                    <?php if ($isAdmin || !empty($guruChatStatus)): ?>
                        <button type="button" class="btn btn-xs btn-light rounded-pill px-3 fw-bold contact-role-tab" data-role="guru">Pengajar</button>
                    <?php endif; ?>
                    <?php if ($isAdmin): ?>
                        <button type="button" class="btn btn-xs btn-light rounded-pill px-3 fw-bold contact-role-tab" data-role="pic">PIC</button>
                        <button type="button" class="btn btn-xs btn-light rounded-pill px-3 fw-bold contact-role-tab" data-role="mitra">Mitra</button>
                    <?php else: ?>
                        <button type="button" class="btn btn-xs btn-light rounded-pill px-3 fw-bold contact-role-tab" data-role="admin">Admin</button>
                    <?php endif; ?>
                </div>

                <div class="overflow-auto" style="max-height: 340px;" id="contact_list_container">
                    <div class="text-center py-6 text-muted fs-8">Ketik nama untuk mencari...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL: Buat Grup Baru                                           -->
<!-- ============================================================== -->
<div class="modal fade" id="modal_create_group" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-500px">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header py-4 px-6 border-0">
                <h4 class="fw-bolder mb-0 text-gray-900">Buat Grup Diskusi Baru</h4>
                <div class="btn btn-sm btn-icon btn-light" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <form id="form_create_group">
                <?= csrf_field() ?>
                <div class="modal-body px-6 py-3">
                    <div class="mb-4">
                        <label class="form-label fw-bold required">Nama Grup</label>
                        <input type="text" name="name" id="group_name_input" class="form-control form-control-solid" placeholder="Contoh: Diskusi Pajak Brevet Batch 12" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Anggota</label>
                        <input type="text" id="search_group_members_input" class="form-control form-control-sm form-control-solid mb-2" placeholder="Filter nama peserta..." />
                        <div class="border rounded p-2 overflow-auto" style="max-height: 200px;" id="group_members_pick_list">
                            <div class="text-center text-muted py-4 fs-8">Memuat daftar peserta...</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-3 px-6 border-0">
                    <button type="button" class="btn btn-light btn-sm fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold" id="btn_submit_group">Buat Grup</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL: Detail Grup & Anggota                                    -->
<!-- ============================================================== -->
<div class="modal fade" id="modal_group_info" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-450px">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header py-4 px-6 border-0">
                <h4 class="fw-bolder mb-0 text-gray-900" id="info_group_title">Detail Grup</h4>
                <div class="btn btn-sm btn-icon btn-light" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body px-6 py-2">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="fw-bold fs-7 text-gray-700">Daftar Anggota (<span id="info_group_count">0</span>)</span>
                    <button type="button" class="btn btn-xs btn-light-primary fw-bold" id="btn_open_add_member">
                        <i class="ki-duotone ki-plus fs-4"></i> Tambah Anggota
                    </button>
                </div>
                <div class="overflow-auto" style="max-height: 300px;" id="info_group_members_list"></div>

                <!-- Form Tambah Anggota Baru -->
                <div id="add_member_section" class="mt-3 pt-3 border-top d-none">
                    <label class="form-label fs-8 fw-bold">Pilih Peserta untuk Ditambahkan</label>
                    <div class="position-relative mb-2">
                        <input type="text" id="search_add_member_input" class="form-control form-control-sm form-control-solid" placeholder="Cari nama..." />
                    </div>
                    <div class="border rounded p-2 overflow-auto mb-2" style="max-height: 140px;" id="add_member_candidates_list"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL: Edit Pesan (< 5 Menit)                                   -->
<!-- ============================================================== -->
<div class="modal fade" id="modal_edit_message" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-400px">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header py-3 px-5 border-0">
                <h5 class="fw-bolder mb-0 text-gray-900">Edit Pesan</h5>
                <div class="btn btn-sm btn-icon btn-light" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <form id="form_edit_message">
                <?= csrf_field() ?>
                <input type="hidden" name="message_id" id="edit_message_id" />
                <div class="modal-body px-5 py-2">
                    <textarea name="message" id="edit_message_text" rows="3" class="form-control form-control-solid" required></textarea>
                    <span class="fs-8 text-muted mt-1 d-block">*Hanya dapat diedit sebelum 5 menit sejak dikirim</span>
                </div>
                <div class="modal-footer py-2 px-5 border-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    const CURRENT_USER = <?= json_encode($currentUser) ?>;
    const IS_ADMIN = <?= $isAdmin ? 'true' : 'false' ?>;
    const DEFAULT_AVATAR = "<?= base_url('assets/app-assets/user/default.jpg') ?>";
    let activeRoomId = null;
    let activeRoomData = null;
    let roomsMap = {};
    let selectedMessageId = null;
    let pollingInterval = null;
    let lastLoadedMessageId = 0;
    let adminFilterMode = 'my'; // 'my' or 'all'

    // Sesuaikan posisi & tinggi container chat secara presisi di bawah navbar atas hingga di atas bottom navbar (tablet/mobile)
    function adjustChatHeight() {
        const appEl = document.querySelector('.kb-chat-app');
        if (!appEl) return;
        const headerEl = document.getElementById('kt_app_header') || document.getElementById('kt_header');
        let topPos = 76;
        if (headerEl) {
            const hRect = headerEl.getBoundingClientRect();
            if (hRect.height > 20) {
                topPos = Math.max(60, Math.round(hRect.height + 10));
            }
        }

        // Deteksi bottom navbar (.siswa-mobile-bottom-nav) yang tampil pada layar tablet & mobile (<= 991.98px)
        let bottomPos = 12;
        const bottomNav = document.querySelector('.siswa-mobile-bottom-nav');
        if (bottomNav && window.getComputedStyle(bottomNav).display !== 'none') {
            const bRect = bottomNav.getBoundingClientRect();
            if (bRect.height > 0) {
                bottomPos = Math.round(bRect.height + 8);
            }
        }

        appEl.style.top = topPos + 'px';
        appEl.style.bottom = bottomPos + 'px';
        appEl.style.height = 'auto';
    }
    window.addEventListener('resize', adjustChatHeight);

    const emojis = ['👍','❤️','😊','🙏','😂','🔥','👏','🎉','💼','📊','💡','✅','❌','⚖️','💯','🤝','⭐','🎓','📖','💬'];

    // Render Emojis
    const emojiGrid = document.getElementById('emoji_list_grid');
    if (emojiGrid) {
        emojis.forEach(emo => {
            const col = document.createElement('div');
            col.className = 'col-3 kb-emoji-item';
            col.innerText = emo;
            col.onclick = () => {
                const txt = document.getElementById('input_chat_message');
                txt.value += emo;
                txt.focus();
                document.getElementById('emoji_panel').style.display = 'none';
            };
            emojiGrid.appendChild(col);
        });
    }

    document.getElementById('btn_toggle_emoji')?.addEventListener('click', (e) => {
        e.stopPropagation();
        const p = document.getElementById('emoji_panel');
        p.style.display = (p.style.display === 'block') ? 'none' : 'block';
    });

    // Klik di luar pesan atau emoji menutup panel pilihan
    document.addEventListener('click', (e) => {
        const p = document.getElementById('emoji_panel');
        if (p && !p.contains(e.target) && e.target.id !== 'btn_toggle_emoji') {
            p.style.display = 'none';
        }
        if (selectedMessageId && !e.target.closest('.kb-msg-row')) {
            clearSelectedMessage();
        }
    });

    function toggleSelectMessage(msgId, e) {
        if (e && (e.target.tagName === 'A' || e.target.tagName === 'IMG' || e.target.closest('a'))) {
            return;
        }
        const row = document.getElementById('msg_row_' + msgId);
        if (!row) return;

        if (selectedMessageId === msgId) {
            clearSelectedMessage();
        } else {
            clearSelectedMessage();
            selectedMessageId = msgId;
            row.classList.add('is-selected');
        }
    }

    function clearSelectedMessage() {
        selectedMessageId = null;
        document.querySelectorAll('.kb-msg-row.is-selected').forEach(el => el.classList.remove('is-selected'));
    }

    // ESC Key to close current chat
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' || e.keyCode === 27) {
            if (selectedMessageId) {
                clearSelectedMessage();
            } else {
                closeActiveChat();
            }
        }
    });

    document.getElementById('btn_close_chat')?.addEventListener('click', closeActiveChat);
    document.getElementById('btn_mobile_back')?.addEventListener('click', closeActiveChat);

    function closeActiveChat() {
        if (pollingInterval) clearInterval(pollingInterval);
        activeRoomId = null;
        activeRoomData = null;
        selectedMessageId = null;
        document.querySelector('.kb-chat-app')?.classList.remove('has-active-room');
        document.getElementById('active_chat_interface').classList.add('d-none');
        document.getElementById('blank_chat_state').classList.remove('d-none');
        document.querySelectorAll('.kb-room-item').forEach(el => el.classList.remove('active'));
    }

    // Toggle Chat Status (Admin only)
    document.getElementById('btn_toggle_chat_status')?.addEventListener('click', function() {
        if (!confirm('Apakah Anda yakin ingin mengubah status aktif/nonaktif fitur chat ini?')) return;
        $.post("<?= base_url('chat/toggle-status') ?>", {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }, function(res) {
            if (res.status === 'success') {
                alert(res.message);
                location.reload();
            } else {
                alert(res.message || 'Gagal mengubah status chat.');
            }
        }, 'json').fail(xhr => alert(xhr.responseJSON?.message || 'Gagal mengubah status fitur chat.'));
    });

    // Toggle Pengajar / Guru di Chat (Admin only)
    document.getElementById('btn_toggle_guru_status')?.addEventListener('click', function() {
        const btn = this;
        btn.disabled = true;
        $.post("<?= base_url('chat/toggle-guru-status') ?>", {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }, function(res) {
            btn.disabled = false;
            if (res.status === 'success') {
                if (res.enabled) {
                    btn.className = 'btn btn-xs py-0 px-2 fw-bold btn-success';
                    btn.innerText = 'Aktif';
                } else {
                    btn.className = 'btn btn-xs py-0 px-2 fw-bold btn-secondary';
                    btn.innerText = 'Nonaktif';
                }
                alert(res.message);
            } else {
                alert(res.message || 'Gagal mengubah status pengajar.');
            }
        }, 'json').fail(xhr => {
            btn.disabled = false;
            alert(xhr.responseJSON?.message || 'Gagal mengubah status pengajar.');
        });
    });

    // Ringtone Suara Pesan Masuk (Web Audio API Chime)
    let soundEnabled = localStorage.getItem('kb_chat_sound') !== 'off';
    let chatAudioCtx = null;
    let prevTotalUnread = null;

    function updateSoundIcon() {
        const icon = document.getElementById('icon_sound_status');
        const btn = document.getElementById('btn_toggle_sound');
        if (!icon || !btn) return;
        if (soundEnabled) {
            icon.className = 'fas fa-volume-up fs-5 text-info';
            btn.className = 'btn btn-icon btn-sm btn-light-info rounded-circle';
            btn.title = 'Suara Ringtone Pesan Masuk: Aktif (Klik untuk Matikan)';
        } else {
            icon.className = 'fas fa-volume-mute fs-5 text-muted';
            btn.className = 'btn btn-icon btn-sm btn-light rounded-circle';
            btn.title = 'Suara Ringtone Pesan Masuk: Nonaktif (Klik untuk Aktifkan)';
        }
    }
    updateSoundIcon();

    function playIncomingRingtone(forceTest = false) {
        if (!soundEnabled && !forceTest) return;
        try {
            const AudioContextClass = window.AudioContext || window.webkitAudioContext;
            if (!AudioContextClass) return;
            if (!chatAudioCtx) chatAudioCtx = new AudioContextClass();
            if (chatAudioCtx.state === 'suspended') chatAudioCtx.resume();

            const now = chatAudioCtx.currentTime;
            // Nada 1 (D5 -> 587.33 Hz)
            const osc1 = chatAudioCtx.createOscillator();
            const gain1 = chatAudioCtx.createGain();
            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(587.33, now);
            gain1.gain.setValueAtTime(0.28, now);
            gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.18);
            osc1.connect(gain1);
            gain1.connect(chatAudioCtx.destination);
            osc1.start(now);
            osc1.stop(now + 0.18);

            // Nada 2 (A5 -> 880 Hz)
            const osc2 = chatAudioCtx.createOscillator();
            const gain2 = chatAudioCtx.createGain();
            osc2.type = 'sine';
            osc2.frequency.setValueAtTime(880, now + 0.13);
            gain2.gain.setValueAtTime(0.32, now + 0.13);
            gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.45);
            osc2.connect(gain2);
            gain2.connect(chatAudioCtx.destination);
            osc2.start(now + 0.13);
            osc2.stop(now + 0.45);
        } catch (e) {}
    }

    document.getElementById('btn_toggle_sound')?.addEventListener('click', function() {
        soundEnabled = !soundEnabled;
        localStorage.setItem('kb_chat_sound', soundEnabled ? 'on' : 'off');
        updateSoundIcon();
        if (soundEnabled) {
            playIncomingRingtone(true);
        }
    });

    // Admin Mode Buttons
    $('#btn_mode_my')?.on('click', function() {
        adminFilterMode = 'my';
        $(this).removeClass('btn-light').addClass('btn-primary');
        $('#btn_mode_all').removeClass('btn-primary').addClass('btn-light');
        closeActiveChat();
        loadRooms();
    });
    $('#btn_mode_all')?.on('click', function() {
        adminFilterMode = 'all';
        $(this).removeClass('btn-light').addClass('btn-primary');
        $('#btn_mode_my').removeClass('btn-primary').addClass('btn-light');
        closeActiveChat();
        loadRooms();
    });

    // Load Rooms
    function loadRooms(search = '') {
        const url = "<?= base_url('chat/rooms') ?>?mode=" + adminFilterMode;
        $.getJSON(url, function(rooms) {
            const container = document.getElementById('room_list_container');
            if (!container) return;

            let totalUnreadNow = 0;
            rooms.forEach(r => {
                roomsMap[r.id] = r;
                totalUnreadNow += Number(r.unread_count || 0);
            });

            if (prevTotalUnread !== null && totalUnreadNow > prevTotalUnread) {
                playIncomingRingtone();
            }
            prevTotalUnread = totalUnreadNow;

            let filtered = rooms;
            if (search.trim() !== '') {
                const s = search.toLowerCase();
                filtered = rooms.filter(r => r.name.toLowerCase().includes(s) || r.last_message.toLowerCase().includes(s));
            }

            if (filtered.length === 0) {
                container.innerHTML = '<div class="text-center py-10 text-muted fs-8">Tidak ada percakapan ditemukan.</div>';
                return;
            }

            let html = '';
            filtered.forEach(r => {
                const isActive = (r.id === activeRoomId) ? 'active' : '';
                const unreadBadge = (r.unread_count > 0) ? `<span class="badge badge-circle badge-primary fs-9 ms-auto">${r.unread_count}</span>` : '';
                const typeBadge = (r.type === 'group')
                    ? `<span class="badge badge-light-primary px-2 py-0 fs-9 me-1"><i class="fas fa-users me-1" style="font-size:9px;"></i>Grup</span>`
                    : `<span class="badge badge-light-info px-2 py-0 fs-9 me-1"><i class="fas fa-user-friends me-1" style="font-size:9px;"></i>Personal</span>`;

                const avatarHtml = r.second_avatar
                    ? `<div class="d-flex align-items-center flex-shrink-0">
                           <img src="${r.avatar || DEFAULT_AVATAR}" onerror="this.onerror=null;this.src='${DEFAULT_AVATAR}'" class="w-35px h-35px rounded-circle object-fit-cover border border-2 border-white shadow-xs" />
                           <img src="${r.second_avatar || DEFAULT_AVATAR}" onerror="this.onerror=null;this.src='${DEFAULT_AVATAR}'" class="w-35px h-35px rounded-circle object-fit-cover border border-2 border-white shadow-xs" style="margin-left: -12px;" />
                       </div>`
                    : `<div class="position-relative flex-shrink-0">
                           <img src="${r.avatar || DEFAULT_AVATAR}" onerror="this.onerror=null;this.src='${DEFAULT_AVATAR}'" class="w-40px h-40px rounded-circle object-fit-cover border" />
                       </div>`;

                const roleSubHtml = r.participants_subtitle
                    ? `<div class="fs-9 text-primary fw-semibold text-truncate mb-1">${escapeHtml(r.participants_subtitle)}</div>`
                    : '';

                html += `
                    <div class="kb-room-item d-flex align-items-center gap-3 ${isActive}" id="room_item_${r.id}" onclick="selectRoom(${r.id})">
                        ${avatarHtml}
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex align-items-center justify-content-between mb-0">
                                <span class="fw-bold text-gray-900 fs-7 text-truncate">${typeBadge}${escapeHtml(r.name)}</span>
                                <span class="fs-9 text-muted flex-shrink-0 ms-1">${r.last_message_at}</span>
                            </div>
                            ${roleSubHtml}
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="fs-8 text-muted text-truncate" style="max-width: 190px;">${escapeHtml(r.last_message)}</span>
                                ${unreadBadge}
                            </div>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
        }).fail(function() {
            const container = document.getElementById('room_list_container');
            if (container) {
                container.innerHTML = '<div class="text-center py-10 text-muted fs-8">Gagal memuat daftar chat. <a href="javascript:void(0)" onclick="loadRooms()">Coba lagi</a></div>';
            }
        });
    }

    document.getElementById('search_room_input')?.addEventListener('input', function() {
        loadRooms(this.value);
    });

    function applyMonitoringUI(isMonitoring) {
        const inputWrapper = document.getElementById('chat_input_bar_wrapper');
        const monitorBar   = document.getElementById('monitoring_readonly_bar');
        if (isMonitoring) {
            inputWrapper?.classList.add('d-none');
            monitorBar?.classList.remove('d-none');
            cancelReply();
        } else {
            inputWrapper?.classList.remove('d-none');
            monitorBar?.classList.add('d-none');
        }
    }

    // Select Room
    function selectRoom(roomId) {
        activeRoomId = roomId;
        lastLoadedMessageId = 0;
        selectedMessageId = null;
        document.querySelector('.kb-chat-app')?.classList.add('has-active-room');
        document.getElementById('blank_chat_state').classList.add('d-none');
        document.getElementById('active_chat_interface').classList.remove('d-none');
        document.getElementById('input_room_id').value = roomId;
        cancelReply();

        // Highlight room aktif
        document.querySelectorAll('.kb-room-item').forEach(el => el.classList.remove('active'));
        document.getElementById('room_item_' + roomId)?.classList.add('active');

        const typeBadgeEl = document.getElementById('active_room_type_badge');
        const secondAvatarEl = document.getElementById('active_room_second_avatar');

        // Tampilkan langsung nama & foto peserta dari cache sidebar sebelum detail API selesai
        if (roomsMap[roomId]) {
            const cached = roomsMap[roomId];
            document.getElementById('active_room_name').innerText = cached.name || 'Chat';
            document.getElementById('active_room_avatar').src = cached.avatar || DEFAULT_AVATAR;
            if (cached.second_avatar) {
                secondAvatarEl.src = cached.second_avatar;
                secondAvatarEl.classList.remove('d-none');
            } else {
                secondAvatarEl.classList.add('d-none');
            }
            if (typeBadgeEl) {
                typeBadgeEl.className = cached.type === 'group' ? 'badge badge-light-primary fs-9 px-2 py-0' : 'badge badge-light-info fs-9 px-2 py-0';
                typeBadgeEl.innerText = cached.type === 'group' ? 'Grup' : 'Personal';
            }
            document.getElementById('active_room_meta').innerText = (cached.type === 'group') ? (cached.total_members + ' Anggota Grup') : (cached.participants_subtitle || 'Personal Chat');
            applyMonitoringUI(!!cached.is_monitoring || (IS_ADMIN && adminFilterMode === 'all'));
        }

        // Load detail lengkap room & lawan bicara, lalu muat pesan
        $.getJSON("<?= base_url('chat/room-details/') ?>" + roomId + "?mode=" + adminFilterMode, function(res) {
            if (res.status === 'success') {
                activeRoomData = res.room;
                document.getElementById('active_room_name').innerText = res.room.name || 'Chat';
                document.getElementById('active_room_avatar').src = res.room.avatar || DEFAULT_AVATAR;
                if (res.room.second_avatar) {
                    secondAvatarEl.src = res.room.second_avatar;
                    secondAvatarEl.classList.remove('d-none');
                } else {
                    secondAvatarEl.classList.add('d-none');
                }
                if (typeBadgeEl) {
                    typeBadgeEl.className = res.room.type === 'group' ? 'badge badge-light-primary fs-9 px-2 py-0' : 'badge badge-light-info fs-9 px-2 py-0';
                    typeBadgeEl.innerText = res.room.type === 'group' ? 'Grup' : 'Personal';
                }
                document.getElementById('active_room_meta').innerText = res.room.subtitle || (res.room.type === 'group' ? (res.members.length + ' Anggota Grup') : 'Personal Chat');

                const isMonitoring = !!res.room.is_monitoring || (IS_ADMIN && adminFilterMode === 'all');
                applyMonitoringUI(isMonitoring);

                // Muat pesan setelah activeRoomData siap agar layout kiri-kanan & profil akurat
                lastLoadedMessageId = 0;
                loadMessages(true);
                if (!isMonitoring) {
                    setTimeout(() => document.getElementById('input_chat_message')?.focus(), 150);
                }
            }
        });

        // Polling realtime (setiap 2 detik)
        if (pollingInterval) clearInterval(pollingInterval);
        pollingInterval = setInterval(() => {
            if (activeRoomId) loadMessages(false);
        }, 2000);
    }

    function loadMessages(isInitial = false) {
        if (!activeRoomId) return;

        // Ambil seluruh pesan room agar edit & tarik pesan (<5 menit) terdeteksi realtime
        const url = "<?= base_url('chat/messages/') ?>" + activeRoomId + "?last_id=0";
        $.getJSON(url, function(res) {
            document.getElementById('messages_loading')?.remove();
            if (res.status === 'success') {
                const box = document.getElementById('messages_container');
                if (isInitial) box.innerHTML = '';

                if (res.messages.length === 0) {
                    if (!document.getElementById('empty_room_hint')) {
                        box.innerHTML = '<div id="empty_room_hint" class="text-center py-8"><span class="bg-white text-muted fs-8 px-4 py-2 rounded-pill shadow-sm d-inline-block border">👋 Belum ada pesan dalam percakapan ini.</span></div>';
                    }
                    return;
                } else {
                    document.getElementById('empty_room_hint')?.remove();
                }

                let hasNewMessage = false;
                let hasIncomingFromOther = false;
                const isMonitoring = !!(activeRoomData && activeRoomData.is_monitoring) || (IS_ADMIN && adminFilterMode === 'all');
                res.messages.forEach(msg => {
                    if (msg.id > lastLoadedMessageId) {
                        const isMsgFromMe = (msg.sender_role === CURRENT_USER.role && Number(msg.sender_id) === Number(CURRENT_USER.id));
                        if (!isInitial && lastLoadedMessageId > 0 && !isMsgFromMe) {
                            hasIncomingFromOther = true;
                        }
                        lastLoadedMessageId = msg.id;
                        hasNewMessage = true;
                    }
                    const reactSig = JSON.stringify(msg.reactions || []);
                    const sig = `${msg.message}|${msg.is_edited ? 1 : 0}|${msg.is_deleted ? 1 : 0}|${msg.can_modify ? 1 : 0}|${reactSig}|${isMonitoring ? 1 : 0}`;
                    const existing = document.getElementById('msg_row_' + msg.id);
                    if (existing) {
                        // Hanya update DOM jika ada perubahan status (diedit / ditarik / reaksi / waktu 5m habis)
                        if (existing.getAttribute('data-sig') !== sig) {
                            const wasSelected = existing.classList.contains('is-selected');
                            existing.outerHTML = renderMessageRow(msg, sig, wasSelected);
                        }
                    } else {
                        box.insertAdjacentHTML('beforeend', renderMessageRow(msg, sig, false));
                    }
                });

                if (hasIncomingFromOther && !isMonitoring) {
                    playIncomingRingtone();
                    loadRooms(document.getElementById('search_room_input')?.value || '');
                }

                if (isInitial || hasNewMessage) {
                    const scrollBox = document.getElementById('messages_scroll_box');
                    if (scrollBox) scrollBox.scrollTop = scrollBox.scrollHeight;
                }
            }
        });
    }

    function scrollToMessage(msgId) {
        const target = document.getElementById('msg_row_' + msgId);
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'center' });
            target.classList.add('is-selected');
            setTimeout(() => {
                if (selectedMessageId !== msgId) target.classList.remove('is-selected');
            }, 1600);
        }
    }

    function renderMessageRow(msg, sig = '', isSelected = false) {
        const isMe = (msg.sender_role === CURRENT_USER.role && Number(msg.sender_id) === Number(CURRENT_USER.id));
        const isMonitoring = !!(activeRoomData && activeRoomData.is_monitoring) || (IS_ADMIN && adminFilterMode === 'all');

        // Saat mode Monitoring Personal Chat antara 2 peserta, pisahkan posisi kiri (Partisipan 1) dan kanan (Partisipan 2) agar alur dialog terlihat jelas
        let isRightSide = isMe;
        if (isMonitoring && activeRoomData && activeRoomData.type === 'direct' && activeRoomData.first_member) {
            const isFirstParticipant = (msg.sender_role === activeRoomData.first_member.role && Number(msg.sender_id) === Number(activeRoomData.first_member.id));
            isRightSide = !isFirstParticipant;
        }

        const bubbleClass = msg.is_deleted ? 'kb-bubble-deleted' : (isRightSide ? 'kb-bubble-me' : 'kb-bubble-other');
        const selectedClass = (isSelected || selectedMessageId === msg.id) ? 'is-selected' : '';

        // Info Lengkap Pengirim (Nama + Badge Role) di dalam setiap gelembung pesan
        const roleBadgeColor = isRightSide ? 'bg-white bg-opacity-20 text-white' : 'badge-light-primary text-primary';
        const senderNameColor = isRightSide ? 'text-white' : 'text-gray-900';
        const senderHeaderHtml = `
            <div class="d-flex align-items-center justify-content-between gap-2 mb-1 pb-1 border-bottom ${isRightSide ? 'border-white border-opacity-25' : 'border-gray-200'}">
                <span class="fw-bolder fs-8 ${senderNameColor}">${escapeHtml(msg.sender_name)}</span>
                <span class="badge ${roleBadgeColor} fs-10 px-2 py-0">${escapeHtml(msg.sender_role_label || msg.sender_role)}</span>
            </div>
        `;

        // Foto Profil (Avatar) Pengirim di samping gelembung chat
        const avatarImgHtml = `
            <div class="flex-shrink-0 align-self-end ${isRightSide ? 'ms-2 order-last' : 'me-2'}">
                <img src="${msg.sender_avatar || DEFAULT_AVATAR}" onerror="this.onerror=null;this.src='${DEFAULT_AVATAR}'" class="w-35px h-35px rounded-circle object-fit-cover border shadow-xs" title="${escapeHtml(msg.sender_name)} (${escapeHtml(msg.sender_role_label || msg.sender_role)})" />
            </div>
        `;

        // Reply box (klik untuk lompat ke pesan yang dibalas)
        let replyHtml = '';
        if (msg.reply_to && !msg.is_deleted) {
            replyHtml = `
                <div class="kb-reply-preview-box" onclick="event.stopPropagation(); scrollToMessage(${msg.reply_to.id})">
                    <strong>${escapeHtml(msg.reply_to.sender_name)}</strong>
                    <div class="text-truncate">${escapeHtml(msg.reply_to.message)}</div>
                </div>
            `;
        }

        // File Attachment
        let fileHtml = '';
        if (msg.file_attachment && !msg.is_deleted) {
            if (msg.file_type === 'image') {
                fileHtml = `<div class="mb-2"><a href="${msg.file_attachment}" target="_blank"><img src="${msg.file_attachment}" class="img-fluid rounded border" style="max-height: 220px;" /></a></div>`;
            } else {
                fileHtml = `<div class="mb-2"><a href="${msg.file_attachment}" target="_blank" class="btn btn-sm btn-light fw-bold"><i class="fas fa-file-download me-1"></i> ${escapeHtml(msg.file_name || 'Download File')}</a></div>`;
            }
        }

        // Link Preview Card
        let linkHtml = '';
        if (msg.link_preview && !msg.is_deleted) {
            const lp = msg.link_preview;
            const imgTag = lp.image ? `<img src="${escapeHtml(lp.image)}" class="kb-link-img" />` : '';
            linkHtml = `
                <a href="${escapeHtml(lp.url)}" target="_blank" class="kb-link-card">
                    ${imgTag}
                    <div class="p-2">
                        <strong class="fs-8 text-dark d-block text-truncate">${escapeHtml(lp.title)}</strong>
                        <span class="fs-9 text-muted d-block text-truncate">${escapeHtml(lp.description)}</span>
                    </div>
                </a>
            `;
        }

        // Reaksi Emoji pada pesan (ala WhatsApp)
        const quickEmojis = ['👍', '❤️', '😂', '😮', '😢', '🙏'];
        const msgReactions = Array.isArray(msg.reactions) ? msg.reactions : [];
        let myReactedEmoji = '';
        msgReactions.forEach(r => {
            if (r.user_role === CURRENT_USER.role && Number(r.user_id) === Number(CURRENT_USER.id)) {
                myReactedEmoji = r.emoji;
            }
        });

        let reactionsPillHtml = '';
        if (!msg.is_deleted && msgReactions.length > 0) {
            const uniqueEmojis = [...new Set(msgReactions.map(r => r.emoji))];
            const tooltipNames = msgReactions.map(r => `${r.user_name}: ${r.emoji}`).join(', ');
            const pillClickAttr = isMonitoring ? '' : `onclick="event.stopPropagation(); toggleReaction(${msg.id}, '${myReactedEmoji || uniqueEmojis[0]}')"`;
            reactionsPillHtml = `
                <div class="d-flex ${isRightSide ? 'justify-content-end' : 'justify-content-start'}">
                    <div class="kb-reactions-pill" title="${escapeHtml(tooltipNames)}" ${pillClickAttr}>
                        <span>${uniqueEmojis.join(' ')}</span>
                        ${msgReactions.length > 1 ? `<span class="kb-react-count">${msgReactions.length}</span>` : ''}
                    </div>
                </div>
            `;
        }

        // Action Bar ala WhatsApp (Saat mode Monitoring, Admin hanya melihat dan tidak ada tombol Balas/Reaksi)
        let actionsBar = '';
        let chevronIcon = '';
        if (!msg.is_deleted && !isMonitoring) {
            chevronIcon = `<span class="kb-wa-chevron" title="Klik untuk opsi & reaksi pesan"><i class="fas fa-chevron-down"></i></span>`;

            const canEditOrPull = (isMe && msg.can_modify);
            const canAdminDelete = (!isMe && IS_ADMIN);

            let reactButtonsHtml = '';
            quickEmojis.forEach(emo => {
                const isAct = (myReactedEmoji === emo) ? 'active' : '';
                reactButtonsHtml += `<button type="button" class="kb-react-emoji-btn ${isAct}" onclick="toggleReaction(${msg.id}, '${emo}'); clearSelectedMessage();" title="Beri Reaksi ${emo}">${emo}</button>`;
            });

            actionsBar = `
                <div class="kb-msg-actions-bar ${isRightSide ? 'order-first me-2' : 'ms-2'}" onclick="event.stopPropagation()">
                    <div class="d-flex align-items-center pe-1 border-end me-1">
                        ${reactButtonsHtml}
                    </div>
                    <button type="button" class="kb-act-btn" onclick="startReply(${msg.id}, '${escapeJs(msg.sender_name)}', '${escapeJs(msg.message)}'); clearSelectedMessage();" title="Balas Pesan Ini">
                        <i class="fas fa-reply text-primary"></i> <span>Balas</span>
                    </button>
                    ${canEditOrPull ? `
                        <button type="button" class="kb-act-btn" onclick="openEditModal(${msg.id}, '${escapeJs(msg.message)}'); clearSelectedMessage();" title="Edit Pesan (< 5 menit)">
                            <i class="fas fa-pen text-warning"></i> <span>Edit</span>
                        </button>
                        <button type="button" class="kb-act-btn kb-act-danger" onclick="deleteMessage(${msg.id}); clearSelectedMessage();" title="Tarik / Hapus Pesan (< 5 menit)">
                            <i class="fas fa-trash-alt text-danger"></i> <span>Tarik</span>
                        </button>
                    ` : ''}
                    ${canAdminDelete ? `
                        <button type="button" class="kb-act-btn kb-act-danger" onclick="deleteMessage(${msg.id}); clearSelectedMessage();" title="Hapus Pesan (Admin)">
                            <i class="fas fa-trash-alt text-danger"></i> <span>Hapus</span>
                        </button>
                    ` : ''}
                    <button type="button" class="kb-act-btn" onclick="forwardToWa('${escapeJs(msg.message)}'); clearSelectedMessage();" title="Teruskan ke WhatsApp">
                        <i class="fab fa-whatsapp text-success"></i> <span>Forward</span>
                    </button>
                    <button type="button" class="kb-act-btn" onclick="copyText('${escapeJs(msg.message)}'); clearSelectedMessage();" title="Salin Pesan">
                        <i class="far fa-copy text-info"></i>
                    </button>
                </div>
            `;
        }

        const editedTag = (msg.is_edited && !msg.is_deleted) ? '<span class="fs-9 opacity-75 ms-1">(diedit)</span>' : '';
        const checkIcon = (isRightSide && !msg.is_deleted) ? '<i class="fas fa-check-double ms-1" style="font-size:0.65rem; color:#93c5fd;"></i>' : '';
        const timeDisplay = msg.date_formatted ? `${msg.date_formatted}, ${msg.time_formatted}` : msg.time_formatted;

        return `
            <div class="kb-msg-row d-flex align-items-center ${isRightSide ? 'justify-content-end' : 'justify-content-start'} ${selectedClass}" id="msg_row_${msg.id}" data-sig="${escapeHtml(sig)}">
                ${!isRightSide ? avatarImgHtml : ''}
                ${isRightSide ? actionsBar : ''}
                <div class="d-flex flex-column ${isRightSide ? 'align-items-end' : 'align-items-start'}" style="max-width: 68%; min-width: 180px;">
                    <div class="kb-bubble ${bubbleClass} w-100" style="max-width: 100%;" onclick="toggleSelectMessage(${msg.id}, event)">
                        ${chevronIcon}
                        ${senderHeaderHtml}
                        ${replyHtml}
                        ${fileHtml}
                        <div class="kb-msg-text pe-3">${msg.is_deleted ? '<i class="fas fa-ban me-1"></i> Pesan ini telah ditarik' : linkify(escapeHtml(msg.message || ''))}</div>
                        ${linkHtml}
                        <div class="kb-bubble-time">
                            ${timeDisplay} ${editedTag} ${checkIcon}
                        </div>
                    </div>
                    ${reactionsPillHtml}
                </div>
                ${!isRightSide ? actionsBar : ''}
                ${isRightSide ? avatarImgHtml : ''}
            </div>
        `;
    }

    // Toggle Reaksi Emoji pada Pesan
    function toggleReaction(msgId, emoji) {
        if (!msgId || !emoji) return;
        $.post("<?= base_url('chat/react-message') ?>", {
            message_id: msgId,
            emoji: emoji,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }, function(res) {
            if (res.status === 'success') {
                loadMessages(false);
            }
        }, 'json');
    }

    // Linkify helper
    function linkify(text) {
        const urlPattern = /(\b(https?):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
        return text.replace(urlPattern, '<a href="$1" target="_blank" class="text-decoration-underline text-reset fw-bold">$1</a>');
    }

    // Reply Handler
    function startReply(msgId, senderName, text) {
        document.getElementById('input_reply_to_id').value = msgId;
        document.getElementById('reply_author_name').innerText = senderName;
        document.getElementById('reply_text_preview').innerText = text;
        document.getElementById('active_reply_box').classList.remove('d-none');
        document.getElementById('input_chat_message').focus();
    }
    document.getElementById('btn_cancel_reply')?.addEventListener('click', cancelReply);
    function cancelReply() {
        document.getElementById('input_reply_to_id').value = '';
        document.getElementById('active_reply_box').classList.add('d-none');
    }

    // Fungsi Kirim Pesan (Dipanggil lewat tombol Kirim maupun tombol Enter)
    function sendChatMessage() {
        const formEl = document.getElementById('form_send_message');
        const msgInput = document.getElementById('input_chat_message');
        const fileInput = document.getElementById('input_chat_file');
        const submitBtn = document.getElementById('btn_send_submit');

        if (!formEl || !msgInput || submitBtn.disabled) return;
        if (msgInput.value.trim() === '' && (!fileInput.files || fileInput.files.length === 0)) {
            return;
        }

        const formData = new FormData(formEl);
        submitBtn.disabled = true;

        $.ajax({
            url: "<?= base_url('chat/send-message') ?>",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(res) {
                submitBtn.disabled = false;
                if (res.status === 'success') {
                    msgInput.value = '';
                    msgInput.style.height = 'auto';
                    fileInput.value = '';
                    document.getElementById('file_preview_badge').classList.add('d-none');
                    cancelReply();
                    loadMessages(false);
                    loadRooms(); // update preview last message
                    msgInput.focus();
                }
            },
            error: function(xhr) {
                submitBtn.disabled = false;
                alert(xhr.responseJSON?.message || 'Gagal mengirim pesan.');
            }
        });
    }

    document.getElementById('form_send_message')?.addEventListener('submit', function(e) {
        e.preventDefault();
        sendChatMessage();
    });

    // Enter = Kirim Pesan | Shift + Enter = Baris Baru (Persis WhatsApp)
    const chatTextarea = document.getElementById('input_chat_message');
    if (chatTextarea) {
        chatTextarea.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendChatMessage();
            }
        });
        chatTextarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 96) + 'px';
        });
    }

    // File input preview
    document.getElementById('input_chat_file')?.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            document.getElementById('file_preview_name').innerText = this.files[0].name;
            document.getElementById('file_preview_badge').classList.remove('d-none');
        }
    });
    document.getElementById('btn_remove_file')?.addEventListener('click', function() {
        document.getElementById('input_chat_file').value = '';
        document.getElementById('file_preview_badge').classList.add('d-none');
    });

    // Forward to WhatsApp
    function forwardToWa(text) {
        if (!text) return;
        const url = "https://api.whatsapp.com/send?text=" + encodeURIComponent(text);
        window.open(url, '_blank');
    }

    function copyText(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Teks berhasil disalin ke clipboard.');
        });
    }

    // Edit Modal & Submit
    function openEditModal(msgId, text) {
        document.getElementById('edit_message_id').value = msgId;
        document.getElementById('edit_message_text').value = text;
        const modalEl = document.getElementById('modal_edit_message');
        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        modal.show();
        setTimeout(() => document.getElementById('edit_message_text')?.focus(), 200);
    }
    document.getElementById('form_edit_message')?.addEventListener('submit', function(e) {
        e.preventDefault();
        $.post("<?= base_url('chat/edit-message') ?>", $(this).serialize(), function(res) {
            if (res.status === 'success') {
                const modalEl = document.getElementById('modal_edit_message');
                bootstrap.Modal.getInstance(modalEl)?.hide();
                loadMessages(false);
                loadRooms();
            } else {
                alert(res.message || 'Gagal mengedit pesan.');
            }
        }, 'json').fail(xhr => alert(xhr.responseJSON?.message || 'Gagal mengedit pesan.'));
    });

    // Delete / Tarik Message
    function deleteMessage(msgId) {
        if (!confirm('Tarik pesan ini untuk semua orang?')) return;
        $.post("<?= base_url('chat/delete-message') ?>", { 
            message_id: msgId,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }, function(res) {
            if (res.status === 'success') {
                loadMessages(false);
                loadRooms();
            } else {
                alert(res.message || 'Gagal menarik pesan.');
            }
        }, 'json').fail(xhr => alert(xhr.responseJSON?.message || 'Gagal menarik pesan.'));
    }

    // Contacts Modal Search & Role Filter
    let activeContactRoleFilter = 'all';

    $('#modal_new_chat').on('shown.bs.modal', function() {
        loadContacts(document.getElementById('search_contact_input')?.value || '', activeContactRoleFilter);
        document.getElementById('search_contact_input').focus();
    });
    document.getElementById('search_contact_input')?.addEventListener('input', function() {
        loadContacts(this.value, activeContactRoleFilter);
    });

    document.querySelectorAll('.contact-role-tab').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.contact-role-tab').forEach(b => {
                b.classList.remove('btn-primary');
                b.classList.add('btn-light');
            });
            this.classList.remove('btn-light');
            this.classList.add('btn-primary');
            activeContactRoleFilter = this.getAttribute('data-role') || 'all';
            loadContacts(document.getElementById('search_contact_input')?.value || '', activeContactRoleFilter);
        });
    });

    function loadContacts(q, roleFilter = 'all') {
        const url = "<?= base_url('chat/contacts') ?>?q=" + encodeURIComponent(q) + "&role_filter=" + encodeURIComponent(roleFilter);
        $.getJSON(url, function(contacts) {
            const box = document.getElementById('contact_list_container');
            if (contacts.length === 0) {
                box.innerHTML = '<div class="text-center py-6 text-muted fs-8">Kontak tidak ditemukan.</div>';
                return;
            }
            let html = '';
            contacts.forEach(c => {
                html += `
                    <div class="d-flex align-items-center justify-content-between p-2 rounded hover-bg-light cursor-pointer mb-1 border-bottom" onclick="openDirect('${c.role}', ${c.id})">
                        <div class="d-flex align-items-center gap-3">
                            <img src="${c.avatar || DEFAULT_AVATAR}" onerror="this.onerror=null;this.src='${DEFAULT_AVATAR}'" class="w-35px h-35px rounded-circle object-fit-cover" />
                            <div>
                                <strong class="fs-7 text-gray-900 d-block">${escapeHtml(c.name)}</strong>
                                <span class="fs-9 text-muted">${escapeHtml(c.subtitle)}</span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-xs btn-light-primary fw-bold btn-chat-contact" onclick="event.stopPropagation(); openDirect('${c.role}', ${c.id}, this)">Chat</button>
                    </div>
                `;
            });
            box.innerHTML = html;
        });
    }

    function openDirect(role, id, btnElem) {
        let originalHtml = '';
        if (btnElem) {
            originalHtml = btnElem.innerHTML;
            btnElem.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>...';
            btnElem.disabled = true;
        }

        const payload = {
            target_role: role,
            target_id: id,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        };

        $.post("<?= base_url('chat/open-direct') ?>", payload, function(res) {
            if (res.status === 'success') {
                const modalElem = document.getElementById('modal_new_chat');
                const modalInstance = bootstrap.Modal.getInstance(modalElem);
                if (modalInstance) {
                    modalInstance.hide();
                } else {
                    $('#modal_new_chat').modal('hide');
                }
                loadRooms();
                selectRoom(res.room_id);
            } else {
                alert(res.message || 'Gagal membuka percakapan.');
            }
        }, 'json')
        .fail(function(xhr) {
            console.error("Open direct failed:", xhr);
            alert(xhr.responseJSON?.message || 'Gagal memulai chat. Silakan coba kembali.');
        })
        .always(function() {
            if (btnElem) {
                btnElem.innerHTML = originalHtml;
                btnElem.disabled = false;
            }
        });
    }

    // Create Group Modal
    function loadGroupMemberCandidates(q = '') {
        $.getJSON("<?= base_url('chat/contacts') ?>?q=" + encodeURIComponent(q), function(contacts) {
            const box = document.getElementById('group_members_pick_list');
            let html = '';
            contacts.forEach(c => {
                html += `
                    <div class="form-check d-flex align-items-center justify-content-between py-1 border-bottom">
                        <label class="form-check-label d-flex align-items-center gap-2 cursor-pointer w-100" for="gm_${c.role}_${c.id}">
                            <img src="${c.avatar || DEFAULT_AVATAR}" onerror="this.onerror=null;this.src='${DEFAULT_AVATAR}'" class="w-25px h-25px rounded-circle object-fit-cover" />
                            <span class="fs-8 text-gray-800">${escapeHtml(c.name)}</span>
                        </label>
                        <input class="form-check-input group-member-checkbox" type="checkbox" value='{"role":"${c.role}","id":${c.id}}' id="gm_${c.role}_${c.id}" />
                    </div>
                `;
            });
            box.innerHTML = html || '<div class="text-center text-muted py-3 fs-8">Kontak tidak ditemukan.</div>';
        });
    }

    $('#modal_create_group').on('shown.bs.modal', function() {
        loadGroupMemberCandidates(document.getElementById('search_group_members_input')?.value || '');
    });

    document.getElementById('search_group_members_input')?.addEventListener('input', function() {
        loadGroupMemberCandidates(this.value);
    });

    document.getElementById('form_create_group')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('btn_submit_group');
        const name = document.getElementById('group_name_input').value.trim();
        if (!name) return;

        const selected = [];
        document.querySelectorAll('.group-member-checkbox:checked').forEach(cb => {
            selected.push(JSON.parse(cb.value));
        });

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Membuat...';
        }

        $.post("<?= base_url('chat/create-group') ?>", {
            name: name,
            members: JSON.stringify(selected),
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }, function(res) {
            if (res.status === 'success') {
                const modalElem = document.getElementById('modal_create_group');
                const modalInstance = bootstrap.Modal.getInstance(modalElem);
                if (modalInstance) {
                    modalInstance.hide();
                } else {
                    $('#modal_create_group').modal('hide');
                }
                document.getElementById('group_name_input').value = '';
                loadRooms();
                selectRoom(res.room_id);
            } else {
                alert(res.message || 'Gagal membuat grup.');
            }
        }, 'json')
        .fail(xhr => alert(xhr.responseJSON?.message || 'Gagal membuat grup.'))
        .always(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Buat Grup';
            }
        });
    });

    // Group & Room Info Details
    document.getElementById('btn_room_info')?.addEventListener('click', function() {
        if (!activeRoomId) return;
        $.getJSON("<?= base_url('chat/room-details/') ?>" + activeRoomId + "?mode=" + adminFilterMode, function(res) {
            if (res.status === 'success') {
                document.getElementById('info_group_title').innerText = (res.room.type === 'group' ? 'Detail Grup: ' : 'Detail Partisipan: ') + (res.room.name || '');
                document.getElementById('info_group_count').innerText = res.members.length;

                const isMonitoring = !!res.room.is_monitoring || (IS_ADMIN && adminFilterMode === 'all');
                const btnOpenAdd = document.getElementById('btn_open_add_member');
                const addSec = document.getElementById('add_member_section');
                if (res.room.type === 'group' && !isMonitoring) {
                    btnOpenAdd?.classList.remove('d-none');
                } else {
                    btnOpenAdd?.classList.add('d-none');
                    addSec?.classList.add('d-none');
                }

                let html = '';
                res.members.forEach(m => {
                    const adminBadge = (res.room.type === 'group' && m.role_in_room === 'admin') ? '<span class="badge badge-light-primary fs-9">Pembuat Grup</span>' : '';
                    const msgCountBadge = `<span class="badge badge-light-info fs-9">${Number(m.message_count || 0)} pesan</span>`;
                    const emailText = m.user_email ? `<div class="fs-9 text-muted">${escapeHtml(m.user_email)}</div>` : '';
                    html += `
                        <div class="d-flex align-items-center justify-content-between gap-3 p-2 border-bottom">
                            <div class="d-flex align-items-center gap-3 overflow-hidden">
                                <img src="${m.user_avatar || DEFAULT_AVATAR}" onerror="this.onerror=null;this.src='${DEFAULT_AVATAR}'" class="w-40px h-40px rounded-circle object-fit-cover border flex-shrink-0" />
                                <div class="overflow-hidden">
                                    <strong class="fs-7 text-gray-900 d-block text-truncate">${escapeHtml(m.user_name)}</strong>
                                    <span class="badge badge-light-secondary text-gray-700 fs-10 px-2 py-0">${escapeHtml(m.role_label || m.user_role)}</span>
                                    ${emailText}
                                </div>
                            </div>
                            <div class="d-flex flex-column align-items-end gap-1 flex-shrink-0">
                                ${msgCountBadge}
                                ${adminBadge}
                            </div>
                        </div>
                    `;
                });
                document.getElementById('info_group_members_list').innerHTML = html;
                const modalEl = document.getElementById('modal_group_info');
                const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                modal.show();
            }
        });
    });

    // Tambah anggota ke grup dari detail
    document.getElementById('btn_open_add_member')?.addEventListener('click', function() {
        const sec = document.getElementById('add_member_section');
        sec.classList.toggle('d-none');
        if (!sec.classList.contains('d-none')) {
            $.getJSON("<?= base_url('chat/contacts') ?>", function(contacts) {
                const box = document.getElementById('add_member_candidates_list');
                let html = '';
                contacts.forEach(c => {
                    html += `
                        <div class="d-flex align-items-center justify-content-between py-1 border-bottom">
                            <span class="fs-8 text-gray-800">${escapeHtml(c.name)}</span>
                            <button type="button" class="btn btn-xs btn-primary py-0" onclick="addMemberToCurrentRoom('${c.role}', ${c.id})">Tambah</button>
                        </div>
                    `;
                });
                box.innerHTML = html;
            });
        }
    });

    function addMemberToCurrentRoom(role, id) {
        if (!activeRoomId) return;
        $.post("<?= base_url('chat/add-member') ?>", {
            room_id: activeRoomId,
            target_role: role,
            target_id: id,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }, function(res) {
            if (res.status === 'success') {
                alert(res.message);
                bootstrap.Modal.getInstance(document.getElementById('modal_group_info')).hide();
                loadMessages(false);
            } else {
                alert(res.message || 'Gagal menambahkan anggota.');
            }
        }, 'json').fail(xhr => alert(xhr.responseJSON?.message || 'Gagal menambahkan anggota.'));
    }

    // Helper Escapes
    function escapeHtml(text) {
        if (!text) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
    function escapeJs(text) {
        if (!text) return '';
        return text.replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '\\"').replace(/\n/g, ' ');
    }

    // Initial Load
    $(document).ready(function() {
        const loader = document.getElementById('global-loader');
        if (loader) {
            loader.classList.add('loader-hidden');
            document.body.classList.remove('page-loading');
            setTimeout(() => loader.remove(), 400);
        }
        adjustChatHeight();
        setTimeout(adjustChatHeight, 250);
        loadRooms();
        // Polling berkala daftar room & pesan belum dibaca
        setInterval(() => {
            const q = document.getElementById('search_room_input')?.value || '';
            loadRooms(q);
        }, 4000);
    });
</script>
<?= $this->endSection(); ?>

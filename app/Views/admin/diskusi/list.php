<?= $this->extend('template/app'); ?>
<?= $this->section('styles'); ?>
<style>
    :root {
        --chat-bg: #F9F6F2;
        --chat-border: #E8E2D9;
        --chat-active: #E8DED3;
        --primary-color: #0086a7;
    }

    .chat-container {
        background-color: var(--chat-bg);
        border: 1px solid var(--chat-border);
        border-radius: 12px;
        display: flex;
        height: 700px;
        margin-bottom: 20px;
    }

    .chat-sidebar {
        width: 350px;
        border-right: 1px solid var(--chat-border);
        background: #fff;
        display: flex;
        flex-direction: column;
    }

    .chat-main {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        background: #fff;
        position: relative;
    }

    .chat-history {
        flex-grow: 1;
        padding: 20px;
        overflow-y: auto;
        background-color: var(--chat-bg);
    }

    /* Accordion & Sidebar Item Styling */
    .btn-link,
    .btn-link:hover,
    .btn-link:focus {
        text-decoration: none;
        color: inherit;
    }

    .chat-item {
        cursor: pointer;
        transition: all 0.2s ease;
        border-left: 4px solid transparent;
        border-bottom: 1px solid #f1f1f1;
    }

    .chat-item:hover {
        background-color: #f0f7f9 !important;
        border-left-color: var(--primary-color) !important;
    }

    .chat-item.active {
        background-color: var(--chat-active) !important;
        border-left-color: var(--primary-color) !important;
    }

    /* Dasar Bubble */
    .bubble-me,
    .bubble-them {
        position: relative;
        padding: 8px 12px;
        min-width: 140px;
        max-width: 75%;
        display: flex;
        flex-direction: column;
    }

    /* Warna & Bentuk Bubble Kanan (Me) */
    .bubble-me {
        background-color: #1e2023;
        /* Warna gelap sesuai gambar */
        color: #ffffff;
        border-radius: 12px 12px 0 12px;
        /* Ekor kanan bawah */
    }

    /* Warna & Bentuk Bubble Kiri (Them) */
    .bubble-them {
        background-color: #29459A;
        /* Abu-abu gelap */
        color: #ffffff;
        border-radius: 12px 12px 12px 0;
        /* Ekor kiri bawah */
    }

    /* Gaya Nama di dalam Bubble */
    .chat-name {
        font-weight: bold;
        font-size: 0.8rem;
        margin-bottom: 2px;
    }

    .text-info-custom {
        color: #00d1ff !important;
    }

    .text-guru-custom {
        color: #ffcc00 !important;
    }

    /* Gaya Teks Pesan */
    .chat-message-text {
        font-size: 0.9rem;
        margin-bottom: 12px;
        /* Memberi ruang untuk waktu agar tidak tumpang tindih */
        word-wrap: break-word;
    }

    /* Gaya Waktu (Pojok Kanan Bawah) */
    .chat-time {
        font-size: 0.65rem;
        color: rgba(255, 255, 255, 0.5);
        position: absolute;
        bottom: 4px;
        right: 10px;
    }

    .avatar-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
    }

    /* Animation */
    .btn-link[aria-expanded="true"] i.fa-chevron-down {
        transform: rotate(180deg);
        transition: 0.3s;
    }

    /* Struktur dasar container bubble */
    .chat-bubble-container {
        display: flex;
        align-items: flex-end;
        /* Avatar rata bawah */
        margin-bottom: 1rem;
        max-width: 80%;
        /* Batasi lebar total chat agar tidak penuh */
    }


    /* Penanganan Elemen Di Dalam Bubble */
    .chat-name {
        font-weight: bold;
        color: #0086a7;
        /* Warna primary Anda untuk Nama */
        font-size: 0.8rem;
        margin-bottom: 4px;
        /* Spasi ke teks pesan */
    }

    .chat-message-text {
        font-size: 0.9rem;
        line-height: 1.4;
        margin-right: 25px;
        /* Spasi agar teks tidak menimpa waktu */
    }

    /* Waktu di Pojok Kanan Bawah Bubble (Floating) */
    .chat-time {
        font-size: 0.7rem;
        color: rgba(255, 255, 255, 0.6);
        /* Putih transparan */
        position: absolute;
        bottom: 5px;
        right: 8px;
    }

    /* Avatar Samping Bubble */
    .avatar-sm {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="container-fluid mt-4">
    <div class="chat-container shadow-sm">
        <div class="chat-sidebar">
            <div class="p-3 d-flex justify-content-between align-items-center border-bottom">
                <h4 class="font-weight-bold m-0">Chat</h4>
                <button class="btn btn-primary btn-sm px-3 shadow-sm" data-toggle="modal" data-target="#modal_materi" style="border-radius: 20px;">
                    <i class="bi bi-chat-plus-fill mr-1"></i> Chat Baru
                </button>
            </div>

            <div class="p-3 border-bottom bg-light">
                <input type="text" class="form-control" placeholder="Cari diskusi..." id="searchChat">
            </div>

            <div class="overflow-auto" id="chat_list">
                <div class="accordion" id="accordionGuru">
                    <?php foreach ($diskusi as $index => $group): ?>
                        <?php
                        if (empty($group['materi'])) continue;

                        // HITUNG TOTAL UNREAD UNTUK GURU INI
                        $totalUnreadGuru = 0;
                        foreach ($group['materi'] as $m) {
                            $totalUnreadGuru += $m['unread_count'];
                        }
                        ?>

                        <div class="card border-0 border-bottom">
                            <div class="card-header bg-white p-0">
                                <button class="btn btn-link btn-block text-left text-dark font-weight-bold d-flex align-items-center p-3 shadow-none"
                                    type="button" data-toggle="collapse" data-target="#collapse<?= $group['id_guru'] ?>">

                                    <div class="position-relative mr-3">
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($group['nama_guru']) ?>&background=E8DED3&color=333" class="avatar-sm">

                                        <?php if ($totalUnreadGuru > 0): ?>
                                            <span class="badge badge-danger badge-pill position-absolute"
                                                style="top: -5px; right: -5px; font-size: 0.6rem; border: 2px solid white;">
                                                <?= ($totalUnreadGuru > 99) ? '99+' : $totalUnreadGuru ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <span class="flex-grow-1"><?= $group['nama_guru'] ?></span>
                                    <i class="fas fa-chevron-down small text-muted"></i>
                                </button>
                            </div>

                            <div id="collapse<?= $group['id_guru'] ?>" class="collapse" data-parent="#accordionGuru">
                                <div class="card-body p-0 border-0" style="background-color: #fcfcfc;">
                                    <?php foreach ($group['materi'] as $m): ?>
                                        <div class="chat-item d-flex align-items-center py-3 px-4"
                                            data-materi="<?= $m['materi'] ?>"
                                            data-namamateri="<?= $m['nama_materi'] ?>"
                                            data-emailguru="<?= $group['email_guru'] ?>"
                                            data-namaguru="<?= $group['nama_guru'] ?>">

                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex flex-column">
                                                        <span class="text-dark font-weight-bold mb-0" style="font-size: 0.85rem;">
                                                            <?= $m['nama_materi'] ?>
                                                        </span>
                                                        <small class="text-muted" style="font-size: 0.7rem;">Klik untuk diskusi</small>
                                                    </div>

                                                    <?php if ($m['unread_count'] > 0): ?>
                                                        <span class="badge badge-primary badge-pill unread-badge" data-materi="<?= $m['materi'] ?>">
                                                            <?= ($m['unread_count'] > 99) ? '99+' : $m['unread_count'] ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
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

        <div class="chat-main">
            <div id="empty_state" class="text-center my-auto">
                <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                <p class="text-muted">Pilih diskusi di sebelah kiri untuk memulai</p>
            </div>

            <div id="chat_area" style="display:none;" class="h-100 flex-column">
                <div class="p-3 border-bottom bg-white d-flex align-items-center shadow-sm">
                    <h5 class="m-0 font-weight-bold" id="active_title">Nama Materi</h5>
                </div>

                <div class="chat-history" id="chat_history"></div>

                <div class="p-3 border-top bg-white">
                    <div class="input-group">
                        <input type="text" id="msg_input" class="form-control" placeholder="Tulis pesan...">
                        <div class="input-group-append">
                            <button class="btn btn-primary px-4" id="btn_send">
                                <i class="fas fa-paper-plane mr-1"></i> Kirim
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_materi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Mulai Diskusi Baru</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body overflow-auto" style="max-height: 400px;">
                <?php foreach ($materi as $m): ?>
                    <div class="materi-item border p-3 mb-2 rounded shadow-sm" style="cursor:pointer;"
                        onclick="startNewChat('<?= $m['kode_materi'] ?>', '<?= $m['nama_materi'] ?>', '<?= $group['email_guru'] ?>', '<?= $group['nama_guru'] ?>')">
                        <div class="font-weight-bold text-primary"><?= $m['nama_materi'] ?></div>
                        <small class="text-muted">Guru: <?= $m['nama_guru'] ?? 'Pengajar' ?></small>
                    </div>
                <?php endforeach; ?>
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

    $(document).ready(function() {
        // Klik Sidebar Materi
        $(document).on('click', '.chat-item', function() {
            const materi = $(this).data('materi');
            const namaMateri = $(this).data('namamateri');
            currentEmailGuru = $(this).data('emailguru'); // Tangkap email guru dari sidebar
            currentNamaGuru = $(this).data('namaguru'); // Tangkap nama guru dari sidebar

            $('.chat-item').removeClass('active');
            $(this).addClass('active');

            loadChat(materi, namaMateri);
        });

        $('#btn_send').on('click', sendMessage);
        $('#msg_input').on('keypress', function(e) {
            if (e.which == 13) sendMessage();
        });
    });

    function startNewChat(kodeMateri, namaMateri, emailGuru, nama_guru) {
        $('#modal_materi').modal('hide');
        currentEmailGuru = emailGuru; // Set email guru dari modal
        currentNamaGuru = nama_guru; // Set nama guru dari modal
        loadChat(kodeMateri, namaMateri);
    }

    function loadChat(materi, namaMateri) {
        if (currentMateri === materi && $('#chat_area').is(':visible')) return;

        currentMateri = materi;
        lastDisplayedId = 0;

        $('#empty_state').hide();
        $('#chat_area').attr('style', 'display: flex !important;');
        $('#active_title').text(namaMateri);
        $('#chat_history').html('');

        fetchMessages(materi);

        if (chatInterval) clearInterval(chatInterval);
        chatInterval = setInterval(() => {
            if (currentMateri) fetchMessages(currentMateri);
        }, 3000);
    }

    function fetchMessages(materi) {
        $.get(`<?= base_url('sw-admin/diskusi/get-messages') ?>/${encodeURIComponent(materi)}`, {
                last_id: lastDisplayedId
            },
            function(response) {
                const data = response.messages;
                if (data && data.length > 0) {
                    let html = '';
                    let hasNewMessage = false; // Flag untuk mendeteksi pesan baru benar-benar masuk

                    data.forEach(m => {
                        const msgId = parseInt(m.id_chat_materi);

                        if (msgId > lastDisplayedId) {
                            lastDisplayedId = msgId;
                            hasNewMessage = true; // Tandai ada pesan yang baru di-render

                            const isMe = (m.email === currentEmailGuru);
                            const time = new Date(m.date_created * 1000).toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit'
                            });

                            if (isMe) {
                                // TAMPILAN SISWA/SAYA (KANAN)
                                html += `
                                <div class="d-flex flex-row-reverse align-items-end mb-4">
                                    <div class="ml-2">
                                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(m.nama)}&background=0086a7&color=fff" class="avatar-circle shadow-sm">
                                    </div>
                                    
                                    <div class="bubble-me shadow-sm">
                                        <div class="chat-name text-info-custom">${m.nama}</div>
                                        <div class="chat-message-text">${urlify(m.text)}</div>
                                        <div class="chat-time">${time}</div>
                                    </div>
                                </div>`;
                            } else {
                                // TAMPILAN GURU/ORANG LAIN (KIRI)
                                html += `
                                <div class="d-flex align-items-end mb-4">
                                    <div class="mr-2">
                                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(m.nama)}&background=E8DED3&color=333" class="avatar-circle shadow-sm">
                                    </div>
                                    
                                    <div class="bubble-them shadow-sm">
                                        <div class="chat-name text-guru-custom">${m.nama}</div>
                                        <div class="chat-message-text">${urlify(m.text)}</div>
                                        <div class="chat-time">${time}</div>
                                    </div>
                                </div>`;
                            }
                        }
                    });

                    if (hasNewMessage) {
                        $('#chat_history').append(html);

                        // HANYA SCROLL JIKA:
                        // 1. Pesan baru masuk
                        // 2. User sedang berada di area bawah chat (opsional, tapi defaultnya kita scroll jika ada pesan baru)
                        $('#chat_history').animate({
                            scrollTop: $('#chat_history')[0].scrollHeight
                        }, 300);
                    }
                }
            });
    }

    function sendMessage() {
        const text = $('#msg_input').val();
        if (!text || !currentMateri) return;

        $('#msg_input').val('').prop('disabled', true);

        $.post(`<?= base_url('sw-admin/diskusi/send') ?>`, {
            materi: currentMateri,
            text: text,
            email_guru: currentEmailGuru, // KIRIM EMAIL GURU KE BACKEND
            nama_guru: currentNamaGuru // KIRIM NAMA GURU KE BACKEND
        }, function() {
            $('#msg_input').prop('disabled', false).focus();
            fetchMessages(currentMateri);
        });
    }

    function urlify(text) {
        if (!text) return "";
        var urlRegex = /(https?:\/\/[^\s]+)/g;
        return text.replace(urlRegex, url => `<a href="${url}" target="_blank" class="text-white border-bottom">${url}</a>`);
    }
</script>
<?= $this->endSection(); ?>
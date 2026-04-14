<?= $this->extend('siswa/template/app'); ?>

<?= $this->section('styles'); ?>
<style>
    :root {
        --chat-bg: #F9F6F2;
        --chat-border: #E8E2D9;
        --chat-active: #E8DED3;
    }

    .chat-container {
        background-color: var(--chat-bg);
        border: 1px solid var(--chat-border);
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        height: 700px;
    }

    .chat-sidebar {
        width: 350px;
        border-right: 1px solid var(--chat-border);
        background: #fff;
        display: flex;
        flex-direction: column;
    }

    .chat-item {
        cursor: pointer;
        padding: 15px;
        border-bottom: 1px solid #f1f1f1;
        transition: 0.2s;
    }

    .chat-item.active {
        background-color: var(--chat-active);
    }

    .chat-main {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        background: #fff;
    }

    .chat-history {
        flex-grow: 1;
        padding: 20px;
        overflow-y: auto;
        background-color: var(--chat-bg);
    }

    .bubble-me {
        background-color: #0086a7;
        color: white;
        border-radius: 15px 15px 0 15px;
        padding: 12px;
        max-width: 70%;
        margin-left: auto;
        shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .bubble-them {
        background-color: #EEEAE4;
        color: #333;
        border-radius: 15px 15px 15px 0;
        padding: 12px;
        max-width: 70%;
        shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .materi-item {
        cursor: pointer;
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 10px;
        transition: 0.2s;
    }

    .materi-item:hover {
        background: #f8f9fa;
        border-color: #0086a7;
    }

    .bubble-me {
        background-color: #0086a7;
        /* Warna Biru Cyan */
        color: white;
        border-radius: 15px 15px 0 15px;
        padding: 12px 18px;
        max-width: 70%;
        margin-left: auto;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        line-height: 1.5;
    }

    /* Agar link di dalam bubble biru tetap terlihat jelas (warna putih) */
    .bubble-me a {
        color: #fff !important;
        text-decoration: underline;
        font-weight: 600;
    }

    .bubble-them {
        background-color: #EEEAE4;
        /* Warna Abu-abu Cream */
        color: #333;
        border-radius: 15px 15px 15px 0;
        padding: 12px 18px;
        max-width: 70%;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        line-height: 1.5;
    }

    /* Link di bubble abu-abu menggunakan warna biru */
    .bubble-them a {
        color: #0086a7 !important;
        text-decoration: underline;
    }
</style>

<?= $this->endSection(); ?>
<?= $this->section('content'); ?>

<div class="container-xxl mt-2">
    <div class="chat-container">
        <div class="chat-sidebar">
            <div class="p-5 d-flex justify-content-between align-items-center">
                <h3 class="fw-bold m-0">Chat</h3>
                <button class="btn btn-sm btn-icon btn-light-primary" data-bs-toggle="modal" data-bs-target="#modal_materi">
                    <i class="ki-outline ki-plus fs-2"></i>
                </button>
            </div>
            <div class="px-5 mb-4">
                <input type="text" class="form-control form-control-solid" placeholder="Cari diskusi..." id="searchChat">
            </div>
            <?php foreach ($diskusi as $d): ?>
                <div class="chat-item d-flex align-items-center" data-materi="<?= $d['materi'] ?>" data-namaMateri="<?= $d['nama_materi'] ?>">
                    <div class="symbol symbol-45px symbol-circle me-4">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($d['nama_materi']) ?>&background=random" alt="">
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="fw-bold fs-6 text-dark"><?= $d['nama_materi'] ?></div>

                            <?php if ($d['unread_count'] > 0): ?>
                                <span class="badge badge-circle badge-primary unread-badge" data-materi="<?= $d['materi'] ?>">
                                    <?= ($d['unread_count'] > 99) ? '99+' : $d['unread_count'] ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="chat-main">
            <div id="empty_state" class="d-flex flex-column align-items-center justify-content-center h-100">
                <i class="ki-outline ki-messages fs-5x text-muted mb-5"></i>
                <p class="text-muted">Pilih diskusi di sebelah kiri untuk memulai</p>
            </div>

            <div id="chat_area" style="display:none;" class="h-100 flex-column">
                <div class="p-5 border-bottom d-flex align-items-center">
                    <div class="fw-bold fs-5" id="active_title">Nama Materi</div>
                </div>

                <div class="chat-history" id="chat_history"></div>

                <div class="p-5 border-top bg-white">
                    <div class="d-flex gap-3">
                        <input type="text" id="msg_input" class="form-control" placeholder="Tulis pesan...">
                        <button class="btn btn-primary" id="btn_send">Kirim</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="modal_materi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-450px">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="fw-bold">Pilih Materi</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body scroll-y mh-300px">
                <?php foreach ($materi as $m): ?>
                    <div class="materi-item" onclick="startNewChat('<?= $m['kode_materi'] ?>', '<?= $m['nama_materi'] ?>')">
                        <div class="fw-bold"><?= $m['nama_materi'] ?></div>
                        <div class="text-muted fs-7">Klik untuk buka diskusi</div>
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
    let lastDisplayedId = 0; // Menyimpan ID pesan terakhir yang tampil
    let chatInterval = null; // Untuk menghentikan interval saat ganti materi

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.chat-item', function() {
            const materi = $(this).data('materi');
            const namaMateri = $(this).data('namamateri');
            loadChat(materi, namaMateri);
        });

        $('#btn_send').on('click', function() {
            sendMessage();
        });
        $('#msg_input').on('keypress', function(e) {
            if (e.which == 13) sendMessage();
        });
    });

    function startNewChat(kodeMateri, namaMateri) {
        // 1. Tutup modal materi
        $('#modal_materi').modal('hide');

        // 2. Bersihkan interval lama jika ada (agar tidak terjadi tabrakan request)
        if (chatInterval) {
            clearInterval(chatInterval);
        }

        // 3. Reset variabel global
        currentMateri = kodeMateri;
        lastDisplayedId = 0; // Mulai dari awal untuk materi baru

        // 4. Update UI Header
        $('#active_title').text(namaMateri);
        $('#active_img').attr('src', `https://ui-avatars.com/api/?name=${encodeURIComponent(namaMateri)}&background=random`);

        // 5. Transisi tampilan (Sembunyikan empty state, tampilkan chat area)
        $('#empty_state').attr('style', 'display: none !important;');
        $('#chat_area').attr('style', 'display: flex !important;');

        // 6. Bersihkan history chat lama di layar
        $('#chat_history').html('');

        // 7. Ambil pesan awal (jika sebelumnya sudah pernah ada chat di materi ini)
        fetchMessages(kodeMateri);

        // 8. Aktifkan Real-time Monitoring untuk materi baru ini
        chatInterval = setInterval(function() {
            if (currentMateri) {
                fetchMessages(currentMateri);
            }
        }, 3000);
    }

    function loadChat(materi, namaMateri) {
        if (currentMateri === materi) return; // Jangan reload jika klik materi yang sama

        currentMateri = materi;
        lastDisplayedId = 0; // Reset ID saat pindah materi

        // UI Transitions
        $('.chat-item').removeClass('active');
        $(`.chat-item[data-materi="${materi}"]`).addClass('active');
        $('#empty_state').attr('style', 'display: none !important;');
        $('#chat_area').attr('style', 'display: flex !important;');
        $('#active_title').text(namaMateri);
        $('#chat_history').html(''); // Bersihkan chat lama

        // Ambil data awal
        fetchMessages(materi);

        // Set interval untuk cek pesan baru setiap 3 detik
        if (chatInterval) clearInterval(chatInterval);
        chatInterval = setInterval(function() {
            if (currentMateri) fetchMessages(currentMateri);
        }, 3000);
    }

    function fetchMessages(materi) {
        $.ajax({
            url: `<?= base_url('sw-siswa/diskusi/get-messages') ?>/${encodeURIComponent(materi)}`,
            type: 'GET',
            data: {
                last_id: lastDisplayedId
            },
            success: function(response) {
                const data = response.messages;
                const unreadId = response.first_unread_id;

                if (data.length > 0) {
                    let html = '';
                    data.forEach(m => {
                        if (parseInt(m.id_chat_materi) > lastDisplayedId) {
                            lastDisplayedId = parseInt(m.id_chat_materi);
                        }

                        const isMe = (m.email === '<?= session()->get('email') ?>');
                        const processedText = urlify(m.text);

                        // Tambahkan ID pada elemen HTML untuk referensi scroll
                        const msgId = `msg-${m.id_chat_materi}`;

                        if (isMe) {
                            html += `<div id="${msgId}" class="mb-4 text-end"><div class="bubble-me text-start d-inline-block">${processedText}</div></div>`;
                        } else {
                            html += `
                            <div id="${msgId}" class="d-flex mb-4">
                                <div class="symbol symbol-35px symbol-circle me-3"><img src="https://ui-avatars.com/api/?name=${encodeURIComponent(m.nama)}"></div>
                                <div class="bubble-them">${processedText}</div>
                            </div>`;
                        }
                    });

                    $('#chat_history').append(html);

                    // LOGIKA SCROLL
                    if (unreadId && lastDisplayedId == data[data.length - 1].id_chat_materi) {
                        // Jika ada pesan belum terbaca, scroll ke pesan tersebut
                        const targetElement = document.getElementById(`msg-${unreadId}`);
                        if (targetElement) {
                            targetElement.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    } else {
                        // Jika tidak ada pesan baru, scroll ke paling bawah seperti biasa
                        $('#chat_history').scrollTop($('#chat_history')[0].scrollHeight);
                    }
                }
            }
        });
    }

    function sendMessage() {
        const text = $('#msg_input').val();
        if (!text || !currentMateri) return;

        // Nonaktifkan input sementara saat mengirim
        $('#msg_input').val('').prop('disabled', true);

        $.post(`<?= base_url('sw-siswa/diskusi/send') ?>`, {
            materi: currentMateri,
            text: text
        }, function() {
            $('#msg_input').prop('disabled', false).focus();
            fetchMessages(currentMateri); // Langsung cek pesan baru
        });
    }

    function urlify(text) {
        if (!text) return "";
        var urlRegex = /(https?:\/\/[^\s]+)/g;
        return text.replace(urlRegex, function(url) {
            return '<a href="' + url + '" target="_blank" rel="noopener noreferrer" style="color: inherit; text-decoration: underline;">' + url + '</a>';
        });
    }
</script>

<?= $this->endSection(); ?>
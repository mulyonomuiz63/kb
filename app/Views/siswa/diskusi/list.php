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
        position: relative;
    }

    .chat-history {
        flex-grow: 1;
        padding: 20px;
        overflow-y: auto;
        background-color: var(--chat-bg);
    }

    .bubble-me {
        background-color: #9da3ac;
        color: #fff;
        border-radius: 8px 8px 0 8px;
        padding: 8px 10px;
        position: relative;
        min-width: 80px;
        max-width: 80%;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    .bubble-them {
        background-color: #2e3136;
        color: #fff;
        border-radius: 8px 8px 8px 0;
        padding: 8px 10px;
        position: relative;
        min-width: 80px;
        max-width: 80%;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    /* FIX AGAR BADGE MENTION TIDAK KELUAR / OVERFLOW DARI BUBBLE */
    .bubble-me .badge, .bubble-them .badge {
        display: inline-block;
        max-width: 100%;
        white-space: normal !important;
        word-break: break-word;
        text-align: left;
        height: auto;
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

    /* Link di dalam bubble */
    .bubble-me a {
        color: #fff !important;
        text-decoration: underline;
        font-weight: 600;
    }

    .bubble-them a {
        color: #00d1ff !important;
        text-decoration: underline;
    }

    .justify-content-end {
        justify-content: flex-end;
    }

    .chat-bubble-container {
        display: flex;
        align-items: flex-end;
    }

    .chat-name {
        font-weight: bold;
        font-size: 0.8rem;
        margin-bottom: 2px;
        color: #00d1ff;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    .chat-message-text {
        font-size: 0.9rem;
        margin-bottom: 10px;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    .chat-time {
        font-size: 0.65rem;
        color: rgba(255, 255, 255, 0.5);
        position: absolute;
        bottom: 4px;
        right: 8px;
    }

    /* Styling Popup Dropdown Mention Tag */
    .mention-dropdown {
        position: absolute;
        bottom: 100%;
        left: 20px;
        z-index: 1050;
        width: 280px;
        max-height: 200px;
        overflow-y: auto;
        background: #ffffff;
        border: 1px solid var(--chat-border);
        border-radius: 8px;
        box-shadow: 0px 0px 20px 0px rgba(76, 87, 125, 0.15);
        display: none;
        margin-bottom: 8px;
    }

    .mention-item {
        padding: 8px 12px;
        cursor: pointer;
        transition: background-color 0.15s ease;
    }

    .mention-item:hover {
        background-color: #f1f1f1;
        color: #0086a7;
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
                            <div class="fw-bold fs-6 text-dark chat-sidebar-name"><?= $d['nama_materi'] ?></div>

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

            <div id="chat_area" style="display:none;" class="h-100 flex-column position-relative">
                <div class="p-5 border-bottom d-flex align-items-center">
                    <div class="fw-bold fs-5" id="active_title">Nama Materi</div>
                </div>

                <div class="chat-history" id="chat_history"></div>

                <div class="p-5 border-top bg-white position-relative">
                    <!-- Dropdown Menu Tagging Mention -->
                    <div id="mention_dropdown" class="mention-dropdown shadow-lg"></div>

                    <div class="d-flex gap-3 align-items-center">
                        <input type="text" id="msg_input" class="form-control" placeholder="Tulis pesan... (Gunakan @ untuk tag)">
                        <button class="btn btn-secondary btn-sm d-none" id="btn_cancel_edit" title="Batal Edit">Batal</button>
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
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div class="modal-body">
                <div class="position-relative mb-5">
                    <i class="ki-duotone ki-magnifier fs-2 position-absolute top-50 translate-middle-y ms-4">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <input type="text" id="search_materi" class="form-control form-control-solid ps-12" placeholder="Cari nama materi..." />
                </div>

                <div class="scroll-y mh-300px pe-5" id="list_materi">
                    <?php foreach ($materi as $m): ?>
                        <div class="materi-item card card-bordered border-gray-200 p-4 mb-3 cursor-pointer hover-elevate-up bg-hover-light-primary"
                            onclick="startNewChat('<?= $m['kode_materi'] ?>', '<?= $m['nama_materi'] ?>')"
                            data-search="<?= strtolower($m['nama_materi']) ?>">

                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-35px me-3">
                                    <span class="symbol-label bg-light-primary text-primary fw-bold">
                                        <?= strtoupper(substr($m['nama_materi'], 0, 1)) ?>
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <div class="fw-bold fs-6 text-gray-800 nama-materi-text"><?= $m['nama_materi'] ?></div>
                                    <div class="text-muted fs-7">Klik untuk buka diskusi</div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div id="no_results" class="text-center py-5 d-none">
                        <i class="ki-duotone ki-search-list fs-3x text-gray-400 mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        <div class="text-muted">Materi tidak ditemukan</div>
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

    // Token CSRF dinamis
    let chatCsrfName = '<?= csrf_token() ?>';
    let chatCsrfHash = '<?= csrf_hash() ?>';

    // Daftar partisipan tag khusus untuk diskusi yang sedang aktif saja
    let activeParticipants = [];

    $(document).ready(function() {
        $.ajaxSetup({
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
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
            if (e.which == 13) {
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

        // Event Input untuk Autocomplete Tag Mention (@)
        $('#msg_input').on('keyup input', handleMentionInput);

        // Tutup dropdown mention jika klik di luar area chat main
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.chat-main').length) {
                $('#mention_dropdown').hide();
            }
        });

        // Event Batal Edit
        $('#btn_cancel_edit').on('click', function() {
            editingMessageId = null;
            $('#msg_input').val('').prop('disabled', false).focus();
            $('#msg_input').attr('placeholder', 'Tulis pesan... (Gunakan @ untuk tag)');
            $(this).addClass('d-none');
        });
    });

    function startNewChat(kodeMateri, namaMateri) {
        $('#modal_materi').modal('hide');

        if (chatInterval) {
            clearInterval(chatInterval);
        }

        currentMateri = kodeMateri;
        lastDisplayedId = 0; 
        editingMessageId = null;
        activeParticipants = []; // Reset partisipan khusus diskusi ini

        $('#btn_cancel_edit').addClass('d-none');
        $('#msg_input').val('').attr('placeholder', 'Tulis pesan... (Gunakan @ untuk tag)');

        $('#active_title').text(namaMateri);
        $('#empty_state').attr('style', 'display: none !important;');
        $('#chat_area').attr('style', 'display: flex !important;');

        $('#chat_history').html('');

        fetchMessages(kodeMateri);

        chatInterval = setInterval(function() {
            if (currentMateri) {
                fetchMessages(currentMateri);
            }
        }, 3000);
    }

    function loadChat(materi, namaMateri) {
        if (currentMateri === materi) return; 

        currentMateri = materi;
        lastDisplayedId = 0; 
        editingMessageId = null;
        activeParticipants = []; // Reset partisipan khusus diskusi ini

        $('#btn_cancel_edit').addClass('d-none');
        $('#msg_input').val('').attr('placeholder', 'Tulis pesan... (Gunakan @ untuk tag)');

        $('.chat-item').removeClass('active');
        $(`.chat-item[data-materi="${materi}"]`).addClass('active');
        $('#empty_state').attr('style', 'display: none !important;');
        $('#chat_area').attr('style', 'display: flex !important;');
        $('#active_title').text(namaMateri);
        $('#chat_history').html(''); 

        fetchMessages(materi);

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

                if (response.participants && Array.isArray(response.participants)) {
                    response.participants.forEach(p => {
                        if (p && !activeParticipants.includes(p)) activeParticipants.push(p);
                    });
                }

                if (data && data.length > 0) {
                    let html = '';
                    data.forEach(m => {
                        const msgIdRaw = parseInt(m.id_chat_materi);

                        // Kumpulkan nama pengirim yang ada di diskusi ini saja
                        if (m.nama && !activeParticipants.includes(m.nama)) {
                            activeParticipants.push(m.nama);
                        }

                        if (msgIdRaw > lastDisplayedId) {
                            lastDisplayedId = msgIdRaw;

                            const isMe = (m.email === '<?= session()->get('email') ?>');
                            const processedText = formatMessage(m.text);
                            const msgId = `msg-${m.id_chat_materi}`;

                            const time = new Date(m.date_created * 1000).toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false
                            });

                            if (isMe) {
                                // Pengecekan Batas Waktu 5 Menit (300 Detik) untuk Edit & Hapus
                                const currentTime = Math.floor(Date.now() / 1000);
                                const timeDiff = currentTime - m.date_created;
                                let actionButtons = '';

                                if (timeDiff <= 300) {
                                    actionButtons = `
                                        <div class="mt-1 me-12">
                                            <span id="btn-edit-${msgIdRaw}" class="badge badge-light-primary badge-sm cursor-pointer text-hover-primary me-2" onclick="editMessage('${msgIdRaw}', '${encodeURIComponent(m.text)}')">
                                                <i class="ki-duotone ki-pencil fs-8 me-1"><span class="path1"></span><span class="path2"></span></i>Edit
                                            </span>
                                            <span id="btn-del-${msgIdRaw}" class="badge badge-light-danger badge-sm cursor-pointer text-hover-danger" onclick="deleteMessage('${msgIdRaw}', ${m.date_created})">
                                                <i class="ki-duotone ki-trash fs-8 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>Hapus
                                            </span>
                                        </div>
                                    `;
                                }

                                html += `
                                <div id="${msgId}" class="chat-bubble-container mb-4 justify-content-end">
                                    <div class="d-flex flex-column align-items-end">
                                        <div class="bubble-me">
                                            <div class="chat-name text-info">${m.nama}</div>
                                            <div class="chat-message-text" id="msg-text-${msgIdRaw}">${processedText}</div>
                                            <div class="chat-time">${time}</div>
                                        </div>
                                        ${actionButtons}
                                    </div>
                                    <div class="symbol symbol-35px symbol-circle ms-3 align-self-end">
                                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(m.nama)}&background=0086a7&color=fff">
                                    </div>
                                </div>`;
                            } else {
                                html += `
                                <div id="${msgId}" class="chat-bubble-container mb-4">
                                    <div class="symbol symbol-35px symbol-circle me-3 align-self-end">
                                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(m.nama)}&background=E8DED3&color=333">
                                    </div>
                                    <div class="d-flex flex-column align-items-start">
                                        <div class="bubble-them">
                                            <div class="chat-name">${m.nama}</div>
                                            <div class="chat-message-text">${processedText}</div>
                                            <div class="chat-time">${time}</div>
                                        </div>
                                        <div class="mt-1 ms-1">
                                            <span class="badge badge-light badge-sm cursor-pointer text-hover-primary" onclick="replyTo('${m.nama}')">
                                                <i class="ki-duotone ki-left fs-8 me-1"><span class="path1"></span><span class="path2"></span></i>Balas
                                            </span>
                                        </div>
                                    </div>
                                </div>`;
                            }
                        }
                    });

                    if (html !== '') {
                        $('#chat_history').append(html);

                        if (unreadId) {
                            const targetElement = document.getElementById(`msg-${unreadId}`);
                            if (targetElement) {
                                targetElement.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'start'
                                });
                            }
                        } else {
                            $('#chat_history').animate({
                                scrollTop: $('#chat_history')[0].scrollHeight
                            }, 300);
                        }
                    }
                }
            }
        });
    }

    // Fungsi Balas (Reply) untuk Mentag Pengirim
    function replyTo(nama) {
        const input = $('#msg_input');
        const currentText = input.val();
        input.val(`${currentText} @[${nama}] `);
        input.focus();
    }

    // Penanganan Autocomplete saat mengetik @
    function handleMentionInput(e) {
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

    // Tampilkan List Dropdown Mention
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

    // Sisipkan hasil Tag ke input teks dalam format @[Nama]
    function insertMention(name, atIndex, cursorPosition) {
        const input = $('#msg_input');
        const val = input.val();

        const beforeAt = val.substring(0, atIndex);
        const afterCursor = val.substring(cursorPosition);

        const newVal = `${beforeAt}@[${name}] ${afterCursor}`;
        input.val(newVal);
        $('#mention_dropdown').hide();
        input.focus();
    }

    // Format URL & Tag Mention (@[Nama]) menjadi Badge Visual
    function formatMessage(text) {
        if (!text) return "";

        // 1. Convert URL link
        var urlRegex = /(https?:\/\/[^\s]+)/g;
        text = text.replace(urlRegex, function(url) {
            return '<a href="' + url + '" target="_blank" rel="noopener noreferrer" style="color: inherit; text-decoration: underline;">' + url + '</a>';
        });

        // 2. Convert Mention Tag (@[Nama]) menjadi Badge
        var mentionRegex = /@\[(.*?)\]/g;
        text = text.replace(mentionRegex, `<span class="badge badge-light-primary text-primary fw-bold px-2 py-1 me-1"><i class="ki-duotone ki-profile-circle text-primary me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>@$1</span>`);

        return text;
    }

    function sendMessage() {
        const text = $('#msg_input').val();
        if (!text || !currentMateri) return;

        $('#mention_dropdown').hide();
        $('#msg_input').prop('disabled', true);
        $('#btn_cancel_edit').prop('disabled', true);

        if (editingMessageId) {
            // PROSES UPDATE / EDIT PESAN
            let postData = { id_chat: editingMessageId, text: text };
            postData[chatCsrfName] = chatCsrfHash;

            $.post(`<?= base_url('sw-siswa/diskusi/update-chat') ?>`, postData, function(res) {
                if (res && res.token) chatCsrfHash = res.token;
                
                // Ubah teks di layar secara instan tanpa reload
                $('#msg-text-' + editingMessageId).html(formatMessage(text));
                
                // Perbarui parameter tombol edit
                $('#btn-edit-' + editingMessageId).attr('onclick', `editMessage('${editingMessageId}', '${encodeURIComponent(text)}')`);
                
                // Reset form input
                editingMessageId = null;
                $('#btn_cancel_edit').addClass('d-none').prop('disabled', false);
                $('#msg_input').attr('placeholder', 'Tulis pesan... (Gunakan @ untuk tag)');
                $('#msg_input').prop('disabled', false).val('').focus();
                
            }).fail(function(xhr) {
                $('#msg_input').prop('disabled', false);
                $('#btn_cancel_edit').prop('disabled', false);
                let errMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Gagal mengedit pesan.";
                Swal.fire('Gagal', errMsg, 'error');
            });

        } else {
            // PROSES KIRIM PESAN BARU
            let postData = {
                materi: currentMateri,
                text: text
            };
            postData[chatCsrfName] = chatCsrfHash;

            $.post(`<?= base_url('sw-siswa/diskusi/send') ?>`, postData, function(res) {
                if (res && res.token) chatCsrfHash = res.token;
                $('#msg_input').prop('disabled', false).val('').focus();
                fetchMessages(currentMateri);
            }).fail(function(xhr) {
                $('#msg_input').prop('disabled', false);
                let errMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Gagal mengirim pesan.";
                Swal.fire('Gagal', errMsg, 'error');
            });
        }
    }

    // Fungsi Trigger Edit ke Kotak Input
    function editMessage(id, encodedText) {
        editingMessageId = id;
        $('#msg_input').val(decodeURIComponent(encodedText)).focus();
        $('#msg_input').attr('placeholder', 'Mengedit pesan... (Tekan Enter untuk simpan)');
        $('#btn_cancel_edit').removeClass('d-none');
    }

    // Fungsi Hapus Pesan dengan Validasi Waktu 5 Menit & SweetAlert
    function deleteMessage(id, timeStamp) {
        const currentTime = Math.floor(Date.now() / 1000);
        const timeDiff = currentTime - timeStamp;

        if (timeDiff > 300) {
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
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Memproses penghapusan pesan',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                let postData = { id_chat: id };
                postData[chatCsrfName] = chatCsrfHash;

                $.post(`<?= base_url('sw-siswa/diskusi/delete-chat') ?>`, postData, function(res) {
                    if (res && res.token) chatCsrfHash = res.token;
                    
                    Swal.close();
                    
                    // Hapus elemen pesan dari layar seketika tanpa reload halaman
                    $(`#msg-${id}`).fadeOut(300, function() {
                        $(this).remove();
                    });

                    // Jika pesan yang sedang diedit ternyata dihapus, reset state edit
                    if (editingMessageId == id) {
                        editingMessageId = null;
                        $('#msg_input').val('').prop('disabled', false);
                        $('#msg_input').attr('placeholder', 'Tulis pesan... (Gunakan @ untuk tag)');
                        $('#btn_cancel_edit').addClass('d-none');
                    }

                }).fail(function(xhr) {
                    Swal.close();
                    let errMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Gagal menghapus pesan.";
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: errMsg,
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    }

    $(document).ready(function() {
        // Fungsi Pencarian Real-time
        $('#search_materi').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            let items = $('.materi-item');
            let found = false;

            items.each(function() {
                if ($(this).attr('data-search').indexOf(value) > -1) {
                    $(this).removeClass('d-none');
                    found = true;
                } else {
                    $(this).addClass('d-none');
                }
            });

            if (found) {
                $('#no_results').addClass('d-none');
            } else {
                $('#no_results').removeClass('d-none');
            }
        });

        $('#modal_materi').on('hidden.bs.modal', function() {
            $('#search_materi').val('');
            $('.materi-item').removeClass('d-none');
            $('#no_results').addClass('d-none');
        });
    });
</script>

<?= $this->endSection(); ?>
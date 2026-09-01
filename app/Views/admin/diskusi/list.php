<?= $this->extend('template/app'); ?>

<?= $this->section('styles'); ?>
<style>
    /* Styling scrollbar untuk area chat */
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
        transition: background-color 0.2s ease;
    }

    .chat-item:hover,
    .chat-item.active {
        background-color: var(--bs-gray-100);
    }

    /* Memastikan text URL wrap */
    .chat-message-text {
        word-wrap: break-word;
    }

    /* Styling Popup Dropdown Mention Tag */
    .mention-dropdown {
        position: absolute;
        bottom: 100%;
        left: 0;
        z-index: 1050;
        width: 280px;
        max-height: 200px;
        overflow-y: auto;
        background: #ffffff;
        border: 1px solid var(--bs-gray-300);
        border-radius: 0.475rem;
        box-shadow: 0px 0px 20px 0px rgba(76, 87, 125, 0.15);
        display: none;
    }

    .mention-item {
        padding: 8px 12px;
        cursor: pointer;
        transition: background-color 0.15s ease;
    }

    .mention-item:hover {
        background-color: var(--bs-light-primary);
        color: var(--bs-primary);
    }

    /* Mencegah card kiri melompat saat menjadi sticky */
    .sticky-lg-top {
        transition: top 0.3s ease;
    }

    /* Mempercantik scrollbar internal card kiri agar lebih profesional */
    #kt_chat_contacts_body::-webkit-scrollbar {
        width: 4px;
    }
    #kt_chat_contacts_body::-webkit-scrollbar-thumb {
        background-color: var(--bs-gray-300);
        border-radius: 10px;
    }
    
    /* Memastikan kontainer utama tidak memiliki overflow yang memotong sticky */
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

                <div class="flex-column flex-lg-row-auto w-100 w-lg-300px w-xl-400px mb-10 mb-lg-0">
                    <div class="sticky-lg-top" style="top: 100px; z-index: 9;">
                        <div class="card card-flush shadow-sm">

                            <div class="card-header pt-7" id="kt_chat_contacts_header">
                                <form class="w-100 position-relative" autocomplete="off">
                                    <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 ms-5 translate-middle-y"><span class="path1"></span><span class="path2"></span></i>
                                    <input type="text" class="form-control form-control-solid px-13" name="search" id="searchChat" placeholder="Cari diskusi..." />
                                </form>
                            </div>

                            <div class="card-body pt-5" id="kt_chat_contacts_body">
                                <div class="scroll-y me-n5 pe-5 h-200px h-lg-auto" style="max-height: 60vh;" id="chat_list">

                                    <div class="accordion accordion-icon-toggle" id="accordionGuru">
                                        <?php foreach ($diskusi as $index => $group): ?>
                                            <?php
                                            if (empty($group['materi'])) continue;

                                            $totalUnreadGuru = 0;
                                            foreach ($group['materi'] as $m) {
                                                $totalUnreadGuru += $m['unread_count'];
                                            }
                                            ?>

                                            <div class="mb-5">

                                                <div class="accordion-header py-3 d-flex" data-bs-toggle="collapse" data-bs-target="#collapse<?= $group['id_guru'] ?>">
                                                    <span class="accordion-icon"><i class="ki-duotone ki-arrow-right fs-4"><span class="path1"></span><span class="path2"></span></i></span>
                                                    <div class="d-flex align-items-center w-100">
                                                        <div class="symbol symbol-35px symbol-circle me-3 position-relative">
                                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($group['nama_guru']) ?>&background=E8DED3&color=333" alt="Pic">
                                                            <?php if ($totalUnreadGuru > 0): ?>
                                                                <span class="position-absolute top-0 start-100 translate-middle badge badge-circle badge-danger fs-9 h-20px w-20px">
                                                                    <?= ($totalUnreadGuru > 99) ? '99+' : $totalUnreadGuru ?>
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <h3 class="fs-5 fw-bold mb-0 text-gray-900"><?= $group['nama_guru'] ?></h3>
                                                    </div>
                                                </div>

                                                <div id="collapse<?= $group['id_guru'] ?>" class="collapse fs-6 ps-10" data-bs-parent="#accordionGuru">
                                                    <div class="d-flex flex-column gap-2 py-3">
                                                        <?php foreach ($group['materi'] as $m): ?>
                                                            <div class="chat-item d-flex flex-stack p-3 rounded"
                                                                data-materi="<?= $m['materi'] ?>"
                                                                data-namamateri="<?= $m['nama_materi'] ?>"
                                                                data-emailguru="<?= $group['email_guru'] ?>"
                                                                data-namaguru="<?= $group['nama_guru'] ?>">

                                                                <div class="d-flex align-items-center">
                                                                    <div class="d-flex flex-column">
                                                                        <span class="text-gray-900 fw-bold fs-6"><?= $m['nama_materi'] ?></span>
                                                                        <span class="text-muted fw-semibold fs-7">Klik untuk diskusi</span>
                                                                    </div>
                                                                </div>

                                                                <div class="d-flex flex-column align-items-end ms-2">
                                                                    <?php if ($m['unread_count'] > 0): ?>
                                                                        <span class="badge badge-sm badge-circle badge-primary unread-badge" data-materi="<?= $m['materi'] ?>">
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
                <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                    <div class="card shadow-sm">

                        <div id="empty_state" class="card-body d-flex flex-column justify-content-center align-items-center text-center h-100" style="min-height: 50vh;">
                            <i class="ki-duotone ki-messages fs-5x text-gray-400 mb-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            <h3 class="text-gray-600 fw-bold">Belum Ada Obrolan Aktif</h3>
                            <div class="text-gray-500 fw-semibold fs-6">Pilih salah satu materi di sebelah kiri untuk memulai diskusi.</div>
                        </div>

                        <div id="chat_area" class="d-none flex-column h-100">

                            <div class="card-header border-bottom">
                                <div class="card-title">
                                    <div class="d-flex justify-content-center flex-column me-3">
                                        <h2 class="fs-4 fw-bold text-gray-900 mb-0" id="active_title">Nama Materi</h2>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="scroll-y me-n5 pe-5 chat-history"
                                    id="chat_history"
                                    data-kt-scroll="true"
                                    data-kt-scroll-activate="{default: true, lg: true}"
                                    data-kt-scroll-max-height="auto"
                                    data-kt-scroll-dependencies="#kt_header, #kt_app_header, #kt_toolbar, #kt_app_toolbar, #kt_footer, #kt_app_footer, .card-header, .card-footer"
                                    data-kt-scroll-wrappers="#kt_app_content_container, .card-body"
                                    data-kt-scroll-offset="5px"
                                    style="min-height: 400px; height: 500px;">
                                </div>
                            </div>

                            <div class="card-footer pt-4" id="kt_chat_messenger_footer">
                                <div class="d-flex align-items-center position-relative">
                                    <!-- Dropdown Menu Tagging Mention -->
                                    <div id="mention_dropdown" class="mention-dropdown shadow-lg"></div>

                                    <input type="text" id="msg_input" class="form-control form-control-flush mb-0 border-0 fs-6" placeholder="Tuliskan pesan Anda... (Gunakan @ untuk tag)" autocomplete="off" />
                                    <div class="d-flex align-items-center ms-2">
                                        <!-- Tombol Batal Edit (Disembunyikan secara default) -->
                                        <button class="btn btn-danger btn-sm btn-icon me-1 d-none" id="btn_cancel_edit" title="Batal Edit">
                                            <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                                        </button>
                                        <button class="btn btn-primary btn-sm btn-icon" id="btn_send">
                                            <i class="ki-duotone ki-send fs-2"><span class="path1"></span><span class="path2"></span></i>
                                        </button>
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

<!-- Modal Materi Baru -->
<div class="modal fade" id="modal_materi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded border-0">

            <div class="modal-header pb-0 border-0 justify-content-between">
                <h2 class="fw-bold">Mulai Diskusi Baru</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">
                <div class="text-gray-500 fw-semibold fs-6 mb-5">Pilih materi di bawah ini untuk memulai atau melanjutkan diskusi.</div>

                <div class="mh-300px scroll-y me-n5 pe-5">
                    <?php foreach ($materi as $m): ?>
                        <div class="d-flex flex-stack py-4 border-bottom border-gray-200" style="cursor:pointer;"
                            onclick="startNewChat('<?= $m['kode_materi'] ?>', '<?= esc($m['nama_materi']) ?>', '<?= esc($group['email_guru'] ?? '') ?>', '<?= esc($group['nama_guru'] ?? '') ?>')">

                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-40px symbol-circle me-4">
                                    <span class="symbol-label bg-light-primary">
                                        <i class="ki-duotone ki-book-open text-primary fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-900 fw-bold fs-6"><?= $m['nama_materi'] ?></span>
                                    <span class="text-muted fw-semibold fs-7">Guru: <?= $m['nama_guru'] ?? 'Pengajar' ?></span>
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
    let editingMessageId = null; // Menyimpan ID pesan yang sedang diedit

    // Token CSRF
    let chatCsrfName = '<?= csrf_token() ?>';
    let chatCsrfHash = '<?= csrf_hash() ?>';

    // Menyimpan daftar nama anggota di materi ini untuk autocomplete mention
    let activeParticipants = [];

    $(document).ready(function() {
        // Klik Sidebar Materi
        $(document).on('click', '.chat-item', function() {
            const materi = $(this).data('materi');
            const namaMateri = $(this).data('namamateri');
            currentEmailGuru = $(this).data('emailguru');
            currentNamaGuru = $(this).data('namaguru');

            $('.chat-item').removeClass('active');
            $(this).addClass('active');

            loadChat(materi, namaMateri);
        });

        $('#btn_send').on('click', sendMessage);
        $('#msg_input').on('keypress', function(e) {
            if (e.which == 13) {
                if ($('#mention_dropdown').is(':visible')) {
                    // Pilih item pertama jika dropdown sedang terbuka
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

        // Tutup dropdown jika klik di luar
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#kt_chat_messenger_footer').length) {
                $('#mention_dropdown').hide();
            }
        });

        // Event Batal Edit
        $('#btn_cancel_edit').on('click', function() {
            editingMessageId = null;
            $('#msg_input').val('').prop('disabled', false).focus();
            $('#msg_input').attr('placeholder', 'Tuliskan pesan Anda... (Gunakan @ untuk tag)');
            $(this).addClass('d-none');
        });
    });

    function startNewChat(kodeMateri, namaMateri, emailGuru, nama_guru) {
        $('#modal_materi').modal('hide');
        currentEmailGuru = emailGuru;
        currentNamaGuru = nama_guru;
        loadChat(kodeMateri, namaMateri);
    }

    function loadChat(materi, namaMateri) {
        if (currentMateri === materi && !$('#chat_area').hasClass('d-none')) return;

        currentMateri = materi;
        lastDisplayedId = 0;
        activeParticipants = [];
        editingMessageId = null;
        
        $('#btn_cancel_edit').addClass('d-none');
        $('#msg_input').val('').attr('placeholder', 'Tuliskan pesan Anda... (Gunakan @ untuk tag)');

        if (currentNamaGuru && !activeParticipants.includes(currentNamaGuru)) {
            activeParticipants.push(currentNamaGuru);
        }

        $('#empty_state').removeClass('d-flex').addClass('d-none');
        $('#chat_area').removeClass('d-none').addClass('d-flex');

        $('#active_title').text(namaMateri);
        $('#chat_history').html('<div class="text-center py-20"><span class="spinner-border text-primary"></span></div>');

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
                if (lastDisplayedId === 0) {
                    $('#chat_history').html('');
                }

                // Update daftar partisipan dari server
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

                        // Kumpulkan partisipan dari riwayat obrolan
                        if (m.nama && !activeParticipants.includes(m.nama)) {
                            activeParticipants.push(m.nama);
                        }

                        if (msgId > lastDisplayedId) {
                            lastDisplayedId = msgId;
                            hasNewMessage = true;

                            const isMe = (m.email === currentEmailGuru);
                            const time = new Date(m.date_created * 1000).toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit'
                            });

                            if (isMe) {
                                // Pengecekan Waktu 5 Menit (300 Detik)
                                const currentTime = Math.floor(Date.now() / 1000);
                                const timeDiff = currentTime - m.date_created;
                                let actionButtons = '';

                                // Jika kurang dari atau sama dengan 5 menit, tampilkan tombol Edit & Hapus
                                if (timeDiff <= 300) {
                                    actionButtons = `
                                        <!-- Tombol Aksi -->
                                        <div class="mt-1 me-14">
                                            <span id="btn-edit-${msgId}" class="badge badge-light-primary badge-sm cursor-pointer text-hover-primary me-2" onclick="editMessage('${msgId}', '${encodeURIComponent(m.text)}')">
                                                <i class="ki-duotone ki-pencil fs-8 me-1"><span class="path1"></span><span class="path2"></span></i>Edit
                                            </span>
                                            <span class="badge badge-light-danger badge-sm cursor-pointer text-hover-danger" onclick="deleteMessage('${msgId}', ${m.date_created})">
                                                <i class="ki-duotone ki-trash fs-8 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>Hapus
                                            </span>
                                        </div>
                                    `;
                                }

                                // TAMPILAN SAYA (KANAN)
                                html += `
                                <div class="d-flex justify-content-end mb-10" id="chat-item-${msgId}">
                                    <div class="d-flex flex-column align-items-end">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="me-3">
                                                <span class="text-muted fs-8 mb-1">${time}</span>
                                                <a href="#" class="fs-6 fw-bold text-gray-900 text-hover-primary ms-1">Anda</a>
                                            </div>
                                            <div class="symbol symbol-35px symbol-circle">
                                                <img alt="Pic" src="https://ui-avatars.com/api/?name=${encodeURIComponent(m.nama)}&background=0086a7&color=fff">
                                            </div>
                                        </div>
                                        <!-- TAMBAHAN: id="msg-text-${msgId}" -->
                                        <div class="p-4 rounded bg-light-primary text-gray-900 fw-semibold mw-lg-400px text-end chat-message-text" id="msg-text-${msgId}">
                                            ${formatMessage(m.text)}
                                        </div>
                                        ${actionButtons}
                                    </div>
                                </div>`;
                            } else {
                                // TAMPILAN ORANG LAIN (KIRI) DENGAN TOMBOL BALAS / TAG
                                html += `
                                <div class="d-flex justify-content-start mb-10">
                                    <div class="d-flex flex-column align-items-start">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="symbol symbol-35px symbol-circle">
                                                <img alt="Pic" src="https://ui-avatars.com/api/?name=${encodeURIComponent(m.nama)}&background=E8DED3&color=333">
                                            </div>
                                            <div class="ms-3">
                                                <a href="#" class="fs-6 fw-bold text-gray-900 text-hover-primary me-1">${m.nama}</a>
                                                <span class="text-muted fs-8 mb-1">${time}</span>
                                            </div>
                                        </div>
                                        <div class="p-4 rounded bg-light-info text-gray-900 fw-semibold mw-lg-400px text-start chat-message-text">
                                            ${formatMessage(m.text)}
                                        </div>
                                        <!-- Tombol Tag / Balas -->
                                        <div class="mt-1 ms-14">
                                            <span class="badge badge-light badge-sm cursor-pointer text-hover-primary" onclick="replyTo('${m.nama}')">
                                                <i class="ki-duotone ki-left fs-8 me-1"><span class="path1"></span><span class="path2"></span></i>Balas
                                            </span>
                                        </div>
                                    </div>
                                </div>`;
                            }
                        }
                    });

                    if (hasNewMessage) {
                        $('#chat_history').append(html);
                        $('#chat_history').animate({
                            scrollTop: $('#chat_history')[0].scrollHeight
                        }, 300);
                    }
                }
            });
    }

    // Fungsi Trigger Balas / Tag dari tombol di bawah pesan
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
        
        // Cari simbol @ terakhir sebelum kursor
        const lastAtIndex = textBeforeCursor.lastIndexOf('@');

        if (lastAtIndex !== -1) {
            const query = textBeforeCursor.substring(lastAtIndex + 1);
            
            // Periksa jika query tidak mengandung spasi atau karakter khusus penutup
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

    // Tampilkan List Dropdown
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

    // Sisipkan hasil Tag ke input teks
    function insertMention(name, atIndex, cursorPosition) {
        const input = $('#msg_input');
        const val = input.val();

        const beforeAt = val.substring(0, atIndex);
        const afterCursor = val.substring(cursorPosition);

        // Format tag disimpankan sebagai @[Nama Member]
        const newVal = `${beforeAt}@[${name}] ${afterCursor}`;
        input.val(newVal);
        $('#mention_dropdown').hide();
        input.focus();
    }

    // Mengubah format URL & Tag Mention (@[Nama]) menjadi Badge Visual
    function formatMessage(text) {
        if (!text) return "";

        // 1. Convert URL link
        var urlRegex = /(https?:\/\/[^\s]+)/g;
        text = text.replace(urlRegex, url => `<a href="${url}" target="_blank" class="fw-bold text-primary text-hover-dark">${url}</a>`);

        // 2. Convert Mention Tag (@[Nama]) menjadi Badge
        var mentionRegex = /@\[(.*?)\]/g;
        text = text.replace(mentionRegex, `<span class="badge badge-light-primary text-primary fw-bold px-2 py-1 me-1"><i class="ki-duotone ki-profile-circle text-primary me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>@$1</span>`);

        return text;
    }

    // Modifikasi Fitur Send Message (Insert Baru atau Update Edit secara Real-time)
    function sendMessage() {
        const text = $('#msg_input').val();
        if (!text || !currentMateri) return;

        $('#mention_dropdown').hide();
        $('#msg_input').prop('disabled', true);
        $('#btn_cancel_edit').prop('disabled', true);

        if (editingMessageId) {
            let postData = { id_chat: editingMessageId, text: text };
            postData[chatCsrfName] = chatCsrfHash;

            $.post(`<?= base_url('sw-admin/diskusi/update-message') ?>`, postData, function(res) {
                if (res && res.token) chatCsrfHash = res.token;
                
                // 1. UBAH TEKS DI LAYAR SECARA LANGSUNG (TANPA RELOAD)
                $('#msg-text-' + editingMessageId).html(formatMessage(text));
                
                // 2. PERBARUI PARAMETER TOMBOL EDIT AGAR MENYIMPAN TEKS TERBARU
                $('#btn-edit-' + editingMessageId).attr('onclick', `editMessage('${editingMessageId}', '${encodeURIComponent(text)}')`);
                
                // 3. Kembalikan input ke mode normal
                editingMessageId = null;
                $('#btn_cancel_edit').addClass('d-none').prop('disabled', false);
                $('#msg_input').attr('placeholder', 'Tuliskan pesan Anda... (Gunakan @ untuk tag)');
                $('#msg_input').prop('disabled', false).val('').focus();
                
            }).fail(function(xhr) {
                $('#msg_input').prop('disabled', false);
                $('#btn_cancel_edit').prop('disabled', false);
                let errMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Gagal mengedit pesan.";
                Swals.alert("Gagal", errMsg, "error");
            });

        } else {
            // PROSES KIRIM BARU
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
                fetchMessages(currentMateri);
            }).fail(function() {
                $('#msg_input').prop('disabled', false);
                Swals.alert("Gagal", "Gagal mengirim pesan, silakan coba lagi.", "error");
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

    // Fungsi Delete dengan Validasi Waktu & SweetAlert Confirm (Tanpa Reload)
    function deleteMessage(id, timeStamp) {
        // Pengecekan 5 Menit (300 Detik)
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
                    
                    // PERBAIKAN: Hapus pesan langsung dari layar dengan animasi fadeOut (tanpa reload)
                    $('#chat-item-' + id).fadeOut(300, function() {
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
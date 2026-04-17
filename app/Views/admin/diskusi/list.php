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
        white-space: pre-wrap;
    }
</style>
<style>
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
                                <div class="d-flex align-items-center">
                                    <input type="text" id="msg_input" class="form-control form-control-flush mb-0 border-0 fs-6" placeholder="Tuliskan pesan Anda..." autocomplete="off" />
                                    <div class="d-flex align-items-center ms-2">
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
            if (e.which == 13) sendMessage();
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

        // Sembunyikan Empty State (Hapus d-flex, tambah d-none)
        $('#empty_state').removeClass('d-flex').addClass('d-none');

        // Tampilkan Chat Area (Hapus d-none, tambah d-flex)
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
                const data = response.messages;
                if (data && data.length > 0) {
                    let html = '';
                    let hasNewMessage = false;

                    data.forEach(m => {
                        const msgId = parseInt(m.id_chat_materi);

                        if (msgId > lastDisplayedId) {
                            lastDisplayedId = msgId;
                            hasNewMessage = true;

                            const isMe = (m.email === currentEmailGuru);
                            const time = new Date(m.date_created * 1000).toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit'
                            });

                            if (isMe) {
                                // TAMPILAN SAYA (KANAN) - Menggunakan komponen Metronic Native
                                html += `
                                <div class="d-flex justify-content-end mb-10">
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
                                        <div class="p-4 rounded bg-light-primary text-gray-900 fw-semibold mw-lg-400px text-end chat-message-text">
                                            ${urlify(m.text)}
                                        </div>
                                    </div>
                                </div>`;
                            } else {
                                // TAMPILAN ORANG LAIN (KIRI) - Menggunakan komponen Metronic Native
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
                                            ${urlify(m.text)}
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

    function sendMessage() {
        const text = $('#msg_input').val();
        if (!text || !currentMateri) return;

        $('#msg_input').val('').prop('disabled', true);

        $.post(`<?= base_url('sw-admin/diskusi/send') ?>`, {
            materi: currentMateri,
            text: text,
            email_guru: currentEmailGuru,
            nama_guru: currentNamaGuru
        }, function() {
            $('#msg_input').prop('disabled', false).focus();
            fetchMessages(currentMateri);
        });
    }

    function urlify(text) {
        if (!text) return "";
        var urlRegex = /(https?:\/\/[^\s]+)/g;
        return text.replace(urlRegex, url => `<a href="${url}" target="_blank" class="fw-bold text-primary text-hover-dark">${url}</a>`);
    }
</script>
<?= $this->endSection(); ?>
<?= $this->extend('siswa/template/app'); ?>
<?= $this->section('content'); ?>

<meta name="csrf-token" content="<?= csrf_hash() ?>">

<style>
    :root {
        --chat-bg: #F9F6F2;
        --chat-border: #E8E2D9;
        --chat-active: #E8DED3;
    }
    .chat-container { background-color: var(--chat-bg); border: 1px solid var(--chat-border); border-radius: 12px; overflow: hidden; }
    .chat-sidebar { border-right: 1px solid var(--chat-border); background: #fff; }
    .chat-item { cursor: pointer; transition: all 0.2s; border-bottom: 1px solid #f1f1f1; }
    .chat-item.active { background-color: var(--chat-active) !important; }
    .bubble-in { background-color: #EEEAE4; border-radius: 15px; padding: 12px; max-width: 70%; }
    .btn-send { background-color: #89B3A1; border: none; border-radius: 8px; color: white; }
    
    /* Style untuk List Materi di Modal */
    .materi-item { cursor: pointer; border: 1px solid #eee; border-radius: 10px; transition: all 0.2s; }
    .materi-item:hover { background-color: #f8f9fa; border-color: #89B3A1; }
</style>

<div class="d-flex flex-column flex-column-fluid py-3 py-lg-6 mt-8">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="chat-container d-flex flex-column flex-lg-row">
                
                <div class="chat-sidebar flex-column flex-lg-row-auto w-100 w-lg-350px">
                    <div class="p-5 d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold m-0">Chat</h3>
                        <button class="btn btn-sm btn-icon btn-light-primary" data-bs-toggle="modal" data-bs-target="#modal_pilih_materi">
                            <i class="ki-outline ki-plus fs-2"></i>
                        </button>
                    </div>

                    <div class="px-5 mb-5">
                        <input type="text" class="form-control form-control-solid border" placeholder="Cari chat..." style="border-radius: 8px;">
                    </div>

                    <div class="scroll-y h-500px" id="chat_list_container">
                        <?php foreach($diskusi as $d): ?>
                            <div class="chat-item d-flex align-items-center p-5" data-chat-id="101" data-name="<?= $d['nama_materi'] ?>">
                                <div class="symbol symbol-45px symbol-circle me-4">
                                    <img src="https://ui-avatars.com/api/?name=<?= $d['nama_materi'] ?>" alt="">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold fs-6"><?= $d['nama_materi'] ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="flex-lg-row-fluid d-flex flex-column" id="chat_main_area" style="display: none !important;">
                    <div class="p-5 border-bottom d-flex justify-content-between align-items-center bg-white">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40px symbol-circle me-3">
                                <img id="header_img" src="" alt="">
                            </div>
                            <div>
                                <div class="fw-bold fs-6" id="header_name">Nama Materi</div>
                                <div class="text-muted fs-8">Siswa & Tutor</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-10 flex-grow-1 scroll-y h-400px bg-white bg-opacity-50" id="chat_history">
                        <div class="text-center text-muted mt-20">Memuat percakapan...</div>
                    </div>

                    <div class="p-5 bg-white border-top">
                        <div class="d-flex align-items-center gap-3">
                            <input type="text" id="input_msg" class="form-control border py-3" placeholder="Ketik pesan...">
                            <button class="btn btn-send px-5 py-3" id="btn_send">Kirim</button>
                        </div>
                    </div>
                </div>

                <div class="flex-lg-row-fluid d-flex flex-column align-items-center justify-content-center p-20" id="empty_state">
                    <i class="ki-outline ki-messages fs-5x text-muted mb-5"></i>
                    <h3 class="text-muted">Klik tombol + untuk memulai diskusi materi</h3>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_pilih_materi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-500px">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h3 class="fw-bold">Pilih Materi Diskusi</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body scroll-y mh-400px">
                <?php foreach($materi as $m): ?>
                    <div class="materi-item p-4 mb-3 d-flex align-items-center" onclick="selectMateri(1, 'Brevet Pajak A', 'Tyas Kurniasari')">
                        <div class="symbol symbol-40px me-4">
                            <span class="symbol-label bg-light-danger text-danger fw-bold"><?= substr($m['nama_materi'], 0, 2) ?></span>
                        </div>
                        <div>
                            <div class="fw-bold fs-6"><?= $m['nama_materi'] ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // Handle klik pada list chat kiri
    $(document).on('click', '.chat-item', function() {
        openChat($(this).data('chat-id'), $(this).data('name'), $(this).find('img').attr('src'));
    });
});

// Fungsi ketika materi dipilih dari modal
function selectMateri(materiId, materiName, tutorName) {
    // 1. Tutup Modal
    $('#modal_pilih_materi').modal('hide');

    // 2. Jalankan AJAX untuk inisialisasi diskusi di database (Opsional)
    // Jika hanya ingin simulasi UI:
    const tutorImg = `https://ui-avatars.com/api/?name=${tutorName.replace(' ', '+')}`;
    
    // Tampilkan di area chat
    openChat(materiId, materiName, tutorImg);
}

function openChat(id, name, img) {
    // UI Transitions
    $('.chat-item').removeClass('active');
    $(`.chat-item[data-chat-id="${id}"]`).addClass('active');
    $('#empty_state').attr('style', 'display: none !important');
    $('#chat_main_area').attr('style', 'display: flex !important');

    // Update Header
    $('#header_name').text(name);
    $('#header_img').attr('src', img);

    // Load History (AJAX)
    $('#chat_history').html('<div class="text-center mt-20"><span class="spinner-border spinner-border-sm text-muted"></span> Memuat pesan...</div>');
    
    // Simulasi Delay Load
    setTimeout(() => {
        $('#chat_history').html(`<div class="text-center text-muted fs-8 mb-5">Diskusi dimulai pada materi: ${name}</div>`);
    }, 500);
}
</script>

<?= $this->endSection(); ?>
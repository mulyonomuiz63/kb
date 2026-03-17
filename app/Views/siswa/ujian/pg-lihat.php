<?= $this->extend('siswa/template/app'); ?>

<?= $this->section('styles'); ?>
<style>
    /* Kostumisasi Scrollbar untuk Navigator */
    .navigator-container {
        max-height: 420px;
        overflow-y: auto;
    }

    /* Styling Pilihan Jawaban */
    .exam-option {
        display: flex;
        align-items: center;
        border: 1px solid #E1E3EA;
        border-radius: 0.75rem;
        padding: 1rem 1.25rem;
        margin-bottom: 0.75rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .exam-option:hover {
        background-color: #F9F9F9;
        border-color: #009ef7;
    }

    .exam-option input[type="radio"] {
        width: 18px;
        height: 18px;
        margin-right: 12px;
    }

    /* Active State Navigator */
    .btn-check:checked+.btn.btn-light-primary,
    .btn.btn-light-primary.active {
        background-color: #009ef7 !important;
        color: white !important;
    }

    /* Question Content */
    .exam-question {
        font-size: 1.15rem;
        line-height: 1.6;
        color: #181C32;
    }
</style>
<style>
    /* Anti-Copy & Anti-Select */
    body {
        -webkit-user-select: none;
        /* Safari */
        -ms-user-select: none;
        /* IE 10 dan 11 */
        user-select: none;
        /* Standard syntax */
        -webkit-touch-callout: none;
        /* Disable long-press on iOS */
    }

    /* Mematikan interaksi pada gambar soal */
    img {
        pointer-events: none;
        -webkit-user-drag: none;
    }

    /* Mencegah akses ke elemen luar saat ujian aktif */
    .app-sidebar,
    .app-header,
    .app-footer {
        pointer-events: none;
        filter: blur(2px);
        opacity: 0.5;
    }

    /* Kecuali kontainer ujian */
    #kt_app_content_container {
        pointer-events: auto !important;
        filter: none !important;
        opacity: 1 !important;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<?php

use APP\Models\UjianSiswaModel;

$ujianSiswaModel = new UjianSiswaModel(); ?>

<div id="kt_app_content_container" class="app-container container-xxl">
    <input type="hidden" id="kode_ujian" value="<?= $ujian->kode_ujian; ?>">

    <div class="d-none">
        <span id="hour">00</span>:<span id="minute">00</span>:<span id="second">00</span>:<span id="count">00</span>
    </div>

    <div class="row g-7">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header align-items-center border-0 mt-4">
                    <div class="card-title align-items-start flex-column">
                        <span class="fw-bold text-gray-900 fs-3"><?= $ujian->nama_ujian; ?></span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Siswa: <?= session()->get('nama'); ?></span>
                    </div>
                    <div class="card-toolbar">
                        <div class="d-flex flex-column align-items-end">
                            <span class="text-muted fs-8 fw-bold text-uppercase mb-1">Sisa Waktu</span>
                            <div id="jam_skrng" class="badge badge-light-danger fs-3 fw-bolder px-4 py-3">00 : 00 : 00</div>
                        </div>
                    </div>
                </div>

                <div class="card-body py-5">
                    <?php if (time() >= strtotime($ujian->start_ujian)) : ?>
                        <?php if (count($ujian_siswa) > 0) : ?>
                            <?php if (($ujian_siswa[0]->status === null)) : ?>

                                <div id="examwizard-question">
                                    <?php
                                    $soal_hidden = '';
                                    $no = 1;
                                    foreach ($detail_ujian as $soal) :
                                        $jawaban_siswa = $ujianSiswaModel
                                            ->join('siswa', 'ujian_siswa.siswa=siswa.id_siswa')
                                            ->where('ujian_siswa.ujian_id', $soal->id_detail_ujian)
                                            ->where('siswa.email', session()->get('email'))
                                            ->get()->getRowObject();
                                    ?>

                                        <div class="question <?= $soal_hidden ?> question-<?= $no ?>" data-question="<?= $no ?>">
                                            <div class="exam-question mb-8">
                                                <span class="badge badge-square badge-outline badge-primary me-2 fs-4"><?= $no ?></span>
                                                <div class="mt-4 text-gray-800 fw-bold">
                                                    <?= strip_tags($soal->nama_soal, '<a><ul><li><i><em><strong><img><p><br>'); ?>
                                                </div>
                                            </div>

                                            <div class="exam-options mb-10">
                                                <?php for ($i = 1; $i <= 5; $i++):
                                                    $field = "pg_" . $i;
                                                    if (!empty(substr($soal->$field, 3))): ?>
                                                        <label class="exam-option">
                                                            <input type="radio"
                                                                data-id_siswa="<?= session('id'); ?>"
                                                                data-id_detail_ujian="<?= $soal->id_detail_ujian; ?>"
                                                                data-jawaban="<?= substr($soal->$field, 0, 1); ?>"
                                                                name="<?= $soal->id_detail_ujian; ?>"
                                                                value="<?= substr($soal->$field, 0, 1); ?>"
                                                                <?= substr($soal->$field, 0, 1) == ($jawaban_siswa->jawaban ?? '') ? 'checked' : ''; ?>>
                                                            <span class="text-gray-700 fw-semibold fs-6">
                                                                <b class="me-2"><?= substr($soal->$field, 0, 1); ?>.</b> <?= substr($soal->$field, 3); ?>
                                                            </span>
                                                        </label>
                                                <?php endif;
                                                endfor; ?>
                                            </div>
                                        </div>

                                    <?php $soal_hidden = 'd-none';
                                        $no++;
                                    endforeach; ?>
                                </div>

                                <input type="hidden" id="currentQuestionNumber" value="1">
                                <input type="hidden" id="totalOfQuestion" value="<?= count($detail_ujian); ?>">

                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <div class="card-footer border-0 d-flex align-items-center justify-content-between px-4 py-6 pb-8" style="gap: 0.5rem;">
                    <button id="back-to-prev-question" class="btn btn-secondary fw-bold px-3 px-md-8 d-flex align-items-center justify-content-center" style="flex: 1; max-width: 150px; height: 45px;">
                        <i class="ki-duotone ki-black-left fs-2 me-1"></i>
                        <span class="fs-7 fs-md-6 d-none d-sm-inline">Sebelumnya</span>
                    </button>

                    <div class="d-flex flex-column align-items-center px-2" style="min-width: 80px;">
                        <span class="text-gray-400 fw-bold" style="font-size: 0.7rem; text-transform: uppercase;">Progres</span>
                        <div class="d-flex align-items-center">
                            <span id="current-question-number-label" class="text-gray-900 fw-bolder fs-6">1</span>
                            <span class="text-gray-400 fw-bold fs-6 mx-1">/</span>
                            <span class="text-gray-900 fw-bolder fs-6"><?= count($detail_ujian); ?></span>
                        </div>
                    </div>

                    <button id="go-to-next-question" class="btn btn-primary fw-bold px-3 px-md-8 d-flex align-items-center justify-content-center" style="flex: 1; max-width: 150px; height: 45px;">
                        <span class="fs-7 fs-md-6 d-none d-sm-inline">Selanjutnya</span>
                        <i class="ki-duotone ki-black-right fs-2 ms-1"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h3 class="card-title fw-bold">Navigasi Soal</h3>
                </div>
                <div class="card-body">
                    <div class="navigator-container pe-2">
                        <div class="row g-3">
                            <?php
                            $no = 1;
                            foreach ($detail_ujian as $soal) :
                                // Cek apakah soal ini sudah dijawab oleh siswa
                                $cek_jawaban = $ujianSiswaModel
                                    ->where('ujian_id', $soal->id_detail_ujian)
                                    ->where('siswa', session()->get('id'))
                                    ->get()->getRowObject();

                                // Jika ada jawaban, beri class btn-success, jika tidak btn-light-primary
                                $bg_class = (!empty($cek_jawaban->jawaban)) ? 'btn-success text-white' : 'btn-light-primary';
                            ?>
                                <div class="col-3">
                                    <button class="btn btn-icon w-100 fw-bold question-response-rows nav-btn-<?= $no ?> <?= $bg_class ?>"
                                        data-question="<?= $no ?>">
                                        <?= $no ?>
                                    </button>
                                </div>
                            <?php $no++;
                            endforeach; ?>
                        </div>
                    </div>

                    <div class="separator separator-dashed my-6"></div>

                    <form id="kirim_ujian" action="<?= base_url('sw-siswa/ujian/kirim-ujian-selesai') ?>" method="post">
                        <input type="hidden" name="id_ujian" value="<?= $ujian->id_ujian ?>">
                        <input type="hidden" name="kode_ujian" value="<?= $ujian->kode_ujian ?>">

                        <button type="button" id="btn_akhiri_ujian" class="btn btn-danger w-100 fw-bold">
                            Akhiri Ujian
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<audio id="notifSound" src="<?= base_url('uploads/audio/notif.mp3') ?>" preload="auto"></audio>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Fungsi update visual navigasi
        function updateNavActive(no) {
            // Reset semua yang punya class 'active' atau border biru
            $('.question-response-rows').css('border', 'none');

            // Berikan border khusus pada soal yang sedang dibuka agar user tahu posisinya
            $('.nav-btn-' + no).css('border', '3px solid #009ef7');
        }

        updateNavActive(1); // Set awal soal nomor 1

        /* KLIK NAVIGASI */
        $('.question-response-rows').click(function() {
            var no_soal = $(this).data('question');
            $('.question').addClass('d-none');
            $('.question-' + no_soal).removeClass('d-none');
            $('#currentQuestionNumber').val(no_soal);
            $('#current-question-number-label').text(no_soal);
            updateNavActive(no_soal);
        });

        /* AJAX SIMPAN JAWABAN + UPDATE WARNA HIJAU */
        $("input[type='radio']").click(function(e) {
            // Simpan status awal (sebelum diklik) untuk berjaga-jaga jika gagal
            const radioBtn = $(this);
            const isPreviouslyChecked = radioBtn.prop('wasChecked');

            // Matikan sementara semua radio di grup ini agar tidak bisa klik brutal saat loading
            const groupName = radioBtn.attr('name');
            $(`input[name="${groupName}"]`).prop('disabled', true);

            var id_detail_ujian = radioBtn.data('id_detail_ujian');
            var id_siswa = radioBtn.data('id_siswa');
            var jawaban = radioBtn.data('jawaban');
            var kode_ujian = $("#kode_ujian").val();
            var jam = $('#minute').text() + ':' + $('#second').text();
            var currentNo = $('#currentQuestionNumber').val();

            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-siswa/ujian/kirim-ujian') ?>",
                data: {
                    id_siswa: id_siswa,
                    id_detail_ujian: id_detail_ujian,
                    kode_ujian: kode_ujian,
                    jawaban: jawaban,
                    jam: jam,
                },
                dataType: 'json', // Pastikan Controller Anda mengembalikan JSON
                success: function(response) {
                    // Aktifkan kembali input
                    $(`input[name="${groupName}"]`).prop('disabled', false);

                    // Play sound
                    const sound = document.getElementById("notifSound");
                    if (sound) sound.play();

                    // Ubah tombol navigasi menjadi hijau
                    $('.nav-btn-' + currentNo)
                        .removeClass('btn-light-primary')
                        .addClass('btn-success text-white');
                },
                error: function(xhr, status, error) {
                    // JIKA GAGAL: Kembalikan tampilan ke semula
                    $(`input[name="${groupName}"]`).prop('disabled', false);
                    radioBtn.prop('checked', false); // Batalkan centang

                    // Tampilkan peringatan keras ke siswa
                    Swal.fire({
                        title: 'Gagal Menyimpan!',
                        text: 'Koneksi internet terganggu atau sesi habis. Silakan klik ulang jawaban Anda.',
                        icon: 'error',
                        confirmButtonText: 'Coba Lagi',
                        customClass: {
                            confirmButton: "btn btn-danger"
                        }
                    });

                    // Kembalikan warna navigasi ke biru jika sebelumnya memang belum dijawab
                    // (Logika ini tergantung apakah sebelumnya sudah hijau atau belum)
                }
            });
        });

        /* NEXT QUESTION */
        $("#go-to-next-question").click(function() {
            var current = parseInt($('#currentQuestionNumber').val());
            var total = parseInt($('#totalOfQuestion').val());
            if (current < total) {
                current++;
                $('.question').addClass('d-none');
                $('.question-' + current).removeClass('d-none');
                $('#currentQuestionNumber').val(current);
                $('#current-question-number-label').text(current);
                updateNavActive(current);
            }
        });

        /* PREV QUESTION */
        $("#back-to-prev-question").click(function() {
            var current = parseInt($('#currentQuestionNumber').val());
            if (current > 1) {
                current--;
                $('.question').addClass('d-none');
                $('.question-' + current).removeClass('d-none');
                $('#currentQuestionNumber').val(current);
                $('#current-question-number-label').text(current);
                updateNavActive(current);
            }
        });
    });
</script>

<script>
    <?php if ($ujian_siswa != null && time() >= strtotime($ujian->start_ujian) && $ujian_siswa[0]->status == null) : ?>
        var countDownDate = new Date("<?= $ujian->end_ujian ?>").getTime();
        var x = setInterval(function() {
            var now = new Date().getTime();
            var distance = countDownDate - now;
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("jam_skrng").innerHTML = hours + " : " + minutes + " : " + seconds;

            if (distance < 0) {
                clearInterval(x);
                document.getElementById("jam_skrng").innerHTML = "00 : 00 : 00";
                document.getElementById("kirim_ujian").submit();
            }
        }, 1000);
    <?php endif; ?>

    let hour = 0;
    let minute = 0;
    let second = 0;
    let count = 0;
    let timer = true;
    stopWatch();

    function stopWatch() {
        if (timer) {
            count++;
            if (count == 100) {
                second++;
                count = 0;
            }
            if (second == 60) {
                minute++;
                second = 0;
            }
            if (minute == 60) {
                hour++;
                minute = 0;
                second = 0;
            }
            document.getElementById('hour').innerHTML = hour < 10 ? "0" + hour : hour;
            document.getElementById('minute').innerHTML = minute < 10 ? "0" + minute : minute;
            document.getElementById('second').innerHTML = second < 10 ? "0" + second : second;
            document.getElementById('count').innerHTML = count < 10 ? "0" + count : count;
            setTimeout(stopWatch, 20);
        }
    }
</script>

<script>
    // Anti Select Text
    document.onselectstart = function() {
        return false;
    };
</script>

<script>
    $("#btn_akhiri_ujian").on("click", function(e) {

        e.preventDefault();

        Swal.fire({
            title: 'Akhiri Ujian?',
            text: "Pastikan Anda sudah menjawab semua soal.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Akhiri',
            cancelButtonText: 'Batal',
            buttonsStyling: false,
            customClass: {
                confirmButton: "btn btn-danger",
                cancelButton: "btn btn-light"
            }
        }).then(function(result) {

            if (result.isConfirmed) {

                Swal.fire({
                    text: "Ujian sedang dikirim...",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "OK",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                }).then(function() {

                    $("#kirim_ujian").submit();

                });

            }

        });

    });
</script>
<script>
    // 1. Matikan Klik Kanan
    document.addEventListener('contextmenu', event => event.preventDefault());

    // 2. Matikan Shortcut Keyboard (F12, Inspect, Copy, Paste, Save)
    document.onkeydown = function(e) {
        if (e.keyCode == 123) return false; // F12
        if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) return false; // Ctrl+Shift+I
        if (e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) return false; // Ctrl+Shift+C
        if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) return false; // Ctrl+Shift+J
        if (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) return false; // Ctrl+U
        if (e.ctrlKey && e.keyCode == 'C'.charCodeAt(0)) return false; // Ctrl+C
        if (e.ctrlKey && e.keyCode == 'V'.charCodeAt(0)) return false; // Ctrl+V
        if (e.ctrlKey && e.keyCode == 'S'.charCodeAt(0)) return false; // Ctrl+S
    };

    // 3. Deteksi Jika User Pindah Tab (Anti-Buka Google)
    let cheatCount = 0;
    document.addEventListener("visibilitychange", function() {
        if (document.hidden) {
            cheatCount++;

            // Suara peringatan bisa ditambahkan di sini
            const sound = document.getElementById("notifSound");
            if (sound) sound.play();

            Swal.fire({
                title: "Peringatan Keras!",
                text: "Anda terdeteksi meninggalkan halaman ujian. Pelanggaran ke-" + cheatCount + ". Jika berulang 5 kali, ujian akan dihentikan otomatis.",
                icon: "error",
                confirmButtonText: "Saya Mengerti",
                customClass: {
                    confirmButton: "btn btn-danger"
                }
            });

            // Opsi: Jika sudah 3 kali pindah tab, otomatis submit
            if (cheatCount >= 3) {
                Swal.fire({
                    title: "Ujian Dihentikan!",
                    text: "Anda telah melebihi batas perpindahan halaman.",
                    icon: "error",
                    showConfirmButton: false,
                    timer: 3000
                }).then(() => {
                    document.getElementById("kirim_ujian").submit();
                });
            }
        }
    });

    // 4. Mencegah User Tidak Sengaja Klik Tombol Back Browser
    window.history.pushState(null, null, window.location.href);
    window.onpopstate = function() {
        window.history.go(1);
        Swal.fire({
            text: "Gunakan tombol navigasi yang tersedia di dalam ujian untuk berpindah soal.",
            icon: "info",
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    };
</script>
<?= $this->endSection(); ?>
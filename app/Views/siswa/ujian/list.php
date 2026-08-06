<?= $this->extend('siswa/template/app'); ?>

<?= $this->section('styles'); ?>
<style>
    /* Efek Berkedip Badge Live */
    .animate-blink {
        animation: blink-animation 1.2s steps(5, start) infinite;
    }

    @keyframes blink-animation {
        to {
            visibility: hidden;
        }
    }

    /* Memastikan video memenuhi container kamera */
    #camera_preview video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        transform: scaleX(-1);
        /* Efek Cermin */
    }

    /* Styling Tombol di Dalam Kamera agar lebih stand out */
    #btn_capture_start {
        border-radius: 12px;
        transition: all 0.2s ease;
    }

    #btn_capture_start:hover {
        transform: scale(1.02);
    }

    /* Gradasi gelap di bawah kamera agar tombol mudah terlihat */
    .position-absolute.bottom-0 {
        z-index: 10;
    }
</style>
<style>
    .biometric-container {
        position: relative;
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
        aspect-ratio: 4/3;
        background: #1e1e2d;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        border: 4px solid #ffffff;
    }

    /* Video dan Canvas menyatu */
    .biometric-video,
    .biometric-canvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scaleX(-1);
        /* Mirror effect agar natural */
    }

    /* Efek gelap di luar wajah */
    .biometric-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at center, transparent 30%, rgba(0, 0, 0, 0.7) 80%);
        z-index: 2;
    }

    /* Sudut Penargetan (Targeting Corners) */
    .scan-corner {
        position: absolute;
        width: 40px;
        height: 40px;
        border-color: #00ff00;
        border-style: solid;
        z-index: 3;
        transition: border-color 0.3s;
    }

    .scan-corner.top-left {
        top: 15%;
        left: 15%;
        border-width: 4px 0 0 4px;
        border-top-left-radius: 10px;
    }

    .scan-corner.top-right {
        top: 15%;
        right: 15%;
        border-width: 4px 4px 0 0;
        border-top-right-radius: 10px;
    }

    .scan-corner.bottom-left {
        bottom: 25%;
        left: 15%;
        border-width: 0 0 4px 4px;
        border-bottom-left-radius: 10px;
    }

    .scan-corner.bottom-right {
        bottom: 25%;
        right: 15%;
        border-width: 0 4px 4px 0;
        border-bottom-right-radius: 10px;
    }

    /* Garis Laser Animasi */
    .laser-line {
        position: absolute;
        left: 15%;
        width: 70%;
        height: 2px;
        background: #00ff00;
        box-shadow: 0 0 15px 2px #00ff00;
        z-index: 3;
        animation: scan-laser 2s infinite alternate ease-in-out;
        display: none;
        /* Disembunyikan sampai kamera siap */
    }

    @keyframes scan-laser {
        0% {
            top: 15%;
            opacity: 0;
        }

        10% {
            opacity: 1;
        }

        90% {
            opacity: 1;
        }

        100% {
            top: 75%;
            opacity: 0;
        }
    }

    /* Warna saat error/warning */
    .biometric-container.warning .scan-corner {
        border-color: #ffc700;
    }

    .biometric-container.warning .laser-line {
        background: #ffc700;
        box-shadow: 0 0 15px 2px #ffc700;
    }

    .biometric-container.danger .scan-corner {
        border-color: #f1416c;
    }

    .biometric-container.danger .laser-line {
        display: none;
    }
</style>
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>

<?php $db = Config\Database::connect(); ?>

<div class="d-flex flex-column flex-column-fluid py-3 py-lg-6">
    <div class="row g-6 g-xl-9">
        <div class="col-12 mb-5">
            <!-- Menggunakan bg-light-primary dan border dashed agar terlihat elegan di kedua mode -->
            <div class="alert bg-light-primary border border-primary border-dashed d-flex align-items-center p-5 mb-0">
                <!-- Ikon Informasi -->
                <i class="ki-duotone ki-information-5 fs-2hx text-primary me-4">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                </i>
                <!-- Teks Informasi -->
                <div class="d-flex flex-column">
                    <h4 class="mb-1 text-gray-900">Informasi Sertifikasi</h4>
                    <span class="text-gray-700">
                        Untuk memperoleh <strong class="text-gray-900">Sertifikat Brevet AB</strong>, Anda diwajibkan lulus pada 8 materi ujian yang telah ditentukan.
                    </span>
                </div>
            </div>
        </div>

        <!-- PERUBAHAN TAMPILAN DIMULAI DI SINI -->
        <?php if (!empty($ujian)) : ?>
            <?php foreach ($ujian as $nama_kelas => $daftar_ujian) : ?>

                <!-- HEADER / PEMISAH KELAS -->
                <div class="col-12 mt-8 mb-4">
                    <div class="card shadow-sm border-0 border-start border-5 border-primary">
                        <div class="card-body p-5 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px symbol-circle me-5">
                                    <div class="symbol-label bg-light-primary text-primary">
                                        <i class="ki-duotone ki-book-open fs-2x">
                                            <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                                        </i>
                                    </div>
                                </div>
                                <div>
                                    <h2 class="fw-bolder text-gray-900 mb-1"><?= $nama_kelas ?></h2>
                                    <span class="text-muted fw-semibold fs-6">Daftar paket ujian untuk kelas ini</span>
                                </div>
                            </div>

                            <!-- Menampilkan badge jumlah ujian di sebelah kanan Card -->
                            <div class="d-none d-sm-block">
                                <span class="badge badge-light-primary fw-bold px-4 py-3 text-uppercase">
                                    <i class="ki-outline ki-document text-primary me-2 fs-5"></i>
                                    <?= count($daftar_ujian) ?> Paket Ujian
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- LOOPING UJIAN DI DALAM KELAS TERSEBUT -->
                <?php foreach ($daftar_ujian as $u) : ?>
                    <?php
                    // Peningkatan Performa & Keamanan (Sama seperti kode Anda)
                    $total = $db->query("SELECT COUNT(id_detail_ujian) as jml FROM ujian_detail WHERE kode_ujian = ?", [$u->kode_ujian])->getRow()->jml;

                    $totalMenit = $total * 3;
                    $start = (date('Y-m-d H:i'));
                    $end_ = (date('Y-m-d H:i', strtotime("+ $totalMenit minutes")));
                    $durasi = date_diff(date_create($start), date_create($end_));

                    // === LOGIKA KUOTA DINAMIS & ROLE ACCESS ===
                    $roleAccess = session()->get('role_access') ?? 0;
                    $sisa_kuota = $u->kuota ?? 0;
                    $total_kuota = 3;
                    $terpakai = $total_kuota - $sisa_kuota;

                    if ($roleAccess == 1) {
                        $tampil_kuota = 'U'; // Untuk badge lingkaran
                        $teks_modal_kuota = 'Tanpa Batas'; // <-- Teks khusus untuk Modal
                        $kuotaColor = 'success';
                    } else {
                        $tampil_kuota = $terpakai . '/' . $total_kuota; // Untuk badge lingkaran
                        $teks_modal_kuota = $terpakai . ' dari ' . $total_kuota; // <-- Teks khusus untuk Modal

                        $kuotaColor = 'success';
                        if ($terpakai >= $total_kuota) {
                            $kuotaColor = 'danger';
                        } elseif ($terpakai > 0) {
                            $kuotaColor = 'warning';
                        }
                    }
                    ?>

                    <div class="col-md-6 col-xl-4">
                        <div class="card border-0 shadow-sm card-xl-stretch mb-xl-8 hover-elevate-up">
                            <div class="card-header border-0 p-0 min-h-150px overlay overflow-hidden rounded-top">
                                <?= img_lazy('uploads/mapel/' . $u->file, "loading", ['class' => 'w-100 h-100 object-fit-cover']) ?>
                                <div class="overlay-layer bg-dark bg-opacity-10"></div>

                                <div class="position-absolute top-0 end-0 m-4">
                                    <?= $u->nilai == null ? '' : ($u->nilai >= 60
                                        ? '<span class="badge badge-success fw-bold uppercase px-4 py-3">Lulus</span>'
                                        : '<span class="badge badge-danger fw-bold uppercase px-4 py-3">Tidak Lulus</span>'); ?>
                                </div>
                            </div>

                            <div class="card-body pt-5">
                                <div class="d-flex flex-stack mb-2">
                                    <span class="text-primary fw-bold fs-7 uppercase"><?= $u->nama_kelas ?></span>
                                    <span class="text-gray-500 fs-8 fw-bold">
                                        <i class="ki-duotone ki-time fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>
                                        <?= ($durasi != '0' ? (($durasi->h * 60) + $durasi->i) : '0') ?> Menit
                                    </span>
                                </div>

                                <a href="#" class="fs-4 text-gray-900 fw-bolder text-hover-primary lh-base d-block mb-4 h-50px">
                                    <?= $u->nama_ujian ?>
                                </a>

                                <div class="d-flex align-items-center flex-wrap d-grid gap-2 mb-6">
                                    <div class="d-flex align-items-center me-5">
                                        <div class="symbol symbol-35px symbol-circle me-3">
                                            <span class="symbol-label bg-light-<?= $kuotaColor ?> text-<?= $kuotaColor ?> fw-bold fs-8"><?= $tampil_kuota ?></span>
                                        </div>
                                        <div>
                                            <div class="fs-7 text-gray-800 fw-bold"> Penggunaan Kuota</div>
                                            <div class="fs-8 text-gray-500 fw-semibold">
                                                <?php if ($roleAccess == 1): ?>
                                                    Akses Tanpa Batas
                                                <?php else: ?>
                                                    <?= ($terpakai == 0) ? 'Kuota penuh' : (($terpakai >= $total_kuota) ? 'Kuota habis' : 'Terpakai ' . $terpakai . 'x') ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-35px symbol-circle me-3">
                                            <span class="symbol-label bg-light-primary text-primary fw-bold fs-8"><?= $u->nilai == null ? '-' : $u->nilai ?></span>
                                        </div>
                                        <div>
                                            <div class="fs-7 text-gray-800 fw-bold">Nilai</div>
                                            <div class="fs-8 text-gray-500 fw-semibold">Nilai Terakhir</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <div class="flex-grow-1">
                                        <?php if ($u->status == 'B') : ?>
                                            <?php $dataStatus = $db->query("SELECT * FROM status_ujian WHERE kode_ujian = ?", [$u->kode_ujian])->getRow(); ?>
                                            <?php if (!empty($dataStatus) && $dataStatus->status == 'A') : ?>
                                                <?php
                                                $dataAttrs = 'data-idujian="' . encrypt_url($u->id_ujian) . '" ' .
                                                    'data-kuota="' . $teks_modal_kuota . '" ' .  // <-- Ubah di sini
                                                    'data-warna="' . $kuotaColor . '" ' .
                                                    'data-soal="' . $total . ' soal" ' .
                                                    'data-waktu="' . $totalMenit . ' menit"';
                                                ?>
                                                <a href="<?= base_url('sw-siswa/ujian/lihat-pg') . '/' . encrypt_url($u->kode_ujian) . '/' . encrypt_url(session()->get('id')) . '/' . encrypt_url($u->id_ujian); ?>"
                                                    <?= $dataAttrs ?>
                                                    class="btn btn-primary w-100 fw-bold btn-informasi-mulai">
                                                    Mulai
                                                </a>
                                            <?php else : ?>
                                                <button class="btn btn-light w-100 fw-bold btn-informasi-disabled" disabled>Belum Dibuka</button>
                                            <?php endif; ?>

                                        <?php elseif ($u->status == 'U') : ?>
                                            <a href="<?= base_url('sw-siswa/ujian/lihat-pg') . '/' . encrypt_url($u->kode_ujian) . '/' . encrypt_url(session()->get('id')) . '/' . encrypt_url($u->id_ujian); ?>"
                                                class="btn btn-warning w-100 fw-bold">Sedang Ujian</a>

                                        <?php else : ?>
                                            <?php if ($roleAccess == 1 || $u->kuota != '0') : ?>
                                                <?php
                                                $dataAttrs = 'data-idujian="' . encrypt_url($u->id_ujian) . '" ' .
                                                    'data-kuota="' . $tampil_kuota . '" ' .
                                                    'data-warna="' . $kuotaColor . '" ' .
                                                    'data-soal="' . $total . ' soal" ' .
                                                    'data-waktu="' . $totalMenit . ' menit"';
                                                ?>
                                                <a href="<?= base_url('sw-siswa/ujian/remedial') . '/' . encrypt_url($u->id_ujian) . '/' . encrypt_url($u->kode_ujian) . '/' . $u->status ?>"
                                                    <?= $dataAttrs ?>
                                                    class="btn btn-light-danger w-100 fw-bold btn-informasi btn-ujian-ulang text-uppercase">
                                                    Ujian Ulang
                                                </a>
                                            <?php else : ?>
                                                <?php if ($u->nilai >= 60) : ?>
                                                    <button class="btn btn-light-success w-100 fw-bold cursor-default" disabled>Ujian Selesai</button>
                                                <?php else : ?>
                                                    <a href="<?= base_url('list-bimbel') ?>" class="btn btn-light-warning w-100 fw-bold text-uppercase">Beli Paket</a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-20">
                        <div class="text-center">
                            <img src="<?= base_url('assets/peserta/media/illustrations/sigma-1/13.png') ?>" class="mw-250px mb-10" alt="No Data" />

                            <h2 class="fw-bolder text-gray-900 mb-3">Belum Ada Ujian Tersedia</h2>
                            <p class="text-gray-500 fs-6 fw-semibold mb-8">
                                Sepertinya Anda belum memiliki akses ujian atau belum membeli paket.<br>
                                Silakan cek jadwal berkala atau beli paket terlebih dahulu.
                            </p>

                            <a href="<?= base_url('list-bimbel') ?>" class="btn btn-primary fw-bold px-8">
                                <i class="ki-outline ki-basket fs-2 me-2"></i> Lihat Paket
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="modal_verifikasi_wajah" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header border-0 py-5">
                <h2 class="fw-bolder mb-0">Verifikasi Identitas</h2>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>

            <div class="modal-body py-5 px-lg-17">
                <div class="text-center mb-9">
                    <div id="biometric_box" class="biometric-container warning">
                        <video id="webcam_video" class="biometric-video d-none" playsinline autoplay></video>
                        <canvas id="camera_canvas" class="biometric-canvas"></canvas>

                        <div class="biometric-overlay"></div>

                        <div class="scan-corner top-left"></div>
                        <div class="scan-corner top-right"></div>
                        <div class="scan-corner bottom-left"></div>
                        <div class="scan-corner bottom-right"></div>

                        <div class="laser-line" id="laser_scanner"></div>

                        <div class="position-absolute top-0 start-0 m-4 z-index-3">
                            <span class="badge bg-dark d-flex align-items-center px-3 py-2 bg-opacity-75 border border-secondary">
                                <span id="cam_indicator" class="bullet bullet-dot bg-warning me-2 animate-blink"></span>
                                <span class="fw-bold fs-9 text-white text-uppercase">AI Scanner</span>
                            </span>
                        </div>

                        <div class="position-absolute bottom-0 start-0 w-100 p-4 text-center z-index-3" style="background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0) 100%);">
                            <h5 class="text-white fw-bold mb-1" id="scan_text">Menginisialisasi Kamera...</h5>
                            <p class="text-muted fs-8 mb-2" id="scan_subtext">Mohon tunggu sebentar</p>
                            <div class="progress h-5px bg-dark w-75 mx-auto rounded-pill" id="scan_progress_container">
                                <div class="progress-bar bg-success rounded-pill transition-none" role="progressbar" id="scan_progress" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-7 mb-10">
                    <div class="col-6 col-md-3 text-center">
                        <div class="border border-dashed border-gray-300 rounded-3 py-4 px-3 h-100 bg-light-secondary hover-elevate-up transition-3d">
                            <i class="ki-outline ki-book-open fs-2x text-info mb-2"></i>
                            <div class="fs-6 fw-bolder text-gray-800 d-block" id="info_jumlah_soal">-</div>
                            <div class="fs-9 fw-bold text-gray-500 text-uppercase ls-1">Total Soal</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 text-center">
                        <div class="border border-dashed border-gray-300 rounded-3 py-4 px-3 h-100 bg-light-secondary hover-elevate-up transition-3d">
                            <i class="ki-outline ki-time fs-2x text-primary mb-2"></i>
                            <div class="fs-6 fw-bolder text-gray-800 d-block" id="info_durasi">-</div>
                            <div class="fs-9 fw-bold text-gray-500 text-uppercase ls-1">Waktu</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 text-center">
                        <div class="border border-dashed border-gray-300 rounded-3 py-4 px-3 h-100 bg-light-secondary hover-elevate-up transition-3d">
                            <i class="ki-outline ki-medal-star fs-2x text-warning mb-2"></i>
                            <div class="fs-6 fw-bolder text-gray-800 d-block" id="info_passing_grade">65</div>
                            <div class="fs-9 fw-bold text-gray-500 text-uppercase ls-1">Passing Grade</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 text-center">
                        <div class="border border-dashed border-gray-300 rounded-3 py-4 px-3 h-100 bg-light-secondary hover-elevate-up transition-3d">
                            <i class="ki-outline ki-arrows-loop fs-2x mb-2" id="icon_percobaan"></i>
                            <div class="fs-6 fw-bolder text-gray-800 d-block" id="info_percobaan">-</div>
                            <div class="fs-9 fw-bold text-gray-500 text-uppercase ls-1">Kuota Ujian</div>
                        </div>
                    </div>
                </div>
                <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">
                    <i class="ki-outline ki-information-5 fs-2tx text-warning me-4"></i>
                    <div class="d-flex flex-stack flex-grow-1 ">
                        <div class="fw-semibold">
                            <h4 class="text-gray-900 fw-bold">Penting Sebelum Memulai!</h4>
                            <div class="fs-7 text-gray-700">
                                <ul class="mb-0 ps-4">
                                    <li>Wajah harus terlihat jelas. Sistem akan memverifikasi otomatis.</li>
                                    <li>Sistem akan mengambil foto secara otomatis saat klik mulai.</li>
                                    <li>Dilarang menutup tab browser selama ujian berlangsung.</li>
                                    <li>Ujian berakhir otomatis jika waktu habis.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <canvas id="snapshot_canvas" style="display:none;"></canvas>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/face_mesh/face_mesh.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        let selectedHref = '';
        let idujian = '';
        let isProcessing = false;

        let camera = null;
        let faceMesh = null;

        const modalWajah = new bootstrap.Modal(document.getElementById('modal_verifikasi_wajah'));
        const videoElement = document.getElementById('webcam_video');
        const canvasElement = document.getElementById('camera_canvas');
        const canvasCtx = canvasElement.getContext('2d');
        const biometricBox = document.getElementById('biometric_box');

        let csrfName = '<?= csrf_token() ?>';
        let csrfHash = '<?= csrf_hash() ?>';

        // --- SISTEM TANTANGAN LIVENESS ---
        let currentChallenge = null;
        let challengeTimer = 0;
        const CHALLENGES = ['SMILE', 'BLINK']; // Senyum atau Kedip

        // Konstanta untuk mengukur geometri wajah (berdasarkan 468 titik)
        const LEFT_EYE_TOP = 159;
        const LEFT_EYE_BOTTOM = 145;
        const RIGHT_EYE_TOP = 386;
        const RIGHT_EYE_BOTTOM = 374;
        const LIP_LEFT = 61;
        const LIP_RIGHT = 291;
        const LIP_TOP = 13;
        const LIP_BOTTOM = 14;

        // 1. Inisialisasi MediaPipe Face Mesh
        faceMesh = new FaceMesh({
            locateFile: (file) => {
                return `https://cdn.jsdelivr.net/npm/@mediapipe/face_mesh/${file}`;
            }
        });

        faceMesh.setOptions({
            maxNumFaces: 1, // Tolak jika ada 2 orang
            refineLandmarks: true, // Penting untuk deteksi mata presisi
            minDetectionConfidence: 0.7,
            minTrackingConfidence: 0.7
        });

        faceMesh.onResults(handleFaceMeshResults);

        // 2. Trigger Buka Kamera
        $('.btn-informasi-mulai, .btn-informasi').click(function(e) {
            e.preventDefault();
            let btn = $(this); // Tangkap tombol yang sedang diklik

            // --- Fungsi untuk menjalankan kamera ---
            let lanjutkanBukaKamera = function() {
                selectedHref = btn.attr('href');
                idujian = btn.data('idujian');

                // --- UPDATE DATA MODAL SECARA DINAMIS BERDASARKAN TOMBOL ---
                $('#info_jumlah_soal').text(btn.data('soal'));
                $('#info_durasi').text(btn.data('waktu'));
                $('#info_percobaan').text(btn.data('kuota'));

                // Update warna ikon kuota (menghapus warna lama, memasukkan warna baru)
                let warna = btn.data('warna');
                $('#icon_percobaan').removeClass('text-success text-warning text-danger text-info').addClass('text-' + warna);
                // -----------------------------------------------------------

                resetToDefault();
                updateUI('warning', 'Menyalakan Kamera...', 'Izinkan akses kamera di browser Anda');

                modalWajah.show();
                startCamera();
            };

            // --- Cek apakah tombol yang diklik adalah "Ujian Ulang" ---
            if (btn.text().toLowerCase().includes('ujian ulang') || btn.hasClass('btn-ujian-ulang')) {
                // Tampilkan konfirmasi SweetAlert2 sebelum membuka kamera
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Mengerjakan ujian ulang akan merubah dan menggantikan hasil ujian Anda sebelumnya. Apakah Anda yakin ingin melanjutkan?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745', // Warna Hijau
                    cancelButtonColor: '#d33', // Warna Merah
                    confirmButtonText: '<i class="fas fa-camera"></i> Ya, Setuju',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        lanjutkanBukaKamera(); // Panggil fungsi buka kamera jika setuju
                    }
                });
            } else {
                // Jika Ujian Baru (Mulai), langsung buka kamera tanpa peringatan
                lanjutkanBukaKamera();
            }
        });

        function startCamera() {
            camera = new Camera(videoElement, {
                onFrame: async () => {
                    if (!isProcessing) {
                        if (canvasElement.width !== videoElement.videoWidth) {
                            canvasElement.width = videoElement.videoWidth;
                            canvasElement.height = videoElement.videoHeight;
                        }
                        canvasCtx.clearRect(0, 0, canvasElement.width, canvasElement.height);
                        canvasCtx.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);
                        await faceMesh.send({
                            image: canvasElement
                        });
                    }
                },
                width: 640,
                height: 480
            });

            camera.start().then(() => {
                $('#laser_scanner').show();
            });
        }

        // Fungsi Bantuan Matematika: Menghitung jarak antara 2 titik 3D
        function getDistance(p1, p2) {
            return Math.sqrt(Math.pow(p1.x - p2.x, 2) + Math.pow(p1.y - p2.y, 2));
        }

        // 3. Logika Analisis Face Mesh
        function handleFaceMeshResults(results) {
            if (isProcessing) return;

            // Jika wajah hilang / ada banyak wajah
            if (!results.multiFaceLandmarks || results.multiFaceLandmarks.length === 0) {
                return resetScan('Arahkan Wajah ke Kamera', 'Tatap layar perangkat Anda', 'warning');
            }
            if (results.multiFaceLandmarks.length > 1) {
                return resetScan('Peringatan: >1 Wajah', 'Pastikan Anda sendirian', 'danger');
            }

            const landmarks = results.multiFaceLandmarks[0];

            // A. Tentukan Tantangan Jika Belum Ada
            if (!currentChallenge) {
                // Pilih acak antara Senyum atau Kedip
                currentChallenge = CHALLENGES[Math.floor(Math.random() * CHALLENGES.length)];
                challengeTimer = Date.now();

                let text = currentChallenge === 'SMILE' ? 'Silakan Tersenyum Lebar' : 'Kedipkan Mata Anda';
                updateUI('info', text, 'Silahkan ikuti intruksi untuk verifikasi ujian');
                return; // Tunggu frame berikutnya
            }

            // B. Evaluasi Tantangan Liveness
            let challengePassed = false;
            let timeElapsed = Date.now() - challengeTimer;

            if (currentChallenge === 'SMILE') {
                // Rasio Lebar Bibir vs Tinggi Bibir
                const lipWidth = getDistance(landmarks[LIP_LEFT], landmarks[LIP_RIGHT]);
                const lipHeight = getDistance(landmarks[LIP_TOP], landmarks[LIP_BOTTOM]);
                // Jika bibir meregang (lebar bertambah proporsional) atau mulut sedikit terbuka
                if (lipWidth > 0.15 && lipHeight > 0.03) {
                    challengePassed = true;
                }
            } else if (currentChallenge === 'BLINK') {
                // Rasio Bukaan Mata Kiri & Kanan (Mata menutup saat rasio mengecil)
                const leftEyeOpen = getDistance(landmarks[LEFT_EYE_TOP], landmarks[LEFT_EYE_BOTTOM]);
                const rightEyeOpen = getDistance(landmarks[RIGHT_EYE_TOP], landmarks[RIGHT_EYE_BOTTOM]);
                // Mata dianggap menutup jika tinggi bukaan kurang dari threshold (misal 0.015)
                if (leftEyeOpen < 0.015 || rightEyeOpen < 0.015) {
                    challengePassed = true;
                }
            }

            // C. Jika Tantangan Terlewati
            if (challengePassed) {
                isProcessing = true;
                $('#laser_scanner').hide();
                $('#scan_progress_container').removeClass('d-none');
                $('#scan_progress').css('width', '100%');
                updateUI('success', 'Verifikasi Selesai', 'Foto diambil otomatis. Memproses data...');

                // Ambil gambar
                captureAndSend();
            }
            // D. Jika Tantangan Gagal / Waktu Habis (Lebih dari 10 detik diam)
            else if (timeElapsed > 10000) {
                resetScan('Waktu Habis', 'Sistem mendeteksi foto statis. Ulangi proses.', 'danger');
                setTimeout(() => {
                    currentChallenge = null;
                }, 2000); // Reset tantangan setelah 2 detik
            }
        }

        function resetScan(title, subtitle, status) {
            currentChallenge = null;
            updateUI(status, title, subtitle);
            $('#scan_progress_container').addClass('d-none');
            $('#scan_progress').css('width', '0%');
        }

        function resetToDefault() {
            isProcessing = false;
            currentChallenge = null;
            $('#scan_progress_container').addClass('d-none');
            $('#scan_progress').css('width', '0%');
        }

        function updateUI(status, title, subtitle) {
            biometricBox.className = `biometric-container ${status}`;
            let indicator = $('#cam_indicator');
            indicator.removeClass('bg-warning bg-danger bg-success bg-info');
            if (status === 'warning') indicator.addClass('bg-warning');
            if (status === 'danger') indicator.addClass('bg-danger');
            if (status === 'success') indicator.addClass('bg-success');
            if (status === 'info') indicator.addClass('bg-info');

            $('#scan_text').text(title);
            $('#scan_subtext').text(subtitle);
        }

        // 4. Pengiriman AJAX
        function captureAndSend() {
            // Kita butuh sedikit delay 500ms agar ekspresi (senyum/kedip) sempurna di kanvas
            setTimeout(() => {
                const imageData = canvasElement.toDataURL('image/jpeg', 0.85);
                const localTime = new Date().toLocaleString('sv-SE').replace(' ', 'T');

                let postData = {
                    'face_image': imageData,
                    'device_time': localTime,
                    'url': selectedHref,
                    'idujian': idujian
                };
                postData[csrfName] = csrfHash;

                $.ajax({
                    url: "<?= base_url('sw-siswa/ujian/proses-verifikasi') ?>",
                    type: 'POST',
                    data: postData,
                    dataType: 'json',
                    success: function(response) {
                        csrfHash = response.csrf_hash;
                        if (response.status === 'success') {
                            $(biometricBox).append('<div style="position:absolute;top:0;left:0;width:100%;height:100%;background:white;z-index:99;animation:flash 1s forwards;"></div>');
                            $('<style>@keyframes flash { 0%{opacity:1;} 100%{opacity:0;} }</style>').appendTo('head');

                            setTimeout(() => {
                                $('#modal_verifikasi_wajah').modal('hide');
                                Swal.fire({
                                    title: 'Verifikasi Berhasil',
                                    text: 'Mengalihkan ke lembar ujian...',
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.href = response.redirect;
                                });
                            }, 500);

                        } else {
                            Swal.fire('Gagal', response.message, 'error');
                            resetToDefault();
                            $('#laser_scanner').show();
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error Server', 'Terjadi kesalahan jaringan.', 'error');
                        resetToDefault();
                        $('#laser_scanner').show();
                    }
                });
            }, 500);
        }

        $('#modal_verifikasi_wajah').on('hidden.bs.modal', function() {
            if (camera) {
                camera.stop();
                camera = null; // Bersihkan dari memori
            }
            // Kosongkan canvas
            canvasCtx.clearRect(0, 0, canvasElement.width, canvasElement.height);

            resetToDefault();
            $('#laser_scanner').hide();
        });
    });
</script>
<?= $this->endSection(); ?>
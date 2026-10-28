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
    }

    #btn_capture_start {
        border-radius: 12px;
        transition: all 0.2s ease;
    }

    #btn_capture_start:hover {
        transform: scale(1.02);
    }

    .position-absolute.bottom-0 {
        z-index: 10;
    }

    .btn-upgrade-review:hover .group-hover-white i,
    .group-hover-white:hover i {
        color: #ffffff !important;
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

    .biometric-video,
    .biometric-canvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scaleX(-1);
    }

    .biometric-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at center, transparent 30%, rgba(0, 0, 0, 0.7) 80%);
        z-index: 2;
    }

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

    /* STYLING FILTER SCROLL (MOBILE FRIENDLY) */
    .filter-scroll {
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .filter-scroll::-webkit-scrollbar {
        display: none;
    }

    .btn-filter {
        border-radius: 20px;
    }
</style>

<?php if (strtolower(setting('midtrans_is_production')) == 'true'): ?>
    <script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="<?= setting('midtrans_client_key') ?>"></script>
<?php else: ?>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= setting('midtrans_client_key') ?>"></script>
<?php endif; ?>

<?= $this->endSection(); ?>
<?= $this->section('content'); ?>

<?php $db = Config\Database::connect(); ?>

<div class="d-flex flex-column flex-column-fluid py-3 py-lg-6">
    <div class="row g-6 g-xl-9">
        <div class="col-12 mb-2">
            <div class="alert bg-light-primary border border-primary border-dashed d-flex align-items-center p-5 mb-0">
                <i class="ki-duotone ki-information-5 fs-2hx text-primary me-4">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                </i>
                <div class="d-flex flex-column">
                    <h4 class="mb-1 text-gray-900">Informasi Sertifikasi</h4>
                    <span class="text-gray-700 mb-3">
                        Untuk memperoleh <strong class="text-gray-900">Sertifikat Brevet AB</strong>, Anda diwajibkan lulus pada 8 materi ujian yang telah ditentukan.
                    </span>
                    <div>
                        <a href="<?= base_url('sw-siswa/sertifikat') ?>" class="btn btn-primary btn-sm">Download Sertifikat</a>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($ujian)) : ?>

            <!-- TOMBOL FILTER KELAS -->
            <div class="col-12 mb-2">
                <div class="d-flex filter-scroll pb-2">
                    <!-- Tombol "Semua" defaultnya aktif (Solid Biru) -->
                    <button class="btn btn-primary fw-bold me-2 btn-filter flex-shrink-0 active" data-filter="semua">Semua</button>

                    <?php foreach ($ujian as $nama_kelas => $daftar_ujian) : ?>
                        <?php $kategori_slug = htmlspecialchars(str_replace(' ', '-', strtolower($nama_kelas))); ?>
                        <!-- Tombol lainnya defaultnya tidak aktif (Outline Biru) -->
                        <button class="btn btn-outline btn-outline-primary fw-bold me-2 btn-filter flex-shrink-0" data-filter="<?= $kategori_slug ?>">
                            <?= htmlspecialchars($nama_kelas) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- LIST UJIAN -->
            <div class="row g-6 g-xl-9 w-100 m-0" id="exam-container">
                <?php foreach ($ujian as $nama_kelas => $daftar_ujian) : ?>
                    <?php $kategori_slug = htmlspecialchars(str_replace(' ', '-', strtolower($nama_kelas))); ?>

                    <?php foreach ($daftar_ujian as $u) : ?>
                        <?php
                        $total =  $u->total_soal;
                        $totalMenit = $total * $u->waktu_per_soal;
                        $start = (date('Y-m-d H:i'));
                        $end_ = (date('Y-m-d H:i', strtotime("+ $totalMenit minutes")));
                        $durasi = date_diff(date_create($start), date_create($end_));

                        $roleAccess = session()->get('role_access') ?? 0;
                        $sisa_kuota = $u->kuota ?? 0;
                        $total_kuota = $u->kuota_master ?? 0;
                        $terpakai = $total_kuota - $sisa_kuota;

                        if ($roleAccess == 1) {
                            $tampil_kuota = 'U';
                            $teks_modal_kuota = 'Tanpa Batas';
                            $kuotaColor = 'success';
                        } else {
                            $tampil_kuota = $terpakai . '/' . $total_kuota;
                            $teks_modal_kuota = $terpakai . ' dari ' . $total_kuota;

                            $kuotaColor = 'success';
                            if ($terpakai >= $total_kuota) {
                                $kuotaColor = 'danger';
                            } elseif ($terpakai > 0) {
                                $kuotaColor = 'warning';
                            }
                        }

                        $dataAttrs = 'data-idujian="' . encrypt_url($u->id_ujian) . '" ' .
                            'data-kuota="' . $teks_modal_kuota . '" ' .
                            'data-warna="' . $kuotaColor . '" ' .
                            'data-soal="' . $u->total_soal . ' soal" ' .
                            'data-waktu="' . $totalMenit . ' menit"';
                        ?>

                        <div class="col-md-6 col-xl-4 exam-item" data-kategori="<?= $kategori_slug ?>">
                            <div class="card border-0 shadow-sm card-xl-stretch mb-xl-8 hover-elevate-up d-flex flex-column h-100">
                                <div class="card-header border-0 p-0 min-h-150px overlay overflow-hidden rounded-top">
                                    <?= img_lazy('uploads/mapel/' . $u->file, "loading", ['class' => 'w-100 h-100 object-fit-cover']) ?>
                                    <div class="overlay-layer bg-dark bg-opacity-10"></div>

                                    <div class="position-absolute top-0 end-0 m-4">
                                        <?= $u->nilai == null ? '' : ($u->nilai >= 60
                                            ? '<span class="badge badge-success fw-bold uppercase px-4 py-3">Lulus</span>'
                                            : '<span class="badge badge-danger fw-bold uppercase px-4 py-3">Tidak Lulus</span>'); ?>
                                    </div>
                                </div>

                                <div class="card-body pt-5 d-flex flex-column">
                                    <div class="d-flex flex-stack mb-2">
                                        <span class="text-primary fw-bold fs-7 uppercase"><?= $u->nama_kelas ?></span>
                                        <span class="text-gray-500 fs-8 fw-bold">
                                            <i class="ki-duotone ki-time fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>
                                            <?= ($durasi != '0' ? (($durasi->h * 60) + $durasi->i) : '0') ?> Menit
                                        </span>
                                    </div>

                                    <a href="#" class="fs-4 text-gray-900 fw-bolder text-hover-primary lh-base d-block mb-2 h-50px">
                                        <?= $u->nama_ujian ?>
                                    </a>

                                    <!-- KUNCI / UPGRADE JAWABAN AREA -->
                                    <div class="mb-5 border-bottom border-gray-200 pb-4">
                                        <?php if ($u->review === '1'): ?>
                                            <div class="d-flex align-items-center text-success fw-bold fs-7 bg-light-success p-2 rounded">
                                                <i class="ki-outline ki-unlock fs-4 text-success me-2"></i> Pembahasan Terbuka
                                            </div>
                                        <?php else: ?>

                                            <!-- LOGIKA BARU: Jika peserta sudah punya token transaksi yang belum dibayar -->
                                            <?php if (!empty($u->token_pending)): ?>
                                                <button type="button"
                                                    class="btn btn-sm btn-light-warning fw-bold text-uppercase d-flex align-items-center justify-content-between w-100 btn-bayar-pending group-hover-white"
                                                    data-token="<?= $u->token_pending ?>">
                                                    <span><i class="ki-outline ki-wallet fs-4 me-2 text-warning"></i> Selesaikan Pembayaran</span>
                                                    <span class="badge badge-warning">Pending</span>
                                                </button>

                                                <!-- Jika belum punya transaksi, tampilkan tombol beli/upgrade biasa -->
                                            <?php elseif (isset($u->biaya_upgrade) && $u->biaya_upgrade > 0 && $u->status != 'B' && $u->status != 'U'): ?>
                                                <button type="button"
                                                    class="btn btn-light-primary border border-primary border-dashed fw-bold d-flex align-items-center justify-content-center justify-content-sm-between flex-wrap gap-2 w-100 btn-upgrade-review p-3 hover-elevate-up"
                                                    data-id="<?= encrypt_url($u->id_ujian) ?>"
                                                    data-kode="<?= encrypt_url($u->kode_ujian) ?>"
                                                    data-nama="<?= htmlspecialchars($u->nama_ujian) ?>"
                                                    data-harga="<?= $u->biaya_upgrade ?>">

                                                    <!-- Bagian Kiri: Ikan dan Teks -->
                                                    <!-- Tambahkan selector CSS di dalam class atau style agar ikon ikut putih saat hover -->
                                                    <div class="d-flex align-items-center justify-content-center justify-content-sm-start flex-grow-1 group-hover-white">
                                                        <i class="ki-outline ki-lock-2 fs-2 me-3 text-primary transition-color"></i>
                                                        <span class="fs-6 text-start">Buka Kunci Jawaban & Pembahasan</span>
                                                    </div>
                                                </button>
                                            <?php else: ?>
                                                <?php if ($u->status == 'B'): ?>
                                                    <!-- 1. Tampilan jika peserta BELUM MULAI ujian -->
                                                    <div class="d-flex align-items-center justify-content-center text-gray-600 fw-semibold fs-7 bg-light-secondary border border-secondary border-dashed p-3 rounded w-100">
                                                        <i class="ki-outline ki-information-5 fs-3 text-gray-500 me-2"></i>
                                                        <span>Segera Kerjakan ujian</span>
                                                    </div>

                                                <?php elseif ($u->status == 'U'): ?>
                                                    <!-- 2. Tampilan jika peserta SEDANG ujian -->
                                                    <div class="d-flex align-items-center justify-content-center text-warning fw-semibold fs-7 bg-light-warning border border-warning border-dashed p-3 rounded w-100">
                                                        <i class="ki-outline ki-timer fs-3 text-warning me-2 animate-blink"></i>
                                                        <span>Selesaikan ujian</span>
                                                    </div>

                                                <?php else: ?>
                                                    <!-- 3. Tampilan jika sudah selesai, tapi pembahasan memang tidak tersedia (Misal: harga 0) -->
                                                    <div class="d-flex align-items-center justify-content-center text-muted fw-semibold fs-7 bg-light p-3 rounded w-100" data-bs-toggle="tooltip" title="Fitur pembahasan tidak aktif untuk paket ini">
                                                        <i class="ki-outline ki-lock-2 fs-3 text-muted me-2"></i>
                                                        <span>Pembahasan Tidak Tersedia</span>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                        <?php endif; ?>
                                    </div>

                                    <div class="d-flex align-items-center flex-wrap gap-2 mb-6">
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

                                    <!-- TOMBOL AKSI UTAMA BAWAH -->
                                    <div class="d-flex gap-2 mt-auto">
                                        <?php if ($u->status == 'B') : ?>
                                            <?php $dataStatus = $db->query("SELECT * FROM status_ujian WHERE kode_ujian = ?", [$u->kode_ujian])->getRow(); ?>
                                            <?php if (!empty($dataStatus) && $dataStatus->status == 'A') : ?>
                                                <a href="<?= base_url('sw-siswa/ujian/lihat-pg') . '/' . encrypt_url($u->kode_ujian) . '/' . encrypt_url(session()->get('id')) . '/' . encrypt_url($u->id_ujian); ?>"
                                                    <?= $dataAttrs ?>
                                                    class="btn btn-primary flex-grow-1 fw-bold btn-informasi-mulai">
                                                    Mulai Ujian
                                                </a>
                                            <?php else : ?>
                                                <button class="btn btn-light flex-grow-1 fw-bold btn-informasi-disabled" disabled>Belum Dibuka</button>
                                            <?php endif; ?>
                                        <?php elseif ($u->status == 'U') : ?>
                                            <a href="<?= base_url('sw-siswa/ujian/lihat-pg') . '/' . encrypt_url($u->kode_ujian) . '/' . encrypt_url(session()->get('id')) . '/' . encrypt_url($u->id_ujian); ?>"
                                                class="btn btn-warning flex-grow-1 fw-bold">Sedang Ujian</a>
                                        <?php else : ?>
                                            <?php if ($roleAccess == 1 || $u->kuota != '0') : ?>
                                                <a href="<?= base_url('sw-siswa/ujian/remedial') . '/' . encrypt_url($u->id_ujian) . '/' . encrypt_url($u->kode_ujian) . '/' . $u->status ?>"
                                                    <?= $dataAttrs ?>
                                                    class="btn btn-light-danger fw-bold btn-informasi btn-ujian-ulang text-uppercase d-flex align-items-center justify-content-center fs-8 fs-sm-7 px-2 flex-grow-1">
                                                    Ujian Ulang
                                                </a>
                                            <?php else : ?>
                                                <button class="btn btn-light-success fw-bold cursor-default text-uppercase d-flex align-items-center justify-content-center fs-8 fs-sm-7 px-2 flex-grow-1" disabled>
                                                    Ujian Selesai
                                                </button>
                                            <?php endif; ?>

                                            <?php if ($u->review === '1'): ?>
                                                <a href="<?= base_url('sw-siswa/ujian/review-ujian') . '/' . encrypt_url($u->kode_ujian) ?>"
                                                    class="btn btn-light-success fw-bold text-uppercase d-flex align-items-center justify-content-center fs-8 fs-sm-7 px-2 lh-sm text-center"
                                                    style="min-width: 100px;">
                                                    Review
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>

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
    <!-- MODAL KAMERA VERIFIKASI WAJAH TETAP SAMA SEPERTI SEBELUMNYA -->
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

        // ==========================================
        // FITUR FILTER KATEGORI KELAS
        // ==========================================
        $('.btn-filter').click(function() {
            // 1. Kembalikan SEMUA tombol ke mode tidak aktif (Outline Biru)
            $('.btn-filter').removeClass('btn-primary active').addClass('btn-outline btn-outline-primary');

            // 2. Ubah HANYA tombol yang diklik menjadi mode aktif (Solid Biru)
            $(this).removeClass('btn-outline btn-outline-primary').addClass('btn-primary active');

            // Logika filter data
            let filter = $(this).data('filter');

            if (filter === 'semua') {
                $('.exam-item').fadeIn(300);
            } else {
                $('.exam-item').hide();
                $('.exam-item[data-kategori="' + filter + '"]').fadeIn(300);
            }
        });

        // (LOGIKA KAMERA FACEMESH TETAP UTUH, DIBIARKAN SAMA SEPERTI SEBELUMNYA)
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
        let currentChallenge = null;
        let challengeTimer = 0;
        const CHALLENGES = ['SMILE', 'BLINK'];
        const LEFT_EYE_TOP = 159;
        const LEFT_EYE_BOTTOM = 145;
        const RIGHT_EYE_TOP = 386;
        const RIGHT_EYE_BOTTOM = 374;
        const LIP_LEFT = 61;
        const LIP_RIGHT = 291;
        const LIP_TOP = 13;
        const LIP_BOTTOM = 14;

        faceMesh = new FaceMesh({
            locateFile: (file) => {
                return `https://cdn.jsdelivr.net/npm/@mediapipe/face_mesh/${file}`;
            }
        });
        faceMesh.setOptions({
            maxNumFaces: 1,
            refineLandmarks: true,
            minDetectionConfidence: 0.7,
            minTrackingConfidence: 0.7
        });
        faceMesh.onResults(handleFaceMeshResults);

        $('.btn-informasi-mulai, .btn-informasi').click(function(e) {
            e.preventDefault();
            let btn = $(this);
            let lanjutkanBukaKamera = function() {
                selectedHref = btn.attr('href');
                idujian = btn.data('idujian');
                $('#info_jumlah_soal').text(btn.data('soal'));
                $('#info_durasi').text(btn.data('waktu'));
                $('#info_percobaan').text(btn.data('kuota'));
                let warna = btn.data('warna');
                $('#icon_percobaan').removeClass('text-success text-warning text-danger text-info').addClass('text-' + warna);
                resetToDefault();
                updateUI('warning', 'Menyalakan Kamera...', 'Izinkan akses kamera di browser Anda');
                modalWajah.show();
                startCamera();
            };

            if (btn.text().toLowerCase().includes('ujian ulang') || btn.hasClass('btn-ujian-ulang')) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Mengerjakan ujian ulang akan merubah hasil ujian sebelumnya. Yakin?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '<i class="fas fa-camera"></i> Ya, Setuju',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        lanjutkanBukaKamera();
                    }
                });
            } else {
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

        function getDistance(p1, p2) {
            return Math.sqrt(Math.pow(p1.x - p2.x, 2) + Math.pow(p1.y - p2.y, 2));
        }

        function handleFaceMeshResults(results) {
            if (isProcessing) return;
            if (!results.multiFaceLandmarks || results.multiFaceLandmarks.length === 0) return resetScan('Arahkan Wajah ke Kamera', 'Tatap layar perangkat Anda', 'warning');
            if (results.multiFaceLandmarks.length > 1) return resetScan('Peringatan: >1 Wajah', 'Pastikan Anda sendirian', 'danger');
            const landmarks = results.multiFaceLandmarks[0];
            if (!currentChallenge) {
                currentChallenge = CHALLENGES[Math.floor(Math.random() * CHALLENGES.length)];
                challengeTimer = Date.now();
                let text = currentChallenge === 'SMILE' ? 'Silakan Tersenyum Lebar' : 'Kedipkan Mata Anda';
                updateUI('info', text, 'Silahkan ikuti intruksi untuk verifikasi ujian');
                return;
            }
            let challengePassed = false;
            let timeElapsed = Date.now() - challengeTimer;
            if (currentChallenge === 'SMILE') {
                const lipWidth = getDistance(landmarks[LIP_LEFT], landmarks[LIP_RIGHT]);
                const lipHeight = getDistance(landmarks[LIP_TOP], landmarks[LIP_BOTTOM]);
                if (lipWidth > 0.15 && lipHeight > 0.03) challengePassed = true;
            } else if (currentChallenge === 'BLINK') {
                const leftEyeOpen = getDistance(landmarks[LEFT_EYE_TOP], landmarks[LEFT_EYE_BOTTOM]);
                const rightEyeOpen = getDistance(landmarks[RIGHT_EYE_TOP], landmarks[RIGHT_EYE_BOTTOM]);
                if (leftEyeOpen < 0.015 || rightEyeOpen < 0.015) challengePassed = true;
            }
            if (challengePassed) {
                isProcessing = true;
                $('#laser_scanner').hide();
                $('#scan_progress_container').removeClass('d-none');
                $('#scan_progress').css('width', '100%');
                updateUI('success', 'Verifikasi Selesai', 'Foto diambil otomatis. Memproses data...');
                captureAndSend();
            } else if (timeElapsed > 10000) {
                resetScan('Waktu Habis', 'Sistem mendeteksi foto statis. Ulangi proses.', 'danger');
                setTimeout(() => {
                    currentChallenge = null;
                }, 2000);
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

        function captureAndSend() {
            setTimeout(() => {
                const imageData = canvasElement.toDataURL('image/jpeg', 0.85);
                const localTime = new Date().toLocaleString('sv-SE').replace(' ', 'T');
                let postData = {
                    'face_image': imageData,
                    'device_time': localTime,
                    'url': selectedHref,
                    'idujian': idujian
                };
                let ajaxCsrfName = '<?= csrf_token() ?>';
                let ajaxCsrfHash = $('input[name="' + ajaxCsrfName + '"]').val() || '<?= csrf_hash() ?>';
                postData[ajaxCsrfName] = ajaxCsrfHash;
                $.ajax({
                    url: "<?= base_url('sw-siswa/ujian/proses-verifikasi') ?>",
                    type: 'POST',
                    data: postData,
                    dataType: 'json',
                    success: function(response) {
                        if (response[ajaxCsrfName]) {
                            $('input[name="' + ajaxCsrfName + '"]').val(response[ajaxCsrfName]);
                        }
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
                camera = null;
            }
            canvasCtx.clearRect(0, 0, canvasElement.width, canvasElement.height);
            resetToDefault();
            $('#laser_scanner').hide();
        });
    });
</script>

<script>
    $(document).ready(function() {

        // ==========================================
        // JIKA TOMBOL "LANJUTKAN PEMBAYARAN" DI KLIK (TRANSAKSI PENDING)
        // ==========================================
        $(document).on('click', '.btn-bayar-pending', function(e) {
            e.preventDefault();
            let pendingToken = $(this).data('token');

            // Langsung panggil Snap tanpa perlu insert AJAX ke database lagi
            snap.pay(pendingToken, {
                onSuccess: function(result) {
                    Swal.fire('Berhasil!', 'Pembayaran berhasil. Akses pembahasan telah dibuka.', 'success')
                        .then(() => location.reload());
                },
                onPending: function(result) {
                    Swal.fire('Menunggu Pembayaran', 'Silakan selesaikan pembayaran Anda di menu tagihan.', 'warning')
                        .then(() => location.reload());
                },
                onError: function(result) {
                    Swal.fire('Gagal!', 'Pembayaran gagal, silakan coba lagi.', 'error');
                },
                onClose: function() {}
            });
        });

        // ==========================================
        // JIKA TOMBOL "UPGRADE REVIEW" DI KLIK (TRANSAKSI BARU)
        // ==========================================
        $(document).on('click', '.btn-upgrade-review', function(e) {
            e.preventDefault();

            let id_ujian = $(this).data('id');
            let kode_ujian = $(this).data('kode');
            let nama_ujian = $(this).data('nama');
            let harga = $(this).data('harga');
            let hargaFormat = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(harga);

            Swal.fire({
                title: '<span class="fs-3 fw-bolder text-gray-900 mt-2 d-block">Upgrade Akses Pembahasan</span>',
                html: `
        <style>
            /* Mencegah scrollbar muncul dan memaksa tinggi otomatis mengikuti konten */
            .swal2-html-container {
                overflow: visible !important;
                max-height: none !important;
                margin-top: 10px !important;
            }
        </style>
        
        <div class="text-start">
            <!-- Info Ujian -->
            <div class="d-flex align-items-center bg-light-primary rounded-4 p-4 mb-5">
                <div class="symbol symbol-40px symbol-circle me-4">
                    <span class="symbol-label bg-primary text-white fw-bold">
                        <i class="ki-outline ki-award fs-3"></i>
                    </span>
                </div>
                <div class="flex-grow-1">
                    <span class="text-primary fs-8 d-block fw-bold text-uppercase ls-1 mb-1">Ujian Dipilih</span>
                    <strong class="text-gray-900 fs-6">${nama_ujian}</strong>
                </div>
            </div>

            <!-- List Keuntungan -->
            <div class="mb-6 px-2">
                <p class="text-gray-800 fs-7 fw-bolder mb-3">Keuntungan yang Anda dapatkan:</p>
                <div class="vstack gap-3">
                    <div class="d-flex align-items-start">
                        <i class="ki-outline ki-check-circle fs-2 text-success me-3 mt-1"></i>
                        <span class="text-gray-700 fs-7 lh-sm">Akses penuh kunci jawaban yang benar</span>
                    </div>
                    <div class="d-flex align-items-start">
                        <i class="ki-outline ki-check-circle fs-2 text-success me-3 mt-1"></i>
                        <span class="text-gray-700 fs-7 lh-sm">Penjelasan & pembahasan lengkap setiap soal</span>
                    </div>
                    <div class="d-flex align-items-start">
                        <i class="ki-outline ki-check-circle fs-2 text-success me-3 mt-1"></i>
                        <span class="text-gray-700 fs-7 lh-sm">Evaluasi dan analisis materi secara detail</span>
                    </div>
                </div>
            </div>

            <!-- Box Harga Modern -->
            <div class="bg-light-info border border-info border-dashed p-4 text-center rounded-4">
                <span class="text-info fs-8 fw-bolder text-uppercase ls-1 d-block mb-1">Total Biaya Upgrade</span>
                <span class="text-gray-900 fw-bolder fs-1">${hargaFormat}</span>
            </div>
        </div>
    `,
                icon: undefined,
                showCancelButton: true,
                confirmButtonText: '<i class="ki-outline ki-wallet fs-4 me-2"></i> Bayar Sekarang',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                width: '500px', // Diperlebar sedikit agar teks tidak terlalu cepat turun ke baris baru
                padding: '2em', // Memberikan ruang kosong yang nyaman di sekeliling popup
                customClass: {
                    popup: "rounded-4 shadow-sm border border-gray-200",
                    confirmButton: "btn btn-primary fw-bold px-6 py-3 me-3 fs-7 rounded-3",
                    cancelButton: "btn btn-light-danger text-danger fw-bold px-6 py-3 fs-7 rounded-3" // Dibuat merah pudar agar lebih kontras
                }
            }).then((result) => {
                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Memproses Pesanan...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    let csrfName = '<?= csrf_token() ?>';
                    let csrfHash = $('input[name="' + csrfName + '"]').val() || '<?= csrf_hash() ?>';

                    let payloadData = {
                        id_ujian: id_ujian,
                        kode_ujian: kode_ujian
                    };
                    payloadData[csrfName] = csrfHash;

                    $.ajax({
                        url: "<?= base_url('sw-siswa/ujian/upgrade-ujian') ?>",
                        type: "POST",
                        dataType: "json",
                        data: payloadData,
                        success: function(response) {
                            if (response[csrfName]) {
                                $('input[name="' + csrfName + '"]').val(response[csrfName]);
                            }
                            if (response.status === 'success' && response.snap_token) {
                                Swal.close();
                                snap.pay(response.snap_token, {
                                    onSuccess: function(result) {
                                        Swal.fire('Berhasil!', 'Pembayaran berhasil. Akses pembahasan telah dibuka.', 'success');
                                        window.location.href = "<?= base_url('sw-siswa/ujian') ?>";
                                    },
                                    onPending: function(result) {
                                        Swal.fire({
                                            title: 'Menunggu Pembayaran',
                                            text: 'Silakan selesaikan pembayaran sesuai metode yang Anda pilih.',
                                            icon: 'info',
                                            confirmButtonText: 'Lihat Tagihan'
                                        }).then(() => {
                                            window.location.href = "<?= base_url('sw-siswa/transaksi') ?>";
                                        });
                                    },
                                    // 3. JIKA PEMBAYARAN GAGAL
                                    onError: function(result) {
                                        window.location.href = "<?= base_url('sw-siswa/ujian') ?>";
                                    },

                                    // 4. JIKA USER MENUTUP POPUP SEBELUM MEMBAYAR
                                    onClose: function() {
                                        window.location.href = "<?= base_url('sw-siswa/ujian') ?>";
                                    }
                                });
                            } else {
                                Swal.fire('Error!', response.message || 'Terjadi kesalahan saat memproses pesanan.', 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            let errorMessage = 'Gagal terhubung ke server.';
                            if (xhr.status === 403) {
                                errorMessage = 'Token Keamanan (CSRF) kedaluwarsa atau tidak valid. Halaman akan dimuat ulang.';
                                Swal.fire({
                                    title: 'Akses Ditolak',
                                    text: errorMessage,
                                    icon: 'warning'
                                }).then(() => {
                                    location.reload();
                                });
                                return;
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                title: 'Error Server (' + xhr.status + ')',
                                text: errorMessage,
                                icon: 'error',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: "btn btn-danger fw-bold"
                                }
                            });
                        }
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>
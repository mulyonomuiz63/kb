<?= $this->extend('siswa/template/app'); ?>

<?= $this->section('styles'); ?>
<style>
    /* Efek Berkedip Badge Live */
    .animate-blink {
        animation: blink-animation 1.2s steps(5, start) infinite;
    }
    @keyframes blink-animation {
        to { visibility: hidden; }
    }

    /* Memastikan video memenuhi container kamera */
    #camera_preview video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        transform: scaleX(-1); /* Efek Cermin */
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
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>

<?php $db = Config\Database::connect(); ?>

<div class="d-flex flex-column flex-column-fluid py-3 py-lg-6 mt-8">
    <div class="row g-6 g-xl-9">
        <?php if (!empty($ujian)) : ?>
            <?php foreach ($ujian as $u) : ?>
                <?php
                $total = 0;
                $ujianDetail = $db->query("select * from ujian_detail where kode_ujian='$u->kode_ujian'")->getResult();
                foreach ($ujianDetail as $dataRows) {
                    $total++;
                }
                $totalMenit = $total * 3;
                $start = (date('Y-m-d H:i'));
                $end_ = (date('Y-m-d H:i', strtotime("+ $totalMenit minutes")));
                $durasi = date_diff(date_create($start), date_create($end_));
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
                                        <span class="symbol-label bg-light-info text-info fw-bold fs-8"><?= $u->kuota ?></span>
                                    </div>
                                    <div>
                                        <div class="fs-7 text-gray-800 fw-bold"> Kuota</div>
                                        <div class="fs-8 text-gray-500 fw-semibold">Sisa Kuota</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-35px symbol-circle me-3">
                                        <span class="symbol-label bg-light-warning text-warning fw-bold fs-8"><?= $u->nilai == null ? '-' : $u->nilai ?></span>
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
                                        <?php $dataStatus = $db->query("select * from status_ujian where kode_ujian='$u->kode_ujian'")->getRow(); ?>
                                        <?php if (!empty($dataStatus) && $dataStatus->status == 'A') : ?>
                                            <a href="<?= base_url('sw-siswa/ujian/lihat-pg') . '/' . encrypt_url($u->kode_ujian) . '/' . encrypt_url(session()->get('id')) . '/' . encrypt_url($u->id_ujian); ?>"
                                                data-idujian="<?= encrypt_url($u->id_ujian) ?>"
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
                                        <?php if ($u->kuota != '0') : ?>
                                            <a href="<?= base_url('sw-siswa/ujian/remedial') . '/' . encrypt_url($u->id_ujian) . '/' . encrypt_url($u->kode_ujian) . '/' . $u->status ?>"
                                                data-idujian="<?= encrypt_url($u->id_ujian) ?>"
                                                class="btn btn-light-danger w-100 fw-bold btn-informasi text-uppercase">
                                                Ujian Ulang
                                            </a>
                                        <?php else : ?>
                                            <?php if ($u->nilai >= 60) : ?>
                                                <button class="btn btn-light-success w-100 fw-bold cursor-default" disabled>Ujian Selesai</button>
                                            <?php else : ?>
                                                <a href="<?= base_url('/#bimbel') ?>" class="btn btn-light-warning w-100 fw-bold btn-ujian-ulang text-uppercase">Beli Paket</a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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

                            <a href="<?= base_url('/#bimbel') ?>" class="btn btn-primary fw-bold px-8">
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
                    <div class="position-relative d-inline-block shadow-lg rounded-4 overflow-hidden border border-4 border-white" style="width: 100%; max-width: 500px;">
                        
                        <div id="camera_preview" style="aspect-ratio: 4/3; background: #1e1e2d;"></div>
                        
                        <div class="position-absolute top-0 start-0 m-4">
                            <span class="badge badge-success d-flex align-items-center px-3 py-2 opacity-75">
                                <span class="bullet bullet-dot bg-white me-2 animate-blink"></span>
                                <span class="fw-bold fs-9 text-uppercase">Kamera Aktif</span>
                            </span>
                        </div>

                        <div class="position-absolute bottom-0 start-0 w-100 p-6" style="background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);">
                            <button type="button" id="btn_capture_start" class="btn btn-primary fw-bold w-100 py-2 shadow-sm">
                                <span class="indicator-label">
                                    <i class="ki-outline ki-camera fs-2 me-2"></i>Ambil Foto & Mulai Ujian
                                </span>
                                <span class="indicator-progress">
                                    Memproses... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row g-7 mb-10">
                    <?php 
                        $stats = [
                            ['icon' => 'ki-book-open', 'color' => 'info', 'label' => 'Total Soal', 'val' => '30 soal', 'id' => 'info_jumlah_soal'],
                            ['icon' => 'ki-time', 'color' => 'primary', 'label' => 'Waktu', 'val' => '90 menit', 'id' => 'info_durasi'],
                            ['icon' => 'ki-medal-star', 'color' => 'warning', 'label' => 'Passing Grade', 'val' => '65', 'id' => 'info_passing_grade'],
                            ['icon' => 'ki-arrows-loop', 'color' => 'danger', 'label' => 'Percobaan', 'val' => '0/3', 'id' => 'info_percobaan'],
                        ];
                        foreach($stats as $s): 
                    ?>
                    <div class="col-6 col-md-3 text-center">
                        <div class="border border-dashed border-gray-300 rounded-3 py-4 px-3 h-100 bg-light-secondary hover-elevate-up transition-3d">
                            <i class="ki-outline <?= $s['icon'] ?> fs-2x text-<?= $s['color'] ?> mb-2"></i>
                            <div class="fs-6 fw-bolder text-gray-800 d-block" id="<?= $s['id'] ?>"><?= $s['val'] ?></div>
                            <div class="fs-9 fw-bold text-gray-500 text-uppercase ls-1"><?= $s['label'] ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
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
<script>
    $(document).ready(function() {
        let selectedHref = '';
        let idujian = '';
        let videoStream = null;
        const modalWajah = new bootstrap.Modal(document.getElementById('modal_verifikasi_wajah'));

        // Ambil token CSRF awal dari fungsi CI4
        let csrfName = '<?= csrf_token() ?>';
        let csrfHash = '<?= csrf_hash() ?>';

        $('.btn-informasi-mulai, .btn-informasi').click(function(e) {
            e.preventDefault();
            selectedHref = $(this).attr('href');
            idujian = $(this).data('idujian');
            modalWajah.show();
            startCamera();
        });

        $('#btn_capture_start').click(function() {
            const btn = $(this);
            const canvas = document.getElementById('snapshot_canvas');
            const video = document.querySelector('#camera_preview video');

            if (!video) return;

            btn.attr('data-kt-indicator', 'on').prop('disabled', true);

            // Snapshot proses
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);

            const imageData = canvas.toDataURL('image/jpeg', 0.8);
            const localTime = new Date().toLocaleString('sv-SE').replace(' ', 'T');

            // Data yang akan dikirim
            let postData = {
                'face_image': imageData,
                'device_time': localTime,
                'url': selectedHref,
                'idujian': idujian
            };
            postData[csrfName] = csrfHash; // Memasukkan CSRF Token ke dalam data POST

            $.ajax({
                url: "<?= base_url('sw-siswa/ujian/proses-verifikasi') ?>",
                type: 'POST',
                data: postData,
                dataType: 'json',
                success: function(response) {
                    csrfHash = response.csrf_hash;

                    if (response.status === 'success') {
                        $('#modal_verifikasi_wajah').modal('hide');
                        Swal.fire({
                            title: 'Verifikasi Berhasil!',
                            text: 'Identitas Anda telah terverifikasi. Halaman akan segera dialihkan ke lembar ujian.',
                            icon: 'success',
                            showConfirmButton: false, // Sembunyikan tombol OK agar terlihat otomatis
                            timer: 3000, // Beri jeda 2 detik sebelum redirect
                            timerProgressBar: true,
                            customClass: {
                                popup: 'rounded-4', // Agar sesuai dengan gaya Metronic
                            }
                        }).then(() => {
                            window.location.href = response.redirect;
                        });

                    } else {
                        // Menampilkan pesan error spesifik dari Controller
                        Swal.fire({
                            title: 'Verifikasi Gagal',
                            text: response.message || 'Terjadi kesalahan saat memproses data.',
                            icon: 'error',
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                        btn.removeAttr('data-kt-indicator').prop('disabled', false);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 403) {
                        Swal.fire('Sesi Habis', 'Halaman akan dimuat ulang untuk memperbarui token keamanan.', 'info')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error', 'Terjadi kesalahan sistem (Server Error).', 'error');
                        btn.removeAttr('data-kt-indicator').prop('disabled', false);
                    }
                }
            });
        });

        async function startCamera() {
            try {
                videoStream = await navigator.mediaDevices.getUserMedia({
                    video: true
                });
                const video = document.createElement('video');
                video.srcObject = videoStream;
                video.setAttribute('autoplay', '');
                video.setAttribute('muted', '');
                video.setAttribute('playsinline', '');
                video.classList.add('w-100', 'h-100', 'object-fit-cover');
                const previewContainer = document.getElementById('camera_preview');
                previewContainer.innerHTML = '';
                previewContainer.appendChild(video);
            } catch (err) {
                Swal.fire('Akses Kamera Ditolak', 'Aktifkan izin kamera pada browser Anda.', 'error');
                modalWajah.hide();
            }
        }

        $('#modal_verifikasi_wajah').on('hidden.bs.modal', function() {
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
            }
        });
    });
</script>
<?= $this->endSection(); ?>
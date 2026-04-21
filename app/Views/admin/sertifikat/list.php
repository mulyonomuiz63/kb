<?= $this->extend('template/app'); ?>

<?= $this->section('styles'); ?>
<style>
    /* Efek Berkedip Badge Live */
    .animate-blink {
        animation: blink-animation 1.2s steps(5, start) infinite;
    }

    @keyframes blink-animation {
        to { visibility: hidden; }
    }

    /* Styling Area Biometrik (AI Face Mesh) */
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

    .biometric-video, .biometric-canvas {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        object-fit: cover;
        transform: scaleX(-1);
    }

    .biometric-overlay {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: radial-gradient(circle at center, transparent 30%, rgba(0, 0, 0, 0.7) 80%);
        z-index: 2;
    }

    .scan-corner {
        position: absolute;
        width: 40px; height: 40px;
        border-color: #00ff00;
        border-style: solid;
        z-index: 3;
        transition: border-color 0.3s;
    }

    .scan-corner.top-left { top: 15%; left: 15%; border-width: 4px 0 0 4px; border-top-left-radius: 10px; }
    .scan-corner.top-right { top: 15%; right: 15%; border-width: 4px 4px 0 0; border-top-right-radius: 10px; }
    .scan-corner.bottom-left { bottom: 25%; left: 15%; border-width: 0 0 4px 4px; border-bottom-left-radius: 10px; }
    .scan-corner.bottom-right { bottom: 25%; right: 15%; border-width: 0 4px 4px 0; border-bottom-right-radius: 10px; }

    .laser-line {
        position: absolute;
        left: 15%; width: 70%; height: 2px;
        background: #00ff00;
        box-shadow: 0 0 15px 2px #00ff00;
        z-index: 3;
        animation: scan-laser 2s infinite alternate ease-in-out;
        display: none;
    }

    @keyframes scan-laser {
        0% { top: 15%; opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { top: 75%; opacity: 0; }
    }

    .biometric-container.warning .scan-corner { border-color: #ffc700; }
    .biometric-container.warning .laser-line { background: #ffc700; box-shadow: 0 0 15px 2px #ffc700; }
    .biometric-container.danger .scan-corner { border-color: #f1416c; }
    .biometric-container.danger .laser-line { display: none; }
    .biometric-container.info .scan-corner { border-color: #7239ea; }
    .biometric-container.info .laser-line { background: #7239ea; box-shadow: 0 0 15px 2px #7239ea; }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<?php $db = Config\Database::connect(); ?>

<div class="d-flex flex-column flex-column-fluid py-3 py-lg-6 mt-8">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card card-flush mb-8">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-sertifikat-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari sertifikat..." />
                        </div>
                    </div>

                    <div class="card-toolbar flex-row-fluid justify-content-end gap-3">
                        <?php if (isset($canDownloadAll) && $canDownloadAll) : ?>
                            <div class="d-flex align-items-center fw-bold text-gray-600 me-2">Unduh Brevet AB:</div>
                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#sertifikat_all_cetak_modal" data-sertifikat_all="<?= base_url("sw-admin/siswa/lihatSertifikatBrevet/" . $idsiswa) ?>" class="btn btn-sm btn-light-success sertifikat_all_cetak" title="Unduh Sertifikat Brevet AB">
                                <i class="ki-duotone ki-file-down fs-2"><span class="path1"></span><span class="path2"></span></i> Standar
                            </a>
                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#sertifikat_all_cap_cetak_modal" data-sertifikat_all_cap="<?= base_url("sw-admin/siswa/lihatSertifikatBrevet/" . $idsiswa) . "/cap" ?>" class="btn btn-sm btn-light-primary sertifikat_all_cap_cetak" title="Unduh Sertifikat Cap Basah">
                                <i class="ki-duotone ki-badge fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Cap Basah
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatable-sertifikat" class="table align-middle table-row-dashed fs-6 gy-5 text-left" data-idsiswa="<?= $idsiswa ?? '' ?>" style="width:100%">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Nama Ujian</th>
                                    <th class="min-w-100px">Verifikasi</th>
                                    <th class="min-w-150px">Mulai / Selesai</th>
                                    <th class="min-w-100px">Nilai</th>
                                    <th class="min-w-100px">Status</th>
                                    <th class="text-center min-w-100px">Sertifikat</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$modals = [
    ['id' => 'sertifikat_cap_cetak_modal', 'class' => 'isiKontenSetifikatCap'],
    ['id' => 'sertifikat_cetak_modal', 'class' => 'isiKontenSertifikat'],
    ['id' => 'sertifikat_all_cap_cetak_modal', 'class' => 'isiKontenSetifikatAllCap'],
    ['id' => 'sertifikat_all_cetak_modal', 'class' => 'isiKontenSertifikatAll']
];
foreach ($modals as $modal): ?>
    <div class="modal fade" id="<?= $modal['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <div class="<?= $modal['class'] ?>"></div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<div class="modal fade" id="modal_view_verification" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header pb-0 border-0 justify-content-between align-items-start px-8 pt-8">
                <div>
                    <h2 class="fw-bolder text-gray-900 mb-2">Detail Verifikasi Ujian</h2>
                    <div class="text-muted fw-semibold fs-5" id="verify_ujian_name">Memuat data...</div>
                </div>
                <div class="btn btn-sm btn-icon btn-active-color-primary bg-light" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-2"></i>
                </div>
            </div>
            <div class="modal-body scroll-y px-8 px-lg-12 pt-10 pb-15">
                <div class="row g-8">
                    <div class="col-6">
                        <div class="d-flex flex-column align-items-center">
                            <div class="rounded-3 overflow-hidden shadow-sm border border-2 border-primary w-100 mb-5 bg-light" style="aspect-ratio: 4/3;">
                                <img src="" alt="Foto Profil" id="img_profile_photo" class="w-100 h-100" style="object-fit: cover; object-position: center;">
                            </div>
                            <span class="fs-4 fw-bold text-gray-800">Foto Profil</span>
                            <span class="badge badge-light-primary mt-2 px-3 py-2">Data Master</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column align-items-center">
                            <div class="rounded-3 overflow-hidden shadow-sm border border-2 border-success w-100 mb-5 bg-light" style="aspect-ratio: 4/3;">
                                <img src="" alt="Foto Ujian" id="img_exam_photo" class="w-100 h-100" style="object-fit: cover; object-position: center;">
                            </div>
                            <span class="fs-4 fw-bold text-gray-800">Foto Saat Ujian</span>
                            <span class="badge badge-light-success mt-2 px-3 py-2">Live Capture</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-center mt-12 gap-3">
                    <button type="button" class="btn btn-light-secondary text-gray-700 fw-bold px-8" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-danger fw-bold px-8" id="btn_suspend_certificate">
                        <i class="ki-outline ki-cross-circle fs-3 me-2"></i> Tangguhkan Sertifikat
                    </button>
                </div>
            </div>
        </div>
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
                                    <li>Ikuti instruksi sistem (contoh: Tersenyum / Kedip Mata).</li>
                                    <li>Sistem akan mengambil foto secara otomatis.</li>
                                    <li>Dilarang menutup tab browser selama ujian berlangsung.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/face_mesh/face_mesh.js" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {

        <?= session()->getFlashdata('pesan'); ?>

        let csrfName = '<?= csrf_token() ?>';
        let csrfHash = '<?= csrf_hash() ?>';

        // ==========================================
        // 1. BAGIAN DATATABLE & ADMIN PANEL
        // ==========================================
        const idSiswaEnc = $('#datatable-sertifikat').data('idsiswa');

        const table = $('#datatable-sertifikat').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= base_url('sw-admin/siswa/get-data-sertifikat') ?>",
                type: "POST",
                data: function(d) {
                    d.id_siswa = idSiswaEnc;
                    d[csrfName] = csrfHash;
                },
                dataSrc: function(json) {
                    csrfHash = json.csrf_hash;
                    return json.data;
                },
            },
            columns: [{
                    data: 'nama_ujian',
                    className: 'text-gray-800 fw-bold'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        if (!row.verifikasi || row.verifikasi.trim() === "") {
                            return '<div class="text-gray-400">-</div>';
                        }

                        let examPhotoUrl = `<?= base_url() ?>/uploads/verifikasi/${row.verifikasi}`;
                        let profilePhotoUrl = row.foto_profil && row.foto_profil.trim() !== "" ?
                            `<?= base_url() ?>/assets/app-assets/user/${row.foto_profil}` :
                            `<?= base_url('assets/app-assets/user/default.jpg') ?>`;
                            
                        let targetIdUjian = row.id_ujian || row.id;

                        return `
                        <a href="javascript:void(0)" 
                        class="btn-view-verification" 
                        data-exam-photo="${examPhotoUrl}" 
                        data-profile-photo="${profilePhotoUrl}" 
                        data-ujian-name="${row.nama_ujian}"
                        data-id-ujian="${targetIdUjian}" 
                        data-status="${row.status}"
                        title="Klik untuk lihat detail verifikasi">
                            <img src="${examPhotoUrl}" 
                                alt="Verifikasi" 
                                class="img-fluid rounded shadow-sm border border-gray-300" 
                                style="max-width: 50px; height: 50px; object-fit: cover; transition: transform 0.2s;"
                                onmouseover="this.style.transform='scale(1.1)'"
                                onmouseout="this.style.transform='scale(1)'">
                        </a>`;
                    }
                },
                {
                    data: null,
                    render: row => `<div class="d-flex flex-column"><span class="text-gray-800 mb-1">${row.start_ujian}</span><span class="text-muted">${row.end_ujian}</span></div>`
                },
                {
                    data: 'nilai',
                    className: 'fw-bold'
                },
                {
                    data: null, // PERBAIKAN: Diubah null untuk mengakses status & nilai sekaligus
                    render: function(data, type, row) {
                        let nilai = row.nilai || 0;
                        let status = row.status || '';
                        
                        let color = 'danger';
                        let label = 'Tidak Lulus';

                        if (status === 'T') {
                            color = 'info';
                            label = 'Ditangguhkan';
                        } else if (nilai >= 60 && status === 'S') {
                            color = 'success';
                            label = 'Lulus';
                        }

                        return `<span class="badge badge-light-${color} fw-bold px-3 py-2">${label}</span>`;
                    }
                },
                {
                    data: null,
                    className: 'text-center',
                    orderable: false,
                    render: function(data, type, row) {
                        if (row.nilai >= 60 && row.status === 'S') {
                            return `
                            <div class="d-flex justify-content-center gap-2">
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#sertifikat_cetak_modal" 
                                data-sertifikat="${row.url_cetak}" class="btn btn-icon btn-light-success btn-sm sertifikat_cetak" title="Sertifikat Standar">
                                    <i class="ki-duotone ki-document fs-3"><span class="path1"></span><span class="path2"></span></i>
                                </a>
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#sertifikat_cap_cetak_modal" 
                                data-sertifikat_cap="${row.url_cetak_cap}" class="btn btn-icon btn-light-primary btn-sm sertifikat_cap_cetak" title="Sertifikat Cap Basah">
                                    <i class="ki-duotone ki-badge fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                </a>
                            </div>`;
                        } else {
                            // PERBAIKAN: Keterangan mengapa tombol nonaktif
                            return `<span class="btn btn-icon btn-light btn-sm disabled opacity-50" title="Sertifikat tidak tersedia" style="cursor: not-allowed;">
                                        <i class="ki-duotone ki-document fs-3"><span class="path1"></span><span class="path2"></span></i>
                                    </span>`;
                        }
                    }
                }
            ],
        });

        // Search Custom Table
        $('[data-kt-sertifikat-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        function renderModalContent(title, url) {
            return `
                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h2 class="fw-bold">${title}</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body p-5">
                    <iframe src="${url}" width="100%" height="600px" style="border:none; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.05);"></iframe>
                </div>
            `;
        }

        $(document).on('click', '.sertifikat_cap_cetak', function() { $(".isiKontenSetifikatCap").html(renderModalContent('Sertifikat (Cap Basah)', $(this).data('sertifikat_cap'))); });
        $(document).on('click', '.sertifikat_cetak', function() { $(".isiKontenSertifikat").html(renderModalContent('Sertifikat Resmi', $(this).data('sertifikat'))); });
        $(document).on('click', '.sertifikat_all_cap_cetak', function() { $(".isiKontenSetifikatAllCap").html(renderModalContent('Sertifikat Brevet AB (Cap Basah)', $(this).data('sertifikat_all_cap'))); });
        $(document).on('click', '.sertifikat_all_cetak', function() { $(".isiKontenSertifikatAll").html(renderModalContent('Sertifikat Brevet AB', $(this).data('sertifikat_all'))); });

        // Tampilkan Modal Verifikasi Foto (Admin)
        $('#datatable-sertifikat').on('click', '.btn-view-verification', function() {
            const examPhoto = $(this).attr('data-exam-photo');
            const profilePhoto = $(this).attr('data-profile-photo');
            const ujianName = $(this).attr('data-ujian-name');
            const idUjianVal = $(this).attr('data-id-ujian');
            const statusUjian = $(this).attr('data-status'); // Tangkap statusnya

            $('#img_exam_photo').attr('src', examPhoto);
            $('#img_profile_photo').attr('src', profilePhoto);
            $('#verify_ujian_name').text(ujianName);

            // Logika Perubahan Tombol Dinamis
            let btnAction = $('#btn_suspend_certificate');
            btnAction.attr('data-id-ujian', idUjianVal);
            btnAction.attr('data-status', statusUjian); // Simpan status di tombol

            if (statusUjian === 'T') {
                // Jika sedang ditangguhkan -> Tombol jadi Aktifkan (Hijau)
                btnAction.removeClass('btn-danger').addClass('btn-success');
                btnAction.html('<i class="ki-outline ki-check-circle fs-3 me-2"></i> Aktifkan Sertifikat');
            } else {
                // Jika aktif -> Tombol jadi Tangguhkan (Merah)
                btnAction.removeClass('btn-success').addClass('btn-danger');
                btnAction.html('<i class="ki-outline ki-cross-circle fs-3 me-2"></i> Tangguhkan Sertifikat');
            }

            $('#modal_view_verification').modal('show');
        });

        // Aksi Tangguhkan / Aktifkan Sertifikat
        $('#btn_suspend_certificate').on('click', function() {
            let id_ujian_action = $(this).attr('data-id-ujian');
            let current_status = $(this).attr('data-status');

            if (!id_ujian_action || id_ujian_action === "undefined") {
                Swal.fire('Error', 'ID Ujian tidak ditemukan di dalam database/tabel!', 'error');
                return;
            }

            // Tentukan teks dan warna SweetAlert berdasarkan status
            let isSuspended = (current_status === 'T');
            let swalTitle = isSuspended ? 'Aktifkan Sertifikat?' : 'Tangguhkan Sertifikat?';
            let swalText = isSuspended ? "Sertifikat akan diaktifkan kembali dan bisa dicetak oleh siswa." : "Sertifikat akan ditangguhkan. Tindakan ini tidak dapat dibatalkan secara langsung.";
            let swalConfirmText = isSuspended ? 'Ya, Aktifkan!' : 'Ya, Tangguhkan!';
            let swalConfirmColor = isSuspended ? '#50cd89' : '#f1416c'; // Hijau : Merah
            let swalConfirmClass = isSuspended ? 'btn btn-success' : 'btn btn-danger';

            Swal.fire({
                title: swalTitle,
                text: swalText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: swalConfirmColor,
                cancelButtonColor: '#7e8299',
                confirmButtonText: swalConfirmText,
                cancelButtonText: 'Batal',
                customClass: { confirmButton: swalConfirmClass, cancelButton: "btn btn-active-light" }
            }).then((result) => {
                if (result.isConfirmed) {
                    let btn = $(this);
                    let originalText = btn.html();
                    btn.html('<span class="spinner-border spinner-border-sm align-middle ms-2"></span> Memproses...').prop('disabled', true);

                    let postData = { id_ujian: id_ujian_action };
                    postData[csrfName] = csrfHash;

                    $.ajax({
                        url: '<?= base_url("sw-admin/siswa/suspend-action") ?>', 
                        type: 'POST',
                        dataType: 'json',
                        data: postData,
                        success: function(response) {
                            btn.html(originalText).prop('disabled', false); 
                            if(response.csrf_hash) csrfHash = response.csrf_hash; 

                            if (response.status === 'success') {
                                let successMsg = isSuspended ? 'Sertifikat telah diaktifkan kembali.' : 'Sertifikat telah ditangguhkan.';
                                Swal.fire('Berhasil!', successMsg, 'success');
                                $('#modal_view_verification').modal('hide');
                                table.ajax.reload(null, false); 
                            } else {
                                Swal.fire('Gagal!', response.message, 'error');
                            }
                        },
                        error: function() {
                            btn.html(originalText).prop('disabled', false);
                            Swal.fire('Error!', 'Terjadi kesalahan pada server.', 'error');
                        }
                    });
                }
            });
        });

        // ==========================================
        // 2. BAGIAN SISWA: LIVENESS AI (FACE MESH)
        // ==========================================
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
        
        const LEFT_EYE_TOP = 159, LEFT_EYE_BOTTOM = 145;
        const RIGHT_EYE_TOP = 386, RIGHT_EYE_BOTTOM = 374;
        const LIP_LEFT = 61, LIP_RIGHT = 291;
        const LIP_TOP = 13, LIP_BOTTOM = 14;

        $('.btn-informasi-mulai, .btn-informasi').click(function(e) {
            e.preventDefault();
            let btn = $(this);
            
            selectedHref = btn.attr('href');
            idujian = btn.data('idujian');
            
            $('#info_jumlah_soal').text(btn.data('soal'));
            $('#info_durasi').text(btn.data('waktu'));
            $('#info_percobaan').text(btn.data('kuota'));
            
            let warna = btn.data('warna');
            $('#icon_percobaan').removeClass('text-success text-warning text-danger text-info').addClass('text-' + warna);
            
            resetToDefault();
            updateUI('warning', 'Menyiapkan Keamanan...', 'Memuat AI tingkat lanjut');
            
            modalWajah.show();
            initFaceMeshAndCamera();
        });

        function initFaceMeshAndCamera() {
            if(!faceMesh) {
                faceMesh = new FaceMesh({locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/face_mesh/${file}`});
                faceMesh.setOptions({
                    maxNumFaces: 1, 
                    refineLandmarks: true, 
                    minDetectionConfidence: 0.5, 
                    minTrackingConfidence: 0.5
                });
                faceMesh.onResults(handleFaceMeshResults);
            }

            camera = new Camera(videoElement, {
                onFrame: async () => {
                    if (!isProcessing) {
                        if (canvasElement.width !== videoElement.videoWidth) {
                            canvasElement.width = videoElement.videoWidth;
                            canvasElement.height = videoElement.videoHeight;
                        }
                        canvasCtx.clearRect(0, 0, canvasElement.width, canvasElement.height);
                        canvasCtx.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);
                        await faceMesh.send({image: canvasElement});
                    }
                },
                width: 320, 
                height: 240
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

            if (!results.multiFaceLandmarks || results.multiFaceLandmarks.length === 0) {
                return resetScan('Arahkan Wajah ke Kamera', 'Tatap layar perangkat Anda', 'warning');
            }
            if (results.multiFaceLandmarks.length > 1) {
                return resetScan('Peringatan: >1 Wajah', 'Pastikan Anda sendirian', 'danger');
            }

            const landmarks = results.multiFaceLandmarks[0];

            if (!currentChallenge) {
                currentChallenge = CHALLENGES[Math.floor(Math.random() * CHALLENGES.length)];
                challengeTimer = Date.now();
                let text = currentChallenge === 'SMILE' ? 'Silakan Tersenyum Lebar' : 'Kedipkan Mata Anda';
                updateUI('info', text, 'Buktikan Anda bukan robot (foto statis)');
                return;
            }

            let challengePassed = false;
            let timeElapsed = Date.now() - challengeTimer;

            if (currentChallenge === 'SMILE') {
                const lipWidth = getDistance(landmarks[LIP_LEFT], landmarks[LIP_RIGHT]);
                const lipHeight = getDistance(landmarks[LIP_TOP], landmarks[LIP_BOTTOM]);
                if (lipWidth > 0.15 && lipHeight > 0.03) challengePassed = true;
            } 
            else if (currentChallenge === 'BLINK') {
                const leftEyeOpen = getDistance(landmarks[LEFT_EYE_TOP], landmarks[LEFT_EYE_BOTTOM]);
                const rightEyeOpen = getDistance(landmarks[RIGHT_EYE_TOP], landmarks[RIGHT_EYE_BOTTOM]);
                if (leftEyeOpen < 0.012 || rightEyeOpen < 0.012) challengePassed = true;
            }

            if (challengePassed) {
                isProcessing = true;
                $('#laser_scanner').hide();
                $('#scan_progress_container').removeClass('d-none');
                $('#scan_progress').css('width', '100%');
                updateUI('success', 'Verifikasi Selesai', 'Foto diambil otomatis. Memproses data...');
                captureAndSend();
            } 
            else if (timeElapsed > 10000) { 
                resetScan('Waktu Habis', 'Sistem mendeteksi foto statis. Ulangi proses.', 'danger');
                setTimeout(() => { currentChallenge = null; }, 2000); 
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
            indicator.addClass('bg-' + status);
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
                postData[csrfName] = csrfHash;

                $.ajax({
                    url: "<?= base_url('sw-siswa/ujian/proses-verifikasi') ?>",
                    type: 'POST',
                    data: postData,
                    dataType: 'json',
                    success: function(response) {
                        if(response.csrf_hash) csrfHash = response.csrf_hash;
                        
                        if (response.status === 'success') {
                            $(biometricBox).append('<div style="position:absolute;top:0;left:0;width:100%;height:100%;background:white;z-index:99;animation:flash 1s forwards;"></div>');
                            $('<style>@keyframes flash { 0%{opacity:1;} 100%{opacity:0;} }</style>').appendTo('head');

                            setTimeout(() => {
                                $('#modal_verifikasi_wajah').modal('hide');
                                Swal.fire({ title: 'Verifikasi Berhasil', text: 'Mengalihkan ke lembar ujian...', icon: 'success', showConfirmButton: false, timer: 1500 })
                                .then(() => { window.location.href = response.redirect; });
                            }, 500);
                        } else {
                            Swal.fire('Gagal', response.message, 'error');
                            resetToDefault();
                            $('#laser_scanner').show();
                        }
                    },
                    error: function() {
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
<?= $this->endSection() ?>
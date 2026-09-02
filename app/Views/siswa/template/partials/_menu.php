<?php $current_uri = uri_string(); ?>

<div class="menu menu-rounded menu-active-bg menu-state-primary menu-column menu-lg-row menu-title-gray-700 menu-icon-gray-500 menu-arrow-gray-500 menu-bullet-gray-500 my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0" id="kt_app_header_menu" data-kt-menu="true">

    <div class="menu-item me-0 me-lg-2">
        <a class="menu-link <?= ($current_uri == 'sw-siswa') ? 'active' : '' ?>" href="<?= base_url('sw-siswa') ?>">
            <span class="menu-title">Dashboards</span>
        </a>
    </div>

    <div id="menu_mobile_siswa" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion me-0 me-lg-2">
        <span class="menu-link <?= (strpos($current_uri, 'sw-siswa/') !== false && $current_uri != 'sw-siswa' && strpos($current_uri, 'sw-siswa/affiliate') === false && strpos($current_uri, 'sw-siswa/ikh') === false) ? 'active' : '' ?>">
            <span class="menu-title">Menu</span>
            <span class="menu-arrow d-lg-none"></span>
        </span>

        <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown p-0 w-100 w-lg-600px">
            <div class="menu-state-bg menu-extended overflow-hidden" data-kt-menu-dismiss="true">
                <div class="row g-0">
                    <div class="col-lg-12 py-3 px-3 py-lg-6 px-lg-6">
                        <div class="row">

                            <div class="col-lg-6 mb-3">
                                <div class="menu-item p-0 m-0">
                                    <a href="<?= base_url('sw-siswa/materi') ?>" class="menu-link <?= ($current_uri == 'sw-siswa/materi') ? 'active' : '' ?>">
                                        <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3">
                                            <i class="ki-outline ki-book-open text-primary fs-1"></i>
                                        </span>
                                        <span class="d-flex flex-column">
                                            <span class="fs-6 fw-bold text-gray-800">Materi Brevet</span>
                                            <span class="fs-7 fw-semibold text-muted">Akses modul pembelajaran</span>
                                        </span>
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div class="menu-item p-0 m-0">
                                    <a href="<?= base_url('sw-siswa/webinar') ?>" class="menu-link <?= ($current_uri == 'sw-siswa/webinar') ? 'active' : '' ?>">
                                        <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3">
                                            <i class="ki-outline ki-award text-primary fs-1"></i>
                                        </span>
                                        <span class="d-flex flex-column">
                                            <span class="fs-6 fw-bold text-gray-800">Webinar</span>
                                            <span class="fs-7 fw-semibold text-muted">Update Regulasi</span>
                                        </span>
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div class="menu-item p-0 m-0">
                                    <a href="<?= base_url('sw-siswa/ujian') ?>" class="menu-link <?= ($current_uri == 'sw-siswa/ujian') ? 'active' : '' ?>">
                                        <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3">
                                            <i class="ki-outline ki-notepad-edit text-danger fs-1"></i>
                                        </span>
                                        <span class="d-flex flex-column">
                                            <span class="fs-6 fw-bold text-gray-800">Ujian & Latihan</span>
                                            <span class="fs-7 fw-semibold text-muted">Ujian Kompetensi</span>
                                        </span>
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div class="menu-item p-0 m-0">
                                    <a href="<?= base_url('sw-siswa/sertifikat') ?>" class="menu-link <?= ($current_uri == 'sw-siswa/sertifikat') ? 'active' : '' ?>">
                                        <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3">
                                            <i class="ki-outline ki-medal-star text-success fs-1"></i>
                                        </span>
                                        <span class="d-flex flex-column">
                                            <span class="fs-6 fw-bold text-gray-800">Sertifikat</span>
                                            <span class="fs-7 fw-semibold text-muted">Download sertifikat</span>
                                        </span>
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div class="menu-item p-0 m-0">
                                    <a href="<?= base_url('sw-siswa/transaksi') ?>" class="menu-link <?= ($current_uri == 'sw-siswa/histori') ? 'active' : '' ?>">
                                        <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3">
                                            <i class="ki-outline ki-time text-info fs-1"></i>
                                        </span>
                                        <span class="d-flex flex-column">
                                            <span class="fs-6 fw-bold text-gray-800">Histori</span>
                                            <span class="fs-7 fw-semibold text-muted">Riwayat transaksi</span>
                                        </span>
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div class="menu-item p-0 m-0">
                                    <a href="<?= base_url('sw-siswa/affiliate') ?>" class="menu-link <?= ($current_uri == 'sw-siswa/affiliate') ? 'active' : '' ?>">
                                        <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3">
                                            <i class="ki-outline ki-people text-warning fs-1"></i>
                                        </span>
                                        <span class="d-flex flex-column">
                                            <span class="fs-6 fw-bold text-gray-800">Affiliate</span>
                                            <span class="fs-7 fw-semibold text-muted">Program bonus</span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    $db = \Config\Database::connect();

    // Mengambil data pendaftaran
    $ikh = $db->table('pendaftaran_ikh')->where([
        'id_siswa' => session('id'),
        'status_validasi_admin' => 'draft'
    ])->get()->getRow();

    // 1. Cek apakah ada data pendaftaran IKH
    $adaData = !empty($ikh);

    // 2. Cek kelengkapan data (Sesuaikan nama kolomnya)
    $isLengkap = false;
    if ($adaData) {
        if (!empty($ikh->kolom_1) && !empty($ikh->kolom_2) && !empty($ikh->kolom_3)) {
            $isLengkap = true;
        }
    }

    // 3. Cek apakah saat ini user sedang membuka halaman IKH
    $isHalamanIKH = ($current_uri == 'sw-siswa/ikh');

    // 4. Kondisi tampilkan alert
    $tampilkanAlert = ($adaData && !$isLengkap && !$isHalamanIKH);
    ?>

    <style>
        @keyframes pulse-alert {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.05);
                opacity: 0.7;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .animate-pulse {
            animation: pulse-alert 1.5s infinite ease-in-out;
            display: inline-flex;
            /* Memastikan transform berjalan dengan baik */
        }
    </style>

    <div class="menu-item me-0 me-lg-2">
        <a class="menu-link <?= ($isHalamanIKH) ? 'active' : '' ?>" href="<?= base_url('sw-siswa/ikh') ?>">
            <span class="menu-title">IKH</span>

            <?php if ($tampilkanAlert): ?>
                <span class="menu-badge ms-2">
                    <span class="badge badge-light-danger fw-bold fs-8 px-2 py-1 animate-pulse" title="Data Pendaftaran IKH belum lengkap">
                        <i class="ki-duotone ki-information-5 fs-7 text-danger me-1">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                        </i>
                        Lengkapi
                    </span>
                </span>
            <?php endif; ?>
        </a>
    </div>
    <div class="menu-item me-0 me-lg-2">
        <a class="menu-link <?= ($current_uri == 'sw-siswa/affiliate') ? 'active' : '' ?>" href="<?= base_url('sw-siswa/affiliate') ?>">
            <span class="menu-title">Affiliate</span>
        </a>
    </div>
</div>

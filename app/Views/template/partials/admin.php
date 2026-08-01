<?php
// Dashboard
$dashboard = array('sw-admin/');

// User & Master
$siswa = array('sw-admin/siswa', 'sw-admin/siswa-tidakaktif', 'sw-admin/siswa-banned', 'sw-admin/sertifikatab', 'sw-admin/sertifikat', 'sw-admin/ujian');
$guru = array('sw-admin/guru', 'sw-admin/ujianguru', 'sw-admin/lihat_ujian', 'sw-admin/pg_siswa', 'sw-admin/mapelguru', 'sw-admin/materi', 'sw-admin/lihat_materi');
$mitra = array('sw-admin/mitra', 'sw-admin/mitra_voucher', 'sw-admin/detail-voucher');
$pic = array('sw-admin/pic');

$kelas = array('sw-admin/kelas');
$mapel = array('sw-admin/mapel');
$relasi = array('sw-admin/relasi', 'sw-admin/atur_relasi');
$review = array('sw-admin/review');
$afiliasi = array('sw-admin/afiliasi');

$master_data_pool = array_merge($siswa, $guru, $mitra, $pic, $kelas, $mapel, $relasi, $review, $afiliasi);

// Langganan & Konten
$iklan = array('sw-admin/iklan');
$twibbon = array('sw-admin/twibbon');
$galeri = array('sw-admin/galeri');
$testimoni = array('sw-admin/testimoni');
$quiz = array('sw-admin/quiz', 'sw-admin/soal', 'sw-admin/hasil');
$artikel = array('sw-admin/artikel', 'sw-admin/tambah-artikel', 'sw-admin/edit-artikel', 'sw-admin/kategori', 'sw-admin/tambah-kategori', 'sw-admin/edit-kategori');
$diskon = array('sw-admin/diskon');
$webinar = array('sw-admin/webinar');
$paket = array('sw-admin/paket', 'sw-admin/review');
$transaksi = array('sw-admin/transaksi');
$affiliate = array('sw-admin/affiliate', 'sw-admin/affiliate/komisi');

$langganan_pool = array_merge($transaksi, $paket, $diskon, $webinar, $affiliate);
$konten_pool = array_merge($artikel, $twibbon, $testimoni, $iklan, $galeri, $quiz);

// Pengaturan
$profile = array('sw-admin/profile');
$settings = array('sw-admin/settings');
$diskusi = array('sw-admin/diskusi');
$ikh = array('sw-admin/ikh');
$bankSoal = array('sw-admin/bank-soal');
$pengaturan_pool = array_merge($profile, $settings);

// State Checker
$current_uri = uri_string();
$is_master_active = in_array($current_uri, $master_data_pool);
$is_langganan_active = in_array($current_uri, $langganan_pool);
$is_konten_active = in_array($current_uri, $konten_pool);
$is_pengaturan_active = in_array($current_uri, $pengaturan_pool);
?>

<style>
    /* Custom Styling untuk Hover Profesional */
    .menu-extended .menu-link {
        transition: all 0.2s ease;
        border: 1px solid transparent !important;
    }

    .menu-extended .menu-link:hover {
        background-color: var(--bs-light) !important;
        border-color: var(--bs-gray-200) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
    }

    .menu-extended .menu-link:hover .menu-custom-icon {
        transform: scale(1.05);
        transition: all 0.2s ease;
    }

    .menu-sub-lg-dropdown {
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1) !important;
    }
</style>
<style>
    /* Efek hover khusus untuk menu dropdown agar lebih interaktif */
    .menu-custom-hover {
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .menu-custom-hover:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        border-color: #f1f1f4;
    }

    .menu-custom-hover.active {
        background-color: #f1faff;
        border-color: #d8edff;
    }

    /* Memastikan icon tidak penyok di layar kecil */
    .menu-custom-icon {
        flex-shrink: 0;
    }
</style>

<div class="header-navs d-flex align-items-stretch flex-stack h-lg-70px w-100 py-5 py-lg-0 overflow-hidden overflow-lg-visible" id="kt_header_navs" data-kt-drawer="true" data-kt-drawer-name="header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_header_navs_toggle" data-kt-swapper="true" data-kt-swapper-mode="append" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header'}">
    <div class="d-lg-flex container-xxl w-100">
        <div class="d-lg-flex flex-column justify-content-lg-center w-100" id="kt_header_navs_wrapper">
            <div class="tab-content" data-kt-scroll="true" data-kt-scroll-activate="{default: true, lg: false}" data-kt-scroll-height="auto" data-kt-scroll-offset="70px">
                <div class="tab-pane fade active show" id="kt_header_navs_tab_1">
                    <div class="header-menu flex-column align-items-stretch flex-lg-row">
                        <div class="menu menu-rounded menu-column menu-lg-row menu-root-here-bg-desktop menu-active-bg menu-title-gray-700 menu-state-primary menu-arrow-gray-400 fw-semibold align-items-stretch flex-grow-1 px-2 px-lg-0" id="#kt_header_menu" data-kt-menu="true">

                            <div class="menu-item me-0 me-lg-2">
                                <a href="<?= base_url('sw-admin') ?>" class="menu-link <?= (in_array($current_uri, $dashboard) ? 'active' : '') ?> py-3 px-4">
                                    <span class="menu-title">Dashboard</span>
                                </a>
                            </div>

                            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion me-0 me-lg-2 <?= $is_master_active ? 'here show' : '' ?>">
                                <span class="menu-link py-3 px-4">
                                    <span class="menu-title">Master Data</span>
                                    <span class="menu-arrow d-lg-none"></span>
                                </span>
                                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown p-0 w-100 w-lg-850px shadow-lg rounded-4">
                                    <div class="menu-state-bg menu-extended overflow-hidden py-6 px-6 py-lg-8 px-lg-8 bg-white">
                                        <div class="row g-4">

                                            <!-- Kolom 1 (Kiri) -->
                                            <div class="col-12 col-md-6 col-lg-4">
                                                <!-- Peserta -->
                                                <a href="<?= base_url('sw-admin/siswa') ?>" class="menu-link p-3 mb-2 rounded-3 menu-custom-hover <?= (in_array($current_uri, $siswa) ? 'active' : '') ?>">
                                                    <span class="menu-custom-icon d-flex flex-center rounded-3 w-45px h-45px me-3 bg-light-success text-success">
                                                        <i class="ki-duotone ki-profile-user fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                                    </span>
                                                    <span class="d-flex flex-column">
                                                        <span class="fs-6 fw-bold text-gray-800">Peserta</span>
                                                        <span class="fs-8 fw-semibold text-muted">Siswa & Ujian</span>
                                                    </span>
                                                </a>

                                                <!-- Pengajar -->
                                                <a href="<?= base_url('sw-admin/guru') ?>" class="menu-link p-3 mb-2 rounded-3 menu-custom-hover <?= (in_array($current_uri, $guru) ? 'active' : '') ?>">
                                                    <span class="menu-custom-icon d-flex flex-center rounded-3 w-45px h-45px me-3 bg-light-info text-info">
                                                        <i class="ki-duotone ki-teacher fs-2"><span class="path1"></span><span class="path2"></span></i>
                                                    </span>
                                                    <span class="d-flex flex-column">
                                                        <span class="fs-6 fw-bold text-gray-800">Pengajar</span>
                                                        <span class="fs-8 fw-semibold text-muted">Guru & Materi</span>
                                                    </span>
                                                </a>

                                                <!-- Mitra -->
                                                <a href="<?= base_url('sw-admin/mitra') ?>" class="menu-link p-3 mb-2 rounded-3 menu-custom-hover <?= (in_array($current_uri, $mitra) ? 'active' : '') ?>">
                                                    <span class="menu-custom-icon d-flex flex-center rounded-3 w-45px h-45px me-3 bg-light-primary text-primary">
                                                        <i class="ki-duotone ki-briefcase fs-2"><span class="path1"></span><span class="path2"></span></i>
                                                    </span>
                                                    <span class="d-flex flex-column">
                                                        <span class="fs-6 fw-bold text-gray-800">Mitra</span>
                                                        <span class="fs-8 fw-semibold text-muted">Voucher & Affiliate</span>
                                                    </span>
                                                </a>
                                            </div>

                                            <!-- Kolom 2 (Tengah) -->
                                            <div class="col-12 col-md-6 col-lg-4">
                                                <!-- PIC -->
                                                <a href="<?= base_url('sw-admin/pic') ?>" class="menu-link p-3 mb-2 rounded-3 menu-custom-hover <?= (in_array($current_uri, $pic) ? 'active' : '') ?>">
                                                    <span class="menu-custom-icon d-flex flex-center rounded-3 w-45px h-45px me-3 bg-light-dark text-dark">
                                                        <i class="ki-duotone ki-security-user fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                    </span>
                                                    <span class="d-flex flex-column">
                                                        <span class="fs-6 fw-bold text-gray-800">PIC</span>
                                                        <span class="fs-8 fw-semibold text-muted">Akses PIC</span>
                                                    </span>
                                                </a>

                                                <!-- Mata Pelajaran -->
                                                <a href="<?= base_url('sw-admin/mapel') ?>" class="menu-link p-3 mb-2 rounded-3 menu-custom-hover <?= (in_array($current_uri, $mapel) ? 'active' : '') ?>">
                                                    <span class="menu-custom-icon d-flex flex-center rounded-3 w-45px h-45px me-3 bg-light-danger text-danger">
                                                        <i class="ki-duotone ki-book fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                                    </span>
                                                    <span class="d-flex flex-column">
                                                        <span class="fs-6 fw-bold text-gray-800">Mata Pelajaran</span>
                                                        <span class="fs-8 fw-semibold text-muted">Modul & Kurikulum</span>
                                                    </span>
                                                </a>

                                                <!-- Kelas -->
                                                <a href="<?= base_url('sw-admin/kelas') ?>" class="menu-link p-3 mb-2 rounded-3 menu-custom-hover <?= (in_array($current_uri, $kelas) ? 'active' : '') ?>">
                                                    <span class="menu-custom-icon d-flex flex-center rounded-3 w-45px h-45px me-3 bg-light-warning text-warning">
                                                        <i class="ki-duotone ki-element-11 fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                                    </span>
                                                    <span class="d-flex flex-column">
                                                        <span class="fs-6 fw-bold text-gray-800">Kelas</span>
                                                        <span class="fs-8 fw-semibold text-muted">Ruang & Level</span>
                                                    </span>
                                                </a>
                                            </div>

                                            <!-- Kolom 3 (Kanan) -->
                                            <div class="col-12 col-md-6 col-lg-4">
                                                <!-- Relasi -->
                                                <a href="<?= base_url('sw-admin/relasi') ?>" class="menu-link p-3 mb-2 rounded-3 menu-custom-hover <?= ($current_uri == 'relasi' ? 'active' : '') ?>">
                                                    <span class="menu-custom-icon d-flex flex-center rounded-3 w-45px h-45px me-3 bg-light-info text-info">
                                                        <i class="ki-duotone ki-graph-3 fs-2"><span class="path1"></span><span class="path2"></span></i>
                                                    </span>
                                                    <span class="d-flex flex-column">
                                                        <span class="fs-6 fw-bold text-gray-800">Relasi</span>
                                                        <span class="fs-8 fw-semibold text-muted">Mapping Sistem</span>
                                                    </span>
                                                </a>

                                                <!-- Review -->
                                                <a href="<?= base_url('sw-admin/review') ?>" class="menu-link p-3 mb-2 rounded-3 menu-custom-hover <?= ($current_uri == 'review' ? 'active' : '') ?>">
                                                    <span class="menu-custom-icon d-flex flex-center rounded-3 w-45px h-45px me-3 bg-light-secondary text-gray-700">
                                                        <i class="ki-duotone ki-message-text fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                    </span>
                                                    <span class="d-flex flex-column">
                                                        <span class="fs-6 fw-bold text-gray-800">Review</span>
                                                        <span class="fs-8 fw-semibold text-muted">Ulasan Pelanggan</span>
                                                    </span>
                                                </a>

                                                <!-- Afiliasi -->
                                                <a href="<?= base_url('sw-admin/afiliasi') ?>" class="menu-link p-3 mb-2 rounded-3 menu-custom-hover <?= ($current_uri == 'afiliasi' ? 'active' : '') ?>">
                                                    <span class="menu-custom-icon d-flex flex-center rounded-3 w-45px h-45px me-3 bg-light-success text-success">
                                                        <i class="ki-duotone ki-discount fs-2"><span class="path1"></span><span class="path2"></span></i>
                                                    </span>
                                                    <span class="d-flex flex-column">
                                                        <span class="fs-6 fw-bold text-gray-800">Afiliasi</span>
                                                        <span class="fs-8 fw-semibold text-muted">Program Afiliasi</span>
                                                    </span>
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion me-0 me-lg-2 <?= $is_langganan_active ? 'here show' : '' ?>">
                                <span class="menu-link py-3 px-4">
                                    <span class="menu-title">Finance</span>
                                    <span class="menu-arrow d-lg-none"></span>
                                </span>
                                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown p-0 w-100 w-lg-600px">
                                    <div class="menu-state-bg menu-extended overflow-hidden py-8 px-8">
                                        <div class="row g-3">
                                            <div class="col-lg-6">
                                                <a href="<?= base_url('sw-admin/transaksi') ?>" class="menu-link p-4 mb-2 <?= (in_array($current_uri, $transaksi) ? 'active' : '') ?>">
                                                    <span class="menu-custom-icon d-flex flex-center rounded-3 w-45px h-45px me-3 bg-light-success"><i class="ki-duotone ki-wallet text-success fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></span>
                                                    <span class="d-flex flex-column"><span class="fs-6 fw-bold text-gray-800">Transaksi</span><span class="fs-7 fw-semibold text-muted">Invoice & Sales</span></span>
                                                </a>
                                                <a href="<?= base_url('sw-admin/paket') ?>" class="menu-link p-4 mb-2 <?= (in_array($current_uri, $paket) ? 'active' : '') ?>">
                                                    <span class="menu-custom-icon d-flex flex-center rounded-3 w-45px h-45px me-3 bg-light-primary"><i class="ki-duotone ki-package text-primary fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></span>
                                                    <span class="d-flex flex-column"><span class="fs-6 fw-bold text-gray-800">Paket</span><span class="fs-7 fw-semibold text-muted">Layanan Kursus</span></span>
                                                </a>
                                            </div>
                                            <div class="col-lg-6">
                                                <a href="<?= base_url('sw-admin/diskon') ?>" class="menu-link p-4 mb-2 <?= (in_array($current_uri, $diskon) ? 'active' : '') ?>">
                                                    <span class="menu-custom-icon d-flex flex-center rounded-3 w-45px h-45px me-3 bg-light-danger"><i class="ki-duotone ki-tag text-danger fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></span>
                                                    <span class="d-flex flex-column"><span class="fs-6 fw-bold text-gray-800">Diskon</span><span class="fs-7 fw-semibold text-muted">Promo & Flashsale</span></span>
                                                </a>
                                                <a href="<?= base_url('sw-admin/affiliate') ?>" class="menu-link p-4 mb-2 <?= (in_array($current_uri, $affiliate) ? 'active' : '') ?>">
                                                    <span class="menu-custom-icon d-flex flex-center rounded-3 w-45px h-45px me-3 bg-light-warning"><i class="ki-duotone ki-bank text-warning fs-1"><span class="path1"></span><span class="path2"></span></i></span>
                                                    <span class="d-flex flex-column"><span class="fs-6 fw-bold text-gray-800">Affiliate</span><span class="fs-7 fw-semibold text-muted">Komisi & Referral</span></span>
                                                </a>
                                            </div>
                                            <div class="col-lg-6">
                                                <a href="<?= base_url('sw-admin/webinar') ?>" class="menu-link p-4 mb-2 <?= (in_array($current_uri, $webinar) ? 'active' : '') ?>">
                                                    <span class="menu-custom-icon d-flex flex-center rounded-3 w-45px h-45px me-3 bg-light-danger"><i class="ki-duotone ki-tag text-danger fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></span>
                                                    <span class="d-flex flex-column"><span class="fs-6 fw-bold text-gray-800">Webinar</span><span class="fs-7 fw-semibold text-muted">Webinar & Pelatihan</span></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion me-0 me-lg-2 <?= $is_konten_active ? 'here show' : '' ?>">
                                <span class="menu-link py-3 px-4">
                                    <span class="menu-title">Konten</span>
                                    <span class="menu-arrow d-lg-none"></span>
                                </span>
                                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown p-0 w-100 w-lg-400px">
                                    <div class="menu-state-bg menu-extended overflow-hidden py-8 px-8">
                                        <a href="<?= base_url('sw-admin/artikel') ?>" class="menu-link p-4 mb-2 <?= (in_array($current_uri, $artikel) ? 'active' : '') ?>">
                                            <span class="menu-custom-icon d-flex flex-center rounded-3 w-45px h-45px me-3 bg-light-info"><i class="ki-duotone ki-book-open text-info fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></span>
                                            <span class="d-flex flex-column"><span class="fs-6 fw-bold text-gray-800">Artikel</span></span>
                                        </a>
                                        <a href="<?= base_url('sw-admin/twibbon') ?>" class="menu-link p-4 mb-2 <?= (in_array($current_uri, $twibbon) ? 'active' : '') ?>">
                                            <span class="menu-custom-icon d-flex flex-center rounded-3 w-45px h-45px me-3 bg-light-primary"><i class="ki-duotone ki-picture text-primary fs-1"><span class="path1"></span><span class="path2"></span></i></span>
                                            <span class="d-flex flex-column"><span class="fs-6 fw-bold text-gray-800">Twibbon</span></span>
                                        </a>
                                        <a href="<?= base_url('sw-admin/testimoni') ?>" class="menu-link p-4 mb-2 <?= (in_array($current_uri, $testimoni) ? 'active' : '') ?>">
                                            <span class="menu-custom-icon d-flex flex-center rounded-3 w-45px h-45px me-3 bg-light-success"><i class="ki-duotone ki-message-text-2 text-success fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></span>
                                            <span class="d-flex flex-column"><span class="fs-6 fw-bold text-gray-800">Testimoni</span></span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="menu-item me-0 me-lg-2">
                                <a href="<?= base_url('sw-admin/diskusi') ?>" class="menu-link <?= (in_array($current_uri, $diskusi) ? 'active' : '') ?> py-3 px-4">
                                    <span class="menu-title">Diskusi</span>
                                </a>
                            </div>
                            <div class="menu-item me-0 me-lg-2">
                                <a href="<?= base_url('sw-admin/ikh') ?>" class="menu-link <?= (in_array($current_uri, $ikh) ? 'active' : '') ?> py-3 px-4">
                                    <span class="menu-title">IKH</span>
                                </a>
                            </div>

                            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion me-0 me-lg-2 <?= $is_pengaturan_active ? 'here show' : '' ?>">
                                <span class="menu-link py-3 px-4">
                                    <span class="menu-title">Sistem</span>
                                    <span class="menu-arrow d-lg-none"></span>
                                </span>
                                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown p-0 w-100 w-lg-400px">
                                    <div class="menu-state-bg menu-extended overflow-hidden py-8 px-8">
                                        <a href="<?= base_url('sw-admin/settings') ?>" class="menu-link p-4 mb-2 <?= (in_array($current_uri, $settings) ? 'active' : '') ?>">
                                            <span class="menu-custom-icon d-flex flex-center rounded-3 w-45px h-45px me-3 bg-light-warning"><i class="ki-duotone ki-setting-2 text-warning fs-1"><span class="path1"></span><span class="path2"></span></i></span>
                                            <span class="d-flex flex-column"><span class="fs-6 fw-bold text-gray-800">Konfigurasi</span><span class="fs-7 fw-semibold text-muted">Global Settings</span></span>
                                        </a>
                                        <a href="<?= base_url('sw-admin/iklan') ?>" class="menu-link p-4 mb-2 <?= (in_array($current_uri, $iklan) ? 'active' : '') ?>">
                                            <span class="menu-custom-icon d-flex flex-center rounded-3 w-45px h-45px me-3 bg-light-primary"><i class="ki-duotone ki-pointers text-primary fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></span>
                                            <span class="d-flex flex-column"><span class="fs-6 fw-bold text-gray-800">Iklan</span><span class="fs-7 fw-semibold text-muted">Landing Page Ads</span></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="menu-item me-0 me-lg-2">
                                <a href="<?= base_url('sw-admin/bank-soal') ?>" class="menu-link <?= (in_array($current_uri, $bankSoal) ? 'active' : '') ?> py-3 px-4">
                                    <span class="menu-title">Bank Soal</span>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
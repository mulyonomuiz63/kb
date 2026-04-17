<?php
//dashboard
$dashboard = array('sw-guru/');

//kelas & mapel & relasi
$ujian = array('sw-guru/ujian');
$bank_soal = array('sw-guru/bank-soal');
$mapel = array('sw-guru/mapel');

//user menu
$profile = array('sw-guru/profile');
$settings = array('sw-guru/settings');

// State Checker untuk Active Menu
$is_master_data = in_array(uri_string(), array_merge($bank_soal, $mapel, $ujian));
$is_pengaturan  = in_array(uri_string(), array_merge($profile, $settings));
?>

<style>
    /* Efek Hover Profesional untuk Menu Link */
    .menu-extended .menu-link {
        transition: all 0.2s ease-in-out !important;
        border: 1px solid transparent !important;
    }

    .menu-extended .menu-link:hover {
        background-color: var(--bs-light) !important;
        border-color: var(--bs-gray-200) !important;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    /* Animasi Ikon saat di-hover */
    .menu-extended .menu-link:hover .menu-custom-icon {
        transform: scale(1.1);
        transition: all 0.2s ease;
    }

    /* Memperhalus tampilan Dropdown */
    .menu-sub-lg-dropdown {
        box-shadow: 0 15px 50px rgba(0,0,0,0.1) !important;
        border: none !important;
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
                                <a href="<?= base_url('sw-guru') ?>" class="menu-link <?= (in_array(uri_string(), $dashboard) ? 'active' : '') ?> py-3 px-4">
                                    <span class="menu-title">Dashboard</span>
                                </a>
                            </div>

                            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion me-0 me-lg-2 <?= $is_master_data ? 'here show' : '' ?>">
                                <span class="menu-link py-3 px-4">
                                    <span class="menu-title">Master Data</span>
                                    <span class="menu-arrow d-lg-none"></span>
                                </span>
                                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown p-0 w-100 w-lg-600px shadow-sm">
                                    <div class="menu-state-bg menu-extended overflow-hidden rounded-3 py-8 px-8" data-kt-menu-dismiss="true">
                                        <div class="row g-4">
                                            
                                            <div class="col-lg-6">
                                                <a href="<?= base_url('sw-guru/mapel') ?>"
                                                    class="menu-link d-flex align-items-center p-4 rounded-3 <?= (in_array(uri_string(), $mapel) ? 'active bg-light' : '') ?>">
                                                    <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded-3 bg-light-primary w-40px h-40px me-3">
                                                        <i class="ki-duotone ki-book-open text-primary fs-1">
                                                            <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                                                        </i>
                                                    </span>
                                                    <span class="d-flex flex-column">
                                                        <span class="fs-6 fw-bold text-gray-800">Mata Pelajaran</span>
                                                        <span class="fs-7 fw-semibold text-muted text-nowrap">Modul & Kurikulum</span>
                                                    </span>
                                                </a>
                                            </div>

                                            <div class="col-lg-6">
                                                <a href="<?= base_url('sw-guru/ujian') ?>"
                                                    class="menu-link d-flex align-items-center p-4 rounded-3 <?= (in_array(uri_string(), $ujian) ? 'active bg-light' : '') ?>">
                                                    <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded-3 bg-light-danger w-40px h-40px me-3">
                                                        <i class="ki-duotone ki-tablet-ok text-danger fs-1">
                                                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                                        </i>
                                                    </span>
                                                    <span class="d-flex flex-column">
                                                        <span class="fs-6 fw-bold text-gray-800">Ujian</span>
                                                        <span class="fs-7 fw-semibold text-muted text-nowrap">Manajemen Ujian</span>
                                                    </span>
                                                </a>
                                            </div>

                                            <div class="col-lg-6">
                                                <a href="<?= base_url('sw-guru/bank-soal') ?>"
                                                    class="menu-link d-flex align-items-center p-4 rounded-3 <?= (in_array(uri_string(), $bank_soal) ? 'active bg-light' : '') ?>">
                                                    <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded-3 bg-light-warning w-40px h-40px me-3">
                                                        <i class="ki-duotone ki-briefcase text-warning fs-1">
                                                            <span class="path1"></span><span class="path2"></span>
                                                        </i>
                                                    </span>
                                                    <span class="d-flex flex-column">
                                                        <span class="fs-6 fw-bold text-gray-800">Bank Soal</span>
                                                        <span class="fs-7 fw-semibold text-muted text-nowrap">Manajemen Soal</span>
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
            </div>
        </div>
    </div>
</div>
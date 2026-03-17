<?php $current_uri = uri_string(); ?>

<div class="menu menu-rounded menu-active-bg menu-state-primary menu-column menu-lg-row menu-title-gray-700 menu-icon-gray-500 menu-arrow-gray-500 menu-bullet-gray-500 my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0" id="kt_app_header_menu" data-kt-menu="true">
    
    <div class="menu-item me-0 me-lg-2">
        <a class="menu-link <?= ($current_uri == 'sw-siswa') ? 'active' : '' ?>" href="<?= base_url('sw-siswa') ?>">
            <span class="menu-title">Dashboards</span>
        </a>
    </div>

    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion me-0 me-lg-2">
        <span class="menu-link <?= (strpos($current_uri, 'sw-siswa/') !== false && $current_uri != 'sw-siswa') ? 'active' : '' ?>">
            <span class="menu-title">Pages</span>
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
                                            <span class="fs-6 fw-bold text-gray-800">Materi</span>
                                            <span class="fs-7 fw-semibold text-muted">Akses modul pembelajaran</span>
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
                                            <span class="fs-6 fw-bold text-gray-800">Ujian</span>
                                            <span class="fs-7 fw-semibold text-muted">Evaluasi & Quiz</span>
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

                            <div class="col-lg-6 mb-3">
                                <div class="menu-item p-0 m-0">
                                    <a href="<?= base_url('sw-siswa/profile') ?>" class="menu-link <?= ($current_uri == 'sw-siswa/profile') ? 'active' : '' ?>">
                                        <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3">
                                            <i class="ki-outline ki-user text-dark fs-1"></i>
                                        </span>
                                        <span class="d-flex flex-column">
                                            <span class="fs-6 fw-bold text-gray-800">Profile</span>
                                            <span class="fs-7 fw-semibold text-muted">Pengaturan akun</span>
                                        </span>
                                    </a>
                                </div>
                            </div>

                        </div> <div class="separator separator-dashed my-5"></div>

                        <div class="d-flex flex-stack flex-wrap gap-2 px-5">
                            <div class="d-flex flex-column">
                                <div class="fs-6 fw-bold text-gray-800">Paket Belajar</div>
                                <div class="fs-7 fw-semibold text-muted">Tingkatkan skill Anda hari ini</div>
                            </div>
                            <a href="<?= base_url('sw-siswa/paket') ?>" class="btn btn-sm btn-light-primary fw-bold">Lihat Paket</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="header-navs d-flex align-items-stretch flex-stack h-lg-70px w-100 py-5 py-lg-0 overflow-hidden overflow-lg-visible" id="kt_header_navs" data-kt-drawer="true" data-kt-drawer-name="header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_header_navs_toggle" data-kt-swapper="true" data-kt-swapper-mode="append" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header'}">
    <div class="d-lg-flex container-xxl w-100">
        <div class="d-lg-flex flex-column justify-content-lg-center w-100" id="kt_header_navs_wrapper">
            <div class="tab-content" data-kt-scroll="true" data-kt-scroll-activate="{default: true, lg: false}" data-kt-scroll-height="auto" data-kt-scroll-offset="70px">

                <div class="tab-pane fade active show" id="kt_header_navs_tab_1">
                    <div class="header-menu flex-column align-items-stretch flex-lg-row">
                        <div class="menu menu-rounded menu-column menu-lg-row menu-root-here-bg-desktop menu-active-bg menu-title-gray-700 menu-state-primary menu-arrow-gray-400 fw-semibold align-items-stretch flex-grow-1 px-2 px-lg-0" id="#kt_header_menu" data-kt-menu="true">

                            <div class="menu-item me-0 me-lg-2">
                                <a href="<?= base_url('sw-admin') ?>" class="menu-link <?= (in_array(uri_string(), $dashboard) ? 'active' : '') ?> py-3">
                                    <span class="menu-title">Dashboard</span>
                                </a>
                            </div>

                            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion me-0 me-lg-2 <?= $is_master_data ? 'here show' : '' ?>">
                                <span class="menu-link py-3">
                                    <span class="menu-title">Master Data</span>
                                    <span class="menu-arrow d-lg-none"></span>
                                </span>
                                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown p-0 w-100 w-lg-600px">
                                    <div class="menu-state-bg menu-extended overflow-hidden overflow-lg-visible" data-kt-menu-dismiss="true">
                                        <div class="row">
                                            <div class="col-12 py-3 px-3 py-lg-6 px-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-6 mb-3">
                                                        <a href="<?= base_url('sw-admin/siswa') ?>" class="menu-item p-0 m-0 menu-link <?= (in_array(uri_string(), $siswa) ? 'active' : '') ?>">
                                                            <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3"><i class="ki-duotone ki-profile-user text-success fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></span>
                                                            <span class="d-flex flex-column"><span class="fs-6 fw-bold text-gray-800">Data Peserta</span><span class="fs-7 fw-semibold text-muted">Kelola Siswa & Ujian</span></span>
                                                        </a>
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <a href="<?= base_url('sw-admin/guru') ?>" class="menu-item p-0 m-0 menu-link <?= (in_array(uri_string(), $guru) ? 'active' : '') ?>">
                                                            <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3"><i class="ki-duotone ki-teacher text-info fs-1"><span class="path1"></span><span class="path2"></span></i></span>
                                                            <span class="d-flex flex-column"><span class="fs-6 fw-bold text-gray-800">Data Pengajar</span><span class="fs-7 fw-semibold text-muted">Guru, Materi & Ujian</span></span>
                                                        </a>
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <a href="<?= base_url('sw-admin/mitra') ?>" class="menu-item p-0 m-0 menu-link <?= (in_array(uri_string(), $mitra) ? 'active' : '') ?>">
                                                            <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3"><i class="ki-duotone ki-profile-user text-success fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></span>
                                                            <span class="d-flex flex-column"><span class="fs-6 fw-bold text-gray-800">Data Mitra</span><span class="fs-7 fw-semibold text-muted">Kelola Mitra & Voucher</span></span>
                                                        </a>
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <a href="<?= base_url('sw-admin/kelas') ?>" class="menu-item p-0 m-0 menu-link <?= (in_array(uri_string(), $kelas) ? 'active' : '') ?>">
                                                            <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3"><i class="ki-duotone ki-home text-dark fs-1"><span class="path1"></span><span class="path2"></span></i></span>
                                                            <span class="d-flex flex-column"><span class="fs-6 fw-bold text-gray-800">Manajemen Kelas</span><span class="fs-7 fw-semibold text-muted">Pengaturan Ruang Kelas</span></span>
                                                        </a>
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <a href="<?= base_url('sw-admin/mapel') ?>" class="menu-item p-0 m-0 menu-link <?= (in_array(uri_string(), $mapel) ? 'active' : '') ?>">
                                                            <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3"><i class="ki-duotone ki-book text-primary fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></span>
                                                            <span class="d-flex flex-column"><span class="fs-6 fw-bold text-gray-800">Mata Pelajaran</span><span class="fs-7 fw-semibold text-muted">Modul & Kurikulum</span></span>
                                                        </a>
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <a href="<?= base_url('sw-admin/relasi') ?>" class="menu-item p-0 m-0 menu-link <?= (in_array(uri_string(), $relasi) ? 'active' : '') ?>">
                                                            <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3"><i class="ki-duotone ki-share text-danger fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i></span>
                                                            <span class="d-flex flex-column"><span class="fs-6 fw-bold text-gray-800">Pengaturan Relasi</span><span class="fs-7 fw-semibold text-muted">Pemetaan Sistem</span></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion me-0 me-lg-2 <?= $is_langganan ? 'here show' : '' ?>">
                                <span class="menu-link py-3">
                                    <span class="menu-title">Langganan & Konten</span>
                                    <span class="menu-arrow d-lg-none"></span>
                                </span>
                                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown p-0 w-100 w-lg-600px">
                                    <div class="menu-state-bg menu-extended overflow-hidden overflow-lg-visible" data-kt-menu-dismiss="true">
                                        <div class="row">
                                            <div class="col-12 py-3 px-3 py-lg-6 px-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-6 mb-3">
                                                        <a href="<?= base_url('sw-admin/transaksi') ?>" class="menu-item p-0 m-0 menu-link <?= (in_array(uri_string(), $transaksi) ? 'active' : '') ?>">
                                                            <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3"><i class="ki-duotone ki-basket text-success fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></span>
                                                            <span class="d-flex flex-column"><span class="fs-6 fw-bold text-gray-800">Transaksi & Penjualan</span><span class="fs-7 fw-semibold text-muted">Riwayat Invoice</span></span>
                                                        </a>
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <a href="<?= base_url('sw-admin/paket') ?>" class="menu-item p-0 m-0 menu-link <?= (in_array(uri_string(), $paket) ? 'active' : '') ?>">
                                                            <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3"><i class="ki-duotone ki-lots-shopping text-warning fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span></i></span>
                                                            <span class="d-flex flex-column"><span class="fs-6 fw-bold text-gray-800">Paket & Diskon</span><span class="fs-7 fw-semibold text-muted">Kelola Layanan</span></span>
                                                        </a>
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <a href="<?= base_url('sw-admin/artikel') ?>" class="menu-item p-0 m-0 menu-link <?= (in_array(uri_string(), $artikel) ? 'active' : '') ?>">
                                                            <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3"><i class="ki-duotone ki-document text-info fs-1"><span class="path1"></span><span class="path2"></span></i></span>
                                                            <span class="d-flex flex-column"><span class="fs-6 fw-bold text-gray-800">Artikel & Media</span><span class="fs-7 fw-semibold text-muted">Blog, Galeri, Twibbon</span></span>
                                                        </a>
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <a href="<?= base_url('sw-admin/affiliate') ?>" class="menu-item p-0 m-0 menu-link <?= (in_array(uri_string(), $affiliate) ? 'active' : '') ?>">
                                                            <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3"><i class="ki-duotone ki-discount text-danger fs-1"><span class="path1"></span><span class="path2"></span></i></span>
                                                            <span class="d-flex flex-column"><span class="fs-6 fw-bold text-gray-800">Affiliate & Komisi</span><span class="fs-7 fw-semibold text-muted">Program Kemitraan</span></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion me-0 me-lg-2 <?= $is_pengaturan ? 'here show' : '' ?>">
                                <span class="menu-link py-3">
                                    <span class="menu-title">Pengaturan</span>
                                    <span class="menu-arrow d-lg-none"></span>
                                </span>
                                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown p-0 w-100 w-lg-600px">
                                    <div class="menu-state-bg menu-extended overflow-hidden overflow-lg-visible" data-kt-menu-dismiss="true">
                                        <div class="row">
                                            <div class="col-12 py-3 px-3 py-lg-6 px-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-6 mb-3">
                                                        <a href="<?= base_url('sw-admin/diskusi') ?>" class="menu-item p-0 m-0 menu-link <?= (in_array(uri_string(), $diskusi) ? 'active' : '') ?>">
                                                            <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3"><i class="ki-duotone ki-messages text-primary fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></span>
                                                            <span class="d-flex flex-column"><span class="fs-6 fw-bold text-gray-800">Diskusi</span><span class="fs-7 fw-semibold text-muted">Ruang Obrolan & Forum</span></span>
                                                        </a>
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <a href="<?= base_url('sw-admin/settings') ?>" class="menu-item p-0 m-0 menu-link <?= (in_array(uri_string(), $settings) ? 'active' : '') ?>">
                                                            <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3"><i class="ki-duotone ki-setting-2 text-warning fs-1"><span class="path1"></span><span class="path2"></span></i></span>
                                                            <span class="d-flex flex-column"><span class="fs-6 fw-bold text-gray-800">Pengaturan Sistem</span><span class="fs-7 fw-semibold text-muted">Konfigurasi Global</span></span>
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

                <div class="tab-pane fade" id="kt_header_navs_tab_2">
                    <div class="d-flex flex-column flex-lg-row flex-lg-stack flex-wrap gap-2 px-4 px-lg-0">
                        <div class="d-flex flex-column flex-lg-row gap-2">
                            <a class="btn btn-sm btn-light-primary fw-bold" href="#">Rekap Nilai</a>
                            <a class="btn btn-sm btn-light-success fw-bold" href="#">Rekap Absensi</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
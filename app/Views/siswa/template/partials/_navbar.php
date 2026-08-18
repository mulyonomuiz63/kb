<div class="app-navbar flex-shrink-0">
    <div class="app-navbar-item ms-1 ms-lg-5" id="notification-wrapper">
        <?= view_cell('\App\Cells\NotificationCell::render') ?>
    </div>

    <!--begin::User menu-->
    <div class="app-navbar-item ms-5" id="kt_header_user_menu_toggle">
        <!--begin::Menu wrapper-->
        <div class="cursor-pointer symbol symbol-35px symbol-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
            <img class="symbol symbol-circle symbol-35px symbol-md-40px" src="<?= base_url('assets/app-assets/user/' . session()->get('avatar')) ?>" alt="user" />
        </div>
        <!--begin::User account menu-->
        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
            <!--begin::Menu item-->
            <div class="menu-item px-3">
                <div class="menu-content d-flex align-items-center px-3">
                    <!--begin::Avatar-->
                    <div class="symbol symbol-50px me-5">
                        <img alt="<?= session()->get('nama') ?>" src="<?= base_url('assets/app-assets/user/' . session()->get('avatar')) ?>" />
                    </div>
                    <!--end::Avatar-->
                    <!--begin::Username-->
                    <div class="d-flex flex-column text-wrap">
                        <div class="fw-bold d-flex align-items-center fs-5 text-truncate"
                            style="max-width: 150px;"
                            title="<?= session()->get('nama') ?>">
                            <?= session()->get('nama') ?>
                        </div>

                        <a href="#" class="fw-semibold text-muted text-hover-primary fs-7 text-truncate"
                            style="max-width: 150px;"
                            title="<?= session()->get('email') ?>">
                            <?= session()->get('email') ?>
                        </a>
                    </div>
                    <!--end::Username-->
                </div>
            </div>
            <!--end::Menu item-->
            <!--begin::Menu separator-->
            <div class="separator my-2"></div>
            <!--end::Menu separator-->
            <!--begin::Menu item-->
            <div class="menu-item px-5">
                <a href="<?= base_url('sw-siswa/profile') ?>" class="menu-link px-5">My Profile</a>
            </div>
            <!--end::Menu item-->
            <!--begin::Menu separator-->
            <div class="separator my-2"></div>
            <!--end::Menu separator-->
            <!--begin::Menu item-->
            <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                <a href="#" class="menu-link px-5">
                    <span class="menu-title position-relative">Mode
                        <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                            <i class="ki-outline ki-night-day theme-light-show fs-2"></i>
                            <i class="ki-outline ki-moon theme-dark-show fs-2"></i>
                        </span></span>
                </a>
                <!--begin::Menu-->
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
                    <!--begin::Menu item-->
                    <div class="menu-item px-3 my-0">
                        <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                            <span class="menu-icon" data-kt-element="icon">
                                <i class="ki-outline ki-night-day fs-2"></i>
                            </span>
                            <span class="menu-title">Light</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-3 my-0">
                        <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                            <span class="menu-icon" data-kt-element="icon">
                                <i class="ki-outline ki-moon fs-2"></i>
                            </span>
                            <span class="menu-title">Dark</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-3 my-0">
                        <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                            <span class="menu-icon" data-kt-element="icon">
                                <i class="ki-outline ki-screen fs-2"></i>
                            </span>
                            <span class="menu-title">System</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                </div>
                <!--end::Menu-->
            </div>
            <!--end::Menu item-->
            <!--begin::Menu item-->
            <div class="menu-item px-5">
                <a href="<?= base_url('logout') ?>" class="menu-link px-5">Logout</a>
            </div>
            <!--end::Menu item-->
        </div>
        <!--end::User account menu-->
        <!--end::Menu wrapper-->
    </div>
    <!--end::User menu-->
</div>

<!-- //mobaile bottom nav -->
 <!-- BOTTOM NAVIGATION MOBILE KHUSUS SISWA (METRONIC 8 STYLE) -->
<style>
    .siswa-mobile-bottom-nav {
        display: none;
    }

    @media (max-width: 991.98px) {
        .siswa-mobile-bottom-nav {
            display: flex;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 65px;
            background-color: #ffffff;
            box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.08);
            z-index: 99999;
            justify-content: space-around;
            align-items: center;
            padding-bottom: env(safe-area-inset-bottom);
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .siswa-bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #7e8299; /* Warna teks standar Metronic */
            font-size: 11px;
            font-weight: 500;
            flex-grow: 1;
            height: 100%;
            transition: all 0.25s ease;
        }

        .siswa-bottom-nav-item i {
            font-size: 20px;
            margin-bottom: 3px;
            transition: transform 0.2s ease;
        }

        .siswa-bottom-nav-item:active i {
            transform: scale(0.85);
        }

        /* Kondisi Aktif berdasarkan URI saat ini */
        .siswa-bottom-nav-item.active,
        .siswa-bottom-nav-item:hover {
            color: #009ef7; /* Warna primary Metronic 8 */
        }

        .siswa-bottom-nav-item:hover i {
            transform: translateY(-2px);
        }

        /* Padding bawah body mobile agar konten tidak tertutup bar menu */
        body {
            padding-bottom: 75px !important;
        }

        /* Mengatur posisi tombol chat CS agar berada di atas bottom nav */
        .topcs-bubble {
            bottom: 85px !important;
            z-index: 100000 !important;
        }
    }
</style>
<?php $current_uri = uri_string(); ?>
<div class="siswa-mobile-bottom-nav">
    <!-- 1. Home / Dashboard -->
    <a href="<?= base_url('sw-siswa'); ?>" class="siswa-bottom-nav-item <?= ($current_uri == 'sw-siswa') ? 'active' : '' ?>">
        <i class="ki-outline ki-home fs-2"></i>
        <span>Home</span>
    </a>

    <!-- 2. Materi -->
    <a href="<?= base_url('sw-siswa/materi'); ?>" class="siswa-bottom-nav-item <?= ($current_uri == 'sw-siswa/materi') ? 'active' : '' ?>">
        <i class="ki-outline ki-book-open fs-2"></i>
        <span>Materi</span>
    </a>

    <!-- 3. Ujian -->
    <a href="<?= base_url('sw-siswa/ujian'); ?>" class="siswa-bottom-nav-item <?= ($current_uri == 'sw-siswa/ujian') ? 'active' : '' ?>">
        <i class="ki-outline ki-notepad-edit fs-2"></i>
        <span>Ujian</span>
    </a>

    <!-- 4. Sertifikat -->
    <a href="<?= base_url('sw-siswa/sertifikat'); ?>" class="siswa-bottom-nav-item <?= ($current_uri == 'sw-siswa/sertifikat') ? 'active' : '' ?>">
        <i class="ki-outline ki-medal-star fs-2"></i>
        <span>Sertifikat</span>
    </a>

    <!-- 5. Histori (Transaksi) -->
    <a href="<?= base_url('sw-siswa/transaksi'); ?>" class="siswa-bottom-nav-item <?= ($current_uri == 'sw-siswa/transaksi' || $current_uri == 'sw-siswa/histori') ? 'active' : '' ?>">
        <i class="ki-outline ki-time fs-2"></i>
        <span>Histori</span>
    </a>
</div>
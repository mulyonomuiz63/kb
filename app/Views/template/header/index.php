<?php
// 1. Logika URL (Gunakan uri_string agar lebih simpel)
$is_template = (uri_string() === "siswa/lihat_pg");

// 2. Mapping Data Role (Menggantikan rentetan IF)
$role_id = session()->get('role');
$role_data = [
    1 => ['label' => 'ADMIN',    'path' => 'sw-admin',    'folder' => 'app'],
    2 => ['label' => 'PESERTA',  'path' => 'sw-siswa',  'folder' => 'app'],
    3 => ['label' => 'PENGAJAR', 'path' => 'sw-guru',   'folder' => 'app'],
    4 => ['label' => 'Mitra',    'path' => 'sw-mitra',  'folder' => 'Mitra'],
    5 => ['label' => 'PIC',    'path' => 'sw-pic',    'folder' => 'pic'],
];
$current_role = $role_data[$role_id] ?? $role_data[1]; // fallback ke admin jika tak ditemukan
?>

<header class="header navbar navbar-expand-sm">
    <ul class="navbar-nav theme-brand flex-row text-center">
        <li class="nav-item theme-text">
            <?php if (!$is_template): ?>
                <a href="<?= base_url('/'); ?>">
                <?php endif; ?>
                    <img src="<?= base_url('assets-landing/images/logo-putih.png') ?>" alt="<?= setting('app_name') ?>" class="nav-link" height="45px">
                <?php if (!$is_template): ?></a><?php endif; ?>
        </li>
        <li class="nav-item toggle-sidebar">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list">
                    <line x1="8" y1="6" x2="21" y2="6"></line>
                    <line x1="8" y1="12" x2="21" y2="12"></line>
                    <line x1="8" y1="18" x2="21" y2="18"></line>
                    <line x1="3" y1="6" x2="3" y2="6"></line>
                    <line x1="3" y1="12" x2="3" y2="12"></line>
                    <line x1="3" y1="18" x2="3" y2="18"></line>
                </svg>
            </a>
        </li>
    </ul>
    <ul class="navbar-item flex-row search-ul">
    </ul>
    <ul class="navbar-item flex-row navbar-dropdown ml-auto">
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle position-relative" href="#" id="alertsDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="bi bi-bell-fill text-white" style="font-size: 1.2rem;"></i>
                <span id="main-notif-badge" class="badge badge-danger badge-counter position-absolute"
                    style="display: none; top: 5px; right: 0; font-size: 0.65rem; border-radius: 50%; width: 18px; height: 18px; align-items: center; justify-content: center;">
                    0
                </span>
            </a>

            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown" style="width: 320px !important;">

                <div class="dropdown-header bg-primary text-white py-3"
                    style="background-size: cover;">
                    <h6 class="text-white m-0 fw-bold">Notifikasi</h6>
                    <small class="text-white-50">Pemberitahuan Terbaru</small>
                </div>

                <div id="notification-list" class="scroll-y" style="max-height: 350px; overflow-y: auto;">
                    <div id="notif-loader" class="text-center py-4">
                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        <div class="small text-muted mt-2">Mengecek pemberitahuan...</div>
                    </div>
                </div>

                <div class="dropdown-item text-center p-0 border-top">
                    <button type="button" id="btn-read-all" class="btn btn-link btn-sm text-gray-600 w-100 py-2 text-decoration-none">
                        Tandai Semua Telah Dibaca <i class="fas fa-check-double ml-1"></i>
                    </button>
                </div>
            </div>
        </li>

        <li class="nav-item dropdown user-profile-dropdown">
            <a href="javascript:void(0);" class="nav-link dropdown-toggle <?= $is_template ? 'disabled' : '' ?>" id="userProfileDropdown" data-toggle="dropdown">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
            </a>

            <div class="dropdown-menu dropdown-menu-right animated fadeInUp" aria-labelledby="userProfileDropdown">
                <div class="user-profile-section p-3">
                    <div class="media align-items-center">
                        <?= img_lazy('assets/' . $current_role['folder'] . '-assets/user/' . session()->get('avatar'), "loading", ['class' => 'user-info img-fluid mr-2 bg-white', 'style' => 'width: 45px; height: 45px; object-fit: cover;']) ?>
                        <div class="media-body">
                            <h5 class="m-0"><?= session()->get('nama'); ?></h5>
                            <p class="text-muted small m-0"><?= $current_role['label']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="dropdown-item">
                    <a href="<?= base_url($current_role['path'] . '/profile'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>Profile</span>
                    </a>
                </div>
                <div class="dropdown-item border-top">
                    <a href="<?= base_url('logout'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span>Log Out</span>
                    </a>
                </div>
            </div>
        </li>
    </ul>
</header>

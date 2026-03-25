<?php
// Konfigurasi Active Menu
$dashboard = ['sw-guru/'];
$mapel     = ['sw-guru/mapel', 'sw-guru/materi'];
$ujian     = ['sw-guru/ujian'];
$bank_soal = ['sw-guru/bank-soal','sw-guru/kategori'];
$profile   = ['sw-guru/profile'];
?>
<style>
    /* Pastikan icon di dalam menu active berwarna putih */
    .sidebar-wrapper nav#sidebar ul.menu-categories li.menu.active a i {
        color: #ffffff !important;
    }

    /* Jika menggunakan efek hover juga */
    .sidebar-wrapper nav#sidebar ul.menu-categories li.menu a:hover i {
        color: #ffffff !important;
    }
</style>
<div class="sidebar-wrapper sidebar-theme">
    <nav id="sidebar">

        <div class="profile-info">
            <figure class="user-cover-image"></figure>
            <div class="user-info">
                <?= img_lazy('assets/app-assets/user/' . session()->get('avatar'), "loading", ['class' => 'bg-white shadow-sm']) ?>
                <h6 class="mt-2 font-weight-bold"><?= session()->get('nama'); ?></h6>
                <p class="text-uppercase small" style="letter-spacing: 1px;">Tutor</p>
            </div>
        </div>

        <div class="shadow-bottom"></div>

        <ul class="list-unstyled menu-categories" id="accordionExample">

            <li class="menu <?= set_active($dashboard)['menu']; ?>">
                <a href="<?= base_url('sw-guru'); ?>" aria-expanded="<?= set_active($dashboard)['expanded']; ?>" class="dropdown-toggle">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-grid-1x2-fill mr-3"></i>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>

            <li class="menu menu-heading">
                <div class="heading w-100">
                    <div class="d-flex align-items-center mb-1">
                        <span>MENU TUTOR</span>
                    </div>
                    <hr class="m-0" style="border-top: 1px solid rgba(255,255,255,0.1); width: 100%;">
                </div>
            </li>

            <li class="menu <?= set_active($mapel)['menu']; ?>">
                <a href="<?= base_url('sw-guru/mapel'); ?>" aria-expanded="<?= set_active($mapel)['expanded']; ?>" class="dropdown-toggle">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-book-half mr-3"></i>
                        <span>Mapel</span>
                    </div>
                </a>
            </li>

            <li class="menu <?= set_active($ujian)['menu']; ?>">
                <a href="<?= base_url('sw-guru/ujian'); ?>" aria-expanded="<?= set_active($ujian)['expanded']; ?>" class="dropdown-toggle">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-pencil-square mr-3"></i>
                        <span>Ujian</span>
                    </div>
                </a>
            </li>

            <li class="menu <?= set_active($bank_soal)['menu']; ?>">
                <a href="<?= base_url('sw-guru/bank-soal'); ?>" aria-expanded="<?= set_active($bank_soal)['expanded']; ?>" class="dropdown-toggle">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-database-fill-add mr-3"></i>
                        <span>Bank Soal</span>
                    </div>
                </a>
            </li>

            <li class="menu menu-heading">
                <div class="heading w-100">
                    <div class="d-flex align-items-center mb-1">
                        <span>USER SETTING</span>
                    </div>
                    <hr class="m-0" style="border-top: 1px solid rgba(255,255,255,0.1); width: 100%;">
                </div>
            </li>

            <li class="menu <?= set_active($profile)['menu']; ?>">
                <a href="<?= base_url('sw-guru/profile'); ?>" aria-expanded="<?= set_active($profile)['expanded']; ?>" class="dropdown-toggle">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-badge-fill mr-3"></i>
                        <span>Profile</span>
                    </div>
                </a>
            </li>

            <li class="menu">
                <a href="<?= base_url('logout'); ?>" class="dropdown-toggle">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-box-arrow-right mr-3 text-danger"></i>
                        <span class="text-danger">Logout</span>
                    </div>
                </a>
            </li>

        </ul>
    </nav>
</div>
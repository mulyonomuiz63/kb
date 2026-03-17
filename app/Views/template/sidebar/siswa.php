<?php

    //dashboard
    $dashboard = array(
		'siswa/',
	);
	
	//profile
	$profile = array(
    		'siswa/profile'
	);
	$materi = array(
		'siswa/modul',
		'siswa/materi',
		'siswa/lihat_materi'
	);
	$ujian = array(
		'siswa/ujian',
		'siswa/lihat_pg'
	);
	$sertifikat = array(
		'siswa/sertifikat',
	);
	$transaksi = array(
		'siswa/transaksi',
	);
	
	$affiliate = array(
		'siswa/affiliate',
	);
?>
<style>
/* AFFILIATE MENU */
.menu-affiliate,
.menu-affiliate-content,
.menu-affiliate .icon-wrapper {
    overflow: visible !important;
}

.menu-affiliate {
    position: relative;
    z-index: 1000;
}

.menu-affiliate-content {
    display: flex;
    align-items: center;
    gap: 10px;
}

.icon-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}



.menu-text {
    font-weight: 500;
}

/* DOT NOTIFIKASI */
.badge-new {
    position: absolute;
    top: -6px;
    right: -8px;
    width: 10px;
    height: 10px;
    background: #ff4d4f;
    border-radius: 50%;
    z-index: 99999;
    pointer-events: none;
    animation: pulseDot 1.6s infinite;
}

/* ANIMASI DOT */
@keyframes pulseDot {
    0%   { transform: scale(1);   box-shadow: 0 0 0 0 rgba(255,77,79,.7); }
    70%  { transform: scale(1.4); box-shadow: 0 0 0 7px rgba(255,77,79,0); }
    100% { transform: scale(1);   box-shadow: 0 0 0 0 rgba(255,77,79,0); }
}


</style>
        <!--  BEGIN SIDEBAR  -->
        <div class="sidebar-wrapper sidebar-theme">
            <nav id="sidebar">
                <div class="profile-info">
                    <figure class="user-cover-image"></figure>
                    <div class="user-info">
                        <?= img_lazy('assets/app-assets/user/'.session()->get('avatar'),"loading", ['class' => 'bg-white']) ?>
                        <h6 class=""><?= session()->get('nama') ?></h6>
                        <p class="">PESERTA</p>
                    </div>
                </div>
                <div class="shadow-bottom"></div>
                <ul class="list-unstyled menu-categories" id="accordionExample">
                    <li class="menu <?= set_active($dashboard)['menu']; ?>">
                        <a href="<?= base_url('siswa'); ?>" aria-expanded="<?= set_active($dashboard)['expanded']; ?>" class="dropdown-toggle">
                            <div class="">
                                <span><i class="bi bi-house-door-fill mr-4"></i>Dashboard</span>
                            </div>
                        </a>
                    </li>
                    <li class="menu menu-heading">
                        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg><span>MENU UTAMA</span></div>
                    </li>
                    <li class="menu <?= set_active($materi)['menu']; ?>">
                        <a href="<?= base_url('siswa/modul'); ?>" aria-expanded="<?= set_active($materi)['expanded']; ?>" class="dropdown-toggle">
                            <div class="">
                                <span><i class="bi bi-book mr-4"></i>Materi</span>
                            </div>
                        </a>
                    </li>
                    <li class="menu <?= set_active($ujian)['menu']; ?>">
                        <a href="<?= base_url('siswa/ujian'); ?>" aria-expanded="<?= set_active($ujian)['expanded']; ?>" class="dropdown-toggle">
                            <div class="">
                                <span><i class="bi bi-bookmark-star mr-4"></i>Ujian</span>
                            </div>
                        </a>
                    </li>
                    <li class="menu <?= set_active($sertifikat)['menu']; ?>">
                        <a href="<?= base_url('siswa/sertifikat'); ?>" aria-expanded="<?= set_active($sertifikat)['expanded']; ?>" class="dropdown-toggle">
                            <div class="">
                                <span><i class="bi bi-award mr-4"></i>Sertifikat</span>
                            </div>
                        </a>
                    </li>
                    <li class="menu menu-heading">
                        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg><span>MENU PESERTA</span></div>
                    </li>
                    <li class="menu <?= set_active($profile)['menu']; ?>">
                        <a href="<?= base_url('siswa/profile'); ?>" aria-expanded="<?= set_active($profile)['expanded']; ?>" class="dropdown-toggle">
                            <div class="">
                                <span><i class="bi bi-person-check mr-4"></i>Profil</span>
                            </div>
                        </a>
                    </li>
                    <li class="menu <?= set_active($transaksi)['menu']; ?>">
                        <a href="<?= base_url('siswa/transaksi'); ?>" aria-expanded="<?= set_active($transaksi)['expanded']; ?>" class="dropdown-toggle">
                            <div class="">
                                <span><i class="bi bi-clock-history  mr-4"></i>Histori</span>
                            </div>
                        </a>
                    </li>
                    <li class="menu <?= set_active($affiliate)['menu']; ?>">
                        <a href="<?= base_url('siswa/affiliate'); ?>"
                           aria-expanded="<?= set_active($affiliate)['expanded']; ?>"
                           class="dropdown-toggle menu-affiliate">
                            <div class="menu-affiliate-content">
                                <i class="bi bi-cash-coin <?= set_active($affiliate)['menu'] != '' ? 'text-light':'' ?> mr-3"></i>
                                <div class="icon-wrapper">
                                    
                                    <span class="menu-text">Affiliate</span>
                                    <span class="badge-new"></span>
                                </div>
                            </div>
                        </a>
                    </li>



                    <li class="menu">
                        <a href="<?= base_url('auth/logout'); ?>" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <span><i class="bi bi-box-arrow-right mr-4"></i>Keluar</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!--  END SIDEBAR  -->
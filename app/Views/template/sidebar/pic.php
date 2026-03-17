<?php

    //dashboard
    $dashboard = array(
		'pic/',
	);
	
    //profile
	$profile = array(
    		'pic/profile',
	);
	
	//siswa
	$siswa = array(
		'pic/siswa',
	);
	//jadwal
	$jadwal = array(
		'pic/jadwal',
		'pic/detail_jadwal',
		'pic/absensi',
	);
	
	//galeri
	$galeri = array(
		'pic/galeri',
	);
	//transaksi
	$transaksi = array(
		'pic/transaksi',
	);
	
?>

        <!--  BEGIN SIDEBAR  -->

        <div class="sidebar-wrapper sidebar-theme">



            <nav id="sidebar">

                <div class="profile-info">

                    <figure class="user-cover-image"></figure>

                    <div class="user-info">

                        <?= img_lazy('assets/pic-assets/user/'.session()->get('avatar'),"loading", ['class' => 'bg-white']) ?>

                        <h6 class=""><?= session()->get('nama') ?></h6>

                        <p class="">PIC</p>

                    </div>

                </div>

                <div class="shadow-bottom"></div>

                <ul class="list-unstyled menu-categories" id="accordionExample">

                    <li class="menu <?= set_active($dashboard)['menu']; ?>">
                        <a href="<?= base_url('Pic'); ?>" aria-expanded="<?= set_active($dashboard)['expanded']; ?>" class="dropdown-toggle">
                            <div class="">
                                <span><i class="bi bi-bank mr-4"></i>Dashboard</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu menu-heading">
                        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg><span>MASTER DATA</span></div>
                    </li>


                        

                    <li class="menu <?= set_active($profile)['menu']; ?>">
                        <a href="<?= base_url('Pic/profile'); ?>" aria-expanded="<?= set_active($profile)['expanded']; ?>" class="dropdown-toggle">
                            <div class="">
                                <span><i class="bi bi-person-check mr-4"></i>Profile</span>
                            </div>
                        </a>
                    </li>
                    
                    <li class="menu <?= set_active($siswa)['menu']; ?>">
                        <a href="<?= base_url('Pic/siswa'); ?>" aria-expanded="<?= set_active($siswa)['expanded']; ?>" class="dropdown-toggle">
                            <div class="">
                                <span><i class="bi bi-people mr-4"></i>Peserta</span>
                            </div>
                        </a>
                    </li>
                    
                    <li class="menu <?= set_active($jadwal)['menu']; ?>">
                        <a href="<?= base_url('Pic/jadwal'); ?>" aria-expanded="<?= set_active($jadwal)['expanded']; ?>" class="dropdown-toggle">
                            <div class="">
                                <span><i class="bi bi-calendar-range mr-4"></i>Jadwal</span>
                            </div>
                        </a>
                    </li>
                    
                    <li class="menu <?= set_active($galeri)['menu']; ?>">
                        <a href="<?= base_url('Pic/galeri'); ?>" aria-expanded="<?= set_active($galeri)['expanded']; ?>" class="dropdown-toggle">
                            <div class="">
                                <span><i class="bi bi-images mr-4"></i>Galeri</span>
                            </div>
                        </a>
                    </li>
                    
                    <li class="menu <?= set_active($transaksi)['menu']; ?>">
                        <a href="<?= base_url('Pic/transaksi'); ?>" aria-expanded="<?= set_active($transaksi)['expanded']; ?>" class="dropdown-toggle">
                            <div class="">
                                <span><i class="bi bi-cash-stack mr-4"></i>Transaksi</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu">
                        <a href="<?= base_url('auth/logout'); ?>" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <span><i class="bi bi-box-arrow-right mr-4"></i>Logout</span>
                            </div>
                        </a>
                    </li>
                </ul>



            </nav>



        </div>

        <!--  END SIDEBAR  -->
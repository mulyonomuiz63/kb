        <!--  BEGIN SIDEBAR  -->
        
<?php

    //dashboard
    $dashboard = array(
		'sw-admin/',
	);
	
	//user
	$siswa = array(
    		'sw-admin/siswa',
    		'sw-admin/siswa-tidakaktif',
    		'sw-admin/siswa-banned',
    		'sw-admin/sertifikatab',
    		'sw-admin/sertifikat',
    		'sw-admin/ujian'
	);
	$guru = array(
		'sw-admin/guru',
		'sw-admin/ujianguru',
		'sw-admin/lihat_ujian',
		'sw-admin/pg_siswa',
		'sw-admin/mapelguru',
		'sw-admin/materi',
		'sw-admin/lihat_materi'
	);
	$mitra = array(
		'sw-admin/mitra',
		'sw-admin/mitra_voucher'
	);
	$pic = array(
		'sw-admin/pic',
	);
	$user = array_merge($siswa, $guru, $mitra, $pic);
	
	//kelas
	$kelas = array(
		'sw-admin/kelas',
	);
	
	//mapel
	$mapel = array(
		'sw-admin/mapel',
	);
	
	//relasi
	$relasi = array(
		'sw-admin/relasi',
		'sw-admin/atur_relasi'
	);
	
	//langganan
	$iklan = array(
		'sw-admin/iklan',
	);
	$twibbon = array(
    	'sw-admin/twibbon',
	);
	$galeri = array(
	    'sw-admin/galeri' ,  
	 );
	$testimoni = array(
	    'sw-admin/testimoni' ,  
	 );
	 
	 $quiz = array(
	    'sw-admin/quiz' ,  
	    'sw-admin/soal' ,
	    'sw-admin/hasil' ,
	 );
 	$artikel = array(
	    'sw-admin/artikel',
		'sw-admin/tambah-artikel',
		'sw-admin/edit-artikel',
		'sw-admin/kategori',
		'sw-admin/tambah-kategori',
		'sw-admin/edit-kategori',  
	 );
	$diskon = array(
	    'sw-admin/diskon',
	);
	$paket = array(
	    'sw-admin/paket',
	    'sw-admin/review'
	);
	$transaksi = array(
	    'sw-admin/transaksi',
	);
	//affiliate
	$affiliate = array(
	    'sw-admin/affiliate',
	    'sw-admin/affiliate/komisi'
	);
	$langganan = array_merge($iklan, $twibbon,$artikel,$galeri,$testimoni,$quiz, $diskon, $paket, $transaksi, $affiliate);
	
	//profile
	$profile = array(
	    'sw-admin/profile',
	);

    $settings = array(
        'sw-admin/settings',
    );
?>

        <div class="sidebar-wrapper sidebar-theme">



            <nav id="sidebar">

                <div class="profile-info">

                    <figure class="user-cover-image"></figure>

                    <div class="user-info">

                        <?= img_lazy('assets/app-assets/user/'.session()->get('avatar'),"loading", ['class' => 'bg-white']) ?>

                        <h6 class=""><?= session()->get('nama'); ?></h6>

                        <p class="">ADMIN</p>

                    </div>

                </div>

                <div class="shadow-bottom"></div>

                <ul class="list-unstyled menu-categories" id="accordionExample">
                    <li class="menu <?= set_active($dashboard)['menu']; ?>">
                        <a href="<?= base_url('sw-admin'); ?>" aria-expanded="<?= set_active($dashboard)['expanded']; ?>" class="dropdown-toggle">
                            <div class="">
                                <span> <i class="bi bi-bank mr-4"></i>Dashboard</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu menu-heading">

                        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus">

                                <line x1="5" y1="12" x2="19" y2="12"></line>

                            </svg><span>MASTER DATA</span></div>

                    </li>

                    <li class="menu <?= set_active($user)['menu']; ?>">
                        <a href="#components" data-toggle="collapse" aria-expanded="<?= set_active($user)['expanded']; ?>" class="dropdown-toggle">
                            <div class="">
                                
                                <span><i class="bi bi-person-fill-check mr-4"></i> User</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>

                        <ul class="collapse submenu list-unstyled <?= set_active($user)['collapse']; ?>" id="components" data-parent="#accordionExample">
                            <li class="<?= set_active_submenu($siswa); ?>">
                                <a href="<?= base_url('sw-admin/siswa'); ?>"> Peserta </a>
                            </li>
                            <li class="<?= set_active_submenu($guru); ?>">
                                <a href="<?= base_url('sw-admin/guru'); ?>"> Pengajar </a>
                            </li>
                            <li class="<?= set_active_submenu($mitra); ?>">
                                <a href="<?= base_url('sw-admin/mitra'); ?>"> Mitra </a>
                            </li>
                            <!-- <li class="<?= set_active_submenu($pic); ?>">
                                <a href="<?= base_url('sw-admin/pic'); ?>"> PIC </a>
                            </li> -->
                        </ul>
                    </li>

                    <li class="menu <?= set_active($kelas)['menu']; ?>">
                        <a href="<?= base_url('sw-admin/kelas'); ?>" aria-expanded="<?= set_active($kelas)['expanded']; ?>" class="dropdown-toggle">
                            <div class="">
                                <span><i class="bi bi-house-check-fill mr-4"></i>Kelas</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu <?= set_active($mapel)['menu']; ?>">
                        <a href="<?= base_url('sw-admin/mapel'); ?>" aria-expanded="<?= set_active($mapel)['expanded']; ?>" class="dropdown-toggle">
                            <div class="">
                                <span><i class="bi bi-book mr-4"></i>Mapel</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu <?= set_active($relasi)['menu']; ?>">
                        <a href="<?= base_url('sw-admin/relasi'); ?>" aria-expanded="<?= set_active($relasi)['expanded']; ?>" class="dropdown-toggle">
                            <div class="">
                                <span><i class="bi bi-share mr-4"></i>Relasi</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu <?= set_active($langganan)['menu']; ?>">
                        <a href="#langganan" data-toggle="collapse" aria-expanded="<?= set_active($langganan)['expanded']; ?>" class="dropdown-toggle">
                            <div class="">
                                <span><i class="bi bi-bell mr-4"></i>Langganan</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>

                        <ul class="collapse submenu list-unstyled <?= set_active($langganan)['collapse']; ?>" id="langganan" data-parent="#accordionExample">

                            <li class="<?= set_active_submenu($iklan); ?>">

                                <a href="<?= base_url('sw-admin/iklan'); ?>"> Iklan </a>

                            </li>
                            <li class="<?= set_active_submenu($artikel); ?>">

                                <a href="<?= base_url('sw-admin/artikel'); ?>"> Artikel </a>

                            </li>
                            <li class="<?= set_active_submenu($twibbon); ?>">

                                <a href="<?= base_url('sw-admin/twibbon'); ?>"> Twibbon </a>

                            </li>
                            <li class="<?= set_active_submenu($galeri); ?>">

                                <a href="<?= base_url('sw-admin/galeri'); ?>">Galeri </a>

                            </li>
                            <li class="<?= set_active_submenu($testimoni); ?>">

                                <a href="<?= base_url('sw-admin/testimoni'); ?>">Testimoni </a>

                            </li>
                            
                             <li class="<?= set_active_submenu($diskon); ?>">

                                <a href="<?= base_url('sw-admin/diskon'); ?>"> Diskon </a>

                            </li>

                            <li class="<?= set_active_submenu($paket); ?>">

                                <a href="<?= base_url('sw-admin/paket'); ?>"> Paket </a>

                            </li>

                            <li class="<?= set_active_submenu($transaksi); ?>">

                                <a href="<?= base_url('sw-admin/transaksi'); ?>"> Transaksi </a>

                            </li>
                            
                            <li class="<?= set_active_submenu($affiliate); ?>">

                                <a href="<?= base_url('sw-admin/affiliate'); ?>"> Affiliate </a>

                            </li>

                        </ul>

                    </li>

                    <li class="menu menu-heading">
                        <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg><span>USER MENU</span></div>
                    </li>

                    <li class="menu <?= set_active($profile)['menu']; ?>">
                        <a href="<?= base_url('sw-admin/profile'); ?>" aria-expanded="<?= set_active($profile)['expanded']; ?>" class="dropdown-toggle">
                            <div class="">
                                <span><i class="bi bi-person-fill-check mr-4"></i>Profile</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu <?= set_active($settings)['menu']; ?>">
                        <a href="<?= base_url('sw-admin/settings'); ?>" aria-expanded="<?= set_active($settings)['expanded']; ?>" class="dropdown-toggle">
                            <div class="">
                                <span><i class="bi bi-gear mr-4"></i>Settings</span>
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
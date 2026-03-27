<?php
$routes->group('sw-siswa', ['filter' => 'roleCheck:2'], function ($routes) {
    $routes->get('/', 'Siswa\HomeController::index');

    // affilate
    $routes->group('affiliate', function ($routes) {
        $routes->get('/', 'Siswa\AffiliateController::index');
        $routes->get('create', 'Siswa\AffiliateController::create');
        $routes->get('edit/(:num)', 'Siswa\AffiliateController::edit/$1');
        $routes->post('save', 'Siswa\AffiliateController::save');
        $routes->post('delete', 'Siswa\AffiliateController::delete');
        $routes->post('copy', 'Siswa\AffiliateController::copy');
    });

    $routes->group('materi',['filter' => 'cekData'] , function($routes){
        $routes->get('/', 'Siswa\MateriController::index');
        $routes->get('lihat-materi/(:segment)/(:segment)/(:segment)', 'Siswa\MateriController::lihatMateri/$1/$2/$3');
        $routes->post('chat-materi', 'Siswa\MateriController::chatMateri');
        $routes->post('get-chat-materi', 'Siswa\MateriController::getChatMateri');
        $routes->post('get-file-materi', 'Siswa\MateriController::getFileMateri');
    });

     $routes->group('sertifikat',['filter' => 'cekData'] , function($routes){
        $routes->get('/', 'Siswa\SertifikatController::index');
        $routes->get('lihat-sertifikat-brevet/(:segment)', 'Siswa\SertifikatController::lihatSertifikatBrevet/$1');
        $routes->get('lihat-sertifikat/(:segment)/(:segment)', 'Siswa\SertifikatController::lihatSertifikat/$1/$2');
     });

    //  review
    $routes->post('simpan-review', 'Siswa\ReviewController::simpanReview');


    $routes->group('profile', function($routes){
        $routes->get('/', 'Siswa\ProfileController::index');
        $routes->post('update-data-diri', 'Siswa\ProfileController::editProfile');
        $routes->post('edit-password', 'Siswa\ProfileController::editPassword');
    });


    $routes->group('ujian',['filter' => 'cekData'] ,  function($routes){
        $routes->get('/', 'Siswa\UjianController::index');
        $routes->get('lihat-pg', 'Siswa\UjianController::lihatPg');
        $routes->get('lihat-pg/(:any)/(:any)/(:any)', 'Siswa\UjianController::lihatPg/$1/$2/$3');
        $routes->post('kirim-ujian', 'Siswa\UjianController::kirimUjian');
        $routes->post('kirim-ujian-selesai', 'Siswa\UjianController::kirimUjianSelesai');

        //untuk remedial
        $routes->get('remedial/(:any)/(:any)/(:any)', 'Siswa\UjianController::remedial/$1/$2/$3');
        $routes->post('proses-verifikasi', 'Siswa\UjianController::prosesVerifikasi');
    });
});

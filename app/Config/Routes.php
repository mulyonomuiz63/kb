<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

$routes->group('auth', ['filter' => 'isGuest'], function ($routes) {
    $routes->get('/', 'AuthController::index'); // Penamaan route: login
    $routes->post('login-proses', 'AuthController::loginProses');
    $routes->get('recovery', 'AuthController::recovery');
    $routes->post('recovery_', 'AuthController::recovery_');
    $routes->get('change-password', 'AuthController::changePassword');
    $routes->post('change-password_', 'AuthController::changePassword_');
    
    $routes->get('registrasi', 'RegisterController::index');
    $routes->post('store', 'RegisterController::store');
    $routes->get('verifikasi/(:segment)', 'RegisterController::verifikasi/$1');

});
$routes->get('logout', 'AuthController::logout', ['as' => 'logout']);
// Halaman utama form

$routes->get('/', 'Landing::index');
$routes->get('tes', 'Landing::tes');
$routes->get('twibbon', 'Landing::twibbon');
$routes->get('twibbon/(:any)', 'Landing::twibbon_url/$1');
$routes->post('twibbon/simpan', 'Landing::twibbon_simpan');
$routes->get('get_paket', 'Landing::get_paket');

$routes->get('paket-allujian', 'Transaksi::allujian');
$routes->get('paket-allinone', 'Transaksi::allinone');

$routes->get('pelatihan', 'Landing::pelatihan');
$routes->get('testimoni', 'Landing::testimoni');
$routes->get('tentangkami', 'Landing::tentangkami');
$routes->get('siap-kerja', 'Landing::siap_kerja');
$routes->get('penilaian', 'Landing::penilaian');
$routes->get('term', 'Landing::term');
$routes->get('privasi', 'Landing::privasi');
$routes->get('jadwal', 'Landing::jadwal');
$routes->get('galeri', 'Landing::galeri');
$routes->get('media-kelasbrevet', 'Landing::media');
$routes->get('presensi/(:any)', 'Landing::presensi/$1');

//quis
$routes->group('quiz', function ($routes) {
    $routes->get('', 'Quiz::index');
    $routes->post('save-leaderboard', 'Quiz::saveLeaderboard');
    $routes->get('leaderboard', 'Quiz::getLeaderboard');
    $routes->get('reset-leaderboard', 'Quiz::resetLeaderboard');
    $routes->get('soal/(:any)', 'Quiz::getQuiz/$1');
});

$routes->get('artikel', 'Artikel::index');
$routes->get('artikel/(:any)', 'Artikel::detail/$1');
$routes->get('kategori/(:any)', 'Artikel::kategori/$1');
$routes->get('tag/(:any)', 'Artikel::tag/$1');


//bimbel
$routes->get('list-bimbel', 'Bimbel::index');
$routes->get('bimbel', 'Bimbel::detail');
$routes->get('bimbel/(:any)', 'Bimbel::detail/$1');
$routes->get('bimbel/(:any)/(:any)', 'Bimbel::detail/$1/$2');

// admin
if (is_file(APPPATH . 'Config/RoutesAdmin.php')) {
    require APPPATH . 'Config/RoutesAdmin.php';
}

// guru
if (is_file(APPPATH . 'Config/RoutesGuru.php')) {
    require APPPATH . 'Config/RoutesGuru.php';
}
// siswa
if (is_file(APPPATH . 'Config/RoutesSiswa.php')) {
    require APPPATH . 'Config/RoutesSiswa.php';
}


// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->group('', function ($routes) {
    $routes->get('/', 'Landing::index');
});



// affiliate register
$routes->get('ref/(:segment)/(:segment)', 'Affiliate::redirect/$1/$2');


//ini untuk download file IAI
$routes->get('download/pdf/(:any)', 'Download::pdf/$1');




//untuk handle midtrans
$routes->post('midtrans/notification', 'MidtransController::notification');

// Route untuk aksi update database
$routes->group('api/notifications', function ($routes) {
    $routes->post('mark-all-read', 'NotificationController::markAllRead');
    $routes->post('mark-as-read', 'NotificationController::markAsRead');
    // Route untuk polling AJAX yang merender ulang View Cell
    $routes->get('update-ui', 'NotificationController::refreshUI');
});

// untuk admin
$routes->group('notif', function($routes) {
    $routes->get('get-data', 'NotificationController::getNotifications');
    $routes->post('mark-read', 'NotificationController::markAsRead');
    $routes->post('mark-all-read', 'NotificationController::markAllRead');
});
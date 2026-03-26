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
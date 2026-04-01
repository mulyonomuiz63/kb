<?php

$routes->group('sw-mitra', ['filter' => 'roleCheck:4'], function ($routes) {
    $routes->get('/', 'Mitra\HomeController::index');
    $routes->get('detail-voucher/(:any)', 'Mitra\HomeController::detailVoucher/$1');

    
    $routes->group('profile', function ($routes) {
        $routes->get('', 'Mitra\ProfileController::index');
        $routes->post('update-profile', 'Mitra\ProfileController::updateProfile');
        $routes->post('update-password', 'Mitra\ProfileController::updatePassword');
    });
});
<?php

$routes->group('sw-pic', ['filter' => 'roleCheck:5'], function ($routes) {
    $routes->get('/', 'Pic\IkhController::index');
    $routes->group('ikh', function ($routes) {
        $routes->get('', 'Pic\IkhController::index');
        $routes->get('review/(:segment)', 'Pic\IkhController::review/$1');
        $routes->post('update-status', 'Pic\IkhController::updateStatus');
        $routes->post('upload-berkas', 'Pic\IkhController::uploadBerkas');
        $routes->post('upload-kartu', 'Pic\IkhController::uploadKartu');
    });
});

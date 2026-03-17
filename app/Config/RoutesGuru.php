<?php
$routes->group('sw-guru', ['filter' => 'roleCheck:3'], function ($routes) {
    $routes->get('/', 'Guru\HomeController::index');

    $routes->group('mapel', function ($routes) {
        $routes->get('', 'Guru\MapelController::index');
        $routes->post('datatables', 'Guru\MapelController::datatables');
    });

    $routes->group('materi', function ($routes) {
        $routes->get('', 'Guru\MateriController::index');
        $routes->get('lihat/(:segment)/(:segment)', 'Guru\MateriController::lihat/$1/$2');
        $routes->post('store', 'Guru\MateriController::store');
        $routes->get('delete/(:segment)/(:segment)/(:segment)', 'Guru\MateriController::index/$1/$2/$3');
    });

    $routes->group('ujian', function ($routes) {
        $routes->get('', 'Guru\UjianController::index');
        $routes->post('datatables', 'Guru\UjianController::datatables');
        $routes->get('create', 'Guru\UjianController::create');
    });


    $routes->group('bank-soal', function ($routes) {
        $routes->get('', 'Guru\BankSoalController::index');
        $routes->get('create', 'Guru\BankSoalController::create');
        $routes->post('store', 'Guru\BankSoalController::store');
        $routes->get('edit/(:segment)', 'Guru\BankSoalController::edit/$1');
        $routes->post('update', 'Guru\BankSoalController::update');
        $routes->get('delete/(:segment)', 'Guru\BankSoalController::delete/$1');
        $routes->post('upload-summernote', 'Guru\BankSoalController::uploadSummernote');
        $routes->post('delete-image', 'Guru\BankSoalController::deleteImage');
    });

    $routes->group('kategori', function ($routes) {
        $routes->get('', 'Guru\KategoriController::index');
        $routes->get('create', 'Guru\KategoriController::create');
        $routes->post('store', 'Guru\KategoriController::store');
        $routes->post('edit', 'Guru\KategoriController::edit');
        $routes->post('update', 'Guru\KategoriController::update');
        $routes->get('delete/(:segment)', 'Guru\KategoriController::delete/$1');
    });

    $routes->group('profile', function ($routes) {
        $routes->get('', 'Guru\ProfileController::index');
        $routes->post('update', 'Guru\ProfileController::update');
        $routes->post('edit-password', 'Guru\ProfileController::editPassword');
    });

});
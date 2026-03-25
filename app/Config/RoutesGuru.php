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
        $routes->post('edit', 'Guru\MateriController::edit');
        $routes->post('update', 'Guru\MateriController::update');
        $routes->get('delete/(:segment)/(:segment)/(:segment)', 'Guru\MateriController::index/$1/$2/$3');

        $routes->get('lihat-materi/(:segment)/(:segment)/(:segment)', 'Guru\MateriController::lihatMateri/$1/$2/$3');
        $routes->post('get-file-materi', 'Guru\MateriController::getFileMateri');
        $routes->post('get-chat-materi', 'Guru\MateriController::getChatMateri');
        $routes->post('chat-materi', 'Guru\MateriController::ChatMateri');
    });

    $routes->group('ujian', function ($routes) {
        $routes->get('', 'Guru\UjianController::index');
        $routes->get('create', 'Guru\UjianController::create');
        $routes->post('store', 'Guru\UjianController::store');
        $routes->get('edit-ujian/(:segment)', 'Guru\UjianController::editUjian/$1');
        $routes->post('update', 'Guru\UjianController::update');
        $routes->get('edit-soal/(:segment)', 'Guru\UjianController::editSoal/$1');
        $routes->post('update-soal', 'Guru\UjianController::updateSoal');
        $routes->post('get-bank-soal', 'Guru\UjianController::getBankSoal');
        $routes->post('tambah-bank-soal', 'Guru\UjianController::tambahBankSoal');
        $routes->post('upload-summernote', 'Guru\UjianController::uploadSummernote');
        $routes->post('delete-image', 'Guru\UjianController::deleteImage');
        
        $routes->get('lihat-ujian/(:segment)', 'Guru\UjianController::lihatUjian/$1');
        $routes->post('ajaxSiswaUjian/(:segment)', 'Guru\UjianController::ajaxSiswaUjian/$1');
        $routes->get('lihat-ujian-siswa/(:segment)/(:segment)', 'Guru\UjianController::lihatUjianSiswa/$1/$2');
        $routes->get('cetak-soal-peserta/(:segment)/(:segment)', 'Guru\UjianController::cetakSoalPeserta/$1/$2');
        $routes->get('cetak-soal/(:segment)', 'Guru\UjianController::cetakSoal/$1');
        
        
        $routes->get('download-template', 'Guru\UjianController::downloadTemplate');
        $routes->post('import-soal-excel', 'Guru\UjianController::importSoalExcel');
        $routes->post('ubah-status-ujian', 'Guru\UjianController::ubahStatusUjian');

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
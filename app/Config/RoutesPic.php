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

    $routes->group('sertifikat', function ($routes) {
        $routes->get('brevet-ab/(:segment)', 'Pic\SertifikatController::brevetAb/$1');
    });


    $routes->group('profile', function ($routes) {
        $routes->get('', 'Pic\ProfileController::index');
        $routes->post('update', 'Pic\ProfileController::update');
        $routes->post('edit-password', 'Pic\ProfileController::editPassword');
    });

    $routes->group('cetak-pdf', function ($routes) {
        $routes->get('cv/(:segment)', 'Admin\PdfController::cetakCv/$1');
        $routes->get('pernyataan-bukan-pns/(:segment)', 'Admin\PdfController::cetakSuratPernyataanBukanPns/$1');
        $routes->get('pernyataan-pengajuan-ikh/(:segment)', 'Admin\PdfController::cetakSuratPernyataanIkh/$1');
        $routes->get('pakta-integritas/(:segment)', 'Admin\PdfController::cetakPaktaIntegritas/$1');
        $routes->get('formulir-pemesanan-ikh/(:segment)', 'Admin\PdfController::cetakFormulirPemesananIkh/$1');
    });
});

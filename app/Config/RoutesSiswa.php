<?php
$routes->get('sw-siswa/transaksi/pesan/(:segment)', 'Siswa\TransaksiController::pesan/$1');
$routes->get('sw-siswa/transaksi/pesan/(:segment)/(:segment)', 'Siswa\TransaksiController::pesan/$1/$2');

$routes->group('sw-siswa', ['filter' => 'roleCheck:2'], function ($routes) {
    $routes->get('/', 'Siswa\HomeController::index');

    // affilate
    $routes->group('affiliate', function ($routes) {
        $routes->get('/', 'Siswa\AffiliateController::index');
        $routes->get('create', 'Siswa\AffiliateController::create');
        $routes->get('edit/(:segment)', 'Siswa\AffiliateController::edit/$1');
        $routes->post('save', 'Siswa\AffiliateController::save');
        $routes->post('delete', 'Siswa\AffiliateController::delete');
        $routes->post('copy', 'Siswa\AffiliateController::copy');
        $routes->get('getDetailPencairan/(:segment)', 'Siswa\AffiliateController::getDetailPencairan/$1');
    });

    // transaksi
    $routes->group('transaksi', function ($routes) {
        $routes->get('invoice/(:any)', 'InvoiceController::invoice/$1');
        $routes->get('', 'Siswa\TransaksiController::index');
        $routes->post('cek-kode-voucher', 'Siswa\TransaksiController::cekKodeVoucher');
        $routes->post('checkout', 'Siswa\TransaksiController::checkout');
        $routes->get('pesan-bayar/(:segment)', 'Siswa\TransaksiController::pesanBayar/$1');
        $routes->get('manual-bayar/(:segment)', 'Siswa\TransaksiController::manualBayar/$1');
        $routes->post('upload-bukti-bayar', 'Siswa\TransaksiController::uploadBuktiBayar');
        $routes->post('update-metode-pembayaran', 'Siswa\TransaksiController::updateMetodePembayaran');

        //midtrans
        $routes->get('midtrans-bayar/(:segment)', 'Siswa\TransaksiController::midtransBayar/$1');
    });

    $routes->group('materi',['filter' => 'cekData'] , function($routes){
        $routes->get('/', 'Siswa\MateriController::index');
        $routes->get('lihat-materi/(:segment)/(:segment)/(:segment)', 'Siswa\MateriController::lihatMateri/$1/$2/$3');
        $routes->post('chat-materi', 'Siswa\MateriController::chatMateri');
        $routes->post('get-chat-materi', 'Siswa\MateriController::getChatMateri');
        $routes->post('get-file-materi', 'Siswa\MateriController::getFileMateri');
        $routes->post('update-chat-materi', 'Siswa\MateriController::updateChatMateri');
        $routes->post('delete-chat-materi', 'Siswa\MateriController::deleteChatMateri');
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

    // diskusi
    $routes->group('diskusi',['filter' => 'cekData'] , function ($routes) {
        $routes->get('/', 'Siswa\DiskusiController::index');
        $routes->get('get-messages/(:any)', 'Siswa\DiskusiController::getMessages/$1');
        $routes->post('send', 'Siswa\DiskusiController::sendMessage');
    });


    $routes->group('ikh',['filter' => 'cekData'] , function ($routes) {
        $routes->get('/', 'Siswa\IKHController::index');
        $routes->post('store', 'Siswa\IKHController::store');
        $routes->post('generate-sertifikat-drive', 'Siswa\IKHController::generateSertifikatDrive');
        $routes->post('upload-ajax', 'Siswa\IKHController::uploadFileAjax');
        $routes->get('perbaikan/(:segment)', 'Siswa\IKHController::perbaikan/$1');
        $routes->get('perpanjang/(:segment)', 'Siswa\IKHController::perpanjang/$1');
    });

    $routes->group('webinar', function($routes){
        $routes->get('/', 'Siswa\WebinarController::index');
        $routes->get('lihat-materi', 'Siswa\WebinarController::lihatMateri');
        $routes->get('sertifikat/(:segment)', 'Siswa\WebinarController::sertifikat/$1');
    });
});

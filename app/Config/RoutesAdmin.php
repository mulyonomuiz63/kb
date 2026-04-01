<?php

$routes->group('sw-admin', ['filter' => 'roleCheck:1'], function ($routes) {
    $routes->get('/', 'Admin\HomeController::index');

    $routes->group('siswa', function ($routes) {
        $routes->get('', 'Admin\SiswaController::index');
        $routes->post('datatable', 'Admin\SiswaController::datatable');
        $routes->get('create', 'Admin\SiswaController::create');
        $routes->post('store', 'Admin\SiswaController::store');
        $routes->get('edit/(:segment)', 'Admin\SiswaController::edit/$1');
        $routes->post('update/(:segment)', 'Admin\SiswaController::update/$1');
        $routes->post('detail', 'Admin\SiswaController::detail');
        $routes->get('sertifikat/(:segment)', 'Admin\SiswaController::sertifikat/$1');
        $routes->post('get-data-sertifikat', 'Admin\SiswaController::getDataSertifikat');
        $routes->get('ujian/(:segment)', 'Admin\SiswaController::ujian/$1');
        $routes->post('getDataUjian', 'Admin\SiswaController::getDataUjian');

        //cetak sertifikat
        // Route tanpa segmen ketiga
        $routes->get('lihatSertifikat/(:segment)/(:segment)', 'Admin\SiswaController::lihatSertifikat/$1/$2');
        $routes->get('lihatSertifikatBrevet/(:segment)', 'Admin\SiswaController::lihatSertifikatBrevet/$1');
        // Route dengan segmen ketiga
        $routes->get('lihatSertifikat/(:segment)/(:segment)/(:any)', 'Admin\SiswaController::lihatSertifikat/$1/$2/$3');
        $routes->get('lihatSertifikatBrevet/(:segment)/(:any)', 'Admin\SiswaController::lihatSertifikatBrevet/$1/$2');

        $routes->post('updateKuota', 'Admin\SiswaController::updateKuota');
        $routes->get('deleteUjian/(:segment)/(:segment)', 'Admin\SiswaController::deleteUjian/$1/$2');
        $routes->get('sertifikat-ab', 'Admin\SiswaController::sertifikatAB');
    });

    $routes->group('guru', function ($routes) {
        $routes->get('', 'Admin\GuruController::index');
        $routes->post('datatable', 'Admin\GuruController::datatable');
        $routes->get('create', 'Admin\GuruController::create');
        $routes->post('store', 'Admin\GuruController::store');
        $routes->get('edit/(:segment)', 'Admin\GuruController::edit/$1');
        $routes->post('update/(:segment)', 'Admin\GuruController::update/$1');
        $routes->get('delete/(:segment)', 'Admin\GuruController::delete/$1');


        $routes->get('ujian-guru/(:segment)', 'Admin\GuruController::ujianGuru/$1');
        $routes->post('ajaxUjianGuru', 'Admin\GuruController::ajaxUjianGuru');
        $routes->get('lihat-ujian/(:segment)', 'Admin\GuruController::lihatUjian/$1');
        $routes->post('ajaxSiswaUjian/(:segment)', 'Admin\GuruController::ajaxSiswaUjian/$1');
        $routes->get('lihat-ujian-siswa/(:segment)/(:segment)', 'Admin\GuruController::lihatUjianSiswa/$1/$2');
        $routes->get('cetak-soal-peserta/(:segment)/(:segment)', 'Admin\GuruController::cetakSoalPeserta/$1/$2');
        $routes->get('cetak-soal/(:segment)', 'Admin\GuruController::cetakSoal/$1');
        
        
        $routes->get('mapel-guru/(:segment)', 'Admin\MapelController::mapelGuru/$1');
        $routes->get('lihat-materi/(:segment)/(:segment)/(:segment)', 'Admin\MapelController::lihatMateri/$1/$2/$3');
        $routes->post('get-file-materi', 'Admin\MapelController::getFileMateri');
        $routes->post('get-chat-materi', 'Admin\MapelController::getChatMateri');
        $routes->post('chat-materi', 'Admin\MapelController::ChatMateri');

    });

    $routes->group('mitra', function ($routes) {
        $routes->get('', 'Admin\MitraController::index');
        $routes->post('datatable', 'Admin\MitraController::datatable');
        $routes->get('create', 'Admin\MitraController::create');
        $routes->post('store', 'Admin\MitraController::store');
        $routes->get('edit/(:segment)', 'Admin\MitraController::edit/$1');
        $routes->post('update', 'Admin\MitraController::update');
        $routes->get('delete/(:segment)', 'Admin\MitraController::delete/$1');
        $routes->post('mitra-by-id', 'Admin\MitraController::getMitraById');


        $routes->get('voucher/(:segment)', 'Admin\MitraController::voucher/$1');
        $routes->post('get-voucher', 'Admin\MitraController::getVoucher');
        $routes->post('store-voucher', 'Admin\MitraController::storeVoucher');
        $routes->post('update-voucher', 'Admin\MitraController::updateVoucher');
        $routes->post('edit-voucher', 'Admin\MitraController::editVoucher');

        $routes->get('daftar-paket/(:segment)', 'Admin\MitraController::daftarPaket/$1');
        $routes->post('store-voucher-paket', 'Admin\MitraController::storeVoucherPaket');
        $routes->get('delete-voucher-paket/(:any)/(:any)', 'Admin\MitraController::deleteVoucherPaket/$1/$2');

        $routes->get('detail-komisi/(:segment)', 'Admin\MitraController::detailKomisi/$1');
        $routes->post('validasi-transaksi', 'Admin\MitraController::validasiTransaksi');
    });

    $routes->group('pic', function ($routes) {
        $routes->get('', 'Admin\PicController::index');
        $routes->post('store', 'Admin\PicController::store');
        $routes->post('edit', 'Admin\PicController::edit'); // Route AJAX untuk ambil data
        $routes->post('update', 'Admin\PicController::update'); // Route Proses Update
        $routes->get('delete/(:any)', 'Admin\PicController::delete/$1');
    });

    $routes->group('kelas', function ($routes) {
        $routes->get('', 'Admin\KelasController::index');
        $routes->post('store', 'Admin\KelasController::store');
        $routes->post('edit', 'Admin\KelasController::edit'); // Route AJAX untuk ambil data
        $routes->post('update', 'Admin\KelasController::update'); // Route Proses Update
        $routes->get('delete/(:any)', 'Admin\KelasController::delete/$1');
    });

    $routes->group('mapel', function ($routes) {
        $routes->get('', 'Admin\MapelController::index');
        $routes->post('datatables', 'Admin\MapelController::datatables');
        $routes->post('store', 'Admin\MapelController::store');
        $routes->post('edit', 'Admin\MapelController::edit'); // Route AJAX untuk ambil data
        $routes->post('update', 'Admin\MapelController::update'); // Route Proses Update
        $routes->get('delete/(:segment)', 'Admin\MapelController::delete/$1');
    });

    $routes->group('relasi', function ($routes) {
        $routes->get('', 'Admin\RelasiController::index');
        $routes->get('atur-relasi/(:segment)', 'Admin\RelasiController::aturRelasi/$1');
        $routes->post('guru-kelas', 'Admin\RelasiController::guruKelas');
        $routes->post('guru-mapel', 'Admin\RelasiController::guruMapel');
    });

    $routes->group('iklan', function ($routes) {
        $routes->get('', 'Admin\IklanController::index');
        $routes->post('datatables', 'Admin\IklanController::datatables');
        $routes->post('store', 'Admin\IklanController::store');
        $routes->get('edit/(:segment)', 'Admin\IklanController::edit/$1'); // Route AJAX untuk ambil data
        $routes->post('update', 'Admin\IklanController::update'); // Route Proses Update
        $routes->get('delete/(:segment)', 'Admin\IklanController::delete/$1');
    });

    $routes->group('artikel', function ($routes) {
        $routes->get('', 'Admin\ArtikelController::index');
        $routes->post('datatables', 'Admin\ArtikelController::datatables');
        $routes->get('create', 'Admin\ArtikelController::create');
        $routes->post('store', 'Admin\ArtikelController::store');
        $routes->get('edit/(:segment)', 'Admin\ArtikelController::edit/$1'); // Route AJAX untuk ambil data
        $routes->post('update', 'Admin\ArtikelController::update'); // Route Proses Update
        $routes->get('delete/(:segment)', 'Admin\ArtikelController::delete/$1');

        $routes->post('upload-summernote', 'Admin\ArtikelController::uploadSummernote');
        $routes->post('delete-image', 'Admin\ArtikelController::deleteImage');
        $routes->post('delete-tag/(:segment)', 'Admin\ArtikelController::deleteTag/$1');
    });

    $routes->group('twibbon', function ($routes) {
        $routes->get('', 'Admin\TwibbonController::index');
        $routes->post('datatables', 'Admin\TwibbonController::datatables');
        $routes->post('store', 'Admin\TwibbonController::store');
        $routes->post('edit', 'Admin\TwibbonController::edit');
        $routes->get('delete/(:segment)', 'Admin\TwibbonController::delete/$1');
        $routes->post('cek-url', 'Admin\TwibbonController::cekUrl');
    });

    $routes->group('galeri', function ($routes) {
        $routes->get('', 'Admin\GaleriController::index');
        $routes->post('datatables', 'Admin\GaleriController::datatables');
        $routes->post('store', 'Admin\GaleriController::store');
        $routes->post('edit', 'Admin\GaleriController::edit');
        $routes->post('update', 'Admin\GaleriController::update');
        $routes->get('delete/(:segment)', 'Admin\GaleriController::delete/$1');
    });

    $routes->group('testimoni', function ($routes) {
        $routes->get('', 'Admin\TestimoniController::index');
        $routes->post('datatables', 'Admin\TestimoniController::datatables');
        $routes->post('store', 'Admin\TestimoniController::store');
        $routes->post('edit', 'Admin\TestimoniController::edit');
        $routes->post('update', 'Admin\TestimoniController::update');
        $routes->get('delete/(:segment)', 'Admin\TestimoniController::delete/$1');
    });


    $routes->group('diskon', function ($routes) {
        $routes->get('', 'Admin\DiskonController::index');
        $routes->post('datatables', 'Admin\DiskonController::datatables');
        $routes->post('store', 'Admin\DiskonController::store');
        $routes->post('edit', 'Admin\DiskonController::edit');
        $routes->post('update', 'Admin\DiskonController::update');
        $routes->get('delete/(:segment)', 'Admin\DiskonController::delete/$1');
    });

    $routes->group('paket', function ($routes) {
        $routes->get('', 'Admin\PaketController::index');
        $routes->post('ujian-master', 'Admin\PaketController::ujianMaster');
        $routes->post('get-mapel', 'Admin\PaketController::getMapel');
        $routes->post('reorder', 'Admin\PaketController::reorder');
        $routes->post('pin', 'Admin\PaketController::pin');

        // Route pendukung lainnya (pastikan sudah ada)
        $routes->post('store', 'Admin\PaketController::store');
        $routes->post('edit', 'Admin\PaketController::edit');
        $routes->post('update', 'Admin\PaketController::update');

        $routes->get('review/(:segment)', 'Admin\PaketController::review/$1');
        $routes->post('edit-review', 'Admin\PaketController::editReview');
        $routes->post('update-review', 'Admin\PaketController::updateReview');
    });

    $routes->group('transaksi', function($routes) {
        $routes->get('/', 'Admin\TransaksiController::index');
        $routes->post('datatables', 'Admin\TransaksiController::datatables'); // Server Side
        $routes->get('transaksi-kodevoucher', 'Admin\TransaksiController::transaksiKodevoucher');
        $routes->post('validasi-transaksi', 'Admin\TransaksiController::validasiTransaksi');
        $routes->post('approve-transaksi', 'Admin\TransaksiController::approveTransaksi');
        $routes->get('approve-manual/(:any)', 'Admin\TransaksiController::approveManual/$1');
        $routes->get('hapus-transaksi-siswa/(:any)', 'Admin\TransaksiController::hapusTransaksiSiswa/$1');
        
        //untuk di terapkan di cronjob, hapus transaksi yang sudah lebih dari 1 hari atau expired
        $routes->get('hapus-transaksi', 'Admin\TransaksiController::hapusTransaksi');
        $routes->get('invoice/(:any)', 'InvoiceController::invoice/$1'); // Route untuk cetak invoice (modal)   
    });

    $routes->group('affiliate', function ($routes) {
        $routes->get('/', 'Admin\AffiliateController::index');
        $routes->post('datatables', 'Admin\AffiliateController::datatables');
        $routes->get('create', 'Admin\AffiliateController::create');
        $routes->get('edit/(:segment)', 'Admin\AffiliateController::edit/$1');
        $routes->post('store', 'Admin\AffiliateController::store');
        $routes->get('komisi/(:segment)', 'Admin\AffiliateController::listKomisi/$1');
        $routes->post('processKomisi', 'Admin\AffiliateController::processKomisi');
    });

    $routes->group('profile', function ($routes) {
        $routes->get('', 'Admin\ProfileController::index');
        $routes->post('update-profile', 'Admin\ProfileController::updateProfile');
        $routes->post('update-password', 'Admin\ProfileController::updatePassword');
    });

    $routes->group('settings', function ($routes) {
        $routes->get('', 'Admin\SettingsController::index');
        $routes->post('update', 'Admin\SettingsController::update');
    }); 
});
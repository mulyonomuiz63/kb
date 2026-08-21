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
        $routes->get('delete/(:segment)', 'Admin\SiswaController::delete/$1');
        $routes->post('detail', 'Admin\SiswaController::detail');
        $routes->get('sertifikat/(:segment)', 'Admin\SiswaController::sertifikat/$1');
        $routes->post('suspend-action', 'Admin\SiswaController::suspendAction');
        $routes->post('get-data-sertifikat', 'Admin\SiswaController::getDataSertifikat');
        $routes->get('ujian/(:segment)', 'Admin\SiswaController::ujian/$1');
        $routes->post('get-data-ujian', 'Admin\SiswaController::getDataUjian');
        $routes->get('webinar/(:segment)', 'Admin\SiswaController::webinar/$1');
        
        $routes->post('processImportBatch', 'Admin\SiswaController::processImportBatch');

        //cetak sertifikat
        // Route tanpa segmen ketiga
        $routes->get('lihatSertifikat/(:segment)/(:segment)', 'Admin\SiswaController::lihatSertifikat/$1/$2');
        $routes->get('lihatSertifikatBrevet/(:segment)', 'Admin\SiswaController::lihatSertifikatBrevet/$1');
        // Route dengan segmen ketiga
        $routes->get('lihatSertifikat/(:segment)/(:segment)/(:any)', 'Admin\SiswaController::lihatSertifikat/$1/$2/$3');
        $routes->get('lihatSertifikatBrevet/(:segment)/(:any)', 'Admin\SiswaController::lihatSertifikatBrevet/$1/$2');

        $routes->post('updateKuota', 'Admin\SiswaController::updateKuota');
        $routes->get('delete-ujian/(:segment)/(:segment)', 'Admin\SiswaController::deleteUjian/$1/$2');
        $routes->get('sertifikat-ab', 'Admin\SiswaController::sertifikatAB');

        //untuk update data siswa menjadi untuk melengkapi data siswa yang belum lengkap
        $routes->get('update-status-massal/', 'Admin\SiswaController::updateStatusMassal'); 

        $routes->group('materi', function($routes){
            $routes->get('(:segment)', 'Admin\MateriController::index/$1');
        });
    });

    $routes->group('guru', function ($routes) {
        $routes->get('', 'Admin\GuruController::index');
        $routes->post('datatable', 'Admin\GuruController::datatable');
        $routes->get('create', 'Admin\GuruController::create');
        $routes->post('store', 'Admin\GuruController::store');
        $routes->get('edit/(:segment)', 'Admin\GuruController::edit/$1');
        $routes->post('update/(:segment)', 'Admin\GuruController::update/$1');
        $routes->get('delete/(:segment)', 'Admin\GuruController::delete/$1');


        $routes->get('ujian/(:segment)', 'Admin\GuruController::ujianGuru/$1');

        $routes->get('mapel/(:segment)', 'Admin\MapelController::mapelGuru/$1');
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

    $routes->group('afiliasi', function ($routes) {
        $routes->get('', 'Admin\AfiliasiController::index');
        $routes->post('store', 'Admin\AfiliasiController::store');
        $routes->post('edit', 'Admin\AfiliasiController::edit'); // Route AJAX untuk ambil data
        $routes->post('update', 'Admin\AfiliasiController::update'); // Route Proses Update
        $routes->get('delete/(:any)', 'Admin\AfiliasiController::delete/$1');
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
    $routes->group('webinar', function ($routes) {
        $routes->get('', 'Admin\WebinarController::index');
        $routes->post('datatables', 'Admin\WebinarController::datatables');
        $routes->post('store', 'Admin\WebinarController::store');
        $routes->post('edit', 'Admin\WebinarController::edit');
        $routes->post('update', 'Admin\WebinarController::update');
        $routes->get('delete/(:segment)', 'Admin\WebinarController::delete/$1');
        $routes->get('sertifikat/(:segment)/(:segment)', 'Admin\WebinarController::sertifikat/$1/$2');
    });
    

    $routes->group('paket', function ($routes) {
        $routes->get('', 'Admin\PaketController::index');
        $routes->post('ujian-master', 'Admin\PaketController::ujianMaster');
        $routes->post('get-mapel', 'Admin\PaketController::getMapel');
        $routes->post('reorder', 'Admin\PaketController::reorder');
        $routes->post('pin', 'Admin\PaketController::pin');
        $routes->get('delete/(:segment)', 'Admin\PaketController::delete/$1');

        // Route pendukung lainnya (pastikan sudah ada)
        $routes->post('store', 'Admin\PaketController::store');
        $routes->post('edit', 'Admin\PaketController::edit');
        $routes->post('update', 'Admin\PaketController::update');

        $routes->get('review/(:segment)', 'Admin\PaketController::review/$1');
        $routes->post('edit-review', 'Admin\PaketController::editReview');
        $routes->post('update-review', 'Admin\PaketController::updateReview');

        $routes->post('ujian-master', 'Admin\PaketController::getUjianMaster'); // Ganti 'Paket' dengan nama Controller Anda
        $routes->post('get-mapel', 'Admin\PaketController::getMapel');
        $routes->post('get-webinar-sesi', 'Admin\PaketController::getWebinarSesi');
        $routes->post('kirim-email-peserta', 'Admin\PaketController::kirimEmailPeserta');
    });

    $routes->group('transaksi', function ($routes) {
        $routes->get('/', 'Admin\TransaksiController::index');
        $routes->post('datatables', 'Admin\TransaksiController::datatables'); // Server Side
        $routes->get('transaksi-kodevoucher', 'Admin\TransaksiController::transaksiKodevoucher');
        $routes->post('validasi-transaksi', 'Admin\TransaksiController::validasiTransaksi');
        $routes->post('approve-transaksi', 'Admin\TransaksiController::approveTransaksi');
        $routes->get('approve-manual/(:any)', 'Admin\TransaksiController::approveManual/$1');
        $routes->get('hapus-transaksi-siswa/(:any)', 'Admin\TransaksiController::hapusTransaksiSiswa/$1');

        //export data siswa
        $routes->get('export-excel', 'Admin\TransaksiController::exportExcel');

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
        $routes->get('getDetailPencairan/(:segment)', 'Admin\AffiliateController::getDetailPencairan/$1');
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

    // diskusi
    $routes->group('diskusi', function ($routes) {
        $routes->get('/', 'Admin\DiskusiController::index');
        $routes->get('get-messages/(:any)', 'Admin\DiskusiController::getMessages/$1');
        $routes->post('send', 'Admin\DiskusiController::sendMessage');
    });


    $routes->group('ikh', function ($routes) {
        $routes->get('', 'Admin\IkhController::index');
        $routes->get('review/(:segment)', 'Admin\IkhController::review/$1');
        $routes->post('update-status', 'Admin\IkhController::updateStatus');
        $routes->post('update-pemohon', 'Admin\IkhController::updatePemohon');
        $routes->post('upload-ajax', 'Admin\IkhController::uploadFileAjax');
        $routes->post('upload-berkas', 'Admin\IkhController::uploadBerkas');
        $routes->post('upload-kartu', 'Admin\IkhController::uploadKartu');
    });

    $routes->group('cetak-pdf', function ($routes) {
        $routes->get('cv/(:segment)', 'Admin\PdfController::cetakCv/$1');
        $routes->get('pernyataan-bukan-pns/(:segment)', 'Admin\PdfController::cetakSuratPernyataanBukanPns/$1');
        $routes->get('pernyataan-pengajuan-ikh/(:segment)', 'Admin\PdfController::cetakSuratPernyataanIkh/$1');
        $routes->get('pakta-integritas/(:segment)', 'Admin\PdfController::cetakPaktaIntegritas/$1');
        $routes->get('formulir-pemesanan-ikh/(:segment)', 'Admin\PdfController::cetakFormulirPemesananIkh/$1');
    });
    
    $routes->group('review', function ($routes) {
        $routes->get('', 'Admin\ReviewController::index');
    });

    $routes->group('log-email', function ($routes) {
        $routes->get('', 'Admin\LogEmailController::index');
        $routes->post('datatables', 'Admin\LogEmailController::datatables');
        $routes->post('delete-old', 'Admin\LogEmailController::deleteOld');
    });
});

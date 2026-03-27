<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="sFNRbIOQMaNWDjh3kNFm_juMtXCUN_O2DLmZu_zNkkU" />

    <title><?= title(); ?></title>

    <meta name="keywords" content="Kelas Brevet AB, Kelas Pajak, Brevet AB, Kursus Brevet Pajak, brevet, pajak, brevet pajak AB, kursus pajak, pelatihan pajak, brevet A&B, pelatihan pajak terapan brevet a&b terpadu, pelatihan perpajakan, perpajakan, kursus pajak offline, kursus pajak online, brevet pajak adalah, Keunggulan pelatihan pajak, manfaat kursus brevet pajak, lokasi pelatihan brevet, lokasi pelatihan pajak, fasilitas pelatihan pajak, fasilias pelatihan brevet, berapa lama pelatihan brevet, berapa lama kursus pelatihan brevet, sertifikat brevet">

    <meta name="description" content="<?= !empty($dataMeta) ? $dataMeta->nama : "Kelas Brevet Pajak Online Terpercaya Di Indonesia"; ?>">
    <meta name="robots" content="index, follow">

    <!-- Open Graph -->
    <meta property="og:title" content="<?= title(); ?>" />
    <meta property="og:description" content="<?= !empty($dataMeta) ? $dataMeta->nama : "Kelas Brevet Pajak Online Terpercaya Di Indonesia"; ?>" />
    <meta property="og:url" content="<?= !empty($dataMeta) ? $dataMeta->url : base_url('/'); ?>" />
    <meta property="og:article:section" content="<?= title(); ?>" />
    <meta property="og:image" content="<?= !empty($dataMeta) ? base_url('uploads/iklan/thumbnails/' . $dataMeta->file) : base_url(favicon()); ?>" />
    <meta property="og:image:alt" content="<?= title(); ?>" />
    <meta property="og:image:type" content="image/jpeg" />

    <link rel="canonical" href="<?= current_url() ?>" />


    <!-- Google tag (gtag.js) -->
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-LVJ4K7XNX9"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-LVJ4K7XNX9');
    </script>

    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-MFDZ3PMC');
    </script>
    <!-- End Google Tag Manager -->

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url(favicon()); ?>" />

    <!-- CSS
	============================================ -->

    <!-- Icon Font CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url('assets-landing/css/plugins/icofont.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets-landing/css/plugins/flaticon.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets-landing/css/plugins/font-awesome.min.css'); ?>">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="<?= base_url('assets-landing/css/plugins/animate.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets-landing/css/plugins/swiper-bundle.min.css'); ?>">

    <!-- Main Style CSS -->


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets-landing/css/style.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets-landing/css/custome.css'); ?>?v=8">

    <link href="<?= base_url('assets/app-assets/template/cbt-malela'); ?>/plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/app-assets/template/cbt-malela'); ?>/plugins/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />
    <script src="<?= base_url('assets/app-assets/template/cbt-malela'); ?>/plugins/sweetalerts/sweetalert2.min.js"></script>
    <style>
        .nice-select {
            display: none;
        }

        .user-info img {
            width: 90px !important;
            height: 90px !important;
            border-radius: 50% !important;
            object-fit: cover !important;
            /* ↓ Atur posisi wajah agar kepala tidak terpotong */
            object-position: center top !important;
        }
    </style>
    <style>
        /* ===== IMAGE ===== */
        .courses-images {
            overflow: hidden;
        }

        .courses-images img {
            transition: transform .45s ease;
        }

        .single-courses:hover .courses-images img {
            transform: scale(1.08);
        }

        /* ===== TITLE ===== */
        .courses-content h4.title {
            font-size: 16px;
            font-weight: 600;
            line-height: 1.4;
            margin-bottom: 6px;
        }

        /* ===== PRICE ===== */
        .courses-meta .fw-bold {
            font-size: 17px;
            color: #1A4FF0;
        }

        /* ===== AFFILIATE ===== */
        .affiliate-box {
            background: linear-gradient(135deg, #f8fbff, #eef4ff);
            border: 1px dashed #c9d9ff;
            border-radius: 10px;
            font-size: 12px;
            animation: fadeUp .5s ease;
        }

        /* ===== BUTTON ===== */
        .btn-buy {
            background: #1A4FF0;
            color: #fff;
            border-radius: 10px;
            border: none;
            padding: 0px 15px;
            font-weight: 600;
            transition: .3s ease;
        }

        .btn-buy:hover {
            background: #d0011b;
            color: #fff;
            transform: scale(1.05);
        }

        /* ===== DISCOUNT BADGE ===== */
        .diskon {
            background: linear-gradient(45deg, #ff4d4d, #ff9800);
            font-size: 12px;
            border-radius: 0 0 0 14px;
            animation: pulse 1.5s infinite;
        }


        /* ===== KEYFRAMES ===== */
        @keyframes pulse {
            0% {
                opacity: 1
            }

            50% {
                opacity: .6
            }

            100% {
                opacity: 1
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(8px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        /* ===== RESPONSIVE ===== */
        @media(max-width:576px) {
            .courses-content h4.title {
                font-size: 15px;
            }

            .btn-buy {
                padding: 10px;
                font-size: 14px;
            }
        }

        .btn-buy-copy {
            background: #DCDCDC;
            color: #212121;
            border-radius: 10px;
            border: none;
            padding: 0px 15px;
            font-weight: 600;
            transition: .3s ease;
        }

        .btn-buy-copy:hover {
            background: #d0011b;
            color: #fff;
            transform: scale(1.05);
        }

        .btn-buy-wa {
            background: #90EE90;
            color: #fff;
            border-radius: 10px;
            border: none;
            padding: 0px 15px;
            font-weight: 600;
            transition: .3s ease;
        }

        .btn-buy-wa:hover {
            background: #d0011b;
            color: #fff;
            transform: scale(1.05);
        }
    </style>
    <!-- ✅ SEO AUTO -->
    <?= $schema ?>
</head>

<body>

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MFDZ3PMC"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php
    $db = Config\Database::connect();
    $statistik_hariini = $db->query("SELECT COUNT(id) as total FROM `statistik_hits` WHERE date(created_at) = date(now())")->getRowObject();
    $statistik_lampau = $db->query("SELECT COUNT(id) as total  FROM `statistik_hits` WHERE date(created_at) = DATE(NOW() - INTERVAL 1 DAY)")->getRowObject();
    $statistik_minggu = $db->query("SELECT COUNT(id) as total  FROM `statistik_hits` WHERE DATE(created_at) >= DATE(NOW() - INTERVAL 7 DAY)")->getRowObject();
    $statistik_bulan = $db->query("SELECT COUNT(id) as total  FROM `statistik_hits` WHERE DATE(created_at) >= DATE(NOW() - INTERVAL 30 DAY)")->getRowObject();
    ?>
    <div class="main-wrapper">

        <!-- Header Section Start -->
        <div class="header-section">

            <!-- Header Top Start -->
            <div class="header-top d-none d-lg-block">
                <div class="container">
                    <!-- Header Top Wrapper Start -->
                    <div class="header-top-wrapper">
                        <!-- Header Top Left Start -->
                        <div class="header-top-left d-flex align-items-center">
                            <p class="typewrite" data-period="2000" data-type='[ "Akuntanmu Learning Center Menjadi Lembaga Pelatihan Brevet Pajak AB Resmi Di Indonesia" ]'></p>
                        </div>
                        <!-- Header Top Left End -->
                        <!-- Header Top Right Start -->
                        <div class="header-top-right">
                            <a href="https://kelasbrevet.com/transaksi/pesan/L2hSZzNKUHpPeG02L3NIdDhKOHNZQT09" class="badge text-bg-primary">Ambil Promo</a>
                        </div>
                        <!-- Header Top Right End -->
                    </div>
                    <!-- Header Top Wrapper End -->
                </div>
            </div>
            <!-- Header Top End -->
            <div class="header-toggle d-lg-none d-flex justify-content-start p-4">
                <a class="menu-toggle" href="javascript:void(0)">
                    <span></span>
                    <span></span>
                    <span></span>
                </a>
            </div>
            <!-- Header Main Start -->
            <div class="header-main d-none d-lg-block">
                <div class="container">

                    <!-- Header Main Start -->
                    <div class="header-main-wrapper">

                        <!-- Header Logo Start -->
                        <div class="header-logo">
                            <a href="<?= base_url('/') ?>"><img src="<?= base_url('assets-landing/images/logo-putih.png') ?>" alt="<?= setting('app_name') ?>" class="img-fluid"></a>
                        </div>
                        <!-- Header Logo End -->

                        <!-- Header Menu Start -->
                        <div class="horizontal-nav d-none d-lg-block ">
                            <ul class="nav-menu ">
                                <li>
                                    <a href="<?= base_url("tentangkami") ?>" class="text-white  btn-hover-dark">Tentang Kami</a>
                                </li>
                                <li>
                                    <a href="<?= base_url("pelatihan") ?>" class="text-white">Pelatihan</a>
                                </li>
                                <li>
                                    <a href="<?= base_url("penilaian") ?>" class="text-white">Penilaian</a>
                                </li>
                                <li>
                                    <a href="<?= base_url("testimoni") ?>" class="text-white">Testimoni</a>
                                </li>
                                <li>
                                    <a href="#" class="text-white">Informasi <i class="bi bi-chevron-down"></i></a>
                                    <div class="submenu">
                                        <ul>
                                            <li class="text-dark"><a href="<?= base_url("artikel") ?>"><i class="bi bi-file-richtext me-2"></i>Artikel</a></li>
                                            <!--<li class="text-dark"><a href="<?= base_url("jadwal") ?>"><i class="bi bi-calendar me-2"></i>Jadwal</a></li>-->
                                            <!--<li class="text-dark"><a href="<?= base_url("galeri") ?>"><i class="bi bi-image me-2"></i>Galeri</a></li>-->
                                            <li class="text-dark"><a href="<?= base_url("media-kelasbrevet") ?>"><i class="bi bi-image me-2"></i>Media</a></li>
                                            <li class="text-dark"><a href="<?= base_url("siap-kerja") ?>"><i class="bi bi-info-square-fill me-2"></i>Siap Kerja</a></li>
                                            <li class="text-dark"><a href="<?= base_url("twibbon") ?>"><i class="bi bi-person-square me-2"></i>Twibbon</a></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>

                            <!--<ul class="nav-menu">-->
                            <!--  <li><a href="#">Beranda</a></li>-->
                            <!--  <li>-->
                            <!--    <a href="#">Produk</a>-->

                            <!--  </li>-->
                            <!--  <li><a href="#">Tentang Kami</a></li>-->
                            <!--  <li><a href="#">Kontak</a></li>-->
                            <!--</ul>-->



                        </div>
                        <!-- Header Menu End -->

                        <!-- Header Sing In & Up Start -->
                        <div class="header-sign-in-up d-none d-lg-block">
                            <ul>
                                <?php if (session('nama') == ''): ?>
                                    <li><a class="sign-in text-white" href="<?= base_url('auth'); ?>">Masuk</a></li> <?php else: ?>
                                    <?php
                                                                                                                        if (session('role') == '1'):
                                                                                                                            $url =  base_url('sw-admin');
                                                                                                                        elseif (session('role') == '2'):
                                                                                                                            $url =  base_url('sw-siswa');
                                                                                                                        elseif (session('role') == '3'):
                                                                                                                            $url =  base_url('sw-guru');
                                                                                                                        elseif (session('role') == '4'):
                                                                                                                            $url =  base_url('sw-mitra');
                                                                                                                        elseif (session('role') == '5'):
                                                                                                                            $url =  base_url('sw-pic');
                                                                                                                        endif;

                                    ?>
                                    <li><a class="sign-in mr-2 text-white" href="<?= $url; ?>"><?= mb_strimwidth(session('nama'), 0, 8, '...')  ?></a></li>

                                <?php endif; ?>
                            </ul>
                        </div>
                        <!-- Header Sing In & Up End -->

                        <!-- Header Mobile Toggle Start -->
                        <div class="header-toggle d-lg-none">
                            <a class="menu-toggle" href="javascript:void(0)">
                                <span></span>
                                <span></span>
                                <span></span>
                            </a>
                        </div>
                        <!-- Header Mobile Toggle End -->

                    </div>
                    <!-- Header Main End -->

                </div>
            </div>
            <!-- Header Main End -->

        </div>

        <!-- Header Section End -->

        <!-- Mobile Menu Start -->
        <div class="mobile-menu">

            <!-- Menu Close Start -->
            <a class="menu-close" href="javascript:void(0)">
                <i class="icofont-close-line"></i>
            </a>
            <!-- Menu Close End -->

            <!-- Mobile Sing In & Up Start -->
            <div class="mobile-sign-in-up">
                <ul>
                    <?php if (session('nama') == ''): ?>
                        <li><a class="sign-in" href="<?= base_url('auth'); ?>">Masuk</a></li>
                        <li><a class="sign-up" href="<?= base_url('auth/registrasi'); ?>">Daftar</a></li>
                    <?php else: ?>
                        <?php
                        if (session('role') == '1'):
                            $url =  base_url('sw-admin');
                        elseif (session('role') == '2'):
                            $url =  base_url('sw-siswa');
                        elseif (session('role') == '3'):
                            $url =  base_url('sw-guru');
                        elseif (session('role') == '4'):
                            $url =  base_url('sw-mitra');
                        elseif (session('role') == '5'):
                            $url =  base_url('sw-pic');
                        endif;

                        ?>
                        <li><a href="<?= $url; ?>"><?= mb_strimwidth(session('nama'), 0, 15, '...')  ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <!-- Mobile Sing In & Up End -->

            <!-- Mobile Menu Start -->
            <div class="mobile-menu-items">
                <ul class="nav-menu text-white">
                    <li>
                        <a href="<?= base_url("tentangkami") ?>">Tentang Kami</a>
                    </li>
                    <li>
                        <a href="<?= base_url("siap-kerja") ?>">Siap Kerja</a>
                    </li>
                    <li>
                        <a href="<?= base_url("pelatihan") ?>">Pelatihan</a>
                    </li>
                    <li>
                        <a href="<?= base_url("penilaian") ?>">Penilaian</a>
                    </li>
                    <li>
                        <a href="<?= base_url("testimoni") ?>">Testimoni</a>
                    </li>
                    <!--<li>-->
                    <!--    <a href="<?= base_url("jadwal") ?>">Jadwal</a>-->
                    <!--</li>-->
                    <!--<li>-->
                    <!--    <a href="<?= base_url("galeri") ?>">Galeri</a>-->
                    <!--</li>-->
                    <li>
                        <a href="<?= base_url("media-kelasbrevet") ?>">Media</a>
                    </li>
                    <li>
                        <a href="<?= base_url("twibbon") ?>">Twibbon</a>
                    </li>
                </ul>

            </div>
            <!-- Mobile Menu End -->
        </div>
        <!-- Mobile Menu End -->

        <!-- Overlay Start -->
        <div class="overlay"></div>
        <!-- Overlay End -->

        <!-- Slider Start -->
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel" data-bs-interval="6000">
            <div class="carousel-inner">
                <?php if (!empty($dataIklanDepan)): ?>
                    <?php
                    $no = 0;
                    foreach ($dataIklanDepan as $rows) :
                    ?>
                        <div class="carousel-item <?= $no == '0' ? 'active' : '' ?>">
                            <div class="d-flex align-items-start  " id="tampilIklanSlide">
                                <?= img_lazy('uploads/iklan/thumbnails/' . $rows->file, $rows->nama, ['class' => 'z-2 position-absolute d-flex align-items-center']) ?>
                                <div class="container d-flex align-items-end z-3" id="tampilIklanSlide">
                                    <!-- Slider Content Start -->
                                    <?php if (!empty($rows->text)): ?>
                                        <div class="slider-content d-none d-lg-block">
                                            <a class="btn btn-primary btn-hover-dark" href="<?= !empty($rows->url) ? $rows->url : base_url(); ?>"><?= $rows->text ?></a>
                                        </div>
                                    <?php endif; ?>
                                    <!-- Slider Content End -->
                                </div>
                            </div>
                        </div>
                    <?php $no++;
                    endforeach;  ?>
                <?php else: ?>
                    <div class="carousel-item active">
                        <div class="d-flex align-items-center d-flex align-items-start" id="tampilIklanSlide">
                            <?= img_lazy('assets-landing/images/slider/team-3.jpg', "kelas brevet AB", ['class' => 'z-2 position-absolute d-flex align-items-center', 'id' => 'tampilIklanSlide']) ?>
                            <div class="container d-flex align-items-start z-3">
                                <!-- Slider Content Start -->
                                <div class="slider-content">
                                    <h4 class="text-white ">Ujian Brevet Pajak AB Online Kapan Saja dan Dimana Saja Lebih Mudah</h4>
                                    <h6 class="main-title text-white ">Harga Mulai <span class="text-white fw-bold">Rp50.000</span>/Materi</h6>
                                    <a class="btn btn-primary btn-hover-dark" href="<?= base_url('auth/registrasi'); ?>">Daftar Sekarang</a>
                                </div>
                                <!-- Slider Content End -->
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Slider End -->
        <div class="section" style="margin-top:-80px">
            <div class="container ">
                <div class="courses-tabs-menu courses-active z-3 bg-white shadow-sm bg-body-tertiary rounded">
                    <div class="swiper-container">
                        <div class="d-flex justify-content-center">
                            <h6>Alumni yang pernah bergabung</h6>
                        </div>
                        <div class="swiper-container tickerwrapper">
                            <ul class="swiper-wrapper nav list">
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/vertica.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/sultanfatih.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/meka.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/istiqomah.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>


                                    <?= img_lazy('assets-landing/images/peserta/husnayain.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/gaspro.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/afco.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/nusapala.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/aliahospital.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/bcm.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/usp.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/rshb aceh.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/thaiwah.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/polinela.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/indra.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/stieni.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/telkom.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/sinar.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/twj.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/dipa.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/gadai.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/gbs.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/farmamedika.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/darmajaya.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/hisotex.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/abeng.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/UnMal.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>
                                <li class='listitem swiper-slide'>

                                    <?= img_lazy('assets-landing/images/peserta/interactive.png', "loading", ['class' => 'img-fluid slider-alumnir']) ?>

                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section section-padding-02" id="tentang-kami">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <!-- Section Title Start -->
                        <div class="row">
                            <div class="section-title shape-02">
                                <h2 class="main-title">Tentang <span>Kelas Brevet</span></h2>
                            </div>
                            <div class="text-justify fs-tentang">
                                Kelas Brevet merupakan platform pelatihan Brevet Pajak AB yang Terdaftar Resmi. Diselenggarakan oleh Akuntanmu Learning Center By Legalyn Konsultan Indonesia (Lembaga Pelatihan, Kursus/Bimbel, yang didirikan sejak tahun 2021). Sebagai upaya merespon kebutuhan peningkatan kompetensi profesi perpajakan di Indonesia, Akuntanmu Learning Center menghadirkan pembelajaran dan ujian Brevet Pajak AB secara online melalui KelasBrevet.com
                            </div>
                            <div class="mt-2 mb-2">
                                <div class="row">
                                    <div class="col-4 col-md-4">
                                        <a href="javascript:void(0)" class="badge text-bg-light p-2 rounded-pill text-primary btn-hover-dark text-izin text-wrap" data-bs-toggle="modal" data-bs-target="#lihatIzinLkp">Izin Operasional LKP</a>
                                    </div>
                                    <div class="col-4 col-md-4">
                                        <a href="javascript:void(0)" class="badge text-bg-light p-2 rounded-pill text-primary btn-hover-dark text-izin text-wrap" data-bs-toggle="modal" data-bs-target="#lihatIzinLpk">Sertifikat Standar LPK</a>
                                    </div>
                                    <div class="col-4 col-md-4">
                                        <a href="javascript:void(0)" class="badge text-bg-light p-2  rounded-pill text-primary btn-hover-dark text-izin text-wrap" data-bs-toggle="modal" data-bs-target="#lihatKemnaker">Publikasi Kemnaker</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Section Title End -->

                    </div>
                    <div class="col-md-6">
                        <div class="card p-4 border-0">
                            <?= img_lazy('assets-landing/images/slider/slider-2.png', "loading", ['class' => 'card-img-top']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section call-to-action-wrapper pb-4 d-flex align-items-center" id="bimbel">
            <div class="">
                <!-- All Courses tab content Start -->
                <div class="tab-content courses-tab-content">
                    <div class="tab-pane fade show active" id="tabs1">

                        <!-- All Courses Wrapper Start -->
                        <div class="courses-wrapper">
                            <h6>Penawaran Paket Brevet Pajak AB</h6>
                            <span>Pilihan ujian yang bisa kamu kuti secara online kapan saja dan dimana saja</span>
                            <div class="row ">
                                <?php foreach ($paket as $rows) : ?>
                                    <?php
                                    // untuk rating
                                    $query = $db->table('paket')->join('detail_paket b', 'paket.idpaket=b.idpaket')->join('ujian_master c', 'b.id_ujian=c.id_ujian')->join('review_ujian d', 'c.kode_ujian=d.kode_ujian')->where('paket.slug', $rows->slug)->get()->getResultObject();

                                    // hitung rata-rata rating
                                    $totalRating = 0;
                                    $jumlahReview = count($query);

                                    foreach ($query as $item) {
                                        $totalRating += $item->rating;
                                    }

                                    $rataRating = $jumlahReview > 0 ? round($totalRating / $jumlahReview, 1) : 0;
                                    ?>
                                    <?php if ($rows->id_mapel == '0' || $rows->id_mapel == '1'): ?>
                                        <div class="col-12 col-md-6 col-lg-4  pt-2">
                                            <!-- Single Courses Start -->
                                            <div class="single-courses card position-relative zoom">
                                                <div class="courses-images">
                                                    <a href="<?= base_url('bimbel/' . $rows->slug) ?>">
                                                        <?= img_lazy('assets-landing/images/paket/thumbnails/' . $rows->file, $rows->nama_paket, ['class' => 'card-img-top']) ?>
                                                    </a>

                                                </div>
                                                <div class="courses-content">
                                                    <h4 class="title"><a href="<?= base_url('bimbel/' . $rows->slug) ?>"><?= $rows->nama_paket ?></a></h4>
                                                    <div class="courses-meta">
                                                        <?php
                                                        $soal = $db->query("SELECT a.id_ujian, b.kode_ujian FROM detail_paket a join ujian_master b on a.id_ujian=b.id_ujian where a.idpaket = '$rows->idpaket' group by a.id_ujian")->getResult();
                                                        $durasi = 0;
                                                        foreach ($soal as $r):
                                                            $total = 0;
                                                            $ujianDetail = $db->query("select * from ujian_detail where kode_ujian = '$r->kode_ujian'")->getResult();
                                                            foreach ($ujianDetail as $dataRows) {
                                                                $total++;
                                                            }
                                                            $jml = $db->query("select count(kode_ujian) as total_soal from ujian_detail where kode_ujian = '$r->kode_ujian'")->getRow();

                                                            $totalMenit = $total * 3;
                                                            $start =  (date('Y-m-d H:i'));
                                                            $end_ = (date('Y-m-d H:i', strtotime("+ $totalMenit minutes")));


                                                            $start_ujian = date_create($start);
                                                            $end_ujian = date_create($end_);
                                                            $durasi = date_diff($start_ujian, $end_ujian);
                                                        endforeach;

                                                        ?>
                                                        <span class="fw-bold"> <i class="icofont-read-book"></i> <?= (!empty($jml) ? $jml->total_soal : '0') ?> Soal/<span style="font-size:10px">Materi</span> </span>
                                                        <div class="d-flex flex-column mb-3">
                                                            <span class="fw-bold"> Rp <?= number_format($rows->nominal_paket - (($rows->nominal_paket * $rows->diskon) / 100)) ?> </span>
                                                            <span style="font-size:12px" class="mt-1"> <del>Rp <?= number_format($rows->nominal_paket) ?></del> </span>
                                                        </div>
                                                        <!--<span> <i class="icofont-clock-time"></i> <?= ($durasi != '0' ? ($durasi->h * 60) + $durasi->i : '0');  ?> Menit</span>-->
                                                    </div>
                                                    <div>
                                                        <div class="mb-2" style="font-size:12px">
                                                            <?php if ($rataRating > 0): ?>
                                                                <span class="text-dark"><?= $rataRating ?><span> <?= showStars($rataRating) ?> <span class="text-dark">(<?= $jumlahReview + 325 ?>)</span>
                                                                    <?php else: ?>
                                                                        <span class="text-dark"><?= "4.9" ?><span> <?= showStars('4.9') ?> <span class="text-dark">(<?= '484' ?>)</span>
                                                                            <?php endif; ?>
                                                        </div>
                                                        <!-- Affiliate -->
                                                        <?php if (session()->get('id') && !empty($affiliate)): ?>
                                                            <div class="affiliate-box p-2 mb-3">
                                                                💰 <strong class="text-warning">Komisi <?= $rows->komisi ?>%</strong>
                                                                <div class="text-muted small">
                                                                    Dari setiap pembelian via link kamu
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>

                                                    </div>
                                                    <div class="d-flex gap-2 mt-3">
                                                        <a href="<?= base_url('sw-siswa/transaksi/pesan/' . encrypt_url($rows->idpaket)) ?>" class="btn-buy btn-sm text-center flex-fill p-2">Pesan Sekarang</a>
                                                        <?php if (session()->get('id')): ?>
                                                            <?php if (!empty($affiliate)): ?>
                                                                <button class="btn-buy-copy btn-sm  btn-copy-link" data-paket_id="<?= $rows->idpaket ?>">
                                                                    <i class="fa fa-copy"></i>
                                                                </button>
                                                                <button class="btn-buy-wa btn-sm  share-link" data-paket_id="<?= $rows->idpaket ?>">
                                                                    <i class="fab fa-whatsapp"></i>
                                                                </button>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <?php if (!empty($rows->deskripsi)): ?>
                                                    <a href="javascript:void(0)" class="badge text-bg-light p-2 mt-4 rounded-pill text-primary btn-hover-dark deskripsi_paket" data-bs-toggle="modal" data-bs-target="#lihatPaket" data-idpaket="<?= $rows->idpaket ?>"> <i class="bi bi-eye-fill me-2"></i>Lihat daftar materi</a>
                                                <?php endif; ?>
                                                <?php if ($rows->iddiskon != null): ?>
                                                    <div class="position-absolute top-0 end-0 diskon p-1 text-white"><?= $rows->diskon ?> %</div>
                                                <?php endif; ?>
                                            </div>
                                            <!-- Single Courses End -->
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <?php if ($lihat == '0'): ?>
                                <div class="d-flex justify-content-center">
                                    <div class="row">
                                        <div class="col-12 text-center" style="padding-top: 20px;">
                                            <a href="<?= base_url('list-bimbel') ?>" class="text-primary mt-2 ">Lihat lebih banyak</a><i class="bi bi-arrow-down-square-fill ms-1" style="color: blue;"></i>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!-- All Courses Wrapper End -->
                    </div>
                </div>
                <!-- All Courses tab content End -->
            </div>
        </div>

        <div class="section section-padding-02 ">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 pt-2">
                        <div class="card call-to-action-wrapper p-4">
                            <h6>Sertifikat Brevet Pajak AB</h6>
                            <span>Sertifikat akan di dapatkan setelah lulus seluruh materi ujian</span>
                            <?= img_lazy('assets-landing/images/sertifikat/brevet-ab.jpg', "loading", ['class' => 'card-img-top mt-4']) ?>
                        </div>
                    </div>
                    <div class="col-md-6 pt-2">
                        <div class="card call-to-action-wrapper p-4">
                            <h6>Transkrip Nilai</h6>
                            <span>Transkrip nilai akan di dapatkan setelah lulus seluruh materi ujian</span>
                            <?= img_lazy('assets-landing/images/sertifikat/transkip-nilai.jpg', "loading", ['class' => 'card-img-top mt-4']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section section-padding-02 ">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <!-- Section Title Start -->
                        <div class="row">
                            <div class="section-title shape-02">
                                <h6>Keuntungan Memiliki </h6>
                                <h2 class="main-title"><span>Sertifikat Brevet Pajak</span></h2>
                            </div>
                            <div>
                                <table>
                                    <tr>
                                        <td width="5%"><i class="bi bi-patch-check text-primary"></i></td>
                                        <td width="95%">Sebagai salah satu bukti memiliki pengetahuan/kompetensi dibidang perpajakan</td>
                                    </tr>
                                    <tr>
                                        <td width="5%"><i class="bi bi-patch-check text-primary"></i></td>
                                        <td width="95%">Dapat digunakan sebagai syarat administrasi seorang kuasa saat mendampingi
                                            WP</td>
                                    </tr>
                                    <tr>
                                        <td width="5%"><i class="bi bi-patch-check text-primary"></i></td>
                                        <td width="95%">Dapat digunakan sebagai syarat permohonan izin kuasa hukum di pengadilan
                                            pajak</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- Section Title End -->

                    </div>
                    <div class="col-md-6">
                        <div class="card p-4 border-0 animation-down">
                            <?= img_lazy('assets-landing/images/slider/slider-3.png', "loading", ['class' => 'card-img-top']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section call-to-action-wrapper pb-4 d-flex align-items-center">
            <div class="container">
                <div class="tab-content courses-tab-content">
                    <div class="tab-pane fade show active" id="tabs1">
                        <div class="courses-wrapper">
                            <span>Berdasarkan Pasal 32 ayat (3) UU KUP Orang pribadi atau badan dapat menunjuk seorang kuasa dengan surat kuasa khusus untuk menjalankan hak dan memenuhi kewajiban sesuai dengan ketentuan peraturan perundang-undangan perpajakan. Seorang kuasa yang ditunjuk harus mempunyai <i><b>kompetensi tertentu dalam aspek perpajakan.</b></i></span>
                            <br>
                            <br>Berdasarkan Pasal 5 ayat (2) Peraturan Menteri Keuangan Nomor 229/PMK.03/2014 bahwa karyawan yang bertindak sebagai kuasa harus memiliki <i><b>sertifikat brevet di bidang perpajakan yang diterbitkan oleh lembaga pendidikan kursus brevet pajak</b></i>.
                            <br>
                            <br>Berdasarkan Pasal 4 huruf b angka 2 PMK 184/PMK.01/2017 “Pengetahuan yang luas dan keahlian tentang peraturan perundang-undangan perpajakan sebagaimana dimaksud dalam Pasal 3 huruf b dibuktikan dengan: <i><b>brevet perpajakan dari instansi atau lembaga penyelenggara brevet perpajakan”</b></i>.
                            <br>
                            <br>Berdasarkan pasal 3 ayat (2) huruf d PER-1/PP/2024 Tentang Tata Cara Permohonan Izin Kuasa Hukum Pada Pengadilan Pajak "bahwa yang bersangkutan mempunyai pengetahuan yang luas dan keahlian tentang peraturan perundang-undangan perpajakan, yaitu (ditunjukan dengan bukti) sebagai berikut: 1) ijazah Sarjana atau Diploma IV di bidang administrasi fiskal, akuntansi, dan/atau perpajakan dari perguruan tinggi yang terakreditasi; 2) ijazah Diploma III perpajakan dari perguruan tinggi yang terakreditasi; 3)<i><b> brevet perpajakan dari instansi atau lembaga penyelenggara brevet perpajakan;</b></i> atau 4) surat atau dokumen yang menunjukkan pengalaman pernah bekerja pada instansi pemerintah di bidang teknis perpajakan."
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="section section-padding-02 ">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="card p-4 border-0 animation-down">
                            <?= img_lazy('assets-landing/images/slider/slider-4.png', "loading", ['class' => 'card-img-top']) ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Section Title Start -->
                        <div class="row">
                            <div class="section-title shape-02">
                                <h6>Penggunaan</h6>
                                <h2 class="main-title"><span>Sertifikat Brevet Pajak</span></h2>
                            </div>
                            <div>
                                <table>
                                    <tr>
                                        <td width="5%"><i class="bi bi-patch-check text-primary"></i></td>
                                        <td width="95%">Kepemilikan sertifikat Brevet Pajak AB dapat digunakan sebagai syarat
                                            administrasi untuk mendampingi WP orang pribadi &
                                            badan dalam negeri.</td>
                                    </tr>
                                    <tr>
                                        <td width="5%"><i class="bi bi-patch-check text-primary"></i></td>
                                        <td width="95%">Kepemilikan sertifikat Brevet Pajak C dapat digunakan sebagai syarat
                                            administrasi untuk mendampingi WP orang pribadi &
                                            badan dalam negeri maupun asing.</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- Section Title End -->
                    </div>
                </div>
            </div>
        </div>

        <div class="section section-padding-02 mb-4" id="penilaian">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <h6>Sistem Penilaian</h6>
                        <span>Berikut ini adalah tabel sistem penilaian di KelasBrevet</span>
                    </div>
                    <div class="col-12 mt-4 card call-to-action-wrapper rounded table-responsive-sm">
                        <table class="table  text-center no-border font-size-table">
                            <tr style="background-color: blue;">
                                <td class="text-white">NILAI</td>
                                <td class="text-white">HURUF</td>
                                <td class="text-white">PREDIKAT</td>
                                <td class="text-white">KETERANGAN</td>
                            </tr>
                            <tr>
                                <td>0-59</td>
                                <td>D</td>
                                <td>KURANG</td>
                                <td>TIDAK LULUS</td>
                            </tr>
                            <tr>
                                <td>60-69</td>
                                <td>C</td>
                                <td>CUKUP</td>
                                <td>LULUS</td>
                            </tr>
                            <tr>
                                <td>70-79</td>
                                <td>B</td>
                                <td>CUKUP BAIK</td>
                                <td>LULUS</td>
                            </tr>
                            <tr>
                                <td>80-89</td>
                                <td>A</td>
                                <td>BAIK</td>
                                <td>LULUS</td>
                            </tr>
                            <tr>
                                <td>90-100</td>
                                <td>A+</td>
                                <td>SANGAT BAIK</td>
                                <td>LULUS</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <!-- Download App Start -->
        <div class="section section-padding download-section mt-4">
            <div class="container">
                <!-- Download App Wrapper Start -->
                <div class="download-app-wrapper mt-n6">
                    <!-- Section Title Start -->
                    <div class="section-title section-title-white">
                        <h2 class="main-title mb-4">Ikuti Ujian Brevet Tanpa Mengikuti Kelas</h2>
                        <h6 class="text-white">Kamu bisa mengikuti Ujian Brevet secara langsung tanpa perlu Mengikuti Kelas terlebih dahulu. <br><i>*Syarat dan ketentuan berlaku</i></h6>
                        <a href="<?= base_url('sw-siswa') ?>" class="btn btn-light  rounded-pill text-primary btn-hover-dark">Ikuti Ujian Brevet Pajak AB</a>
                    </div>
                    <!-- Section Title End -->
                    <?= img_lazy('assets-landing/images/shape/shape-14.png', "brevet pajak", ['class' => 'shape-1 animation-righ']) ?>
                    <!-- Download App Button End -->
                    <div class="">
                        <?= img_lazy('assets-landing/images/slider/slider-5.png', "brevet ab", ['class' => '']) ?>
                    </div>
                    <!-- Download App Button End -->

                </div>
                <!-- Download App Wrapper End -->

            </div>
        </div>
        <!-- Download App End -->

        <!-- Testimonial End -->
        <div class="section section-padding-02 mt-n1" id="testimoni">
            <div class="container">

                <!-- Section Title Start -->
                <div class="section-title shape-03 text-center">
                    <h5 class="sub-title">Testimoni Alumni</h5>
                </div>
                <!-- Section Title End -->

                <!-- Testimonial Wrapper End -->
                <div class="testimonial-wrapper testimonial-active">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <!-- Single Testimonial Start -->
                            <?php
                            $data_testi = $db->query("SELECT testimoni.*, siswa.nama_siswa, siswa.kota_intansi, siswa.avatar FROM testimoni join siswa on testimoni.idsiswa=siswa.id_siswa order by idtestimoni desc")->getResult();
                            foreach ($data_testi as $rows) {
                            ?>
                                <div class="single-testimonial swiper-slide">
                                    <div class="testimonial-author">
                                        <div class="position-relative user-info mb-4">
                                            <?= img_lazy('assets/app-assets/user/' . $rows->avatar, "loading", ['class' => 'circle-img']) ?>

                                            <i class="icofont-quote-left position-absolute top-100 start-50 translate-middle text-primary fs-1"></i>
                                        </div>
                                        <h4 class="name"><?= $rows->nama_siswa; ?></h4>
                                        <span class="designation"><?= $rows->kota_intansi; ?></span>
                                    </div>
                                    <div class="testimonial-content text">

                                        <p><?= $rows->keterangan; ?></p>
                                    </div>
                                    <span class="toggle-btn">Add more</span>
                                </div>
                            <?php } ?>
                            <!-- Single Testimonial End -->
                        </div>
                        <!-- Add Pagination -->
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
                <!-- Testimonial Wrapper End -->

            </div>
        </div>
        <!-- Testimonial End -->




        <!-- Footer Start  -->
        <div class="section footer-section mt-4">

            <!-- Footer Widget Section Start -->
            <div class="footer-widget-section">

                <?= img_lazy('assets-landing/images/shape/shape-21.png', "loading", ['class' => 'shape-1 animation-down']) ?>

                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 order-md-6 order-lg-6">

                            <!-- Footer Widget Start -->
                            <div class="footer-widget">
                                <div class="widget-logo">
                                    <a href="#"><?= img_lazy('assets-landing/images/logo.png', "loading", ['class' => '']) ?></a>
                                </div>

                                <div>
                                    <p>Kelas Brevet merupakan platform pelatihan Brevet Pajak AB yang Terdaftar Resmi. Diselenggarakan oleh Akuntanmu Learning Center By Legalyn Konsultan Indonesia (Lembaga Pelatihan, Kursus/Bimbel, yang didirikan sejak tahun 2021)</p>
                                </div>

                                <ul class="widget-info">
                                    <li>
                                        <p> <i class="flaticon-email"></i> <a href="mailto:support@kelasbrevet.com">support@kelasbrevet.com</a> </p>
                                    </li>
                                    <li>
                                        <p> <i class="flaticon-phone-call"></i> <a href="tel:082180744966">082180744966</a> </p>
                                    </li>
                                    <li>
                                        <p> <i class="bi bi-building-add"></i><a href="#">KBIG Office - Jl. Sawo Raya - Lampung</a> </p>
                                    </li>
                                    <li>
                                        <p><i class="bi bi-building-add"> </i><a href="#">Menara 165 Lantai 4 - Jakarta Selatan</a></p>
                                    </li>
                                </ul>

                                <ul class="widget-social">
                                    <li><a href="#"><i class="flaticon-facebook"></i></a></li>
                                    <li><a href="#"><i class="flaticon-twitter"></i></a></li>
                                    <li><a href="#"><i class="flaticon-skype"></i></a></li>
                                    <li><a href="#"><i class="flaticon-instagram"></i></a></li>
                                </ul>
                            </div>
                            <!-- Footer Widget End -->
                        </div>
                        <div class="col-lg-3 col-md-3 order-md-3 order-lg-3">
                            <div class="row">
                                <div class="col-12">
                                    <!-- Footer Widget Start -->
                                    <div class="footer-widget">
                                        <h4 class="footer-widget-title">Lembaga Terdaftar</h4>
                                        <div class="widget-subscribe">
                                            <div class="row">
                                                <div class="col-4 col-md-4 col-lg-4"><?= img_lazy('assets-landing/images/lembaga/kemendikbud.png', "loading", ['class' => '', 'width' => '50', 'height' => '50']) ?></div>
                                                <div class="col-4 col-md-4 col-lg-4"><?= img_lazy('assets-landing/images/lembaga/kemenaker.png', "loading", ['class' => '', 'width' => '50', 'height' => '50']) ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Footer Widget End -->
                                </div>
                                <div class="col-12 mt-4">
                                    <h6 class="footer-widget-title">Menu KelasBrevet</h6>
                                    <ul class="widget-info">
                                        <li>
                                            <p><i class="bi bi-dot me-1"></i><a href="<?= base_url("tentangkami") ?>" class="">Tentang Kami</a></p>
                                        </li>
                                        <li>
                                            <p><i class="bi bi-dot me-1"></i><a href="<?= base_url("siap-kerja") ?>" class="">Siap Kerja</a></p>
                                        </li>
                                        <li>
                                            <p><i class="bi bi-dot me-1"></i><a href="<?= base_url("pelatihan") ?>">Pelatihan</a></p>
                                        </li>
                                        <li>
                                            <p><i class="bi bi-dot me-1"></i><a href="<?= base_url("penilaian") ?>">Penilaian</a></p>
                                        </li>
                                        <li>
                                            <p><i class="bi bi-dot me-1"></i><a href="<?= base_url("testimoni") ?>">Testimoni</a></p>
                                        </li>
                                        <!--<li>-->
                                        <!--    <p><i class="bi bi-dot me-1"></i><a href="<?= base_url("jadwal") ?>">Jadwal</a></p>-->
                                        <!--</li>-->
                                        <!--<li>-->
                                        <!--    <p><i class="bi bi-dot me-1"></i><a href="<?= base_url("galeri") ?>">Galeri</a></p>-->
                                        <!--</li>-->
                                        <li>
                                            <p><i class="bi bi-dot me-1"></i><a href="<?= base_url("media-kelasbrevet") ?>">Media</a></p>
                                        </li>
                                        <li>
                                            <p><i class="bi bi-dot me-1"></i><a href="<?= base_url("twibbon") ?>">Twibbon</a></p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 order-md-3 order-lg-3">
                            <div class="row">
                                <div class="col-12">
                                    <!-- Footer Widget Start -->
                                    <div class="footer-widget">
                                        <h4 class="footer-widget-title">Statistik</h4>
                                        <ul class="widget-info">
                                            <li>
                                                <p><a href="#">Today's Views: <?= nominal($statistik_hariini->total) ?></a></p>
                                            </li>
                                            <li>
                                                <p><a href="#">Yesterday's Views: <?= nominal($statistik_lampau->total) ?></a></p>
                                            </li>
                                            <li>
                                                <p><a href="#">7 Days Views: <?= nominal($statistik_minggu->total) ?></a></p>
                                            </li>
                                            <li>
                                                <p><a href="#">30 Days Views: <?= nominal($statistik_bulan->total) ?></a></p>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- Footer Widget End -->
                                </div>
                            </div>
                        </div>
                    </div>


                    <hr>
                    <!-- Footer Copyright Start -->
                    <div class="accordion" id="accordionExample">
                        <div class="d-flex justify-content-between text-dark">
                            <div class="copyright-link">
                                <a href="<?= base_url("terms") ?>" class="text-dark">Terms of Conditions</a>
                                <a href="<?= base_url("privasi") ?>" class="text-dark">Privacy Policy</a>
                            </div>
                            <div class="copyright-text">
                                <p class="text-dark">
                                    <?= copyright() ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Footer Copyright End -->
                </div>

            </div>
            <!-- Footer Widget Section End -->
            <!--//iklan-->
            <?php if (!empty($dataIklan)) { ?>
                <div class="modal fade" id="iklanDepan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm modal-dialog-centered" id="modalIklan">
                        <div class="modal-content ">
                            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                                <div class="carousel-indicators">
                                    <?php $no = 0;
                                    foreach ($dataIklan as $rows) : ?>
                                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?= $no + 1; ?>" class="<?= $no == 0 ? "active" : ""; ?> " aria-current="true" aria-label="Slide <?= $no + 1; ?>"></button>
                                    <?php endforeach; ?>
                                </div>
                                <div class="carousel-inner">
                                    <?php $no = 0;
                                    foreach ($dataIklan as $rows) : $no++ ?>
                                        <?php if ($rows->url != null): ?>
                                            <a href="<?= $rows->url ?>" target="_blank">
                                            <?php else: ?>
                                                <a>
                                                <?php endif; ?>
                                                <div class="carousel-item <?= $no == 1 ? 'active' : '' ?>">
                                                    <?= img_lazy('uploads/iklan/thumbnails/' . $rows->file, $rows->nama, ['class' => 'img-fluid img-thumbnail']) ?>
                                                </div>
                                                </a>
                                            <?php endforeach; ?>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                            <button type="button" class="z-3 position-absolute top-0 end-0 text-dark zoom close-iklan" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x"></i></button>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <!-- Footer End -->

            <!--Back To Start-->
            <a href="#" class="back-to-top">
                <i class="icofont-simple-up"></i>
            </a>

            <!-- Chat Floating Button -->
            <div style="display:none" id="chatButton">
                <a href="javascript:void(0)" data-url="Halo,%20saya%20ingin%20bertanya..." class="whatsapp-button d-flex align-items-center justify-content-center">
                    <img src="<?= base_url('uploads/icon/call.png') ?>" alt="loading" class="img-fluid" width="50">
                </a>
            </div>

            <!-- Chat Box -->
            <div class="chat-box" id="chatBox">
                <div class="chat-header">
                    <span><?= img_lazy('assets-landing/images/logo-putih.png', "loading", ['class' => 'img-fluid', "width" => '130px']) ?></span>
                    <button class="badge bg-light p-2 border-0 radius-10 text-dark" id="closeChat"><i class="bi bi-x"></i></button>
                </div>
                <div class="chat-body">
                    <p><strong>Halo!</strong> Saya Kelasbrevet, Ada yang bisa saya bantu?</p>
                    <div class="d-grid gap-2">
                        <a href="javascript:void(0)" data-url="Halo Kelas Brevet,%20saya%20ingin%20bertanya..." target="_blank" class="badge bg-secondary p-2 border-0 text-dark myFunctionWACs">
                            Customer Service
                        </a>
                        <a href="javascript:void(0)" data-url="Halo Kelas Brevet,%20saya%20ingin%20bertanya..." target="_blank" class="badge bg-secondary p-2 border-0 text-dark myFunctionWATs">
                            Technical Support - Untuk kendala website</button>
                        </a>
                    </div>
                </div>
            </div>


            <!-- untuk paket-->
            <div class="modal fade" id="lihatPaket" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-scrollable" style="scrollbar-color: #0000FF #ffffff" id="modalPaket">
                    <div class="modal-content position-relative">

                        <div class="modal-body card-body ">
                            <div class="isideskripsi fs-6" style="margin-top:-25px"></div>
                        </div>
                        <button type="button" class="position-absolute top-0 start-0 text-light zoom close-deskripsi-paket" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x"></i></button>
                    </div>
                </div>
            </div>

            <!-- untuk tampilan surat izin LKP-->
            <div class="modal fade" id="lihatIzinLkp" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Surat Izin LKP</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            <iframe src="<?= base_url('assets-landing/images/surat-izin/izin-LKP-akuntanmu-01.pdf') ?>" width="100%" height="600px" style="border: none;"></iframe>
                        </div>
                    </div>
                </div>
            </div>

            <!--surat izin LPK-->
            <div class="modal fade" id="lihatIzinLpk" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Sertifikat Standar LPK</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            <iframe src="<?= base_url('assets-landing/images/surat-izin/izin-LPK-akuntanmu-01.pdf') ?>" width="100%" height="600px" style="border: none;"></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <!--kemnaker-->
            <div class="modal fade" id="lihatKemnaker" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <iframe src="https://skillhub.kemnaker.go.id/mitra/temukan-mitra/lpk-akuntanmu-by-legalyn-konsultan-indonesia-d8c65b1d-90e6-43a3-b1de-96cb4af8c662" width="100%" height="500px"></iframe>
                    </div>
                </div>
            </div>

            <!--audio notifikasi-->
            <audio id="notifSound" src="<?= base_url('uploads/audio/chat.mp3') ?>" preload="auto"></audio>



            <!-- JS 
    ============================================ -->
            <!--//untuk pesan-->
            <script>
                <?= session()->getFlashdata('pesan'); ?>
            </script>

            <!-- Modernizer & jQuery JS -->
            <script src="<?= base_url('assets-landing/js/vendor/modernizr-3.11.2.min.js'); ?>"></script>
            <script src="<?= base_url('assets-landing/js/vendor/jquery-3.5.1.min.js'); ?>"></script>

            <!-- Bootstrap JS -->
            <script src="https://akuntanmu.com/assets/assetLanding/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
            <!--<script src="<?= base_url('assets-landing/js/plugins/bootstrap.min.js'); ?>"></script>-->

            <!-- Plugins JS -->
            <script src="<?= base_url('assets-landing/js/plugins/swiper-bundle.min.js'); ?>"></script>
            <script src="<?= base_url('assets-landing/js/plugins/jquery.magnific-popup.min.js'); ?>"></script>
            <script src="<?= base_url('assets-landing/js/plugins/video-playlist.js'); ?>"></script>
            <script src="<?= base_url('assets-landing/js/plugins/jquery.nice-select.min.js'); ?>"></script>
            <script src="<?= base_url('assets-landing/js/plugins/ajax-contact.js'); ?>"></script>
            <script src="<?= base_url('assets-landing/js/TweenMax.min.js'); ?>"></script>

            <!--====== Use the minified version files listed below for better performance and remove the files listed above ======-->
            <!-- <script src="<?= base_url('assets-landing/js/plugins.min.js'); ?>"></script> -->


            <!-- Main JS -->
            <script src="<?= base_url('assets-landing/js/main.js'); ?>"></script>
            <script>
                $(document).on('click', '.btn-copy-link', function() {
                    let btn = $(this);
                    let paket_id = btn.data('paket_id');

                    $.ajax({
                        url: "<?= base_url('sw-siswa/affiliate/copy') ?>",
                        type: "POST",
                        dataType: "json",
                        data: {
                            paket_id: paket_id,
                            <?= csrf_token() ?>: "<?= csrf_hash() ?>"
                        },
                        success: function(res) {
                            if (res.status === 'success') {

                                copyToClipboard(res.link).then(function() {
                                    swal({
                                        title: 'Berhasil!',
                                        text: 'Link berhasil disalin, silakan dibagikan.',
                                        type: 'success',
                                        padding: '2em'
                                    }).then(() => location.reload());
                                }).catch(function() {
                                    swal({
                                        title: 'Perhatian',
                                        text: 'Link gagal di copy, Silahkan coba lagi',
                                        type: 'info',
                                        padding: '2em'
                                    });
                                });

                            } else {
                                swal({
                                    title: 'Gagal!',
                                    text: 'Link gagal dibuat.',
                                    type: 'error',
                                    padding: '2em'
                                });
                            }
                        }
                    });
                });

                /* ===============================
                   CLIPBOARD HELPER (iOS SAFE)
                ================================ */
                function copyToClipboard(text) {
                    // Modern API (Chrome, Edge, Android, iOS 16+)
                    if (navigator.clipboard && window.isSecureContext) {
                        return navigator.clipboard.writeText(text);
                    }

                    // Fallback iOS lama
                    return new Promise(function(resolve, reject) {
                        let textarea = document.createElement("textarea");
                        textarea.value = text;
                        textarea.style.position = "fixed";
                        textarea.style.opacity = "0";
                        document.body.appendChild(textarea);

                        textarea.focus();
                        textarea.select();

                        try {
                            let successful = document.execCommand("copy");
                            document.body.removeChild(textarea);
                            successful ? resolve() : reject();
                        } catch (err) {
                            document.body.removeChild(textarea);
                            reject();
                        }
                    });
                }
            </script>
            <script>
                $(document).on('click', '.share-link', function(e) {
                    e.preventDefault();

                    let btn = $(this);
                    let paket_id = btn.data('paket_id');

                    btn.prop('disabled', true);

                    // 🔥 buka window dulu (masih user gesture)
                    let waWindow = window.open('about:blank', '_blank');

                    $.ajax({
                        url: "<?= base_url('sw-siswa/affiliate/copy') ?>",
                        type: "POST",
                        dataType: "json",
                        data: {
                            paket_id: paket_id,
                            <?= csrf_token() ?>: "<?= csrf_hash() ?>"
                        },
                        success: function(res) {
                            if (res.status !== 'success') {
                                showAlert('error', 'Gagal!', 'Link gagal dibuat.');
                                waWindow.close();
                                btn.prop('disabled', false);
                                return;
                            }

                            let link = encodeURIComponent(res.link);
                            waWindow.location.href = "https://wa.me/?text=" + link;

                            btn.prop('disabled', false);
                        },
                        error: function() {
                            showAlert('error', 'Error!', 'Terjadi kesalahan server.');
                            waWindow.close();
                            btn.prop('disabled', false);
                        }
                    });
                });


                // ===============================
                // SWEETALERT HELPER
                // ===============================
                function showAlert(type, title, message) {
                    swal({
                        title: title,
                        text: message,
                        icon: type,
                        timer: 2200,
                        buttons: false
                    });
                }
            </script>

            <script>
                var splide = new Splide('.splide', {

                    type: 'loop',
                    perPage: 3,
                    rewind: true,
                    breakpoints: {
                        640: {
                            perPage: 2,
                            gap: '.7rem',
                            height: '12rem',
                        },

                        480: {
                            perPage: 1,
                            gap: '.7rem',
                            height: '12rem',
                        },

                    },

                });

                splide.mount();
            </script>
            <script>
                var $tickerWrapper = $(".tickerwrapper");
                var $list = $tickerWrapper.find("ul.list");
                var $clonedList = $list.clone();
                var listWidth = 10;

                $list.find("li").each(function(i) {
                    listWidth += $(this, i).outerWidth(true);
                });

                var endPos = $tickerWrapper.width() - listWidth;

                $list.add($clonedList).css({
                    "width": listWidth + "px"
                });

                $clonedList.addClass("cloned").appendTo($tickerWrapper);

                //TimelineMax
                var infinite = new TimelineMax({
                    repeat: -1,
                    paused: true
                });
                var time = 40;

                infinite
                    .fromTo($list, time, {
                        rotation: 0.01,
                        x: 0
                    }, {
                        force3D: true,
                        x: listWidth,
                        ease: Linear.easeNone
                    }, 0)
                    .fromTo($clonedList, time, {
                        rotation: 0.01,
                        x: -listWidth
                    }, {
                        force3D: true,
                        x: 0,
                        ease: Linear.easeNone
                    }, 0)
                    .set($list, {
                        force3D: true,
                        rotation: 0.01,
                        x: -listWidth
                    })
                    .to($clonedList, time, {
                        force3D: true,
                        rotation: 0.01,
                        x: listWidth,
                        ease: Linear.easeNone
                    }, time)
                    .to($list, time, {
                        force3D: true,
                        rotation: 0.01,
                        x: 0,
                        ease: Linear.easeNone
                    }, time)
                    .progress(1).progress(0)
                    .play();

                //Pause/Play		
                $tickerWrapper.on("mouseenter", function() {
                    infinite.pause();
                }).on("mouseleave", function() {
                    infinite.play();
                });

                setTimeout(function() {

                    $('#iklanDepan').modal('show');

                }, 2000);




                document.querySelector('.whatsapp-button').addEventListener('click', function() {

                    this.classList.add('bounce');

                    setTimeout(function() {

                        this.classList.remove('bounce');

                    }.bind(this), 1000);

                });


                var TxtType = function(el, toRotate, period) {
                    this.toRotate = toRotate;
                    this.el = el;
                    this.loopNum = 0;
                    this.period = parseInt(period, 10) || 2000;
                    this.txt = '';
                    this.tick();
                    this.isDeleting = false;
                };

                TxtType.prototype.tick = function() {
                    var i = this.loopNum % this.toRotate.length;
                    var fullTxt = this.toRotate[i];

                    if (this.isDeleting) {
                        this.txt = fullTxt.substring(0, this.txt.length - 1);
                    } else {
                        this.txt = fullTxt.substring(0, this.txt.length + 1);
                    }

                    this.el.innerHTML = '<span class="wrap">' + this.txt + '</span>';

                    var that = this;
                    var delta = 200 - Math.random() * 100;

                    if (this.isDeleting) {
                        delta /= 2;
                    }

                    if (!this.isDeleting && this.txt === fullTxt) {
                        delta = this.period;
                        this.isDeleting = true;
                    } else if (this.isDeleting && this.txt === '') {
                        this.isDeleting = false;
                        this.loopNum++;
                        delta = 500;
                    }

                    setTimeout(function() {
                        that.tick();
                    }, delta);
                };

                window.onload = function() {
                    var elements = document.getElementsByClassName('typewrite');
                    for (var i = 0; i < elements.length; i++) {
                        var toRotate = elements[i].getAttribute('data-type');
                        var period = elements[i].getAttribute('data-period');
                        if (toRotate) {
                            new TxtType(elements[i], JSON.parse(toRotate), period);
                        }
                    }
                    // INJECT CSS
                    // var css = document.createElement("style");
                    // css.type = "text/css";
                    // css.innerHTML = ".typewrite > .wrap { border-right: 0.08em solid #fff}";
                    // document.body.appendChild(css);
                };

                $('.view_galeri').click(function() {
                    const url = $(this).data('url_galleri');
                    $(".isiKonten").html(`
            <div class="modal-header">
                <h5 class="modal-title">Sertifikat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    x
                </button>
            </div>
                <iframe src="${url}" width="100%" height="500vh"></iframe>
            `);
                });

                $('.deskripsi_paket').click(function() {
                    const idpaket = $(this).data('idpaket');
                    $.ajax({
                        type: 'GET',
                        data: {
                            idpaket: idpaket
                        },
                        dataType: 'JSON',
                        async: true,
                        url: "<?= base_url('get_paket') ?>",
                        success: function(data) {
                            console.log(data);
                            $(".isideskripsi").html(`
                    
                    <div class="single-courses card">
                        <div class="courses-images">
                            <a href="#" >
                                <img class="card-img-top zoom" src="<?= base_url('assets-landing/images/paket/thumbnails/'); ?>/${data.file}" alt="Courses">
                            </a>

                        </div>
                        <div class="courses-content">
                            <div class="fs-6">
                                ${data.deskripsi}
                            </div>
                        </div>
                    </div>
                    
                    `);
                        }
                    });
                });

                $('.myFunctionWACs').click(function() {
                    var slugWa = $(this).data('url');
                    window.location = "https://api.whatsapp.com/send?phone=6282180744966&text=" + slugWa;
                });

                $('.myFunctionWATs').click(function() {
                    var slugWa = $(this).data('url');
                    window.location = "https://api.whatsapp.com/send?phone=6281532423436&text=" + slugWa;
                });

                // $('.close-iklan').click(function(){
                const sound = document.getElementById("notifSound");
                setTimeout(function() {
                    //untuk membunyikan notifikasi
                    sound.play();
                    //untuk menampilkan icon call
                    document.getElementById("chatButton").style.display = 'block';
                }, 2000);
                // })
            </script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    let lazyImages = document.querySelectorAll("img.lazy");

                    if ("IntersectionObserver" in window) {
                        // ✅ Browser support IntersectionObserver
                        let observer = new IntersectionObserver((entries, obs) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    let img = entry.target;
                                    img.src = img.dataset.src;
                                    img.removeAttribute("data-src");
                                    img.classList.remove("lazy");
                                    obs.unobserve(img);
                                }
                            });
                        });

                        lazyImages.forEach(img => observer.observe(img));

                    } else {
                        // ⚠️ Fallback kalau browser tidak support
                        lazyImages.forEach(img => {
                            img.src = img.dataset.src;
                            img.removeAttribute("data-src");
                            img.setAttribute("loading", "lazy");
                            img.classList.remove("lazy");
                        });
                    }
                });
            </script>

            <script>
                document.querySelectorAll(".single-testimonial").forEach(card => {
                    const text = card.querySelector(".text");
                    const btn = card.querySelector(".toggle-btn");

                    const lineHeight = parseInt(window.getComputedStyle(text).lineHeight, 10);
                    const maxHeight = lineHeight * 2; // clamp 4 baris

                    // Cek apakah teks lebih tinggi dari batas
                    if (text.scrollHeight <= maxHeight) {
                        btn.style.display = "none"; // sembunyikan tombol kalau teks pendek
                    }

                    btn.addEventListener("click", () => {
                        text.classList.toggle("expanded");
                        btn.textContent = text.classList.contains("expanded") ? "Show less" : "Add more";
                    });
                });
            </script>
            <script>
                const chatButton = document.getElementById("chatButton");
                const chatBox = document.getElementById("chatBox");
                const closeChat = document.getElementById("closeChat");

                chatButton.addEventListener("click", () => {
                    chatBox.style.display = "flex";
                    chatButton.style.display = "none";
                });

                closeChat.addEventListener("click", () => {
                    chatBox.style.display = "none";
                    chatButton.style.display = "flex";
                });
            </script>
</body>

</html>
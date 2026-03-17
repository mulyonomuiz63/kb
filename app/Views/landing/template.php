<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="google-site-verification" content="sFNRbIOQMaNWDjh3kNFm_juMtXCUN_O2DLmZu_zNkkU" />
    

    <meta name="keywords" content="Kelas Brevet AB, Kelas Pajak, Brevet AB, Kursus Brevet Pajak, brevet, pajak, brevet pajak AB, kursus pajak, pelatihan pajak, brevet A&B, pelatihan pajak terapan brevet a&b terpadu, pelatihan perpajakan, perpajakan, kursus pajak offline, kursus pajak online, brevet pajak adalah, Keunggulan pelatihan pajak, manfaat kursus brevet pajak, lokasi pelatihan brevet, lokasi pelatihan pajak, fasilitas pelatihan pajak, fasilias pelatihan brevet, berapa lama pelatihan brevet, berapa lama kursus pelatihan brevet, sertifikat brevet">
    <meta name="robots" content="index, follow" />
    <?php $uris = service('uri'); ?>
    <?php if($uris->getSegment(1) == 'artikel'): ?>
    
        <meta content="<?= !empty($detail) ? $detail->judul : "Kelas Brevet Pajak Online Terpercaya Di Indonesia" ; ?>" name="description">
        <meta property="og:title" content="<?= !empty($detail)? $detail->judul :'Kelas Brevet AB' ?>" />
        <meta property="og:description" content="<?= !empty($detail) ? $detail->judul : "Kelas Brevet Pajak Online Terpercaya Di Indonesia" ; ?>" />
        <meta property="og:url" content="<?= !empty($detail) ? base_url('artikel/'.$detail->slug_judul) : base_url('/'); ?>" />
    
        <meta property="article:section" content="<?= !empty($detail)? $detail->judul :'Kelas Brevet AB' ?>"/>
        <meta property="og:image" content="<?= !empty($detail) ? base_url('uploads/artikel/thumbnails/'.$detail->image_default) : base_url(favicon()); ?>"/>
        <meta property="og:image:secure_url" content="<?= !empty($detail) ? base_url('uploads/artikel/thumbnails/'.$detail->image_default) : base_url(favicon()) ?>"/>
        <!--<meta property="og:image:width" content="1446"/>-->
        <!--<meta property="og:image:height" content="754"/>-->
        <meta property="og:image:alt" content="<?= !empty($detail)? $detail->judul :'Kelas Brevet AB' ?>"/>
        <meta property="og:image:type" content="image/jpeg"/>
    <?php elseif($uris->getSegment(1) == 'twibbon'): ?>
        <meta content="Siap Mengikuti Pelatihan Brevet Pajak AB" name="description">
        <meta name="robots" content="all" data-rh="true">
        <meta property="og:title" content="<?= title(); ?>" />
        <meta property="og:description" content="Siap Mengikuti Pelatihan Brevet Pajak AB" />
        <meta property="og:url" content="<?= base_url('twibbon'); ?>" />
    
        <meta property="article:section" content="<?= title(); ?>"/>
        <meta property="og:image" content="<?=  base_url("uploads/twibbon.png"); ; ?>"/>
        <meta property="og:image:secure_url" content="<?= base_url("uploads/twibbon.png"); ?>"/>
        <!--<meta property="og:image:width" content="1446"/>-->
        <!--<meta property="og:image:height" content="754"/>-->
        <meta property="og:image:alt" content="<?= title(); ?>"/>
        <meta property="og:image:type" content="image/jpeg"/>
    <?php elseif($uris->getSegment(1) == 'presensi'): ?>
        <?php 
            $dataIklan = $db->table('iklan')->where('status', 'I')->where('status_iklan', 'modal')->orderBy("id", 'desc')->get();
        ?>
        <meta content="Presensi Pelatihan Kelas Brevet Pajak AB" name="description">
        <meta name="robots" content="all" data-rh="true">
        <meta property="og:title" content="<?= title(); ?>" />
        <meta property="og:description" content="Presensi Pelatihan Kelas Brevet Pajak AB" />
        <meta property="og:url" content="<?= base_url('jadwal'); ?>" />
    
        <meta property="article:section" content="<?= title(); ?>"/>
        <meta property="og:image" content="<?= !empty($dataIklan->getRowObject()) ? base_url('uploads/iklan/thumbnails/' . $dataIklan->getRowObject()->file) : base_url(favicon()); ?>"/>
        <meta property="og:image:secure_url" content="<?= !empty($dataIklan->getRowObject()) ? base_url('uploads/iklan/thumbnails/' . $dataIklan->getRowObject()->file) : base_url(favicon()); ?>"/>
        <!--<meta property="og:image:width" content="1446"/>-->
        <!--<meta property="og:image:height" content="754"/>-->
        <meta property="og:image:alt" content="<?= title(); ?>"/>
        <meta property="og:image:type" content="image/jpeg"/>
    <?php elseif($uris->getSegment(1) == 'bimbel'): ?>
        <!-- Open Graph meta tags -->
        <meta name="robots" content="all" data-rh="true">
        <meta property="og:title" content="<?= $paket->nama_paket ?>" />
        <meta property="og:description" content="<?= $paket->nama_paket ?>" />
        <meta property="og:image" content="<?= base_url('assets-landing/images/paket/thumbnails/'.$paket->file) ?>" />
        <meta property="og:url" content="<?= !base_url('assets-landing/images/paket/thumbnails/'.$paket->file) ?>" />
        <meta property="og:type" content="website" />
        <meta property="og:site_name" content="KelasBrevet" />
        
        <!-- Opsional: Twitter card -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="<?= $paket->nama_paket ?>" />
        <meta name="twitter:description" content="Deskripsi singkat." />
        <meta name="twitter:image" content="<?= base_url('assets-landing/images/paket/thumbnails/'.$paket->file) ?>" />
    <?php else: ?>
        <?php 
            $dataIklan = $db->table('iklan')->where('status', 'I')->where('status_iklan', 'modal')->orderBy("id", 'desc')->get();
        ?>
        <meta content="<?= !empty($dataIklan->getRowObject()) ? $dataIklan->getRowObject()->nama : "Kelas Brevet Pajak Online Terpercaya Di Indonesia" ; ?>" name="description">
        <meta name="robots" content="all" data-rh="true">
        <meta property="og:title" content="<?= title(); ?>" />
        <meta property="og:description" content="<?= !empty($dataIklan->getRowObject()) ? $dataIklan->getRowObject()->nama : "Kelas Brevet Pajak Online Terpercaya Di Indonesia" ; ?>" />
        <meta property="og:url" content="<?= !empty($dataIklan->getRowObject()) ? $dataIklan->getRowObject()->url : base_url('/'); ?>" />
    
        <meta property="article:section" content="<?= title(); ?>"/>
        <meta property="og:image" content="<?= !empty($dataIklan->getRowObject()) ? base_url('uploads/iklan/thumbnails/' . $dataIklan->getRowObject()->file) : base_url(favicon()); ; ?>"/>
        <meta property="og:image:secure_url" content="<?= !empty($dataIklan->getRowObject()) ? base_url('uploads/iklan/thumbnails/' . $dataIklan->getRowObject()->file) : base_url(favicon()); ; ?>"/>
        <!--<meta property="og:image:width" content="1446"/>-->
        <!--<meta property="og:image:height" content="754"/>-->
        <meta property="og:image:alt" content="<?= title(); ?>"/>
        <meta property="og:image:type" content="image/jpeg"/>
    <?php endif; ?>
    
    
    <!-- Google tag (gtag.js) -->
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-LVJ4K7XNX9"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-LVJ4K7XNX9');
    </script>
    
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-MFDZ3PMC');</script>
    <!-- End Google Tag Manager -->

    <!-- Favicon -->
    <title><?= ucwords($uris->getSegment(1). ' kelas brevet') ?> </title>
    <link rel="icon" type="image/x-icon" href="<?= base_url(favicon()); ?>" />
    <link rel="canonical" href="<?= current_url() ?>" />

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
    <link rel="stylesheet" href="<?= base_url('assets-landing/css/plugins/magnific-popup.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets-landing/css/plugins/nice-select.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets-landing/css/plugins/apexcharts.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets-landing/css/plugins/jqvmap.min.css'); ?>">

    <!-- Main Style CSS -->
    

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets-landing/css/style.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets-landing/css/plugins/jqvmap.min.css'); ?>">
    
    <link href="<?= base_url('assets/app-assets/template/cbt-malela'); ?>/plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/app-assets/template/cbt-malela'); ?>/plugins/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />
    <script src="<?= base_url('assets/app-assets/template/cbt-malela'); ?>/plugins/sweetalerts/sweetalert2.min.js"></script>
    <style>
        @media only screen and (max-width: 767px) {
            .section-content{
                margin-top: 100px;
            }
            .text-peraturan{
                font-size:12px !important;
            }
            #modalPaket {
                width: 100%;
            }
        }
        @media only screen and (min-width: 768px) {
            .header-logo{
                width:15%;
            }
            #modalPaket {
                width: 35%;
            }
            .zoom {
              /*padding: 50px;*/
              transition: transform .2s; /* Animation */
              margin: 0 auto;
            }
            
            .zoom:hover {
              transform: scale(1.05); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
            }
            .section-content{
                margin-top: 150px;
            }
        } 
        .whatsapp-button {
            position: fixed;
            width: 70px;
            height: 70px;
            bottom: 20px;
            left:20px;
            background-color: #25D366;
            color: #fff;
            border-radius: 100%;
            text-align: center;
            font-size: 30px;
            z-index: 100;
             transition: all 0.3s ease;
             animation: zoomGlow 2s ease-in-out infinite;
        }
        
        @keyframes zoomGlow {
          0% {
            transform: scale(1);
            box-shadow: 0 0 10px rgba(37, 211, 102, 0.5);
          }
          50% {
            transform: scale(1.2);
            box-shadow: 0 0 25px rgba(37, 211, 102, 0.9);
          }
          100% {
            transform: scale(1);
            box-shadow: 0 0 10px rgba(37, 211, 102, 0.5);
          }
        }
        .diskon {
          background: linear-gradient(to left, #CA762B, #D4A017);
          /* Gradasi linier  */
          border-top-right-radius: 20%;
          border-bottom-left-radius: 40%;
          font-weight:bold;
          border:none;
          font-size:14px;
        }
        
        /*//untuk artikel*/
          .block-header{ 
            border-bottom: 3px solid #408c40;
            margin-bottom: 20px;
          }
          .judul-artikel{
              text-align: justify;
              font-size: 18px; 
          }
          .judul-artikel-up{
              text-align: justify;
              font-size: 18px; 
              background-color:rgba(0,0,0, 0.3); 
              padding:10px; 
              color:#ffffff
          }
          .judul-artikel-bottom{
              text-align: justify;
              font-size: 16px; 
          }
          .konten-artikel{
              text-align:justify;
              display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
          }
          .kategori-artikel{
              background: linear-gradient(to left,  #FFA500, #A52A2A);
              border:none;
              font-size:14px;
              margin-bottom:10px;
          }
          
          .posted-on-bottom{
            font-size: 12px;   
          }
          
          .galeri-artikel{
              margin-top:50px;
          }
          .block-title{
              font-size:18px;
              font-weight:bold;
          }
          .block-header{
              margin-top:50px;
          }
          .img-artikel {
              overflow: hidden;
              display: flex;
              align-items: center;
              width:100%;
              height:240px;
              border-radius:20px;
          }
          
          .img-artikel-utama{
              overflow: hidden;
              display: flex;
              align-items: center;
              width:100%;
              height:420px;
              border-radius:20px;
          }
          .card-artikel{
              margin-bottom:50px;
          }
          
          .judul-artikel, .judul-artikel-bottom {
              color:#000000;
              transition: 0.3s;
            }
            
            .judul-artikel:hover, .judul-artikel-bottom:hover, .judul-artikel-up:hover {
                color:#FFA500;
                cursor: pointer
            }
          
          .social-links a{
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
             background-color: rgba(255, 0, 0, 0.25);
            color: red;
            transition: all 0.3s ease;
          }
          .block-line{
            border-bottom: 0.5px solid #408c40;
            margin-bottom: 10px;
            margin-top:20px;
          }
          
          .siap-kerja{
              margin-top:50px;
          }
        .photo-gallery .photo-thumb .icon i{
            color: #fff;
            font-size: 24px;
            width: 44px;
            height: 44px;
            line-height: 40px;
            text-align: center;
            border-radius: 50%;
            border: 2px solid #fff;
        }
        .photo-caption{
            font-size:14px;
            font-weight:bold;
            margin:5px 0px;
        }
        .photo-date{
            font-size:14px;
        }
        .nav-menu a {
            font-size:16px !important;
            font-weight: 550;
        }
        .close-deskripsi-paket{
            margin:13px 0 0 10px; 
            border-radius:100%; 
            background-color:blue; 
            border: none
        }
        .horizontal-nav {
          overflow: hidden; /* Menghilangkan overflow */
        }
    
        .horizontal-nav ul {
          list-style-type: none;
          margin: 0;
          padding: 0;
          overflow: hidden;
        }
    
        .horizontal-nav li {
          float: left;
        }
    
        .horizontal-nav li a {
          display: block;
          color: white;
          text-align: center;
          padding: 14px 16px;
          text-decoration: none;
        }
    
        .horizontal-nav li a:hover {
          color: #FFBD3A !important;
          
        }
        
        .nav-menu-header{
            color:#FFBD3A !important;
        }
    
        .submenu {
          display: none;
          position: absolute;
          background-color: #fff;
          /*min-width: 160px;*/
          width:10%;
          box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
          z-index: 1;
          font-size:10px;
        }
    
        .submenu li {
          float: none;
        }
        
        .submenu ul li a {
          color: black;
          padding: 12px 16px;
          text-decoration: none;
          display: block;
          text-align: left;
          font-size:12px !important;
          
        }
        
        .submenu a:hover {
          background-color: #ddd;
        }
        
        .horizontal-nav li:hover .submenu {
          display: block;
        }
        
        img.lazy {
          filter: blur(5px);
          transition: filter 0.5s ease;
        }
        
        img.fade-in {
          filter: blur(0);
        }
        
        /* Chat box */
        .chat-box {
          position: fixed;
          bottom: 70px;
          left: 20px;
          width: 320px;
          max-height: 500px;
          background: white;
          border-radius: 10px;
          box-shadow: 0 4px 20px rgba(0,0,0,0.2);
          display: none;
          flex-direction: column;
          overflow: hidden;
          z-index: 1050;
        }
    
        .chat-header {
          background: #6f42c1;
          color: white;
          padding: 10px;
          display: flex;
          justify-content: space-between;
          align-items: center;
        }
    
        .chat-body {
          padding: 15px;
          flex: 1;
          overflow-y: auto;
          font-size: 14px;
        }

        
    </style>
    <?= $this->renderSection('css'); ?>
   
    <script>
        gtag('event', 'ads_conversion_PAGE_VIEW_1', {
        // <event_parameters>
        });
    </script>
    <?= $schema ?>
</head>

<body>
    <?php 
        $statistik_hariini = $db->query("SELECT COUNT(id) as total FROM `statistik_hits` WHERE date(created_at) = date(now())")->getRowObject();
        $statistik_lampau = $db->query("SELECT COUNT(id) as total  FROM `statistik_hits` WHERE date(created_at) = DATE(NOW() - INTERVAL 1 DAY)")->getRowObject();
        $statistik_minggu = $db->query("SELECT COUNT(id) as total  FROM `statistik_hits` WHERE DATE(created_at) >= DATE(NOW() - INTERVAL 7 DAY)")->getRowObject();
        $statistik_bulan = $db->query("SELECT COUNT(id) as total  FROM `statistik_hits` WHERE DATE(created_at) >= DATE(NOW() - INTERVAL 30 DAY)")->getRowObject();
    ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MFDZ3PMC"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
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
                            <a href="https://kelasbrevet.com/Transaksi/pesan/T2w0WnJ0ZWhBWmNUR3A1endmZTdkdz09 " class="badge text-bg-primary">Ambil Promo</a>
                        </div>
                        <!-- Header Top Right End -->
                    </div>
                    <!-- Header Top Wrapper End -->
                </div>
            </div>
            <!-- Header Top End -->

            <!-- Header Main Start -->
            <div class="header-main">
                <div class="container">

                    <!-- Header Main Start -->
                    <div class="header-main-wrapper">

                        <!-- Header Logo Start -->
                        <div class="header-logo">
                            <a href="<?= base_url('/') ?>"><?= img_lazy('assets-landing/images/logo.png',"loading", ['class' => 'img-fluid']) ?></a>
                        </div>
                        <!-- Header Logo End -->
                        <?php 
                            $tentangkami = array(
                                                "tentangkami"
                                            );
                            $pelatihan = array(
                                                "pelatihan"
                                            );
                            $penilaian = array(
                                                "penilaian"
                                            );
                            $testimoni = array(
                                                "testimoni"
                                            );
                            $informasi = array(
                                                "artikel",
                                                "jadwal",
                                                "galeri",
                                                "media-kelasbrevet",
                                                "siap-kerja",
                                                "twibbon",
                                                "presensi"
                                            );
                        ?>
                        <!-- Header Menu Start -->
                        <div class="horizontal-nav d-none d-lg-block ">
                            <ul class="nav-menu ">
                                <li>
                                    <a href="<?= base_url("tentangkami") ?>" class="text-dark <?= set_active_nav($tentangkami); ?>">Tentang Kami</a>
                                </li>
                                <li>
                                    <a href="<?= base_url("pelatihan") ?>" class="text-dark <?= set_active_nav($pelatihan); ?>">Pelatihan</a>
                                </li>
                                <li>
                                    <a href="<?= base_url("penilaian") ?>" class="text-dark <?= set_active_nav($penilaian); ?>">Penilaian</a>
                                </li>
                                <li>
                                    <a href="<?= base_url("testimoni") ?>" class="text-dark <?= set_active_nav($testimoni); ?>">Testimoni</a>
                                </li>
                                <li>
                                    <a href="#" class="text-dark <?= set_active_nav($informasi); ?>">Informasi<i class="bi bi-chevron-down"></i></a>
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

                        </div>
                        <!-- Header Menu End -->

                        <!-- Header Sing In & Up Start -->
                        <div class="header-sign-in-up d-none d-lg-block">
                            <ul  class="nav-menu ">
                                <?php if(session('nama') == ''): ?>
                                    <li><a class="sign-in" href="<?= base_url('auth'); ?>">Masuk</a></li>
                                    <!--<li><a class="sign-up" href="<?= base_url('Register'); ?>">Daftar</a></li>-->
                                <?php else: ?>
                                <?php 
                                    if(session('role') == '1'):
                                       $url =  base_url('App');
                                    elseif(session('role') == '2'):
                                        $url =  base_url('siswa'); 
                                    elseif(session('role') == '3'):
                                        $url =  base_url('guru');
                                    elseif(session('role') == '4'):
                                        $url =  base_url('Mitra');
                                    elseif(session('role') == '5'):
                                        $url =  base_url('Pic');
                                    endif;
                                    
                                ?>
                                    <li><a class="sign-in mr-2" href="<?= $url; ?>"><?= mb_strimwidth(session('nama'), 0, 8, '..')  ?></a></li>
                                    
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
                    <?php if(session('nama') == ''): ?>
                        <li><a class="sign-in" href="<?= base_url('auth'); ?>">Masuk</a></li>
                        <li><a class="sign-up" href="<?= base_url('Register'); ?>">Daftar</a></li>
                    <?php else: ?>
                    <?php 
                        if(session('role') == '1'):
                           $url =  base_url('App');
                        elseif(session('role') == '2'):
                            $url =  base_url('siswa'); 
                        elseif(session('role') == '3'):
                            $url =  base_url('guru');
                        elseif(session('role') == '4'):
                            $url =  base_url('Mitra');
                        elseif(session('role') == '5'):
                            $url =  base_url('Pic');
                        endif;
                        
                    ?>
                        <li><a href="<?= $url; ?>"><?= mb_strimwidth(session('nama'), 0, 15, '...')  ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <!-- Mobile Sing In & Up End -->

            <!-- Mobile Menu Start -->
            <div class="mobile-menu-items">
                <ul class="nav-menu">
                    <li>
                        <a href="<?= base_url("tentangkami") ?>" class="">Tentang Kami</a>
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


        <div class="section section-content">
            <?= $this->renderSection('content'); ?>
        </div>
        

        <!-- Footer Start  -->
        <div class="section footer-section">

            <!-- Footer Widget Section Start -->
            <div class="footer-widget-section">

                <?= img_lazy('assets-landing/images/shape/shape-21.png',"loading", ['class' => 'shape-1 animation-down']) ?>

                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 order-md-6 order-lg-6">

                            <!-- Footer Widget Start -->
                            <div class="footer-widget">
                                <div class="widget-logo">
                                    <a href="#"><?= img_lazy('assets-landing/images/logo.png',"loading", ['class' => '']) ?></a>
                                </div>

                                <div>
                                    <p>Kelas Brevet merupakan platform pelatihan Brevet Pajak AB yang Terdaftar Resmi. Diselenggarakan oleh Akuntanmu Learning Center By Legalyn Konsultan Indonesia (Lembaga Pelatihan, Kursus/Bimbel, yang didirikan sejak tahun 2021)</p>
                                </div>

                                <ul class="widget-info">
                                    <li>
                                        <p> <i class="flaticon-email"></i> <a
                                                href="mailto:support@kelasbrevet.com">support@kelasbrevet.com</a> </p>
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
                                                <div class="col-4 col-md-4 col-lg-4"><?= img_lazy('assets-landing/images/lembaga/kemendikbud.png',"loading", ['class' => '', "whidth"=>"50"]) ?></div>
                                                <div class="col-4 col-md-4 col-lg-4"><?= img_lazy('assets-landing/images/lembaga/kemenaker.png',"loading", ['class' => '']) ?></div>
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
                                            <li><p><a href="#">Today's Views: <?= nominal($statistik_hariini->total) ?></a></p></li>
                                            <li><p><a href="#">Yesterday's Views: <?= nominal($statistik_lampau->total) ?></a></p></li>
                                            <li><p><a href="#">7 Days Views: <?= nominal($statistik_minggu->total) ?></a></p></li>
                                            <li><p><a href="#">30 Days Views: <?= nominal($statistik_bulan->total) ?></a></p></li>
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
                                <a href="<?= base_url("term") ?>" class="text-dark">Terms of Conditions</a>
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

        </div>

        <!-- Footer End -->

        <!--Back To Start-->
        <a href="#" class="back-to-top">
            <i class="icofont-simple-up"></i>
        </a>
        
        <!-- Chat Floating Button -->
        <div id="chatButton">
            <a href="javascript:void(0)" data-url="Halo,%20saya%20ingin%20bertanya..." class="whatsapp-button d-flex align-items-center justify-content-center">
                <?= img_lazy('uploads/icon/call.png',"loading", ['class' => '']) ?>
            </a>
        </div>
        
          <!-- Chat Box -->
        <div class="chat-box" id="chatBox">
            <div class="chat-header">
              <span><?= img_lazy('assets-landing/images/logo-putih.png',"loading", ['class' => 'img-fluid', "width" => '130px']) ?></span>
              <button class="badge bg-light p-2 border-0 radius-10 text-dark" id="closeChat"><i class="bi bi-x"></i></button>
            </div>
            <div class="chat-body">
              <p><strong>Halo!</strong> Saya Kelasbrevet, Ada yang bisa saya bantu?</p>
              <div class="d-grid gap-2">
                <a href="javascript:void(0)" data-url="Halo Kelas Brevet,%20saya%20ingin%20bertanya..."  target="_blank" class="badge bg-secondary p-2 border-0 text-dark myFunctionWACs">
                    Customer Service
                </a>
                <a href="javascript:void(0)" data-url="Halo Kelas Brevet,%20saya%20ingin%20bertanya..."  target="_blank" class="badge bg-secondary p-2 border-0 text-dark myFunctionWATs">
                    Technical Support - Untuk kendala website</button>
                </a>
              </div>
            </div>
        </div>
    </div>


    <!-- JS
    ============================================ -->

    <!-- Modernizer & jQuery JS -->
    <script src="<?= base_url('assets-landing/js/vendor/modernizr-3.11.2.min.js'); ?>"></script>
    <script src="<?= base_url('assets-landing/js/vendor/jquery-3.5.1.min.js'); ?>"></script>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets-landing/js/plugins/popper.min.js'); ?>"></script>
    <script src="<?= base_url('assets-landing/js/plugins/bootstrap.min.js'); ?>"></script>

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
    
    <script src="https://code.jquery.com/jquery-3.7.1.jss"></script>
    <!--<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>-->
    <!--<script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.js"></script>-->
    <!--<script>-->
    <!--    new DataTable('#example' , {-->
    <!--        searching: false,-->
    <!--        bInfo: false,-->
    <!--        bLengthChange : false,-->
    <!--        responsive: true-->
    <!--    });-->
    <!--</script>-->
    
    
    <script>
        $(document).on('click', '.btn-copy-link', function () {
            let btn = $(this);
            let paket_id = btn.data('paket_id');
        
            btn.prop('disabled', true);
        
            $.ajax({
                url: "<?= base_url('siswa/affiliate/copy') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    paket_id: paket_id,
                    <?= csrf_token() ?>: "<?= csrf_hash() ?>"
                },
                success: function (res) {
                    if (res.status !== 'success') {
                        showAlert('error', 'Gagal!', 'Link gagal dibuat.');
                        btn.prop('disabled', false);
                        return;
                    }
        
                    let link = res.link;
        
                    // ===============================
                    // COPY CLIPBOARD (iOS SAFE)
                    // ===============================
                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(link)
                            .then(() => showAlert('success', 'Berhasil!', 'Link berhasil disalin.'))
                            .catch(() => fallbackCopy(link));
                    } else {
                        fallbackCopy(link);
                    }
        
                    function fallbackCopy(text) {
                        let input = document.getElementById('clipboard-temp');
                        input.value = text;
                        document.body.appendChild(input); // pastikan ada di DOM
                        input.focus();
                        input.select();
        
                        let success = false;
                        try {
                            success = document.execCommand('copy');
                        } catch (e) {}
        
                        if (success) {
                            showAlert('success', 'Berhasil!', 'Link berhasil disalin.');
                        } else {
                            showAlert('error', 'info', 'Link gagal di copy, Silahkan coba lagi');
                        }
        
                        input.blur(); // hapus fokus
                    }
        
                    btn.prop('disabled', false);
                },
                error: function () {
                    showAlert('error', 'Error!', 'Terjadi kesalahan server.');
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
        $(document).on('click', '.share-link', function (e) {
            e.preventDefault();
        
            let btn = $(this);
            let paket_id = btn.data('paket_id');
        
            btn.prop('disabled', true);
        
            // 🔥 buka window dulu (masih user gesture)
            let waWindow = window.open('about:blank', '_blank');
        
            $.ajax({
                url: "<?= base_url('siswa/affiliate/copy') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    paket_id: paket_id,
                    <?= csrf_token() ?>: "<?= csrf_hash() ?>"
                },
                success: function (res) {
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
                error: function () {
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
        // $tickerWrapper.on("mouseenter", function() {
        //     infinite.pause();
        // }).on("mouseleave", function() {
        //     infinite.play();
        // });
        
         setTimeout(function() {

          $('#iklanDepan').modal('show');
    
        }, 2000);
    
    
        $("#test").click(function(){ 
            $("#iklanDepan").modal('hide');
        });
        
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
    
            this.el.innerHTML = '<span class="wrap">'+this.txt+'</span>';
    
            var that = this;
            var delta = 200 - Math.random() * 100;
    
            if (this.isDeleting) { delta /= 2; }
    
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
            for (var i=0; i<elements.length; i++) {
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
                <img src="${url}" class="img-fluid img-thumbnail" alt="...">
            `);
        });
        
        $('.deskripsi_paket').click(function() {
            const idpaket = $(this).data('idpaket');
            console.log(idpaket);
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
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
      let lazyImages = document.querySelectorAll("img.lazy");
    
      if ("IntersectionObserver" in window) {
        let observer = new IntersectionObserver((entries, obs) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              let img = entry.target;
              let realSrc = img.dataset.src;
    
              // buat objek Image baru biar nunggu sampai siap
              let loader = new Image();
              loader.src = realSrc;
    
              loader.onload = function() {
                img.src = realSrc;
                img.classList.add("fade-in"); // animasi
              };
    
              img.removeAttribute("data-src");
              obs.unobserve(img);
            }
          });
        });
    
        lazyImages.forEach(img => observer.observe(img));
      }
    });
    </script>
    <?= $this->renderSection('script'); ?>
    <script>
        <?= session()->getFlashdata('pesan'); ?>
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
        
        $('.myFunctionWACs').click(function() {
            var slugWa = $(this).data('url');
            window.location = "https://api.whatsapp.com/send?phone=6282180744966&text="+slugWa;
        });
        
        $('.myFunctionWATs').click(function() {
            var slugWa = $(this).data('url');
            window.location = "https://api.whatsapp.com/send?phone=6281532423436&text="+slugWa;
        });
    </script>

</body>

</html>
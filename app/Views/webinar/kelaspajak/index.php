<?php
// UPGRADE: Mengambil data paket (Kini berupa Array Banyak Paket)
$daftarPaket = (!empty($katalog_webinar) && is_array($katalog_webinar)) ? $katalog_webinar : [];
// Ambil paket pertama untuk mengisi SEO Meta Tag & Hero Header
$paketUtama = !empty($daftarPaket) ? $daftarPaket[0] : null;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Meta Tags Dinamis -->
    <title><?= $paketUtama ? esc($paketUtama->nama_paket) : 'Kelas Brevet Pajak Online Terpercaya Di Indonesia' ?></title>
    <meta name="description" content="<?= $paketUtama ? esc($paketUtama->tagline) : 'Ikuti webinar eksklusif kami dan dapatkan e-sertifikat, materi lengkap, dan sesi mentoring dari pakar perpajakan.' ?>">
    <meta name="keywords" content="webinar bisnis, transformasi digital, belajar bisnis, kelas online, webinar 2026, kelas brevet, pajak,  <?= $paketUtama ? esc(strtolower($paketUtama->nama_paket)) : '' ?>">
    <meta name="author" content="kelasbrevet.com">
    <meta name="robots" content="index, follow">

    <!-- Open Graph (Untuk Social Media/WhatsApp Sharing) Dinamis -->
    <meta property="og:title" content="<?= $paketUtama ? esc($paketUtama->nama_paket) : 'Webinar Eksklusif: Strategi Perpajakan' ?>">
    <meta property="og:description" content="<?= $paketUtama ? esc($paketUtama->tagline) : 'Kelas Brevet akan mengajak anda untuk lebih dekat dengan regulasi bersama para pakar perpajakan di Indonesia. Daftar sekarang!' ?>">
    <meta property="og:image" content="<?= ($paketUtama && !empty($paketUtama->file)) ? base_url('assets-landing/images/paket/thumbnails/' . $paketUtama->file) : base_url(favicon()) ?>">
    <meta property="og:url" content="<?= current_url() ?>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        /* Tema Warna & Tipografi */
        :root {
            --primary-blue: #0d6efd;
            --dark-blue: #0a4297;
            --light-blue: #eef4ff;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #333;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--light-blue) 0%, #ffffff 100%);
            padding: 50px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .hero-title {
            font-weight: 800;
            color: var(--dark-blue);
            line-height: 1.2;
        }

        /* Countdown Timer */
        .countdown-box {
            background: white;
            border: 2px solid var(--primary-blue);
            border-radius: 12px;
            padding: 15px 10px;
            text-align: center;
            min-width: 80px;
            box-shadow: 0 4px 6px rgba(13, 110, 253, 0.1);
        }

        .countdown-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-blue);
            display: block;
            line-height: 1;
        }

        .countdown-label {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            margin-top: 5px;
        }

        /* PENGAMAN RESPONSIF MOBILE: Agar tidak offside/meluber di layar kecil */
        @media (max-width: 576px) {
            .countdown-box {
                min-width: 55px !important;
                padding: 8px 4px !important;
                border-radius: 8px !important;
                border-width: 1.5px !important;
            }

            .countdown-number {
                font-size: 1.2rem !important;
            }

            .countdown-label {
                font-size: 0.6rem !important;
                margin-top: 3px !important;
            }
        }

        /* Styling tombol custom yang sudah ada */
        .btn-custom {
            padding: 14px 32px;
            font-weight: 700;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        /* TAMBAHAN: Pengaman Responsif Mobile untuk Tombol Hero */
        @media (max-width: 576px) {
            .btn-custom {
                padding: 10px 24px !important;
                /* Padding diperkecil agar pas di HP */
                font-size: 0.9rem !important;
                /* Ukuran teks disesuaikan */
            }
        }

        /* Cards & Buttons */
        .benefit-card {
            border: none;
            background: #fff;
            border-radius: 16px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .benefit-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(13, 110, 253, 0.15);
        }

        .icon-box {
            width: 60px;
            height: 60px;
            background: var(--light-blue);
            color: var(--primary-blue);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .btn-custom {
            padding: 14px 32px;
            font-weight: 700;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-primary-custom {
            background-color: var(--primary-blue);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
        }

        .btn-primary-custom:hover {
            background-color: var(--dark-blue);
            color: white;
            transform: translateY(-2px);
        }

        /* CTA Section */
        .cta-section {
            background-color: var(--dark-blue);
            color: white;
            padding: 80px 0;
            background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
        }
    </style>
    <style>
        /* Styling khusus untuk card pilihan sesi */
        .session-card {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            background-color: #fff;
        }

        .session-card:hover {
            border-color: #0d6efd;
            background-color: #f8faff;
        }

        /* Sembunyikan checkbox bawaan */
        .session-checkbox {
            display: none;
        }

        /* Jika checkbox dicheck, ubah tampilan card-nya */
        .session-checkbox:checked+.session-card {
            border-color: #0d6efd;
            background-color: #eef4ff;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.15);
        }

        .session-checkbox:checked+.session-card .check-icon {
            color: #0d6efd;
            opacity: 1 !important;
        }

        .check-icon {
            opacity: 0.2;
            transition: all 0.2s;
        }
    </style>
    <style>
        /* CSS khusus untuk tombol Google */
        .btn-google-custom {
            background-color: #ffffff !important;
            color: #212121 !important;
            border: 1px solid #dee2e6;
            transition: color 0.3s ease, border-color 0.3s ease;
        }

        .btn-google-custom:hover {
            color: #0d6efd !important;
            border-color: #0d6efd !important;
            background-color: #ffffff !important;
        }
    </style>
</head>

<body>

    <header class="sticky-top bg-white shadow-sm" style="z-index: 1030;">
        <nav class="navbar navbar-expand-lg navbar-light py-3">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary" href="<?= base_url('/') ?>">
                    <img src="<?= base_url('assets-landing/images/logo.png') ?>" alt="Logo" height="35" width="auto">
                </a>

                <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul>

                    <div class="d-flex align-items-center mt-3 mt-lg-0">
                        <?php if (session()->get('id')) : ?>
                            <a href="<?= base_url('sw-siswa') ?>" class="btn btn-outline-primary fw-semibold rounded-pill px-4 w-100 w-lg-auto">
                                <i class="fa-solid fa-user-circle me-1"></i> Dashboard
                            </a>
                        <?php else : ?>
                            <a href="<?= base_url('auth') ?>" class="btn btn-primary fw-semibold rounded-pill px-4 shadow-sm w-100 w-lg-auto">
                                <i class="fa-solid fa-right-to-bracket me-1"></i> Masuk / Login
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <!-- SECTION 1: HERO & COUNTDOWN -->
        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center flex-column-reverse flex-lg-row">
                    <div class="col-lg-6 mt-5 mt-lg-0">
                        <h1 class="hero-title mb-4"><?= $paketUtama ? esc($paketUtama->nama_paket) : 'Strategi Jitu Transformasi Bisnis di Era Kecerdasan Buatan' ?></h1>
                        <p class="lead text-secondary mb-4">
                            <?= $paketUtama && !empty($paketUtama->deskripsi) ? $paketUtama->deskripsi : 'Pelajari langkah demi langkah bagaimana mengamankan posisi bisnis Anda di masa depan. Dibawakan langsung oleh praktisi industri terkemuka.' ?>
                        </p>

                        <div class="mb-5">
                            <h5 class="fw-bold mb-3 text-dark fs-6 fs-lg-5">Sesi Selanjutnya Dimulai Dalam:</h5>
                            <div class="d-flex gap-2 gap-md-3">
                                <div class="countdown-box"><span class="countdown-number" id="days">00</span><span class="countdown-label">Hari</span></div>
                                <div class="countdown-box"><span class="countdown-number" id="hours">00</span><span class="countdown-label">Jam</span></div>
                                <div class="countdown-box"><span class="countdown-number" id="minutes">00</span><span class="countdown-label">Menit</span></div>
                                <div class="countdown-box"><span class="countdown-number" id="seconds">00</span><span class="countdown-label">Detik</span></div>
                            </div>
                        </div>

                        <!-- Tombol dengan kelas responsif yang proporsional di mobile & desktop -->
                        <a href="#pendaftaran" class="btn btn-primary-custom btn-custom w-100 w-sm-auto shadow-sm">
                            <i class="fa-solid fa-ticket me-2"></i> Amankan Tiket Saya
                        </a>
                    </div>

                    <div class="col-lg-6 text-center">
                        <?php
                        // Mendapatkan path fisik file di server untuk mengecek kapan terakhir diedit
                        $videoPath = FCPATH . 'uploads/video/openingv1.mp4';
                        $versi = file_exists($videoPath) ? filemtime($videoPath) : 1;

                        $videoUrl = base_url('uploads/video/opening.mp4?v=' . $versi);
                        ?>
                        <div class="ratio ratio-16x9 rounded-4 shadow-lg border border-3 border-white overflow-hidden" style="pointer-events: none;">
                            <video src="<?= $videoUrl ?>" autoplay muted loop playsinline style="object-fit: cover; width: 100%; height: 100%;"></video>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION PAKAR & JADWAL PERTEMUAN -->
        <?php
        $pakarUtama = [
            'keynote' => [
                'role'  => 'PEMBICARA',
                'nama'  => 'Nurtiyas, S.E., M.Ak., BKP',
                'desc'  => 'Ketua LKP - Kelas Brevet<br>Registered Tax Consultant',
                'image' => base_url('uploads/webinar/nurtiyas.png')
            ],
            'moderator' => [
                'role'  => 'MODERATOR',
                'nama'  => 'Muhammad Fajrul Falah, S.H',
                'desc'  => 'Legal Specialist<br>ND Tax and Law',
                'image' => base_url('uploads/webinar/fajrul.png')
            ]
        ];
        ?>
        <section id="pakar-jadwal" class="py-4" style="background-color: #0b5ed7;">
            <style>
                .text-navy {
                    color: #0c2b6b !important;
                }

                .bg-navy {
                    background-color: #122b6d !important;
                }

                .bg-yellow {
                    background-color: #ffc107 !important;
                }

                .img-top {
                    width: 90px;
                    height: 90px;
                }

                .img-card {
                    width: 75px;
                    height: 75px;
                }

                .img-top,
                .img-card {
                    object-fit: cover;
                    border: 3px solid #0b5ed7;
                    padding: 2px;
                    background-color: white;
                }

                .badge-pertemuan {
                    position: absolute;
                    top: 0;
                    left: 0;
                    font-size: 0.75rem;
                    font-weight: 700;
                    padding: 6px 12px;
                    border-bottom-right-radius: 12px;
                    border-top-left-radius: 15px;
                    z-index: 10;
                }

                .separator-line {
                    width: 2px;
                    background-color: #dee2e6;
                }

                @media (max-width: 768px) {
                    .separator-line {
                        width: 100%;
                        height: 2px;
                        margin: 1rem 0;
                    }
                }

                .jadwal-divider-container {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 2rem 0;
                }
            </style>
            <div class="container pb-3">
                <div class="jadwal-divider-container">
                    <div class="bg-yellow text-navy fw-bold px-4 py-1 rounded-pill mx-3 shadow-sm" style="font-size: 1rem;">
                        <h3>KAMI MENGHADIRKAN</h3>
                    </div>
                </div>
                <div class="card bg-white border-0 shadow-sm mx-auto mb-4" style="border-radius: 15px; max-width: 850px;">
                    <div class="card-body p-3 p-md-4">
                        <div class="row align-items-center">
                            <div class="col-md-6 d-flex align-items-center gap-3 mb-3 mb-md-0">
                                <img src="<?= $pakarUtama['keynote']['image'] ?>?v=<?= time() ?>" alt="Keynote" class="rounded-circle img-top shadow-sm flex-shrink-0">
                                <div>
                                    <span class="badge bg-yellow text-dark rounded-pill px-2 py-1 mb-1 fw-bold" style="font-size: 0.65rem;"><?= $pakarUtama['keynote']['role'] ?></span>
                                    <h6 class="fw-bold text-navy mb-1" style="font-size: 0.95rem;"><?= $pakarUtama['keynote']['nama'] ?></h6>
                                    <p class="text-secondary mb-0" style="font-size: 0.8rem; line-height: 1.3;"><?= $pakarUtama['keynote']['desc'] ?></p>
                                </div>
                            </div>
                            <div class="separator-line h-75 d-none d-md-block"></div>
                            <div class="separator-line d-block d-md-none"></div>
                            <div class="col-md d-flex align-items-center gap-3 ps-md-4">
                                <img src="<?= $pakarUtama['moderator']['image'] ?>?v=<?= time() ?>" alt="Moderator" class="rounded-circle img-top shadow-sm flex-shrink-0">
                                <div>
                                    <span class="badge bg-yellow text-dark rounded-pill px-2 py-1 mb-1 fw-bold" style="font-size: 0.65rem;"><?= $pakarUtama['moderator']['role'] ?></span>
                                    <h6 class="fw-bold text-navy mb-1" style="font-size: 0.95rem;"><?= $pakarUtama['moderator']['nama'] ?></h6>
                                    <p class="text-secondary mb-0" style="font-size: 0.8rem; line-height: 1.3;"><?= $pakarUtama['moderator']['desc'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION DAFTAR -->
        <section id="pendaftaran" class="py-4 py-lg-5 bg-light">
            <style>
                .session-card {
                    border: 2px solid #dee2e6 !important;
                    transition: all 0.3s ease-in-out;
                    cursor: pointer;
                }

                .session-checkbox:checked+.session-card {
                    border: 2px solid #198754 !important;
                    background-color: #f2fcf5 !important;
                    box-shadow: 0 0.5rem 1.5rem rgba(25, 135, 84, 0.2) !important;
                }

                .session-checkbox {
                    display: none;
                }

                /* Perbaikan agar teks nominal/harga aman tidak offside di mobile */
                .price-container {
                    word-break: break-word;
                    overflow-wrap: break-word;
                }

                @media (max-width: 768px) {
                    .session-card {
                        padding: 1rem !important;
                    }

                    .session-card h5 {
                        font-size: 0.95rem !important;
                    }

                    .session-card .badge {
                        font-size: 0.6rem !important;
                    }
                }
            </style>

            <div class="container py-3">
                <div class="text-center mb-4 mb-lg-5">
                    <span class="badge bg-primary px-3 py-2 rounded-pill mb-2">Formulir Pendaftaran</span>
                    <h2 class="fw-bold text-dark fs-3 fs-lg-2">Pilih Paket & Amankan Tiket</h2>
                    <p class="text-secondary fs-6 px-2">Pilih salah satu paket di bawah ini yang sesuai dengan kebutuhan Anda.</p>
                </div>

                <form action="<?= base_url('webinar/daftar') ?>" method="POST" id="formWebinar">
                    <?= csrf_field() ?>
                    <!-- ID Paket dikosongkan dan akan diisi dinamis oleh Javascript saat radio diklik -->
                    <input type="hidden" name="idpaket" id="input_idpaket" value="">

                    <?php
                    $currentDateTime = date('Y-m-d H:i:s');
                    $db = \Config\Database::connect();
                    ?>

                    <!-- ========================================================== -->
                    <!-- BAGIAN 1: BARIS ATAS (PILIHAN PAKET FULL WIDTH - 2 KOLOM) -->
                    <!-- ========================================================== -->
                    <div class="mb-5">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
                            <h4 class="fw-bold mb-0 fs-4">1. Pilih Paket</h4>
                        </div>

                        <div class="row g-3 g-lg-4">
                            <?php if (!empty($daftarPaket)) : ?>
                                <?php foreach ($daftarPaket as $paket) : ?>
                                    <?php if (!empty($paket->sesi)) : ?>
                                        <?php foreach ($paket->sesi as $sesi) : ?>
                                            <?php
                                            $isFree = ($sesi['harga_sesi'] <= 0);
                                            $sekarang = strtotime($currentDateTime);
                                            $isExpired = true;

                                            $waktuMulaiParent = strtotime($sesi['waktu_mulai']);
                                            if ($waktuMulaiParent > $sekarang || date('Y-m-d', $waktuMulaiParent) === date('Y-m-d', $sekarang)) {
                                                $isExpired = false;
                                            }

                                            $childSessionsData = [];
                                            $childIds = json_decode($sesi['sesi_gratis'], true) ?? [];

                                            if (!empty($childIds)) {
                                                $childSessionsData = $db->table('webinar_sesi')
                                                    ->whereIn('id_sesi', $childIds)
                                                    ->orderBy('waktu_mulai', 'ASC')
                                                    ->get()
                                                    ->getResultArray();

                                                foreach ($childSessionsData as $cs) {
                                                    $waktuMulaiChild = strtotime($cs['waktu_mulai']);
                                                    if ($waktuMulaiChild > $sekarang || date('Y-m-d', $waktuMulaiChild) === date('Y-m-d', $sekarang)) {
                                                        $isExpired = false;
                                                    }
                                                }
                                            }
                                            ?>
                                            <!-- MENGGUNAKAN col-lg-6 AGAR TAMPIL 2 PER BARIS SEPERTI SCREENSHOT -->
                                            <div class="col-lg-6">
                                                <label class="w-100 h-100 m-0 <?= $isExpired ? 'opacity-50' : '' ?>" <?= $isExpired ? 'style="cursor: not-allowed;"' : 'style="cursor: pointer;"' ?>>

                                                    <!-- Tambahkan data-idpaket -->
                                                    <input type="radio" name="id_sesi[]" value="<?= esc($sesi['id_sesi']) ?>" class="session-checkbox calculate-price" data-price="<?= round($sesi['harga_sesi']) ?>" data-idpaket="<?= $paket->idpaket ?>" <?= $isExpired ? 'disabled' : '' ?> <?= $isFree && !$isExpired ? 'checked' : '' ?>>

                                                    <div class="session-card p-3 p-lg-4 h-100 d-flex flex-column rounded-4 shadow-sm <?= $isExpired ? 'bg-light' : 'bg-white' ?>">
                                                        <div class="d-flex align-items-start justify-content-between w-100 gap-2">
                                                            <!-- Ditambahkan flex-grow-1 dan min-width agar teks judul tidak mendorong harga ke luar card -->
                                                            <div class="pe-1 flex-grow-1" style="min-width: 0;">
                                                                <h5 class="fw-bold mb-1 fs-6 fs-lg-4 text-break"><?= esc($sesi['nama_sesi']) ?></h5>
                                                                <?php if ($isExpired): ?>
                                                                    <div class="mb-1"><span class="badge bg-danger mt-1" style="font-size: 0.7rem;">Semua Sesi Telah Berakhir</span></div>
                                                                <?php elseif ($isFree): ?>
                                                                    <div class="mb-1"><span class="badge bg-success mt-1" style="font-size: 0.65rem;">Fasilitas Terbatas</span></div>
                                                                <?php endif; ?>

                                                                <?php if (!$isFree && !empty($sesi['deskripsi_sesi'])): ?>
                                                                    <p class="text-secondary mb-0 mt-2" style="font-size: 0.9rem; line-height: 1.4;">
                                                                        <span class="badge bg-primary mt-1 px-2 py-1" style="font-size: 0.7rem; white-space: normal;">Fasilitas Full Akses</span>
                                                                    </p>
                                                                <?php endif; ?>
                                                            </div>

                                                            <!-- Bagian Harga Paket: Diatur agar tetap rapi di sisi kanan -->
                                                            <div class="text-end flex-shrink-0 price-container ps-1">
                                                                <?php if (!$isFree && isset($sesi['harga_coret']) && $sesi['harga_coret'] > $sesi['harga_sesi']): ?>
                                                                    <span class="text-muted text-decoration-line-through d-block" style="font-size: 0.75rem;">Rp <?= number_format($sesi['harga_coret'], 0, ',', '.') ?></span>
                                                                <?php endif; ?>
                                                                <h5 class="fw-bold <?= $isExpired ? 'text-muted text-decoration-line-through' : 'text-primary' ?> d-block mb-1 fs-6 fs-lg-5 text-nowrap">
                                                                    <?= $isFree ? 'Rp 0' : 'Rp ' . number_format($sesi['harga_sesi'], 0, ',', '.') ?>
                                                                </h5>
                                                            </div>
                                                        </div>

                                                        <div class="mt-3 mt-lg-4 pt-3 border-top flex-grow-1">
                                                            <?php if (!empty($childSessionsData)): ?>
                                                                <p class="text-dark fw-bold fs-7 mb-2 mb-lg-3"><i class="fa-solid fa-layer-group me-2 text-primary"></i>Materi Spesial Perpajakan:</p>
                                                                <ul class="list-unstyled mb-3 mb-lg-4" style="margin-bottom: 0;">
                                                                    <?php foreach ($childSessionsData as $cs): ?>
                                                                        <?php
                                                                        $waktuMulaiChild = strtotime($cs['waktu_mulai']);
                                                                        $isCsExpired = ($waktuMulaiChild < $sekarang && date('Y-m-d', $waktuMulaiChild) !== date('Y-m-d', $sekarang));
                                                                        ?>
                                                                        <li class="<?= $isCsExpired ? 'opacity-75' : '' ?> mb-2 mb-lg-3 d-flex align-items-start">
                                                                            <i class="fa-solid fa-circle-check <?= $isCsExpired ? 'text-secondary' : 'text-success' ?> fs-6 mt-1 me-2 me-lg-3 flex-shrink-0"></i>
                                                                            <div>
                                                                                <span class="fw-semibold <?= $isCsExpired ? 'text-decoration-line-through text-muted' : 'text-dark' ?> d-block mb-1 text-break" style="font-size: 0.85rem; line-height: 1.3;"><?= esc($cs['nama_sesi']) ?></span>
                                                                                <span class="text-primary fw-bold" style="font-size: 0.75rem;">
                                                                                    <?php
                                                                                    $timestamp = strtotime($cs['waktu_mulai']);
                                                                                    $namaHari = ['Sun' => 'Minggu', 'Mon' => 'Senin', 'Tue' => 'Selasa', 'Wed' => 'Rabu', 'Thu' => 'Kamis', 'Fri' => 'Jumat', 'Sat' => 'Sabtu'][date('D', $timestamp)];
                                                                                    $namaBulan = ['January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'][date('F', $timestamp)];
                                                                                    $formattedDate = $namaHari . ', ' . date('d', $timestamp) . ' ' . $namaBulan . ' ' . date('Y, H:i', $timestamp);
                                                                                    ?>
                                                                                    <i class="far fa-calendar-alt me-1"></i> <?= $formattedDate ?> WIB
                                                                                </span>
                                                                                <?php if ($isCsExpired): ?>
                                                                                    <span class="badge bg-danger ms-1" style="font-size: 0.6rem;">Selesai</span>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </li>
                                                                    <?php endforeach; ?>
                                                                </ul>
                                                            <?php elseif ($isFree && empty($childSessionsData)): ?>
                                                                <p class="text-muted fs-8 mb-3 mb-lg-4 fst-italic"><i class="fa-solid fa-info-circle me-1"></i> Belum ada sesi tertaut.</p>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="small text-muted"><?= $sesi['deskripsi_sesi'] ?></div>
                                                    </div>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- ========================================================== -->


                    <!-- ========================================================== -->
                    <!-- BAGIAN 2: BARIS BAWAH (DATA PESERTA & PEMBAYARAN KIRI KANAN)-->
                    <!-- ========================================================== -->
                    <div class="row g-3 g-lg-4">
                        <!-- KOLOM KIRI: DATA PESERTA (col-lg-7) -->
                        <div class="col-lg-7">
                            <div class="card border-0 shadow-sm rounded-4 p-3 p-lg-4 h-100">
                                <h4 class="fw-bold mb-3 mb-lg-4 border-bottom pb-2 fs-4">2. Data Peserta</h4>

                                <?php if (!session()->get('id')): ?>
                                    <div class="alert alert-light border shadow-sm rounded-3 mb-4 p-3">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="me-2 mt-1">
                                                <i class="fa fa-info-circle text-primary fs-5"></i>
                                            </div>
                                            <span class="text-dark" style="font-size: 0.82rem; line-height: 1.4;">
                                                <strong>Informasi:</strong> Jika Anda sudah terdaftar di Kelasbrevet, silahkan login dengan Google atau akun Kelasbrevet Anda.
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column flex-sm-row gap-2">
                                            <?php if (strtolower(setting('client_status')) == 'true'): ?>
                                                <a href="<?= isset($link) ? $link : '#' ?>" class="btn btn-google-custom d-flex align-items-center justify-content-center flex-fill py-2" style="background-color: #ffffff;">
                                                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="me-2" style="width: 16px; height: 16px;">
                                                    <span class="fw-bold" style="font-size: 0.8rem;">Login Dengan Google</span>
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?= base_url('auth') ?>" class="btn btn-primary d-flex align-items-center justify-content-center flex-fill py-2">
                                                <span class="fw-bold" style="font-size: 0.8rem;">Login Dengan Akun</span>
                                            </a>
                                        </div>
                                        <div class="d-flex align-items-center my-3">
                                            <div class="border-bottom flex-grow-1"></div>
                                            <div class="px-3 text-muted fw-bold" style="font-size: 0.7rem;">ATAU ISI DATA BARU</div>
                                            <div class="border-bottom flex-grow-1"></div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold fs-7 fs-lg-6">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" value="<?= session()->get('id') && isset($siswa) ? esc($siswa['nama_siswa']) : esc(old('nama')) ?>" class="form-control form-control-md form-control-lg-lg" placeholder="Masukkan nama lengkap" required <?= session()->get('id') ? 'readonly style="cursor: not-allowed; background-color: #e9ecef;"' : '' ?>>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold fs-7 fs-lg-6">Email Aktif <span class="text-danger">*</span></label>
                                    <input type="email" name="email" value="<?= session()->get('id') && isset($siswa) ? esc($siswa['email']) : esc(old('email')) ?>" class="form-control form-control-md form-control-lg-lg" placeholder="contoh@email.com" required <?= session()->get('id') ? 'readonly style="cursor: not-allowed; background-color: #e9ecef;"' : '' ?>>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold fs-7 fs-lg-6">Nomor WhatsApp <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="hp" value="<?= session()->get('id') && isset($siswa) ? esc($siswa['hp']) : esc(old('hp')) ?>" class="form-control form-control-md form-control-lg-lg" placeholder="81234567890" pattern="[0-9]+" minlength="10" maxlength="15" required autocomplete="off" <?= session()->get('id') ? 'readonly style="cursor: not-allowed; background-color: #e9ecef;"' : '' ?>>
                                    </div>
                                </div>

                                <?php if (session()->get('id')): ?>
                                    <div class="alert alert-success border shadow-sm rounded-3 mt-3 p-3 d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fa fa-check-circle text-success fs-3"></i>
                                        </div>
                                        <span class="text-dark" style="font-size: 0.82rem; line-height: 1.4;">
                                            <strong> Anda sudah berhasil login. </strong> Silakan selesaikan pendaftaran dengan menekan tombol <strong>Daftar Sekarang</strong>.
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- KOLOM KANAN: KARTU BAYAR (col-lg-5) -->
                        <div class="col-lg-5">
                            <div class="card border-primary shadow-sm rounded-4 p-3 p-lg-4 h-100 d-flex flex-column justify-content-center bg-light" style="border-width: 2px !important;">
                                <div class="text-center px-2" style="overflow: hidden;">
                                    <p class="text-secondary fw-semibold mb-1 fs-6">Total Pembayaran Anda</p>

                                    <!-- Ukuran font disesuaikan responsif (fs-4 fs-md-3 fs-lg-2) dan text-break agar nominal tidak offside/keluar card -->
                                    <h1 class="fw-bold text-primary mb-3 mb-lg-4 fs-4 fs-md-3 fs-lg-2 text-break" id="displayTotal" style="word-break: break-word;">Rp 0</h1>

                                    <button type="submit" id="btnSubmit" class="btn btn-primary btn-lg w-100 fw-bold rounded-pill shadow-sm py-2.5 py-lg-3 fs-6" disabled>
                                        <i class="fa-solid fa-shield-halved me-2"></i> Daftar Sekarang
                                    </button>

                                    <div class="mt-3 mt-lg-4 pt-3 border-top">
                                        <small class="text-muted d-block mb-2 fw-medium" style="font-size: 0.75rem;">Pembayaran aman didukung oleh:</small>
                                        <img src="https://opd-static.midtrans.com/assethera/logo/midtrans-dark-3a5ac77cd3110b28b32cb590fc968f296d2123e686591d636bd51b276f6ed034.svg" height="22" alt="Midtrans Logo" style="opacity: 0.8;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ========================================================== -->

                </form>
            </div>
        </section>

        <!-- SECTION 2: BENEFIT & PAKET (DINAMIS) -->
        <section class="py-3 bg-light">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="fw-bold text-dark mb-3">Kenapa Anda Harus Mengikuti Webinar Ini?</h2>
                    <p class="text-secondary mx-auto" style="max-width: 600px;">Kami merancang webinar ini khusus untuk membantu Anda menguasai regulasi perpajakan terbaru di Indonesia.</p>
                </div>
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="card benefit-card p-4">
                            <div class="icon-box"><i class="fa-solid fa-book-open"></i></div>
                            <h4 class="fw-bold fs-5">Pakar Perpajakan</h4>
                            <p class="text-secondary mb-0">Webinar akan diisi oleh pakar perpajakan yang berpengalaman dan ahli di bidangnya.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card benefit-card p-4">
                            <div class="icon-box"><i class="fa-solid fa-certificate"></i></div>
                            <h4 class="fw-bold fs-5">E-Sertifikat Resmi</h4>
                            <p class="text-secondary mb-0">Dapatkan sertifikat resmi setelah menyelesaikan webinar yang dapat ditambahkan ke profil Anda.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card benefit-card p-4">
                            <div class="icon-box"><i class="fa-solid fa-user-group"></i></div>
                            <h4 class="fw-bold fs-5">Sesi Tanya Jawab</h4>
                            <p class="text-secondary mb-0">Interaksi langsung dengan pemateri dan bahas studi kasus yang relevan dengan masalah Anda.</p>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-12 col-xl-11 col-xxl-10">
                        <div class="row g-4 align-items-stretch">
                            <div class="col-md-7">
                                <div class="card border-primary border-2 rounded-4 shadow overflow-hidden h-100">
                                    <div class="row g-0 h-100">
                                        <div class="col-lg-7 p-4 bg-white d-flex flex-column justify-content-center">
                                            <h4 class="fw-bold text-primary mb-3"><?= $paketUtama ? esc($paketUtama->nama_paket) : 'Paket VIP Access' ?></h4>
                                            <ul class="list-unstyled mb-0" style="font-size: 0.9rem;">
                                                <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Akses Live Gmeet Session</li>
                                                <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> E-Sertifikat Kehadiran</li>
                                                <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> PDF Modul Materi</li>
                                                <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Rekaman Ulang Webinar</li>
                                                <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Grup Whatsapp Eksklusif Alumni</li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-5 p-4 d-flex flex-column justify-content-center align-items-center text-center" style="background-color: var(--light-blue, #e3f2fd);">
                                            <div class="mb-2"><i class="fa-solid fa-star text-warning fs-1"></i></div>
                                            <h5 class="fw-bold text-dark mb-1">Amankan Paket Pelatihan!</h5>
                                            <p class="text-muted mb-3" style="font-size: 0.8rem; line-height: 1.4;">Jangan lewatkan kesempatan belajar langsung bersama ahlinya.</p>
                                            <a href="#pendaftaran" class="btn btn-primary fw-bold text-decoration-none w-100 rounded-3 shadow-sm py-2 mb-2">Daftar Promo Terbatas!</a>
                                            <div class="text-muted" style="font-size: 0.75rem;"><i class="fa-solid fa-clock me-1"></i> Kuota pendaftaran akan segera ditutup</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="card border-0 shadow-lg h-100 rounded-4 overflow-hidden text-white" style="background: linear-gradient(145deg, #0c2b6b, #1a4ab9);">
                                    <div class="card-body p-4 d-flex flex-column">
                                        <span class="badge bg-warning text-dark align-self-start px-2 py-1 rounded-pill mb-2 fw-bold" style="font-size: 0.7rem;">
                                            <i class="fa-solid fa-star me-1"></i> SPESIAL OFFER
                                        </span>
                                        <h5 class="fw-bold mb-2 text-white">Sertifikasi Brevet Pajak A/B</h5>
                                        <p class="text-light opacity-75 mb-3" style="font-size: 0.8rem; line-height: 1.4;">Tingkatkan kompetensi dan legalitas Anda di bidang perpajakan bersama praktisi ahli.</p>
                                        <ul class="list-unstyled mb-3 mt-auto" style="font-size: 0.8rem;">
                                            <li class="mb-2 d-flex align-items-start"><i class="fa-solid fa-circle-check text-warning mt-1 me-2"></i><span class="text-light">Sebagai salah satu bukti memiliki pengetahuan/kompetensi dibidang perpajakan</span></li>
                                            <li class="mb-2 d-flex align-items-start"><i class="fa-solid fa-circle-check text-warning mt-1 me-2"></i><span class="text-light">Dapat digunakan sebagai syarat administrasi seorang kuasa saat mendampingi WP</span></li>
                                            <li class="mb-2 d-flex align-items-start"><i class="fa-solid fa-circle-check text-warning mt-1 me-2"></i><span class="text-light">Dapat digunakan sebagai syarat permohonan izin kuasa hukum di pengadilan pajak</span></li>
                                        </ul>
                                        <div class="mt-2 pt-3 border-top border-light border-opacity-25 text-center">
                                            <a href="<?= base_url('/') ?>" class="btn btn-warning w-100 py-2 fw-bold rounded-pill shadow-sm text-dark transition-all" style="font-size: 0.85rem;">
                                                Lihat Penawaran <i class="fa-solid fa-arrow-right ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 3: PENDAFTARAN (CTA) -->
        <section id="pendaftaran-cta" class="cta-section text-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <h2 class="fw-bold mb-4 display-6">Siap Untuk Meningkatkan Karir dan Bisnis Anda?</h2>
                        <p class="lead mb-5 opacity-75">Kapasitas sangat terbatas. Jangan sampai Anda kehilangan kesempatan belajar langsung dari ahlinya. Amankan kursi Anda sekarang juga!</p>
                        <a href="#pendaftaran" class="btn btn-light btn-custom btn-lg fs-5 text-primary">Daftar Sekarang <i class="fa-solid fa-arrow-right ms-2"></i></a>
                        <p class="mt-4 fs-7 opacity-75"><i class="fa-solid fa-lock me-1"></i> Pembayaran aman dan terenkripsi.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-dark text-white py-4 text-center">
        <div class="container">
            <p class="mb-0 opacity-75">&copy; 2026 Kelasbrevet. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Countdown Script -->
    <script>
        <?php
        $validTimestamps = [];
        if (!empty($daftarPaket)) {
            $db = \Config\Database::connect();
            foreach ($daftarPaket as $paket) {
                if (!empty($paket->sesi)) {
                    foreach ($paket->sesi as $sesi) {
                        $childIds = json_decode($sesi['sesi_gratis'], true) ?? [];
                        if (!empty($childIds)) {
                            $childSessions = $db->table('webinar_sesi')->whereIn('id_sesi', $childIds)->get()->getResultArray();
                            foreach ($childSessions as $cs) {
                                $validTimestamps[] = strtotime($cs['waktu_mulai']) * 1000;
                            }
                        } else {
                            if (isset($sesi['waktu_mulai'])) {
                                $validTimestamps[] = strtotime($sesi['waktu_mulai']) * 1000;
                            }
                        }
                    }
                }
            }
            $validTimestamps = array_unique($validTimestamps);
            sort($validTimestamps);
        }
        ?>

        const sessionTimes = <?= json_encode(array_values($validTimestamps)) ?>;
        const timer = setInterval(function() {
            const now = new Date().getTime();
            let targetDate = null;
            for (let i = 0; i < sessionTimes.length; i++) {
                if (sessionTimes[i] > now) {
                    targetDate = sessionTimes[i];
                    break;
                }
            }
            if (targetDate) {
                const distance = targetDate - now;
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("days").innerHTML = days < 10 ? "0" + days : days;
                document.getElementById("hours").innerHTML = hours < 10 ? "0" + hours : hours;
                document.getElementById("minutes").innerHTML = minutes < 10 ? "0" + minutes : minutes;
                document.getElementById("seconds").innerHTML = seconds < 10 ? "0" + seconds : seconds;
            } else {
                clearInterval(timer);
                document.getElementById("days").innerHTML = "00";
                document.getElementById("hours").innerHTML = "00";
                document.getElementById("minutes").innerHTML = "00";
                document.getElementById("seconds").innerHTML = "00";
            }
        }, 1000);
    </script>

    <!-- SCRIPT PENGHITUNG HARGA OTOMATIS & PENGISI ID PAKET -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const checkboxes = document.querySelectorAll('.calculate-price');
            const displayTotal = document.getElementById('displayTotal');
            const btnSubmit = document.getElementById('btnSubmit');
            const inputIdPaket = document.getElementById('input_idpaket');

            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(angka);
            }

            function calculateTotal() {
                let total = 0;
                let isChecked = false;

                checkboxes.forEach(function(cb) {
                    if (cb.checked && !cb.disabled) {
                        total += parseInt(cb.getAttribute('data-price'));
                        isChecked = true;
                        // Mengisi value input idpaket saat sesi dari paket tertentu dipilih
                        inputIdPaket.value = cb.getAttribute('data-idpaket');
                    }
                });

                displayTotal.innerText = formatRupiah(total);

                if (isChecked) {
                    btnSubmit.removeAttribute('disabled');
                    btnSubmit.classList.remove('btn-secondary');
                    btnSubmit.classList.add('btn-primary');
                } else {
                    btnSubmit.setAttribute('disabled', 'true');
                    btnSubmit.classList.remove('btn-primary');
                    btnSubmit.classList.add('btn-secondary');
                    inputIdPaket.value = ""; // Reset jika tidak ada yg dipilih
                }
            }

            checkboxes.forEach(function(cb) {
                cb.addEventListener('change', calculateTotal);
            });

            calculateTotal();
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("formWebinar");
            if (!form) return;

            const btnSubmit = document.getElementById("btnSubmit");
            const inputs = form.querySelectorAll("input[name='nama'], input[name='email'], input[name='hp']");
            const radios = form.querySelectorAll(".session-checkbox");

            function validateForm() {
                let isPackageSelected = Array.from(radios).some(radio => radio.checked && !radio.disabled);
                let areInputsFilled = Array.from(inputs).every(input => {
                    if (input.readOnly) return true;
                    return input.value.trim() !== "";
                });

                let isHpValid = true;
                const hpInput = form.querySelector("input[name='hp']");
                if (hpInput && !hpInput.readOnly) {
                    let hpLength = hpInput.value.trim().length;
                    isHpValid = (hpLength >= 10 && hpLength <= 15);
                }

                if (isPackageSelected && areInputsFilled && isHpValid) {
                    btnSubmit.removeAttribute("disabled");
                } else {
                    btnSubmit.setAttribute("disabled", "true");
                }
            }

            const hpInput = form.querySelector("input[name='hp']");
            if (hpInput && !hpInput.readOnly) {
                hpInput.addEventListener("input", function() {
                    if (this.value.length > 15) {
                        this.value = this.value.slice(0, 15);
                    }
                    validateForm();
                });
            }

            form.addEventListener("input", validateForm);
            form.addEventListener("change", validateForm);
            validateForm();
        });
    </script>
    <script src="https://topcs.id/widget.js" data-tenant="kelas-brevet" data-mode="bubble" data-position="right" data-color="#2563eb"></script>

    <!-- SweetAlert2 CDN & Notifikasi Logic -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (session()->getFlashdata('success_webinar')) : ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Pendaftaran Berhasil!',
                    html: `
                        <p class="text-gray-700 fs-6 mb-3"><?= json_encode(session()->getFlashdata('success_webinar')) ?></p>
                        <div class="p-3 bg-light-success border border-success border-dashed rounded-4 text-center">
                            <span class="d-block fw-bold text-dark fs-6 mb-1">🎉 Langkah Terakhir!</span>
                            <p class="text-muted fs-7 mb-3" style="font-size: 0.85rem; line-height: 1.4;">Silakan bergabung ke Grup WhatsApp resmi untuk mendapatkan link akses gmeet dan koordinasi selanjutnya.</p>
                            
                            <!-- PERUBAHAN DI SINI: Ukuran tombol disesuaikan, padding diperkecil, dan ukuran font lebih pas -->
                            <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                                <a href="https://chat.whatsapp.com/CHWkmrWMqrSJvJNlu1UoVy?s=cl&p=i&mlu=4" target="_blank" class="btn btn-success fw-bold px-3 py-2 shadow-sm d-inline-flex align-items-center justify-content-center gap-2 flex-fill text-start" style="font-size: 0.8rem; border-radius: 8px;">
                                    <i class="fa-brands fa-whatsapp fs-4 flex-shrink-0"></i>
                                    <span style="line-height: 1.2;">Grup Update Perpajakan</span>
                                </a>
                                <a href="https://chat.whatsapp.com/JejzQkQMV0GCo9ANGko11m?s=cl&p=i&mlu=4" target="_blank" class="btn btn-success fw-bold px-3 py-2 shadow-sm d-inline-flex align-items-center justify-content-center gap-2 flex-fill text-start" style="font-size: 0.8rem; border-radius: 8px;">
                                    <i class="fa-brands fa-whatsapp fs-4 flex-shrink-0"></i>
                                    <span style="line-height: 1.2;">Grup ALC & KelasBrevet</span>
                                </a>
                            </div>
                        </div>
                    `,
                    confirmButtonColor: '#0d6efd',
                    confirmButtonText: 'Tutup / Nanti Saja',
                    customClass: {
                        popup: 'rounded-4 p-4'
                    }
                });
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: <?= json_encode(session()->getFlashdata('error')) ?>,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Tutup'
                });
            <?php endif; ?>
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const btnSubmit = document.getElementById('btnSubmit');
            if (btnSubmit) {
                const form = btnSubmit.closest('form');
                if (form) {
                    form.addEventListener('submit', function() {
                        btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Sedang Memproses...';
                        btnSubmit.disabled = true;
                        btnSubmit.classList.add('disabled');
                        btnSubmit.style.pointerEvents = 'none';
                    });
                }
            }
        });
    </script>
</body>

</html>
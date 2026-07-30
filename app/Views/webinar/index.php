<?php
// Mengambil data paket pertama dari array yang dikirim Controller
$paketWebinar = !empty($katalog_webinar) ? $katalog_webinar[0] : null;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Meta Tags -->
    <title>Webinar Eksklusif: Strategi Transformasi Bisnis Digital 2026</title>
    <meta name="description" content="Ikuti webinar eksklusif kami dan pelajari strategi transformasi bisnis digital terbaik. Dapatkan e-sertifikat, materi lengkap, dan sesi mentoring.">
    <meta name="keywords" content="webinar bisnis, transformasi digital, belajar bisnis, kelas online, webinar 2026">
    <meta name="author" content="Nama Perusahaan/Penyelenggara Anda">
    <meta name="robots" content="index, follow">

    <!-- Open Graph (Untuk Social Media/WhatsApp Sharing) -->
    <meta property="og:title" content="Webinar Eksklusif: Strategi Transformasi Bisnis Digital">
    <meta property="og:description" content="Ikuti webinar eksklusif kami dan pelajari strategi transformasi digital. Daftar sekarang!">
    <meta property="og:image" content="URL_GAMBAR_THUMBNAIL_ANDA.jpg">
    <meta property="og:url" content="https://www.websiteanda.com">

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
            padding: 80px 0;
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
</head>

<body>

    <!-- Header / Navbar -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary" href="#">
                    <i class="fa-solid fa-graduation-cap me-2"></i>WebinarPro
                </a>
            </div>
        </nav>
    </header>

    <main>
        <!-- SECTION 1: HERO & COUNTDOWN -->
        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center flex-column-reverse flex-lg-row">
                    <!-- Teks Kiri -->
                    <div class="col-lg-6 mt-5 mt-lg-0">
                        <span class="badge bg-primary px-3 py-2 rounded-pill mb-3">Live Masterclass 2026</span>
                        <h1 class="hero-title mb-4">Strategi Jitu Transformasi Bisnis di Era Kecerdasan Buatan</h1>
                        <p class="lead text-secondary mb-4">
                            Pelajari langkah demi langkah bagaimana mengamankan posisi bisnis Anda di masa depan. Dibawakan langsung oleh praktisi industri terkemuka.
                        </p>

                        <!-- Area Countdown -->
                        <div class="mb-5">
                            <h5 class="fw-bold mb-3 text-dark">Webinar Dimulai Dalam:</h5>
                            <div class="d-flex gap-3">
                                <div class="countdown-box">
                                    <span class="countdown-number" id="days">00</span>
                                    <span class="countdown-label">Hari</span>
                                </div>
                                <div class="countdown-box">
                                    <span class="countdown-number" id="hours">00</span>
                                    <span class="countdown-label">Jam</span>
                                </div>
                                <div class="countdown-box">
                                    <span class="countdown-number" id="minutes">00</span>
                                    <span class="countdown-label">Menit</span>
                                </div>
                                <div class="countdown-box">
                                    <span class="countdown-number" id="seconds">00</span>
                                    <span class="countdown-label">Detik</span>
                                </div>
                            </div>
                        </div>

                        <a href="#pendaftaran" class="btn btn-primary-custom btn-custom btn-lg w-100 w-sm-auto">
                            <i class="fa-solid fa-ticket me-2"></i> Amankan Tiket Saya
                        </a>
                    </div>

                    <!-- Gambar Kanan -->
                    <div class="col-lg-6 text-center">
                        <!-- Ganti URL src di bawah dengan URL gambar kegiatan/webinar Anda -->
                        <img src="https://images.unsplash.com/photo-1591115765373-5207764f72e7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                            alt="Suasana Webinar Transformasi Digital"
                            class="img-fluid rounded-4 shadow-lg border border-3 border-white">
                    </div>
                </div>
            </div>
        </section>

        <!-- AREA PENDAFTARAN & PILIH PAKET -->
        <section id="pendaftaran" class="py-5 bg-light">
            <div class="container py-5">

                <!-- Pesan Notifikasi Controller (Ditambahkan agar Error/Success muncul) -->
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger mb-4"><b>Gagal:</b> <?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success mb-4"><b>Sukses:</b> <?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <div class="text-center mb-5">
                    <span class="badge bg-primary px-3 py-2 rounded-pill mb-2">Formulir Pendaftaran</span>
                    <h2 class="fw-bold text-dark">Pilih Sesi & Amankan Tiket Anda</h2>
                    <p class="text-secondary">Anda dapat membeli satuan per sesi atau langsung mengambil full paket untuk pengalaman maksimal.</p>
                </div>

                <!-- Form mengarah ke Controller CI4 yang akan memproses Midtrans -->
                <form action="<?= base_url('webinar/daftar') ?>" method="POST" id="formWebinar">
                    <?= csrf_field() ?>
                    <!-- Hidden input untuk ID Paket Induk -->
                    <input type="hidden" name="idpaket" value="<?= $paketWebinar->idpaket ?>">

                    <div class="row g-4 justify-content-center">
                        <!-- KOLOM KIRI: Data Diri -->
                        <div class="col-lg-5">
                            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                                <h4 class="fw-bold mb-4 border-bottom pb-2">Data Peserta</h4>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" value="<?= session()->get('id') && isset($siswa) ? esc($siswa['nama_siswa']) : esc(old('nama')) ?>" class="form-control form-control-lg" placeholder="Masukkan nama lengkap" required <?= session()->get('id') ? 'readonly style="cursor: not-allowed; background-color: #e9ecef;"' : '' ?>>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" value="<?= session()->get('id') && isset($siswa) ? esc($siswa['email']) : esc(old('email')) ?>" class="form-control form-control-lg" placeholder="contoh@email.com" required <?= session()->get('id') ? 'readonly style="cursor: not-allowed; background-color: #e9ecef;"' : '' ?>>
                                    <small class="text-muted">Akses zoom dan materi akan dikirim ke email ini.</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nomor WhatsApp <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="tel" name="hp" value="<?= session()->get('id') && isset($siswa) ? esc($siswa['hp']) : esc(old('hp')) ?>" class="form-control form-control-lg" placeholder="81234567890" pattern="[0-9]+" minlength="9" maxlength="15" required autocomplete="off" <?= session()->get('id') ? 'readonly style="cursor: not-allowed; background-color: #e9ecef;"' : '' ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- KOLOM KANAN: Pilihan Sesi & Total Bayar -->
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm rounded-4 p-4">
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                    <h4 class="fw-bold mb-0">Pilih Sesi</h4>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" id="btnSelectAll">Pilih Semua Sesi</button>
                                </div>

                                <!-- Daftar Sesi (Di-loop dari database) -->
                                <div class="d-flex flex-column gap-3 mb-4">
                                    <?php if ($paketWebinar && !empty($paketWebinar->sesi)) : ?>
                                        <?php
                                        $currentDateTime = date('Y-m-d H:i:s');
                                        foreach ($paketWebinar->sesi as $sesi) :
                                            $isExpired = (strtotime($sesi['waktu_mulai']) <= strtotime($currentDateTime));
                                            $isFree = ($sesi['harga_sesi'] <= 0); // Pengecekan harga 0
                                        ?>
                                            <!-- Looping Sesi -->
                                            <label class="w-100 m-0 <?= $isExpired ? 'opacity-50' : '' ?>" <?= $isExpired ? 'style="cursor: not-allowed;"' : ($isFree ? 'style="cursor: default;"' : '') ?>>
                                                <input type="checkbox" name="id_sesi[]" value="<?= esc($sesi['id_sesi']) ?>" class="session-checkbox calculate-price" data-price="<?= round($sesi['harga_sesi']) ?>" <?= $isExpired ? 'disabled' : '' ?> <?= $isFree ? 'checked onclick="return false;"' : '' ?>>
                                                <div class="session-card p-3 d-flex align-items-center justify-content-between <?= $isExpired ? 'bg-light' : '' ?>">
                                                    <div>
                                                        <h6 class="fw-bold mb-1"><?= esc($sesi['nama_sesi']) ?></h6>
                                                        <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> <?= date('d M Y, H:i', strtotime($sesi['waktu_mulai'])) ?> WIB</small>
                                                        <?php if ($isExpired): ?>
                                                            <span class="badge bg-danger ms-2" style="font-size: 0.65rem;">Sesi Telah Dimulai/Berakhir</span>
                                                        <?php elseif ($isFree): ?>
                                                            <span class="badge bg-success ms-2" style="font-size: 0.65rem;">Gratis (Otomatis Terpilih)</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="fw-bold <?= $isExpired ? 'text-muted text-decoration-line-through' : 'text-dark' ?> d-block">
                                                            <?= $isFree ? 'Gratis' : 'Rp ' . number_format($sesi['harga_sesi'], 0, ',', '.') ?>
                                                        </span>
                                                        <i class="fa-solid fa-circle-check fs-4 check-icon <?= $isExpired ? 'text-secondary' : ($isFree ? 'text-primary' : 'text-muted') ?>"></i>
                                                    </div>
                                                </div>
                                            </label>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <div class="alert alert-warning border-dashed">Sesi belum tersedia untuk pendaftaran saat ini.</div>
                                    <?php endif; ?>
                                </div>

                                <!-- Area Rincian Bayar & Tombol Midtrans -->
                                <div class="bg-light p-4 rounded-3 text-center border">
                                    <p class="text-secondary mb-1">Total yang harus dibayar:</p>
                                    <h2 class="fw-bold text-primary mb-3" id="displayTotal">Rp 0</h2>

                                    <button type="submit" id="btnSubmit" class="btn btn-primary btn-lg w-100 fw-bold rounded-pill shadow" disabled>
                                        <i class="fa-solid fa-shield-halved me-2"></i> Daftar & Lanjut Bayar
                                    </button>
                                    <div class="mt-3">
                                        <small class="text-muted d-block mb-2">Pembayaran aman didukung oleh:</small>
                                        <!-- Logo Midtrans Dummy -->
                                        <img src="https://midtrans.com/assets/img/midtrans-logo.svg" height="25" alt="Midtrans Logo" style="opacity: 0.7;">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <!-- SECTION 2: BENEFIT & PAKET -->
        <section class="py-5 bg-light">
            <div class="container py-4">
                <div class="text-center mb-5">
                    <h2 class="fw-bold text-dark mb-3">Kenapa Anda Harus Mengikuti Webinar Ini?</h2>
                    <p class="text-secondary mx-auto" style="max-width: 600px;">Kami merancang materi ini khusus untuk membantu Anda menguasai keahlian yang paling relevan dengan kondisi industri saat ini.</p>
                </div>

                <!-- Grid Benefit -->
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="card benefit-card p-4">
                            <div class="icon-box">
                                <i class="fa-solid fa-book-open"></i>
                            </div>
                            <h4 class="fw-bold fs-5">Materi Terstruktur</h4>
                            <p class="text-secondary mb-0">Modul pembelajaran yang dirancang step-by-step agar mudah dipahami bahkan untuk pemula.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card benefit-card p-4">
                            <div class="icon-box">
                                <i class="fa-solid fa-certificate"></i>
                            </div>
                            <h4 class="fw-bold fs-5">E-Sertifikat Resmi</h4>
                            <p class="text-secondary mb-0">Dapatkan sertifikat resmi setelah menyelesaikan webinar yang dapat ditambahkan ke profil LinkedIn Anda.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card benefit-card p-4">
                            <div class="icon-box">
                                <i class="fa-solid fa-user-group"></i>
                            </div>
                            <h4 class="fw-bold fs-5">Sesi Tanya Jawab</h4>
                            <p class="text-secondary mb-0">Interaksi langsung dengan pemateri dan bahas studi kasus yang relevan dengan masalah Anda.</p>
                        </div>
                    </div>
                </div>

                <!-- Info Paket -->
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card border-primary border-2 rounded-4 shadow overflow-hidden">
                            <div class="row g-0">
                                <div class="col-md-7 p-4 p-md-5 bg-white">
                                    <h3 class="fw-bold text-primary mb-3">Paket VIP Access</h3>
                                    <ul class="list-unstyled mb-4">
                                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Akses Live Zoom Session</li>
                                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> E-Sertifikat Kehadiran</li>
                                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> PDF Modul Materi (Downloadable)</li>
                                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Rekaman Ulang Webinar Seumur Hidup</li>
                                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Grup Telegram Eksklusif Alumni</li>
                                    </ul>
                                </div>
                                <div class="col-md-5 p-4 p-md-5 d-flex flex-column justify-content-center align-items-center" style="background-color: var(--light-blue);">
                                    <p class="text-decoration-line-through text-muted mb-1 fs-5">Rp 499.000</p>
                                    <h2 class="fw-bold text-dark mb-1">Rp 149.000</h2>
                                    <p class="text-primary fw-semibold mb-0">Promo Terbatas!</p>
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
                        <p class="lead mb-5 opacity-75">Kapasitas Zoom sangat terbatas. Jangan sampai Anda kehilangan kesempatan belajar langsung dari ahlinya. Amankan kursi Anda sekarang juga!</p>

                        <!-- Ganti URL href dengan link pendaftaran Google Form / WhatsApp / Sistem Anda -->
                        <a href="https://wa.me/6281234567890?text=Halo%20saya%20ingin%20mendaftar%20Webinar" target="_blank" class="btn btn-light btn-custom btn-lg fs-5 text-primary">
                            Daftar Sekarang <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                        <p class="mt-4 fs-7 opacity-75"><i class="fa-solid fa-lock me-1"></i> Pembayaran aman dan terenkripsi.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 text-center">
        <div class="container">
            <p class="mb-0 opacity-75">&copy; 2026 WebinarPro. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Countdown Script -->
    <script>
        // Atur Tanggal Webinar Di Sini (Format: Year, Month (0-11), Day, Hour, Minute, Second)
        // Bulan dimulai dari 0 (0 = Januari, 1 = Februari, ..., 7 = Agustus)
        // Contoh: 15 Agustus 2026 jam 19:00
        const webinarDate = new Date(2026, 7, 15, 19, 0, 0).getTime();

        const timer = setInterval(function() {
            const now = new Date().getTime();
            const distance = webinarDate - now;

            // Kalkulasi waktu
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Menampilkan hasil dengan tambahan angka '0' di depan jika di bawah 10
            document.getElementById("days").innerHTML = days < 10 ? "0" + days : days;
            document.getElementById("hours").innerHTML = hours < 10 ? "0" + hours : hours;
            document.getElementById("minutes").innerHTML = minutes < 10 ? "0" + minutes : minutes;
            document.getElementById("seconds").innerHTML = seconds < 10 ? "0" + seconds : seconds;

            // Jika hitung mundur selesai
            if (distance < 0) {
                clearInterval(timer);
                document.getElementById("days").innerHTML = "00";
                document.getElementById("hours").innerHTML = "00";
                document.getElementById("minutes").innerHTML = "00";
                document.getElementById("seconds").innerHTML = "00";
            }
        }, 1000); // Diperbarui setiap 1 detik
    </script>
    <!-- SCRIPT PENGHITUNG HARGA OTOMATIS -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const checkboxes = document.querySelectorAll('.calculate-price');
            const displayTotal = document.getElementById('displayTotal');
            const btnSubmit = document.getElementById('btnSubmit');
            const btnSelectAll = document.getElementById('btnSelectAll');

            // Fungsi Format Rupiah
            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(angka);
            }

            // Fungsi Hitung Total
            function calculateTotal() {
                let total = 0;
                let checkedCount = 0;
                let activeCount = 0; // Tambahan: Menghitung total checkbox yang aktif/bisa dipilih

                checkboxes.forEach(function(cb) {
                    if (!cb.disabled) {
                        activeCount++; // Hanya hitung sesi yang belum kadaluarsa
                    }

                    if (cb.checked) {
                        total += parseInt(cb.getAttribute('data-price'));
                        checkedCount++;
                    }
                });

                // Update UI Total Harga
                displayTotal.innerText = formatRupiah(total);

                // Disable tombol bayar jika tidak ada sesi yang dipilih
                if (checkedCount > 0) {
                    btnSubmit.removeAttribute('disabled');
                    btnSubmit.classList.remove('btn-secondary');
                    btnSubmit.classList.add('btn-primary');
                } else {
                    btnSubmit.setAttribute('disabled', 'true');
                    btnSubmit.classList.remove('btn-primary');
                    btnSubmit.classList.add('btn-secondary');
                }

                // Update teks tombol Select All (Bandingkan jumlah centang dengan jumlah sesi yang aktif saja)
                if (checkedCount === activeCount && activeCount > 0) {
                    btnSelectAll.innerText = "Batalkan Pilihan";
                    btnSelectAll.classList.replace('btn-outline-primary', 'btn-danger');
                } else {
                    btnSelectAll.innerText = "Pilih Semua Sesi";
                    btnSelectAll.classList.replace('btn-danger', 'btn-outline-primary');
                }
            }

            // Event Listener tiap checkbox
            checkboxes.forEach(function(cb) {
                cb.addEventListener('change', calculateTotal);
            });

            // Event Listener tombol Pilih Semua Sesi
            if (btnSelectAll) {
                btnSelectAll.addEventListener('click', function() {
                    let allChecked = true;

                    // Cek apakah semua sesi YANG AKTIF sudah tercentang
                    checkboxes.forEach(function(cb) {
                        if (!cb.disabled && !cb.checked) {
                            allChecked = false;
                        }
                    });

                    // Jika semua sudah tercentang, hilangkan centang semua. Jika belum, centang semua (HANYA SESI AKTIF).
                    checkboxes.forEach(function(cb) {
                        if (!cb.disabled) {
                            cb.checked = !allChecked;
                        }
                    });

                    calculateTotal();
                });
            }
        });
    </script>
</body>

</html>
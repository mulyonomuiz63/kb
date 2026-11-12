<?= $this->extend('siswa/template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid mt-4">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid py-4">

            <!-- ACTION BAR / TOP NAV -->
            <div class="card shadow-sm border-0 mb-8 no-print">
                <div class="card-body p-6">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-4">
                        <div class="d-flex align-items-center gap-3">
                            <a href="<?= base_url('sw-siswa/affiliate') ?>" class="btn btn-icon btn-light-primary rounded-circle" data-bs-toggle="tooltip" title="Kembali ke Dashboard">
                                <i class="ki-outline ki-arrow-left fs-2"></i>
                            </a>
                            <div>
                                <h2 class="fs-2 fw-bolder text-gray-900 m-0 d-flex align-items-center gap-2">
                                    Kartu Anggota Affiliate Resmi
                                    <span class="badge badge-light-success fs-8 fw-bold">Verified Partner</span>
                                </h2>
                                <span class="text-muted fs-7 fw-semibold">Kartu identitas resmi mitra Kelas Brevet. Siap dicetak (Depan & Belakang) atau disimpan sebagai PDF.</span>
                            </div>
                        </div>

                        <!-- Print Action Buttons -->
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <button type="button" class="btn btn-sm btn-light-primary fw-bold" onclick="printCard('front')">
                                <i class="ki-outline ki-printer fs-4 me-1"></i> Cetak Sisi Depan
                            </button>
                            <button type="button" class="btn btn-sm btn-light-primary fw-bold" onclick="printCard('back')">
                                <i class="ki-outline ki-printer fs-4 me-1"></i> Cetak Sisi Belakang
                            </button>
                            <button type="button" class="btn btn-sm btn-primary fw-bold shadow-sm" onclick="printCard('both')">
                                <i class="ki-outline ki-printer fs-3 me-1"></i> Cetak Lengkap (Depan & Belakang)
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PRINTABLE CARD STAGE -->
            <div class="card-print-stage mb-8">
                <div class="row g-6 justify-content-center">

                    <!-- ========================================== -->
                    <!-- SISI DEPAN (FRONT SIDE) -->
                    <!-- ========================================== -->
                    <div class="col-12 col-xl-6 d-flex flex-column align-items-center side-wrapper" id="wrapper-front">
                        <div class="d-flex justify-content-between align-items-center w-100 max-w-card mb-2 no-print">
                            <span class="fw-bolder fs-6 text-gray-800 d-flex align-items-center gap-2">
                                <i class="ki-outline ki-element-plus text-primary fs-4"></i> Sisi Depan (Front)
                            </span>
                            <span class="badge badge-light-primary fs-8 fw-semibold">Standard CR80 (85.6 x 54 mm)</span>
                        </div>

                        <div class="id-card id-card-front shadow-lg">
                            <!-- Background Guilloche Lines & Shimmer -->
                            <div class="card-overlay-mesh"></div>
                            <div class="card-glow-orb orb-1"></div>
                            <div class="card-glow-orb orb-2"></div>

                            <!-- Inner Frame -->
                            <div class="card-inner d-flex flex-column justify-content-between h-100 position-relative">
                                
                                <!-- Card Header -->
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="<?= base_url('assets-landing/images/logo-putih.png') ?>" alt="Kelas Brevet" class="brand-logo" onerror="this.style.display='none'; document.getElementById('text-brand-alt-front').style.display='block';">
                                        <div id="text-brand-alt-front" style="display:none;">
                                            <span class="text-white fw-bolder fs-5 tracking-wide">KELAS BREVET</span>
                                        </div>
                                        <div class="border-start border-white border-opacity-25 ps-3 py-1">
                                            <div class="text-gold fw-bold text-uppercase fs-9 letter-spacing-1">Affiliate Partner</div>
                                            <div class="text-white text-opacity-75 fs-10 fw-normal">Lembaga Kursus & Pelatihan Resmi</div>
                                        </div>
                                    </div>

                                    <!-- Hologram Badge -->
                                    <div class="hologram-badge">
                                        <div class="holo-inner">
                                            <i class="ki-outline ki-shield-tick text-gold fs-6"></i>
                                            <span class="holo-text">OFFICIAL</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Center / Body -->
                                <div class="d-flex align-items-center justify-content-between my-auto py-2">
                                    <!-- Left: Photo & Identification -->
                                    <div class="d-flex align-items-center gap-4">
                                        <div class="position-relative member-photo-wrapper flex-shrink-0">
                                            <img src="<?= $avatarUrl ?>" alt="<?= esc($siswa->nama_siswa) ?>" class="member-photo">
                                            <span class="verified-tick" title="Verified Affiliate">
                                                <i class="ki-outline ki-check text-white fs-8"></i>
                                            </span>
                                        </div>

                                        <div class="d-flex flex-column">
                                            <span class="text-gold fs-10 fw-bold letter-spacing-1 mb-1">MITRA RESMI</span>
                                            <div class="member-name text-truncate" title="<?= esc($siswa->nama_siswa) ?>">
                                                <?= strtoupper(esc($siswa->nama_siswa)) ?>
                                            </div>
                                            <div class="d-flex align-items-center gap-2 mt-2">
                                                <span class="code-badge"><?= $memberId ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right: QR Code for Verification -->
                                    <div class="qr-container text-center flex-shrink-0 ms-2">
                                        <img src="<?= $qrDataUri ?>" alt="QR Code Referral" class="qr-code-img">
                                        <span class="qr-label">SCAN LINK</span>
                                    </div>
                                </div>

                                <!-- Card Footer -->
                                <div class="pt-2 border-top border-white border-opacity-10 mt-auto">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-white text-opacity-65 fs-10 d-flex align-items-center gap-1">
                                            <i class="ki-outline ki-verify fs-7 text-gold"></i>
                                            TERDAFTAR RESMI LKP KELAS BREVET
                                        </div>
                                        <div class="text-end">
                                            <span class="badge-status-gold">TERVERIFIKASI</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Card Description -->
                        <div class="mt-3 text-center no-print">
                            <span class="text-muted fs-8">Sisi depan menampilkan identitas, foto profil, dan QR Code referral.</span>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- SISI BELAKANG (BACK SIDE) -->
                    <!-- ========================================== -->
                    <div class="col-12 col-xl-6 d-flex flex-column align-items-center side-wrapper" id="wrapper-back">
                        <div class="d-flex justify-content-between align-items-center w-100 max-w-card mb-2 no-print">
                            <span class="fw-bolder fs-6 text-gray-800 d-flex align-items-center gap-2">
                                <i class="ki-outline ki-element-plus text-primary fs-4"></i> Sisi Belakang (Back)
                            </span>
                            <span class="badge badge-light-success fs-8 fw-semibold">Official Terms & Branding</span>
                        </div>

                        <div class="id-card id-card-back shadow-lg">
                            <!-- Background Guilloche Lines -->
                            <div class="card-overlay-mesh"></div>
                            <div class="card-glow-orb orb-1"></div>
                            <div class="card-glow-orb orb-2"></div>

                            <!-- Back Content Container -->
                            <div class="card-inner d-flex flex-column justify-content-between h-100 position-relative">
                                
                                <!-- Back Header: Logo Kelas Brevet -->
                                <div class="d-flex align-items-center justify-content-between pb-2 border-bottom border-white border-opacity-15 mb-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="<?= base_url('assets-landing/images/logo-putih.png') ?>" alt="Kelas Brevet" class="brand-logo-back" onerror="this.style.display='none'; document.getElementById('text-brand-alt-back').style.display='block';">
                                        <div id="text-brand-alt-back" style="display:none;">
                                            <span class="text-white fw-bolder fs-5 tracking-wide">KELAS BREVET</span>
                                        </div>
                                        <div class="border-start border-white border-opacity-25 ps-3 py-1">
                                            <div class="text-gold fw-bold text-uppercase fs-9 letter-spacing-1">Program Kemitraan Affiliate</div>
                                            <div class="text-white text-opacity-75 fs-10 fw-normal">Lembaga Kursus & Pelatihan Resmi</div>
                                        </div>
                                    </div>
                                    <div class="hologram-badge">
                                        <div class="holo-inner">
                                            <span class="holo-text">RESMI</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Terms & Conditions (Peraturan) -->
                                <div class="terms-box my-auto">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="ki-outline ki-shield-tick text-gold fs-5"></i>
                                        <span class="text-gold fw-bold fs-9 letter-spacing-1 text-uppercase">Ketentuan Penggunaan Kartu Anggota</span>
                                    </div>
                                    <ol class="terms-list m-0 p-0 ps-3 fs-9 text-white text-opacity-90">
                                        <li>Kartu ini adalah bukti keanggotaan sah mitra resmi program affiliate Kelas Brevet.</li>
                                        <li>Perhitungan komisi referral terikat secara otomatis pada Kode Affiliate dan QR Code terdaftar.</li>
                                        <li>Dilarang memindahtangankan akun atau melakukan spamming di luar kode etik promosi.</li>
                                        <li>Kartu ini diterbitkan oleh manajemen Kelas Brevet dan berlaku selama status affiliate aktif.</li>
                                    </ol>
                                </div>

                                <!-- Official Footer -->
                                <div class="pt-2 border-top border-white border-opacity-15 mt-auto">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex flex-column">
                                            <span class="text-white text-opacity-90 fs-10 fw-bold">www.kelasbrevet.id</span>
                                            <span class="text-white text-opacity-60 fs-10">LKP Kelas Brevet Indonesia</span>
                                        </div>
                                        <div class="text-end">
                                            <span class="text-gold fs-10 fw-bold">Hak Cipta Dilindungi Undang-Undang</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Card Description -->
                        <div class="mt-3 text-center no-print">
                            <span class="text-muted fs-8">Sisi belakang memuat logo resmi dan ketentuan keanggotaan mitra.</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- PRINT INSTRUCTION & TIPS CARD -->
            <div class="card shadow-sm border-0 bg-light-primary mb-8 no-print">
                <div class="card-body p-6">
                    <div class="d-flex align-items-start gap-4">
                        <div class="w-50px h-50px rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0">
                            <i class="ki-outline ki-information-4 text-primary fs-2x"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-gray-900 mb-2">Petunjuk & Tips Pencetakan Kartu Fisik (PVC / Kertas)</h4>
                            <div class="row g-3 fs-7 text-gray-700">
                                <div class="col-md-4">
                                    <strong class="text-dark d-block mb-1">1. Rekomendasi Kertas:</strong>
                                    Gunakan kertas <strong>PVC Card Khusus ID Card</strong> (seperti kartu ATM), atau kertas <strong>Art Paper / Glossy Photo Paper 260 - 310 gsm</strong> dengan laminasi glossy/doff.
                                </div>
                                <div class="col-md-4">
                                    <strong class="text-dark d-block mb-1">2. Pengaturan Print:</strong>
                                    Di menu cetak browser, pastikan centang opsi <strong>"Background Graphics" (Grafik Latar Belakang)</strong> aktif agar warna gelap elegan dan efek emas tercetak penuh.
                                </div>
                                <div class="col-md-4">
                                    <strong class="text-dark d-block mb-1">3. Skala Cetak (Scale):</strong>
                                    Pilih skala <strong>100% (Actual Size)</strong>. Kartu sudah disesuaikan dengan rasio baku kartu ATM/KTP (85.6 mm x 54 mm).
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ======================================================= -->
<!-- STYLES: LUXURY DARK NAVY & GOLD ID CARD + PRINT CSS     -->
<!-- ======================================================= -->
<style>
    :root {
        --card-gold: #e5b95c;
        --card-gold-light: #fef08a;
        --card-gold-dark: #b48324;
        --card-navy-deep: #070e1e;
        --card-navy-mid: #0d1b2a;
        --card-navy-surface: #1b263b;
    }

    .max-w-card {
        max-width: 480px;
    }

    /* CARD CONTAINER (CR80 Standard Ratio: ~1.586) */
    .id-card {
        width: 100%;
        max-width: 480px;
        height: 300px;
        border-radius: 18px;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, var(--card-navy-deep) 0%, var(--card-navy-mid) 50%, var(--card-navy-surface) 100%);
        border: 1.5px solid rgba(229, 185, 92, 0.45);
        color: #ffffff;
        box-shadow: 0 15px 35px rgba(7, 14, 30, 0.35), 0 5px 15px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .id-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(7, 14, 30, 0.45), 0 0 25px rgba(229, 185, 92, 0.2);
    }

    /* GUILLOCHE MESH OVERLAY */
    .card-overlay-mesh {
        position: absolute;
        inset: 0;
        background-image: 
            radial-gradient(circle at 10% 20%, rgba(229, 185, 92, 0.08) 0%, transparent 20%),
            radial-gradient(circle at 90% 80%, rgba(59, 130, 246, 0.1) 0%, transparent 25%),
            repeating-linear-gradient(45deg, rgba(255, 255, 255, 0.015) 0px, rgba(255, 255, 255, 0.015) 2px, transparent 2px, transparent 6px);
        pointer-events: none;
        z-index: 1;
    }

    .card-glow-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(40px);
        pointer-events: none;
        z-index: 0;
    }
    .orb-1 {
        width: 140px;
        height: 140px;
        background: rgba(229, 185, 92, 0.12);
        top: -30px;
        right: -30px;
    }
    .orb-2 {
        width: 160px;
        height: 160px;
        background: rgba(30, 64, 175, 0.18);
        bottom: -40px;
        left: -30px;
    }

    .card-inner {
        padding: 22px 24px;
        position: relative;
        z-index: 2;
    }

    /* TEXT UTILITIES */
    .text-gold {
        color: var(--card-gold) !important;
    }
    .letter-spacing-1 {
        letter-spacing: 1.5px;
    }
    .fs-9 {
        font-size: 0.74rem;
        line-height: 1.4;
    }
    .fs-10 {
        font-size: 0.65rem;
        line-height: 1.35;
    }

    /* BRAND LOGO */
    .brand-logo {
        height: 32px;
        width: auto;
        object-fit: contain;
    }
    .brand-logo-back {
        height: 30px;
        width: auto;
        object-fit: contain;
    }

    /* HOLOGRAM EMBLEM */
    .hologram-badge {
        background: linear-gradient(135deg, rgba(229, 185, 92, 0.25), rgba(255, 255, 255, 0.35), rgba(229, 185, 92, 0.25));
        border: 1px solid rgba(229, 185, 92, 0.6);
        border-radius: 8px;
        padding: 2px;
        box-shadow: 0 0 10px rgba(229, 185, 92, 0.3);
    }
    .holo-inner {
        background: rgba(7, 14, 30, 0.8);
        padding: 4px 8px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .holo-text {
        font-size: 0.62rem;
        font-weight: 800;
        letter-spacing: 1.2px;
        background: linear-gradient(90deg, #fef08a, #e5b95c, #ffffff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* MEMBER PHOTO */
    .member-photo-wrapper {
        position: relative;
        width: 80px;
        height: 80px;
    }
    .member-photo {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 50%;
        border: 2.5px solid var(--card-gold);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5), 0 0 10px rgba(229, 185, 92, 0.3);
        background: #0f172a;
    }
    .verified-tick {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 22px;
        height: 22px;
        background: #10b981;
        border: 2px solid #070e1e;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* QR CODE CONTAINER */
    .qr-container {
        background: #ffffff;
        padding: 5px;
        border-radius: 8px;
        border: 2px solid var(--card-gold);
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .qr-code-img {
        width: 68px;
        height: 68px;
        object-fit: contain;
        display: block;
    }
    .qr-label {
        display: block;
        font-size: 0.55rem;
        font-weight: 800;
        color: #070e1e;
        letter-spacing: 0.8px;
        margin-top: 2px;
    }

    /* CARD FOOTER INFO */
    .member-name {
        font-size: 1.05rem;
        font-weight: 800;
        letter-spacing: 1.2px;
        color: #ffffff;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.6);
        max-width: 250px;
    }
    .code-badge {
        font-family: 'Courier New', Courier, monospace;
        font-size: 0.76rem;
        font-weight: 800;
        color: var(--card-gold);
        background: rgba(229, 185, 92, 0.12);
        padding: 2px 8px;
        border-radius: 4px;
        border: 1px solid rgba(229, 185, 92, 0.35);
        letter-spacing: 1.5px;
    }
    .badge-status-gold {
        font-size: 0.65rem;
        font-weight: 800;
        letter-spacing: 1.2px;
        color: var(--card-gold);
        background: rgba(229, 185, 92, 0.1);
        border: 1px solid rgba(229, 185, 92, 0.4);
        padding: 2px 8px;
        border-radius: 4px;
    }

    /* TERMS BOX ON BACK */
    .terms-box {
        background: rgba(0, 0, 0, 0.3);
        border-radius: 8px;
        padding: 10px 14px;
        border: 1px solid rgba(229, 185, 92, 0.2);
    }
    .terms-list li {
        margin-bottom: 5px;
    }

    /* ======================================================= */
    /* PRINT MEDIA QUERY                                       */
    /* ======================================================= */
    @media print {
        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        /* Hide all page layout elements */
        body, html {
            background: #ffffff !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        #kt_app_header,
        #kt_app_sidebar,
        #kt_app_footer,
        .app-header,
        .app-sidebar,
        .app-footer,
        .no-print,
        .btn,
        #kt_app_toolbar {
            display: none !important;
        }

        .d-flex.flex-column.flex-column-fluid.mt-4,
        .app-content,
        .app-container {
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Card print layout */
        .card-print-stage {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            width: 100% !important;
            margin-top: 15mm !important;
        }

        .row {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            gap: 15mm !important;
        }

        .side-wrapper {
            page-break-inside: avoid !important;
            margin-bottom: 15mm !important;
        }

        /* Mode: Only Front */
        body.print-front-only #wrapper-back {
            display: none !important;
        }

        /* Mode: Only Back */
        body.print-front-only #wrapper-front {
            display: flex !important;
        }

        /* Mode: Only Back */
        body.print-back-only #wrapper-front {
            display: none !important;
        }

        body.print-back-only #wrapper-back {
            display: flex !important;
        }

        /* Force high fidelity printing */
        .id-card {
            box-shadow: none !important;
            transform: none !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
            border: 1px dashed #999999 !important; /* Crop line helper */
            width: 85.6mm !important;
            height: 54mm !important;
            max-width: none !important;
            border-radius: 4mm !important;
        }

        .card-inner {
            padding: 4mm 5mm !important;
        }

        .member-photo {
            width: 16mm !important;
            height: 16mm !important;
        }
        .member-photo-wrapper {
            width: 16mm !important;
            height: 16mm !important;
        }
        .qr-code-img {
            width: 13mm !important;
            height: 13mm !important;
        }
        .brand-logo {
            height: 6mm !important;
        }
        .brand-logo-back {
            height: 6mm !important;
        }
        .member-name {
            font-size: 8pt !important;
        }
        .code-badge {
            font-size: 6pt !important;
        }
        .fs-9 {
            font-size: 5.5pt !important;
            line-height: 1.25 !important;
        }
        .fs-10 {
            font-size: 5pt !important;
            line-height: 1.25 !important;
        }
        .terms-box {
            padding: 2mm 3mm !important;
        }
    }
</style>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    function printCard(mode) {
        document.body.classList.remove('print-front-only', 'print-back-only');
        
        if (mode === 'front') {
            document.body.classList.add('print-front-only');
        } else if (mode === 'back') {
            document.body.classList.add('print-back-only');
        }

        // Trigger native browser print dialog
        window.print();

        // Cleanup class after print dialog closes
        setTimeout(() => {
            document.body.classList.remove('print-front-only', 'print-back-only');
        }, 1000);
    }
</script>
<?= $this->endSection(); ?>

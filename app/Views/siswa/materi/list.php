<?= $this->extend('siswa/template/app'); ?>
<?= $this->section('styles'); ?>
<style>
    /* ====================================================
       MODERN HERO & STATS BANNER
       ==================================================== */
    .hero-materi-banner {
        background: linear-gradient(135deg, #0b132b 0%, #1c2541 60%, #1e3a8a 100%);
        border-radius: 1.25rem;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 10px 30px rgba(11, 19, 43, 0.15);
    }
    .hero-overlay-pattern {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle at 80% 20%, rgba(59, 130, 246, 0.15) 0%, transparent 40%),
                          radial-gradient(circle at 10% 80%, rgba(229, 185, 92, 0.1) 0%, transparent 30%);
        pointer-events: none;
    }

    /* ====================================================
       FILTER PILLS
       ==================================================== */
    .filter-scroll-wrapper {
        position: relative;
        width: 100%;
    }
    .filter-scroll {
        display: flex;
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
        gap: 0.75rem;
        padding: 4px 2px 8px 2px;
    }
    .filter-scroll::-webkit-scrollbar {
        display: none;
    }
    .btn-filter {
        border-radius: 50px !important; 
        padding: 0.65rem 1.4rem !important;
        font-weight: 600;
        font-size: 0.9rem;
        background: #ffffff;
        border: 1px solid #e2e8f0 !important; 
        color: #475569;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }
    .btn-filter:hover {
        background: #f8fafc;
        border-color: #cbd5e1 !important;
        color: #0f172a;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.07);
    }
    .btn-filter.active {
        background: #009ef7 !important;
        border-color: #009ef7 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(0, 158, 247, 0.35) !important;
    }
    .badge-filter-count {
        background: rgba(0, 0, 0, 0.08);
        font-size: 0.72rem;
        padding: 2px 8px;
        border-radius: 50px;
        font-weight: 700;
    }
    .btn-filter.active .badge-filter-count {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    /* ====================================================
       COURSE CARD STYLING
       ==================================================== */
    .materi-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 1.25rem !important;
        border: 1px solid #eef2f6 !important;
        background: #ffffff;
    }
    .materi-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08) !important;
        border-color: #e2e8f0 !important;
    }

    /* Thumbnail with Subtle Gradient */
    .card-thumbnail-modern {
        position: relative;
        aspect-ratio: 16/9;
        overflow: hidden;
        border-radius: 1.25rem 1.25rem 0 0;
        background-color: #0f172a;
    }
    .card-thumbnail-modern img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .materi-card:hover .card-thumbnail-modern img {
        transform: scale(1.06);
    }
    .thumbnail-scrim {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(15, 23, 42, 0.85) 0%, rgba(15, 23, 42, 0.25) 50%, transparent 100%);
        pointer-events: none;
    }
    .thumbnail-title-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 1.25rem 1.25rem 0.85rem 1.25rem;
        z-index: 2;
    }

    /* Badges */
    .floating-status-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 3;
    }

    /* Animation */
    .animate-card {
        animation: cardFadeIn 0.5s ease backwards;
    }
    @keyframes cardFadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<?php
$db = Config\Database::connect();
$id_siswa = session()->get('id');

// Hitung Statistik Global untuk Banner
$totalMapelCount = 0;
$totalSesiCount = 0;
$totalSelesaiCount = 0;

if (!empty($modul)) {
    foreach ($modul as $nama_kelas => $daftar_materi) {
        foreach ($daftar_materi as $m) {
            $totalMapelCount++;
            $qM = $db->query("SELECT COUNT(CASE WHEN dilihat = '1' THEN materi ELSE NULL END) as materi_selesai FROM materi_siswa WHERE mapel = '$m->id_mapel' AND siswa = '$id_siswa'")->getRow();
            $qT = $db->query("SELECT COUNT(*) as total_materi FROM materi WHERE mapel = '$m->id_mapel'")->getRow();
            $totalSesiCount += ($qT->total_materi ?? 0);
            $totalSelesaiCount += ($qM->materi_selesai ?? 0);
        }
    }
}
$overallProgress = ($totalSesiCount > 0) ? round(($totalSelesaiCount / $totalSesiCount) * 100) : 0;
?>

<div class="d-flex flex-column flex-column-fluid py-4 py-lg-6 mt-4">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <!-- HERO LEARNING BANNER -->
            <div class="hero-materi-banner p-6 p-lg-10 mb-8 position-relative">
                <div class="hero-overlay-pattern"></div>
                <div class="row align-items-center position-relative z-index-1 g-6">
                    <div class="col-lg-7">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge badge-light-primary fw-bold fs-8 px-3 py-1 text-uppercase">Portal Belajar Resmi</span>
                            <span class="text-white text-opacity-75 fs-8">• LKP Kelas Brevet</span>
                        </div>
                        <h1 class="text-white fw-bolder fs-2hx mb-3 lh-sm">
                            Materi Pelatihan Brevet Pajak
                        </h1>
                        <p class="text-white text-opacity-80 fs-6 fw-normal mb-0 max-w-600px">
                            Akses modul kursus resmi, video pembahasan interaktif dari instruktur bersertifikasi, dan pantau progres kompetensi Anda.
                        </p>
                    </div>

                    <!-- Mini KPI Stats Inside Banner -->
                    <div class="col-lg-5">
                        <div class="row g-3">
                            <div class="col-4">
                                <div class="bg-white bg-opacity-10 rounded-3 p-4 text-center border border-white border-opacity-15 backdrop-blur">
                                    <div class="text-white fs-2hx fw-bolder mb-1"><?= $totalMapelCount ?></div>
                                    <span class="text-white text-opacity-75 fs-9 fw-semibold text-uppercase d-block">Modul Kelas</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-white bg-opacity-10 rounded-3 p-4 text-center border border-white border-opacity-15 backdrop-blur">
                                    <div class="text-white fs-2hx fw-bolder mb-1"><?= $totalSesiCount ?></div>
                                    <span class="text-white text-opacity-75 fs-9 fw-semibold text-uppercase d-block">Video Sesi</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-white bg-opacity-10 rounded-3 p-4 text-center border border-white border-opacity-15 backdrop-blur">
                                    <div class="text-gold fs-2hx fw-bolder mb-1" style="color: #fef08a !important;"><?= $overallProgress ?>%</div>
                                    <span class="text-white text-opacity-75 fs-9 fw-semibold text-uppercase d-block">Progres</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FILTER KATEGORI KELAS -->
            <?php if (!empty($modul)): ?>
                <div class="filter-scroll-wrapper mb-8">
                    <div class="filter-scroll">
                        <button class="btn btn-filter active flex-shrink-0" data-filter="semua">
                            <i class="ki-outline ki-category fs-6 text-primary me-1"></i> Semua Modul
                            <span class="badge-filter-count"><?= $totalMapelCount ?></span>
                        </button>

                        <?php foreach ($modul as $nama_kelas => $daftar_materi) : ?>
                            <?php $kategori_slug = preg_replace('/[^a-z0-9]+/', '-', strtolower(trim($nama_kelas))); ?>
                            <button class="btn btn-filter flex-shrink-0" data-filter="<?= $kategori_slug ?>">
                                <?= esc($nama_kelas) ?>
                                <span class="badge-filter-count"><?= count($daftar_materi) ?></span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- GRID MATERI CARDS -->
            <div class="row g-7">
                <?php if (!empty($modul)): ?>
                    <?php $delay = 0; ?>

                    <?php foreach ($modul as $nama_kelas => $daftar_materi) : ?>
                        <?php $kategori_slug = preg_replace('/[^a-z0-9]+/', '-', strtolower(trim($nama_kelas))); ?>

                        <?php foreach ($daftar_materi as $m) : ?>
                            <?php
                                $query_materi = $db->query("SELECT  
                                    COUNT(CASE WHEN dilihat = '1' THEN materi ELSE NULL END) as materi_selesai
                                    FROM materi_siswa 
                                    WHERE mapel = '$m->id_mapel' AND siswa = '$id_siswa'")->getRow();

                                $query_total_materi = $db->query("SELECT COUNT(*) as total_materi FROM materi WHERE mapel = '$m->id_mapel'")->getRow();
                                
                                $total_materi = $query_total_materi->total_materi ?? 0;
                                $materi_selesai = $query_materi->materi_selesai ?? 0;
                                $persentase = ($total_materi > 0) ? round(($materi_selesai / $total_materi) * 100) : 0;
                                
                                $delay += 0.05;
                            ?>

                            <div class="col-md-6 col-xl-4 animate-card materi-item" data-kategori="<?= $kategori_slug ?>" style="animation-delay: <?= $delay ?>s">
                                <div class="card materi-card shadow-sm h-100 overflow-hidden d-flex flex-column">

                                    <!-- THUMBNAIL -->
                                    <div class="card-thumbnail-modern">
                                        <?= img_lazy('uploads/mapel/' . $m->file, $m->nama_mapel, ['class' => 'w-100 h-100 object-fit-cover']) ?>
                                        <div class="thumbnail-scrim"></div>

                                        <!-- Status Badge Kanan Atas -->
                                        <div class="floating-status-badge">
                                            <?php if ($m->status == 0): ?>
                                                <span class="badge badge-light-warning fw-bold fs-8 px-3 py-2 shadow-sm">
                                                    <i class="ki-outline ki-time fs-7 me-1 text-warning"></i> Segera Hadir
                                                </span>
                                            <?php elseif($persentase == 100): ?>
                                                <span class="badge badge-success fw-bold fs-8 px-3 py-2 shadow-sm">
                                                    <i class="ki-outline ki-check-circle fs-7 me-1 text-white"></i> Selesai
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-light-primary fw-bold fs-8 px-3 py-2 shadow-sm">
                                                    <i class="ki-outline ki-book-open fs-7 me-1 text-primary"></i> <?= $persentase ?>% Selesai
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Judul di Atas Thumbnail -->
                                        <div class="thumbnail-title-overlay">
                                            <span class="badge badge-light-dark fs-9 fw-bold px-2 py-1 mb-1 text-uppercase text-white bg-dark bg-opacity-50">
                                                <?= esc($nama_kelas) ?>
                                            </span>
                                            <h3 class="text-white fw-bolder fs-4 m-0 text-truncate" title="<?= esc($m->nama_mapel) ?>">
                                                <?= esc($m->nama_mapel) ?>
                                            </h3>
                                        </div>
                                    </div>

                                    <!-- BODY -->
                                    <div class="card-body p-6 d-flex flex-column flex-grow-1 justify-content-between">
                                        
                                        <div>
                                            <!-- Meta Info Pills -->
                                            <div class="d-flex align-items-center justify-content-between bg-light rounded-3 p-3 mb-5 border border-dashed border-gray-200">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="w-35px h-35px rounded-circle bg-light-primary d-flex align-items-center justify-content-center">
                                                        <i class="ki-outline ki-video fs-4 text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-900 fw-bolder fs-7 d-block"><?= $total_materi ?> Sesi</span>
                                                        <span class="text-muted fs-9">Video Materi</span>
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="w-35px h-35px rounded-circle bg-light-warning d-flex align-items-center justify-content-center">
                                                        <i class="ki-outline ki-time fs-4 text-warning"></i>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-900 fw-bolder fs-7 d-block"><?= max($total_materi * 20, 30) ?> Menit</span>
                                                        <span class="text-muted fs-9">Estimasi Belajar</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Progress Bar -->
                                            <div class="mb-5">
                                                <div class="d-flex justify-content-between align-items-center fs-7 mb-2">
                                                    <span class="fw-bold text-gray-700">Progres Belajar</span>
                                                    <span class="fw-bolder <?= $persentase == 100 ? 'text-success' : 'text-primary' ?>"><?= $persentase ?>%</span>
                                                </div>
                                                <div class="h-6px w-100 bg-light-primary rounded-pill overflow-hidden">
                                                    <div class="rounded-pill h-100 <?= $persentase == 100 ? 'bg-success' : 'bg-primary' ?>" 
                                                         role="progressbar" 
                                                         style="width: <?= $persentase ?>%; transition: width 0.8s ease-in-out;">
                                                    </div>
                                                </div>
                                                <div class="mt-2 text-muted fs-8 fw-semibold d-flex align-items-center">
                                                    <?php if($persentase == 100): ?>
                                                        <i class="ki-outline ki-check-circle fs-6 me-1 text-success"></i>
                                                        <span class="text-success fw-bold">Semua materi terselesaikan!</span>
                                                    <?php else: ?>
                                                        <i class="ki-outline ki-chart-line fs-8 me-1 text-primary"></i>
                                                        <span><?= $materi_selesai ?> dari <?= $total_materi ?> materi terselesaikan</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="d-flex flex-column gap-2 pt-2">
                                            <?php if ($m->status == 0): ?>
                                                <button class="btn btn-light-danger w-100 fs-7 fw-bold py-3 disabled">
                                                    <i class="ki-outline ki-lock fs-5 me-2"></i> Materi Belum Tersedia
                                                </button>
                                            <?php else: ?>
                                                <a href="<?= base_url('sw-siswa/materi/lihat-materi/' . encrypt_url($m->kode_materi) . '/' . encrypt_url($m->id_mapel) . '/' . encrypt_url($m->id_kelas)) ?>"
                                                    class="btn <?= ($persentase == 100) ? 'btn-light-success' : 'btn-primary' ?> w-100 fs-7 fw-bold py-3 shadow-sm d-flex align-items-center justify-content-center">
                                                    <?php if ($persentase == 100): ?>
                                                        <span>Belajar Ulang</span>
                                                        <i class="ki-outline ki-arrow-circle-right fs-4 ms-2 text-success"></i>
                                                    <?php elseif ($persentase > 0): ?>
                                                        <span>Lanjutkan Belajar</span>
                                                        <i class="ki-outline ki-arrow-right fs-4 ms-2"></i>
                                                    <?php else: ?>
                                                        <span>Mulai Belajar</span>
                                                        <i class="ki-outline ki-arrow-right fs-4 ms-2"></i>
                                                    <?php endif; ?>
                                                </a>

                                                <a href="https://drive.google.com/drive/folders/1Rqr_3mgwLJx-8Zx2NLUPNuLT0xrqvUe9?usp=sharing" target="_blank" class="btn btn-light-success w-100 fs-8 fw-bold py-2 d-flex align-items-center justify-content-center">
                                                    <i class="ki-outline ki-cloud-download fs-5 me-1 text-success"></i> Unduh Modul / Softcopy
                                                </a>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                <?php else: ?>
                    <!-- EMPTY STATE -->
                    <div class="col-12 animate-card">
                        <div class="card border-0 shadow-none bg-transparent">
                            <div class="card-body d-flex flex-column flex-center py-20">
                                <div class="mb-8 text-center">
                                    <img src="<?= base_url('assets/peserta/media/illustrations/sigma-1/5.png') ?>" class="mw-250px mb-6" alt="Empty State">
                                </div>

                                <div class="text-center">
                                    <h2 class="fw-bolder text-gray-900 mb-3 fs-2">Belum Ada Materi Aktif</h2>
                                    <p class="text-muted fs-6 fw-semibold mb-8 max-w-600px mx-auto">
                                        Anda belum memiliki paket pelatihan aktif. Daftarkan diri Anda pada paket Brevet Pajak resmi kami untuk membuka seluruh video sesi pembelajaran dan modul softcopy.
                                    </p>
                                    <div class="d-flex flex-center gap-3">
                                        <a href="<?= base_url('list-bimbel') ?>" class="btn btn-primary px-8 py-3 fw-bold shadow-sm">
                                            <i class="ki-outline ki-shopping-cart fs-4 me-2"></i> Jelajahi Paket Pelatihan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        if (typeof KTComponents !== 'undefined') {
            KTComponents.init();
        }

        // ==========================================
        // FILTER KATEGORI KELAS
        // ==========================================
        $('.btn-filter').on('click', function() {
            $('.btn-filter').removeClass('active');
            $(this).addClass('active');

            let filter = $(this).attr('data-filter');

            if (filter === 'semua') {
                $('.materi-item').fadeIn(250);
            } else {
                $('.materi-item').hide();
                $('.materi-item[data-kategori="' + filter + '"]').fadeIn(250);
            }
        });
    });
</script>
<?= $this->endSection(); ?>

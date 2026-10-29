<?= $this->extend('siswa/template/app'); ?>
<?= $this->section('styles'); ?>
<style>
    /* Animasi Masuk (Fade Up) */
    .animate-card {
        animation: cardFadeIn 0.6s ease backwards;
    }

    @keyframes cardFadeIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Hover Effect pada Card */
    .hover-elevate-up {
        transition: all 0.3s ease-in-out;
    }

    .hover-elevate-up:hover {
        transform: translateY(-10px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.1) !important;
    }

    /* Button Glow Effect */
    .btn-glow-primary:hover {
        box-shadow: 0 0 15px rgba(26, 79, 240, 0.4);
    }

    /* Animasi denyut halus untuk empty state */
    .pulse-animation {
        animation: pulseCustom 3s infinite ease-in-out;
    }

    @keyframes pulseCustom {
        0% { transform: scale(1); }
        50% { transform: scale(1.03); }
        100% { transform: scale(1); }
    }

    .max-w-600px {
        max-width: 600px;
    }

    /* ====================================================
       STYLING FILTER & CARD
       ==================================================== */

    /* STYLING FILTER SCROLL */
    .filter-scroll {
        display: flex;
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
        gap: 1rem;
        padding-bottom: 0.5rem;
    }

    .filter-scroll::-webkit-scrollbar {
        display: none;
    }

    .btn-filter {
        border-radius: 50px !important; 
        padding: 0.65rem 1.75rem !important;
        font-weight: 600;
        font-size: 0.95rem;
        background: transparent;
        /* Outline Putih Solid */
        border: 1px solid #ffffff !important; 
        color: #ffffff;
        transition: all 0.2s ease;
    }

    /* Saat Tombol Aktif / Di-hover */
    .btn-filter.active, .btn-filter:hover {
        background: #009ef7 !important; /* Biru terang solid */
        border: 1px solid #009ef7 !important;
        color: #ffffff !important;
    }

    /* THUMBNAIL KARTU MIRIP FOTO (Gradasi & Badge) */
    .card-thumbnail {
        position: relative;
        aspect-ratio: 16/9;
        overflow: hidden;
        border-radius: 0.75rem 0.75rem 0 0;
    }
    .card-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .hover-elevate-up:hover .card-thumbnail img {
        transform: scale(1.1);
    }

    /* Overlay Gradasi Biru di bawah gambar */
    .thumbnail-gradient {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 70%;
        background: linear-gradient(to top, rgba(0, 85, 255, 0.95) 0%, rgba(0, 85, 255, 0.4) 60%, transparent 100%);
        display: flex;
        align-items: flex-end;
        justify-content: center;
        padding: 1.5rem 1rem;
    }

    /* Text Judul di dalam gambar */
    .thumbnail-title {
        color: #ffffff;
        font-weight: 800;
        font-size: 1.25rem;
        margin: 0;
        text-align: center;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        line-height: 1.3;
    }

    /* Badge Merah/Hijau Kanan Atas */
    .status-badge-photo {
        position: absolute;
        top: 1rem;
        right: 1rem;
        border-radius: 50px;
        padding: 0.4rem 1.25rem;
        font-weight: 700;
        font-size: 0.85rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.15);
        color: white;
        z-index: 10;
    }
    .bg-pink-custom {
        background-color: #F8285A; /* Warna pink/merah persis seperti di foto */
    }
</style>
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>

<?php $db = Config\Database::connect(); ?>

<div class="d-flex flex-column flex-column-fluid py-4 py-lg-6 mt-8">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <?php if (!empty($modul)): ?>
                <!-- TOMBOL FILTER KELAS -->
                <div class="d-flex filter-scroll mb-8">
                    <!-- Tombol "Semua" defaultnya aktif -->
                    <button class="btn btn-filter flex-shrink-0 active" data-filter="semua">Semua</button>

                    <!-- Looping Dinamis Kategori Kelas -->
                    <?php foreach ($modul as $nama_kelas => $daftar_materi) : ?>
                        <!-- Perbaikan keamanan filter slug untuk jQuery (Menghapus & , spasi, dll) -->
                        <?php $kategori_slug = preg_replace('/[^a-z0-9]+/', '-', strtolower(trim($nama_kelas))); ?>
                        
                        <button class="btn btn-filter flex-shrink-0" data-filter="<?= $kategori_slug ?>">
                            <?= htmlspecialchars($nama_kelas) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="row g-7">
                <?php if (!empty($modul)): ?>
                    <?php $delay = 0; ?>

                    <!-- Looping per kelas -->
                    <?php foreach ($modul as $nama_kelas => $daftar_materi) : ?>
                        <!-- Samakan fungsi slug dengan tombol di atas -->
                        <?php $kategori_slug = preg_replace('/[^a-z0-9]+/', '-', strtolower(trim($nama_kelas))); ?>

                        <!-- LOOPING MATERI DI DALAM KELAS TERSEBUT -->
                        <?php foreach ($daftar_materi as $m) : ?>
                            <?php
                                $id_siswa = session()->get('id'); // Ambil ID siswa dari session
                                
                                $query_materi = $db->query("SELECT  
                                    COUNT(CASE WHEN dilihat = '1' THEN materi ELSE NULL END) as materi_selesai
                                    FROM materi_siswa 
                                    WHERE mapel = '$m->id_mapel' AND siswa = '$id_siswa'")->getRow();

                                $query_total_materi = $db->query("SELECT COUNT(*) as total_materi FROM materi WHERE mapel = '$m->id_mapel'")->getRow();
                                
                                $total_materi = $query_total_materi->total_materi ?? 0;
                                $materi_selesai = $query_materi->materi_selesai ?? 0;
                                
                                // Menghitung persentase (hindari pembagian dengan nol)
                                $persentase = ($total_materi > 0) ? round(($materi_selesai / $total_materi) * 100) : 0;
                                
                                $delay += 0.1; // Memberikan efek loading berurutan
                            ?>

                            <!-- MATERI ITEM (Menambahkan Class materi-item dan Data Kategori) -->
                            <div class="col-md-6 col-xl-4 animate-card materi-item" data-kategori="<?= $kategori_slug ?>" style="animation-delay: <?= $delay ?>s">
                                <div class="card border-0 shadow-sm h-100 hover-elevate-up rounded-4">

                                    <!-- BAGIAN THUMBNAIL (Fokus Desain Mirip Foto) -->
                                    <div class="card-thumbnail">
                                        <?= img_lazy('uploads/mapel/' . $m->file, "loading") ?>
                                        
                                        <!-- Gradasi Biru & Judul di dalam Gambar -->
                                        <div class="thumbnail-gradient">
                                            <h3 class="thumbnail-title"><?= $m->nama_mapel ?></h3>
                                        </div>

                                        <!-- Badge Status di Kanan Atas -->
                                        <?php if ($m->status == 0): ?>
                                            <div class="status-badge-photo bg-secondary text-dark border border-gray-400">Coming Soon</div>
                                        <?php elseif($persentase < 100): ?>
                                            <div class="status-badge-photo bg-pink-custom">Belum Selesai</div>
                                        <?php else: ?>
                                            <div class="status-badge-photo bg-success">Selesai</div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- BAGIAN FUNGSIONAL (Progress & Action) -->
                                    <div class="card-body p-6">
                                        
                                        <!-- Ringkasan Info (Compact) -->
                                        <div class="d-flex flex-stack mb-5">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light-info rounded p-2 me-3">
                                                    <i class="ki-outline ki-book-open fs-4 text-info"></i>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="text-dark fw-bold fs-7"><?= $total_materi ?> Sesi</span>
                                                    <span class="text-muted fw-semibold fs-9">Video Materi</span>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center">
                                                <div class="bg-light-warning rounded p-2 me-3">
                                                    <i class="ki-outline ki-time fs-4 text-warning"></i>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="text-dark fw-bold fs-7">180 Menit</span>
                                                    <span class="text-muted fw-semibold fs-9">Durasi Total</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Progress Bar -->
                                        <div class="d-flex flex-column mb-6">
                                            <div class="d-flex justify-content-between w-100 fs-7 mb-2">
                                                <span class="fw-bold text-gray-700">Progres Belajar</span>
                                                <span class="fw-bolder text-primary"><?= $persentase ?>%</span>
                                            </div>
                                            <div class="h-8px w-100 bg-light-primary rounded-pill overflow-hidden">
                                                <div class="bg-primary rounded-pill h-100" role="progressbar" style="width: <?= $persentase ?>%; transition: width 1s ease-in-out;" aria-valuenow="<?= $persentase ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <div class="mt-2 text-muted fs-8 fw-semibold d-flex align-items-center">
                                                <?php if($persentase == 100): ?>
                                                    <i class="ki-outline ki-check-circle fs-6 me-1 text-success"></i>
                                                    <span class="text-success">Semua materi diselesaikan!</span>
                                                <?php else: ?>
                                                    <i class="ki-outline ki-chart-line fs-8 me-1 text-primary"></i>
                                                    <?= $materi_selesai ?> dari <?= $total_materi ?> materi diselesaikan
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Tombol Action -->
                                        <div class="d-flex flex-column gap-2 mt-auto">
                                            <?php if ($m->status == 0): ?>
                                                <button class="btn btn-light-danger w-100 fs-7 fw-bold py-3 disabled">
                                                    <i class="ki-outline ki-lock fs-5 me-2"></i> Belum Tersedia
                                                </button>
                                            <?php else: ?>
                                                <a href="<?= base_url('sw-siswa/materi/lihat-materi/' . encrypt_url($m->kode_materi) . '/' . encrypt_url($m->id_mapel) . '/' . encrypt_url($m->id_kelas)) ?>"
                                                    class="btn <?= ($persentase == 100) ? 'btn-light-success' : 'btn-primary btn-glow-primary' ?> w-100 fs-7 fw-bold py-3 shadow-sm">
                                                    <?= ($persentase == 100) ? 'Belajar Ulang <i class="ki-outline ki-arrow-circle-right fs-5 ms-2"></i>' : 'Mulai Belajar <i class="ki-outline ki-arrow-right fs-5 ms-2"></i>' ?>
                                                </a>

                                                <a href="https://drive.google.com/drive/folders/1Rqr_3mgwLJx-8Zx2NLUPNuLT0xrqvUe9?usp=sharing" target="_blank" class="btn btn-success w-100 fs-7 fw-bold py-3 shadow-sm">
                                                    <i class="ki-outline ki-cloud-download fs-5 me-2"></i> Download Materi
                                                </a>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                <?php else: ?>
                    <div class="col-12 animate-card">
                        <div class="card border-0 shadow-none bg-transparent">
                            <div class="card-body d-flex flex-column flex-center py-20">
                                <div class="mb-10 text-center">
                                    <img src="<?= base_url('assets/peserta/media/illustrations/sigma-1/5.png') ?>"
                                        class="mw-350px mw-lg-450px mb-10 pulse-animation"
                                        alt="Empty State">
                                </div>

                                <div class="text-center">
                                    <h2 class="fw-boldest text-dark mb-4 fs-1">Mulai Perjalanan Belajarmu!</h2>
                                    <p class="text-gray-500 fs-5 fw-semibold mb-10 max-w-600px mx-auto">
                                        Anda belum memiliki materi aktif untuk dipelajari.<br>
                                        Tingkatkan kompetensi Anda dengan pilihan paket Brevet terbaik kami dan jadilah ahli pajak profesional sekarang juga.
                                    </p>
                                    <div class="d-flex flex-center gap-4">
                                        <a href="<?= base_url('list-bimbel') ?>" class="btn btn-primary px-8 fw-bold">
                                            <i class="ki-outline ki-shopping-cart fs-4 me-2"></i> Jelajahi Paket Materi
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
<!-- TAMBAHKAN SECTION SCRIPTS DI SINI -->
<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        if (typeof KTComponents !== 'undefined') {
            KTComponents.init();
        }

        // ==========================================
        // FITUR FILTER KATEGORI KELAS
        // ==========================================
        $('.btn-filter').click(function() {
            // 1. Reset semua tombol (kembali ke state awal outline)
            $('.btn-filter').removeClass('active');

            // 2. Tambahkan class active ke tombol yang diklik
            $(this).addClass('active');

            // 3. Ambil data-filter (slug kategori)
            let filter = $(this).attr('data-filter');

            // 4. Logika menyembunyikan/menampilkan kartu materi
            if (filter === 'semua') {
                $('.materi-item').fadeIn(300);
            } else {
                $('.materi-item').hide();
                $('.materi-item[data-kategori="' + filter + '"]').fadeIn(300);
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        if (typeof KTComponents !== 'undefined') {
            KTComponents.init();
        }

        // ==========================================
        // FITUR FILTER KATEGORI KELAS
        // ==========================================
        $('.btn-filter').click(function() {
            // 1. Reset semua tombol (kembali ke state awal outline)
            $('.btn-filter').removeClass('active');

            // 2. Tambahkan class active ke tombol yang diklik
            $(this).addClass('active');

            // 3. Ambil data-filter (slug kategori)
            let filter = $(this).attr('data-filter');

            // 4. Logika menyembunyikan/menampilkan kartu materi
            if (filter === 'semua') {
                $('.materi-item').fadeIn(300);
            } else {
                $('.materi-item').hide();
                $('.materi-item[data-kategori="' + filter + '"]').fadeIn(300);
            }
        });
    });
</script>
<?= $this->endSection(); ?>
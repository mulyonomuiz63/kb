<style>
    /* Animasi Masuk Awal */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
    }

    /* Styling Wadah Leaderboard (Glassmorphism) */
    .leaderboard-box {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 1rem;
        padding: 1.25rem;
        height: 100%;
    }

    /* Container untuk Auto-Scroll */
    .ticker-container {
        height: 130px; 
        overflow: hidden;
        position: relative;
        mask-image: linear-gradient(to bottom, transparent, black 15%, black 85%, transparent);
        -webkit-mask-image: linear-gradient(to bottom, transparent, black 15%, black 85%, transparent);
    }

    /* Konten Ticker yang Bergerak */
    .ticker-content {
        display: flex;
        flex-direction: column;
        gap: 10px;
        animation: scroll-vertical 40s linear infinite;
    }
    
    /* Hover untuk Pause/Berhenti Sementara saat user membaca */
    .ticker-container:hover .ticker-content {
        animation-play-state: paused;
    }

    /* MODIFIKASI: Pause 2 detik (5% dari 40s) di awal sebelum mulai scroll */
    @keyframes scroll-vertical {
        0%, 7% { transform: translateY(0); }
        100% { transform: translateY(-50%); }
    }

    /* Styling Baris/Row Peserta */
    .leaderboard-row {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        transition: background 0.3s ease;
    }
    .leaderboard-row:hover {
        background: rgba(255, 255, 255, 0.15);
    }

    /* Styling Badge Peringkat */
    .rank-badge {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: bold;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .rank-1 { background: rgba(255, 215, 0, 0.2); color: #FFD700; border: 1px solid #FFD700; box-shadow: 0 0 10px rgba(255, 215, 0, 0.3); }
    .rank-2 { background: rgba(192, 192, 192, 0.2); color: #C0C0C0; border: 1px solid #C0C0C0; }
    .rank-3 { background: rgba(205, 127, 50, 0.2); color: #CD7F32; border: 1px solid #CD7F32; }
    .rank-other { background: rgba(255, 255, 255, 0.1); color: #E4E6EF; }
</style>

<div id="kt_app_toolbar" class="app-toolbar py-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-column flex-lg-row align-items-stretch gap-5 gap-lg-10">

        <!-- ================= KOLOM KIRI (Info Utama) ================= -->
        <div class="d-flex flex-column flex-row-fluid justify-content-center">

            <!-- BAGIAN ASLI: Breadcrumbs -->
            <div class="d-flex align-items-center pt-1">
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold">
                    <li class="breadcrumb-item text-white fw-bold lh-1">
                        <a href="<?= base_url('/') ?>" class="text-white text-hover-primary">
                            <i class="ki-outline ki-home text-gray-100 fs-6"></i>
                        </a>
                    </li>
                    <?php if (isset($breadcrumbs) && is_array($breadcrumbs)): ?>
                        <?php foreach ($breadcrumbs as $item): ?>
                            <li class="breadcrumb-item">
                                <i class="ki-outline ki-right fs-7 text-gray-100 mx-n1"></i>
                            </li>
                            <li class="breadcrumb-item text-white fw-bold lh-1">
                                <?php if ($item['url'] !== '#'): ?>
                                    <a href="<?= $item['url'] ?>" class="text-white text-hover-primary"><?= esc($item['title']) ?></a>
                                <?php else: ?>
                                    <span class="text-gray-400"><?= esc($item['title']) ?></span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- BAGIAN ASLI: Greeting -->
            <div class="d-flex flex-stack flex-wrap flex-lg-nowrap gap-4 gap-lg-10 pt-6 pb-6">
                <div class="page-title me-5">
                    <h1 class="page-heading d-flex text-white fw-bold fs-2x flex-column justify-content-center my-0">
                        Hai, <?= session()->get('nama') ?>! 👋
                        <span class="page-desc text-gray-200 fw-semibold fs-5 pt-3">
                            Semangat belajar hari ini! Mari selesaikan modul Anda dan raih sertifikat kompetensi.
                        </span>
                    </h1>
                </div>
            </div>

            <!-- BAGIAN ASLI: Banner -->
            <?php if (isset($show_banner) && $show_banner === true): ?>
                <?= $this->include('siswa/template/partials/_slider_toolbar') ?>
            <?php endif; ?>

        </div>

        <!-- ================= KOLOM KANAN (Leaderboard Auto-Scroll) ================= -->
        <!-- Filter PHP: Hanya tampilkan jika URL saat ini adalah sw-siswa -->
        <?php if (url_is('sw-siswa') && !empty($top_siswa)): ?>
            <div class="w-100 w-lg-350px flex-shrink-0 animate-fade-in-up mt-3 mt-lg-0">
                <div class="leaderboard-box d-flex flex-column justify-content-center">

                    <div class="d-flex align-items-center mb-4">
                        <i class="ki-outline ki-trophy text-warning fs-1 me-3"></i>
                        <h3 class="text-white fw-bold mb-0">Top 10 Peserta Nilai Tertinggi</h3>
                    </div>

                    <!-- Area Scroll (Hanya menampilkan ~3 baris) -->
                    <div class="ticker-container">
                        <div class="ticker-content">

                            <?php
                            // Loop dijalankan 2 KALI agar ilusi scroll infinitenya berjalan mulus
                            for ($i = 0; $i < 2; $i++):
                                // Menggunakan $top_siswa dari controller
                                foreach ($top_siswa as $index => $peserta):

                                    // Membuat rank secara dinamis dari index array (dimulai dari 0, jadi + 1)
                                    $rank = $index + 1;

                                    // Tentukan warna badge berdasarkan urutan rank
                                    $badgeClass = ($rank == 1) ? 'rank-1' : (($rank == 2) ? 'rank-2' : (($rank == 3) ? 'rank-3' : 'rank-other'));
                                    $namaSingkat = ucfirst(strtolower(substr($peserta['nama'], 0, 4))) . '...';
                            ?>

                                    <!-- Item Baris Leaderboard -->
                                    <div class="leaderboard-row d-flex align-items-center">
                                        <div class="rank-badge <?= $badgeClass ?> me-4">
                                            <?= $rank ?>
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1">
                                            <span class="text-white fw-bold fs-6"><?= $namaSingkat ?></span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="text-warning fw-bold fs-5"><?= $peserta['nilai'] ?></span>
                                        </div>
                                    </div>

                            <?php
                                endforeach;
                            endfor;
                            ?>

                        </div>
                    </div>
                    <!-- Akhir Area Scroll -->

                </div>
            </div>
        <?php endif; ?>
        <!-- AKHIR KOLOM KANAN -->

    </div>
</div>
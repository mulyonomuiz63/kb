<?php
// Data Dummy Campuran (Gambar & Teks)
$banners = [
    [
        'type'        => 'image',
        'url_gambar'  => base_url('assets-landing/images/slider/team-3.jpg'),
        'link_tujuan' => base_url('sw-siswa/materi/baru'),
    ],
    [
        'type'       => 'color',
        'background' => 'bg-danger',
        'icon'       => 'ki-outline ki-notification-on',
        'title'      => 'Try Out Akbar Minggu Ini!',
        'desc'       => 'Persiapkan diri Anda untuk simulasi ujian sertifikasi serentak.',
        'button_text' => 'Lihat Jadwal',
        'button_url' => base_url('sw-siswa/ujian'),
    ]
];
?>

<div class="mb-10">
    <div id="kt_banner_slider" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">

        <div class="carousel-indicators mb-n5">
            <?php foreach ($banners as $index => $banner): ?>
                <button type="button" data-bs-target="#kt_banner_slider" data-bs-slide-to="<?= $index ?>" class="<?= $index == 0 ? 'active' : '' ?>"></button>
            <?php endforeach; ?>
        </div>

        <div class="carousel-inner rounded-4 shadow-sm">
            <?php foreach ($banners as $index => $banner): ?>
                <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">

                    <?php if (isset($banner['type']) && $banner['type'] == 'color'): ?>
                        <div class="d-flex align-items-center <?= $banner['background'] ?> rounded-4 p-8 p-lg-12 h-200px h-md-250px h-lg-300px overflow-hidden position-relative shadow-sm hover-elevate-up">

                            <span class="position-absolute top-0 start-100 translate-middle badge badge-circle bg-white opacity-10 w-200px h-200px"></span>
                            <span class="position-absolute bottom-0 start-0 translate-middle badge badge-circle bg-black opacity-5 w-150px h-150px"></span>

                            <div class="flex-grow-1 me-5 z-index-2 position-relative">
                                <span class="badge badge-light-white text-white opacity-75 fw-bold mb-2 text-uppercase ls-1 fs-9">Pengumuman</span>

                                <h2 class="text-white fw-bolder mb-3 fs-2hx ls-n1"><?= $banner['title'] ?></h2>

                                <p class="text-white opacity-75 fs-5 fw-medium d-none d-md-block mb-6 max-w-400px">
                                    <?= $banner['desc'] ?>
                                </p>

                                <a href="<?= $banner['button_url'] ?>" class="btn btn-sm btn-white btn-color-gray-800 fw-bold px-6 py-3 shadow-sm border-0">
                                    <?= $banner['button_text'] ?>
                                </a>
                            </div>

                            <div class="z-index-1 position-relative d-none d-md-block ms-5">
                                <i class="<?= $banner['icon'] ?> text-white opacity-20 fs-10x animate-float"></i>
                            </div>
                        </div>

                    <?php else: ?>
                        <a href="<?= $banner['link_tujuan'] ?>">
                            <div class="d-block w-100 h-200px h-md-250px h-lg-300px rounded-4"
                                style="background-image: url('<?= $banner['url_gambar'] ?>'); background-size: cover; background-position: center;">
                            </div>
                        </a>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        </div>

        <button class="carousel-control-prev w-50px" type="button" data-bs-target="#kt_banner_slider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-dark bg-opacity-25 rounded-circle" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next w-50px" type="button" data-bs-target="#kt_banner_slider" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-dark bg-opacity-25 rounded-circle" aria-hidden="true"></span>
        </button>
    </div>
</div>
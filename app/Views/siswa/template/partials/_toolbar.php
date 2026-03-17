<div id="kt_app_toolbar" class="app-toolbar py-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex align-items-start">
        <div class="d-flex flex-column flex-row-fluid">

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

            <div class="d-flex flex-stack flex-wrap flex-lg-nowrap gap-4 gap-lg-10 pt-10 pb-6">
                <div class="page-title me-5">
                    <h1 class="page-heading d-flex text-white fw-bold fs-2x flex-column justify-content-center my-0">
                        Hai, <?= session()->get('nama') ?>! 👋
                        <span class="page-desc text-gray-200 fw-semibold fs-5 pt-3">
                            Semangat belajar hari ini! Mari selesaikan modul Anda dan raih sertifikat kompetensi.
                        </span>
                    </h1>
                </div>
            </div>

            <?php if (isset($show_banner) && $show_banner === true): ?>
                <?= $this->include('siswa/template/partials/_slider_toolbar') ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">

                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('sw-admin') ?>" class="text-muted text-hover-primary">
                        <i class="ki-outline ki-home text-muted fs-6 text-hover-primary"></i>
                    </a>
                </li>

                <?php if (isset($breadcrumbs) && is_array($breadcrumbs)): ?>
                    <?php foreach ($breadcrumbs as $item): ?>

                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>

                        <li class="breadcrumb-item text-muted">
                            <?php if ($item['url'] !== '#'): ?>
                                <a href="<?= $item['url'] ?>" class="text-muted text-hover-primary">
                                    <?= esc($item['title']) ?>
                                </a>
                            <?php else: ?>
                                <span class="text-gray-900"><?= esc($item['title']) ?></span>
                            <?php endif; ?>
                        </li>

                    <?php endforeach; ?>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</div>
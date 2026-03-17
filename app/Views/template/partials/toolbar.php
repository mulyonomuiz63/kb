<ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>"><i class="bi bi-house-door"></i></a></li>
    
    <?php if (isset($parent_url)) : ?>
        <li class="breadcrumb-item">
            <a href="<?= $parent_url ?>" class="text-muted"><?= $parent_title ?></a>
        </li>
    <?php endif; ?>

    <li class="breadcrumb-item active text-primary font-weight-bold" aria-current="page">
        <?= $title ?? 'Dashboard'; ?>
    </li>
</ol>

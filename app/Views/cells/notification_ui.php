<div id="notification-icon-wrapper" class="btn btn-icon btn-custom btn-active-color-primary w-35px h-35px w-md-40px h-md-40px position-relative"
    data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
    data-kt-menu-attach="parent"
    data-kt-menu-placement="bottom-end">

    <i class="ki-outline ki-notification-on fs-1"></i>

    <?php if ($unread_count > 0): ?>
        <span class="position-absolute top-0 start-100 translate-middle badge badge-circle badge-danger w-15px h-15px ms-n3 mt-3 fs-9">
            <?= $unread_count > 9 ? '9+' : $unread_count ?>
        </span>
    <?php endif; ?>
</div>
<div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true" id="kt_menu_notifications">
    <div class="d-flex flex-column bgi-no-repeat rounded-top" style="background-image:url('<?= base_url('assets/peserta/media/misc/menu-header-bg.jpg') ?>'); background-size: cover;">
        <h3 class="text-white fw-semibold px-9 mt-10 mb-6">
            Notifications
            <span class="fs-8 opacity-75 ps-3"><?= $unread_count ?> laporan baru</span>
        </h3>
    </div>
    <hr class="text-gray-200 border-2 my-0" />
    <div class="scroll-y mh-325px my-5 px-8">
        <?php if (!empty($notifications)): ?>
            <?php foreach ($notifications as $notif): ?>
                <div class="d-flex flex-stack py-4 border-bottom border-gray-200 border-bottom-dashed notification-item"
                    data-uuid="<?= $notif['id'] ?>"
                    data-link="<?= $notif['link'] ?>"
                    style="cursor: pointer;">

                    <div class="d-flex align-items-center">
                        <div class="mb-0 me-2">
                            <a href="<?= $notif['link'] ?>" 
                               class="fs-6 text-gray-800 text-hover-primary fw-bold btn-mark-read" 
                               data-id="<?= $notif['id'] ?>">
                                <?= esc($notif['title']) ?>
                            </a>
                            <div class="text-gray-500 fs-7 d-block"><?= esc($notif['message']) ?></div>
                            <span class="badge badge-success fs-9"><?= timeAgo($notif['created_at']) ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-10 text-gray-400">Tidak ada notifikasi baru</div>
        <?php endif; ?>
    </div>

    <div class="py-3 text-center border-top">
        <button id="btn-read-all" class="btn btn-sm btn-light-primary">
            Tandai Semua Sudah Dibaca
        </button>
    </div>
</div>
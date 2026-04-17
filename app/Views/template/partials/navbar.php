<div id="kt_header" class="header">
    <div class="header-top d-flex align-items-stretch flex-grow-1">
        <div class="d-flex container-xxl align-items-stretch">

            <div class="d-flex align-items-center align-items-lg-stretch me-5 flex-row-fluid">
                <button class="d-lg-none btn btn-icon btn-color-white bg-hover-white bg-hover-opacity-10 w-35px h-35px h-md-40px w-md-40px ms-n3 me-2" id="kt_header_navs_toggle">
                    <i class="ki-duotone ki-abstract-14 fs-2"><span class="path1"></span><span class="path2"></span></i>
                </button>

                <?php if (session()->get('id')): ?>
                    <a href="<?= base_url('/') ?>" class="d-flex align-items-center mt-3">
                        <img alt="<?= setting('app_name') ?>" src="<?= base_url('assets-landing/images/logo-putih.png') ?>" class="h-25px d-none d-sm-block" />
                    </a>
                <?php endif; ?>

                <div class="align-self-end overflow-auto" id="kt_brand_tabs">
                    <div class="header-tabs overflow-auto mx-4 ms-lg-10 mb-5 mb-lg-0" id="kt_header_tabs" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_header_navs_wrapper', lg: '#kt_brand_tabs'}">
                        <ul class="nav flex-nowrap text-nowrap">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#kt_header_navs_tab_1">Menu Utama</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center flex-row-auto">
                <div class="d-flex align-items-center ms-1">
                    <div class="btn btn-icon btn-color-white bg-hover-white bg-hover-opacity-10 w-35px h-35px h-md-40px w-md-40px position-relative"
                        data-kt-menu-trigger="click"
                        data-kt-menu-attach="parent"
                        data-kt-menu-placement="bottom-end">

                        <i class="ki-duotone ki-message-text-2 fs-2">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                        </i>

                        <span id="main-notif-badge"
                            class="bullet bullet-dot bg-success h-6px w-6px position-absolute translate-middle top-0 start-50 animation-blink d-none">
                        </span>
                    </div>

                    <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true">
                        <div class="d-flex flex-column bgi-no-repeat rounded-top" style="background-image:url('<?= base_url('assets/admin/media/misc/menu-header-bg.jpg') ?>')">
                            <div class="d-flex align-items-center fw-semibold px-9 mt-10 mb-10">
                                <h3 class="text-white fw-bold mb-0">Notifikasi</h3>
                                <span id="header-unread-count" class="badge badge-sm badge-light-danger ms-3">0 Baru</span>
                            </div>
                        </div>

                        <div class="scroll-y mh-325px my-5 px-8" id="notification-list">
                            <div id="notif-loader" class="text-center py-10">
                                <span class="spinner-border text-primary"></span>
                            </div>
                        </div>

                        <div class="py-3 text-center border-top">
                            <button id="btn-read-all" class="btn btn-color-gray-600 btn-active-color-primary fw-bold">
                                Tandai Semua Dibaca
                                <i class="ki-duotone ki-arrow-right fs-5">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center ms-1">
                    <a href="#" class="btn btn-icon btn-color-white bg-hover-white bg-hover-opacity-10 w-35px h-35px h-md-40px w-md-40px" data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                        <i class="ki-duotone ki-night-day theme-light-show fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span></i>
                        <i class="ki-duotone ki-moon theme-dark-show fs-2"><span class="path1"></span><span class="path2"></span></i>
                    </a>
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                                <span class="menu-icon" data-kt-element="icon"><i class="ki-duotone ki-night-day fs-2"><span class="path1"></span><span class="path2"></span></i></span>
                                <span class="menu-title">Light</span>
                            </a>
                        </div>
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                                <span class="menu-icon" data-kt-element="icon"><i class="ki-duotone ki-moon fs-2"><span class="path1"></span><span class="path2"></span></i></span>
                                <span class="menu-title">Dark</span>
                            </a>
                        </div>
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                                <span class="menu-icon" data-kt-element="icon"><i class="ki-duotone ki-screen fs-2"><span class="path1"></span><span class="path2"></span></i></span>
                                <span class="menu-title">System</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center ms-1" id="kt_header_user_menu_toggle">
                    <div class="btn btn-flex align-items-center bg-hover-white bg-hover-opacity-10 py-2 ps-2 pe-2 me-n2" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                        <div class="d-none d-md-flex flex-column align-items-end justify-content-center me-2 me-md-4">
                            <span class="text-white opacity-75 fs-8 fw-semibold lh-1 mb-1">
                                <?php if (session()->get('role') == '1'): ?>
                                    Admin
                                <?php elseif (session()->get('role') == '3'): ?>
                                    Pengajar
                                <?php elseif (session()->get('role') == '4'): ?>
                                    Mitra
                                <?php else: ?>
                                    PIC
                                <?php endif; ?>
                            </span>
                            <span class="text-white fs-8 fw-bold lh-1"><?= session()->get('nama') ?></span>
                        </div>
                        <div class="symbol symbol-30px symbol-md-40px">
                            <?= img_lazy('assets/app-assets/user/' . session()->get('avatar'), "loading", ['class' => 'bg-white rounded']) ?>
                        </div>
                    </div>
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">

                        <div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <div class="symbol symbol-50px me-5">
                                    <?php
                                    $avatar = session()->get('avatar') ?: 'default.jpg';
                                    ?>
                                    <?= img_lazy('assets/app-assets/user/' . session()->get('avatar'), "loading", ['class' => 'rounded object-fit-cover']) ?>
                                </div>

                                <div class="d-flex flex-column">
                                    <div class="fw-bold d-flex align-items-center fs-5">
                                        <?= session()->get('nama') ?>
                                    </div>

                                    <div class="mt-1">
                                        <?php if (session()->get('role') == '1'): ?>
                                            <span class="badge badge-light-danger fw-bold fs-9 px-2 py-1">ADMIN</span>
                                        <?php elseif (session()->get('role') == '3'): ?>
                                            <span class="badge badge-light-success fw-bold fs-9 px-2 py-1">PENGAJAR</span>
                                        <?php elseif (session()->get('role') == '4'): ?>
                                            <span class="badge badge-light-warning fw-bold fs-9 px-2 py-1">MITRA</span>
                                        <?php else: ?>
                                            <span class="badge badge-light-info fw-bold fs-9 px-2 py-1">PIC</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="separator my-2"></div>
                        <?php if(session()->get('role') === '1'): ?>
                            <?php $swurl =  'sw-admin' ?>
                        <?php elseif(session()->get('role') === '3'): ?>
                            <?php $swurl =  'sw-guru' ?>
                        <?php elseif(session()->get('role') === '4'): ?>
                            <?php $swurl =  'sw-mitra' ?>
                        <?php else: ?>
                            <?php $swurl =  'sw-pic' ?>
                        <?php endif; ?>
                        <div class="menu-item px-5">
                            <a href="<?= base_url($swurl . '/profile') ?>" class="menu-link px-5">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-address-book fs-2">
                                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Profil Saya</span>
                            </a>
                        </div>

                        <div class="menu-item px-5 my-1">
                            <?php if(session()->get('role') === '1'): ?>
                                <a href="<?= base_url($swurl . '/settings') ?>" class="menu-link px-5">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-setting-2 fs-2">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">Pengaturan Akun</span>
                                </a>
                            <?php endif ?>
                        </div>

                        <div class="separator my-2"></div>

                        <div class="menu-item px-5">
                            <a href="<?= base_url('logout') ?>" class="menu-link px-5 text-danger">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-entrance-right fs-2 text-danger">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                </span>
                                <span class="menu-title text-danger">Keluar Aplikasi</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(session()->get('role') === '1'): ?>
        <?= $this->include('template/partials/admin') ?>
    <?php elseif(session()->get('role') === '3'): ?>
        <?= $this->include('template/partials/pengajar') ?>
    <?php elseif(session()->get('role') === '4'): ?>
        <?= $this->include('template/partials/mitra') ?>
    <?php else: ?>
        <?= $this->include('template/partials/pic') ?>
    <?php endif; ?>
</div>
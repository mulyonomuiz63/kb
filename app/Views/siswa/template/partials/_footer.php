<div id="kt_app_footer" class="app-footer d-flex flex-column flex-md-row align-items-center flex-center flex-md-stack py-2 py-lg-4">
    <!--begin::Copyright-->
    <div class="text-dark order-2 order-md-1">
        <span class="fw-semibold me-1"><?= setting('tahun_berdiri') ?> &copy;</span>
        <a href="<?= base_url() ?>" target="_blank" class="text-gray-800 text-hover-primary"><?= setting('app_name') ?> </a>
         | Hak Cipta Dilindungi Undang-Undang.
    </div>
    <!--end::Copyright-->
    <!--begin::Menu-->
    <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
        <li class="menu-item">
            <a href="<?= base_url('terms') ?>" target="_blank" class="menu-link px-2">Terms of Conditions</a>
        </li>
        <li class="menu-item">
            <a href="<?= base_url('privasi') ?>" target="_blank" class="menu-link px-2">Privacy Policy</a>
        </li>
    </ul>
    <!--end::Menu-->
</div>
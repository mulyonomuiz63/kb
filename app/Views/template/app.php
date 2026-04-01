<!DOCTYPE html>
<html lang="en">
<?php
$db = Config\Database::connect();
$nav_imgs = $db->query("SELECT file FROM iklan where status_iklan = 'nav' LIMIT 1 ");
$nav_img = $nav_imgs->getRowObject();
?>
<?php
if (!empty($nav_img)):
    $image_nav = base_url('uploads/iklan/thumbnails/' . $nav_img->file);
else:
    $image_nav = base_url('uploads/nav-sidebar/top.jpg');
endif;
?>

<?php
$uri = service('request')->getUri();
// Memberikan string kosong jika segmen tidak ada
$seg1 = $uri->getSegment(1, '');
$seg2 = $uri->getSegment(2, '');

$url_aktif = trim($seg1 . '/' . $seg2, '/');
$url_template = "sw-siswa/lihat_pg";
$data_view = [
    'url_aktif'    => uri_string(),
    'url_template' => 'sw-siswa/lihat_pg',
    'image_nav'    => $image_nav
];

// Ini akan membuat $url_aktif tersedia di SEMUA include
$this->setData($data_view);
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title><?= title(); ?></title>
    
    <?= $this->include('template/partials/css/custom') ?>
    <?= $this->include('template/partials/css/styles') ?>
    <?= $this->renderSection('styles'); ?>
</head>
<body id="body" class="sidebar-noneoverflow">
    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->
    <!--  BEGIN NAVBAR  -->
    <div class="header-container sticky-top">
        <?= $this->include('template/header/index') ?>
    </div>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">
        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!-- Content -->
        <?php if (session()->get('role') == '1'): ?>
            <?= $this->include('template/sidebar/admin'); ?>
        <?php elseif (session()->get('role') == '2'): ?>
            <?= $this->include('template/sidebar/siswa'); ?>
        <?php elseif (session()->get('role') == '3'): ?>
            <?= $this->include('template/sidebar/guru'); ?>
        <?php elseif (session()->get('role') == '4'): ?>
            <?= $this->include('template/sidebar/mitra'); ?>
        <?php else: ?>
            <?= $this->include('template/sidebar/pic'); ?>
        <?php endif; ?>

        <div id="content" class="main-content">
            <?= $this->include('template/partials/toolbar'); ?>
            <?= $this->renderSection('content'); ?>
            <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                    <p class="terms-conditions"><?= copyright() ?></p>
                </div>
                <div class="footer-section f-section-2">

                </div>
            </div>
        </div>

        <!-- END MAIN CONTAINER -->
        <script>
            var csrfName = '<?= csrf_token() ?>';
            var csrfHash = '<?= csrf_hash() ?>';
        </script>
        <?= $this->include('template/partials/js/scripts') ?>
        <?= $this->renderSection('scripts'); ?>
        <?= $this->include('template/partials/js/bgcolor') ?>
        <?= $this->include('template/partials/js/lazy') ?>
        <?= $this->include('template/partials/js/chat') ?>
        <?= $this->include('template/partials/js/notification') ?>

        <script>
            $(document).ready(function() {

                // 1. GLOBAL FOCUS PROTECTION
                // Menangkap klik pada semua tombol penutup modal (close, cancel, X)
                $(document).on('click', '[data-dismiss="modal"], .close, .btn-secondary, .btn-light', function() {
                    // Pindahkan fokus ke body sebelum Bootstrap sempat memberikan aria-hidden
                    document.body.focus();
                });

                // 2. GLOBAL MUTATION OBSERVER (Anti Aria-Hidden)
                // Fungsi ini akan memantau SEMUA modal yang muncul di DOM
                const globalModalObserver = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        // Jika ada perubahan atribut pada elemen dengan class .modal
                        if (mutation.type === 'attributes' && mutation.attributeName === 'aria-hidden') {
                            const target = mutation.target;
                            if (target.getAttribute('aria-hidden') === 'true') {
                                // Hapus paksa aria-hidden agar tidak bentrok dengan fokus
                                target.removeAttribute('aria-hidden');
                            }
                        }
                    });
                });

                // Jalankan observer pada seluruh body untuk memantau penambahan/perubahan modal
                globalModalObserver.observe(document.body, {
                    attributes: true,
                    subtree: true, // Pantau seluruh elemen di dalam body (global)
                    attributeFilter: ['aria-hidden'] // Hanya pantau atribut ini agar performa tetap ringan
                });

                // 3. GLOBAL MODAL CLEANUP (Event Based)
                $(document).on('show.bs.modal hide.bs.modal hidden.bs.modal', '.modal', function() {
                    // Pastikan modal dan pembungkusnya tidak pernah memiliki aria-hidden
                    $(this).removeAttr('aria-hidden');
                    $('.layout-px-spacing, .main-container, body').removeAttr('aria-hidden');
                });

            });
        </script>

</body>


</html>
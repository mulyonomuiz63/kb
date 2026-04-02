<base href="" />
<title><?= setting('app_name') ?></title>
<meta charset="utf-8" />
<meta name="description" content="The most advanced Bootstrap 5 Admin Theme with 40 unique prebuilt layouts on Themeforest trusted by 100,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel versions. Grab your copy now and get life-time updates for free." />
<meta name="keywords" content="metronic, bootstrap, bootstrap 5, angular, VueJs, React, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel starter kits, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="article" />
<meta property="og:title" content="<?= setting('app_name') ?>" />
<meta property="og:url" content="<?= base_url() ?>" />
<meta property="og:site_name" content="<?= setting('app_name') ?>" />
<link rel="canonical" href="<?= base_url() ?>" />
<link rel="shortcut icon" href="<?= base_url() . favicon() ?>" />
<!--begin::Fonts(mandatory for all pages)-->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
<!--end::Fonts-->
<!--begin::Vendor Stylesheets(used for this page only)-->
<link href="<?= base_url() ?>assets/peserta/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/peserta/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<!--end::Vendor Stylesheets-->
<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
<link href="<?= base_url() ?>assets/peserta/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/peserta/css/style.bundle.css" rel="stylesheet" type="text/css" />

<!-- midtrans -->
 <?php if (strtolower(setting('midtrans_is_production')) == 'true'): ?>
    <script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="<?= setting('midtrans_client_key') ?>"></script>
<?php else: ?>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= setting('midtrans_client_key') ?>"></script>
<?php endif; ?>
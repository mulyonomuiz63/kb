<title><?= setting('app_name') ?></title>
<meta charset="utf-8" />
<meta name="description" content="<?= setting('site_description') ?>" />
<meta name="keywords" content="<?= setting('site_keywords') ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="article" />
<meta property="og:title" content="<?= setting('app_name') ?>" />
<meta property="og:url" content="<?= base_url() ?>" />
<meta property="og:site_name" content="<?= setting('app_name') ?>" />
<link rel="canonical" href="<?= base_url() ?>" />
<link rel="shortcut icon" href="<?= base_url() . favicon() ?>" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
<link href="<?= base_url('assets/admin/plugins/custom/fullcalendar/fullcalendar.bundle.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('assets/admin/plugins/custom/datatables/datatables.bundle.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('assets/admin/plugins/global/plugins.bundle.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('assets/admin/css/style.bundle.css') ?>" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script>
    // Frame-busting to prevent site from being loaded within a frame without permission
    if (window.top != window.self) { window.top.location.replace(window.self.location.href); }
</script>
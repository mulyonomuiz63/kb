<?php
$assets = base_url('assets/app-assets/template/cbt-malela');
?>
<link rel="icon" type="image/x-icon" href="<?= base_url(favicon()); ?>" />
<link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&amp;display=swap" rel="stylesheet">
<link href="<?= $assets; ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?= $assets; ?>/assets/css/loader.css" rel="stylesheet" type="text/css" />
<link href="<?= $assets; ?>/assets/css/plugins.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="<?= $assets; ?>/plugins/table/datatable/datatables.css" rel="stylesheet" type="text/css">
<link href="<?= $assets; ?>/plugins/animate/animate.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="<?= $assets; ?>/assets/css/elements/infobox.css" rel="stylesheet" type="text/css" />


<style>
    /* Menghilangkan padding berlebih pada wrapper datatables */
    #datatables-list_wrapper.container-fluid {
        padding: 0 !important;
    }

    /* Memastikan baris kontrol (search/length) sejajar dengan header widget */
    .dt--top-section {
        margin-bottom: 20px !important;
        padding: 0 !important;
    }

    /* Menghilangkan margin pada row di dalam datatables agar sejajar dengan div widget */
    #datatables-list_wrapper .row {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    /* Memastikan header tabel rata kiri */
    table.dataTable thead th {
        padding-left: 15px !important;
    }
</style>
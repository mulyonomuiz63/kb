<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?= $this->include('template/sidebar/pic'); ?>
<?php $db = Config\Database::connect(); ?>

<!--  BEGIN CONTENT AREA  -->

<!--  END CONTENT AREA  -->

<?= $this->endSection(); ?>
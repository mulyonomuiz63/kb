<?= $this->extend('template/app'); ?>
<?= $this->section('styles'); ?>
<style>
    /* Custom CSS untuk halaman create artikel */
    .select2-container--bootstrap5 .select2-selection--single,
    .select2-container--bootstrap5 .select2-selection--multiple {
        min-height: calc(1.5em + 1.65rem + 2px) !important;
        padding: 0.4rem 1rem !important;
    }
</style>
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>
<?php $db = Config\Database::connect(); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card card-flush shadow-sm border-0">
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatable-list" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Nama Paket</th>
                                    <th class="text-center min-w-100px">Jenis</th>
                                    <th class="text-center min-w-100px">Diskon</th>
                                    <th class="text-end min-w-150px">Nominal</th>
                                    <th class="text-center min-w-100px">Status</th>
                                    <th class="text-center min-w-100px">Komisi</th>
                                    <th class="text-center min-w-150px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-paket" class="fw-semibold text-gray-600">
                                <?php foreach ($paket as $s) : ?>
                                    <tr data-id="<?= $s->idpaket ?>" style="cursor: grab;">
                                        <td class="text-gray-800 fw-bold text-wrap pl-3">
                                            <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-element-11 fs-4 text-muted me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                                <?= $s->nama_paket; ?>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-muted"><?= $s->tagline; ?></span>
                                        </td>
                                        <td class="text-center fw-bold text-danger">
                                            <?= $s->diskon; ?>%
                                        </td>
                                        <td class="text-end fw-bold text-gray-900">
                                            Rp <?= number_format($s->nominal_paket, 0, '.', '.'); ?>
                                        </td>
                                        <td class="text-center">
                                            <?= $s->status == 1
                                                ? '<span class="badge badge-light-success fw-bold px-3 py-2">Aktif</span>'
                                                : '<span class="badge badge-light-danger fw-bold px-3 py-2">Non-Aktif</span>'; ?>
                                        </td>
                                        <td class="text-center text-primary fw-bold">
                                            <?= $s->komisi ?>%
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <?php if ($s->v_ujian == 1 && $s->v_materi == 0): ?>
                                                    <a href="<?= base_url("sw-admin/paket/review/" . $s->slug) ?>" class="btn btn-icon btn-light-info btn-sm" data-bs-toggle="tooltip" title="Review">
                                                        <i class="ki-duotone ki-eye fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {

        // --- Init DataTables ---
        var table = $('#datatable-list').DataTable({
            "ordering": false,
        });
    });
</script>
<?= $this->endSection(); ?>
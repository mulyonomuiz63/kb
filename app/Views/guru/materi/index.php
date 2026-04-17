<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?php $db = Config\Database::connect(); ?>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="card-body py-4">
            <div class="table-responsive">
                <table id="datatables-list" class="table align-middle table-row-dashed fs-6 gy-5">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-150px">Mapel</th>
                            <th class="min-w-100px">Jumlah Materi</th>
                            <th class="min-w-100px">Kelas</th>
                            <th class="text-end min-w-70px">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-semibold">
                        <?php foreach ($mapel as $m) : ?>
                            <tr>
                                <td>
                                    <span class="text-gray-800 text-hover-primary fw-bold"><?= $m->nama_mapel; ?></span>
                                </td>

                                <?php $jml_materi = $db->query("select count(*) as total_materi from materi where mapel = '$m->mapel'")->getRow(); ?>
                                <td>
                                    <div class="badge badge-light-primary fw-bold">
                                        <?= !empty($jml_materi->total_materi) ? $jml_materi->total_materi : 0; ?> Materi
                                    </div>
                                </td>

                                <td>
                                    <span class="badge badge-light-success"><?= $m->nama_kelas ?></span>
                                </td>

                                <td class="text-end">
                                    <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        Aksi
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                    </a>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <a href="<?= base_url('sw-guru/materi/lihat') . '/' . encrypt_url($m->mapel) . '/' . encrypt_url($m->kelas); ?>" class="menu-link px-3">
                                                Lihat Materi
                                            </a>
                                        </div>
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
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable tetap berjalan sesuai fungsi asli
        $('#datatables-list').DataTable({
            "ordering": false,
            "lengthChange": false,
            "pageLength": 10,
            "info": false,
            // Menyesuaikan DOM DataTable dengan style Metronic
            "dom": "<'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>"
        });
    });
</script>
<?= $this->endSection(); ?>
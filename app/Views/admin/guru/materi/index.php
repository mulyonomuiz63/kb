<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?php $db = Config\Database::connect(); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="card card-flush shadow-sm">
                
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Daftar Mata Pelajaran</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Mata pelajaran yang diampu oleh instruktur</span>
                    </h3>
                </div>

                <div class="card-body pt-0 mt-3">
                    <div class="table-responsive">
                        <table id="datatable-table" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Mata Pelajaran</th>
                                    <th class="min-w-150px text-center">Jumlah Materi</th>
                                    <th class="min-w-150px text-center">Kelas</th>
                                    <th class="text-end min-w-100px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                <?php foreach ($mapel as $m) : ?>
                                    <tr>
                                        <td>
                                            <span class="text-gray-800 fw-bold d-block fs-6"><?= $m->nama_mapel; ?></span>
                                        </td>
                                        
                                        <?php  $jml_materi = $db->query("select count(*) as total_materi from materi where mapel = '$m->mapel'")->getRow(); ?>
                                        <?php $totalMateri = !empty($jml_materi->total_materi) ? $jml_materi->total_materi : 0; ?>
                                        
                                        <td class="text-center">
                                            <span class="badge badge-light-info fw-bold px-3 py-2 fs-7">
                                                <?= $totalMateri; ?> Materi
                                            </span>
                                        </td>
                                        
                                        <td class="text-center">
                                            <span class="badge badge-light-primary fw-bold px-3 py-2 fs-7">
                                                <?= $m->nama_kelas ?>
                                            </span>
                                        </td>
                                        
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                Aksi <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                            </a>
                                            
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4 text-start" data-kt-menu="true">
                                                <div class="menu-item px-3">
                                                    <a href="<?= base_url('sw-admin/guru/lihat-materi') . '/' . encrypt_url($m->mapel). '/' . encrypt_url($m->kelas). '/' .$idGuru; ?>" class="menu-link px-3">
                                                        <i class="ki-duotone ki-eye text-primary fs-4 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> Lihat Materi
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
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Inisialisasi DataTables dasar agar pagination dan stylingnya selaras dengan Metronic
        $('#datatable-table').DataTable({
            "ordering": false,
            "drawCallback": function(settings) {
                // Inisialisasi ulang KTMenu jika tabel memiliki fitur paging/search
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            }
        });
    });
</script>
<?= $this->endSection(); ?>
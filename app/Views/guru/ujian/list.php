<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?php $db = Config\Database::connect(); ?>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <div class="card card-flush shadow-sm">
            <div class="card-header border-0 pt-6">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" data-kt-ujian-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Cari Ujian..." />
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= base_url('sw-guru/ujian/create'); ?>" class="btn btn-primary btn-sm">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah Ujian
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body py-4">
                <div class="table-responsive">
                    <table id="datatable-list" class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-150px">Nama Ujian</th>
                                <th class="min-w-100px">Kelas</th>
                                <th class="min-w-100px">Status</th>
                                <th class="text-end min-w-100px">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            <?php foreach ($ujian as $u) : ?>
                                <tr>
                                    <td>
                                        <span class="text-gray-800 fw-bold"><?= $u->nama_ujian; ?></span>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-info fw-bold"><?= $u->nama_kelas; ?></span>
                                    </td>
                                    <td>
                                        <form action="<?= base_url('sw-guru/ujian/ubah-status-ujian'); ?>" method="POST">
                                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                            <input type="hidden" name="kode_ujian" value="<?= $u->kode_ujian  ?>">
                                            <?php $data = $db->query("select * from status_ujian where kode_ujian = '$u->kode_ujian'")->getRow(); ?>

                                            <?php if (!empty($data) && $data->status == 'A'): ?>
                                                <button type="submit" class="btn btn-sm btn-light-success fw-bold px-4 py-1">Aktif</button>
                                            <?php else: ?>
                                                <button type="submit" class="btn btn-sm btn-light-danger fw-bold px-4 py-1">Tidak Aktif</button>
                                            <?php endif; ?>
                                        </form>
                                    </td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Aksi <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                        </a>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <?php if ($u->jenis_ujian == 1) : ?>
                                                    <a href="<?= base_url('sw-guru/ujian/lihat-essay/') . encrypt_url($u->kode_ujian); ?>" class="menu-link px-3">Lihat</a>
                                                <?php else : ?>
                                                    <a href="<?= base_url('sw-guru/ujian/lihat-ujian/') . encrypt_url($u->kode_ujian); ?>" class="menu-link px-3">Lihat</a>
                                                <?php endif; ?>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a href="<?= base_url('sw-guru/ujian/edit-ujian/') . encrypt_url($u->kode_ujian); ?>" class="menu-link px-3">Edit</a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a target="_blank" href="<?= base_url('sw-guru/ujian/cetak-soal/') . encrypt_url($u->kode_ujian); ?>" class="menu-link px-3 text-primary">Cetak PDF</a>
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

<div class="modal fade" id="tambah_ujian" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-400px">
        <form action="" method="POST" enctype="multipart/form-data" class="w-100">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Tambah Ujian</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body text-center py-10">
                    <a href="<?= base_url('guru/tambah_pg'); ?>" class="btn btn-primary me-3">Pilihan Ganda</a>
                    <a href="<?= base_url('guru/tambah_essay'); ?>" class="btn btn-light-primary">Essay</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section("scripts"); ?>
<script>
    $(document).ready(function() {
        var table = $('#datatable-list').DataTable({
            "ordering": false,
            "lengthChange": false,
            "pageLength": 10,
            "info": false,
            "dom": "rtp", // Menyembunyikan search bawaan agar tidak double
            "drawCallback": function(settings) {
                // Re-init KTMenu agar dropdown di dalam baris tabel tetap berfungsi
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            }
        });

        $('[data-kt-ujian-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });
    });
</script>
<?= $this->endSection(); ?>
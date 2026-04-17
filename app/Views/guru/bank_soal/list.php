<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <div class="card card-flush shadow-sm">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" data-kt-bank-soal-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Cari Bank Soal..." />
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end gap-3" data-kt-bank-soal-table-toolbar="base">
                        <a href="<?= base_url('sw-guru/kategori'); ?>" class="btn btn-light-primary fw-bold">
                            <i class="ki-duotone ki-plus-square fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            Kategori
                        </a>
                        <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#tambah_bank_soal">
                            <i class="ki-duotone ki-add-item fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            Tambah Soal
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body py-4">
                <div class="table-responsive">
                    <table id="datatables-list" class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">Nama Soal</th>
                                <th class="text-end min-w-100px">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            <?php foreach ($soal as $u) : ?>
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 text-hover-primary mb-1 fw-bold"><?= $u->nama_soal; ?></span>
                                            <span class="text-muted fs-7">Kategori: <?= $u->nama_kategori; ?></span>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Aksi
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                        </a>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <a href="<?= base_url('sw-guru/bank-soal/edit') . '/' . encrypt_url($u->id_bank_soal); ?>" class="menu-link px-3">Edit</a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a href="javascript:void(0)" data-url="<?= base_url('sw-guru/bank-soal/delete') . '/' . encrypt_url($u->id_bank_soal); ?>" class="menu-link px-3 btn-hapus text-danger btn-delete">Delete</a>
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
<div class="modal fade" id="tambah_bank_soal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-400px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Tambah Soal</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 my-7 text-center">
                <a href="<?= base_url('sw-guru/bank-soal/create'); ?>" class="btn btn-primary me-3">Input Manual</a>
                <a href="javascript:void(0);" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#excel_ujian">Import Excel</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="excel_ujian" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form action="<?= base_url('guru/excel_bank_soal_pg'); ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <div class="modal-header">
                    <h2 class="fw-bold">Import Soal via Excel</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 my-7">
                    <div class="row g-9">
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">File Excel</label>
                            <input type="file" class="form-control form-control-solid" name="excel" accept=".xls, .xlsx" required>
                        </div>
                        <div class="col-md-6 fv-row d-flex flex-column justify-content-end">
                            <label class="fs-6 fw-semibold mb-2">Template</label>
                            <a href="<?= base_url('download/excel_soal_pg'); ?>" class="btn btn-success w-100">
                                <i class="ki-duotone ki-file-down fs-2"><span class="path1"></span><span class="path2"></span></i>
                                Download
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section("scripts"); ?>
<script>
    $(document).ready(function() {
        // 1. Deklarasikan variabel di dalam scope yang bisa diakses
        var table = $('#datatables-list').DataTable({
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

        // 2. Pindahkan event listener ke dalam ready() agar variabel 'table' terbaca
        $('[data-kt-bank-soal-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });
    });
</script>
<?= $this->endSection(); ?>
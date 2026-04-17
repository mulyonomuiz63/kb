<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?php $db = Config\Database::connect(); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-detail-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari data..." />
                        </div>
                    </div>

                    <div class="card-toolbar flex-row-fluid justify-content-end gap-3">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambah_paket">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah Paket
                        </button>
                    </div>

                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatable-list" class="table align-middle table-row-dashed fs-6 gy-5 text-start w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Kode Voucher</th>
                                    <th class="min-w-250px">Nama Paket</th>
                                    <th class="text-end min-w-100px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                <?php foreach ($detailvoucher as $s) : ?>
                                    <tr>
                                        <td>
                                            <span class="badge badge-light-primary fw-bold px-4 py-2 fs-6"><?= $s->kode_voucher; ?></span>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold"><?= $s->nama_paket; ?></span>
                                        </td>
                                        <td class="text-end">
                                            <a href="javascript:void(0)" 
                                                data-url="<?= base_url('sw-admin/mitra/delete-voucher-paket/' . encrypt_url($s->iddetailvoucher) . '/' . encrypt_url($s->idvoucher)); ?>" 
                                                class="btn btn-icon btn-light-danger btn-sm btn-delete" 
                                                data-bs-toggle="tooltip" 
                                                title="Hapus Data">
                                                <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                            </a>
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
<div class="modal fade" id="tambah_paket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded">
            
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h2 class="fw-bold">Tambah Paket ke Voucher</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <form action="<?= base_url('sw-admin/mitra/store-voucher-paket'); ?>" method="POST" class="form">
                <?= csrf_field(); ?>
                <input type="hidden" name="idvoucher" value="<?= $idvoucher ?>">
                
                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-15">
                    
                    <div class="d-flex flex-stack mb-5">
                        <div class="fs-6 fw-semibold text-gray-700">Pilih paket yang akan dihubungkan</div>
                        <button type="button" class="btn btn-sm btn-light-primary tambah-baris-paket">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah Baris
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-3">
                            <tbody id="tbody-paket">
                                <tr>
                                    <td class="ps-0 w-100">
                                        <select name="idpaket[]" class="form-select form-select-solid" required>
                                            <option value="">-- Pilih Paket --</option>
                                            <?php foreach ($paket as $p) : ?>
                                                <option value="<?= $p->idpaket; ?>"><?= $p->nama_paket; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td class="text-end pe-0 align-middle">
                                        <button type="button" class="btn btn-icon btn-light-danger btn-sm disabled" disabled>
                                            <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center pt-10">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Simpan Data</span>
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Inisialisasi DataTables Metronic Style
        var table = $('#datatable-list').DataTable({
            "ordering": false,
        });

        // Fitur Pencarian DataTables (Menghubungkan input custom dengan library datatables)
        $('[data-kt-detail-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

       

        // Tambah Baris Paket (Disesuaikan element desainnya dengan form control solid Metronic)
        $('.tambah-baris-paket').click(function() {
            const row = `
            <tr>
                <td class="ps-0 w-100">
                    <select name="idpaket[]" class="form-select form-select-solid" required>
                        <option value="">-- Pilih Paket --</option>
                        <?php foreach ($paket as $p) : ?>
                            <option value="<?= $p->idpaket; ?>"><?= $p->nama_paket; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td class="text-end pe-0 align-middle">
                    <button type="button" class="btn btn-icon btn-light-danger btn-sm hapus-baris">
                        <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    </button>
                </td>
            </tr>`;
            $('#tbody-paket').append(row);
        });

        // Hapus Baris
        $(document).on('click', '.hapus-baris', function() {
            $(this).closest('tr').remove();
        });
        
    });
</script>
<?= $this->endSection(); ?>
<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        
        <div class="card card-flush shadow-sm">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"></i>
                        <h3 class="card-label fw-bold text-dark">Mitra KelasBrevet</h3>
                    </div>
                </div>
            </div>

            <div class="card-body py-4">
                <div class="table-responsive">
                    <table id="datatables-list" class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">Mitra</th>
                                <th class="min-w-100px">Kode Voucher</th>
                                <th class="min-w-100px text-center">Diskon</th>
                                <th class="min-w-125px">Tgl Pembuatan</th>
                                <th class="min-w-125px">Tgl Expire</th>
                                <th class="min-w-100px">Status</th>
                                <th class="text-end min-w-70px">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            <?php foreach ($voucher as $s) : ?>
                                <tr>
                                    <td>
                                        <span class="text-gray-800 fw-bold"><?= $s->nama_mitra; ?></span>
                                    </td>
                                    <td>
                                        <code class="fw-bold text-primary"><?= $s->kode_voucher; ?></code>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-light-primary fw-bold"><?= $s->diskon_voucher; ?> %</span>
                                    </td>
                                    <td><?= $s->tgl_aktif; ?></td>
                                    <td><?= $s->tgl_exp; ?></td>
                                    <td>
                                        <?php if($s->status == 'A'): ?>
                                            <span class="badge badge-light-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge badge-light-danger">Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?= base_url('sw-mitra/detail-voucher/') . encrypt_url($s->kode_voucher); ?>" 
                                           class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" 
                                           data-bs-toggle="tooltip" 
                                           title="Lihat Detail">
                                            <i class="ki-duotone ki-eye fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
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
            // Styling khusus Metronic untuk pagination
            "dom": "<'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>"
        });
    });
</script>
<?= $this->endSection(); ?>
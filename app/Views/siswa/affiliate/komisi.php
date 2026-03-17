<?= $this->extend('siswa/template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card card-flush shadow-sm">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                <i class="ki-outline ki-magnifier fs-3"></i>
                            </span>
                            <h3 class="fw-bold text-gray-900 m-0">Riwayat Komisi Affiliate</h3>
                        </div>
                    </div>
                    <div class="card-toolbar">
                        <span class="badge badge-light-primary fw-bold px-4 py-3">Total: <?= count($komisi) ?> Transaksi</span>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-100px">Affiliate</th>
                                    <th class="min-w-100px">ID Transaksi</th>
                                    <th class="min-w-125px">Nominal Komisi</th>
                                    <th class="min-w-100px">Status</th>
                                    <th class="min-w-125px">Tanggal</th>
                                    <th class="text-end min-w-70px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                <?php foreach($komisi as $k): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-0">
                                                <span class="text-gray-800 text-hover-primary fs-5 fw-bold"><?= esc($k->kode_affiliate) ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-secondary fw-bold">#<?= esc($k->id_transaksi) ?></span>
                                    </td>
                                    <td>
                                        <span class="text-gray-900 fw-boldest fs-6">
                                            Rp <?= number_format($k->komisi, 0, '.', '.') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($k->status == 'paid'): ?>
                                            <div class="badge badge-light-success fw-bold">PAID</div>
                                        <?php else: ?>
                                            <div class="badge badge-light-warning fw-bold">PENDING</div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="fw-bold"><?= date('d M Y', strtotime($k->created_at)) ?></span>
                                    </td>
                                    <td class="text-end">
                                        <?php if($k->status == 'pending'): ?>
                                            <a href="<?= base_url('admin/affiliate/paid/'.$k->id) ?>" 
                                               class="btn btn-sm btn-light-success btn-flex btn-center btn-active-success"
                                               onclick="return confirm('Tandai komisi ini sebagai sudah dibayar?')">
                                                <i class="ki-outline ki-check-circle fs-5 me-1"></i> Paid
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted fs-7 italic">Selesai</span>
                                        <?php endif ?>
                                    </td>
                                </tr>
                                <?php endforeach ?>
                                
                                <?php if(empty($komisi)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-10">
                                            <div class="d-flex flex-column flex-center">
                                                <i class="ki-outline ki-nodata fs-3x text-gray-300 mb-5"></i>
                                                <span class="text-gray-500 fs-5 fw-semibold">Belum ada data komisi ditemukan.</span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex flex-stack flex-wrap pt-10">
                        <div class="fs-6 fw-semibold text-gray-700">
                            Menampilkan data komisi affiliate
                        </div>
                        <ul class="pagination">
                            </ul>
                    </div>
                </div>
            </div>
            </div>
    </div>
</div>

<style>
    /* Custom style untuk meratakan tampilan sesuai Demo 34 */
    .table.table-row-dashed tr {
        border-bottom-width: 1px;
        border-bottom-style: dashed;
        border-bottom-color: var(--bs-gray-200);
    }
    .badge-light-success {
        background-color: #E8FFF3;
        color: #50CD89;
    }
    .badge-light-warning {
        background-color: #FFF8DD;
        color: #FFC700;
    }
    .fw-boldest {
        font-weight: 800;
    }
</style>

<?= $this->endSection(); ?>
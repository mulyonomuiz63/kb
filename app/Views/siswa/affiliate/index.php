<?= $this->extend('siswa/template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid mt-8">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid py-6">

            <div class="card shadow-sm mb-8">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex flex-column">
                            <h3 class="fw-bolder fs-3 mb-1">Data Affiliate</h3>
                            <span class="text-muted fw-semibold fs-7">Ikuti program affiliate dan dapatkan jutaan rupiah.</span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body py-4">
                    <?php if (!empty($affiliates)): ?>
                        <?php
                            $status = $affiliates->status;
                            $statusMap = [
                                '1' => ['class' => 'badge-light-success', 'label' => 'Approved', 'icon' => 'ki-check-circle'],
                                '0' => ['class' => 'badge-light-warning', 'label' => 'Pending', 'icon' => 'ki-information-2'],
                                '2' => ['class' => 'badge-light-danger', 'label' => 'Rejected', 'icon' => 'ki-cross-circle'],
                            ];
                            $currStatus = $statusMap[$status] ?? ['class' => 'badge-light-secondary', 'label' => 'Unknown', 'icon' => ''];
                        ?>

                        <div class="rounded border border-dashed border-gray-300 p-6">
                            <div class="d-flex flex-stack flex-wrap">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="me-5">
                                        <div class="fs-7 text-muted fw-semibold">Kode Affiliate</div>
                                        <div class="fs-2 fw-bolder text-gray-800"><?= $affiliates->kode_affiliate ?></div>
                                    </div>
                                    <span class="badge <?= $currStatus['class'] ?> fs-7 fw-bold py-3 px-4">
                                        <?= $currStatus['label'] ?>
                                    </span>
                                </div>

                                <div class="d-flex align-items-center">
                                    <?php if ($status === '1' || $status === '2'): ?>
                                        <button class="btn btn-sm btn-light-primary fw-bold me-2" data-bs-toggle="collapse" data-bs-target="#kt_bank_details">
                                            <i class="ki-outline ki-bank fs-4 me-1"></i> Detail Rekening
                                        </button>
                                        <?php if($affiliates->total_edit <= 0): ?>
                                            <a href="<?= base_url('sw-siswa/affiliate/edit/'.$affiliates->id_affiliate) ?>" class="btn btn-sm btn-light-warning fw-bold">
                                                Edit
                                            </a>
                                        <?php endif ?>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-light-danger fw-bold btn-delete" data-id="<?= $affiliates->id_affiliate ?>">
                                            Batalkan Pengajuan
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="collapse mt-5" id="kt_bank_details">
                                <div class="bg-light-primary rounded p-5 border border-primary border-dashed">
                                    <div class="row g-5">
                                        <div class="col-md-3 col-6">
                                            <div class="fs-7 text-muted fw-semibold">Nama Bank</div>
                                            <div class="fs-6 fw-bold text-gray-800"><?= $affiliates->bank ?: '-' ?></div>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="fs-7 text-muted fw-semibold">Cabang</div>
                                            <div class="fs-6 fw-bold text-gray-800"><?= $affiliates->cabang_bank ?: '-' ?></div>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="fs-7 text-muted fw-semibold">No. Rekening</div>
                                            <div class="fs-6 fw-bold text-gray-800"><?= $affiliates->norek ?: '-' ?></div>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="fs-7 text-muted fw-semibold">Atas Nama</div>
                                            <div class="fs-6 fw-bold text-gray-800"><?= $affiliates->nama_akun_bank ?: '-' ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php else: ?>
                        <div class="text-center py-10">
                            <img src="<?= base_url('assets/media/illustrations/sigma-1/5.png') ?>" class="mw-150px mb-5" alt="" />
                            <div class="fs-5 fw-bold text-gray-900 mb-2">Anda belum terdaftar sebagai affiliate</div>
                            <div class="fs-7 text-gray-500 mb-5">Mulai dapatkan penghasilan tambahan dengan bergabung sekarang.</div>
                            <a href="<?= base_url('sw-siswa/affiliate/create') ?>" class="btn btn-primary shadow-sm">
                                <i class="ki-outline ki-plus-square fs-2"></i> Daftar Sekarang
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($affiliates) && $affiliates->status === '1'): ?>
                <?php
                    $totalKomisi = 0;
                    if (!empty($komisi)) {
                        foreach ($komisi as $k) {
                            if (in_array($k['status'], ['approved', 'paid']) && $k['status_penarikan'] === 'pending') {
                                $totalKomisi += ($k['harga'] * $k['komisi'] / 100);
                            }
                        }
                    }
                ?>

                <div class="card shadow-sm border-0">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex flex-column">
                                <h3 class="fw-bolder fs-3 mb-1">Komisi Affiliate</h3>
                                <span class="text-muted fw-semibold fs-7">
                                    <i class="ki-outline ki-information-2 fs-7 me-1"></i> 
                                    Minimal pencairan <strong>Rp 100.000</strong> (Setiap awal bulan)
                                </span>
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex flex-column text-end">
                                <span class="text-muted fs-7 fw-bold text-uppercase ls-1">Total Komisi</span>
                                <span class="text-success fs-2hx fw-bolder">Rp <?= number_format($totalKomisi, 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_komisi">
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="w-50px">No</th>
                                        <th class="min-w-125px">Pemesan</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-center">Komisi</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Pencairan</th>
                                        <th class="text-end">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    <?php if (!empty($komisi)): ?>
                                        <?php $no = 1 + ($pager->getCurrentPage('komisi') - 1) * $pager->getPerPage('komisi'); ?>
                                        <?php foreach ($komisi as $k): ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">
                                                            <?= ucwords(strtolower($k['nama_siswa'])) ?>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="text-end">Rp <?= number_format($k['harga'], 0, ',', '.') ?></td>
                                                <td class="text-center">
                                                    <span class="badge badge-light-success fw-bold"><?= $k['komisi'] ?>%</span>
                                                </td>
                                                <td class="text-end text-gray-800 fw-bolder">
                                                    Rp <?= number_format($k['harga'] * $k['komisi']/100, 0, ',', '.') ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php 
                                                        $statusClass = [
                                                            'approved' => 'badge-light-success',
                                                            'pending'  => 'badge-light-warning',
                                                            'paid'     => 'badge-light-primary',
                                                            'rejected' => 'badge-light-danger'
                                                        ][$k['status']] ?? 'badge-light-secondary';
                                                    ?>
                                                    <span class="badge <?= $statusClass ?> fs-8 fw-bold"><?= ucfirst($k['status']) ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                        $penarikanMap = [
                                                            'pending'    => ['c' => 'badge-light-warning', 't' => 'Pending'],
                                                            'approved'   => ['c' => 'badge-light-primary', 't' => 'Approved'],
                                                            'processing' => ['c' => 'badge-light-info', 't' => 'Processing'],
                                                            'paid'       => ['c' => 'badge-light-success', 't' => 'Paid'],
                                                        ][$k['status_penarikan']] ?? ['c' => 'badge-light-secondary', 't' => '-'];
                                                    ?>
                                                    <span class="badge <?= $penarikanMap['c'] ?> fs-8 fw-bold"><?= $penarikanMap['t'] ?></span>
                                                </td>
                                                <td class="text-end">
                                                    <span class="text-gray-500 fs-7"><?= $k['tgl_pembayaran'] ? date('d M Y', strtotime($k['tgl_pembayaran'])) : '-' ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-10">Belum ada data komisi</td>
                                        </tr>
                                    <?php endif ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex flex-stack flex-wrap pt-10">
                            <div class="fs-6 fw-semibold text-gray-700">
                                Menampilkan <?= count($komisi) ?> data
                            </div>
                            <?php if ($pager): ?>
                                <?= $pager->links('komisi', 'bootstrap') ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(document).on('click', '.btn-delete', function () {
    const id = $(this).data('id');

    Swal.fire({
        title: 'Yakin ingin membatalkan?',
        text: "Pengajuan affiliate ini akan dihapus permanen.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Batalkan!',
        cancelButtonText: 'Tutup',
        customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-active-light"
        }
    }).then(function (result) {
        if (result.isConfirmed) {
            $.post("<?= base_url('sw-siswa/affiliate/delete') ?>", {
                id: id,
                <?= csrf_token() ?>: "<?= csrf_hash() ?>"
            }, function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                }
            }, 'json');
        }
    });
});
</script>
<?= $this->endSection(); ?>
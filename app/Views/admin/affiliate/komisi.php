<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <?php if (!empty($affiliate)): ?>
                <div class="card shadow-sm border-0 border-start border-primary border-4 mb-7">
                    <div class="card-body">
                        
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <h3 class="card-title fw-bold text-gray-800 m-0 d-flex align-items-center">
                                <i class="ki-duotone ki-bank fs-2 text-primary me-2"><span class="path1"></span><span class="path2"></span></i>
                                Informasi Rekening Affiliate
                            </h3>
                            <span class="badge badge-light-success fw-bold px-4 py-2 fs-7">Aktif</span>
                        </div>

                        <div class="row g-5">
                            <div class="col-md-3">
                                <div class="text-muted fw-semibold fs-7 mb-1">Nama Pemilik</div>
                                <div class="fw-bold text-gray-900 fs-5"><?= esc($affiliate['nama_akun_bank']) ?></div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-muted fw-semibold fs-7 mb-1">Bank</div>
                                <div class="fw-bold text-gray-900 fs-5"><?= esc($affiliate['bank']) ?></div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-muted fw-semibold fs-7 mb-1">Cabang Bank</div>
                                <div class="fw-bold text-gray-900 fs-5"><?= esc($affiliate['cabang_bank'] ?? '-') ?></div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-muted fw-semibold fs-7 mb-1">Nomor Rekening</div>
                                <div class="fw-bold text-gray-900 fs-5"><?= esc($affiliate['norek']) ?></div>
                            </div>
                        </div>

                        <div class="separator separator-dashed my-5"></div>
                        
                        <div class="d-flex align-items-center text-muted fs-7">
                            <i class="ki-duotone ki-information-5 fs-4 text-info me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            Dana komisi akan dikirim ke rekening ini
                        </div>
                        
                    </div>
                </div>
            <?php else: ?>
                <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6 mb-7">
                    <i class="ki-duotone ki-information fs-2tx text-warning me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <div class="d-flex flex-stack flex-grow-1">
                        <div class="fw-semibold">
                            <h4 class="text-gray-900 fw-bold">Perhatian!</h4>
                            <div class="fs-6 text-gray-700">Data rekening affiliate belum tersedia.</div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-header pt-7">
                    <div class="card-title flex-column">
                        <h2 class="fw-bold text-gray-800 mb-2">Riwayat Komisi</h2>
                        <div class="d-flex align-items-center text-muted fw-semibold fs-7">
                            <i class="ki-duotone ki-information-5 fs-5 text-info me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            Pencairan komisi minimal <strong class="text-gray-800 ms-1 me-1">Rp 100.000</strong>. Diproses setiap awal bulan pada hari kerja.
                        </div>
                    </div>

                    <?php
                        $totalKomisi = 0;
                        if (!empty($komisi)) {
                            foreach ($komisi as $k) {
                                if (in_array($k['status'], ['approved', 'paid'])) {
                                    $totalKomisi += ($k['harga'] * $k['komisi'] / 100);
                                }
                            }
                        }
                    ?>
                    
                    <div class="card-toolbar">
                        <div class="d-flex flex-column align-items-end">
                            <span class="text-muted fw-semibold fs-7 mb-1">Total Komisi Dipilih</span>
                            <span class="text-success fw-bold fs-2" id="totalKomisi">Rp 0</span>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2 text-center">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid justify-content-center">
                                            <input class="form-check-input" type="checkbox" id="checkAll" />
                                        </div>
                                    </th>
                                    <th class="w-50px text-center">No</th>
                                    <th class="min-w-150px">Pemesan</th>
                                    <th class="text-end min-w-125px">Harga</th>
                                    <th class="text-end min-w-100px">Komisi %</th>
                                    <th class="text-end min-w-125px">Total</th>
                                    <th class="text-center min-w-100px">Status</th>
                                    <th class="text-center min-w-150px">Status Pencairan</th>
                                    <th class="text-center min-w-150px">Tgl Pencairan</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                <?php if (!empty($komisi)): ?>
                                    <?php $no = 1 + ($pager->getCurrentPage('komisi') - 1) * $pager->getPerPage('komisi'); ?>
                                    <?php foreach ($komisi as $k): ?>
                                        <tr>
                                            <td class="text-center">
                                                <?php if ($k['status'] == 'approved' && $k['status_penarikan'] == 'pending'): ?>
                                                    <div class="form-check form-check-sm form-check-custom form-check-solid justify-content-center">
                                                        <input class="form-check-input komisi-check" type="checkbox" 
                                                               data-id="<?= $k['id'] ?>" 
                                                               data-total="<?= ($k['harga'] * $k['komisi'] / 100) ?>" />
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center text-gray-800 fw-bold"><?= $no++ ?></td>
                                            <td>
                                                <span class="text-gray-800 fw-bold"><?= esc($k['nama_siswa']) ?></span>
                                            </td>
                                            <td class="text-end">
                                                Rp <?= number_format($k['harga'], 0, ',', '.') ?>
                                            </td>
                                            <td class="text-end fw-bold text-success">
                                                <?= $k['komisi'] ?>%
                                            </td>
                                            <td class="text-end text-gray-900 fw-bold">
                                                Rp <?= number_format($k['harga'] * $k['komisi']/100, 0, ',', '.') ?>
                                            </td>
                                            
                                            <td class="text-center">
                                                <?php if ($k['status'] == 'approved'): ?>
                                                    <span class="badge badge-light-success fw-bold px-3 py-2">Success</span>
                                                <?php elseif ($k['status'] == 'pending'): ?>
                                                    <span class="badge badge-light-warning fw-bold px-3 py-2">Pending</span>
                                                <?php elseif ($k['status'] == 'paid'): ?>
                                                    <span class="badge badge-light-primary fw-bold px-3 py-2">Paid</span>
                                                <?php elseif ($k['status'] == 'rejected'): ?>
                                                    <span class="badge badge-light-danger fw-bold px-3 py-2">Rejected</span>
                                                <?php else: ?>
                                                    -
                                                <?php endif ?>
                                            </td>
                    
                                            <td class="text-center">
                                                <?php
                                                    switch ($k['status_penarikan']) {
                                                        case 'pending':
                                                            $badge = 'badge-light-warning';
                                                            $text  = 'Pending';
                                                            break;
                                                        case 'approved':
                                                            $badge = 'badge-light-primary';
                                                            $text  = 'Approved';
                                                            break;
                                                        case 'processing':
                                                            $badge = 'badge-light-info';
                                                            $text  = 'Processing';
                                                            break;
                                                        case 'paid':
                                                            $badge = 'badge-light-success';
                                                            $text  = 'Paid';
                                                            break;
                                                        default:
                                                            $badge = 'badge-light';
                                                            $text  = '-';
                                                            break;
                                                    }
                                                ?>
                                                <span class="badge <?= $badge ?> fw-bold px-3 py-2">
                                                    <?= $text ?>
                                                </span>
                                            </td>

                                            <td class="text-center">
                                                <?= $k['tgl_pembayaran'] ? date('d-m-Y', strtotime($k['tgl_pembayaran'])) : '-' ?>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-10 fs-6">
                                            Belum ada data komisi
                                        </td>
                                    </tr>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="row align-items-center mt-5">
                        <div class="col-sm-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start mb-3 mb-md-0">
                            <button class="btn btn-success fw-bold" id="btnUpdateKomisi" disabled>
                                <i class="ki-duotone ki-check-circle fs-2 me-1"><span class="path1"></span><span class="path2"></span></i>
                                Proses Pencairan
                            </button>
                        </div>
                        <div class="col-sm-12 col-md-6 d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-end gap-3">
                            <span class="text-muted fw-semibold fs-7">
                                Menampilkan <?= count($komisi) ?> data
                            </span>
                            <?php if ($pager): ?>
                                <?= $pager->links('komisi', 'bootstrap') ?>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(function () {

    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID');
    }

    function hitungTotal() {
        let total = 0;
        let checked = $('.komisi-check:checked');

        checked.each(function () {
            total += parseFloat($(this).data('total'));
        });

        $('#totalKomisi').text(formatRupiah(total));

        // enable tombol jika >= 100rb
        $('#btnUpdateKomisi').prop('disabled', total < 100000 || checked.length === 0);
    }

    // Check all
    $('#checkAll').on('change', function () {
        $('.komisi-check').prop('checked', this.checked);
        hitungTotal();
    });

    // Per checkbox
    $('.komisi-check').on('change', function () {
        hitungTotal();
    });

    // Submit bulk (Sama persis tidak ada yang dirubah)
    $('#btnUpdateKomisi').on('click', function () {

        let ids = [];

        $('.komisi-check:checked').each(function () {
            ids.push($(this).data('id'));
        });


        swal({
        title: 'Yakin?',
        text: 'Pengajuan affiliate ini akan dibatalkan',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, batalkan',
        cancelButtonText: 'Batal',
        padding: '2em'
        }).then(function (result) {
    
            if (result.value) {
                $.post("<?= base_url('sw-admin/affiliate/processKomisi') ?>", {
                    ids: ids,
                    <?= csrf_token() ?>: "<?= csrf_hash() ?>"
                }, function (response) {
    
                    if (response.status === 'success') {
                        swal({
                            title: 'Berhasil!',
                            text: response.message,
                            type: 'success',
                            padding: '2em'
                        }).then(function () {
                            location.reload();
                        });
                    } else {
                        swal({
                            title: 'Gagal!',
                            text: response.message,
                            type: 'error',
                            padding: '2em'
                        });
                    }
    
                }, 'json');
            }
    
        });
    });

});
</script>
<?= $this->endSection(); ?>
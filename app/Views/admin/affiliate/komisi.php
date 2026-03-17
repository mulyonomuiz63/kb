<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">
            <div class="widget shadow p-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="d-flex justify-content-between">
                            <h4>Komisi Affiliate</h4>
                            <a href="<?= base_url('sw-admin/affiliate') ?>" class="btn btn-secondary px-4">
                                Kembali
                            </a>
                        </div>
                        
                        <?php if (!empty($affiliate)): ?>
                            <div class="card border-left-primary shadow-sm my-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="font-weight-bold mb-0">
                                            <i class="fas fa-university mr-2 text-primary"></i>
                                            Informasi Rekening Affiliate
                                        </h5>
                            
                                        <span class="badge badge-success px-3 py-2">
                                            Aktif
                                        </span>
                                    </div>
                            
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <small class="text-muted">Nama Pemilik</small>
                                            <div class="font-weight-bold">
                                                <?= esc($affiliate['nama_akun_bank']) ?>
                                            </div>
                                        </div>
                            
                                        <div class="col-md-3 mb-2">
                                            <small class="text-muted">Bank</small>
                                            <div class="font-weight-bold">
                                                <?= esc($affiliate['bank']) ?>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3 mb-2">
                                            <small class="text-muted">Cabang Bank</small>
                                            <div class="font-weight-bold">
                                                <?= esc($affiliate['cabang_bank'] ?? '-') ?>
                                            </div>
                                        </div>
                            
                                        <div class="col-md-3 mb-2">
                                            <small class="text-muted">Nomor Rekening</small>
                                            <div class="font-weight-bold">
                                                <?= esc($affiliate['norek']) ?>
                                            </div>
                                        </div>
                                    </div>
                            
                                    <hr class="my-3">
                            
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Dana komisi akan dikirim ke rekening ini
                                    </small>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Data rekening affiliate belum tersedia
                            </div>
                            <?php endif; ?>

                        <!--//unyuk komisi-->
                        <div class="card  mt-4">
                            <div class="card-body">
                        
                                <!-- HEADER -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h5 class="mb-1 font-weight-bold">Komisi Affiliate</h5>
                                        <p class="text-muted small mb-0">
                                            Riwayat komisi yang Anda peroleh
                                        </p>
                                
                                        <!-- KETERANGAN PENCAIRAN -->
                                        <p class="text-muted small mt-2 mb-0">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Pencairan komisi minimal 
                                            <strong>Rp 100.000</strong> Di proses setiap awal bulan pada hari kerja
                                        </p>
                                    </div>
                                    
                                    <?php
                                        $totalKomisi = 0;
                                        
                                        if (!empty($komisi)) {
                                            foreach ($komisi as $k) {
                                                // hitung hanya komisi yang valid (misalnya approved / paid)
                                                if (in_array($k['status'], ['approved', 'paid'])) {
                                                    $totalKomisi += ($k['harga'] * $k['komisi'] / 100);
                                                }
                                            }
                                        }
                                    ?>
                                
                                    <!-- TOTAL KOMISI -->
                                    <div class="text-right">
                                        <h6 class="mb-0 text-muted">Total Komisi Dipilih</h6>
                                        <h4 class="mb-1 font-weight-bold text-success" id="totalKomisi">
                                            Rp 0
                                        </h4>
                                        <small class="text-muted">
                                            Minimal pencairan <strong>Rp 100.000</strong>
                                        </small>
                                    </div>

                                </div>


                        
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-sm mb-0">
                                        <thead class="thead-light">
                                            <tr class="text-center">
                                                <th class="text-center">
                                                    <input type="checkbox" id="checkAll">
                                                </th>
                                                <th width="5%">No</th>
                                                <th>ID Transaksi</th>
                                                <th>Harga</th>
                                                <th>Komisi %</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Status Pencairan</th>
                                                <th>Tanggal Pencairan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                        
                                        <?php if (!empty($komisi)): ?>
                                            <?php
                                                $no = 1 + ($pager->getCurrentPage('komisi') - 1) * $pager->getPerPage('komisi');
                                            ?>
                                            <?php foreach ($komisi as $k): ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <?php if ($k['status'] == 'approved' && $k['status_penarikan'] == 'pending'): ?>
                                                            <input type="checkbox"
                                                                    class="komisi-check"
                                                                    data-id="<?= $k['id'] ?>"
                                                                    data-total="<?= ($k['harga'] * $k['komisi'] / 100) ?>">
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center"><?= $no++ ?></td>
                        
                                                    <td class="text-center">
                                                        <?= esc($k['id_transaksi']) ?>
                                                    </td>
                        
                                                    <td class="text-right">
                                                        Rp <?= number_format($k['harga'], 0, ',', '.') ?>
                                                    </td>
                        
                                                    <td class="text-right font-weight-bold text-success">
                                                        <?= $k['komisi'] ?>
                                                    </td>
                                                    <td class="text-right">
                                                        Rp <?= number_format($k['harga'] * $k['komisi']/100, 0, ',', '.') ?>
                                                    </td>
                                                    
                        
                                                    <!-- STATUS TRANSAKSI -->
                                                    <td class="text-center">
                                                        <?php if ($k['status'] == 'approved'): ?>
                                                            <span class="badge badge-success">Success</span>
                                                        <?php elseif ($k['status'] == 'pending'): ?>
                                                            <span class="badge badge-warning">Pending</span>
                                                        <?php elseif ($k['status'] == 'paid'): ?>
                                                            <span class="badge badge-primary">Paid</span>
                                                        <?php elseif ($k['status'] == 'rejected'): ?>
                                                            <span class="badge badge-danger">Rejected</span>
                                                        <?php else: ?>
                                                            -
                                                        <?php endif ?>
                                                    </td>
                        
                                                    <!-- STATUS PENARIKAN -->
                                                    <td class="text-center">
                                                        <?php
                                                            switch ($k['status_penarikan']) {
                                                                case 'pending':
                                                                    $badge = 'badge-warning';
                                                                    $text  = 'Pending';
                                                                    break;
                                                    
                                                                case 'approved':
                                                                    $badge = 'badge-primary';
                                                                    $text  = 'Approved';
                                                                    break;
                                                    
                                                                case 'processing':
                                                                    $badge = 'badge-info';
                                                                    $text  = 'Processing';
                                                                    break;
                                                    
                                                                case 'paid':
                                                                    $badge = 'badge-success';
                                                                    $text  = 'Paid';
                                                                    break;
                                                    
                                                                default:
                                                                    $badge = '';
                                                                    $text  = '-';
                                                                    break;
                                                            }
                                                        ?>
                                                        <span class="badge <?= $badge ?>">
                                                            <?= $text ?>
                                                        </span>
                                                    </td>
        
                        
                                                    <td class="text-center">
                                                        <?= $k['tgl_pembayaran'] ? date('d-m-Y', strtotime($k['tgl_pembayaran'])) :'-' ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="9" class="text-center text-muted py-3">
                                                    Belum ada data komisi
                                                </td>
                                            </tr>
                                        <?php endif ?>
                        
                                        </tbody>
                                    </table>
                                    <div class="mt-3 text-right">
                                        <button class="btn btn-success"
                                                id="btnUpdateKomisi"
                                                disabled>
                                            <i class="fas fa-check-circle"></i>
                                            Proses Pencairan
                                        </button>
                                    </div>

                                </div>
                        
                                <!-- PAGINATION -->
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <small class="text-muted">
                                        Menampilkan <?= count($komisi) ?> data
                                    </small>
                                    <?php if ($pager): ?>
                                        <?php echo $pager->links('komisi', 'bootstrap') ?>
                                    <?php endif; ?>
        
                                </div>
                        
                            </div>
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

    // Submit bulk
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
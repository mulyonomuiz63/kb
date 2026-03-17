<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">
            <div class="widget shadow-sm p-4 bg-white rounded">
                
                <div class="widget-heading">
                    <div class="row align-items-center mb-4">
                        <div class="col-md-8">
                            <h5 class="font-weight-bold text-dark">Laporan Voucher & Komisi</h5>
                            <p class="text-muted small">Data penggunaan voucher dan perhitungan komisi mitra secara real-time.</p>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="javascript:window.history.go(-1);" class="btn btn-outline-secondary btn-sm">
                                <i class="flaticon-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <?php 
                        $nama = !empty($voucher) ? $voucher->nama_mitra : '-';
                        $kode_voucher = !empty($voucher) ? $voucher->kode_voucher : '-';
                        $komisi = !empty($voucher) ? $voucher->komisi : 0;
                        $total_pengguna = 0;
                        $total_harga = 0;

                        foreach ($transaksi as $s) {
                            $diskon = ($s->nominal * $s->diskon) / 100;
                            $totalSetelahDiskon = $s->nominal - $diskon;
                            $diskon_voucher = ($totalSetelahDiskon * $s->voucher) / 100;
                            $jumlah = $totalSetelahDiskon - $diskon_voucher;
                            
                            $total_pengguna += 1;
                            $total_harga += $jumlah;
                        }
                    ?>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light border-0">
                                <div class="card-body p-3">
                                    <ul class="list-group list-group-flush bg-transparent">
                                        <li class="list-group-item d-flex justify-content-between bg-transparent px-0">
                                            <span>Mitra:</span> <span class="font-weight-bold"><?= $nama ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between bg-transparent px-0">
                                            <span>Kode Voucher:</span> <span class="badge badge-primary"><?= $kode_voucher ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between bg-transparent px-0">
                                            <span>Total Pengguna:</span> <span class="text-dark"><?= $total_pengguna ?> Peserta</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light border-0">
                                <div class="card-body p-3">
                                    <ul class="list-group list-group-flush bg-transparent">
                                        <li class="list-group-item d-flex justify-content-between bg-transparent px-0">
                                            <span>Total Transaksi:</span> <span class="font-weight-bold">Rp <?= number_format($total_harga, 0, '.', '.') ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between bg-transparent px-0">
                                            <span>% Komisi:</span> <span class="text-success font-weight-bold"><?= $komisi ?> %</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between bg-transparent px-0 border-top mt-1">
                                            <span class="font-weight-bold">Nilai Komisi:</span> 
                                            <span class="h5 mb-0 font-weight-bold text-danger">Rp <?= number_format(($total_harga * $komisi)/100, 0, '.', '.') ?></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="datatable-table" class="table table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th>Siswa</th>
                                <th>Paket</th>
                                <th>Tgl Pembayaran</th>
                                <th class="text-right">Nominal</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $currentMonth = null;
                            $currentYear  = null;
                            $totalBulan   = 0;
                            
                            foreach ($transaksi as $s) : 
                                $dateObj = strtotime($s->tgl_pembayaran);
                                $month = date('n', $dateObj);
                                $year  = date('Y', $dateObj);

                                $diskon = ($s->nominal * $s->diskon) / 100;
                                $totalDiskon = $s->nominal - $diskon;
                                $diskon_voucher = ($totalDiskon * $s->voucher) / 100;
                                $nominalBersih = $totalDiskon - $diskon_voucher;

                                // Subtotal per bulan
                                if ($currentMonth !== null && ($currentMonth !== $month || $currentYear !== $year)) {
                                    echo "<tr class='table-warning'>
                                            <td colspan='3' class='text-right font-weight-bold'>SUBTOTAL KOMISI BULAN SEBELUMNYA</td>
                                            <td colspan='3' class='font-weight-bold text-danger'>Rp " . number_format(($totalBulan * $komisi)/100, 0, '.', '.') . "</td>
                                          </tr>";
                                    $totalBulan = 0;
                                }

                                $currentMonth = $month;
                                $currentYear  = $year;
                                $totalBulan  += $nominalBersih;
                            ?>
                                <tr>
                                    <td><div class="text-wrap" style="width: 150px;"><?= $s->nama_siswa; ?></div></td>
                                    <td><?= $s->nama_paket; ?></td>
                                    <td><?= date('d M Y', $dateObj); ?></td>
                                    <td class="text-right font-weight-bold">Rp <?= number_format($nominalBersih, 0, '.', '.'); ?></td>
                                    <td class="text-center">
                                        <?php if ($s->status == 'V') : ?>
                                            <span class="badge badge-info">Waiting</span>
                                        <?php else : ?>
                                            <span class="badge badge-success">Approved</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <button data-transaksi="<?= encrypt_url($s->idtransaksi); ?>"
                                           class="btn btn-sm btn-primary validasi-transaksi">Detail</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <?php if ($currentMonth !== null): ?>
                                <tr class="table-warning">
                                    <td colspan="3" class="text-right font-weight-bold">SUBTOTAL KOMISI BULAN TERAKHIR</td>
                                    <td colspan="3" class="font-weight-bold text-danger">Rp <?= number_format(($totalBulan * $komisi)/100, 0, '.', '.'); ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="validasi_transaksi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="formValidasi" method="POST" action="<?= base_url('App/proses_validasi') ?>">
            <?= csrf_field() ?>
            <div class="modal-content border-0">
                <div id="isiKonten">
                    </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        $('.validasi-transaksi').click(function() {
            const idtransaksi = $(this).data('transaksi');
            const base_url_thumb = '<?php echo base_url('uploads/transaksi/thumbnails'); ?>';
            
            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-admin/mitra/validasi-transaksi') ?>",
                data: { idtransaksi: idtransaksi, <?= csrf_token() ?>: "<?= csrf_hash() ?>" },
                dataType: 'JSON',
                success: function(data) {
                    var diskon = (data.nominal * data.diskon) / 100;
                    var totalDiskon = data.nominal - diskon;
                    var diskon_voucher = (totalDiskon * data.voucher) / 100;
                    var nett = totalDiskon - diskon_voucher;

                    var pilihstatus = '';
                    var button = '';
                    var informasi = '';

                    if (data.status == 'V') {
                        pilihstatus = `
                            <label class="font-weight-bold">Tindakan Validasi</label>
                            <select name="status" class="form-control mb-3" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="S">Selesai (Approve)</option>
                                <option value="P">Upload Ulang (Reject)</option>
                            </select>`;
                        button = `<button type="submit" class="btn btn-primary btn-block">Simpan Perubahan</button>`;
                        informasi = `
                            <div class="form-group">
                                <label class="small text-muted">Keterangan (Opsional jika reject)</label>
                                <textarea class="form-control" name="keterangan" rows="2"></textarea>
                            </div>
                            <div class="text-center bg-light p-2 rounded">
                                <label class="d-block small font-weight-bold">Bukti Pembayaran:</label>
                                <a href="${'<?= base_url() ?>' + '/' + data.bukti_pembayaran}" target="_blank">
                                    <img src="${'<?= base_url() ?>' + '/' + data.bukti_pembayaran}" class="img-fluid rounded border" style="max-height:200px">
                                </a>
                            </div>`;
                    } else {
                        pilihstatus = `<div class="alert alert-success text-center">Transaksi ini sudah <b>Approved</b></div>`;
                    }

                    var jenis_bayar = (data.jenis_bayar != 'online') ? 'Manual Transfer' : 'Midtrans / Online';

                    $("#isiKonten").html(`
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title text-white">Validasi Pembayaran</h5>
                            <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Siswa</small>
                                    <span class="font-weight-bold">${data.nama_siswa}</span>
                                </div>
                                <div class="col-6 text-right">
                                    <small class="text-muted d-block">Metode</small>
                                    <span class="badge badge-secondary">${jenis_bayar}</span>
                                </div>
                            </div>
                            
                            <div class="p-3 mb-3" style="background: #f1f2f3; border-radius: 8px;">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small">Paket:</span>
                                    <span class="small font-weight-bold">${data.nama_paket}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="small">Total Bayar:</span>
                                    <span class="text-primary font-weight-bold">Rp ${numberFormat(nett, 0, ',', '.')}</span>
                                </div>
                            </div>

                            ${pilihstatus}
                            ${informasi}

                            <input type="hidden" name="idtransaksi" value="${data.idtransaksi}">
                        </div>
                        <div class="modal-footer border-0">
                            ${button}
                        </div>
                    `);
                    $('#validasi_transaksi').modal('show');
                }
            });
        });
    });

    function numberFormat(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        return s.join(dec);
    }
</script>

<style>
    .widget-heading h5 { font-size: 1.25rem; color: #3b3f5c; }
    .table thead th { 
        background-color: #f8f9fa; 
        border-bottom: 1px solid #e0e6ed;
        color: #515365;
        font-size: 13px;
    }
    .list-group-item { font-size: 0.9rem; color: #515365; }
    .badge { padding: 5px 10px; font-weight: 500; }
    .table-warning td { background-color: #fff9e6 !important; border-top: 2px solid #ffecc0; border-bottom: 2px solid #ffecc0; }
</style>

<?= $this->endSection(); ?>
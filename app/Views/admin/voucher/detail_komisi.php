<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?php $db = Config\Database::connect(); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
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

            <div class="row g-5 g-xl-8 mb-8">
                <div class="col-xl-6">
                    <div class="card card-flush bg-light-primary border-0 h-md-100">
                        <div class="card-body py-9">
                            <div class="row gx-9">
                                <div class="col-sm-6 mb-10 mb-sm-0">
                                    <div class="fs-6 fw-semibold text-gray-600 mb-2">Nama Mitra</div>
                                    <div class="fs-2 fw-bold text-gray-900"><?= $nama ?></div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="fs-6 fw-semibold text-gray-600 mb-2">Kode Voucher</div>
                                    <div class="badge badge-primary fs-4 px-3 py-2"><?= $kode_voucher ?></div>
                                </div>
                            </div>
                            <div class="separator separator-dashed border-primary opacity-25 my-5"></div>
                            <div class="d-flex flex-stack">
                                <div class="text-gray-700 fw-semibold fs-6">Total Pengguna Voucher:</div>
                                <div class="fw-bold text-gray-900 fs-5"><?= $total_pengguna ?> Peserta</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card card-flush h-md-100 border-0 shadow-sm">
                        <div class="card-body py-9">
                            <div class="d-flex flex-stack mb-5">
                                <div class="text-gray-600 fw-semibold fs-6">Total Transaksi Masuk:</div>
                                <div class="fw-bold text-gray-900 fs-4">Rp <?= number_format($total_harga, 0, '.', '.') ?></div>
                            </div>
                            <div class="d-flex flex-stack mb-5">
                                <div class="text-gray-600 fw-semibold fs-6">Persentase Komisi Mitra:</div>
                                <div class="badge badge-light-success fs-5 px-3 py-1"><?= $komisi ?> %</div>
                            </div>
                            <div class="separator separator-dashed my-5"></div>
                            <div class="d-flex flex-stack">
                                <div class="text-gray-800 fw-bold fs-5">Estimasi Pendapatan Komisi:</div>
                                <div class="fw-bold text-danger fs-2x">Rp <?= number_format(($total_harga * $komisi)/100, 0, '.', '.') ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-flush shadow-sm">
                <div class="card-header align-items-center py-5">
                    <div class="card-title">
                        <h3 class="fw-bold mb-0">Rincian Transaksi Pengguna</h3>
                    </div>
                    <div class="card-toolbar">
                        </div>
                </div>
                
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatable-table" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Siswa</th>
                                    <th class="min-w-150px">Paket</th>
                                    <th class="min-w-150px">Tgl Pembayaran</th>
                                    <th class="text-end min-w-125px">Nominal</th>
                                    <th class="text-center min-w-100px">Status</th>
                                    <th class="text-center min-w-100px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
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
                                            echo "<tr class='bg-light-warning'>
                                                    <td colspan='3' class='text-end fw-bold text-gray-800 text-uppercase fs-7'>Subtotal Komisi Bulan Sebelumnya</td>
                                                    <td colspan='3' class='fw-bold text-danger fs-5'>Rp " . number_format(($totalBulan * $komisi)/100, 0, '.', '.') . "</td>
                                                  </tr>";
                                            $totalBulan = 0;
                                        }

                                        $currentMonth = $month;
                                        $currentYear  = $year;
                                        $totalBulan  += $nominalBersih;
                                ?>
                                    <tr>
                                        <td>
                                            <span class="text-gray-800 fw-bold"><?= $s->nama_siswa; ?></span>
                                        </td>
                                        <td><?= $s->nama_paket; ?></td>
                                        <td><?= date('d M Y', $dateObj); ?></td>
                                        <td class="text-end fw-bold text-gray-900">Rp <?= number_format($nominalBersih, 0, '.', '.'); ?></td>
                                        <td class="text-center">
                                            <?php if ($s->status == 'V') : ?>
                                                <span class="badge badge-light-info fw-bold px-4 py-2">Waiting</span>
                                            <?php else : ?>
                                                <span class="badge badge-light-success fw-bold px-4 py-2">Approved</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <button data-transaksi="<?= encrypt_url($s->idtransaksi); ?>" class="btn btn-sm btn-light-primary validasi-transaksi">Detail</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php if ($currentMonth !== null): ?>
                                    <tr class="bg-light-warning">
                                        <td colspan="3" class="text-end fw-bold text-gray-800 text-uppercase fs-7">Subtotal Komisi Bulan Terakhir</td>
                                        <td colspan="3" class="fw-bold text-danger fs-5">Rp <?= number_format(($totalBulan * $komisi)/100, 0, '.', '.'); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="validasi_transaksi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <form id="formValidasi" method="POST" action="<?= base_url('App/proses_validasi') ?>">
            <?= csrf_field() ?>
            <div class="modal-content rounded shadow-sm" id="isiKonten">
                </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // AJAX Request untuk menampilkan detail transaksi
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

                    // Menyesuaikan struktur HTML form di dalam modal dengan gaya Metronic
                    if (data.status == 'V') {
                        pilihstatus = `
                            <div class="fv-row mb-5">
                                <label class="fs-6 fw-bold mb-2">Tindakan Validasi</label>
                                <select name="status" class="form-select form-select-solid" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="S">Selesai (Approve)</option>
                                    <option value="P">Upload Ulang (Reject)</option>
                                </select>
                            </div>`;
                        button = `<button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>`;
                        informasi = `
                            <div class="fv-row mb-5">
                                <label class="fs-6 fw-semibold mb-2">Keterangan <span class="text-muted fs-8">(Opsional jika reject)</span></label>
                                <textarea class="form-control form-control-solid" name="keterangan" rows="3" placeholder="Tuliskan alasan penolakan..."></textarea>
                            </div>
                            <div class="text-center bg-light-primary border border-primary border-dashed p-4 rounded mt-5">
                                <div class="fs-6 fw-bold text-gray-800 mb-3">Bukti Pembayaran:</div>
                                <a href="${'<?= base_url() ?>' + '/' + data.bukti_pembayaran}" target="_blank" class="d-block">
                                    <img src="${'<?= base_url() ?>' + '/' + data.bukti_pembayaran}" class="img-fluid rounded shadow-sm" style="max-height: 250px; object-fit: contain;">
                                </a>
                            </div>`;
                    } else {
                        pilihstatus = `<div class="notice d-flex bg-light-success rounded border-success border border-dashed p-4 mt-4">
                                            <i class="ki-duotone ki-check-circle fs-2tx text-success me-4"><span class="path1"></span><span class="path2"></span></i>
                                            <div class="d-flex flex-stack flex-grow-1">
                                                <div class="fw-semibold">
                                                    <div class="fs-6 text-gray-700">Transaksi ini sudah <b>Approved</b></div>
                                                </div>
                                            </div>
                                        </div>`;
                    }

                    var jenis_bayar = (data.jenis_bayar != 'online') ? 'Manual Transfer' : 'Midtrans / Online';

                    $("#isiKonten").html(`
                        <div class="modal-header pb-0 border-0 justify-content-between">
                            <h2 class="fw-bold">Validasi Pembayaran</h2>
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                            </div>
                        </div>
                        
                        <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">
                            
                            <div class="row mb-7">
                                <div class="col-6">
                                    <div class="fs-7 text-muted fw-semibold mb-1">Nama Siswa</div>
                                    <div class="fs-5 fw-bold text-gray-900">${data.nama_siswa}</div>
                                </div>
                                <div class="col-6 text-end">
                                    <div class="fs-7 text-muted fw-semibold mb-1">Metode Bayar</div>
                                    <div class="badge badge-light-dark fw-bold px-3 py-2">${jenis_bayar}</div>
                                </div>
                            </div>
                            
                            <div class="border border-dashed border-gray-300 bg-light rounded p-5 mb-7">
                                <div class="d-flex flex-stack mb-2">
                                    <span class="text-gray-600 fw-semibold">Paket:</span>
                                    <span class="text-gray-800 fw-bold">${data.nama_paket}</span>
                                </div>
                                <div class="separator separator-dashed border-gray-300 my-3"></div>
                                <div class="d-flex flex-stack">
                                    <span class="text-gray-600 fw-semibold">Total Bayar:</span>
                                    <span class="text-primary fw-bold fs-4">Rp ${numberFormat(nett, 0, ',', '.')}</span>
                                </div>
                            </div>

                            ${pilihstatus}
                            ${informasi}

                            <input type="hidden" name="idtransaksi" value="${data.idtransaksi}">
                        </div>
                        <div class="modal-footer border-0 p-5 p-lg-10 pt-0">
                            ${button}
                        </div>
                    `);
                    $('#validasi_transaksi').modal('show');
                }
            });
        });
    });

    // Helper: Number Format 
    function numberFormat(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? ',' : dec_point,
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

<?= $this->endSection(); ?>
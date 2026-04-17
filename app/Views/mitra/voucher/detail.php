<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        
        <?php
        $nama = $voucher->nama_mitra;
        $kode_voucher = $voucher->kode_voucher;
        $total_pengguna = 0;
        $total_harga = 0;
        $idmitra = $voucher->idmitra;
        foreach ($transaksi as $s) :
            $diskon         = ($s->nominal * $s->diskon) / 100;
            $totalDiskon    = $s->nominal - $diskon;
            $diskon_voucher = ($totalDiskon * $s->voucher) / 100;
            $jumlah = ($s->nominal - $diskon - $diskon_voucher);
            $total_pengguna += 1;
            $total_harga += $jumlah;
        endforeach;

        $komisi = !empty($voucher) ? $voucher->komisi : 0;
        $nilai_komisi = ($total_harga * $komisi) / 100;
        ?>

        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            <div class="col-md-4">
                <div class="card card-flush shadow-sm h-md-100">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2"><?= $total_pengguna ?></span>
                            <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Pengguna</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-boldest fs-6 text-dark"><?= $nama ?></span>
                                <span class="fw-bold fs-6 text-gray-400">Mitra</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-flush shadow-sm h-md-100">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Rp. <?= number_format($total_harga, 0, '.', '.') ?></span>
                            <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Harga (Omset)</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                        <span class="badge badge-light-primary fs-7 fw-bold">Kode: <?= $kode_voucher ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-flush shadow-sm h-md-100 bg-light-success">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-success me-2 lh-1 ls-n2">Rp. <?= number_format($nilai_komisi, 0, '.', '.') ?></span>
                            <span class="text-gray-700 pt-1 fw-semibold fs-6">Nilai Komisi (<?= $komisi ?>%)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-flush shadow-sm">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="card-label fw-bold text-dark">Data Transaksi Voucher</h3>
                </div>
                <div class="card-toolbar">
                    <a href="javascript:window.history.go(-1);" class="btn btn-sm btn-light-primary">
                        <i class="ki-duotone ki-black-left fs-4 me-1"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="card-body py-4">
                <div class="table-responsive">
                    <table id="datatables-list" class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-150px">Siswa</th>
                                <th class="min-w-150px">Paket</th>
                                <th class="min-w-125px">Tgl Pembayaran</th>
                                <th class="min-w-100px text-end">Nominal</th>
                                <th class="min-w-100px text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            <?php foreach ($transaksi as $s) : 
                                $diskon         = ($s->nominal * $s->diskon) / 100;
                                $totalDiskon    = $s->nominal - $diskon;
                                $diskon_voucher = ($totalDiskon * $s->voucher) / 100;
                                $net_nominal    = $s->nominal - $diskon - $diskon_voucher;
                            ?>
                                <tr>
                                    <td class="text-gray-800 fw-bold"><?= $s->nama_siswa; ?></td>
                                    <td><?= $s->nama_paket; ?></td>
                                    <td><?= $s->tgl_pembayaran; ?></td>
                                    <td class="text-end fw-bold">Rp. <?= number_format($net_nominal, 0, '.', '.'); ?></td>
                                    <td class="text-center">
                                        <?php if ($s->status == 'V') : ?>
                                            <span class="badge badge-light-info fw-bold">Menunggu Approved</span>
                                        <?php else : ?>
                                            <span class="badge badge-light-success fw-bold">Approved</span>
                                        <?php endif; ?>
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
        $('#datatables-list').DataTable({
            "ordering": false,
            "lengthChange": false,
            "pageLength": 10,
            "info": false,
            "dom": "<'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>"
        });
    });
</script>
<?= $this->endSection(); ?>
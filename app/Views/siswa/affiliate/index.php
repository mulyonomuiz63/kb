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
                                        <div class="fs-7 text-muted fw-semibold">Affiliate</div>
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
                                            <a href="<?= base_url('sw-siswa/affiliate/edit/'.encrypt_url($affiliates->id_affiliate)) ?>" class="btn btn-sm btn-light-warning fw-bold">
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
                                        <th class="w-50px text-center">No</th>
                                        <th class="min-w-150px">Pemesan</th>
                                        <th class="min-w-150px">Paket</th>
                                        <th class="min-w-150px">Rincian Komisi</th>
                                        <th class="min-w-120px text-center">Status & Pencairan</th>
                                        <th class="min-w-120px text-center">Tanggal Pembayaran</th>
                                        <th class="text-end min-w-100px">Aksi / Detail</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    <?php if (!empty($komisi)): ?>
                                        <?php $no = 1 + ($pager->getCurrentPage('komisi') - 1) * $pager->getPerPage('komisi'); ?>
                                        <?php foreach ($komisi as $k): ?>
                                            <tr>
                                                <td class="text-center text-gray-800 fw-bold"><?= $no++ ?></td>
                                                <td>
                                                    <span class="text-gray-800 fw-bold text-hover-primary fs-6">
                                                        <?= ucwords(strtolower($k['nama_siswa'])) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-gray-800 fw-bold text-hover-primary fs-6">
                                                        <?= ucwords(strtolower($k['nama_paket'])) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="text-gray-800 fw-bold">Rp <?= number_format($k['harga'], 0, ',', '.') ?></span>
                                                        <span class="fs-7 text-muted">Komisi (<?= $k['komisi'] ?>%): <strong class="text-success">Rp <?= number_format($k['harga'] * $k['komisi']/100, 0, ',', '.') ?></strong></span>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex flex-column align-items-center gap-1">
                                                        <div>
                                                            <span class="text-muted fs-8">Status:</span> 
                                                            <?php 
                                                                $statusClass = [
                                                                    'approved' => 'badge-light-success',
                                                                    'pending'  => 'badge-light-warning',
                                                                    'paid'     => 'badge-light-primary',
                                                                    'rejected' => 'badge-light-danger'
                                                                ][$k['status']] ?? 'badge-light-secondary';
                                                            ?>
                                                            <span class="badge <?= $statusClass ?> fs-8 fw-bold px-2 py-1"><?= ucfirst($k['status']) ?></span>
                                                        </div>
                                                        <div>
                                                            <span class="text-muted fs-8">Pencairan:</span> 
                                                            <?php
                                                                $penarikanMap = [
                                                                    'pending'    => ['c' => 'badge-light-warning', 't' => 'Pending'],
                                                                    'approved'   => ['c' => 'badge-light-primary', 't' => 'Approved'],
                                                                    'processing' => ['c' => 'badge-light-info', 't' => 'Processing'],
                                                                    'paid'       => ['c' => 'badge-light-success', 't' => 'Paid'],
                                                                ][$k['status_penarikan']] ?? ['c' => 'badge-light-secondary', 't' => '-'];
                                                            ?>
                                                            <span class="badge <?= $penarikanMap['c'] ?> fs-8 fw-bold px-2 py-1"><?= $penarikanMap['t'] ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="text-gray-700 fs-7 fw-bold"><?= $k['tgl_pembayaran'] ? date('d M Y', strtotime($k['tgl_pembayaran'])) : '-' ?></span>
                                                </td>
                                                <td class="text-end">
                                                    <?php if ($k['status_penarikan'] == 'paid'): ?>
                                                        <button type="button" class="btn btn-icon btn-light-primary btn-sm btn-view-pencairan" 
                                                            data-id-komisi="<?= $k['id'] ?>"
                                                                title="Lihat Detail Pencairan">
                                                            <i class="ki-outline ki-eye fs-4"></i>
                                                        </button>
                                                    <?php else: ?>
                                                        <span class="text-muted fs-7 italic">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-10">Belum ada data komisi</td>
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

<!-- ==============================================
     MODAL DETAIL PENCAIRAN UNTUK SISWA
     ============================================== -->
<div class="modal fade" id="modalDetailPencairan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Detail Riwayat Pencairan Komisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <div class="row g-5">
                    <div class="col-md-6">
                        <table class="table table-row-bordered fs-6 gy-2">
                            <tr>
                                <td class="fw-bold text-muted">Kode Penarikan</td>
                                <td id="det_kode_penarikan" class="fw-bold text-gray-800">-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Bank Tujuan</td>
                                <td id="det_bank">-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">No. Rekening</td>
                                <td id="det_norek">-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Atas Nama</td>
                                <td id="det_atas_nama">-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Status</td>
                                <td id="det_status">-</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <table class="table table-row-bordered fs-6 gy-2">
                            <tr>
                                <td class="fw-bold text-muted">Nominal Kotor</td>
                                <td id="det_kotor" class="text-end">-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Potongan PPh21</td>
                                <td id="det_pph21" class="text-end text-danger">-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Biaya Admin</td>
                                <td id="det_admin" class="text-end text-danger">-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Total Bersih</td>
                                <td id="det_bersih" class="text-end fw-bold text-success">-</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="separator my-5"></div>

                <div class="text-center">
                    <label class="form-label fw-bold d-block mb-3">Bukti Transfer:</label>
                    <div id="wrapper_bukti" class="border rounded p-3 bg-light">
                        <span class="text-muted">Memuat bukti transfer...</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom style untuk meratakan tampilan */
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
    .badge-light-primary {
        background-color: #F1FAFF;
        color: #009EF7;
    }
    .badge-light-info {
        background-color: #F8F5FF;
        color: #7239EA;
    }
    .fw-boldest {
        font-weight: 800;
    }
</style>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(document).ready(function () {
    // Fungsi pembatalan pengajuan affiliate (fungsi asli tetap dipertahankan)
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

    // Tombol Lihat Detail Pencairan untuk Siswa
    $(document).on('click', '.btn-view-pencairan', function () {
        let idKomisi = $(this).data('id-komisi');

        // Tampilkan state loading pada modal
        $('#det_kode_penarikan').text('Memuat...');
        $('#det_bank').text('...');
        $('#det_norek').text('...');
        $('#det_atas_nama').text('...');
        $('#det_status').text('...');
        $('#det_kotor').text('...');
        $('#det_pph21').text('...');
        $('#det_admin').text('...');
        $('#det_bersih').text('...');
        $('#wrapper_bukti').html('<span class="text-muted">Memuat bukti transfer...</span>');
        
        $('#modalDetailPencairan').modal('show');

        // Mengambil data detail pencairan via AJAX
        $.ajax({
            url: "<?= base_url('sw-siswa/affiliate/getDetailPencairan') ?>/" + idKomisi,
            type: "GET",
            dataType: "json",
            success: function (res) {
                if (res.status === 'success') {
                    let d = res.data;
                    $('#det_kode_penarikan').text(d.kode_penarikan);
                    $('#det_bank').text(d.bank_tujuan);
                    $('#det_norek').text(d.no_rekening);
                    $('#det_atas_nama').text(d.atas_nama);
                    $('#det_status').html('<span class="badge badge-light-success fw-bold">' + d.status.toUpperCase() + '</span>');
                    
                    $('#det_kotor').text('Rp ' + parseFloat(d.nominal_kotor).toLocaleString('id-ID'));
                    $('#det_pph21').text('- Rp ' + parseFloat(d.potongan_pph21).toLocaleString('id-ID'));
                    $('#det_admin').text('- Rp ' + parseFloat(d.biaya_admin).toLocaleString('id-ID'));
                    $('#det_bersih').text('Rp ' + parseFloat(d.nominal_bersih).toLocaleString('id-ID'));

                    if (d.bukti_transfer) {
                        let imageUrl = "<?= base_url('uploads/bukti_pencairan/') ?>" + d.bukti_transfer;
                        $('#wrapper_bukti').html(`
                            <a href="${imageUrl}" target="_blank" title="Klik untuk memperbesar gambar">
                                <img src="${imageUrl}" alt="Bukti Transfer" class="img-fluid rounded shadow-sm" style="max-height: 300px; object-fit: contain;">
                            </a>
                            <div class="mt-2">
                                <a href="${imageUrl}" target="_blank" class="btn btn-sm btn-light-primary">Buka Gambar Penuh</a>
                            </div>
                        `);
                    } else {
                        $('#wrapper_bukti').html('<span class="text-danger">Bukti transfer tidak ditemukan.</span>');
                    }
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            },
            error: function () {
                Swal.fire('Gagal', 'Tidak dapat mengambil data detail pencairan.', 'error');
            }
        });
    });
});
</script>
<?= $this->endSection(); ?>
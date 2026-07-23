<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <?php if (!empty($affiliate)): ?>
                <input type="hidden" id="dataUser" data-email="<?= esc($affiliate['email']) ?>" data-nama="<?= esc($affiliate['nama_siswa']) ?>" value="bebas">
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
                                    <th class="min-w-150px">Paket</th>
                                    <th class="text-end min-w-125px">Harga</th>
                                    <th class="text-center min-w-100px">Status</th>
                                    <th class="text-center min-w-150px">Tgl Pencairan</th>
                                    <th class="text-center min-w-150px">Aksi / Detail</th>
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
                                            <td>
                                                <span class="text-gray-800 fw-bold"><?= esc($k['nama_paket']) ?></span>
                                            </td>

                                            <!-- DIGABUNG JADI 1 KOLOM: HARGA & KOMISI -->
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="text-gray-800 fw-bold">Rp <?= number_format($k['harga'], 0, ',', '.') ?></span>
                                                    <span class="fs-7 text-muted">Komisi (<?= $k['komisi'] ?>%): <strong class="text-success">Rp <?= number_format($k['harga'] * $k['komisi'] / 100, 0, ',', '.') ?></strong></span>
                                                </div>
                                            </td>

                                            <!-- DIGABUNG JADI 1 KOLOM: STATUS & STATUS PENCAIRAN -->
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    <div>
                                                        <span class="text-muted fs-8">Status:</span>
                                                        <?php if ($k['status'] == 'approved'): ?>
                                                            <span class="badge badge-light-success fw-bold px-2 py-1 fs-8">Success</span>
                                                        <?php elseif ($k['status'] == 'pending'): ?>
                                                            <span class="badge badge-light-warning fw-bold px-2 py-1 fs-8">Pending</span>
                                                        <?php elseif ($k['status'] == 'paid'): ?>
                                                            <span class="badge badge-light-primary fw-bold px-2 py-1 fs-8">Paid</span>
                                                        <?php elseif ($k['status'] == 'rejected'): ?>
                                                            <span class="badge badge-light-danger fw-bold px-2 py-1 fs-8">Rejected</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-light fw-bold px-2 py-1 fs-8">-</span>
                                                        <?php endif ?>
                                                    </div>
                                                    <div>
                                                        <span class="text-muted fs-8">Pencairan:</span>
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
                                                        <span class="badge <?= $badge ?> fw-bold px-2 py-1 fs-8">
                                                            <?= $text ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <?= $k['tgl_pembayaran'] ? date('d-m-Y', strtotime($k['tgl_pembayaran'])) : '-' ?>
                                            </td>

                                            <!-- KOLOM AKSI / DETAIL PENCAIRAN -->
                                            <td class="text-center">
                                                <?php if ($k['status_penarikan'] == 'paid'): ?>
                                                    <button type="button" class="btn btn-icon btn-light-primary btn-sm btn-view-pencairan"
                                                        data-id-komisi="<?= $k['id'] ?>"
                                                        title="Lihat Detail Pencairan">
                                                        <i class="ki-duotone ki-eye fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                    </button>
                                                <?php else: ?>
                                                    <span class="text-muted fs-7">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-10 fs-6">
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

<!-- ==============================================
     MODAL PROSES PENCAIRAN 
     ============================================== -->
<div class="modal fade" id="modalPencairan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Form Proses Pencairan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formPencairan" enctype="multipart/form-data">
                <!-- CSRF Token -->
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <!-- Hidden Data -->
                <input type="hidden" name="email" id="input_email">
                <input type="hidden" name="nama" id="input_nama">

                <div class="modal-body">
                    <div class="alert alert-info d-flex align-items-center p-4 mb-5">
                        <i class="ki-duotone ki-information-5 fs-2 text-info me-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        <span class="fw-semibold">Total komisi yang akan dicairkan: <strong id="modalTotalPencairan">Rp 0</strong></span>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Bank Tujuan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="bank_tujuan" id="bank_tujuan" required value="<?= esc($affiliate['bank'] ?? '') ?>" placeholder="Masukkan nama bank">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Nomor Rekening <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="no_rekening" id="no_rekening" required value="<?= esc($affiliate['norek'] ?? '') ?>" placeholder="Masukkan nomor rekening">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Atas Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="atas_nama" id="atas_nama" required value="<?= esc($affiliate['nama_akun_bank'] ?? '') ?>" placeholder="Masukkan nama pemilik rekening">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Potongan PPh21 (Rp)</label>
                        <input type="number" class="form-control" name="potongan_pph21" id="potongan_pph21" required min="0" placeholder="Contoh: 15000">
                        <div class="form-text">Masukkan nominal potongan pajak (jika ada, masukkan 0 jika tidak ada).</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Biaya Admin / Transfer (Rp)</label>
                        <input type="number" class="form-control" name="biaya_admin" id="biaya_admin" min="0" value="0" placeholder="Contoh: 2500 atau 6500">
                        <div class="form-text">Masukkan biaya transfer antar bank. Masukkan 0 jika gratis.</div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold">Bukti Transfer <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="bukti_transfer" id="bukti_transfer" accept=".jpg,.jpeg,.png" required>
                        <div class="form-text text-muted">Format file yang didukung: JPG, JPEG, PNG.</div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitForm">
                        <span class="indicator-label">Kirim Pencairan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==============================================
     MODAL DETAIL PENCAIRAN (UNTUK MELIHAT BUKTI & RINCIAN)
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
                        <!-- Gambar bukti transfer akan dimuat di sini secara dinamis -->
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

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(function() {

        function formatRupiah(angka) {
            return 'Rp ' + angka.toLocaleString('id-ID');
        }

        function hitungTotal() {
            let total = 0;
            let checked = $('.komisi-check:checked');

            checked.each(function() {
                total += parseFloat($(this).data('total'));
            });

            $('#totalKomisi').text(formatRupiah(total));
            $('#modalTotalPencairan').text(formatRupiah(total));

            // Enable tombol jika total pencairan >= 100rb
            $('#btnUpdateKomisi').prop('disabled', total < 100000 || checked.length === 0);
        }

        // Check all
        $('#checkAll').on('change', function() {
            $('.komisi-check').prop('checked', this.checked);
            hitungTotal();
        });

        // Per checkbox
        $('.komisi-check').on('change', function() {
            hitungTotal();
        });

        // Tombol Bulk -> Buka Modal
        $('#btnUpdateKomisi').on('click', function() {
            let email = $('#dataUser').data('email');
            let nama  = $('#dataUser').data('nama');
            $('#input_email').val(email);
            $('#input_nama').val(nama);

            // Buka Modal Bootstrap
            $('#modalPencairan').modal('show');
        });

        // Submit Form Pencairan via AJAX
        $('#formPencairan').on('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Konfirmasi Pencairan?',
                text: 'Pastikan data transfer dan file bukti sudah benar.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Proses Sekarang',
                cancelButtonText: 'Batal'
            }).then(function(result) {

                if (result.isConfirmed || result.value) {

                    let formData = new FormData($('#formPencairan')[0]);

                    $('.komisi-check:checked').each(function() {
                        formData.append('ids[]', $(this).data('id'));
                    });

                    $('#modalPencairan').modal('hide');
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: "<?= base_url('sw-admin/affiliate/processKomisi') ?>",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success'
                                }).then(function() {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: response.message,
                                    icon: 'error'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Terjadi Kesalahan!',
                                text: 'Gagal terhubung ke server. Silakan muat ulang halaman dan coba lagi.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });

        // Klik Tombol Lihat Detail Pencairan
        $('.-view-pencairan, .btn-view-pencairan').on('click', function() {
            let idkomisi = $(this).data('id-komisi');

            // Tampilkan modal terlebih dahulu dengan state loading
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

            // Ambil data detail via AJAX ke Controller (Pastikan Anda sudah membuat endpoint/method ini atau menyesuaikannya)
            $.ajax({
                url: "<?= base_url('sw-admin/affiliate/getDetailPencairan') ?>/" + idkomisi,
                type: "GET",
                dataType: "json",
                success: function(res) {
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
                error: function() {
                    Swal.fire('Gagal', 'Tidak dapat mengambil data detail pencairan.', 'error');
                }
            });
        });

    });
</script>
<?= $this->endSection(); ?>
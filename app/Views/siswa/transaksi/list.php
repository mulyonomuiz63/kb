<?= $this->extend('siswa/template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid py-3 py-lg-6 mt-8">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card shadow-sm">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                            <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Cari Transaksi..." />
                        </div>
                    </div>
                </div>

                <div class="card-body py-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_transaksi">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px">Detail Paket</th>
                                    <th class="text-center min-w-100px">Nominal</th>
                                    <th class="text-center min-w-125px">Metode</th>
                                    <th class="text-center min-w-125px">Status</th>
                                    <th class="text-end min-w-70px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                <?php foreach ($transaksi as $s) :
                                    $diskon = ($s->nominal * $s->diskon) / 100;
                                    $totalDiskon = $s->nominal - $diskon;
                                    $diskon_voucher = ($totalDiskon * $s->voucher) / 100;
                                    $grand_total = $totalDiskon - $diskon_voucher;
                                ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 text-hover-primary mb-1 fw-bold"><?= ucwords(strtolower($s->nama_paket)); ?></span>
                                                <span class="fs-7 text-muted"><?= $s->jenis_paket; ?></span>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <span class="fw-bold text-dark">Rp <?= number_format($grand_total, 0, '.', '.'); ?></span>
                                        </td>

                                        <td class="text-center">
                                            <?php if ($s->jenis_bayar == 'manual') : ?>
                                                <span class="badge badge-light-success fw-bold">Manual Transfer</span>
                                            <?php elseif ($s->jenis_bayar == 'online') : ?>
                                                <span class="badge badge-light-primary fw-bold">Payment Gateway</span>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <?php
                                            $statusMap = [
                                                'S'  => ['color' => 'success', 'text' => 'Lunas'],
                                                'P'  => ['color' => 'warning', 'text' => ($s->jenis_bayar == "manual" ? "Menunggu Verifikasi" : "Belum Bayar")],
                                                'M'  => ['color' => 'info',    'text' => 'Menunggu Pembayaran'],
                                                'V'  => ['color' => 'primary', 'text' => 'Sedang Diproses'],
                                                'PM' => ['color' => 'danger',  'text' => 'Pending'],
                                                'E'  => ['color' => 'secondary', 'text' => 'Expired'],
                                            ];
                                            $st = $statusMap[$s->status] ?? ['color' => 'secondary', 'text' => 'Unknown'];
                                            ?>
                                            <span class="badge badge-light-<?= $st['color'] ?> fw-bold"><?= $st['text'] ?></span>
                                        </td>

                                        <td class="text-end">
                                            <?php if ($s->status == 'S') : ?>
                                                <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm btn_invoice_cetak"
                                                    data-invoice="<?= base_url('sw-siswa/transaksi/invoice/' . encrypt_url($s->idtransaksi)); ?>"
                                                    data-bs-toggle="tooltip" title="Unduh Invoice">
                                                    <i class="ki-outline ki-document fs-2"></i>
                                                </button>

                                            <?php elseif ($s->status == 'P') : ?>
                                                <a href="<?= base_url('sw-siswa/transaksi/pesan-bayar/' . encrypt_url($s->idtransaksi)) ?>" class="btn btn-sm btn-primary fw-bold">
                                                    Bayar Sekarang
                                                </a>

                                            <?php elseif ($s->status == 'M' && !empty($s->token)) : ?>
                                                <button type="button"
                                                    class="btn btn-sm btn-light-info fw-bold btn-bayar"
                                                    data-id="<?= encrypt_url($s->idtransaksi) ?>">
                                                    Lanjut Bayar
                                                </button>

                                            <?php elseif ($s->status == 'V') : ?>
                                                <button type="button" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $s->idtransaksi ?>">
                                                    <i class="ki-outline ki-eye fs-2"></i>
                                                </button>

                                            <?php else: ?>
                                                <span class="text-muted fs-7">No Action</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                    <?php if ($s->status == 'V') : ?>
                                        <div class="modal fade" id="modalDetail<?= $s->idtransaksi ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="fw-bold">Informasi Paket</h2>
                                                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                            <i class="ki-outline ki-cross fs-1"></i>
                                                        </div>
                                                    </div>
                                                    <div class="modal-body py-10 px-lg-17">
                                                        <div class="d-flex flex-column mb-5 fv-row">
                                                            <div class="fs-5 fw-bold mb-2">Detail Paket:</div>
                                                            <div class="bg-light-primary rounded p-5">
                                                                <div class="d-flex flex-stack mb-3">
                                                                    <span class="fw-semibold text-gray-600">Nama Paket</span>
                                                                    <span class="fw-bold text-gray-800"><?= $s->nama_paket; ?></span>
                                                                </div>
                                                                <div class="d-flex flex-stack">
                                                                    <span class="fw-semibold text-gray-600">Total Bayar</span>
                                                                    <span class="fw-bold text-success fs-4">Rp <?= number_format($grand_total, 0, '.', '.'); ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">
                                                            <i class="ki-outline ki-information fs-2tx text-warning me-4"></i>
                                                            <div class="d-flex flex-stack flex-grow-1">
                                                                <div class="fw-semibold">
                                                                    <h4 class="text-gray-900 fw-bold">Menunggu Aktivasi</h4>
                                                                    <div class="fs-6 text-gray-700">Waktu proses: 09:00 - 17:00 WIB</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="invoice_cetak_modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-primary">
                <h2 class="fw-bold text-white mb-0">E-Invoice</h2>
                <div class="btn btn-icon btn-sm btn-color-white btn-active-color-dark" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body p-0">
                <div id="isiKontenInvoice" style="height: 70vh;">
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // 1. Inisialisasi DataTable
        const table = $('#kt_table_transaksi').DataTable({
            "info": false,
            'order': [],
            "lengthChange": false,
            'pageLength': 10,
            "dom": "lrtip" // 'f' dihilangkan agar search bar bawaan DataTable tersembunyi
        });

        // 2. Hubungkan Input Custom ke DataTable
        $('[data-kt-user-table-filter="search"]').on('keyup', function() {
            table.search($(this).val()).draw();
        });

        // 3. Handle Klik Invoice (Kode lama Anda)
        $('.btn_invoice_cetak').on('click', function() {
            const url = $(this).data('invoice');
            const modal = new bootstrap.Modal(document.getElementById('invoice_cetak_modal'));

            $("#isiKontenInvoice").html(`
            <iframe src="${url}" width="100%" height="100%" style="border:none;"></iframe>
        `);

            modal.show();
        });
    });
</script>

<script>
    $(document).on('click', '.btn-bayar', function(e) {
        e.preventDefault();

        let btn = $(this);
        let idt = btn.data('id');

        // Ambil token CSRF dari meta tag (pastikan meta tag sudah ada di header)
        let csrfName = $('meta[name="X-CSRF-TOKEN"]').attr('name') || '<?= csrf_token() ?>';
        let csrfHash = $('meta[name="X-CSRF-TOKEN"]').attr('content') || '<?= csrf_hash() ?>';

        // Beri loading pada tombol agar tidak diklik berkali-kali
        btn.addClass('disabled').html('<span class="spinner-border spinner-border-sm"></span> Loading...');

        $.ajax({
            url: "<?= base_url('sw-siswa/transaksi/midtrans-bayar') ?>/" + idt, // Sesuaikan route Anda
            type: "GET",
            data: {
                [csrfName]: csrfHash
            },
            dataType: "JSON",
            success: function(response) {
                // Update CSRF Hash agar request selanjutnya tidak error 403
                if (response.csrf_hash) {
                    $('meta[name="X-CSRF-TOKEN"]').attr('content', response.csrf_hash);
                }

                if (response.status) {
                    // Eksekusi Modal Snap
                    window.snap.pay(response.snap_token, {
                        onSuccess: function(result) {
                            Swal.fire('Berhasil!', 'Pembayaran Anda telah sukses.', 'success').then(() => {
                                // Arahkan ke halaman riwayat transaksi agar user bisa melihat status terupdate
                                window.location.href = "<?= base_url('sw-siswa/transaksi') ?>";
                            });
                        },
                        onPending: function(result) {
                            // Tampilkan nomor VA atau instruksi pembayaran (opsional, Midtrans sudah menampilkannya)
                            Swal.fire('Pending', 'Silahkan selesaikan pembayaran sesuai instruksi.', 'warning').then(() => {
                                window.location.href = "<?= base_url('sw-siswa/transaksi') ?>";
                            });
                        },
                        onError: function(result) {
                            Swal.fire('Gagal', 'Pembayaran gagal atau dibatalkan.', 'error');
                            btn.removeClass('disabled').text('Lanjut Bayar');
                        },
                        onClose: function() {
                            // Jika user menutup modal tanpa bayar
                            console.log('Customer closed the popup without finishing the payment');
                            btn.removeClass('disabled').text('Lanjut Bayar');
                        }
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                    btn.removeClass('disabled').text('Lanjut Bayar');
                }
            },
            error: function() {
                Swal.fire('Error', 'Gagal menghubungi server.', 'error');
                btn.removeClass('disabled').text('Lanjut Bayar');
            }
        });
    });
</script>
<?= $this->endSection(); ?>
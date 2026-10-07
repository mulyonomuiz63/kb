<?= $this->extend('siswa/template/app'); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid py-3 py-lg-6 mt-8">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <!-- Banner Informasi -->
            <div class="alert alert-dismissible bg-light-primary border border-primary d-flex flex-column flex-sm-row p-5 mb-10 rounded">
                <!-- Icon disesuaikan agar posisinya tetap di atas (align-self-start) karena isi teks sekarang lebih panjang -->
                <i class="ki-outline ki-information-5 fs-2hx text-primary me-4 mb-5 mb-sm-0 align-self-start mt-1"></i>

                <div class="d-flex flex-column pe-0 pe-sm-10">
                    <h4 class="fw-bold text-primary">Informasi Pengajuan Sertifikat Fisik</h4>
                    <span class="text-gray-800 mb-3">
                        Anda dapat mengajukan pencetakan sertifikat dengan <b>Cap Basah dan Tanda Tangan Asli</b>. <br>
                        Biaya administrasi dan pengiriman adalah sebesar <span class="fw-bolder text-danger">Rp 100.000</span>. Dokumen akan dikirim ke alamat yang Anda berikan melalui kurir.
                    </span>

                    <!-- Tambahan Info dan Tombol Tracking -->
                    <div class="bg-white bg-opacity-50 rounded p-4 mt-2 border border-primary border-dashed">
                        <span class="text-gray-700 fw-semibold d-block mb-3 fs-7">
                            Jika status pengajuan sudah dikirim, Anda dapat mengecek perjalanan paket menggunakan Nomor Resi melalui website resmi JNE.
                        </span>
                        <a href="https://jne.co.id/tracking-package" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-primary fw-bold">
                            <i class="ki-outline ki-truck fs-4 me-1"></i> Lacak Resi JNE
                        </a>
                    </div>

                </div>
            </div>

            <!-- Card Data Pengajuan -->
            <div class="card card-flush shadow-sm">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <h3 class="fw-bold m-0"><i class="bi bi-clock-history me-2"></i>Riwayat Pengajuan</h3>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalPengajuan">
                            <i class="bi bi-envelope-paper fs-4 me-2"></i> Ajukan Pengiriman
                        </button>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="dt_pengajuan">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th>Order ID</th>
                                    <th>Tanggal</th>
                                    <th>Penerima</th>
                                    <th class="min-w-200px">Alamat Pengiriman</th>
                                    <th>Status Bayar</th>
                                    <th>Status Kurir</th>
                                    <th>No. Resi</th>
                                    <th class="text-end min-w-100px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                <?php $no = 1;
                                foreach ($pengajuan as $row): ?>
                                    <?php
                                    // LOGIKA STATUS PEMBAYARAN
                                    if ($row->status == 'S') {
                                        $statusBayar = '<span class="badge badge-light-success fw-bold px-3 py-2">Berhasil</span>';
                                    } elseif ($row->status == 'M' || $row->status == 'P') {
                                        $statusBayar = '<span class="badge badge-light-warning fw-bold px-3 py-2">Pending</span>';
                                    } else {
                                        $statusBayar = '<span class="badge badge-light-danger fw-bold px-3 py-2">Gagal</span>';
                                    }

                                    // LOGIKA STATUS PENGIRIMAN
                                    if ($row->status_pengiriman == 'dikirim') {
                                        $badgeKirim = '<span class="badge badge-light-success fw-bold px-3 py-2"><i class="ki-outline ki-delivery-time fs-6 me-1"></i> Dikirim</span>';
                                    } elseif ($row->status_pengiriman == 'diproses') {
                                        $badgeKirim = '<span class="badge badge-light-primary fw-bold px-3 py-2"><i class="ki-outline ki-setting-2 fs-6 me-1"></i> Diproses</span>';
                                    } else {
                                        $badgeKirim = '<span class="badge badge-light-secondary fw-bold px-3 py-2">Menunggu</span>';
                                    }

                                    // TOMBOL BAYAR
                                    $aksi = '';
                                    if ($row->status == 'M' && !empty($row->token)) {
                                        $aksi = '<button class="btn btn-sm btn-primary fw-bold btn-bayar" data-token="' . esc($row->token) . '"><i class="bi bi-wallet2"></i> Bayar</button>';
                                    }
                                    ?>
                                    <tr>
                                        <td><span class="fw-bold text-gray-800"><?= $row->idtransaksi ?></span></td>
                                        <td><?= date('d M Y H:i', strtotime($row->created_at)) ?></td>
                                        <td><b><?= esc($row->nama_penerima) ?></b><br><span class="text-muted fs-7"><?= esc($row->no_hp) ?></span></td>
                                        <td>
                                            <div class="text-break" style="max-width:200px;"><?= esc($row->alamat_lengkap) ?> (<?= esc($row->kode_pos) ?>)</div>
                                        </td>
                                        <td><?= $statusBayar ?></td>
                                        <td><?= $badgeKirim ?></td>
                                        <td><?= !empty($row->no_resi) ? '<span class="badge badge-light fw-bold text-dark">' . esc($row->no_resi) . '</span>' : '-' ?></td>
                                        <td class="text-end"><?= $aksi ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Form Pengajuan (SAMA SEPERTI SEBELUMNYA) -->
<div class="modal fade" id="modalPengajuan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal"><i class="ki-outline ki-cross fs-1"></i></div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7 pt-0">
                <div class="text-center mb-10">
                    <h1 class="mb-3">Form Pengiriman Sertifikat Fisik</h1>
                    <div class="text-muted fw-semibold fs-5">Total Biaya Pembayaran: <span class="fw-bolder text-danger">Rp 100.000</span></div>
                </div>

                <form id="formPengajuan" class="form">
                    <?= csrf_field() ?>
                    <div class="d-flex flex-column mb-7 fv-row">
                        <label class="required fs-6 fw-semibold form-label mb-2">Nama Lengkap Penerima</label>
                        <input type="text" class="form-control form-control-solid" placeholder="Nama sesuai KTP" name="nama_penerima" required />
                    </div>
                    <div class="d-flex flex-column mb-7 fv-row">
                        <label class="required fs-6 fw-semibold form-label mb-2">Nomor HP / WhatsApp Aktif</label>
                        <input type="number" class="form-control form-control-solid" placeholder="081234567890" name="no_hp" required />
                    </div>
                    <div class="d-flex flex-column mb-7 fv-row">
                        <label class="required fs-6 fw-semibold form-label mb-2">Alamat Lengkap</label>
                        <textarea class="form-control form-control-solid" rows="3" name="alamat_lengkap" placeholder="Jalan, RT/RW, Desa/Kelurahan" required></textarea>
                    </div>
                    <div class="d-flex flex-column mb-7 fv-row">
                        <label class="required fs-6 fw-semibold form-label mb-2">Kode Pos</label>
                        <input type="number" class="form-control form-control-solid" placeholder="Contoh: 12345" name="kode_pos" required />
                    </div>

                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="btnSubmitPengajuan" class="btn btn-primary">
                            <span class="indicator-label"><i class="bi bi-wallet2"></i> Lanjutkan Pembayaran</span>
                            <span class="indicator-progress">Harap tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<!-- PENTING: Pastikan CLIENT_KEY_MIDTRANS_ANDA sudah diubah ke Client Key yang asli -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="CLIENT_KEY_MIDTRANS_ANDA"></script>

<script>
    $(document).ready(function() {
        // 1. Inisialisasi DataTables Statis (Sangat simpel)
        $('#dt_pengajuan').DataTable();

        // 2. Proses Submit Form Pengajuan Baru
        $('#formPengajuan').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = $('#btnSubmitPengajuan');

            btn.attr("data-kt-indicator", "on").prop("disabled", true);

            $.ajax({
                url: "<?= base_url('sw-siswa/sertifikat/pengajuan-sertifikat/store') ?>",
                type: "POST",
                data: form.serialize(),
                dataType: "JSON",
                success: function(response) {
                    btn.removeAttr("data-kt-indicator").prop("disabled", false);

                    if (response.status === 'success') {
                        $('#modalPengajuan').modal('hide');
                        form[0].reset();

                        // Munculkan Midtrans Snap Pop-up
                        snap.pay(response.token, {
                            onSuccess: function(result) {
                                Swal.fire("Berhasil!", "Pembayaran berhasil diterima.", "success").then(() => {
                                    window.location.reload(); // Muat ulang halaman untuk update tabel
                                });
                            },
                            onPending: function(result) {
                                Swal.fire("Menunggu!", "Selesaikan pembayaran Anda.", "info").then(() => {
                                    window.location.reload();
                                });
                            },
                            onError: function(result) {
                                Swal.fire("Gagal!", "Pembayaran gagal diproses.", "error").then(() => {
                                    window.location.reload();
                                });
                            },
                            onClose: function() {
                                Swal.fire("Perhatian!", "Anda menutup popup tanpa membayar. Lanjutkan melalui tabel riwayat.", "warning").then(() => {
                                    window.location.reload();
                                });
                            }
                        });
                    } else {
                        Swal.fire("Error!", response.message, "error");
                    }
                },
                error: function(xhr) {
                    btn.removeAttr("data-kt-indicator").prop("disabled", false);
                    var errMsg = "Terjadi kesalahan pada server.";
                    if (xhr.responseJSON && xhr.responseJSON.message) errMsg = xhr.responseJSON.message;
                    Swal.fire("Error!", errMsg, "error");
                }
            });
        });

        // 3. Tombol "Bayar" di dalam Tabel
        $('#dt_pengajuan').on('click', '.btn-bayar', function() {
            var snapToken = $(this).data('token');

            snap.pay(snapToken, {
                onSuccess: function(result) {
                    Swal.fire("Berhasil!", "Pembayaran berhasil diterima.", "success").then(() => {
                        window.location.reload();
                    });
                },
                onPending: function(result) {
                    Swal.fire("Menunggu!", "Selesaikan pembayaran Anda.", "info").then(() => {
                        window.location.reload();
                    });
                },
                onError: function(result) {
                    Swal.fire("Gagal!", "Pembayaran gagal diproses.", "error");
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>
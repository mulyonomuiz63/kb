<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?php $db = Config\Database::connect(); ?>

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">
            <div class="widget shadow p-3 border-0 bg-white" style="border-radius: 15px;">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="widget-heading d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="font-weight-bold">Manajemen Voucher</h5>
                                <p class="text-muted small">Kelola kode promo dan diskon mitra Anda</p>
                            </div>
                            <div>
                                <a href="<?= base_url('sw-admin/mitra') ?>" class="btn btn-outline-info rounded-pill px-4">
                                    <i class="bi bi-people me-1"></i> Data Mitra
                                </a>
                                <a href="javascript:void(0)" class="btn btn-primary rounded-pill px-4 shadow" data-toggle="modal" data-target="#tambah_voucher">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah Voucher
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="datatable-list" class="table table-hover text-nowrap">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Mitra</th>
                                        <th>Kode Voucher</th>
                                        <th class="text-center">Diskon</th>
                                        <th>Masa Berlaku</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Paket</th>
                                        <th class="text-center">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($voucher as $s) : ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-light-primary text-primary rounded-circle p-2 mr-2 text-center" style="width:35px">
                                                        <i class="bi bi-person"></i>
                                                    </div>
                                                    <span class="fw-bold"><?= $s->nama_mitra; ?></span>
                                                </div>
                                            </td>
                                            <td><code class="text-primary fw-bold" style="font-size: 1.1em;"><?= $s->kode_voucher; ?></code></td>
                                            <td class="text-center">
                                                <span class="badge badge-light-success text-success px-3 fw-bold"><?= $s->diskon_voucher; ?> %</span>
                                            </td>
                                            <td>
                                                <small class="text-muted d-block">Aktif: <?= $s->tgl_aktif; ?></small>
                                                <small class="text-danger d-block">Exp: <?= $s->tgl_exp; ?></small>
                                            </td>
                                            <td class="text-center">
                                                <?= $s->status == 'A' ?
                                                    '<span class="badge bg-success rounded-pill px-3">Aktif</span>' :
                                                    '<span class="badge bg-danger rounded-pill px-3">Non-Aktif</span>'; ?>
                                            </td>
                                            <?php $totalPaket = $db->query("select count(iddetailvoucher) as total from detail_voucher where idvoucher = '$s->idvoucher'")->getRow(); ?>
                                            <td class="text-center">
                                                <span class="badge bg-light-info text-info rounded-circle"><?= $totalPaket->total ?></span>
                                            </td>
                                            <td class="text-center">
                                                <div class="dropdown custom-dropdown">
                                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical h5 text-muted"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right shadow border-0" aria-labelledby="dropdownMenuLink">
                                                        <a class="dropdown-item edit-voucher py-2" href="javascript:void(0)" data-toggle="modal" data-target="#edit_voucher" data-voucher="<?= encrypt_url($s->idvoucher); ?>">
                                                            <i class="bi bi-pencil text-primary me-2"></i> Edit
                                                        </a>
                                                        <a class="dropdown-item py-2" href="<?= base_url('sw-admin/mitra/daftar-paket/' . encrypt_url($s->idvoucher)); ?>">
                                                            <i class="bi bi-box me-2 text-warning"></i> Daftar Paket
                                                        </a>
                                                        <a class="dropdown-item py-2" href="<?= base_url('sw-admin/mitra/detail-komisi/' . encrypt_url($s->kode_voucher)); ?>">
                                                            <i class="bi bi-bar-chart me-2 text-info"></i> Komisi
                                                        </a>
                                                    </div>
                                                </div>
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
    </div>
</div>

<div class="modal fade" id="tambah_voucher" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold">Tambah Voucher Baru</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <form action="<?= base_url('sw-admin/mitra/store-voucher'); ?>" method="POST">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf-token" />
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="small fw-bold">Pilih Mitra</label>
                            <select name="idmitra" class="form-control" required>
                                <option value="">Pilih Mitra...</option>
                                <?php foreach ($mitra as $rows): ?>
                                    <option value="<?= $rows->idmitra ?>"><?= $rows->nama_mitra ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="small fw-bold">Kode Voucher</label>
                            <input type="text" name="kode_voucher" id="kode_voucher" class="form-control" required placeholder="Contoh: PROMO2024">
                            <div id="informasi"></div>
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label class="small fw-bold">Diskon (%)</label>
                            <input type="text" name="diskon_voucher" id="diskon_voucher" class="form-control" required placeholder="0.00">
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label class="small fw-bold">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="A">Aktif</option>
                                <option value="T">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label class="small fw-bold">Tgl Expire</label>
                            <input type="date" name="tgl_exp" class="form-control">
                        </div>
                    </div>

                    <div class="bg-light p-3 rounded mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <label class="fw-bold m-0">Paket Terhubung</label>
                            <button type="button" class="btn btn-sm btn-primary rounded-pill tambah-baris-paket">
                                <i class="bi bi-plus"></i> Tambah Paket
                            </button>
                        </div>
                        <table class="table table-sm bg-white">
                            <tbody id="tbody-paket">
                                <tr>
                                    <td width="90%">
                                        <select name="idpaket[]" class="form-control border-0" required>
                                            <option value="">-- Pilih Paket --</option>
                                            <?php foreach ($paket as $rowsPaket) : ?>
                                                <option value="<?= $rowsPaket->idpaket; ?>"><?= $rowsPaket->nama_paket; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td class="text-center"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow">Simpan Voucher</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_voucher" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold">Edit Voucher</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <form action="<?= base_url('sw-admin/mitra/update-voucher'); ?>" method="POST">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf-token" />
                <input type="hidden" name="idvoucher" id="idvoucher">
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="small fw-bold">Mitra Pemilik</label>
                        <select name="idmitra" id="idmitra" class="form-control" required>
                            <?php foreach ($mitra as $rows): ?>
                                <option value="<?= $rows->idmitra ?>"><?= $rows->nama_mitra ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="small fw-bold">Diskon Voucher (%)</label>
                        <input type="text" name="diskon_voucher" id="e_diskon_voucher" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="small fw-bold">Status Voucher</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="A">Aktif</option>
                            <option value="T">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="small fw-bold">Tgl Exp <span id="tgl_exp_label"></span></label>
                        <input type="date" name="tgl_exp" class="form-control">
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow">Update Voucher</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script>
    // Menangkap Flashdata Pesan
    $(document).ready(function() {
        $('#datatable-list').DataTable({
            "ordering": false
        });
        <?php if (session()->getFlashdata('success')) : ?>
            Swal.fire({
                title: 'Berhasil!',
                text: '<?= session()->getFlashdata("success") ?>',
                icon: 'success',
                timer: 3000
            });
        <?php endif; ?>

        // Update CSRF secara global setiap request AJAX selesai
        $(document).ajaxComplete(function(event, xhr, settings) {
            if (xhr.responseJSON && xhr.responseJSON.token) {
                $('.csrf-token').val(xhr.responseJSON.token);
            }
        });

        // Tambah Baris Paket di Modal
        $('.tambah-baris-paket').click(function() {
            const row = `<tr>
                <td>
                    <select name="idpaket[]" class="form-control border-0" required>
                        <option value="">-- Pilih Paket --</option>
                        <?php foreach ($paket as $rowsPaket) : ?>
                            <option value="<?= $rowsPaket->idpaket; ?>"><?= $rowsPaket->nama_paket; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-danger btn-sm rounded-circle btn-remove">×</button>
                </td>
            </tr>`;
            $('#tbody-paket').append(row);
        });

        $(document).on('click', '.btn-remove', function() {
            $(this).closest('tr').remove();
        });

        // Edit Voucher AJAX
        $('.edit-voucher').click(function() {
            const idvoucher = $(this).data('voucher');
            const csrfName = "<?= csrf_token() ?>";
            const csrfToken = $('.csrf-token').val();

            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-admin/mitra/edit-voucher') ?>",
                data: {
                    idvoucher: idvoucher,
                    [csrfName]: csrfToken
                },
                dataType: 'JSON',
                success: function(data) {
                    // 1. UPDATE TOKEN CSRF (Sangat penting agar klik selanjutnya tidak 403)
                    if (data.token_baru) {
                        $('.csrf-token').val(data.token_baru);
                    }

                    // 2. ISI FIELD (Akses lewat data.voucher karena di controller kita bungkus begitu)
                    const v = data.voucher;
                    $("#idvoucher").val(v.idvoucher); // Pastikan ini ID yang terenkripsi jika perlu
                    $("#idmitra").val(v.idmitra);
                    $("#e_diskon_voucher").val(v.diskon_voucher);
                    $("#status").val(v.status);
                    $("#tgl_exp_label").html("<span class='text-danger'>(Lama: " + v.tgl_exp + ")</span>");

                }
            });
        });

        // Cek Kode Voucher
        $('#kode_voucher').keyup(function() {
            const val = $(this).val();
            if (val.length < 3) return;
            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-admin/mitra/get-voucher') ?>",
                data: {
                    id: val,
                    "<?= csrf_token() ?>": $('.csrf-token').val()
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == '1') {
                        $("#informasi").html('<small class="text-danger fw-bold">Kode sudah digunakan!</small>');
                    } else {
                        $("#informasi").html('<small class="text-success fw-bold">Kode tersedia.</small>');
                    }
                }
            });
        });

        // Format Angka/Decimal
        $('#diskon_voucher').on('input', function() {
            this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
        });
        // Format Angka/Decimal
        $('#e_diskon_voucher').on('input', function() {
            this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
        });
    });
</script>

<?= $this->endSection(); ?>
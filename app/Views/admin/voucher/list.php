<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?php $db = Config\Database::connect(); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title flex-column align-items-start">
                        <h3 class="fw-bold mb-1">Daftar Voucher Mitra</h3>
                        <span class="text-muted fs-7">Kelola kode promo dan diskon mitra Anda</span>
                    </div>
                    <div class="card-toolbar gap-3">
                        <a href="<?= base_url('sw-admin/mitra') ?>" class="btn btn-light-info">
                            <i class="ki-duotone ki-users fs-2"><span class="path1"></span><span class="path2"></span></i> Data Mitra
                        </a>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambah_voucher">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah Voucher
                        </button>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatable-list" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Mitra</th>
                                    <th class="min-w-150px">Kode Voucher</th>
                                    <th class="min-w-100px text-center">Diskon</th>
                                    <th class="min-w-150px">Masa Berlaku</th>
                                    <th class="min-w-100px text-center">Status</th>
                                    <th class="min-w-100px text-center">Paket</th>
                                    <th class="text-center min-w-100px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                <?php foreach ($voucher as $s) : ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-35px symbol-circle me-3">
                                                    <div class="symbol-label bg-light-primary text-primary">
                                                        <i class="ki-duotone ki-user fs-3"><span class="path1"></span><span class="path2"></span></i>
                                                    </div>
                                                </div>
                                                <span class="fw-bold text-gray-800"><?= $s->nama_mitra; ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-primary fw-bold px-3 py-2 fs-6"><?= $s->kode_voucher; ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-light-success px-3 py-2 fw-bold"><?= $s->diskon_voucher; ?> %</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-muted fs-7">Aktif: <span class="text-gray-800"><?= $s->tgl_aktif; ?></span></span>
                                                <span class="text-danger fs-7">Exp: <?= $s->tgl_exp; ?></span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?= $s->status == 'A' ?
                                                '<span class="badge badge-light-success px-3 py-2">Aktif</span>' :
                                                '<span class="badge badge-light-danger px-3 py-2">Non-Aktif</span>'; ?>
                                        </td>
                                        <?php $totalPaket = $db->query("select count(iddetailvoucher) as total from detail_voucher where idvoucher = '$s->idvoucher'")->getRow(); ?>
                                        <td class="text-center">
                                            <span class="badge badge-circle badge-light-info text-info fs-6 px-2"><?= $totalPaket->total ?></span>
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                Aksi <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                            </a>
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4 text-start" data-kt-menu="true">

                                                <div class="menu-item px-3">
                                                    <a href="javascript:void(0)" class="menu-link px-3 edit-voucher" data-bs-toggle="modal" data-bs-target="#edit_voucher" data-voucher="<?= encrypt_url($s->idvoucher); ?>">
                                                        <i class="ki-duotone ki-pencil text-primary fs-4 me-2"><span class="path1"></span><span class="path2"></span></i> Edit Voucher
                                                    </a>
                                                </div>

                                                <div class="menu-item px-3">
                                                    <a class="menu-link px-3" href="<?= base_url('sw-admin/mitra/daftar-paket/' . encrypt_url($s->idvoucher)); ?>">
                                                        <i class="ki-duotone ki-element-11 text-warning fs-4 me-2">
                                                            <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                                                        </i> Daftar Paket
                                                    </a>
                                                </div>

                                                <div class="menu-item px-3">
                                                    <a class="menu-link px-3" href="<?= base_url('sw-admin/mitra/detail-komisi/' . encrypt_url($s->kode_voucher)); ?>">
                                                        <i class="ki-duotone ki-chart-simple text-info fs-4 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i> Detail Komisi
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
<div class="modal fade" id="tambah_voucher" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded">

            <div class="modal-header pb-0 border-0 justify-content-between">
                <h2 class="fw-bold">Tambah Voucher Baru</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <form action="<?= base_url('sw-admin/mitra/store-voucher'); ?>" method="POST" class="form">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf-token" />

                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-15">

                    <div class="row g-9 mb-8">
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Pilih Mitra</label>
                            <select name="idmitra" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Mitra..." data-dropdown-parent="#tambah_voucher" required>
                                <option value=""></option>
                                <?php foreach ($mitra as $rows): ?>
                                    <option value="<?= $rows->idmitra ?>"><?= $rows->nama_mitra ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Kode Voucher</label>
                            <input type="text" name="kode_voucher" id="kode_voucher" class="form-control form-control-solid" required placeholder="Contoh: PROMO2024">
                            <div id="informasi" class="mt-2"></div>
                        </div>
                    </div>

                    <div class="row g-9 mb-10">
                        <div class="col-md-4 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Diskon (%)</label>
                            <div class="input-group input-group-solid">
                                <input type="text" name="diskon_voucher" id="diskon_voucher" class="form-control form-control-solid" required placeholder="0.00">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-4 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Status</label>
                            <select name="status" class="form-select form-select-solid" data-control="select2" data-hide-search="true" required>
                                <option value="A">Aktif</option>
                                <option value="T">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-4 fv-row">
                            <label class="fs-6 fw-semibold mb-2">Tgl Expire</label>
                            <input type="date" name="tgl_exp" class="form-control form-control-solid">
                        </div>
                    </div>

                    <div class="border border-dashed border-gray-300 rounded p-7">
                        <div class="d-flex flex-stack mb-5">
                            <h3 class="fw-bold m-0 fs-5">Paket Terhubung</h3>
                            <button type="button" class="btn btn-sm btn-light-primary tambah-baris-paket">
                                <i class="ki-duotone ki-plus fs-2"></i> Tambah Paket
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-3">
                                <tbody id="tbody-paket">
                                    <tr>
                                        <td class="ps-0 w-100">
                                            <select name="idpaket[]" class="form-select form-select-solid" required>
                                                <option value="">-- Pilih Paket --</option>
                                                <?php foreach ($paket as $rowsPaket) : ?>
                                                    <option value="<?= $rowsPaket->idpaket; ?>"><?= $rowsPaket->nama_paket; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td class="text-end pe-0">
                                            <button type="button" class="btn btn-icon btn-light-danger btn-sm disabled" disabled>
                                                <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="text-center pt-10">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Simpan Voucher</span>
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_voucher" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded">

            <div class="modal-header pb-0 border-0 justify-content-between">
                <h2 class="fw-bold">Edit Voucher</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <form action="<?= base_url('sw-admin/mitra/update-voucher'); ?>" method="POST" class="form">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf-token" />
                <input type="hidden" name="idvoucher" id="idvoucher">

                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-15">

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Mitra Pemilik</label>
                        <select name="idmitra" id="idmitra" class="form-select form-select-solid" required>
                            <?php foreach ($mitra as $rows): ?>
                                <option value="<?= $rows->idmitra ?>"><?= $rows->nama_mitra ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Diskon Voucher (%)</label>
                        <div class="input-group input-group-solid">
                            <input type="text" name="diskon_voucher" id="e_diskon_voucher" class="form-control form-control-solid" required>
                            <span class="input-group-text">%</span>
                        </div>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Status Voucher</label>
                        <select name="status" id="status" class="form-select form-select-solid" required>
                            <option value="A">Aktif</option>
                            <option value="T">Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Tgl Exp <span id="tgl_exp_label"></span></label>
                        <input type="date" name="tgl_exp" class="form-control form-control-solid">
                    </div>

                    <div class="text-center pt-5">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Update Voucher</span>
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {

        // Inisialisasi Select2 bawaan template (jika ada form tambahan di UI)
        $('[data-control="select2"]').select2();

        // DataTables Inisialisasi ala Metronic
        var table = $('#datatable-list').DataTable({
            "ordering": false,
            "drawCallback": function(settings) {
                // Re-init KTMenu setiap kali data berubah (page next, search, dll)
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            }
        });

        // Search Custom Input Datatables
        $('[data-kt-mitra-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Tangkap Flashdata Pesan -> Rubah ke SweetAlert2 Metronic style
        <?php if (session()->getFlashdata('success')) : ?>
            Swal.fire({
                text: '<?= session()->getFlashdata("success") ?>',
                icon: 'success',
                buttonsStyling: false,
                confirmButtonText: "Ok, Mengerti!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
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
                <td class="ps-0 w-100">
                    <select name="idpaket[]" class="form-select form-select-solid" required>
                        <option value="">-- Pilih Paket --</option>
                        <?php foreach ($paket as $rowsPaket) : ?>
                            <option value="<?= $rowsPaket->idpaket; ?>"><?= $rowsPaket->nama_paket; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td class="text-end pe-0 align-middle">
                    <button type="button" class="btn btn-icon btn-light-danger btn-sm btn-remove">
                        <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    </button>
                </td>
            </tr>`;
            $('#tbody-paket').append(row);
        });

        // Hapus Baris Paket
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
                    if (data.token_baru) {
                        $('.csrf-token').val(data.token_baru);
                    }

                    const v = data.voucher;
                    $("#idvoucher").val(v.idvoucher);
                    $("#idmitra").val(v.idmitra);
                    $("#e_diskon_voucher").val(v.diskon_voucher);
                    $("#status").val(v.status);
                    $("#tgl_exp_label").html("<span class='badge badge-light-danger ms-2'>Lama: " + v.tgl_exp + "</span>");

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
                        $("#informasi").html('<span class="badge badge-light-danger mt-2">Kode sudah digunakan!</span>');
                    } else {
                        $("#informasi").html('<span class="badge badge-light-success mt-2">Kode tersedia.</span>');
                    }
                }
            });
        });

        // Format Angka/Decimal
        function commaOnly(input) {
            let value = input.val().replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
            input.val(value);
        }

        $('#diskon_voucher, #e_diskon_voucher').on('input', function() {
            commaOnly($(this));
        });
    });
</script>
<?= $this->endSection(); ?>
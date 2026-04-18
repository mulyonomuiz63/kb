<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <div class="card card-flush shadow-sm">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-45px me-5">
                            <span class="symbol-label bg-light-primary">
                                <i class="ki-duotone ki-bank text-primary fs-2x">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <h3 class="fw-bold text-gray-900 mb-1">Detail Rekening Affiliate</h3>
                            <span class="text-muted fw-semibold fs-7">Verifikasi dan kelola informasi pencairan komisi pengguna</span>
                        </div>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end">
                        <a href="<?= base_url('sw-admin/affiliate') ?>" class="btn btn-sm btn-light me-3">
                            <i class="ki-duotone ki-arrow-left fs-4 me-1"><span class="path1"></span><span class="path2"></span></i> Kembali
                        </a>
                        <button type="button" id="btnEdit" class="btn btn-sm btn-primary">
                            <i class="ki-duotone ki-notepad-edit fs-4 me-1"><span class="path1"></span><span class="path2"></span></i> Buka Kunci Edit
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body py-4">
                <form method="post" action="<?= base_url('sw-admin/affiliate/store') ?>" class="form">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id_affiliate" value="<?= $affiliate['id_affiliate'] ?? '' ?>">

                    <div class="row g-9">
                        <div class="col-md-7 border-end-md">
                            <h6 class="text-primary fw-bold text-uppercase fs-7 mb-7 ls-1">Informasi Rekening</h6>
                            
                            <div class="row fv-row mb-7">
                                <div class="col-md-3">
                                    <label class="fs-7 fw-bold mt-2 text-muted">NAMA BANK</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="bank" class="form-control form-control-solid fw-bold fs-6 editable-input" value="<?= esc($affiliate['bank'] ?? '-') ?>" readonly required />
                                </div>
                            </div>

                            <div class="row fv-row mb-7">
                                <div class="col-md-3">
                                    <label class="fs-7 fw-bold mt-2 text-muted">CABANG BANK</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="cabang_bank" class="form-control form-control-solid fw-bold fs-6 editable-input" value="<?= esc($affiliate['cabang_bank'] ?? '-') ?>" readonly required />
                                </div>
                            </div>

                            <div class="row fv-row mb-7">
                                <div class="col-md-3">
                                    <label class="fs-7 fw-bold mt-2 text-muted">NOMOR REKENING</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="norek" class="form-control form-control-solid fw-bold fs-6 editable-input text-primary" value="<?= esc($affiliate['norek'] ?? '-') ?>" readonly required />
                                </div>
                            </div>

                            <div class="row fv-row mb-10">
                                <div class="col-md-3">
                                    <label class="fs-7 fw-bold mt-2 text-muted text-uppercase">Pemilik Rekening</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="nama_akun_bank" class="form-control form-control-solid fw-bold fs-6 editable-input" value="<?= esc($affiliate['nama_akun_bank'] ?? '-') ?>" readonly required />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5 ps-md-10">
                            <h6 class="text-primary fw-bold text-uppercase fs-7 mb-7 ls-1">Status Verifikasi</h6>
                            
                            <div class="bg-light p-6 rounded-3 mb-7">
                                <label class="fw-bold fs-6 mb-3 text-gray-800">Update Status</label>
                                <select name="status" class="form-select form-select-solid" data-control="select2" data-hide-search="true" onchange="document.getElementById('formAction').classList.remove('d-none')" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="1" <?= isset($affiliate['status']) && $affiliate['status'] == '1' ? 'selected' : '' ?>>✅ Approved</option>
                                    <option value="0" <?= isset($affiliate['status']) && $affiliate['status'] == '0' ? 'selected' : '' ?>>⏳ Pending</option>
                                    <option value="2" <?= isset($affiliate['status']) && $affiliate['status'] == '2' ? 'selected' : '' ?>>❌ Reject</option>
                                </select>
                                <div class="d-flex align-items-start mt-4">
                                    <i class="ki-duotone ki-information-5 text-info fs-3 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    <span class="text-muted fs-7">Perubahan status akan langsung berdampak pada hak akses affiliate pengguna tersebut.</span>
                                </div>
                            </div>

                            <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">
                                <i class="ki-duotone ki-shield-search fs-2tx text-warning me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <div class="d-flex flex-stack flex-grow-1">
                                    <div class="fw-semibold">
                                        <div class="fs-7 text-gray-700">Data ini diinput langsung oleh siswa. Pastikan nomor rekening valid sebelum memberikan status <b>Approved</b>.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="formAction" class="mt-10 d-none border-top pt-8 text-end">
                        <button type="reset" class="btn btn-light-danger fw-bold me-3" onclick="location.reload();">Batal</button>
                        <button type="submit" class="btn btn-success fw-bold px-10">
                            <i class="ki-duotone ki-check-circle fs-3 me-1"><span class="path1"></span><span class="path2"></span></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<style>
    @media (min-width: 768px) {
        .border-end-md { border-right: 1px dashed var(--bs-gray-300); }
    }
    .form-control-solid:read-only { background-color: var(--bs-gray-100) !important; cursor: not-allowed; }
</style>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $('#btnEdit').on('click', function () {
        $('.editable-input')
            .prop('readonly', false)
            .removeClass('form-control-solid')
            .addClass('form-control-white border border-primary shadow-sm')
            .css('cursor', 'text');

        $('#formAction').hide().removeClass('d-none').fadeIn();
        $(this).fadeOut();
    });
</script>
<?= $this->endSection(); ?>
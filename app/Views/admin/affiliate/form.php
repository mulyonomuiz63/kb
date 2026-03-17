<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

    <div class="layout-px-spacing">
        <div class="row layout-top-spacing justify-content-center">
            <div class="col-xl-12 col-lg-12 layout-spacing">
                
                <div class="widget widget-card shadow-sm rounded-lg border-0">
                    
                    <div class="widget-header p-4 border-bottom bg-white rounded-top-lg">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="icon-container mr-3 bg-light-primary p-3 rounded-circle">
                                    <i class="fas fa-university text-primary fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 font-weight-bold text-dark">Detail Rekening Affiliate</h5>
                                    <p class="text-muted small mb-0">Verifikasi dan kelola informasi pencairan komisi pengguna</p>
                                </div>
                            </div>
                            <div class="btn-group-header">
                                <a href="<?= base_url('sw-admin/affiliate') ?>" class="btn btn-light shadow-none mr-2">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="button" id="btnEdit" class="btn btn-primary px-4">
                                    <i class="fas fa-edit mr-1"></i> Buka Kunci Edit
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="widget-content p-4 p-md-5 bg-white rounded-bottom-lg">
                        <form method="post" action="<?= base_url('sw-admin/affiliate/store') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id_affiliate" value="<?= $affiliate['id_affiliate'] ?? '' ?>">

                            <div class="row">
                                <div class="col-md-7 border-right-md pr-md-5">
                                    <h6 class="text-uppercase text-primary font-weight-bold small mb-4 tracking-wider">Informasi Rekening</h6>
                                    
                                    <div class="form-group mb-4">
                                        <label class="text-muted small font-weight-bold">NAMA BANK</label>
                                        <div class="input-group">
                                            <input type="text" name="bank" class="form-control form-control-lg bg-light border-0 shadow-none editable-input" value="<?= esc($affiliate['bank'] ?? '-') ?>" readonly required>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="text-muted small font-weight-bold">CABANG BANK</label>
                                        <input type="text" name="cabang_bank" class="form-control form-control-lg bg-light border-0 shadow-none editable-input" value="<?= esc($affiliate['cabang_bank'] ?? '-') ?>" readonly required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-4">
                                                <label class="text-muted small font-weight-bold">NOMOR REKENING</label>
                                                <input type="text" name="norek" class="form-control form-control-lg bg-light border-0 shadow-none editable-input" value="<?= esc($affiliate['norek'] ?? '-') ?>" readonly required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-4">
                                                <label class="text-muted small font-weight-bold">NAMA PEMILIK REKENING</label>
                                                <input type="text" name="nama_akun_bank" class="form-control form-control-lg bg-light border-0 shadow-none editable-input" value="<?= esc($affiliate['nama_akun_bank'] ?? '-') ?>" readonly required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5 pl-md-5">
                                    <h6 class="text-uppercase text-primary font-weight-bold small mb-4 tracking-wider">Status Verifikasi</h6>
                                    
                                    <div class="form-group mb-4 p-4 rounded-lg bg-light shadow-none">
                                        <label class="font-weight-bold text-dark">Update Status</label>
                                        <select name="status" class="form-control form-control-lg custom-select border-0 shadow-sm" onchange="document.getElementById('formAction').classList.remove('d-none')" required>
                                            <option value="">-- Pilih Status --</option>
                                            <option value="1" <?= isset($affiliate['status']) && $affiliate['status'] == '1' ? 'selected' : '' ?>>✅ Approved</option>
                                            <option value="0" <?= isset($affiliate['status']) && $affiliate['status'] == '0' ? 'selected' : '' ?>>⏳ Pending</option>
                                            <option value="2" <?= isset($affiliate['status']) && $affiliate['status'] == '2' ? 'selected' : '' ?>>❌ Reject</option>
                                        </select>
                                        <div class="mt-3">
                                            <p class="small text-muted mb-0">
                                                <i class="fas fa-info-circle mr-1 text-info"></i> 
                                                Perubahan status akan langsung berdampak pada hak akses affiliate pengguna tersebut.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="alert alert-info border-0 shadow-none small">
                                        Data ini diinput langsung oleh siswa. Pastikan nomor rekening valid sebelum memberikan status <strong>Approved</strong>.
                                    </div>
                                </div>
                            </div>

                            <div id="formAction" class="mt-5 d-none border-top pt-4 text-right">
                                <button type="submit" class="btn btn-success px-5 py-2 shadow-sm font-weight-bold">
                                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                                </button>
                                <button type="reset" class="btn btn-outline-danger ml-2 px-4 py-2 border-0" onclick="location.reload();">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="footer-wrapper mt-4">
                    <div class="footer-section text-center">
                        <p class="text-muted small"><?= copyright() ?></p>
                    </div>
                </div>

            </div>
        </div>
    </div>

<style>
    .widget-card { border-radius: 15px; overflow: hidden; background: #fff; }
    .bg-light-primary { background-color: #e8f1ff; }
    .tracking-wider { letter-spacing: 0.05em; }
    .form-control-lg { font-size: 1rem; border-radius: 10px; padding: 12px 15px; }
    .custom-select { height: calc(2.875rem + 2px); }
    .editable-input:focus { background: #fff !important; border: 1px solid #2196f3 !important; box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.1) !important; }
    @media (min-width: 768px) {
        .border-right-md { border-right: 1px dashed #e0e6ed; }
    }
    .btn { border-radius: 8px; font-weight: 500; transition: all 0.3s ease; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(33, 150, 243, 0.3); }
</style>
<?= $this->endSection(); ?>
<?= $this->section('scriptss'); ?>
<script>
    $('#btnEdit').on('click', function () {
        $('.editable-input')
            .prop('readonly', false)
            .removeClass('bg-light')
            .addClass('bg-white border shadow-none');

        $('#formAction').hide().removeClass('d-none').fadeIn();
        $(this).fadeOut();
    });
</script>

<?= $this->endSection(); ?>
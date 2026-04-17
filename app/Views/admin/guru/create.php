<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="card shadow-sm border-0">
                <div class="card-header pt-5 pb-4">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Form Registrasi Instruktur</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Lengkapi data di bawah ini untuk menambahkan instruktur baru.</span>
                    </h3>
                </div>

                <form action="<?= base_url('sw-admin/guru/store'); ?>" method="POST" id="formInstruktur" class="form">
                    <?= csrf_field(); ?>
                    
                    <div class="card-body py-7">
                        <div class="row">
                            
                            <div class="col-md-12 mb-8 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Nama Lengkap</label>
                                <div class="input-group input-group-solid">
                                    <span class="input-group-text">
                                        <i class="ki-duotone ki-user fs-2"><span class="path1"></span><span class="path2"></span></i>
                                    </span>
                                    <input type="text" name="nama_guru" class="form-control form-control-solid" placeholder="Contoh: John Doe, S.Kom" value="<?= old('nama_guru'); ?>" required>
                                </div>
                            </div>

                            <div class="col-md-12 mb-8 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Alamat Email</label>
                                <div class="input-group input-group-solid">
                                    <span class="input-group-text">
                                        <i class="ki-duotone ki-sms fs-2"><span class="path1"></span><span class="path2"></span></i>
                                    </span>
                                    <input type="email" name="email" class="form-control form-control-solid" placeholder="name@example.com" value="<?= old('email'); ?>" required>
                                </div>
                            </div>

                            <div class="col-md-12 mb-5 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Password</label>
                                <div class="input-group input-group-solid">
                                    <span class="input-group-text">
                                        <i class="ki-duotone ki-lock-3 fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    </span>
                                    <input type="password" name="password" id="passwordField" class="form-control form-control-solid" placeholder="Minimal 6 karakter" required>
                                    
                                    <button class="btn btn-icon btn-light-primary" type="button" id="togglePassword" data-bs-toggle="tooltip" title="Sembunyikan/Tampilkan Password">
                                        <i class="bi bi-eye fs-4" id="eyeIcon"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between align-items-center py-6">
                        <div class="text-muted fs-7">
                            <span class="text-danger fw-bold">*</span> Semua field wajib diisi
                        </div>
                        <div class="d-flex gap-3">
                            <button type="button" onclick="history.back()" class="btn btn-light fw-bold">Batal</button>
                            <button type="submit" class="btn btn-primary fw-bold">
                                <i class="ki-duotone ki-save-2 fs-2 me-1"><span class="path1"></span><span class="path2"></span></i> Simpan Instruktur
                            </button>
                        </div>
                    </div>
                    
                </form>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        
        // Inisialisasi Tooltip Bawaan Metronic
        KTApp.initBootstrapTooltips();

        // Fungsi Toggle Password Asli Anda (TIDAK DIUBAH)
        $('#togglePassword').on('click', function() {
            const passwordField = $('#passwordField');
            const eyeIcon = $('#eyeIcon');
            
            // Toggle tipe input
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            
            // Toggle ikon mata
            eyeIcon.toggleClass('bi-eye bi-eye-slash');
            
            // Berikan efek fokus kembali ke input
            passwordField.focus();
        });

    });
</script>
<?= $this->endSection(); ?>
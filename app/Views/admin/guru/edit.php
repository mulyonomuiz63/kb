<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>
<div class="layout-px-spacing">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-12 layout-top-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header border-bottom p-3 bg-white">
                    <h4 class="m-0 font-weight-bold text-primary">Edit Instruktur: <?= $guru->nama_guru; ?></h4>
                </div>

                <div class="widget-content widget-content-area p-4">
                    <form action="<?= base_url('sw-admin/guru/update/' . encrypt_url($guru->id_guru)); ?>" method="POST">
                        <?= csrf_field(); ?>
                        
                        <div class="row">
                            <div class="col-12 mb-4">
                                <label class="font-weight-bold">Nama Lengkap</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user text-primary"></i></span>
                                    </div>
                                    <input type="text" name="nama_guru" class="form-control" value="<?= old('nama_guru', $guru->nama_guru); ?>" required>
                                </div>
                            </div>

                            <div class="col-12 mb-4">
                                <label class="font-weight-bold">Alamat Email</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope text-primary"></i></span>
                                    </div>
                                    <input type="email" name="email" class="form-control" value="<?= old('email', $guru->email); ?>" required>
                                </div>
                            </div>

                            <div class="col-12 mb-4">
                                <label class="font-weight-bold">Password <small class="text-danger">(Kosongkan jika tidak ingin diubah)</small></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock text-primary"></i></span>
                                    </div>
                                    <input type="password" name="password" id="passwordField" class="form-control" placeholder="Isi hanya jika ingin mengganti password">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="bi bi-eye" id="eyeIcon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-2 border-top pt-4 d-flex justify-content-between">
                            <button type="button" onclick="history.back()" class="btn btn-light shadow-sm">Kembali</button>
                            <button type="submit" class="btn btn-primary shadow px-4">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
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
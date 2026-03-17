<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="layout-px-spacing my-4">
    <div class="row">

        <!-- ================= PROFILE CARD ================= -->
        <div class="col-lg-4 col-md-5 col-12 mb-4">
            <div class="card shadow-sm border-0 profile-card">
                <div class="card-body text-center">

                    <img src="<?= base_url('assets/app-assets/user/') . '/' . $admin->avatar; ?>"
                         class="rounded-circle mb-3 profile-avatar"
                         alt="avatar">

                    <h5 class="mb-1 font-weight-bold">
                        <?= $admin->nama_admin; ?>
                    </h5>

                    <p class="text-muted mb-2">Administrator</p>

                    <div class="profile-email">
                        <i class="fa fa-envelope mr-2 text-muted"></i>
                        <?= $admin->email; ?>
                    </div>

                </div>
            </div>
        </div>

        <!-- ================= FORM SECTION ================= -->
        <div class="col-lg-8 col-md-7 col-12">

            <!-- Update Profile -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold mb-4">
                        Update Profile
                    </h5>

                    <form action="<?= base_url('sw-admin/profile/update-profile'); ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text"
                                   name="nama_admin"
                                   value="<?= $admin->nama_admin; ?>"
                                   class="form-control form-control-lg"
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email"
                                   name="email"
                                   value="<?= $admin->email; ?>"
                                   class="form-control form-control-lg"
                                   readonly>
                        </div>

                        <div class="form-group">
                            <label>Foto Profile</label>
                            <div class="custom-file">
                                <input type="file"
                                       class="custom-file-input"
                                       name="avatar"
                                       id="customFile"
                                       accept=".jpg,.png,.jpeg"
                                       onchange="previewImg()">

                                <input type="hidden"
                                       name="gambar_lama"
                                       value="<?= $admin->avatar ?>">

                                <label class="custom-file-label" for="customFile">
                                    Pilih file...
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary px-4 mt-3">
                            <i class="fa fa-save mr-1"></i> Simpan Perubahan
                        </button>

                    </form>
                </div>
            </div>

            <!-- Update Password -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold mb-4">
                        Ubah Password
                    </h5>

                    <form action="<?= base_url('sw-admin/profile/update-password'); ?>" method="post">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

                        <div class="form-group">
                            <label>Password Lama</label>
                            <input type="password"
                                   name="current_password"
                                   class="form-control form-control-lg"
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Password Baru</label>
                            <input type="password"
                                   name="password"
                                   class="form-control form-control-lg"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa fa-key mr-1"></i> Update Password
                        </button>

                    </form>
                </div>
            </div>

        </div>

    </div>
</div>
<style>
.profile-card {
    border-radius: 15px;
}

.profile-avatar {
    width: 130px;
    height: 130px;
    object-fit: cover;
    border: 4px solid #f1f1f1;
}

.card {
    border-radius: 15px;
}

.card-title {
    font-size: 18px;
}

.form-control-lg {
    border-radius: 8px;
}

.btn-primary {
    border-radius: 8px;
    font-weight: 500;
}
</style>

<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script>
function previewImg() {
    const input = document.getElementById('customFile');
    const label = document.querySelector('.custom-file-label');
    const preview = document.querySelector('.profile-avatar');

    if (input.files.length > 0) {
        label.textContent = input.files[0].name;

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?= $this->endSection(); ?>
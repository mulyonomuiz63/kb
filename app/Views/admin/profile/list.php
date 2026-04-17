<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="row g-5 g-xl-10">

                <div class="col-xl-4 mb-xl-10">
                    <div class="card shadow-sm border-0 mb-5 mb-xl-8">
                        <div class="card-body pt-15 px-0 text-center">

                            <div class="symbol symbol-150px symbol-circle mb-7 position-relative">
                                <?= img_lazy('assets/app-assets/user/' . session()->get('avatar'), "Avatar", ['class' => 'profile-avatar object-fit-cover shadow-sm border border-3 border-body']) ?>
                                <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>
                            </div>

                            <h3 class="fs-2 text-gray-800 fw-bold mb-1"><?= esc($admin->nama_admin); ?></h3>
                            <div class="fs-5 fw-semibold text-muted mb-6">Administrator</div>

                            <div class="d-flex flex-center flex-wrap">
                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 mx-2 mb-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="ki-duotone ki-sms fs-3 text-primary me-2"><span class="path1"></span><span class="path2"></span></i>
                                        <div class="fs-6 fw-bold text-gray-700"><?= esc($admin->email); ?></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-8">

                    <div class="card shadow-sm border-0 mb-5 mb-xl-10">
                        <div class="card-header border-0">
                            <div class="card-title m-0">
                                <h3 class="fw-bold m-0">Informasi Dasar</h3>
                            </div>
                        </div>

                        <form action="<?= base_url('sw-admin/profile/update-profile'); ?>" method="post" enctype="multipart/form-data" class="form">
                            <?= csrf_field() ?>

                            <div class="card-body border-top p-9">

                                <div class="row mb-8">
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Foto Profil</label>
                                    <div class="col-lg-8">
                                        <div class="d-flex align-items-center gap-4">
                                            <input type="file" class="form-control form-control-solid w-auto" name="avatar" id="customFile" accept=".jpg,.png,.jpeg" onchange="previewImg()">
                                            <input type="hidden" name="gambar_lama" value="<?= esc($admin->avatar) ?>">
                                            <div class="form-text mt-0">Format: png, jpg, jpeg.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-8">
                                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Nama Lengkap</label>
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" name="nama_admin" value="<?= esc($admin->nama_admin); ?>" class="form-control form-control-lg form-control-solid" required>
                                    </div>
                                </div>

                                <div class="row mb-8">
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Alamat Email</label>
                                    <div class="col-lg-8 fv-row">
                                        <input type="email" name="email" value="<?= esc($admin->email); ?>" class="form-control form-control-lg form-control-solid bg-light" readonly>
                                        <div class="form-text">Email administrator tidak dapat diubah dari panel ini.</div>
                                    </div>
                                </div>

                            </div>

                            <div class="card-footer d-flex justify-content-end py-6 px-9">
                                <button type="submit" class="btn btn-primary fw-bold">
                                    <i class="ki-duotone ki-save-2 fs-2 me-1"><span class="path1"></span><span class="path2"></span></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="card shadow-sm border-0 mb-5 mb-xl-10">
                        <div class="card-header border-0">
                            <div class="card-title m-0">
                                <h3 class="fw-bold m-0">Ganti Password</h3>
                            </div>
                        </div>

                        <form action="<?= base_url('sw-admin/profile/update-password'); ?>" method="post" class="form">
                            <?= csrf_field() ?>

                            <div class="card-body border-top p-9">

                                <div class="row mb-8">
                                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Password Lama</label>
                                    <div class="col-lg-8 fv-row">
                                        <input type="password" name="current_password" class="form-control form-control-lg form-control-solid" placeholder="Masukkan password saat ini" required>
                                    </div>
                                </div>

                                <div class="row mb-8">
                                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Password Baru</label>
                                    <div class="col-lg-8 fv-row">
                                        <input type="password" name="password" class="form-control form-control-lg form-control-solid" placeholder="Masukkan password baru" required>
                                    </div>
                                </div>

                            </div>

                            <div class="card-footer d-flex justify-content-end py-6 px-9">
                                <button type="submit" class="btn btn-dark fw-bold">
                                    <i class="ki-duotone ki-key fs-2 me-1"><span class="path1"></span><span class="path2"></span></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    // Fungsi asli bawaan Anda (Sedikit disesuaikan variabel DOM-nya karena custom-file-label sudah dihapus)
    function previewImg() {
        const input = document.getElementById('customFile');
        const preview = document.querySelector('.profile-avatar');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?= $this->endSection(); ?>
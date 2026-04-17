<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="row g-5 g-xl-10">
            
            <div class="col-xl-4">
                <div class="card card-flush mb-5 mb-xl-8 shadow-sm">
                    <div class="card-body pt-15">
                        <div class="d-flex flex-center flex-column mb-5">
                            <div class="symbol symbol-100px symbol-circle mb-7 shadow">
                                <img src="<?= base_url('assets/Mitra-assets/user/') . '/' . $mitra->avatar; ?>" class="img-user" alt="avatar" style="object-fit: cover;" />
                            </div>
                            <span class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1"><?= $mitra->nama_mitra; ?></span>
                            <div class="fs-5 fw-semibold text-muted mb-6">Mitra Terdaftar</div>
                        </div>

                        <div class="d-flex flex-stack fs-4 py-3">
                            <div class="fw-bold">Detail Informasi</div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>
                        
                        <div class="pb-5 fs-6">
                            <div class="fw-bold mt-5 text-gray-800">Email</div>
                            <div class="text-gray-600">
                                <a href="mailto:<?= $mitra->email; ?>" class="text-gray-600 text-hover-primary"><?= $mitra->email; ?></a>
                            </div>

                            <div class="fw-bold mt-5 text-gray-800">Bergabung Pada</div>
                            <div class="text-gray-600"><?= date('d M Y', $mitra->date_created); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card card-flush mb-5 mb-xl-10 shadow-sm">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <h3 class="fw-bold">Ubah Profil</h3>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <form action="<?= base_url('sw-mitra/profile/update-profile'); ?>" method="post" enctype="multipart/form-data" class="form">
                            <?= csrf_field() ?>
                            <div class="row g-9 mb-7">
                                <div class="col-md-6 fv-row">
                                    <label class="required fs-6 fw-semibold mb-2">Nama Lengkap</label>
                                    <input type="text" name="nama_mitra" class="form-control form-control-solid" value="<?= $mitra->nama_mitra; ?>" required />
                                </div>
                                <div class="col-md-6 fv-row">
                                    <label class="fs-6 fw-semibold mb-2">Email</label>
                                    <input type="email" class="form-control form-control-solid bg-light" value="<?= $mitra->email; ?>" readonly />
                                </div>
                            </div>

                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">Foto Profil</label>
                                <div class="col-lg-12">
                                    <input type="file" name="avatar" id="customFile" class="form-control form-control-solid" accept=".jpg, .png, .jpeg" onchange="previewImg()" />
                                    <input type="hidden" name="gambar_lama" value="<?= $mitra->avatar; ?>">
                                    <div class="form-text text-muted">Ketentuan: 1:1 ratio, max 2MB (JPG, PNG, JPEG).</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary px-10">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card card-flush shadow-sm">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <h3 class="fw-bold">Ubah Kata Sandi</h3>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <form action="<?= base_url('sw-mitra/profile/update-password'); ?>" method="post" class="form">
                            <?= csrf_field() ?>
                            <div class="row g-9 mb-7">
                                <div class="col-md-6 fv-row">
                                    <label class="required fs-6 fw-semibold mb-2">Kata Sandi Saat Ini</label>
                                    <input type="password" name="current_password" class="form-control form-control-solid" placeholder="••••••••" required />
                                </div>
                                <div class="col-md-6 fv-row">
                                    <label class="required fs-6 fw-semibold mb-2">Kata Sandi Baru</label>
                                    <input type="password" name="password" id="pass" class="form-control form-control-solid" placeholder="••••••••" required />
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary px-10">Simpan Password</button>
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
    // Preview Image Logic (Sesuai fungsi asli Anda)
    function previewImg() {
        const gambar = document.querySelector('#customFile');
        const imgPreview = document.querySelector('.img-user');

        if (gambar.files && gambar.files[0]) {
            const filegambar = new FileReader();
            filegambar.readAsDataURL(gambar.files[0]);

            filegambar.onload = function(e) {
                imgPreview.src = e.target.result;
            }
        }
    }
</script>
<?= $this->endSection(); ?>
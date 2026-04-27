<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        
        <div class="d-flex flex-column flex-lg-row">
            <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
                <div class="card mb-5 mb-xl-8 shadow-sm">
                    <div class="card-body pt-15">
                        <div class="d-flex flex-center flex-column mb-5">
                            <div class="symbol symbol-100px symbol-circle mb-7 shadow">
                                <img src="<?= base_url('assets/app-assets/user/') . '/' . $pic->avatar; ?>" alt="image" style="object-fit: cover;" />
                            </div>
                            
                            <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1"><?= $pic->nama_pic; ?></a>
                            <div class="fs-5 fw-semibold text-muted mb-6">Professional Systems Architect</div>
                            
                            <div class="d-flex flex-wrap flex-center">
                                <span class="badge badge-light-success fw-bold px-4 py-3">AKTIF</span>
                            </div>
                        </div>

                        <div class="d-flex flex-stack fs-4 py-3">
                            <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_details" role="button" aria-expanded="false" aria-controls="kt_user_view_details">
                                Detail Informasi
                                <span class="ms-2 rotate-180">
                                    <i class="ki-duotone ki-down fs-3"></i>
                                </span>
                            </div>
                        </div>
                        
                        <div class="separator separator-dashed my-3"></div>
                        
                        <div id="kt_user_view_details" class="collapse show">
                            <div class="pb-5 fs-6">
                                <div class="fw-bold mt-5 text-gray-800">Email</div>
                                <div class="text-gray-600">
                                    <a href="mailto:<?= $pic->email; ?>" class="text-gray-600 text-hover-primary"><?= $pic->email; ?></a>
                                </div>

                                <div class="fw-bold mt-5 text-gray-800">Bergabung Sejak</div>
                                <div class="text-gray-600"><?= date('d F Y', $pic->date_created); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex-lg-row-fluid ms-lg-15">
                <div class="card pt-4 mb-6 mb-xl-9 shadow-sm">
                    <div class="card-header border-0">
                        <div class="card-title">
                            <h2 class="fw-bold">Pengaturan Akun</h2>
                        </div>
                    </div>
                    
                    <div class="card-body pt-0 pb-5">
                        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold mb-8">
                            <li class="nav-item">
                                <a class="nav-link text-active-primary py-4 active" data-bs-toggle="tab" href="#nav-profile">Informasi Dasar</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary py-4" data-bs-toggle="tab" href="#nav-password">Keamanan</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="nav-profile" role="tabpanel">
                                <form action="<?= base_url('sw-pic/profile/update'); ?>" method="post" enctype="multipart/form-data">
                                    <?= csrf_field(); ?>
                                    
                                    <div class="row mb-7">
                                        <label class="col-lg-4 fw-semibold text-muted">Nama Lengkap</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="nama_pic" class="form-control form-control-lg form-control-solid" value="<?= $pic->nama_pic; ?>" required />
                                        </div>
                                    </div>

                                    <div class="row mb-7">
                                        <label class="col-lg-4 fw-semibold text-muted">Alamat Email</label>
                                        <div class="col-lg-8">
                                            <input type="email" class="form-control form-control-lg form-control-solid bg-light" value="<?= $pic->email; ?>" readonly />
                                            <div class="form-text text-muted fs-7">Email digunakan sebagai identitas login dan tidak dapat diubah.</div>
                                        </div>
                                    </div>

                                    <div class="row mb-10">
                                        <label class="col-lg-4 fw-semibold text-muted">Foto Profil</label>
                                        <div class="col-lg-8">
                                            <input type="file" name="avatar" class="form-control form-control-solid" accept=".jpg, .png, .jpeg" />
                                            <input type="hidden" name="gambar_lama" value="<?= $pic->avatar; ?>">
                                            <div class="form-text text-muted fs-7">Format yang didukung: png, jpg, jpeg.</div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary px-10">
                                            <span class="indicator-label">Simpan Perubahan</span>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="nav-password" role="tabpanel">
                                <form action="<?= base_url('sw-pic/profile/edit-password'); ?>" method="post">
                                    <?= csrf_field(); ?>
                                    
                                    <div class="row mb-10">
                                        <label class="col-lg-4 fw-semibold text-muted">Kata Sandi Baru</label>
                                        <div class="col-lg-8">
                                            <input type="password" name="password" class="form-control form-control-lg form-control-solid" placeholder="Masukkan minimal 6 karakter" required />
                                            <div class="form-text text-muted fs-7">Pastikan kata sandi sulit ditebak untuk keamanan akun Anda.</div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-dark px-10">
                                            <span class="indicator-label">Perbarui Kata Sandi</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    // Preview image logic jika Anda memilikinya dapat diletakkan di sini
</script>
<?= $this->endSection(); ?>
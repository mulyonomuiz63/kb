<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>

<style>
    /* Custom Styling untuk tampilan lebih profesional */
    .profile-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        background: #fff;
    }
    .user-info .img-user {
        border: 4px solid #f8f9fa;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        object-fit: cover;
        border-radius: 50%;
    }
    .widget-title {
        font-weight: 700;
        color: #3b3f5c;
        font-size: 1.2rem;
        border-bottom: 2px solid #f1f2f3;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    .contacts-block__item {
        color: #515365;
        margin-bottom: 15px;
    }
    .contacts-block__item svg {
        color: #4361ee;
        margin-bottom: 5px;
    }
    .form-control {
        border-radius: 8px;
        padding: 10px 15px;
        border: 1px solid #bfc9d4;
    }
    .form-control:focus {
        border-color: #4361ee;
        box-shadow: 0 0 5px rgba(67, 97, 238, 0.2);
    }
    .btn-save {
        background-color: #4361ee;
        border-color: #4361ee;
        padding: 10px 30px;
        border-radius: 8px;
        font-weight: 600;
    }
</style>

<div class="layout-px-spacing">
    <div class="row layout-spacing">
        
        <div class="col-xl-4 col-lg-5 col-md-12 layout-top-spacing">
            <div class="profile-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="widget-title mb-0">Profil</h3>
                    <div class="icon-container">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4361ee" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                </div>
                
                <div class="text-center user-info">
                    <img src="<?= base_url('assets/Mitra-assets/user/') . '/' . $mitra->avatar; ?>" class="img-user mb-3" alt="avatar" style="width: 140px; height: 140px;">
                    <h5 class="font-weight-bold mb-1"><?= $mitra->nama_mitra; ?></h5>
                    <p class="text-muted small">Mitra Terdaftar</p>
                </div>

                <hr>

                <div class="user-info-list">
                    <ul class="contacts-block list-unstyled text-center">
                        <li class="contacts-block__item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            <div class="small font-weight-bold">Bergabung</div>
                            <span><?= date('d M Y', $mitra->date_created); ?></span>
                        </li>
                        <li class="contacts-block__item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            <div class="small font-weight-bold">Email</div>
                            <span><?= $mitra->email; ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7 col-md-12 layout-top-spacing">
            
            <div class="profile-card p-4 mb-4">
                <h3 class="widget-title">Ubah Profil</h3>
                <form action="<?= base_url('sw-mitra/profile/update-profile'); ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Nama Lengkap</label>
                                <input type="text" name="nama_mitra" id="nama_mitra" value="<?= $mitra->nama_mitra; ?>" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Email</label>
                                <input type="email" name="email" id="email" value="<?= $mitra->email; ?>" class="form-control" style="background-color: #f9f9f9;" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Foto Profil</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="avatar" id="customFile" accept=".jpg, .png, .jpeg" onchange="previewImg()">
                            <input type="hidden" name="gambar_lama" value="<?= $mitra->avatar; ?>">
                            <label class="custom-file-label" for="customFile">Choose new image...</label>
                        </div>
                        <small class="text-muted mt-3 d-block">Ketentuan: 1:1 ratio, max 2MB (JPG, PNG, JPEG).</small>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary btn-save">Simpan</button>
                    </div>
                </form>
            </div>

            <div class="profile-card p-4">
                <h3 class="widget-title">Ubah Kata Sandi</h3>
                <form action="<?= base_url('sw-mitra/profile/update-password'); ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Kata Sandi Saat Ini</label>
                                <input type="password" name="current_password" class="form-control" placeholder="••••••••" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Kata Sandi Baru</label>
                                <input type="password" name="password" id="pass" class="form-control" placeholder="••••••••" required>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary btn-save">Simpan</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    // Preview Image Logic
    function previewImg() {
        const gambar = document.querySelector('#customFile');
        const gambarLabel = document.querySelector('.custom-file-label');
        const imgPreview = document.querySelector('.img-user');

        if (gambar.files && gambar.files[0]) {
            gambarLabel.textContent = gambar.files[0].name;
            const filegambar = new FileReader();
            filegambar.readAsDataURL(gambar.files[0]);

            filegambar.onload = function(e) {
                imgPreview.src = e.target.result;
            }
        }
    }
</script>
<?= $this->endSection(); ?>
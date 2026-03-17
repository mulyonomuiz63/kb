<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        
        <div class="col-xl-4 col-lg-5 col-md-12 col-sm-12 layout-spacing">
            <div class="user-profile">
                <div class="widget-content widget-content-area shadow-sm border-0 br-6">
                    <div class="d-flex justify-content-between px-2 pt-2">
                        <h4 class="font-weight-bold">Profil Pengajar</h4>
                        <span class="badge badge-primary my-auto">Aktif</span>
                    </div>
                    
                    <div class="text-center user-info mt-4">
                        <div class="avatar-container position-relative d-inline-block">
                            <img src="<?= base_url('assets/app-assets/user/') . '/' . $guru->avatar; ?>" 
                                 class="img-user rounded-circle shadow-sm border" 
                                 alt="avatar" 
                                 style="width: 130px; height: 130px; object-fit: cover; border: 4px solid #fff !important;">
                        </div>
                        <h5 class="mt-3 mb-0 font-weight-bold text-dark"><?= $guru->nama_guru; ?></h5>
                        <p class="text-muted small">Professional Systems Architect</p>
                    </div>

                    <div class="user-info-list mt-4 px-3">
                        <div class="">
                            <ul class="contacts-block list-unstyled">
                                <li class="contacts-block__item d-flex align-items-center mb-3">
                                    <div class="icon-wrapper bg-light-primary rounded p-2 mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail text-primary"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Email Address</small>
                                        <span class="text-dark font-weight-600"><?= $guru->email; ?></span>
                                    </div>
                                </li>
                                <li class="contacts-block__item d-flex align-items-center mb-3">
                                    <div class="icon-wrapper bg-light-success rounded p-2 mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar text-success"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Bergabung Sejak</small>
                                        <span class="text-dark font-weight-600"><?= date('d F Y', $guru->date_created); ?></span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7 col-md-12 col-sm-12 layout-spacing">
            
            <div class="widget-content widget-content-area shadow-sm border-0 br-6">
                <nav>
                    <div class="nav nav-tabs mb-4" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab">Informasi Dasar</a>
                        <a class="nav-item nav-link" id="nav-password-tab" data-toggle="tab" href="#nav-password" role="tab">Keamanan</a>
                    </div>
                </nav>

                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-profile" role="tabpanel">
                        <form action="<?= base_url('sw-guru/profile/update'); ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field(); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Nama Lengkap</label>
                                        <input type="text" name="nama_guru" value="<?= $guru->nama_guru; ?>" class="form-control bg-light" placeholder="Nama Lengkap" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Alamat Email</label>
                                        <input type="email" value="<?= $guru->email; ?>" class="form-control" style="background-color: #f1f2f3;" readonly>
                                        <small class="form-text text-muted">Email tidak dapat diubah.</small>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Perbarui Foto Profil</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="avatar" id="customFile" accept=".jpg, .png, .jpeg" onchange="previewImg()">
                                            <label class="custom-file-label" for="customFile">Pilih file baru...</label>
                                        </div>
                                        <input type="hidden" name="gambar_lama" value="<?= $guru->avatar; ?>">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg mt-4 px-5">Simpan Perubahan</button>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="nav-password" role="tabpanel">
                        <form action="<?= base_url('sw-guru/profile/edit-password'); ?>" method="post">
                            <?= csrf_field(); ?>
                            <div class="form-group col-md-8 px-0">
                                <label class="font-weight-bold">Kata Sandi Baru</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 6 karakter" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-dark btn-lg mt-3 px-5">Perbarui Kata Sandi</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Custom Styling untuk meningkatkan profesionalisme */
    .bg-light-primary { background-color: rgba(67, 97, 238, 0.1); }
    .bg-light-success { background-color: rgba(28, 213, 155, 0.1); }
    .font-weight-600 { font-weight: 600; }
    .br-6 { border-radius: 8px; }
    .nav-tabs .nav-link.active { 
        border: none; 
        border-bottom: 2px solid #4361ee; 
        color: #4361ee; 
        font-weight: bold; 
    }
    .form-control:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.15);
    }
    .img-user {
        transition: transform .3s ease;
    }
    .img-user:hover {
        transform: scale(1.05);
    }
</style>

<?= $this->endSection(); ?>
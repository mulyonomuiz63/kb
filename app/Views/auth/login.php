<?= $this->extend('auth/pages/layout'); ?>
<?= $this->section('content'); ?>
<div class="form-container">
    <div class="form-content">
        <div class="user-info">
            <a href="<?= base_url('/'); ?>"><img src="<?= base_url('assets-landing/images/logo.png') ?>" style="width: 250px;" /></a>
            <?php if ($admin == null) : ?>
                <p class="signup-link">Admin belum ada. <a href="<?= base_url('auth/install'); ?>">Buat akun Admin dulu</a></p>
            <?php endif; ?>

            <form action="<?= base_url('auth/login-proses'); ?>" method="POST" class="text-left" id="form">
                <?= csrf_field() ?>
                <div class="form">
                    <div id="username-field" class="field-wrapper input">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <input type="email" id="username" name="email" type="text" class="form-control" value="<?= old('email'); ?>" placeholder="Email" required>
                    </div>

                    <div id="password-field" class="field-wrapper input mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <input type="password" id="password" name="password" type="password" class="form-control" placeholder="Kata Sandi" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div class="field-wrapper toggle-pass">
                            <p class="d-inline-block">Lihat Kata Sandi</p>
                            <label class="switch s-primary">
                                <input type="checkbox" id="toggle-password" class="d-none">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <input type="hidden" name="recaptcha_token" id="recaptcha_token">
                        <div class="field-wrapper">
                            <button type="button" class="btn btn-primary" onclick="submitForm()">Masuk</button>
                        </div>
                    </div>
                </div>
            </form>
            <div id="username-field" class="field-wrapper input my-4">
                <a href="<?= $link ?>" class="btn  btn-sm form-control"><img src="<?= base_url('assets-landing/images/icon/google.png') ?>" style="width: 30px;" /> <span style="font-size:14px;">Masuk dengan <b>Google</b></span></a>
            </div>
            <p class="signup-link">
                Lupa Kata Sandi? <a href="<?= base_url('auth/recovery') ?>">Klik Disini</a><br>
                Belum punya akun? <a href="<?= base_url('auth/registrasi') ?>">Registrasi disini</a>
            </p>
            <p class="terms-conditions"><?= copyright(); ?></p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
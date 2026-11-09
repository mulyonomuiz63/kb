<?= $this->extend('auth/pages/layout'); ?>
<?= $this->section('content'); ?>
<?= $this->include('auth/pages/kb-auth-style'); ?>

<div class="kb-login">
    <?= $this->include('auth/pages/kb-panel'); ?>

    <main class="kb-stage">
        <div class="kb-card">
            <a class="kb-logo" href="<?= base_url('/'); ?>">
                <img src="<?= base_url('uploads/app-icon/' . setting('logo_perusahaan')); ?>" alt="<?= setting('app_name'); ?>">
            </a>
            <h2>Pulihkan kata sandi</h2>
            <p class="kb-sub">Masukkan email akun Anda. Tautan pemulihan akan dikirim ke email tersebut.</p>

            <form action="<?= base_url('auth/recovery_'); ?>" method="post" id="form">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="recaptcha_token" id="recaptcha_token">

                <div class="kb-field">
                    <label for="email">Email</label>
                    <div class="kb-input">
                        <svg class="kb-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <input id="email" name="email" type="email" value="" placeholder="nama@email.com" required>
                    </div>
                </div>

                <button type="button" class="kb-submit" onclick="submitForm('lupapassword')">Kirim</button>
            </form>

            <p class="kb-signup"><a href="<?= base_url('auth') ?>">Kembali ke masuk</a></p>
            <p class="kb-copy"><?= copyright(); ?></p>
        </div>
    </main>
</div>
<?= $this->endSection() ?>

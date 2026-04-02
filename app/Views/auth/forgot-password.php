<?= $this->extend('auth/pages/layout'); ?>
<?= $this->section('content'); ?>
<div class="form-container">
    <div class="form-content">

        <h1 class="">Pulihkan Kata Sandi</h1>
        <p class="signup-link">Lupa Sandi? masukin email kamu aja dibawah ini</p>
        <form action="<?= base_url('auth/recovery_'); ?>" method="post" class="text-left" id="form">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="form">
                <div id="email-field" class="field-wrapper input">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-at-sign">
                        <circle cx="12" cy="12" r="4"></circle>
                        <path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path>
                    </svg>
                    <input id="email" name="email" type="email" value="" placeholder="Email" required>
                    <input type="hidden" name="recaptcha_token" id="recaptcha_token">
                </div>
                <div class="d-sm-flex justify-content-between">
                    <div class="field-wrapper">
                        <button type="button" class="btn btn-primary" onclick="submitForm('lupapassword')">Kirim</button>
                    </div>
                    <p class="signup-link">
                        <a href="<?= base_url('auth') ?>">Kembali</a>
                    </p>
                </div>
            </div>
        </form>
        <p class="terms-conditions"><?= copyright(); ?></p>

    </div>
</div>
<?= $this->endSection() ?>
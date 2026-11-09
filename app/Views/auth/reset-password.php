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
            <h2>Kata sandi baru</h2>
            <p class="kb-sub">Silakan masukkan sandi baru untuk akun Anda.</p>

            <form action="<?= base_url('auth/change-password_'); ?>" method="post">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="email" value="<?= $email; ?>">
                <input type="hidden" name="token" value="<?= $token; ?>">
                <input type="checkbox" id="toggle-password" class="d-none" hidden>

                <div class="kb-field">
                    <label for="password">Kata sandi</label>
                    <div class="kb-input">
                        <svg class="kb-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <input id="password" name="password" type="password" placeholder="Masukkan kata sandi baru" required>
                        <button type="button" class="kb-eye" id="kb-eye" aria-label="Lihat kata sandi">
                            <svg id="kb-eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            <svg id="kb-eye-off" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"></path><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="kb-submit">Ubah kata sandi</button>
            </form>

            <p class="kb-signup"><a href="<?= base_url('auth') ?>">Kembali ke masuk</a></p>
            <p class="kb-copy"><?= copyright(); ?></p>
        </div>
    </main>
</div>
<script>
    (function () {
        var eye = document.getElementById('kb-eye');
        var password = document.getElementById('password');
        var toggle = document.getElementById('toggle-password');
        var openIcon = document.getElementById('kb-eye-open');
        var offIcon = document.getElementById('kb-eye-off');
        if (!eye || !password) return;
        eye.addEventListener('click', function () {
            var show = password.type === 'password';
            password.type = show ? 'text' : 'password';
            openIcon.style.display = show ? 'none' : '';
            offIcon.style.display = show ? '' : 'none';
            eye.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Lihat kata sandi');
            if (toggle) toggle.checked = show;
        });
    })();
</script>
<?= $this->endSection() ?>

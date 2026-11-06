<?= $this->extend('auth/pages/layout'); ?>
<?= $this->section('content'); ?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body.form {
        margin: 0;
        min-height: 100vh;
        background: #eef2f8 !important;
        font-family: "Plus Jakarta Sans", Quicksand, sans-serif !important;
    }

    body.form > .form-container {
        display: block !important;
        min-height: 100vh;
        background: transparent !important;
    }

    body.form .form-form,
    body.form .form-form-wrap,
    body.form .form-form .form-container {
        width: 100% !important;
        max-width: none !important;
        min-height: 100vh !important;
        height: auto !important;
        margin: 0 !important;
        padding: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
        border: 0 !important;
        display: block !important;
        position: relative !important;
    }

    body.form .form-image {
        display: none !important;
    }

    .kb-login {
        min-height: 100vh;
        display: grid;
        grid-template-columns: minmax(320px, 1.05fr) minmax(360px, 0.95fr);
    }

    .kb-panel {
        position: relative;
        overflow: hidden;
        color: #fff;
        background:
            radial-gradient(circle at 15% 20%, rgba(255, 255, 255, 0.16), transparent 28%),
            radial-gradient(circle at 85% 80%, rgba(120, 168, 255, 0.35), transparent 32%),
            linear-gradient(155deg, #162754 0%, #29459a 52%, #3b67d6 100%);
        padding: 56px 64px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .kb-panel::before,
    .kb-panel::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, 0.14);
        pointer-events: none;
    }

    .kb-panel::before {
        width: 420px;
        height: 420px;
        right: -120px;
        top: -90px;
    }

    .kb-panel::after {
        width: 280px;
        height: 280px;
        left: -80px;
        bottom: -70px;
    }

    .kb-panel-top,
    .kb-panel-copy,
    .kb-points {
        position: relative;
        z-index: 1;
    }

    .kb-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.18);
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .kb-panel h1 {
        margin: 22px 0 14px;
        max-width: 460px;
        font-size: 42px;
        line-height: 1.15;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: #fff;
    }

    .kb-panel p.lead {
        max-width: 420px;
        margin: 0;
        color: rgba(255, 255, 255, 0.82);
        font-size: 16px;
        line-height: 1.7;
    }

    .kb-points {
        display: grid;
        gap: 14px;
        margin-top: 36px;
    }

    .kb-point {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        padding: 14px 16px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(8px);
    }

    .kb-point svg {
        flex: 0 0 auto;
        margin-top: 2px;
    }

    .kb-point strong {
        display: block;
        font-size: 14px;
        font-weight: 700;
    }

    .kb-point span {
        display: block;
        margin-top: 2px;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.75);
    }

    .kb-panel-foot {
        position: relative;
        z-index: 1;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.7);
    }

    .kb-stage {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 28px;
        background:
            radial-gradient(circle at top right, rgba(41, 69, 154, 0.08), transparent 36%),
            #f6f8fc;
    }

    .kb-card {
        width: 100%;
        max-width: 440px;
        background: #fff;
        border: 1px solid #e6ebf4;
        border-radius: 28px;
        box-shadow: 0 24px 60px rgba(22, 39, 84, 0.08);
        padding: 36px 36px 28px;
    }

    .kb-logo {
        display: flex;
        justify-content: center;
        margin-bottom: 22px;
    }

    .kb-logo img {
        width: auto;
        max-width: 210px;
        max-height: 72px;
        object-fit: contain;
    }

    .kb-card h2 {
        margin: 0;
        text-align: center;
        color: #162033;
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .kb-card .kb-sub {
        margin: 8px 0 26px;
        text-align: center;
        color: #667085;
        font-size: 14.5px;
        line-height: 1.6;
    }

    .kb-field {
        margin-bottom: 16px;
    }

    .kb-field label {
        display: block;
        margin-bottom: 8px;
        color: #344054;
        font-size: 13px;
        font-weight: 700;
    }

    .kb-input {
        position: relative;
    }

    .kb-input svg.kb-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #98a2b3;
        pointer-events: none;
    }

    .kb-input input {
        width: 100%;
        height: 52px;
        border: 1px solid #e4e7ec !important;
        border-radius: 14px !important;
        background: #f8fafc !important;
        padding: 0 46px 0 46px !important;
        color: #101828 !important;
        font-size: 15px !important;
        font-family: inherit !important;
        box-shadow: none !important;
        transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
    }

    .kb-input input::placeholder {
        color: #98a2b3;
    }

    .kb-input input:focus {
        outline: none;
        background: #fff !important;
        border-color: #29459a !important;
        box-shadow: 0 0 0 4px rgba(41, 69, 154, 0.12) !important;
    }

    .kb-eye {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        width: 36px;
        height: 36px;
        border: 0;
        border-radius: 10px;
        background: transparent;
        color: #667085;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .kb-eye:hover {
        background: #eef2f8;
        color: #29459a;
    }

    .kb-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin: 4px 0 18px;
        font-size: 13.5px;
    }

    .kb-row a {
        color: #29459a;
        font-weight: 700;
        text-decoration: none;
    }

    .kb-row a:hover {
        text-decoration: underline;
    }

    .kb-submit {
        width: 100%;
        height: 52px;
        border: 0;
        border-radius: 14px;
        background: linear-gradient(180deg, #3558c4 0%, #29459a 100%);
        color: #fff;
        font-family: inherit;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 0.01em;
        cursor: pointer;
        box-shadow: 0 10px 20px rgba(41, 69, 154, 0.24);
        transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
    }

    .kb-submit:hover {
        filter: brightness(1.05);
        transform: translateY(-1px);
        box-shadow: 0 14px 24px rgba(41, 69, 154, 0.28);
    }

    .kb-submit:active {
        transform: translateY(0);
    }

    .kb-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 20px 0 16px;
        color: #98a2b3;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .kb-divider::before,
    .kb-divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #e4e7ec;
    }

    .kb-google {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        height: 50px;
        border-radius: 14px;
        border: 1px solid #e4e7ec;
        background: #fff;
        color: #344054;
        font-size: 14.5px;
        font-weight: 700;
        text-decoration: none !important;
        transition: background .15s ease, border-color .15s ease;
    }

    .kb-google:hover {
        background: #f8fafc;
        border-color: #d0d5dd;
        color: #101828;
    }

    .kb-google img {
        width: 20px;
        height: 20px;
    }

    .kb-signup {
        margin: 22px 0 0;
        text-align: center;
        color: #667085;
        font-size: 14px;
    }

    .kb-signup a {
        color: #29459a;
        font-weight: 700;
        text-decoration: none;
    }

    .kb-signup a:hover {
        text-decoration: underline;
    }

    .kb-copy {
        margin: 18px 0 0;
        text-align: center;
        color: #98a2b3;
        font-size: 12px;
        line-height: 1.6;
    }

    .kb-copy a {
        color: #667085;
        text-decoration: none;
        font-weight: 600;
    }

    @media (max-width: 980px) {
        .kb-login {
            grid-template-columns: 1fr;
        }

        .kb-panel {
            min-height: auto;
            padding: 32px 24px 28px;
        }

        .kb-panel h1 {
            font-size: 30px;
        }

        .kb-points,
        .kb-panel-foot {
            display: none;
        }

        .kb-stage {
            padding: 20px 16px 36px;
        }

        .kb-card {
            padding: 28px 20px 22px;
            border-radius: 22px;
        }
    }
</style>

<div class="kb-login">
    <aside class="kb-panel">
        <div class="kb-panel-copy">
            <div class="kb-panel-top">
                <span class="kb-kicker">Portal KelasBrevet</span>
            </div>
            <h1>Tingkatkan keahlian perpajakan Anda sekarang.</h1>
            <p class="lead">Kelola materi, ujian, dan progres belajar Anda dalam satu tempat yang rapi dan mudah diikuti.</p>
            <div class="kb-points">
                <div class="kb-point">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    <div>
                        <strong>Materi tersusun</strong>
                        <span>Akses pelajaran sesuai paket yang Anda ikuti.</span>
                    </div>
                </div>
                <div class="kb-point">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    <div>
                        <strong>Ujian</strong>
                        <span>Uji kompetensi perpajakan dengan hasil yang terukur.</span>
                    </div>
                </div>
                <div class="kb-point">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"></circle><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"></path></svg>
                    <div>
                        <strong>Sertifikat Brevet A&B</strong>
                        <span>Dokumen kelulusan Brevet A&B dan transkrip nilai.</span>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <main class="kb-stage">
        <div class="kb-card">
            <a class="kb-logo" href="<?= base_url('/'); ?>">
                <img src="<?= base_url('uploads/app-icon/' . setting('logo_perusahaan')); ?>" alt="<?= setting('app_name'); ?>">
            </a>
            <p class="kb-sub">Gunakan email dan kata sandi akun Anda untuk melanjutkan.</p>

            <form action="<?= base_url('auth/login-proses'); ?>" method="POST" id="form">
                <?= csrf_field() ?>
                <input type="hidden" name="recaptcha_token" id="recaptcha_token">
                <input type="checkbox" id="toggle-password" class="d-none" hidden>

                <div class="kb-field">
                    <label for="username">Email</label>
                    <div class="kb-input">
                        <svg class="kb-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <input type="email" id="username" name="email" value="<?= old('email'); ?>" placeholder="nama@email.com" autocomplete="email" required>
                    </div>
                </div>

                <div class="kb-field">
                    <label for="password">Kata sandi</label>
                    <div class="kb-input">
                        <svg class="kb-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" autocomplete="current-password" required>
                        <button type="button" class="kb-eye" id="kb-eye" aria-label="Lihat kata sandi">
                            <svg id="kb-eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            <svg id="kb-eye-off" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"></path><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                        </button>
                    </div>
                </div>

                <div class="kb-row">
                    <span></span>
                    <a href="<?= base_url('auth/recovery') ?>">Lupa kata sandi?</a>
                </div>

                <button type="button" class="kb-submit" onclick="submitForm('login')">Masuk</button>
            </form>

            <?php if (strtolower((string) setting('client_status')) == 'true'): ?>
                <div class="kb-divider">atau</div>
                <a href="<?= $link ?>" class="kb-google">
                    <img src="<?= base_url('assets-landing/images/icon/google.png') ?>" alt="">
                    <span>Masuk dengan Google</span>
                </a>
            <?php endif; ?>

            <p class="kb-signup">Belum punya akun? <a href="<?= base_url('auth/registrasi') ?>">Daftar di sini</a></p>
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

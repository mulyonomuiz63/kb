<?= $this->extend('auth/pages/layout'); ?>
<?= $this->section('content'); ?>
<?= $this->include('auth/pages/kb-auth-style'); ?>

<div class="kb-login">
    <?= $this->include('auth/pages/kb-panel'); ?>

    <main class="kb-stage">
        <div class="kb-card kb-card-wide">
            <a class="kb-logo" href="<?= base_url('/'); ?>">
                <img src="<?= base_url('uploads/app-icon/' . setting('logo_perusahaan')); ?>" alt="<?= setting('app_name'); ?>">
            </a>
            <h2>Buat akun</h2>
            <p class="kb-sub">Lengkapi data di bawah ini untuk mulai belajar.</p>

            <form action="<?= base_url('auth/store'); ?>" method="POST" id="form">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="recaptcha_token" id="recaptcha_token">
                <input type="hidden" id="id_kelas" name="kelas" value="1" required>
                <input type="checkbox" id="toggle-password" class="d-none" hidden>

                <div class="kb-field">
                    <label for="nama_siswa">Nama lengkap</label>
                    <div class="kb-input">
                        <svg class="kb-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <input type="text" id="nama_siswa" name="nama_siswa" value="<?= old('nama_siswa'); ?>" placeholder="Nama lengkap" required autocomplete="off">
                    </div>
                </div>

                <div class="kb-field">
                    <label for="email">Email</label>
                    <div class="kb-input">
                        <svg class="kb-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <input type="email" id="email" name="email" value="<?= old('email'); ?>" placeholder="nama@email.com" required autocomplete="off">
                    </div>
                </div>

                <div class="kb-field" id="hp-field">
                    <label for="hp">Nomor WhatsApp</label>
                    <div class="kb-wa">
                        <div class="kb-input">
                            <svg class="kb-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            <input type="number"
                                id="hp"
                                name="hp"
                                data-verified="0"
                                class="<?= session('errors.hp') ? 'is-invalid' : '' ?>"
                                value="<?= old('hp'); ?>"
                                placeholder="08xxxxxxxxxx"
                                required
                                maxlength="15"
                                autocomplete="off">
                        </div>
                        <button class="kb-submit kb-submit-side" type="button" id="btn-send-otp" style="display: none;" title="Kirim OTP Ke WhatsApp">Verifikasi</button>
                    </div>

                    <div id="otp-area" class="kb-otp" style="display: none;">
                        <label for="otp-input">Masukkan 6 Digit OTP</label>
                        <div class="kb-wa">
                            <div class="kb-input">
                                <input type="text" id="otp-input" class="kb-plain" placeholder="••••••" maxlength="6" autocomplete="off">
                            </div>
                            <button class="kb-submit kb-submit-side" type="button" id="btn-verify-otp">Cek Kode</button>
                        </div>
                        <div class="kb-note">
                            Kode OTP kadaluarsa dalam: <span id="otp-timer" class="kb-timer">05:00</span>
                        </div>
                    </div>

                    <div id="wa-verified-badge" class="kb-badge" style="display: none;">Nomor WA terverifikasi</div>
                </div>

                <div class="kb-field">
                    <label for="jenis_kelamin">Jenis kelamin</label>
                    <div class="kb-input">
                        <select name="jenis_kelamin" id="jenis_kelamin" required>
                            <option value="">Pilih jenis kelamin</option>
                            <option value="Laki - Laki" <?= old('jenis_kelamin') == 'Laki - Laki' ? 'selected' : ''; ?>>Laki - Laki</option>
                            <option value="Perempuan" <?= old('jenis_kelamin') == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="kb-field">
                    <label for="password">Kata sandi</label>
                    <div class="kb-input">
                        <svg class="kb-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <input type="password" id="password" name="password" value="" placeholder="Masukkan kata sandi" required>
                        <button type="button" class="kb-eye" id="kb-eye" aria-label="Lihat kata sandi">
                            <svg id="kb-eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            <svg id="kb-eye-off" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"></path><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                        </button>
                    </div>
                </div>

                <button type="button" class="kb-submit" id="btn-submit-reg">Registrasi</button>
            </form>

            <p class="kb-signup">Sudah punya akun? <a href="<?= base_url('auth') ?>">Masuk di sini</a></p>
            <p class="kb-copy"><?= copyright() ?></p>
        </div>
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>';

    $(document).ready(function() {
        var timerInterval;
        var lastVerifiedHp = '';

        function checkWaStatus() {
            var currentHp = $('#hp').val();
            var isVerified = $('#hp').data('verified');
            var btnReg = $('#btn-submit-reg');

            $('#btn-send-otp').hide();
            $('#wa-verified-badge').hide();
            btnReg.prop('disabled', false);

            if (currentHp === '') {
                btnReg.prop('disabled', true);
            } else if (currentHp !== lastVerifiedHp || isVerified == 0) {
                $('#btn-send-otp').show();
                btnReg.prop('disabled', true);
                btnReg.text('Verifikasi WA Dulu');
            } else {
                $('#wa-verified-badge').show();
                btnReg.prop('disabled', false);
                btnReg.text('Registrasi');
            }
        }

        checkWaStatus();

        $('#hp').on('input', function() {
            if (this.value.length > 15) this.value = this.value.slice(0, 15);
            $('#otp-area').slideUp();
            clearInterval(timerInterval);

            if ($(this).val() !== lastVerifiedHp) {
                $(this).data('verified', 0);
            }
            checkWaStatus();
        });

        $('#btn-submit-reg').click(function(e) {
            e.preventDefault();

            var btnReg = $(this);

            btnReg.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Sedang diproses...');

            if (typeof submitForm === 'function') {
                submitForm('registrasi');
            } else {
                $('#form').submit();
            }
        });

        $('#btn-send-otp').click(function(e) {
            e.preventDefault();
            var hp = $('#hp').val();

            if (hp.length < 9) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Format Salah', 'Pastikan nomor WhatsApp yang Anda masukkan valid!', 'warning');
                } else {
                    alert('Pastikan nomor WhatsApp valid!');
                }
                return;
            }

            var btn = $(this);
            btn.prop('disabled', true).text('Loading...');

            var requestData = { hp: hp };
            requestData[csrfName] = csrfHash;

            $.ajax({
                url: '<?= base_url("auth/send-otp") ?>',
                type: 'POST',
                data: requestData,
                dataType: 'json',
                success: function(res) {
                    csrfHash = res.csrfHash;
                    $('input[name="' + csrfName + '"]').val(csrfHash);

                    if (res.status === 'success') {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'OTP Terkirim!',
                                text: 'Silakan cek WhatsApp Anda.',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                        btn.hide().prop('disabled', false).text('Verifikasi');
                        $('#otp-area').slideDown();
                        $('#otp-input').val('').focus();
                        startOtpTimer(300);
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Gagal', res.message, 'error');
                        } else {
                            alert(res.message);
                        }
                        btn.prop('disabled', false).text('Coba Lagi');
                    }
                },
                error: function(xhr) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Oops!', 'Terjadi kesalahan sistem saat mengirim OTP.', 'error');
                    }
                    btn.prop('disabled', false).text('Coba Lagi');
                }
            });
        });

        $('#btn-verify-otp').click(function(e) {
            e.preventDefault();
            var otp = $('#otp-input').val();
            var hp = $('#hp').val();

            if (otp.length !== 6) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Perhatian', 'Kode OTP harus 6 digit angka!', 'warning');
                } else {
                    alert('Kode OTP harus 6 digit!');
                }
                return;
            }

            var btn = $(this);
            btn.prop('disabled', true).text('Cek...');

            var requestData = { hp: hp, otp: otp };
            requestData[csrfName] = csrfHash;

            $.ajax({
                url: '<?= base_url("auth/verify-otp") ?>',
                type: 'POST',
                data: requestData,
                dataType: 'json',
                success: function(res) {
                    csrfHash = res.csrfHash;
                    $('input[name="' + csrfName + '"]').val(csrfHash);

                    if (res.status === 'success') {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Berhasil!', 'Nomor WhatsApp berhasil diverifikasi.', 'success');
                        }

                        clearInterval(timerInterval);
                        $('#otp-area').slideUp();

                        lastVerifiedHp = hp;
                        $('#hp').data('verified', 1);
                        checkWaStatus();

                        btn.prop('disabled', false).text('Cek Kode');
                        $('#otp-input').val('');
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Gagal', res.message, 'error');
                        } else {
                            alert(res.message);
                        }
                        btn.prop('disabled', false).text('Cek Kode');
                    }
                },
                error: function(xhr) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Oops!', 'Terjadi kesalahan saat verifikasi.', 'error');
                    }
                    btn.prop('disabled', false).text('Cek Kode');
                }
            });
        });

        function startOtpTimer(duration) {
            clearInterval(timerInterval);
            var timer = duration, minutes, seconds;

            timerInterval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                $('#otp-timer').text(minutes + ":" + seconds);

                if (--timer < 0) {
                    clearInterval(timerInterval);
                    $('#otp-area').slideUp();
                    $('#btn-send-otp').show().text('Kirim Ulang OTP');
                }
            }, 1000);
        }
    });
</script>
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

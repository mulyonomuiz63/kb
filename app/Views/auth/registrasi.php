<?= $this->extend('auth/pages/layout'); ?>
<?= $this->section('content'); ?>
<div class="form-container">

    <div class="form-content">
        <a href="<?= base_url('/'); ?>"><img src="<?= base_url('assets-landing/images/logo.png') ?>" style="width: 250px;" /></a>
        <form action="<?= base_url('auth/store'); ?>" method="POST" class="text-left" id="form">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="form">
                <div id="nama_siswa-field" class="field-wrapper input">
                    <input type="text" id="nama_siswa" name="nama_siswa" value="<?= old('nama_siswa'); ?>" placeholder="Nama Lengkap" type="text" class="form-control" required autocomplete="off">
                </div>


                <div id="email-field" class="field-wrapper input">
                    <input type="email" id="email" name="email" value="<?= old('email'); ?>" placeholder="Email Aktif" class="form-control" required autocomplete="off">
                </div>

                <!-- BAGIAN WHATSAPP & VERIFIKASI OTP -->
                <div id="hp-field" class="field-wrapper input mb-3">
                    <!-- Ditambahkan class flex-nowrap agar elemen selalu berdampingan -->
                    <div class="input-group flex-nowrap">
                        <input type="number"
                            id="hp"
                            name="hp"
                            data-verified="0"
                            class="form-control <?= session('errors.hp') ? 'is-invalid' : '' ?>"
                            value="<?= old('hp'); ?>"
                            placeholder="Nomor/WA Aktif"
                            required
                            maxlength="15"
                            autocomplete="off">
                        <button class="btn btn-primary text-nowrap" type="button" id="btn-send-otp" style="display: none;" title="Kirim OTP Ke WhatsApp">
                            Verifikasi
                        </button>
                    </div>

                    <!-- Form OTP Dinamis -->
                    <div id="otp-area" class="mt-3 p-3 border border-primary border-dashed rounded bg-light-primary" style="display: none;">
                        <label class="form-label fw-bold text-primary fs-7 mb-1">Masukkan 6 Digit OTP</label>
                        <!-- Ditambahkan class flex-nowrap juga di area input OTP -->
                        <div class="input-group flex-nowrap mb-2">
                            <input type="text" id="otp-input" class="form-control form-control-solid text-center fw-bolder fs-4" placeholder="••••••" maxlength="6" autocomplete="off">
                            <button class="btn btn-success text-nowrap" type="button" id="btn-verify-otp">Cek Kode</button>
                        </div>
                        <div class="form-text text-muted fs-7">
                            Kode OTP kadaluarsa dalam: <span id="otp-timer" class="fw-bold text-danger">05:00</span>
                        </div>
                    </div>

                    <!-- Badge Terverifikasi -->
                    <div id="wa-verified-badge" class="mt-2" style="display: none;">
                        <span class="badge badge-light-success fs-7 fw-bold p-2 text-success"><i class="bi bi-check-circle-fill me-1"></i>Nomor WA Terverifikasi</span>
                    </div>
                </div>
                <!-- END WHATSAPP -->

                <div id="jenis_kelamin-field" class="field-wrapper input">
                    <select name="jenis_kelamin" required class="form-control" style=" display:block;
                        color: #999;
                        border: none;
                        border-bottom: 1px solid #e0e6ed">
                        <option value="">Pilih Jenis Kelamin </option>
                        <option value="Laki - Laki" <?= old('jenis_kelamin') == 'Laki - Laki' ? 'selected' : ''; ?>>Laki - Laki</option>
                        <option value="Perempuan" <?= old('jenis_kelamin') == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                </div>

                <!--<div id="kelas-field" class="field-wrapper input">-->
                <!--    <input type="text" id="data-kelas" class="form-control" value="" placeholder="Kelas">-->
                <input type="hidden" id="id_kelas" class="form-control" name="kelas" value="1" required>
                <!--    <div id="suggestion-box"></div>-->

                <!--</div>-->
                <div id="password-field" class="field-wrapper input mb-2">
                    <input type="password" id="password" name="password" type="password" value="" placeholder="Kata Sandi" required>
                </div>
                <input type="hidden" name="recaptcha_token" id="recaptcha_token">
                <div class="d-flex justify-content-between">

                    <div class="field-wrapper toggle-pass">

                        <p class="d-inline-block">Lihat kata sandi</p>

                        <label class="switch s-primary">

                            <input type="checkbox" id="toggle-password" class="d-none">

                            <span class="slider round"></span>

                        </label>

                    </div>

                    <div class="field-wrapper">

                        <button type="button" class="btn btn-primary" id="btn-submit-reg">Registrasi</button>

                    </div>

                </div>



            </div>

        </form>
        <p class="signup-link">

            Sudah punya akun? <a href="<?= base_url('auth') ?>">Masuk disini</a><br>

        </p>
        <p class="terms-conditions"><?= copyright() ?></p>



    </div>

</div>

<!-- SCRIPT PENDUKUNG VERIFIKASI OTP REGISTRASI -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>';

    $(document).ready(function() {
        var timerInterval;
        var lastVerifiedHp = ''; // Menyimpan nomor HP yang sukses diverifikasi

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
                // Jika nomor diubah atau belum diverifikasi
                $('#btn-send-otp').show();
                btnReg.prop('disabled', true);
                btnReg.text('Verifikasi WA Dulu');
            } else {
                // Jika nomor sama dan sudah terverifikasi
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
            
            // Jika user merubah nomor dari yang sudah terverifikasi sebelumnya, set verified jadi 0
            if ($(this).val() !== lastVerifiedHp) {
                $(this).data('verified', 0);
            }
            checkWaStatus();
        });

        // Event Klik Tombol Registrasi (Menampilkan Loading & Menjalankan Fungsi Asli Anda)
        $('#btn-submit-reg').click(function(e) {
            e.preventDefault();
            
            var btnReg = $(this);
            
            // Ubah tombol jadi status loading dan disable agar tidak double click
            btnReg.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Sedang diproses...');

            // Panggil fungsi submitForm bawaan Anda yang sudah ada di sistem
            if (typeof submitForm === 'function') {
                submitForm('registrasi');
            } else {
                // Fallback jika fungsi submitForm global tidak ada, submit form secara native
                $('#form').submit();
            }
        });

        // Kirim OTP
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

        // Verifikasi OTP
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

                        lastVerifiedHp = hp; // Kunci nomor yang sudah verified
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
<?= $this->endSection() ?>
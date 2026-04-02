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
                    <input type="email" id="email" name="email" value="<?= old('email'); ?>" placeholder="Email Aktif" type="text" class="form-control" required autocomplete="off">
                </div>

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

                        <button type="button" class="btn btn-primary" onclick="submitForm('registrasi')">Registrasi</button>

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
<?= $this->endSection() ?>
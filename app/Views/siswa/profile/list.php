<?= $this->extend('siswa/template/app'); ?>

<?= $this->section('styles'); ?>
<style>
    .sticky-announcement {
        position: -webkit-sticky;
        position: sticky;
        top: 70px;
        z-index: 1000;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        animation: slideDown 0.5s ease;
    }

    @keyframes slideDown {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .is-invalid-field,
    .is-invalid-field:focus {
        border: 2px solid #f1416c !important;
        background-color: #fff5f8 !important;
        transition: all 0.3s ease;
    }

    .btn.disabled,
    .btn:disabled {
        cursor: not-allowed;
        opacity: 0.75;
    }

    .select2-container--bootstrap5 .select2-selection {
        border: 2px solid #f1416c !important;
        background-color: #fff5f8 !important;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .select2-container--bootstrap5 .select2-selection--single {
        min-height: calc(1.5em + 1.65rem + 2px) !important;
        padding: 0.825rem 1.5rem !important;
    }

    .select2-container--bootstrap5 .select2-selection__rendered {
        line-height: 1.5 !important;
    }

    .shake-error {
        animation: shake 0.4s 1 linear;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        50% { transform: translateX(5px); }
        75% { transform: translateX(-5px); }
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<?php
$isStatusB = ($siswa->status == 'B');
$checkInvalid = function ($val) use ($isStatusB) {
    $isEmpty = empty(trim((string)$val)) || trim((string)$val) === '0000-00-00';
    return ($isStatusB && $isEmpty) ? 'is-invalid-field' : '';
};

// Ambil status verifikasi dari DB, pastikan nilai default 0 jika belum ada
$isWaVerified = isset($siswa->is_wa_verified) ? $siswa->is_wa_verified : 0; 
?>

<div class="d-flex flex-column flex-column-fluid py-3 py-lg-6 mt-8">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <?php if ($isStatusB || session()->getFlashdata('errors')) : ?>
                <div class="sticky-announcement alert alert-dismissible bg-danger d-flex flex-column flex-sm-row p-5 mb-10 rounded border-0 shadow-lg">
                    <div class="d-flex flex-column text-white pe-0 pe-sm-10">
                        <h4 class="mb-1 text-white fw-bold">Peringatan: Data Belum Lengkap!</h4>
                        <span>Beberapa kolom wajib (ditandai border merah) masih kosong. Harap lengkapi semua data agar Anda dapat mencetak sertifikat dan mengikuti ujian.</span>
                    </div>
                    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                        <i class="ki-outline ki-cross fs-1 text-white"></i>
                    </button>
                </div>
            <?php endif; ?>

            <div class="row g-7">
                <div class="col-12 col-xl-4">
                    <div class="card mb-5 mb-xl-8">
                        <div class="card-body pt-15">
                            <div class="d-flex flex-center flex-column mb-5">
                                <div class="symbol symbol-150px symbol-circle mb-7 position-relative">
                                    <?= img_lazy('assets/app-assets/user/' . $siswa->avatar, "loading", ['class' => 'img-user border border-3 border-secondary', 'id' => 'profile_avatar_preview']) ?>
                                    <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>
                                </div>
                                <h3 class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1"><?= $siswa->nama_siswa; ?></h3>
                                <div class="fs-6 fw-semibold text-muted mb-6">Peserta KelasBrevet</div>
                            </div>

                            <div class="d-flex flex-stack fs-4 py-3">
                                <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_details" role="button" aria-expanded="false" aria-controls="kt_user_view_details">
                                    Detail Akun
                                    <span class="ms-2 rotate-180">
                                        <i class="ki-outline ki-down fs-3"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="separator separator-dashed my-3"></div>
                            <div id="kt_user_view_details" class="collapse">
                                <div class="pb-5 fs-6">
                                    <div class="fw-bold mt-5">E-mail</div>
                                    <div class="text-gray-600"><?= $siswa->email; ?></div>
                                    <div class="fw-bold mt-5">WhatsApp</div>
                                    <div class="text-gray-600"><?= $siswa->hp; ?></div>
                                    <div class="fw-bold mt-5">NIP</div>
                                    <div class="text-gray-600"><?= $siswa->no_induk_siswa; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-5 mb-xl-8">
                        <div class="card-header border-0">
                            <div class="card-title">
                                <h3 class="fw-bold m-0 fs-4">Ganti Kata Sandi</h3>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <form action="<?= base_url('sw-siswa/profile/edit-password'); ?>" method="post">
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                <div class="fv-row mb-7">
                                    <label class="fs-7 fw-bold mb-2 text-uppercase">Kata Sandi Baru</label>
                                    <div class="position-relative">
                                        <input type="password" name="password" id="pass" class="form-control form-control-solid" placeholder="Masukan sandi baru" required />
                                        <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" id="mybutton" onclick="togglePassword()">
                                            <i class="bi bi-eye-slash-fill fs-3"></i>
                                        </span>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-light-primary fw-bold w-100">Simpan Sandi</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-8">
                    <div class="card mb-5 mb-xl-10">
                        <div class="card-header border-0 cursor-pointer" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details">
                            <div class="card-title m-0">
                                <h3 class="fw-bold m-0">Pengaturan Data Diri</h3>
                            </div>
                        </div>

                        <div id="kt_account_settings_profile_details" class="collapse show">
                            <form action="<?= base_url('sw-siswa/profile/update-data-diri'); ?>" method="post" enctype="multipart/form-data" class="form" id="formUpdateProfil" novalidate>
                                <?= csrf_field(); ?>
                                <input type="hidden" name="id_siswa" value="<?= decrypt_url($siswa->id_siswa); ?>">

                                <div class="card-body border-top p-9">
                                    <div class="row mb-8">
                                        <label class="col-12 col-lg-4 col-form-label fw-semibold fs-6 text-gray-700">Pas Foto</label>
                                        <div class="col-12 col-lg-8">
                                            <div class="d-flex flex-column gap-3">
                                                <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-4">
                                                    <div class="image-input image-input-outline shadow-sm" data-kt-image-input="true">
                                                        <div class="image-input-wrapper w-125px h-125px <?= $checkInvalid($siswa->avatar) ?>" id="preview_wrapper" style="background-image: url(<?= base_url('assets/app-assets/user/' . $siswa->avatar) ?>)"></div>
                                                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Ubah Foto">
                                                            <i class="bi bi-pencil-fill fs-7"></i>
                                                            <input type="file" name="avatar" id="customFile" accept=".png, .jpg, .jpeg" onchange="previewImg()" />
                                                            <input type="hidden" name="gambar_lama" value="<?= $siswa->avatar; ?>">
                                                        </label>
                                                    </div>
                                                    <div>
                                                        <div class="form-text text-muted mb-2">Format 3x4 (Background Merah)<br>Ukuran maksimal: 1MB.</div>
                                                        <div id="file-result" class="fw-bold text-danger"></div>
                                                    </div>
                                                </div>

                                                <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-4 mt-2">
                                                    <i class="ki-outline ki-information-5 fs-2tx text-warning me-3 align-self-center"></i>
                                                    <div class="d-flex flex-stack flex-grow-1">
                                                        <div class="fw-semibold">
                                                            <h4 class="text-gray-900 fw-bold fs-6 mb-1">Penting: Verifikasi Keaslian</h4>
                                                            <div class="fs-7 text-gray-700">
                                                                Sistem AI kami akan mencocokkan wajah pada foto profil ini dengan wajah Anda saat ujian. <b>Ketidaksesuaian dapat menangguhkan sertifikat.</b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-12 col-lg-4 col-form-label required fw-bold fs-6">Nama Lengkap</label>
                                        <div class="col-12 col-lg-8">
                                            <input type="text" name="nama_siswa" class="form-control form-control-lg form-control-solid <?= $checkInvalid(old('nama_siswa', $siswa->nama_siswa)) ?>" placeholder="Sesuai KTP/Sertifikat" value="<?= old('nama_siswa', $siswa->nama_siswa); ?>" required />
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-12 col-lg-4 col-form-label required fw-bold fs-6">NIK (16 Digit)</label>
                                        <div class="col-12 col-lg-8">
                                            <input type="number" name="nik" id="nik" class="form-control form-control-lg form-control-solid <?= $checkInvalid(old('nik', $siswa->nik)) ?>" value="<?= old('nik', $siswa->nik); ?>" maxlength="16" placeholder="Masukan 16 digit NIK" required />
                                        </div>
                                    </div>

                                    <!-- BAGIAN WHATSAPP & VERIFIKASI OTP -->
                                    <div class="row mb-6">
                                        <label class="col-12 col-lg-4 col-form-label fw-bold fs-6 required">Email & WhatsApp</label>
                                        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                                            <input type="email" class="form-control form-control-lg form-control-solid bg-light-secondary" value="<?= $siswa->email; ?>" readonly />
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <div class="input-group">
                                                <input type="number" name="hp" id="hp" 
                                                       data-original="<?= old('hp', $siswa->hp); ?>" 
                                                       data-verified="<?= $isWaVerified; ?>" 
                                                       class="form-control form-control-lg form-control-solid <?= $checkInvalid(old('hp', $siswa->hp)) ?>" 
                                                       value="<?= old('hp', $siswa->hp); ?>" required maxlength="15" placeholder="Nomor WhatsApp" />
                                                <button class="btn btn-primary" type="button" id="btn-send-otp" style="display: none;" title="Kirim OTP Ke WhatsApp">
                                                    Verifikasi
                                                </button>
                                            </div>

                                            <!-- Form OTP Dinamis -->
                                            <div id="otp-area" class="mt-3 p-4 border border-primary border-dashed rounded bg-light-primary" style="display: none;">
                                                <label class="form-label fw-bold text-primary fs-7">Masukkan 6 Digit OTP</label>
                                                <div class="input-group mb-2">
                                                    <input type="text" id="otp-input" class="form-control form-control-solid text-center fw-bolder fs-4 ls-2" placeholder="••••••" maxlength="6" autocomplete="off">
                                                    <button class="btn btn-success" type="button" id="btn-verify-otp">Cek Kode</button>
                                                </div>
                                                <div class="form-text text-muted fs-7">
                                                    Kode OTP kadaluarsa dalam: <span id="otp-timer" class="fw-bold text-danger fs-6">05:00</span>
                                                </div>
                                            </div>

                                            <!-- Badge Terverifikasi -->
                                            <div id="wa-verified-badge" class="mt-2" style="display: none;">
                                                <span class="badge badge-light-success fs-7 fw-bold p-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Nomor WA Terverifikasi</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END WHATSAPP -->

                                    <div class="row mb-6">
                                        <label class="col-12 col-lg-4 col-form-label fw-bold fs-6 required">Tempat & Tanggal Lahir</label>
                                        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                                            <input type="text" name="tempat_lahir" class="form-control form-control-lg form-control-solid <?= $checkInvalid(old('tempat_lahir', $siswa->tempat_lahir)) ?>" value="<?= old('tempat_lahir', $siswa->tempat_lahir); ?>" required placeholder="Tempat Lahir" />
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <input type="date" name="tgl_lahir" max="<?= date('Y-m-d', strtotime('-10 years')); ?>" class="form-control form-control-lg form-control-solid <?= $checkInvalid(old('tgl_lahir', $siswa->tgl_lahir)) ?>" value="<?= old('tgl_lahir', $siswa->tgl_lahir); ?>" required />
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-12 col-lg-4 col-form-label required fw-bold fs-6">Jenis Kelamin</label>
                                        <div class="col-12 col-lg-8">
                                            <select name="jenis_kelamin" class="form-select form-select-lg form-select-solid <?= $checkInvalid(old('jenis_kelamin', $siswa->jenis_kelamin)) ?>" required>
                                                <?php $jk = old('jenis_kelamin', $siswa->jenis_kelamin); ?>
                                                <option value="">- Pilih Jenis Kelamin -</option>
                                                <option value="Laki - Laki" <?= $jk == "Laki - Laki" ? 'selected' : '' ?>>Laki-Laki</option>
                                                <option value="Perempuan" <?= $jk == "Perempuan" ? 'selected' : '' ?>>Perempuan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="separator separator-dashed my-10"></div>

                                    <h4 class="fw-bold text-gray-800 mb-7"><i class="bi bi-geo-alt-fill me-2"></i>Alamat Lengkap</h4>

                                    <div class="row mb-6">
                                        <label class="col-12 col-lg-4 col-form-label fw-bold fs-6 required">Alamat KTP & Domisili</label>
                                        <div class="col-12 col-lg-8">
                                            <input type="text" name="alamat_ktp" class="form-control form-control-lg form-control-solid mb-3 <?= $checkInvalid(old('alamat_ktp', $siswa->alamat_ktp)) ?>" placeholder="Alamat lengkap sesuai KTP" value="<?= old('alamat_ktp', $siswa->alamat_ktp); ?>" required />
                                            <input type="text" name="alamat_domisili" class="form-control form-control-lg form-control-solid <?= $checkInvalid(old('alamat_domisili', $siswa->alamat_domisili)) ?>" placeholder="Alamat Domisili saat ini" value="<?= old('alamat_domisili', $siswa->alamat_domisili); ?>" required />
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <div class="col-lg-4 d-none d-lg-block"></div>
                                        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                                            <input type="text" name="kelurahan" class="form-control form-control-solid <?= $checkInvalid(old('kelurahan', $siswa->kelurahan)) ?>" placeholder="Kelurahan" value="<?= old('kelurahan', $siswa->kelurahan); ?>" required />
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <input type="text" name="kecamatan" class="form-control form-control-solid <?= $checkInvalid(old('kecamatan', $siswa->kecamatan)) ?>" placeholder="Kecamatan" value="<?= old('kecamatan', $siswa->kecamatan); ?>" required />
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <div class="col-lg-4 d-none d-lg-block"></div>
                                        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                                            <input type="text" name="kota" class="form-control form-control-solid <?= $checkInvalid(old('kota', $siswa->kota)) ?>" placeholder="Kota/Kabupaten" value="<?= old('kota', $siswa->kota); ?>" required />
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <input type="text" name="provinsi" class="form-control form-control-solid <?= $checkInvalid(old('provinsi', $siswa->provinsi)) ?>" placeholder="Provinsi" value="<?= old('provinsi', $siswa->provinsi); ?>" required />
                                        </div>
                                    </div>

                                    <div class="separator separator-dashed my-10"></div>

                                    <h4 class="fw-bold text-primary mb-7"><i class="bi bi-briefcase-fill me-2 text-primary"></i>Profil Profesi</h4>

                                    <div class="row mb-6">
                                        <label class="col-12 col-lg-4 col-form-label fw-bold fs-6 required">Pekerjaan & Bidang</label>
                                        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                                            <input type="text" name="profesi" class="form-control form-control-lg form-control-solid <?= $checkInvalid(old('profesi', $siswa->profesi)) ?>" placeholder="Profesi saat ini" value="<?= old('profesi', $siswa->profesi); ?>" required />
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <input type="text" name="bidang_usaha" class="form-control form-control-lg form-control-solid <?= $checkInvalid(old('bidang_usaha', $siswa->bidang_usaha)) ?>" placeholder="Bidang Usaha" value="<?= old('bidang_usaha', $siswa->bidang_usaha); ?>" required />
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-12 col-lg-4 col-form-label fw-bold fs-6 required">Kantor</label>
                                        <div class="col-12 col-lg-8">
                                            <select name="kantor" id="select_kantor" class="form-select form-select-lg form-select-solid mb-3 <?= $checkInvalid(old('kantor', $siswa->kantor)) ?>" data-control="select2" required>
                                                <option value="">- Pilih Jenis Kantor -</option>
                                                <?php $kat = old('kantor', $siswa->kantor); ?>
                                                <option value="Firma Hukum" <?= $kat == 'Firma Hukum' ? 'selected' : '' ?>>Firma Hukum</option>
                                                <option value="KAP" <?= $kat == 'KAP' ? 'selected' : '' ?>>KAP</option>
                                                <option value="KKP" <?= $kat == 'KKP' ? 'selected' : '' ?>>KKP</option>
                                                <option value="Mandiri" <?= $kat == 'Mandiri' ? 'selected' : '' ?>>Mandiri</option>
                                                <option value="Lainnya" <?= $kat == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                                            </select>
                                            <input type="text" name="nama_kantor" class="form-control form-control-lg form-control-solid mb-3 <?= $checkInvalid(old('nama_kantor', $siswa->nama_kantor)) ?>" placeholder="Nama Perusahaan/Lembaga/Instansi" value="<?= old('nama_kantor', $siswa->nama_kantor); ?>" required />
                                            <input type="text" name="alamat_kantor" class="form-control form-control-lg form-control-solid" placeholder="Alamat Perusahaan/Lembaga/Kantor (Opsional)" value="<?= old('alamat_kantor', $siswa->alamat_kantor); ?>" />
                                        </div>
                                    </div>

                                    <div class="separator separator-dashed my-10"></div>

                                    <h4 class="fw-bold text-primary mb-7"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Pekerjaan</h4>
                                    <div class="row mb-6">
                                        <label class="col-12 col-lg-4 col-form-label fw-bold fs-6 required">Daftar Riwayat</label>
                                        <div class="col-12 col-lg-8">
                                            <div id="riwayat_container">
                                                <?php
                                                $riwayat_data = $siswa->riwayat_pekerjaan ? json_decode($siswa->riwayat_pekerjaan, true) : old('riwayat_pekerjaan');
                                                if (empty($riwayat_data)):
                                                ?>
                                                    <div class="input-group mb-3 riwayat-row">
                                                        <input type="text" name="riwayat_pekerjaan[]" class="form-control form-control-lg form-control-solid" placeholder="Contoh: PT. Legalyn Indonesia (2015 - 2020)" required />
                                                    </div>
                                                <?php else: ?>
                                                    <?php foreach ($riwayat_data as $index => $riwayat): ?>
                                                        <div class="input-group mb-3 riwayat-row">
                                                            <input type="text" name="riwayat_pekerjaan[]" class="form-control form-control-lg form-control-solid" value="<?= esc($riwayat) ?>" placeholder="Contoh: PT. Legalyn Indonesia (2015 - 2020)" required/>
                                                            <?php if ($index > 0): ?>
                                                                <button type="button" class="btn btn-icon btn-light-danger btn-hapus-riwayat" title="Hapus Baris"><i class="ki-outline ki-trash fs-2"></i></button>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>

                                            <button type="button" class="btn btn-light-primary btn-sm mt-2 w-100 w-sm-auto" id="btn_tambah_riwayat">
                                                <i class="ki-outline ki-plus fs-2"></i> Tambah Riwayat Pekerjaan
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer d-flex justify-content-end py-6 px-9 bg-light-secondary rounded-bottom">
                                    <button type="submit" class="btn btn-primary fw-bold w-100 w-md-auto" id="file-submit">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    // Konfigurasi CSRF
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>';

    // Preview Gambar
    function previewImg() {
        var gambar = document.querySelector('#customFile');
        var imgPreview = document.querySelector('#profile_avatar_preview');
        var wrapper = document.querySelector('#preview_wrapper');

        if (gambar.files && gambar.files[0]) {
            var filegambar = new FileReader();
            filegambar.readAsDataURL(gambar.files[0]);
            filegambar.onload = function(e) {
                imgPreview.src = e.target.result;
                wrapper.style.backgroundImage = 'url(' + e.target.result + ')';
            }
        }
    }

    // Toggle Password
    function togglePassword() {
        var x = document.getElementById('pass');
        var btn = document.getElementById('mybutton');
        if (x.type === 'password') {
            x.type = 'text';
            btn.innerHTML = '<i class="bi bi-eye-fill fs-3 text-primary"></i>';
        } else {
            x.type = 'password';
            btn.innerHTML = '<i class="bi bi-eye-slash-fill fs-3"></i>';
        }
    }

    $(document).ready(function() {
        
        console.log("Script berhasil dimuat!");

        // -------------------------------------------------------------
        // BLOK VERIFIKASI WHATSAPP OTP
        // -------------------------------------------------------------
        var timerInterval;

        function checkWaStatus() {
            var currentHp = $('#hp').val();
            var originalHp = $('#hp').data('original');
            var isVerified = $('#hp').data('verified');
            var btnSubmit = $('#file-submit');

            // Reset UI State
            $('#btn-send-otp').hide();
            $('#wa-verified-badge').hide();
            btnSubmit.prop('disabled', false); 

            if (currentHp === '') {
                btnSubmit.prop('disabled', true);
            } else if (currentHp != originalHp || isVerified == 0) {
                $('#btn-send-otp').show();
                btnSubmit.prop('disabled', true);
                btnSubmit.html('<i class="bi bi-lock-fill"></i> Selesaikan Verifikasi WA');
            } else {
                $('#wa-verified-badge').show();
                btnSubmit.prop('disabled', false);
                btnSubmit.html('Simpan Perubahan');
            }
        }

        checkWaStatus();

        $('#hp').on('input', function() {
            if (this.value.length > 15) this.value = this.value.slice(0, 15);
            $('#otp-area').slideUp(); 
            clearInterval(timerInterval);
            checkWaStatus();
        });

        $('#btn-send-otp').click(function(e) {
            e.preventDefault();
            var hp = $('#hp').val();

            if (hp.length < 9) {
                Swal.fire('Format Salah', 'Pastikan nomor WhatsApp yang Anda masukkan valid!', 'warning');
                return;
            }

            var btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');

            var requestData = { hp: hp };
            requestData[csrfName] = csrfHash; // Assign CSRF secara manual untuk kompatibilitas penuh

            $.ajax({
                url: '<?= base_url("sw-siswa/profile/send-otp") ?>',
                type: 'POST',
                data: requestData,
                dataType: 'json',
                success: function(res) {
                    csrfHash = res.csrfHash; // Update token baru dari server
                    
                    // UPDATE TOKEN CSRF PADA FORM UTAMA
                    $('input[name="' + csrfName + '"]').val(csrfHash);
                    
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'OTP Terkirim!',
                            text: 'Silakan cek kotak masuk WhatsApp Anda.',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        btn.hide().prop('disabled', false).html('Verifikasi');
                        $('#otp-area').slideDown();
                        $('#otp-input').val('').focus();
                        startOtpTimer(300); // 5 Menit
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                        btn.prop('disabled', false).html('Coba Lagi');
                    }
                },
            });
        });

        $('#btn-verify-otp').click(function(e) {
            e.preventDefault();
            var otp = $('#otp-input').val();
            var hp = $('#hp').val();

            if (otp.length !== 6) {
                Swal.fire('Perhatian', 'Kode OTP harus 6 digit angka!', 'warning');
                return;
            }

            var btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Cek...');

            var requestData = { hp: hp, otp: otp };
            requestData[csrfName] = csrfHash;

            $.ajax({
                url: '<?= base_url("sw-siswa/profile/verify-otp") ?>',
                type: 'POST',
                data: requestData,
                dataType: 'json',
                success: function(res) {
                    csrfHash = res.csrfHash; // Update token baru dari server

                    // UPDATE TOKEN CSRF PADA FORM UTAMA
                    $('input[name="' + csrfName + '"]').val(csrfHash);

                    if (res.status === 'success') {
                        Swal.fire('Berhasil Terverifikasi!', 'Nomor WhatsApp Anda berhasil dihubungkan.', 'success');
                        
                        clearInterval(timerInterval);
                        $('#otp-area').slideUp();
                        
                        $('#hp').data('original', hp).data('verified', 1);
                        checkWaStatus(); 
                        
                        btn.prop('disabled', false).html('Cek Kode');
                        $('#otp-input').val('');
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                        btn.prop('disabled', false).html('Cek Kode');
                    }
                },
                error: function() {
                    Swal.fire('Oops!', 'Terjadi kesalahan saat memverifikasi.', 'error');
                    btn.prop('disabled', false).html('Cek Kode');
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
                    $('#btn-send-otp').show().html('Kirim Ulang OTP');
                    Swal.fire('Kadaluarsa', 'Waktu pengisian OTP telah habis, silakan kirim ulang kode.', 'info');
                }
            }, 1000);
        }

        // -------------------------------------------------------------
        // VALIDASI UI LAMA (Bawaan)
        // -------------------------------------------------------------
        $('input[required], select[required]').on('input change', function() {
            if ($(this).val().trim() !== '') {
                $(this).removeClass('is-invalid-field shake-error');
            }
        });

        $('#nik').on('input', function() {
            if (this.value.length > 16) this.value = this.value.slice(0, 16);
        });

        $("#customFile").on("change", function() {
            var fileResult = document.getElementById("file-result");
            var fileSubmit = document.getElementById("file-submit");
            if (this.files.length > 0) {
                var fileSize = this.files.item(0).size;
                var fileMb = fileSize / (1024 * 1024);
                if (fileMb >= 1.1) {
                    fileResult.innerHTML = "<i class='bi bi-exclamation-triangle-fill'></i> File melebihi batas 1MB!";
                    fileSubmit.disabled = true;
                } else {
                    fileResult.innerHTML = "<span class='text-success'><i class='bi bi-check-circle-fill'></i> Siap diupload (" + fileMb.toFixed(1) + "MB)</span>";
                    
                    if ($('#hp').data('verified') == 1) {
                        fileSubmit.disabled = false;
                    }
                }
            }
        });

        $('#formUpdateProfil').on('submit', function(e) {
            var isValid = true;
            var firstInvalidInput = null;

            $(this).find('input[required], select[required]').each(function() {
                if ($(this).val() === null || $(this).val().trim() === '') {
                    isValid = false;
                    $(this).addClass('is-invalid-field shake-error');
                    if (firstInvalidInput === null) {
                        firstInvalidInput = $(this);
                    }
                } else {
                    $(this).removeClass('is-invalid-field shake-error');
                }
            });

            var nik = $('#nik').val();
            if (nik && nik.length !== 16) {
                isValid = false;
                $('#nik').addClass('is-invalid-field shake-error');
                if (firstInvalidInput === null) firstInvalidInput = $('#nik');

                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Format NIK Tidak Valid',
                    text: 'KTP/NIK Anda harus terdiri dari 16 digit angka. Silakan periksa kembali.',
                    confirmButtonText: 'Baik, Saya Perbaiki',
                    customClass: { confirmButton: "btn btn-primary" }
                }).then(function() {
                    $('#nik').focus();
                });
                return false;
            }

            if (!isValid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops! Data Belum Lengkap',
                    text: 'Masih ada kolom wajib (bergaris merah) yang belum Anda isi. Mohon lengkapi terlebih dahulu.',
                    confirmButtonText: 'Lengkapi Sekarang',
                    customClass: { confirmButton: "btn btn-danger" }
                }).then(function() {
                    setTimeout(function() {
                        $('html, body').animate({
                            scrollTop: firstInvalidInput.offset().top - 150
                        }, 500);

                        if (firstInvalidInput.hasClass('select2-hidden-accessible')) {
                            firstInvalidInput.select2('open'); 
                        } else {
                            firstInvalidInput.focus(); 
                        }
                    }, 300);
                });

                setTimeout(function() {
                    $('.shake-error').removeClass('shake-error');
                }, 500);
            }
        });

        // -------------------------------------------------------------
        // SCRIPT DINAMIS RIWAYAT PEKERJAAN
        // -------------------------------------------------------------
        $('#btn_tambah_riwayat').click(function(e) {
            e.preventDefault();
            var barisBaru = '<div class="input-group mb-3 riwayat-row" style="display: none;">' +
                                '<input type="text" name="riwayat_pekerjaan[]" class="form-control form-control-lg form-control-solid" placeholder="Contoh: PT Contoh (2021 - Sekarang)" required />' +
                                '<button type="button" class="btn btn-icon btn-light-danger btn-hapus-riwayat" title="Hapus Baris">' +
                                    '<i class="ki-outline ki-trash fs-2"></i>' +
                                '</button>' +
                            '</div>';
            var el = $(barisBaru);
            $('#riwayat_container').append(el);
            el.slideDown('fast');
        });

        $(document).on('click', '.btn-hapus-riwayat', function(e) {
            e.preventDefault();
            var baris = $(this).closest('.riwayat-row');
            baris.slideUp('fast', function() {
                $(this).remove();
            });
        });

    });
</script>
<?= $this->endSection(); ?>
<?= $this->extend('siswa/template/app'); ?>

<?= $this->section('styles'); ?>
<style>
    .select2-container--bootstrap5 .select2-selection--single {
        min-height: calc(1.5em + 1.65rem + 2px) !important;
        padding: 0.825rem 1.5rem !important;
    }

    .select2-container--bootstrap5 .select2-selection__rendered {
        line-height: 1.5 !important;
    }
</style>
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid py-3 py-lg-6 mt-8">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="row g-7">
                <div class="col-xl-4">
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
                                        <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" id="mybutton" onclick="change()">
                                            <i class="bi bi-eye-slash-fill fs-3"></i>
                                        </span>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-light-primary fw-bold w-100">Simpan Sandi</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <div class="card mb-5 mb-xl-10">
                        <div class="card-header border-0 cursor-pointer" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details">
                            <div class="card-title m-0">
                                <h3 class="fw-bold m-0">Pengaturan Data Diri</h3>
                            </div>
                        </div>

                        <div id="kt_account_settings_profile_details" class="collapse show">
                            <form action="<?= base_url('sw-siswa/profile/update-data-diri'); ?>" method="post" enctype="multipart/form-data" class="form">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="id_siswa" value="<?= decrypt_url($siswa->id_siswa); ?>">

                                <div class="card-body border-top p-9">
                                    <div class="row mb-8">
                                        <label class="col-lg-4 col-form-label fw-semibold fs-6 text-gray-700">Pas Foto</label>
                                        <div class="col-lg-8">
                                            <div class="d-flex flex-column gap-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="image-input image-input-outline shadow-sm" data-kt-image-input="true">
                                                        <div class="image-input-wrapper w-125px h-125px" id="preview_wrapper" style="background-image: url(<?= base_url('assets/app-assets/user/' . $siswa->avatar) ?>)"></div>
                                                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Ubah Foto">
                                                            <i class="bi bi-pencil-fill fs-7"></i>
                                                            <input type="file" name="avatar" id="customFile" accept=".png, .jpg, .jpeg" onchange="previewImg()" />
                                                            <input type="hidden" name="gambar_lama" value="<?= $siswa->avatar; ?>">
                                                        </label>
                                                    </div>
                                                    <div class="ms-5">
                                                        <div class="form-text text-muted mb-2">Format 3x4 (Background Merah)<br>Ukuran maksimal: 1MB.</div>
                                                        <div id="file-result" class="fw-bold text-danger"></div>
                                                    </div>
                                                </div>

                                                <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-4 mt-2">
                                                    <i class="ki-outline ki-information-5 fs-2tx text-warning me-3"></i>
                                                    <div class="d-flex flex-stack flex-grow-1">
                                                        <div class="fw-semibold">
                                                            <h4 class="text-gray-900 fw-bold fs-6 mb-1">Penting: Verifikasi Keaslian</h4>
                                                            <div class="fs-7 text-gray-700">
                                                                Gunakan pas foto resmi terbaru. Sistem AI kami akan mencocokkan wajah pada foto profil ini dengan wajah Anda saat ujian berlangsung. <b>Ketidaksesuaian dapat menyebabkan penangguhan sertifikat Brevet Anda.</b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Nama Lengkap</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="nama_siswa" class="form-control form-control-lg form-control-solid" placeholder="Nama untuk sertifikat" value="<?= old('nama_siswa', $siswa->nama_siswa); ?>" required />
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-lg-4 col-form-label required fw-bold fs-6">NIK (16 Digit)</label>
                                        <div class="col-lg-8">
                                            <input type="number" name="nik" id="nik" class="form-control form-control-lg form-control-solid" value="<?= old('nik', $siswa->nik); ?>" maxlength="16" placeholder="Masukan 16 digit NIK" required />
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-lg-4 col-form-label fw-bold fs-6 required">Email & WA</label>
                                        <div class="col-lg-4">
                                            <input type="email" class="form-control form-control-lg form-control-solid bg-light-secondary" value="<?= $siswa->email; ?>" readonly />
                                        </div>
                                        <div class="col-lg-4">
                                            <input type="number" name="hp" id="hp" class="form-control form-control-lg form-control-solid" value="<?= old('hp', $siswa->hp); ?>" required maxlength="15" placeholder="WhatsApp" />
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <label class="col-lg-4 col-form-label fw-bold fs-6 required">Tempat & Tanggal Lahir</label>
                                        <div class="col-lg-4">
                                            <input type="text" name="tempat_lahir" class="form-control form-control-lg form-control-solid" value="<?= old('tempat_lahir', $siswa->tempat_lahir); ?>" required placeholder="Tempat Lahir" />
                                        </div>
                                        <div class="col-lg-4">
                                            <input type="date" name="tgl_lahir" class="form-control form-control-lg form-control-solid" value="<?= old('tgl_lahir', $siswa->tgl_lahir); ?>" required />
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-lg-4 col-form-label required fw-bold fs-6 required">Jenis Kelamin</label>
                                        <div class="col-lg-4">
                                            <select name="jenis_kelamin" class="form-select form-select-lg form-select-solid" required>
                                                <?php $jk = old('jenis_kelamin', $siswa->jenis_kelamin); ?>
                                                <option value="Laki - Laki" <?= $jk == "Laki - Laki" ? 'selected' : '' ?>>Laki-Laki</option>
                                                <option value="Perempuan" <?= $jk == "Perempuan" ? 'selected' : '' ?>>Perempuan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="separator separator-dashed my-10"></div>

                                    <h4 class="fw-bold text-gray-800 mb-7"><i class="bi bi-geo-alt-fill me-2"></i>Alamat Lengkap</h4>

                                    <div class="row mb-6">
                                        <label class="col-lg-4 col-form-label fw-bold fs-6 required">Alamat KTP & Domisili</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="alamat_ktp" class="form-control form-control-lg form-control-solid mb-3" placeholder="Alamat sesuai KTP" value="<?= old('alamat_ktp', $siswa->alamat_ktp); ?>" required />
                                            <input type="text" name="alamat_domisili" class="form-control form-control-lg form-control-solid" placeholder="Alamat Domisili saat ini" value="<?= old('alamat_domisili', $siswa->alamat_domisili); ?>" required />
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <div class="col-lg-4"></div>
                                        <div class="col-lg-4 mb-3 mb-lg-0">
                                            <input type="text" name="kelurahan" class="form-control form-control-solid" placeholder="Kelurahan" value="<?= old('kelurahan', $siswa->kelurahan); ?>" required />
                                        </div>
                                        <div class="col-lg-4">
                                            <input type="text" name="kecamatan" class="form-control form-control-solid" placeholder="Kecamatan" value="<?= old('kecamatan', $siswa->kecamatan); ?>" required />
                                        </div>
                                    </div>

                                    <div class="row mb-6">
                                        <div class="col-lg-4"></div>
                                        <div class="col-lg-4 mb-3 mb-lg-0">
                                            <input type="text" name="kota" class="form-control form-control-solid" placeholder="Kota" value="<?= old('kota', $siswa->kota); ?>" required />
                                        </div>
                                        <div class="col-lg-4">
                                            <input type="text" name="provinsi" class="form-control form-control-solid" placeholder="Provinsi" value="<?= old('provinsi', $siswa->provinsi); ?>" required />
                                        </div>
                                    </div>

                                    <div class="separator separator-dashed my-10"></div>

                                    <h4 class="fw-bold text-primary mb-7"><i class="bi bi-briefcase-fill me-2 text-primary"></i>Profil Profesi</h4>
                                    <div class="row mb-6">
                                        <label class="col-lg-4 col-form-label fw-bold fs-6 required">Pekerjaan</label>
                                        <div class="col-lg-4">
                                            <input type="text" name="profesi" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Profesi saat ini" value="<?= old('profesi', $siswa->profesi); ?>" required />
                                        </div>
                                        <div class="col-lg-4">
                                            <input type="text" name="bidang_usaha" class="form-control form-control-lg form-control-solid" placeholder="Bidang Usaha" value="<?= old('bidang_usaha', $siswa->bidang_usaha); ?>" required />
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <label class="col-lg-4 col-form-label fw-bold fs-6 required">Kantor</label>
                                        <div class="col-lg-8">
                                            <select name="kantor" class="form-select form-select-lg form-select-solid mb-3" data-control="select2" required>
                                                <option value="">-Pilih Kantor-</option>
                                                <?php $kat = $siswa->kantor; ?>
                                                <option value="Firma Hukum" <?= $kat == 'Firma Hukum' ? 'selected' : '' ?>>Firma Hukum</option>
                                                <option value="KAP" <?= $kat == 'KAP' ? 'selected' : '' ?>>KAP</option>
                                                <option value="KKP" <?= $kat == 'KKP' ? 'selected' : '' ?>>KKP</option>
                                                <option value="Mandiri" <?= $kat == 'Mandiri' ? 'selected' : '' ?>>Mandiri</option>
                                                <option value="Lainnya" <?= $kat == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                                            </select>
                                            <input type="text" name="nama_kantor" class="form-control form-control-lg form-control-solid mb-3" placeholder="Nama Perusahaan/Lembaga/Kantor" value="<?= old('kantor', $siswa->kantor); ?>" required />
                                            <input type="text" name="alamat_kantor" class="form-control form-control-lg form-control-solid" placeholder="Alamat Perusahaan/Lembaga/Kantor" value="<?= old('alamat_kantor', $siswa->alamat_kantor); ?>" />
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-10"></div>

                                    <h4 class="fw-bold text-primary mb-7"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Pekerjaan</h4>
                                    <div class="row mb-6">
                                        <label class="col-lg-4 col-form-label fw-bold fs-6 required">Daftar Riwayat</label>
                                        <div class="col-lg-8">

                                            <div id="riwayat_container">

                                                <?php
                                                // Logika jika data sudah ada dari database (misal disimpan dalam bentuk array/JSON)
                                                // Jika tidak ada, tampilkan 1 baris kosong default
                                                $riwayat_data = $siswa->riwayat_pekerjaan ? json_decode($siswa->riwayat_pekerjaan, true) : old('riwayat_pekerjaan'); // Sesuaikan dengan cara Anda menyimpan datanya nanti
                                                if (empty($riwayat_data)):
                                                ?>
                                                    <div class="input-group mb-3 riwayat-row">
                                                        <input type="text" name="riwayat_pekerjaan[]" class="form-control form-control-lg form-control-solid" placeholder="Contoh: PT. Legalyn Indonesia (2015 - 2020)" />
                                                    </div>
                                                <?php else: ?>
                                                    <?php foreach ($riwayat_data as $index => $riwayat): ?>
                                                        <div class="input-group mb-3 riwayat-row">
                                                            <input type="text" name="riwayat_pekerjaan[]" class="form-control form-control-lg form-control-solid" value="<?= esc($riwayat) ?>" placeholder="Contoh: PT. Legalyn Indonesia (2015 - 2020)" />
                                                            <?php if ($index > 0): ?>
                                                                <button type="button" class="btn btn-icon btn-light-danger btn-hapus-riwayat" title="Hapus Baris"><i class="ki-outline ki-trash fs-2"></i></button>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>

                                            </div>

                                            <button type="button" class="btn btn-light-primary btn-sm mt-2" id="btn_tambah_riwayat">
                                                <i class="ki-outline ki-plus fs-2"></i> Tambah Riwayat Pekerjaan
                                            </button>

                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer d-flex justify-content-end py-6 px-9 bg-light-secondary rounded-bottom">
                                    <button type="submit" class="btn btn-primary fw-bold" id="file-submit">Simpan Perubahan</button>
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
    function previewImg() {
        const gambar = document.querySelector('#customFile');
        const imgPreview = document.querySelector('#profile_avatar_preview');
        const wrapper = document.querySelector('#preview_wrapper');

        if (gambar.files && gambar.files[0]) {
            const filegambar = new FileReader();
            filegambar.readAsDataURL(gambar.files[0]);
            filegambar.onload = function(e) {
                imgPreview.src = e.target.result;
                wrapper.style.backgroundImage = `url(${e.target.result})`;
            }
        }
    }

    function change() {
        var x = document.getElementById('pass');
        var btn = document.getElementById('mybutton');
        if (x.type === 'password') {
            x.type = 'text';
            btn.innerHTML = `<i class="bi bi-eye-fill fs-3 text-primary"></i>`;
        } else {
            x.type = 'password';
            btn.innerHTML = `<i class="bi bi-eye-slash-fill fs-3"></i>`;
        }
    }

    // Validasi NIK 16 digit
    document.getElementById('nik').addEventListener('input', function() {
        if (this.value.length > 16) this.value = this.value.slice(0, 16);
    });
    document.getElementById('hp').addEventListener('input', function() {
        if (this.value.length > 15) this.value = this.value.slice(0, 15);
    });
                                                                
    // Validasi Ukuran File
    document.getElementById("customFile").addEventListener("change", function() {
        const fileResult = document.getElementById("file-result");
        const fileSubmit = document.getElementById("file-submit");
        if (this.files.length > 0) {
            const fileSize = this.files.item(0).size;
            const fileMb = fileSize / 1024 ** 2;
            if (fileMb >= 1.1) { // Toleransi sedikit dari 1MB
                fileResult.innerHTML = "<i class='bi bi-exclamation-triangle-fill'></i> File melebihi 1MB!";
                fileSubmit.disabled = true;
            } else {
                fileResult.innerHTML = "<span class='text-success'><i class='bi bi-check-circle-fill'></i> Siap diupload (" + fileMb.toFixed(1) + "MB)</span>";
                fileSubmit.disabled = false;
            }
        }
    });

    // Validasi Form saat submit
    document.querySelector('form[action*="update-data-diri"]').addEventListener('submit', function(e) {
        const nik = document.getElementById('nik').value;
        if (nik.length !== 16) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Format NIK Salah',
                text: 'NIK harus tepat 16 digit. Harap periksa kembali.',
                buttonsStyling: false,
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        }
    });

    $(document).ready(function() {
        
        // Fungsi untuk menambahkan baris input baru
        $('#btn_tambah_riwayat').click(function(e) {
            e.preventDefault();
            
            // Template HTML untuk baris baru
            let barisBaru = `
                <div class="input-group mb-3 riwayat-row" style="display: none;">
                    <input type="text" name="riwayat_pekerjaan[]" class="form-control form-control-lg form-control-solid" placeholder="Contoh: PT Contoh (2021 - Sekarang)" />
                    <button type="button" class="btn btn-icon btn-light-danger btn-hapus-riwayat" title="Hapus Baris">
                        <i class="ki-outline ki-trash fs-2"></i>
                    </button>
                </div>
            `;
            
            // Tambahkan ke kontainer dengan efek animasi slide down (turun ke bawah)
            let el = $(barisBaru);
            $('#riwayat_container').append(el);
            el.slideDown('fast');
        });

        // Fungsi untuk menghapus baris (Menggunakan Event Delegation karena elemen dibuat dinamis)
        $(document).on('click', '.btn-hapus-riwayat', function(e) {
            e.preventDefault();
            let baris = $(this).closest('.riwayat-row');
            
            // Berikan efek animasi naik ke atas sebelum dihapus dari DOM
            baris.slideUp('fast', function() {
                $(this).remove();
            });
        });

    });
</script>

<?= $this->endSection(); ?>
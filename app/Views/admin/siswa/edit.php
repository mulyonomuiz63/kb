<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <form action="<?= base_url('sw-admin/siswa/update/' . encrypt_url($siswa["id_siswa"])); ?>" method="POST" id="form-edit-siswa" class="form d-flex flex-column flex-lg-row">
                <?= csrf_field(); ?>

                <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">

                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Status Akun</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <select name="active" id="active" class="form-select form-select-solid" data-control="select2" data-hide-search="true">
                                <option value="1" <?= ($siswa["is_active"] == '1') ? 'selected' : ''; ?>>Aktif</option>
                                <option value="0" <?= ($siswa["is_active"] == '0') ? 'selected' : ''; ?>>Non-Aktif</option>
                            </select>
                            <div class="text-muted fs-7 mt-2">Atur hak akses peserta untuk login ke sistem.</div>
                        </div>
                    </div>

                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Validasi Data</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <select name="status" id="status" class="form-select form-select-solid" data-control="select2" data-hide-search="true">
                                <option value="S" <?= ($siswa["status"] == 'S') ? 'selected' : ''; ?>>Valid</option>
                                <option value="B" <?= ($siswa["status"] == 'B') ? 'selected' : ''; ?>>Tidak Valid</option>
                            </select>

                            <div class="notice d-flex bg-light-info rounded border-info border border-dashed mt-5 p-4">
                                <i class="ki-duotone ki-information-5 fs-2tx text-info me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <div class="d-flex flex-stack flex-grow-1">
                                    <div class="fw-semibold">
                                        <div class="fs-7 text-gray-700">Jika tidak valid, status ini akan menghambat penerbitan sertifikat.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">

                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Identitas Peserta</h2>
                            </div>
                        </div>

                        <div class="card-body pt-0">

                            <div class="mb-10 fv-row">
                                <label class="required form-label fw-semibold fs-6">Nama Peserta</label>
                                <input type="text" name="nama_siswa" id="nama_siswa" class="form-control form-control-solid" value="<?= old('nama_siswa', $siswa["nama_siswa"]); ?>" required placeholder="Masukkan nama lengkap">
                            </div>

                            <div class="mb-10 fv-row">
                                <label class="required form-label fw-semibold fs-6">Email</label>
                                <input type="email" name="email" id="email" class="form-control form-control-solid" value="<?= old('email', $siswa["email"]); ?>" required placeholder="nama@email.com">
                            </div>

                            <div class="row row-cols-1 row-cols-md-2 mb-10">
                                <div class="col">
                                    <div class="fv-row">
                                        <label class="required form-label fw-semibold fs-6">Kelas</label>
                                        <select name="kelas" id="kelas" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Kelas" required>
                                            <option></option>
                                            <?php foreach ($kelas as $kel) : ?>
                                                <option value="<?= $kel->id_kelas; ?>" <?= ($siswa["kelas"] == $kel->id_kelas) ? 'selected' : ''; ?>>
                                                    <?= $kel->nama_kelas; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="fv-row">
                                        <label class="form-label fw-semibold fs-6">Password (Opsional)</label>
                                        <div class="position-relative mb-3">
                                            <input class="form-control form-control-solid" type="password" placeholder="Isi jika ingin diubah" name="password" id="password" autocomplete="off" />
                                            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" id="togglePassword">
                                                <i class="ki-duotone ki-eye-slash fs-2" id="toggleIcon"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                            </span>
                                        </div>
                                        <div class="text-muted fs-7">Kosongkan jika tidak ingin merubah password.</div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="<?= base_url('sw-admin/siswa'); ?>" class="btn btn-light me-5">Kembali</a>
                            <button type="submit" class="btn btn-primary me-4">
                                <span class="indicator-label">
                                    <i class="ki-duotone ki-save-2 fs-3 me-1"><span class="path1"></span><span class="path2"></span></i> Simpan Perubahan
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {

        // Inisialisasi Select2 Native Metronic
        $('[data-control="select2"]').select2();

        // Fitur Show/Hide Password dengan Icon Metronic
        $('#togglePassword').on('click', function() {
            const input = $('#password');
            const icon = $('#toggleIcon');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('ki-eye-slash').addClass('ki-eye');
            } else {
                input.attr('type', 'password');
                icon.removeClass('ki-eye').addClass('ki-eye-slash');
            }
        });

        // Submit dengan Konfirmasi SweetAlert2 (Gaya Metronic)
        $('#form-edit-siswa').on('submit', function(e) {
            e.preventDefault();

            let nama = $('#nama_siswa').val();

            Swal.fire({
                html: `Simpan perubahan data untuk <span class='badge badge-light-primary fw-bold'>${nama}</span>?`,
                icon: "info",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Ya, Simpan!",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function(result) {
                if (result.isConfirmed) {
                    // Tampilkan loading state
                    Swal.fire({
                        text: "Sedang menyimpan data...",
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit form
                    $('#form-edit-siswa')[0].submit();
                }
            });
        });

    });
</script>
<?= $this->endSection(); ?>
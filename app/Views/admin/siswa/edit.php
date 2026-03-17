<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4><i class="bi bi-pencil-square mr-2"></i>Edit Data Peserta</h4>
                    </div>
                </div>
            </div>

            <form action="<?= base_url('sw-admin/siswa/update/' . encrypt_url($siswa["id_siswa"])); ?>" method="POST" id="form-edit-siswa">
                <?= csrf_field(); ?>
                <div class="row">
                    <div class="col-md-8">
                        <div class="widget shadow p-4 bg-white" style="border-radius: 8px;">
                            <div class="form-group">
                                <label class="font-weight-bold">Nama Peserta</label>
                                <input type="text" name="nama_siswa" id="nama_siswa" class="form-control"
                                    value="<?= old('nama_siswa', $siswa["nama_siswa"]); ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="<?= old('email', $siswa["email"]); ?>" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="font-weight-bold">Kelas</label>
                                    <select name="kelas" id="kelas" class="form-control" required>
                                        <option value="">Pilih Kelas</option>
                                        <?php foreach ($kelas as $kel) : ?>
                                            <option value="<?= $kel->id_kelas; ?>" <?= ($siswa["kelas"] == $kel->id_kelas) ? 'selected' : ''; ?>>
                                                <?= $kel->nama_kelas; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="font-weight-bold">Password (Opsinonal)</label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Isi jika ingin ubah">
                                        <div class="input-group-append">
                                            <span class="input-group-text" style="cursor: pointer;" id="togglePassword">
                                                <i class="bi bi-eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <small class="text-muted">Kosongkan jika tidak ingin merubah password.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="widget shadow p-4 bg-white" style="border-radius: 8px;">
                            <div class="form-group">
                                <label class="font-weight-bold">Akun Aktif</label>
                                <select name="active" id="active" class="form-control">
                                    <option value="1" <?= ($siswa["is_active"] == '1') ? 'selected' : ''; ?>>Yes (Aktif)</option>
                                    <option value="0" <?= ($siswa["is_active"] == '0') ? 'selected' : ''; ?>>No (Non-Aktif)</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Validasi Kelengkapan Data</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="S" <?= ($siswa["status"] == 'S') ? 'selected' : ''; ?>>Valid</option>
                                    <option value="B" <?= ($siswa["status"] == 'B') ? 'selected' : ''; ?>>Tidak Valid</option>
                                </select>
                                <div class="alert alert-light-info border-0 small mt-2 py-2">
                                    <i class="bi bi-info-circle mr-1"></i> Jika data tidak valid, status ini akan menghambat penerbitan sertifikat.
                                </div>
                            </div>

                            <hr>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="bi bi-save mr-1"></i> Simpan Perubahan
                            </button>
                            <a href="<?= base_url('sw-admin/siswa'); ?>" class="btn btn-outline-secondary btn-block">
                                Batal
                            </a>
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
        // Fitur Show/Hide Password
        $('#togglePassword').on('click', function() {
            const input = $('#password');
            const icon = $(this).find('i');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('bi-eye').addClass('bi-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('bi-eye-slash').addClass('bi-eye');
            }
        });

        // Submit dengan Konfirmasi Swal
        $('#form-edit-siswa').on('submit', function(e) {
            e.preventDefault();

            Swals.confirm(
                'Konfirmasi Update',
                'Simpan perubahan data untuk ' + $('#nama_siswa').val() + '?',
                function() {
                    Swals.loading('Memproses...', 'Sedang mengupdate data ke server');
                    $('#form-edit-siswa')[0].submit();
                }
            );
        });
    });
</script>
<?= $this->endSection(); ?>
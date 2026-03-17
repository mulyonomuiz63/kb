<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<div class="layout-px-spacing">
    <form action="<?= base_url('sw-admin/siswa/store'); ?>" method="POST" id="form-tambah-siswa">
        <?= csrf_field(); ?>
        <div class="row layout-top-spacing">
            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 layout-spacing">
                <div class="widget shadow p-4 bg-white" style="border-radius: 8px;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="font-weight-bold mb-1"><i class="bi bi-people-fill mr-2"></i>Data Peserta</h5>
                            <p class="text-muted small mb-0">Tambahkan satu atau lebih peserta ke dalam sistem.</p>
                        </div>
                        <button type="button" class="btn btn-success btn-sm tambah-baris-siswa">
                            <i class="bi bi-plus-circle mr-1"></i> Tambah Baris
                        </button>
                    </div>

                    <div id="container-siswa">
                        <?php
                        // Ambil data lama dari session jika ada (saat validasi gagal)
                        $old_nis = old('nis') ?? ['']; // Default minimal 1 baris kosong
                        $old_nama = old('nama_siswa') ?? [''];
                        $old_email = old('email') ?? [''];
                        $old_jk = old('jenis_kelamin') ?? [''];
                        $old_kelas = old('kelas') ?? [''];
                        foreach ($old_nis as $key => $val) :
                        ?>
                            <div class="card mb-3 item-siswa border shadow-none" style="border-radius: 10px;">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                    <span class="btn btn-outline-primary btn-sm counter shadow-sm">
                                        <i class="bi bi-person mr-1"></i> Peserta <?= $key + 1; ?>
                                    </span>
                                    <button type="button" class="btn btn-outline-danger btn-sm btn-remove-row <?= ($key == 0 && count($old_nis) == 1) ? 'd-none' : ''; ?>">
                                        <i class="bi bi-trash mr-1"></i>Hapus
                                    </button>
                                </div>
                                <div class="card-body p-3">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="small font-weight-bold">Whatsapp</label>
                                            <input type="text" name="nis[]" required class="form-control val-hp"
                                                value="<?= old('nis.' . $key); ?>" placeholder="Contoh: 0812234567">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="small font-weight-bold">Nama Lengkap</label>
                                            <input type="text" name="nama_siswa[]" required class="form-control"
                                                value="<?= old('nama_siswa.' . $key); ?>" placeholder="Nama lengkap...">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="small font-weight-bold">Email</label>
                                            <input type="email" name="email[]" required class="form-control"
                                                value="<?= old('email.' . $key); ?>" placeholder="email@domain.com">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="small font-weight-bold">Gender</label>
                                            <select name="jenis_kelamin[]" required class="form-control">
                                                <option value="">Pilih</option>
                                                <option value="Laki - Laki" <?= old('jenis_kelamin.' . $key) == 'Laki - Laki' ? 'selected' : ''; ?>>Laki - Laki</option>
                                                <option value="Perempuan" <?= old('jenis_kelamin.' . $key) == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="small font-weight-bold">Kelas</label>
                                            <select name="kelas[]" required class="form-control">
                                                <option value="">Pilih Kelas</option>
                                                <?php foreach ($kelas as $kel) : ?>
                                                    <option value="<?= $kel->id_kelas; ?>" <?= old('kelas.' . $key) == $kel->id_kelas ? 'selected' : ''; ?>>
                                                        <?= $kel->nama_kelas; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 layout-spacing">
                <div class="widget shadow p-4 bg-white mb-4" style="border-radius: 8px;">
                    <h6 class="font-weight-bold mb-3">Pengaturan Batch</h6>

                    <div class="alert alert-info border-0 small">
                        <i class="bi bi-info-circle-fill mr-1"></i> Pastikan data email tidak duplikat dengan data yang sudah ada di sistem.
                    </div>

                    <hr>

                    <div class="mt-4" id="submit-wrapper">
                        <button type="submit" id="btn-submit" class="btn btn-primary btn-block mb-2" style="display: none;">
                            <i class="bi bi-cloud-arrow-up-fill mr-1"></i> Simpan Semua Data
                        </button>
                        <a href="<?= base_url('sw-admin/siswa'); ?>" class="btn btn-outline-secondary btn-block">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {

        function checkFormValidity() {
            let allFilled = true;
            let anyInvalid = $('.is-invalid').length > 0; // Cek apakah ada class is-invalid

            // Cek semua input yang wajib diisi
            $('#form-tambah-siswa [required]').each(function() {
                if ($(this).val() === '') {
                    allFilled = false;
                    return false;
                }
            });

            // Tampilkan tombol jika semua terisi DAN tidak ada error validasi
            if (allFilled && !anyInvalid) {
                $('#btn-submit').fadeIn();
            } else {
                $('#btn-submit').fadeOut();
            }
        }

        // Jalankan pengecekan setiap ada perubahan di form
        $(document).on('keyup change input', '#form-tambah-siswa input, #form-tambah-siswa select', function() {
            // Khusus untuk validasi HP (Logika kamu yang sebelumnya)
            if ($(this).hasClass('val-hp')) {
                let val = $(this).val().replace(/[^0-9]/g, '');
                $(this).val(val);

                if (val.length > 0 && (val.length < 10 || val.length > 15)) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            }

            // Panggil fungsi tampil/sembunyi tombol
            checkFormValidity();
        });

        // Modifikasi fungsi hapus agar cek validasi lagi setelah baris dibuang
        $(document).on('click', '.btn-remove-row', function() {
            $(this).closest('.item-siswa').fadeOut(300, function() {
                $(this).remove();
                updateCounter();
                checkFormValidity(); // Cek ulang setelah baris dihapus
            });
        });

        // Modifikasi fungsi tambah baris agar tombol sembunyi lagi (karena baris baru masih kosong)
        $('.tambah-baris-siswa').on('click', function() {
            var newCard = $('.item-siswa:first').clone();
            newCard.find('input').val('').removeClass('is-invalid'); // Reset input & error
            newCard.find('select').val('');
            newCard.find('.btn-remove-row').removeClass('d-none');
            $('#container-siswa').append(newCard);
            updateCounter();
            checkFormValidity(); // Sembunyikan tombol karena ada baris kosong baru
        });

        // Fungsi updateCounter tetap sama
        function updateCounter() {
            $('.item-siswa').each(function(index) {
                $(this).find('.counter').html('<i class="bi bi-person mr-1"></i> Peserta ' + (index + 1));
            });
        }

        // Submit Loading
        $('#form-tambah-siswa').on('submit', function() {
            Swals.loading();
        });
        checkFormValidity();
    });
</script>
<?= $this->endSection(); ?>
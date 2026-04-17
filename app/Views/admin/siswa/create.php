<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <form action="<?= base_url('sw-admin/siswa/store'); ?>" method="POST" id="form-tambah-siswa" class="form d-flex flex-column flex-lg-row">
                <?= csrf_field(); ?>

                <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Aksi Penyimpanan</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            
                            <div class="notice d-flex bg-light-info rounded border-info border border-dashed mb-7 p-4">
                                <i class="ki-duotone ki-information-5 fs-2tx text-info me-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <div class="d-flex flex-stack flex-grow-1">
                                    <div class="fw-semibold">
                                        <div class="fs-7 text-gray-700">Pastikan data email dan Whatsapp tidak duplikat dengan data yang sudah ada di sistem.</div>
                                    </div>
                                </div>
                            </div>

                            <div id="submit-wrapper">
                                <button type="submit" id="btn-submit" class="btn btn-primary w-100 mb-3" style="display: none;">
                                    <i class="ki-duotone ki-cloud-add fs-2"><span class="path1"></span><span class="path2"></span></i> Simpan Semua
                                </button>
                                <a href="<?= base_url('sw-admin/siswa'); ?>" class="btn btn-light w-100">
                                    Batal
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <div class="card card-flush py-4">
                        
                        <div class="card-header align-items-center">
                            <div class="card-title">
                                <h2>Form Data Peserta</h2>
                            </div>
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-sm btn-light-primary tambah-baris-siswa">
                                    <i class="ki-duotone ki-plus fs-2"></i> Tambah Baris
                                </button>
                            </div>
                        </div>

                        <div class="card-body pt-0" id="container-siswa">
                            
                            <?php
                            // Ambil data lama dari session jika ada (saat validasi gagal)
                            $old_nis = old('nis') ?? ['']; // Default minimal 1 baris kosong
                            foreach ($old_nis as $key => $val) :
                            ?>
                            
                            <div class="item-siswa border border-dashed border-gray-300 rounded p-7 mb-7">
                                
                                <div class="d-flex flex-stack mb-5">
                                    <span class="badge badge-light-primary fw-bold px-4 py-2 fs-7 counter">
                                        Peserta <?= $key + 1; ?>
                                    </span>
                                    <button type="button" class="btn btn-sm btn-icon btn-light-danger btn-remove-row <?= ($key == 0 && count($old_nis) == 1) ? 'd-none' : ''; ?>" data-bs-toggle="tooltip" title="Hapus Baris">
                                        <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                    </button>
                                </div>

                                <div class="row g-5">
                                    <div class="col-md-6 fv-row">
                                        <label class="required form-label fw-semibold fs-6">Whatsapp</label>
                                        <input type="text" name="nis[]" required class="form-control form-control-solid val-hp" value="<?= old('nis.' . $key); ?>" placeholder="Contoh: 0812234567">
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="required form-label fw-semibold fs-6">Nama Lengkap</label>
                                        <input type="text" name="nama_siswa[]" required class="form-control form-control-solid" value="<?= old('nama_siswa.' . $key); ?>" placeholder="Nama lengkap...">
                                    </div>
                                    <div class="col-md-12 fv-row">
                                        <label class="required form-label fw-semibold fs-6">Email</label>
                                        <input type="email" name="email[]" required class="form-control form-control-solid" value="<?= old('email.' . $key); ?>" placeholder="email@domain.com">
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="required form-label fw-semibold fs-6">Gender</label>
                                        <select name="jenis_kelamin[]" required class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Pilih Gender">
                                            <option></option>
                                            <option value="Laki - Laki" <?= old('jenis_kelamin.' . $key) == 'Laki - Laki' ? 'selected' : ''; ?>>Laki - Laki</option>
                                            <option value="Perempuan" <?= old('jenis_kelamin.' . $key) == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="required form-label fw-semibold fs-6">Kelas</label>
                                        <select name="kelas[]" required class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Kelas">
                                            <option></option>
                                            <?php foreach ($kelas as $kel) : ?>
                                                <option value="<?= $kel->id_kelas; ?>" <?= old('kelas.' . $key) == $kel->id_kelas ? 'selected' : ''; ?>>
                                                    <?= $kel->nama_kelas; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <?php endforeach; ?>

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

        // Inisialisasi awal select2
        $('[data-control="select2"]').select2();

        // Cek Validitas Form
        function checkFormValidity() {
            let allFilled = true;
            let anyInvalid = $('.is-invalid').length > 0;

            $('#form-tambah-siswa [required]').each(function() {
                if ($(this).val() === '' || $(this).val() === null) {
                    allFilled = false;
                    return false;
                }
            });

            if (allFilled && !anyInvalid) {
                $('#btn-submit').fadeIn(300);
            } else {
                $('#btn-submit').fadeOut(300);
            }
        }

        // Live Validation
        $(document).on('keyup change input', '#form-tambah-siswa input, #form-tambah-siswa select', function() {
            // Validasi WA
            if ($(this).hasClass('val-hp')) {
                let val = $(this).val().replace(/[^0-9]/g, '');
                $(this).val(val);

                if (val.length > 0 && (val.length < 10 || val.length > 15)) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            }
            checkFormValidity();
        });

        // Hapus Baris
        $(document).on('click', '.btn-remove-row', function() {
            $(this).closest('.item-siswa').slideUp(300, function() {
                $(this).remove();
                updateCounter();
                checkFormValidity();
            });
        });

        // Tambah Baris (Dengan perbaikan Metronic Select2)
        $('.tambah-baris-siswa').on('click', function() {
            var newCard = $('.item-siswa:first').clone();
            
            // 1. Bersihkan sisa-sisa elemen Select2 bawaan Metronic dari objek hasil clone
            newCard.find('.select2-container').remove();
            newCard.find('select').removeClass('select2-hidden-accessible').removeAttr('data-select2-id tabindex aria-hidden').val('');
            newCard.find('select option').removeAttr('data-select2-id');

            // 2. Bersihkan Value Input & Error state
            newCard.find('input').val('').removeClass('is-invalid');
            
            // 3. Tampilkan tombol hapus
            newCard.find('.btn-remove-row').removeClass('d-none');
            
            // 4. Masukkan ke container dan sembunyikan sementara untuk efek animasi
            newCard.hide();
            $('#container-siswa').append(newCard);
            newCard.slideDown(300);
            
            // 5. Inisialisasi ulang Select2 HANYA pada baris yang baru ditambah
            newCard.find('[data-control="select2"]').select2();

            // 6. Update UI
            updateCounter();
            checkFormValidity();
            
            // Re-init Tooltip Metronic (Opsional jika pakai tooltip)
            KTApp.initBootstrapTooltips();
        });

        // Update Label Peserta 1, Peserta 2, dst
        function updateCounter() {
            $('.item-siswa').each(function(index) {
                $(this).find('.counter').text('Peserta ' + (index + 1));
            });
        }

        // Submit dengan SweetAlert Loading Metronic
        $('#form-tambah-siswa').on('submit', function() {
            Swal.fire({
                text: "Menyimpan data...",
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });

        // Jalankan pengecekan di awal
        checkFormValidity();
    });
</script>
<?= $this->endSection(); ?>
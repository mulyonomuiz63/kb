<?= $this->extend('siswa/template/app'); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid py-3 py-lg-6 mt-8">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6 mb-8">
                <i class="ki-outline ki-document text-primary fs-2tx me-4"></i>
                <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                    <div class="mb-3 mb-md-0 fw-semibold">
                        <h4 class="text-gray-900 fw-bold">Pendaftaran Izin Kuasa Hukum (IKH)</h4>
                        <div class="fs-6 text-gray-700 pe-7">Lengkapi data secara bertahap. Pastikan semua dokumen yang diunggah adalah hasil <strong>scan asli</strong> yang jelas terbaca.</div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-lg-15">
                    
                    <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid" id="kt_stepper_ikh">
                        
                        <div class="d-flex justify-content-center justify-content-xl-start flex-row-auto w-100 w-xl-300px">
                            <div class="stepper-nav ps-lg-10">
                                
                                <div class="stepper-item current" data-kt-stepper-element="nav">
                                    <div class="stepper-wrapper">
                                        <div class="stepper-icon w-40px h-40px">
                                            <i class="stepper-check fas fa-check"></i>
                                            <span class="stepper-number">1</span>
                                        </div>
                                        <div class="stepper-label">
                                            <h3 class="stepper-title">Data Diri</h3>
                                            <div class="stepper-desc">Informasi Dasar Pemohon</div>
                                        </div>
                                    </div>
                                    <div class="stepper-line h-40px"></div>
                                </div>

                                <div class="stepper-item" data-kt-stepper-element="nav">
                                    <div class="stepper-wrapper">
                                        <div class="stepper-icon w-40px h-40px">
                                            <i class="stepper-check fas fa-check"></i>
                                            <span class="stepper-number">2</span>
                                        </div>
                                        <div class="stepper-label">
                                            <h3 class="stepper-title">Lampiran Berkas</h3>
                                            <div class="stepper-desc">Upload Dokumen Persyaratan</div>
                                        </div>
                                    </div>
                                    <div class="stepper-line h-40px"></div>
                                </div>

                                <div class="stepper-item" data-kt-stepper-element="nav">
                                    <div class="stepper-wrapper">
                                        <div class="stepper-icon w-40px h-40px">
                                            <i class="stepper-check fas fa-check"></i>
                                            <span class="stepper-number">3</span>
                                        </div>
                                        <div class="stepper-label">
                                            <h3 class="stepper-title">Pernyataan</h3>
                                            <div class="stepper-desc">Persetujuan Sah Secara Hukum</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="flex-row-fluid py-lg-5 px-lg-15">
                            <form class="form" novalidate="novalidate" id="form_pendaftaran_ikh" action="<?= base_url('sw-siswa/ikh/simpan') ?>" method="POST" enctype="multipart/form-data">
                                <?= csrf_field(); ?>

                                <div class="current" data-kt-stepper-element="content">
                                    <div class="w-100">
                                        <div class="pb-10 pb-lg-15">
                                            <h2 class="fw-bold text-dark">Langkah 1: Lengkapi Data Diri</h2>
                                            <div class="text-muted fw-semibold fs-6">Sesuai dengan dokumen KTP dan NPWP Anda.</div>
                                        </div>

                                        <div class="row g-5">
                                            <div class="col-md-6">
                                                <label class="required form-label">NIK (Nomor Induk Kependudukan)</label>
                                                <input type="number" name="nik" class="form-control form-control-solid" placeholder="16 Digit NIK" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="required form-label">NPWP</label>
                                                <input type="text" name="npwp" class="form-control form-control-solid" placeholder="99.999.999.9-999.000" required />
                                            </div>
                                            <div class="col-md-12">
                                                <label class="required form-label">Nama Lengkap (Sesuai KTP/Ijazah)</label>
                                                <input type="text" name="nama_lengkap" class="form-control form-control-solid" placeholder="Mulyono, S.H., M.H." required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="required form-label">Tempat Lahir</label>
                                                <input type="text" name="tempat_lahir" class="form-control form-control-solid" placeholder="Kota Kelahiran" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="required form-label">Tanggal Lahir</label>
                                                <input type="date" name="tanggal_lahir" class="form-control form-control-solid" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="required form-label">Pendidikan Terakhir</label>
                                                <select name="pendidikan_terakhir" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Pilih Jenjang" required>
                                                    <option></option>
                                                    <option value="D3">D3</option><option value="D4">D4</option><option value="S1">S1</option><option value="S2">S2</option><option value="S3">S3</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="required form-label">Nomor WhatsApp</label>
                                                <input type="number" name="no_wa" class="form-control form-control-solid" placeholder="Contoh: 08123456789" required />
                                            </div>
                                            <div class="col-md-12">
                                                <label class="required form-label">Email Aktif</label>
                                                <input type="email" name="email" class="form-control form-control-solid" placeholder="email@contoh.com" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="required form-label">Kategori Kantor</label>
                                                <select name="kategori_kantor" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Pilih Kategori" required>
                                                    <option></option>
                                                    <option value="Firma Hukum">Firma Hukum</option><option value="KAP">KAP</option><option value="KKP">KKP</option><option value="Mandiri">Mandiri</option><option value="Lainnya">Lainnya</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="required form-label">Nama Kantor</label>
                                                <input type="text" name="nama_kantor" class="form-control form-control-solid" placeholder="Nama Instansi/Firma" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="required form-label">Alamat Sesuai KTP</label>
                                                <textarea name="alamat_ktp" class="form-control form-control-solid" rows="3" placeholder="Alamat lengkap sesuai KTP" required></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="required form-label">Alamat Korespondensi</label>
                                                <textarea name="alamat_korespondensi" class="form-control form-control-solid" rows="3" placeholder="Alamat domisili saat ini" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div data-kt-stepper-element="content">
                                    <div class="w-100">
                                        <div class="pb-10 pb-lg-15">
                                            <h2 class="fw-bold text-dark">Langkah 2: Unggah Lampiran</h2>
                                            <div class="text-muted fw-semibold fs-6">Harap unggah hasil scan asli dengan format PDF atau JPG/PNG.</div>
                                        </div>

                                        <div class="row g-7">
                                            <div class="col-md-6">
                                                <label class="required form-label">KTP (Scan Asli)</label>
                                                <input type="file" name="file_ktp" class="form-control form-control-solid" accept=".pdf,.jpg,.jpeg,.png" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="required form-label">NPWP (Scan Asli)</label>
                                                <input type="file" name="file_npwp" class="form-control form-control-solid" accept=".pdf,.jpg,.jpeg,.png" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="required form-label">Kartu Keluarga</label>
                                                <input type="file" name="file_kk" class="form-control form-control-solid" accept=".pdf,.jpg,.jpeg,.png" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="required form-label">Pas Foto 4x6</label>
                                                <input type="file" name="file_foto" class="form-control form-control-solid" accept=".jpg,.jpeg,.png" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="required form-label">SKCK <span class="text-danger fs-8">(Tujuan: Pengadilan Pajak)</span></label>
                                                <input type="file" name="file_skck" class="form-control form-control-solid" accept=".pdf" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="required form-label">Ijazah Terakhir <span class="text-danger fs-8">(Scan ASLI)</span></label>
                                                <input type="file" name="file_ijazah" class="form-control form-control-solid" accept=".pdf" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="required form-label">Bukti Terima SPT 2 Tahun Terakhir</label>
                                                <input type="file" name="file_spt" class="form-control form-control-solid" accept=".pdf" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="required form-label">Daftar Riwayat Hidup <span class="text-danger fs-8">(Format Khusus)</span></label>
                                                <input type="file" name="file_cv" class="form-control form-control-solid" accept=".pdf" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="required form-label">Sertifikat Brevet Pajak AB</label>
                                                <input type="file" name="file_sertifikat" class="form-control form-control-solid" accept=".pdf,.jpg,.png" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="required form-label">TTD Elektronik (Spesimen)</label>
                                                <input type="file" name="file_ttd" class="form-control form-control-solid" accept=".jpg,.jpeg,.png" required />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div data-kt-stepper-element="content">
                                    <div class="w-100">
                                        <div class="pb-10 pb-lg-15">
                                            <h2 class="fw-bold text-dark">Langkah 3: Lembar Pernyataan</h2>
                                            <div class="text-muted fw-semibold fs-6">Centang kotak persetujuan sah di bawah ini sebagai pengganti E-Materai.</div>
                                        </div>

                                        <div class="d-flex flex-column gap-5">
                                            <div class="form-check form-check-custom form-check-solid form-check-success p-5 rounded border border-gray-300">
                                                <input class="form-check-input h-30px w-30px" type="checkbox" value="1" name="check_pns" id="check_pns" required />
                                                <label class="form-check-label text-gray-800 fw-bold fs-5 cursor-pointer ms-4" for="check_pns">
                                                    Saya menyatakan TIDAK BERSTATUS PNS <br>
                                                    <span class="text-danger fs-7 fw-normal">(Telah menyetujui dokumen ber-E-MATERAI)</span>
                                                </label>
                                            </div>

                                            <div class="form-check form-check-custom form-check-solid form-check-success p-5 rounded border border-gray-300">
                                                <input class="form-check-input h-30px w-30px" type="checkbox" value="1" name="check_pakta" id="check_pakta" required />
                                                <label class="form-check-label text-gray-800 fw-bold fs-5 cursor-pointer ms-4" for="check_pakta">
                                                    Saya menyetujui PAKTA INTEGRITAS <br>
                                                    <span class="text-danger fs-7 fw-normal">(Telah menyetujui dokumen ber-E-MATERAI)</span>
                                                </label>
                                            </div>

                                            <div class="form-check form-check-custom form-check-solid form-check-success p-5 rounded border border-gray-300">
                                                <input class="form-check-input h-30px w-30px" type="checkbox" value="1" name="check_pengajuan" id="check_pengajuan" required />
                                                <label class="form-check-label text-gray-800 fw-bold fs-5 cursor-pointer ms-4" for="check_pengajuan">
                                                    Saya menyetujui PERNYATAAN PENGAJUAN IKH <br>
                                                    <span class="text-danger fs-7 fw-normal">(Telah menyetujui dokumen ber-E-MATERAI)</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex flex-stack pt-15">
                                    <div class="mr-2">
                                        <button type="button" class="btn btn-lg btn-light-primary me-3" data-kt-stepper-action="previous" style="display: none;">
                                            <i class="ki-outline ki-arrow-left fs-4 me-1"></i> Kembali
                                        </button>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-lg btn-primary" data-kt-stepper-action="submit" style="display: none;">
                                            <span class="indicator-label">Ajukan Permohonan <i class="ki-outline ki-arrow-right fs-4 ms-2"></i></span>
                                            <span class="indicator-progress">Mengunggah... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                        </button>
                                        
                                        <button type="button" class="btn btn-lg btn-primary" data-kt-stepper-action="next">
                                            Lanjut <i class="ki-outline ki-arrow-right fs-4 ms-1"></i>
                                        </button>
                                    </div>
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
    document.addEventListener('DOMContentLoaded', function () {
        // Inisialisasi Select2
        $('[data-control="select2"]').select2();

        var element = document.querySelector("#kt_stepper_ikh");
        var form = document.querySelector("#form_pendaftaran_ikh");

        var btnNext = form.querySelector('[data-kt-stepper-action="next"]');
        var btnPrev = form.querySelector('[data-kt-stepper-action="previous"]');
        var btnSubmit = form.querySelector('[data-kt-stepper-action="submit"]');

        var stepper = new KTStepper(element);
        var totalSteps = form.querySelectorAll('[data-kt-stepper-element="content"]').length;

        // Fungsi Validasi Custom
        function validateCurrentStep() {
            var currentIndex = stepper.getCurrentStepIndex() - 1; 
            var currentContent = form.querySelectorAll('[data-kt-stepper-element="content"]')[currentIndex];
            var requiredInputs = currentContent.querySelectorAll('[required]');
            var isValid = true;

            requiredInputs.forEach(function(input) {
                input.classList.remove('is-invalid');
                
                // Pengecekan aman untuk input teks, select, file, dan checkbox
                if (!input.value || input.value.trim() === '' || (input.type === 'checkbox' && !input.checked)) {
                    isValid = false;
                    input.classList.add('is-invalid');
                }
            });

            return isValid;
        }

        // FUNGSI UTAMA: Sinkronisasi Tampilan Kanan & Kiri
        function updateStepView() {
            var currentStep = stepper.getCurrentStepIndex();
            var currentIndex = currentStep - 1;

            // 1. PAKSA KONTEN KANAN BERUBAH (Solusi Bug Metronic)
            var contents = form.querySelectorAll('[data-kt-stepper-element="content"]');
            contents.forEach(function(content, index) {
                if (index === currentIndex) {
                    content.classList.add('current');
                    content.style.display = 'block'; // Tampilkan step aktif
                } else {
                    content.classList.remove('current');
                    content.style.display = 'none';  // Sembunyikan step lain
                }
            });

            // 2. Atur Tombol Navigasi
            if (currentStep === 1) {
                btnPrev.style.display = 'none';
            } else {
                btnPrev.style.display = 'inline-block';
            }

            if (currentStep === totalSteps) {
                btnNext.style.display = 'none';
                btnSubmit.style.display = 'inline-block';
            } else {
                btnNext.style.display = 'inline-block';
                btnSubmit.style.display = 'none';
            }
        }

        // Event Lanjut (Next)
        stepper.on("kt.stepper.next", function (stepper) {
            if (!validateCurrentStep()) {
                Swal.fire({
                    text: "Mohon lengkapi semua isian (atau file) yang diwajibkan berwarna merah pada langkah ini.",
                    icon: "warning",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, mengerti!",
                    customClass: { confirmButton: "btn btn-primary" }
                });
                return;
            }
            
            stepper.goNext(); 
            updateStepView(); // Panggil fungsi sinkronisasi
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Event Kembali (Previous)
        stepper.on("kt.stepper.previous", function (stepper) {
            stepper.goPrevious(); 
            updateStepView(); // Panggil fungsi sinkronisasi
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Event Submit Form
        stepper.on("kt.stepper.submit", function (stepper) {
            if (!validateCurrentStep()) {
                Swal.fire({
                    text: "Mohon centang semua kotak persetujuan sebelum mengirim formulir.",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Baiklah",
                    customClass: { confirmButton: "btn btn-danger" }
                });
                return;
            }

            // Animasi Loading
            btnSubmit.setAttribute('data-kt-indicator', 'on');
            btnSubmit.disabled = true;
            btnPrev.disabled = true;

            form.submit();
        });

        // Paksa sinkronisasi awal saat halaman pertama dimuat
        updateStepView();
    });
</script>
<?= $this->endSection(); ?>
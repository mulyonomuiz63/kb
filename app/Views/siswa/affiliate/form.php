<?= $this->extend('siswa/template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid py-4 py-lg-6 mt-8">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="card shadow-sm border-0">
                <div class="card-header border-0 pt-10">
                    <div class="card-title d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
                            <span class="svg-icon svg-icon-1 svg-icon-primary me-3">
                                <i class="ki-outline ki-credit-cart fs-2tx"></i>
                            </span>
                            <h3 class="fw-bold text-gray-900 m-0">
                                <?= isset($affiliate) ? 'Pembaruan Data Rekening' : 'Aktivasi Program Affiliate' ?>
                            </h3>
                        </div>
                        <span class="text-muted fw-semibold fs-6">
                            Pastikan data benar untuk kelancaran pencairan komisi otomatis ke rekening Anda.
                        </span>
                    </div>
                </div>

                <div class="card-body p-lg-15">
                    <form action="<?= base_url('sw-siswa/affiliate/save') ?>" method="post" id="kt_affiliate_form">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id_affiliate" value="<?= $affiliate['id_affiliate'] ?? '' ?>">
                        <input type="hidden" name="status" value="<?= $affiliate['status'] ?? '0' ?>">

                        <?php if (session()->getFlashdata('errors')) : ?>
                            <div class="alert alert-dismissible bg-light-danger d-flex flex-column flex-sm-row p-5 mb-10">
                                <i class="ki-outline ki-information-2 fs-2hx text-danger me-4 mb-5 mb-sm-0"></i>
                                <div class="d-flex flex-column pe-0 pe-sm-10">
                                    <h4 class="fw-bold">Terjadi Kesalahan</h4>
                                    <ul class="mb-0">
                                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                            <li><?= esc($error) ?></li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                    <i class="ki-outline ki-cross fs-1 text-danger"></i>
                                </button>
                            </div>
                        <?php endif; ?>

                        <?php if(isset($affiliate)): ?>
                            <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-10 p-6">
                                <i class="ki-outline ki-notification-on fs-2tx text-warning me-4"></i>
                                <div class="d-flex flex-stack flex-grow-1">
                                    <div class="fw-semibold">
                                        <h4 class="text-gray-900 fw-bold">Peringatan Penting!</h4>
                                        <div class="fs-6 text-gray-700">
                                            Pengubahan data hanya dapat dilakukan <b class="text-danger">maksimal 1 kali</b>. Mohon teliti sebelum menekan tombol simpan.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="row g-9 mb-8">
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Nama Bank</label>
                                <input type="text" name="bank" class="form-control form-control-solid <?= (session('errors.bank')) ? 'is-invalid' : '' ?>" 
                                       placeholder="Contoh: BCA, Mandiri, BRI" 
                                       value="<?= old('bank', $affiliate['bank'] ?? '') ?>" required />
                            </div>

                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Cabang Pembukaan</label>
                                <input type="text" name="cabang_bank" class="form-control form-control-solid <?= (session('errors.cabang_bank')) ? 'is-invalid' : '' ?>" 
                                       placeholder="Contoh: Jakarta Sudirman" 
                                       value="<?= old('cabang_bank', $affiliate['cabang_bank'] ?? '') ?>" required />
                            </div>

                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Nomor Rekening</label>
                                <input type="number" name="norek" class="form-control form-control-solid <?= (session('errors.norek')) ? 'is-invalid' : '' ?>" 
                                       placeholder="Hanya angka" 
                                       value="<?= old('norek', $affiliate['norek'] ?? '') ?>" required />
                            </div>

                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Nama Pemilik Rekening</label>
                                <input type="text" name="nama_akun_bank" class="form-control form-control-solid <?= (session('errors.nama_akun_bank')) ? 'is-invalid' : '' ?>" 
                                       placeholder="Harus sesuai dengan Buku Tabungan" 
                                       value="<?= old('nama_akun_bank', $affiliate['nama_akun_bank'] ?? '') ?>" required />
                            </div>
                        </div>

                        <div class="bg-light-primary rounded p-8 mb-10">
                            <div class="d-flex align-items-center mb-5">
                                <i class="ki-outline ki-shield-search fs-1 text-primary me-3"></i>
                                <h3 class="fw-bold text-gray-900 m-0">Syarat & Ketentuan Affiliate</h3>
                            </div>
                            <div class="text-gray-700 fs-6">
                                <ul class="list-unstyled">
                                    <li class="d-flex align-items-center mb-2">
                                        <i class="ki-outline ki-check fs-4 text-success me-2"></i> Data rekening harus valid dan milik pribadi peserta.
                                    </li>
                                    <li class="d-flex align-items-center mb-2">
                                        <i class="ki-outline ki-check fs-4 text-success me-2"></i> Komisi dihitung berdasarkan transaksi yang dinyatakan "Selesai".
                                    </li>
                                    <li class="d-flex align-items-center mb-2">
                                        <i class="ki-outline ki-check fs-4 text-success me-2"></i> Admin berhak melakukan verifikasi ulang jika ditemukan aktivitas mencurigakan.
                                    </li>
                                </ul>
                            </div>
                            <div class="form-check form-check-custom form-check-solid mt-6">
                                <input class="form-check-input" type="checkbox" id="agree" required />
                                <label class="form-check-label fw-bold text-gray-800" for="agree">
                                    Saya menyatakan data di atas benar dan menyetujui seluruh ketentuan program.
                                </label>
                            </div>
                        </div>

                        <div class="d-flex flex-stack pt-5">
                            <div class="me-2">
                                <a href="<?= base_url('sw-siswa/affiliate') ?>" class="btn btn-light fw-bold">
                                    <i class="ki-outline ki-arrow-left fs-4 me-1"></i> Kembali
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary fw-bold px-10 py-4">
                                    <span class="indicator-label">
                                        <?= isset($affiliate) ? 'Simpan Perubahan' : 'Aktivasi Sekarang' ?>
                                        <i class="ki-outline ki-right-square fs-3 ms-2"></i>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Styling khusus agar input number tidak memiliki spinner */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    /* Menyesuaikan container agar sesuai feel Metronic Demo 34 */
    #kt_app_content {
        padding: 30px 0;
    }

    .form-control-solid:focus {
        background-color: #f1f3f5;
        border-color: #009ef7;
    }
</style>

<?= $this->endSection(); ?>
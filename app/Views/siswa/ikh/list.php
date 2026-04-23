<?= $this->extend('siswa/template/app'); ?>

<?= $this->section('styles'); ?>
<style>
    .step-line {
        flex-grow: 1;
        height: 4px;
        background-color: #E4E6EF;
        margin: 0 15px;
        border-radius: 4px;
        position: relative;
        top: -15px;
    }

    .step-line.active {
        background-color: #009EF7;
    }

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
<?php
// 1. Cek apakah ada data di database
$hasData = !empty($ikh);
$idIkh   = $hasData ? $ikh['id_ikh'] : '';

// 2. Tentukan status dari database (Default: 'draft' jika baru simpan text)
$status_validasi = $hasData ? ($ikh['status_validasi_admin'] ?? 'draft') : 'draft';

// 3. Tentukan apakah user harus melihat Form atau melihat Monitoring
// User melihat Form JIKA belum ada data, ATAU data masih 'draft' (file belum lengkap), ATAU menekan tombol Edit
$isEditMode = isset($_GET['edit']) && $_GET['edit'] == 'true';
$showForm   = !$hasData || $status_validasi == 'draft' || $isEditMode;

// 4. Default Tab
$activeTab = isset($_GET['tab']) && $_GET['tab'] == 'lampiran' ? 'lampiran' : 'data';
?>

<div class="d-flex flex-column flex-column-fluid py-3 py-lg-6 mt-8">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <?php
            // Ambil catatan admin jika ada
            $catatan_admin = $hasData ? ($ikh['catatan_admin'] ?? 'Silakan perbaiki data atau lampiran Anda sesuai instruksi.') : '';
            ?>

            <?php if ($status_validasi == 'ditolak'): ?>
                <div class="notice d-flex bg-light-danger rounded border-danger border border-dashed p-6 mb-8">
                    <i class="ki-outline ki-information-5 text-danger fs-2tx me-4"></i>
                    <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                        <div class="mb-3 mb-md-0 fw-semibold w-100">
                            <h4 class="text-danger fw-bold">Pengajuan Dikembalikan / Ditolak!</h4>
                            <div class="fs-6 text-gray-700 pe-7 mb-3">
                                Mohon maaf, pengajuan Izin Kuasa Hukum Anda tidak dapat dilanjutkan karena ada data atau dokumen yang tidak sesuai. Silakan perbaiki bagian yang salah.
                            </div>

                            <div class="bg-body rounded p-4 border border-danger border-dashed w-100">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="ki-outline ki-message-text-2 text-danger fs-3 me-2"></i>
                                    <span class="fw-bold text-gray-800">Pesan dari Admin:</span>
                                </div>
                                <div class="text-danger fs-6 ps-7">
                                    "<?= esc($catatan_admin) ?>"
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6 mb-8">
                    <i class="ki-outline <?= $showForm ? 'ki-document' : 'ki-shield-tick' ?> text-primary fs-2tx me-4"></i>
                    <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                        <div class="mb-3 mb-md-0 fw-semibold">
                            <h4 class="text-gray-900 fw-bold">
                                <?= $showForm ? 'Pendaftaran Izin Kuasa Hukum (IKH)' : 'Monitoring Izin Kuasa Hukum (IKH)' ?>
                            </h4>
                            <div class="fs-6 text-gray-700 pe-7">
                                <?= $showForm
                                    ? 'Simpan Data Diri terlebih dahulu, kemudian unggah dokumen satu per satu untuk menghindari kegagalan jaringan.'
                                    : 'Pantau proses pengajuan IKH Anda secara berkala. Kartu IKH akan muncul setelah pengajuan disetujui.' ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($showForm): ?>

                <div class="card shadow-sm border-0 mb-8">
                    <div class="card-header card-header-stretch">

                        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-4 fw-bold w-100">

                            <li class="nav-item">
                                <a class="nav-link text-active-primary text-gray-600 px-4 <?= $activeTab == 'data' ? 'active' : '' ?>" data-bs-toggle="tab" href="#tab_data_diri">
                                    <i class="ki-outline ki-profile-user fs-2 me-2 <?= $activeTab == 'data' ? 'text-primary' : 'text-gray-500' ?>"></i>
                                    1. Data Diri & Pernyataan
                                </a>
                            </li>

                            <li class="nav-item">
                                <?php if ($hasData): ?>
                                    <a class="nav-link text-active-primary text-gray-600 px-4 <?= $activeTab == 'lampiran' ? 'active' : '' ?>" data-bs-toggle="tab" href="#tab_lampiran">
                                        <i class="ki-outline ki-document fs-2 me-2 <?= $activeTab == 'lampiran' ? 'text-primary' : 'text-gray-500' ?>"></i>
                                        2. Unggah Lampiran
                                    </a>
                                <?php else: ?>
                                    <a class="nav-link text-muted px-4 disabled bg-light" href="javascript:;" title="Simpan data diri terlebih dahulu">
                                        <i class="ki-outline ki-lock fs-2 me-2"></i>
                                        2. Unggah Lampiran <span class="badge badge-light-danger ms-2 fs-8">Terkunci</span>
                                    </a>
                                <?php endif; ?>
                            </li>

                        </ul>

                    </div>
                </div>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade <?= $activeTab == 'data' ? 'show active' : '' ?>" id="tab_data_diri" role="tabpanel">
                        <div class="card shadow-sm">
                            <div class="card-header align-items-center border-0 pt-6">

                                <div class="card-title">
                                    <h3 class="fw-bold m-0">Data Diri & Persyaratan</h3>
                                </div>
                                <?php if ($isEditMode): ?>
                                    <div class="card-toolbar">
                                        <a href="<?= base_url('sw-siswa/perijinan-ikh') ?>" class="btn btn-sm btn-light fw-bold hover-elevate-up">
                                            <i class="ki-outline ki-arrow-left fs-3"></i> Kembali
                                        </a>
                                    </div>
                                <?php endif; ?>

                            </div>
                            <form action="<?= base_url('sw-siswa/perijinan-ikh/store') ?>" method="POST" id="form_data_diri">
                                <?= csrf_field() ?>
                                <?php if ($hasData): ?>
                                    <input type="hidden" name="id_ikh" value="<?= $idIkh ?>">
                                <?php endif; ?>
                                <div class="card-body p-9">
                                    <div class="row g-5">
                                        <div class="col-md-6"><label class="required form-label">NIK</label><input type="number" name="nik" id="nik" class="form-control" value="<?= $hasData ? $ikh['nik'] : $siswa['nik'] ?>" required /></div>
                                        <div class="col-md-6"><label class="required form-label">NPWP</label><input type="number" name="npwp" id="npwp" class="form-control" value="<?= $hasData ? $ikh['npwp'] : '' ?>" required /></div>
                                        <div class="col-md-12"><label class="required form-label">Nama Lengkap</label><input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" value="<?= $hasData ? $ikh['nama_lengkap'] : $siswa['nama_siswa'] ?>" required /></div>
                                        <div class="col-md-6"><label class="required form-label">Tempat Lahir</label><input type="text" name="tempat_lahir" class="form-control" value="<?= $hasData ? $ikh['tempat_lahir'] : $siswa['tempat_lahir'] ?>" required /></div>
                                        <div class="col-md-6"><label class="required form-label">Tanggal Lahir</label><input type="date" id="kt_datepicker_lahir" name="tanggal_lahir" class="form-control" value="<?= $hasData ? $ikh['tanggal_lahir'] : $siswa['tgl_lahir'] ?>" required /></div>

                                        <div class="col-md-6">
                                            <label class="required form-label">Pendidikan Terakhir</label>
                                            <select name="pendidikan_terakhir" class="form-select" data-control="select2" required>
                                                <option></option>
                                                <?php $pend = $hasData ? $ikh['pendidikan_terakhir'] : ''; ?>
                                                <option value="D4" <?= $pend == 'D4' ? 'selected' : '' ?>>D4</option>
                                                <option value="S1" <?= $pend == 'S1' ? 'selected' : '' ?>>S1</option>
                                                <option value="S2" <?= $pend == 'S2' ? 'selected' : '' ?>>S2</option>
                                                <option value="S3" <?= $pend == 'S3' ? 'selected' : '' ?>>S3</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6"><label class="required form-label">Jurusan</label><input type="text" name="jurusan" class="form-control" value="<?= $hasData ? $ikh['jurusan'] : '' ?>" required /></div>
                                        <div class="col-md-6"><label class="required form-label">Tahun Masuk</label><input type="number" name="tahun_masuk" id="tahun_masuk" class="form-control" value="<?= $hasData ? $ikh['tahun_masuk'] : '' ?>" required /></div>
                                        <div class="col-md-6"><label class="required form-label">Tahun Lulus</label><input type="number" name="tahun_lulus" id="tahun_lulus" class="form-control" value="<?= $hasData ? $ikh['tahun_lulus'] : '' ?>" required /></div>
                                        <div class="col-md-6"><label class="required form-label">Nomor WhatsApp</label><input type="number" name="no_wa" id="no_wa" class="form-control" value="<?= $hasData ? $ikh['no_wa'] : $siswa['hp'] ?>" required /></div>
                                        <div class="col-md-6"><label class="required form-label">Email Aktif</label><input type="email" name="email" class="form-control" value="<?= $hasData ? $ikh['email'] : $siswa['email'] ?>" required /></div>
                                        <div class="col-md-6">
                                            <label class="required form-label">Kategori Kantor</label>
                                            <select name="kategori_kantor" class="form-select" data-control="select2" required>
                                                <option></option>
                                                <?php $kat = $hasData ? $ikh['kategori_kantor'] : $siswa['kantor']; ?>
                                                <option value="Firma Hukum" <?= $kat == 'Firma Hukum' ? 'selected' : '' ?>>Firma Hukum</option>
                                                <option value="KAP" <?= $kat == 'KAP' ? 'selected' : '' ?>>KAP</option>
                                                <option value="KKP" <?= $kat == 'KKP' ? 'selected' : '' ?>>KKP</option>
                                                <option value="Mandiri" <?= $kat == 'Mandiri' ? 'selected' : '' ?>>Mandiri</option>
                                                <option value="Lainnya" <?= $kat == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6"><label class="required form-label">Nama Kantor</label><input type="text" name="nama_kantor" class="form-control" value="<?= $hasData ? $ikh['nama_kantor'] : $siswa['nama_kantor'] ?>" required /></div>
                                        <div class="col-md-6"><label class="required form-label">Alamat Sesuai KTP</label><textarea name="alamat_ktp" class="form-control" rows="3" required><?= $hasData ? $ikh['alamat_ktp'] : $siswa['alamat_ktp'] ?></textarea></div>
                                        <div class="col-md-6"><label class="required form-label">Alamat Korespondensi</label><textarea name="alamat_korespondensi" class="form-control" rows="3" required><?= $hasData ? $ikh['alamat_korespondensi'] : '' ?></textarea></div>
                                    </div>
                                    <div class="separator my-10"></div>
                                    <h3 class="fw-bold fs-4 mb-5">Lembar Pernyataan (Di Siapkan Oleh Tim Legal)</h3>
                                    <div class="d-flex flex-column gap-4">
                                        <div class="form-check form-check-custom form-check-solid form-check-success p-3 rounded border border-gray-300">
                                            <input class="form-check-input" type="checkbox" name="check_riwayat" id="check_riwayat" <?= ($hasData && $ikh['is_riwayat_hidup']) ? 'checked' : 'checked' ?> required />
                                            <label class="form-check-label fw-bold cursor-pointer" for="check_riwayat">DAFTAR RIWAYAT HIDUP</label>
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid form-check-success p-3 rounded border border-gray-300">
                                            <input class="form-check-input" type="checkbox" name="check_pns" id="check_pns" <?= ($hasData && $ikh['is_bukan_pns']) ? 'checked' : 'checked' ?> required />
                                            <label class="form-check-label fw-bold cursor-pointer" for="check_pns">TIDAK BERSTATUS PNS</label>
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid form-check-success p-3 rounded border border-gray-300">
                                            <input class="form-check-input" type="checkbox" name="check_pakta" id="check_pakta" <?= ($hasData && $ikh['is_pakta_integritas']) ? 'checked' : 'checked' ?> required />
                                            <label class="form-check-label fw-bold cursor-pointer" for="check_pakta">PAKTA INTEGRITAS</label>
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid form-check-success p-3 rounded border border-gray-300">
                                            <input class="form-check-input" type="checkbox" name="check_pengajuan" id="check_pengajuan" <?= ($hasData && $ikh['is_pernyataan_ikh']) ? 'checked' : 'checked' ?> required />
                                            <label class="form-check-label fw-bold cursor-pointer" for="check_pengajuan">PERNYATAAN PENGAJUAN IKH</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ki-outline ki-save-2 fs-2"></i> Simpan Data Diri & Pernyataan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="tab-pane fade <?= $activeTab == 'lampiran' ? 'show active' : '' ?>" id="tab_lampiran" role="tabpanel">
                        <div class="card shadow-sm">
                            <div class="card-header align-items-center border-0 pt-6">

                                <div class="card-title">
                                    <h3 class="fw-bold m-0">Unggah Dokumen Lampiran</h3>
                                </div>
                                <?php if ($isEditMode): ?>
                                    <div class="card-toolbar">
                                        <a href="<?= base_url('sw-siswa/perijinan-ikh') ?>" class="btn btn-sm btn-light fw-bold hover-elevate-up">
                                            <i class="ki-outline ki-arrow-left fs-3"></i> Kembali
                                        </a>
                                    </div>
                                <?php endif; ?>

                            </div>
                            <div class="card-body p-9">
                                <?php if ($status_validasi == 'draft'): ?>
                                    <div class="alert alert-warning mb-8">
                                        <strong>Peringatan!</strong> Anda harus menyelesaikan proses upload untuk ke-9 berkas di bawah ini agar berkas otomatis dikirim ke Admin.
                                    </div>
                                <?php endif; ?>

                                <div class="row g-7">
                                    <?php
                                    $fileConfigs = [
                                        ['id' => 'file_ktp', 'label' => '1. KTP (Scan Asli)', 'accept' => '.jpg,.jpeg,.png', 'hint' => 'JPG/PNG'],
                                        ['id' => 'file_npwp', 'label' => '2. NPWP', 'accept' => '.jpg,.jpeg,.png', 'hint' => 'JPG/PNG'],
                                        ['id' => 'file_kk', 'label' => '3. Kartu Keluarga', 'accept' => '.jpg,.jpeg,.png', 'hint' => 'JPG/PNG'],
                                        ['id' => 'file_foto', 'label' => '4. Pas Foto 4x6', 'accept' => '.jpg,.jpeg,.png', 'hint' => 'JPG/PNG'],
                                        ['id' => 'file_skck', 'label' => '5. SKCK', 'accept' => '.pdf', 'hint' => 'Hanya PDF', 'danger' => true],
                                        ['id' => 'file_ijazah', 'label' => '6. IJAZAH (ASLI)', 'accept' => '.pdf', 'hint' => 'Hanya PDF', 'danger' => true],
                                        ['id' => 'file_spt', 'label' => '7. BUKTI TANDA TERIMA SPT 2 TAHUN TERAKHIR', 'accept' => '.pdf', 'hint' => 'Hanya PDF', 'danger' => true],
                                        ['id' => 'file_sertifikat', 'label' => '8. SERTIFIKAT BREVET PAJAK AB', 'accept' => '.pdf', 'hint' => 'Hanya PDF', 'danger' => true],
                                        ['id' => 'file_ttd', 'label' => '9. TTD ELEKTRONIK', 'accept' => '.pdf', 'hint' => 'Hanya PDF', 'danger' => true],
                                    ];
                                    ?>
                                    <?php foreach ($fileConfigs as $cfg):
                                        $isUploaded = $hasData && !empty($ikh[$cfg['id']]);

                                        // Siapkan URL dan Ekstensi jika file sudah terunggah
                                        $fileUrl = '';
                                        $fileExt = '';
                                        if ($isUploaded) {
                                            $fileUrl = base_url('uploads/ikh/' . $ikh[$cfg['id']]);
                                            $fileExt = strtolower(pathinfo($ikh[$cfg['id']], PATHINFO_EXTENSION));
                                        }
                                    ?>
                                        <div class="col-md-6">
                                            <div class="border rounded p-5 <?= $isUploaded ? 'border-success bg-light-success' : 'border-gray-300' ?>" id="box_<?= $cfg['id'] ?>">

                                                <div class="d-flex justify-content-between align-items-center mb-4">
                                                    <label class="fw-bold fs-5 text-gray-800 <?= isset($cfg['danger']) ? 'text-danger' : '' ?>"><?= $cfg['label'] ?></label>

                                                    <div class="d-flex align-items-center gap-2">

                                                        <?php if ($isUploaded): ?>
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-icon btn-sm btn-light-primary hover-elevate-up btn-preview-berkas"
                                                                data-file-url="<?= $fileUrl ?>"
                                                                data-file-ext="<?= $fileExt ?>"
                                                                data-file-name="<?= $cfg['label'] ?>"
                                                                title="Lihat dokumen tersimpan">
                                                                <i class="ki-outline ki-eye fs-3"></i>
                                                            </a>
                                                        <?php endif; ?>

                                                        <span class="badge status-badge <?= $isUploaded ? 'badge-success' : 'badge-light-danger' ?>" id="status_<?= $cfg['id'] ?>">
                                                            <?= $isUploaded ? '<i class="ki-outline ki-check text-white fs-5 me-1"></i> Tersimpan' : 'Belum Upload' ?>
                                                        </span>

                                                    </div>
                                                </div>

                                                <div class="input-group input-group-sm">
                                                    <input type="file" class="form-control file-input-ajax" id="input_<?= $cfg['id'] ?>" data-name="<?= $cfg['id'] ?>" accept="<?= $cfg['accept'] ?>">
                                                    <button class="btn btn-primary btn-upload-ajax" type="button" data-target="<?= $cfg['id'] ?>">
                                                        <span class="indicator-label">Upload</span>
                                                        <span class="indicator-progress" style="display:none;">... <span class="spinner-border spinner-border-sm align-middle"></span></span>
                                                    </button>
                                                </div>
                                                <div class="form-text mt-2 <?= isset($cfg['danger']) ? 'text-danger fw-bold' : '' ?>">Format: <?= $cfg['hint'] ?> (Maks 2MB)</div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <?php
                // 1. Ambil Semua Status Langsung dari Database
                $stat_val = $ikh['status_validasi_admin'] ?? 'draft';
                $stat_pro = $ikh['status_proses'] ?? 'pending';
                $stat_fin = $ikh['status_final'] ?? 'pending';
                $stat_ser = $ikh['status_sertifikat'] ?? 'belum';

                // 2. Pemetaan Logika STEP 1 (Pendaftaran & Validasi)
                $step1_done    = ($stat_val === 'valid');

                // 3. Pemetaan Logika STEP 2 (Proses)
                $step2_done    = ($stat_pro === 'selesai');
                $step2_active  = ($stat_pro === 'proses');

                // 4. Pemetaan Logika STEP 3 (Final)
                $step3_done    = ($stat_fin === 'selesai');
                $step3_active  = ($stat_fin === 'proses');

                // 5. Pemetaan Logika STEP 4 (Sertifikat)
                $step4_done    = ($stat_ser === 'terbit');
                $step4_active  = ($step3_done && !$step4_done); // Otomatis aktif jika step 3 selesai tapi sertifikat belum terbit

                // 6. Kunci Tombol Edit (Hanya boleh edit jika draft, pending, atau ditolak)
                $canEdit = in_array($stat_val, ['draft', 'pending', 'ditolak']);
                ?>
                <?php if ($stat_ser != 'terbit'): ?>
                    <div class="card shadow-sm mb-8">
                        <div class="card-body pt-10 pb-10">

                            <div class="d-flex flex-center flex-nowrap w-100 stepper-mobile-scroll">

                                <div class="d-flex flex-column align-items-center position-relative w-150px step-item-mobile">
                                    <div class="symbol symbol-50px symbol-circle mb-3">
                                        <span class="symbol-label bg-primary text-inverse-primary fs-2 fw-bold">
                                            <i class="ki-outline ki-check fs-1 text-white"></i>
                                        </span>
                                    </div>
                                    <span class="fw-bold text-gray-800 text-center">Pendaftaran</span>
                                    <?php if ($stat_val == 'pending'): ?>
                                        <span class="badge badge-light-primary mt-2 animate-pulse">Menunggu Validasi</span>
                                    <?php elseif ($stat_val == 'ditolak'): ?>
                                        <span class="badge badge-light-danger mt-2">Ditolak</span>
                                    <?php endif; ?>
                                </div>

                                <div class="step-line <?= $step1_done ? 'active' : '' ?>"></div>

                                <div class="d-flex flex-column align-items-center position-relative w-150px step-item-mobile <?= (!$step1_done) ? 'opacity-50' : '' ?>">
                                    <div class="symbol symbol-50px symbol-circle mb-3 <?= $step2_done ? '' : ($step2_active ? 'border border-primary border-dashed' : 'bg-light') ?>">
                                        <span class="symbol-label <?= $step2_done ? 'bg-primary text-white' : ($step2_active ? 'bg-light-primary text-primary' : 'text-muted') ?> fs-2 fw-bold">
                                            <?= $step2_done ? '<i class="ki-outline ki-check fs-1 text-white"></i>' : '2' ?>
                                        </span>
                                    </div>
                                    <span class="fw-bold <?= $step2_done ? 'text-gray-800' : ($step2_active ? 'text-primary' : 'text-gray-600') ?> text-center">Validasi Berkas</span>
                                    <?php if ($step2_active): ?>
                                        <span class="badge badge-light-primary mt-2 animate-pulse">In Progress</span>
                                    <?php endif; ?>
                                </div>

                                <div class="step-line <?= $step2_done ? 'active' : '' ?>"></div>

                                <div class="d-flex flex-column align-items-center position-relative w-150px step-item-mobile <?= (!$step2_done) ? 'opacity-50' : '' ?>">
                                    <div class="symbol symbol-50px symbol-circle mb-3 <?= $step3_done ? '' : ($step3_active ? 'border border-primary border-dashed' : 'bg-light') ?>">
                                        <span class="symbol-label <?= $step3_done ? 'bg-primary text-white' : ($step3_active ? 'bg-light-primary text-primary' : 'text-muted') ?> fs-2 fw-bold">
                                            <?= $step3_done ? '<i class="ki-outline ki-check fs-1 text-white"></i>' : '3' ?>
                                        </span>
                                    </div>
                                    <span class="fw-bold <?= $step3_done ? 'text-gray-800' : ($step3_active ? 'text-primary' : 'text-gray-600') ?> text-center">Tahap Final</span>
                                    <?php if ($step3_active): ?>
                                        <span class="badge badge-light-primary mt-2 animate-pulse">In Progress</span>
                                    <?php endif; ?>
                                </div>

                                <div class="step-line <?= $step3_done ? 'active' : '' ?>"></div>

                                <div class="d-flex flex-column align-items-center position-relative w-150px step-item-mobile <?= (!$step4_done) ? 'opacity-50' : '' ?>">
                                    <div class="symbol symbol-50px symbol-circle mb-3 <?= $step4_done ? '' : ($step4_active ? 'border border-primary border-dashed' : 'bg-light') ?>">
                                        <span class="symbol-label <?= $step4_done ? 'bg-primary text-white' : ($step4_active ? 'bg-light-primary text-primary' : 'text-muted') ?> fs-2 fw-bold">
                                            <?= $step4_done ? '<i class="ki-outline ki-award fs-1 text-white"></i>' : '4' ?>
                                        </span>
                                    </div>
                                    <span class="fw-bold <?= $step4_done ? 'text-gray-800' : ($step4_active ? 'text-primary' : 'text-gray-600') ?> text-center">Kartu IKH Terbit</span>
                                    <?php if ($step4_done): ?>
                                        <span class="badge badge-light-primary mt-2">Selesai</span>
                                    <?php elseif ($step4_active): ?>
                                        <span class="badge badge-light-primary mt-2 animate-pulse">Penerbitan</span>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="row g-5 g-xxl-8">
                        <div class="col-xl-7">
                            <div class="card shadow-sm h-100">
                                <div class="card-header border-0 pt-6">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bold fs-3 mb-1">Formulir Permohonan</span>
                                        <span class="text-muted mt-1 fw-semibold fs-7">Data diri sesuai pengajuan terakhir</span>
                                    </h3>
                                    <div class="card-toolbar d-flex flex-wrap gap-2">
                                        <?php if ($canEdit): ?>

                                            <?php if ($stat_val == 'ditolak'): ?>
                                                <a href="javascript:void(0)" data-url="<?= base_url('sw-siswa/perijinan-ikh/perbaikan/' . encrypt_url($ikh['id_ikh'])) ?>" class="btn btn-sm btn-danger fw-bold hover-elevate-up btn-perbaikan" title="Kirim perbaikan data">
                                                    <i class="ki-outline ki-arrows-circle fs-3"></i>
                                                    <span class="d-none d-sm-inline">Kirim Perbaikan</span>
                                                    <span class="d-inline d-sm-none">Perbaiki</span>
                                                </a>
                                                <a href="?edit=true#tab_data_diri" class="btn btn-sm btn-light-primary fw-bold hover-elevate-up">
                                                    <i class="ki-outline ki-pencil fs-3"></i>
                                                    <span class="d-none d-sm-inline">Edit Data</span>
                                                    <span class="d-inline d-sm-none">Edit</span>
                                                </a>
                                            <?php endif; ?>

                                        <?php else: ?>
                                            <span class="badge badge-light-success px-3 py-2 text-wrap text-center">
                                                <i class="ki-outline ki-shield-tick fs-4 text-success me-1"></i>
                                                <span class="d-none d-sm-inline">Data tidak dapat diubah</span>
                                                <span class="d-inline d-sm-none">Terkunci</span>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="card-body pt-5">
                                    <div class="row mb-5">
                                        <div class="col-lg-5 fw-semibold text-muted">Nomor Induk Kependudukan</div>
                                        <div class="col-lg-7"><span class="fw-bold fs-6 text-gray-800"><?= $ikh['nik'] ?></span></div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-lg-5 fw-semibold text-muted">Nomor Pokok Wajib Pajak</div>
                                        <div class="col-lg-7"><span class="fw-bold fs-6 text-gray-800"><?= $ikh['npwp'] ?></span></div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-lg-5 fw-semibold text-muted">Nama Lengkap</div>
                                        <div class="col-lg-7"><span class="fw-bold fs-6 text-gray-800"><?= $ikh['nama_lengkap'] ?></span></div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-lg-5 fw-semibold text-muted">Tempat, Tanggal Lahir</div>
                                        <div class="col-lg-7"><span class="fw-bold fs-6 text-gray-800"><?= $ikh['tempat_lahir'] . ', ' . date('d F Y', strtotime($ikh['tanggal_lahir'])) ?></span></div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-lg-5 fw-semibold text-muted">Pendidikan Terakhir</div>
                                        <div class="col-lg-7"><span class="fw-bold fs-6 text-gray-800"><?= $ikh['pendidikan_terakhir'] ?></span></div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-lg-5 fw-semibold text-muted">Jurusan</div>
                                        <div class="col-lg-7"><span class="fw-bold fs-6 text-gray-800"><?= $ikh['jurusan'] ?></span></div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-lg-5 fw-semibold text-muted">Tahun Masuk & Lulus</div>
                                        <div class="col-lg-7"><span class="fw-bold fs-6 text-gray-800"><?= $ikh['tahun_masuk'] ?> - <?= $ikh['tahun_lulus'] ?></span></div>
                                    </div>
                                    <div class="separator my-5"></div>
                                    <div class="row mb-5">
                                        <div class="col-lg-5 fw-semibold text-muted">Alamat KTP</div>
                                        <div class="col-lg-7"><span class="fw-semibold text-gray-800"><?= $ikh['alamat_ktp'] ?></span></div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-lg-5 fw-semibold text-muted">Alamat Korespondensi</div>
                                        <div class="col-lg-7"><span class="fw-semibold text-gray-800"><?= $ikh['alamat_korespondensi'] ?></span></div>
                                    </div>
                                    <div class="separator my-5"></div>
                                    <div class="row mb-5">
                                        <div class="col-lg-5 fw-semibold text-muted">Kontak Dihubungi</div>
                                        <div class="col-lg-7 d-flex align-items-center"><span class="fw-bold fs-6 text-gray-800 me-4"><?= $ikh['no_wa'] ?></span><span class="badge badge-light-success">WhatsApp</span></div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-lg-5 fw-semibold text-muted">Email</div>
                                        <div class="col-lg-7"><span class="fw-bold fs-6 text-gray-800"><?= $ikh['email'] ?></span></div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-lg-5 fw-semibold text-muted">Kantor & Nama Kantor</div>
                                        <div class="col-lg-7"><span class="fw-bold fs-6 text-gray-800"><?= $ikh['kategori_kantor'] . ' - ' . $ikh['nama_kantor'] ?></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-5">
                            <div class="card shadow-sm h-100">
                                <div class="card-header border-0 pt-6">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bold fs-3 mb-1">Status Lampiran</span>
                                        <span class="text-muted mt-1 fw-semibold fs-7">Verifikasi berkas persyaratan</span>
                                    </h3>
                                </div>
                                <div class="card-body pt-5">
                                    <div class="mh-400px scroll-y px-2">
                                        <?php
                                        $berkasList = [
                                            ['nama' => '1. KTP (Scan Asli)', 'field' => 'file_ktp'],
                                            ['nama' => '2. NPWP', 'field' => 'file_npwp'],
                                            ['nama' => '3. Kartu Keluarga', 'field' => 'file_kk'],
                                            ['nama' => '4. Pas Foto 4x6', 'field' => 'file_foto'],
                                            ['nama' => '5. SKCK', 'field' => 'file_skck'],
                                            ['nama' => '6. Ijazah (Scan Asli)', 'field' => 'file_ijazah'],
                                            ['nama' => '7. Bukti Terima SPT', 'field' => 'file_spt'],
                                            ['nama' => '8. Sertifikat Brevet Pajak', 'field' => 'file_sertifikat'],
                                            ['nama' => '9. TTD Elektronik', 'field' => 'file_ttd'],
                                        ];

                                        foreach ($berkasList as $berkas):
                                            $isUploaded = !empty($ikh[$berkas['field']]);

                                            // Tentukan URL file dan ekstensi (Jika sudah diupload)
                                            $fileUrl = '';
                                            $fileExt = '';
                                            if ($isUploaded) {
                                                $fileUrl = base_url('uploads/ikh/' . $ikh[$berkas['field']]);
                                                $fileExt = strtolower(pathinfo($ikh[$berkas['field']], PATHINFO_EXTENSION));
                                            }
                                        ?>
                                            <div class="d-flex align-items-center mb-5">
                                                <div class="symbol symbol-40px me-4">
                                                    <?php if ($isUploaded): ?>
                                                        <a href="javascript:void(0)"
                                                            class="symbol-label bg-light-primary text-primary hover-elevate-up btn-preview-berkas"
                                                            data-file-url="<?= $fileUrl ?>"
                                                            data-file-ext="<?= $fileExt ?>"
                                                            data-file-name="<?= $berkas['nama'] ?>"
                                                            title="Klik untuk melihat dokumen">
                                                            <i class="ki-outline ki-eye fs-2 text-primary"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <div class="symbol-label bg-light-danger">
                                                            <i class="ki-outline ki-file fs-2 text-danger"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="d-flex flex-column flex-grow-1">
                                                    <?php if ($isUploaded): ?>
                                                        <a href="javascript:void(0)"
                                                            class="fs-6 fw-bold text-gray-800 text-hover-primary mb-1 btn-preview-berkas"
                                                            data-file-url="<?= $fileUrl ?>"
                                                            data-file-ext="<?= $fileExt ?>"
                                                            data-file-name="<?= $berkas['nama'] ?>">
                                                            <?= $berkas['nama'] ?>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="fs-6 fw-bold text-gray-800 mb-1"><?= $berkas['nama'] ?></span>
                                                    <?php endif; ?>

                                                    <span class="text-muted fw-semibold fs-8"><?= $isUploaded ? 'Telah diunggah (Klik untuk lihat)' : 'File belum ada' ?></span>
                                                </div>

                                                <div>
                                                    <?php if ($isUploaded): ?>
                                                        <span class="badge badge-light-success fw-bold px-3 py-2"><i class="ki-outline ki-check-circle fs-5 text-success me-1"></i> Valid</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-light-danger fw-bold px-3 py-2"><i class="ki-outline ki-cross-circle fs-5 text-danger me-1"></i> Kosong</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card shadow-sm mb-8 border-top border-success border-3">
                        <div class="card-header bg-light-success border-0 pt-6">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-success fs-3 mb-1">Selamat! Kartu IKH Anda Telah Terbit</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">Proses verifikasi selesai. Anda dapat mengunduh e-Kartu IKH di bawah ini.</span>
                            </h3>
                        </div>
                        <div class="card-body pt-7 pb-10">

                            <?php if (!empty($ikh['file_kartu_ikh'])):
                                $fileUrl = base_url('uploads/ikh/' . $ikh['file_kartu_ikh']);
                                $fileExt = strtolower(pathinfo($ikh['file_kartu_ikh'], PATHINFO_EXTENSION));
                            ?>

                                <div class="d-flex flex-column flex-md-row align-items-center bg-body border border-success border-dashed rounded p-8">

                                    <div class="symbol symbol-100px mb-7 mb-md-0 me-md-10">
                                        <div class="symbol-label bg-light-success">
                                            <i class="ki-outline ki-award fs-5x text-success"></i>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column flex-grow-1 text-center text-md-start mb-7 mb-md-0">
                                        <h2 class="text-gray-900 fw-bolder mb-2">E-KARTU IZIN KUASA HUKUM</h2>
                                        <div class="d-flex justify-content-center justify-content-md-start align-items-center mb-1">
                                            <span class="text-gray-600 fw-semibold fs-5 me-2">Masa Berlaku hingga:</span>
                                            <span class="badge badge-light-danger fw-bold fs-6">
                                                <?= !empty($ikh['tgl_exp']) ? date('d F Y', strtotime($ikh['tgl_exp'])) : 'Tidak Terbatas' ?>
                                            </span>
                                        </div>
                                        <span class="text-muted fw-semibold fs-7 text-uppercase"><i class="ki-outline ki-file-check fs-6 me-1"></i> Format Dokumen: <?= $fileExt ?></span>
                                    </div>

                                    <div class="d-flex flex-column flex-sm-row gap-3">
                                        <a href="javascript:void(0)"
                                            class="btn btn-outline btn-outline-success btn-active-light-success btn-preview-berkas w-100 w-sm-auto"
                                            data-file-url="<?= $fileUrl ?>"
                                            data-file-ext="<?= $fileExt ?>"
                                            data-file-name="Kartu IKH - <?= $ikh['nama_lengkap'] ?>"
                                            title="Lihat Pratinjau">
                                            <i class="ki-outline ki-eye fs-2"></i> Pratinjau
                                        </a>

                                        <a href="<?= $fileUrl ?>" target="_blank" download="Kartu_IKH_<?= str_replace(' ', '_', $ikh['nama_lengkap']) ?>.<?= $fileExt ?>" class="btn btn-success w-100 w-sm-auto shadow-sm">
                                            <i class="ki-outline ki-file-down fs-2"></i> Unduh Kartu
                                        </a>
                                    </div>

                                </div>
                            <?php else: ?>

                                <div class="alert alert-warning d-flex align-items-center p-5 mb-0">
                                    <i class="ki-outline ki-information-5 fs-2hx text-warning me-4"></i>
                                    <div class="d-flex flex-column">
                                        <h4 class="fw-semibold text-warning mb-1">Sedang Dalam Proses Cetak</h4>
                                        <span>Status Anda sudah selesai, namun file E-Kartu IKH sedang dalam proses unggah oleh Admin. Mohon cek kembali secara berkala.</span>
                                    </div>
                                </div>

                            <?php endif; ?>

                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        </div>
    </div>
</div>

<div class="modal fade" id="modal_preview_berkas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content shadow-none">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_preview_title">Pratinjau Dokumen</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>

            <div class="modal-body p-0" style="background-color: #f5f8fa; min-height: 500px;">
                <div id="preview_container" class="d-flex justify-content-center align-items-center w-100 h-100 p-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Memuat...</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <a href="#" id="btn_download_berkas" class="btn btn-primary" download>
                    <i class="ki-outline ki-file-down fs-2"></i> Unduh Dokumen
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    // Injeksi CSS Animasi
    $('<style>@keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } } .animate-pulse { animation: pulse 2s infinite; }</style>').appendTo('head');

    $(document).ready(function() {

        // ==========================================================
        // 1. INISIALISASI PLUGIN & BATASAN INPUT
        // ==========================================================
        // Cek apakah elemen ada sebelum mengaktifkan flatpickr agar tidak error
        if ($("#kt_datepicker_lahir").length) {
            $("#kt_datepicker_lahir").flatpickr({
                altInput: true,
                altFormat: "d-m-Y",
                dateFormat: "Y-m-d",
                maxDate: "today",
                locale: "id"
            });
        }

        // Pembatas Karakter menggunakan jQuery (Anti-Error meskipun elemen tidak ada di halaman)
        $('#nik, #npwp').on('input', function() { if (this.value.length > 16) this.value = this.value.slice(0, 16); });
        $('#nama_lengkap').on('input', function() { if (this.value.length > 60) this.value = this.value.slice(0, 60); });
        $('#no_wa').on('input', function() { if (this.value.length > 15) this.value = this.value.slice(0, 15); });
        $('#tahun_masuk, #tahun_lulus').on('input', function() { if (this.value.length > 4) this.value = this.value.slice(0, 4); });


        <?php if ($showForm): ?>
        // ==========================================================
        // 2. SCRIPT AJAX UPLOAD 
        // ==========================================================
        $('.btn-upload-ajax').click(function() {
            const idIkh = '<?= $idIkh ?>';
            let csrfHash = '<?= csrf_hash() ?>';
            const csrfName = '<?= csrf_token() ?>';
            let targetId = $(this).data('target');
            let fileInput = $('#input_' + targetId)[0];
            let btn = $(this);

            if (fileInput.files.length === 0) {
                toastr.warning("Silakan pilih file terlebih dahulu.");
                return;
            }

            let fileData = fileInput.files[0];
            const maxSizeInBytes = 2 * 1024 * 1024; // 2 MB
            
            if (fileData.size > maxSizeInBytes) {
                Swal.fire({
                    text: "Ukuran file " + fileData.name + " terlalu besar! Maksimal hanya 2 MB.",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Baiklah",
                    customClass: { confirmButton: "btn btn-danger" }
                });
                $(fileInput).val('');
                return;
            }

            let formData = new FormData();
            formData.append('file_dokumen', fileData);
            formData.append('input_name', targetId);
            formData.append('id_ikh', idIkh);
            formData.append(csrfName, csrfHash);

            btn.find('.indicator-label').hide();
            btn.find('.indicator-progress').show();
            btn.prop('disabled', true);

            $.ajax({
                url: '<?= base_url('sw-siswa/perijinan-ikh/upload-ajax') ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.csrf_hash) csrfHash = response.csrf_hash;

                    if (response.success) {
                        toastr.success(response.message);

                        $('#box_' + targetId).removeClass('border-gray-300').addClass('border-success bg-light-success');
                        $('#status_' + targetId).removeClass('badge-light-danger').addClass('badge-success')
                            .html('<i class="ki-outline ki-check text-white fs-5 me-1"></i> Tersimpan');

                        // --- UPDATE TOMBOL PREVIEW DINAMIS ---
                        let localFileUrl = URL.createObjectURL(fileData);
                        let newFileExt = fileData.name.split('.').pop().toLowerCase();
                        let fileNameLabel = $('#box_' + targetId).find('label.fs-5').text().trim();

                        let previewBtn = $('#box_' + targetId).find('.btn-preview-berkas');

                        if (previewBtn.length > 0) {
                            previewBtn.attr('data-file-url', localFileUrl);
                            previewBtn.attr('data-file-ext', newFileExt);
                        } else {
                            let newPreviewBtn = `
                                <a href="javascript:void(0)" 
                                   class="btn btn-icon btn-sm btn-light-primary hover-elevate-up btn-preview-berkas me-2" 
                                   data-file-url="${localFileUrl}" 
                                   data-file-ext="${newFileExt}" 
                                   data-file-name="${fileNameLabel}" 
                                   title="Lihat dokumen tersimpan">
                                    <i class="ki-outline ki-eye fs-3"></i>
                                </a>
                            `;
                            $('#status_' + targetId).before(newPreviewBtn);
                        }

                        $(fileInput).val('');

                        if (response.is_complete) {
                            Swal.fire({
                                text: "Berhasil! Semua file persyaratan telah berhasil diunggah. Kami akan mengalihkan Anda ke halaman Monitoring.",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 3000
                            }).then(() => {
                                window.location.href = '<?= base_url('sw-siswa/perijinan-ikh') ?>';
                            });
                        }
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error("Terjadi kesalahan jaringan atau ekstensi tidak didukung.");
                },
                complete: function() {
                    btn.find('.indicator-label').show();
                    btn.find('.indicator-progress').hide();
                    btn.prop('disabled', false);
                }
            });
        });
        <?php endif; ?>


        // ==========================================================
        // 3. SCRIPT MODAL PRATINJAU (TERPISAH & ANTI-GAGAL)
        // ==========================================================
        $('body').on('click', '.btn-preview-berkas', function(e) {
            e.preventDefault();

            let fileUrl = $(this).attr('data-file-url');
            let fileExt = $(this).attr('data-file-ext');
            let fileName = $(this).attr('data-file-name');

            if (!fileUrl) {
                toastr.error("Gagal memuat pratinjau. Silakan refresh halaman.");
                return;
            }

            $('#modal_preview_title').text(fileName);
            $('#btn_download_berkas').attr('href', fileUrl).attr('download', fileName + '.' + fileExt);
            $('#preview_container').html('<div class="spinner-border text-primary" role="status"></div>');

            let modalElement = document.getElementById('modal_preview_berkas');
            let myModal = bootstrap.Modal.getInstance(modalElement);
            if (!myModal) {
                myModal = new bootstrap.Modal(modalElement);
            }
            myModal.show();

            setTimeout(() => {
                if (fileExt === 'pdf') {
                    $('#preview_container').html('<embed src="' + fileUrl + '" type="application/pdf" width="100%" height="700px" />');
                } else if (['jpg', 'jpeg', 'png'].includes(fileExt)) {
                    $('#preview_container').html('<img src="' + fileUrl + '" class="img-fluid rounded shadow-sm" style="max-height: 700px; object-fit: contain;" />');
                } else {
                    $('#preview_container').html('<div class="text-center text-muted"><i class="ki-outline ki-file fs-5x mb-3"></i><br>Pratinjau tidak tersedia untuk format ini.</div>');
                }
            }, 500);
        });

        $('body').on('hidden.bs.modal', '#modal_preview_berkas', function() {
            $('#preview_container').empty();
            $('.modal-backdrop').remove();
            $('body').css('overflow', '');
        });


        // ==========================================================
        // 4. SCRIPT POPUP PERBAIKAN
        // ==========================================================
        $('body').on('click', '.btn-perbaikan', function(e) {
            e.preventDefault();
            const targetUrl = $(this).attr('data-url');

            if (!targetUrl) {
                console.error("URL tujuan tidak ditemukan!");
                return;
            }

            Swal.fire({
                title: "Kirim Ulang Berkas?",
                text: "Apakah Anda yakin semua data dan dokumen perbaikan sudah benar? Berkas Anda akan masuk kembali ke antrean admin untuk divalidasi ulang.",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Ya, Kirim Sekarang!",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: "btn btn-light-primary"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        text: "Meneruskan perbaikan Anda...",
                        icon: "info",
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    window.location.href = targetUrl;
                }
            });
        });

    });
</script>
<?= $this->endSection(); ?>
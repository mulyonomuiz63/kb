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
// 1. Cek apakah ada data di database (Pastikan id_ikh benar-benar ada nilainya)
$hasData = (!empty($ikh) && !empty($ikh['id_ikh']));
$idIkh   = $hasData ? $ikh['id_ikh'] : '';

// 2. Tentukan status dari database (Default: 'draft' jika baru simpan text)
$status_validasi = $hasData ? ($ikh['status_validasi_admin'] ?? 'draft') : 'draft';

// 3. Tentukan apakah user harus melihat Form atau melihat Monitoring
$isEditMode = isset($_GET['edit']) && $_GET['edit'] == 'true';
$showForm = (
    !$hasData ||
    $status_validasi === 'draft' ||
    $isEditMode
) && (isset($ikh['kuota']) && (int)$ikh['kuota'] > 0);

// 4. Default Tab (Hanya boleh buka lampiran jika data Step 1 benar-benar tersimpan)
$activeTab = (isset($_GET['tab']) && $_GET['tab'] == 'lampiran' && $hasData) ? 'lampiran' : 'data';
?>

<div class="d-flex flex-column flex-column-fluid py-3 py-lg-6 mt-8">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <?php $catatan_admin = $hasData ? ($ikh['catatan_admin'] ?? 'Silakan perbaiki data atau lampiran Anda sesuai instruksi.') : ''; ?>

            <?php if ($status_validasi == 'ditolak'): ?>
                <div class="notice d-flex bg-light-danger rounded border-danger border border-dashed p-6 mb-8">
                    <i class="ki-outline ki-information-5 text-danger fs-2tx me-4"></i>
                    <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                        <div class="mb-3 mb-md-0 fw-semibold w-100">
                            <h4 class="text-danger fw-bold">Perbaikan Persyaratan</h4>
                            <div class="fs-6 text-gray-700 pe-7 mb-3">
                                Mohon maaf, pengajuan Izin Kuasa Hukum Anda tidak dapat dilanjutkan karena ada data atau dokumen yang tidak sesuai. Silakan perbaiki bagian yang salah.
                            </div>
                            <div class="bg-body rounded p-4 border border-danger border-dashed w-100">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="ki-outline ki-message-text-2 text-danger fs-3 me-2"></i>
                                    <span class="fw-bold text-gray-800">Pesan dari Admin:</span>
                                </div>
                                <div class="text-danger fs-6 ps-7">"<?= esc($catatan_admin) ?>"</div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif ($status_validasi == 'revisi'): ?>
                <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6 mb-8">
                    <i class="ki-outline ki-arrows-loop text-warning fs-2tx me-4"></i>
                    <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                        <div class="mb-3 mb-md-0 fw-semibold w-100">
                            <h4 class="text-warning fw-bold">Perpanjangan Izin Kuasa Hukum</h4>
                            <div class="fs-6 text-gray-700 pe-7 mb-3">
                                Masa berlaku Kartu IKH Anda sudah habis atau akan segera berakhir. Silakan perbarui data diri dan unggah dokumen persyaratan terbaru untuk mengajukan perpanjangan.
                            </div>
                            <?php if (!empty($catatan_admin) && $catatan_admin !== 'Silakan perbaiki data atau lampiran Anda sesuai instruksi.'): ?>
                                <div class="bg-body rounded p-4 border border-warning border-dashed w-100">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="ki-outline ki-message-text-2 text-warning fs-3 me-2"></i>
                                        <span class="fw-bold text-gray-800">Pesan dari Admin:</span>
                                    </div>
                                    <div class="text-warning fs-6 ps-7">"<?= esc($catatan_admin) ?>"</div>
                                </div>
                            <?php endif; ?>
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
                                    : 'Pantau proses pengajuan Izin Kuasa Hukum Anda secara berkala. Kartu Izin Kuasa Hukum akan muncul setelah pengajuan disetujui.' ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php
            $today = strtotime(date('Y-m-d'));
            $expiredDate = !empty($ikh['tgl_exp']) ? strtotime($ikh['tgl_exp']) : 0;
            $files = json_decode($ikh['file_kartu_ikh'] ?? '', true);
            $hasFile = (!empty($files) && is_array($files)) ? true : false;

            if ($expiredDate >= $today && $hasFile && $status_validasi !== 'revisi'):
            ?>
                <div class="card shadow-sm mb-8 border-top border-success border-3">
                    <div class="card-header bg-light-success border-0 pt-6">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-success fs-3 mb-1">Selamat! Kartu Izin Kuasa Hukum Anda Telah Terbit</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">Proses verifikasi selesai. Anda dapat mengunduh Kartu Izin Kuasa Hukum di bawah ini.</span>
                        </h3>
                    </div>
                    <div class="card-body pt-7 pb-10">

                        <?php if (!empty($ikh['file_kartu_ikh'])): ?>
                            <div class="bg-body border border-success border-dashed rounded p-8">
                                <div class="d-flex flex-column flex-md-row align-items-center mb-8">
                                    <div class="symbol symbol-100px mb-7 mb-md-0 me-md-10">
                                        <div class="symbol-label bg-light-success">
                                            <i class="ki-outline ki-award fs-5x text-success"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column flex-grow-1 text-center text-md-start">
                                        <h2 class="text-gray-900 fw-bolder mb-2">E-KARTU IZIN KUASA HUKUM</h2>
                                        <div class="d-flex justify-content-center justify-content-md-start align-items-center mb-1">
                                            <span class="text-gray-600 fw-semibold fs-5 me-2">Masa Berlaku hingga:</span>
                                            <span class="badge badge-light-danger fw-bold fs-6">
                                                <?= !empty($ikh['tgl_exp']) ? date('d F Y', strtotime($ikh['tgl_exp'])) : 'Tidak Terbatas' ?>
                                            </span>
                                        </div>
                                        <span class="text-muted fw-semibold fs-7"><i class="ki-outline ki-information-5 fs-6 me-1"></i> Klik pada ikon file untuk melihat atau mengunduh dokumen.</span>
                                    </div>

                                    <div class="d-flex flex-column flex-sm-row gap-3">
                                        <?php
                                        $kuota = (int)($ikh['kuota'] ?? 0);
                                        $tgl_exp = !empty($ikh['tgl_exp']) ? $ikh['tgl_exp'] : null;
                                        $show_perpanjang = false;
                                        $link_perpanjang = base_url('sw-siswa/ikh/perpanjang/' . encrypt_url($ikh['id_ikh']));
                                        $class_btn = "";

                                        if ($kuota > 0) {
                                            $show_perpanjang = true;
                                            $class_btn = "btn-perpanjang-kuota";
                                        } else {
                                            if ($tgl_exp) {
                                                $exp_time = strtotime($tgl_exp);
                                                $tiga_bulan_lagi = strtotime("+3 months", time());

                                                if ($exp_time <= $tiga_bulan_lagi) {
                                                    $show_perpanjang = true;
                                                    $link_perpanjang = base_url('list-bimbel');
                                                    $class_btn = "";
                                                }
                                            }
                                        }
                                        ?>
                                        <?php if ($show_perpanjang): ?>
                                            <?php
                                            $alertTitle = ($kuota > 0) ? "Perpanjang Izin Kuasa Hukum" : "Beli Paket Perpanjangan?";
                                            $alertText  = ($kuota > 0) ? "Masa berlaku IKH Anda akan diperpanjang." : "Anda akan dialihkan ke halaman pembelian paket. Lanjutkan?";
                                            ?>
                                            <a href="<?= $link_perpanjang ?>" class="btn btn-warning shadow-sm <?= $class_btn ?> btn-confirm-perpanjang" data-title="<?= $alertTitle ?>" data-text="<?= $alertText ?>">
                                                <i class="ki-outline ki-arrows-loop fs-2"></i> <?= ($kuota > 0) ? 'Ajukan Perpanjangan IKH' : 'Paket Perpanjangan IKH' ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="row g-5">
                                    <?php
                                    $files = json_decode($ikh['file_kartu_ikh'], true) ?? [];
                                    if (!empty($files)):
                                        foreach ($files as $index => $file):

                                            // ---------------------------------------------------------
                                            // MODIFIKASI: Deteksi & Atur URL Google Drive (E-KARTU)
                                            // ---------------------------------------------------------
                                            $isDrive = (strpos($file, '.') === false); // ID Drive tidak punya titik ekstensi

                                            if ($isDrive) {
                                                $fileUrl = 'https://drive.google.com/file/d/' . $file . '/preview';
                                                $downloadUrl = 'https://drive.google.com/uc?export=download&id=' . $file;
                                                $ext = 'pdf'; // Default ekstensi untuk kartu dari drive
                                            } else {
                                                $fileUrl = base_url('uploads/ikh/' . $file);
                                                $downloadUrl = $fileUrl;
                                                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                            }

                                            $icon = ($ext == 'pdf') ? 'ki-pdf-file' : 'ki-picture';
                                            $color = ($ext == 'pdf') ? 'text-danger' : 'text-primary';
                                    ?>
                                            <div class="col-md-6 col-lg-4">
                                                <div class="d-flex align-items-center border border-gray-300 border-dashed rounded p-4 h-100">
                                                    <div class="symbol symbol-40px me-4">
                                                        <div class="symbol-label bg-light">
                                                            <i class="ki-outline <?= $icon ?> fs-2x <?= $color ?>"></i>
                                                        </div>
                                                    </div>

                                                    <div class="flex-grow-1 me-2">
                                                        <span class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">Dokumen <?= $index + 1 ?></span>
                                                        <span class="text-muted fw-semibold d-block fs-7 text-uppercase">Tersedia</span>
                                                    </div>

                                                    <div class="d-flex gap-2">
                                                        <button type="button" class="btn btn-sm btn-icon btn-bg-light btn-active-color-success btn-preview-berkas"
                                                            data-file-url="<?= $fileUrl ?>"
                                                            data-file-ext="<?= $ext ?>"
                                                            data-file-name="Kartu IKH - <?= $ikh['nama_lengkap'] ?> (<?= $index + 1 ?>)"
                                                            title="Lihat Pratinjau">
                                                            <i class="ki-outline ki-eye fs-3"></i>
                                                        </button>

                                                        <a href="<?= $downloadUrl ?>" target="_blank" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" title="Unduh">
                                                            <i class="ki-outline ki-file-down fs-3"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach;
                                    else: ?>
                                        <div class="col-12 text-center py-5">
                                            <span class="text-muted italic">Belum ada kartu yang diterbitkan.</span>
                                        </div>
                                    <?php endif; ?>
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
                                <?php
                                // LOGIKA KUNCI STEP 2 (SESUAI PERMINTAAN)
                                // Hanya bisa dibuka JIKA Step 1 tersimpan (ada data) DAN:
                                // - User di-redirect ke tab lampiran secara aktif setelah klik tombol simpan, ATAU
                                // - Statusnya sudah bukan draft lagi
                                $canOpenStep2 = $hasData && ($activeTab === 'lampiran' || $status_validasi !== 'draft');
                                ?>
                                <?php if ($canOpenStep2): ?>
                                    <a class="nav-link text-active-primary text-gray-600 px-4 <?= $activeTab == 'lampiran' ? 'active' : '' ?>" data-bs-toggle="tab" href="#tab_lampiran">
                                        <i class="ki-outline ki-document fs-2 me-2 <?= $activeTab == 'lampiran' ? 'text-primary' : 'text-gray-500' ?>"></i>
                                        2. Unggah Lampiran
                                    </a>
                                <?php else: ?>
                                    <!-- Terkunci total, pointer-events: none memastikan JS bootstrap tidak bisa memaksanya terbuka -->
                                    <a class="nav-link text-muted px-4 disabled bg-light" href="javascript:void(0);" title="Selesaikan dan Simpan Data Diri di Step 1 terlebih dahulu" style="pointer-events: none; cursor: not-allowed;">
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
                                        <a href="<?= base_url('sw-siswa/ikh') ?>" class="btn btn-sm btn-light fw-bold hover-elevate-up">
                                            <i class="ki-outline ki-arrow-left fs-3"></i> Kembali
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <form action="<?= base_url('sw-siswa/ikh/store') ?>" method="POST" id="form_data_diri">
                                <?= csrf_field() ?>
                                <?php if ($hasData): ?>
                                    <input type="hidden" name="id_ikh" value="<?= $idIkh ?>">
                                <?php endif; ?>
                                <div class="card-body p-9">
                                    <div class="row g-5">
                                        <div class="col-md-6"><label class="required form-label">NIK</label><input type="number" name="nik" id="nik" class="form-control" value="<?= !empty($ikh['nik']) ? $ikh['nik'] : $siswa['nik'] ?>" required /></div>
                                        <div class="col-md-6"><label class="required form-label">NPWP</label><input type="number" name="npwp" id="npwp" class="form-control" value="<?= !empty($ikh['npwp']) ? $ikh['npwp'] : '' ?>" required /></div>
                                        <div class="col-md-12"><label class="required form-label">Nama Lengkap</label><input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" value="<?= !empty($ikh['nama_lengkap']) ? $ikh['nama_lengkap'] : $siswa['nama_siswa'] ?>" required /></div>
                                        <div class="col-md-6"><label class="required form-label">Tempat Lahir</label><input type="text" name="tempat_lahir" class="form-control" value="<?= !empty($ikh['tempat_lahir']) ? $ikh['tempat_lahir'] : $siswa['tempat_lahir'] ?>" required /></div>
                                        <div class="col-md-6"><label class="required form-label">Tanggal Lahir</label><input type="date" id="kt_datepicker_lahir" name="tanggal_lahir" class="form-control" value="<?= !empty($ikh['tanggal_lahir']) ? $ikh['tanggal_lahir'] : $siswa['tgl_lahir'] ?>" required /></div>

                                        <div class="col-md-6">
                                            <label class="required form-label">Pendidikan Terakhir</label>
                                            <select name="pendidikan_terakhir" class="form-select" data-control="select2" required>
                                                <option></option>
                                                <?php $pend = !empty($ikh['pendidikan_terakhir']) ? $ikh['pendidikan_terakhir'] : ''; ?>
                                                <option value="D4" <?= $pend == 'D4' ? 'selected' : '' ?>>D4</option>
                                                <option value="S1" <?= $pend == 'S1' ? 'selected' : '' ?>>S1</option>
                                                <option value="S2" <?= $pend == 'S2' ? 'selected' : '' ?>>S2</option>
                                                <option value="S3" <?= $pend == 'S3' ? 'selected' : '' ?>>S3</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6"><label class="required form-label">Jurusan</label><input type="text" name="jurusan" class="form-control" value="<?= !empty($ikh['jurusan']) ? $ikh['jurusan'] : '' ?>" required /></div>
                                        <div class="col-md-6"><label class="required form-label">Tahun Masuk</label><input type="number" name="tahun_masuk" id="tahun_masuk" class="form-control" value="<?= !empty($ikh['tahun_masuk']) ? $ikh['tahun_masuk'] : '' ?>" required /></div>
                                        <div class="col-md-6"><label class="required form-label">Tahun Lulus</label><input type="number" name="tahun_lulus" id="tahun_lulus" class="form-control" value="<?= !empty($ikh['tahun_lulus']) ? $ikh['tahun_lulus'] : '' ?>" required /></div>
                                        <div class="col-md-6"><label class="required form-label">Nomor WhatsApp</label><input type="number" name="no_wa" id="no_wa" class="form-control" value="<?= !empty($ikh['no_wa']) ? $ikh['no_wa'] : $siswa['hp'] ?>" required /></div>
                                        <div class="col-md-6"><label class="required form-label">Email Aktif</label><input type="email" name="email" class="form-control" value="<?= !empty($ikh['email']) ? $ikh['email'] : $siswa['email'] ?>" required /></div>
                                        <div class="col-md-6">
                                            <label class="required form-label">Kategori Kantor</label>
                                            <select name="kategori_kantor" class="form-select" data-control="select2" required>
                                                <option></option>
                                                <?php $kat = !empty($ikh['kategori_kantor']) ? $ikh['kategori_kantor'] : $siswa['kantor']; ?>
                                                <option value="Firma Hukum" <?= $kat == 'Firma Hukum' ? 'selected' : '' ?>>Firma Hukum</option>
                                                <option value="KAP" <?= $kat == 'KAP' ? 'selected' : '' ?>>KAP</option>
                                                <option value="KKP" <?= $kat == 'KKP' ? 'selected' : '' ?>>KKP</option>
                                                <option value="Mandiri" <?= $kat == 'Mandiri' ? 'selected' : '' ?>>Mandiri</option>
                                                <option value="Lainnya" <?= $kat == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6"><label class="required form-label">Nama Kantor</label><input type="text" name="nama_kantor" class="form-control" value="<?= !empty($ikh['nama_kantor']) ? $ikh['nama_kantor'] : $siswa['nama_kantor'] ?>" required /></div>
                                        <div class="col-md-6"><label class="required form-label">Alamat Sesuai KTP</label><textarea name="alamat_ktp" class="form-control" rows="3" required><?= !empty($ikh['alamat_ktp']) ? $ikh['alamat_ktp'] : $siswa['alamat_ktp'] ?></textarea></div>
                                        <div class="col-md-6"><label class="required form-label">Alamat Korespondensi</label><textarea name="alamat_korespondensi" class="form-control" rows="3" required><?= !empty($ikh['alamat_korespondensi']) ? $ikh['alamat_korespondensi'] : '' ?></textarea></div>
                                    </div>
                                    <div class="separator my-10"></div>
                                    <h3 class="fw-bold fs-4 mb-5">Persetujuan (Dokumen pendukung disiapkan oleh tim Legal KelasBrevet)</h3>
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
                                        <a href="<?= base_url('sw-siswa/ikh') ?>" class="btn btn-sm btn-light fw-bold hover-elevate-up">
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
                                        ['id' => 'file_ktp', 'label' => '1. KTP (Scan Asli)', 'accept' => '.pdf', 'hint' => 'Hanya PDF'],
                                        ['id' => 'file_npwp', 'label' => '2. NPWP', 'accept' => '.pdf', 'hint' => 'Hanya PDF'],
                                        ['id' => 'file_kk', 'label' => '3. Kartu Keluarga', 'accept' => '.pdf', 'hint' => 'Hanya PDF'],
                                        ['id' => 'file_foto', 'label' => '4. Pas Foto 4x6', 'accept' => '.jpg', 'hint' => 'JPG'],
                                        ['id' => 'file_skck', 'label' => '5. SKCK', 'accept' => '.pdf', 'hint' => 'Hanya PDF'],
                                        ['id' => 'file_ijazah', 'label' => '6. IJAZAH (ASLI)', 'accept' => '.pdf', 'hint' => 'Hanya PDF'],
                                        ['id' => 'file_spt', 'label' => '7. BUKTI TANDA TERIMA SPT 2 TAHUN TERAKHIR', 'accept' => '.pdf', 'hint' => 'Hanya PDF'],
                                        ['id' => 'file_sertifikat', 'label' => '8. SERTIFIKAT BREVET PAJAK AB', 'accept' => '.pdf', 'hint' => 'Hanya PDF'],
                                        ['id' => 'file_ttd', 'label' => '9. TTD ELEKTRONIK', 'accept' => '.jpg', 'hint' => 'JPG'],
                                    ];
                                    ?>
                                    <?php foreach ($fileConfigs as $cfg):
                                        $isUploaded = $hasData && !empty($ikh[$cfg['id']]);

                                        // ---------------------------------------------------------
                                        // MODIFIKASI: Deteksi & Atur URL Google Drive (UPLOAD TAB)
                                        // ---------------------------------------------------------
                                        $fileUrl = '';
                                        $fileExt = '';
                                        if ($isUploaded) {
                                            $fileData = $ikh[$cfg['id']];
                                            $isDrive = (strpos($fileData, '.') === false); // Cek ID Drive

                                            if ($isDrive) {
                                                $fileUrl = 'https://drive.google.com/file/d/' . $fileData . '/preview';
                                                $fileExt = str_replace('.', '', $cfg['accept']); // Ambil ekspektasi format
                                            } else {
                                                $fileUrl = base_url('uploads/ikh/' . $fileData);
                                                $fileExt = strtolower(pathinfo($fileData, PATHINFO_EXTENSION));
                                            }
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
                                                    <!-- TAMBAHAN: Kita tambahkan pengecekan class khusus 'input-ttd-crop' jika ID-nya file_ttd -->
                                                    <input type="file"
                                                        class="form-control file-input-ajax <?= $cfg['id'] == 'file_ttd' ? 'input-ttd-crop' : '' ?>"
                                                        id="input_<?= $cfg['id'] ?>"
                                                        data-name="<?= $cfg['id'] ?>"
                                                        accept="<?= $cfg['accept'] ?>">

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
                // Logika Status STEP (Tetap sama)
                $stat_val = $ikh['status_validasi_admin'] ?? 'draft';
                $stat_pro = $ikh['status_proses'] ?? 'pending';
                $stat_fin = $ikh['status_final'] ?? 'pending';
                $stat_ser = $ikh['status_sertifikat'] ?? 'belum';

                $step1_done    = ($stat_val === 'valid');
                $step2_done    = ($stat_pro === 'selesai');
                $step2_active  = ($stat_pro === 'proses');
                $step3_done    = ($stat_fin === 'selesai');
                $step3_active  = ($stat_fin === 'proses');
                $step4_done    = ($stat_ser === 'terbit');
                $step4_active  = ($step3_done && !$step4_done);
                $canEdit = in_array($stat_val, ['draft', 'pending', 'ditolak', 'revisi']);
                ?>
                <?php if ($stat_ser != 'terbit' || $stat_val == 'revisi'): ?>

                    <div class="card shadow-sm mb-8">
                        <div class="card-body pt-10 pb-10">
                            <div class="d-flex flex-center flex-nowrap w-100 stepper-mobile-scroll">
                                <div class="d-flex flex-column align-items-center position-relative w-150px step-item-mobile">
                                    <div class="symbol symbol-50px symbol-circle mb-3">
                                        <span class="symbol-label bg-primary text-inverse-primary fs-2 fw-bold"><i class="ki-outline ki-check fs-1 text-white"></i></span>
                                    </div>
                                    <span class="fw-bold text-gray-800 text-center">Pendaftaran</span>
                                    <?php if ($stat_val == 'pending'): ?><span class="badge badge-light-primary mt-2 animate-pulse">Menunggu Validasi</span>
                                    <?php elseif ($stat_val == 'ditolak'): ?><span class="badge badge-light-danger mt-2">Perbaikan Persyaratan</span>
                                    <?php elseif ($stat_val == 'revisi'): ?><span class="badge badge-light-warning mt-2">Proses Perpanjangan</span><?php endif; ?>
                                </div>
                                <div class="step-line <?= $step1_done ? 'active' : '' ?>"></div>
                                <div class="d-flex flex-column align-items-center position-relative w-150px step-item-mobile <?= (!$step1_done) ? 'opacity-50' : '' ?>">
                                    <div class="symbol symbol-50px symbol-circle mb-3 <?= $step2_done ? '' : ($step2_active ? 'border border-primary border-dashed' : 'bg-light') ?>">
                                        <span class="symbol-label <?= $step2_done ? 'bg-primary text-white' : ($step2_active ? 'bg-light-primary text-primary' : 'text-muted') ?> fs-2 fw-bold">
                                            <?= $step2_done ? '<i class="ki-outline ki-check fs-1 text-white"></i>' : '2' ?>
                                        </span>
                                    </div>
                                    <span class="fw-bold <?= $step2_done ? 'text-gray-800' : ($step2_active ? 'text-primary' : 'text-gray-600') ?> text-center">Validasi Berkas</span>
                                    <?php if ($step2_active): ?><span class="badge badge-light-primary mt-2 animate-pulse">In Progress</span><?php endif; ?>
                                </div>
                                <div class="step-line <?= $step2_done ? 'active' : '' ?>"></div>
                                <div class="d-flex flex-column align-items-center position-relative w-150px step-item-mobile <?= (!$step2_done) ? 'opacity-50' : '' ?>">
                                    <div class="symbol symbol-50px symbol-circle mb-3 <?= $step3_done ? '' : ($step3_active ? 'border border-primary border-dashed' : 'bg-light') ?>">
                                        <span class="symbol-label <?= $step3_done ? 'bg-primary text-white' : ($step3_active ? 'bg-light-primary text-primary' : 'text-muted') ?> fs-2 fw-bold">
                                            <?= $step3_done ? '<i class="ki-outline ki-check fs-1 text-white"></i>' : '3' ?>
                                        </span>
                                    </div>
                                    <span class="fw-bold <?= $step3_done ? 'text-gray-800' : ($step3_active ? 'text-primary' : 'text-gray-600') ?> text-center">Tahap Final</span>
                                    <?php if ($step3_active): ?><span class="badge badge-light-primary mt-2 animate-pulse">In Progress</span><?php endif; ?>
                                </div>
                                <div class="step-line <?= $step3_done ? 'active' : '' ?>"></div>
                                <div class="d-flex flex-column align-items-center position-relative w-150px step-item-mobile <?= (!$step4_done) ? 'opacity-50' : '' ?>">
                                    <div class="symbol symbol-50px symbol-circle mb-3 <?= $step4_done ? '' : ($step4_active ? 'border border-primary border-dashed' : 'bg-light') ?>">
                                        <span class="symbol-label <?= $step4_done ? 'bg-primary text-white' : ($step4_active ? 'bg-light-primary text-primary' : 'text-muted') ?> fs-2 fw-bold">
                                            <?= $step4_done ? '<i class="ki-outline ki-award fs-1 text-white"></i>' : '4' ?>
                                        </span>
                                    </div>
                                    <span class="fw-bold <?= $step4_done ? 'text-gray-800' : ($step4_active ? 'text-primary' : 'text-gray-600') ?> text-center">Kartu IKH Terbit</span>
                                    <?php if ($step4_done): ?><span class="badge badge-light-primary mt-2">Selesai</span>
                                    <?php elseif ($step4_active): ?><span class="badge badge-light-primary mt-2 animate-pulse">Penerbitan</span><?php endif; ?>
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
                                            <?php if ($stat_val == 'ditolak' || $stat_val == 'revisi'): ?>
                                                <?php
                                                $btnColor = ($stat_val == 'revisi') ? 'btn-warning' : 'btn-danger';
                                                $btnIcon = ($stat_val == 'revisi') ? 'ki-arrows-loop' : 'ki-arrows-circle';
                                                $btnTextLg = ($stat_val == 'revisi') ? 'Ajukan Perpanjangan' : 'Kirim Perbaikan';
                                                $btnTextSm = ($stat_val == 'revisi') ? 'Perpanjang' : 'Perbaiki';
                                                $swalTitle = ($stat_val == 'revisi') ? 'Ajukan Perpanjangan?' : 'Kirim Ulang Berkas?';
                                                $swalText = ($stat_val == 'revisi') ? 'Apakah Anda yakin data dan berkas perpanjangan sudah lengkap? Data akan dikirim ke admin untuk divalidasi.' : 'Apakah Anda yakin semua data dan dokumen perbaikan sudah benar? Berkas Anda akan masuk kembali ke antrean admin untuk divalidasi ulang.';
                                                ?>
                                                <a href="javascript:void(0)" data-url="<?= base_url('sw-siswa/ikh/perbaikan/' . encrypt_url($ikh['id_ikh'])) ?>"
                                                    class="btn btn-sm <?= $btnColor ?> fw-bold hover-elevate-up btn-perbaikan"
                                                    data-title="<?= $swalTitle ?>" data-text="<?= $swalText ?>" title="<?= $btnTextLg ?>">
                                                    <i class="ki-outline <?= $btnIcon ?> fs-3"></i>
                                                    <span class="d-none d-sm-inline"><?= $btnTextLg ?></span>
                                                    <span class="d-inline d-sm-none"><?= $btnTextSm ?></span>
                                                </a>
                                                <a href="?edit=true#tab_data_diri" class="btn btn-sm btn-light-primary fw-bold hover-elevate-up">
                                                    <i class="ki-outline ki-pencil fs-3"></i>
                                                    <span class="d-none d-sm-inline">Edit Data & Berkas</span>
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
                                            ['nama' => '10. Riwayat Hidup', 'field' => 'file_riwayat_hidup'],
                                            ['nama' => '11. Pernyataan Bukan PNS', 'field' => 'file_bukan_pns'],
                                            ['nama' => '12. Integritas', 'field' => 'file_pakta_integritas'],
                                            ['nama' => '13. Pernyataan Izin Kuasa Hukum', 'field' => 'file_pernyataan_ikh'],
                                        ];

                                        $fileAdmin = ['file_riwayat_hidup', 'file_bukan_pns', 'file_pakta_integritas', 'file_pernyataan_ikh'];

                                        foreach ($berkasList as $berkas):
                                            $isUploaded = !empty($ikh[$berkas['field']]);
                                            $isAdminProvided = in_array($berkas['field'], $fileAdmin);

                                            // ---------------------------------------------------------
                                            // MODIFIKASI: Deteksi & Atur URL Google Drive (STATUS TAB)
                                            // ---------------------------------------------------------
                                            $fileUrl = '';
                                            $fileExt = '';
                                            if ($isUploaded) {
                                                $fileData = $ikh[$berkas['field']];
                                                $isDrive = (strpos($fileData, '.') === false);

                                                if ($isDrive) {
                                                    $fileUrl = 'https://drive.google.com/file/d/' . $fileData . '/preview';
                                                    $fileExt = (strpos($berkas['field'], 'foto') !== false || strpos($berkas['field'], 'ttd') !== false) ? 'jpg' : 'pdf';
                                                } else {
                                                    $fileUrl = base_url('uploads/ikh/' . $fileData);
                                                    $fileExt = strtolower(pathinfo($fileData, PATHINFO_EXTENSION));
                                                }
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
                                                        <div class="symbol-label <?= $isAdminProvided ? 'bg-light-info' : 'bg-light-danger' ?>">
                                                            <i class="ki-outline <?= $isAdminProvided ? 'ki-document text-info' : 'ki-file text-danger' ?> fs-2"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="d-flex flex-column flex-grow-1">
                                                    <div class="d-flex align-items-center mb-1">
                                                        <?php if ($isUploaded): ?>
                                                            <a href="javascript:void(0)"
                                                                class="fs-6 fw-bold text-gray-800 text-hover-primary btn-preview-berkas"
                                                                data-file-url="<?= $fileUrl ?>"
                                                                data-file-ext="<?= $fileExt ?>"
                                                                data-file-name="<?= $berkas['nama'] ?>">
                                                                <?= $berkas['nama'] ?>
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="fs-6 fw-bold text-gray-800"><?= $berkas['nama'] ?></span>
                                                        <?php endif; ?>

                                                        <?php if ($isAdminProvided): ?>
                                                            <span class="badge badge-light-info ms-2 px-2 py-1 fs-9">Disiapkan Admin</span>
                                                        <?php endif; ?>
                                                    </div>

                                                    <span class="text-muted fw-semibold fs-8">
                                                        <?php
                                                        if ($isUploaded) {
                                                            echo 'Telah diunggah (Klik untuk lihat)';
                                                        } else {
                                                            echo $isAdminProvided ? 'File ini akan diurus dan diunggah oleh tim Admin' : 'File belum ada';
                                                        }
                                                        ?>
                                                    </span>
                                                </div>

                                                <div>
                                                    <?php if ($isUploaded): ?>
                                                        <span class="badge badge-light-success fw-bold px-3 py-2"><i class="ki-outline ki-check-circle fs-5 text-success me-1"></i> Valid</span>
                                                    <?php else: ?>
                                                        <?php if ($isAdminProvided): ?>
                                                            <span class="badge badge-light-info fw-bold px-3 py-2"><i class="ki-outline ki-time fs-5 text-info me-1"></i> Proses</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-light-danger fw-bold px-3 py-2"><i class="ki-outline ki-cross-circle fs-5 text-danger me-1"></i> Kosong</span>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
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
                <a href="#" id="btn_download_berkas" class="btn btn-primary">
                    <i class="ki-outline ki-file-down fs-2"></i> Unduh Dokumen
                </a>
            </div>
        </div>
    </div>
</div>



<!-- Modal untuk Crop TTD -->
<div class="modal fade" id="modalCropTtd" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="fw-bold">Sesuaikan Tanda Tangan</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body text-center">
                <div class="text-muted mb-3">Posisikan TTD di dalam kotak. Background putih akan otomatis dihapus.</div>

                <!-- Area Gambar Crop -->
                <div style="max-height: 400px; overflow: hidden; display: inline-block; width: 100%; border: 1px dashed #ccc; border-radius: 8px;">
                    <img id="imageToCrop" src="" style="max-width: 100%; display: block;">
                </div>

                <!-- TAMBAHAN: Slider Rotasi Manual -->
                <div class="mt-5 px-4">
                    <label class="form-label fs-7 fw-bold text-muted d-block text-start mb-2">Geser untuk memutar gambar (Rotasi Manual):</label>
                    <input type="range" id="sliderRotasi" class="form-range" min="-180" max="180" value="0">
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between align-items-center w-100">
                <!-- Tombol Rotasi Kiri & Kanan -->
                <div>
                    <button type="button" class="btn btn-icon btn-light-info me-2" id="btnRotateLeft" title="Putar Kiri 90 Derajat">
                        <i class="ki-outline ki-arrow-circle-left fs-2"></i>
                    </button>
                    <button type="button" class="btn btn-icon btn-light-info" id="btnRotateRight" title="Putar Kanan 90 Derajat">
                        <i class="ki-outline ki-arrow-circle-right fs-2"></i>
                    </button>
                </div>

                <!-- Tombol Aksi -->
                <div>
                    <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btnProsesCrop">
                        <i class="ki-outline ki-magic fs-2"></i> Crop & Hapus Latar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<!-- Load CSS & JS Cropper.js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    $('<style>@keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } } .animate-pulse { animation: pulse 2s infinite; }</style>').appendTo('head');

    $(document).ready(function() {

        if ($("#kt_datepicker_lahir").length) {
            $("#kt_datepicker_lahir").flatpickr({
                altInput: true,
                altFormat: "d-m-Y",
                dateFormat: "Y-m-d",
                maxDate: "today",
                locale: "id"
            });
        }
        $('#nik, #npwp').on('input', function() {
            if (this.value.length > 16) this.value = this.value.slice(0, 16);
        });
        $('#nama_lengkap').on('input', function() {
            if (this.value.length > 60) this.value = this.value.slice(0, 60);
        });
        $('#no_wa').on('input', function() {
            if (this.value.length > 15) this.value = this.value.slice(0, 15);
        });
        $('#tahun_masuk, #tahun_lulus').on('input', function() {
            if (this.value.length > 4) this.value = this.value.slice(0, 4);
        });

        <?php if ($showForm): ?>
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
                if (fileData.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        text: "Ukuran file " + fileData.name + " terlalu besar! Maksimal hanya 2 MB.",
                        icon: "error",
                        customClass: {
                            confirmButton: "btn btn-danger"
                        }
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
                    url: '<?= base_url('sw-siswa/ikh/upload-ajax') ?>',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.csrf_hash) csrfHash = response.csrf_hash;
                        if (response.success) {
                            toastr.success(response.message);
                            $('#box_' + targetId).removeClass('border-gray-300').addClass('border-success bg-light-success');
                            $('#status_' + targetId).removeClass('badge-light-danger').addClass('badge-success').html('<i class="ki-outline ki-check text-white fs-5 me-1"></i> Tersimpan');

                            // Update Preview lokal (Tetap jalan pakai URL Blob lokal)
                            let localFileUrl = URL.createObjectURL(fileData);
                            let newFileExt = fileData.name.split('.').pop().toLowerCase();
                            let fileNameLabel = $('#box_' + targetId).find('label.fs-5').text().trim();
                            let previewBtn = $('#box_' + targetId).find('.btn-preview-berkas');

                            if (previewBtn.length > 0) {
                                previewBtn.attr('data-file-url', localFileUrl).attr('data-file-ext', newFileExt);
                            } else {
                                let newPreviewBtn = `<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-light-primary hover-elevate-up btn-preview-berkas me-2" data-file-url="${localFileUrl}" data-file-ext="${newFileExt}" data-file-name="${fileNameLabel}" title="Lihat dokumen tersimpan"><i class="ki-outline ki-eye fs-3"></i></a>`;
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
                                    window.location.href = '<?= base_url('sw-siswa/ikh') ?>';
                                });
                            }
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
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

        // ---------------------------------------------------------
        // MODIFIKASI: SCRIPT MODAL PRATINJAU & UNDUH (Mendukung G-Drive)
        // ---------------------------------------------------------
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
            $('#preview_container').html('<div class="spinner-border text-primary" role="status"></div>');

            // Logika Link Unduh (Jika Google Drive, gunakan format unduh langsung /uc?export)
            if (fileUrl.includes('drive.google.com')) {
                let driveId = fileUrl.split('/d/')[1].split('/preview')[0];
                let downloadUrl = 'https://drive.google.com/uc?export=download&id=' + driveId;
                $('#btn_download_berkas').attr('href', downloadUrl).removeAttr('download').attr('target', '_blank');
            } else {
                $('#btn_download_berkas').attr('href', fileUrl).attr('download', fileName + '.' + fileExt).removeAttr('target');
            }

            let myModal = new bootstrap.Modal(document.getElementById('modal_preview_berkas'));
            myModal.show();

            setTimeout(() => {
                if (fileUrl.includes('drive.google.com')) {
                    // Gunakan iFrame untuk Google Drive (Google Viewer otomatis menampilkan PDF/Gambar)
                    $('#preview_container').html('<iframe src="' + fileUrl + '" width="100%" height="700px" style="border: none; border-radius: 8px;"></iframe>');
                } else {
                    // Logika bawaan untuk file lokal / file blob lokal setelah upload ajax
                    if (fileExt === 'pdf') {
                        $('#preview_container').html('<embed src="' + fileUrl + '" type="application/pdf" width="100%" height="700px" />');
                    } else if (['jpg', 'jpeg', 'png'].includes(fileExt)) {
                        $('#preview_container').html('<img src="' + fileUrl + '" class="img-fluid rounded shadow-sm" style="max-height: 700px; object-fit: contain;" />');
                    } else {
                        $('#preview_container').html('<div class="text-center text-muted"><i class="ki-outline ki-file fs-5x mb-3"></i><br>Pratinjau tidak tersedia.</div>');
                    }
                }
            }, 500);
        });

        $('body').on('hidden.bs.modal', '#modal_preview_berkas', function() {
            $('#preview_container').empty();
            $('.modal-backdrop').remove();
            $('body').css('overflow', '');
        });

        $('body').on('click', '.btn-perbaikan', function(e) {
            /* SCRIPT LAMA... */
            e.preventDefault();
            const targetUrl = $(this).attr('data-url');
            const swalTitle = $(this).attr('data-title') || "Kirim Ulang Berkas?";
            const swalText = $(this).attr('data-text') || "Apakah Anda yakin semua data dan dokumen perbaikan sudah benar?";
            if (!targetUrl) return;
            Swal.fire({
                title: swalTitle,
                text: swalText,
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Ya, Kirim Sekarang!",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-light-primary"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        text: "Meneruskan permintaan Anda...",
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

        $('body').on('click', '.btn-confirm-perpanjang', function(e) {
            /* SCRIPT LAMA... */
            e.preventDefault();
            let targetUrl = $(this).attr('href');
            let title = $(this).attr('data-title');
            let text = $(this).attr('data-text');
            Swal.fire({
                title: title,
                text: text,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Ya, Lanjutkan!",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-light-primary"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        text: "Mengalihkan halaman...",
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
<script>
    $(document).ready(function() {
        let cropper;
        const cropModal = $('#modalCropTtd');
        const image = document.getElementById('imageToCrop');

        // 1. Deteksi saat input TTD dipilih
        $(document).on('change', '.input-ttd-crop', function(e) {
            let files = e.target.files;
            if (files && files.length > 0) {
                let file = files[0];
                let reader = new FileReader();

                reader.onload = function(evt) {
                    image.src = evt.target.result;
                    cropModal.modal('show'); // Tampilkan modal
                };
                reader.readAsDataURL(file);
            }
        });

        // 2. Inisialisasi Cropper.js saat modal terbuka
        cropModal.on('shown.bs.modal', function() {
            cropper = new Cropper(image, {
                aspectRatio: 1 / 1, // Memaksa ukuran 1:1 (Persegi)
                viewMode: 1,
                autoCropArea: 0.8,
                dragMode: 'move',
            });
        }).on('hidden.bs.modal', function() {
            // Hancurkan cropper saat modal ditutup agar tidak error saat dibuka lagi
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            image.src = '';
            $('#sliderRotasi').val(0);
        });

        // 3. Proses Crop & Hapus Background Putih
        $('#btnProsesCrop').on('click', function() {
            if (!cropper) return;

            // Ambil hasil crop dengan resolusi tajam
            let canvas = cropper.getCroppedCanvas({
                width: 500,
                height: 500
            });

            // --- ALGORITMA PENGHAPUS BACKGROUND PUTIH ---
            let ctx = canvas.getContext('2d');
            let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            let data = imageData.data;

            for (let i = 0; i < data.length; i += 4) {
                let r = data[i];
                let g = data[i + 1];
                let b = data[i + 2];

                // Jika warna piksel terang/mendekati putih (R, G, B di atas 200)
                if (r > 200 && g > 200 && b > 200) {
                    data[i + 3] = 0; // Ubah nilai Alpha menjadi 0 (Transparan)
                } else {
                    // (Opsional) Ubah sisa tinta yang tidak terhapus menjadi hitam pekat 
                    // agar hasil TTD di PDF nanti sangat jelas
                    data[i] = 0; // R
                    data[i + 1] = 0; // G
                    data[i + 2] = 0; // B
                }
            }
            ctx.putImageData(imageData, 0, 0);
            // --------------------------------------------

            // Ubah canvas menjadi file Blob (.png agar transparansi tersimpan)
            canvas.toBlob(function(blob) {
                // Kita manipulasi input file asli menggunakan DataTransfer
                let dataTransfer = new DataTransfer();
                let file = new File([blob], "ttd_siap_upload.png", {
                    type: "image/png"
                });
                dataTransfer.items.add(file);

                // Suntikkan file hasil crop ini ke input file_ttd asli Anda
                document.getElementById('input_file_ttd').files = dataTransfer.files;

                cropModal.modal('hide');

                // Beri tahu user bahwa file sudah siap diupload
                Swal.fire({
                    text: "Tanda tangan berhasil di-crop dan background dihapus! Silakan klik tombol Upload.",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, Mengerti!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });

            }, 'image/png'); // Format harus PNG untuk menyimpan transparan
        });

        // ==========================================
        // FITUR TAMBAHAN: ROTASI SLIDER & TOMBOL
        // ==========================================

        // 1. Putar gambar secara mulus saat slider digeser
        $('#sliderRotasi').on('input', function() {
            if (cropper) {
                let derajat = $(this).val();
                cropper.rotateTo(derajat); // rotateTo memutar ke sudut absolut yang spesifik
            }
        });

        // 2. Tombol Putar Kiri (-90 derajat)
        $('#btnRotateLeft').on('click', function() {
            if (cropper) {
                let nilaiSekarang = parseInt($('#sliderRotasi').val());
                let nilaiBaru = nilaiSekarang - 90;

                // Batasi agar tidak lewat dari batas minimum slider (-180)
                if (nilaiBaru < -180) nilaiBaru = nilaiBaru + 360;

                $('#sliderRotasi').val(nilaiBaru); // Update posisi slider
                cropper.rotateTo(nilaiBaru); // Putar gambar
            }
        });

        // 3. Tombol Putar Kanan (+90 derajat)
        $('#btnRotateRight').on('click', function() {
            if (cropper) {
                let nilaiSekarang = parseInt($('#sliderRotasi').val());
                let nilaiBaru = nilaiSekarang + 90;

                // Batasi agar tidak lewat dari batas maksimum slider (180)
                if (nilaiBaru > 180) nilaiBaru = nilaiBaru - 360;

                $('#sliderRotasi').val(nilaiBaru); // Update posisi slider
                cropper.rotateTo(nilaiBaru); // Putar gambar
            }
        });

        // ==========================================
    });
</script>
<?= $this->endSection(); ?>
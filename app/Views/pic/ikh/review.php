<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?php
// Deteksi Status
$stat_val = $ikh['status_validasi_admin'] ?? 'pending';
$stat_pro = $ikh['status_proses'] ?? 'pending';
$stat_fin = $ikh['status_final'] ?? 'pending';
$stat_ser = $ikh['status_sertifikat'] ?? 'belum';
?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">

                <div class="flex-column flex-lg-row-auto w-100 w-xl-600px mb-10 mb-xl-0">
                    <div class="card card-flush shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title fw-bold">Data Pemohon & Berkas</h3>
                        </div>
                        <div class="card-body pt-0">

                            <!-- ========================================== -->
                            <!-- BAGIAN DATA PEMOHON (READ-ONLY VIEW)       -->
                            <!-- ========================================== -->
                            <div id="view_pemohon">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="text-uppercase text-muted fw-bold mb-0">Identitas Diri</h6>
                                    <button class="btn btn-icon btn-sm btn-light-primary" id="btn_edit_pemohon" title="Edit Seluruh Data Pemohon">
                                        <i class="ki-outline ki-pencil fs-4"></i>
                                    </button>
                                </div>
                                <div class="d-flex flex-column gap-4 mb-6">
                                    <div class="d-flex flex-stack"><span class="fw-semibold text-gray-500">Nama Lengkap</span><span class="fw-bold text-gray-800"><?= $ikh['nama_lengkap'] ?></span></div>
                                    <div class="d-flex flex-stack"><span class="fw-semibold text-gray-500">NIK</span><span class="fw-bold text-gray-800"><?= $ikh['nik'] ?></span></div>
                                    <div class="d-flex flex-stack"><span class="fw-semibold text-gray-500">NPWP</span><span class="fw-bold text-gray-800"><?= $ikh['npwp'] ?></span></div>
                                    <div class="d-flex flex-stack"><span class="fw-semibold text-gray-500">Tempat, Tgl Lahir</span><span class="fw-bold text-gray-800"><?= $ikh['tempat_lahir'] ?>, <?= !empty($ikh['tanggal_lahir']) ? date('d M Y', strtotime($ikh['tanggal_lahir'])) : '-' ?></span></div>
                                </div>

                                <div class="separator border-gray-200 my-5"></div>

                                <h6 class="text-uppercase text-muted fw-bold mb-3">Riwayat Pendidikan</h6>
                                <div class="d-flex flex-column gap-4 mb-6">
                                    <div class="d-flex flex-stack"><span class="fw-semibold text-gray-500">Pendidikan</span><span class="fw-bold text-gray-800"><?= $ikh['pendidikan_terakhir'] ?> - <?= $ikh['jurusan'] ?></span></div>
                                    <div class="d-flex flex-stack"><span class="fw-semibold text-gray-500">Tahun Studi</span><span class="fw-bold text-gray-800"><?= $ikh['tahun_masuk'] ?> s/d <?= $ikh['tahun_lulus'] ?></span></div>
                                </div>

                                <div class="separator border-gray-200 my-5"></div>

                                <h6 class="text-uppercase text-muted fw-bold mb-3">Kontak & Instansi</h6>
                                <div class="d-flex flex-column gap-4 mb-6">
                                    <div class="d-flex flex-stack"><span class="fw-semibold text-gray-500">WhatsApp</span><span class="fw-bold text-primary"><?= $ikh['no_wa'] ?></span></div>
                                    <div class="d-flex flex-stack"><span class="fw-semibold text-gray-500">Email</span><span class="fw-bold text-gray-800"><?= $ikh['email'] ?></span></div>
                                    <div class="d-flex flex-stack"><span class="fw-semibold text-gray-500">Email Custom</span><span class="fw-bold text-gray-800"><?= $ikh['email_custom'] ?></span></div>
                                    <div class="d-flex flex-stack"><span class="fw-semibold text-gray-500">Kategori Kantor</span><span class="fw-bold text-gray-800"><?= $ikh['kategori_kantor'] ?></span></div>
                                    <div class="d-flex flex-stack"><span class="fw-semibold text-gray-500">Nama Kantor</span><span class="fw-bold text-gray-800"><?= $ikh['nama_kantor'] ?></span></div>
                                </div>

                                <div class="separator border-gray-200 my-5"></div>

                                <h6 class="text-uppercase text-muted fw-bold mb-3">Informasi Alamat</h6>
                                <div class="d-flex flex-column gap-4 mb-8">
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold text-gray-500 mb-1">Alamat Sesuai KTP</span>
                                        <span class="fw-bold text-gray-800"><?= $ikh['alamat_ktp'] ?></span>
                                    </div>
                                    <div class="d-flex flex-column mt-3">
                                        <span class="fw-semibold text-gray-500 mb-1">Alamat Korespondensi</span>
                                        <span class="fw-bold text-gray-800"><?= $ikh['alamat_korespondensi'] ?></span>
                                    </div>
                                </div>
                                <h6 class="text-uppercase text-muted fw-bold mb-3">Pengalaman Kerja</h6>
                                <div class="d-flex flex-column gap-4 mb-8">
                                    <div class="d-flex flex-column">
                                        <div class="fw-bold text-gray-800">
                                            <?php
                                            // 1. Ambil data JSON (Sesuaikan 'riwayat_pekerjaan' dengan nama kolom di database Anda)
                                            $json_data = $ikh['riwayat_pekerjaan'] ?? '[]';

                                            // 2. Decode JSON menjadi Array PHP biasa
                                            $riwayat_array = json_decode($json_data, true);

                                            // 3. Cek apakah array valid dan ada isinya
                                            if (!empty($riwayat_array) && is_array($riwayat_array)) {
                                                echo '<ul class="m-0 px-4" style="list-style-type: disc;">';

                                                foreach ($riwayat_array as $pekerjaan) {
                                                    // Cetak langsung stringnya sebagai list item (li)
                                                    echo "<li class='mb-1 fw-bold text-gray-800'>{$pekerjaan}</li>";
                                                }

                                                echo '</ul>';
                                            } else {
                                                echo '<span class="text-muted fw-normal"><i>Tidak ada riwayat pekerjaan.</i></span>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ========================================== -->


                            <!-- ========================================== -->
                            <!-- BAGIAN DATA PEMOHON (FORM EDIT)            -->
                            <!-- ========================================== -->
                            <form action="<?= base_url('sw-pic/ikh/update-pemohon') ?>" method="POST" id="form_edit_pemohon" class="d-none">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id_ikh" value="<?= $ikh['id_ikh'] ?>">

                                <h6 class="text-uppercase text-primary fw-bold mb-3 border-bottom border-primary pb-2"><i class="ki-outline ki-profile-circle fs-4 text-primary me-1"></i> Identitas Diri</h6>
                                <div class="row g-3 mb-7">
                                    <div class="col-12">
                                        <label class="form-label fs-8 fw-semibold text-muted mb-1">Nama Lengkap</label>
                                        <input type="text" name="nama_lengkap" class="form-control form-control-sm form-control-solid" value="<?= $ikh['nama_lengkap'] ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs-8 fw-semibold text-muted mb-1">NIK</label>
                                        <input type="text" name="nik" class="form-control form-control-sm form-control-solid" value="<?= $ikh['nik'] ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs-8 fw-semibold text-muted mb-1">NPWP</label>
                                        <input type="text" name="npwp" class="form-control form-control-sm form-control-solid" value="<?= $ikh['npwp'] ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs-8 fw-semibold text-muted mb-1">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" class="form-control form-control-sm form-control-solid" value="<?= $ikh['tempat_lahir'] ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs-8 fw-semibold text-muted mb-1">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" class="form-control form-control-sm form-control-solid" value="<?= $ikh['tanggal_lahir'] ?>">
                                    </div>
                                </div>

                                <h6 class="text-uppercase text-primary fw-bold mb-3 border-bottom border-primary pb-2"><i class="ki-outline ki-book-open fs-4 text-primary me-1"></i> Riwayat Pendidikan</h6>
                                <div class="row g-3 mb-7">
                                    <div class="col-md-6">
                                        <label class="form-label fs-8 fw-semibold text-muted mb-1">Pendidikan Terakhir</label>
                                        <input type="text" name="pendidikan_terakhir" class="form-control form-control-sm form-control-solid" value="<?= $ikh['pendidikan_terakhir'] ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs-8 fw-semibold text-muted mb-1">Jurusan</label>
                                        <input type="text" name="jurusan" class="form-control form-control-sm form-control-solid" value="<?= $ikh['jurusan'] ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs-8 fw-semibold text-muted mb-1">Tahun Masuk</label>
                                        <input type="text" name="tahun_masuk" class="form-control form-control-sm form-control-solid" value="<?= $ikh['tahun_masuk'] ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs-8 fw-semibold text-muted mb-1">Tahun Lulus</label>
                                        <input type="text" name="tahun_lulus" class="form-control form-control-sm form-control-solid" value="<?= $ikh['tahun_lulus'] ?>">
                                    </div>
                                </div>

                                <h6 class="text-uppercase text-primary fw-bold mb-3 border-bottom border-primary pb-2"><i class="ki-outline ki-phone fs-4 text-primary me-1"></i> Kontak & Instansi</h6>
                                <div class="row g-3 mb-7">
                                    <div class="col-md-6">
                                        <label class="form-label fs-8 fw-semibold text-muted mb-1">No. WhatsApp</label>
                                        <input type="text" name="no_wa" class="form-control form-control-sm form-control-solid" value="<?= $ikh['no_wa'] ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs-8 fw-semibold text-muted mb-1">Email</label>
                                        <input type="email" name="email" class="form-control form-control-sm form-control-solid" value="<?= $ikh['email'] ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs-8 fw-semibold text-muted mb-1">Email Custom</label>
                                        <input type="email" name="email_custom" class="form-control form-control-sm form-control-solid" value="<?= $ikh['email_custom'] ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs-8 fw-semibold text-muted mb-1">Kategori Kantor</label>
                                        <input type="text" name="kategori_kantor" class="form-control form-control-sm form-control-solid" value="<?= $ikh['kategori_kantor'] ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs-8 fw-semibold text-muted mb-1">Nama Kantor</label>
                                        <input type="text" name="nama_kantor" class="form-control form-control-sm form-control-solid" value="<?= $ikh['nama_kantor'] ?>">
                                    </div>
                                </div>

                                <h6 class="text-uppercase text-primary fw-bold mb-3 border-bottom border-primary pb-2"><i class="ki-outline ki-geolocation fs-4 text-primary me-1"></i> Informasi Alamat</h6>
                                <div class="row g-3 mb-8">
                                    <div class="col-12">
                                        <label class="form-label fs-8 fw-semibold text-muted mb-1">Alamat Sesuai KTP</label>
                                        <textarea name="alamat_ktp" class="form-control form-control-sm form-control-solid" rows="2"><?= $ikh['alamat_ktp'] ?></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fs-8 fw-semibold text-muted mb-1">Alamat Korespondensi</label>
                                        <textarea name="alamat_korespondensi" class="form-control form-control-sm form-control-solid" rows="2"><?= $ikh['alamat_korespondensi'] ?></textarea>
                                    </div>
                                </div>

                                <h4 class="fw-bold text-primary mb-7"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Pekerjaan</h4>
                                <div class="row mb-6">
                                    <div class="col-12 col-lg-8">
                                        <label class="form-label fs-8 fw-semibold text-muted mb-1">Daftar Riwayat</label>
                                        <div id="riwayat_container">
                                            <?php
                                            $riwayat_data = $ikh['riwayat_pekerjaan'] ? json_decode($ikh['riwayat_pekerjaan'], true) : old('riwayat_pekerjaan');
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

                                        <button type="button" class="btn btn-light-primary btn-sm mt-2 w-100 w-sm-auto" id="btn_tambah_riwayat">
                                            <i class="ki-outline ki-plus fs-2"></i> Tambah Riwayat Pekerjaan
                                        </button>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end bg-light p-4 rounded border mb-8">
                                    <button type="button" class="btn btn-sm btn-light me-3" id="btn_batal_pemohon">Batal Edit</button>
                                    <button type="submit" class="btn btn-sm btn-primary" id="btn_simpan_pemohon">
                                        <span class="indicator-label"><i class="ki-outline ki-save-2 fs-4"></i> Simpan Data Pemohon</span>
                                        <span class="indicator-progress">Menyimpan... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                            </form>
                            <!-- ========================================== -->


                            <div class="separator border-gray-200 my-8 border-dashed border-2"></div>


                            <!-- ========================================== -->
                            <!-- BAGIAN DOKUMEN TERLAMPIR (DITAMBAHKAN EDIT)-->
                            <!-- ========================================== -->
                            <div class="d-flex justify-content-between align-items-center mb-5">
                                <h4 class="fw-bold mb-0">Dokumen Terlampir</h4>
                                <button class="btn btn-sm btn-light-primary" id="btn_edit_dokumen_peserta">
                                    <i class="ki-outline ki-pencil fs-4"></i> Edit Dokumen
                                </button>
                                <!-- Tombol Batal Edit (Awalnya disembunyikan) -->
                                <button class="btn btn-sm btn-light-danger d-none" id="btn_batal_dokumen_peserta">
                                    <i class="ki-outline ki-cross fs-4"></i> Batal Edit
                                </button>
                            </div>

                            <div class="mh-400px scroll-y px-2 overflow-x-hidden" id="container_dokumen_peserta">
                                <?php
                                $fileConfigs = [
                                    ['id' => 'file_ktp', 'label' => '1. KTP (Scan Asli)', 'accept' => '.pdf', 'hint' => 'Hanya PDF'],
                                    ['id' => 'file_npwp', 'label' => '2. NPWP', 'accept' => '.pdf', 'hint' => 'Hanya PDF'],
                                    ['id' => 'file_kk', 'label' => '3. Kartu Keluarga', 'accept' => '.pdf', 'hint' => 'Hanya PDF'],
                                    ['id' => 'file_foto', 'label' => '4. Pas Foto 4x6', 'accept' => '.jpg,.jpeg,.png', 'hint' => 'JPG/PNG'],
                                    ['id' => 'file_skck', 'label' => '5. SKCK', 'accept' => '.pdf', 'hint' => 'Hanya PDF'],
                                    ['id' => 'file_ijazah', 'label' => '6. Ijazah (Scan Asli)', 'accept' => '.pdf', 'hint' => 'Hanya PDF'],
                                    ['id' => 'file_spt', 'label' => '7. Bukti Terima SPT', 'accept' => '.pdf', 'hint' => 'Hanya PDF'],
                                    ['id' => 'file_sertifikat', 'label' => '8. Sertifikat Brevet Pajak', 'accept' => '.pdf', 'hint' => 'Hanya PDF'],
                                    ['id' => 'file_ttd', 'label' => '9. TTD Elektronik', 'accept' => '.jpg,.jpeg,.png', 'hint' => 'JPG/PNG'],
                                    ['id' => 'file_riwayat_hidup', 'label' => '10. Riwayat Hidup', 'accept' => '.pdf', 'hint' => 'Hanya PDF'],
                                    ['id' => 'file_bukan_pns', 'label' => '11. Pernyataan Bukan PNS', 'accept' => '.pdf', 'hint' => 'Hanya PDF'],
                                    ['id' => 'file_pakta_integritas', 'label' => '12. Integritas', 'accept' => '.pdf', 'hint' => 'Hanya PDF'],
                                    ['id' => 'file_pernyataan_ikh', 'label' => '13. Pernyataan Izin Kuasa Hukum', 'accept' => '.pdf', 'hint' => 'Hanya PDF'],
                                ];

                                $fileAdmin = ['file_riwayat_hidup', 'file_bukan_pns', 'file_pakta_integritas', 'file_pernyataan_ikh'];
                                ?>

                                <div class="row g-5">
                                    <?php foreach ($fileConfigs as $cfg):
                                        $isUploaded = !empty($ikh[$cfg['id']]);
                                        $isAdminProvided = in_array($cfg['id'], $fileAdmin);

                                        $fileUrl = '';
                                        $fileExt = '';
                                        if ($isUploaded) {
                                            $fileData = $ikh[$cfg['id']];
                                            $isDrive = (strpos($fileData, '.') === false);

                                            if ($isDrive) {
                                                $fileUrl = 'https://drive.google.com/file/d/' . $fileData . '/preview';
                                                $fileExt = (strpos($cfg['id'], 'foto') !== false || strpos($cfg['id'], 'ttd') !== false) ? 'jpg' : 'pdf';
                                            } else {
                                                $fileUrl = base_url('uploads/ikh/' . $fileData);
                                                $fileExt = strtolower(pathinfo($fileData, PATHINFO_EXTENSION));
                                            }
                                        }
                                    ?>
                                        <div class="col-12">

                                            <!-- ========================================== -->
                                            <!-- 1. TAMPILAN READ ONLY                      -->
                                            <!-- ========================================== -->
                                            <div class="d-flex align-items-center mb-2 view-text-dokumen">
                                                <div class="symbol symbol-40px me-4">
                                                    <?php if ($isUploaded): ?>
                                                        <a href="javascript:void(0)"
                                                            class="symbol-label bg-light-primary text-primary hover-elevate-up btn-preview-berkas"
                                                            data-file-url="<?= $fileUrl ?>"
                                                            data-file-ext="<?= $fileExt ?>"
                                                            data-file-name="<?= $cfg['label'] ?>"
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
                                                                data-file-name="<?= $cfg['label'] ?>">
                                                                <?= $cfg['label'] ?>
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="fs-6 fw-bold text-gray-800"><?= $cfg['label'] ?></span>
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
                                                            echo $isAdminProvided ? 'File ini akan diurus oleh tim Admin' : 'File belum ada';
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


                                            <!-- ========================================== -->
                                            <!-- 2. TAMPILAN KOTAK EDIT (Upload Per File)   -->
                                            <!-- ========================================== -->
                                            <div class="edit-input-dokumen d-none mt-2 mb-2">
                                                <div class="border rounded p-5 <?= $isUploaded ? 'border-success bg-light-success' : 'border-gray-300' ?>" id="box_<?= $cfg['id'] ?>">

                                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                                        <label class="fw-bold fs-6 text-gray-800"><?= $cfg['label'] ?></label>

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
                                                                <?= $isUploaded ? '<i class="ki-outline ki-check text-white fs-8 me-1"></i> Tersimpan' : 'Belum Upload' ?>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="input-group input-group-sm">
                                                        <input type="file"
                                                            class="form-control <?= $cfg['id'] == 'file_ttd' ? 'input-ttd-crop' : '' ?>"
                                                            id="input_<?= $cfg['id'] ?>"
                                                            data-name="<?= $cfg['id'] ?>"
                                                            accept="<?= $cfg['accept'] ?>">

                                                        <button class="btn btn-primary btn-upload-ajax-admin" type="button" data-target="<?= $cfg['id'] ?>">
                                                            <span class="indicator-label">Upload</span>
                                                            <span class="indicator-progress" style="display:none;">... <span class="spinner-border spinner-border-sm align-middle"></span></span>
                                                        </button>
                                                    </div>
                                                    <div class="form-text mt-2 text-muted fs-8">Format: <?= $cfg['hint'] ?> (Maks 2MB).</div>
                                                </div>
                                            </div>

                                            <div class="separator border-gray-200 my-4"></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <!-- ========================================== -->

                        </div>
                    </div>
                </div>

                <div class="flex-lg-row-fluid">
                    <div class="card card-flush shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title fw-bold">Panel Tindakan Admin</h3>
                        </div>
                        <div class="card-body pt-0">

                            <div class="border rounded p-5 mb-7 <?= $stat_val == 'valid' ? 'border-success bg-light-success' : 'border-primary' ?>">
                                <h4 class="fw-bold text-gray-800 mb-4">1. Validasi Persyaratan
                                    <?php if ($stat_val == 'valid'): ?>
                                        <span class="badge badge-success fs-6"><i class="ki-outline ki-check text-white me-2"></i> Berkas Valid</span>
                                    <?php elseif ($stat_val == 'ditolak'): ?>
                                        <span class="badge badge-danger fs-6"><i class="ki-outline ki-cross text-white me-2"></i> Berkas Revisi</span>
                                    <?php endif; ?>
                                </h4>
                                <form id="form_validasi" class="form-action-admin">
                                    <input type="hidden" name="id_ikh" value="<?= $ikh['id_ikh'] ?>">
                                    <input type="hidden" name="jenis_update" value="validasi">

                                    <div class="mb-5">
                                        <label class="form-label fw-semibold">Keputusan</label>
                                        <select name="status" class="form-select form-select-solid" data-control="select2" data-hide-search="true">
                                            <option value="valid" <?= $stat_val == 'valid' ? 'selected' : '' ?>>TERIMA (Berkas Lengkap & Valid)</option>
                                            <option value="ditolak" <?= $stat_val == 'ditolak' ? 'selected' : '' ?>>REVISI (Ada Berkas Salah)</option>
                                        </select>
                                    </div>

                                    <!-- Tambahkan ID container_catatan di sini -->
                                    <div class="mb-5" id="container_catatan" style="display: none;">
                                        <label class="form-label fw-semibold">Catatan untuk Siswa (Wajib jika direvisi)</label>
                                        <textarea name="catatan_admin" class="form-control form-control-solid" rows="3" placeholder="Contoh: KTP buram, harap foto ulang..."><?= $ikh['catatan_admin'] ?></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 btn-submit-admin">
                                        <span class="indicator-label">Simpan Keputusan</span>
                                        <span class="indicator-progress" style="display:none;">Menyimpan...</span>
                                    </button>
                                </form>
                            </div>

                            <div class="border rounded p-5 border-primary mb-10 <?= !empty($ikh['file_riwayat_hidup']) ? 'border-success bg-light-success' : 'border-primary' ?>">
                                <h4 class="fw-bold text-gray-800 mb-4">2. Berkas Administrasi IKH</h4>

                                <form id="form_upload_berkas" enctype="multipart/form-data">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id_ikh" value="<?= $ikh['id_ikh'] ?>">

                                    <div class="row g-5 mb-7">
                                        <?php
                                        $berkas = [
                                            'file_riwayat_hidup' => [
                                                'label'    => 'Daftar Riwayat Hidup',
                                                'template' => base_url('sw-pic/cetak-pdf/cv/' . encrypt_url($ikh['id_ikh']))
                                            ],
                                            'file_bukan_pns' => [
                                                'label'    => 'Surat Pernyataan Bukan PNS',
                                                'template' => base_url('sw-pic/cetak-pdf/pernyataan-bukan-pns/' . encrypt_url($ikh['id_ikh']))
                                            ],
                                            'file_pakta_integritas' => [
                                                'label'    => 'Pakta Integritas',
                                                'template' => base_url('sw-pic/cetak-pdf/pakta-integritas/' . encrypt_url($ikh['id_ikh']))
                                            ],
                                            'file_pernyataan_ikh' => [
                                                'label'    => 'Surat Pernyataan IKH',
                                                'template' => base_url('sw-pic/cetak-pdf/pernyataan-pengajuan-ikh/' . encrypt_url($ikh['id_ikh']))
                                            ],
                                            'file_skck' => [
                                                'label'    => 'File SKCK (Opsional)',
                                                'template' => ''
                                            ]
                                        ];

                                        foreach ($berkas as $name => $item):
                                            $isUploaded = !empty($ikh[$name]);
                                        ?>
                                            <div class="col-md-6">
                                                <div class="border border-dashed border-gray-300 rounded p-4 h-100 bg-body d-flex flex-column">

                                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                                        <label class="form-label fw-bolder text-dark mb-0 fs-5"><?= $item['label'] ?></label>

                                                        <?php if (!empty($item['template'])): ?>
                                                            <a href="<?= $item['template'] ?>" terget="_blank" class="btn btn-sm btn-light-info fw-bold px-3 py-2" title="Unduh Format untuk ditempel di e-matrai">
                                                                <i class="ki-outline ki-file-down fs-4"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>

                                                    <div class="mt-auto d-flex align-items-center">
                                                        <div class="position-relative me-4">
                                                            <input type="file" name="<?= $name ?>" id="file_<?= $name ?>" class="d-none input-file-custom" accept=".pdf">
                                                            <label for="file_<?= $name ?>"
                                                                class="btn btn-outline btn-outline-dashed p-0 d-flex align-items-center justify-content-center transition-all <?= $isUploaded ? 'border-success bg-light-success text-success' : 'border-primary text-primary btn-active-light-primary' ?>"
                                                                style="width: 70px; height: 70px; border-radius: 12px; cursor: pointer;"
                                                                title="Klik untuk memilih file">
                                                                <i class="ki-outline ki-document <?= $isUploaded ? 'text-success' : 'text-primary' ?> fs-2x"></i>
                                                            </label>

                                                            <?php if ($isUploaded): ?>
                                                                <span class="position-absolute top-0 start-100 translate-middle badge badge-circle badge-success shadow-sm" style="width: 22px; height: 22px;">
                                                                    <i class="ki-outline ki-check text-white fs-8"></i>
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>

                                                        <div class="flex-grow-1">
                                                            <?php if ($isUploaded): ?>
                                                                <?php
                                                                $fileData = $ikh[$name];
                                                                $isDrive = (strpos($fileData, '.') === false);
                                                                $fileUrl = $isDrive ? 'https://drive.google.com/file/d/' . $fileData . '/preview' : base_url("uploads/ikh/" . $fileData);
                                                                $ext = $isDrive ? 'pdf' : strtolower(pathinfo($fileData, PATHINFO_EXTENSION));
                                                                ?>
                                                                <div class="text-success fs-8 fw-bold mb-2"><i class="ki-outline ki-verify fs-6 text-success me-1"></i> Telah Terunggah</div>
                                                                <button type="button" class="btn btn-sm btn-light-success btn-preview-berkas py-2 px-3"
                                                                    data-file-url="<?= $fileUrl ?>"
                                                                    data-file-ext="<?= $ext ?>"
                                                                    data-file-name="<?= $item['label'] ?>">
                                                                    <i class="ki-outline ki-eye fs-5"></i> Lihat Berkas
                                                                </button>
                                                            <?php else: ?>
                                                                <div class="text-muted fs-8 mb-1">Unggah <b><?= $item['label'] ?></b> di sini.</div>
                                                                <div class="text-muted fs-9">Format: .PDF (Maks 2MB)</div>
                                                            <?php endif; ?>

                                                            <div id="filename_<?= $name ?>" class="text-primary fs-8 fw-bold mt-1" style="display: none;"></div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary" id="btn_simpan_berkas">
                                            <span class="indicator-label"><i class="ki-outline ki-cloud-download fs-2"></i> Simpan & Unggah Berkas</span>
                                            <span class="indicator-progress">Mengunggah... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="border rounded p-5 <?= $stat_ser == 'terbit' ? 'border-success bg-light-success' : 'border-primary' ?>">
                                <h4 class="fw-bold text-gray-800 mb-4">3. Terbitkan Kartu IKH</h4>

                                <?php if ($stat_fin != 'selesai'): ?>
                                    <div class="alert alert-warning"><i class="ki-outline ki-information-5 fs-2 text-warning me-2"></i> Selesaikan Tahap Validasi berkas terlebih dahulu untuk membuka kunci fitur ini.</div>
                                <?php else: ?>
                                    <form id="form_upload_kartu">
                                        <input type="hidden" name="id_ikh" value="<?= $ikh['id_ikh'] ?>">
                                        <div class="row g-5 mb-5">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Tanggal Aktif</label>
                                                <input type="date" name="tgl_aktif" class="form-control form-control-solid datepicker-admin" value="<?= $ikh['tgl_aktif'] ?? date('Y-m-d') ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Tanggal Berlaku (Exp)</label>
                                                <input type="date" name="tgl_exp" class="form-control form-control-solid datepicker-admin" value="<?= $ikh['tgl_exp'] ?? date('Y-m-d', strtotime('+1 years')) ?>" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold text-primary">Upload File Kartu IKH <span class="text-muted fs-8">(Bisa pilih banyak file sekaligus)</span></label>
                                                <input type="file" name="file_kartu_ikh[]" class="form-control" accept="image/*, application/pdf" multiple required>

                                                <?php if (!empty($ikh['file_kartu_ikh'])):
                                                    $files = json_decode($ikh['file_kartu_ikh'], true) ?? [];
                                                    if (!empty($files)): ?>
                                                        <div class="mt-3 d-flex flex-wrap gap-2">
                                                            <?php foreach ($files as $index => $file):
                                                                $isDrive = (strpos($file, '.') === false);

                                                                if ($isDrive) {
                                                                    $fileUrl = 'https://drive.google.com/file/d/' . $file . '/preview';
                                                                    $ext = 'pdf';
                                                                } else {
                                                                    $fileUrl = base_url('uploads/ikh/' . $file);
                                                                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                                                }
                                                            ?>
                                                                <div class="symbol symbol-50px symbol-2by3 position-relative">
                                                                    <button type="button" class="btn btn-icon btn-light-primary w-100 h-100 btn-preview-berkas"
                                                                        data-file-url="<?= $fileUrl ?>"
                                                                        data-file-ext="<?= strtolower($ext) ?>"
                                                                        data-file-name="Kartu IKH - <?= $index + 1 ?>">
                                                                        <i class="ki-outline ki-file fs-2x"></i>
                                                                        <span class="fs-9 position-absolute bottom-0 start-50 translate-middle-x pb-1"><?= strtoupper($ext) ?></span>
                                                                    </button>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                <?php endif;
                                                endif; ?>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success w-100" id="btn_terbitkan">
                                            <i class="ki-outline ki-cloud-add fs-2"></i> Terbitkan Dokumen
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
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
                <div id="preview_container" class="d-flex justify-content-center align-items-center w-100 h-100 p-5"></div>
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    $(document).ready(function() {
        $('[data-control="select2"]').select2();

        // Inisialisasi Flatpickr
        $(".datepicker-admin").flatpickr({
            dateFormat: "Y-m-d"
        });

        // =========================================================
        // SCRIPT: FUNGSI TOGGLE EDIT DOKUMEN PESERTA (Admin)
        // =========================================================
        $('#btn_edit_pemohon, #btn_batal_pemohon').on('click', function(e) {
            e.preventDefault();
            $('#view_pemohon').toggleClass('d-none');
            $('#form_edit_pemohon').toggleClass('d-none');
        });
        $('#btn_edit_dokumen_peserta').on('click', function(e) {
            e.preventDefault();
            $('.view-text-dokumen').addClass('d-none'); // Sembunyikan read-only
            $('.edit-input-dokumen').removeClass('d-none'); // Munculkan kotak upload

            $(this).addClass('d-none'); // Sembunyikan tombol 'Edit Dokumen'
            $('#btn_batal_dokumen_peserta').removeClass('d-none'); // Munculkan tombol batal
        });

        // PERBAIKAN: Batal edit tanpa reload halaman
        $('#btn_batal_dokumen_peserta').on('click', function(e) {
            e.preventDefault();
            $('.edit-input-dokumen').addClass('d-none'); // Sembunyikan kotak upload
            $('.view-text-dokumen').removeClass('d-none'); // Munculkan kembali read-only

            $(this).addClass('d-none'); // Sembunyikan tombol 'Batal Edit'
            $('#btn_edit_dokumen_peserta').removeClass('d-none'); // Munculkan tombol edit kembali
        });

        // =========================================================
        // SCRIPT: UPLOAD DOKUMEN SATUAN (Seperti Siswa)
        // =========================================================
        $('.btn-upload-ajax-admin').click(function() {
            const idIkh = '<?= $ikh['id_ikh'] ?>';
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
                url: '<?= base_url('sw-pic/ikh/upload-ajax') ?>', // Pastikan endpoint ini benar
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.csrf_hash) csrfHash = response.csrf_hash;

                    if (response.success) {
                        toastr.success(response.message || "File berhasil diunggah.");

                        // 1. UPDATE TAMPILAN KOTAK EDIT (Warna Hijau & Tombol Tersimpan)
                        $('#box_' + targetId).removeClass('border-gray-300').addClass('border-success bg-light-success');
                        $('#status_' + targetId).removeClass('badge-light-danger').addClass('badge-success').html('<i class="ki-outline ki-check text-white fs-8 me-1"></i> Tersimpan');

                        // Buat URL Blob lokal untuk preview sementara
                        let localFileUrl = URL.createObjectURL(fileData);
                        let newFileExt = fileData.name.split('.').pop().toLowerCase();
                        let fileNameLabel = $('#box_' + targetId).find('label.fw-bold').text().trim();

                        // Update tombol Preview di Form Edit
                        let previewBtnEdit = $('#box_' + targetId).find('.btn-preview-berkas');
                        if (previewBtnEdit.length > 0) {
                            previewBtnEdit.attr('data-file-url', localFileUrl).attr('data-file-ext', newFileExt);
                        } else {
                            let newPreviewBtn = `<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-light-primary hover-elevate-up btn-preview-berkas me-2" data-file-url="${localFileUrl}" data-file-ext="${newFileExt}" data-file-name="${fileNameLabel}" title="Lihat dokumen tersimpan"><i class="ki-outline ki-eye fs-3"></i></a>`;
                            $('#status_' + targetId).before(newPreviewBtn);
                        }

                        // ----------------------------------------------------------------------
                        // 2. PERBAIKAN: UPDATE TAMPILAN READ-ONLY SECARA REALTIME
                        // Agar ketika di-klik "Batal Edit", tampilan awal sudah terupdate
                        // ----------------------------------------------------------------------
                        let readOnlyRow = $('#box_' + targetId).closest('.col-12').find('.view-text-dokumen');

                        // Update Ikon Mata (Symbol)
                        readOnlyRow.find('.symbol').html(`
                            <a href="javascript:void(0)"
                                class="symbol-label bg-light-primary text-primary hover-elevate-up btn-preview-berkas"
                                data-file-url="${localFileUrl}"
                                data-file-ext="${newFileExt}"
                                data-file-name="${fileNameLabel}"
                                title="Klik untuk melihat dokumen">
                                <i class="ki-outline ki-eye fs-2 text-primary"></i>
                            </a>
                        `);

                        // Update Judul & Deskripsi Text
                        let textCol = readOnlyRow.find('.flex-grow-1');
                        textCol.find('.mb-1').html(`
                            <a href="javascript:void(0)"
                                class="fs-6 fw-bold text-gray-800 text-hover-primary btn-preview-berkas"
                                data-file-url="${localFileUrl}"
                                data-file-ext="${newFileExt}"
                                data-file-name="${fileNameLabel}">
                                ${fileNameLabel}
                            </a>
                        `);
                        textCol.find('.text-muted').text('Telah diunggah (Klik untuk lihat)');

                        // Update Badge Status di ujung kanan menjadi "Valid"
                        readOnlyRow.find('> div:last-child').html(`
                            <span class="badge badge-light-success fw-bold px-3 py-2">
                                <i class="ki-outline ki-check-circle fs-5 text-success me-1"></i> Valid
                            </span>
                        `);

                        // Bersihkan input file setelah berhasil
                        $(fileInput).val('');
                    } else {
                        toastr.error(response.message || "Gagal mengunggah file.");
                    }
                },
                error: function() {
                    toastr.error("Terjadi kesalahan jaringan atau tipe file tidak didukung.");
                },
                complete: function() {
                    btn.find('.indicator-label').show();
                    btn.find('.indicator-progress').hide();
                    btn.prop('disabled', false);
                }
            });
        });

        // 1. AJAX Untuk Update Status (Validasi, Proses, Final)
        $('.form-action-admin').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let btn = form.find('.btn-submit-admin');
            let formData = form.serialize() + '&' + csrfName + '=' + csrfHash;

            btn.prop('disabled', true);
            $.post('<?= base_url('sw-pic/ikh/update-status') ?>', formData, function(res) {
                csrfHash = res.csrf_hash;
                if (res.success) {
                    Swal.fire({
                        text: res.message,
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    toastr.error(res.message);
                    btn.prop('disabled', false);
                }
            });
        });

        // 2. AJAX Untuk Upload Kartu IKH
        $('#form_upload_kartu').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            formData.append(csrfName, csrfHash);

            let btn = $('#btn_terbitkan');
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm align-middle ms-2"></span> Mengunggah...');

            $.ajax({
                url: '<?= base_url('sw-pic/ikh/upload-kartu') ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    csrfHash = res.csrf_hash;
                    if (res.success) {
                        Swal.fire({
                            text: res.message,
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        toastr.error(res.message);
                        btn.prop('disabled', false).html('<i class="ki-outline ki-cloud-add fs-2"></i> Terbitkan Dokumen');
                    }
                }
            });
        });

        // 3. Handler Submit Form Berkas Admin
        $('#form_upload_berkas').submit(function(e) {
            e.preventDefault();

            let btn = $('#btn_simpan_berkas');
            let formData = new FormData(this);

            btn.attr('data-kt-indicator', 'on').attr('disabled', true);

            $.ajax({
                url: '<?= base_url("sw-pic/ikh/upload-berkas") ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(res) {
                    let csrfName = '<?= csrf_token() ?>';
                    if (res[csrfName]) {
                        $('input[name="' + csrfName + '"]').val(res[csrfName]);
                    }

                    btn.removeAttr('data-kt-indicator').attr('disabled', false);

                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.msg,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Gagal!', res.msg, 'error');
                    }
                },
                error: function(xhr) {
                    btn.removeAttr('data-kt-indicator').attr('disabled', false);
                    Swal.fire('Error!', 'Terjadi kesalahan sistem atau file terlalu besar.', 'error');
                }
            });
        });

        // Script untuk menampilkan nama file yang dipilih
        $(document).on('change', '.input-file-custom', function(e) {
            let inputId = $(this).attr('id');
            let nameTarget = inputId.replace('file_', '');

            if (this.files && this.files.length > 0) {
                let fileName = this.files[0].name;
                $('#filename_' + nameTarget).text('Pilihan: ' + fileName).slideDown(200);

                $('label[for="' + inputId + '"]').removeClass('border-primary text-primary').addClass('border-info text-info');
                $('label[for="' + inputId + '"] i').removeClass('text-primary').addClass('text-info');
            } else {
                $('#filename_' + nameTarget).hide().text('');

                if (!$('label[for="' + inputId + '"]').hasClass('bg-light-success')) {
                    $('label[for="' + inputId + '"]').removeClass('border-info text-info').addClass('border-primary text-primary');
                    $('label[for="' + inputId + '"] i').removeClass('text-info').addClass('text-primary');
                }
            }
        });

        // Logic Modal Preview Berkas
        $(document).on('click', '.btn-preview-berkas', function(e) {
            e.preventDefault();
            let fileUrl = $(this).data('file-url');
            let fileExt = $(this).data('file-ext');
            let fileName = $(this).data('file-name');

            $('#modal_preview_title').text(fileName);

            if (fileUrl.includes('drive.google.com')) {
                let driveId = fileUrl.split('/d/')[1].split('/preview')[0];
                let downloadUrl = 'https://drive.google.com/uc?export=download&id=' + driveId;
                $('#btn_download_berkas').attr('href', downloadUrl).removeAttr('download').attr('target', '_blank');
            } else {
                $('#btn_download_berkas').attr('href', fileUrl).attr('download', fileName + '.' + fileExt).removeAttr('target');
            }

            $('#preview_container').html('<div class="d-flex justify-content-center p-10"><div class="spinner-border text-primary"></div></div>');
            $('#modal_preview_berkas').modal('show');

            setTimeout(() => {
                if (fileUrl.includes('drive.google.com') || fileExt === 'pdf') {
                    $('#preview_container').html('<iframe src="' + fileUrl + '" width="100%" height="600px" frameborder="0" style="border-radius: 8px;"></iframe>');
                } else if (fileExt === 'jpg' || fileExt === 'jpeg' || fileExt === 'png') {
                    $('#preview_container').html('<div class="text-center"><img src="' + fileUrl + '" class="img-fluid rounded shadow-sm" style="max-height: 600px; object-fit: contain;" /></div>');
                } else {
                    $('#preview_container').html('<div class="text-center text-muted"><i class="ki-outline ki-file fs-5x mb-3"></i><br>Pratinjau tidak tersedia.</div>');
                }
            }, 500);
        });

        $('#modal_preview_berkas').on('hidden.bs.modal', function() {
            $('#preview_container').empty();
        });

    });

    $('#btn_tambah_riwayat').click(function(e) {
        e.preventDefault();
        let barisBaru = `
                <div class="input-group mb-3 riwayat-row" style="display: none;">
                    <input type="text" name="riwayat_pekerjaan[]" class="form-control form-control-lg form-control-solid" placeholder="Contoh: PT Contoh (2021 - Sekarang)" />
                    <button type="button" class="btn btn-icon btn-light-danger btn-hapus-riwayat" title="Hapus Baris">
                        <i class="ki-outline ki-trash fs-2"></i>
                    </button>
                </div>
            `;
        let el = $(barisBaru);
        $('#riwayat_container').append(el);
        el.slideDown('fast');
    });

    // Fungsi Hapus Baris (Event Delegation)
    $(document).on('click', '.btn-hapus-riwayat', function(e) {
        e.preventDefault();
        let baris = $(this).closest('.riwayat-row');
        baris.slideUp('fast', function() {
            $(this).remove();
        });
    });

    // =========================================================
    // SCRIPT: TOGGLE CATATAN VALIDASI ADMIN
    // =========================================================
    // Fungsi untuk mengecek dan mengatur tampilan catatan
    function toggleCatatanValidasi() {
        let statusVal = $('select[name="status"]').val();
        if (statusVal === 'ditolak') {
            $('#container_catatan').slideDown('fast'); // Munculkan dengan animasi
        } else {
            $('#container_catatan').slideUp('fast'); // Sembunyikan
            // Opsional: Kosongkan isi catatan jika diubah kembali ke TERIMA
            // $('textarea[name="catatan_admin"]').val(''); 
        }
    }

    // 1. Jalankan saat halaman pertama kali dimuat (untuk menyesuaikan dengan data awal)
    toggleCatatanValidasi();

    // 2. Jalankan saat dropdown diubah
    // Karena Anda menggunakan Select2, kita tangkap event change-nya
    $('select[name="status"]').on('change', function() {
        toggleCatatanValidasi();
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

    $('#btn_tambah_riwayat').click(function(e) {
        e.preventDefault();
        let barisBaru = `
                <div class="input-group mb-3 riwayat-row" style="display: none;">
                    <input type="text" name="riwayat_pekerjaan[]" class="form-control" placeholder="Contoh: PT Contoh (2021 - Sekarang)" />
                    <button type="button" class="btn btn-icon btn-light-danger btn-hapus-riwayat" title="Hapus Baris">
                        <i class="ki-outline ki-trash fs-2"></i>
                    </button>
                </div>
            `;
        let el = $(barisBaru);
        $('#riwayat_container').append(el);
        el.slideDown('fast');
    });

    // Fungsi Hapus Baris (Event Delegation)
    $(document).on('click', '.btn-hapus-riwayat', function(e) {
        e.preventDefault();
        let baris = $(this).closest('.riwayat-row');
        baris.slideUp('fast', function() {
            $(this).remove();
        });
    });

    // =========================================================
    // SCRIPT BARU: GENERATE & UPLOAD SERTIFIKAT OTOMATIS
    // =========================================================
    $('.btn-generate-sertifikat').click(function() {
        let btn = $(this);
        let targetId = btn.data('target');
        const idIkh = '<?= $ikh['id_ikh'] ?>';
        let csrfName = '<?= csrf_token() ?>';
        let csrfHash = '<?= csrf_hash() ?>';
        Swal.fire({
            title: 'Gunakan Sertifikat Sistem?',
            text: "Sistem akan membuat sertifikat kelulusan Anda dan otomatis mengunggahnya ke server.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Ya, Buat & Unggah',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.find('.indicator-label').hide();
                btn.find('.indicator-progress').show();
                btn.prop('disabled', true);

                let formData = new FormData();
                formData.append('id_ikh', idIkh);
                formData.append(csrfName, csrfHash);

                $.ajax({
                    url: '<?= base_url('sw-siswa/ikh/generate-sertifikat-drive') ?>', // Pastikan rute ini benar
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.csrf_hash) csrfHash = response.csrf_hash;

                        if (response.success) {
                            toastr.success(response.message);

                            // Update tampilan kotak menjadi hijau (Tersimpan)
                            $('#box_' + targetId).removeClass('border-gray-300').addClass('border-success bg-light-success');
                            $('#status_' + targetId).removeClass('badge-light-danger').addClass('badge-success').html('<i class="ki-outline ki-check text-white fs-5 me-1"></i> Tersimpan');

                            // Update tombol Preview
                            let fileNameLabel = $('#box_' + targetId).find('label.fw-bold').text().trim();
                            let previewBtn = $('#box_' + targetId).find('.btn-preview-berkas');

                            if (previewBtn.length > 0) {
                                previewBtn.attr('data-file-url', response.file_url).attr('data-file-ext', 'pdf');
                            } else {
                                let newPreviewBtn = `<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-light-primary hover-elevate-up btn-preview-berkas me-2" data-file-url="${response.file_url}" data-file-ext="pdf" data-file-name="${fileNameLabel}" title="Lihat dokumen tersimpan"><i class="ki-outline ki-eye fs-3"></i></a>`;
                                $('#status_' + targetId).before(newPreviewBtn);
                            }
                        } else {
                            Swal.fire('Gagal!', response.message, 'error');
                        }
                    },
                    complete: function() {
                        btn.find('.indicator-label').show();
                        btn.find('.indicator-progress').hide();
                        btn.prop('disabled', false);
                    }
                });
            }
        });
    });
</script>
<?= $this->endSection(); ?>
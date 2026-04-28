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

                            <h6 class="text-uppercase text-muted fw-bold mb-3">Identitas Diri</h6>
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
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold text-gray-500 mb-1">Alamat Korespondensi</span>
                                    <span class="fw-bold text-gray-800"><?= $ikh['alamat_korespondensi'] ?></span>
                                </div>
                            </div>

                            <div class="separator border-gray-200 my-8 border-dashed border-2"></div>

                            <h4 class="fw-bold mb-5">Dokumen Terlampir</h4>
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

                                    // =========================================================
                                    // PERBAIKAN 1: Deteksi Link G-Drive (Dokumen Terlampir)
                                    // =========================================================
                                    $fileUrl = '';
                                    $fileExt = '';
                                    if ($isUploaded) {
                                        $fileData = $ikh[$berkas['field']];
                                        $isDrive = (strpos($fileData, '.') === false); // ID Google Drive tidak memiliki titik

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
                                        <span class="badge badge-danger fs-6"><i class="ki-outline ki-cross text-white me-2"></i> Berkas Ditolak</span>
                                    <?php endif; ?>
                                </h4>
                                <form id="form_validasi" class="form-action-admin">
                                    <input type="hidden" name="id_ikh" value="<?= $ikh['id_ikh'] ?>">
                                    <input type="hidden" name="jenis_update" value="validasi">

                                    <div class="mb-5">
                                        <label class="form-label fw-semibold">Keputusan</label>
                                        <select name="status" class="form-select form-select-solid" data-control="select2" data-hide-search="true">
                                            <option value="valid" <?= $stat_val == 'valid' ? 'selected' : '' ?>>TERIMA (Berkas Lengkap & Valid)</option>
                                            <option value="ditolak" <?= $stat_val == 'ditolak' ? 'selected' : '' ?>>TOLAK (Ada Berkas Salah)</option>
                                        </select>
                                    </div>
                                    <div class="mb-5">
                                        <label class="form-label fw-semibold">Catatan untuk Siswa (Wajib jika ditolak)</label>
                                        <textarea name="catatan_admin" class="form-control form-control-solid" rows="3" placeholder="Contoh: KTP buram, harap foto ulang..."><?= $ikh['catatan_admin'] ?></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 btn-submit-admin"><span class="indicator-label">Simpan Keputusan</span><span class="indicator-progress" style="display:none;">Menyimpan...</span></button>
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
                                            'file_riwayat_hidup'  => 'Daftar Riwayat Hidup',
                                            'file_bukan_pns'      => 'Surat Pernyataan Bukan PNS',
                                            'file_pakta_integritas' => 'Pakta Integritas',
                                            'file_pernyataan_ikh' => 'Surat Pernyataan IKH',
                                            'file_skck'           => 'File SKCK (Opsional)'
                                        ];

                                        foreach ($berkas as $name => $label):
                                        ?>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold"><?= $label ?></label>
                                                <div class="input-group input-group-solid">
                                                    <input type="file" name="<?= $name ?>" class="form-control" accept=".pdf">

                                                    <?php if (!empty($ikh[$name])): ?>
                                                        <?php
                                                        // =========================================================
                                                        // PERBAIKAN 2: Deteksi Link G-Drive (Berkas Admin IKH)
                                                        // =========================================================
                                                        $fileData = $ikh[$name];
                                                        $isDrive = (strpos($fileData, '.') === false);

                                                        if ($isDrive) {
                                                            $fileUrl = 'https://drive.google.com/file/d/' . $fileData . '/preview';
                                                            $ext = 'pdf'; // Karena form input hanya menerima .pdf
                                                        } else {
                                                            $fileUrl = base_url("uploads/ikh/" . $fileData);
                                                            $ext = strtolower(pathinfo($fileData, PATHINFO_EXTENSION));
                                                        }
                                                        ?>
                                                        <button type="button" class="btn btn-icon btn-light-primary btn-preview-berkas"
                                                            data-file-url="<?= $fileUrl ?>"
                                                            data-file-ext="<?= $ext ?>"
                                                            data-file-name="<?= $label ?>">
                                                            <i class="ki-outline ki-eye fs-2"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="form-text text-muted fs-8">Format: .PDF (Max 2MB)</div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <button type="submit" class="btn btn-primary" id="btn_simpan_berkas">
                                        <span class="indicator-label"><i class="ki-outline ki-check-circle fs-2"></i> Simpan & Perbarui Berkas</span>
                                        <span class="indicator-progress">Mohon tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
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
                                                                // =========================================================
                                                                // PERBAIKAN 3: Deteksi Link G-Drive (File Kartu IKH)
                                                                // =========================================================
                                                                $isDrive = (strpos($file, '.') === false);
                                                                
                                                                if ($isDrive) {
                                                                    $fileUrl = 'https://drive.google.com/file/d/' . $file . '/preview';
                                                                    $ext = 'pdf'; // Default tampilan teks ekstensi
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

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        $('[data-control="select2"]').select2();

        // Inisialisasi Flatpickr untuk Input Tanggal Admin
        $(".datepicker-admin").flatpickr({
            dateFormat: "Y-m-d"
        });

        let csrfName = '<?= csrf_token() ?>';
        let csrfHash = '<?= csrf_hash() ?>';

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

        // =========================================================
        // PERBAIKAN 4: Logic Modal Preview Berkas (Dibersihkan dari duplikasi)
        // =========================================================
        $(document).on('click', '.btn-preview-berkas', function(e) {
            e.preventDefault();
            let fileUrl = $(this).data('file-url');
            let fileExt = $(this).data('file-ext');
            let fileName = $(this).data('file-name');

            $('#modal_preview_title').text(fileName);
            
            // Logika Link Unduh (Support Google Drive Export)
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
                // Gunakan iFrame untuk membaca Google Drive dan PDF lokal
                if (fileUrl.includes('drive.google.com') || fileExt === 'pdf') {
                    $('#preview_container').html('<iframe src="' + fileUrl + '" width="100%" height="600px" frameborder="0" style="border-radius: 8px;"></iframe>');
                } else if (fileExt === 'jpg' || fileExt === 'jpeg' || fileExt === 'png') {
                    $('#preview_container').html('<div class="text-center"><img src="' + fileUrl + '" class="img-fluid rounded shadow-sm" style="max-height: 600px; object-fit: contain;" /></div>');
                } else {
                    $('#preview_container').html('<div class="text-center text-muted"><i class="ki-outline ki-file fs-5x mb-3"></i><br>Pratinjau tidak tersedia.</div>');
                }
            }, 500);
        });

        // Hapus sisa iFrame saat modal ditutup agar tidak membebani memori
        $('#modal_preview_berkas').on('hidden.bs.modal', function () {
            $('#preview_container').empty();
        });

    });
</script>
<?= $this->endSection(); ?>
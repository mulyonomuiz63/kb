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
                                    ['nama' => '1. KTP', 'field' => 'file_ktp'],
                                    ['nama' => '2. NPWP', 'field' => 'file_npwp'],
                                    ['nama' => '3. Kartu Keluarga', 'field' => 'file_kk'],
                                    ['nama' => '4. Foto', 'field' => 'file_foto'],
                                    ['nama' => '5. SKCK', 'field' => 'file_skck'],
                                    ['nama' => '6. Ijazah', 'field' => 'file_ijazah'],
                                    ['nama' => '7. SPT', 'field' => 'file_spt'],
                                    ['nama' => '8. Brevet', 'field' => 'file_sertifikat'],
                                    ['nama' => '9. TTD', 'field' => 'file_ttd'],
                                ];
                                foreach ($berkasList as $berkas):
                                    $isUploaded = !empty($ikh[$berkas['field']]);
                                    if ($isUploaded):
                                        $fileUrl = base_url('uploads/ikh/' . $ikh[$berkas['field']]);
                                        $fileExt = strtolower(pathinfo($ikh[$berkas['field']], PATHINFO_EXTENSION));
                                ?>
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="symbol symbol-40px me-3">
                                                <a href="javascript:void(0)" class="symbol-label bg-light-primary text-primary btn-preview-berkas hover-elevate-up" data-file-url="<?= $fileUrl ?>" data-file-ext="<?= $fileExt ?>" data-file-name="<?= $berkas['nama'] ?>" title="Pratinjau File">
                                                    <i class="ki-outline ki-eye fs-2"></i>
                                                </a>
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1">
                                                <a href="javascript:void(0)" class="fs-6 fw-bold text-gray-800 text-hover-primary btn-preview-berkas" data-file-url="<?= $fileUrl ?>" data-file-ext="<?= $fileExt ?>" data-file-name="<?= $berkas['nama'] ?>"><?= $berkas['nama'] ?></a>
                                                <span class="text-muted fw-semibold fs-8 text-uppercase"><?= $fileExt ?></span>
                                            </div>
                                        </div>
                                <?php endif;
                                endforeach; ?>
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

                            <div class="border rounded p-5 <?= $stat_ser == 'terbit' ? 'border-success bg-light-success' : 'border-primary' ?>">
                                <h4 class="fw-bold text-gray-800 mb-4">2. Terbitkan Kartu IKH</h4>

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
                                                <label class="form-label fw-semibold">Upload File Kartu IKH</label>
                                                <input type="file" name="file_kartu_ikh" class="form-control" accept="image/*, application/pdf" required>
                                                <?php if (!empty($ikh['file_kartu_ikh'])): ?>
                                                    <div class="mt-2"><a href="<?= base_url('uploads/ikh/' . $ikh['file_kartu_ikh']) ?>" target="_blank" class="badge badge-light-success">Lihat Kartu Tersimpan</a></div>
                                                <?php endif; ?>
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
            $.post('<?= base_url('sw-admin/ikh/update-status') ?>', formData, function(res) {
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
                url: '<?= base_url('sw-admin/ikh/upload-kartu') ?>',
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


        // 3. Logic Modal Preview Berkas
        $('.btn-preview-berkas').on('click', function(e) {
            e.preventDefault();
            let fileUrl = $(this).data('file-url');
            let fileExt = $(this).data('file-ext');
            let fileName = $(this).data('file-name');

            // Ubah judul modal
            $('#modal_preview_title').text(fileName);

            // PERBAIKAN PENTING: Ubah link tujuan tombol download
            $('#btn_download_berkas').attr('href', fileUrl);
            // Opsional: Buat nama file rapi saat terdownload
            $('#btn_download_berkas').attr('download', fileName + '.' + fileExt);

            $('#preview_container').html('<div class="spinner-border text-primary"></div>');
            new bootstrap.Modal(document.getElementById('modal_preview_berkas')).show();

            setTimeout(() => {
                if (fileExt === 'pdf') {
                    $('#preview_container').html('<embed src="' + fileUrl + '" type="application/pdf" width="100%" height="700px" />');
                } else if (fileExt === 'jpg' || fileExt === 'jpeg' || fileExt === 'png') {
                    $('#preview_container').html('<img src="' + fileUrl + '" class="img-fluid rounded" style="max-height: 700px; object-fit: contain;" />');
                }
            }, 500);
        });
    });
</script>
<?= $this->endSection(); ?>
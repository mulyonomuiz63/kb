<?= $this->extend('siswa/template/app'); ?>

<?= $this->section('content'); ?>
<?php
$hasData = !empty($ikh);
$idIkh   = $hasData ? $ikh['id_ikh'] : '';
$isDraft = $hasData && ($ikh['status_validasi_admin'] == 'draft');

// Buka tab lampiran secara default jika data teks sudah pernah disimpan
$activeTab = isset($_GET['tab']) && $_GET['tab'] == 'lampiran' ? 'lampiran' : 'data';
?>

<div class="d-flex flex-column flex-column-fluid py-3 py-lg-6 mt-8">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6 mb-8">
                <i class="ki-outline ki-document text-primary fs-2tx me-4"></i>
                <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                    <div class="mb-3 mb-md-0 fw-semibold">
                        <h4 class="text-gray-900 fw-bold">Pendaftaran Izin Kuasa Hukum (IKH)</h4>
                        <div class="fs-6 text-gray-700 pe-7">Sistem kami menyimpan data secara otomatis. Simpan Data Diri terlebih dahulu, kemudian unggah dokumen satu per satu untuk menghindari kegagalan jaringan.</div>
                    </div>
                </div>
            </div>

            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 <?= $activeTab == 'data' ? 'active' : '' ?>" data-bs-toggle="tab" href="#tab_data_diri">
                        1. Data Diri & Pernyataan
                    </a>
                </li>
                <li class="nav-item">
                    <?php if($hasData): ?>
                        <a class="nav-link text-active-primary pb-4 <?= $activeTab == 'lampiran' ? 'active' : '' ?>" data-bs-toggle="tab" href="#tab_lampiran">
                            2. Unggah Lampiran
                        </a>
                    <?php else: ?>
                        <a class="nav-link text-muted pb-4 disabled" href="javascript:;" title="Simpan data diri terlebih dahulu">
                            <i class="ki-outline ki-lock fs-4"></i> 2. Unggah Lampiran
                        </a>
                    <?php endif; ?>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                
                <div class="tab-pane fade <?= $activeTab == 'data' ? 'show active' : '' ?>" id="tab_data_diri" role="tabpanel">
                    <div class="card shadow-sm">
                        <form action="<?= base_url('sw-siswa/perijinan-ikh/store') ?>" method="POST" id="form_data_diri">
                            <?= csrf_field() ?>
                            <?php if($hasData): ?>
                                <input type="hidden" name="id_ikh" value="<?= $idIkh ?>">
                            <?php endif; ?>

                            <div class="card-body p-9">
                                <div class="row g-5">
                                    <div class="col-md-6">
                                        <label class="required form-label">NIK (Nomor Induk Kependudukan)</label>
                                        <input type="number" name="nik" class="form-control" value="<?= $hasData ? $ikh['nik'] : '' ?>" required />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="required form-label">NPWP</label>
                                        <input type="text" name="npwp" class="form-control" value="<?= $hasData ? $ikh['npwp'] : '' ?>" required />
                                    </div>
                                    <div class="col-md-12">
                                        <label class="required form-label">Nama Lengkap (Sesuai KTP/Ijazah)</label>
                                        <input type="text" name="nama_lengkap" class="form-control" value="<?= $hasData ? $ikh['nama_lengkap'] : '' ?>" required />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="required form-label">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" class="form-control" value="<?= $hasData ? $ikh['tempat_lahir'] : '' ?>" required />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="required form-label">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" class="form-control" value="<?= $hasData ? $ikh['tanggal_lahir'] : '' ?>" required />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="required form-label">Pendidikan Terakhir</label>
                                        <select name="pendidikan_terakhir" class="form-select" data-control="select2" required>
                                            <option></option>
                                            <?php $pend = $hasData ? $ikh['pendidikan_terakhir'] : ''; ?>
                                            <option value="D3" <?= $pend == 'D3' ? 'selected' : '' ?>>D3</option><option value="D4" <?= $pend == 'D4' ? 'selected' : '' ?>>D4</option><option value="S1" <?= $pend == 'S1' ? 'selected' : '' ?>>S1</option><option value="S2" <?= $pend == 'S2' ? 'selected' : '' ?>>S2</option><option value="S3" <?= $pend == 'S3' ? 'selected' : '' ?>>S3</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="required form-label">Nomor WhatsApp</label>
                                        <input type="number" name="no_wa" class="form-control" value="<?= $hasData ? $ikh['no_wa'] : '' ?>" required />
                                    </div>
                                    <div class="col-md-12">
                                        <label class="required form-label">Email Aktif</label>
                                        <input type="email" name="email" class="form-control" value="<?= $hasData ? $ikh['email'] : '' ?>" required />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="required form-label">Kategori Kantor</label>
                                        <select name="kategori_kantor" class="form-select" data-control="select2" required>
                                            <option></option>
                                            <?php $kat = $hasData ? $ikh['kategori_kantor'] : ''; ?>
                                            <option value="Firma Hukum" <?= $kat == 'Firma Hukum' ? 'selected' : '' ?>>Firma Hukum</option><option value="KAP" <?= $kat == 'KAP' ? 'selected' : '' ?>>KAP</option><option value="KKP" <?= $kat == 'KKP' ? 'selected' : '' ?>>KKP</option><option value="Mandiri" <?= $kat == 'Mandiri' ? 'selected' : '' ?>>Mandiri</option><option value="Lainnya" <?= $kat == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="required form-label">Nama Kantor</label>
                                        <input type="text" name="nama_kantor" class="form-control" value="<?= $hasData ? $ikh['nama_kantor'] : '' ?>" required />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="required form-label">Alamat Sesuai KTP</label>
                                        <textarea name="alamat_ktp" class="form-control" rows="3" required><?= $hasData ? $ikh['alamat_ktp'] : '' ?></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="required form-label">Alamat Korespondensi</label>
                                        <textarea name="alamat_korespondensi" class="form-control" rows="3" required><?= $hasData ? $ikh['alamat_korespondensi'] : '' ?></textarea>
                                    </div>
                                </div>

                                <div class="separator my-10"></div>
                                <h3 class="fw-bold fs-4 mb-5">Lembar Pernyataan (Pengganti E-Materai)</h3>
                                <div class="d-flex flex-column gap-4">
                                    <div class="form-check form-check-custom form-check-solid form-check-success p-3 rounded border border-gray-300">
                                        <input class="form-check-input" type="checkbox" name="check_pns" id="check_pns" <?= ($hasData && $ikh['is_bukan_pns']) ? 'checked' : '' ?> required />
                                        <label class="form-check-label fw-bold cursor-pointer" for="check_pns">TIDAK BERSTATUS PNS <span class="text-danger">(WAJIB E-MATERAI)</span></label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid form-check-success p-3 rounded border border-gray-300">
                                        <input class="form-check-input" type="checkbox" name="check_pakta" id="check_pakta" <?= ($hasData && $ikh['is_pakta_integritas']) ? 'checked' : '' ?> required />
                                        <label class="form-check-label fw-bold cursor-pointer" for="check_pakta">PAKTA INTEGRITAS <span class="text-danger">(WAJIB E-MATERAI)</span></label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid form-check-success p-3 rounded border border-gray-300">
                                        <input class="form-check-input" type="checkbox" name="check_pengajuan" id="check_pengajuan" <?= ($hasData && $ikh['is_pernyataan_ikh']) ? 'checked' : '' ?> required />
                                        <label class="form-check-label fw-bold cursor-pointer" for="check_pengajuan">PERNYATAAN PENGAJUAN IKH <span class="text-danger">(WAJIB E-MATERAI)</span></label>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ki-outline ki-save-2 fs-2"></i> Simpan Data Diri & Lanjut Upload
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="tab-pane fade <?= $activeTab == 'lampiran' ? 'show active' : '' ?>" id="tab_lampiran" role="tabpanel">
                    <div class="card shadow-sm">
                        <div class="card-header border-0 pt-6">
                            <h3 class="card-title">Unggah Dokumen Persyaratan</h3>
                        </div>
                        <div class="card-body p-9">
                            
                            <?php if($isDraft): ?>
                            <div class="alert alert-warning mb-8">
                                <strong>Peringatan!</strong> Pendaftaran Anda belum terkirim. Anda harus menyelesaikan proses upload untuk ke-10 berkas di bawah ini agar berkas bisa dikirim ke Admin.
                            </div>
                            <?php endif; ?>

                            <div class="row g-7">
                                <?php
                                // Array Konfigurasi File
                                $fileConfigs = [
                                    ['id' => 'file_ktp', 'label' => '1. KTP (Scan Asli)', 'accept' => '.jpg,.jpeg,.png', 'hint' => 'JPG/PNG'],
                                    ['id' => 'file_npwp', 'label' => '2. NPWP', 'accept' => '.jpg,.jpeg,.png', 'hint' => 'JPG/PNG'],
                                    ['id' => 'file_kk', 'label' => '3. Kartu Keluarga', 'accept' => '.jpg,.jpeg,.png', 'hint' => 'JPG/PNG'],
                                    ['id' => 'file_foto', 'label' => '4. Pas Foto 4x6', 'accept' => '.jpg,.jpeg,.png', 'hint' => 'JPG/PNG'],
                                    ['id' => 'file_skck', 'label' => '5. SKCK', 'accept' => '.pdf', 'hint' => 'Hanya PDF', 'danger' => true],
                                    ['id' => 'file_ijazah', 'label' => '6. IJAZAH (ASLI)', 'accept' => '.pdf', 'hint' => 'Hanya PDF', 'danger' => true],
                                    ['id' => 'file_spt', 'label' => '7. BUKTI TANDA TERIMA SPT 2 TAHUN TERAKHIR', 'accept' => '.pdf', 'hint' => 'Hanya PDF', 'danger' => true],
                                    ['id' => 'file_cv', 'label' => '8. DAFTAR RIWAYAT HIDUP (FORMAT KHUSUS)', 'accept' => '.pdf', 'hint' => 'Hanya PDF', 'danger' => true],
                                    ['id' => 'file_sertifikat', 'label' => '9. SERTIFIKAT BREVET PAJAK AB', 'accept' => '.pdf', 'hint' => 'Hanya PDF', 'danger' => true],
                                    ['id' => 'file_ttd', 'label' => '10. TTD ELEKTRONIK', 'accept' => '.pdf', 'hint' => 'Hanya PDF', 'danger' => true],
                                ];
                                ?>

                                <?php foreach($fileConfigs as $cfg): 
                                    $isUploaded = $hasData && !empty($ikh[$cfg['id']]);
                                ?>
                                <div class="col-md-6">
                                    <div class="border rounded p-5 <?= $isUploaded ? 'border-success bg-light-success' : 'border-gray-300' ?>" id="box_<?= $cfg['id'] ?>">
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <label class="fw-bold fs-5 text-gray-800 <?= isset($cfg['danger']) ? 'text-danger' : '' ?>">
                                                <?= $cfg['label'] ?>
                                            </label>
                                            
                                            <span class="badge status-badge <?= $isUploaded ? 'badge-success' : 'badge-light-danger' ?>" id="status_<?= $cfg['id'] ?>">
                                                <?= $isUploaded ? '<i class="ki-outline ki-check text-white fs-5 me-1"></i> Tersimpan' : 'Belum Upload' ?>
                                            </span>
                                        </div>

                                        <div class="input-group input-group-sm">
                                            <input type="file" class="form-control file-input-ajax" 
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
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('[data-control="select2"]').select2();

        const idIkh = '<?= $idIkh ?>';
        const csrfHash = '<?= csrf_hash() ?>'; // Dapatkan token awal
        // Event Listener untuk tombol Upload AJAX
        $('.btn-upload-ajax').click(function() {
            let targetId = $(this).data('target'); // contoh: 'file_ktp'
            let fileInput = $('#input_' + targetId)[0];
            let btn = $(this);
            
            // Validasi: Pastikan ada file yang dipilih
            if (fileInput.files.length === 0) {
                toastr.warning("Silakan pilih file terlebih dahulu.");
                return;
            }

            let fileData = fileInput.files[0];
            let formData = new FormData();
            
            formData.append('file_dokumen', fileData);
            formData.append('input_name', targetId);
            formData.append('id_ikh', idIkh);
            formData.append('<?= csrf_token() ?>', csrfHash); // Kirim CSRF Token
            
            // Animasi Loading
            btn.find('.indicator-label').hide();
            btn.find('.indicator-progress').show();
            btn.prop('disabled', true);

            // Eksekusi AJAX (JQuery)
            $.ajax({
                url: '<?= base_url('sw-siswa/perijinan-ikh/upload-ajax') ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if(response.success) {
                        toastr.success(response.message);
                        
                        // Update UI Box menjadi hijau (Sukses)
                        $('#box_' + targetId).removeClass('border-gray-300').addClass('border-success bg-light-success');
                        $('#status_' + targetId).removeClass('badge-light-danger').addClass('badge-success')
                                               .html('<i class="ki-outline ki-check text-white fs-5 me-1"></i> Tersimpan');
                        
                        // Kosongkan input file agar bersih
                        $('#input_' + targetId).val('');
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    // Jika token CSRF kadaluarsa / Error Server
                    toastr.error("Terjadi kesalahan jaringan atau ekstensi tidak didukung.");
                },
                complete: function() {
                    // Matikan loading
                    btn.find('.indicator-label').show();
                    btn.find('.indicator-progress').hide();
                    btn.prop('disabled', false);
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>
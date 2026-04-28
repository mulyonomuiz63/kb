<?= $this->extend('template/app'); ?>
<?= $this->section('styles'); ?>
<style>
    /* Custom CSS untuk halaman create artikel */
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
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="card shadow-sm border-0">
                <div class="card-header pt-7 pb-0 border-bottom">
                    <div class="card-title flex-column">
                        <h2 class="fw-bold mb-2">Konfigurasi Aplikasi</h2>
                    </div>
                    
                    <div class="card-toolbar">
                        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold" role="tablist">
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5 active" data-bs-toggle="tab" href="#tab_general">General</a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#tab_sosial_media">Sosial Media</a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#tab_smtp">SMTP</a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#tab_seo">SEO</a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#tab_recaptcha">reCAPTCHA</a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#tab_google">Google</a>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#tab_midtrans">Midtrans</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <form action="<?= base_url('sw-admin/settings/update') ?>" method="post" enctype="multipart/form-data" class="form">
                    <?= csrf_field() ?>
                    
                    <div class="card-body pt-10 pb-5">
                        <div class="tab-content">

                            <?php
                            // Fungsi Helper Custom Row untuk Layout Metronic
                            function inputRow($label, $input) {
                                echo '
                                <div class="row mb-8">
                                    <label class="col-lg-3 col-form-label fw-semibold fs-6 text-lg-end">'.$label.'</label>
                                    <div class="col-lg-7 fv-row">'.$input.'</div>
                                </div>';
                            }
                            ?>

                            <div class="tab-pane fade show active" id="tab_general" role="tabpanel">
                                <div class="d-flex flex-column gap-5">
                                    <h4 class="text-gray-800 fw-bold mb-5">Informasi Perusahaan</h4>

                                    <?php inputRow('Nama Aplikasi',
                                        '<input type="text" class="form-control form-control-solid" name="app_name" value="'.old('app_name', $settings['app_name'] ?? '').'" required>'
                                    ); ?>

                                    <?php inputRow('Favicon Aplikasi',
                                        '<input type="file" class="form-control form-control-solid mb-3" name="app_icon">' .
                                        (!empty($settings['app_icon']) ? '<div class="mt-2">'.img_lazy('uploads/app-icon/'.$settings['app_icon'],'-',['class'=>'rounded border border-gray-300 p-1','width'=>60]).'</div>' : '')
                                    ); ?>

                                    <?php inputRow('Logo Perusahaan',
                                        '<input type="file" class="form-control form-control-solid mb-3" name="logo_perusahaan">' .
                                        (!empty($settings['logo_perusahaan']) ? '<div class="mt-2">'.img_lazy('uploads/app-icon/'.$settings['logo_perusahaan'],'-',['class'=>'rounded border border-gray-300 p-1','width'=>80]).'</div>' : '')
                                    ); ?>

                                    <?php inputRow('Email Perusahaan',
                                        '<input type="email" class="form-control form-control-solid" name="app_email" value="'.old('app_email', $settings['app_email'] ?? '').'" required>'
                                    ); ?>

                                    <?php inputRow('Telepon',
                                        '<input type="text" class="form-control form-control-solid" name="app_phone" value="'.old('app_phone', $settings['app_phone'] ?? '').'">'
                                    ); ?>

                                    <?php inputRow('Alamat Lengkap',
                                        '<input type="text" class="form-control form-control-solid" name="alamat_perusahaan" value="'.old('alamat_perusahaan', $settings['alamat_perusahaan'] ?? '').'">'
                                    ); ?>

                                    <?php inputRow('Google Maps',
                                        '<input type="text" class="form-control form-control-solid mb-2" name="google_maps" value="'.old('google_maps', $settings['google_maps'] ?? '').'">
                                         <div class="text-muted fs-7"><i class="ki-duotone ki-information-5 text-gray-500 fs-6 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> Copy hanya isi <code>src="..."</code> dari embed iframe Google Maps.</div>'
                                    ); ?>

                                    <?php inputRow('Tahun Berdiri',
                                        '<input type="text" class="form-control form-control-solid" name="tahun_berdiri" value="'.old('tahun_berdiri', $settings['tahun_berdiri'] ?? '').'">'
                                    ); ?>

                                    <?php inputRow('Versi Aplikasi',
                                        '<input type="text" class="form-control form-control-solid" name="app_versi" value="'.old('app_versi', $settings['app_versi'] ?? '').'">'
                                    ); ?>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab_sosial_media" role="tabpanel">
                                <div class="d-flex flex-column gap-5">
                                    <h4 class="text-gray-800 fw-bold mb-5">Tautan Sosial Media</h4>

                                    <?php inputRow('Facebook','<input type="text" class="form-control form-control-solid" name="footer_facebook" value="'.old('footer_facebook',$settings['footer_facebook']??'').'">'); ?>
                                    <?php inputRow('Instagram','<input type="text" class="form-control form-control-solid" name="footer_instagram" value="'.old('footer_instagram',$settings['footer_instagram']??'').'">'); ?>
                                    <?php inputRow('Youtube','<input type="text" class="form-control form-control-solid" name="footer_youtube" value="'.old('footer_youtube',$settings['footer_youtube']??'').'">'); ?>
                                    <?php inputRow('LinkedIn','<input type="text" class="form-control form-control-solid" name="footer_linkedin" value="'.old('footer_linkedin',$settings['footer_linkedin']??'').'">'); ?>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab_smtp" role="tabpanel">
                                <div class="d-flex flex-column gap-5">
                                    <h4 class="text-gray-800 fw-bold mb-5">Konfigurasi Email Gateway (SMTP)</h4>

                                    <?php inputRow('SMTP Host','<input type="text" class="form-control form-control-solid" name="smtp_host" value="'.old('smtp_host',$settings['smtp_host']??'').'">'); ?>
                                    <?php inputRow('SMTP User','<input type="text" class="form-control form-control-solid" name="smtp_user" value="'.old('smtp_user',$settings['smtp_user']??'').'">'); ?>
                                    <?php inputRow('SMTP Password','<input type="password" class="form-control form-control-solid mb-2" name="smtp_pass"><div class="text-muted fs-7">Kosongkan jika tidak ingin mengubah password saat ini.</div>'); ?>
                                    <?php inputRow('SMTP Port','<input type="number" class="form-control form-control-solid" name="smtp_port" value="'.old('smtp_port',$settings['smtp_port']??587).'">'); ?>
                                    
                                    <?php inputRow('SMTP Security',
                                        '<select name="smtp_crypto" class="form-select form-select-solid" data-control="select2" data-hide-search="true">
                                            <option value="tls" '.(($settings['smtp_crypto']??'')=='tls'?'selected':'').'>TLS</option>
                                            <option value="ssl" '.(($settings['smtp_crypto']??'')=='ssl'?'selected':'').'>SSL</option>
                                        </select>'
                                    ); ?>
                                    
                                    <?php inputRow('Status SMTP',
                                        '<select name="smtp_status" class="form-select form-select-solid" data-control="select2" data-hide-search="true">
                                            <option value="true" '.(($settings['smtp_status']??'')=='true'?'selected':'').'>Aktif</option>
                                            <option value="false" '.(($settings['smtp_status']??'')=='false'?'selected':'').'>Non-Aktif</option>
                                        </select>'
                                    ); ?>
                                    
                                    <?php inputRow('From Email','<input type="email" class="form-control form-control-solid" name="smtp_from_email" value="'.old('smtp_from_email',$settings['smtp_from_email']??'').'">'); ?>
                                    <?php inputRow('From Name','<input type="text" class="form-control form-control-solid" name="smtp_from_name" value="'.old('smtp_from_name',$settings['smtp_from_name']??'').'">'); ?>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab_seo" role="tabpanel">
                                <div class="d-flex flex-column gap-5">
                                    <h4 class="text-gray-800 fw-bold mb-5">Optimasi Mesin Pencari (SEO)</h4>

                                    <?php inputRow('Keywords','<input type="text" class="form-control form-control-solid" name="site_keywords" value="'.old('site_keywords',$settings['site_keywords']??'').'" placeholder="keyword1, keyword2, keyword3">'); ?>
                                    <?php inputRow('Deskripsi','<textarea class="form-control form-control-solid" name="site_description" rows="4">'.old('site_description',$settings['site_description']??'').'</textarea>'); ?>
                                    <?php inputRow('Google Verification','<input type="text" class="form-control form-control-solid" name="google_site_verification" value="'.old('google_site_verification',$settings['google_site_verification']??'').'">'); ?>
                                    <?php inputRow('Google Analytics ID','<input type="text" class="form-control form-control-solid" name="google_analytics_id" value="'.old('google_analytics_id',$settings['google_analytics_id']??'').'" placeholder="G-XXXXXXXXXX">'); ?>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab_recaptcha" role="tabpanel">
                                <div class="d-flex flex-column gap-5">
                                    <h4 class="text-gray-800 fw-bold mb-5">Google reCAPTCHA v2</h4>

                                    <?php inputRow('Site Key','<input type="text" class="form-control form-control-solid" name="recaptcha_site_key" value="'.old('recaptcha_site_key',$settings['recaptcha_site_key']??'').'">'); ?>
                                    <?php inputRow('Secret Key','<input type="text" class="form-control form-control-solid" name="recaptcha_secret_key" value="'.old('recaptcha_secret_key',$settings['recaptcha_secret_key']??'').'">'); ?>
                                    <?php inputRow('Status',
                                        '<select name="recaptcha_status" class="form-select form-select-solid" data-control="select2" data-hide-search="true">
                                            <option value="true" '.(($settings['recaptcha_status']??'')=='true'?'selected':'').'>Aktif</option>
                                            <option value="false" '.(($settings['recaptcha_status']??'')=='false'?'selected':'').'>Tidak Aktif</option>
                                        </select>'
                                    ); ?>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab_google" role="tabpanel">
                                <div class="d-flex flex-column gap-5">
                                    <h4 class="text-gray-800 fw-bold mb-5">Google OAuth Login</h4>

                                    <?php inputRow('Client ID','<input type="text" class="form-control form-control-solid" name="client_id" value="'.old('client_id',$settings['client_id']??'').'">'); ?>
                                    <?php inputRow('Client Secret','<input type="text" class="form-control form-control-solid" name="client_secret" value="'.old('client_secret',$settings['client_secret']??'').'">'); ?>
                                    <?php inputRow('Redirect URI','<input type="text" class="form-control form-control-solid bg-light" name="redirect_uri" value="'.old('redirect_uri',$settings['redirect_uri']??'').'" readonly><div class="text-muted fs-7 mt-2">Disesuaikan otomatis oleh sistem.</div>'); ?>
                                    <?php inputRow('Folder Id Drive','<input type="text" class="form-control form-control-solid bg-light" name="folder_id_drive" value="'.old('folder_id_drive',$settings['folder_id_drive']??'').'">'); ?>
                                    <?php inputRow('Status',
                                        '<select name="client_status" class="form-select form-select-solid" data-control="select2" data-hide-search="true">
                                            <option value="true" '.(($settings['client_status']??'')=='true'?'selected':'').'>Aktif</option>
                                            <option value="false" '.(($settings['client_status']??'')=='false'?'selected':'').'>Non Aktif</option>
                                        </select>'
                                    ); ?>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab_midtrans" role="tabpanel">
                                <div class="d-flex flex-column gap-5">
                                    <h4 class="text-gray-800 fw-bold mb-5">Midtrans Payment Gateway</h4>

                                    <?php inputRow('Client Key','<input type="text" class="form-control form-control-solid" name="midtrans_client_key" value="'.old('midtrans_client_key',$settings['midtrans_client_key']??'').'">'); ?>
                                    <?php inputRow('Server Key','<input type="text" class="form-control form-control-solid" name="midtrans_server_key" value="'.old('midtrans_server_key',$settings['midtrans_server_key']??'').'">'); ?>
                                    <?php inputRow('Environment',
                                        '<select name="midtrans_is_production" class="form-select form-select-solid" data-control="select2" data-hide-search="true">
                                            <option value="true" '.(($settings['midtrans_is_production']??'')=='true'?'selected':'').'>Production (Live)</option>
                                            <option value="false" '.(($settings['midtrans_is_production']??'')=='false'?'selected':'').'>Sandbox (Testing)</option>
                                        </select>'
                                    ); ?>
                                </div>
                            </div>

                        </div>
                    </div>
                    
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="submit" class="btn btn-primary fw-bold" id="kt_settings_submit">
                            <i class="ki-duotone ki-save-2 fs-2 me-1"><span class="path1"></span><span class="path2"></span></i> Simpan Pengaturan
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Inisialisasi Select2 untuk semua select
        $('[data-control="select2"]').select2();
    });
</script>
<?= $this->endSection(); ?>
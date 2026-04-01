<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<style>
    .settings-card {
        border: 0;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }

    .settings-header {
        border-bottom: 1px solid #eee;
        margin-bottom: 25px;
        padding-bottom: 15px;
    }

    .nav-tabs .nav-link {
        border: none;
        font-weight: 500;
        color: #6c757d;
    }

    .nav-tabs .nav-link.active {
        color: #007bff;
        border-bottom: 3px solid #007bff;
        background: transparent;
    }

    .form-section-title {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #343a40;
    }

    .form-group label {
        font-weight: 600;
        color: #495057;
    }

    .form-control {
        border-radius: 6px;
    }

    .preview-img {
        margin-top: 10px;
        border-radius: 6px;
        border: 1px solid #ddd;
        padding: 3px;
    }

    .save-bar {
        border-top: 1px solid #eee;
        margin-top: 30px;
        padding-top: 20px;
    }
</style>

<div class="container-fluid mt-4">
    <div class="card settings-card">
        <div class="card-body">

            <div class="settings-header">
                <h4 class="mb-1">Pengaturan Sistem</h4>
                <small class="text-muted">Kelola konfigurasi aplikasi Anda</small>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab_general">General</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_sosial_media">Sosial Media</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_smtp">SMTP</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_seo">SEO</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_recaptcha">reCAPTCHA</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_google">Google</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_midtrans">Midtrans</a></li>
            </ul>

            <form action="<?= base_url('sw-admin/settings/update') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="tab-content">

                    <!-- ================= GENERAL ================= -->
                    <div class="tab-pane fade show active" id="tab_general">

                        <div class="form-section-title">Informasi Perusahaan</div>

                        <?php
                        function inputRow($label, $input) {
                            echo '
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-md-right">'.$label.'</label>
                                <div class="col-md-7">'.$input.'</div>
                            </div>';
                        }
                        ?>

                        <?php inputRow('Nama Aplikasi',
                            '<input type="text" class="form-control" name="app_name" value="'.old('app_name', $settings['app_name'] ?? '').'" required>'
                        ); ?>

                        <?php inputRow('Favicon Aplikasi',
                            '<input type="file" class="form-control" name="app_icon">'
                        ); ?>

                        <?php if (!empty($settings['app_icon'])): ?>
                            <div class="text-center mb-3">
                                <?= img_lazy('uploads/app-icon/'.$settings['app_icon'],'-',['class'=>'preview-img','width'=>60]) ?>
                            </div>
                        <?php endif; ?>

                        <?php inputRow('Logo Perusahaan',
                            '<input type="file" class="form-control" name="logo_perusahaan">'
                        ); ?>

                        <?php if (!empty($settings['logo_perusahaan'])): ?>
                            <div class="text-center mb-3">
                                <?= img_lazy('uploads/app-icon/'.$settings['logo_perusahaan'],'-',['class'=>'preview-img','width'=>80]) ?>
                            </div>
                        <?php endif; ?>

                        <?php inputRow('Email Perusahaan',
                            '<input type="email" class="form-control" name="app_email" value="'.old('app_email', $settings['app_email'] ?? '').'" required>'
                        ); ?>

                        <?php inputRow('Telepon',
                            '<input type="text" class="form-control" name="app_phone" value="'.old('app_phone', $settings['app_phone'] ?? '').'">'
                        ); ?>

                        <?php inputRow('Alamat Lengkap',
                            '<input type="text" class="form-control" name="alamat_perusahaan" value="'.old('alamat_perusahaan', $settings['alamat_perusahaan'] ?? '').'">'
                        ); ?>

                        <?php inputRow('Google Maps',
                            '<input type="text" class="form-control" name="google_maps" value="'.old('google_maps', $settings['google_maps'] ?? '').'">
                             <small class="text-muted">Copy hanya isi src dari embed Google Maps.</small>'
                        ); ?>

                        <?php inputRow('Tahun Berdiri',
                            '<input type="text" class="form-control" name="tahun_berdiri" value="'.old('tahun_berdiri', $settings['tahun_berdiri'] ?? '').'">'
                        ); ?>

                        <?php inputRow('Versi Aplikasi',
                            '<input type="text" class="form-control" name="app_versi" value="'.old('app_versi', $settings['app_versi'] ?? '').'">'
                        ); ?>

                    </div>

                    <!-- ================= SOSIAL MEDIA ================= -->
                    <div class="tab-pane fade" id="tab_sosial_media">
                        <div class="form-section-title">Link Sosial Media</div>

                        <?php inputRow('Facebook','<input type="text" class="form-control" name="footer_facebook" value="'.old('footer_facebook',$settings['footer_facebook']??'').'">'); ?>
                        <?php inputRow('Instagram','<input type="text" class="form-control" name="footer_instagram" value="'.old('footer_instagram',$settings['footer_instagram']??'').'">'); ?>
                        <?php inputRow('Youtube','<input type="text" class="form-control" name="footer_youtube" value="'.old('footer_youtube',$settings['footer_youtube']??'').'">'); ?>
                        <?php inputRow('LinkedIn','<input type="text" class="form-control" name="footer_linkedin" value="'.old('footer_linkedin',$settings['footer_linkedin']??'').'">'); ?>
                    </div>

                    <!-- ================= SMTP ================= -->
                    <div class="tab-pane fade" id="tab_smtp">
                        <div class="form-section-title">Konfigurasi SMTP</div>

                        <?php inputRow('SMTP Host','<input type="text" class="form-control" name="smtp_host" value="'.old('smtp_host',$settings['smtp_host']??'').'">'); ?>
                        <?php inputRow('SMTP User','<input type="text" class="form-control" name="smtp_user" value="'.old('smtp_user',$settings['smtp_user']??'').'">'); ?>
                        <?php inputRow('SMTP Password','<input type="password" class="form-control" name="smtp_pass"><small class="text-muted">Kosongkan jika tidak diubah</small>'); ?>
                        <?php inputRow('SMTP Port','<input type="number" class="form-control" name="smtp_port" value="'.old('smtp_port',$settings['smtp_port']??587).'">'); ?>
                        <?php inputRow('SMTP Security',
                            '<select name="smtp_crypto" class="form-control">
                                <option value="tls" '.(($settings['smtp_crypto']??'')=='tls'?'selected':'').'>TLS</option>
                                <option value="ssl" '.(($settings['smtp_crypto']??'')=='ssl'?'selected':'').'>SSL</option>
                            </select>'
                        ); ?>
                        <?php inputRow('Status SMTP',
                            '<select name="smtp_status" class="form-control">
                                <option value="true" '.(($settings['smtp_status']??'')=='true'?'selected':'').'>Aktif</option>
                                <option value="false" '.(($settings['smtp_status']??'')=='false'?'selected':'').'>Non-Aktif</option>
                            </select>'
                        ); ?>
                        <?php inputRow('From Email','<input type="email" class="form-control" name="smtp_from_email" value="'.old('smtp_from_email',$settings['smtp_from_email']??'').'">'); ?>
                        <?php inputRow('From Name','<input type="text" class="form-control" name="smtp_from_name" value="'.old('smtp_from_name',$settings['smtp_from_name']??'').'">'); ?>
                    </div>

                    <!-- ================= SEO ================= -->
                    <div class="tab-pane fade" id="tab_seo">
                        <div class="form-section-title">Pengaturan SEO</div>

                        <?php inputRow('Keywords','<input type="text" class="form-control" name="site_keywords" value="'.old('site_keywords',$settings['site_keywords']??'').'">'); ?>
                        <?php inputRow('Deskripsi','<textarea class="form-control" name="site_description" rows="3">'.old('site_description',$settings['site_description']??'').'</textarea>'); ?>
                        <?php inputRow('Google Verification','<input type="text" class="form-control" name="google_site_verification" value="'.old('google_site_verification',$settings['google_site_verification']??'').'">'); ?>
                        <?php inputRow('Google Analytics ID','<input type="text" class="form-control" name="google_analytics_id" value="'.old('google_analytics_id',$settings['google_analytics_id']??'').'">'); ?>
                    </div>

                    <!-- ================= RECAPTCHA ================= -->
                    <div class="tab-pane fade" id="tab_recaptcha">
                        <div class="form-section-title">Pengaturan reCAPTCHA</div>

                        <?php inputRow('Site Key','<input type="text" class="form-control" name="recaptcha_site_key" value="'.old('captcha_site_key',$settings['captcha_site_key']??'').'">'); ?>
                        <?php inputRow('Secret Key','<input type="text" class="form-control" name="recaptcha_secret_key" value="'.old('captcha_secret_key',$settings['captcha_secret_key']??'').'">'); ?>
                        <?php inputRow('Status',
                            '<select name="recaptcha_status" class="form-control">
                                <option value="true" '.(($settings['recaptcha_status']??'')=='true'?'selected':'').'>Aktif</option>
                                <option value="false" '.(($settings['recaptcha_status']??'')=='false'?'selected':'').'>Tidak Aktif</option>
                            </select>'
                        ); ?>
                    </div>

                    <!-- ================= GOOGLE LOGIN ================= -->
                    <div class="tab-pane fade" id="tab_google">
                        <div class="form-section-title">Google Login</div>

                        <?php inputRow('Client ID','<input type="text" class="form-control" name="client_id" value="'.old('client_id',$settings['client_id']??'').'">'); ?>
                        <?php inputRow('Client Secret','<input type="text" class="form-control" name="client_secret" value="'.old('client_secret',$settings['client_secret']??'').'">'); ?>
                        <?php inputRow('Redirect URI','<input type="text" class="form-control" name="redirect_uri" value="'.old('redirect_uri',$settings['redirect_uri']??'').'">'); ?>
                        <?php inputRow('Status',
                            '<select name="client_status" class="form-control">
                                <option value="true" '.(($settings['client_status']??'')=='true'?'selected':'').'>Aktif</option>
                                <option value="false" '.(($settings['client_status']??'')=='false'?'selected':'').'>Non Aktif</option>
                            </select>'
                        ); ?>
                    </div>

                    <!-- ================= MIDTRANS ================= -->
                    <div class="tab-pane fade" id="tab_midtrans">
                        <div class="form-section-title">Midtrans Configuration</div>

                        <?php inputRow('Client Key','<input type="text" class="form-control" name="midtrans_client_key" value="'.old('midtrans_client_key',$settings['midtrans_client_key']??'').'">'); ?>
                        <?php inputRow('Server Key','<input type="text" class="form-control" name="midtrans_server_key" value="'.old('midtrans_server_key',$settings['midtrans_server_key']??'').'">'); ?>
                        <?php inputRow('Is Production',
                            '<select name="midtrans_is_production" class="form-control">
                                <option value="true" '.(($settings['midtrans_is_production']??'')=='true'?'selected':'').'>Aktif</option>
                                <option value="false" '.(($settings['midtrans_is_production']??'')=='false'?'selected':'').'>Non Aktif</option>
                            </select>'
                        ); ?>
                    </div>

                </div>

                <div class="save-bar text-right">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fa fa-save mr-1"></i> Simpan Pengaturan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
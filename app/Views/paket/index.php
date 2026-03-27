<!DOCTYPE html>

<html lang="en">



<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= title(); ?></title>

    <link rel="icon" type="image/x-icon" href="<?= base_url(favicon()); ?>" />
    
    <meta name="keywords" content="Kelas Brevet AB, Kelas Pajak, Brevet AB, Kursus Brevet Pajak, brevet, pajak, brevet pajak AB, kursus pajak, pelatihan pajak, brevet A&B, pelatihan pajak terapan brevet a&b terpadu, pelatihan perpajakan, perpajakan, kursus pajak offline, kursus pajak online, brevet pajak adalah, Keunggulan pelatihan pajak, manfaat kursus brevet pajak, lokasi pelatihan brevet, lokasi pelatihan pajak, fasilitas pelatihan pajak, fasilias pelatihan brevet, berapa lama pelatihan brevet, berapa lama kursus pelatihan brevet, sertifikat brevet">
    <meta name="description" content="<?= $paket->nama_paket ?>">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?=  $paket->nama_paket  ?>" />
    <meta property="og:description" content="<?=  $paket->jenis_paket ?>" />
    <meta property="og:url" content="<?= current_url() ?>" />
    <meta property="og:article:section" content="<?= $paket->jenis_paket ?>" />
    <meta property="og:image" content="<?= base_url('assets-landing/images/paket/thumbnails/'.$paket->file) ?>" />
    <meta property="og:image:alt" content="<?= $paket->nama_paket ?>" />
    <meta property="og:image:type" content="image/jpeg" />

    <!-- BEGIN GLOBAL MANDATORY STYLES -->

    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&amp;display=swap" rel="stylesheet">

    <link href="<?= base_url('assets/app-assets/template/cbt-malela'); ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <link href="<?= base_url('assets/app-assets/template/cbt-malela'); ?>/assets/css/plugins.css" rel="stylesheet" type="text/css" />

    <link href="<?= base_url('assets/app-assets/template/cbt-malela'); ?>/assets/css/authentication/form-1.css" rel="stylesheet" type="text/css" />

    <!-- END GLOBAL MANDATORY STYLES -->

    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/app-assets/template/cbt-malela'); ?>/assets/css/forms/theme-checkbox-radio.css">

    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/app-assets/template/cbt-malela'); ?>/assets/css/forms/switches.css">

    <link href="<?= base_url('assets/app-assets/template/cbt-malela'); ?>/plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet" type="text/css" />

    <link href="<?= base_url('assets/app-assets/template/cbt-malela'); ?>/plugins/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />

    <script src="<?= base_url('assets/app-assets/template/cbt-malela'); ?>/plugins/sweetalerts/sweetalert2.min.js"></script>

    <script src="<?= base_url('assets/app-assets/template/cbt-malela'); ?>/plugins/sweetalerts/custom-sweetalert.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
      .navbar-kelasbrevet {
        background-color: #0d5be1; /* biru seperti contoh */
        padding: 14px 0;
      }
    
      .navbar-kelasbrevet .nav-link {
        color: #ffffff !important;
        font-weight: 500;
        padding: 8px 14px;
        transition: opacity .2s ease;
      }
    
      .navbar-kelasbrevet .nav-link:hover {
        opacity: .8;
      }
    
      .navbar-kelasbrevet .dropdown-menu {
        border-radius: 6px;
        border: none;
        box-shadow: 0 8px 24px rgba(0,0,0,.12);
        font-size: 14px;
      }
    
      .navbar-kelasbrevet .dropdown-item {
        padding: 8px 16px;
      }
    
      .navbar-kelasbrevet .dropdown-item:hover {
        background-color: #f1f5ff;
      }
    
      .navbar-kelasbrevet .btn-masuk {
        color: #fff;
        font-weight: 500;
        border: 1px solid rgba(255,255,255,.5);
        border-radius: 20px;
        padding: 6px 16px;
      }
    
      .navbar-kelasbrevet .btn-masuk:hover {
        background-color: rgba(255,255,255,.15);
        text-decoration: none;
      }
      .navbar-toggler {
          border-color: #fff;
        }
        .navbar-toggler-icon {
          background-image:
            url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255,255,255,1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

    </style>

    <style>
        #togglePassword {
            border-left: none; /* Menghilangkan garis pembatas kiri agar terlihat menyatu dengan input */
            cursor: pointer;
        }
        #password {
            border-right: none; /* Menghilangkan garis pembatas kanan input */
        }
        .input-group-append .btn {
            background-color: #fff; /* Menyamakan warna background dengan input */
            border-color: #ced4da;
            color: #6c757d;
        }
    </style>
</head>



<body>

<nav class="navbar navbar-expand-lg navbar-kelasbrevet">
  <div class="container px-3">

    <!-- LOGO -->
    <a class="navbar-brand d-flex align-items-center text-white font-weight-bold"
       href="<?= base_url('/') ?>">
      <img src="<?= base_url('assets-landing/images/logo-putih.png') ?>"
           alt="KelasBrevet"
           style="height:32px"
           class="mr-2">
    </a>

    <!-- TOGGLE BUTTON (WAJIB UNTUK MOBILE) -->
    <button class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#kelasbrevetNavbar"
            aria-controls="kelasbrevetNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- MENU -->
    <div class="collapse navbar-collapse" id="kelasbrevetNavbar">
      <!-- MENU TENGAH -->
      <ul class="navbar-nav mx-auto text-center">

        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('tentangkami') ?>">Tentang Kami</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('pelatihan') ?>">Pelatihan</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('penilaian') ?>">Penilaian</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('testimoni') ?>">Testimoni</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle"
             href="#"
             id="infoDropdown"
             role="button"
             data-toggle="dropdown">
            Informasi
          </a>

          <div class="dropdown-menu text-left">
            <a class="dropdown-item" href="<?= base_url('artikel') ?>">Artikel</a>
            <a class="dropdown-item" href="<?= base_url('jadwal') ?>">Jadwal</a>
            <a class="dropdown-item" href="<?= base_url('galeri') ?>">Galeri</a>
            <a class="dropdown-item" href="<?= base_url('media-kelasbrevet') ?>">Media</a>
            <a class="dropdown-item" href="<?= base_url('siap-kerja') ?>">Siap Kerja</a>
            <a class="dropdown-item" href="<?= base_url('twibbon') ?>">Twibbon</a>
          </div>
        </li>

      </ul>

      <!-- MENU KANAN -->
      <ul class="navbar-nav ml-auto">

        <?php if(session('nama') == ''): ?>
          <li class="nav-item text-center">
            <a href="<?= base_url('Auth') ?>" class="btn-masuk">
              Masuk
            </a>
          </li>
        <?php else: ?>

          <?php
            if(session('role') == '1')      $url = base_url('App');
            elseif(session('role') == '2')  $url = base_url('siswa');
            elseif(session('role') == '3')  $url = base_url('guru');
            elseif(session('role') == '4')  $url = base_url('Mitra');
            elseif(session('role') == '5')  $url = base_url('Pic');
          ?>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle"
               href="#"
               data-toggle="dropdown">
              <?= mb_strimwidth(session('nama'), 0, 10, '...') ?>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="<?= $url ?>">Dashboard</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item text-danger" href="<?= base_url('Auth/logout') ?>">
                Logout
              </a>
            </div>
          </li>

        <?php endif; ?>

      </ul>
    </div>

  </div>
</nav>


    <div class="container">

      <div class="row g-4">
        <!-- ================== KIRI : RINGKASAN PESANAN ================== -->
        <div class="col-lg-4">
          <div class="card rounded-4 shadow-sm sticky-top my-4">
            <div class="card-body p-4">
                <div class="courses-images mb-3">
                    <a href="<?= base_url('bimbel/'. $paket->slug) ?>">
                        <?= img_lazy('assets-landing/images/paket/thumbnails/'.$paket->file, $paket->nama_paket, ['class' => 'card-img-top']) ?>
                    </a>
                </div>
    
              
              <form action="<?= base_url('Transaksi/checkout'); ?>" method="POST">
    
                <!-- ====== HIDDEN INPUT (TETAP) ====== -->
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <input type="hidden" name="idpaket" id="idpaket" value="<?= $paket->idpaket ?>">
                <input type="hidden" name="idmapel" value="<?= $paket->id_mapel ?>">
                <input type="hidden" name="nama_paket" value="<?= $paket->nama_paket ?>">
                <input type="hidden" name="jenis_paket" value="<?= $paket->jenis_paket ?>">
                <input type="hidden" name="jumlah_bulan" value="<?= $paket->jumlah_bulan ?>">
                <input type="hidden" name="nominal" id="nominal" value="<?= $paket->nominal_paket ?>">
                <input type="hidden" name="diskon" id="diskon" value="<?= $paket->diskon ?>">
                <input type="hidden" name="komisi" value="<?= $paket->komisi ?>">
                <input type="hidden" name="total" value="<?= $paket->nominal_paket ?>">
    
                <!-- ITEM -->
                <div class="d-flex justify-content-between font-weight-bold text-dark mb-2">
                  <span><?= $paket->nama_paket ?></span>
                  <span class="fw-semibold">
                    Rp<?= number_format($paket->nominal_paket) ?>
                  </span>
                </div>
    
                <!-- DISKON -->
                <div class="d-flex justify-content-between fs-5 text-muted small mb-2">
                  <span>Diskon <?= $paket->diskon ?>%</span>
                  <span class="font-weight-bold text-danger">
                    - Rp<?= number_format(($paket->nominal_paket * $paket->diskon) / 100) ?>
                  </span>
                </div>
    
                <hr>
    
                <!-- SUBTOTAL -->
                <div class="d-flex justify-content-between fs-5 font-weight-bold text-dark mb-3">
                  <span>Subtotal</span>
                  <span>
                    Rp<?= number_format(
                      $paket->nominal_paket - ($paket->nominal_paket * $paket->diskon / 100)
                    ) ?>
                  </span>
                </div>
                <div id="tampil_diskon_voucher">
                </div>
                <div id="tampil_total_diskon_voucher">
                </div>
    
                <!-- VOUCHER (TETAP) -->
               
                    <div class="mb-3">
                      <label class="small">Kode Voucher (Opsional)</label>
                      <input type="text"
                             name="kode_voucher"
                             id="kode_voucher"
                             onkeyup="lihatKodeVoucher()"
                             class="form-control form-control-sm"
                             value="<?= $kodevoucher ?>" <?= $kodevoucher == '8173AF4239'? 'readonly':'' ?>>
                      <input type="hidden" name="diskon_voucher" id="diskon_voucher">
                      <small id="info"></small>
                    </div>
                 <?php if(session()->get('id')): ?>
                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-3">
                      Lanjutkan
                    </button>
                <?php endif; ?>
    
              </form>
    
            </div>
          </div>
        </div>
        <!-- ================== KANAN : DETAIL PAKET ================== -->
        <div class="col-lg-8">
          <div class="card shadow-sm my-4">
            <div class="card-body p-4">
        
              <?php if (session()->get('id')): ?>
        
                <!-- ================== JUDUL ================== -->
                <h4 class="font-weight-bold mb-3">Paket Premium</h4>
                <hr class="mb-4">
        
                <!-- ================== INFO RINGKAS ================== -->
                <div class="row text-center mb-4">
        
                  <div class="col-md-4 mb-3">
                    <div class="border rounded p-3 h-100">
                      <div style="font-size:22px;">📦</div>
                      <div class="font-weight-bold text-dark small mt-1">Jenis Paket</div>
                      <div class="text-muted small"><?= $paket->jenis_paket ?></div>
                    </div>
                  </div>
        
                  <div class="col-md-4 mb-3">
                    <div class="border rounded p-3 h-100">
                      <div style="font-size:22px;">⏱️</div>
                      <div class="font-weight-bold text-dark small mt-1">Durasi Akses</div>
                      <div class="text-muted small">Akses tanpa batas waktu</div>
                    </div>
                  </div>
        
                  <div class="col-md-4 mb-3">
                    <div class="border rounded p-3 h-100">
                      <div style="font-size:22px;">💸</div>
                      <div class="font-weight-bold text-dark small mt-1">Total Hemat</div>
                      <div class="text-success font-weight-bold small">
                        Rp<?= number_format(($paket->nominal_paket * $paket->diskon) / 100) ?>
                      </div>
                    </div>
                  </div>
        
                </div>
        
                <!-- ================== PROMO ================== -->
                <div class="p-3 text-white mb-4"
                     style="background:linear-gradient(90deg,#1e3a8a,#2563eb); border-radius:6px;">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <strong>Promo Spesial</strong><br>
                      <small>Paket hemat untuk hasil maksimal</small>
                      <small class="font-weight-bold">Diskon <?= $paket->diskon ?>%</small>
                    </div>
                    <span class="badge badge-warning text-dark">Aktif</span>
                  </div>
                </div>
        
                <!-- ================== BENEFIT ================== -->
                <div class="mb-4">
                  <h6 class="font-weight-bold mb-3 text-primary">
                    Benefit Pelatihan
                  </h6>
        
                  <ul class="list-unstyled mb-0 small text-muted">
        
                    <li class="d-flex align-items-start mb-2">
                      <span class="mr-2" style="font-size:18px;">⏱️</span>
                      <span>Akses pembelajaran tanpa batas waktu</span>
                    </li>
        
                    <li class="d-flex align-items-start mb-2">
                      <span class="mr-2" style="font-size:18px;">📄</span>
                      <span>Softcopy materi pembelajaran terkini</span>
                    </li>
        
                    <li class="d-flex align-items-start mb-2">
                      <span class="mr-2" style="font-size:18px;">🎓</span>
                      <span>E-sertifikat lulus ujian per materi</span>
                    </li>
        
                    <li class="d-flex align-items-start mb-2">
                      <span class="mr-2" style="font-size:18px;">🏅</span>
                      <span>E-sertifikat lulus ujian Brevet Pajak AB</span>
                    </li>
        
                    <li class="d-flex align-items-start mb-2">
                      <span class="mr-2" style="font-size:18px;">💬</span>
                      <span>Live konsultasi online bulanan</span>
                    </li>
        
                    <li class="d-flex align-items-start mb-2">
                      <span class="mr-2" style="font-size:18px;">👥</span>
                      <span>Grup aktif diskusi pelatihan</span>
                    </li>
        
                    <li class="d-flex align-items-start mb-2">
                      <span class="mr-2" style="font-size:18px;">📝</span>
                      <span>Pelaksanaan ujian fleksibel</span>
                    </li>
        
                    <li class="d-flex align-items-start">
                      <span class="mr-2" style="font-size:18px;">👨‍🏫</span>
                      <span>Instruktur praktisi perpajakan berpengalaman</span>
                    </li>
        
                  </ul>
                </div>
        
                <!-- ================== CATATAN ================== -->
                <div class="alert alert-primary small mb-0">
                  <strong>Catatan:</strong>
                  Paket ini dirancang untuk membantu Anda meningkatkan kompetensi secara profesional
                  melalui pembelajaran yang fleksibel dengan biaya yang lebih hemat.
                </div>
        
              <?php else: ?>
        
                <!-- ================== LOGIN / REGISTER ================== -->
                <h4 class="text-center font-weight-bold mb-4">
                  Registrasi atau login untuk melanjutkan pembelian
                </h4>
        
                <form action="<?= base_url('auth/store-siswa-melalui-pesan'); ?>"
                      method="POST"
                      id="form"
                      onsubmit="return submitForm(event)">
        
                  <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
          
                  <div class="form-group">
                    <input type="text" class="form-control" name="nama_siswa"
                           placeholder="Nama lengkap" required>
                  </div>
        
                  <div class="form-group">
                    <select name="jenis_kelamin" class="form-control" required>
                      <option value="">Pilih Jenis Kelamin</option>
                      <option value="Laki - Laki">Laki - Laki</option>
                      <option value="Perempuan">Perempuan</option>
                    </select>
                  </div>
        
                  <input type="hidden" name="kelas" value="1">
                  <input type="hidden" name="idpaketenc" value="<?= $idpaketenc ?>">
                  <input type="hidden" name="kodevoucher" value="<?= $kodevoucher ?>">
        
                  <div class="form-group">
                    <input type="email" name="email" class="form-control"
                           placeholder="Email" required>
                  </div>
        
                  <div class="form-group">
                      <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control"
                               placeholder="Kata sandi" required>
                        <div class="input-group-append">
                          <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                          </button>
                        </div>
                      </div>
                  </div>
        
                  <input type="hidden" name="recaptcha_token" id="recaptcha_token">
        
                  <button type="submit" class="btn btn-primary btn-block mb-3">
                    Registrasi
                  </button>
                </form>
        
                <p class="text-center small">
                  Dengan mendaftar, Anda menyetujui
                  <a href="#">Persyaratan Penggunaan</a> dan
                  <a href="#">Kebijakan Privasi</a>.
                </p>
        
                <div class="text-center bg-light py-3 rounded">
                  Sudah punya akun?
                  <a href="<?= base_url('auth') ?>" class="font-weight-bold">Log in</a>
                </div>
        
              <?php endif; ?>
        
            </div>
        
            <p class="terms-conditions text-center small mt-3 mb-3">
              <?= copyright(); ?>
            </p>
        
          </div>
        </div>
      </div>
    </div>

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->

    <script src="<?= base_url('assets/app-assets/template/cbt-malela'); ?>/assets/js/libs/jquery-3.1.1.min.js"></script>

    <script src="<?= base_url('assets/app-assets/template/cbt-malela'); ?>/bootstrap/js/popper.min.js"></script>

    <script src="<?= base_url('assets/app-assets/template/cbt-malela'); ?>/bootstrap/js/bootstrap.min.js"></script>



    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <script src="<?= base_url('assets/app-assets/template/cbt-malela'); ?>/assets/js/authentication/form-1.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js" integrity="sha256-0YPKAwZP7Mp3ALMRVB2i8GXeEndvCq3eSl/WsAl1Ryk=" crossorigin="anonymous"></script>

    <script type="text/javascript">
        <?= session()->getFlashdata('pesan'); ?>
        var kodevoucher = "<?= $kodevoucher ?>";
        if(kodevoucher != ''){
            lihatKodeVoucher();
        }

        function lihatKodeVoucher() {
            const kode_voucher = $('#kode_voucher').val();
            const idpaket = $('#idpaket').val();
            var nominal = $("#nominal").val();
            var diskon = $("#diskon").val();
            $.ajax({
                type: 'POST',
                data: {
                    kode_voucher: kode_voucher,
                    idpaket:idpaket
                },
                dataType: 'JSON',
                async: true,
                url: "<?= base_url('Transaksi/cek_kode_voucher') ?>",
                success: function(data) {
                    if(data.length > 0){
                        var diskonHasil = (nominal*diskon)/100;
                        var hargaDiskon = nominal - diskonHasil;
                        var voucher = (hargaDiskon*data[0].diskon_voucher)/100;
                        var hargaVoucher = hargaDiskon - voucher;
                        
                        $("#diskon_voucher").val(data[0].diskon_voucher);
                        $("#info").css("color", "#fc5d32");
                        $("#info").html("<span class='text-success'>Voucher berhasil</span>");
                        $("#tampil_diskon_voucher").html(`
                          <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                            <div class="small text-muted">
                              Ekstra Diskon
                              <span class="font-weight-bold text-dark">
                                ${data[0].diskon_voucher}%
                              </span>
                            </div>
                            <div class="font-weight-bold text-danger">
                              - Rp ${number_format(voucher,'0','.','.')}
                            </div>
                          </div>
                        `);

                        $("#tampil_total_diskon_voucher").html(`
                          <div class="d-flex justify-content-between align-items-center pt-2">
                            <div class="font-weight-bold text-dark">
                              Total Tagihan
                            </div>
                            <div class="font-weight-bold text-dark">
                              Rp ${number_format(hargaVoucher,'0','.','.')}
                            </div>
                          </div>
                        `);

                    }else{
                        $("#info").html("<span class='text-danger'>Voucher tidak ditemukan</span>");
                        $("#diskon_voucher").val(0);
                        $("#tampil_diskon_voucher").html('<div></div>'); 
                        $("#tampil_total_diskon_voucher").html('<div></div>');
                    }
                    
                }
            });
        };
        
         function number_format (number, decimals, dec_point, thousands_sep) {
            number = number.toFixed(decimals);
    
            var nstr = number.toString();
            nstr += '';
            x = nstr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? dec_point + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
    
            while (rgx.test(x1))
                x1 = x1.replace(rgx, '$1' + thousands_sep + '$2');
    
            return x1 + x2;
        }
        // END diskon
        
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://www.google.com/recaptcha/api.js?render=<?= setting('recaptcha_site_key') ?>"></script>

    <script>
        function submitForm(e) {
            e.preventDefault();
        
            const form = e.target;
        
            if (!form.checkValidity()) {
                form.reportValidity();
                return false;
            }
        
            if (typeof grecaptcha === 'undefined') {
                alert('reCAPTCHA gagal dimuat');
                return false;
            }
        
            grecaptcha.ready(function () {
                grecaptcha.execute('<?= setting('recaptcha_site_key') ?>', {
                    action: 'registrasi_pesan'
                }).then(function (token) {
        
                    const field = document.getElementById('recaptcha_token');
                    if (!field) {
                        console.error('recaptcha_token tidak ditemukan');
                        return;
                    }
        
                    field.value = token; // ✅ BENAR
                    form.submit();
                });
            });
        
            return false;
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
          let lazyImages = document.querySelectorAll("img.lazy");
        
          if ("IntersectionObserver" in window) {
            let observer = new IntersectionObserver((entries, obs) => {
              entries.forEach(entry => {
                if (entry.isIntersecting) {
                  let img = entry.target;
                  let realSrc = img.dataset.src;
        
                  // buat objek Image baru biar nunggu sampai siap
                  let loader = new Image();
                  loader.src = realSrc;
        
                  loader.onload = function() {
                    img.src = realSrc;
                    img.classList.add("fade-in"); // animasi
                  };
        
                  img.removeAttribute("data-src");
                  obs.unobserve(img);
                }
              });
            });
        
            lazyImages.forEach(img => observer.observe(img));
          }
        });
    </script>
    <script>
        $(document).ready(function() {
            // Gunakan delegasi event agar tetap bekerja meski elemen baru muncul
            $(document).on('click', '#togglePassword', function() {
                const passwordField = $('#password');
                const eyeIcon = $('#eyeIcon');
                
                // Cek tipe input saat ini
                const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                
                // Ubah tipe input
                passwordField.attr('type', type);
                
                // Ubah icon
                if (type === 'password') {
                    eyeIcon.removeClass('fa-eye-slash').addClass('fa-eye');
                } else {
                    eyeIcon.removeClass('fa-eye').addClass('fa-eye-slash');
                }
            });
        });
    </script>

</body>





</html>
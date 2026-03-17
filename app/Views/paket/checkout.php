<!DOCTYPE html>

<html lang="en">



<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

    <title><?= title(); ?></title>

    <link rel="icon" type="image/x-icon" href="<?= base_url(favicon()); ?>" />

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

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
      .payment-option {
          transition: all 0.25s ease;
          cursor: pointer;
          background-color: #fff;
        }
        
        /* Hover */
        .payment-option:hover {
          border-color: #007bff;
          background-color: #f8faff;
          transform: translateY(-2px);
          box-shadow: 0 8px 20px rgba(0, 123, 255, 0.15);
          text-decoration: none;
        }
        
        /* Hover teks kanan */
        .payment-option:hover span {
          color: #007bff !important;
          font-weight: 600;
        }
        
        /* Active / Click */
        .payment-option:active {
          transform: translateY(0);
          box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
          background-color: #eef4ff;
        }

    </style>
</head>



<body>
    <nav class="navbar navbar-expand-lg navbar-kelasbrevet">
  <div class="container">

    <!-- LOGO -->
    <a class="navbar-brand d-flex align-items-center text-white font-weight-bold"
       href="<?= base_url('/') ?>">
      <img src="<?= base_url('assets-landing/images/logo-putih.png') ?>"
           alt="KelasBrevet"
           style="height:32px"
           class="mr-2">
    </a>

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
          <li class="nav-item">
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
<div class="container my-5">
  <div class="row justify-content-center">

    <!-- ================= LEFT : METODE PEMBAYARAN ================= -->
    <div class="col-lg-7 mb-4">
      <?php if($transaksi->jenis_bayar == ''): ?>
          <div class="card border-0 shadow-sm rounded-lg">
            <div class="card-body p-4">
                <h5 class="font-weight-bold mb-4">
                    Pilih metode pembayaran
                </h5>
                <a href="<?= base_url("Transaksi/manual_bayar/".encrypt_url($transaksi->idtransaksi)) ?>"
                   class="payment-option d-block border rounded p-3 mb-3 text-dark text-decoration-none">
                  <div class="d-flex justify-content-between align-items-center">
                    <strong>🏦 Transfer Manual Bank</strong>
                    <span class="text-muted">Pilih</span>
                  </div>
                </a>
    
                <a href="<?= base_url("Transaksi/midtrans_bayar/".encrypt_url($transaksi->idtransaksi)) ?>"
                   class="payment-option d-block border rounded p-3 text-dark text-decoration-none">
                  <div class="d-flex justify-content-between align-items-center">
                    <strong>⚡ Virtual Account (Otomatis)</strong>
                    <span class="text-muted">Pilih</span>
                  </div>
                </a>
    
            </div>
          </div>
      <?php endif; ?>

      <!-- ================= MANUAL UPLOAD ================= -->
      <div id="manual" class="card border-0 shadow-sm rounded-lg collapse">
        <div class="card-body p-4">

          <h6 class="font-weight-bold mb-3">Upload Bukti Pembayaran</h6>

          <form action="<?= base_url('Transaksi/UploadBuktibayar'); ?>"
                method="POST"
                enctype="multipart/form-data">

            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <input type="hidden" name="idtransaksi" value="<?= $transaksi->idtransaksi; ?>">

            <div class="form-group">
              <label class="small">Bukti Transfer</label>
              <input type="file"
                     class="form-control-file"
                     accept="image/jpeg,image/png,image/jpg"
                     name="bukti_bayar"
                     required>
            </div>

            <div class="border rounded p-3 mb-3 bg-light">
              <div class="d-flex justify-content-between small">
                <span>Batas Waktu Pembayaran</span>
                <strong id="jam_skrng"></strong>
              </div>
              <hr class="my-2">
              <div class="d-flex justify-content-between small">
                <span>No. Rekening Mandiri</span>
                <strong>
                  1660003837846
                  <input type="hidden" id="norek" value="1660003837846">
                  <a href="javascript:void(0)" onclick="myFunction()">📋</a>
                </strong>
              </div>
              <div class="d-flex justify-content-between small mt-1">
                <span>Atas Nama</span>
                <strong>PT. Legalyn Konsultan Indonesia</strong>
              </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
              Upload Bukti Bayar
            </button>

          </form>

        </div>
      </div>
    </div>
    <?php 
        $diskon = $transaksi->nominal-($transaksi->nominal - ($transaksi->nominal * $transaksi->diskon / 100));
        $totalDiskon = $transaksi->nominal - $diskon;
        $diskon_voucher =$totalDiskon-($totalDiskon - ($totalDiskon * $transaksi->voucher / 100)); 
        $totalVoucher = $totalDiskon - $diskon_voucher;
    ?>


    <!-- ================= RIGHT : RINGKASAN ORDER ================= -->
    <div class="col-lg-5">
      <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body p-4">

          <h5 class="font-weight-bold mb-4">
            Ringkasan Order
          </h5>

          <div class="mb-3">
            <strong><?= $transaksi->nama_paket; ?></strong><br>
            <small class="text-muted">Paket <?= $transaksi->jenis_paket; ?></small>
          </div>

          <div class="d-flex justify-content-between small mb-2">
            <span>Harga</span>
            <span>Rp <?= number_format($transaksi->nominal,0,'.','.'); ?></span>
          </div>

          <div class="d-flex justify-content-between small text-success mb-2">
            <span>Diskon <?= $transaksi->diskon; ?>%</span>
            <span>
              - Rp <?= number_format(
                $transaksi->nominal - ($transaksi->nominal - ($transaksi->nominal * $transaksi->diskon / 100)),
                0,'.','.'
              ); ?>
            </span>
          </div>
            
          <?php if($transaksi->voucher != '0'): ?>
          <div class="d-flex justify-content-between small text-success mb-2">
            <span>Voucher <?= $transaksi->voucher; ?>%</span>
            <span>
              - Rp <?= number_format($diskon_voucher,0,'.','.'); ?>
            </span>
          </div>
          <?php endif; ?>

          <hr>
            
          <div class="d-flex justify-content-between align-items-center">
            <strong>Total</strong>
            <strong class="text-primary h5 mb-0">
              Rp <?= number_format(($transaksi->nominal - $diskon - $diskon_voucher),0,'.','.'); ?>
            </strong>
          </div>

          <?php if ($transaksi->keterangan != null) : ?>
            <div class="alert alert-danger small mt-3 mb-0">
              <?= $transaksi->keterangan; ?>
            </div>
          <?php endif ?>

        </div>
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

<script>
    <?= session()->getFlashdata('pesan'); ?>
    var countDownDate = new Date("<?= $transaksi->tgl_exp ?>").getTime();

    // Update the count down every 1 second
    var x = setInterval(function() {

        // Get today's date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the element with id="demo"
        document.getElementById("jam_skrng").innerHTML = hours + " : " +
            minutes + " : " + seconds;

        // If the count down is finished, write some text
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("jam_skrng").innerHTML = "Loading";
            document.location.reload();
        }
    }, 1000);

    function myFunction() {
        var norek = $('#norek').val();
        console.log(norek)
        // Get the text field

        // Copy the text inside the text field
        navigator.clipboard.writeText(norek);

        // Alert the copied text
        alert("Norek telah tersalin");

    }
   var jenis_pembayaran = '<?= $transaksi->jenis_bayar ?>';
    
    if(jenis_pembayaran == 'manual'){
        $("#manual").collapse("show");
    }
     
    
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>





</html>
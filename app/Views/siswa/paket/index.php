<?= $this->extend('siswa/template/app'); ?>
<?= $this->section('styles'); ?>
<style>
  :root {
    --kt-primary: #0095E8;
    --kt-primary-light: #F1FAFF;
    --kt-success: #50CD89;
    --kt-danger: #F1416C;
    --kt-gray-200: #EFF2F5;
    --kt-text-gray-700: #4B5675;
  }

  body {
    background-color: #F9FAFB;
    color: var(--kt-text-gray-700);
  }

  /* Card & Shadow Metronic Style */
  .card {
    border: none;
    box-shadow: 0 0 20px 0 rgba(76, 87, 125, 0.02);
    border-radius: 0.75rem;
  }

  .card-header {
    background: transparent;
    border-bottom: 1px solid var(--kt-gray-200);
    padding: 1.5rem;
  }

  /* Navbar Customization */
  .navbar-kelasbrevet {
    background-color: #1B1F3B;
    padding: 1rem 0;
  }

  /* Input Style */
  .form-control-solid {
    background-color: var(--kt-gray-200);
    border-color: var(--kt-gray-200);
    color: #5E6278;
    transition: color 0.2s ease, background-color 0.2s ease;
  }

  .form-control-solid:focus {
    background-color: #EBEDF3;
  }

  /* Badge & Info */
  .benefit-icon {
    width: 35px;
    height: 35px;
    background-color: var(--kt-primary-light);
    color: var(--kt-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.5rem;
    margin-right: 1rem;
  }

  .promo-banner {
    background: linear-gradient(90deg, #1B1F3B 0%, #0095E8 100%);
    border-radius: 0.75rem;
  }

  .sticky-summary {
    top: 100px;
  }

  #togglePassword {
    border-left: none;
    cursor: pointer;
    background: var(--kt-gray-200);
  }

  #password {
    border-right: none;
  }
</style>
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>
<div class="container py-10">
  <div class="row g-7">

    <div class="col-lg-8 order-2 order-lg-1">
      <div class="card shadow-sm mb-5">
        <div class="card-body p-9">
          <?php if (session()->get('id')): ?>
            <div class="d-flex align-items-center mb-7">
              <div class="flex-grow-1">
                <h1 class="text-dark fw-bolder fs-2 mb-2">Detail Paket Pelatihan</h1>
                <div class="text-muted fw-bold fs-6">Brevet Pajak AB - Professional Class</div>
              </div>
            </div>

            <div class="row g-5 mb-10">
              <div class="col-md-4">
                <div class="bg-light-primary rounded border-primary border border-dashed p-6 text-center">
                  <i class="ki-outline ki-package fs-2tx text-primary mb-2"></i>
                  <div class="fw-bolder fs-6 text-gray-800 mt-2"><?= $paket->jenis_paket ?></div>
                  <div class="fw-bold text-muted small">Tipe Paket</div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="bg-light-success rounded border-success border border-dashed p-6 text-center">
                  <i class="ki-outline ki-time fs-2tx text-success mb-2"></i>
                  <div class="fw-bolder fs-6 text-gray-800 mt-2">Lifetime Access</div>
                  <div class="fw-bold text-muted small">Masa Aktif</div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="bg-light-danger rounded border-danger border border-dashed p-6 text-center">
                  <i class="ki-outline ki-discount fs-2tx text-danger mb-2"></i>
                  <div class="fw-bolder fs-6 text-danger mt-2">Rp<?= number_format(($paket->nominal_paket * $paket->diskon) / 100) ?></div>
                  <div class="fw-bold text-muted small">Hemat Kamu</div>
                </div>
              </div>
            </div>

            <div class="promo-banner p-8 mb-10 d-flex align-items-center justify-content-between">
              <div class="text-white">
                <p class="fw-bolder mb-1 fs-1">Promo Member Baru!</p>
                <p class="opacity-75 mb-0">Gunakan kesempatan ini untuk mendapatkan sertifikasi profesional.</p>
              </div>
              <span class="badge badge-light-warning fs-7 fw-bolder px-4 py-3">DISKON <?= $paket->diskon ?>% AKTIF</span>
            </div>

            <h4 class="text-dark fw-bolder mb-6">Benefit yang Anda Dapatkan</h4>
            <div class="row g-4 mb-8">
              <?php
              $benefits = [
                ['icon' => '⏱️', 'title' => 'Akses Selamanya', 'desc' => 'Belajar kapan saja tanpa batas waktu.'],
                ['icon' => '📄', 'title' => 'Modul Lengkap', 'desc' => 'Softcopy materi update perpajakan terbaru.'],
                ['icon' => '🎓', 'title' => 'E-Sertifikat', 'desc' => 'Sertifikat resmi per modul dan ujian akhir.'],
                ['icon' => '💬', 'title' => 'Konsultasi Live', 'desc' => 'Tanya jawab langsung dengan praktisi ahli.'],
              ];
              foreach ($benefits as $b): ?>
                <div class="col-md-6">
                  <div class="d-flex align-items-center">
                    <div class="benefit-icon fs-4"><?= $b['icon'] ?></div>
                    <div>
                      <div class="text-gray-800 fw-bolder fs-6"><?= $b['title'] ?></div>
                      <div class="text-muted fs-7"><?= $b['desc'] ?></div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6">
              <i class="ki-outline ki-information-5 fs-2tx text-primary me-4"></i>
              <div class="d-flex flex-stack flex-grow-1">
                <div class="fw-bold">
                  <div class="fs-6 text-gray-700">Paket ini dirancang untuk membantu Anda meningkatkan kompetensi secara profesional melalui kurikulum yang fleksibel.</div>
                </div>
              </div>
            </div>

          <?php else: ?>
            <div class="text-center mb-10">
              <h1 class="text-dark fw-bolder mb-3">Mulai Belajar Sekarang</h1>
              <div class="text-gray-500 fw-bold fs-6">Lengkapi data diri untuk melanjutkan pembelian paket.</div>
            </div>

            <form action="<?= base_url('Register/tambah_siswa_melalui_pesan'); ?>" method="POST" id="form" onsubmit="return submitForm(event)">
              <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
              <input type="hidden" name="kelas" value="1">
              <input type="hidden" name="idpaketenc" value="<?= $idpaketenc ?>">
              <input type="hidden" name="kodevoucher" value="<?= $kodevoucher ?>">
              <input type="hidden" name="recaptcha_token" id="recaptcha_token">

              <div class="fv-row mb-7">
                <label class="fw-bold fs-6 mb-2">Nama Lengkap</label>
                <input type="text" name="nama_siswa" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Contoh: Budi Santoso" required />
              </div>

              <div class="row g-9 mb-7">
                <div class="col-md-6 fv-row">
                  <label class="fw-bold fs-6 mb-2">Jenis Kelamin</label>
                  <select name="jenis_kelamin" class="form-select form-select-solid" required>
                    <option value="">Pilih</option>
                    <option value="Laki - Laki">Laki - Laki</option>
                    <option value="Perempuan">Perempuan</option>
                  </select>
                </div>
                <div class="col-md-6 fv-row">
                  <label class="fw-bold fs-6 mb-2">Email Aktif</label>
                  <input type="email" name="email" class="form-control form-control-solid" placeholder="email@contoh.com" required />
                </div>
              </div>

              <div class="fv-row mb-10">
                <label class="fw-bold fs-6 mb-2">Kata Sandi</label>
                <div class="input-group">
                  <input type="password" name="password" id="password" class="form-control form-control-solid" placeholder="Minimal 8 karakter" required />
                  <button class="btn btn-icon btn-light" type="button" id="togglePassword">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                  </button>
                </div>
              </div>

              <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg w-100 fw-bolder">
                  <span class="indicator-label">Daftar & Lanjutkan Pembayaran</span>
                </button>
                <div class="text-gray-500 text-center fw-bold fs-7 mt-5">
                  Sudah punya akun? <a href="<?= base_url('auth') ?>" class="link-primary fw-bolder">Log in di sini</a>
                </div>
              </div>
            </form>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-lg-4 order-1 order-lg-2">
      <div class="card shadow-sm">
        <div class="card-header">
          <h3 class="card-title fw-bolder text-gray-800">Ringkasan Pesanan</h3>
        </div>
        <div class="card-body p-9">
          <div class="mb-7 text-center">
            <?= img_lazy('assets-landing/images/paket/thumbnails/' . $paket->file, $paket->nama_paket, ['class' => 'rounded-3 w-100 mb-5 shadow-sm']) ?>
            <h4 class="text-gray-800 fw-bolder mb-0"><?= $paket->nama_paket ?></h4>
            <span class="badge badge-light-primary fw-bold px-4 py-2 mt-2"><?= $paket->jenis_paket ?></span>
          </div>

          <div class="separator separator-dashed mb-7"></div>

          <form action="<?= base_url('sw-siswa/transaksi/checkout'); ?>" method="POST">
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

            <div class="d-flex flex-stack mb-4">
              <span class="fw-bold text-gray-600 fs-6">Harga Paket</span>
              <span class="fw-bolder text-gray-800 fs-6">Rp<?= number_format($paket->nominal_paket) ?></span>
            </div>
            <div class="d-flex flex-stack mb-4">
              <span class="fw-bold text-gray-600 fs-6">Diskon (<?= $paket->diskon ?>%)</span>
              <span class="fw-bolder text-danger fs-6">- Rp<?= number_format(($paket->nominal_paket * $paket->diskon) / 100) ?></span>
            </div>

            <div id="tampil_diskon_voucher"></div>

            <div class="separator separator-dashed mb-5"></div>

            <div class="d-flex flex-stack mb-8">
              <span class="fw-bolder text-gray-800 fs-4">Total</span>
              <span class="fw-bolder text-dark fs-3" id="main_total_display">
                Rp<?= number_format($paket->nominal_paket - ($paket->nominal_paket * $paket->diskon / 100)) ?>
              </span>
            </div>

            <div class="mb-8">
              <label class="form-label fw-bolder text-gray-700 fs-7">Kode Voucher</label>
              <input type="text" name="kode_voucher" id="kode_voucher" onkeyup="lihatKodeVoucher()"
                class="form-control form-control-solid" placeholder="Masukkan kode jika ada"
                value="<?= $kodevoucher ?>" <?= $kodevoucher == '8173AF4239' ? 'readonly' : '' ?>>
              <input type="hidden" name="diskon_voucher" id="diskon_voucher">
              <div id="info" class="fs-7 fw-bold mt-2"></div>
            </div>

            <?php if (session()->get('id')): ?>
              <button type="submit" class="btn btn-primary w-100 py-4 fw-bolder">
                Konfirmasi Pemesanan
              </button>
            <?php endif; ?>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>
<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script type="text/javascript">
  <?= session()->getFlashdata('pesan'); ?>

  $(document).ready(function() {
    var kodevoucherInit = "<?= $kodevoucher ?>";
    if (kodevoucherInit != '') {
      lihatKodeVoucher();
    }
  });

  function lihatKodeVoucher() {
    const kode_voucher = $('#kode_voucher').val();
    const idpaket = $('#idpaket').val();
    var nominal = $("#nominal").val();
    var diskon = $("#diskon").val();

    $.ajax({
      type: 'POST',
      data: {
        kode_voucher: kode_voucher,
        idpaket: idpaket
      },
      dataType: 'JSON',
      url: "<?= base_url('sw-siswa/transaksi/cek-kode-voucher') ?>",
      success: function(data) {
        if (data.length > 0) {
          var diskonHasil = (nominal * diskon) / 100;
          var hargaDiskon = nominal - diskonHasil;
          var voucher = (hargaDiskon * data[0].diskon_voucher) / 100;
          var hargaVoucher = hargaDiskon - voucher;

          $("#diskon_voucher").val(data[0].diskon_voucher);
          $("#info").html("<span class='text-success'><i class='fas fa-check-circle me-1'></i> Voucher Berhasil Digunakan!</span>");

          $("#tampil_diskon_voucher").html(`
                        <div class="d-flex flex-stack mb-4">
                            <span class="fw-bold text-gray-600 fs-6">Ekstra Voucher (${data[0].diskon_voucher}%)</span>
                            <span class="fw-bolder text-danger fs-6">- Rp ${number_format(voucher,'0','.','.')}</span>
                        </div>
                    `);

          // Update total di bagian bawah sidebar
          $("#main_total_display").html(`Rp ${number_format(hargaVoucher,'0','.','.')}`);

        } else {
          $("#info").html("<span class='text-danger'><i class='fas fa-times-circle me-1'></i> Voucher Tidak Berlaku</span>");
          $("#diskon_voucher").val(0);
          $("#tampil_diskon_voucher").empty();

          var normalTotal = nominal - (nominal * diskon / 100);
          $("#main_total_display").html(`Rp ${number_format(normalTotal,'0','.','.')}`);
        }
      }
    });
  };

  function number_format(number, decimals, dec_point, thousands_sep) {
    number = parseFloat(number).toFixed(decimals);
    var nstr = number.toString();
    var x = nstr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? dec_point + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) x1 = x1.replace(rgx, '$1' + thousands_sep + '$2');
    return x1 + x2;
  }
</script>

<script src="https://www.google.com/recaptcha/api.js?render=<?= getenv('RECAPTCHA_SITE_KEY') ?>"></script>
<script>
  function submitForm(e) {
    e.preventDefault();
    const form = e.target;
    if (!form.checkValidity()) {
      form.reportValidity();
      return false;
    }
    grecaptcha.ready(function() {
      grecaptcha.execute('<?= getenv('RECAPTCHA_SITE_KEY') ?>', {
        action: 'registrasi_pesan'
      }).then(function(token) {
        document.getElementById('recaptcha_token').value = token;
        form.submit();
      });
    });
    return false;
  }

  $(document).on('click', '#togglePassword', function() {
    const passwordField = $('#password');
    const eyeIcon = $('#eyeIcon');
    const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
    passwordField.attr('type', type);
    eyeIcon.toggleClass('fa-eye fa-eye-slash');
  });
</script>
<?= $this->endSection(); ?>
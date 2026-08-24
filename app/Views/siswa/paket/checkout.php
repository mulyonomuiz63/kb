<?= $this->extend('siswa/template/app'); ?>

<?= $this->section('styles'); ?>
<style>
  /* Custom Styling to match Metronic 8 Professional Look */
  .payment-option {
    border: 1px solid var(--kt-gray-200);
    transition: all 0.3s ease;
  }

  .payment-option:hover {
    border-color: var(--kt-primary) !important;
    background-color: var(--kt-primary-lighten) !important;
    transform: translateY(-3px);
  }

  .payment-option.active {
    border-color: var(--kt-primary);
    background-color: var(--kt-primary-lighten);
  }

  .copy-badge {
    cursor: pointer;
    transition: color 0.2s;
  }

  .copy-badge:hover {
    color: var(--kt-primary) !important;
  }

  #jam_skrng {
    font-family: 'Courier New', Courier, monospace;
    letter-spacing: 2px;
  }
</style>
<style>
  /* Membuat area input transparan menutupi seluruh box */
  .upload-container {
    border: 2px dashed #cbd5e0;
    transition: all 0.3s ease;
    cursor: pointer;
  }

  .upload-container:hover {
    border-color: #0086a7;
    background-color: #f0f9ff !important;
  }

  .file-input-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 2;
  }

  .border-dashed {
    border-style: dashed !important;
  }

  .rounded-lg {
    border-radius: 1rem !important;
  }

  /* Mempercantik tombol ubah metode */
  .btn-outline-secondary.border-dashed {
    color: #6c757d;
    background: #f8f9fa;
    border-width: 1.5px;
  }

  .btn-outline-secondary.border-dashed:hover {
    background: #e2e8f0;
    color: #333;
  }
</style>
<style>
  .selection-card {
    cursor: pointer;
    border: 2px solid #f3f6f9;
    transition: all 0.2s ease;
  }

  .selection-card:hover {
    border-color: #0086a7;
    background-color: #f0f9ff !important;
    transform: translateY(-2px);
  }

  .symbol-label {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background-color: #ebf8fa;
  }

  /* Mempercantik font inside modal */
  .fw-bold {
    font-weight: 700 !important;
  }

  .fw-semibold {
    font-weight: 600 !important;
  }

  .fs-6 {
    font-size: 1.05rem !important;
  }

  .fs-7 {
    font-size: 0.85rem !important;
  }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div id="kt_app_content" class="app-content flex-column-fluid py-3 py-lg-6 mt-4">
  <div id="kt_app_content_container" class="app-container container-xxl">

    <div class="row g-5 g-xl-10">
      <div class="col-lg-7">

        <?php if ($transaksi->jenis_bayar == ''): ?>
          <div class="card card-flush shadow-sm mb-5">
            <div class="card-header pt-7">
              <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold text-gray-800">Pilih Metode Pembayaran</span>
                <span class="text-muted mt-1 fw-semibold fs-7">Selesaikan pembayaran untuk mengaktifkan paket</span>
              </h3>
            </div>
            <div class="card-body">
              <a href="<?= base_url("sw-siswa/transaksi/manual-bayar/" . encrypt_url($transaksi->idtransaksi)) ?>"
                class="payment-option d-flex align-items-center p-6 rounded-3 mb-5 text-decoration-none">
                <div class="symbol symbol-40px me-5">
                  <span class="symbol-label bg-light-primary">
                    <i class="ki-outline ki-bank fs-2x text-primary"></i>
                  </span>
                </div>
                <div class="flex-grow-1">
                  <span class="fw-bold text-gray-800 d-block fs-6">Transfer Manual Bank</span>
                  <span class="text-muted fw-semibold fs-7">Konfirmasi manual oleh admin</span>
                </div>
                <i class="ki-outline ki-arrow-right fs-2 text-gray-400"></i>
              </a>

              <a href="javascript:void(0)" onclick="processSelection('<?= encrypt_url($transaksi->idtransaksi) ?>', 'online')" class="payment-option d-flex align-items-center p-6 rounded-3 text-decoration-none">
                <div class="symbol symbol-40px me-5">
                  <span class="symbol-label bg-light-success">
                    <i class="ki-outline ki-flash-circle fs-2x text-success"></i>
                  </span>
                </div>
                <div class="flex-grow-1">
                  <span class="fw-bold text-gray-800 d-block fs-6">Virtual Account (Otomatis)</span>
                  <span class="text-muted fw-semibold fs-7">Verifikasi instan via Midtrans</span>
                </div>
                <i class="ki-outline ki-arrow-right fs-2 text-gray-400"></i>
              </a>
            </div>
          </div>
        <?php endif; ?>

        <div id="manual" class="card card-flush shadow-sm collapse <?= ($transaksi->jenis_bayar == 'manual') ? 'show' : ''; ?>">
          <div class="card-header pt-7">
            <h3 class="card-title fw-bold text-gray-800">Detail Pembayaran Manual</h3>
          </div>
          <div class="card-body">
            <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6 mb-8">
              <i class="ki-outline ki-information-5 fs-2tx text-warning me-4"></i>
              <div class="d-flex flex-stack flex-grow-1">
                <div class="fw-semibold">
                  <h4 class="text-gray-900 fw-bold">Waktu Terbatas!</h4>
                  <div class="fs-6 text-gray-700">Selesaikan pembayaran sebelum:
                    <span class="badge badge-danger fw-bold fs-6 ms-2" id="jam_skrng">-- : -- : --</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="bg-gray-100 rounded p-6 mb-8">
              <div class="d-flex flex-stack mb-3">
                <span class="fw-semibold text-gray-600">Bank Tujuan:</span>
                <span class="fw-bold text-gray-800">Bank Mandiri</span>
              </div>
              <div class="d-flex flex-stack mb-3">
                <span class="fw-semibold text-gray-600">Nomor Rekening:</span>
                <div class="d-flex align-items-center">
                  <input type="hidden" id="norek" value="1660003837846">
                  <code class="fw-bolder fs-5 text-gray-900 me-2">1660003837846</code>
                  <button class="btn btn-icon btn-sm btn-light-primary" onclick="myFunction()">
                    <i class="ki-outline ki-copy fs-3"></i>
                  </button>
                </div>
              </div>
              <div class="d-flex flex-stack">
                <span class="fw-semibold text-gray-600">Atas Nama:</span>
                <span class="fw-bold text-gray-800">PT. Legalyn Konsultan Indonesia</span>
              </div>
            </div>

            <form id="form-pembayaran" action="<?= base_url('sw-siswa/transaksi/upload-bukti-bayar'); ?>" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
              <input type="hidden" name="idtransaksi" value="<?= $transaksi->idtransaksi; ?>">

              <div class="mb-5">
                <label class="form-label fw-bold mb-3">Upload Bukti Transfer</label>

                <div class="upload-container shadow-sm border-dashed p-5 text-center position-relative rounded-lg bg-light">
                  <input type="file" name="bukti_bayar" id="bukti_bayar" class="file-input-overlay" accept="image/*" required>

                  <div class="upload-placeholder">
                    <i class="fas fa-cloud-upload-alt text-primary mb-3" style="font-size: 3rem;"></i>
                    <h5 class="fw-bold text-dark">Klik atau Tarik File Ke Sini</h5>
                    <p class="text-muted small mb-0">Format: JPG, PNG, JPEG (Maks. 2MB)</p>
                  </div>

                  <div id="file-preview-name" class="mt-3 font-weight-bold text-success d-none">
                    <i class="fas fa-check-circle mr-2"></i> <span id="filename-text"></span>
                  </div>
                </div>
              </div>

              <div class="d-flex flex-column gap-3">
                <button type="submit" id="btn-submit" class="btn btn-primary btn-lg w-100 shadow-sm mb-3">
                  <i id="btn-icon" class="fas fa-paper-plane mr-2"></i>
                  <span id="btn-text">Konfirmasi Pembayaran</span>
                </button>

                <button type="button"
                  onclick="ubahMetode('<?= encrypt_url($transaksi->idtransaksi) ?>')"
                  class="btn btn-outline-secondary btn-sm w-100 border-dashed py-3">
                  <i class="fas fa-sync-alt mr-2"></i> Ubah Metode Pembayaran
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="card card-flush shadow-sm bg-body">
          <div class="card-header pt-7">
            <h3 class="card-title fw-bold text-gray-800">Ringkasan Order</h3>
          </div>
          <div class="card-body pt-0">
            <div class="d-flex align-items-center mb-7">
              <div class="symbol symbol-50px me-5">
                <span class="symbol-label bg-light-primary">
                  <i class="ki-outline ki-book-open fs-2x text-primary"></i>
                </span>
              </div>
              <div class="d-flex flex-column">
                <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold"><?= $transaksi->nama_paket; ?></a>
                <span class="text-muted fw-semibold">Tipe: <?= $transaksi->tagline; ?></span>
              </div>
            </div>

            <div class="separator separator-dashed mb-7"></div>

            <?php
            $diskon = $transaksi->nominal - ($transaksi->nominal - ($transaksi->nominal * $transaksi->diskon / 100));
            $totalDiskon = $transaksi->nominal - $diskon;
            $diskon_voucher = $totalDiskon - ($totalDiskon - ($totalDiskon * $transaksi->voucher / 100));
            ?>

            <div class="d-flex flex-stack mb-4">
              <span class="fw-semibold text-gray-600">Harga Paket</span>
              <span class="fw-bold text-gray-800">Rp <?= number_format($transaksi->nominal, 0, '.', '.'); ?></span>
            </div>

            <div class="d-flex flex-stack mb-4">
              <span class="fw-semibold text-gray-600">Diskon (<?= $transaksi->diskon; ?>%)</span>
              <span class="fw-bold text-success">- Rp <?= number_format($diskon, 0, '.', '.'); ?></span>
            </div>

            <?php if ($transaksi->voucher != '0'): ?>
              <div class="d-flex flex-stack mb-4">
                <span class="fw-semibold text-gray-600">Voucher (<?= $transaksi->voucher; ?>%)</span>
                <span class="fw-bold text-success">- Rp <?= number_format($diskon_voucher, 0, '.', '.'); ?></span>
              </div>
            <?php endif; ?>

            <div class="separator separator-dashed mb-6"></div>

            <div class="d-flex flex-stack mb-6">
              <span class="fw-bolder text-gray-800 fs-4">Total Bayar</span>
              <span class="fw-bolder text-primary fs-3">Rp <?= number_format(($transaksi->nominal - $diskon - $diskon_voucher), 0, '.', '.'); ?></span>
            </div>

            <?php if ($transaksi->keterangan != null) : ?>
              <div class="alert alert-dismissible bg-light-danger d-flex align-items-center p-5 mb-0">
                <i class="ki-outline ki-shield-cross fs-2hx text-danger me-4"></i>
                <div class="d-flex flex-column">
                  <span class="fw-bold text-danger">Catatan Admin:</span>
                  <span class="text-gray-700 small"><?= $transaksi->keterangan; ?></span>
                </div>
              </div>
            <?php endif ?>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>

<script>
  fbq('track', 'AddPaymentInfo');
</script>

<script>
  document.getElementById('bukti_bayar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const previewDiv = document.getElementById('file-preview-name');
    const filenameText = document.getElementById('filename-text');
    const placeholder = document.querySelector('.upload-placeholder');

    if (file) {
      // Membaca file untuk dijadikan preview gambar
      const reader = new FileReader();
      reader.onload = function(event) {
        // Mengganti icon upload dengan gambar yang dipilih
        placeholder.innerHTML = `
                <div class="mb-3">
                    <img src="${event.target.result}" style="max-height: 120px; border-radius: 8px;" class="shadow-sm">
                </div>
                <h5 class="fw-bold text-success">File Siap Diupload!</h5>
            `;

        previewDiv.classList.remove('d-none');
        filenameText.innerText = file.name;
      }
      reader.readAsDataURL(file);
    }
  });
  // Countdown Logic (Fungsi tetap sama)
  var countDownDate = new Date("<?= $transaksi->tgl_exp ?>").getTime();
  var x = setInterval(function() {
    var now = new Date().getTime();
    var distance = countDownDate - now;

    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    // Tambah padding nol di depan angka
    hours = String(hours).padStart(2, '0');
    minutes = String(minutes).padStart(2, '0');
    seconds = String(seconds).padStart(2, '0');

    document.getElementById("jam_skrng").innerHTML = hours + " : " + minutes + " : " + seconds;

    if (distance < 0) {
      clearInterval(x);
      document.getElementById("jam_skrng").innerHTML = "Expired";
      document.location.reload();
    }
  }, 1000);

  // Copy to Clipboard (Metronic Style Alert)
  function myFunction() {
    var norek = $('#norek').val();
    navigator.clipboard.writeText(norek);

    // Menggunakan Swal bawaan Metronic
    Swal.fire({
      text: "Nomor rekening berhasil disalin!",
      icon: "success",
      buttonsStyling: false,
      confirmButtonText: "Ok, mengerti",
      customClass: {
        confirmButton: "btn btn-primary"
      }
    });
  }

  // Toggle Collapse Manual (Fungsi tetap sama)
  var jenis_pembayaran = '<?= $transaksi->jenis_bayar ?>';
  if (jenis_pembayaran == 'manual') {
    // Jika menggunakan Bootstrap 5 (Standard Metronic 8)
    const manualEl = document.getElementById('manual');
    if (manualEl && !manualEl.classList.contains('show')) {
      new bootstrap.Collapse(manualEl, {
        show: true
      });
    }
  }
</script>
<script>
  function ubahMetode(idEncrypted) {
    Swal.fire({
      title: 'Pilih Metode Pembayaran',
      html: `
            <div class="row mt-4 px-2">
                <div class="col-12 mb-3">
                    <a href="<?= base_url("sw-siswa/transaksi/manual-bayar/" . encrypt_url($transaksi->idtransaksi)) ?>" class="card p-4 selection-card border-2">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-45px mr-3 bg-light-primary p-3 rounded">
                                <i class="fas fa-university text-primary fs-4"></i>
                            </div>
                            <div class="text-left">
                                <span class="fw-bold text-dark d-block fs-6">Transfer Manual Bank</span>
                                <span class="text-muted fw-semibold fs-7 small">Konfirmasi manual oleh admin</span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12">
                    <div class="card p-4 selection-card border-2" onclick="processSelection('${idEncrypted}', 'online')">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-45px mr-3 bg-light-success p-3 rounded">
                                <i class="fas fa-bolt text-success fs-4"></i>
                            </div>
                            <div class="text-left">
                                <span class="fw-bold text-dark d-block fs-6">Virtual Account (Otomatis)</span>
                                <span class="text-muted fw-semibold fs-7 small">Verifikasi instan via Midtrans</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `,
      showConfirmButton: false,
      showCancelButton: true,
      cancelButtonText: 'Batal',
      customClass: {
        container: 'my-swal-container'
      }
    });
  }

  function processSelection(idEncrypted, method) {
    // Tampilkan loading saat update database
    Swal.fire({
      title: 'Menyiapkan Pembayaran...',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });

    $.ajax({
      url: "<?= base_url('sw-siswa/transaksi/update-metode-pembayaran'); ?>", // Route update DB yang kita buat tadi
      type: "POST",
      data: {
        "<?= csrf_token() ?>": "<?= csrf_hash() ?>",
        "id": idEncrypted,
        "metode": method
      },
      dataType: "json",
      success: function(response) {
        if (response.status === 'success') {
          if (method === 'Transfer Manual') {
            // Jika manual, langsung reload ke halaman instruksi manual
            window.location.href = "<?= base_url('sw-siswa/transaksi/manual-bayar/') ?>/" + idEncrypted;
          } else {
            // Jika Virtual Account, panggil fungsi Midtrans
            panggilMidtrans(idEncrypted);
          }
        } else {
          Swal.fire('Error', 'Gagal memperbarui metode', 'error');
        }
      },
      error: function(err) {
        console.error(err.responseText);
        Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
      }
    });
  }

  function panggilMidtrans(idEncrypted) {
    $.ajax({
      url: "<?= base_url('sw-siswa/transaksi/midtrans-bayar') ?>/" + idEncrypted,
      type: "GET",
      dataType: "JSON",
      success: function(response) {
        if (response.status) {
          Swal.close(); // Tutup loading Swal
          window.snap.pay(response.snap_token, {
            onSuccess: function(result) {
              window.location.href = "<?= base_url('sw-siswa/transaksi') ?>";
            },
            onPending: function(result) {
              window.location.href = "<?= base_url('sw-siswa/transaksi') ?>";
            },
            onError: function(result) {
              Swal.fire('Gagal', 'Pembayaran gagal.', 'error').then(() => {
                location.reload();
              });
            },
            onClose: function() {
              location.reload(); // Reload agar tampilan update ke status VA
            }
          });
        } else {
          Swal.fire('Error', response.message, 'error');
        }
      }
    });
  }
</script>
<script>
  document.getElementById('form-pembayaran').addEventListener('submit', function(e) {
    // Tombol hanya akan berubah jika form sudah lolos validasi HTML (contoh: file required sudah diisi)
    
    let btn = document.getElementById('btn-submit');
    let icon = document.getElementById('btn-icon');
    let text = document.getElementById('btn-text');

    // 1. Disable tombol agar tidak bisa diklik lagi
    btn.disabled = true;
    btn.classList.add('disabled');

    // 2. Ganti ikon menjadi spinner loading (menggunakan spinner bawaan Bootstrap)
    icon.className = 'spinner-border spinner-border-sm mr-2';

    // 3. Ubah teks
    text.innerText = 'Memproses...';
  });
</script>
<?= $this->endSection(); ?>
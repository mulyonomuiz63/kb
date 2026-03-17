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

              <a href="javascript:void(0)"
                id="btn-bayar-midtrans"
                data-id="<?= encrypt_url($transaksi->idtransaksi) ?>"
                class="payment-option d-flex align-items-center p-6 rounded-3 text-decoration-none">
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

            <form action="<?= base_url('sw-siswa/transaksi/upload-bukti-bayar'); ?>" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
              <input type="hidden" name="idtransaksi" value="<?= $transaksi->idtransaksi; ?>">

              <div class="mb-5">
                <label class="form-label fw-bold">Upload Bukti Transfer</label>
                <input type="file" name="bukti_bayar" class="form-control form-control-solid" accept="image/*" required>
                <div class="form-text text-muted">Format: JPG, PNG, JPEG. Max 2MB.</div>
              </div>

              <button type="submit" class="btn btn-primary w-100 py-4 fw-bold fs-6">
                <i class="ki-outline ki-cloud-upload fs-2 me-2"></i> Konfirmasi Pembayaran
              </button>
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
                <span class="text-muted fw-semibold">Tipe: <?= $transaksi->jenis_paket; ?></span>
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
  // Integrasi Flashdata (SweetAlert)
  <?= session()->getFlashdata('pesan'); ?>

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
    $(document).on('click', '#btn-bayar-midtrans', function(e) {
        e.preventDefault();

        let btn = $(this);
        let idt = btn.data('id');

        // Ambil token CSRF dari meta tag (pastikan meta tag sudah ada di header)
        let csrfName = $('meta[name="X-CSRF-TOKEN"]').attr('name') || '<?= csrf_token() ?>';
        let csrfHash = $('meta[name="X-CSRF-TOKEN"]').attr('content') || '<?= csrf_hash() ?>';

        // Beri loading pada tombol agar tidak diklik berkali-kali
        btn.addClass('disabled').html('<span class="spinner-border spinner-border-sm"></span> Loading...');

        $.ajax({
            url: "<?= base_url('sw-siswa/transaksi/midtrans-bayar') ?>/" + idt, // Sesuaikan route Anda
            type: "GET",
            data: {
                [csrfName]: csrfHash
            },
            dataType: "JSON",
            success: function(response) {
                // Update CSRF Hash agar request selanjutnya tidak error 403
                if (response.csrf_hash) {
                    $('meta[name="X-CSRF-TOKEN"]').attr('content', response.csrf_hash);
                }

                if (response.status) {
                    // Eksekusi Modal Snap
                    window.snap.pay(response.snap_token, {
                        onSuccess: function(result) {
                            Swal.fire('Berhasil!', 'Pembayaran Anda telah sukses.', 'success').then(() => {
                                // Arahkan ke halaman riwayat transaksi agar user bisa melihat status terupdate
                                window.location.href = "<?= base_url('sw-siswa/transaksi') ?>";
                            });
                        },
                        onPending: function(result) {
                            // Tampilkan nomor VA atau instruksi pembayaran (opsional, Midtrans sudah menampilkannya)
                            Swal.fire('Pending', 'Silahkan selesaikan pembayaran sesuai instruksi.', 'warning').then(() => {
                                window.location.href = "<?= base_url('sw-siswa/transaksi') ?>";
                            });
                        },
                        onError: function(result) {
                            Swal.fire('Gagal', 'Pembayaran gagal atau dibatalkan.', 'error');
                            btn.removeClass('disabled').text('Lanjut Bayar');
                        },
                        onClose: function() {
                            // Jika user menutup modal tanpa bayar
                            console.log('Customer closed the popup without finishing the payment');
                            btn.removeClass('disabled').text('Lanjut Bayar');
                        }
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                    btn.removeClass('disabled').text('Lanjut Bayar');
                }
            },
            error: function() {
                Swal.fire('Error', 'Gagal menghubungi server.', 'error');
                btn.removeClass('disabled').text('Lanjut Bayar');
            }
        });
    });
</script>
<?= $this->endSection(); ?>
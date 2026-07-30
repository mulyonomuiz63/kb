<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pembayaran Webinar</title>
    
    <!-- Google Fonts & Bootstrap -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8faff;
            color: #333;
        }
        .invoice-card {
            max-width: 600px;
            margin: 50px auto;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(13, 110, 253, 0.1);
            background: #fff;
            overflow: hidden;
        }
        .invoice-header {
            background-color: #0d6efd;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .icon-success {
            font-size: 60px;
            margin-bottom: 15px;
        }
    </style>
    <?php if (strtolower(setting('midtrans_is_production')) == 'true'): ?>
        <script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="<?= setting('midtrans_client_key') ?>"></script>
    <?php else: ?>
        <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= setting('midtrans_client_key') ?>"></script>
    <?php endif; ?>
</head>
<body>

    <div class="container">
        <div class="invoice-card">
            <div class="invoice-header">
                <i class="fa-solid fa-circle-check icon-success"></i>
                <h3 class="fw-bold mb-1">Pendaftaran Berhasil!</h3>
                <p class="mb-0 opacity-75">Satu langkah lagi untuk menyelesaikan pendaftaran Anda.</p>
            </div>
            
            <div class="p-4 p-md-5 text-center">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success border-dashed mb-4">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <h4 class="fw-bold text-dark mb-3">Selesaikan Pembayaran Anda</h4>
                <p class="text-secondary mb-4">
                    Sistem kami sedang menyiapkan gerbang pembayaran. Jika popup pembayaran tidak muncul secara otomatis, silakan klik tombol di bawah ini.
                </p>

                <!-- Tombol Manual untuk memicu pembayaran -->
                <button id="pay-button" class="btn btn-primary btn-lg fw-bold rounded-pill shadow-sm px-5 py-3 w-100">
                    <i class="fa-solid fa-credit-card me-2"></i> Bayar Sekarang
                </button>

                <div class="mt-4">
                    <small class="text-muted d-block mb-2">Pembayaran aman didukung oleh:</small>
                    <img src="https://midtrans.com/assets/img/midtrans-logo.svg" height="25" alt="Midtrans Logo" style="opacity: 0.7;">
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT LOGIKA PEMBAYARAN MIDTRANS -->
    <script type="text/javascript">
        // Mengambil Token Snap yang dikirimkan dari Controller (via with())
        var snapToken = "<?= session()->getFlashdata('snapToken') ?>";

        // Memicu popup Snap
        function triggerPayment() {
            if (snapToken) {
                window.snap.pay(snapToken, {
                    onSuccess: function(result){
                        // Aksi jika pembayaran berhasil
                        alert("Pembayaran berhasil!");
                        window.location.href = "<?= base_url('member/dashboard') ?>"; // Redirect ke dashboard user
                    },
                    onPending: function(result){
                        // Aksi jika pembayaran tertunda (menunggu transfer bank/minimarket)
                        alert("Menunggu pembayaran Anda!");
                        window.location.href = "<?= base_url('member/transaksi') ?>"; // Redirect ke riwayat transaksi
                    },
                    onError: function(result){
                        // Aksi jika pembayaran gagal
                        alert("Pembayaran gagal!");
                    },
                    onClose: function(){
                        // Aksi jika user menutup popup tanpa membayar
                        alert("Anda menutup popup tanpa menyelesaikan pembayaran.");
                    }
                });
            } else {
                alert("Token pembayaran tidak ditemukan. Silakan ulangi pendaftaran.");
            }
        }

        // Jalankan pembayaran otomatis ketika halaman dimuat
        document.addEventListener("DOMContentLoaded", function() {
            if (snapToken) {
                triggerPayment();
            }
        });

        // Jalankan pembayaran ketika tombol ditekan (jika popup diblokir browser)
        document.getElementById('pay-button').onclick = function(){
            triggerPayment();
        };
    </script>
</body>
</html>
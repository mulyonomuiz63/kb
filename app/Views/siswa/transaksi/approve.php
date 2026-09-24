<?php
// === KALKULASI PEMBAYARAN ===
// Diletakkan di atas agar $total_bayar bisa dibaca oleh Meta Pixel di dalam <head>
$diskon =$transaksi->nominal - ($transaksi->nominal - ($transaksi->nominal * $transaksi->diskon / 100));$totalDiskon = $transaksi->nominal -$diskon;
$diskon_voucher = $totalDiskon - ($totalDiskon - ($totalDiskon * $transaksi->voucher / 100));
$total_bayar =$transaksi->nominal - $diskon -$diskon_voucher;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - Kelasbrevet</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        
        // GANTI DENGAN ID PIXEL ANDA
        fbq('init', 'MASUKKAN_ID_PIXEL_ANDA_DI_SINI'); 
        fbq('track', 'PageView');
        
        // EVENT PURCHASE MENGGUNAKAN TOTAL BAYAR ASLI
        fbq('track', 'Purchase', {
            value: <?= (float)$total_bayar; ?>,
            currency: 'IDR'
        });
    </script>
    <noscript>
        <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=MASUKKAN_ID_PIXEL_ANDA_DI_SINI&ev=PageView&noscript=1"/>
    </noscript>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #29459A 0%, #15265c 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }

        .success-card {
            background: #ffffff;
            max-width: 480px;
            width: 100%;
            border-radius: 20px;
            border-top: 6px solid #29459A;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            padding: 40px 35px;
            text-align: center;
            animation: slideUpFade 0.7s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        @keyframes slideUpFade {
            to { opacity: 1; transform: translateY(0); }
        }

        .checkmark {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: block;
            stroke-width: 4;
            stroke: #fff;
            stroke-miterlimit: 10;
            margin: 0 auto 20px auto;
            box-shadow: inset 0px 0px 0px #16a34a;
            animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
        }
        .checkmark-circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 4;
            stroke-miterlimit: 10;
            stroke: #16a34a;
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }
        .checkmark-check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
        }
        @keyframes stroke { 100% { stroke-dashoffset: 0; } }
        @keyframes scale {
            0%, 100% { transform: none; }
            50% { transform: scale3d(1.1, 1.1, 1); }
        }
        @keyframes fill { 100% { box-shadow: inset 0px 0px 0px 50px #16a34a; } }

        /* --- DESAIN INVOICE & RINCIAN --- */
        .invoice-box {
            background-color: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .countdown-box {
            background: linear-gradient(145deg, rgba(41, 69, 154, 0.03), rgba(41, 69, 154, 0.08));
            border: 1px solid rgba(41, 69, 154, 0.1);
            border-radius: 14px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .countdown-number {
            color: #29459A; 
            font-weight: 800;
            font-size: 2rem;
            display: inline-block;
            animation: pulseText 1s infinite;
        }

        @keyframes pulseText {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.65; transform: scale(0.92); }
        }

        .btn-custom {
            background-color: #29459A;
            color: #ffffff;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 14px 20px;
            border-radius: 50px; 
            border: none;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 8px 15px rgba(41, 69, 154, 0.2);
        }
        .btn-custom:hover {
            background-color: #1a2a5c; 
            transform: translateY(-3px);
            box-shadow: 0 12px 20px rgba(41, 69, 154, 0.35);
            color: #ffffff;
        }
    </style>
</head>
<body>

    <div class="container d-flex justify-content-center p-3">
        <div class="success-card">
            
            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>

            <h2 class="fw-bold text-dark mb-4">Pembayaran Sukses!</h2>
            
            <div class="invoice-box text-start">
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-secondary" style="font-size: 0.9rem;">ID Transaksi</span>
                    <span class="badge bg-light text-dark border border-secondary border-opacity-25">#<?= esc($transaksi->idtransaksi ?? '-'); ?></span>
                </div>
                
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-secondary" style="font-size: 0.9rem;">Paket Kelas</span>
                    <span class="fw-bold text-dark text-end" style="font-size: 0.9rem; max-width: 60%;">
                        <?= esc($transaksi->nama_paket ?? '-'); ?>
                    </span>
                </div>

                <div class="d-flex flex-stack justify-content-between mb-2">
                    <span class="fw-semibold text-secondary" style="font-size: 0.9rem;">Harga Paket</span>
                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">Rp <?= number_format($transaksi->nominal, 0, '.', '.'); ?></span>
                </div>

                <div class="d-flex flex-stack justify-content-between mb-2">
                    <span class="fw-semibold text-secondary" style="font-size: 0.9rem;">Diskon (<?= $transaksi->diskon; ?>%)</span>
                    <span class="fw-bold text-success" style="font-size: 0.9rem;">- Rp <?= number_format($diskon, 0, '.', '.'); ?></span>
                </div>

                <?php if ($transaksi->voucher != '0'): ?>
                <div class="d-flex flex-stack justify-content-between mb-2">
                    <span class="fw-semibold text-secondary" style="font-size: 0.9rem;">Voucher (<?= $transaksi->voucher; ?>%)</span>
                    <span class="fw-bold text-success" style="font-size: 0.9rem;">- Rp <?= number_format($diskon_voucher, 0, '.', '.'); ?></span>
                </div>
                <?php endif; ?>

                <hr class="my-3 border-secondary opacity-25" style="border-style: dashed;">

                <div class="d-flex flex-stack justify-content-between align-items-center mt-3">
                    <span class="fw-bolder text-dark" style="font-size: 1.1rem;">Total Bayar</span>
                    <span class="fw-bolder" style="font-size: 1.3rem; color: #29459A;">Rp <?= number_format($total_bayar, 0, '.', '.'); ?></span>
                </div>

            </div>
            <?php if ($transaksi->keterangan != null) : ?>
            <div class="alert alert-dismissible bg-light-danger d-flex align-items-center p-3 mb-4 border border-danger border-opacity-25 rounded-3 text-start">
                <i class="ki-outline ki-shield-cross fs-2x text-danger me-3"></i>
                <div class="d-flex flex-column">
                    <span class="fw-bold text-danger mb-1" style="font-size: 0.9rem;">Catatan Admin:</span>
                    <span class="text-danger opacity-75" style="font-size: 0.85rem; line-height: 1.4;"><?= esc($transaksi->keterangan); ?></span>
                </div>
            </div>
            <?php endif ?>

            <p class="text-secondary mb-4" style="font-size: 0.95rem; line-height: 1.6;">
                Terima kasih, <strong><?= esc($transaksi->nama_siswa ?? 'Peserta'); ?></strong>. Pembayaran Anda telah berhasil kami verifikasi.
            </p>

            <div class="countdown-box">
                <p class="mb-0 text-secondary fw-medium" style="font-size: 0.9rem;">
                    Anda akan dialihkan otomatis dalam <br>
                    <span id="countdown" class="countdown-number mt-1">5</span>
                </p>
            </div>

            <button id="btnRedirect" class="btn btn-custom w-100">
                Kembali ke Kelasbrevet
            </button>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let waktuTunggu = 5; // 5 detik dalam milidetik
            const urlTujuan = "<?= base_url('sw-siswa') ?>"; 
            
            const countdownElement = document.getElementById('countdown');
            const btnRedirect = document.getElementById('btnRedirect');

            const interval = setInterval(function() {
                waktuTunggu--;
                countdownElement.textContent = waktuTunggu;

                if (waktuTunggu <= 0) {
                    clearInterval(interval);
                    window.location.href = urlTujuan; 
                }
            }, 1000); 

            btnRedirect.addEventListener('click', function() {
                clearInterval(interval); 
                
                this.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memuat...`;
                this.style.pointerEvents = 'none';
                this.style.opacity = '0.8';
                
                window.location.href = urlTujuan; 
            });
        });
    </script>
</body>
</html>
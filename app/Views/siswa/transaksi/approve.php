<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - Kelasbrevet</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* --- TEMA & BACKGROUND (Menggunakan Warna Brand Anda) --- */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            /* Background gradien elegan berbasis #29459A */
            background: linear-gradient(135deg, #29459A 0%, #15265c 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* --- STYLING CARD KONTEN --- */
        .success-card {
            background: #ffffff;
            max-width: 440px;
            width: 100%;
            border-radius: 20px;
            /* Aksen garis atas dengan warna brand */
            border-top: 6px solid #29459A;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            padding: 45px 35px;
            text-align: center;
            /* Animasi masuk */
            animation: slideUpFade 0.7s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        @keyframes slideUpFade {
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- ANIMASI SVG CHECKMARK --- */
        .checkmark {
            width: 85px;
            height: 85px;
            border-radius: 50%;
            display: block;
            stroke-width: 4;
            stroke: #fff;
            stroke-miterlimit: 10;
            margin: 0 auto 20px auto;
            /* Menggunakan warna hijau sukses standar UI/UX */
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

        /* --- KOTAK COUNTDOWN ELEGAN --- */
        .countdown-box {
            /* Background tint biru sangat tipis yang menyatu */
            background: linear-gradient(145deg, rgba(41, 69, 154, 0.03), rgba(41, 69, 154, 0.08));
            border: 1px solid rgba(41, 69, 154, 0.1);
            border-radius: 14px;
            padding: 18px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .countdown-number {
            color: #29459A; /* Warna brand untuk highlight angka */
            font-weight: 800;
            font-size: 2rem;
            display: inline-block;
            animation: pulseText 1s infinite;
        }

        @keyframes pulseText {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.65; transform: scale(0.92); }
        }

        /* --- KUSTOMISASI TOMBOL BRAND --- */
        .btn-custom {
            background-color: #29459A;
            color: #ffffff;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 14px 20px;
            border-radius: 50px; /* Rounded pill */
            border: none;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 8px 15px rgba(41, 69, 154, 0.2);
        }
        
        .btn-custom:hover {
            background-color: #1a2a5c; /* Warna sedikit lebih gelap saat di-hover */
            transform: translateY(-3px);
            box-shadow: 0 12px 20px rgba(41, 69, 154, 0.35);
            color: #ffffff;
        }

        .btn-custom:active {
            transform: translateY(0);
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

            <h2 class="fw-bold text-dark mb-3">Pembayaran Sukses!</h2>
            <p class="text-secondary mb-4" style="font-size: 0.95rem; line-height: 1.7;">
                Terima kasih. Pembayaran Anda telah berhasil diverifikasi dan diproses oleh sistem Kelasbrevet.
            </p>

            <div class="countdown-box">
                <p class="mb-0 text-secondary fw-medium" style="font-size: 0.9rem;">
                    Anda akan dialihkan otomatis dalam <br>
                    <span id="countdown" class="countdown-number mt-1">5</span>
                </p>
            </div>

            <button id="btnRedirect" class="btn btn-custom w-100">
                Kembali Ke Kelasbrevet
            </button>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // === KONFIGURASI ===
            let waktuTunggu = 5; 
            const urlTujuan = "<?= base_url('sw-siswa/transaksi') ?>"; // GANTI DENGAN URL ROUTE ANDA
            
            const countdownElement = document.getElementById('countdown');
            const btnRedirect = document.getElementById('btnRedirect');

            // Fungsi hitung mundur
            const interval = setInterval(function() {
                waktuTunggu--;
                countdownElement.textContent = waktuTunggu;

                if (waktuTunggu <= 0) {
                    clearInterval(interval);
                    window.location.href = urlTujuan; // Eksekusi redirect otomatis
                }
            }, 1000); 

            // Fungsi klik manual (Jika user mengklik tombol)
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
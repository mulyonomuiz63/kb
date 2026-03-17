<?= $this->extend('landing/template'); ?>
<?= $this->section('css'); ?>
<style>

    #twibbon {
      background: linear-gradient(to bottom, #1e3c72, #2a5298);
      color: white;
      font-family: Arial, sans-serif;
    }
    canvas {
        max-width: 100%; 
    }
    /* Card mockup */
    .mockup {
      width: 250px;
      margin: auto;
      border-radius: 16px;
      background: #fff;
      box-shadow: 0 4px 15px rgba(0,0,0,0.15);
      overflow: hidden;
      text-align: left;
    }

    /* Header */
    .mockup-header {
      display: flex;
      align-items: center;
      padding: 10px 15px;
      font-weight: bold;
      font-size: 14px;
    }
    .mockup-header img {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      margin-right: 10px;
    }

    /* Gambar utama */
    .mockup img.main {
      width: 100%;
      display: block;
    }

    /* Footer likes */
    .mockup-footer {
      padding: 10px 15px;
      font-size: 14px;
      display: flex;
      align-items: center;
      color: #333;
    }
    .mockup-footer span.heart {
      color: red;
      margin-right: 6px;
      font-size: 16px;
    }

    /* Caption bawah */
    .caption {
      margin-top: 20px;
    }
    .caption strong {
      display: block;
      font-size: 18px;
      margin-bottom: 6px;
    }
    .caption small {
      font-size: 14px;
      color: #555;
    }
    .caption small a {
      color: red;
      text-decoration: none;
    }

    /* Share icons */
    .share {
      margin-top: 20px;
    }
    .share p {
      font-weight: bold;
      margin-bottom: 10px;
    }
    .share-icons {
      display: flex;
      gap: 15px;
    }
    .share-icons a img {
      width: 36px;
      height: 36px;
      transition: transform 0.2s;
    }
    .share-icons a img:hover {
      transform: scale(1.2);
    }
    .text-box {
      border: 1px solid #ccc;      /* garis border */
      padding: 10px;               /* jarak teks dengan border */
      border-radius: 4px;          /* sudut melengkung */
      background-color: #fff;      /* warna background */
      font-family: Arial, sans-serif;
      font-size: 14px;
      color: #333;
      width: 100%;   
      white-space: pre-wrap;       /* supaya enter baris tetap tampil */
    }
    .custom-card {
      background-color: #ffece1; /* warna peach */
      border-radius: 0.75rem;
    }
    .icon-circle {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 0.5rem auto;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    
    .info-text {
      font-size: 0.9rem;
      color: #555;
    }
    textarea {
        width: 100%;
        min-height: 80px;          /* tinggi minimal */
        resize: none;              /* nonaktifkan manual resize */
        overflow: hidden;          /* sembunyikan scrollbar */
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-sizing: border-box;
        transition: height 0.2s ease;
    }
  </style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<!-- Testimonial End -->
        <div class="section py-4 mt-2 " id="twibbon">
            <div class="container d-flex justify-content-center">
                <div class="row card d-none " id="TampilHasilTwibbon" style="width:300px">
                    <div class="col-12">
                        <div class="mockup my-4">
                            <!-- Header -->
                            <div class="mockup-header">
                              <img src="<?= base_url(favicon()) ?>" alt="User">
                              KelasBrevet
                            </div>
                        
                            <!-- Gambar Twibbon -->
                            <img id="hasilTwibbon" src="" alt="Hasil Twibbon" class="img-fluid rounded border main">
                        
                            <!-- Footer -->
                            <div class="mockup-footer">
                              <span class="heart">❤️</span> 10.456 likes
                            </div>
                        </div>
                        <div clss="mx-2">
                            <textarea class="text-box text-wrap" id="textContent"><?= strip_tags($twibbon->caption) ?></textarea>
                        </div>
                        <!-- Share icons -->
                         <div class="share">
                          <!-- Caption -->
                          <div class="caption">
                            <strong>Saatnya menyebarkan foto kemedia sosial kamu 🎉</strong>
                            <div class="d-flex flex-row">
                                <button class="badge text-bg-info border-0 text-white fw-bold p-2 my-2 btnDownload downloadHasil" data-twibbon="<?= encrypt_url($twibbon->idtwibbon); ?>">Unduh</button>
                                <button class="badge text-bg-warning border-0 text-white fw-bold p-2 m-2  btnDownload" onclick="copyText()">Salin</button>
                                <a href="<?= base_url('twibbon/'.$twibbon->url) ?>" class="badge text-bg-info border-0 text-white fw-bold p-2 my-2 d-block w-100 text-center"><i class="bi bi-arrow-clockwise"> Buat Lagi!</i></a>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="row card" id="TampilProsesTwibbon" style="width:300px">
                    <div class="col-12 text-center">
                        <h2 class="text-wrap my-4 mx-4" style="font-size:16px"><?= $twibbon->judul ?></h2>
                        <input type="file" id="uploadFoto" accept="image/*" style="display:none">
                        
                        <div class="custom-card mb-2">
                            <!-- ikon lingkaran -->
                            <div class="mockup-header d-flex justify-content-center">
                              <img src="<?= base_url(favicon()) ?>" alt="User">
                              KelasBrevet
                            </div>
                        
                            <!-- informasi -->
                            <div class="d-flex justify-content-center gap-3 info-text pb-2">
                              <span><i class="bi bi-people-fill"></i> <?= $twibbon->pengguna ?> dukungan</span>
                              <span><i class="bi bi-clock"></i> <?= timeAgo($twibbon->created_at) ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                      <canvas id="canvas"></canvas>
                    </div>
                    <div class="col-12 text-center">
                        <div class="" id="pilihFoto">
                            <button class="badge text-bg-info border-0 text-white fw-bold p-2 my-2 d-block w-100 text-center btnPilihFoto" id="btnUpload1"><i class="bi bi-camera"></i> Pilih Foto</button>
                        </div>
                        <div class="d-flex flex-row d-none" id="selanjutnya">
                            <button class="badge text-bg-info border-0 text-white fw-bold p-2 my-2" id="btnUpload2"><i class="bi bi-camera"></i></button>
                            <button class="badge text-bg-info border-0 text-white fw-bold p-2 my-2 ms-2 d-block w-100 text-center btnSelanjutnya">Selanjutnya</button>
                        </div>
                    </div>
                </div>
                  <!-- Tombol Upload Foto -->
            </div>
        </div>
        <!-- Testimonial End -->
<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
    <script>
        const canvas = document.getElementById("canvas");
        const ctx = canvas.getContext("2d");
    
        const twibbon = new Image();
        twibbon.src = "<?= base_url('uploads/twibbon/thumbnails/'.$twibbon->file) ?>"; // twibbon PNG transparan
    
        let foto = new Image();
        let fotoX = 0, fotoY = 0;
        let scale = 1;
        let dragging = false;
        let startX, startY;
        let lastDist = 0;
    
        // Render utama
        function render() {
          ctx.clearRect(0, 0, canvas.width, canvas.height);
        
          // Gradien merah marun terang ke biru cerah
          const gradient = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
          gradient.addColorStop(0, '#b22222'); // merah terang
          gradient.addColorStop(1, '#4169e1'); // biru cerah
          ctx.fillStyle = gradient;
          ctx.fillRect(0, 0, canvas.width, canvas.height);
        
          // Foto pengguna
          if (foto.src) {
            ctx.drawImage(foto, fotoX, fotoY, foto.width * scale, foto.height * scale);
          }
        
          // Overlay twibbon
          ctx.drawImage(twibbon, 0, 0, twibbon.width, twibbon.height);
        }
    
    
    
    
        // Upload dari galeri
        document.getElementById("btnUpload1").addEventListener("click", () => {
          document.getElementById("uploadFoto").click();
        });
        document.getElementById("btnUpload2").addEventListener("click", () => {
          document.getElementById("uploadFoto").click();
        });
    
        document.getElementById("uploadFoto").addEventListener("change", function(e) {
          const reader = new FileReader();
          reader.onload = function(event) {
            foto = new Image();
            foto.onload = () => resetFoto();
            foto.src = event.target.result;
          };
          reader.readAsDataURL(e.target.files[0]);
        });
    
        // Reset posisi foto ke tengah
        function resetFoto() {
          scale = Math.min(canvas.width / foto.width, canvas.height / foto.height);
          fotoX = (canvas.width - foto.width * scale) / 2;
          fotoY = (canvas.height - foto.height * scale) / 2;
          render();
        }
        // Drag pakai mouse
        canvas.addEventListener("mousedown", (e) => {
          dragging = true;
          startX = e.offsetX - fotoX;
          startY = e.offsetY - fotoY;
        });
    
        canvas.addEventListener("mousemove", (e) => {
          if (dragging) {
            fotoX = e.offsetX - startX;
            fotoY = e.offsetY - startY;
            render();
          }
        });
    
        canvas.addEventListener("mouseup", () => dragging = false);
        canvas.addEventListener("mouseleave", () => dragging = false);
    
        // Zoom pakai scroll mouse
        canvas.addEventListener("wheel", (e) => {
          e.preventDefault();
          const zoomFactor = 1.1;
          if (e.deltaY < 0) {
            scale *= zoomFactor;
          } else {
            scale /= zoomFactor;
          }
          render();
        });
    
        // Drag & Pinch di HP
        canvas.addEventListener("touchstart", (e) => {
          if (e.touches.length === 1) {
            dragging = true;
            startX = e.touches[0].clientX - fotoX;
            startY = e.touches[0].clientY - fotoY;
          } else if (e.touches.length === 2) {
            lastDist = getDistance(e.touches[0], e.touches[1]);
          }
        });
    
        canvas.addEventListener("touchmove", (e) => {
          e.preventDefault();
          if (e.touches.length === 1 && dragging) {
            fotoX = e.touches[0].clientX - startX;
            fotoY = e.touches[0].clientY - startY;
            render();
          } else if (e.touches.length === 2) {
            let newDist = getDistance(e.touches[0], e.touches[1]);
            if (lastDist) {
              let zoomChange = newDist / lastDist;
              scale *= zoomChange;
              render();
            }
            lastDist = newDist;
          }
        });
    
        canvas.addEventListener("touchend", () => {
          dragging = false;
          lastDist = 0;
        });
    
        function getDistance(touch1, touch2) {
          return Math.hypot(
            touch2.clientX - touch1.clientX,
            touch2.clientY - touch1.clientY
          );
        }
    
        // Download hasil twibbon
        $('.downloadHasil').click(function() {
            
            //untuk nambah jumlah download di database
            const id = $(this).data('twibbon');
            $.ajax({
                 "<?= csrf_token() ?>": "<?= csrf_hash() ?>",
                type: 'POST',
                data: {
                    idtwibbon: id
                },
                dataType: 'JSON',
                async: true,
                url: "<?= base_url('App/update_view_pengguna') ?>",
                success: function(data) {
                },
                error: function(err){
                }
            });
            
            //untuk download
            const dataURL = canvas.toDataURL("image/png");
            const link = document.createElement("a");
            link.href = dataURL;
            link.download = "twibbon.png";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
        });
    
        twibbon.onload = () => {
          // Sesuaikan ukuran canvas dengan ukuran asli twibbon
          canvas.width = twibbon.width;
          canvas.height = twibbon.height;
        
          // Kalau sudah ada foto, reset posisinya biar tetap center
          if (foto.src) resetFoto();
        
          render();
        };
  </script>
  <script>
    const btnPilihFoto = document.querySelector(".btnPilihFoto");
    const btnSelanjutnya = document.querySelector(".btnSelanjutnya");
    const btnDownload = document.querySelector(".btnDownload");
    
    
    const pilihanFoto = document.getElementById("pilihFoto");
    const selanjutnya = document.getElementById("selanjutnya");
    const download = document.getElementById("download");
    const tampilHasilTwibbon = document.getElementById("TampilHasilTwibbon");
    const tampilProsesTwibbon = document.getElementById("TampilProsesTwibbon");
    
    btnPilihFoto.addEventListener("click", function() {
      // tampilkan tombol lain
      selanjutnya.classList.remove("d-none");
      pilihanFoto.classList.add("d-none");
    });
    
    btnSelanjutnya.addEventListener("click", function() {
      tampilHasilTwibbon.classList.remove("d-none");
      tampilProsesTwibbon.classList.add("d-none");
      
      const dataURL = canvas.toDataURL("image/png");
      const hasilImg = document.getElementById("hasilTwibbon");
      hasilImg.src = dataURL;
      hasilImg.classList.remove("d-none");
    
      // ✅ panggil autoResize setelah elemen tampil di layar
      setTimeout(() => {
        const textarea = document.getElementById('textContent');
        if (textarea) {
          textarea.style.height = 'auto';
          textarea.style.height = textarea.scrollHeight + 'px';
        }
      }, 100);
    });


    //copy text
    function copyText() {
      const textarea = document.getElementById("textContent");
      const text = textarea.value.trim(); // ambil teks dari textarea
    
      if (!text) {
        alert("Tidak ada teks untuk disalin!");
        return;
      }
    
      navigator.clipboard.writeText(text)
        .then(() => {
          alert("✅ Teks berhasil disalin!");
        })
        .catch(err => {
          console.error("Gagal menyalin teks:", err);
          alert("❌ Gagal menyalin teks. Silakan salin manual.");
        });
    }

  </script>
  <script>
      const textarea = document.getElementById('textContent');
    
      // Fungsi untuk menyesuaikan tinggi textarea
      function autoResize(el) {
        el.style.height = 'auto'; // reset dulu
        el.style.height = el.scrollHeight + 'px'; // sesuaikan dengan isi
      }
    
      // Jalankan setiap kali user mengetik
      textarea.addEventListener('input', function() {
        autoResize(this);
      });
    
      // Panggil sekali waktu halaman pertama kali load (jika ada teks default)
       setTimeout(() => {
        autoResize(textarea);
      }, 100);
  </script>
  
<?= $this->endSection(); ?>

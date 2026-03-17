<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>Twibbon Generator</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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
  </style>
</head>
<body>
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
                            <div class="text-box text-wrap" id="textContent">
                                Hallo, Saya nama x bersama Akuntanmu Learning Center, Siap Mengasah kemampuan dengan Brevet Pajak AB! 💪🏼📈 #Brevet #Pelatihan #Profesional #Kari
                            </div>
                        </div>
                        <!-- Share icons -->
                         <div class="share">
                          <!-- Caption -->
                          <div class="caption">
                            <strong>Saatnya menyebarkan foto kemedia sosial kamu 🎉</strong>
                            <div class="d-flex flex-row">
                                <button class="badge text-bg-info border-0 text-white fw-bold p-2 my-2 btnDownload" onclick="downloadHasil()">Unduh</button>
                                <button class="badge text-bg-warning border-0 text-white fw-bold p-2 m-2  btnDownload" onclick="copyText()">Salin</button>
                                <a href="<?= base_url('twibbon') ?>" class="badge text-bg-info border-0 text-white fw-bold p-2 my-2 d-block w-100 text-center"><i class="bi bi-arrow-clockwise"> Buat Lagi!</i></a>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="row card" id="TampilProsesTwibbon" style="width:300px">
                    <div class="col-12 text-center">
                        <h2 class="text-wrap mt-4 mx-4" style="font-size:16px">Siap Mengikuti Pelatihan Brevet Pajak AB</h2>
                        <input type="file" id="uploadFoto" accept="image/*" style="display:none"><br>
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
                        <div class="d-flex flex-row d-none" id="download">
                            <button class="badge text-bg-info border-0 text-white fw-bold p-2 my-2 btnKembali"><i class="bi bi-arrow-left-circle-fill"></i></button>
                            <button class="badge text-bg-info border-0 text-white fw-bold p-2 my-2 ms-2 d-block w-100 text-center btnDownload" onclick="downloadHasil()">Unduh Foto</button>
                        </div>
                    </div>
                </div>
                  <!-- Tombol Upload Foto -->
            </div>
        </div>
        <!-- Testimonial End -->
  <script>
        const canvas = document.getElementById("canvas");
        const ctx = canvas.getContext("2d");
    
        const twibbon = new Image();
        twibbon.src = "<?= base_url('uploads/twibbon.png') ?>"; // twibbon PNG transparan
    
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
        function downloadHasil() {
          const dataURL = canvas.toDataURL("image/png");
          const link = document.createElement("a");
          link.href = dataURL;
          link.download = "twibbon.png";
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
        }
    
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
    const btnKembali = document.querySelector(".btnKembali");
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
      // tampilkan tombol lain
    //   selanjutnya.classList.add("d-none");
    //   download.classList.remove("d-none");
        tampilHasilTwibbon.classList.remove("d-none");
        tampilProsesTwibbon.classList.add("d-none");
        
        const dataURL = canvas.toDataURL("image/png");
        //   const link = document.createElement("a");
        //   link.href = dataURL;
        //   link.download = "twibbon.png";
        //   document.body.appendChild(link);
        //   link.click();
        //   document.body.removeChild(link);
          
          //untuk menampilkan foto hasil twibbon
          const hasilImg = document.getElementById("hasilTwibbon");
          hasilImg.src = dataURL;
          hasilImg.classList.remove("d-none");
    
    });
    
    btnKembali.addEventListener("click", function() {
      // tampilkan tombol lain
      selanjutnya.classList.remove("d-none");
      download.classList.add("d-none");
    });
    
    //copy text
    function copyText() {
      // ambil teks tanpa format HTML
      const text = document.getElementById("textContent").innerText;
    
      navigator.clipboard.writeText(text).then(() => {
        alert("Teks berhasil disalin!");
      }).catch(err => {
        console.error("Gagal menyalin teks: ", err);
      });
    }
  </script>
</body>
</html>

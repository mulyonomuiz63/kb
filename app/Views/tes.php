<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Bootstrap Tema Dinamis dari URL Gambar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

  <style>
    body {
      min-height: 100vh;
      color: var(--bs-primary-text);
      background: #111;
      position: relative;
      overflow: hidden;
      transition: color 0.5s ease;
    }
    
    .btn-primary{
        background:var(--bs-primary);
    }

    .gradient-bg {
      position: fixed;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: var(--bg-gradient);
      animation: rotateGradient 20s linear infinite;
      filter: blur(80px);
      opacity: 0.7;
      z-index: -1;
      pointer-events: none;
    }

    @keyframes rotateGradient {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .card {
      background-color: rgba(255 255 255 / 0.9);
      border-radius: 1rem;
      padding: 1rem;
      color: #000;
      max-width: 400px;
      margin: 2rem auto;
      position: relative;
      z-index: 1;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
    }

    #preview {
      max-width: 100%;
      max-height: 200px;
      object-fit: contain;
      border: 2px solid var(--bs-primary);
      margin-bottom: 1rem;
      display: block;
    }
  </style>
</head>
<body>

  <div class="gradient-bg"></div>

  <div class="card text-center">
    <h4 class="mb-3">Masukkan URL Gambar</h4>
    <div class="input-group mb-3">
      <input type="text" id="imgUrl" value="https://kelasbrevet.com/uploads/nav-sidebar/top.jpg" class="form-control" placeholder="https://..." />
      <button class="btn btn-primary" id="loadBtn">Muat</button>
    </div>
    <p>Warna Dominan: <span id="colorCode">#0d6efd</span></p>
    <button class="btn btn-primary">Tombol Bootstrap Primary</button>
  </div>

  <script src="<?= base_url('assets-landing/js/color-thief.umd.js') ?>"></script>
  <script>
    const imgUrlInput = document.getElementById('imgUrl');
    const loadBtn = document.getElementById('loadBtn');
    const colorCode = document.getElementById('colorCode');
    const colorThief = new ColorThief();
    const gradientBg = document.querySelector('.gradient-bg');

    // Elemen img untuk proses ekstraksi warna (disembunyikan)
    const hiddenImg = document.createElement('img');
    hiddenImg.crossOrigin = "anonymous";
    hiddenImg.style.display = 'none';
    document.body.appendChild(hiddenImg);

    function rgbToHex(r,g,b) {
      return "#" + [r,g,b].map(x => x.toString(16).padStart(2,'0')).join('');
    }

    function getContrastColor(r,g,b){
      const yiq = (r*299 + g*587 + b*114)/1000;
      return yiq >= 128 ? '#000' : '#fff';
    }

    function applyThemeFromImage(img){
      if(img.complete){
        try{
          const [r,g,b] = colorThief.getColor(img);
          const hex = rgbToHex(r,g,b);
          const textColor = getContrastColor(r,g,b);
          document.documentElement.style.setProperty('--bs-primary', hex);
          document.documentElement.style.setProperty('--bs-primary-text', textColor);
          colorCode.textContent = `${hex} (rgb(${r}, ${g}, ${b}))`;

          const palette = colorThief.getPalette(img, 8)
            .map(c => rgbToHex(c[0], c[1], c[2]));
          const gradient = `conic-gradient(${palette.join(', ')})`;
          document.documentElement.style.setProperty('--bg-gradient', gradient);
          gradientBg.style.background = gradient;
        }catch(e){
        }
      }
    }
    load_gambar();
    function load_gambar() {
      const url = ("<?= base_url('uploads/nav-sidebar/top.jpg') ?>").trim();
      hiddenImg.src = url;
      hiddenImg.onload = () => applyThemeFromImage(hiddenImg);
    };
  </script>

</body>
</html>

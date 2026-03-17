<script>
    const colorCode = document.getElementById('colorCode');
    const colorThief = new ColorThief();
    const gradientBg = document.querySelector('.gradient-bg');

    // Elemen img untuk proses ekstraksi warna (disembunyikan)
    const hiddenImg = document.createElement('img');
    hiddenImg.crossOrigin = "anonymous";
    hiddenImg.style.display = 'none';
    document.body.appendChild(hiddenImg);

    function rgbToHex(r, g, b) {
        return "#" + [r, g, b].map(x => x.toString(16).padStart(2, '0')).join('');
    }

    function getContrastColor(r, g, b) {
        const yiq = (r * 299 + g * 587 + b * 114) / 1000;
        return yiq >= 128 ? '#000' : '#fff';
    }

    function applyThemeFromImage(img) {
        if (img.complete) {
            try {
                const [r, g, b] = colorThief.getColor(img);
                const hex = rgbToHex(r, g, b);
                const textColor = getContrastColor(r, g, b);
                document.documentElement.style.setProperty('--bs-primary', hex);
                document.documentElement.style.setProperty('--bs-primary-text', textColor);
                colorCode.textContent = `${hex} (rgb(${r}, ${g}, ${b}))`;

                const palette = colorThief.getPalette(img, 8)
                    .map(c => rgbToHex(c[0], c[1], c[2]));
                const gradient = `conic-gradient(${palette.join(', ')})`;
                document.documentElement.style.setProperty('--bg-gradient', gradient);
                gradientBg.style.background = gradient;
            } catch (e) {}
        }
    }
    load_gambar();

    function load_gambar() {
        const url = ("<?= $image_nav ?>").trim();
        hiddenImg.src = url;
        hiddenImg.onload = () => applyThemeFromImage(hiddenImg);
    };
</script>
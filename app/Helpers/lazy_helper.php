<?php
function img_lazy(string $src, string $alt = '', array $attrs = []): string
{
    $filePath = FCPATH . ltrim($src, '/');

        $width = $attrs['width'] ?? null;
        $height = $attrs['height'] ?? null;

        // deteksi ukuran asli jika ada di server
        if (file_exists($filePath) && (empty($width) || empty($height))) {
            $size = @getimagesize($filePath);
            if ($size) {
                $width = $width ?? $size[0];
                $height = $height ?? $size[1];
            }
        }

        // fallback jika ukuran tidak ketemu
        $width = $width ?: 200;
        $height = $height ?: 150;

        // placeholder shimmer animasi (SVG)
        $placeholder = 'data:image/svg+xml;utf8,' . rawurlencode('
            <svg xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'" preserveAspectRatio="none">
              <rect width="100%" height="100%" fill="#eeeeee"/>
              <rect width="100%" height="100%" fill="url(#g)">
                <animate attributeName="x" from="-'.$width.'" to="'.$width.'" dur="1.2s" repeatCount="indefinite" />
              </rect>
              <defs>
                <linearGradient id="g">
                    <stop stop-color="#9caaec" offset="20%" />
                    <stop stop-color="#c4dcff" offset="50%" />
                    <stop stop-color="#a7cfdb" offset="70%" />
                </linearGradient>
              </defs>
            </svg>');
    
    $seoText = trim($alt) !== '' ? $alt : 'kelas brevet';
    $title = $attrs['title'] ?? $seoText; // kalau title belum diset, gunakan alt
    
    // 🔥 Ganti source agar lewat converter WebP
    $webpSrc = base_url('webp.php?src=' . urlencode($src));
    
    $default = [
        'src'      => $placeholder, // placeholder
        'data-src' => $webpSrc,
        'alt'      => $seoText,
        'title'    => $title,
        'class'    => 'lazy',
        'loading'  => 'lazy',
        'decoding' => 'async'
    ];

    // jika user kasih class tambahan → gabungkan dengan default
    if (isset($attrs['class'])) {
        $attrs['class'] = $default['class'] . ' ' . $attrs['class'];
        unset($default['class']);
    }

    $allAttrs = array_merge($default, $attrs);

    $attrString = '';
    foreach ($allAttrs as $key => $val) {
        $attrString .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($val) . '"';
    }

    return '<img' . $attrString . '>';
}

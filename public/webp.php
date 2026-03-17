<?php
$file = $_GET['src'] ?? '';
$path = __DIR__ . '/' . ltrim($file, '/');

if (!file_exists($path)) {
    header("HTTP/1.0 404 Not Found");
    exit('File tidak ditemukan.');
}

$info = getimagesize($path);
$mime = $info['mime'];

switch ($mime) {
    case 'image/jpeg':
        $image = imagecreatefromjpeg($path);
        break;
    case 'image/png':
        $image = imagecreatefrompng($path);
        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);
        break;
    default:
        header("Content-Type: $mime");
        readfile($path);
        exit;
}

header('Content-Type: image/webp');
imagewebp($image, null, 85);
imagedestroy($image);

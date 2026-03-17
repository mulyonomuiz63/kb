<?php

use App\Models\GuruKelasModel;
use App\Models\GuruMapelModel;



function check_kelas($id_guru, $id_kelas)
{
    $guruKelasmodel = new GuruKelasModel();

    $guru = decrypt_url($id_guru);

    $result = $guruKelasmodel->getALLByGuruAndKelas($guru, $id_kelas);

    if (count($result) > 0) {
        return "checked='checked'";
    }
}

function check_mapel($id_guru, $id_mapel)
{
    $guruMapelmodel = new GuruMapelModel();

    $guru = decrypt_url($id_guru);

    $result = $guruMapelmodel->getALLByGuruAndMapel($guru, $id_mapel);

    if (count($result) > 0) {
        return "checked='checked'";
    }
}

function ukuran_file($path)
{
    $bytes = sprintf('%u', filesize($path));

    if ($bytes > 0) {
        $unit = intval(log($bytes, 1024));
        $units = array('B', 'KB', 'MB', 'GB');

        if (array_key_exists($unit, $units) === true) {
            return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
        }
    }

    return $bytes;
}


function base64_encode_image (string $path) {
    if ($path) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        return 'data:image/' . $type . ';base64,' . base64_encode($path);
    }
}

function number_hp($hp){
    $nohp    = $hp;
    if($nohp != ''){
        if(!preg_match("/[^+0-9]/",trim($nohp))){
            // cek apakah no hp karakter ke 1 dan 2 adalah angka 62
            if(substr(trim($nohp), 0, 2)=="62"){
               return trim($nohp);
            }
            // cek apakah no hp karakter ke 1 adalah angka 0
            else if(substr(trim($nohp), 0, 1)=="0"){
                return "62".substr(trim($nohp), 1);
            }
        }
    }else{
        return '';
    }
}

if (!function_exists('nominal')) {
    function nominal($str)
    {
        return number_format($str, 0, ',', '.');
    }
}

function slug($text, string $divider = '-')
{
      // Ubah ke huruf kecil
      $text = strtolower($text);
    
      // Hapus karakter non-alphanumeric
      $text = preg_replace('~[^-a-z0-9]+~', $divider, $text);
    
      // Hapus tanda hubung di awal atau akhir
      $text = trim($text, $divider);
    
      return $text;
}



function formatRupiahSingkat($angka)
{
    if ($angka >= 1000000) {
        // Di atas 1 juta → JT
        return number_format($angka / 1000000, 1, ',', '') . 'JT';
    } elseif ($angka >= 1000) {
        // Di atas 1 ribu → RB
        return number_format($angka / 1000, 0, ',', '') . 'RB';
    } else {
        // Di bawah 1 ribu → tampilkan utuh
        return number_format($angka, 0, ',', '.');
    }
}

function showStars($rating)
{
    $output = '';
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
    $emptyStars = 5 - $fullStars - $halfStar;

    for ($i = 1; $i <= $fullStars; $i++) {
        $output .= '<i class="fas fa-star text-warning"></i>';
    }
    if ($halfStar) {
        $output .= '<i class="fas fa-star-half-alt text-warning"></i>';
    }
    for ($i = 1; $i <= $emptyStars; $i++) {
        $output .= '<i class="far fa-star text-warning"></i>';
    }

    return $output;
}





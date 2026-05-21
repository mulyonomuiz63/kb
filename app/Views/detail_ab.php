<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title><?= title(); ?></title>
    <link rel="icon" type="image/x-icon" href="<?= base_url(favicon()); ?>" />
    
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="<?= base_url('assets/app-assets/template/cbt-malela/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/app-assets/template/cbt-malela/plugins/sweetalerts/sweetalert2.min.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Quicksand', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        
        .result-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            display: flex;
            flex-wrap: wrap;
        }

        .result-info {
            flex: 1 1 500px;
            padding: 50px;
        }

        .result-visual {
            flex: 1 1 300px;
            background: linear-gradient(135deg, #1C3FAA 0%, #00d2ff 100%);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 50px;
            text-align: center;
        }

        .result-visual i {
            font-size: 100px;
            margin-bottom: 20px;
            text-shadow: 2px 4px 10px rgba(0,0,0,0.2);
        }

        .table-custom {
            width: 100%;
            margin-top: 20px;
        }

        .table-custom td {
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 15px;
        }

        .table-custom td:first-child {
            font-weight: 600;
            color: #555;
            width: 40%;
        }

        .table-custom td:last-child {
            color: #333;
            font-weight: 500;
        }

        .badge-status {
            padding: 8px 15px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 14px;
        }

        .brand-logo {
            max-width: 200px;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>

<?php
// Optimasi PHP Logic
$nilai = 0;
$total = 0;
$nama = '';
$no_induk_siswa = '';
$id_ujian = '';
$tgl_sertifikat_end = $data->end_ujian ?? '-';

foreach($hasil as $rows){
    $nilai += $rows->nilai;
    $total++;
    $nama = $rows->nama_siswa;
    $no_induk_siswa = $rows->no_induk_siswa;
    $id_ujian = $rows->id_ujian;
}

$totalNilai = $total > 0 ? round($nilai / $total) : 0;
$isLulus = $totalNilai >= 60;
?>

    <div class="result-card">
        <div class="result-info">
            <a href="<?= base_url('/'); ?>">
                <img src="<?= base_url('assets-landing/images/logo.png') ?>" alt="Logo" class="brand-logo" />
            </a>
            
            <h4 class="fw-bold mb-4 text-dark">Detail Hasil Ujian Brevet Pajak A&B</h4>
            
            <table class="table-custom">
                <tr>
                    <td>Nama Siswa</td>
                    <td><?= esc($nama) ?></td>
                </tr>
                <tr>
                    <td>Keterangan</td>
                    <td>Menyelesaikan ujian dengan nilai rata-rata <?= $totalNilai ?></td>
                </tr>
                <tr>
                    <td>Total Materi</td>
                    <td><?= $total ?> Materi</td>
                </tr>
                <tr>
                    <td>Tanggal Selesai</td>
                    <td><?= $tgl_sertifikat_end ?></td>
                </tr>
                <tr>
                    <td>Nilai Akhir</td>
                    <td><span class="text-primary fw-bold fs-5"><?= $totalNilai ?></span></td>
                </tr>
                <tr>
                    <td>Status Kelulusan</td>
                    <td>
                        <?php if ($totalNilai == '' || $total == 0): ?>
                            <span class="badge badge-secondary badge-status">Belum Ada Data</span>
                        <?php elseif ($isLulus): ?>
                            <span class="badge badge-success badge-status"><i class="fas fa-check-circle me-1"></i> Lulus</span>
                        <?php else: ?>
                            <span class="badge badge-danger badge-status"><i class="fas fa-times-circle me-1"></i> Tidak Lulus</span>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>

            <div class="mt-5 text-muted" style="font-size: 13px;">
                © 2021 - <?= date('Y') ?> | All Rights Reserved. <a href="<?= base_url('/') ?>" class="text-primary text-decoration-none fw-bold">KelasBrevet</a>
            </div>
        </div>

        <div class="result-visual">
            <?php if ($isLulus && $total > 0): ?>
                <i class="fas fa-award text-warning"></i>
                <h3 class="fw-bold">Selamat!</h3>
                <p>Anda telah berhasil lulus dalam ujian sertifikasi ini.</p>
            <?php elseif (!$isLulus && $total > 0): ?>
                <i class="fas fa-file-excel"></i>
                <h3 class="fw-bold">Tetap Semangat!</h3>
                <p>Silakan pelajari materi kembali dan coba lagi di kesempatan berikutnya.</p>
            <?php else: ?>
                <i class="fas fa-file-signature"></i>
                <h3 class="fw-bold">Rekap Ujian</h3>
                <p>Informasi hasil pengerjaan ujian Anda akan tampil di sini.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="<?= base_url('assets/app-assets/template/cbt-malela/assets/js/libs/jquery-3.1.1.min.js'); ?>"></script>
    <script src="<?= base_url('assets/app-assets/template/cbt-malela/plugins/sweetalerts/sweetalert2.min.js'); ?>"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    
    <script type="text/javascript">
        <?= session()->getFlashdata('pesan'); ?>
        
        $(document).ready(function() {
            $("#data-kelas").autocomplete({
                appendTo: "#suggestion-box",
                source: function(request, response) {
                    $.ajax({
                        type: "POST",
                        url: "<?= site_url('Register/autocomplate'); ?>",
                        dataType: "json",
                        data: { term: request.term },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                select: function(event, ui) {
                    $('#id_kelas').val(ui.item.id_kelas);
                    $('#data-kelas').val(ui.item.nama_kelas);
                    return false;
                }
            }).autocomplete("instance")._renderItem = function(ul, item) {
                return $("<li type='none'>")
                    .append("<div id='lis-kelas'><b>" + item.nama_kelas + "</div>")
                    .appendTo(ul);
            };
        });
    </script>
</body>
</html>
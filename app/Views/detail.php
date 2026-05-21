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
        
        /* Modern Card Layout */
        .result-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            width: 100%;
            max-width: 950px;
            display: flex;
            flex-wrap: wrap;
        }

        .result-info {
            flex: 1 1 500px;
            padding: 50px;
            background: #ffffff;
        }

        .result-visual {
            flex: 1 1 350px;
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
            font-size: 110px;
            margin-bottom: 25px;
            text-shadow: 2px 4px 15px rgba(0,0,0,0.2);
        }

        .table-custom {
            width: 100%;
            margin-top: 25px;
        }

        .table-custom td {
            padding: 14px 0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 15px;
            vertical-align: middle;
        }

        .table-custom tr:last-child td {
            border-bottom: none;
        }

        .table-custom td:first-child {
            font-weight: 600;
            color: #6c757d;
            width: 35%;
        }

        .table-custom td:last-child {
            color: #2b2b2b;
            font-weight: 600;
        }

        .badge-status {
            padding: 8px 18px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        .brand-logo {
            max-width: 220px;
            margin-bottom: 35px;
        }
    </style>
</head>

<body>

<?php
// Evaluasi Status Kelulusan
$hasNilai = ($ujian->nilai !== '');
$isLulus  = ($hasNilai && $ujian->nilai >= 60);
?>

    <div class="result-card">
        <div class="result-info">
            <a href="<?= base_url('/'); ?>">
                <img src="<?= base_url('assets-landing/images/logo.png') ?>" alt="KelasBrevet" class="brand-logo" />
            </a>
            
            <h4 class="fw-bold text-dark mb-1">Detail Hasil Ujian</h4>
            <p class="text-muted mb-4">Rincian nilai dan status pengerjaan ujian Anda.</p>
            
            <table class="table-custom">
                <tr>
                    <td>Nama Peserta</td>
                    <td><?= esc($ujian->nama_siswa) ?></td>
                </tr>
                <tr>
                    <td>Nama Ujian</td>
                    <td><?= esc($ujian->nama_ujian) ?></td>
                </tr>
                <tr>
                    <td>Kelas</td>
                    <td><?= esc($ujian->nama_kelas) ?></td>
                </tr>
                <tr>
                    <td>Materi</td>
                    <td><?= esc($ujian->nama_mapel) ?></td>
                </tr>
                <tr>
                    <td>Waktu Pengerjaan</td>
                    <td><?= esc($ujian->start_ujian) ?></td>
                </tr>
                <tr>
                    <td>Nilai Akhir</td>
                    <td>
                        <?php if($hasNilai): ?>
                            <span class="text-primary fw-bold fs-4"><?= esc($ujian->nilai) ?></span>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>Status Ujian</td>
                    <td>
                        <?php if (!$hasNilai): ?>
                            <span class="badge badge-secondary badge-status">Belum Dinilai</span>
                        <?php elseif ($isLulus): ?>
                            <span class="badge badge-success badge-status bg-success text-white"><i class="fas fa-check-circle me-1"></i> Lulus</span>
                        <?php else: ?>
                            <span class="badge badge-danger badge-status bg-danger text-white"><i class="fas fa-times-circle me-1"></i> Tidak Lulus</span>
                        <?php endif; ?>
                        
                        </td>
                </tr>
            </table>

            <div class="mt-5 text-muted" style="font-size: 13px;">
                © 2021 - <?= date('Y') ?> | All Rights Reserved. <a href="<?= base_url('/') ?>" class="text-primary text-decoration-none fw-bold">KelasBrevet</a>
            </div>
        </div>

        <div class="result-visual">
            <?php if (!$hasNilai): ?>
                <i class="fas fa-hourglass-half"></i>
                <h3 class="fw-bold">Menunggu Hasil</h3>
                <p>Ujian Anda sedang diproses atau belum memiliki nilai akhir.</p>
            <?php elseif ($isLulus): ?>
                <i class="fas fa-graduation-cap text-warning"></i>
                <h3 class="fw-bold">Luar Biasa!</h3>
                <p>Anda telah mencapai standar kelulusan untuk materi ini. Pertahankan prestasimu!</p>
            <?php else: ?>
                <i class="fas fa-book-reader"></i>
                <h3 class="fw-bold">Jangan Menyerah!</h3>
                <p>Nilai Anda belum memenuhi standar kelulusan. Pelajari kembali materi dan coba lagi.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="<?= base_url('assets/app-assets/template/cbt-malela/assets/js/libs/jquery-3.1.1.min.js'); ?>"></script>
    <script src="<?= base_url('assets/app-assets/template/cbt-malela/plugins/sweetalerts/sweetalert2.min.js'); ?>"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    
    <script type="text/javascript">
        <?= session()->getFlashdata('pesan'); ?>
        
        // Autocomplete Script (Dipertahankan sesuai fungsi aslinya)
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
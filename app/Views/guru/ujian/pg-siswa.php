<?= $this->extend('template/app'); ?>
<?= $this->section('styles'); ?>
<style>
    .answer-box {
        border: 2px solid #e0e6ed;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .answer-box:hover {
        background-color: #f1f2f3;
        border-color: #4361ee;
    }

    .answer-box.selected {
        border-color: #4361ee;
        background-color: #f3f6ff;
    }

    .nav-btn {
        width: 40px;
        height: 40px;
        margin: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        border: 1px solid #bfc9d4;
    }

    .nav-btn.active {
        background-color: #4361ee !important;
        color: #fff !important;
        border-color: #4361ee;
    }

    .nav-btn.benar {
        background-color: #1abc9c;
        color: #fff;
        border-color: #1abc9c;
    }

    .nav-btn.salah {
        background-color: #e7515a;
        color: #fff;
        border-color: #e7515a;
    }

    .nav-btn.kosong {
        background-color: #e2a03f;
        color: #fff;
        border-color: #e2a03f;
    }

    .question-item {
        display: none;
    }

    .question-item.active {
        display: block;
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('content'); ?>
<?php

use App\Models\UjianSiswaModel;

$UjianSiswaModel = new UjianSiswaModel();
?>
<div class="layout-px-spacing">

    <div class="row layout-top-spacing mb-2">
        <div class="col-12">
            <div class="widget shadow-sm p-3 bg-white d-flex justify-content-between align-items-center" style="border-radius:8px;">
                <div>
                    <h5 class="mb-0 font-weight-bold"><?= $ujian->nama_ujian; ?></h5>
                    <small class="text-muted">Peserta: <?= $siswa->nama_siswa; ?> | Total: <?= count($detail_ujian); ?> Soal</small>
                </div>
                <div>
                    <span class="badge badge-success">Benar: <?= count($jawaban_benar); ?></span>
                    <span class="badge badge-danger">Salah: <?= count($jawaban_salah); ?></span>
                    <span class="badge badge-warning">Kosong: <?= count($tidak_dijawab); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <strong>Soal No. <span id="currentNumberLabel">1</span></strong>
                </div>

                <div class="card-body">
                    <form id="examForm">
                        <?php $no = 1;
                        foreach ($detail_ujian as $soal):
                            $jawaban_siswa = $UjianSiswaModel
                                ->where('ujian_id', $soal->id_detail_ujian)
                                ->where('siswa', $siswa->id_siswa)
                                ->get()->getRowObject();

                            $is_benar = ($jawaban_siswa && $jawaban_siswa->jawaban == $soal->jawaban);
                            $is_salah = ($jawaban_siswa && $jawaban_siswa->jawaban != NULL && $jawaban_siswa->jawaban != $soal->jawaban);
                            $is_kosong = (!$jawaban_siswa || $jawaban_siswa->jawaban == NULL);
                        ?>

                            <div class="question-item <?= $no == 1 ? 'active' : '' ?>" id="question-<?= $no ?>" data-status="<?= $is_benar ? 'benar' : ($is_salah ? 'salah' : 'kosong') ?>">

                                <div class="mb-4 font-weight-bold" style="font-size: 1.1rem;">
                                    <?= strip_tags($soal->nama_soal, '<b><i><u><strong><em><img><a><ul><li>') ?>
                                </div>

                                <div class="options-container">
                                    <?php
                                    $pilihan = [
                                        'A' => $soal->pg_1,
                                        'B' => $soal->pg_2,
                                        'C' => $soal->pg_3,
                                        'D' => $soal->pg_4,
                                        'E' => $soal->pg_5
                                    ];

                                    foreach ($pilihan as $key => $val):
                                        if (empty($val) || strlen($val) <= 3) continue;
                                        $isi_pg = substr($val, 3);

                                        // Logika Warna Indikator
                                        $class_label = "";
                                        if ($jawaban_siswa && $jawaban_siswa->jawaban == $key) {
                                            $class_label = ($key == $soal->jawaban) ? "bg-success text-white" : "bg-danger text-white";
                                        } elseif ($key == $soal->jawaban) {
                                            $class_label = "border-success text-success font-weight-bold"; // Tandai jawaban yang benar jika siswa salah
                                        }
                                    ?>
                                        <div class="answer-box d-flex align-items-center <?= $class_label ?>">
                                            <div class="mr-3 font-weight-bold"><?= $key ?>.</div>
                                            <div class="answer-content"><?= $isi_pg ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <hr>
                                <hr>
                                <div class="alert <?= $is_benar ? 'alert-light-success' : ($is_kosong ? 'alert-light-warning' : 'alert-light-danger') ?> mt-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="font-weight-bold mb-0">Analisis Jawaban:</h6>
                                        <span class="badge badge-dark">
                                            <i class="far fa-clock mr-1"></i> Dijawab pada: <?= $jawaban_siswa->jam ?? '-'; ?>
                                        </span>
                                    </div>

                                    <p class="mb-1">Jawaban Peserta: <strong><?= $jawaban_siswa->jawaban ?? 'Tidak Dijawab' ?></strong>
                                        <?php if (!$is_benar && !$is_kosong): ?>
                                            | Jawaban Benar: <strong class="text-success"><?= $soal->jawaban ?></strong>
                                        <?php endif; ?>
                                    </p>

                                    <div class="mt-2 pt-2 border-top">
                                        <strong>Penjelasan:</strong><br>
                                        <div class="text-dark">
                                            <?= $soal->penjelasan ?: 'Tidak ada penjelasan untuk soal ini.' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php $no++;
                        endforeach; ?>
                    </form>
                </div>
            </div>

            <div class="d-flex justify-content-between mb-4">
                <button class="btn btn-secondary" id="prevBtn" disabled>Sebelumnya</button>
                <a href="javascript:void(0)" class="btn btn-outline-primary" onclick="history.back(-1)">Kembali</a>
                <button class="btn btn-primary" id="nextBtn">Selanjutnya</button>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-info text-white text-center font-weight-bold">
                    Navigasi Soal
                </div>
                <div class="card-body p-3">
                    <div id="navContainer" class="d-flex flex-wrap justify-content-start">
                        <?php $n = 1;
                        foreach ($detail_ujian as $s):
                            // Ambil data jam untuk tooltip
                            $js = $UjianSiswaModel->where(['ujian_id' => $s->id_detail_ujian, 'siswa' => $siswa->id_siswa])->get()->getRowObject();
                            $jam_jawab = $js->jam ?? '-';
                        ?>
                            <div class="nav-btn"
                                id="nav-btn-<?= $n ?>"
                                onclick="goToQuestion(<?= $n ?>)"
                                data-toggle="tooltip"
                                title="Waktu: <?= $jam_jawab ?>">
                                <?= $n ?>
                            </div>
                        <?php $n++;
                        endforeach; ?>
                    </div>

                    <div class="mt-4 pt-2 border-top">
                        <small class="d-block mb-1"><span class="badge badge-success">&nbsp;</span> Benar</small>
                        <small class="d-block mb-1"><span class="badge badge-danger">&nbsp;</span> Salah</small>
                        <small class="d-block mb-1"><span class="badge badge-warning">&nbsp;</span> Kosong / Tidak Dijawab</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script>
    let currentQ = 1;
    const totalQ = <?= count($detail_ujian); ?>;

    function updateUI() {
        // Update Soal Visibility
        $('.question-item').removeClass('active');
        $('#question-' + currentQ).addClass('active');

        // Update Label Nomor
        $('#currentNumberLabel').text(currentQ);

        // Update Tombol
        $('#prevBtn').prop('disabled', currentQ === 1);
        $('#nextBtn').text(currentQ === totalQ ? 'Selesai' : 'Selanjutnya');

        // Update Active Nav
        $('.nav-btn').removeClass('active');
        $('#nav-btn-' + currentQ).addClass('active');
    }

    function goToQuestion(num) {
        currentQ = num;
        updateUI();
    }

    // Beri warna pada navigasi berdasarkan status jawaban (PHP rendered)
    function colorNav() {
        $('.question-item').each(function() {
            const id = $(this).attr('id').split('-')[1];
            const status = $(this).data('status');
            $('#nav-btn-' + id).addClass(status);
        });
    }

    $(document).ready(function() {
        colorNav();
        updateUI();

        $('#nextBtn').click(function() {
            if (currentQ < totalQ) {
                currentQ++;
                updateUI();
            } else {
                history.back(-1);
            }
        });

        $('#prevBtn').click(function() {
            if (currentQ > 1) {
                currentQ--;
                updateUI();
            }
        });
    });
</script>

<?= $this->endSection(); ?>
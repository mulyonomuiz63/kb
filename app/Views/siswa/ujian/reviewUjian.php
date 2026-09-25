<?= $this->extend('template/app'); ?>

<?= $this->section('styles'); ?>
<style>
    /* Styling Dasar Layout - Metronic Style */
    .answer-box {
        border: 1px solid #E1E3EA;
        border-radius: 0.625rem;
        padding: 1rem;
        margin-bottom: 0.75rem;
        transition: all 0.2s ease;
    }

    .answer-content {
        flex: 1;
        font-weight: 500;
        color: #3F4254;
    }

    /* Indikator Navigasi Nomor */
    .nav-btn {
        width: 38px; height: 38px; margin: 3px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 0.475rem; font-weight: 600; cursor: pointer;
        border: 1px solid #E1E3EA; background-color: #ffffff;
        color: #3F4254; transition: all 0.2s;
    }

    .nav-btn.active {
        background-color: #009ef7 !important;
        color: #ffffff !important;
        border-color: #009ef7;
    }

    /* Warna Status Hasil */
    .nav-btn.benar { background-color: #50cd89 !important; color: #fff !important; border-color: #50cd89; }
    .nav-btn.salah { background-color: #f1416c !important; color: #fff !important; border-color: #f1416c; }
    .nav-btn.kosong { background-color: #ffc700 !important; color: #fff !important; border-color: #ffc700; }

    .question-item { display: none; }
    .question-item.active { display: block; }
    
    .answer-box.bg-success { background-color: #E8FFF3 !important; border-color: #50cd89 !important; color: #181C32; }
    .answer-box.bg-danger { background-color: #FFF5F8 !important; border-color: #f1416c !important; color: #181C32; }
    .answer-box.border-success { border: 2px dashed #50cd89 !important; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content'); ?>
<?php use App\Models\UjianSiswaModel; $UjianSiswaModel = new UjianSiswaModel(); ?>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        
        <div class="card card-flush shadow-sm mb-6">
            <div class="card-body py-5 d-flex flex-column flex-md-row align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h3 class="fw-bolder text-dark mb-1"><?= $ujian->nama_ujian; ?></h3>
                    <div class="text-muted fw-bold fs-7">
                        <span class="me-3"><i class="ki-duotone ki-user fs-6 me-1"><span class="path1"></span><span class="path2"></span></i> <?= $siswa->nama_siswa; ?></span>
                        <span><i class="ki-duotone ki-问号 fs-6 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> Total: <?= count($detail_ujian); ?> Soal</span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge badge-light-success border border-success px-4 py-3">Benar: <?= count($jawaban_benar); ?></span>
                    <span class="badge badge-light-danger border border-danger px-4 py-3">Salah: <?= count($jawaban_salah); ?></span>
                    <span class="badge badge-light-warning border border-warning px-4 py-3">Kosong: <?= count($tidak_dijawab); ?></span>
                </div>
            </div>
        </div>

        <div class="row g-6">
            <div class="col-xl-9">
                <div class="card card-flush shadow-sm h-lg-100">
                    <div class="card-header border-0 pt-6">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder text-dark">Soal No. <span id="currentNumberLabel">1</span></span>
                        </h3>
                    </div>

                    <div class="card-body">
                        <form id="examForm">
                            <?php $no = 1; foreach ($detail_ujian as $soal): 
                                $jawaban_siswa = $UjianSiswaModel->where(['ujian_id' => $soal->id_detail_ujian, 'siswa' => $siswa->id_siswa])->get()->getRowObject();
                                $is_benar = ($jawaban_siswa && $jawaban_siswa->jawaban == $soal->jawaban);
                                $is_salah = ($jawaban_siswa && $jawaban_siswa->jawaban != NULL && $jawaban_siswa->jawaban != $soal->jawaban);
                                $is_kosong = (!$jawaban_siswa || $jawaban_siswa->jawaban == NULL);
                            ?>

                            <div class="question-item <?= $no == 1 ? 'active' : '' ?>" id="question-<?= $no ?>" data-status="<?= $is_benar ? 'benar' : ($is_salah ? 'salah' : 'kosong') ?>">
                                
                                <div class="fs-4 fw-bold text-gray-800 mb-8">
                                    <?= strip_tags($soal->nama_soal, '<b><i><u><strong><em><img><a><ul><li>') ?>
                                </div>

                                <div class="options-container mb-8">
                                    <?php
                                    $pilihan = ['A' => $soal->pg_1, 'B' => $soal->pg_2, 'C' => $soal->pg_3, 'D' => $soal->pg_4, 'E' => $soal->pg_5];
                                    foreach ($pilihan as $key => $val):
                                        if (empty($val) || strlen($val) <= 3) continue;
                                        $isi_pg = substr($val, 3);
                                        $class_label = "";
                                        if ($jawaban_siswa && $jawaban_siswa->jawaban == $key) {
                                            $class_label = ($key == $soal->jawaban) ? "bg-success" : "bg-danger";
                                        } elseif ($key == $soal->jawaban) {
                                            $class_label = "border-success";
                                        }
                                    ?>
                                        <div class="answer-box d-flex align-items-center <?= $class_label ?>">
                                            <div class="fw-bolder me-4 fs-5"><?= $key ?>.</div>
                                            <div class="answer-content"><?= $isi_pg ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="notice d-flex <?= $is_benar ? 'bg-light-success border-success' : ($is_kosong ? 'bg-light-warning border-warning' : 'bg-light-danger border-danger') ?> rounded border border-dashed p-6">
                                    <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                                        <div class="mb-3 mb-md-0 fw-semibold">
                                            <div class="d-flex align-items-center mb-2">
                                                <h4 class="text-gray-900 fw-bold mb-0 me-3">Analisis Jawaban</h4>
                                                <span class="badge badge-dark">
                                                    <i class="ki-duotone ki-time fs-7 me-1 text-white"><span class="path1"></span><span class="path2"></span></i>
                                                    Dijawab: <?= $jawaban_siswa->jam ?? '-'; ?>
                                                </span>
                                            </div>
                                            <div class="fs-6 text-gray-700 pe-7">
                                                <p class="mb-2">Status: 
                                                    <span class="fw-bolder <?= $is_benar ? 'text-success' : 'text-danger' ?>">
                                                        <?= $is_benar ? 'BENAR' : 'SALAH / KOSONG' ?>
                                                    </span> | 
                                                    Jawaban Anda: <strong><?= $jawaban_siswa->jawaban ?? 'Tidak Dijawab' ?></strong>
                                                    <?php if (!$is_benar): ?>
                                                        | Jawaban Benar: <span class="badge badge-success"><?= $soal->jawaban ?></span>
                                                    <?php endif; ?>
                                                </p>
                                                <div class="mt-4 p-4 bg-white bg-opacity-50 rounded border">
                                                    <strong class="text-gray-900">Penjelasan:</strong>
                                                    <div class="mt-1 text-dark"><?= $soal->penjelasan ?: 'Tidak ada penjelasan.' ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php $no++; endforeach; ?>
                        </form>
                    </div>

                    <div class="card-footer border-0 d-flex justify-content-between py-6">
                        <button class="btn btn-light" id="prevBtn" disabled>
                            <i class="ki-duotone ki-black-left fs-4 me-1"></i> Sebelumnya
                        </button>
                        <a href="javascript:void(0)" class="btn btn-light-primary" onclick="history.back(-1)">Kembali</a>
                        <button class="btn btn-primary" id="nextBtn">
                            Selanjutnya <i class="ki-duotone ki-black-right fs-4 ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-xl-3">
                <div class="card card-flush shadow-sm sticky-lg-top" style="top: 100px;">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title fw-bolder text-dark">Navigasi Soal</h3>
                    </div>
                    <div class="card-body">
                        <div id="navContainer" class="d-flex flex-wrap gap-1 justify-content-start">
                            <?php $n = 1; foreach ($detail_ujian as $s): 
                                $js = $UjianSiswaModel->where(['ujian_id' => $s->id_detail_ujian, 'siswa' => $siswa->id_siswa])->get()->getRowObject();
                            ?>
                                <div class="nav-btn" id="nav-btn-<?= $n ?>" onclick="goToQuestion(<?= $n ?>)" 
                                     data-bs-toggle="tooltip" data-bs-placement="top" title="Pukul: <?= $js->jam ?? '-'; ?>">
                                    <?= $n ?>
                                </div>
                            <?php $n++; endforeach; ?>
                        </div>

                        <div class="separator separator-dashed my-6"></div>

                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center">
                                <span class="w-20px h-20px rounded bg-success me-3"></span>
                                <span class="text-gray-700 fw-bold fs-7">Jawaban Benar</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="w-20px h-20px rounded bg-danger me-3"></span>
                                <span class="text-gray-700 fw-bold fs-7">Jawaban Salah</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="w-20px h-20px rounded bg-warning me-3"></span>
                                <span class="text-gray-700 fw-bold fs-7">Tidak Dijawab</span>
                            </div>
                        </div>
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
        $('.question-item').removeClass('active');
        $('#question-' + currentQ).addClass('active');
        $('#currentNumberLabel').text(currentQ);

        $('#prevBtn').prop('disabled', currentQ === 1);
        if (currentQ === totalQ) {
            $('#nextBtn').html('Selesai <i class="ki-duotone ki-check fs-4 ms-1"></i>').addClass('btn-success');
        } else {
            $('#nextBtn').html('Selanjutnya <i class="ki-duotone ki-black-right fs-4 ms-1"></i>').removeClass('btn-success');
        }

        $('.nav-btn').removeClass('active');
        $('#nav-btn-' + currentQ).addClass('active');
        
        // Scroll top on change
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function goToQuestion(num) {
        currentQ = num;
        updateUI();
    }

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
<?= $this->extend('template/app'); ?>

<?= $this->section('styles'); ?>
<style>
    /* Penyesuaian Custom CSS ke Metronic 8 Variables */
    .answer-box {
        border: 1px dashed var(--bs-gray-400);
        border-radius: 0.75rem;
        padding: 1rem 1.25rem;
        margin-bottom: 0.75rem;
        cursor: pointer;
        transition: all 0.3s;
        background-color: #fff;
    }

    .answer-box:hover {
        background-color: var(--bs-gray-100);
        border-color: var(--bs-primary);
    }

    .answer-box.selected {
        border-color: var(--bs-primary);
        background-color: var(--bs-primary-light);
    }

    .nav-btn {
        width: 42px;
        height: 42px;
        margin: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        border: 1px solid var(--bs-gray-300);
        background-color: var(--bs-gray-100);
        color: var(--bs-gray-700);
        transition: all 0.2s;
    }

    /* Status Warna Navigasi Soal */
    .nav-btn.benar {
        background-color: var(--bs-success);
        color: #fff;
        border-color: var(--bs-success);
    }

    .nav-btn.salah {
        background-color: var(--bs-danger);
        color: #fff;
        border-color: var(--bs-danger);
    }

    .nav-btn.kosong {
        background-color: var(--bs-warning);
        color: #fff;
        border-color: var(--bs-warning);
    }

    /* Highlight Active state dengan Box Shadow & Transform */
    .nav-btn.active {
        box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), 0.4) !important;
        transform: translateY(-2px);
    }

    .question-item {
        display: none;
    }

    .question-item.active {
        display: block;
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content'); ?>
<?php
use App\Models\UjianSiswaModel;
$UjianSiswaModel = new UjianSiswaModel();
?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card shadow-sm border-0 mb-7">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center p-5 gap-4">
                    <div>
                        <h3 class="fw-bold text-gray-900 mb-1"><?= $ujian->nama_ujian; ?></h3>
                        <div class="text-muted fw-semibold fs-6">
                            <i class="ki-duotone ki-user fs-4 me-1"><span class="path1"></span><span class="path2"></span></i> Peserta: <span class="text-gray-800 fw-bold me-3"><?= $siswa->nama_siswa; ?></span>
                            <i class="ki-duotone ki-document fs-4 me-1"><span class="path1"></span><span class="path2"></span></i> Total: <span class="text-gray-800 fw-bold"><?= count($detail_ujian); ?> Soal</span>
                        </div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge badge-light-success px-4 py-2 fs-6 fw-bold">Benar: <?= count($jawaban_benar); ?></span>
                        <span class="badge badge-light-danger px-4 py-2 fs-6 fw-bold">Salah: <?= count($jawaban_salah); ?></span>
                        <span class="badge badge-light-warning px-4 py-2 fs-6 fw-bold text-dark">Kosong: <?= count($tidak_dijawab); ?></span>
                    </div>
                </div>
            </div>

            <div class="row g-7">
                <div class="col-lg-8 col-xl-9">
                    <div class="card shadow-sm border-0 mb-5">
                        
                        <div class="card-header bg-primary min-h-50px px-6">
                            <h3 class="card-title text-white fw-bold m-0">
                                Soal No. <span id="currentNumberLabel" class="ms-1">1</span>
                            </h3>
                        </div>

                        <div class="card-body p-6 p-lg-10">
                            <form id="examForm">
                                <?php 
                                $no = 1;
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

                                        <div class="mb-8 text-gray-800 fw-semibold fs-4">
                                            <?= strip_tags($soal->nama_soal, '<b><i><u><strong><em><img><a><ul><li>') ?>
                                        </div>

                                        <div class="options-container d-flex flex-column gap-3">
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

                                                // Logika Warna Indikator menggunakan class Metronic
                                                $class_label = "";
                                                if ($jawaban_siswa && $jawaban_siswa->jawaban == $key) {
                                                    $class_label = ($key == $soal->jawaban) ? "bg-success text-white border-success" : "bg-danger text-white border-danger";
                                                } elseif ($key == $soal->jawaban) {
                                                    $class_label = "bg-light-success border-success border-2 text-success fw-bold"; // Highlight jawaban benar jika siswa salah
                                                }
                                            ?>
                                                <div class="answer-box d-flex align-items-center <?= $class_label ?>">
                                                    <div class="me-4 fw-bold fs-5"><?= $key ?>.</div>
                                                    <div class="answer-content flex-grow-1 fs-6"><?= $isi_pg ?></div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>

                                        <?php 
                                            $alert_class = $is_benar ? 'bg-light-success border-success text-success' : ($is_kosong ? 'bg-light-warning border-warning text-warning' : 'bg-light-danger border-danger text-danger'); 
                                        ?>
                                        <div class="notice d-flex flex-column rounded border border-dashed <?= $alert_class ?> p-6 mt-10">
                                            <div class="d-flex justify-content-between align-items-center mb-4">
                                                <h6 class="fw-bold mb-0 text-gray-900 fs-5">Analisis Jawaban:</h6>
                                                <span class="badge badge-secondary fw-bold px-3 py-2">
                                                    <i class="ki-duotone ki-time fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                                                    Dijawab: <?= $jawaban_siswa->jam ?? '-'; ?>
                                                </span>
                                            </div>

                                            <div class="d-flex flex-wrap gap-4 mb-4 fs-6 text-gray-800">
                                                <div class="border border-gray-300 rounded px-4 py-2 bg-white">
                                                    Jawaban Peserta: <strong class="<?= $is_benar ? 'text-success' : ($is_kosong ? 'text-warning' : 'text-danger') ?> fs-5"><?= $jawaban_siswa->jawaban ?? 'Tidak Dijawab' ?></strong>
                                                </div>
                                                <?php if (!$is_benar && !$is_kosong): ?>
                                                    <div class="border border-success rounded px-4 py-2 bg-white">
                                                        Kunci Jawaban: <strong class="text-success fs-5"><?= $soal->jawaban ?></strong>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="mt-4 pt-4 border-top border-gray-300">
                                                <span class="fw-bold text-gray-900 d-block mb-2">Penjelasan:</span>
                                                <div class="text-gray-700 fs-6">
                                                    <?= $soal->penjelasan ?: '<span class="text-muted fst-italic">Tidak ada penjelasan untuk soal ini.</span>' ?>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                <?php 
                                $no++;
                                endforeach; 
                                ?>
                            </form>
                        </div>
                        
                        <div class="card-footer d-flex justify-content-between align-items-center py-5">
                            <button type="button" class="btn btn-light-primary fw-bold" id="prevBtn" disabled>
                                <i class="ki-duotone ki-arrow-left fs-2"></i> Sebelumnya
                            </button>
                            <button type="button" class="btn btn-primary fw-bold" id="nextBtn">
                                Selanjutnya <i class="ki-duotone ki-arrow-right fs-2"></i>
                            </button>
                        </div>
                        
                    </div>
                </div>

                <div class="col-lg-4 col-xl-3">
                    <div class="card shadow-sm border-0 sticky-top" style="top: 100px; z-index: 1;">
                        <div class="card-header bg-light-info min-h-50px px-6">
                            <h3 class="card-title text-info fw-bold m-0 w-100 justify-content-center">
                                Navigasi Soal
                            </h3>
                        </div>
                        <div class="card-body p-6">
                            <div id="navContainer" class="d-flex flex-wrap justify-content-center">
                                <?php 
                                $n = 1;
                                foreach ($detail_ujian as $s):
                                    $js = $UjianSiswaModel->where(['ujian_id' => $s->id_detail_ujian, 'siswa' => $siswa->id_siswa])->get()->getRowObject();
                                    $jam_jawab = $js->jam ?? '-';
                                ?>
                                    <div class="nav-btn"
                                        id="nav-btn-<?= $n ?>"
                                        onclick="goToQuestion(<?= $n ?>)"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Waktu: <?= $jam_jawab ?>">
                                        <?= $n ?>
                                    </div>
                                <?php 
                                $n++;
                                endforeach; 
                                ?>
                            </div>

                            <div class="mt-8 pt-5 border-top border-gray-200">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="bullet bullet-dot bg-success h-15px w-15px me-3"></span>
                                    <span class="text-gray-700 fw-semibold fs-6">Benar</span>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <span class="bullet bullet-dot bg-danger h-15px w-15px me-3"></span>
                                    <span class="text-gray-700 fw-semibold fs-6">Salah</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="bullet bullet-dot bg-warning h-15px w-15px me-3"></span>
                                    <span class="text-gray-700 fw-semibold fs-6">Kosong / Tidak Dijawab</span>
                                </div>
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
        // Update Soal Visibility
        $('.question-item').removeClass('active');
        $('#question-' + currentQ).addClass('active');

        // Update Label Nomor
        $('#currentNumberLabel').text(currentQ);

        // Update Tombol
        $('#prevBtn').prop('disabled', currentQ === 1);
        
        if(currentQ === totalQ) {
            $('#nextBtn').html('Selesai <i class="ki-duotone ki-check fs-2"></i>').removeClass('btn-primary').addClass('btn-success');
        } else {
            $('#nextBtn').html('Selanjutnya <i class="ki-duotone ki-arrow-right fs-2"></i>').removeClass('btn-success').addClass('btn-primary');
        }

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
                // Scroll ke atas card (opsional agar nyaman di mobile)
                $('html, body').animate({
                    scrollTop: $(".card-header.bg-primary").offset().top - 100
                }, 300);
            } else {
                history.back(-1);
            }
        });

        $('#prevBtn').click(function() {
            if (currentQ > 1) {
                currentQ--;
                updateUI();
                $('html, body').animate({
                    scrollTop: $(".card-header.bg-primary").offset().top - 100
                }, 300);
            }
        });
    });
</script>
<?= $this->endSection(); ?>
<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?= $this->include('template/sidebar/guru'); ?>

<?php

use App\Models\UjiansiswaModel;

$UjiansiswaModel = new UjiansiswaModel();
?>

<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <div class="widget shadow p-3">
                    <div class="widget-heading">
                        <h5 class=""><?= $ujian->nama_ujian; ?></h5>
                        <table class="mt-2">
                            <tr>
                                <th>Jumlah Soal</th>
                                <th>: <?= count($detail_ujian); ?> Soal</th>
                            </tr>
                            <tr>
                                <th>Nama Peserta</th>
                                <th>: <?= $siswa->nama_siswa; ?></th>
                            </tr>
                        </table>
                        <div class="row mt-3">
                            <div class="col-sm-9">
                                <h5>Ujian <?= $siswa->nama_siswa; ?></h5>
                                <form id="examwizard-question" class="mt-3" action="#" method="POST">
                                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                    <div class="card-body">
                                        <div style="margin-top: -20px;">
                                            <?php
                                            $no = 1;
                                            $soal_hidden = '';
                                            //echo session()->get('id') . '<br>';
                                            //var_dump($siswa);

                                            foreach ($detail_ujian as $soal) : ?>
                                                <?php $jawaban_siswa = $UjiansiswaModel
                                                    ->where('ujian_id', $soal->id_detail_ujian)
                                                    //->where('siswa', session()->get('id'))
                                                    ->where('siswa', $siswa->id_siswa)
                                                    ->get()->getRowObject();
                                                ?>
                                                <div class="question <?= $soal_hidden; ?> question-<?= $no; ?>" data-question="<?= $no; ?>">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <h6 class="question-title color-green"><span><?= $no; ?>. </span> <?= strip_tags($soal->nama_soal, '<a><ul><li><i><em><strong>'); ?></h2>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-50">
                                                        <div class="col-sm-12">
                                                            <div class="alert alert-danger hidden"></div>
                                                            <div class="green-radio color-green">
                                                                <ul type="none" style="color: #000;">
                                                                    <?php if (substr($soal->pg_1, 3, strlen($soal->pg_1)) != null) { ?>
                                                                        <li class="answer-number <?= $jawaban_siswa->jawaban == 'A'? $soal->jawaban == 'A'? 'bg-success':'bg-danger':'' ?>">
                                                                            <label for="answer-<?= $soal->id_detail_ujian; ?>-<?= substr($soal->pg_1, 0, 1); ?>" class="answer-text" style="color: #000;">
                                                                                <span></span><?= substr($soal->pg_1, 3, strlen($soal->pg_1)); ?>
                                                                            </label>
                                                                        </li>
                                                                    <?php } ?>
                                                                    <?php if (substr($soal->pg_2, 3, strlen($soal->pg_2)) != null) { ?>
                                                                        <li class="answer-number <?= $jawaban_siswa->jawaban == 'B'? $soal->jawaban == 'B'? 'bg-success':'bg-danger':'' ?>">
                                                                            <label for="answer-<?= $soal->id_detail_ujian; ?>-<?= substr($soal->pg_2, 0, 1); ?>" class="answer-text" style="color: #000;">
                                                                                <span></span><?= substr($soal->pg_2, 3, strlen($soal->pg_2)); ?>
                                                                            </label>
                                                                        </li>
                                                                    <?php } ?>
                                                                    <?php if (substr($soal->pg_3, 3, strlen($soal->pg_3)) != null) { ?>
                                                                        <li class="answer-number <?= $jawaban_siswa->jawaban == 'C'? $soal->jawaban == 'C'? 'bg-success':'bg-danger':'' ?>">
                                                                            <label for="answer-<?= $soal->id_detail_ujian; ?>-<?= substr($soal->pg_3, 0, 1); ?>" class="answer-text" style="color: #000;">
                                                                                <span></span><?= substr($soal->pg_3, 3, strlen($soal->pg_3)); ?>
                                                                            </label>
                                                                        </li>
                                                                    <?php } ?>
                                                                    <?php if (substr($soal->pg_4, 3, strlen($soal->pg_4)) != null) { ?>
                                                                        <li class="answer-number <?= $jawaban_siswa->jawaban == 'D'? $soal->jawaban == 'D'? 'bg-success':'bg-danger':'' ?>">
                                                                            <label for="answer-<?= $soal->id_detail_ujian; ?>-<?= substr($soal->pg_4, 0, 1); ?>" class="answer-text" style="color: #000;">
                                                                                <span></span><?= substr($soal->pg_4, 3, strlen($soal->pg_4)); ?>
                                                                            </label>
                                                                        </li>
                                                                    <?php } ?>

                                                                    <?php if (substr($soal->pg_5, 3, strlen($soal->pg_5) != null)) { ?>
                                                                        <li class="answer-number <?= $jawaban_siswa->jawaban == 'E'? $soal->jawaban == 'E'? 'bg-success':'bg-danger':'' ?>">
                                                                            <label for="answer-<?= $soal->id_detail_ujian; ?>-<?= substr($soal->pg_5, 0, 1); ?>" class="answer-text" style="color: #000;">
                                                                                <span></span><?= substr($soal->pg_5, 3, strlen($soal->pg_5)); ?>
                                                                            </label>
                                                                        </li>
                                                                    <?php } ?>
                                                                </ul>
                                                                <?php if ($soal->jawaban == $jawaban_siswa->jawaban) : ?>
                                                                    <div class="mt-2"><span style="font-weight: bold;">Jawaban Kamu</span> : <?= $jawaban_siswa->jawaban; ?> <span class="badge badge-success ml-2">benar</span> <label for="" class="badge badge-info" data-bs-toggle="collapse" data-bs-target="#benar_penjelasan" aria-expanded="true" aria-controls="benar_penjelasan">?</label></div>
                                                                    <div class="accordion" id="benarPen">
                                                                        <div class="accordion-item">
                                                                            <div id="benar_penjelasan" class="accordion-collapse collapse" data-bs-parent="#benarPen">
                                                                                <h6>Penjelasan Jawaban</h6>
                                                                                <div class="accordion-body">
                                                                                    <?= $soal->penjelasan; ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>


                                                                <?php else : ?>
                                                                    <?php if ($jawaban_siswa->jawaban == NULL) : ?>
                                                                        <div class="mt-2"><span style="font-weight: bold;">Jawaban Kamu</span> :<span class="badge badge-warning ml-2">tidak dijawab</span> <label for="" class="badge badge-info" data-bs-toggle="collapse" data-bs-target="#tidak_dijawab_penjelasan" aria-expanded="true" aria-controls="tidak_dijawab_penjelasan">?</label></div>
                                                                        <div class="mt-2 text-success">Jawaban Benar : <?= $soal->jawaban; ?></div>
                                                                        <div class="accordion" id="tidakPen">
                                                                            <div class="accordion-item">
                                                                                <div id="tidak_dijawab_penjelasan" class="accordion-collapse collapse" data-bs-parent="#tidakPen">
                                                                                    <h6>Penjelasan Jawaban</h6>
                                                                                    <div class="accordion-body">
                                                                                        <?= $soal->penjelasan; ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    <?php else : ?>
                                                                        <div class="mt-2"><span style="font-weight: bold;">Jawaban Kamu</span> : <?= $jawaban_siswa->jawaban; ?> <span class="badge badge-danger ml-2">salah </span> <span class="badge badge-success ml-2">Jawaban Benar : <?= $soal->jawaban; ?></span>
                                                                            <label for="" class="badge badge-info" data-bs-toggle="collapse" data-bs-target="#salah_penjelasan" aria-expanded="true" aria-controls="salah_penjelasan">?</label>
                                                                        </div>
                                                                        <!--<div class="mt-2 text-success">Jawaban Benar : <?= $soal->jawaban; ?></div>-->
                                                                        <div class="accordion" id="salahPen">
                                                                            <div class="accordion-item">
                                                                                <div id="salah_penjelasan" class="accordion-collapse collapse" data-bs-parent="#salahPen">
                                                                                    <h6>Penjelasan Jawaban</h6>
                                                                                    <div class="accordion-body">
                                                                                        <?= $soal->penjelasan; ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>




                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php $soal_hidden = 'hidden'; ?>
                                                <?php $no++ ?>
                                            <?php endforeach; ?>
                                        </div>
                                        <!-- SOAL -->

                                        <input type="hidden" value="1" id="currentQuestionNumber" name="currentQuestionNumber" />
                                        <input type="hidden" value="<?= count($detail_ujian); ?>" id="totalOfQuestion" name="totalOfQuestion" />
                                        <input type="hidden" value="[]" id="markedQuestion" name="markedQuestions" />
                                        <!-- END SOAL -->
                                    </div>
                                </form>
                            </div>

                            <div class="col-sm-3" id="quick-access-section" class="table-responsive">
                                <table class="table text-center table-hover">
                                    <thead class="question-response-header">
                                        <tr>
                                            <th class="text-center">No. Soal</th>
                                            <th class="text-center">Jawaban Peserta</th>
                                            <th class="text-center">Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($detail_ujian as $soal) : ?>
                                            <?php $jawaban_siswa = $UjiansiswaModel
                                                ->where('ujian_id', $soal->id_detail_ujian)
                                                ->where('siswa', $siswa->id_siswa)
                                                //->where('siswa', session()->get('id'))
                                                ->get()->getRowObject();
                                            ?>
                                            <tr class="question-response-rows" data-question="<?= $no; ?>" style="cursor: pointer;">
                                                <td style="font-weight: bold;"><?= $no; ?></td>
                                                <td class="question-response-rows-value"><?= ($jawaban_siswa->jawaban == null) ? '-' : $jawaban_siswa->jawaban; ?></td>
                                                <td class="question-response-rows-value"><?= $jawaban_siswa->jam == null ? '-' : $jawaban_siswa->jam; ?></td>
                                            </tr>
                                            <?php $no++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <div class="text-nowrap text-center">
                                    <a href="javascript:void(0)" class="btn btn-success" id="quick-access-prev">
                                        &laquo;
                                    </a>
                                    <span class="alert alert-info" id="quick-access-info"></span>
                                    <a href="javascript:void(0)" class="btn btn-success" id="quick-access-next">&raquo;</a>
                                </div>
                            </div>

                        </div>
                        <!-- Exmas Footer - Multi Step Pages Footer -->
                        <div class="row">
                            <div class="col-lg-12 exams-footer">
                                <div class="row">
                                    <div class="col-sm-1 back-to-prev-question-wrapper text-center">
                                        <a href="javascript:void(0);" id="back-to-prev-question" class="btn btn-success disabled">
                                            Back
                                        </a>
                                    </div>
                                    <div class="col-sm-2 footer-question-number-wrapper text-center">
                                        <div>
                                            <span id="current-question-number-label">1</span>
                                            <span>Dari <b><?= count($detail_ujian); ?></b></span>
                                        </div>
                                        <div>
                                            Nomor Soal
                                        </div>
                                    </div>
                                    <div class="col-sm-1 go-to-next-question-wrapper text-center">
                                        <a href="javascript:void(0);" id="go-to-next-question" class="btn btn-success">
                                            Next
                                        </a>
                                    </div>
                                </div>
                                <br>
                                <span class="text-success" style="font-weight: bold;">BENAR : <?= count($jawaban_benar); ?></span> | <span class="text-danger" style="font-weight: bold;">SALAH : <?= count($jawaban_salah); ?></span> | <span class="text-warning" style="font-weight: bold;">TIDAK DIJAWAB : <?= count($tidak_dijawab); ?></span>
                            </div>

                        </div>
                        <a href="javascript:void(0)" class="btn btn-primary mt-3" onclick="history.back(-1)">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-wrapper">
        <div class="footer-section f-section-1">
            <p class="terms-conditions"><?= copyright() ?></p>
        </div>
        <div class="footer-section f-section-2">
           
        </div>
    </div>
</div>
<!--  END CONTENT AREA  -->

<!-- MODAL -->

<script>
    var examWizard = $.fn.examWizard({
        finishOption: {
            enableModal: true,
        },
        quickAccessOption: {
            quickAccessPagerItem: 5,
        },
    });
    $('.question-response-rows').click(function() {
        var no_soal = $(this).data('question');

        var soal_ini = '.question-' + no_soal;
        $('.question').addClass('hidden');
        $(soal_ini).removeClass('hidden');
        $('input[name=currentQuestionNumber]').val(no_soal);
        $('#current-question-number-label').text(no_soal);
        $('#back-to-prev-question').removeClass('disabled');
        $('#go-to-next-question').removeClass('disabled');

    });
    <?= session()->getFlashdata('pesan'); ?>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>


<?= $this->endSection(); ?>
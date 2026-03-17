<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<!--  BEGIN CONTENT AREA  -->
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <div class="widget shadow p-3">
                    <div class="widget-heading">
                        <div class="row ">
                            <div class="col-sm-12">
                                <div class="card-body">
                                    <div style="margin-top: -10px;">

                                        <div class="question">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h5>Soal</h5>
                                                </div>
                                                <div class="col-sm-12 mt-3">
                                                    <h6 class="question-title color-green text-justify "></span> <?= $soal->nama_soal; ?></h2>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-sm-12">
                                                    <div class="green-radio color-green">
                                                        <ol type="A" style="color: #000;">
                                                            <?php if (substr($soal->pg_1, 3, strlen($soal->pg_1)) != null) { ?>
                                                                <li class="answer-number">
                                                                    <label for="" class="answer-text" style="color: #000;">
                                                                        <span></span><?= substr($soal->pg_1, 3, strlen($soal->pg_1)); ?>
                                                                    </label>
                                                                </li>
                                                            <?php } ?>
                                                            <?php if (substr($soal->pg_2, 3, strlen($soal->pg_2)) != null) { ?>
                                                                <li class="answer-number">
                                                                    <label for="" class="answer-text" style="color: #000;">
                                                                        <span></span><?= substr($soal->pg_2, 3, strlen($soal->pg_2)); ?>
                                                                    </label>
                                                                </li>
                                                            <?php } ?>
                                                            <?php if (substr($soal->pg_3, 3, strlen($soal->pg_3)) != null) { ?>
                                                                <li class="answer-number">
                                                                    <label for="" class="answer-text" style="color: #000;">
                                                                        <span></span><?= substr($soal->pg_3, 3, strlen($soal->pg_3)); ?>
                                                                    </label>
                                                                </li>
                                                            <?php } ?>
                                                            <?php if (substr($soal->pg_4, 3, strlen($soal->pg_4)) != null) { ?>
                                                                <li class="answer-number">
                                                                    <label for="" class="answer-text" style="color: #000;">
                                                                        <span></span><?= substr($soal->pg_4, 3, strlen($soal->pg_4)); ?>
                                                                    </label>
                                                                </li>
                                                            <?php } ?>

                                                            <?php if (substr($soal->pg_5, 3, strlen($soal->pg_5) != null)) { ?>
                                                                <li class="answer-number">
                                                                    <label for="" class="answer-text" style="color: #000;">
                                                                        <span></span><?= substr($soal->pg_5, 3, strlen($soal->pg_5)); ?>
                                                                    </label>
                                                                </li>
                                                            <?php } ?>
                                                        </ol>
                                                        <div class="mt-4"><span style="font-weight: bold;">Jawaban</span> : <?= $soal->jawaban; ?> <label for="" class="badge badge-info ml-2" data-bs-toggle="collapse" data-bs-target="#benar_penjelasan" aria-expanded="true" aria-controls="benar_penjelasan">?</label></div>
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
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="javascript:void(0)" class="btn btn-primary mt-3" onclick="history.back(-1)">Kembali</a>
                        <a href="<?= base_url('sw-guru/bank-soal/edit') . '/' . encrypt_url($soal->id_bank_soal); ?>" class="btn btn-info mt-3">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!--  END CONTENT AREA  -->

<!-- MODAL -->



<?= $this->endSection(); ?>
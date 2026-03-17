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
                                <th>Kelas</th>
                                <th>: <?= $ujian->nama_kelas; ?></th>
                            </tr>
                            <tr>
                                <th>Jumlah Soal</th>
                                <th>: <?= count($detail_ujian); ?> Soal</th>
                            </tr>
                            <tr>
                                <th>Waktu Mulai</th>
                                <th>: <?= $ujian->waktu_mulai; ?></th>
                            </tr>
                            <tr>
                                <th>Waktu Selesai</th>
                                <th>: <?= $ujian->waktu_berakhir; ?></th>
                            </tr>
                        </table>
                        <div class="row mt-3">
                            <div class="col-sm-9">
                                <h5>Soal Ujian</h5>
                                <form id="examwizard-question" class="mt-3" action="#" method="POST">
                                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                    <div class="card-body">
                                        <div style="margin-top: -20px;">
                                            <?php
                                            $no = 1;
                                            $soal_hidden = '';
                                            foreach ($detail_ujian as $soal) : ?>
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
                                                                        <li class="answer-number">
                                                                            <label for="answer-<?= $soal->id_detail_ujian; ?>-<?= substr($soal->pg_1, 0, 1); ?>" class="answer-text" style="color: #000;">
                                                                                <span></span><?= substr($soal->pg_1, 3, strlen($soal->pg_1)); ?>
                                                                            </label>
                                                                        </li>
                                                                    <?php } ?>
                                                                    <?php if (substr($soal->pg_2, 3, strlen($soal->pg_2)) != null) { ?>
                                                                        <li class="answer-number">
                                                                            <label for="answer-<?= $soal->id_detail_ujian; ?>-<?= substr($soal->pg_2, 0, 1); ?>" class="answer-text" style="color: #000;">
                                                                                <span></span><?= substr($soal->pg_2, 3, strlen($soal->pg_2)); ?>
                                                                            </label>
                                                                        </li>
                                                                    <?php } ?>
                                                                    <?php if (substr($soal->pg_3, 3, strlen($soal->pg_3)) != null) { ?>
                                                                        <li class="answer-number">
                                                                            <label for="answer-<?= $soal->id_detail_ujian; ?>-<?= substr($soal->pg_3, 0, 1); ?>" class="answer-text" style="color: #000;">
                                                                                <span></span><?= substr($soal->pg_3, 3, strlen($soal->pg_3)); ?>
                                                                            </label>
                                                                        </li>
                                                                    <?php } ?>
                                                                    <?php if (substr($soal->pg_4, 3, strlen($soal->pg_4)) != null) { ?>
                                                                        <li class="answer-number">
                                                                            <label for="answer-<?= $soal->id_detail_ujian; ?>-<?= substr($soal->pg_4, 0, 1); ?>" class="answer-text" style="color: #000;">
                                                                                <span></span><?= substr($soal->pg_4, 3, strlen($soal->pg_4)); ?>
                                                                            </label>
                                                                        </li>
                                                                    <?php } ?>

                                                                    <?php if (substr($soal->pg_5, 3, strlen($soal->pg_5) != null)) { ?>
                                                                        <li class="answer-number">
                                                                            <label for="answer-<?= $soal->id_detail_ujian; ?>-<?= substr($soal->pg_5, 0, 1); ?>" class="answer-text" style="color: #000;">
                                                                                <span></span><?= substr($soal->pg_5, 3, strlen($soal->pg_5)); ?>
                                                                            </label>
                                                                        </li>
                                                                    <?php } ?>
                                                                </ul>
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
                                            <th class="text-center">Jawaban</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($detail_ujian as $soal) : ?>

                                            <tr class="question-response-rows" data-question="<?= $no; ?>" style="cursor: pointer;">
                                                <td style="font-weight: bold;"><?= $no; ?></td>
                                                <td class="question-response-rows-value"><?= $soal->jawaban; ?></td>
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
                            </div>

                        </div>


                        <ul class="list-group list-group-media mt-4">
                            <li class="list-group-item">Peserta Yang Sudah Mengerjakan</li>
                            <?php foreach ($siswa as $s) : ?>
                                <?php $belum_terjawab = $UjiansiswaModel->belum_terjawab($ujian->kode_ujian, $s->id_siswa, null); ?>
                                <?php $salah = $UjiansiswaModel->salah($ujian->kode_ujian, $s->id_siswa, 0); ?>
                                <?php $benar = $UjiansiswaModel->salah($ujian->kode_ujian, $s->id_siswa, 1); ?>
                                <?php $jawaban_siswa = $UjiansiswaModel
                                    ->where('ujian', $ujian->kode_ujian)
                                    //->where('siswa', session()->get('id'))
                                    ->where('siswa', $s->id_siswa)
                                    ->where('status', "selesai")
                                    ->get()->getNumRows();
                                ?>
                                <?php if ($s->date_send != 0) { ?>
                                    <?php if ($jawaban_siswa > 0) : ?>
                                        <li class="list-group-item list-group-item-action">
                                            <div class="row">
                                                <div class="col-md-10 col-8">
                                                    <a href="<?= base_url('guru/pg_siswa/') . '/' . encrypt_url($s->id_siswa) . '/' . encrypt_url($ujian->kode_ujian); ?>">
                                                        <div class="media">
                                                            <div class="mr-3">
                                                                <img alt="avatar" src="<?= base_url('assets/app-assets/user/') . '/' . $s->avatar; ?>" class="img-fluid rounded-circle">
                                                            </div>
                                                            <div class="media-body">
                                                                <h6 class="tx-inverse"><?= $s->nama_siswa; ?> - (<?= round(count($benar) / count($detail_ujian) * 100, 0) ?>) <?= $s->date_send == 0 ? '' : date('d-m-Y H:i:s', $s->date_send) ?></h6>
                                                                <p class="mg-b-0">
                                                                    <span class="text-success"> Benar</span> : <?= count($benar); ?> | <span class="text-danger">Salah</span> : <?= count($salah); ?> | <span class="text-warning">Belum Terjawab</span> : <?= count($belum_terjawab); ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-2 col-4">
                                                    <div class="dropdown custom-dropdown">
                                                        <a class="dropdown-toggle btn btn-primary" href="#" role="button" id="dropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                                                <line x1="3" y1="12" x2="21" y2="12"></line>
                                                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                                                <line x1="3" y1="18" x2="21" y2="18"></line>
                                                            </svg>
                                                        </a>

                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink2">
                                                            <a class="dropdown-item" href="<?= base_url('guru/pg_siswa/') . '/' . encrypt_url($s->id_siswa) . '/' . encrypt_url($ujian->kode_ujian); ?>">Lihat</a>
                                                            <a class="dropdown-item" target="_blank" href="<?= base_url('guru/cetak_soal_peserta/') . '/' . encrypt_url($s->id_siswa) . '/' . encrypt_url($ujian->kode_ujian); ?>">Cetak PDF</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                <?php } ?>
                            <?php endforeach; ?>
                            <?php foreach ($siswa as $s) : ?>
                                <?php $belum_terjawab = $UjiansiswaModel->belum_terjawab($ujian->kode_ujian, $s->id_siswa, null); ?>
                                <?php $salah = $UjiansiswaModel->salah($ujian->kode_ujian, $s->id_siswa, 0); ?>
                                <?php $benar = $UjiansiswaModel->salah($ujian->kode_ujian, $s->id_siswa, 1); ?>
                                <?php $jawaban_siswa = $UjiansiswaModel
                                    ->where('ujian', $ujian->kode_ujian)
                                    //->where('siswa', session()->get('id'))
                                    ->where('siswa', $s->id_siswa)
                                    ->where('status', "selesai")
                                    ->get()->getNumRows();
                                ?>
                                <?php if ($s->date_send == 0) { ?>
                                    <?php if ($jawaban_siswa > 0) : ?>
                                        <li class="list-group-item list-group-item-action">
                                            <div class="row">
                                                <div class="col-md-10 col-8">
                                                    <a href="<?= base_url('guru/pg_siswa/') . '/' . encrypt_url($s->id_siswa) . '/' . encrypt_url($ujian->kode_ujian); ?>">
                                                        <div class="media">
                                                            <div class="mr-3">
                                                                <img alt="avatar" src="<?= base_url('assets/app-assets/user/') . '/' . $s->avatar; ?>" class="img-fluid rounded-circle">
                                                            </div>
                                                            <div class="media-body">
                                                                <h6 class="tx-inverse"><?= $s->nama_siswa; ?> - (<?= round(count($benar) / count($detail_ujian) * 100, 0) ?>) <?= $s->date_send == 0 ? '' : date('d-m-Y H:i:s', $s->date_send) ?></h6>
                                                                <p class="mg-b-0">
                                                                    <span class="text-success"> Benar</span> : <?= count($benar); ?> | <span class="text-danger">Salah</span> : <?= count($salah); ?> | <span class="text-warning">Belum Terjawab</span> : <?= count($belum_terjawab); ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-2 col-4">
                                                    <div class="dropdown custom-dropdown">
                                                        <a class="dropdown-toggle btn btn-primary" href="#" role="button" id="dropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                                                <line x1="3" y1="12" x2="21" y2="12"></line>
                                                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                                                <line x1="3" y1="18" x2="21" y2="18"></line>
                                                            </svg>
                                                        </a>

                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink2">
                                                            <a class="dropdown-item" href="<?= base_url('guru/pg_siswa/') . '/' . encrypt_url($s->id_siswa) . '/' . encrypt_url($ujian->kode_ujian); ?>">Lihat</a>
                                                            <a class="dropdown-item" target="_blank" href="<?= base_url('guru/cetak_soal_peserta/') . '/' . encrypt_url($s->id_siswa) . '/' . encrypt_url($ujian->kode_ujian); ?>">Cetak PDF</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                <?php } ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <a href="javascript:void(0)" class="btn btn-primary mt-3" onclick="history.back(-1)">Kembali</a>
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
</script>


<?= $this->endSection(); ?>
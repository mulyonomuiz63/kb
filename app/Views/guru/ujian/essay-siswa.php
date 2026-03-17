<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?= $this->include('template/sidebar/guru'); ?>

<?php

use App\Models\EssaysiswaModel;
use App\Models\EssaydetailModel;

$essaysiswamodel = new EssaysiswaModel();
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
                                <th>: <?= count($essay_detail); ?> Soal</th>
                            </tr>
                            <tr>
                                <th>Nama Peserta</th>
                                <th>: <?= $siswa->nama_siswa; ?></th>
                            </tr>
                            <tr>
                                <th>Kelas</th>
                                <th>: <?= $ujian->nama_kelas; ?></th>
                            </tr>
                        </table>
                        <div class="mt-3">
                            <form action="<?= base_url('guru/nilai_essay'); ?>" method="POST">
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                <input type="hidden" name="kode_ujian" value="<?= $ujian->kode_ujian; ?>">
                                <input type="hidden" name="siswa" value="<?= $siswa->id_siswa; ?>">
                                <div id="circle-basic">
                                    <?php
                                    $no = 1;
                                    foreach ($essay_detail as $soal) : ?>
                                        <?php
                                        $jawaban_siswa = $essaysiswamodel
                                            ->where('essay_id', $soal->id_essay_detail)
                                            ->where('siswa', $siswa->id_siswa)
                                            ->get()->getRowObject();
                                        ?>
                                        <h3>no</h3>
                                        <section style="border: 1px solid #eaeaea; border-left: 5px solid #304aca;">
                                            <h5 style="margin-top: -25px;">No. <?= $no++; ?> - <?= count($essay_detail); ?></h5>
                                            <p class="mt-2"><?= $soal->soal; ?></p>
                                            <p><strong>Jawaban Peserta : </strong></p>
                                            <div class="mt-2 rounded p-3" style="border: 1px solid #304aca;">
                                                <?php if ($jawaban_siswa->jawaban) : ?>
                                                    <?= $jawaban_siswa->jawaban; ?>
                                                <?php else : ?>
                                                    <span class="badge badge-danger">TIDAK DIJAWAB</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="mt-3">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon5">Score</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="<?= $jawaban_siswa->id_essay_siswa; ?>" placeholder="Score" aria-label="Score">
                                                </div>
                                            </div>
                                        </section>
                                    <?php endforeach; ?>
                                </div>
                                <button class="btn btn-success d-flex ml-auto">Kirim Nilai</button>
                            </form>
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

<script>
    <?= session()->getFlashdata('pesan'); ?>
    $(document).ready(function() {
        $('.content').css('background', 'white');
        $('.steps ul').css('display', 'none');

        $("circle-basic").steps({
            cssClass: 'circle wizard'
        });
    })
</script>


<?= $this->endSection(); ?>
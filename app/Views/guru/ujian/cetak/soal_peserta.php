<?php

use App\Models\UjiansiswaModel;

$UjiansiswaModel = new UjiansiswaModel();
?>
<table>
    <tr>
        <th width="100">Jumlah Soal</th>
        <th>: <?= count($detail_ujian); ?> Soal</th>
    </tr>
    <tr>
        <th width="100">Nama Peserta</th>
        <th>: <?= $siswa->nama_siswa; ?></th>
    </tr>
</table>
<ol style="margin-left: -50em;" type="1">
    <?php
    $no = 1;
    $soal_hidden = '';
    foreach ($detail_ujian as $soal) : ?>
        <?php $jawaban_siswa = $UjiansiswaModel
            ->where('ujian_id', $soal->id_detail_ujian)
            //->where('siswa', session()->get('id'))
            ->where('siswa', $id_siswa)
            ->get()->getRowObject();
        ?>
        <li><?= $soal->nama_soal; ?>
            <ol type="A">
                <?php if (substr($soal->pg_1, 3, strlen($soal->pg_1) != null)) { ?>
                    <li><?= substr($soal->pg_1, 3, strlen($soal->pg_1)); ?></li>
                <?php } ?>
                <?php if (substr($soal->pg_2, 3, strlen($soal->pg_2) != null)) { ?>
                    <li><?= substr($soal->pg_2, 3, strlen($soal->pg_2)); ?></li>
                <?php } ?>
                <?php if (substr($soal->pg_3, 3, strlen($soal->pg_3) != null)) { ?>
                    <li><?= substr($soal->pg_3, 3, strlen($soal->pg_3)); ?></li>
                <?php } ?>
                <?php if (substr($soal->pg_4, 3, strlen($soal->pg_4) != null)) { ?>
                    <li><?= substr($soal->pg_4, 3, strlen($soal->pg_4)); ?></li>
                <?php } ?>
                <?php if (substr($soal->pg_5, 3, strlen($soal->pg_5) != null)) { ?>
                    <li><?= substr($soal->pg_5, 3, strlen($soal->pg_5)); ?></li>
                <?php } ?>
            </ol>
            <br>
        </li>

        <div>
            Jawaban Peserta: <?= $jawaban_siswa->jawaban == null ? 'Tidak dijawab' : $jawaban_siswa->jawaban; ?><br>
        </div>
    <?php endforeach; ?>
</ol>
 <ol type="1">
     <?php
        $no = 1;
        $soal_hidden = '';
        foreach ($detail_ujian as $soal) : ?>

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
             Jawaban : <?= $soal->jawaban; ?><br>
             Penjelasan : <?= $soal->penjelasan; ?>
         </div>

     <?php endforeach; ?>
 </ol>
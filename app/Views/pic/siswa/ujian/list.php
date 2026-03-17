<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?= $this->include('template/sidebar/pic'); ?>
<?php $db = Config\Database::connect(); ?>
<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <div class="widget shadow p-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="widget-heading d-flex justify-content-between">
                                <h5>Ujian</h5>
                               <a href="javascript:window.history.go(-1);" class="btn btn-primary">Kembali</a>
                            </div>
                            <div class="table-responsive" style="overflow-x: scroll;">
                                <table id="datatable-table" class="table text-left text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Nama Ujian</th>
                                            <th>Kelas</th>
                                            <th>Kuota</th>
                                            <th>Durasi</th>
                                            <th>Nilai</th>
                                            <th>Status</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ujian as $u) : ?>
                                        <?php
                                             $total = 0;
                                             $durasi=0;
                                            $ujianDetail = $db->query("select * from ujian_detail where kode_ujian = '$u->kode_ujian'")->getResult();
                                            foreach($ujianDetail as $dataRows){
                                                $total++;
                                            }
                                            $jml = $db->query("select count(kode_ujian) as total_soal from ujian_detail where kode_ujian = '$u->kode_ujian'")->getRow();
                                                    
                                            $totalMenit = $total * 3;
                                            $start =  (date('Y-m-d H:i'));
                                            $end_ = (date('Y-m-d H:i', strtotime("+ $totalMenit minutes")));
    
                                            
                                            $start_ujian = date_create($start);
                                            $end_ujian = date_create($end_);
                                            $durasi = date_diff($start_ujian, $end_ujian);
                                        ?>
                                            <tr>
                                                <td><?= $u->nama_ujian; ?></td>
                                                <td><?= $u->nama_kelas; ?></td>
                                                <td><?= $u->kuota; ?> Kali</td>
                                                <td><?= ($durasi != '0'? (($durasi->h * 60) + $durasi->i):'0') ?> Menit</td>
                                                <td><?= $u->nilai == null? '-':$u->nilai ?></td>
                                                <td><?= $u->nilai == null? '-' :($u->nilai >= 60 ? '<span class="badge badge-success ml-2">Lulus</span>' : '<span class="badge badge-danger ml-2">Tidak Lulus</span>'); ?></td>
                                                <td>
                                                    <?php if ($u->status == 'B') : ?>
                                                        <?php
                                                             $data = $db->query("select * from status_ujian where kode_ujian = '$u->kode_ujian'")->getRow();
                                                        ?>
                                                        <?php if(!empty($data)): ?>
                                                            <?php if($data->status == 'A'): ?>
                                                                <a href="<?= base_url('siswa/lihat_pg/') . '/' . encrypt_url($u->kode_ujian) . '/' . encrypt_url(session()->get('id')) .'/' . encrypt_url($u->id_ujian) .'/' . encrypt_url($u->status); ?>" class="btn btn-primary btn-informasi-mulai" >Mulai Ujian</a>
                                                            <?php else: ?>
                                                                <a href="javascript:void(0)" class="btn btn-primary btn-informasi-disabled disabled" >Mulai Ujian</a>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <a href="javascript:void(0)" class="btn btn-primary btn-informasi-disabled disabled" >Mulai Ujian</a>
                                                        <?php endif; ?>
                                                    <?php elseif($u->status == 'U') : ?>
                                                       <a href="<?= base_url('siswa/lihat_pg/') . '/' . encrypt_url($u->kode_ujian) . '/' . encrypt_url(session()->get('id')) .'/' . encrypt_url($u->id_ujian) .'/' . encrypt_url($u->status); ?>" class="btn btn-warning">Sedang Ujian</a>
                                                    <?php else: ?>
                                                        <?php if($u->kuota != '0' ){ ?>
                                                            <a href="<?= base_url('siswa/remedial').'/'.encrypt_url($u->id_ujian). '/' . encrypt_url($u->kode_ujian) .'/'.encrypt_url($u->status) ?>" class="btn btn-danger btn-informasi">Ujian Ulang</a>
                                                        <?php }else{ ?>
                                                            <?php if($u->nilai >= 60): ?>
                                                                <a href="<?= base_url('siswa/sertifikat/') ?>" class="btn btn-success">Ujian Selesai</a>
                                                            <?php else: ?>
                                                                <a href="<?= base_url('/#bimbel') ?>" class="btn btn-warning btn-ujian-ulang">Ujian Ulang</a>
                                                            <?php endif; ?>
                                                        <?php } ?>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
    $('.btn-informasi').on('click', function(e) {
        e.preventDefault();
        const href = $(this).attr('href');
        swal({
            title: 'Informasi!',
            text: "Mengulangi ujian akan merubah hasil yang sebelumnya, anda yakin?",
            type: 'info',
            showCancelButton: true,
            confirmButtonText: 'OK',
            padding: '2em'
        }).then(function(result) {
            if (result.value) {
                document.location.href = href
            }
        });
    });
    
    $('.btn-informasi-mulai').on('click', function(e) {
        e.preventDefault();
        const href = $(this).attr('href');
        swal({
            title: 'Informasi!',
            text: "Klik OK untuk memulai ujian",
            type: 'info',
            showCancelButton: true,
            confirmButtonText: 'OK',
            padding: '2em'
        }).then(function(result) {
            if (result.value) {
                document.location.href = href
            }
        });
    });
    
    $('.btn-informasi-disabled').on('click', function(e) {
        e.preventDefault();
        const href = $(this).attr('href');
        swal({
            title: 'Informasi!',
            text: "Ujian belum dibuka",
            type: 'info',
            showCancelButton: true,
            confirmButtonText: 'OK',
            padding: '2em'
        }).then(function(result) {
            if (result.value) {
                document.location.href = href
            }
        });
    });
   
    
    
    $('.btn-ujian-ulang').on('click', function(e) {
        e.preventDefault();
        const href = $(this).attr('href');
        swal({
            title: 'Informasi!',
            text: "Nilai anda tidak memenuhi standar sistem kami, silahkan ujian ulang dengan memesan kembali paket ujian!",
            type: 'info',
            showCancelButton: true,
            confirmButtonText: 'OK',
            padding: '2em'
        }).then(function(result) {
            if (result.value) {
                document.location.href = href
            }
        });
    });
            
            
</script>

<?= $this->endSection(); ?>
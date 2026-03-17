<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?= $this->include('template/sidebar/pic'); ?>
<?php
use App\Models\UjianMasterModel;
use App\Models\UjianModel;
$this->UjianMasterModel = new UjianMasterModel();
$this->UjianModel = new UjianModel();
?>
<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <div class="widget shadow p-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="widget-heading d-flex justify-content-between">
                                <h5>Peserta</h5>
                               <a href="javascript:window.history.go(-1);" class="btn btn-primary">Kembali</a>
                            </div>
                            <div class="table-responsive">
                                <table id="datatable-table" class="table text-nowrap">
                                    <thead >
                                        <tr>
                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Telp</th>
                                            <th>Email</th>
                                            <th>Tgl Lulus</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($sertifikatAB as $s) : ?>
                                        <?php
                                            $ujian = $this->UjianModel->getAllByKelasSertifikat($s->kelas, $s->id_siswa);
                                                foreach ($ujian as $u) {
                                                    $data['ujian'][] = $u;
                                                }
                                                
                                                $dataUjian = $this->UjianMasterModel->where('kelas', $s->kelas)->groupBy('mapel')->get()->getResultObject();
                                                $total = 0;
                                                foreach($dataUjian as $rr){
                                                    $total++;
                                                }
                                                
                                                $totalUjian = $this->UjianModel->where('kelas', $s->kelas)->where('id_siswa', $s->id_siswa)
                                                            ->where('ujian.nilai >=', 60)
                                                            ->groupBy('ujian.mapel')->get()->getResultObject();
                                                $totalSertifikat =0;
                                                foreach ($totalUjian as $r){
                                                    $totalSertifikat++;
                                                }
                                        ?>
                                            <?php if($total != 0 ){ ?>
                                                <?php if($totalSertifikat >= $total){ ?>
                                            <tr>
                                                <td><?= $s->no_induk_siswa; ?></td>
                                                <td><?= $s->nama_siswa; ?></td>
                                                <td><?= $s->hp; ?></td>
                                                <td><?= $s->email; ?></td>
                                                <td><?= $s->end_ujian; ?></td>
                                                <td class="text-center">
                                                    <a href="<?= base_url('pic/sertifikat/'.encrypt_url($s->id_siswa)) ?>" class="badge  bg-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="List sertifikat siswa">
                                                        <i class="bi bi-file-earmark-fill"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                                 <?php } ?>
                                            <?php } ?>
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
<?= $this->endSection(); ?>
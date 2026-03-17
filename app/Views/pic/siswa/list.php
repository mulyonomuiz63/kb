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
                                <a href="<?= base_url('pic/sertifikatAB/') ?>" class="badge  bg-success d-flex align-items-center p-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="List sertifikat AB seluruh siswa">
                                    Sertifikat AB
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table id="datatable-table" class="table text-nowrap">
                                    <thead class="text-center">
                                        <tr>
                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Handphone</th>
                                            <th>Registrasi</th>
                                            <th>Status</th>
                                            <th>Ujian</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($siswa as $s) : ?>
                                        
                                        <?php
                                                $totalUjian = $this->UjianModel->where('kelas', $s->kelas)->where('id_siswa', $s->id_siswa)
                                                            ->where('ujian.nilai >=', 60)
                                                            ->groupBy('ujian.mapel')->get()->getResultObject();
                                                $totalSertifikat =0;
                                                foreach ($totalUjian as $r){
                                                    $totalSertifikat++;
                                                }
                                                
                                                $totalUjians = $this->UjianModel->where('kelas', $s->kelas)->where('id_siswa', $s->id_siswa)
                                                            ->groupBy('ujian.mapel')->get()->getResultObject();
                                                $totalSertifikats =0;
                                                foreach ($totalUjians as $rs){
                                                    $totalSertifikats++;
                                                }
                                        ?>
                                        
                                            <tr >
                                                <td><?= $s->no_induk_siswa; ?></td>
                                                <td class="text-wrap"><?= $s->nama_siswa; ?></td>
                                                <td><?= $s->email; ?></td>
                                                <td><?= $s->hp; ?></td>
                                                <td class="text-center"><?= date('d-m-Y',$s->date_created); ?></td>



                                                <td class="text-center"><?= $s->is_active != 0 ? ' <p class="text-success">Aktif</p>' : ' <p class="text-danger">Tidak Aktif</p>'; ?></td>
                                                <td class="text-center"><?= $totalSertifikat.'/'.$totalSertifikats ?></td>
                                                <td class="text-left">
                                                    <?php if($totalSertifikat > 0): ?>
                                                        <a href="<?= base_url('Pic/sertifikat/'.encrypt_url($s->id_siswa)) ?>" class="badge  bg-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="List sertifikat siswa">
                                                            <i class="bi bi-file-earmark-fill"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="<?= base_url('Pic/ujian/'.encrypt_url($s->id_siswa)) ?>" class="badge  bg-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="List ujian siswa">
                                                        <i class="bi bi-file-earmark-fill"></i>
                                                    </a>
                                                    <?php if($s->hp != ''): ?>
                                                        <a href="https://wa.me/<?= number_hp($s->hp) ?>" target="_blank" class="badge  bg-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Kirim pesan ke WA">
                                                            <i class="bi bi-whatsapp"></i>
                                                        </a>
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
</script>

<?= $this->endSection(); ?>
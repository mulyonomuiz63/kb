<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?php
use App\Models\UjianMasterModel;
use App\Models\UjianModel;
$this->UjianMasterModel = new UjianMasterModel();
$this->UjianModel = new UjianModel();
?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="card card-flush">
                
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-sertifikat-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari data..." />
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatable-list" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-100px">NIP</th>
                                    <th class="min-w-150px">Nama</th>
                                    <th class="min-w-125px">Telp</th>
                                    <th class="min-w-150px">Email</th>
                                    <th class="min-w-125px">Tgl Lulus</th>
                                    <th class="text-center min-w-70px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                
                                <?php 
                                $no = 1;
                                foreach ($sertifikatAB as $s) : 
                                    
                                    // LOGIKA PHP ORIGINAL ANDA DITAHAN DI SINI
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
                                    
                                    $totalSertifikat = 0;
                                    foreach ($totalUjian as $r){
                                        $totalSertifikat++;
                                    }
                                    
                                    if($total != 0 ){ 
                                        if($totalSertifikat >= $total){ 
                                ?>
                                
                                <tr>
                                    <td>
                                        <span class="text-gray-800 fw-bold"><?= $s->no_induk_siswa; ?></span>
                                    </td>
                                    <td><?= $s->nama_siswa; ?></td>
                                    <td><?= $s->hp; ?></td>
                                    <td><?= $s->email; ?></td>
                                    <td>
                                        <span class="badge badge-light-primary fw-bold px-3 py-2"><?= $s->end_ujian; ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('sw-admin/siswa/sertifikat/'.encrypt_url($s->id_siswa)) ?>" class="btn btn-icon btn-light-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Sertifikat">
                                            <i class="ki-duotone ki-file-added fs-3">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                        </a>
                                    </td>
                                </tr>
                                
                                <?php 
                                        } 
                                    } 
                                endforeach; 
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Inisialisasi DataTables dengan gaya Metronic
        var table = $('#datatable-list').DataTable({
            "ordering": false,
        });

        // Fitur Pencarian Custom
        $('[data-kt-sertifikat-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

    });
</script>
<?= $this->endSection(); ?>
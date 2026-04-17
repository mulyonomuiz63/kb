<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="card card-flush shadow-sm">
                
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Daftar Instruktur</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Pilih instruktur untuk mengatur relasi mata pelajaran dan kelas.</span>
                    </h3>
                </div>

                <div class="card-body pt-5">
                    <div class="row align-items-center">
                        
                        <div class="col-lg-7 col-md-12 order-2 order-lg-1">
                            <div class="table-responsive">
                                <table id="tableGuru" class="table align-middle table-row-dashed fs-6 gy-5">
                                    <thead>
                                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 border-bottom border-gray-200">
                                            <th class="min-w-250px">Nama Guru</th>
                                            <th class="text-center min-w-150px">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                        <?php foreach ($guru as $g) : ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-40px symbol-circle me-4">
                                                            <span class="symbol-label bg-light-primary text-primary fw-bold fs-5">
                                                                <?= strtoupper(substr($g->nama_guru, 0, 1)); ?>
                                                            </span>
                                                        </div>
                                                        <span class="text-gray-800 fw-bold fs-5"><?= $g->nama_guru; ?></span>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?= base_url('sw-admin/relasi/atur-relasi/' . encrypt_url($g->id_guru)); ?>" class="btn btn-sm btn-light-primary fw-bold px-4 py-2 hover-scale">
                                                        <i class="ki-duotone ki-user-edit fs-3 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> Relasikan
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-5 col-md-12 text-center order-1 order-lg-2 mb-10 mb-lg-0">
                            <img src="<?= base_url('assets/app-assets/img/relation.svg'); ?>" 
                                 class="img-fluid w-75 hover-scale" 
                                 alt="Illustration Relation">
                        </div>

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
        // Jika Anda ingin mengaktifkan DataTables (meskipun di kode asli tidak ada inisialisasi JS nya, 
        // tapi tabelnya memiliki id="tableGuru"), Anda bisa membukanya di bawah ini:
        
        $('#tableGuru').DataTable({
            "ordering": false,
        });
        
    });
</script>
<?= $this->endSection(); ?>
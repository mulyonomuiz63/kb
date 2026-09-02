<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="card card-flush shadow-sm border-0">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title flex-column align-items-start">
                        <span class="card-label fw-bold text-gray-800">Daftar Pengajuan IKH</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Kelola persetujuan dan penerbitan Kartu IKH.</span>
                    </div>
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" id="searchInput" class="form-control form-control-solid w-250px ps-12" placeholder="Cari No HP / Nama..." />
                        </div>
                    </div>
                </div>
                
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_ikh">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-150px">Nama Lengkap & No HP</th>
                                    <th class="min-w-125px">Instansi</th>
                                    <th class="min-w-125px">Status Validasi</th>
                                    <th class="min-w-125px">Tahap Akhir</th>
                                    <th class="text-end min-w-70px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                <?php foreach($list_ikh as $row): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <a href="<?= base_url('admin/ikh/review/'.$row['id_ikh']) ?>" class="text-gray-800 text-hover-primary mb-1 fw-bold"><?= $row['nama_lengkap'] == ''? $row['nama_siswa']:$row['nama_lengkap'] ?></a>
                                            <?php 
                                                $no_hp = $row['hp'] ?? '';
                                                $clean_hp = preg_replace('/[^0-9]/', '', $no_hp);
                                                if(substr($clean_hp, 0, 1) === '0') {
                                                    $clean_hp = '62' . substr($clean_hp, 1);
                                                }
                                            ?>
                                            <?php if(!empty($no_hp)): ?>
                                                <a href="https://wa.me/<?= $clean_hp ?>" target="_blank" class="text-success text-hover-primary d-flex align-items-center">
                                                    <i class="ki-outline ki-whatsapp fs-4 me-1"></i><?= $no_hp ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?= $row['nama_kantor'] ?></td>
                                    <td>
                                        <?php
                                            $badgeVal = 'badge-light-warning'; $textVal = 'Pending';
                                            if($row['status_validasi_admin'] == 'valid') { $badgeVal = 'badge-light-success'; $textVal = 'Valid'; }
                                            if($row['status_validasi_admin'] == 'ditolak') { $badgeVal = 'badge-light-danger'; $textVal = 'Ditolak'; }
                                        ?>
                                        <span class="badge <?= $badgeVal ?> fs-7 fw-bold"><?= $textVal ?></span>
                                    </td>
                                    <td>
                                        <?php if($row['status_sertifikat'] == 'terbit'): ?>
                                            <span class="badge badge-light-success fs-7">Sertifikat Terbit</span>
                                        <?php else: ?>
                                            <span class="badge badge-light-secondary fs-7">Dalam Proses</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php if($row['status_validasi_admin'] != 'draft'): ?>
                                            <a href="<?= base_url('sw-admin/ikh/review/'.encrypt_url($row['id_ikh'])) ?>" class="btn btn-sm btn-light-primary">
                                                <i class="ki-outline ki-eye fs-3"></i> Review
                                            </a>
                                        <?php else: ?>
                                            <span>-</span>
                                        <?php endif ?>
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
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        var table = $('#kt_table_ikh').DataTable({
            "info": false,
            "order": [],
            "pageLength": 10,
        });
        
        $('#searchInput').on('keyup', function () {
            table.search(this.value).draw();
        });
    });
</script>
<?= $this->endSection(); ?>
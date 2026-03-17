<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            
            <div class="widget widget-content-area br-6 shadow-sm">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4 class="p-3">Relasi Pengajar</h4>
                        </div>
                    </div>
                </div>

                <div class="p-4">
                    <div class="row align-items-center">
                        
                        <div class="col-lg-7 col-md-12 order-lg-1 order-2">
                            <div class="table-responsive">
                                <table id="tableGuru" class="table table-hover table-bordered" style="width:100%">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-dark font-weight-bold">Nama Guru</th>
                                            <th class="text-center text-dark font-weight-bold" style="width: 30%;">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($guru as $g) : ?>
                                            <tr>
                                                <td class="align-middle">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-primary-light text-primary rounded-circle p-2 mr-3 text-center" style="width: 40px; height: 40px;">
                                                            <?= strtoupper(substr($g->nama_guru, 0, 1)); ?>
                                                        </div>
                                                        <span class="font-weight-600"><?= $g->nama_guru; ?></span>
                                                    </div>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <a href="<?= base_url('sw-admin/relasi/atur-relasi/' . encrypt_url($g->id_guru)); ?>" class="btn btn-primary btn-rounded shadow-sm">
                                                        <i class="bi bi-person-plus-fill mr-1"></i> Relasikan
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-5 col-md-12 text-center order-lg-2 order-1 mb-lg-0 mb-4">
                            <img src="<?= base_url('assets/app-assets/img/relation.svg'); ?>" 
                                 class="img-fluid" 
                                 style="max-width: 80%; transition: transform .3s ease;" 
                                 onmouseover="this.style.transform='scale(1.05)'" 
                                 onmouseout="this.style.transform='scale(1)'"
                                 alt="Illustration">
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Custom CSS untuk mempercantik tampilan */
    .widget-content-area {
        background-color: #fff;
        border-radius: 12px;
        border: none;
    }
    .bg-primary-light {
        background-color: rgba(67, 97, 238, 0.1);
    }
    .font-weight-600 {
        font-weight: 600;
        color: #3b3f5c;
    }
    .table thead th {
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1px;
    }
    .btn-rounded {
        border-radius: 30px;
        padding: 8px 20px;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fb;
        transition: 0.3s;
    }
</style>


<?= $this->endSection(); ?>
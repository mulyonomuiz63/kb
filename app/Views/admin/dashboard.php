<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<style>
    /* Custom utility untuk mempercantik tampilan tanpa merusak struktur Bootstrap */
    .card-dashboard {
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .card-dashboard:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important;
    }
    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 15px;
    }
    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: #3b3f5c;
    }
    .stat-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #888ea8;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
</style>

<div class="layout-px-spacing animate__animated animate__fadeIn">
    <div class="row layout-top-spacing mt-4">
        
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-dashboard shadow-sm h-100">
                <div class="card-body">
                    <div class="icon-box bg-light-primary text-primary shadow-sm">
                        <i class="fas fa-users"></i>
                    </div>
                    <p class="stat-label mb-1">Total Peserta</p>
                    <div class="stat-value mb-2"><?= count($siswa); ?></div>
                    <div class="d-flex justify-content-between small border-top pt-2">
                        <span class="text-success"><i class="fas fa-check-circle"></i> <?= count($siswa_aktif); ?> Aktif</span>
                        <span class="text-danger"><i class="fas fa-times-circle"></i> <?= count($siswa_tidak_aktif); ?> Off</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-dashboard shadow-sm h-100">
                <div class="card-body">
                    <div class="icon-box bg-light-info text-info shadow-sm">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <p class="stat-label mb-1">Total Pengajar</p>
                    <div class="stat-value mb-2"><?= count($guru); ?></div>
                    <div class="d-flex justify-content-between small border-top pt-2">
                        <span class="text-success"><i class="fas fa-id-badge"></i> <?= count($guru_aktif); ?> Aktif</span>
                        <span class="text-danger"><i class="fas fa-id-badge"></i> <?= count($guru_tidak_aktif); ?> Off</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-dashboard shadow-sm h-100">
                <div class="card-body">
                    <div class="icon-box bg-light-success text-success shadow-sm">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <p class="stat-label mb-1">Mata Pelajaran</p>
                    <div class="stat-value mb-2"><?= count($mapel); ?></div>
                    <div class="small border-top pt-2">
                        <span class="text-muted"><i class="fas fa-tags"></i> Materi Aktif Terdaftar</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-dashboard shadow-sm h-100">
                <div class="card-body">
                    <div class="icon-box bg-light-warning text-warning shadow-sm">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <p class="stat-label mb-1">Jumlah Mitra</p>
                    <div class="stat-value mb-2"><?= count($mitra); ?></div>
                    <div class="small border-top pt-2">
                        <span class="text-muted"><i class="fas fa-building"></i> Instansi/Mitra Aktif</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-dashboard shadow-sm h-100">
                <div class="card-body">
                    <div class="icon-box bg-light-danger text-danger shadow-sm">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <p class="stat-label mb-1">Total Transaksi</p>
                    <?php
                        $total = 0;
                        $total_transaksi = 0;
                        foreach($transaksi as $row){
                            $total_transaksi++;
                            $diskon = ($row->nominal * $row->diskon) / 100;
                            $totalDiskon = $row->nominal - $diskon;
                            $diskon_voucher = ($totalDiskon * $row->voucher) / 100;
                            $total += ($row->nominal - $diskon - $diskon_voucher);
                        }
                    ?>
                    <div class="stat-value mb-2"><?= $total_transaksi ?></div>
                    <div class="small border-top pt-2">
                        <span class="font-weight-bold text-primary">Rp <?= number_format($total, 0, '.', '.'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-dashboard shadow-sm h-100">
                <div class="card-body">
                    <div class="icon-box bg-light-secondary text-secondary shadow-sm">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <p class="stat-label mb-1">Peserta Lulus</p>
                    <div class="stat-value mb-2"><?= $peserta_lulus; ?></div>
                    <div class="small border-top pt-2 text-muted">
                        <i class="fas fa-medal"></i> Total Lulus Sertifikasi
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>
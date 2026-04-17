<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid">


    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card card-flush shadow-sm">
                <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                            <h3 class="fw-bold text-gray-800">
                                <i class="ki-duotone ki-chart-line-star fs-2 text-primary me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                Statistik & Analitik
                            </h3>
                        </div>
                        <div class="d-flex align-items-center gap-2 gap-lg-3">
                            <span class="btn btn-sm btn-light-primary fw-bold">
                                <i class="ki-duotone ki-calendar-8 fs-2 me-2">
                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span>
                                </i>
                                <?= date('d M Y') ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body py-8 px-9">

                    <div class="row g-5 g-xl-10">

                        <div class="col-md-6 col-lg-4 col-xl-4 mb-md-5 mb-xl-10">
                            <div class="card card-flush shadow-sm h-md-100 border-top border-4 border-primary hover-elevate-up" style="transition: all 0.3s ease">
                                <div class="card-header pt-5">
                                    <div class="card-title d-flex flex-column">
                                        <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2"><?= count($siswa); ?></span>
                                        <span class="text-gray-500 pt-1 fw-semibold fs-6">Total Peserta</span>
                                    </div>
                                    <div class="card-toolbar">
                                        <div class="symbol symbol-55px">
                                            <div class="symbol-label" style="background: linear-gradient(135deg, #E1E9FF 0%, #C1D1FF 100%)">
                                                <i class="ki-duotone ki-profile-user fs-2tx text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-2 pb-4 d-flex flex-wrap align-items-center">
                                    <div class="d-flex flex-column w-100 mt-auto">
                                        <div class="d-flex justify-content-between fw-bold fs-6 text-gray-800 mb-3">
                                            <span>Rasio Aktif</span>
                                            <span><?= count($siswa) > 0 ? round((count($siswa_aktif) / count($siswa)) * 100) : 0 ?>%</span>
                                        </div>
                                        <div class="h-8px mx-3 w-100 bg-light-primary rounded">
                                            <div class="bg-primary rounded h-8px" role="progressbar" style="width: <?= count($siswa) > 0 ? (count($siswa_aktif) / count($siswa)) * 100 : 0 ?>%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="d-flex flex-stack mt-4">
                                            <span class="badge badge-light-success fs-7 fw-bold">Active: <?= count($siswa_aktif); ?></span>
                                            <span class="badge badge-light-danger fs-7 fw-bold">Off: <?= count($siswa_tidak_aktif); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4 col-xl-4 mb-md-5 mb-xl-10">
                            <div class="card card-flush shadow-sm h-md-100 border-top border-4 border-info hover-elevate-up" style="transition: all 0.3s ease">
                                <div class="card-header pt-5">
                                    <div class="card-title d-flex flex-column">
                                        <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2"><?= count($guru); ?></span>
                                        <span class="text-gray-500 pt-1 fw-semibold fs-6">Total Pengajar</span>
                                    </div>
                                    <div class="card-toolbar">
                                        <div class="symbol symbol-55px">
                                            <div class="symbol-label" style="background: linear-gradient(135deg, #DFFFEA 0%, #AFFFD0 100%)">
                                                <i class="ki-duotone ki-teacher fs-2tx text-info"><span class="path1"></span><span class="path2"></span></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-2 pb-4">
                                    <div class="separator separator-dashed border-gray-300 mb-5"></div>
                                    <div class="d-flex flex-stack">
                                        <div class="d-flex align-items-center me-5">
                                            <div class="symbol symbol-30px me-3">
                                                <span class="symbol-label bg-light-success">
                                                    <i class="ki-duotone ki-check text-success fs-3"></i>
                                                </span>
                                            </div>
                                            <div class="me-5">
                                                <span class="text-gray-800 fw-bold fs-6">Aktif</span>
                                                <span class="text-gray-400 fw-semibold d-block fs-7"><?= count($guru_aktif); ?> Orang</span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-30px me-3">
                                                <span class="symbol-label bg-light-danger">
                                                    <i class="ki-duotone ki-cross text-danger fs-3"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-gray-800 fw-bold fs-6">Off</span>
                                                <span class="text-gray-400 fw-semibold d-block fs-7"><?= count($guru_tidak_aktif); ?> Orang</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4 col-xl-4 mb-md-5 mb-xl-10">
                            <div class="card card-flush shadow-sm h-md-100 border-top border-4 border-danger hover-elevate-up" style="transition: all 0.3s ease; background: linear-gradient(180deg, #FFFFFF 0%, #FFF9F9 100%)">
                                <div class="card-header pt-5">
                                    <div class="card-title d-flex flex-column">
                                        <?php
                                        $total = 0;
                                        $total_transaksi = 0;
                                        foreach ($transaksi as $row) {
                                            $total_transaksi++;
                                            $diskon = ($row->nominal * $row->diskon) / 100;
                                            $totalDiskon = $row->nominal - $diskon;
                                            $diskon_voucher = ($totalDiskon * $row->voucher) / 100;
                                            $total += ($row->nominal - $diskon - $diskon_voucher);
                                        }
                                        ?>
                                        <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">Rp <?= number_format($total, 0, ',', '.'); ?></span>
                                        <span class="text-gray-500 pt-1 fw-semibold fs-6">Total Pendapatan</span>
                                    </div>
                                    <div class="card-toolbar">
                                        <div class="symbol symbol-55px">
                                            <div class="symbol-label" style="background: linear-gradient(135deg, #FFE2E5 0%, #FFB6C1 100%)">
                                                <i class="ki-duotone ki-bill fs-2tx text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-2 pb-4">
                                    <div class="d-flex flex-stack bg-light-danger rounded p-4">
                                        <span class="fw-bold text-danger fs-6">Volume Transaksi:</span>
                                        <span class="badge badge-danger fs-6 fw-bolder"><?= $total_transaksi ?> Invoice</span>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <span class="text-muted fs-8 fw-semibold italic">*Data akumulasi transaksi berhasil</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4 col-xl-4 mb-md-5 mb-xl-10">
                            <div class="card card-flush h-md-100 hover-elevate-up position-relative overflow-hidden" style="background-color: #1B283F">
                                <div class="position-absolute top-0 end-0 opacity-10" style="margin-top: -20px; margin-right: -20px">
                                    <i class="ki-duotone ki-award text-white" style="font-size: 150px"></i>
                                </div>

                                <div class="card-header pt-5 position-relative">
                                    <div class="card-title d-flex flex-column">
                                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2"><?= isset($peserta_lulus) ? $peserta_lulus : 0; ?></span>
                                        <span class="text-gray-500 pt-1 fw-semibold fs-6">Siswa Lulus</span>
                                    </div>
                                    <div class="card-toolbar">
                                        <span class="badge badge-success fw-bolder fs-8 px-3 py-2">Sertifikasi</span>
                                    </div>
                                </div>
                                <div class="card-body pt-2 pb-4 d-flex flex-wrap align-items-center position-relative">
                                    <div class="d-flex flex-column w-100 mt-auto">
                                        <div class="separator separator-dashed border-white opacity-25 mb-4"></div>
                                        <div class="d-flex align-items-center text-white">
                                            <i class="ki-duotone ki-medal-star fs-1 text-warning me-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                            <span class="fw-bold fs-6">Pencapaian Kompetensi Terbaik</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4 col-xl-4 mb-md-5 mb-xl-10">
                            <div class="card card-flush shadow-sm h-md-100 hover-elevate-up border-bottom border-4 border-success">
                                <div class="card-header pt-5">
                                    <div class="card-title d-flex flex-column">
                                        <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2"><?= count($mapel); ?></span>
                                        <span class="text-gray-500 pt-1 fw-semibold fs-6">Mata Pelajaran</span>
                                    </div>
                                    <div class="card-toolbar">
                                        <div class="symbol symbol-55px">
                                            <div class="symbol-label" style="background: linear-gradient(135deg, #C9F7F5 0%, #89ECE5 100%)">
                                                <i class="ki-duotone ki-book-open fs-2tx text-success"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-2 pb-4">
                                    <div class="d-flex align-items-center bg-light-success rounded p-4">
                                        <i class="ki-duotone ki-tag fs-2x text-success me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                        <span class="fw-bold text-gray-800 fs-6">Kurikulum Aktif Terdistribusi</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4 col-xl-4 mb-md-5 mb-xl-10">
                            <div class="card card-flush shadow-sm h-md-100 hover-elevate-up border-bottom border-4 border-warning">
                                <div class="card-header pt-5">
                                    <div class="card-title d-flex flex-column">
                                        <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2"><?= count($mitra); ?></span>
                                        <span class="text-gray-500 pt-1 fw-semibold fs-6">Jumlah Mitra</span>
                                    </div>
                                    <div class="card-toolbar">
                                        <div class="symbol symbol-55px">
                                            <div class="symbol-label" style="background: linear-gradient(135deg, #FFF4DE 0%, #FFD9AF 100%)">
                                                <i class="ki-duotone ki-user fs-2tx text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-2 pb-4">
                                    <div class="d-flex align-items-center bg-light-warning rounded p-4">
                                        <i class="ki-duotone ki-bank fs-2x text-warning me-4"><span class="path1"></span><span class="path2"></span></i>
                                        <span class="fw-bold text-gray-800 fs-6">Instansi / Strategic Partner</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
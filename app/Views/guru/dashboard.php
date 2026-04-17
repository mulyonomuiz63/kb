<?= $this->extend('template/app'); ?>

<?= $this->section('styles'); ?>
<style>
    /* Custom overlay untuk banner jika ingin gradasi spesifik */
    .banner-gradient {
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        
        <div class="card mb-5 mb-xl-10 shadow-sm">
            <div class="card-body pt-9 pb-0">
                <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="text-gray-900 text-hover-primary fs-2 fw-bolder me-1">Ringkasan Distribusi</span>
                                    <span>
                                        <i class="ki-duotone ki-verify fs-1 text-primary">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                    </span>
                                </div>
                                <div class="d-flex flex-wrap fw-bold fs-6 mb-4 pe-2">
                                    <span class="d-flex align-items-center text-gray-500 me-5 mb-2">
                                    <i class="ki-duotone ki-address-book fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> 
                                    Data Akademik Guru
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex overflow-auto h-55px">
                    <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder flex-nowrap">
                        <li class="nav-item">
                            <a class="nav-link text-active-primary me-6 active" href="#">Overview</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row g-5 g-xl-10">
            
            <div class="col-md-6">
                <div class="card card-flush h-md-100 shadow-sm">
                    <div class="card-header pt-7">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder text-dark">
                                <i class="ki-duotone ki-people fs-2 text-primary me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                Distribusi Kelas
                            </span>
                            <span class="text-gray-400 mt-1 fw-bold fs-7">Daftar kelas yang diajar</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($guru_kelas)) : ?>
                            <?php foreach ($guru_kelas as $gk) : ?>
                                <div class="d-flex align-items-center mb-7 bg-light-neutral p-4 rounded border border-dashed">
                                    <div class="symbol symbol-50px me-5">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="ki-duotone ki-element-11 fs-2x text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="text-gray-800 fw-bolder fs-6"><?= $gk->nama_kelas; ?></span>
                                    </div>
                                    <span class="badge badge-light-primary fw-bolder">Kelas</span>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="text-center py-10">
                                <i class="ki-duotone ki-information-5 fs-3x text-gray-300 mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <p class="text-gray-400 font-italic">Belum terdaftar di kelas manapun.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-flush h-md-100 shadow-sm">
                    <div class="card-header pt-7">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder text-dark">
                                <i class="ki-duotone ki-book-open fs-2 text-info me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                Mata Pelajaran
                            </span>
                            <span class="text-gray-400 mt-1 fw-bold fs-7">Bidang studi yang diampu</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($guru_mapel)) : ?>
                            <?php foreach ($guru_mapel as $gm) : ?>
                                <div class="d-flex align-items-center mb-7 bg-light-neutral p-4 rounded border border-dashed">
                                    <div class="symbol symbol-50px me-5">
                                        <span class="symbol-label bg-light-info">
                                            <i class="ki-duotone ki-book fs-2x text-info"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 text-gray-800 fw-bolder fs-6">
                                        <?= $gm->nama_mapel; ?>
                                    </div>
                                    <div class="symbol symbol-circle symbol-15px">
                                        <div class="bg-info h-15px w-15px rounded-circle"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="text-center py-10">
                                <i class="ki-duotone ki-information-5 fs-3x text-gray-300 mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <p class="text-gray-400 font-italic">Mata pelajaran belum ditentukan.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection(); ?>
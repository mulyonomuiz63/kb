<?= $this->extend('landing/template'); ?>
<?= $this->section('content'); ?>
<div class="section section-padding-02 mb-4" id="">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <!-- Section Title Start -->
                <div class="row">
                    <div class="section-title shape-02">
                        <h2 class="main-title">Tentang <span>Kelas Brevet</span></h2>
                    </div>
                    <div>
                        <span>Kelas Brevet merupakan platform pelatihan Brevet Pajak AB yang Terdaftar Resmi. Diselenggarakan oleh Akuntanmu Learning Center By Legalyn Konsultan Indonesia (Lembaga Pelatihan, Kursus/Bimbel, yang didirikan sejak tahun 2021). Sebagai upaya merespon kebutuhan peningkatan kompetensi profesi perpajakan di Indonesia, Akuntanmu Learning Center menghadirkan pembelajaran dan ujian Brevet Pajak AB secara online melalui KelasBrevet.com</span>
                    </div>
                    <div class="mt-4 mb-2">
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <a href="<?= base_url('legalitas#lkp') ?>" class="badge text-bg-light p-2 rounded-pill text-primary btn-hover-dark text-izin text-wrap w-100 text-center d-block">Izin Operasional LKP</a>
                            </div>
                            <div class="col-12 col-md-4">
                                <a href="<?= base_url('legalitas#lpk') ?>" class="badge text-bg-light p-2 rounded-pill text-primary btn-hover-dark text-izin text-wrap w-100 text-center d-block">Sertifikat Standar LPK</a>
                            </div>
                            <div class="col-12 col-md-4">
                                <a href="<?= base_url('legalitas#kemnaker') ?>" class="badge text-bg-light p-2 rounded-pill text-primary btn-hover-dark text-izin text-wrap w-100 text-center d-block">Publikasi Kemnaker</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Section Title End -->
            </div>
            <div class="col-md-6 mt-4 mt-md-0">
                <div class="card p-4 border-0">
                    <?= img_lazy('assets-landing/images/slider/slider-2.png', "loading", ['class' => 'card-img-top']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
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
                    <div class="mt-2 mb-2">
                        <div class="row">
                            <div class="col-4 col-md-4">
                                <a href="javascript:void(0)" class="badge text-bg-light p-2 rounded-pill text-primary btn-hover-dark text-izin" data-bs-toggle="modal" data-bs-target="#lihatIzinLkp">Izin Operasional LKP</a>
                            </div>
                            <div class="col-4 col-md-4">
                                <a href="javascript:void(0)" class="badge text-bg-light p-2 rounded-pill text-primary btn-hover-dark text-izin" data-bs-toggle="modal" data-bs-target="#lihatIzinLpk">Sertifikat Standar LPK</a>
                            </div>
                            <div class="col-4 col-md-4">
                                <a href="javascript:void(0)" class="badge text-bg-light p-2  rounded-pill text-primary btn-hover-dark text-izin" data-bs-toggle="modal" data-bs-target="#lihatKemnaker">Publikasi Kemnaker</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Section Title End -->

            </div>
            <div class="col-md-6">
                <div class="card p-4 border-0">
                    <?= img_lazy('assets-landing/images/slider/slider-2.png',"loading", ['class' => 'card-img-top']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
 <!-- untuk tampilan surat izin LKP-->
<div class="modal fade" id="lihatIzinLkp" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
              <iframe src="https://docs.google.com/gview?embedded=true&url=<?= urlencode(base_url("assets-landing/images/surat-izin/izin-LKP-akuntanmu-01.pdf")) ?>" width="100%" height="500vh"></iframe>
        </div>
    </div>
</div>

<!--surat izin LPK-->
<div class="modal fade" id="lihatIzinLpk" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
              <iframe src="https://docs.google.com/gview?embedded=true&url=<?= urlencode(base_url("assets-landing/images/surat-izin/izin-LPK-akuntanmu-01.pdf")) ?>" width="100%" height="500vh"></iframe>
        </div>
    </div>
</div>

<!--kemnaker-->
<div class="modal fade" id="lihatKemnaker" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
              <iframe src="https://skillhub.kemnaker.go.id/mitra/temukan-mitra/lpk-akuntanmu-by-legalyn-konsultan-indonesia-d8c65b1d-90e6-43a3-b1de-96cb4af8c662" width="100%" height="500vh"></iframe>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
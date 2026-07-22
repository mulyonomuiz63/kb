<?= $this->extend('landing/template'); ?>
<?= $this->section('content'); ?>

<!-- Custom CSS Tambahan untuk Animasi & Styling Halus -->
<style>
    .legalitas-nav .nav-link {
        color: #6c757d;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        font-size: 0.9rem;
        /* Mengecilkan sedikit ukuran font tab */
    }

    .legalitas-nav .nav-link:hover {
        color: #0d6efd;
        background-color: #f8f9fa;
    }

    .legalitas-nav .nav-link.active {
        background-color: #0d6efd;
        color: #fff;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
    }

    .doc-container {
        background: #fff;
        border: 1px solid #eaedf1;
    }
</style>

<div class="section section-padding-02 py-5 bg-light" id="legalitas-section">
    <div class="container">

        <!-- Header Section -->
        <div class="row mb-5 justify-content-center">
            <div class="col-12 col-lg-8 text-center">

                <!-- Badge Modern (Diperbaiki agar tidak numpuk/kebesaran) -->
                <div class="d-inline-flex align-items-center bg-primary text-white rounded-pill px-3 py-1 mb-3 fw-bold" style="font-size: 0.85rem;">
                    <i class="fas fa-shield-alt me-2"></i>Terdaftar & Terverifikasi
                </div>

                <h2 class="main-title fw-bolder mb-4">Legalitas <span class="text-primary">Kelas Brevet</span></h2>

                <p class="text-muted fs-6 lh-lg">
                    Kelas Brevet merupakan platform pelatihan Brevet Pajak AB yang Terdaftar Resmi. Diselenggarakan oleh <strong>Akuntanmu Learning Center By Legalyn Konsultan Indonesia</strong> (Lembaga Pelatihan, Kursus/Bimbel, yang didirikan sejak tahun 2021). Kami hadir merespon kebutuhan peningkatan kompetensi profesi perpajakan di Indonesia.
                </p>
            </div>
        </div>

        <!-- Document View Section (Tabs) -->
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">

                <!-- Nav Pills (Diperkecil padding-nya) -->
                <div class="bg-white p-1 rounded-pill shadow-sm border mb-4">
                    <ul class="nav nav-pills nav-fill legalitas-nav gap-1" id="legalitas-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill py-2" id="lkp-tab" data-bs-toggle="pill" data-bs-target="#lkp" type="button" role="tab" aria-controls="lkp" aria-selected="true">
                                <i class="fas fa-file-signature me-1"></i> Izin Operasional LKP
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill py-2" id="lpk-tab" data-bs-toggle="pill" data-bs-target="#lpk" type="button" role="tab" aria-controls="lpk" aria-selected="false">
                                <i class="fas fa-certificate me-1"></i> Sertifikat Standar LPK
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill py-2" id="kemnaker-tab" data-bs-toggle="pill" data-bs-target="#kemnaker" type="button" role="tab" aria-controls="kemnaker" aria-selected="false">
                                <i class="fas fa-building me-1"></i> Publikasi Kemnaker
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content -->
                <div class="tab-content doc-container p-3 p-md-4 rounded-4 shadow-sm" id="legalitas-tabContent">

                    <!-- Tab: LKP -->
                    <div class="tab-pane fade show active" id="lkp" role="tabpanel" aria-labelledby="lkp-tab" tabindex="0">
                        <!-- Area Dokumen LKP -->
                        <div class="ratio ratio-4x3 bg-secondary bg-opacity-10 rounded-3 overflow-hidden" style="min-height: 600px;">
                            <object data="<?= base_url('assets-landing/images/surat-izin/izin-LKP-akuntanmu-01.pdf') ?>" type="application/pdf" class="border-0 w-100 h-100">
                                <!-- Pesan Ini Hanya Muncul di Smartphone / Instagram -->
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-center p-4 bg-white">
                                    <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                    <p class="mb-3 text-muted fw-bold">Browser Instagram tidak mendukung pratinjau PDF di layar ini.</p>
                                    <a href="<?= base_url('assets-landing/images/surat-izin/izin-LKP-akuntanmu-01.pdf') ?>" target="_blank" class="btn btn-primary btn-sm rounded-pill px-4">
                                        <i class="fas fa-external-link-alt me-2"></i>Buka Dokumen LKP
                                    </a>
                                </div>
                            </object>
                        </div>
                    </div>

                    <!-- Tab: LPK -->
                    <div class="tab-pane fade" id="lpk" role="tabpanel" aria-labelledby="lpk-tab" tabindex="0">
                        <!-- Area Dokumen LPK -->
                        <div class="ratio ratio-4x3 bg-secondary bg-opacity-10 rounded-3 overflow-hidden" style="min-height: 600px;">
                            <object data="<?= base_url('assets-landing/images/surat-izin/izin-LPK-akuntanmu-01.pdf') ?>" type="application/pdf" class="border-0 w-100 h-100">
                                <!-- Pesan Ini Hanya Muncul di Smartphone / Instagram -->
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-center p-4 bg-white">
                                    <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                    <p class="mb-3 text-muted fw-bold">Browser Instagram tidak mendukung pratinjau PDF di layar ini.</p>
                                    <a href="<?= base_url('assets-landing/images/surat-izin/izin-LPK-akuntanmu-01.pdf') ?>" target="_blank" class="btn btn-primary btn-sm rounded-pill px-4">
                                        <i class="fas fa-external-link-alt me-2"></i>Buka Dokumen LPK
                                    </a>
                                </div>
                            </object>
                        </div>
                    </div>

                    <!-- Tab: Kemnaker -->
                    <div class="tab-pane fade" id="kemnaker" role="tabpanel" aria-labelledby="kemnaker-tab" tabindex="0">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 p-2 px-3 bg-light rounded-3 border border-light-subtle">
                            <div class="text-muted fs-7 mb-2 mb-md-0">
                                <i class="fas fa-info-circle text-primary me-1"></i> Verifikasi status kemitraan di portal resmi Kemnaker.
                            </div>
                            <a href="https://skillhub.kemnaker.go.id/mitra/temukan-mitra/lpk-akuntanmu-by-legalyn-konsultan-indonesia-d8c65b1d-90e6-43a3-b1de-96cb4af8c662" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm">
                                <i class="fas fa-external-link-alt me-1"></i>Buka Portal Kemnaker
                            </a>
                        </div>
                        <div class="ratio ratio-4x3 bg-secondary bg-opacity-10 rounded-3 overflow-hidden" style="min-height: 600px;">
                            <iframe src="https://skillhub.kemnaker.go.id/mitra/temukan-mitra/lpk-akuntanmu-by-legalyn-konsultan-indonesia-d8c65b1d-90e6-43a3-b1de-96cb4af8c662" class="border-0" allowfullscreen></iframe>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let hash = window.location.hash;
        if (hash) {
            let targetTab = document.querySelector('button[data-bs-target="' + hash + '"]');
            if (targetTab) {
                let tab = new bootstrap.Tab(targetTab);
                tab.show();

                setTimeout(() => {
                    document.getElementById('legalitas-section').scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 300);
            }
        }
    });
</script>
<?= $this->endSection(); ?>
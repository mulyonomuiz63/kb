<?= $this->extend('landing/template'); ?>
<?= $this->section('content'); ?>

<!-- Custom CSS Tambahan untuk Animasi, Styling Halus & Responsivitas -->
<style>
    /* Styling Nav Tabs */
    .legalitas-nav {
        flex-wrap: nowrap;
        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    .legalitas-nav::-webkit-scrollbar {
        display: none;
    }

    .legalitas-nav .nav-item {
        flex: 0 0 auto;
    }

    .legalitas-nav .nav-link {
        color: #6c757d;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
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

    /* Responsivitas Area Dokumen/Iframe */
    .doc-viewer {
        min-height: 400px;
        max-height: 900px;
        overflow-y: auto;
        scroll-behavior: smooth;
        position: relative;
    }

    /* Memastikan canvas PDF responsif penuh di mobile */
    .pdf-canvas {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 0 auto 15px auto;
    }

    /* Styling indikator loading agar di tengah */
    .loading-indicator {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
    }

    @media (max-width: 768px) {
        .main-title {
            font-size: 1.75rem;
        }

        .legalitas-nav .nav-link {
            font-size: 0.85rem;
        }

        .doc-viewer {
            height: 60vh;
            min-height: 400px;
        }
    }
</style>

<div class="section section-padding-02 py-5 bg-light" id="legalitas-section">
    <div class="container">

        <!-- Header Section -->
        <div class="row mb-4 mb-md-5 justify-content-center">
            <div class="d-none d-md-block text-center">
                <div class="d-inline-flex align-items-center bg-primary text-white rounded-pill px-3 py-1 mb-3 fw-bold" style="font-size: 0.85rem;">
                    <i class="fas fa-shield-alt me-2"></i>Terdaftar & Terverifikasi
                </div>

                <h2 class="main-title fw-bolder mb-3 mb-md-4">Legalitas <span class="text-primary">Kelas Brevet</span></h2>

                <p class="text-muted fs-6 lh-lg px-2 px-md-0">
                    Kelas Brevet merupakan platform pelatihan Brevet Pajak AB yang Terdaftar Resmi. Diselenggarakan oleh <strong>Akuntanmu Learning Center By Legalyn Konsultan Indonesia</strong> (Lembaga Pelatihan, Kursus/Bimbel, yang didirikan sejak tahun 2021). Kami hadir merespon kebutuhan peningkatan kompetensi profesi perpajakan di Indonesia.
                </p>
            </div>
        </div>

        <!-- Document View Section (Tabs) -->
        <div class="row justify-content-center">
            <div class="col-12 col-xl-12">

                <!-- Nav Pills -->
                <div class="bg-white p-1 rounded-pill shadow-sm border mb-4">
                    <ul class="nav nav-pills legalitas-nav gap-1" id="legalitas-tab" role="tablist">
                        <li class="nav-item flex-sm-fill text-center" role="presentation">
                            <button class="nav-link w-100 active rounded-pill py-2" id="lkp-tab" data-bs-toggle="pill" data-bs-target="#lkp" type="button" role="tab" aria-controls="lkp" aria-selected="true">
                                <i class="fas fa-file-signature me-1"></i> Izin Operasional LKP
                            </button>
                        </li>
                        <li class="nav-item flex-sm-fill text-center" role="presentation">
                            <button class="nav-link w-100 rounded-pill py-2" id="lpk-tab" data-bs-toggle="pill" data-bs-target="#lpk" type="button" role="tab" aria-controls="lpk" aria-selected="false">
                                <i class="fas fa-certificate me-1"></i> Sertifikat Standar LPK
                            </button>
                        </li>
                        <li class="nav-item flex-sm-fill text-center" role="presentation">
                            <button class="nav-link w-100 rounded-pill py-2" id="kemnaker-tab" data-bs-toggle="pill" data-bs-target="#kemnaker" type="button" role="tab" aria-controls="kemnaker" aria-selected="false">
                                <i class="fas fa-building me-1"></i> Publikasi Kemnaker
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content -->
                <div class="tab-content doc-container p-3 p-md-4 rounded-4 shadow-sm" id="">

                    <!-- Tab: LKP -->
                    <div class="tab-pane fade show active" id="lkp" role="tabpanel" aria-labelledby="lkp-tab" tabindex="0">
                        <div class="doc-viewer bg-secondary bg-opacity-10 rounded-3 p-2 p-md-3">
                            <!-- Indikator Loading LKP -->
                            <div class="loading-indicator text-center" id="loading-lkp">
                                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-3 text-muted fw-bold">Sedang memuat dokumen...</p>
                            </div>

                            <!-- Container tempat halaman PDF LKP akan dirender -->
                            <div id="pdf-container-lkp" class="d-flex flex-column align-items-center w-100"></div>
                        </div>
                    </div>

                    <!-- Tab: LPK -->
                    <div class="tab-pane fade" id="lpk" role="tabpanel" aria-labelledby="lpk-tab" tabindex="0">
                        <div class="doc-viewer bg-secondary bg-opacity-10 rounded-3 p-2 p-md-3">
                            <!-- Indikator Loading LPK -->
                            <div class="loading-indicator text-center" id="loading-lpk">
                                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-3 text-muted fw-bold">Sedang memuat dokumen...</p>
                            </div>

                            <!-- Container tempat halaman PDF LPK akan dirender -->
                            <div id="pdf-container-lpk" class="d-flex flex-column align-items-center w-100"></div>
                        </div>
                    </div>

                    <!-- Tab: Kemnaker (Iframe) -->
                    <div class="tab-pane fade" id="kemnaker" role="tabpanel" aria-labelledby="kemnaker-tab" tabindex="0">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 p-2 px-3 bg-light rounded-3 border border-light-subtle">
                            <div class="text-muted fs-7 mb-2 mb-md-0 text-center text-md-start">
                                <i class="fas fa-info-circle text-primary me-1"></i> Verifikasi status kemitraan di portal resmi Kemnaker.
                            </div>
                            <a href="https://skillhub.kemnaker.go.id/mitra/temukan-mitra/lpk-akuntanmu-by-legalyn-konsultan-indonesia-d8c65b1d-90e6-43a3-b1de-96cb4af8c662" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm">
                                <i class="fas fa-external-link-alt me-1"></i>Buka Portal
                            </a>
                        </div>
                        <div class="doc-viewer bg-secondary bg-opacity-10 rounded-3 overflow-hidden">
                            <iframe src="https://skillhub.kemnaker.go.id/mitra/temukan-mitra/lpk-akuntanmu-by-legalyn-konsultan-indonesia-d8c65b1d-90e6-43a3-b1de-96cb4af8c662" class="border-0 w-100 h-100" sandbox="allow-scripts allow-same-origin allow-popups allow-forms" allowfullscreen></iframe>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<!-- Load library PDF.js dari CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // Logika untuk mempertahankan Tab aktif setelah refresh
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

        // Konfigurasi PDF.js Worker
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

        // Fungsi Render SEMUA Halaman PDF dengan Loading UI
        function renderFullPDF(url, containerId, loadingId) {
            const container = document.getElementById(containerId);
            const loadingElement = document.getElementById(loadingId);

            // Mulai memuat dokumen
            const loadingTask = pdfjsLib.getDocument(url);

            loadingTask.promise.then(function(pdf) {
                const totalPages = pdf.numPages;

                // Menghapus elemen loading begitu file PDF berhasil ditarik dan siap dirender
                if (loadingElement) {
                    loadingElement.style.display = 'none';
                }

                // Looping untuk merender dari halaman 1 sampai halaman terakhir
                for (let pageNum = 1; pageNum <= totalPages; pageNum++) {
                    pdf.getPage(pageNum).then(function(page) {
                        const scale = 1.5;
                        const viewport = page.getViewport({
                            scale: scale
                        });

                        const canvas = document.createElement('canvas');
                        canvas.className = 'pdf-canvas shadow-sm border bg-white';
                        const context = canvas.getContext('2d');

                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        container.appendChild(canvas);

                        const renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        page.render(renderContext);
                    });
                }
            }).catch(function(error) {
                console.error('Error saat merender PDF: ', error);
                // Ubah tampilan loading menjadi pesan error jika gagal
                if (loadingElement) {
                    loadingElement.innerHTML = '<i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i><p class="text-danger fw-bold">Gagal memuat dokumen. Pastikan koneksi internet stabil.</p>';
                }
            });
        }

        // Panggil fungsi render
        // Parameter: (URL_PDF, ID_Container, ID_Loading)
        const urlLKP = '<?= base_url('assets-landing/images/surat-izin/izin-LKP-akuntanmu-01.pdf') ?>';
        const urlLPK = '<?= base_url('assets-landing/images/surat-izin/izin-LPK-akuntanmu-01.pdf') ?>';

        renderFullPDF(urlLKP, 'pdf-container-lkp', 'loading-lkp');
        renderFullPDF(urlLPK, 'pdf-container-lpk', 'loading-lpk');
    });
</script>

<?= $this->endSection(); ?>
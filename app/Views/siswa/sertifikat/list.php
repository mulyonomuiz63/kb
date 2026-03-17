<?= $this->extend('siswa/template/app'); ?>

<?= $this->section('styles') ?>
<style>
    /* Custom Star Rating Animation */
    .star-rating {
        display: inline-flex;
        gap: 0.75rem;
        cursor: pointer;
        padding: 10px;
        background: f8f9fa;
        border-radius: 12px;
    }

    .star-rating .bi-star-fill {
        font-size: 2.5rem;
        color: #E4E6EF;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .star-rating .bi-star-fill.active {
        color: #FFAD0F;
        transform: scale(1.2);
        filter: drop-shadow(0 0 5px rgba(255, 173, 15, 0.4));
    }

    .star-rating .bi-star-fill.hover {
        color: #FFD60A;
        transform: scale(1.1);
    }

    /* Table Improvements */
    .table-responsive {
        min-height: 400px;
    }

    .btn-download-glow {
        box-shadow: 0 4px 15px rgba(0, 158, 247, 0.2);
        transition: all 0.3s ease;
    }

    .btn-download-glow:hover {
        box-shadow: 0 6px 20px rgba(0, 158, 247, 0.4);
        transform: translateY(-1px);
    }

    /* Iframe Loader */
    #loader_sertifikat {
        backdrop-filter: blur(4px);
        background: rgba(255, 255, 255, 0.8) !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content'); ?>
<?php $db = Config\Database::connect(); ?>

<div class="d-flex flex-column flex-column-fluid py-3 py-lg-6 mt-8">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card card-flush shadow-sm">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                <i class="bi bi-search fs-2"></i>
                            </span>
                            <input type="text" data-kt-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Cari materi ujian..." />
                        </div>
                    </div>
                    <div class="card-toolbar">
                        <?php if ($total != 0 && $totalSertifikat >= $total): ?>
                            <a href="javascript:void(0)"
                                data-bs-toggle="modal"
                                data-bs-target="#sertifikat_cetak_modal"
                                data-sertifikat_all="<?= base_url("sw-siswa/sertifikat/lihat-sertifikat-brevet/" . encrypt_url(session()->get('id'))) ?>"
                                class="btn btn-primary btn-download-glow fw-bold sertifikat_all_cetak">
                                <i class="bi bi-patch-check-fill fs-4 me-2"></i> Unduh Sertifikat Brevet AB (Lengkap)
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_datatable_sertifikat">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-250px">Materi Pelatihan</th>
                                    <th class="min-w-150px text-center">Periode Ujian</th>
                                    <th class="min-w-100px text-center">Skor Akhir</th>
                                    <th class="min-w-125px text-center">Status Kelulusan</th>
                                    <th class="text-end min-w-100px">Opsi Sertifikat</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                <?php foreach ($ujian as $u) : ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-50px me-3">
                                                    <div class="symbol-label bg-light-primary">
                                                        <i class="bi bi-award text-primary fs-1"></i>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="text-gray-800 text-hover-primary fw-bold fs-6 mb-1"><?= $u->nama_ujian; ?></span>
                                                    <span class="fs-7 text-muted fw-normal">Kode: <?= $u->kode_ujian ?></span>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <div class="d-flex flex-column align-items-center">
                                                <span class="text-gray-800 fw-bold fs-7"><?= date('d M Y', strtotime($u->start_ujian)) ?></span>
                                                <span class="text-muted fs-8">Sampai <?= date('d M Y', strtotime($u->end_ujian)) ?></span>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <div class="d-flex flex-column">
                                                <span class="text-dark fw-bolder fs-5"><?= $u->nilai ?></span>
                                                <div class="progress h-4px w-100 mt-1 bg-light">
                                                    <div class="progress-bar bg-<?= $u->nilai >= 60 ? 'success' : 'danger' ?>" role="progressbar" style="width: <?= $u->nilai ?>%"></div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <?php if ($u->nilai >= 60): ?>
                                                <span class="badge badge-light-success border border-success border-dashed px-4 py-3">
                                                    <i class="bi bi-check2-circle me-1 text-success"></i> Kompeten
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-light-danger border border-danger border-dashed px-4 py-3">
                                                    <i class="bi bi-exclamation-triangle me-1 text-danger"></i> Tidak Lulus
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-end">
                                            <?php
                                            $idsiswa = session()->get('id');
                                            $ratingData = $db->query("SELECT AVG(rating) as avg_rating FROM review_ujian WHERE kode_ujian = '$u->kode_ujian' and id_siswa= '$idsiswa'")->getRow();
                                            ?>

                                            <?php if (empty($ratingData->avg_rating)): ?>
                                                <button class="btn btn-sm btn-light-warning btn-active-warning w-100 justify-content-center"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#ratingModal"
                                                    data-id="<?= $u->kode_ujian; ?>"
                                                    data-nama="<?= htmlspecialchars($u->nama_ujian); ?>">
                                                    <i class="bi bi-star-fill fs-7 me-2"></i> Beri Feedback
                                                </button>
                                            <?php else: ?>
                                                <?php if ($u->nilai >= 60): ?>
                                                    <button class="btn btn-icon btn-sm btn-light-primary btn-active-primary shadow-sm sertifikat_cetak"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#sertifikat_cetak_modal"
                                                        data-bs-toggle="tooltip" title="Lihat & Unduh Sertifikat"
                                                        data-sertifikat="<?= base_url("sw-siswa/sertifikat/lihat-sertifikat/" . encrypt_url($u->kode_ujian) . "/" . encrypt_url($u->id_ujian)) ?>">
                                                        <i class="bi bi-file-earmark-pdf fs-3"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary py-3 px-4 disabled">🔒 Terkunci</span>
                                                <?php endif; ?>
                                            <?php endif; ?>
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

<div class="modal fade" id="ratingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-500px">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header pb-0 border-0 justify-content-end text-end">
                <button class="btn btn-icon btn-sm btn-active-light-primary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg fs-4"></i>
                </button>
            </div>

            <form action="<?= base_url('sw-siswa/simpan-review'); ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body px-10 px-lg-15 pt-0 pb-15">
                    <div class="text-center mb-10">
                        <i class="bi bi-chat-quote fs-3x text-warning mb-5 d-block"></i>
                        <h2 class="fw-bold text-dark mb-3" id="ratingModalLabel">Feedback Materi</h2>
                        <p class="text-muted fw-semibold">Silakan berikan penilaian Anda terhadap materi ujian ini untuk mengakses sertifikat.</p>
                    </div>

                    <input type="hidden" name="kode_ujian" id="kode_ujian">
                    <input type="hidden" name="id_siswa" id="idsiswa">
                    <input type="hidden" name="link" value="sw-siswa/sertifikat">

                    <div class="fv-row mb-10 text-center">
                        <div class="star-rating mb-3">
                            <i class="bi bi-star-fill" data-value="1"></i>
                            <i class="bi bi-star-fill" data-value="2"></i>
                            <i class="bi bi-star-fill" data-value="3"></i>
                            <i class="bi bi-star-fill" data-value="4"></i>
                            <i class="bi bi-star-fill" data-value="5"></i>
                        </div>
                        <input type="hidden" name="rating" id="ratingValue" required>
                        <div class="fs-7 fw-bold text-warning" id="ratingDesc">Pilih Bintang</div>
                    </div>

                    <div class="fv-row mb-8 text-start">
                        <label class="fs-6 fw-semibold mb-2">Pesan atau Kesan (Opsional)</label>
                        <textarea class="form-control form-control-solid border-0" rows="4" name="komentar" placeholder="Contoh: Materi sangat relevan dengan kebutuhan industri..."></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary w-100 py-4 fs-5 fw-bold">
                            Kirim & Buka Akses Sertifikat
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="sertifikat_cetak_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen p-5 p-lg-10">
        <div class="modal-content rounded shadow-lg">
            <div class="modal-header py-3">
                <h3 class="modal-title fw-bold text-gray-800">
                    <i class="bi bi-file-earmark-pdf text-danger me-2"></i> Preview E-Sertifikat
                </h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg fs-4"></i>
                </div>
            </div>
            <div class="modal-body p-0 position-relative bg-light-dark">
                <div id="loader_sertifikat" class="d-flex flex-column justify-content-center align-items-center h-100 position-absolute w-100" style="z-index: 100; background: rgba(255, 255, 255, 0.9);">
                    <div class="spinner-border text-primary mb-3" role="status"></div>
                    <span class="text-gray-600 fw-bold">Menyiapkan Dokumen...</span>
                </div>

                <iframe id="iframe_sertifikat"
                    src=""
                    width="100%"
                    height="100%"
                    frameborder="0"
                    style="opacity: 0; transition: opacity 0.5s ease;">
                </iframe>
            </div>
            <div class="modal-footer py-3">
                <button type="button" class="btn btn-light-primary fw-bold" data-bs-dismiss="modal">Tutup Preview</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Initialize Tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        $('.sertifikat_cetak, .sertifikat_all_cetak').off('click').on('click', function() {
            const url = $(this).data('sertifikat') || $(this).data('sertifikat_all');
            if (!url) return;

            // 1. Persiapan Tampilan
            $('#sertifikat_cetak_modal').modal('show');
            $('#loader_sertifikat').show();
            $('#iframe_sertifikat').css('opacity', '0').attr('src', '');

            // 2. Ambil data PDF menggunakan Fetch
            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Gagal mengambil dokumen');
                    return response.blob(); // Ubah response menjadi Blob
                })
                .then(blob => {
                    // Buat URL lokal dari Blob tersebut
                    const blobUrl = URL.createObjectURL(blob);

                    // Masukkan ke iframe
                    $('#iframe_sertifikat').attr('src', blobUrl);

                    // Hilangkan loader segera
                    $('#loader_sertifikat').fadeOut(300, function() {
                        $('#iframe_sertifikat').css('opacity', '1');
                        $(this).attr('style', 'display: none !important');
                    });

                })
                .catch(error => {
                    $('#loader_sertifikat').hide();
                });
        });

        // Bersihkan memory saat modal ditutup
        $('#sertifikat_cetak_modal').on('hidden.bs.modal', function() {
            const iframe = $('#iframe_sertifikat');
            if (iframe.attr('src').startsWith('blob:')) {
                URL.revokeObjectURL(iframe.attr('src')); // Hapus ObjectURL dari RAM
            }
            iframe.attr('src', '');
        });


        // Rating Logic with Descriptions
        const starDescriptions = {
            1: "Sangat Kurang",
            2: "Kurang Baik",
            3: "Cukup Memuaskan",
            4: "Sangat Baik",
            5: "Luar Biasa!"
        };

        $('#ratingModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            $('#kode_ujian').val(button.data('id'));
            $('#idsiswa').val('<?= session()->get('id') ?>');
            $('#ratingModalLabel').text(button.data('nama'));

            // Reset
            $('.star-rating .bi-star-fill').removeClass('active hover');
            $('#ratingValue').val('');
            $('#ratingDesc').text('Pilih Bintang').removeClass('text-success').addClass('text-warning');
        });

        $('.star-rating .bi-star-fill').on('mouseenter', function() {
            const val = $(this).data('value');
            $('.star-rating .bi-star-fill').each(function() {
                $(this).toggleClass('hover', $(this).data('value') <= val);
            });
            $('#ratingDesc').text(starDescriptions[val]);
        }).on('mouseleave', function() {
            $('.star-rating .bi-star-fill').removeClass('hover');
            const currentVal = $('#ratingValue').val();
            $('#ratingDesc').text(currentVal ? starDescriptions[currentVal] : 'Pilih Bintang');
        });

        $('.star-rating .bi-star-fill').on('click', function() {
            const val = $(this).data('value');
            $('#ratingValue').val(val);
            $('.star-rating .bi-star-fill').each(function() {
                $(this).toggleClass('active', $(this).data('value') <= val);
            });
            $('#ratingDesc').text(starDescriptions[val]).addClass('text-success').removeClass('text-warning');
        });
    });
</script>
<?= $this->endSection(); ?>
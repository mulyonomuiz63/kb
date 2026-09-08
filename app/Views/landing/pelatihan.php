<?= $this->extend('landing/template'); ?>
<?= $this->section('css'); ?>
<style>
    /* ===== CARD ===== */
    .single-courses.card {
        border-radius: 16px;
        border: none;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 8px 22px rgba(0, 0, 0, .08);
        transition: all .35s ease;
    }

    .single-courses.card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 36px rgba(0, 0, 0, .15);
    }

    /* ===== FLASH SALE PILL TOP-LEFT ===== */
    .flash-sale-pill {
        background: linear-gradient(45deg, #ff0055, #ff5e00);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 5px 10px;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 12px rgba(255, 0, 85, 0.3);
        z-index: 10;
    }

    .flash-sale-timer-box {
        background: rgba(0, 0, 0, 0.25);
        color: #fff;
        padding: 2px 8px;
        border-radius: 20px;
        font-family: monospace;
        letter-spacing: 0.5px;
    }

    /* ===== IMAGE ===== */
    .courses-images {
        overflow: hidden;
    }

    .courses-images img {
        transition: transform .45s ease;
    }

    .single-courses:hover .courses-images img {
        transform: scale(1.08);
    }

    /* ===== TITLE ===== */
    .courses-content h4.title {
        font-size: 16px;
        font-weight: 600;
        line-height: 1.4;
        margin-bottom: 6px;
    }

    /* ===== PRICE ===== */
    .courses-meta .fw-bold {
        font-size: 17px;
        color: #29459A;
    }

    /* ===== AFFILIATE ===== */
    .affiliate-box {
        background: linear-gradient(135deg, #f8fbff, #eef4ff);
        border: 1px dashed #c9d9ff;
        border-radius: 10px;
        font-size: 12px;
        animation: fadeUp .5s ease;
    }

    /* ===== BUTTON ===== */
    .btn-buy {
        background: #29459A;
        color: #fff;
        border-radius: 10px;
        border: none;
        padding: 0px 15px;
        font-weight: 600;
        transition: .3s ease;
    }

    .btn-buy:hover {
        background: #d0011b;
        color: #fff;
        transform: scale(1.05);
    }

    .btn-buy-copy {
        background: #DCDCDC;
        color: #212121;
        border-radius: 10px;
        border: none;
        padding: 0px 15px;
        font-weight: 600;
        transition: .3s ease;
    }

    .btn-buy-copy:hover {
        background: #d0011b;
        color: #fff;
        transform: scale(1.05);
    }

    .btn-buy-wa {
        background: #90EE90;
        color: #fff;
        border-radius: 10px;
        border: none;
        padding: 0px 15px;
        font-weight: 600;
        transition: .3s ease;
    }

    .btn-buy-wa:hover {
        background: #d0011b;
        color: #fff;
        transform: scale(1.05);
    }

    /* ===== DISCOUNT BADGE ===== */
    .diskon {
        background: linear-gradient(45deg, #ff4d4d, #ff9800);
        font-size: 12px;
        border-radius: 0 0 0 14px;
        animation: pulse 1.5s infinite;
        z-index: 10;
    }

    /* ===== ENTRY ANIMATION ===== */
    .animate-card {
        opacity: 0;
        transform: translateY(20px);
        transition: all .6s ease;
    }

    .animate-card.show {
        opacity: 1;
        transform: translateY(0);
    }

    /* ===== KEYFRAMES ===== */
    @keyframes pulse {
        0% {
            opacity: 1
        }

        50% {
            opacity: .6
        }

        100% {
            opacity: 1
        }
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(8px)
        }

        to {
            opacity: 1;
            transform: translateY(0)
        }
    }

    /* ===== RESPONSIVE ===== */
    @media(max-width:576px) {
        .courses-content h4.title {
            font-size: 15px;
        }

        .btn-buy {
            padding: 10px;
            font-size: 14px;
        }
    }
</style>
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>
<div class="section call-to-action-wrapper pb-4 d-flex align-items-center mt-2" id="bimbel">
    <!-- All Courses tab content Start -->
    <div class="tab-content courses-tab-content">
        <div class="tab-pane fade show active" id="tabs1">

            <!-- All Courses Wrapper Start -->
            <div class="courses-wrapper">
                <h6>Penawaran Paket Brevet Pajak AB</h6>
                <span>Pilihan ujian yang bisa kamu ikuti secara online kapan saja dan dimana saja</span>
                <div class="row">
                    <?php foreach ($paket as $rows) : ?>
                        <?php
                        // untuk rating
                        $query = $db->table('paket')->join('detail_paket b', 'paket.idpaket=b.idpaket')->join('ujian_master c', 'b.id_ujian=c.id_ujian')->join('review_ujian d', 'c.kode_ujian=d.kode_ujian')->where('paket.slug', $rows->slug)->get()->getResultObject();

                        // hitung rata-rata rating
                        $totalRating = 0;
                        $jumlahReview = count($query);

                        foreach ($query as $item) {
                            $totalRating += $item->rating;
                        }

                        $rataRating = $jumlahReview > 0 ? round($totalRating / $jumlahReview, 1) : 0;
                        ?>
                        <div class="col-12 col-md-6 col-lg-4 mt-4">
                            <!-- Single Courses Start -->
                            <div class="single-courses card position-relative animate-card">
                                
                                <!-- Flash Sale Badge di Pojok Kiri Atas -->
                                <?php if (isset($rows->is_pinned) && $rows->is_pinned == 1): ?>
                                    <div class="position-absolute top-0 start-0 m-2 flash-sale-pill">
                                        <i class="fa fa-bolt"></i> FLASH SALE 
                                        <span class="flash-sale-timer-box flash-sale-countdown">00:00:00</span>
                                    </div>
                                <?php endif; ?>

                                <div class="courses-images">
                                    <a href="<?= base_url('bimbel/' . $rows->slug) ?>">
                                        <?= img_lazy('assets-landing/images/paket/thumbnails/' . $rows->file, $rows->nama_paket, ['class' => 'card-img-top']) ?>
                                    </a>
                                </div>
                                <div class="courses-content">
                                    <h4 class="title"><a href="<?= base_url('bimbel/' . $rows->slug) ?>"><?= $rows->nama_paket ?></a></h4>
                                    <div class="courses-meta">
                                        <?php
                                        $soal = $db->query("SELECT a.id_ujian, b.kode_ujian FROM detail_paket a join ujian_master b on a.id_ujian=b.id_ujian where a.idpaket = '$rows->idpaket' group by a.id_ujian")->getResult();
                                        $total = null;
                                        foreach ($soal as $r):
                                            $ujianDetail = $db->query("select * from ujian_detail where kode_ujian = '$r->kode_ujian'")->getResult();
                                            foreach ($ujianDetail as $dataRows) {
                                                $hasilUjian = soal_ujian(encrypt_url($r->kode_ujian));
                                                $total = count($hasilUjian);
                                            }
                                        endforeach;

                                        ?>
                                        <span class="fw-bold"> <i class="icofont-read-book"></i> <?= (!empty($total) ? $total : '0') ?> Soal/<span style="font-size:10px">Materi</span> </span>
                                        <div class="d-flex flex-column mb-3">
                                            <span class="fw-bold"> Rp <?= number_format($rows->nominal_paket - (($rows->nominal_paket * $rows->diskon) / 100)) ?> </span>
                                            <span style="font-size:12px" class="mt-1"> <del>Rp <?= number_format($rows->nominal_paket) ?></del> </span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="mb-2" style="font-size:12px">
                                            <?php if ($rataRating > 0): ?>
                                                <span class="text-dark"><?= $rataRating ?><span> <?= showStars($rataRating) ?> <span class="text-dark">(<?= $jumlahReview + 325 ?>)</span>
                                                <?php else: ?>
                                                    <span class="text-dark"><?= "4.9" ?><span> <?= showStars('4.9') ?> <span class="text-dark">(<?= '484' ?>)</span>
                                                        <?php endif; ?>
                                        </div>
                                        <!-- Affiliate -->
                                        <?php if (session()->get('id') && !empty($affiliate)): ?>
                                            <?php
                                            $potongan_diskon = ($rows->nominal_paket * $rows->diskon) / 100;
                                            $harga_final     = $rows->nominal_paket - $potongan_diskon;
                                            $est_komisi      = ($harga_final * $rows->komisi) / 100;
                                            ?>
                                            <div class="affiliate-box p-2 mb-3">
                                                <div class="d-flex flex-wrap align-items-center gap-1" style="font-size: 0.75rem;">
                                                    <span>💰</span>
                                                    <span class="text-muted fw-bold">Komisi</span>
                                                    <strong class="text-danger"><?= $rows->komisi ?>%</strong>
                                                    <span class="text-muted mx-1">|</span>
                                                    <span class="text-muted fw-bold">Est.</span>
                                                    <strong class="text-danger">Rp <?= number_format($est_komisi, 0, ',', '.') ?></strong>
                                                </div>
                                                <div class="text-muted mt-1" style="font-size: 0.75rem;">
                                                    Dari setiap pembelian via link kamu
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                    <!-- Accordion Detail Paket Start -->
                                    <div class="accordion accordion-flush mb-2" id="accordionDetail<?= $rows->idpaket ?>">
                                        <div class="accordion-item border rounded">
                                            <h2 class="accordion-header" id="heading<?= $rows->idpaket ?>">
                                                <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDetail<?= $rows->idpaket ?>" aria-expanded="false" aria-controls="collapseDetail<?= $rows->idpaket ?>" style="font-size: 0.8rem; background-color: transparent;">
                                                    <i class="icofont-info-circle me-1"></i> Detail Paket
                                                </button>
                                            </h2>
                                            <div id="collapseDetail<?= $rows->idpaket ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $rows->idpaket ?>" data-bs-parent="#accordionDetail<?= $rows->idpaket ?>">
                                                <div class="accordion-body p-2 custom-accordion-text">
                                                    <?= !empty($rows->deskripsi) ? $rows->deskripsi : (!empty($rows->detail_paket) ? $rows->detail_paket : 'Detail informasi paket pembelajaran.') ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <style>
                                        /* Styling khusus isi accordion agar rapi & modern */
                                        .custom-accordion-text {
                                            background-color: #f8f9fa;
                                            border-radius: 6px;
                                            max-height: 180px;
                                            overflow-y: auto;
                                        }

                                        .custom-accordion-text p {
                                            margin-bottom: 4px !important;
                                            font-size: 11px !important;
                                            line-height: 1.4 !important;
                                            letter-spacing: normal !important;
                                            /* Menghapus letter-spacing 0.4992px yang renggang */
                                            color: #4a5568 !important;
                                        }

                                        /* Styling khusus untuk judul header materi (paragraf pertama) */
                                        .custom-accordion-text p:first-child {
                                            font-size: 11.5px !important;
                                            font-weight: 700 !important;
                                            color: #1e293b !important;
                                            border-bottom: 1px dashed #cbd5e1;
                                            padding-bottom: 4px;
                                            margin-bottom: 6px !important;
                                        }
                                    </style>
                                    <!-- Accordion Detail Paket End -->

                                    <!-- Informasi Umum (Keunggulan Paket) -->
                                    <div class="info-umum-box p-3 rounded-3 shadow-sm mb-3 position-relative overflow-hidden bg-white border">
                                        <div class="box-accent" style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background-color: #0d6efd;"></div>
                                        <h6 class="fw-bolder mb-2" style="font-size: 12px; color: #0d6efd;">
                                            <i class="fa fa-star text-warning me-1"></i> Keunggulan Paket
                                        </h6>
                                        <div class="row g-1">
                                            <div class="col-12 col-md-6 mb-2">
                                                <div class="d-flex align-items-start gap-2">
                                                    <i class="fa fa-check-circle text-success mt-1 flex-shrink-0" style="font-size: 11px;"></i>
                                                    <span class="info-text text-dark" style="font-size: 11px; line-height: 1.3;">LKP Terdaftar Resmi</span>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6 mb-2">
                                                <div class="d-flex align-items-start gap-2">
                                                    <i class="fa fa-check-circle text-success mt-1 flex-shrink-0" style="font-size: 11px;"></i>
                                                    <span class="info-text text-dark" style="font-size: 11px; line-height: 1.3;">Sertifikat Brevet Diakui</span>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6 mb-2">
                                                <div class="d-flex align-items-start gap-2">
                                                    <i class="fa fa-check-circle text-success mt-1 flex-shrink-0" style="font-size: 11px;"></i>
                                                    <span class="info-text text-dark" style="font-size: 11px; line-height: 1.3;">Akses Belajar Selamanya</span>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6 mb-2">
                                                <div class="d-flex align-items-start gap-2">
                                                    <i class="fa fa-check-circle text-success mt-1 flex-shrink-0" style="font-size: 11px;"></i>
                                                    <span class="info-text text-dark" style="font-size: 11px; line-height: 1.3;">Materi Terus di Update</span>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6 mb-2">
                                                <div class="d-flex align-items-start gap-2">
                                                    <i class="fa fa-check-circle text-success mt-1 flex-shrink-0" style="font-size: 11px;"></i>
                                                    <span class="info-text text-dark" style="font-size: 11px; line-height: 1.3;">Tanpa Langganan Bulanan/Tahunan</span>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6 mb-2">
                                                <div class="d-flex align-items-start gap-2">
                                                    <i class="fa fa-check-circle text-success mt-1 flex-shrink-0" style="font-size: 11px;"></i>
                                                    <span class="info-text text-dark" style="font-size: 11px; line-height: 1.3;">Dilatih Oleh Konsultan Pajak dan ASN/EX-DJP</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Informasi Umum End -->

                                    <div class="d-flex gap-2 mt-3">
                                        <a href="<?= base_url('sw-siswa/transaksi/pesan/' . encrypt_url($rows->idpaket)) ?>" class="btn-buy btn-sm text-center flex-fill p-2">Pesan Sekarang</a>
                                        <?php if (session()->get('id')): ?>
                                            <?php if (!empty($affiliate)): ?>
                                                <button class="btn-buy-copy btn-sm  btn-copy-link" data-paket_id="<?= $rows->idpaket ?>">
                                                    <i class="fa fa-copy"></i>
                                                </button>
                                                <button class="btn-buy-wa btn-sm  share-link" data-paket_id="<?= $rows->idpaket ?>">
                                                    <i class="fab fa-whatsapp"></i>
                                                </button>
                                                <!-- iOS clipboard helper -->
                                                <input type="text" id="clipboard-temp" style="position:fixed;top:-1000px;opacity:0;">
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>


                                </div>
                                <?php if ($rows->iddiskon != null): ?>
                                    <div class="position-absolute top-0 end-0 diskon p-1 text-white"><?= $rows->diskon ?> %</div>
                                <?php endif; ?>
                            </div>
                            <!-- Single Courses End -->
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if ($lihat == '0'): ?>
                    <div class="d-flex justify-content-center">
                        <div class="row">
                            <div class="col-12 text-center" style="padding-top: 20px;">
                                <a href="<?= base_url('list-bimbel') ?>" class="text-primary mt-2 ">Lihat lebih banyak</a><i class="bi bi-arrow-down-square-fill ms-1" style="color: blue;"></i>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <!-- All Courses Wrapper End -->
        </div>
    </div>
    <!-- All Courses tab content End -->
</div>
<!-- untuk paket-->
<div class="modal fade" id="lihatPaket" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-scrollable" style="scrollbar-color: #0000FF #ffffff" id="modalPaket">
        <div class="modal-content position-relative">

            <div class="modal-body card-body ">
                <div class="isideskripsi fs-6" style="margin-top:-25px"></div>
            </div>
            <button type="button" class="position-absolute top-0 start-0 text-light zoom close-deskripsi-paket" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x"></i></button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('show');
                }
            });
        }, {
            threshold: 0.15
        });

        document.querySelectorAll('.animate-card').forEach(card => {
            observer.observe(card);
        });

        // Countdown Timer 24 Jam Berulang (Reset setiap jam 12 malam / 00:00:00)
        function updateCountdowns() {
            const now = new Date();
            
            // Waktu target akhir siklus hari ini (jam 00:00:00 hari berikutnya / tengah malam)
            const midnight = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1, 0, 0, 0);
            
            // Sisa waktu dalam milidetik menuju jam 12 malam berikutnya
            const distance = midnight.getTime() - now.getTime();

            if (distance < 0) {
                $('.flash-sale-countdown').text('00:00:00');
                return;
            }

            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            const formatTime = (num) => String(num).padStart(2, '0');
            const timeString = formatTime(hours) + ':' + formatTime(minutes) + ':' + formatTime(seconds);

            $('.flash-sale-countdown').text(timeString);
        }

        setInterval(updateCountdowns, 1000);
        updateCountdowns();
    });
</script>

<?= $this->endSection(); ?>
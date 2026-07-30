<?= $this->extend('siswa/template/app'); ?>
<?= $this->section('styles'); ?>
<style>
    /* Tombol Navigasi Lingkaran Biru */
    .nav-circle {
        width: 40px !important;
        height: 40px !important;
        border-radius: 50% !important;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #29459A !important;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .nav-circle:hover {
        background-color: #0086d1 !important;
        transform: scale(1.1);
    }

    /* Hover Card */
    .paket-card {
        transition: all 0.3s ease;
        border-color: #eff2f5 !important;
    }

    .paket-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
    }

    /* FIX: Mencegah tampilan melompat (CLS) dari 1 ke 2 card saat loading */
    #kt_brevet_slider {
        display: flex;
        gap: 10px;
        overflow: hidden;
    }

    #kt_brevet_slider>div {
        flex: 0 0 calc(50% - 10px);
        min-width: calc(50% - 10px);
    }

    .tns-slider#kt_brevet_slider {
        display: block !important;
    }

    .tns-slider#kt_brevet_slider>div {
        flex: none !important;
        min-width: auto !important;
    }

    @media (max-width: 767px) {
        #kt_brevet_slider>div {
            flex: 0 0 100%;
            min-width: 100%;
        }
    }

    .tns-ovh {
        padding-right: 10px;
        padding-top: 10px;
    }

    /* Penyesuaian Tombol Affiliate */
    .btn-affiliate-action {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px !important;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-affiliate-action:hover {
        transform: translateY(-2px);
    }

    .btn-copy-link {
        background-color: #f1f1f2;
        color: #3f4254;
    }

    .btn-copy-link:hover {
        background-color: #dbdfe9;
        color: #181c32;
    }

    .share-link {
        background-color: #e8fff3;
        color: #50cd89;
    }

    .share-link:hover {
        background-color: #50cd89;
        color: #ffffff;
    }

    @media (min-width: 1200px) {
        .sticky-column {
            position: -webkit-sticky;
            position: sticky;
            top: 80px;
            /* Jarak dari atas layar, sesuaikan dengan tinggi header/navbar kamu */
            z-index: 1;
        }
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="row g-5 g-xl-8">
    <div class="col-xl-4">
        <div class="sticky-column">
            <div class="row mb-5 mb-xl-8 g-5 g-xl-8">
                <?php
                $menus = [
                    ['url' => 'sw-siswa/materi', 'icon' => 'ki-book-open', 'color' => 'primary', 'title' => 'Materi Belajar', 'desc' => 'Akses semua modul'],
                    ['url' => 'sw-siswa/ujian', 'icon' => 'ki-notepad-edit', 'color' => 'danger', 'title' => 'Ujian & Quiz', 'desc' => 'Evaluasi pemahaman'],
                    ['url' => 'sw-siswa/sertifikat', 'icon' => 'ki-medal-star', 'color' => 'success', 'title' => 'Sertifikat', 'desc' => 'Download bukti lulus'],
                    ['url' => 'sw-siswa/affiliate', 'icon' => 'ki-people', 'color' => 'warning', 'title' => 'Affiliate', 'desc' => 'Bonus referensi'],
                    ['url' => 'sw-siswa/transaksi', 'icon' => 'ki-time', 'color' => 'info', 'title' => 'Histori', 'desc' => 'Status pembayaran'],
                    ['url' => 'sw-siswa/profile', 'icon' => 'ki-user', 'color' => 'dark', 'title' => 'Akun Saya', 'desc' => 'Pengaturan data diri'],
                    ['url' => 'sw-siswa/diskusi', 'icon' => 'ki-messages', 'color' => 'warning', 'title' => 'Diskusi', 'desc' => 'Komunitas belajar'],
                    ['url' => 'sw-siswa/ikh', 'icon' => 'ki-award', 'color' => 'warning', 'title' => 'Izin Kuasa Hukum', 'desc' => 'Pengurusan IKH.']
                ];
                foreach ($menus as $m): ?>
                    <div class="col-6 d-flex">
                        <div class="card card-stretch shadow-sm w-100">
                            <a href="<?= base_url($m['url']) ?>" class="btn btn-flex btn-text-gray-800 btn-active-color-<?= $m['color'] ?> bg-body flex-column justify-content-start align-items-start text-start w-100 p-6 p-xl-10 h-100">
                                <i class="ki-outline <?= $m['icon'] ?> fs-2tx mb-5 ms-n1 text-<?= $m['color'] ?>"></i>
                                <span class="fs-4 fw-bold"><?= $m['title'] ?></span>
                                <span class="fs-7 fw-semibold text-muted mt-1 d-none d-md-block"><?= $m['desc'] ?></span>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="col-xl-8 ps-xl-12">
        <?php
        // Cek apakah ada paket webinar/pelatihan aktif yang waktu selesainya belum terlewati
        $currentDateTime = date('Y-m-d H:i:s');
        $activeWebinar = null;
        if (!empty($paketWebinar)) {
            foreach ($paketWebinar as $p) {
                // Mengecek sesi webinar berdasarkan idpaket pada tabel webinar_sesi
                $checkSesi = $db->query("SELECT * FROM webinar_sesi WHERE idpaket = ? AND waktu_selesai >= ? ORDER BY waktu_selesai ASC LIMIT 1", [$p->idpaket, $currentDateTime])->getRow();
                if ($checkSesi) {
                    $activeWebinar = [
                        'paket' => $p,
                        'sesi' => $checkSesi
                    ];
                    break;
                }
            }
        }
        ?>

        <?php if ($activeWebinar): ?>
            <!-- TAMPILAN KHUSUS PELATIHAN/WEBINAR AKTIF BERDASARKAN WAKTU SELESAI -->
            <div class="card border-0 shadow-sm rounded-4 mb-5 mb-xl-8 overflow-hidden">
                <div class="card-body p-0 d-flex flex-column justify-content-center bgi-no-repeat bgi-size-cover bgi-position-x-end"
                    style="background-position: right bottom; background-size: auto 100%; background-image:url('<?= base_url('assets-landing/images/bg-banner.png') /* Ganti dengan URL gambar Anda jika perlu */ ?>');" dir="ltr">

                    <!-- Container konten dengan background gradient putih agar teks tetap tajam terbaca -->
                    <div class="p-10 p-lg-15 w-100 h-100" style="background: linear-gradient(90deg, rgba(255,255,255,1) 0%, rgba(255,255,255,0.95) 50%, rgba(255,255,255,0) 100%);">

                        <!-- Status Badges -->
                        <div class="d-flex align-items-center flex-wrap gap-3 mb-4">
                            <span class="badge badge-light-primary fw-bolder px-4 py-2 rounded-pill">
                                <i class="ki-outline ki-screen fs-4 text-primary me-2"></i> Webinar Sedang Aktif
                            </span>
                            <span class="badge badge-light-danger fw-bolder px-4 py-2 rounded-pill">
                                <i class="ki-outline ki-timer fs-4 text-danger me-2"></i> Berakhir: <?= date('d M Y, H:i', strtotime($activeWebinar['sesi']->waktu_selesai)) ?> WIB
                            </span>
                        </div>

                        <!-- Judul Paket -->
                        <h2 class="text-dark fw-bolder fs-2qx mb-5 w-lg-75 w-100" style="line-height: 1.3;">
                            <?= esc($activeWebinar['paket']->nama_paket) ?>
                        </h2>

                        <!-- Info Detail Sesi (Desain Box Highlight) -->
                        <div class="border border-dashed border-primary border-opacity-50 rounded-4 bg-light-primary p-6 mb-7 w-lg-700px w-100 shadow-sm">
                            <div class="d-flex align-items-center mb-3">
                                <div class="symbol symbol-45px me-4">
                                    <span class="symbol-label bg-primary shadow-sm">
                                        <i class="ki-outline ki-calendar-tick fs-2 text-white"></i>
                                    </span>
                                </div>
                                <div>
                                    <h4 class="text-primary fw-bolder mb-1"><?= esc($activeWebinar['sesi']->nama_sesi) ?></h4>
                                    <div class="text-gray-700 fw-semibold fs-6">
                                        <i class="ki-outline ki-time fs-5 me-1"></i>
                                        <?= date('d M Y', strtotime($activeWebinar['sesi']->waktu_mulai)) ?> <span class="mx-1">|</span>
                                        <?= date('H:i', strtotime($activeWebinar['sesi']->waktu_mulai)) ?> - <?= date('H:i', strtotime($activeWebinar['sesi']->waktu_selesai)) ?> WIB
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($activeWebinar['sesi']->deskripsi_sesi)): ?>
                                <div class="text-gray-600 fw-medium fs-6 mt-4 border-top border-primary border-opacity-25 pt-4">
                                    <i class="ki-outline ki-information fs-5 me-1 text-primary"></i> <?= esc($activeWebinar['sesi']->deskripsi_sesi) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Action Buttons -->
                        <div class="m-0 d-flex flex-column flex-sm-row gap-3">
                            <a href="<?= base_url('webinar/' . $activeWebinar['paket']->slug) ?>"
                                class="btn btn-primary fw-bold px-8 py-3 w-100 w-sm-auto shadow-sm">
                                <i class="ki-outline ki-entrance-left fs-3 me-2"></i> Ikuti Pelatihan
                            </a>
                            <a href="<?= base_url('list-bimbel') ?>"
                                class="btn btn-light-primary fw-bold px-8 py-3 w-100 w-sm-auto">
                                Lihat Paket Lain
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- TAMPILAN KODE SEBELUMNYA APABILA WAKTU SUDAH HABIS ATAU BUKAN PAKET WEBINAR AKTIF -->
            <div class="card bgi-position-y-bottom bgi-position-x-end bgi-no-repeat bgi-size-cover min-h-250px bg-body mb-5 mb-xl-8"
                style="background-position: 100% 50px; background-size: 500px auto; background-image:url('<?= base_url('') ?>')" dir="ltr">
                <div class="card-body d-flex flex-column justify-content-center ps-lg-15">
                    <h3 class="text-dark fs-2qx fw-bold mb-4">Siap Menjadi Ahli Pajak <br /><span class="text-primary">Profesional?</span></h3>
                    <div class="fs-5 fw-semibold text-gray-600 mb-7">Tingkatkan kompetensi Anda dengan materi terupdate dan instruktur berpengalaman.</div>
                    <div class="m-0 d-flex flex-column flex-sm-row">
                        <a href="<?= base_url('sw-siswa/materi') ?>"
                            class="btn btn-primary fw-bold px-8 py-3 mb-2 mb-sm-0 w-100 w-sm-auto">
                            Mulai Belajar Sekarang
                        </a>
                        <a href="<?= base_url('list-bimbel') ?>"
                            class="btn btn-light-primary fw-bold px-8 py-3 ms-0 ms-sm-2 w-100 w-sm-auto">
                            Lihat Paket Lain
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm rounded-4 mb-10">
            <div class="card-body">
                <div class="d-flex flex-stack mb-9">
                    <h3 class="fw-bold text-dark m-0 fs-2">Paket Kelas Brevet</h3>
                    <div class="d-flex align-items-center">
                        <button class="nav-circle me-3" id="kt_slider_prev"><i class="ki-outline ki-left fs-3 text-white"></i></button>
                        <button class="nav-circle" id="kt_slider_next"><i class="ki-outline ki-right fs-3 text-white"></i></button>
                    </div>
                </div>

                <div id="kt_brevet_slider">
                    <?php foreach ($paket as $rows) : ?>
                        <?php if ($rows->id_mapel == '0'): ?>
                            <?php
                            $query = $db->table('paket')->join('detail_paket b', 'paket.idpaket=b.idpaket')->join('ujian_master c', 'b.id_ujian=c.id_ujian')->join('review_ujian d', 'c.kode_ujian=d.kode_ujian')->where('paket.slug', $rows->slug)->get()->getResultObject();
                            $totalRating = 0;
                            $jumlahReview = count($query);
                            foreach ($query as $item) {
                                $totalRating += $item->rating;
                            }
                            $rataRating = $jumlahReview > 0 ? round($totalRating / $jumlahReview, 1) : 0;

                            $soal = $db->query("SELECT a.id_ujian, b.kode_ujian FROM detail_paket a join ujian_master b on a.id_ujian=b.id_ujian where a.idpaket = '$rows->idpaket' group by a.id_ujian")->getResult();
                            $jml = null;
                            foreach ($soal as $r):
                                $jml = $db->query("select count(kode_ujian) as total_soal from ujian_detail where kode_ujian = '$r->kode_ujian'")->getRow();
                            endforeach;
                            ?>

                            <div class="px-1">
                                <div class="card paket-card h-100 rounded-4 overflow-hidden border shadow-none">
                                    <div class="position-relative" style="aspect-ratio: 16/9; overflow: hidden; background-color: #f8f9fa;">
                                        <a href="<?= base_url('bimbel/' . $rows->slug) ?>">
                                            <?= img_lazy('assets-landing/images/paket/thumbnails/' . $rows->file, "loading", ['class' => 'w-100 h-100 object-fit-cover']) ?>
                                        </a>
                                        <?php if ($rows->iddiskon != null): ?>
                                            <div class="position-absolute top-0 end-0 m-4">
                                                <span class="badge badge-danger fw-bold fs-8 px-3 py-2 rounded-pill" style="background-color: #ff6b35 !important;"><?= $rows->diskon ?> %</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="card-body p-7">
                                        <h6 class="fs-5 fw-bold mb-4" style="min-height: 45px;">
                                            <a href="<?= base_url('bimbel/' . $rows->slug) ?>" class="text-dark text-hover-primary"><?= $rows->nama_paket ?></a>
                                        </h6>

                                        <div class="d-flex justify-content-between align-items-center mb-5">
                                            <span class="fs-7 fw-bold text-gray-600"><i class="ki-outline ki-book-open fs-2 text-primary me-1"></i> <?= (!empty($jml) ? $jml->total_soal : '0') ?> Soal/Materi</span>
                                            <div class="text-end">
                                                <div class="fw-bolder fs-4 text-dark">Rp <?= number_format($rows->nominal_paket - (($rows->nominal_paket * $rows->diskon) / 100)) ?></div>
                                                <div class="text-muted text-decoration-line-through fs-8 small">Rp <?= number_format($rows->nominal_paket) ?></div>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center mb-5">
                                            <img src="<?= base_url('assets-landing/images/logo-blue.png') ?>" class="rounded-circle me-3" width="30px">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-gray-800" style="font-size: 11px;">Akuntanmu Learning Center</span>
                                                <span class="text-primary fw-semibold" style="font-size: 10px;">Terverifikasi <i class="bi bi-patch-check-fill"></i></span>
                                            </div>
                                        </div>

                                        <div class="mb-6 h-20px">
                                            <?php if ($rataRating > 0): ?>
                                                <div class="d-flex align-items-center">
                                                    <span class="text-dark fw-bold me-2 fs-7"><?= $rataRating ?></span>
                                                    <div class="rating"> <?= showStars($rataRating) ?> </div>
                                                    <span class="text-muted fs-8 ms-2">(<?= $jumlahReview ?>)</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mb-6" style="min-height: 20px;">
                                            <?php if (!empty($affiliate)):
                                                $potongan_diskon = ($rows->nominal_paket * $rows->diskon) / 100;
                                                $harga_final     = $rows->nominal_paket - $potongan_diskon;
                                                $est_komisi      = ($harga_final * $rows->komisi) / 100;
                                            ?>
                                                <div class="d-flex align-items-center flex-wrap gap-1" style="font-size: 0.80rem;">
                                                    <span class="text-muted fw-semibold">Komisi Affiliate:</span>
                                                    <span class="text-danger fw-bolder"><?= $rows->komisi ?>%</span>
                                                    <span class="text-muted mx-1">|</span>
                                                    <span class="text-muted fw-semibold">Est:</span>
                                                    <span class="text-danger fw-bolder">Rp <?= number_format($est_komisi, 0, ',', '.') ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (session('role') == 2) : ?>
                                            <div class="d-flex align-items-center gap-2">
                                                <a href="<?= base_url('sw-siswa/transaksi/pesan/' . encrypt_url($rows->idpaket)) ?>" class="btn btn-primary btn-sm fw-bold flex-grow-1 rounded-3 py-3">Pesan Sekarang</a>

                                                <?php if (!empty($affiliate)): ?>
                                                    <button type="button" class="btn-affiliate-action btn-copy-link"
                                                        data-paket_id="<?= $rows->idpaket ?>"
                                                        data-bs-toggle="tooltip" title="Salin Link Affiliate">
                                                        <i class="ki-outline ki-copy fs-3"></i>
                                                    </button>

                                                    <button type="button" class="btn-affiliate-action share-link"
                                                        data-paket_id="<?= $rows->idpaket ?>"
                                                        data-bs-toggle="tooltip" title="Bagikan ke WhatsApp">
                                                        <i class="fab fa-whatsapp fs-3"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                            <input type="text" id="clipboard-temp" style="position:absolute;left:-9999px;">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Slider Initialization
        var slider = tns({
            container: '#kt_brevet_slider',
            items: 2,
            gutter: 10,
            speed: 400,
            autoplay: false,
            controls: false,
            nav: false,
            mouseDrag: true,
            touch: true,
            preventScrollOnTouch: 'auto',
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                1200: {
                    items: 2
                }
            }
        });

        document.querySelector('#kt_slider_prev').addEventListener('click', function() {
            slider.goTo('prev');
        });

        document.querySelector('#kt_slider_next').addEventListener('click', function() {
            slider.goTo('next');
        });
    });

    // AJAX Copy Link Affiliate
    $(document).on('click', '.btn-copy-link', function() {
        let btn = $(this);
        let paket_id = btn.data('paket_id');
        btn.prop('disabled', true);

        $.ajax({
            url: "<?= base_url('sw-siswa/affiliate/copy') ?>",
            type: "POST",
            dataType: "json",
            data: {
                paket_id: paket_id,
                <?= csrf_token() ?>: "<?= csrf_hash() ?>"
            },
            success: function(res) {
                if (res.status === 'success') {
                    let link = res.link;
                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(link).then(() => {
                            showAlert('success', 'Berhasil!', 'Link affiliate berhasil disalin.');
                        }).catch(() => fallbackCopy(link));
                    } else {
                        fallbackCopy(link);
                    }
                } else {
                    showAlert('error', 'Gagal!', 'Link gagal dibuat.');
                }
                btn.prop('disabled', false);
            },
            error: function() {
                showAlert('error', 'Error!', 'Terjadi kesalahan server.');
                btn.prop('disabled', false);
            }
        });
    });

    function fallbackCopy(text) {
        let input = document.getElementById('clipboard-temp');
        input.value = text;
        input.focus();
        input.select();
        input.setSelectionRange(0, 99999);
        try {
            document.execCommand('copy');
            showAlert('success', 'Berhasil!', 'Link berhasil disalin.');
        } catch (e) {
            showAlert('error', 'Gagal', 'Gagal menyalin link.');
        }
    }

    // AJAX Share WhatsApp
    $(document).on('click', '.share-link', function(e) {
        e.preventDefault();
        let btn = $(this);
        let paket_id = btn.data('paket_id');
        btn.prop('disabled', true);

        let waWindow = window.open('about:blank', '_blank');

        $.ajax({
            url: "<?= base_url('sw-siswa/affiliate/copy') ?>",
            type: "POST",
            dataType: "json",
            data: {
                paket_id: paket_id,
                <?= csrf_token() ?>: "<?= csrf_hash() ?>"
            },
            success: function(res) {
                if (res.status === 'success') {
                    let link = encodeURIComponent(res.link);
                    waWindow.location.href = "https://wa.me/?text=Cek kelas brevet menarik ini: " + link;
                } else {
                    showAlert('error', 'Gagal!', 'Link gagal dibuat.');
                    waWindow.close();
                }
                btn.prop('disabled', false);
            },
            error: function() {
                showAlert('error', 'Error!', 'Terjadi kesalahan server.');
                waWindow.close();
                btn.prop('disabled', false);
            }
        });
    });

    function showAlert(type, title, message) {
        // Gunakan Swal.fire untuk SweetAlert2
        Swal.fire({
            title: title,
            text: message,
            icon: type, // 'success', 'error', 'warning', 'info'
            timer: 2200,
            showConfirmButton: false // Di SweetAlert2, 'buttons: false' diganti ini
        });
    }
</script>
<?= $this->endSection(); ?>
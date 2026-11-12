<?= $this->extend('siswa/template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid mt-8">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid py-6">

            <!-- JIKA BELUM DAFTAR AFFILIATE -->
            <?php if (empty($affiliates)): ?>
                <div class="card shadow-sm mb-8 border-0">
                    <div class="card-body py-15 text-center">
                        <img src="<?= base_url('assets/media/illustrations/sigma-1/5.png') ?>" class="mw-150px mb-5" alt="" />
                        <div class="fs-3 fw-bolder text-gray-900 mb-2">Anda belum terdaftar sebagai affiliate</div>
                        <div class="fs-6 text-gray-500 mb-8">Mulai dapatkan penghasilan tambahan jutaan rupiah dengan bergabung sekarang.</div>
                        <a href="<?= base_url('sw-siswa/affiliate/create') ?>" class="btn btn-primary shadow-sm px-8 py-3">
                            <i class="ki-outline ki-plus-square fs-2"></i> Daftar Sekarang
                        </a>
                    </div>
                </div>
            <?php else: ?>

                <!-- 1 & 2: DATA AFFILIATE & KOMISI (GRID BERSEBELAHAN AGAR RINGKAS) -->
                <?php
                $status = $affiliates->status;
                $statusMap = [
                    '1' => ['class' => 'badge-light-success border-success text-success', 'label' => 'Approved', 'icon' => 'ki-check-circle'],
                    '0' => ['class' => 'badge-light-warning border-warning text-warning', 'label' => 'Pending', 'icon' => 'ki-information-2'],
                    '2' => ['class' => 'badge-light-danger border-danger text-danger', 'label' => 'Rejected', 'icon' => 'ki-cross-circle'],
                ];
                $currStatus = $statusMap[$status] ?? ['class' => 'badge-light-secondary border-secondary', 'label' => 'Unknown', 'icon' => ''];

                // Hitung Total Komisi
                $totalKomisiTampil = $totalKomisiPending ?? 0;
                ?>

                <?php if ($status === '1'): ?>
                    <!-- ROW KPI ANALISIS AFFILIATE -->
                    <div class="row g-5 g-xl-8 mb-8">
                        <!-- 1. Total Klik -->
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-flush h-100 shadow-sm border-0 bg-white">
                                <div class="card-body d-flex flex-column justify-content-between p-6">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span class="text-muted fw-bold fs-7 text-uppercase">Total Klik Link</span>
                                        <div class="w-40px h-40px rounded-3 bg-light-primary d-flex align-items-center justify-content-center">
                                            <i class="ki-outline ki-mouse fs-2 text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-2hx fw-bolder text-gray-900 mb-1"><?= number_format($totalKlik ?? 0, 0, ',', '.') ?></div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge badge-light-primary fw-bold fs-8"><?= count($linksByPaket ?? []) ?> Link Aktif</span>
                                            <span class="text-muted fs-8">Kunjungan link</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Pesanan Sukses (Konversi) -->
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-flush h-100 shadow-sm border-0 bg-white">
                                <div class="card-body d-flex flex-column justify-content-between p-6">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span class="text-muted fw-bold fs-7 text-uppercase">Pesanan Berhasil</span>
                                        <div class="w-40px h-40px rounded-3 bg-light-success d-flex align-items-center justify-content-center">
                                            <i class="ki-outline ki-basket fs-2 text-success"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-2hx fw-bolder text-gray-900 mb-1"><?= number_format($totalApprovedTransaksi ?? 0, 0, ',', '.') ?></div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge badge-light-success fw-bold fs-8">Closing Pembelian</span>
                                            <span class="text-muted fs-8">Dari referral Anda</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Conversion Rate -->
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-flush h-100 shadow-sm border-0 bg-white">
                                <div class="card-body d-flex flex-column justify-content-between p-6">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span class="text-muted fw-bold fs-7 text-uppercase">Conversion Rate (CR)</span>
                                        <div class="w-40px h-40px rounded-3 bg-light-warning d-flex align-items-center justify-content-center">
                                            <i class="ki-outline ki-chart-line-up fs-2 text-warning"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-2hx fw-bolder text-gray-900 mb-1"><?= $conversionRate ?? 0 ?>%</div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge badge-light-warning fw-bold fs-8"><?= ($totalApprovedTransaksi ?? 0) ?> / <?= ($totalKlik ?? 0) ?> Klik</span>
                                            <span class="text-muted fs-8">Rasio efektivitas</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Total Komisi -->
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-flush h-100 shadow-sm border-0 bg-white">
                                <div class="card-body d-flex flex-column justify-content-between p-6">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span class="text-muted fw-bold fs-7 text-uppercase">Est. Total Komisi</span>
                                        <div class="w-40px h-40px rounded-3 bg-light-info d-flex align-items-center justify-content-center">
                                            <i class="ki-outline ki-wallet fs-2 text-info"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-2hx fw-bolder text-success mb-1">Rp <?= number_format($totalKomisiTampil, 0, ',', '.') ?></div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge badge-light-success fw-bold fs-8">Cair: Rp <?= number_format($totalKomisiCair ?? 0, 0, ',', '.') ?></span>
                                            <span class="text-muted fs-8">Pending pencairan</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row g-5 g-xl-8 mb-8">
                    <!-- 1. DATA AFFILIATE -->
                    <div class="col-xl-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header border-0 pt-6">
                                <div class="card-title flex-column align-items-start">
                                    <h3 class="fw-bolder fs-3 mb-1">Data Affiliate</h3>
                                    <span class="text-muted fw-semibold fs-8">Informasi status & rekening</span>
                                </div>
                                <div class="card-toolbar d-flex align-items-center gap-2">
                                    <?php if ($status === '1'): ?>
                                        <a href="<?= base_url('sw-siswa/affiliate/kartu') ?>" class="btn btn-sm btn-light-primary fw-bold" data-bs-toggle="tooltip" title="Lihat & Cetak Kartu Anggota">
                                            <i class="ki-outline ki-badge fs-4 me-1"></i> Kartu
                                        </a>
                                        <?php if ($affiliates->total_edit <= 0): ?>
                                            <a href="<?= base_url('sw-siswa/affiliate/edit/' . encrypt_url($affiliates->id_affiliate)) ?>" class="btn btn-sm btn-icon btn-light-warning" data-bs-toggle="tooltip" title="Edit Rekening">
                                                <i class="ki-outline ki-pencil fs-4"></i>
                                            </a>
                                        <?php endif; ?>
                                    <?php elseif ($status === '0'): ?>
                                        <button class="btn btn-sm btn-light-danger fw-bold btn-delete" data-id="<?= $affiliates->id_affiliate ?>">
                                            Batalkan
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="card-body pt-3 pb-6">
                                <!-- Status Badge -->
                                <div class="d-flex flex-stack bg-light rounded-3 p-4 mb-5 border border-dashed <?= $currStatus['class'] ?>">
                                    <div class="fw-bold <?= str_replace('border-', 'text-', explode(' ', $currStatus['class'])[1]) ?>">Status Pengajuan</div>
                                    <span class="badge <?= $currStatus['class'] ?> fs-7 fw-bold px-3 py-2">
                                        <i class="ki-outline <?= $currStatus['icon'] ?> fs-5 me-1 <?= str_replace('border-', 'text-', explode(' ', $currStatus['class'])[1]) ?>"></i>
                                        <?= $currStatus['label'] ?>
                                    </span>
                                </div>

                                <?php if ($status === '1' || $status === '2'): ?>
                                    <!-- Rekening Details -->
                                    <div class="bg-gray-50 rounded-3 p-5 border border-gray-200">
                                        <div class="d-flex align-items-center mb-4">
                                            <i class="ki-outline ki-bank fs-1 text-primary me-3"></i>
                                            <h6 class="m-0 fw-bolder text-gray-800">Detail Rekening Pencairan</h6>
                                        </div>
                                        <div class="d-flex flex-column gap-3">
                                            <div class="d-flex justify-content-between align-items-center border-bottom border-gray-200 pb-2">
                                                <span class="text-muted fs-7">Bank & Cabang</span>
                                                <span class="fw-bold text-dark fs-7 text-end"><?= $affiliates->bank ?: '-' ?> <br><span class="fw-normal text-muted fs-8"><?= $affiliates->cabang_bank ?: '-' ?></span></span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center border-bottom border-gray-200 pb-2">
                                                <span class="text-muted fs-7">No. Rekening</span>
                                                <span class="fw-bold text-dark fs-7"><?= $affiliates->norek ?: '-' ?></span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-muted fs-7">Atas Nama</span>
                                                <span class="fw-bold text-dark fs-7"><?= $affiliates->nama_akun_bank ?: '-' ?></span>
                                            </div>
                                        </div>

                                        <!-- Tombol Kartu Anggota -->
                                        <div class="mt-4 pt-3 border-top border-gray-200">
                                            <a href="<?= base_url('sw-siswa/affiliate/kartu') ?>" class="btn btn-primary btn-sm w-100 fw-bold d-flex align-items-center justify-content-center shadow-sm">
                                                <i class="ki-outline ki-id-card fs-4 me-2"></i> Cetak Kartu Anggota (Depan & Belakang)
                                            </a>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center text-muted py-8 fs-7">
                                        Data rekening akan tampil setelah pengajuan disetujui.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- 2. GRAFIK TREN KUNJUNGAN / STATUS AREA -->
                    <div class="col-xl-8">
                        <?php if ($status === '1'): ?>
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header border-0 pt-6">
                                    <div class="card-title flex-column align-items-start">
                                        <h3 class="fw-bolder fs-3 mb-1">Tren Kunjungan Link</h3>
                                        <span class="text-muted fw-semibold fs-8">Statistik klik tautan referral dalam 7 hari terakhir</span>
                                    </div>
                                    <div class="card-toolbar">
                                        <span class="badge badge-light-primary fs-8 fw-bold">7 Hari Terakhir</span>
                                    </div>
                                </div>
                                <div class="card-body pt-2 pb-4">
                                    <div id="chart_affiliate_clicks" style="height: 250px;"></div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body d-flex align-items-center justify-content-center p-10">
                                    <div class="text-center text-muted">
                                        <i class="ki-outline ki-time fs-3x text-warning mb-3"></i><br>
                                        Fitur analitik dan link promosi akan aktif setelah pengajuan disetujui.
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- SECTION PREVIEW KARTU ANGGOTA AFFILIATE (LANGSUNG TAMPIL) -->
                <?php if ($status === '1'): ?>
                    <div class="card shadow-sm border-0 mb-8 rounded-4 overflow-hidden">
                        <div class="card-header border-0 pt-6 px-8">
                            <div class="card-title flex-column align-items-start">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <h3 class="fw-bolder fs-3 m-0 text-dark">Kartu Anggota Affiliate Resmi</h3>
                                    <span class="badge badge-light-success fw-bold fs-8">Verified Partner</span>
                                </div>
                                <span class="text-muted fw-semibold fs-7">Identitas resmi kemitraan affiliate Kelas Brevet. Tampil langsung dan siap dicetak.</span>
                            </div>
                            <div class="card-toolbar d-flex align-items-center gap-2 flex-wrap">
                                <a href="<?= base_url('sw-siswa/affiliate/kartu') ?>" class="btn btn-sm btn-primary fw-bold shadow-sm">
                                    <i class="ki-outline ki-printer fs-4 me-1"></i> Buka Halaman Cetak
                                </a>
                            </div>
                        </div>

                        <div class="card-body px-8 pb-8 pt-2">
                            <div class="row g-6 justify-content-center">
                                <!-- DEPAN (FRONT) -->
                                <div class="col-12 col-xl-6 d-flex flex-column align-items-center">
                                    <div class="d-flex justify-content-between align-items-center w-100 max-w-card mb-2">
                                        <span class="fw-bold fs-7 text-gray-700 d-flex align-items-center gap-2">
                                            <i class="ki-outline ki-element-plus text-primary fs-5"></i> Sisi Depan
                                        </span>
                                        <span class="badge badge-light-primary fs-9 fw-semibold">CR80 (85.6 x 54 mm)</span>
                                    </div>
                                    <div class="id-card id-card-front shadow-lg">
                                        <div class="card-overlay-mesh"></div>
                                        <div class="card-glow-orb orb-1"></div>
                                        <div class="card-glow-orb orb-2"></div>
                                        <div class="card-inner d-flex flex-column justify-content-between h-100 position-relative">
                                            <!-- Card Header -->
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="<?= base_url('assets-landing/images/logo-putih.png') ?>" alt="Kelas Brevet" class="brand-logo" onerror="this.style.display='none'; document.getElementById('text-brand-live-front').style.display='block';">
                                                    <div id="text-brand-live-front" style="display:none;">
                                                        <span class="text-white fw-bolder fs-5 tracking-wide">KELAS BREVET</span>
                                                    </div>
                                                    <div class="border-start border-white border-opacity-25 ps-3 py-1">
                                                        <div class="text-gold fw-bold text-uppercase fs-9 letter-spacing-1">Affiliate Partner</div>
                                                        <div class="text-white text-opacity-75 fs-10 fw-normal">Lembaga Kursus & Pelatihan Resmi</div>
                                                    </div>
                                                </div>
                                                <div class="hologram-badge">
                                                    <div class="holo-inner">
                                                        <i class="ki-outline ki-shield-tick text-gold fs-6"></i>
                                                        <span class="holo-text">OFFICIAL</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Card Center / Body (Tanpa Cip & Tanpa Wireless) -->
                                            <div class="d-flex align-items-center justify-content-between my-auto py-2">
                                                <div class="d-flex align-items-center gap-4">
                                                    <div class="position-relative member-photo-wrapper flex-shrink-0">
                                                        <img src="<?= $avatarUrl ?>" alt="<?= esc($siswa->nama_siswa) ?>" class="member-photo">
                                                        <span class="verified-tick" title="Verified Affiliate">
                                                            <i class="ki-outline ki-check text-white fs-8"></i>
                                                        </span>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span class="text-gold fs-10 fw-bold letter-spacing-1 mb-1">MITRA RESMI</span>
                                                        <div class="member-name text-truncate" title="<?= esc($siswa->nama_siswa) ?>">
                                                            <?= strtoupper(esc($siswa->nama_siswa)) ?>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-2 mt-2">
                                                            <span class="code-badge"><?= $memberId ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="qr-container text-center flex-shrink-0 ms-2">
                                                    <img src="<?= $qrDataUri ?>" alt="QR Code Referral" class="qr-code-img">
                                                    <span class="qr-label">SCAN LINK</span>
                                                </div>
                                            </div>

                                            <!-- Card Footer -->
                                            <div class="pt-2 border-top border-white border-opacity-10 mt-auto">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="text-white text-opacity-65 fs-10 d-flex align-items-center gap-1">
                                                        <i class="ki-outline ki-verify fs-7 text-gold"></i>
                                                        TERDAFTAR RESMI LKP KELAS BREVET
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="badge-status-gold">TERVERIFIKASI</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- BELAKANG (BACK) -->
                                <div class="col-12 col-xl-6 d-flex flex-column align-items-center">
                                    <div class="d-flex justify-content-between align-items-center w-100 max-w-card mb-2">
                                        <span class="fw-bold fs-7 text-gray-700 d-flex align-items-center gap-2">
                                            <i class="ki-outline ki-element-plus text-primary fs-5"></i> Sisi Belakang
                                        </span>
                                        <span class="badge badge-light-success fs-9 fw-semibold">Official Terms & Logo</span>
                                    </div>
                                    <div class="id-card id-card-back shadow-lg">
                                        <div class="card-overlay-mesh"></div>
                                        <div class="card-glow-orb orb-1"></div>
                                        <div class="card-glow-orb orb-2"></div>
                                        <div class="card-inner d-flex flex-column justify-content-between h-100 position-relative">
                                            <!-- Back Header: Logo Kelas Brevet -->
                                            <div class="d-flex align-items-center justify-content-between pb-2 border-bottom border-white border-opacity-15 mb-2">
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="<?= base_url('assets-landing/images/logo-putih.png') ?>" alt="Kelas Brevet" class="brand-logo-back" onerror="this.style.display='none'; document.getElementById('text-brand-live-back').style.display='block';">
                                                    <div id="text-brand-live-back" style="display:none;">
                                                        <span class="text-white fw-bolder fs-5 tracking-wide">KELAS BREVET</span>
                                                    </div>
                                                    <div class="border-start border-white border-opacity-25 ps-3 py-1">
                                                        <div class="text-gold fw-bold text-uppercase fs-9 letter-spacing-1">Program Kemitraan Affiliate</div>
                                                        <div class="text-white text-opacity-75 fs-10 fw-normal">Lembaga Kursus & Pelatihan Resmi</div>
                                                    </div>
                                                </div>
                                                <div class="hologram-badge">
                                                    <div class="holo-inner">
                                                        <span class="holo-text">RESMI</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Terms & Conditions (Peraturan) -->
                                            <div class="terms-box my-auto">
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <i class="ki-outline ki-shield-tick text-gold fs-5"></i>
                                                    <span class="text-gold fw-bold fs-9 letter-spacing-1 text-uppercase">Ketentuan Penggunaan Kartu Anggota</span>
                                                </div>
                                                <ol class="terms-list m-0 p-0 ps-3 fs-9 text-white text-opacity-90">
                                                    <li>Kartu ini adalah bukti keanggotaan sah mitra resmi program affiliate Kelas Brevet.</li>
                                                    <li>Perhitungan komisi referral terikat secara otomatis pada Kode Affiliate dan QR Code terdaftar.</li>
                                                    <li>Dilarang memindahtangankan akun atau melakukan spamming di luar kode etik promosi.</li>
                                                    <li>Kartu ini diterbitkan oleh manajemen Kelas Brevet dan berlaku selama status affiliate aktif.</li>
                                                </ol>
                                            </div>

                                            <!-- Official Footer -->
                                            <div class="pt-2 border-top border-white border-opacity-15 mt-auto">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex flex-column">
                                                        <span class="text-white text-opacity-90 fs-10 fw-bold">www.kelasbrevet.id</span>
                                                        <span class="text-white text-opacity-60 fs-10">LKP Kelas Brevet Indonesia</span>
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="text-gold fs-10 fw-bold">Hak Cipta Dilindungi</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- 3. KOMISI AFFILIATE (RIWAYAT KOMISI) -->
                <?php if ($status === '1'): ?>
                    <div class="card shadow-sm border-0 mb-8">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title flex-column align-items-start">
                                <h3 class="fw-bolder fs-3 mb-1">Riwayat Komisi Affiliate</h3>
                                <span class="text-muted fw-semibold fs-8">Daftar transaksi siswa yang menggunakan link referral Anda</span>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex flex-column align-items-end bg-light-success rounded-3 px-4 py-2 border border-success border-dashed">
                                    <span class="text-success fs-8 fw-bold text-uppercase">Est. Total Komisi</span>
                                    <span class="text-success fs-3 fw-bolder">Rp <?= number_format($totalKomisiTampil, 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="card-body pt-3 pb-6">
                            <div class="table-responsive" style="max-height: 340px; overflow-y: auto;">
                                <table class="table align-middle table-row-dashed fs-7 gy-3" id="kt_table_komisi">
                                    <thead class="sticky-top bg-white">
                                        <tr class="text-start text-gray-400 fw-bold text-uppercase gs-0">
                                            <th class="w-30px text-center">No</th>
                                            <th class="min-w-100px">Pemesan</th>
                                            <th class="min-w-120px">Paket & Komisi</th>
                                            <th class="min-w-100px text-center">Status</th>
                                            <th class="text-end min-w-80px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                        <?php if (!empty($komisi)): ?>
                                            <?php $no = 1 + ($pager->getCurrentPage('komisi') - 1) * $pager->getPerPage('komisi'); ?>
                                            <?php foreach ($komisi as $k): ?>
                                                <tr>
                                                    <td class="text-center text-gray-800 fw-bold"><?= $no++ ?></td>
                                                    <td><span class="text-gray-800 fw-bold"><?= ucwords(strtolower($k['nama_siswa'])) ?></span></td>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span class="text-gray-800 fw-bold text-truncate" style="max-width: 250px;" title="<?= ucwords(strtolower($k['nama_paket'])) ?>">
                                                                <?= ucwords(strtolower($k['nama_paket'])) ?>
                                                            </span>
                                                            <span class="text-success fw-bold">Rp <?= number_format($k['harga'] * $k['komisi'] / 100, 0, ',', '.') ?> <span class="text-muted fw-normal fs-8">(<?= $k['komisi'] ?>%)</span></span>
                                                        </div>
                                                    </td>
                                                    <td class="text-start">
                                                        <div class="d-flex flex-column gap-2">
                                                            <?php
                                                            // Status Transaksi (Pembelian via link affiliate)
                                                            $sc = ['approved' => 'success', 'pending' => 'warning', 'paid' => 'primary', 'rejected' => 'danger'][$k['status']] ?? 'secondary';

                                                            // Status Pencairan Komisi (Withdrawal)
                                                            $pc = ['pending' => ['warning', 'Pending'], 'approved' => ['primary', 'Approved'], 'processing' => ['info', 'Proses'], 'paid' => ['success', 'Cair']][$k['status_penarikan']] ?? ['secondary', '-'];
                                                            ?>

                                                            <!-- Status Transaksi Pembelian -->
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span class="text-muted fs-8 fw-medium me-3" title="Status pembayaran dari peserta yang menggunakan link Anda">Transaksi:</span>
                                                                <span class="badge badge-light-<?= $sc ?> fs-8 px-2 py-1 min-w-70px text-center"><?= ucfirst($k['status']) ?></span>
                                                            </div>

                                                            <!-- Status Pencairan Komisi -->
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span class="text-muted fs-8 fw-medium me-3" title="Status pencairan komisi Anda ke rekening">Pencairan:</span>
                                                                <span class="badge badge-light-<?= $pc[0] ?> fs-8 px-2 py-1 min-w-70px text-center"><?= $pc[1] ?></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end">
                                                        <?php if ($k['status_penarikan'] == 'paid'): ?>
                                                            <button type="button" class="btn btn-icon btn-light-primary btn-sm btn-view-pencairan" data-id-komisi="<?= $k['id'] ?>" title="Detail Pencairan">
                                                                <i class="ki-outline ki-eye fs-4"></i>
                                                            </button>
                                                        <?php else: ?>
                                                            <span class="text-muted fs-8">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-8">Belum ada data riwayat komisi.</td>
                                            </tr>
                                        <?php endif ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-4">
                                <div class="fs-8 fw-semibold text-gray-500">Total: <?= count($komisi) ?> data</div>
                                <?php if ($pager): ?>
                                    <div class="pagination-sm"><?= $pager->links('komisi', 'bootstrap') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- 3. SECTION PAKET AFFILIATE (Hanya tampil jika Approved) -->
                <?php if ($status === '1'): ?>
                    <div class="card shadow-sm rounded-4 mb-8 border-0">
                        <div class="card-header border-0 pt-6 px-8">
                            <div class="card-title w-100 m-0">
                                <div class="d-flex flex-stack w-100 align-items-center mb-2">
                                    <div class="d-flex flex-column">
                                        <h3 class="fw-bolder fs-3 mb-1 text-dark">Daftar Paket & Link Referral</h3>
                                        <span class="text-muted fw-semibold fs-7">Geser paket, klik <strong class="text-primary">Salin Link</strong> atau <strong class="text-success">Bagikan WA</strong> untuk mulai promosi.</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <button class="btn btn-icon btn-sm btn-light-primary me-2 rounded-circle" id="kt_slider_prev"><i class="ki-outline ki-left fs-3"></i></button>
                                        <button class="btn btn-icon btn-sm btn-light-primary rounded-circle" id="kt_slider_next"><i class="ki-outline ki-right fs-3"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body px-8 pb-8 pt-2">
                            <div id="kt_brevet_slider" class="py-2">
                                <?php if (!empty($paket)): ?>
                                    <?php foreach ($paket as $rows) : ?>
                                        <?php
                                        // Hitung rating
                                        $query = $db->table('paket')->join('detail_paket b', 'paket.idpaket=b.idpaket')->join('ujian_master c', 'b.id_ujian=c.id_ujian')->join('review_ujian d', 'c.kode_ujian=d.kode_ujian')->where('paket.slug', $rows->slug)->get()->getResultObject();

                                        $totalRating = 0;
                                        $jumlahReview = count($query);
                                        foreach ($query as $item) {
                                            $totalRating += $item->rating;
                                        }
                                        $rataRating = $jumlahReview > 0 ? round($totalRating / $jumlahReview, 1) : 0;

                                        // Hitung soal/materi
                                        $soal = $db->query("SELECT a.id_ujian, b.kode_ujian FROM detail_paket a join ujian_master b on a.id_ujian=b.id_ujian where a.idpaket = '$rows->idpaket' group by a.id_ujian")->getResult();
                                        $jml = null;
                                        foreach ($soal as $r):
                                            $hasilUjian = soal_ujian(encrypt_url($r->kode_ujian));
                                            $jml = count($hasilUjian);
                                        endforeach;
                                        ?>

                                        <?php if ($rows->id_mapel == '0' || $rows->id_mapel == '1'): ?>
                                            <div class="pt-2 pb-5 px-2 h-100">
                                                <div class="single-courses card position-relative zoom h-100 rounded-4 overflow-hidden border border-gray-200 shadow-sm transition-all d-flex flex-column">

                                                    <!-- Flash Sale & Diskon -->
                                                    <div class="flash-sale-badge position-absolute top-0 start-0 m-3 p-2 bg-danger text-white rounded-3 shadow-sm d-flex align-items-center gap-2" style="z-index: 10;">
                                                        <span class="fs-icon text-white"><i class="fa fa-bolt text-white"></i> Flash Sale</span>
                                                        <span class="timer-countdown countdown-clock fw-bold text-white">00:00:00</span>
                                                    </div>
                                                    <?php if ($rows->iddiskon != null): ?>
                                                        <div class="position-absolute top-0 end-0 m-3" style="z-index: 10;">
                                                            <span class="badge badge-danger fw-bold fs-8 px-3 py-2 rounded-pill" style="background-color: #ff6b35 !important;"><?= $rows->diskon ?> %</span>
                                                        </div>
                                                    <?php endif; ?>

                                                    <div class="courses-images position-relative" style="aspect-ratio: 16/9; overflow: hidden; background-color: #f8f9fa;">
                                                        <a href="<?= base_url('bimbel/' . $rows->slug) ?>">
                                                            <?= img_lazy('assets-landing/images/paket/thumbnails/' . $rows->file, $rows->nama_paket, ['class' => 'w-100 h-100 object-fit-cover']) ?>
                                                        </a>
                                                    </div>

                                                    <div class="courses-content card-body p-5 d-flex flex-column flex-grow-1">
                                                        <h4 class="title fs-5 fw-bold mb-3" style="min-height: 45px;">
                                                            <a href="<?= base_url('bimbel/' . $rows->slug) ?>" class="text-dark text-hover-primary"><?= $rows->nama_paket ?></a>
                                                        </h4>

                                                        <div class="d-flex justify-content-between align-items-center mb-4 pb-4 border-bottom border-dashed">
                                                            <span class="fs-7 fw-bold text-gray-600"><i class="icofont-read-book fs-4 text-primary me-1"></i> <?= (!empty($jml) ? $jml : '0') ?> Soal/<span style="font-size:10px">Materi</span></span>
                                                            <div class="text-end">
                                                                <?php
                                                                $persen_diskon = $rows->diskon ?? 0;
                                                                $nilai_diskon = ($rows->nominal_paket * $persen_diskon) / 100;
                                                                $harga_tampil = $rows->nominal_paket - $nilai_diskon;
                                                                ?>
                                                                <div class="fw-bolder fs-4 text-dark">Rp <?= number_format($harga_tampil, 0, ',', '.') ?></div>
                                                                <div class="text-muted text-decoration-line-through fs-8 small">Rp <?= number_format($rows->nominal_paket, 0, ',', '.') ?></div>
                                                            </div>
                                                        </div>

                                                        <!-- Affiliate Estimasi Box -->
                                                        <?php
                                                        $est_komisi = ($harga_tampil * $rows->komisi) / 100;
                                                        ?>
                                                        <div class="affiliate-box p-3 mb-3 bg-light-success border border-success border-dashed rounded-3">
                                                            <div class="d-flex justify-content-between align-items-center fs-7">
                                                                <div>
                                                                    <span class="me-1">💰</span><span class="text-success fw-bold">Komisi (<?= $rows->komisi ?>%)</span>
                                                                </div>
                                                                <strong class="text-dark fs-6">Rp <?= number_format($est_komisi, 0, ',', '.') ?></strong>
                                                            </div>
                                                        </div>

                                                        <!-- Statistik Klik & Terjual Per Paket -->
                                                        <?php
                                                        $linkData = $linksByPaket[$rows->idpaket] ?? null;
                                                        $klikPerPaket = (int)($linkData['klik_link'] ?? 0);
                                                        $salesData = $salesByPaket[$rows->idpaket] ?? null;
                                                        $terjualPerPaket = (int)($salesData['total_terjual'] ?? 0);
                                                        ?>
                                                        <div class="bg-light-primary rounded-3 p-3 mb-4 border border-primary border-dashed">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <span class="badge badge-light-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                                                        <i class="ki-outline ki-mouse fs-6 text-primary"></i>
                                                                    </span>
                                                                    <div class="d-flex flex-column">
                                                                        <span class="text-muted fs-8 fw-semibold">Jumlah Diklik</span>
                                                                        <span class="text-gray-900 fw-bolder fs-7"><?= number_format($klikPerPaket, 0, ',', '.') ?> kali</span>
                                                                    </div>
                                                                </div>
                                                                <div class="text-end">
                                                                    <span class="text-muted fs-8 fw-semibold d-block">Terjual</span>
                                                                    <span class="badge badge-light-success fw-bolder fs-8"><?= number_format($terjualPerPaket, 0, ',', '.') ?> Pesanan</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Accordion Info Paket -->
                                                        <div class="accordion accordion-flush mb-4" id="accordionDetail<?= $rows->idpaket ?>">
                                                            <div class="card border border-gray-200 shadow-none rounded-2 overflow-hidden">
                                                                <div class="card-header bg-light py-2 px-3 min-h-auto" id="heading<?= $rows->idpaket ?>">
                                                                    <h3 class="card-title m-0 w-100">
                                                                        <button class="accordion-button collapsed p-0 bg-transparent shadow-none fw-bold fs-8 text-gray-800 w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDetail<?= $rows->idpaket ?>" aria-expanded="false">
                                                                            <i class="icofont-info-circle text-primary fs-6 me-2"></i> Info & Keunggulan
                                                                        </button>
                                                                    </h3>
                                                                </div>
                                                                <div id="collapseDetail<?= $rows->idpaket ?>" class="accordion-collapse collapse" data-bs-parent="#accordionDetail<?= $rows->idpaket ?>">
                                                                    <div class="card-body p-3 bg-white">
                                                                        <div class="text-gray-600 fs-8 mb-3" style="line-height: 1.5;">
                                                                            <?= !empty($rows->deskripsi) ? $rows->deskripsi : (!empty($rows->detail_paket) ? $rows->detail_paket : 'Detail informasi paket.') ?>
                                                                        </div>
                                                                        <div class="d-flex flex-column gap-2">
                                                                            <div class="d-flex align-items-center"><i class="fa fa-check-circle text-success fs-8 me-2"></i><span class="fs-8 text-gray-700">LKP Terdaftar Resmi</span></div>
                                                                            <div class="d-flex align-items-center"><i class="fa fa-check-circle text-success fs-8 me-2"></i><span class="fs-8 text-gray-700">Sertifikat Brevet Diakui</span></div>
                                                                            <div class="d-flex align-items-center"><i class="fa fa-check-circle text-success fs-8 me-2"></i><span class="fs-8 text-gray-700">Akses Belajar Selamanya</span></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Action Buttons -->
                                                        <div class="d-flex flex-column gap-2 mt-auto">
                                                            <div class="d-flex gap-2 w-100 mb-1">
                                                                <button type="button" class="btn btn-light-primary fw-bold w-50 btn-copy-link rounded-3 py-2 px-1 d-flex justify-content-center align-items-center fs-7" data-paket_id="<?= $rows->idpaket ?>" title="Salin Link">
                                                                    <i class="ki-outline ki-copy fs-4 me-1"></i> Salin Link
                                                                </button>
                                                                <button type="button" class="btn btn-success fw-bold w-50 share-link rounded-3 py-2 px-1 d-flex justify-content-center align-items-center fs-7" data-paket_id="<?= $rows->idpaket ?>" title="Bagikan WA">
                                                                    <i class="fab fa-whatsapp fs-4 me-1"></i> Bagikan WA
                                                                </button>
                                                            </div>
                                                            <a href="<?= base_url('sw-siswa/transaksi/pesan/' . encrypt_url($rows->idpaket)) ?>" class="btn btn-outline btn-outline-dashed btn-outline-default btn-sm fw-bold w-100 rounded-3 text-center text-muted">
                                                                Lihat Paket
                                                            </a>
                                                        </div>
                                                        <input type="text" id="clipboard-temp" style="position:absolute;left:-9999px;">
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="col-12 text-center text-muted py-10 w-100">Belum ada data paket pelatihan.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- 4. TABEL ANALISA & PERFORMA LINK REFERRAL PER PAKET -->
                    <div class="card shadow-sm rounded-4 mb-8 border-0">
                        <div class="card-header border-0 pt-6 px-8">
                            <div class="card-title flex-column align-items-start">
                                <h3 class="fw-bolder fs-3 mb-1 text-dark">Analisa & Performa Link Referral per Paket</h3>
                                <span class="text-muted fw-semibold fs-7">Rincian efektivitas klik, transaksi, dan komisi dari masing-masing paket yang Anda promosikan.</span>
                            </div>
                        </div>
                        <div class="card-body px-8 pb-8 pt-2">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-7 gy-4">
                                    <thead>
                                        <tr class="text-start text-gray-400 fw-bold text-uppercase gs-0">
                                            <th class="min-w-200px">Paket Pelatihan</th>
                                            <th class="min-w-180px">Link Referral</th>
                                            <th class="text-center min-w-90px">Total Klik</th>
                                            <th class="text-center min-w-90px">Terjual</th>
                                            <th class="text-center min-w-120px">Konversi (CR)</th>
                                            <th class="text-end min-w-130px">Total Komisi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                        <?php if (!empty($paket)): ?>
                                            <?php foreach ($paket as $p): ?>
                                                <?php
                                                $lData = $linksByPaket[$p->idpaket] ?? null;
                                                $pKlik = (int)($lData['klik_link'] ?? 0);
                                                $sData = $salesByPaket[$p->idpaket] ?? null;
                                                $pTerjual = (int)($sData['total_terjual'] ?? 0);
                                                $pKomisi = (float)($sData['total_komisi_paket'] ?? 0);
                                                $pCr = $pKlik > 0 ? round(($pTerjual / $pKlik) * 100, 1) : 0;
                                                $linkUrl = !empty($lData) ? base_url('ref/' . $lData['short_code'] . '/8173AF4239') : null;
                                                ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="symbol symbol-40px me-3">
                                                                <?= img_lazy('assets-landing/images/paket/thumbnails/' . $p->file, $p->nama_paket, ['class' => 'rounded-3 object-fit-cover w-40px h-40px']) ?>
                                                            </div>
                                                            <div class="d-flex flex-column">
                                                                <span class="text-gray-900 fw-bold"><?= esc($p->nama_paket) ?></span>
                                                                <span class="text-muted fs-8">Komisi: <strong class="text-success"><?= $p->komisi ?>%</strong></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php if ($linkUrl): ?>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <code class="text-primary bg-light-primary px-2 py-1 rounded fs-8 text-truncate" style="max-width: 160px;" title="<?= $linkUrl ?>">
                                                                    ref/<?= $lData['short_code'] ?>
                                                                </code>
                                                                <button type="button" class="btn btn-icon btn-sm btn-light-primary btn-copy-link" data-paket_id="<?= $p->idpaket ?>" title="Salin Link">
                                                                    <i class="ki-outline ki-copy fs-5"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-icon btn-sm btn-light-success share-link" data-paket_id="<?= $p->idpaket ?>" title="Bagikan WA">
                                                                    <i class="fab fa-whatsapp fs-5"></i>
                                                                </button>
                                                            </div>
                                                        <?php else: ?>
                                                            <button type="button" class="btn btn-sm btn-light-primary fw-bold btn-copy-link py-1 px-3 fs-8" data-paket_id="<?= $p->idpaket ?>">
                                                                <i class="ki-outline ki-copy fs-6 me-1"></i> Buat Link
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-light-primary fs-7 fw-bold px-3 py-2">
                                                            <i class="ki-outline ki-mouse fs-6 me-1 text-primary"></i> <?= number_format($pKlik, 0, ',', '.') ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-light-success fs-7 fw-bold px-3 py-2">
                                                            <?= number_format($pTerjual, 0, ',', '.') ?> pesanan
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <span class="fw-bold fs-7 <?= $pCr > 0 ? 'text-success' : 'text-muted' ?>"><?= $pCr ?>%</span>
                                                            <div class="progress h-4px w-100 bg-light-secondary mt-1" style="max-width: 80px;">
                                                                <div class="progress-bar bg-<?= $pCr >= 10 ? 'success' : ($pCr > 0 ? 'primary' : 'secondary') ?>" role="progressbar" style="width: <?= min($pCr, 100) ?>%"></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end">
                                                        <span class="text-success fw-bolder fs-7">Rp <?= number_format($pKomisi, 0, ',', '.') ?></span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-5">Belum ada data paket pelatihan.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- 5. PEDOMAN AFFILIATE CARD (Diletakkan di luar supaya SELALU TAMPIL baik yang sudah daftar maupun belum) -->
            <div class="card shadow-sm border-0 bg-light-info mb-8">
                <div class="card-header border-0 pt-6">
                    <div class="card-title m-0">
                        <div class="d-flex align-items-center">
                            <i class="ki-outline ki-shield-tick fs-1 text-primary me-3"></i>
                            <div class="d-flex flex-column">
                                <h3 class="fw-bolder fs-3 mb-1 text-gray-900">Panduan & Peraturan Affiliate</h3>
                                <span class="text-muted fw-semibold fs-7">Pahami ketentuan berikut untuk memaksimalkan komisi Anda.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-5">
                        <div class="col-md-6 col-xl-3">
                            <div class="bg-white p-5 rounded-3 shadow-sm h-100 border border-gray-200">
                                <i class="ki-outline ki-people fs-2x text-primary mb-3 d-block"></i>
                                <h5 class="fw-bold text-gray-800 mb-2 fs-6">Ketentuan Umum</h5>
                                <span class="text-gray-600 fs-8 lh-sm">Dapatkan penghasilan tambahan dengan merekomendasikan pelatihan Kelas Brevet via media sosial.</span>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="bg-white p-5 rounded-3 shadow-sm h-100 border border-gray-200">
                                <i class="ki-outline ki-percentage fs-2x text-success mb-3 d-block"></i>
                                <h5 class="fw-bold text-gray-800 mb-2 fs-6">Besaran Komisi</h5>
                                <span class="text-gray-600 fs-8 lh-sm">Komisi berdasarkan persentase dari harga paket yang terjual melalui link Anda.</span>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="bg-white p-5 rounded-3 shadow-sm h-100 border border-gray-200">
                                <i class="ki-outline ki-link fs-2x text-info mb-3 d-block"></i>
                                <h5 class="fw-bold text-gray-800 mb-2 fs-6">Cara Kerja Referral</h5>
                                <span class="text-gray-600 fs-8 lh-sm">Bagikan link atau klik tombol WA. Pembelian sah melalui link tersebut akan otomatis dihitung komisi.</span>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="bg-white p-5 rounded-3 shadow-sm h-100 border border-gray-200">
                                <i class="ki-outline ki-wallet fs-2x text-warning mb-3 d-block"></i>
                                <h5 class="fw-bold text-gray-800 mb-2 fs-6">Sistem Pencairan</h5>
                                <span class="text-gray-600 fs-8 lh-sm">Cair otomatis tiap <strong>awal bulan</strong> (Min. Rp 100.000) ke rekening valid yang Anda daftarkan.</span>
                            </div>
                        </div>
                    </div>

                    <div class="notice d-flex bg-white rounded-3 border-danger border border-dashed p-4 mt-5 shadow-sm align-items-center">
                        <i class="ki-outline ki-information-2 fs-2x text-danger me-4"></i>
                        <div class="d-flex flex-stack flex-grow-1">
                            <div class="fw-semibold">
                                <h5 class="text-danger fw-bold fs-6 mb-1">Larangan Keras</h5>
                                <div class="fs-8 text-gray-700">Dilarang spamming, menyebar link di grup resmi tanpa izin, atau memberikan klaim palsu. Pelanggaran berakibat pada penangguhan akun sepihak.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ==============================================
     MODAL DETAIL PENCAIRAN UNTUK SISWA
     ============================================== -->
<div class="modal fade" id="modalDetailPencairan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Detail Riwayat Pencairan Komisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-5">
                    <div class="col-md-6">
                        <table class="table table-row-bordered fs-6 gy-2">
                            <tr>
                                <td class="fw-bold text-muted">Kode Penarikan</td>
                                <td id="det_kode_penarikan" class="fw-bold text-gray-800">-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Bank Tujuan</td>
                                <td id="det_bank">-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">No. Rekening</td>
                                <td id="det_norek">-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Atas Nama</td>
                                <td id="det_atas_nama">-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Status</td>
                                <td id="det_status">-</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-row-bordered fs-6 gy-2">
                            <tr>
                                <td class="fw-bold text-muted">Nominal Kotor</td>
                                <td id="det_kotor" class="text-end">-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Potongan PPh21</td>
                                <td id="det_pph21" class="text-end text-danger">-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Biaya Admin</td>
                                <td id="det_admin" class="text-end text-danger">-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Total Bersih</td>
                                <td id="det_bersih" class="text-end fw-bold text-success">-</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="separator my-5"></div>
                <div class="text-center">
                    <label class="form-label fw-bold d-block mb-3">Bukti Transfer:</label>
                    <div id="wrapper_bukti" class="border rounded p-3 bg-light"><span class="text-muted">Memuat bukti transfer...</span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling */
    .table.table-row-dashed tr {
        border-bottom: 1px dashed var(--bs-gray-200);
    }

    .badge-light-success {
        background-color: #E8FFF3;
        color: #50CD89;
    }

    .badge-light-warning {
        background-color: #FFF8DD;
        color: #FFC700;
    }

    .badge-light-primary {
        background-color: #F1FAFF;
        color: #009EF7;
    }

    .badge-light-danger {
        background-color: #FFF5F8;
        color: #F1416C;
    }

    .zoom {
        transition: transform 0.3s ease;
    }

    .zoom:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
    }

    /* Scrollbar minimalis untuk table komisi */
    .table-responsive::-webkit-scrollbar {
        height: 6px;
        width: 6px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    /* KARTU ANGGOTA STYLES */
    :root {
        --card-gold: #e5b95c;
        --card-gold-light: #fef08a;
        --card-gold-dark: #b48324;
        --card-navy-deep: #070e1e;
        --card-navy-mid: #0d1b2a;
        --card-navy-surface: #1b263b;
    }

    .max-w-card {
        max-width: 480px;
    }

    .id-card {
        width: 100%;
        max-width: 480px;
        height: 300px;
        border-radius: 18px;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, var(--card-navy-deep) 0%, var(--card-navy-mid) 50%, var(--card-navy-surface) 100%);
        border: 1.5px solid rgba(229, 185, 92, 0.45);
        color: #ffffff;
        box-shadow: 0 15px 35px rgba(7, 14, 30, 0.35), 0 5px 15px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .id-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(7, 14, 30, 0.45), 0 0 25px rgba(229, 185, 92, 0.2);
    }

    .card-overlay-mesh {
        position: absolute;
        inset: 0;
        background-image: 
            radial-gradient(circle at 10% 20%, rgba(229, 185, 92, 0.08) 0%, transparent 20%),
            radial-gradient(circle at 90% 80%, rgba(59, 130, 246, 0.1) 0%, transparent 25%),
            repeating-linear-gradient(45deg, rgba(255, 255, 255, 0.015) 0px, rgba(255, 255, 255, 0.015) 2px, transparent 2px, transparent 6px);
        pointer-events: none;
        z-index: 1;
    }

    .card-glow-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(40px);
        pointer-events: none;
        z-index: 0;
    }
    .orb-1 {
        width: 140px;
        height: 140px;
        background: rgba(229, 185, 92, 0.12);
        top: -30px;
        right: -30px;
    }
    .orb-2 {
        width: 160px;
        height: 160px;
        background: rgba(30, 64, 175, 0.18);
        bottom: -40px;
        left: -30px;
    }

    .card-inner {
        padding: 22px 24px;
        position: relative;
        z-index: 2;
    }

    .text-gold {
        color: var(--card-gold) !important;
    }
    .letter-spacing-1 {
        letter-spacing: 1.5px;
    }
    .fs-9 {
        font-size: 0.74rem;
        line-height: 1.4;
    }
    .fs-10 {
        font-size: 0.65rem;
        line-height: 1.35;
    }

    .brand-logo {
        height: 32px;
        width: auto;
        object-fit: contain;
    }
    .brand-logo-back {
        height: 30px;
        width: auto;
        object-fit: contain;
    }

    .hologram-badge {
        background: linear-gradient(135deg, rgba(229, 185, 92, 0.25), rgba(255, 255, 255, 0.35), rgba(229, 185, 92, 0.25));
        border: 1px solid rgba(229, 185, 92, 0.6);
        border-radius: 8px;
        padding: 2px;
        box-shadow: 0 0 10px rgba(229, 185, 92, 0.3);
    }
    .holo-inner {
        background: rgba(7, 14, 30, 0.8);
        padding: 4px 8px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .holo-text {
        font-size: 0.62rem;
        font-weight: 800;
        letter-spacing: 1.2px;
        background: linear-gradient(90deg, #fef08a, #e5b95c, #ffffff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .member-photo-wrapper {
        position: relative;
        width: 80px;
        height: 80px;
    }
    .member-photo {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 50%;
        border: 2.5px solid var(--card-gold);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5), 0 0 10px rgba(229, 185, 92, 0.3);
        background: #0f172a;
    }
    .verified-tick {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 22px;
        height: 22px;
        background: #10b981;
        border: 2px solid #070e1e;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .qr-container {
        background: #ffffff;
        padding: 5px;
        border-radius: 8px;
        border: 2px solid var(--card-gold);
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .qr-code-img {
        width: 68px;
        height: 68px;
        object-fit: contain;
        display: block;
    }
    .qr-label {
        display: block;
        font-size: 0.55rem;
        font-weight: 800;
        color: #070e1e;
        letter-spacing: 0.8px;
        margin-top: 2px;
    }

    .member-name {
        font-size: 1.05rem;
        font-weight: 800;
        letter-spacing: 1.2px;
        color: #ffffff;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.6);
        max-width: 250px;
    }
    .code-badge {
        font-family: 'Courier New', Courier, monospace;
        font-size: 0.76rem;
        font-weight: 800;
        color: var(--card-gold);
        background: rgba(229, 185, 92, 0.12);
        padding: 2px 8px;
        border-radius: 4px;
        border: 1px solid rgba(229, 185, 92, 0.35);
        letter-spacing: 1.5px;
    }
    .badge-status-gold {
        font-size: 0.65rem;
        font-weight: 800;
        letter-spacing: 1.2px;
        color: var(--card-gold);
        background: rgba(229, 185, 92, 0.1);
        border: 1px solid rgba(229, 185, 92, 0.4);
        padding: 2px 8px;
        border-radius: 4px;
    }

    .terms-box {
        background: rgba(0, 0, 0, 0.3);
        border-radius: 8px;
        padding: 10px 14px;
        border: 1px solid rgba(229, 185, 92, 0.2);
    }
    .terms-list li {
        margin-bottom: 5px;
    }
</style>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // --- 0. APEXCHARTS TREN KLIK HARIAN ---
        if (document.querySelector("#chart_affiliate_clicks") && typeof ApexCharts !== 'undefined') {
            var chartEl = document.getElementById('chart_affiliate_clicks');
            var chartOptions = {
                series: [{
                    name: 'Jumlah Klik',
                    data: <?= json_encode($chartClicks ?? [0, 0, 0, 0, 0, 0, 0]) ?>
                }],
                chart: {
                    type: 'area',
                    height: 250,
                    toolbar: { show: false },
                    zoom: { enabled: false }
                },
                colors: ['#009EF7'],
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                xaxis: {
                    categories: <?= json_encode($chartDates ?? []) ?>,
                    labels: {
                        style: {
                            colors: '#a1a5b7',
                            fontSize: '12px'
                        }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    min: 0,
                    forceNiceScale: true,
                    labels: {
                        style: {
                            colors: '#a1a5b7',
                            fontSize: '12px'
                        },
                        formatter: function(val) {
                            return Math.round(val);
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f1f4',
                    strokeDashArray: 4,
                    yaxis: { lines: { show: true } }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + " Klik";
                        }
                    }
                },
                markers: {
                    size: 4,
                    colors: ['#009EF7'],
                    strokeColors: '#ffffff',
                    strokeWidth: 2,
                    hover: { size: 6 }
                }
            };

            var affiliateChart = new ApexCharts(chartEl, chartOptions);
            affiliateChart.render();
        }

        // --- 1. SLIDER ---
        if (document.querySelector('#kt_brevet_slider')) {
            var sliderBrevet = tns({
                container: '#kt_brevet_slider',
                items: 3,
                gutter: 20,
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
                        items: 3
                    }
                }
            });
            $('#kt_slider_prev').on('click', () => sliderBrevet.goTo('prev'));
            $('#kt_slider_next').on('click', () => sliderBrevet.goTo('next'));
        }

        // --- 2. AJAX COPY LINK ---
        $(document).on('click', '.btn-copy-link', function() {
            let btn = $(this);
            let originalContent = btn.html();
            btn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> Wait..');

            $.ajax({
                url: "<?= base_url('sw-siswa/affiliate/copy') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    paket_id: btn.data('paket_id'),
                    <?= csrf_token() ?>: "<?= csrf_hash() ?>"
                },
                success: function(res) {
                    if (res.status === 'success') {
                        if (navigator.clipboard && window.isSecureContext) {
                            navigator.clipboard.writeText(res.link).then(() => showAlert('success', 'Disalin!', 'Link siap dibagikan.')).catch(() => fallbackCopy(res.link));
                        } else fallbackCopy(res.link);
                    } else showAlert('error', 'Gagal!', 'Link gagal dibuat.');
                    btn.prop('disabled', false).html(originalContent);
                },
                error: function() {
                    showAlert('error', 'Error!', 'Server bermasalah.');
                    btn.prop('disabled', false).html(originalContent);
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
                showAlert('success', 'Disalin!', 'Link siap dibagikan.');
            } catch (e) {
                showAlert('error', 'Gagal', 'Tidak dapat menyalin link.');
            }
        }

        // --- 3. AJAX SHARE WA ---
        $(document).on('click', '.share-link', function(e) {
            e.preventDefault();
            let btn = $(this);
            let originalContent = btn.html();
            btn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> Wait..');
            let waWindow = window.open('about:blank', '_blank');

            $.ajax({
                url: "<?= base_url('sw-siswa/affiliate/copy') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    paket_id: btn.data('paket_id'),
                    <?= csrf_token() ?>: "<?= csrf_hash() ?>"
                },
                success: function(res) {
                    if (res.status === 'success') {
                        let link = encodeURIComponent(res.link);
                        let pesan = encodeURIComponent("Halo, yuk gabung pelatihan brevet resmi bersama kami. Daftar lewat link ini ya: \n\n");
                        waWindow.location.href = "https://wa.me/?text=" + pesan + link;
                    } else {
                        showAlert('error', 'Gagal!', 'Link gagal dibuat.');
                        waWindow.close();
                    }
                    btn.prop('disabled', false).html(originalContent);
                },
                error: function() {
                    showAlert('error', 'Error!', 'Server bermasalah.');
                    waWindow.close();
                    btn.prop('disabled', false).html(originalContent);
                }
            });
        });

        function showAlert(type, title, message) {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
                timer: 2000,
                showConfirmButton: false
            });
        }

        // --- 4. BATALKAN AFFILIATE ---
        $(document).on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Batalkan Pengajuan?',
                text: "Data affiliate Anda akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Tutup',
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: "btn btn-active-light"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("<?= base_url('sw-siswa/affiliate/delete') ?>", {
                        id: id,
                        <?= csrf_token() ?>: "<?= csrf_hash() ?>"
                    }, function(res) {
                        if (res.status === 'success') Swal.fire({
                            title: 'Berhasil!',
                            text: res.message,
                            icon: 'success'
                        }).then(() => location.reload());
                        else Swal.fire('Gagal!', res.message, 'error');
                    }, 'json');
                }
            });
        });

        // --- 5. DETAIL PENCAIRAN ---
        $(document).on('click', '.btn-view-pencairan', function() {
            let idKomisi = $(this).data('id-komisi');
            $('#det_kode_penarikan, #det_bank, #det_norek, #det_atas_nama, #det_status, #det_kotor, #det_pph21, #det_admin, #det_bersih').text('...');
            $('#wrapper_bukti').html('<span class="text-muted">Memuat...</span>');
            $('#modalDetailPencairan').modal('show');

            $.ajax({
                url: "<?= base_url('sw-siswa/affiliate/getDetailPencairan') ?>/" + idKomisi,
                type: "GET",
                dataType: "json",
                success: function(res) {
                    if (res.status === 'success') {
                        let d = res.data;
                        $('#det_kode_penarikan').text(d.kode_penarikan);
                        $('#det_bank').text(d.bank_tujuan);
                        $('#det_norek').text(d.no_rekening);
                        $('#det_atas_nama').text(d.atas_nama);
                        $('#det_status').html('<span class="badge badge-light-success fw-bold">' + d.status.toUpperCase() + '</span>');
                        $('#det_kotor').text('Rp ' + parseFloat(d.nominal_kotor).toLocaleString('id-ID'));
                        $('#det_pph21').text('- Rp ' + parseFloat(d.potongan_pph21).toLocaleString('id-ID'));
                        $('#det_admin').text('- Rp ' + parseFloat(d.biaya_admin).toLocaleString('id-ID'));
                        $('#det_bersih').text('Rp ' + parseFloat(d.nominal_bersih).toLocaleString('id-ID'));
                        if (d.bukti_transfer) {
                            let imageUrl = "<?= base_url('uploads/bukti_pencairan/') ?>" + d.bukti_transfer;
                            $('#wrapper_bukti').html(`<a href="${imageUrl}" target="_blank"><img src="${imageUrl}" class="img-fluid rounded" style="max-height: 250px;"></a>`);
                        } else $('#wrapper_bukti').html('<span class="text-danger">Belum ada bukti</span>');
                    } else Swal.fire('Gagal', res.message, 'error');
                },
                error: () => Swal.fire('Gagal', 'Server bermasalah', 'error')
            });
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const countdownElements = document.querySelectorAll(".flash-sale-badge");

        countdownElements.forEach(function(element) {
            const clockSpan = element.querySelector(".countdown-clock");
            if (!clockSpan) return;

            function updateCountdown() {
                const now = new Date();

                // Set waktu target ke pukul 00:00:00 di hari berikutnya
                const tomorrow = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1, 0, 0, 0);
                const distance = tomorrow - now;

                if (distance < 0) {
                    clockSpan.innerHTML = "00:00:00";
                    return;
                }

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Format angka menjadi 2 digit (00:00:00)
                const formatHours = String(hours).padStart(2, '0');
                const formatMinutes = String(minutes).padStart(2, '0');
                const formatSeconds = String(seconds).padStart(2, '0');

                clockSpan.innerHTML = `${formatHours}:${formatMinutes}:${formatSeconds}`;
            }

            // Jalankan fungsi pertama kali agar tidak ada jeda 1 detik saat halaman dimuat
            updateCountdown();

            // Perbarui setiap 1 detik
            setInterval(updateCountdown, 1000);
        });
    });
</script>
<?= $this->endSection(); ?>
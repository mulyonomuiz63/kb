<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?php

use App\Models\UjiansiswaModel;

$UjiansiswaModel = new UjiansiswaModel();
?>

<!-- DATALIST: Daftar memori materi dari Bank Soal untuk Komposisi -->
<datalist id="materi_list">
    <?php if (!empty($sub_materi_bank)): ?>
        <?php foreach ($sub_materi_bank as $sm) : ?>
            <?php if (!empty($sm->sub_materi)): ?>
                <option value="<?= htmlspecialchars($sm->sub_materi); ?>">
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
</datalist>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl pb-20">

        <a href="javascript:void(0);" class="btn btn-primary tambah-pg shadow-lg" style="position: fixed; right: 20px; top: 50%; z-index: 9999; border-radius: 50px;">
            <i class="ki-duotone ki-plus fs-2"></i> Tambah Soal
        </a>

        <form action="<?= base_url('sw-guru/ujian/update-soal/' . encrypt_url($ujian->kode_ujian)); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

            <!-- CARD MASTER UJIAN -->
            <div class="card card-flush shadow-sm mb-5">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="card-label fw-bold text-dark">Edit Data Ujian USKP & Soal</h3>
                    </div>
                    <div class="card-toolbar gap-3">
                        <a href="javascript:void(0);" class="btn btn-light-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bank_soal">
                            <i class="ki-duotone ki-folder fs-2"><span class="path1"></span><span class="path2"></span></i> Ambil Bank Soal
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-9">
                        <div class="col-md-3">
                            <label class="required fs-6 fw-semibold mb-2">Nama Ujian</label>
                            <input type="text" name="nama_ujian" value="<?= $ujian->nama_ujian; ?>" class="form-control form-control-solid" required>
                        </div>
                        <div class="col-md-3">
                            <label class="required fs-6 fw-semibold mb-2">Kelas</label>
                            <select class="form-select form-select-solid" name="kelas" id="pilih_kelas" required>
                                <option value="">Pilih</option>
                                <?php foreach ($guru_kelas as $gk) : ?>
                                    <option value="<?= $gk->kelas; ?>" <?= $ujian->kelas == $gk->kelas ? 'selected' : '' ?>><?= $gk->nama_kelas; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="required fs-6 fw-semibold mb-2">Mapel</label>
                            <select class="form-select form-select-solid" name="mapel" id="pilih_mapel" required>
                                <option value="">Pilih Kelas Terlebih Dahulu</option>
                                <?php foreach ($guru_mapel as $gm) : ?>
                                    <option value="<?= $gm->id_mapel; ?>" <?= $ujian->mapel == $gm->id_mapel ? 'selected' : '' ?>><?= $gm->nama_mapel; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="required fs-6 fw-semibold mb-2">Status Ujian</label>
                            <select class="form-select form-select-solid" name="status_ujian" required>
                                <option value="">Pilih</option>
                                <option value="A" <?= $status_ujian['status'] == 'A' ? 'selected' : '' ?>>Aktif</option>
                                <option value="T" <?= $status_ujian['status'] == 'T' ? 'selected' : '' ?>>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-9 mt-5">
                        <div class="col-md-3">
                            <label class="required fs-6 fw-semibold mb-2">Waktu / Soal (Menit)</label>
                            <input type="number" name="waktu_per_soal" value="<?= $ujian->waktu_per_soal; ?>" class="form-control form-control-solid" required min="1">
                        </div>
                    </div>

                    <div class="separator separator-dashed my-8"></div>

                    <!-- KOMPOSISI MATERI USKP -->
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <div>
                            <h4 class="fw-bold text-dark m-0">Komposisi Sub-Materi (Khusus USKP)</h4>
                            <small class="text-muted">Kelola jatah soal per sub-materi di sini.</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-success" id="tambah-komposisi">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah Materi
                        </button>
                    </div>

                    <div class="bg-light p-5 rounded">
                        <div class="row g-3 mb-2 fw-bold text-muted">
                            <div class="col-md-4">Nama Sub-Materi</div>
                            <div class="col-md-2">Jml Mudah</div>
                            <div class="col-md-2">Jml Sedang</div>
                            <div class="col-md-2">Jml Susah</div>
                            <div class="col-md-2">Aksi</div>
                        </div>
                        <div id="container-komposisi">
                            <?php if (!empty($komposisi_ujian)): ?>
                                <?php foreach ($komposisi_ujian as $idx => $comp): ?>
                                    <div class="row g-3 mb-3 row-komposisi align-items-center">
                                        <div class="col-md-4">
                                            <input type="text" name="nama_sub_materi[]" list="materi_list" value="<?= htmlspecialchars($comp->nama_sub_materi) ?>" class="form-control form-control-solid border-primary" placeholder="Pilih/Ketik Materi..." autocomplete="off">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="jml_mudah[]" value="<?= $comp->jml_mudah ?>" class="form-control form-control-solid" min="0">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="jml_sedang[]" value="<?= $comp->jml_sedang ?>" class="form-control form-control-solid" min="0">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="jml_susah[]" value="<?= $comp->jml_susah ?>" class="form-control form-control-solid" min="0">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-icon btn-light-danger hapus-komposisi" <?= $idx == 0 ? 'disabled' : '' ?>>
                                                <i class="ki-duotone ki-trash fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i></button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="row g-3 mb-3 row-komposisi align-items-center">
                                    <div class="col-md-4">
                                        <input type="text" name="nama_sub_materi[]" list="materi_list" class="form-control form-control-solid border-primary" placeholder="Pilih/Ketik Materi..." autocomplete="off">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="jml_mudah[]" class="form-control form-control-solid" min="0" value="0">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="jml_sedang[]" class="form-control form-control-solid" min="0" value="0">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="jml_susah[]" class="form-control form-control-solid" min="0" value="0">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-icon btn-light-danger hapus-komposisi" disabled>
                                            <i class="ki-duotone ki-trash fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i></button>
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARD ISI SOAL UJIAN -->
            <div class="card card-flush shadow-sm mb-20">
                <div class="card-header">
                    <h3 class="card-title fw-bold">Daftar Isi Soal Ujian</h3>
                </div>
                <div class="card-body">
                    <div id="soal_pg">
                        <?php
                        $no = 1;
                        foreach ($detail_ujian as $q):
                            $pg_1 = substr($q->pg_1, 3);
                            $pg_2 = substr($q->pg_2, 3);
                            $pg_3 = substr($q->pg_3, 3);
                            $pg_4 = substr($q->pg_4, 3);
                            $pg_5 = substr($q->pg_5, 3);

                            $badgeClass = 'bg-secondary';
                            $badgeText = 'Belum Diset';
                            if ($q->jenis_soal == 'E') {
                                $badgeClass = 'badge-light-success text-success';
                                $badgeText = 'Mudah';
                            } elseif ($q->jenis_soal == 'M') {
                                $badgeClass = 'badge-light-warning text-warning';
                                $badgeText = 'Sedang';
                            } elseif ($q->jenis_soal == 'H') {
                                $badgeClass = 'badge-light-danger text-danger';
                                $badgeText = 'Sulit';
                            }
                        ?>
                            <div class="isi_soal mb-8 p-5 border rounded bg-light">
                                <input type="hidden" name="id_detail_ujian[]" value="<?= $q->id_detail_ujian ?>">

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-3 flex-wrap">
                                        <h4 class="fw-bold mb-0 text-dark">Soal No. <span class="nomor-teks"><?= $no++ ?></span></h4>
                                        <!-- Badge Level Kesulitan -->
                                        <span class="badge <?= $badgeClass ?> fw-bold fs-7 label-kesulitan"><?= $badgeText ?></span>
                                        <!-- Badge Sub-Materi (Diberi warna badge-light-info agar tampil elegan) -->
                                        <?php if (!empty($q->sub_materi)): ?>
                                            <span class="badge badge-light-info text-info fw-bold fs-7">
                                                <i class="ki-outline ki-folder fs-8 me-1"></i><?= $q->sub_materi ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <button type="button" class="btn btn-sm btn-light-primary btn-toggle-edit">
                                            <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i> Edit Soal
                                        </button>
                                        <button type="button" class="btn btn-sm btn-icon btn-light-danger hapus-pg">
                                            <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="preview-area mt-4">
                                    <div class="border p-4 bg-white rounded text-gray-800" style="max-height: 100px; overflow: hidden; position: relative;">
                                        <?= $q->nama_soal ?>
                                        <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 30px; background: linear-gradient(transparent, white);"></div>
                                    </div>
                                </div>

                                <div class="edit-area mt-6" style="display: none;">
                                    <div class="row g-5 mb-5">
                                        <div class="col-md-6">
                                            <label class="fw-bold mb-2">Sub-Materi Soal</label>
                                            <select name="sub_materi[]" class="form-select form-select-sm form-select-solid select-sub-materi">
                                                <option value="">Pilih Sub-Materi</option>
                                                <?php if (!empty($komposisi_ujian)): ?>
                                                    <?php foreach ($komposisi_ujian as $comp): ?>
                                                        <option value="<?= htmlspecialchars($comp->nama_sub_materi) ?>" <?= $q->sub_materi == $comp->nama_sub_materi ? 'selected' : '' ?>><?= htmlspecialchars($comp->nama_sub_materi) ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold mb-2">Ubah Kesulitan Soal</label>
                                            <select name="jenis_soal[]" class="form-select form-select-sm form-select-solid select-kesulitan" required>
                                                <option value="">Pilih Kesulitan</option>
                                                <option value="E" <?= $q->jenis_soal == 'E' ? 'selected' : '' ?>>Mudah (Easy)</option>
                                                <option value="M" <?= $q->jenis_soal == 'M' ? 'selected' : '' ?>>Sedang (Medium)</option>
                                                <option value="H" <?= $q->jenis_soal == 'H' ? 'selected' : '' ?>>Sulit (Hard)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group mb-5">
                                        <label class="fw-bold mb-2">Teks Soal</label>
                                        <textarea name="nama_soal[]" class="summernote" required><?= $q->nama_soal ?></textarea>
                                    </div>

                                    <div class="row g-5">
                                        <div class="col-md-4">
                                            <div class="input-group input-group-solid">
                                                <span class="input-group-text">A</span>
                                                <input type="text" name="pg_1[]" value="<?= htmlspecialchars($pg_1) ?>" class="form-control" placeholder="Opsi A">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group input-group-solid">
                                                <span class="input-group-text">B</span>
                                                <input type="text" name="pg_2[]" value="<?= htmlspecialchars($pg_2) ?>" class="form-control" placeholder="Opsi B">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group input-group-solid">
                                                <span class="input-group-text">C</span>
                                                <input type="text" name="pg_3[]" value="<?= htmlspecialchars($pg_3) ?>" class="form-control" placeholder="Opsi C">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group input-group-solid">
                                                <span class="input-group-text">D</span>
                                                <input type="text" name="pg_4[]" value="<?= htmlspecialchars($pg_4) ?>" class="form-control" placeholder="Opsi D">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group input-group-solid">
                                                <span class="input-group-text">E</span>
                                                <input type="text" name="pg_5[]" value="<?= htmlspecialchars($pg_5) ?>" class="form-control" placeholder="Opsi E">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group input-group-solid border border-primary bg-light-primary">
                                                <span class="input-group-text bg-primary text-white">Jawaban</span>
                                                <input type="text" name="jawaban[]" value="<?= htmlspecialchars($q->jawaban) ?>" class="form-control" placeholder="Contoh: A" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-5">
                                        <label class="fw-bold mb-2">Penjelasan (Opsional)</label>
                                        <textarea name="penjelasan[]" class="summernote"><?= $q->penjelasan ?></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- TOMBOL SUBMIT MELAYANG DI BAWAH -->
            <div class="position-fixed bottom-0 start-0 w-100 bg-white border-top py-4 shadow-lg text-center" style="z-index: 1050;">
                <button type="submit" class="btn btn-primary px-10 fw-bold fs-5 rounded-pill shadow">
                    <i class="ki-duotone ki-save-2 fs-2"><span class="path1"></span><span class="path2"></span></i> Simpan Perubahan Ujian
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL BANK SOAL -->
<div class="modal fade" id="bank_soal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Ambil dari Bank Soal</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body">
                <div class="row g-9 mb-8">
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-2">Filter Kategori</label>
                        <select class="form-select form-select-solid" id="id_kategori">
                            <option value="">Semua Kategori</option>
                            <?php foreach ($kategori ?? [] as $rows) : ?>
                                <option value="<?= $rows->id_kategori; ?>"><?= $rows->nama_kategori; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-2">Filter Sub-Materi</label>
                        <select class="form-select form-select-solid" id="filter_sub_materi">
                            <option value="">Semua Sub-Materi</option>
                            <?php foreach ($sub_materi_bank as $sm) : ?>
                                <?php if (!empty($sm->sub_materi)) : ?>
                                    <option value="<?= $sm->sub_materi; ?>"><?= $sm->sub_materi; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-2">Cari Nama Soal</label>
                        <div class="position-relative d-flex align-items-center">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4"></i>
                            <input type="text" id="nama_soal" class="form-control form-control-solid ps-12" placeholder="Kata kunci...">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 shadow-sm cursor-pointer" id="table">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0 bg-light">
                                <th class="min-w-100px text-center">Kesulitan</th>
                                <th class="min-w-150px">Kategori</th>
                                <th class="min-w-150px">Sub-Materi</th>
                                <th class="min-w-200px">Detail Isi Soal</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" class="btn btn-light-primary" data-bs-dismiss="modal">Selesai Memilih</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL NOTIFIKASI ERROR -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold text-white">Terjadi Kesalahan Sistem</h5>
                <div class="btn btn-icon btn-sm btn-active-light-danger" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-flex align-items-center p-5 mb-0">
                    <i class="ki-duotone ki-shield-cross fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-dark">Gagal Memuat Data</h4>
                        <span id="errorMessage" class="text-gray-700 fw-semibold">Pesan error akan muncul di sini...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="location.reload()">Muat Ulang Halaman</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>';

    function updateCsrfToken(newToken) {
        if (newToken && newToken !== csrfHash) {
            csrfHash = newToken;
            $('input[name="' + csrfName + '"]').val(newToken);
        }
    }

    $(document).ready(function() {
        var t = $('#table').DataTable({
            "select": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "bLengthChange": false,
            "bInfo": false,
            "bAutoWidth": false,
            "searching": false,
            "ajax": {
                "url": "<?php echo site_url('sw-guru/ujian/get-bank-soal') ?>",
                "type": "POST",
                "data": function(d) {
                    d[csrfName] = $('input[name="' + csrfName + '"]').val();
                    d.nama_soal = $('#nama_soal').val();
                    d.id_kategori = $('#id_kategori').val();
                    d.sub_materi = $('#filter_sub_materi').val();
                },
                "dataSrc": function(json) {
                    if (json.token) updateCsrfToken(json.token);
                    return json.data;
                },
                "error": function(xhr) {
                    let pesanError = "Terjadi kesalahan pada server.";
                    if (xhr.status === 403) {
                        pesanError = "Error 403 (Forbidden): Token CSRF kedaluwarsa.";
                    } else if (xhr.status === 500) {
                        pesanError = "Error 500 (Internal Server Error): Masalah pada Controller.";
                    }
                    $('#errorMessage').text(pesanError);
                    var myModal = new bootstrap.Modal(document.getElementById('errorModal'));
                    myModal.show();
                }
            },
            "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                    "className": 'text-center'
                },
                {
                    "targets": [1],
                    "orderable": false,
                    "className": 'text-center'
                },
                {
                    "targets": [2],
                    "orderable": false
                },
                {
                    "targets": [3],
                    "orderable": false
                },
                {
                    "targets": [4],
                    "orderable": false
                }
            ]
        });

        $('#nama_soal').on('keyup', function() {
            t.draw();
        });
        $('#id_kategori').on('change', function() {
            t.draw();
        });
        $('#filter_sub_materi').on('change', function() {
            t.draw();
        });

        function initSummernote() {
            $('.summernote').each(function() {
                if (!$(this).next().hasClass('note-editor')) {
                    $(this).summernote({
                        height: 120,
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'underline', 'clear']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['table', ['table']],
                            ['insert', ['link', 'picture', 'video']],
                            ['view', ['fullscreen', 'help']]
                        ]
                    });
                }
            });
        }
        setInterval(initSummernote, 2000);

        // --- MANAJEMEN KOMPOSISI MATERI ---
        $('#tambah-komposisi').click(function() {
            var row = `
            <div class="row g-3 mb-3 row-komposisi align-items-center">
                <div class="col-md-4">
                    <input type="text" name="nama_sub_materi[]" list="materi_list" class="form-control form-control-solid border-primary" placeholder="Pilih/Ketik Materi..." autocomplete="off">
                </div>
                <div class="col-md-2">
                    <input type="number" name="jml_mudah[]" class="form-control form-control-solid" min="0" value="0">
                </div>
                <div class="col-md-2">
                    <input type="number" name="jml_sedang[]" class="form-control form-control-solid" min="0" value="0">
                </div>
                <div class="col-md-2">
                    <input type="number" name="jml_susah[]" class="form-control form-control-solid" min="0" value="0">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-icon btn-light-danger hapus-komposisi">
                        <i class="ki-duotone ki-trash fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </button>
                </div>
            </div>`;
            $('#container-komposisi').append(row);
            updateAllSubMateriDropdowns();
        });

        $('#container-komposisi').on('click', '.hapus-komposisi', function() {
            $(this).closest('.row-komposisi').remove();
            updateAllSubMateriDropdowns();
        });

        // Sinkronisasi input komposisi dengan pilihan sub-materi di soal
        $(document).on('input', 'input[name="nama_sub_materi[]"]', function() {
            updateAllSubMateriDropdowns();
        });

        function buildSubMateriOptions(selectedMateri = '') {
            let options = '<option value="">Pilih Sub-Materi</option>';
            $('input[name="nama_sub_materi[]"]').each(function() {
                let val = $(this).val().trim();
                if (val !== '') {
                    let isSelected = (selectedMateri && val.toLowerCase() === selectedMateri.toLowerCase()) ? 'selected' : '';
                    options += `<option value="${val}" ${isSelected}>${val}</option>`;
                }
            });
            return options;
        }

        function updateAllSubMateriDropdowns() {
            $('.select-sub-materi').each(function() {
                let currentVal = $(this).val();
                let newOptions = buildSubMateriOptions(currentVal);
                $(this).html(newOptions);
            });
        }

        // --- TAMBAH SOAL MANUAL ---
        var no_soal = <?= count($detail_ujian) + 1 ?>;

        $('.tambah-pg').click(function() {
            let optionsHtml = buildSubMateriOptions();

            const pg = `
            <div class="isi_soal mb-8 p-5 border rounded bg-light border-primary">
                <input type="hidden" name="id_detail_ujian[]" value="">
                
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-4">
                        <h4 class="fw-bold mb-0 text-primary">Soal No. <span class="nomor-teks">${no_soal}</span> (Baru)</h4>
                        <span class="badge bg-secondary fw-bold fs-7 label-kesulitan">Belum Diset</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button type="button" class="btn btn-sm btn-light-success btn-toggle-edit">
                            <i class="ki-duotone ki-check fs-2"></i> Tutup Edit
                        </button>
                        <button type="button" class="btn btn-sm btn-icon btn-light-danger hapus-pg">
                            <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span></i>
                        </button>
                    </div>
                </div>

                <div class="preview-area mt-4" style="display: none;">
                    <div class="border p-4 bg-white rounded text-gray-800 text-muted fst-italic">
                        Soal masih kosong...
                    </div>
                </div>
                
                <div class="edit-area mt-6">
                    <div class="row g-5 mb-5">
                        <div class="col-md-6">
                            <label class="fw-bold mb-2">Sub-Materi Soal</label>
                            <select name="sub_materi[]" class="form-select form-select-sm form-select-solid select-sub-materi">
                                ${optionsHtml}
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-2">Pilih Kesulitan Soal</label>
                            <select name="jenis_soal[]" class="form-select form-select-sm form-select-solid select-kesulitan" required>
                                <option value="">Pilih Kesulitan</option>
                                <option value="E">Mudah (Easy)</option>
                                <option value="M">Sedang (Medium)</option>
                                <option value="H">Sulit (Hard)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-5">
                        <label class="fw-bold mb-2">Teks Soal</label>
                        <textarea name="nama_soal[]" class="summernote" required></textarea>
                    </div>

                    <div class="row g-5">
                        ${['A', 'B', 'C', 'D', 'E'].map((opt, i) => `
                            <div class="col-md-4">
                                <div class="input-group input-group-solid">
                                    <span class="input-group-text">${opt}</span>
                                    <input type="text" name="pg_${i+1}[]" class="form-control" placeholder="Opsi ${opt}">
                                </div>
                            </div>
                        `).join('')}
                        <div class="col-md-4">
                            <div class="input-group input-group-solid border border-primary bg-light-primary">
                                <span class="input-group-text bg-primary text-white">Jawaban</span>
                                <input type="text" name="jawaban[]" class="form-control" placeholder="Contoh: A" required>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <label class="fw-bold mb-2">Penjelasan (Opsional)</label>
                        <textarea name="penjelasan[]" class="summernote"></textarea>
                    </div>
                </div>
            </div>`;
            $('#soal_pg').append(pg);
            no_soal++;
            initSummernote();
        });

        // --- TAMBAH DARI BANK SOAL (KLIK PADA BARIS TABEL) ---
        $('#table tbody').on('click', 'tr', function() {
            var row = $(this);
            var id_bank_soal = row.find('[data-id_bank_soal]').data('id_bank_soal');

            if (!id_bank_soal) return;

            // Berikan efek visual baris terpilih (warna biru)
            row.addClass('bg-light-primary');

            var currentHash = $('input[name="' + csrfName + '"]').val();

            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-guru/ujian/tambah-bank-soal') ?>",
                data: {
                    id_bank_soal: id_bank_soal,
                    [csrfName]: currentHash
                },
                dataType: 'JSON',
                success: function(data) {
                    if (data.token) updateCsrfToken(data.token);

                    let bClass = 'bg-secondary';
                    let bText = 'Belum Diset';
                    if (data.jenis_soal === 'E') {
                        bClass = 'badge-light-success text-success';
                        bText = 'Mudah';
                    } else if (data.jenis_soal === 'M') {
                        bClass = 'badge-light-warning text-warning';
                        bText = 'Sedang';
                    } else if (data.jenis_soal === 'H') {
                        bClass = 'badge-light-danger text-danger';
                        bText = 'Sulit';
                    }

                    let optionsHtml = buildSubMateriOptions(data.sub_materi);

                    var pg = `
            <div class="isi_soal mb-8 p-5 border rounded bg-light border-info">
                <input type="hidden" name="id_detail_ujian[]" value="">
                
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <h4 class="fw-bold mb-0 text-info">Soal No. <span class="nomor-teks">${no_soal}</span> (Bank Soal)</h4>
                        
                        <!-- Badge Level Kesulitan -->
                        <span class="badge ${bClass} fw-bold fs-7 label-kesulitan">${bText}</span>
                        
                        <!-- Badge Sub-Materi (Otomatis tampil jika ada isinya) -->
                        ${data.sub_materi ? `<span class="badge badge-light-info text-info fw-bold fs-7"><i class="ki-outline ki-folder fs-8 me-1"></i>${data.sub_materi}</span>` : '<span class="badge badge-light-secondary text-muted fw-bold fs-7">-</span>'}
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button type="button" class="btn btn-sm btn-light-primary btn-toggle-edit">
                            <i class="ki-duotone ki-pencil fs-2"></i> Edit Soal
                        </button>
                        <button type="button" class="btn btn-sm btn-icon btn-light-danger hapus-pg">
                            <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span></i>
                        </button>
                    </div>
                </div>

                <div class="preview-area mt-4">
                    <div class="border p-4 bg-white rounded text-gray-800" style="max-height: 100px; overflow: hidden; position: relative;">
                        ${data.nama_soal}
                        <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 30px; background: linear-gradient(transparent, white);"></div>
                    </div>
                </div>
                
                <div class="edit-area mt-6" style="display: none;">
                    <div class="row g-5 mb-5">
                        <div class="col-md-6">
                            <label class="fw-bold mb-2">Sub-Materi Soal</label>
                            <select name="sub_materi[]" class="form-select form-select-sm form-select-solid select-sub-materi">
                                ${optionsHtml}
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-2">Ubah Kesulitan Soal</label>
                            <select name="jenis_soal[]" class="form-select form-select-sm form-select-solid select-kesulitan" required>
                                <option value="E" ${data.jenis_soal === 'E' ? 'selected' : ''}>Mudah (Easy)</option>
                                <option value="M" ${data.jenis_soal === 'M' ? 'selected' : ''}>Sedang (Medium)</option>
                                <option value="H" ${data.jenis_soal === 'H' ? 'selected' : ''}>Sulit (Hard)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-5">
                        <label class="fw-bold mb-2">Teks Soal</label>
                        <textarea name="nama_soal[]" class="summernote" required>${data.nama_soal}</textarea>
                    </div>

                    <div class="row g-5">
                        ${[1,2,3,4,5].map(i => `
                            <div class="col-md-4">
                                <div class="input-group input-group-solid">
                                    <span class="input-group-text">${String.fromCharCode(64+i)}</span>
                                    <input type="text" name="pg_${i}[]" value="${data['pg_'+i] ? data['pg_'+i].substring(3) : ''}" class="form-control">
                                </div>
                            </div>
                        `).join('')}
                        <div class="col-md-4">
                            <div class="input-group input-group-solid border border-primary bg-light-primary">
                                <span class="input-group-text bg-primary text-white">Jawaban</span>
                                <input type="text" name="jawaban[]" value="${data.jawaban}" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <label class="fw-bold mb-2">Penjelasan</label>
                        <textarea name="penjelasan[]" class="summernote">${data.penjelasan}</textarea>
                    </div>
                </div>
            </div>`;

                    $('#soal_pg').append(pg);
                    no_soal++;
                    initSummernote();

                    // Notifikasi Toast Sukses di Pojok Kanan Atas
                    Swal.fire({
                        text: "Soal berhasil ditambahkan ke ujian!",
                        icon: "success",
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                }
            });
        });

        // TOGGLE COLLAPSE / EXPAND EDIT SOAL
        $('#soal_pg').on('click', '.btn-toggle-edit', function() {
            let container = $(this).closest('.isi_soal');
            let formArea = container.find('.edit-area');
            let previewArea = container.find('.preview-area');

            if (formArea.is(':visible')) {
                formArea.slideUp();
                previewArea.slideDown();
                $(this).removeClass('btn-light-success').addClass('btn-light-primary');
                $(this).html('<i class="ki-duotone ki-pencil fs-2"></i> Edit Soal');
            } else {
                formArea.slideDown();
                previewArea.slideUp();
                $(this).removeClass('btn-light-primary').addClass('btn-light-success');
                $(this).html('<i class="ki-duotone ki-check fs-2"></i> Tutup Edit');
            }
        });

        // UPDATE WARNA BADGE REAL-TIME
        $('#soal_pg').on('change', '.select-kesulitan', function() {
            let container = $(this).closest('.isi_soal');
            let badge = container.find('.label-kesulitan');
            let val = $(this).val();

            badge.removeClass('bg-secondary badge-light-success badge-light-warning badge-light-danger text-success text-warning text-danger');
            if (val === 'E') {
                badge.addClass('badge-light-success text-success').text('Mudah');
            } else if (val === 'M') {
                badge.addClass('badge-light-warning text-warning').text('Sedang');
            } else if (val === 'H') {
                badge.addClass('badge-light-danger text-danger').text('Sulit');
            } else {
                badge.addClass('bg-secondary').text('Belum Diset');
            }
        });

        // HAPUS SOAL
        $('#soal_pg').on('click', '.hapus-pg', function() {
            $(this).closest('.isi_soal').remove();
            no_soal = 1;
            $('#soal_pg .isi_soal').each(function() {
                $(this).find('.nomor-teks').text(no_soal);
                no_soal++;
            });
        });
    });

    // SISTEM PENGAMBILAN MAPEL BERDASARKAN KELAS
    $(document).ready(function() {
        $('#pilih_kelas').on('change', function() {
            let idKelas = $(this).val();
            let mapelSelect = $('#pilih_mapel');
            mapelSelect.empty();
            mapelSelect.append('<option value="">Pilih</option>');

            let csrfToken = $('input[name="' + csrfName + '"]').val();

            if (idKelas !== '') {
                $.ajax({
                    type: 'POST',
                    url: '<?= base_url('sw-guru/ujian/getMapelByKelas') ?>',
                    data: {
                        id_kelas: idKelas,
                        [csrfName]: csrfToken
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.token) updateCsrfToken(response.token);
                        let listMapel = response.mapel || response;
                        if (listMapel.length > 0) {
                            $.each(listMapel, function(index, data) {
                                mapelSelect.append('<option value="' + data.mapel + '">' + data.nama_mapel + '</option>');
                            });
                        } else {
                            mapelSelect.append('<option value="">Tidak ada mapel di kelas ini</option>');
                        }
                    }
                });
            }
        });
    });
</script>
<?= $this->endSection(); ?>
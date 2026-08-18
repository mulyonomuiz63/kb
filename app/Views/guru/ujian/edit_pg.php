<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?php
use App\Models\UjiansiswaModel;
$UjiansiswaModel = new UjiansiswaModel();
?>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <!-- Tambahan class pb-20 agar card terakhir tidak tertutup oleh tombol melayang di bawah -->
    <div id="kt_app_content_container" class="app-container container-xxl pb-20">

        <a href="javascript:void(0);" class="btn btn-primary tambah-pg shadow-lg" style="position: fixed; right: 20px; top: 50%; z-index: 9999; border-radius: 50px;">
            <i class="ki-duotone ki-plus fs-2"></i> Tambah Soal
        </a>

        <!-- ACTION MENGARAH KE FUNGSI UPDATE DI CONTROLLER -->
        <form action="<?= base_url('sw-guru/ujian/update-soal/' . encrypt_url($ujian->kode_ujian)); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

            <!-- CARD MASTER UJIAN -->
            <div class="card card-flush shadow-sm mb-5">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="card-label fw-bold text-dark">Edit Data Ujian & Soal Pilihan Ganda</h3>
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

                    <!-- KOLOM SETTING SOAL -->
                    <div class="row g-9 mt-5">
                        <div class="col-md-3">
                            <label class="required fs-6 fw-semibold mb-2">Jml Soal Mudah</label>
                            <input type="number" name="jml_mudah" value="<?= $ujian->jml_mudah; ?>" class="form-control form-control-solid" required min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="required fs-6 fw-semibold mb-2">Jml Soal Sedang</label>
                            <input type="number" name="jml_sedang" value="<?= $ujian->jml_sedang; ?>" class="form-control form-control-solid" required min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="required fs-6 fw-semibold mb-2">Jml Soal Susah</label>
                            <input type="number" name="jml_susah" value="<?= $ujian->jml_susah; ?>" class="form-control form-control-solid" required min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="required fs-6 fw-semibold mb-2">Waktu / Soal (Menit)</label>
                            <input type="number" name="waktu_per_soal" value="<?= $ujian->waktu_per_soal; ?>" class="form-control form-control-solid" required min="1">
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
                        
                        <!-- MENGAMBIL DATA SOAL LAMA -->
                        <?php 
                        $no = 1;
                        foreach ($detail_ujian as $q):
                            // Membuang awalan "A. " "B. " agar di inputan bersih
                            $pg_1 = substr($q->pg_1, 3);
                            $pg_2 = substr($q->pg_2, 3);
                            $pg_3 = substr($q->pg_3, 3);
                            $pg_4 = substr($q->pg_4, 3);
                            $pg_5 = substr($q->pg_5, 3);

                            // Pemetaan Badge Kesulitan
                            $badgeClass = 'bg-secondary';
                            $badgeText = 'Belum Diset';
                            if($q->jenis_soal == 'E'){ $badgeClass = 'badge-light-success text-success'; $badgeText = 'Mudah';}
                            elseif($q->jenis_soal == 'M'){ $badgeClass = 'badge-light-warning text-warning'; $badgeText = 'Sedang';}
                            elseif($q->jenis_soal == 'H'){ $badgeClass = 'badge-light-danger text-danger'; $badgeText = 'Sulit';}
                        ?>
                            <div class="isi_soal mb-8 p-5 border rounded bg-light">
                                <input type="hidden" name="id_detail_ujian[]" value="<?= $q->id_detail_ujian ?>">
                                
                                <!-- HEADER SOAL (SELALU TAMPIL) -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-4">
                                        <h4 class="fw-bold mb-0">Soal No. <span class="nomor-teks"><?= $no++ ?></span></h4>
                                        <span class="badge <?= $badgeClass ?> fw-bold fs-7 label-kesulitan"><?= $badgeText ?></span>
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

                                <!-- PREVIEW SOAL (TAMPIL SAAT COLLAPSED) -->
                                <div class="preview-area mt-4">
                                    <div class="border p-4 bg-white rounded text-gray-800" style="max-height: 100px; overflow: hidden; position: relative;">
                                        <?= $q->nama_soal ?>
                                        <!-- Efek gradien memudar ke bawah -->
                                        <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 30px; background: linear-gradient(transparent, white);"></div>
                                    </div>
                                </div>
                                
                                <!-- AREA EDIT FORM (DISEMBUNYIKAN SECARA DEFAULT) -->
                                <div class="edit-area mt-6" style="display: none;">
                                    <div class="form-group mb-5">
                                        <label class="fw-bold mb-2">Ubah Kesulitan Soal</label>
                                        <select name="jenis_soal[]" class="form-select form-select-sm form-select-solid select-kesulitan" style="width: 200px;" required>
                                            <option value="">Pilih Kesulitan</option>
                                            <option value="E" <?= $q->jenis_soal == 'E' ? 'selected' : '' ?>>Mudah (Easy)</option>
                                            <option value="M" <?= $q->jenis_soal == 'M' ? 'selected' : '' ?>>Sedang (Medium)</option>
                                            <option value="H" <?= $q->jenis_soal == 'H' ? 'selected' : '' ?>>Sulit (Hard)</option>
                                        </select>
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

            <!-- TOMBOL SUBMIT MELAYANG DI BAWAH (FLOATING FOOTER) -->
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
                    <div class="col-md-8">
                        <label class="fs-6 fw-semibold mb-2">Cari Nama Soal</label>
                        <div class="position-relative d-flex align-items-center">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4"></i>
                            <input type="text" id="nama_soal" class="form-control form-control-solid ps-12" placeholder="Ketik kata kunci soal...">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 shadow-sm" id="table">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0 bg-light">
                                <th class="w-50px px-3 text-center">Pilih</th>
                                <th class="min-w-125px text-center">Kesulitan</th> 
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

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
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
                    d[csrfName] = csrfHash;
                    d.nama_soal = $('#nama_soal').val();
                    d.id_kategori = $('#id_kategori').val();
                },
                "dataSrc": function(json) {
                    if (json.token) updateCsrfToken(json.token);
                    return json.data;
                },
                "error": function(xhr) {
                    if (xhr.status === 403) {
                        location.reload();
                    }
                }
            },
            "columnDefs": [
                { "targets": [0], "orderable": false, "className": 'text-center' },
                { "targets": [1], "orderable": false, "className": 'text-center' },
                { "targets": [2], "orderable": false }
            ]
        });

        $('#nama_soal').on('keyup', function() { t.draw(); });
        $('#id_kategori').on('change', function() { t.draw(); });

        // Summernote Global Initializer
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
                        ],
                        callbacks: {
                            onImageUpload: function(image) { uploadImage(image[0], this); },
                            onMediaDelete: function(target) { deleteImage(target[0].src); }
                        }
                    });
                }
            });
        }

        setInterval(initSummernote, 2000);

        function uploadImage(image, which_sum) {
            var data = new FormData();
            data.append(csrfName, csrfHash);
            data.append("image", image);
            $.ajax({
                url: "<?= base_url('sw-guru/ujian/upload-summernote') ?>",
                cache: false,
                contentType: false,
                processData: false,
                data: data,
                type: "POST",
                success: function(response) {
                    var res = JSON.parse(response);
                    if (res.token) updateCsrfToken(res.token);
                    $(which_sum).summernote("insertImage", res.url);
                }
            });
        }

        function deleteImage(src) {
            $.ajax({
                url: "<?= base_url('sw-guru/ujian/delete-image') ?>",
                type: "POST",
                data: { [csrfName]: csrfHash, src: src },
                dataType: "JSON",
                success: function(response) {
                    if (response.token) updateCsrfToken(response.token);
                }
            });
        }

        // FUNGSI TOGGLE COLLAPSE/EXPAND SOAL
        $('#soal_pg').on('click', '.btn-toggle-edit', function() {
            let container = $(this).closest('.isi_soal');
            let formArea = container.find('.edit-area');
            let previewArea = container.find('.preview-area');

            if (formArea.is(':visible')) {
                // Sedang Edit, klik untuk Tutup
                formArea.slideUp();
                previewArea.slideDown();
                $(this).removeClass('btn-light-success').addClass('btn-light-primary');
                $(this).html('<i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i> Edit Soal');
            } else {
                // Sedang Tutup, klik untuk Edit
                formArea.slideDown();
                previewArea.slideUp();
                $(this).removeClass('btn-light-primary').addClass('btn-light-success');
                $(this).html('<i class="ki-duotone ki-check fs-2"></i> Tutup Edit');
            }
        });

        // FUNGSI UPDATE WARNA BADGE REAL-TIME SAAT DROPDOWN KESULITAN DIUBAH
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

        // Variabel nomor soal menyesuaikan dengan jumlah data soal yang sudah ada
        var no_soal = <?= count($detail_ujian) + 1 ?>;

        // TAMBAH SOAL MANUAL (Otomatis Terbuka Mode Edit Karena Teks Masih Kosong)
        $('.tambah-pg').click(function() {
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
                    <div class="form-group mb-5">
                        <label class="fw-bold mb-2">Pilih Kesulitan Soal</label>
                        <select name="jenis_soal[]" class="form-select form-select-sm form-select-solid select-kesulitan" style="width: 200px;" required>
                            <option value="">Pilih Kesulitan</option>
                            <option value="E">Mudah (Easy)</option>
                            <option value="M">Sedang (Medium)</option>
                            <option value="H">Sulit (Hard)</option>
                        </select>
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
        });

        // TAMBAH DARI BANK SOAL (Otomatis Tertutup Preview)
        $('#table').on('click', 'input#tambahSoal', function() {
            if ($(this).is(':checked')) {
                var id_bank_soal = $(this).data('id_bank_soal');
                var currentHash = $('input[name="' + csrfName + '"]').val();

                let postData = { id_bank_soal: id_bank_soal };
                postData[csrfName] = currentHash;

                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('sw-guru/ujian/tambah-bank-soal') ?>",
                    data: postData,
                    dataType: 'JSON',
                    success: function(data) {
                        if (data.token) updateCsrfToken(data.token);

                        let bClass = 'bg-secondary';
                        let bText = 'Belum Diset';
                        if(data.jenis_soal === 'E'){ bClass = 'badge-light-success text-success'; bText = 'Mudah';}
                        else if(data.jenis_soal === 'M'){ bClass = 'badge-light-warning text-warning'; bText = 'Sedang';}
                        else if(data.jenis_soal === 'H'){ bClass = 'badge-light-danger text-danger'; bText = 'Sulit';}

                        var pg = `
                        <div class="isi_soal mb-8 p-5 border rounded bg-light border-info">
                            <input type="hidden" name="id_detail_ujian[]" value="">
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-4">
                                    <h4 class="fw-bold mb-0 text-info">Soal No. <span class="nomor-teks">${no_soal}</span> (Bank Soal)</h4>
                                    <span class="badge ${bClass} fw-bold fs-7 label-kesulitan">${bText}</span>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <button type="button" class="btn btn-sm btn-light-primary btn-toggle-edit">
                                        <i class="ki-duotone ki-pencil fs-2"></i> Edit Soal
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon btn-light-danger hapus-pg">
                                        <i class="ki-duotone ki-trash fs-2"></i>
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
                                <div class="form-group mb-5">
                                    <label class="fw-bold mb-2">Ubah Kesulitan Soal</label>
                                    <select name="jenis_soal[]" class="form-select form-select-sm form-select-solid select-kesulitan" style="width: 200px;" required>
                                        <option value="E" ${data.jenis_soal === 'E' ? 'selected' : ''}>Mudah (Easy)</option>
                                        <option value="M" ${data.jenis_soal === 'M' ? 'selected' : ''}>Sedang (Medium)</option>
                                        <option value="H" ${data.jenis_soal === 'H' ? 'selected' : ''}>Sulit (Hard)</option>
                                    </select>
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
                    }
                });
            }
        });

        // MENGHAPUS BLOK SOAL & UPDATE NOMOR URUT
        $('#soal_pg').on('click', '.hapus-pg', function() {
            $(this).closest('.isi_soal').remove();
            
            // Update Nomor Urut Dinamis setelah ada yang dihapus
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
                let postData = { id_kelas: idKelas };
                postData[csrfName] = csrfToken;

                $.ajax({
                    type: 'POST',
                    url: '<?= base_url('sw-guru/ujian/getMapelByKelas') ?>',
                    data: postData,
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.token) $('input[name="' + csrfName + '"]').val(response.token);
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
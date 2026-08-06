<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <a href="javascript:void(0);" class="btn btn-primary tambah-pg shadow-sm" style="position: fixed; right: 20px; top: 50%; z-index: 9999; border-radius: 50px;">
            <i class="ki-duotone ki-plus fs-2"></i> Tambah Soal
        </a>

        <form action="<?= base_url('sw-guru/ujian/store'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

            <div class="card card-flush shadow-sm mb-5">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="card-label fw-bold text-dark">Ujian Pilihan Ganda</h3>
                    </div>
                    <div class="card-toolbar gap-3">
                        <a href="javascript:void(0);" class="btn btn-light-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bank_soal">
                            <i class="ki-duotone ki-folder fs-2"><span class="path1"></span><span class="path2"></span></i> Bank Soal
                        </a>
                        <!-- <a href="javascript:void(0);" class="btn btn-light-success btn-sm" data-bs-toggle="modal" data-bs-target="#excel_ujian">
                            <i class="ki-duotone ki-file-up fs-2"><span class="path1"></span><span class="path2"></span></i> Import Excel
                        </a> -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-9">
                        <div class="col-md-3">
                            <label class="required fs-6 fw-semibold mb-2">Nama Ujian</label>
                            <input type="text" name="nama_ujian" class="form-control form-control-solid" required>
                        </div>
                        <div class="col-md-3">
                            <label class="required fs-6 fw-semibold mb-2">Kelas</label>
                            <select class="form-select form-select-solid" name="kelas" id="pilih_kelas" required>
                                <option value="">Pilih</option>
                                <?php foreach ($guru_kelas as $gk) : ?>
                                    <option value="<?= $gk->kelas; ?>"><?= $gk->nama_kelas; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="required fs-6 fw-semibold mb-2">Mapel</label>
                            <select class="form-select form-select-solid" name="mapel" id="pilih_mapel" required>
                                <option value="">Pilih Kelas Terlebih Dahulu</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="required fs-6 fw-semibold mb-2">Status Ujian</label>
                            <select class="form-select form-select-solid" name="status_ujian" required>
                                <option value="">Pilih</option>
                                <option value="A">Aktif</option>
                                <option value="T">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-flush shadow-sm">
                <div class="card-header">
                    <h3 class="card-title fw-bold">Soal Ujian</h3>
                </div>
                <div class="card-body">
                    <div id="soal_pg">
                    </div>

                    <div class="separator separator-dashed my-10"></div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-10">
                            <i class="ki-duotone ki-save-2 fs-2"><span class="path1"></span><span class="path2"></span></i> Submit Ujian
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="excel_ujian" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="<?= base_url('sw-guru/ujian/import-soal-excel'); ?>" method="POST" enctype="multipart/form-data" class="w-100">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Import Soal via Excel</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row g-9 mb-5">
                        <div class="col-md-4">
                            <label class="required fs-6 fw-semibold mb-2">Nama Ujian / Quiz</label>
                            <input type="text" name="e_nama_ujian" class="form-control form-control-solid" required>
                        </div>
                        <div class="col-md-4">
                            <label class="required fs-6 fw-semibold mb-2">Kelas</label>
                            <select class="form-select form-select-solid" name="e_kelas" required>
                                <option value="">Pilih</option>
                                <?php foreach ($guru_kelas as $gk) : ?>
                                    <option value="<?= $gk->id_kelas; ?>"><?= $gk->nama_kelas; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="required fs-6 fw-semibold mb-2">Mapel</label>
                            <select class="form-select form-select-solid" name="e_mapel" required>
                                <option value="">Pilih</option>
                                <?php foreach ($guru_mapel as $gm) : ?>
                                    <option value="<?= $gm->id_mapel; ?>"><?= $gm->nama_mapel; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row g-9">
                        <div class="col-md-6">
                            <label class="fs-6 fw-semibold mb-2">File Excel</label>
                            <input type="file" name="excel" class="form-control form-control-solid" accept=".xls, .xlsx" required>
                        </div>
                        <div class="col-md-6 text-center">
                            <label class="fs-6 fw-semibold mb-2 d-block">Template</label>
                            <a href="<?= base_url('sw-guru/ujian/download-template'); ?>" class="btn btn-success">
                                <i class="ki-duotone ki-cloud-download fs-2"><span class="path1"></span><span class="path2"></span></i> Download Template
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Start Import</button>
                </div>
            </div>
        </form>
    </div>
</div>

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
                            <?php foreach ($kategori as $rows) : ?>
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
        // DataTable Bank Soal
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
            "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                    "className": 'text-center'
                },
                {
                    "targets": [1],
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
                            onImageUpload: function(image) {
                                uploadImage(image[0], this);
                            },
                            onMediaDelete: function(target) {
                                deleteImage(target[0].src);
                            }
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
                data: {
                    [csrfName]: csrfHash,
                    src: src
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.token) updateCsrfToken(response.token);
                }
            });
        }

        var no_soal = 1;

        // TAMBAH SOAL MANUAL
        $('.tambah-pg').click(function() {
            const pg = `
            <div class="isi_soal mb-10 p-5 border rounded bg-light-neutral">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h4 class="fw-bold">Soal No. ${no_soal}</h4>
                    <button type="button" class="btn btn-sm btn-icon btn-light-danger hapus-pg">
                        <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span></i>
                    </button>
                </div>
                <div class="form-group mb-5">
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
                        <div class="input-group input-group-solid border border-primary">
                            <span class="input-group-text bg-primary text-white">Jawaban</span>
                            <input type="text" name="jawaban[]" class="form-control" placeholder="Contoh: A" required>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <label class="fw-bold mb-2">Penjelasan</label>
                    <textarea name="penjelasan[]" class="summernote"></textarea>
                </div>
            </div>`;
            $('#soal_pg').append(pg);
            no_soal++;
        });

        // TAMBAH DARI BANK SOAL
        $('#table').on('click', 'input#tambahSoal', function() {
            if ($(this).is(':checked')) {
                var id_bank_soal = $(this).data('id_bank_soal');
                
                // Ambil token CSRF terbaru langsung dari input form agar tidak kedaluwarsa
                var currentHash = $('input[name="' + csrfName + '"]').val();

                let postData = {
                    id_bank_soal: id_bank_soal
                };
                postData[csrfName] = currentHash;

                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('sw-guru/ujian/tambah-bank-soal') ?>",
                    data: postData,
                    dataType: 'JSON',
                    success: function(data) {
                        if (data.token) updateCsrfToken(data.token);
                        var pg = `
                        <div class="isi_soal mb-10 p-5 border rounded bg-light-primary">
                            <div class="d-flex justify-content-between align-items-center mb-5">
                                <h4 class="fw-bold text-primary">Soal No. ${no_soal} (Bank Soal)</h4>
                                <button type="button" class="btn btn-sm btn-icon btn-light-danger hapus-pg">
                                    <i class="ki-duotone ki-trash fs-2"></i>
                                </button>
                            </div>
                            <textarea name="nama_soal[]" class="summernote">${data.nama_soal}</textarea>
                            <div class="row g-5 mt-2">
                                ${[1,2,3,4,5].map(i => `
                                    <div class="col-md-4">
                                        <div class="input-group input-group-solid">
                                            <span class="input-group-text">${String.fromCharCode(64+i)}</span>
                                            <input type="text" name="pg_${i}[]" value="${data['pg_'+i]}" class="form-control">
                                        </div>
                                    </div>
                                `).join('')}
                                <div class="col-md-4">
                                    <div class="input-group input-group-solid border border-primary">
                                        <span class="input-group-text bg-primary text-white">Jawaban</span>
                                        <input type="text" name="jawaban[]" value="${data.jawaban}" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5">
                                <label class="fw-bold mb-2">Penjelasan</label>
                                <textarea name="penjelasan[]" class="summernote">${data.penjelasan}</textarea>
                            </div>
                        </div>`;
                        $('#soal_pg').append(pg);
                        no_soal++;
                    }
                });
            }
        });

        $('#soal_pg').on('click', '.hapus-pg', function() {
            $(this).closest('.isi_soal').remove();
            no_soal--;
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#pilih_kelas').on('change', function() {
            let idKelas = $(this).val();
            let mapelSelect = $('#pilih_mapel');

            // Reset dropdown mapel
            mapelSelect.empty();
            mapelSelect.append('<option value="">Pilih</option>');

            const csrfName = "<?= csrf_token() ?>";

            // Ambil token terbaru (bisa dari input tersembunyi atau variabel yang terus di-update)
            let csrfToken = $('input[name="' + csrfName + '"]').val();

            if (idKelas !== '') {
                let postData = {
                    id_kelas: idKelas
                };
                postData[csrfName] = csrfToken;

                $.ajax({
                    type: 'POST',
                    url: '<?= base_url('sw-guru/ujian/getMapelByKelas') ?>',
                    data: postData,
                    dataType: 'JSON',
                    success: function(response) {
                        // 1. UPDATE TOKEN CSRF TERBARU DARI SERVER
                        // Pastikan Controller Anda mengirim balik token, contoh: return $this->response->setJSON([... , 'token' => csrf_hash()]);
                        if (response.token) {
                            $('input[name="' + csrfName + '"]').val(response.token);
                        }

                        // 2. Tampilkan data mapel seperti biasa
                        // Cek apakah response berupa array langsung atau dibungkus objek (tergantung cara return controller)
                        let listMapel = response.mapel || response;

                        if (listMapel.length > 0) {
                            $.each(listMapel, function(index, data) {
                                mapelSelect.append('<option value="' + data.mapel + '">' + data.nama_mapel + '</option>');
                            });
                        } else {
                            mapelSelect.append('<option value="">Tidak ada mapel di kelas ini</option>');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 403) {
                            alert('Token keamanan kedaluwarsa, halaman akan dimuat ulang.');
                            location.reload();
                        }
                    }
                });
            }
        });
    });
</script>
<?= $this->endSection(); ?>
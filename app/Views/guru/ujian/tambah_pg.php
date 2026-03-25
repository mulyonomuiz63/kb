<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<!--  BEGIN CONTENT AREA  -->
<div class="layout-px-spacing">
    <a href="javascript:void(0);" class="btn btn-primary tambah-pg" style="position: fixed; right: -10px; top: 50%; z-index: 9999;">Tambah Soal</a>
    <form action="<?= base_url('sw-guru/ujian/store'); ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <div class="widget shadow p-3">
                    <div class="widget-heading">
                        <h5 class="">Ujian Pilihan Ganda</h5>
                        <a href="javascript:void(0);" class="btn btn-primary my-2" data-toggle="modal" data-target="#excel_ujian">Import Excel</a>
                        <a href="javascript:void(0);" class="btn btn-primary my-2" data-toggle="modal" data-target="#bank_soal">Bank Soal</a>
                        <div class="row mt-2">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Nama Ujian</label>
                                    <input type="text" name="nama_ujian" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Kelas</label>
                                    <select class="form-control" name="kelas" id="mapel_materi" required>
                                        <option value="">Pilih</option>
                                        <?php foreach ($guru_kelas as $gk) : ?>
                                            <option value="<?= $gk->id_kelas; ?>"><?= $gk->nama_kelas; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Mapel</label>
                                    <select class="form-control" name="mapel" id="mapel_materi" required>
                                        <option value="">Pilih</option>
                                        <?php foreach ($guru_mapel as $gm) : ?>
                                            <option value="<?= $gm->id_mapel; ?>"><?= $gm->nama_mapel; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Status Ujian</label>
                                    <select class="form-control" name="status_ujian" required>
                                        <option value="">Pilih</option>
                                        <option value="A">Aktif</option>
                                        <option value="T">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <div class="widget shadow p-3">
                    <div class="widget-heading">
                        <h5 class="">Soal Ujian</h5>
                    </div>
                    <div id="soal_pg">
                    </div>
                    <div class="mt-4">
                        <button class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!--  END CONTENT AREA  -->

<!-- MODAL -->
<!-- Modal Tambah -->
<div class="modal fade" id="excel_ujian" tabindex="-1" role="dialog" aria-labelledby="excel_ujianLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="<?= base_url('sw-guru/ujian/import-soal-excel'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="excel_ujianLabel">Import Soal via Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mt-2">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="">Nama Ujian / Quiz</label>
                                <input type="text" name="e_nama_ujian" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="">Kelas</label>
                                <select class="form-control" name="e_kelas" id="mapel_materi" required>
                                    <option value="">Pilih</option>
                                    <?php foreach ($guru_kelas as $gk) : ?>
                                        <option value="<?= $gk->id_kelas; ?>"><?= $gk->nama_kelas; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="">Mapel</label>
                                <select class="form-control" name="e_mapel" id="mapel_materi" required>
                                    <option value="">Pilih</option>
                                    <?php foreach ($guru_mapel as $gm) : ?>
                                        <option value="<?= $gm->id_mapel; ?>"><?= $gm->nama_mapel; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">File Excel</label><br>
                                <input type="file" name="excel" accept=".xls, .xlsx">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label for="">Template</label><br>
                            <a href="<?= base_url('sw-guru/ujian/download-template'); ?>" class="btn btn-success">Download Template</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" value="reset" class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- bank soal modal -->
<div class="modal fade" id="bank_soal" tabindex="-1" role="dialog" aria-labelledby="bank_soalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bank_soalLabel">Bank Soal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    x
                </button>
            </div>
            <div class="modal-body">
                <div class="row mt-2">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">Kategori</label>
                            <select class="form-control" name="id_kategori" id="id_kategori">
                                <option value="">Pilih</option>
                                <?php foreach ($kategori as $rows) : ?>
                                    <option value="<?= $rows->id_kategori; ?>"><?= $rows->nama_kategori; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">Soal</label>
                            <input type="text" name="nama_soal" id="nama_soal" class="form-control">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-condesed" id="table">
                            <thead>
                                <tr class="success" style="background-color:#055F93; color: white;">
                                    <th style="text-align: center;"></th>
                                    <th style="width: 95%;text-align: center;" class=" text-white">Soal</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="reset" value="reset" class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- bank soal modal -->
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

        //defenisi datatable
        var t = $('#table').DataTable({
            "select": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": false,
            "bAutoWidth": false,
            "searching": false,
            "ajax": {
                "url": "<?php echo site_url('sw-guru/ujian/get-bank-soal') ?>",
                "type": "POST",
                "data": function(d) {
                    // Injeksi CSRF Token terbaru ke setiap request
                    d[csrfName] = csrfHash;

                    // Custom Filter Anda
                    d.nama_soal = $('input[name=nama_soal]').val();
                    d.id_kategori = $('#id_kategori').val();
                },
                "dataSrc": function(json) {
                    // Tangkap token baru dari response controller
                    if (json.token) {
                        updateCsrfToken(json.token);
                    }
                    return json.data;
                },
                "error": function(xhr) {
                    if (xhr.status === 403) {
                        alert('Sesi keamanan habis, halaman akan dimuat ulang.');
                        location.reload();
                    }
                }
            },
            "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                    "className": 'text-center',
                },
                {
                    "targets": [1],
                    "orderable": false,
                    "className": 'dt-body-center'
                },
            ],
            "language": {
                "infoFiltered": "",
                "processing": '<span class="spinner-border text-primary"></span>'
            }
        });
        $('#nama_soal').on('keyup', function(e) {
            t.draw();
            e.preventDefault();
        });

        $('#id_kategori').on('change', function(e) {
            t.draw();
            e.preventDefault();
        });

    });

    $(document).ready(function() {
        // SUMMERNOTE
        setInterval(() => {
            $('.summernote').summernote({
                placeholder: 'Hello stand alone ui',
                tabsize: 2,
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
                    onImageUpload: function(image, which_sum = this) {
                        uploadImage(image[0], which_sum);
                    },
                    onMediaDelete: function(target) {
                        deleteImage(target[0].src);
                    }
                }
            });
        }, 1000);

        function uploadImage(image, which_sum) {
            var data = new FormData();

            // SUNTIKKAN CSRF KE FORMDATA
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
                    // Kita asumsikan response sekarang berupa JSON agar bisa update token
                    var res = JSON.parse(response);

                    if (res.token) {
                        updateCsrfToken(res.token);
                    }

                    $(which_sum).summernote("insertImage", res.url);
                },
                error: function(xhr) {
                    if (xhr.status === 403) {
                        console.error("CSRF Expired saat upload gambar");
                    }
                }
            });
        }

        function deleteImage(src) {
            $.ajax({
                url: "<?= base_url('sw-guru/ujian/delete-image') ?>",
                type: "POST",
                data: {
                    // SUNTIKKAN CSRF
                    [csrfName]: csrfHash,
                    src: src
                },
                cache: false,
                dataType: "JSON", // Ubah ke JSON agar bisa membaca token baru
                success: function(response) {
                    // UPDATE TOKEN GLOBAL
                    if (response.token) {
                        updateCsrfToken(response.token);
                    }
                    console.log(response.message);
                },
                error: function(xhr) {
                    if (xhr.status === 403) {
                        console.error("Gagal menghapus: Sesi keamanan berakhir.");
                    }
                }
            });
        }

        // TAMBAH SOAL PG
        // SISWA
        var no_soal = 1;
        $('.tambah-pg').click(function() {
            const pg = `
            <div class="isi_soal">
            <hr>
                <div class="form-group">
                    <label for="">Soal No . ` + no_soal + `</label>
                    <textarea name="nama_soal[]" cols="30" rows="2" class="summernote" wrap="hard" required></textarea>
                </div>
                <div class="row mt-2">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">Pilihan A</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon5">A</span>
                                </div>
                                <input type="text" name="pg_1[]" class="form-control" placeholder="Opsi A" autocomplete="off" >
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">Pilihan B</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon5">B</span>
                                </div>
                                <input type="text" name="pg_2[]" class="form-control" placeholder="Opsi B" autocomplete="off" >
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">Pilihan C</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon5">C</span>
                                </div>
                                <input type="text" name="pg_3[]" class="form-control" placeholder="Opsi C" autocomplete="off" >
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">Pilihan D</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon5">D</span>
                                </div>
                                <input type="text" name="pg_4[]" class="form-control" placeholder="Opsi D" autocomplete="off" >
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">Pilihan E</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon5">E</span>
                                </div>
                                <input type="text" name="pg_5[]" class="form-control" placeholder="Opsi E" autocomplete="off" >
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">Jawaban</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon5">
                                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </span>
                                </div>
                                <input type="text" name="jawaban[]" class="form-control" placeholder="Contoh : A" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Penjelasan</label>
                    <textarea name="penjelasan[]" cols="30" rows="2" class="summernote" wrap="hard" required></textarea>
                </div>
                <a href="javascript:void(0);" class="btn btn-danger hapus-pg">Hapus</a>
            </div>
           `;

            $('#soal_pg').append(pg);
            no_soal++;
        });


        $('#table').on('click', 'tbody input#tambahSoal', function() {
            if ($(this).is(':checked')) {
                var id_bank_soal = $(this).data('id_bank_soal');

                // Pastikan variabel no_soal sudah terdefinisi di luar fungsi ini

                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('sw-guru/ujian/tambah-bank-soal') ?>",
                    data: {
                        // SUNTIKKAN CSRF DISINI
                        [csrfName]: csrfHash,
                        id_bank_soal: id_bank_soal
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        // UPDATE TOKEN GLOBAL AGAR REQUEST SELANJUTNYA VALID
                        if (data.token) {
                            updateCsrfToken(data.token);
                        }

                        var nama_soal = data.nama_soal;
                        var pg_1 = data.pg_1;
                        var pg_2 = data.pg_2;
                        var pg_3 = data.pg_3;
                        var pg_4 = data.pg_4;
                        var pg_5 = data.pg_5;
                        var jawaban = data.jawaban;
                        var penjelasan = data.penjelasan;

                        var pg = `
                    <div class="isi_soal">
                        <hr>
                        <div class="form-group">
                            <label for="">Soal No. ` + no_soal + `</label>
                            <textarea name="nama_soal[]" cols="30" rows="2" class="summernote" required>` + nama_soal + `</textarea>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Pilihan A</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">A</span></div>
                                        <input type="text" name="pg_1[]" value="` + pg_1 + `" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Pilihan B</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">B</span></div>
                                        <input type="text" name="pg_2[]" value="` + pg_2 + `" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Pilihan C</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">C</span></div>
                                        <input type="text" name="pg_3[]" value="` + pg_3 + `" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Pilihan D</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">D</span></div>
                                        <input type="text" name="pg_4[]" value="` + pg_4 + `" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Pilihan E</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">E</span></div>
                                        <input type="text" name="pg_5[]" value="` + pg_5 + `" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Jawaban</label>
                                    <input type="text" name="jawaban[]" value="` + jawaban + `" class="form-control" placeholder="Contoh: A" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <label>Penjelasan</label>
                            <textarea name="penjelasan[]" cols="30" rows="2" class="summernote">` + penjelasan + `</textarea>
                        </div>
                        <a href="javascript:void(0);" class="btn btn-sm btn-light-danger hapus-pg mt-2">Hapus Soal Ini</a>
                    </div>`;

                        $('#soal_pg').append(pg);

                        // Inisialisasi ulang Summernote jika Anda menggunakannya
                        if (typeof $('.summernote').summernote === "function") {
                            $('.summernote').summernote({
                                height: 150
                            });
                        }

                        no_soal++;
                    },
                    error: function(xhr) {
                        if (xhr.status === 403) {
                            Swal.fire('Error', 'Sesi keamanan kadaluarsa, silakan refresh halaman.', 'error');
                        }
                        console.log(xhr.responseText);
                    }
                });
            }
        });

        $('#soal_pg').on('click', '.isi_soal a', function() {
            $(this).parents('.isi_soal').remove();
            no_soal = no_soal - 1;
        });


    })
</script>


<?= $this->endSection(); ?>
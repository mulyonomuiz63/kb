<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<!--  BEGIN CONTENT AREA  -->
    <a href="javascript:void(0);" class="btn btn-primary tambah-pg" style="position: fixed; right: -10px; top: 50%; z-index: 9999;">Tambah Soal</a>
    <div class="layout-px-spacing">
        <form action="<?= base_url('sw-guru/bank-soal/store'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="row layout-top-spacing">
                <div class="col-lg-12 layout-spacing">
                    <div class="widget shadow p-3">
                        <div class="widget-heading">
                            <h5 class="">Soal</h5>
                        </div>
                        <div id="soal_pg">
                            <div class="isi_soal">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="">Pilihan Kategori</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon5">B</span>
                                            </div>
                                            <select name="id_kategori[]" class="form-control" required>
                                                <option value="">Pilih</option>
                                                <?php foreach ($kategori as $rows) : ?>
                                                    <option value="<?= $rows->id_kategori; ?>"><?= $rows->nama_kategori; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Soal No. 1</label>
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
                                                <input type="text" name="pg_1[]" class="form-control" placeholder="Opsi A" autocomplete="off" required>
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
                                                <input type="text" name="pg_2[]" class="form-control" placeholder="Opsi B" autocomplete="off">
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
                                                <input type="text" name="pg_3[]" class="form-control" placeholder="Opsi C" autocomplete="off">
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
                                                <input type="text" name="pg_4[]" class="form-control" placeholder="Opsi D" autocomplete="off">
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
                                                <input type="text" name="pg_5[]" class="form-control" placeholder="Opsi E" autocomplete="off">
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
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="<?= base_url('sw-guru/bank-soal'); ?>" class="btn btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-reply-fill" viewBox="0 0 16 16">
                                    <path d="M5.921 11.9 1.353 8.62a.72.72 0 0 1 0-1.238L5.921 4.1A.716.716 0 0 1 7 4.719V6c1.5 0 6 0 7 8-2.5-4.5-7-4-7-4v1.281c0 .56-.606.898-1.079.62z" />
                                </svg>
                            </a>
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
        <form action="<?= base_url('sw-guru/bank-soal/excel-bank-soal-pg'); ?>" method="POST" enctype="multipart/form-data">
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

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">File Excel</label><br>
                                    <input type="file" name="excel" accept=".xls, .xlsx">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="">Template</label><br>
                                <a href="<?= base_url('download/excel_pg'); ?>" class="btn btn-success">Download Template</a>
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
<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script>

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
            data.append("image", image);
            $.ajax({
                url: "<?= base_url('sw-guru/bank-soal/upload-summernote') ?>",
                cache: false,
                contentType: false,
                processData: false,
                data: data,
                type: "POST",
                success: function(url) {
                    $(which_sum).summernote("insertImage", url);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        function deleteImage(src) {
            $.ajax({
                data: {
                    src: src
                },
                type: "POST",
                url: "<?= base_url('sw-guru/bank-soal/delete-image') ?>",
                cache: false,
                success: function(response) {
                    console.log(response);
                }
            });
        }

        // TAMBAH SOAL PG
        // SISWA
        var no_soal = 2;
        $('.tambah-pg').click(function() {
            const pg = `
            <div class="isi_soal">
            <hr>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="">Pilihan Kategori</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon5">B</span>
                            </div>
                            <select name="id_kategori[]" class="form-control" required>
                                <option value="">Pilih</option>
                                <?php foreach ($kategori as $rows) : ?>
                                    <option value="<?= $rows->id_kategori; ?>"><?= $rows->nama_kategori; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
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

        $('#soal_pg').on('click', '.isi_soal a', function() {
            $(this).parents('.isi_soal').remove();
            no_soal = no_soal - 1;
        });

    })
</script>


<?= $this->endSection(); ?>
<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?= $this->include('template/sidebar/guru'); ?>

<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <form action="<?= base_url('guru/update_soal_'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="row layout-top-spacing">
                <div class="col-lg-12 layout-spacing">
                    <div class="widget shadow p-3">
                        <div class="widget-heading">
                            <h5 class="">Edit Soal Ujian</h5>
                        </div>
                        <div id="soal_pg">
                            <div class="isi_soal">
                                <div class="form-group">
                                    <label for="">Soal No. 1</label>
                                    <textarea name="nama_soal" cols="30" rows="2" class="summernote" wrap="hard" required><?= $detail_ujian->nama_soal; ?></textarea>
                                    <input type="hidden" name="id_detail_ujian" value="<?= $detail_ujian->id_detail_ujian; ?>" id="">
                                    <input type="hidden" name="kode_ujian" value="<?= $detail_ujian->kode_ujian; ?>" id="">
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="">Pilihan A</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon5">A</span>
                                                </div>
                                                <input type="text" name="pg_1" class="form-control" value="<?= substr("$detail_ujian->pg_1", 3); ?>" placeholder="Opsi A" autocomplete="off" required>
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
                                                <input type="text" name="pg_2" class="form-control" value="<?= substr("$detail_ujian->pg_2", 3); ?>" placeholder="Opsi B" autocomplete="off">
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
                                                <input type="text" name="pg_3" class="form-control" value="<?= substr("$detail_ujian->pg_3", 3); ?>" placeholder="Opsi C" autocomplete="off">
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
                                                <input type="text" name="pg_4" class="form-control" value="<?= substr("$detail_ujian->pg_4", 3); ?>" placeholder="Opsi D" autocomplete="off">
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
                                                <input type="text" name="pg_5" class="form-control" value="<?= substr("$detail_ujian->pg_5", 3); ?>" placeholder="Opsi E" autocomplete="off">
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
                                                <input type="text" name="jawaban" class="form-control" value="<?= $detail_ujian->jawaban; ?>" placeholder="Contoh : A" autocomplete="off" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Penjelasan</label>
                                    <textarea name="penjelasan" cols="30" rows="2" class="summernote" wrap="hard" required><?= $detail_ujian->penjelasan; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="javascript:window.history.go(-1);" class="btn btn-secondary">Kembali</a>
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="footer-wrapper">
        <div class="footer-section f-section-1">
            <p class="terms-conditions"><?= copyright() ?></p>
        </div>
        <div class="footer-section f-section-2">
           
        </div>
    </div>
</div>
<!--  END CONTENT AREA  -->

<script>
    <?= session()->getFlashdata('pesan'); ?>
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
                url: "<?= base_url('guru/upload_summernote') ?>",
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
                url: "<?= base_url('guru/delete_image') ?>",
                cache: false,
                success: function(response) {
                    console.log(response);
                }
            });
        }
    })
</script>


<?= $this->endSection(); ?>
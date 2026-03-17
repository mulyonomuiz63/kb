<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?= $this->include('template/sidebar/guru'); ?>

<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <a href="javascript:void(0);" class="btn btn-primary tambah-essay" style="position: fixed; right: -10px; top: 50%; z-index: 9999;">Tambah Soal</a>
    <div class="layout-px-spacing">
        <form action="<?= base_url('guru/tambah_essay_'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="row layout-top-spacing">
                <div class="col-lg-12 layout-spacing">
                    <div class="widget shadow p-3">
                        <div class="widget-heading">
                            <h5 class="">Ujian Essay</h5>
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
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Waktu Mulai</label>
                                        <div class="input-group">
                                            <input type="date" name="tgl_mulai" class="form-control" required>
                                            <input type="time" name="jam_mulai" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Waktu Berakhir</label>
                                        <div class="input-group">
                                            <input type="date" name="tgl_berakhir" class="form-control" required>
                                            <input type="time" name="jam_berakhir" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 layout-spacing">
                    <div class="widget shadow p-3">
                        <div class="widget-heading">
                            <h5 class="">Soal Ujian</h5>
                        </div>
                        <div id="soal_essay">
                            <div class="isi_soal">
                                <div class="form-group">
                                    <label for="">Soal No. 1</label><br>
                                    <textarea class="summernote" name="soal[]" cols="30" rows="5" wrap="hard"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button class="btn btn-primary d-flex ml-auto">Submit</button>
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

        // $('.content').css('background', 'white');
        // $('.steps ul').css('display', 'none');
        // $("circle-basic").steps({
        //     cssClass: 'circle wizard'
        // });

        var no_soal = 2
        $('.tambah-essay').click(function() {
            const essay = `
            <div class="isi_soal mt-2">
                <div class="form-group">
                    <label for="">Soal No. ` + no_soal + `</label><br>
                    <textarea class="summernote" name="soal[]" cols="30" rows="5" wrap="hard"></textarea>
                </div>
                <a href="javascript:void(0);" class="btn btn-danger hapus-pg">Hapus</a>
            </div>
           `;

            $('#soal_essay').append(essay);
            no_soal++;
        });
        $('#soal_essay').on('click', '.isi_soal a', function() {
            $(this).parents('.isi_soal').remove();
            no_soal = no_soal - 1;
        });

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
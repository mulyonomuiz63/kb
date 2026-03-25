<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?php

use App\Models\UjiansiswaModel;

$UjiansiswaModel = new UjiansiswaModel();
?>
<!--  BEGIN CONTENT AREA  -->
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <div class="widget shadow p-3">
                    <div class="widget-heading">
                        <h5 class="">Soal Ujian</h5>
                    </div>
                    <div id="soal_pg">
                        <div class="isi_soal">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Edit Soal Ujian</label>
                                    <table id="datatable-table-soal" class="table text-left text-nowrap">
                                        <thead>
                                            <tr>
                                                <td class="text-center">No</td>
                                                <td>Soal</td>
                                                <td class="text-center">Jawaban</td>
                                                <td class="text-center">Opsi</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($detail_ujian as $rows) : ?>
                                                <tr>
                                                    <td class="text-center"><?= $no++; ?></td>
                                                    <td class="text-wrap" style="width: 60%; color:#000000; font-weight:bold"><?= $rows->nama_soal; ?></td>
                                                    <td class="text-center" style="color:#000000; font-weight:bold"><?= $rows->jawaban; ?></td>
                                                    <td class="text-center" style="color:#000000; font-weight:bold"><a href="<?= base_url('sw-guru/ujian/edit-soal/' . encrypt_url($rows->id_detail_ujian)); ?>" class="btn btn-success">Edit</a></td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!--  END CONTENT AREA  -->

<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script>
    function toggle_ujian(source) {
        var checkboxes = document.querySelectorAll('#tambah_ujian');
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i] != source)
                checkboxes[i].checked = source.checked;
        }
    }

    function toggle_reset(source) {
        var checkboxes = document.querySelectorAll('#reset_ujian');
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i] != source)
                checkboxes[i].checked = source.checked;
        }
    }
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
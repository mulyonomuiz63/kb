<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?= $this->include('template/sidebar/admin'); ?>

<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <form action="<?= base_url('App/simpan-kategori'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="row layout-top-spacing">
                <div class="col-lg-12 layout-spacing">
                    <div class="widget shadow p-3">
                        <div class="widget-heading">
                            <h5 class="">Kategori</h5>
                        </div>
                        <div id="soal_pg">
                            <div class="isi_soal">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" name="kategori" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="<?= base_url('App/kategori'); ?>" class="btn btn-secondary">
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
                placeholder: 'Tulis konten disini..',
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
                url: "<?= base_url('App/upload_summernote') ?>",
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
                url: "<?= base_url('App/delete_image') ?>",
                cache: false,
                success: function(response) {
                    console.log(response);
                }
            });
        }

    })
</script>


<?= $this->endSection(); ?>
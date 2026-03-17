<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<style>
    .select2-container--default .select2-selection--single {
        height: 45px !important;
        /* Sesuaikan dengan tinggi input Bootstrap Anda */
        padding: 8px;
        border: 1px solid #ced4da;
    }
</style>
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

            <div class="widget shadow-sm border-0" style="border-radius: 15px; background: #fff;">
                <div class="widget-header p-4 border-bottom">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4 class="font-weight-bold text-dark"><i class="bi bi-pencil-square mr-2 text-primary"></i>Buat Artikel Baru</h4>
                            <p class="text-muted small">Isi formulir di bawah ini untuk menerbitkan konten baru.</p>
                        </div>
                    </div>
                </div>

                <div class="widget-content p-4">
                    <form action="<?= base_url('sw-admin/artikel/store'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold">Judul Artikel</label>
                                    <input type="text" name="judul" class="form-control form-control-lg border-primary-light" placeholder="Masukkan judul artikel yang menarik..." required>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="font-weight-bold">Konten Artikel</label>
                                    <textarea name="konten" class="summernote" required></textarea>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="card border-0 bg-light-primary p-3 mb-4" style="border-radius: 12px; background-color: #f8f9fb;">
                                    <h6 class="font-weight-bold mb-3 text-primary">Pengaturan Publikasi</h6>

                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold">Thumbnail Artikel</label>
                                        <div class="custom-file">
                                            <input type="file" name="image_default" class="custom-file-input" id="customFile" accept="image/*" required>
                                            <label class="custom-file-label" for="customFile">Pilih Gambar</label>
                                        </div>
                                        <small class="text-muted">Format: JPG, PNG. Maks: 2MB</small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold">Kategori</label>
                                        <select name="idkategori" class="form-control select2-kategori" required>
                                            <option value="" selected disabled>Pilih atau Ketik Kategori Baru...</option>
                                            <?php foreach ($kategori as $rows): ?>
                                                <option value="<?= $rows->id ?>"><?= $rows->kategori ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="text-muted">Pilih kategori yang tersedia atau ketik baru lalu tekan <strong>Enter</strong>.</small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold">Posisi Tampil</label>
                                        <select name="status" class="form-control custom-select" required>
                                            <option value="" selected disabled>Pilih Posisi</option>
                                            <option value="utama_up">Artikel Utama Atas</option>
                                            <option value="utama_down">Artikel Utama Bawah</option>
                                            <option value="rekomendasi">Artikel Rekomendasi</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-0">
                                        <label class="small font-weight-bold">Tag (Pisahkan dengan koma)</label>
                                        <input type="text" name="tags" class="form-control form-control-sm" placeholder="Contoh: berita, tekno, edukasi">
                                    </div>
                                </div>

                                <div class="d-flex flex-column gap-2">
                                    <button type="submit" class="btn btn-primary btn-block shadow-sm py-2">
                                        <i class="bi bi-send mr-2"></i> Terbitkan Artikel
                                    </button>
                                    <a href="<?= base_url('sw-admin/artikel/artikel'); ?>" class="btn btn-outline-secondary btn-block py-2">
                                        Batal
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {

        // 1. Inisialisasi Summernote
        // Pastikan library Summernote sudah ter-include di template/app
        $('.summernote').summernote({
            placeholder: 'Mulai menulis konten yang luar biasa disini...',
            tabsize: 2,
            height: 450,
            lang: 'id-ID', // Jika ada lang file
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
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

        // 2. Fungsi Preview Gambar Thumbnail sebelum Upload
        $("#customFile").on("change", function() {
            // Tampilkan nama file
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);

            // Preview Image
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    // Cek apakah element preview sudah ada, jika belum buat baru
                    if ($('#img-preview').length == 0) {
                        $('.custom-file').after('<img id="img-preview" src="" class="img-fluid rounded mt-2 shadow-sm" style="max-height: 200px;">');
                    }
                    $('#img-preview').attr('src', event.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        // 3. Fungsi Upload Image Summernote
        function uploadImage(image, editor) {
            var data = new FormData();
            data.append("image", image);

            // Ambil token terbaru dari input hidden atau hash awal
            var csrfName = '<?= csrf_token() ?>';
            var csrfHash = $("input[name='<?= csrf_token() ?>']").val() || '<?= csrf_hash() ?>';

            data.append(csrfName, csrfHash);

            $.ajax({
                url: "<?= base_url('sw-admin/artikel/upload-summernote') ?>",
                cache: false,
                contentType: false,
                processData: false,
                data: data,
                type: "POST",
                success: function(response) {
                    // 1. Masukkan gambar ke editor
                    $(editor).summernote("insertImage", response.url);

                    // 2. UPDATE TOKEN CSRF di semua input
                    // Ini kunci agar submit form utama tidak "Action not allowed"
                    $("input[name='" + csrfName + "']").val(response.token);
                },
                error: function(data) {
                    console.error("Upload error", data);
                }
            });
        }

        // 4. Fungsi Delete Image Summernote
        function deleteImage(src) {
            var csrfName = '<?= csrf_token() ?>';
            // Ambil hash terbaru dari input hidden
            var csrfHash = $("input[name='" + csrfName + "']").val();

            $.ajax({
                url: "<?= base_url('sw-admin/artikel/delete-image') ?>",
                type: "POST",
                data: {
                    src: src,
                    [csrfName]: csrfHash // Gunakan bracket [] untuk key dinamis
                },
                cache: false,
                success: function(response) {
                    console.log("Deleted:", response.message);

                    // UPDATE TOKEN CSRF di semua input hidden agar form utama tetap valid
                    $("input[name='" + csrfName + "']").val(response.token);
                },
                error: function(xhr) {
                    console.error("Gagal menghapus gambar di server.");
                }
            });
        }
    });
    // Inisialisasi Select2 untuk Kategori
    $('.select2-kategori').select2({
        placeholder: "Pilih atau Ketik Kategori Baru...",
        tags: true, // Ini kunci untuk input manual
        allowClear: true,
        width: '100%',
        createTag: function(params) {
            var term = $.trim(params.term);
            if (term === '') {
                return null;
            }
            return {
                id: term,
                text: term,
                newTag: true // Tandai bahwa ini adalah tag baru
            };
        }
    });
</script>
<?= $this->endSection(); ?>
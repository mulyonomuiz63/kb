<?= $this->extend('template/app'); ?>
<?= $this->section('styles'); ?>
<style>
    /* Custom CSS untuk halaman create artikel */
    .select2-container--bootstrap5 .select2-selection--single {
        min-height: calc(1.5em + 1.65rem + 2px) !important;
        padding: 0.825rem 1.5rem !important;
    }
    .select2-container--bootstrap5 .select2-selection__rendered {
        line-height: 1.5 !important;
    }
</style>
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <form action="<?= base_url('sw-admin/artikel/store'); ?>" method="POST" enctype="multipart/form-data" class="form d-flex flex-column flex-lg-row gap-7 gap-lg-10">
                <?= csrf_field() ?>

                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <div class="card card-flush shadow-sm border-0">
                        <div class="card-header pt-7">
                            <div class="card-title">
                                <h2 class="fw-bold">Konten Artikel</h2>
                            </div>
                        </div>
                        <div class="card-body pt-5">

                            <div class="fv-row mb-10">
                                <label class="required form-label fs-6 fw-bold text-gray-900">Judul Artikel</label>
                                <input type="text" name="judul" class="form-control form-control-lg form-control-solid" placeholder="Masukkan judul artikel yang menarik..." required>
                                <div class="text-muted fs-7 mt-2">Judul yang menarik akan meningkatkan minat baca pengunjung.</div>
                            </div>

                            <div class="fv-row">
                                <label class="required form-label fs-6 fw-bold text-gray-900 mb-3">Tulis Konten</label>
                                <textarea name="konten" class="summernote" required></textarea>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px w-xl-400px">
                    <div class="card card-flush shadow-sm border-0">
                        <div class="card-header pt-7">
                            <div class="card-title">
                                <h2 class="fw-bold">Pengaturan Publikasi</h2>
                            </div>
                        </div>

                        <div class="card-body pt-5">

                            <div class="fv-row mb-7">
                                <label class="required form-label fw-bold">Thumbnail Artikel</label>
                                <div class="custom-file">
                                    <input type="file" name="image_default" class="form-control form-control-solid" id="customFile" accept="image/*" required>
                                    <span class="custom-file-label d-none"></span>
                                </div>
                                <div class="text-muted fs-7 mt-2">Format: JPG, PNG. Maks: 2MB</div>
                            </div>

                            <div class="separator separator-dashed my-7"></div>

                            <div class="fv-row mb-7">
                                <label class="required form-label fw-bold">Kategori</label>
                                <select name="idkategori" class="form-select form-select-solid select2-kategori" required>
                                    <option value="" selected disabled>Pilih atau Ketik Baru...</option>
                                    <?php foreach ($kategori as $rows): ?>
                                        <option value="<?= $rows->id ?>"><?= $rows->kategori ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="text-muted fs-7 mt-2">Pilih kategori yang tersedia atau ketik baru lalu tekan <strong>Enter</strong>.</div>
                            </div>

                            <div class="fv-row mb-7">
                                <label class="required form-label fw-bold">Posisi Tampil</label>
                                <select name="status" class="form-select form-select-solid" data-control="select2" data-hide-search="true" required>
                                    <option value="" selected disabled>Pilih Posisi</option>
                                    <option value="utama_up">Artikel Utama Atas</option>
                                    <option value="utama_down">Artikel Utama Bawah</option>
                                    <option value="rekomendasi">Artikel Rekomendasi</option>
                                </select>
                            </div>

                            <div class="fv-row mb-7">
                                <label class="form-label fw-bold">Tag</label>
                                <input type="text" name="tags" class="form-control form-control-solid" placeholder="Contoh: berita, tekno, edukasi">
                                <div class="text-muted fs-7 mt-2">Pisahkan setiap tag dengan koma.</div>
                            </div>

                        </div>

                        <div class="card-footer pt-0">
                            <button type="submit" class="btn btn-primary w-100 fw-bold">
                                <i class="ki-duotone ki-send fs-3 me-1"><span class="path1"></span><span class="path2"></span></i> Terbitkan Artikel
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {

        // 1. Inisialisasi Summernote
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

        // 2. Fungsi Preview Gambar Thumbnail sebelum Upload (LOGIKA ASLI)
        $("#customFile").on("change", function() {
            // Tampilkan nama file (dummy label operation dipertahankan agar tidak error)
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);

            // Preview Image
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    // Cek apakah element preview sudah ada, jika belum buat baru
                    if ($('#img-preview').length == 0) {
                        $('.custom-file').after('<img id="img-preview" src="" class="img-fluid rounded mt-4 border border-gray-300 shadow-sm" style="max-height: 200px; width: 100%; object-fit: cover;">');
                    }
                    $('#img-preview').attr('src', event.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        // 3. Fungsi Upload Image Summernote (LOGIKA ASLI)
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
                    $("input[name='" + csrfName + "']").val(response.token);
                },
                error: function(data) {
                    console.error("Upload error", data);
                }
            });
        }

        // 4. Fungsi Delete Image Summernote (LOGIKA ASLI)
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
                    // UPDATE TOKEN CSRF di semua input hidden agar form utama tetap valid
                    $("input[name='" + csrfName + "']").val(response.token);
                }
            });
        }

        // 5. Inisialisasi Select2 untuk Kategori (LOGIKA ASLI tags: true)
        $('.select2-kategori').select2({
            placeholder: "Pilih atau Ketik Baru...",
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

    });
</script>
<?= $this->endSection(); ?>
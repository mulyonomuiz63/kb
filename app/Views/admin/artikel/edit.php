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
                    <h4 class="font-weight-bold text-dark mb-1"><i class="bi bi-pencil-square mr-2 text-primary"></i>Edit Artikel</h4>
                    <p class="text-muted small mb-0">Perbarui informasi dan konten artikel Anda.</p>
                </div>

                <div class="widget-content p-4">
                    <form action="<?= base_url('sw-admin/artikel/update'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="image_default_lama" value="<?= $artikel->image_default ?>">
                        <input type="hidden" name="idartikel" value="<?= $artikel->id ?>">

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark">Judul Artikel</label>
                                    <input type="text" name="judul" value="<?= $artikel->judul ?>" class="form-control form-control-lg bg-light" readonly>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark">Konten</label>
                                    <textarea name="konten" class="summernote" required><?= $artikel->konten ?></textarea>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="card border-0 p-3 mb-4" style="border-radius: 12px; background-color: #f8f9fb;">
                                    <h6 class="font-weight-bold mb-3 text-primary">Pengaturan & Media</h6>

                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold">Ganti Gambar (Opsional)</label>
                                        <div class="custom-file">
                                            <input type="file" name="image_default" class="custom-file-input" id="customFile">
                                            <label class="custom-file-label" for="customFile">Pilih file...</label>
                                        </div>
                                        <div class="mt-2 text-center border rounded p-2 bg-white">
                                            <small class="d-block text-muted mb-1">Preview Saat Ini:</small>
                                            <img id="img-preview" src="<?= base_url('uploads/artikel/thumbnails/' . $artikel->image_default) ?>" class="img-fluid rounded shadow-sm" style="max-height: 150px;">
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold">Kategori</label>
                                        <select name="idkategori" class="form-control select2-kategori" required>
                                            <option value="" disabled>Pilih atau Ketik Kategori Baru...</option>
                                            <?php foreach ($kategori as $rows): ?>
                                                <option value="<?= $rows->id ?>" <?= $artikel->idkategori == $rows->id ? "selected" : "" ?>>
                                                    <?= $rows->kategori ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="text-muted">Pilih yang tersedia atau ketik baru lalu tekan <strong>Enter</strong>.</small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold">Posisi Tampil</label>
                                        <select name="status" class="form-control custom-select" required>
                                            <option value="utama_up" <?= $artikel->status == "utama_up" ? "selected" : "" ?>>Artikel Utama Atas</option>
                                            <option value="utama_down" <?= $artikel->status == "utama_down" ? "selected" : "" ?>>Artikel Utama Bawah</option>
                                            <option value="rekomendasi" <?= $artikel->status == "rekomendasi" ? "selected" : "" ?>>Artikel Rekomendasi</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="small font-weight-bold">Tambah Tag Baru</label>
                                        <input type="text" name="tags" class="form-control form-control-sm" placeholder="tag1, tag2...">
                                        <small class="text-muted">Pisahkan dengan koma</small>
                                    </div>

                                    <div class="d-flex flex-wrap gap-1" style="gap: 5px;">
                                        <?php foreach ($tag as $rows): ?>
                                            <span class="badge badge-light-primary border px-2 py-1 d-flex align-items-center tag-wrapper">
                                                <?= $rows->tag ?>
                                                <a href="<?= base_url('sw-admin/artikel/delete-tag') . '/' . encrypt_url($rows->id); ?>"
                                                    class="ml-2 text-danger delete-tag-btn">
                                                    <i class="bi bi-x-circle-fill"></i>
                                                </a>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-block py-2 shadow-sm font-weight-bold">
                                    <i class="bi bi-check2-circle mr-1"></i> Simpan Perubahan
                                </button>
                                <a href="<?= base_url('sw-admin/artikel'); ?>" class="btn btn-outline-secondary btn-block py-2">
                                    Kembali
                                </a>
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
        // Fungsi pusat untuk ambil token terbaru dari input hidden
        function getCsrfHash() {
            return $("input[name='" + csrfName + "']").val();
        }

        // Fungsi pusat untuk update token di semua tempat
        function updateAllCsrf(newToken) {
            if (newToken) {
                $("input[name='" + csrfName + "']").val(newToken);
                console.log("Token diperbarui ke: " + newToken.substring(0, 10) + "...");
            }
        }

        // --- SUMMERNOTE ---
        $('.summernote').summernote({
            height: 400,
            callbacks: {
                onImageUpload: function(image) {
                    uploadImage(image[0], this);
                },
                onMediaDelete: function(target) {
                    deleteImage(target[0].src);
                }
            }
        });

        function uploadImage(image, editor) {
            var data = new FormData();
            data.append("image", image);
            data.append(csrfName, getCsrfHash()); // Ambil token terbaru

            $.ajax({
                url: "<?= base_url('sw-admin/artikel/upload-summernote') ?>",
                cache: false,
                contentType: false,
                processData: false,
                data: data,
                type: "POST",
                success: function(response) {
                    $(editor).summernote("insertImage", response.url);
                    updateAllCsrf(response.token); // Update token setelah upload
                }
            });
        }

        function deleteImage(src) {
            $.ajax({
                url: "<?= base_url('sw-admin/artikel/delete-image') ?>",
                type: "POST",
                data: {
                    src: src,
                    [csrfName]: getCsrfHash() // Ambil token terbaru
                },
                success: function(response) {
                    updateAllCsrf(response.token); // Update token setelah hapus gambar
                }
            });
        }

        // --- HAPUS TAG ---
        $(document).on('click', '.delete-tag-btn', function(e) {
            e.preventDefault();
            var self = $(this);
            var url = self.attr('href');
            var tagElement = self.closest('.tag-wrapper');

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    [csrfName]: getCsrfHash()
                }, // Ambil token terbaru
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        tagElement.fadeOut(200, function() {
                            $(this).remove();
                        });
                        updateAllCsrf(response.token); // Update token setelah hapus tag
                    }
                }
            });
        });

        // --- PREVIEW IMAGE UTAMA ---
        $("#customFile").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#img-preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
    });
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
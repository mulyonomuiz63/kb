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
            
            <form action="<?= base_url('sw-admin/artikel/update'); ?>" method="POST" enctype="multipart/form-data" class="form d-flex flex-column flex-lg-row gap-7 gap-lg-10">
                <?= csrf_field() ?>
                <input type="hidden" name="image_default_lama" value="<?= $artikel->image_default ?>">
                <input type="hidden" name="idartikel" value="<?= $artikel->id ?>">

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
                                <input type="text" name="judul" value="<?= esc($artikel->judul) ?>" class="form-control form-control-lg form-control-solid bg-secondary" readonly>
                                <div class="text-muted fs-7 mt-2">Judul artikel yang telah diterbitkan tidak dapat diubah.</div>
                            </div>

                            <div class="fv-row">
                                <label class="required form-label fs-6 fw-bold text-gray-900 mb-3">Konten</label>
                                <textarea name="konten" class="summernote" required><?= $artikel->konten ?></textarea>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px w-xl-400px">
                    <div class="card card-flush shadow-sm border-0">
                        <div class="card-header pt-7">
                            <div class="card-title">
                                <h2 class="fw-bold">Pengaturan & Media</h2>
                            </div>
                        </div>

                        <div class="card-body pt-5">
                            
                            <div class="fv-row mb-7">
                                <label class="form-label fw-bold">Ganti Gambar (Opsional)</label>
                                <div class="custom-file mb-4">
                                    <input type="file" name="image_default" class="form-control form-control-solid" id="customFile" accept="image/*">
                                    <span class="custom-file-label d-none"></span>
                                </div>
                                <div class="border border-dashed border-gray-300 rounded p-3 text-center bg-light">
                                    <div class="text-muted fs-7 fw-semibold mb-2">Preview Saat Ini:</div>
                                    <img id="img-preview" src="<?= base_url('uploads/artikel/thumbnails/' . $artikel->image_default) ?>" class="img-fluid rounded shadow-sm" style="max-height: 150px; object-fit: contain;">
                                </div>
                            </div>

                            <div class="separator separator-dashed my-7"></div>

                            <div class="fv-row mb-7">
                                <label class="required form-label fw-bold">Kategori</label>
                                <select name="idkategori" class="form-select form-select-solid select2-kategori" required>
                                    <option value="" disabled>Pilih atau Ketik Kategori Baru...</option>
                                    <?php foreach ($kategori as $rows): ?>
                                        <option value="<?= $rows->id ?>" <?= $artikel->idkategori == $rows->id ? "selected" : "" ?>>
                                            <?= $rows->kategori ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="text-muted fs-7 mt-2">Pilih yang tersedia atau ketik baru lalu tekan <strong>Enter</strong>.</div>
                            </div>

                            <div class="fv-row mb-7">
                                <label class="required form-label fw-bold">Posisi Tampil</label>
                                <select name="status" class="form-select form-select-solid custom-select" data-control="select2" data-hide-search="true" required>
                                    <option value="utama_up" <?= $artikel->status == "utama_up" ? "selected" : "" ?>>Artikel Utama Atas</option>
                                    <option value="utama_down" <?= $artikel->status == "utama_down" ? "selected" : "" ?>>Artikel Utama Bawah</option>
                                    <option value="rekomendasi" <?= $artikel->status == "rekomendasi" ? "selected" : "" ?>>Artikel Rekomendasi</option>
                                </select>
                            </div>

                            <div class="fv-row mb-5">
                                <label class="form-label fw-bold">Tambah Tag Baru</label>
                                <input type="text" name="tags" class="form-control form-control-solid" placeholder="tag1, tag2...">
                                <div class="text-muted fs-7 mt-2">Pisahkan setiap tag dengan koma.</div>
                            </div>

                            <div class="fv-row">
                                <div class="d-flex flex-wrap gap-2">
                                    <?php foreach ($tag as $rows): ?>
                                        <span class="badge badge-light-primary px-3 py-2 fs-7 fw-semibold d-flex align-items-center tag-wrapper border border-primary border-dashed">
                                            <?= esc($rows->tag) ?>
                                            <a href="<?= base_url('sw-admin/artikel/delete-tag') . '/' . encrypt_url($rows->id); ?>" 
                                               class="ms-2 text-danger text-hover-dark delete-tag-btn" data-bs-toggle="tooltip" title="Hapus Tag">
                                                <i class="ki-duotone ki-cross-circle fs-3"><span class="path1"></span><span class="path2"></span></i>
                                            </a>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                        </div>
                        
                        <div class="card-footer pt-0">
                            <button type="submit" class="btn btn-primary w-100 fw-bold">
                                <i class="ki-duotone ki-check-circle fs-2 me-1"><span class="path1"></span><span class="path2"></span></i> Simpan Perubahan
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
        // Fungsi pusat untuk ambil token terbaru dari input hidden
        function getCsrfHash() {
            return $("input[name='" + csrfName + "']").val();
        }

        // Fungsi pusat untuk update token di semua tempat
        function updateAllCsrf(newToken) {
            if (newToken) {
                $("input[name='" + csrfName + "']").val(newToken);
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

            // Hapus tooltip dari elemen sebelum di-remove agar tidak nyangkut (ghost tooltip)
            const tooltip = bootstrap.Tooltip.getInstance(this);
            if(tooltip) { tooltip.dispose(); }

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
            // Label update dibiarkan dummy agar tidak membebani tampilan custom native Bootstrap 5
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

        // --- SELECT2 KATEGORI ---
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

    });
</script>
<?= $this->endSection(); ?>
<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<a href="javascript:void(0);" class="btn btn-primary tambah-pg shadow-sm" style="position: fixed; right: 20px; top: 50%; z-index: 9999; border-radius: 50px;">
    <i class="ki-duotone ki-plus fs-2"></i> Tambah Soal
</a>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <form action="<?= base_url('sw-admin/bank-soal/store'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            
            <div class="card card-flush shadow-sm">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="card-label fw-bold text-dark">Tambah Bank Soal</h3>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div id="soal_pg">
                        <div class="isi_soal mb-10">
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Pilihan Kategori</label>
                                <div class="input-group input-group-solid">
                                    <select name="id_kategori[]" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Kategori" required>
                                        <option value="">Pilih</option>
                                        <?php foreach ($kategori as $rows) : ?>
                                            <option value="<?= $rows->id_kategori; ?>"><?= $rows->nama_kategori; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Soal No. 1</label>
                                <textarea name="nama_soal[]" class="summernote" required></textarea>
                            </div>

                            <div class="row g-9 mb-7">
                                <?php 
                                    $options = ['A', 'B', 'C', 'D', 'E'];
                                    foreach($options as $index => $label): 
                                    $num = $index + 1;
                                ?>
                                <div class="col-md-4">
                                    <label class="fs-6 fw-semibold mb-2">Pilihan <?= $label ?></label>
                                    <div class="input-group input-group-solid">
                                        <span class="input-group-text"><?= $label ?></span>
                                        <input type="text" name="pg_<?= $num ?>[]" class="form-control form-control-solid" placeholder="Opsi <?= $label ?>" autocomplete="off" <?= ($label == 'A') ? 'required' : '' ?>>
                                    </div>
                                </div>
                                <?php endforeach; ?>

                                <div class="col-md-4">
                                    <label class="required fs-6 fw-semibold mb-2">Jawaban Benar</label>
                                    <div class="input-group input-group-solid border border-primary">
                                        <span class="input-group-text bg-light-primary">
                                            <i class="ki-duotone ki-check-circle fs-2 text-primary"><span class="path1"></span><span class="path2"></span></i>
                                        </span>
                                        <input type="text" name="jawaban[]" class="form-control form-control-solid" placeholder="Contoh : A" autocomplete="off" required>
                                    </div>
                                </div>
                            </div>

                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Penjelasan</label>
                                <textarea name="penjelasan[]" class="summernote" required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="separator separator-dashed my-10"></div>
                    <div class="d-flex justify-content-end">
                        <a href="<?= base_url('sw-admin/bank-soal'); ?>" class="btn btn-light me-3">
                            <i class="ki-duotone ki-arrow-left fs-4 me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Simpan Soal</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Initialize Summernote with Metronic Style
        function initSummernote() {
            $('.summernote').summernote({
                tabsize: 2,
                height: 150,
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
                    onImageUpload: function(image) {
                        uploadImage(image[0], this);
                    },
                    onMediaDelete: function(target) {
                        deleteImage(target[0].src);
                    }
                }
            });
        }

        initSummernote();

        // Re-init summernote because of the 1s interval from original code
        // Note: In Metronic/Modern JS, we usually init only once, but keeping your logic:
        setInterval(() => {
            $('.summernote').each(function() {
                if ($(this).summernote('isEmpty') && !$(this).next().hasClass('note-editor')) {
                    initSummernote();
                }
            });
        }, 2000);

        function uploadImage(image, which_sum) {
            var data = new FormData();
            data.append("image", image);
            $.ajax({
                url: "<?= base_url('sw-admin/bank-soal/upload-summernote') ?>",
                cache: false, contentType: false, processData: false,
                data: data, type: "POST",
                success: function(url) {
                    $(which_sum).summernote("insertImage", url);
                }
            });
        }

        function deleteImage(src) {
            $.ajax({
                data: { src: src },
                type: "POST",
                url: "<?= base_url('sw-admin/bank-soal/delete-image') ?>",
                cache: false
            });
        }

        // TAMBAH SOAL PG DYNAMIC
        var no_soal = 2;
        $('.tambah-pg').click(function() {
            const pg = `
            <div class="isi_soal mt-10">
                <div class="separator separator-content border-primary my-15">
                    <span class="w-250px fw-bold text-primary fs-4">Soal No. ${no_soal}</span>
                </div>
                
                <div class="fv-row mb-7">
                    <label class="required fs-6 fw-semibold mb-2">Pilihan Kategori</label>
                    <div class="input-group input-group-solid">
                        <span class="input-group-text"><i class="ki-duotone ki-category fs-2"></i></span>
                        <select name="id_kategori[]" class="form-select form-select-solid" required>
                            <option value="">Pilih</option>
                            <?php foreach ($kategori as $rows) : ?>
                                <option value="<?= $rows->id_kategori; ?>"><?= $rows->nama_kategori; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="fv-row mb-7">
                    <label class="required fs-6 fw-semibold mb-2">Isi Soal</label>
                    <textarea name="nama_soal[]" class="summernote" required></textarea>
                </div>

                <div class="row g-9 mb-7">
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-2">Pilihan A</label>
                        <div class="input-group input-group-solid"><span class="input-group-text">A</span><input type="text" name="pg_1[]" class="form-control form-control-solid"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-2">Pilihan B</label>
                        <div class="input-group input-group-solid"><span class="input-group-text">B</span><input type="text" name="pg_2[]" class="form-control form-control-solid"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-2">Pilihan C</label>
                        <div class="input-group input-group-solid"><span class="input-group-text">C</span><input type="text" name="pg_3[]" class="form-control form-control-solid"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-2">Pilihan D</label>
                        <div class="input-group input-group-solid"><span class="input-group-text">D</span><input type="text" name="pg_4[]" class="form-control form-control-solid"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="fs-6 fw-semibold mb-2">Pilihan E</label>
                        <div class="input-group input-group-solid"><span class="input-group-text">E</span><input type="text" name="pg_5[]" class="form-control form-control-solid"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="required fs-6 fw-semibold mb-2 text-primary">Jawaban Benar</label>
                        <div class="input-group input-group-solid border border-primary"><span class="input-group-text bg-light-primary text-primary fw-bold">✓</span><input type="text" name="jawaban[]" class="form-control form-control-solid" required></div>
                    </div>
                </div>

                <div class="fv-row mb-7">
                    <label class="required fs-6 fw-semibold mb-2">Penjelasan</label>
                    <textarea name="penjelasan[]" class="summernote" required></textarea>
                </div>

                <div class="d-flex justify-content-end mb-10">
                    <button type="button" class="btn btn-light-danger btn-sm hapus-pg">
                        <i class="ki-duotone ki-trash fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Hapus Item Soal
                    </button>
                </div>
            </div>`;

            $('#soal_pg').append(pg);
            initSummernote(); // Initialize summernote for the new element
            no_soal++;
        });

        $('#soal_pg').on('click', '.hapus-pg', function() {
            $(this).closest('.isi_soal').remove();
            no_soal--;
        });
    });
</script>
<?= $this->endSection(); ?>
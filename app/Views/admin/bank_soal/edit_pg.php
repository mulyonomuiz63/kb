<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <form action="<?= base_url('sw-admin/bank-soal/update'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <input type="hidden" name="id_bank_soal" value="<?= $soal->id_bank_soal; ?>">

            <div class="card card-flush shadow-sm">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="card-label fw-bold text-dark">Edit Soal</h3>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div id="soal_pg">
                        <div class="isi_soal">
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Pilihan Kategori</label>
                                <div class="input-group input-group-solid">
                                    <select name="id_kategori" class="form-select form-select-solid" data-control="select2" required>
                                        <option value="">Pilih</option>
                                        <?php foreach ($kategori as $rows) : ?>
                                            <option value="<?= $rows->id_kategori; ?>" <?= $rows->id_kategori == $soal->id_kategori ? 'selected' : ''; ?>><?= $rows->nama_kategori; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Soal No. 1</label>
                                <textarea name="nama_soal" class="summernote" required><?= $soal->nama_soal; ?></textarea>
                            </div>

                            <div class="row g-9 mb-7">
                                <?php 
                                    $options = [
                                        '1' => ['label' => 'A', 'val' => $soal->pg_1],
                                        '2' => ['label' => 'B', 'val' => $soal->pg_2],
                                        '3' => ['label' => 'C', 'val' => $soal->pg_3],
                                        '4' => ['label' => 'D', 'val' => $soal->pg_4],
                                        '5' => ['label' => 'E', 'val' => $soal->pg_5],
                                    ];
                                    foreach($options as $key => $opt): 
                                ?>
                                <div class="col-md-4">
                                    <label class="fs-6 fw-semibold mb-2">Pilihan <?= $opt['label'] ?></label>
                                    <div class="input-group input-group-solid">
                                        <span class="input-group-text"><?= $opt['label'] ?></span>
                                        <input type="text" name="pg_<?= $key ?>" value="<?= substr($opt['val'], 3); ?>" class="form-control form-control-solid" autocomplete="off" <?= ($opt['label'] == 'A') ? 'required' : '' ?>>
                                    </div>
                                </div>
                                <?php endforeach; ?>

                                <div class="col-md-4">
                                    <label class="required fs-6 fw-semibold mb-2 text-primary">Jawaban Benar</label>
                                    <div class="input-group input-group-solid border border-primary">
                                        <span class="input-group-text bg-light-primary">
                                            <i class="ki-duotone ki-check-circle fs-2 text-primary"><span class="path1"></span><span class="path2"></span></i>
                                        </span>
                                        <input type="text" name="jawaban" value="<?= $soal->jawaban; ?>" class="form-control form-control-solid" placeholder="Contoh : A" autocomplete="off" required>
                                    </div>
                                </div>
                            </div>

                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Penjelasan</label>
                                <textarea name="penjelasan" class="summernote" required><?= $soal->penjelasan; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="separator separator-dashed my-10"></div>
                    <div class="d-flex justify-content-end">
                        <a href="<?= base_url('sw-admin/bank-soal'); ?>" class="btn btn-light me-3">
                            <i class="ki-duotone ki-arrow-left fs-4 me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ki-duotone ki-save-2 fs-4 me-1"><span class="path1"></span><span class="path2"></span></i> Simpan Perubahan
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
        // SUMMERNOTE INITIALIZATION
        function initSummernote() {
            $('.summernote').summernote({
                placeholder: 'Tulis soal di sini...',
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

        // Keep original interval logic for checking summernote instances
        setInterval(() => {
            $('.summernote').each(function() {
                if ($(this).summernote('isEmpty') && !$(this).next().hasClass('note-editor')) {
                    initSummernote();
                }
            });
        }, 1000);

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
    });
</script>
<?= $this->endSection(); ?>
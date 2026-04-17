<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <form action="<?= base_url('sw-guru/ujian/update-soal'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            
            <div class="card card-flush shadow-sm">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="card-label fw-bold text-dark">Edit Soal Ujian</h3>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div id="soal_pg">
                        <div class="isi_soal">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">Isi Soal</label>
                                <textarea name="nama_soal" class="summernote" required><?= $detail_ujian->nama_soal; ?></textarea>
                                <input type="hidden" name="id_detail_ujian" value="<?= $detail_ujian->id_detail_ujian; ?>">
                                <input type="hidden" name="kode_ujian" value="<?= $detail_ujian->kode_ujian; ?>">
                            </div>

                            <div class="row g-9 mb-7">
                                <?php 
                                    $options = [
                                        '1' => 'A', 
                                        '2' => 'B', 
                                        '3' => 'C', 
                                        '4' => 'D', 
                                        '5' => 'E'
                                    ];
                                    foreach($options as $key => $label): 
                                    $field = "pg_".$key;
                                ?>
                                <div class="col-md-4">
                                    <label class="fs-6 fw-semibold mb-2">Pilihan <?= $label ?></label>
                                    <div class="input-group input-group-solid">
                                        <span class="input-group-text"><?= $label ?></span>
                                        <input type="text" name="pg_<?= $key ?>" class="form-control form-control-solid" 
                                               value="<?= substr($detail_ujian->$field, 3); ?>" 
                                               placeholder="Opsi <?= $label ?>" autocomplete="off" <?= ($key == '1') ? 'required' : '' ?>>
                                    </div>
                                </div>
                                <?php endforeach; ?>

                                <div class="col-md-4">
                                    <label class="fs-6 fw-semibold mb-2 text-primary">Jawaban Benar</label>
                                    <div class="input-group input-group-solid border border-primary">
                                        <span class="input-group-text bg-light-primary">
                                            <i class="ki-duotone ki-check-circle fs-2 text-primary"><span class="path1"></span><span class="path2"></span></i>
                                        </span>
                                        <input type="text" name="jawaban" class="form-control form-control-solid" 
                                               value="<?= $detail_ujian->jawaban; ?>" readonly>
                                    </div>
                                    <div class="text-muted fs-7 mt-1">Status: Terkunci (Readonly)</div>
                                </div>
                            </div>

                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">Penjelasan Jawaban</label>
                                <textarea name="penjelasan" class="summernote" required><?= $detail_ujian->penjelasan; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="separator separator-dashed my-10"></div>
                    <div class="d-flex justify-content-end">
                        <a href="javascript:window.history.go(-1);" class="btn btn-light me-3">
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
    function updateCsrfToken(newToken) {
        if (newToken && newToken !== csrfHash) {
            csrfHash = newToken;
            $('input[name="' + csrfName + '"]').val(newToken);
        }
    }

    $(document).ready(function() {
        // SUMMERNOTE INITIALIZATION
        function initSummernote() {
            $('.summernote').summernote({
                placeholder: 'Tulis di sini...',
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

        // Keep original interval check
        setInterval(() => {
            $('.summernote').each(function() {
                if (!$(this).next().hasClass('note-editor')) {
                    initSummernote();
                }
            });
        }, 2000);

        function uploadImage(image, which_sum) {
            var data = new FormData();
            data.append(csrfName, csrfHash);
            data.append("image", image);

            $.ajax({
                url: "<?= base_url('sw-guru/ujian/upload-summernote') ?>",
                cache: false,
                contentType: false,
                processData: false,
                data: data,
                type: "POST",
                success: function(response) {
                    var res = JSON.parse(response);
                    if (res.token) updateCsrfToken(res.token);
                    $(which_sum).summernote("insertImage", res.url);
                }
            });
        }

        function deleteImage(src) {
            $.ajax({
                url: "<?= base_url('sw-guru/ujian/delete-image') ?>",
                type: "POST",
                data: {
                    [csrfName]: csrfHash,
                    src: src
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.token) updateCsrfToken(response.token);
                }
            });
        }
    });
</script>
<?= $this->endSection(); ?>
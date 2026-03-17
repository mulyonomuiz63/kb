<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <div class="widget-content widget-content-area br-6">
                <div class="d-flex justify-content-between p-3">
                    <h4 class="font-weight-bold">Daftar Twibbon</h4>
                    <button class="btn btn-primary" id="addBtn">
                        <i class="bi bi-plus-lg"></i> Tambah Twibbon
                    </button>
                </div>

                <div class="table-responsive">
                    <table id="datatables-list" class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>File</th>
                                <th>Informasi</th>
                                <th>Caption</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="twibbonModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="twibbonForm" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="idtwibbon" id="idtwibbon">

                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold" id="modalTitle">Tambah Twibbon</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Judul Twibbon</label>
                                <input type="text" name="judul" id="judul" class="form-control" placeholder="Contoh: Twibbon HUT RI" required>
                            </div>
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">URL Campaign</label>
                                <input type="text" name="url" id="url" class="form-control" placeholder="contoh: brevet-pajak" required>
                                <small class="text-muted">Gunakan huruf kecil dan tanda hubung (-)</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">File PNG (Transparan)</label>
                                <div class="custom-file">
                                    <input type="file" name="file" class="custom-file-input" id="fileInput" accept="image/png">
                                    <label class="custom-file-label" for="fileInput">Pilih file...</label>
                                </div>
                                <div id="preview-area" class="mt-3 text-center">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Caption / Deskripsi</label>
                                <textarea name="caption" id="caption_summernote" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Simpan Data</button>
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
        $('#caption_summernote').summernote({
            placeholder: 'Tulis caption atau cara penggunaan twibbon di sini...',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        // 2. Inisialisasi DataTable
        let table = $('#datatables-list').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= base_url('sw-admin/twibbon/datatables') ?>",
                type: "POST",
                data: function(d) {
                    d[csrfName] = csrfHash;
                },
                dataSrc: function(json) {
                    updateCSRF(json.token); // GUNAKAN FUNGSI UPDATE DISINI
                    return json.data;
                }
            },
            columnDefs: [{
                targets: [0, 1, 4],
                orderable: false
            }]
        });

        // 3. Tombol Tambah
        $('#addBtn').click(function() {
            $('#twibbonForm')[0].reset();
            $('#idtwibbon').val('');
            $('#caption_summernote').summernote('code', '');
            $('#preview-area').html('');

            updateCSRF(csrfHash); // Pastikan token terbaru masuk ke input hidden

            $('#modalTitle').text('Tambah Twibbon');
            $('.custom-file-label').html('Pilih file...');
            $('#twibbonModal').modal('show');
        });

        // 4. Submit Form (Tambah & Edit)
        $('#twibbonForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            formData.set(csrfName, csrfHash); // Selalu set token terbaru ke form data

            $.ajax({
                url: "<?= base_url('sw-admin/twibbon/store') ?>",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#saveBtn').prop('disabled', true).text('Menyimpan...');
                },
                success: function(res) {
                    updateCSRF(res.token); // UPDATE DISINI
                    if (res.status === 'success') {
                        $('#twibbonModal').modal('hide');
                        table.ajax.reload(null, false);
                        Swal.fire("Berhasil!", res.message, "success");
                    } else {
                        Swal.fire("Gagal!", res.message, "error");
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 403) location.reload();
                },
                complete: function() {
                    $('#saveBtn').prop('disabled', false).text('Simpan Data');
                }
            });
        });

        // 5. Trigger Edit
        $(document).on('click', '.editBtn', function() {
            let id = $(this).data('id');

            $.ajax({
                url: "<?= base_url('sw-admin/twibbon/edit') ?>",
                type: "POST",
                data: {
                    idtwibbon: id,
                    [csrfName]: csrfHash
                },
                dataType: "JSON",
                success: function(res) {
                    updateCSRF(res.token); // PERBAIKAN: ambil dari res.token, bukan res.data.token

                    $('#idtwibbon').val(id);
                    $('#judul').val(res.data.judul);
                    $('#url').val(res.data.url);
                    $('#caption_summernote').summernote('code', res.data.caption);

                    if (res.data.file) {
                        $('#preview-area').html(`
                            <label class="d-block small">File saat ini:</label>
                            <img src="<?= base_url('uploads/twibbon/thumbnails/') ?>/${res.data.file}" 
                                 class="img-fluid rounded border shadow-sm" style="max-height: 150px;">
                        `);
                    }

                    $('#modalTitle').text('Edit Twibbon');
                    $('#twibbonModal').modal('show');
                }
            });
        });

        // 6. Validasi URL Unik (Debounce)
        let typingTimer;
        let doneTypingInterval = 500;

        $('#url').on('input', function() {
            clearTimeout(typingTimer);
            let urlVal = $(this).val();
            let idtwibbon = $('#idtwibbon').val();

            if (urlVal.length > 3) {
                typingTimer = setTimeout(function() {
                    $.ajax({
                        url: "<?= base_url('sw-admin/twibbon/cek-url') ?>",
                        type: "POST",
                        data: {
                            url: urlVal,
                            idtwibbon: idtwibbon,
                            [csrfName]: csrfHash
                        },
                        success: function(res) {
                            updateCSRF(res.token); // UPDATE DISINI

                            if (res.status === 'taken') {
                                $('#url').addClass('is-invalid').removeClass('is-valid');
                                $('#saveBtn').prop('disabled', true);
                                if (!$('#url-feedback').length) {
                                    $('#url').after('<div id="url-feedback" class="invalid-feedback">URL ini sudah digunakan.</div>');
                                }
                            } else {
                                $('#url').addClass('is-valid').removeClass('is-invalid');
                                $('#saveBtn').prop('disabled', false);
                                $('#url-feedback').remove();
                            }
                        }
                    });
                }, doneTypingInterval);
            }
        });

        // 7. File Input Label
        $('#fileInput').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });

    // --- 8. FUNGSI SHARE WHATSAPP ---
    $(document).on('click', '.shareBtn', function() {
        let judul = $(this).data('judul');
        let slug = $(this).data('url');

        // Alamat URL publik twibbon Anda (sesuaikan dengan route publik Anda)
        let urlPublik = "<?= base_url('twibbon') ?>/" + slug;

        // Format pesan WhatsApp
        let pesan = "*Halo! Ayo gunakan twibbon:* \n\n" +
            "*" + judul + "*\n\n" +
            "Klik link di bawah ini untuk memasang foto Anda:\n" +
            urlPublik;

        // Encode pesan agar aman untuk URL
        let whatsappUrl = "https://api.whatsapp.com/send?text=" + encodeURIComponent(pesan);

        // Buka di tab baru
        window.open(whatsappUrl, '_blank');
    });

    // FUNGSI UTAMA PENJAGA TOKEN
    function updateCSRF(newToken) {
        if (newToken) {
            csrfHash = newToken; // Update variabel JS global
            $('input[name="' + csrfName + '"]').val(newToken); // Update SEMUA input hidden CSRF
        }
    }
</script>
<?= $this->endSection(); ?>
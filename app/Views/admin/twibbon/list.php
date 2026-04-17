<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="card card-flush shadow-sm border-0">
                
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title flex-column align-items-start">
                        <span class="card-label fw-bold text-gray-800">Daftar Twibbon</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Kelola frame campaign dan caption untuk media sosial.</span>
                    </div>
                    
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-twibbon-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Twibbon..." />
                        </div>

                        <button type="button" class="btn btn-primary fw-bold" id="addBtn">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah Twibbon
                        </button>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatables-list" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-50px text-center">No</th>
                                    <th class="min-w-100px text-center">File</th>
                                    <th class="min-w-200px">Informasi</th>
                                    <th class="min-w-300px">Caption</th>
                                    <th class="text-center min-w-100px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="twibbonModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content rounded border-0">
            <form id="twibbonForm" enctype="multipart/form-data" class="form">
                <?= csrf_field() ?>
                <input type="hidden" name="idtwibbon" id="idtwibbon">

                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h2 class="fw-bold" id="modalTitle">Tambah Twibbon</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>

                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">
                    <div class="row g-7">
                        
                        <div class="col-md-6">
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Judul Twibbon</label>
                                <input type="text" name="judul" id="judul" class="form-control form-control-solid" placeholder="Contoh: Twibbon HUT RI" required>
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">URL Campaign</label>
                                <input type="text" name="url" id="url" class="form-control form-control-solid" placeholder="contoh: brevet-pajak" required>
                                <div class="text-muted fs-7 mt-2"><i class="ki-duotone ki-information-5 text-gray-500 fs-6 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> Gunakan huruf kecil dan tanda hubung (-)</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">File PNG (Transparan)</label>
                                <input type="file" name="file" class="form-control form-control-solid" id="fileInput" accept="image/png">
                                <div id="preview-area" class="mt-4 text-center">
                                    </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="fv-row mb-2">
                                <label class="required fs-6 fw-semibold mb-2">Caption / Deskripsi</label>
                                <textarea name="caption" id="caption_summernote" class="form-control form-control-solid"></textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer border-0 p-5 p-lg-10 pt-0 justify-content-end">
                    <button type="button" class="btn btn-light me-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="saveBtn">
                        <span class="indicator-label"><i class="ki-duotone ki-save-2 fs-3 me-1"><span class="path1"></span><span class="path2"></span></i> Simpan Data</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    // FUNGSI UTAMA PENJAGA TOKEN (Dibiarkan di luar agar global)
    function updateCSRF(newToken) {
        if (newToken) {
            csrfHash = newToken; // Update variabel JS global
            $('input[name="' + csrfName + '"]').val(newToken); // Update SEMUA input hidden CSRF
        }
    }

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

        // 2. Inisialisasi DataTable dg Custom DOM Metronic
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
                    updateCSRF(json.token); // UPDATE CSRF
                    return json.data;
                }
            },
            columnDefs: [{
                targets: [0, 1, 4],
                orderable: false
            }],
            drawCallback: function(settings) {
                // Beri tahu Metronic untuk membaca ulang DOM dan mengaktifkan dropdown baru
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            }
        });

        // Fitur Pencarian Custom yang menyatu dengan UI Card Header Metronic
        $('[data-kt-twibbon-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        // 3. Tombol Tambah
        $('#addBtn').click(function() {
            $('#twibbonForm')[0].reset();
            $('#idtwibbon').val('');
            $('#caption_summernote').summernote('code', '');
            $('#preview-area').html('');
            
            // Reset validation class
            $('#url').removeClass('is-invalid is-valid');
            $('#url-feedback').remove();
            $('#saveBtn').prop('disabled', false);

            updateCSRF(csrfHash); // Pastikan token terbaru masuk ke input hidden

            $('#modalTitle').text('Tambah Twibbon');
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
                    $('#saveBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...');
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
                    $('#saveBtn').prop('disabled', false).html('<i class="ki-duotone ki-save-2 fs-3 me-1"><span class="path1"></span><span class="path2"></span></i> Simpan Data');
                }
            });
        });

        // 5. Trigger Edit
        $(document).on('click', '.editBtn', function() {
            let id = $(this).data('id');
            
            // Reset validation class
            $('#url').removeClass('is-invalid is-valid');
            $('#url-feedback').remove();
            $('#saveBtn').prop('disabled', false);

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
                            <label class="d-block text-start fs-7 fw-semibold text-gray-600 mb-2">File saat ini:</label>
                            <div class="bg-light rounded p-4 border border-dashed border-gray-300">
                                <img src="<?= base_url('uploads/twibbon/thumbnails/') ?>/${res.data.file}" 
                                     class="img-fluid rounded shadow-sm" style="max-height: 150px; object-fit: contain;">
                            </div>
                        `);
                    } else {
                        $('#preview-area').html('');
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
                                    $('#url').after('<div id="url-feedback" class="invalid-feedback fw-bold">URL ini sudah digunakan. Silakan cari URL lain.</div>');
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

        // 7. File Input Label (Diadaptasi untuk input standar tanpa custom-file-label BS4)
        // Logika ini dibiarkan agar tidak merusak JS lama, walau UI Metronic tidak terlalu membutuhkannya.
        $('#fileInput').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            // Jika Anda masih ingin memanipulasi elemen label, letakkan kelas .custom-file-label di dekatnya
            // Namun karena Bootstrap 5 native file input sudah menampikan nama file, kode ini opsional.
        });

        // 8. FUNGSI SHARE WHATSAPP
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

    });
</script>
<?= $this->endSection(); ?>
<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card shadow-sm border-0 mb-7 bg-light">
                <div class="card-body p-5">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-auto mb-3 mb-lg-0">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-4">
                                    <div class="symbol-label bg-light-primary">
                                        <i class="ki-duotone ki-filter text-primary fs-2x">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-gray-900 fs-5">Filter Data</span>
                                    <span class="text-muted fw-semibold fs-7">Berdasarkan penempatan iklan</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-auto ms-auto">
                            <div class="d-flex flex-column flex-sm-row gap-3">
                                <div class="min-w-200px">
                                    <select id="filter-status" class="form-select form-select-solid fw-bold" data-control="select2" data-hide-search="true" data-placeholder="Semua Penempatan">
                                        <option value="" selected>Semua Penempatan</option>
                                        <option value="depan">✨ Iklan Tampilan Depan</option>
                                        <option value="modal">🚀 Iklan POP UP (Modal)</option>
                                        <option value="nav">📱 Nav-bar Banner</option>
                                    </select>
                                </div>

                                <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#tambah_iklan">
                                    <i class="ki-duotone ki-plus fs-2"></i> Tambah Iklan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-flush shadow-sm border-0">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Daftar Iklan</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Kelola banner promosi dan penempatan iklan aplikasi</span>
                    </h3>
                    <div class="card-toolbar">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-iklan-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Iklan..." />
                        </div>
                    </div>
                </div>

                <div class="card-body pt-5">
                    <div class="table-responsive">
                        <table id="datatable-list" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Informasi Iklan</th>
                                    <th class="text-center min-w-150px">Preview</th>
                                    <th class="min-w-150px">Link URL</th>
                                    <th class="text-center min-w-150px">Penempatan</th>
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
<div class="modal fade" id="tambah_iklan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded border-0">

            <div class="modal-header pb-0 border-0 justify-content-between">
                <h2 class="fw-bold">Tambah Iklan Baru</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <form action="<?= base_url('sw-admin/iklan/store'); ?>" method="POST" enctype="multipart/form-data" class="form">
                <?= csrf_field() ?>
                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Gambar Iklan</label>
                        <input type="file" name="file" class="form-control form-control-solid" accept="image/*" required>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Nama Iklan</label>
                        <input type="text" name="nama" class="form-control form-control-solid" placeholder="Contoh: Promo Weekend" required>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">URL Tujuan <span class="text-muted fs-8 fw-normal">(Opsional)</span></label>
                        <input type="url" name="url" class="form-control form-control-solid" placeholder="https://domain.com/promo">
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Nama Tombol <span class="text-muted fs-8 fw-normal">(Opsional)</span></label>
                        <input type="text" name="text" class="form-control form-control-solid" placeholder="Daftar Sekarang">
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Penempatan</label>
                        <select name="status_iklan" class="form-select form-select-solid" data-control="select2" data-hide-search="true" required>
                            <option value="">Pilih Penempatan...</option>
                            <option value="modal">🚀 Iklan POP UP (Modal)</option>
                            <option value="depan">✨ Iklan Tampilan Depan</option>
                            <option value="nav">📱 Nav-bar Banner</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer border-0 p-5 p-lg-10 pt-0">
                    <button type="button" class="btn btn-light me-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">
                        <span class="indicator-label"><i class="ki-duotone ki-save-2 fs-3 me-1"><span class="path1"></span><span class="path2"></span></i> Simpan Iklan</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="modal_edit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded border-0">

            <div class="modal-header pb-0 border-0 justify-content-between">
                <h2 class="fw-bold">Edit Konfigurasi Iklan</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <form action="<?= base_url('sw-admin/iklan/update'); ?>" method="POST" enctype="multipart/form-data" class="form">
                <?= csrf_field() ?>
                <input type="hidden" name="id_iklan" id="edit_id">
                <input type="hidden" name="file_lama" id="edit_file_lama">

                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">

                    <div class="border border-dashed border-gray-300 rounded p-5 bg-light text-center mb-7">
                        <img src="" id="old_preview" class="img-fluid rounded shadow-sm mb-4" style="max-height: 150px; object-fit: contain;">
                        <input type="file" name="file" class="form-control form-control-solid bg-white">
                        <div class="text-muted fs-7 mt-2">Kosongkan file input di atas jika tidak ingin mengganti gambar saat ini.</div>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Nama Iklan</label>
                        <input type="text" name="nama" id="edit_nama" class="form-control form-control-solid" required>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">URL Tujuan <span class="text-muted fs-8 fw-normal">(Opsional)</span></label>
                        <input type="url" name="url" id="edit_url" class="form-control form-control-solid">
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Nama Tombol <span class="text-muted fs-8 fw-normal">(Opsional)</span></label>
                        <input type="text" name="text" id="edit_text" class="form-control form-control-solid" placeholder="Daftar Sekarang">
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Penempatan</label>
                        <select name="status_iklan" id="edit_status" class="form-select form-select-solid" data-control="select2" data-hide-search="true" required>
                            <option value="modal">🚀 Iklan POP UP</option>
                            <option value="depan">✨ Iklan Depan</option>
                            <option value="nav">📱 Nav-bar</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer border-0 p-5 p-lg-10 pt-0">
                    <button type="button" class="btn btn-light me-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info fw-bold text-white">
                        <span class="indicator-label"><i class="ki-duotone ki-check fs-3 me-1"></i> Update Perubahan</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // 1. DataTables Server-Side (LOGIKA ASLI)
        var table = $('#datatable-list').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('sw-admin/iklan/datatables') ?>",
                "type": "POST",
                "data": function(d) {
                    d.filter_status = $('#filter-status').val();
                    d[csrfName] = csrfHash;
                },
                "dataSrc": function(json) {
                    if (json.token) {
                        csrfHash = json.token;
                        $('input[name="<?= csrf_token() ?>"]').val(json.token);
                    }
                    return json.data;
                }
            },
            "columnDefs": [{
                    "targets": [0],
                    "className": "text-gray-800 fw-bold"
                },
                {
                    "targets": [1, 4],
                    "orderable": false
                },
                {
                    "className": "text-center",
                    "targets": [1, 3, 4]
                }
            ],
            "drawCallback": function(settings) {
                // Inisialisasi ulang dropdown menu dari metronic
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            }
        });

        // Event listener Filter Datatable bawaan Anda
        $('#filter-status').on('change', function() {
            table.draw();
        });

        // Event listener Search TextBox custom Metronic
        $('[data-kt-iklan-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        // 2. Ajax Edit (LOGIKA ASLI)
        $(document).on('click', '.edit-iklan', function(e) {
            e.preventDefault();
            var id = $(this).data('iklan');

            $.ajax({
                url: "<?= base_url('sw-admin/iklan/edit') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                headers: {
                    'X-CSRF-TOKEN': csrfHash
                },
                success: function(response) {
                    if (response.status) {
                        var d = response.data;
                        $('#edit_id').val(id);
                        $('#edit_nama').val(d.nama);
                        $('#edit_url').val(d.url);
                        $('#edit_text').val(d.text);

                        // Select2 auto update UI
                        $('#edit_status').val(d.status_iklan).trigger('change');

                        $('#old_preview').attr('src', '<?= base_url('uploads/iklan/thumbnails/') ?>/' + d.file);
                        $('#edit_file_lama').val(d.file);
                        $('#modal_edit').modal('show');

                        if (response.token) {
                            $('input[name="<?= csrf_token() ?>"]').val(response.token);
                            csrfHash = response.token; // Pastikan variable global csrfHash juga terupdate
                        }
                    } else {
                        Swal.fire('Gagal', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Status: ' + xhr.status + ' - Terjadi kesalahan', 'error');
                }
            });
        });

        // 3. Reset form saat nambah iklan baru
        $('[data-bs-target="#tambah_iklan"]').on('click', function(e) {
            e.preventDefault();
            $('#tambah_iklan form')[0].reset();
            // Reset Select2 ui
            $('#tambah_iklan select').val('').trigger('change');

            // Pastikan token di form tambah adalah yang terbaru
            $('#tambah_iklan input[name="' + csrfName + '"]').val(csrfHash);
        });

    });
</script>
<?= $this->endSection(); ?>
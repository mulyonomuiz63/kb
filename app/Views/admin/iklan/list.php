<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <div class="widget shadow-sm border-0 p-4 bg-white" style="border-radius: 15px;">

                <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">Manajemen Konten Iklan</h4>
                        <p class="text-muted small mb-0">Kelola banner promosi dan penempatan iklan aplikasi</p>
                    </div>
                    <button class="btn btn-primary px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#tambah_iklan" data-toggle="modal" data-target="#tambah_iklan">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Iklan
                    </button>
                </div>

                <hr class="mb-4 text-muted opacity-25">

                <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background: #fdfdfd;">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-auto me-auto">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary-soft p-2 rounded-3 me-3">
                                        <i class="bi bi-funnel text-primary fs-5"></i>
                                    </div>
                                    <div class="ml-2">
                                        <h6 class="mb-0 fw-bold">Filter Data</h6>
                                        <small class="text-muted">Filter iklan berdasarkan penempatan</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mt-3 mt-md-0">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 10px 0 0 10px;">
                                        <i class="bi bi-layers"></i>
                                    </span>
                                    <select id="filter-status" class="form-select border-start-0 ps-0 shadow-none" style="border-radius: 0 10px 10px 0; height: 45px; cursor: pointer;">
                                        <option value="" selected>Semua Penempatan</option>
                                        <option value="depan">✨ Iklan Tampilan Depan</option>
                                        <option value="modal">🚀 Iklan POP UP (Modal)</option>
                                        <option value="nav">📱 Nav-bar Banner</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="datatable-list" class="table table-hover border-0 w-100">
                        <thead>
                            <tr class="bg-light">
                                <th class="border-0 py-3">Informasi Iklan</th>
                                <th class="border-0 py-3 text-center">Preview</th>
                                <th class="border-0 py-3">Link URL</th>
                                <th class="border-0 py-3 text-center">Penempatan</th>
                                <th class="border-0 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="align-middle"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tambah_iklan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header border-0 bg-primary p-4">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center">
                    <i class="bi bi-plus-circle-fill me-2"></i> Tambah Iklan Baru
                </h5>
                <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg fs-4"></i></button>
            </div>
            <form action="<?= base_url('sw-admin/iklan/store'); ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Gambar Iklan</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-image"></i></span>
                            <input type="file" name="file" class="form-control bg-light border-0 shadow-none" accept="image/*" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Iklan</label>
                        <input type="text" name="nama" class="form-control bg-light border-0 p-3 shadow-none" placeholder="Contoh: Promo Weekend" style="border-radius: 10px;" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">URL Tujuan</label>
                        <input type="url" name="url" class="form-control bg-light border-0 p-3 shadow-none" placeholder="https://domain.com/promo" style="border-radius: 10px;">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Penempatan</label>
                        <select name="status_iklan" class="form-select bg-light border-0 p-3 shadow-none" style="border-radius: 10px;" required>
                            <option value="modal">🚀 Iklan POP UP (Modal)</option>
                            <option value="depan">✨ Iklan Tampilan Depan</option>
                            <option value="nav">📱 Nav-bar Banner</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm" style="border-radius: 12px;">
                        Simpan Iklan <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_edit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header border-0 bg-info p-4 text-white">
                <h5 class="modal-title fw-bold d-flex align-items-center">
                    <i class="bi bi-pencil-square text-white me-2"></i> Edit Konfigurasi Iklan
                </h5>
                <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg fs-4"></i></button>
            </div>
            <form action="<?= base_url('sw-admin/iklan/update'); ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="id_iklan" id="edit_id">
                <input type="hidden" name="file_lama" id="edit_file_lama">
                <div class="modal-body p-4">
                    <div class="text-center mb-4 p-3 border border-dashed rounded-3 bg-light">
                        <img src="" id="old_preview" class="img-fluid rounded shadow-sm mb-2" style="max-height: 150px; display: block; margin: 0 auto;">
                        <input type="file" name="file" class="form-control mt-3 bg-white shadow-none">
                        <small class="text-muted d-block mt-2 font-italic">Kosongkan jika tidak ingin mengganti gambar</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Iklan</label>
                        <input type="text" name="nama" id="edit_nama" class="form-control bg-light border-0 p-3 shadow-none" style="border-radius: 10px;" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">URL Tujuan</label>
                        <input type="url" name="url" id="edit_url" class="form-control bg-light border-0 p-3 shadow-none" style="border-radius: 10px;">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Penempatan</label>
                        <select name="status_iklan" id="edit_status" class="form-select bg-light border-0 p-3 shadow-none" style="border-radius: 10px;" required>
                            <option value="modal">🚀 Iklan POP UP</option>
                            <option value="depan">✨ Iklan Depan</option>
                            <option value="nav">📱 Nav-bar</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-info text-white w-100 py-3 fw-bold shadow-sm" style="border-radius: 12px;">
                        Update Perubahan <i class="bi bi-check-circle ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-primary-soft {
        background-color: rgba(13, 110, 253, 0.1);
    }

    .zoom {
        transition: transform .3s ease;
    }

    .zoom:hover {
        transform: scale(1.05);
    }


    .badge {
        font-weight: 600;
        padding: 0.5em 0.8em;
        border-radius: 6px;
    }
</style>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
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
                    }
                    return json.data;
                }
            },
            "columnDefs": [{
                    "targets": [1, 4],
                    "orderable": false
                },
                {
                    "className": "text-center",
                    "targets": [1, 3, 4]
                }
            ],
            "language": {
                "searchPlaceholder": "Cari iklan...",
                "lengthMenu": "_MENU_",
                "paginate": {
                    "previous": "<",
                    "next": ">"
                }
            }
        });

        $('#filter-status').on('change', function() {
            table.draw();
        });

        // Ajax Edit
        $(document).on('click', '.edit-iklan', function() {
            var id = $(this).data('iklan');

            $.ajax({
                url: "<?= base_url('sw-admin/iklan/edit') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                headers: {
                    // KIRIM TOKEN DI HEADER
                    'X-CSRF-TOKEN': csrfHash
                },
                success: function(response) {
                    if (response.status) {
                        var d = response.data;
                        $('#edit_id').val(id);
                        $('#edit_nama').val(d.nama);
                        $('#edit_url').val(d.url);
                        $('#edit_status').val(d.status_iklan);
                        $('#old_preview').attr('src', '<?= base_url('uploads/iklan/thumbnails/') ?>/' + d.file);
                        $('#edit_file_lama').val(d.file);
                        $('#modal_edit').modal('show');

                        // PENTING: Update token untuk request selanjutnya
                        if (response.token) {
                            $('input[name="<?= csrf_token() ?>"]').val(response.token);
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
    });

    $('[data-bs-target="#tambah_iklan"]').on('click', function(e) {
        e.preventDefault();
        $('#tambah_iklan form')[0].reset();
        // Pastikan token di form tambah adalah yang terbaru
        $('#tambah_iklan input[name="' + csrfName + '"]').val(csrfHash);
        $('#tambah_iklan').modal('show');
    });
</script>
<?= $this->endSection(); ?>
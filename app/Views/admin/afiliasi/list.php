<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card card-flush shadow-sm border-0">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title flex-column align-items-start">
                        <span class="card-label fw-bold text-gray-800">Manajemen Afiliasi</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Kelola data Afiliasi untuk sistem.</span>
                    </div>

                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-afiliasi-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Afiliasi..." />
                        </div>

                        <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#tambah_afiliasi">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah Afiliasi
                        </button>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatable-list" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 border-bottom border-gray-200">
                                    <th class="min-w-200px">Nama Afiliasi</th>
                                    <th class="text-center min-w-100px">Status</th>
                                    <th class="text-center min-w-100px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                <?php foreach ($afiliasi as $g) : ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <!-- Tambahkan class cursor-pointer dan event onclick -->
                                                <div class="symbol symbol-circle symbol-40px me-3 cursor-pointer" data-bs-toggle="tooltip" title="Klik untuk perbesar" onclick="lihatLogoUtuh('<?= base_url('uploads/afiliasi/' . ($g->logo ?: 'default.jpg')); ?>', '<?= htmlspecialchars($g->nama_afiliasi, ENT_QUOTES); ?>')">
                                                    <img src="<?= base_url('uploads/afiliasi/' . ($g->logo ?: 'default.jpg')); ?>" alt="<?= $g->nama_afiliasi; ?>" style="object-fit: cover;" />
                                                </div>
                                                <span class="text-gray-800 fw-bold fs-6"><?= $g->nama_afiliasi; ?></span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?= $g->status == 'A'
                                                ? '<span class="badge badge-light-success fw-bold px-3 py-2">Aktif</span>'
                                                : '<span class="badge badge-light-danger fw-bold px-3 py-2">Tidak Aktif</span>'; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-icon btn-light-primary btn-sm edit-afiliasi" data-afiliasi="<?= encrypt_url($g->idafiliasi); ?>" data-bs-toggle="tooltip" title="Edit Data">
                                                    <i class="ki-duotone ki-pencil fs-3"><span class="path1"></span><span class="path2"></span></i>
                                                </button>
                                                <a href="javascript:void(0)" data-url="<?= base_url('sw-admin/afiliasi/delete/' . encrypt_url($g->idafiliasi)); ?>" class="btn btn-icon btn-light-danger btn-sm btn-delete" data-bs-toggle="tooltip" title="Hapus Data">
                                                    <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambah_afiliasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <div class="modal-content rounded border-0">
            <!-- WAJIB multipart/form-data untuk upload file -->
            <form action="<?= base_url('sw-admin/afiliasi/store'); ?>" method="POST" class="form" enctype="multipart/form-data">
                <?= csrf_field(); ?>

                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h2 class="fw-bold">Tambah Afiliasi Baru</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>

                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">
                    <div class="d-flex flex-stack mb-5">
                        <div class="text-gray-700 fw-semibold fs-6">Daftar Afiliasi yang akan ditambahkan</div>
                        <button type="button" class="btn btn-sm btn-light-success fw-bold tambah-baris-afiliasi">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah Baris
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-3">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="ps-0">Nama Afiliasi</th>
                                    <th>Logo (Max 2MB)</th>
                                    <th class="w-50px pe-0"></th>
                                </tr>
                            </thead>
                            <tbody id="tbody-afiliasi">
                                <tr>
                                    <td class="ps-0">
                                        <input type="text" name="nama_afiliasi[]" placeholder="Nama Afiliasi" required class="form-control form-control-solid">
                                    </td>
                                    <td>
                                        <input type="file" name="logo[]" accept=".png, .jpg, .jpeg" class="form-control form-control-solid">
                                    </td>
                                    <td class="text-end pe-0 w-50px">
                                        <button type="button" class="btn btn-icon btn-light-danger btn-sm disabled" disabled>
                                            <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer border-0 p-5 p-lg-10 pt-0">
                    <button type="button" class="btn btn-light me-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">
                        <span class="indicator-label"><i class="ki-duotone ki-save-2 fs-3 me-1"></i> Simpan Data</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="edit_afiliasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content rounded border-0">
            <form action="<?= base_url('sw-admin/afiliasi/update'); ?>" method="POST" class="form" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="idafiliasi" id="idafiliasi">
                <input type="hidden" name="logo_lama" id="logo_lama">

                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h2 class="fw-bold">Edit Afiliasi</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>

                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Nama Afiliasi</label>
                        <input type="text" name="nama_afiliasi" id="edit_nama_afiliasi" class="form-control form-control-solid" required>
                    </div>

                    <div class="row g-5 mb-7">
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Status</label>
                            <!-- Nilai disesuaikan dengan Enum A dan T -->
                            <select name="status" id="edit_status" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-dropdown-parent="#edit_afiliasi" required>
                                <option value="A">Aktif (A)</option>
                                <option value="T">Tidak Aktif (T)</option>
                            </select>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="fs-6 fw-semibold mb-2">Ganti Logo <span class="text-muted fs-8 fw-normal">(Opsional)</span></label>
                            <input type="file" name="logo" accept=".png, .jpg, .jpeg" class="form-control form-control-solid">
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-5 p-lg-10 pt-0">
                    <button type="button" class="btn btn-light me-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info fw-bold text-white">
                        <span class="indicator-label"><i class="ki-duotone ki-check fs-3 me-1"></i> Update Data</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Lihat Logo Full -->
<div class="modal fade" id="modal_lihat_logo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded border-0 shadow-lg">
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h2 class="fw-bold text-gray-800" id="judul_preview_logo">Logo Afiliasi</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body text-center p-10 bg-light">
                <!-- Tempat gambar akan ditampilkan -->
                <img src="" id="gambar_preview_full" class="img-fluid rounded shadow-sm" alt="Logo Full" style="max-height: 500px; object-fit: contain;" />
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        var table = $('#datatable-list').DataTable({
            "ordering": false,
            drawCallback: function(settings) {
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            }
        });

        $('[data-kt-afiliasi-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        // 2. Tambah Baris Dinamis (Diperbarui)
        $('.tambah-baris-afiliasi').click(function() {
            const row = `
            <tr>
                <td class="ps-0">
                    <input type="text" name="nama_afiliasi[]" placeholder="Nama Afiliasi" required class="form-control form-control-solid">
                </td>
                <td>
                    <input type="file" name="logo[]" accept=".png, .jpg, .jpeg" class="form-control form-control-solid">
                </td>
                <td class="text-end pe-0 w-50px">
                    <button type="button" class="btn btn-icon btn-light-danger btn-sm hapus-baris">
                        <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    </button>
                </td>
            </tr>`;
            $('#tbody-afiliasi').append(row);
        });

        $(document).on('click', '.hapus-baris', function() {
            $(this).closest('tr').remove();
        });

        // 3. AJAX Edit Afiliasi
        $('.edit-afiliasi').click(function(e) {
            e.preventDefault();
            const idafiliasi = $(this).data('afiliasi');
            const csrfName = "<?= csrf_token() ?>";
            const csrfToken = $('.csrf-token').val() || $('input[name="<?= csrf_token() ?>"]').val();

            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-admin/afiliasi/edit') ?>",
                data: {
                    idafiliasi: idafiliasi,
                    [csrfName]: csrfToken
                },
                dataType: 'JSON',
                success: function(data) {
                    if (data.token_baru) {
                        $('.csrf-token').val(data.token_baru);
                        $('input[name="<?= csrf_token() ?>"]').val(data.token_baru);
                    }

                    const p = data.afiliasi;
                    $("#idafiliasi").val(idafiliasi);
                    $("#logo_lama").val(p.logo);
                    $("#edit_nama_afiliasi").val(p.nama_afiliasi);

                    if ($('#edit_status').hasClass("select2-hidden-accessible")) {
                        $("#edit_status").val(p.status).trigger('change');
                    } else {
                        $("#edit_status").val(p.status);
                    }

                    $('#edit_afiliasi').modal('show');
                }
            });
        });
    });
    // Fungsi untuk memanggil gambar full ke dalam modal
    function lihatLogoUtuh(src, nama) {
        // Ubah URL gambar pada tag img di dalam modal
        $('#gambar_preview_full').attr('src', src);

        // Ubah judul modal sesuai nama afiliasi
        $('#judul_preview_logo').text('Logo: ' + nama);

        // Tampilkan modal
        $('#modal_lihat_logo').modal('show');
    }
</script>
<?= $this->endSection(); ?>
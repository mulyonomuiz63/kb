<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="card card-flush shadow-sm border-0">
                
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title flex-column align-items-start">
                        <span class="card-label fw-bold text-gray-800">Manajemen PIC</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Kelola data Person in Charge (PIC) untuk sistem.</span>
                    </div>
                    
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-pic-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari PIC..." />
                        </div>

                        <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#tambah_pic">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah PIC
                        </button>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatable-list" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 border-bottom border-gray-200">
                                    <th class="min-w-200px">Nama PIC</th>
                                    <th class="min-w-200px">Email</th>
                                    <th class="text-center min-w-100px">Status</th>
                                    <th class="text-center min-w-100px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                <?php foreach ($pic as $g) : ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-circle symbol-40px me-3">
                                                    <div class="symbol-label bg-light-primary text-primary fw-bold fs-6">
                                                        <?= strtoupper(substr($g->nama_pic, 0, 1)); ?>
                                                    </div>
                                                </div>
                                                <span class="text-gray-800 fw-bold fs-6"><?= $g->nama_pic; ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-gray-700"><?= $g->email; ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?= $g->is_active == 1 
                                                ? '<span class="badge badge-light-success fw-bold px-3 py-2">Aktif</span>' 
                                                : '<span class="badge badge-light-danger fw-bold px-3 py-2">Non-Aktif</span>'; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-icon btn-light-primary btn-sm edit-pic" data-pic="<?= encrypt_url($g->idpic); ?>" data-bs-toggle="tooltip" title="Edit Data">
                                                    <i class="ki-duotone ki-pencil fs-3"><span class="path1"></span><span class="path2"></span></i>
                                                </button>
                                                <a href="javascript:void(0)" data-url="<?= base_url('sw-admin/pic/delete/' . encrypt_url($g->idpic)); ?>" class="btn btn-icon btn-light-danger btn-sm btn-delete" data-bs-toggle="tooltip" title="Hapus Data">
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
<div class="modal fade" id="tambah_pic" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <div class="modal-content rounded border-0">
            <form action="<?= base_url('sw-admin/pic/store'); ?>" method="POST" class="form">
                <?= csrf_field(); ?>
                
                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h2 class="fw-bold">Tambah PIC Baru</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                
                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">
                    <div class="d-flex flex-stack mb-5">
                        <div class="text-gray-700 fw-semibold fs-6">Daftar PIC yang akan ditambahkan</div>
                        <button type="button" class="btn btn-sm btn-light-success fw-bold tambah-baris-pic">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah Baris
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-3">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="ps-0">Nama Lengkap</th>
                                    <th>Alamat Email</th>
                                    <th>Sandi Akun</th>
                                    <th class="w-50px pe-0"></th>
                                </tr>
                            </thead>
                            <tbody id="tbody-pic">
                                <tr>
                                    <td class="ps-0">
                                        <input type="text" name="nama_pic[]" placeholder="Nama PIC" required class="form-control form-control-solid">
                                    </td>
                                    <td>
                                        <input type="email" name="email[]" placeholder="email@domain.com" required class="form-control form-control-solid">
                                    </td>
                                    <td>
                                        <input type="text" name="sandi[]" value="Sandi@#" required class="form-control form-control-solid">
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
                        <span class="indicator-label"><i class="ki-duotone ki-save-2 fs-3 me-1"><span class="path1"></span><span class="path2"></span></i> Simpan Data</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_pic" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content rounded border-0">
            <form action="<?= base_url('sw-admin/pic/update'); ?>" method="POST" class="form">
                <?= csrf_field() ?>
                <input type="hidden" name="idpic" id="idpic">
                
                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h2 class="fw-bold">Edit PIC</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                
                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">
                    
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Nama PIC</label>
                        <input type="text" name="nama_pic" id="nama_pic" class="form-control form-control-solid" required>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Alamat Email</label>
                        <input type="email" name="email" id="email" class="form-control form-control-solid" required>
                    </div>

                    <div class="row g-5 mb-7">
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Status Aktif</label>
                            <select name="active" id="active" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-dropdown-parent="#edit_pic" required>
                                <option value="1">Aktif</option>
                                <option value="0">Non-Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="fs-6 fw-semibold mb-2">Ganti Sandi <span class="text-muted fs-8 fw-normal">(Opsional)</span></label>
                            <input type="text" name="sandi" class="form-control form-control-solid" placeholder="Kosongkan jika tetap">
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

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // 1. DataTables Init dengan DOM kustom Metronic
        var table = $('#datatable-list').DataTable({
            "ordering": false,
            drawCallback: function(settings) {
                // Beri tahu Metronic untuk membaca ulang DOM dan mengaktifkan dropdown baru
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            }
        });

        // Fitur Pencarian Custom yang menyatu dengan UI Card Header Metronic
        $('[data-kt-pic-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        // 2. Tambah Baris Dinamis (Multiple Insert)
        $('.tambah-baris-pic').click(function() {
            const row = `
            <tr>
                <td class="ps-0"><input type="text" name="nama_pic[]" placeholder="Nama PIC" required class="form-control form-control-solid"></td>
                <td><input type="email" name="email[]" placeholder="email@domain.com" required class="form-control form-control-solid"></td>
                <td><input type="text" name="sandi[]" required value="Sandi@#" class="form-control form-control-solid"></td>
                <td class="text-end pe-0 w-50px">
                    <button type="button" class="btn btn-icon btn-light-danger btn-sm hapus-baris">
                        <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    </button>
                </td>
            </tr>`;
            $('#tbody-pic').append(row);
        });

        $(document).on('click', '.hapus-baris', function() {
            $(this).closest('tr').remove();
        });

        // 3. AJAX Edit PIC (LOGIKA ASLI)
        $('.edit-pic').click(function(e) {
            e.preventDefault(); // Mencegah scrolling ke atas jika tag anchor
            const idpic = $(this).data('pic');
            const csrfName = "<?= csrf_token() ?>";
            const csrfToken = $('.csrf-token').val() || $('input[name="<?= csrf_token() ?>"]').val();

            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-admin/pic/edit') ?>",
                data: { idpic: idpic, [csrfName]: csrfToken },
                dataType: 'JSON',
                success: function(data) {
                    // Update CSRF Token agar klik selanjutnya tidak 403
                    if (data.token_baru) { 
                        // Update semua instance token di halaman
                        $('.csrf-token').val(data.token_baru); 
                        $('input[name="<?= csrf_token() ?>"]').val(data.token_baru); 
                    }

                    // Isi Modal (Gunakan data.pic sesuai controller)
                    const p = data.pic;
                    $("#idpic").val(idpic); // Tetap gunakan yang terenkripsi untuk keamanan
                    $("#nama_pic").val(p.nama_pic);
                    $("#email").val(p.email);
                    
                    // Update Select2 jika ada
                    if ($('#active').hasClass("select2-hidden-accessible")) {
                        $("#active").val(p.is_active).trigger('change');
                    } else {
                        $("#active").val(p.is_active);
                    }
                    
                    $('#edit_pic').modal('show');
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>
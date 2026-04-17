<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="card card-flush shadow-sm border-0">
                
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title flex-column align-items-start">
                        <span class="card-label fw-bold text-gray-800">Manajemen Kelas</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Kelola data kelas untuk siswa.</span>
                    </div>
                    
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-kelas-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Kelas..." />
                        </div>

                        <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#tambah_kelas">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah Kelas
                        </button>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatables-list" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 border-bottom border-gray-200">
                                    <th class="w-50px text-center">No</th>
                                    <th class="min-w-200px">Nama Kelas</th>
                                    <th class="text-center min-w-150px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                <?php $no = 1; foreach ($kelas as $k) : ?>
                                    <tr>
                                        <td class="text-center text-gray-800 fw-bold"><?= $no++; ?></td>
                                        <td>
                                            <span class="badge badge-light-info fw-bold px-4 py-2 fs-6"><?= $k->nama_kelas; ?></span>
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                Aksi <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                            </a>
                                            
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4 text-start" data-kt-menu="true">
                                                <div class="menu-item px-3">
                                                    <a href="javascript:void(0)" class="menu-link px-3 edit-kelas" data-id="<?= encrypt_url($k->id_kelas); ?>">
                                                        <i class="ki-duotone ki-pencil fs-4 me-2 text-primary"><span class="path1"></span><span class="path2"></span></i> Edit Kelas
                                                    </a>
                                                </div>
                                                
                                                <div class="separator mt-3 opacity-75"></div>
                                                
                                                <div class="menu-item px-3">
                                                    <a href="javascript:void(0)" class="menu-link px-3 text-danger btn-delete" data-url="<?= base_url('sw-admin/kelas/delete/' . encrypt_url($k->id_kelas)); ?>">
                                                        <i class="ki-duotone ki-trash fs-4 me-2 text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Hapus Kelas
                                                    </a>
                                                </div>
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
<div class="modal fade" id="tambah_kelas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content rounded border-0">
            <form action="<?= base_url('sw-admin/kelas/store'); ?>" method="POST" class="form">
                <?= csrf_field() ?>
                
                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h2 class="fw-bold">Tambah Kelas Baru</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                
                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">
                    <div class="d-flex flex-stack mb-5">
                        <div class="text-gray-700 fw-semibold fs-6">Daftar kelas yang akan ditambahkan</div>
                        <button type="button" class="btn btn-sm btn-light-success fw-bold tambah-baris-kelas">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah Baris
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-3">
                            <tbody id="tbody-kelas">
                                <tr>
                                    <td class="ps-0">
                                        <input type="text" name="nama_kelas[]" placeholder="Contoh: X RPL 1" required class="form-control form-control-solid">
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

<div class="modal fade" id="edit_kelas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-500px">
        <div class="modal-content rounded border-0">
            <form action="<?= base_url('sw-admin/kelas/update'); ?>" method="POST" class="form">
                <?= csrf_field() ?>
                <input type="hidden" name="id_kelas" id="id_kelas_edit">
                
                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h2 class="fw-bold">Edit Nama Kelas</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                
                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">
                    <div class="fv-row mb-5">
                        <label class="required fs-6 fw-semibold mb-2">Nama Kelas</label>
                        <input type="text" name="nama_kelas" id="nama_kelas_edit" class="form-control form-control-solid" required>
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
        // 1. DataTables Init (Disesuaikan dengan DOM Metronic 8 agar rapi)
        var table = $('#datatables-list').DataTable({
            "ordering": false,
            "lengthChange": false,
            "pageLength": 10,
            "drawCallback": function(settings) {
                // Re-init Metronic menu dropdown pada saat pindah halaman paginasi
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            }
        });

        // Fitur Pencarian Custom yang menyatu dengan UI Card Header Metronic
        $('[data-kt-kelas-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        // --- 2. MULTIPLE INSERT (Tambah Baris) ---
        // Disempurnakan dengan form-control-solid dan tombol icon Metronic
        $('.tambah-baris-kelas').click(function() {
            let html = `<tr>
            <td class="ps-0"><input type="text" name="nama_kelas[]" placeholder="Contoh: X RPL 2" required class="form-control form-control-solid"></td>
            <td class="text-end pe-0 w-50px">
                <button type="button" class="btn btn-icon btn-light-danger btn-sm hapus-baris">
                    <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                </button>
            </td>
        </tr>`;
            $('#tbody-kelas').append(html);
        });

        $(document).on('click', '.hapus-baris', function() {
            $(this).closest('tr').remove();
        });

        // --- 3. AJAX EDIT (TIDAK DIUBAH SAMA SEKALI) ---
        $('.edit-kelas').click(function(e) {
            e.preventDefault();
            const id_enc = $(this).data('id');
            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-admin/kelas/edit') ?>",
                data: {
                    id_kelas: id_enc,
                    "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
                },
                dataType: 'JSON',
                success: function(response) {
                    // Set data ke modal
                    $('#id_kelas_edit').val(response.kelas.id_kelas);
                    $('#nama_kelas_edit').val(response.kelas.nama_kelas);

                    // Update CSRF Hash di form (agar tidak error saat submit)
                    $('input[name="<?= csrf_token() ?>"]').val(response.token_baru);

                    // Menggunakan standar Bootstrap 5
                    $('#edit_kelas').modal('show');
                },
            });
        });
    });
</script>
<?= $this->endSection(); ?>
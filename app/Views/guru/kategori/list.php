<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        
        <div class="card card-flush shadow-sm">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <h3 class="card-label fw-bold text-dark">Manajemen Kategori</h3>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= base_url('sw-guru/bank-soal'); ?>" class="btn btn-light-primary btn-sm">
                            <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
                            Kembali
                        </a>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambah_kategori">
                            <i class="ki-duotone ki-plus fs-2"></i>
                            Tambah Kategori
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body py-4">
                <div class="table-responsive">
                    <table id="datatable-table" class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-100px">ID</th>
                                <th class="min-w-150px">Nama Kategori</th>
                                <th class="text-end min-w-100px">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            <?php foreach ($kategori as $u) : ?>
                                <tr>
                                    <td><span class="badge badge-light-dark fw-bold"><?= $u->id_kategori; ?></span></td>
                                    <td><span class="text-gray-800 fw-bold"><?= $u->nama_kategori; ?></span></td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Aksi
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                        </a>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <a href="javascript:void(0);" class="menu-link px-3 btn_edit_kategori" 
                                                   data-kategori="<?= encrypt_url($u->id_kategori); ?>" 
                                                   data-bs-toggle="modal" 
                                                   data-bs-target="#edit_kategori">Edit</a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a href="javascript:void(0)" data-url="<?= base_url('sw-guru/kategori/delete') . '/' . encrypt_url($u->id_kategori); ?>" 
                                                   class="menu-link px-3 btn-hapus text-danger btn-delete">Hapus</a>
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

<div class="modal fade" id="tambah_kategori" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-450px">
        <form action="<?= base_url('sw-guru/kategori/store'); ?>" method="POST" class="w-100">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Tambah Kategori Baru</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body px-10 py-10">
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control form-control-solid" placeholder="Masukan nama kategori" required />
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="edit_kategori" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-450px">
        <form action="<?= base_url('sw-guru/kategori/update'); ?>" method="POST" class="w-100">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Ubah Kategori</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body px-10 py-10">
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Nama Kategori</label>
                        <input type="hidden" name="id_kategori" id="id_kategori">
                        <input type="text" name="nama_kategori" id="nama_kategori" class="form-control form-control-solid" required />
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section("scripts"); ?>
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable standar Metronic
        $('#datatable-table').DataTable({
            "ordering": true,
            "pageLength": 10,
            "lengthChange": false,
            "info": true,
            "dom": "<'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>"
        });

        // AJAX EDIT LOGIC (Tetap sesuai fungsi asli Anda)
        $(document).on('click', '.btn_edit_kategori', function() {
            const id_kategori = $(this).data('kategori');
            
            let postData = { id_kategori: id_kategori };
            postData[csrfName] = csrfHash;

            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-guru/kategori/edit') ?>",
                data: postData,
                dataType: 'JSON',
                success: function(data) {
                    if (data.csrf_hash) {
                        csrfHash = data.csrf_hash;
                        $("input[name='" + csrfName + "']").val(csrfHash);
                    }

                    if (data.kategori) {
                        $("#id_kategori").val(data.kategori.id_kategori);
                        $("#nama_kategori").val(data.kategori.nama_kategori);
                    }
                },
                error: function(xhr) {
                    alert("Sesi verifikasi habis, silakan refresh halaman.");
                    location.reload();
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>
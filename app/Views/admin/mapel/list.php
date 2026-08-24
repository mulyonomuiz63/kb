<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-mapel-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Mapel..." />
                        </div>
                    </div>
                    <div class="card-toolbar">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambah_mapel">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah Mapel
                        </button>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatables" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-50px text-center">No</th>
                                    <th class="min-w-200px">Nama Mapel</th>
                                    <th class="min-w-150px">Preview</th>
                                    <th class="min-w-100px text-center">Opsi</th>
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
<div class="modal fade" id="tambah_mapel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded">
            
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h2 class="fw-bold">Tambah Mapel Baru</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <form action="<?= base_url('sw-admin/mapel/store'); ?>" method="POST" enctype="multipart/form-data" class="form">
                <?= csrf_field(); ?>
                
                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-15">
                    <div class="d-flex flex-stack mb-5">
                        <div class="fs-6 fw-semibold text-gray-700">Daftar Mata Pelajaran</div>
                        <button type="button" class="btn btn-sm btn-light-primary tambah-baris-mapel">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah Baris
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-3">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Nama Mapel</th>
                                    <th class="min-w-250px">File Gambar</th>
                                    <th class="w-50px text-end"></th>
                                </tr>
                            </thead>
                            <tbody id="tbody-mapel">
                                <tr>
                                    <td>
                                        <input type="text" name="nama_mapel[]" required class="form-control form-control-solid" placeholder="Nama Mapel">
                                    </td>
                                    <td>
                                        <input type="file" name="gambar_mapel[]" required class="form-control form-control-solid" accept="image/*">
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-icon btn-light-danger btn-sm disabled" disabled>
                                            <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-muted fs-7 mt-2"><span class="text-danger">*</span> Rekomendasi ukuran gambar: 1280px x 1024px</div>

                    <div class="text-center pt-10">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Simpan Semua</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_mapel_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content rounded">
            
            <div class="modal-header pb-0 border-0 justify-content-between">
                <h2 class="fw-bold">Edit Mata Pelajaran</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <form action="<?= base_url('sw-admin/mapel/update'); ?>" method="POST" enctype="multipart/form-data" class="form">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_mapel" id="id_mapel">
                <input type="hidden" name="gambar_mapel_lama" id="gambar_mapel_lama">
                
                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-15">
                    
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Nama Mata Pelajaran</label>
                        <input type="text" name="nama_mapel" id="nama_mapel" class="form-control form-control-solid" required>
                    </div>
                    
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Ganti Gambar <span class="text-muted fs-7">(Opsional)</span></label>
                        <input type="file" name="gambar_mapel" class="form-control form-control-solid" accept="image/*">
                    </div>
                    
                    <div class="border border-dashed border-gray-300 rounded p-5 bg-light text-center mt-5">
                        <label class="d-block text-gray-600 fw-bold mb-3 fs-6">Gambar Saat Ini:</label>
                        <div id="preview_gambar_lama"></div>
                    </div>

                    <div class="text-center pt-10">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Update Data</span>
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        
        // Server-side DataTable (Metronic Styled)
        var table = $('#datatables').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= base_url('sw-admin/mapel/datatables') ?>",
                "type": "POST",
                "data": function(d) {
                    d.<?= csrf_token() ?> = $('input[name="<?= csrf_token() ?>"]').val();
                },
                "dataSrc": function(json) {
                    if (json.token) {
                        $('input[name="<?= csrf_token() ?>"]').val(json.token);
                    }
                    return json.data;
                }
            },
            "columnDefs": [
                { "targets": [0, 2, 3], "orderable": false },
                { "targets": [0, 3], "className": "text-center" }
            ],
            "drawCallback": function(settings) {
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            }
        });

        // Search Datatables
        $('[data-kt-mapel-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Tambah Baris
        $('.tambah-baris-mapel').click(function() {
            let baris = `<tr>
                <td><input type="text" name="nama_mapel[]" required class="form-control form-control-solid" placeholder="Nama Mapel"></td>
                <td><input type="file" name="gambar_mapel[]" required class="form-control form-control-solid" accept="image/*"></td>
                <td class="text-end">
                    <button type="button" class="btn btn-icon btn-light-danger btn-sm btn-remove-row">
                        <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    </button>
                </td>
            </tr>`;
            $('#tbody-mapel').append(baris);
        });

        // Hapus Baris
        $(document).on('click', '.btn-remove-row', function() {
            $(this).closest('tr').remove();
        });

        // Edit AJAX (Perbaikan Utama)
        $(document).on('click', '.edit-mapel', function(e) {
            e.preventDefault(); // Mencegah reload / submit event default

            const id = $(this).data('id');
            const csrfName = '<?= csrf_token() ?>';
            const csrfHash = $('input[name="' + csrfName + '"]').val();

            if (!id) {
                console.error('ID Mapel tidak ditemukan pada tombol edit!');
                return;
            }

            $.ajax({
                type: "POST",
                url: "<?= base_url('sw-admin/mapel/edit') ?>",
                data: {
                    id_mapel: id,
                    [csrfName]: csrfHash
                },
                dataType: "JSON",
                success: function(res) {
                    // 1. Update Token CSRF di seluruh input form
                    if (res.token) {
                        $('input[name="' + csrfName + '"]').val(res.token);
                    }

                    // 2. Tampilkan data ke input modal jika respon valid
                    if (res.mapel) {
                        $('#id_mapel').val(res.mapel.id_mapel);
                        $('#nama_mapel').val(res.mapel.nama_mapel);
                        $('#gambar_mapel_lama').val(res.mapel.file);

                        if (res.mapel.file) {
                            let url_preview = "<?= base_url('uploads/mapel') ?>/" + res.mapel.file;
                            $('#preview_gambar_lama').html(`<img src="${url_preview}" class="img-fluid rounded shadow-sm" style="max-height: 150px; object-fit: contain;">`);
                        } else {
                            $('#preview_gambar_lama').html('<span class="text-muted fs-7">Tidak ada gambar</span>');
                        }

                        // 3. Trigger Modal menggunakan Bootstrap 5 Instance (Standar Metronic 8)
                        const modalElement = document.getElementById('edit_mapel_modal');
                        const modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);
                        modalInstance.show();
                    } else {
                        alert('Data mapel tidak ditemukan!');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error AJAX:", xhr.responseText);
                    alert("Gagal mengambil data. Pastikan controller mengembalikan JSON dan token CSRF sesuai.");
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>
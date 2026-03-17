<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<style>
    .zoom:hover {
        transform: scale(1.5);
        transition: transform .2s;
        z-index: 999;
    }

    .img-table {
        width: 80px;
        border-radius: 4px;
        cursor: pointer;
    }
</style>

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">
            <div class="widget shadow p-3 bg-white rounded">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="widget-heading d-flex justify-content-between align-items-center">
                            <h4 class="font-weight-bold">Daftar Galeri</h4>
                            <a href="javascript:void(0)" class="btn btn-primary mt-3" id="addBtn">Tambah Galeri</a>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table id="datatable-list" class="table table-hover text-left" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th width="40%">Judul</th>
                                        <th>Gambar</th>
                                        <th width="20%">Tgl Pelatihan</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tambah_galeri" tabindex="-1" role="dialog" aria-labelledby="tambah_galeriLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form action="<?= base_url('sw-admin/galeri/store'); ?>" method="POST" enctype="multipart/form-data" id="tambah_galeriForm">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambah_galeriLabel">Tambah Galeri</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>File Gambar</label>
                        <input type="file" name="file" class="form-control-file" accept="image/*" required>
                        <small class="text-danger font-italic">Max ukuran file 2MB</small>
                    </div>
                    <div class="form-group">
                        <label>Judul</label>
                        <input type="text" name="judul" class="form-control" placeholder="Judul Galeri" required>
                    </div>
                    <div class="form-group">
                        <label>Tgl Pelatihan</label>
                        <input type="date" name="tgl_pelatihan" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="edit_galeri" tabindex="-1" role="dialog" aria-labelledby="edit_galeriLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('sw-admin/galeri/update'); ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_galeriLabel">Edit Galeri</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="e_idgaleri" name="idgaleri">
                    <input type="hidden" name="file_lama" id="e_file_lama">

                    <div class="form-group">
                        <label>Ganti File (Kosongkan jika tidak diubah)</label>
                        <input type="file" name="file" class="form-control-file" accept="image/*">
                        <small class="text-danger font-italic">Max ukuran file 2MB</small>
                    </div>
                    <div class="form-group">
                        <label>Judul</label>
                        <input type="text" id="e_judul" name="judul" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Tgl Pelatihan</label>
                        <input type="date" id="e_tgl_pelatihan" name="tgl_pelatihan" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Data</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="view_galeri" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-0 text-center tampil-file">
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script>
    // Fungsi pembantu untuk update semua input CSRF di halaman
    function updateAllCSRF(newToken) {
        csrfHash = newToken;
        $('input[name="' + csrfName + '"]').val(newToken);
    }

    $(document).ready(function() {
        // 1. Inisialisasi DataTable Server Side
        var table = $('#datatable-list').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= base_url('sw-admin/galeri/datatables') ?>",
                type: "POST",
                data: function(d) {
                    d[csrfName] = csrfHash; // Kirim token terbaru
                },
                dataSrc: function(json) {
                    // Update token global dan semua input hidden saat tabel reload
                    updateAllCSRF(json.token);
                    return json.data;
                },
            },
            columns: [{
                    data: 'no'
                },
                {
                    data: 'judul'
                },
                {
                    data: 'file'
                },
                {
                    data: 'tgl_pelatihan'
                },
                {
                    data: 'opsi'
                }
            ],
            columnDefs: [{
                    targets: [0, 2, 4],
                    orderable: false
                },
                {
                    targets: [0],
                    width: "5%"
                }
            ],
        });

        // 2. Tombol Tambah (Reset Form & Sync Token)
        $('#addBtn').click(function() {
            $('#tambah_galeriForm')[0].reset();
            $('input[name="' + csrfName + '"]').val(csrfHash); // Pastikan input hidden form pakai token terbaru
            $('#tambah_galeri').modal('show');
        });

        // 3. Fungsi Edit (AJAX)
        $(document).on('click', '.edit-galeri', function() {
            const id = $(this).data('id');
            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-admin/galeri/edit') ?>",
                data: {
                    [csrfName]: csrfHash,
                    idgaleri: id
                },
                dataType: 'JSON',
                success: function(data) {
                    // Update token global dari response
                    if (data.token) {
                        updateAllCSRF(data.token);
                    }

                    // Isi data ke input (bekerja baik untuk JSON object)
                    $("#e_idgaleri").val(data.idgaleri);
                    $("#e_judul").val(data.judul);
                    $("#e_file_lama").val(data.file);
                    $("#e_tgl_pelatihan").val(data.tgl_pelatihan);
                    $('#edit_galeri').modal('show');
                },
                error: function(xhr) {
                    // Jika error, tetap update CSRF agar tidak 403 saat mencoba lagi
                    let res = xhr.responseJSON;
                    if (res && res.token) updateAllCSRF(res.token);
                    swal("Error", "Gagal memuat data", "error");
                }
            });
        });


        // 5. Fungsi View Gambar
        $(document).on('click', '.view-galeri', function() {
            var url = '<?= base_url('uploads/galeri/thumbnails/'); ?>';
            var file = $(this).data('galeri');
            $(".tampil-file").html('<img src="' + url + '/' + file + '" class="img-fluid w-100" alt="View">');
            $('#view_galeri').modal('show');
        });
    });
</script>
<?= $this->endSection(); ?>
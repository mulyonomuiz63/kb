<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">
            <div class="widget shadow p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="font-weight-bold"><i class="bi bi-book mr-2"></i>Mata Pelajaran</h5>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#tambah_mapel">
                        <i class="bi bi-plus-circle mr-1"></i> Tambah Mapel
                    </button>
                </div>

                <div class="table-responsive">
                    <table id="datatables" class="table table-hover table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Mapel</th>
                                <th width="120">Preview</th>
                                <th class="text-center" width="80">Opsi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tambah_mapel" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <form action="<?= base_url('sw-admin/mapel/store'); ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white">Tambah Mapel Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-outline-success btn-sm mb-3 tambah-baris-mapel">
                        <i class="bi bi-plus"></i> Tambah Baris
                    </button>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Mapel</th>
                                <th width="40%">File Gambar</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody id="tbody-mapel">
                            <tr>
                                <td><input type="text" name="nama_mapel[]" required class="form-control" placeholder="Nama Mapel"></td>
                                <td><input type="file" name="gambar_mapel[]" required class="form-control-file border p-1 rounded w-100" accept="image/*"></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <small class="text-muted italic">* Rekomendasi ukuran: 1280px x 1024px</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Semua</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="edit_mapel_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('sw-admin/mapel/update'); ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field(); ?>
            <input type="hidden" name="id_mapel" id="id_mapel">
            <input type="hidden" name="gambar_mapel_lama" id="gambar_mapel_lama">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title text-white">Edit Mata Pelajaran</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Mata Pelajaran</label>
                        <input type="text" name="nama_mapel" id="nama_mapel" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Ganti Gambar (Opsional)</label>
                        <input type="file" name="gambar_mapel" class="form-control-file border p-1 rounded w-100" accept="image/*">
                    </div>
                    <div class="text-center p-2 border rounded bg-light">
                        <label class="d-block text-muted mb-2">Gambar Saat Ini:</label>
                        <div id="preview_gambar_lama"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info text-white">Update Data</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Server-side DataTable
        var table = $('#datatables').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= base_url('sw-admin/mapel/datatables') ?>",
                "type": "POST",
                "data": function(d) {
                    // Ambil hash terbaru dari input setiap kali datatables melakukan request
                    d.<?= csrf_token() ?> = $('input[name="<?= csrf_token() ?>"]').val();
                },
                "dataSrc": function(json) {
                    // Update token di seluruh halaman dengan token baru yang dikirim server
                    // Pastikan Controller datatables juga mengirimkan 'token' => csrf_hash()
                    if (json.token) {
                        $('input[name="<?= csrf_token() ?>"]').val(json.token);
                    }
                    return json.data;
                }
            },
            "columnDefs": [{
                    "targets": [0, 2, 3],
                    "orderable": false
                },
                {
                    "targets": [3],
                    "className": "text-center"
                }
            ],
        });

        // Tambah Baris
        $('.tambah-baris-mapel').click(function() {
            let baris = `<tr>
            <td><input type="text" name="nama_mapel[]" required class="form-control"></td>
            <td><input type="file" name="gambar_mapel[]" required class="form-control-file border p-1 rounded w-100"></td>
            <td class="text-center"><button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="bi bi-x-lg"></i></button></td>
        </tr>`;
            $('#tbody-mapel').append(baris);
        });

        $(document).on('click', '.btn-remove-row', function() {
            $(this).closest('tr').remove();
        });

        $(document).on('click', '.edit-mapel', function() {
            const id = $(this).data('id');

            // 1. Ambil Nama & Hash CSRF terbaru dari input hidden yang ada di halaman
            const csrfName = '<?= csrf_token() ?>';
            const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

            $.ajax({
                type: "POST",
                url: "<?= base_url('sw-admin/mapel/edit') ?>",
                data: {
                    id_mapel: id,
                    [csrfName]: csrfHash // Mengirim hash terbaru (Kunci A)
                },
                dataType: "JSON",
                success: function(res) {
                    // 2. Isi data ke dalam field modal
                    $('#id_mapel').val(res.mapel.id_mapel);
                    $('#nama_mapel').val(res.mapel.nama_mapel);
                    $('#gambar_mapel_lama').val(res.mapel.file);

                    let url_preview = "<?= base_url('uploads/mapel') ?>/" + res.mapel.file;
                    $('#preview_gambar_lama').html(`<img src="${url_preview}" class="img-fluid rounded border shadow-sm" style="max-height: 150px;">`);

                    // 3. UPDATE TOKEN CSRF DI HALAMAN (Paling Penting!)
                    // Kita pasang Kunci B (res.token) ke input hidden untuk request selanjutnya
                    if (res.token) {
                        $('input[name="<?= csrf_token() ?>"]').val(res.token);
                    }

                    $('#edit_mapel_modal').modal('show');
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>
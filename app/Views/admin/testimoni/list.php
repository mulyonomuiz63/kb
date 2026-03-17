<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">
            <div class="widget shadow p-3 bg-white rounded">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="widget-heading d-flex justify-content-between align-items-center">
                            <h5 class="font-weight-bold">Daftar Testimoni</h5>
                            <a href="javascript:void(0)" class="btn btn-primary mt-3" id="addBtnTestimoni">Tambah Testimoni</a>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table id="datatable-testimoni" class="table table-hover text-left" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Peserta</th>
                                        <th>Keterangan</th>
                                        <th class="text-right">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tambah_testimoni" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form action="<?= base_url('sw-admin/testimoni/store'); ?>" method="POST" id="formTambahTestimoni">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Testimoni</h5>
                    <button type="button" class="close" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Peserta</label>
                        <select name="idsiswa" class="form-control select2" required>
                            <option value="" selected disabled>-- Pilih Peserta --</option>
                            <?php foreach ($siswa as $rows): ?>
                                <option value="<?= $rows->id_siswa ?>"><?= $rows->nama_siswa ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="4" required></textarea>
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

<div class="modal fade" id="edit_testimoni" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form action="<?= base_url('sw-admin/testimoni/update'); ?>" method="POST" id="formEditTestimoni">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Testimoni</h5>
                    <button type="button" class="close" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="idtestimoni" id="e_idtestimoni">
                    <div class="form-group">
                        <label>Peserta</label>
                        <select name="idsiswa" id="e_idsiswa" class="form-control select2" required>
                            <?php foreach ($siswa as $rows): ?>
                                <option value="<?= $rows->id_siswa ?>"><?= $rows->nama_siswa ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" id="e_keterangan_val" class="form-control" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script>
    function updateAllCSRF(newToken) {
        csrfHash = newToken;
        $('input[name="' + csrfName + '"]').val(newToken);
    }

    $(document).ready(function() {
        // 1. DataTables Server Side
        var table = $('#datatable-testimoni').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= base_url('sw-admin/testimoni/datatables') ?>",
                type: "POST",
                data: function(d) {
                    d[csrfName] = csrfHash;
                },
                dataSrc: function(json) {
                    updateAllCSRF(json.token);
                    return json.data;
                },
            },
            columns: [{
                    data: 'no'
                },
                {
                    data: 'nama_siswa'
                },
                {
                    data: 'keterangan'
                },
                {
                    data: 'opsi',
                    className: 'text-right'
                }
            ],
            columnDefs: [{
                targets: [0, 3],
                orderable: false
            }]
        });

        // 2. Open Modal Tambah
        $('#addBtnTestimoni').click(function() {
            $('#formTambahTestimoni')[0].reset();
            updateAllCSRF(csrfHash);
            $('#tambah_testimoni').modal('show');
        });

        // 3. Edit (AJAX)
        $(document).on('click', '.edit-testimoni', function() {
            const id = $(this).data('id');
            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-admin/testimoni/edit') ?>",
                data: {
                    [csrfName]: csrfHash,
                    idtestimoni: id
                },
                dataType: 'JSON',
                success: function(data) {
                    if (data.token) updateAllCSRF(data.token);

                    $("#e_idtestimoni").val(data.idtestimoni);
                    
                    // Update value Select2 dan paksa refresh tampilan (.trigger)
                    $("#e_idsiswa").val(data.idsiswa).trigger('change');
                    
                    $("#e_keterangan_val").val(data.keterangan);
                    $('#edit_testimoni').modal('show');
                },
                error: function(xhr) {
                    // Penanganan error agar CSRF tidak rusak
                    if(xhr.responseJSON && xhr.responseJSON.token) updateAllCSRF(xhr.responseJSON.token);
                    alert("Gagal mengambil data");
                }
            });
        });

        // Fungsi Toggle Keterangan (Read More / Read Less)
        $(document).on('click', '.btn-read-more', function(e) {
            e.preventDefault();
            var container = $(this).closest('div');
            var shortSpan = container.find('.txt-short');
            var fullSpan = container.find('.txt-full');

            if (fullSpan.hasClass('d-none')) {
                // Tampilkan Full
                fullSpan.removeClass('d-none');
                shortSpan.addClass('d-none');
                $(this).text('Sembunyikan');
            } else {
                // Tampilkan Ringkas
                fullSpan.addClass('d-none');
                shortSpan.removeClass('d-none');
                $(this).text('Lihat Selengkapnya');
            }
        });
    });
</script>
<?= $this->endSection(); ?>
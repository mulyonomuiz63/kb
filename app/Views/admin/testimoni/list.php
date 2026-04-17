<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card card-flush shadow-sm border-0">

                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <button type="button" class="btn btn-sm btn-primary fw-bold" id="addBtnTestimoni">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah Testimoni
                        </button>
                    </div>

                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-testimoni-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Testimoni..." />
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatable-testimoni" class="table align-middle table-row-dashed fs-6 gy-5 w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 border-bottom border-gray-200">
                                    <th class="w-50px text-center">No</th>
                                    <th class="min-w-200px">Peserta</th>
                                    <th class="min-w-300px">Keterangan</th>
                                    <th class="text-end min-w-100px">Opsi</th>
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
<div class="modal fade" id="tambah_testimoni" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content rounded border-0">
            <form action="<?= base_url('sw-admin/testimoni/store'); ?>" method="POST" id="formTambahTestimoni" class="form">
                <?= csrf_field() ?>

                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h2 class="fw-bold">Tambah Testimoni</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>

                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Nama Peserta</label>
                        <select name="idsiswa" class="form-select form-select-solid" data-control="select2" data-dropdown-parent="#tambah_testimoni" data-placeholder="-- Pilih Peserta --" required>
                            <option value=""></option>
                            <?php foreach ($siswa as $rows): ?>
                                <option value="<?= $rows->id_siswa ?>"><?= $rows->nama_siswa ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Keterangan</label>
                        <textarea name="keterangan" class="form-control form-control-solid" rows="5" placeholder="Tuliskan testimoni atau ulasan peserta..." required></textarea>
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

<div class="modal fade" id="edit_testimoni" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content rounded border-0">
            <form action="<?= base_url('sw-admin/testimoni/update'); ?>" method="POST" id="formEditTestimoni" class="form">
                <?= csrf_field() ?>
                <input type="hidden" name="idtestimoni" id="e_idtestimoni">

                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h2 class="fw-bold">Edit Testimoni</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>

                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Peserta</label>
                        <select name="idsiswa" id="e_idsiswa" class="form-select form-select-solid" data-control="select2" data-dropdown-parent="#edit_testimoni" data-placeholder="-- Pilih Peserta --" required>
                            <option value=""></option>
                            <?php foreach ($siswa as $rows): ?>
                                <option value="<?= $rows->id_siswa ?>"><?= $rows->nama_siswa ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Keterangan</label>
                        <textarea name="keterangan" id="e_keterangan_val" class="form-control form-control-solid" rows="5" required></textarea>
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
    function updateAllCSRF(newToken) {
        csrfHash = newToken;
        $('input[name="' + csrfName + '"]').val(newToken);
    }

    $(document).ready(function() {

        $('[data-control="select2"]').select2();

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
                    data: 'no',
                    className: 'text-center text-gray-800 fw-bold'
                },
                {
                    data: 'nama_siswa'
                },
                {
                    data: 'keterangan'
                },
                {
                    data: 'opsi',
                    className: 'text-end'
                }
            ],
            columnDefs: [{
                targets: [0, 3],
                orderable: false
            }],
            drawCallback: function(settings) {
                // Beri tahu Metronic untuk membaca ulang DOM dan mengaktifkan dropdown baru
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            }
        });

        // Fitur Pencarian Custom yang menyatu dengan UI Card Header Metronic
        $('[data-kt-testimoni-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        // 2. Open Modal Tambah
        $('#addBtnTestimoni').click(function() {
            $('#formTambahTestimoni')[0].reset();
            // Reset Select2 ui
            $('#formTambahTestimoni select').val('').trigger('change');

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
                    if (xhr.responseJSON && xhr.responseJSON.token) updateAllCSRF(xhr.responseJSON.token);
                    alert("Gagal mengambil data");
                }
            });
        });

        // Fungsi Toggle Keterangan (Read More / Read Less)
        // Dibiarkan 100% sama sesuai logika aslinya
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
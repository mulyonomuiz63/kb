<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="card card-flush shadow-sm border-0">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title flex-column align-items-start">
                        <span class="card-label fw-bold text-gray-800">Daftar Sesi Webinar</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Kelola data sesi webinar, waktu, harga, dan tautan bonus</span>
                    </div>
                    
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-webinar-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Sesi Webinar..." />
                        </div>

                        <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#tambah_webinar">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah Sesi Webinar
                        </button>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatables-list" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Nama Sesi</th>
                                    <th class="min-w-150px">Waktu</th>
                                    <th class="min-w-100px">Harga</th>
                                    <th class="min-w-100px">Status</th>
                                    <th class="min-w-150px text-wrap">Sesi Bonus / Gratis</th>
                                    <th class="min-w-150px">Link Zoom</th>
                                    <th class="min-w-150px">Link YouTube</th>
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

<!-- Modal Tambah Sesi Webinar -->
<div class="modal fade" id="tambah_webinar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded border-0">
            <form action="<?= base_url('sw-admin/webinar/store'); ?>" method="POST" class="needs-validation">
                <input type="hidden" name="<?= csrf_token() ?>" class="csrf-token" value="<?= csrf_hash() ?>" />
                
                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h2 class="fw-bold">Tambah Sesi Webinar Baru</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                
                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Nama Sesi</label>
                        <input type="text" name="nama_sesi" class="form-control form-control-solid" placeholder="Contoh: Sesi 1 - Pengantar Pajak" required>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Deskripsi Sesi</label>
                        <textarea name="deskripsi_sesi" class="form-control form-control-solid" rows="3" placeholder="Deskripsi singkat sesi..."></textarea>
                    </div>
                    <div class="row g-3 mb-7">
                        <div class="col-md-6">
                            <label class="required fs-6 fw-semibold mb-2">Waktu Mulai</label>
                            <input type="datetime-local" name="waktu_mulai" class="form-control form-control-solid" required>
                        </div>
                        <div class="col-md-6">
                            <label class="required fs-6 fw-semibold mb-2">Waktu Selesai</label>
                            <input type="datetime-local" name="waktu_selesai" class="form-control form-control-solid" required>
                        </div>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Harga Sesi (Rp)</label>
                        <input type="number" step="0.01" name="harga_sesi" class="form-control form-control-solid" placeholder="0" required>
                    </div>

                    <!-- Input Sesi Gratis / Bonus Terkait -->
                    <div class="fv-row mb-7" id="sesi_gratis">
                        <label class="fs-6 fw-semibold mb-2">Pilih Sesi Bonus / Gratis Terkait</label>
                        <select name="sesi_gratis[]" class="form-select form-select-solid" data-control="select2" data-dropdown-parent="#sesi_gratis" data-placeholder="Pilih sesi bonus..." multiple="multiple">
                            <?php foreach ($allSesi as $s): ?>
                                <option value="<?= $s['id_sesi'] ?>"><?= esc($s['nama_sesi']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="text-muted fs-7 mt-1">Sesi yang dipilih di sini akan otomatis terbuka jika user membeli sesi utama ini.</div>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Link Zoom (Bisa banyak, pisahkan baris baru / enter)</label>
                        <textarea name="link_zoom" class="form-control form-control-solid" rows="2" placeholder="https://zoom.us/j/...&#10;https://zoom.us/j/..."></textarea>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Link YouTube (Bisa banyak, pisahkan baris baru / enter)</label>
                        <textarea name="link_youtube" class="form-control form-control-solid" rows="2" placeholder="https://youtube.com/watch?v=...&#10;https://youtube.com/watch?v=..."></textarea>
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

<!-- Modal Edit Sesi Webinar -->
<div class="modal fade" id="edit_webinar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded border-0">
            <form action="<?= base_url('sw-admin/webinar/update'); ?>" method="POST">
                <input type="hidden" name="<?= csrf_token() ?>" class="csrf-token" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="id_sesi" id="e_id_sesi">
                
                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h2 class="fw-bold">Ubah Data Sesi Webinar</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                
                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Nama Sesi</label>
                        <input type="text" name="nama_sesi" id="e_nama_sesi" class="form-control form-control-solid" required>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Deskripsi Sesi</label>
                        <textarea name="deskripsi_sesi" id="e_deskripsi_sesi" class="form-control form-control-solid" rows="3"></textarea>
                    </div>
                    <div class="row g-3 mb-7">
                        <div class="col-md-6">
                            <label class="required fs-6 fw-semibold mb-2">Waktu Mulai</label>
                            <input type="datetime-local" name="waktu_mulai" id="e_waktu_mulai" class="form-control form-control-solid" required>
                        </div>
                        <div class="col-md-6">
                            <label class="required fs-6 fw-semibold mb-2">Waktu Selesai</label>
                            <input type="datetime-local" name="waktu_selesai" id="e_waktu_selesai" class="form-control form-control-solid" required>
                        </div>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Harga Sesi (Rp)</label>
                        <input type="number" step="0.01" name="harga_sesi" id="e_harga_sesi" class="form-control form-control-solid" required>
                    </div>

                    <!-- Input Sesi Gratis / Bonus Terkait Edit -->
                    <div class="fv-row mb-7" id="sesi_gratis_edit">
                        <label class="fs-6 fw-semibold mb-2">Pilih Sesi Bonus / Gratis Terkait</label>
                        <select name="sesi_gratis[]" id="e_sesi_gratis" class="form-select form-select-solid" data-control="select2" data-dropdown-parent="#sesi_gratis_edit" data-placeholder="Pilih sesi bonus..." multiple="multiple">
                            <?php foreach ($allSesi as $s): ?>
                                <option value="<?= $s['id_sesi'] ?>"><?= esc($s['nama_sesi']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Link Zoom (Pisahkan baris baru jika lebih dari satu)</label>
                        <textarea name="link_zoom" id="e_link_zoom" class="form-control form-control-solid" rows="2"></textarea>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Link YouTube (Pisahkan baris baru jika lebih dari satu)</label>
                        <textarea name="link_youtube" id="e_link_youtube" class="form-control form-control-solid" rows="2"></textarea>
                    </div>
                </div>
                
                <div class="modal-footer border-0 p-5 p-lg-10 pt-0">
                    <button type="button" class="btn btn-light me-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info fw-bold text-white">
                        <span class="indicator-label"><i class="ki-duotone ki-check fs-3 me-1"></i> Update Webinar Sesi</span>
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
        // Inisialisasi Select2 Metronic
        $('[data-control="select2"]').select2();

        // 1. Inisialisasi DataTables Server Side
        var table = $('#datatables-list').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= base_url('sw-admin/webinar/datatables') ?>",
                "type": "POST",
                "data": function(d) {
                    d.<?= csrf_token() ?> = $('.csrf-token').val();
                },
                "dataSrc": function(json) {
                    $('.csrf-token').val(json.<?= csrf_token() ?>);
                    return json.data;
                }
            },
            "columns": [
                { "data": "nama_sesi" },
                { "data": "waktu" },
                { "data": "harga_sesi" },
                { "data": "status" },
                { "data": "sesi_gratis" },
                { "data": "link_zoom" },
                { "data": "link_youtube" },
                { "data": "opsi" }
            ],
            "columnDefs": [{
                "targets": [7],
                "orderable": false,
                "className": "text-end"
            }],
            "drawCallback": function(settings) {
                if (typeof KTMenu !== 'undefined') { KTMenu.createInstances(); }
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });

        // Fitur Pencarian Custom
        $('[data-kt-webinar-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        // 2. Fungsi Load Data ke Modal Edit (AJAX)
        $(document).on('click', '.edit-webinar', function() {
            const id_sesi = $(this).data('id');
            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-admin/webinar/edit') ?>",
                data: {
                    id_sesi: id_sesi,
                    <?= csrf_token() ?>: $('.csrf-token').val()
                },
                dataType: 'JSON',
                success: function(data) {
                    $('.csrf-token').val(data.token);

                    let waktuMulai = data.waktu_mulai ? data.waktu_mulai.replace(' ', 'T') : '';
                    let waktuSelesai = data.waktu_selesai ? data.waktu_selesai.replace(' ', 'T') : '';

                    $("#e_id_sesi").val(data.id_sesi);
                    $("#e_nama_sesi").val(data.nama_sesi);
                    $("#e_deskripsi_sesi").val(data.deskripsi_sesi);
                    $("#e_waktu_mulai").val(waktuMulai);
                    $("#e_waktu_selesai").val(waktuSelesai);
                    $("#e_harga_sesi").val(data.harga_sesi);
                    $("#e_link_zoom").val(data.link_zoom_text);
                    $("#e_link_youtube").val(data.link_youtube_text);
                    
                    // Set value untuk select2 multi-select sesi gratis
                    $("#e_sesi_gratis").val(data.sesi_gratis_array).trigger('change');
                    
                    $('#edit_webinar').modal('show');
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>
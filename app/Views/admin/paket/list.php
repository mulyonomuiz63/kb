<?= $this->extend('template/app'); ?>
<?= $this->section('styles'); ?>
<style>
    /* Custom CSS untuk halaman create artikel */
    .select2-container--bootstrap5 .select2-selection--single {
        min-height: calc(1.5em + 1.65rem + 2px) !important;
        padding: 0.825rem 1.5rem !important;
    }
    .select2-container--bootstrap5 .select2-selection__rendered {
        line-height: 1.5 !important;
    }
</style>
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>
<?php $db = Config\Database::connect(); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card card-flush shadow-sm border-0">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title flex-column align-items-start">
                        <div class="d-flex align-items-center gap-2 gap-lg-3">
                            <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#tambah_paket">
                                <i class="ki-duotone ki-plus fs-2"></i> Tambah Paket
                            </button>
                        </div>
                    </div>
                    <div class="card-toolbar">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-paket-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Paket..." />
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatable-list" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Nama Paket</th>
                                    <th class="text-center min-w-100px">Jenis</th>
                                    <th class="text-center min-w-100px">Diskon</th>
                                    <th class="text-end min-w-150px">Nominal</th>
                                    <th class="text-center min-w-100px">Status</th>
                                    <th class="text-center min-w-100px">Komisi</th>
                                    <th class="text-center min-w-150px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-paket" class="fw-semibold text-gray-600">
                                <?php foreach ($paket as $s) : ?>
                                    <tr data-id="<?= $s->idpaket ?>" style="cursor: grab;">
                                        <td class="text-gray-800 fw-bold text-wrap pl-3">
                                            <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-element-11 fs-4 text-muted me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                                <?= $s->nama_paket; ?>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-muted"><?= $s->jenis_paket; ?></span>
                                        </td>
                                        <td class="text-center fw-bold text-danger">
                                            <?= $s->diskon; ?>%
                                        </td>
                                        <td class="text-end fw-bold text-gray-900">
                                            Rp <?= number_format($s->nominal_paket, 0, '.', '.'); ?>
                                        </td>
                                        <td class="text-center">
                                            <?= $s->status == 1
                                                ? '<span class="badge badge-light-success fw-bold px-3 py-2">Aktif</span>'
                                                : '<span class="badge badge-light-danger fw-bold px-3 py-2">Non-Aktif</span>'; ?>
                                        </td>
                                        <td class="text-center text-primary fw-bold">
                                            <?= $s->komisi ?>%
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <?php if ($s->v_ujian == 1 && $s->v_materi == 0): ?>
                                                    <a href="<?= base_url("sw-admin/paket/review/" . $s->slug) ?>" class="btn btn-icon btn-light-info btn-sm" data-bs-toggle="tooltip" title="Review">
                                                        <i class="ki-duotone ki-eye fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                    </a>
                                                <?php endif; ?>

                                                <button type="button" class="btn btn-icon btn-sm <?= $s->is_pinned ? 'btn-warning' : 'btn-light-dark' ?> pin-paket" data-id="<?= $s->idpaket ?>" data-bs-toggle="tooltip" title="<?= $s->is_pinned ? 'Lepas Pin' : 'Pin ke Atas' ?>">
                                                    <i class="ki-duotone ki-pin fs-3"><span class="path1"></span><span class="path2"></span></i>
                                                </button>

                                                <button type="button" class="btn btn-icon btn-light-primary btn-sm edit-paket" data-bs-toggle="modal" data-bs-target="#edit_paket" data-paket="<?= encrypt_url($s->idpaket); ?>" data-bs-toggle="tooltip" title="Edit Data">
                                                    <i class="ki-duotone ki-pencil fs-3"><span class="path1"></span><span class="path2"></span></i>
                                                </button>
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
<div class="modal fade" id="tambah_paket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <div class="modal-content rounded border-0">
            <form action="<?= base_url('sw-admin/paket/store'); ?>" method="POST" enctype="multipart/form-data" class="form">
                <?= csrf_field() ?>
                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h2 class="fw-bold">Tambah Paket Baru</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>

                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">
                    <div class="row g-5">
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Kelas</label>
                            <select name="id_kelas" id="id_kelas" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Kelas" required>
                                <option value="">Pilih</option>
                                <?php $kelas = $db->query("select * from kelas")->getResultObject(); ?>
                                <?php foreach ($kelas as $rows) : ?>
                                    <option value="<?= $rows->id_kelas; ?>"><?= $rows->nama_kelas; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="fs-6 fw-semibold mb-2">Ujian <span class="text-danger fs-8 fw-normal">(Pilih semua untuk semua ujian)</span></label>
                            <select name="id_ujian" id="ujian_master" class="form-select form-select-solid" data-control="select2">
                                <option value="">Pilih Kelas Dahulu</option>
                            </select>
                        </div>
                        <div class="col-md-12 fv-row">
                            <label class="fs-6 fw-semibold mb-2">Mapel <span class="text-danger fs-8 fw-normal">(Pilih semua untuk semua materi)</span></label>
                            <select name="id_mapel" id="id_mapel" class="form-select form-select-solid" data-control="select2">
                                <option value="">Pilih Kelas Dahulu</option>
                            </select>
                        </div>
                    </div>

                    <div class="separator separator-dashed my-7"></div>

                    <div class="row g-5">
                        <div class="col-md-12 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Gambar Paket</label>
                            <input type="file" name="avatar" class="form-control form-control-solid" required>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Nama Paket</label>
                            <input type="text" name="nama_paket" class="form-control form-control-solid" required>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Jenis Paket</label>
                            <input type="text" name="jenis_paket" class="form-control form-control-solid" required>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Nominal Paket (Rp)</label>
                            <input type="number" name="nominal_paket" class="form-control form-control-solid" required>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Diskon</label>
                            <select name="iddiskon" class="form-select form-select-solid" required>
                                <option value="">Pilih</option>
                                <?php $dataDiskon = $db->query("select * from diskon ")->getResultObject(); ?>
                                <?php foreach ($dataDiskon as $rows) : ?>
                                    <option value="<?= $rows->iddiskon; ?>"><?= $rows->diskon; ?>%</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Status Paket</label>
                            <select name="status" class="form-select form-select-solid" required>
                                <option value="">Pilih</option>
                                <option value="1">Tampil / Aktif</option>
                                <option value="0">Tidak Tampil / Non-Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="fs-6 fw-semibold mb-2">Komisi Affiliate (%)</label>
                            <input type="number" name="komisi" class="form-control form-control-solid" value="0">
                        </div>
                    </div>
                    <input type="hidden" name="jumlah_bulan" value="12">

                    <div class="fv-row mt-7">
                        <label class="required fs-6 fw-semibold mb-2">Deskripsi Paket</label>
                        <textarea name="deskripsi" class="summernote form-control" required></textarea>
                    </div>
                </div>

                <div class="modal-footer border-0 p-5 p-lg-10 pt-0 justify-content-end">
                    <button type="button" class="btn btn-light me-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_paket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <div class="modal-content rounded border-0 shadow-lg">
            <form action="<?= base_url('sw-admin/paket/update'); ?>" method="POST" enctype="multipart/form-data" class="form">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h2 class="fw-bold">Edit Paket</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>

                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">
                    <div class="fv-row mb-7 text-center" id="view_gambar"></div>

                    <div class="row g-5">
                        <div class="col-md-12 fv-row">
                            <label class="fs-6 fw-semibold mb-2">Ganti Gambar <span class="text-muted fw-normal fs-8">(Kosongkan jika tidak diganti)</span></label>
                            <input type="file" name="avatar" class="form-control form-control-solid">
                            <input type="hidden" name="gambar_lama" id="gambar_lama">
                            <input type="hidden" name="idpaket" id="idpaket">
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Nama Paket</label>
                            <input type="text" name="nama_paket" id="nama_paket" class="form-control form-control-solid" required>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Jenis Paket</label>
                            <input type="text" name="jenis_paket" id="jenis_paket" class="form-control form-control-solid" required>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Nominal (Rp)</label>
                            <input type="number" name="nominal_paket" id="nominal_paket" class="form-control form-control-solid" required>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Diskon</label>
                            <select name="iddiskon" id="iddiskon" class="form-select form-select-solid" required>
                                <option value="">Pilih</option>
                                <?php foreach ($dataDiskon as $rows) : ?>
                                    <option value="<?= $rows->iddiskon; ?>"><?= $rows->diskon; ?>%</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Status</label>
                            <select name="status" id="status" class="form-select form-select-solid" required>
                                <option value="">Pilih</option>
                                <option value="1">Aktif</option>
                                <option value="0">Non-Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="fs-6 fw-semibold mb-2">Komisi Affiliate (%)</label>
                            <input type="number" name="komisi" id="komisi" class="form-control form-control-solid" required>
                        </div>
                    </div>
                    <input type="hidden" name="jumlah_bulan" id="jumlah_bulan">

                    <div class="fv-row mt-7">
                        <label class="required fs-6 fw-semibold mb-2">Deskripsi Paket</label>
                        <div id="deskripsi"></div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-5 p-lg-10 pt-0 justify-content-end">
                    <button type="button" class="btn btn-light me-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    // Fungsi Global untuk update CSRF Token
    function updateCSRF(token) {
        $('input[name="<?= csrf_token() ?>"]').val(token);
    }

    $(document).ready(function() {
        // Init DataTables
        var table = $('#datatable-list').DataTable({
            "ordering": false,
        });

        // Search Control
        $('[data-kt-paket-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Summernote Init
        $('.summernote').summernote({
            placeholder: 'Tulis konten disini..',
            height: 300,
            fontSizes: ['8', '9', '10', '11', '12', '14', '18', '24', '36', '48', '64', '82'],
        });

        // AJAX Edit Paket
        $(document).on('click', '.edit-paket', function() {
            const idpaket = $(this).data('paket');
            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-admin/paket/edit') ?>",
                data: {
                    idpaket: idpaket,
                    "<?= csrf_token() ?>": $('input[name="<?= csrf_token() ?>"]').val()
                },
                dataType: 'JSON',
                success: function(data) {
                    updateCSRF(data.<?= csrf_token() ?>);

                    $("#idpaket").val(data.idpaket);
                    $("#nama_paket").val(data.nama_paket);
                    $("#jenis_paket").val(data.jenis_paket);
                    $("#iddiskon").val(data.iddiskon);
                    $("#nominal_paket").val(data.nominal_paket);
                    $("#jumlah_bulan").val(data.jumlah_bulan);
                    $("#gambar_lama").val(data.file);
                    $("#status").val(data.status);
                    $("#komisi").val(data.komisi);
                    $("#deskripsi").html(`<textarea name="deskripsi" cols="30" rows="2" class="summernote_edit">${data.deskripsi}</textarea>`);
                    $("#view_gambar").html("<img class='img-fluid rounded shadow-sm w-150px' src='<?= base_url('assets-landing/images/paket/thumbnails'); ?>/" + data.file + "' alt='Thumbnail'>");

                    $('.summernote_edit').summernote({
                        height: 300,
                        fontSizes: ['8', '9', '10', '11', '12', '14', '18', '24', '36', '48', '64', '82'],
                    });
                }
            });
        });

        // AJAX Dynamic Select
        $('#id_kelas').change(function() {
            var id = $(this).val();
            var csrfName = '<?= csrf_token() ?>';
            var csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

            $.ajax({
                url: "<?php echo site_url('sw-admin/paket/ujian-master'); ?>",
                method: "POST",
                data: {
                    id: id,
                    [csrfName]: csrfHash
                },
                dataType: 'json',
                success: function(res) {
                    updateCSRF(res[csrfName]);
                    var html = '<option value="">Pilih</option><option value="all">Pilih Semua</option>';
                    $.each(res.data, function(i, item) {
                        html += '<option value="' + item.id_ujian + '">' + item.nama_ujian + '</option>';
                    });
                    $('#ujian_master').html(html);
                }
            });

            $.ajax({
                url: "<?php echo site_url('sw-admin/paket/get-mapel'); ?>",
                method: "POST",
                data: {
                    id: id,
                    [csrfName]: $('input[name="<?= csrf_token() ?>"]').val()
                },
                dataType: 'json',
                success: function(res) {
                    updateCSRF(res[csrfName]);
                    var html = '<option value="">Pilih</option><option value="all">Pilih Semua</option>';
                    $.each(res.data, function(i, item) {
                        html += '<option value="' + item.id_mapel + '">' + item.nama_mapel + '</option>';
                    });
                    $('#id_mapel').html(html);
                }
            });
        });
    });

    // Drag and Drop Reorder
    document.addEventListener('DOMContentLoaded', function() {
        let tbody = document.getElementById('sortable-paket');
        if (tbody) {
            Sortable.create(tbody, {
                animation: 150,
                ghostClass: 'bg-light-primary',
                onEnd: function() {
                    let order = [];
                    tbody.querySelectorAll('tr').forEach((row, index) => {
                        order.push({
                            id: row.dataset.id,
                            position: index + 1
                        });
                    });

                    fetch("<?= base_url('sw-admin/paket/reorder') ?>", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-Requested-With": "XMLHttpRequest",
                                "X-CSRF-TOKEN": $('input[name="<?= csrf_token() ?>"]').val()
                            },
                            body: JSON.stringify(order)
                        })
                        .then(res => res.json())
                        .then(res => {
                            updateCSRF(res.<?= csrf_token() ?>);
                            if (res.status !== 'success') Swal.fire("Gagal!", "Urutan gagal disimpan", "error");
                        });
                }
            });
        }
    });

    // PIN Function
    $(document).on('click', '.pin-paket', function() {
        let id = $(this).data('id');
        $.ajax({
            url: "<?= base_url('sw-admin/paket/pin') ?>",
            type: "POST",
            data: {
                id: id,
                "<?= csrf_token() ?>": $('input[name="<?= csrf_token() ?>"]').val()
            },
            dataType: "json",
            success: function(res) {
                if (res.status === 'success') {
                    location.reload();
                } else {
                    updateCSRF(res.<?= csrf_token() ?>);
                    Swal.fire("Gagal!", res.message, "error");
                }
            }
        });
    });
</script>
<?= $this->endSection(); ?>
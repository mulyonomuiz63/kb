<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?php $db = Config\Database::connect(); ?>
<style>
    /* Styling Tambahan untuk Kesan Premium */
    .badge-subtle-success {
        background-color: #e8fadf;
        color: #71dd37;
        border-radius: 6px;
    }

    .badge-subtle-danger {
        background-color: #ffe5e5;
        color: #ff3e1d;
        border-radius: 6px;
    }

    .font-weight-600 {
        font-weight: 600;
    }

    .table thead th {
        font-size: 11px;
        letter-spacing: 0.8px;
        color: #888;
    }

    .table tbody tr:hover {
        background-color: #fbfbfb;
        transition: 0.3s;
    }

    .btn-sm {
        padding: 0.4rem 0.6rem;
        border-radius: 8px;
    }

    .gap-2 {
        gap: 0.5rem;
    }
</style>
<!--  BEGIN CONTENT AREA  -->
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">

            <div class="widget shadow-sm bg-white rounded-lg border-0" style="border-top: 4px solid #4361ee;">
                <div class="widget-content p-3">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="font-weight-bold mb-0 text-dark">Manajemen Paket</h5>
                            <p class="text-muted small">Kelola daftar paket, urutan tampilan, dan status afiliasi.</p>
                        </div>
                        <button class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#tambah_paket">
                            <i class="bi bi-plus-circle mr-1"></i> Tambah Paket
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable-list" class="table table-hover mb-0">
                            <thead class="bg-light text-uppercase">
                                <tr>
                                    <th class="border-0 px-3 py-3" style="width: 30%;">Nama Paket</th>
                                    <th class="border-0 py-3 text-center">Jenis</th>
                                    <th class="border-0 py-3 text-center">Diskon</th>
                                    <th class="border-0 py-3 text-right">Nominal</th>
                                    <th class="border-0 py-3 text-center">Status</th>
                                    <th class="border-0 py-3 text-center">Komisi</th>
                                    <th class="border-0 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-paket">
                                <?php foreach ($paket as $s) : ?>
                                    <tr data-id="<?= $s->idpaket ?>" class="align-middle" style="cursor: move;">
                                        <td class="text-wrap font-weight-600 text-dark pl-3">
                                            <?= $s->nama_paket; ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-muted"><?= $s->jenis_paket; ?></span>
                                        </td>
                                        <td class="text-center font-weight-bold text-danger">
                                            <?= $s->diskon; ?>%
                                        </td>
                                        <td class="text-right font-weight-bold text-dark">
                                            Rp <?= number_format($s->nominal_paket, 0, '.', '.'); ?>
                                        </td>
                                        <td class="text-center">
                                            <?= $s->status == 1
                                                ? '<span class="badge badge-subtle-success p-2 px-3">Aktif</span>'
                                                : '<span class="badge badge-subtle-danger p-2 px-3">Non-Aktif</span>'; ?>
                                        </td>
                                        <td class="text-center text-primary font-weight-bold">
                                            <?= $s->komisi ?>%
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <?php if ($s->v_ujian == 1 && $s->v_materi == 0): ?>
                                                    <a href="<?= base_url("sw-admin/paket/review/" . $s->slug) ?>"
                                                        class="btn btn-sm btn-outline-info mr-1" title="Review">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <button type="button"
                                                    class="btn btn-sm <?= $s->is_pinned ? 'btn-warning' : 'btn-outline-secondary' ?> pin-paket mr-1"
                                                    data-id="<?= $s->idpaket ?>" title="Pin ke Atas">
                                                    <i class="bi <?= $s->is_pinned ? 'bi-pin-fill' : 'bi-pin' ?>"></i>
                                                </button>

                                                <button type="button"
                                                    data-toggle="modal"
                                                    data-target="#edit_paket"
                                                    data-paket="<?= encrypt_url($s->idpaket); ?>"
                                                    class="btn btn-sm btn-primary edit-paket" title="Edit Data">
                                                    <i class="bi bi-pencil-square"></i>
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


<!--  END CONTENT AREA  -->

<!-- MODAL -->
<!-- Modal Tambah -->
<div class="modal fade" id="tambah_paket" tabindex="-1" role="dialog" aria-labelledby="tambah_paketLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form action="<?= base_url('sw-admin/paket/store'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambah_paketLabel">Tambah Paket</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Kelas</label>
                            <select name="id_kelas" id="id_kelas" class="form-control" required>
                                <option value="">Pilih</option>
                                <?php
                                $kelas = $db->query("select * from kelas")->getResultObject();

                                ?>
                                <?php foreach ($kelas as $rows) : ?>
                                    <option value="<?= $rows->id_kelas; ?>"><?= $rows->nama_kelas; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Ujian <small class="text-danger">(pilih semua, untuk membuat paket semua ujian)</small></label>
                            <select name="id_ujian" id="ujian_master" class="form-control">
                                <option value="">Pilih</option>

                            </select>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="">Mapel <small class="text-danger">(pilih semua, untuk membuat paket semua materi)</small></label>
                            <select name="id_mapel" id="id_mapel" class="form-control">
                                <option value="">Pilih</option>
                            </select>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="">Gambar Paket</label>
                            <input type="file" name="avatar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">Nama Paket</label>
                            <input type="text" name="nama_paket" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">Jenis Paket</label>
                            <input type="text" name="jenis_paket" class="form-control" required>
                        </div>
                        <!--<div class="form-group">-->
                        <!--    <label for="">Jumlah Bulan</label>-->
                        <input type="hidden" name="jumlah_bulan" value="12" class="form-control">
                        <!--</div>-->
                        <div class="form-group">
                            <label for="">Nominal Paket</label>
                            <input type="number" name="nominal_paket" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">Diskon</label>
                            <select name="iddiskon" class="form-control" required>
                                <option value="">Pilih</option>
                                <?php
                                $dataDiskon = $db->query("select * from diskon ")->getResultObject();

                                ?>
                                <?php foreach ($dataDiskon as $rows) : ?>
                                    <option value="<?= $rows->iddiskon; ?>"><?= $rows->diskon; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Status Paket</label>
                            <select name="status" class="form-control" required>
                                <option value="">Pilih</option>
                                <option value="1">Tampil</option>
                                <option value="0">Tidak Tampil</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Komisi Affiliate</label>
                            <input type="number" name="komisi" class="form-control" value="0">
                        </div>
                        <div class="form-group">
                            <label for="">Deskripsi Paket</label>
                            <textarea name="deskripsi" cols="30" rows="2" class="summernote" wrap="hard" required></textarea>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" value="reset" class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal edit -->
<div class="modal fade" id="edit_paket" tabindex="-1" role="dialog" aria-labelledby="edit_paketLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('sw-admin/paket/update'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_paketLabel">Edit Paket</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group" id="view_gambar">

                    </div>
                    <div class="form-group">
                        <label for="">Gambar Paket</label>
                        <input type="file" name="avatar" class="form-control">
                        <input type="hidden" name="gambar_lama" id="gambar_lama" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Nama Paket</label>
                        <input type="hidden" name="idpaket" id="idpaket" class="form-control">
                        <input type="text" name="nama_paket" id="nama_paket" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Jenis Paket</label>
                        <input type="text" name="jenis_paket" id="jenis_paket" class="form-control">
                    </div>
                    <!--<div class="form-group">-->
                    <!--    <label for="">Jumlah Bulan</label>-->
                    <input type="hidden" name="jumlah_bulan" id="jumlah_bulan" class="form-control">
                    <!--</div>-->
                    <div class="form-group">
                        <label for="">Nominal Paket</label>
                        <input type="number" name="nominal_paket" id="nominal_paket" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Diskon</label>
                        <select name="iddiskon" id="iddiskon" class="form-control" required>
                            <option value="">Pilih</option>
                            <?php
                            $dataDiskon = $db->query("select * from diskon")->getResultObject();

                            ?>
                            <?php foreach ($dataDiskon as $rows) : ?>
                                <option value="<?= $rows->iddiskon; ?>"><?= $rows->diskon; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Status Paket</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="">Pilih</option>
                            <option value="1">Tampil</option>
                            <option value="0">Tidak Tampil</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Komisi Affiliate</label>
                        <input type="number" name="komisi" id="komisi" class="form-control" value="0">
                    </div>
                    <div class="form-group">
                        <label for="">Deskripsi Paket</label>
                        <div id="deskripsi"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" value="reset" class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script>
    $('#datatable-list').DataTable({
        "ordering": false
    });
    // Fungsi Global untuk update CSRF Token di semua input/form
    function updateCSRF(token) {
        $('input[name="<?= csrf_token() ?>"]').val(token);
    }

    $(document).ready(function() {
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
                    updateCSRF(data.<?= csrf_token() ?>); // Update CSRF

                    $("#idpaket").val(data.idpaket);
                    $("#nama_paket").val(data.nama_paket);
                    $("#jenis_paket").val(data.jenis_paket);
                    $("#iddiskon").val(data.iddiskon);
                    $("#nominal_paket").val(data.nominal_paket);
                    $("#jumlah_bulan").val(data.jumlah_bulan);
                    $("#gambar_lama").val(data.file);
                    $("#status").val(data.status);
                    $("#komisi").val(data.komisi);
                    $("#deskripsi").html(`<textarea name="deskripsi" cols="30" rows="2" class="summernote">${data.deskripsi}</textarea>`);
                    $("#view_gambar").html("<img class='card-img-top' src='<?= base_url('assets-landing/images/paket/thumbnails'); ?>/" + data.file + "' alt='Courses'>");
                    $('.summernote').summernote({
                        placeholder: 'Tulis konten disini..',
                        height: 300,
                        fontSizes: ['8', '9', '10', '11', '12', '14', '18', '24', '36', '48', '64', '82'],
                    });
                }
            });
        });

        // AJAX Get Ujian & Mapel berdasarkan Kelas
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
                    updateCSRF(res[csrfName]); // Update CSRF
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
                    updateCSRF(res[csrfName]); // Update CSRF
                    var html = '<option value="">Pilih</option><option value="all">Pilih Semua</option>';
                    $.each(res.data, function(i, item) {
                        html += '<option value="' + item.id_mapel + '">' + item.nama_mapel + '</option>';
                    });
                    $('#id_mapel').html(html);
                }
            });
        });

        // Summernote Init
        $('.summernote').summernote({
            placeholder: 'Tulis konten disini..',
            height: 300,
            fontSizes: ['8', '9', '10', '11', '12', '14', '18', '24', '36', '48', '64', '82'],
        });
    });

    // Drag and Drop Reorder
    document.addEventListener('DOMContentLoaded', function() {
        let tbody = document.getElementById('sortable-paket');
        Sortable.create(tbody, {
            animation: 150,
            ghostClass: 'bg-light',
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
                            "X-CSRF-TOKEN": $('input[name="<?= csrf_token() ?>"]').val() // Kirim via Header
                        },
                        body: JSON.stringify(order)
                    })
                    .then(res => res.json())
                    .then(res => {
                        updateCSRF(res.<?= csrf_token() ?>);
                        if (res.status !== 'success') swal("Gagal!", "Urutan gagal disimpan", "error");
                    });
            }
        });
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
                    swal("Gagal!", res.message, "error");
                }
            }
        });
    });
</script>
<?= $this->endSection(); ?>
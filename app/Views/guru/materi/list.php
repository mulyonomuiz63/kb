<?= $this->extend('template/app'); ?>

<?= $this->section('styles') ?>
<style>
    .link-group {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .link-group .form-control {
        flex: 1;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content'); ?>
<?php $db = Config\Database::connect(); ?>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <div class="card card-flush shadow-sm mb-5">
            <div class="card-body py-5">
                <?php
                $dkelas = $db->query("select nama_kelas from kelas where id_kelas = '$id_kelas'")->getRow();
                $dmapel = $db->query("select nama_mapel from mapel where id_mapel = '$id_mapel'")->getRow();
                $namamapel = $dmapel->nama_mapel;
                $kelas = $dkelas->nama_kelas;
                ?>
                <div class="d-flex align-items-center mb-2">
                    <span class="badge badge-light-primary fw-bold me-2">MAPEL:</span>
                    <h4 class="mb-0 fw-bolder text-dark"><?= $namamapel ?></h4>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge badge-light-success fw-bold me-2">KELAS:</span>
                    <h4 class="mb-0 fw-bolder text-dark"><?= $kelas ?></h4>
                </div>
            </div>
        </div>

        <div class="card card-flush shadow-sm">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" data-kt-materi-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Cari Bank Soal..." />
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= base_url('guru/mapel') ?>" class="btn btn-light-primary btn-sm">
                            <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i> Kembali
                        </a>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambah_materi">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah Materi
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body py-4">
                <div class="table-responsive">
                    <table id="datatable-list" class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-150px">Judul</th>
                                <th class="min-w-100px">File Lampiran</th>
                                <th class="min-w-100px">Status</th>
                                <th class="text-end min-w-100px">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            <?php foreach ($materi as $m) : ?>
                                <tr>
                                    <td><span class="text-gray-800 fw-bold"><?= $m->nama_materi; ?></span></td>
                                    <?php $jml_file = $db->query("select count(*) as total_file from file where kode_file = '$m->kode_materi'")->getRow(); ?>
                                    <td>
                                        <span class="badge badge-light-dark fw-bold">
                                            <i class="ki-duotone ki-file fs-7 me-1"><span class="path1"></span><span class="path2"></span></i>
                                            (<?= !empty($jml_file) ? $jml_file->total_file : 0; ?>) File
                                        </span>
                                    </td>
                                    <td>
                                        <?= $m->status == 0 ? '<span class="badge badge-light-warning">Coming Soon</span>' : '<span class="badge badge-light-success">Ready</span>'; ?>
                                    </td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Aksi <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                        </a>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <a href="<?= base_url('sw-guru/materi/lihat-materi/') . encrypt_url($m->id_materi) . '/' . $idmapel  . '/' . $idkelas; ?>" class="menu-link px-3">Lihat</a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a href="javascript:void(0);" class="menu-link px-3 edit_materi" data-materi="<?= encrypt_url($m->id_materi); ?>" data-bs-toggle="modal" data-bs-target="#edit_materi">Edit</a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a href="<?= base_url('guru/hapus_materi/') . '/' . encrypt_url($m->kode_materi) . '/' . encrypt_url($m->id_mapel) . '/' . encrypt_url($m->id_kelas); ?>" class="menu-link px-3 btn-hapus text-danger">Delete</a>
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

<div class="modal fade" id="tambah_materi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="<?= base_url('sw-guru/materi/store'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Tambah Materi</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <input type="hidden" name="kode_materi" value="<?= random_string('alnum', 8); ?>">
                    <div class="row g-9 mb-7">
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Nama Materi</label>
                            <input type="text" name="nama_materi" class="form-control form-control-solid" required />
                            <input type="hidden" name="mapel" value="<?= $id_mapel ?>">
                            <input type="hidden" name="kelas" value="<?= $id_kelas ?>">
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Status Materi</label>
                            <select class="form-select form-select-solid" name="status" required>
                                <option value="">Pilih</option>
                                <option value="0">Coming Soon</option>
                                <option value="1">Ready</option>
                            </select>
                        </div>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Url Video</label>
                        <div id="link_video">
                            <div class="link-group my-2">
                                <input type="text" class="form-control form-control-solid" name="text_materi[]" placeholder="Masukkan URL video">
                            </div>
                        </div>
                        <button type="button" class="btn btn-light-success btn-sm mt-3 tambah-baris-link">
                            <i class="ki-duotone ki-plus fs-3"></i> Tambah URL Video
                        </button>
                    </div>

                    <div class="fv-row">
                        <label class="fs-6 fw-semibold mb-2">Upload File (Max 10MB)</label>
                        <input type="file" class="form-control form-control-solid" name="file_materi[]" multiple accept=".jpg, .jpeg, .png, .pdf">
                        <div class="text-muted fs-7 mt-2">Format: JPG, PNG, PDF. Bisa pilih banyak file sekaligus.</div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="edit_materi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="<?= base_url('sw-guru/materi/update'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Edit Materi</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <input type="hidden" name="e_kode_materi">
                    <div class="row g-9 mb-7">
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Nama Materi</label>
                            <input type="text" name="e_nama_materi" class="form-control form-control-solid" required />
                            <input type="hidden" name="e_mapel" id="e_mapel">
                            <input type="hidden" name="e_kelas" id="e_kelas">
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Status Materi</label>
                            <select class="form-select form-select-solid" name="e_status" id="e_status" required>
                                <option value="">Pilih</option>
                                <option value="0">Coming Soon</option>
                                <option value="1">Ready</option>
                            </select>
                        </div>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Url Video</label>
                        <div id="e_link_video"></div>
                        <button type="button" class="btn btn-light-success btn-sm mt-3" id="tambah-link-edit">
                            <i class="ki-duotone ki-plus fs-3"></i> Tambah URL Video
                        </button>
                    </div>

                    <div class="fv-row">
                        <label class="fs-6 fw-semibold mb-2">Ganti/Tambah File</label>
                        <input type="file" class="form-control form-control-solid" name="e_file_materi[]" multiple accept=".jpg, .jpeg, .png, .pdf">
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    function updateCsrfToken(newToken) {
        if (newToken && newToken !== csrfHash) {
            csrfHash = newToken;
            $('input[name="' + csrfName + '"]').val(newToken);
            $('meta[name="csrf-hash"]').attr('content', newToken);
        }
    }

    $(document).ready(function() {
        if ($.fn.DataTable) {
            var table = $('#datatable-list').DataTable({
                "ordering": false,
                "lengthChange": false,
                "pageLength": 10,
                "info": false,
                "dom": "rtp", // Menyembunyikan search bawaan agar tidak double
                "drawCallback": function(settings) {
                    // Re-init KTMenu agar dropdown di dalam baris tabel tetap berfungsi
                    if (typeof KTMenu !== 'undefined') {
                        KTMenu.createInstances();
                    }
                }
            });
        }

        // 2. Pindahkan event listener ke dalam ready() agar variabel 'table' terbaca
        $('[data-kt-materi-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        $(document).on('click', '.edit_materi', function() {
            const id_materi = $(this).data('materi');
            const container = $('#e_link_video');
            container.html('<p class="text-muted">Loading...</p>');

            let postData = {
                id_materi: id_materi
            };
            postData[csrfName] = csrfHash;

            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-guru/materi/edit') ?>",
                data: postData,
                dataType: 'JSON',
                success: function(data) {
                    updateCsrfToken(data.token);
                    $("input[name=e_kode_materi]").val(data.kode_materi);
                    $("input[name=e_nama_materi]").val(data.nama_materi);
                    $("#e_mapel").val(data.mapel);
                    $("#e_kelas").val(data.kelas);
                    $("#e_status").val(data.status);

                    container.empty();
                    let link_video = [];
                    try {
                        link_video = JSON.parse(data.text_materi || '[]');
                    } catch (e) {
                        link_video = [];
                    }

                    if (link_video.length === 0) {
                        addVideoRow('#e_link_video', 'e_text_materi[]');
                    } else {
                        link_video.forEach((url) => {
                            addVideoRow('#e_link_video', 'e_text_materi[]', url);
                        });
                    }
                },
            });
        });

        function addVideoRow(containerId, inputName, value = '') {
            const row = `
                <div class="link-group my-2">
                    <input type="text" class="form-control form-control-solid" name="${inputName}" value="${value}" placeholder="Masukkan URL video">
                    <button type="button" class="remove-link btn btn-icon btn-light-danger btn-sm">
                        <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    </button>
                </div>`;
            $(containerId).append(row);
        }

        $('.tambah-baris-link').click(function() {
            addVideoRow('#link_video', 'text_materi[]');
        });

        $('#tambah-link-edit').click(function() {
            addVideoRow('#e_link_video', 'e_text_materi[]');
        });

        $(document).on('click', '.remove-link', function() {
            $(this).closest('.link-group').remove();
        });
    });
</script>
<?= $this->endSection(); ?>
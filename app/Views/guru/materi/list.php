<?= $this->extend('template/app'); ?>
<?= $this->section('css') ?>
<style>
    .link-group {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .link-group .form-control {
        flex: 1;
    }

    .link-group .remove-link {
        padding: 6px 10px;
        font-size: 18px;
        line-height: 1;
    }
</style>

<?= $this->endSection() ?>
<?= $this->section('content'); ?>
<?php $db = Config\Database::connect(); ?>
<!--  BEGIN CONTENT AREA  -->
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">
            <div class="widget shadow p-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="widget-heading">
                            <?php
                            $dkelas = $db->query("select nama_kelas from kelas where id_kelas = '$id_kelas'")->getRow();
                            $dmapel = $db->query("select nama_mapel from mapel where id_mapel = '$id_mapel'")->getRow();
                            $namamapel = $dmapel->nama_mapel;
                            $kelas = $dkelas->nama_kelas;

                            ?>
                            <div class="row">
                                <div class="col-1">
                                    <h5 class="">Mapel</h5>
                                </div>
                                <div class="col-11">
                                    <h5 class=""><?= $namamapel ?></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-1">
                                    <h5 class="">Kelas</h5>
                                </div>
                                <div class="col-11">
                                    <h5 class=""><?= $kelas ?></h5>
                                </div>
                            </div>
                            <a href="<?= base_url('guru/mapel') ?>" class="btn btn-info mt-3">Kembali</a><a href="javascript:void(0)" class="btn btn-primary mt-3" data-toggle="modal" data-target="#tambah_materi">Tambah Materi</a>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable-list" class="table text-left text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>File Lampiran</th>
                                        <th>Status</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($materi as $m) : ?>
                                        <tr>
                                            <td><?= $m->nama_materi; ?></td>
                                            <?php $jml_file = $db->query("select count(*) as total_file from file where kode_file = '$m->kode_materi'")->getRow(); ?>
                                            <td>
                                                (<?= !empty($jml_file) ? $jml_file->total_file : 0; ?>) File
                                            </td>
                                            <td>
                                                <?= $m->status == 0 ? '<span class="btn btn-warning btn-sm">Coming Soon</span>' : '<span class="btn btn-success btn-sm">Ready</span>'; ?>
                                            </td>
                                            <td>
                                                <div class="dropdown custom-dropdown">
                                                    <a class="dropdown-toggle btn btn-primary" href="#" role="button" id="dropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                                            <line x1="3" y1="12" x2="21" y2="12"></line>
                                                            <line x1="3" y1="6" x2="21" y2="6"></line>
                                                            <line x1="3" y1="18" x2="21" y2="18"></line>
                                                        </svg>
                                                    </a>

                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink2">
                                                        <a class="dropdown-item" href="<?= base_url('sw-guru/materi/lihat-materi/') . encrypt_url($m->id_materi) . '/' . $idmapel  . '/' . $idkelas; ?>">Lihat</a>
                                                        <a class="dropdown-item edit_materi" href="javascript:void(0);" data-materi="<?= encrypt_url($m->id_materi); ?>" data-toggle="modal" data-target="#edit_materi">Edit</a>
                                                        <a class="dropdown-item btn-hapus" href="<?= base_url('guru/hapus_materi/') . '/' . encrypt_url($m->kode_materi) . '/' . encrypt_url($m->id_mapel) . '/' . encrypt_url($m->id_kelas); ?>">Delete</a>
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
    </div>
</div>
<!--  END CONTENT AREA  -->

<!-- MODAL -->
<!-- Modal Tambah -->
<div class="modal fade" id="tambah_materi" tabindex="-1" role="dialog" aria-labelledby="tambah_materiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="<?= base_url('sw-guru/materi/store'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambah_materiLabel">Tambah materi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="kode_materi" value="<?= random_string('alnum', 8); ?>" class="form-control" required>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Nama Materi</label>
                                <input type="text" name="nama_materi" class="form-control" required>
                                <input type="hidden" name="mapel" value="<?= $id_mapel ?>">
                                <input type="hidden" name="kelas" value="<?= $id_kelas ?>">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Status materi</label>
                                <select class="form-control" name="status" required>
                                    <option value="">Pilih</option>
                                    <option value="0">Coming Soon</option>
                                    <option value="1">ready</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <a href="javascript:void(0)" class="btn btn-success mb-3 tambah-baris-link">tambah url video</a>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="">Url Video</label>
                                <input type="text" class="form-control" name="text_materi[]">
                                <div id="link_video"> </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="custom-file-container" data-upload-id="fileMateri">
                                <label>Upload File <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                                <label class="custom-file-container__custom-file file_materi">
                                    <input type="file" class="custom-file-container__custom-file__custom-file-input" name="file_materi[]" multiple accept=".jpg, .jpeg, .png, .pdf">
                                    <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                                    <span class="custom-file-container__custom-file__custom-file-control"></span>
                                </label>
                                <div class="custom-file-container__image-preview"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" value="reset" class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal edit -->
<div class="modal fade" id="edit_materi" tabindex="-1" role="dialog" aria-labelledby="edit_materiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="<?= base_url('sw-guru/materi/update'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_materiLabel">Edit materi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="e_kode_materi" class="form-control" required>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Nama Materi</label>
                                <input type="text" name="e_nama_materi" class="form-control" required>
                                <input type="hidden" name="e_mapel" id="e_mapel" value="">
                                <input type="hidden" name="e_kelas" id="e_kelas" value="">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Status materi</label>
                                <select class="form-control" name="e_status" id="e_status" required>
                                    <option value="">Pilih</option>
                                    <option value="0">Coming Soon</option>
                                    <option value="1">ready</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="">Url Video</label>
                                <div id="e_link_video"></div>
                                <a href="javascript:void(0)" class="btn btn-success mt-2" id="tambah-link-edit">Tambah URL Video</a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="custom-file-container" data-upload-id="e_fileMateri">
                                <label>Upload File <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                                <label class="custom-file-container__custom-file file_materi">
                                    <input type="file" class="custom-file-container__custom-file__custom-file-input" name="e_file_materi[]" multiple accept=".jpg, .jpeg, .png, .pdf">
                                    <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                                    <span class="custom-file-container__custom-file__custom-file-control"></span>
                                </label>
                                <div class="custom-file-container__image-preview"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" value="reset" class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
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
        // 1. Inisialisasi DataTable
        if ($.fn.DataTable) {
            $('#datatable-list').DataTable({
                "ordering": false
            });
        }

        // 2. Inisialisasi Summernote
        $(document).on('click', '.edit_materi', function() {
            const id_materi = $(this).data('materi');
            const container = $('#e_link_video');

            container.html('<p class="text-muted">Loading...</p>');

            // Siapkan data yang akan dikirim
            let postData = {
                id_materi: id_materi
            };
            // Sisipkan token CSRF ke dalam objek data
            postData[csrfName] = csrfHash;

            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-guru/materi/edit') ?>",
                data: postData, // Sekarang data membawa token
                dataType: 'JSON',
                success: function(data) {
                    // Update token agar request berikutnya tidak error 403
                    updateCsrfToken(data.token);

                    // Isi field dasar
                    $("input[name=e_kode_materi]").val(data.kode_materi);
                    $("input[name=e_nama_materi]").val(data.nama_materi);
                    $("input[name=e_mapel]").val(data.mapel);
                    $("input[name=e_kelas]").val(data.kelas);
                    $("select[name=e_status]").val(data.status);

                    // Render Link Video
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
                error: function(xhr) {
                    // Jika ditolak karena token expired, CI4 biasanya mengirim token baru di response
                    if (xhr.responseJSON && xhr.responseJSON.token) {
                        updateCsrfToken(xhr.responseJSON.token);
                    }
                    console.log(xhr.responseText);
                    alert("Terjadi kesalahan keamanan (CSRF). Silakan coba lagi.");
                }
            });
        });


        // 6. Helper Function untuk tambah baris URL Video
        function addVideoRow(containerId, inputName, value = '') {
            const row = `
                <div class="link-group my-2">
                    <input type="text" class="form-control" name="${inputName}" value="${value}" placeholder="Masukkan URL video">
                    <span class="remove-link btn btn-danger ml-2">&times;</span>
                </div>`;
            $(containerId).append(row);
        }

        // Event Listener Tombol Tambah URL
        $('.tambah-baris-link').click(function() {
            addVideoRow('#link_video', 'text_materi[]');
        });

        $('#tambah-link-edit').click(function() {
            addVideoRow('#e_link_video', 'e_text_materi[]');
        });

        // Event Listener Hapus Baris (Delegasi)
        $(document).on('click', '.remove-link', function() {
            $(this).closest('.link-group').remove();
        });
    });
</script>
<?= $this->endSection(); ?>
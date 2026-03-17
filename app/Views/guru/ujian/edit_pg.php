<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?= $this->include('template/sidebar/guru'); ?>

<?php

use App\Models\UjiansiswaModel;

$UjiansiswaModel = new UjiansiswaModel();
?>
<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <form action="<?= base_url('guru/update_pg_'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="row layout-top-spacing">
                <div class="col-lg-12 layout-spacing">
                    <div class="widget shadow p-3">
                        <div class="widget-heading">
                            <h5 class="">Ujian Pilihan Ganda</h5>
                            <div class="row mt-2">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="">Nama Ujian</label>
                                        <input type="text" name="nama_ujian" class="form-control" value="<?= $ujian->nama_ujian; ?>" required>
                                        <input type="hidden" name="id_ujian" class="form-control" value="<?= $ujian->id_ujian; ?>">
                                        <input type="hidden" name="kode_ujian" class="form-control" value="<?= $ujian->kode_ujian; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="">Kelas</label>
                                        <select class="form-control" name="kelas" id="mapel_materi" required>
                                            <option value="">Pilih</option>
                                            <?php foreach ($guru_kelas as $gk) : ?>
                                                <option value="<?= $gk->id_kelas; ?>" <?= $ujian->kelas == $gk->id_kelas ? 'selected' : ''; ?>><?= $gk->nama_kelas; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="">Mapel</label>
                                        <select class="form-control" name="mapel" id="mapel_materi" required>
                                            <option value="">Pilih</option>
                                            <?php foreach ($guru_mapel as $gm) : ?>
                                                <option value="<?= $gm->id_mapel; ?>" <?= $gm->id_mapel == $ujian->mapel ? 'selected' : ''; ?>><?= $gm->nama_mapel; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="">Tambahkan Siswa Yang ikut ujian</label>
                                        <table id="datatable-table" class="table text-left text-nowrap">
                                            <thead>
                                                <tr>
                                                    <td>Nama Siswa</td>
                                                    <td>Status Siswa</td>
                                                    <td>Status Ujian</td>
                                                    <td class="text-center">Daftar Ujian<br /><input type="checkbox" onclick="toggle_ujian(this);" /></td>
                                                    <td class="text-center">Reset Ujian<br /><input type="checkbox" onclick="toggle_reset(this);" /></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($siswa as $rows) : ?>
                                                    <?php
                                                    $jawaban_siswa = $UjiansiswaModel
                                                        ->where('ujian', $ujian->kode_ujian)
                                                        //->where('siswa', session()->get('id'))
                                                        ->where('siswa', $rows->id_siswa)
                                                        ->where('jawaban!=', null)
                                                        ->get()->getNumRows();

                                                    $total_soal = $UjiansiswaModel
                                                        ->where('ujian', $ujian->kode_ujian)
                                                        //->where('siswa', session()->get('id'))
                                                        ->where('siswa', $rows->id_siswa)
                                                        ->get()->getNumRows();
                                                    ?>
                                                    <tr>

                                                        <td><?= $rows->nama_siswa; ?></td>
                                                        <td><?= $total_soal == 0 ? '<span class="text-danger">Belum Terdaftar Ujian</span>' : '<span class="text-success">Mengikuti Ujian</span>'; ?></td>
                                                        <td><?= $jawaban_siswa == 0 ? '<span class="text-danger">Tidak Mengerjakan Ujian</span>' : '<span class="text-success">Mengerjakan Ujian</span>'; ?></td>
                                                        <td class="text-center"><?= $total_soal == 0 ? '<input type="checkbox" name="id_siswa[]" value="' . $rows->id_siswa . '" id="tambah_ujian">' : '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-square-fill" viewBox="0 0 16 16">
  <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm10.03 4.97a.75.75 0 0 1 .011 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.75.75 0 0 1 1.08-.022z"/>
</svg>'; ?>
                                                        </td>
                                                        <td class="text-center"><input type="checkbox" name="id_siswa_reset[]" value="<?= $rows->id_siswa; ?> " id="reset_ujian"></td>
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <div class="col-lg-12">
                                    <a href="<?= base_url('guru/ujian'); ?>" class="btn btn-secondary">Kembali</a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <div class="widget shadow p-3">
                    <div class="widget-heading">
                        <h5 class="">Soal Ujian</h5>
                    </div>
                    <div id="soal_pg">
                        <div class="isi_soal">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Edit Soal Ujian</label>
                                    <table id="datatable-table-soal" class="table text-left text-nowrap">
                                        <thead>
                                            <tr>
                                                <td class="text-center">No</td>
                                                <td>Soal</td>
                                                <td class="text-center">Jawaban</td>
                                                <td class="text-center">Opsi</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($detail_ujian as $rows) : ?>
                                                <tr>
                                                    <td class="text-center"><?= $no++; ?></td>
                                                    <td class="text-wrap" style="width: 60%; color:#000000; font-weight:bold"><?= $rows->nama_soal; ?></td>
                                                    <td class="text-center" style="color:#000000; font-weight:bold"><?= $rows->jawaban; ?></td>
                                                    <td class="text-center" style="color:#000000; font-weight:bold"><a href="<?= base_url('guru/edit_soal/' . encrypt_url($rows->id_detail_ujian)); ?>" class="btn btn-success">Edit</a></td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-wrapper">
        <div class="footer-section f-section-1">
            <p class="terms-conditions"><?= copyright() ?></p>
        </div>
        <div class="footer-section f-section-2">
           
        </div>
    </div>
    </div>
</div>
<!--  END CONTENT AREA  -->


<script>
    function toggle_ujian(source) {
        var checkboxes = document.querySelectorAll('#tambah_ujian');
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i] != source)
                checkboxes[i].checked = source.checked;
        }
    }

    function toggle_reset(source) {
        var checkboxes = document.querySelectorAll('#reset_ujian');
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i] != source)
                checkboxes[i].checked = source.checked;
        }
    }
    <?= session()->getFlashdata('pesan'); ?>

    $(document).ready(function() {
        // SUMMERNOTE
        setInterval(() => {
            $('.summernote').summernote({
                placeholder: 'Hello stand alone ui',
                tabsize: 2,
                height: 120,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'help']]
                ],
                callbacks: {
                    onImageUpload: function(image, which_sum = this) {
                        uploadImage(image[0], which_sum);
                    },
                    onMediaDelete: function(target) {
                        deleteImage(target[0].src);
                    }
                }
            });
        }, 1000);

        function uploadImage(image, which_sum) {
            var data = new FormData();
            data.append("image", image);
            $.ajax({
                url: "<?= base_url('guru/upload_summernote') ?>",
                cache: false,
                contentType: false,
                processData: false,
                data: data,
                type: "POST",
                success: function(url) {
                    $(which_sum).summernote("insertImage", url);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        function deleteImage(src) {
            $.ajax({
                data: {
                    src: src
                },
                type: "POST",
                url: "<?= base_url('guru/delete_image') ?>",
                cache: false,
                success: function(response) {
                    console.log(response);
                }
            });
        }
    })
</script>


<?= $this->endSection(); ?>
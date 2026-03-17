<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?= $this->include('template/sidebar/pic'); ?>
<?php $db = Config\Database::connect(); ?>

<style>
.zoom:hover {
  transform: scale(1.5); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
}
</style>

<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <div class="widget shadow p-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="widget-heading">
                                <a href="javascript:void(0)" class="btn btn-primary mt-3" data-toggle="modal" data-target="#tambah_voucher">Tambah Galeri</a>
                            </div>
                            <div class="table-responsive">
                                <table id="datatable-table" class="table text-left text-nowrap">
                                    <thead>
                                        <tr>
                                            <th width="40%">Judul</th>
                                            <th>Gambar</th>
                                            <th width="20%">Tgl Pelatihan</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($galeri as $s) : ?>
                                            <tr>
                                                <td class="text-wrap"><?= $s->judul; ?></td>
                                                <td><img src="<?= base_url('uploads/galeri/thumbnails/' . $s->file); ?>" class="img-fluid zoom view-galeri" style="width:80px" alt="..." data-toggle="modal" data-target="#view_galeri" data-galeri="<?= $s->file; ?>" role="button" tabindex="0"></td>
                                                <td class="text-wrap"><?= $s->tgl_pelatihan; ?></td>
                                                <td>
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#edit_galeri" data-galeri="<?= encrypt_url($s->idgaleri); ?>" class="badge  bg-primary  edit-galeri">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                          <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                          <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                                        </svg>
                                                    </a>
                                                     <a href="<?= base_url('Pic/hapusGaleri/'.encrypt_url($s->idgaleri)) ?>" id="hapus" class="badge  bg-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
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
    <div class="footer-wrapper">
        <div class="footer-section f-section-1">
            <p class="terms-conditions"><?= copyright() ?></p>
        </div>
        <div class="footer-section f-section-2">
           
        </div>
    </div>
</div>
<!--  END CONTENT AREA  -->

<!-- MODAL -->
<!-- Modal Tambah -->
<div class="modal fade" id="tambah_voucher" tabindex="-1" role="dialog" aria-labelledby="tambah_voucherLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form action="<?= base_url('Pic/tambah_galeri'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambah_voucherLabel">Tambah Galeri</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="mt-3">
                                    <div class="form-group">
                                        <input type="file" name="file" accept="image/*" onchange="loadFile1(event)"><br>
                                        <span style="color: red; font-size: 12px; font-weight: bold;"><i> Max ukuran file 2MB</i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group required">
                                <label for="">Judul</label>
                                <input type="text" name="judul" class="form-control" placeholder="Judul Galeri" autofocus="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Tgl Pelatihan</label>
                                <input type="date" name="tgl_pelatihan" class="form-control" placeholder="" autofocus="">
                            </div>
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
<div class="modal fade" id="edit_galeri" tabindex="-1" role="dialog" aria-labelledby="edit_galeriLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('Pic/edit_galeri_'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_voucherLabel">Edit Galeri</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="mt-3">
                                <div class="form-group">
                                    <input type="hidden" id="e_idgaleri" name="idgaleri">
                                    <input type="file" name="file" accept="image/*">
                                    <input type="hidden" value="" name="file_lama" id="e_file_lama" class="form-control" /><br>
                                    <span style="color: red; font-size: 12px; font-weight: bold;"><i> Max ukuran file 2MB</i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group required">
                            <label for="">Judul</label>
                            <input type="text" id="e_judul" name="judul" class="form-control" placeholder="Judul">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group required">
                            <label for="">Tgl Pelatihan</label>
                            <input type="date" id="e_tgl_pelatihan" name="tgl_pelatihan" value="" class="form-control" placeholder="">
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


<!-- view file -->
<div class="modal fade" id="view_galeri" tabindex="-1" role="dialog" aria-labelledby="view_galeriLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content tampil-file">
        </div>
    </div>
</div>


<script>
    <?= session()->getFlashdata('pesan'); ?>

    $(document).ready(function() {

        $('.edit-galeri').click(function() {
            const id = $(this).data('galeri');
            $.ajax({
                type: 'GET',
                data: {
                    idgaleri: id
                },
                dataType: 'JSON',
                async: true,
                url: "<?= base_url('Pic/edit_galeri') ?>",
                success: function(data) {
                    $.each(data, function() {
                        $("#e_idgaleri").val(data.idgaleri);
                        $("#e_judul").val(data.judul);
                        $("#e_file_lama").val(data.file);
                        $("#e_tgl_pelatihan").val(data.tgl_pelatihan);
                        
                    });
                }
            });
        });
        
        
        $('.view-galeri').click(function() {
            var url = '<?= base_url('uploads/galeri/thumbnails/'); ?>';
            var file = $(this).data('galeri');
            var url_file = url+'/'+file;
            $(".tampil-file").html('<img src="'+url_file+'" class="img-fluid" alt="...">');
           
        });
        // END voucher
    });
    
    $(document).on("click", "#hapus", function(e) {
         var link = $(this).attr("href");
         e.preventDefault();
         let result = confirm("Anda yakin ingin menghapus data ini?");
         if (result) {
             document.location.href = link;
         }
    });
      
</script>

<?= $this->endSection(); ?>
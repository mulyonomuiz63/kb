<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?= $this->include('template/sidebar/admin'); ?>
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
                                <a href="<?= base_url('App/artikel'); ?>" class="btn btn-info mt-3">Kembali</a>
                                <a href="<?= base_url('App/tambah-kategori'); ?>" class="btn btn-primary mt-3">Tambah Kategori</a>
                            </div>
                            <div class="table-responsive" style="overflow-x: scroll;">
                                <table id="datatable-table" class="table text-left text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>kategori</th>
                                            <th class="text-center">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($kategori as $u) : ?>
                                            <tr>
                                                <td class="text-wrap" style="width:60%;"><?= $u->kategori; ?></td>
                                                <td class="text-center">
                                                    <div class="dropdown custom-dropdown">
                                                        <a class="dropdown-toggle btn btn-primary" href="#" role="button" id="dropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                                                <line x1="3" y1="12" x2="21" y2="12"></line>
                                                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                                                <line x1="3" y1="18" x2="21" y2="18"></line>
                                                            </svg>
                                                        </a>

                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink2">
                                                            <a class="dropdown-item" href="<?= base_url('App/edit-kategori/') . '/' . encrypt_url($u->id); ?>">Edit</a>
                                                            <a class="dropdown-item btn-hapus" href="<?= base_url('App/hapus-kategori/') . '/' . encrypt_url($u->id); ?>">Delete</a>
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

<div class="modal fade" id="view_artikel" tabindex="-1" role="dialog" aria-labelledby="view_artikel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content tampil-file">
        </div>
    </div>
</div>

<script>
    <?= session()->getFlashdata('pesan'); ?>
    $('.view-artikel').click(function() {
        var url = '<?= base_url('uploads/artikel/thumbnails/'); ?>';
        var file = $(this).data('artikel');
        var url_file = url+'/'+file;
        console.log(url_file);
        $(".tampil-file").html('<img src="'+url_file+'" class="img-fluid" alt="...">');
       
    });
</script>

<?= $this->endSection(); ?>
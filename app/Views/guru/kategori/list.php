<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<!--  BEGIN CONTENT AREA  -->
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <div class="widget shadow p-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="widget-heading">
                                <h5 class="">Kategori</h5>
                                <a href="<?= base_url('sw-guru/kategori'); ?>" class="btn btn-secondary mt-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-reply-fill" viewBox="0 0 16 16">
                                        <path d="M5.921 11.9 1.353 8.62a.72.72 0 0 1 0-1.238L5.921 4.1A.716.716 0 0 1 7 4.719V6c1.5 0 6 0 7 8-2.5-4.5-7-4-7-4v1.281c0 .56-.606.898-1.079.62z" />
                                    </svg>
                                </a>
                                <a href=" javascript:void(0)" class="btn btn-primary mt-3" data-toggle="modal" data-target="#tambah_kategori">Tambah Kategori</a>
                            </div>
                            <div class="table-responsive" style="overflow-x: scroll;">
                                <table id="datatable-table" class="table text-left text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>ID Kategori</th>
                                            <th>Kategori</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($kategori as $u) : ?>
                                            <tr>
                                                <td class="text-wrap text-center" style="width:10%;"><?= $u->id_kategori; ?></td>
                                                <td class="text-wrap" style="width:80%;"><?= $u->nama_kategori; ?></td>
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
                                                            <a class="dropdown-item btn_edit_kategori" href="javascript:void(0);" data-kategori="<?= encrypt_url($u->id_kategori); ?>" data-toggle="modal" data-target="#edit_kategori">Edit</a>
                                                            <a class="dropdown-item btn-hapus" href="<?= base_url('sw-guru/kategori/delete') . '/' . encrypt_url($u->id_kategori); ?>">Delete</a>
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

<!-- tambah -->
<div class="modal fade" id="tambah_kategori" tabindex="-1" role="dialog" aria-labelledby="tambah_kategoriLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <form action="<?= base_url('sw-guru/kategori/store'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambah_kategoriLabel">Tambah Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="">Kategori</label>
                                <input type="text" name="nama_kategori" value="" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" value="reset" class="btn btn-secondary" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Kembali</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- tambah -->


<!-- edit -->
<!-- tambah -->
<div class="modal fade" id="edit_kategori" tabindex="-1" role="dialog" aria-labelledby="edit_kategoriLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <form action="<?= base_url('sw-guru/kategori/update'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_kategoriLabel">Edit Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="">Kategori</label>
                                <input type="hidden" name="id_kategori" id="id_kategori">
                                <input type="text" name="nama_kategori" id="nama_kategori" value="" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" value="reset" class="btn btn-secondary" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Kembali</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- edit -->


<?= $this->endSection(); ?>
<?= $this->section("scripts"); ?>
<script>
    $(document).ready(function() {
        // 1. Inisialisasi variabel CSRF (Ambil dari sistem CI4)
        $(document).on('click', '.btn_edit_kategori', function() {
            const id_kategori = $(this).data('kategori');
            
            let postData = {
                id_kategori: id_kategori
            };
            
            // Masukkan token ke data yang akan dikirim
            postData[csrfName] = csrfHash;

            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-guru/kategori/edit') ?>",
                data: postData,
                dataType: 'JSON',
                success: function(data) {
                    // 2. Update variabel hash global
                    if (data.csrf_hash) {
                        csrfHash = data.csrf_hash;
                        
                        // 3. PENTING: Update semua input hidden CSRF di semua form agar tidak forbidden saat submit
                        $("input[name='" + csrfName + "']").val(csrfHash);
                    }

                    if (data.kategori) {
                        // Pastikan selektor menggunakan ID agar lebih spesifik (sesuai HTML Anda)
                        $("#id_kategori").val(data.kategori.id_kategori);
                        $("#nama_kategori").val(data.kategori.nama_kategori);
                    }
                },
                error: function(xhr, status, error) {
                    // Jika forbidden di sini, biasanya karena token sudah expired sebelum diklik
                    alert("Sesi verifikasi habis, silakan refresh halaman.");
                    location.reload();
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>
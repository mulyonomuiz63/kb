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
                                <h5 class="">Bank Soal</h5>
                                <a href="javascript:void(0)" class="btn btn-primary mt-3" data-toggle="modal" data-target="#tambah_bank_soal">Tambah Soal</a>
                                <a href="<?= base_url('sw-guru/kategori'); ?>" class="btn btn-primary mt-3">Tambah Kategori</a>

                            </div>
                            <div class="table-responsive" style="overflow-x: scroll;">
                                <table id="datatables-list" class="table text-left text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Nama Soal</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($soal as $u) : ?>
                                            <tr>
                                                <td class="text-wrap" style="width:90%; color:#000000; font-weight:bold"><?= $u->nama_soal; ?> (Kategori: <?= $u->nama_kategori; ?>)</td>
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
                                                            <a class="dropdown-item" href="<?= base_url('sw-guru/bank-soal/edit') . '/' . encrypt_url($u->id_bank_soal); ?>">Edit</a>
                                                            <a class="dropdown-item btn-hapus" href="<?= base_url('sw-guru/bank-soal/delete') . '/' . encrypt_url($u->id_bank_soal); ?>">Delete</a>
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

<div class="modal fade" id="tambah_bank_soal" tabindex="-1" role="dialog" aria-labelledby="tambah_bank_soalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambah_bank_soalLabel">Tambah Soal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body text-center">
                    <a href="<?= base_url('sw-guru/bank-soal/create'); ?>" class="btn btn-primary">Tambah</a>
                    <a href="javascript:void(0);" class="btn btn-primary ml-2" data-toggle="modal" data-target="#excel_ujian">Import</a>
                </div>
                <div class="modal-footer">
                    <button type="reset" value="reset" class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="excel_ujian" tabindex="-1" role="dialog" aria-labelledby="excel_ujianLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="<?= base_url('guru/excel_bank_soal_pg'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="excel_ujianLabel">Import Soal via Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">File Excel</label><br>
                                <input type="file" name="excel" accept=".xls, .xlsx">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label for="">Template</label><br>
                            <a href="<?= base_url('download/excel_soal_pg'); ?>" class="btn btn-success">Download Template</a>
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
<?= $this->endSection(); ?>
<?= $this->section("scripts"); ?>
<script>
    $(document).ready(function() {
        $('#datatables-list').DataTable({
            "ordering": false,
            "lengthChange": false,
            "pageLength": 10
        });
    });
</script>
<?= $this->endSection(); ?>
<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?= $this->include('template/sidebar/pic'); ?>
<?php $db = Config\Database::connect(); ?>
<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <div class="widget shadow p-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="widget-heading">
                                <a href="javascript:void(0)" class="btn btn-primary mt-3" data-toggle="modal" data-target="#tambah_batch">Tambah Batch</a>
                            </div>
                            <div class="table-responsive">
                                <table id="datatable-table" class="table text-left text-nowrap">
                                    <thead>
                                        <tr>
                                            <th width="60%">Batch</th>
                                            <th class="text-center">Total Pelatihan</th>
                                            <th class="text-center">Status</th>
                                            <th width="10%">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       <?php foreach($batch as $rows): ?>
                                           <?php 
                                                $dataJadwal = $db->query("select status from jadwal_pelatihan where idbatch = '$rows->idbatch'")->getResultObject();
                                                $total = 0;
                                                $selesai = 0;
                                                foreach($dataJadwal as $item){
                                                    $total++;
                                                    if($item->status == 'Selesai'){
                                                        $selesai++;
                                                    }
                                                }
                                           ?>
                                            <tr>
                                                <td class="text-wrap"><?= $rows->batch ?></td>
                                                <td class="text-center"> <?= $selesai.'/'.$total ?></td>
                                                <td class="text-center"><span class="badge  bg-<?= $rows->status_batch == 'S'? "success":"warning" ?> text-white"><?= $rows->status_batch == 'S'? 'Selesai':'Menunggu' ?></span></td>
                                                <td>
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#edit_batch" data-batch="<?= encrypt_url($rows->idbatch); ?>" class="badge  bg-primary  edit-batch">
                                                        <i class="bi bi-gear"></i>
                                                    </a>
                                                    <a href="<?= base_url('Pic/detail_jadwal/'.encrypt_url($rows->idbatch)) ?>" class="badge  bg-success">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="<?= base_url('Pic/hapusBatch/'.encrypt_url($rows->idbatch)) ?>" id="hapus" class="badge  bg-danger">
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
<div class="modal fade" id="tambah_batch" tabindex="-1" role="dialog" aria-labelledby="tambah_batchlLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form action="<?= base_url('Pic/tambah_batch'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambah_batchLabel">Batch Pelatihan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="form-group required">
                                <label for="">Batch</label>
                                <textarea class="form-control" name="batch" rows="2" placeholder="Batch pelatihan"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Status</label>
                                <select class="form-control" name="status_batch">
                                    <option value="S">Selesai</option>
                                    <option value="B">Menunggu</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" value="reset" class="btn btn-info" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal edit -->
<div class="modal fade" id="edit_batch" tabindex="-1" role="dialog" aria-labelledby="edit_batchLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form action="<?= base_url('Pic/edit_batch_'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_batchLabel">Edit Batch</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                            <div class="form-group required">
                                <label for="">Batch</label>
                                <textarea class="form-control" name="batch" id="e_batch" rows="2" placeholder="Batch pelatihan"></textarea>
                                <input type="hidden" id="e_idbatch" name="idbatch" value="">
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Status</label>
                                <select class="form-control" name="status_batch" id="e_status_batch">
                                    <option value="S">Selesai</option>
                                    <option value="B">Menunggu</option>
                                </select>
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="reset" value="reset" class="btn btn-info" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    <?= session()->getFlashdata('pesan'); ?>

    $(document).ready(function() {
        $('.edit-batch').click(function() {
            const id = $(this).data('batch');
            $.ajax({
                type: 'GET',
                data: {
                    id: id
                },
                dataType: 'JSON',
                async: true,
                url: "<?= base_url('Pic/edit_batch') ?>",
                success: function(data) {
                    $.each(data, function(idbatch, batch, status_batch) {
                        $("#e_idbatch").val(data.idbatch);
                        $("#e_batch").val(data.batch);
                        $("#e_status_batch").val(data.status_batch);
                    });
                }
            });
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
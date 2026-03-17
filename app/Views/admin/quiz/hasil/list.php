<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?= $this->include('template/sidebar/admin'); ?>
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
                                <table>
                                    <tr>
                                        <td width="30%"><h5 class="">Hadiah Ujian</h5></td>
                                        <td width="1%">:</td>
                                        <td><?= $quiztem->hadiah ?></td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2" width="30%"><a href="<?= base_url('App/quiz') ?>" class="btn btn-info mt-3" >Kembali</a></td>
                                    </tr>
                                </table>
                                
                            </div>
                            <div class="table-responsive">
                                <table id="datatable-table" class="table text-left text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Nilai</th>
                                            <th>Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($hasil as $key=>$s) : ?>
                                            <tr>
                                                <td><?= $s->nama_siswa; ?></td>
                                                <td><?= $s->skor_dasar + $s->bonus ?></td>
                                                <td>
                                                    <?php if(($key+1) <= $quiztem): ?>
                                                        <a href="javascript:void(0)" class="badge  bg-success me-1 myFunctionWA" data-url="Helo, Kami dari kelasbrevet.com selamat anda telah berhasil memenangkan quiz."><i class="bi bi-whatsapp"></i></a>
                                                    <?php endif; ?>
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
<div class="modal fade" id="tambah_testimoni" tabindex="-1" role="dialog" aria-labelledby="tambah_quizLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form action="<?= base_url('App/tambah_quiz'); ?>" method="POST">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambah_quizLabel">Tambah Quiz</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Tema</label>
                            <textarea name="judul" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Deskripsi Tema</label>
                            <textarea name="deskripsi" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Status</label>
                            <select name="status" class="form-control">
                                <option value="A">Aktif</option>
                                <option value="S">Tidak Aktif</option>
                            </select>
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
<div class="modal fade" id="edit_quiztem" tabindex="-1" role="dialog" aria-labelledby="edit_quizLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('App/edit_quiz_'); ?>" method="POST">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_quizLabel">Edit Quiz</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="idquiztem" id="e_idquiztem">
                    <div class="form-group">
                        <label for="">Tema</label>
                        <div id="e_judul"></div>
                    </div>
                    <div class="form-group">
                        <label for="">Deskripsi Tema</label>
                        <div id="e_deskripsi"></div>
                    </div>
                    <div class="form-group">
                        <label for="">Status</label>
                        <select name="status" id="e_status" class="form-control">
                            <option value="A">Aktif</option>
                            <option value="S">Tidak Aktif</option>
                        </select>
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


<script>
    <?= session()->getFlashdata('pesan'); ?>

    $(document).ready(function() {

        $('.edit-quiztem').click(function() {
            const idquiztem = $(this).data('idquiztem');
            console.log(idquiztem);
            $.ajax({
                type: 'POST',
                data: {
                    idquiztem: idquiztem
                },
                dataType: 'JSON',
                async: true,
                url: "<?= base_url('App/edit_quiz') ?>",
                success: function(data) {
                    console.log(data);
                    $.each(data, function(idquiztem, judul, deskripsi, status) {
                        $("#e_idquiztem").val(data.idquiztem);
                        $("#e_status").val(data.status);
                        $("#e_judul").html(`
                        <textarea type="text" name="judul" class="form-control">${data.judul}</textarea>`);
                        $("#e_deskripsi").html(`
                        <textarea type="text" name="deskripsi" class="form-control">${data.deskripsi}</textarea>`);
                    });
                }
            });
        });
        
        function commaOnly(input){ 

        var value = input.val();
        var values = value.split("");
        var update = "";
        var transition = "";
    
        var expression=/(^\d+$)|(^\d+\.\d+$)|[,\.]/;
        var finalExpression=/^([1-9][0-9]*[,\.]?\d{0,3})$/;
    
        for(id in values){           
            if (expression.test(values[id])==true && values[id]!=''){
                transition+=''+values[id].replace(',','.');
                if(finalExpression.test(transition) == true){
                    update+=''+values[id].replace(',','.');
                }
            }
        }
        input.val(update);
    }
    
    $('#diskon').keyup(function (e) {
      commaOnly($(this));
    });
    
    $('#e_diskon').keyup(function (e) {
      commaOnly($(this));
    });
        // END diskon
    })
    
    $('.myFunctionWA').click(function() {
        var slugWa = $(this).data('url');
        window.location = "https://api.whatsapp.com/send/?text="+slugWa;
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    

<?= $this->endSection(); ?>
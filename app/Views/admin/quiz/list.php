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
                                <h5 class="">Tema Quiz</h5>
                                <a href="javascript:void(0)" class="btn btn-primary mt-3" data-toggle="modal" data-target="#tambah_testimoni">Tambah Quiz</a>
                            </div>
                            <div class="table-responsive">
                                <table id="datatable-table" class="table text-left text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Tema Quiz</th>
                                            <th>Status</th>
                                            <th class="text-right">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($quiztem as $s) : ?>
                                            <tr>
                                                <td><?= $s->judul; ?></td>
                                                <td></td>
                                                <td class="text-right">
                                                    <a href="<?= base_url('App/hasil/'.encrypt_url($s->idquiztem)) ?>" class="badge  bg-warning">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="<?= base_url('App/soal/'.encrypt_url($s->idquiztem)) ?>" class="badge  bg-success">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#edit_quiztem" data-idquiztem="<?= encrypt_url($s->idquiztem); ?>" class="badge  bg-primary edit-quiztem">
                                                        <i class="bi bi-gear"></i>
                                                    </a>
                                                    <a href="<?= base_url('App/hapus_quiz/') . '/' . encrypt_url($s->idquiztem); ?>" class="badge  bg-danger btn-hapus">
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
                        <div class="form-group">
                            <label for="">Start Quiz</label>
                            <input type="date" name="start" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">End Quiz</label>
                            <input type="date" name="end" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Hadiah</label>
                            <input type="text" name="hadiah" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Jumlah Pemenang</label>
                            <input type="number" name="jumlah" class="form-control">
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
                    <div class="form-group">
                            <label for="">Start Quiz</label>
                            <input type="date" name="start" id="e_start" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">End Quiz</label>
                            <input type="date" name="end" id="e_end" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Hadiah</label>
                            <input type="text" name="hadiah" id="e_hadiah" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Jumlah Pemenang</label>
                            <input type="number" name="jumlah" id="e_jumlah" class="form-control">
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
                        $("#e_start").val(data.start);
                        $("#e_end").val(data.end);
                        $("#e_hadiah").val(data.hadiah);
                        $("#e_jumlah").val(data.jumlah);
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
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    

<?= $this->endSection(); ?>
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
                                <h5 class="">Soal Quiz</h5>
                                <a href="<?= base_url('App/quiz') ?>" class="btn btn-info mt-3" >Kembali</a>
                                <a href="javascript:void(0)" class="btn btn-primary mt-3" data-toggle="modal" data-target="#tambah_testimoni">Tambah Soal</a>
                            </div>
                            <div class="table-responsive">
                                <table id="datatable-table" class="table text-left text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Soal</th>
                                            <th class="text-right">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($quiz as $s) : ?>
                                            <tr>
                                                <td><?= $s->pertanyaan; ?></td>
                                                <td class="text-right">
                                                    <a href="javascript:void(0)" data-idquiz="<?= encrypt_url($s->id); ?>" class="badge  bg-primary edit-quiz">
                                                        <i class="bi bi-gear"></i>
                                                    </a>
                                                    <a href="<?= base_url('App/hapus_soal/') . '/' . encrypt_url($s->id).'/'.encrypt_url($s->idquiztem); ?>" class="badge  bg-danger btn-hapus">
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
        <form action="<?= base_url('App/tambah_soal'); ?>" method="POST">
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
                        <input type="hidden" name="idquiztem" value="<?= $idquiztem ?>">
                        <div class="form-group">
                          <label class="form-label">Pertanyaan</label>
                          <textarea name="pertanyaan" class="form-control" rows="3" required></textarea>
                        </div>
                    
                        <div class="form-group">
                          <label class="form-label">Opsi Jawaban</label>
                          <?php for($i=0;$i<4;$i++): ?>
                            <input type="text" name="opsi[]" class="form-control mb-2" placeholder="Opsi <?= $i+1 ?>" required>
                          <?php endfor; ?>
                        </div>
                    
                        <div class="form-group">
                          <label class="form-label">Jawaban Benar</label>
                          <input type="text" name="jawaban" class="form-control" placeholder="Tuliskan jawaban yang benar persis seperti opsi" required>
                        </div>
                    
                        <div class="form-group">
                          <label class="form-label">Bonus</label>
                          <input type="number" name="bonus" class="form-control" value="0">
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
<div class="modal fade" id="edit_quiz" tabindex="-1" role="dialog" aria-labelledby="edit_quizLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('App/edit_soal_'); ?>" method="POST">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_quizLabel">Edit Quiz</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                        <input type="hidden" name="idquiz" id="edit_idquiz">
                        <input type="hidden" name="idquiztem" id="edit_idquiztem">
                    
                      <div class="form-group">
                        <label>Pertanyaan</label>
                        <textarea name="pertanyaan" id="edit_pertanyaan" class="form-control" required></textarea>
                      </div>
            
                      <div class="form-group">
                        <label>Opsi Jawaban</label>
                        <div id="edit_opsi_wrapper"></div>
                      </div>
            
                      <div class="form-group">
                        <label>Jawaban Benar</label>
                        <input type="text" name="jawaban" id="edit_jawaban" class="form-control" required>
                      </div>
            
                      <div class="form-group">
                        <label>Bonus</label>
                        <input type="number" name="bonus" id="edit_bonus" class="form-control">
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

        $('.edit-quiz').click(function(){
            const idquiz = $(this).data('idquiz');
            $.ajax({
              type: 'POST',
              url: "<?= base_url('app/edit_soal') ?>",
              data: { idquiz: idquiz },
              dataType: 'JSON',
              success: function(data){
                // isi form di modal
                $('#edit_idquiz').val(data.id);
                $('#edit_idquiztem').val(data.idquiztem);
                $('#edit_pertanyaan').val(data.pertanyaan);
                $('#edit_jawaban').val(data.jawaban);
                $('#edit_bonus').val(data.bonus);
        
                // isi opsi
                let opsiHTML = '';
                let opsiArr = JSON.parse(data.opsi);
                opsiArr.forEach(function(op){
                  opsiHTML += `<input type="text" name="opsi[]" value="${op}" class="form-control mb-2" required>`;
                });
                $('#edit_opsi_wrapper').html(opsiHTML);
        
                // buka modal
                $('#edit_quiz').modal('show');
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
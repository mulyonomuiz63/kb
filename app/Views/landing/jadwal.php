<?= $this->extend('landing/template'); ?>
<?= $this->section('content'); ?>

<div class="pb-4 d-flex align-items-center mt-8" >
    <div class="container jadwal-table">
        <h6>Jadwal Pelatihan Brevet Pajak AB</h6>
        <span>Informasi jadwal pelatihan secara online</span>
        <div class="row ">
            <div class="col-12">
                <div class="accordion mt-4" id="accordionJadwal">
                    <?php 
                        $no=0; 
                        foreach($data["batch"] as $rows): 
                        $no++
                    ?>
                        <div class="accordion-item" >
                            <h2 class="accordion-header">
                              <button class="accordion-button <?= $no%2? 'bg-primary-subtle':'bg-warning-subtle' ?> <?= $no != 1? 'collapsed':'' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $rows["idbatch"] ?>" aria-expanded="true" aria-controls="collapse<?= $rows["idbatch"] ?>">
                                <?= strtoupper($rows["batch"]) ?> <?= $rows['status_batch'] == 'S'? ' <span class="badge bg-success ms-4">(Selesai)</span>':'' ?>
                              </button>
                            </h2>
                            <div id="collapse<?= $rows["idbatch"] ?>" class="accordion-collapse collapse <?= $no == 1? 'show':'' ?>" data-bs-parent="#accordionJadwal">
                              <div class="accordion-body table-responsive-sm">
                               <table id="example font-jadwal" class="display nowrap table-striped table-bordered table " style="width:100%; font-size:12px">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="50%">Materi</th>
                                            <th width="5%">Jenis</th>
                                            <th>Kelas</th>
                                            <th>Kuota</th>
                                            <th width="18%">Waktu</th>
                                            <th width="7%">Presensi</th>
                                            <th width="7%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $idbatch = $rows['idbatch'];
                                            $jadwal = $db->query("select * from jadwal_pelatihan where idbatch = '$idbatch'")->getResult();
                                        ?>
                                        <?php if(!empty($jadwal)): ?>
                                            <?php foreach($jadwal as $item): ?>
                                                <tr>
                                                    <td class="font-jadwal"><?= $item->materi ?></td>
                                                    <td class="font-jadwal"><span class="badge bg-success"><?= $item->jenis ?></span></td>
                                                    <td class="font-jadwal"><?= $item->kelas ?></td>
                                                    <td class="font-jadwal">
                                                        Kapasitas: <?= $item->kapasitas ?>
                                                        <br>
                                                        Peserta: <?= $item->pendaftar ?>
                                                    </td>
                                                    <td class="wrap-text font-jadwal"><?= hari($item->tgl_pelatihan).', '. tanggal_indo($item->tgl_pelatihan).' ('.$item->mulai.'-'. $item->berakhir .' WIB)' ?></td>
                                                    <td class="text-center">
                                                    <?php
                                                        if($item->tgl_pelatihan == date("Y-m-d")){
                                                        // if("2025-09-24" == date("Y-m-d")){
                                                            $mulai = $item->mulai;    // jam mulai (bisa diganti sesuai kebutuhan)
                                                            
                                                            // hitung jam selesai = jam mulai + 4 jam
                                                            $selesai = date("H:i", strtotime($mulai . " +4 hours"));
                                                            
                                                            // waktu sekarang
                                                            $sekarang = date("H:i");
                                                            
                                                            // cek dengan if
                                                            if ($sekarang >= $mulai && $sekarang <= $selesai) {
                                                                echo '
                                                                <button type="button"  class="badge bg-warning border-0 p-2 presensi" data-idjadwalpelatihan="'.encrypt_url($item->id).'" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                                                  <i class="bi bi-disc-fill"></i>
                                                                </button>
                                                                ';
                                                            }else{
                                                                echo '
                                                                    <button type="button"  class="badge bg-secondary disabled border-0 p-2">
                                                                      <i class="bi bi-disc-fill"></i>
                                                                    </button>
                                                                    ';
                                                            }
                                                        }else{
                                                            echo '
                                                                <button type="button"  class="badge bg-secondary disabled border-0 p-2">
                                                                  <i class="bi bi-disc-fill"></i>
                                                                </button>
                                                                ';
                                                        }
                                                    ?>
                                                    </td>
                                                    <td class="font-jadwal">
                                                        <?php
                                                        if($item->tgl_pelatihan == date("Y-m-d")){
                                                        // if("2025-09-22" == date("Y-m-d")){
                                                            $mulai = $item->mulai;    // jam mulai (bisa diganti sesuai kebutuhan)
                                                            
                                                            // hitung jam selesai = jam mulai + 4 jam
                                                            $selesai = date("H:i", strtotime($mulai . " +4 hours"));
                                                            
                                                            // waktu sekarang
                                                            $sekarang = date("H:i");
                                                            
                                                            // cek dengan if
                                                            if ($sekarang >= $mulai && $sekarang <= $selesai) {
                                                                echo '
                                                                <span class="badge bg-warning">Sedang Pelatihan</span>
                                                                ';
                                                            }else{
                                                                if ($sekarang >= $mulai) {
                                                                    echo '
                                                                    <span class="badge bg-success">Selesai</span>
                                                                    ';
                                                                }else{
                                                                    echo '
                                                                        <span class="badge bg-info">Menunggu</span>
                                                                        ';
                                                                }
                                                            }
                                                        }else{
                                                            if($item->status == "Selesai"){
                                                                echo '<span class="badge bg-success">'. $item->status .'</span>';
                                                            }else{
                                                                echo '<span class="badge bg-info">'. $item->status .'</span>';
                                                            }                                                        }
                                                    ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                        <?php else: ?>
                                            <tr>
                                                <td class="text-center" colspan="6">Jadwal Belum Tersedia...</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                              </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
                
            </div>
            <div class="col-12 float-sm-end mt-4"> 
                <?php echo $data["pager"]->links('default', 'custom_pager') ?>
            </div>
        </div> 
    </div>
                        <!-- All Courses Wrapper End -->
</div>


<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog d-flex justify-content-center modal-dialog-centered">
    <div class="modal-content modal-sm">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Presensi Kehadiran</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <form action="<?= base_url('landing/presensi_'); ?>" method="post" class="text-left">
          <div class="modal-body">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="form">
                <div class="mb-3">
                    <label for="recipient-name" class="col-form-label">Email Terdaftar</label>
                    <input type="email" name="email" class="form-control py-1 px-1 border-1 fs-6">
                    <input type="hidden" name="idjadwalpelatihan" id="idjadwalpelatihan" value="">
                </div>
            </div>
           
          </div>
          <div class="modal-footer">
            <button type="submit" class="badge bg-primary border-0 p-2">Kirim</button>
          </div>
        </form>
    </div>
  </div>
</div>
<script src="<?= base_url('assets/app-assets/template/cbt-malela'); ?>/assets/js/libs/jquery-3.1.1.min.js"></script>
<script src="<?= base_url('assets/app-assets/template/cbt-malela'); ?>/bootstrap/js/popper.min.js"></script>
<script>
    $(document).ready(function() {
        $('.presensi').click(function() {
            const id = $(this).data('idjadwalpelatihan');
            $('#idjadwalpelatihan').val(id);
        });
    });
</script>


<?= $this->endSection(); ?>
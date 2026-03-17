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
                                <a href="<?= base_url('Pic/jadwal') ?>" class="btn btn-info">Kembali</a>
                                <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal" data-target="#tambah_jadwal">Tambah Jadwal Pelatihan</a>
                            </div>
                            <div class="table-responsive">
                                <table id="datatable-table" class="table text-left text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Materi</th>
                                            <th>Jenis</th>
                                            <th>Kelas</th>
                                            <th>Quota</th>
                                            <th>Waktu</th>
                                            <th>Presensi</th>
                                            <th>Status</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       <?php foreach($jadwal as $rows): ?>
                                        <?php
                                            if($rows->tgl_pelatihan == date("Y-m-d")){
                                            // if("2025-09-24" == date("Y-m-d")){
                                                $mulai = $rows->mulai;    // jam mulai (bisa diganti sesuai kebutuhan)
                                                
                                                // hitung jam selesai = jam mulai + 4 jam
                                                $selesai = date("H:i", strtotime($mulai . " +4 hours"));
                                                
                                                // waktu sekarang
                                                $sekarang = date("H:i");
                                                
                                                // cek dengan if
                                                if ($sekarang >= $mulai && $sekarang <= $selesai) {
                                                    $presensi = '
                                                    <button type="button"  class="badge bg-warning border-0 p-2 presensi" data-idjadwalpelatihan="'.base_url('presensi/'.encrypt_url($rows->id)).'" onclick="copyId(this)" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                                      <i class="bi bi-copy"></i>
                                                    </button>
                                                    ';
                                                    $status = '<span class="badge  bg-warning text-white">Sedang Pelatihan</span>';
                                                }else{
                                                    $presensi = '
                                                        <button type="button"  class="badge bg-light disabled border-0 p-2">
                                                          <i class="bi bi-copy"></i>
                                                        </button>
                                                        ';
                                                    if ($sekarang >= $mulai) {
                                                        $status = '
                                                        <span class="badge bg-success">Selesai</span>
                                                        ';
                                                    }else{
                                                        $status = '
                                                            <span class="badge bg-dark">Menunggu</span>
                                                            ';
                                                    }
                                                }
                                            }else{
                                                $presensi = '
                                                    <button type="button"  class="badge bg-light disabled border-0 p-2">
                                                      <i class="bi bi-copy"></i>
                                                    </button>
                                                    ';
                                                if($rows->status == "Selesai"){
                                                    $status = '<span class="badge bg-success">'. $rows->status .'</span>';
                                                }else{
                                                    $status = '<span class="badge bg-dark">'. $rows->status .'</span>';
                                                }  
                                            }
                                        ?>
                                            <tr>
                                                <td class="text-wrap"><?= $rows->materi ?></td>
                                                <td><span class="badge  bg-<?= $rows->jenis == 'Onile'? "seuccess":"info" ?> text-white"><?= $rows->jenis ?></span></td>
                                                <td><?= $rows->kelas ?></td>
                                                <td>
                                                    Kapasitas: <b><?= $rows->kapasitas ?></b>
                                                    <br>
                                                    Peserta: <b><?= $rows->pendaftar ?></b>
                                                    <br>
                                                    <?php
                                                        $total = $db->table('presensi')->select('count(idpresensi) as total')->where('presensi.idjadwalpelatihan', $rows->id)->get()->getRowObject();
                                                    ?>
                                                    Presensi: <b><?= $total->total ?></b>
                                                </td>
                                                <td class="text-wrap"><?= hari($rows->tgl_pelatihan).', '. tanggal_indo($rows->tgl_pelatihan).' ('.$rows->mulai.'-'. $rows->berakhir .' WIB)' ?></td>
                                                <td class="text-center"><?= $presensi ?></td>
                                                <td class="text-center"><?= $status ?></td>
                                                <td>
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#edit_jadwal" data-jadwal="<?= encrypt_url($rows->id); ?>" class="badge  bg-primary  edit-jadwal">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                          <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                          <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                                        </svg>
                                                    </a>
                                                    <a href="<?= base_url('Pic/presensi/'.encrypt_url($rows->id)) ?>" class="badge  bg-success">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                     <a href="<?= base_url('Pic/hapusJadwal/'.encrypt_url($rows->id).'/'.encrypt_url($rows->idbatch)) ?>" id="hapus" class="badge  bg-danger">
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
<div class="modal fade" id="tambah_jadwal" tabindex="-1" role="dialog" aria-labelledby="tambah_jadwalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form action="<?= base_url('Pic/tambah_jadwal'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambah_jadwalLabel">Jadwal Pelatihan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="form-group required">
                                <label for="">Materi</label>
                                <input type="hidden" name="idbatch" value="<?= encrypt_url($idbatch) ?>">
                                <textarea class="form-control" name="materi" rows="2" placeholder="Materi pelatihan" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Jenis Pelatihan</label>
                                <select class="form-control" name="jenis">
                                    <option value="Online">Online</option>
                                    <option value="Offline">Offline</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Kelas</label>
                                <select class="form-control" name="kelas">
                                    <option value="Reguler">Reguler</option>
                                    <option value="Biasiswa">Biasiswa</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group required">
                                <label for="">Kapasistas</label>
                                <input type="number" name="kapasitas" class="form-control" placeholder="Kapasitas" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group required">
                                <label for="">Peserta</label>
                                <input type="number" name="pendaftar" class="form-control" placeholder="Pendaftar" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group required">
                                <label for="">Tgl Pelatihan</label>
                                <input type="date" name="tgl_pelatihan" class="form-control" placeholder="" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group required">
                                <label for="">Waktu Mulai</label>
                                <input type="time" name="mulai" class="form-control" placeholder="" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group required">
                                <label for="">Waktu Berakhir</label>
                                <input type="time" name="berakhir" class="form-control" placeholder="" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Status</label>
                                <select class="form-control" name="status">
                                    <option value="Menunggu">Menunggu</option>
                                    <option value="Selesai">Selesai</option>
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
<div class="modal fade" id="edit_jadwal" tabindex="-1" role="dialog" aria-labelledby="edit_jadwalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <form action="<?= base_url('Pic/edit_jadwal_'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_jadwalLabel">Edit Jadwal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                            <div class="form-group required">
                                <label for="">Materi</label>
                                <textarea class="form-control" name="materi" id="e_materi" rows="2" placeholder="Materi pelatihan"></textarea>
                                <input type="hidden" name="idbatch" value="<?= encrypt_url($idbatch) ?>">
                                <input type="hidden" id="e_id" name="id" value="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Jenis Pelatihan</label>
                                <select class="form-control" id="e_jenis" name="jenis">
                                    <option value="Online">Online</option>
                                    <option value="Offline">Offline</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Kelas</label>
                                <select class="form-control" id="e_kelas" name="kelas">
                                    <option value="Reguler">Reguler</option>
                                    <option value="Biasiswa">Biasiswa</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group required">
                                <label for="">Kapasistas</label>
                                <input type="number" name="kapasitas" id="e_kapasitas" class="form-control" placeholder="Kapasitas">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group required">
                                <label for="">Peserta</label>
                                <input type="number" name="pendaftar" id="e_pendaftar" class="form-control" placeholder="Pendaftar">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group required">
                                <label for="">Tgl Pelatihan</label>
                                <input type="date" name="tgl_pelatihan" id="e_tgl_pelatihan" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group required">
                                <label for="">Waktu Mulai</label>
                                <input type="time" name="mulai" id="e_mulai" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group required">
                                <label for="">Waktu Berakhir</label>
                                <input type="time" name="berakhir"  id="e_berakhir"class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Status</label>
                                <select class="form-control" id="e_status" name="status">
                                    <option value="Menunggu">Menunggu</option>
                                    <option value="Selesai">Selesai</option>
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
        $('.edit-jadwal').click(function() {
            const id = $(this).data('jadwal');
            $.ajax({
                type: 'GET',
                data: {
                    id: id
                },
                dataType: 'JSON',
                async: true,
                url: "<?= base_url('Pic/edit_jadwal') ?>",
                success: function(data) {
                    $.each(data, function(id, materi, jenis, kelas, kapasitas, pendaftar, waktu, status) {
                        $("#e_id").val(data.id);
                        $("#e_materi").val(data.materi);
                        $("#e_jenis").val(data.jenis);
                        $("#e_kelas").val(data.kelas);
                        $("#e_kapasitas").val(data.kapasitas);
                        $("#e_pendaftar").val(data.pendaftar);
                        $("#e_tgl_pelatihan").val(data.tgl_pelatihan);
                        $("#e_mulai").val(data.mulai);
                        $("#e_berakhir").val(data.berakhir);
                        $("#e_status").val(data.status);
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
      
      
    //untuk copy link
    function copyId(el) {
    // Ambil data-idjadwalpelatihan dari tombol yang diklik
        const idJadwal = el.getAttribute("data-idjadwalpelatihan");
    
        // Copy ke clipboard
        navigator.clipboard.writeText(idJadwal)
          .then(() => {
            alert("Link berhasil dicopy.");
          })
          .catch(err => {
            alert("Gagal menyalin: " + err);
          });
    }
</script>

<?= $this->endSection(); ?>
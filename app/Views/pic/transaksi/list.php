<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?= $this->include('template/sidebar/pic'); ?>
<?php $db = Config\Database::connect(); ?>
<style>
    .zoom {
          transition: transform .2s; /* Animation */
        }
        
    .zoom:hover {
      transform: scale(1.1); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
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
                                <h5 class="">Transaksi</h5>
                            </div>
                            <div class="table-responsive">
                                <table id="datatable-table" class="table text-left text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Siswa</th>
                                            <th>Email</th>
                                            <th>Hp</th>
                                            <th>Paket</th>
                                            <th class="text-wrap">Tgl Bergabung</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($transaksi as $s) : ?>
                                            <tr>
                                                <td class="text-wrap">
                                                    <?php if ($s->status == 'S') : ?>
                                                        <a href="<?= base_url('App/ujian/'.encrypt_url($s->id_siswa)) ?>" >
                                                            <?= $s->nama_siswa; ?></td>
                                                        </a>
                                                    <?php else : ?>
                                                        <?= $s->nama_siswa; ?></td>
                                                    <?php endif; ?>
                                                <td class="text-wrap"><?= $s->email; ?></td>
                                                <td class="text-wrap">
                                                    <a href="https://wa.me/<?= number_hp($s->hp) ?>" target="_blank" class="badge  bg-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Kirim pesan ke WA">
                                                        <?= $s->hp ?>
                                                    </a>
                                                </td>
                                                <td class="text-wrap"><?= $s->nama_paket; ?></td>
                                                <td class="text-wrap"><?= $s->tgl_pembayaran; ?></td>
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

<!-- Modal Validasi -->
<div class="modal fade" id="validasi_transaksi" tabindex="-1" role="dialog" aria-labelledby="validasi_transaksiLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('App/approve_transaksi'); ?>" method="POST">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <div class="modal-content">
                <div id="isiKonten"></div>

            </div>
        </form>
    </div>
</div>


<script>
    <?= session()->getFlashdata('pesan'); ?>

    $(document).ready(function() {

        $('.validasi-transaksi').click(function() {
            const idtransaksi = $(this).data('transaksi');
            $.ajax({
                type: 'POST',
                data: {
                    idtransaksi: idtransaksi
                },
                dataType: 'JSON',
                async: true,
                url: "<?= base_url('App/validasi_transaksi') ?>",
                success: function(data) {
                    $.each(data, function() {
                        var diskon = (data.nominal*data.diskon)/100; 
                        var totalDiskon = data.nominal - diskon;
                        var diskon_voucher = (totalDiskon*data.voucher)/100;
                        
                        //untuk status
                        if (data.status == 'S') {
                            var pilihstatus = `<span class="badge  bg-success">Sudah dibayar</span>`
                        } else if(data.status == 'P') {
                            var pilihstatus = `<span class="badge  bg-warning">Pilih Metode Bayar</span>`;
                        } else if(data.status == 'M') {
                            var pilihstatus = `<span class="badge  bg-warning">Proses Pembayaran</span>`;
                        } else if(data.status == 'PM') {
                            var pilihstatus = `<span class="badge  bg-danger">Pending</span>`;
                        } else if(data.status == 'DM') {
                            var pilihstatus = `<span class="badge  bg-danger">Denied</span>`;
                        } else if(data.status == 'E') {
                            var pilihstatus = `<span class="badge  bg-danger">Expire</span>`;
                        }else{
                            var pilihstatus = `<span class="badge  bg-info">Menunggu Approved</span>`;
                        }
                        
                        
                        //untuk jenis pembayaran
                        if(data.jenis_bayar != 'online'){
                            var jenis_bayar = 'Manual';
                        }else{
                            var jenis_bayar = 'Midtrans';
                        }

                        var base_url = '<?php echo base_url('uploads/transaksi/thumbnails'); ?>/'+data.bukti_pembayaran;
                        $("#idtransaksi").val(data.idtransaksi);
                        $("#isiKonten").html(`<div class="modal-header">
                    <h5 class="modal-title" id="validasi_transaksiLabel">Validasi transaksi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Paket <b>${data.nama_paket}</b></label>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="idtransaksi" value="${data.idtransaksi}"  class="form-control">
                        <input type="hidden" name="idsiswa" value="${data.idsiswa}"  class="form-control">
                        <input type="hidden" name="nama_siswa" value="${data.nama_siswa}"  class="form-control">
                        <input type="hidden" name="email" value="${data.email}"  class="form-control">
                        <input type="hidden" name="bulan" value="${data.jumlah_bulan}"  class="form-control">
                        <input type="hidden" name="bukti_pembayaran" value="${data.bukti_pembayaran}" " class="form-control">
                        <label>Nama Peserta <b>${data.nama_siswa}</b></label>
                    </div>
                    <div class="form-group">
                        <label>ID Peserta <b>${data.idsiswa}</b></label>
                    </div>
                    <div class="form-group">
                        <label>ID Transaksi <b>${data.idtransaksi}</b></label>
                    </div>
                    <div class="form-group">
                        <label>Nominal <b>${numberFormat(data.nominal-diskon-diskon_voucher, 0, 0, '.')}</b></label>
                    </div>
                
                    <div class="form-group">
                        <label>Tanggal Bayar <b>${data.tgl_pembayaran}</b></label>
                    </div>
                    <div class="form-group">
                        <label>Metode pembayaran <b>${jenis_bayar}</b> </label>
                    </div>
                    <div class="form-group">
                    ${pilihstatus}
                    </div>
                    <div class="form-group">
                        <img class="zoom" src="${base_url}" alt="" width="100%">
                    </div>

                    
                    
                    
                </div>
                <div class="modal-footer">
                    <button type="reset" value="reset" class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancel</button>
                </div>`);
                    });
                }
            });
        });
        // END transaksi
    })

    numberFormat = (number, decimals, dec_point, thousands_sep) => {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = typeof thousands_sep === 'undefined' ? ',' : thousands_sep,
            dec = typeof dec_point === 'undefined' ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    };
    
    $(document).on("click", "#hapus", function(e) {
         var link = $(this).attr("href");
         e.preventDefault();
         let result = confirm("Anda yakin ingin membatalkan transaksi ini?");
         if (result) {
             document.location.href = link;
         }
     });
     
     $(document).on("click", "#approve", function(e) {
         var link = $(this).attr("href");
         e.preventDefault();
         let result = confirm("Anda yakin ingin approve transaksi ini?");
         if (result) {
             document.location.href = link;
         }
     });
</script>


<?= $this->endSection(); ?>
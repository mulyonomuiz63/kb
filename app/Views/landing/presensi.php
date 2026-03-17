<?= $this->extend('landing/template'); ?>
<?= $this->section('content'); ?>
 <div class="section section-padding-02 mb-4" id="penilaian">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h3>Sistem Presensi Pelatihan KelasBrevet</h3>
                <h6><?= $jadwal->materi ?></h6>
                <span>Waktu Pelaksanaan <?= hari($jadwal->tgl_pelatihan).', '. tanggal_indo($jadwal->tgl_pelatihan).' ('.$jadwal->mulai.'-'. $jadwal->berakhir .' WIB)' ?></span>
            </div>
            <div class="col-12 mt-4 card call-to-action-wrapper rounded table-responsive-sm">
                <form action="<?= base_url('landing/presensi_'); ?>" method="post" class="text-left">
                  <div class="modal-body">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                    <div class="form">
                        <div class="">
                            <label for="recipient-name" class="col-form-label">Email Terdaftar</label>
                            <input type="email" name="email" class="form-control py-1 px-1 border-1 fs-6">
                            <input type="hidden" name="idjadwalpelatihan" value="<?= encrypt_url($jadwal->id) ?>">
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
</div>

<?= $this->endSection(); ?>
<?= $this->extend('template/app'); ?>
<?= $this->section('styles'); ?>
<style>
    /* Tambahan CSS khusus modal */
    #tambah_kuota .bg-gradient-primary {
        background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%) !important;
    }
    #tambah_kuota .input-group-text { border-radius: 8px 0 0 8px; }
    #tambah_kuota .form-control { border-radius: 0 8px 8px 0; height: 45px; transition: all 0.2s; }
    #tambah_kuota .form-control:focus { background-color: #fff !important; border-color: #5e72e4; box-shadow: none; }
    #tambah_kuota .uppercase-label { letter-spacing: 0.5px; text-transform: uppercase; font-size: 11px; color: #8898aa !important; }
    .text-nowrap { white-space: nowrap; }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">
            <div class="widget shadow p-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="widget-heading d-flex justify-content-between mb-4">
                            <h5>List Ujian</h5>
                        </div>
                        <div class="table-responsive">
                            <table id="tableUjian" data-idsiswa="<?= $idsiswa ?>" class="table table-bordered table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nama Ujian</th>
                                        <th>Kelas</th>
                                        <th>Kuota</th>
                                        <th>Durasi</th>
                                        <th>Nilai</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tambah_kuota" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="tambah_kuotaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <form action="<?= base_url('sw-admin/siswa/updateKuota'); ?>" method="POST" id="formUpdateKuota" class="w-100">
            <?= csrf_field(); ?>
            <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
                <div class="modal-header bg-gradient-primary text-white border-0">
                    <h5 class="modal-title font-weight-bold" id="tambah_kuotaLabel">
                        <i class="fas fa-plus-circle mr-2"></i>Tambah Kuota
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="id_ujian" id="modal_id_ujian">
                    <input type="hidden" name="id_siswa" id="modal_id_siswa">
                    <div class="form-group mb-0">
                        <label for="modal_kuota" class="text-dark small font-weight-bold uppercase-label">Jumlah Kuota Ujian</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0">
                                    <i class="fas fa-ticket-alt text-primary"></i>
                                </span>
                            </div>
                            <input type="number" name="kuota" class="form-control border-left-0 bg-light font-weight-bold" id="modal_kuota" value="0" min="1" required>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            <i class="fas fa-info-circle mr-1"></i> Masukkan jumlah kuota tambahan.
                        </small>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light p-3">
                    <button type="button" class="btn btn-link text-muted font-weight-bold text-decoration-none" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;" id="btnSimpanKuota">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // 1. Inisialisasi DataTable
        const idSiswaEnc = $('#tableUjian').data('idsiswa');
        var table = $('#tableUjian').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= base_url('sw-admin/siswa/getDataUjian') ?>",
                type: "POST",
                data: function(d) {
                    d.id_siswa = idSiswaEnc;
                    d[csrfName] = csrfHash;
                },
                dataSrc: function(json) {
                    if (json.csrf_hash) {
                        csrfHash = json.csrf_hash; // UPDATE token untuk request berikutnya
                        $('input[name="' + csrfName + '"]').val(csrfHash);
                    }
                    return json.data;
                },
            },
            columns: [
                { data: "nama_ujian" },
                { data: "nama_kelas" },
                { data: "kuota_html" },
                { data: "durasi_menit" },
                { data: "nilai" },
                { data: "status_lulus" },
                { 
                    data: "aksi",
                    className: "text-center text-nowrap",
                    orderable: false
                }
            ],
            language: {
                search: "Cari Ujian:",
                lengthMenu: "Tampilkan _MENU_ data",
            }
        });

        // 2. Klik Tombol Edit Kuota (Munculkan Modal)
        $(document).on('click', '.edit_kuota', function() {
            const idSiswa = $(this).data('idsiswa');
            const idUjian = $(this).data('idujian');
            const kuota   = $(this).data('kuota');

            $('#modal_id_siswa').val(idSiswa);
            $('#modal_id_ujian').val(idUjian);
            $('#modal_kuota').val(kuota);

            $('#tambah_kuota').modal('show');
        });

        // 3. Proses Submit Update Kuota (AJAX Reload Table)
        $('#formUpdateKuota').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const submitBtn = $('#btnSimpanKuota');

            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');

            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                dataType: "JSON",
                success: function(response) {
                    // Update CSRF token di form untuk request berikutnya
                    if (response.csrf_token) {
                        $('input[name="<?= csrf_token() ?>"]').val(response.csrf_token);
                    }

                    if (response.status === 'success') {
                        $('#tambah_kuota').modal('hide');
                        Swals.toast(response.message, 'success');
                        
                        // REFRESH DATATABLE TANPA RELOAD PAGE
                        table.ajax.reload(null, false); 
                    } else {
                        Swals.alert('Gagal!', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    Swals.alert('Error', 'Terjadi kesalahan sistem.', 'error');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan');
                }
            });
        });

        // 5. Accessibility & Focus Fix
        $('#tambah_kuota').on('shown.bs.modal', function() {
            $('#modal_kuota').focus().select();
            $(this).removeAttr('aria-hidden');
        });
    });
</script>
<?= $this->endSection(); ?>
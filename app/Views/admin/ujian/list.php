<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="card card-flush">
                
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-ujian-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Ujian..." />
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="tableUjian" data-idsiswa="<?= $idsiswa ?>" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap" style="width:100%">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Nama Ujian</th>
                                    <th class="min-w-125px">Kelas</th>
                                    <th class="min-w-100px text-center">Kuota</th>
                                    <th class="min-w-100px text-center">Durasi</th>
                                    <th class="min-w-100px text-center">Nilai</th>
                                    <th class="min-w-125px text-center">Status</th>
                                    <th class="min-w-100px text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600"></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="tambah_kuota" data-focus="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-400px">
        <form action="<?= base_url('sw-admin/siswa/updateKuota'); ?>" method="POST" id="formUpdateKuota" class="w-100">
            <?= csrf_field(); ?>
            <div class="modal-content rounded">
                
                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h2 class="fw-bold">Tambah Kuota</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>

                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-15">
                    <input type="hidden" name="id_ujian" id="modal_id_ujian">
                    <input type="hidden" name="id_siswa" id="modal_id_siswa">
                    
                    <div class="d-flex flex-column mb-8 fv-row">
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                            <span class="required">Jumlah Kuota Ujian</span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-toggle="tooltip" title="Masukkan jumlah kuota tambahan untuk ujian ini"></i>
                        </label>
                        
                        <div class="input-group input-group-solid">
                            <span class="input-group-text">
                                <i class="ki-duotone ki-ticket fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                            <input type="number" name="kuota" id="modal_kuota" class="form-control form-control-solid fw-bold" value="0" min="1" required />
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="button" class="btn btn-light me-3" data-dismiss="modal">Batal</button>
                        <button type="submit" id="btnSimpanKuota" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Simpan
                        </button>
                    </div>
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
                url: "<?= base_url('sw-admin/siswa/get-data-ujian') ?>",
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
                { data: "nama_ujian", className: "text-gray-800 fw-bold" },
                { data: "nama_kelas" },
                { data: "kuota_html", className: "text-center" },
                { data: "durasi_menit", className: "text-center" },
                { data: "nilai", className: "text-center fw-bold" },
                { data: "status_lulus", className: "text-center" },
                { 
                    data: "aksi",
                    className: "text-center text-nowrap",
                    orderable: false
                }
            ]
        });

        // Search Custom DataTables
        $('[data-kt-ujian-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
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
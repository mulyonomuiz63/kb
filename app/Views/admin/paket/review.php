<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="card card-flush shadow-sm border-0">
                
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title flex-column align-items-start">
                        <span class="card-label fw-bold text-gray-800">Daftar Review Peserta</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Kelola feedback dan rating dari peserta ujian.</span>
                    </div>
                    
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        
                        <!-- Filter Dropdown -->
                        <div class="w-100 mw-200px">
                            <select id="filter_komentar" class="form-select form-select-solid" data-hide-search="true">
                                <option value="all">Semua Data</option>
                                <!-- TAMBAHKAN KATA 'selected' DI BAWAH INI -->
                                <option value="with_comment" selected>Ada Komentar</option>
                                <option value="without_comment">Tanpa Komentar</option>
                            </select>
                        </div>
                        <!-- END Filter Dropdown -->

                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-review-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Review..." />
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatable-list" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 border-bottom border-gray-200">
                                    <th class="min-w-200px">Peserta</th>
                                    <th class="min-w-300px">Komentar</th>
                                    <th class="text-center min-w-100px">Rating</th>
                                    <th class="text-center min-w-100px">Status</th>
                                    <th class="text-center min-w-100px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                <?php foreach ($review as $s) : ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-element-11 fs-4 text-muted me-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                </i>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold"><?= $s->nama_siswa; ?></span>
                                                    <span class="text-muted fs-7 fw-normal mt-1">
                                                        Direview: <?= date('d M Y, H:i', strtotime($s->created_at)); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-wrap text-muted">
                                            <?= $s->komentar; ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-light-warning fw-bold px-3 py-2 fs-6">
                                                <i class="ki-duotone ki-star fs-5 text-warning me-1"><span class="path1"></span><span class="path2"></span></i>
                                                <?= $s->rating ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?= $s->status == 'A'
                                                ? '<span class="badge badge-light-success fw-bold px-3 py-2">Aktif</span>'
                                                : '<span class="badge badge-light-danger fw-bold px-3 py-2">Tidak Aktif</span>'; ?>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-icon btn-light-primary btn-sm edit-review" data-review="<?= encrypt_url($s->id_review); ?>" data-bs-toggle="tooltip" title="Edit Review">
                                                <i class="ki-duotone ki-pencil fs-3"><span class="path1"></span><span class="path2"></span></i>
                                            </button>
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

<div class="modal fade" id="edit_review" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content rounded border-0">
            <form action="<?= base_url('sw-admin/paket/update-review'); ?>" method="POST" class="form">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf-token" />
                <input type="hidden" name="id_review" id="id_review">
                
                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h2 class="fw-bold">Edit Review Peserta</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                
                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">
                    
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Komentar</label>
                        <textarea name="komentar" id="komentar" class="form-control form-control-solid" rows="4"></textarea>
                    </div>
                    
                    <div class="row g-5">
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Rating (1-5)</label>
                            <input type="number" name="rating" id="rating" min="1" max="5" class="form-control form-control-solid" required>
                        </div>
                        
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Status Tampil</label>
                            <select name="status" id="status" class="form-select form-select-solid" data-control="select2" data-hide-search="true" required>
                                <option value="A">Aktif / Tampil</option>
                                <option value="T">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                </div>
                
                <div class="modal-footer border-0 p-5 p-lg-10 pt-0">
                    <button type="button" class="btn btn-light me-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">
                        <i class="ki-duotone ki-save-2 fs-3 me-1"><span class="path1"></span><span class="path2"></span></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        
        // 1. Inisialisasi Select2 HANYA untuk form status di dalam modal
        $('#status').select2({ 
            dropdownParent: $('#edit_review'),
            minimumResultsForSearch: -1 // Sembunyikan kotak search
        });

        // 2. Inisialisasi Select2 untuk Filter Dropdown di luar modal
        $('#filter_komentar').select2({
            minimumResultsForSearch: -1 // Sembunyikan kotak search
        });

        // 3. Custom Filter Logic DataTables untuk Filter Komentar
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            var filterValue = $('#filter_komentar').val();
            
            // Ambil teks dari kolom kedua (Index 1) yaitu kolom 'Komentar'
            // .trim() digunakan untuk menghapus spasi/enter kosong
            var komentarText = data[1].trim(); 

            if (filterValue === 'all') {
                return true;
            } else if (filterValue === 'with_comment') {
                // Tampilkan jika teks komentar tidak kosong
                return komentarText !== '' && komentarText !== '-'; 
            } else if (filterValue === 'without_comment') {
                // Tampilkan jika teks komentar kosong
                return komentarText === '' || komentarText === '-'; 
            }
            
            return true;
        });

        // 4. Inisialisasi DataTables dg custom DOM Metronic
        var table = $('#datatable-list').DataTable({
            "ordering": false,
            "drawCallback": function(settings) {
                // Re-init Metronic menu dropdown pada saat pindah halaman paginasi
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            }
        });

        // 5. Trigger filter ketika Dropdown Select diubah
        // Untuk select2, pastikan menangkap event 'change' 
        $('#filter_komentar').on('change', function() {
            table.draw();
        });

        // 6. Fitur Pencarian Custom yang menyatu dengan UI Card Header Metronic
        $('[data-kt-review-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        // 7. Event delegation untuk AJAX Edit (LOGIKA ASLI)
        $(document).on('click', '.edit-review', function() {
            const id_review = $(this).data('review');
            const csrfName = "<?= csrf_token() ?>";
            const csrfHash = $('.csrf-token').val(); // Ambil hash terbaru

            // Feedback visual saat loading
            const btn = $(this);
            const originalIcon = '<i class="ki-duotone ki-pencil fs-3"><span class="path1"></span><span class="path2"></span></i>';
            btn.html('<span class="spinner-border spinner-border-sm"></span>').attr('disabled', true);

            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-admin/paket/edit-review') ?>",
                data: {
                    id_review: id_review,
                    [csrfName]: csrfHash // Kirim token
                },
                dataType: 'JSON',
                success: function(data) {
                    // Update token CSRF di halaman
                    $('.csrf-token').val(data[csrfName]);

                    // Isi form
                    $("#id_review").val(data.id_review);
                    $("#komentar").val(data.komentar);
                    $("#rating").val(data.rating);
                    
                    // Update Select2 UI di dalam modal
                    $("#status").val(data.status).trigger('change');

                    // Reset Button & Tampilkan Modal
                    btn.html(originalIcon).attr('disabled', false);
                    $('#edit_review').modal('show');
                },
                error: function(xhr) {
                    btn.html(originalIcon).attr('disabled', false);
                    alert("Gagal memuat data. Sesi mungkin berakhir, silakan refresh.");
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>
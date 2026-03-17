<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">
            <div class="widget shadow-sm bg-white rounded-lg border-0">
                <div class="widget-content p-4">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="font-weight-bold text-dark mb-0">Review Ujian</h5>
                            <p class="text-muted small">Kelola feedback dan rating dari peserta ujian.</p>
                        </div>
                        <a href="<?= base_url('sw-admin/paket'); ?>" class="btn btn-outline-primary btn-sm px-3">
                            <i class="bi bi-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable-list" class="table table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 text-uppercase small font-weight-bold" style="width: 20%;">Peserta</th>
                                    <th class="border-0 text-uppercase small font-weight-bold" style="width: 40%;">Komentar</th>
                                    <th class="border-0 text-uppercase small font-weight-bold text-center">Rating</th>
                                    <th class="border-0 text-uppercase small font-weight-bold text-center">Status</th>
                                    <th class="border-0 text-uppercase small font-weight-bold text-center">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($review as $s) : ?>
                                    <tr class="align-middle">
                                        <td class="font-weight-bold text-dark"><?= $s->nama_siswa; ?></td>
                                        <td class="text-wrap text-muted small"><?= $s->komentar; ?></td>
                                        <td class="text-center">
                                            <span class="badge badge-warning text-white px-2">
                                                <i class="bi bi-star-fill small mr-1"></i><?= $s->rating ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?= $s->status == 'A'
                                                ? '<span class="badge badge-subtle-success px-3">Aktif</span>'
                                                : '<span class="badge badge-subtle-danger px-3">Tidak Aktif</span>'; ?>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" 
                                                class="btn btn-sm btn-primary edit-review" 
                                                data-review="<?= encrypt_url($s->id_review); ?>">
                                                <i class="bi bi-pencil-square"></i>
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

<div class="modal fade" id="edit_review" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <form action="<?= base_url('sw-admin/paket/update-review'); ?>" method="POST">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf-token" />
                
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white">Edit Review Peserta</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="id_review" id="id_review">
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Komentar</label>
                        <textarea name="komentar" id="komentar" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Rating (1-5)</label>
                                <input type="number" name="rating" id="rating" min="1" max="5" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Status Tampil</label>
                                <select name="status" id="status" class="form-control selectpicker" required>
                                    <option value="A">Aktif / Tampil</option>
                                    <option value="T">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-link text-dark" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .badge-subtle-success { background-color: #e8fadf; color: #71dd37; border-radius: 4px; }
    .badge-subtle-danger { background-color: #ffe5e5; color: #ff3e1d; border-radius: 4px; }
    .table thead th { font-size: 11px; letter-spacing: 0.5px; color: #888; border-bottom: 1px solid #eee !important; }
    .widget { border-radius: 15px; }
</style>

<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#datatable-list').DataTable({
            "ordering": false
        });
        // Event delegation agar lebih stabil
        $(document).on('click', '.edit-review', function() {
            const id_review = $(this).data('review');
            const csrfName = "<?= csrf_token() ?>";
            const csrfHash = $('.csrf-token').val(); // Ambil hash terbaru

            // Feedback visual saat loading
            const btn = $(this);
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
                    // 1. Update token CSRF di halaman
                    $('.csrf-token').val(data[csrfName]);

                    // 2. Isi form
                    $("#id_review").val(data.id_review);
                    $("#komentar").val(data.komentar);
                    $("#rating").val(data.rating);
                    $("#status").val(data.status);

                    // 3. Reset Button & Tampilkan Modal
                    btn.html('<i class="bi bi-gear"></i>').attr('disabled', false);
                    $('#edit_review').modal('show');
                },
                error: function(xhr) {
                    btn.html('<i class="bi bi-gear"></i>').attr('disabled', false);
                    alert("Gagal memuat data. Sesi mungkin berakhir, silakan refresh.");
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>
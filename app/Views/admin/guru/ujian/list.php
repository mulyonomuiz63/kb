<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">
            <div class="widget shadow p-3 bg-white">
                <div class="table-responsive">
                    <table id="datatable-list" class="table text-left text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>Nama Ujian</th>
                                <th>Kelas</th>
                                <th>Status</th>
                                <th width="10%">Opsi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#datatable-list').DataTable({
            processing: true,
            serverSide: true,
            order: [[0, 'desc']], // Urutkan berdasarkan kolom pertama secara default
            ajax: {
                url: "<?= base_url('sw-admin/guru/ajaxUjianGuru'); ?>", // URL ke controller
                type: "POST",
                data: function(d) {
                    // Perbaikan: hapus titik setelah d
                    d.id_guru = "<?= $id_guru_enc ?>";
                    d['<?= csrf_token() ?>'] = "<?= csrf_hash() ?>";
                },
                dataSrc: function(json) {
                    // Update CSRF jika regenerasi aktif
                    if (json.token) {
                        $('input[name="<?= csrf_token() ?>"]').val(json.token);
                        // Jika Anda pakai meta tag global:
                        // $('meta[name="X-CSRF-TOKEN"]').attr('content', json.token);
                    }
                    return json.data;
                }
            },
            // Menggunakan "columns" lebih rapi untuk Server-side
            "columns": [
                { "data": "nama_ujian" },
                { "data": "nama_kelas" },
                { "data": "status" },
                { "data": "opsi" }
            ],
            "columnDefs": [{
                "targets": [2, 3], // Kolom status dan opsi
                "orderable": false, // Matikan fitur sorting untuk kolom ini
                "searchable": false // Matikan fitur pencarian untuk kolom ini
            }],
            "language": {
                "paginate": {
                    "previous": "<",
                    "next": ">"
                },
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data",
            }
        });
    });
</script>
<?= $this->endSection(); ?>
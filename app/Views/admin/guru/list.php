<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">
            <div class="widget shadow p-3 bg-white">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="widget-heading d-flex justify-content-between">
                            <a href="<?= base_url('sw-admin/guru/create') ?>" class="btn btn-primary">Tambah Instruktur</a>
                        </div>
                        <div class="table-responsive mt-4">
                            <table id="datatable-list" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Mapel</th>
                                        <th>Soal</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        var table = $('#datatable-list').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= base_url('sw-admin/guru/datatable') ?>", // Arahkan ke method controller di atas
                "type": "POST",
                "data": function(d) {
                    // Kirim CSRF Token
                    d.<?= csrf_token() ?> = "<?= csrf_hash() ?>";
                },
                "dataSrc": function(json) {
                    // Update hash CSRF setiap kali table reload agar tidak expired
                    $('input[name="<?= csrf_token() ?>"]').val(json.csrf_hash);
                    return json.data;
                }
            },
            "columns": [{
                    "data": "nama"
                },
                {
                    "data": "email"
                },
                {
                    "data": "mapel"
                },
                {
                    "data": "soal"
                },
                {
                    "data": "opsi"
                }
            ],
            // Total ada 5 objek, sama dengan 5 <th> di atas.
            "language": {
                "search": "Cari Siswa:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "processing": '<div class="spinner-border text-primary" role="status"></div>'
            }
        });
    });
</script>
<?= $this->endSection(); ?>
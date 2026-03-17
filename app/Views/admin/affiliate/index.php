<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">

            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <!-- HEADER -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="mb-1 font-weight-bold">Data Affiliate</h4>
                            <p class="text-muted mb-0 small">
                                Daftar peserta yang terdaftar sebagai affiliate
                            </p>
                        </div>
                    </div>

                    <!-- TABLE -->
                    <div class="table-responsive">
                        <table id="datatables-list" class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Peserta</th>
                                    <th>Tgl Daftar</th>
                                    <th  class="text-center">Komisi</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" width="120">Aksi</th>
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


<!-- STYLE TAMBAHAN -->
<style>
.table td {
    vertical-align: middle;
}
.badge {
    font-size: 90%;
}
</style>

<!-- SCRIPT -->
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>

<script>
$(function () {
    let table = $('#datatables-list').DataTable({
        processing: true,
        serverSide: true,
        ordering: false,
        ajax: {
            url: "<?= base_url('sw-admin/affiliate/datatables') ?>",
            type: "POST",
            data: function (d) {
                d[csrfName] = csrfHash;
            },
            dataSrc: function (json) {

                // 🔥 Update CSRF setiap response
                csrfHash = json[csrfName];

                return json.data;
            },
            error: function (xhr, error, thrown) {
                console.error('Error fetching data:', error);
                console.log('Response:', xhr.responseText);
            }
        },
        columnDefs: [
            { targets: 2, className: "text-right" },
            { targets: 3, className: "text-center" },
            { targets: 4, className: "text-center" }
        ]
    });

});
$(function () {

    // Tooltip Bootstrap 4
    $('[data-toggle="tooltip"]').tooltip();
});
</script>

<?= $this->endSection(); ?>

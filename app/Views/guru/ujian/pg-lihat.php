<?= $this->extend('template/app'); ?>
<?= $this->section('styles'); ?>

<style>
    /* ====== JAWABAN VERTIKAL ====== */

    .answer-box {
        cursor: pointer;
        margin-bottom: 15px;
    }

    .answer-content {
        border: 1px solid #dee2e6;
        padding: 14px 18px;
        border-radius: 8px;
        transition: 0.2s;
        background: #fff;
    }

    .answer-box:hover .answer-content {
        background: #f8f9fa;
        border-color: #007bff;
    }

    .answer-box.active .answer-content {
        background: #e7f1ff;
        border-color: #007bff;
    }

    /* ====== NAVIGATION ====== */

    .nav-number {
        width: 45px;
        height: 45px;
        margin: 4px;
        font-weight: 600;

        display: flex;
        align-items: center;
        justify-content: center;

        padding: 0;
        line-height: 1;
        white-space: nowrap;
    }

    .nav-number.active {
        background: #007bff !important;
        color: #fff !important;
    }

    .nav-number.answered {
        background: #28a745 !important;
        color: #fff !important;
    }

    /* Tambahan agar hover effect terasa lebih premium */
    .list-group-item {
        transition: background-color 0.2s ease;
        border-left: 3px solid transparent;
    }

    .list-group-item:hover {
        background-color: #f8fbff;
        border-left: 3px solid #4361ee;
    }

    .avatar-container img {
        transition: transform 0.2s ease;
    }

    .list-group-item:hover .avatar-container img {
        transform: scale(1.1);
    }

    .dropdown-item i {
        width: 20px;
    }
</style>

<?= $this->endSection() ?>
<?= $this->section('content'); ?>
<!--  BEGIN CONTENT AREA  -->
<div class="container-fluid">
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex align-items-center">
                    <h5 class="mb-0 text-dark font-weight-bold">
                        <i class="fas fa-users mr-2 text-primary"></i> Peserta Selesai
                    </h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tableSiswa" class="table table-hover border-0 w-100">
                            <thead>
                                <tr>
                                    <th>Peserta</th>
                                    <th class="text-center">Statistik</th>
                                    <th class="text-center">Aksi</th>
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

<!--  END CONTENT AREA  -->
<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#tableSiswa').DataTable({
            "processing": true,
            "serverSide": true,
            "ordering": false,
            "ajax": {
                "url": "<?= base_url('sw-guru/ujian/ajaxSiswaUjian/' . $kode_ujian_encrypt) ?>",
                "type": "POST",
                "data": function(d) {
                    d[csrfName] = csrfHash;
                },
                "dataSrc": function(json) {
                    // Update input CSRF dengan token baru yang dikirim oleh Controller
                    csrfHash = json[csrfName];

                    return json.data;
                },
                "error": function(xhr, error, thrown) {
                    console.log("=== DEBUG ERROR BACKEND ===");
                    console.log("Response Text: ", xhr.responseText);
                }
            },
            "language": {
                "search": "Cari Peserta:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "paginate": {
                    "previous": "<",
                    "next": ">"
                },
            },
            "dom": '<"d-flex justify-content-between align-items-center mb-3"lf>rt<"d-flex justify-content-between align-items-center mt-3"ip>'
        });
    });
</script>
<?= $this->endSection(); ?>
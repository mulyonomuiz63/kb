<?= $this->extend('template/app'); ?>

<?= $this->section('styles'); ?>
<!-- Tambahkan Meta Tag untuk CSRF Token -->
<meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card card-flush shadow-sm border-0">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title flex-column align-items-start">
                        <span class="card-label fw-bold text-gray-800">Migrasi Materi Siswa</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Sinkronisasi data transaksi tervalidasi ke tabel mapel_siswa.</span>
                    </div>

                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <!-- Progress info untuk Batch Migration -->
                        <div class="d-none align-items-center" id="batch-progress-container">
                            <span class="spinner-border spinner-border-sm text-primary me-2" role="status"></span>
                            <span class="text-muted fw-bold" id="batch-status-text">Memproses migrasi...</span>
                        </div>

                        <button type="button" class="btn btn-success fw-bold" id="btn-migrate-all">
                            <i class="ki-duotone ki-arrows-circle fs-2">
                                <span class="path1"></span><span class="path2"></span>
                            </i> Migrasi Semua Data
                        </button>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatables-list" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap w-100">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th>No</th>
                                    <th>ID Siswa</th>
                                    <th>Mapel (Materi)</th>
                                    <th>Status Migrasi</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                <?php $no = 1;
                                foreach ($dataMateri as $row): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>Siswa ID: <?= $row->nama_siswa ?></td>
                                        <td><?= $row->nama_mapel ?> (ID: <?= $row->idmapel ?>)</td>
                                        <td>
                                            <?php if ($row->idmapelsiswa): ?>
                                                <span class="badge badge-light-success fs-7 fw-bold">Sudah Migrasi</span>
                                            <?php else: ?>
                                                <span class="badge badge-light-warning fs-7 fw-bold" id="status-<?= $row->idsiswa ?>-<?= $row->idmapel ?>">Belum Migrasi</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <?php if (!$row->idmapelsiswa): ?>
                                                <button type="button" class="btn btn-sm btn-light-primary btn-migrate-single"
                                                    data-idsiswa="<?= $row->idsiswa ?>"
                                                    data-idmapel="<?= $row->idmapel ?>"
                                                    id="btn-<?= $row->idsiswa ?>-<?= $row->idmapel ?>">
                                                    Migrasi
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-light-secondary" disabled>Selesai</button>
                                            <?php endif; ?>
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
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Setup CSRF Global via Headers
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="<?= csrf_token() ?>"]').attr('content')
            }
        });

        // Datatable Init
        $('#datatables-list').DataTable({
            "pageLength": 25,
            "language": {
                "emptyTable": "Tidak ada data tersedia"
            }
        });

        // ==========================================
        // 1. MIGRASI SINGLE (PER SISWA)
        // ==========================================
        $(document).on('click', '.btn-migrate-single', function() {
            let btn = $(this);
            let idsiswa = btn.data('idsiswa');
            let idmapel = btn.data('idmapel');

            // Ambil token terbaru dari meta tag
            let currentCsrf = $('meta[name="<?= csrf_token() ?>"]').attr('content');

            btn.attr('data-kt-indicator', 'on').prop('disabled', true);

            $.ajax({
                url: "<?= base_url('sw-admin/materi/migrasi-single') ?>",
                type: "POST",
                data: {
                    idsiswa: idsiswa,
                    idmapel: idmapel,
                    '<?= csrf_token() ?>': currentCsrf // Kirim token di payload data
                },
                dataType: "JSON",
                success: function(response) {
                    // Update CSRF meta tag untuk request selanjutnya
                    if (response.csrf) {
                        $('meta[name="<?= csrf_token() ?>"]').attr('content', response.csrf);
                    }

                    if (response.status === 'success') {
                        btn.removeAttr('data-kt-indicator');
                        btn.removeClass('btn-light-primary btn-migrate-single').addClass('btn-light-secondary').text('Selesai');

                        let statusBadge = $('#status-' + idsiswa + '-' + idmapel);
                        if (statusBadge.length) {
                            statusBadge.removeClass('badge-light-warning badge-light-danger').addClass('badge-light-success').text('Sudah Migrasi');
                        }
                        toastr.success(response.message);
                    } else {
                        btn.removeAttr('data-kt-indicator').prop('disabled', false);
                        toastr.error(response.message);
                    }
                },
                error: function(error) {
                    btn.removeAttr('data-kt-indicator').prop('disabled', false);
                    toastr.error('Terjadi kesalahan server.');
                }
            });
        });

        // ==========================================
        // 2. MIGRASI BATCH (MENYELURUH & PERSENTASE)
        // ==========================================
        let totalData = 0;

        $('#btn-migrate-all').click(function() {
            Swal.fire({
                title: "Migrasi Semua Data?",
                text: "Proses ini akan berjalan di latar belakang secara bertahap agar tidak membebani server.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Mulai!",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function(result) {
                if (result.isConfirmed) {
                    $('#btn-migrate-all').prop('disabled', true);
                    $('#batch-progress-container').removeClass('d-none').addClass('d-flex');
                    $('#batch-status-text').text("Menghitung data...");
                    totalData = 0;

                    processBatchMigration();
                }
            });
        });

        function processBatchMigration() {
            // Ambil token terbaru sesaat sebelum request dikirim
            let latestCsrfToken = $('meta[name="<?= csrf_token() ?>"]').attr('content');

            $.ajax({
                url: "<?= base_url('sw-admin/materi/migrasi-batch') ?>",
                type: "POST",
                dataType: "JSON",
                data: {
                    '<?= csrf_token() ?>': latestCsrfToken // Selipkan token baru di sini
                },
                success: function(response) {
                    // Segera perbarui meta tag dengan token baru dari response server
                    if (response.csrf) {
                        $('meta[name="<?= csrf_token() ?>"]').attr('content', response.csrf);
                    }

                    if (response.status === 'processing') {
                        if (totalData === 0) {
                            totalData = response.total_awal;
                        }

                        let sisa = response.sisa;
                        let sudahDiproses = totalData - sisa;
                        let persentase = totalData > 0 ? Math.round((sudahDiproses / totalData) * 100) : 0;

                        if (persentase > 100) persentase = 100;
                        if (persentase < 0) persentase = 0;

                        $('#batch-status-text').html(`Memproses... <b class="text-primary">${persentase}%</b> (Sisa: ${sisa} data)`);

                        // Lanjut eksekusi sisa data
                        setTimeout(processBatchMigration, 500);
                    } else if (response.status === 'done') {
                        $('#batch-progress-container').removeClass('d-flex').addClass('d-none');
                        Swal.fire({
                            title: "Berhasil!",
                            text: "Semua data berhasil dimigrasi 100%.",
                            icon: "success",
                            confirmButtonText: "Ok, Mengerti",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function() {
                            location.reload();
                        });
                    } else {
                        $('#btn-migrate-all').prop('disabled', false);
                        $('#batch-progress-container').removeClass('d-flex').addClass('d-none');
                        Swal.fire("Peringatan!", response.message, "warning");
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);  
                    $('#btn-migrate-all').prop('disabled', false);
                    $('#batch-progress-container').removeClass('d-flex').addClass('d-none');
                    Swal.fire("Error Server!", "Terjadi kesalahan keamanan atau timeout. Silakan klik tombol migrasi kembali untuk melanjutkan sisa data.", "error");
                }
            });
        }
    });
</script>
<?= $this->endSection(); ?>
<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-md-12 mb-4">
            <div class="widget shadow p-4 bg-primary text-white br-10">
                <div class="d-flex align-items-center">
                    <div class="icon-box mr-3">
                        <i class="bi bi-person-badge" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 text-white">Pengaturan Relasi: <?= $guru->nama_guru ?></h5>
                        <small class="opacity-75">Kelola akses kelas dan mata pelajaran pengajar secara real-time.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 layout-spacing">
            <div class="widget shadow p-3 br-10">
                <div class="widget-heading border-bottom pb-2 mb-3">
                    <h5 class="text-primary font-weight-bold"><i class="bi bi-door-open mr-2"></i>Relasi Kelas</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Kelas</th>
                                <th class="text-center">Akses Mengajar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($kelas as $kel) : ?>
                                <tr>
                                    <td class="align-middle font-weight-bold text-dark"><?= $kel->nama_kelas; ?></td>
                                    <td class="text-center align-middle">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input check-kelas custom-switch-lg"
                                                type="checkbox"
                                                role="switch"
                                                id="kelas_<?= $kel->id_kelas; ?>"
                                                <?= check_kelas(encrypt_url($guru->id_guru), $kel->id_kelas); ?>
                                                data-id_guru="<?= encrypt_url($guru->id_guru); ?>"
                                                data-id_kelas="<?= $kel->id_kelas; ?>"
                                                style="cursor: pointer; width: 3em; height: 1.5em;">
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6 layout-spacing">
            <div class="widget shadow p-3 br-10">
                <div class="widget-heading border-bottom pb-2 mb-3">
                    <h5 class="text-success font-weight-bold"><i class="bi bi-book mr-2"></i>Relasi Mapel</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mata Pelajaran</th>
                                <th class="text-center">Akses Mengajar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mapel as $m) : ?>
                                <tr>
                                    <td class="align-middle font-weight-bold text-dark"><?= $m->nama_mapel; ?></td>
                                    <td class="text-center align-middle">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input check-mapel custom-switch-lg"
                                                type="checkbox"
                                                role="switch"
                                                id="mapel_<?= $m->id_mapel; ?>"
                                                <?= check_mapel(encrypt_url($guru->id_guru), $m->id_mapel); ?>
                                                data-id_guru="<?= encrypt_url($guru->id_guru); ?>"
                                                data-id_mapel="<?= $m->id_mapel; ?>"
                                                style="cursor: pointer; width: 3em; height: 1.5em;">
                                        </div>
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

<style>
    .br-10 {
        border-radius: 10px;
    }

    .widget {
        border: none;
    }

    .table thead th {
        border: none;
        font-size: 13px;
        text-transform: uppercase;
        color: #888ea8;
    }

    .table tbody td {
        border-top: 1px solid #f1f2f3;
        vertical-align: middle;
    }
</style>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Simpan hash awal ke dalam variabel yang bisa diupdate
        let currentCsrfHash = "<?= csrf_hash() ?>";
        const csrfTokenName = "<?= csrf_token() ?>";

        function updateRelasi(url, dataPayload) {
            // Selalu gunakan hash terbaru
            dataPayload[csrfTokenName] = currentCsrfHash;

            $.ajax({
                type: 'POST',
                url: url,
                data: dataPayload,
                dataType: 'JSON',
                success: function(response) {
                    // UPDATE HASH: Ambil token baru dari response server
                    if (response.token) {
                        currentCsrfHash = response.token;
                    }

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });

                    Toast.fire({
                        icon: 'success',
                        title: response.message || 'Data berhasil diperbarui'
                    });
                },
                error: function(xhr) {
                    // Jika Forbidden (403), kemungkinan token mismatch, maka reload
                    if (xhr.status === 403) {
                        Swal.fire('Sesi Berakhir', 'Halaman akan dimuat ulang untuk keamanan.', 'info')
                            .then(() => location.reload());
                    }
                    console.error(xhr.responseText);
                }
            });
        }

        $('.check-kelas').on('change', function() {
            updateRelasi("<?= base_url('sw-admin/relasi/guru-kelas') ?>", {
                id_guru: $(this).data('id_guru'),
                id_kelas: $(this).data('id_kelas')
            });
        });

        $('.check-mapel').on('change', function() {
            updateRelasi("<?= base_url('sw-admin/relasi/guru-mapel') ?>", {
                id_guru: $(this).data('id_guru'),
                id_mapel: $(this).data('id_mapel')
            });
        });
    });
</script>
<?= $this->endSection(); ?>
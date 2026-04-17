<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="card border-0 bg-primary shadow-sm mb-7">
                <div class="card-body d-flex align-items-center p-7">
                    <div class="symbol symbol-50px me-5">
                        <span class="symbol-label bg-white bg-opacity-10">
                            <i class="ki-duotone ki-user-square fs-2x text-white"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        </span>
                    </div>
                    <div class="d-flex flex-column">
                        <h2 class="text-white fw-bold mb-1">Pengaturan Relasi: <?= esc($guru->nama_guru) ?></h2>
                        <span class="text-white opacity-75 fs-6">Kelola akses kelas dan mata pelajaran pengajar secara real-time.</span>
                    </div>
                </div>
            </div>

            <div class="row g-5 g-xl-8">
                
                <div class="col-lg-6">
                    <div class="card card-flush shadow-sm h-100">
                        <div class="card-header pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-800">
                                    <i class="ki-duotone ki-abstract-26 text-primary fs-2 me-2"><span class="path1"></span><span class="path2"></span></i> Relasi Kelas
                                </span>
                            </h3>
                        </div>
                        
                        <div class="card-body pt-5">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-4">
                                    <thead>
                                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                            <th class="min-w-150px">Nama Kelas</th>
                                            <th class="text-center min-w-100px">Akses Mengajar</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                        <?php foreach ($kelas as $kel) : ?>
                                            <tr>
                                                <td class="text-gray-800 fw-bold"><?= $kel->nama_kelas; ?></td>
                                                <td class="text-center">
                                                    <div class="form-check form-switch form-check-custom form-check-solid form-check-success justify-content-center">
                                                        <input class="form-check-input check-kelas h-30px w-50px cursor-pointer" 
                                                               type="checkbox" 
                                                               id="kelas_<?= $kel->id_kelas; ?>"
                                                               <?= check_kelas(encrypt_url($guru->id_guru), $kel->id_kelas); ?>
                                                               data-id_guru="<?= encrypt_url($guru->id_guru); ?>"
                                                               data-id_kelas="<?= $kel->id_kelas; ?>" />
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

                <div class="col-lg-6">
                    <div class="card card-flush shadow-sm h-100">
                        <div class="card-header pt-7">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-800">
                                    <i class="ki-duotone ki-book-open text-success fs-2 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i> Relasi Mapel
                                </span>
                            </h3>
                        </div>
                        
                        <div class="card-body pt-5">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-4">
                                    <thead>
                                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                            <th class="min-w-150px">Mata Pelajaran</th>
                                            <th class="text-center min-w-100px">Akses Mengajar</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                        <?php foreach ($mapel as $m) : ?>
                                            <tr>
                                                <td class="text-gray-800 fw-bold"><?= $m->nama_mapel; ?></td>
                                                <td class="text-center">
                                                    <div class="form-check form-switch form-check-custom form-check-solid form-check-success justify-content-center">
                                                        <input class="form-check-input check-mapel h-30px w-50px cursor-pointer" 
                                                               type="checkbox" 
                                                               id="mapel_<?= $m->id_mapel; ?>"
                                                               <?= check_mapel(encrypt_url($guru->id_guru), $m->id_mapel); ?>
                                                               data-id_guru="<?= encrypt_url($guru->id_guru); ?>"
                                                               data-id_mapel="<?= $m->id_mapel; ?>" />
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

        </div>
    </div>
</div>
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

        // Trigger update relasi kelas
        $('.check-kelas').on('change', function() {
            updateRelasi("<?= base_url('sw-admin/relasi/guru-kelas') ?>", {
                id_guru: $(this).data('id_guru'),
                id_kelas: $(this).data('id_kelas')
            });
        });

        // Trigger update relasi mapel
        $('.check-mapel').on('change', function() {
            updateRelasi("<?= base_url('sw-admin/relasi/guru-mapel') ?>", {
                id_guru: $(this).data('id_guru'),
                id_mapel: $(this).data('id_mapel')
            });
        });
    });
</script>
<?= $this->endSection(); ?>
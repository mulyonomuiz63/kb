<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <!-- Header Profil -->
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

            <!-- TAHAP 1: Pilih Kelas -->
            <div class="card card-flush shadow-sm mb-7">
                <div class="card-header bg-light-primary pt-7 pb-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800 fs-4">
                            <i class="ki-duotone ki-abstract-26 text-primary fs-2 me-2"><span class="path1"></span><span class="path2"></span></i>
                            Tahap 1: Relasi Kelas
                        </span>
                        <span class="text-gray-600 mt-2 fw-semibold fs-7">Centang kelas mana saja yang ditugaskan kepada pengajar ini.</span>
                    </h3>
                </div>
                <div class="card-body pt-5">
                    <div class="row g-4">
                        <?php foreach ($kelas as $kel) : ?>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="d-flex align-items-center justify-content-between p-4 border border-dashed border-gray-300 rounded bg-white hover-elevate-up transition">
                                    <span class="text-gray-800 fw-bold fs-6"><?= $kel->nama_kelas; ?></span>
                                    <div class="form-check form-switch form-check-custom form-check-success form-check-sm ms-2">
                                        <input class="form-check-input check-kelas h-25px w-40px cursor-pointer"
                                            type="checkbox"
                                            id="kelas_<?= $kel->id_kelas; ?>"
                                            <?= check_kelas(encrypt_url($guru->id_guru), $kel->id_kelas); ?>
                                            data-id_guru="<?= encrypt_url($guru->id_guru); ?>"
                                            data-id_kelas="<?= $kel->id_kelas; ?>" />
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- TAHAP 2: Pilih Mapel Berdasarkan Kelas -->
            <div class="card card-flush shadow-sm">
                <div class="card-header bg-light-success pt-7 pb-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800 fs-4">
                            <i class="ki-duotone ki-book-open text-success fs-2 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                            Tahap 2: Relasi Mata Pelajaran Spesifik
                        </span>
                        <span class="text-gray-600 mt-2 fw-semibold fs-7">Buka menu kelas di bawah, lalu centang mata pelajaran yang ditugaskan untuk kelas tersebut.</span>
                    </h3>
                </div>
                <div class="card-body pt-5">

                    <div class="accordion" id="accordionMapelPerKelas">
                        <?php foreach ($kelas as $index => $kel) : ?>
                            <div class="accordion-item border-0 mb-4 bg-white rounded shadow-sm border border-gray-200">

                                <!-- Header Accordion -->
                                <div class="accordion-header" id="headingMapel_<?= $kel->id_kelas; ?>">
                                    <button class="accordion-button <?= $index === 0 ? '' : 'collapsed'; ?> fw-bolder fs-5 text-gray-800 bg-light-neutral"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseMapel_<?= $kel->id_kelas; ?>"
                                        aria-expanded="<?= $index === 0 ? 'true' : 'false'; ?>">
                                        <i class="ki-duotone ki-abstract-26 text-success fs-3 me-3"><span class="path1"></span><span class="path2"></span></i>
                                        Daftar Mapel untuk Kelas: <?= $kel->nama_kelas; ?>
                                    </button>
                                </div>

                                <!-- Body Accordion (Daftar Mapel) -->
                                <div id="collapseMapel_<?= $kel->id_kelas; ?>"
                                    class="accordion-collapse collapse <?= $index === 0 ? 'show' : ''; ?>"
                                    data-bs-parent="#accordionMapelPerKelas">

                                    <div class="accordion-body p-5 border-top border-gray-200 bg-light">
                                        <div class="row g-4">
                                            <?php foreach ($mapel as $m) : ?>
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="d-flex align-items-center justify-content-between p-4 border border-dashed border-gray-300 rounded bg-white hover-elevate-up transition">
                                                        <span class="text-gray-800 fw-bold fs-6"><?= $m->nama_mapel; ?></span>
                                                        <div class="form-check form-switch form-check-custom form-check-success form-check-sm ms-2">
                                                            <!-- Tambahan id_kelas ke dalam check_mapel dan atribut data -->
                                                            <input class="form-check-input check-mapel h-25px w-40px cursor-pointer"
                                                                type="checkbox"
                                                                id="mapel_<?= $kel->id_kelas; ?>_<?= $m->id_mapel; ?>"
                                                                <?= check_mapel(encrypt_url($guru->id_guru), $m->id_mapel, $kel->id_kelas); ?>
                                                                data-id_guru="<?= encrypt_url($guru->id_guru); ?>"
                                                                data-id_kelas="<?= $kel->id_kelas; ?>"
                                                                data-id_mapel="<?= $m->id_mapel; ?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        <?php endforeach; ?>
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
        let currentCsrfHash = "<?= csrf_hash() ?>";
        const csrfTokenName = "<?= csrf_token() ?>";

        function updateRelasi(url, dataPayload) {
            dataPayload[csrfTokenName] = currentCsrfHash;

            $.ajax({
                type: 'POST',
                url: url,
                data: dataPayload,
                dataType: 'JSON',
                success: function(response) {
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
            let isChecked = $(this).is(':checked');
            let idKelas = $(this).data('id_kelas');

            // Proses update relasi kelas ke database
            updateRelasi("<?= base_url('sw-admin/relasi/guru-kelas') ?>", {
                id_guru: $(this).data('id_guru'),
                id_kelas: idKelas
            });

            // [BARU] Jika kelas di-uncheck (dimatikan), otomatis uncheck semua mapel di kelas tersebut secara visual
            if (!isChecked) {
                $('.check-mapel[data-id_kelas="' + idKelas + '"]').prop('checked', false);
            }
        });

        // Trigger update relasi mapel
        $('.check-mapel').on('change', function() {
            updateRelasi("<?= base_url('sw-admin/relasi/guru-mapel') ?>", {
                id_guru: $(this).data('id_guru'),
                id_mapel: $(this).data('id_mapel'),
                id_kelas: $(this).data('id_kelas') // [BARU] Kirim id_kelas ke controller
            });
        });
    });
</script>
<?= $this->endSection(); ?>
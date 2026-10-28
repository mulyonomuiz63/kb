<?= $this->extend('template/app'); ?>
<?= $this->section('styles'); ?>
<?= csrf_meta() ?>
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <div class="card card-flush shadow-sm">
            <div class="card-header border-0 pt-6 flex-wrap gap-3">
                <div class="card-title m-0">
                    <!-- Filter Nama Soal / Pencarian -->
                    <div class="d-flex align-items-center position-relative my-1 me-3">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" data-kt-bank-soal-table-filter="search" class="form-control form-control-solid w-200px ps-13" placeholder="Cari Bank Soal..." />
                    </div>

                    <!-- Filter Kategori -->
                    <div class="my-1 me-3 w-200px">
                        <select id="filter_kategori" class="form-select form-select-solid">
                            <option value="">Semua Kategori</option>
                            <?php foreach ($kategori as $kat) : ?>
                                <option value="<?= $kat->nama_kategori; ?>"><?= $kat->nama_kategori; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Sub-Materi -->
                    <div class="my-1 w-200px">
                        <select id="filter_sub_materi" class="form-select form-select-solid">
                            <option value="">Semua Sub-Materi</option>
                            <?php foreach ($sub_materi_list as $sm) : ?>
                                <?php if (!empty($sm->sub_materi)): ?>
                                    <option value="<?= $sm->sub_materi; ?>"><?= $sm->sub_materi; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="card-toolbar m-0">
                    <div class="d-flex justify-content-end gap-3" data-kt-bank-soal-table-toolbar="base">
                        <a href="<?= base_url('sw-guru/kategori'); ?>" class="btn btn-light-primary fw-bold">
                            <i class="ki-duotone ki-plus-square fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            Kategori
                        </a>
                        <a href="<?= base_url('sw-guru/bank-soal/create'); ?>" class="btn btn-primary me-3">
                            <i class="ki-duotone ki-add-item fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            Tambah Soal
                        </a>
                        <!-- Tambahkan Tombol Import di bawah/sebelah tombol Tambah Soal -->
                        <button type="button" class="btn btn-light-success me-3" data-bs-toggle="modal" data-bs-target="#modalImportSoal">
                            <i class="ki-duotone ki-file-up fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Import Excel
                        </button>

                        <!-- Sediakan Link Download Template -->
                        <a href="<?= base_url('sw-guru/bank-soal/download-template'); ?>" class="btn btn-light-info me-3">
                            <i class="ki-duotone ki-cloud-download fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Download Template
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body py-4">
                <div class="table-responsive">
                    <table id="datatables-list" class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-200px">Nama Soal & Kategori</th>
                                <th class="min-w-125px text-center">Sub-Materi</th>
                                <th class="min-w-100px text-center">Level Soal</th>
                                <th class="text-end min-w-100px">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            <?php foreach ($soal as $u) : ?>
                                <?php
                                // Pemetaan Badge Level Kesulitan
                                $badgeClass = 'bg-secondary';
                                $badgeText = '-';
                                if (isset($u->jenis_soal)) {
                                    if ($u->jenis_soal == 'E') {
                                        $badgeClass = 'badge-light-success text-success';
                                        $badgeText = 'Mudah';
                                    } elseif ($u->jenis_soal == 'M') {
                                        $badgeClass = 'badge-light-warning text-warning';
                                        $badgeText = 'Sedang';
                                    } elseif ($u->jenis_soal == 'H') {
                                        $badgeClass = 'badge-light-danger text-danger';
                                        $badgeText = 'Sulit';
                                    }
                                }
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 text-hover-primary mb-1 fw-bold"><?= $u->nama_soal; ?></span>
                                            <span class="text-muted fs-7">Kategori: <?= !empty($u->nama_kategori) ? $u->nama_kategori : '-'; ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-gray-700 fs-7"><?= !empty($u->sub_materi) ? $u->sub_materi : '-'; ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge <?= $badgeClass ?> fw-bold fs-7"><?= $badgeText; ?></span>
                                    </td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Aksi
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                        </a>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <a href="<?= base_url('sw-guru/bank-soal/edit') . '/' . encrypt_url($u->id_bank_soal); ?>" class="menu-link px-3">Edit</a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a href="javascript:void(0)" data-url="<?= base_url('sw-guru/bank-soal/delete') . '/' . encrypt_url($u->id_bank_soal); ?>" class="menu-link px-3 btn-hapus text-danger btn-delete">Delete</a>
                                            </div>
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

<!-- Modal Import Excel -->
<div class="modal fade" id="modalImportSoal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form id="formImportSoal" enctype="multipart/form-data">
                <div class="modal-header">
                    <h2 class="fw-bold">Import Data Bank Soal (Excel)</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <div class="mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Pilih File Excel (.xlsx / .xls)</label>
                        <input type="file" name="file_excel" id="file_excel" class="form-control form-control-solid" accept=".xlsx, .xls" required />
                        <div class="text-muted fs-7 mt-2">Format file harus sesuai dengan template yang disediakan. Mendukung puluhan ribu data.</div>
                    </div>

                    <!-- Progress Bar Container (Hidden by default) -->
                    <div id="progressContainer" class="d-none">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold fs-6 text-gray-700" id="progressText">Mengunggah dan memproses data...</span>
                            <span class="fw-bold fs-6 text-primary" id="progressPercentage">0%</span>
                        </div>
                        <div class="progress h-6px w-100 bg-light-primary">
                            <div id="progressBar" class="progress-bar bg-primary" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="text-muted fs-7 mt-2 text-center" id="progressDetail"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSubmitImport" class="btn btn-primary">
                        <span class="indicator-label">Mulai Import</span>
                        <span class="indicator-progress">Mohon tunggu... 
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section("scripts"); ?>
<script>
    $(document).ready(function() {
        var table = $('#datatables-list').DataTable({
            "ordering": false,
            "lengthChange": false,
            "pageLength": 10,
            "info": false,
            "dom": "rtp",
            "drawCallback": function(settings) {
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            }
        });

        // Search Nama Soal
        $('[data-kt-bank-soal-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Filter Kategori (Kolom 0)
        $('#filter_kategori').on('change', function() {
            var val = $(this).val();
            table.column(0).search(val).draw();
        });

        // Filter Sub-Materi (Kolom 1)
        $('#filter_sub_materi').on('change', function() {
            var val = $(this).val();
            table.column(1).search(val ? '^' + val + '$' : '', true, false).draw();
        });
    });
</script>
<!-- Script AJAX untuk Proses Chunking / Batch Import -->
<script>
$('#formImportSoal').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    var fileInput = $('#file_excel')[0].files[0];

    if (!fileInput) {
        Swal.fire('Peringatan', 'Silakan pilih file Excel terlebih dahulu!', 'warning');
        return;
    }

    // Ubah UI tombol dan tampilkan progress bar
    $('#btnSubmitImport').attr('data-kt-indicator', 'on').prop('disabled', true);
    $('#progressContainer').removeClass('d-none');
    $('#progressBar').css('width', '10%');
    $('#progressPercentage').text('10%');
    $('#progressText').text('Membaca file Excel...');

    $.ajax({
        url: '<?= base_url("sw-guru/bank-soal/process-import"); ?>',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="X-CSRF-TOKEN"]').attr('content') // Mengirim token CSRF otomatis dari meta tag CI4
        },
        xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                    var percentComplete = Math.round((evt.loaded / evt.total) * 50); // 50% awal untuk upload
                    $('#progressBar').css('width', percentComplete + '%');
                    $('#progressPercentage').text(percentComplete + '%');
                }
            }, false);
            return xhr;
        },
        success: function(response) {
            if (response.status === 'success') {
                $('#progressBar').css('width', '100%');
                $('#progressPercentage').text('100%');
                $('#progressText').text('Import Selesai!');
                $('#progressDetail').text(response.message);

                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                Swal.fire('Gagal!', response.message, 'error');
                resetForm();
            }
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            Swal.fire('Terjadi Kesalahan!', 'Server mengalami kendala atau memori habis saat memproses data besar.', 'error');
            resetForm();
        }
    });
});

function resetForm() {
    $('#btnSubmitImport').removeAttr('data-kt-indicator').prop('disabled', false);
    $('#progressContainer').addClass('d-none');
    $('#progressBar').css('width', '0%');
}
</script>
<?= $this->endSection(); ?>
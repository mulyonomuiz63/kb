<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-sertifikat-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari sertifikat..." />
                        </div>
                    </div>

                    <div class="card-toolbar flex-row-fluid justify-content-end gap-3">
                        <?php if ($canDownloadAll) : ?>
                            <div class="d-flex align-items-center fw-bold text-gray-600 me-2">
                                Unduh Brevet AB:
                            </div>
                            <a href="javascript:void(0)"
                                data-bs-toggle="modal"
                                data-bs-target="#sertifikat_all_cetak_modal"
                                data-sertifikat_all="<?= base_url("sw-admin/siswa/lihatSertifikatBrevet/" . $idsiswa) ?>"
                                class="btn btn-sm btn-light-success sertifikat_all_cetak"
                                title="Unduh Sertifikat Brevet AB">
                                <i class="ki-duotone ki-file-down fs-2"><span class="path1"></span><span class="path2"></span></i> Standar
                            </a>

                            <a href="javascript:void(0)"
                                data-bs-toggle="modal"
                                data-bs-target="#sertifikat_all_cap_cetak_modal"
                                data-sertifikat_all_cap="<?= base_url("sw-admin/siswa/lihatSertifikatBrevet/" . $idsiswa) . "/cap" ?>"
                                class="btn btn-sm btn-light-primary sertifikat_all_cap_cetak"
                                title="Unduh Sertifikat Cap Basah">
                                <i class="ki-duotone ki-badge fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Cap Basah
                            </a>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatable-sertifikat" class="table align-middle table-row-dashed fs-6 gy-5 text-left" data-idsiswa="<?= $idsiswa ?>" style="width:100%">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Nama Ujian</th>
                                    <th class="min-w-100px">Verifikasi</th>
                                    <th class="min-w-150px">Mulai / Selesai</th>
                                    <th class="min-w-100px">Nilai</th>
                                    <th class="min-w-100px">Status</th>
                                    <th class="text-center min-w-100px">Sertifikat</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold"></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php 
$modals = [
    ['id' => 'sertifikat_cap_cetak_modal', 'class' => 'isiKontenSetifikatCap'],
    ['id' => 'sertifikat_cetak_modal', 'class' => 'isiKontenSertifikat'],
    ['id' => 'sertifikat_all_cap_cetak_modal', 'class' => 'isiKontenSetifikatAllCap'],
    ['id' => 'sertifikat_all_cetak_modal', 'class' => 'isiKontenSertifikatAll']
];
foreach($modals as $modal): ?>
<div class="modal fade" id="<?= $modal['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-lg">
            <div class="<?= $modal['class'] ?>"></div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        
        <?= session()->getFlashdata('pesan'); ?>

        const idSiswaEnc = $('#datatable-sertifikat').data('idsiswa');

        // Initialize DataTable
        const table = $('#datatable-sertifikat').DataTable({
            processing: true,
            serverSide: true,
            
            ajax: {
                url: "<?= base_url('sw-admin/siswa/get-data-sertifikat') ?>",
                type: "POST",
                data: function(d) {
                    d.id_siswa = idSiswaEnc;
                    d[csrfName] = csrfHash;
                },
                dataSrc: function(json) {
                    csrfHash = json.csrf_hash;
                    return json.data;
                },
            },
            columns: [
                { 
                    data: 'nama_ujian',
                    className: 'text-gray-800 fw-bold'
                },
                {
                    data: "verifikasi",
                    render: function(data) {
                        if (!data || data.trim() === "") return '<div class="text-gray-400">-</div>';
                        return `<img src="<?= base_url() ?>/uploads/verifikasi/${data}" alt="Verifikasi" class="img-fluid rounded shadow-sm" style="max-width: 50px; height: auto;">`;
                    }
                },
                {
                    data: null,
                    render: row => `<div class="d-flex flex-column"><span class="text-gray-800 mb-1">${row.start_ujian}</span><span class="text-muted">${row.end_ujian}</span></div>`
                },
                {
                    data: 'nilai',
                    className: 'fw-bold'
                },
                {
                    data: 'nilai',
                    render: nilai => {
                        let color = nilai >= 60 ? 'success' : 'danger';
                        let label = nilai >= 60 ? 'Lulus' : 'Tidak Lulus';
                        return `<span class="badge badge-light-${color} fw-bold px-3 py-2">${label}</span>`;
                    }
                },
                {
                    data: null,
                    className: 'text-center',
                    orderable: false,
                    render: row => {
                        if (row.nilai >= 60) {
                            return `
                            <div class="d-flex justify-content-center gap-2">
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#sertifikat_cetak_modal" 
                                   data-sertifikat="${row.url_cetak}" class="btn btn-icon btn-light-success btn-sm sertifikat_cetak" title="Sertifikat Standar">
                                    <i class="ki-duotone ki-document fs-3"><span class="path1"></span><span class="path2"></span></i>
                                </a>
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#sertifikat_cap_cetak_modal" 
                                   data-sertifikat_cap="${row.url_cetak_cap}" class="btn btn-icon btn-light-primary btn-sm sertifikat_cap_cetak" title="Sertifikat Cap Basah">
                                    <i class="ki-duotone ki-badge fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                </a>
                            </div>`;
                        } else {
                            return `<span class="btn btn-icon btn-light btn-sm disabled opacity-50"><i class="ki-duotone ki-document fs-3"><span class="path1"></span><span class="path2"></span></i></span>`;
                        }
                    }
                }
            ],
        });

        // Search DataTables Custom
        $('[data-kt-sertifikat-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Helper function untuk merender konten modal Metronic
        function renderModalContent(title, url) {
            return `
                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h2 class="fw-bold">${title}</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body p-5">
                    <iframe src="${url}" width="100%" height="600px" style="border:none; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.05);"></iframe>
                </div>
            `;
        }

        // Event Handlers
        $(document).on('click', '.sertifikat_cap_cetak', function() {
            $(".isiKontenSetifikatCap").html(renderModalContent('Sertifikat (Cap Basah)', $(this).data('sertifikat_cap')));
        });

        $(document).on('click', '.sertifikat_cetak', function() {
            $(".isiKontenSertifikat").html(renderModalContent('Sertifikat Resmi', $(this).data('sertifikat')));
        });

        $(document).on('click', '.sertifikat_all_cap_cetak', function() {
            $(".isiKontenSetifikatAllCap").html(renderModalContent('Sertifikat Brevet AB (Cap Basah)', $(this).data('sertifikat_all_cap')));
        });

        $(document).on('click', '.sertifikat_all_cetak', function() {
            $(".isiKontenSertifikatAll").html(renderModalContent('Sertifikat Brevet AB', $(this).data('sertifikat_all')));
        });
    });
</script>
<?= $this->endSection() ?>
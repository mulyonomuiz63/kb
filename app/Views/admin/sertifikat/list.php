<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<!--  BEGIN CONTENT AREA  -->
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">
            <div class="widget shadow p-3 bg-white">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <?php if ($canDownloadAll) : ?>
                                <div class="btn-group">
                                    <span class="mr-2 pt-1"><strong>Unduh Brevet AB:</strong></span>

                                    <a href="javascript:void(0)"
                                        data-toggle="modal"
                                        data-target="#sertifikat_all_cetak_modal"
                                        data-sertifikat_all="<?= base_url("sw-admin/siswa/lihatSertifikatBrevet/" . $idsiswa) ?>"
                                        class="badge badge-success sertifikat_all_cetak p-2"
                                        title="Unduh Sertifikat Brevet AB">
                                        <i class="bi bi-download"></i> Standar
                                    </a>

                                    <a href="javascript:void(0)"
                                        data-toggle="modal"
                                        data-target="#sertifikat_all_cap_cetak_modal"
                                        data-sertifikat_all_cap="<?= base_url("sw-admin/siswa/lihatSertifikatBrevet/" . $idsiswa) . "/cap" ?>"
                                        class="badge badge-primary sertifikat_all_cap_cetak p-2 ml-2"
                                        title="Unduh Sertifikat Cap Basah">
                                        <i class="bi bi-download"></i> Cap Basah
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable-sertifikat" class="table text-left"
                                data-idsiswa="<?= $idsiswa ?>"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nama Ujian</th>
                                        <th>Verifikasi</th>
                                        <th>Mulai / Selesai</th>
                                        <th>Nilai</th>
                                        <th>Status</th>
                                        <th class="text-center">Sertifikat</th>
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
</div>

<div class="modal fade" id="sertifikat_cap_cetak_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="isiKontenSetifikatCap"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="sertifikat_cetak_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="isiKontenSertifikat"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="sertifikat_all_cap_cetak_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="isiKontenSetifikatAllCap"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="sertifikat_all_cetak_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="isiKontenSertifikatAll"></div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Tampilkan pesan flashdata jika ada
        <?= session()->getFlashdata('pesan'); ?>

        const idSiswaEnc = $('#datatable-sertifikat').data('idsiswa');

        // Initialize DataTable
        const table = $('#datatable-sertifikat').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "<?= base_url('sw-admin/siswa/get-data-sertifikat') ?>",
                type: "POST",
                data: function(d) {
                    d.id_siswa = idSiswaEnc;
                    d[csrfName] = csrfHash;
                },
                dataSrc: function(json) {
                    csrfHash = json.csrf_hash; // Update CSRF Hash agar request berikutnya tidak 403
                    return json.data;
                },
            },
            columns: [{
                    data: 'nama_ujian'
                },
                {
                    data: "verifikasi",
                    render: function(data, type, row) {
                        // Jika data null, undefined, atau string kosong, tampilkan tanda hubung
                        if (!data || data.trim() === "") {
                            return '<div class="text-center">-</div>';
                        }

                        // Jika ada data, tampilkan tag img
                        return `<img src="<?= base_url() ?>/uploads/verifikasi/${data}" alt="Verifikasi" class="img-fluid" style="max-width: 50px; height: auto;">`;
                    }
                },
                {
                    data: null,
                    render: row => `<small>${row.start_ujian} / <br>${row.end_ujian}</small>`
                },
                {
                    data: 'nilai'
                },
                {
                    data: 'nilai',
                    render: nilai => {
                        let color = nilai >= 60 ? 'success' : 'danger';
                        let label = nilai >= 60 ? 'Lulus' : 'Tidak Lulus';
                        return `<span class="badge badge-${color}">${label}</span>`;
                    }
                },
                {
                    data: null,
                    className: 'text-center',
                    orderable: false,
                    render: row => {
                        if (row.nilai >= 60) {
                            return `
                            <div class="btn-group">
                                <a href="javascript:void(0)" data-toggle="modal" data-target="#sertifikat_cetak_modal" 
                                   data-sertifikat="${row.url_cetak}" class="badge badge-success sertifikat_cetak mr-1">
                                    <i class="bi bi-download"></i>
                                </a>
                                <a href="javascript:void(0)" data-toggle="modal" data-target="#sertifikat_cap_cetak_modal" 
                                   data-sertifikat_cap="${row.url_cetak_cap}" class="badge badge-primary sertifikat_cap_cetak">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>`;
                        } else {
                            return `<span class="badge badge-secondary" style="opacity:0.5"><i class="bi bi-download"></i></span>`;
                        }
                    }
                }
            ]
        });

        // --- Event Handlers (Gunakan Delegation agar tombol di tabel terdeteksi) ---

        // Sertifikat Cap
        $(document).on('click', '.sertifikat_cap_cetak', function() {
            const url = $(this).data('sertifikat_cap');
            $(".isiKontenSetifikatCap").html(`
                <div class="modal-header">
                    <h5 class="modal-title">Sertifikat (Cap Basah)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <iframe src="${url}" width="100%" height="500px" style="border:none;box-shadow: 0 10px 30px rgba(0,0,0,0.1);"></iframe>
            `);
        });

        // Sertifikat Standar
        $(document).on('click', '.sertifikat_cetak', function() {
            const url = $(this).data('sertifikat');
            $(".isiKontenSertifikat").html(`
                <div class="modal-header">
                    <h5 class="modal-title">Sertifikat Resmi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <iframe src="${url}" width="100%" height="500px" style="border:none;box-shadow: 0 10px 30px rgba(0,0,0,0.1);"></iframe>
            `);
        });

        // Sertifikat All Cap
        $(document).on('click', '.sertifikat_all_cap_cetak', function() {
            const url = $(this).data('sertifikat_all_cap');
            $(".isiKontenSetifikatAllCap").html(`
                <div class="modal-header">
                    <h5 class="modal-title">Sertifikat Brevet AB (Cap Basah)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <iframe src="${url}" width="100%" height="500px" style="border:none;box-shadow: 0 10px 30px rgba(0,0,0,0.1);"></iframe>
            `);
        });

        // Sertifikat All Standar
        $(document).on('click', '.sertifikat_all_cetak', function() {
            const url = $(this).data('sertifikat_all');
            $(".isiKontenSertifikatAll").html(`
                <div class="modal-header">
                    <h5 class="modal-title">Sertifikat Brevet AB</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <iframe src="${url}" width="100%" height="500px" style="border:none;box-shadow: 0 10px 30px rgba(0,0,0,0.1);"></iframe>
            `);
        });
    });
</script>
<?= $this->endSection() ?>
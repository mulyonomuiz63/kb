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
                            <input type="text" data-kt-siswa-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari peserta..." />
                        </div>
                    </div>

                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">

                        <div class="w-100 mw-150px">
                            <select id="filter-status" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Status">
                                <option value="">Semua Status</option>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                                <option value="2">Data Tidak Lengkap</option>
                            </select>
                        </div>

                        <a href="<?= base_url('sw-admin/siswa/sertifikat-ab') ?>" class="btn btn-light-success" data-bs-toggle="tooltip" data-bs-placement="top" title="List sertifikat AB seluruh siswa">
                            <i class="ki-duotone ki-badge fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            Sertifikat AB
                        </a>

                        <a href="<?= base_url('sw-admin/siswa/create') ?>" class="btn btn-primary">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah Peserta
                        </a>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatable-list" class="table align-middle table-row-dashed fs-6 gy-5 text-wrap">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-100px">NIP</th>
                                    <th class="min-w-150px">Nama</th>
                                    <th class="min-w-150px">Email</th>
                                    <th class="min-w-125px">Handphone</th>
                                    <th class="text-center min-w-100px">Registrasi</th>
                                    <th class="text-center min-w-100px">Status</th>
                                    <th class="text-center min-w-100px">Ujian</th>
                                    <th class="text-end min-w-100px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="detail_siswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div class="modal-body scroll-y pt-0 pb-15 px-5 px-xl-20">
                <div class="mb-13 text-center">
                    <h1 class="mb-3">Detail Informasi Peserta</h1>
                    <div class="text-muted fw-semibold fs-5">Informasi lengkap terkait identitas dan instansi peserta</div>
                </div>

                <div class="row g-9">
                    <div class="col-md-4 text-center mb-4">
                        <div class="symbol symbol-150px symbol-circle mb-5" id="file_profile">
                            <img src="<?= base_url('assets/admin/media/avatars/blank.png') ?>" alt="image" />
                        </div>
                        <h4 class="fw-bold text-gray-900 mb-1" id="nama_peserta_top">Loading...</h4>
                    </div>

                    <div class="col-md-8">

                        <div class="fs-5 fw-bold text-gray-900 mb-4 border-bottom pb-2">Identitas Pribadi</div>
                        <div class="row mb-7">
                            <?= renderDetailRow('ID Peserta', 'idpeserta') ?>
                            <?= renderDetailRow('NIK', 'nik') ?>
                            <?= renderDetailRow('Nama Lengkap', 'nama_peserta') ?>
                            <?= renderDetailRow('Tgl Lahir', 'tgl_lahir') ?>
                            <?= renderDetailRow('Jenis Kelamin', 'jenis_kelamin') ?>
                        </div>

                        <div class="fs-5 fw-bold text-gray-900 mb-4 border-bottom pb-2 mt-8">Kontak & Alamat</div>
                        <div class="row mb-7">
                            <?= renderDetailRow('Email', 'email_pribadi') ?>
                            <?= renderDetailRow('No. HP', 'hp_pribadi') ?>
                            <?= renderDetailRow('Alamat KTP', 'alamat_ktp', 12) ?>
                            <?= renderDetailRow('Domisili', 'alamat_domisili', 12) ?>
                        </div>

                        <div class="fs-5 fw-bold text-gray-900 mb-4 border-bottom pb-2 mt-8">Pekerjaan / Lembaga</div>
                        <div class="row">
                            <?= renderDetailRow('Profesi', 'profesi') ?>
                            <?= renderDetailRow('Lembaga', 'nama_lembaga') ?>
                            <?= renderDetailRow('Bidang Usaha', 'bidang_usaha') ?>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php
// Helper function disesuaikan dengan style typography Metronic 8
function renderDetailRow($label, $id, $col = 6)
{
    return "
    <div class='col-sm-$col mb-4'>
        <div class='fw-semibold text-muted fs-7 text-uppercase'>$label</div>
        <div class='fw-bold text-gray-800 fs-6' id='$id'>-</div>
    </div>";
}
?>
<?= $this->endSection(); ?>


<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Init DataTables
        var table = $('#datatable-list').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            // Konfigurasi UI khusus Metronic 8
            ajax: {
                url: "<?= base_url('sw-admin/siswa/datatable') ?>",
                type: "POST",
                data: function(d) {
                    d[csrfName] = csrfHash;
                    d.status_filter = $('#filter-status').val();
                },
                dataSrc: function(json) {
                    if (json.csrf_hash) {
                        csrfHash = json.csrf_hash;
                        $('input[name="' + csrfName + '"]').val(csrfHash);
                    }
                    return json.data;
                },
            },
            columns: [{
                    data: 'no_induk_siswa',
                    className: 'text-gray-800 fw-bold'
                },
                {
                    data: 'nama_siswa',
                    render: d => `<span class="text-gray-800 fw-bold">${d}</span>`
                },
                {
                    data: 'email'
                },
                {
                    data: 'hp',
                    render: function(data, type, row) {
                        // Cek jika data kosong, null, atau hanya berisi '0'
                        if (!data || data === '0' || data === '') {
                            return '<span class="text-muted">-</span>';
                        }

                        // Membersihkan nomor dari karakter non-angka
                        let cleanNumber = data.replace(/[^0-9]/g, '');

                        // Konversi awalan 0 menjadi 62 (Kode Negara Indonesia)
                        if (cleanNumber.startsWith('0')) {
                            cleanNumber = '62' + cleanNumber.slice(1);
                        }

                        return `<a href="https://wa.me/${cleanNumber}" target="_blank" style="text-decoration: none;">
                    <i class="fab fa-whatsapp" style="color: #25D366;"></i> ${data}
                </a>`;
                    }
                },
                {
                    data: 'date_created',
                    className: 'text-center'
                },
                {
                    data: 'is_active',
                    className: 'text-center',
                    render: function(d) {
                        return d != 0 ?
                            '<span class="badge badge-light-success fw-bold px-4 py-2">Aktif</span>' :
                            '<span class="badge badge-light-danger fw-bold px-4 py-2">Tidak Aktif</span>';
                    }
                },
                {
                    data: 'stats',
                    className: 'text-center'
                },
                {
                    data: null,
                    orderable: false,
                    className: 'text-end',
                    render: function(row) {

                        return `
                        <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            Aksi <i class="ki-duotone ki-down fs-5 ms-1"></i>
                        </a>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
                            <div class="menu-item px-3">
                                <a href="javascript:void(0);" class="menu-link px-3 detail-siswa" data-siswa="${row.id_siswa_enc}" data-bs-toggle="modal" data-bs-target="#detail_siswa">
                                    Detail
                                </a>
                            </div>
                            <div class="menu-item px-3">
                                <a href="<?= base_url('sw-admin/siswa/edit') ?>/${row.id_siswa_enc}" class="menu-link px-3">
                                    Edit
                                </a>
                            </div>
                            <div class="menu-item px-3">
                                <a href="<?= base_url('sw-admin/siswa/sertifikat') ?>/${row.id_siswa_enc}" class="menu-link px-3">
                                    Sertifikat
                                </a>
                            </div>
                            <div class="menu-item px-3">
                                <a href="<?= base_url('sw-admin/siswa/ujian') ?>/${row.id_siswa_enc}" class="menu-link px-3">
                                    List Ujian
                                </a>
                            </div>
                            <div class="menu-item px-3">
                                <a href="<?= base_url('sw-admin/siswa/materi') ?>/${row.id_siswa_enc}" class="menu-link px-3">
                                    List Materi
                                </a>
                            </div>
                            ${row.totalUjian <= 0 ? `
                                <div class="separator mt-3 opacity-75"></div>
                                <div class="menu-item px-3">
                                    <a href="<?= base_url('sw-admin/siswa/delete') ?>/${row.id_siswa_enc}" class="menu-link px-3 text-danger btn-hapus">
                                        Hapus
                                    </a>
                                </div>
                            ` : ''}
                        </div>
                        `;
                    }
                }
            ],
            // ======= TAMBAHKAN KODE INI =======
            drawCallback: function(settings) {
                // Beri tahu Metronic untuk membaca ulang DOM dan mengaktifkan dropdown baru
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            }
            // ===================================
        });

        // Search Custom Input
        $('[data-kt-siswa-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Filter Dropdown
        $('#filter-status').on('change', function() {
            table.ajax.reload();
        });

        // Detail Modal Ajax
        $(document).on('click', '.detail-siswa', function() {
            const id_siswa = $(this).data('siswa');

            $("#nama_peserta_top").html('Loading...');

            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-admin/siswa/detail') ?>",
                data: {
                    [csrfName]: csrfHash,
                    id_siswa: id_siswa
                },
                dataType: 'JSON',
                success: function(data) {
                    if (data.csrf_hash) {
                        csrfHash = data.csrf_hash;
                        $('input[name="' + csrfName + '"]').val(csrfHash);
                    }

                    // Render Foto Profil (Menggunakan classes Metronic)
                    let imgSource = data.avatar ? 'https://kelasbrevet.com/assets/app-assets/user/' + data.avatar : '<?= base_url('assets/admin/media/avatars/blank.png') ?>';
                    $("#file_profile").html('<img src="' + imgSource + '" alt="avatar" />');

                    // Render Data
                    $("#nama_peserta_top").html(data.nama_siswa);

                    $("#idpeserta").html(data.id_siswa);
                    $("#nama_peserta").html(data.nama_siswa);
                    $("#nik").html(data.nik || '-');
                    $("#tgl_lahir").html(data.tgl_lahir || '-');
                    $("#jenis_kelamin").html(data.jenis_kelamin || '-');
                    $("#alamat_ktp").html(data.alamat_ktp || '-');
                    $("#alamat_domisili").html(data.alamat_domisili || '-');
                    $("#profesi").html(data.profesi || '-');
                    $("#nama_lembaga").html(data.kantor || '-');
                    $("#bidang_usaha").html(data.bidang_usaha || '-');
                    $("#email_pribadi").html(data.email || '-');
                    $("#hp_pribadi").html(data.hp || '-');
                }
            });
        });

        // SweetAlert untuk Hapus (Gaya Metronic)
        $(document).on("click", ".btn-hapus", function(e) {
            e.preventDefault();
            var link = $(this).attr("href");

            Swal.fire({
                text: "Anda yakin ingin menghapus data ini?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function(result) {
                if (result.value) {
                    document.location.href = link;
                }
            });
        });

    });
</script>
<?= $this->endSection(); ?>
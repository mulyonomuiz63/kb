<?php

use Faker\Provider\Base;
?>
<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<!--  BEGIN CONTENT AREA  -->

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">
            <div class="widget shadow p-3 bg-white">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="d-flex align-items-center mb-3">
                            <a href="<?= base_url('sw-admin/siswa/create') ?>" class="badge bg-primary p-2 mr-2">Tambah Peserta</a>
                            <a href="<?= base_url('sw-admin/siswa/sertifikat-ab') ?>" class="badge  bg-success  p-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="List sertifikat AB seluruh siswa">
                                Sertifikat AB
                            </a>
                            <select id="filter-status" class="badge  bg-light p-2 ml-2">
                                <option value="">-- Semua Status --</option>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                                <option value="2">Data Tidak Lengkap</option>
                            </select>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable-list" class="table text-wrap">
                                <thead class="text-center">
                                    <tr>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Handphone</th>
                                        <th>Registrasi</th>
                                        <th>Status</th>
                                        <th>Ujian</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--  END CONTENT AREA  -->


<!--detail siswa-->
<!-- Modal edit -->
<div class="modal fade" id="detail_siswa" tabindex="-1" role="dialog" aria-labelledby="detail_siswaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold" id="detail_siswaLabel">
                    <i class="bi bi-person-badge mr-2"></i>Detail Informasi Peserta
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <div id="file_profile" class="mb-3">
                            <div class="avatar-preview"></div>
                        </div>
                        <h6 class="font-weight-bold mb-0" id="nama_peserta_top"></h6>
                        <small class="text-muted" id="idpeserta_top"></small>
                    </div>

                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <h6 class="text-primary border-bottom pb-2 mb-3">Identitas Pribadi</h6>
                            </div>

                            <?= renderDetailRow('ID Peserta', 'idpeserta') ?>
                            <?= renderDetailRow('NIK', 'nik') ?>
                            <?= renderDetailRow('Nama Lengkap', 'nama_peserta') ?>
                            <?= renderDetailRow('Tgl Lahir', 'tgl_lahir') ?>
                            <?= renderDetailRow('Jenis Kelamin', 'jenis_kelamin') ?>

                            <div class="col-12 mt-3 mb-2">
                                <h6 class="text-primary border-bottom pb-2 mb-3">Kontak & Alamat</h6>
                            </div>
                            <?= renderDetailRow('Email', 'email_pribadi') ?>
                            <?= renderDetailRow('No. HP', 'hp_pribadi') ?>
                            <?= renderDetailRow('Alamat KTP', 'alamat_ktp', 12) ?>
                            <?= renderDetailRow('Domisili', 'alamat_domisili', 12) ?>

                            <div class="col-12 mt-3 mb-2">
                                <h6 class="text-primary border-bottom pb-2 mb-3">Pekerjaan / Lembaga</h6>
                            </div>
                            <?= renderDetailRow('Profesi', 'profesi') ?>
                            <?= renderDetailRow('Lembaga', 'nama_lembaga') ?>
                            <?= renderDetailRow('Bidang Usaha', 'bidang_usaha') ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Styling untuk Modal yang lebih elegan */
    #detail_siswa .modal-content {
        border-radius: 15px;
        overflow: hidden;
    }

    #detail_siswa .label-detail {
        font-size: 0.8rem;
        color: #888ea8;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    #detail_siswa .value-detail {
        font-weight: 600;
        color: #3b3f5c;
        display: block;
        margin-bottom: 10px;
    }

    #detail_siswa .img-thumbnail {
        border-radius: 50%;
        width: 150px;
        height: 150px;
        object-fit: cover;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Responsif untuk Mobile */
    @media (max-width: 576px) {
        #detail_siswa .modal-body {
            padding: 1.5rem;
        }

        #detail_siswa .value-detail {
            margin-bottom: 15px;
            font-size: 0.95rem;
        }
    }
</style>

<?php
// Helper function untuk merender row agar kode HTML di atas tidak terlalu panjang
function renderDetailRow($label, $id, $col = 6)
{
    return "
    <div class='col-sm-$col mb-2'>
        <span class='label-detail'>$label</span>
        <span class='value-detail' id='$id'>-</span>
    </div>";
}
?>
<!--end detail siswa-->
<?= $this->endSection(); ?>
<?= $this->section('scripts') ?>
<script>
    <?= session()->getFlashdata('pesan'); ?>
    $(document).ready(function() {
        var table = $('#datatable-list').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= base_url('sw-admin/siswa/datatable') ?>",
                type: "POST",
                data: function(d) {
                    d[csrfName] = csrfHash; // SELALU pakai token terbaru
                    d.status_filter = $('#filter-status').val();
                },
                dataSrc: function(json) {
                    if (json.csrf_hash) {
                        csrfHash = json.csrf_hash; // UPDATE token untuk request berikutnya
                        $('input[name="' + csrfName + '"]').val(csrfHash);
                    }
                    return json.data;
                },
            },
            columns: [{
                    data: 'no_induk_siswa'
                },
                {
                    data: 'nama_siswa',
                    render: d => `<b>${d}</b>`
                },
                {
                    data: 'email'
                },
                {
                    data: 'hp'
                },
                {
                    data: 'date_created',
                    className: 'text-center'
                },
                {
                    data: 'is_active',
                    className: 'text-center',
                    render: d =>
                        d != 0 ?
                        '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Not Active</span>'
                },
                {
                    data: 'stats',
                    className: 'text-center'
                },
                {
                    data: null,
                    orderable: false,
                    className: 'text-end',
                    render: row => `
                    <div class="dropdown custom-dropdown">
                        <button class="badge badge-secondary border-0 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item detail-siswa text-info" href="javascript:void(0);" data-siswa="${row.id_siswa_enc}" data-toggle="modal" data-target="#detail_siswa">
                                <i class="bi bi-eye mr-2"></i> Detail
                            </a>
                            
                            <a class="dropdown-item text-primary" href="<?= base_url('sw-admin/siswa/edit') ?>/${row.id_siswa_enc}">
                                <i class="bi bi-pencil mr-2"></i> Edit
                            </a>

                            <a class="dropdown-item text-success" href="<?= base_url('sw-admin/siswa/sertifikat') ?>/${row.id_siswa_enc}">
                                <i class="bi bi-patch-check mr-2"></i> Sertifikat
                            </a>

                            <a class="dropdown-item text-warning" href="<?= base_url('sw-admin/siswa/ujian') ?>/${row.id_siswa_enc}" title="List ujian siswa">
                                <i class="bi bi-journal-text mr-2"></i> List Ujian
                            </a>

                            <div class="dropdown-divider"></div>
                            
                            <a class="dropdown-item text-danger" href="<?= base_url('sw-admin/siswa/delete') ?>/${row.id_siswa_enc}" id="hapus">
                                <i class="bi bi-trash mr-2"></i> Hapus
                            </a>
                        </div>
                    </div>`
                }
            ]
        });

        $('#filter-status').on('change', function() {
            table.ajax.reload();
        });


        $(document).on('click', '.detail-siswa', function() {
            const id_siswa = $(this).data('siswa');

            // Tampilkan animasi loading sederhana agar user tahu proses sedang berjalan
            $("#nama_peserta").html('<i class="spinner-border spinner-border-sm"></i> Loading...');

            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-admin/siswa/detail') ?>",
                data: {
                    // Mengambil token dari variabel global yang sudah kamu definisikan di awal script
                    [csrfName]: csrfHash,
                    id_siswa: id_siswa
                },
                dataType: 'JSON',
                success: function(data) {
                    // PENTING: Update csrfHash dengan token baru yang dikirim balik oleh server
                    // Pastikan Controller kamu mengirimkan hash terbaru (lihat penjelasan di bawah)
                    if (data.csrf_hash) {
                        csrfHash = data.csrf_hash;
                        // Update juga input hidden CSRF di halaman jika ada
                        $('input[name="' + csrfName + '"]').val(csrfHash);
                    }

                    // Render data ke Modal
                    $("#file_profile").html('<img src="https://kelasbrevet.com/assets/app-assets/user/' + (data.avatar ? data.avatar : 'default.png') + '" alt="avatar" class="img-profile-detail">');
                    $("#idpeserta").html(data.id_siswa);
                    $("#nama_peserta").html(data.nama_siswa);
                    $("#nik").html(data.nik || '-');
                    $("#tgl_lahir").html(data.tgl_lahir || '-');
                    $("#jenis_kelamin").html(data.jenis_kelamin || '-');
                    $("#alamat_ktp").html(data.alamat_ktp || '-');
                    $("#alamat_domisili").html(data.alamat_domisili || '-');
                    $("#kelurahan").html(data.kelurahan || '-');
                    $("#kecamatan").html(data.kecamatan || '-');
                    $("#kota").html(data.kota || '-');
                    $("#provinsi").html(data.provinsi || '-');
                    $("#profesi").html(data.profesi || '-');
                    $("#nama_lembaga").html(data.kota_intansi || '-');
                    $("#alamat_lembaga").html(data.kota_aktifitas_profesi || '-');
                    $("#bidang_usaha").html(data.bidang_usaha || '-');
                    $("#email_pribadi").html(data.email || '-');
                    $("#hp_pribadi").html(data.hp || '-');
                },
            });
        });
        // END SISWA

        $(document).on("click", "#hapus", function(e) {
            var link = $(this).attr("href");
            e.preventDefault();
            let result = confirm("Anda yakin ingin menghapus data ini?");
            if (result) {
                document.location.href = link;
            }
        });
    })
</script>

<?= $this->endSection(); ?>
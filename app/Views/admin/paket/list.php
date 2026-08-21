<?= $this->extend('template/app'); ?>
<?= $this->section('styles'); ?>
<style>
    /* Custom CSS untuk halaman create artikel */
    .select2-container--bootstrap5 .select2-selection--single,
    .select2-container--bootstrap5 .select2-selection--multiple {
        min-height: calc(1.5em + 1.65rem + 2px) !important;
        padding: 0.4rem 1rem !important;
    }
</style>
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>
<?php $db = Config\Database::connect(); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card card-flush shadow-sm border-0">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title flex-column align-items-start">
                        <div class="d-flex align-items-center gap-2 gap-lg-3">
                            <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#tambah_paket">
                                <i class="ki-duotone ki-plus fs-2"></i> Tambah Paket
                            </button>
                        </div>
                    </div>
                    <div class="card-toolbar">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-paket-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Paket..." />
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatable-list" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Nama Paket</th>
                                    <th class="text-center min-w-100px">Jenis</th>
                                    <th class="text-center min-w-100px">Diskon</th>
                                    <th class="text-end min-w-150px">Nominal</th>
                                    <th class="text-center min-w-100px">Status</th>
                                    <th class="text-center min-w-100px">Komisi</th>
                                    <th class="text-center min-w-150px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-paket" class="fw-semibold text-gray-600">
                                <?php foreach ($paket as $s) : ?>
                                    <tr data-id="<?= $s->idpaket ?>" style="cursor: grab;">
                                        <td class="text-gray-800 fw-bold text-wrap pl-3">
                                            <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-element-11 fs-4 text-muted me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                                <?= $s->nama_paket; ?>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-muted"><?= $s->tagline; ?></span>
                                        </td>
                                        <td class="text-center fw-bold text-danger">
                                            <?= $s->diskon; ?>%
                                        </td>
                                        <td class="text-end fw-bold text-gray-900">
                                            Rp <?= number_format($s->nominal_paket, 0, '.', '.'); ?>
                                        </td>
                                        <td class="text-center">
                                            <?= $s->status == 1
                                                ? '<span class="badge badge-light-success fw-bold px-3 py-2">Aktif</span>'
                                                : '<span class="badge badge-light-danger fw-bold px-3 py-2">Non-Aktif</span>'; ?>
                                        </td>
                                        <td class="text-center text-primary fw-bold">
                                            <?= $s->komisi ?>%
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <?php if ($s->v_ujian == 1 && $s->v_materi == 0): ?>
                                                    <a href="<?= base_url("sw-admin/paket/review/" . $s->slug) ?>" class="btn btn-icon btn-light-info btn-sm" data-bs-toggle="tooltip" title="Review">
                                                        <i class="ki-duotone ki-eye fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                    </a>
                                                <?php endif; ?>

                                                <!-- ======================================================== -->
                                                <!-- TAMBAHAN: Tombol Kirim Email (Hanya untuk Paket Webinar) -->
                                                <!-- ======================================================== -->
                                                <?php
                                                // 1. Decode string JSON menjadi Array PHP
                                                $jenisPaketArr = json_decode($s->jenis_paket, true);
                                                // 2. Pastikan bentuknya array, lalu ubah semua isinya jadi huruf kecil agar aman
                                                $jenisPaketArr = is_array($jenisPaketArr) ? array_map('strtolower', $jenisPaketArr) : [];

                                                // 3. Cek apakah ada kata 'webinar' di dalam array tersebut
                                                if (in_array('webinar', $jenisPaketArr)):
                                                ?>
                                                    <button type="button" class="btn btn-icon btn-light-success btn-sm btn-kirim-info-paket" data-id="<?= encrypt_url($s->idpaket); ?>" data-bs-toggle="tooltip" title="Kirim Email Peserta">
                                                        <i class="ki-duotone ki-send fs-3"><span class="path1"></span><span class="path2"></span></i>
                                                    </button>
                                                <?php endif; ?>
                                                <!-- ======================================================== -->

                                                <button type="button" class="btn btn-icon btn-sm <?= $s->is_pinned ? 'btn-warning' : 'btn-light-dark' ?> pin-paket" data-id="<?= $s->idpaket ?>" data-bs-toggle="tooltip" title="<?= $s->is_pinned ? 'Lepas Pin' : 'Pin ke Atas' ?>">
                                                    <i class="ki-duotone ki-pin fs-3"><span class="path1"></span><span class="path2"></span></i>
                                                </button>

                                                <button type="button" class="btn btn-icon btn-light-primary btn-sm edit-paket" data-bs-toggle="modal" data-bs-target="#edit_paket" data-paket="<?= encrypt_url($s->idpaket); ?>" data-bs-toggle="tooltip" title="Edit Data">
                                                    <i class="ki-duotone ki-pencil fs-3"><span class="path1"></span><span class="path2"></span></i>
                                                </button>

                                                <a href="javascript:void(0)" class="btn btn-icon btn-light-danger btn-sm btn-delete" data-url="<?= base_url('sw-admin/paket/delete/' . encrypt_url($s->idpaket)) ?>">
                                                    <i class="ki-duotone ki-trash fs-3">
                                                        <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                                    </i>
                                                </a>
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

<!-- ================= MODAL TAMBAH PAKET ================= -->
<div class="modal fade" id="tambah_paket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <div class="modal-content rounded border-0">
            <form action="<?= base_url('sw-admin/paket/store'); ?>" method="POST" enctype="multipart/form-data" class="form">
                <?= csrf_field() ?>
                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h2 class="fw-bold">Tambah Paket Baru</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>

                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">
                    <div class="row g-5">
                        <div class="col-md-12 fv-row mb-5">
                            <label class="required fs-6 fw-semibold mb-2 text-primary">Pilih Layanan / Jenis Paket</label>
                            <select name="jenis_paket[]" class="form-select form-select-solid jenis_paket" data-control="select2" data-dropdown-parent="#tambah_paket" data-placeholder="Pilih layanan..." multiple="multiple" required>
                                <option value="brevet">Kelas Brevet AB</option>
                                <option value="ikh">Perijinan IKH</option>
                                <option value="webinar">Webinar</option>
                                <option value="uskp">USKP</option>
                            </select>
                            <div class="form-text text-muted">Form akan menyesuaikan berdasarkan pilihan di atas. Anda dapat memilih lebih dari satu.</div>
                        </div>

                        <div class="col-md-12 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Gambar Paket</label>
                            <input type="file" name="avatar" class="form-control form-control-solid" required>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Nama Paket</label>
                            <input type="text" name="nama_paket" class="form-control form-control-solid" required>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Tagline Paket</label>
                            <input type="text" name="tagline" class="form-control form-control-solid" required>
                        </div>

                        <!-- Input Nominal Tambah Paket -->
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Nominal Paket (Rp)</label>
                            <input type="number" name="nominal_paket" id="add_nominal_paket" class="form-control form-control-solid" required>
                        </div>

                        <div class="col-md-6 fv-row" id="wrapper_tambah_diskon">
                            <label class="required fs-6 fw-semibold mb-2">Diskon</label>
                            <select name="iddiskon" class="form-select form-select-solid" data-control="select2" data-dropdown-parent="#wrapper_tambah_diskon" required>
                                <option value="">Pilih</option>
                                <?php $dataDiskon = $db->query("select * from diskon ")->getResultObject(); ?>
                                <?php foreach ($dataDiskon as $rows) : ?>
                                    <option value="<?= $rows->iddiskon; ?>"><?= $rows->diskon; ?>%</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Status Paket</label>
                            <select name="status" class="form-select form-select-solid" required>
                                <option value="1">Tampil / Aktif</option>
                                <option value="0">Tidak Tampil / Non-Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="fs-6 fw-semibold mb-2">Komisi Affiliate (%)</label>
                            <input type="number" name="komisi" class="form-control form-control-solid" value="0">
                        </div>
                    </div>

                    <!-- FORM BREVET / USKP -->
                    <div class="brevet-fields" style="display: none;">
                        <div class="separator separator-dashed my-8"></div>
                        <h4 class="fw-bold text-dark mb-5"><i class="ki-duotone ki-book-open fs-2 me-2"></i> Pengaturan Kelas Brevet / USKP</h4>
                        <div class="row g-5 bg-light-primary p-5 rounded">
                            <div class="col-md-12 fv-row" id="wrapper_id_kelas">
                                <label class="required fs-6 fw-semibold mb-2">Kelas</label>
                                <select name="id_kelas" id="id_kelas" class="form-select form-select-solid dynamic-req-brevet" data-control="select2" data-dropdown-parent="#wrapper_id_kelas" data-placeholder="Pilih Kelas">
                                    <option value="">Pilih</option>
                                    <?php $kelas = $db->query("select * from kelas")->getResultObject(); ?>
                                    <?php foreach ($kelas as $rows) : ?>
                                        <option value="<?= $rows->id_kelas; ?>"><?= $rows->nama_kelas; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-12 fv-row" id="wrapper_id_ujian">
                                <label class="fs-6 fw-semibold mb-2">Ujian</label>
                                <select name="id_ujian[]" id="id_ujian" class="form-select form-select-solid dynamic-req-brevet" data-control="select2" data-dropdown-parent="#wrapper_id_ujian" data-placeholder="Pilih Ujian..." multiple="multiple">
                                    <option></option>
                                </select>
                            </div>

                            <div class="col-md-12 fv-row" id="wrapper_id_mapel">
                                <label class="fs-6 fw-semibold mb-2">Mapel</label>
                                <select name="id_mapel[]" id="id_mapel" class="form-select form-select-solid" data-control="select2" data-dropdown-parent="#wrapper_id_mapel" data-placeholder="Pilih Mapel..." multiple="multiple">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- FORM WEBINAR -->
                    <div class="webinar-fields" style="display: none;">
                        <div class="separator separator-dashed my-8"></div>
                        <h4 class="fw-bold text-dark mb-5"><i class="ki-duotone ki-laptop fs-2 me-2"></i> Pengaturan Webinar</h4>
                        <div class="row g-5 bg-light-info p-5 rounded">
                            <div class="col-md-12 fv-row" id="wrapper_id_sesi">
                                <label class="required fs-6 fw-semibold mb-2">Pilih Sesi Webinar <span class="text-danger fs-8 fw-normal">(Bisa pilih banyak)</span></label>
                                <select name="id_sesi[]" id="id_sesi" class="form-select form-select-solid dynamic-req-webinar" data-control="select2" data-dropdown-parent="#wrapper_id_sesi" data-placeholder="Pilih Sesi Webinar..." multiple="multiple">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="fv-row mt-7">
                        <label class="required fs-6 fw-semibold mb-2">Deskripsi Paket</label>
                        <textarea name="deskripsi" class="summernote form-control" required></textarea>
                    </div>
                    <input type="hidden" name="jumlah_bulan" value="12">
                </div>

                <div class="modal-footer border-0 p-5 p-lg-10 pt-0 justify-content-end">
                    <button type="button" class="btn btn-light me-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= MODAL EDIT PAKET ================= -->
<div class="modal fade" id="edit_paket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <div class="modal-content rounded border-0 shadow-lg">
            <form action="<?= base_url('sw-admin/paket/update'); ?>" method="POST" enctype="multipart/form-data" class="form">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" name="idpaket" id="idpaket">

                <div class="modal-header pb-0 border-0 justify-content-between">
                    <h2 class="fw-bold">Edit Paket</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>

                <div class="modal-body scroll-y px-10 px-lg-15 pt-5 pb-10">
                    <div class="fv-row mb-7 text-center" id="view_gambar"></div>

                    <div class="row g-5">
                        <div class="col-md-12 fv-row mb-5" id="edit_jenis_paket">
                            <label class="required fs-6 fw-semibold mb-2 text-primary">Jenis Paket Terkait</label>
                            <select name="jenis_paket[]" class="form-select form-select-solid jenis_paket" data-control="select2" data-dropdown-parent="#edit_jenis_paket" data-placeholder="Pilih layanan..." multiple="multiple" required>
                                <option value="brevet">Kelas Brevet AB</option>
                                <option value="ikh">Perijinan IKH</option>
                                <option value="webinar">Webinar</option>
                                <option value="uskp">USKP</option>
                            </select>
                        </div>

                        <div class="col-md-12 fv-row">
                            <label class="fs-6 fw-semibold mb-2">Ganti Gambar <span class="text-muted fw-normal fs-8">(Kosongkan jika tidak diganti)</span></label>
                            <input type="file" name="avatar" class="form-control form-control-solid">
                            <input type="hidden" name="gambar_lama" id="gambar_lama">
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Nama Paket</label>
                            <input type="text" name="nama_paket" id="nama_paket" class="form-control form-control-solid" required>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Tagline Paket</label>
                            <input type="text" name="tagline" id="tagline" class="form-control form-control-solid" required>
                        </div>

                        <!-- Input Nominal Edit Paket -->
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Nominal (Rp)</label>
                            <input type="number" name="nominal_paket" id="edit_nominal_paket" class="form-control form-control-solid" required>
                        </div>

                        <div class="col-md-6 fv-row" id="wrapper_edit_diskon">
                            <label class="required fs-6 fw-semibold mb-2">Diskon</label>
                            <select name="iddiskon" id="iddiskon" class="form-select form-select-solid" data-control="select2" data-dropdown-parent="#wrapper_edit_diskon" required>
                                <option value="">Pilih</option>
                                <?php foreach ($dataDiskon as $rows) : ?>
                                    <option value="<?= $rows->iddiskon; ?>"><?= $rows->diskon; ?>%</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Status</label>
                            <select name="status" id="status" class="form-select form-select-solid" required>
                                <option value="1">Aktif</option>
                                <option value="0">Non-Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="fs-6 fw-semibold mb-2">Komisi Affiliate (%)</label>
                            <input type="number" name="komisi" id="komisi" class="form-control form-control-solid" required>
                        </div>
                    </div>

                    <!-- FORM BREVET / USKP EDIT -->
                    <div class="brevet-fields" style="display: none;">
                        <div class="separator separator-dashed my-8"></div>
                        <h4 class="fw-bold text-dark mb-5"><i class="ki-duotone ki-book-open fs-2 me-2"></i> Update Susunan Kelas Brevet / USKP</h4>

                        <div class="alert alert-dismissible bg-light-warning border border-warning d-flex flex-column flex-sm-row p-5 mb-5">
                            <i class="ki-duotone ki-information fs-2hx text-warning me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            <div class="d-flex flex-column pe-0 pe-sm-10">
                                <h5 class="mb-1">Penting!</h5>
                                <span>Biarkan form di bawah ini <strong>KOSONG</strong> jika Anda tidak ingin mengubah susunan Ujian & Mapel yang sudah ada. Memilih kelas baru akan menghapus dan menimpa susunan yang lama.</span>
                            </div>
                        </div>

                        <div class="row g-5 bg-light-primary p-5 rounded" id="wrapper_kelas">
                            <div class="col-md-6 fv-row">
                                <label class="fs-6 fw-semibold mb-2">Ubah Kelas <span class="text-muted fw-normal fs-8">(Opsional)</span></label>
                                <select name="id_kelas" id="edit_id_kelas" class="form-select form-select-solid" data-control="select2" data-dropdown-parent="#wrapper_kelas" data-placeholder="Pilih Kelas Baru">
                                    <option value="">-- Biarkan Kosong --</option>
                                    <?php foreach ($kelas as $rows) : ?>
                                        <option value="<?= $rows->id_kelas; ?>"><?= $rows->nama_kelas; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6 fv-row" id="wrapper_ujian_edit">
                                <label class="fs-6 fw-semibold mb-2">Ujian <span class="text-danger fs-8 fw-normal">(Bisa pilih banyak)</span></label>
                                <select name="id_ujian[]" id="edit_ujian_master" class="form-select form-select-solid" data-control="select2" data-dropdown-parent="#wrapper_ujian_edit" multiple="multiple" data-placeholder="Pilih Ujian...">
                                    <option></option>
                                </select>
                            </div>

                            <div class="col-md-12 fv-row" id="wrapper_mapel_edit">
                                <label class="fs-6 fw-semibold mb-2">Mapel <span class="text-danger fs-8 fw-normal">(Bisa pilih banyak)</span></label>
                                <select name="id_mapel[]" id="edit_id_mapel" class="form-select form-select-solid" data-control="select2" data-dropdown-parent="#wrapper_mapel_edit" multiple="multiple" data-placeholder="Pilih Mapel...">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- FORM WEBINAR EDIT -->
                    <div class="webinar-fields" style="display: none;">
                        <div class="separator separator-dashed my-8"></div>
                        <h4 class="fw-bold text-dark mb-5"><i class="ki-duotone ki-laptop fs-2 me-2"></i> Update Sesi Webinar</h4>
                        <div class="row g-5 bg-light-info p-5 rounded" id="wrapper_id_sesi_edit">
                            <div class="col-md-12 fv-row">
                                <label class="fs-6 fw-semibold mb-2">Pilih Sesi Webinar <span class="text-danger fs-8 fw-normal">(Bisa pilih banyak)</span></label>
                                <select name="id_sesi[]" id="edit_id_sesi" class="form-select form-select-solid dynamic-req-webinar" data-control="select2" data-dropdown-parent="#wrapper_id_sesi_edit" multiple="multiple" data-placeholder="Pilih Sesi Webinar...">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="fv-row mt-7">
                        <label class="required fs-6 fw-semibold mb-2">Deskripsi Paket</label>
                        <div id="deskripsi_wrapper"></div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-5 p-lg-10 pt-0 justify-content-end">
                    <button type="button" class="btn btn-light me-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    // =========================================================
    // 1. FUNGSI GLOBAL
    // =========================================================

    function updateCSRF(token) {
        $('input[name="<?= csrf_token() ?>"]').val(token);
    }

    // Fungsi Kalkulasi Harga Nominal berdasarkan total "harga_sesi"
    function calculateWebinarTotal(selectElement, nominalInputId) {
        let total = 0;
        $(selectElement).find('option:selected').each(function() {
            let harga = $(this).data('harga') || 0;
            total += parseInt(harga);
        });
        $(nominalInputId).val(total);
    }

    // UPDATE: Fungsi Menampilkan/Menyembunyikan Form Dinamis (Brevet, USKP, & Webinar)
    function toggleDynamicFields(modalElement, nominalInputId) {
        let selectedTypes = $(modalElement).find('.jenis_paket').val() || [];

        // Logika Form Brevet atau USKP
        if (selectedTypes.includes('brevet') || selectedTypes.includes('uskp')) {
            $(modalElement).find('.brevet-fields').slideDown();
            $(modalElement).find('.dynamic-req-brevet').prop('required', true);
        } else {
            $(modalElement).find('.brevet-fields').slideUp();
            $(modalElement).find('.dynamic-req-brevet').prop('required', false).val('').trigger('change');
        }

        // Logika Form Webinar & Kalkulasi Nominal
        if (selectedTypes.includes('webinar')) {
            $(modalElement).find('.webinar-fields').slideDown();
            $(modalElement).find('.dynamic-req-webinar').prop('required', true);

            // Jadikan field Nominal READONLY (Hanya bisa dihitung otomatis) & Ganti Background
            $(nominalInputId).prop('readonly', true).addClass('bg-secondary');

            // Hitung harga sesi (berjaga-jaga jika ada yg sudah terpilih)
            let selectSesi = $(modalElement).find('.dynamic-req-webinar');
            calculateWebinarTotal(selectSesi, nominalInputId);

        } else {
            $(modalElement).find('.webinar-fields').slideUp();
            $(modalElement).find('.dynamic-req-webinar').prop('required', false).val('').trigger('change');

            // Lepas kunci READONLY jika bukan webinar & Hapus efek background
            $(nominalInputId).prop('readonly', false).removeClass('bg-secondary');
        }
    }

    // =========================================================
    // 2. KETIKA DOKUMEN SIAP (DOCUMENT READY)
    // =========================================================
    $(document).ready(function() {

        var table = $('#datatable-list').DataTable({
            "ordering": false,
        });

        $('[data-kt-paket-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        $('.summernote').summernote({
            placeholder: 'Tulis deskripsi paket di sini...',
            height: 300,
            fontSizes: ['8', '9', '10', '11', '12', '14', '18', '24', '36', '48', '64', '82'],
        });

        // Deteksi perubahan jenis_paket pada masing-masing modal
        $('#tambah_paket .jenis_paket').on('change', function() {
            toggleDynamicFields($('#tambah_paket'), '#add_nominal_paket');
        });

        $('#edit_paket .jenis_paket').on('change', function() {
            toggleDynamicFields($('#edit_paket'), '#edit_nominal_paket');
        });

        // =========================================================
        // 3. AJAX DYNAMIC SELECT (MODAL TAMBAH)
        // =========================================================
        $('#id_kelas').change(function() {
            var id = $(this).val();
            var csrfName = '<?= csrf_token() ?>';
            var csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

            $.ajax({
                url: "<?php echo site_url('sw-admin/paket/ujian-master'); ?>",
                method: "POST",
                data: {
                    id: id,
                    [csrfName]: csrfHash
                },
                dataType: 'json',
                success: function(res) {
                    updateCSRF(res[csrfName]);
                    var html = '<option></option><option value="all">-- PILIH SEMUA UJIAN --</option>';
                    $.each(res.data, function(i, item) {
                        html += '<option value="' + item.id_ujian + '">' + item.nama_ujian + '</option>';
                    });
                    $('#id_ujian').html(html).select2({
                        dropdownParent: $('#wrapper_id_ujian')
                    });
                }
            });

            $.ajax({
                url: "<?php echo site_url('sw-admin/paket/get-mapel'); ?>",
                method: "POST",
                data: {
                    id: id,
                    [csrfName]: $('input[name="<?= csrf_token() ?>"]').val()
                },
                dataType: 'json',
                success: function(res) {
                    updateCSRF(res[csrfName]);
                    var html = '<option></option><option value="all">-- PILIH SEMUA MAPEL --</option>';
                    $.each(res.data, function(i, item) {
                        html += '<option value="' + item.id_mapel + '">' + item.nama_mapel + '</option>';
                    });
                    $('#id_mapel').html(html).select2({
                        dropdownParent: $('#wrapper_id_mapel')
                    });
                }
            });
        });

        // Load Sesi Webinar (Modal Tambah)
        $('#tambah_paket .jenis_paket').on('change', function() {
            let selectedTypes = $(this).val() || [];
            if (selectedTypes.includes('webinar')) {
                let sesiSelect = $('#id_sesi');
                if (sesiSelect.children('option').length <= 1) {
                    var csrfName = '<?= csrf_token() ?>';
                    $.ajax({
                        url: "<?= base_url('sw-admin/paket/get-webinar-sesi') ?>",
                        method: "POST",
                        data: {
                            [csrfName]: $('input[name="<?= csrf_token() ?>"]').val()
                        },
                        dataType: 'json',
                        success: function(res) {
                            updateCSRF(res[csrfName]);
                            var html = '<option></option>';
                            $.each(res.data, function(i, item) {
                                // SELIPKAN HARGA SESI di data-harga
                                html += '<option value="' + item.id_sesi + '" data-harga="' + item.harga_sesi + '">' + item.nama_sesi + '</option>';
                            });
                            sesiSelect.select2('destroy').html(html).select2({
                                theme: 'bootstrap5',
                                dropdownParent: $('#wrapper_id_sesi')
                            });
                        }
                    });
                }
            }
        });

        // Panggil fungsi Kalkulator otomatis ketika user memilih sesi (Modal Tambah)
        $('#id_sesi').on('change', function() {
            calculateWebinarTotal(this, '#add_nominal_paket');
        });

        // Panggil fungsi Kalkulator otomatis ketika user memilih sesi (Modal Edit)
        $('#edit_id_sesi').on('change', function() {
            let selectedTypes = $('#edit_paket .jenis_paket').val() || [];
            if (selectedTypes.includes('webinar')) {
                calculateWebinarTotal(this, '#edit_nominal_paket');
            }
        });


        // =========================================================
        // 4. AJAX DYNAMIC SELECT (MODAL EDIT)
        // =========================================================
        $('#edit_id_kelas').change(function(e) {
            if (e.preventAjax) return;
            var id = $(this).val();
            var csrfName = '<?= csrf_token() ?>';
            var csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

            if (!id) {
                $('#edit_ujian_master').html('<option></option>').trigger('change');
                $('#edit_id_mapel').html('<option></option>').trigger('change');
                return;
            }

            $.ajax({
                url: "<?php echo site_url('sw-admin/paket/ujian-master'); ?>",
                method: "POST",
                data: {
                    id: id,
                    [csrfName]: csrfHash
                },
                dataType: 'json',
                success: function(res) {
                    updateCSRF(res[csrfName]);
                    var html = '<option></option><option value="all">-- PILIH SEMUA UJIAN --</option>';
                    $.each(res.data, function(i, item) {
                        html += '<option value="' + item.id_ujian + '">' + item.nama_ujian + '</option>';
                    });
                    $('#edit_ujian_master').select2('destroy').html(html).select2({
                        theme: 'bootstrap5',
                        dropdownParent: $('#wrapper_ujian_edit')
                    });
                }
            });

            $.ajax({
                url: "<?php echo site_url('sw-admin/paket/get-mapel'); ?>",
                method: "POST",
                data: {
                    id: id,
                    [csrfName]: $('input[name="<?= csrf_token() ?>"]').val()
                },
                dataType: 'json',
                success: function(res) {
                    updateCSRF(res[csrfName]);
                    var html = '<option></option><option value="all">-- PILIH SEMUA MAPEL --</option>';
                    $.each(res.data, function(i, item) {
                        html += '<option value="' + item.id_mapel + '">' + item.nama_mapel + '</option>';
                    });
                    $('#edit_id_mapel').select2('destroy').html(html).select2({
                        theme: 'bootstrap5',
                        dropdownParent: $('#wrapper_mapel_edit')
                    });
                }
            });
        });


        // =========================================================
        // 5. AJAX BUKA MODAL EDIT PAKET
        // =========================================================
        $(document).on('click', '.edit-paket', function() {
            const idpaket = $(this).data('paket');

            $('#edit_id_kelas').val('');
            $('#edit_ujian_master').html('<option></option>').trigger('change');
            $('#edit_id_mapel').html('<option></option>').trigger('change');
            $('#edit_id_sesi').html('<option></option>').trigger('change');

            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-admin/paket/edit') ?>",
                data: {
                    idpaket: idpaket,
                    "<?= csrf_token() ?>": $('input[name="<?= csrf_token() ?>"]').val()
                },
                dataType: 'JSON',
                success: function(data) {
                    var csrfName = '<?= csrf_token() ?>';
                    updateCSRF(data[csrfName]);

                    $("#idpaket").val(data.idpaket);
                    $("#nama_paket").val(data.nama_paket);
                    $("#tagline").val(data.tagline);
                    $("#iddiskon").val(data.iddiskon).trigger('change');
                    $("#edit_nominal_paket").val(data.nominal_paket);
                    $("#gambar_lama").val(data.file);
                    $("#status").val(data.status);
                    $("#komisi").val(data.komisi);
                    $("#deskripsi_wrapper").html(`<textarea name="deskripsi" class="summernote_edit">${data.deskripsi}</textarea>`);

                    if (data.file) {
                        $("#view_gambar").html("<img class='img-fluid rounded shadow-sm w-150px' src='<?= base_url('assets-landing/images/paket/thumbnails'); ?>/" + data.file + "'>");
                    } else {
                        $("#view_gambar").html("");
                    }

                    $('.summernote_edit').summernote({
                        height: 300,
                        fontSizes: ['8', '9', '10', '11', '12', '14', '18', '24', '36', '48', '64', '82']
                    });

                    let arrPaket = [];
                    if (data.jenis_paket) {
                        let parsedData = (typeof data.jenis_paket === 'string') ? JSON.parse(data.jenis_paket) : data.jenis_paket;
                        if (Array.isArray(parsedData)) {
                            arrPaket = parsedData.map(item => item.trim().toLowerCase());
                        }
                    }

                    // Ini akan mentrigger toggleDynamicFields
                    $("#edit_paket .jenis_paket").val(arrPaket).trigger('change');

                    // AUTO-FILL BREVET / USKP
                    if (data.id_kelas) {
                        $("#edit_id_kelas").val(data.id_kelas).trigger({
                            type: 'change',
                            preventAjax: true
                        });
                        var tokenUpdate = $('input[name="<?= csrf_token() ?>"]').val();

                        $.ajax({
                            url: "<?php echo site_url('sw-admin/paket/ujian-master'); ?>",
                            method: "POST",
                            data: {
                                id: data.id_kelas,
                                [csrfName]: tokenUpdate
                            },
                            dataType: 'json',
                            success: function(res) {
                                updateCSRF(res[csrfName]);
                                var html = '<option></option><option value="all">-- PILIH SEMUA UJIAN --</option>';
                                $.each(res.data, function(i, item) {
                                    html += '<option value="' + item.id_ujian + '">' + item.nama_ujian + '</option>';
                                });
                                $('#edit_ujian_master').select2('destroy').html(html).select2({
                                    theme: 'bootstrap5',
                                    dropdownParent: $('#wrapper_ujian_edit')
                                });
                                if (data.arr_ujian && data.arr_ujian.length > 0) {
                                    $('#edit_ujian_master').val(data.arr_ujian).trigger('change');
                                }
                            }
                        });

                        $.ajax({
                            url: "<?php echo site_url('sw-admin/paket/get-mapel'); ?>",
                            method: "POST",
                            data: {
                                id: data.id_kelas,
                                [csrfName]: $('input[name="<?= csrf_token() ?>"]').val()
                            },
                            dataType: 'json',
                            success: function(res) {
                                updateCSRF(res[csrfName]);
                                var html = '<option></option><option value="all">-- PILIH SEMUA MAPEL --</option>';
                                $.each(res.data, function(i, item) {
                                    html += '<option value="' + item.id_mapel + '">' + item.nama_mapel + '</option>';
                                });
                                $('#edit_id_mapel').select2('destroy').html(html).select2({
                                    theme: 'bootstrap5',
                                    dropdownParent: $('#wrapper_mapel_edit')
                                });
                                if (data.arr_mapel && data.arr_mapel.length > 0) {
                                    $('#edit_id_mapel').val(data.arr_mapel).trigger('change');
                                }
                            }
                        });
                    }

                    // AUTO-FILL WEBINAR
                    if (arrPaket.includes('webinar')) {
                        $.ajax({
                            url: "<?= base_url('sw-admin/paket/get-webinar-sesi') ?>",
                            method: "POST",
                            data: {
                                [csrfName]: $('input[name="<?= csrf_token() ?>"]').val()
                            },
                            dataType: 'json',
                            success: function(res) {
                                updateCSRF(res[csrfName]);
                                var html = '<option></option>';
                                $.each(res.data, function(i, item) {
                                    // SELIPKAN HARGA SESI
                                    html += '<option value="' + item.id_sesi + '" data-harga="' + item.harga_sesi + '">' + item.nama_sesi + '</option>';
                                });

                                $('#edit_id_sesi').select2('destroy').html(html).select2({
                                    theme: 'bootstrap5',
                                    dropdownParent: $('#wrapper_id_sesi_edit')
                                });

                                if (data.arr_sesi && data.arr_sesi.length > 0) {
                                    // trigger change akan otomatis memanggil kalkulator total nominal
                                    $('#edit_id_sesi').val(data.arr_sesi).trigger('change');
                                } else {
                                    calculateWebinarTotal('#edit_id_sesi', '#edit_nominal_paket');
                                }
                            }
                        });
                    }
                }
            });
        });

        // =========================================================
        // 6. FITUR HAPUS PAKET & 7. FITUR PIN PAKET
        // =========================================================
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            const targetUrl = $(this).data('url');

            Swal.fire({
                title: "Hapus Paket?",
                text: "Data paket ini akan dipindahkan ke tempat sampah (Soft Delete).",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: "btn btn-light-primary"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        text: "Menghapus data...",
                        icon: "info",
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    window.location.href = targetUrl;
                }
            });
        });

        $(document).on('click', '.pin-paket', function() {
            let id = $(this).data('id');
            $.ajax({
                url: "<?= base_url('sw-admin/paket/pin') ?>",
                type: "POST",
                data: {
                    id: id,
                    "<?= csrf_token() ?>": $('input[name="<?= csrf_token() ?>"]').val()
                },
                dataType: "json",
                success: function(res) {
                    if (res.status === 'success') {
                        location.reload();
                    } else {
                        updateCSRF(res.<?= csrf_token() ?>);
                        Swal.fire("Gagal!", res.message, "error");
                    }
                }
            });
        });
    });

    // =========================================================
    // 8. SORTABLE (DRAG AND DROP REORDER)
    // =========================================================
    document.addEventListener('DOMContentLoaded', function() {
        let tbody = document.getElementById('sortable-paket');
        if (tbody) {
            Sortable.create(tbody, {
                animation: 150,
                ghostClass: 'bg-light-primary',
                onEnd: function() {
                    let order = [];
                    tbody.querySelectorAll('tr').forEach((row, index) => {
                        order.push({
                            id: row.dataset.id,
                            position: index + 1
                        });
                    });
                    fetch("<?= base_url('sw-admin/paket/reorder') ?>", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-Requested-With": "XMLHttpRequest",
                                "X-CSRF-TOKEN": $('input[name="<?= csrf_token() ?>"]').val()
                            },
                            body: JSON.stringify(order)
                        })
                        .then(res => res.json())
                        .then(res => {
                            updateCSRF(res.<?= csrf_token() ?>);
                            if (res.status !== 'success') Swal.fire("Gagal!", "Urutan gagal disimpan", "error");
                        });
                }
            });
        }
    });
</script>
<script>
    $(document).ready(function() {
        $(document).on('click', '.btn-kirim-info-paket', function(e) {
            e.preventDefault();
            var idPaket = $(this).data('id');

            Swal.fire({
                title: 'Kirim Email ke Peserta?',
                text: "Pesan akan dikirim secara bertahap untuk mencegah server down. Jangan tutup halaman ini selama proses berlangsung.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Kirim Sekarang!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan Loading awal
                    Swal.fire({
                        title: 'Mempersiapkan Data...',
                        html: 'Menghitung jumlah peserta...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                            // Mulai proses bertahap dari antrean (offset) 0
                            processEmailBatch(idPaket, 0, 0);
                        }
                    });
                }
            });
        });

        // Fungsi Rekursif (Memanggil dirinya sendiri hingga selesai)
        function processEmailBatch(idPaket, offset, totalBerhasil) {
            $.ajax({
                url: "<?= base_url('sw-admin/paket/kirim-email-peserta') ?>",
                type: "POST",
                data: {
                    id_paket: idPaket,
                    offset: offset,
                    [csrfName]: csrfHash // csrfName & csrfHash harus sudah didefinisikan secara global
                },
                dataType: "JSON",
                success: function(response) {
                    // Selalu perbarui Token CSRF agar tidak expired di tengah jalan
                    updateCSRF(response.<?= csrf_token() ?>); 

                    if (response.status) {
                        var newBerhasil = totalBerhasil + response.berhasil_batch;

                        // Jika antrean sudah habis (selesai)
                        if (response.is_done) {
                            Swal.fire('Selesai!', 'Berhasil mengirim pengingat ke ' + newBerhasil + ' email peserta.', 'success');
                        } else {
                            // Jika masih ada sisa, update text Swal dan panggil fungsi ini lagi
                            Swal.update({
                                title: 'Mengirim Email...',
                                html: `Proses pengiriman: <b>${response.next_offset}</b> dari <b>${response.total_data}</b> peserta... <br><br> <small class="text-danger">Mohon jangan menutup halaman ini.</small>`
                            });
                            
                            // Panggil lagi untuk batch selanjutnya
                            processEmailBatch(idPaket, response.next_offset, newBerhasil);
                        }
                    } else {
                        Swal.fire('Info', response.message, 'warning');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Koneksi terputus atau terjadi kesalahan pada server. Proses terhenti.', 'error');
                }
            });
        }
    });
</script>
<?= $this->endSection(); ?>
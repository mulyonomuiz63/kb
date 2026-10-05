<?= $this->extend('template/app'); ?>

<?= $this->section('content'); ?>
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card card-flush mb-8">
                <!-- Card Header: Search & Toolbar -->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-sertifikat-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari data pengajuan..." />
                        </div>
                    </div>
                </div>

                <!-- Card Body: Tabel Data -->
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="dt_pengajuan_admin" class="table align-middle table-row-dashed fs-6 gy-5 text-left" style="width:100%">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2">No</th>
                                    <th class="min-w-100px">Order ID & Tgl</th>
                                    <th class="min-w-150px">Data Penerima</th>
                                    <th class="min-w-200px">Alamat Pengiriman</th>
                                    <th class="min-w-100px">Status Bayar</th>
                                    <th class="min-w-100px">Status Kirim</th>
                                    <th class="min-w-125px">No. Resi</th>
                                    <th class="text-end min-w-100px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                <!-- Data dimuat via AJAX DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ======================================================= -->
<!-- MODAL UPDATE STATUS & RESI PENGIRIMAN -->
<!-- ======================================================= -->
<div class="modal fade" id="modal_update_pengiriman" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-500px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Update Pengiriman Sertifikat</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <form id="formUpdatePengiriman" class="form" action="#">
                    <?= csrf_field() ?>
                    <!-- Hidden input untuk ID Pengajuan (bukan ID Transaksi) -->
                    <input type="hidden" name="id_pengajuan" id="edit_id_pengajuan">

                    <div class="mb-5 text-center">
                        <div class="text-muted fw-semibold">Order ID</div>
                        <div class="fs-3 fw-bold text-dark" id="display_order_id">XXX</div>
                    </div>

                    <div class="d-flex flex-column mb-7 fv-row">
                        <label class="required fs-6 fw-semibold mb-2">Status Pengiriman</label>
                        <select name="status_pengiriman" id="edit_status_pengiriman" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-dropdown-parent="#modal_update_pengiriman">
                            <option value="menunggu">Menunggu Diproses</option>
                            <option value="diproses">Sedang Diproses/Dicetak</option>
                            <option value="dikirim">Sudah Dikirim (Input Resi)</option>
                        </select>
                    </div>

                    <div class="d-flex flex-column mb-7 fv-row" id="wrap_resi">
                        <label class="fs-6 fw-semibold mb-2">Nomor Resi / Kurir</label>
                        <input type="text" class="form-control form-control-solid" placeholder="Contoh: JNE - 0101010101" name="no_resi" id="edit_no_resi" />
                        <div class="text-muted fs-7 mt-2">Wajib diisi jika status diubah menjadi "Sudah Dikirim"</div>
                    </div>

                    <div class="text-center pt-10">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="btnSubmitUpdate" class="btn btn-primary">
                            <span class="indicator-label"><i class="ki-duotone ki-check fs-3"></i> Simpan Perubahan</span>
                            <span class="indicator-progress">Harap tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        let csrfName = '<?= csrf_token() ?>';
        let csrfHash = '<?= csrf_hash() ?>';

        // Fungsi update CSRF Token agar tidak Expired
        function updateCsrf(newHash) {
            csrfHash = newHash;
            $('input[name="' + csrfName + '"]').val(csrfHash);
        }

        // ==========================================
        // 1. INISIALISASI DATATABLES ADMIN
        // ==========================================
        const table = $('#dt_pengajuan_admin').DataTable({
            processing: true,
            serverSide: true, // Gunakan serverSide jika data banyak
            order: [],
            ajax: {
                // Pastikan URL ini mengarah ke Controller Admin Anda
                url: "<?= base_url('sw-admin/pengajuan-sertifikat/get-data-pengajuan') ?>", 
                type: "POST",
                data: function(d) {
                    d[csrfName] = csrfHash;
                },
                dataSrc: function(json) {
                    // PERBAIKAN: Tangkap token menggunakan key dinamis dan panggil fungsi updateCsrf
                    if (json[csrfName]) {
                        updateCsrf(json[csrfName]); 
                    }
                    return json.data;
                },
                error: function(xhr, error, thrown) {
                    console.group("DEBUG DATATABLE ERROR");
                    console.log("XHR Response:", xhr.responseText); // Lihat isi error PHP di sini
                    console.log("Status:", error);
                    console.log("Error Thrown:", thrown);
                    console.groupEnd();
                }
            },
            columns: [
                { data: "no", className: "text-center" },
                { 
                    data: null, 
                    render: function(data, type, row) {
                        return `
                            <span class="fw-bold text-gray-800 d-block">${row.idtransaksi}</span>
                            <span class="text-muted fs-7">${row.tanggal_pengajuan}</span>
                        `;
                    }
                },
                { 
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <span class="fw-bold text-gray-800 d-block">${row.nama_penerima}</span>
                            <span class="text-primary fs-7"><i class="ki-duotone ki-whatsapp fs-6 text-success"><span class="path1"></span><span class="path2"></span></i> ${row.no_hp}</span>
                        `;
                    }
                },
                { 
                    data: "alamat_lengkap",
                    render: function(data, type, row) {
                        return `<div class="text-break" style="max-width:250px; white-space: normal;">${data} <br><span class="fw-bold">Kode Pos: ${row.kode_pos}</span></div>`;
                    }
                },
                { 
                    data: "status_bayar", // Asumsi 'S' = Lunas, 'M' = Pending
                    render: function(data, type, row) {
                        if(data === 'S') return '<span class="badge badge-light-success">Lunas</span>';
                        if(data === 'M' || data === 'P') return '<span class="badge badge-light-warning">Pending</span>';
                        return '<span class="badge badge-light-danger">Batal/Gagal</span>';
                    }
                },
                { 
                    data: "status_pengiriman",
                    render: function(data, type, row) {
                        if(data === 'dikirim') return '<span class="badge badge-light-success">Dikirim</span>';
                        if(data === 'diproses') return '<span class="badge badge-light-primary">Diproses</span>';
                        return '<span class="badge badge-light-secondary">Menunggu</span>';
                    }
                },
                { 
                    data: "no_resi",
                    render: function(data) {
                        return data ? `<span class="badge badge-secondary text-dark fw-bold">${data}</span>` : '-';
                    }
                },
                {
                    data: null,
                    className: 'text-end',
                    orderable: false,
                    render: function(data, type, row) {
                        // Jika belum lunas, tombol update disembunyikan / didisable (Opsional)
                        let btnState = row.status_bayar === 'S' ? '' : 'disabled';
                        let btnTitle = row.status_bayar === 'S' ? 'Update Pengiriman' : 'Menunggu Pembayaran Lunas';

                        return `
                            <button type="button" class="btn btn-sm btn-light-primary btn-icon btn-update-status" 
                                data-id="${row.id_pengajuan}"
                                data-order="${row.idtransaksi}"
                                data-status="${row.status_pengiriman}"
                                data-resi="${row.no_resi || ''}"
                                title="${btnTitle}" ${btnState}>
                                <i class="ki-duotone ki-pencil fs-3"><span class="path1"></span><span class="path2"></span></i>
                            </button>
                        `;
                    }
                }
            ]
        });

        // Search Custom Table
        $('[data-kt-sertifikat-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        // ==========================================
        // 2. MUNCULKAN MODAL UPDATE STATUS
        // ==========================================
        $('#dt_pengajuan_admin').on('click', '.btn-update-status', function() {
            let id = $(this).data('id');
            let order = $(this).data('order');
            let status = $(this).data('status');
            let resi = $(this).data('resi');

            // Isi form modal
            $('#edit_id_pengajuan').val(id);
            $('#display_order_id').text(order);
            $('#edit_status_pengiriman').val(status).trigger('change');
            $('#edit_no_resi').val(resi);

            $('#modal_update_pengiriman').modal('show');
        });

        // ==========================================
        // 3. PROSES SIMPAN AJAX
        // ==========================================
        $('#formUpdatePengiriman').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let btn = $('#btnSubmitUpdate');

            // Validasi manual jika status dikirim, resi harus diisi
            if($('#edit_status_pengiriman').val() === 'dikirim' && $('#edit_no_resi').val().trim() === '') {
                Swal.fire('Perhatian!', 'Nomor Resi wajib diisi jika status sudah dikirim.', 'warning');
                return;
            }

            // Pastikan Token terupdate di form
            form.find('input[name="' + csrfName + '"]').val(csrfHash);
            btn.attr("data-kt-indicator", "on").prop("disabled", true);

            $.ajax({
                // Pastikan URL ini mengarah ke Controller Admin Anda
                url: "<?= base_url('sw-admin/pengajuan-sertifikat/update-pengiriman') ?>", 
                type: "POST",
                data: form.serialize(),
                dataType: "JSON",
                success: function(response) {
                    if (response[csrfName]) updateCsrf(response[csrfName]);
                    btn.removeAttr("data-kt-indicator").prop("disabled", false);

                    if (response.status === 'success') {
                        $('#modal_update_pengiriman').modal('hide');
                        Swal.fire("Berhasil!", response.message, "success");
                        table.ajax.reload(null, false); // Reload tabel tanpa reset pagination
                    } else {
                        Swal.fire("Gagal!", response.message, "error");
                    }
                },
                error: function(xhr) {
                    btn.removeAttr("data-kt-indicator").prop("disabled", false);
                    if(xhr.responseJSON && xhr.responseJSON[csrfName]) updateCsrf(xhr.responseJSON[csrfName]);
                    
                    let errMsg = "Terjadi kesalahan pada server.";
                    if (xhr.responseJSON && xhr.responseJSON.message) errMsg = xhr.responseJSON.message;
                    Swal.fire("Error Server!", errMsg, "error");
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>
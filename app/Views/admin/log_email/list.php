<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card card-flush shadow-sm border-0">
                <input type="hidden" class="csrf-token" value="<?= csrf_hash() ?>" />
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title flex-column align-items-start">
                        <span class="card-label fw-bold text-gray-800">Log Pengiriman Email</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Pantau status riwayat email (Success / Failed).</span>
                    </div>

                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <!-- Tombol Hapus Log > 3 Bulan -->
                        <button type="button" id="btn-delete-old" class="btn btn-danger">
                            <i class="ki-duotone ki-trash fs-2">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                            </i>
                            Hapus Log > 3 Bulan
                        </button>

                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" data-kt-log-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Email/Subjek..." />
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table id="datatables-log" class="table align-middle table-row-dashed fs-6 gy-5 text-nowrap w-100">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-150px">Tanggal</th>
                                    <th class="min-w-200px">Penerima</th>
                                    <th class="min-w-200px">Subjek</th>
                                    <th class="min-w-100px text-center">Status</th>
                                    <th class="min-w-200px">Pesan Error</th>
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

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {

        // 1. Inisialisasi DataTables Server Side
        var table = $('#datatables-log').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [
                [0, 'desc']
            ], // Default urutkan dari tanggal terbaru
            "ajax": {
                "url": "<?= base_url('sw-admin/log-email/datatables') ?>",
                "type": "POST",
                "data": function(d) {
                    d.<?= csrf_token() ?> = $('.csrf-token').val();
                },
                "dataSrc": function(json) {
                    // Selalu update token CSRF setiap kali tabel reload
                    $('.csrf-token').val(json.<?= csrf_token() ?>);
                    return json.data;
                }
            },
            "columns": [{
                    "data": "created_at"
                },
                {
                    "data": "penerima",
                    "className": "text-gray-800 fw-bold"
                },
                {
                    "data": "subjek"
                },
                {
                    "data": "status",
                    "className": "text-center",
                    "render": function(data, type, row) {
                        // Render badge berdasarkan status dari image_f8691d.png enum('success', 'failed')
                        if (data === 'success') {
                            return '<span class="badge badge-light-success">Success</span>';
                        } else {
                            return '<span class="badge badge-light-danger">Failed</span>';
                        }
                    }
                },
                {
                    "data": "error_message",
                    "render": function(data, type, row) {
                        if (!data) return '-';

                        // 1. FILTER KARAKTER (SANGAT PENTING)
                        // Mengubah tag HTML/Kutip dari pesan error menjadi teks aman agar tidak merusak tabel
                        var safeData = data.toString()
                            .replace(/&/g, "&amp;")
                            .replace(/</g, "&lt;")
                            .replace(/>/g, "&gt;")
                            .replace(/"/g, "&quot;")
                            .replace(/'/g, "&#039;");

                        // 2. Pisahkan string berdasarkan spasi (per kata)
                        var words = safeData.split(' ');

                        // Jika kata kurang dari atau sama dengan 20, tampilkan normal
                        if (words.length <= 20) {
                            return '<span style="word-break: break-word;">' + safeData + '</span>';
                        }

                        // Ambil 20 kata pertama dan gabungkan kembali ditambah titik-titik
                        var shortText = words.slice(0, 20).join(' ') + '...';

                        // Buat elemen HTML untuk toggle (buka/tutup) dengan penambahan word-break
                        return '<div class="toggle-error" style="cursor: pointer; word-break: break-word;" title="Klik untuk lihat detail">' +
                            '<span class="text-short">' + shortText + ' <span class="badge badge-light-primary ms-1 badge-sm" style="white-space: nowrap;">Lihat</span></span>' +
                            '<span class="text-full" style="display: none;">' + safeData + ' <span class="badge badge-light-danger ms-1 badge-sm" style="white-space: nowrap;">Ringkas</span></span>' +
                            '</div>';
                    }
                },
                {
                    "data": "opsi"
                }
            ],
            "columnDefs": [{
                    // AKTIFKAN TEXT WRAP KE SEMUA KOLOM
                    "targets": "_all",
                    "className": "text-wrap"
                },
                {
                    // Aturan tambahan spesifik untuk kolom status dan opsi
                    "targets": [3, 5],
                    "orderable": false,
                    "className": "text-end text-wrap"
                }
            ],
            "drawCallback": function(settings) {
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            }
        });

        // EVENT CLICK: Untuk membuka dan menutup kolom pesan error yang panjang
        $('#datatables-log').on('click', '.toggle-error', function() {
            // Sembunyikan kolom lain yang mungkin sedang terbuka (agar hanya 1 yang terbuka)
            $('.toggle-error').not(this).find('.text-short').show();
            $('.toggle-error').not(this).find('.text-full').hide();

            // Lakukan toggle (tampil/sembunyi) pada elemen yang diklik
            $(this).find('.text-short').toggle();
            $(this).find('.text-full').toggle();
        });

        // Fitur Pencarian
        $('[data-kt-log-table-filter="search"]').on('keyup', function() {
            table.search(this.value).draw();
        });

        // 2. Fungsi Hapus Log Lebih Dari 3 Bulan (AJAX)
        $('#btn-delete-old').on('click', function(e) {
            e.preventDefault();

            Swal.fire({
                text: "Anda yakin ingin menghapus semua log email yang usianya lebih dari 3 bulan? Data yang dihapus tidak dapat dikembalikan.",
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
                    // Lakukan request AJAX ke controller
                    $.ajax({
                        type: 'POST',
                        url: "<?= base_url('sw-admin/log-email/delete-old') ?>",
                        data: {
                            <?= csrf_token() ?>: $('.csrf-token').val()
                        },
                        dataType: 'JSON',
                        success: function(response) {
                            $('.csrf-token').val(response.token);

                            if (response.status === 'success') {
                                Swal.fire({
                                    text: response.message,
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, Mengerti!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function(result) {
                                    // Refresh Datatable
                                    table.ajax.reload(null, false);
                                });
                            } else {
                                Swal.fire({
                                    text: response.message,
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, Mengerti!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                text: "Terjadi kesalahan pada server.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, Mengerti!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        }
                    });
                }
            });
        });

    });
</script>
<?= $this->endSection(); ?>
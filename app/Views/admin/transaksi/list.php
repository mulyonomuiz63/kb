<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">
            <div class="widget shadow-sm bg-white rounded-lg border-0">
                <div class="widget-content p-3">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="font-weight-bold text-dark mb-0">Manajemen Transaksi</h5>
                            <p class="text-muted small">Kelola dan validasi pembayaran peserta ujian secara real-time.</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatables-list" class="table table-hover dt-table-hover w-100">
                            <thead>
                                <tr>
                                    <th>Peserta</th>
                                    <th>Paket & Lembaga</th>
                                    <th>Voucher</th>
                                    <th>Pembayaran</th>
                                    <th>Nominal</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
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

<div class="modal fade" id="validasi_transaksi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <form action="<?= base_url('sw-admin/transaksi/approve-transaksi'); ?>" method="POST">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf-token" />
                <div id="isiKonten">
                    <div class="p-5 text-center">
                        <div class="spinner-border text-primary"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="invoice_cetak_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="isiKontenInvoice"></div>
        </div>
    </div>
</div>

<style>
    .widget {
        border-radius: 12px;
    }

    .table thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #eee;
    }

    .badge {
        padding: 0.5em 0.8em;
        border-radius: 6px;
        font-weight: 500;
    }

    .badge-subtle-success {
        background: #e6f7e9;
        color: #28a745;
    }

    .badge-subtle-warning {
        background: #fff4e6;
        color: #fd7e14;
    }

    .zoom {
        transition: transform .3s ease;
        cursor: zoom-in;
        border-radius: 8px;
    }

    .zoom:hover {
        transform: scale(1.05);
    }
</style>

<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // 1. Inisialisasi DataTable Server Side
        var table = $('#datatables-list').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= base_url('sw-admin/transaksi/datatables') ?>",
                "type": "POST",
                "data": function(d) {
                    d["<?= csrf_token() ?>"] = $('.csrf-token').val();
                },
                "dataSrc": function(json) {
                    $('.csrf-token').val(json.csrf_hash);
                    return json.data;
                },
            },
            "columns": [{
                    "data": "peserta"
                },
                {
                    "data": "paket"
                },
                {
                    "data": "voucher"
                },
                {
                    "data": "pembayaran"
                },
                {
                    "data": "nominal"
                },
                {
                    "data": "status"
                },
                {
                    "data": "aksi"
                }
            ],
            "columnDefs": [{
                "targets": [5, 6],
                "orderable": false
            }],
        });


        // 2. Fungsi Validasi Transaksi (AJAX)
        $(document).on('click', '.validasi-transaksi', function() {
            const idtransaksi = $(this).data('transaksi');
            const csrfName = "<?= csrf_token() ?>";
            const csrfHash = $('.csrf-token').val();

            $("#isiKonten").html('<div class="p-5 text-center"><div class="spinner-border text-primary"></div></div>');

            $.ajax({
                type: 'POST',
                url: "<?= base_url('sw-admin/transaksi/validasi-transaksi') ?>",
                data: {
                    idtransaksi: idtransaksi,
                    [csrfName]: csrfHash
                },
                dataType: 'JSON',
                success: function(data) {
                    // Update CSRF token
                    $('.csrf-token').val(data[csrfName]);

                    // Kalkulasi (Logika Anda tidak berubah)
                    let diskon = (data.nominal * data.diskon) / 100;
                    let totalDiskon = data.nominal - diskon;
                    let diskon_voucher = (totalDiskon * data.voucher) / 100;
                    let totalFix = data.nominal - diskon - diskon_voucher;

                    let statusBadge = getStatusBadge(data.status);
                    let jenisBayar = (data.jenis_bayar == 'online') ? 'Midtrans' : (data.status == 'P' ? 'N/A' : 'Manual');
                    let imgPath = '<?= base_url('uploads/transaksi/thumbnails'); ?>/' + data.bukti_pembayaran;
                    let lampiran = '';
                    if(data.status === 'V' && data.jenis_bayar === 'manual'){
                           lampiran = `<div class="form-group mb-0 text-center">
                                <label class="small d-block text-left text-muted font-weight-bold">Bukti Pembayaran:</label>
                                <img class="zoom shadow-sm border w-100 mt-2" src="${imgPath}" alt="Bukti">
                            </div>`;
                    }else if( data.status === 'S' && data.jenis_bayar === 'manual'){
                        lampiran = `<div class="form-group mb-0 text-center">
                                <label class="small d-block text-left text-muted font-weight-bold">Bukti Pembayaran:</label>
                                <img class="zoom shadow-sm border w-100 mt-2" src="${imgPath}" alt="Bukti">
                            </div>`;
                    }
                    
                    $("#isiKonten").html(`
                        <div class="modal-header bg-light">
                            <h5 class="modal-title font-weight-bold">Validasi Transaksi</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="row small mb-3">
                                <div class="col-6 text-muted">Paket:</div><div class="col-6 font-weight-bold text-right">${data.nama_paket}</div>
                                <div class="col-6 text-muted">Peserta:</div><div class="col-6 font-weight-bold text-right">${data.nama_siswa}</div>
                                <hr class="w-100">
                                <div class="col-6 text-muted">ID Transaksi:</div><div class="col-6 text-right">${data.idtransaksi}</div>
                                <div class="col-6 text-muted">Jenis Pembayaran:</div><div class="col-6 font-weight-bold text-right text-success">${jenisBayar}</div>
                                <div class="col-6 text-muted">Nominal:</div><div class="col-6 font-weight-bold text-right text-primary">Rp ${numberFormat(totalFix, 0, ',', '.')}</div>
                            </div>
                            <div class="mb-3 text-center">${statusBadge}</div>
                            ${lampiran}
                            <input type="hidden" name="idtransaksi" value="${data.idtransaksi}">
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-sm btn-link text-dark" data-dismiss="modal">Tutup</button>
                        </div>
                    `);
                }
            });
        });

        // 3. Helper Status Badge
        function getStatusBadge(status) {
            const map = {
                'S': '<span class="badge badge-subtle-success">Lunas</span>',
                'P': '<span class="badge badge-primary">Menunggu Pembayaran</span>',
                'V': '<span class="badge badge-info">Menunggu Approval</span>',
                'E': '<span class="badge badge-danger">Expired</span>',
                'M': '<span class="badge badge-warning">Proses Pembayaran</span>',
                'DM': '<span class="badge badge-danger">Denied</span>',
                'PM': '<span class="badge badge-warning">Pending</span>'
            };
            return map[status] || '<span class="badge badge-danger">Dined</span>';
        }

        // 4. Invoice Cetak
        $(document).on('click', '.invoice_cetak', function() {
            const url = $(this).data('invoice');
            $(".isiKontenInvoice").html(`
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title text-white">Preview Invoice</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <iframe src="${url}" width="100%" height="600px" style="border:none;"></iframe>
            `);
        });

        // 5. Konfirmasi Aksi (SweetAlert Style Native)
        $(document).on("click", "#hapus, #approve", function(e) {
            e.preventDefault();

            const url = $(this).attr("href");
            const isHapus = $(this).attr('id') == 'hapus';

            const title = isHapus ? "Batalkan Transaksi?" : "Approve Transaksi?";
            const text = isHapus ? "Data yang dibatalkan mungkin tidak dapat dikembalikan." : "Pastikan data transaksi sudah sesuai.";
            const icon = isHapus ? "warning" : "question";
            const confirmButtonColor = isHapus ? "#d33" : "#3085d6";
            const confirmButtonText = isHapus ? "Ya, Batalkan!" : "Ya, Approve!";

            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmButtonColor,
                cancelButtonColor: "#aaa",
                confirmButtonText: confirmButtonText,
                cancelButtonText: "Kembali"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading sebentar sebelum pindah halaman (opsional namun baik untuk UX)
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    window.location.href = url;
                }
            });
        });
    });

    // Helper Number Format
    function numberFormat(n, c, d, t) {
        var c = isNaN(c = Math.abs(c)) ? 2 : c,
            d = d == undefined ? "." : d,
            t = t == undefined ? "," : t,
            s = n < 0 ? "-" : "",
            i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
            j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    }
</script>
<?= $this->endSection(); ?>
<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <div class="card card-flush shadow-sm border-0">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title flex-column align-items-start">
                        <span class="card-label fw-bold text-gray-800">Daftar Pengajuan IKH</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Kelola persetujuan dan penerbitan Kartu IKH.</span>
                    </div>
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input type="text" id="searchInput" class="form-control form-control-solid w-250px ps-12" placeholder="Cari NIK / Nama..." />
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_ikh">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-150px">Nama Lengkap & NIK</th>
                                    <th class="min-w-125px">Instansi</th>
                                    <th class="min-w-125px">Status Validasi</th>
                                    <th class="min-w-125px">Tahap Akhir</th>
                                    <th class="text-end min-w-70px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                <?php foreach ($list_ikh as $row): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <a href="<?= base_url('admin/ikh/review/' . $row['id_ikh']) ?>" class="text-gray-800 text-hover-primary mb-1 fw-bold"><?= $row['nama_lengkap'] ?></a>
                                                <span><?= $row['nik'] ?></span>
                                            </div>
                                        </td>
                                        <td><?= $row['nama_kantor'] ?></td>
                                        <td>
                                            <?php
                                            $badgeVal = 'badge-light-warning';
                                            $textVal = 'Pending';
                                            if ($row['status_validasi_admin'] == 'valid') {
                                                $badgeVal = 'badge-light-success';
                                                $textVal = 'Valid';
                                            }
                                            if ($row['status_validasi_admin'] == 'ditolak') {
                                                $badgeVal = 'badge-light-danger';
                                                $textVal = 'Ditolak';
                                            }
                                            ?>
                                            <span class="badge <?= $badgeVal ?> fs-7 fw-bold"><?= $textVal ?></span>
                                        </td>
                                        <td>
                                            <?php if ($row['status_sertifikat'] == 'terbit'): ?>
                                                <span class="badge badge-light-success fs-7">Sertifikat Terbit</span>
                                            <?php else: ?>
                                                <span class="badge badge-light-secondary fs-7">Dalam Proses</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="<?= base_url('sw-pic/ikh/review/' . encrypt_url($row['id_ikh'])) ?>" class="btn btn-sm btn-light-primary fw-bold">
                                                    <i class="ki-outline ki-eye fs-3 me-1"></i> Review
                                                </a>

                                                <button type="button"
                                                    class="btn btn-sm btn-light-success fw-bold btn-lihat-sertifikat"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modal_sertifikat"
                                                    data-url="<?= base_url('sw-pic/sertifikat/brevet-ab/' . encrypt_url($row['id_siswa'])) ?>"
                                                    data-nama="<?= $row['nama_lengkap'] ?>">
                                                    <i class="ki-outline ki-award fs-3 me-1"></i> Sertifikat
                                                </button>
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
<div class="modal fade" id="modal_sertifikat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded">
            <div class="modal-header justify-content-between">
                <h3 class="modal-title fs-4 fw-bolder">Sertifikat: <span id="nama_peserta_modal" class="text-primary"></span></h3>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div class="modal-body p-0">
                <div id="loader_sertifikat" class="text-center py-20 d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                    <div class="text-muted mt-2">Memuat Sertifikat...</div>
                </div>
                <iframe id="frame_sertifikat" src="" width="100%" height="500px" style="border:none; display:block;"></iframe>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Tutup</button>
                <a id="link_download_sertifikat" href="" target="_blank" class="btn btn-primary fw-bold">
                    <i class="ki-duotone ki-file-down fs-2"><span class="path1"></span><span class="path2"></span></i> Download Sertifikat
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        var table = $('#kt_table_ikh').DataTable({
            "info": false,
            "order": [],
            "pageLength": 10,
        });

        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.btn-lihat-sertifikat').on('click', function() {
            // Ambil data dari atribut tombol yang diklik
            const urlSertifikat = $(this).data('url');
            const namaPeserta = $(this).data('nama');

            // Update Judul Modal
            $('#nama_peserta_modal').text(namaPeserta);

            // Tampilkan Loader, Sembunyikan Iframe sementara
            $('#frame_sertifikat').addClass('d-none');
            $('#loader_sertifikat').removeClass('d-none');

            // Set URL ke Iframe (Preview) dan Link Download
            $('#frame_sertifikat').attr('src', urlSertifikat);
            $('#link_download_sertifikat').attr('href', urlSertifikat);

            // Ketika iframe selesai loading
            $('#frame_sertifikat').on('load', function() {
                $('#loader_sertifikat').addClass('d-none');
                $(this).removeClass('d-none');
            });
        });

        // Reset iframe saat modal ditutup agar tidak berat
        $('#modal_sertifikat').on('hidden.bs.modal', function() {
            $('#frame_sertifikat').attr('src', '');
        });

        $('#link_download_sertifikat').on('click', function(e) {
            e.preventDefault(); // Mencegah browser membuka link (mencegah pindah tab)

            let btn = $(this);
            let fileUrl = btn.attr('href'); // Ambil URL dari tombol
            let originalContent = btn.html(); // Simpan tampilan tombol asli

            // Ubah tombol jadi status loading agar user tahu sedang diproses
            btn.html('<span class="spinner-border spinner-border-sm me-2"></span> Mengunduh...');
            btn.addClass('disabled').css('pointer-events', 'none');

            // Proses ambil file di background
            fetch(fileUrl)
                .then(response => {
                    if (!response.ok) throw new Error('Gagal mengambil file');
                    return response.blob(); // Ubah response menjadi Blob (file biner)
                })
                .then(blob => {
                    // Buat URL lokal sementara dari file Blob
                    let url = window.URL.createObjectURL(blob);

                    // Ambil nama peserta dari judul modal untuk nama file (Opsional, agar dinamis)
                    let namaPeserta = $('#nama_peserta_modal').text().trim();
                    let namaFile = namaPeserta ? "Sertifikat_Brevet_" + namaPeserta.replace(/\s+/g, '_') + ".pdf" : "Sertifikat_Brevet.pdf";

                    // Buat elemen <a> bayangan untuk men-trigger download
                    let a = document.createElement('a');
                    a.href = url;
                    a.download = namaFile; // Paksa browser untuk mendownload dengan nama ini
                    document.body.appendChild(a);
                    a.click(); // Klik otomatis

                    // Bersihkan elemen bayangan dan memori
                    a.remove();
                    window.URL.revokeObjectURL(url);

                    // Kembalikan tombol ke tampilan semula
                    btn.html(originalContent);
                    btn.removeClass('disabled').css('pointer-events', 'auto');
                })
                .catch(error => {
                    console.error('Download gagal:', error);
                    alert('Maaf, gagal mengunduh sertifikat. Pastikan koneksi stabil.');

                    // Kembalikan tombol ke tampilan semula jika error
                    btn.html(originalContent);
                    btn.removeClass('disabled').css('pointer-events', 'auto');
                });
        });
    });
</script>
<?= $this->endSection(); ?>
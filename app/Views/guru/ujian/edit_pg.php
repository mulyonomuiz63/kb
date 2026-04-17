<?= $this->extend('template/app'); ?>
<?= $this->section('content'); ?>
<?php
use App\Models\UjiansiswaModel;
$UjiansiswaModel = new UjiansiswaModel();
?>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="card card-flush shadow-sm">
            <div class="card-body py-4">
                <div class="table-responsive">
                    <table id="datatable-table-soal" class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-50px text-center">No</th>
                                <th class="min-w-200px">Isi Soal</th>
                                <th class="w-100px text-center">Jawaban</th>
                                <th class="w-100px text-center">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            <?php $no = 1;
                            foreach ($detail_ujian as $rows) : ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td>
                                        <div class="text-gray-800 fw-bold">
                                            <?= $rows->nama_soal; ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-light-success fw-bold fs-7"><?= $rows->jawaban; ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('sw-guru/ujian/edit-soal/' . encrypt_url($rows->id_detail_ujian)); ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Edit Soal">
                                            <i class="ki-duotone ki-pencil fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    // Logic Toggle & Reset tetap dipertahankan
    function toggle_ujian(source) {
        var checkboxes = document.querySelectorAll('#tambah_ujian');
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i] != source)
                checkboxes[i].checked = source.checked;
        }
    }

    function toggle_reset(source) {
        var checkboxes = document.querySelectorAll('#reset_ujian');
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i] != source)
                checkboxes[i].checked = source.checked;
        }
    }

    <?= session()->getFlashdata('pesan'); ?>

    $(document).ready(function() {
        // Initialize DataTable with Metronic Style
        $('#datatable-table-soal').DataTable({
            "info": false,
            "ordering": false,
            "lengthChange": false,
            "pageLength": 10,
            // TAMBAHKAN BAGIAN INI
            "drawCallback": function(settings) {
                // Inisialisasi ulang dropdown Metronic setelah data tampil
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            },
            "language": {
                "search": "",
                "searchPlaceholder": "Cari Peserta...",
                "lengthMenu": "Tampilkan _MENU_",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "paginate": {
                    "previous": "<i class='ki-duotone ki-left fs-2'></i>",
                    "next": "<i class='ki-duotone ki-right fs-2'></i>"
                }
            },
            "dom": `
                <'row'
                    <'col-sm-6 d-flex align-items-center justify-content-start'l>
                    <'col-sm-6 d-flex align-items-center justify-content-end'f>
                >
                <'table-responsive'tr>
                <'row'
                    <'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>
                    <'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>
                >`
        });

        // SUMMERNOTE logic tetap dipertahankan
        setInterval(() => {
            $('.summernote').each(function() {
                if (!$(this).next().hasClass('note-editor')) {
                    $(this).summernote({
                        placeholder: 'Tulis isi soal di sini...',
                        tabsize: 2,
                        height: 120,
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'underline', 'clear']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['table', ['table']],
                            ['insert', ['link', 'picture', 'video']],
                            ['view', ['fullscreen', 'help']]
                        ],
                        callbacks: {
                            onImageUpload: function(image) {
                                uploadImage(image[0], this);
                            },
                            onMediaDelete: function(target) {
                                deleteImage(target[0].src);
                            }
                        }
                    });
                }
            });
        }, 1000);

        function uploadImage(image, which_sum) {
            var data = new FormData();
            data.append("image", image);
            $.ajax({
                url: "<?= base_url('guru/upload_summernote') ?>",
                cache: false,
                contentType: false,
                processData: false,
                data: data,
                type: "POST",
                success: function(url) {
                    $(which_sum).summernote("insertImage", url);
                }
            });
        }

        function deleteImage(src) {
            $.ajax({
                data: { src: src },
                type: "POST",
                url: "<?= base_url('guru/delete_image') ?>",
                cache: false
            });
        }
    });
</script>
<?= $this->endSection(); ?>
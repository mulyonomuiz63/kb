<?php
$scripts =  base_url('assets/app-assets/template/cbt-malela');
?>
<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="<?= $scripts; ?>/assets/js/loader.js"></script>
<script src="<?= $scripts; ?>/assets/js/libs/jquery-3.1.1.min.js"></script>
<script src="<?= $scripts; ?>/bootstrap/js/popper.min.js"></script>
<script src="<?= $scripts; ?>/bootstrap/js/bootstrap.min.js"></script>
<script src="<?= $scripts; ?>/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="<?= $scripts; ?>/plugins/table/datatable/datatables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script src="<?= $scripts; ?>/assets/js/app.js"></script>
<script>
    $(document).ready(function() {
        // App.init() biasanya menginisialisasi perfect scrollbar secara otomatis
        App.init();
    });
</script>

<!-- <script src="<?= $scripts; ?>/assets/js/custom.js"></script> -->
<script src="<?= base_url('assets-landing/js/color-thief.umd.js') ?>"></script>

<script>
    $('.select2').each(function() {
        var $this = $(this);
        var parentModal = $this.closest('.modal'); // Cek apakah di dalam modal

        $this.select2({
            placeholder: "Pilih atau Ketik Baru...",
            tags: true,
            allowClear: true,
            width: '100%',
            // Jika ada modal, pasang dropdown ke modal tersebut
            dropdownParent: parentModal.length ? parentModal : $(document.body),
            createTag: function(params) {
                var term = $.trim(params.term);
                if (term === '') return null;
                return {
                    id: term,
                    text: term,
                    newTag: true
                };
            }
        });
    });
</script>
<script>
     // 4. Konfirmasi Hapus Ujian
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const urlHapus = $(this).data('url');

        Swals.confirm(
            'Apakah Anda yakin?',
            'Data ini akan dihapus secara permanen!',
            function() {
                Swals.loading('Menghapus...', 'Silakan tunggu sebentar');
                window.location.href = urlHapus;
            }
        );
    });
    /**
     * Helper SweetAlert2 Global
     */
    const Swals = {
        // 1. Alert Biasa
        alert: function(title, text, icon = 'success') {
            return Swal.fire({
                title: title,
                text: text,
                icon: icon,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
        },

        // 2. Fungsi LOADING (Tambahkan ini agar error hilang)
        loading: function(title = 'Memproses...', text = 'Mohon tunggu sebentar') {
            Swal.fire({
                title: title,
                text: text,
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        },

        close: function() {
            Swal.close();
        },

        // 3. Konfirmasi
        confirm: function(title, text, callback) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lakukan!',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-danger mx-2',
                    cancelButton: 'btn btn-secondary mx-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            });
        },

        // 4. Toast
        toast: function(title, icon = 'success') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            Toast.fire({
                icon: icon,
                title: title
            });
        }
    };
    $(document).ready(function() {
        // Tangkap Flashdata dari Session CI4
        <?php if (session()->getFlashdata('success')) : ?>
            Swals.alert('Berhasil!', '<?= session()->getFlashdata('success') ?>', 'success');
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            Swals.alert('Gagal!', '<?= session()->getFlashdata('error') ?>', 'error');
        <?php endif; ?>

        <?php if (session()->getFlashdata('warning')) : ?>
            Swals.alert('Perhatian', '<?= session()->getFlashdata('warning') ?>', 'warning');
        <?php endif; ?>
    });
</script>

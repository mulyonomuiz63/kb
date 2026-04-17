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
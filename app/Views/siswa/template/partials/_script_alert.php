<script>
$(document).ready(function() {
    // Ambil pesan dari flashdata PHP
    <?php if (session()->getFlashdata('pesan')) : ?>
        Swal.fire({
            text: "<?= session()->getFlashdata('pesan') ?>",
            icon: "info",
            buttonsStyling: false,
            confirmButtonText: "Ok",
            customClass: {
                confirmButton: "btn btn-primary"
            }
        });
    <?php endif; ?>

    // Jika Anda punya pesan sukses (misal setelah simpan data)
    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({
            text: "<?= session()->getFlashdata('success') ?>",
            icon: "success",
            buttonsStyling: false,
            confirmButtonText: "Ok",
            customClass: {
                confirmButton: "btn btn-success"
            }
        });
    <?php endif; ?>
});
</script>
<script>
    const MyAlert = {
    // 1. Alert Loading (Block UI)
    showLoading: function (message = 'Mohon tunggu...') {
        Swal.fire({
            text: message,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    },

    // 2. Alert Suksess/Error/Info (Auto Close)
    showToast: function (type = 'success', message = 'Berhasil!') {
        Swal.fire({
            text: message,
            icon: type, // success, error, warning, info
            buttonsStyling: false,
            confirmButtonText: "Ok, Mengerti!",
            customClass: {
                confirmButton: "btn btn-primary"
            }
        });
    },

    // 3. Konfirmasi Hapus (Atau aksi berbahaya lainnya)
    confirmDelete: function (title, message, callback) {
        Swal.fire({
            title: title || 'Apakah Anda yakin?',
            text: message || "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batalkan",
            customClass: {
                confirmButton: "btn btn-danger",
                cancelButton: "btn btn-active-light"
            }
        }).then((result) => {
            if (result.isConfirmed) {
                callback(); // Jalankan fungsi hapus jika user klik Ya
            }
        });
    },

    // 4. Tutup Alert Apapun
    close: function () {
        Swal.close();
    }
};
</script>

<script>
$('#btn-delete').on('click', function() {
    MyAlert.confirmDelete(
        'Hapus Paket?', 
        'Anda akan menghapus Paket Brevet A, yakin?', 
        function() {
            // Logika penghapusan asli diletakkan di sini (misal: Ajax Delete)
            MyAlert.showLoading('Menghapus data...');
            
            // Contoh simulasi sukses
            setTimeout(() => {
                MyAlert.showToast('success', 'Data berhasil dihapus dari sistem.');
            }, 1000);
        }
    );
});

$('#btn-proses').on('click', function() {
    MyAlert.showLoading('Sedang memproses pendaftaran...');
    
    // Simulasi Ajax
    setTimeout(() => {
        MyAlert.close(); // Tutup loading
        MyAlert.showToast('success', 'Pendaftaran Anda berhasil!');
    }, 2000);
});
</script>
<script>
    /**
     * Polling untuk Update UI secara Real-time
     */
    async function updateNotificationUI() {
        try {
            const response = await fetch('<?= base_url("api/notifications/update-ui") ?>', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Penting agar CI4 mengenali ini AJAX
                }
            });

            // Cek jika status bukan 200 OK
            if (!response.ok) {
                console.warn("Polling dilewati: Server merespon status " + response.status);
                return;
            }

            const html = await response.text();

            // Validasi apakah yang diterima benar-benar HTML bukan JSON error
            if (html.trim() !== "" && !html.startsWith('{')) {
                const wrapper = $('#notification-wrapper');
                if (wrapper.length) {
                    wrapper.html(html);
                    if (typeof KTMenu !== 'undefined') {
                        KTMenu.createInstances();
                    }
                    if (typeof initNotificationActions === 'function') {
                        initNotificationActions();
                    }
                }
            }
        } catch (err) {
            // Ini akan menangkap error TypeError jika ada masalah manipulasi DOM
            console.error("Gagal update UI:", err);
        }
    }

    // Jalankan polling setiap 30 detik
    setInterval(updateNotificationUI, 30000);

    /**
     * Event Handlers (Delegasi ke Document)
     */
    $(document).ready(function() {

        // --- 1. Mark Single As Read ---
        $(document).on('click', '.btn-mark-read', async function(e) {
            e.preventDefault();

            const uuid = $(this).data('id');
            const fullUrl = $(this).attr('href');
            const targetUrl = '<?= base_url("api/notifications/mark-as-read") ?>';

            // Efek loading
            $(this).closest('.notif-item').css('opacity', '0.5');

            try {
                const formData = new FormData();
                formData.append('uuid', uuid);
                formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                await fetch(targetUrl, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
            } catch (err) {
                console.error("Gagal menandai baca:", err);
            } finally {
                // Redirect tetap dilakukan baik fetch berhasil atau gagal
                if (fullUrl && fullUrl !== '#') {
                    window.location.href = fullUrl;
                }
            }
        });

        // --- 2. Mark All As Read ---
        $(document).on('click', '#btn-read-all', async function(e) {
            e.preventDefault();

            const targetUrl = '<?= base_url("api/notifications/mark-all-read") ?>';
            const btn = $(this);
            const originalText = btn.html();

            // Loading State
            btn.html('<span class="spinner-border spinner-border-sm me-2"></span> Memproses...').prop('disabled', true);

            try {
                const formData = new FormData();
                formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                const response = await fetch(targetUrl, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                if (response.ok) {
                    // Sembunyikan badge secara instan
                    $('#main-notif-badge').fadeOut();

                    // Refresh UI untuk membersihkan list
                    await updateNotificationUI();

                    btn.html('<i class="ki-outline ki-check fs-5 me-1"></i> Selesai');
                    setTimeout(() => {
                        btn.html(originalText).prop('disabled', false);
                    }, 2000);
                }
            } catch (err) {
                btn.html(originalText).prop('disabled', false);
            }
        });
    });
</script>
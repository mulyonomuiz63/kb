<script>
    let currentCsrfToken = '<?= csrf_hash() ?>'; // Simpan di variabel global

    function updateNotification() {
        fetch('<?= base_url("notif/get-data") ?>', {
                referrerPolicy: 'no-referrer'
            })
            .then(response => {
                // AMBIL TOKEN BARU DARI HEADER (Jika CI4 dikonfigurasi mengirim balik token)
                // Atau pastikan controller notif/get-data mengirim token di dalam JSON res
                return response.json();
            })
            .then(res => {
                // Update token jika server mengirimkan token baru dalam response JSON
                if (res.csrf_token) currentCsrfToken = res.csrf_token;

                const listContainer = document.getElementById('notification-list');
                const countBadge = document.getElementById('main-notif-badge');
                const loader = document.getElementById('notif-loader');

                const unreadCount = parseInt(res.unread_count) || 0;

                if (unreadCount > 0) {
                    countBadge.innerText = unreadCount > 99 ? '99+' : unreadCount;
                    countBadge.style.setProperty('display', 'flex', 'important');
                    countBadge.classList.add('animate__animated', 'animate__bounceIn');
                } else {
                    countBadge.style.display = 'none';
                }

                if (loader) loader.style.display = 'none';

                if (res.data && res.data.length > 0) {
                    let html = '';
                    res.data.forEach(item => {
                        const isUnread = item.is_read == 0 ? 'bg-light-success' : '';
                        html += `
                        <div class="notif-item ${isUnread}">
                            <div class="d-flex align-items-start">
                                <div class="mr-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                         style="width: 35px; height: 35px;">
                                        <i class="fas fa-comment-alt" style="font-size: 0.8rem;"></i>
                                    </div>
                                </div>
                                <div style="flex: 1;">
                                    <a href="${item.link}" 
                                       class="btn-mark-read font-weight-bold text-dark fs-7 d-block"
                                       data-id="${item.id}" 
                                       style="line-height: 1.3; text-decoration: none;">
                                        ${item.title}
                                    </a>
                                    <div class="text-muted fs-8 mt-1">${item.message}</div>
                                </div>
                            </div>
                        </div>`;
                    });
                    listContainer.innerHTML = html;
                } else {
                    listContainer.innerHTML = '<div class="text-center py-10 text-muted fs-7">Tidak ada notifikasi baru</div>';
                }
            })
            .catch(err => console.error("Error fetching notifications:", err));
    }

    // Handler untuk klik satuan
    $(document).on('click', '.btn-mark-read', async function(e) {
        e.preventDefault();
        const uuid = $(this).data('id');
        const fullUrl = $(this).attr('href');
        const targetUrl = '<?= base_url("notif/mark-read") ?>';

        $(this).closest('.notif-item').css('opacity', '0.5');

        try {
            const formData = new FormData();
            formData.append('uuid', uuid);
            // MENGGUNAKAN VARIABEL YANG SELALU UPDATE
            formData.append('<?= csrf_token() ?>', currentCsrfToken);

            const response = await fetch(targetUrl, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                referrerPolicy: 'no-referrer',
                body: formData
            });

            const result = await response.json();
            if (result.csrf_token) currentCsrfToken = result.csrf_token; // Simpan token baru

        } catch (err) {
            console.error("Gagal menandai baca:", err);
        } finally {
            window.location.href = fullUrl;
        }
    });

    // Handler untuk Tandai Semua Dibaca
    $(document).on('click', '#btn-read-all', async function(e) {
        e.preventDefault();
        const targetUrl = '<?= base_url("notif/mark-all-read") ?>';
        const btn = $(this);
        const originalText = btn.html();

        btn.html('<span class="spinner-border spinner-border-sm mr-2"></span> Memproses...').prop('disabled', true);

        try {
            const formData = new FormData();
            // MENGGUNAKAN VARIABEL YANG SELALU UPDATE
            formData.append('<?= csrf_token() ?>', currentCsrfToken);

            const response = await fetch(targetUrl, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const result = await response.json();
            if (result.csrf_token) currentCsrfToken = result.csrf_token; // Simpan token baru

            if (response.ok) {
                $('#main-notif-badge').css('display', 'none', 'important');
                updateNotification();
                btn.html('<i class="fas fa-check"></i> Selesai');
                setTimeout(() => {
                    btn.html(originalText).prop('disabled', false);
                }, 2000);
            } else {
                btn.html(originalText).prop('disabled', false);
            }
        } catch (err) {
            btn.html(originalText).prop('disabled', false);
        }
    });
    // Jalankan otomatis
    updateNotification();
    const notifInterval = setInterval(updateNotification, 30000);
    $(document).on('submit', 'form', function() {
        clearInterval(notifInterval);
    });
</script>
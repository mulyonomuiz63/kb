<script>
    // Variabel Global Notifikasi
    let notifCsrfName = '<?= csrf_token() ?>';
    let currentCsrfToken = '<?= csrf_hash() ?>';

    /**
     * Mengambil data notifikasi terbaru
     */
    function updateNotification() {
        fetch('<?= base_url("notif/get-data") ?>', {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(res => {
            if (res.csrf_token) currentCsrfToken = res.csrf_token;

            const listContainer = document.getElementById('notification-list');
            const mainBadge = document.getElementById('main-notif-badge');
            const headerBadge = document.getElementById('header-unread-count');
            const loader = document.getElementById('notif-loader');

            const unreadCount = parseInt(res.unread_count) || 0;

            if (mainBadge && headerBadge) {
                if (unreadCount > 0) {
                    mainBadge.classList.remove('d-none');
                    headerBadge.innerText = `${unreadCount > 99 ? '99+' : unreadCount} Baru`;
                    headerBadge.classList.remove('d-none');
                } else {
                    mainBadge.classList.add('d-none');
                    headerBadge.classList.add('d-none');
                }
            }

            if (loader) loader.style.display = 'none';
            if (!listContainer) return;

            if (res.data && res.data.length > 0) {
                let html = '';
                res.data.forEach(item => {
                    const isUnread = item.is_read == 0 ? 'bg-light-primary border border-primary border-opacity-25' : 'bg-light';
                    const isDiskusi = (item.title && item.title.toLowerCase().includes('diskusi')) || (item.link && item.link.includes('diskusi'));
                    const iconClass = isDiskusi ? 'ki-messages text-primary' : 'ki-notification-status text-primary';
                    const timeStr = item.created_at ? item.created_at.substring(0, 16) : '';

                    html += `
                    <a href="${item.link || '#'}" data-id="${item.id}" class="btn-mark-read d-flex align-items-start mb-3 p-3 rounded-3 text-decoration-none ${isUnread}" style="transition: all 0.2s ease;">
                        <div class="symbol symbol-35px me-3 flex-shrink-0 mt-1">
                            <span class="symbol-label bg-white shadow-xs">
                                <i class="ki-duotone ${iconClass} fs-2">
                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                                </i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-gray-900 text-hover-primary fw-bold fs-7 text-truncate">${item.title || 'Notifikasi'}</span>
                            </div>
                            <span class="text-gray-600 fw-semibold d-block fs-8 mb-1" style="line-height: 1.35;">${item.message || ''}</span>
                            ${timeStr ? `<span class="text-muted fs-9"><i class="ki-duotone ki-time fs-9 me-1"><span class="path1"></span><span class="path2"></span></i>${timeStr}</span>` : ''}
                        </div>
                    </a>`;
                });
                listContainer.innerHTML = html;
            } else {
                listContainer.innerHTML = `
                <div class="d-flex flex-column flex-center py-10">
                    <i class="ki-duotone ki-notification-on fs-3x text-gray-300 mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    <span class="text-gray-400 fs-7 fw-bold">Tidak ada notifikasi baru</span>
                </div>`;
            }
        })
        .catch(err => console.error("Error fetching notifications:", err));
    }

    /**
     * Handler: Klik Satuan Notifikasi
     */
    $(document).on('click', '.btn-mark-read', async function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const href = $(this).attr('href');
        
        const formData = new FormData();
        formData.append('uuid', id);
        formData.append(notifCsrfName, currentCsrfToken);

        try {
            const response = await fetch('<?= base_url("notif/mark-read") ?>', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            const result = await response.json();
            if (result.csrf_token) currentCsrfToken = result.csrf_token;
        } catch (err) {
            console.error("Gagal update read status:", err);
        } finally {
            if (href && href !== '#') {
                window.location.href = href;
            } else {
                updateNotification();
            }
        }
    });

    /**
     * Handler: Tandai Semua Dibaca
     */
    $(document).on('click', '#btn-read-all', async function(e) {
        e.preventDefault();
        const btn = $(this);
        const originalContent = btn.html();

        btn.html('<span class="spinner-border spinner-border-sm me-2"></span> Memproses...').prop('disabled', true);

        const formData = new FormData();
        formData.append(notifCsrfName, currentCsrfToken);

        try {
            const response = await fetch('<?= base_url("notif/mark-all-read") ?>', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            const result = await response.json();
            if (result.csrf_token) currentCsrfToken = result.csrf_token;

            if (response.ok) {
                updateNotification();
            }
        } catch (err) {
            console.error("Gagal mark all read:", err);
        } finally {
            btn.html(originalContent).prop('disabled', false);
        }
    });

    // Inisialisasi
    updateNotification();
    
    // Realtime Interval (15 Detik)
    const notifInterval = setInterval(updateNotification, 15000);

    $(window).on('beforeunload', () => clearInterval(notifInterval));
</script>
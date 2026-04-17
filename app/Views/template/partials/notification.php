<script>
    // Variabel Global
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
            // Update CSRF jika dikirim dari server
            if (res.csrf_token) currentCsrfToken = res.csrf_token;

            const listContainer = document.getElementById('notification-list');
            const mainBadge = document.getElementById('main-notif-badge');
            const headerBadge = document.getElementById('header-unread-count');
            const loader = document.getElementById('notif-loader');

            const unreadCount = parseInt(res.unread_count) || 0;

            // Update Badge Status
            if (unreadCount > 0) {
                mainBadge.classList.remove('d-none');
                headerBadge.innerText = `${unreadCount > 99 ? '99+' : unreadCount} Baru`;
                headerBadge.classList.remove('d-none');
            } else {
                mainBadge.classList.add('d-none');
                headerBadge.classList.add('d-none');
            }

            if (loader) loader.style.display = 'none';

            // Render HTML List
            if (res.data && res.data.length > 0) {
                let html = '';
                res.data.forEach(item => {
                    const isUnread = item.is_read == 0 ? 'bg-light-primary' : '';
                    html += `
                    <div class="d-flex align-items-center mb-5 p-3 rounded ${isUnread}">
                        <div class="symbol symbol-35px me-4">
                            <span class="symbol-label bg-light-primary">
                                <i class="ki-duotone ki-notification-status fs-2 text-primary">
                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                                </i>
                            </span>
                        </div>
                        
                        <div class="mb-1 flex-grow-1">
                            <a href="${item.link}" 
                               class="btn-mark-read text-gray-800 text-hover-primary fw-bold fs-6 d-block" 
                               data-id="${item.id}">
                                ${item.title}
                            </a>
                            <span class="text-gray-400 fw-semibold d-block fs-8">${item.message}</span>
                        </div>
                    </div>`;
                });
                listContainer.innerHTML = html;
            } else {
                listContainer.innerHTML = `
                <div class="d-flex flex-column flex-center py-10">
                    <i class="ki-duotone ki-notification-on fs-3x text-gray-300 mb-4"><span class="path1"></span><span class="path2"></span></i>
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
        formData.append(csrfName, currentCsrfToken);

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
            window.location.href = href;
        }
    });

    /**
     * Handler: Tandai Semua Dibaca
     */
    $(document).on('click', '#btn-read-all', async function(e) {
        e.preventDefault();
        const btn = $(this);
        const originalContent = btn.html();

        btn.html('<span class="spinner-border spinner-border-sm me-2"></span> Loading...').prop('disabled', true);

        const formData = new FormData();
        formData.append(csrfName, currentCsrfToken);

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
                Swal.fire({
                    text: "Semua notifikasi telah dibaca",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok",
                    customClass: { confirmButton: "btn btn-primary" }
                });
            }
        } catch (err) {
            console.error("Gagal mark all read:", err);
        } finally {
            btn.html(originalContent).prop('disabled', false);
        }
    });

    // Inisialisasi
    updateNotification();
    
    // Realtime Interval (30 Detik)
    const notifInterval = setInterval(updateNotification, 30000);

    // Hentikan interval saat user meninggalkan halaman/form submit (Opsional)
    $(window).on('beforeunload', () => clearInterval(notifInterval));
</script>
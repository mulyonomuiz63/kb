<?php if (service('request')->getUri()->getPath() === 'sw-siswa/ujian/lihat-pg') : ?>
    <script src="https://topcs.id/widget.js" data-tenant="kelas-brevet" data-mode="bubble" data-position="right" data-color="#2563eb"></script>
<?php endif; ?>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="<?= base_url() ?>assets/peserta/plugins/global/plugins.bundle.js"></script>
<script src="<?= base_url() ?>assets/peserta/js/scripts.bundle.js"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Vendors Javascript(used for this page only)-->
<script src="<?= base_url() ?>assets/peserta/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>

<script src="<?= base_url() ?>assets/peserta/plugins/custom/datatables/datatables.bundle.js"></script>
<!--end::Vendors Javascript-->
<!--begin::Custom Javascript(used for this page only)-->
<script src="<?= base_url() ?>assets/peserta/js/widgets.bundle.js"></script>
<script src="<?= base_url() ?>assets/peserta/js/custom/widgets.js"></script>
<script src="<?= base_url() ?>assets/peserta/js/custom/apps/chat/chat.js"></script>
<script src="<?= base_url() ?>assets/peserta/js/custom/utilities/modals/upgrade-plan.js"></script>
<script src="<?= base_url() ?>assets/peserta/js/custom/utilities/modals/new-target.js"></script>
<script src="<?= base_url() ?>assets/peserta/js/custom/utilities/modals/create-app.js"></script>
<script src="<?= base_url() ?>assets/peserta/js/custom/utilities/modals/users-search.js"></script>



<!-- untuk antisipasi error pada modal ketika di close -->
<script>
    $(document).ready(function() {

        // 1. GLOBAL FOCUS PROTECTION (Bootstrap 5 Compatible)
        // Menangkap klik pada semua tombol penutup modal dengan atribut BS5
        $(document).on('click', '[data-bs-dismiss="modal"], .btn-close, [data-kt-users-modal-action="close"]', function() {
            // Pindahkan fokus ke body agar tidak tertinggal di elemen yang akan disembunyikan
            document.body.focus();
        });

        // 2. GLOBAL MUTATION OBSERVER (Inert & Aria-Hidden Fix)
        // Menangani konflik antara fokus elemen dan aksesibilitas pembaca layar
        const globalModalObserver = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                const target = mutation.target;

                // Perbaikan untuk aria-hidden yang muncul otomatis
                if (mutation.attributeName === 'aria-hidden' && target.getAttribute('aria-hidden') === 'true') {
                    target.removeAttribute('aria-hidden');
                }

                // Tambahan untuk Metronic: Mencegah body/root terkunci secara salah
                if (mutation.attributeName === 'class' && document.body.classList.contains('modal-open')) {
                    const appRoot = document.getElementById('kt_app_root');
                    if (appRoot && appRoot.hasAttribute('aria-hidden')) {
                        appRoot.removeAttribute('aria-hidden');
                    }
                }
            });
        });

        globalModalObserver.observe(document.body, {
            attributes: true,
            subtree: true,
            attributeFilter: ['aria-hidden', 'class']
        });

        // 3. GLOBAL MODAL CLEANUP (Bootstrap 5 Events)
        // Metronic 8 menggunakan Bootstrap 5, event names tetap sama namun target layout berbeda
        $(document).on('show.bs.modal hide.bs.modal hidden.bs.modal', '.modal', function() {
            const $this = $(this);

            // Hapus aria-hidden dari modal itu sendiri
            $this.removeAttr('aria-hidden');

            // Target spesifik layout Metronic 8 Demo 34
            // #kt_app_root adalah pembungkus utama di Demo 34
            $('#kt_app_root, #kt_app_page, #kt_app_wrapper, body').each(function() {
                if (this.hasAttribute('aria-hidden')) {
                    this.removeAttribute('aria-hidden');
                }
            });

            // Opsi: Gunakan 'inert' daripada aria-hidden (Saran Chrome DevTools)
            const appRoot = document.getElementById('kt_app_root');
            if (appRoot) {
                if ($this.hasClass('show')) {
                    // Saat modal muncul, buat konten belakang tidak bisa difokus (inert)
                    // tapi jangan gunakan aria-hidden agar tidak error
                    appRoot.setAttribute('inert', 'true');
                } else {
                    appRoot.removeAttribute('inert');
                }
            }
        });

        // 4. FIX: Disable Bootstrap Enforce Focus (Sering menyebabkan loop fokus di Metronic)
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            bootstrap.Modal.prototype._enforceFocus = function() {};
        }
    });
</script>
<!-- midtrans -->
 <?php if (strtolower(setting('midtrans_is_production')) == 'true'): ?>
    <script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="<?= setting('midtrans_client_key') ?>"></script>
<?php else: ?>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= setting('midtrans_client_key') ?>"></script>
<?php endif; ?>
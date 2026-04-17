<script>
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>';
</script>
<script>
    var hostUrl = "<?= base_url('assets/admin/') ?>";
</script>

<script src="<?= base_url('assets/admin/plugins/global/plugins.bundle.js') ?>"></script>
<script src="<?= base_url('assets/admin/js/scripts.bundle.js') ?>"></script>
<script src="<?= base_url('assets/admin/plugins/custom/fullcalendar/fullcalendar.bundle.js') ?>"></script>
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
<script src="<?= base_url('assets/admin/plugins/custom/datatables/datatables.bundle.js') ?>"></script>
<script src="<?= base_url('assets/admin/js/widgets.bundle.js') ?>"></script>
<script src="<?= base_url('assets/admin/js/custom/widgets.js') ?>"></script>
<script src="<?= base_url('assets/admin/js/custom/apps/chat/chat.js') ?>"></script>
<script src="<?= base_url('assets/admin/js/custom/utilities/modals/upgrade-plan.js') ?>"></script>
<script src="<?= base_url('assets/admin/js/custom/utilities/modals/create-campaign.js') ?>"></script>
<script src="<?= base_url('assets/admin/js/custom/utilities/modals/create-app.js') ?>"></script>
<script src="<?= base_url('assets/admin/js/custom/utilities/modals/users-search.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    $(document).ready(function() {
        // 1. Inisialisasi Global untuk Select2 di dalam Modal
        $(document).on('shown.bs.modal', '.modal', function() {
            const modalElement = $(this);

            // Cari semua select dengan class .select2-global atau data-control="select2"
            modalElement.find('[data-control="select2"], .select2-global').each(function() {
                const $this = $(this);

                $this.select2({
                    dropdownParent: modalElement, // Otomatis nempel ke modal yang sedang aktif
                    width: '100%',
                    placeholder: $this.data('placeholder') || "Pilih...",
                    allowClear: true,
                    tags: $this.data('tags') || false, // Aktifkan jika ada atribut data-tags="true"
                });
            });
        });

        // 2. FIX GLOBAL: Paksa fokus agar bisa diketik (Masalah utama Bootstrap 5)
        $(document).on('select2:open', () => {
            const searchField = document.querySelector('.select2-container--open .select2-search__field');
            if (searchField) {
                searchField.focus();
            }
        });
    });
</script>
<script>
    // Inisialisasi Tooltip Bootstrap 5 (Bawaan Metronic)
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
    $.fn.dataTable.ext.errMode = 'none';
</script>
<?= $this->include('template/partials/alert') ?>
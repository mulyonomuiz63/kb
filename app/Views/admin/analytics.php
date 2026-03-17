<?= $this->extend('template/app'); ?>
<?= $this->section('css'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>
<?= $this->include('template/sidebar/admin'); ?>
<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row">
            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">Pengunjung 7 Hari Terakhir</div>
                    <div class="card-body">
                        <canvas id="visitorChart"></canvas>
                    </div>
                </div>
            </div>
    
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header bg-success text-white">Proporsi Top Pages</div>
                    <div class="card-body">
                        <canvas id="pagePieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="card mb-4">
            <div class="card-header bg-info text-white">Halaman Terpopuler</div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Halaman</th>
                            <th>Jumlah Kunjungan</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($topPages as $page): ?>
                        <tr>
                            <td><?= $page->page_url ?></td>
                            <td><?= $page->total ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    
        <div class="card mb-5">
            <div class="card-header bg-dark text-white">Log Kunjungan Terbaru</div>
            <div class="card-body table-responsive">
                <table class="table table-sm table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Waktu</th>
                            <th>IP Address</th>
                            <th>Page URL</th>
                            <th>Referrer</th>
                            <th>User Agent</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($logs as $row): ?>
                        <tr>
                            <td><?= $row->created_at ?></td>
                            <td><?= $row->ip_address ?></td>
                            <td><?= $row->page_url ?></td>
                            <td><?= $row->referrer ?></td>
                            <td><?= $row->user_agent ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    

    <div class="footer-wrapper">
        <div class="footer-section f-section-1">
            <p class="terms-conditions"><?= copyright() ?></p>
        </div>
        <div class="footer-section f-section-2">
           
        </div>
    </div>
</div>

<script>
    const visitorCtx = document.getElementById('visitorChart').getContext('2d');
    new Chart(visitorCtx, {
        type: 'line',
        data: {
            labels: <?= $labels ?>,
            datasets: [{
                label: 'Jumlah Pengunjung',
                data: <?= $totals ?>,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: { responsive: true }
    });

    const pageLabels = <?= json_encode(array_map(fn($p) => $p->page_url, $topPages)) ?>;
    const pageTotals = <?= json_encode(array_map(fn($p) => $p->total, $topPages)) ?>;

    const pieCtx = document.getElementById('pagePieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: pageLabels,
            datasets: [{
                data: pageTotals,
                backgroundColor: [
                    'rgba(255,99,132,0.7)','rgba(54,162,235,0.7)','rgba(255,206,86,0.7)',
                    'rgba(75,192,192,0.7)','rgba(153,102,255,0.7)','rgba(255,159,64,0.7)',
                    'rgba(199,199,199,0.7)','rgba(201,203,207,0.7)','rgba(100,181,246,0.7)',
                    'rgba(255,138,101,0.7)'
                ]
            }]
        },
        options: { responsive: true }
    });
</script>
<!--  END CONTENT AREA  -->
<?= $this->endSection(); ?>
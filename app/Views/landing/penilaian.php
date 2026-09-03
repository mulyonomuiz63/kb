<?= $this->extend('landing/template'); ?>
<?= $this->section('content'); ?>
<div class="section section-padding-02 mb-4" id="penilaian">
    <div class="container">
        <div class="row justify-content-center">
            <!-- Header Section -->
            <div class="col-lg-8 text-center mb-4">
                <span class="badge bg-opacity-10 text-white px-3 py-2 rounded-pill fw-semibold mb-2" style="background-color: #29459A;">Standar Akademik</span>
                <h3 class="fw-bold text-dark">Sistem Penilaian</h3>
                <p class="text-muted">Berikut ini adalah tabel standar sistem penilaian yang berlaku di KelasBrevet.</p>
            </div>

            <!-- Table Card Container -->
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-center">
                            <thead style="background-color: #29459A;" class="text-white">
                                <tr>
                                    <th class="py-3 text-white fw-semibold">NILAI</th>
                                    <th class="py-3 text-white fw-semibold">HURUF</th>
                                    <th class="py-3 text-white fw-semibold">PREDIKAT</th>
                                    <th class="py-3 text-white fw-semibold">KETERANGAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold text-dark">0-59</td>
                                    <td><span class="badge bg-danger bg-opacity-10 text-white px-3 py-2 rounded-pill fw-bold">D</span></td>
                                    <td class="fw-medium text-dark">KURANG</td>
                                    <td><span class="badge bg-danger text-white px-3 py-2 rounded-pill">TIDAK LULUS</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">60-69</td>
                                    <td><span class="badge bg-warning bg-opacity-10 text-white px-3 py-2 rounded-pill fw-bold">C</span></td>
                                    <td class="fw-medium text-dark">CUKUP</td>
                                    <td><span class="badge bg-success text-white px-3 py-2 rounded-pill">LULUS</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">70-79</td>
                                    <td><span class="badge bg-info bg-opacity-10 text-white px-3 py-2 rounded-pill fw-bold">B</span></td>
                                    <td class="fw-medium text-dark">CUKUP BAIK</td>
                                    <td><span class="badge bg-success text-white px-3 py-2 rounded-pill">LULUS</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">80-89</td>
                                    <td><span class="badge bg-primary bg-opacity-10 text-white px-3 py-2 rounded-pill fw-bold">A</span></td>
                                    <td class="fw-medium text-dark">BAIK</td>
                                    <td><span class="badge bg-success text-white px-3 py-2 rounded-pill">LULUS</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">90-100</td>
                                    <td><span class="badge bg-success bg-opacity-10 text-white px-3 py-2 rounded-pill fw-bold">A+</span></td>
                                    <td class="fw-medium text-dark">SANGAT BAIK</td>
                                    <td><span class="badge bg-success text-white px-3 py-2 rounded-pill">LULUS</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
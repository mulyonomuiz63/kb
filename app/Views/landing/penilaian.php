<?= $this->extend('landing/template'); ?>
<?= $this->section('content'); ?>
 <div class="section section-padding-02 mb-4" id="penilaian">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h6>Sistem Penilaian</h6>
                <span>Berikut ini adalah tabel sistem penilaian di KelasBrevet</span>
            </div>
            <div class="col-12 mt-4 card call-to-action-wrapper rounded table-responsive-sm">
                <table class="table  text-center no-border" style="font-size:12px">
                    <tr style="background-color: blue;">
                        <td class="text-white">NILAI</td>
                        <td class="text-white">HURUF</td>
                        <td class="text-white">PREDIKAT</td>
                        <td class="text-white">KETERANGAN</td>
                    </tr>
                    <tr>
                        <td>0-59</td>
                        <td>D</td>
                        <td>KURANG</td>
                        <td>TIDAK LULUS</td>
                    </tr>
                    <tr>
                        <td>60-69</td>
                        <td>C</td>
                        <td>CUKUP</td>
                        <td>LULUS</td>
                    </tr>
                    <tr>
                        <td>70-79</td>
                        <td>B</td>
                        <td>CUKUP BAIK</td>
                        <td>LULUS</td>
                    </tr>
                    <tr>
                        <td>80-89</td>
                        <td>A</td>
                        <td>BAIK</td>
                        <td>LULUS</td>
                    </tr>
                    <tr>
                        <td>90-100</td>
                        <td>A+</td>
                        <td>SANGAT BAIK</td>
                        <td>LULUS</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
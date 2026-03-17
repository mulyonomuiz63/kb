<?php

namespace App\Controllers;

class Download extends BaseController
{
    public function file($nama)
    {
        return $this->response->download('assets/app-assets/file/' . $nama, NULL);
    }

    public function excel_pg()
    {
        return $this->response->download('assets/app-assets/file-excel/template.xlsx', NULL);
    }
    public function excel_soal_pg()
    {
        return $this->response->download('assets/app-assets/file-excel/template_soal.xlsx', NULL);
    }
    public function pdf($range = null)
    {

        // supaya tidak timeout
        set_time_limit(0);
        ini_set('memory_limit', '10024M');

        if (!$range) {
            return "Range tidak ada";
        }

        $exp = explode('-', $range);

        if (count($exp) != 2) {
            return "Format harus seperti 1-10";
        }

        $start = (int)$exp[0];
        $end   = (int)$exp[1];

        // 1.abm
        // 2.asi
        // 3.mkd
        // 4.ak
        // 5.bde
        // 6.hbp
        // 7.aml
        // 8.mkl
        // 9.sipi
        // 10.mp
        // 11.aa
        // 12.msk
        // 13.pk_19

        $baseUrl = "https://web.iaiglobal.or.id/assets/materi/Sertifikasi/CA/modul/pk_19/files/mobile/";

        $pdf = new \FPDF('P', 'mm', 'A4');

        for ($i = $start; $i <= $end; $i++) {

            $imageUrl = $baseUrl . $i . ".jpg";

            $img = @file_get_contents($imageUrl);

            if ($img !== false) {

                // simpan sementara
                $temp = WRITEPATH . "temp_" . $i . ".jpg";
                file_put_contents($temp, $img);

                $pdf->AddPage();

                // gambar full halaman
                $pdf->Image($temp, 0, 0, 210);

                // hapus file sementara
                unlink($temp);
            }
        }

        $filename = "materi_" . $start . "_" . $end . ".pdf";

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody($pdf->Output('S'));
    }
}

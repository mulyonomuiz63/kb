<?php
class MYPDF extends TCPDF
{
    protected $company;

    public function setCompany($var)
    {
        $this->company = $var;
    }
    public function Header()
    {
        $data = $this->company;
        $file = site_url('uploads/surat/thumbnails/');
        $this->SetY(15);
        // $this->SetFont('bookman_old_style_', 'B');
        $judul = strtoupper($data->nama_ujian);
        $nama_kelas = strtoupper($data->nama_kelas);

        $isi_header = "
        <table>
            <tr>
                <td style=\"width: 10%; text-align:left;\"> <img src=\" \" > </td>
                <td style=\"width: 80%; text-align:center;\">
                    <table>
                        <tr>
                            <td  style=\"font-size: 16px;font-weight: bold;\">$judul </td>
                        </tr>
                        <tr>
                            <td style=\"font-size: 12px;\">KELAS: $nama_kelas </td>
                        </tr>
                    </table>
                </td>
                <td style=\"width:10%;\"></td>
            </tr>

        </table>
        <hr style=\"border-bottom: 1pt solid black;\">
        ";
        $this->writeHTML($isi_header, true, false, false, false, '');
    }

    // Page footer
    public function Footer()
    {
        $numberPage = $this->getAliasNumPage();
        $aliasPage = $this->getAliasNbPages();
        $date = date('d/m/Y');
        $isi_footer = "
        <table>
            <tr>
                <td style=\"width: 80%; text-align:left;\">kelasbrevet.com | tanggal cetak $date</td>

                <td style=\"width:20%;\"> Page  $numberPage / $aliasPage</td>
            </tr>

        </table>
        ";
        $this->writeHTML($isi_footer, true, false, false, false, '');
    }
}

// create new PDF document
$pdf = new MYPDF('P', PDF_UNIT, 'F4', true, 'UTF-8', false);

$pdf->setCompany($ujian);
// set default header data
// $pdf->SetFont('bookman_old_style_', 'B', 11);

// if ($data->status_kop == '1') {
//     $pdf->SetHeaderData($data->img_kop, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setPrintHeader(true);
$pdf->SetMargins(20, 40, 20);
// } else {
//     $pdf->setPrintHeader(false);
//     $pdf->SetMargins(20, 40, 20, true);
// }
// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);


$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->AddPage();
// output the HTML content
$pdf->writeHTML($view, true, false, false, false, '');

$response;
//Close and output PDF document
$pdf->Output("$file.pdf", 'I');

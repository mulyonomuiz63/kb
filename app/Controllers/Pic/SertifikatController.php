<?php

namespace App\Controllers\Pic;

use App\Controllers\BaseController;
use App\Libraries\Emailer;
use App\Libraries\Pdf;
use App\Models\SiswaModel;
use App\Models\UjianMasterModel;
use App\Models\UjianModel;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class SertifikatController extends BaseController
{
    protected $siswaModel;
    protected $ujianModel;
    protected $ujianMasterModel;
    protected $emailer;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->ujianModel = new UjianModel();
        $this->ujianMasterModel = new UjianMasterModel();
        $this->emailer = new Emailer();
    }

    public function brevetAb($id, $jenis = "")
    {
        // 1. DATA PREPARATION
        $id_siswa = decrypt_url($id);
        $hasilUjian = $this->ujianModel->getByIdsiswaSertifikat($id_siswa); // Materi & Nilai
        $siswa = $this->siswaModel->where('id_siswa', $id_siswa)->get()->getRowObject(); // Gunakan first() untuk RowObject CI4

        if (!$hasilUjian || !$siswa) {
            return "Anda belum memiliki sertifikat brevet AB.";
        }

        // Kalkulasi Nilai & Tanggal
        $totalNilaiUjian = 0;
        $countMateri = count($hasilUjian);
        $tgl_awal = null;
        $tgl_akhir = null;

        foreach ($hasilUjian as $row) {
            $totalNilaiUjian += $row->nilai_ujian;
            // Cari range tanggal ujian
            $currentStart = strtotime($row->start_ujian);
            $currentEnd = strtotime($row->end_ujian);
            if (!$tgl_awal || $currentStart < $tgl_awal) $tgl_awal = $currentStart;
            if (!$tgl_akhir || $currentEnd > $tgl_akhir) $tgl_akhir = $currentEnd;
        }

        $hasilTotal = ($countMateri > 0) ? round($totalNilaiUjian / $countMateri) : 0;
        $predikat = $this->_getPredikat($hasilTotal); // Fungsi helper di bawah

        // 2. PDF INITIALIZATION
        new Pdf();
        $pdf = new \setasign\Fpdi\Fpdi();
        $pdf->SetAutoPageBreak(false, 0);
        $bulanNomor = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $bulanIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        // Format String Tanggal & Nomor
        $strTglAkhir = date('d', $tgl_akhir) . ' ' . $bulanIndo[(int)date('m', $tgl_akhir)] . ' ' . date('Y', $tgl_akhir);
        $strRangeTgl = date('d', $tgl_awal) . ' ' . $bulanIndo[(int)date('m', $tgl_awal)] . ' ' . date('Y', $tgl_awal) . ' - ' . $strTglAkhir;
        $noSertifikat = "{$hasilUjian[0]->id_ujian}/ALC-BREVET-AB/{$bulanNomor[(int)date('m',$tgl_akhir)]}/" . date('Y', $tgl_akhir);

        // 3. GENERATE QR CODE
        $writer = new PngWriter();
        $qrCode = QrCode::create(base_url('detail/data_ab/' . encrypt_url($id_siswa)))->setSize(300)->setMargin(0);
        $logoQr = Logo::create(FCPATH . 'assets/img/logo-brevet.png')->setResizeToWidth(60);
        $qrResult = $writer->write($qrCode, $logoQr);
        $qrUri = $qrResult->getDataUri();

        // ---------------------------------------------------------
        // PAGE 1: SERTIFIKAT UTAMA (LANDSCAPE)
        // ---------------------------------------------------------
        $pdf->AddPage('L');
        $bgSertifikat = ($jenis == "cap") ? 'brevet-ab-cap.jpg' : 'brevet-ab.jpg';
        $pdf->Image(FCPATH . 'uploads/sertifikat/' . $bgSertifikat, 0, 0, 297, 210);

        $pdf->SetTextColor(51, 49, 49);

        // Izin Operasional
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY(28, 12);
        $pdf->Cell(100, 5, "Izin Operasional LKP: 500.16.7.2/0003/SPNF-LKP/IV.7/I/2025", 0, 1, 'L');

        // Nomor & Nama
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->SetXY(28, 70);
        $pdf->Cell(0, 5, "Nomor : " . $noSertifikat, 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 24);
        $pdf->SetXY(28, 118);
        $pdf->Cell(0, 15, strtoupper($siswa->nama_siswa), 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(28, 134);
        $pdf->Cell(0, 10, "NIP : " . $siswa->no_induk_siswa, 0, 1, 'L');

        // Keterangan Lulus
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetXY(28, 150);
        $pdf->Cell(0, 8, "Dinyatakan LULUS dengan nilai " . $hasilTotal, 0, 1, 'L');
        $pdf->SetX(28);
        $pdf->Cell(0, 8, "Predikat kelulusan " . $predikat['huruf'] . " ({$predikat['teks']})", 0, 1, 'L');
        $pdf->SetX(28);
        $pdf->Cell(0, 8, "Pada tanggal " . $strTglAkhir, 0, 1, 'L');

        $pdf->Image($qrUri, 30, 175, 28, 28, 'png');

        // ---------------------------------------------------------
        // PAGE 2: TRANSKRIP NILAI (LANDSCAPE)
        // ---------------------------------------------------------
        $pdf->AddPage('L');
        $pdf->Image(FCPATH . 'uploads/sertifikat/brevet-ab-2.jpg', 0, 0, 297, 210);

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(55, 57);
        $pdf->Cell(0, 5, strtoupper($siswa->nama_siswa), 0, 1, 'L');

        // Tabel Materi
        $pdf->SetXY(25, 65);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(15, 6, 'No', 1, 0, 'C');
        $pdf->Cell(140, 6, 'Materi Pelatihan', 1, 0, 'C');
        $pdf->Cell(75, 6, 'Nilai', 1, 1, 'C');

        $pdf->SetFont('Arial', '', 10);
        $no = 1;
        foreach ($hasilUjian as $row) {
            $pdf->SetX(25);
            $pdf->Cell(15, 6, $no++, 1, 0, 'C');
            $pdf->Cell(140, 6, $row->nama_mapel, 1, 0, 'L');
            $pdf->Cell(75, 6, $row->nilai_ujian, 1, 1, 'C');
        }
        // Row Total
        $pdf->SetX(25);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(155, 6, 'NILAI RATA-RATA', 1, 0, 'C');
        $pdf->Cell(75, 6, $hasilTotal, 1, 1, 'C');

        $pdf->Image($qrUri, 240, 145, 25, 25, 'png');
        $pdf->SetXY(138, 174);
        $pdf->Cell(0, 5, $strTglAkhir, 0, 1, 'L');


        // OUTPUT
        $this->response->setContentType('application/pdf');
        $pdf->Output(str_replace(' ', '_', $siswa->nama_siswa) . '-brevet-ab.pdf', 'I');
    }

    private function _getPredikat($nilai)
    {
        if ($nilai < 60) return ['huruf' => 'D', 'teks' => 'Kurang'];
        if ($nilai < 70) return ['huruf' => 'C', 'teks' => 'Cukup'];
        if ($nilai < 80) return ['huruf' => 'B', 'teks' => 'Cukup Baik'];
        if ($nilai < 90) return ['huruf' => 'A', 'teks' => 'Baik'];
        return ['huruf' => 'A+', 'teks' => 'Sangat Baik'];
    }
}

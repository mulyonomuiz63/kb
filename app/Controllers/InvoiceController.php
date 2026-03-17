<?php

namespace App\Controllers;


class InvoiceController extends BaseController
{
    protected $transaksiModel;
    protected $detailTransaksiModel;

    public function __construct()
    {
        $this->transaksiModel = new \App\Models\TransaksiModel();
        $this->detailTransaksiModel = new \App\Models\DetailTransaksiModel();
    }

    public function invoice($id)
{
    // 1. Data Retrieval
    $idtransaksi = decrypt_url($id);
    
    $dataTransaksi = $this->transaksiModel
        ->join('detail_transaksi d', 'd.idtransaksi = transaksi.idtransaksi')
        ->join('siswa b', 'b.id_siswa = transaksi.idsiswa')
        ->join('paket c', 'c.idpaket = d.idpaket')
        ->where('transaksi.idtransaksi', $idtransaksi)
        ->get()->getResultObject();

    $invoice = $this->transaksiModel
        ->join('siswa b', 'b.id_siswa = transaksi.idsiswa')
        ->where('idtransaksi', $idtransaksi)
        ->get()->getRowObject();

    if (!$invoice) return "Data tidak ditemukan";

    // 2. Kalkulasi Data
    $totalTransaksi = $this->transaksiModel->where('idtransaksi', $idtransaksi)->get()->getRowObject();
    $totalDetail = $this->detailTransaksiModel->select('count(iddetailtransaksi) as jumlah')->where('idtransaksi', $idtransaksi)->get()->getRowObject();
    
    // Menghitung harga satuan (menjaga fungsi pembagian yang Anda buat sebelumnya)
    $hargaSatuan = ($totalTransaksi->nominal) / (int)$totalDetail->jumlah;

    // 3. Inisialisasi PDF (P - Portrait)
    $pdf = new \setasign\Fpdi\Fpdi();
    $pdf->SetAutoPageBreak(false, 5);
    $pdf->AddPage('P');
    
    // Metadata
    $pdf->SetCreator("kelasbrevet.com");
    $pdf->SetAuthor($invoice->nama_siswa);
    $pdf->SetTitle('Invoice - ' . $idtransaksi);
    
    // Background (Watermark/Lunas)
    $pdf->Image('lunas.png', 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());

    // 4. HEADER SECTION
    // Logo
    $pdf->Image('uploads/app-icon/'.setting('logo_perusahaan'), 20, 15, 25, 0); // Disesuaikan ukurannya

    // Informasi Invoice (Kanan Atas)
    $pdf->SetTextColor(54, 67, 83);
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetXY(120, 15);
    $pdf->Cell(70, 7, 'INVOICE', 0, 1, 'R');
    
    $pdf->SetFont('Arial', '', 9);
    $pdf->SetX(120);
    $pdf->Cell(70, 5, 'Nomor: #INV' . $invoice->idtransaksi . $invoice->idsiswa, 0, 1, 'R');
    $pdf->SetX(120);
    $pdf->Cell(70, 5, 'Tanggal: ' . date('d/m/Y', strtotime($invoice->tgl_pembayaran)), 0, 1, 'R');
    $pdf->SetX(120);
    $pdf->Cell(70, 5, 'Pelanggan: ' . strtoupper($invoice->nama_siswa), 0, 1, 'R');

    // 5. TABLE HEADER
    $pdf->SetY(50);
    $pdf->SetX(20);
    $pdf->SetFillColor(245, 245, 245); // Warna abu muda untuk header tabel
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(95, 8, '  DESKRIPSI PAKET', 'B', 0, 'L', true);
    $pdf->Cell(25, 8, 'HARGA', 'B', 0, 'C', true);
    $pdf->Cell(15, 8, 'QTY', 'B', 0, 'C', true);
    $pdf->Cell(35, 8, 'TOTAL', 'B', 0, 'R', true);
    $pdf->Ln(10);

    // 6. TABLE BODY
    $pdf->SetFont('Arial', '', 9);
    $totalGross = 0;
    $yPos = $pdf->GetY();

    foreach($dataTransaksi as $rows) {
        $subtotalItem = $hargaSatuan * $rows->quantity;
        $totalGross += $subtotalItem;

        $pdf->SetX(20);
        // Membersihkan nama paket seperti logika asli Anda
        $namaPaket = str_replace("Ujian Kompetensi", "", $rows->name);
        
        $pdf->Cell(95, 7, $namaPaket, 0, 0, 'L');
        $pdf->Cell(25, 7, number_format($hargaSatuan, 0, '.', '.'), 0, 0, 'C');
        $pdf->Cell(15, 7, $rows->quantity, 0, 0, 'C');
        $pdf->Cell(35, 7, number_format($subtotalItem, 0, '.', '.'), 0, 1, 'R');
        
        $yPos += 7;
    }

    // Garis Penutup Tabel
    $pdf->SetX(20);
    $pdf->Cell(170, 0, '', 'T', 1);
    $pdf->Ln(2);

    // 7. SUMMARY SECTION (FOOTER TABEL)
    $diskon = ($totalGross * $invoice->diskon) / 100;
    $totalSetelahDiskon = $totalGross - $diskon;
    $diskonVoucher = ($totalSetelahDiskon * $invoice->voucher) / 100;
    $grandTotal = $totalSetelahDiskon - $diskonVoucher;

    $pdf->SetFont('Arial', '', 9);
    
    // Subtotal
    $pdf->SetX(110);
    $pdf->Cell(45, 6, 'Sub Total', 0, 0, 'R');
    $pdf->Cell(35, 6, 'Rp ' . number_format($totalGross, 0, '.', '.'), 0, 1, 'R');

    // Diskon
    if ($invoice->diskon > 0) {
        $pdf->SetX(110);
        $pdf->Cell(45, 6, 'Diskon (' . $invoice->diskon . '%)', 0, 0, 'R');
        $pdf->Cell(35, 6, '- Rp ' . number_format($diskon, 0, '.', '.'), 0, 1, 'R');
    }

    // Voucher
    if ($invoice->voucher > 0) {
        $pdf->SetX(110);
        $pdf->Cell(45, 6, 'Voucher (' . $invoice->voucher . '%)', 0, 0, 'R');
        $pdf->Cell(35, 6, '- Rp ' . number_format($diskonVoucher, 0, '.', '.'), 0, 1, 'R');
    }

    // Grand Total
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetX(110);
    $pdf->SetTextColor(30, 144, 255); // Warna Biru untuk Total
    $pdf->Cell(45, 8, 'TOTAL AKHIR', 0, 0, 'R');
    $pdf->Cell(35, 8, 'Rp ' . number_format($grandTotal, 0, '.', '.'), 0, 1, 'R');

    // 8. SIGNATURE AREA
    $pdf->SetTextColor(51, 49, 49);
    $pdf->SetFont('Arial', 'I', 8);
    $pdf->SetY($yPos + 30);
    $pdf->SetX(20);
    $pdf->MultiCell(80, 4, "Catatan:\nTerima kasih telah melakukan pembayaran. Simpan invoice ini sebagai bukti transaksi yang sah.", 0, 'L');

    // Tanda Tangan Keuangan
    $pdf->Image('assets-landing/images/bag_keuangan.png', 145, $pdf->GetY() - 10, 45);

    // 9. OUTPUT
    $this->response->setContentType('application/pdf');
    $pdf->Output('Invoice_#'.$invoice->idtransaksi.'_'.date('dmY').'.pdf', 'I');
    exit;
}

    private function drawWatermark($pdf, $txt)
    {
        $pdf->StartTransform();
        $pdf->Rotate(45, 50, 190);
        $pdf->Text(15, 190, $txt);
        $pdf->StopTransform();
    }
}

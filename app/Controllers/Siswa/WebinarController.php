<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Libraries\Emailer;
use App\Models\WebinarSesiModel;
use App\Models\SiswaModel; // Sesuaikan dengan model siswa Anda
use App\Libraries\SeoHelper;
use App\Models\DetailTransaksiModel;
use App\Models\PaketModel;
use App\Models\TransaksiModel;

class WebinarController extends BaseController
{
    protected  $seo;
    protected  $sesiModel;
    protected  $siswaModel;
    protected  $paketModel;
    protected  $transaksiModel;
    protected  $detailTransaksiModel;
    protected  $emailer;

    public function __construct()
    {
        $this->seo = new SeoHelper();
        $this->sesiModel = new WebinarSesiModel();
        $this->siswaModel = new SiswaModel();
        $this->paketModel = new PaketModel();
        $this->transaksiModel = new TransaksiModel();
        $this->detailTransaksiModel = new DetailTransaksiModel();
        $this->emailer = new Emailer();
    }

    public function index()
    {
        // Pastikan user sudah login
        $id_siswa = session()->get('id');
        // Query untuk mengambil sesi webinar yang sudah dibeli dan lunas
        $dataWebinar = $this->transaksiModel
            ->select('webinar_sesi.*, paket.nama_paket, paket.file, transaksi.tgl_pembayaran, transaksi.created_at')
            ->join('detail_transaksi', 'transaksi.idtransaksi = detail_transaksi.idtransaksi')
            ->join('paket', 'detail_transaksi.idpaket=paket.idpaket')
            ->join('webinar_sesi', 'detail_transaksi.idsesi=webinar_sesi.id_sesi')
            ->where('transaksi.status', 'S')
            ->where('transaksi.idsiswa', $id_siswa)
            ->groupBy('detail_transaksi.idsesi')
            ->get()
            ->getResult();

        // var_dump($data['webinar']);

        $data = [
            'webinar' => $dataWebinar
        ];
        $data['breadcrumbs'] = [
            ['title' => 'Webinar', 'url' => base_url('sw-siswa')],
            ['title' => 'List Webinar', 'url' => '#'],
        ];

        return view('siswa/webinar/list', $data);
    }

    public function sertifikat($id_sesien)
    {
        // 1. Data Retrieval
        $id_siswa = session()->get('id'); // Ambil ID siswa dari session
        $id_sesi   = decrypt_url($id_sesien);
        $dataSesi =$id_siswa = session()->get('id'); // Ambil ID siswa yang sedang login
        $id_target = (int) $id_sesi;       // ID Sesi yang ingin dilihat sertifikatnya

        $dataSesi = $this->transaksiModel
            ->select('ws_target.*, paket.nama_paket')
            ->join('detail_transaksi', 'transaksi.idtransaksi = detail_transaksi.idtransaksi')
            ->join('paket', 'detail_transaksi.idpaket = paket.idpaket')
            ->join('siswa', 'transaksi.idsiswa = siswa.id_siswa')
            // Ambil data detail dari webinar_sesi target yang ingin dicetak sertifikatnya
            ->join('webinar_sesi ws_target', 'ws_target.id_sesi = ' . $id_target)
            ->where('transaksi.status', 'S')
            ->where('transaksi.idsiswa', $id_siswa)
            ->groupStart()
                // KONDISI 1: Sesi yang diminta dibeli secara langsung sebagai sesi utama
                ->where('detail_transaksi.idsesi', $id_target)
                // KONDISI 2: Sesi yang diminta ada di dalam daftar JSON `sesi_gratis` dari sesi utama yang dibeli
                ->orWhere("EXISTS (
                    SELECT 1 FROM webinar_sesi ws_parent 
                    WHERE ws_parent.id_sesi = detail_transaksi.idsesi 
                    AND (
                        ws_parent.sesi_gratis LIKE '%\"{$id_target}\"%' 
                        OR ws_parent.sesi_gratis LIKE '%[{$id_target},%' 
                        OR ws_parent.sesi_gratis LIKE '%,{$id_target},%' 
                        OR ws_parent.sesi_gratis LIKE '%,{$id_target}]%'
                    )
                )", null, false)
            ->groupEnd()
            ->get()
            ->getRow();
        $dataSiswa     = $this->siswaModel->where('id_siswa', $id_siswa)->get()->getRow();


        if (!$dataSesi || !$dataSiswa) {
            return "Data tidak ditemukan";
        }

        // 2. Inisialisasi PDF (Landscape - A4: 297 x 210 mm)
        $pdf = new \setasign\Fpdi\Fpdi();
        $pdf->SetAutoPageBreak(false, 5);
        $pdf->AddPage('L', 'A4');

        // Metadata
        $pdf->SetCreator("kelasbrevet.com");
        $pdf->SetAuthor(strtoupper($dataSiswa->nama_siswa));
        $pdf->SetTitle(strtoupper($dataSesi->nama_sesi) . ' - SERTIFIKAT');
        $pdf->SetSubject('SERTIFIKAT ' . strtoupper($dataSesi->nama_sesi) . ' - SERTIFIKAT');
        $pdf->SetKeywords('KelasBrevet, Pajak, Webinar');

        // 3. Background Image (Sesuai Permintaan)
        $bgImg = 'uploads/webinar/sertifikat/background.jpeg';
        $pdf->Image($bgImg, 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());

        // 4. Helper Format Tanggal & Nomor
        $arrBulan        = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $arrBulanRomawi  = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

        $timeStart   = strtotime($dataSesi->waktu_mulai);
        $idSesi   = str_pad($dataSesi->id_sesi, 3, '0', STR_PAD_LEFT);
        // Bisa disesuaikan jika ingin statis 8 Agustus 2026 atau dinamis berdasarkan start_ujian
        $tglSertif   = date('d', $timeStart) . ' ' . $arrBulan[(int)date('m', $timeStart)] . ' ' . date('Y', $timeStart);
        $nomorSertif = $idSesi .' - ' . $dataSiswa->id_siswa . '/WEBINAR-BREVET/' . $arrBulanRomawi[(int)date('m', $timeStart)] . '/' . date('Y', $timeStart);

        // =========================================================
        // 5. PENULISAN KONTEN DINAMIS (Rata Tengah)
        // =========================================================

        // A. NOMOR SERTIFIKAT (Di atas teks "diberikan kepada:")
        $pdf->SetTextColor(51, 49, 49); // Warna Abu-abu gelap / Hitam
        $pdf->SetFont('Arial', 'BU', 16);
        // Posisi Y: 75 (Silakan naik/turunkan angka 75 jika kurang pas dengan background)
        $pdf->SetXY(10, 55);
        // Lebar 0 agar membentang penuh dari kiri ke kanan, 'C' untuk Center
        $pdf->Cell(0, 5, "Nomor: " . $nomorSertif, 0, 1, 'C');

        // B. NAMA LENGKAP PESERTA
        // Warna Biru (Menyesuaikan desain draft: RGB ~ 23, 107, 195)
        $pdf->SetTextColor(23, 98, 185);
        $pdf->SetFont('Arial', 'B', 36);

        $nama_siswa = ucwords(strtoupper($dataSiswa->nama_siswa));

        // Jika panjang karakter nama lebih dari 20 huruf, otomatis turunkan ukuran font-nya
        if (strlen($nama_siswa) > 25) {
            $pdf->SetFont('Arial', 'B', 20); // Font diperkecil jadi 28
        } elseif (strlen($nama_siswa) > 35) {
            $pdf->SetFont('Arial', 'B', 18); // Font diperkecil jadi 20 jika sangat panjang
        }

        $pdf->SetXY(8, 88);
        $pdf->Cell(0, 10, $nama_siswa, 0, 1, 'C');

        // C. DESKRIPSI KEGIATAN
        $pdf->SetTextColor(51, 49, 49); // Kembali ke warna Hitam
        $pdf->SetFont('Arial', '', 12);

        $namaWebinar = $dataSesi->nama_paket ?? 'Webinar Perpajakan';
        $teksTema = !empty($dataSesi->nama_sesi) ? "dengan tema \"" . $dataSesi->nama_sesi . "\"\n" : "";

        $deskripsi = "Atas partisipasinya sebagai Peserta " . $namaWebinar . "\n"
                   . "Yang diselenggarakan oleh Kelas Brevet\n"
                   . $teksTema
                   . "Pada " . $tglSertif;

        // Karena FPDF MultiCell mengukur dari margin kiri, kita harus hitung posisi X 
        // agar kotaknya persis berada di tengah kertas.
        $lebar_teks = 200; // Lebar area teks
        $posisi_x = ($pdf->getPageWidth() - $lebar_teks) / 2;

        $pdf->SetXY($posisi_x, 110); // Posisi Y: 135
        $pdf->MultiCell($lebar_teks, 6, $deskripsi, 0, 'C');

        // =========================================================

        // 6. Output
        $isDownload = $this->request->getGet('download');
        $outputMode = $isDownload ? 'D' : 'I'; // 'D' = Download File, 'I' = Preview di Iframe

        $this->response->setContentType('application/pdf');
        $pdf->Output(strtoupper($dataSiswa->nama_siswa) . '-SERTIFIKAT.pdf', $outputMode);
        exit;
    }
}

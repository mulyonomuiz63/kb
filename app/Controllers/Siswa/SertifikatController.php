<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Logo\Logo;

class SertifikatController extends BaseController
{
    protected $siswaModel;
    protected $ujianModel;
    protected $ujianMasterModel;
    public function __construct()
    {
        $this->siswaModel = new \App\Models\SiswaModel();
        $this->ujianModel = new \App\Models\UjianModel();
        $this->ujianMasterModel = new \App\Models\UjianMasterModel();
    }
    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-siswa')],
            ['title' => 'List Sertifikat', 'url' => '#'], // '#' untuk halaman aktif
        ];
        $userId = session()->get('id');
        $userEmail = session()->get('email');

        $data['siswa'] = $this->siswaModel->asObject()->find($userId);
        $siswaList = $this->siswaModel->where('email', $userEmail)->get()->getResultObject();

        $data['ujian'] = [];
        $totalSertifikat = 0;
        $totalMapel = 0;

        foreach ($siswaList as $r) {
            // Gabungkan list ujian
            $ujian = $this->ujianModel->getAllByKelasSertifikat($r->kelas, $r->id_siswa);
            $data['ujian'] = array_merge($data['ujian'], $ujian);

            // Hitung Total Mapel yang harus diikuti
            $totalMapel += $this->ujianMasterModel->where('kelas', $r->kelas)->groupBy('mapel')->countAllResults();

            // Hitung Total Lulus (Nilai >= 60)
            $totalSertifikat += $this->ujianModel->where(['kelas' => $r->kelas, 'id_siswa' => $r->id_siswa, 'ujian.nilai >=' => 60])
                ->groupBy('ujian.mapel')->countAllResults();
        }

        $data['totalSertifikat'] = $totalSertifikat;
        $data['total'] = $totalMapel;

        return view('siswa/sertifikat/list', $data);
    }


    public function lihatSertifikatBrevet($id, $jenis = "")
    {
        $id_siswa = decrypt_url($id);
        $hasil = $this->ujianModel->getByIdsiswa($id_siswa);
        $siswa = $this->siswaModel->where('id_siswa', $id_siswa)->get()->getRowObject();

        $siswaAsc = $this->ujianModel->getByIdsiswaAsc($id_siswa);
        $siswaDesc = $this->ujianModel->getByIdsiswaDesc($id_siswa);

        $nilai = 0;
        $total = 0;
        $nama = '';
        $no_induk_siswa = '';
        $id_ujian = '';

        foreach ($hasil as $rows) {
            $nilai += (int)$rows->nilai;
            $total++;
            $nama = $rows->nama_siswa;
            $no_induk_siswa = $rows->no_induk_siswa;
            $id_ujian = $rows->id_ujian;
        }

        $tgl_sertifikat_start = $siswaAsc->start_ujian;
        $tgl_sertifikat_end = $siswaDesc->end_ujian;
        $totalNilai = ($total > 0) ? round($nilai / $total) : 0;

        // --- PERBAIKAN NAMESPACE ---
        // Gunakan \setasign\Fpdi\Fpdi() jika Anda menginstal fpdf + fpdi
        // Jika masih error "Class FPDF not found", pastikan sudah jalankan: composer require setasign/fpdf
        $pdf = new \setasign\Fpdi\Fpdi();
        $pdf->SetAutoPageBreak(false, 5);

        // PAGE 1
        $pdf->AddPage('L');
        $pdf->SetCreator("kelasbrevet.com");
        $pdf->SetAuthor(strtoupper($nama));
        $pdf->SetTitle('BREVET PAJAK AB');
        $pdf->SetSubject('SERTIFIKAT BREVET PAJAK AB');

        // Perbaikan Path Gambar menggunakan FCPATH agar terbaca di semua server
        $bgPath = ($jenis == "") ? FCPATH . 'uploads/sertifikat/brevet-ab.jpg' : FCPATH . 'uploads/sertifikat/brevet-ab-cap.jpg';
        if (file_exists($bgPath)) {
            $pdf->Image($bgPath, 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());
        }

        $pdf->SetTextColor(51, 49, 49);

        // NIS / Nomor Sertifikat
        $bulanNomor = array('I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII');
        $pdf->SetFont('Arial', 'B', 16); // Hilangkan tanda kutip pada ukuran font
        $pdf->SetXY(28, 70);
        $noSertif = $id_ujian . '/' . 'ALC-BREVET-AB' . '/' . $bulanNomor[(int)date('m', strtotime($tgl_sertifikat_end)) - 1] . '/' . date('Y', strtotime($tgl_sertifikat_end));
        $pdf->Cell(75, 4, "Nomor : " . $noSertif, 0, 1, 'L');

        // Izin Operasional
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(28, 5);
        $pdf->Cell(75, 4, "Izin Operasional Lembaga Kursus dan Pelatihan:", 0, 1, 'L');
        $pdf->SetXY(28, 10);
        $pdf->Cell(75, 4, "500.16.7.2/0003/SPNF-LKP/IV.7/I/2025", 0, 1, 'L');

        // Nama Siswa
        $pdf->SetFont('Arial', 'B', 19.5);
        $pdf->SetXY(28, 126);
        $pdf->Cell(75, 4, strtoupper($nama), 0, 1, 'L');

        // NIP
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->SetXY(28, 138);
        $pdf->Cell(75, 4, "NIP : " . $no_induk_siswa, 0, 1, 'L');

        // Kelulusan & Predikat
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetXY(28, 150);
        $pdf->Cell(75, 4, "Dinyatakan [LULUS] dengan nilai " . $totalNilai, 0, 1, 'L');

        if ($totalNilai < 60) {
            $nilaiKeterangan = "D";
            $keterangan = 'Kurang';
        } elseif ($totalNilai < 70) {
            $nilaiKeterangan = "C";
            $keterangan = 'Cukup';
        } elseif ($totalNilai < 80) {
            $nilaiKeterangan = "B";
            $keterangan = 'Cukup Baik';
        } elseif ($totalNilai < 90) {
            $nilaiKeterangan = "A";
            $keterangan = 'Baik';
        } else {
            $nilaiKeterangan = "A+";
            $keterangan = 'Sangat Baik';
        }

        $pdf->SetXY(28, 158);
        $pdf->Cell(75, 4, "Predikat kelulusan " . $nilaiKeterangan . " ($keterangan)", 0, 1, 'L');

        // Tanggal
        $bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        $pdf->SetXY(28, 166);
        $tglTeks = date('d', strtotime($tgl_sertifikat_end)) . ' ' . $bulan[(int)date('m', strtotime($tgl_sertifikat_end)) - 1] . ' ' . date('Y', strtotime($tgl_sertifikat_end));
        $pdf->Cell(75, 4, "Pada tanggal " . $tglTeks, 0, 1, 'L');

        // --- QR CODE GENERATION ---
        $writer = new PngWriter();
        $qrCode = QrCode::create(base_url('detail/data_ab') . '/' . encrypt_url($id_siswa));
        $logo = Logo::create(FCPATH . 'assets/img/logo-brevet.png')->setResizeToWidth(100);

        $result = $writer->write($qrCode, $logo);
        $qrCodesDataUri = $result->getDataUri();

        $pdf->Image($qrCodesDataUri, 30, 175, 30, 30, 'png');

        // PAGE 2: Daftar Nilai
        $pdf->AddPage('L');
        $bg2 = FCPATH . 'uploads/sertifikat/brevet-ab-2.jpg';
        if (file_exists($bg2)) $pdf->Image($bg2, 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(55, 57);
        $pdf->Cell(75, 4, strtoupper($nama), 0, 1, 'L');

        $hasilMateri = $this->ujianModel->getByIdsiswaSertifikat($id_siswa);
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetXY(25, 63);

        $pdf->Cell(15, 6, 'No', 1, 0, 'C');
        $pdf->Cell(140, 6, 'Materi', 1, 0, 'C');
        $pdf->Cell(75, 6, 'Nilai', 1, 1, 'C');

        $no = 1;
        $totalSkor = 0;
        foreach ($hasilMateri as $rows) {
            $totalSkor += $rows->nilai_ujian;
            $pdf->SetX(25);
            $pdf->Cell(15, 6, $no++, 1, 0, 'C');
            $pdf->Cell(140, 6, $rows->nama_mapel, 1, 0, 'L');
            $pdf->Cell(75, 6, $rows->nilai_ujian, 1, 1, 'C');
        }

        $count = count($hasilMateri);
        $hasilTotal = ($count > 0) ? round($totalSkor / $count) : 0;
        $pdf->SetX(25);
        $pdf->Cell(155, 6, 'Nilai Rata-rata', 1, 0, 'C');
        $pdf->Cell(75, 6, $hasilTotal == 0 ? '-' : $hasilTotal, 1, 1, 'C');

        $pdf->Image($qrCodesDataUri, 225, 145, 30, 30, 'png');
        $pdf->SetXY(138, 174);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(95, 4, $tglTeks, 0, 1, 'L');

        // PAGE 3: SK (Halaman 1)
        $pdf->AddPage('P');
        $pdf->Image(FCPATH . 'uploads/sertifikat/halaman_1.jpg', 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());
        $pdf->SetXY(83, 48);
        $pdf->Cell(75, 4, $id_ujian . '/' . 'KEP-ALC-BREVET' . '/' . $bulanNomor[(int)date('m', strtotime($tgl_sertifikat_end)) - 1] . '/' . date('Y', strtotime($tgl_sertifikat_end)), 0, 1, 'L');

        $pdf->SetXY(110, 106);
        $start = date('d', strtotime($tgl_sertifikat_start)) . ' ' . $bulan[(int)date('m', strtotime($tgl_sertifikat_start)) - 1] . ' ' . date('Y', strtotime($tgl_sertifikat_start));
        $pdf->Cell(95, 4, $start . ' - ' . $tglTeks, 0, 1, 'L');

        // PAGE 4: SK (Halaman 2)
        $pdf->AddPage('P');
        $bgH2 = ($jenis == "") ? FCPATH . 'uploads/sertifikat/halaman_2.jpg' : FCPATH . 'uploads/sertifikat/halaman_cap_2.jpg';
        if (file_exists($bgH2)) $pdf->Image($bgH2, 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());

        $pdf->SetXY(80, 33);
        $pdf->Cell(95, 4, strtoupper($nama), 0, 1, 'L');
        $pdf->SetXY(55, 84);
        $pdf->Cell(95, 4, $tglTeks, 0, 1, 'L');

        // PAGE 5: Biodata (Halaman 3)
        $pdf->AddPage('P');
        $pdf->Image(FCPATH . 'uploads/sertifikat/halaman_3.jpg', 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());

        // Foto Profil
        $urlAvatar = FCPATH . 'assets/app-assets/user/' . $siswa->avatar;
        if (file_exists($urlAvatar) && !empty($siswa->avatar)) {
            $pdf->Image($urlAvatar, 27.8, 28, 40, 52);
        }

        $pdf->SetFont('Arial', '', 12);
        $fields = [
            92 => $siswa->nik,
            100 => $siswa->nama_siswa,
            108 => $siswa->tgl_lahir,
            117 => $siswa->jenis_kelamin,
            125 => substr($siswa->alamat_ktp, 0, 50),
            134 => substr($siswa->alamat_domisili, 0, 50),
            143 => $siswa->kelurahan,
            152 => $siswa->kecamatan,
            160 => $siswa->kota,
            168 => $siswa->provinsi,
            176 => $siswa->profesi,
            185 => $siswa->kota_intansi,
            194 => substr($siswa->kota_aktifitas_profesi, 0, 50),
            202 => $siswa->bidang_usaha,
            210 => $siswa->email,
            219 => $siswa->hp,
            228 => date("d-m-Y", $siswa->date_created),
            237 => date('d-m-Y', strtotime($tgl_sertifikat_end)),
            245 => $hasilTotal == 0 ? '-' : $hasilTotal,
            253 => $nilaiKeterangan . " ($keterangan)"
        ];

        foreach ($fields as $y => $val) {
            $pdf->SetXY(78, $y);
            $pdf->Cell(95, 4, $val, 0, 1, 'L');
        }

        // --- FINAL OUTPUT ---
        ob_clean(); // Hapus buffer agar tidak ada karakter sampah sebelum PDF
        $this->response->setContentType('application/pdf');
        $pdf->Output($nama . '-brevet-ab-' . date('d-m-Y') . '.pdf', 'I');
        exit();
    }

    public function lihatSertifikat($kode_ujian, $id_ujian, $jenis = "")
    {
        // 1. Data Retrieval
        $kode_ujian = decrypt_url($kode_ujian);
        $id_ujian   = decrypt_url($id_ujian);
        $hasil      = $this->ujianModel->getBykode($kode_ujian, $id_ujian);

        if (!$hasil) {
            return "Data tidak ditemukan";
        }

        // 2. Inisialisasi PDF
        $pdf = new \setasign\Fpdi\Fpdi();
        $pdf->SetAutoPageBreak(false, 5);
        $pdf->AddPage('L');

        // Metadata
        $pdf->SetCreator("kelasbrevet.com");
        $pdf->SetAuthor(strtoupper($hasil->nama_siswa));
        $pdf->SetTitle(strtoupper($hasil->nama_mapel));
        $pdf->SetSubject('SERTIFIKAT ' . strtoupper($hasil->nama_mapel));
        $pdf->SetKeywords('KelasBrevet, Pajak, Brevet Pajak AB');

        // 3. Background Image
        $bgImg = ($jenis == "") ? 'uploads/sertifikat/brevet-materi.jpg' : 'uploads/sertifikat/brevet-materi-cap.jpg';
        $pdf->Image($bgImg, 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());

        $pdf->SetTextColor(51, 49, 49);

        // 4. Helper Format Tanggal & Nomor
        $arrBulan        = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $arrBulanRomawi  = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

        $timeStart   = strtotime($hasil->start_ujian);
        $tglSertif   = date('d', $timeStart) . ' ' . $arrBulan[(int)date('m', $timeStart)] . ' ' . date('Y', $timeStart);
        $nomorSertif = $hasil->id_ujian . '/ALC-BREVET/' . $arrBulanRomawi[(int)date('m', $timeStart)] . '/' . date('Y', $timeStart);

        // 5. Penulisan Konten (Izin Operasional)
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(28, 5);
        $pdf->Cell(75, 4, "Izin Operasional Lembaga Kursus dan Pelatihan", 0, 1, 'L');
        $pdf->SetXY(28, 10);
        $pdf->Cell(75, 4, "500.16.7.2/0003/SPNF-LKP/IV.7/I/2025", 0, 1, 'L');

        // Nomor Sertifikat
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetXY(28, 70);
        $pdf->Cell(75, 4, "Nomor : " . $nomorSertif, 0, 1, 'L');

        // Nama Mata Pelajaran
        $pdf->SetXY(28, 85);
        $pdf->Cell(75, 4, strtoupper($hasil->nama_mapel), 0, 1, 'L');

        // Nama Siswa
        $pdf->SetFont('Arial', 'B', 19.5);
        $pdf->SetXY(28, 126);
        $pdf->Cell(75, 4, strtoupper($hasil->nama_siswa), 0, 1, 'L');

        // NIP / No Induk
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->SetXY(28, 138);
        $pdf->Cell(75, 4, "NIP : " . $hasil->no_induk_siswa, 0, 1, 'L');

        // Keterangan Kelulusan
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetXY(28, 150);
        $pdf->Cell(75, 4, "Dinyatakan [LULUS] dengan nilai " . $hasil->nilai, 0, 1, 'L');

        // Tanggal Terbit
        $pdf->SetXY(28, 158);
        $pdf->Cell(75, 4, "Pada tanggal " . $tglSertif, 0, 1, 'L');

        // 6. QR Code Generation
        $writer = new PngWriter();
        $qrCode = QrCode::create(base_url('detail/data/' . encrypt_url($hasil->id_ujian)));
        $logo   = Logo::create('assets/img/logo-brevet.png')->setResizeToWidth(100);

        $result  = $writer->write($qrCode, $logo, null);
        $qrImage = $result->getDataUri();

        // Render QR Code ke PDF
        $pdf->Image($qrImage, 30, 175, 30, 30, 'png');

        // 7. Output
        $this->response->setContentType('application/pdf');
        $pdf->Output(strtoupper($hasil->nama_mapel) . '-' . date('d-m-Y') . '.pdf', 'I');
        exit;
    }
}

<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use TCPDF;

class PdfController extends BaseController
{
    protected $ikhModel;

    public function __construct()
    {
        $this->ikhModel = new \App\Models\IkhModel();
    }

    // Tambahkan parameter $id untuk mengambil data spesifik
    public function cetakCv($idikh)
    {
        $id = decrypt_url($idikh);
        // 1. Inisialisasi TCPDF dengan ukuran A4
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // 2. Pengaturan Meta Dokumen
        $pdf->SetCreator('Sistem Kelas Brevet');
        $pdf->SetTitle('Daftar Riwayat Hidup');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // 3. PENGATURAN MARGIN DAN PADDING
        $marginKiriLuar  = 20.07;
        $marginAtasLuar  = 18.03;
        $marginKananLuar = 20.07;
        $marginBawahLuar = 18.03;
        $padding = 5;

        $pdf->SetMargins($marginKiriLuar + $padding, $marginAtasLuar + $padding, $marginKananLuar + $padding);
        $pdf->SetAutoPageBreak(TRUE, $marginBawahLuar + $padding);

        // 4. Tambah Halaman
        $pdf->AddPage();

        // 5. MEMBUAT BORDER KOTAK 
        $pdf->Rect($marginKiriLuar, $marginAtasLuar, 169.86, 260.94, 'D');

        // 6. LOAD FONT BOOKMAN OLD STYLE
        $fontRegularPath = FCPATH . 'fonts/bookos.ttf';
        $fontBoldPath    = FCPATH . 'fonts/bookos.ttf'; // Pastikan ini mengarah ke bookosb.ttf jika ingin bold

        if (!file_exists($fontRegularPath) || !file_exists($fontBoldPath)) {
            die('Error: File font bookos.ttf atau bookosb.ttf tidak ditemukan di public/fonts/');
        }

        $fontRegular = \TCPDF_FONTS::addTTFfont($fontRegularPath, 'TrueTypeUnicode', '', 96);
        $fontBold    = \TCPDF_FONTS::addTTFfont($fontBoldPath, 'TrueTypeUnicode', '', 96);

        // =====================================================================
        // 7. AMBIL DATA DARI DATABASE DAN FORMAT
        // =====================================================================
        $dataPeserta = $this->ikhModel
            ->select('pendaftaran_ikh.*, siswa.profesi, siswa.kota')
            ->join('siswa', 'pendaftaran_ikh.id_siswa = siswa.id_siswa')
            ->where('pendaftaran_ikh.id_ikh', $id) // Ganti 'id_ikh' dengan nama primary key tabel pendaftaran_ikh Anda
            ->first();

        if (!$dataPeserta) {
            die('Data peserta tidak ditemukan.');
        }

        // Karena return find() bisa berupa array atau object, kita amankan:
        $peserta = is_array($dataPeserta) ? (object) $dataPeserta : $dataPeserta;

        $namaLengkap = strtoupper($peserta->nama_lengkap ?? '-');
        $tempatLahir = strtoupper($peserta->tempat_lahir ?? '-');
        $tglLahir    = $peserta->tanggal_lahir ?? '';
        $alamat      = strtoupper($peserta->alamat_korespondensi ?? '-');
        $nik         = strtoupper($peserta->nik ?? '-');
        $pendidikan  = strtoupper($peserta->pendidikan_terakhir ?? '-');
        $tahunLulus  = strtoupper($peserta->tahun_lulus ?? '-');
        $jurusan     = strtoupper($peserta->jurusan ?? '-');
        $pekerjaan   = strtoupper($peserta->profesi ?? '-');
        $email       = strtoupper(!empty($peserta->email_custom) ? $peserta->email_custom : ($peserta->email ?? '-'));
        $kota        = strtoupper($peserta->kota ?? '-');

        $pengalaman = '-';
        if (!empty($peserta->riwayat_pekerjaan)) {
            $riwayatArray = json_decode($peserta->riwayat_pekerjaan, true);
            
            // Cek apakah hasil decode benar-benar array dan ada isinya
            if (is_array($riwayatArray) && !empty($riwayatArray)) {
                // Otomatis jadikan huruf besar semua, lalu gabungkan dengan koma
                $pengalaman = implode(', ', array_map('strtoupper', $riwayatArray));
            }
        }

        // Format TTL (Tempat, Tanggal Lahir)
        $ttl = $tempatLahir . ', ' . $tglLahir;

        // Gabungkan Pendidikan dan Tahun Lulus sesuai format gambar
        $pendidikanDanTahun = $pendidikan . ' (' . $tahunLulus . '), ' . $jurusan;

        // Tanggal Download Hari Ini (Format: DD MM YYYY)
        date_default_timezone_set('Asia/Jakarta');
        $bulanIndo = [
            1 => 'JANUARI',
            'FEBRUARI',
            'MARET',
            'APRIL',
            'MEI',
            'JUNI',
            'JULI',
            'AGUSTUS',
            'SEPTEMBER',
            'OKTOBER',
            'NOVEMBER',
            'DESEMBER'
        ];
        $tanggalSekarang = date('d') . ' ' . $bulanIndo[(int)date('m')] . ' ' . date('Y');

        // =====================================================================
        // LOGIKA PENAMPILAN TANDA TANGAN (GOOGLE DRIVE / LOKAL)
        // =====================================================================
        // Default HTML jika belum ada TTD (Hanya spasi kosong)
        $htmlTtd = '<br><br><br><br><br><br>';

        if (!empty($peserta->file_ttd)) {
            $fileData = $peserta->file_ttd;
            $imgSrc = '';

            // Cek apakah itu ID Google Drive (Tidak mengandung titik / ekstensi)
            if (strpos($fileData, '.') === false) {
                // Gunakan URL khusus download G-Drive
                $gdriveUrl = 'https://drive.google.com/uc?id=' . $fileData;

                // Gunakan cURL untuk mengambil gambar (mengakali redirect Google)
                $ch = curl_init($gdriveUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $imgData = curl_exec($ch);
                curl_close($ch);

                // Jika berhasil diambil, format ke Base64 agar dikenali TCPDF
                if ($imgData) {
                    $imgSrc = '@' . base64_encode($imgData);
                }
            } else {
                // Jika file TTD ada di folder lokal (uploads/ikh/)
                $path = FCPATH . 'uploads/ikh/' . $fileData;
                if (file_exists($path)) {
                    $imgSrc = $path; // TCPDF paling suka format absolute path
                }
            }

            // Jika sumber gambar valid, buat tag <img> dengan ukuran proporsional
            if ($imgSrc !== '') {
                // Ukuran diset 22x22 mm (Cukup proporsional untuk rasio 1:1)
                $htmlTtd = '<br><img src="' . $imgSrc . '" width="83" height="83"><br>';
            }
        }
        // =====================================================================


        // 8. Cetak Judul
        $pdf->SetFont($fontBold, '', 10);
        $pdf->Cell(0, 10, 'DAFTAR RIWAYAT HIDUP', 0, 1, 'C');
        $pdf->Ln(5);

        // 9. Susun Konten HTML dengan Data
        $pdf->SetFont($fontRegular, '', 10);

        $html = '
        <table cellpadding="3">
            <tr>
                <td width="38%">Nama</td>
                <td width="3%">:</td>
                <td width="59%">' . $namaLengkap . '</td>
            </tr>
            <tr>
                <td>Tempat dan Tanggal Lahir</td>
                <td>:</td>
                <td>' . $ttl . '</td>
            </tr>
            <tr>
                <td>Alamat Rumah</td>
                <td>:</td>
                <td>' . $alamat . '</td>
            </tr>
            <tr>
                <td>Nomor Kartu Tanda Penduduk</td>
                <td>:</td>
                <td>' . $nik . '</td>
            </tr>
            <tr>
                <td>Pendidikan Formal (Cantumkan Tahun Lulus dan Nama Program Studi)</td>
                <td>:</td>
                <td>' . $pendidikanDanTahun . '</td>
            </tr>
            <tr>
                <td>Pekerjaan</td>
                <td>:</td>
                <td>' . $pekerjaan . '</td>
            </tr>
            <tr>
                <td>Alamat Surel <i>(e-Mail)</i></td>
                <td>:</td>
                <td>' . $email . '</td>
            </tr>
            <tr>
                <td>Pengalaman Kerja</td>
                <td>:</td>
                <td>' . $pengalaman . '</td>
            </tr>
        </table>

        <br><br><br>

        <div style="text-align: justify; text-indent: 0px;">Demikian Daftar Riwayat Hidup ini saya buat dengan sebenar-benarnya dan akan saya pertanggungjawabkan sebagaimana mestinya.</div>

        <br><br><br><br><br>

        <table cellpadding="1">
            <tr>
                <td width="55%"></td>
                <td width="45%" style="text-align: center;">
                    '.$kota.', ' . $tanggalSekarang . '
                    ' . $htmlTtd . '
                    (' . $namaLengkap . ')
                </td>
            </tr>
        </table>
        ';

        // 10. Render ke PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // 11. Output
        $this->response->setContentType('application/pdf');

        // Nama file saat di-download juga kita buat dinamis berdasarkan nama peserta
        $namaFile = 'CV_' . str_replace(' ', '_', $namaLengkap) . '.pdf';
        $pdf->Output($namaFile, 'I');
        exit();
    }

    // Tambahkan fungsi ini di bawah fungsi cetakCv($id)
    public function cetakSuratPernyataanBukanPns($idikh)
    {
        $id = decrypt_url($idikh);
        // 1. Inisialisasi TCPDF dengan ukuran A4
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // 2. Pengaturan Meta Dokumen
        $pdf->SetCreator('Sistem Kelas Brevet');
        $pdf->SetTitle('Surat Pernyataan Bukan PNS');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // 3. PENGATURAN MARGIN DAN PADDING
        $marginKiriLuar  = 20.07;
        $marginAtasLuar  = 18.03;
        $marginKananLuar = 20.07;
        $marginBawahLuar = 18.03;
        $padding = 5;

        $pdf->SetMargins($marginKiriLuar + $padding, $marginAtasLuar + $padding, $marginKananLuar + $padding);
        $pdf->SetAutoPageBreak(TRUE, $marginBawahLuar + $padding);

        // 4. Tambah Halaman
        $pdf->AddPage();

        // 5. MEMBUAT BORDER KOTAK 
        $pdf->Rect($marginKiriLuar, $marginAtasLuar, 169.86, 260.94, 'D');

        // 6. LOAD FONT BOOKMAN OLD STYLE
        $fontRegularPath = FCPATH . 'fonts/bookos.ttf';
        $fontBoldPath    = FCPATH . 'fonts/bookosb.ttf'; // Tetap saya perbaiki ke bookosb agar bold bisa jalan

        if (!file_exists($fontRegularPath) || !file_exists($fontBoldPath)) {
            die('Error: File font bookos.ttf atau bookosb.ttf tidak ditemukan di public/fonts/');
        }

        $fontRegular = \TCPDF_FONTS::addTTFfont($fontRegularPath, 'TrueTypeUnicode', '', 96);
        $fontBold    = \TCPDF_FONTS::addTTFfont($fontBoldPath, 'TrueTypeUnicode', '', 96);

        // =====================================================================
        // 7. AMBIL DATA DARI DATABASE
        // =====================================================================
        $dataPeserta = $this->ikhModel->find($id);$dataPeserta = $this->ikhModel
            ->select('pendaftaran_ikh.*, siswa.kota')
            ->join('siswa', 'pendaftaran_ikh.id_siswa = siswa.id_siswa')
            ->where('pendaftaran_ikh.id_ikh', $id) // Ganti 'id_ikh' dengan nama primary key tabel pendaftaran_ikh Anda
            ->first();

        if (!$dataPeserta) {
            die('Data peserta tidak ditemukan.');
        }

        $peserta = is_array($dataPeserta) ? (object) $dataPeserta : $dataPeserta;

        $namaLengkap = strtoupper($peserta->nama_lengkap ?? '-');
        $tempatLahir = strtoupper($peserta->tempat_lahir ?? '-');
        $tglLahir    = $peserta->tanggal_lahir ?? '';
        $alamat      = strtoupper($peserta->alamat_korespondensi ?? '-');
        $nik         = strtoupper($peserta->nik ?? '-');
        $kota        = strtoupper($peserta->kota ?? '-');

        $ttl = $tempatLahir . ', ' . $tglLahir;

        // Tanggal Download Hari Ini
        date_default_timezone_set('Asia/Jakarta');
        $bulanIndo = [
            1 => 'JANUARI',
            'FEBRUARI',
            'MARET',
            'APRIL',
            'MEI',
            'JUNI',
            'JULI',
            'AGUSTUS',
            'SEPTEMBER',
            'OKTOBER',
            'NOVEMBER',
            'DESEMBER'
        ];
        $tanggalSekarang = date('d') . ' ' . $bulanIndo[(int)date('m')] . ' ' . date('Y');

        // =====================================================================
        // LOGIKA PENAMPILAN TANDA TANGAN (GOOGLE DRIVE / LOKAL)
        // =====================================================================
        // Default HTML jika belum ada TTD (Hanya spasi kosong)
        $htmlTtd = '<br><br><br><br><br><br>';

        if (!empty($peserta->file_ttd)) {
            $fileData = $peserta->file_ttd;
            $imgSrc = '';

            // Cek apakah itu ID Google Drive (Tidak mengandung titik / ekstensi)
            if (strpos($fileData, '.') === false) {
                // Gunakan URL khusus download G-Drive
                $gdriveUrl = 'https://drive.google.com/uc?id=' . $fileData;

                // Gunakan cURL untuk mengambil gambar (mengakali redirect Google)
                $ch = curl_init($gdriveUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $imgData = curl_exec($ch);
                curl_close($ch);

                // Jika berhasil diambil, format ke Base64 agar dikenali TCPDF
                if ($imgData) {
                    $imgSrc = '@' . base64_encode($imgData);
                }
            } else {
                // Jika file TTD ada di folder lokal (uploads/ikh/)
                $path = FCPATH . 'uploads/ikh/' . $fileData;
                if (file_exists($path)) {
                    $imgSrc = $path; // TCPDF paling suka format absolute path
                }
            }

            // Jika sumber gambar valid, buat tag <img> dengan ukuran proporsional
            if ($imgSrc !== '') {
                // Ukuran diset 83x83 (Cukup proporsional untuk rasio 1:1)
                $htmlTtd = '<br><img src="' . $imgSrc . '" width="83" height="83"><br>';
            }
        }
        // =====================================================================

        // 8. Cetak Judul
        $pdf->SetFont($fontBold, '', 10);
        $pdf->Cell(0, 10, 'SURAT PERNYATAAN', 0, 1, 'C');
        $pdf->Ln(5);

        // 9. Susun Konten HTML dengan Data
        $pdf->SetFont($fontRegular, '', 10);

        // Catatan: Saya menggunakan tag <table> untuk daftar angka (1. & 2.) 
        // agar teks baris kedua pada poin ke-2 menjorok dengan rapi, tidak menabrak angka "2".
        $html = '
        <div style="text-align: justify; text-indent: 0px;">Saya yang bertanda tangan di bawah ini:</div>
        
        <br><br>

        <table cellpadding="2">
            <tr>
                <td width="38%">Nama</td>
                <td width="3%">:</td>
                <td width="59%">' . $namaLengkap . '</td>
            </tr>
            <tr>
                <td>Tempat dan Tanggal Lahir</td>
                <td>:</td>
                <td>' . $ttl . '</td>
            </tr>
            <tr>
                <td>Alamat Rumah</td>
                <td>:</td>
                <td>' . $alamat . '</td>
            </tr>
            <tr>
                <td>Nomor Kartu Tanda Penduduk</td>
                <td>:</td>
                <td>' . $nik . '</td>
            </tr>
        </table>

        <br><br>

        <div style="text-align: justify; text-indent: 0px;">Sebagai Kuasa Hukum pada Pengadilan Pajak, dengan ini menyatakan bahwa saya:</div>

        <br><br>

        <table cellpadding="1">
            <tr>
                <td width="4%" style="text-align: left;">1.</td>
                <td width="96%" style="text-align: justify;">tidak berstatus sebagai Pegawai Negeri Sipil pada Pemerintah Pusat/Daerah atau pejabat negara;</td>
            </tr>
            <tr>
                <td style="text-align: left;">2.</td>
                <td style="text-align: justify;">apabila melanggar hal-hal yang telah saya nyatakan dalam Surat Pernyataan ini, saya bersedia dikenakan sanksi sesuai dengan ketentuan peraturan perundang-undangan yang berlaku.</td>
            </tr>
        </table>

        <br><br>

        <div style="text-align: justify; text-indent: 0px;">Demikian Surat Pernyataan sebagai Kuasa Hukum pada Pengadilan Pajak ini dibuat dengan sebenar-benarnya dan akan saya pertanggungjawabkan sebagaimana mestinya.</div>

        <br><br><br><br><br>

        <table cellpadding="1">
            <tr>
                <td width="55%"></td>
                <td width="45%" style="text-align: center;">
                    '.$kota.', ' . $tanggalSekarang . '
                    ' . $htmlTtd . '
                    ( ' . $namaLengkap . ' )
                </td>
            </tr>
        </table>
        ';

        // 10. Render ke PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // 11. Output
        $this->response->setContentType('application/pdf');

        $namaFile = 'Surat_Pernyataan_Bukan_PNS_' . str_replace(' ', '_', $namaLengkap) . '.pdf';
        $pdf->Output($namaFile, 'I');
        exit();
    }

    // Tambahkan fungsi ini di bawah fungsi cetakSuratPernyataanBukanPns($id)
    public function cetakSuratPernyataanIkh($idikh)
    {
        $id = decrypt_url($idikh);
        // 1. Inisialisasi TCPDF dengan ukuran A4
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // 2. Pengaturan Meta Dokumen
        $pdf->SetCreator('Sistem Kelas Brevet');
        $pdf->SetTitle('Surat Pernyataan IKH');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // 3. PENGATURAN MARGIN DAN PADDING
        $marginKiriLuar  = 20.07;
        $marginAtasLuar  = 18.03;
        $marginKananLuar = 20.07;
        $marginBawahLuar = 18.03;
        $padding = 5;

        $pdf->SetMargins($marginKiriLuar + $padding, $marginAtasLuar + $padding, $marginKananLuar + $padding);
        $pdf->SetAutoPageBreak(TRUE, $marginBawahLuar + $padding);

        // 4. Tambah Halaman
        $pdf->AddPage();

        // 5. MEMBUAT BORDER KOTAK 
        $pdf->Rect($marginKiriLuar, $marginAtasLuar, 169.86, 260.94, 'D');

        // 6. LOAD FONT BOOKMAN OLD STYLE
        $fontRegularPath = FCPATH . 'fonts/bookos.ttf';
        $fontBoldPath    = FCPATH . 'fonts/bookos.ttf';

        if (!file_exists($fontRegularPath) || !file_exists($fontBoldPath)) {
            die('Error: File font bookos.ttf atau bookosb.ttf tidak ditemukan di public/fonts/');
        }

        $fontRegular = \TCPDF_FONTS::addTTFfont($fontRegularPath, 'TrueTypeUnicode', '', 96);
        $fontBold    = \TCPDF_FONTS::addTTFfont($fontBoldPath, 'TrueTypeUnicode', '', 96);

        // =====================================================================
        // 7. AMBIL DATA DARI DATABASE
        // =====================================================================
        $dataPeserta = $this->ikhModel->find($id);$dataPeserta = $this->ikhModel
            ->select('pendaftaran_ikh.*, siswa.kota')
            ->join('siswa', 'pendaftaran_ikh.id_siswa = siswa.id_siswa')
            ->where('pendaftaran_ikh.id_ikh', $id) // Ganti 'id_ikh' dengan nama primary key tabel pendaftaran_ikh Anda
            ->first();

        if (!$dataPeserta) {
            die('Data peserta tidak ditemukan.');
        }

        $peserta = is_array($dataPeserta) ? (object) $dataPeserta : $dataPeserta;

        $namaLengkap = strtoupper($peserta->nama_lengkap ?? '-');
        $tempatLahir = strtoupper($peserta->tempat_lahir ?? '-');
        $tglLahir    = $peserta->tanggal_lahir ?? '';
        $alamat      = strtoupper($peserta->alamat_korespondensi ?? '-');
        $nik         = strtoupper($peserta->nik ?? '-');
        $kota        = strtoupper($peserta->kota ?? '-');

        $ttl = $tempatLahir . ', ' . $tglLahir;

        // Tanggal Download Hari Ini
        date_default_timezone_set('Asia/Jakarta');
        $bulanIndo = [
            1 => 'JANUARI',
            'FEBRUARI',
            'MARET',
            'APRIL',
            'MEI',
            'JUNI',
            'JULI',
            'AGUSTUS',
            'SEPTEMBER',
            'OKTOBER',
            'NOVEMBER',
            'DESEMBER'
        ];
        $tanggalSekarang = date('d') . ' ' . $bulanIndo[(int)date('m')] . ' ' . date('Y');

        // =====================================================================
        // LOGIKA PENAMPILAN TANDA TANGAN (GOOGLE DRIVE / LOKAL)
        // =====================================================================
        // Default HTML jika belum ada TTD (Hanya spasi kosong)
        $htmlTtd = '<br><br><br><br><br><br>';

        if (!empty($peserta->file_ttd)) {
            $fileData = $peserta->file_ttd;
            $imgSrc = '';

            // Cek apakah itu ID Google Drive (Tidak mengandung titik / ekstensi)
            if (strpos($fileData, '.') === false) {
                // Gunakan URL khusus download G-Drive
                $gdriveUrl = 'https://drive.google.com/uc?id=' . $fileData;

                // Gunakan cURL untuk mengambil gambar (mengakali redirect Google)
                $ch = curl_init($gdriveUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $imgData = curl_exec($ch);
                curl_close($ch);

                // Jika berhasil diambil, format ke Base64 agar dikenali TCPDF
                if ($imgData) {
                    $imgSrc = '@' . base64_encode($imgData);
                }
            } else {
                // Jika file TTD ada di folder lokal (uploads/ikh/)
                $path = FCPATH . 'uploads/ikh/' . $fileData;
                if (file_exists($path)) {
                    $imgSrc = $path; // TCPDF paling suka format absolute path
                }
            }

            // Jika sumber gambar valid, buat tag <img> dengan ukuran proporsional
            if ($imgSrc !== '') {
                // Ukuran diset 83x83 (Cukup proporsional untuk rasio 1:1)
                $htmlTtd = '<br><img src="' . $imgSrc . '" width="83" height="83"><br>';
            }
        }
        // =====================================================================

        // 8. Cetak Judul
        $pdf->SetFont($fontBold, '', 10);
        $pdf->Cell(0, 10, 'SURAT PERNYATAAN', 0, 1, 'C');
        $pdf->Ln(5);

        // 9. Susun Konten HTML dengan Data
        $pdf->SetFont($fontRegular, '', 10);

        // Perhatikan bagian isi pernyataan di bawah ini. Anda bisa mengubah teks
        // di dalam tag <td> sesuai dengan redaksi resmi IKH Pengadilan Pajak.
        $html = '
        <div style="text-align: justify; text-indent: 0px;">Saya yang bertanda tangan di bawah ini:</div>
        
        <br><br>

        <table cellpadding="2">
            <tr>
                <td width="38%">Nama (sesuai KTP)</td>
                <td width="3%">:</td>
                <td width="59%">' . $namaLengkap . '</td>
            </tr>
            <tr>
                <td>Tempat dan Tanggal Lahir</td>
                <td>:</td>
                <td>' . $ttl . '</td>
            </tr>
            <tr>
                <td>Alamat Rumah</td>
                <td>:</td>
                <td>' . $alamat . '</td>
            </tr>
            <tr>
                <td>Nomor Kartu Tanda Penduduk</td>
                <td>:</td>
                <td>' . $nik . '</td>
            </tr>
        </table>

        <br><br>

        <div style="text-align: justify; text-indent: 0px;">dengan ini menyatakan bahwa:</div>

        <br><br>

        <table cellpadding="1">
            <tr>
                <td width="4%" style="text-align: left;">1.</td>
                <td width="96%" style="text-align: justify;">saya benar telah mengajukan permohonan Izin Kuasa Hukum Pengadilan Pajak;</td>
            </tr>
            <tr>
                <td style="text-align: left;">2.</td>
                <td style="text-align: justify;">data dan dokumen yang saya sampaikan untuk permohonan Izin Kuasa Hukum adalah benar dan sesuai aslinya;</td>
            </tr>
            <tr>
                <td style="text-align: left;">3.</td>
                <td style="text-align: justify;">saya telah membaca, memahami, dan bersedia untuk mentaati Peraturan Ketua Pengadilan Pajak tentang Tata Tertib Persidangan Pengadilan Pajak;</td>
            </tr>
            <tr>
                <td style="text-align: left;">4.</td>
                <td style="text-align: justify;">apabila melanggar hal-hal yang telah saya nyatakan dalam Surat Pernyataan ini, Izin Kuasa Hukum saya bersedia untuk dicabut dan dikenakan sanksi sesuai dengan ketentuan peraturan perundang-undangan yang berlaku.</td>
            </tr>
        </table>

        <br><br>

        <div style="text-align: justify; text-indent: 0px;">Demikian Surat Pernyataan sebagai Kuasa Hukum pada Pengadilan Pajak ini dibuat dengan sebenar-benarnya dan akan saya pertanggungjawabkan sebagaimana mestinya.</div>

        <br><br><br><br><br>

        <table cellpadding="1">
            <tr>
                <td width="55%"></td>
                <td width="45%" style="text-align: center;">
                    '.$kota.', ' . $tanggalSekarang . '
                    ' . $htmlTtd . '
                    ( ' . $namaLengkap . ' )
                </td>
            </tr>
        </table>
        ';

        // 10. Render ke PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // 11. Output
        $this->response->setContentType('application/pdf');

        $namaFile = 'Surat_Pernyataan_IKH_' . str_replace(' ', '_', $namaLengkap) . '.pdf';
        $pdf->Output($namaFile, 'I');
        exit();
    }

    // Tambahkan fungsi ini di bawah fungsi sebelumnya di PdfController
    public function cetakPaktaIntegritas($idikh)
    {
        $id = decrypt_url($idikh);
        // 1. Inisialisasi TCPDF dengan ukuran A4
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // 2. Pengaturan Meta Dokumen
        $pdf->SetCreator('Sistem Kelas Brevet');
        $pdf->SetTitle('Pakta Integritas');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // 3. PENGATURAN MARGIN DAN PADDING
        $marginKiriLuar  = 20.07;
        $marginAtasLuar  = 18.03;
        $marginKananLuar = 20.07;
        $marginBawahLuar = 18.03;
        $padding = 5;

        $pdf->SetMargins($marginKiriLuar + $padding, $marginAtasLuar + $padding, $marginKananLuar + $padding);
        $pdf->SetAutoPageBreak(TRUE, $marginBawahLuar + $padding);

        // 4. Tambah Halaman
        $pdf->AddPage();

        // 5. MEMBUAT BORDER KOTAK 
        $pdf->Rect($marginKiriLuar, $marginAtasLuar, 169.86, 260.94, 'D');

        // 6. LOAD FONT BOOKMAN OLD STYLE
        $fontRegularPath = FCPATH . 'fonts/bookos.ttf';
        $fontBoldPath    = FCPATH . 'fonts/bookos.ttf';

        if (!file_exists($fontRegularPath) || !file_exists($fontBoldPath)) {
            die('Error: File font bookos.ttf atau bookosb.ttf tidak ditemukan di public/fonts/');
        }

        $fontRegular = \TCPDF_FONTS::addTTFfont($fontRegularPath, 'TrueTypeUnicode', '', 96);
        $fontBold    = \TCPDF_FONTS::addTTFfont($fontBoldPath, 'TrueTypeUnicode', '', 96);

        // =====================================================================
        // 7. AMBIL DATA DARI DATABASE
        // =====================================================================
        $dataPeserta = $this->ikhModel->find($id);$dataPeserta = $this->ikhModel
            ->select('pendaftaran_ikh.*, siswa.kota')
            ->join('siswa', 'pendaftaran_ikh.id_siswa = siswa.id_siswa')
            ->where('pendaftaran_ikh.id_ikh', $id) // Ganti 'id_ikh' dengan nama primary key tabel pendaftaran_ikh Anda
            ->first();

        if (!$dataPeserta) {
            die('Data peserta tidak ditemukan.');
        }

        $peserta = is_array($dataPeserta) ? (object) $dataPeserta : $dataPeserta;

        $namaLengkap = strtoupper($peserta->nama_lengkap ?? '-');
        $tempatLahir = strtoupper($peserta->tempat_lahir ?? '-');
        $tglLahir    = $peserta->tanggal_lahir ?? '';
        $alamat      = strtoupper($peserta->alamat_korespondensi ?? '-');
        $nik         = strtoupper($peserta->nik ?? '-');
        $kota        = strtoupper($peserta->kota ?? '-');

        $ttl = $tempatLahir . ', ' . $tglLahir;

        // Tanggal Download Hari Ini
        date_default_timezone_set('Asia/Jakarta');
        $bulanIndo = [
            1 => 'JANUARI',
            'FEBRUARI',
            'MARET',
            'APRIL',
            'MEI',
            'JUNI',
            'JULI',
            'AGUSTUS',
            'SEPTEMBER',
            'OKTOBER',
            'NOVEMBER',
            'DESEMBER'
        ];
        $tanggalSekarang = date('d') . ' ' . $bulanIndo[(int)date('m')] . ' ' . date('Y');

        // =====================================================================
        // LOGIKA PENAMPILAN TANDA TANGAN (GOOGLE DRIVE / LOKAL)
        // =====================================================================
        // Default HTML jika belum ada TTD (Hanya spasi kosong)
        $htmlTtd = '<br><br><br><br><br><br>';

        if (!empty($peserta->file_ttd)) {
            $fileData = $peserta->file_ttd;
            $imgSrc = '';

            // Cek apakah itu ID Google Drive (Tidak mengandung titik / ekstensi)
            if (strpos($fileData, '.') === false) {
                // Gunakan URL khusus download G-Drive
                $gdriveUrl = 'https://drive.google.com/uc?id=' . $fileData;

                // Gunakan cURL untuk mengambil gambar (mengakali redirect Google)
                $ch = curl_init($gdriveUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $imgData = curl_exec($ch);
                curl_close($ch);

                // Jika berhasil diambil, format ke Base64 agar dikenali TCPDF
                if ($imgData) {
                    $imgSrc = '@' . base64_encode($imgData);
                }
            } else {
                // Jika file TTD ada di folder lokal (uploads/ikh/)
                $path = FCPATH . 'uploads/ikh/' . $fileData;
                if (file_exists($path)) {
                    $imgSrc = $path; // TCPDF paling suka format absolute path
                }
            }

            // Jika sumber gambar valid, buat tag <img> dengan ukuran proporsional
            if ($imgSrc !== '') {
                // Ukuran diset 83x83 (Cukup proporsional untuk rasio 1:1)
                $htmlTtd = '<br><img src="' . $imgSrc . '" width="83" height="83"><br>';
            }
        }
        // =====================================================================

        // 8. Cetak Judul
        $pdf->SetFont($fontBold, '', 10);
        $pdf->Cell(0, 10, 'PAKTA INTEGRITAS', 0, 1, 'C');
        $pdf->Ln(5);

        // 9. Susun Konten HTML dengan Data
        $pdf->SetFont($fontRegular, '', 10);

        $html = '
        <div style="text-align: justify; text-indent: 0px;">Saya yang bertanda tangan di bawah ini:</div>
        
        <br><br>

        <table cellpadding="2">
            <tr>
                <td width="38%">Nama</td>
                <td width="3%">:</td>
                <td width="59%">' . $namaLengkap . '</td>
            </tr>
            <tr>
                <td>Tempat dan Tanggal Lahir</td>
                <td>:</td>
                <td>' . $ttl . '</td>
            </tr>
            <tr>
                <td>Alamat Rumah</td>
                <td>:</td>
                <td>' . $alamat . '</td>
            </tr>
            <tr>
                <td>Nomor Kartu Tanda Penduduk</td>
                <td>:</td>
                <td>' . $nik . '</td>
            </tr>
        </table>

        <br><br>

        <div style="text-align: justify; text-indent: 0px;">Sebagai Kuasa Hukum pada Pengadilan Pajak, dengan ini menyatakan bahwa saya:</div>

        <br><br>

        <table cellpadding="1">
            <tr>
                <td width="4%" style="text-align: left;">1.</td>
                <td width="96%" style="text-align: justify;">dalam menjalankan tugas sebagai Kuasa Hukum pada Pengadilan Pajak, saya berjanji akan melaksanakan peraturan perundang-undangan dengan sebaik-baiknya dan sebenar-benarnya;</td>
            </tr>
            <tr>
                <td style="text-align: left;">2.</td>
                <td style="text-align: justify;">tidak akan melakukan praktik-praktik korupsi, kolusi, dan nepotisme (KKN);</td>
            </tr>
            <tr>
                <td style="text-align: left;">3.</td>
                <td style="text-align: justify;">akan menjalankan tugas secara bertanggung jawab, transparan, dan profesional sesuai ketentuan peraturan perundang-undangan yang berlaku;</td>
            </tr>
            <tr>
                <td style="text-align: left;">4.</td>
                <td style="text-align: justify;">apabila melanggar hal-hal yang telah saya nyatakan dalam Pakta Integritas ini, saya bersedia dikenakan sanksi sesuai dengan ketentuan peraturan perundang-undangan yang berlaku.</td>
            </tr>
        </table>

        <br><br>

        <div style="text-align: justify; text-indent: 0px;">Demikian Pakta Integritas sebagai Kuasa Hukum pada Pengadilan Pajak ini dibuat dengan sebenar-benarnya dan akan saya pertanggungjawabkan sebagaimana mestinya.</div>

        <br><br><br><br><br>

        <table cellpadding="1">
            <tr>
                <td width="55%"></td>
                <td width="45%" style="text-align: center;">
                    '.$kota.', ' . $tanggalSekarang . '
                    ' . $htmlTtd . '
                    (' . $namaLengkap . ')
                </td>
            </tr>
        </table>
        ';

        // 10. Render ke PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // 11. Output
        $this->response->setContentType('application/pdf');

        $namaFile = 'Pakta_Integritas_' . str_replace(' ', '_', $namaLengkap) . '.pdf';
        $pdf->Output($namaFile, 'I');
        exit();
    }
}

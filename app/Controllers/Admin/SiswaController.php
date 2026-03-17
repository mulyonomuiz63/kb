<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\Emailer;
use App\Libraries\Pdf;
use App\Models\KelasModel;
use App\Models\SiswaModel;
use App\Models\UjianMasterModel;
use App\Models\UjianModel;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class SiswaController extends BaseController
{
    protected $siswaModel;
    protected $ujianModel;
    protected $ujianMasterModel;
    protected $kelasModel;
    protected $emailer;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->ujianModel = new UjianModel();
        $this->ujianMasterModel = new UjianMasterModel();
        $this->kelasModel = new KelasModel();
        $this->emailer = new Emailer();
    }

    public function index()
    {
        $data = [
            'title' => 'List Peserta',
            'kelas' => $this->kelasModel->asObject()->findAll(), // Asumsi model kelas
        ];
        return view('admin/siswa/list', $data);
    }

    // Di App\Models\SiswaModel.php
    public function datatable()
    {
        $request = $this->request;
        $postData = $request->getPost();

        $column_order  = ['no_induk_siswa', 'nama_siswa', 'email', 'hp', 'date_created', 'is_active'];
        $column_search = ['nama_siswa', 'no_induk_siswa', 'email'];
        $order         = ['id_siswa' => 'DESC'];

        // Ambil data dari model
        $list = $this->siswaModel->get_datatables($column_order, $column_search, $order);
        $data = [];

        foreach ($list as $s) {
            // Hitung statistik ujian
            $totalUjian = $this->ujianModel->where([
                'kelas'    => $s->kelas,
                'id_siswa' => $s->id_siswa,
                'nilai >=' => 60
            ])->groupBy('mapel')->countAllResults();

            $totalSertifikats = $this->ujianModel->where([
                'kelas'    => $s->kelas,
                'id_siswa' => $s->id_siswa
            ])->groupBy('mapel')->countAllResults();

            // PAKAI KEY (Associative Array) agar sinkron dengan JS 'columns'
            $row = [
                "no_induk_siswa" => $s->no_induk_siswa,
                "nama_siswa"     => $s->nama_siswa,
                "email"          => $s->email,
                "hp"             => $s->hp,
                "date_created"   => date('d-m-Y', $s->date_created),
                "is_active"      => $s->is_active,
                "stats"          => $totalUjian . '/' . $totalSertifikats,
                "id_siswa_enc"   => encrypt_url($s->id_siswa) // Untuk tombol aksi
            ];

            $data[] = $row;
        }

        $output = [
            "draw"            => intval($postData['draw'] ?? 1),
            "recordsTotal"    => $this->siswaModel->countAllResults(),
            "recordsFiltered" => $this->siswaModel->countFiltered($column_order, $column_search, $order),
            "data"            => $data,
            "csrf_hash"       => csrf_hash()
        ];

        return $this->response->setJSON($output);
    }

    public function create()
    {
        $data = [
            'title'        => 'Tambah Peserta',
            'parent_title' => 'List Peserta',
            'parent_url'   => base_url('sw-admin/siswa'),
            'kelas' => $this->kelasModel->asObject()->findAll(), // Asumsi model kelas
        ];
        return view('admin/siswa/create', $data);
    }

    public function store()
    {
        // 2. Definisi Aturan Validasi
        $rules = [
            'nis.*' => [
                'rules'  => 'required|min_length[10]|max_length[15]|numeric',
                'errors' => [
                    'required'   => 'Nomor HP/WhatsApp harus diisi.',
                    'min_length' => 'Nomor HP minimal 10 digit.',
                    'max_length' => 'Nomor HP maksimal 15 digit.',
                    'numeric'    => 'Nomor HP harus berupa angka.'
                ]
            ],
            'nama_siswa.*' => [
                'rules'  => 'required|min_length[3]',
                'errors' => [
                    'required'   => 'Nama lengkap tidak boleh kosong.',
                    'min_length' => 'Nama minimal terdiri dari 3 karakter.'
                ]
            ],
            'email.*' => [
                'rules'  => 'required|valid_email|is_unique[siswa.email]',
                'errors' => [
                    'required'    => 'Alamat email wajib diisi.',
                    'valid_email' => 'Format email tidak valid.',
                    'is_unique'   => 'Email ini sudah terdaftar, gunakan email lain.'
                ]
            ],
            'kelas.*' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Pilih kelas untuk setiap peserta.']
            ],
        ];

        // 3. Jalankan Validasi
        if (!$this->validate($rules)) {
            // Ambil satu pesan error pertama untuk ditampilkan di SweetAlert
            $errors = $this->validator->getErrors();
            $firstError = reset($errors);

            session()->setFlashdata('error', $firstError);
            return redirect()->back()->withInput();
        }

        // 4. Ambil Data Input
        $nisArray          = $this->request->getVar('nis');
        $namaSiswaArray    = $this->request->getVar('nama_siswa');
        $emailArray        = $this->request->getVar('email');
        $jkArray           = $this->request->getVar('jenis_kelamin');
        $kelasArray        = $this->request->getVar('kelas');
        $tglBerakhirGlobal = $this->request->getVar('tanggal_berakhir_global');

        // 5. Mulai Proses Database & Email
        try {
            $data_batch = [];

            foreach ($namaSiswaArray as $index => $nama) {
                $data_batch[] = [
                    'no_induk_siswa'   => $nisArray[$index],
                    'nama_siswa'       => $nama,
                    'email'            => $emailArray[$index],
                    'password'         => password_hash($nisArray[$index], PASSWORD_DEFAULT),
                    'jenis_kelamin'    => $jkArray[$index],
                    'kelas'            => $kelasArray[$index],
                    'tanggal_berakhir' => $tglBerakhirGlobal,
                    'role'             => 2,
                    'is_active'        => 1,
                    'date_created'     => time(),
                    'avatar'           => 'default.jpg'
                ];
            }

            // Insert Batch
            if (!empty($data_batch)) {
                $this->siswaModel->insertBatch($data_batch);
            }

            // 6. Kirim Email Loop
            foreach ($data_batch as $siswa) {
                $template = '
                <div style="color: #000; padding: 20px; font-family: sans-serif; border: 1px solid #eee; border-radius: 8px;">
                    <h2 style="color: #1C3FAA; margin-top: 0;">REGISTRASI BERHASIL</h2>
                    <p>Halo <b>' . $siswa['nama_siswa'] . '</b>,</p>
                    <p>Akun Anda telah berhasil didaftarkan ke sistem <b>KelasBrevet</b>. Silakan gunakan detail berikut untuk masuk:</p>
                    <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;">
                        <table style="width: 100%;">
                            <tr><td style="width: 100px; color: #666;">Email</td><td>: <b>' . $siswa['email'] . '</b></td></tr>
                            <tr><td style="color: #666;">Kata Sandi</td><td>: <b>' . $siswa['no_induk_siswa'] . '</b></td></tr>
                        </table>
                    </div>
                    <a href="' . base_url('auth') . '" style="display: inline-block; padding: 12px 25px; background: #1C3FAA; color: #fff; text-decoration: none; border-radius: 5px; font-weight: bold;">Masuk ke Dashboard</a>
                    <p style="font-size: 12px; color: #888; margin-top: 25px; border-top: 1px dashed #ccc; padding-top: 10px;">
                        Pesan ini dikirim otomatis oleh sistem. Jangan membalas email ini.
                    </p>
                </div>';

                $this->emailer->sendEmail($siswa['email'], 'Selamat Datang di KelasBrevet', $template);
            }

            session()->setFlashdata('success', 'Berhasil! ' . count($data_batch) . ' data peserta telah disimpan dan email notifikasi dikirim.');
        } catch (\Exception $e) {
            // Cek jika error disebabkan oleh data duplikat (Unique Constraint)
            $msg = $e->getMessage();
            if (strpos($msg, 'Duplicate entry') !== false) {
                $msg = "Gagal: Nomor HP atau Email sudah terdaftar di sistem.";
            }

            session()->setFlashdata('error', $msg);
        }

        return redirect()->to('sw-admin/siswa');
    }

    public function edit($id)
    {
        $siswa = $this->siswaModel->find(decrypt_url($id));
        if (!$siswa) {
            return redirect()->back()->with('error', 'Data peserta tidak ditemukan.');
        }

        $data = [
            'title'        => 'Edit Peserta',
            'parent_title' => 'List Peserta',
            'parent_url'   => base_url('sw-admin/siswa'),
            'siswa'        => $siswa,
            'kelas'        => $this->kelasModel->asObject()->findAll(), // Asumsi model kelas
        ];

        return view('admin/siswa/edit', $data);
    }

    public function update($id)
    {
        // 1. Dekripsi ID
        $id_decoded = decrypt_url($id);

        // 2. Definisi Aturan Validasi dengan Pesan Bahasa Indonesia
        $rules = [
            'nama_siswa' => [
                'rules'  => 'required|min_length[3]',
                'errors' => [
                    'required'   => 'Nama peserta harus diisi.',
                    'min_length' => 'Nama peserta minimal 3 karakter.'
                ]
            ],
            'email' => [
                'rules'  => "required|valid_email|is_unique[siswa.email,id_siswa,$id_decoded]",
                'errors' => [
                    'required'    => 'Email tidak boleh kosong.',
                    'valid_email' => 'Format email tidak valid.',
                    'is_unique'   => 'Email ini sudah digunakan oleh peserta lain.'
                ]
            ],
            'kelas' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Silakan pilih kelas.'
                ]
            ]
        ];

        // 3. Jalankan Validasi
        if (!$this->validate($rules)) {
            // Ambil pesan error pertama saja agar user tidak bingung

            $errors = $this->validator->getErrors();
            $firstError = reset($errors);

            session()->setFlashdata('error', $firstError);
            return redirect()->back()->withInput();
        }

        try {
            // 4. Siapkan Data untuk Update
            $data = [
                'nama_siswa' => $this->request->getPost('nama_siswa'),
                'email'      => $this->request->getPost('email'),
                'kelas'      => $this->request->getPost('kelas'),
                'is_active'  => $this->request->getPost('active'),
                'status'     => $this->request->getPost('status'),
                'status_data' => $this->request->getPost('status_data'),
            ];

            // 5. Logika Password (Hanya update jika diisi)
            $password = $this->request->getPost('password');
            if (!empty($password)) {
                // Gunakan password_hash agar aman (bcrypt)
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            // 6. Eksekusi Update
            $this->siswaModel->update($id_decoded, $data);

            return redirect()->to('sw-admin/siswa')->with('success', 'Data peserta berhasil diperbarui.');
        } catch (\Exception $e) {
            // Tangkap error jika terjadi masalah pada database
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
    public function detail()
    {
        if ($this->request->isAJAX()) {
            $id_siswa_enc = $this->request->getVar('id_siswa');

            // 1. Dekripsi dan validasi ID
            $id_siswa = decrypt_url($id_siswa_enc);

            if (!$id_siswa) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'ID tidak valid',
                    'csrf_hash' => csrf_hash() // Tetap kirim hash baru
                ]);
            }

            $data_siswa = $this->siswaModel->getId($id_siswa);

            if ($data_siswa) {
                // 2. Gabungkan data siswa dengan CSRF Hash terbaru
                // Kita ubah ke array dulu agar bisa di-merge dengan hash
                $result = array_merge((array) $data_siswa, [
                    'csrf_hash' => csrf_hash()
                ]);

                return $this->response->setJSON($result);
            } else {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Data tidak ditemukan',
                    'csrf_hash' => csrf_hash()
                ]);
            }
        }

        // Jika diakses langsung secara non-AJAX
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    public function sertifikat($id)
    {

        $jumlahLulus = $this->ujianModel->where('id_siswa', decrypt_url($id))
            ->where('nilai >=', 60)
            ->countAllResults();


        // MASTER DATA
        $data = [
            'title'        => 'List Sertifikat',
            'parent_title' => 'List Peserta',
            'parent_url'   => base_url('sw-admin/siswa'),
            'idsiswa'        => $id,
            'canDownloadAll' => ($jumlahLulus >= 5)
        ];
        return view('admin/sertifikat/list', $data);
    }

    public function getDataSertifikat()
    {
        if ($this->request->isAJAX()) {
            $id_siswa_enc = $this->request->getPost('id_siswa');
            $id_siswa = decrypt_url($id_siswa_enc);

            // 1. Ambil data siswa untuk mendapatkan informasi kelas
            $siswa = $this->siswaModel->find($id_siswa);

            if (!$siswa) {
                return $this->response->setJSON(['data' => [], 'csrf_hash' => csrf_hash()]);
            }

            // 2. Ambil data ujian berdasarkan kelas dan id_siswa (Logic dari model kamu)
            $tugas = $this->ujianModel->getAllByKelas($siswa['kelas'], $id_siswa);

            $rows = [];
            foreach ($tugas as $t) {
                // Kita siapkan link cetak di sini agar di JS tinggal panggil
                $url_base = base_url("sw-admin/siswa/lihatSertifikat") . '/' . encrypt_url($t->kode_ujian) . "/" . encrypt_url($t->id_ujian);

                $rows[] = [
                    'nama_ujian'   => $t->nama_ujian,
                    'start_ujian'  => $t->start_ujian,
                    'end_ujian'    => $t->end_ujian,
                    'nilai'        => (int)$t->nilai,
                    'url_cetak'    => $url_base,
                    'url_cetak_cap' => $url_base . '/cap',
                    'id_siswa_enc' => $id_siswa_enc // Untuk keperluan lain jika butuh
                ];
            }

            // 3. Format Response sesuai kebutuhan DataTables
            return $this->response->setJSON([
                'draw'            => intval($this->request->getPost('draw')),
                'recordsTotal'    => count($rows),
                'recordsFiltered' => count($rows),
                'data'            => $rows,
                'csrf_hash'       => csrf_hash() // Penting untuk keamanan request berikutnya
            ]);
        }
    }


    public function ujian($id)
    {
        $data = [
            'title'        => 'List Ujian',
            'parent_title' => 'List Peserta',
            'parent_url'   => base_url('sw-admin/siswa'),
            'idsiswa'        => $id,
        ];
        return view('admin/ujian/list', $data);
    }

    public function getDataUjian()
    {
        $request = $this->request;
        $db = $this->db;

        $id_siswa_enc = $request->getPost('id_siswa');
        $id_siswa = decrypt_url($id_siswa_enc);

        $siswa_list = $this->siswaModel->where('id_siswa', $id_siswa)->get()->getResultObject();

        if (!$siswa_list) {
            return $this->response->setJSON(["data" => []]);
        }

        $data = [];
        foreach ($siswa_list as $r) {
            $tugas = $this->ujianModel->getAllByKelas($r->kelas, $r->id_siswa);

            foreach ($tugas as $u) {
                // --- LOGIKA HITUNG DURASI (3 Menit per Soal) ---
                $jmlSoal = $db->query("SELECT COUNT(kode_ujian) as total_soal FROM ujian_detail WHERE kode_ujian = '$u->kode_ujian'")->getRow();
                $totalSoal = $jmlSoal ? $jmlSoal->total_soal : 0;
                $durasiMenit = $totalSoal * 3;

                // --- LOGIKA TOMBOL AKSI ---
                $btn_list = []; // Gunakan array untuk menampung tombol agar mudah dikelola

                if ($u->status == 'B') {
                    $btn_list[] = '<a href="javascript:void(0)" class="btn btn-primary btn-sm disabled"><i class="fas fa-play mr-1"></i> Mulai</a>';
                } elseif ($u->status == 'U') {
                    $btn_list[] = '<a href="#" class="btn btn-warning btn-sm"><i class="fas fa-edit mr-1"></i> Sedang Ujian</a>';
                } else {
                    if ($u->kuota != '0') {
                        $btn_list[] = '<a href="javascript:void(0)" class="btn btn-danger btn-sm disabled"><i class="fas fa-redo mr-1"></i> Ulang</a>';
                    } else {
                        if ($u->nilai >= 60) {
                            $btn_list[] = '<a href="' . base_url('siswa/sertifikat/') . '" class="btn btn-success btn-sm"><i class="fas fa-certificate mr-1"></i> Selesai</a>';
                        } else {
                            $btn_list[] = '<a href="' . base_url('/#bimbel') . '" class="btn btn-warning btn-sm btn-ujian-ulang"><i class="fas fa-sync mr-1"></i> Ulang</a>';
                        }
                    }
                }

                // Tambahan Tombol Hapus jika kuota == 3
                if ($u->kuota == 3) {
                    $url_hapus = base_url('sw-admin/siswa/deleteUjian') . '/' . encrypt_url($u->id_ujian) . '/' . encrypt_url($u->id_siswa);
                    $btn_list[] = '<a href="javascript:void(0)" data-url="' . $url_hapus . '" class="btn btn-outline-danger btn-sm btn-delete" title="Hapus Data"><i class="bi bi-trash"></i></a>';
                }

                // --- BINDING KE OBJECT DATA ---
                $row = new \stdClass();
                $row->nama_ujian = $u->nama_ujian;
                $row->nama_kelas = $u->nama_kelas;

                // Kolom Kuota (dengan Badge & Modal)
                $row->kuota_html = '<a href="javascript:void(0)" data-toggle="modal" data-target="#tambah_kuota" class="edit_kuota badge badge-success p-2" 
                        data-idsiswa="' . encrypt_url($u->id_siswa) . '" 
                        data-idujian="' . encrypt_url($u->id_ujian) . '" 
                        data-kuota="' . $u->kuota . '"><i class="fas fa-ticket-alt mr-1"></i> ' . $u->kuota . ' Kali</a>';

                $row->durasi_menit = $durasiMenit . ' Menit';
                $row->nilai        = $u->nilai ?? '-';

                // Kolom Lulus/Tidak Lulus
                $row->status_lulus = $u->nilai === null ? '-' : ($u->nilai >= 60 ? '<span class="badge badge-success">Lulus</span>' : '<span class="badge badge-danger">Tidak Lulus</span>');

                // MENGGABUNGKAN TOMBOL DENGAN FLEXBOX
                // justify-content-center agar tombol di tengah kolom
                // Jika Bootstrap 4 belum support 'gap', kita gunakan 'mx-1' pada setiap tombol
                $row->aksi = '<div class="d-flex justify-content-center align-items-center flex-nowrap" style="gap: 4px;">' . implode('', $btn_list) . '</div>';

                $data[] = $row;
            }
        }

        return $this->response->setJSON([
            "draw"            => intval($request->getPost('draw')),
            "recordsTotal"    => count($data),
            "recordsFiltered" => count($data),
            "data"            => $data,
            "csrf_hash"       => csrf_hash()
        ]);
    }




    public function lihatSertifikat($kode_ujian, $id_ujian, $jenis = "")
    {
        $kode_ujian = decrypt_url($kode_ujian);
        $id_ujian = decrypt_url($id_ujian);
        $hasil = $this->ujianModel->getBykode($kode_ujian, $id_ujian);

        if (!$hasil) {
            return "Data tidak ditemukan";
        }
        new Pdf();
        $pdf = new \setasign\Fpdi\Fpdi();
        $pdf->SetAutoPageBreak(false, 0);

        // --- PAGE SETUP ---
        $pdf->AddPage('L', 'A4');
        $pdf->SetCreator("kelasbrevet.com");
        $pdf->SetAuthor("KELAS BREVET");
        $pdf->SetTitle("SERTIFIKAT - " . strtoupper($hasil->nama_siswa));

        // --- BACKGROUND IMAGE ---
        // Gunakan background yang berbeda berdasarkan $jenis (cap/non-cap)
        $bg = ($jenis == "cap") ? 'brevet-materi-cap.jpg' : 'brevet-materi.jpg';
        $pathBg = FCPATH . 'uploads/sertifikat/' . $bg;

        if (file_exists($pathBg)) {
            $pdf->Image($pathBg, 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());
        }

        $pdf->SetTextColor(40, 40, 40); // Warna abu gelap lebih elegan daripada hitam pekat

        // --- 1. IZIN OPERASIONAL (Top Left) ---
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY(28, 12);
        $pdf->Cell(100, 5, "Izin Operasional Lembaga Kursus dan Pelatihan (LKP)", 0, 1, 'L');
        $pdf->SetX(28);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(100, 5, "Nomor: 500.16.7.2/0003/SPNF-LKP/IV.7/I/2025", 0, 1, 'L');

        // --- 2. NOMOR SERTIFIKAT ---
        $bulanNomor = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $tgl_ujian = strtotime($hasil->start_ujian);
        $noSertifikat = "Nomor: " . $hasil->id_ujian . '/ALC-BREVET/' . $bulanNomor[(int)date('m', $tgl_ujian) - 1] . '/' . date('Y', $tgl_ujian);

        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(0, 70); // Center alignment manual atau sesuaikan margin kiri
        $pdf->SetX(28);
        $pdf->Cell(0, 10, $noSertifikat, 0, 1, 'L');

        // --- 3. JUDUL MATERI / MAPEL ---
        $pdf->SetFont('Arial', 'B', 24);
        $pdf->SetTextColor(25, 50, 100); // Sedikit sentuhan warna biru tua branding
        $pdf->SetXY(28, 85);
        $pdf->Cell(0, 15, strtoupper($hasil->nama_mapel), 0, 1, 'L');

        // --- 4. NAMA PENERIMA (The Star of the Show) ---
        $pdf->SetTextColor(40, 40, 40);
        $pdf->SetFont('Arial', 'B', 32); // Ukuran lebih besar agar eksklusif
        $pdf->SetXY(28, 115);
        $pdf->Cell(0, 20, strtoupper($hasil->nama_siswa), 0, 1, 'L');

        // --- 5. NIP / NOMOR INDUK ---
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(28, 133);
        $pdf->Cell(0, 10, "NIP: " . $hasil->no_induk_siswa, 0, 1, 'L');

        // --- 6. STATUS KELULUSAN ---
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tglFormat = date('d', $tgl_ujian) . ' ' . $bulan[(int)date('m', $tgl_ujian) - 1] . ' ' . date('Y', $tgl_ujian);

        $pdf->SetFont('Arial', '', 15);
        $pdf->SetXY(28, 150);
        // Menggunakan MultiCell jika teks panjang
        $pdf->MultiCell(180, 8, "Dinyatakan LULUS dengan predikat nilai " . $hasil->nilai . " pada ujian materi tersebut yang diselenggarakan pada tanggal " . $tglFormat . ".", 0, 'L');

        // --- 7. QR CODE (Security Verification) ---
        $writer = new PngWriter();
        $qrCode = QrCode::create(base_url('detail/data/' . encrypt_url($hasil->id_ujian)))
            ->setSize(300)
            ->setMargin(0);

        $logo = Logo::create(FCPATH . 'assets/img/logo-brevet.png')->setResizeToWidth(60);
        $result = $writer->write($qrCode, $logo);

        // Render QR Code langsung ke PDF
        $pdf->Image($result->getDataUri(), 30, 172, 28, 28, 'png');

        // Metadata tambahan di bawah QR (opsional)
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->SetXY(30, 200);
        $pdf->Cell(30, 5, "Scan to Verify", 0, 0, 'C');

        // --- OUTPUT ---
        $this->response->setContentType('application/pdf');
        $filename = "Sertifikat_" . str_replace(' ', '_', $hasil->nama_siswa) . "_" . date('Ymd') . ".pdf";
        $pdf->Output($filename, 'I');
    }

    public function lihatSertifikatBrevet($id, $jenis = "")
    {
        // 1. DATA PREPARATION
        $id_siswa = decrypt_url($id);
        $hasilUjian = $this->ujianModel->getByIdsiswaSertifikat($id_siswa); // Materi & Nilai
        $siswa = $this->siswaModel->where('id_siswa', $id_siswa)->get()->getRowObject(); // Gunakan first() untuk RowObject CI4

        if (!$hasilUjian || !$siswa) {
            return "Data tidak ditemukan.";
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

        // ---------------------------------------------------------
        // PAGE 3-5: DOKUMEN SK & BIODATA (PORTRAIT)
        // ---------------------------------------------------------

        // Halaman 1 SK
        $pdf->AddPage('P');
        $pdf->Image(FCPATH . 'uploads/sertifikat/halaman_1.jpg', 0, 0, 210, 297);
        $pdf->SetXY(83, 48);
        $pdf->Cell(0, 5, $hasilUjian[0]->id_ujian . '/KEP-ALC-BREVET/' . $bulanNomor[(int)date('m', $tgl_akhir)] . '/' . date('Y', $tgl_akhir), 0, 1, 'L');
        $pdf->SetXY(110, 106);
        $pdf->Cell(0, 5, $strRangeTgl, 0, 1, 'L');

        // Halaman 2 SK
        $pdf->AddPage('P');
        $bgP4 = ($jenis == "") ? 'halaman_2.jpg' : 'halaman_cap_2.jpg';
        $pdf->Image(FCPATH . 'uploads/sertifikat/' . $bgP4, 0, 0, 210, 297);
        $pdf->SetXY(80, 33);
        $pdf->Cell(0, 5, strtoupper($siswa->nama_siswa), 0, 1, 'L');
        $pdf->SetXY(55, 84);
        $pdf->Cell(0, 5, $strTglAkhir, 0, 1, 'L');

        // Halaman 3 Biodata
        $pdf->AddPage('P');
        $pdf->Image(FCPATH . 'uploads/sertifikat/halaman_3.jpg', 0, 0, 210, 297);

        // Foto Siswa
        $urlAvatar = FCPATH . 'assets/app-assets/user/' . $siswa->avatar;
        if (file_exists($urlAvatar) && !empty($siswa->avatar)) {
            $pdf->Image($urlAvatar, 27.8, 28, 40, 52);
        }

        // List Biodata (Gunakan Loop untuk efisiensi posisi Y)
        $pdf->SetFont('Arial', '', 11);
        $startY = 92;
        $dataSiswa = [
            $siswa->nik,
            $siswa->nama_siswa,
            $siswa->tgl_lahir,
            $siswa->jenis_kelamin,
            $this->_limitStr($siswa->alamat_ktp),
            $this->_limitStr($siswa->alamat_domisili),
            $siswa->kelurahan,
            $siswa->kecamatan,
            $siswa->kota,
            $siswa->provinsi,
            $siswa->profesi,
            $siswa->kota_intansi,
            $this->_limitStr($siswa->kota_aktifitas_profesi),
            $siswa->bidang_usaha,
            $siswa->email,
            $siswa->hp,
            date("d-m-Y", $siswa->date_created),
            $strTglAkhir,
            $hasilTotal,
            $predikat['huruf'] . " ({$predikat['teks']})"
        ];

        foreach ($dataSiswa as $val) {
            $pdf->SetXY(78, $startY);
            $pdf->Cell(0, 5, $val, 0, 1, 'L');
            $startY += 8.45; // Jarak antar baris presisi
        }

        // OUTPUT
        $this->response->setContentType('application/pdf');
        $pdf->Output(str_replace(' ', '_', $siswa->nama_siswa) . '-brevet-ab.pdf', 'I');
    }

    // ---------------------------------------------------------
    // HELPER METHODS
    // ---------------------------------------------------------

    private function _getPredikat($nilai)
    {
        if ($nilai < 60) return ['huruf' => 'D', 'teks' => 'Kurang'];
        if ($nilai < 70) return ['huruf' => 'C', 'teks' => 'Cukup'];
        if ($nilai < 80) return ['huruf' => 'B', 'teks' => 'Cukup Baik'];
        if ($nilai < 90) return ['huruf' => 'A', 'teks' => 'Baik'];
        return ['huruf' => 'A+', 'teks' => 'Sangat Baik'];
    }

    private function _limitStr($str, $limit = 50)
    {
        return (strlen($str) > $limit) ? substr($str, 0, $limit) . '...' : $str;
    }

    public function updateKuota()
    {
        $id_ujian = decrypt_url($this->request->getVar('id_ujian'));
        $kuota    = $this->request->getVar('kuota');

        $success = $this->ujianModel
            ->where('id_ujian', $id_ujian)
            ->set('kuota', $kuota)
            ->update();

        // Kirim respons JSON beserta token CSRF baru
        return $this->response->setJSON([
            'status'     => $success ? 'success' : 'error',
            'message'    => $success ? 'Kuota berhasil diperbarui!' : 'Gagal memperbarui data.',
            'csrf_token' => csrf_hash() // Ini kunci agar submit berikutnya tidak 403
        ]);
    }

    public function deleteUjian($id_ujian, $id_siswa)
    {
        $idujian = decrypt_url($id_ujian);
        $this->ujianModel->delete($idujian);

        session()->setFlashdata('success', 'Data berhasil dihapus');
        return redirect()->to('sw-admin/siswa/ujian/'.$id_siswa);
    }
}

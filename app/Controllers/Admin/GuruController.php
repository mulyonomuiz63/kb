<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GuruModel;
use App\Models\SiswaModel;
use App\Models\UjianDetailModel;
use App\Models\UjianMasterModel;
use App\Models\UjianSiswaModel;

class GuruController extends BaseController
{
    protected $guruModel;
    protected $ujianMasterModel;
    protected $ujianDetailModel;
    protected $siswaModel;
    protected $ujianSiswaModel;

    public function __construct()
    {
        $this->guruModel = new GuruModel();
        $this->ujianMasterModel = new UjianMasterModel();
        $this->ujianDetailModel = new UjianDetailModel();
        $this->siswaModel = new SiswaModel();
        $this->ujianSiswaModel = new UjianSiswaModel();
    }

    public function index()
    {
        $data = [
            'title' => 'List Guru',
            'kelas' => $this->guruModel->asObject()->findAll(), // Asumsi model kelas
        ];
        return view('admin/guru/list', $data);
    }

    // Di App\Models\SiswaModel.php
    public function guru()
    {
        return view('admin/guru/list');
    }

    // METHOD BARU UNTUK DATATABLES
    public function datatable()
    {
        if ($this->request->isAJAX()) {
            // Ambil parameter dari DataTables
            $draw   = $this->request->getPost('draw');
            $start  = $this->request->getPost('start');
            $length = $this->request->getPost('length');
            $search = $this->request->getPost('search')['value'];

            // 1. Query Dasar
            $builder = $this->guruModel->builder();

            // 2. Total Data Asli (Sebelum Filter)
            $totalData = $builder->countAllResults(false);

            // 3. Fitur Pencarian
            if (!empty($search)) {
                $builder->groupStart()
                    ->like('nama_guru', $search)
                    ->orLike('email', $search)
                    ->groupEnd();
            }

            // 4. Total Data Setelah Filter
            $totalFiltered = $builder->countAllResults(false);

            // 5. Pagination & Fetch Data
            $guru = $builder->limit($length, $start)
                ->orderBy('id_guru', 'DESC')
                ->get()
                ->getResult();

            $data = [];
            foreach ($guru as $g) {
                $id_enc = encrypt_url($g->id_guru);

                $row = [];
                $row['nama']  = $g->nama_guru;
                $row['email'] = $g->email;
                $row['mapel'] = '<a href="' . base_url("sw-admin/guru/mapel-guru/" . $id_enc) . '" class="badge bg-success"><i class="bi bi-eye"></i></a>';
                $row['soal']  = '<a href="' . base_url("sw-admin/guru/ujian-guru/" . $id_enc) . '" class="badge bg-success"><i class="bi bi-eye"></i></a>';
                $row['opsi']  = '
                <div class="d-flex" style="gap:5px;">
                    <a href="' . base_url('sw-admin/guru/edit/') . $id_enc . '"class="badge bg-primary">
                        <i class="bi bi-gear"></i>
                    </a>
                    <a href="javascript:void(0)" data-url="' . base_url('sw-admin/guru/delete/' . $id_enc) . '" class="badge bg-danger btn-delete">
                        <i class="bi bi-trash"></i>
                    </a>
                </div>';
                $data[] = $row;
            }

            // 6. Response JSON dengan CSRF Hash terbaru
            return $this->response->setJSON([
                'draw'            => intval($draw),
                'recordsTotal'    => $totalData,
                'recordsFiltered' => $totalFiltered,
                'data'            => $data,
                "csrf_hash"       => csrf_hash() // Update token di sisi client
            ]);
        }
    }

    public function create()
    {
        $data = [
            'title'        => 'Tambah Instruktur',
            'parent_title' => 'List Instruktur',
            'parent_url'   => base_url('sw-admin/guru'),
        ];
        return view('admin/guru/create', $data);
    }

    public function store()
    {
        // 1. Validasi Input
        if (!$this->validate([
            'nama_guru' => [
                'rules'  => 'required|min_length[3]',
                'errors' => [
                    'required'   => 'Nama instruktur harus diisi.',
                    'min_length' => 'Nama terlalu pendek.'
                ]
            ],
            'email' => [
                'rules'  => [
                    'required',
                    'valid_email',
                    function ($value, $params, &$error = null) {
                        $this->db = \Config\Database::connect();

                        $tables = ['admin', 'siswa', 'guru', 'mitra', 'pic'];
                        foreach ($tables as $table) {
                            $exists = $this->db->table($table)->where('email', $value)->countAllResults();
                            if ($exists > 0) {
                                $error = "Email sudah terdaftar dalam sistem ($table).";
                                return false;
                            }
                        }
                        return true;
                    }
                ],
                'errors' => [
                    'required'    => 'Email wajib diisi.',
                    'valid_email' => 'Format email tidak sah.'
                ]
            ],
            'password' => [
                'rules'  => 'required|min_length[6]',
                'errors' => [
                    'required'   => 'Password wajib diisi.',
                    'min_length' => 'Password minimal 6 karakter.'
                ]
            ]
        ])) {
            // Jika validasi gagal, kembalikan ke form dengan input lama & pesan error
            $errors = $this->validator->getErrors();
            $firstError = reset($errors);

            session()->setFlashdata('error', $firstError);
            return redirect()->back()->withInput();
        }

        // 2. Ambil data dari request
        $nama     = $this->request->getPost('nama_guru');
        $email    = $this->request->getPost('email');
        $pwd_raw  = $this->request->getPost('password');

        // 3. Siapkan Array Data
        $data_guru = [
            'nama_guru'    => $nama,
            'email'        => $email,
            // Enkripsi password menggunakan BCRYPT
            'password'     => password_hash($pwd_raw, PASSWORD_DEFAULT),
            'role'         => 3,
            'is_active'    => 1,
            'date_created' => time(),
            'avatar'       => 'default.jpg'
        ];

        // 4. Eksekusi Insert ke Database
        try {
            $this->guruModel->insert($data_guru);

            // Kirim notifikasi sukses
            session()->setFlashdata('success', "$nama telah ditambahkan");
            return redirect()->to('sw-admin/guru'); // Sesuaikan dengan route list guru Anda

        } catch (\Exception $e) {
            // Tangani jika ada error database
            session()->setFlashdata('error', "Terjadi kesalahan.");
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $guru = $this->guruModel->asObject()->find(decrypt_url($id));
        if (!$guru) {
            return redirect()->back()->with('error', 'Data instruktur tidak ditemukan.');
        }

        $data = [
            'title'        => 'Edit Peserta',
            'parent_title' => 'List Peserta',
            'parent_url'   => base_url('sw-admin/guru'),
            'guru'        => $guru,
        ];

        return view('admin/guru/edit', $data);
    }

    public function update($id)
    {
        $id_guru = decrypt_url($id);
        $guru_lama = $this->guruModel->find($id_guru);

        if (!$guru_lama) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // 1. Validasi Input
        $rules = [
            'nama_guru' => 'required|min_length[3]',
            'email' => [
                'rules'  => [
                    'required',
                    'valid_email',
                    function ($value, $params, &$error = null) use ($id_guru) {
                        $this->db = \Config\Database::connect();
                        $tables = ['admin', 'siswa', 'guru', 'mitra', 'pic'];

                        foreach ($tables as $table) {
                            $builder = $this->db->table($table)->where('email', $value);

                            // Jika di tabel guru, abaikan ID milik sendiri
                            if ($table === 'guru') {
                                $builder->where('id_guru !=', $id_guru);
                            }

                            if ($builder->countAllResults() > 0) {
                                $error = "Email sudah digunakan di sistem ($table).";
                                return false;
                            }
                        }
                        return true;
                    }
                ],
                'errors' => [
                    'required'    => 'Email wajib diisi.',
                    'valid_email' => 'Format email tidak sah.'
                ]
            ]
        ];

        // Password hanya divalidasi JIKA diisi
        $pwd_raw = $this->request->getPost('password');
        if (!empty($pwd_raw)) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            session()->setFlashdata('error', reset($errors));
            return redirect()->back()->withInput();
        }

        // 2. Siapkan Data
        $data_update = [
            'nama_guru' => $this->request->getPost('nama_guru'),
            'email'     => $this->request->getPost('email'),
        ];

        // Jika password diisi, masukkan ke array update
        if (!empty($pwd_raw)) {
            $data_update['password'] = password_hash($pwd_raw, PASSWORD_DEFAULT);
        }

        // 3. Eksekusi Update
        try {
            $this->guruModel->update($id_guru, $data_update);
            session()->setFlashdata('success', "Data " . $data_update['nama_guru'] . " berhasil diperbarui");
            return redirect()->to('sw-admin/guru');
        } catch (\Exception $e) {
            session()->setFlashdata('error', "Gagal memperbarui data.");
            return redirect()->back()->withInput();
        }
    }

    public function delete($id)
    {
        // 1. Dekripsi ID
        $id_guru = decrypt_url($id);

        // 2. Cari data guru (sebagai object)
        $guru = $this->guruModel->asObject()->find($id_guru);

        if (!$guru) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return redirect()->to('sw-admin/guru');
        }

        // 3. Eksekusi Hapus
        try {

            $this->guruModel->delete($id_guru);

            session()->setFlashdata('success', "Data {$guru->nama_guru} berhasil dihapus.");
            return redirect()->to('sw-admin/guru');
        } catch (\Exception $e) {
            session()->setFlashdata('error', "Gagal menghapus data. Data mungkin terhubung dengan tabel lain.");
            return redirect()->back();
        }
    }

    public function ujianGuru($id)
    {
        $data = [
            'title'        => 'Data Ujian',
            'parent_title' => 'List Instuktur',
            'parent_url'   => base_url('sw-admin/guru'),
        ];
        // Cukup kirim ID terenkripsi ke view untuk digunakan AJAX
        $data['id_guru_enc'] = $id;
        return view('admin/guru/ujian/list', $data);
    }

    public function ajaxUjianGuru()
    {
        $idGuru = decrypt_url($this->request->getPost('id_guru'));

        $draw   = $this->request->getPost('draw');
        $start  = (int)$this->request->getPost('start');
        $length = (int)$this->request->getPost('length');
        $searchValue = $this->request->getPost('search')['value'] ?? '';

        // Base Builder
        $builder = $this->db->table('ujian_master')
            ->select('
            ujian_master.*,
            kelas.nama_kelas,
            mapel.nama_mapel,
            status_ujian.status as status_ujian
        ')
            ->join('kelas', 'kelas.id_kelas = ujian_master.kelas')
            ->join('mapel', 'mapel.id_mapel = ujian_master.mapel')
            ->join('guru', 'guru.id_guru = ujian_master.guru')
            ->join('status_ujian', 'status_ujian.kode_ujian = ujian_master.kode_ujian', 'left')
            ->where('ujian_master.guru', $idGuru);

        // Total Data Tanpa Filter
        $totalRecords = clone $builder;
        $recordsTotal = $totalRecords->countAllResults();

        // Filter Search
        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('ujian_master.nama_ujian', $searchValue)
                ->orLike('kelas.nama_kelas', $searchValue)
                ->groupEnd();
        }

        // Total Setelah Filter
        $totalFiltered = clone $builder;
        $recordsFiltered = $totalFiltered->countAllResults();

        // Ambil Data
        $results = $builder
            ->orderBy('ujian_master.id_ujian', 'DESC')
            ->limit($length, $start)
            ->get()
            ->getResult();

        $data = [];

        foreach ($results as $u) {

            // Status Badge
            $statusBadge = '<span class="badge bg-danger">Tidak Aktif</span>';
            if (!empty($u->status_ujian) && $u->status_ujian == 'A') {
                $statusBadge = '<span class="badge bg-success">Aktif</span>';
            }

            $btnLihat = '';
            if ($u->jenis_ujian != 1) {
                $btnLihat = '<a class="dropdown-item" href="' .
                    base_url('sw-admin/guru/lihat-ujian/' .
                        encrypt_url($u->kode_ujian)) . '"><i class="bi bi-eye me-2 text-primary"></i> Lihat</a>';
            }

            $action = '
        <div class="dropdown custom-dropdown">
            <a class="dropdown-toggle badge badge-secondary border-0" href="#" role="button" data-toggle="dropdown">
                <i class="bi bi-three-dots-vertical"></i>
            </a> 
            <div class="dropdown-menu">
                ' . $btnLihat . '
                <a class="dropdown-item" target="_blank" href="' .
                base_url('sw-admin/guru/cetak-soal/' . encrypt_url($u->kode_ujian)) .
                '"><i class="bi bi-printer me-2 text-success"></i> Cetak Soal</a>
            </div>
        </div>';

            $data[] = [
                "nama_ujian" => $u->nama_ujian,
                "nama_kelas" => $u->nama_kelas, // ✅ diperbaiki
                "status"     => $statusBadge,
                "opsi"       => $action
            ];
        }

        return $this->response->setJSON([
            "draw"            => intval($draw),
            "recordsTotal"    => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data"            => $data,
            "token"           => csrf_hash()
        ]);
    }

    public function lihatUjian($kode_ujian)
    {
        $data = [
            'title'        => 'Data Ujian',
            'parent_title' => 'List Instuktur',
            'parent_url'   => base_url('sw-admin/guru'),
        ];

        $kode_ujian = decrypt_url($kode_ujian);
        $data['kode_ujian_encrypt'] = $kode_ujian; // Kirim untuk AJAX

        // Kita tidak lagi mengirim $data['siswa'] ke view karena akan ditarik via AJAX
        return view('admin/guru/ujian/pg-lihat', $data);
    }

    // Fungsi baru untuk suplai data ke DataTable
    public function ajaxSiswaUjian($kode_ujian_encrypt)
    {
        $kode_ujian = decrypt_url($kode_ujian_encrypt);
        $request = \Config\Services::request();

        // Ambil parameter dari DataTables
        $draw   = $request->getPost('draw');
        $start  = $request->getPost('start');
        $length = $request->getPost('length');
        $search = $request->getPost('search')['value'];

        $dataUjian = $this->ujianMasterModel->getBykode($kode_ujian);

        // 1. Ambil data dari Model (Hasilnya berupa Array sesuai limit 20 di model)
        $list_siswa_raw = $this->siswaModel->getAllbyKelasUjian($dataUjian->kelas, $dataUjian->kode_ujian);

        // 2. Hitung total data mentah dari array
        $totalData = count($list_siswa_raw);

        // 3. Filter Search Manual (karena data sudah ditarik sebagai array)
        if ($search) {
            $list_siswa = array_filter($list_siswa_raw, function ($item) use ($search) {
                return stripos($item->nama_siswa, $search) !== false;
            });
        } else {
            $list_siswa = $list_siswa_raw;
        }

        $recordsFiltered = count($list_siswa);

        // 4. POTONG DATA (PAGINATION)
        // Memotong array agar hanya mengirim data sesuai halaman (misal 10 data)
        $list_siswa_paginated = array_slice($list_siswa, $start, $length);

        $data_json = [];
        foreach ($list_siswa_paginated as $s) {
            $url_detail = base_url('sw-admin/guru/lihat-ujian-siswa/' . encrypt_url($s->id_siswa) . '/' . encrypt_url($dataUjian->kode_ujian));

            // Link untuk Cetak Hasil Peserta (Fungsi cetak yang baru kita buat)
            $url_cetak = base_url('sw-admin/guru/cetak-soal-peserta/' . encrypt_url($s->id_siswa) . '/' . encrypt_url($dataUjian->kode_ujian));
            // Hitung Statistik
            $benar = $this->ujianSiswaModel->where(['ujian' => $dataUjian->kode_ujian, 'siswa' => $s->id_siswa, 'benar' => 1])->countAllResults();
            $salah = $this->ujianSiswaModel->where(['ujian' => $dataUjian->kode_ujian, 'siswa' => $s->id_siswa, 'benar' => 0])->countAllResults();
            $total_soal_dikerjakan = $this->ujianSiswaModel->where(['ujian' => $dataUjian->kode_ujian, 'siswa' => $s->id_siswa])->countAllResults();

            $skor = ($total_soal_dikerjakan > 0) ? round(($benar / $total_soal_dikerjakan) * 100) : 0;

            $data_json[] = [
                // Kolom 1: Profil
                '<div class="d-flex align-items-center">
                <img src="' . base_url('assets/app-assets/user/' . $s->avatar) . '" class="rounded-circle mr-3" style="width:45px; height:45px; object-fit:cover;">
                <div>
                    <h6 class="mb-0 font-weight-bold">' . $s->nama_siswa . '</h6>
                    <small class="text-muted">' . ($s->date_send == 0 ? 'Selesai' : date('d M Y, H:i', $s->date_send)) . '</small>
                </div>
            </div>',
                // Kolom 2: Statistik
                '<div class="d-flex justify-content-around text-center">
                <div class="px-2"><b>' . $skor . '</b><br><small>Skor</small></div>
                <div class="px-2 text-success"><b>' . $benar . '</b><br><small>Benar</small></div>
                <div class="px-2 text-danger"><b>' . $salah . '</b><br><small>Salah</small></div>
            </div>',
                // Kolom 3: Aksi
                '<div class="dropdown custom-dropdown text-center">
                    <a class="dropdown-toggle badge badge-primary border-0" href="#" role="button" id="dropdownMenuLink' . $s->id_siswa . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink' . $s->id_siswa . '">
                        <a class="dropdown-item" href="' . $url_detail . '">
                            <i class="bi bi-eye me-2 text-primary"></i> Lihat Detail
                        </a>
                        <a class="dropdown-item" target="_blank" href="' . $url_cetak . '">
                            <i class="bi bi-printer me-2 text-success"></i> Cetak Hasil
                        </a>
                    </div>
                </div>'
            ];
        }

        return $this->response->setJSON([
            "draw"            => intval($draw),
            "recordsTotal"    => $totalData,
            "recordsFiltered" => $recordsFiltered,
            "data"            => $data_json,
            csrf_token() => csrf_hash() // Update CSRF Token
        ]);
    }

    public function lihatUjianSiswa($id_siswa, $kode_ujian)
    {
        $data = [
            'title'        => 'Data Ujian',
            'parent_title' => 'List Instuktur',
            'parent_url'   => base_url('sw-admin/guru'),
        ];

        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }

        $data['ujian'] = $this->ujianMasterModel->getBykode(decrypt_url($kode_ujian));
        $data['detail_ujian'] = $this->ujianDetailModel->getAllBykodeUjian(decrypt_url($kode_ujian));
        $data['siswa'] = $this->siswaModel->asObject()->find(decrypt_url($id_siswa));

        $data['ujian_siswa'] = $this->ujianSiswaModel
            ->where('ujian', decrypt_url($kode_ujian))
            ->where('siswa', decrypt_url($id_siswa))
            ->get()->getResultObject();

        $data['jawaban_benar'] = $this->ujianSiswaModel->benar(decrypt_url($kode_ujian), decrypt_url($id_siswa), 1);
        $data['jawaban_salah'] = $this->ujianSiswaModel->salah(decrypt_url($kode_ujian), decrypt_url($id_siswa), 0);
        $data['tidak_dijawab'] = $this->ujianSiswaModel->belum_terjawab(decrypt_url($kode_ujian), decrypt_url($id_siswa));

        return view('admin/guru/ujian/pg-siswa', $data);
    }

    public function cetakSoalPeserta($id_siswa_encrypt, $kode_ujian_encrypt)
    {
        // 1. Proteksi Akses
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }

        try {
            // 2. Dekripsi dengan Error Handling
            $id_siswa = decrypt_url($id_siswa_encrypt);
            $kode_ujian = decrypt_url($kode_ujian_encrypt);

            if (!$id_siswa || !$kode_ujian) {
                return redirect()->back()->with('error', 'Parameter tidak valid.');
            }

            // 3. Pastikan Data Siswa Ada
            $siswa = $this->siswaModel->asObject()->find($id_siswa);
            if (!$siswa) {
                return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
            }

            // 4. Pastikan Data Ujian Ada
            $ujian = $this->ujianMasterModel->getBykode($kode_ujian);
            if (!$ujian) {
                return redirect()->back()->with('error', 'Data ujian tidak ditemukan.');
            }

            // 5. Ambil Detail Soal
            $detail_ujian = $this->ujianDetailModel->getAllBykodeUjian($kode_ujian);
            if (empty($detail_ujian)) {
                // Opsional: Tetap lanjut cetak atau stop jika soal kosong
            }

            // Persiapan data untuk view
            $data = [
                'detail_ujian' => $detail_ujian,
                'ujian'        => $ujian,
                'id_siswa'     => $id_siswa,
                'siswa'        => $siswa,
                'file'         => 'Soal_' . str_replace(' ', '_', $siswa->nama_siswa),
                'title'        => 'Cetak Hasil Ujian - ' . $siswa->nama_siswa,
                'response'     => $this->response->setContentType('application/pdf'),
            ];

            // 6. Generate View dengan pengecekan file eksis
            // Menggunakan view guru sesuai path di variabel Anda
            $data['view'] = view('guru/ujian/cetak/soal_peserta', $data);

            return view('admin/guru/ujian/cetak/tampil', $data);
        } catch (\Exception $e) {
            // 7. Tangkap error tak terduga (misal: error database)
            log_message('error', '[Cetak Soal] Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses data.');
        }
    }
    function cetakSoal($kode_ujian)
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }

        try {
            // Proses Dekripsi
            $kode_ujian_decrypted = decrypt_url($kode_ujian);

            // Ambil Data
            $data['detail_ujian'] = $this->ujianDetailModel->getAllBykodeUjian($kode_ujian_decrypted);
            $data['ujian'] = $this->ujianMasterModel->getBykode($kode_ujian_decrypted);

            // Validasi jika data tidak ditemukan (Antisipasi error property of non-object)
            if (!$data['ujian']) {
                return redirect()->back()->with('error', 'Data ujian tidak ditemukan.');
            }

            // Render View
            $view = view('guru/ujian/cetak/soal', $data);
            $data['view'] = $view;
            $data['response'] = $this->response->setContentType('application/pdf');
            $data['file'] = $data['ujian']->nama_ujian;

            return view('admin/guru/ujian/cetak/tampil', $data);
        } catch (\Exception $e) {
            // Jika terjadi error, catat di log dan kembalikan ke halaman sebelumnya
            log_message('error', '[Cetak Soal Error]: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses cetak soal. Terjadi kesalahan sistem.');
        }
    }
}

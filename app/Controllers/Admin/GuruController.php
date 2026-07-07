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
    protected $guruKelasModel;
    protected $guruMapelModel;
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
        $this->guruKelasModel = new \App\Models\GuruKelasModel();
        $this->guruMapelModel = new \App\Models\GuruMapelModel();
    }

    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'List Instruktur', 'url' => '#'],
        ];
        $data['kelas'] = $this->guruModel->asObject()->findAll(); // Asumsi model kelas
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

                // Teks nama dipertegas dengan warna standar text-gray-800 Metronic
                $row['nama']  = '<span class="text-gray-800 fw-bold">' . esc($g->nama_guru) . '</span>';

                $row['email'] = esc($g->email);

                // Tombol Lihat Mapel (Warna Hijau Lembut)
                $row['mapel'] = '
                <a href="' . base_url("sw-admin/guru/mapel-guru/" . $id_enc) . '" class="btn btn-icon btn-light-success btn-sm" data-bs-toggle="tooltip" title="Lihat Mapel">
                    <i class="ki-duotone ki-eye fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                </a>';

                // Tombol Lihat Soal (Warna Biru Lembut)
                $row['soal']  = '
                <a href="' . base_url("sw-admin/guru/ujian-guru/" . $id_enc) . '" class="btn btn-icon btn-light-info btn-sm" data-bs-toggle="tooltip" title="Lihat Soal">
                    <i class="ki-duotone ki-document fs-3"><span class="path1"></span><span class="path2"></span></i>
                </a>';

                // Tombol Opsi Edit & Hapus disejajarkan dengan Gap Flexbox
                $row['opsi']  = '
                <div class="d-flex justify-content-center gap-2">
                    <a href="' . base_url('sw-admin/guru/edit/') . $id_enc . '" class="btn btn-icon btn-light-primary btn-sm" data-bs-toggle="tooltip" title="Pengaturan">
                        <i class="ki-duotone ki-setting-2 fs-3"><span class="path1"></span><span class="path2"></span></i>
                    </a>
                    <a href="javascript:void(0)" data-url="' . base_url('sw-admin/guru/delete/' . $id_enc) . '" class="btn btn-icon btn-light-danger btn-sm btn-delete" data-bs-toggle="tooltip" title="Hapus">
                        <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
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
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'Data Instruktur', 'url' => base_url('sw-admin/guru')],
            ['title' => 'Tambah Instruktur', 'url' => '#'],
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

        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'Data Instruktur', 'url' => base_url('sw-admin/guru')],
            ['title' => 'Edit Instruktur', 'url' => '#'],
        ];
        $data['guru'] = $guru; 

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
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'Data Instruktur', 'url' => base_url('sw-admin/guru')],
            ['title' => 'List Ujian', 'url' => '#'],
        ];
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

            // 1. Status Badge (Metronic Style: Light Background)
            $statusBadge = '<span class="badge badge-light-danger fs-7 fw-bold">Tidak Aktif</span>';
            if (!empty($u->status_ujian) && $u->status_ujian == 'A') {
                $statusBadge = '<span class="badge badge-light-success fs-7 fw-bold">Aktif</span>';
            }

            // 2. Tombol Lihat (Hanya jika jenis_ujian != 1)
            $btnLihat = '';
            if ($u->jenis_ujian != 1) {
                $btnLihat = '
            <div class="menu-item px-3">
                <a href="' . base_url('sw-admin/guru/lihat-ujian/' . encrypt_url($u->kode_ujian)) . '" class="menu-link px-3">
                    <i class="ki-duotone ki-eye fs-4 me-2 text-primary">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i> Lihat
                </a>
            </div>';
            }

            // 3. Action Dropdown (Metronic 8 Menu System)
            $action = '
        <div class="text-center">
            <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                Aksi <i class="ki-duotone ki-down fs-5 ms-1"></i>
            </a>
            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
                ' . $btnLihat . '
                
                <div class="menu-item px-3">
                    <a href="' . base_url('sw-admin/guru/edit-ujian/' . encrypt_url($u->kode_ujian)) . '" class="menu-link px-3">
                        <i class="ki-duotone ki-pencil fs-4 me-2 text-success">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                        </i> Edit Soal
                    </a>
                </div>
                <div class="menu-item px-3">
                    <a target="_blank" href="' . base_url('sw-admin/guru/cetak-soal/' . encrypt_url($u->kode_ujian)) . '" class="menu-link px-3">
                        <i class="ki-duotone ki-printer fs-4 me-2 text-success">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                        </i> Cetak Soal
                    </a>
                </div>
            </div>
        </div>';

            $data[] = [
                "nama_ujian" => $u->nama_ujian,
                "nama_kelas" => $u->nama_kelas,
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
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'Data Instruktur', 'url' => base_url('sw-admin/guru')],
            ['title' => 'List Ujian', 'url' => '#'],
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
                // Kolom 1: Profil (Metronic Style with Symbol)
                '<div class="d-flex align-items-center">
                    <div class="symbol symbol-45px me-5">
                        <img src="' . base_url('assets/app-assets/user/' . $s->avatar) . '" alt="' . $s->nama_siswa . '" style="object-fit:cover;">
                    </div>
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">' . $s->nama_siswa . '</a>
                        <span class="text-muted fw-semibold d-block fs-7">' . ($s->date_send == 0 ? 'Selesai' : date('d M Y, H:i', $s->date_send)) . '</span>
                    </div>
                </div>',

                // Kolom 2: Statistik (Badge style & Bold colors)
                '<div class="d-flex justify-content-around align-items-center">
                    <div class="text-center px-2">
                        <span class="text-gray-900 fw-bold d-block fs-5">' . $skor . '</span>
                        <span class="text-muted fs-8 fw-semibold uppercase">Skor</span>
                    </div>
                    <div class="text-center px-2">
                        <span class="text-success fw-bold d-block fs-5">' . $benar . '</span>
                        <span class="text-muted fs-8 fw-semibold uppercase">Benar</span>
                    </div>
                    <div class="text-center px-2">
                        <span class="text-danger fw-bold d-block fs-5">' . $salah . '</span>
                        <span class="text-muted fs-8 fw-semibold uppercase">Salah</span>
                    </div>
                </div>',

                // Kolom 3: Aksi (Metronic Dropdown Menu)
                '<div class="text-center">
                    <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        Aksi <i class="ki-duotone ki-down fs-5 ms-1"></i>
                    </a>
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
                        <div class="menu-item px-3">
                            <a href="' . $url_detail . '" class="menu-link px-3">
                                <i class="ki-duotone ki-eye text-primary fs-4 me-2">
                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                </i> Lihat Detail
                            </a>
                        </div>
                        <div class="menu-item px-3">
                            <a target="_blank" href="' . $url_cetak . '" class="menu-link px-3">
                                <i class="ki-duotone ki-printer text-success fs-4 me-2">
                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                </i> Cetak Hasil
                            </a>
                        </div>
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
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'Data Instruktur', 'url' => base_url('sw-admin/guru')],
            ['title' => 'List Ujian', 'url' => '#'],
        ];

        $data['ujian'] = $this->ujianMasterModel->getBykode(decrypt_url($kode_ujian));
        $data['detail_ujian'] = $this->ujianDetailModel->getAllBykodeUjianAdmin(decrypt_url($kode_ujian));
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
            $detail_ujian = $this->ujianDetailModel->getAllBykodeUjianAdmin($kode_ujian);
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
            $data['detail_ujian'] = $this->ujianDetailModel->getAllBykodeUjianAdmin($kode_ujian_decrypted);
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
    public function editUjian($kode_ujian)
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'Data Ujian', 'url' => base_url('sw-admin/guru')],
            ['title' => 'List Soal Ujian', 'url' => '#'],
        ];
        $data['detail_ujian'] = $this->ujianDetailModel->getAllBykodeUjianAdmin(decrypt_url($kode_ujian));
        $data['ujian'] = $this->ujianMasterModel->getBykode(decrypt_url($kode_ujian));
        $data['siswa'] = $this->siswaModel->getAllbyKelas($data['ujian']->kelas);
        $data['guru'] = $this->guruModel->asObject()->find(session()->get('id'));
        $data['guru_kelas'] = $this->guruKelasModel->getALLByGuru(session()->get('id'));
        $data['guru_mapel'] = $this->guruMapelModel->getALLByGuru(session()->get('id'));
        return view('admin/guru/ujian/edit_pg', $data);
    }

    public function editSoal($id_detail_ujian)
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'Data Ujian', 'url' => base_url('sw-admin/guru')],
             ['title' => 'Edit Soal Ujian', 'url' => '#'],
        ];
        $data['detail_ujian'] = $this->ujianDetailModel->getAllByiddetailujian(decrypt_url($id_detail_ujian));
        $data['guru'] = $this->guruModel->asObject()->find(session()->get('id'));
        $data['guru_kelas'] = $this->guruKelasModel->getALLByGuru(session()->get('id'));
        $data['guru_mapel'] = $this->guruMapelModel->getALLByGuru(session()->get('id'));
        return view('admin/guru/ujian/edit_soal', $data);
    }
    public function updateSoal()
    {

        $data_detail_ujian = [
            'kode_ujian' => $this->request->getVar('kode_ujian'),
            'nama_soal' => $this->request->getVar('nama_soal'),
            'pg_1' => 'A. ' . $this->request->getVar('pg_1'),
            'pg_2' => 'B. ' . $this->request->getVar('pg_2'),
            'pg_3' => 'C. ' . $this->request->getVar('pg_3'),
            'pg_4' => 'D. ' . $this->request->getVar('pg_4'),
            'pg_5' => 'E. ' . $this->request->getVar('pg_5'),
            'jawaban' => $this->request->getVar('jawaban'),
            'penjelasan' => $this->request->getVar('penjelasan'),
        ];


        $this->ujianDetailModel->set($data_detail_ujian)->where('id_detail_ujian', $this->request->getVar('id_detail_ujian'))->update();
        return redirect()->to('sw-admin/guru/edit-ujian/' . encrypt_url($this->request->getVar('kode_ujian')))->with('success', 'Soal telah diubah');
    }

    public function uploadSummernote()
    {
        $fileGambar = $this->request->getFile('image');

        if ($fileGambar && !$fileGambar->hasMoved()) {
            // Generate nama file Random
            $nama_gambar = $fileGambar->getRandomName();

            // Upload Gambar
            $fileGambar->move('assets/app-assets/file', $nama_gambar);

            // Kirim balik URL dan Token baru
            return json_encode([
                'url'   => base_url('assets/app-assets/file/' . $nama_gambar),
                'token' => csrf_hash()
            ]);
        }
    }

    public function deleteImage()
    {
        $src = $this->request->getVar('src');

        // Bersihkan path: pastikan hanya menghapus file di folder yang diizinkan
        $file_path = str_replace(base_url() . '/', '', $src);

        $status = false;
        $message = 'File not found';

        // Cek apakah file ada sebelum di-unlink (delete)
        if (file_exists($file_path)) {
            if (unlink($file_path)) {
                $status = true;
                $message = 'File Deleted Successfully';
            } else {
                $message = 'Failed to delete file from server';
            }
        }

        // Selalu kembalikan CSRF Hash terbaru
        return $this->response->setJSON([
            'status'  => $status,
            'message' => $message,
            'token'   => csrf_hash()
        ]);
    }
}

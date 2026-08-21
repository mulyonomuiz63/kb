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
                <a href="' . base_url("sw-admin/guru/mapel/" . $id_enc) . '" class="btn btn-icon btn-light-success btn-sm" data-bs-toggle="tooltip" title="Lihat Mapel">
                    <i class="ki-duotone ki-eye fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                </a>';

                // Tombol Lihat Soal (Warna Biru Lembut)
                $row['soal']  = '
                <a href="' . base_url("sw-admin/guru/ujian/" . $id_enc) . '" class="btn btn-icon btn-light-info btn-sm" data-bs-toggle="tooltip" title="Lihat Soal">
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
        $sessionData = [
            'idguru_diadmin'    => decrypt_url($id),
        ];
        session()->set($sessionData);
        return redirect()->to('sw-guru/ujian');
    }
}

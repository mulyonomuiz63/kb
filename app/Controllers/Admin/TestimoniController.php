<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class TestimoniController extends BaseController
{
    protected $testimoniModel;

    public function __construct()
    {
        $this->testimoniModel = new \App\Models\TestimoniModel();
    }
    // Halaman Utama
    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'List Testimoni', 'url' => '#'],
        ];
        $data['siswa'] = (new \App\Models\SiswaModel())->asObject()->findAll();
        return view('admin/testimoni/list', $data);
    }

    // Server Side DataTables
    public function datatables()
    {
        if (!$this->request->isAJAX()) return redirect()->to('auth');

        $request = $this->request;
        $postData = $request->getPost();

        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length'];
        $searchValue = $postData['search']['value'];

        // Gunakan builder agar lebih fleksibel
        $builder = $this->db->table('testimoni'); // Pastikan $this->db sudah di-init di constructor atau pakai \Config\Database::connect()
        $builder->select('testimoni.*, siswa.nama_siswa');
        $builder->join('siswa', 'testimoni.idsiswa = siswa.id_siswa');

        // Total data tanpa filter
        $totalRecords = $this->testimoniModel->countAll();

        // Terapkan Filter jika ada pencarian
        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('siswa.nama_siswa', $searchValue)
                ->orLike('testimoni.keterangan', $searchValue)
                ->groupEnd();
        }

        // Hitung total setelah filter
        // 'false' agar builder tidak di-reset setelah count
        $totalRecordwithFilter = $builder->countAllResults(false);

        // Ambil data dengan limit dan offset
        $records = $builder->orderBy('testimoni.idtestimoni', 'DESC')
            ->limit($rowperpage, $start)
            ->get()
            ->getResultObject();

        $data = [];
        $no = $start + 1;

        foreach ($records as $row) {
            $id_encrypt = encrypt_url($row->idtestimoni);

            // Logika Ringkasan Teks (20 Kata)
            $full_text = htmlspecialchars($row->keterangan);
            $words = explode(" ", $full_text);
            $is_long = count($words) > 20;

            if ($is_long) {
                $short_text = implode(" ", array_slice($words, 0, 20)) . '...';
                // Menggunakan styling Metronic 8 (text-gray-800)
                $display_keterangan = '
                <div class="text-wrap min-w-250px">
                    <span class="txt-short text-gray-800">' . $short_text . '</span>
                    <span class="txt-full d-none text-gray-800">' . $full_text . '</span>
                    <br>
                    <a href="javascript:void(0)" class="btn-read-more btn btn-link btn-color-primary btn-active-color-primary fw-bold p-0 h-auto fs-8">Lihat Selengkapnya</a>
                </div>';
            } else {
                $display_keterangan = '<div class="text-wrap text-gray-800">' . $full_text . '</div>';
            }

            // Opsi menggunakan Dropdown Menu Metronic 8 (Metronic Menu)
            $opsi = '
            <div class="ms-2">
                <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                    Aksi <i class="ki-duotone ki-down fs-5 ms-1"></i>
                </a>
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold py-4 w-125px fs-7" data-kt-menu="true">
                    <div class="menu-item px-3">
                        <a href="javascript:void(0)" class="menu-link px-3 edit-testimoni" data-id="' . $id_encrypt . '">
                            Edit
                        </a>
                    </div>
                    <div class="menu-item px-3">
                        <a href="javascript:void(0)" class="menu-link px-3 text-danger btn-delete" data-url="' . base_url('sw-admin/testimoni/delete/' . $id_encrypt) . '">
                            Hapus
                        </a>
                    </div>
                </div>
            </div>';

            $data[] = [
                "no"         => $no++,
                "nama_siswa" => '<span class="text-gray-900 fw-bold fs-6">' . $row->nama_siswa . '</span>', // Styling Metronic untuk nama
                "keterangan" => $display_keterangan,
                "opsi"       => $opsi,
            ];
        }

        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => (int)$totalRecords,
            "iTotalDisplayRecords" => (int)$totalRecordwithFilter,
            "data" => $data,
            "token" => csrf_hash()
        ];

        return $this->response->setJSON($response);
    }

    // Store Data
    public function store()
    {
        try {
            $data = [
                'idsiswa'    => $this->request->getPost('idsiswa'),
                'keterangan' => $this->request->getPost('keterangan'),
            ];

            if ($this->testimoniModel->insert($data)) {
                return redirect()->back()->with('success', 'Data testimoni berhasil disimpan.');
            }
            return redirect()->back()->with('error', 'Gagal menyimpan data.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Fetch Data for Edit (AJAX)
    public function edit()
    {
        // Cek keamanan akses
        if (!$this->request->isAJAX()) return redirect()->to('auth');

        try {
            $id_raw = $this->request->getVar('idtestimoni');
            $id = decrypt_url($id_raw);

            $data = $this->testimoniModel->asObject()->find($id);

            if ($data) {
                // Injeksi token baru ke dalam objek data agar JS bisa updateAllCSRF
                $data->token = csrf_hash();
                return $this->response->setJSON($data);
            }

            // Jika data tidak ditemukan, tetap kirim token baru agar form tidak macet
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Data tidak ditemukan',
                'token' => csrf_hash()
            ]);
        } catch (\Exception $e) {
            // Jika terjadi error sistem, tetap kirim token baru
            return $this->response->setStatusCode(500)->setJSON([
                'error' => $e->getMessage(),
                'token' => csrf_hash()
            ]);
        }
    }

    // Update Data
    public function update()
    {
        try {
            $id = $this->request->getPost('idtestimoni');
            $data = [
                'idsiswa'    => $this->request->getPost('idsiswa'),
                'keterangan' => $this->request->getPost('keterangan'),
            ];

            if ($this->testimoniModel->update($id, $data)) {
                return redirect()->back()->with('success', 'Data testimoni berhasil diperbarui.');
            }
            return redirect()->back()->with('error', 'Gagal memperbarui data.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Delete Data
    public function delete($id = '')
    {
        try {
            $idtestimoni = decrypt_url($id);
            if ($this->testimoniModel->delete($idtestimoni)) {
                return redirect()->back()->with('success', 'Data testimoni berhasil dihapus.');
            }
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}

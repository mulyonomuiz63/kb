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
        $data = [
            'title' => 'Data Testimoni',
            'siswa' => (new \App\Models\SiswaModel())->asObject()->findAll()
        ];
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
                // Bungkus dengan span agar bisa dimanipulasi JS
                $display_keterangan = '
            <div class="text-wrap" style="min-width:250px;">
                <span class="txt-short">' . $short_text . '</span>
                <span class="txt-full d-none">' . $full_text . '</span>
                <br>
                <a href="javascript:void(0)" class="btn-read-more text-primary font-weight-bold" style="font-size:11px;">Lihat Selengkapnya</a>
            </div>';
            } else {
                $display_keterangan = '<div class="text-wrap">' . $full_text . '</div>';
            }

            $opsi = '<div class="dropdown custom-dropdown">
                        <a class="dropdown-toggle" href="javascript:void(0)" role="button" data-toggle="dropdown" id="drop' . $row->idtestimoni . '"><i class="bi bi-three-dots-vertical"></i></a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="drop' . $row->idtestimoni . '">
                            <a class="dropdown-item edit-testimoni" href="javascript:void(0)" data-id="' . $id_encrypt . '">Edit</a>
                            <a class="dropdown-item text-danger btn-delete" href="javascript:void(0)" data-url="' . base_url('sw-admin/testimoni/delete/' . $id_encrypt) . '">Hapus</a>
                        </div>
                    </div>';

            $data[] = [
                "no"         => $no++,
                "nama_siswa" => $row->nama_siswa,
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

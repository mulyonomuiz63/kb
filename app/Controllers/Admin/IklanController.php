<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\IklanModel;

class IklanController extends BaseController
{
    protected $iklanModel;
    protected $AdminModel;
    protected $image;

    public function __construct()
    {
        $this->iklanModel = new IklanModel();
        $this->image = \Config\Services::image();
    }


    public function index()
    {
        $data = [
            'title' => 'Manajemen Iklan'
        ];
        return view('admin/iklan/list', $data);
    }
    public function datatables()
    {
        if ($this->request->isAJAX()) {
            $request = $this->request;
            $postData = $request->getPost();

            // Ambil nilai filter dari AJAX
            $filterStatus = $postData['filter_status'] ?? '';

            // Inisialisasi Query
            $builder = $this->iklanModel->builder();

            // LOGIKA FILTER CUSTOM
            if (!empty($filterStatus)) {
                // Sesuaikan 'status_iklan' dengan nama kolom di database Anda
                $builder->where('status_iklan', $filterStatus);
            }

            // --- Logika Pencarian Standard DataTables ---
            $searchValue = $postData['search']['value'];
            if ($searchValue) {
                $builder->groupStart()
                    ->like('nama', $searchValue)
                    ->orLike('text', $searchValue)
                    ->groupEnd();
            }

            // Hitung Total Data (setelah filter)
            $totalDataFiltered = $builder->countAllResults(false);

            // Pagination & Order
            $start = $postData['start'];
            $length = $postData['length'];
            $columnOrder = $postData['order'][0]['column'];
            $dirOrder = $postData['order'][0]['dir'];

            // Mapping kolom untuk ordering
            $columns = ['nama', 'file', 'url', 'status_iklan', 'id'];
            $builder->orderBy($columns[$columnOrder], $dirOrder);

            $list = $builder->limit($length, $start)->get()->getResult();

            $data = [];
            foreach ($list as $s) {
                // Render Badge HTML (Sama seperti logika di View sebelumnya)
                $badgeClass = ($s->status_iklan == 'depan') ? 'bg-success' : (($s->status_iklan == 'modal') ? 'bg-warning' : 'bg-info');
                $badgeLabel = ($s->status_iklan == 'depan') ? 'Iklan Depan' : (($s->status_iklan == 'modal') ? 'Iklan POP UP' : 'Nav-bar');

                $row = [];
                $row[] = '<b>' . $s->nama . '</b><br><small>' . $s->text . '</small>';
                $row[] = '<img src="' . base_url('uploads/iklan/thumbnails/' . $s->file) . '" class="rounded zoom" style="width:70px">';
                $row[] = '<a href="' . $s->url . '" target="_blank" class="small">Kunjungi Link</a>';
                $row[] = '<span class="badge ' . $badgeClass . '">' . $badgeLabel . '</span>';
                $row[] = '<div class="dropdown custom-dropdown text-center">
                            <a class="dropdown-toggle badge badge-primary border-0" href="javascript:void(0)" role="button" id="dropdownMenu' . $s->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow border-0" aria-labelledby="dropdownMenu' . $s->id . '" style="border-radius:10px;">
                                <a class="dropdown-item py-2 edit-iklan" href="javascript:void(0)" 
                                data-iklan="' . encrypt_url($s->id) . '">
                                    <i class="bi bi-pencil-square me-2 text-primary"></i> Edit Iklan
                                </a> 
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item py-2 text-danger btn-delete" href="javascript:void(0)" data-url="' . base_url('sw-admin/iklan/delete/' . encrypt_url($s->id)) . '">
                                    <i class="bi bi-trash me-2"></i> Hapus Iklan
                                </a>
                            </div>
                        </div>';
                $data[] = $row;
            }

            return $this->response->setJSON([
                "draw" => intval($postData['draw']),
                "recordsTotal" => $this->iklanModel->countAllResults(),
                "recordsFiltered" => $totalDataFiltered,
                "data" => $data,
                "token" => csrf_hash() // Update token CSRF untuk request selanjutnya
            ]);
        }
    }

    public function store()
    {
        try {
            $file = $this->request->getFile('file');
            $newName = null;

            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/iklan', $newName);

                // Resize & Create Thumbnail
                $this->image->withFile(FCPATH . 'uploads/iklan/' . $newName)
                    ->save(FCPATH . 'uploads/iklan/thumbnails/' . $newName, 80);

                // Hapus original jika hanya butuh thumbnail
                if (file_exists(FCPATH . 'uploads/iklan/' . $newName)) {
                    unlink(FCPATH . 'uploads/iklan/' . $newName);
                }
            }

            $data = $this->iklanModel->insert([
                'nama'         => $this->request->getPost('nama'),
                'file'         => $newName,
                'url'          => $this->request->getPost('url'),
                'text'         => '',
                'status'       => 'I',
                'status_iklan' => $this->request->getPost('status_iklan'),
            ]);

            return redirect()->to('sw-admin/iklan')->with('success', 'Iklan berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function edit($id = null)
    {
        // 1. Cek apakah ini request AJAX (Mencegah akses langsung via URL browser)
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Direct access not allowed']);
        }

        try {
            // 2. Dekripsi ID (Sangat disarankan agar user tidak bisa menebak ID lain)
            // Gunakan helper yang Anda miliki, contoh: decrypt_url()
            $realId = decrypt_url($id);

            if (!$realId) {
                return $this->response->setJSON(['status' => false, 'message' => 'Invalid ID Format'])->setStatusCode(400);
            }

            // 3. Ambil data menggunakan model
            $data = $this->iklanModel->find($realId);

            // 4. Cek apakah data benar-benar ada
            if (!$data) {
                return $this->response->setJSON(['status' => false, 'message' => 'Data tidak ditemukan'])->setStatusCode(404);
            }

            // 5. Kembalikan data dalam format JSON yang bersih
            return $this->response->setJSON([
                'status' => true,
                'data'   => $data,
                'token'  => csrf_hash() // Update token agar AJAX selanjutnya tidak forbidden
            ]);
        } catch (\Exception $e) {
            // Log error jika diperlukan: log_message('error', $e->getMessage());
            return $this->response->setJSON(['status' => false, 'message' => 'Server Error'])->setStatusCode(500);
        }
    }

    public function update()
    {
        try {
            $id_post = $this->request->getPost('id_iklan');
            $id = decrypt_url($id_post);

            if (empty($id)) {
                return redirect()->back()->with('error', 'ID tidak valid.');
            }

            // 1. Ambil nama file lama dari database/hidden input
            $file_lama = $this->request->getPost('file_lama');
            $newName = $file_lama; // Default: pakai nama file lama

            $file = $this->request->getFile('file');

            // 2. Cek apakah user mengunggah file baru
            if ($file && $file->isValid() && !$file->hasMoved()) {

                // Generate nama baru
                $newName = $file->getRandomName();

                // Pindahkan file baru ke folder temporary/original
                $file->move(FCPATH . 'uploads/iklan', $newName);

                // Buat Thumbnail dari file baru
                $this->image->withFile(FCPATH . 'uploads/iklan/' . $newName)
                    ->save(FCPATH . 'uploads/iklan/thumbnails/' . $newName, 80);

                // 3. HAPUS FILE LAMA (Hanya jika upload file baru sukses)
                if (!empty($file_lama)) {
                    $path_thumb_lama = FCPATH . 'uploads/iklan/thumbnails/' . $file_lama;
                    if (file_exists($path_thumb_lama)) {
                        unlink($path_thumb_lama);
                    }
                }

                // Opsional: Hapus file asli di folder 'uploads/iklan' jika Anda hanya ingin simpan thumbnail
                if (file_exists(FCPATH . 'uploads/iklan/' . $newName)) {
                    unlink(FCPATH . 'uploads/iklan/' . $newName);
                }
            }

            // 4. Update Database
            $this->iklanModel->update($id, [
                'nama'         => $this->request->getPost('nama'),
                'file'         => $newName, // Tetap $file_lama jika tidak ada upload baru
                'url'          => $this->request->getPost('url'),
                'text'         => '',
                'status_iklan' => $this->request->getPost('status_iklan'),
            ]);

            return redirect()->to('sw-admin/iklan')->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        $idiklan = decrypt_url($id);
        $data = $this->iklanModel->find($idiklan);

        if ($data) {
            if (file_exists('./uploads/iklan/thumbnails/' . $data['file'])) {
                unlink('./uploads/iklan/thumbnails/' . $data['file']);
            }
            $this->iklanModel->delete($idiklan);
            return redirect()->to('sw-admin/iklan')->with('success', 'Iklan berhasil dihapus');
        }
        return redirect()->to('sw-admin/iklan')->with('error', 'Data tidak ditemukan');
    }
}

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
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'List Iklan', 'url' => '#'],
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
                // 1. Logika Badge Metronic (Gaya Soft/Light)
                // Depan -> Success, Modal -> Warning, Nav -> Info
                $statusMap = [
                    'depan' => ['class' => 'badge-light-success', 'label' => 'Iklan Depan'],
                    'modal' => ['class' => 'badge-light-warning', 'label' => 'Iklan POP UP'],
                    'nav'   => ['class' => 'badge-light-info', 'label' => 'Nav-bar']
                ];

                $currentStatus = $statusMap[$s->status_iklan] ?? ['class' => 'badge-light-primary', 'label' => 'Lainnya'];
                $id_encrypt = encrypt_url($s->id);

                $row = [];

                // KOLOM 1: Info Iklan (Nama & Sub-teks)
                $row[] = '
                <div class="d-flex flex-column">
                    <a href="javascript:void(0)" class="text-gray-800 text-hover-primary mb-1 fw-bold fs-6">' . htmlspecialchars($s->nama) . '</a>
                    <span class="text-muted fw-semibold d-block fs-7">' . htmlspecialchars($s->text) . '</span>
                </div>';

                // KOLOM 2: Thumbnail dengan gaya Symbol Metronic
                $row[] = '
                <div class="symbol symbol-50px">
                    <img src="' . base_url('uploads/iklan/thumbnails/' . $s->file) . '" alt="Thumbnail" class="rounded zoom shadow-sm" style="object-fit: cover;">
                </div>';

                // KOLOM 3: URL (Gaya Link Button kecil)
                $row[] = '
                <a href="' . $s->url . '" target="_blank" class="btn btn-sm btn-light btn-active-light-primary fw-bold">
                    <i class="ki-duotone ki-external-drive fs-5 me-1"><span class="path1"></span><span class="path2"></span><i class="path3"></i></i> Kunjungi
                </a>';

                // KOLOM 4: Status Badge
                $row[] = '<span class="badge ' . $currentStatus['class'] . ' fw-bold px-4 py-3">' . $currentStatus['label'] . '</span>';

                // KOLOM 5: Opsi (Dropdown Metronic 8)
                $row[] = '
                <div class="text-center">
                    <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        Aksi <i class="ki-duotone ki-down fs-5 ms-1"></i>
                    </a>
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold py-4 w-150px fs-7" data-kt-menu="true">
                        <div class="menu-item px-3">
                            <a href="javascript:void(0)" class="menu-link px-3 edit-iklan" data-iklan="' . $id_encrypt . '">
                                <i class="ki-duotone ki-pencil fs-5 me-2"><span class="path1"></span><span class="path2"></span></i> Edit Iklan
                            </a>
                        </div>
                        <div class="menu-item px-3">
                            <a href="javascript:void(0)" class="menu-link px-3 text-danger btn-delete" data-url="' . base_url('sw-admin/iklan/delete/' . $id_encrypt) . '">
                                <i class="ki-duotone ki-trash fs-5 me-2 text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Hapus Iklan
                            </a>
                        </div>
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
                'text'         => $this->request->getPost('text'),
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
                'text'         => $this->request->getPost('text'),
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

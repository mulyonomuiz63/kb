<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class TwibbonController extends BaseController
{
    protected $twibbonModel;

    public function __construct()
    {
        $this->twibbonModel = new \App\Models\TwibbonModel();
    }

    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'List Twibbon', 'url' => '#'],
        ];
        return view('admin/twibbon/list', $data);
    }

    public function datatables()
    {
        if ($this->request->isAJAX()) {
            try {
                $request = $this->request;
                $model = $this->twibbonModel;

                // Parameter Datatables
                $start = $request->getPost('start');
                $length = $request->getPost('length');
                $search = $request->getPost('search')['value'];

                // Query Utama
                $builder = $model->builder();

                // Filter Search
                if ($search) {
                    $builder->groupStart()
                        ->like('judul', $search)
                        ->orLike('url', $search)
                        ->groupEnd();
                }

                $totalFiltered = $builder->countAllResults(false);
                $dataRows = $builder->orderBy('idtwibbon', 'desc')->limit($length, $start)->get()->getResult();

                $data = [];
                $no = $start + 1;
                foreach ($dataRows as $row) {
                    $id_encrypt = encrypt_url($row->idtwibbon);

                    // Opsi menggunakan Metronic Menu (Dropdown modern)
                    $opsi = '
                    <div class="text-center">
                        <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            Aksi <i class="ki-duotone ki-down fs-5 ms-1"></i>
                        </a>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold py-4 w-150px fs-7" data-kt-menu="true">
                            <div class="menu-item px-3">
                                <a href="javascript:void(0)" class="menu-link px-3 editBtn" data-id="' . $id_encrypt . '">
                                    <i class="ki-duotone ki-pencil fs-5 me-2"><span class="path1"></span><span class="path2"></span></i> Edit
                                </a>
                            </div>
                            <div class="menu-item px-3">
                                <a href="javascript:void(0)" class="menu-link px-3 text-success shareBtn" data-judul="' . $row->judul . '" data-url="' . $row->url . '">
                                    <i class="ki-duotone ki-whatsapp fs-5 me-2 text-success"><span class="path1"></span><span class="path2"></span></i> Share WA
                                </a>
                            </div>
                            <div class="separator my-2 opacity-75"></div>
                            <div class="menu-item px-3">
                                <a href="javascript:void(0)" class="menu-link px-3 text-danger btn-delete" data-url="' . base_url('sw-admin/twibbon/delete/' . $id_encrypt) . '">
                                    <i class="ki-duotone ki-trash fs-5 me-2 text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Hapus
                                </a>
                            </div>
                        </div>
                    </div>';

                    // Menggunakan Symbol Metronic untuk tampilan gambar yang lebih elegan
                    $img = '
                    <div class="symbol symbol-50px">
                        <img src="' . base_url('uploads/twibbon/thumbnails/' . $row->file) . '" alt="Twibbon" class="rounded shadow-sm" style="object-fit: cover;">
                    </div>';

                    $data[] = [
                        '<span class="text-gray-600 fw-bold d-block fs-7">' . $no++ . '</span>',
                        $img,
                        '<div>
                            <span class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">' . htmlspecialchars($row->judul) . '</span>
                            <div class="text-muted fw-semibold fs-7">' . $row->url . '</div>
                        </div>',
                        '<span class="text-gray-700 fw-semibold fs-7">' . substr(strip_tags($row->caption), 0, 50) . '...</span>',
                        $opsi
                    ];
                }

                return $this->response->setJSON([
                    "draw"            => intval($request->getPost('draw')),
                    "recordsTotal"    => $model->countAll(),
                    "recordsFiltered" => $totalFiltered,
                    "data"            => $data,
                    "token"           => csrf_hash()
                ]);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    "error" => $e->getMessage(),
                    "token" => csrf_hash()
                ]);
            }
        }
    }

    public function store()
    {
        try {
            $id_raw = $this->request->getPost('idtwibbon');
            $idtwibbon = !empty($id_raw) ? decrypt_url($id_raw) : null;

            $url     = $this->request->getPost('url');
            $judul   = $this->request->getPost('judul');
            $caption = $this->request->getPost('caption');
            $file    = $this->request->getFile('file');

            // --- VALIDASI URL UNIK ---
            $cek = $this->twibbonModel->where('url', $url);
            if ($idtwibbon) {
                $cek->where('idtwibbon !=', $idtwibbon);
            }

            if ($cek->get()->getRow()) {
                throw new \Exception('Slug URL sudah digunakan oleh twibbon lain.');
            }

            // Ambil data lama jika sedang edit
            $data_lama = $idtwibbon ? $this->twibbonModel->find($idtwibbon) : null;
            $newName = $data_lama ? $data_lama['file'] : null;

            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $path = FCPATH . 'uploads/twibbon';
                $thumbnail_path = $path . '/thumbnails';

                if ($file->move($path, $newName)) {
                    \Config\Services::image('gd')
                        ->withFile($path . '/' . $newName)
                        ->save($thumbnail_path . '/' . $newName, 100, 'png');

                    if (file_exists($path . '/' . $newName)) {
                        unlink($path . '/' . $newName);
                    }

                    if ($data_lama && !empty($data_lama['file']) && file_exists($thumbnail_path . '/' . $data_lama['file'])) {
                        unlink($thumbnail_path . '/' . $data_lama['file']);
                    }
                }
            }

            $saveData = [
                'url'     => $url,
                'judul'   => $judul,
                'caption' => $caption,
                'file'    => $newName
            ];

            if ($idtwibbon) {
                $saveData['idtwibbon'] = $idtwibbon;
                $this->twibbonModel->save($saveData);
                $msg = "Data berhasil diperbarui";
            } else {
                $this->twibbonModel->insert($saveData);
                $msg = "Data berhasil disimpan";
            }

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => $msg,
                'token'   => csrf_hash()
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $e->getMessage(),
                'token'   => csrf_hash()
            ]);
        }
    }

    public function edit()
    {
        if ($this->request->isAJAX()) {
            try {
                $id_encrypted = $this->request->getVar('idtwibbon');
                $idtwibbon = decrypt_url($id_encrypted);

                $data_twibbon = $this->twibbonModel->find($idtwibbon);

                if ($data_twibbon) {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'data'   => $data_twibbon,
                        'token'  => csrf_hash()
                    ]);
                } else {
                    throw new \Exception('Data tidak ditemukan');
                }
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => $e->getMessage(),
                    'token'   => csrf_hash()
                ]);
            }
        }
    }

    public function delete($id)
    {
        try {
            $idtwibbon = decrypt_url($id);
            $data = $this->twibbonModel->find($idtwibbon);

            if (!$data) {
                throw new \Exception("Data twibbon tidak ditemukan.");
            }

            $file_path = FCPATH . 'uploads/twibbon/thumbnails/' . $data['file'];
            if (!empty($data['file']) && file_exists($file_path)) {
                unlink($file_path);
            }

            if (!$this->twibbonModel->delete($idtwibbon)) {
                throw new \Exception("Gagal menghapus data dari database.");
            }

            return redirect()->to(base_url('sw-admin/twibbon'))->with('success', 'Twibbon berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->to(base_url('sw-admin/twibbon'))->with('error', $e->getMessage());
        }
    }

    public function cekUrl()
    {
        if ($this->request->isAJAX()) {
            try {
                $url = $this->request->getVar('url');
                $id_raw = $this->request->getVar('idtwibbon');
                $idtwibbon = !empty($id_raw) ? decrypt_url($id_raw) : null;

                $builder = $this->twibbonModel->where('url', $url);

                if ($idtwibbon) {
                    $builder->where('idtwibbon !=', $idtwibbon);
                }

                $exists = $builder->get()->getRow();

                return $this->response->setJSON([
                    'status' => $exists ? 'taken' : 'available',
                    'token'  => csrf_hash()
                ]);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'token'  => csrf_hash()
                ]);
            }
        }
    }
}

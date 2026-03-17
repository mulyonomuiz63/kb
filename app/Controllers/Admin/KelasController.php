<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;


class KelasController extends BaseController
{

    protected $kelasModel;
    public function __construct()
    {
        $this->kelasModel = new \App\Models\KelasModel();
    }

    public function index()
    {
        $data = [
            'title'        => 'Data Kelas',
        ];
        $data['kelas'] = $this->kelasModel->asObject()->findAll();
        return view('admin/kelas/list', $data);
    }

    public function store()
    {
        try {
            $nama_kelas = $this->request->getVar('nama_kelas');
            $data_kelas = [];

            if (empty($nama_kelas)) {
                return redirect()->back()->with('error', 'Nama kelas tidak boleh kosong.');
            }

            foreach ($nama_kelas as $nama) {
                if (!empty($nama)) {
                    $data_kelas[] = ['nama_kelas' => $nama];
                }
            }

            $this->kelasModel->insertBatch($data_kelas);
            return redirect()->to('sw-admin/kelas')->with('success', count($data_kelas) . ' Data kelas berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambah kelas: ' . $e->getMessage());
        }
    }

    public function edit()
    {
        if ($this->request->isAJAX()) {
            try {
                $id_input = $this->request->getVar('id_kelas');
                $id_kelas = decrypt_url($id_input);
                $data = $this->kelasModel->asObject()->find($id_kelas);

                if ($data) {
                    return $this->response->setJSON([
                        'token_baru' => csrf_hash(),
                        'kelas'      => $data
                    ]);
                }
            } catch (\Exception $e) {
                return $this->response->setStatusCode(500)->setJSON(['error' => $e->getMessage()]);
            }
        }
    }

    public function update()
    {
        try {
            $id_kelas = $this->request->getVar('id_kelas'); // Pastikan ini ID asli dari form hidden

            $this->kelasModel->save([
                'id_kelas'   => $id_kelas,
                'nama_kelas' => $this->request->getVar('nama_kelas')
            ]);

            return redirect()->to('sw-admin/kelas')->with('success', 'Nama kelas berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui kelas: ' . $e->getMessage());
        }
    }

    public function delete($id = '')
    {
        try {
            $id_kelas = decrypt_url($id);
            $this->kelasModel->delete($id_kelas);

            return redirect()->to('sw-admin/kelas')->with('success', 'Kelas berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to('sw-admin/kelas')->with('error', 'Gagal menghapus kelas: ' . $e->getMessage());
        }
    }
}

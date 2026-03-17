<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class KategoriController extends BaseController
{
    protected $kategoriModel;

    public function __construct()
    {
        $this->kategoriModel = new \App\Models\KategoriModel();
    }
    //Kategori
    public function index()
    {
        $data['kategori'] = $this->kategoriModel->getAll();
        return view('guru/kategori/list', $data);
    }
    public function create()
    {
        return view('guru/kategori/tambah_');
    }
    public function store()
    {
        // DATA DETAIL UJIAN PG
        $nama_kategori = $this->request->getVar('nama_kategori');

        $data_kategori = array(
            'nama_kategori' => $nama_kategori,
            'nama_kategori_slug' => url_title($nama_kategori, '-', TRUE),
        );
        // END DATA DETAIL UJIAN PG

        $this->kategoriModel->insert($data_kategori);
        return redirect()->to('sw-guru/kategori')->with('success', 'Kategori berhasil dibuat');
    }


    public function edit()
    {
        if ($this->request->isAJAX()) {
            try {
                // Dekripsi ID
                $id_raw = $this->request->getVar('id_kategori');
                $id_kategori = decrypt_url($id_raw);

                // Ambil data
                $data_kategori = $this->kategoriModel->getById($id_kategori);

                // Bungkus dalam array beserta CSRF Hash terbaru
                $response = [
                    'status'    => true,
                    'kategori'  => $data_kategori,
                    'csrf_hash' => csrf_hash() // Kirim hash baru ke view
                ];

                return $this->response->setJSON($response);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'status'    => false,
                    'csrf_hash' => csrf_hash(),
                    'message'   => $e->getMessage()
                ]);
            }
        }
    }

    public function update()
    {
        $nama_kategori = $this->request->getVar('nama_kategori');

        $data_kategori = array(
            'nama_kategori' => $nama_kategori,
            'nama_kategori_slug' => url_title($nama_kategori, '-', TRUE),
        );
        // END DATA DETAIL UJIAN PG



        $this->kategoriModel->set($data_kategori)->where('id_kategori', $this->request->getVar('id_kategori'))->update();

        return redirect()->to('sw-guru/kategori')->with('success', 'Kategori berhasil diubah.');
    }

    public function delete($id_kategori)
    {
        $this->kategoriModel
            ->where('id_kategori', decrypt_url($id_kategori))
            ->delete();
        return redirect()->to('sw-guru/kategori')->with('success', 'Kategori berhasil dihapus');
    }
    //end kategori
}

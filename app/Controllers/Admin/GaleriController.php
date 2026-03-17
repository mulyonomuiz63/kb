<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GaleriModel;

class GaleriController extends BaseController
{
    protected $galeriModel;
    protected $image;

    public function __construct()
    {
        $this->galeriModel = new GaleriModel();
        $this->image = \Config\Services::image();
    }

    public function index()
    {
        $data = [
            'title' => 'Galeri',
        ];

        // Master data untuk datatables tidak perlu di-load di sini karena pakai AJAX
        return view('admin/galeri/list', $data);
    }

    // --- FUNGSI BARU UNTUK DATATABLES SERVER SIDE ---
    public function datatables()
    {
        // Cek apakah request datang dari AJAX
        if (!$this->request->isAJAX()) {
            return redirect()->to('auth');
        }

        $request = $this->request;
        $postData = $request->getPost();

        // 1. Parameter dari DataTables
        $draw       = $postData['draw'];
        $start      = $postData['start'];
        $rowperpage = $postData['length'];
        $searchValue = $postData['search']['value'];

        // 2. Hitung Total Data
        $totalRecords = $this->galeriModel->countAll();
        $totalRecordwithFilter = $this->galeriModel->like('judul', $searchValue)->countAllResults();

        // 3. Ambil Data
        $records = $this->galeriModel->like('judul', $searchValue)
            ->orderBy('idgaleri', 'DESC')
            ->findAll($rowperpage, $start);

        $data = array();
        $no = $start + 1;

        foreach ($records as $row) {
            $id_encrypt = encrypt_url($row['idgaleri']);

            // Opsi Tombol menggunakan Bootstrap Icons (bi)
            // Bootstrap 4 menggunakan class 'badge'
            $opsi = '<div class="dropdown custom-dropdown">
                        <a class="dropdown-toggle" href="javascript:void(0)" role="button" data-toggle="dropdown" id="drop' . $row['idgaleri'] . '"><i class="bi bi-three-dots-vertical"></i></a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="drop' . $row['idgaleri'] . '">
                            <a class="dropdown-item edit-galeri" href="javascript:void(0)" data-id="' . $id_encrypt . '">Edit</a>
                            <a class="dropdown-item text-danger btn-delete" href="javascript:void(0)" data-url="' . base_url('sw-admin/galeri/delete/' . $id_encrypt) . '">Hapus</a>
                        </div>
                    </div>';

            // Kolom Gambar
            $img = '<img src="' . base_url('uploads/galeri/thumbnails/' . $row['file']) . '" 
                     class="img-fluid zoom view-galeri img-thumbnail" 
                     style="width:80px; cursor:pointer;" 
                     data-galeri="' . $row['file'] . '" 
                     role="button">';

            // Mapping Data ke Kolom Tabel
            $data[] = array(
                "no"            => $no++,
                "judul"         => '<div class="text-wrap" style="min-width: 200px;">' . $row['judul'] . '</div>',
                "file"          => $img,
                "tgl_pelatihan" => $row['tgl_pelatihan'],
                "opsi"          => $opsi,
            );
        }

        // 5. Response JSON dengan CSRF Token baru
        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "data"                 => $data,
            "token"                => csrf_hash()
        );

        return $this->response->setJSON($response);
    }

    public function store()
    {
        try {
            $judul         = $this->request->getPost('judul');
            $file          = $this->request->getFile('file');
            $tgl_pelatihan = $this->request->getPost('tgl_pelatihan');
            $newName       = null;

            if ($file && $file->isValid()) {
                $newName = $file->getRandomName();
                $path = FCPATH . 'uploads/galeri';
                $thumbnail_path = $path . '/thumbnails';

                if ($file->move($path, $newName)) {
                    $this->image->withFile($path . '/' . $newName)
                        ->resize(1012, 1012, true, 'auto')
                        ->save($thumbnail_path . '/' . $newName, 80);

                    if (file_exists($path . '/' . $newName)) unlink($path . '/' . $newName);
                }
            }

            $this->galeriModel->insert([
                'judul'         => $judul,
                'tgl_pelatihan' => $tgl_pelatihan,
                'file'          => $newName,
            ]);

            return redirect()->to('sw-admin/galeri')->with('success', 'Data galeri berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function edit()
    {
        if (!$this->request->isAJAX()) return redirect()->to('auth');

        try {
            $id = decrypt_url($this->request->getVar('idgaleri'));

            // Ambil data (tanpa asObject agar konsisten sebagai Array)
            $data_galeri = $this->galeriModel->find($id);

            if ($data_galeri) {
                // Pastikan data_galeri adalah array sebelum diisi token
                if (is_object($data_galeri)) {
                    $data_galeri->token = csrf_hash();
                } else {
                    $data_galeri['token'] = csrf_hash();
                }

                return $this->response->setJSON($data_galeri);
            } else {
                return $this->response->setStatusCode(404)->setJSON([
                    'error' => 'Data tidak ditemukan',
                    'token' => csrf_hash()
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'error' => $e->getMessage(),
                'token' => csrf_hash()
            ]);
        }
    }

    public function update()
    {
        if (session()->get('role') != 1) return redirect()->to('auth');

        try {
            $idgaleri      = $this->request->getPost('idgaleri');
            $judul         = $this->request->getPost('judul');
            $file          = $this->request->getFile('file');
            $file_lama     = $this->request->getPost('file_lama');
            $tgl_pelatihan = $this->request->getPost('tgl_pelatihan');

            if ($file && $file->isValid()) {
                $newName = $file->getRandomName();
                $path = FCPATH . 'uploads/galeri';
                $thumbnail_path = $path . '/thumbnails';

                if ($file->move($path, $newName)) {
                    $this->image->withFile($path . '/' . $newName)
                        ->resize(1012, 1012, true, 'auto')
                        ->save($thumbnail_path . '/' . $newName, 80);

                    if (file_exists($thumbnail_path . '/' . $file_lama)) unlink($thumbnail_path . '/' . $file_lama);
                    if (file_exists($path . '/' . $newName)) unlink($path . '/' . $newName);
                }
            } else {
                $newName = $file_lama;
            }

            $this->galeriModel->save([
                'idgaleri'      => $idgaleri,
                'judul'         => $judul,
                'tgl_pelatihan' => $tgl_pelatihan,
                'file'          => $newName,
            ]);

            return redirect()->to('sw-admin/galeri')->with('success', 'Data galeri berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $idgaleri = decrypt_url($id);
            $data = $this->galeriModel->asObject()->find($idgaleri);

            if ($data) {
                if (file_exists('./uploads/galeri/thumbnails/' . $data->file)) {
                    unlink('./uploads/galeri/thumbnails/' . $data->file);
                }
                $this->galeriModel->delete($idgaleri);
                return redirect()->to('sw-admin/galeri')->with('success', 'Data berhasil dihapus!');
            }
        } catch (\Exception $e) {
            return redirect()->to('sw-admin/galeri')->with('error', 'Gagal menghapus data!');
        }
    }
}

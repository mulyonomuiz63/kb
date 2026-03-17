<?php

namespace App\Controllers\Admin; // Sesuaikan namespace Anda
use App\Controllers\BaseController;

class ArtikelController extends BaseController
{

    public function __construct() {}

    public function index()
    {
        $data = [
            'title' => 'Daftar Artikel',
        ];
        return view('admin/artikel/list', $data);
    }

    public function datatables()
    {
        $request = $this->request;
        $postData = $request->getPost();

        try {
            $builder = $this->db->table('artikel a')
                ->select('a.id, a.judul, a.image_default, a.hit, a.status, k.kategori')
                ->join('kategori_artikel k', 'k.id = a.idkategori', 'left');

            // --- FITUR SEARCH (Pencegah Unknown Column) ---
            if (!empty($postData['search']['value'])) {
                $search = $postData['search']['value'];
                $builder->groupStart()
                    ->like('a.judul', $search)
                    ->orLike('k.kategori', $search)
                    ->groupEnd();
            }

            // --- FITUR ORDERING (Pencegah Ambiguous Column) ---
            if (isset($postData['order'])) {
                $colIdx = $postData['order'][0]['column'];
                $colDir = $postData['order'][0]['dir'];
                $columns = ['a.judul', null, 'a.hit', 'a.status', null]; // Sesuaikan index kolom table
                if ($columns[$colIdx]) {
                    $builder->orderBy($columns[$colIdx], $colDir);
                }
            } else {
                $builder->orderBy('a.id', 'DESC');
            }

            // --- TOTAL RECORDS ---
            $totalAll = $this->db->table('artikel')->countAllResults();
            $totalFiltered = $builder->countAllResults(false); // false agar builder tidak reset

            // --- LIMIT & OFFSET (Agar Server-Side Berfungsi) ---
            $data = $builder->get(
                intval($postData['length'] ?? 10),
                intval($postData['start'] ?? 0)
            )->getResult();

            $results = [];
            foreach ($data as $s) {
                $status_badge = [
                    'utama_up'   => '<span class="badge badge-primary">Utama Atas</span>',
                    'utama_down' => '<span class="badge badge-success">Utama Bawah</span>',
                    'rekomendasi' => '<span class="badge badge-warning">Rekomendasi</span>'
                ];

                $row = [];
                $row[] = '<div class="text-wrap" style="min-width:200px"><b>' . esc($s->judul) . '</b><br><small class="text-muted">' . esc($s->kategori) . '</small></div>';
                $row[] = '<img src="' . base_url('uploads/artikel/thumbnails/' . $s->image_default) . '" class="rounded border preview-img" style="cursor:pointer; width:60px" data-src="' . base_url('uploads/artikel/thumbnails/' . $s->image_default) . '">';
                $row[] = '<span class="badge outline-badge-info">' . $s->hit . ' Views</span>';
                $row[] = $status_badge[$s->status] ?? '<span class="badge badge-secondary">Draft</span>';
                $row[] = '
                <div class="dropdown custom-dropdown">
                    <a class="dropdown-toggle" href="javascript:void(0)" role="button" data-toggle="dropdown" id="drop' . $s->id . '"><i class="bi bi-three-dots-vertical"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="drop' . $s->id . '">
                        <a class="dropdown-item" href="' . base_url('sw-admin/artikel/edit/' . encrypt_url($s->id)) . '">Edit</a>
                        <a class="dropdown-item text-danger btn-delete" href="' . base_url('sw-admin/artikel/delete/' . encrypt_url($s->id)) . '">Hapus</a>
                    </div>
                </div>';
                $results[] = $row;
            }

            return $this->response->setJSON([
                "draw"            => intval($postData['draw'] ?? 1),
                "recordsTotal"    => $totalAll,
                "recordsFiltered" => $totalFiltered,
                "data"            => $results,
                "token"           => csrf_hash()
            ]);
        } catch (\Exception $e) {
            // KIRIM DETAIL QUERY JIKA ERROR (Untuk tracking)
            return $this->response->setStatusCode(500)->setJSON([
                'error'   => true,
                'message' => $e->getMessage(),
                'query'   => (string)$this->db->getLastQuery(),
                'token'   => csrf_hash()
            ]);
        }
    }
    public function create()
    {
        $data = [
            'title'        => 'Tambah Artikel',
            'parent_title' => 'List Artikel',
            'parent_url'   => base_url('sw-admin/artikel'),
            'kategori'     => $this->db->table('kategori_artikel')->get()->getResultObject()
        ];
        return view('admin/artikel/create', $data);
    }

    public function store()
    {
        $judul = $this->request->getPost('judul');
        $slug = slug($judul);

        // Cek Duplikasi Slug
        if ($this->db->table('artikel')->where('slug_judul', $slug)->countAllResults() > 0) {
            return redirect()->back()->withInput()->with('error', 'Judul sudah ada!');
        }

        // --- LOGIKA KATEGORI OTOMATIS (BARU) ---
        $idkategori = $this->request->getPost('idkategori');

        // Jika input bukan angka, berarti ini kategori baru dari Select2 Tags
        if (!is_numeric($idkategori) && !empty($idkategori)) {
            $namaKategori = $idkategori;
            $slugKategori = slug($namaKategori);

            // Cek dulu apakah slug kategori ini sudah ada (mencegah duplikasi/typo huruf besar-kecil)
            $cekKategori = $this->db->table('kategori_artikel')
                ->where('slug_kategori', $slugKategori)
                ->get()->getRowObject();

            if ($cekKategori) {
                // Jika sudah ada, pakai ID yang lama
                $idkategori = $cekKategori->id;
            } else {
                // Jika benar-benar baru, masukkan ke tabel kategori
                $this->db->table('kategori_artikel')->insert([
                    'kategori'      => $namaKategori,
                    'slug_kategori' => $slugKategori,
                ]);
                $idkategori = $this->db->insertID();
            }
        }
        // ---------------------------------------

        $file = $this->request->getFile('image_default');
        $newName = 'default.jpg';

        if ($file && $file->isValid()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/artikel', $newName);

            // Thumbnail process
            \Config\Services::image()
                ->withFile(FCPATH . 'uploads/artikel/' . $newName)
                ->resize(800, 600, true, 'height')
                ->save(FCPATH . 'uploads/artikel/thumbnails/' . $newName);

            if (file_exists(FCPATH . 'uploads/artikel/' . $newName)) unlink(FCPATH . 'uploads/artikel/' . $newName);
        }

        // Masukkan data ke artikel (idkategori sudah dipastikan berupa ID angka di sini)
        $this->db->table('artikel')->insert([
            'iduser'        => session()->get('id'),
            'idkategori'    => $idkategori, // Menggunakan variabel $idkategori hasil olahan di atas
            'judul'         => $judul,
            'slug_judul'    => $slug,
            'konten'        => $this->request->getPost('konten'),
            'image_default' => $newName,
            'status'        => $this->request->getPost('status'),
        ]);

        // Insert Tags
        $idartikel = $this->db->insertID();
        $this->_handleTags($idartikel, $this->request->getPost('tags'));

        return redirect()->to('sw-admin/artikel')->with('success', 'Artikel berhasil diterbitkan!');
    }

    private function _handleTags($idartikel, $tagInput)
    {
        if (!empty($tagInput)) {
            $tags = explode(",", $tagInput);
            foreach ($tags as $item) {
                $this->db->table('tag_artikel')->insert([
                    'idartikel' => $idartikel,
                    'tag'       => trim($item),
                    'slug_tag'  => slug($item)
                ]);
            }
        }
    }

    public function edit($id)
    {
        $id_decoded = decrypt_url($id);
        $artikel = $this->db->table('artikel')->where('id', $id_decoded)->get()->getRowObject();

        if (!$artikel) {
            return redirect()->to('sw-admin/artikel')->with('error', 'Artikel tidak ditemukan');
        }

        $data = [
            'title'    => 'Edit Artikel',
            'kategori' => $this->db->table('kategori_artikel')->get()->getResultObject(),
            'artikel'  => $artikel,
            'tag'      => $this->db->table('tag_artikel')->where('idartikel', $id_decoded)->get()->getResultObject(),
        ];

        return view('admin/artikel/edit', $data);
    }

    public function update()
    {
        $idartikel = $this->request->getPost('idartikel');
        $file      = $this->request->getFile('image_default');
        $file_lama = $this->request->getPost('image_default_lama');
        $judul     = $this->request->getPost('judul');

        // --- LOGIKA KATEGORI OTOMATIS (BARU) ---
        $idkategori = $this->request->getPost('idkategori');

        // Jika input bukan angka, berarti user mengetik kategori baru di Select2
        if (!is_numeric($idkategori) && !empty($idkategori)) {
            $namaKategori = $idkategori;
            $slugKategori = slug($namaKategori);

            // Cek apakah slug kategori ini sudah ada untuk menghindari duplikasi typo
            $cekKategori = $this->db->table('kategori_artikel')
                ->where('slug_kategori', $slugKategori)
                ->get()->getRowObject();

            if ($cekKategori) {
                // Jika sudah ada, gunakan ID yang sudah ada
                $idkategori = $cekKategori->id;
            } else {
                // Jika benar-benar baru, simpan ke tabel kategori
                $this->db->table('kategori_artikel')->insert([
                    'kategori'      => $namaKategori,
                    'slug_kategori' => $slugKategori,
                ]);
                $idkategori = $this->db->insertID();
            }
        }
        // ---------------------------------------

        // Tentukan Path
        $base_path = FCPATH . 'uploads/artikel/';
        $thumb_path = $base_path . 'thumbnails/';

        // Handle Upload Gambar
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();

            // Pindahkan file asli ke folder uploads/artikel
            if ($file->move($base_path, $newName)) {

                // Pastikan folder thumbnail ada
                if (!is_dir($thumb_path)) {
                    mkdir($thumb_path, 0777, true);
                }

                // Buat Thumbnail
                \Config\Services::image()
                    ->withFile($base_path . $newName)
                    ->resize(800, 600, true, 'height')
                    ->save($thumb_path . $newName, 80);

                // Hapus file lama (Thumbnail & Asli) jika ada file baru
                if (!empty($file_lama)) {
                    if (file_exists($base_path . $file_lama)) unlink($base_path . $file_lama);
                    if (file_exists($thumb_path . $file_lama)) unlink($thumb_path . $file_lama);
                }
            }
        } else {
            $newName = $file_lama;
        }

        $data = [
            'iduser'        => session()->get('id'),
            'idkategori'    => $idkategori, // Menggunakan ID hasil olahan logika kategori di atas
            'judul'         => $judul,
            'slug_judul'    => slug($judul),
            'konten'        => $this->request->getPost('konten'),
            'image_default' => $newName,
            'status'        => $this->request->getPost('status'),
        ];

        $update = $this->db->table('artikel')->where('id', $idartikel)->update($data);

        if ($update) {
            // Handle Tags
            $tagInput = $this->request->getPost('tags');
            if (!empty($tagInput)) {
                // Tag lama tidak dihapus semua agar tombol "X" (delete via AJAX) yang Anda buat sebelumnya tetap relevan, 
                // Namun di sini kita tambahkan tag baru yang diketik di input "tags"
                $tags = explode(",", $tagInput);
                foreach ($tags as $item) {
                    if (trim($item) == "") continue;

                    $namaTag = trim($item);
                    $slugTag = slug($namaTag);

                    // Opsional: Cek agar tidak insert tag yang sama persis untuk artikel ini
                    $cekTag = $this->db->table('tag_artikel')
                        ->where(['idartikel' => $idartikel, 'slug_tag' => $slugTag])
                        ->countAllResults();

                    if ($cekTag == 0) {
                        $this->db->table('tag_artikel')->insert([
                            'idartikel' => $idartikel,
                            'tag'       => $namaTag,
                            'slug_tag'  => $slugTag,
                        ]);
                    }
                }
            }
            return redirect()->to('sw-admin/artikel')->with('success', 'Artikel berhasil diperbarui');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data');
    }

    public function delete($id)
    {
        $idartikel = decrypt_url($id);
        $artikel = $this->db->table('artikel')->where('id', $idartikel)->get()->getRow();

        if ($artikel) {
            $path = FCPATH . 'uploads/artikel/thumbnails/' . $artikel->image_default;
            if (file_exists($path) && $artikel->image_default != 'default.jpg') unlink($path);

            $this->db->table('artikel')->delete(['id' => $idartikel]);
            $this->db->table('tag_artikel')->delete(['idartikel' => $idartikel]);
        }

        return redirect()->to('sw-admin/artikel')->with('success', 'Artikel berhasil dihapus!');
    }

    // START::SUMMERNOTE
    // Di dalam method upload_summernote controller Anda
    public function uploadSummernote()
    {
        $file = $this->request->getFile('image');
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'assets/app-assets/artikel', $newName);

            return $this->response->setJSON([
                'url'   => base_url('assets/app-assets/artikel/' . $newName),
                // Kirim token baru ke frontend
                'token' => csrf_hash()
            ]);
        }
    }

    public function deleteImage()
    {
        $src = $this->request->getPost('src');

        if (empty($src)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Source kosong', 'token' => csrf_hash()]);
        }

        // 1. Ambil path relatif (hapus base_url)
        $relative_path = str_replace(base_url(), '', $src);

        // 2. Bersihkan slash di awal jika ada (agar tidak double slash saat digabung FCPATH)
        $relative_path = ltrim($relative_path, '/');

        // 3. Konversi semua forward-slash (/) menjadi DIRECTORY_SEPARATOR sistem (\ atau /)
        // Ini penting agar terbaca oleh file sistem Windows maupun Linux
        $clean_path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relative_path);

        // 4. Gabungkan dengan FCPATH
        $full_path = FCPATH . $clean_path;

        if (file_exists($full_path)) {
            if (unlink($full_path)) {
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'File berhasil dihapus',
                    'token'   => csrf_hash()
                ]);
            } else {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Gagal menghapus file di server',
                    'token'   => csrf_hash()
                ]);
            }
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'File tidak ditemukan di: ' . $full_path, // Tambahkan ini untuk debug sementara
            'token'   => csrf_hash()
        ]);
    }
    // END::SUMMERNOTE

    public function deleteTag($id = '')
    {
        try {
            $idtag = decrypt_url($id);

            // Cari data tag untuk memastikan datanya ada
            $tag = $this->db->table('tag_artikel')->where("id", $idtag)->get()->getRowObject();

            if ($tag) {
                $this->db->table('tag_artikel')->where("id", $idtag)->delete();

                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Tag berhasil dihapus',
                    'token'  => csrf_hash(),
                ]);
            } else {
                return $this->response->setStatusCode(404)->setJSON([
                    'status'  => 'error',
                    'message' => 'Data tag tidak ditemukan',
                    'token'  => csrf_hash(),
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => $e->getMessage(),
                'token'  => csrf_hash(),
            ]);
        }
    }
}

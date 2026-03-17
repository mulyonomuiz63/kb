<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;


class PaketController extends BaseController
{
    protected $paketModel;
    protected $ujianMasterModel;
    protected $kelasModel;
    protected $mapelModel;
    protected $detailPaketModel;
    protected $reviewModel;

    public function __construct()
    {
        $this->paketModel = new \App\Models\PaketModel();
        $this->ujianMasterModel = new \App\Models\UjianMasterModel();
        $this->kelasModel = new \App\Models\KelasModel();
        $this->mapelModel = new \App\Models\MapelModel();
        $this->detailPaketModel = new \App\Models\DetailPaketModel();
        $this->reviewModel = new \App\Models\ReviewModel();
    }

    public function index()
    {

        try {
            // MASTER DATA
            $data['paket'] = $this->paketModel->join('diskon b', 'b.iddiskon = paket.iddiskon')
                ->orderBy('paket.sort_order', 'ASC')
                ->get()->getResultObject();

            $data['ujian'] = $this->ujianMasterModel->getAll();
            $data['kelas'] = $this->kelasModel->asObject()->find();

            return view('admin/paket/list', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    public function edit()
    {
        // Proteksi Role
        if (session()->get('role') != 1) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Unauthorized']);
        }

        if ($this->request->isAJAX()) {
            try {
                $idRaw = $this->request->getVar('idpaket');
                $idDecrypted = decrypt_url($idRaw);

                if (!$idDecrypted) {
                    throw new \Exception('ID Paket tidak valid.');
                }

                $data_paket = $this->paketModel->asArray()->find($idDecrypted);

                if (!$data_paket) {
                    throw new \Exception('Data paket tidak ditemukan.');
                }

                // Tambahkan Token CSRF baru ke dalam array respons
                $data_paket[csrf_token()] = csrf_hash();

                return $this->response->setJSON($data_paket);
            } catch (\Exception $e) {
                return $this->response->setStatusCode(500)->setJSON([
                    'error' => $e->getMessage(),
                    csrf_token() => csrf_hash() // Tetap kirim token baru meski error
                ]);
            }
        }

        return $this->response->setStatusCode(404);
    }

    public function store()
    {

        try {
            // Validasi Input Wajib
            if (empty($this->request->getVar('id_ujian')) && empty($this->request->getVar('id_mapel'))) {
                return redirect()->to('sw-admin/paket')->with('info', 'Salah satu ujian dan mapel harus diisi');
            }

            // Handle Upload Gambar
            $file = $this->request->getFile('avatar');
            $newName = null;

            if ($file && $file->isValid() && !$file->hasMoved()) {
                $path = FCPATH . 'assets-landing/images/paket';
                $thumbnail_path = $path . '/thumbnails';
                $newName = $file->getRandomName();

                if ($file->move($path, $newName)) {
                    // Image Processing (Resize)
                    $this->image->withFile($path . '/' . $newName)
                        ->resize(1012, 1012, true, 'auto')
                        ->save($thumbnail_path . '/' . $newName, 80);

                    // Hapus file asli setelah resize (sesuai logika lama Anda)
                    if (file_exists($path . '/' . $newName)) {
                        unlink($path . '/' . $newName);
                    }
                }
            }

            // Logika v_ujian & v_materi
            $v_ujian = ($this->request->getVar('id_ujian') == "all") ? "all" : (empty($this->request->getVar('id_ujian')) ? "0" : "1");
            $v_materi = ($this->request->getVar('id_mapel') == "all") ? "all" : (empty($this->request->getVar('id_mapel')) ? "0" : "1");

            // Database Transaction Start
            $db = \Config\Database::connect();
            $db->transStart();

            $data_paket = [
                'iddiskon'      => $this->request->getVar('iddiskon'),
                'nama_paket'    => $this->request->getVar('nama_paket'),
                'jenis_paket'   => $this->request->getVar('jenis_paket'),
                'jumlah_bulan'  => $this->request->getVar('jumlah_bulan'),
                'nominal_paket' => $this->request->getVar('nominal_paket'),
                'file'          => $newName,
                'status'        => $this->request->getVar('status'),
                'v_ujian'       => $v_ujian,
                'v_materi'      => $v_materi,
                'deskripsi'     => $this->request->getVar('deskripsi'),
                'komisi'        => $this->request->getVar('komisi'),
            ];

            $this->paketModel->insert($data_paket);
            $id_paket = $this->paketModel->insertID();

            // Ambil data ujian untuk detail paket
            $data_master = $this->ujianMasterModel
                ->join('mapel', 'mapel.id_mapel=ujian_master.mapel')
                ->where('ujian_master.kelas', $this->request->getVar('id_kelas'))
                ->groupBy('ujian_master.id_ujian')
                ->get()->getResultObject();

            // Loop Detail Paket (Logika dipertahankan)
            foreach ($data_master as $rows) {
                $insert_detail = false;
                $id_u = $this->request->getVar('id_ujian');
                $id_m = $this->request->getVar('id_mapel');

                if ($id_u == 'all' && $id_m == 'all') {
                    $insert_detail = ['id_ujian' => $rows->id_ujian, 'id_mapel' => $rows->mapel];
                } elseif ($id_u == 'all' && empty($id_m)) {
                    $insert_detail = ['id_ujian' => $rows->id_ujian, 'id_mapel' => $id_m];
                } elseif (empty($id_u) && $id_m == 'all') {
                    $insert_detail = ['id_ujian' => $id_u, 'id_mapel' => $rows->mapel];
                }

                if ($insert_detail) {
                    $this->detailPaketModel->insert(array_merge(['idpaket' => $id_paket], $insert_detail));
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception("Gagal menyimpan data ke database.");
            }

            return redirect()->to('sw-admin/paket')->with('success', 'Data paket berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->to('sw-admin/paket')->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function update()
    {
        try {
            $file = $this->request->getFile('avatar');
            $gambar_lama = $this->request->getVar('gambar_lama');
            $newName = $gambar_lama;

            if ($file && $file->isValid() && !$file->hasMoved()) {
                $path = FCPATH . 'assets-landing/images/paket';
                $thumbnail_path = $path . '/thumbnails';
                $newName = $file->getRandomName();

                if ($file->move($path, $newName)) {
                    // Resize
                    $this->image->withFile($path . '/' . $newName)
                        ->resize(1012, 1012, true, 'auto')
                        ->save($thumbnail_path . '/' . $newName, 80);

                    // Hapus file asli
                    if (file_exists($path . '/' . $newName)) unlink($path . '/' . $newName);

                    // Hapus thumbnail lama
                    if (!empty($gambar_lama) && file_exists($thumbnail_path . '/' . $gambar_lama)) {
                        unlink($thumbnail_path . '/' . $gambar_lama);
                    }
                }
            }

            $this->paketModel->save([
                'idpaket'       => $this->request->getVar('idpaket'),
                'iddiskon'      => $this->request->getVar('iddiskon'),
                'nama_paket'    => $this->request->getVar('nama_paket'),
                'jenis_paket'   => $this->request->getVar('jenis_paket'),
                'jumlah_bulan'  => $this->request->getVar('jumlah_bulan'),
                'nominal_paket' => $this->request->getVar('nominal_paket'),
                'file'          => $newName,
                'status'        => $this->request->getVar('status'),
                'deskripsi'     => $this->request->getVar('deskripsi'),
                'komisi'        => $this->request->getVar('komisi'),
            ]);

            return redirect()->to('sw-admin/paket')->with('success', 'Data paket berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->to('sw-admin/paket')->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }
    public function ujianMaster()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('auth');
        }

        try {
            $data = $this->ujianMasterModel
                ->where('kelas', $this->request->getVar('id'))
                ->get()->getResultObject();

            return $this->response->setJSON([
                'status' => 'success',
                'data'   => $data,
                csrf_token() => csrf_hash() // Kirim token baru
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => $e->getMessage(),
                csrf_token() => csrf_hash()
            ]);
        }
    }

    public function getMapel()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('auth');
        }

        try {
            $data = $this->mapelModel->join('materi', 'materi.mapel=mapel.id_mapel')
                ->where('kelas', $this->request->getVar('id'))
                ->groupBy('mapel.id_mapel')
                ->get()->getResultObject();

            return $this->response->setJSON([
                'status' => 'success',
                'data'   => $data,
                csrf_token() => csrf_hash()
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => $e->getMessage(),
                csrf_token() => csrf_hash()
            ]);
        }
    }



    // untuk drag urutan
    public function reorder()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        try {
            $data = $this->request->getJSON(true);
            $db = \Config\Database::connect();
            $builder = $db->table('paket');

            $db->transStart(); // Gunakan transaksi database agar aman
            foreach ($data as $row) {
                $builder->where('idpaket', $row['id'])
                    ->update(['sort_order' => $row['position']]);
            }
            $db->transComplete();

            return $this->response->setJSON([
                'status' => 'success',
                csrf_token() => csrf_hash()
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => $e->getMessage(),
                csrf_token() => csrf_hash()
            ]);
        }
    }

    // untuk pin 
    public function pin()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('auth');
        }

        try {
            $id = $this->request->getPost('id');
            $db = \Config\Database::connect();

            // ambil data paket
            $paket = $db->table('paket')
                ->where('idpaket', $id)
                ->get()
                ->getRowArray();

            if (!$paket) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan',
                    csrf_token() => csrf_hash()
                ]);
            }

            $db->transStart();
            // toggle pin
            if ($paket['is_pinned'] == 1) {
                // UNPIN
                $db->table('paket')
                    ->where('idpaket', $id)
                    ->update(['is_pinned' => 0]);
            } else {
                // PIN → geser semua pinned yang ada
                $db->table('paket')
                    ->set('sort_order', 'sort_order + 1', false)
                    ->where('is_pinned', 1)
                    ->update();

                $db->table('paket')
                    ->where('idpaket', $id)
                    ->update(['is_pinned' => 1]);
            }
            $db->transComplete();

            return $this->response->setJSON([
                'status' => 'success',
                csrf_token() => csrf_hash()
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => $e->getMessage(),
                csrf_token() => csrf_hash()
            ]);
        }
    }

    public function review($slug)
    {
        try {
            // Gunakan Query Builder CI4 agar lebih aman (SQL Injection Protection)
            $data['review'] = $this->db->table('review_ujian d')
                ->select('q.nama_siswa, q.avatar, d.id_review, d.status, d.komentar, d.rating, d.created_at')
                ->join('ujian_master c', 'c.kode_ujian = d.kode_ujian')
                ->join('detail_paket b', 'b.id_ujian = c.id_ujian')
                ->join('paket p', 'p.idpaket = b.idpaket')
                ->join('siswa q', 'q.id_siswa = d.id_siswa')
                ->where('p.slug', $slug)
                ->orderBy('d.created_at', 'DESC')
                ->get()->getResult();


            return view('admin/paket/review', $data);
        } catch (\Exception $e) {
            return redirect()->to('sw-admin/paket')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function editReview()
    {
        if ($this->request->isAJAX()) {
            try {
                $idRaw = $this->request->getVar('id_review');
                $idDecrypted = decrypt_url($idRaw);

                if (!$idDecrypted) {
                    throw new \Exception('ID Review tidak valid.');
                }

                $data_review = $this->reviewModel->asArray()->find($idDecrypted);

                if (!$data_review) {
                    throw new \Exception('Data tidak ditemukan.');
                }

                // Kirim balik data beserta Token CSRF baru
                $data_review[csrf_token()] = csrf_hash();

                return $this->response->setJSON($data_review);
            } catch (\Exception $e) {
                return $this->response->setStatusCode(500)->setJSON([
                    'error' => $e->getMessage(),
                    csrf_token() => csrf_hash()
                ]);
            }
        }
    }

    public function updateReview()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }

        try {
            $id_review = $this->request->getVar('id_review'); // Pastikan ini ID asli (hidden field)

            $this->reviewModel->save([
                'id_review' => $id_review,
                'rating'    => $this->request->getVar('rating'),
                'komentar'  => $this->request->getVar('komentar'),
                'status'    => $this->request->getVar('status'),
            ]);

            return redirect()->back()->with('success', 'Data review berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah data: ' . $e->getMessage());
        }
    }
}

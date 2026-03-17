<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MapelModel;

class MapelController extends BaseController
{
    protected $mapelModel;
    protected $guruMapelModel;
    protected $materiModel;
    protected $fileModel;
    protected $guruModel;
    protected $chatMateriModel;

    public function __construct()
    {
        $this->mapelModel = new MapelModel();
        $this->guruMapelModel = new \App\Models\GuruMapelModel();
        $this->materiModel = new \App\Models\MateriModel();
        $this->fileModel = new \App\Models\FileModel();
        $this->guruModel = new \App\Models\GuruModel();
        $this->chatMateriModel = new \App\Models\ChatMateriModel();
    }

    public function index()
    {

        $data = [
            'title'        => 'Data Mapel',
        ];
        return view('admin/mapel/list', $data);
    }

    public function datatables()
    {
        $request = $this->request;
        $db      = $this->db;
        $builder = $db->table('mapel'); // Sesuaikan nama tabel

        // Logika Server-side
        $searchValue = $request->getPost('search')['value'];
        $start = $request->getPost('start');
        $length = $request->getPost('length');

        if ($searchValue) {
            $builder->like('nama_mapel', $searchValue);
        }

        $totalFiltered = $builder->countAllResults(false);
        $data = $builder->limit($length, $start)->get()->getResult();

        $result = [];
        $no = $start + 1;
        foreach ($data as $row) {
            $imgUrl = base_url('uploads/mapel/' . $row->file);
            $opsi = '
            <div class="dropdown custom-dropdown text-center">
                <a class="dropdown-toggle badge badge-primary border-0" href="#" role="button" data-toggle="dropdown">
                    <i class="bi bi-three-dots-vertical"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow border-0">
                    <a class="dropdown-item py-2 edit-mapel" href="javascript:void(0)" data-id="' . encrypt_url($row->id_mapel) . '">
                        <i class="bi bi-pencil-square text-primary"></i> Edit
                    </a>
                    <a class="dropdown-item py-2 btn-delete" href="javascript:void(0)" data-url="' . base_url('sw-admin/mapel/delete/' . encrypt_url($row->id_mapel)) . '">
                        <i class="bi bi-trash text-danger"></i> Hapus
                    </a>
                </div>
            </div>';

            $result[] = [
                $no++,
                '<strong>' . $row->nama_mapel . '</strong>',
                '<img src="' . $imgUrl . '" class="img-thumbnail" style="width:80px; height:auto;">',
                $opsi
            ];
        }

        return $this->response->setJSON([
            "draw"            => intval($request->getPost('draw')),
            "recordsTotal"    => $this->mapelModel->countAll(),
            "recordsFiltered" => $totalFiltered,
            "data"            => $result,
            "token"           => csrf_hash() // Kirim token baru ke DataTable
        ]);
    }

    public function store()
    {
        try {
            $nama_mapel = $this->request->getVar('nama_mapel');
            $files = $this->request->getFileMultiple('gambar_mapel');
            $data_mapel = [];

            foreach ($files as $key => $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'uploads/mapel', $newName);

                    $data_mapel[] = [
                        'nama_mapel' => $nama_mapel[$key],
                        'file'       => $newName,
                    ];
                }
            }

            if (!empty($data_mapel)) {
                $this->mapelModel->insertBatch($data_mapel);
                return redirect()->to('sw-admin/mapel')->with('success', 'Berhasil menyimpan ' . count($data_mapel) . ' Mata Pelajaran.');
            }

            return redirect()->back()->with('error', 'Tidak ada data valid untuk disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function edit()
    {
        if ($this->request->isAJAX()) {
            $id = decrypt_url($this->request->getVar('id_mapel'));
            $data = $this->mapelModel->asObject()->find($id);
            return $this->response->setJSON([
                'mapel' => $data,
                'token' => csrf_hash()
            ]);
        }
    }

    public function update()
    {
        try {
            $id = $this->request->getVar('id_mapel');
            $gambar_lama = $this->request->getVar('gambar_mapel_lama');
            $file = $this->request->getFile('gambar_mapel');

            $data = [
                'id_mapel'   => $id,
                'nama_mapel' => $this->request->getVar('nama_mapel')
            ];

            if ($file->isValid()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/mapel', $newName);
                $data['file'] = $newName;

                // Hapus file lama
                if ($gambar_lama && file_exists(FCPATH . 'uploads/mapel/' . $gambar_lama)) {
                    unlink(FCPATH . 'uploads/mapel/' . $gambar_lama);
                }
            }

            $this->mapelModel->save($data);
            return redirect()->to('sw-admin/mapel')->with('success', 'Mata pelajaran berhasil diubah.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id = null)
    {
        // 1. Pastikan ID ada
        if ($id === null) {
            return redirect()->to('sw-admin/mapel')->with('error', 'ID tidak valid.');
        }

        try {
            $id_mapel = decrypt_url($id);

            // 2. Gunakan asObject() agar pemanggilan $mapel->file konsisten
            $mapel = $this->mapelModel->asObject()->find($id_mapel);

            if (!$mapel) {
                return redirect()->to('sw-admin/mapel')->with('error', 'Data tidak ditemukan.');
            }

            // 3. Hapus File Fisik
            if (!empty($mapel->file)) {
                $path = FCPATH . 'uploads/mapel/' . $mapel['file'];
                // Gunakan is_file() untuk memastikan itu benar-benar file, bukan folder
                if (is_file($path) && file_exists($path)) {
                    unlink($path);
                }
            }

            // 4. Hapus Data di DB
            $this->mapelModel->delete($id_mapel);

            return redirect()->to('sw-admin/mapel')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            // Log error jika perlu: log_message('error', $e->getMessage());
            return redirect()->to('sw-admin/mapel')->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    public function mapelGuru($id)
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }
        $idGuru = decrypt_url($id);
        $data['mapel'] = $this->guruMapelModel->join('guru_kelas', 'guru_kelas.guru=guru_mapel.guru')->where('guru_mapel.guru', $idGuru)->groupBy('guru_mapel.mapel')->get()->getResultObject();
        $data['idGuru'] = $id;

        return view('admin/guru/materi/index', $data);
    }

    public function lihatMateri($idmapel, $idkelas, $guru)
    {
        // Mengambil semua materi berdasarkan mapel dan kelas
        $materiList = $this->materiModel->getAllByMapelKelas(decrypt_url($idmapel), decrypt_url($idkelas));

        if (empty($materiList)) {
            return redirect()->back()->with('error', 'Materi tidak ditemukan');
        }

        $data = [
            'materiAll' => $materiList,
            'materi'    => $materiList[0], // Ambil materi pertama untuk default view
            'guru'      => $this->guruModel->where('id_guru', decrypt_url($guru))->get()->getRowObject()
        ];

        return view('admin/guru/materi/lihat-materi', $data);
    }

    public function getChatMateri()
    {
        if ($this->request->isAJAX()) {
            $kode_materi = $this->request->getPost('kode_materi');
            $idguru = decrypt_url($this->request->getPost('idguru'));
            $user = $this->guruModel->find($idguru);

            if (!$kode_materi) {
                return $this->response->setBody('<div class="text-center mt-5 text-muted small">Pilih materi untuk melihat diskusi.</div>');
            }

            $chat_materi = $this->chatMateriModel->getAllByKodeMateri($kode_materi);
            $myEmail = $user['email'];

            $html = '';

            if (empty($chat_materi)) {
                $html = '<div class="text-center mt-5 text-muted small">Belum ada diskusi di materi ini.</div>';
            } else {
                foreach ($chat_materi as $chat) {
                    $isMe = ($chat->email == $myEmail);
                    $namaUser = esc($chat->nama);
                    $pesan = esc($chat->text);
                    $gambar = $chat->gambar ? base_url('assets/app-assets/user/' . $chat->gambar) : base_url('assets/app-assets/img/90x90.jpg');
                    $waktu = date('H:i', strtotime($chat->date_created ?? 'now'));

                    // Logika Bubble Chat
                    $alignClass = $isMe ? 'flex-row-reverse text-right' : '';
                    $bgClass = $isMe ? 'bg-primary text-white' : 'bg-light text-dark';
                    $marginAvatar = $isMe ? 'ml-3' : 'mr-3';
                    $borderRadius = $isMe ? 'border-radius: 15px 15px 2px 15px;' : 'border-radius: 15px 15px 15px 2px;';

                    $html .= '
                <div class="d-flex mb-4 ' . $alignClass . '">
                    <div class="avatar ' . $marginAvatar . ' flex-shrink-0">
                        <img src="' . $gambar . '" class="rounded-circle shadow-sm" style="width:35px; height:35px; object-fit:cover;" />
                    </div>
                    <div style="max-width: 80%;">
                        <div class="d-flex align-items-center mb-1 ' . ($isMe ? 'justify-content-end' : '') . '">
                            <small class="font-weight-bold" style="font-size: 11px; color: #888;">' . $namaUser . '</small>
                        </div>
                        <div class="p-2 shadow-sm ' . $bgClass . '" style="' . $borderRadius . ' font-size: 13px; white-space: pre-line; display: inline-block; text-align: left;">
                            ' . $pesan . '
                        </div>
                        <div class="mt-1">
                            <small class="text-muted" style="font-size: 9px;">' . $waktu . '</small>
                        </div>
                    </div>
                </div>';
                }
            }

            return $this->response
                ->setHeader('X-CSRF-TOKEN', csrf_hash())
                ->setBody($html);
        }

        return redirect()->to('auth');
    }

    public function getFileMateri()
    {
        if ($this->request->isAJAX()) {
            $kode_materi = $this->request->getPost('kode_materi');

            if (!$kode_materi) {
                return $this->response->setBody('<div class="text-center mt-5 text-muted small">Pilih materi untuk melihat file.</div>');
            }

            // Pastikan cara dekrip kode_materi sesuai dengan cara kirim di JS
            // Jika di JS tidak di-encrypt, hapus fungsi decrypt_url()
            $decoded_kode = decrypt_url($kode_materi) ?: $kode_materi;
            $file = $this->fileModel->getAllByKode($decoded_kode);

            $html = '';

            if (empty($file)) {
                $html = '<div class="text-center mt-5 text-muted small">
                        <i class="bi bi-folder2-open d-block mb-2" style="font-size: 2rem; color: #ddd;"></i>
                        Belum ada file materi tersedia.
                     </div>';
            } else {
                $html .= '<div class="list-group list-group-flush">';
                foreach ($file as $m) {
                    if ($m->nama_file) {
                        $filePath = base_url('assets/app-assets/file/' . $m->nama_file);
                        $namaTampil = str_replace('_', ' ', pathinfo($m->nama_file, PATHINFO_FILENAME));

                        $html .= '
                    <a href="' . $filePath . '" download class="list-group-item list-group-item-action d-flex align-items-center border-0 mb-2 shadow-sm rounded" style="background: #fdfdfd; transition: all 0.3s;">
                        <div class="icon-file mr-3">
                            <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size: 1.8rem;"></i>
                        </div>
                        <div class="file-info flex-grow-1">
                            <h6 class="mb-0 text-dark font-weight-bold" style="font-size: 13px;">' . $namaTampil . '</h6>
                            <small class="text-muted" style="font-size: 11px;">Klik untuk download PDF</small>
                        </div>
                        <div class="download-icon">
                            <i class="bi bi-cloud-arrow-down-fill text-primary" style="font-size: 1.2rem;"></i>
                        </div>
                    </a>';
                    }
                }
                $html .= '</div>';
            }

            return $this->response
                ->setHeader('X-CSRF-TOKEN', csrf_hash())
                ->setBody($html);
        }

        return redirect()->to('auth');
    }

    public function chatMateri()
    {
        if ($this->request->isAJAX()) {
            $kode_materi = $this->request->getPost('kode_materi');
            $chat_text   = $this->request->getPost('chat_materi');
            $idguru_raw  = $this->request->getPost('idguru');

            // Validasi input dasar
            if (empty($chat_text) || empty($idguru_raw)) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Pesan atau ID Guru tidak boleh kosong',
                    'token' => csrf_hash() // Kirim token baru
                ])->setStatusCode(400);
            }

            $idguru = decrypt_url($idguru_raw);
            $user = $this->guruModel->find($idguru);

            if (!$user) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Data Guru tidak ditemukan',
                    'token' => csrf_hash()
                ])->setStatusCode(404);
            }

            // Ambil data (sesuaikan apakah $user itu array atau object)
            // Jika find() mengembalikan array (default CI4), gunakan $user['nama_guru']
            $data = [
                'materi'       => $kode_materi,
                'nama'         => is_array($user) ? $user['nama_guru'] : $user->nama_guru,
                'gambar'       => is_array($user) ? $user['avatar'] : $user->avatar,
                'email'        => is_array($user) ? $user['email'] : $user->email,
                'text'         => strip_tags($chat_text), // Bersihkan tag HTML
                'date_created' => time()
            ];

            try {
                $this->chatMateriModel->save($data);

                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'Pesan terkirim',
                    'token' => csrf_hash() // Sangat penting: Kirim token baru ke JS
                ]);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Gagal menyimpan ke database',
                    'token' => csrf_hash()
                ])->setStatusCode(500);
            }
        }
        return redirect()->to('auth');
    }
}

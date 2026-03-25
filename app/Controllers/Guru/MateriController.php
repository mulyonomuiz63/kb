<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\MapelModel;

class MateriController extends BaseController
{
    protected $mapelModel;
    protected $guruMapelModel;
    protected $guruKelasModel;
    protected $materiModel;
    protected $fileModel;
    protected $guruModel;
    protected $chatMateriModel;
    protected $kelasModel;
    protected $siswaModel;
    protected $materiSiswaModel;

    public function __construct()
    {
        $this->mapelModel = new MapelModel();
        $this->guruMapelModel = new \App\Models\GuruMapelModel();
        $this->guruKelasModel = new \App\Models\GuruKelasModel();
        $this->materiModel = new \App\Models\MateriModel();
        $this->fileModel = new \App\Models\FileModel();
        $this->guruModel = new \App\Models\GuruModel();
        $this->chatMateriModel = new \App\Models\ChatMateriModel();
        $this->kelasModel = new \App\Models\KelasModel();
        $this->siswaModel = new \App\Models\SiswaModel();
        $this->materiSiswaModel = new \App\Models\MateriSiswaModel();
    }

    // START::MATERI
    public function index()
    {
        $data['mapel'] = $this->guruMapelModel->join('guru_kelas', 'guru_kelas.guru=guru_mapel.guru')->where('guru_mapel.guru', session()->get('id'))->groupBy('guru_mapel.mapel')->get()->getResultObject();

        $data['guru_kelas'] = $this->guruKelasModel->getALLByGuru(session()->get('id'));
        $data['guru_mapel'] = $this->guruMapelModel->getALLByGuru(session()->get('id'));

        return view('guru/materi/index', $data);
    }

    // START::MATERI
    public function lihat($id, $kelas)
    {

        $data['idmapel'] = $id;
        $data['idkelas'] = $kelas;
        $data['id_mapel'] = decrypt_url($id);
        $data['id_kelas'] = decrypt_url($kelas);
        $data['materi'] = $this->materiModel->getAllByGuru(session()->get('id'), decrypt_url($id));

        $data['guru_kelas'] = $this->guruKelasModel->getALLByGuru(session()->get('id'));
        $data['guru_mapel'] = $this->guruMapelModel->getALLByGuru(session()->get('id'));

        return view('guru/materi/list', $data);
    }

    public function store()
    {
        $idmapel = encrypt_url($this->request->getVar('mapel'));
        $idkelas = encrypt_url($this->request->getVar('kelas'));
        $thumbs = $this->request->getPost();
        $link_video = [];
        if (isset($thumbs['text_materi'])) {
            foreach ($thumbs['text_materi'] as $thumb) {
                $link_video[] = $thumb;
            }
        }
        $data_materi = [
            'kode_materi' => $this->request->getVar('kode_materi'),
            'nama_materi' => $this->request->getVar('nama_materi'),
            'guru' => session()->get('id'),
            'mapel' => $this->request->getVar('mapel'),
            'kelas' => $this->request->getVar('kelas'),
            'text_materi' => json_encode($link_video),
            'status' => $this->request->getVar('status'),
            'date_created' => time()
        ];



        $siswa = $this->siswaModel
            ->where('kelas', $this->request->getVar('kelas'))
            ->get()->getResultObject();

        if (count($siswa) == 0) {
            session()->setFlashdata('pesan', "
                swal({
                    title: 'Oops!',
                    text: 'Belum ada siswa dikelas ini',
                    type: 'error',
                    padding: '2em'
                    });
                ");
            return redirect()->to('guru/materi/' . $idmapel . '/' . $idkelas);
        }

        // CEK APAKAH ADA FILE U=YANG DIPILIH
        $file_materi = $this->request->getFileMultiple('file_materi');
        if ($file_materi[0]->getError() != 4) {
            $data_file_materi = [];

            // FUNGSI UPLOAD FILE
            foreach ($file_materi as $file) {
                // Generate nama file Random
                $nama_file = str_replace(' ', '_', $file->getName());
                // Upload Gambar
                $file->move('assets/app-assets/file/', $nama_file);

                array_push($data_file_materi, [
                    'kode_file' => $this->request->getVar('kode_materi'),
                    'nama_file' => $nama_file
                ]);
            }

            $this->fileModel->insertBatch($data_file_materi);
        }

        $siswa_materi = [];
        foreach ($siswa as $s2) {
            array_push($siswa_materi, array(
                'materi' => $this->request->getVar('kode_materi'),
                'kelas' => $this->request->getVar('kelas'),
                'mapel' => $this->request->getVar('mapel'),
                'siswa' => $s2->id_siswa,
                'dilihat' => 0
            ));
        }

        // INSERT DATA MATERI
        $this->materiModel->save($data_materi);
        // INSERT DATA MATERI SISWA
        $this->materiSiswaModel->insertBatch($siswa_materi);
        return redirect()->to('sw-guru/materi/lihat/' . $idmapel . '/' . $idkelas)->with('success', 'Materi telah dibuat');
    }

    public function edit()
    {
        if ($this->request->isAJAX()) {
            $id_materi = decrypt_url($this->request->getVar('id_materi'));
            $data_materi = $this->materiModel->asArray()->find($id_materi);

            if ($data_materi) {
                // Tambahkan token terbaru ke dalam array response
                $data_materi['token'] = csrf_hash();
                return $this->response->setJSON($data_materi);
            }

            return $this->response->setJSON([
                'token' => csrf_hash(),
                'error' => 'Data tidak ditemukan'
            ], 404);
        }
    }
    public function update()
    {
        $idmapel = encrypt_url($this->request->getVar('e_mapel'));
        $idkelas = encrypt_url($this->request->getVar('e_kelas'));
        $kode_materi = $this->request->getVar('e_kode_materi');

        // CEK APAKAH ADA FILE U=YANG DIPILIH
        $file_materi = $this->request->getFileMultiple('e_file_materi');
        if ($file_materi[0]->getError() != 4) {
            $data_file_materi = [];

            // FUNGSI UPLOAD FILE
            foreach ($file_materi as $file) {
                // Generate nama file Random
                $nama_file = str_replace(' ', '_', $file->getName());
                // Upload Gambar
                $file->move('assets/app-assets/file/', $nama_file);

                array_push($data_file_materi, [
                    'kode_file' => $this->request->getVar('e_kode_materi'),
                    'nama_file' => $nama_file
                ]);
            }

            $this->fileModel->insertBatch($data_file_materi);
        }

        $thumbs = $this->request->getPost();
        $link_video = [];
        if (isset($thumbs['e_text_materi'])) {
            foreach ($thumbs['e_text_materi'] as $thumb) {
                $link_video[] = $thumb;
            }
        }

        $this->materiModel
            ->set('nama_materi', $this->request->getVar('e_nama_materi'))
            ->set('mapel', $this->request->getVar('e_mapel'))
            ->set('kelas', $this->request->getVar('e_kelas'))
            ->set('text_materi', json_encode($link_video))
            ->set('status', $this->request->getVar('e_status'))
            ->where('kode_materi', $kode_materi)
            ->update();
        return redirect()->to('sw-guru/materi/lihat/' . $idmapel . '/' . $idkelas)->with('success', 'Materi telah diupdate');
    }

    public function lihatMateri($id, $idmapel, $idkelas)
    {
        $id_materi = decrypt_url($id);
        $data['materiAll'] = $this->materiModel->getAllByMapelKelas(decrypt_url($idmapel), decrypt_url($idkelas));
        $data['materi'] = $this->materiModel->getById($id_materi);
        $data['guru'] = $this->guruModel->asObject()->find(session()->get('id'));
        $data['file'] = $this->fileModel->getMateriWithFile(decrypt_url($idmapel), decrypt_url($idkelas));

        return view('guru/materi/lihat-materi', $data);
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
                    $waktu = timeAgo($chat->date_created);

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

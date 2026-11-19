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
    public function lihat($id, $kelas)
    {
        $idguru = session()->get('idguru_diadmin') ?? session()->get('id');
        // Cek apakah session idguru_diadmin ada (berarti diakses oleh admin)
        $isAdmin = !empty(session()->get('idguru_diadmin'));
        $data['breadcrumbs'] = [
            [
                'title' => $isAdmin ? 'Instruktur': 'Dashboard',
                'url'   => $isAdmin ? base_url('sw-admin/guru') : base_url('sw-guru') // Sesuaikan 'sw-admin' dengan route dashboard admin Anda
            ],
            ['title' => 'List Ujian', 'url' => '#'],
        ];

        $data['idmapel'] = $id;
        $data['idkelas'] = $kelas;
        $data['id_mapel'] = decrypt_url($id);
        $data['id_kelas'] = decrypt_url($kelas);
        $data['materi'] = $this->materiModel->getAllByGuru($idguru, decrypt_url($id));

        $data['guru_kelas'] = $this->guruKelasModel->getALLByGuru($idguru);
        $data['guru_mapel'] = $this->guruMapelModel->getALLByGuru($idguru);

        return view('guru/materi/list', $data);
    }

    public function store()
    {
        $idguru = session()->get('idguru_diadmin') ?? session()->get('id');
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
            'guru' => $idguru,
            'mapel' => $this->request->getVar('mapel'),
            'kelas' => $this->request->getVar('kelas'),
            'text_materi' => json_encode($link_video),
            'status' => $this->request->getVar('status'),
            'date_created' => time()
        ];



        $id_kelas_dipilih = $this->request->getVar('kelas');

        $siswa = $this->siswaModel
            ->select('siswa.*')
            ->join('siswa_kelas', 'siswa_kelas.id_siswa = siswa.id_siswa', 'left')
            ->groupStart()
            ->where('siswa.kelas', $id_kelas_dipilih)          // Menangkap data siswa kelas lama
            ->orWhere('siswa_kelas.id_kelas', $id_kelas_dipilih) // Menangkap data siswa multi-kelas (pivot)
            ->groupEnd()
            ->groupBy('siswa.id_siswa') // Mencegah data siswa muncul dua kali jika terdaftar di kedua tempat
            ->get()->getResultObject();

        if (count($siswa) == 0) {
            return redirect()->to('sw-guru/materi/lihat/' . $idmapel . '/' . $idkelas)->with('pesan', 'Belum ada siswa dikelas ini');
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
        $idguru = session()->get('idguru_diadmin') ?? session()->get('id');
        $id_materi = decrypt_url($id);
        $data['materiAll'] = $this->materiModel->getAllByMapelKelas(decrypt_url($idmapel), decrypt_url($idkelas));
        $data['materi'] = $this->materiModel->getById($id_materi);
        $data['guru'] = $this->guruModel->asObject()->find($idguru);
        $data['file'] = $this->fileModel->getMateriWithFile(decrypt_url($idmapel), decrypt_url($idkelas));

        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-guru')],
            ['title' => 'List Materi', 'url' => '#'],
        ];
        return view('guru/materi/lihat-materi', $data);
    }
    public function getChatMateri()
    {
        if ($this->request->isAJAX()) {
            $kode_materi = (string) $this->request->getPost('kode_materi');
            $last_id     = (int) ($this->request->getPost('last_id') ?? 0);
            $idguru      = decrypt_url($this->request->getPost('idguru'));
            $user        = $this->guruModel->find($idguru);
            $myEmail     = is_array($user) ? ($user['email'] ?? '') : ($user->email ?? '');

            if (!$kode_materi) {
                return $this->response->setJSON([
                    'messages'     => [],
                    'participants' => [],
                    'my_email'     => $myEmail,
                    'token'        => csrf_hash()
                ]);
            }

            $chat_materi = $this->chatMateriModel->where('materi', $kode_materi)
                ->where('id_chat_materi >', $last_id)
                ->orderBy('id_chat_materi', 'ASC')
                ->findAll();

            $participantsData = $this->chatMateriModel->select('nama')
                ->where('materi', $kode_materi)
                ->groupBy('nama')
                ->findAll();
            $participants = [];
            foreach ($participantsData as $p) {
                $pName = is_object($p) ? $p->nama : ($p['nama'] ?? '');
                if ($pName && !in_array($pName, $participants, true)) {
                    $participants[] = $pName;
                }
            }

            // Tandai pesan peserta sebagai telah dibaca
            try {
                if ($myEmail !== '') {
                    $db = \Config\Database::connect();
                    $builder = $db->table('chat_materi');
                    $builder->where('materi', $kode_materi);
                    $builder->where('status_notif', '0');
                    $builder->where('email !=', $myEmail);
                    if ($builder->countAllResults(false) > 0) {
                        $builder->update(['status_notif' => '1']);
                    }
                }
            } catch (\Exception $e) {
            }

            return $this->response->setJSON([
                'messages'     => $chat_materi,
                'participants' => $participants,
                'my_email'     => $myEmail,
                'token'        => csrf_hash()
            ]);
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
            $kode_materi = trim((string) $this->request->getPost('kode_materi'));
            $chat_text   = trim((string) $this->request->getPost('chat_materi'));
            $idguru_raw  = $this->request->getPost('idguru');

            if ($chat_text === '' || empty($idguru_raw) || $kode_materi === '') {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Pesan atau ID Guru tidak boleh kosong',
                    'token'   => csrf_hash()
                ])->setStatusCode(400);
            }

            $idguru = decrypt_url($idguru_raw);
            $user = $this->guruModel->find($idguru);

            if (!$user) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Data Guru tidak ditemukan',
                    'token'   => csrf_hash()
                ])->setStatusCode(404);
            }

            $senderName  = is_array($user) ? ($user['nama_guru'] ?? 'Pengajar') : ($user->nama_guru ?? 'Pengajar');
            $senderEmail = is_array($user) ? ($user['email'] ?? '') : ($user->email ?? '');
            $avatar      = is_array($user) ? ($user['avatar'] ?? 'default.jpg') : ($user->avatar ?? 'default.jpg');

            $data = [
                'materi'       => $kode_materi,
                'nama'         => $senderName,
                'gambar'       => $avatar ?: 'default.jpg',
                'email'        => $senderEmail,
                'text'         => htmlspecialchars($chat_text),
                'status_notif' => '0',
                'date_created' => time()
            ];

            try {
                $this->chatMateriModel->save($data);
                send_diskusi_notif($kode_materi, '3', $senderEmail, $senderName, $chat_text);

                return $this->response->setJSON([
                    'status'  => true,
                    'message' => 'Pesan terkirim',
                    'token'   => csrf_hash()
                ]);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Gagal menyimpan ke database',
                    'token'   => csrf_hash()
                ])->setStatusCode(500);
            }
        }
        return redirect()->to('auth');
    }

    public function updateChatMateri()
    {
        if ($this->request->isAJAX()) {
            try {
                $id_chat    = $this->request->getPost('id_chat');
                $text       = trim((string) $this->request->getPost('text'));
                $idguru_raw = $this->request->getPost('idguru');
                $idguru     = decrypt_url($idguru_raw);
                $user       = $this->guruModel->find($idguru);
                $myEmail    = is_array($user) ? ($user['email'] ?? '') : ($user->email ?? '');

                $pesan = $this->chatMateriModel->find($id_chat);

                if ($pesan && $pesan['email'] === $myEmail && (time() - $pesan['date_created']) <= 300) {
                    $this->chatMateriModel->update($id_chat, [
                        'text' => htmlspecialchars($text)
                    ]);
                    return $this->response->setJSON([
                        'status' => 'success',
                        'token'  => csrf_hash()
                    ]);
                }

                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Pesan tidak dapat diedit (batas 5 menit habis atau akses ditolak).',
                    'token'   => csrf_hash()
                ])->setStatusCode(400);
            } catch (\Exception $e) {
                return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'token' => csrf_hash()])->setStatusCode(500);
            }
        }
    }

    public function deleteChatMateri()
    {
        if ($this->request->isAJAX()) {
            try {
                $id_chat    = $this->request->getPost('id_chat');
                $idguru_raw = $this->request->getPost('idguru');
                $idguru     = decrypt_url($idguru_raw);
                $user       = $this->guruModel->find($idguru);
                $myEmail    = is_array($user) ? ($user['email'] ?? '') : ($user->email ?? '');

                $pesan = $this->chatMateriModel->find($id_chat);

                if ($pesan && $pesan['email'] === $myEmail && (time() - $pesan['date_created']) <= 300) {
                    $this->chatMateriModel->delete($id_chat);
                    return $this->response->setJSON([
                        'status' => 'success',
                        'token'  => csrf_hash()
                    ]);
                }

                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Pesan tidak dapat dihapus (batas 5 menit habis).',
                    'token'   => csrf_hash()
                ])->setStatusCode(400);
            } catch (\Exception $e) {
                return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'token' => csrf_hash()])->setStatusCode(500);
            }
        }
    }
}

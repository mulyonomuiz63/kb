<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class DiskusiController extends BaseController
{
    protected $chatMateriModel;
    protected $materiModel;
    protected $siswaModel;
    protected $guruModel;


    public function __construct()
    {
        $this->chatMateriModel = new \App\Models\ChatMateriModel();
        $this->materiModel = new \App\Models\MateriModel();
        $this->siswaModel = new \App\Models\SiswaModel();
        $this->guruModel = new \App\Models\GuruModel();
    }

    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'Diskusi', 'url' => '#'],
        ];

        $myEmail = session()->get('email');
        $dataGuru = $this->db->table('guru')->get()->getResultArray();
        $sidebarData = [];

        foreach ($dataGuru as $g) {
            $materiByGuru = $this->chatMateriModel
                ->select('chat_materi.materi, materi.nama_materi, COUNT(CASE WHEN status_notif = "0" AND email != "' . $myEmail . '" THEN 1 END) as unread_count')
                ->join('materi', 'chat_materi.materi = materi.kode_materi')
                ->where('materi.guru', $g['id_guru'])
                ->groupBy('chat_materi.materi')
                ->findAll();

            $sidebarData[] = [
                'nama_guru'  => $g['nama_guru'],
                'id_guru'    => $g['id_guru'],
                'email_guru' => $g['email'],
                'materi'     => $materiByGuru
            ];
        }
        $data['diskusi'] = $sidebarData;

        $data['materi'] = $this->materiModel->groupBy('kode_materi')->get()->getResultArray();

        return view('admin/diskusi/list', $data);
    }

    public function getMessages($materiName)
    {
        $materiName = urldecode($materiName);
        $lastId = $this->request->getGet('last_id') ?? 0;

        // 1. Ambil pesan baru
        $messages = $this->chatMateriModel->where('materi', $materiName)
            ->where('id_chat_materi >', $lastId)
            ->orderBy('date_created', 'ASC')
            ->findAll();

        // 2. Ambil daftar partisipan unik untuk keperluan Autocomplete Tag Mention
        $participantsData = $this->chatMateriModel->select('nama')
            ->where('materi', $materiName)
            ->groupBy('nama')
            ->findAll();

        $participants = array_column($participantsData, 'nama');

        // 3. Cari pesan pertama belum terbaca
        $firstUnread = $this->chatMateriModel->where('materi', $materiName)
            ->where('status_notif', '0')
            ->orderBy('id_chat_materi', 'ASC')
            ->first();

        // 4. Update status terbaca
        try {
            $db = \Config\Database::connect();
            $builder = $db->table('chat_materi');
            $builder->where('materi', $materiName);
            $builder->where('status_notif', '0');

            if ($builder->countAllResults(false) > 0) {
                $builder->update(['status_notif' => '1']);
            }
        } catch (\Exception $e) {
            // Abaikan error "no data to update"
        }

        return $this->response->setJSON([
            'messages'        => $messages,
            'participants'    => $participants,
            'first_unread_id' => $firstUnread ? $firstUnread['id_chat_materi'] : null
        ]);
    }

    public function sendMessage()
    {
        if ($this->request->isAJAX()) {
            try {
                $kode_materi = $this->request->getPost('materi');
                $chat_text   = (string) $this->request->getPost('text');
                $email       = (string) $this->request->getPost('email_guru');
                $nama_guru   = (string) $this->request->getPost('nama_guru');

                $user = $this->siswaModel->asObject()->find(session('id'));

                $data = [
                    'materi'       => $kode_materi,
                    'nama'         => $nama_guru,
                    'gambar'       => $user->avatar ?? 'default.png',
                    'email'        => $email,
                    'text'         => htmlspecialchars($chat_text),
                    'date_created' => time()
                ];

                if ($this->chatMateriModel->save($data)) {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'token'  => csrf_hash()
                    ]);
                }
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => $e->getMessage(),
                    'token'   => csrf_hash()
                ])->setStatusCode(500);
            }
        }
    }

    public function updateMessage()
    {
        if ($this->request->isAJAX()) {
            try {
                $id_chat = $this->request->getPost('id_chat');
                $text    = (string) $this->request->getPost('text');

                // Verifikasi agar hanya pemilik pesan yang bisa mengedit
                $pesan = $this->chatMateriModel->where('id_chat_materi', $id_chat)->first();

                if ($pesan && $pesan['email']) {
                    $this->chatMateriModel->update($id_chat, [
                        'text' => htmlspecialchars($text)
                    ]);
                    
                    return $this->response->setJSON([
                        'status' => 'success', 
                        'token'  => csrf_hash()
                    ]);
                }

                return $this->response->setJSON([
                    'status' => 'error', 
                    'message' => 'Akses ditolak', 
                    'token' => csrf_hash()
                ])->setStatusCode(403);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'status' => 'error', 
                    'message' => $e->getMessage(), 
                    'token' => csrf_hash()
                ])->setStatusCode(500);
            }
        }
    }

    public function deleteMessage()
    {
        if ($this->request->isAJAX()) {
            try {
                $id_chat = $this->request->getPost('id_chat');
                $email   = session()->get('email');

                // Verifikasi kepemilikan pesan
                $pesan = $this->chatMateriModel->where('id_chat_materi', $id_chat)->first();

                if ($pesan && $pesan['email']) {
                    
                    // Validasi 5 Menit (300 Detik) Backend
                    $waktuSekarang = time();
                    $selisihWaktu = $waktuSekarang - $pesan['date_created'];

                    if ($selisihWaktu > 300) {
                        return $this->response->setJSON([
                            'status' => 'error', 
                            'message' => 'Pesan sudah melewati batas 5 menit dan tidak bisa dihapus.', 
                            'token' => csrf_hash()
                        ])->setStatusCode(400);
                    }

                    $this->chatMateriModel->where('id_chat_materi', $id_chat)->delete();
                    
                    return $this->response->setJSON([
                        'status' => 'success', 
                        'token'  => csrf_hash()
                    ]);
                }

                return $this->response->setJSON([
                    'status' => 'error', 
                    'message' => 'Akses ditolak', 
                    'token' => csrf_hash()
                ])->setStatusCode(403);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'status' => 'error', 
                    'message' => $e->getMessage(), 
                    'token' => csrf_hash()
                ])->setStatusCode(500);
            }
        }
    }
}

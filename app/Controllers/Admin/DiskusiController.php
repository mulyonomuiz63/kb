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
        $this->materiModel     = new \App\Models\MateriModel();
        $this->siswaModel      = new \App\Models\SiswaModel();
        $this->guruModel       = new \App\Models\GuruModel();
    }

    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'Diskusi Materi', 'url' => '#'],
        ];

        $db       = \Config\Database::connect();
        $dataGuru = $db->table('guru')->get()->getResultArray();
        $sidebarData = [];

        foreach ($dataGuru as $g) {
            $guruEmail = (string) ($g['email'] ?? '');
            $materiByGuru = $this->chatMateriModel
                ->select('
                    chat_materi.materi,
                    materi.id_materi,
                    materi.nama_materi,
                    materi.mapel,
                    materi.kelas,
                    mapel.nama_mapel,
                    kelas.nama_kelas,
                    MAX(chat_materi.date_created) as last_time,
                    MAX(chat_materi.id_chat_materi) as last_id,
                    COUNT(CASE WHEN chat_materi.status_notif = "0" AND chat_materi.email != ' . $db->escape($guruEmail) . ' THEN 1 END) as unread_count
                ')
                ->join('materi', 'chat_materi.materi = materi.kode_materi')
                ->join('mapel', 'mapel.id_mapel = materi.mapel', 'left')
                ->join('kelas', 'kelas.id_kelas = materi.kelas', 'left')
                ->where('materi.guru', $g['id_guru'])
                ->groupBy('chat_materi.materi')
                ->orderBy('unread_count', 'DESC')
                ->orderBy('last_time', 'DESC')
                ->findAll();

            foreach ($materiByGuru as &$mItem) {
                $lastMsg = $this->chatMateriModel
                    ->select('nama, text, date_created')
                    ->where('id_chat_materi', $mItem['last_id'])
                    ->first();
                $cleanPreview = $lastMsg ? trim(preg_replace('/@\[(.*?)\]/', '@$1', html_entity_decode($lastMsg['text'] ?? ''))) : '';
                $mItem['last_sender']  = $lastMsg['nama'] ?? '';
                $mItem['last_message'] = mb_strimwidth($cleanPreview, 0, 50, '...');
                $mItem['video_url']    = base_url('sw-admin/guru/lihat-materi/' . encrypt_url($mItem['mapel']) . '/' . encrypt_url($mItem['kelas']) . '/' . encrypt_url($g['id_guru']));
            }
            unset($mItem);

            $sidebarData[] = [
                'nama_guru'   => $g['nama_guru'],
                'id_guru'     => $g['id_guru'],
                'email_guru'  => $g['email'],
                'avatar_guru' => $g['avatar'] ?? 'default.jpg',
                'materi'      => $materiByGuru
            ];
        }
        $data['diskusi'] = $sidebarData;

        // Ambil semua materi beserta data Guru, Mapel, Kelas untuk modal "+ Mulai Diskusi"
        $materiAll = $db->table('materi')
            ->select('materi.id_materi, materi.kode_materi, materi.nama_materi, materi.mapel, materi.kelas, materi.guru, mapel.nama_mapel, kelas.nama_kelas, guru.nama_guru, guru.email as email_guru')
            ->join('mapel', 'mapel.id_mapel = materi.mapel', 'left')
            ->join('kelas', 'kelas.id_kelas = materi.kelas', 'left')
            ->join('guru', 'guru.id_guru = materi.guru', 'left')
            ->groupBy('materi.kode_materi')
            ->orderBy('materi.id_materi', 'DESC')
            ->get()
            ->getResultArray();

        foreach ($materiAll as &$ma) {
            $ma['video_url'] = base_url('sw-admin/guru/lihat-materi/' . encrypt_url($ma['mapel']) . '/' . encrypt_url($ma['kelas']) . '/' . encrypt_url($ma['guru']));
        }
        unset($ma);

        $data['materi'] = $materiAll;

        // Cek parameter ?materi=KODE_MATERI dari notifikasi
        $targetMateri = trim((string) $this->request->getGet('materi'));
        $targetMateriInfo = null;
        if ($targetMateri !== '') {
            $mRow = $db->table('materi')
                ->select('materi.id_materi, materi.kode_materi, materi.nama_materi, materi.mapel, materi.kelas, materi.guru, mapel.nama_mapel, kelas.nama_kelas, guru.nama_guru, guru.email as email_guru')
                ->join('mapel', 'mapel.id_mapel = materi.mapel', 'left')
                ->join('kelas', 'kelas.id_kelas = materi.kelas', 'left')
                ->join('guru', 'guru.id_guru = materi.guru', 'left')
                ->where('materi.kode_materi', $targetMateri)
                ->get()
                ->getRowArray();
            if ($mRow) {
                $mRow['video_url'] = base_url('sw-admin/guru/lihat-materi/' . encrypt_url($mRow['mapel']) . '/' . encrypt_url($mRow['kelas']) . '/' . encrypt_url($mRow['guru']));
                $targetMateriInfo = $mRow;
            }
        }
        $data['targetMateriInfo'] = $targetMateriInfo;

        return view('admin/diskusi/list', $data);
    }

    public function getMessages($materiName)
    {
        $materiName = urldecode($materiName);
        $lastId     = (int) ($this->request->getGet('last_id') ?? 0);
        $db         = \Config\Database::connect();

        $materiInfo = $db->table('materi')
            ->select('materi.id_materi, materi.kode_materi, materi.nama_materi, materi.mapel, materi.kelas, materi.guru, mapel.nama_mapel, kelas.nama_kelas, guru.nama_guru, guru.email as email_guru')
            ->join('mapel', 'mapel.id_mapel = materi.mapel', 'left')
            ->join('kelas', 'kelas.id_kelas = materi.kelas', 'left')
            ->join('guru', 'guru.id_guru = materi.guru', 'left')
            ->where('materi.kode_materi', $materiName)
            ->get()
            ->getRowArray();

        $videoUrl = '';
        if ($materiInfo) {
            $videoUrl = base_url('sw-admin/guru/lihat-materi/' . encrypt_url($materiInfo['mapel']) . '/' . encrypt_url($materiInfo['kelas']) . '/' . encrypt_url($materiInfo['guru']));
        }

        // 1. Ambil pesan baru
        $messages = $this->chatMateriModel->where('materi', $materiName)
            ->where('id_chat_materi >', $lastId)
            ->orderBy('id_chat_materi', 'ASC')
            ->findAll();

        // 2. Ambil daftar partisipan unik untuk keperluan Autocomplete Tag Mention
        $participantsData = $this->chatMateriModel->select('nama')
            ->where('materi', $materiName)
            ->groupBy('nama')
            ->findAll();

        $participants = [];
        if (!empty($materiInfo['nama_guru'])) {
            $participants[] = $materiInfo['nama_guru'];
        }
        foreach ($participantsData as $p) {
            if (!empty($p['nama']) && !in_array($p['nama'], $participants, true)) {
                $participants[] = $p['nama'];
            }
        }

        // 3. Cari pesan pertama belum terbaca
        $firstUnread = $this->chatMateriModel->where('materi', $materiName)
            ->where('status_notif', '0')
            ->orderBy('id_chat_materi', 'ASC')
            ->first();

        // 4. Update status terbaca
        try {
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
            'guru_email'      => $materiInfo['email_guru'] ?? '',
            'guru_nama'       => $materiInfo['nama_guru'] ?? '',
            'nama_mapel'      => $materiInfo['nama_mapel'] ?? '',
            'nama_kelas'      => $materiInfo['nama_kelas'] ?? '',
            'video_url'       => $videoUrl,
            'first_unread_id' => $firstUnread ? $firstUnread['id_chat_materi'] : null,
            'token'           => csrf_hash()
        ]);
    }

    public function sendMessage()
    {
        if ($this->request->isAJAX()) {
            try {
                $kode_materi = trim((string) $this->request->getPost('materi'));
                $chat_text   = trim((string) $this->request->getPost('text'));
                $email_guru  = trim((string) $this->request->getPost('email_guru'));
                $nama_guru   = trim((string) $this->request->getPost('nama_guru'));

                if ($kode_materi === '' || $chat_text === '') {
                    return $this->response->setJSON([
                        'status'  => 'error',
                        'message' => 'Pesan tidak boleh kosong.',
                        'token'   => csrf_hash()
                    ])->setStatusCode(400);
                }

                // Ambil data guru dari materi di database agar selalu akurat meskipun frontend kosong
                $db = \Config\Database::connect();
                $materiRow = $db->table('materi')
                    ->select('materi.guru, guru.nama_guru, guru.email as email_guru, guru.avatar')
                    ->join('guru', 'guru.id_guru = materi.guru', 'left')
                    ->where('materi.kode_materi', $kode_materi)
                    ->get()
                    ->getRowArray();

                $senderName  = $nama_guru !== '' ? $nama_guru : ($materiRow['nama_guru'] ?? (session()->get('nama') ?: 'Admin KelasBrevet'));
                $senderEmail = $email_guru !== '' ? $email_guru : ($materiRow['email_guru'] ?? (session()->get('email') ?: 'admin@kelasbrevet.com'));
                $avatar      = !empty($materiRow['avatar']) ? $materiRow['avatar'] : (session()->get('avatar') ?: 'default.jpg');

                $data = [
                    'materi'       => $kode_materi,
                    'nama'         => $senderName,
                    'gambar'       => $avatar,
                    'email'        => $senderEmail,
                    'text'         => htmlspecialchars($chat_text),
                    'status_notif' => '0',
                    'date_created' => time()
                ];

                if ($this->chatMateriModel->save($data)) {
                    send_diskusi_notif($kode_materi, '1', $senderEmail, $senderName, $chat_text);

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
                $text    = trim((string) $this->request->getPost('text'));

                $pesan = $this->chatMateriModel->where('id_chat_materi', $id_chat)->first();

                if ($pesan && (time() - $pesan['date_created']) <= 300) {
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
                    'message' => 'Pesan tidak dapat diedit (batas 5 menit telah habis).',
                    'token'   => csrf_hash()
                ])->setStatusCode(400);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => $e->getMessage(),
                    'token'   => csrf_hash()
                ])->setStatusCode(500);
            }
        }
    }

    public function deleteMessage()
    {
        if ($this->request->isAJAX()) {
            try {
                $id_chat = $this->request->getPost('id_chat');

                $pesan = $this->chatMateriModel->where('id_chat_materi', $id_chat)->first();

                if ($pesan) {
                    $selisihWaktu = time() - $pesan['date_created'];

                    if ($selisihWaktu > 300) {
                        return $this->response->setJSON([
                            'status'  => 'error',
                            'message' => 'Pesan sudah melewati batas 5 menit dan tidak bisa dihapus.',
                            'token'   => csrf_hash()
                        ])->setStatusCode(400);
                    }

                    $this->chatMateriModel->where('id_chat_materi', $id_chat)->delete();

                    return $this->response->setJSON([
                        'status' => 'success',
                        'token'  => csrf_hash()
                    ]);
                }

                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Pesan tidak ditemukan',
                    'token'   => csrf_hash()
                ])->setStatusCode(404);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => $e->getMessage(),
                    'token'   => csrf_hash()
                ])->setStatusCode(500);
            }
        }
    }
}

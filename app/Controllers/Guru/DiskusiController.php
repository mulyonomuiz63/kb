<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class DiskusiController extends BaseController
{
    protected $chatMateriModel;
    protected $materiModel;
    protected $guruModel;

    public function __construct()
    {
        $this->chatMateriModel = new \App\Models\ChatMateriModel();
        $this->materiModel     = new \App\Models\MateriModel();
        $this->guruModel       = new \App\Models\GuruModel();
    }

    protected function getActiveGuruId()
    {
        if (session()->get('role') == '1' && session()->get('idguru_diadmin')) {
            return (int) session()->get('idguru_diadmin');
        }
        return (int) session()->get('id');
    }

    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-guru')],
            ['title' => 'Diskusi Materi', 'url' => '#'],
        ];

        $idGuru   = $this->getActiveGuruId();
        $guruData = $this->guruModel->asObject()->find($idGuru);
        $myEmail  = $guruData->email ?? (string) session()->get('email');
        $myNama   = $guruData->nama_guru ?? (string) session()->get('nama');
        $db       = \Config\Database::connect();

        // 1. Ambil daftar ruang diskusi materi milik Pengajar ini
        $diskusiList = $this->chatMateriModel
            ->select('
                chat_materi.materi,
                materi.id_materi,
                materi.nama_materi,
                materi.mapel,
                materi.kelas,
                mapel.nama_mapel,
                kelas.nama_kelas,
                guru.nama_guru,
                guru.email as email_guru,
                MAX(chat_materi.date_created) as last_time,
                MAX(chat_materi.id_chat_materi) as last_id,
                COUNT(CASE WHEN chat_materi.status_notif = "0" AND chat_materi.email != ' . $db->escape($myEmail) . ' THEN 1 END) as unread_count
            ')
            ->join('materi', 'chat_materi.materi = materi.kode_materi')
            ->join('mapel', 'mapel.id_mapel = materi.mapel', 'left')
            ->join('kelas', 'kelas.id_kelas = materi.kelas', 'left')
            ->join('guru', 'guru.id_guru = materi.guru', 'left')
            ->where('materi.guru', $idGuru)
            ->groupBy('chat_materi.materi')
            ->orderBy('unread_count', 'DESC')
            ->orderBy('last_time', 'DESC')
            ->get()
            ->getResultArray();

        foreach ($diskusiList as &$d) {
            $lastMsg = $this->chatMateriModel
                ->select('nama, text, date_created')
                ->where('id_chat_materi', $d['last_id'])
                ->first();
            $cleanPreview = $lastMsg ? trim(preg_replace('/@\[(.*?)\]/', '@$1', html_entity_decode($lastMsg['text'] ?? ''))) : '';
            $d['last_sender']  = $lastMsg['nama'] ?? '';
            $d['last_message'] = mb_strimwidth($cleanPreview, 0, 55, '...');
            $d['video_url']    = base_url('sw-guru/materi/lihat-materi/' . encrypt_url($d['id_materi']) . '/' . encrypt_url($d['mapel']) . '/' . encrypt_url($d['kelas']));
        }
        unset($d);

        // 2. Ambil seluruh materi yang diampu Pengajar ini untuk modal "+ Mulai Diskusi"
        $materiList = $db->table('materi')
            ->select('materi.id_materi, materi.kode_materi, materi.nama_materi, materi.mapel, materi.kelas, mapel.nama_mapel, kelas.nama_kelas, guru.nama_guru, guru.email as email_guru')
            ->join('mapel', 'mapel.id_mapel = materi.mapel', 'left')
            ->join('kelas', 'kelas.id_kelas = materi.kelas', 'left')
            ->join('guru', 'guru.id_guru = materi.guru', 'left')
            ->where('materi.guru', $idGuru)
            ->groupBy('materi.kode_materi')
            ->orderBy('materi.id_materi', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($materiList as &$m) {
            $m['video_url'] = base_url('sw-guru/materi/lihat-materi/' . encrypt_url($m['id_materi']) . '/' . encrypt_url($m['mapel']) . '/' . encrypt_url($m['kelas']));
        }
        unset($m);

        // Cek parameter ?materi=KODE_MATERI dari notifikasi
        $targetMateri = trim((string) $this->request->getGet('materi'));
        $targetMateriInfo = null;
        if ($targetMateri !== '') {
            $mRow = $db->table('materi')
                ->select('materi.id_materi, materi.kode_materi, materi.nama_materi, materi.mapel, materi.kelas, mapel.nama_mapel, kelas.nama_kelas, guru.nama_guru, guru.email as email_guru')
                ->join('mapel', 'mapel.id_mapel = materi.mapel', 'left')
                ->join('kelas', 'kelas.id_kelas = materi.kelas', 'left')
                ->join('guru', 'guru.id_guru = materi.guru', 'left')
                ->where('materi.kode_materi', $targetMateri)
                ->get()
                ->getRowArray();
            if ($mRow) {
                $mRow['video_url'] = base_url('sw-guru/materi/lihat-materi/' . encrypt_url($mRow['id_materi']) . '/' . encrypt_url($mRow['mapel']) . '/' . encrypt_url($mRow['kelas']));
                $targetMateriInfo = $mRow;
            }
        }

        $data['diskusi']          = $diskusiList;
        $data['materi']           = $materiList;
        $data['guruEmail']        = $myEmail;
        $data['guruNama']         = $myNama;
        $data['targetMateriInfo'] = $targetMateriInfo;

        return view('guru/diskusi/list', $data);
    }

    public function getMessages($materiName)
    {
        $materiName = urldecode($materiName);
        $lastId     = (int) ($this->request->getGet('last_id') ?? 0);
        $idGuru     = $this->getActiveGuruId();
        $guruData   = $this->guruModel->asObject()->find($idGuru);
        $myEmail    = $guruData->email ?? (string) session()->get('email');
        $db         = \Config\Database::connect();

        $materiInfo = $db->table('materi')
            ->select('materi.id_materi, materi.kode_materi, materi.nama_materi, materi.mapel, materi.kelas, mapel.nama_mapel, kelas.nama_kelas, guru.nama_guru, guru.email as email_guru')
            ->join('mapel', 'mapel.id_mapel = materi.mapel', 'left')
            ->join('kelas', 'kelas.id_kelas = materi.kelas', 'left')
            ->join('guru', 'guru.id_guru = materi.guru', 'left')
            ->where('materi.kode_materi', $materiName)
            ->get()
            ->getRowArray();

        $videoUrl = '';
        if ($materiInfo) {
            $videoUrl = base_url('sw-guru/materi/lihat-materi/' . encrypt_url($materiInfo['id_materi']) . '/' . encrypt_url($materiInfo['mapel']) . '/' . encrypt_url($materiInfo['kelas']));
        }

        $messages = $this->chatMateriModel->where('materi', $materiName)
            ->where('id_chat_materi >', $lastId)
            ->orderBy('id_chat_materi', 'ASC')
            ->findAll();

        $firstUnread = $this->chatMateriModel->where('materi', $materiName)
            ->where('status_notif', '0')
            ->where('email !=', $myEmail)
            ->orderBy('id_chat_materi', 'ASC')
            ->first();

        $participantRows = $this->chatMateriModel->select('nama')
            ->where('materi', $materiName)
            ->groupBy('nama')
            ->findAll();
        $participants = [];
        foreach ($participantRows as $pr) {
            if (!empty($pr['nama']) && !in_array($pr['nama'], $participants, true)) {
                $participants[] = $pr['nama'];
            }
        }

        // Tandai pesan peserta sebagai telah dibaca oleh Pengajar
        try {
            $builder = $db->table('chat_materi');
            $builder->where('materi', $materiName);
            $builder->where('status_notif', '0');
            $builder->where('email !=', $myEmail);
            if ($builder->countAllResults(false) > 0) {
                $builder->update(['status_notif' => '1']);
            }
        } catch (\Exception $e) {
            // Abaikan error update
        }

        return $this->response->setJSON([
            'messages'        => $messages,
            'participants'    => $participants,
            'guru_email'      => $materiInfo['email_guru'] ?? $myEmail,
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

                if ($kode_materi === '' || $chat_text === '') {
                    return $this->response->setJSON([
                        'status'  => 'error',
                        'message' => 'Pesan tidak boleh kosong.',
                        'token'   => csrf_hash()
                    ])->setStatusCode(400);
                }

                $idGuru      = $this->getActiveGuruId();
                $guru        = $this->guruModel->asObject()->find($idGuru);
                $senderName  = $guru->nama_guru ?? (string) session()->get('nama');
                $senderEmail = $guru->email ?? (string) session()->get('email');
                $avatar      = !empty($guru->avatar) ? $guru->avatar : 'default.jpg';

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
                    send_diskusi_notif($kode_materi, '3', $senderEmail, $senderName, $chat_text);

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
                $idGuru  = $this->getActiveGuruId();
                $guru    = $this->guruModel->asObject()->find($idGuru);
                $myEmail = $guru->email ?? (string) session()->get('email');

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
                    'message' => 'Pesan tidak dapat diedit (batas waktu 5 menit telah habis atau bukan pesan Anda).',
                    'token'   => csrf_hash()
                ])->setStatusCode(400);
            } catch (\Exception $e) {
                return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'token' => csrf_hash()])->setStatusCode(500);
            }
        }
    }

    public function deleteMessage()
    {
        if ($this->request->isAJAX()) {
            try {
                $id_chat = $this->request->getPost('id_chat');
                $idGuru  = $this->getActiveGuruId();
                $guru    = $this->guruModel->asObject()->find($idGuru);
                $myEmail = $guru->email ?? (string) session()->get('email');

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
                    'message' => 'Pesan tidak dapat dihapus (batas waktu 5 menit telah habis).',
                    'token'   => csrf_hash()
                ])->setStatusCode(400);
            } catch (\Exception $e) {
                return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'token' => csrf_hash()])->setStatusCode(500);
            }
        }
    }
}

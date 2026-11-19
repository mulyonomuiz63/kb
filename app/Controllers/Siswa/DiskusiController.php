<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class DiskusiController extends BaseController
{
    protected $chatMateriModel;
    protected $materiModel;
    protected $siswaModel;
    protected $mapelSiswaModel;

    public function __construct()
    {
        $this->chatMateriModel = new \App\Models\ChatMateriModel();
        $this->materiModel     = new \App\Models\MateriModel();
        $this->siswaModel      = new \App\Models\SiswaModel();
        $this->mapelSiswaModel = new \App\Models\MapelSiswaModel();
    }

    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Diskusi Materi', 'url' => '#'],
        ];

        $userId  = (int) session()->get('id');
        $myEmail = (string) session()->get('email');
        $db      = \Config\Database::connect();

        // Ambil daftar mapel yang dimiliki siswa
        $mapelRows = $this->mapelSiswaModel
            ->select('idmapel')
            ->where('idsiswa', $userId)
            ->get()
            ->getResultArray();
        $mapelIds = array_values(array_unique(array_filter(array_column($mapelRows, 'idmapel'))));

        // Ambil daftar materi yang sudah pernah diakses di materi_siswa
        $materiSiswaRows = $db->table('materi_siswa')
            ->select('materi')
            ->where('siswa', $userId)
            ->get()
            ->getResultArray();
        $kodeMateriSiswa = array_values(array_unique(array_filter(array_column($materiSiswaRows, 'materi'))));

        // 1. Ambil seluruh Materi aktif milik kelas/mapel siswa untuk modal "Mulai Diskusi Baru"
        $materiBuilder = $db->table('materi')
            ->select('materi.id_materi, materi.kode_materi, materi.nama_materi, materi.mapel, materi.kelas, mapel.nama_mapel, kelas.nama_kelas, guru.nama_guru, guru.email as email_guru')
            ->join('mapel', 'mapel.id_mapel = materi.mapel', 'left')
            ->join('kelas', 'kelas.id_kelas = materi.kelas', 'left')
            ->join('guru', 'guru.id_guru = materi.guru', 'left')
            ->where('materi.status', '1');

        if (!empty($mapelIds) || !empty($kodeMateriSiswa)) {
            $materiBuilder->groupStart();
            if (!empty($mapelIds)) {
                $materiBuilder->whereIn('materi.mapel', $mapelIds);
            }
            if (!empty($kodeMateriSiswa)) {
                $materiBuilder->orWhereIn('materi.kode_materi', $kodeMateriSiswa);
            }
            $materiBuilder->groupEnd();
        } else {
            $materiBuilder->where('1=0');
        }

        $materiList = $materiBuilder
            ->groupBy('materi.kode_materi')
            ->orderBy('materi.id_materi', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($materiList as &$m) {
            $m['video_url'] = base_url('sw-siswa/materi/lihat-materi/' . encrypt_url($m['kode_materi']) . '/' . encrypt_url($m['mapel']) . '/' . encrypt_url($m['kelas']));
        }
        unset($m);

        $accessibleKodes = array_values(array_unique(array_filter(array_column($materiList, 'kode_materi'))));

        // 2. Ambil daftar ruang diskusi yang aktif untuk materi siswa (atau yang pernah diikuti siswa)
        $diskusiBuilder = $this->chatMateriModel
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
                COUNT(chat_materi.id_chat_materi) as total_messages,
                COUNT(CASE WHEN chat_materi.status_notif = "0" AND chat_materi.email != ' . $db->escape($myEmail) . ' THEN 1 END) as unread_count
            ')
            ->join('materi', 'chat_materi.materi = materi.kode_materi')
            ->join('mapel', 'mapel.id_mapel = materi.mapel', 'left')
            ->join('kelas', 'kelas.id_kelas = materi.kelas', 'left')
            ->join('guru', 'guru.id_guru = materi.guru', 'left');

        $diskusiBuilder->groupStart()
            ->whereIn('chat_materi.materi', function ($builder) use ($myEmail) {
                return $builder->select('materi')->from('chat_materi')->where('email', $myEmail);
            });
        if (!empty($accessibleKodes)) {
            $diskusiBuilder->orWhereIn('chat_materi.materi', $accessibleKodes);
        }
        $diskusiBuilder->groupEnd();

        $diskusiList = $diskusiBuilder
            ->groupBy('chat_materi.materi')
            ->orderBy('unread_count', 'DESC')
            ->orderBy('last_time', 'DESC')
            ->get()
            ->getResultArray();

        // Tambahkan preview pesan terakhir & URL video materi
        foreach ($diskusiList as &$d) {
            $lastMsg = $this->chatMateriModel
                ->select('nama, text, date_created')
                ->where('id_chat_materi', $d['last_id'])
                ->first();
            $cleanPreview = $lastMsg ? trim(preg_replace('/@\[(.*?)\]/', '@$1', html_entity_decode($lastMsg['text'] ?? ''))) : '';
            $d['last_sender']  = $lastMsg['nama'] ?? '';
            $d['last_message'] = mb_strimwidth($cleanPreview, 0, 55, '...');
            $d['video_url']    = base_url('sw-siswa/materi/lihat-materi/' . encrypt_url($d['materi']) . '/' . encrypt_url($d['mapel']) . '/' . encrypt_url($d['kelas']));
        }
        unset($d);

        // Cek apakah ada request auto-open ?materi=KODE_MATERI (misal dari klik notifikasi)
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
                $mRow['video_url'] = base_url('sw-siswa/materi/lihat-materi/' . encrypt_url($mRow['kode_materi']) . '/' . encrypt_url($mRow['mapel']) . '/' . encrypt_url($mRow['kelas']));
                $targetMateriInfo = $mRow;
            }
        }

        $data['diskusi']          = $diskusiList;
        $data['materi']           = $materiList;
        $data['targetMateriInfo'] = $targetMateriInfo;

        return view('siswa/diskusi/list', $data);
    }

    public function getMessages($materiName)
    {
        $materiName = urldecode($materiName);
        $lastId     = (int) ($this->request->getGet('last_id') ?? 0);
        $myEmail    = (string) session()->get('email');
        $db         = \Config\Database::connect();

        // Info materi & instruktur
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
            $videoUrl = base_url('sw-siswa/materi/lihat-materi/' . encrypt_url($materiInfo['kode_materi']) . '/' . encrypt_url($materiInfo['mapel']) . '/' . encrypt_url($materiInfo['kelas']));
        }

        // 1. Ambil pesan baru
        $messages = $this->chatMateriModel->where('materi', $materiName)
            ->where('id_chat_materi >', $lastId)
            ->orderBy('id_chat_materi', 'ASC')
            ->findAll();

        // 2. Cari pesan pertama belum terbaca
        $firstUnread = $this->chatMateriModel->where('materi', $materiName)
            ->where('status_notif', '0')
            ->where('email !=', $myEmail)
            ->orderBy('id_chat_materi', 'ASC')
            ->first();

        // 3. Ambil daftar partisipan untuk fitur @mention
        $participantRows = $this->chatMateriModel->select('nama')
            ->where('materi', $materiName)
            ->groupBy('nama')
            ->findAll();
        $participants = [];
        if (!empty($materiInfo['nama_guru'])) {
            $participants[] = $materiInfo['nama_guru'];
        }
        foreach ($participantRows as $pr) {
            $pName = $pr['nama'] ?? '';
            if ($pName && !in_array($pName, $participants, true)) {
                $participants[] = $pName;
            }
        }

        // 4. Update status baca jika pesan dari Pengajar
        try {
            if (!empty($materiInfo['email_guru'])) {
                $builder = $db->table('chat_materi');
                $builder->where('materi', $materiName);
                $builder->where('status_notif', '0');
                $builder->where('email', $materiInfo['email_guru']);
                if ($builder->countAllResults(false) > 0) {
                    $builder->update(['status_notif' => '1']);
                }
            }
        } catch (\Exception $e) {
            // Abaikan error update
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

                if ($kode_materi === '' || $chat_text === '') {
                    return $this->response->setJSON([
                        'status'  => 'error',
                        'message' => 'Pesan tidak boleh kosong.',
                        'token'   => csrf_hash()
                    ])->setStatusCode(400);
                }

                $user        = $this->siswaModel->asObject()->find(session('id'));
                $senderName  = (string) (session()->get('nama') ?: ($user->nama_siswa ?? 'Peserta'));
                $senderEmail = (string) (session()->get('email') ?: ($user->email ?? ''));
                $avatar      = !empty($user->avatar) ? $user->avatar : 'default.jpg';

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
                    send_diskusi_notif($kode_materi, '2', $senderEmail, $senderName, $chat_text);

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
                $id    = $this->request->getPost('id_chat');
                $text  = trim((string) $this->request->getPost('text'));
                $email = session()->get('email');

                $msg = $this->chatMateriModel->find($id);

                if ($msg && $msg['email'] == $email && (time() - $msg['date_created']) <= 300) {
                    $this->chatMateriModel->update($id, ['text' => htmlspecialchars($text)]);
                    return $this->response->setJSON([
                        'status' => 'success',
                        'token'  => csrf_hash()
                    ]);
                }

                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Pesan tidak dapat diedit (waktu 5 menit telah habis atau bukan pesan Anda).',
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
                $id    = $this->request->getPost('id_chat');
                $email = session()->get('email');

                $msg = $this->chatMateriModel->find($id);

                if ($msg && $msg['email'] == $email && (time() - $msg['date_created']) <= 300) {
                    $this->chatMateriModel->delete($id);
                    return $this->response->setJSON([
                        'status' => 'success',
                        'token'  => csrf_hash()
                    ]);
                }

                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Pesan tidak dapat dihapus (waktu 5 menit telah habis).',
                    'token'   => csrf_hash()
                ])->setStatusCode(400);

            } catch (\Exception $e) {
                return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'token' => csrf_hash()])->setStatusCode(500);
            }
        }
    }
}

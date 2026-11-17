<?php

namespace App\Controllers;

use App\Models\ChatMemberModel;
use App\Models\ChatMessageModel;
use App\Models\ChatRoomModel;

class ChatController extends BaseController
{
    protected $roomModel;
    protected $memberModel;
    protected $messageModel;

    public function __construct()
    {
        $this->roomModel    = new ChatRoomModel();
        $this->memberModel  = new ChatMemberModel();
        $this->messageModel = new ChatMessageModel();
        helper(['setting', 'filesystem']);
    }

    /**
     * Dapatkan info user aktif dari sesi
     */
    protected function getAuthUser(bool $releaseLock = true): ?array
    {
        $id = session()->get('id');
        if (!$id) {
            if ($releaseLock) {
                session()->close();
            }
            return null;
        }

        $rawRole = session()->get('role');
        $name    = session()->get('nama') ?? 'User';
        $avatar  = session()->get('avatar') ?? '';

        if ($releaseLock) {
            session()->close();
        }

        // Normalisasi role di KelasBrevet:
        // 1 => admin
        // 2 => siswa (peserta)
        // 3 => guru (instruktur/pengajar)
        // 4 => mitra
        // 5 => pic
        $roleStr = 'siswa';
        if ($rawRole == 1 || $rawRole === 'admin') {
            $roleStr = 'admin';
        } elseif ($rawRole == 3 || $rawRole === 'guru') {
            $roleStr = 'guru';
        } elseif ($rawRole == 4 || $rawRole === 'mitra') {
            $roleStr = 'mitra';
        } elseif ($rawRole == 5 || $rawRole === 'pic') {
            $roleStr = 'pic';
        } elseif ($rawRole == 2 || $rawRole === 'siswa') {
            $roleStr = 'siswa';
        }

        return [
            'id'     => (int) $id,
            'role'   => $roleStr,
            'name'   => $name,
            'avatar' => $avatar,
        ];
    }

    /**
     * Cek apakah fitur chat diaktifkan di pengaturan
     */
    protected function isChatEnabled(): bool
    {
        $status = setting('chat_status', 'true');
        return in_array(strtolower((string)$status), ['true', '1', 'yes', 'on']);
    }

    /**
     * Cek apakah Pengajar / Instruktur (Guru) ditampilkan di fitur chat (Default: false / nonaktif)
     */
    protected function isGuruChatEnabled(): bool
    {
        $status = setting('chat_guru_status', 'false');
        return in_array(strtolower((string)$status), ['true', '1', 'yes', 'on']);
    }

    /**
     * Halaman Utama Chat (Bisa dibuka oleh Admin maupun Peserta)
     */
    public function index()
    {
        $user = $this->getAuthUser(false);
        if (!$user) {
            return redirect()->to('auth');
        }

        // Jika bukan admin dan chat dinonaktifkan, tolak
        if ($user['role'] !== 'admin' && !$this->isChatEnabled()) {
            return redirect()->to('/')->with('error', 'Fitur chat sedang dinonaktifkan oleh administrator.');
        }

        $data = [
            'currentUser'    => $user,
            'isAdmin'        => ($user['role'] === 'admin'),
            'chatStatus'     => $this->isChatEnabled(),
            'guruChatStatus' => $this->isGuruChatEnabled(),
            'title'          => 'Live Chat & Diskusi - KelasBrevet',
        ];

        return view('chat/index', $data);
    }

    /**
     * API: Ambil status chat dan unread count untuk floating widget
     */
    public function getStatus()
    {
        $user = $this->getAuthUser();
        if (!$user) {
            return $this->response->setJSON([
                'enabled'     => false,
                'unreadTotal' => 0,
            ]);
        }

        $enabled = $this->isChatEnabled() || ($user['role'] === 'admin');

        $unreadTotal = 0;
        if ($enabled) {
            $db = \Config\Database::connect();
            // Hitung pesan yang masuk di room user setelah last_read_message_id
            $rooms = $db->table('chat_members')
                ->where('user_role', $user['role'])
                ->where('user_id', $user['id'])
                ->get()->getResult();

            foreach ($rooms as $r) {
                $lastRead = (int) $r->last_read_message_id;
                $count = $db->table('chat_messages')
                    ->where('room_id', $r->room_id)
                    ->where('id >', $lastRead)
                    ->where('is_deleted', 0)
                    ->where('NOT (sender_role = ' . $db->escape($user['role']) . ' AND sender_id = ' . (int)$user['id'] . ')')
                    ->countAllResults();
                $unreadTotal += $count;
            }
        }

        return $this->response->setJSON([
            'enabled'     => $enabled,
            'unreadTotal' => $unreadTotal,
            'user'        => $user,
        ]);
    }

    /**
     * API: Toggle status fitur chat (Hanya Admin)
     */
    public function toggleStatus()
    {
        $user = $this->getAuthUser();
        if (!$user || $user['role'] !== 'admin') {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Hanya admin yang dapat mengubah pengaturan ini.']);
        }

        $settingModel = new \App\Models\SettingModel();
        $current = $this->isChatEnabled();
        $newVal  = $current ? 'false' : 'true';
        $settingModel->saveSetting('chat_status', $newVal);

        return $this->response->setJSON([
            'status'  => 'success',
            'enabled' => ($newVal === 'true'),
            'message' => 'Status fitur chat berhasil diubah menjadi: ' . ($newVal === 'true' ? 'AKTIF' : 'NONAKTIF'),
        ]);
    }

    /**
     * API: Toggle status tampilkan Pengajar/Guru di chat (Hanya Admin)
     */
    public function toggleGuruStatus()
    {
        $user = $this->getAuthUser();
        if (!$user || $user['role'] !== 'admin') {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Hanya admin yang dapat mengubah pengaturan ini.']);
        }

        $settingModel = new \App\Models\SettingModel();
        $current = $this->isGuruChatEnabled();
        $newVal  = $current ? 'false' : 'true';
        $settingModel->saveSetting('chat_guru_status', $newVal);

        return $this->response->setJSON([
            'status'  => 'success',
            'enabled' => ($newVal === 'true'),
            'message' => 'Status Pengajar/Instruktur di Chat diubah menjadi: ' . ($newVal === 'true' ? 'AKTIF (Ditampilkan)' : 'NONAKTIF (Disembunyikan)'),
        ]);
    }

    /**
     * API: Dapatkan daftar rooms/percakapan user (atau semua room jika admin mode monitor)
     */
    public function getRooms()
    {
        $user = $this->getAuthUser();
        if (!$user) {
            return $this->response->setStatusCode(401)->setJSON([]);
        }

        $mode = $this->request->getGet('mode'); // 'all' khusus admin monitor
        $db = \Config\Database::connect();

        $query = $db->table('chat_rooms r')
            ->select('r.*, m.last_read_message_id, m.role_in_room');

        if ($user['role'] === 'admin' && $mode === 'all') {
            // Admin memonitor seluruh room
            $query->join('chat_members m', 'm.room_id = r.id AND m.user_role = "admin" AND m.user_id = ' . (int)$user['id'], 'left');
        } else {
            // Room tempat user menjadi anggota
            $query->join('chat_members m', 'm.room_id = r.id')
                ->where('m.user_role', $user['role'])
                ->where('m.user_id', $user['id']);
        }

        $query->orderBy('r.last_message_at', 'DESC');
        $rooms = $query->get()->getResultArray();

        $formatted = [];
        foreach ($rooms as $room) {
            $roomId = (int) $room['id'];
            $roomName = $room['name'];
            $roomAvatar = $room['avatar'] ? base_url($room['avatar']) : '';

            // Jika direct chat, cari lawan bicara
            if ($room['type'] === 'direct') {
                $otherMember = $db->table('chat_members')
                    ->where('room_id', $roomId)
                    ->where('NOT (user_role = ' . $db->escape($user['role']) . ' AND user_id = ' . (int)$user['id'] . ')')
                    ->limit(1)
                    ->get()->getRow();

                if ($otherMember) {
                    $otherInfo = $this->resolveUserInfo($db, $otherMember->user_role, (int)$otherMember->user_id);
                    $roomName   = $otherInfo['name'];
                    $roomAvatar = $otherInfo['avatar'];
                } else {
                    $roomName = 'Direct Chat';
                }
            } else {
                // Group chat: jika avatar kosong, berikan default group icon
                if (empty($roomAvatar)) {
                    $roomAvatar = base_url('assets-landing/images/logo.png');
                }
            }

            // Hitung unread count
            $lastRead = (int) ($room['last_read_message_id'] ?? 0);
            $unreadCount = $db->table('chat_messages')
                ->where('room_id', $roomId)
                ->where('id >', $lastRead)
                ->where('is_deleted', 0)
                ->where('NOT (sender_role = ' . $db->escape($user['role']) . ' AND sender_id = ' . (int)$user['id'] . ')')
                ->countAllResults();

            // Total members dalam room
            $totalMembers = $db->table('chat_members')->where('room_id', $roomId)->countAllResults();

            $formatted[] = [
                'id'              => $roomId,
                'type'            => $room['type'],
                'name'            => $roomName,
                'avatar'          => $roomAvatar,
                'last_message'    => $room['last_message'] ?: 'Belum ada pesan',
                'last_message_at' => $room['last_message_at'] ? date('H:i', strtotime($room['last_message_at'])) : '',
                'unread_count'    => $unreadCount,
                'total_members'   => $totalMembers,
                'is_member'       => !empty($room['role_in_room']),
            ];
        }

        return $this->response->setJSON($formatted);
    }

    /**
     * API: Cari / filter kontak peserta, admin, guru, pic, mitra untuk mulai chat baru
     */
    public function searchContacts()
    {
        $user = $this->getAuthUser();
        if (!$user) {
            return $this->response->setStatusCode(401)->setJSON([]);
        }

        $q          = trim((string) $this->request->getGet('q'));
        $roleFilter = trim((string) $this->request->getGet('role_filter'));
        $isAdmin    = ($user['role'] === 'admin');
        $db         = \Config\Database::connect();
        $results    = [];

        // 1. Cari Admin (selalu bisa dihubungi semua role)
        if ($roleFilter === '' || $roleFilter === 'all' || $roleFilter === 'admin') {
            $adminBuilder = $db->table('admin')->select('id_admin as id, nama_admin as name, email, avatar, "admin" as role');
            if ($q !== '') {
                $adminBuilder->groupStart()
                    ->like('nama_admin', $q)
                    ->orLike('email', $q)
                    ->groupEnd();
            }
            $admins = $adminBuilder->limit(10)->get()->getResultArray();
            foreach ($admins as $a) {
                if ($user['role'] === 'admin' && (int)$a['id'] === $user['id']) {
                    continue;
                }
                $results[] = [
                    'id'       => (int) $a['id'],
                    'role'     => 'admin',
                    'name'     => $a['name'] . ' (Admin)',
                    'email'    => $a['email'],
                    'avatar'   => !empty($a['avatar']) ? base_url('assets/app-assets/user/' . $a['avatar']) : base_url('assets/app-assets/user/default.jpg'),
                    'subtitle' => 'Customer Care & Admin',
                ];
            }
        }

        // 2. Cari Guru / Pengajar (Selalu tampil untuk Admin, dan tampil untuk Peserta jika diaktifkan oleh Admin)
        if (($isAdmin || $this->isGuruChatEnabled()) && ($roleFilter === '' || $roleFilter === 'all' || $roleFilter === 'guru')) {
            $guruBuilder = $db->table('guru')->select('id_guru as id, nama_guru as name, email, avatar, "guru" as role')->where('is_active', 1);
            if ($q !== '') {
                $guruBuilder->groupStart()
                    ->like('nama_guru', $q)
                    ->orLike('email', $q)
                    ->groupEnd();
            }
            $gurus = $guruBuilder->limit(15)->get()->getResultArray();
            foreach ($gurus as $g) {
                if ($user['role'] === 'guru' && (int)$g['id'] === $user['id']) {
                    continue;
                }
                $results[] = [
                    'id'       => (int) $g['id'],
                    'role'     => 'guru',
                    'name'     => $g['name'] . ' (Pengajar)',
                    'email'    => $g['email'],
                    'avatar'   => !empty($g['avatar']) ? base_url('assets/app-assets/user/' . $g['avatar']) : base_url('assets/app-assets/user/default.jpg'),
                    'subtitle' => 'Instruktur / Pengajar',
                ];
            }
        }

        // 3. Cari PIC & Mitra (Khusus Admin bisa chat ke PIC, Mitra, dan semua user)
        if ($isAdmin && ($roleFilter === '' || $roleFilter === 'all' || $roleFilter === 'pic')) {
            $picBuilder = $db->table('pic')->select('idpic as id, nama_pic as name, email, avatar, "pic" as role')->where('is_active', 1);
            if ($q !== '') {
                $picBuilder->groupStart()
                    ->like('nama_pic', $q)
                    ->orLike('email', $q)
                    ->groupEnd();
            }
            $pics = $picBuilder->limit(15)->get()->getResultArray();
            foreach ($pics as $p) {
                $results[] = [
                    'id'       => (int) $p['id'],
                    'role'     => 'pic',
                    'name'     => $p['name'] . ' (PIC)',
                    'email'    => $p['email'],
                    'avatar'   => !empty($p['avatar']) ? base_url('assets/app-assets/user/' . $p['avatar']) : base_url('assets/app-assets/user/default.jpg'),
                    'subtitle' => 'PIC KelasBrevet',
                ];
            }
        }

        if ($isAdmin && ($roleFilter === '' || $roleFilter === 'all' || $roleFilter === 'mitra')) {
            $mitraBuilder = $db->table('mitra')->select('idmitra as id, nama_mitra as name, email, avatar, "mitra" as role')->where('is_active', 1);
            if ($q !== '') {
                $mitraBuilder->groupStart()
                    ->like('nama_mitra', $q)
                    ->orLike('email', $q)
                    ->groupEnd();
            }
            $mitras = $mitraBuilder->limit(15)->get()->getResultArray();
            foreach ($mitras as $m) {
                $results[] = [
                    'id'       => (int) $m['id'],
                    'role'     => 'mitra',
                    'name'     => $m['name'] . ' (Mitra)',
                    'email'    => $m['email'],
                    'avatar'   => !empty($m['avatar']) ? base_url('assets/app-assets/user/' . $m['avatar']) : base_url('assets/app-assets/user/default.jpg'),
                    'subtitle' => 'Mitra KelasBrevet',
                ];
            }
        }

        // 4. Cari Siswa / Peserta
        if ($roleFilter === '' || $roleFilter === 'all' || $roleFilter === 'siswa') {
            $siswaBuilder = $db->table('siswa')->select('id_siswa as id, nama_siswa as name, email, avatar, no_induk_siswa, "siswa" as role')->where('is_active', 1);
            if ($q !== '') {
                $siswaBuilder->groupStart()
                    ->like('nama_siswa', $q)
                    ->orLike('email', $q)
                    ->orLike('no_induk_siswa', $q)
                    ->groupEnd();
            }
            $siswas = $siswaBuilder->limit(30)->get()->getResultArray();
            foreach ($siswas as $s) {
                if ($user['role'] === 'siswa' && (int)$s['id'] === $user['id']) {
                    continue;
                }
                $results[] = [
                    'id'       => (int) $s['id'],
                    'role'     => 'siswa',
                    'name'     => $s['name'],
                    'email'    => $s['email'],
                    'avatar'   => !empty($s['avatar']) ? base_url('assets/app-assets/user/' . $s['avatar']) : base_url('assets/app-assets/user/default.jpg'),
                    'subtitle' => 'Peserta ' . ($s['no_induk_siswa'] ? '- ' . $s['no_induk_siswa'] : ''),
                ];
            }
        }

        return $this->response->setJSON($results);
    }

    /**
     * API: Buka atau buat direct chat dengan target user
     */
    public function openDirectChat()
    {
        $user = $this->getAuthUser();
        if (!$user) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error']);
        }

        $targetRole = trim((string)$this->request->getPost('target_role'));
        $targetId   = (int) $this->request->getPost('target_id');

        if (empty($targetRole) || $targetId <= 0) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Target user tidak valid.']);
        }

        try {
            $roomId = $this->roomModel->getOrCreateDirectRoom($user['role'], $user['id'], $targetRole, $targetId);

            return $this->response->setJSON([
                'status'  => 'success',
                'room_id' => $roomId,
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Error openDirectChat: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Gagal membuka ruang obrolan: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * API: Buat group chat baru
     */
    public function createGroup()
    {
        $user = $this->getAuthUser();
        if (!$user) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error']);
        }

        $name       = trim((string)$this->request->getPost('name'));
        $membersRaw = $this->request->getPost('members'); // json string or array: [ {role: "siswa", id: 1}, ... ]

        if (empty($name)) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Nama grup tidak boleh kosong.']);
        }

        $members = is_array($membersRaw) ? $membersRaw : json_decode((string)$membersRaw, true);
        if (empty($members) || !is_array($members)) {
            $members = [];
        }

        $now = date('Y-m-d H:i:s');
        $roomId = $this->roomModel->insert([
            'type'            => 'group',
            'name'            => $name,
            'created_by_role' => $user['role'],
            'created_by_id'   => $user['id'],
            'last_message'    => 'Grup dibuat oleh ' . $user['name'],
            'last_message_at' => $now,
            'created_at'      => $now,
            'updated_at'      => $now,
        ], true);

        $db = \Config\Database::connect();
        // Masukkan pembuat grup sebagai admin
        $memberRows = [
            [
                'room_id'              => $roomId,
                'user_role'            => $user['role'],
                'user_id'              => $user['id'],
                'role_in_room'         => 'admin',
                'last_read_message_id' => 0,
                'joined_at'            => $now,
            ],
        ];

        // Pastikan admin utama selalu masuk atau terdaftar jika dibuat oleh peserta
        $adminIncluded = false;
        if ($user['role'] === 'admin') {
            $adminIncluded = true;
        }

        foreach ($members as $m) {
            $mRole = $m['role'] ?? 'siswa';
            $mId   = (int) ($m['id'] ?? 0);
            if ($mId <= 0) continue;
            if ($mRole === $user['role'] && $mId === $user['id']) continue; // jangan duplikat

            if ($mRole === 'admin') {
                $adminIncluded = true;
            }

            $memberRows[] = [
                'room_id'              => $roomId,
                'user_role'            => $mRole,
                'user_id'              => $mId,
                'role_in_room'         => 'member',
                'last_read_message_id' => 0,
                'joined_at'            => $now,
            ];
        }

        // Jika grup dibuat oleh peserta dan belum ada admin, tambahkan 1 admin aktif agar admin bisa memonitor
        if (!$adminIncluded) {
            $firstAdmin = $db->table('admin')->select('id_admin')->where('is_active', 1)->orderBy('id_admin', 'ASC')->limit(1)->get()->getRow();
            if ($firstAdmin) {
                $memberRows[] = [
                    'room_id'              => $roomId,
                    'user_role'            => 'admin',
                    'user_id'              => (int) $firstAdmin->id_admin,
                    'role_in_room'         => 'member',
                    'last_read_message_id' => 0,
                    'joined_at'            => $now,
                ];
            }
        }

        $db->table('chat_members')->insertBatch($memberRows);

        // Buat pesan sistem pertama
        $this->messageModel->insert([
            'room_id'     => $roomId,
            'sender_role' => $user['role'],
            'sender_id'   => $user['id'],
            'message'     => '🎉 ' . $user['name'] . ' membuat grup "' . $name . '"',
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'room_id' => $roomId,
            'message' => 'Grup berhasil dibuat.',
        ]);
    }

    /**
     * API: Ambil pesan-pesan dalam room
     */
    public function getMessages(int $roomId)
    {
        $user = $this->getAuthUser();
        if (!$user) {
            return $this->response->setStatusCode(401)->setJSON([]);
        }

        $db = \Config\Database::connect();
        // Cek keanggotaan (kecuali admin)
        $member = $db->table('chat_members')
            ->where('room_id', $roomId)
            ->where('user_role', $user['role'])
            ->where('user_id', $user['id'])
            ->get()->getRow();

        if (!$member && $user['role'] !== 'admin') {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Anda bukan anggota percakapan ini.']);
        }

        $lastId = (int) $this->request->getGet('last_id');
        $messages = $this->messageModel->getMessagesForRoom($roomId, $lastId);

        // Update last_read_message_id jika ada pesan baru
        if (!empty($messages) && $member) {
            $latestMsgId = end($messages)['id'];
            if ($latestMsgId > (int)$member->last_read_message_id) {
                $db->table('chat_members')
                    ->where('id', $member->id)
                    ->update(['last_read_message_id' => $latestMsgId]);
            }
        }

        return $this->response->setJSON([
            'status'   => 'success',
            'messages' => $messages,
        ]);
    }

    /**
     * API: Kirim pesan teks, file gambar (kompresi), reply, link meta
     */
    public function sendMessage()
    {
        $user = $this->getAuthUser();
        if (!$user) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error']);
        }

        // Cek setting chat aktif
        if ($user['role'] !== 'admin' && !$this->isChatEnabled()) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Fitur chat sedang nonaktif.']);
        }

        $roomId   = (int) $this->request->getPost('room_id');
        $message  = trim((string)$this->request->getPost('message'));
        $replyTo  = (int) $this->request->getPost('reply_to_id');
        $file     = $this->request->getFile('file');

        if ($roomId <= 0) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Room ID tidak valid.']);
        }

        $db = \Config\Database::connect();
        // Cek apakah user ada dalam room, jika admin belum ada, otomatis gabungkan
        $member = $db->table('chat_members')
            ->where('room_id', $roomId)
            ->where('user_role', $user['role'])
            ->where('user_id', $user['id'])
            ->get()->getRow();

        if (!$member) {
            if ($user['role'] === 'admin') {
                $db->table('chat_members')->insert([
                    'room_id'              => $roomId,
                    'user_role'            => 'admin',
                    'user_id'              => $user['id'],
                    'role_in_room'         => 'admin',
                    'last_read_message_id' => 0,
                    'joined_at'            => date('Y-m-d H:i:s'),
                ]);
            } else {
                return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Anda bukan anggota room ini.']);
            }
        }

        if (empty($message) && (!$file || !$file->isValid())) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Pesan atau file tidak boleh kosong.']);
        }

        // Handle File Attachment dengan Kompresi Gambar
        $filePath = null;
        $fileName = null;
        $fileType = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $mimeType = $file->getMimeType();
            $origName = $file->getClientName();
            $ext      = $file->guessExtension() ?: 'dat';

            $uploadDir = FCPATH . 'uploads/chat/' . date('Ym') . '/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $newName = bin2hex(random_bytes(10)) . '.' . $ext;

            // Jika gambar, lakukan resize & kompresi (max width 1200px, quality 75)
            if (str_starts_with($mimeType, 'image/')) {
                try {
                    $imageService = \Config\Services::image();
                    $file->move($uploadDir, $newName);
                    $fullPath = $uploadDir . $newName;

                    // Resize jika terlalu besar
                    $imageService->withFile($fullPath)
                        ->resize(1200, 1200, true, 'width')
                        ->save($fullPath, 75);

                    $filePath = 'uploads/chat/' . date('Ym') . '/' . $newName;
                    $fileType = 'image';
                    $fileName = $origName;
                } catch (\Throwable $e) {
                    $file->move($uploadDir, $newName);
                    $filePath = 'uploads/chat/' . date('Ym') . '/' . $newName;
                    $fileType = 'image';
                    $fileName = $origName;
                }
            } else {
                $file->move($uploadDir, $newName);
                $filePath = 'uploads/chat/' . date('Ym') . '/' . $newName;
                $fileType = 'file';
                $fileName = $origName;
            }
        }

        // Ekstraksi Link Preview Meta Tag jika ada URL di pesan
        $linkPreview = null;
        if (!empty($message)) {
            $extractedMeta = $this->extractUrlMetadata($message);
            if ($extractedMeta) {
                $linkPreview = json_encode($extractedMeta);
            }
        }

        $now = date('Y-m-d H:i:s');
        $msgId = $this->messageModel->insert([
            'room_id'         => $roomId,
            'sender_role'     => $user['role'],
            'sender_id'       => $user['id'],
            'reply_to_id'     => $replyTo > 0 ? $replyTo : null,
            'message'         => $message ?: null,
            'file_attachment' => $filePath,
            'file_name'       => $fileName,
            'file_type'       => $fileType,
            'link_preview'    => $linkPreview,
            'created_at'      => $now,
            'updated_at'      => $now,
        ], true);

        // Update ringkasan room
        $lastPreview = !empty($message) ? $message : ($fileName ? '📎 ' . $fileName : 'Pesan');
        $this->roomModel->update($roomId, [
            'last_message'    => $lastPreview,
            'last_message_at' => $now,
        ]);

        // Tandai terbaca oleh pengirim
        $db->table('chat_members')
            ->where('room_id', $roomId)
            ->where('user_role', $user['role'])
            ->where('user_id', $user['id'])
            ->update(['last_read_message_id' => $msgId]);

        return $this->response->setJSON([
            'status'     => 'success',
            'message_id' => $msgId,
        ]);
    }

    /**
     * API: Edit pesan (hanya < 5 menit dan pemilik pesan)
     */
    public function editMessage()
    {
        $user = $this->getAuthUser();
        if (!$user) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error']);
        }

        $msgId      = (int) $this->request->getPost('message_id');
        $newMessage = trim((string)$this->request->getPost('message'));

        if ($msgId <= 0 || empty($newMessage)) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Data tidak valid.']);
        }

        $msg = $this->messageModel->find($msgId);
        if (!$msg) {
            return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'Pesan tidak ditemukan.']);
        }

        // Cek kepemilikan (admin bisa edit miliknya sendiri)
        if ($msg['sender_role'] !== $user['role'] || (int)$msg['sender_id'] !== $user['id']) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Anda hanya bisa mengedit pesan milik Anda sendiri.']);
        }

        // Cek batas waktu 5 menit (300 detik)
        $diff = time() - strtotime($msg['created_at']);
        if ($diff > 300) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Pesan sudah lebih dari 5 menit sehingga tidak dapat diedit lagi.']);
        }

        // Ekstraksi ulang meta jika ada link
        $linkPreview = null;
        $extractedMeta = $this->extractUrlMetadata($newMessage);
        if ($extractedMeta) {
            $linkPreview = json_encode($extractedMeta);
        }

        $this->messageModel->update($msgId, [
            'message'      => $newMessage,
            'link_preview' => $linkPreview,
            'is_edited'    => 1,
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        // Update preview last_message di room jika ini pesan terakhir
        $latestMsg = $this->messageModel->where('room_id', $msg['room_id'])->orderBy('id', 'DESC')->first();
        if ($latestMsg && (int)$latestMsg['id'] === $msgId) {
            $this->roomModel->update($msg['room_id'], [
                'last_message' => mb_strimwidth($newMessage, 0, 80, '...'),
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Pesan berhasil diperbarui.',
        ]);
    }

    /**
     * API: Tarik / Hapus pesan (hanya < 5 menit dan pemilik pesan, atau admin)
     */
    public function deleteMessage()
    {
        $user = $this->getAuthUser();
        if (!$user) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error']);
        }

        $msgId = (int) $this->request->getPost('message_id');
        $msg = $this->messageModel->find($msgId);
        if (!$msg) {
            return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'Pesan tidak ditemukan.']);
        }

        $isOwner = ($msg['sender_role'] === $user['role'] && (int)$msg['sender_id'] === $user['id']);
        $isAdmin = ($user['role'] === 'admin');

        if (!$isOwner && !$isAdmin) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Anda tidak memiliki hak untuk menarik pesan ini.']);
        }

        // Jika bukan admin, cek batas waktu 5 menit
        if (!$isAdmin) {
            $diff = time() - strtotime($msg['created_at']);
            if ($diff > 300) {
                return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Pesan sudah lebih dari 5 menit sehingga tidak dapat ditarik lagi.']);
            }
        }

        $this->messageModel->update($msgId, [
            'is_deleted'      => 1,
            'message'         => null,
            'file_attachment' => null,
            'link_preview'    => null,
            'updated_at'      => date('Y-m-d H:i:s'),
        ]);

        // Update preview last_message di room jika ini pesan terakhir
        $latestMsg = $this->messageModel->where('room_id', $msg['room_id'])->orderBy('id', 'DESC')->first();
        if ($latestMsg && (int)$latestMsg['id'] === $msgId) {
            $this->roomModel->update($msg['room_id'], [
                'last_message' => '🚫 Pesan telah ditarik',
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Pesan berhasil ditarik/dihapus.',
        ]);
    }

    /**
     * API: Dapatkan info detail room/grup dan anggotanya
     */
    public function getRoomDetails(int $roomId)
    {
        $user = $this->getAuthUser();
        if (!$user) {
            return $this->response->setStatusCode(401)->setJSON([]);
        }

        $room = $this->roomModel->find($roomId);
        if (!$room) {
            return $this->response->setStatusCode(404)->setJSON(['status' => 'error']);
        }

        $db = \Config\Database::connect();
        $members = $this->memberModel->getMembersWithUser($roomId);

        if ($room['type'] === 'direct') {
            $otherMember = $db->table('chat_members')
                ->where('room_id', $roomId)
                ->where('NOT (user_role = ' . $db->escape($user['role']) . ' AND user_id = ' . (int)$user['id'] . ')')
                ->limit(1)
                ->get()->getRow();

            if ($otherMember) {
                $otherInfo = $this->resolveUserInfo($db, $otherMember->user_role, (int)$otherMember->user_id);
                $room['name']   = $otherInfo['name'];
                $room['avatar'] = $otherInfo['avatar'];
                $roleMap = [
                    'admin' => 'Admin KelasBrevet',
                    'guru'  => 'Instruktur / Pengajar',
                    'pic'   => 'PIC KelasBrevet',
                    'mitra' => 'Mitra KelasBrevet',
                    'siswa' => 'Peserta',
                ];
                $roleLabel = $roleMap[$otherMember->user_role] ?? 'Peserta';
                $room['subtitle'] = $roleLabel . ' • Personal Chat';
            } else {
                $names = array_map(fn($m) => $m['user_name'], $members);
                $room['name']     = !empty($names) ? implode(' & ', $names) : 'Personal Chat';
                $room['avatar']   = $members[0]['user_avatar'] ?? base_url('assets/app-assets/user/default.jpg');
                $room['subtitle'] = 'Personal Chat';
            }
        } else {
            $room['avatar']   = !empty($room['avatar']) ? base_url($room['avatar']) : base_url('assets-landing/images/logo.png');
            $room['subtitle'] = count($members) . ' Anggota Grup';
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'room'    => $room,
            'members' => $members,
        ]);
    }

    /**
     * API: Tambah anggota ke grup (hanya anggota/admin grup)
     */
    public function addGroupMember()
    {
        $user = $this->getAuthUser();
        if (!$user) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error']);
        }

        $roomId     = (int) $this->request->getPost('room_id');
        $targetRole = trim((string)$this->request->getPost('target_role'));
        $targetId   = (int) $this->request->getPost('target_id');

        if ($roomId <= 0 || empty($targetRole) || $targetId <= 0) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Parameter tidak valid.']);
        }

        $room = $this->roomModel->find($roomId);
        if (!$room || $room['type'] !== 'group') {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Hanya berlaku untuk grup chat.']);
        }

        $db = \Config\Database::connect();
        $exists = $db->table('chat_members')
            ->where('room_id', $roomId)
            ->where('user_role', $targetRole)
            ->where('user_id', $targetId)
            ->countAllResults();

        if ($exists > 0) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Pengguna sudah menjadi anggota grup ini.']);
        }

        $db->table('chat_members')->insert([
            'room_id'              => $roomId,
            'user_role'            => $targetRole,
            'user_id'              => $targetId,
            'role_in_room'         => 'member',
            'last_read_message_id' => 0,
            'joined_at'            => date('Y-m-d H:i:s'),
        ]);

        $targetInfo = $this->resolveUserInfo($db, $targetRole, $targetId);

        // Notifikasi sistem dalam room
        $this->messageModel->insert([
            'room_id'     => $roomId,
            'sender_role' => $user['role'],
            'sender_id'   => $user['id'],
            'message'     => '👋 ' . $user['name'] . ' menambahkan ' . $targetInfo['name'] . ' ke dalam grup',
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Anggota berhasil ditambahkan ke grup.',
        ]);
    }

    /**
     * Helper: Scraping metadata OpenGraph / Meta Tag dari URL
     */
    private function extractUrlMetadata(string $text): ?array
    {
        // Cari URL pertama dalam teks
        $pattern = '/\bhttps?:\/\/[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|\/))/i';
        if (!preg_match($pattern, $text, $matches)) {
            return null;
        }

        $url = $matches[0];
        $parsed = parse_url($url);
        if (!$parsed || empty($parsed['host'])) {
            return null;
        }

        // Ambil isi HTML dengan cURL timeout singkat (2.5 detik) agar chat tetap cepat
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // Batasi download maksimal 150KB untuk meta tag
            curl_setopt($ch, CURLOPT_RANGE, '0-150000');
            $html = curl_exec($ch);
            curl_close($ch);

            if (!$html) {
                return [
                    'url'         => $url,
                    'title'       => $parsed['host'],
                    'description' => $url,
                    'image'       => '',
                ];
            }

            $title = '';
            $description = '';
            $image = '';

            // 1. Open Graph Tags
            if (preg_match('/<meta[^>]+property=["\']og:title["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $m)) {
                $title = html_entity_decode($m[1]);
            }
            if (preg_match('/<meta[^>]+property=["\']og:description["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $m)) {
                $description = html_entity_decode($m[1]);
            }
            if (preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $m)) {
                $image = $m[1];
            }

            // 2. Fallbacks
            if (empty($title) && preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m)) {
                $title = trim(html_entity_decode($m[1]));
            }
            if (empty($description) && preg_match('/<meta[^>]+name=["\']description["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $m)) {
                $description = html_entity_decode($m[1]);
            }

            if (empty($title)) {
                $title = $parsed['host'];
            }

            // Normalisasi URL gambar relatif
            if (!empty($image) && !str_starts_with($image, 'http')) {
                $image = rtrim($url, '/') . '/' . ltrim($image, '/');
            }

            return [
                'url'         => $url,
                'title'       => mb_strimwidth($title, 0, 90, '...'),
                'description' => mb_strimwidth($description, 0, 160, '...'),
                'image'       => $image,
            ];
        } catch (\Throwable $e) {
            return [
                'url'         => $url,
                'title'       => $parsed['host'],
                'description' => $url,
                'image'       => '',
            ];
        }
    }

    private function resolveUserInfo($db, string $role, int $id): array
    {
        $name   = 'User';
        $avatar = base_url('assets/app-assets/user/default.jpg');

        if ($role === 'admin') {
            $u = $db->table('admin')->where('id_admin', $id)->get()->getRow();
            if ($u) {
                $name   = $u->nama_admin ?? 'Admin KelasBrevet';
                $avatar = !empty($u->avatar) ? base_url('assets/app-assets/user/' . $u->avatar) : base_url('assets/app-assets/user/default.jpg');
            }
        } elseif ($role === 'siswa') {
            $u = $db->table('siswa')->where('id_siswa', $id)->get()->getRow();
            if ($u) {
                $name   = $u->nama_siswa ?? 'Peserta';
                $avatar = !empty($u->avatar) ? base_url('assets/app-assets/user/' . $u->avatar) : base_url('assets/app-assets/user/default.jpg');
            }
        } elseif ($role === 'guru') {
            $u = $db->table('guru')->where('id_guru', $id)->get()->getRow();
            if ($u) {
                $name   = $u->nama_guru ?? 'Pengajar';
                $avatar = !empty($u->avatar) ? base_url('assets/app-assets/user/' . $u->avatar) : base_url('assets/app-assets/user/default.jpg');
            }
        } elseif ($role === 'pic') {
            $u = $db->table('pic')->where('idpic', $id)->get()->getRow();
            if ($u) {
                $name   = $u->nama_pic ?? 'PIC';
                $avatar = !empty($u->avatar) ? base_url('assets/app-assets/user/' . $u->avatar) : base_url('assets/app-assets/user/default.jpg');
            }
        } elseif ($role === 'mitra') {
            $u = $db->table('mitra')->where('idmitra', $id)->get()->getRow();
            if ($u) {
                $name   = $u->nama_mitra ?? 'Mitra';
                $avatar = !empty($u->avatar) ? base_url('assets/app-assets/user/' . $u->avatar) : base_url('assets/app-assets/user/default.jpg');
            }
        }

        return ['name' => $name, 'avatar' => $avatar];
    }
}

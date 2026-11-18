<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatMessageModel extends Model
{
    protected $table            = 'chat_messages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'room_id',
        'sender_role',
        'sender_id',
        'reply_to_id',
        'message',
        'file_attachment',
        'file_name',
        'file_type',
        'link_preview',
        'reactions',
        'is_edited',
        'is_deleted',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    /**
     * Dapatkan pesan-pesan dalam room beserta data reply dan sender info
     */
    public function getMessagesForRoom(int $roomId, int $lastId = 0): array
    {
        $db = \Config\Database::connect();
        if (!$db->fieldExists('reactions', 'chat_messages')) {
            try {
                $db->query("ALTER TABLE `chat_messages` ADD COLUMN `reactions` TEXT NULL AFTER `link_preview`");
            } catch (\Throwable $e) {
            }
        }

        $builder = $this->where('room_id', $roomId);
        if ($lastId > 0) {
            $builder->where('id >', $lastId);
        }
        $builder->orderBy('id', 'ASC');
        $messages = $builder->findAll();

        if (empty($messages)) {
            return [];
        }

        // Kumpulkan sender & reply id untuk efisiensi
        $userCache = [];
        $replyIds = [];
        foreach ($messages as $m) {
            if (!empty($m['reply_to_id'])) {
                $replyIds[] = (int) $m['reply_to_id'];
            }
        }

        $replyMap = [];
        if (!empty($replyIds)) {
            $replies = $this->whereIn('id', array_unique($replyIds))->findAll();
            foreach ($replies as $r) {
                $replyMap[$r['id']] = $r;
            }
        }

        $formatted = [];
        foreach ($messages as $msg) {
            $senderKey = $msg['sender_role'] . '_' . $msg['sender_id'];
            if (!isset($userCache[$senderKey])) {
                $userCache[$senderKey] = $this->resolveUserInfo($db, $msg['sender_role'], (int) $msg['sender_id']);
            }
            $sender = $userCache[$senderKey];

            // Resolve reply
            $replyData = null;
            if (!empty($msg['reply_to_id']) && isset($replyMap[$msg['reply_to_id']])) {
                $r = $replyMap[$msg['reply_to_id']];
                $rSenderKey = $r['sender_role'] . '_' . $r['sender_id'];
                if (!isset($userCache[$rSenderKey])) {
                    $userCache[$rSenderKey] = $this->resolveUserInfo($db, $r['sender_role'], (int) $r['sender_id']);
                }
                $replyData = [
                    'id'          => $r['id'],
                    'sender_name' => $userCache[$rSenderKey]['name'],
                    'message'     => $r['is_deleted'] ? 'Pesan telah dihapus' : ($r['message'] ?? ($r['file_name'] ? '📎 ' . $r['file_name'] : '')),
                ];
            }

            // Parse link_preview JSON jika ada
            $linkPreview = null;
            if (!empty($msg['link_preview'])) {
                $linkPreview = json_decode($msg['link_preview'], true);
            }

            // Parse reactions JSON jika ada
            $reactions = [];
            if (!$msg['is_deleted'] && !empty($msg['reactions'])) {
                $decodedReactions = json_decode($msg['reactions'], true);
                if (is_array($decodedReactions)) {
                    $reactions = $decodedReactions;
                }
            }

            // Hitung apakah masih bisa di-edit/dihapus (< 5 menit)
            $createdTimestamp = strtotime($msg['created_at']);
            $canModify = (time() - $createdTimestamp) < 300; // 300 detik = 5 menit

            $formatted[] = [
                'id'                => (int) $msg['id'],
                'room_id'           => (int) $msg['room_id'],
                'sender_role'       => $msg['sender_role'],
                'sender_id'         => (int) $msg['sender_id'],
                'sender_name'       => $sender['name'],
                'sender_role_label' => $sender['role_label'],
                'sender_avatar'     => $sender['avatar'],
                'message'         => $msg['is_deleted'] ? '🚫 Pesan ini telah ditarik/dihapus' : $msg['message'],
                'file_attachment' => $msg['is_deleted'] ? null : ($msg['file_attachment'] ? base_url($msg['file_attachment']) : null),
                'file_name'       => $msg['file_name'],
                'file_type'       => $msg['file_type'],
                'link_preview'    => $msg['is_deleted'] ? null : $linkPreview,
                'reactions'       => $reactions,
                'reply_to'        => $replyData,
                'is_edited'       => (bool) $msg['is_edited'],
                'is_deleted'      => (bool) $msg['is_deleted'],
                'can_modify'      => $canModify,
                'time_formatted'  => date('H:i', $createdTimestamp),
                'date_formatted'  => date('d M Y', $createdTimestamp),
                'created_at'      => $msg['created_at'],
            ];
        }

        return $formatted;
    }

    private function resolveUserInfo($db, string $role, int $id): array
    {
        $name      = 'User';
        $avatar    = base_url('assets/app-assets/user/default.jpg');
        $roleLabel = 'Peserta';

        if ($role === 'admin') {
            $roleLabel = 'Admin';
            $u = $db->table('admin')->where('id_admin', $id)->get()->getRow();
            if ($u) {
                $name   = $u->nama_admin ?? 'Admin KelasBrevet';
                $avatar = !empty($u->avatar) ? base_url('assets/app-assets/user/' . $u->avatar) : base_url('assets/app-assets/user/default.jpg');
            }
        } elseif ($role === 'siswa') {
            $roleLabel = 'Peserta';
            $u = $db->table('siswa')->where('id_siswa', $id)->get()->getRow();
            if ($u) {
                $name   = $u->nama_siswa ?? 'Peserta';
                $avatar = !empty($u->avatar) ? base_url('assets/app-assets/user/' . $u->avatar) : base_url('assets/app-assets/user/default.jpg');
            }
        } elseif ($role === 'guru') {
            $roleLabel = 'Pengajar';
            $u = $db->table('guru')->where('id_guru', $id)->get()->getRow();
            if ($u) {
                $name   = $u->nama_guru ?? 'Pengajar';
                $avatar = !empty($u->avatar) ? base_url('assets/app-assets/user/' . $u->avatar) : base_url('assets/app-assets/user/default.jpg');
            }
        } elseif ($role === 'pic') {
            $roleLabel = 'PIC';
            $u = $db->table('pic')->where('idpic', $id)->get()->getRow();
            if ($u) {
                $name   = $u->nama_pic ?? 'PIC';
                $avatar = !empty($u->avatar) ? base_url('assets/app-assets/user/' . $u->avatar) : base_url('assets/app-assets/user/default.jpg');
            }
        } elseif ($role === 'mitra') {
            $roleLabel = 'Mitra';
            $u = $db->table('mitra')->where('idmitra', $id)->get()->getRow();
            if ($u) {
                $name   = $u->nama_mitra ?? 'Mitra';
                $avatar = !empty($u->avatar) ? base_url('assets/app-assets/user/' . $u->avatar) : base_url('assets/app-assets/user/default.jpg');
            }
        }

        return [
            'name'       => $name,
            'avatar'     => $avatar,
            'role_label' => $roleLabel,
        ];
    }
}

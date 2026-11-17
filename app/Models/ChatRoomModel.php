<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatRoomModel extends Model
{
    protected $table            = 'chat_rooms';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'type',
        'name',
        'avatar',
        'created_by_role',
        'created_by_id',
        'last_message',
        'last_message_at',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    /**
     * Dapatkan atau buat direct chat antar 2 user
     */
    public function getOrCreateDirectRoom(string $myRole, int $myId, string $targetRole, int $targetId): int
    {
        $db = \Config\Database::connect();

        // Cari direct room yang anggotanya persis kedua user ini
        $builder = $db->table('chat_rooms r')
            ->select('r.id')
            ->join('chat_members m1', 'm1.room_id = r.id AND m1.user_role = ' . $db->escape($myRole) . ' AND m1.user_id = ' . (int)$myId)
            ->join('chat_members m2', 'm2.room_id = r.id AND m2.user_role = ' . $db->escape($targetRole) . ' AND m2.user_id = ' . (int)$targetId)
            ->where('r.type', 'direct')
            ->limit(1);

        $row = $builder->get()->getRow();
        if ($row) {
            return (int) $row->id;
        }

        // Buat room baru
        $now = date('Y-m-d H:i:s');
        $roomId = $this->insert([
            'type'            => 'direct',
            'created_by_role' => $myRole,
            'created_by_id'   => $myId,
            'last_message_at' => $now,
            'created_at'      => $now,
            'updated_at'      => $now,
        ]);
        if (!$roomId) {
            $roomId = (int) $db->insertID();
        } else {
            $roomId = (int) $roomId;
        }

        // Masukkan kedua member
        $db->table('chat_members')->insertBatch([
            [
                'room_id'              => $roomId,
                'user_role'            => $myRole,
                'user_id'              => $myId,
                'role_in_room'         => 'admin',
                'last_read_message_id' => 0,
                'joined_at'            => $now,
            ],
            [
                'room_id'              => $roomId,
                'user_role'            => $targetRole,
                'user_id'              => $targetId,
                'role_in_room'         => 'member',
                'last_read_message_id' => 0,
                'joined_at'            => $now,
            ],
        ]);

        return (int) $roomId;
    }
}

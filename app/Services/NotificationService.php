<?php

namespace App\Services;

use Config\Database;

class NotificationService
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Mengambil data notifikasi dan jumlah unread
     */
    public function getUnreadNotifications(int|string $userId, int $limit = 10): array
    {
        $builder = $this->db->table('notifications');

        // Ambil data list
        $notifications = $builder->where('user_id', $userId)
            ->where('is_read', 0)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        // Hitung total unread (Gunakan instance builder baru agar query tidak tercampur)
        $unreadCount = $this->db->table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->countAllResults();

        return [
            'notifications' => $notifications,
            'unread_count'  => $unreadCount
        ];
    }

    /**
     * Menandai semua sebagai baca
     */
    public function markAllAsRead(string $userId): bool
    {
        return $this->db->table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->delete();
    }

    /**
     * Menandai satu sebagai baca berdasarkan UUID
     */
    public function markSingleAsRead(string $uuid, string $userId): bool
    {
        return $this->db->table('notifications')
            ->where('id', $uuid)
            ->where('user_id', $userId)
            ->delete();
    }
}
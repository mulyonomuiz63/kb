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
     * Filter query berdasarkan role aktif agar ID antar tabel (siswa/guru/admin) tidak bertabrakan
     */
    protected function applyRoleScope($builder, int|string $userId)
    {
        $role = (string) session()->get('role');

        if ($role === '1') {
            // Admin menerima notifikasi user_id session('id') atau '1' yang ditujukan untuk sw-admin
            $builder->groupStart()
                ->where('user_id', $userId)
                ->orWhere('user_id', '1')
                ->groupEnd()
                ->notLike('link', '/sw-siswa/')
                ->notLike('link', '/sw-guru/');
        } elseif ($role === '2') {
            // Peserta (Siswa) hanya menerima notifikasi khusus sw-siswa
            $builder->where('user_id', $userId)
                ->notLike('link', '/sw-admin/')
                ->notLike('link', '/sw-guru/')
                ->notLike('link', '/sw-pic/');
        } elseif ($role === '3') {
            // Pengajar (Guru) hanya menerima notifikasi khusus sw-guru
            $builder->where('user_id', $userId)
                ->notLike('link', '/sw-admin/')
                ->notLike('link', '/sw-siswa/')
                ->notLike('link', '/sw-pic/');
        } else {
            $builder->where('user_id', $userId);
        }

        return $builder;
    }

    /**
     * Mengambil data notifikasi dan jumlah unread
     */
    public function getUnreadNotifications(int|string $userId, int $limit = 15): array
    {
        $builder = $this->db->table('notifications');
        $this->applyRoleScope($builder, $userId);

        // Ambil data list
        $notifications = $builder
            ->where('is_read', 0)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        // Hitung total unread (Gunakan instance builder baru agar query tidak tercampur)
        $countBuilder = $this->db->table('notifications');
        $this->applyRoleScope($countBuilder, $userId);
        $unreadCount = $countBuilder
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
        $builder = $this->db->table('notifications');
        $this->applyRoleScope($builder, $userId);
        return $builder
            ->where('is_read', 0)
            ->delete();
    }

    /**
     * Menandai satu sebagai baca berdasarkan UUID
     */
    public function markSingleAsRead(string $uuid, string $userId): bool
    {
        $role = (string) session()->get('role');
        $builder = $this->db->table('notifications')->where('id', $uuid);
        if ($role === '1') {
            $builder->groupStart()
                ->where('user_id', $userId)
                ->orWhere('user_id', '1')
                ->groupEnd();
        } else {
            $builder->where('user_id', $userId);
        }
        return $builder->delete();
    }
}
<?php

if (!function_exists('send_notif')) {
    /**
     * @param string|null $userId  Isi ID user untuk personal, null untuk broadcast ke semua
     * @param string $title       Judul Notifikasi
     * @param string $message     Isi Pesan
     * @param string $link        Link tujuan
     */
    function send_notif($userId, $title, $message, $link = '#')
    {
        helper('uuid');
        $db = \Config\Database::connect();
        $builder = $db->table('notifications');
        // LOGIKA SINGLE NOTIF
        return $builder->insert([
            'id'         => uuid(),
            'user_id'    => $userId,
            'title'      => $title ?? '',
            'message'    => $message ?? '',
            'link'       => $link ?? '',
            'is_read'    => 0,
        ]);
    }
}
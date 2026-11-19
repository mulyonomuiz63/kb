<?php

if (!function_exists('send_notif')) {
    /**
     * @param string|int|null $userId  Isi ID user untuk personal
     * @param string $title            Judul Notifikasi
     * @param string $message          Isi Pesan
     * @param string $link             Link tujuan
     */
    function send_notif($userId, $title, $message, $link = '#')
    {
        if (empty($userId)) {
            return false;
        }
        helper('uuid');
        $db = \Config\Database::connect();
        $builder = $db->table('notifications');
        return $builder->insert([
            'id'         => uuid(),
            'user_id'    => (string) $userId,
            'title'      => $title ?? '',
            'message'    => $message ?? '',
            'link'       => $link ?? '#',
            'is_read'    => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}

if (!function_exists('send_diskusi_notif')) {
    /**
     * Kirim notifikasi diskusi materi secara pintar ke Pengajar, Admin, dan Peserta yang terlibat / di-mention
     */
    function send_diskusi_notif(string $kodeMateri, string $senderRole, string $senderEmail, string $senderName, string $chatText)
    {
        try {
            $db = \Config\Database::connect();
            $materi = $db->table('materi')
                ->where('kode_materi', $kodeMateri)
                ->get()
                ->getRowArray();

            if (!$materi) {
                return;
            }

            $namaMateri = $materi['nama_materi'] ?? 'Materi Brevet';
            $idGuru     = $materi['guru'] ?? null;

            $cleanText = trim(preg_replace('/\s+/', ' ', preg_replace('/@\[(.*?)\]/', '@$1', strip_tags($chatText))));
            $snippet   = mb_strlen($cleanText) > 75 ? mb_substr($cleanText, 0, 72) . '...' : $cleanText;

            // Ekstrak daftar nama yang di-mention @[Nama]
            $mentionedNames = [];
            if (preg_match_all('/@\[(.*?)\]/', $chatText, $matches)) {
                foreach ($matches[1] as $mName) {
                    $mName = trim($mName);
                    if ($mName !== '') {
                        $mentionedNames[] = strtolower($mName);
                    }
                }
            }

            $linkGuru  = base_url('sw-guru/diskusi?materi=' . urlencode($kodeMateri));
            $linkAdmin = base_url('sw-admin/diskusi?materi=' . urlencode($kodeMateri));
            $linkSiswa = base_url('sw-siswa/diskusi?materi=' . urlencode($kodeMateri));

            if ($senderRole === '2') {
                // Pengirim adalah Peserta (Siswa) -> Beri tahu Pengajar & Admin
                if (!empty($idGuru)) {
                    send_notif(
                        $idGuru,
                        'Diskusi: ' . $namaMateri,
                        $senderName . ': "' . $snippet . '"',
                        $linkGuru
                    );
                }

                send_notif(
                    '1',
                    'Diskusi: ' . $namaMateri,
                    $senderName . ': "' . $snippet . '"',
                    $linkAdmin
                );

                // Jika siswa me-mention peserta lain di diskusi tersebut
                if (!empty($mentionedNames)) {
                    $participants = $db->table('chat_materi')
                        ->select(' DISTINCT(email) as email, nama ')
                        ->where('materi', $kodeMateri)
                        ->where('email !=', $senderEmail)
                        ->get()
                        ->getResultArray();

                    $notifiedSiswaIds = [];
                    foreach ($participants as $p) {
                        if (in_array(strtolower(trim($p['nama'] ?? '')), $mentionedNames, true)) {
                            $siswaRow = $db->table('siswa')->select('id_siswa')->where('email', $p['email'])->get()->getRowArray();
                            if ($siswaRow && !in_array($siswaRow['id_siswa'], $notifiedSiswaIds, true)) {
                                $notifiedSiswaIds[] = $siswaRow['id_siswa'];
                                send_notif(
                                    $siswaRow['id_siswa'],
                                    $senderName . ' menyebut Anda di ' . $namaMateri,
                                    '"' . $snippet . '"',
                                    $linkSiswa
                                );
                            }
                        }
                    }
                }
            } else {
                // Pengirim adalah Pengajar (role 3) atau Admin (role 1)
                $roleLabel = ($senderRole === '3') ? 'Pengajar' : 'Admin';

                // Ambil semua peserta yang pernah bertanya/berdiskusi di materi ini
                $participants = $db->table('chat_materi')
                    ->select('email, MAX(nama) as nama')
                    ->where('materi', $kodeMateri)
                    ->where('email !=', $senderEmail)
                    ->groupBy('email')
                    ->get()
                    ->getResultArray();

                $notifiedSiswaIds = [];

                foreach ($participants as $p) {
                    if (empty($p['email'])) {
                        continue;
                    }
                    $siswaRow = $db->table('siswa')->select('id_siswa, nama_siswa')->where('email', $p['email'])->get()->getRowArray();
                    if ($siswaRow && !in_array($siswaRow['id_siswa'], $notifiedSiswaIds, true)) {
                        $notifiedSiswaIds[] = $siswaRow['id_siswa'];
                        $isMentioned = in_array(strtolower(trim($siswaRow['nama_siswa'] ?? '')), $mentionedNames, true)
                            || in_array(strtolower(trim($p['nama'] ?? '')), $mentionedNames, true);

                        $title = $isMentioned
                            ? $senderName . ' membalas Anda di ' . $namaMateri
                            : 'Balasan ' . $roleLabel . ': ' . $namaMateri;

                        send_notif(
                            $siswaRow['id_siswa'],
                            $title,
                            $senderName . ': "' . $snippet . '"',
                            $linkSiswa
                        );
                    }
                }

                // Jika ada nama siswa yang di-mention tapi belum pernah kirim pesan di room ini
                if (!empty($mentionedNames)) {
                    foreach ($mentionedNames as $mName) {
                        $siswaMention = $db->table('siswa')
                            ->select('id_siswa, nama_siswa')
                            ->like('nama_siswa', $mName, 'none')
                            ->get()
                            ->getRowArray();
                        if ($siswaMention && !in_array($siswaMention['id_siswa'], $notifiedSiswaIds, true)) {
                            $notifiedSiswaIds[] = $siswaMention['id_siswa'];
                            send_notif(
                                $siswaMention['id_siswa'],
                                $senderName . ' menyebut Anda di ' . $namaMateri,
                                $senderName . ': "' . $snippet . '"',
                                $linkSiswa
                            );
                        }
                    }
                }

                // Sinkronisasi notif antara Guru & Admin
                if ($senderRole === '1' && !empty($idGuru)) {
                    send_notif(
                        $idGuru,
                        'Balasan Admin: ' . $namaMateri,
                        $senderName . ': "' . $snippet . '"',
                        $linkGuru
                    );
                } elseif ($senderRole === '3') {
                    send_notif(
                        '1',
                        'Balasan Pengajar: ' . $namaMateri,
                        $senderName . ': "' . $snippet . '"',
                        $linkAdmin
                    );
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'send_diskusi_notif error: ' . $e->getMessage());
        }
    }
}

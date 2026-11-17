<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatMemberModel extends Model
{
    protected $table            = 'chat_members';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'room_id',
        'user_role',
        'user_id',
        'role_in_room',
        'last_read_message_id',
        'joined_at',
    ];
    public $timestamps          = false;

    /**
     * Dapatkan daftar anggota dalam room dengan detail user
     */
    public function getMembersWithUser(int $roomId): array
    {
        $members = $this->where('room_id', $roomId)->findAll();
        $db = \Config\Database::connect();
        $result = [];

        foreach ($members as $m) {
            $userRole = $m['user_role'];
            $userId   = (int) $m['user_id'];
            $name     = 'User';
            $avatar   = '';
            $email    = '';

            if ($userRole === 'admin') {
                $u = $db->table('admin')->where('id_admin', $userId)->get()->getRow();
                if ($u) {
                    $name   = $u->nama_admin ?? 'Admin';
                    $avatar = !empty($u->avatar) ? base_url('assets/app-assets/user/' . $u->avatar) : '';
                    $email  = $u->email ?? '';
                }
            } elseif ($userRole === 'siswa') {
                $u = $db->table('siswa')->where('id_siswa', $userId)->get()->getRow();
                if ($u) {
                    $name   = $u->nama_siswa ?? 'Peserta';
                    $avatar = !empty($u->avatar) ? base_url('assets/app-assets/user/' . $u->avatar) : '';
                    $email  = $u->email ?? '';
                }
            } elseif ($userRole === 'guru') {
                $u = $db->table('guru')->where('id_guru', $userId)->get()->getRow();
                if ($u) {
                    $name   = $u->nama_guru ?? 'Pengajar';
                    $avatar = !empty($u->avatar) ? base_url('assets/app-assets/user/' . $u->avatar) : '';
                    $email  = $u->email ?? '';
                }
            } elseif ($userRole === 'pic') {
                $u = $db->table('pic')->where('idpic', $userId)->get()->getRow();
                if ($u) {
                    $name   = $u->nama_pic ?? 'PIC';
                    $avatar = !empty($u->avatar) ? base_url('assets/app-assets/user/' . $u->avatar) : '';
                    $email  = $u->email ?? '';
                }
            } elseif ($userRole === 'mitra') {
                $u = $db->table('mitra')->where('idmitra', $userId)->get()->getRow();
                if ($u) {
                    $name   = $u->nama_mitra ?? 'Mitra';
                    $avatar = !empty($u->avatar) ? base_url('assets/app-assets/user/' . $u->avatar) : '';
                    $email  = $u->email ?? '';
                }
            }

            $m['user_name']   = $name;
            $m['user_avatar'] = $avatar;
            $m['user_email']  = $email;
            $result[] = $m;
        }

        return $result;
    }
}

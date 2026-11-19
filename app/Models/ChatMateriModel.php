<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatMateriModel extends Model
{
    protected $table            = 'chat_materi';
    protected $primaryKey       = 'id_chat_materi';
    protected $allowedFields    = ['materi', 'nama', 'gambar', 'email', 'text', 'status_notif', 'date_created'];

    public function getAllByKodeMateri($kode_materi)
    {
        return $this
            ->where('materi', $kode_materi)
            ->orderBy('id_chat_materi', 'ASC')
            ->get()->getResultObject();
    }
}

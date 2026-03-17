<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatnotifModel extends Model
{
    protected $table            = 'chat_notif';
    protected $primaryKey       = 'idnotif';
    protected $allowedFields    = ['materi', 'email'];

    public function getAllnotif($email,$kode_materi)
    {
        return $this
            ->where('email', $email)
            ->where('materi', $kode_materi)
            ->get()->getResultObject();
    }
}

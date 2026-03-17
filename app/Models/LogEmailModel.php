<?php

namespace App\Models;

use CodeIgniter\Model;

class LogEmailModel extends Model
{
    protected $table            = 'log_emails';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array'; // Bisa diubah ke 'object' jika suka
    protected $protectFields    = true;
    
    // Kolom yang boleh diisi (mass assignment)
    protected $allowedFields    = [
        'penerima', 
        'subjek', 
        'status', 
        'error_message', 
        'created_at'
    ];

    // Fitur Otomatisasi Waktu
    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = ''; // Kosongkan karena kita tidak butuh log kapan diupdate

    /**
     * Opsional: Fungsi untuk mengambil log terbaru
     */
    public function getLatestLogs($limit = 100)
    {
        return $this->orderBy('created_at', 'DESC')->findAll($limit);
    }
}
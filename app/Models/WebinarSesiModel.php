<?php
namespace App\Models;
use CodeIgniter\Model;

class WebinarSesiModel extends Model
{
    protected $table            = 'webinar_sesi';
    protected $primaryKey       = 'id_sesi';
    protected $allowedFields    = ['idpaket', 'nama_sesi', 'deskripsi_sesi', 'waktu_mulai', 'waktu_selesai', 'harga_sesi', 'link_zoom'];

    // Fungsi untuk Landing Page: Mengambil data paket berserta sesi-sesinya
    public function getPaketWebinarLengkap($idpaket = null)
    {
        $builder = $this->db->table('paket'); // Asumsi nama tabel Anda 'paket'
        
        if ($idpaket) {
            $builder->where('paket.idpaket', $idpaket);
        }
        
        $pakets = $builder->get()->getResult();

        foreach ($pakets as $p) {
            // Ambil sesi untuk masing-masing paket
            $p->sesi = $this->where('idpaket', $p->idpaket)->findAll();
        }

        return $pakets;
    }
}
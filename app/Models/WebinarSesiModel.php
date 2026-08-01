<?php

namespace App\Models;

use CodeIgniter\Model;

class WebinarSesiModel extends Model
{
    protected $table            = 'webinar_sesi';
    protected $primaryKey       = 'id_sesi';
    protected $allowedFields    = ['nama_sesi', 'deskripsi_sesi', 'waktu_mulai', 'waktu_selesai', 'harga_sesi', 'link_zoom', 'link_youtube','sesi_gratis'];

    // Fungsi untuk Landing Page: Mengambil data paket berserta sesi-sesinya
    public function getPaketWebinarLengkap($slug = null)
    {
        $builder = $this->db->table('paket');

        if ($slug) {
            $builder->where('slug', $slug);
        }

        $pakets = $builder->get()->getResult(); // Paket utama dibiarkan object

        foreach ($pakets as $p) {
            // Ambil sesi sebagai ARRAY menggunakan getResultArray()
            $p->sesi = $this->db->table('webinar_sesi ws') // Sesuaikan nama tabel
                ->select('ws.*, d.diskon')
                ->join('detail_paket dp', 'dp.id_sesi = ws.id_sesi')
                ->join('paket p', 'p.idpaket = dp.idpaket')
                ->join('diskon d', 'd.iddiskon = p.iddiskon', 'left')
                ->where('dp.idpaket', $p->idpaket)
                ->orderBy('harga_sesi', 'asc')
                ->orderBy('waktu_mulai', 'asc')
                ->get()
                ->getResultArray(); // <-- Ini kunci perbaikannya
        }

        if ($slug && !empty($pakets)) {
            return $pakets[0];
        }

        return $pakets;
    }
}

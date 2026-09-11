<?php

namespace App\Models;

use CodeIgniter\Model;

class WebinarSesiModel extends Model
{
    protected $table            = 'webinar_sesi';
    protected $primaryKey       = 'id_sesi';
    protected $allowedFields    = ['nama_sesi', 'deskripsi_sesi', 'waktu_mulai', 'waktu_selesai', 'harga_sesi', 'link_zoom', 'link_youtube', 'sesi_gratis', 'status', 'file_materi'];

    // Fungsi untuk Landing Page: Mengambil data paket berserta sesi-sesinya
    public function getPaketWebinarLengkap($slug = null)
    {
        // 1. PERBAIKAN DI SINI: Gunakan alias 'p' dan join tabel diskon
        $builder = $this->db->table('paket p');
        $builder->select('p.*, d.diskon'); // Ambil semua data paket + kolom diskon
        $builder->join('diskon d', 'd.iddiskon = p.iddiskon', 'left');

        if ($slug) {
            $builder->where('p.slug', $slug);
        }

        $pakets = $builder->get()->getResult(); // Sekarang object paket punya properti ->diskon

        foreach ($pakets as $p) {
            // Ambil sesi sebagai ARRAY menggunakan getResultArray()
            $p->sesi = $this->db->table('webinar_sesi ws')
                ->select('ws.*, d.diskon') // (Nilai diskon per sesi tetap diambil)
                ->join('detail_paket dp', 'dp.id_sesi = ws.id_sesi')
                ->join('paket p', 'p.idpaket = dp.idpaket')
                ->join('diskon d', 'd.iddiskon = p.iddiskon', 'left')
                ->where('dp.idpaket', $p->idpaket)
                ->orderBy('harga_sesi', 'asc')
                ->orderBy('waktu_mulai', 'asc')
                ->get()
                ->getResultArray();
        }

        if ($slug && !empty($pakets)) {
            return $pakets[0];
        }

        return $pakets;
    }
}

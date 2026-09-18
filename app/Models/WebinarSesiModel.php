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
        $builder = $this->db->table('paket p');
        $builder->select('p.*, d.diskon');
        $builder->join('diskon d', 'd.iddiskon = p.iddiskon', 'left');

        if ($slug) {
            // UPGRADE 1: Cek apakah slug berupa array (banyak) atau string (satu)
            if (is_array($slug)) {
                $builder->whereIn('p.slug', $slug); // Gunakan whereIn jika array
            } else {
                $builder->where('p.slug', $slug); // Gunakan where biasa jika string tunggal
            }
        }

        $pakets = $builder->get()->getResult();

        foreach ($pakets as $p) {
            $p->sesi = $this->db->table('webinar_sesi ws')
                ->select('ws.*, d.diskon')
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
            // UPGRADE 2: Mengatur return data
            if (is_array($slug)) {
                // Jika memanggil banyak slug (array), kembalikan SEMUA datanya
                return $pakets;
            } else {
                // Jika memanggil 1 slug saja (string), kembalikan data tunggal (Object pertama)
                // Ini menjaga agar halaman lain yang pakai fungsi ini dengan 1 slug tidak ikut error
                return $pakets[0];
            }
        }

        return $pakets;
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;


class PaketModel extends Model
{
    protected $table            = 'paket';
    protected $primaryKey       = 'idpaket';
    protected $allowedFields    = ['iddiskon', 'slug', 'nama_paket', 'jenis_paket', 'jumlah_bulan', 'nominal_paket','file', 'status', 'v_ujian', 'v_materi', 'deskripsi', 'is_pinned', 'sort_order', 'komisi'];
    
    protected $beforeInsert = ['generateSlug'];
    // protected $beforeUpdate = ['generateSlug'];

    protected function generateSlug(array $data)
    {
        helper('text'); // gunakan helper bawaan CI4: url_title()

        if (isset($data['data']['nama_paket'])) {
            // buat slug dasar
            $slug = url_title($data['data']['nama_paket'], '-', true);

            // pastikan slug unik
            $slug = $this->makeUniqueSlug($slug, $data['data'][$this->primaryKey] ?? null);

            $data['data']['slug'] = $slug;
        }

        return $data;
    }

    /**
     * Membuat slug unik dengan menambahkan angka jika sudah ada
     */
    private function makeUniqueSlug(string $slug, $id = null): string
    {
        $originalSlug = $slug;
        $counter = 1;

        while (
            $this->where('slug', $slug)
                 ->where($this->primaryKey . ' !=', $id)
                 ->first()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    
    public function getAll()
    {
        return $this
            ->select('paket.*, b.diskon, c.id_mapel, c.id_ujian')
            ->join('diskon b', 'b.iddiskon = paket.iddiskon')
            ->join('detail_paket c', 'c.idpaket=paket.idpaket')
            ->where('paket.status', 1)
            ->orderBy('paket.is_pinned', 'DESC')
            ->orderBy('paket.sort_order', 'asc')
            ->groupBy('paket.idpaket')
            ->get()->getResultObject();
    }
    public function getById($id)
    {
        return $this
            ->select('paket.*, b.diskon, c.id_mapel')
            ->join('diskon b', 'b.iddiskon = paket.iddiskon')
            ->join('detail_paket c', 'c.idpaket=paket.idpaket')
            ->where('paket.idpaket', $id)
            ->where('paket.status', '1')
            ->get()->getRowObject();
    }
    
    public function getAllLimit()
    {
        return $this
            ->select('paket.*, b.diskon, c.id_mapel, c.id_ujian')
            ->join('diskon b', 'b.iddiskon = paket.iddiskon')
            ->join('detail_paket c', 'c.idpaket=paket.idpaket')
            ->where('paket.status', 1)
            ->orderBy('paket.is_pinned', 'DESC')
            ->orderBy('paket.sort_order', 'asc')
            ->groupBy('paket.idpaket')
            ->limit(3)
            ->get()->getResultObject();
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;


class ArtikelModel extends Model
{
    protected $table            = 'artikel';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['iduser', 'idkategori', 'judul', 'slug_judul', 'konten','image_default', 'hit'];

    public function getAll()
    {
        return $this->get()->getResultObject();
    }
    public function detailArtikel($slug)
    {
         return $this->get()->getRowObject();
    }
    public function kategoriArtikel($slug)
    {   
         return $this->where()->get()->getRowObject();
    }
    public function tagArtikel($slug)
    {
         return $this->get()->getRowObject();
    }
    
    function getKategori()
    {
        return $this->db->table("kategori_artikel a")
            ->select("a.kategori,a.slug_kategori, count(a.id) as total")
            ->join("artikel b", "a.id=b.idkategori")
            ->groupBy("a.id")
            ->get()->getResultObject();
    }
    
    public function getPagination(?int $perPage = null, $slug = null)
    {
        $this->builder()
            ->select('artikel.*, b.kategori, c.nama_admin')
            ->join('kategori_artikel b', 'artikel.idkategori=b.id')
            ->join('admin c', 'artikel.iduser=c.id_admin')
            ->where('b.slug_kategori', $slug)
            ->orderBy('artikel.id', 'desc')
            ->groupBy('artikel.id');
        return [
            'kategoriartikel'  => $this->paginate($perPage),
            'pager' => $this->pager,
        ];
    }

    function getPaginationAll($slug)
    {
        return $this->builder()
            ->select('artikel.*, b.kategori, c.nama_admin')
            ->join('kategori_artikel b', 'artikel.idkategori=b.id')
            ->join('admin c', 'artikel.iduser=c.id_admin')
            ->where('b.slug_kategori', $slug)
            ->orderBy('artikel.id', 'desc')
            ->get()->getResultObject();
    }
    
    public function getDetail($slug)
    {
        return $this->builder()
            ->select('artikel.*, b.kategori, c.nama_admin')
            ->join('kategori_artikel b', 'artikel.idkategori=b.id')
            ->join('admin c', 'artikel.iduser=c.id_admin')
            ->where('artikel.slug_judul', $slug)
            ->get()->getRowObject();
    }
    
    public function getPaginationTag(?int $perPage = null, $slug = null)
    {
        $this->builder()
            ->select('artikel.*, b.kategori, c.nama_admin')
            ->join('kategori_artikel b', 'artikel.idkategori=b.id')
            ->join('admin c', 'artikel.iduser=c.id_admin')
            ->join('tag_artikel d', 'artikel.id=d.idartikel')
            ->where('d.slug_tag', $slug)
            ->orderBy('artikel.id', 'desc')
            ->groupBy('d.id');
        return [
            'tagArtikel'  => $this->paginate($perPage),
            'pager' => $this->pager,
        ];
    }

    function getPaginationAllTag($slug)
    {
        return $this->builder()
            ->select('artikel.*, b.kategori, c.nama_admin')
            ->join('kategori_artikel b', 'artikel.idkategori=b.id')
            ->join('admin c', 'artikel.iduser=c.id_admin')
            ->join('tag_artikel d', 'artikel.id=d.idartikel')
            ->where('d.slug_tag', $slug)
            ->orderBy('artikel.id', 'desc')
            ->get()->getResultObject();
    }
    
    public function getTag($id)
    {
        return $this->db->table("tag_artikel a")
            ->where('a.idartikel', $id)
            ->get()->getResultObject();
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class BankSoalModel extends Model
{
    protected $table            = 'bank_soal';
    protected $primaryKey       = 'id_bank_soal';
    protected $allowedFields    = ['id_kategori', 'nama_soal', 'pg_1', 'pg_2', 'pg_3', 'pg_4', 'pg_5', 'jawaban', 'penjelasan'];


    var $column_order = array(null); //set nama field yang bisa diurutkan
    var $column_search = array('nama_soal'); //set nama field yang akan di cari
    var $order = array('nama_soal' => 'asc'); // default order 

    public function getAll()
    {
        return $this
            ->join('kategori', 'kategori.id_kategori=bank_soal.id_kategori')
            ->get()->getResultObject();
    }
    public function getById($id)
    {
        return $this
            ->where('id_bank_soal', $id)
            ->get()->getRowObject();
    }
    public function getBySoal($nama_soal)
    {
        return $this
            ->where('nama_soal', $nama_soal)
            ->get()->getRowObject();
    }
    function get_datatables()
    {
        $this->_get_datatables_query();
        if (!empty($_POST['length']) && $_POST['length'] != -1)
            $this->builder->limit($_POST['length'], $_POST['start']);
        return $this->builder->get();
    }

    private function _get_datatables_query()
    {
        $this->builder = $this->db->table('bank_soal');
        $i = 0;

        if ($_POST['nama_soal'] != "") {
            $this->builder->like('nama_soal', $_POST['nama_soal']);
        }
        if ($_POST['id_kategori'] != "") {
            $this->builder->like('id_kategori', $_POST['id_kategori']);
        }
        foreach ($this->column_search as $item) {
            if (!empty($_POST['search']['value'])) {
                if ($i === 0) {
                    $this->builder->groupStart(); // Untuk Menggabung beberapa kondisi "AND"
                    $this->builder->like($item, $_POST['search']['value']);
                } else {
                    $this->builder->orLike($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) //last loop
                    $this->builder->groupEnd();
            }
            $i++;
        }

        // -------------------------> Proses Order by        
        if (isset($_POST['order'])) {
            $this->builder->orderBy($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->builder->orderBy(key($order), $order[key($order)]);
        }
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $this->builder->select('count(*) as jlh');
        $query = $this->builder->get();
        return $query->getRow()->jlh;
    }

    public function count_all()
    {
        $builder = $this->db->table('bank_soal');
        return $builder->countAllResults();
    }
}

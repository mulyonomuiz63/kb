<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table            = 'siswa';
    protected $primaryKey       = 'id_siswa';
    protected $allowedFields    = ['no_induk_siswa', 'nama_siswa', 'email', 'password', 'jenis_kelamin', 'kelas', 'role', 'is_active', 'date_created', 'avatar', 'nik', 'tgl_lahir', 'alamat_ktp', 'alamat_domisili', 'provinsi', 'kota', 'kecamatan', 'kelurahan', 'hp', 'profesi', 'kota_intansi', 'bidang_usaha', 'kota_aktifitas_profesi', 'status'];

    public function get_datatables($column_order, $column_search, $order)
    {
        $builder = $this->_get_datatables_query($column_order, $column_search, $order);

        if ($_POST['length'] != -1) {
            $builder->limit($_POST['length'], $_POST['start']);
        }

        return $builder->get()->getResult();
    }

    public function countFiltered($column_order, $column_search, $order)
    {
        $builder = $this->_get_datatables_query($column_order, $column_search, $order);
        return $builder->countAllResults();
    }

    // Fungsi bantu (Private) agar tidak duplikasi kode query
    private function _get_datatables_query($column_order, $column_search, $order)
    {
        $request = \Config\Services::request();
        // Gunakan $this->table karena sudah didefinisikan di properti Model
        $builder = $this->db->table($this->table);

        // FILTER STATUS (TAMBAHKAN INI)
        $status = $request->getPost('status_filter');
        if ($status !== '' && $status !== null) {
            if($status == '2'){
                $builder->where('is_active', '1');
                $builder->where('status', 'B');
            }else{
                $builder->where('is_active', $status);
            }
        }

        $i = 0;
        foreach ($column_search as $item) {
            if (isset($_POST['search']['value']) && $_POST['search']['value']) {
                if ($i === 0) {
                    $builder->groupStart();
                    $builder->like($item, $_POST['search']['value']);
                } else {
                    $builder->orLike($item, $_POST['search']['value']);
                }
                if (count($column_search) - 1 == $i) $builder->groupEnd();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $builder->orderBy($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($order)) {
            $builder->orderBy(key($order), $order[key($order)]);
        }

        return $builder;
    }
    // buat admin
    public function getAll()
    {
        return $this
            ->select('siswa.*, siswa.status as status_data,kelas.*')
            ->join('kelas', 'kelas.id_kelas = siswa.kelas')
            ->orderBy('siswa.id_siswa', 'desc')
            ->get()->getResultObject();
    }
    public function getAllAktif()
    {
        return $this
            ->select('siswa.*, siswa.status as status_data')
            ->where('siswa.is_active', '1')->where('siswa.status', 'S')
            ->orderBy('siswa.id_siswa', 'desc')
            ->get()->getResultObject();
    }

    public function getAllTidakAktif()
    {
        return $this
            ->select('siswa.*, siswa.status as status_data')
            ->where('siswa.is_active', '0')
            ->orderBy('siswa.id_siswa', 'desc')
            ->get()->getResultObject();
    }

    public function getAllBanned()
    {
        return $this
            ->select('siswa.*, siswa.status as status_data')
            ->where('siswa.is_active', '1')->where('siswa.status', 'B')
            ->orderBy('siswa.date_created', 'desc')
            ->orderBy('siswa.nama_siswa', 'asc')
            ->get()->getResultObject();
    }

    public function getSertifikatAB()
    {
        return $this
            ->select('siswa.*,max(ujian.end_ujian) as end_ujian')
            ->join('ujian', 'ujian.id_siswa = siswa.id_siswa', 'left')
            ->groupBy('ujian.id_siswa')
            ->orderBy('max(ujian.end_ujian)', 'desc')
            ->get()->getResultObject();
    }

    public function getId($id_siswa)
    {
        return $this
            ->select('siswa.*, siswa.status as status_data')
            ->where('siswa.id_siswa', $id_siswa)
            ->get()->getRowObject();
    }

    public function getAllbyKelas($kelas)
    {
        return $this
            ->join('kelas', 'kelas.id_kelas = siswa.kelas')
            ->join('ujian_siswa', 'ujian_siswa.siswa=siswa.id_siswa', 'left')
            ->where('siswa.kelas', $kelas)
            ->groupBy('siswa.id_siswa')
            ->get()->getResultObject();
    }

    public function getAllbyKelasUjian($kelas, $ujian)
    {
        return $this
            ->join('kelas', 'kelas.id_kelas = siswa.kelas')
            ->join('ujian_siswa', 'ujian_siswa.siswa=siswa.id_siswa', 'left')
            ->where('siswa.kelas', $kelas)
            ->where('ujian_siswa.ujian', $ujian)
            ->orderBy('ujian_siswa.date_send', 'desc')
            ->groupBy('siswa.id_siswa')
            ->limit(20)
            ->get()->getResultObject();
    }

    public function getByEmail($email)
    {
        return $this
            ->select('siswa.*,kelas.*')
            ->join('kelas', 'kelas.id_kelas = siswa.kelas')
            ->where('siswa.email', $email)
            ->get()->getRowObject();
    }

    public function getById($id)
    {
        return $this
            ->join('kelas', 'kelas.id_kelas = siswa.kelas')
            ->where('siswa.id_siswa', $id)
            ->get()->getRowObject();
    }

    public function getByNoInduk($nim)
    {
        return $this
            ->join('kelas', 'kelas.id_kelas = siswa.kelas')
            ->where('siswa.no_induk_siswa', $nim)
            ->get()->getRowObject();
    }
}

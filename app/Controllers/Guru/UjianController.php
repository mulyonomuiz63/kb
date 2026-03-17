<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class UjianController extends BaseController
{
    protected $ujianMasterModel;
    protected $guruKelasModel;
    protected $guruMapelModel;
    protected $guruModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->ujianMasterModel = new \App\Models\UjianMasterModel();
        $this->guruKelasModel = new \App\Models\GuruKelasModel();
        $this->guruMapelModel = new \App\Models\GuruMapelModel();
        $this->guruModel = new \App\Models\GuruModel();
        $this->kategoriModel = new \App\Models\KategoriModel();
    }

    // START = UJIAN PG
    public function index()
    {
        // Cukup panggil view, data akan diload via AJAX
        return view('guru/ujian/list');
    }

    public function datatables()
    {
        if ($this->request->isAJAX()) {
            $id_guru = session()->get('id');

            // Ambil data dari model
            $list = $this->ujianMasterModel->get_datatables($id_guru, $this->request);

            $data = [];
            foreach ($list as $u) {
                // PROTEKSI 1: Lewati jika data baris ini null
                if (empty($u)) continue;

                $row = [];

                // PROTEKSI 2: Gunakan null coalescing (??) agar jika kolom tidak ada, tidak error
                $nama_ujian = $u->nama_ujian ?? '-';
                $nama_kelas = $u->nama_kelas ?? '-';
                $kode_ujian = $u->kode_ujian ?? null;

                // Jika kode_ujian tidak ada, data ini rusak, lewati saja
                if (!$kode_ujian) continue;

                $row[] = $nama_ujian;
                $row[] = $nama_kelas;

                // Status logic
                $db = \Config\Database::connect();
                $status_data = $db->query("SELECT status FROM status_ujian WHERE kode_ujian = ?", [$kode_ujian])->getRow();

                $is_active = (!empty($status_data) && $status_data->status == 'A');
                $badge = $is_active ? 'bg-success' : 'bg-danger';
                $label = $is_active ? 'Aktif' : 'Tidak Aktif';

                $row[] = '
            <form action="' . base_url('Guru/ubah_status_ujian') . '" method="POST">
                ' . csrf_field() . '
                <input type="hidden" name="kode_ujian" value="' . $kode_ujian . '">
                <button type="submit" class="badge ' . $badge . ' border-0">' . $label . '</button>
            </form>';

                // Opsi logic
                $enc_kode = encrypt_url($kode_ujian);
                $enc_guru = encrypt_url($id_guru);

                // Proteksi jenis_ujian
                $jenis = $u->jenis_ujian ?? 0;
                $url_lihat = ($jenis == 1)
                    ? base_url('guru/lihat_essay/' . $enc_kode . '/' . $enc_guru)
                    : base_url('guru/lihat_ujian/' . $enc_kode . '/' . $enc_guru);

                $row[] = '
            <div class="dropdown custom-dropdown">
                <a class="dropdown-toggle btn btn-primary" href="#" role="button" data-toggle="dropdown">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" class="css-i6dzq1"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="' . $url_lihat . '">Lihat</a>
                    <a class="dropdown-item" href="' . base_url('guru/edit_ujian/' . $enc_kode . '/' . $enc_guru) . '">Edit</a>
                </div>
            </div>';

                $data[] = $row;
            }

            $output = [
                "draw" => intval($this->request->getPost('draw')),
                "recordsTotal" => (int)($this->ujianMasterModel->count_all($id_guru) ?? 0),
                "recordsFiltered" => (int)($this->ujianMasterModel->count_filtered($id_guru, $this->request) ?? 0),
                "data" => $data,
            ];

            $output[csrf_token()] = csrf_hash();
            return $this->response->setJSON($output);
        }
    }
    public function create()
    {
        $data['guru_kelas'] = $this->guruKelasModel->getALLByGuru(session()->get('id'));
        $data['guru_mapel'] = $this->guruMapelModel->getALLByGuru(session()->get('id'));
        $data['guru'] = $this->guruModel->asObject()->find(session()->get('id'));
        $data['kategori'] = $this->kategoriModel->getAll();
        return view('guru/ujian/tambah_pg', $data);
    }
}

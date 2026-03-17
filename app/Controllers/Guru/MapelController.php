<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\MapelModel;

class MapelController extends BaseController
{
    protected $mapelModel;
    protected $guruMapelModel;
    protected $guruKelasModel;
    protected $materiModel;
    protected $fileModel;
    protected $guruModel;
    protected $chatMateriModel;

    public function __construct()
    {
        $this->mapelModel = new MapelModel();
        $this->guruMapelModel = new \App\Models\GuruMapelModel();
        $this->guruKelasModel = new \App\Models\GuruKelasModel();
        $this->materiModel = new \App\Models\MateriModel();
        $this->fileModel = new \App\Models\FileModel();
        $this->guruModel = new \App\Models\GuruModel();
        $this->chatMateriModel = new \App\Models\ChatMateriModel();
    }

    public function index()
    {
        $data['mapel'] = $this->guruMapelModel->join('guru_kelas','guru_kelas.guru=guru_mapel.guru')->where('guru_mapel.guru', session()->get('id'))->groupBy('guru_mapel.mapel')->get()->getResultObject();

        $data['guru_kelas'] = $this->guruKelasModel->getALLByGuru(session()->get('id'));
        $data['guru_mapel'] = $this->guruMapelModel->getALLByGuru(session()->get('id'));

        return view('guru/materi/index', $data);
    }

    public function datatables()
    {
        $request = $this->request;
        $db      = $this->db;
        $builder = $db->table('mapel'); // Sesuaikan nama tabel

        // Logika Server-side
        $searchValue = $request->getPost('search')['value'];
        $start = $request->getPost('start');
        $length = $request->getPost('length');

        if ($searchValue) {
            $builder->like('nama_mapel', $searchValue);
        }

        $totalFiltered = $builder->countAllResults(false);
        $data = $builder->limit($length, $start)->get()->getResult();

        $result = [];
        $no = $start + 1;
        foreach ($data as $row) {
            $imgUrl = base_url('uploads/mapel/' . $row->file);
            $opsi = '
            <div class="dropdown custom-dropdown text-center">
                <a class="dropdown-toggle badge badge-primary border-0" href="#" role="button" data-toggle="dropdown">
                    <i class="bi bi-three-dots-vertical"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow border-0">
                    <a class="dropdown-item py-2 edit-mapel" href="javascript:void(0)" data-id="' . encrypt_url($row->id_mapel) . '">
                        <i class="bi bi-pencil-square text-primary"></i> Edit
                    </a>
                    <a class="dropdown-item py-2 btn-delete" href="javascript:void(0)" data-url="' . base_url('sw-admin/mapel/delete/' . encrypt_url($row->id_mapel)) . '">
                        <i class="bi bi-trash text-danger"></i> Hapus
                    </a>
                </div>
            </div>';

            $result[] = [
                $no++,
                '<strong>' . $row->nama_mapel . '</strong>',
                '<img src="' . $imgUrl . '" class="img-thumbnail" style="width:80px; height:auto;">',
                $opsi
            ];
        }

        return $this->response->setJSON([
            "draw"            => intval($request->getPost('draw')),
            "recordsTotal"    => $this->mapelModel->countAll(),
            "recordsFiltered" => $totalFiltered,
            "data"            => $result,
            "token"           => csrf_hash() // Kirim token baru ke DataTable
        ]);
    }

    
}

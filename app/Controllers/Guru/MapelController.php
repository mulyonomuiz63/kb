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
        $idguru = session()->get('idguru_diadmin') ?? session()->get('id');
        // Cek apakah session idguru_diadmin ada (berarti diakses oleh admin)
        $isAdmin = !empty(session()->get('idguru_diadmin'));
        $data['breadcrumbs'] = [
            [
                'title' => $isAdmin ? 'Instruktur': 'Dashboard',
                'url'   => $isAdmin ? base_url('sw-admin/guru') : base_url('sw-guru') // Sesuaikan 'sw-admin' dengan route dashboard admin Anda
            ],
            ['title' => 'List Ujian', 'url' => '#'],
        ];
        $data['mapel'] = $this->guruMapelModel
            ->select('guru_mapel.*, guru_kelas.nama_kelas') // Secara spesifik memanggil kolom nama_kelas
            ->join('guru_kelas', 'guru_kelas.guru = guru_mapel.guru AND guru_kelas.kelas = guru_mapel.kelas', 'left') // Join berdasarkan Guru DAN Kelas
            ->where('guru_mapel.guru', $idguru)
            // ->groupBy('guru_mapel.mapel') // SEBAIKNYA DIHAPUS agar mapel yang diajarkan di 2 kelas berbeda tidak saling menimpa
            ->get()
            ->getResultObject();

        $data['guru_kelas'] = $this->guruKelasModel->getALLByGuru($idguru);
        $data['guru_mapel'] = $this->guruMapelModel->getALLByGuru($idguru);

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
                    <a class="dropdown-item py-2 btn-delete" href="javascript:void(0)" data-url="' . base_url('sw-guru/mapel/delete/' . encrypt_url($row->id_mapel)) . '">
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

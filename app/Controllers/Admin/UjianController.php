<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class UjianController extends BaseController
{
    protected $ujianMasterModel;

    public function __construct()
    {
        $this->ujianMasterModel = new \App\Models\UjianMasterModel();
    }

    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'List Ujian', 'url' => '#'],
        ];
        return view('admin/ujianMaster/list', $data);
    }

    public function datatable()
    {
        try {
            $request = \Config\Services::request();

            $db = \Config\Database::connect();
            $builder = $db->table('ujian');

            // Pilih kolom yang diperlukan dan agregasi data
            $builder->select('
            kode_ujian,
            nama_ujian,
            COUNT(id_siswa) as total_peserta,
            SUM(CASE WHEN status = "B" THEN 1 ELSE 0 END) as status_b,
            SUM(CASE WHEN status = "U" THEN 1 ELSE 0 END) as status_u,
            SUM(CASE WHEN status = "S" THEN 1 ELSE 0 END) as status_s,
            SUM(CASE WHEN status = "T" THEN 1 ELSE 0 END) as status_t,
            SUM(CASE WHEN nilai < 65 AND status = "S" THEN 1 ELSE 0 END) as nilai_kurang,
            SUM(CASE WHEN nilai >= 65 AND status = "S" THEN 1 ELSE 0 END) as nilai_lulus
        ');
            $builder->groupBy('kode_ujian, nama_ujian');
            $builder->orderBy('id_ujian', 'ASC');

            // 1. Hitung Total Data Asli (Tanpa Filter)
            $totalDataBuilder = clone $builder;
            $totalData = count($totalDataBuilder->get()->getResult());

            // 2. Logika Pencarian
            $searchValue = $request->getPost('search')['value'] ?? '';
            if ($searchValue) {
                $builder->groupStart()
                    ->like('nama_ujian', $searchValue)
                    ->orLike('kode_ujian', $searchValue)
                    ->groupEnd();
            }

            // 3. Hitung Total Setelah Filter
            $totalFilteredBuilder = clone $builder;
            $totalFiltered = count($totalFilteredBuilder->get()->getResult());

            // 4. Order (Sorting kolom)
            $orderColumn = $request->getPost('order');
            if ($orderColumn) {
                $cols = ['', 'nama_ujian', 'total_peserta', 'status_s', 'nilai_lulus'];
                if (isset($cols[$orderColumn[0]['column']]) && !empty($cols[$orderColumn[0]['column']])) {
                    $builder->orderBy($cols[$orderColumn[0]['column']], $orderColumn[0]['dir']);
                }
            }

            // 5. Limit & Offset
            $length = $request->getPost('length') ?? 10;
            $start = $request->getPost('start') ?? 0;
            $list = $builder->limit($length, $start)->get()->getResult();

            $data = [];
            $no = intval($start) + 1;

            foreach ($list as $m) {
                $row = [];
                $row[] = $no++;

                // 1. Nama Ujian & Kode
                $row[] = '<div class="d-flex flex-column">
                        <span class="text-gray-800 fw-bold fs-6">' . esc($m->nama_ujian) . '</span>
                        <span class="text-muted fs-7">Kode: ' . esc($m->kode_ujian) . '</span>
                      </div>';

                // 2. Total Peserta
                $row[] = '<div class="text-center"><span class="badge badge-light-primary fw-bold px-3 py-2 fs-7">' . $m->total_peserta . ' Peserta</span></div>';

                // 3. Status Pengerjaan (B, U, S, T)
                $row[] = '<div class="d-flex flex-column gap-1 fs-7">
                        <span>Aktif (B): <b>' . $m->status_b . '</b></span>
                        <span>Berjalan (U): <b class="text-warning">' . $m->status_u . '</b></span>
                        <span>Selesai (S): <b class="text-success">' . $m->status_s . '</b></span>
                        <span>Ditangguhkan (T): <b class="text-danger">' . $m->status_t . '</b></span>
                      </div>';

                // 4. Analisis Nilai (< 65 dan >= 65)
                $row[] = '<div class="d-flex flex-column gap-1 fs-7">
                        <span><i class="ki-outline ki-arrow-down text-danger fs-6 me-1"></i> Di bawah 65: <b class="text-danger">' . $m->nilai_kurang . '</b></span>
                        <span><i class="ki-outline ki-arrow-up text-success fs-6 me-1"></i> 65 ke atas: <b class="text-success">' . $m->nilai_lulus . '</b></span>
                      </div>';

                $data[] = $row;
            }

            return $this->response->setJSON([
                "draw"            => intval($request->getPost('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data,
                "csrfHash"        => csrf_hash()
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'error'    => $e->getMessage(),
                'csrfHash' => csrf_hash()
            ]);
        }
    }
}

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
            $builder = $db->table('ujian_master'); // Tabel utama adalah ujian_master

            // Lakukan LEFT JOIN ke tabel 'ujian' agar data ujian_master tetap tampil 
            // meskipun belum ada siswa yang masuk ke tabel ujian
            $builder->select('
                ujian_master.kode_ujian,
                ujian_master.nama_ujian,
                COUNT(ujian.id_siswa) as total_peserta,
                SUM(CASE WHEN ujian.status = "B" THEN 1 ELSE 0 END) as status_b,
                SUM(CASE WHEN ujian.status = "U" THEN 1 ELSE 0 END) as status_u,
                SUM(CASE WHEN ujian.status = "S" THEN 1 ELSE 0 END) as status_s,
                SUM(CASE WHEN ujian.status = "T" THEN 1 ELSE 0 END) as status_t,
                SUM(CASE WHEN ujian.nilai < 65 AND ujian.status = "S" THEN 1 ELSE 0 END) as nilai_kurang,
                SUM(CASE WHEN ujian.nilai >= 65 AND ujian.status = "S" THEN 1 ELSE 0 END) as nilai_lulus
            ');
            $builder->join('ujian', 'ujian.kode_ujian = ujian_master.kode_ujian', 'left');

            // Group by kolom dari ujian_master
            $builder->groupBy('ujian_master.kode_ujian, ujian_master.nama_ujian');

            // Urutkan berdasarkan id di ujian_master (misal: id_ujian atau id pada master)
            $builder->orderBy('ujian_master.id_ujian', 'ASC'); // Sesuaikan nama kolom primary key di ujian_master jika berbeda

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

    // FUNGSI BARU: Untuk Export Data Peserta ke Excel
    // FUNGSI BARU: Untuk Export Data Peserta ke Excel dengan Filter
    public function exportExcel()
    {
        $tahun = $this->request->getGet('tahun');
        $bulan = $this->request->getGet('bulan');

        $db = \Config\Database::connect();
        $builder = $db->table('siswa');
        $builder->select('siswa.nama_siswa, siswa.hp, siswa.email');
        $builder->join('ujian', 'ujian.id_siswa = siswa.id_siswa', 'inner');

        // LOGIKA FILTER MENGGUNAKAN FROM_UNIXTIME UNTUK KOLOM date_created
        if (!empty($tahun)) {
            $builder->where('YEAR(FROM_UNIXTIME(ujian.date_created))', $tahun);
            if (!empty($bulan)) {
                $builder->where('MONTH(FROM_UNIXTIME(ujian.date_created))', $bulan);
            }
        }

        $builder->groupBy('siswa.id_siswa, siswa.nama_siswa, siswa.hp, siswa.email');
        $query = $builder->get();
        $peserta = $query->getResult();

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=Data_Peserta_Ujian_" . ($bulan ? $bulan . "_" : "") . ($tahun ? $tahun : "Semua") . ".xls");
        header("Cache-Control: max-age=0");

        echo '<table border="1">';
        echo '<tr>';
        echo '<th style="background-color:#009ef7; color:#ffffff;">Nama</th>';
        echo '<th style="background-color:#009ef7; color:#ffffff;">No WhatsApp</th>';
        echo '<th style="background-color:#009ef7; color:#ffffff;">Email</th>';
        echo '</tr>';

        if (empty($peserta)) {
            echo '<tr><td colspan="3" style="text-align:center;">Tidak ada data pada periode ini</td></tr>';
        } else {
            foreach ($peserta as $p) {
                // Bersihkan spasi di awal/akhir
                $no_hp = trim($p->hp);

                // Jika nomor hp kosong, null, atau hanya bernilai '0' tunggal, lewati baris ini
                if (empty($no_hp) || $no_hp === '0') {
                    continue;
                }

                // Mengubah angka 0 di depan menjadi 62
                if (substr($no_hp, 0, 1) === '0') {
                    $no_hp = '62' . substr($no_hp, 1);
                }

                echo '<tr>';
                echo '<td>' . esc(ucwords(strtolower($p->nama_siswa))) . '</td>';
                echo '<td style="mso-number-format:\'\@\';">' . esc($no_hp) . '</td>';
                echo '<td>' . esc($p->email) . '</td>';
                echo '</tr>';
            }
        }

        echo '</table>';
        exit;
    }
}

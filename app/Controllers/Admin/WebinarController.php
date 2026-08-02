<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\WebinarSesiModel;

class WebinarController extends BaseController
{
    protected $webinarSesiModel;
    protected $detailPaketModel;
    protected $paketModel;

    public function __construct()
    {
        $this->webinarSesiModel = new WebinarSesiModel();
        $this->detailPaketModel = new \App\Models\DetailPaketModel();
        $this->paketModel = new \App\Models\PaketModel();
    }

    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'List Webinar Sesi', 'url' => '#'],
        ];

        // Mengirimkan seluruh data sesi untuk pilihan multi-select sesi bonus/gratis di form
        $data['allSesi'] = $this->webinarSesiModel->findAll();

        return view('admin/webinar/list', $data);
    }

    public function datatables()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('auth');
        }

        $request = $this->request;
        $postData = $request->getPost();

        $draw = isset($postData['draw']) ? (int)$postData['draw'] : 1;
        $start = isset($postData['start']) ? (int)$postData['start'] : 0;
        $rowperpage = isset($postData['length']) ? (int)$postData['length'] : 10;
        $searchValue = $postData['search']['value'] ?? '';

        $builder = $this->webinarSesiModel;

        $totalRecords = $builder->countAllResults(false);

        if ($searchValue != '') {
            $builder->like('nama_sesi', $searchValue);
        }

        $totalRecordwithFilter = $builder->countAllResults(false);

        $records = $builder->orderBy('id_sesi', 'DESC')
            ->limit($rowperpage, $start)
            ->get()
            ->getResult();

        $currentDateTime = date('Y-m-d H:i:s');
        $data = [];

        foreach ($records as $record) {
            $id_encrypt = encrypt_url($record->id_sesi);

            // Penentuan Status Sesi Berdasarkan Waktu
            if ($record->waktu_selesai < $currentDateTime) {
                $statusHtml = '<span class="badge badge-light-secondary fw-bold px-3 py-2"><i class="ki-outline ki-check fs-7 me-1"></i> Selesai</span>';
            } elseif ($record->waktu_mulai <= $currentDateTime && $record->waktu_selesai >= $currentDateTime) {
                $statusHtml = '<span class="badge badge-light-success fw-bold px-3 py-2"><span class="bullet bullet-dot bg-success me-1"></span> Berjalan</span>';
            } else {
                $statusHtml = '<span class="badge badge-light-primary fw-bold px-3 py-2"><span class="bullet bullet-dot bg-primary me-1"></span> Akan Datang</span>';
            }

            // Parsing JSON link_zoom
            $zoomLinks = json_decode($record->link_zoom, true) ?? [];
            $zoomHtml = '';
            foreach ($zoomLinks as $zl) {
                $zoomHtml .= '<a href="' . esc($zl) . '" target="_blank" class="badge badge-light-primary mb-1 me-1 text-truncate" style="max-width:150px;"><i class="ki-duotone ki-video fs-6 me-1"></i> Zoom Link</a>';
            }

            // Parsing JSON link_youtube
            $ytLinks = json_decode($record->link_youtube, true) ?? [];
            $ytHtml = '';
            foreach ($ytLinks as $yl) {
                $ytHtml .= '<a href="' . esc($yl) . '" target="_blank" class="badge badge-light-danger mb-1 me-1 text-truncate" style="max-width:150px;"><i class="ki-duotone ki-youtube fs-6 me-1"></i> YT Link</a>';
            }

            // Parsing Sesi Bonus / Gratis Terkait
            $bonusIds = json_decode($record->sesi_gratis, true) ?? [];
            $bonusHtml = '<div class="d-flex flex-wrap gap-1" style="max-width: 250px; white-space: normal;">';
            if (!empty($bonusIds)) {
                $bonusSessions = $this->webinarSesiModel->whereIn('id_sesi', $bonusIds)->findAll();
                foreach ($bonusSessions as $bs) {
                    $bonusHtml .= '<span class="badge badge-light-info text-break text-start" style="white-space: normal;">' . esc($bs['nama_sesi']) . '</span>';
                }
            } else {
                $bonusHtml .= '<span class="text-muted fs-7">Tidak ada</span>';
            }
            $bonusHtml .= '</div>';

            $opsi = '
            <div class="d-flex justify-content-end">
                <a href="javascript:void(0)" 
                    data-id="' . $id_encrypt . '" 
                    class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 edit-webinar"
                    data-bs-toggle="tooltip" 
                    data-bs-placement="top" 
                    title="Edit Webinar Sesi">
                    <i class="ki-duotone ki-pencil fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </a>
            </div>';

            $data[] = [
                "nama_sesi"    => '<div class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6 text-break" style="white-space: normal; word-break: break-word;">' . esc($record->nama_sesi) . '</div>',
                "waktu"        => '<div class="fs-7 text-muted text-break" style="white-space: normal;"><b>Mulai:</b> ' . esc($record->waktu_mulai) . '<br><b>Selesai:</b> ' . esc($record->waktu_selesai) . '</div>',
                "harga_sesi"   => '<span class="fw-bold text-success text-break">Rp ' . number_format($record->harga_sesi, 0, ',', '.') . '</span>',
                "status"       => $statusHtml,
                "sesi_gratis"  => $bonusHtml,
                "link_zoom"    => '<div class="d-flex flex-wrap text-break">' . ($zoomHtml ?: '<span class="text-muted fs-7">Tidak ada</span>') . '</div>',
                "link_youtube" => '<div class="d-flex flex-wrap text-break">' . ($ytHtml ?: '<span class="text-muted fs-7">Tidak ada</span>') . '</div>',
                "opsi"         => $opsi
            ];
        }

        $response = [
            "draw" => $draw,
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "data" => $data,
            csrf_token() => csrf_hash()
        ];

        return $this->response->setJSON($response);
    }

    public function store()
    {
        try {
            $zoomInput = $this->request->getVar('link_zoom');
            $ytInput = $this->request->getVar('link_youtube');
            $sesiGratisInput = $this->request->getVar('sesi_gratis') ?? [];

            $zoomArray = array_filter(array_map('trim', preg_split('/[\r\n,]+/', $zoomInput)));
            $ytArray = array_filter(array_map('trim', preg_split('/[\r\n,]+/', $ytInput)));

            $data = [
                'nama_sesi'      => $this->request->getVar('nama_sesi'),
                'deskripsi_sesi' => $this->request->getVar('deskripsi_sesi'),
                'waktu_mulai'    => $this->request->getVar('waktu_mulai'),
                'waktu_selesai'  => $this->request->getVar('waktu_selesai'),
                'harga_sesi'     => $this->request->getVar('harga_sesi'),
                'link_zoom'      => json_encode(array_values($zoomArray)),
                'link_youtube'   => json_encode(array_values($ytArray)),
                'sesi_gratis'    => json_encode(array_values($sesiGratisInput)),
            ];

            if ($this->webinarSesiModel->insert($data)) {
                return redirect()->to('sw-admin/webinar')->with('success', 'Webinar sesi berhasil disimpan');
            } else {
                return redirect()->to('sw-admin/webinar')->with('error', 'Gagal menyimpan data');
            }
        } catch (\Exception $e) {
            return redirect()->to('sw-admin/webinar')->with('error', $e->getMessage());
        }
    }

    public function edit()
    {
        if ($this->request->isAJAX()) {
            try {
                $id_encrypted = $this->request->getVar('id_sesi');
                $id_decrypted = decrypt_url($id_encrypted);

                $record = $this->webinarSesiModel->find($id_decrypted);

                if ($record) {
                    $zoomArray = json_decode($record['link_zoom'], true) ?? [];
                    $ytArray = json_decode($record['link_youtube'], true) ?? [];
                    $sesiGratisArray = json_decode($record['sesi_gratis'], true) ?? [];

                    $record['link_zoom_text'] = implode("\n", $zoomArray);
                    $record['link_youtube_text'] = implode("\n", $ytArray);
                    $record['sesi_gratis_array'] = $sesiGratisArray;
                    $record['token'] = csrf_hash();

                    return $this->response->setJSON($record);
                }
            } catch (\Exception $e) {
                return $this->response->setStatusCode(500)->setJSON(['error' => $e->getMessage()]);
            }
        }
    }

    public function update()
    {
        try {
            $id = $this->request->getVar('id_sesi');

            $zoomInput = $this->request->getVar('link_zoom');
            $ytInput = $this->request->getVar('link_youtube');
            $sesiGratisInput = $this->request->getVar('sesi_gratis') ?? [];

            $zoomArray = array_filter(array_map('trim', preg_split('/[\r\n,]+/', $zoomInput)));
            $ytArray = array_filter(array_map('trim', preg_split('/[\r\n,]+/', $ytInput)));

            $this->webinarSesiModel->update($id, [
                'nama_sesi'      => $this->request->getVar('nama_sesi'),
                'deskripsi_sesi' => $this->request->getVar('deskripsi_sesi'),
                'waktu_mulai'    => $this->request->getVar('waktu_mulai'),
                'waktu_selesai'  => $this->request->getVar('waktu_selesai'),
                'harga_sesi'     => $this->request->getVar('harga_sesi'),
                'link_zoom'      => json_encode(array_values($zoomArray)),
                'link_youtube'   => json_encode(array_values($ytArray)),
                'sesi_gratis'    => json_encode(array_values($sesiGratisInput)),
            ]);
            

            // =========================================================================
            // TAMBAHAN LOGIKA UPDATE OTOMATIS HARGA PAKET
            // =========================================================================
            $db = \Config\Database::connect();

            // 1. Cari semua paket yang terkait dengan id_sesi yang baru saja diubah
            $affectedPackages = $db->table('detail_paket')
                                   ->select('idpaket')
                                   ->where('id_sesi', $id)
                                   ->get()
                                   ->getResult();

            // 2. Looping semua paket yang terdampak
            foreach ($affectedPackages as $paket) {
                
                // Hitung total harga_sesi untuk idpaket ini (Relasi: detail_paket -> webinar_sesi)
                $totalHargaRow = $db->table('detail_paket')
                                    ->selectSum('webinar_sesi.harga_sesi', 'total_harga')
                                    ->join('webinar_sesi', 'webinar_sesi.id_sesi = detail_paket.id_sesi')
                                    ->where('detail_paket.idpaket', $paket->idpaket)
                                    ->get()
                                    ->getRow();

                $totalHargaBaru = $totalHargaRow->total_harga ?? 0;

                // 3. Update field nominal_paket di tabel paket dengan total harga yang baru
                $db->table('paket')
                   ->where('idpaket', $paket->idpaket)
                   ->update(['nominal_paket' => $totalHargaBaru]);
            }
            // =========================================================================
            // AKHIR TAMBAHAN LOGIKA
            // =========================================================================


            return redirect()->to('sw-admin/webinar')->with('success', 'Webinar sesi berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->to('sw-admin/webinar')->with('error', 'Gagal mengubah data');
        }
    }
}
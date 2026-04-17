<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;


class DiskonController extends BaseController
{
    protected $diskonModel;
    public function __construct()
    {
        $this->diskonModel = new \App\Models\DiskonModel();
    }

    // --- DISKON ---

    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'List Diskon', 'url' => '#'],
        ];

        // Data 'diskon' tidak lagi dipanggil di sini karena menggunakan Server-Side
        return view('admin/diskon/list', $data);
    }

    /**
     * Method untuk menangani Request DataTables Server-Side
     */
    public function datatables()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('auth');
        }

        $request = $this->request;
        $postData = $request->getPost();

        // Parameter DataTables - Tambahkan (int) untuk memastikan tipe datanya benar
        $draw = isset($postData['draw']) ? (int)$postData['draw'] : 1;
        $start = isset($postData['start']) ? (int)$postData['start'] : 0;
        $rowperpage = isset($postData['length']) ? (int)$postData['length'] : 10; // Perbaikan di sini
        $searchValue = $postData['search']['value'] ?? '';

        $builder = $this->diskonModel;

        $totalRecords = $builder->countAllResults(false);

        if ($searchValue != '') {
            $builder->like('nama', $searchValue);
        }

        $totalRecordwithFilter = $builder->countAllResults(false);

        // Sekarang limit() akan menerima integer, bukan string
        $records = $builder->orderBy('iddiskon', 'DESC')
            ->limit($rowperpage, $start)
            ->get()
            ->getResult();

        $data = [];
        foreach ($records as $record) {
            // Enkripsi ID
            $id_encrypt = encrypt_url($record->iddiskon);

            // Styling Diskon (Badge Soft)
            // Menggunakan warna yang berbeda berdasarkan besaran diskon bisa menjadi ide bagus
            $diskon_val = (int)$record->diskon;
            $badge_color = ($diskon_val > 50) ? 'badge-light-danger' : 'badge-light-success';

            $diskon_display = '<span class="badge ' . $badge_color . ' fw-bold fs-7">' . $diskon_val . '%</span>';

            // Opsi: Menggunakan Button Icon yang lebih bersih atau link bergaya modern
            $opsi = '
            <div class="d-flex justify-content-end">
                <a href="javascript:void(0)" 
                    data-diskon="' . $id_encrypt . '" 
                    class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 edit-diskon"
                    data-bs-toggle="tooltip" 
                    data-bs-placement="top" 
                    title="Edit Diskon">
                    <i class="ki-duotone ki-pencil fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </a>
            </div>';

            $data[] = [
                "nama"   => '<div class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">' . $record->nama . '</div>',
                "diskon" => $diskon_display,
                "opsi"   => $opsi
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
            $data_diskon = [
                'nama'   => $this->request->getVar('nama'),
                'diskon' => $this->request->getVar('diskon'),
            ];

            if ($this->diskonModel->insert($data_diskon)) {
                return redirect()->to('sw-admin/diskon')->with('success', 'Data diskon berhasil disimpan');
            } else {
                return redirect()->to('sw-admin/diskon')->with('error', 'Gagal menyimpan data');
            }
        } catch (\Exception $e) {
            return redirect()->to('sw-admin/diskon')->with('error', $e->getMessage());
        }
    }

    public function edit()
    {
        if ($this->request->isAJAX()) {
            try {
                $id_encrypted = $this->request->getVar('iddiskon');
                $id_decrypted = decrypt_url($id_encrypted);

                $data_diskon = $this->diskonModel->find($id_decrypted);

                if ($data_diskon) {
                    // Sertakan token baru untuk keamanan form edit
                    $data_diskon['token'] = csrf_hash();
                    return $this->response->setJSON($data_diskon);
                }
            } catch (\Exception $e) {
                return $this->response->setStatusCode(500)->setJSON(['error' => $e->getMessage()]);
            }
        }
    }

    public function update()
    {
        try {
            $id = $this->request->getVar('iddiskon'); // ID ini biasanya hidden field yang tidak di-encrypt saat di form

            $this->diskonModel->update($id, [
                'nama'   => $this->request->getVar('nama'),
                'diskon' => $this->request->getVar('diskon'),
            ]);

            return redirect()->to('sw-admin/diskon')->with('success', 'Diskon berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->to('sw-admin/diskon')->with('error', 'Gagal mengubah data');
        }
    }

    // --- END DISKON ---
}

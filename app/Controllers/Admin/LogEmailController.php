<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class LogEmailController extends BaseController
{
    protected $db;

    public function __construct()
    {
        // Inisialisasi database builder
        $this->db = \Config\Database::connect();
    }

    /**
     * Menampilkan View Halaman Utama Log Email
     */
    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'List Log Email', 'url' => '#'],
        ];

        // Sesuaikan path view Anda di sini
        return view('admin/log_email/list', $data); 
    }

    /**
     * Menangani Request DataTables Server-Side
     */
    public function datatables()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->db->table('log_emails');

            // 1. Setup Kolom Pencarian & Pengurutan
            $column_search = ['penerima', 'subjek', 'status', 'error_message'];
            // Indeks urutan kolom harus sesuai dengan <th> di view
            $column_order  = ['created_at', 'penerima', 'subjek', 'status', 'error_message', null];

            // 2. Logika Pencarian (Search)
            $searchValue = $this->request->getPost('search')['value'];
            if (!empty($searchValue)) {
                $builder->groupStart();
                foreach ($column_search as $i => $item) {
                    if ($i === 0) {
                        $builder->like($item, $searchValue);
                    } else {
                        $builder->orLike($item, $searchValue);
                    }
                }
                $builder->groupEnd();
            }

            // Simpan query untuk menghitung total data setelah difilter
            $builderFiltered = clone $builder;
            $recordsFiltered = $builderFiltered->countAllResults(false);

            // 3. Logika Pengurutan (Order)
            $order = $this->request->getPost('order');
            if (isset($order)) {
                $builder->orderBy($column_order[$order['0']['column']], $order['0']['dir']);
            } else {
                // Default order by tanggal terbaru
                $builder->orderBy('created_at', 'DESC');
            }

            // 4. Logika Paging (Limit & Offset)
            $length = $this->request->getPost('length');
            $start  = $this->request->getPost('start');
            if ($length != -1) {
                $builder->limit($length, $start);
            }

            $results = $builder->get()->getResult();
            $recordsTotal = $this->db->table('log_emails')->countAllResults();

            // 5. Format Data untuk JSON DataTables
            $data = [];
            foreach ($results as $row) {
                $nestedData = [];
                
                // Format tanggal (misal: 12 Aug 2026 14:30)
                $nestedData['created_at']    = date('d M Y H:i', strtotime($row->created_at));
                $nestedData['penerima']      = $row->penerima;
                $nestedData['subjek']        = $row->subjek;
                $nestedData['status']        = $row->status; 
                $nestedData['error_message'] = $row->error_message;
                $data[] = $nestedData;
            }

            // 6. Return Data JSON beserta CSRF Token baru
            $json_data = [
                "draw"            => intval($this->request->getPost('draw')),
                "recordsTotal"    => intval($recordsTotal),
                "recordsFiltered" => intval($recordsFiltered),
                "data"            => $data,
                csrf_token()      => csrf_hash() // Update token CSRF
            ];

            return $this->response->setJSON($json_data);
        }
    }

    /**
     * Logika untuk menghapus data log yang usianya lebih dari 3 bulan
     */
    public function deleteOld()
    {
        if ($this->request->isAJAX()) {
            
            // Hitung timestamp untuk 3 bulan ke belakang dari hari ini
            $tigaBulanLalu = date('Y-m-d H:i:s', strtotime('-3 months'));

            // Hapus di mana created_at lebih kecil (lebih tua) dari 3 bulan lalu
            $hapus = $this->db->table('log_emails')
                              ->where('created_at <', $tigaBulanLalu)
                              ->delete();

            if ($hapus) {
                // Berhasil dihapus
                $response = [
                    'status'  => 'success',
                    'message' => 'Log email yang lebih tua dari 3 bulan berhasil dibersihkan!',
                    'token'   => csrf_hash() // Kembalikan token CSRF baru
                ];
            } else {
                // Gagal dihapus (atau query bermasalah)
                $response = [
                    'status'  => 'error',
                    'message' => 'Terjadi kesalahan saat menghapus log email.',
                    'token'   => csrf_hash()
                ];
            }

            return $this->response->setJSON($response);
        }
    }
}
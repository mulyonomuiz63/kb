<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DetailVoucherModel;
use App\Models\MitraModel;
use App\Models\PaketModel;
use App\Models\VoucherModel;

class MitraController extends BaseController
{
    protected $mitraModel;
    protected $voucherModel;
    protected $paketModel;
    protected $detailVoucherModel;
    protected $transaksiModel;

    public function __construct()
    {
        $this->mitraModel = new MitraModel();
        $this->voucherModel = new VoucherModel();
        $this->paketModel = new PaketModel();
        $this->detailVoucherModel = new DetailVoucherModel();
        $this->transaksiModel = new \App\Models\TransaksiModel();
    }

    public function index()
    {
        $data = [
            'title' => 'List Mitra',
        ];
        return view('admin/mitra/list', $data);
    }

    public function datatable()
    {
        try {
            $request = \Config\Services::request();
            $builder = $this->mitraModel->builder(); // Mengambil instance builder asli

            // 1. Hitung Total Data Asli (Tanpa Filter)
            $totalData = $this->mitraModel->countAllResults(false);

            // 2. Logika Pencarian
            $searchValue = $request->getPost('search')['value'];
            if ($searchValue) {
                $builder->groupStart()
                    ->like('nama_mitra', $searchValue)
                    ->orLike('email', $searchValue)
                    ->groupEnd();
            }

            // 3. Hitung Total Setelah Filter
            // Kita gunakan countAllResults(false) agar builder tidak reset untuk query limit nanti
            $totalFiltered = $builder->countAllResults(false);

            // 4. Order (Opsional: Jika ingin mendukung klik sortir di kolom)
            $orderColumn = $request->getPost('order');
            if ($orderColumn) {
                $cols = ['idmitra', 'nama_mitra', 'email', 'komisi']; // Sesuaikan index kolom
                $builder->orderBy($cols[$orderColumn[0]['column']], $orderColumn[0]['dir']);
            }

            // 5. Limit & Offset
            $list = $builder->limit($request->getPost('length'), $request->getPost('start'))
                ->get()
                ->getResult();

            $data = [];
            $no = $request->getPost('start') + 1;

            foreach ($list as $m) {
                $row = [];
                $row[] = $no++;
                $row[] = '<div class="fw-bold text-dark">' . esc($m->nama_mitra) . '</div>';
                $row[] = esc($m->email);
                $row[] = '<span class="badge bg-light-success text-success">' . $m->komisi . '%</span>';

                // Tombol Aksi
                $row[] = '
                <div class="dropdown custom-dropdown text-center">
                    <a class="dropdown-toggle badge badge-primary border-0" href="#" role="button" id="dropdownMenu' . $m->idmitra . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow border-0" aria-labelledby="dropdownMenu' . $m->idmitra . '">
                        <a class="dropdown-item py-2" href="' . base_url('sw-admin/mitra/voucher/' . encrypt_url($m->idmitra)) . '">
                            <i class="bi bi-eye me-2 text-info"></i> Lihat Voucher
                        </a>
                        <a class="dropdown-item py-2 edit-mitra" href="javascript:void(0)" 
                        data-toggle="modal" 
                        data-target="#edit_mitra" 
                        data-mitra="' . encrypt_url($m->idmitra) . '">
                            <i class="bi bi-gear me-2 text-primary"></i> Pengaturan Mitra
                        </a>
                    </div>
                </div>';

                $data[] = $row;
            }

            return $this->response->setJSON([
                "draw"            => intval($request->getPost('draw')),
                "recordsTotal"    => $totalData,
                "recordsFiltered" => $totalFiltered,
                "data"            => $data,
                "csrfHash"        => csrf_hash()
            ]);
        } catch (\Exception $e) {
            // Jika error, kirim status 500 agar ditangkap console error AJAX
            return $this->response->setStatusCode(500)->setJSON([
                'error'   => $e->getMessage(),
                'csrfHash' => csrf_hash()
            ]);
        }
    }

    public function store()
    {
        // 2. Ambil Input (Array)
        $nama_mitra = $this->request->getVar('nama_mitra');
        $emails     = $this->request->getVar('email');
        $sandis     = $this->request->getVar('sandi');
        $komisis    = $this->request->getVar('komisi');

        $data_mitra = [];
        $email_duplikat = 0;

        if ($nama_mitra) {
            foreach ($nama_mitra as $index => $nama) {
                $email = $emails[$index];

                // 3. Cek apakah email sudah terdaftar di database
                // Menggunakan MitraModel langsung lebih disarankan
                $cek = $this->mitraModel->where('email', $email)->first();

                if (!$cek) {
                    $data_mitra[] = [
                        'nama_mitra'   => $nama,
                        'email'        => $email,
                        'password'     => password_hash($sandis[$index], PASSWORD_DEFAULT),
                        'role'         => 4,
                        'is_active'    => 1,
                        'date_created' => time(),
                        'avatar'       => 'default.jpg',
                        'komisi'       => str_replace(',', '.', $komisis[$index]),
                    ];
                } else {
                    $email_duplikat++;
                }
            }
        }

        // 4. Proses Insert Batch
        if (!empty($data_mitra)) {
            $sql = $this->mitraModel->insertBatch($data_mitra);

            if ($sql) {
                $pesan = "Data berhasil disimpan.";
                if ($email_duplikat > 0) {
                    $pesan .= " ($email_duplikat email dilewati karena sudah terdaftar)";
                }
                return redirect()->to('sw-admin/mitra')->with('success', $pesan);
            }
        }

        // 5. Jika gagal atau semua email duplikat
        $pesan_gagal = $email_duplikat > 0 ? "Gagal simpan: Semua email sudah terdaftar." : "Gagal menyimpan data.";
        return redirect()->to('sw-admin/mitra')->with('error', $pesan_gagal);
    }

    public function getMitraById()
    {
        // Hanya izinkan request via AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['message' => 'Direct access not allowed']);
        }

        try {
            // 1. Ambil & Dekripsi ID
            $id_encrypt = $this->request->getVar('idmitra');
            $id_mitra = decrypt_url($id_encrypt);

            if (!$id_mitra) {
                throw new \Exception('ID Mitra tidak valid atau sudah kedaluwarsa.');
            }

            // 2. Cari Data di Database
            $data_mitra = $this->mitraModel->asObject()->find($id_mitra);

            if (!$data_mitra) {
                throw new \Exception('Data mitra tidak ditemukan dalam sistem.');
            }

            // 3. Susun Response dengan CSRF Hash terbaru
            $response = [
                'status'    => 'success',
                'idmitra'   => $id_encrypt, // Kirim kembali ID yang terenkripsi untuk input hidden
                'nama_mitra' => $data_mitra->nama_mitra,
                'email'     => $data_mitra->email,
                'komisi'    => $data_mitra->komisi,
                'is_active' => $data_mitra->is_active, // Pastikan field sesuai nama di DB (is_active/active)
                'token'     => csrf_hash() // SANGAT PENTING: Untuk update token di sisi client
            ];

            return $this->response->setJSON($response);
        } catch (\Exception $e) {
            // Jika error, kirim status 500 agar ditangkap oleh console.error AJAX
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => $e->getMessage(),
                'token'   => csrf_hash()
            ]);
        }
    }

    public function update()
    {
        try {
            $id_encrypt = $this->request->getVar('idmitra');
            $idmitra = decrypt_url($id_encrypt);

            if (!$idmitra) {
                return redirect()->back()->with('error', 'ID Mitra tidak valid.');
            }

            $nama_mitra = $this->request->getVar('nama_mitra');
            $email      = $this->request->getVar('email');
            $active     = $this->request->getVar('active');
            $komisi     = $this->request->getVar('komisi');
            $sandi      = $this->request->getVar('sandi');

            $dataUpdate = [
                'nama_mitra' => $nama_mitra,
                'email'      => $email,
                'is_active'  => $active,
                'komisi'     => str_replace(',', '.', $komisi),
            ];

            if (!empty($sandi)) {
                $dataUpdate['password'] = password_hash($sandi, PASSWORD_DEFAULT);
            }

            $this->mitraModel->update($idmitra, $dataUpdate);

            // Menggunakan redirect with success sesuai permintaan
            return redirect()->to('sw-admin/mitra')->with('success', 'Data mitra berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    public function voucher($id)
    {
        $idmitra = decrypt_url($id);
        $data = [
            'title' => 'List Voucher Mitra',
            'parent_title' => 'Mitra',
            'parent_url'   => base_url('sw-admin/mitra'),
            'voucher' => $this->voucherModel->join('mitra', 'voucher.idmitra=mitra.idmitra')->where('voucher.idmitra', $idmitra)->orderBy('mitra.nama_mitra', 'asc')->orderBy('voucher.diskon_voucher', 'asc')->groupBy('voucher.kode_voucher')->get()->getResultObject(),
            'mitra' => $this->mitraModel->where('idmitra', $idmitra)->get()->getResultObject(),
            'paket' => $this->paketModel->get()->getResultObject(),
            'idmitra' => $id,
        ];

        return view('admin/voucher/list', $data);
    }

    public function getVoucher()
    {
        // 1. Pastikan hanya menerima request AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setBody('Direct access not allowed');
        }

        try {
            $kode_voucher = $this->request->getVar('id');

            // 2. Gunakan query yang lebih efisien (count saja sudah cukup)
            $exists = $this->voucherModel
                ->where('kode_voucher', $kode_voucher)
                ->countAllResults();

            // 3. Kembalikan JSON beserta CSRF Hash baru
            return $this->response->setJSON([
                'status' => $exists > 0 ? 1 : 0,
                'token'  => csrf_hash() // Sangat penting agar request selanjutnya tidak error
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'token'  => csrf_hash()
            ]);
        }
    }

    public function storeVoucher()
    {
        // 2. Persiapan Data Tanggal
        $tgl_mulai = date('Y-m-d');
        $tgl_exp_input = $this->request->getVar('tgl_exp');

        if (!empty($tgl_exp_input)) {
            $tgl_exp = date('Y-m-d', strtotime($tgl_exp_input));
        } else {
            // Default +7 hari jika kosong
            $tgl_exp = date('Y-m-d', strtotime('+7 days', strtotime($tgl_mulai)));
        }

        // 3. Mulai Database Transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Data untuk tabel Voucher
            $data_voucher = [
                'idmitra'        => $this->request->getVar('idmitra'),
                'diskon_voucher' => $this->request->getVar('diskon_voucher'),
                'kode_voucher'   => strtoupper($this->request->getVar('kode_voucher')),
                'tgl_aktif'      => $tgl_mulai,
                'tgl_exp'        => $tgl_exp,
                'status'         => $this->request->getVar('status')
            ];

            // Insert ke VoucherModel
            $this->voucherModel->insert($data_voucher);

            // Ambil ID Voucher yang baru saja masuk
            $idvoucher = $this->voucherModel->insertID();

            // Persiapan data untuk Detail Voucher (Batch Insert)
            $idpaket_array = $this->request->getVar('idpaket');
            $data_paket = [];

            if (!empty($idpaket_array)) {
                foreach ($idpaket_array as $idpaket) {
                    $data_paket[] = [
                        'idvoucher' => $idvoucher,
                        'idpaket'   => $idpaket,
                    ];
                }
                // Insert banyak data sekaligus
                $this->detailVoucherModel->insertBatch($data_paket);
            }

            // Selesaikan Transaksi
            $db->transComplete();

            if ($db->transStatus() === false) {
                // Jika transaksi gagal di tengah jalan
                throw new \Exception("Gagal menyimpan data ke database.");
            }

            // Persiapan Redirect dengan Flashdata Sukses
            $redirectURL = 'sw-admin/mitra/voucher/' . encrypt_url($this->request->getVar('idmitra'));

            return redirect()->to($redirectURL)->with('success', 'Data voucher berhasil disimpan!');
        } catch (\Exception $e) {
            // Jika terjadi error (Try Catch)
            $db->transRollback();

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function editVoucher()
    {
        if (session()->get('role') != 1) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Unauthorized']);
        }

        if ($this->request->isAJAX()) {
            try {
                $id_input = $this->request->getVar('idvoucher');
                $idvoucher = decrypt_url($id_input);

                $voucher = $this->voucherModel->find($idvoucher);

                if ($voucher) {
                    // Gabungkan data voucher, detail paket, dan token baru
                    $response = [
                        'status'     => 'success',
                        'token_baru' => csrf_hash(),
                        'voucher'    => $voucher, // Data utama voucher ada di sini
                        'paket'      => $this->detailVoucherModel->where('idvoucher', $idvoucher)->findAll()
                    ];

                    return $this->response->setJSON($response);
                } else {
                    return $this->response->setStatusCode(404)->setJSON(['error' => 'Data tidak ditemukan']);
                }
            } catch (\Exception $e) {
                return $this->response->setStatusCode(500)->setJSON(['error' => $e->getMessage()]);
            }
        }
        return redirect()->to('sw-admin/mitra');
    }
    public function updateVoucher()
    {
        if (!empty($this->request->getVar('tgl_exp'))) {
            $this->voucherModel->save([
                'idvoucher'     => $this->request->getVar('idvoucher'),
                'idmitra'        => $this->request->getVar('idmitra'),
                'diskon_voucher'        => $this->request->getVar('diskon_voucher'),
                'tgl_exp'        => date('Y-m-d', strtotime($this->request->getVar('tgl_exp'))),
                'status'        => $this->request->getVar('status')

            ]);
        } else {
            $this->voucherModel->save([
                'idvoucher'     => $this->request->getVar('idvoucher'),
                'idmitra'        => $this->request->getVar('idmitra'),
                'diskon_voucher'        => $this->request->getVar('diskon_voucher'),
                'status'        => $this->request->getVar('status')

            ]);
        }

        return redirect()->to('sw-admin/mitra/voucher/' . encrypt_url($this->request->getVar('idmitra')))->with('success', 'Data voucher berhasil disimpan!');
    }

    public function daftarPaket($id)
    {
        try {
            // 2. Dekripsi ID
            $idvoucher = decrypt_url($id);

            // Cek jika hasil dekripsi kosong/gagal
            if (!$idvoucher) {
                return redirect()->back()->with('error', 'ID Voucher tidak valid.');
            }

            // 3. Ambil Detail Voucher dengan Join
            // Perbaikan: 'esc' diganti menjadi 'ASC'
            $data['detailvoucher'] = $this->detailVoucherModel
                ->select('detail_voucher.*, a.kode_voucher, b.nama_paket')
                ->join('voucher a', 'detail_voucher.idvoucher = a.idvoucher')
                ->join('paket b', 'detail_voucher.idpaket = b.idpaket')
                ->where('detail_voucher.idvoucher', $idvoucher)
                ->orderBy('b.nama_paket', 'ASC')
                ->get()->getResultObject();

            // 4. Data Pendukung
            $data['idvoucher'] = $idvoucher;
            $data['id_raw']    = $id; // Tetap simpan ID terenkripsi jika butuh di view
            $data['paket']     = $this->paketModel->orderBy('nama_paket', 'ASC')->get()->getResultObject();

            // 5. Kirim ke View
            return view('admin/voucher/detail_voucher', $data);
        } catch (\Exception $e) {
            // Jika terjadi error sistem (misal table tidak ditemukan)
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function storeVoucherPaket()
    {
        $idvoucher = $this->request->getVar('idvoucher');
        $idpaket_array = $this->request->getVar('idpaket');

        if (empty($idpaket_array)) {
            return redirect()->back()->with('error', 'Pilih minimal satu paket.');
        }

        try {
            $data_paket = [];
            foreach ($idpaket_array as $idpaket) {
                // Cek apakah paket ini sudah ada di voucher tersebut (mencegah duplikat)
                $exists = $this->detailVoucherModel
                    ->where(['idvoucher' => $idvoucher, 'idpaket' => $idpaket])
                    ->first();

                if (!$exists) {
                    $data_paket[] = [
                        'idvoucher' => $idvoucher,
                        'idpaket'   => $idpaket
                    ];
                }
            }

            if (!empty($data_paket)) {
                $this->detailVoucherModel->insertBatch($data_paket);
                $pesan = "Data paket berhasil ditambahkan.";
            } else {
                $pesan = "Paket sudah terdaftar sebelumnya.";
            }

            return redirect()->to('sw-admin/mitra/daftar-paket/' . encrypt_url($idvoucher))
                ->with('success', $pesan);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function deleteVoucherPaket($id = '', $idvoucher_enc = null)
    {
        try {
            $iddetail = decrypt_url($id);
            $this->detailVoucherModel->delete($iddetail);

            return redirect()->to('sw-admin/mitra/daftar-paket/' . $idvoucher_enc)
                ->with('success', 'Paket berhasil dihapus dari voucher.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }

    public function detailKomisi($id)
    {
        try {
            $kode_voucher = decrypt_url($id);
            $data['transaksi'] = $this->transaksiModel
                ->select('transaksi.*, b.nama_siswa, b.email, c.nama_paket')
                ->join('detail_transaksi d', 'd.idtransaksi=transaksi.idtransaksi')
                ->join('siswa b', 'b.id_siswa = transaksi.idsiswa')
                ->join('paket c', 'c.idpaket = d.idpaket')
                ->join('voucher e', 'transaksi.kode_voucher = e.kode_voucher')
                ->where('transaksi.kode_voucher', $kode_voucher)
                ->where('transaksi.status', 'S')
                ->groupBY('transaksi.idtransaksi')
                ->orderBy('transaksi.status', 'esc')
                ->get()->getResultObject();

            $data['voucher'] = $this->voucherModel->join('mitra', 'voucher.idmitra=mitra.idmitra')->where('voucher.kode_voucher', $kode_voucher)->groupBy('voucher.kode_voucher')->get()->getRowObject();
            return view('admin/voucher/detail_komisi', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function validasiTransaksi()
    {
        // 1. Proteksi Role (Hanya Admin)
        if (session()->get('role') != 1) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Akses ditolak']);
        }

        // 2. Pastikan hanya menerima request AJAX
        if (!$this->request->isAJAX()) {
            return redirect()->to('auth');
        }

        try {
            // 3. Dekripsi ID
            $id_raw = $this->request->getVar('idtransaksi');
            $id_decrypted = decrypt_url($id_raw);

            if (!$id_decrypted) {
                throw new \Exception('ID Transaksi tidak valid atau gagal didekripsi.');
            }

            // 4. Ambil Data
            $data_transaksi = $this->transaksiModel->getById($id_decrypted);

            if ($data_transaksi) {
                // Injeksi token CSRF baru untuk sinkronisasi form modal
                $data_transaksi->token = csrf_hash();
                return $this->response->setJSON($data_transaksi);
            } else {
                return $this->response->setStatusCode(404)->setJSON([
                    'error' => 'Data transaksi tidak ditemukan',
                    'token' => csrf_hash()
                ]);
            }
        } catch (\Exception $e) {
            // 5. Tangani error secara elegan
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'token' => csrf_hash()
            ]);
        }
    }
}

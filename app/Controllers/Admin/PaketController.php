<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;


class PaketController extends BaseController
{
    protected $paketModel;
    protected $ujianMasterModel;
    protected $kelasModel;
    protected $mapelModel;
    protected $detailPaketModel;
    protected $reviewModel;
    protected $emailer;

    public function __construct()
    {
        $this->paketModel = new \App\Models\PaketModel();
        $this->ujianMasterModel = new \App\Models\UjianMasterModel();
        $this->kelasModel = new \App\Models\KelasModel();
        $this->mapelModel = new \App\Models\MapelModel();
        $this->detailPaketModel = new \App\Models\DetailPaketModel();
        $this->reviewModel = new \App\Models\ReviewModel();
        $this->emailer = new \App\Libraries\Emailer();
    }

    public function index()
    {

        try {
            $data['breadcrumbs'] = [
                ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
                ['title' => 'Data Paket', 'url' => '#'],
            ];
            // MASTER DATA
            $data['paket'] = $this->paketModel
                ->join('diskon b', 'b.iddiskon = paket.iddiskon', 'left')
                // Penulisan yang benar: null tanpa tanda kutip
                ->where('paket.deleted_at', null)
                ->orderBy('paket.sort_order', 'ASC')
                ->get()->getResultObject();

            $data['ujian'] = $this->ujianMasterModel->getAll();
            $data['kelas'] = $this->kelasModel->asObject()->find();

            return view('admin/paket/list', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    public function edit()
    {
        if ($this->request->isAJAX()) {
            try {
                $idRaw = $this->request->getVar('idpaket');
                $idDecrypted = decrypt_url($idRaw);

                if (!$idDecrypted) {
                    throw new \Exception('ID Paket tidak valid.');
                }

                $data_paket = $this->paketModel->asArray()->find($idDecrypted);

                if (!$data_paket) {
                    throw new \Exception('Data paket tidak ditemukan.');
                }

                $jenis_paket = json_decode($data_paket['jenis_paket'], true) ?? [];

                $data_paket['id_kelas']  = '';
                $data_paket['arr_ujian'] = [];
                $data_paket['arr_mapel'] = [];
                $data_paket['arr_sesi']  = []; // <-- INISIALISASI ARRAY SESI WEBINAR

                // Cek jika paket ini mengandung layanan brevet
                if (in_array('brevet', $jenis_paket)) {
                    $db = \Config\Database::connect();

                    // 1. Cari id_kelas dari relasi ujian_master (Lebih Akurat)
                    $kelasRow = $db->query("
                        SELECT ujian_master.kelas as id_kelas 
                        FROM detail_paket 
                        LEFT JOIN ujian_master 
                          ON (ujian_master.id_ujian = detail_paket.id_ujian OR ujian_master.mapel = detail_paket.id_mapel) 
                        WHERE detail_paket.idpaket = ? AND ujian_master.kelas IS NOT NULL 
                        LIMIT 1
                    ", [$idDecrypted])->getRow();

                    if ($kelasRow) {
                        $data_paket['id_kelas'] = $kelasRow->id_kelas;
                    }

                    // 2. Ambil Array ID Ujian
                    if ($data_paket['v_ujian'] === 'all') {
                        $data_paket['arr_ujian'] = ['all'];
                    } else {
                        $ujianRows = $db->table('detail_paket')
                            ->where('idpaket', $idDecrypted)->where('id_ujian !=', 0)
                            ->select('id_ujian')->get()->getResultArray();
                        $data_paket['arr_ujian'] = array_column($ujianRows, 'id_ujian');
                    }

                    // 3. Ambil Array ID Mapel
                    if ($data_paket['v_materi'] === 'all') {
                        $data_paket['arr_mapel'] = ['all'];
                    } else {
                        $mapelRows = $db->table('detail_paket')
                            ->where('idpaket', $idDecrypted)->where('id_mapel !=', 0)
                            ->select('id_mapel')->get()->getResultArray();
                        $data_paket['arr_mapel'] = array_column($mapelRows, 'id_mapel');
                    }
                }

                // =====================================================
                // TAMBAHAN: AMBIL DATA ID SESI WEBINAR UNTUK EDIT
                // =====================================================
                if (in_array('webinar', $jenis_paket)) {
                    $db = \Config\Database::connect();
                    $sesiRows = $db->table('detail_paket')
                        ->where('idpaket', $idDecrypted)
                        ->where('id_sesi !=', 0)
                        ->where('id_sesi IS NOT NULL')
                        ->select('id_sesi')
                        ->get()
                        ->getResultArray();
                    
                    $data_paket['arr_sesi'] = array_column($sesiRows, 'id_sesi');
                }
                // =====================================================

                // Tambahkan Token CSRF baru ke dalam array respons
                $data_paket[csrf_token()] = csrf_hash();

                return $this->response->setJSON($data_paket);
            } catch (\Exception $e) {
                return $this->response->setStatusCode(500)->setJSON([
                    'error' => $e->getMessage(),
                    csrf_token() => csrf_hash()
                ]);
            }
        }

        return $this->response->setStatusCode(404);
    }

    public function store()
    {
        try {
            // 1. Tangkap dan Bersihkan Input Jenis Paket
            $jenis_paket_array = $this->request->getVar('jenis_paket');
            if (!is_array($jenis_paket_array)) {
                $jenis_paket_array = [];
            }
            // Bersihkan duplikat & nilai kosong, lalu jadikan JSON
            $jenis_paket_bersih = array_values(array_unique(array_filter($jenis_paket_array)));
            $json_jenis_paket = json_encode($jenis_paket_bersih);

            // 2. Variabel Penampung Logika Khusus Brevet
            $raw_id_kelas = $this->request->getVar('id_kelas');

            // Pastikan raw_id_ujian dan raw_id_mapel selalu berbentuk Array
            $raw_id_ujian = $this->request->getVar('id_ujian') ?? [];
            if (!is_array($raw_id_ujian)) $raw_id_ujian = [$raw_id_ujian];

            $raw_id_mapel = $this->request->getVar('id_mapel') ?? [];
            if (!is_array($raw_id_mapel)) $raw_id_mapel = [$raw_id_mapel];

            $v_ujian = "0";
            $v_materi = "0";

            // 3. Validasi Khusus Jika Memilih Layanan Brevet
            if (in_array('brevet', $jenis_paket_bersih)) {
                // Cek apakah array kosong (hanya berisi nilai kosong/string kosong)
                $is_ujian_empty = empty(array_filter($raw_id_ujian));
                $is_mapel_empty = empty(array_filter($raw_id_mapel));

                if ($is_ujian_empty && $is_mapel_empty) {
                    return redirect()->to('sw-admin/paket')->with('info', 'Salah satu ujian atau mapel harus diisi untuk paket Brevet AB.');
                }

                // PERBAIKAN: Kalkulasi flag ujian & materi menggunakan in_array()
                $v_ujian  = in_array("all", $raw_id_ujian) ? "all" : ($is_ujian_empty ? "0" : "1");
                $v_materi = in_array("all", $raw_id_mapel) ? "all" : ($is_mapel_empty ? "0" : "1");
            }

            // 4. Proses Upload Gambar
            $file = $this->request->getFile('avatar');
            $newName = null;

            if ($file && $file->isValid() && !$file->hasMoved()) {
                $path = FCPATH . 'assets-landing/images/paket';
                $thumbnail_path = $path . '/thumbnails';
                $newName = $file->getRandomName();

                if ($file->move($path, $newName)) {
                    // Resize dan simpan di folder thumbnails
                    \Config\Services::image()
                        ->withFile($path . '/' . $newName)
                        ->resize(1012, 1012, true, 'auto')
                        ->save($thumbnail_path . '/' . $newName, 80);

                    // Hapus file asli yang terlalu besar
                    if (file_exists($path . '/' . $newName)) {
                        unlink($path . '/' . $newName);
                    }
                }
            }

            // 5. Mulai Transaksi Database (Anti Data Setengah Masuk)
            $db = \Config\Database::connect();
            $db->transStart();

            // Insert Tabel Utama (Paket)
            $data_paket = [
                'iddiskon'      => $this->request->getVar('iddiskon'),
                'nama_paket'    => $this->request->getVar('nama_paket'),
                'tagline'       => $this->request->getVar('tagline'),
                'jumlah_bulan'  => $this->request->getVar('jumlah_bulan') ?? 12,
                'nominal_paket' => $this->request->getVar('nominal_paket'),
                'file'          => $newName,
                'status'        => $this->request->getVar('status'),
                'v_ujian'       => $v_ujian,
                'v_materi'      => $v_materi,
                'deskripsi'     => $this->request->getVar('deskripsi'),
                'komisi'        => $this->request->getVar('komisi') ?? 0,
                'jenis_paket'   => $json_jenis_paket, // Disimpan sebagai JSON
            ];

            $this->paketModel->insert($data_paket);
            $id_paket = $this->paketModel->insertID();

            // 6. Insert Tabel Detail (HANYA JIKA LAYANAN BREVET DIPILIH)
            if (in_array('brevet', $jenis_paket_bersih) && !empty($raw_id_kelas)) {

                // Ambil semua data ujian dan mapel berdasarkan kelas yang dipilih
                $data_master = $this->ujianMasterModel
                    ->select('ujian_master.id_ujian, ujian_master.mapel')
                    ->where('ujian_master.kelas', $raw_id_kelas)
                    ->groupBy('ujian_master.id_ujian')
                    ->get()->getResultObject();

                // ==========================================================
                // 1. Ekstrak Data Ujian
                // ==========================================================
                $arr_u = [];
                // PERBAIKAN: Cek apakah ada kata "all" di dalam array
                if (in_array('all', $raw_id_ujian)) {
                    foreach ($data_master as $row) {
                        $arr_u[] = $row->id_ujian;
                    }
                    $arr_u = array_values(array_unique($arr_u));
                } else {
                    // Filter array agar id yang kosong tidak ikut terproses
                    $arr_u = array_values(array_filter($raw_id_ujian));
                }

                // ==========================================================
                // 2. Ekstrak Data Mapel
                // ==========================================================
                $arr_m = [];
                // PERBAIKAN: Cek apakah ada kata "all" di dalam array
                if (in_array('all', $raw_id_mapel)) {
                    foreach ($data_master as $row) {
                        $arr_m[] = $row->mapel;
                    }
                    $arr_m = array_values(array_unique($arr_m));
                } else {
                    // Filter array agar id yang kosong tidak ikut terproses
                    $arr_m = array_values(array_filter($raw_id_mapel));
                }

                // ==========================================================
                // 3. Proses Penggabungan Sejajar (Index Pairing)
                // ==========================================================
                $detail_batch = [];
                $max_count = max(count($arr_u), count($arr_m));

                $id_paket_target = $id_paket;

                for ($i = 0; $i < $max_count; $i++) {
                    $detail_batch[] = [
                        'idpaket'  => $id_paket_target,
                        'id_ujian' => $arr_u[$i] ?? 0,
                        'id_mapel' => $arr_m[$i] ?? 0
                    ];
                }

                if (!empty($detail_batch)) {
                    $this->detailPaketModel->insertBatch($detail_batch);
                }
            }

            // Pastikan Anda sudah menangkap input id_sesi dari form sebelumnya
            $raw_id_sesi = $this->request->getPost('id_sesi'); // Berupa Array dari multi-select

            // Mengecek jika paket adalah webinar dan id_sesi ada isinya
            if (in_array('webinar', $jenis_paket_bersih) && !empty($raw_id_sesi)) {

                // Inisialisasi/kosongkan array penampung agar tidak bentrok dengan data lain
                $detail_batch = [];

                // Looping semua id_sesi yang dipilih oleh admin
                foreach ($raw_id_sesi as $sesi) {
                    $detail_batch[] = [
                        'idpaket'  => $id_paket, // Variabel ID Paket yang baru saja disimpan
                        'id_ujian' => 0,
                        'id_mapel' => 0,
                        'id_sesi'  => $sesi      // Masukkan id_sesi dari hasil looping
                    ];
                }

                // Jika array penampung sudah terisi, eksekusi insertBatch
                if (!empty($detail_batch)) {
                    $this->detailPaketModel->insertBatch($detail_batch);
                }
            }

            // Selesai Transaksi
            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception("Gagal melakukan transaksi database.");
            }

            return redirect()->to('sw-admin/paket')->with('success', 'Data paket berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->to('sw-admin/paket')->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }


    // ==========================================================
    // FUNGSI UPDATE DATA PAKET
    // ==========================================================
    public function update()
    {
        try {
            $idpaket_enkripsi = $this->request->getVar('idpaket');
            $idpaket = $idpaket_enkripsi;

            if (!$idpaket) {
                return redirect()->to('sw-admin/paket')->with('error', 'ID Paket tidak valid.');
            }

            $jenis_paket_array = $this->request->getVar('jenis_paket');
            if (!is_array($jenis_paket_array)) {
                $jenis_paket_array = [];
            }
            $jenis_paket_bersih = array_values(array_unique(array_filter($jenis_paket_array)));
            $json_jenis_paket = json_encode($jenis_paket_bersih);

            $file = $this->request->getFile('avatar');
            $newName = $this->request->getVar('gambar_lama');

            if ($file && $file->isValid() && !$file->hasMoved()) {
                $path = FCPATH . 'assets-landing/images/paket';
                $thumbnail_path = $path . '/thumbnails';
                $newName = $file->getRandomName();

                if ($file->move($path, $newName)) {
                    \Config\Services::image()
                        ->withFile($path . '/' . $newName)
                        ->resize(1012, 1012, true, 'auto')
                        ->save($thumbnail_path . '/' . $newName, 80);

                    if (file_exists($path . '/' . $newName)) {
                        unlink($path . '/' . $newName);
                    }

                    $gambar_lama = $this->request->getVar('gambar_lama');
                    if (!empty($gambar_lama) && file_exists($thumbnail_path . '/' . $gambar_lama)) {
                        unlink($thumbnail_path . '/' . $gambar_lama);
                    }
                }
            }

            $db = \Config\Database::connect();
            $db->transStart();

            $data_paket = [
                'iddiskon'      => $this->request->getVar('iddiskon'),
                'nama_paket'    => $this->request->getVar('nama_paket'),
                'tagline'       => $this->request->getVar('tagline'),
                'nominal_paket' => $this->request->getVar('nominal_paket'),
                'file'          => $newName,
                'status'        => $this->request->getVar('status'),
                'deskripsi'     => $this->request->getVar('deskripsi'),
                'komisi'        => $this->request->getVar('komisi') ?? 0,
                'jenis_paket'   => $json_jenis_paket,
            ];

            $this->paketModel->update($idpaket, $data_paket);

            // Bersihkan seluruh detail lama paket ini terlebih dahulu sebelum dimasukkan ulang sesuai pilihan terbaru
            $this->detailPaketModel->where('idpaket', $idpaket)->delete();

            $v_u = '0';
            $v_m = '0';

            // 1. Proses Brevet jika dipilih
            if (in_array('brevet', $jenis_paket_bersih)) {
                $raw_id_kelas = $this->request->getVar('id_kelas');
                $raw_id_ujian = $this->request->getVar('id_ujian') ?? [];
                if (!is_array($raw_id_ujian)) $raw_id_ujian = [$raw_id_ujian];

                $raw_id_mapel = $this->request->getVar('id_mapel') ?? [];
                if (!is_array($raw_id_mapel)) $raw_id_mapel = [$raw_id_mapel];

                if (!empty($raw_id_kelas)) {
                    $is_ujian_empty = empty(array_filter($raw_id_ujian));
                    $is_mapel_empty = empty(array_filter($raw_id_mapel));

                    $v_u = in_array("all", $raw_id_ujian) ? "all" : ($is_ujian_empty ? "0" : "1");
                    $v_m = in_array("all", $raw_id_mapel) ? "all" : ($is_mapel_empty ? "0" : "1");

                    $data_master = [];
                    if (in_array('all', $raw_id_ujian) || in_array('all', $raw_id_mapel)) {
                        $data_master = $this->ujianMasterModel
                            ->select('ujian_master.id_ujian, ujian_master.mapel')
                            ->join('mapel', 'mapel.id_mapel=ujian_master.mapel')
                            ->where('ujian_master.kelas', $raw_id_kelas)
                            ->groupBy('ujian_master.id_ujian')
                            ->get()->getResultObject();
                    }

                    $arr_u = [];
                    if (in_array('all', $raw_id_ujian)) {
                        foreach ($data_master as $row) {
                            $arr_u[] = $row->id_ujian;
                        }
                        $arr_u = array_values(array_unique($arr_u));
                    } else {
                        $arr_u = array_values(array_filter($raw_id_ujian));
                    }

                    $arr_m = [];
                    if (in_array('all', $raw_id_mapel)) {
                        foreach ($data_master as $row) {
                            $arr_m[] = $row->mapel;
                        }
                        $arr_m = array_values(array_unique($arr_m));
                    } else {
                        $arr_m = array_values(array_filter($raw_id_mapel));
                    }

                    $detail_batch = [];
                    $max_count = max(count($arr_u), count($arr_m));

                    for ($i = 0; $i < $max_count; $i++) {
                        $detail_batch[] = [
                            'idpaket'  => $idpaket,
                            'id_ujian' => $arr_u[$i] ?? 0,
                            'id_mapel' => $arr_m[$i] ?? 0,
                            'id_sesi'  => 0
                        ];
                    }

                    if (!empty($detail_batch)) {
                        $this->detailPaketModel->insertBatch($detail_batch);
                    }
                }
            }

            // Update flag v_ujian & v_materi pada tabel paket
            $this->paketModel->update($idpaket, ['v_ujian' => $v_u, 'v_materi' => $v_m]);

            // 2. Proses Webinar jika dipilih
            if (in_array('webinar', $jenis_paket_bersih)) {
                $raw_id_sesi = $this->request->getPost('id_sesi') ?? [];
                if (!is_array($raw_id_sesi)) $raw_id_sesi = [$raw_id_sesi];

                if (!empty($raw_id_sesi)) {
                    $detail_batch_webinar = [];
                    foreach ($raw_id_sesi as $sesi) {
                        $detail_batch_webinar[] = [
                            'idpaket'  => $idpaket,
                            'id_ujian' => 0,
                            'id_mapel' => 0,
                            'id_sesi'  => $sesi
                        ];
                    }
                    if (!empty($detail_batch_webinar)) {
                        $this->detailPaketModel->insertBatch($detail_batch_webinar);
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception("Transaksi database gagal dieksekusi.");
            }

            return redirect()->to('sw-admin/paket')->with('success', 'Data paket berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->to('sw-admin/paket')->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }
    public function ujianMaster()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('auth');
        }

        try {
            $data = $this->ujianMasterModel
                ->where('kelas', $this->request->getVar('id'))
                ->get()->getResultObject();

            return $this->response->setJSON([
                'status' => 'success',
                'data'   => $data,
                csrf_token() => csrf_hash() // Kirim token baru
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => $e->getMessage(),
                csrf_token() => csrf_hash()
            ]);
        }
    }

    public function getMapel()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('auth');
        }

        try {
            $data = $this->mapelModel->join('materi', 'materi.mapel=mapel.id_mapel')
                ->where('kelas', $this->request->getVar('id'))
                ->groupBy('mapel.id_mapel')
                ->get()->getResultObject();

            return $this->response->setJSON([
                'status' => 'success',
                'data'   => $data,
                csrf_token() => csrf_hash()
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => $e->getMessage(),
                csrf_token() => csrf_hash()
            ]);
        }
    }



    // untuk drag urutan
    public function reorder()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        try {
            $data = $this->request->getJSON(true);
            $db = \Config\Database::connect();
            $builder = $db->table('paket');

            $db->transStart(); // Gunakan transaksi database agar aman
            foreach ($data as $row) {
                $builder->where('idpaket', $row['id'])
                    ->update(['sort_order' => $row['position']]);
            }
            $db->transComplete();

            return $this->response->setJSON([
                'status' => 'success',
                csrf_token() => csrf_hash()
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => $e->getMessage(),
                csrf_token() => csrf_hash()
            ]);
        }
    }

    // untuk pin 
    public function pin()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('auth');
        }

        try {
            $id = $this->request->getPost('id');
            $db = \Config\Database::connect();

            // ambil data paket
            $paket = $db->table('paket')
                ->where('idpaket', $id)
                ->get()
                ->getRowArray();

            if (!$paket) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan',
                    csrf_token() => csrf_hash()
                ]);
            }

            $db->transStart();
            // toggle pin
            if ($paket['is_pinned'] == 1) {
                // UNPIN
                $db->table('paket')
                    ->where('idpaket', $id)
                    ->update(['is_pinned' => 0]);
            } else {
                // PIN → geser semua pinned yang ada
                $db->table('paket')
                    ->set('sort_order', 'sort_order + 1', false)
                    ->where('is_pinned', 1)
                    ->update();

                $db->table('paket')
                    ->where('idpaket', $id)
                    ->update(['is_pinned' => 1]);
            }
            $db->transComplete();

            return $this->response->setJSON([
                'status' => 'success',
                csrf_token() => csrf_hash()
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => $e->getMessage(),
                csrf_token() => csrf_hash()
            ]);
        }
    }

    public function review($slug)
    {
        try {
            // Gunakan Query Builder CI4 agar lebih aman (SQL Injection Protection)
            $data['review'] = $this->db->table('review_ujian d')
                ->select('q.nama_siswa, q.avatar, d.id_review, d.status, d.komentar, d.rating, d.created_at')
                ->join('ujian_master c', 'c.kode_ujian = d.kode_ujian')
                ->join('detail_paket b', 'b.id_ujian = c.id_ujian')
                ->join('paket p', 'p.idpaket = b.idpaket')
                ->join('siswa q', 'q.id_siswa = d.id_siswa')
                ->where('p.slug', $slug)
                ->orderBy('d.created_at', 'DESC')
                ->get()->getResult();

            $data['breadcrumbs'] = [
                ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
                ['title' => 'Data Review Paket', 'url' => base_url('sw-admin/review')],
                ['title' => 'List Review Peserta', 'url' => '#'],
            ];

            return view('admin/paket/review', $data);
        } catch (\Exception $e) {
            return redirect()->to('sw-admin/paket')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function editReview()
    {
        if ($this->request->isAJAX()) {
            try {
                $idRaw = $this->request->getVar('id_review');
                $idDecrypted = decrypt_url($idRaw);

                if (!$idDecrypted) {
                    throw new \Exception('ID Review tidak valid.');
                }

                $data_review = $this->reviewModel->asArray()->find($idDecrypted);

                if (!$data_review) {
                    throw new \Exception('Data tidak ditemukan.');
                }

                // Kirim balik data beserta Token CSRF baru
                $data_review[csrf_token()] = csrf_hash();

                return $this->response->setJSON($data_review);
            } catch (\Exception $e) {
                return $this->response->setStatusCode(500)->setJSON([
                    'error' => $e->getMessage(),
                    csrf_token() => csrf_hash()
                ]);
            }
        }
    }

    public function updateReview()
    {
        if (session()->get('role') != 1) {
            return redirect()->to('auth');
        }

        try {
            $id_review = $this->request->getVar('id_review'); // Pastikan ini ID asli (hidden field)

            $this->reviewModel->save([
                'id_review' => $id_review,
                'rating'    => $this->request->getVar('rating'),
                'komentar'  => $this->request->getVar('komentar'),
                'status'    => $this->request->getVar('status'),
            ]);

            return redirect()->back()->with('success', 'Data review berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah data: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        $idPaket = decrypt_url($id); // Asumsi: Jika fungsi decrypt Anda bernama decrypt_url
        // Validasi ID
        if (!$idPaket) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid atau tidak ditemukan.');
        }

        try {
            // Karena $useSoftDeletes = true di Model, ini HANYA akan mengisi kolom deleted_at!
            $dataUpdate = [
                'status'     => '0',
                'deleted_at' => date('Y-m-d H:i:s')
            ];

            // 3. Eksekusi Update (Bukan Delete)
            $this->paketModel->update($idPaket, $dataUpdate);

            return redirect()->back()->withInput()->with('success', "Paket berhasil dihapus");
        } catch (\Exception $e) {
            // Tangkap jika ada error database
            return redirect()->back()->withInput()->with('error', 'Paket gagal dihapus');
        }
    }

    public function getUjianMaster()
    {
        // Pastikan request ini murni dari AJAX untuk keamanan
        if ($this->request->isAJAX()) {
            $id_kelas = $this->request->getPost('id');

            // Ambil data ujian berdasarkan id_kelas
            // Asumsi: tabel master ujian Anda digabung (join) dengan tabel nama ujian
            $data = $this->ujianMasterModel
                ->select('ujian_master.id_ujian, tabel_nama_ujian.nama_ujian') // SESUAIKAN: ganti tabel_nama_ujian dengan nama tabel asli yang menyimpan teks nama ujian
                ->join('tabel_nama_ujian', 'tabel_nama_ujian.id_ujian = ujian_master.id_ujian')
                ->where('ujian_master.kelas', $id_kelas)
                ->groupBy('ujian_master.id_ujian')
                ->findAll();

            // Kembalikan dalam bentuk JSON beserta CSRF Token baru
            return $this->response->setJSON([
                'status' => 'success',
                'data'   => $data,
                csrf_token() => csrf_hash() // Wajib ada agar token form ter-update
            ]);
        }
    }
    public function getWebinarSesi()
    {
        // Pastikan request melalui AJAX demi keamanan
        if ($this->request->isAJAX()) {
            $db = \Config\Database::connect();

            // Mengambil semua sesi webinar yang aktif, diurutkan dari yang terbaru (waktu mulai)
            // Sesuaikan nama tabel 'webinar_sesi' jika di database Anda berbeda
            $sesi = $db->table('webinar_sesi')
                ->select('id_sesi, nama_sesi, harga_sesi')
                ->orderBy('waktu_mulai', 'DESC')
                ->get()
                ->getResultArray();

            return $this->response->setJSON([
                'status'       => 'success',
                'data'         => $sesi,
                csrf_token()   => csrf_hash()
            ]);
        }
    }

    public function kirimEmailPeserta()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('auth');
        }

        $id_encrypt = $this->request->getPost('id_paket');
        $id_paket   = decrypt_url($id_encrypt);
        
        // Ambil data antrean (mulai dari 0, lalu 5, 10, dst)
        $offset = (int) $this->request->getPost('offset') ?? 0;
        $limit  = 5; // Batasi pengiriman 5 email per request agar PHP tidak Timeout

        if (!$id_paket) {
            return $this->response->setJSON(['status' => false, 'message' => 'Data paket tidak valid.', csrf_token() => csrf_hash()]);
        }

        $db = \Config\Database::connect();

        // 1. Ambil nama Paket
        $paket = $db->table('paket')->where('idpaket', $id_paket)->get()->getRow();
        if (!$paket) {
            return $this->response->setJSON(['status' => false, 'message' => 'Paket tidak ditemukan.', csrf_token() => csrf_hash()]);
        }

        // 2. Hitung TOTAL SELURUH PESERTA untuk informasi progress di layar
        $queryTotal = $db->table('detail_transaksi')
            ->select('siswa.email')
            ->join('transaksi', 'transaksi.idtransaksi = detail_transaksi.idtransaksi')
            ->join('siswa', 'siswa.id_siswa = transaksi.idsiswa')
            ->where('detail_transaksi.idpaket', $id_paket)
            ->where('transaksi.status', 'S')
            ->groupBy('siswa.email')
            ->get();
            
        $totalData = $queryTotal->getNumRows();

        if ($totalData == 0) {
            return $this->response->setJSON([
                'status' => false, 
                'message' => 'Tidak ada peserta aktif yang terdaftar pada paket ini.',
                csrf_token() => csrf_hash()
            ]);
        }

        // 3. Ambil data peserta SECARA BERTAHAP (Limit & Offset)
        $pesertaList = $db->table('detail_transaksi')
            ->select('siswa.nama_siswa, siswa.email')
            ->join('transaksi', 'transaksi.idtransaksi = detail_transaksi.idtransaksi')
            ->join('siswa', 'siswa.id_siswa = transaksi.idsiswa')
            ->where('detail_transaksi.idpaket', $id_paket)
            ->where('transaksi.status', 'S')
            ->groupBy('siswa.email')
            ->orderBy('siswa.email', 'ASC') // WAJIB ada order agar data tidak acak saat offset
            ->limit($limit, $offset)
            ->get()
            ->getResultObject();

        // 4. Proses Kirim Email (Hanya untuk 5 data di batch ini)
        $berhasilBatch = 0;
        foreach ($pesertaList as $p) {
            $nama_siswa = $p->nama_siswa;
            $email = $p->email;

            $subject = 'INFORMASI PELAKSANAAN WEBINAR - ' . strtoupper($paket->nama_paket);
            
            $message = '
            <div style="color: #000; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; max-width: 600px;">
                <div style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; font-size: 20px; color: #1C3FAA; font-weight: bold; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">
                    INFORMASI PELAKSANAAN WEBINAR
                </div>
                <br>
                <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #334155; line-height: 1.6;">
                    Hallo <b>' . substr(esc($nama_siswa), 0, 10) . '</b>, <br>
                    Paket Webinar <b>' . esc($paket->nama_paket) . '</b> yang Anda ikuti 3 jam lagi akan segera dimulai. Silakan cek detail jadwal melalui akun Anda.
                </p>
                
                <div style="margin-top: 20px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 15px;">
                    <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #166534; font-size: 14px; margin: 0 0 10px 0; line-height: 1.4;">
                        <b>Informasi Grup WhatsApp:</b> Silakan bergabung ke grup resmi untuk koordinasi, tanya jawab, dan info penting lainnya.
                    </p>
                    <a href="https://chat.whatsapp.com/CHWkmrWMqrSJvJNlu1UoVy?s=cl&p=i&mlu=4" target="_blank" style="display: inline-block; background: #22c55e; color: #fff; text-decoration: none; border-radius: 5px; text-align: center; line-height: 30px; font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; padding: 5px 15px; font-weight: bold; font-size: 14px;">
                        Gabung Grup WhatsApp
                    </a>
                </div>

                <div style="margin-top: 20px;">
                    <a href="' . base_url("auth/") . '" style="display: inline-block; background: #1C3FAA; color: #fff; margin: 5px 0 10px 0; text-decoration: none; border-radius: 5px; text-align: center; line-height: 30px; font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; padding: 5px 20px;">
                        Login
                    </a>
                </div> 
            </div>';

            $kirim = $this->emailer->send($email, $subject, $message);
            if ($kirim) {
                $berhasilBatch++;
            }
            
            // JEDA 1 DETIK ANTAR EMAIL
            // Sangat penting untuk mencegah error "Unable to send email" akibat rate-limit (dianggap spam) oleh server
            sleep(1); 
        }

        // Kalkulasi posisi berikutnya
        $nextOffset = $offset + count($pesertaList);
        $isDone = ($nextOffset >= $totalData); // True jika semua email sudah terkirim

        return $this->response->setJSON([
            'status'         => true, 
            'is_done'        => $isDone,
            'total_data'     => $totalData,
            'next_offset'    => $nextOffset,
            'berhasil_batch' => $berhasilBatch,
            csrf_token()     => csrf_hash()
        ]);
    }
}

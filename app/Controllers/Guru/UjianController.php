<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class UjianController extends BaseController
{
    protected $ujianMasterModel;
    protected $guruKelasModel;
    protected $guruMapelModel;
    protected $guruModel;
    protected $kategoriModel;
    protected $bankSoalModel;
    protected $siswaModel;
    protected $statusUjianModel;
    protected $ujianDetailModel;
    protected $ujianSiswaModel;
    protected $gurukelasModel;
    protected $ujianModel;

    public function __construct()
    {
        $this->ujianMasterModel = new \App\Models\UjianMasterModel();
        $this->guruKelasModel = new \App\Models\GuruKelasModel();
        $this->guruMapelModel = new \App\Models\GuruMapelModel();
        $this->guruModel = new \App\Models\GuruModel();
        $this->kategoriModel = new \App\Models\KategoriModel();
        $this->bankSoalModel = new \App\Models\BankSoalModel();
        $this->siswaModel = new \App\Models\SiswaModel();
        $this->statusUjianModel = new \App\Models\StatusUjianModel();
        $this->ujianDetailModel = new \App\Models\UjianDetailModel();
        $this->ujianSiswaModel = new \App\Models\UjianSiswaModel();
        $this->gurukelasModel = new \App\Models\GurukelasModel();
        $this->ujianModel = new \App\Models\UjianModel();

    }

    // START = UJIAN PG
    public function index()
    {
        $data['ujian'] = $this->ujianMasterModel->getAllBykodeGuru(session()->get('id'))->get()->getResultObject();
        return view('guru/ujian/list', $data);
    }

    public function create()
    {

        $data['guru_kelas'] = $this->guruKelasModel->getALLByGuru(session()->get('id'));
        $data['guru_mapel'] = $this->guruMapelModel->getALLByGuru(session()->get('id'));
        $data['guru'] = $this->guruModel->asObject()->find(session()->get('id'));
        $data['kategori'] = $this->kategoriModel->getAll();
        return view('guru/ujian/tambah_pg', $data);
    }
    public function store()
    {
        $nama_soal = $this->request->getVar('nama_soal');
        if ($nama_soal != null) {
            $siswa = $this->siswaModel->getAllbyKelas($this->request->getVar('kelas'));
            if (count($siswa) == 0) {
                return redirect()->to('sw-guru/ujian')->with('pesan', 'Belum ada siswa dikelas ini');
            }

            // DATA UJIAN
            $kode_ujian = random_string('alnum', 10);
            $data_ujian = [
                'kode_ujian' => $kode_ujian,
                'nama_ujian' => $this->request->getVar('nama_ujian'),
                'guru' => session()->get('id'),
                'kelas' => $this->request->getVar('kelas'),
                'mapel' => $this->request->getVar('mapel'),
                'date_created' => time(),
            ];
            // END DATA UJIAN
            $this->ujianMasterModel->save($data_ujian);

            //UJIAN UNTUK SETIAP SISWA


            $status_ujian = [
                'kode_ujian' => $kode_ujian,
                'status' => $this->request->getVar('status_ujian'),
            ];
            // END DATA UJIAN
            $this->statusUjianModel->save($status_ujian);


            // DATA DETAIL UJIAN PG

            // $data_detail_ujian = array();
            $index = 0;
            foreach ($nama_soal as $nama) {
                $dataSoal = $this->ujianDetailModel->getBySoalKodeUjian($nama, $kode_ujian);
                if ($dataSoal == null) {
                    $data_detail_ujian = [
                        'kode_ujian' => $kode_ujian,
                        'nama_soal' => $nama,
                        'pg_1' => 'A. ' . $this->request->getVar('pg_1')[$index],
                        'pg_2' => 'B. ' . $this->request->getVar('pg_2')[$index],
                        'pg_3' => 'C. ' . $this->request->getVar('pg_3')[$index],
                        'pg_4' => 'D. ' . $this->request->getVar('pg_4')[$index],
                        'pg_5' => 'E. ' . $this->request->getVar('pg_5')[$index],
                        'jawaban' => $this->request->getVar('jawaban')[$index],
                        'penjelasan' => $this->request->getVar('penjelasan')[$index],
                    ];
                    // END DATA UJIAN
                    $this->ujianDetailModel->save($data_detail_ujian);
                }

                $index++;
            }
            // END DATA DETAIL UJIAN PG

            return redirect()->to('sw-guru/ujian')->with('success', 'Ujian telah dibuat');
        } else {
            return redirect()->to('sw-guru/ujian')->with('error', 'Ujian Tidak dapat di tambah karna tidak ada soal yang di ditambah');
        }
    }

    public function ubahStatusUjian()
    {

        $data = $this->statusUjianModel
            ->where('kode_ujian', $this->request->getVar('kode_ujian'))
            ->get()->getRowObject();

        if (!empty($data)) {
            if ($data->status == 'T') {
                $status = 'A';
            } else {
                $status = 'T';
            }
        } else {
            $status = 'A';
        }

        if (!empty($data)) {
            $status_ujian = [
                'idstatusujian' => $data->idstatusujian,
                'kode_ujian' => $data->kode_ujian,
                'status' => $status,
            ];
            // END DATA UJIAN
            $this->statusUjianModel->save($status_ujian);
        } else {
            $status_ujian = [
                'kode_ujian' => $this->request->getVar('kode_ujian'),
                'status' => $status,
            ];
            // END DATA UJIAN
            $this->statusUjianModel->save($status_ujian);
        }
        return redirect()->to('sw-guru/ujian')->with('success', 'Ujian berhasil diubah.');
    }

    public function getBankSoal()
    {
        $RsData = $this->bankSoalModel->get_datatables();
        $no = $this->request->getPost('start');
        $data = array();

        if ($RsData->getNumRows() > 0) {
            foreach ($RsData->getResult() as $rowdata) {
                $row = array();
                $row[] = '<input type="checkbox" data-id_bank_soal="' . $rowdata->id_bank_soal . '" id="tambahSoal" class="check-item">';
                $row[] = $rowdata->nama_soal;
                $data[] = $row;
            }
        }

        $output = array(
            "draw"            => $this->request->getPost('draw'),
            "recordsTotal"    => $this->bankSoalModel->count_all(),
            "recordsFiltered" => $this->bankSoalModel->count_filtered(),
            "data"            => $data,
            "token"           => csrf_hash(), // TAMBAHKAN INI
        );

        return $this->response->setJSON($output);
    }

    public function tambahBankSoal()
    {
        if ($this->request->isAJAX()) {
            $id_bank_soal = $this->request->getVar('id_bank_soal');
            $data = $this->bankSoalModel->getById($id_bank_soal);

            // Tambahkan token terbaru ke dalam response data
            $data->token = csrf_hash();

            return $this->response->setJSON($data);
        }
    }

    public function uploadSummernote()
    {
        $fileGambar = $this->request->getFile('image');

        if ($fileGambar && !$fileGambar->hasMoved()) {
            // Generate nama file Random
            $nama_gambar = $fileGambar->getRandomName();

            // Upload Gambar
            $fileGambar->move('assets/app-assets/file', $nama_gambar);

            // Kirim balik URL dan Token baru
            return json_encode([
                'url'   => base_url('assets/app-assets/file/' . $nama_gambar),
                'token' => csrf_hash()
            ]);
        }
    }

    public function deleteImage()
    {
        $src = $this->request->getVar('src');

        // Bersihkan path: pastikan hanya menghapus file di folder yang diizinkan
        $file_path = str_replace(base_url() . '/', '', $src);

        $status = false;
        $message = 'File not found';

        // Cek apakah file ada sebelum di-unlink (delete)
        if (file_exists($file_path)) {
            if (unlink($file_path)) {
                $status = true;
                $message = 'File Deleted Successfully';
            } else {
                $message = 'Failed to delete file from server';
            }
        }

        // Selalu kembalikan CSRF Hash terbaru
        return $this->response->setJSON([
            'status'  => $status,
            'message' => $message,
            'token'   => csrf_hash()
        ]);
    }


     public function lihatUjian($kode_ujian)
    {
        $data = [
            'title'        => 'Data Ujian',
            'parent_title' => 'Ujian ',
            'parent_url'   => base_url('sw-guru/ujian'),
        ];

        $kode_ujian = decrypt_url($kode_ujian);
        $data['kode_ujian_encrypt'] = $kode_ujian; // Kirim untuk AJAX

        // Kita tidak lagi mengirim $data['siswa'] ke view karena akan ditarik via AJAX
        return view('guru/ujian/pg-lihat', $data);
    }

    public function ajaxSiswaUjian($kode_ujian_encrypt)
    {
        $kode_ujian = decrypt_url($kode_ujian_encrypt);
        $request = \Config\Services::request();

        // Ambil parameter dari DataTables
        $draw   = $request->getPost('draw');
        $start  = $request->getPost('start');
        $length = $request->getPost('length');
        $search = $request->getPost('search')['value'];

        $dataUjian = $this->ujianMasterModel->getBykode($kode_ujian);

        // 1. Ambil data dari Model (Hasilnya berupa Array sesuai limit 20 di model)
        $list_siswa_raw = $this->siswaModel->getAllbyKelasUjian($dataUjian->kelas, $dataUjian->kode_ujian);

        // 2. Hitung total data mentah dari array
        $totalData = count($list_siswa_raw);

        // 3. Filter Search Manual (karena data sudah ditarik sebagai array)
        if ($search) {
            $list_siswa = array_filter($list_siswa_raw, function ($item) use ($search) {
                return stripos($item->nama_siswa, $search) !== false;
            });
        } else {
            $list_siswa = $list_siswa_raw;
        }

        $recordsFiltered = count($list_siswa);

        // 4. POTONG DATA (PAGINATION)
        // Memotong array agar hanya mengirim data sesuai halaman (misal 10 data)
        $list_siswa_paginated = array_slice($list_siswa, $start, $length);

        $data_json = [];
        foreach ($list_siswa_paginated as $s) {
            $url_detail = base_url('sw-guru/ujian/lihat-ujian-siswa/' . encrypt_url($s->id_siswa) . '/' . encrypt_url($dataUjian->kode_ujian));

            // Link untuk Cetak Hasil Peserta (Fungsi cetak yang baru kita buat)
            $url_cetak = base_url('sw-guru/ujian/cetak-soal-peserta/' . encrypt_url($s->id_siswa) . '/' . encrypt_url($dataUjian->kode_ujian));
            // Hitung Statistik
            $benar = $this->ujianSiswaModel->where(['ujian' => $dataUjian->kode_ujian, 'siswa' => $s->id_siswa, 'benar' => 1])->countAllResults();
            $salah = $this->ujianSiswaModel->where(['ujian' => $dataUjian->kode_ujian, 'siswa' => $s->id_siswa, 'benar' => 0])->countAllResults();
            $total_soal_dikerjakan = $this->ujianSiswaModel->where(['ujian' => $dataUjian->kode_ujian, 'siswa' => $s->id_siswa])->countAllResults();

            $skor = ($total_soal_dikerjakan > 0) ? round(($benar / $total_soal_dikerjakan) * 100) : 0;

            $data_json[] = [
                // Kolom 1: Profil
                '<div class="d-flex align-items-center">
                <img src="' . base_url('assets/app-assets/user/' . $s->avatar) . '" class="rounded-circle mr-3" style="width:45px; height:45px; object-fit:cover;">
                <div>
                    <h6 class="mb-0 font-weight-bold">' . $s->nama_siswa . '</h6>
                    <small class="text-muted">' . ($s->date_send == 0 ? 'Selesai' : date('d M Y, H:i', $s->date_send)) . '</small>
                </div>
            </div>',
                // Kolom 2: Statistik
                '<div class="d-flex justify-content-around text-center">
                <div class="px-2"><b>' . $skor . '</b><br><small>Skor</small></div>
                <div class="px-2 text-success"><b>' . $benar . '</b><br><small>Benar</small></div>
                <div class="px-2 text-danger"><b>' . $salah . '</b><br><small>Salah</small></div>
            </div>',
                // Kolom 3: Aksi
                '<div class="dropdown custom-dropdown text-center">
                    <a class="dropdown-toggle badge badge-primary border-0" href="#" role="button" id="dropdownMenuLink' . $s->id_siswa . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink' . $s->id_siswa . '">
                        <a class="dropdown-item" href="' . $url_detail . '">
                            <i class="bi bi-eye me-2 text-primary"></i> Lihat Detail
                        </a>
                        <a class="dropdown-item" target="_blank" href="' . $url_cetak . '">
                            <i class="bi bi-printer me-2 text-success"></i> Cetak Hasil
                        </a>
                    </div>
                </div>'
            ];
        }

        return $this->response->setJSON([
            "draw"            => intval($draw),
            "recordsTotal"    => $totalData,
            "recordsFiltered" => $recordsFiltered,
            "data"            => $data_json,
            csrf_token() => csrf_hash() // Update CSRF Token
        ]);
    }

    public function lihatUjianSiswa($id_siswa, $kode_ujian)
    {
        $data = [
            'title'        => 'Data Ujian',
            'parent_title' => 'List ujian',
            'parent_url'   => base_url('sw-guru/ujian'),
        ];
        $data['ujian'] = $this->ujianMasterModel->getBykode(decrypt_url($kode_ujian));
        $data['detail_ujian'] = $this->ujianDetailModel->getAllBykodeUjian(decrypt_url($kode_ujian));
        $data['siswa'] = $this->siswaModel->asObject()->find(decrypt_url($id_siswa));

        $data['ujian_siswa'] = $this->ujianSiswaModel
            ->where('ujian', decrypt_url($kode_ujian))
            ->where('siswa', decrypt_url($id_siswa))
            ->get()->getResultObject();

        $data['jawaban_benar'] = $this->ujianSiswaModel->benar(decrypt_url($kode_ujian), decrypt_url($id_siswa), 1);
        $data['jawaban_salah'] = $this->ujianSiswaModel->salah(decrypt_url($kode_ujian), decrypt_url($id_siswa), 0);
        $data['tidak_dijawab'] = $this->ujianSiswaModel->belum_terjawab(decrypt_url($kode_ujian), decrypt_url($id_siswa));

        return view('guru/ujian/pg-siswa', $data);
    }

    public function cetakSoalPeserta($id_siswa_encrypt, $kode_ujian_encrypt)
    {
        try {
            // 2. Dekripsi dengan Error Handling
            $id_siswa = decrypt_url($id_siswa_encrypt);
            $kode_ujian = decrypt_url($kode_ujian_encrypt);

            if (!$id_siswa || !$kode_ujian) {
                return redirect()->back()->with('error', 'Parameter tidak valid.');
            }

            // 3. Pastikan Data Siswa Ada
            $siswa = $this->siswaModel->asObject()->find($id_siswa);
            if (!$siswa) {
                return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
            }

            // 4. Pastikan Data Ujian Ada
            $ujian = $this->ujianMasterModel->getBykode($kode_ujian);
            if (!$ujian) {
                return redirect()->back()->with('error', 'Data ujian tidak ditemukan.');
            }

            // 5. Ambil Detail Soal
            $detail_ujian = $this->ujianDetailModel->getAllBykodeUjian($kode_ujian);
            if (empty($detail_ujian)) {
                // Opsional: Tetap lanjut cetak atau stop jika soal kosong
            }

            // Persiapan data untuk view
            $data = [
                'detail_ujian' => $detail_ujian,
                'ujian'        => $ujian,
                'id_siswa'     => $id_siswa,
                'siswa'        => $siswa,
                'file'         => 'Soal_' . str_replace(' ', '_', $siswa->nama_siswa),
                'title'        => 'Cetak Hasil Ujian - ' . $siswa->nama_siswa,
                'response'     => $this->response->setContentType('application/pdf'),
            ];

            // 6. Generate View dengan pengecekan file eksis
            // Menggunakan view guru sesuai path di variabel Anda
            $data['view'] = view('guru/ujian/cetak/soal_peserta', $data);

            return view('guru/ujian/cetak/tampil', $data);
        } catch (\Exception $e) {
            // 7. Tangkap error tak terduga (misal: error database)
            log_message('error', '[Cetak Soal] Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses data.');
        }
    }
    function cetakSoal($kode_ujian)
    {

        try {
            // Proses Dekripsi
            $kode_ujian_decrypted = decrypt_url($kode_ujian);

            // Ambil Data
            $data['detail_ujian'] = $this->ujianDetailModel->getAllBykodeUjian($kode_ujian_decrypted);
            $data['ujian'] = $this->ujianMasterModel->getBykode($kode_ujian_decrypted);

            // Validasi jika data tidak ditemukan (Antisipasi error property of non-object)
            if (!$data['ujian']) {
                return redirect()->back()->with('error', 'Data ujian tidak ditemukan.');
            }

            // Render View
            $view = view('guru/ujian/cetak/soal', $data);
            $data['view'] = $view;
            $data['response'] = $this->response->setContentType('application/pdf');
            $data['file'] = $data['ujian']->nama_ujian;

            return view('guru/ujian/cetak/tampil', $data);
        } catch (\Exception $e) {
            // Jika terjadi error, catat di log dan kembalikan ke halaman sebelumnya
            log_message('error', '[Cetak Soal Error]: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses cetak soal. Terjadi kesalahan sistem.');
        }
    }


    public function editUjian($kode_ujian)
    {
        $data['detail_ujian'] = $this->ujianDetailModel->getAllBykodeUjian(decrypt_url($kode_ujian));
        $data['ujian'] = $this->ujianMasterModel->getBykode(decrypt_url($kode_ujian));
        $data['siswa'] = $this->siswaModel->getAllbyKelas($data['ujian']->kelas);
        $data['guru'] = $this->guruModel->asObject()->find(session()->get('id'));
        $data['guru_kelas'] = $this->gurukelasModel->getALLByGuru(session()->get('id'));
        $data['guru_mapel'] = $this->guruMapelModel->getALLByGuru(session()->get('id'));
        return view('guru/ujian/edit_pg', $data);
    }

    public function update()
    {
        // DATA UJIAN
        $data_ujian = [
            'kode_ujian' => $this->request->getVar('kode_ujian'),
            'nama_ujian' => $this->request->getVar('nama_ujian'),
            'guru' => session()->get('id'),
            'kelas' => $this->request->getVar('kelas'),
            'mapel' => $this->request->getVar('mapel'),
            'date_created' => time(),
        ];
        // END DATA UJIAN


        $this->ujianMasterModel->set($data_ujian)->where('id_ujian', $this->request->getVar('id_ujian'))->update();
        
        $dataUjianUpdate = $this->ujianModel->where('kode_ujian', $this->request->getVar('kode_ujian'))->where('status', 'B')->get()->getResultObject();
        foreach($dataUjianUpdate as $rowsUjian){
            $update_ujian= [
                            'kode_ujian' => $this->request->getVar('kode_ujian'),
                            'nama_ujian' => $this->request->getVar('nama_ujian'),
                            'guru' => session()->get('id'),
                            'kelas' => $this->request->getVar('kelas'),
                            'mapel' => $this->request->getVar('mapel'),
                            'date_created' => time(),
                        ];

                $this->ujianModel->set($update_ujian)->where('id_ujian', $rowsUjian->id_ujian)->update();
        }

        if ($this->request->getVar('id_siswa') !== null) {
            $id_siswa = $this->request->getVar('id_siswa');
            //membuat ujian ke peserta
            foreach ($id_siswa as $rows) {
                $data_ujian_per_siswa= [
                    'id_siswa' => $rows,
                    'kode_ujian' => $this->request->getVar('kode_ujian'),
                    'nama_ujian' => $this->request->getVar('nama_ujian'),
                    'guru' => session()->get('id'),
                    'kelas' => $this->request->getVar('kelas'),
                    'mapel' => $this->request->getVar('mapel'),
                    'date_created' => time(),
                ];
                  $this->ujianModel->save($data_ujian_per_siswa);
            }
            
            
            
            $ujian_detail = $this->ujianDetailModel->getAllBykodeUjian($this->request->getVar('kode_ujian'));
            $data_detail_ujian = array();
            $index = 0;
            foreach ($ujian_detail as $uj) {
                foreach ($id_siswa as $rows) {
                    $ujian_siswa = $this->ujianSiswaModel->getAll($this->request->getVar('kode_ujian'), $rows);

                    if (empty($ujian_siswa)) {
                        array_push($data_detail_ujian, array(
                            'ujian_id' =>  $uj->id_detail_ujian,
                            'ujian' => $uj->kode_ujian,
                            'siswa' => $rows,
                        ));

                        $index++;
                    }
                }
            }
            $this->ujianSiswaModel->insertBatch($data_detail_ujian);
            
            
            
            
        }

        //untuk mereset ujian
        if ($this->request->getVar('id_siswa_reset') !== null) {
            $id_siswa_reset = $this->request->getVar('id_siswa_reset');
            foreach ($id_siswa_reset as $row) {
                $data_ujian_siswa = $this->ujianSiswaModel->where('ujian', $this->request->getVar('kode_ujian'))->where('siswa', $row)->get()->getResultObject();
                foreach ($data_ujian_siswa as $rows) {
                    $data_detail_siswa = [
                        'jawaban'       => null,
                        'benar'         => null,
                        'jam'           => null,
                        'status'        => null,
                    ];
                    $this->ujianSiswaModel->set($data_detail_siswa)->where('id_ujian_siswa', $rows->id_ujian_siswa)->update();
                }
                //untuk membuat ujian remedial
                $data_ujian_per_siswa= [
                            'id_siswa' => $row,
                            'kode_ujian' => $this->request->getVar('kode_ujian'),
                            'nama_ujian' => $this->request->getVar('nama_ujian'),
                            'guru' => session()->get('id'),
                            'kelas' => $this->request->getVar('kelas'),
                            'mapel' => $this->request->getVar('mapel'),
                            'date_created' => time(),
                        ];

                $this->ujianModel->insert($data_ujian_per_siswa);
                //end ujian remedial
            }
        }
        return redirect()->to('guru/ujian')->with('success', 'Ujian telah dibuat');
    }

    public function editSoal($id_detail_ujian)
    {
        $data['detail_ujian'] = $this->ujianDetailModel->getAllByiddetailujian(decrypt_url($id_detail_ujian));
        $data['guru'] = $this->guruModel->asObject()->find(session()->get('id'));
        $data['guru_kelas'] = $this->gurukelasModel->getALLByGuru(session()->get('id'));
        $data['guru_mapel'] = $this->guruMapelModel->getALLByGuru(session()->get('id'));
        return view('guru/ujian/edit_soal', $data);
    }
    public function updateSoal()
    {

        $data_detail_ujian = [
            'kode_ujian' => $this->request->getVar('kode_ujian'),
            'nama_soal' => $this->request->getVar('nama_soal'),
            'pg_1' => 'A. ' . $this->request->getVar('pg_1'),
            'pg_2' => 'B. ' . $this->request->getVar('pg_2'),
            'pg_3' => 'C. ' . $this->request->getVar('pg_3'),
            'pg_4' => 'D. ' . $this->request->getVar('pg_4'),
            'pg_5' => 'E. ' . $this->request->getVar('pg_5'),
            'jawaban' => $this->request->getVar('jawaban'),
            'penjelasan' => $this->request->getVar('penjelasan'),
        ];


        $this->ujianDetailModel->set($data_detail_ujian)->where('id_detail_ujian', $this->request->getVar('id_detail_ujian'))->update();
        return redirect()->to('sw-guru/ujian/edit-ujian/' . encrypt_url($this->request->getVar('kode_ujian')))->with('success', 'Soal telah diubah');
    }

    
    public function downloadTemplate()
    {
        return $this->response->download('assets/app-assets/file-excel/template.xlsx', NULL);
    }
    public function importSoalExcel()
    {
        $siswa = $this->siswaModel->getAllbyKelas($this->request->getVar('e_kelas'));
        $guru = $this->guruModel->asObject()->find(session()->get('id'));
        if (count($siswa) == 0) {
            return redirect()->to('sw-guru/ujian')->with('pesan', 'Belum ada siswa dikelas ini');
        }

        // DATA UJIAN
        $kode_ujian = random_string('alnum', 10);
        $data_ujian = [
            'kode_ujian' => $kode_ujian,
            'nama_ujian' => $this->request->getVar('e_nama_ujian'),
            'guru' => session()->get('id'),
            'kelas' => $this->request->getVar('e_kelas'),
            'mapel' => $this->request->getVar('e_mapel'),
            'date_created' => time(),
        ];
        // END DATA UJIAN


        // TANGKAP FILE EXCEL YANG DI UPLLOAD
        $file = $this->request->getFile('excel');
        // AMBIL EXTENSI EXCEL YANG DI UPLOAD
        $ekstensi = $file->getClientExtension();

        // JIKA EKSTENSINYA XLS BERARTI FORMAT EXCEL VERSI LAMA
        if ($ekstensi == 'xls') {
            $reader = new Xls();
        }
        // JIKA EKSTENSINYA XLSX BERARTI FORMAT EXCEL VERSI BARU
        if ($ekstensi == 'xlsx') {
            $reader = new Xlsx();
        }
        /** Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($file);
        // SIMPAN DATA EXCEL KEDALAM VARIABLE $data DAN UBAH MENJADI ARRAY
        $data = $spreadsheet->getActiveSheet()->toArray();
        // LOOPING DATA EXCEL

        // DATA DETAIL UJIAN PG
        $data_detail_ujian = array();

        foreach ($data as $baris => $kolom) {
            // KARENA DI DALAM EXCELNYA MEMILIKI HEADER / JUDUL (contoh : nama | kelas | email)
            // MAKA SKIP BAGIAN JUDUL / BARIS PERTAMA
            if ($baris != 0) {
                // AMBDIL DATA DARI BARIS KEDUA DAN MENYIMPANNYA KEDALAM VARIABEL $data_detail_ujian
                if ($kolom[0] != null) {
                    array_push($data_detail_ujian, array(
                        'kode_ujian' => $kode_ujian,
                        'nama_soal' => $kolom[0],
                        'pg_1' => 'A. ' . $kolom[1],
                        'pg_2' => 'B. ' . $kolom[2],
                        'pg_3' => 'C. ' . $kolom[3],
                        'pg_4' => 'D. ' . $kolom[4],
                        'pg_5' => 'E. ' . $kolom[5],
                        'jawaban' => $kolom[6],
                        'penjelasan' => $kolom[7],
                    ));
                }
            }
        }


        $this->ujianMasterModel->save($data_ujian);
        $this->ujianDetailModel->insertBatch($data_detail_ujian);
        return redirect()->to('sw-guru/ujian')->with('success', 'Ujian telah dibuat');
    }
}

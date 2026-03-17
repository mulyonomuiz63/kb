<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\SiswaModel;
use App\Models\GuruModel;
use App\Models\MapelModel;
use App\Models\KelasModel;
use App\Models\GurukelasModel;
use App\Models\GuruMapelModel;
use App\Models\SmtpModel;
use App\Models\MateriModel;
use App\Models\TugasModel;
use App\Models\FileModel;
use App\Models\Materi_siswaModel;
use App\Models\Tugas_siswaModel;
use App\Models\ChatmateriModel;
use App\Models\ChattugasModel;
use App\Models\UjianModel;
use App\Models\UjianMasterModel;
use App\Models\UjianDetailModel;
use App\Models\UjianSiswaModel;
use App\Models\EssaydetailModel;
use App\Models\EssaysiswaModel;
use App\Models\KategoriModel;
use App\Models\BankSoalModel;
use App\Models\TagModel;
use App\Models\StatusUjianModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Guru extends BaseController
{
    protected $guruModel;
    protected $SiswaModel;
    protected $GuruModel;
    protected $MapelModel;
    protected $KelasModel;
    protected $GurukelasModel;
    protected $GuruMapelModel;
    protected $SmtpModel;
    protected $MateriModel;
    protected $TugasModel;
    protected $FileModel;
    protected $Materi_siswaModel;
    protected $Tugas_siswaModel;
    protected $ChatmateriModel;
    protected $ChattugasModel;
    protected $UjianModel;
    protected $UjianMasterModel;
    protected $UjianDetailModel;
    protected $UjianSiswaModel;
    protected $EssaydetailModel;
    protected $EssaysiswaModel;
    protected $KategoriModel;
    protected $BankSoalModel;
    protected $TagModel;
    protected $StatusUjianModel;

    public function __construct()
    {
        $validation = \Config\Services::validation();
        $this->AdminModel = new AdminModel();
        $this->SiswaModel = new SiswaModel();
        $this->GuruModel = new GuruModel();
        $this->MapelModel = new MapelModel();
        $this->KelasModel = new KelasModel();
        $this->GurukelasModel = new GurukelasModel();
        $this->GuruMapelModel = new GuruMapelModel();
        $this->SmtpModel = new SmtpModel();
        $this->MateriModel = new MateriModel();
        $this->TugasModel = new TugasModel();
        $this->FileModel = new FileModel();
        $this->Materi_siswaModel = new Materi_siswaModel();
        $this->Tugas_siswaModel = new Tugas_siswaModel();
        $this->ChatmateriModel = new ChatmateriModel();
        $this->ChattugasModel = new ChattugasModel();
        $this->UjianModel = new UjianModel();
        $this->UjianMasterModel = new UjianMasterModel();
        $this->UjianDetailModel = new UjianDetailModel();
        $this->UjianSiswaModel = new UjianSiswaModel();
        $this->EssaydetailModel = new EssaydetailModel();
        $this->EssaysiswaModel = new EssaysiswaModel();
        $this->KategoriModel = new KategoriModel();
        $this->BankSoalModel = new BankSoalModel();
        $this->TagModel = new TagModel();
        $this->StatusUjianModel = new StatusUjianModel();

        $this->email = \Config\Services::email();

        date_default_timezone_set('Asia/Jakarta');
    }



    public function index()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => 'active',
            'expanded' => 'true'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_kategori'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_bank_soal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];


        $data['mapel'] = $this->MapelModel->asObject()->findAll();
        $data['kelas'] = $this->KelasModel->asObject()->findAll();

        $data['guru_kelas'] = $this->GurukelasModel->getALLByGuru(session()->get('id'));
        $data['guru_mapel'] = $this->GuruMapelModel->getALLByGuru(session()->get('id'));
        $data['guru'] = $this->GuruModel->asObject()->find(session()->get('id'));

        // dd($data['guru_kelas']);

        return view('guru/dashboard', $data);
    }

    // START::PROFILE
    public function profile()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_kategori'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_bank_soal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];

        $data['guru'] = $this->GuruModel->asObject()->find(session()->get('id'));

        return view('guru/profile', $data);
    }
    public function edit_profile()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        $fileGambar = $this->request->getFile('avatar');

        // Cek Gambar, Apakah Tetap Gambar lama
        if ($fileGambar->getError() == 4) {
            $nama_gambar = $this->request->getVar('gambar_lama');
        } else {
            // Generate nama file Random
            $nama_gambar = $fileGambar->getRandomName();
            // Upload Gambar
            $fileGambar->move('assets/app-assets/user', $nama_gambar);
        }

        $this->GuruModel->save([
            'id_guru' => session()->get('id'),
            'nama_guru' => $this->request->getVar('nama_guru'),
            'avatar' => $nama_gambar
        ]);

        session()->setFlashdata('pesan', "
            swal({
                title: 'Berhasil!',
                text: 'Profile telah diubah',
                type: 'success',
                padding: '2em'
            }); 
        ");
        return redirect()->to('guru/profile');
    }
    public function edit_password()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        $guru = $this->GuruModel->asObject()->find(session()->get('id'));

        if (password_verify($this->request->getVar('current_password'), $guru->password)) {
            $this->GuruModel->save([
                'id_guru' => $guru->id_guru,
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
            ]);
            session()->setFlashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Password telah diubah',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
            return redirect()->to('guru/profile');
        } else {
            session()->setFlashdata('pesan', "
                        swal({
                            title: 'Oops..',
                            text: 'Current Password Salah',
                            type: 'error',
                            padding: '2em'
                            });
                        ");
            return redirect()->to('guru/profile');
        }
    }
    // END::PROFILE

    // START::MATERI
    public function mapel()
    {
        $data['mapel'] = $this->GuruMapelModel->join('guru_kelas','guru_kelas.guru=guru_mapel.guru')->where('guru_mapel.guru', session()->get('id'))->groupBy('guru_mapel.mapel')->get()->getResultObject();

        $data['guru_kelas'] = $this->GurukelasModel->getALLByGuru(session()->get('id'));
        $data['guru_mapel'] = $this->GuruMapelModel->getALLByGuru(session()->get('id'));

        return view('guru/materi/index', $data);
    }
    
    // START::MATERI
    public function materi($id, $kelas)
    {
        
        $data['idmapel'] = $id;
        $data['idkelas'] = $kelas;
        $data['id_mapel'] = decrypt_url($id);
        $data['id_kelas'] = decrypt_url($kelas);
        $data['materi'] = $this->MateriModel->getAllByGuru(session()->get('id'), decrypt_url($id));

        $data['guru_kelas'] = $this->GurukelasModel->getALLByGuru(session()->get('id'));
        $data['guru_mapel'] = $this->GuruMapelModel->getALLByGuru(session()->get('id'));

        return view('guru/materi/list', $data);
    }
    
    public function tambah_materi()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        $idmapel = encrypt_url($this->request->getVar('mapel'));
        $idkelas = encrypt_url($this->request->getVar('kelas'));
        $thumbs = $this->request->getPost();
        $link_video = [];
        if (isset($thumbs['text_materi'])) {
            foreach ($thumbs['text_materi'] as $thumb) {
                $link_video[] = $thumb;
            }
        }
        $data_materi = [
            'kode_materi' => $this->request->getVar('kode_materi'),
            'nama_materi' => $this->request->getVar('nama_materi'),
            'guru' => session()->get('id'),
            'mapel' => $this->request->getVar('mapel'),
            'kelas' => $this->request->getVar('kelas'),
            'text_materi' => json_encode($link_video),
            'status' => $this->request->getVar('status'),
            'date_created' => time()
        ];
        
        
        $kelas = $this->KelasModel->asObject()->find($this->request->getVar('kelas'));

        $siswa = $this->SiswaModel
            ->where('kelas', $this->request->getVar('kelas'))
            ->get()->getResultObject();

        if (count($siswa) == 0) {
            session()->setFlashdata('pesan', "
                swal({
                    title: 'Oops!',
                    text: 'Belum ada siswa dikelas ini',
                    type: 'error',
                    padding: '2em'
                    });
                ");
            return redirect()->to('guru/materi/'.$idmapel.'/'.$idkelas);
        }

        // CEK APAKAH ADA FILE U=YANG DIPILIH
        $file_materi = $this->request->getFileMultiple('file_materi');
        if ($file_materi[0]->getError() != 4) {
            $data_file_materi = [];

            // FUNGSI UPLOAD FILE
            foreach ($file_materi as $file) {
                // Generate nama file Random
                $nama_file = str_replace(' ', '_', $file->getName());
                // Upload Gambar
                $file->move('assets/app-assets/file/', $nama_file);

                array_push($data_file_materi, [
                    'kode_file' => $this->request->getVar('kode_materi'),
                    'nama_file' => $nama_file
                ]);
            }

            $this->FileModel->insertBatch($data_file_materi);
        }

        $siswa_materi = [];
        foreach ($siswa as $s2) {
            array_push($siswa_materi, array(
                'materi' => $this->request->getVar('kode_materi'),
                'kelas' => $this->request->getVar('kelas'),
                'mapel' => $this->request->getVar('mapel'),
                'siswa' => $s2->id_siswa,
                'dilihat' => 0
            ));
        }

        // INSERT DATA MATERI
        $this->MateriModel->save($data_materi);
        // INSERT DATA MATERI SISWA
        $this->Materi_siswaModel->insertBatch($siswa_materi);

        session()->setFlashdata('pesan', "
            swal({
                title: 'Berhasil!',
                text: 'Materi telah dibuat',
                type: 'success',
                padding: '2em'
                });
            ");
        return redirect()->to('guru/materi/'.$idmapel.'/'.$idkelas);
    }
    public function lihat_materi($id, $idmapel, $idkelas)
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        $id_materi = decrypt_url($id);

        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_kategori'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_bank_soal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['materiAll'] = $this->MateriModel->getAllByMapelKelas(decrypt_url($idmapel), decrypt_url($idkelas));
        $data['materi'] = $this->MateriModel->getById($id_materi);
        $data['guru'] = $this->GuruModel->asObject()->find(session()->get('id'));
        $data['file'] = $this->FileModel->getMateriWithFile(decrypt_url($idmapel), decrypt_url($idkelas));

        return view('guru/materi/lihat-materi', $data);
    }
    public function edit_materi()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        if ($this->request->isAJAX()) {
            $materi = decrypt_url($this->request->getVar('id_materi'));
            $data_materi = $this->MateriModel->asObject()->find($materi);
            echo json_encode($data_materi);
        }
    }
    public function edit_materi_()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        $idmapel = encrypt_url($this->request->getVar('e_mapel'));
        $idkelas = encrypt_url($this->request->getVar('e_kelas'));
        $kode_materi = $this->request->getVar('e_kode_materi');

        // CEK APAKAH ADA FILE U=YANG DIPILIH
        $file_materi = $this->request->getFileMultiple('e_file_materi');
        if ($file_materi[0]->getError() != 4) {
            $data_file_materi = [];

            // FUNGSI UPLOAD FILE
            foreach ($file_materi as $file) {
                // Generate nama file Random
                $nama_file = str_replace(' ', '_', $file->getName());
                // Upload Gambar
                $file->move('assets/app-assets/file/', $nama_file);

                array_push($data_file_materi, [
                    'kode_file' => $this->request->getVar('e_kode_materi'),
                    'nama_file' => $nama_file
                ]);
            }

            $this->FileModel->insertBatch($data_file_materi);
        }
        
        $thumbs = $this->request->getPost();
        $link_video = [];
        if (isset($thumbs['e_text_materi'])) {
            foreach ($thumbs['e_text_materi'] as $thumb) {
                $link_video[] = $thumb;
            }
        }
        
        $this->MateriModel
            ->set('nama_materi', $this->request->getVar('e_nama_materi'))
            ->set('mapel', $this->request->getVar('e_mapel'))
            ->set('kelas', $this->request->getVar('e_kelas'))
            ->set('text_materi', json_encode($link_video))
            ->set('status', $this->request->getVar('e_status'))
            ->where('kode_materi', $kode_materi)
            ->update();


        session()->setFlashdata('pesan', "
            swal({
                title: 'Berhasil!',
                text: 'Materi telah diupdate',
                type: 'success',
                padding: '2em'
                });
            ");
        return redirect()->to('guru/materi/'.$idmapel.'/'.$idkelas);
    }
    public function chat_materi()
    {
        // if (session()->get('role') != 3) {
        //     return redirect()->to('auth');
        // }
        if ($this->request->isAJAX()) {
            $kode_materi = $this->request->getVar('kode_materi');
            $chat_materi = $this->request->getVar('chat_materi');
            $user = $this->GuruModel->asObject()->find(session('id'));

            $data = [
                'materi' => $kode_materi,
                'nama' => session()->get('nama'),
                'gambar' => $user->avatar,
                'email' => session()->get('email'),
                'text' => $chat_materi,
                'date_created' => time()
            ];

            $this->ChatmateriModel->save($data);
        }
    }
    public function get_chat_materi()
    {
        // if (session()->get('role') != 3) {
        //     return redirect()->to('auth');
        // }
        if ($this->request->isAJAX()) {
            $kode_materi = $this->request->getVar('kode_materi');
            $chat_materi = $this->ChatmateriModel->getAllByKodeMateri($kode_materi);

            foreach ($chat_materi as $chat) {
                if ($chat->email == session()->get('email')) {
                    echo '
                    <div class="media">
                        <div class="avatar avatar-sm avatar-indicators avatar-online">
                            <img alt="avatar" src="' . base_url('assets/app-assets/user/') . '/' . $chat->gambar . '" class="rounded-circle" />
                        </div>
                        <div class="media-body ml-2">
                            <h5 class="media-heading"><span class="media-title"> ' . $chat->nama . ' <span class="badge badge-primary">You</span></h5>
                            <p class="media-text" style="white-space: pre-line; margin-top: -20px;">
                                ' . $chat->text . '
                            </p>
                            <hr>
                        </div>
                    </div>
                ';
                } else {
                    echo '
                    <div class="media">
                        <div class="avatar avatar-sm avatar-indicators avatar-online">
                            <img alt="avatar" src="' . base_url('assets/app-assets/user/') . '/' . $chat->gambar . '" class="rounded-circle" />
                        </div>
                        <div class="media-body ml-2">
                            <h5 class="media-heading"><span class="media-title"> ' . $chat->nama . '</h5>
                            <p class="media-text" style="white-space: pre-line; margin-top: -20px;">
                                ' . $chat->text . '
                            </p>
                            <hr>
                        </div>
                    </div>
                ';
                }
            }
            exit;
        }
    }
    public function hapus_materi($kode_materi, $idmapel, $idkelas)
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        $this->FileModel
            ->where('kode_file', decrypt_url($kode_materi))
            ->delete();

        $this->MateriModel
            ->where('kode_materi', decrypt_url($kode_materi))
            ->delete();

        $this->ChatmateriModel
            ->where('materi', decrypt_url($kode_materi))
            ->delete();

        session()->setFlashdata('pesan', "
            swal({
                title: 'Berhasil!',
                text: 'Materi telah di hapus',
                type: 'success',
                padding: '2em'
                });
            ");
        return redirect()->to('guru/materi/'.$idmapel.'/'.$idkelas);
    }
    // END::MATERI
    
    public function delete_file()
    {
        // optional: cek ajax
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }
    
        $id_file = $this->request->getPost('id_file');
    
        // ambil data dari DB (AMAN)
        $file = $this->FileModel
            ->where('id_file', $id_file)
            ->first();
    
        if (!$file) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data file tidak ditemukan'
            ]);
        }
    
        $path = FCPATH . 'assets/app-assets/file/' . $file['nama_file'];
    
        // hapus file fisik (jika ada)
        if (file_exists($path)) {
            unlink($path);
        }
    
        // hapus database
        $this->FileModel->delete($id_file);
    
        return $this->response->setJSON([
            'status' => 'success'
        ]);
    }


    // START::TUGAS
    public function tugas()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_ujian'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_kategori'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_bank_soal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['tugas'] = $this->TugasModel->getAllByGuru(session()->get('id'));

        $data['guru_kelas'] = $this->GurukelasModel->getALLByGuru(session()->get('id'));
        $data['guru_mapel'] = $this->GuruMapelModel->getALLByGuru(session()->get('id'));
        $data['guru'] = $this->GuruModel->asObject()->find(session()->get('id'));

        return view('guru/tugas/list', $data);
    }
    public function tambah_tugas()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        $data_tugas = [
            'kode_tugas' => $this->request->getVar('kode_tugas'),
            'kelas' => $this->request->getVar('kelas'),
            'mapel' => $this->request->getVar('mapel'),
            'guru' => session()->get('id'),
            'nama_tugas' => $this->request->getVar('nama_tugas'),
            'deskripsi' => $this->request->getVar('deskripsi'),
            'date_created' => time(),
            'due_date' => $this->request->getVar('tgl') . ' ' . $this->request->getVar('jam')
        ];
        $kelas = $this->KelasModel->asObject()->find($this->request->getVar('kelas'));

        $siswa = $this->SiswaModel
            ->where('kelas', $this->request->getVar('kelas'))
            ->get()->getResultObject();

        if (count($siswa) == 0) {
            session()->setFlashdata('pesan', "
                swal({
                    title: 'Oops!',
                    text: 'Belum ada siswa dikelas ini',
                    type: 'error',
                    padding: '2em'
                    });
                ");
            return redirect()->to('guru/tugas');
        }

        $siswa_tugas = [];
        foreach ($siswa as $s) {
            array_push($siswa_tugas, array(
                'tugas' => $this->request->getVar('kode_tugas'),
                'siswa' => $s->id_siswa
            ));
        }


        // CEK APAKAH ADA FILE U=YANG DIPILIH
        $file_materi = $this->request->getFileMultiple('file_materi');
        if ($file_materi[0]->getError() != 4) {
            $data_file_materi = [];

            // FUNGSI UPLOAD FILE
            foreach ($file_materi as $file) {
                // Generate nama file Random
                $nama_file = str_replace(' ', '_', $file->getName());
                // Upload Gambar
                $file->move('assets/app-assets/file', $nama_file);

                array_push($data_file_materi, [
                    'kode_file' => $this->request->getVar('kode_tugas'),
                    'nama_file' => $nama_file
                ]);
            }

            $this->FileModel->insertBatch($data_file_materi);
        }

        $video_materi = $this->request->getFileMultiple('video_materi');
        if ($video_materi[0]->getError() != 4) {
            $data_video_materi = [];

            // FUNGSI UPLOAD FILE
            foreach ($video_materi as $file) {
                // Generate nama file Random
                $nama_file = str_replace(' ', '_', $file->getName());
                // Upload Gambar
                $file->move('assets/app-assets/file', $nama_file);

                array_push($data_video_materi, [
                    'kode_file' => $this->request->getVar('kode_tugas'),
                    'nama_file' => $nama_file
                ]);
            }

            $this->FileModel->insertBatch($data_video_materi);
        }

        // INSERT DATA TUGAS
        $this->TugasModel->save($data_tugas);
        // INSERT DATA TUGAS SISWA
        $this->Tugas_siswaModel->insertBatch($siswa_tugas);

        session()->setFlashdata('pesan', "
            swal({
                title: 'Berhasil!',
                text: 'Tugas telah dibuat',
                type: 'success',
                padding: '2em'
                });
            ");
        return redirect()->to('guru/tugas');
    }
    public function edit_tugas()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        if ($this->request->isAJAX()) {
            $tugas = decrypt_url($this->request->getVar('id_tugas'));
            $data_tugas = $this->TugasModel->asObject()->find($tugas);
            echo json_encode($data_tugas);
        }
    }
    public function edit_tugas_()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        $kode_tugas = $this->request->getVar('e_kode_tugas');

        // CEK APAKAH ADA FILE U=YANG DIPILIH
        $file_materi = $this->request->getFileMultiple('e_file_materi');
        if ($file_materi[0]->getError() != 4) {
            $data_file_materi = [];

            // FUNGSI UPLOAD FILE
            foreach ($file_materi as $file) {
                // Generate nama file Random
                $nama_file = str_replace(' ', '_', $file->getName());
                // Upload Gambar
                $file->move('assets/app-assets/file', $nama_file);

                array_push($data_file_materi, [
                    'kode_file' => $this->request->getVar('e_kode_tugas'),
                    'nama_file' => $nama_file
                ]);
            }

            $this->FileModel->insertBatch($data_file_materi);
        }

        $video_materi = $this->request->getFileMultiple('e_video_materi');
        if ($video_materi[0]->getError() != 4) {
            $data_video_materi = [];

            // FUNGSI UPLOAD FILE
            foreach ($video_materi as $file) {
                // Generate nama file Random
                $nama_file = str_replace(' ', '_', $file->getName());
                // Upload Gambar
                $file->move('assets/app-assets/file', $nama_file);

                array_push($data_video_materi, [
                    'kode_file' => $this->request->getVar('e_kode_tugas'),
                    'nama_file' => $nama_file
                ]);
            }

            $this->FileModel->insertBatch($data_video_materi);
        }

        $this->TugasModel
            ->set('nama_tugas', $this->request->getVar('e_nama_tugas'))
            ->set('mapel', $this->request->getVar('e_mapel'))
            ->set('kelas', $this->request->getVar('e_kelas'))
            ->set('deskripsi', $this->request->getVar('e_deskripsi'))
            ->set('due_date', $this->request->getVar('e_tgl') . ' ' . $this->request->getVar('e_jam'))
            ->where('kode_tugas', $kode_tugas)
            ->update();


        session()->setFlashdata('pesan', "
            swal({
                title: 'Berhasil!',
                text: 'Tugas telah diupdate',
                type: 'success',
                padding: '2em'
                });
            ");
        return redirect()->to('guru/tugas');
    }
    public function lihat_tugas($data_kode_tugas, $data_id_guru)
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_ujian'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_kategori'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_bank_soal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['tugas'] = $this->TugasModel->getBykodeTugas(decrypt_url($data_kode_tugas));
        // dd(decrypt_url($data_kode_tugas));
        $data['guru'] = $this->GuruModel->asObject()->find(session()->get('id'));

        $data['file'] = $this->FileModel->getAllByKode(decrypt_url($data_kode_tugas));
        $data['tugas_siswa'] = $this->Tugas_siswaModel->getAllByKodeTugas(decrypt_url($data_kode_tugas));

        return view('guru/tugas/lihat-tugas', $data);
    }
    public function get_chat_tugas()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        if ($this->request->isAJAX()) {
            $kode_tugas = $this->request->getVar('kode_tugas');
            $chat_tugas = $this->ChattugasModel->getAllByKodeTugas($kode_tugas);

            foreach ($chat_tugas as $chat) {
                if ($chat->email == session()->get('email')) {
                    echo '
                    <div class="media">
                        <div class="avatar avatar-sm avatar-indicators avatar-online">
                            <img alt="avatar" src="' . base_url('assets/app-assets/user/') . '/' . $chat->gambar . '" class="rounded-circle" />
                        </div>
                        <div class="media-body ml-2">
                            <h5 class="media-heading"><span class="media-title"> ' . $chat->nama . ' <span class="badge badge-primary">You</span></h5>
                            <p class="media-text" style="white-space: pre-line; margin-top: -20px;">
                                ' . $chat->text . '
                            </p>
                            <hr>
                        </div>
                    </div>
                ';
                } else {
                    echo '
                    <div class="media">
                        <div class="avatar avatar-sm avatar-indicators avatar-online">
                            <img alt="avatar" src="' . base_url('assets/app-assets/user/') . '/' . $chat->gambar . '" class="rounded-circle" />
                        </div>
                        <div class="media-body ml-2">
                            <h5 class="media-heading"><span class="media-title"> ' . $chat->nama . '</h5>
                            <p class="media-text" style="white-space: pre-line; margin-top: -20px;">
                                ' . $chat->text . '
                            </p>
                            <hr>
                        </div>
                    </div>
                ';
                }
            }
            exit;
        }
    }
    public function chat_tugas()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        if ($this->request->isAJAX()) {
            $kode_tugas = $this->request->getVar('kode_tugas');
            $chat_tugas = $this->request->getVar('chat_tugas');
            $user = $this->GuruModel->asObject()->find(session()->get('id'));

            $data = [
                'tugas' => $kode_tugas,
                'nama' => $user->nama_guru,
                'email' => $user->email,
                'gambar' => $user->avatar,
                'text' => $chat_tugas,
                'date_created' => time()
            ];

            $this->ChattugasModel->save($data);
        }
    }
    public function tugas_siswa($kode_tugas, $id_siswa)
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_ujian'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_kategori'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_bank_soal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['siswa'] = $this->SiswaModel->asObject()->find(decrypt_url($id_siswa));

        $data['tugas_siswa'] = $this->Tugas_siswaModel
            ->where('tugas', decrypt_url($kode_tugas))
            ->where('siswa', decrypt_url($id_siswa))
            ->get()->getRowObject();

        $data['tugas'] = $this->TugasModel->getBykodeTugas(decrypt_url($kode_tugas));
        $data['file'] = $this->FileModel->getAllByKode($data['tugas_siswa']->file_siswa);
        $data['guru'] = $this->GuruModel->asObject()->find(session()->get('id'));

        return view('guru/tugas/tugas-siswa', $data);
    }
    public function nilai_tugas()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        $this->Tugas_siswaModel
            ->set('nilai', $this->request->getVar('nilai'))
            ->set('catatan_guru', $this->request->getVar('catatan_guru'))
            ->where('tugas', $this->request->getVar('tugas'))
            ->where('siswa', $this->request->getVar('siswa'))
            ->update();

        session()->setFlashdata('pesan', "
            swal({
                title: 'Berhasil!',
                text: 'Tugas telah Dinilai',
                type: 'success',
                padding: '2em'
                });
        ");
        return redirect()->to('guru/lihat_tugas/' .  encrypt_url($this->request->getVar('tugas')) . '/' . encrypt_url($this->request->getVar('siswa')));
    }
    public function hapus_tugas($kode_tugas)
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        $this->FileModel
            ->where('kode_file', decrypt_url($kode_tugas))
            ->delete();

        $this->ChattugasModel
            ->where('tugas', decrypt_url($kode_tugas))
            ->delete();

        $this->Tugas_siswaModel
            ->where('tugas', decrypt_url($kode_tugas))
            ->delete();

        $this->TugasModel
            ->where('kode_tugas', decrypt_url($kode_tugas))
            ->delete();

        session()->setFlashdata('pesan', "
            swal({
                title: 'Berhasil!',
                text: 'Tugas telah di hapus',
                type: 'success',
                padding: '2em'
                });
            ");
        return redirect()->to('guru/tugas');
    }
    // END::TUGAS

    // START::UJIAN

    // START = UJIAN PG
    public function ujian()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_kategori'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_bank_soal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['ujian'] = $this->UjianMasterModel->getAllBykodeGuru(session()->get('id'));
        $data['guru'] = $this->GuruModel->asObject()->find(session()->get('id'));

        return view('guru/ujian/list', $data);
    }
    public function tambah_pg()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_kategori'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_bank_soal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['guru_kelas'] = $this->GurukelasModel->getALLByGuru(session()->get('id'));
        $data['guru_mapel'] = $this->GuruMapelModel->getALLByGuru(session()->get('id'));
        $data['guru'] = $this->GuruModel->asObject()->find(session()->get('id'));
        $data['kategori'] = $this->KategoriModel->getAll();
        return view('guru/ujian/tambah_pg', $data);
    }
    public function tambah_pg_()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        $nama_soal = $this->request->getVar('nama_soal');
        if ($nama_soal != null) {
            $siswa = $this->SiswaModel->getAllbyKelas($this->request->getVar('kelas'));
            if (count($siswa) == 0) {
                session()->setFlashdata('pesan', "
                swal({
                    title: 'Oops!',
                    text: 'Belum ada siswa dikelas ini',
                    type: 'error',
                    padding: '2em'
                    });
            ");
                return redirect()->to('guru/ujian');
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
            $this->UjianMasterModel->save($data_ujian);
            
            //UJIAN UNTUK SETIAP SISWA
            
            // foreach ($siswa as $su) {
            //   $data_ujian_per_siswa= [
            //         'id_siswa' => $su->id_siswa,
            //         'kode_ujian' => $kode_ujian,
            //         'nama_ujian' => $this->request->getVar('nama_ujian'),
            //         'guru' => session()->get('id'),
            //         'kelas' => $this->request->getVar('kelas'),
            //         'mapel' => $this->request->getVar('mapel'),
            //         'date_created' => time(),
            //         'waktu_mulai' => $this->request->getVar('tgl_mulai') . ' ' . $this->request->getVar('jam_mulai'),
            //         'waktu_berakhir' => $this->request->getVar('tgl_berakhir') . ' ' . $this->request->getVar('jam_berakhir'),
            //     ];
            //       $this->UjianModel->save($data_ujian_per_siswa);
            // }

            //END
            
            
            
            $status_ujian = [
                'kode_ujian' => $kode_ujian,
                'status' => $this->request->getVar('status_ujian'),
            ];
            // END DATA UJIAN
            $this->StatusUjianModel->save($status_ujian);
            

            // DATA DETAIL UJIAN PG

            // $data_detail_ujian = array();
            $index = 0;
            foreach ($nama_soal as $nama) {
                $dataSoal = $this->UjianDetailModel->getBySoalKodeUjian($nama, $kode_ujian);
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
                    $this->UjianDetailModel->save($data_detail_ujian);
                }

                $index++;
            }
            // END DATA DETAIL UJIAN PG
            // $ujian_detail = $this->UjianDetailModel->getAllBykodeUjian($kode_ujian);
            // $data_ujian_siswa = [];
            // foreach ($ujian_detail as $uj) {
            //     foreach ($siswa as $su) {
            //         array_push($data_ujian_siswa, [
            //             'ujian_id' => $uj->id_detail_ujian,
            //             'ujian' => $uj->kode_ujian,
            //             'siswa' => $su->id_siswa,
            //         ]);
            //     }
            // }
            // $this->UjianSiswaModel->insertBatch($data_ujian_siswa);

            session()->setFlashdata('pesan', "
                            swal({
                                title: 'Berhasil!',
                                text: 'Ujian telah dibuat',
                                type: 'success',
                                padding: '2em'
                                });
                            ");
        } else {
            session()->setFlashdata('pesan', "
                            swal({
                                title: 'Gagal!',
                                text: 'Ujian Tidak dapat di tambah karna tidak ada soal yang di ditambah',
                                type: 'info',
                                padding: '2em'
                                });
                            ");
        }
        return redirect()->to('guru/ujian?pesan=success');
    }
    
    public function ubah_status_ujian(){
        
        $data = $this->StatusUjianModel
            ->where('kode_ujian', $this->request->getVar('kode_ujian'))
            ->get()->getRowObject();
            
        if(!empty($data)){
           if($data->status == 'T'){
                $status = 'A';
            }else{
                $status = 'T';
            }
        }else{
            $status = 'A';
        }
        
        if(!empty($data)){
            $status_ujian = [
                    'idstatusujian' => $data->idstatusujian,
                    'kode_ujian' => $data->kode_ujian,
                    'status' => $status,
                ];
                // END DATA UJIAN
                $this->StatusUjianModel->save($status_ujian);
        }else{
            $status_ujian = [
                    'kode_ujian' => $this->request->getVar('kode_ujian'),
                    'status' => $status,
                ];
                // END DATA UJIAN
                $this->StatusUjianModel->save($status_ujian);
        }
        session()->setFlashdata('pesan', "
                            swal({
                                title: 'Berhasil!',
                                text: 'Ujian berhasil diubah',
                                type: 'success',
                                padding: '2em'
                                });
                            ");
        return redirect()->to('guru/ujian');
    }
    public function excel_pg()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        $siswa = $this->SiswaModel->getAllbyKelas($this->request->getVar('e_kelas'));
        $guru = $this->GuruModel->asObject()->find(session()->get('id'));
        if (count($siswa) == 0) {
            session()->setFlashdata('pesan', "
                swal({
                    title: 'Oops!',
                    text: 'Belum ada siswa dikelas ini',
                    type: 'error',
                    padding: '2em'
                    });
            ");
            return redirect()->to('guru/ujian');
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
        $nama_soal = $this->request->getVar('e_nama_soal');
        $data_detail_ujian = array();
        $index = 0;

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


        $this->UjianMasterModel->save($data_ujian);
        $this->UjianDetailModel->insertBatch($data_detail_ujian);

       

        session()->setFlashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Ujian telah dibuat',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
        return redirect()->to('guru/ujian?pesan=success');
    }
    public function lihat_ujian($kode_ujian)
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_kategori'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_bank_soal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['ujian'] = $this->UjianMasterModel->getBykode(decrypt_url($kode_ujian));
        $data['detail_ujian'] = $this->UjianDetailModel->getAllBykodeUjian(decrypt_url($kode_ujian));

        $data['siswa'] = $this->SiswaModel->getAllbyKelasUjian($data['ujian']->kelas, $data['ujian']->kode_ujian);


        // $UjianSiswaModel->date_kirim_ujian($ujian->kode_ujian, $s->id_siswa);

        $data['guru'] = $this->GuruModel->asObject()->find(session()->get('id'));
        return view('guru/ujian/pg-lihat', $data);
    }

    public function edit_ujian($kode_ujian)
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_kategori'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_bank_soal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['detail_ujian'] = $this->UjianDetailModel->getAllBykodeUjian(decrypt_url($kode_ujian));

        $data['ujian'] = $this->UjianMasterModel->getBykode(decrypt_url($kode_ujian));

        $data['siswa'] = $this->SiswaModel->getAllbyKelas($data['ujian']->kelas);
        $data['guru'] = $this->GuruModel->asObject()->find(session()->get('id'));
        $data['guru_kelas'] = $this->GurukelasModel->getALLByGuru(session()->get('id'));
        $data['guru_mapel'] = $this->GuruMapelModel->getALLByGuru(session()->get('id'));
        return view('guru/ujian/edit_pg', $data);
    }

    public function update_pg_()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }

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


        $this->UjianMasterModel->set($data_ujian)->where('id_ujian', $this->request->getVar('id_ujian'))->update();
        
        $dataUjianUpdate = $this->UjianModel->where('kode_ujian', $this->request->getVar('kode_ujian'))->where('status', 'B')->get()->getResultObject();
        foreach($dataUjianUpdate as $rowsUjian){
            $update_ujian= [
                            'kode_ujian' => $this->request->getVar('kode_ujian'),
                            'nama_ujian' => $this->request->getVar('nama_ujian'),
                            'guru' => session()->get('id'),
                            'kelas' => $this->request->getVar('kelas'),
                            'mapel' => $this->request->getVar('mapel'),
                            'date_created' => time(),
                        ];

                $this->UjianModel->set($update_ujian)->where('id_ujian', $rowsUjian->id_ujian)->update();
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
                  $this->UjianModel->save($data_ujian_per_siswa);
            }
            
            
            
            $ujian_detail = $this->UjianDetailModel->getAllBykodeUjian($this->request->getVar('kode_ujian'));
            $data_detail_ujian = array();
            $index = 0;
            foreach ($ujian_detail as $uj) {
                foreach ($id_siswa as $rows) {
                    $ujian_siswa = $this->UjianSiswaModel->getAll($this->request->getVar('kode_ujian'), $rows);

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
            $this->UjianSiswaModel->insertBatch($data_detail_ujian);
            
            
            
            
        }

        //untuk mereset ujian
        if ($this->request->getVar('id_siswa_reset') !== null) {
            $id_siswa_reset = $this->request->getVar('id_siswa_reset');
            foreach ($id_siswa_reset as $row) {
                $data_ujian_siswa = $this->UjianSiswaModel->where('ujian', $this->request->getVar('kode_ujian'))->where('siswa', $row)->get()->getResultObject();
                foreach ($data_ujian_siswa as $rows) {
                    $data_detail_siswa = [
                        'jawaban'       => null,
                        'benar'         => null,
                        'jam'           => null,
                        'status'        => null,
                    ];
                    $this->UjianSiswaModel->set($data_detail_siswa)->where('id_ujian_siswa', $rows->id_ujian_siswa)->update();
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

                $this->UjianModel->insert($data_ujian_per_siswa);
                //end ujian remedial
            }
        }
        
        


        session()->setFlashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Ujian telah dibuat',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
        return redirect()->to('guru/ujian?pesan=success');
    }

    public function edit_soal($id_detail_ujian)
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_kategori'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_bank_soal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['detail_ujian'] = $this->UjianDetailModel->getAllByiddetailujian(decrypt_url($id_detail_ujian));
        $data['guru'] = $this->GuruModel->asObject()->find(session()->get('id'));
        $data['guru_kelas'] = $this->GurukelasModel->getALLByGuru(session()->get('id'));
        $data['guru_mapel'] = $this->GuruMapelModel->getALLByGuru(session()->get('id'));
        return view('guru/ujian/edit_soal', $data);
    }

    public function update_soal_()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }

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


        $this->UjianDetailModel->set($data_detail_ujian)->where('id_detail_ujian', $this->request->getVar('id_detail_ujian'))->update();
        session()->setFlashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Soal telah diubah',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
        return redirect()->to('guru/edit_ujian/' . encrypt_url($this->request->getVar('kode_ujian')) . '?pesan=success');
    }

    public function pg_siswa($id_siswa, $kode_ujian)
    {
        //return decrypt_url($id_siswa);

        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_kategori'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_bank_soal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['ujian'] = $this->UjianMasterModel->getBykode(decrypt_url($kode_ujian));
        $data['detail_ujian'] = $this->UjianDetailModel->getAllBykodeUjian(decrypt_url($kode_ujian));
        $data['siswa'] = $this->SiswaModel->asObject()->find(decrypt_url($id_siswa));
        $data['guru'] = $this->GuruModel->asObject()->find(session()->get('id'));

        $data['ujian_siswa'] = $this->UjianSiswaModel
            ->where('ujian', decrypt_url($kode_ujian))
            ->where('siswa', decrypt_url($id_siswa))
            ->get()->getResultObject();

        if (count($data['ujian_siswa']) <= 0) {
            session()->setFlashdata('pesan', "
                        swal({
                            title: 'Warning!',
                            text: 'Siswa ini tidak bisa mengikuti ujian dikarenakan akun terdaftar setelah ujian dibuat',
                            type: 'warning',
                            padding: '2em'
                            });
                        ");
            $url = 'guru/lihat_ujian/' . $kode_ujian . '/' . encrypt_url(session()->get('id'));
            return redirect()->to($url);
        }

        $data['jawaban_benar'] = $this->UjianSiswaModel->benar(decrypt_url($kode_ujian), decrypt_url($id_siswa), 1);
        $data['jawaban_salah'] = $this->UjianSiswaModel->salah(decrypt_url($kode_ujian), decrypt_url($id_siswa), 0);
        $data['tidak_dijawab'] = $this->UjianSiswaModel->belum_terjawab(decrypt_url($kode_ujian), decrypt_url($id_siswa));

        return view('guru/ujian/pg-siswa', $data);
    }
    // END = UJIAN PG

    // START = UJIAN ESSAY
    public function tambah_essay()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_kategori'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_bank_soal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['guru_kelas'] = $this->GurukelasModel->getALLByGuru(session()->get('id'));
        $data['guru_mapel'] = $this->GuruMapelModel->getALLByGuru(session()->get('id'));
        $data['guru'] = $this->GuruModel->asObject()->find(session()->get('id'));
        return view('guru/ujian/tambah_essay', $data);
    }
    public function tambah_essay_()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        $siswa = $this->SiswaModel->getAllbyKelas($this->request->getVar('kelas'));
        if (count($siswa) == 0) {
            session()->setFlashdata('pesan', "
                swal({
                    title: 'Oops!',
                    text: 'Belum ada siswa dikelas ini',
                    type: 'error',
                    padding: '2em'
                });
            ");
            return redirect()->to('guru/ujian');
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
            'jenis_ujian' => 1
        ];
        // END DATA UJIAN


        // DATA DETAIL UJIAN ESSAY
        $nama_soal = $this->request->getVar('soal');
        $data_detail_essay = array();
        foreach ($nama_soal as $nama) {
            array_push($data_detail_essay, array(
                'kode_ujian' => $kode_ujian,
                'soal' => $nama,
            ));
        }
        // END DATA DETAIL UJIAN ESSAY


        $this->UjianModel->save($data_ujian);
        $this->EssaydetailModel->insertBatch($data_detail_essay);

        $essay_detail = $this->EssaydetailModel->getAllBykodeUjian($kode_ujian);
        $data_ujian_siswa = [];
        foreach ($essay_detail as $ed) {
            foreach ($siswa as $su) {
                array_push($data_ujian_siswa, [
                    'essay_id' => $ed->id_essay_detail,
                    'ujian' => $ed->kode_ujian,
                    'siswa' => $su->id_siswa,
                ]);
            }
        }

        $this->EssaysiswaModel->insertBatch($data_ujian_siswa);

        session()->setFlashdata('pesan', "
            swal({
                title: 'Berhasil!',
                text: 'Ujian telah dibuat',
                type: 'success',
                padding: '2em'
            });
        ");
        return redirect()->to('guru/ujian?pesan=success');
    }
    public function lihat_essay($kode_ujian, $id_guru)
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_kategori'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_bank_soal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['ujian'] = $this->UjianModel->getBykode(decrypt_url($kode_ujian));
        $data['essay_detail'] = $this->EssaydetailModel->getAllBykodeUjian(decrypt_url($kode_ujian));
        $data['siswa'] = $this->SiswaModel->getAllbyKelas($data['ujian']->kelas);

        $data['guru'] = $this->GuruModel->asObject()->find(session()->get('id'));
        return view('guru/ujian/essay-lihat', $data);
    }
    public function essay_siswa($id_siswa, $kode_ujian)
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }

        $essay_siswa = $this->EssaysiswaModel
            ->where('ujian', decrypt_url($kode_ujian))
            ->where('siswa', decrypt_url($id_siswa))
            ->get()->getResultObject();

        if (count($essay_siswa) <= 0) {
            session()->setFlashdata('pesan', "
                swal({
                    title: 'Warning!',
                    text: 'Siswa ini tidak bisa mengikuti ujian dikarenakan akun terdaftar setelah ujian dibuat',
                    type: 'warning',
                    padding: '2em'
                });
            ");
            $url = '/guru/lihat_essay/' . $kode_ujian . '/' . encrypt_url(session()->get('id'));
            return redirect()->to($url);
        }

        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_kategori'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_bank_soal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['ujian'] = $this->UjianModel->getBykode(decrypt_url($kode_ujian));
        $data['essay_detail'] = $this->EssaydetailModel->getAllBykodeUjian(decrypt_url($kode_ujian));
        $data['essay_siswa'] = $essay_siswa;
        $data['siswa'] = $this->SiswaModel->asObject()->find(decrypt_url($id_siswa));

        $data['guru'] = $this->GuruModel->asObject()->find(session()->get('id'));
        return view('guru/ujian/essay-siswa', $data);
    }
    public function nilai_essay()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        $kode_ujian = $this->request->getVar('kode_ujian');
        $siswa = $this->request->getVar('siswa');
        // $ujian = $this->db->get_where('ujian', ['kode_ujian' => $kode_ujian])->row();

        $essay_detail = $this->EssaysiswaModel
            ->where('ujian', $kode_ujian)
            ->where('siswa', $siswa)
            ->get()->getResultObject();

        foreach ($essay_detail as $ed) {
            $score = $this->request->getVar("$ed->id_essay_siswa");

            $this->EssaysiswaModel
                ->set('score', $score)
                ->where('id_essay_siswa', $ed->id_essay_siswa)
                ->update();
        }
        session()->setFlashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Ujian telah dinilai',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
        return redirect()->to('guru/lihat_essay/' . encrypt_url($kode_ujian) . '/' . encrypt_url(session()->get('id')));
    }
    // END = UJIAN ESSAY

    public function hapus_ujian($kode_ujian)
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        $ujian = $this->UjianMasterModel->getBykode(decrypt_url($kode_ujian));

        if ($ujian->jenis_ujian == 1) {

            $this->EssaysiswaModel
                ->where('ujian', decrypt_url($kode_ujian))
                ->delete();

            $this->EssaydetailModel
                ->where('kode_ujian', decrypt_url($kode_ujian))
                ->delete();

            $this->UjianMasterModel
                ->where('kode_ujian', decrypt_url($kode_ujian))
                ->delete();
        }

        if ($ujian->jenis_ujian == null) {

            $this->UjianSiswaModel
                ->where('ujian', decrypt_url($kode_ujian))
                ->delete();

            $this->UjianDetailModel
                ->where('kode_ujian', decrypt_url($kode_ujian))
                ->delete();

            $this->UjianMasterModel
                ->where('kode_ujian', decrypt_url($kode_ujian))
                ->delete();
        }

        session()->setFlashdata('pesan', "
            swal({
                title: 'Berhasil!',
                text: 'Ujian telah di hapus',
                type: 'success',
                padding: '2em'
            });
        ");
        return redirect()->to('guru/ujian');
    }

    // END::UJIAN

    // START::SUMMERNOTE
    public function upload_summernote()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        $fileGambar = $this->request->getFile('image');
        // Generate nama file Random
        $nama_gambar = $fileGambar->getRandomName();
        // Upload Gambar
        $fileGambar->move('assets/app-assets/file', $nama_gambar);

        echo base_url() . '/assets/app-assets/file/' . $nama_gambar;
    }

    function delete_image()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        $src = $this->request->getVar('src');
        $file_name = str_replace(base_url() . '/', '', $src);
        if (unlink($file_name)) {
            echo 'File Delete Successfully';
        }
    }
    // END::SUMMERNOTE

    public function getBankSoal()
    {
        $RsData = $this->BankSoalModel->get_datatables();
        $no = $this->request->getPost('start');
        $data = array();

        if ($RsData->getNumRows() > 0) {
            foreach ($RsData->getResult() as $rowdata) {

                $no++;
                $row = array();
                // $row[] = $no;
                // $row[] = '<input type="checkbox" data-nama_soal="' . $rowdata->nama_soal . '" data-pg_1="' . substr($rowdata->pg_1, 3) . '" data-pg_2="' .  substr($rowdata->pg_2, 3) . '" data-pg_3="' . substr($rowdata->pg_3, 3) . '" data-pg_4="' . substr($rowdata->pg_4, 3) . '" data-pg_5="' . substr($rowdata->pg_5, 3) . '" data-jawaban="' . $rowdata->jawaban . '" data-penjelasan="' . $rowdata->penjelasan . '" id="tambahSoal"  class="check-item" name="id_bank_soal" value="' . $rowdata->id_bank_soal . '">';

                $row[] = '<input type="checkbox" data-id_bank_soal="' . $rowdata->id_bank_soal . '"  id="tambahSoal"  class="check-item">';
                $row[] = $rowdata->nama_soal;

                $data[] = $row;
            }
        }

        $output = array(
            "draw" => $this->request->getPost('draw'),
            "recordsTotal" => $this->BankSoalModel->count_all(),
            "recordsFiltered" => $this->BankSoalModel->count_filtered(),
            "data" => $data,
        );

        //output to json format
        return $this->response->setJSON($output);
    }


    //bank soal
    // START = UJIAN PG
    public function bank_soal()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_kategori'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_bank_soal'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['soal'] = $this->BankSoalModel->getAll();
        $data['guru'] = $this->GuruModel->asObject()->find(session()->get('id'));

        return view('guru/bank_soal/list', $data);
    }
    public function tambah_bank_soal_pg()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_kategori'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_bank_soal'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['kategori'] = $this->KategoriModel->getAll();
        $data['guru'] = $this->GuruModel->asObject()->find(session()->get('id'));
        return view('guru/bank_soal/tambah_pg', $data);
    }

    public function tambah_bank_soal()
    {

        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        if ($this->request->isAJAX()) {
            $id_bank_soal = ($this->request->getVar('id_bank_soal'));
            $data = $this->BankSoalModel->getById($id_bank_soal);
            echo json_encode($data);
        }
    }
    public function tambah_bank_soal_pg_()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }

        // DATA DETAIL UJIAN PG
        $nama_soal = $this->request->getVar('nama_soal');
        $data_detail_ujian = array();
        $index = 0;
        foreach ($nama_soal as $nama) {
            array_push($data_detail_ujian, array(
                'id_kategori' => $this->request->getVar('id_kategori')[$index],
                'nama_soal' => $nama,
                'pg_1' => 'A. ' . $this->request->getVar('pg_1')[$index],
                'pg_2' => 'B. ' . $this->request->getVar('pg_2')[$index],
                'pg_3' => 'C. ' . $this->request->getVar('pg_3')[$index],
                'pg_4' => 'D. ' . $this->request->getVar('pg_4')[$index],
                'pg_5' => 'E. ' . $this->request->getVar('pg_5')[$index],
                'jawaban' => $this->request->getVar('jawaban')[$index],
                'penjelasan' => $this->request->getVar('penjelasan')[$index],
            ));

            $index++;
        }
        // END DATA DETAIL UJIAN PG

        $this->BankSoalModel->insertBatch($data_detail_ujian);

        // $ujian_detail = $this->UjianDetailModel->getAllBykodeUjian($kode_ujian);
        // $data_ujian_siswa = [];
        // foreach ($ujian_detail as $uj) {
        //     foreach ($siswa as $su) {
        //         array_push($data_ujian_siswa, [
        //             'ujian_id' => $uj->id_detail_ujian,
        //             'ujian' => $uj->kode_ujian,
        //             'siswa' => $su->id_siswa,
        //         ]);
        //     }
        // }

        // $this->UjianSiswaModel->insertBatch($data_ujian_siswa);

        session()->setFlashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Soal telah dibuat',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
        return redirect()->to('guru/bank_soal?pesan=success');
    }
    public function excel_bank_soal_pg()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }


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

                $dataSoal = $this->BankSoalModel->getBySoal($kolom[1]);
                if ($dataSoal == null) {
                    if ($kolom[1] != null) {
                        $data_detail_ujian = [
                            'id_kategori' => $kolom[0],
                            'nama_soal' => $kolom[1],
                            'pg_1' => 'A. ' . $kolom[2],
                            'pg_2' => 'B. ' . $kolom[3],
                            'pg_3' => 'C. ' . $kolom[4],
                            'pg_4' => 'D. ' . $kolom[5],
                            'pg_5' => 'E. ' . $kolom[6],
                            'jawaban' => $kolom[7] == null ? null : $kolom[7],
                            'penjelasan' => $kolom[8] == null ? null : $kolom[8],
                        ];
                        // END DATA UJIAN
                        $this->BankSoalModel->save($data_detail_ujian);
                    }
                }
            }
        }

        session()->setFlashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Ujian telah dibuat',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
        return redirect()->to('guru/bank_soal?pesan=success');
    }


    public function edit_bank_soal($id_bank_soal)
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_kategori'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_bank_soal'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['soal'] = $this->BankSoalModel->getById(decrypt_url($id_bank_soal));
        $data['guru'] = $this->GuruModel->asObject()->find(session()->get('id'));
        $data['kategori'] = $this->KategoriModel->getAll();

        return view('guru/bank_soal/edit_pg', $data);
    }

    public function lihat_bank_soal($id_bank_soal)
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_kategori'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_bank_soal'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['soal'] = $this->BankSoalModel->getById(decrypt_url($id_bank_soal));
        $data['guru'] = $this->GuruModel->asObject()->find(session()->get('id'));
        $data['kategori'] = $this->KategoriModel->getAll();

        return view('guru/bank_soal/lihat_pg', $data);
    }



    public function update_bank_soal_()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }

        $data_detail_ujian = [
            'id_kategori' => $this->request->getVar('id_kategori'),
            'nama_soal' => $this->request->getVar('nama_soal'),
            'pg_1' => $this->request->getVar('pg_1'),
            'pg_2' => $this->request->getVar('pg_2'),
            'pg_3' => $this->request->getVar('pg_3'),
            'pg_4' => $this->request->getVar('pg_4'),
            'pg_5' => $this->request->getVar('pg_5'),
            'jawaban' => $this->request->getVar('jawaban'),
            'penjelasan' => $this->request->getVar('penjelasan'),
        ];


        $this->BankSoalModel->set($data_detail_ujian)->where('id_bank_soal', $this->request->getVar('id_bank_soal'))->update();
        session()->setFlashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Soal telah diubah',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
        return redirect()->to('guru/bank_soal/?pesan=success');
    }

    public function hapus_bank_soal($id_bank_soal)
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }


        $this->TagModel
            ->where('id_bank_soal', decrypt_url($id_bank_soal))
            ->delete();

        $this->BankSoalModel
            ->where('id_bank_soal', decrypt_url($id_bank_soal))
            ->delete();



        session()->setFlashdata('pesan', "
            swal({
                title: 'Berhasil!',
                text: 'Soal telah di hapus',
                type: 'success',
                padding: '2em'
            });
        ");
        return redirect()->to('guru/bank_soal');
    }
    // END = UJIAN PG
    //end bank soal

    //Kategori
    public function kategori()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_kategori'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_bank_soal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['kategori'] = $this->KategoriModel->getAll();
        $data['guru'] = $this->GuruModel->asObject()->find(session()->get('id'));

        return view('guru/kategori/list', $data);
    }
    public function tambah_kategori()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_kategori'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_bank_soal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['guru'] = $this->GuruModel->asObject()->find(session()->get('id'));
        return view('guru/kategori/tambah_', $data);
    }
    public function tambah_kategori_()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }

        // DATA DETAIL UJIAN PG
        $nama_kategori = $this->request->getVar('nama_kategori');

        $data_kategori = array(
            'nama_kategori' => $nama_kategori,
            'nama_kategori_slug' => url_title($nama_kategori, '-', TRUE),
        );
        // END DATA DETAIL UJIAN PG

        $this->KategoriModel->insert($data_kategori);



        session()->setFlashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Kategori telah dibuat',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
        return redirect()->to('guru/kategori?pesan=success');
    }


    public function edit_kategori()
    {

        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        if ($this->request->isAJAX()) {
            $id_kategori = decrypt_url($this->request->getVar('id_kategori'));
            $data = $this->KategoriModel->getById($id_kategori);
            echo json_encode($data);
        }
    }





    public function update_kategori()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }

        $nama_kategori = $this->request->getVar('nama_kategori');

        $data_kategori = array(
            'nama_kategori' => $nama_kategori,
            'nama_kategori_slug' => url_title($nama_kategori, '-', TRUE),
        );
        // END DATA DETAIL UJIAN PG



        $this->KategoriModel->set($data_kategori)->where('id_kategori', $this->request->getVar('id_kategori'))->update();
        session()->setFlashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Kategori telah diubah',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
        return redirect()->to('guru/kategori/?pesan=success');
    }

    public function hapus_kategori($id_kategori)
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }


        $this->KategoriModel
            ->where('id_kategori', decrypt_url($id_kategori))
            ->delete();

        session()->setFlashdata('pesan', "
            swal({
                title: 'Berhasil!',
                text: 'Soal telah di hapus',
                type: 'success',
                padding: '2em'
            });
        ");
        return redirect()->to('guru/kategori');
    }
    //end kategori

    function cetak_soal($kode_ujian)
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        
        $data['detail_ujian'] = $this->UjianDetailModel->getAllBykodeUjian(decrypt_url($kode_ujian));
        $data['ujian'] = $this->UjianMasterModel->getBykode(decrypt_url($kode_ujian));

        $view = view('guru/ujian/cetak/soal', $data);
        $data['view'] = $view;
        $data['response'] = $this->response->setContentType('application/pdf');
        $data['file'] =  $data['ujian']->nama_ujian;

        return view('guru/ujian/cetak/tampil', $data);
    }

    function cetak_soal_peserta($id_siswa, $kode_ujian)
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        $data['detail_ujian'] = $this->UjianDetailModel->getAllBykodeUjian(decrypt_url($kode_ujian));
        $data['ujian'] = $this->UjianMasterModel->getBykode(decrypt_url($kode_ujian));
        $data['id_siswa'] = decrypt_url($id_siswa);
        $data['siswa'] = $this->SiswaModel->asObject()->find(decrypt_url($id_siswa));
        $view = view('guru/ujian/cetak/soal_peserta', $data);
        $data['view'] = $view;
        $data['response'] = $this->response->setContentType('application/pdf');
        $data['file'] = $data['siswa']->nama_siswa;

        return view('guru/ujian/cetak/tampil', $data);
    }
}

<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\MapelModel;

class MateriController extends BaseController
{
    protected $mapelModel;
    protected $guruMapelModel;
    protected $guruKelasModel;
    protected $materiModel;
    protected $fileModel;
    protected $guruModel;
    protected $chatMateriModel;
    protected $kelasModel;
    protected $siswaModel;
    protected $materiSiswaModel;

    public function __construct()
    {
        $this->mapelModel = new MapelModel();
        $this->guruMapelModel = new \App\Models\GuruMapelModel();
        $this->guruKelasModel = new \App\Models\GuruKelasModel();
        $this->materiModel = new \App\Models\MateriModel();
        $this->fileModel = new \App\Models\FileModel();
        $this->guruModel = new \App\Models\GuruModel();
        $this->chatMateriModel = new \App\Models\ChatMateriModel();
        $this->kelasModel = new \App\Models\KelasModel();
        $this->siswaModel = new \App\Models\SiswaModel();
        $this->materiSiswaModel = new \App\Models\MateriSiswaModel();
    }

    // START::MATERI
    public function index()
    {
        $data['mapel'] = $this->guruMapelModel->join('guru_kelas', 'guru_kelas.guru=guru_mapel.guru')->where('guru_mapel.guru', session()->get('id'))->groupBy('guru_mapel.mapel')->get()->getResultObject();

        $data['guru_kelas'] = $this->guruKelasModel->getALLByGuru(session()->get('id'));
        $data['guru_mapel'] = $this->guruMapelModel->getALLByGuru(session()->get('id'));

        return view('guru/materi/index', $data);
    }

    // START::MATERI
    public function lihat($id, $kelas)
    {

        $data['idmapel'] = $id;
        $data['idkelas'] = $kelas;
        $data['id_mapel'] = decrypt_url($id);
        $data['id_kelas'] = decrypt_url($kelas);
        $data['materi'] = $this->materiModel->getAllByGuru(session()->get('id'), decrypt_url($id));

        $data['guru_kelas'] = $this->guruKelasModel->getALLByGuru(session()->get('id'));
        $data['guru_mapel'] = $this->guruMapelModel->getALLByGuru(session()->get('id'));

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


        $kelas = $this->kelasModel->asObject()->find($this->request->getVar('kelas'));

        $siswa = $this->siswaModel
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
            return redirect()->to('guru/materi/' . $idmapel . '/' . $idkelas);
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

            $this->fileModel->insertBatch($data_file_materi);
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
        $this->materiModel->save($data_materi);
        // INSERT DATA MATERI SISWA
        $this->materiSiswaModel->insertBatch($siswa_materi);

        session()->setFlashdata('pesan', "
            swal({
                title: 'Berhasil!',
                text: 'Materi telah dibuat',
                type: 'success',
                padding: '2em'
                });
            ");
        return redirect()->to('guru/materi/' . $idmapel . '/' . $idkelas);
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

        $data['materiAll'] = $this->materiModel->getAllByMapelKelas(decrypt_url($idmapel), decrypt_url($idkelas));
        $data['materi'] = $this->materiModel->getById($id_materi);
        $data['guru'] = $this->guruModel->asObject()->find(session()->get('id'));
        $data['file'] = $this->fileModel->getMateriWithFile(decrypt_url($idmapel), decrypt_url($idkelas));

        return view('guru/materi/lihat-materi', $data);
    }
    public function edit_materi()
    {
        if (session()->get('role') != 3) {
            return redirect()->to('auth');
        }
        if ($this->request->isAJAX()) {
            $materi = decrypt_url($this->request->getVar('id_materi'));
            $data_materi = $this->materiModel->asObject()->find($materi);
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

            $this->fileModel->insertBatch($data_file_materi);
        }

        $thumbs = $this->request->getPost();
        $link_video = [];
        if (isset($thumbs['e_text_materi'])) {
            foreach ($thumbs['e_text_materi'] as $thumb) {
                $link_video[] = $thumb;
            }
        }

        $this->materiModel
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
        return redirect()->to('guru/materi/' . $idmapel . '/' . $idkelas);
    }
    public function chat_materi()
    {
        // if (session()->get('role') != 3) {
        //     return redirect()->to('auth');
        // }
        if ($this->request->isAJAX()) {
            $kode_materi = $this->request->getVar('kode_materi');
            $chat_materi = $this->request->getVar('chat_materi');
            $user = $this->guruModel->asObject()->find(session('id'));

            $data = [
                'materi' => $kode_materi,
                'nama' => session()->get('nama'),
                'gambar' => $user->avatar,
                'email' => session()->get('email'),
                'text' => $chat_materi,
                'date_created' => time()
            ];

            $this->chatMateriModel->save($data);
        }
    }
    public function get_chat_materi()
    {
        // if (session()->get('role') != 3) {
        //     return redirect()->to('auth');
        // }
        if ($this->request->isAJAX()) {
            $kode_materi = $this->request->getVar('kode_materi');
            $chat_materi = $this->chatMateriModel->getAllByKodeMateri($kode_materi);

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
        $this->fileModel
            ->where('kode_file', decrypt_url($kode_materi))
            ->delete();

        $this->materiModel
            ->where('kode_materi', decrypt_url($kode_materi))
            ->delete();

        $this->chatMateriModel
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
        return redirect()->to('guru/materi/' . $idmapel . '/' . $idkelas);
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
        $file = $this->fileModel
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
        $this->fileModel->delete($id_file);

        return $this->response->setJSON([
            'status' => 'success'
        ]);
    }
}

<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class MateriController extends BaseController
{
    protected $affiliateModel;
    protected $transaksiModel;
    protected $mapelModel;
    protected $materiModel;
    protected $materiSiswaModel;
    protected $fileModel;
    protected $chatMateriModel;
    protected $siswaModel;

    public function __construct()
    {
        $this->affiliateModel = new \App\Models\AffiliateModel();
        $this->transaksiModel = new \App\Models\TransaksiModel();
        $this->mapelModel     = new \App\Models\MapelModel();
        $this->materiModel    = new \App\Models\MateriModel();
        $this->materiSiswaModel = new \App\Models\MateriSiswaModel();
        $this->fileModel      = new \App\Models\FileModel();
        $this->chatMateriModel = new \App\Models\ChatMateriModel();
        $this->siswaModel     = new \App\Models\SiswaModel();
    }
    public function index()
    {
        $userId = session()->get('id');

        $data = [
            'title'       => 'Daftar Materi',
            'breadcrumbs' => [
                ['title' => 'Materi', 'url' => base_url('sw-siswa/materi')],
            ],
            'affiliate'   => $this->affiliateModel->where('user_id', $userId)->first(),
        ];

        // 1. Ambil semua idmapel milik siswa dalam satu query
        $siswa = $this->transaksiModel
            ->select('detail_transaksi.idmapel')
            ->join('detail_transaksi', 'transaksi.idtransaksi = detail_transaksi.idtransaksi')
            ->where('transaksi.status', 'S')
            ->where('idsiswa', $userId)
            ->groupBy('detail_transaksi.idmapel')
            ->get()
            ->getResultObject();

        $data['modul'] = array();
        foreach ($siswa as  $r) {
            $modul = $this->mapelModel->getAllIdSiswa($r->idmapel);

            foreach ($modul as $m) {
                // PERUBAHAN: Kelompokkan modul berdasarkan nama_kelas
                // (Menggunakan fallback string jika field nama_kelas tidak di-select di model)
                $namaKelas = !empty($m->nama_kelas) ? $m->nama_kelas : 'Paket Kelas (ID: ' . $m->id_kelas . ')';
                
                $data['modul'][$namaKelas][] = $m;
            }
        }

        return view('siswa/materi/list', $data);
    }



    public function lihatMateri($kode, $idmapel, $idkelas)
    {
        try {
            $dec_kode    = decrypt_url($kode);
            $dec_idmapel = decrypt_url($idmapel);
            $dec_idkelas = decrypt_url($idkelas);

            $data = [
                'breadcrumbs' => [
                    ['title' => 'Materi', 'url' => base_url('sw-siswa/materi')],
                    ['title' => 'List Video Materi', 'url' => '#'],
                ],
                'materiAll' => $this->materiModel->getAllByMapelKelas($dec_idmapel, $dec_idkelas),
                'materi'    => $this->materiModel->getBykodeMateri($dec_kode),
                'file'      => $this->fileModel->getAllByKode($dec_kode),
            ];

            $materi_siswa = $this->materiSiswaModel
                ->join('siswa', 'materi_siswa.siswa=siswa.id_siswa')
                ->where('materi_siswa.materi', decrypt_url($kode))
                ->where('siswa.email', session()->get('email'))
                ->get()->getRowObject();

            if ($materi_siswa) {
                $this->materiSiswaModel->where('id_materi_siswa', $materi_siswa->id_materi_siswa)->delete();
            }

            $cekDataMateri = $this->materiModel->where('kode_materi', $dec_kode)->first();
            $data['link'] = '';
            $data['linkadmin'] = '';
            if ($cekDataMateri) {
                $data['link'] =  encrypt_url($cekDataMateri['id_materi']) . '/' . $idmapel . '/' . $idkelas;
                $data['linkadmin'] =  $idmapel . '/' . $idkelas . '/' . encrypt_url($cekDataMateri['guru']);
            }

            return view('siswa/materi/lihat-materi', $data);
        } catch (\Exception $e) {
            return redirect()->to('sw-siswa')->with('pesan', 'Materi tidak ditemukan atau akses tidak sah.');
        }
    }

    public function getFileMateri()
    {
        if ($this->request->isAJAX()) {
            $kode_materi = $this->request->getPost('kode_materi');
            $file = $this->fileModel->getAllByKode($kode_materi);
            $html = '';

            if (empty($file)) {
                $html = '
            <div class="text-center py-5">
                <i class="ki-outline ki-cloud-slash fs-3x text-muted mb-3"></i>
                <p class="text-gray-600">Tidak ada materi softcopy tersedia untuk video ini.</p>
            </div>';
            } else {
                $html .= '<div class="d-flex flex-column">';
                foreach ($file as $m) {
                    if ($m->nama_file) {
                        $namaTampil = str_replace('_', ' ', pathinfo($m->nama_file, PATHINFO_FILENAME));
                        $urlFile = base_url('assets/app-assets/file/' . $m->nama_file);

                        $html .= '
                    <a href="javascript:void(0)" 
                    data-bs-toggle="modal" 
                    data-bs-target="#pdfPreviewModal" 
                    data-file-url="' . $urlFile . '" 
                    data-file-name="' . $namaTampil . '"
                    class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary d-flex align-items-center p-4 mb-3 btn-view-pdf">
                        <i class="ki-outline ki-document fs-2x me-4"></i> 
                        <div class="text-start">
                            <span class="fw-bold d-block fs-6">' . $namaTampil . '</span>
                            <span class="text-muted fs-8">Klik untuk melihat / preview berkas PDF</span>
                        </div>
                    </a>';
                    }
                }
                $html .= '</div>';
            }

            return $this->response
                ->setHeader('X-CSRF-TOKEN', csrf_hash())
                ->setBody($html);
        }
    }

    public function getChatMateri()
    {
        if ($this->request->isAJAX()) {
            $kode_materi = $this->request->getVar('kode_materi');
            $last_id = $this->request->getVar('last_id') ?? 0;
            
            // Ambil pesan hanya yang melebihi ID terakhir yang ditampilkan di layar
            $chat_materi = $this->chatMateriModel->where('materi', $kode_materi)
                                                 ->where('id_chat_materi >', $last_id)
                                                 ->orderBy('date_created', 'ASC')
                                                 ->findAll();
                                                 
            // Ambil partisipan untuk fitur Mention (@)
            $participantsData = $this->chatMateriModel->select('nama')
                                                      ->where('materi', $kode_materi)
                                                      ->groupBy('nama')
                                                      ->findAll();
            $participants = [];
            foreach ($participantsData as $p) {
                $participants[] = is_object($p) ? $p->nama : $p['nama'];
            }

            return $this->response->setJSON([
                'messages' => $chat_materi,
                'participants' => $participants,
                'token' => csrf_hash()
            ]);
        }
    }

    public function chatMateri()
    {
        if ($this->request->isAJAX()) {
            try {
                $kode_materi = $this->request->getPost('kode_materi');
                $chat_text   = (string) $this->request->getPost('chat_materi');
                $link        = $this->request->getPost('link');
                $linkadmin   = $this->request->getPost('linkadmin');

                $user = $this->siswaModel->asObject()->find(session('id'));
                $dataMateri = $this->materiModel->where('kode_materi', $kode_materi)->first();

                if ($dataMateri) {
                    send_notif(
                        $dataMateri['guru'],
                        'Pesan baru: ' . session()->get('nama'),
                        mb_strimwidth($chat_text, 0, 40, "..."),
                        base_url('sw-guru/materi/lihat-materi/' . $link)
                    );
                    send_notif(
                        '1',
                        'Pesan baru: ' . session()->get('nama'),
                        mb_strimwidth($dataMateri['nama_materi'], 0, 40, "..."),
                        base_url('sw-admin/diskusi')
                    );
                }

                $data = [
                    'materi'       => $kode_materi,
                    'nama'         => session()->get('nama'),
                    'gambar'       => $user->avatar ?? 'default.png',
                    'email'        => session()->get('email'),
                    'text'         => htmlspecialchars($chat_text),
                    'date_created' => time()
                ];

                if ($this->chatMateriModel->save($data)) {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'token'  => csrf_hash()
                    ]);
                }
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'token' => csrf_hash()
                ])->setStatusCode(500);
            }
        }
    }

    // Fungsi Baru Edit Pesan (Untuk Siswa)
    public function updateChatMateri()
    {
        if ($this->request->isAJAX()) {
            try {
                $id_chat = $this->request->getPost('id_chat');
                $text    = (string) $this->request->getPost('text');
                $email   = session()->get('email');

                $pesan = $this->chatMateriModel->where('id_chat_materi', $id_chat)->first();
                $pesanEmail = is_object($pesan) ? $pesan->email : $pesan['email'];

                if ($pesan && $pesanEmail === $email) {
                    $this->chatMateriModel->update($id_chat, [
                        'text' => htmlspecialchars($text)
                    ]);
                    
                    return $this->response->setJSON([
                        'status' => 'success', 
                        'token'  => csrf_hash()
                    ]);
                }

                return $this->response->setJSON(['status' => 'error', 'message' => 'Akses ditolak', 'token' => csrf_hash()])->setStatusCode(403);
            } catch (\Exception $e) {
                return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'token' => csrf_hash()])->setStatusCode(500);
            }
        }
    }

    // Fungsi Baru Hapus Pesan (Untuk Siswa)
    public function deleteChatMateri()
    {
        if ($this->request->isAJAX()) {
            try {
                $id_chat = $this->request->getPost('id_chat');
                $email   = session()->get('email');

                $pesan = $this->chatMateriModel->where('id_chat_materi', $id_chat)->first();
                $pesanEmail = is_object($pesan) ? $pesan->email : $pesan['email'];
                $pesanDate = is_object($pesan) ? $pesan->date_created : $pesan['date_created'];

                if ($pesan && $pesanEmail === $email) {
                    
                    // Batas waktu edit/hapus maksimal 5 menit (300 detik)
                    $waktuSekarang = time();
                    $selisihWaktu = $waktuSekarang - $pesanDate;

                    if ($selisihWaktu > 300) {
                        return $this->response->setJSON([
                            'status' => 'error', 
                            'message' => 'Pesan sudah melewati batas 5 menit dan tidak bisa dihapus.', 
                            'token' => csrf_hash()
                        ])->setStatusCode(400);
                    }

                    $this->chatMateriModel->where('id_chat_materi', $id_chat)->delete();
                    
                    return $this->response->setJSON([
                        'status' => 'success', 
                        'token'  => csrf_hash()
                    ]);
                }

                return $this->response->setJSON(['status' => 'error', 'message' => 'Akses ditolak', 'token' => csrf_hash()])->setStatusCode(403);
            } catch (\Exception $e) {
                return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'token' => csrf_hash()])->setStatusCode(500);
            }
        }
    }
}

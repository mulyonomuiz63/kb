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
                $data['modul'][] = $m;
            }
        }

        return view('siswa/materi/list', $data);
    }



    public function lihatMateri($kode, $idmapel, $idkelas)
    {
        // 2. Gunakan Try-Catch untuk menangani kegagalan Decrypt atau Database
        try {
            // Dekripsi ID di awal untuk keamanan
            $dec_kode    = decrypt_url($kode);
            $dec_idmapel = decrypt_url($idmapel);
            $dec_idkelas = decrypt_url($idkelas);

            // 4. Pengambilan Data (Gunakan variabel terdekripsi)
            $data = [
                'breadcrumbs' => [
                    ['title' => 'Materi', 'url' => base_url('sw-siswa/materi')],
                    ['title' => 'List Video Materi', 'url' => '#'], // '#' untuk halaman aktif
                ],
                'materiAll' => $this->materiModel->getAllByMapelKelas($dec_idmapel, $dec_idkelas),
                'materi'    => $this->materiModel->getBykodeMateri($dec_kode),
                'file'      => $this->fileModel->getAllByKode($dec_kode),
            ];

            // 5. Logika Hapus Notifikasi/Materi Siswa (Disederhanakan)
            // Gunakan join dan where yang lebih efisien
            $materi_siswa = $this->materiSiswaModel
                ->join('siswa', 'materi_siswa.siswa=siswa.id_siswa')
                ->where('materi_siswa.materi', decrypt_url($kode))
                ->where('siswa.email', session()->get('email'))
                ->get()->getRowObject();

            if ($materi_siswa) {
                $this->materiSiswaModel->where('id_materi_siswa', $materi_siswa->id_materi_siswa)->delete();
            }

            // 6. Cek Data Materi & Generate Link
            $cekDataMateri = $this->materiModel->where('kode_materi', $dec_kode)->first();
            $data['link'] = '';
            if ($cekDataMateri) {
                $data['link'] = base_url('sw-guru/lihat-materi/' . encrypt_url($cekDataMateri['id_materi']) . '/' . $idmapel . '/' . $idkelas);
            }

            return view('siswa/materi/lihat-materi', $data);
        } catch (\Exception $e) {

            // Tampilkan halaman error 404 jika ada serangan URL atau data tidak ditemukan
            return redirect()->to('sw-siswa')->with('pesan', 'Materi tidak ditemukan atau akses tidak sah.');
        }
    }
    public function getFileMateri()
    {
        if ($this->request->isAJAX()) {
            $kode_materi = $this->request->getPost('kode_materi');

            // Sesuaikan query-nya dengan model Anda (apakah butuh decrypt atau tidak)
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
                    <a href="' . $urlFile . '" download
                        class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary d-flex align-items-center p-4 mb-3">
                        <i class="ki-outline ki-save-2 fs-2x me-4"></i>
                        <div class="text-start">
                            <span class="fw-bold d-block fs-6">' . $namaTampil . '</span>
                            <span class="text-muted fs-8">Klik untuk mengunduh berkas PDF/Doc</span>
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
            $chat_materi = $this->chatMateriModel->getAllByKodeMateri($kode_materi);
            $myEmail     = session()->get('email');

            $html = '';
            foreach ($chat_materi as $chat) {
                $isMe = ($chat->email == $myEmail);
                $time = date('H:i', $chat->date_created);
                $avatarUrl = base_url('assets/app-assets/user/' . ($chat->gambar ?: 'default.png'));

                if ($isMe) {
                    $html .= '
                <div class="d-flex justify-content-end mb-10">
                    <div class="d-flex flex-column align-items-end">
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-3">
                                <span class="text-muted fs-9 mb-1">' . $time . '</span>
                                <span class="fs-7 fw-bold text-gray-900 text-hover-primary ms-1">Anda</span>
                            </div>
                            <div class="symbol symbol-35px symbol-circle">
                                <img alt="Pic" src="' . $avatarUrl . '" />
                            </div>
                        </div>
                        <div class="p-5 rounded bg-light-primary text-dark fw-semibold mw-lg-400px text-end">
                            ' . nl2br(esc($chat->text)) . '
                        </div>
                    </div>
                </div>';
                } else {
                    $html .= '
                <div class="d-flex justify-content-start mb-10">
                    <div class="d-flex flex-column align-items-start">
                        <div class="d-flex align-items-center mb-2">
                            <div class="symbol symbol-35px symbol-circle">
                                <img alt="Pic" src="' . $avatarUrl . '" />
                            </div>
                            <div class="ms-3">
                                <span class="fs-7 fw-bold text-gray-900 text-hover-primary me-1">' . $chat->nama . '</span>
                                <span class="text-muted fs-9 mb-1">' . $time . '</span>
                            </div>
                        </div>
                        <div class="p-5 rounded bg-light-info text-dark fw-semibold mw-lg-400px text-start">
                            ' . nl2br(esc($chat->text)) . '
                        </div>
                    </div>
                </div>';
                }
            }

            if (empty($chat_materi)) {
                $html = '<div class="text-center text-muted py-10">Belum ada diskusi. Mulai bertanya yuk!</div>';
            }

            // PERBAIKAN: Kirim HTML beserta Token CSRF terbaru
            return $this->response->setJSON([
                'html' => $html,
                'token' => csrf_hash() // Kirim hash baru setiap kali load chat
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

                $user = $this->siswaModel->asObject()->find(session('id'));
                $dataMateri = $this->materiModel->where('kode_materi', $kode_materi)->first();

                if ($dataMateri) {
                    send_notif(
                        $dataMateri['guru'],
                        'Pesan baru: ' . session()->get('nama'),
                        mb_strimwidth($chat_text, 0, 40, "..."),
                        $link
                    );
                    send_notif(
                        '1',
                        'Pesan baru: ' . session()->get('nama'),
                        mb_strimwidth($dataMateri['nama_materi'], 0, 40, "..."),
                        $link
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
                    // PERBAIKAN: Kirim status success beserta token CSRF terbaru
                    return $this->response->setJSON([
                        'status' => 'success',
                        'token'  => csrf_hash() // Hash baru setelah save
                    ]);
                }
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'token' => csrf_hash() // Tetap kirim hash baru meski error
                ], 500);
            }
        }
    }
}

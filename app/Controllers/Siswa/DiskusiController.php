<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class DiskusiController extends BaseController
{
    protected $chatMateriModel;
    protected $materiModel;
    protected $siswaModel;

    public function __construct()
    {
        $this->chatMateriModel = new \App\Models\ChatMateriModel();
        $this->materiModel = new \App\Models\MateriModel();
        $this->siswaModel = new \App\Models\SiswaModel();
    }

    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Diskusi', 'url' => '#'],
        ];

        // Di ChatController.php (Metode index atau yang memuat sidebar)
        $myEmail = session()->get('email');


        $data['diskusi'] = $this->chatMateriModel
            ->select('
        chat_materi.materi, 
        materi.nama_materi, 
        COUNT(CASE WHEN chat_materi.status_notif = "0" AND chat_materi.email != "' . $myEmail . '" THEN 1 END) as unread_count
    ')
            ->join('materi', 'chat_materi.materi = materi.kode_materi')
            // Jangan filter email di sini secara kaku jika ingin menghitung pesan orang lain
            // Kita filter agar hanya menampilkan materi yang memang pernah Anda ajak diskusi
            ->whereIn('chat_materi.materi', function ($builder) use ($myEmail) {
                return $builder->select('materi')->from('chat_materi')->where('email', $myEmail);
            })
            ->groupBy('chat_materi.materi')
            ->get()
            ->getResultArray();

        $data['materi'] = $this->materiModel->join('materi_siswa', 'materi_siswa.materi = materi.kode_materi')->where('materi_siswa.siswa', session('id'))->groupBy('kode_materi')->get()->getResultArray();

        return view('siswa/diskusi/list', $data);
    }

    public function getMessages($materiName)
    {
        $materiName = urldecode($materiName);
        $lastId = $this->request->getGet('last_id') ?? 0;
        $myEmail = session()->get('email');

        // 1. Ambil pesan baru
        $messages = $this->chatMateriModel->where('materi', $materiName)
            ->where('id_chat_materi >', $lastId)
            ->orderBy('date_created', 'ASC')
            ->findAll();

        // 2. Cari pesan pertama belum terbaca
        $firstUnread = $this->chatMateriModel->where('materi', $materiName)
            ->where('status_notif', '0')
            ->where('email !=', $myEmail)
            ->orderBy('id_chat_materi', 'ASC')
            ->first();

        // 3. Update status (Gunakan Try-Catch)
        try {
            $db = \Config\Database::connect();
            $builder = $db->table('chat_materi');
            $builder->where('materi', $materiName);
            $builder->where('status_notif', '0');
            $builder->where('email !=', $myEmail);

            // Periksa apakah ada baris yang memenuhi kriteria
            if ($builder->countAllResults(false) > 0) {
                $builder->update(['status_notif' => '1']);
            }
        } catch (\Exception $e) {
            // Abaikan error "no data to update" agar response tetap kembali ke JS
        }

        return $this->response->setJSON([
            'messages'        => $messages,
            'first_unread_id' => $firstUnread ? $firstUnread['id_chat_materi'] : null
        ]);
    }

    public function sendMessage()
    {

        if ($this->request->isAJAX()) {
            try {
                $kode_materi = $this->request->getPost('materi');
                $chat_text   = (string) $this->request->getPost('text');

                $user = $this->siswaModel->asObject()->find(session('id'));
                $dataMateri = $this->materiModel->where('kode_materi', $kode_materi)->first();

                $link = '';
                $linkadmin = '';
                if ($dataMateri) {
                    $link =  encrypt_url($dataMateri['id_materi']) . '/' . encrypt_url($dataMateri['mapel']) . '/' . encrypt_url($dataMateri['kelas']);
                    $linkadmin =  encrypt_url($dataMateri['mapel']) . '/' . encrypt_url($dataMateri['kelas']) . '/' . encrypt_url($dataMateri['guru']);
                }



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

    public function updateMessage()
    {
        if ($this->request->isAJAX()) {
            try {
                $id = $this->request->getPost('id_chat');
                $text = (string) $this->request->getPost('text');
                $email = session()->get('email');

                $msg = $this->chatMateriModel->find($id);

                // Validasi: Milik user sendiri & belum lewat 5 menit (300 detik)
                if ($msg && $msg['email'] == $email && (time() - $msg['date_created']) <= 300) {
                    $this->chatMateriModel->update($id, ['text' => htmlspecialchars($text)]);
                    return $this->response->setJSON([
                        'status' => 'success',
                        'token'  => csrf_hash()
                    ]);
                }

                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Pesan tidak dapat diedit (waktu 5 menit telah habis atau bukan pesan Anda).',
                    'token'  => csrf_hash()
                ])->setStatusCode(400);

            } catch (\Exception $e) {
                return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'token' => csrf_hash()], 500);
            }
        }
    }

    public function deleteMessage()
    {
        if ($this->request->isAJAX()) {
            try {
                $id = $this->request->getPost('id_chat');
                $email = session()->get('email');

                $msg = $this->chatMateriModel->find($id);

                // Validasi: Milik user sendiri & belum lewat 5 menit (300 detik)
                if ($msg && $msg['email'] == $email && (time() - $msg['date_created']) <= 300) {
                    $this->chatMateriModel->delete($id);
                    return $this->response->setJSON([
                        'status' => 'success',
                        'token'  => csrf_hash()
                    ]);
                }

                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Pesan tidak dapat dihapus (waktu 5 menit telah habis).',
                    'token'  => csrf_hash()
                ])->setStatusCode(400);

            } catch (\Exception $e) {
                return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'token' => csrf_hash()], 500);
            }
        }
    }
}

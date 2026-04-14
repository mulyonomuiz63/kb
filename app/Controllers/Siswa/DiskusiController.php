<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class DiskusiController extends BaseController
{
    protected $chatMateriModel;
    protected $materiModel;

    public function __construct()
    {
        $this->chatMateriModel = new \App\Models\ChatMateriModel();
        $this->materiModel = new \App\Models\MateriModel();

    }

    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Diskusi', 'url' => '#'],
        ];

        $data['diskusi'] = $this->chatMateriModel->join('materi', 'chat_materi.materi = materi.kode_materi')->where('email', session()->get('email'))->get()->getResultArray(); // Ganti 'kode_materi' dengan parameter yang sesuai
        $data['materi'] = $this->materiModel->groupBy('kode_materi')->get()->getResultArray();

        return view('siswa/diskusi/list', $data);
    }

    public function create()
    {
        // Validasi input
        $tutorId = $this->request->getPost('tutor_id');
        $subject = $this->request->getPost('subject');

        if (!$tutorId || !$subject) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap']);
        }

        // Contoh Logika Simpan Database (Sesuaikan dengan Model Anda)
        $data = ['tutor_id' => $tutorId, 'subject' => $subject, 'user_id' => session()->get('id')];
        $chatModel->insert($data);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Diskusi berhasil dibuat!',
            'redirect' => base_url('sw-siswa/diskusi') // Atau arahkan ke ID chat spesifik
        ]);
    }
}

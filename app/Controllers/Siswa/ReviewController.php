<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class ReviewController extends BaseController
{
    protected $reviewUjianModel;

    public function __construct()
    {
        $this->reviewUjianModel = new \App\Models\ReviewUjianModel();
    }

    public function simpanReview()
    {
        $reviewModel = $this->reviewUjianModel;
    
        $kode_ujian = $this->request->getPost('kode_ujian');
        $idSiswa = session()->get('id');
        $rating = $this->request->getPost('rating');
        $komentar = $this->request->getPost('komentar');
        $link = $this->request->getPost('link');
    
        // Cek apakah siswa sudah pernah memberikan review untuk ujian ini
        $cek = $reviewModel->where(['kode_ujian' => $kode_ujian, 'id_siswa' => $idSiswa])->first();
        if ($cek) {
            // Update review lama
            $reviewModel->update($cek['id_review'], [
                'rating' => $rating,
                'komentar' => $komentar,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $pesan = 'Penilaian Anda telah diperbarui.';
        } else {
            // Simpan review baru
            $reviewModel->insert([
                'kode_ujian' => $kode_ujian,
                'id_siswa' => $idSiswa,
                'rating' => $rating,
                'komentar' => $komentar,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $pesan = 'Terima kasih, penilaian Anda telah disimpan.';
        }
        return redirect()->to($link)->with('success', $pesan);
    
    }
}

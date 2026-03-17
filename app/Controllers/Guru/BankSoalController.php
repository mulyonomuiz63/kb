<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class BankSoalController extends BaseController
{
    protected $bankSoalModel;
    protected $kategoriModel;
    protected $guruModel;
    protected $tagModel;

    public function __construct()
    {
        $this->bankSoalModel = new \App\models\BankSoalModel();
        $this->kategoriModel = new \App\models\KategoriModel();
        $this->guruModel = new \App\models\GuruModel();
        $this->tagModel = new \App\models\TagModel();

    }
    public function index()
    {
        $data['soal'] = $this->bankSoalModel->getAll();
        return view('guru/bank_soal/list', $data);
    }
    public function create()
    {
        $data['kategori'] = $this->kategoriModel->getAll();
        return view('guru/bank_soal/tambah_pg', $data);
    }
    
    public function store()
    {
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

        $this->bankSoalModel->insertBatch($data_detail_ujian);

        return redirect()->to('sw-guru/bank-soal')->with('success', 'Soal berhasil dibuat.');
    }

    public function edit($id_bank_soal)
    {
        $data['soal'] = $this->bankSoalModel->getById(decrypt_url($id_bank_soal));
        $data['kategori'] = $this->kategoriModel->getAll();

        return view('guru/bank_soal/edit_pg', $data);
    }

    public function update()
    {
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


        $this->bankSoalModel->set($data_detail_ujian)->where('id_bank_soal', $this->request->getVar('id_bank_soal'))->update();
        return redirect()->to('sw-guru/bank-soal')->with('success', 'Soal berhasil diubah');
    }

    public function delete($id_bank_soal)
    {
        $this->tagModel
            ->where('id_bank_soal', decrypt_url($id_bank_soal))
            ->delete();

        $this->bankSoalModel
            ->where('id_bank_soal', decrypt_url($id_bank_soal))
            ->delete();

        return redirect()->to('sw-guru/bank-soal')->with('success', 'Soal berhasil dihapus');
    }


    // START::SUMMERNOTE
    public function uploadSummernote()
    {
        $fileGambar = $this->request->getFile('image');
        // Generate nama file Random
        $nama_gambar = $fileGambar->getRandomName();
        // Upload Gambar
        $fileGambar->move('assets/app-assets/file', $nama_gambar);

        echo base_url() . '/assets/app-assets/file/' . $nama_gambar;
    }

    function deleteImage()
    {
        $src = $this->request->getVar('src');
        $file_name = str_replace(base_url() . '/', '', $src);
        if (unlink($file_name)) {
            echo 'File Delete Successfully';
        }
    }
    // END::SUMMERNOTE
}

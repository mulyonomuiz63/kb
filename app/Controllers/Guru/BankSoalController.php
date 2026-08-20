<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\BankSoalModel;
use App\Models\GuruModel;
use App\Models\KategoriModel;
use App\Models\TagModel;

class BankSoalController extends BaseController
{
    protected $bankSoalModel;
    protected $kategoriModel;
    protected $guruModel;
    protected $tagModel;

    public function __construct()
    {
        $this->bankSoalModel = new BankSoalModel();
        $this->kategoriModel = new KategoriModel();
        $this->guruModel = new GuruModel();
        $this->tagModel = new TagModel();
    }
    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-guru')],
            ['title' => 'List Soal', 'url' => '#'],
        ];

        $db = \Config\Database::connect();

        $data['soal'] = $this->bankSoalModel->getAll();

        // Tambahan data untuk dropdown filter
        $data['kategori'] = $db->table('kategori')->get()->getResultObject();
        $data['sub_materi_list'] = $db->table('bank_soal')
            ->select('sub_materi')
            ->distinct()
            ->where('sub_materi IS NOT NULL')
            ->where('sub_materi !=', '')
            ->get()
            ->getResultObject();

        return view('guru/bank_soal/list', $data);
    }
    public function create()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-guru')],
            ['title' => 'Bank Soal', 'url' => base_url('sw-guru/bank-soal')],
            ['title' => 'Tambah Soal', 'url' => '#'],
        ];
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
            $val_pg1 = $this->request->getVar('pg_1')[$index];
            $val_pg2 = $this->request->getVar('pg_2')[$index];
            $val_pg3 = $this->request->getVar('pg_3')[$index];
            $val_pg4 = $this->request->getVar('pg_4')[$index];
            $val_pg5 = $this->request->getVar('pg_5')[$index];

            array_push($data_detail_ujian, array(
                'id_kategori' => $this->request->getVar('id_kategori')[$index],
                'sub_materi'  => $this->request->getVar('sub_materi')[$index],
                'nama_soal'   => $nama,
                'pg_1'        => !empty(trim($val_pg1)) ? 'A. ' . $val_pg1 : '',
                'pg_2'        => !empty(trim($val_pg2)) ? 'B. ' . $val_pg2 : '',
                'pg_3'        => !empty(trim($val_pg3)) ? 'C. ' . $val_pg3 : '',
                'pg_4'        => !empty(trim($val_pg4)) ? 'D. ' . $val_pg4 : '',
                'pg_5'        => !empty(trim($val_pg5)) ? 'E. ' . $val_pg5 : '',
                'jawaban'     => $this->request->getVar('jawaban')[$index],
                'penjelasan'  => $this->request->getVar('penjelasan')[$index],
                'jenis_soal'  => $this->request->getVar('jenis_soal')[$index],
            ));

            $index++;
        }
        // END DATA DETAIL UJIAN PG

        $this->bankSoalModel->insertBatch($data_detail_ujian);

        return redirect()->to('sw-guru/bank-soal')->with('success', 'Soal berhasil dibuat.');
    }

    public function edit($id_bank_soal)
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-guru')],
            ['title' => 'Bank Soal', 'url' => base_url('sw-guru/bank-soal')],
            ['title' => 'Edit Soal', 'url' => '#'],
        ];
        $data['soal'] = $this->bankSoalModel->getById(decrypt_url($id_bank_soal));
        $data['kategori'] = $this->kategoriModel->getAll();

        return view('guru/bank_soal/edit_pg', $data);
    }

    public function update()
    {
        $val_pg1 = $this->request->getVar('pg_1');
        $val_pg2 = $this->request->getVar('pg_2');
        $val_pg3 = $this->request->getVar('pg_3');
        $val_pg4 = $this->request->getVar('pg_4');
        $val_pg5 = $this->request->getVar('pg_5');
        $data_detail_ujian = [
            'id_kategori' => $this->request->getVar('id_kategori'),
            'sub_materi' => $this->request->getVar('sub_materi'),
            'nama_soal' => $this->request->getVar('nama_soal'),
            'pg_1'        => !empty(trim($val_pg1)) ? 'A. ' . $val_pg1 : '',
            'pg_2'        => !empty(trim($val_pg2)) ? 'B. ' . $val_pg2 : '',
            'pg_3'        => !empty(trim($val_pg3)) ? 'C. ' . $val_pg3 : '',
            'pg_4'        => !empty(trim($val_pg4)) ? 'D. ' . $val_pg4 : '',
            'pg_5'        => !empty(trim($val_pg5)) ? 'E. ' . $val_pg5 : '',
            'jawaban' => $this->request->getVar('jawaban'),
            'penjelasan' => $this->request->getVar('penjelasan'),
            'jenis_soal' => $this->request->getVar('jenis_soal'),
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

<?php
namespace App\Controllers;

use App\Models\QuizModel;
use App\Models\HasilModel;
use App\Libraries\SeoHelper;

class Quiz extends BaseController
{
    protected  $seo;
    public function __construct(){
        $this->seo = new SeoHelper();
    }
    
    public function index()
    {
        $uri = new \CodeIgniter\HTTP\URI($this->request->getUri());

         $data = [
            'url' => $uri->getPath(),
        ];
        session()->set($data);
        
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        
        
        $tema = $this->db->table('quiztem')->where('status', 'A')->get()->getRowObject();
        $hasil = $this->db->table('hasil')->where('idsiswa', session()->get('id'))->where('idquiztem', $tema->idquiztem)->get()->getRowObject();
        if(empty($hasil)){
            if(!empty($tema)){
                $quizModel = new QuizModel();
                $data['quiz'] = $quizModel->findAll();
                $data['quiztem'] = $this->db->table('quiztem')->where('status', 'A')->get()->getRowObject();
            	//untuk breadcrumb 
                $breadcrumbItems = [
                    "Home" => base_url(),
                ];
                $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
                $schema = $schemaBreadcrumb;
                $data['schema'] = $schema;
                return view('landing/quiz', $data);
            }else{
                session()->setFlashdata('pesan', "
                                swal({
                                    title: 'Informasi',
                                    text: 'Quiz tidak tersedia.',
                                    type: 'info',
                                    padding: '2em'
                                }); 
                            "); 
                return redirect()->to('/');
            }
        }else{
            $nilai = intval($hasil->total) + intval($hasil->bonus);
            session()->setFlashdata('pesan', "
                                swal({
                                    title: 'Informasi',
                                    text: 'Anda sudah pernah mengerjakan quiz tersebut dengan Skor $nilai',
                                    type: 'info', 
                                }); 
                            "); 
                return redirect()->to('/');
        }    
    }
    public function getQuiz($jumlah = 'all')
    {
        $quizModel = new QuizModel();
        $builder = $quizModel->orderBy('RAND()');

        if ($jumlah !== 'all') {
            $builder = $builder->limit((int) $jumlah);
        }

        $soal = $builder->findAll();

        // Decode opsi JSON
        $soal = array_map(function($row) {
            $row['opsi'] = json_decode($row['opsi']);
            return $row;
        }, $soal);

        return $this->response->setJSON($soal);
    }

    public function saveLeaderboard()
    {
        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->response->setStatusCode(400)
                                  ->setJSON(['error' => 'Data kosong']);
        } 
        $hasilModel = new HasilModel();

        $hasilModel->insert([
            'idsiswa'    => $data['idsiswa'],
            'idquiztem'  => $data['idquiztem'],
            'skor_dasar' => $data['base'],
            'bonus'      => $data['bonus'],
            'total'      => $data['total'],
            'jawaban'    => json_encode($data['answers']),
        ]);

        $this->response->setHeader(csrf_token(), csrf_hash());

        return $this->response->setJSON(['status' => 'ok']);
    }

    public function getLeaderboard()
    {
        $hasilModel = new HasilModel();
        $data = $hasilModel->join('siswa', 'hasil.idsiswa=siswa.id_siswa')->orderBy('total', 'DESC')->findAll(20);

        foreach ($data as &$d) {
            $d['jawaban'] = json_decode($d['jawaban']);
        }

        return $this->response->setJSON($data);
    }

    public function resetLeaderboard()
    {
        $hasilModel = new HasilModel();
        $hasilModel->truncate();

        return $this->response->setJSON(['status' => 'reset']);
    }
}

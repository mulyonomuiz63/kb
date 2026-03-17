<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;




class HomeController extends BaseController
{
    
    protected $kelasModel;
    protected $guruModel;
    protected $guruMapelModel;
    protected $guruKelasModel;
    protected $mapelModel;

    public function __construct()
    {
        $this->mapelModel = new \App\Models\MapelModel();
        $this->kelasModel = new \App\Models\KelasModel();
        $this->guruModel = new \App\Models\GuruModel();
        $this->guruMapelModel = new \App\Models\GuruMapelModel();
        $this->guruKelasModel = new \App\Models\GuruKelasModel();

    }

    public function index()
    {
        $data['mapel'] = $this->mapelModel->asObject()->findAll();
        $data['kelas'] = $this->kelasModel->asObject()->findAll();

        $data['guru_kelas'] = $this->guruKelasModel->getALLByGuru(session()->get('id'));
        $data['guru_mapel'] = $this->guruMapelModel->getALLByGuru(session()->get('id'));
        $data['guru'] = $this->guruModel->asObject()->find(session()->get('id'));

        // dd($data['guru_kelas']);

        return view('guru/dashboard', $data);
    }


}

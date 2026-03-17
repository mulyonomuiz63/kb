<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class HomeController extends BaseController
{
    protected $paketModel;
    protected $affiliateModel;
    public function __construct()
    {
        $this->paketModel = new \App\Models\PaketModel();
        $this->affiliateModel = new \App\Models\AffiliateModel();
    }
    public function index()
    {
        $this->data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-siswa')],
        ];

        $this->data['show_banner'] = true;

        $this->data['affiliate'] =  $this->affiliateModel->where('user_id', session()->get('id'))->first();
        
        $this->data['paket'] = $this->paketModel->getAll();
        // Pastikan DB instance tersedia di view jika Anda melakukan query di dalam loop
        $this->data['db'] = \Config\Database::connect();

        return view('siswa/dashboard', $this->data);
    }
}

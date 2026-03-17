<?php

namespace App\Controllers;

use App\Models\PaketModel;
use App\Models\AffiliateModel;
use App\Models\AffiliateLinkModel;
use App\Libraries\SeoHelper;
use Config\Database;

class Bimbel extends BaseController
{

    protected  $PaketModel;
    protected  $affiliateModel;
    protected  $affiliateLinkModel;
    protected  $seo;

    public function __construct()
    {
        $this->PaketModel = new PaketModel();
        $this->affiliateModel = new AffiliateModel();
        $this->affiliateLinkModel = new AffiliateLinkModel();
        $this->seo = new SeoHelper();
    }

    public function index()
    {
         $data['paket'] = $this->PaketModel->getAll();
        $data['lihat'] = '1';
        //untuk breadcrumb 
        $breadcrumbItems = [
            "Home" => base_url(),
            "Bimbel" => current_url()
        ];
        
            $paket=$data['paket'];
        if(!empty($data['paket'])){
            $paket = $data['paket'][0]->nama_paket;
        }
        $title = "{$paket} - Paket Pelatihan Terbaik di kelasbrevet";
        $desc = "Temukan paket {$paket} terbaik dengan harga terjangkau di kelasbrevet.";
        $schemaItemList  = $this->seo->itemListSchema($data['paket'], $title, $desc, current_url());
        $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
        
        $schema = $schemaItemList . "\n" . $schemaBreadcrumb;
        $data['schema'] = $schema;
        $data['db'] =  Database::connect();
        
        
        $data['affiliate'] =  $this->affiliateModel->where('user_id', session()->get('id'))->first();
        return view('landing/pelatihan', $data);
    }
    
    public function detail($slug = null, $voucher='')
    {
        if($slug == null){
            return redirect()->to('/');
        }
        $data['paket'] = $this->PaketModel->select('paket.*, b.diskon, c.id_mapel, c.id_ujian')
            ->join('diskon b', 'b.iddiskon = paket.iddiskon')
            ->join('detail_paket c', 'c.idpaket=paket.idpaket')
            ->where('paket.slug', $slug)->get()->getRowObject();
            
            
        $data['affiliate'] =  $this->affiliateModel->where('user_id', session()->get('id'))->first();
        
        $data['lihat'] = '1';
        $data['kdvoucher'] = $voucher;
        
        if(empty($data['paket'])){
            return redirect()->to('/');
        }
        
        $query = $this->db->table('paket')->join('detail_paket b', 'paket.idpaket=b.idpaket')->join('ujian_master c', 'b.id_ujian=c.id_ujian')->join('review_ujian d', 'c.kode_ujian=d.kode_ujian')->where('paket.slug', $slug)->get()->getResultObject();
        
        // hitung rata-rata rating
        $totalRating = 0;
        $jumlahReview = count($query);
    
        foreach ($query as $item) {
            $totalRating += $item->rating;
        }
    
        $rataRating = $jumlahReview > 0 ? round($totalRating / $jumlahReview, 1) : 0;
        
        $views = $this->db->query("
              SELECT q.nama_siswa, q.avatar, d.komentar, d.rating, d.created_at
              FROM review_ujian d
              JOIN ujian_master c ON c.kode_ujian=d.kode_ujian
              JOIN detail_paket b ON b.id_ujian=c.id_ujian
              JOIN paket p ON p.idpaket=b.idpaket
              JOIN siswa q ON q.id_siswa=d.id_siswa
              WHERE p.slug = '$slug'
              ORDER BY d.created_at DESC
              LIMIT 10
            ")->getResult();
        
        $data['review'] = $views;
        $data['dataReting'] = $query;
        $data['rataRating'] = $rataRating;
        $data['totalRating'] = $jumlahReview;
        
        //untuk breadcrumb 
        $breadcrumbItems = [
            "Home" => base_url(),
            "Bimbel" => base_url('bimbel'),
            $slug => current_url(),
            
        ];
        $schemaProduct = $this->seo->productSchema($data['paket'], $data['rataRating'],$data['totalRating'], $data['review']);
        $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
        $schema = $schemaProduct . "\n" . $schemaBreadcrumb;
        $data['schema'] = $schema;
        $data['db'] =  Database::connect();
        return view('landing/detail', $data);
    }
    
}

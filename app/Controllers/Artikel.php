<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use App\Models\IklanModel;
use App\Libraries\SeoHelper;
use Config\Database;

class Artikel extends BaseController
{

    protected  $PaketModel;
    protected  $IklanModel;
    protected  $seo;

    public function __construct()
    {
        $this->ArtikelModel = new ArtikelModel();
        $this->IklanModel = new IklanModel();
        $this->seo = new SeoHelper();
    }

    public function index()
    {
        $data['artikelUtamaUp'] = $this->ArtikelModel->select('artikel.*, admin.nama_admin, kategori_artikel.kategori')->join('admin','artikel.iduser=admin.id_admin')->join('kategori_artikel', 'artikel.idkategori=kategori_artikel.id')->where('status', 'utama_up')->orderBy('artikel.id', 'desc')->get()->getResultObject();
        $data['artikelUtamaDown'] = $this->ArtikelModel->select('artikel.*, admin.nama_admin, kategori_artikel.kategori')->join('admin','artikel.iduser=admin.id_admin')->join('kategori_artikel', 'artikel.idkategori=kategori_artikel.id')->where('status', 'utama_down')->orderBy('artikel.id', 'desc')->limit(4)->get()->getResultObject();
        $data['artikelRekomendasi'] = $this->ArtikelModel->select('artikel.*, admin.nama_admin, kategori_artikel.kategori')->join('admin','artikel.iduser=admin.id_admin')->join('kategori_artikel', 'artikel.idkategori=kategori_artikel.id')->where('status', 'rekomendasi')->orderBy('artikel.id', 'desc')->get()->getResultObject();
        $data['kategori'] = $this->ArtikelModel->getKategori();
        $model = $this->ArtikelModel->select('artikel.*, admin.nama_admin, kategori_artikel.kategori')->join('admin','artikel.iduser=admin.id_admin')->join('kategori_artikel', 'artikel.idkategori=kategori_artikel.id');

        $data['iklan'] = $this->IklanModel->getAll();
		$data['artikel'] = $model->orderBy('artikel.id', 'desc')->paginate(10);
    	$data['pager'] = $model->pager;
    	
    	//untuk breadcrumb 
        $breadcrumbItems = [
            "Home" => base_url(),
        ];
        $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
        $schema = $schemaBreadcrumb;
        $data['schema'] = $schema;
        $data['db'] =  Database::connect();
        return view('landing/artikel/index', $data);
    }
    
    public function detail($slug)
    {
        $data["detail"] = $this->ArtikelModel->getDetail($slug);
        // $data["kategori"] = $this->FrontModal->get_kategori();
        if(!empty( $data["detail"])){
            $data["tags"] = $this->ArtikelModel->getTag($data["detail"]->id);
            $data['artikelRekomendasi'] = $this->ArtikelModel->select('artikel.*, admin.nama_admin, kategori_artikel.kategori')->join('admin','artikel.iduser=admin.id_admin')->join('kategori_artikel', 'artikel.idkategori=kategori_artikel.id')->where('status', 'rekomendasi')->get()->getResultObject();
            $data['kategori'] = $this->ArtikelModel->getKategori();
            $datas = array(
                'hit' => $data["detail"]->hit + 1 ,
            );
            $this->db->table('artikel')
            ->where('id', $data["detail"]->id)
            ->update($datas);
            $data['iklan'] = $this->IklanModel->getAll();
            
            //untuk breadcrumb 
            $breadcrumbItems = [
                "Home" => base_url(),
                'Artikel' => base_url('artikel/'),
                $data["detail"]->slug_judul => current_url()
            ];
            $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
            $schema = $schemaBreadcrumb;
            $data['schema'] = $schema;
            $data['db'] =  Database::connect();
            return view('landing/artikel/detail', $data);
        }else{
           return redirect()->back();
        }
    }
    
    public function kategori($slug)
    {
        $model = new ArtikelModel;
        $data['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
        $data['perPage'] = 9;
        $pagination = $model->getPagination($data['perPage'], $slug);
        $data['total'] = count($model->getPaginationAll($slug));
        $data["dataPagination"] = $pagination["kategoriartikel"];
        $data["pager"] = $pagination["pager"];
        $data['kategori'] = $this->ArtikelModel->getKategori();
        $data['artikelRekomendasi'] = $this->ArtikelModel->select('artikel.*, admin.nama_admin, kategori_artikel.kategori')->join('admin','artikel.iduser=admin.id_admin')->join('kategori_artikel', 'artikel.idkategori=kategori_artikel.id')->where('status', 'rekomendasi')->get()->getResultObject();
        $data['iklan'] = $this->IklanModel->getAll();
        //untuk breadcrumb 
        $breadcrumbItems = [
            "Home" => base_url(),
            'Artikel' => base_url('artikel/'),
             $slug => current_url()
        ];
        $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
        $schema = $schemaBreadcrumb;
        $data['schema'] = $schema;
        $data['db'] =  Database::connect();
        return view('landing/artikel/kategori', $data);
    }
    public function tag($slug)
    {
        $model = new ArtikelModel;
        $data['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
        $data['perPage'] = 9;
        $pagination = $model->getPaginationTag($data['perPage'], $slug);
        $data['total'] = count($model->getPaginationAllTag($slug));
        $data["dataPagination"] = $pagination["tagArtikel"];
        $data["pager"] = $pagination["pager"];
        $data['kategori'] = $this->ArtikelModel->getKategori();
        $data['artikelRekomendasi'] = $this->ArtikelModel->select('artikel.*, admin.nama_admin, kategori_artikel.kategori')->join('admin','artikel.iduser=admin.id_admin')->join('kategori_artikel', 'artikel.idkategori=kategori_artikel.id')->where('status', 'rekomendasi')->get()->getResultObject();
        $data['iklan'] = $this->IklanModel->getAll();
        //untuk breadcrumb 
        $breadcrumbItems = [
            "Home" => base_url(),
            'Artikel' => base_url('artikel/'),
             $slug => current_url()
        ];
        $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
        $schema = $schemaBreadcrumb;
        $data['schema'] = $schema;
        $data['db'] =  Database::connect();
        return view('landing/artikel/tag', $data);
    }
}

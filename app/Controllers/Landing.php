<?php

namespace App\Controllers;

use App\Models\PaketModel;
use App\Models\JadwalModel;
use App\Models\BatchModel;
use App\Models\GaleriModel;
use App\Models\InstagramModel;
use App\Models\TwibbonModel;
use App\Models\AffiliateModel;
use App\Libraries\SeoHelper;
use App\Models\SmtpModel;
use Config\Database;

class Landing extends BaseController
{

    protected  $PaketModel;
    protected  $BatchModel;
    protected  $JadwalModel;
    protected  $GaleriModel;
    protected  $InstagramModel;
    protected  $TwibbonModel;
    protected  $affiliateModel;
    protected  $seo;
    protected  $SmtpModel;
    protected  $email;
    

    public function __construct()
    {
        $this->PaketModel = new PaketModel();
        $this->JadwalModel = new JadwalModel();
        $this->BatchModel = new BatchModel();
        $this->GaleriModel = new GaleriModel();
        $this->InstagramModel = new InstagramModel();
        $this->TwibbonModel = new TwibbonModel();
        $this->seo = new SeoHelper();
        
        $this->affiliateModel = new AffiliateModel();
        $this->email = \Config\Services::email();
        $this->SmtpModel = new SmtpModel();
    }

    public function index()
    {
        //data untuk perusahaan
        $business = [
            'name' => 'PT Legalyn Konsultan Indonesia',
            'image' => base_url('uploads/iklan/thumbnails/1754033061_8e1c45adcf28141d1bd0.jpg'),
            'url' => base_url(),
            'telephone' => '+62 821-8074-4966',
            'description' => 'Kelas Brevet merupakan platform pelatihan Brevet Pajak AB yang diselenggarakan oleh Akuntanmu Learning Center By Legalyn Konsultan Indonesia (Lembaga Pelatihan, Kursus/Bimbel, yang didirikan sejak tahun 2021 dan telah resmi terdaftar di Indonesia). Sebagai upaya merespon kebutuhan peningkatan kompetensi profesi perpajakan di Indonesia, Akuntanmu Learning Center menghadirkan Ujian Brevet Pajak AB secara online melalui KelasBrevet.com',
            'address' => [
                'streetAddress' => 'Jl. Sawo Raya Gang Sawah Ruslan, Fajar Baru, Kec. Jati Agung, Kabupaten Lampung Selatan, Lampung 35365',
                'addressLocality' => 'Lampung',
                'addressRegion' => 'Lampung Selatan',
                'postalCode' => '35365',
                'addressCountry' => 'ID'
            ],
            'geo' => [
                'latitude' => '-5.334449529046349',
                'longitude' => '105.27212661836225'
            ],
            'openingHours' => 'Mo-Su 09:00-17:00'
        ];
        //end data untuk perusahaan

        //untuk mendeteksi IP user yang mengakses
        $request = \Config\Services::request();
        $userAgent = $this->request->getUserAgent();
        $ipAddress = $request->getIPAddress();
        $browserName = $userAgent->getBrowser();

        $data = [
            'browserName' => $browserName,
            'ipAddress' => $ipAddress,
        ];
        
        $cekdata = $this->db->query("SELECT ipAddress FROM `statistik_hits` WHERE ipAddress ='$ipAddress' and date(created_at) = date(now())")->getRowObject();
        
        if(empty($cekdata)){
            $this->db->table('statistik_hits')
                ->insert($data);
        }
        //end mendeteksi ip user
        
        
        $query = $this->db->table('review_ujian')->get()->getResultObject();
        
        // hitung rata-rata rating
        $totalRating = 0;
        $jumlahReview = count($query);
        foreach ($query as $item) {
            $totalRating += $item->rating;
        }
        $rataRating = $jumlahReview > 0 ? round($totalRating / $jumlahReview, 1) : 0;
        
        $data['rataRating'] = $rataRating;
        $data['totalRating'] = $jumlahReview;
        
        //untuk breadcrumb 
        $breadcrumbItems = [
            "Home" => base_url(),
        ];
        $metaLocalSeo = $this->seo->meta_local_seo($business);
        $localBusinessSchema = $this->seo->local_business_schema($business, $data['rataRating'],$data['totalRating']);
        $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
        $homeProduct = $this->seo->homeSchema($business, $data['rataRating'],$data['totalRating']);
        $schema = $metaLocalSeo ."\n". $homeProduct . "\n" . $schemaBreadcrumb ."\n". $localBusinessSchema;
        $data['schema'] = $schema;
        
        $data['affiliate'] =  $this->affiliateModel->where('user_id', session()->get('id'))->first();
        
        $data['schema'] = $schema;
        $data['paket'] = $this->PaketModel->getAllLimit();
        $data['lihat'] = '0';
        $data['dataIklan'] =  $this->db->table('iklan')->where('status', 'I')->where('status_iklan', 'modal')->orderBy("id", 'desc')->get()->getResultObject();
        $data['dataMeta'] =  $this->db->table('iklan')->where('status', 'I')->where('status_iklan', 'modal')->orderBy("id", 'desc')->get()->getRowObject();
        $data['dataIklanDepan'] =  $this->db->table('iklan')->where('status', 'I')->where('status_iklan', 'depan')->orderBy("id", 'desc')->get()->getResultObject();
        $data['db'] =  Database::connect();
        return view('landing/index', $data);
    }
    
    public function tes()
    {
        $smtp = $this->SmtpModel->asObject()->first();
        $config['protocol']    = 'smtp';
        $config['SMTPHost']    = $smtp->smtp_host;
        $config['SMTPUser']    = $smtp->smtp_user;
        $config['SMTPPass']    = $smtp->smtp_pass;
        $config['SMTPPort']    = $smtp->smtp_port;
        $config['SMTPCrypto']  = $smtp->smtp_crypto;
        $config['mailType']    = 'html';
        $config['charset']     = 'UTF-8';
        $config['CRLF']        = "\r\n"; // Gunakan petik dua (") agar terbaca line break
        $config['SMTPTimeout'] = 60;

        $this->email->initialize($config);

        $this->email->setNewline("\r\n");

        $this->email->setFrom($smtp->smtp_user, 'KELASBREVET');
        $this->email->setTo("mulyonomuiz63@gmail.com");

        $this->email->setSubject('Akun KelasBrevet');
        $this->email->setMessage('
                <div style="color: #000; padding: 10px;">
                    <div
                        style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; font-size: 20px; color: #1C3FAA; font-weight: bold;">
                        REGISTRASI</div>
                    
                    <br>
                    <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Hallo MMasyon <br>
                        <span style="color: #000;">Kami menambahkan anda ke dalam KelasBrevet</span></p>
                    <table style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">
                        <tr>
                            <td>Nama</td>
                            <td> : Masyon</td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td> : mulyonomuiz63@gmail.com</td>
                        </tr>
                        <tr>
                            <td>Kelas</td>
                            <td> : Percobaan</td>
                        </tr>
                    </table>
                    </div>
            ');

        if (!$this->email->send()) {
            echo $this->email->printDebugger();
            // die();
        }else{
            echo "berhasil";
        }

    }
    
    public function get_paket()
    {
        if ($this->request->isAJAX()) {
            $paket = $this->request->getVar('idpaket');
            $data_paket = $this->PaketModel->asObject()->find($paket);
            echo json_encode($data_paket);
        }
    }
    
    public function pelatihan()
    {
        $data['paket'] = $this->PaketModel->getAllLimit();
        $data['lihat'] = '0';
        //untuk breadcrumb 
        $breadcrumbItems = [
            "Home" => base_url(),
            "Pelatihan" => current_url()
        ];
        
            $paket=$data['paket'];
        if(!empty($data['paket'])){
            $paket = $data['paket'][0]->nama_paket;
        }
        
        $data['affiliate'] =  $this->affiliateModel->where('user_id', session()->get('id'))->first();
        
        $title = "{$paket} - Paket Pelatihan Terbaik di kelasbrevet";
        $desc = "Temukan paket {$paket} terbaik dengan harga terjangkau di kelasbrevet.";
        $schemaItemList  = $this->seo->itemListSchema($data['paket'], $title, $desc, current_url());
        $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
        
        $schema = $schemaItemList . "\n" . $schemaBreadcrumb;
        $data['schema'] = $schema;
        $data['db'] =  Database::connect();
        return view('landing/pelatihan', $data);
    }
    public function testimoni()
    {
        //untuk breadcrumb 
        $breadcrumbItems = [
            "Home" => base_url(),
            "Testimoni" => current_url()
        ];
        $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
        $schema = $schemaBreadcrumb;
        $data['schema'] = $schema;
        $data['db'] =  Database::connect();
        return view('landing/testimoni', $data);
    }
    public function tentangkami()
    {   //untuk breadcrumb 
        $breadcrumbItems = [
            "Home" => base_url(),
            "Tentang Kami" => current_url()
        ];
        $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
        $schema = $schemaBreadcrumb;
        $data['schema'] = $schema;
        $data['db'] =  Database::connect();
        return view('landing/tentangkami', $data);
    }
    public function siap_kerja()
    {
        //untuk breadcrumb 
        $breadcrumbItems = [
            "Home" => base_url(),
            "Siap Kerja" => current_url()
        ];
        $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
        $schema = $schemaBreadcrumb;
        $data['schema'] = $schema;
        $data['db'] =  Database::connect();
        return view('landing/siapkerja', $data);
    }
    public function penilaian()
    {
        //untuk breadcrumb 
        $breadcrumbItems = [
            "Home" => base_url(),
            "Penilaian" => current_url()
        ];
        $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
        $schema = $schemaBreadcrumb;
        $data['schema'] = $schema;
        $data['db'] =  Database::connect();
        return view('landing/penilaian', $data);
    }
    public function term()
    {
        //untuk breadcrumb 
        $breadcrumbItems = [
            "Home" => base_url(),
            "Terms of Conditions" => current_url()
        ];
        $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
        $schema = $schemaBreadcrumb;
        $data['schema'] = $schema;
        $data['db'] =  Database::connect();
        return view('landing/term', $data);
    }
    public function privasi()
    {
        //untuk breadcrumb 
        $breadcrumbItems = [
            "Home" => base_url(),
            "Privacy Policy" => current_url()
        ];
        $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
        $schema = $schemaBreadcrumb;
        $data['schema'] = $schema;
        $data['db'] =  Database::connect();
        return view('landing/service', $data);
    }
    
    public function twibbon()
    {
       $model = $this->TwibbonModel;
        $data['data'] = [
			    'twibbon' => $model->orderBy('idtwibbon', 'desc')->paginate(8),
			    'pager' => $model->pager
			];
			
		//untuk breadcrumb 
        $breadcrumbItems = [
            "Home" => base_url(),
            "Twibbon" => current_url()
        ];
        $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
        $schema = $schemaBreadcrumb;
        $data['schema'] = $schema;
        $data['db'] =  Database::connect();
        return view('landing/twibbon',$data);
    }
    
    public function twibbon_url($url)
    {
        //untuk breadcrumb 
        $breadcrumbItems = [
            "Home" => base_url(),
            "Twibbon" => base_url('twibbon/'),
            "brevet-pajak" => current_url()
        ];
        $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
        $schema = $schemaBreadcrumb;
        $data['schema'] = $schema;
        $data['twibbon'] = $this->TwibbonModel->where('url', $url)->get()->getRowObject();
        $data['db'] =  Database::connect();
        return view('landing/twibbon-url', $data);
    }
    

    public function jadwal()
    {
        $model = $this->BatchModel;
        
        $dataBatch = $this->BatchModel->get()->getResultObject();
        $date = date("Y-m-d");
        
        foreach($dataBatch as $rows){
            // untuk update otomatis jadwal
            $a = $this->JadwalModel->select('id')->where('idbatch', $rows->idbatch)->where('status', 'Menunggu')->where('tgl_pelatihan <=', $date)->get()->getResultObject();
            foreach($a as $item){
                $this->JadwalModel
                ->where('id', $item->id)
                ->set('status', 'Selesai')
                ->update();
            }
            
            //update otomatis batch
            $b = $this->JadwalModel->select('status')->where('idbatch', $rows->idbatch)->get()->getResultObject();
            $total=0;
            $selesai=0;
            if(!empty($b)){
                foreach($b as $itemBatch){
                    $total++;
                   if($itemBatch->status == 'Selesai'){
                       $selesai++;
                   }
                }
                
                if($total == $selesai){
                    $this->BatchModel
                    ->where('idbatch', $rows->idbatch)
                    ->set('status_batch', 'S')
                    ->update();
                }
            }
        }

		$data['data'] = [
			    'batch' => $model->orderBy('status_batch', 'desc')->paginate(10),
			    'pager' => $model->pager
			];
		
		//untuk breadcrumb
		$breadcrumbItems = [
            "Home" => base_url(),
            "Jadwal" => current_url()
        ];
        $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
        $schema = $schemaBreadcrumb;
        $data['schema'] = $schema;
        $data['db'] =  Database::connect();
        return view('landing/jadwal', $data);
    }
    
    public function galeri()
    {
        $model = $this->GaleriModel;
        $data['data'] = [
			    'galeri' => $model->orderBy('idgaleri', 'desc')->paginate(8),
			    'pager' => $model->pager
			];
		
		//untuk breadcrumb
		$breadcrumbItems = [
            "Home" => base_url(),
            "Galeri" => current_url()
        ];
        $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
        $schema = $schemaBreadcrumb;
        $data['schema'] = $schema;
        $data['db'] =  Database::connect();
        return view('landing/galeri', $data);
    }
    
    public function media()
    {
        $model = $this->InstagramModel;
        $data['data'] = [
			    'galeri' => $model->orderBy('id', 'desc')->paginate(8),
			    'pager' => $model->pager
			];
		
		//untuk breadcrumb
		$breadcrumbItems = [
            "Home" => base_url(),
            "Media" => current_url()
        ];
        $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
        $schema = $schemaBreadcrumb;
        $data['schema'] = $schema;
        $data['db'] =  Database::connect();
        return view('landing/instagram', $data);
    }
    
    public function presensi($id){
        //untuk breadcrumb
		$breadcrumbItems = [
            "Home" => base_url(),
            "Presensi" => current_url()
        ];
        $schemaBreadcrumb = $this->seo->breadcrumbSchema($breadcrumbItems);
        $schema = $schemaBreadcrumb;
        $data['schema'] = $schema;
        
        $idjadwalpelatihan = decrypt_url($id);
        $data['jadwal'] = $this->JadwalModel->where('id', $idjadwalpelatihan)->get()->getRowObject();
        $data['db'] =  Database::connect();
        if(!empty($data['jadwal'])){
            if($data['jadwal']->tgl_pelatihan == date("Y-m-d")){
            // if("2025-09-24" == date("Y-m-d")){
                $mulai = $data['jadwal']->mulai;    // jam mulai (bisa diganti sesuai kebutuhan)
                
                // hitung jam selesai = jam mulai + 4 jam
                $selesai = date("H:i", strtotime($mulai . " +4 hours"));
                
                // waktu sekarang
                $sekarang = date("H:i");
                
                // cek dengan if
                if ($sekarang >= $mulai && $sekarang <= $selesai) {
                    return view('landing/presensi', $data);
                }else{
                    session()->setFlashdata('pesan', "
                        swal({
                            title: 'Informasi!',
                            text: 'Pelatihan telah selesai dilaksanakan.',
                            type: 'info',
                            padding: '2em'
                        }); 
                    ");
                    return redirect()->to('jadwal');
                }
            }else{
                session()->setFlashdata('pesan', "
                        swal({
                            title: 'Informasi!',
                            text: 'Pelatihan telah selesai dilaksanakan.',
                            type: 'info',
                            padding: '2em'
                        }); 
                    ");
                return redirect()->to('jadwal');
            }
        }else{
            session()->setFlashdata('pesan', "
                        swal({
                            title: 'Informasi!',
                            text: 'Halaman yang dicari tidak ditemukan.',
                            type: 'info',
                            padding: '2em'
                        }); 
                    ");
                return redirect()->to('jadwal');
        }
        
    }
    
    public function presensi_(){
        $idjadwalpelatihan = decrypt_url($this->request->getVar('idjadwalpelatihan'));
        $dataSiswa = $this->db->table('siswa')->where('email', $this->request->getVar('email'))->get()->getRowObject();
        if(!empty($dataSiswa)){
            $dataPresensi = $this->db->table('presensi')->where('idsiswa', $dataSiswa->id_siswa)->where('idjadwalpelatihan', $idjadwalpelatihan)->get()->getRowObject();
            if(empty($dataPresensi)){
                $data = array(
                            'idsiswa'               => $dataSiswa->id_siswa,
                            'idjadwalpelatihan'     => $idjadwalpelatihan,
                        );
        
                $builder = $this->db->table('presensi');
                $builder->insert($data);
                session()->setFlashdata('pesan', "
                    swal({
                        title: 'Berhasil!',
                        text: 'Anda berhasil melakukan presensi',
                        type: 'success',
                        padding: '2em'
                    }); 
                ");
                return redirect()->to('presensi/'.$this->request->getVar('idjadwalpelatihan'));
            }else{
                session()->setFlashdata('pesan', "
                    swal({
                        title: 'Informasi!',
                        text: 'Anda sudah melakukan presensi',
                        type: 'info',
                        padding: '2em'
                    }); 
                ");
                return redirect()->to('presensi/'.$this->request->getVar('idjadwalpelatihan'));
            }
        }else{
            session()->setFlashdata('pesan', "
                    swal({
                        title: 'Informasi!',
                        text: 'Email yang anda masukan tidak terdaftar disistem kami.',
                        type: 'error',
                        padding: '2em'
                    }); 
                ");
            return redirect()->to('jadwal');
        }
    }
}

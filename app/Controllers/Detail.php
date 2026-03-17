<?php

namespace App\Controllers;

use App\Models\UjianModel;
use App\Models\UjianSiswaModel;
use App\Models\UjianMasterModel;
use App\Models\UjianDetailModel;
use App\Models\SiswaModel;


use App\Libraries\Pdf;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class Detail extends BaseController
{

    protected $UjianModel;
    protected $UjianSiswaModel;
    protected $UjianMasterModel;
    protected $UjianDetailModel;
    protected $SiswaModel;
    

    public function __construct()
    {
        $validation = \Config\Services::validation();
        $this->UjianModel = new UjianModel();
        $this->UjianSiswaModel = new UjianSiswaModel();
        $this->UjianMasterModel = new UjianMasterModel();
        $this->UjianDetailModel = new UjianDetailModel();
        $this->SiswaModel = new SiswaModel();

        $this->email = \Config\Services::email();
        
         
    }

    public function data($id_ujian)
    {
        $data['ujian'] = $this->UjianModel->select('ujian.nama_ujian, ujian.start_ujian, ujian.nilai, ujian.kode_ujian, ujian.id_ujian, siswa.nama_siswa, kelas.nama_kelas, mapel.nama_mapel')->join('siswa','ujian.id_siswa=siswa.id_siswa')->join('kelas','ujian.kelas=kelas.id_kelas')->join('mapel','ujian.mapel=mapel.id_mapel')->where('id_ujian', decrypt_url($id_ujian))->where('nilai >=', 60)->get()->getRowObject();
        if(!isset($data['ujian'])){
        session()->setFlashdata('pesan', "
                        swal({
                            title: 'Info!',
                            text: 'Data yang anda masukan tidak ditemukan...',
                            type: 'info',
                            padding: '2em'
                            });
                        ");
            return redirect()->to('Auth');
        }else{
            session()->setFlashdata('pesan', "
                        swal({
                            title: 'Informasi',
                            text: 'Sertifikat Telah Terverifikasi',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
            return view('detail', $data);
        }
    }
    
     public function data_ab($id_siswa)
    {
        $data['hasil'] = $this->UjianModel->getByIdsiswa(decrypt_url($id_siswa));
        $data['data'] = $this->UjianModel->getByIdsiswaDesc(decrypt_url($id_siswa));
             
        if(!isset($data['hasil'])){
            session()->setFlashdata('pesan', "
                        swal({
                            title: 'Info!',
                            text: 'Data yang anda masukan tidak ditemukan...',
                            type: 'info',
                            padding: '2em'
                            });
                        ");
            return redirect()->to('Auth');
        }else{
            session()->setFlashdata('pesan', "
                        swal({
                            title: 'Informasi',
                            text: 'Sertifikat Telah Terverifikasi',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
            return view('detail_ab', $data);
        }
    }
    
    public function lihat_sertifikat($kode_ujian, $id_ujian)
    {
       $kode_ujian = decrypt_url($kode_ujian);
        $id_ujian = decrypt_url($id_ujian);
        $hasil = $this->UjianModel->getBykode($kode_ujian, $id_ujian);

        new Pdf();


        $pdf = new \setasign\Fpdi\Fpdi();
        $pdf->SetAutoPageBreak(false, 5);



        // PAGE 1
        $pdf->AddPage('L');
        $pdf->Image('public/brevet-ab.jpg', 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());

        //echo $pdf->GetPageWidth();

        //$pdf->setSourceFile('sertifikat.pdf');    

        //$tplIdx = $pdf->importPage(1);
        //$pdf->useTemplate($tplIdx);

        $pdf->SetTextColor(51, 49, 49);

        //nis
        $bulanNomor = array('I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII');
        $pdf->SetFont('Arial', 'B', '18');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(28, 70);
        $pdf->Cell(75, 4,"Nomor : ".$hasil->id_ujian . '/' .'KB-EC'. '/' . $bulanNomor[(int)date('m', strtotime($hasil->start_ujian)) - 1] . '/' . date('Y', strtotime($hasil->start_ujian)), 0, 1, 'L');

        //nama
        $pdf->SetFont('Arial', 'B', '24');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(28, 123);
        $pdf->Cell(75, 4, strtoupper($hasil->nama_siswa), 0, 1, 'L');

        //nilai
        $pdf->SetFont('Arial', 'B', '18');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(28, 138);
        $pdf->Cell(75, 4,"NIP : ". $hasil->no_induk_siswa, 0, 1, 'L');
        
        //keterangan 1
        $pdf->SetFont('Arial', 'B', '18');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(28, 150);
        $pdf->Cell(75, 4,"Dinyatakan [LULUS] dengan nilai ". $hasil->nilai, 0, 1, 'L');
        
         //keterangan 2
        $pdf->SetFont('Arial', 'B', '18');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(28, 158);
        if($hasil->nilai < 60){
            $nilai = "D";
        }elseif($hasil->nilai < 70){
            $nilai = "C";
        }elseif($hasil->nilai < 80){
            $nilai = "B";
        }elseif($hasil->nilai < 90){
            $nilai = "A";
        }else{
             $nilai = "A+";
        }
        $pdf->Cell(75, 4,"Kualifikasi kelulusan ". $nilai, 0, 1, 'L');
        
         //keterangan 3
        $bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        $pdf->SetFont('Arial', 'B', '18');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(28, 166);
        $pdf->Cell(75, 4,"Pada tanggal ". date('d', strtotime($hasil->start_ujian)) .  ' ' . $bulan[(int)date('m', strtotime($hasil->start_ujian)) - 1] . ' ' . date('Y', strtotime($hasil->start_ujian)), 0, 1, 'L');

        //keterangan 3
        $writer = new PngWriter();
        $qrCode = QrCode::create(base_url('detail/data').'/'.encrypt_url($hasil->id_ujian))
        ->setEncoding(new Encoding('UTF-8'))
        ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
        ->setSize(300)
        ->setMargin(10)
        ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
        ->setForegroundColor(new Color(0,0,0))
        ->setBackgroundColor(new Color(255,255,255));
        
        
        $logo = Logo::create('assets/img/logo-brevet.png')
        ->setResizeToWidth(100);
        
        // $label = Label::create("tes")
        // ->setTextColor(new Color(255, 0, 0));
        //  $result = $writer->write($qrCode,$logo, $label);
        
        
        $result = $writer->write($qrCode, $logo, null);
        $qrCodes = $result->getDataUri();
        $img = explode(',',$qrCodes,2)[1];
        $pic = 'data://text/plain;base64,'. $img;
        
        $pdf->Image($qrCodes, 30, 175, 30, 30, 'png');
        
        
        
        $pdf->AddPage('L');
        $pdf->Image('public/brevet-ab-2.jpg', 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());
         //nama 
        $pdf->SetFont('Arial', 'B', '18');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(55, 56);
        $pdf->Cell(75, 4,strtoupper($hasil->nama_siswa), 0, 1, 'L');
        
        
        $this->response->setContentType('application/pdf');


        $pdf->Output('sertifikat.pdf', 'I');
    }
    
    

    public function otomatis_kirim_ujian(){
         $data = $this->UjianModel->where('status', 'U')->where('end_ujian <', date('Y-m-d H:i'))->get()->getResultObject();
         foreach($data as $rows){
             $this->UjianSiswaModel
                ->set('status', 'selesai')
                ->set('date_send', time())
                ->where('ujian', $rows->kode_ujian)
                ->where('siswa', $rows->id_siswa)
                ->update();
                
            $siswa = $this->SiswaModel->where('id_siswa', $rows->id_siswa)->get()->getResultObject();

            $data['ujian'] = array();
            foreach ($siswa as  $r) {
                $ujian = $this->UjianMasterModel->getAllUntukNilaiUjian($r->kelas, $r->id_siswa, $rows->kode_ujian);
    
                foreach ($ujian as $u) {
                    $data['ujian'][] = $u;
                }
            }

            if(!empty($data['ujian'])){
                for ($i = 0; $i < count($data['ujian']); $i++) {
                    $ujian_detail = $this->UjianDetailModel->getAllByKodeUjianJumlah($data['ujian'][$i]->kode_ujian);
                    $nilai = round($data['ujian'][$i]->benar / count($ujian_detail) * 100);
                     $this->UjianModel
                    ->set('status', 'S')
                    ->set('nilai', $nilai)
                    ->where('id_ujian', $rows->id_ujian)
                    ->update();
                    
                }
            }else{
                 $this->UjianModel
                    ->set('status', 'S')
                    ->set('nilai', 0)
                    ->where('id_ujian', $rows->id_ujian)
                    ->update();
            }
         }
    }
    
}

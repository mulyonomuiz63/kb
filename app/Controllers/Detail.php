<?php

namespace App\Controllers;

use App\Models\UjianModel;
use App\Models\UjiansiswaModel;
use App\Models\UjianMasterModel;
use App\Models\UjiandetailModel;
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
    protected $UjiansiswaModel;
    protected $UjianMasterModel;
    protected $UjiandetailModel;
    protected $SiswaModel;
    

    public function __construct()
    {
        $validation = \Config\Services::validation();
        $this->UjianModel = new UjianModel();
        $this->UjiansiswaModel = new UjiansiswaModel();
        $this->UjianMasterModel = new UjianMasterModel();
        $this->UjiandetailModel = new UjiandetailModel();
        $this->SiswaModel = new SiswaModel();      
         
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
}

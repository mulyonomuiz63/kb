<?php

namespace App\Controllers;

use App\Models\UjianModel;

class Detail extends BaseController
{

    protected $UjianModel;
    

    public function __construct()
    {
        $this->UjianModel = new UjianModel();
         
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

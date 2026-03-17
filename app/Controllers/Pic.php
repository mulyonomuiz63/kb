<?php

namespace App\Controllers;

use App\Models\PicModel;
use App\Models\SiswaModel;
use App\Models\GuruModel;
use App\Models\MitraModel;
use App\Models\MapelModel;
use App\Models\KelasModel;
use App\Models\TransaksiModel;
use App\Models\JadwalModel;
use App\Models\BatchModel;
use App\Models\GaleriModel;

use App\Models\UjianModel;
use App\Models\UjianMasterModel;



class Pic extends BaseController
{
    protected $PicModel;
    protected $SiswaModel;
    protected $GuruModel;
    protected $MitraModel;
    protected $KelasModel;
    protected $MapelModel;
    protected $TransaksiModel;
    protected $JadwalModel;
    protected $BatchModel;
    protected $GaleriModel;
    
    protected $UjianModel;
    protected $UjianMasterModel;
    

    public function __construct()
    {
        $validation = \Config\Services::validation();
        $this->PicModel = new PicModel();
        $this->SiswaModel = new SiswaModel();
        $this->GuruModel = new GuruModel();
        $this->MitraModel = new MitraModel();
        $this->KelasModel = new KelasModel();
        $this->MapelModel = new MapelModel();
        $this->JadwalModel = new JadwalModel();
        $this->BatchModel = new BatchModel();
        $this->GaleriModel = new GaleriModel();

        $this->TransaksiModel = new TransaksiModel();
        
        $this->UjianModel = new UjianModel();
        $this->UjianMasterModel = new UjianMasterModel();
        

    }
    
     //voucher
    public function index()
    {
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => 'active',
            'expanded' => 'true'
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_peserta'] = [
           'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_transaksi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_jadwal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['guru'] = $this->GuruModel->asObject()->findAll();
        $data['guru_aktif'] = $this->GuruModel
            ->where('is_active', 1)
            ->get()->getResultObject();
        $data['guru_tidak_aktif'] = $this->GuruModel
            ->where('is_active', 0)
            ->get()->getResultObject();

        $data['siswa'] = $this->SiswaModel->asObject()->findAll();
        $data['siswa_aktif'] = $this->SiswaModel
            ->where('is_active', 1)
            ->get()->getResultObject();

        $data['siswa_tidak_aktif'] = $this->SiswaModel
            ->where('is_active', 0)
            ->get()->getResultObject();
            
        $data['mitra'] = $this->MitraModel
            ->where('is_active', 1)
            ->get()->getResultObject();
            
            
       $sertifikatAB = $this->SiswaModel->getSertifikatAB();
        $no=1;
        $lulus = 0;
        foreach ($sertifikatAB as $s) {
            $ujian = $this->UjianModel->getAllByKelasSertifikat($s->kelas, $s->id_siswa);
                foreach ($ujian as $u) {
                    $data['ujian'][] = $u;
                }
                
                $dataUjian = $this->UjianMasterModel->where('kelas', $s->kelas)->groupBy('mapel')->get()->getResultObject();
                $total = 0;
                foreach($dataUjian as $rr){
                    $total++;
                }
                
                $totalUjian = $this->UjianModel->where('kelas', $s->kelas)->where('id_siswa', $s->id_siswa)
                            ->where('ujian.nilai >=', 60)
                            ->groupBy('ujian.mapel')->get()->getResultObject();
                $totalSertifikat =0;
                foreach ($totalUjian as $r){
                    $totalSertifikat++;
                }
                
                if($total != 0 ){
                    if($totalSertifikat >= $total){
                        $lulus += $no;
                    }
                }
        }
        $data['peserta_lulus'] = $lulus;

        $data['kelas'] = $this->KelasModel->asObject()->findAll();
        $data['mapel'] = $this->MapelModel->asObject()->findAll();
        $data['pic'] = $this->PicModel->where('idpic',session('id'))->get()->getRowObject();

        return view('pic/dashboard', $data);
    }

   
    // START::PROFILE & SETTING
    public function profile()
    {
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        
        $data['menu_profile'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_peserta'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_transaksi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_jadwal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        
       $data['pic'] = $this->PicModel->where('idpic',session('id'))->get()->getRowObject();
        return view('pic/profile-setting', $data);
    }
    public function edit_profile()
    {
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
        $fileGambar = $this->request->getFile('avatar');

        // Cek Gambar, Apakah Tetap Gambar lama
        if ($fileGambar->getError() == 4) {
            $nama_gambar = $this->request->getVar('gambar_lama');
        } else {
            // Generate nama file Random
            $nama_gambar = $fileGambar->getRandomName();
            // Upload Gambar
            $fileGambar->move('assets/pic-assets/user/', $nama_gambar);
            // hapus File Yang Lama
            if ($this->request->getVar('gambar_lama') != 'default.jpg') {
                unlink('assets/pic-assets/user/' . $this->request->getVar('gambar_lama'));
            }
        }

        $this->PicModel->save([
            'idpic' => session()->get('id'),
            'nama_pic' => $this->request->getVar('nama_pic'),
            'avatar' => $nama_gambar
        ]);

        session()->setFlashdata('pesan', "
            swal({
                title: 'Berhasil!',
                text: 'Profile telah diubah',
                type: 'success',
                padding: '2em'
            }); 
        ");
        return redirect()->to('pic/profile');
    }
    public function edit_password()
    {
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
        $admin = $this->PicModel->asObject()->find(session()->get('id'));

        if (password_verify($this->request->getVar('current_password'), $admin->password)) {
            $this->PicModel->save([
                'idpic' => $admin->idpic,
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
            ]);
            session()->setFlashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Password telah diubah',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
            return redirect()->to('pic/profile');
        } else {
            session()->setFlashdata('pesan', "
                        swal({
                            title: 'Oops..',
                            text: 'Current Password Salah',
                            type: 'error',
                            padding: '2em'
                            });
                        ");
            return redirect()->to('pic/profile');
        }
    }
    
    
     // START::SISWA
    public function siswa()
    {
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_peserta'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_transaksi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_jadwal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        // END MENU DATA
        // ================================================

        // MASTER DATA
        $data['siswa'] = $this->SiswaModel->getAll();
        $data['pic'] = $this->PicModel->where('idpic',session('id'))->get()->getRowObject();
        $data['kelas'] = $this->KelasModel->asObject()->findAll();
        return view('pic/siswa/list', $data);
    }
    
     // START::transaksi
    public function transaksi()
    {
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_peserta'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_transaksi'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_jadwal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        // END MENU DATA
        // ================================================

        // MASTER DATA
        $data['transaksi'] = $this->TransaksiModel->getAllStatusPeserta();
        $data['pic'] = $this->PicModel->where('idpic',session('id'))->get()->getRowObject();
        $data['kelas'] = $this->KelasModel->asObject()->findAll();
        return view('pic/transaksi/list', $data);
    }
    
    public function sertifikatAB()
    {
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_peserta'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_transaksi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_jadwal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        // END MENU DATA
        // ================================================

        // MASTER DATA
        $data['sertifikatAB'] = $this->SiswaModel->getSertifikatAB();
        $data['kelas'] = $this->KelasModel->asObject()->findAll();
        $data['pic'] = $this->PicModel->where('idpic',session('id'))->get()->getRowObject();
        return view('pic/siswa/listAB', $data);
    }
    
    public function sertifikat($id){
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_peserta'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_transaksi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_jadwal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        // END MENU DATA
        // ================================================

        // MASTER DATA
        $data['siswa'] = $this->SiswaModel->getAll();
        $data['kelas'] = $this->KelasModel->asObject()->findAll();
        $data['pic'] = $this->PicModel->where('idpic',session('id'))->get()->getRowObject();
        $idsiswa = decrypt_url($id);

        $siswa = $this->SiswaModel->where('id_siswa', $idsiswa)->get()->getResultObject();

        $data['ujian'] = array();
        foreach ($siswa as  $r) {
            $ujian = $this->UjianModel->getAllByKelasSertifikat($r->kelas, $r->id_siswa);
            foreach ($ujian as $u) {
                $data['ujian'][] = $u;
            }
            
            $dataUjian = $this->UjianMasterModel->where('kelas', $r->kelas)->groupBy('mapel')->get()->getResultObject();
            $total = 0;
            foreach($dataUjian as $rr){
                $total++;
            }
            
            $totalUjian = $this->UjianModel->where('kelas', $r->kelas)->where('id_siswa', $r->id_siswa)
                        ->where('ujian.nilai >=', 60)
                        ->groupBy('ujian.mapel')->get()->getResultObject();
            $totalSertifikat =0;
            foreach ($totalUjian as $r){
                $totalSertifikat++;
            }
            
            $data['totalSertifikat']=$totalSertifikat;
            $data['total']=$total;
        }

      

        return view('pic/sertifikat/list', $data);
    }
    
    public function ujian($id){
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_peserta'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_transaksi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_jadwal'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        
         // MASTER DATA
        $data['siswa'] = $this->SiswaModel->getAll();
        $data['kelas'] = $this->KelasModel->asObject()->findAll();
        $data['pic'] = $this->PicModel->where('idpic',session('id'))->get()->getRowObject();

        $idsiswa = decrypt_url($id);
        $data['siswa'] = $this->SiswaModel->asObject()->find($idsiswa);
        //$data['ujian'] = $this->UjianModel->getAllBykelas($data['siswa']->kelas);

        $siswa = $this->SiswaModel->where('id_siswa', $idsiswa)->get()->getResultObject();
        $data['ujian'] = array();
        foreach ($siswa as  $r) {
            $tugas = $this->UjianModel->getAllByKelas($r->kelas, $r->id_siswa);

            foreach ($tugas as $t) {
                $data['ujian'][] = $t;
            }
        }

        return view('pic/siswa/ujian/list', $data);
    }
    
    public function jadwal()
    {
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_peserta'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_transaksi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_jadwal'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        // END MENU DATA
        // ================================================

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
            if(!empty($b)){
                $total=0;
                $selesai=0;
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
        // MASTER DATA
        $data['batch'] = $this->BatchModel->getAll();
        $data['kelas'] = $this->KelasModel->asObject()->findAll();
        $data['pic'] = $this->PicModel->where('idpic',session('id'))->get()->getRowObject();
        return view('pic/jadwal/batch', $data);
    }
    
    public function hapusBatch($id){
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
        
        
        $idbatch = decrypt_url($id);
        
        $data_jadwal = $this->JadwalModel
            ->where('idbatch', $idbatch)
            ->get()->getResultObject();
            
        if(!empty($data_jadwal)){
            foreach($data_jadwal as $rows){
                $this->JadwalModel->delete($rows->id); 
            }
        }
        
        
        $this->BatchModel->delete($idbatch); 
        
       
        session()->setFlashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'Data dihapus',
                    type: 'success',
                    padding: '2em'
                    })
                ");
        return redirect()->to('Pic/jadwal');  
    }
    
    public function tambah_batch()
    {
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
       
        $batch         = $this->request->getPost('batch');
        $status_batch  = $this->request->getPost('status_batch');
        
        

        $data = array(
            'batch'        => $batch,
            'status_batch' => $status_batch,
        );


        $sql = $this->BatchModel->insert($data);

        // Cek apakah query insert nya sukses atau gagal
        if ($sql) { // Jika sukses
            session()->setFlashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'data disimpan',
                    type: 'success',
                    padding: '2em'
                    })
                ");
            return redirect()->to('Pic/jadwal');
        } else { // Jika gagal
            session()->setFlashdata('pesan', "
                swal({
                    title: 'Error!',
                    text: 'gagal disimpan',
                    type: 'error',
                    padding: '2em'
                    })
                ");
            return redirect()->to('Pic/jadwal');
        }
    }
    public function edit_batch()
    {
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
        if ($this->request->isAJAX()) {
            $id = decrypt_url($this->request->getVar('id'));
            $data_batch = $this->BatchModel->asObject()->find($id);
            echo json_encode($data_batch);
        }
    }
    
    public function edit_batch_()
    {
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
        
        $idbatch            = $this->request->getPost('idbatch');
        $batch         = $this->request->getPost('batch');
        $status_batch  = $this->request->getPost('status_batch');
        
        $this->BatchModel->save([
            'idbatch'       => $idbatch,
            'batch'         => $batch,
            'status_batch'  => $status_batch,
        ]);

        session()->setFlashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'Data telah diubah',
                    type: 'success',
                    padding: '2em'
                    })
                ");
        return redirect()->to('Pic/jadwal');
    }
    
    public function detail_jadwal($id)
    {
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_peserta'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_transaksi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_jadwal'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        // END MENU DATA
        // ================================================

        // MASTER DATA
        $idbatch = decrypt_url($id);
        $data['jadwal'] = $this->JadwalModel->where('idbatch', $idbatch)->get()->getResultObject();
        $data['idbatch'] = $idbatch;
        $data['kelas'] = $this->KelasModel->asObject()->findAll();
        $data['pic'] = $this->PicModel->where('idpic',session('id'))->get()->getRowObject();
        return view('pic/jadwal/index', $data);
    }
    
    public function presensi($id)
    {
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_peserta'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_transaksi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_jadwal'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        // END MENU DATA
        // ================================================

        // MASTER DATA
        $idjadwalpelatihan = decrypt_url($id);
        $data['presensi'] = $this->db->table('presensi')->join('siswa','presensi.idsiswa=siswa.id_siswa')->where('presensi.idjadwalpelatihan', $idjadwalpelatihan)->get()->getResultObject();
        $data['pic'] = $this->PicModel->where('idpic',session('id'))->get()->getRowObject();
        return view('pic/jadwal/presensi', $data);
    }
    
    public function hapusJadwal($id, $idb){
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
        
        
        $idjadwal = decrypt_url($id);
        $idbatch = $idb;
        $this->JadwalModel->delete($idjadwal); 
        
       
        session()->setFlashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'Data dihapus',
                    type: 'success',
                    padding: '2em'
                    })
                ");
        return redirect()->to('Pic/detail_jadwal/'.$idbatch);  
    }
    
    public function tambah_jadwal()
    {
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
       
        $idbatch        = $this->request->getPost('idbatch');
        $materi         = $this->request->getPost('materi');
        $jenis          = $this->request->getPost('jenis');
        $kelas          = $this->request->getPost('kelas');
        $kapasitas      = $this->request->getPost('kapasitas');
        $pendaftar      = $this->request->getPost('pendaftar');
        $tgl_pelatihan  = $this->request->getPost('tgl_pelatihan');
        $mulai          = $this->request->getPost('mulai');
        $berakhir       = $this->request->getPost('berakhir');
        $status         = $this->request->getPost('status');
        
        
        $idbatc = decrypt_url($idbatch);
        $data = array(
            'idbatch'       => $idbatc,
            'materi'        => $materi,
            'jenis'         => $jenis,
            'kelas'         => $kelas,
            'kapasitas'     => $kapasitas,
            'pendaftar'     => $pendaftar,
            'tgl_pelatihan' => $tgl_pelatihan,
            'mulai'         => $mulai,
            'berakhir'      => $berakhir,
            'status'        => $status,
        );


        $sql = $this->JadwalModel->insert($data);

        // Cek apakah query insert nya sukses atau gagal
        if ($sql) { // Jika sukses
            session()->setFlashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'data disimpan',
                    type: 'success',
                    padding: '2em'
                    })
                ");
            return redirect()->to('Pic/detail_jadwal/'.$idbatch);
        } else { // Jika gagal
            session()->setFlashdata('pesan', "
                swal({
                    title: 'Error!',
                    text: 'gagal disimpan',
                    type: 'error',
                    padding: '2em'
                    })
                ");
            return redirect()->to('Pic/detail_jadwal/'.$idbatch);
        }
    }
    public function edit_jadwal()
    {
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
        if ($this->request->isAJAX()) {
            $id = decrypt_url($this->request->getVar('id'));
            $data_jadwal = $this->JadwalModel->asObject()->find($id);
            echo json_encode($data_jadwal);
        }
    }
    
    public function get_jadwal()
    {
        if ($this->request->isAJAX()) {
            $data_Jadwal = $this->JadwalModel
            ->where('id', $this->request->getVar('id'))
            ->get()->getRowObject();;
           if (!empty($data_jadwal)) {
                echo '1';
            } else {
                echo '0';
            }
        }
    }
    public function edit_jadwal_()
    {
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
        
        $idbatch        = $this->request->getPost('idbatch');
        $id             = $this->request->getPost('id');
        $materi         = $this->request->getPost('materi');
        $jenis          = $this->request->getPost('jenis');
        $kelas          = $this->request->getPost('kelas');
        $kapasitas      = $this->request->getPost('kapasitas');
        $pendaftar      = $this->request->getPost('pendaftar');
        $tgl_pelatihan  = $this->request->getPost('tgl_pelatihan');
        $mulai          = $this->request->getPost('mulai');
        $berakhir       = $this->request->getPost('berakhir');
        $status         = $this->request->getPost('status');
        
        $idbatc = decrypt_url($idbatch);
        $this->JadwalModel->save([
            'id'            => $id,
            'idbatch'       => $idbatc,
            'materi'        => $materi,
            'jenis'         => $jenis,
            'kelas'         => $kelas,
            'kapasitas'     => $kapasitas,
            'pendaftar'     => $pendaftar,
            'tgl_pelatihan' => $tgl_pelatihan,
            'mulai'         => $mulai,
            'berakhir'      => $berakhir,
            'status'        => $status,
        ]);

        session()->setFlashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'Data telah diubah',
                    type: 'success',
                    padding: '2em'
                    })
                ");
        return redirect()->to('Pic/detail_jadwal/'.$idbatch);
    }
    
    //galeri
    public function galeri()
    {
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
        
        // MASTER DATA
        $data['galeri'] = $this->GaleriModel->getAll();
        $data['pic'] = $this->PicModel->where('idpic',session('id'))->get()->getRowObject();
        return view('pic/galeri/list', $data);
    }
    
    public function hapusGaleri($id){
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
        
        
        $idgaleri= decrypt_url($id);
        $data_galeri = $this->GaleriModel
            ->where('idgaleri', $idgaleri)
            ->get()->getRowObject();
        
        
        
        
        if (!empty($data_galeri)) {
            if (file_exists('./uploads/galeri/thumbnails/' . $data_galeri->file)) {
                unlink('./uploads/galeri/thumbnails/' . $data_galeri->file);
            };
        }
        $this->GaleriModel->delete($idgaleri); 
        session()->setFlashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'Data dihapus',
                    type: 'success',
                    padding: '2em'
                    })
                ");
        return redirect()->to('Pic/galeri');  
    }
    
    public function tambah_galeri()
    {
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
       
        $judul           = $this->request->getPost('judul');
        $file           = $this->request->getFile('file');
        $tgl_pelatihan  = $this->request->getPost('tgl_pelatihan');
        if ($file->isValid()) {
            $newName = $file->getRandomName();
            // thumnail foto_ktp path
            $thumbnail_path = FCPATH . 'uploads/galeri/thumbnails';

            $path = FCPATH . 'uploads/galeri';
            if ($file->move($path, $newName)) {
                // resizing newName
                $this->image->withFile($path . '/' . $newName)
                    ->resize(1012, 1012, true, 'auto') // maintain ratio, auto dimensi
                    ->save($thumbnail_path . '/' . $newName, 80);

                if (file_exists('./uploads/galeri/' . $newName)) {
                    unlink('./uploads/galeri/' . $newName);
                };
            }
        }

        $data = array(
            'judul'          => $judul,
            'tgl_pelatihan'  => $tgl_pelatihan,
            'file'           => $newName,
        );


        $sql = $this->GaleriModel->insert($data);

        // Cek apakah query insert nya sukses atau gagal
        if ($sql) { // Jika sukses
            session()->setFlashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'data disimpan',
                    type: 'success',
                    padding: '2em'
                    })
                ");
            return redirect()->to('Pic/galeri');
        } else { // Jika gagal
            session()->setFlashdata('pesan', "
                swal({
                    title: 'Error!',
                    text: 'gagal disimpan',
                    type: 'error',
                    padding: '2em'
                    })
                ");
            return redirect()->to('Pic/galeri');
        }
    }
    public function edit_galeri()
    {
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
        if ($this->request->isAJAX()) {
            $id = decrypt_url($this->request->getVar('idgaleri'));
            $data_galeri = $this->GaleriModel->asObject()->find($id);
            echo json_encode($data_galeri);
        }
    }
    
    public function edit_galeri_()
    {
        if (session()->get('role') != 5) {
            return redirect()->to('auth');
        }
        
        $idgaleri       = $this->request->getPost('idgaleri');
        $judul          = $this->request->getPost('judul');
        $file           = $this->request->getFile('file');
        $file_lama      = $this->request->getPost('file_lama');
        $tgl_pelatihan  = $this->request->getPost('tgl_pelatihan');
        
        if ($file->isValid()) {
                $newName = $file->getRandomName();
                // thumnail foto_ktp path
                $thumbnail_path = FCPATH . 'uploads/galeri/thumbnails';

                $path = FCPATH . 'uploads/galeri';
                if ($file->move($path, $newName)) {
                    // resizing newName
                    $this->image->withFile($path . '/' . $newName)
                        ->resize(1012, 1012, true, 'auto') // maintain ratio, auto dimensi
                        ->save($thumbnail_path . '/' . $newName, 80);

                    if (file_exists('./uploads/galeri/thumbnails/' . $file_lama)) {
                        unlink('./uploads/galeri/thumbnails/' . $file_lama);
                    };
                }
            } else {
                $newName = $file_lama;
            }



        $this->GaleriModel->save([
            'idgaleri'       => $idgaleri,
            'judul'          => $judul,
            'tgl_pelatihan'  => $tgl_pelatihan,
            'file'          => $newName,
        ]);

        session()->setFlashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'Data telah diubah',
                    type: 'success',
                    padding: '2em'
                    })
                ");
        return redirect()->to('Pic/galeri');
    }

}

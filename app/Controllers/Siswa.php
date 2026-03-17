<?php

namespace App\Controllers;
use Config\Services;

use App\Models\AdminModel;
use App\Models\SiswaModel;
use App\Models\GuruModel;
use App\Models\MapelModel;
use App\Models\KelasModel;
use App\Models\GuruKelasModel;
use App\Models\GuruMapelModel;
use App\Models\SmtpModel;
use App\Models\MateriModel;
use App\Models\TugasModel;
use App\Models\FileModel;
use App\Models\Materi_siswaModel;
use App\Models\Tugas_siswaModel;
use App\Models\ChatmateriModel;
use App\Models\ChattugasModel;
use App\Models\UjianModel;
use App\Models\UjianMasterModel;
use App\Models\UjianDetailModel;
use App\Models\UjianSiswaModel;
use App\Models\EssaydetailModel;
use App\Models\EssaysiswaModel;
use App\Models\TransaksiModel;
use App\Models\DetailTransaksiModel;
use App\Models\PaketModel;
use App\Models\MapelSiswaModel;
use App\Models\ReviewUjianModel;
use App\Models\AffiliateModel;

use App\Libraries\Pdf;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class Siswa extends BaseController
{
    protected $guruModel;
    protected $SiswaModel;
    protected $GuruModel;
    protected $MapelModel;
    protected $KelasModel;
    protected $GuruKelasModel;
    protected $GuruMapelModel;
    protected $SmtpModel;
    protected $MateriModel;
    protected $TugasModel;
    protected $FileModel;
    protected $Materi_siswaModel;
    protected $Tugas_siswaModel;
    protected $ChatmateriModel;
    protected $ChattugasModel;
    protected $UjianModel;
    protected $UjianMasterModel;
    protected $UjianDetailModel;
    protected $UjianSiswaModel;
    protected $EssaydetailModel;
    protected $EssaysiswaModel;
    protected $TransaksiModel;
    protected $DetailTransaksiModel;
    protected $PaketModel;
    protected $MapelSiswaModel;
    protected $ReviewUjianModel;
    protected $affiliateModel;


    public function __construct()
    {
        $validation = \Config\Services::validation();
        $this->AdminModel = new AdminModel();
        $this->SiswaModel = new SiswaModel();
        $this->GuruModel = new GuruModel();
        $this->MapelModel = new MapelModel();
        $this->KelasModel = new KelasModel();
        $this->GuruKelasModel = new GuruKelasModel();
        $this->GuruMapelModel = new GuruMapelModel();
        $this->SmtpModel = new SmtpModel();
        $this->MateriModel = new MateriModel();
        $this->TugasModel = new TugasModel();
        $this->FileModel = new FileModel();
        $this->Materi_siswaModel = new Materi_siswaModel();
        $this->Tugas_siswaModel = new Tugas_siswaModel();
        $this->ChatmateriModel = new ChatmateriModel();
        $this->ChattugasModel = new ChattugasModel();
        $this->UjianModel = new UjianModel();
        $this->UjianMasterModel = new UjianMasterModel();
        $this->UjianDetailModel = new UjianDetailModel();
        $this->UjianSiswaModel = new UjianSiswaModel();
        $this->EssaydetailModel = new EssaydetailModel();
        $this->EssaysiswaModel = new EssaysiswaModel();
        $this->TransaksiModel = new TransaksiModel();
        $this->DetailTransaksiModel = new DetailTransaksiModel();
        $this->PaketModel = new PaketModel();
        $this->MapelSiswaModel = new MapelSiswaModel();
        $this->ReviewUjianModel = new ReviewUjianModel();
        $this->affiliateModel = new AffiliateModel();
        

        date_default_timezone_set('Asia/Jakarta');

        $this->email = \Config\Services::email();
        if(!emptY(session()->get('role'))){
            if(session()->get('role') != 1){
                $this->dataSiswa = $this->SiswaModel->where('email', session()->get('email'))->get()->getRowObject();
            }
        }
         
    }

    public function index()
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
       
        $data['affiliate'] =  $this->affiliateModel->where('user_id', session()->get('id'))->first();
        $data['kelas'] = $this->KelasModel->asObject()->findAll();
        $data['mapel'] = $this->MapelModel->asObject()->findAll();
        $data['siswa'] = $this->SiswaModel->asObject()->find(session()->get('id'));
        
        //untuk data paket
        $data['paket'] = $this->PaketModel->getAll();

        
        return view('siswa/dashboard', $data);
    }

    // START::PROFILE
    public function profile()
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }

        $data['siswa'] = $this->SiswaModel->asObject()->find(session()->get('id'));

        return view('siswa/profile', $data);
    }
    public function edit_profile()
    {
        // 1. Cek Role
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
    
        // 2. Definisikan Rule Validasi
        $rules = [
            'nama_siswa' => [
                'rules'  => 'required|alpha_numeric_space|min_length[3]|max_length[60]',
                'errors' => [
                    'required'            => 'Nama tidak boleh kosong.',
                    'alpha_numeric_space' => 'Nama hanya boleh berisi huruf, angka, dan spasi.',
                    'min_length'          => 'Nama minimal 3 karakter.',
                    'max_length'          => 'Nama maksimal 60 karakter.'
                ]
            ],
            'avatar' => [
                'rules'  => 'max_size[avatar,2048]|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran foto terlalu besar (Max 2MB).',
                    'is_image' => 'Yang Anda pilih bukan gambar.',
                    'mime_in'  => 'Format gambar harus JPG, JPEG, atau PNG.'
                ]
            ],
            // NIK: Wajib angka dan tepat 16 digit
            'nik' => [
                'rules'  => 'required|numeric|exact_length[16]',
                'errors' => [
                    'required'     => 'NIK wajib diisi.',
                    'numeric'      => 'NIK harus berupa angka.',
                    'exact_length' => 'NIK harus tepat 16 digit.'
                ]
            ],
            
            // HP: Wajib angka, maksimal 15 digit
            'hp' => [
                'rules'  => 'required|numeric|min_length[10]|max_length[15]',
                'errors' => [
                    'required'   => 'Nomor HP wajib diisi.',
                    'numeric'    => 'Nomor HP harus berupa angka.',
                    'max_length' => 'Nomor HP maksimal 15 digit.',
                    'min_length' => 'Nomor HP minimal 10 digit.',
                ]
            ],
        
            // Field Text: Tidak boleh karakter aneh (hanya huruf, angka, spasi)
           'alamat_ktp' => [
                'rules'  => 'required|max_length[100]',
                'errors' => [
                    'required'            => 'Alamat KTP wajib diisi.',
                    'max_length'          => 'Alamat KTP maksimal 100 karakter.'
                ]
            ],
            'alamat_domisili' => [
                'rules'  => 'required|max_length[100]',
                'errors' => [
                    'required'            => 'Alamat domisili wajib diisi.',
                    'max_length'          => 'Alamat domisili maksimal 100 karakter.'
                ]
            ],
            'provinsi' => [
                'rules'  => 'required',
                'errors' => [
                    'required'            => 'Provinsi wajib diisi.',
                ]
            ],
            'kota' => [
                'rules'  => 'required',
                'errors' => [
                    'required'            => 'Kabupaten/Kota wajib diisi.',
                ]
            ],
            'kecamatan' => [
                'rules'  => 'required',
                'errors' => [
                    'required'            => 'Kecamatan wajib diisi.',
                ]
            ],
            'kelurahan' => [
                'rules'  => 'required',
                'errors' => [
                    'required'            => 'Kelurahan wajib diisi.',
                ]
            ],
            'profesi' => [
                'rules'  => 'required',
                'errors' => [
                    'required'            => 'Profesi wajib diisi.',
                ]
            ],
            'kota_intansi' => [
                'rules'  => 'required',
                'errors' => [
                    'required'            => 'Nama instansi wajib diisi.',
                ]
            ],
            'bidang_usaha' => [
                'rules'  => 'required',
                'errors' => [
                    'required'            => 'Bidang usaha wajib diisi.',
                ]
            ],
        ];
    
        // 3. Jalankan Validasi
        if (!$this->validate($rules)) {
            // Ambil pesan error pertama untuk ditampilkan di SweetAlert
            $errors = $this->validator->getErrors();
            $pesanError = reset($errors);
    
            session()->setFlashdata('pesan', "
                swal({
                    title: 'Gagal!',
                    text: '$pesanError',
                    type: 'error',
                    padding: '2em'
                }); 
            ");
           return redirect()->to(base_url('siswa/profile'))->withInput();
        }
    
        // 4. Proses File Gambar
        $file = $this->request->getFile('avatar');
        $rows = $this->request->getVar('gambar_lama');
    
        if (!$file->isValid()) {
            $nama_gambar = $rows;
        } else {
            $nama_gambar = $file->getRandomName();
            $path = FCPATH . 'assets/app-assets/user/';
    
            if ($file->move($path, $nama_gambar)) {
                // Kompres gambar
                \Config\Services::image()
                    ->withFile($path . $nama_gambar)
                    ->resize(1012, 1012, true, 'auto') 
                    ->save($path . $nama_gambar, 70); 
                    
                // Hapus gambar lama jika bukan default
                if ($rows != 'default.jpg' && file_exists($path . $rows)) {
                    unlink($path . $rows);
                }
            }
        }
    
        // 5. Sanitasi Input Nama (Potong 10 huruf untuk tampilan jika perlu, 
        // tapi simpan full di DB sesuai max_length validasi)
        $namaClean = strip_tags($this->request->getVar('nama_siswa'));
    
        // 6. Update Database
        $this->SiswaModel
            ->set('nama_siswa', $namaClean)
            ->set('jenis_kelamin', $this->request->getVar('jenis_kelamin'))
            ->set('avatar', $nama_gambar)
            ->set('nik', $this->request->getVar('nik'))
            ->set('tgl_lahir', $this->request->getVar('tgl_lahir'))
            ->set('alamat_ktp', $this->request->getVar('alamat_ktp'))
            ->set('alamat_domisili', $this->request->getVar('alamat_domisili'))
            ->set('provinsi', $this->request->getVar('provinsi'))
            ->set('kota', $this->request->getVar('kota'))
            ->set('kecamatan', $this->request->getVar('kecamatan'))
            ->set('kelurahan', $this->request->getVar('kelurahan'))
            ->set('hp', $this->request->getVar('hp'))
            ->set('profesi', $this->request->getVar('profesi'))
            ->set('kota_intansi', $this->request->getVar('kota_intansi'))
            ->set('bidang_usaha', $this->request->getVar('bidang_usaha'))
            ->set('kota_aktifitas_profesi', $this->request->getVar('kota_aktifitas_profesi'))
            ->set('status', 'S')
            ->where('id_siswa', session()->get('id'))
            ->update();
    
        session()->setFlashdata('pesan', "
            swal({
                title: 'Berhasil!',
                text: 'Profile telah diperbarui',
                type: 'success',
                padding: '2em'
            }); 
        ");
        return redirect()->to('siswa');
    }
    public function edit_password()
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        $siswa = $this->SiswaModel->asObject()->find(session()->get('id'));

        // if (password_verify($this->request->getVar('current_password'), $siswa->password)) {
            $this->SiswaModel->save([
                'id_siswa' => $siswa->id_siswa,
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
            return redirect()->to('siswa/profile');
        // } else {
        //     session()->setFlashdata('pesan', "
        //                 swal({
        //                     title: 'Oops..',
        //                     text: 'Current Password Salah',
        //                     type: 'error',
        //                     padding: '2em'
        //                     });
        //                 ");
        //     return redirect()->to('siswa/profile');
        // }
    }
    // END::PROFILE

    public function modul()
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        if(!empty($this->dataSiswa)){
            if($this->dataSiswa->status == "B" ){
                session()->setFlashdata('pesan', "
                    swal({
                        title: 'Lengkapi Data Diri',
                        text: 'Mohon segera lengkapi data diri anda untuk dapat mengakses seluruh layanan Kelasbrevet.',
                        type: 'info',
                        padding: '2em'
                    }); 
                ");
            	throw new \CodeIgniter\Router\Exceptions\RedirectException('siswa/profile');
            }
        } 

        //echo session()->get('email');
        $data['affiliate'] =  $this->affiliateModel->where('user_id', session()->get('id'))->first();
        $data['siswa'] = $this->SiswaModel->asObject()->find(session()->get('id'));
        $data['paket'] = $this->PaketModel->getAll();
        //print_r($data['modul']);

        $siswa = $this->TransaksiModel->join('detail_transaksi', 'transaksi.idtransaksi=detail_transaksi.idtransaksi')->where('transaksi.status', 'S')->where('idsiswa', session('id'))->groupBy('detail_transaksi.idmapel')->get()->getResultObject();

        $data['modul'] = array();
        foreach ($siswa as  $r) {
            $modul = $this->MapelModel->getAllIdSiswa($r->idmapel);

            foreach ($modul as $m) {
                $data['modul'][] = $m;
            }
        }

        //return $data['siswa']->kelas;
        return view('siswa/modul/list', $data);
    }

    // START::MATERI
    public function materi($idmapel, $idkelas)
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        if(!empty($this->dataSiswa)){
            if($this->dataSiswa->status == "B" ){
                 session()->setFlashdata('pesan', "
                    swal({
                        title: 'Lengkapi Data Diri',
                        text: 'Mohon segera lengkapi data diri anda untuk dapat mengakses seluruh layanan Kelasbrevet.',
                        type: 'info',
                        padding: '2em'
                    }); 
                ");
            	throw new \CodeIgniter\Router\Exceptions\RedirectException('siswa/profile');
            }
        }
        $data['idmapel'] = $idmapel;
        $data['idkelas'] = $idkelas;
        $mapel = decrypt_url($idmapel);
        $kelas = decrypt_url($idkelas);

        $data['siswa'] = $this->SiswaModel->asObject()->find(session()->get('id'));
        //$data['materi'] = $this->MateriModel->getAllbyMapelKelas($mapel, $data['siswa']->kelas);



        $siswa = $this->SiswaModel
            ->where('email', session()->get('email'))
            ->get()->getResultObject();


        $data['materi'] = $this->MateriModel->getAllByMapelKelas($mapel, $kelas);

        return view('siswa/materi/list', $data);
    }
    public function lihat_materi($kode, $idmapel, $idkelas)
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        if(!empty($this->dataSiswa)){
            if($this->dataSiswa->status == "B" ){
                 session()->setFlashdata('pesan', "
                    swal({
                        title: 'Lengkapi Data Diri',
                        text: 'Mohon segera lengkapi data diri anda untuk dapat mengakses seluruh layanan Kelasbrevet.',
                        type: 'info',
                        padding: '2em'
                    }); 
                ");
            	throw new \CodeIgniter\Router\Exceptions\RedirectException('siswa/profile');
            }
        }
        
        
        $data['materiAll'] = $this->MateriModel->getAllByMapelKelas(decrypt_url($idmapel), decrypt_url($idkelas));
        $data['siswa'] = $this->SiswaModel->asObject()->find(session()->get('id'));
        $data['materi'] = $this->MateriModel->getBykodeMateri(decrypt_url($kode));
        $data['file'] = $this->FileModel->getMateriWithFile(decrypt_url($idmapel), decrypt_url($idkelas));

        $materi_siswa = $this->Materi_siswaModel
            ->join('siswa', 'materi_siswa.siswa=siswa.id_siswa')
            ->where('materi_siswa.materi', decrypt_url($kode))
            ->where('siswa.email', session()->get('email'))
            ->get()->getRowObject();

        if ($materi_siswa) {
            $this->Materi_siswaModel->where('id_materi_siswa', $materi_siswa->id_materi_siswa)->delete();
        }

        /*$this->Materi_siswaModel
            ->where('materi', decrypt_url($kode))
            ->where('siswa', session()->get('id'))
            ->delete();*/
            
        $cekDataMateri = $this->MateriModel->where('kode_materi', decrypt_url($kode))->get()->getRowObject();
        $data['link'] = '';
        if($cekDataMateri){
            $data['link'] = base_url('guru/lihat_materi/'.encrypt_url($cekDataMateri->id_materi).'/'.$idmapel.'/'.$idkelas);
        }
            

        return view('siswa/materi/lihat-materi', $data);
    }
    public function chat_materi()
    {
        // 1. Cek Role (Sudah Benar)
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
    
        // 2. Cek Data Siswa (Sudah Benar)
        if (!empty($this->dataSiswa)) {
            if ($this->dataSiswa->status == "B") {
                 session()->setFlashdata('pesan', "
                    swal({
                        title: 'Lengkapi Data Diri',
                        text: 'Mohon segera lengkapi data diri anda untuk dapat mengakses seluruh layanan Kelasbrevet.',
                        type: 'info',
                        padding: '2em'
                    }); 
                ");
                throw new \CodeIgniter\Router\Exceptions\RedirectException('siswa/profile');
            }
        }
    
        if ($this->request->isAJAX()) {
            // Gunakan getPost() jika AJAX kamu menggunakan type: POST
            $kode_materi = $this->request->getVar('kode_materi');
            $chat_materi = (string) $this->request->getVar('chat_materi'); // Force to string
            $link        = $this->request->getVar('link');
    
            // Ambil data user untuk gambar/avatar
            $user = $this->SiswaModel->asObject()->find(session('id'));
    
            // PERBAIKAN DI SINI: tambahkan () setelah get
            $dataMateri = $this->MateriModel->where('kode_materi', $kode_materi)->get()->getRowObject();
    
            if ($dataMateri) {
                // Mengirim notifikasi ke Guru
                send_notif(
                    $dataMateri->guru, 
                    'Pesan baru', 
                    mb_strimwidth($chat_materi, 0, 20, "..."), 
                    $link
                );
            }
    
            $data = [
                'materi'       => $kode_materi,
                'nama'         => session()->get('nama'),
                'gambar'       => $user->avatar ?? 'default.png', // Fallback jika avatar kosong
                'email'        => session()->get('email'),
                'text'         => $chat_materi,
                'date_created' => time()
            ];
    
            // Simpan ke database
            if ($this->ChatmateriModel->save($data)) {
                return $this->response->setJSON(['status' => 'success']);
            } else {
                return $this->response->setJSON(['status' => 'error'], 500);
            }
        }
    }
    public function get_chat_materi()
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        if(!empty($this->dataSiswa)){
            if($this->dataSiswa->status == "B" ){
                 session()->setFlashdata('pesan', "
                    swal({
                        title: 'Lengkapi Data Diri',
                        text: 'Mohon segera lengkapi data diri anda untuk dapat mengakses seluruh layanan Kelasbrevet.',
                        type: 'info',
                        padding: '2em'
                    }); 
                ");
            	throw new \CodeIgniter\Router\Exceptions\RedirectException('siswa/profile');
            }
        }
        if ($this->request->isAJAX()) {
            $kode_materi = $this->request->getVar('kode_materi');
            $chat_materi = $this->ChatmateriModel->getAllByKodeMateri($kode_materi);

            foreach ($chat_materi as $chat) {
                if ($chat->email == session()->get('email')) {
                    echo '
                    <div class="media">
                        <div class="avatar avatar-sm avatar-indicators avatar-online">
                            <img alt="avatar" src="' . base_url('assets/app-assets/user/') . '/' . $chat->gambar . '" class="rounded-circle" />
                        </div>
                        <div class="media-body ml-2">
                            <h5 class="media-heading"><span class="media-title"> ' . $chat->nama . ' <span class="badge badge-primary">You</span></h5>
                            <p class="media-text" style="white-space: pre-line; margin-top: -20px;">
                                ' . $chat->text . '
                            </p>
                            <hr>
                        </div>
                    </div>
                ';
                } else {
                    echo '
                    <div class="media">
                        <div class="avatar avatar-sm avatar-indicators avatar-online">
                            <img alt="avatar" src="' . base_url('assets/app-assets/user/') . '/' . $chat->gambar . '" class="rounded-circle" />
                        </div>
                        <div class="media-body ml-2">
                            <h5 class="media-heading"><span class="media-title"> ' . $chat->nama . '</h5>
                            <p class="media-text" style="white-space: pre-line; margin-top: -20px;">
                                ' . $chat->text . '
                            </p>
                            <hr>
                        </div>
                    </div>
                ';
                }
            }
            exit;
        }
    }
    // END::MATERI

    // START::TUGAS
    public function tugas()
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        if(!empty($this->dataSiswa)){
            if($this->dataSiswa->status == "B" ){
                 session()->setFlashdata('pesan', "
                    swal({
                        title: 'Lengkapi Data Diri',
                        text: 'Mohon segera lengkapi data diri anda untuk dapat mengakses seluruh layanan Kelasbrevet.',
                        type: 'info',
                        padding: '2em'
                    }); 
                ");
            	throw new \CodeIgniter\Router\Exceptions\RedirectException('siswa/profile');
            }
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_ujian'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_sertifikat'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_transaksi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['siswa'] = $this->SiswaModel->asObject()->find(session()->get('id'));
        //$data['tugas'] = $this->TugasModel->getAllByKelas($data['siswa']->kelas);

        $siswa = $this->SiswaModel->where('email', session()->get('email'))->get()->getResultObject();

        $data['tugas'] = array();
        foreach ($siswa as  $r) {
            $tugas = $this->TugasModel->getAllByKelas($r->kelas);

            foreach ($tugas as $t) {
                $data['tugas'][] = $t;
            }
        }


        return view('siswa/tugas/list', $data);
    }
    public function lihat_tugas($kode)
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        if(!empty($this->dataSiswa)){
            if($this->dataSiswa->status == "B" ){
                 session()->setFlashdata('pesan', "
                    swal({
                        title: 'Lengkapi Data Diri',
                        text: 'Mohon segera lengkapi data diri anda untuk dapat mengakses seluruh layanan Kelasbrevet.',
                        type: 'info',
                        padding: '2em'
                    }); 
                ");
            	throw new \CodeIgniter\Router\Exceptions\RedirectException('siswa/profile');
            }
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_ujian'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_sertifikat'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_transaksi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];

        $data['siswa'] = $this->SiswaModel->asObject()->find(session()->get('id'));
        $data['tugas'] = $this->TugasModel->getBykodeTugas(decrypt_url($kode));
        $data['guru'] = $this->GuruModel->asObject()->find($data['tugas']->guru);
        $data['tugas_siswa'] = $this->Tugas_siswaModel->getByKodeTugasAndSiswa(decrypt_url($kode), $data['siswa']->email);



        $data['file'] = $this->FileModel->getAllByKode(decrypt_url($kode));


        // $tugas_siswa = $this->Tugas_siswaModel
        //     ->join('siswa', 'materi_siswa.siswa=siswa.id_siswa')
        //     ->where('tugas_siswa.tugas', decrypt_url($kode))
        //     ->where('siswa.email', session()->get('email'))
        //     ->get()->getRowObject();

        // if($tugas_siswa){    
        //     $this->Tugas_siswaModel->where('id_tugas_siswa', $tugas_siswa->id_tugas_siswa)->delete();
        // }

        //print_r($data['tugas_siswa']);
        return view('siswa/tugas/lihat-tugas', $data);
    }
    public function get_chat_tugas()
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        if(!empty($this->dataSiswa)){
            if($this->dataSiswa->status == "B" ){
                 session()->setFlashdata('pesan', "
                    swal({
                        title: 'Lengkapi Data Diri',
                        text: 'Mohon segera lengkapi data diri anda untuk dapat mengakses seluruh layanan Kelasbrevet.',
                        type: 'info',
                        padding: '2em'
                    }); 
                ");
            	throw new \CodeIgniter\Router\Exceptions\RedirectException('siswa/profile');
            }
        }
        if ($this->request->isAJAX()) {
            $kode_tugas = $this->request->getVar('kode_tugas');
            $chat_tugas = $this->ChattugasModel->getAllByKodeTugas($kode_tugas);

            foreach ($chat_tugas as $chat) {
                if ($chat->email == session()->get('email')) {
                    echo '
                    <div class="media">
                        <div class="avatar avatar-sm avatar-indicators avatar-online">
                            <img alt="avatar" src="' . base_url('assets/app-assets/user/') . '/' . $chat->gambar . '" class="rounded-circle" />
                        </div>
                        <div class="media-body ml-2">
                            <h5 class="media-heading"><span class="media-title"> ' . $chat->nama . ' <span class="badge badge-primary">You</span></h5>
                            <p class="media-text" style="white-space: pre-line; margin-top: -20px;">
                                ' . $chat->text . '
                            </p>
                            <hr>
                        </div>
                    </div>
                ';
                } else {
                    echo '
                    <div class="media">
                        <div class="avatar avatar-sm avatar-indicators avatar-online">
                            <img alt="avatar" src="' . base_url('assets/app-assets/user/') . '/' . $chat->gambar . '" class="rounded-circle" />
                        </div>
                        <div class="media-body ml-2">
                            <h5 class="media-heading"><span class="media-title"> ' . $chat->nama . '</h5>
                            <p class="media-text" style="white-space: pre-line; margin-top: -20px;">
                                ' . $chat->text . '
                            </p>
                            <hr>
                        </div>
                    </div>
                ';
                }
            }
            exit;
        }
    }
    public function chat_tugas()
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        if(!empty($this->dataSiswa)){
            if($this->dataSiswa->status == "B" ){
                 session()->setFlashdata('pesan', "
                    swal({
                        title: 'Lengkapi Data Diri',
                        text: 'Mohon segera lengkapi data diri anda untuk dapat mengakses seluruh layanan Kelasbrevet.',
                        type: 'info',
                        padding: '2em'
                    }); 
                ");
            	throw new \CodeIgniter\Router\Exceptions\RedirectException('siswa/profile');
            }
        }
        if ($this->request->isAJAX()) {
            $kode_tugas = $this->request->getVar('kode_tugas');
            $chat_tugas = $this->request->getVar('chat_tugas');
            $user = $this->SiswaModel->asObject()->find(session()->get('id'));

            $data = [
                'tugas' => $kode_tugas,
                'nama' => $user->nama_siswa,
                'email' => $user->email,
                'gambar' => $user->avatar,
                'text' => $chat_tugas,
                'date_created' => time()
            ];

            $this->ChattugasModel->save($data);
        }
    }
    public function kumpulkan()
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        if(!empty($this->dataSiswa)){
            if($this->dataSiswa->status == "B" ){
                 session()->setFlashdata('pesan', "
                    swal({
                        title: 'Lengkapi Data Diri',
                        text: 'Mohon segera lengkapi data diri anda untuk dapat mengakses seluruh layanan Kelasbrevet.',
                        type: 'info',
                        padding: '2em'
                    }); 
                ");
            	throw new \CodeIgniter\Router\Exceptions\RedirectException('siswa/profile');
            }
        }
        $kode_tugas = $this->request->getVar('kode_tugas');
        $tugas = $this->TugasModel->getBykodeTugas($kode_tugas);
        $siswa = $this->SiswaModel->asObject()->find(session()->get('id'));
        //$tugas_siswa = $this->Tugas_siswaModel->getByKodeTugasAndSiswa($kode_tugas, $siswa->id_siswa);
        $tugas_siswa = $this->Tugas_siswaModel->getByKodeTugasAndSiswa($kode_tugas, $siswa->email);

        $kode_file_siswa = null;
        if ($tugas_siswa->file_siswa == null) {
            $kode_file_siswa = random_string('alnum', 8);
        } else {
            $kode_file_siswa = $tugas_siswa->file_siswa;
        }
        // Cek Tugas Telat
        $telat = '';
        $waktu =  date('Y-m-d H:i', time());
        $batas = date($tugas->due_date);
        if (strtotime($waktu) > strtotime($batas)) {
            // echo "<b>Batas waktu sudah berakhir</b><br>";
            $telat = 1;
        } else {
            // echo "<b>Masih dalam jangka waktu</b><br>";
            $telat = 0;
        }


        // CEK APAKAH ADA FILE U=YANG DIPILIH
        $file_siswa = $this->request->getFileMultiple('file_siswa');
        if ($file_siswa[0]->getError() != 4) {
            $data_file_siswa = [];

            // FUNGSI UPLOAD FILE
            foreach ($file_siswa as $file) {
                // Generate nama file Random
                $nama_file = str_replace(' ', '_', $file->getName());
                // Upload Gambar
                $file->move('assets/app-assets/file', $nama_file);

                array_push($data_file_siswa, [
                    'kode_file' => $kode_file_siswa,
                    'nama_file' => $nama_file
                ]);
            }

            $this->FileModel->insertBatch($data_file_siswa);
        }


        //cari siswa dengan email dan tugas

        $tugas_siswa = $this->Tugas_siswaModel->getByKodeTugasAndSiswa($kode_tugas, $siswa->email);


        $this->Tugas_siswaModel
            ->set('text_siswa', $this->request->getVar('text_siswa'))
            ->set('file_siswa', $kode_file_siswa)
            ->set('date_send', time())
            ->set('is_telat', $telat)
            ->where('tugas', $kode_tugas)
            //->where('siswa', $siswa->id_siswa)
            ->where('siswa', $tugas_siswa->id_siswa)
            ->update();

        session()->setFlashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Tugas telah dikerjakan',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
        return redirect()->to('siswa/lihat_tugas/' . encrypt_url($tugas->kode_tugas));
    }
    // END::TUGAS

    // START::UJIAN

    // START = UJIAN PG
    public function ujian()
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        if(!empty($this->dataSiswa)){
            if($this->dataSiswa->status == "B" ){
                 session()->setFlashdata('pesan', "
                    swal({
                        title: 'Lengkapi Data Diri',
                        text: 'Mohon segera lengkapi data diri anda untuk dapat mengakses seluruh layanan Kelasbrevet.',
                        type: 'info',
                        padding: '2em'
                    }); 
                ");
            	throw new \CodeIgniter\Router\Exceptions\RedirectException('siswa/profile');
            }
        }


        $data['siswa'] = $this->SiswaModel->asObject()->find(session()->get('id'));
        //$data['ujian'] = $this->UjianModel->getAllBykelas($data['siswa']->kelas);

        $siswa = $this->SiswaModel->where('email', session()->get('email'))->get()->getResultObject();
        $data['ujian'] = array();
        foreach ($siswa as  $r) {
            $tugas = $this->UjianModel->getAllByKelas($r->kelas, $r->id_siswa);

            foreach ($tugas as $t) {
                $data['ujian'][] = $t;
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

        return view('siswa/ujian/list', $data);
    }
    
    public function ujiantes()
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        if(!empty($this->dataSiswa)){
            if($this->dataSiswa->status == "B" ){
                 session()->setFlashdata('pesan', "
                    swal({
                        title: 'Lengkapi Data Diri',
                        text: 'Mohon segera lengkapi data diri anda untuk dapat mengakses seluruh layanan Kelasbrevet.',
                        type: 'info',
                        padding: '2em'
                    }); 
                ");
            	throw new \CodeIgniter\Router\Exceptions\RedirectException('siswa/profile');
            }
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_sertifikat'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_transaksi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];


        $data['siswa'] = $this->SiswaModel->asObject()->find(session()->get('id'));
        //$data['ujian'] = $this->UjianModel->getAllBykelas($data['siswa']->kelas);

        $siswa = $this->SiswaModel->where('email', session()->get('email'))->get()->getResultObject();
        $data['ujian'] = array();
        foreach ($siswa as  $r) {
            $tugas = $this->UjianModel->getAllByKelas($r->kelas, $r->id_siswa);

            foreach ($tugas as $t) {
                $data['ujian'][] = $t;
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

        return view('siswa/ujian/listtes', $data);
    }
    public function simpan_review()
    {
        $reviewModel = $this->ReviewUjianModel;
    
        $kode_ujian = $this->request->getPost('kode_ujian');
        $idSiswa = session()->get('id');
        $rating = $this->request->getPost('rating');
        $komentar = $this->request->getPost('komentar');
        $link = $this->request->getPost('link');
    
        // Cek apakah siswa sudah pernah memberikan review untuk ujian ini
        $cek = $reviewModel->where(['kode_ujian' => $kode_ujian, 'id_siswa' => $idSiswa])->first();
        if ($cek) {
            // Update review lama
            $reviewModel->update($cek['id_review'], [
                'rating' => $rating,
                'komentar' => $komentar,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            session()->setFlashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'Penilaian Anda telah diperbarui.',
                    type: 'success',
                    padding: '2em'
                });
            ");
        } else {
            // Simpan review baru
            $reviewModel->insert([
                'kode_ujian' => $kode_ujian,
                'id_siswa' => $idSiswa,
                'rating' => $rating,
                'komentar' => $komentar,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            session()->setFlashdata('pesan', "
                swal({
                    title: 'Berhasil!',
                    text: 'Terima kasih, penilaian Anda telah disimpan.',
                    type: 'success',
                    padding: '2em'
                });
            ");
        }
        return redirect()->to($link);
    
    }


    public function lihat_pg($kode_ujian, $id_siswa, $id_ujian, $status)
    {

        //return session()->get('id');

        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        if(!empty($this->dataSiswa)){
            if($this->dataSiswa->status == "B" ){
                 session()->setFlashdata('pesan', "
                    swal({
                        title: 'Lengkapi Data Diri',
                        text: 'Mohon segera lengkapi data diri anda untuk dapat mengakses seluruh layanan Kelasbrevet.',
                        type: 'info',
                        padding: '2em'
                    }); 
                ");
            	throw new \CodeIgniter\Router\Exceptions\RedirectException('siswa/profile');
            }
        }

        //ujian berjalan
        $ujianDetail = $this->UjianDetailModel
            ->where('kode_ujian', decrypt_url($kode_ujian))
            ->get()->getResultObject();
        
        $total = 0;
        foreach($ujianDetail as $dataRows){
            $total++;
        }
        $totalMenit = $total * 3;
        $dat_u = $this->UjianModel->where('id_ujian', decrypt_url($id_ujian))->where('id_siswa', decrypt_url($id_siswa))->where('status', 'B')->get()->getResultObject();
        if(!empty($dat_u)){
            $dataUjian = $this->UjianModel->where('id_ujian', decrypt_url($id_ujian))->get()->getRowObject();
            $kuota = $dataUjian->kuota - 1;
             $this->UjianModel
                ->set('start_ujian', date('Y-m-d H:i'))
                ->set('end_ujian', date('Y-m-d H:i', strtotime("+ $totalMenit minutes")))
                ->set('status', 'U')
                ->set('kuota', $kuota)
                ->where('id_ujian', decrypt_url($id_ujian))
                ->update();
                
            $data_ujian_model = $this->UjianSiswaModel->where('ujian', decrypt_url($kode_ujian))->where('siswa', decrypt_url($id_siswa))->get()->getResultObject();
            if(!empty($data_ujian_model)){
                //menambah ujian ke tabel ujian siswa
                $data_ujian_siswa = $this->UjianSiswaModel->where('ujian', decrypt_url($kode_ujian))->where('siswa', decrypt_url($id_siswa))->get()->getResultObject();
                foreach ($data_ujian_siswa as $rows) {
                    $data_detail_siswa = [
                        'jawaban'       => null,
                        'benar'         => null,
                        'jam'           => null,
                        'status'        => null,
                    ];
                    $this->UjianSiswaModel->set($data_detail_siswa)->where('id_ujian_siswa', $rows->id_ujian_siswa)->update();
                }
            }else{
                //menambah ujian ke tabel ujian siswa
                $ujian_detail = $this->UjianDetailModel->getAllBykodeUjian(decrypt_url($kode_ujian));
                $data_ujian_siswa = [];
                foreach ($ujian_detail as $uj) {
                    array_push($data_ujian_siswa, [
                        'ujian_id' => $uj->id_detail_ujian,
                        'ujian' => $uj->kode_ujian,
                        'siswa' => decrypt_url($id_siswa),
                    ]);
                }
                $this->UjianSiswaModel->insertBatch($data_ujian_siswa);
            }

        }
        
        $data['siswa'] = $this->SiswaModel->asObject()->find(session()->get('id'));
        $data['ujian'] = $this->UjianModel->getBykode(decrypt_url($kode_ujian), decrypt_url($id_ujian));
        $data['detail_ujian'] = $this->UjianDetailModel->getAllBykodeUjian(decrypt_url($kode_ujian));
        $data['ujian_siswa'] = $this->UjianSiswaModel
            ->where('ujian', decrypt_url($kode_ujian))
            ->where('siswa', decrypt_url($id_siswa))
            ->get()->getResultObject();

        $data['jawaban_benar'] = $this->UjianSiswaModel->benar(decrypt_url($kode_ujian), decrypt_url($id_siswa), 1);
        $data['jawaban_salah'] = $this->UjianSiswaModel->salah(decrypt_url($kode_ujian), decrypt_url($id_siswa), 0);
        $data['tidak_dijawab'] = $this->UjianSiswaModel->belum_terjawab(decrypt_url($kode_ujian), decrypt_url($id_siswa), null);
        $data['sedang'] =  true;
        return view('siswa/ujian/pg-lihat', $data);
    }
    public function kirim_ujian()
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        if(!empty($this->dataSiswa)){
            if($this->dataSiswa->status == "B" ){
                 session()->setFlashdata('pesan', "
                    swal({
                        title: 'Lengkapi Data Diri',
                        text: 'Mohon segera lengkapi data diri anda untuk dapat mengakses seluruh layanan Kelasbrevet.',
                        type: 'info',
                        padding: '2em'
                    }); 
                ");
            	throw new \CodeIgniter\Router\Exceptions\RedirectException('siswa/profile');
            }
        }
        if ($this->request->isAJAX()) {
            $id_siswa = $this->request->getVar('id_siswa');
            $id_detail_ujian = $this->request->getVar('id_detail_ujian');
            $jawaban = $this->request->getVar('jawaban');
            $jam = $this->request->getVar('jam');

            $du = $this->UjianDetailModel->getAllByiddetailujian($id_detail_ujian);

            $dataJawaban = $this->UjianSiswaModel->getByUjianSiswa($id_detail_ujian, $id_siswa);
            if (!empty($dataJawaban)) {
                if ($jawaban == $du->jawaban) {
                    $this->UjianSiswaModel
                        ->set('jawaban', $jawaban)
                        ->set('benar', 1)
                        ->set('jam', $jam)
                        ->where('ujian_id', $id_detail_ujian)
                        ->where('siswa', $id_siswa)
                        ->update();
                } else {
                    if ($jawaban == NULL) {
                        $this->UjianSiswaModel
                            ->set('jawaban', $jawaban)
                            ->set('benar', 2)
                            ->set('jam', $jam)
                            ->where('ujian_id', $id_detail_ujian)
                            ->where('siswa', $id_siswa)
                            ->update();
                    } else {
                        $this->UjianSiswaModel
                            ->set('jawaban', $jawaban)
                            ->set('benar', 0)
                            ->set('jam', $jam)
                            ->where('ujian_id', $id_detail_ujian)
                            ->where('siswa', $id_siswa)
                            ->update();
                    }
                }
            } else {
                if ($jawaban == $du->jawaban) {
                    $this->UjianSiswaModel
                        ->set('jawaban', $jawaban)
                        ->set('benar', 1)
                        ->where('ujian_id', $id_detail_ujian)
                        ->where('siswa', $id_siswa)
                        ->update();
                } else {
                    if ($jawaban == NULL) {
                        $this->UjianSiswaModel
                            ->set('jawaban', $jawaban)
                            ->set('benar', 2)
                            ->where('ujian_id', $id_detail_ujian)
                            ->where('siswa', $id_siswa)
                            ->update();
                    } else {
                        $this->UjianSiswaModel
                            ->set('jawaban', $jawaban)
                            ->set('benar', 0)
                            ->where('ujian_id', $id_detail_ujian)
                            ->where('siswa', $id_siswa)
                            ->update();
                    }
                }
            }
            echo true;
        }
    }

    public function kirim_ujian_selesai()
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        if(!empty($this->dataSiswa)){
            if($this->dataSiswa->status == "B" ){
                 session()->setFlashdata('pesan', "
                    swal({
                        title: 'Lengkapi Data Diri',
                        text: 'Mohon segera lengkapi data diri anda untuk dapat mengakses seluruh layanan Kelasbrevet.',
                        type: 'info',
                        padding: '2em'
                    }); 
                ");
            	throw new \CodeIgniter\Router\Exceptions\RedirectException('siswa/profile');
            }
        }
        $kode_ujian = $this->request->getVar('ujian');
        $id_ujian = $this->request->getVar('id_ujian');
        $id_siswa   = session('id');

        $this->UjianSiswaModel
            ->set('status', 'selesai')
            ->set('date_send', time())
            ->where('ujian', $kode_ujian)
            ->where('siswa', $id_siswa)
            ->update();
            
         $this->UjianModel
            ->set('status', 'S')
            ->set('nilai', 0)
            ->where('kode_ujian', $kode_ujian)
            ->where('id_ujian', $id_ujian)
            ->update();
       
            
            
        $siswa = $this->SiswaModel->where('email', session()->get('email'))->get()->getResultObject();

        $data['ujian'] = array();
        foreach ($siswa as  $r) {
            $ujian = $this->UjianMasterModel->getAllUntukNilaiUjian($r->kelas, $r->id_siswa, $kode_ujian);

            foreach ($ujian as $u) {
                $data['ujian'][] = $u;
            }
        }



        for ($i = 0; $i < count($data['ujian']); $i++) {
            $ujian_detail = $this->UjianDetailModel->getAllByKodeUjianJumlah($data['ujian'][$i]->kode_ujian);
            $nilai = round($data['ujian'][$i]->benar / count($ujian_detail) * 100);
             $this->UjianModel
            ->set('status', 'S')
            ->set('nilai', $nilai)
            ->where('kode_ujian', $kode_ujian)
            ->where('id_ujian', $id_ujian)
            ->update();
            
        }
            
            

        session()->setFlashdata('pesan', "
            swal({
                title: 'Berhasil!',
                text: 'Ujian telah dikerjakan',
                type: 'success',
                padding: '2em'
            });
        ");
        return redirect()->to('siswa/ujian');
    }
    
    public function remedial($id, $kode, $status)
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        $id_ujian = decrypt_url($id);
        $kode_ujian = decrypt_url($kode);
        $idsiswa = session('id');
        $dataUjian = $this->UjianModel->where('id_ujian',$id_ujian)->get()->getRowObject();
        $kuota = $dataUjian->kuota - 1;
        
        //ujian berjalan
        $data['ujian_siswa'] = $this->UjianSiswaModel
            ->where('ujian_siswa.ujian', $kode_ujian)
            ->where('ujian_siswa.siswa', $idsiswa)
            ->get()->getResultObject();
        
        $total = 0;
        foreach($data['ujian_siswa'] as $dataRows){
            $total++;
        }
        $totalMenit = $total * 3;
        $this->UjianModel
            ->set('date_created', time())
            ->set('start_ujian', date('Y-m-d H:i'))
            ->set('end_ujian', date('Y-m-d H:i', strtotime("+ $totalMenit minutes")))
            ->set('status', 'U')
            ->set('nilai', null)
            ->set('kuota', $kuota)
            ->where('id_ujian', $id_ujian)
            ->update();
        

        //menambah ujian ke tabel ujian siswa
        $data_ujian_siswa = $this->UjianSiswaModel->where('ujian', $kode_ujian)->where('siswa', $idsiswa)->get()->getResultObject();
        foreach ($data_ujian_siswa as $rows) {
            $data_detail_siswa = [
                'jawaban'       => null,
                'benar'         => null,
                'jam'           => null,
                'status'        => null,
            ];
            $this->UjianSiswaModel->set($data_detail_siswa)->where('id_ujian_siswa', $rows->id_ujian_siswa)->update();
        }
        session()->setFlashdata('pesan', "
            swal({
                title: 'Berhasil!',
                text: 'Ujian ulang telah diaktifkan dan waktu ujian telah berjalan',
                type: 'success',
                padding: '2em'
            });
        ");
        // return redirect()->to('siswa/ujian');
        return redirect()->to('siswa/lihat_pg/'.encrypt_url($kode_ujian).'/'.encrypt_url($idsiswa).'/'.encrypt_url($id_ujian).'/'.$status);
    }
    // END = UJIAN PG

    // START = UJIAN ESSAY
    public function lihat_essay($kode_ujian, $id_siswa)
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        if(!empty($this->dataSiswa)){
            if($this->dataSiswa->status == "B" ){
                 session()->setFlashdata('pesan', "
                    swal({
                        title: 'Lengkapi Data Diri',
                        text: 'Mohon segera lengkapi data diri anda untuk dapat mengakses seluruh layanan Kelasbrevet.',
                        type: 'info',
                        padding: '2em'
                    }); 
                ");
            	throw new \CodeIgniter\Router\Exceptions\RedirectException('siswa/profile');
            }
        }
        // MENU DATA
        $data['dashboard'] = [
            'menu' => '',
            'expanded' => 'false'
        ];
        $data['menu_materi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_tugas'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_ujian'] = [
            'menu' => 'active',
            'expanded' => 'true',
        ];
        $data['menu_sertifikat'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_profile'] = [
            'menu' => '',
            'expanded' => 'false',
        ];
        $data['menu_transaksi'] = [
            'menu' => '',
            'expanded' => 'false',
        ];


        $data['siswa'] = $this->SiswaModel->asObject()->find(session()->get('id'));
        $data['ujian'] = $this->UjianModel->getBykode(decrypt_url($kode_ujian));
        $data['essay_detail'] = $this->EssaydetailModel->getAllBykodeUjian(decrypt_url($kode_ujian));
        $data['essay_siswa'] = $this->EssaysiswaModel
            ->where('ujian', decrypt_url($kode_ujian))
            ->where('siswa', decrypt_url($id_siswa))
            ->get()->getResultObject();

        return view('siswa/ujian/essay-lihat', $data);
    }
    public function kirim_essay()
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        if(!empty($this->dataSiswa)){
            if($this->dataSiswa->status == "B" ){
                 session()->setFlashdata('pesan', "
                    swal({
                        title: 'Lengkapi Data Diri',
                        text: 'Mohon segera lengkapi data diri anda untuk dapat mengakses seluruh layanan Kelasbrevet.',
                        type: 'info',
                        padding: '2em'
                    }); 
                ");
            	throw new \CodeIgniter\Router\Exceptions\RedirectException('siswa/profile');
            }
        }
        $kode_ujian = $this->request->getVar('ujian');
        $siswa = $this->request->getVar('siswa');

        $essay_detail = $this->EssaydetailModel->getAllBykodeUjian($kode_ujian);

        foreach ($essay_detail as $du) {
            if ($this->request->getVar("$du->id_essay_detail")) {
                $this->EssaysiswaModel
                    ->set('jawaban', $this->request->getVar("$du->id_essay_detail"))
                    ->set('sudah_dikerjakan', 1)
                    ->where('essay_id', $du->id_essay_detail)
                    ->where('siswa', $siswa)
                    ->update();
            } else {
                $this->EssaysiswaModel
                    ->set('jawaban', NULL)
                    ->set('sudah_dikerjakan', 1)
                    ->where('essay_id', $du->id_essay_detail)
                    ->where('siswa', $siswa)
                    ->update();
            }
        }

        session()->setFlashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Ujian telah dikerjakan',
                            type: 'success',
                            padding: '2em'
                            });
                        ");
        return redirect()->to('siswa/lihat_essay/' . encrypt_url($kode_ujian) . '/' . encrypt_url($siswa));
    }
    // END = UJIAN ESSAY

    // END::UJIAN

    public function sertifikat()
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        if(!empty($this->dataSiswa)){
            if($this->dataSiswa->status == "B" ){
                 session()->setFlashdata('pesan', "
                    swal({
                        title: 'Lengkapi Data Diri',
                        text: 'Mohon segera lengkapi data diri anda untuk dapat mengakses seluruh layanan Kelasbrevet.',
                        type: 'info',
                        padding: '2em'
                    }); 
                ");
            	throw new \CodeIgniter\Router\Exceptions\RedirectException('siswa/profile');
            }
        }



        $data['siswa'] = $this->SiswaModel->asObject()->find(session()->get('id'));

        $siswa = $this->SiswaModel->where('email', session()->get('email'))->get()->getResultObject();

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

      

        return view('siswa/sertifikat/list', $data);
    }

    public function lihat_sertifikat($kode_ujian, $id_ujian, $jenis = "")
    {
        $kode_ujian = decrypt_url($kode_ujian);
        $id_ujian = decrypt_url($id_ujian);
        $hasil = $this->UjianModel->getBykode($kode_ujian, $id_ujian);

        new Pdf();


        $pdf = new \setasign\Fpdi\Fpdi();
        $pdf->SetAutoPageBreak(false, 5);



        // PAGE 1
        $pdf->AddPage('L');
        $pdf->SetCreator("kelasbrevet.com");
        $pdf->SetAuthor(strtoupper($hasil->nama_siswa)); // Ganti dengan nama Anda
        $pdf->SetTitle(strtoupper($hasil->nama_mapel)); // Ganti dengan judul dokumen
        $pdf->SetSubject('SERTIFIKAT ' .strtoupper($hasil->nama_mapel));
        $pdf->SetKeywords('KelasBrevet, Pajak, Brevet Pajak AB');
        if($jenis == ""){
            $pdf->Image('public/brevet-materi.jpg', 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());
        }else{
            $pdf->Image('public/brevet-materi-cap.jpg', 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());
        }
        $pdf->SetTextColor(51, 49, 49);
        
        //izin operasional
        $pdf->SetFont('Arial', '', '12');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(28, 5);
        $pdf->Cell(75, 4, "Izin Operasional Lembaga Kursus dan Pelatihan", 0, 1, 'L');
        $pdf->SetXY(28, 10);
        $pdf->Cell(75, 4, "500.16.7.2/0003/SPNF-LKP/IV.7/I/2025", 0, 1, 'L');
        
        
        //nis
        $bulanNomor = array('I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII');
        $pdf->SetFont('Arial', 'B', '16');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(28, 70);
        $pdf->Cell(75, 4,"Nomor : ".$hasil->id_ujian . '/' .'ALC-BREVET'. '/' . $bulanNomor[(int)date('m', strtotime($hasil->start_ujian)) - 1] . '/' . date('Y', strtotime($hasil->start_ujian)), 0, 1, 'L');

        //nama mapel
        $pdf->SetFont('Arial', 'B', '16');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(28, 85);
        $pdf->Cell(75, 4, strtoupper($hasil->nama_mapel), 0, 1, 'L');
        
        //nama
        $pdf->SetFont('Arial', 'B', '19.5');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(28, 126);
        $pdf->Cell(75, 4, strtoupper($hasil->nama_siswa), 0, 1, 'L');

        //nilai
        $pdf->SetFont('Arial', 'B', '18');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(28, 138);
        $pdf->Cell(75, 4,"NIP : ". $hasil->no_induk_siswa, 0, 1, 'L');
        
        //keterangan 1
        $pdf->SetFont('Arial', '', '14');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(28, 150);
        $pdf->Cell(75, 4,"Dinyatakan [LULUS] dengan nilai ". $hasil->nilai, 0, 1, 'L');
        
         //keterangan 3
        $bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        $pdf->SetFont('Arial', '', '14');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(28, 158);
        $pdf->Cell(75, 4,"Pada tanggal ". date('d', strtotime($hasil->start_ujian)) .  ' ' . $bulan[(int)date('m', strtotime($hasil->start_ujian)) - 1] . ' ' . date('Y', strtotime($hasil->start_ujian)), 0, 1, 'L');

        //keterangan 3
        $writer = new PngWriter();
        $qrCode = QrCode::create(base_url('detail/data').'/'.encrypt_url($hasil->id_ujian));
        // ->setEncoding(new Encoding('UTF-8'))
        // ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
        // ->setSize(300)
        // ->setMargin(10)
        // ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
        // ->setForegroundColor(new Color(0,0,0))
        // ->setBackgroundColor(new Color(255,255,255));
        
        
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
        
  
        
        $this->response->setContentType('application/pdf');


        $pdf->Output($hasil->nama_mapel.'-'.date('d-m-Y').'.pdf', 'I');
    }
    
    public function lihat_sertifikat_brevet($id, $jenis = "")
    {

        // $id_siswa = session()->get('id');
        $id_siswa = decrypt_url($id);
        $hasil = $this->UjianModel->getByIdsiswa($id_siswa);
        $siswa = $this->SiswaModel->where('id_siswa', $id_siswa)->get()->getRowObject();
        
        $siswaAsc = $this->UjianModel->getByIdsiswaAsc($id_siswa);
        $siswaDesc = $this->UjianModel->getByIdsiswaDesc($id_siswa);
        
        $nilai = 0;
        $total = 0;
        $nama = '';
        $tgl_sertifikat_start = '';
        $tgl_sertifikat = '';
        $no_induk_siswa= '';
        $id_ujian='';
        foreach($hasil as $rows){
            $nilai += $rows->nilai;
            $total++;
            $nama = $rows->nama_siswa;
            $tgl_sertifikat = $rows->end_ujian;
            $no_induk_siswa = $rows->no_induk_siswa;
            $id_ujian = $rows->id_ujian;
        }
        
            $tgl_sertifikat_start = $siswaAsc->start_ujian;
            $tgl_sertifikat_end = $siswaDesc->end_ujian; 
        $totalNilai = round($nilai/$total);

        new Pdf();


        $pdf = new \setasign\Fpdi\Fpdi();
        $pdf->SetAutoPageBreak(false, 5);


        // PAGE 1
        $pdf->AddPage('L');
        $pdf->SetCreator("kelasbrevet.com");
        $pdf->SetAuthor(strtoupper($nama)); // Ganti dengan nama Anda
        $pdf->SetTitle('BREVET PAJAK AB'); // Ganti dengan judul dokumen
        $pdf->SetSubject('SERTIFIKAT BREVET PAJAK AB');
        $pdf->SetKeywords('KelasBrevet, Pajak, Brevet Pajak AB');
        if($jenis == ""){
            $pdf->Image('public/brevet-ab.jpg', 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());
        }else{
            $pdf->Image('public/brevet-ab-cap.jpg', 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());
        }

        $pdf->SetTextColor(51, 49, 49);

        //nis
        $bulanNomor = array('I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII');
        $pdf->SetFont('Arial', 'B', '16');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(28, 70);
        $pdf->Cell(75, 4,"Nomor : ".$id_ujian . '/' .'ALC-BREVET-AB'. '/' . $bulanNomor[(int)date('m', strtotime($tgl_sertifikat_end)) - 1] . '/' . date('Y', strtotime($tgl_sertifikat_end)), 0, 1, 'L');

        //izin operasional
        $pdf->SetFont('Arial', '', '12');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(28, 5);
        $pdf->Cell(75, 4, "Izin Operasional Lembaga Kursus dan Pelatihan:", 0, 1, 'L');
        $pdf->SetXY(28, 10);
        $pdf->Cell(75, 4, "500.16.7.2/0003/SPNF-LKP/IV.7/I/2025", 0, 1, 'L');
        
        //nama
        $pdf->SetFont('Arial', 'B', '19.5');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(28, 126);
        $pdf->Cell(75, 4, strtoupper($nama), 0, 1, 'L');

        //nilai
        $pdf->SetFont('Arial', 'B', '18');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(28, 138);
        $pdf->Cell(75, 4,"NIP : ". $no_induk_siswa, 0, 1, 'L');
        
        //keterangan 1
        $pdf->SetFont('Arial', '', '14');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(28, 150);
        $pdf->Cell(75, 4,"Dinyatakan [LULUS] dengan nilai ". $totalNilai, 0, 1, 'L');
        
         //keterangan 2
        $pdf->SetFont('Arial', '', '14');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(28, 158);
        if($totalNilai < 60){
            $nilaiKeterangan = "D";
            $keterangan = 'Kurang';
        }elseif($totalNilai < 70){
            $nilaiKeterangan = "C";
            $keterangan = 'Cukup';
        }elseif($totalNilai < 80){
            $nilaiKeterangan = "B";
            $keterangan = 'Cukup Baik';
        }elseif($totalNilai < 90){
            $nilaiKeterangan = "A";
            $keterangan = 'Baik';
        }else{
             $nilaiKeterangan = "A+";
             $keterangan = 'Sangat Baik';
        }
        $pdf->Cell(75, 4,"Predikat kelulusan ". $nilaiKeterangan." ($keterangan)", 0, 1, 'L');
        
         //keterangan 3
        $bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        $pdf->SetFont('Arial', '', '14');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(28, 166);
        $pdf->Cell(75, 4,"Pada tanggal ". date('d', strtotime($tgl_sertifikat_end)) .  ' ' . $bulan[(int)date('m', strtotime($tgl_sertifikat_end)) - 1] . ' ' . date('Y', strtotime($tgl_sertifikat_end)), 0, 1, 'L');

        //keterangan 3
        $writer = new PngWriter();
        $qrCode = QrCode::create(base_url('detail/data_ab').'/'.encrypt_url($id_siswa));
        
        $logo = Logo::create('assets/img/logo-brevet.png')
        ->setResizeToWidth(100);
        
        
        $result = $writer->write($qrCode, $logo, null);
        $qrCodes = $result->getDataUri();
        $img = explode(',',$qrCodes,2)[1];
        $pic = 'data://text/plain;base64,'. $img;
        
        $pdf->Image($qrCodes, 30, 175, 30, 30, 'png');
        
        
        
        $pdf->AddPage('L');
        $pdf->Image('public/brevet-ab-2.jpg', 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());

        //nama 
        $pdf->SetFont('Arial', 'B', '14');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(55, 57);
        $pdf->Cell(75, 4,strtoupper($nama), 0, 1, 'L');
        $hasil = $this->UjianModel->getByIdsiswaSertifikat($id_siswa);
         //tabel materi 
         $page = 65;
         $total = 0;
         $no = 1;
         $pdf->SetFont('Arial','', '14');
        $pdf->SetXY(25, 63);
        
        $pdf->Cell(15,6,'No',1,0,'C',0);   // empty cell with left,top, and right borders
        $pdf->Cell(140,6,'Materi',1,0,'C',0);
        $pdf->Cell(75,6,'Nilai',1,0,'C',0);
        $pdf->Ln();
        foreach($hasil as $rows){
            $total +=  $rows->nilai_ujian;
            $pdf->SetX(25);
            $pdf->Cell(15,6,$no++,1,0,'C',0);   // empty cell with left,top, and right borders
            $pdf->Cell(140,6,$rows->nama_mapel,1,0,'L',0);
            $pdf->Cell(75,6,$rows->nilai_ujian,1,0,'C',0);
            $pdf->Ln();
        }
        $count = count($hasil);
        $hasilTotal = round($total/$count);
        $pdf->SetX(25);
        $pdf->Cell(155,6,'Nilai Rata-rata',1,0,'C',0);
        $pdf->Cell(75,6,$hasilTotal == 0 ? '-': $hasilTotal,1,0,'C',0);
        $pdf->Ln();
        $pdf->Image($qrCodes, 225, 145, 30, 30, 'png');
        
        
    
            
        //keterangan 3
        $bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        $pdf->SetFont('Arial', 'B', '12');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(138, 174);
        $pdf->Cell(95, 4, date('d', strtotime($tgl_sertifikat_end)) .  ' ' . $bulan[(int)date('m', strtotime($tgl_sertifikat_end)) - 1] . ' ' . date('Y', strtotime($tgl_sertifikat_end)), 0, 1, 'L');

        $pdf->AddPage('P');
        $pdf->Image('public/halaman_1.jpg', 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());
        //nomor sk
        $bulanNomor = array('I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII');
        $pdf->SetFont('Arial', 'B', '12');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(83, 48);
        $pdf->Cell(75, 4,$id_ujian . '/' .'KEP-ALC-BREVET'. '/' . $bulanNomor[(int)date('m', strtotime($tgl_sertifikat_end)) - 1] . '/' . date('Y', strtotime($tgl_sertifikat_end)), 0, 1, 'L');
        
        //tanggal sk
        $pdf->SetFont('Arial', 'B', '12');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(110, 106);
        $start = date('d',strtotime($tgl_sertifikat_start)) .  ' ' . $bulan[(int)date('m', strtotime($tgl_sertifikat_start)) - 1] . ' ' . date('Y', strtotime($tgl_sertifikat_start)) ;
        $end = date('d',strtotime($tgl_sertifikat_end)) .  ' ' . $bulan[(int)date('m', strtotime($tgl_sertifikat_end)) - 1] . ' ' . date('Y', strtotime($tgl_sertifikat_end));
        $pdf->Cell(95, 4, $start .' - '. $end , 0, 1, 'L');
        
        
        $pdf->AddPage('P');
        if($jenis == ""){
            $pdf->Image('public/halaman_2.jpg', 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());
        }else{
            $pdf->Image('public/halaman_cap_2.jpg', 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());
        }
        
          //tanggal sk
        $pdf->SetFont('Arial', 'B', '12');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(80, 33);
        $pdf->Cell(95, 4, strtoupper($nama), 0, 1, 'L');

        
        //tanggal sk
        $pdf->SetFont('Arial', 'B', '12');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(55, 84);
        $pdf->Cell(95, 4, date('d', strtotime($tgl_sertifikat_end)) .  ' ' . $bulan[(int)date('m', strtotime($tgl_sertifikat_end)) - 1] . ' ' . date('Y', strtotime($tgl_sertifikat_end)), 0, 1, 'L');

        $pdf->AddPage('P');
        $pdf->Image('public/halaman_3.jpg', 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());
        $urlAvater = base_url('assets/app-assets/user/'.$siswa->avatar);
        $pdf->Image("$urlAvater", 27.8, 28, 40, 52);
        
        //tanggal sk
        $pdf->SetFont('Arial', '', '12');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(78, 92);
        $pdf->Cell(95, 4,$siswa->nik, 0, 1, 'L');

        $pdf->SetXY(78, 100);
        $pdf->Cell(95, 4,$siswa->nama_siswa, 0, 1, 'L');
        
        $pdf->SetXY(78, 108);
        $pdf->Cell(95, 4,$siswa->tgl_lahir, 0, 1, 'L');
        $pdf->SetXY(78, 117);
        $pdf->Cell(95, 4,$siswa->jenis_kelamin, 0, 1, 'L');
        $pdf->SetXY(78, 125);
        $pdf->Cell(95, 4, strlen($siswa->alamat_ktp) <= 50? $siswa->alamat_ktp : substr($siswa->alamat_ktp, 0, 50).'...', 0, 1, 'L');
        $pdf->SetXY(78, 134);
        $pdf->Cell(95, 4, strlen($siswa->alamat_domisili) <= 50? $siswa->alamat_domisili : substr($siswa->alamat_domisili, 0, 50).'...', 0, 1, 'L');
        $pdf->SetXY(78, 143);
        $pdf->Cell(95, 4,$siswa->kelurahan, 0, 1, 'L');
        $pdf->SetXY(78, 152);
        $pdf->Cell(95, 4,$siswa->kecamatan, 0, 1, 'L');
        $pdf->SetXY(78, 160);
        $pdf->Cell(95, 4,$siswa->kota, 0, 1, 'L');
        $pdf->SetXY(78, 168);
        $pdf->Cell(95, 4,$siswa->provinsi, 0, 1, 'L');
        $pdf->SetXY(78, 176);
        $pdf->Cell(95, 4,$siswa->profesi, 0, 1, 'L');
        $pdf->SetXY(78, 185);
        $pdf->Cell(95, 4,$siswa->kota_intansi, 0, 1, 'L');
        $pdf->SetXY(78, 194);
        $pdf->Cell(95, 4, strlen($siswa->kota_aktifitas_profesi) <= 50? $siswa->kota_aktifitas_profesi : substr($siswa->kota_aktifitas_profesi, 0, 50).'...', 0, 1, 'L');
        $pdf->SetXY(78, 202);
        $pdf->Cell(95, 4,$siswa->bidang_usaha, 0, 1, 'L');
        $pdf->SetXY(78, 210);
        $pdf->Cell(95, 4,$siswa->email, 0, 1, 'L');
        $pdf->SetXY(78, 219);
        $pdf->Cell(95, 4,$siswa->hp, 0, 1, 'L');
        $pdf->SetXY(78, 228);
        $pdf->Cell(95, 4,date("d-m-Y", $siswa->date_created), 0, 1, 'L');
        $pdf->SetXY(78, 237);
        $pdf->Cell(95, 4,date('d-m-Y', strtotime($tgl_sertifikat_end)), 0, 1, 'L');
        $pdf->SetXY(78, 245);
        $pdf->Cell(95, 4,$hasilTotal == 0 ? '-': $hasilTotal, 0, 1, 'L');
        $pdf->SetXY(78, 253);
        $pdf->Cell(95, 4,$nilaiKeterangan." ($keterangan)", 0, 1, 'L');

        $this->response->setContentType('application/pdf');


        $pdf->Output($nama.'-brevet-ab-'.date('d-m-Y').'.pdf', 'I');
    }


    public function transaksi()
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        
        
        $data['transaksi'] = $this->TransaksiModel->getByIdSiswaAll(session('id'));
        $data['siswa'] = $this->SiswaModel->asObject()->find(session()->get('id'));
        
        return view('siswa/transaksi/list', $data);
    }
    
    public function invoice($id)
    {
        $idtransaksi = decrypt_url($id);
        $dataTransaksi  = $this->TransaksiModel
            ->join('detail_transaksi d', 'd.idtransaksi=transaksi.idtransaksi')
            ->join('siswa b', 'b.id_siswa = transaksi.idsiswa')
            ->join('paket c', 'c.idpaket = d.idpaket')
            ->where('transaksi.idtransaksi', $idtransaksi)->get()->getResultObject();
        $invoice  = $this->TransaksiModel->join('siswa b', 'b.id_siswa = transaksi.idsiswa')->where('idtransaksi', $idtransaksi)->get()->getRowObject();
        
        new Pdf();


        $pdf = new \setasign\Fpdi\Fpdi();
        $pdf->SetAutoPageBreak(false, 5);



        // PAGE 1
        $pdf->AddPage('P');
        $pdf->SetCreator("kelasbrevet.com");
        $pdf->SetAuthor($invoice->nama_siswa); // Ganti dengan nama Anda
        $pdf->SetTitle('Invoice'); // Ganti dengan judul dokumen
        $pdf->SetSubject('Invoice #'.$invoice->idtransaksi.$invoice->idsiswa);
        $pdf->SetKeywords('KelasBrevet, Pajak, Brevet Pajak AB, Invoice');
        $pdf->Image('public/lunas.png', 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());

        $pdf->SetTextColor(51, 49, 49);

      
        //logo
        $pdf->Image('assets-landing/images/logo.png', 20, 10, 50, 10);
        
        $pdf->SetFont('Arial', 'B', '12');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(130, 10);
        $pdf->Cell(10, 4, 'Invoice #'.$invoice->idtransaksi.$invoice->idsiswa, 0, 1, 'L');
        
        $pdf->SetFont('Arial', 'B', '8');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(130, 15);
        $pdf->Cell(10, 4, 'Pemesan: '.$invoice->nama_siswa, 0, 1, 'L');
        
        $pdf->SetFont('Arial', 'B', '8');
        $pdf->setFillColor(54, 67, 83);
        $pdf->SetXY(130, 20);
        $pdf->Cell(10, 4, 'Tgl Bayar: '.$invoice->tgl_pembayaran, 0, 1, 'L');

        
        $pdf->SetFont('Arial','B', '10');
        $pdf->SetXY(20, 40);
        $pdf->Cell(110,6,'NAMA PAKET',"B",0,'L',0);   // empty cell with left,top, and right borders
        $pdf->Cell(20,6,'HARGA',"B",0,'C',0);
        $pdf->Cell(20,6,'QTY',"B",0,'C',0); 
        $pdf->Cell(30,6,'JUMLAH HARGA',"B",0,'R',0);
        $pdf->Ln();
        $total = 0;
        $ttd = 80;
        $totalHarga = 0;
        $totalTransaksi = $this->TransaksiModel->where('idtransaksi', $idtransaksi)->get()->getRowObject();
        $totalDetailTransaksi = $this->DetailTransaksiModel->select('count(iddetailtransaksi) as jumlah')->where('idtransaksi', $idtransaksi)->get()->getRowObject();
        $hasil = ($totalTransaksi->nominal)/(int)$totalDetailTransaksi->jumlah;

        foreach($dataTransaksi as $rows){
            $ttd += 5;
            $totalHarga += ($hasil*$rows->quantity );
            
            $pdf->SetFont('Arial','', '9');
            $pdf->SetX(20);
            $pdf->Cell(110,6,str_replace("Ujian Kompetensi","",$rows->name),0,0,'L',0);   // empty cell with left,top, and right borders
            $pdf->Cell(20,6,'Rp.'.number_format($hasil, 0,'.','.'),0,0,'C',0);
            $pdf->Cell(20,6,$rows->quantity,0,0,'C',0);
            $pdf->Cell(30,6,'Rp.'.number_format(($hasil*$rows->quantity ), 0,'.','.'),0,0,'R',0); 
            $pdf->Ln();
        }
        $diskon = ($totalHarga*$rows->diskon)/100; 
        $totalDiskon = $totalHarga - $diskon;
        $diskon_voucher = ($totalDiskon*$rows->voucher)/100;
        
        $pdf->SetFont('Arial','B', '10');
        $pdf->SetX(20);
        $pdf->Cell(130,6,'Sub Total',"T",0,'R',0);   // empty cell with left,top, and right borders
        $pdf->Cell(50,6,'Rp.'.number_format($totalHarga, 0,'.','.'),"T",0,'R',0);
        $pdf->Ln();
        
        $pdf->SetFont('Arial','B', '10');
        $pdf->SetX(20);
        $pdf->Cell(130,6,'Diskon '.$rows->diskon.'%',0,0,'R',0);   // empty cell with left,top, and right borders
        $pdf->Cell(50,6,'(Rp.'.number_format($diskon, 0,'.','.').')',0,0,'R',0);
        $pdf->Ln();
        
        $pdf->SetFont('Arial','B', '10');
        $pdf->SetX(20);
        $pdf->Cell(130,6,'Voucher '.$rows->voucher.'%',0,0,'R',0);   // empty cell with left,top, and right borders
        $pdf->Cell(50,6,'(Rp.'.number_format($diskon_voucher, 0,'.','.').')',0,0,'R',0);
        $pdf->Ln();
        
        $pdf->SetFont('Arial','B', '10');
        $pdf->SetX(20);
        $pdf->Cell(130,6,'TOTAL',0,0,'R',0);   // empty cell with left,top, and right borders
        $pdf->Cell(50,6,'Rp.'.number_format($totalHarga-$diskon-$diskon_voucher, 0,'.','.'),0,0,'R',0);
        $pdf->Ln();
        
        //logo
        $pdf->Image('assets-landing/images/bag_keuangan.png', 150,  $ttd, 50, 35);
  
        
        $this->response->setContentType('application/pdf');


        $pdf->Output('INV -'.date('d-m-Y').'.pdf', 'I');
    }
    
    public function otomatis_kirim_ujian(){
        //  $data = $this->UjianModel->where('status', 'U')->where('end_ujian <', date('Y-m-d H:i'))->get()->getResultObject();
        //  foreach($data as $rows){
        //      $this->UjianSiswaModel
        //         ->set('status', 'selesai')
        //         ->set('date_send', time())
        //         ->where('ujian', $rows->kode_ujian)
        //         ->where('siswa', $rows->id_siswa)
        //         ->update();
        //      $this->UjianModel
        //         ->set('status', 'S')
        //         ->set('nilai', 0)
        //         ->where('id_ujian', $rows->id_ujian)
        //         ->update();
        //  }
        
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

<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\SiswaModel;
use App\Models\GuruModel;
use App\Models\MapelModel;
use App\Models\KelasModel;
use App\Models\GuruKelasModel;
use App\Models\GuruMapelModel;
use App\Models\SmtpModel;
use Google_Client;

class RegisterController extends BaseController
{

    protected $AdminModel;
    protected $SiswaModel;
    protected $GuruModel;
    protected $MapelModel;
    protected $KelasModel;
    protected $GuruKelasModel;
    protected $GuruMapelModel;
    protected $SmtpModel;
    protected $googleClient;
    protected $emailer;

    public function __construct()
    {
        $this->AdminModel = new AdminModel();
        $this->SiswaModel = new SiswaModel();
        $this->GuruModel = new GuruModel();
        $this->MapelModel = new MapelModel();
        $this->KelasModel = new KelasModel();
        $this->GuruKelasModel = new GuruKelasModel();
        $this->GuruMapelModel = new GuruMapelModel();
        $this->SmtpModel = new SmtpModel();

        $this->emailer = new \App\Libraries\Emailer();

        $this->googleClient = new Google_Client();
        $this->googleClient->setClientId('381263998843-1ms0agnrq1eldj7rgj9k10qm7ktgvmb1.apps.googleusercontent.com');
        $this->googleClient->setClientSecret('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');
        $this->googleClient->setRedirectUri('https://kelasbrevet.com/Auth/google');
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
    }

    public function index()
    {
        $data['link'] = $this->googleClient->createAuthUrl();
        $data['kelas'] = $this->KelasModel->asObject()->findAll();
        return view('auth/registrasi', $data);
    }

    public function store()
    {
        // 1. Definisikan Rule Validasi (Mencegah karakter aneh & menjamin format email standar)
        
       $rules = [
            'email' => [
                'rules'  => 'required|valid_email|is_unique[siswa.email]',
                'errors' => [
                    'required'    => 'Email harus diisi.',
                    'valid_email' => 'Format email tidak valid (Gunakan standar: contoh@gmail.com).',
                    'is_unique'   => 'Email sudah terdaftar.'
                ]
            ],
            'nama_siswa' => [
                'rules'  => 'required|alpha_numeric_space|min_length[3]|max_length[60]',
                'errors' => [
                    'required'            => 'Nama harus diisi.',
                    'alpha_numeric_space' => 'Nama hanya boleh huruf, angka, dan spasi.',
                    'min_length'          => 'Nama terlalu pendek.',
                    'max_length'          => 'Nama maksimal 60 karakter.'
                ]
            ],
            'password'   => 'required|min_length[6]',
        ];
    
        // 2. Jalankan Validasi
        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $errorMsg = implode(' ', $errors);
            session()->setFlashdata('pesan', "
                swal({
                    title: 'Gagal!',
                    text: '" . str_replace(["\r", "\n"], '', $errorMsg) . "',
                    type: 'error',
                    padding: '2em'
                })
            ");
            return redirect()->to('auth/registrasi')->withInput();
        }
        
        
        // Cek apakah domain memiliki record MX (Mail Server)
        if (!is_valid_domain($this->request->getPost('email'))) {
            session()->setFlashdata('pesan', "
                swal({
                    title: 'Email Palsu Terdeteksi!',
                    text: 'Domain email tidak terdaftar atau tidak aktif.',
                    type: 'warning',
                    padding: '2em'
                })
            ");
            return redirect()->back()->withInput();
        }
    
        // 3. Verifikasi reCAPTCHA
        if(setting('recaptcha_status') == 'true') {
            $token = $this->request->getPost('recaptcha_token');
            if (!$this->verifyRecaptcha($token, 'registrasi')) {
                session()->setFlashdata('pesan', "swal({title:'Info', text:'Verifikasi Bot Gagal', type:'info', padding:'2em'})");
                return redirect()->to('auth/registrasi')->withInput();
            }
        }
    
        // 4. Proses Data (Hanya jika lolos validasi)
        $emailClean = $this->request->getPost('email', FILTER_SANITIZE_EMAIL);
        $namaClean  = $this->request->getPost('nama_siswa', FILTER_SANITIZE_STRING);
        $nama_pendek = substr($namaClean, 0, 10);
    
        $data_siswa = array(
            'no_induk_siswa' => rand(1000000, 9000000),
            'nama_siswa'     => $namaClean,
            'email'          => $emailClean,
            'password'       => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'jenis_kelamin'  => $this->request->getPost('jenis_kelamin'),
            'kelas'          => $this->request->getPost('kelas'),
            'role'           => 2,
            'is_active'      => 0,
            'date_created'   => time(),
            'avatar'         => 'default.jpg',
        );
    
        $this->SiswaModel->insert($data_siswa);
        $id_siswa = $this->SiswaModel->insertID();
        $idsiswa = encrypt_url($id_siswa);
        // KIRIM EMAIL
        
        $subject = 'SELAMAT ANDA BERHASIL REGISTRASI';
        $message = '
                <div style="color: #000; padding: 10px;">
                    <div
                        style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; font-size: 20px; color: #1C3FAA; font-weight: bold;">
                        VERIFIKASI</div>

                    <br>
                    <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Hallo ' . $nama_pendek . ' <br>
                        <span style="color: #000;">Kami menambahkan anda ke dalam kelasBrevet 
                        <br>Silahkan Verifikasi email anda dibawah ini:</span></p>
                    <table style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">
                        <tr>
                            <td>Nama</td>
                            <td> : ' .$nama_pendek . '</td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td> : ' . $this->request->getVar('email') . '</td>
                        </tr>
                    </table>
                    <br>
                        <a href="' . base_url("auth/verifikasi/$idsiswa") . '"  style="display: inline-block;  background: #1C3FAA; color: #fff;margin:10px; text-decoration: none; border-radius: 5px; text-align: center; line-height: 30px; font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif;">Verifikasi Email</a>
                    </div> 
            ';

        $this->emailer->send($this->request->getVar('email'), $subject, $message);
        
       
        session()->setFlashdata('pesan', "
        swal({
            title: 'Berhasil!',
            text: 'Anda berhasil mendaftar, Cek inbox/Spam email terdaftar untuk proses verifikasi akun',
            type: 'success',
            padding: '2em'
            })
        ");
        // text: 'Anda berhasil mendaftar, Cek inbox/Spam email terdaftar untuk proses verifikasi akun',
        return redirect()->to('auth');
    }
    
    public function storeSiswaMelaluiPesan(){
        // 1. Ambil data input awal
        $idpaketenc = $this->request->getPost('idpaketenc');
        $kodevoucher = $this->request->getPost('kodevoucher');
        $token = $this->request->getPost('recaptcha_token');
    
        // 2. Definisikan Rule Validasi Ketat (Mencegah manipulasi via Burp Suite)
        $rules = [
            'email' => [
                'rules'  => 'required|valid_email|is_unique[siswa.email]',
                'errors' => [
                    'required'    => 'Email tidak boleh kosong.',
                    'valid_email' => 'Format email menyimpang (Gunakan standar: contoh@mail.com).',
                    'is_unique'   => 'Email ini sudah terdaftar di sistem.'
                ]
            ],
            'nama_siswa' => [
                'rules'  => 'required|alpha_numeric_space|min_length[3]|max_length[60]',
                'errors' => [
                    'required'            => 'Nama harus diisi.',
                    'alpha_numeric_space' => 'Nama hanya boleh huruf, angka, dan spasi.',
                    'min_length'          => 'Nama terlalu pendek.',
                    'max_length'          => 'Nama maksimal 60 karakter.'
                ]
            ],
            'password' => [
                'rules'  => 'required|min_length[6]',
                'errors' => [
                    'required'   => 'Password tidak boleh kosong.',
                    'min_length' => 'Password minimal harus {param} karakter.'
                ]
            ],
        ];
    
        // 3. Jalankan Validasi Sisi Server
        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $errorMsg = implode(' ', $errors);
            
            session()->setFlashdata('pesan', "
                swal({
                    title: 'Gagal!',
                    text: '" . str_replace(["\r", "\n"], '', $errorMsg) . "',
                    type: 'error',
                    padding: '2em'
                })
            ");
            return redirect()->back()->withInput();
        }
        
        // Cek apakah domain memiliki record MX (Mail Server)
        if (!is_valid_domain($this->request->getPost('email'))) {
            session()->setFlashdata('pesan', "
                swal({
                    title: 'Email Palsu Terdeteksi!',
                    text: 'Domain email tidak terdaftar atau tidak aktif.',
                    type: 'warning',
                    padding: '2em'
                })
            ");
            return redirect()->back()->withInput();
        }
    
        // 4. Verifikasi Google reCAPTCHA
        if(setting('recaptcha_status') == 'true') {
            if (!$this->verifyRecaptcha($token, 'registrasi_pesan')) {
                session()->setFlashdata('pesan', "swal({title:'Info', text:'Gagal verifikasi token (Bot Detected)', type:'info', padding:'2em'})");
                return redirect()->back()->withInput();
            }
        }
        
        // 5. Sanitasi Data (Pembersihan Karakter Tersembunyi)
        $emailClean = $this->request->getPost('email', FILTER_SANITIZE_EMAIL);
        $namaClean  = $this->request->getPost('nama_siswa', FILTER_SANITIZE_STRING);
    
        // 6. Proses Simpan Data Siswa Baru
        $data_siswa = array(
            'no_induk_siswa' => rand(1000000, 9000000),
            'nama_siswa'     => $namaClean,
            'email'          => $emailClean,
            'password'       => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'jenis_kelamin'  => $this->request->getPost('jenis_kelamin'),
            'kelas'          => $this->request->getPost('kelas'),
            'role'           => 2,
            'is_active'      => 1, // Langsung aktif karena alur pesan/transaksi
            'date_created'   => time(),
            'avatar'         => 'default.jpg',
        );
    
        $this->SiswaModel->insert($data_siswa);
        $id_siswa = $this->SiswaModel->insertID();
        
        // Ambil data siswa yang baru saja masuk untuk session
        $userBaru = $this->SiswaModel->find($id_siswa);
    
        // 7. Set Session (Auto-Login setelah daftar)
        $datasession = [
            'id'               => $userBaru['id_siswa'],
            'email'            => $userBaru['email'],
            'nama'             => $userBaru['nama_siswa'],
            'role'             => $userBaru['role'],
            'is_logged_in'     => true
        ];
        session()->set($datasession);
    
        // 8. Tambahkan ke tabel pengikut
        $data_pengikut = array(
            'id_siswa'         => $id_siswa,
            'tanggal_mulai'    => date('Y-m-d'),
            'tanggal_berakhir' => date('Y-m-d'),
        );
        $this->db->table('pengikut')->insert($data_pengikut);
    
        // 9. Redirect ke halaman pesan dengan ID Paket
        return redirect()->to('sw-siswa/transaksi/pesan/'.$idpaketenc.'/'.$kodevoucher);
    }
    
    private function verifyRecaptcha($token, $action)
    {
        $secret = setting('recaptcha_secret_key');

        $response = file_get_contents(
            "https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$token}"
        );

        $result = json_decode($response, true);

        if (!isset($result['success']) || $result['success'] !== true) {
            return false;
        }

        // cek action
        if ($result['action'] !== $action) {
            return false;
        }

        // cek score (0.0 – 1.0)
        if ($result['score'] < 0.5) {
            return false;
        }

        return true;
    }

    public function verifikasi($id)
    {
        // var_dump($idsiswa);
        $idsiswa = decrypt_url($id);
        $this->SiswaModel
            ->where('id_siswa', $idsiswa)
            ->set('is_active', '1')
            ->update();
         session()->setFlashdata('pesan', "
        swal({
            title: 'Berhasil',
            text: 'Email berhasil diverifikasi, Silahkan login.',
            type: 'success',
            padding: '2em'
            })
        ");
        return redirect()->to('Auth');
    }

    public function autocomplate()
    {
        $cari = $this->request->getPost('term');

        $query = "SELECT * FROM kelas WHERE ( nama_kelas like '%" . $cari . "%') order by nama_kelas asc limit 10";
        $res = $this->db->query($query);
        $result = array();
        foreach ($res->getResult() as $row) {
            array_push($result, array(
                'id_kelas' => $row->id_kelas,
                'nama_kelas' => $row->nama_kelas,
            ));
        }
        return $this->response->setJSON($result);
    }
}

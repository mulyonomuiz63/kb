<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\SiswaModel;
use App\Models\GuruModel;
use App\Models\KelasModel;
use App\Models\SmtpModel;
use App\Models\TokenModel;
use App\Models\MitraModel;
use App\Models\PicModel;
use Google_Client;

class AuthController extends BaseController
{
    protected $AdminModel;
    protected $SiswaModel;
    protected $GuruModel;
    protected $KelasModel;
    protected $SmtpModel;
    protected $TokenModel;
    protected $MitraModel;
    protected $PicModel;
    protected $googleClient;
    protected $email;
    protected $emailer;

    public function __construct()
    {
        helper('setting');
        $this->AdminModel = new AdminModel();
        $this->SiswaModel = new SiswaModel();
        $this->GuruModel = new GuruModel();
        $this->KelasModel = new KelasModel();
        $this->SmtpModel = new SmtpModel();
        $this->TokenModel = new TokenModel();
        $this->MitraModel = new MitraModel();
        $this->PicModel = new PicModel();
        $this->email = \Config\Services::email();
        $this->emailer = new \App\Libraries\Emailer();

        $this->googleClient = new Google_Client();
        $clientId     = setting('client_id');
        $clientSecret = setting('client_secret');
        $redirectUri  = setting('redirect_uri');
        $this->googleClient->setClientId($clientId);
        $this->googleClient->setClientSecret($clientSecret);
        $this->googleClient->setRedirectUri($redirectUri);
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
    }

    public function index()
    {
        $data['link'] = $this->googleClient->createAuthUrl();
        return view('auth/login', $data);
    }
    public function loginProses()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $token = $this->request->getPost('recaptcha_token');

        // 1. Cek Throttler (Proteksi Brute Force)
        $attempts = session()->get('login_attempts') ?? 0;
        if ($attempts >= 5) {
            $lastAttemptTime = session()->get('last_attempt_time');
            if (time() - $lastAttemptTime < 60) {
                $sisaWaktu = 60 - (time() - $lastAttemptTime);
                session()->setFlashdata('pesan', "swal({title:'Terblokir!', text:'Terlalu banyak percobaan. Tunggu $sisaWaktu detik lagi.', type:'warning', padding:'2em'});");
                return redirect()->back()->withInput();
            } else {
                session()->set('login_attempts', 0);
                $attempts = 0;
            }
        }

        // --- PERBAIKAN RECAPTCHA DISINI ---
        // Cek apakah recaptcha diaktifkan di .env
        $isRecaptchaActive = setting('recaptcha_status') === 'true';

        if ($isRecaptchaActive) {
            if (!$this->verifyRecaptcha($token, 'akses-recaptcha')) {
                session()->setFlashdata('pesan', "swal({title: 'Oops!', text: 'Verifikasi reCAPTCHA gagal, silahkan coba lagi.', type: 'error', padding: '2em'});");
                return redirect()->to('auth')->withInput();
            }
        }
        // ----------------------------------

        // 3. Validasi Input (Email & Password)
        $rules = [
            'email' => [
                'rules'  => 'required|valid_email|max_length[100]',
                'errors' => [
                    'required'    => 'Email tidak boleh kosong.',
                    'valid_email' => 'Format email tidak valid.',
                    'max_length'  => 'Email terlalu panjang.',
                ]
            ],
            'password' => [
                'rules'  => 'required|min_length[5]',
                'errors' => [
                    'required'   => 'Password tidak boleh kosong.',
                    'min_length' => 'Password minimal 5 karakter.',
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('pesan', "swal({title:'Gagal!', text:'Format Email atau Password salah', type:'error', padding:'2em'});");
            return redirect()->back()->withInput();
        }

        // 4. Cari User di Berbagai Tabel
        $user = null;
        $type = '';

        if ($siswa = $this->SiswaModel->getByEmail($email)) {
            $user = $siswa;
            $type = 'siswa';
        } elseif ($guru = $this->GuruModel->getByEmail($email)) {
            $user = $guru;
            $type = 'guru';
        } elseif ($admin = $this->AdminModel->getbyemail($email)) {
            $user = $admin;
            $type = 'admin';
        } elseif ($mitra = $this->MitraModel->getbyemail($email)) {
            $user = $mitra;
            $type = 'mitra';
        } elseif ($pic = $this->PicModel->getbyemail($email)) {
            $user = $pic;
            $type = 'pic';
        }

        // 5. Proses Otentikasi
        if ($user && password_verify($password, $user->password)) {

            // RESET COUNTER JIKA SUKSES
            session()->remove(['login_attempts', 'last_attempt_time']);
          
            // Cek Akun Aktif
            if ($user->is_active == 1) {
                // Mapping ID berdasarkan tipe
                $id_key = ($type == 'siswa') ? 'id_siswa' : (($type == 'guru') ? 'id_guru' : (($type == 'admin') ? 'id_admin' : (($type == 'mitra') ? 'idmitra' : 'idpic')));
                $nama_key = ($type == 'siswa') ? 'nama_siswa' : (($type == 'guru') ? 'nama_guru' : (($type == 'admin') ? 'nama_admin' : (($type == 'mitra') ? 'nama_mitra' : 'nama_pic')));

                $sessionData = [
                    'id'    => $user->$id_key,
                    'email' => $user->email,
                    'nama'  => $user->$nama_key,
                    'role'  => $user->role,
                    'avatar'=> $user->avatar,
                ];

                
                session()->set($sessionData);
                if($type != 'siswa'):
                    session()->setFlashdata('pesan', "swal({title: 'Berhasil!', text: 'Selamat Datang', type: 'success', padding: '2em'});");
                endif;

                // Redirect sesuai role
                switch ($type) {
                    case 'siswa':
                        // Jika ada session URL (intended URL), arahkan ke sana, jika tidak ke dashboard siswa
                        $redirectTo = !empty(session('url')) ? session('url') : 'sw-siswa';
                        $pesan = ucwords('Selamat Datang '. session()->get('nama'));
                        return redirect()->to($redirectTo)->with('success', $pesan);

                    case 'guru':
                        return redirect()->to('sw-guru');

                    case 'admin':
                        return redirect()->to('sw-admin'); // Pastikan ini sesuai group routes admin

                    case 'mitra':
                        return redirect()->to('sw-mitra');

                    case 'pic':
                        return redirect()->to('sw-pic');

                    default:
                        return redirect()->to('/');
                }
            } else {
                // Akun Belum Verifikasi
                $nama_key = ($type == 'siswa') ? 'nama_siswa' : (($type == 'guru') ? 'nama_guru' : 'User');
                $id_key = ($type == 'siswa') ? 'id_siswa' : (($type == 'guru') ? 'id_guru' : 'id');

                $this->kirimEmail($user->$id_key, $user->email, $user->$nama_key);
                session()->setFlashdata('pesan', "swal({title: 'Oops!', text: 'Silahkan Verifikasi Email, Cek Inbox/Spam akun anda.', type: 'error', padding: '2em'});");
                return redirect()->to('auth')->withInput();
            }
        } else {
            // GAGAL LOGIN (Password salah atau Email tidak ada)
            $attempts++;
            session()->set('login_attempts', $attempts);
            session()->set('last_attempt_time', time());

            $msg = ($user) ? 'Login Gagal, Email atau Password salah' : 'Email anda belum terdaftar silahkan daftarkan email anda.';
            $type_swal = ($user) ? 'error' : 'info';

            session()->setFlashdata('pesan', "swal({title: 'Oops!', text: '$msg', type: '$type_swal', padding: '2em'});");
            return redirect()->to('auth')->withInput();
        }
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

    public function google()
    {
        if ($this->request->getGet('error') == 'access_denied') {
            session()->setFlashdata('pesan', "
                swal({
                    title: 'Oops!',
                    text: 'Login Gagal, Silahkan masuk kembali.',
                    type: 'error',
                    padding: '2em'
                    })
            ");
            return redirect()->to('auth');
        } else {
            $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->request->getGet('code'));
            if (!isset($token['error'])) {
                $this->googleClient->setAccessToken($token['access_token']);
                $googleService = new \Google_Service_Oauth2($this->googleClient);
                $data = $googleService->userinfo->get();
                // $data['email'];
                // Cek Siswa
                $siswa = $this->SiswaModel->getByEmail($data['email']);
                if ($siswa != null) {
                    if ($siswa->is_active == 1) {
                        $data = [
                            'id' => $siswa->id_siswa,
                            'email' => $siswa->email,
                            'nama' => $siswa->nama_siswa,
                            'role' => $siswa->role,
                            'avatar' => $siswa->avatar,
                            'status' => $siswa->status,
                        ];
                        session()->set($data);
                        // Arahkan Ke halaman siswa
                        session()->setFlashdata('pesan', "
                        swal({
                            title: 'Berhasil!',
                            text: 'Selamat Datang',
                            type: 'success',
                            padding: '2em'
                            })
                        ");
                        if (!empty(session('url'))) {
                            return redirect()->to(session('url'));
                        } else {
                            return redirect()->to('sw-siswa');
                        }
                    } else {
                        session()->setFlashdata('pesan', "
                            swal({
                                title: 'Oops!',
                                text: 'Silahkan Verifikasi Email, Cek Inbox/Spam untuk verifikasi akun email anda.',
                                type: 'error',
                                padding: '2em'
                                })
                        ");
                        return redirect()->to('auth');
                    }
                } else {
                    $data_siswa = [
                        'no_induk_siswa' => rand(1000000, 9000000),
                        'nama_siswa' => $data['name'],
                        'email' => $data['email'],
                        'kelas' => 1,
                        'role' => 2,
                        'is_active' => 1,
                        'date_created' => time(),
                        'avatar' => 'default.jpg'
                    ];
                    $this->SiswaModel->save($data_siswa);


                    $siswa = $this->SiswaModel->getByEmail($data['email']);
                    $data = [
                        'id' => $siswa->id_siswa,
                        'email' => $siswa->email,
                        'nama' => $siswa->nama_siswa,
                        'role' => $siswa->role,
                        'avatar'=> $siswa->avatar,
                        'status' => $siswa->status,
                    ];
                    session()->set($data);

                    // Arahkan Ke halaman siswa
                    session()->setFlashdata('pesan', "
                    swal({
                        title: 'Berhasil!',
                        text: 'Selamat Datang',
                        type: 'success',
                        padding: '2em'
                        })
                    ");
                    if (!empty(session('url'))) {
                        return redirect()->to(session('url'));
                    } else {
                        return redirect()->to('sw-siswa');
                    }
                }
            } else {
                session()->setFlashdata('pesan', "
                swal({
                    title: 'Oops!',
                    text: 'Login Gagal, Silahkan masuk kembali.',
                    type: 'error',
                    padding: '2em'
                    })
                ");
                return redirect()->to('auth');
            }
        }
    }


    public function cek_no_induk()
    {
        if ($this->request->isAJAX()) {
            $no_induk = $this->request->getVar('no_induk');
            $siswa = $this->SiswaModel->getByNoInduk($no_induk);

            if ($siswa != null) {
                echo '1';
            } else {
                echo '0';
            }
        }
    }

    public function verify()
    {
        $email = $this->request->getVar('email');
        $token = $this->request->getVar('token');

        $akun = $this->SiswaModel->getByEmail($email);

        if ($akun != null) {
            $user = $this->SiswaModel->getByEmail($email);
        } else {
            $user = $this->GuruModel->getByEmail($email);
        }

        if ($user != null) {
            $user_token = $this->TokenModel->getByEmailAndToken($email, $token);
            if ($user_token != null) {
                // CEK APAKAH TOKEN SUDAH LEBIH DARI 1 HARI
                if (time() - $user_token->date_created < (60 * 60 * 24)) {

                    if ($user->role == 2) {
                        $this->SiswaModel
                            ->set('is_active', 1)
                            ->where('email', $email)
                            ->update();

                        $this->TokenModel->delete($user_token->id_user_token);

                        session()->setFlashdata(
                            'pesan',
                            "swal({
                                title: 'Berhasil!',
                                text: '" . $email . " Sudah aktif',
                                type: 'success',
                                padding: '2em'
                            })"
                        );
                        return redirect()->to('auth');
                    } else {
                        $this->GuruModel
                            ->set('is_active', 1)
                            ->where('email', $email)
                            ->update();

                        $this->TokenModel->delete($user_token->id_user_token);

                        session()->setFlashdata(
                            'pesan',
                            "swal({
                                title: 'Berhasil!',
                                text: '" . $email . " Sudah aktif',
                                type: 'success',
                                padding: '2em'
                            })"
                        );
                        return redirect()->to('auth');
                    }
                } else {

                    if ($user->role == 3) {
                        $this->GuruModel->delete($user->id_guru);
                    } else {
                        $this->SiswaModel->delete($user->id_siswa);
                    }
                    $this->TokenModel->delete($user_token->id_user_token);

                    session()->setFlashdata(
                        'pesan',
                        "swal({
                            title: 'Oops!',
                            text: 'Aktivasi gagal, Token Kadaluarsa!',
                            type: 'error',
                            padding: '2em'
                        })"
                    );
                    return redirect()->to('auth');
                }
            } else {
                session()->setFlashdata(
                    'pesan',
                    "swal({
                        title: 'Oops!',
                        text: 'Aktivasi gagal, Token salah!',
                        type: 'error',
                        padding: '2em'
                    })"
                );
                return redirect()->to('auth');
            }
        } else {
            session()->setFlashdata(
                'pesan',
                "swal({
                    title: 'Oops!',
                    text: 'Aktivasi gagal, email salah!',
                    type: 'error',
                    padding: '2em'
                })"
            );
            return redirect()->to('auth');
        }
    }

    public function recovery()
    {
        return view('auth/forgot-password');
    }
    public function recovery_()
    {
        // CEK EMAIL
        if(setting('recaptcha_status') == 'true') {
            $token = $this->request->getPost('recaptcha_token');
            if (!$this->verifyRecaptcha($token, 'akses-recaptcha')) {
                session()->setFlashdata('pesan', "
                            swal({
                                title: 'Oops!',
                                text: 'Email tidak ditemukan',
                                type: 'error',
                                padding: '2em'
                                })
                                ");
                return redirect()->to('auth/recovery');
            }
        }
        
        // Cek email ke siswa
        $email = $this->request->getVar('email');
        $siswa = $this->SiswaModel->getByEmail($email);
        if ($siswa != null) {
            // Fungsi Siswa

            $token = random_string('alnum', 32);
            $user_token = [
                'email' => $email,
                'token' => $token,
                'date_created' => time()
            ];

           $subject = 'Forgot Password';
            $message = '
                <div style="color: #000; padding: 10px;">
                    <div style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; font-size: 20px; color: #1C3FAA; font-weight: bold;">
                        RESET PASSWORD
                    </div>
                    
                    <br>
                    <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Hallo ' . $this->request->getVar('nama') . ' <br>
                        <span style="color: #000;">Klik Tombol dibawah ini untuk melanjutkan proses</span><br>
                        </p>
                    <a href="' . base_url() . '/auth/change-password?email=' . $email . '&token=' . $token .  '" style="display: inline-block; width: 100px; height: 30px; background: #1C3FAA; color: #fff; text-decoration: none; border-radius: 5px; text-align: center; line-height: 30px; font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif;">
                        resset Password        
                    </a>
                </div>
            ';

            $emaiFeed = $this->emailer->send($email, $subject, $message);

            if (!$emaiFeed) {
                // echo $this->email->printDebugger();
                // die();
                session()->setFlashdata('pesan', "
                    swal({
                        title: 'Informasi!',
                        text: 'Sistem lagi dalam antrian, Coba lagi!',
                        type: 'info'
                    })
                ");
                return redirect()->to('auth');
            } else {
                $this->TokenModel->save($user_token);
                session()->setFlashdata('pesan', "
                    swal({
                        title: 'Berhasil!',
                        text: 'silahkan buka email untuk melanjutkan prosses',
                        type: 'success'
                    })
                ");
                return redirect()->to('auth');
            }
        } else {
            session()->setFlashdata('pesan', "
                swal({
                    title: 'Oops!',
                    text: 'Email tidak ditemukan',
                    type: 'error',
                    padding: '2em'
                    })
            ");
            return redirect()->to('auth/recovery');
        }
    }
    public function changePassword()
    {
        $email = $this->request->getGet('email');
        $token = $this->request->getGet('token');

        $cek_user_token = $this->TokenModel->getByEmailAndToken($email, $token);

        if ($cek_user_token == null) {
            return redirect()->to('auth');
        }

        $data['email'] = $email;
        $data['token'] = $token;
        return view('auth/reset-password', $data);
    }
    public function changePassword_()
    {
        $email = $this->request->getVar('email');
        $token = $this->request->getVar('token');
        $new_password = $this->request->getVar('password');
        $user = $this->SiswaModel->getByEmail($email);

        if ($user != null) {
            $user_token = $this->TokenModel->getByEmailAndToken($email, $token);
            if ($user_token != null) {
                if (time() - $user_token->date_created < (60 * 60 * 24)) {
                    $this->SiswaModel
                        ->set('password', password_hash($new_password, PASSWORD_DEFAULT))
                        ->where('email', $email)
                        ->update();

                    $this->TokenModel->delete($user_token->id_user_token);

                    session()->setFlashdata(
                        'pesan',
                        "swal({
                                title: 'Berhasil!',
                                text: 'password diganti',
                                type: 'success'
                            })"
                    );
                    return redirect()->to('auth');
                } else {

                    $this->TokenModel->delete($user_token->id_user_token);

                    session()->setFlashdata(
                        'pesan',
                        "swal({
                                title: 'Oops!',
                                text: 'Aktivasi gagal, Token Expired!',
                                type: 'error'
                            })"
                    );
                    return redirect()->to('auth');
                }
            } else {
                session()->setFlashdata(
                    'pesan',
                    "swal({
                            title: 'Oops!',
                            text: 'Aktivasi gagal, Token kadaluarsa, Silahkan requesst ulang!',
                            type: 'error'
                        })"
                );
                return redirect()->to('auth');
            }
        } else {
            session()->setFlashdata(
                'pesan',
                "swal({
                        title: 'Oops!',
                        text: 'Aktivasi gagal, email salah!',
                        type: 'error',
                        padding: '2em'
                    })"
            );
            return redirect()->to('auth');
        }
    }

    public function logout()
    {
        $session = session();
        $sessionID = session_id();  // ambil ID dari PHP

        $session->destroy();        // hancurkan session aktif

        $file = WRITEPATH . 'session/ci_session' . $sessionID;
        if (is_file($file)) {
            unlink($file);
        }

        session()->setFlashdata('pesan', "
            swal({
                title: 'Berhasil!',
                text: 'berhasil logout',
                type: 'success',
                padding: '2em'
            })
        ");
        return redirect()->to('/');
    }


    public function kirimEmail($id, $email, $nama_siswa)
    {
        $idsiswa = encrypt_url($id);

        $subject = 'VERIFIKASI';
        $message = '
                <div style="color: #000; padding: 10px;">
                    <div
                        style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; font-size: 20px; color: #1C3FAA; font-weight: bold;">
                        VERIFIKASI EMAIL</div>

                    <br>
                    <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Hallo ' . $nama_siswa . ' <br>
                        <span style="color: #000;">Kami menambahkan anda ke dalam kelasBrevet 
                        <br>Silahkan Verifikasi email anda dibawah ini:</span></p>
                    <table style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">
                        <tr>
                            <td>Nama</td>
                            <td> : ' . $nama_siswa . '</td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td> : ' . $email . '</td>
                        </tr>
                    </table>
                    <br>
                        <a href="' . base_url("auth/verifikasi/$idsiswa") . '"  style="display: inline-block;  background: #1C3FAA; color: #fff;margin:10px; text-decoration: none; border-radius: 5px; text-align: center; line-height: 30px; font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif;">Verifikasi Email</a>
                    </div> 
            ';

        return $this->emailer->send($email, $subject, $message);
    }
}

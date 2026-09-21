<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class ProfileController extends BaseController
{
    protected $siswaModel;
    public function __construct()
    {
        $this->siswaModel = new \App\Models\SiswaModel();
    }
    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-siswa')],
            ['title' => 'Profile', 'url' => '#'],
        ];
        $data['siswa'] = $this->siswaModel->asObject()->find(session()->get('id'));

        return view('siswa/profile/list', $data);
    }

    public function editProfile()
    {
        // 2. Definisikan Rule Validasi
        $id_session = session()->get('id');
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
                // PERHATIKAN: Gunakan tanda kutip ganda (" ") di bawah ini, bukan kutip satu (' ')
                'rules'  => "required|numeric|min_length[10]|max_length[15]|is_unique[siswa.hp,id_siswa,{$id_session}]",
                'errors' => [
                    'required'   => 'Nomor HP wajib diisi.',
                    'numeric'    => 'Nomor HP harus berupa angka.',
                    'max_length' => 'Nomor HP maksimal 15 digit.',
                    'min_length' => 'Nomor HP minimal 10 digit.',
                    'is_unique'  => 'Nomor HP ini sudah digunakan oleh akun lain.'
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
            'kantor' => [
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
            return redirect()->to(base_url('sw-siswa/profile'))->withInput()->with('pesan', $pesanError);
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

        $riwayat = $this->request->getVar('riwayat_pekerjaan'); // Ini akan menjadi array
        if (!is_array($riwayat)) {
            $riwayat = [];
        }
        $riwayat_bersih = array_values(array_filter($riwayat, function ($value) {
            return !empty(trim($value));
        }));

        // 4. Encode menjadi format JSON
        $json_riwayat = json_encode($riwayat_bersih);

        // 6. Update Database
        $this->siswaModel
            ->set('nama_siswa', $namaClean)
            ->set('jenis_kelamin', $this->request->getVar('jenis_kelamin'))
            ->set('avatar', $nama_gambar)
            ->set('nik', $this->request->getVar('nik'))
            ->set('tempat_lahir', $this->request->getVar('tempat_lahir'))
            ->set('tgl_lahir', $this->request->getVar('tgl_lahir'))
            ->set('alamat_ktp', $this->request->getVar('alamat_ktp'))
            ->set('alamat_domisili', $this->request->getVar('alamat_domisili'))
            ->set('provinsi', $this->request->getVar('provinsi'))
            ->set('kota', $this->request->getVar('kota'))
            ->set('kecamatan', $this->request->getVar('kecamatan'))
            ->set('kelurahan', $this->request->getVar('kelurahan'))
            ->set('hp', $this->request->getVar('hp'))
            ->set('profesi', $this->request->getVar('profesi'))
            ->set('kantor', $this->request->getVar('kantor'))
            ->set('nama_kantor', $this->request->getVar('nama_kantor'))
            ->set('bidang_usaha', $this->request->getVar('bidang_usaha'))
            ->set('alamat_kantor', $this->request->getVar('alamat_kantor'))
            ->set('riwayat_pekerjaan',  $json_riwayat)
            ->set('status', 'S')
            ->where('id_siswa', session()->get('id'))
            ->update();
        return redirect()->to('sw-siswa')->with('success', 'Profile telah diperbarui');
    }
    public function editPassword()
    {
        if (session()->get('role') != 2) {
            return redirect()->to('auth');
        }
        $siswa = $this->siswaModel->asObject()->find(session()->get('id'));

        $this->siswaModel->save([
            'id_siswa' => $siswa->id_siswa,
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
        ]);
        return redirect()->to('sw-siswa/profile')->with('success', 'Password telah diubah');
    }

    // Pastikan Anda meload helper atau class untuk kirim_wa
    // dan memiliki model untuk akses tabel siswa.

public function sendOtp()
    {
        try {
            // 1. Ambil data dari post dan session
            $hp = $this->request->getPost('hp');
            $id_siswa = session()->get('id');

            // 2. Validasi Sesi (Pastikan user masih login)
            if (!$id_siswa) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Sesi Anda telah habis, silakan muat ulang halaman atau login kembali.',
                    'csrfHash' => csrf_hash()
                ]);
            }

            // 3. Bersihkan Nomor HP (Hanya ambil angka)
            $hp = preg_replace('/[^0-9]/', '', (string)$hp);

            // 4. Validasi Input HP
            if (empty($hp) || strlen($hp) < 9) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Format nomor WhatsApp tidak valid.',
                    'csrfHash' => csrf_hash()
                ]);
            }

            // 5. Generate OTP dan Expired
            $otp_code = rand(100000, 999999);
            $expired_time = date('Y-m-d H:i:s', strtotime('+5 minutes'));

            // 6. Simpan ke Database dengan pengecekan
            $updateDb = $this->siswaModel->update($id_siswa, [
                'wa_otp' => $otp_code,
                'wa_otp_expired' => $expired_time
            ]);

            if (!$updateDb) {
                throw new \Exception('Gagal menyimpan kode OTP ke database.');
            }

            // 7. Siapkan Template WhatsApp
            $data_template = [
                "template_name"     => "kirim_otp",
                "template_language" => "id",
                "parameter"         => [
                    [
                        "1"    => (string)$otp_code,
                        "code" => (string)$otp_code
                    ]
                ],
                "apps_source"       => "kelasbrevet"
            ];

            // 8. Kirim WA dan Cek Responnya
            kirim_wa($hp, '', $data_template);
            
            // Catatan: Jika helper kirim_wa mengembalikan format JSON/Array, 
            // Anda bisa melakukan pengecekan di sini. 
            // Contoh (sesuaikan dengan respon asli dari Watzap Anda):
            // $res_wa = json_decode($send, true);
            // if(isset($res_wa['status']) && $res_wa['status'] != 'success') {
            //     throw new \Exception('API Watzap gagal merespon: ' . json_encode($send));
            // }

            // 9. Berhasil
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'OTP Terkirim ke nomor Anda.',
                'csrfHash' => csrf_hash()
            ]);

        } catch (\Exception $e) {
            // CATAT ERROR KE FILE LOG CI4 (Cek di folder writable/logs/)

            // Tampilkan pesan error detail jika di environment development, 
            // tampilkan pesan umum jika di production agar aman.
            $msg = ENVIRONMENT !== 'production' ? $e->getMessage() : 'Terjadi kesalahan sistem saat mengirim OTP. Silakan coba beberapa saat lagi.';

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $msg,
                'csrfHash' => csrf_hash()
            ]);
        }
    }

    public function verifyOtp()
    {
        try {
            $hp = $this->request->getPost('hp');
            $otp = $this->request->getPost('otp');
            $id_siswa = session()->get('id');

            // 1. Validasi Input & Sesi
            if (!$id_siswa) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Sesi habis.', 'csrfHash' => csrf_hash()]);
            }
            if (empty($hp) || empty($otp)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap.', 'csrfHash' => csrf_hash()]);
            }

            // Bersihkan nomor HP dan OTP
            $hp = preg_replace('/[^0-9]/', '', (string)$hp);
            $otp = preg_replace('/[^0-9]/', '', (string)$otp);

            // 2. Ambil data siswa
            $siswa = $this->siswaModel->find($id_siswa);

            // Pengecekan apakah user ditemukan di DB
            if (!$siswa) {
                throw new \Exception('Data siswa tidak ditemukan di database.');
            }

            // Pengecekan apakah OTP ada di DB
            if (empty($siswa['wa_otp'])) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Sistem tidak menemukan permintaan OTP untuk akun ini. Silakan klik Kirim Ulang OTP.',
                    'csrfHash' => csrf_hash()
                ]);
            }

            $now = date('Y-m-d H:i:s');

            // 3. Cek Kedaluwarsa
            if ($now > $siswa['wa_otp_expired']) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Kode OTP sudah kadaluarsa. Silakan kirim ulang kode.',
                    'csrfHash' => csrf_hash()
                ]);
            }

            // 4. Cek Kecocokan (Gunakan operator type casting agar string "123456" = int 123456)
            if ((string)$otp !== (string)$siswa['wa_otp']) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Kode OTP tidak cocok. Silakan periksa kembali.',
                    'csrfHash' => csrf_hash()
                ]);
            }

            // 5. Update Status Verified
            $updateDb = $this->siswaModel->update($id_siswa, [
                'hp' => $hp,
                'is_wa_verified' => 1,
                'wa_otp' => null,
                'wa_otp_expired' => null
            ]);

            if (!$updateDb) {
                throw new \Exception('Gagal mengupdate status verifikasi ke database.');
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Nomor WhatsApp berhasil diverifikasi.',
                'csrfHash' => csrf_hash()
            ]);

        } catch (\Exception $e) {
            // CATAT ERROR KE FILE LOG
            log_message('error', '[VERIFY OTP ERROR] ' . $e->getMessage() . ' | Line: ' . $e->getLine());

            $msg = ENVIRONMENT !== 'production' ? $e->getMessage() : 'Terjadi kesalahan sistem saat memverifikasi kode.';

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $msg,
                'csrfHash' => csrf_hash()
            ]);
        }
    }
}

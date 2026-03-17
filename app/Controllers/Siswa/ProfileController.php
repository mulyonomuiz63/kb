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
    
        // 6. Update Database
        $this->siswaModel
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
        return redirect()->to('sw-siswa/profile')->with('success','Profile telah diperbarui');
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
}

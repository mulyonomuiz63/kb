<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\AffiliateModel;
use App\Models\AffiliateCommissionModel;
use App\Models\SiswaModel;
use App\Models\AffiliateLinkModel;

class AffiliateController extends BaseController
{
    protected $affiliate;
    protected $komisi;
    protected $SiswaModel;
    protected $affiliateLinkModel;

    public function __construct()
    {
        $this->affiliate = new AffiliateModel();
        $this->komisi    = new AffiliateCommissionModel();
        $this->SiswaModel    = new SiswaModel();
        $this->affiliateLinkModel = new AffiliateLinkModel();
    }

    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-siswa')],
            ['title' => 'Affiliate', 'url' => '#'],
        ];
        // Ambil affiliate
        $affiliate = $this->affiliate
            ->where('user_id', session()->get('id'))
            ->get()->getRowObject();
    
        $data['affiliates'] = $affiliate;
        $data['siswa'] = $this->SiswaModel->asObject()->find(session()->get('id'));
    
        // Kalau belum punya affiliate
        if (!$affiliate) {
            $data['komisi'] = [];
            $data['pager']  = null;
            return view('siswa/affiliate/index', $data);
        }
    
        // Pagination komisi (WAJIB pakai group)
        $data['komisi'] = $this->komisi
            ->select('affiliate_commissions.*, siswa.nama_siswa')
            ->join('transaksi', 'transaksi.idtransaksi=affiliate_commissions.id_transaksi')
            ->join('siswa', 'siswa.id_siswa=transaksi.idsiswa')
            ->where('kode_affiliate', $affiliate->kode_affiliate)
            ->orderBy('tgl_approved', 'DESC')
            ->paginate(10, 'komisi');
    
        $data['pager'] = $this->komisi->pager;
    
        return view('siswa/affiliate/index', $data);
    }


    public function create()
    {
        $data['siswa'] = $this->SiswaModel->asObject()->find(session()->get('id'));
        return view('siswa/affiliate/form', $data);
    }

    public function edit($id)
    {
        $data['breadcrumbs'] = [
            ['title' => 'Affiliate', 'url' => base_url('sw-siswa/affiliate')],
            ['title' => 'Edit Affiliate', 'url' => '#'],
        ];
        $data['siswa'] = $this->SiswaModel->asObject()->find(session()->get('id'));
        $data['affiliate'] = $this->affiliate->find($id);
        
    
        return view('siswa/affiliate/form', $data);
    }

    public function save()
    {
        // 1. Tentukan Rules Validasi
        $rules = [
            'bank' => [
                'rules'  => 'required|alpha_numeric_space|max_length[50]',
                'errors' => [
                    'required' => 'Nama Bank wajib diisi.',
                    'alpha_numeric_space' => 'Nama Bank tidak boleh mengandung karakter spesial.',
                ]
            ],
            'norek' => [
                'rules'  => 'required|numeric|min_length[5]|max_length[25]',
                'errors' => [
                    'required' => 'Nomor rekening wajib diisi.',
                    'numeric'  => 'Nomor rekening hanya boleh berisi angka.',
                    'min_length' => 'Nomor rekening terlalu pendek.',
                    'max_length' => 'Nomor rekening maksimal 25 angka.',
                ]
            ],
            'nama_akun_bank' => [
                'rules'  => 'required|regex_match[/^[a-zA-Z0-9 \.]+$/]|max_length[50]',
                'errors' => [
                    'required' => 'Nama pemilik rekening wajib diisi.',
                    'regex_match' => 'Nama pemilik rekening hanya boleh huruf, angka, spasi, dan titik.',
                ]
            ],
            'cabang_bank' => [
                'rules'  => 'required|alpha_numeric_space|max_length[50]',
                'errors' => [
                    'required' => 'Cabang bank wajib diisi.',
                    'alpha_numeric_space' => 'Cabang tidak boleh mengandung karakter spesial.',
                ]
            ]
        ];
    
        // 2. Jalankan Validasi
        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $pesanError = implode('\n', $errors); 
    
            return redirect()->back()->withInput('pesan', $pesanError);
        }
    
        // 3. Inisialisasi Data & Keamanan
        $id = $this->request->getPost('id_affiliate');
        $currentUserId = session()->get('id');
    
        // Data dasar yang akan disimpan
        $data = [
            'user_id'        => $currentUserId,
            'bank'           => esc($this->request->getPost('bank')),
            'norek'          => esc($this->request->getPost('norek')),
            'nama_akun_bank' => esc($this->request->getPost('nama_akun_bank')),
            'cabang_bank'    => esc($this->request->getPost('cabang_bank')),
        ];
    
        // 4. Logika Update atau Insert
        if ($id) {
            // --- PROTEKSI UPDATE (IDOR PROTECTION) ---
            $affiliate = $this->affiliate->where([
                'id_affiliate' => $id,
                'user_id'      => $currentUserId
            ])->first();
    
            // Jika data tidak ditemukan atau bukan milik user tersebut
            if (!$affiliate) {
                return redirect()->back()->with('pesan', 'Gagal memproses data,Coba lagi!');
            }
    
            // --- PROTEKSI LIMIT EDIT (Maksimal 1 Kali) ---
            if (($affiliate['total_edit'] ?? 0) >= 1) {
                return redirect()->back()->with('pesan', 'Anda sudah menggunakan batas maksimal pengubahan data.');
            }
    
            // Gunakan status yang sudah ada (mencegah manipulasi status via Inspect Element)
            $data['status'] = $affiliate['status'];
            $data['total_edit'] = ($affiliate['total_edit'] ?? 0) + 1;
    
            $this->affiliate->update($id, $data);
            $pesanUser = 'Data rekening berhasil diperbarui.';
            
        } else {
            // --- PROTEKSI INSERT ---
            // Pastikan status selalu Pending (0) saat pendaftaran baru
            $data['status'] = '0';
            $data['total_edit'] = 0;
            
            send_notif(
                '1', // Pastikan kolom di DB namanya idsiswa
                "Pendaftar affiliate baru", 
                "Silahkan verifikasi pendaftar affiliate baru.", 
                base_url('sw-admin/affiliate')
            );
    
            $this->affiliate->insert($data);
            $pesanUser = 'Pendaftaran program affiliate berhasil disimpan.';
        }
    
        return redirect()->to(base_url('sw-siswa/affiliate'))->with('success', $pesanUser);
    }
    public function delete()
    {
        $id = $this->request->getPost('id');
    
        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID tidak ditemukan'
            ]);
        }
    
        $this->affiliate->delete($id);
    
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Pengajuan affiliate berhasil dibatalkan'
        ]);
    }

    
    public function copy()
    {
        if (!$this->request->isAJAX()) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Forbidden'
                ]);
        }
        // cek affiliate terlebih dahulu
        $affiliate = $this->affiliate->where('user_id', session()->get('id'))->first();
        
        
        $paket_id       = $this->request->getPost('paket_id');
        $kode_affiliate = $affiliate['kode_affiliate'];
        $now       = date('Y-m-d H:i:s');
        $expiredAt = date('Y-m-d H:i:s', strtotime('+7 days'));
    
        // 🔍 CEK DATA EXISTING
        $existing = $this->affiliateLinkModel
            ->where('kode_affiliate', $kode_affiliate)
            ->where('paket_id', $paket_id)
            ->first();
    
        // ===============================
        // JIKA DATA SUDAH ADA
        // ===============================
        if ($existing) {
    
            // ⏳ CEK EXPIRED
            if ($existing['expired_at'] > $now) {
    
                // MASIH AKTIF → UPDATE EXPIRED SAJA
                $this->affiliateLinkModel->update($existing['id'], [
                    'expired_at' => $expiredAt
                ]);
    
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Link masih aktif, expired diperpanjang',
                    'link'    => base_url('ref/'.$existing['short_code'].'/8173AF4239'),
                ]);
            }else{
                $chars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                $length = 8;
        
                do {
                    $kode = '';
                    for ($i = 0; $i < $length; $i++) {
                        $kode .= $chars[random_int(0, strlen($chars) - 1)];
                    }
                } while ($this->affiliateLinkModel->where('short_code', $kode)->countAllResults() > 0);
        
                $short_code = $kode;
                
                // ❌ SUDAH EXPIRED → UPDATE DATA
                $this->affiliateLinkModel->update($existing['id'], [
                    'short_code' => $short_code,
                    'expired_at' => $expiredAt
                ]);
        
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Link diperbarui karena sudah expired',
                    'link'    => base_url('ref/'.$short_code.'/8173AF4239')
                ]);
            }
    
        }else{
            // ===============================
            // JIKA DATA BELUM ADA → INSERT
            // ===============================
            $chars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $length = 8;
    
            do {
                $kode = '';
                for ($i = 0; $i < $length; $i++) {
                    $kode .= $chars[random_int(0, strlen($chars) - 1)];
                }
            } while ($this->affiliateLinkModel->where('short_code', $kode)->countAllResults() > 0);
    
            $short_code = $kode;
            
            $insert = $this->affiliateLinkModel->insert([
                'kode_affiliate' => $kode_affiliate,
                'paket_id'       => $paket_id,
                'short_code'     => $short_code,
                'expired_at'     => $expiredAt,
            ]);
        
            if ($insert === false) {
                return $this->response
                    ->setStatusCode(500)
                    ->setJSON([
                        'status'  => 'error',
                        'message' => 'Gagal menyimpan data'
                    ]);
            }else{
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Link berhasil dibuat',
                    'link'    => base_url('ref/'.$short_code.'/8173AF4239')
                ]);
            }
        
        }
    
    }



}

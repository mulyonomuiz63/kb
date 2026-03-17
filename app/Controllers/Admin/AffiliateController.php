<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AffiliateModel;
use App\Models\AffiliateLinkModel;
use App\Models\AffiliateCommissionModel;

class AffiliateController extends BaseController
{
    protected $affiliate;
    protected $affiliateLinkModel;
    protected $komisi;

    public function __construct()
    {
        $this->affiliate = new AffiliateModel();
        $this->affiliateLinkModel = new AffiliateLinkModel();
        $this->komisi    = new AffiliateCommissionModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Affiliate',
        ];
        return view('admin/affiliate/index', $data);
    }

    public function datatables()
    {
        $request = service('request');

        $draw   = (int) $request->getPost('draw');
        $start  = (int) $request->getPost('start');
        $length = (int) $request->getPost('length');
        $search = $request->getPost('search')['value'] ?? null;

        $builder = $this->affiliate
            ->select("
            siswa.nama_siswa,
            affiliates.id_affiliate,
            affiliates.status,
            affiliates.kode_affiliate,
            affiliates.created_at,

            IFNULL(
                SUM(
                    CASE 
                        WHEN c.status = 'approved'
                         AND c.status_penarikan = 'pending'
                        THEN (c.harga * c.komisi / 100)
                        ELSE 0
                    END
                ),
            0) AS total_komisi
        ")
            ->join('siswa', 'siswa.id_siswa = affiliates.user_id')
            ->join(
                'affiliate_commissions c',
                'c.kode_affiliate = affiliates.kode_affiliate 
             AND c.status = "approved"
             AND c.status_penarikan IN ("pending")',
                'left'
            )
            ->where('affiliates.status !=', '2')
            ->groupBy('affiliates.kode_affiliate');

        // 🔎 SEARCH
        if ($search) {
            $builder->groupStart()
                ->like('siswa.nama_siswa', $search)
                ->groupEnd();
        }

        $totalData = $builder->countAllResults(false);

        $builder->orderBy('affiliates.status', 'asc')
            ->orderBy('affiliates.id_affiliate', 'desc')
            ->limit($length, $start);

        $query = $builder->get()->getResult();

        $data = [];

        foreach ($query as $row) {

            switch ($row->status) {
                case '1':
                    $badge = 'badge-success';
                    $text  = 'Approved';
                    break;
                case '0':
                    $badge = 'badge-warning';
                    $text  = 'Pending';
                    break;
                default:
                    $badge = 'badge-secondary';
                    $text  = '-';
            }

            $data[] = [
                '<div class="font-weight-bold">' . $row->nama_siswa . '</div>
             <small class="text-muted">Affiliate User</small>',

                date('d M Y H:i', strtotime($row->created_at)),

                '<span class="text-success font-weight-bold">
                Rp ' . number_format($row->total_komisi, 0, ',', '.') . '
            </span>',

                '<span class="badge ' . $badge . ' px-3 py-2">' . $text . '</span>',

                '<div class="btn-group">
                <a href="' . base_url('sw-admin/affiliate/edit/' . encrypt_url($row->id_affiliate)) . '"
                    class="btn btn-sm btn-outline-warning mr-2">
                    <i class="bi bi-eye"></i>
                </a>
                <a href="' . base_url('sw-admin/affiliate/komisi/' . encrypt_url($row->kode_affiliate)) . '"
                    class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye"></i>
                </a>
            </div>'
            ];
        }

        return $this->response->setJSON([
            'draw' => intval($draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalData,
            'data' => $data,
            csrf_token() => csrf_hash() // 🔥 AUTO REFRESH CSRF
        ]);
    }


    public function edit($id)
    {
        try {

            // Pastikan parameter tidak kosong
            if (empty($id)) {
                throw new \Exception('Parameter tidak valid.');
            }

            // Decrypt ID
            $id = decrypt_url($id);

            // Pastikan hasil decrypt berupa angka
            if (!is_numeric($id)) {
                throw new \Exception('ID tidak valid.');
            }

            // Cari data affiliate
            $affiliate = $this->affiliate->find((int) $id);

            if (!$affiliate) {
                throw new \Exception('Data affiliate tidak ditemukan.');
            }

            $data['affiliate'] = $affiliate;
            return view('admin/affiliate/form', $data);
        } catch (\Throwable $e) {

            log_message('error', 'Affiliate Edit Error: ' . $e->getMessage());

            return redirect()
                ->to(base_url('sw-admin/affiliate'))
                ->with('error', 'Terjadi kesalahan atau data tidak ditemukan.');
        }
    }

    public function store()
    {
        try {

            $id = $this->request->getPost('id_affiliate');

            // Ambil & sanitasi input
            $data = [
                'bank'           => strtoupper(trim($this->request->getPost('bank'))),
                'norek'          => trim($this->request->getPost('norek')),
                'nama_akun_bank' => trim($this->request->getPost('nama_akun_bank')),
                'cabang_bank'    => trim($this->request->getPost('cabang_bank')),
                'status'         => $this->request->getPost('status'),
            ];

            // ===============================
            // UPDATE
            // ===============================
            if ($id) {

                if (!$this->affiliate->update($id, $data)) {
                    throw new \Exception('Gagal memperbarui data affiliate.');
                }

                $cekDataAffiliate = $this->affiliate
                    ->where('id_affiliate', $id)
                    ->get()
                    ->getRowObject();

                if ($cekDataAffiliate) {

                    $statusInput = $data['status'];

                    switch ($statusInput) {
                        case '1':
                            $judul = "Affiliate Disetujui!";
                            $pesan = "Selamat! Pendaftaran affiliate Anda telah diterima. Sekarang Anda bisa mulai menggunakan fitur affiliate.";
                            break;

                        case '2':
                            $judul = "Affiliate Ditolak";
                            $pesan = "Mohon maaf, pendaftaran affiliate Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.";
                            break;

                        case '0':
                            $judul = "Affiliate Pending";
                            $pesan = "Pendaftaran affiliate Anda sedang dalam antrean verifikasi. Mohon tunggu kabar selanjutnya.";
                            break;

                        default:
                            $judul = "Update Affiliate";
                            $pesan = "Ada perubahan status pada pendaftaran affiliate Anda.";
                            break;
                    }

                    // Kirim notifikasi (jika gagal jangan hentikan sistem)
                    try {
                        send_notif(
                            $cekDataAffiliate->user_id,
                            $judul,
                            $pesan,
                            base_url('sw-siswa/affiliatee')
                        );
                    } catch (\Throwable $notifError) {
                        log_message('error', 'Notif gagal dikirim: ' . $notifError->getMessage());
                    }
                }
            }
            // ===============================
            // INSERT
            // ===============================
            else {

                $data['created_at'] = date('Y-m-d H:i:s');

                if (!$this->affiliate->insert($data)) {
                    throw new \Exception('Gagal menyimpan data affiliate.');
                }
            }

            return redirect()
                ->to(base_url('sw-admin/affiliate'))
                ->with('success', 'Data affiliate berhasil disimpan.');
        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function copy()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $paket_id = $this->request->getPost('paket_id');
        $short_code = $this->request->getPost('short_code');
        $kode_affiliate    = $this->request->getPost('kode_affiliate');

        $expiredAt = date('Y-m-d H:i:s', strtotime('+1 months'));

        $linkModel = new AffiliateLinkModel();
        $linkModel->insert([
            'kode_affiliate' => $kode_affiliate,
            'paket_id'      => $paket_id,
            'short_code'    => $short_code,
            'expired_at'    => $expiredAt,
        ]);

        return $this->response->setJSON([
            'status' => 'success'
        ]);
    }

    public function redirect($id, $voucher = null)
    {
        // cek affiliate terlebih dahulu
        $affiliateLink = $this->affiliateLinkModel->where('short_code', $id)->first();
        $affiliate = $this->affiliate->where('kode_affiliate', $affiliateLink['kode_affiliate'])->first();
        $now       = date('Y-m-d H:i:s');
        if ($affiliateLink):
            if ($affiliate['user_id'] != session()->get('id')):
                if ($affiliateLink['expired_at'] > $now) {
                    //expired masih aktif
                    $data = [
                        'short_code' => $affiliateLink['short_code'],
                    ];
                    session()->set($data);
                    return redirect()->to('sw-admin/transaksi/pesan/' . encrypt_url($affiliateLink['paket_id']) . '/' . $voucher);
                } else {
                    //pembelian tanpa affiliate
                    return redirect()->to('sw-admin/transaksi/pesan/' . encrypt_url($affiliateLink['paket_id']) . '/' . $voucher);
                }
            else:
                //pembelian tanpa affiliate
                return redirect()->to('sw-admin/transaksi/pesan/' . encrypt_url($affiliateLink['paket_id']) . '/' . $voucher);
            endif;
        else:
            //pembelian tanpa affiliate
            return redirect()->to('sw-admin/transaksi/pesan/' . encrypt_url($affiliateLink['paket_id']) . '/' . $voucher);
        endif;
    }


    public function listKomisi($id)
    {
        try {

            // Validasi parameter kosong
            if (empty($id)) {
                throw new \Exception('Parameter tidak valid.');
            }

            // Decrypt ID
            $id = decrypt_url($id);

            if (empty($id)) {
                throw new \Exception('Kode affiliate tidak valid.');
            }

            // Ambil data affiliate
            $affiliate = $this->affiliate
                ->where('kode_affiliate', $id)
                ->first();

            if (!$affiliate) {
                throw new \Exception('Data affiliate tidak ditemukan.');
            }

            // Ambil data komisi dengan pagination
            $komisi = $this->komisi
                ->where('kode_affiliate', $id)
                ->orderBy('created_at', 'DESC')
                ->paginate(15, 'komisi');

            $data = [
                'title' => 'Komisi Affiliate',
                'parent_title' => 'List Affiliate',
                'parent_url'   => base_url('sw-admin/affiliate'),
                'affiliate' => $affiliate,
                'komisi'    => $komisi,
                'pager'     => $this->komisi->pager
            ];

            return view('admin/affiliate/komisi', $data);
        } catch (\Throwable $e) {
            return redirect()
                ->to(base_url('sw-admin/affiliate'))
                ->with('error', 'Terjadi kesalahan atau data tidak ditemukan.');
        }
    }

    public function processKomisi()
    {
        $ids = $this->request->getPost('ids');

        if (empty($ids)) {
            return $this->response->setJSON(['status' => 'error']);
        }

        $this->komisi
            ->whereIn('id', $ids)
            ->set([
                'status_penarikan' => 'paid',
                'tgl_pembayaran'   => date('Y-m-d H:i:s')
            ])
            ->update();

        return $this->response->setJSON(['status' => 'success']);
    }
}

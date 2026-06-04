<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;


class TransaksiController extends BaseController
{

    protected $transaksiModel;
    protected $detailPaketModel;
    protected $ujianModel;
    protected $ujianSiswaModel;
    protected $affiliateCommissionModel;
    protected $mapelSiswaModel;
    protected $paketModel;
    protected $siswaModel;
    protected $serviceEmail;
    protected $detailTransaksiModel;
    protected $ikhModel;
    public function __construct()
    {
        $this->transaksiModel = new \App\Models\TransaksiModel();
        $this->detailPaketModel = new \App\Models\DetailPaketModel();
        $this->ujianModel = new \App\Models\UjianModel();
        $this->ujianSiswaModel = new \App\Models\UjianSiswaModel();
        $this->affiliateCommissionModel = new \App\Models\AffiliateCommissionModel();
        $this->mapelSiswaModel = new \App\Models\MapelSiswaModel();
        $this->paketModel = new \App\Models\PaketModel();
        $this->siswaModel = new \App\Models\SiswaModel();
        $this->serviceEmail = new \App\Libraries\Emailer();
        $this->detailTransaksiModel = new \App\Models\DetailTransaksiModel();
        $this->ikhModel  = new \App\Models\IkhModel();
    }
    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'List Transaksi', 'url' => '#'],
        ];
        return view('admin/transaksi/list', $data);
    }
    public function datatables()
    {
        if ($this->request->isAJAX()) {
            try {
                $request = $this->request;
                $start  = (int) $request->getPost('start');
                $length = (int) $request->getPost('length');
                $search = $request->getPost('search')['value'];

                $query = $this->transaksiModel->getBaseQuery();

                if (!empty($search)) {
                    $query->groupStart()
                        ->like('b.nama_siswa', $search)
                        ->orLike('c.nama_paket', $search)
                        ->orLike('transaksi.idtransaksi', $search)
                        ->groupEnd();
                }

                $totalFiltered = $query->countAllResults(false);

                $data = $query->orderBy('transaksi.status', 'asc')
                    ->orderBy('transaksi.tgl_pembayaran', 'desc')
                    ->limit($length, $start)
                    ->get()
                    ->getResultObject();

                $results = [];
                foreach ($data as $s) {
                    $id_enc = encrypt_url($s->idtransaksi);
                    $row = [];

                    // Kolom Peserta
                    $row['peserta'] = '<div class="text-gray-800 fw-bold fs-6">' . esc($s->nama_siswa) . '</div>
                       <div class="text-muted fw-semibold fs-7">' . esc($s->email) . '</div>';

                    // Kolom Paket
                    $row['paket'] = '<div class="text-gray-800 fw-bold fs-6">' . esc($s->nama_paket) . '</div>
                     <div class="text-muted fw-semibold fs-7">' . esc($s->kantor ?? '') . '</div>';

                    // ==========================================
                    // LOGIKA AFFILIATE & KOLOM VOUCHER
                    // ==========================================
                    $is_affiliate = false;
                    $kode_affiliate = $s->kode_affiliate ?? null; // Pastikan field ini ada di query getBaseQuery()

                    // Kondisi: Voucher 8173AF4239 (walau affiliate null/ada) ATAU jika kode_affiliate ada isinya
                    if ($s->kode_voucher === '8173AF4239' || !empty($kode_affiliate)) {
                        $is_affiliate = true;
                    }

                    // Tampilan dasar voucher
                    $html_voucher = $s->kode_voucher
                        ? '<span class="badge badge-light-info fw-bold px-3 py-2">' . esc($s->kode_voucher) . '</span>'
                        : '<span class="text-muted fs-7 fw-semibold">-</span>';

                    // Jika affiliate, tambahkan label info di bawahnya
                    if ($is_affiliate) {
                        $html_voucher .= '<div class="mt-2"><span class="badge badge-light-success fs-8 fw-bold px-2 py-1"><i class="ki-duotone ki-shop fs-7 me-1 text-success"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Link Affiliate</span></div>';
                    }

                    $row['voucher'] = $html_voucher;
                    // ==========================================

                    // Kolom Pembayaran (Format Tanggal)
                    $label_jenis_bayar = '';
                    if ($s->jenis_bayar === 'online') {
                        $label_jenis_bayar = '<div class="text-info fw-semibold fs-7"><i class="ki-duotone ki-credit-cart fs-6 me-1 text-info"><span class="path1"></span><span class="path2"></span></i> Midtrans</div>';
                    } elseif ($s->jenis_bayar === 'manual') {
                        $label_jenis_bayar = '<div class="text-primary fw-semibold fs-7"><i class="ki-duotone ki-wallet fs-6 me-1 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i> Manual Transfer</div>';
                    } else {
                        $label_jenis_bayar = '<div class="text-muted fw-semibold fs-7">Belum memilih</div>';
                    }

                    // Kolom Pembayaran (Format Tanggal + Jenis Bayar)
                    if ($s->tgl_pembayaran) {
                        $date = new \DateTime($s->tgl_pembayaran);
                        $row['pembayaran'] = '<div class="text-gray-800 fw-bold fs-6">' . $date->format('d M Y, H:i') . '</div>' . $label_jenis_bayar;
                    } else {
                        $row['pembayaran'] = '<div class="text-muted fw-semibold fs-6">-</div>' . $label_jenis_bayar;
                    }

                    $diskon         = ($s->nominal * $s->diskon) / 100;
                    $totalDiskon    = $s->nominal - $diskon;
                    $diskon_voucher = ($totalDiskon * $s->voucher) / 100;
                    $nominal        = $s->nominal - $diskon - $diskon_voucher;

                    // Kolom Nominal
                    $row['nominal'] = '<span class="text-primary fw-bold fs-6">Rp ' . number_format($nominal, 0, ',', '.') . '</span>';

                    // Kolom Status (Menggunakan Badge Metronic)
                    if ($s->status === 'S') {
                        $row['status'] = '<div class="text-center"><span class="badge badge-light-success fw-bold px-3 py-2">Lunas</span></div>';
                    } elseif ($s->status === 'P') {
                        $row['status'] = '<div class="text-center"><span class="badge badge-light-primary fw-bold px-3 py-2">Menunggu Pembayaran</span></div>';
                    } elseif ($s->status === 'V') {
                        $row['status'] = '<div class="text-center"><span class="badge badge-light-info fw-bold px-3 py-2">Menunggu Approval</span></div>';
                    } elseif ($s->status === 'E') {
                        $row['status'] = '<div class="text-center"><span class="badge badge-light-danger fw-bold px-3 py-2">Expired</span></div>';
                    } elseif ($s->status === 'M') {
                        $row['status'] = '<div class="text-center"><span class="badge badge-light-warning fw-bold px-3 py-2">Proses Pembayaran</span></div>';
                    } elseif ($s->status === 'DM') {
                        $row['status'] = '<div class="text-center"><span class="badge badge-light-danger fw-bold px-3 py-2">Denied</span></div>';
                    } elseif ($s->status === 'PM') {
                        $row['status'] = '<div class="text-center"><span class="badge badge-light-warning fw-bold px-3 py-2">Pending</span></div>';
                    } else {
                        $row['status'] = '<div class="text-center"><span class="badge badge-light-danger fw-bold px-3 py-2">Expired</span></div>';
                    }

                    // [6] Kolom Aksi (Menggunakan Metronic KTMenu)
                    $row['aksi'] = '
                    <div class="text-center">
                        <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            Aksi <i class="ki-duotone ki-down fs-5 ms-1"></i>
                        </a>
                        
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4 text-start" data-kt-menu="true">
                            
                            <div class="menu-item px-3">
                                <a href="javascript:void(0)" class="menu-link px-3 validasi-transaksi" data-bs-toggle="modal" data-bs-target="#validasi_transaksi" data-transaksi="' . $id_enc . '">
                                    <i class="ki-duotone ki-setting-2 fs-4 me-2 text-gray-500"><span class="path1"></span><span class="path2"></span></i> Detail Transaksi
                                </a>
                            </div>';

                    // Opsi bersyarat berdasarkan status S (Lunas)
                    if ($s->status == 'S') {
                        $row['aksi'] .= '
                            <div class="menu-item px-3">
                                <a href="javascript:void(0)" class="menu-link px-3 text-success invoice_cetak" data-bs-toggle="modal" data-bs-target="#invoice_cetak_modal" data-invoice="' . base_url('sw-admin/transaksi/invoice/' . $id_enc) . '">
                                    <i class="ki-duotone ki-file-down fs-4 me-2 text-success"><span class="path1"></span><span class="path2"></span></i> Unduh Invoice
                                </a>
                            </div>';
                    } else {
                        $row['aksi'] .= '
                            <div class="menu-item px-3">
                                <a href="' . base_url('sw-admin/transaksi/approve-manual/' . $id_enc) . '" class="menu-link px-3 text-primary" id="approve">
                                    <i class="ki-duotone ki-check-square fs-4 me-2 text-primary"><span class="path1"></span><span class="path2"></span></i> Approve Transaksi
                                </a>
                            </div>
                            
                            <div class="separator mt-3 opacity-75"></div>
                            
                            <div class="menu-item px-3 mt-3">
                                <a href="' . base_url('sw-admin/transaksi/hapus-transaksi-siswa/' . $id_enc) . '" class="menu-link px-3 text-danger btn-delete" id="hapus">
                                    <i class="ki-duotone ki-trash fs-4 me-2 text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Hapus Transaksi
                                </a>
                            </div>';
                    }

                    $row['aksi'] .= '
                        </div>
                    </div>';

                    $results[] = $row;
                }

                return $this->response->setJSON([
                    'draw'            => (int) $request->getPost('draw'),
                    'recordsTotal'    => $this->transaksiModel->countAllData(),
                    'recordsFiltered' => $totalFiltered,
                    'data'            => $results,
                    'csrf_hash'       => csrf_hash()
                ]);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'draw'            => 0,
                    'recordsTotal'    => 0,
                    'recordsFiltered' => 0,
                    'data'            => [],
                    'error'           => $e->getMessage()
                ]);
            }
        }
    }
    public function validasiTransaksi()
    {
        if ($this->request->isAJAX()) {
            try {
                $id = decrypt_url($this->request->getVar('idtransaksi'));
                $data = $this->transaksiModel->getById($id);

                if (!$data) throw new \Exception("Data transaksi tidak ditemukan.");

                // Pastikan data dikonversi ke array agar mudah dimanipulasi
                $result = (array) $data;

                // Masukkan CSRF dengan KEY yang benar agar terbaca oleh data[csrfName] di JS
                $result[csrf_token()] = csrf_hash();

                return $this->response->setJSON($result);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    csrf_token() => csrf_hash() // Tetap kirim token baru meski error
                ]);
            }
        }
    }

    public function approveManual($id)
    {
        $db = $this->db;
        $idtransaksi = decrypt_url($id);
        $now = date("Y-m-d H:i:s");

        $db->transBegin();

        try {
            // 1. Update Status Transaksi Utama
            $this->transaksiModel->update($idtransaksi, [
                'status' => 'S',
                'tgl_pembayaran' => $now
            ]);

            // 2. Ambil data transaksi
            $datatransaksi = $this->transaksiModel
                ->join('detail_transaksi', 'detail_transaksi.idtransaksi=transaksi.idtransaksi')
                ->where('transaksi.idtransaksi', $idtransaksi)
                ->where('transaksi.status', 'S') // Pastikan statusnya memang sudah S
                ->groupBy('detail_transaksi.idpaket')
                ->get()->getResultObject();

            if (empty($datatransaksi)) {
                throw new \Exception("Data transaksi tidak ditemukan atau gagal diupdate.");
            }

            // Simpan ID Siswa untuk keperluan notifikasi di luar loop
            $idSiswaTujuan = $datatransaksi[0]->idsiswa;

            foreach ($datatransaksi as $rowst) {
                // Mapping Mapel Siswa
                if ($rowst->idmapel != '0') {
                    $this->mapelSiswaModel->save([
                        'idmapel' => $rowst->idmapel,
                        'idsiswa' => $rowst->idsiswa
                    ]);
                }

                // Ambil Paket
                $datapaket = $this->detailPaketModel
                    ->join('ujian_master', 'detail_paket.id_ujian=ujian_master.id_ujian')
                    ->where('idpaket', $rowst->idpaket)
                    ->groupBy('detail_paket.id_ujian')
                    ->get()->getResultObject();

                foreach ($datapaket as $rowsp) {
                    // Menambah ujian ke siswa
                    $this->ujianModel->save([
                        'id_siswa'   => $rowst->idsiswa,
                        'kode_ujian' => $rowsp->kode_ujian,
                        'nama_ujian' => $rowsp->nama_ujian,
                        'guru'       => $rowsp->guru,
                        'kelas'      => $rowsp->kelas,
                        'mapel'      => $rowsp->mapel,
                        'date_created' => time(),
                    ]);

                    // Reset status ujian siswa jika sebelumnya sudah ada
                    $this->ujianSiswaModel->where('ujian', $rowsp->kode_ujian)
                        ->where('siswa', $rowst->idsiswa)
                        ->set([
                            'jawaban' => null,
                            'benar'   => null,
                            'jam'     => null,
                            'status'  => null,
                        ])->update();
                }
            }

            //untuk jenis paket ikh
            $transaksiMaster = $this->transaksiModel
                ->where('idtransaksi', $idtransaksi)
                ->where('status', 'S')
                ->first();

            if ($transaksiMaster) {
                $jenisPaketArray = json_decode($transaksiMaster['jenis_paket'], true) ?? [];

                if (in_array('ikh', $jenisPaketArray)) {

                    $idsiswa = $transaksiMaster['idsiswa'];

                    // 2. Cek apakah siswa ini sudah punya data di ikhModel
                    $existingIkh = $this->ikhModel->where('id_siswa', $idsiswa)->first();

                    if ($existingIkh) {
                        // --- LOGIKA UPDATE ---
                        // Ambil kuota saat ini dan tambahkan 1
                        $newKuota = (int)$existingIkh['kuota'] + 1;

                        $dataUpdate = [
                            'kuota'  => $newKuota // Kuota bertambah
                        ];

                        // Update berdasarkan ID primary key tabel IKH
                        $this->ikhModel->update($existingIkh['id_ikh'], $dataUpdate);
                    } else {
                        // --- LOGIKA INSERT BARU ---
                        $dataInsert = [
                            'id_siswa'            => $idsiswa,
                            'is_riwayat_hidup'    => '1',
                            'is_bukan_pns'        => '1',
                            'is_pakta_integritas' => '1',
                            'is_pernyataan_ikh'   => '1',
                            'kuota'               => '1' // Kuota awal
                        ];

                        $this->ikhModel->insert($dataInsert);
                    }
                }
            }

            // 3. Update Afiliasi
            $this->affiliateCommissionModel->where('id_transaksi', $idtransaksi)
                ->set([
                    'status'           => 'approved',
                    'tgl_approved'     => $now,
                    'status_penarikan' => 'pending'
                ])->update();

            $db->transCommit();

            // 4. Kirim Notifikasi (Gunakan variabel $idSiswaTujuan yang sudah aman)
            send_notif($idSiswaTujuan, "Verifikasi pembayaran", "Pembelian paket berhasil.", base_url('sw-siswa/transaksi'));

            $dataSiswa = $this->siswaModel->find($idSiswaTujuan);
            if ($dataSiswa) {
                $this->serviceEmail->send(
                    $dataSiswa['email'],
                    "Verifikasi Pembayaran - KelasBrevet",
                    "Halo <b>{$dataSiswa['nama_siswa']}</b>,<br>Pembayaran Anda telah kami verifikasi. Selamat belajar!"
                );
            }

            return redirect()->to('sw-admin/transaksi')->with('success', 'Pembayaran berhasil diverifikasi.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function approveTransaksi()
    {
        if (session()->get('role') != 1) return redirect()->to('auth');

        $db = $this->db;
        $db->transBegin(); // Menggunakan Database Transaction agar aman

        try {
            $idTransaksi = $this->request->getVar('idtransaksi');
            $status = $this->request->getVar('status');
            $idsiswa = $this->request->getVar('idsiswa');

            if ($status != "P") {
                // Update Status Transaksi
                $this->transaksiModel->update($idTransaksi, ['status' => $status]);

                // Logic Penambahan Ujian ke Siswa
                $datatransaksi = $this->transaksiModel->where('idtransaksi', $idTransaksi)->where('status', 'S')->first();

                if ($datatransaksi) {
                    $datapaket = $this->detailPaketModel->join('ujian_master', 'detail_paket.id_ujian=ujian_master.id_ujian')
                        ->where('idpaket', $datatransaksi->idpaket)
                        ->groupBy('detail_paket.id_ujian')
                        ->findAll();

                    foreach ($datapaket as $row) {
                        $this->ujianModel->save([
                            'id_siswa'   => $idsiswa,
                            'kode_ujian' => $row->kode_ujian,
                            'nama_ujian' => $row->nama_ujian,
                            'guru'       => $row->guru,
                            'kelas'      => $row->kelas,
                            'mapel'      => $row->mapel,
                            'date_created' => time(),
                        ]);
                    }
                }

                // Kirim Email (Disederhanakan)
                $this->serviceEmail->send(
                    $this->request->getVar('email'),
                    "Verifikasi Pembayaran - KelasBrevet",
                    "Pembayaran Anda telah berhasil diverifikasi."
                );
            } else {
                // Logic Penolakan / Expired
                $tgl_exp = date('Y-m-d H:i:s', strtotime('+1 day'));
                $this->transaksiModel->update($idTransaksi, [
                    'status' => $status,
                    'tgl_exp' => $tgl_exp,
                    'tgl_pembayaran' => null,
                    'bukti_pembayaran' => null,
                    'keterangan' => $this->request->getVar('keterangan'),
                ]);

                // Hapus Thumbnail jika ada
                $path = './uploads/transaksi/thumbnails/' . $this->request->getVar('bukti_pembayaran');
                if (file_exists($path)) unlink($path);
            }

            $db->transCommit();
            return redirect()->to('sw-admin/transaksi')->with('success', 'Transaksi berhasil diproses.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function hapusTransaksiSiswa($id)
    {
        $idtransaksi = decrypt_url($id);
        $dataDrop  =   $this->db->query("select * from transaksi where idtransaksi='$idtransaksi'")->getRow();
        if ($dataDrop != null) {
            //untuk menghapus file bukti transaksi
            if ($dataDrop->bukti_pembayaran != '') {
                if (file_exists('./uploads/transaksi/thumbnails/' . $dataDrop->bukti_pembayaran)) {
                    unlink('./uploads/transaksi/thumbnails/' . $dataDrop->bukti_pembayaran);
                };
            }
            $data = $this->detailTransaksiModel->where('idtransaksi', $dataDrop->idtransaksi)->get()->getResultObject();
            foreach ($data as $rows) {
                $this->detailTransaksiModel->delete($rows->iddetailtransaksi);
            }
            $this->transaksiModel->delete($dataDrop->idtransaksi);
        }
        return redirect()->to('sw-admin/transaksi')->with('success', 'Transaksi berhasil dibatalkan');
    }

    public function hapusTransaksi()
    {
        // Ambil SEMUA transaksi yang memenuhi kriteria kedaluwarsa
        $dataDrop = $this->db->query("SELECT * FROM transaksi WHERE status IN ('P', 'M','PM', 'DM', 'E') AND tgl_drop <= NOW()")->getResultObject();

        // Cek apakah ada data yang perlu dihapus
        if (!empty($dataDrop)) {

            $berhasil = 0;
            $gagal = 0;

            // Looping setiap transaksi yang kedaluwarsa
            foreach ($dataDrop as $transaksi) {

                // 1. Mulai transaksi database untuk SATU idtransaksi ini saja
                $this->db->transBegin();

                try {
                    // Ambil data detail transaksi (child)
                    $details = $this->detailTransaksiModel->where('idtransaksi', $transaksi->idtransaksi)->get()->getResultObject();

                    // Hapus semua child
                    foreach ($details as $detail) {
                        $this->detailTransaksiModel->delete($detail->iddetailtransaksi);
                    }

                    // Hapus parent (transaksi utama)
                    $this->transaksiModel->delete($transaksi->idtransaksi);

                    // Cek status query di background
                    if ($this->db->transStatus() === false) {
                        throw new \Exception('Query gagal dieksekusi oleh database.');
                    }

                    // Jika sukses, simpan perubahan untuk transaksi ini
                    $this->db->transCommit();
                    $berhasil++;
                } catch (\Exception $e) {
                    // Jika error, kembalikan data HANYA untuk transaksi ini
                    $this->db->transRollback();
                    $gagal++;
                }
            }
            echo "Proses selesai. Berhasil: $berhasil, Gagal: $gagal.";
        }
    }
}

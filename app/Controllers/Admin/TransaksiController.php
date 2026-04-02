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
    }
    public function index()
    {
        $data = [
            'title' => 'Data Transaksi',
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
                    $row['peserta'] = '<div class="font-weight-bold">' . esc($s->nama_siswa) . '</div>
                                   <div class="text-muted small">' . esc($s->email) . '</div>';

                    // Kolom Paket
                    $row['paket'] = '<div class="font-weight-bold">' . esc($s->nama_paket) . '</div>
                                 <div class="text-muted small">' . esc($s->kota_intansi ?? '') . '</div>';

                    // Kolom Voucher
                    $row['voucher'] = $s->kode_voucher
                        ? '<span class="badge badge-info">' . esc($s->kode_voucher) . '</span>'
                        : '<span class="text-muted small">-</span>';

                    // Kolom Pembayaran (Format Tanggal)
                    if ($s->tgl_pembayaran) {
                        $date = new \DateTime($s->tgl_pembayaran);
                        $row['pembayaran'] = $date->format('d M Y, H:i');
                    } else {
                        $row['pembayaran'] = '<span class="text-muted small">-</span>';
                    }
                    $diskon         = ($s->nominal * $s->diskon) / 100;
                    $totalDiskon    = $s->nominal - $diskon;
                    $diskon_voucher = ($totalDiskon * $s->voucher) / 100;
                    $nominal = $s->nominal - $diskon - $diskon_voucher;

                    // Kolom Nominal
                    $row['nominal'] = '<span class="font-weight-bold text-primary">Rp ' . number_format($nominal, 0, ',', '.') . '</span>';

                    // Kolom Status
                    if ($s->status === 'S') {
                        $row['status'] = '<div class="text-center"><span class="badge badge-success">Lunas</span></div>';
                    } elseif ($s->status === 'P') {
                        $row['status'] = '<div class="text-center"><span class="badge badge-warning">Menunggu Pembayaran</span></div>';
                    } elseif ($s->status === 'V') {
                        $row['status'] = '<div class="text-center"><span class="badge badge-warning">Menunggu Approved</span></div>';
                    } elseif ($s->status === 'E') {
                        $row['status'] = '<div class="text-center"><span class="badge badge-danger">Expired</span></div>';
                    } elseif ($s->status === 'M') {
                        $row['status'] = '<div class="text-center"><span class="badge badge-warning">Proses Pembayaran</span></div>';
                    } elseif ($s->status === 'DM') {
                        $row['status'] = '<div class="text-center"><span class="badge badge-danger">Denied</span></div>';
                    } elseif ($s->status === 'PM') {
                        $row['status'] = '<div class="text-center"><span class="badge badge-warning">Pending</span></div>';
                    } else {
                        $row['status'] = '<div class="text-center"><span class="badge badge-danger">Expired</span></div>';
                    }

                    // [6] Kolom Aksi (Dropdown)
                    $row['aksi'] = '<div class="dropdown custom-dropdown text-center">
                                <a class="dropdown-toggle" href="javascript:void(0)" role="button" data-toggle="dropdown" id="drop' . $s->idtransaksi . '">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="drop' . $s->idtransaksi . '">
                                    <a class="dropdown-item validasi-transaksi" href="javascript:void(0)" data-toggle="modal" data-target="#validasi_transaksi" data-transaksi="' . $id_enc . '">
                                        <i class="bi bi-gear mr-2"></i> Detail Transaksi
                                    </a>';

                    if ($s->status == 'S') {
                        $row['aksi'] .= '<a class="dropdown-item invoice_cetak text-success" href="javascript:void(0)" data-toggle="modal" data-target="#invoice_cetak_modal" data-invoice="' . base_url('sw-admin/transaksi/invoice/' . $id_enc) . '">
                        <i class="bi bi-download mr-2"></i> Unduh Invoice
                    </a>';
                    } else {
                        $row['aksi'] .= '<a class="dropdown-item text-primary" id="approve" href="' . base_url('sw-admin/transaksi/approve-manual/' . $id_enc) . '">
                        <i class="bi bi-check-square mr-2"></i> Approve Transaksi
                    </a>
                    <a class="dropdown-item text-danger btn-delete" id="hapus" href="' . base_url('sw-admin/transaksi/hapus-transaksi-siswa/' . $id_enc) . '">
                        <i class="bi bi-trash mr-2"></i> Hapus
                    </a>';
                    }

                    $row['aksi'] .= '</div></div>';

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
                    'draw' => 0,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => $e->getMessage()
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
                    "Verifikasi Akun - KelasBrevet",
                    "Terima kasih telah mendaftar di KelasBrevet."
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

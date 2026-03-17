<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\SiswaModel;
use App\Models\GuruModel;
use App\Models\MitraModel;
use App\Models\PicModel;
use App\Models\MapelModel;
use App\Models\KelasModel;
use App\Models\TransaksiModel;
use App\Models\UjianMasterModel;
use App\Models\UjianModel;



class HomeController extends BaseController
{
    protected $adminModel;
    protected $guruModel;
    protected $siswaModel;
    protected $kelasModel;
    protected $mapelModel;
    protected $mitraModel;
    protected $picModel;
    protected $ujianModel;
    protected $ujianMasterModel;
    protected $transaksiModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
        $this->guruModel = new GuruModel();
        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();
        $this->mapelModel = new MapelModel();
        $this->mitraModel = new MitraModel();
        $this->picModel = new PicModel();
        $this->ujianModel = new UjianModel();
        $this->ujianMasterModel = new UjianMasterModel();
        $this->transaksiModel = new TransaksiModel();
    }

    public function index()
    {
        $data['guru'] = $this->guruModel->asObject()->findAll();
        $data['guru_aktif'] = $this->guruModel
            ->where('is_active', 1)
            ->get()->getResultObject();
        $data['guru_tidak_aktif'] = $this->guruModel
            ->where('is_active', 0)
            ->get()->getResultObject();

        $data['siswa'] = $this->siswaModel->asObject()->findAll();
        $data['siswa_aktif'] = $this->siswaModel
            ->where('is_active', 1)
            ->get()->getResultObject();

        $data['siswa_tidak_aktif'] = $this->siswaModel
            ->where('is_active', 0)
            ->get()->getResultObject();

        $data['mitra'] = $this->mitraModel
            ->where('is_active', 1)
            ->get()->getResultObject();

        $data['pic'] = $this->picModel
            ->where('is_active', 1)
            ->get()->getResultObject();

        $data['transaksi'] = $this->transaksiModel
            // ->select('count(idtransaksi) as transaksi, sum(nominal) as nominal, diskon, voucher')
            ->where('status', 'S')
            ->get()->getResultObject();


        $sertifikatAB = $this->siswaModel->getSertifikatAB();
        $no = 1;
        $lulus = 0;
        foreach ($sertifikatAB as $s) {
            $ujian = $this->ujianModel->getAllByKelasSertifikat($s->kelas, $s->id_siswa);
            foreach ($ujian as $u) {
                $data['ujian'][] = $u;
            }

            $dataUjian = $this->ujianMasterModel->where('kelas', $s->kelas)->groupBy('mapel')->get()->getResultObject();
            $total = 0;
            foreach ($dataUjian as $rr) {
                $total++;
            }

            $totalUjian = $this->ujianModel->where('kelas', $s->kelas)->where('id_siswa', $s->id_siswa)
                ->where('ujian.nilai >=', 60)
                ->groupBy('ujian.mapel')->get()->getResultObject();
            $totalSertifikat = 0;
            foreach ($totalUjian as $r) {
                $totalSertifikat++;
            }

            if ($total != 0) {
                if ($totalSertifikat >= $total) {
                    $lulus += $no;
                }
            }
        }
        $data['peserta_lulus'] = $lulus;

        $data['mapel'] = $this->mapelModel->asObject()->findAll();

        return view('admin/dashboard', $data);
    }
}

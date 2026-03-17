<?= $this->extend('landing/template'); ?>
<?= $this->section('content'); ?>
<?php 
    $dasarHukum = array(
        'Undang-undang Nomor 13 Tahun 2003 tentang Ketenagakerjaan',
        'Peraturan Pemerintah Nomor 31 Tahun 2006 tentang Sistem Pelatihan Kerja Nasional',
        'Peraturan Presiden Nomor 8 Tahun 2012 tentang Kerangka Kualifikasi Nasional Indonesia',
        'Peraturan Menteri Tenaga Kerja dan Transmigrasi Nomor 5 Tahun 2012 tentang Sistem Standardisasi Kompetensi Kerja Nasional',
        'Keputusan Menteri Tenaga Kerja dan Transmigrasi Nomor 347 Tahun 2014 Tentang Penetapan Standar Kompetensi Kerja Nasional Indonesia Kategori Jasa Profesional, Ilmiah dan Teknisi Golongan Pokok Perpajakan Bidang Teknis Pelaksanaan Hak dan Kewajiban Perpajakan'
    ); 
    $unitKompetensi = array(
        'M.692000.001.01 Menyiapkan Pendaftaran Nomor Pokok Wajib Pajak (NPWP)',
        'M.692000.002.01 Menyiapkan Pendaftaran Pengukuhan Pengusaha Kena Pajak',
        'M.692000.006.01 Mengisi dan Penyerahan Formulir Pendaftaran',
        'M.692000.041.01 Memperoleh Tanda Bukti Sebagai Wajib Pajak',
        'M.692000.042.01 Memperoleh Tanda Bukti Sebagai Pengusaha Kena Pajak',
        'M.692000.007.01 Menyiapkan Perubahan Data Wajib Pajak',
        'M.692000.048.01 Mengajukan Perubahan Data Wajib Pajak',
        'M.692000.050.01 Mengajukan Perubahan Data Pengusaha Kena Pajak',
        'M.692000.052.01 Mengajukan Penghapusan dan Pencabutan NPWP',
        'M.692000.053.01 Mengajukan Penghapusan dan Pencabutan Pengukuhan PKP',
        'M.692000.010.01 Menentukan Dasar Pengenaan Pajak',
        'M.692000.011.01 Menghitung Pajak Terutang',
        'M.692000.015.01 Menyiapkan dan Mengisi Surat Setoran Pajak (SSP)',
        'M.692000.020.01 Melakukan Pembayaran atau Penyetoran Pajak',
        'M.692000.022.01 Menyiapkan dan Mengisi Surat Pemberitahuan (SPT)',
        'M.692000.060.01 Mengajukan Perpanjangan Batas Waktu SPT',
        'M.692000.026.01 Menyampaikan Surat Pemberitahuan (SPT)',
        'M.692000.059.01 Mengajukan Pembetulan Surat Pemberitahuan (SPT)',
        'M.692000.061.01 Mengajukan Kompensasi',
        'M.692000.071.01 Mengajukan Permohonan Pengurangan, Keringanan, Pembatalan, Penghapusan Sanksi Administrasi',
    );
    
    //skema
    $pphOrangPribadi = array(
        'M.692000.001.01 Menyiapkan Pendaftaran Nomor Pokok Wajib Pajak (NPWP)',
        'M.692000.006.01 Mengisi dan Penyerahan Formulir Pendaftaran',
        'M.692000.041.01 Memperoleh Tanda Bukti Sebagai Wajib Pajak',
        'M.692000.007.01 Menyiapkan Perubahan Data Wajib Pajak',
        'M.692000.010.01 Menentukan Dasar Pengenaan Pajak',
        'M.692000.011.01 Menghitung Pajak Terutang',
        'M.692000.022.01 Menyiapkan dan Mengisi Surat Pemberitahuan (SPT)',
        'M.692000.015.01 Menyiapkan dan Mengisi Surat Setoran Pajak (SSP)',
        'M.692000.060.01 Mengajukan Perpanjangan Batas Waktu Penyampaian SPT',
        'M.692000.020.01 Melakukan Pembayaran atau Penyetoran Pajak',
        'M.692000.026.01 Menyampaikan Surat Pemberitahuan (SPT)',
        'M.692000.059.01 Mengajukan Pembetulan Surat Pemberitahuan (SPT)',
        'M.692000.071.01 Mengajukan Permohonan Pengurangan, Keringanan, Pembatalan, Penghapusan Sanksi Administrasi',
    );
    $pphBadan = array(
        'M.692000.001.01 Menyiapkan Pendaftaran Nomor Pokok Wajib Pajak (NPWP)',
        'M.692000.006.01 Mengisi dan Penyerahan Formulir Pendaftaran',
        'M.692000.041.01 Memperoleh Tanda Bukti Sebagai Wajib Pajak',
        'M.692000.007.01 Menyiapkan Perubahan Data Wajib Pajak',
        'M.692000.048.01 Mengajukan Perubahan Data Wajib Pajak',
        'M.692000.052.01 Mengajukan Penghapusan dan Pencabutan NPWP',
        'M.692000.010.01 Menentukan Dasar Pengenaan Pajak',
        'M.692000.011.01 Menghitung Pajak Terutang',
        'M.692000.022.01 Menyiapkan dan Mengisi Surat Pemberitahuan (SPT)',
        'M.692000.015.01 Menyiapkan dan Mengisi Surat Setoran Pajak (SSP)',
        'M.692000.060.01 Mengajukan Perpanjangan Batas Waktu Penyampaian SPT',
        'M.692000.020.01 Melakukan Pembayaran Atau Penyetoran Pajak',
        'M.692000.026.01 Menyampaikan Surat Pemberitahuan (SPT)',
        'M.692000.059.01 Mengajukan Pembetulan Surat Pemberitahuan (SPT)',
        'M.692000.071.01 Mengajukan Permohonan Pengurangan, Keringanan, Pembatalan, Penghapusan Sanksi Administrasi',
    );
    
    $pajakPenghasilanPasal21 = array(
        'M.692000.010.01 Menentukan Dasar Pengenaan Pajak',
        'M.692000.011.01 Menghitung Pajak Terutang',
        'M.692000.022.01 Menyiapkan dan Mengisi Surat Pemberitahuan (SPT)',
        'M.692000.015.01 Menyiapkan dan Mengisi Surat Setoran Pajak (SSP)',
        'M.692000.020.01 Melakukan Pembayaran Atau Penyetoran Pajak',
        'M.692000.026.01 Menyampaikan Surat Pemberitahuan (SPT)',
        'M.692000.059.01 Mengajukan Pembetulan Surat Pemberitahuan (SPT)',
        'M.692000.061.01 Mengajukan Kompensasi',
    );
    $pajakPenghasilanPotPut = array(
        'M.692000.010.01 Menentukan Dasar Pengenaan Pajak',
        'M.692000.011.01 Menghitung Pajak Terutang',
        'M.692000.022.01 Menyiapkan dan Mengisi Surat Pemberitahuan (SPT)',
        'M.692000.015.01 Menyiapkan dan Mengisi Surat Setoran Pajak (SSP)',
        'M.692000.020.01 Melakukan Pembayaran atau Penyetoran Pajak',
        'M.692000.026.01 Menyampaikan Surat Pemberitahuan (SPT)',
        'M.692000.059.01 Mengajukan Pembetulan Surat Pemberitahuan (SPT)',
    );
    $ppn = array(
        'M.692000.002.01 Menyiapkan Pendaftaran Pengukuhan Pengusaha Kena Pajak',
        'M.692000.006.01 Mengisi dan Penyerahan Formulir Pendaftaran',
        'M.692000.042.01 Memperoleh Tanda Bukti Sebagai Pengusaha Kena Pajak',
        'M.692000.050.01 Mengajukan Perubahan Data Pengusaha Kena Pajak',
        'M.692000.053.01 Mengajukan Penghapusan dan Pencabutan Pengukuhan PKP',
        'M.692000.007.01 Menyiapkan Perubahan Data Wajib Pajak',
        'M.692000.010.01 Menentukan Dasar Pengenaan Pajak',
        'M.692000.011.01 Menghitung Pajak Terutang',
        'M.692000.022.01 Menyiapkan dan Mengisi Surat Pemberitahuan (SPT)',
        'M.692000.015.01 Menyiapkan dan Mengisi Surat Setoran Pajak (SSP)',
        'M.692000.020.01 Melakukan Pembayaran atau Penyetoran Pajak',
        'M.692000.026.01 Menyampaikan Surat Pemberitahuan (SPT)',
        'M.692000.059.01 Mengajukan Pembetulan Surat Pemberitahuan (SPT)',
        'M.692000.061.01 Mengajukan Kompensasi',

    );
?>  


<div class="section mb-4 " id="">
    <div class="container">
        <div class="row siap-kerja">
            <div class="col-12 col-md-12">
                <div class="row">
                    <div class="col-12 col-md-8">
                        <h5 class="fw-bold d-flex justify-content-start">SAMBUTAN KETUA LEMBAGA PELATIHAN KERJA</h5>
                        <div class="mt-2">
                            <h6 class="mt-4 mb-4">Assalamualaikum Warahmatullahi Wabarakatuh</h6>
                            <p class="MsoNormal" style="margin-bottom:0cm;text-align:justify;text-indent:1.0cm;line-height:200%;mso-layout-grid-align:none;text-autospace:none">
                                <span lang="EN-US">
                                    Dalam rangka menyiapkan tenaga kerja yang memiliki
                                    keahlian teknis di bidang perpajakan, Lembaga Kursus dan Pelatihan (LKP)
                                    Akuntanmu</span><span lang="EN-US"> Learning Center berkolaborasi bersama Lembaga Pelatihan Kerja (LPK) Akuntanmu
                                    By Legalyn Konsultan Indonesia. Dengan tujuan mencetak tenaga kerja yang
                                    memenuhi syarat (<i>qualified</i>) sebagai teknisi di bidang perpajakan, pihak
                                    lembaga menerapkan pelatihan dengan Standar Kompetensi Kerja Nasional Indonesia
                                    (SKKNI).&nbsp;
                                </span>
                            </p>
                            <p class="MsoNormal" style="margin-bottom:0cm;text-align:justify;text-indent:1.0cm;line-height:200%;mso-layout-grid-align:none;text-autospace:none">
                                <span lang="EN-US">Peserta pelatihan diarahkan untuk langsung berpraktik
                                    dengan dokumen-dokumen simulasi kerja yang umumnya digunakan pada
                                    usaha/industri. Dengan demikian, peserta akan lebih mudah untuk menerapkan
                                    pengetahuan teoritisnya ke dalam praktik kerja. Setelah masa pelatihan
                                    berakhir, peserta akan diuji keahlianya dengan skema ujian langsung dan
                                    penugasan. Apabila peserta berhasil melewati nilai minimal yang telah
                                    ditetapkan, maka lembaga pelatihan kerja akan menerbitkan sertifikat yang
                                    menyatakan bahwa peserta telah memenuhi syarat (<i>qualified</i>) sebagai
                                    teknisi pelaporan pajak dan lembaga memberikan apresiasi dengan sebutan <i>Qualified
                                    Tax Reporting Technician (QTRT)</i>.
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mt-4 mb-4">
                        <img width="100%" height="auto" class="zoom" src="https://kelasbrevet.com/uploads/siapkerja/nurtiyas.jpg">
                    </div>
                    <div class="col-12 col-md-12">
                        <p class="MsoNormal" style="margin-bottom:0cm;text-align:justify;text-indent:1.0cm;line-height:200%;mso-layout-grid-align:none;text-autospace:none">
                            <span lang="EN-US">
                                Pengakuan <i>Qualified Tax Reporting Technician</i>
                                (QTRT) akan berlaku dihadapan lembaga dan para mitra lembaga secara nasional.
                                Namun tidak terbatas pada pengakuan lembaga, pihak lain yang memiliki
                                kepercayaan pada lembaga dapat turut serta memberi pengakuan secara mandiri
                                untuk mengapresiasi pencapaian peserta pelatihan dalam menempuh pelatihan
                                teknisi pelaporan pajak.
                            </span>
                        </p>
                        <p class="MsoNormal" style="margin-bottom:0cm;text-align:justify;text-indent:1.0cm;line-height:200%;mso-layout-grid-align:none;text-autospace:none">
                            <span lang="EN-US">Lembaga juga telah mendaftarkan merek <i>Qualified Tax
                                Reporting Technician (QTRT) </i>pada Direktorat Jenderal Kekayaan Intelektual
                                (DJKI) dengan nomor Berita Resmi Merek: BRM2550A yang telah dipublikasi pada 5
                                Maret 2025. Sebagai pemegang merek QTRT, lembaga secara resmi dan sah
                                menggunakan sepenuhnya sebutan QTRT dalam ruang lingkup aktifitas yang telah
                                ditentukan.
                            </span>
                        </p>
                        <p class="MsoNormal" style="margin-bottom:0cm;text-align:justify;text-indent:1.0cm;line-height:200%;mso-layout-grid-align:none;text-autospace:none">
                            <span lang="EN-US">Lebih jauh dari sekedar memberi sebutan <i>Qualified Tax Reporting Technician</i>,
                                lembaga berupaya menjaga kualitas pelatihan maupun ujian secara objektif dan
                                profesional untuk memastikan bahwa peserta yang telah selesai mengikuti
                                pelatihan benar-benar siap untuk melakukan pekerjaan sebagai teknisi pelaporan
                                pajak.
                            </span>
                        </p>
                        <p class="MsoNormal mt-4" style="margin-bottom:0cm;text-align:justify;line-height:150%;mso-layout-grid-align:none;text-autospace:none">
                            <span lang="EN-US">Wassalamu’alaikum Warahmatullahi Wabarakatuh</span>
                        </p>
                        <p class="MsoNormal mt-4" style="margin-bottom:0cm;text-align:justify;line-height:150%;mso-layout-grid-align:none;text-autospace:none">
                            <span lang="EN-US">Ketua Lembaga Pelatihan Kerja</span>
                        </p>
                        <p class="MsoNormal" style="margin-bottom:0cm;text-align:justify;line-height:150%;mso-layout-grid-align:none;text-autospace:none">
                            <span lang="EN-US">Akuntanmu By Legalyn Konsultan Indonesia</span>
                        </p>
                        <p class="MsoNormal" style="margin-bottom:0cm;text-align:justify;line-height:150%;mso-layout-grid-align:none;text-autospace:none">
                            <span lang="EN-US">Nurtiyas, S.E., M.Ak., BKP</span>
                        </p>
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="accordion mt-4" id="accordionSiapKerja">
                            <div class="accordion-item" >
                                <h2 class="accordion-header">
                                  <button class="accordion-button bg-primary-subtle" type="button" data-bs-toggle="collapse" data-bs-target="#dasarHukum" aria-expanded="true" aria-controls="dasarHukum">
                                    <table width="100%">
                                        <thead>
                                            <tr class="text-peraturan">
                                                <td width="3%">A. </td>
                                                <td>DASAR HUKUM</td>
                                            </tr>
                                        </thead>
                                    </table>
                                  </button>
                                </h2>
                                <div id="dasarHukum" class="accordion-collapse collapse show " data-bs-parent="#accordionSiapKerja">
                                  <div class="accordion-body">
                                      <table>
                                          <thead>
                                                <?php foreach($dasarHukum as $key=>$item): ?>
                                                  <tr>
                                                      <td><i class="bi bi-dot me-1 fs-4" width="5%"></i></td>
                                                      <td><p><?= $item?></p></td>
                                                  </tr>
                                                <?php endforeach; ?>
                                          </thead>
                                      </table>
                                  </div>
                                </div>
                            </div>
                            <div class="accordion-item" >
                                <h2 class="accordion-header">
                                  <button class="accordion-button bg-warning-subtle collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#unitKompetensi" aria-expanded="true" aria-controls="unitKompetensi">
                                    <table width="100%">
                                        <thead>
                                            <tr class="text-peraturan">
                                                <td width="3%">B. </td>
                                                <td>DAFTAR UNIT KOMPETENSI</td>
                                            </tr>
                                        </thead>
                                    </table>
                                  </button>
                                </h2>
                                <div id="unitKompetensi" class="accordion-collapse collapse " data-bs-parent="#accordionSiapKerja">
                                  <div class="accordion-body table-responsive-sm">
                                      <table>
                                          <thead>
                                                <?php foreach($unitKompetensi as $key=>$item): ?>
                                                  <tr>
                                                      <td width="4%"><?=$key+1 ?>.</td>
                                                      <td><p><?= $item?></p></td>
                                                  </tr>
                                                <?php endforeach; ?>
                                          </thead>
                                      </table>
                                  </div>
                                </div>
                            </div>
                            <div class="accordion-item" >
                                <h2 class="accordion-header">
                                  <button class="accordion-button bg-primary-subtle collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pelatihanKerja" aria-expanded="true" aria-controls="pelatihanKerja">
                                    <table width="100%">
                                        <thead>
                                            <tr class="text-peraturan">
                                                <td width="3%">C. </td>
                                                <td>SKEMA PELATIHAN KERJA</td>
                                            </tr>
                                        </thead>
                                    </table>
                                  </button>
                                  </button>
                                </h2>
                                <div id="pelatihanKerja" class="accordion-collapse collapse " data-bs-parent="#accordionSiapKerja">
                                    <div class="accordion-body table-responsive-sm">
                                        <div class="accordion mt-4" id="accordionPelatihanKerja">
                                            <div class="accordion-item" >
                                                <h2 class="accordion-header">
                                                  <button class="accordion-button bg-danger-subtle" type="button" data-bs-toggle="collapse" data-bs-target="#pphOrangPribadi" aria-expanded="true" aria-controls="pphOrangPribadi">
                                                    <table width="100%">
                                                        <thead>
                                                            <tr class="text-peraturan">
                                                                <td width="3%">C.1</td>
                                                                <td>Teknisi Perpajakan – Pajak Penghasilan Orang Pribadi</td>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                  </button>
                                                </h2>
                                                <div id="pphOrangPribadi" class="accordion-collapse collapse show " data-bs-parent="#accordionPelatihanKerja">
                                                    <div class="accordion-body">
                                                        <table>
                                                            <thead>
                                                                <?php foreach($pphOrangPribadi as $key=>$item): ?>
                                                                  <tr>
                                                                      <td width="4%"><?=$key+1 ?>.</td>
                                                                      <td><p><?= $item?></p></td>
                                                                  </tr>
                                                                <?php endforeach; ?>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item" >
                                                <h2 class="accordion-header">
                                                  <button class="accordion-button bg-secondary-subtle" type="button" data-bs-toggle="collapse" data-bs-target="#pphBadan" aria-expanded="true" aria-controls="pphBadan">
                                                    <table width="100%">
                                                        <thead>
                                                            <tr class="text-peraturan">
                                                                <td width="3%">C.2</td>
                                                                <td>Teknisi Perpajakan – Pajak Penghasilan Badan (Sektor Jasa dan Dagang)</td>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                  </button>
                                                </h2>
                                                <div id="pphBadan" class="accordion-collapse collapse " data-bs-parent="#accordionPelatihanKerja">
                                                    <div class="accordion-body">
                                                        <table>
                                                            <thead>
                                                                <?php foreach($pphBadan as $key=>$item): ?>
                                                                  <tr>
                                                                      <td width="4%"><?=$key+1 ?>.</td>
                                                                      <td><p><?= $item?></p></td>
                                                                  </tr>
                                                                <?php endforeach; ?>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item" >
                                                <h2 class="accordion-header">
                                                  <button class="accordion-button bg-danger-subtle" type="button" data-bs-toggle="collapse" data-bs-target="#pajakPenghasilanPasal21" aria-expanded="true" aria-controls="pajakPenghasilanPasal21">
                                                    <table width="100%">
                                                        <thead>
                                                            <tr class="text-peraturan">
                                                                <td width="3%">C.3</td>
                                                                <td>Teknisi Perpajakan – Pajak Penghasilan Pasal 21</td>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                  </button>
                                                </h2>
                                                <div id="pajakPenghasilanPasal21" class="accordion-collapse collapse " data-bs-parent="#accordionPelatihanKerja">
                                                    <div class="accordion-body">
                                                        <table>
                                                            <thead>
                                                                <?php foreach($pajakPenghasilanPasal21 as $key=>$item): ?>
                                                                  <tr>
                                                                      <td width="4%"><?=$key+1 ?>.</td>
                                                                      <td><p><?= $item?></p></td>
                                                                  </tr>
                                                                <?php endforeach; ?>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item" >
                                                <h2 class="accordion-header">
                                                  <button class="accordion-button bg-secondary-subtle" type="button" data-bs-toggle="collapse" data-bs-target="#pajakPenghasilanPotPut" aria-expanded="true" aria-controls="pajakPenghasilanPotPut">
                                                    <table width="100%">
                                                        <thead>
                                                            <tr class="text-peraturan">
                                                                <td width="3%">C.4</td>
                                                                <td>Teknisi Perpajakan – Pajak Penghasilan Potput (Unifikasi)</td>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                  </button>
                                                </h2>
                                                <div id="pajakPenghasilanPotPut" class="accordion-collapse collapse " data-bs-parent="#accordionPelatihanKerja">
                                                    <div class="accordion-body">
                                                        <table>
                                                            <thead>
                                                                <?php foreach($pajakPenghasilanPotPut as $key=>$item): ?>
                                                                  <tr>
                                                                      <td width="4%"><?=$key+1 ?>.</td>
                                                                      <td><p><?= $item?></p></td>
                                                                  </tr>
                                                                <?php endforeach; ?>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item" >
                                                <h2 class="accordion-header">
                                                  <button class="accordion-button bg-danger-subtle" type="button" data-bs-toggle="collapse" data-bs-target="#ppnPpnbm" aria-expanded="true" aria-controls="ppnPpnbm">
                                                    <table width="100%">
                                                        <thead>
                                                            <tr class="text-peraturan">
                                                                <td width="3%">C.5</td>
                                                                <td>Teknisi Perpajakan – PPN dan PPnBM</td>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                  </button>
                                                </h2>
                                                <div id="ppnPpnbm" class="accordion-collapse collapse " data-bs-parent="#accordionPelatihanKerja">
                                                    <div class="accordion-body">
                                                        <table>
                                                            <thead>
                                                                <?php foreach($ppn as $key=>$item): ?>
                                                                  <tr>
                                                                      <td width="4%"><?=$key+1 ?>.</td>
                                                                      <td><p><?= $item?></p></td>
                                                                  </tr>
                                                                <?php endforeach; ?>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
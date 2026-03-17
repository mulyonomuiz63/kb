<?= $this->extend('landing/template'); ?>
<?= $this->section('css'); ?>
<style>
        /* ===== CARD ===== */
    .single-courses.card{
        border-radius:16px;
        border:none;
        overflow:hidden;
        background:#fff;
        box-shadow:0 8px 22px rgba(0,0,0,.08);
        transition:all .35s ease;
    }
    .single-courses.card:hover{
        transform:translateY(-6px);
        box-shadow:0 16px 36px rgba(0,0,0,.15);
    }
    
    /* ===== IMAGE ===== */
    .courses-images{
        overflow:hidden;
    }
    .courses-images img{
        transition:transform .45s ease;
    }
    .single-courses:hover .courses-images img{
        transform:scale(1.08);
    }
    
    /* ===== TITLE ===== */
    .courses-content h4.title{
        font-size:16px;
        font-weight:600;
        line-height:1.4;
        margin-bottom:6px;
    }
    
    /* ===== PRICE ===== */
    .courses-meta .fw-bold{
        font-size:17px;
        color:#1A4FF0;
    }
    
    /* ===== AFFILIATE ===== */
    .affiliate-box{
        background:linear-gradient(135deg,#f8fbff,#eef4ff);
        border:1px dashed #c9d9ff;
        border-radius:10px;
        font-size:12px;
        animation:fadeUp .5s ease;
    }
    
    /* ===== BUTTON ===== */
    .btn-buy{
        background:#1A4FF0;
        color: #fff;
        border-radius:10px;
        border:none;
        padding:0px 15px;
        font-weight:600;
        transition:.3s ease;
    }
    .btn-buy:hover{
        background:#d0011b;
        color: #fff;
        transform:scale(1.05);
    }
    
    .btn-buy-copy{
        background:#DCDCDC;
        color: #212121;
        border-radius:10px;
        border:none;
        padding:0px 15px;
        font-weight:600;
        transition:.3s ease;
    }
    .btn-buy-copy:hover{
        background:#d0011b;
        color: #fff;
        transform:scale(1.05);
    }
    
    .btn-buy-wa{
        background:#90EE90;
        color: #fff;
        border-radius:10px;
        border:none;
        padding:0px 15px;
        font-weight:600;
        transition:.3s ease;
    }
    .btn-buy-wa:hover{
        background:#d0011b;
        color: #fff;
        transform:scale(1.05);
    }
    
    /* ===== DISCOUNT BADGE ===== */
    .diskon{
        background:linear-gradient(45deg,#ff4d4d,#ff9800);
        font-size:12px;
        border-radius:0 0 0 14px;
        animation:pulse 1.5s infinite;
    }
    
    /* ===== ENTRY ANIMATION ===== */
    .animate-card{
        opacity:0;
        transform:translateY(20px);
        transition:all .6s ease;
    }
    .animate-card.show{
        opacity:1;
        transform:translateY(0);
    }
    
    /* ===== KEYFRAMES ===== */
    @keyframes pulse{
        0%{opacity:1}
        50%{opacity:.6}
        100%{opacity:1}
    }
    @keyframes fadeUp{
        from{opacity:0;transform:translateY(8px)}
        to{opacity:1;transform:translateY(0)}
    }
    
    /* ===== RESPONSIVE ===== */
    @media(max-width:576px){
        .courses-content h4.title{
            font-size:15px;
        }
        .btn-buy{
            padding:10px;
            font-size:14px;
        }
    }

    
</style>
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>
 <div class="section call-to-action-wrapper pb-4 d-flex align-items-center mt-2" id="bimbel">
            <div class="">
                <!-- All Courses tab content Start -->
                <div class="tab-content courses-tab-content">
                    <div class="tab-pane fade show active" id="tabs1">

                        <!-- All Courses Wrapper Start -->
                        <div class="courses-wrapper">
                            <h6>Penawaran Paket Brevet Pajak AB</h6>
                            <span>Pilihan ujian yang bisa kamu ikuti secara online kapan saja dan dimana saja</span>
                            <div class="row">
                                <?php foreach ($paket as $rows) : ?>
                                    <?php
                                        // untuk rating
                                        $query = $db->table('paket')->join('detail_paket b', 'paket.idpaket=b.idpaket')->join('ujian_master c', 'b.id_ujian=c.id_ujian')->join('review_ujian d', 'c.kode_ujian=d.kode_ujian')->where('paket.slug', $rows->slug)->get()->getResultObject();
                    
                                        // hitung rata-rata rating
                                        $totalRating = 0;
                                        $jumlahReview = count($query);
                                        
                                        foreach ($query as $item) {
                                            $totalRating += $item->rating;
                                        }
                                        
                                        $rataRating = $jumlahReview > 0 ? round($totalRating / $jumlahReview, 1) : 0;
                                    ?>
                                     <?php if($rows->id_mapel == '0' || $rows->id_mapel == '1' ): ?> 
                                        <div class="col-12 col-md-6 col-lg-4 mt-4">
                                            <!-- Single Courses Start --> 
                                            <div class="single-courses card position-relative animate-card">
                                                <div class="courses-images">
                                                    <a href="<?= base_url('bimbel/'. $rows->slug) ?>">
                                                        <?= img_lazy('assets-landing/images/paket/thumbnails/'.$rows->file, $rows->nama_paket, ['class' => 'card-img-top']) ?>
                                                    </a>
                                                </div>
                                                <div class="courses-content">
                                                    <h4 class="title"><a href="<?= base_url('bimbel/'. $rows->slug) ?>"><?= $rows->nama_paket ?></a></h4>
                                                    <div class="courses-meta">
                                                        <?php
                                                            $soal = $db->query("SELECT a.id_ujian, b.kode_ujian FROM detail_paket a join ujian_master b on a.id_ujian=b.id_ujian where a.idpaket = '$rows->idpaket' group by a.id_ujian")->getResult();
                                                            $durasi = 0;
                                                            foreach($soal as $r):
                                                                $total = 0;
                                                                $ujianDetail = $db->query("select * from ujian_detail where kode_ujian = '$r->kode_ujian'")->getResult();
                                                                foreach($ujianDetail as $dataRows){
                                                                    $total++;
                                                                }
                                                                $jml = $db->query("select count(kode_ujian) as total_soal from ujian_detail where kode_ujian = '$r->kode_ujian'")->getRow();
                                                                
                                                                $totalMenit = $total * 3;
                                                                $start =  (date('Y-m-d H:i'));
                                                                $end_ = (date('Y-m-d H:i', strtotime("+ $totalMenit minutes")));
    
                                                                
                                                                $start_ujian = date_create($start);
                                                                $end_ujian = date_create($end_);
                                                                $durasi = date_diff($start_ujian, $end_ujian);
                                                            endforeach;
                                                            
                                                        ?>
                                                        <span class="fw-bold"> <i class="icofont-read-book"></i> <?= (!empty($jml) ? $jml->total_soal:'0') ?> Soal/<span style="font-size:10px">Materi</span> </span>
                                                        <div class="d-flex flex-column mb-3">
                                                            <span class="fw-bold"> Rp <?= number_format($rows->nominal_paket - (($rows->nominal_paket*$rows->diskon)/100)) ?> </span> 
                                                            <span style="font-size:12px" class="mt-1"> <del>Rp <?= number_format($rows->nominal_paket) ?></del> </span> 
                                                        </div>
                                                        
                                                        <!--<span> <i class="icofont-clock-time"></i> <?= ($durasi != '0'? ($durasi->h * 60) + $durasi->i :'0');  ?> Menit</span>-->
                                                    </div>
                                                    <div>
                                                        <div class="mb-2" style="font-size:12px">
                                                            <?php if($rataRating > 0): ?>
                                                                <span class="text-dark"><?= $rataRating ?><span> <?= showStars($rataRating) ?> <span class="text-dark">(<?= $jumlahReview + 325 ?>)</span>
                                                            <?php else: ?>
                                                                <span class="text-dark"><?= "4.9" ?><span> <?= showStars('4.9') ?> <span class="text-dark">(<?= '484' ?>)</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <!-- Affiliate -->
                                                        <?php if(session()->get('id') && !empty($affiliate)): ?>
                                                            <div class="affiliate-box p-2 mb-3">
                                                                💰 <strong class="text-warning">Komisi <?= $rows->komisi ?>%</strong>
                                                                <div class="text-muted small">
                                                                    Dari setiap pembelian via link kamu
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                        
                                                    </div>
                                                    <div class="d-flex gap-2 mt-3">
                                                        <a href="<?= base_url('Transaksi/pesan/'.encrypt_url($rows->idpaket)) ?>" class="btn-buy btn-sm text-center flex-fill p-2">Pesan Sekarang</a>
                                                        <?php if(session()->get('id')): ?>
                                                            <?php if(!empty($affiliate)): ?>
                                                                <button class="btn-buy-copy btn-sm  btn-copy-link" data-paket_id="<?= $rows->idpaket ?>">
                                                                    <i class="fa fa-copy"></i>
                                                                </button>
                                                                <button class="btn-buy-wa btn-sm  share-link" data-paket_id="<?= $rows->idpaket ?>">
                                                                    <i class="fab fa-whatsapp"></i>
                                                                </button>
                                                                <!-- iOS clipboard helper -->
                                                                <input type="text" id="clipboard-temp" style="position:fixed;top:-1000px;opacity:0;">
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                            
                                                       
                                                </div>
                                                <?php if(!empty($rows->deskripsi)): ?>
                                                    <a href="javascript:void(0)" class="badge text-bg-light p-2 mt-4 rounded-pill text-primary btn-hover-dark text-izin deskripsi_paket" data-bs-toggle="modal" data-bs-target="#lihatPaket" data-idpaket="<?= $rows->idpaket ?>"> <i class="bi bi-eye-fill me-2"></i>Lihat Daftar Materi</a>
                                                <?php endif; ?>
                                                <?php if($rows->iddiskon != null ): ?>
                                                    <div class="position-absolute top-0 end-0 diskon p-1 text-white"><?= $rows->diskon ?> %</div>
                                                <?php endif; ?>
                                            </div>
                                            <!-- Single Courses End -->
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <?php if($lihat == '0'): ?>
                                <div class="d-flex justify-content-center">
                                    <div class="row">
                                        <div class="col-12 text-center" style="padding-top: 20px;">
                                            <a href="<?= base_url('list-bimbel') ?>" class="text-primary mt-2 ">Lihat lebih banyak</a><i class="bi bi-arrow-down-square-fill ms-1" style="color: blue;"></i>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!-- All Courses Wrapper End -->
                    </div>
                </div>
                <!-- All Courses tab content End -->
            </div>
        </div>
         <!-- untuk paket-->
        <div class="modal fade" id="lihatPaket" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-sm modal-dialog-scrollable" style="scrollbar-color: #0000FF #ffffff" id="modalPaket">
            <div class="modal-content position-relative">
              
                <div class="modal-body card-body ">
                    <div class="isideskripsi fs-6" style="margin-top:-25px"></div>
                </div>
                <button type="button" class="position-absolute top-0 start-0 text-light zoom close-deskripsi-paket" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x"></i></button>
            </div>
          </div>
        </div>
        
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if(entry.isIntersecting){
                entry.target.classList.add('show');
            }
        });
    }, { threshold: 0.15 });

    document.querySelectorAll('.animate-card').forEach(card => {
        observer.observe(card);
    });
});
</script>

<?= $this->endSection(); ?>
<?= $this->extend('landing/template'); ?>
<?= $this->section('css'); ?>
  <!-- 🔑 CSRF -->
  <meta name="csrf-token" content="<?= csrf_hash() ?>">
  <meta name="csrf-header" content="<?= csrf_token() ?>">

  <style>
    body { background: #f8f9fa; }
    .quiz-box {
      max-width: 900px;
      margin: 30px auto;
      padding: 70px;
      border-radius: 10px;
      background: linear-gradient(135deg, rgba(163,102,255,0.9), rgba(150,198,255,0.9));
      color: #fff;
      box-shadow: 0 4px 20px rgba(0,0,0,.1);
      position: relative;
    }

    .answer-btn { transition: all .2s; }
    .answer-btn.selected {
      background-color: #ffc107 !important;
      color: #fff !important;
      border-color: #ffc107 !important;
    }
    .answer-btn:focus,
    .answer-btn:active {
      box-shadow: none !important;
    }
    #resultBox, #quizBox { display: none; }
    #scoreNumber { font-size: 3rem; font-weight: bold; color: #0d6efd; }
    canvas { max-height: 300px; }
    #confettiCanvas { position:absolute; top:0; left:0; width:100%; height:100%; pointer-events:none; }
  </style>
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>

<!-- Halaman Awal -->
<div class="quiz-box text-center" id="startBox">
  <h3 class="">🎯 <?= $quiztem->judul ?></h3>
  <h6 class="text-wrap mb-3"><?= $quiztem->deskripsi ?></h6>
  <div class="mb-3">
    <input type="hidden" id="playerName" class="form-control text-center" value="<?= session('id') ?>">
    <input type="hidden" id="idquiztem" class="form-control text-center" value="<?= $quiztem->idquiztem ?>">
  </div>
  <div class="d-grid gap-2 col-6 mx-auto">
    <button class="btn btn-primary" onclick="startQuiz()">Mulai Kuis</button>
  </div>
</div>

<!-- Halaman Kuis -->
<div class="quiz-box text-center" id="quizBox">
  <div class="mb-3 d-flex justify-content-between">
    <small id="progressText">Soal 1 dari 1</small>
  </div>
  <div class="progress mb-3">
    <div id="progressBar" class="progress-bar bg-warning" role="progressbar" style="width: 0%"></div>
  </div>

  <h4 class="mb-4" id="question"></h4>
  <div class="d-grid gap-2 mb-3" id="options"></div>
  <button id="nextBtn" class="btn btn-primary d-none">Next ➡</button>
</div>

<!-- Halaman Hasil -->
<div class="quiz-box" id="resultBox">
  <h3 class="text-center mb-3">🎉 Hasil Kuis</h3>
  <p class="text-center mb-1">Skor Kamu:</p>
  <div id="scoreNumber" class="text-center text-warning">0</div>
  <p class="text-center" id="totalSoal"></p>

  <!-- canvas confetti hanya di hasil kuis -->
  <canvas id="confettiCanvas"></canvas>

  <div class="table-responsive mt-4">
    <table class="table table-bordered text-center">
      <thead class="table-light">
        <tr><th>Soal</th><th>Status</th><th>Bonus</th></tr>
      </thead>
      <tbody id="scoreDetails"></tbody>
    </table>
  </div>

  <div class="mt-4">
    <h5 class="text-center">🏆 Leaderboard (Top 5)</h5>
    <div class="table-responsive">
      <table class="table table-sm table-bordered text-center">
        <thead class="table-light">
          <tr>
            <th>Peringkat</th>
            <th>Nama</th>
            <th>Total</th>
            <th>Bonus</th>
            <th>Nilai</th> <!-- ✅ kolom tambahan -->
          </tr>
        </thead>
        <tbody id="leaderboardTop5"></tbody>
      </table>
    </div>
  </div>

  <div class="text-center">
    <button class="btn btn-success mt-3" onclick="backToStart()">🔄 Mulai Lagi</button>
  </div>
</div>

<!-- Confetti -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
// 🔑 Setup CSRF otomatis
$.ajaxSetup({
  beforeSend: function(xhr) {
    let csrfToken = $('meta[name="csrf-token"]').attr('content');
    let csrfHeader = $('meta[name="csrf-header"]').attr('content');
    xhr.setRequestHeader(csrfHeader, csrfToken);
  },
  complete: function(xhr) {
    let csrfHeader = $('meta[name="csrf-header"]').attr('content');
    if (xhr.getResponseHeader(csrfHeader)) {
      $('meta[name="csrf-token"]').attr('content', xhr.getResponseHeader(csrfHeader));
    }
  }
});

let quizData=[], currentQuestion=0, scoreDetails=[], idsiswa="", idquiztem="", selectedAnswer=null;
const startBox=$("#startBox"),
      quizBox=$("#quizBox"),
      resultBox=$("#resultBox"),
      questionEl=$("#question"),
      optionsEl=$("#options"),
      nextBtn=$("#nextBtn"),
      scoreNumber=$("#scoreNumber"),
      totalSoal=$("#totalSoal"),
      progressText=$("#progressText"),
      progressBar=$("#progressBar"),
      scoreDetailsBody=$("#scoreDetails"),
      leaderboardTop5Body=$("#leaderboardTop5");

let chartResult;

function shuffle(arr){ return arr.sort(()=>Math.random()-0.5); }

function startQuiz(){
  const name=$("#playerName").val().trim();
  const tema=$("#idquiztem").val().trim();
  if(!name){alert("Masukkan nama!");return;}
  idsiswa=name;idquiztem=tema;  currentQuestion=0; scoreDetails=[];
  startBox.hide(); 
  resultBox.hide(); 
  quizBox.show();

  $.getJSON("<?= base_url("quiz/soal/all") ?>", function(res){
    quizData=res;
    loadQuestion();
  });
}

function updateProgress(){
  progressText.text(`Soal ${currentQuestion+1} dari ${quizData.length}`);
  progressBar.css("width",((currentQuestion)/quizData.length)*100+"%");
}

function loadQuestion(){
  const q=quizData[currentQuestion]; 
  questionEl.text(q.pertanyaan); optionsEl.html("");
  selectedAnswer=null;

  shuffle([...q.opsi]).forEach(opt=>{
    const btn=$("<button>")
      .addClass("btn btn-outline-primary answer-btn")
      .text(opt).data("answer",opt)
      .click(function(){
        $(".answer-btn").removeClass("selected");
        $(this).addClass("selected");
        selectedAnswer=$(this).data("answer");
        nextBtn.removeClass("d-none");
      });
    optionsEl.append(btn);
  });

  nextBtn.addClass("d-none").off("click").on("click",nextQuestion);
  updateProgress();
}

function nextQuestion(){
  const q=quizData[currentQuestion]; 
  let status="Salah", bonusDapat=0;

  if(selectedAnswer===q.jawaban){
    status="Benar"; bonusDapat=q.bonus;
  }

  scoreDetails.push({ nomor: currentQuestion+1, status, bonus: bonusDapat });

  if(currentQuestion < quizData.length-1){
    currentQuestion++;
    loadQuestion();
  } else {
    showResult();
  }
}

function showResult(){
  quizBox.hide(); resultBox.show(); scoreNumber.text("0");
  totalSoal.text(`Dari total ${quizData.length} soal`);

  let benar=scoreDetails.filter(r=>r.status==="Benar").length;
  let bonusTotal=scoreDetails.reduce((s,r)=>parseInt(s)+parseInt(r.bonus),0);
  let baseScore=(benar/quizData.length)*100;
  let finalScore=Math.min(Math.round(baseScore+bonusTotal),100);

  saveLeaderboard(idsiswa,idquiztem,Math.round(baseScore),bonusTotal,finalScore);

  scoreDetailsBody.html("");
  scoreDetails.forEach(r=>{
    scoreDetailsBody.append(`<tr><td>${r.nomor}</td><td>${r.status}</td><td>${r.bonus}</td></tr>`);
  });

  let cur=0;
  const interval=setInterval(()=>{
    if(cur<finalScore){cur++;scoreNumber.text(cur);}
    else{
      clearInterval(interval); 
      // 🎉 Confetti 3 detik di hasil kuis
      let canvas=document.getElementById("confettiCanvas");
      let myConfetti=confetti.create(canvas,{resize:true,useWorker:true});
      let duration=2000; // 3 detik
      let end=Date.now()+duration;

      (function frame(){
        myConfetti({particleCount:8,spread:70,origin:{y:0.2}});
        if(Date.now()<end){
          requestAnimationFrame(frame);
        }
      })();
    }
  },20);

  progressBar.css("width","100%"); 
  progressText.text("Kuis selesai ✅");

  loadLeaderboard();
  renderCharts(benar,quizData.length-benar);
}

// Leaderboard
function saveLeaderboard(idsiswa,idquiztem,base,bonus,total){
    $.ajax({
      url: "<?= base_url("quiz/save-leaderboard") ?>",
      type: "POST",
      contentType: "application/json",
      data: JSON.stringify({
        [ $('meta[name="csrf-header"]').attr('content') ]: $('meta[name="csrf-token"]').attr('content'),
        idsiswa, idquiztem, base, bonus, total,
        answers: scoreDetails.map(r => r.status)
      }),
      success: function(res){ },
      error: function(err){ }
    });
}
function loadLeaderboard(){
  $.getJSON("<?= base_url("quiz/leaderboard") ?>", function(data){
    leaderboardTop5Body.html("");

    // ✅ urutkan berdasarkan total + bonus terbesar
    data.sort((a,b)=>(parseInt(b.skor_dasar) + parseInt(b.bonus)) - (parseInt(a.skor_dasar) + parseInt(a.bonus)));

    data.forEach((r,i)=>{
      if(i<5){
        let nilai = parseInt(r.skor_dasar) + parseInt(r.bonus);
        leaderboardTop5Body.append(`<tr>
          <td>${i+1}</td>
          <td>${r.nama_siswa}</td>
          <td>${r.skor_dasar}</td>
          <td>${r.bonus}</td>
          <td><b>${nilai}</b></td>
        </tr>`);
      }
    });
  });
}

// Chart
function renderCharts(correct,wrong){
  if(chartResult)chartResult.destroy();
  chartResult=new Chart(document.getElementById("chartResult"),{
    type:"pie",
    data:{labels:["Benar","Salah"],datasets:[{data:[correct,wrong],backgroundColor:["#198754","#dc3545"]}]}
  });
}

function backToStart(){
  resultBox.hide(); quizBox.hide(); startBox.show();
}
</script>
<?= $this->endSection(); ?>

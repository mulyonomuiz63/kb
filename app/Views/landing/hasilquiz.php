<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Hasil Kuis</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="card p-4 text-center">
    <h3>🎉 Hasil Kuis</h3>
    <p class="mb-1"><b><?= esc($hasil['nama']) ?></b></p>
    <h1 class="text-primary"><?= $hasil['total'] ?></h1>
    <p>Skor Dasar: <?= $hasil['skor'] ?> | Bonus: <?= $hasil['bonus'] ?> | Total: <?= $hasil['total'] ?></p>

    <table class="table table-bordered mt-4">
      <thead>
        <tr>
          <th>No Soal</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($hasil['detail'] as $id => $status): ?>
          <tr>
            <td><?= $id ?></td>
            <td><?= $status ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <a href="/quiz" class="btn btn-success mt-3">🔄 Mulai Lagi</a>
  </div>
</div>

</body>
</html>

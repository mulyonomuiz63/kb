<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Inter', sans-serif;
        }

        .container {
            max-width: 900px;
            margin-top: 50px;
            margin-bottom: 50px;
        }

        /* Card Styling */
        .chat-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            background: white;
        }

        .header-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 35px;
            text-align: center;
        }

        /* Upload Area */
        .upload-box {
            border: 2px dashed #cbd5e1;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
            background: #f8fafc;
        }

        .upload-box:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .form-control {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 15px;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            border-color: #3b82f6;
        }

        .btn-primary {
            background-color: #2563eb;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
            transform: translateY(-1px);
        }

        /* Response Area */
        .response-box {
            padding: 35px;
            border-top: 1px solid #f1f5f9;
        }

        .user-query {
            background: #f1f5f9;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            border-left: 5px solid #3b82f6;
        }

        .ai-response {
            line-height: 1.8;
            color: #1e293b;
            white-space: pre-line;
            background: #ffffff;
            padding: 20px;
            border-radius: 15px;
            border: 1px solid #e2e8f0;
        }

        .empty-state {
            text-align: center;
            color: #94a3b8;
            padding: 50px 0;
        }

        .label-step {
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 10px;
            display: block;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="chat-card">
            <div class="header-bg">
                <i class="fa fa-balance-scale fa-3x mb-3"></i>
                <h2 class="font-weight-bold">NDTAXANDLAW AI</h2>
                <p class="mb-0 opacity-75">Deep Research Document Analyzer</p>
            </div>

            <div class="p-4 p-md-5">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger border-0 shadow-sm" style="border-radius: 12px;">
                        <i class="fa fa-exclamation-circle mr-2"></i> <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('gemini/processResume'); ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="form-group mb-4">
                        <span class="label-step">1. Unggah Putusan Pajak (PDF)</span>
                        <div class="upload-box" onclick="document.getElementById('pdf_file').click()">
                            <i class="fa fa-file-pdf-o fa-3x text-danger mb-3"></i>
                            <p id="file-name" class="text-muted mb-0">Klik untuk memilih file PDF atau seret ke sini</p>
                            <input type="file" name="pdf_file" id="pdf_file" class="d-none" accept=".pdf" required onchange="displayFileName(this)">
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <span class="label-step">2. Instruksi Analisis</span>
                        <textarea name="prompt" class="form-control" rows="5" required placeholder="Contoh: Rangkum sengketa ini, sebutkan nama WP, NPWP, Hakim, dan Hasil Putusan secara detail..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block shadow-sm">
                        <i class="fa fa-search mr-2"></i> Mulai Deep Research
                    </button>
                </form>
            </div>

            <?php if ($hasil): ?>
                <div class="response-box bg-light">
                    <div class="ai-response shadow-sm bg-white p-4">

                        <?php if (isset($isJson) && $isJson === true): ?>
                            <h4 class="text-center font-weight-bold mb-4" style="text-decoration: underline;">RESUME HASIL ANALISIS</h4>

                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="30%">NOMOR PUTUSAN</th>
                                    <td>: <?= $hasil['nomor_putusan'] ?></td>
                                </tr>
                                <tr>
                                    <th>NAMA WAJIB PAJAK</th>
                                    <td>: <?= $hasil['nama_wp'] ?></td>
                                </tr>
                                <tr>
                                    <th>BIDANG USAHA</th>
                                    <td>: <?= $hasil['bidang_usaha'] ?></td>
                                </tr>
                                <tr>
                                    <th>NILAI SENGKETA</th>
                                    <td>: <strong class="text-danger"><?= number_format((float)$hasil['nilai_sengketa'], 0, ',', '.') ?></strong></td>
                                </tr>
                                <tr>
                                    <th>HASIL PUTUSAN</th>
                                    <td>: <span class="badge badge-primary"><?= $hasil['putusan'] ?></span></td>
                                </tr>
                            </table>

                            <hr>
                            <h6 class="font-weight-bold"><i class="fa fa-gavel"></i> Argumentasi Majelis Hakim:</h6>
                            <p class="text-justify"><?= $hasil['arg_hakim'] ?></p>

                            <h6 class="font-weight-bold"><i class="fa fa-book"></i> Dasar Hukum Hakim:</h6>
                            <p><?= $hasil['dh_hakim'] ?></p>

                        <?php else: ?>
                            <div id="ai-content-text"><?= nl2br(esc($hasil)); ?></div>
                        <?php endif; ?>

                    </div>
                </div>
            <?php endif; ?>
        </div>

        <p class="text-center mt-4 text-muted" style="font-size: 13px; letter-spacing: 0.5px;">
            Powered by <strong>Google Gemini API</strong> & <strong>CodeIgniter 4</strong>
        </p>
    </div>

    <script>
        // Menampilkan nama file setelah dipilih
        function displayFileName(input) {
            const fileName = input.files[0] ? input.files[0].name : "Klik untuk memilih file PDF";
            const label = document.getElementById('file-name');
            label.innerText = fileName;
            label.classList.add('text-primary', 'font-weight-bold');
        }

        // Fungsi copy teks hasil
        function copyResult() {
            const content = document.getElementById('ai-content-text').innerText;
            navigator.clipboard.writeText(content).then(() => {
                alert('Hasil analisis berhasil disalin!');
            });
        }
    </script>
</body>

</html>
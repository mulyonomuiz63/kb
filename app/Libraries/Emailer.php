<?php

namespace App\Libraries;

use App\Models\LogEmailModel;
use App\Models\SettingModel;

class Emailer
{
    protected $email;
    protected $settingModel;
    protected $logModel;

    public function __construct()
    {
        $this->email = \Config\Services::email();
        $this->logModel = new LogEmailModel();
        $this->settingModel = new SettingModel();
    }

    public function send($to, $subject, $message)
    {
        // 1. Cek apakah fitur SMTP diaktifkan di pengaturan
        $isSmtpActive = setting('smtp_status');

        // Jika OFF (false/0), kita tidak kirim email sama sekali
        if (!$isSmtpActive) return false;

        $config = [
            'protocol'   => 'smtp',
            'SMTPHost'   => setting('smtp_host'),
            'SMTPUser'   => setting('smtp_user'),
            'SMTPPass'   => setting('smtp_pass'),
            'SMTPPort'   => (int) setting('smtp_port'),
            'SMTPCrypto' => setting('smtp_crypto'),
            'mailType'   => 'html',
            'charset'    => 'UTF-8',
            'CRLF'       => "\r\n",
            'newline'    => "\r\n"
        ];

        $this->email->initialize($config);
        $this->email->setFrom(
            setting('smtp_from_email'),
            setting('smtp_from_name') ? setting('smtp_from_name') : 'Admin Kelas Brevet'
        );

        $this->email->setTo($to);
        $this->email->setSubject($subject);
        $this->email->setMessage($message);

        // 2. Proses Pengiriman
        $status = $this->email->send();

        // Ambil detail error jika pengiriman gagal
        $note = $status ? null : $this->email->printDebugger(['headers', 'subject', 'body']);

        // 3. Simpan ke Log Database
        $logData = [
            'penerima'      => $to,
            'subjek'        => $subject,
            'status'        => $status ? 'success' : 'failed',
            'error_message' => $note // Simpan detail error jika gagal agar mudah di-debug
        ];

        $this->logModel->insert($logData);

        return $status;
    }
}

<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class SettingsController extends BaseController
{
    protected $settingModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
    }

    public function index()
    {
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('sw-admin')],
            ['title' => 'Data Pengaturan', 'url' => '#'],
        ];
        $data['settings'] = $this->settingModel->getAllSettings();
        return view('admin/settings/list', $data);
    }

    public function ujiAsisten()
    {
        $model = trim((string) $this->request->getPost('asisten_model'));
        $url = trim((string) $this->request->getPost('asisten_url'));
        $apiKey = trim((string) $this->request->getPost('asisten_api_key'));

        if ($apiKey === '') {
            $apiKey = trim((string) $this->settingModel->getByKey('asisten_api_key'));
        }
        if ($model === '') {
            $model = trim((string) ($this->settingModel->getByKey('asisten_model') ?: 'gemini-3.5-flash'));
        }
        if ($url === '') {
            $url = trim((string) ($this->settingModel->getByKey('asisten_url') ?: 'https://generativelanguage.googleapis.com/v1beta/openai/chat/completions'));
        }

        if ($apiKey === '') {
            return $this->ujiJson(false, 'API key masih kosong.');
        }
        if ($model === '') {
            return $this->ujiJson(false, 'Nama model masih kosong.');
        }
        if (!$this->urlAsistenAman($url)) {
            return $this->ujiJson(false, 'URL API tidak valid. Gunakan alamat http atau https penyedia.');
        }

        $payload = json_encode([
            'model' => $model,
            'temperature' => 0,
            'max_tokens' => 16,
            'messages' => [
                ['role' => 'user', 'content' => 'Balas hanya dengan kata: terhubung'],
            ],
        ]);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_CONNECTTIMEOUT => 8,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
        ]);

        $body = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($body === false) {
            return $this->ujiJson(false, 'Tidak terhubung ke penyedia. ' . $this->rapikanPesan($error));
        }

        $data = json_decode($body, true);
        $reply = $data['choices'][0]['message']['content'] ?? null;

        if ($httpCode >= 200 && $httpCode < 300 && is_string($reply) && trim($reply) !== '') {
            return $this->ujiJson(true, 'Terhubung. Model membalas: ' . $this->rapikanPesan($reply));
        }

        $providerMessage = $data['error']['message'] ?? '';
        if ($httpCode === 401 || $httpCode === 403) {
            return $this->ujiJson(false, 'API key ditolak oleh penyedia. Periksa kunci dan izin model.');
        }
        if ($providerMessage !== '') {
            return $this->ujiJson(false, 'Penyedia menolak permintaan. ' . $this->rapikanPesan($providerMessage));
        }

        return $this->ujiJson(false, 'Koneksi gagal. Kode jawaban penyedia: ' . $httpCode);
    }

    private function ujiJson(bool $ok, string $message)
    {
        return $this->response->setStatusCode($ok ? 200 : 422)->setJSON([
            'ok' => $ok,
            'message' => $message,
            'csrf' => csrf_hash(),
        ]);
    }

    private function rapikanPesan(string $text): string
    {
        $text = trim(preg_replace('/\s+/', ' ', strip_tags($text)) ?? '');
        if (mb_strlen($text) > 180) {
            $text = mb_substr($text, 0, 180) . '...';
        }
        return $text;
    }

    private function urlAsistenAman(string $url): bool
    {
        $parts = parse_url($url);
        if (!$parts || empty($parts['scheme']) || empty($parts['host'])) {
            return false;
        }
        if (!in_array(strtolower($parts['scheme']), ['http', 'https'], true)) {
            return false;
        }

        $host = strtolower($parts['host']);
        if (in_array($host, ['localhost', '127.0.0.1', '0.0.0.0', '::1'], true)) {
            return false;
        }

        $ip = filter_var($host, FILTER_VALIDATE_IP) ? $host : gethostbyname($host);
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false;
        }

        return true;
    }

    public function update()
    {
        if (! $this->request->is('post')) {
            return redirect()->to('sw-admin/settings')
                ->with('error', 'Metode request tidak diizinkan.');
        }

        try {

            $post = $this->request->getPost();

            // =============================
            // 1. PROSES DATA TEXT
            // =============================
            foreach ($post as $key => $value) {

                // Skip empty smtp_pass
                if ($key === 'smtp_pass' && empty($value)) {
                    continue;
                }

                if ($key === 'asisten_api_key' && trim((string) $value) === '') {
                    continue;
                }

                if ($key === 'asisten_status') {
                    $value = $value === 'true' ? 'true' : 'false';
                }

                if (in_array($key, ['asisten_model', 'asisten_url'], true)) {
                    $value = trim((string) $value);
                }

                // Force integer
                if ($key === 'smtp_port') {
                    $value = (int) $value;
                }

                // Extract Google Maps iframe
                if ($key === 'google_maps') {
                    if (preg_match('/src="([^"]+)"/', $value, $match)) {
                        $value = $match[1];
                    }
                }

                $this->settingModel->saveSetting($key, $value);
            }

            // =============================
            // 2. PROSES FILE UPLOAD
            // =============================
            $files = $this->request->getFiles();
            $imageKeys = ['app_icon', 'logo_perusahaan'];
            $uploadPath = FCPATH . 'uploads/app-icon/';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            foreach ($files as $key => $file) {

                if (
                    in_array($key, $imageKeys) &&
                    $file->isValid() &&
                    !$file->hasMoved()
                ) {

                    $oldSetting = $this->settingModel
                        ->where('key', $key)
                        ->first();

                    $newName = $file->getRandomName();

                    $file->move($uploadPath, $newName);

                    $this->settingModel->saveSetting($key, $newName);

                    // Delete old file
                    if ($oldSetting && !empty($oldSetting['value'])) {
                        $oldPath = $uploadPath . $oldSetting['value'];
                        if (file_exists($oldPath)) {
                            @unlink($oldPath);
                        }
                    }
                }
            }

            return redirect()->to('sw-admin/settings')
                ->with('success', 'Pengaturan berhasil disimpan');
        } catch (\Exception $e) {

            return redirect()->to('sw-admin/settings')
                ->withInput()
                ->with('error', 'Gagal menyimpan pengaturan: ' . $e->getMessage());
        }
    }
}

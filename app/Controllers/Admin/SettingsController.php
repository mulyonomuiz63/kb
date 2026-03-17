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
        $data['settings'] = $this->settingModel->getAllSettings();
        return view('admin/settings/list', $data);
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

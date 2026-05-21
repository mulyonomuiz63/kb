<?php

use App\Models\SettingModel;

if (!function_exists('setting')) {
    /**
     * Ambil setting aplikasi berdasarkan key
     *
     * @param string $key Nama key setting
     * @param mixed $default Nilai default jika key tidak ditemukan
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        static $settings = null;

        // load settings hanya sekali untuk efisiensi
        if ($settings === null) {
            $model = new SettingModel();
            $settings = $model->getAllSettings();
        }

        return $settings[$key] ?? $default;
    }
}


function copyright()
{
   return "Copyright © " . setting('tahun_berdiri') . " - "  .date('Y'). " <a href=".base_url('/')." class='text-primery'>".setting('app_name')."</a> | Hak Cipta Dilindungi Undang-Undang. ";
}

function title()
{
   return setting('app_name');
}

function favicon(){
    return "uploads/app-icon/".setting('app_icon');
}

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


if(env('CI_ENVIRONMENT') == 'development'){
    //midtrans
    function midtrans_url()
    {
        //developer
        return "https://app.sandbox.midtrans.com/snap/v2/vtweb";
    }
    
    function midtrans_url_status()
    {
        //developer
        return "https://app.sandbox.midtrans.com/snap/v2/";
    }
    
    function status_midtrans()
    {
        //developer
        return false;
    }
    function midtrans_server_key()
    {
        //develop
        return "SB-Mid-server-tBK-Q08DyvSh-oObJ76DYZY2";
    }
    
    function midtrans_client_key()
    {
        return "SB-Mid-client-vNg7W2_lVIDw2d6J";
    }
}else{
    //midtrans
    function midtrans_url()
    {
        //production
        return "https://app.midtrans.com/snap/v2/vtweb";
    }
    
    function midtrans_url_status()
    {
        //production
        return "https://app.midtrans.com/snap/v2/";
    }
    
    function status_midtrans()
    {
        //production
        return true;
    }
    function midtrans_server_key()
    {
        return "Mid-server-5fVXDJBPZWy-YpjZMd6DbwEy";
    }
    
    function midtrans_client_key()
    {
        //production
        return "Mid-client-kmrGZVIg5iNP0vO5";
    }
}
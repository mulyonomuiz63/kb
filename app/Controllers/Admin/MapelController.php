<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class MapelController extends BaseController
{
    public function mapelGuru($id)
    {
        $sessionData = [
            'idguru_diadmin'    => decrypt_url($id),
        ];
        session()->set($sessionData);
        return redirect()->to('sw-guru/mapel');
    }
}

<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class DiskusiController extends BaseController
{
    

    public function index()
    {        $data['breadcrumbs'] = [
            ['title' => 'Diskusi', 'url' => '#'],
        ];
    
        return view('siswa/diskusi/list', $data);
    }
}

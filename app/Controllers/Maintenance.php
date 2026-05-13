<?php

namespace App\Controllers;

class Maintenance extends BaseController
{
    public function index()
    {
        // Mengirim status HTTP 503 (Service Unavailable) agar SEO Google tahu ini sementara
        $this->response->setStatusCode(503);
        return view('errors/html/maintenance_view');
    }
}
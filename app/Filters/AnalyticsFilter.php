<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AnalyticsFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('analytics_log');

        $builder->insert([
            'ip_address' => $request->getIPAddress(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'page_url'   => current_url(),
            'referrer'   => $_SERVER['HTTP_REFERER'] ?? '',
        ]);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // tidak dipakai
    }
}

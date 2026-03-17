<?php

namespace App\Cells;

// app/Cells/NotificationCell.php
namespace App\Cells;

use App\Services\NotificationService;

class NotificationCell
{
    protected $service;

    public function __construct()
    {
        $this->service = new NotificationService();
    }

    public function render(): string
    {
        $userId = session()->get('id'); // Mengambil ID dari session

        if (!$userId) {
            return ''; 
        }

        $result = $this->service->getUnreadNotifications($userId);

        return view('cells/notification_ui', [
            'notifications' => $result['notifications'],
            'unread_count'  => $result['unread_count']
        ]);
    }
}
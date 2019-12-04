<?php

namespace App\Notifications;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\NotificationInterface;

class NotificationComment implements NotificationInterface
{
    public function send(){
        return "Notification Comment";
    }
}

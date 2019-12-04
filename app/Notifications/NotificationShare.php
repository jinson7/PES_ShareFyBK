<?php

namespace App\Notifications;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\NotificationInterface;

class NotificationShare implements NotificationInterface
{
    public function send(){
        return "Notification Share";
    }
}

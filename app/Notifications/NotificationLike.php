<?php

namespace App\Notifications;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\NotificationInterface;

class NotificationLike implements NotificationInterface
{
    public function send(){
        return "Notification Like";
    }
}

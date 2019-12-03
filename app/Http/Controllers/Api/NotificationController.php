<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\FirebaseController;
use App\Notifications\NotificationLike;
use App\SendNotification;

class NotificationController extends Controller
{
    protected $type_notifications = [
        'like' =>  NotificationLike::class,
        'comment' =>  NotificationLike::class,
        'invite' =>  NotificationLike::class,
        'follow' =>  NotificationLike::class,
        'share' =>  NotificationLike::class,
    ];

    public function testFirebase(){
        $firebase = new FirebaseController();
        return $firebase->createMessaging();
        return $firebase->getUser('eM96vsRokkM0dfYxaK5TRvGiE0q2');
    }

    public function sendNotification($type = 'like'){
        $type_notification = new $this->type_notifications[$type];
        $notification = new SendNotification($type_notification);
        return $notification->send();
    }
}

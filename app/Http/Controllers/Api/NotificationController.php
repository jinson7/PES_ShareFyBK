<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\FirebaseController;

use App\Notifications\NotificationLike;
use App\Notifications\NotificationInvite;
use App\Notifications\NotificationShare;
use App\Notifications\NotificationFollow;
use App\Notifications\NotificationComment;

use App\SendNotification;

class NotificationController extends Controller
{
    protected $type_notifications = [
        'like' =>  NotificationLike::class,
        'comment' =>  NotificationComment::class,
        'invite' =>  NotificationInvite::class,
        'follow' =>  NotificationFollow::class,
        'share' =>  NotificationShare::class,
    ];

    public function testFirebase(){
        $firebase = new FirebaseController();
        return $firebase->createMessaging();
        return $firebase->getUser('eM96vsRokkM0dfYxaK5TRvGiE0q2');
    }

    public function sendNotification($type, Request $request){
        $type_notification = new $this->type_notifications[$type]($request->path());
        $notification = new SendNotification($type_notification);
        return $notification->send();
    }
}

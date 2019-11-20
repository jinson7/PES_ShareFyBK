<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\FirebaseController;

class NotificationController extends Controller
{
    public function testFirebase(){
        $firebase = new FirebaseController();
        return $firebase->createMessaging();
        return $firebase->getUser('eM96vsRokkM0dfYxaK5TRvGiE0q2');
    }
}

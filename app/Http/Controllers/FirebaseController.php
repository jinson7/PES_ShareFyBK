<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseController extends Controller
{

    protected $firebase;

    public function __construct(){
        $this->firebase = (new Factory())
            ->withServiceAccount(__DIR__.'/sharefy_fb.json');
    }

    public function createNotificatoin($title, $body, $imageUrl){
        $notification = Notification::create($title, $body, $imageUrl);
        return $notification;
    }

    public function createMessaging($to_deviceToken, $notification){
        $messaging = $this->firebase->createMessaging();
        $message = CloudMessage::withTarget('token', $to_deviceToken)
            ->withNotification($notification);
            //->withData(['key' => 'value']);
        $messaging->send($message);
    }

    public function createUser(){
        $auth = $this->firebase->createAuth();
        $userProperties = [
            'email' => 'user@example.com',
            'emailVerified' => false,
            'phoneNumber' => '+15555550100',
            'password' => 'secretPassword',
            'displayName' => 'John Doe',
            'photoUrl' => 'http://www.example.com/12345678/photo.png',
            'disabled' => false,
        ];
        
        $createdUser = $auth->createUser($userProperties);
        return dd("Done");
    }

    public function verifyIdToken($token){
        $auth = $this->firebase->createAuth();
        try {
            $verifiedIdToken = $auth->verifyIdToken($token);
            //return $verifiedIdToken;
        } catch (InvalidToken $e) {
            echo $e->getMessage();
        }
        $uid = $verifiedIdToken->getClaim('sub');
        $user = $auth->getUser($uid);
        return $user;
    }

    public function getUser($uid){
        $auth = $this->firebase->createAuth();
        try {
            $verifiedIdToken = $auth->getUser($uid);
            return $verifiedIdToken;
        } catch (InvalidToken $e) {
            echo $e->getMessage();
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kreait\Firebase\Factory;

class FirebaseController extends Controller
{

    protected $firebase;

    public function __construct(){
        //$this->firebase = (new Factory())
            //->withServiceAccount(__DIR__.'/FirebaseKey.json');
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

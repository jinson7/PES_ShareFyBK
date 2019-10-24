<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kreait\Firebase\Factory;

class FirebaseController extends Controller
{
    public function index(){
        $firebase = (new Factory())
            ->withServiceAccount(__DIR__.'/FirebaseKey.json');

        $auth = $firebase->createAuth();
        //$users = $auth->listUsers($defaultMaxResults = 1000, $defaultBatchSize = 1000);
        $user = $auth->getUser('lL3LUXyqx2gVstitcLf6YXlVPbz2');
        $user_login = $auth->verifyPassword('user@example.com', 'secretPassword');
        $user_after_login = $auth->getUser('lL3LUXyqx2gVstitcLf6YXlVPbz2');

        dd($user, $user_login, $user_after_login);
    }
}

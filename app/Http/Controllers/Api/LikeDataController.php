<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Like;
use App\User;

class LikeDataController extends Controller
{
    public function __construct(){
        //$this->middleware('jwt');
    }

    public function set_like($username, $id_publication){
        $user = User::where('username', $username)->first();
        Like::create([
            'id_user' => $user->id,
            'id_publication' => $id_publication,
        ]);
        return response()->json([
            'message' => 'relació creada correctament'
        ], 400);
    }

    public function unset_like($username, $id_publication){
    }
}

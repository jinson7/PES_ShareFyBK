<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Like;
use App\Http\Controllers\Api\LikeDataController;
use App\User;


class LikeController extends Controller
{

    protected $like_data;

    public function __construct(){
        //$this->middleware('jwt');
        $this->like_data = new LikeDataController();
    }

    public function set_like($username, $id_publication){
        if($username !== null && $username !== "" && $id_publication!== null && $id_publication!== ""){
            return $this->like_data->set_like($username, $id_publication);
        }else{
            return response()->json([
                'error' => 'error en els paràmetres'
            ], 400);
        }
    }

    public function unset_like($username, $id_publication){
        if($username !== null && $username !== "" && $id_publication!== null && $id_publication!== ""){
            return $this->like_data->unset_like($username, $id_publication);
        }else{
            return response()->json([
                'error' => 'error en els paràmetres'
            ], 400);
        }
    }
}

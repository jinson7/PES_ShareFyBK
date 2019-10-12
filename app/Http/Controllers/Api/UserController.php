<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/user/username",
     *     tags={"user"},
     *     summary="Return if username is used",
     *     description="Return if username is used",
     *     @OA\Response(
     *         response=200,
     *         description="true -> username disponible; false -> username no disponible"
     *     ),
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="string amb el valor del username",
     *         required=true
     *     )
     * )
    */
    public function check_username(Request $request){
        //( User::where('username', $request->username)->get()->count() != 0 ? "false" : "true" )
        return response()->json([
            'value' => ( User::where('username', $request->username)->get()->count() != 0 ? "false" : "true" )
          ], 200);
        //return ( User::where('username', $request->username)->get()->count() != 0 ? false : true );
    }
}

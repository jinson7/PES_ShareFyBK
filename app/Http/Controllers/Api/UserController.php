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
     *     summary="Retorna si username es troba disponible",
     *     description="Retorna si username es troba disponible",
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
        return response()->json([
            'value' => ( User::where('username', $request->username)->get()->count() != 0 ? "false" : "true" )
          ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/user/email",
     *     tags={"user"},
     *     summary="Retorna si email es troba disponible",
     *     description="Retorna si email es troba disponible",
     *     @OA\Response(
     *         response=200,
     *         description="true -> email disponible; false -> email no disponible"
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="string amb el valor del email",
     *         required=true
     *     )
     * )
    */
    public function check_email(Request $request){
        return response()->json([
            'value' => ( User::where('email', $request->email)->get()->count() != 0 ? "false" : "true" )
          ], 200);
    }
}

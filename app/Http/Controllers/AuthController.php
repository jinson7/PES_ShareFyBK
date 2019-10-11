<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

/**
* @OA\Info(title="API Sharefy", version="1.0")
*/

class AuthController extends Controller
{

    /**
    * @OA\Post(
    *     path="/api/register",
    *     summary="Registrar Usuario",
    *     @OA\Response(
    *         response=200,
    *         description="Usuario creado correctamente."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */

    public function register(Request $request){
      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
      ]);

      $token = auth()->login($user);

      return $this->respondWithToken($token);
    }

    public function login(Request $request){
      $credentials = $request->only(['email', 'password']);

      if (!$token = auth()->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
      }

      return $this->respondWithToken($token);
    }

    protected function respondWithToken($token){
      return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth()->factory()->getTTL() * 60
      ]);
    }
}

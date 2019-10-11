<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"user"},
     *     summary="Return list all users",
     *     description="Returns an array with all the users",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="error operation"
     *     ),
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="Status values that needed to be considered for filter",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="first_name",
     *         in="query",
     *         description="Status2 values that needed to be considered for filter",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="last_name",
     *         in="query",
     *         description="Status2 values that needed to be considered for filter",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Status2 values that needed to be considered for filter",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="photo",
     *         in="query",
     *         description="Status2 values that needed to be considered for filter",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Status2 values that needed to be considered for filter",
     *         required=true
     *     )
     * )
    */

    public function register(Request $request){
        
        $user = User::create([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = auth()->login($user);
        
        $user->token_password = $token;
        $user->save();

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

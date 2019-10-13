<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

use App\Http\Controllers\MailController;

class AuthController extends Controller
{

    public function __construct(){
        $this->middleware('jwt', ['except' => ['login', 'register']]);
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"user"},
     *     summary="Torna un access_token si l'usuari es registra",
     *     description="Torna un access_token si l'usuari es registra correctament, és dona per fet que l'username i email es troben disponibles.",
     *     @OA\Response(
     *         response=200,
     *         description="operació correcta"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="operació incorrecta"
     *     ),
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="string amb el valor del username",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="first_name",
     *         in="query",
     *         description="string amb el valor del first_name",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="last_name",
     *         in="query",
     *         description="string amb el valor del last_name",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="string amb el valor del mail",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="string amb el valor del password",
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

        // Enviar mail al user
        MailController::send_mail_user_registered($request->username, $request->email);

        return $this->respondWithToken($token);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"user"},
     *     summary="Torna un access_token si l'usuari fa login correctament",
     *     description="Torna un access_token si l'usuari fa login correctament",
     *     @OA\Response(
     *         response=200,
     *         description="operació correcta"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="operació incorrecta"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Dades no valides (contrasenya o username)"
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="string amb el valor del mail",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="string amb el valor del password",
     *         required=true
     *     )
     * )
    */

    public function login(Request $request){
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"user"},
     *     summary="access_token a una llista negra i no es pot tornar a utilitzar més",
     *     description="posa l'access_token a una llista negra i no es pot tornar a utilitzar més, per tenir un altre token haurà de fer login un altre cop.",
     *     @OA\Response(
     *         response=200,
     *         description="operació correcta"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="token no vàlid"
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="string amb el valor del access_token",
     *         required=true
     *     )
     * )
    */

    public function logout(){
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ]);
    }
}

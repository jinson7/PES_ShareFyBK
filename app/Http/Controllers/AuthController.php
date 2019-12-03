<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

use App\Http\Controllers\MailController;

class AuthController extends Controller
{

    public function __construct(){
        $this->middleware('jwt', ['except' => ['login', 'register', 'login_google']]);
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"auth"},
     *     summary="Torna un access_token i el username si l'usuari es registra",
     *     description="Torna un access_token i el username si l'usuari es registra correctament, és dona per fet que l'username i email es troben disponibles.",
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
     *         name="birth_date",
     *         in="query",
     *         description="string amb el valor de birth_date amb el format 1997-12-26",
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
        
        if( $request->password!==null ){
            $user = User::create([
                'username' => $request->username,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'birth_date' => $request->birth_date,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
        }else{
            $user = User::create([
                'username' => $request->username,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'birth_date' => $request->birth_date,
                'email' => $request->email,
            ]);
        }

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
     *     tags={"auth"},
     *     summary="Torna un access_token i el username si l'usuari fa login correctament",
     *     description="Torna un access_token i el username si l'usuari fa login correctament",
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
     *         name="login",
     *         in="query",
     *         description="string amb el valor del email o username",
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

        $loginField = request()->input('login');
        $credentials = null;

        if ($loginField !== null) {
            $loginType = filter_var($loginField, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            request()->merge([ $loginType => $loginField ]);

            $credentials = request([ $loginType, 'password' ]);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * @OA\Post(
     *     path="/api/login/google",
     *     tags={"auth"},
     *     summary="login per als usuaris de google",
     *     description="login per als usuaris de google",
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
     *         description="Dades no valides"
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="string amb el valor del email",
     *         required=true
     *     )
     * )
    */

    public function login_google(Request $request){

        $email = request()->input('email');
        $user = User::where('email', $email)->first();
        if($user === null || $user->email !== ""){
            return response()->json(
                [
                    'error' => 'user amb el mail: '.$email.' no trobat',
                ], 401
            );
        }
        auth()->login($user);
        return response()->json(
            [
                'message' => 'Successfully logged google user',
                'user_logged' => auth()->user(),
            ], 200
        );
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"auth"},
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

        $user = User::where('username', auth()->user()->username)->first();
        $user->token_password = $token;
        $user->save();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'username'  => auth()->user()->username,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Str;
use App\Http\Controllers\MailController;

use App\User;

class UserController extends Controller
{

    public function __construct(){
        $this->middleware('jwt', ['except' => ['check_username',
                                               'check_email',
                                               'reset_password']]);
    }

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

    /**
     * @OA\Post(
     *     path="/api/user/reset",
     *     tags={"user"},
     *     summary="Envia la password a l'usuari per mail",
     *     description="Envia la password a l'usuari per mail",
     *     @OA\Response(
     *         response=200,
     *         description="Operació correcta i mail enviat"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Operació incorrecta, no existeix l'usuari amb el mail indicat"
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="string amb el valor del email",
     *         required=true
     *     )
     * )
    */
    public function reset_password(Request $request){
        
        $user = \App\User::where('email', $request->email)->first();

        if($user !== null){

            $temporal_password = Str::random(12);
            $user->password = bcrypt($temporal_password);
            $user->save();

            // Enviar mail al user
            MailController::send_mail_user_reset_password($user->username, $temporal_password, $user->email);
            
            return response()->json([
                'message' => 'operació correcta'
            ], 200);
            
        }else{

            return response()->json([
                'message' => 'Operació incorrecta, no existeix l\'usuari amb el mail indicat'
            ], 400);

        }
    
    }

    /**
     * @OA\Get(
     *     path="/api/user/{username}?token=valor",
     *     tags={"user"},
     *     summary="Dado un username existente, devuelve su información.",
     *     description="Dado un username existente, devuelve su información.",
     *     @OA\Response(
     *         response=200,
     *         description="Devuelve un json con la información del usuario."
     *     ),
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="string amb el valor del username",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access",
     *         required=true
     *     )
     * 
     * )
    */
    public function get_info_user($username){
        $user = User::select('id','username', 'email', 'password', 'first_name', 'last_name')
        ->where('username', $username)->get();
        return response()->json([
            'value' => $user
          ], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/user/update/{username}?token=valor",
     *     tags={"user"},
     *     summary="Se actualiza la informacion del usuario enviada a esta ruta",
     *     description="Dado un username existente, devuelve su información.",
     *     @OA\Response(
     *         response=200,
     *         description="Retorna un json amb el missatge 'Dades actualitzades correctament' "
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Retorna un json amb el missatge 'No s'han pogut actualitzar les dades' "
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
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access",
     *         required=true
     *     )
     * )
    */

    public function update(Request $request){
        UserDB::update([
            ''
        ]);
    }
}

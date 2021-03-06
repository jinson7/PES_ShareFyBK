<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Controllers\MailController;

use App\User;

class UserDataController extends Controller
{
    
    public function get_id($username) {
        return User::where('username', $username)->first('id');
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
    public function check_username($username) {
        return response()->json([
            'value' => ( User::where('username', $username)->get()->count() != 0 ? "false" : "true" )
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
    public function check_email($email) {
        return response()->json([
            'value' => ( User::where('email', $email)->get()->count() != 0 ? "false" : "true" )
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
     *         response=404,
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
    public function reset_password(Request $request) {
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
            ], 404);
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
    public function get_info($username) {
        $user = User::select('id', 'username', 'email', 'photo_path', 'birth_date', 
                            'first_name', 'last_name', 'notification', 'public', 'language')
                    ->withCount('publications', 'followers', 'followed')
                    ->where('username', $username)->get();
        return response()->json([
            'value' => $user
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"user"},
     *     summary="Retrona un llistat dels usernames existents a la base de dades",
     *     description="Retrona un llistat dels usernames existents a la base de dades",
     *     @OA\Response(
     *         response=200,
     *         description="Retrona un json amb la llista de usernames dels usuaris"
     *     )
     * )
    */
    public function list_all() {
        return response()->json([
            'list' => User::select('username')->get()
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/emails",
     *     tags={"user"},
     *     summary="Retrona un llistat dels emails existents a la base de dades",
     *     description="Retrona un llistat dels emails existents a la base de dades",
     *     @OA\Response(
     *         response=200,
     *         description="Retrona un json amb la llista de usernames dels usuaris"
     *     )
     * )
    */
    public function list_all_emails(){
        return response()->json([
            'list' => User::select('email')->get()
        ], 200);
    }

    public function check_password($password, $req_password) {
        return Hash::check($req_password, $password);
    }

    /**
     * @OA\Put(
     *     path="/api/user/{username}/password",
     *     tags={"user"},
     *     summary="Cambia el password de un usuario.",
     *     description="Dado el username de un usuario, la contraseña actual a cambiar y la nueva contraseña, se cambia la contraseña de dicho usuario.",
     *     @OA\Response(
     *         response=200,
     *         description="Retorna un json amb el 'message' : 'Operació correcta'."
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Retorna un json amb el 'error' 'Paràmetres incorrectes.'"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Retorna un json amb el 'error' 'El password actual no coincideix amb el password introduït.'"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Retorna un json amb el 'error' : 'Usuari no trobat a la base de dades.'"
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="string amb el valor del password actual del usuario con el que se logea.",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="new_password",
     *         in="query",
     *         description="string amb el valor del nou password a cambiar, debe de contener almenos 8 carácteres, mayusculas, minusculas, y números.",
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
    public function update_password(Request $request, $username) {
        $user = User::where('username', $username)->first();
        if($user !== null ) {
            if( $this->check_password($user->password, $request->password) ) {
                $user->password = bcrypt($request->new_password);
                $user->save();
                return response()->json([
                    'message' => 'operació correcta.'
                ], 200);
            }
            return response()->json([
                'error' => 'El password actual no coincideix amb el password introduït.'
            ], 403);
        }
        return response()->json([
            'error' => 'usuari no trobat a la base de dades'
        ], 404);
    }

    /**
     * @OA\Put(
     *     path="/api/user/{username}?token=valor",
     *     tags={"user"},
     *     summary="S'actualitza la informació de l'usuari, només l'usuari propietari de les dades pot modificar-les.",
     *     description="S'actualitza la informació de l'usuari, només l'usuari propietari de les dades pot modificar-les.",
     *     @OA\Response(
     *         response=200,
     *         description="Retorna un json amb el missatge 'operació correcta' "
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Retorna un json amb el missatge 'usuari no trobat a la base de dades' "
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Retorna un json amb el missatge 'no pots modificar dades d'un altre usuari' "
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
     *         description="string amb el valor del birth_date",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="string amb el valor del mail",
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
    public function update_info(Request $request, $username) {
        $user = User::where('username', $username)->first();
        if($user === null ) return response()->json(['message' => 'usuari no trobat a la base de dades'], 404);
        if($user->token_password !== $request->token) return response()->json(['error' => 'no pots modificar dades d\'un altre usuari'], 401);
        
        $user->username = $request->username;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->birth_date = $request->birth_date;
        $user->email = $request->email;
        $user->save();
        return response()->json([
            'message' => 'operació correcta'
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/user/{username}/configuration",
     *     tags={"user"},
     *     summary="Configuracions de privacitat, notificació i idioma.",
     *     description="Configuracions de privacitat, notificació i idioma.",
     *     @OA\Response(
     *         response=200,
     *         description="Retorna json 'message' : 'Configuració guardada conrrectament.'"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Retorna json 'error' : usuari no trobat a la base de dades.'"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Retorna json 'error' : token no valido.'"
     *     ),
     *     @OA\Parameter(
     *         name="privacy",
     *         in="query",
     *         description="String amb el valor true o false, on (true) vol dir que l'usuari es privat i (false) que és públic.",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="notification",
     *         in="query",
     *         description="String amb el valor true o false, on (true) vol dir que l'usuari vol rebre notificacions i (false) que NO.",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="language",
     *         in="query",
     *         description="String amb la abreviatura de l'idioma que vol utilizar l'usuari en la aplicació. p. ex. {'cat','es','en}",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token access.",
     *         required=true
     *     )
     * )
    */
    public function set_configurations(Request $request, $username) {
        $user = User::where('username', $username)->first();
        if ($user === null) 
            return response()->json(['error' => 'usuari no trobat a la base de dades.'], 404);
        if ($user->token_password !== $request->token) 
            return response()->json(['error' => 'token no valido.'], 401);
        if ($request->privacy !== null) {
            if ($request->privacy === 'true') $user->public = false;
            if ($request->privacy === 'false') $user->public = true;
        }
        if ($request->notification !== null) {
            if ($request->notification === 'true') $user->notification = true;
            if ($request->notification === 'false') $user->notification = false;
        }
        $user->language = $request->language;
        $user->save();
        return response()->json([
            'message' => 'Configuració guardada conrrectament.'
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/user/{username}/token_password",
     *     tags={"user"},
     *     summary="set token_password per als usuaris que han fet login amb google",
     *     description="set token_password per als usuaris que han fet login amb google",
     *     @OA\Parameter(
     *         name="token_expire",
     *         in="query",
     *         description="string amb el valor del token a ficar a l'usuari",
     *         required=true
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="'error' => 'usuari no trobat a la base de dades'"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="'message' => 'Configuració guardada conrrectament.'"
     *     )
     * )
    */
    public function set_token(Request $request, $username) {
        $user = User::where('username', $username)->first();
        if($user === null ) return response()->json(['error' => 'usuari no trobat a la base de dades'], 404);
        $user->token_password = sha1(time());
        $user->token_expire = $request->token_expire;
        $user->save();
        return response()->json([
            'message' => 'Configuració guardada conrrectament.',
            'token' => $user->token_password
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/user/{username}/token_notification",
     *     tags={"user"},
     *     summary="set token_notification per als usuaris",
     *     description="set token_notification per als usuaris",
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="valor del token_access per poder utilitzar el endpoint",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="token_notification",
     *         in="query",
     *         description="string amb el valor del token a ficar a l'usuari",
     *         required=true
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="'error' => 'usuari no trobat a la base de dades'"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="'message' => 'Configuració guardada conrrectament.'"
     *     )
     * )
    */
    public function set_token_notification(Request $request, $username){
        
        $user = User::where('username', $username)->first();
        if($user === null ) return response()->json(['error' => 'usuari no trobat a la base de dades'], 404);
        $user->token_notification = $request->token_notification;
        $user->save();
        return response()->json([
            'message' => 'Configuració guardada conrrectament.'
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/user/{username}/follow/request",
     *     tags={"user"},
     *     summary="Obtenir el llistat de sol·licituds de follow pendents d'acceptar de l'usuari",
     *     description="Obtenir el llistat de sol·licituds de follow pendents d'acceptar de l'usuari",
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="valor del token_access per poder utilitzar el endpoint",
     *         required=true
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="'error' => 'usuari no trobat a la base de dades'"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="'message' => 'Configuració guardada conrrectament.'"
     *     )
     * )
    */
    public function follow_requests(Request $request, $username){
        $user = User::where('username', $username)->first();
        if($user === null ) return response()->json(['error' => 'usuari no trobat a la base de dades'], 404);
        $users_follow_request = User::with('followers.user_follower')->where('username', $username)->first();
        $users_follow_request = collect($users_follow_request->followers)->map(function ($value, $key) {
            if($value->pending === 1){
                return $value;
            }
        });
        $users_follow_request = $users_follow_request->filter(function ($value, $key) {
            return $value !== null;
        });
        //dd($users_follow_request, $users_follow_request->toArray());
        return response()->json([
            'value' => $users_follow_request
        ], 200);
    }
}

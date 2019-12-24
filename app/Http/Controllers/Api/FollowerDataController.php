<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Follower;
use App\User;

class FollowerDataController extends Controller
{
    /** @OA\Post(
     *     path="/api/follow/user/{username}",
     *     tags={"follow"},
     *     summary="Seguir a un usuari",
     *     description="Dado un username del usuari a seguir, es crea la relació en la taula followers.",
     *     @OA\Response(
     *         response=200,
     *         description="Devuelve un json con el mensaje: 'Has començat a seguir a {username} =P'."
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Devuelve un json con el error: 'error en els paràmetres'."
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Devuelve un json con el error: 'Restricció: un usuari no pot seguir-se a ell mateix'."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Devuelve un json con el error: 'Usuari no trobat'."
     *     ),
     *     @OA\Parameter(
     *         name="user_follower",
     *         in="query",
     *         description="Username del usuari que fa l'acció de seguir a un altre usuari.",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access.",
     *         required=true
     *     )
     * )
    **/
    public function create(Request $request, $username) {
        $user_follower  = User::where('username', $request->follower_username)->first();
        $user_to_follow = User::where('username', $username)->first();
        if ($user_follower !== null && $user_to_follow !== null) {
            Follower::create([
                'id_follower' => $user_follower->id,
                'id_followed' => $user_to_follow->id,
            ]);
            return response()->json([
                'message' => 'Has començat a seguir a ' . $username . ' XP'
            ], 200);
        }
        return response()->json([
            'error' => 'Usuari no trobat.'
        ], 404);
    }

    /** @OA\Delete(
     *     path="/api/follow/user/{follower}/user/{followed}",
     *     tags={"follow"},
     *     summary="Deixar de seguir a un usuari",
     *     description="Dado un username del seguidor {follower} i un username d'usuari que seguix {followed}, s'esborra la relació de followers.",
     *     @OA\Response(
     *         response=200,
     *         description="Devuelve un json con el mensaje: 'Has deixat de seguir a {username} =G'."
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Devuelve un json con el error: 'error en els paràmetres'."
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Devuelve un json con el error: 'Restricció: un usuari no pot deixar de seguir-se a ell mateix'."
     *     ),
     *      @OA\Response(
     *         response=402,
     *         description="Devuelve un json con el error: 'No pots deixar de seguir a cap usuari que encara no segueix'."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Devuelve un json con el error: 'Usuari no trobat'."
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access.",
     *         required=true
     *     )
     * )
    **/
    public function delete($follower, $followed) {
        $user_follower  = User::where('username', $follower)->first();
        $user_followed = User::where('username', $followed)->first();

        if ($user_follower !== null && $user_followed !== null) {
            $unfollow = Follower::where('id_follower', $user_follower->id)
                            ->where('id_followed', $user_followed->id)->delete();
            if ($unfollow !== 0) {
                return response()->json([
                    'message' => 'Has deixat de seguir a ' . $user_followed->username . ' =G'
                ], 200);
            }
            return response()->json([
                'error' => 'No pots deixar de seguir a cap usuari que encara no segueix.'
            ], 402);
        }
        return response()->json([
            'error' => 'Usuari no trobat.'
        ], 404);
    }

    /** @OA\Get(
     *     path="/api/follow/user/{follower}/user/{followed}",
     *     tags={"follow"},
     *     summary="Comprova si un usuari segueix a un altre.",
     *     description="Dado un username del seguidor {follower} i un altre username {followed}, comprova si el segueix o no.",
     *     @OA\Response(
     *         response=200,
     *         description="Devuelve un json con el value: 'true' o 'false'."
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Devuelve un json con el error: 'error en els paràmetres'."
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Devuelve un json con el error: 'Restricció: un usuari no pot seguir-se a ell mateix."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Devuelve un json con el error: 'Usuari no trobat'."
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access.",
     *         required=true
     *     )
     * )
    **/
    public function is_following($follower, $followed) {
        $user_follower  = User::where('username', $follower)->first();
        $user_followed = User::where('username', $followed)->first();

        if ($user_follower !== null && $user_followed !== null) {
            $follow = Follower::where('id_follower', $user_follower->id)
                            ->where('id_followed', $user_followed->id)->first();
            return response()->json([
                'value' => ($follow !== null ? 'true' : 'false')
            ], 200);
        }
        return response()->json([
            'error' => 'Usuari no trobat.'
        ], 404);
    }

    /** @OA\Get(
     *     path="/api/user/{id}/followers",
     *     tags={"follow"},
     *     summary="Dado un id d'un usuari et retorna els seus seguidors.",
     *     description="Dado un id usuari et retorna un json amb les dades dels seus seguidors {id, username, photo_path}.",
     *     @OA\Response(
     *         response=200,
     *         description="Devuelve un json: followers: [ {id, username, photo_path} ]"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Devuelve un json con el error: 'error en els paràmetres'."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Devuelve un json con el missage: 'Aquest usuari no existeix o no té cap seguidor'."
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access.",
     *         required=true
     *     )
     * )
    **/
    public function get_followers($id_user) {
        $users = null;
        foreach (Follower::where('id_followed', $id_user)->cursor() as $follower) {
            $user = User::where('id', $follower->id_follower)->first();
            $users [] = ['id' => $user->id, 'username' => $user->username, 'photo_path' => $user->photo_path];
        }
        if ($users !== null) {
            $data = ['followers' => $users];
            return json_encode($data, JSON_PRETTY_PRINT);
        }
        return response()->json([
            'missage' => 'Aquest usuari no existeix o no té cap seguidor.'
        ], 404);
    }

    /** @OA\Get(
     *     path="/api/user/{id}/followed",
     *     tags={"follow"},
     *     summary="Dado un id d'un usuari et retorna els usuaris que segueix.",
     *     description="Dado un id usuari et retorna un json amb les dades dels usuaris que segueix {id, username, photo_path}.",
     *     @OA\Response(
     *         response=200,
     *         description="Devuelve un json: followed: [ {id, username, photo_path} ]"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Devuelve un json con el error: 'error en els paràmetres'."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Devuelve un json con el missage: 'Aquest usuari no existeix o no segueix a cap usuari'."
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access.",
     *         required=true
     *     )
     * )
    **/
    public function get_followed($id_user) {
        $users = null;
        foreach (Follower::where('id_follower', $id_user)->cursor() as $followed) {
            $user = User::where('id', $followed->id_followed)->first();
            $users [] = ['id' => $user->id, 'username' => $user->username, 'photo_path' => $user->photo_path];
        }
        if ($users !== null) {
            $data = ['followed' => $users];
            return json_encode($data, JSON_PRETTY_PRINT);
        }
        return response()->json([
            'missage' => 'Aquest usuari no existeix o no segueix a cap usuari.'
        ], 404);
    }

    public function get_id_followed($id_user) {
        return Follower::where('id_follower', $id_user)->get('id_followed');
    }
}

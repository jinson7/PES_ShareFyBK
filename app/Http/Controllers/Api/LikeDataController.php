<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Like;
use App\User;

class LikeDataController extends Controller
{
    public function __construct(){
        //$this->middleware('jwt');
    }
    
    /** @OA\Get(
     *     path="/api/publication/{id}/likes",
     *     tags={"like"},
     *     summary="Devuelve los usuarios que han dado like a una publicación.",
     *     description="Dado un id publicación, retorna un json con la información de los usuarios que han dado like a una publicación",
     *     @OA\Response(
     *         response=200,
     *         description="Devuelve un json users: [ {'id', 'username',  'first_name', 'photo_path'} ]."
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Devuelve un json con el error: 'error en els paràmetres'."
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access",
     *         required=true
     *     )
     * )
    **/
    public function get_info_user($id_publication){
        $likes = DB::table('likes')->join('users', 'likes.id_user', '=', 'users.id')
                    ->select('users.id', 'users.username', 'users.first_name', 'users.photo_path')
                    ->where('id_publication', $id_publication)->get();
        $data = ['users' => $likes->toArray()];
        return $data;
    }

    /** @OA\Get(
     *     path="/api/like/user/{username}/publication/{id_publication}",
     *     tags={"like"},
     *     summary="Comproba si un usuario ha dado like a una publicación",
     *     description="Dado un username y un id publicación retorna: 'true' o 'false', si el usuario ha dado like a esta publicación o no, respectivamente.",
     *     @OA\Response(
     *         response=200,
     *         description="Devuelve un json con el value: 'true' o 'false'."
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Devuelve un json con el error: 'error en els paràmetres'."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Devuelve un json con el error: 'usuari no trobat a la base de dades'.'"
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access",
     *         required=true
     *     )
     * )
    **/
    public function is_like($username, $id_publication){
        $user = User::where('username', $username)->first();
        if($user === null ) 
            return response()->json(['error' => 'usuari no trobat a la base de dades.'], 404);
        $res = DB::table('likes')->where([['id_user', $user->id],['id_publication', $id_publication]])->first();
        return response()->json([
            'value' => ($res !== null ? 'true' : 'false')
        ], 200);
    }


    /** @OA\Get(
     *     path="/api/likes/user/{id}/publications",
     *     tags={"like"},
     *     summary="Dado un id usuario devuelve las publicaciones a las que el usuario ha dado like.",
     *     description="Devuelve todas las publicaciones con toda su información, a las que un usuario a ha dado like.",
     *     @OA\Response(
     *         response=200,
     *         description="Devuelve un json con todos los datos de una publicación."
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Devuelve un json con el error: 'error en els paràmetres'."
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access",
     *         required=true
     *     )
     * )
    */
    public function get_id_publications_by_user($id_user) {
        $likes = Like::where('id_user', $id_user)->get('id_publication');
        return $likes;
    }

    /** @OA\Post(
     *     path="/api/like/user/{username}/publication/{id_publication}",
     *     tags={"like"},
     *     summary="dado un username se crea la relacion like con la publicacion id_publicacion",
     *     description="dado un username se crea la relacion like con la publicacion id_publicacion",
     *     @OA\Response(
     *         response=200,
     *         description="Devuelve un json con el mensaje: 'relació creada correctament'."
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Devuelve un json con el error: 'error en els paràmetres'."
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access",
     *         required=true
     *     )
     * )
    **/
    public function set_like($username, $id_publication){
        $user = User::where('username', $username)->first();
        Like::create([
            'id_user' => $user->id,
            'id_publication' => $id_publication,
        ]);
        return response()->json([
            'message' => 'relació creada correctament'
        ], 200);
    }

    /** @OA\Delete(
     *     path="/api/like/user/{username}/publication/{id_publication}",
     *     tags={"like"},
     *     summary="dado un username se elimina la relacion like con la publicacion id_publicacion",
     *     description="dado un username se elimina la relacion like con la publicacion id_publicacion",
     *     @OA\Response(
     *         response=200,
     *         description="Devuelve un json con el mensaje: 'relació eliminada correctament'."
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Devuelve un json con el error: 'error en els paràmetres'."
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access",
     *         required=true
     *     )
     * )
    **/
    public function unset_like($username, $id_publication){
        $user = User::where('username', $username)->first();
        DB::table('likes')->where([['id_user', $user->id],['id_publication', $id_publication]])->delete();
        return response()->json([
            'message' => 'relació eliminada correctament'
        ], 200);
    }
}

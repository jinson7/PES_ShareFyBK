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

    /** @OA\Post(
    *     path="/api/like/user/{username}/publication/{id_publication}",
    *     tags={"publication"},
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
     */
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
    *     tags={"publication"},
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
     */
    public function unset_like($username, $id_publication){
        $user = User::where('username', $username)->first();
        //$like = Like::where([['id_user', $user->id],['id_publication', $id_publication]])->first();
        DB::table('likes')->where([['id_user', $user->id],['id_publication', $id_publication]])->delete();
        return response()->json([
            'message' => 'relació eliminada correctament'
        ], 200);
    }
}

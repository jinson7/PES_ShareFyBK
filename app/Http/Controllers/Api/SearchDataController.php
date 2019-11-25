<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\User;
use App\Publication;
use App\Like;

class SearchDataController extends Controller
{
    /** @OA\Get(
     *     path="/api/search/{data}",
     *     tags={"app"},
     *     summary="Cerca en la aplicació d'usuaris i publicaciones",
     *     description="Donat una o unes paraules, es cerca en la aplicació usuaris i publicacions que coincideixen amb la o les paraules introduïdes, retornan un json amb dades d'usuaris {id,username,photo_path} i publicacions {id, text, likes, created_at}.",
     *     @OA\Response(
     *         response=200,
     *         description="Devuelve un json: { users: [ {id, username, photo_path} ], publications: [ {id, text, likes, created_at} ] }"
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
    public function search($data) {
        $users = User::where('username', 'like', '%'.$data.'%')->orderBy('username')->get(['id','username','photo_path']);
        $publications = Publication::select('id','text', 'created_at')
            ->without('game', 'user', 'comments')
            ->withCount('like as likes')
            ->where('text', 'like', '%'.$data.'%')
            ->orderBy('text')->get();
        $data_users = $users->toArray();
        $data = ['users' => $data_users,
                'publications' => $publications->toArray()];
        return json_encode($data, JSON_PRETTY_PRINT);
    }
}

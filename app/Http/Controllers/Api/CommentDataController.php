<?php

namespace App\Http\Controllers\Api;

use App\Comment;
use App\User;

class CommentDataController
{
    /** @OA\Post(
     *     path="/api/comment/user/{username}/publication/{id_publication}",
     *     tags={"comment"},
     *     summary="Crea un comentari a una publicació",
     *     description="dado un username, un id publicació, un texto del comentari y la data actual del comentari, es crea un comentari",
     *     @OA\Response(
     *         response=200,
     *         description="Devuelve un json con el mensaje: 'Comentari crear correctament'."
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Devuelve un json con el error: 'error en els paràmetres'."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Devuelve un json con el error: 'Usuari no trobat'."
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access",
     *         required=true
     *     )
     * )
    **/
    public function create($request, $username, $id_publication){
        $user = User::where('username', $username)->first();
        if ($user !== null) {
            Comment::create([
                'id_user' => $user->id,
                'id_publication' => $id_publication,
                'date' => $request->date,
                'text' => $request->text,
            ]);
            return response()->json([
                'message' => 'Comentari crear correctament.'
            ], 200);
        }
        return response()->json([
            'error' => 'Usuari no trobat.'
        ], 404);
    }
}

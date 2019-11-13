<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Comment;
use App\User;

class CommentDataController extends Controller
{

    /** @OA\Post(
     *     path="/api/comment/user/{username}/publication/{id_publication}",
     *     tags={"comment"},
     *     summary="Crea un comentari a una publicació",
     *     description="Dado un username, un id publicació, un texto del comentari y la data actual del comentari, es crea un comentari",
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
     *         name="text",
     *         in="query",
     *         description="Text del comentari a fer.",
     *         required=true
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

    /** @OA\Get(
     *     path="/api/comment/{id}",
     *     tags={"comment"},
     *     summary="Obté un comentari de una publicació",
     *     description="Dado un id comentari existent retorna les dades del comentari, cas contrari retorna un error.",
     *     @OA\Response(
     *         response=200,
     *         description="Devuelve un json amb les dades del comentari."
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Devuelve un json con el error: 'error en els paràmetres'."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Devuelve un json con el error: 'No existeix cap comentari amb l'id introduit'."
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access",
     *         required=true
     *     )
     * )
    **/
    public function get($id_publication){
        $comment = Comment::find($id_publication);
        if ($comment !== null) {
            return response()->json([
                'value' => $comment
            ], 200);
        }
        else {
            return response()->json([
                'error' => "No existeix cap comentari amb l'id introduit."
            ], 404);
        }
    }

    /** @OA\Delete(
     *     path="/api/comment/{id}",
     *     tags={"comment"},
     *     summary="Elimina un comentari de una publicació",
     *     description="Dado id comentari existent elimina el comentari de la base de dades, cas contrari retorna un error.",
     *     @OA\Response(
     *         response=200,
     *         description="Devuelve un json amb el messaje: Comentari eliminat correctament."
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Devuelve un json con el error: 'error en els paràmetres'."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Devuelve un json con el error: 'No existeix cap comentari amb l'id introduit'."
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access",
     *         required=true
     *     )
     * )
    **/
    public function delete($id_publication){
        $comment = Comment::find($id_publication);
        if ($comment !== null) {
            $comment->delete();
            return response()->json([
                'message' => "Comentari eliminat correctament."
            ], 200);
        }
        else {
            return response()->json([
                'error' => "No existeix cap comentari amb l'id introduit."
            ], 404);
        }
    }
}

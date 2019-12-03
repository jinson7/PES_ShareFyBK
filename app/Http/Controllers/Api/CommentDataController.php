<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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

    /** @OA\Get(
     *     path="/api/comments/publication/{id}",
     *     tags={"comment"},
     *     summary="Devuelve los comentarios de una publicación.",
     *     description="Dado un id publicación, retorna un json con los comentarios y datos del usuario la publicación",
     *     @OA\Response(
     *         response=200,
     *         description="Devuelve un json comments: [ {'id_comment', 'text', 'created_at', 'id_user', 'username',  'first_name', 'photo_path'} ]."
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
    public function get_comments($id_publication){
        $comments = DB::table('comments')->join('users', 'comments.id_user', '=', 'users.id')
                    ->select('comments.id as id_comment', 'comments.text', 'comments.created_at', 'users.id as id_user', 
                            'users.username', 'users.first_name', 'users.photo_path')
                    ->where('id_publication', $id_publication)->orderBy('comments.created_at', 'DESC')->get();
        $data = ['comments' => $comments->toArray()];
        return $data;
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

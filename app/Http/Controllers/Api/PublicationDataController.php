<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use App\Publication;
use App\User;
use App\Like;

class PublicationDataController extends Controller
{

    /**
      * @OA\Get(
      *     path="/api/publication/{id}",
      *     tags={"publication"},
      *     summary="Dado un id de publicación existente, devuelve su información.",
      *     description="Dado un id de publicación existente, devuelve su información.",
      *     @OA\Response(
      *         response=200,
      *         description="Devuelve un json con la información de la publicación."
      *     ),
      *     @OA\Parameter(
      *         name="token",
      *         in="query",
      *         description="Valor del token_access",
      *         required=true
      *     )
      * )
    */
    public function show($id) {
        $publication = Publication::where('id', $id)->first();
        if ($publication !== null) {
            $likes = Like::with('user:id,username')->where('id_publication', $publication->id)->get();
            $list_usernames_like = $likes->implode('user.username', ',');
            $publication->likes = explode(',', $list_usernames_like);
        }
        return response()->json([
            'value' => $publication
        ], 200);
    }

    /**
      * @OA\Get(
      *     path="/api/user/{id_user}/publications",
      *     tags={"publication"},
      *     summary="Dado un id de usuario existente, devuelve todas sus publicaciones",
      *     description="Dado un id de usuario existente, devuelve todas sus publicaciones",
      *     @OA\Response(
      *         response=200,
      *         description="Devuelve un json con la información de la publicación."
      *     ),
      *     @OA\Parameter(
      *         name="token",
      *         in="query",
      *         description="Valor del token_access",
      *         required=true
      *     )
      * )
    */
    public function list_user($id_user){
        $publications = Publication::where('id_user', $id_user)->orderBy('created_at', 'DESC')->get();
        return response()->json([
            'value' => $publications
        ], 200);
    }

    /**
      * @OA\Get(
      *     path="/api/user/{id}/wall",
      *     tags={"publication"},
      *     summary="Dado un id de usuario existente, devuelve las publicaciones más recientes de todos sus seguidos y las suyas (Muro).",
      *     description="Dado un id de usuario, devuelve toda la información de todas las publicaciones recientes que han hecho los usuarios al cual sigue y las suyas propias",
      *     @OA\Response(
      *         response=200,
      *         description="Devuelve un json con la información de la publicación, users, likes, comentarios, game"
      *     ),
      *     @OA\Parameter(
      *         name="token",
      *         in="query",
      *         description="Valor del token_access",
      *         required=true
      *     )
      * )
    */
    public function wall($followed) {
        $wall = Publication::whereIn('id_user', $followed)->orderBy('created_at','desc')->get();
        return response()->json([
            'value' => $wall
        ], 200);
    }

    public function get_publications($id_publications) {
        $publications = Publication::whereIn('id', $id_publications)->without('like', 'comments')->orderBy('created_at', 'DESC')->get();
        return response()->json([
            'value' => $publications
        ], 200);
    }

    /**
      * @OA\Post(
      *     path="/api/publication",
      *     tags={"publication"},
      *     summary="Es crea la publicació amb la informació enviada.",
      *     description="Es crea la publicació amb la informació enviada.",
      *     @OA\Response(
      *         response=200,
      *         description="Retorna un json amb el identificador de la publicació"
      *     ),
      *     @OA\Response(
      *         response=401,
      *         description="El vídeo no té un format vàlid, format suportat .mp4"
      *     ),
      *     @OA\Parameter(
      *         name="id_user",
      *         in="query",
      *         description="string amb el id de l'usuari",
      *         required=true
      *     ),
      *     @OA\Parameter(
      *         name="game",
      *         in="query",
      *         description="id del game",
      *         required=true
      *     ),
      *     @OA\Parameter(
      *         name="video",
      *         in="query",
      *         description="valor del video en el form",
      *         required=true
      *     ),
      *     @OA\Parameter(
      *         name="text",
      *         in="query",
      *         description="string amb el text de la publicació",
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
    public function create(Request $request, $file, $ext) {
        $publication = Publication::create([
            'id_user' => $request->id_user,
            'id_game' => $request->game,
            'text' => $request->text
        ]);
        // crear ruta per el clip
        $id_publication = str_pad($publication->id, 3, "0", STR_PAD_LEFT);
        $path = '/media/clips/'.$id_publication[0].'/'.$id_publication[1].'/'.$id_publication[2].'/';
        $date = now()->timestamp;
        $name_file = $date . '.' . $ext;
        Storage::disk('public')->putFileAs($path, $file, $name_file);
        $publication->video_path = '/storage'.$path.$name_file;
        $publication->save();
        return response()->json([
            'value' => $publication->id
        ], 200);
    }

    /** 
      * @OA\Put(
      *     path="/api/publication/{id}",
      *     tags={"publication"},
      *     summary="Dado un id de publicación existente, edita dicha publicación.",
      *     description="Dado un id de publicación existente, y los campos a modificados guarda la dicha informacioón.",
      *     @OA\Response(
      *         response=200,
      *         description="Devuelve un json con el mensaje: 'Publicació editada correctament'."
      *     ),
      *     @OA\Response(
      *         response=400,
      *         description="Devuelve un json con el error: 'No existe la publicación a editar'."
      *     ),
      *     @OA\Parameter(
      *         name="game",
      *         in="query",
      *         description="Id del juego",
      *     ),
      *     @OA\Parameter(
      *         name="text",
      *         in="query",
      *         description="Texto descriptivo de la publicación",
      *     ),
      *     @OA\Parameter(
      *         name="token",
      *         in="query",
      *         description="Valor del token_access",
      *         required=true
      *     )
      * )
    */
    public function update(Request $request, $id)
    {
        $publication = Publication::find($id);
        if ($publication !== null) {
            if ($request->game !== null) $publication->id_game = $request->game;
            if ($request->text !== null) $publication->text = $request->text;
            $publication->save();
            return response()->json([
                'message' => 'Publicació editada correctament.'
            ], 200);
        }
        return response()->json([
            'error' => 'No existe la publicación a editar.'
        ], 400);
    }

    /** 
     * @OA\DELETE(
     *     path="/api/publication/{publication_id}",
     *     tags={"publication"},
     *     summary="Dado un id de publicación existente, elimina dicha publicación.",
     *     description="Dado un id de publicación existente, elimina dicha publicación.",
     *     @OA\Response(
     *         response=200,
     *         description="Devuelve un json con el mensaje: 'Publicació eliminada correctament'."
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Devuelve un json con el error: 'No existeix la publicació a eliminar'."
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access",
     *         required=true
     *     )
     * )
    */
    public function delete($id)
    {
        $publication = Publication::find($id);
        if ($publication !== null) {
            $video_path = $publication->video_path;
            // quitamos el /storage de el video_path
            $video_path = substr($publication->video_path, 8);
            
            if(Storage::disk('public')->exists($video_path)){
                Storage::disk('public')->delete($video_path);
            }
            $publication->delete();

            return response()->json([
                'message' => 'Publicació eliminada correctament.'
            ], 200);
        }
        return response()->json([
            'error' => 'No existeix la publicació a eliminar.'
        ], 400);
    }
}

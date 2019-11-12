<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use App\Publication;
use App\User;
use App\Like;

class PublicationController extends Controller
{
    
    public function __construct(){
        //$this->middleware('jwt');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function list_publication_user($id_user){
        $publications = Publication::with('game', 'user:id,username,photo_path')
                        ->withCount('like AS num_likes')
                        ->where('id_user', $id_user)->get();
        return response()->json([
            'value' => $publications
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/api/publication",
     *     tags={"publication"},
     *     summary="Es crea la publicació amb la informació enviada.",
     *     description="Es crea la publicació amb la informació enviada.",
     *     @OA\Response(
     *         response=200,
     *         description="Retorna un json amb el missatge 'operació correcta' "
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
    public function store(Request $request)
    {
        // agafar el video
        $file = $request->file('video');
        $ext = $file->getClientOriginalExtension();
        if ( $ext === 'mp4' ) {
            // crear publicació
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
                'message' => 'Publicació creada correctament.'
            ], 200);
        }else{
            return response()->json([
                'error' => "El vídeo no té un format vàlid, format suportat .mp4"
            ], 401);
        }
        
    }

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
        $publication = Publication::with('game', 'user:id,username,photo_path')->where('id', $id)->first();
        //$publication->user = User::select('username', 'photo_path')->where('id', $publication->id_user)->first();
        $likes = Like::with('user:id,username')->where('id_publication', $publication->id)->get();
        $list_usernames_like = $likes->implode('user.username', ',');
        $publication->num_likes = $likes->count();
        $publication->likes = explode(',', $list_usernames_like);
        return response()->json([
            'value' => $publication
        ], 200);
    }

    public function edit(Request $request, $id) {
    }

    /** @OA\Put(
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

    /** @OA\DELETE(
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
    public function destroy($id)
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

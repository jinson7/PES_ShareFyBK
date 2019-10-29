<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use App\Publication;

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
        $publications = Publication::where('id_user', $id_user)->get();
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
        $publication = Publication::select('*')->where('id', $id)->first();
        return response()->json([
            'value' => $publication
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

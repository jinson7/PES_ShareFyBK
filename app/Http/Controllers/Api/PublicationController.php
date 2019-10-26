<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Publication;

class PublicationController extends Controller
{
    
    public function __construct(){
        $this->middleware('jwt');
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
     *     path="/api/publication?token=valor",
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
     *         description="string amb el valor del game",
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
                'game' => $request->game,
                'text' => $request->text
            ]);

            // crear ruta per el clip
            $id_publication = str_pad($publication->id, 3, "0", STR_PAD_LEFT);
            $path = '/media/clips/'.$id_publication[0].'/'.$id_publication[1].'/'.$id_publication[2].'/';
        
            $date = now()->timestamp;
            $name_file = $date . '.' . $ext;
            $file->move(public_path($path), $name_file);
            $publication->video_path = $path.$name_file;
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

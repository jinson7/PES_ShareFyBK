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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    /** @OA\GET(
    *     path="/api/publication/{id}/edit",
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
    *         description="Nombre del juego",
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
    public function edit(Request $request, $id) {
        $publication = Publication::find($id);
        if ($publication !== null) {
            if ($request->game !== null) $publication->game = $request->game;
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

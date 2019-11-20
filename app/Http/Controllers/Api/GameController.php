<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Game;
use App\Publication;

class GameController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/games",
     *     tags={"game"},
     *     summary="Retrona un llistat amb tots els games existents a la base de dades",
     *     description="Retrona un llistat amb tots els games existents a la base de dades",
     *     @OA\Response(
     *         response=200,
     *         description="Retrona un json amb la llista de games"
     *     )
     * )
    */
    public function index(){
        return response()->json([
            'value' => Game::all()
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/game/{id}/publications",
     *     tags={"game"},
     *     summary="Retrona un llistat amb totes les publicacions del joc amb la id indicada a la url",
     *     description="Retrona un llistat amb totes les publicacions del joc amb la id indicada a la url",
     *     @OA\Response(
     *         response=200,
     *         description="Retrona un json amb la llista de games"
     *     )
     * )
    */
    public function game_publications($id){
        return response()->json([
            'value' => Publication::with(['user:id,username,photo_path', 
                                    'comments.user:id,username,photo_path'])
                                ->publicationsFromGame($id)->get()
        ], 200);
    }
}

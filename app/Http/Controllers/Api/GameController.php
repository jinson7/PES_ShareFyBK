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
     *         description="Retrona un json amb la llista de publicacions d'un game"
     *     )
     * )
    */
    public function game_publications($id){
        return response()->json([
            'value' => Publication::publicationsFromGame($id)->orderBy('num_likes', 'desc')->get()
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/game/{id}/lang/{lang}",
     *     tags={"game"},
     *     summary="Retrona un llistat amb la info del joc {id} en l'idioma {lang} (ca, es, en)",
     *     description="Retrona un llistat amb la info del joc {id} en l'idioma {lang} (ca, es, en)",
     *     @OA\Response(
     *         response=200,
     *         description="Retrona un json amb la info de un game"
     *     )
     * )
    */
    public function game_info_lang($id, $lang){
        $game = Game::whereId($id);
        if($lang === "ca") $game->select('id', 'name_ca as name', 'description_ca as description', 'image_url');
        if($lang === "es") $game->select('id', 'name_es as name', 'description_es as description', 'image_url');
        if($lang === "en") $game->select('id', 'name_en as name', 'description_en as description', 'image_url');
        
        return response()->json([
            'value' => $game->first()
        ], 200);
    }
}

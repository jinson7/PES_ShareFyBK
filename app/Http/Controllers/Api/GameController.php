<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Game;

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
}
